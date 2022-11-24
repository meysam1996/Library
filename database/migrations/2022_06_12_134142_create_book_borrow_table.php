<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookBorrowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_borrow', function (Blueprint $table) {
            $table->unsignedBigInteger("book_id")->nullable();
            $table->unsignedBigInteger("borrow_id");

            $table->foreign("book_id")->references("id")->on("books")
                ->onDelete("restrict")->onUpdate("cascade");
            $table->foreign("borrow_id")->references("id")->on("borrows")
                ->onDelete("cascade")->onUpdate("cascade");

            $table->unique(['book_id', 'borrow_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_borrow');
    }
}
