<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PersonaRequest;
use App\Http\Requests\PersonaStoreRequest;
use App\Http\Requests\PersonaUpdateRequest;
use App\Http\Resources\PersonaResource;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PersonaRequest $request)
    {
        // validate request
        $validated = $request->validated();

        // build conditional query
        $personas = Persona::query()->select('id', 'nombre', 'email', 'telefono');

        if($validated['filters']) {

            if(isset($validated['nombre'])) {
                $personas->where('nombre', 'like', '%' . $validated['nombre'] . '%');
            }
            if(isset($validated['email'])) {
                $personas->where('email', 'like', '%' . $validated['email'] . '%');
            }
        }

        if(isset($validated['sort'])) {
            $personas->orderBy($validated['sort'], $validated['sort_asc'] ? "asc" : "desc");
        }

        $personas = $validated['pagination'] ? $personas->paginate($validated['elements_per_page']) : $personas->get();
        return $this->sendResponse($personas, "List of Personas");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonaStoreRequest $request)
    {
        // validate request
        $validated = $request->validated();
        // add record in database
        $persona = Persona::create($validated);
        // send response
        return $this->sendResponse(new PersonaResource(Persona::findOrFail($persona->id)), 'Persona created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find related resource
        try {
            $persona = Persona::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Persona not found", []);
        }
        // send response
        return $this->sendResponse(new PersonaResource($persona), "");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonaUpdateRequest $request, int $id)
    {
        // validate request
        $validated = $request->validated();

        // find related resource
        try {
            $persona = Persona::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Persona not found", []);
        }

        // additional validation
        if(Persona::where([
            ['email', $validated['email']],
            ['id', '!=', $persona->id]
        ])->exists()){
            return $this->sendError("Email in use.", []);
        }

        // update resource
        $persona->update($validated);
        // send response
        return $this->sendResponse(new PersonaResource($persona), 'Persona updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // find related resource
        try {
            $persona = Persona::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Persona no encontrada", []);
        }
        $persona->delete();
        return $this->sendResponse([], 'Persona deleted successfully');
    }
}
