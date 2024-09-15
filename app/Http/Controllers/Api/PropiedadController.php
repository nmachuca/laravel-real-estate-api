<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropiedadRequest;
use App\Http\Requests\PropiedadStoreRequest;
use App\Http\Requests\PropiedadUpdateRequest;
use App\Http\Resources\PersonaResource;
use App\Http\Resources\PropiedadResource;
use App\Models\Persona;
use App\Models\Propiedad;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PropiedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PropiedadRequest $request)
    {
        // validate request
        $validated = $request->validated();

        // build conditional query
        $propiedades = Propiedad::query()->select('id', 'direccion', 'ciudad', 'precio', 'descripcion');

        if($validated['filters']) {

            if(isset($validated['direccion'])) {
                $propiedades->where('direccion', 'like', '%' . $validated['direccion'] . '%');
            }
            if(isset($validated['ciudad'])) {
                $propiedades->where('ciudad', 'like', '%' . $validated['ciudad'] . '%');
            }

            if(isset($validated['precio_min'])) {
                $propiedades->where('precio', '>=', $validated['precio_min'] );
            }

            if(isset($validated['precio_max'])) {
                $propiedades->where('precio', '<=', $validated['precio_max'] );
            }
        }

        if(isset($validated['sort'])) {
            $propiedades->orderBy($validated['sort'], $validated['sort_asc'] ? "asc" : "desc");
        }

        $propiedades = $validated['pagination'] ? $propiedades->paginate($validated['elements_per_page']) : $propiedades->get();
        return $this->sendResponse($propiedades, "List of Propiedades");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PropiedadStoreRequest $request)
    {
        // validate request
        $validated = $request->validated();
        // add record in database
        $propiedad = Propiedad::create($validated);
        // send response
        return $this->sendResponse(new PropiedadResource(Propiedad::findOrFail($propiedad->id)), 'Propiedad created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find related resource
        try {
            $propiedad = Propiedad::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Propiedad not found", []);
        }
        // send response
        return $this->sendResponse(new PropiedadResource($propiedad), "");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PropiedadUpdateRequest $request, string $id)
    {
        // validate request
        $validated = $request->validated();

        // find related resource
        try {
            $propiedad = Propiedad::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Propiedad not found", []);
        }

        // additional validation
        if(Propiedad::where([
            ['direccion', $validated['direccion']],
            ['id', '!=', $propiedad->id]
        ])->exists()){
            return $this->sendError("Direccion in use.", []);
        }

        // update resource
        $propiedad->update($validated);
        // send response
        return $this->sendResponse(new PropiedadResource($propiedad), 'Propiedad updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // find related resource
        try {
            $propiedad = Propiedad::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Propiedad not found", []);
        }
        $propiedad->delete();
        return $this->sendResponse([], 'Propiedad deleted successfully');
    }
}
