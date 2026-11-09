<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('products')->count() > 0) {
            return;
        }

        DB::table('products')->insert([
            [
                'nama_produk'  => 'Tomat Merah 1 kg',
                'harga_asli'   => 20000,
                'harga_diskon' => 10000,
                'gambar_url'   => '/images/tomat.jpg',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama_produk'  => 'Mangga Kopyor 2 kg',
                'harga_asli'   => 25000,
                'harga_diskon' => 12000,
                'gambar_url'   => '/images/mangga.jpg',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nama_produk'  => 'Wortel Lokal 1 kg',
                'harga_asli'   => 15000,
                'harga_diskon' => 7500,
                'gambar_url'   => '/images/wortel.jpg',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }
}