<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name", 255);
            $table->text("summary")->nullable();
            $table->text("description")->nullable();
            $table->unsignedMediumInteger("printer_key");
            $table->unsignedBigInteger("serial_number");

            # Foreign Keys
            $table->unsignedBigInteger("publisher_id")->nullable();
            $table->unsignedBigInteger("subject_id")->nullable();
            $table->foreign("publisher_id")->references("id")->on("publishers")->onDelete("set null")->onUpdate("cascade");
            $table->foreign("subject_id")->references("id")->on("subjects")->onDelete("set null")->onUpdate("cascade");

            # Unique and index fields
            $table->unique(['printer_key','serial_number']);

            # Create_at and update_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
