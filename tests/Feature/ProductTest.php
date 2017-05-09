<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends TestCase
{
    use DatabaseTransactions;

    private $correctData = [
        'name' => 'Test product',
        'menu_id' => 1,
        'position' => 1,
    ];

    private $incorrectData = [
        'name' => 'Test product',
        'menu_id' => 100500,
        'position' => 1,
    ];

    public function testIndex()
    {
        $products = Product::with('menu')->get()->toArray();

        $response = $this->json('GET', '/api/product');
        $response
            ->assertStatus(200)
            ->assertJson($products);
    }

    public function testStore()
    {
        $response = $this->json('POST', '/api/product', $this->correctData);
        $response
            ->assertRedirect($response->getTargetUrl());

        // На дубляж
        $response = $this->json('POST', '/api/product', $this->correctData);
        $response
            ->assertStatus(409);
    }

    public function testShow()
    {
        $product = Product::with('menu')->where('id', 1)->get()->toArray();
        $response = $this->json('GET', '/api/product/1');
        $response
            ->assertStatus(200)
            ->assertJson($product);

        $response = $this->json('GET', '/api/product/100500');
        $response
            ->assertStatus(404);
    }

    public function testUpdate()
    {
        $response = $this->json('PUT', '/api/product/1', $this->correctData);
        $response
            ->assertStatus(200);

        $response = $this->json('PUT', '/api/product/100500', $this->correctData);
        $response
            ->assertStatus(404);

        $response = $this->json('PUT', '/api/product/1', $this->incorrectData);
        $response
            ->assertStatus(422);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', '/api/product/1');
        $response
            ->assertStatus(200);

        $response = $this->json('DELETE', '/api/product/1');
        $response
            ->assertStatus(404);
    }
}
