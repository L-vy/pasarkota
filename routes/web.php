<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// GET: tampilkan produk
Route::get('/', function () {
    $products = DB::table('products')->latest('id')->get();
    return view('welcome', ['products' => $products]);
});

// POST: tambah produk
Route::post('/produk', function (Request $request) {
    $data = $request->validate([
        'nama_produk'  => 'required|string|max:255',
        'harga_asli'   => 'required|integer|min:0',
        'harga_diskon' => 'required|integer|min:0|lte:harga_asli',
        'gambar_url'   => 'nullable|url|max:2048',
    ]);

    DB::table('products')->insert($data);

    // API/curl (Accept: application/json) -> 201, form browser -> redirect
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Produk berhasil ditambahkan'], 201);
    }
    return redirect('/')->with('success', 'Produk berhasil ditambahkan!');
});

Route::delete('/produk/{id}', function ($id) {
    DB::table('products')->where('id', $id)->delete();
    return redirect('/')->with('success', 'Produk berhasil dihapus!');
});

// Health check
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'healthy', 'database' => 'up']);
    } catch (\Throwable $e) {
        return response()->json(['status' => 'unhealthy', 'database' => 'down'], 503);
    }
});