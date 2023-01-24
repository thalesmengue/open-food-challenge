<?php

namespace App\Repositories;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;

class DatabaseRepository
{
    public function getConnection()
    {
        try {
            Product::query()->create([
                'code' => 'test',
            ]);

            $product = Product::query()->where('code', 'test')->first();
            if (empty($product)) {
                throw new Exception('Was not able to create a product!');
            }

            $product->delete();

            return "The connection reading and writing with the database is working!";
        } catch (Exception $e) {
            die("Database has a problem.  Please check your configuration. error:" . $e->getMessage());
        }
    }
}
