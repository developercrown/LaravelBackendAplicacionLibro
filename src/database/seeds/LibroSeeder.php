<?php

use App\Libro;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    public function run()
    {
        $this->create('El cid cantador', 'rodrigo díaz vivar');
        $this->create('Cien años de soledad', 'gabriel garcia marquez');
        $this->create('Anillos', 'J.R.R Tolkien');
        $this->create('1984', 'George Orwell');
        $this->create('Un mundo feliz', 'Aldous Huxley');
        $this->create('Orgullo y prejuicio', 'Jane Austen');
    }

    public function create($titulo, $autor)
    {
        Libro::create([
            'id' => null,
            'titulo' => $titulo,
            'autor' => $autor
        ]);
    }
}
