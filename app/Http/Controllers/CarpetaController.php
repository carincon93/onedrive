<?php

namespace App\Http\Controllers;

use App\Helpers\OneDrive;
use App\Http\Requests\CarpetaRequest;
use App\Models\Carpeta;
use Illuminate\Http\Request;

class CarpetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages/carpetas/Index', [
            'carpetas' => Carpeta::where('children_id', null)->orderBy('id', 'ASC')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages/carpetas/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarpetaRequest $request)
    {
        if (Carpeta::where('nombre', $request->nombre)->count() == 0) {
            $folderId = OneDrive::createRootFolder($request->nombre);
            $carpeta = new Carpeta();
            $carpeta->nombre        = $request->nombre;
            $carpeta->onedrive_id   = $folderId;

            $carpeta->save();

            return redirect()->route('carpetas.index')->with('success', 'El recurso se ha creado correctamente.');
        }

        return redirect()->route('carpetas.index')->with('error', 'No se ha podido crear el recurso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function show(Carpeta $carpeta)
    {
        return view('pages/carpetas/Show', [
            'carpetas_hijas'    => Carpeta::where('children_id', $carpeta->id)->get(),
            'carpeta'           => $carpeta->load('archivos')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function edit(Carpeta $carpeta)
    {
        return view('pages/carpetas/Edit', [
            'carpeta' => $carpeta
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function update(CarpetaRequest $request, Carpeta $carpeta)
    {
        $carpeta->nombre = $request->nombre;

        $carpeta->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Carpeta $carpeta)
    {
        OneDrive::deleteFolder($carpeta);

        foreach ($carpeta->archivos as $archivo) {
            $archivo->delete();
        }

        $carpeta->delete();

        return back()->with('success', 'El recurso se ha eliminado correctamente.');
    }

    public function carpetasHijasStore(CarpetaRequest $request, Carpeta $carpeta)
    {
        if (Carpeta::where('nombre', $request->nombre)->count() == 0) {
            $folderId = OneDrive::createFolder($carpeta->onedrive_id, $request->nombre);
            $carpetaHija = new Carpeta();
            $carpetaHija->nombre        = $request->nombre;
            $carpetaHija->children_id   = $carpeta->id;
            $carpetaHija->onedrive_id   = $folderId;

            $carpetaHija->save();

            return back()->with('success', 'El recurso se ha creado correctamente.');
        }

        return back()->with('error', 'No se ha podido crear el recurso.');
    }

    public function getFolderId($carpeta)
    {
        foreach ($carpeta->carpetas()->get() as $carp) {
            dd($carp);
        }
        return $carpeta->id;
    }
}
