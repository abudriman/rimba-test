<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
               
        // return csrf_token(); //for postman
        return json_encode(Customer::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'foto_ktp' => 'required|image|max:2048',
        ]);
        $customer = new Customer;

        //teteup pake request, validasinya nanti client side aja ya
        $customer->nama = $request->nama;
        $customer->kontak = $request->kontak;
        $customer->email = $request->email;
        $customer->alamat = $request->alamat;
        $customer->diskon = $request->diskon;
        $customer->tipe_diskon = $request->tipe_diskon;
        $file = $request->file('foto_ktp')->store('public/foto_ktp');
        $customer->foto_ktp = $file;
        $customer->save();
        return response(json_encode($customer), 201)->header('Content-type', 'application/json');
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
        $customer = Customer::find($id);
        if($request->nama)  $customer->nama = $request->nama;
        if($request->kontak)  $customer->kontak = $request->kontak;
        if($request->email)  $customer->email = $request->email;
        if($request->alamat)  $customer->alamat = $request->alamat;
        if($request->diskon)  $customer->diskon = $request->diskon;
        if($request->tipe_diskon)  $customer->tipe_diskon = $request->tipe_diskon;
        
        if($request->foto_ktp){
            Storage::delete($customer->foto_ktp);
            $file = $request->file('foto_ktp')->store('public/foto_ktp');
            $customer->foto_ktp = $file;
        }

        $customer->save();
        return response(json_encode($customer), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        $customer->delete();
        return response(200);
    }
}
