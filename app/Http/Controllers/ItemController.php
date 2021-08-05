<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json_encode(Item::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Item;
        
        $item->nama = $request->nama;
        $item->unit = $request->unit;
        $item->stok = $request->stok;
        $item->harga = $request->harga;
        $file = $request->file('foto_barang')->store('foto_barang');
        $item->foto_barang = $file;
        $item->save();
        return response(json_encode($item), 201)->header('Content-type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        if($request->nama) $item->nama = $request->nama;
        if($request->unit) $item->unit = $request->unit;
        if($request->stok) $item->stok = $request->stok;
        if($request->harga) $item->harga = $request->harga;

        if($request->foto_barang){
            Storage::delete($item->foto_barang);
            $file = $request->file('foto_barang')->store('foto_barang');
            $item->foto_barang = $file;
        }

        $item->save();
        return response(json_encode($item), 200)->header('Content-type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);

        $item->delete();
        return response(200);
        
    }
}
