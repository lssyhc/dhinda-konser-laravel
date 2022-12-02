<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verifikasi;
use App\Models\Transaksi;

class VerifikasiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cekKonfirmasiBayar = Transaksi::find($request->id_transaksi);
        if (!$cekKonfirmasiBayar->konfirmasi_bayar) {
            return ["message" => "Pembayaran Belum Terkonfirmasi"];
        }
        
        $data = new Verifikasi();
        $data->id_user = $cekKonfirmasiBayar->id_user;
        $data->id_transaksi = $request->id_transaksi;
        $data->created_at = date('Y-m-d H:i:s');
        $data->kode_verifikasi = rand(1000,9999);
        $data->save();

        return response()->json([
            "message" => "Store Succes",
            "data" => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $data = Verifikasi::find($id);
        $transaksi = Transaksi::find($data->id_transaksi);

        if ($request->user()->id != $transaksi->id_user) {
            return ["message" => "Anda tidak berhak atas verifikasi ini!"];
        }

        return $data;
    }
}
