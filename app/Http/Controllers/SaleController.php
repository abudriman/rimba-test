<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json_encode(Sale::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sale = new Sale;
        $item_id = $request->item_id;
        $customer_id = $request->customer_id;
        $qty = $request->qty;

        $namabarang = hash('sha256', Item::find($request->item_id)->nama);
        $namapembeli = hash('sha256', Customer::find($request->customer_id)->nama);
        $kode = substr($namabarang,0,10).'/'.date("Y-m-d").'/'.substr($namapembeli,0,10);
        $sale->kode_transaksi = $kode;
        

        $sale->customer_id = $customer_id;
        $sale->item_id = $item_id;

        if($this->checkQty($qty, $item_id)){
            $sale->qty = $qty;
        } else return response('qty tidak boleh melebihi stok',400);

        $sale->total_harga = $this->getHarga($item_id, $qty);
        $sale->total_diskon = $this->getDiskon($sale->total_harga, $customer_id);
        $sale->total_bayar = $sale->total_harga-$sale->total_diskon;
        $sale->save();
        return response(json_encode($sale), 201)->header('Content-type', 'application/json');
    }
   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale = Sale::find($id);

        $sale->delete();
        return response(200);
    }


    protected function checkQty(int $qty,int $item_id){
        if(Item::find($item_id)->stok<$qty){
            return false;
        }
        return true;
    }

    protected function getHarga(int $item_id, int $qty){
        return Item::find($item_id)->harga * $qty;
    }

    protected function getDiskon(int $total_harga, int $customer_id){
        $customer = Customer::find($customer_id);
        if($customer->tipe_diskon == "persentase"){
            return ($total_harga*$customer->diskon)/100;
        } else return $total_harga-$customer->diskon;
    }
}
