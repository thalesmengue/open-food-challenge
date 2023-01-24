<?php

namespace Tests\Feature\Controller;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function test_should_retrieve_all_products(): void
    {
        $response = $this->get('/api/products');

        $data = Product::query()->paginate(10);

        $data = json_decode($data);

        $response->assertJsonFragment([$data]);
    }

    public function test_should_retrieve_a_unique_product(): void
    {
        $code = '"0000000000017';
        $response = $this->get('/api/products/' . $code);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertJsonFragment([
            'code' => $code
        ]);
    }

    public function test_should_update_a_registered_product(): void
    {
        $code = '"000000000054';
        $response = $this->put('/api/products/' . $code, [
            'code' => $code,
            'product_name' => 'Teste',
            'url' => 'https://www.teste.com.br'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertOk();
        $this->assertDatabaseHas('products', [
            'code' => $code,
            'product_name' => 'Teste',
            'url' => 'https://www.teste.com.br'
        ]);
    }

    public function test_should_delete_a_registered_product(): void
    {
        $code = '"0000000000031';
        $response = $this->delete('/api/products/' . $code);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseHas('products', [
            'code' => $code,
            'status' => 'trash'
        ]);
    }
}
