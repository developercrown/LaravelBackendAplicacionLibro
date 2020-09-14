<?php

namespace App\Http\Controllers;

use App\Libro;
use Illuminate\Http\Request;

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

        $libro = Libro::create([
            "titulo" => $titulo,
            "autor" => $autor
        ]);

        return $libro;
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
}
