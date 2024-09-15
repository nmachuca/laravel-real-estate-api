<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisitaPropiedadRequest;
use App\Http\Requests\VisitaPropiedadStoreRequest;
use App\Http\Resources\VisitaPropiedadResource;
use App\Models\VisitaPropiedad;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VisitaPropiedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VisitaPropiedadRequest $request)
    {
        // validate request
        $validated = $request->validated();

        // build conditional query
        $visitas = VisitaPropiedad::query();

        if($validated['filters']) {

            if(isset($validated['propiedad_id'])) {
                $visitas->where('propiedad_id', $validated['propiedad_id']);
            }
            if(isset($validated['persona_id'])) {
                $visitas->where('persona_id', $validated['persona_id']);
            }

            if(isset($validated['fecha_min'])) {
                $visitas->where('fecha_visita', '>=', $validated['fecha_min'] );
            }

            if(isset($validated['fecha_max'])) {
                $visitas->where('fecha_visita', '<=', $validated['fecha_max'] );
            }
        }

        if(isset($validated['sort'])) {
            $visitas->orderBy($validated['sort'], $validated['sort_asc'] ? "asc" : "desc");
        }

        $visitas = $validated['pagination'] ? $visitas->paginate($validated['elements_per_page']) : $visitas->get();
        return $this->sendResponse($visitas, "List of Visitas");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VisitaPropiedadStoreRequest $request)
    {
        // validate request
        $validated = $request->validated();
        // add record in database
        $visita_propiedad = VisitaPropiedad::create($validated);
        if(isset($validated['comentarios'])) {
            $visita_propiedad->comentarios = $validated['comentarios'];
            $visita_propiedad->save();
        }
        // send response
        return $this->sendResponse(new VisitaPropiedadResource(VisitaPropiedad::findOrFail($visita_propiedad->id)), 'Visita created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // find related resource
        try {
            $visita_propiedad = VisitaPropiedad::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Visita not found", []);
        }
        // send response
        return $this->sendResponse(new VisitaPropiedadResource($visita_propiedad), "");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VisitaPropiedadStoreRequest $request, string $id)
    {
        // validate request
        $validated = $request->validated();

        // find related resource
        try {
            $visita_propiedad = VisitaPropiedad::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Visita not found", []);
        }

        // update resource
        $visita_propiedad->update($validated);
        // send response
        return $this->sendResponse(new VisitaPropiedadResource($visita_propiedad), 'Visita updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // find related resource
        try {
            $visita_propiedad = VisitaPropiedad::findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ["method" => __METHOD__, "line" => $th->getLine(), "trace" => $th->getTrace()]);
            return $this->sendError("Visita not found", []);
        }
        $visita_propiedad->delete();
        return $this->sendResponse([], 'Visita deleted successfully');
    }
}
