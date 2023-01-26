<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository $productRepository
    )
    {
    }

    public function index(): JsonResponse
    {
        $products = $this->productRepository->all();

        return response()->json([
            'success' => true,
            'data' => $products
        ], Response::HTTP_OK);
    }

    public function show(string $code): JsonResponse
    {
        $product = $this->productRepository->find($code);

        return response()->json([
            'success' => true,
            'data' => $product
        ], Response::HTTP_FOUND);
    }

    public function update(string $code, Request $request): JsonResponse
    {
        $product = $this->productRepository->update($code, $request->all());

        return response()->json([
            'success' => true,
            'data' => $product
        ], Response::HTTP_OK);
    }

    public function delete(string $code): JsonResponse
    {
        $product = $this->productRepository->delete($code);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
