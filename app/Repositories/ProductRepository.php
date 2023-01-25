<?php

namespace App\Repositories;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): LengthAwarePaginator
    {
        return Product::query()
            ->notTrashed()
            ->paginate(10);
    }

    public function find(string $code): Builder|Model
    {
        return Product::query()
            ->where('code', $code)
            ->firstOrFail();
    }

    public function update(string $code, array $data): int
    {
        return Product::query()
            ->where('code', $code)
            ->update($data);
    }

    public function delete(string $code): int
    {
        return Product::query()
            ->where('code', $code)
            ->update(['status' => ProductStatus::trash->value]);
    }
}
