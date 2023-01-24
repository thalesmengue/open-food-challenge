<?php

namespace App\Observers;

use App\Models\Product;
use Carbon\Carbon;

class ProductObserver
{
    public function creating(Product $product): void
    {
        $product->imported_t = Carbon::now();
    }
}
