<?php

namespace App\Http\Controllers;

use App\Helpers\OneDrive;
use App\Http\Requests\ArchivoRequest;
use App\Models\Archivo;
use App\Models\Carpeta;
use Illuminate\Http\Request;

class ArchivoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArchivoRequest $request, Carpeta $carpeta)
    {
        $onedriveid = OneDrive::uploadFile($carpeta->onedrive_id, $request->file, $request->file->getClientOriginalName());

        $archivo = new Archivo();
        $archivo->nombre        = $request->file->getClientOriginalName();
        $archivo->onedrive_id   = $onedriveid;
        $archivo->carpeta_id    = $carpeta->id;

        $archivo->save();

        return back()->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function update(ArchivoRequest $request, Archivo $archivo)
    {
        $archivo->fieldName = $request->fieldName;
        $archivo->fieldName = $request->fieldName;
        $archivo->fieldName = $request->fieldName;

        $archivo->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Archivo $archivo)
    {
        OneDrive::deleteFile($archivo);

        $archivo->delete();

        return back()->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * Download the specified resource from storage.
     *
     * @param  \App\Models\Archivo  $archivo
     * @return \Illuminate\Http\Response
     */
    public function download(Archivo $archivo)
    {
        return OneDrive::downloadFile($archivo);
    }
}
