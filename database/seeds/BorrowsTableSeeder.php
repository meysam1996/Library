<?php

use Illuminate\Database\Seeder;

class BorrowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Borrow::class, 10)->create()->each(function ($book) {
            $book->books()->attach(factory(\App\Book::class)->create()->id);
        })->unique();
    }
}
