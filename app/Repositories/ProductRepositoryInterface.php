<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function all();
    public function find(string $code);
    public function update(string $code, array $data);
    public function delete(string $code);
}
