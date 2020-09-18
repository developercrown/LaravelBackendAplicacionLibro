<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

// use Image;
use Intervention\Image\ImageManagerStatic as Image;

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
        $imageFile = File::get($file);

        $hdFolderPath = 'hd/';
        $thumbFolderPath = 'thumb/';

        Storage::disk('imagenes')->put($hdFolderPath.$filename, $imageFile);
        Storage::disk('imagenes')->put($thumbFolderPath.$filename, $imageFile);

        if (Storage::disk('imagenes')->exists($hdFolderPath.$filename) && Storage::disk('imagenes')->exists($thumbFolderPath.$filename)) {

            $sourceFileStored = Storage::disk('imagenes')->path($thumbFolderPath.$filename);
            $img = Image::make($sourceFileStored);

            $img->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($sourceFileStored);

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

    public function createThumbnail($path, $width, $height) {
        $img = Image::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
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


    public function getImage($id, $filename, $key, $definition)
    {
        $libro = Libro::where([
            [ 'id', $id ],
            [ 'uri', $filename ],
            [ 'uri_key', $key ],
        ])->get();

        if($libro->count() == 1){
            $libro = $libro->first();
            if (Storage::disk('imagenes')->exists($definition."/".$libro->uri)) {
                return Storage::disk('imagenes')->response($definition."/".$libro->uri);
            } else {
                abort(404);
            }

        }

        abort(404);
    }


}
