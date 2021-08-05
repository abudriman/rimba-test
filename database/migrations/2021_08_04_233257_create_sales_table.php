<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            //tanggal_transaksi pake created_at
            $table->timestamps();
            //multiple item dalam satu transaksi memiliki kode_transaksi yang sama, jadi dibikin ga unique()
            $table->string('kode_transaksi');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->bigInteger('qty');
            $table->bigInteger('total_diskon');
            $table->bigInteger('total_harga');
            $table->bigInteger('total_bayar');
        });
        // namabarang/tanggal/namapembeli hashed
    //     id
	// code_transaksi
	// tanggal_transaksi
	// customer_id (fk)
	// item_id (fk)
	// qty
	// total_diskon
	// total_harga
	// total_bayar

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
