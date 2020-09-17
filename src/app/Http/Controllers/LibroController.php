<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

use App\Models\Libro;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function index()
    {
        return response()->json(Libro::all());
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $titulo = $request->input('titulo');
        $autor = $request->input('autor');
        $file = $request->file('portada');
        $image = null;
        
        if($file){
            $image = $this->storeDocument($file);
        } else {
            $image = (object) array("name" => null, "key" => null);
        }

        $libro = Libro::create([
            "titulo" => $titulo,
            "autor" => $autor,
            "uri" => $image->name,
            "uri_key" => $image->key
        ]);

        return $libro;
    }

    public function storeDocument($file)
    {
        $filename = $this->generateFileName($file);
        Storage::disk('imagenes')->put($filename, File::get($file));

        if (Storage::disk('imagenes')->exists($filename)) {
            return (object) array(
                'name' => $filename,
                'key' => Carbon::now()->timestamp
            );
        } else {
            abort(403); //TODO : Pendiente verificar casos de error
        }
    }

    public function generateFileName($file){
        $extension = $file->getClientOriginalExtension();
        $filename = 'newfile.img';

        do {
            $filename = rand(1999999, 99999999) . '.' . $extension;
        } while (Storage::disk('imagenes')->exists($filename));

        return $filename;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $libro = Libro::where('id', $id)->get()->first();
        return response()->json($libro);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $titulo = $request->input('titulo');
        $autor = $request->input('autor');

        $libro = Libro::where('id', $id)->get()->first();

        $result = $libro->update([
            "titulo" => $titulo,
            "autor" => $autor
        ]);

        if ($result) {
            return response()->json($libro);
        } else{
            return response()->json(null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $libro = Libro::where('id', $id)->get()->first();

        if ($libro && $libro->delete()) {
            return response()->json(array('status' => true));
        } else{
            return response()->json(array('status' => false));
        }
    }


    public function getImage($id, $filename, $key)
    {
        $libro = Libro::where([
            [ 'id', $id ],
            [ 'uri', $filename ],
            [ 'uri_key', $key ],
        ])->get();

        if($libro->count() == 1){
            $libro = $libro->first();
            if (Storage::disk('imagenes')->exists($libro->uri)) {
                return Storage::disk('imagenes')->response($libro->uri);
            } else {
                abort(404);
            }

        }

        abort(404);
    }


}
