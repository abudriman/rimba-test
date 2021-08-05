<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->bigInteger('kontak');
            $table->string('email')->unique();
            $table->string('alamat');
            $table->integer('diskon');
            $table->string('tipe_diskon');
            $table->string('foto_ktp');
        });
    }

    // id
	// nama
	// contact
	// email
	// alamat
	// diskon
	// tipe_diskon (persentase/fix?)
	// ktp (image?/blob)

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
