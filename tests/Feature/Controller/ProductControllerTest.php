<?php

namespace Tests\Feature\Controller;

use App\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_retrieve_all_products(): void
    {
        $response = $this->get('/api/products');

        $response->assertSee('Next');
        $response->assertOk()
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_should_retrieve_a_unique_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->get('/api/products/' . $product->code);

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertJsonFragment([
            'code' => $product->code,
            'product_name' => $product->product_name,
            'url' => $product->url,
        ]);
    }

    public function test_should_update_a_registered_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->put('/api/products/' . $product->code, [
            'product_name' => 'teste',
            'url' => 'https://www.teste.com.br'
        ]);

        $response->assertOk()
            ->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('products', [
            'code' => $product->code,
            'product_name' => 'teste',
            'url' => 'https://www.teste.com.br'
        ]);
    }

    public function test_should_delete_a_registered_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete('/api/products/' . $product->code);

        $response->assertNoContent()
            ->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseHas('products', [
            'code' => $product->code,
            'status' => 'trash'
        ]);
    }
}
