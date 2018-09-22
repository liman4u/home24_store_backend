<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        \DB::table('products')->delete();

        // Creates 5 records of products
        factory(Product::class, 5)->create();

        Model::reguard();
    }
}
