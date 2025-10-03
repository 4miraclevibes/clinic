<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'nama' => 'Product 1',
            'harga' => 10000,
            'keterangan' => 'Keterangan Product 1',
            'gambar' => 'gambar/product1.jpg',
        ]);

        Product::create([
            'nama' => 'Product 2',
            'harga' => 20000,
            'keterangan' => 'Keterangan Product 2',
            'gambar' => 'gambar/product2.jpg',
        ]);

        Product::create([
            'nama' => 'Product 3',
            'harga' => 30000,
            'keterangan' => 'Keterangan Product 3',
            'gambar' => 'gambar/product3.jpg',
        ]);
    }
}
