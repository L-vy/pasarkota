<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

// GET: baca semua produk -> 200
Route::get('/products', function () {
    return response()->json(DB::table('products')->get(), 200);
});

// POST: tulis produk baru -> 201 (atau 422 jika data tidak valid)
Route::post('/products', function (Request $request) {
    $v = Validator::make($request->all(), [
        'nama_produk'  => 'required|string|max:255',
        'harga_asli'   => 'required|integer|min:0',
        'harga_diskon' => 'required|integer|min:0|lte:harga_asli',
        'gambar_url'   => 'nullable|url',
    ]);

    if ($v->fails()) {
        return response()->json(['errors' => $v->errors()], 422);
    }

    $id = DB::table('products')->insertGetId($v->validated());

    return response()->json(DB::table('products')->find($id), 201);
});