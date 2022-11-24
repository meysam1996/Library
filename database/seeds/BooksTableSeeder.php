<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Book::class, 10)->create()->each(function ($book) {
            $book->authors()->attach(random_int(1, count(\App\Author::all())));
        });
    }
}
