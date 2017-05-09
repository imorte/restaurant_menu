<?php

namespace Tests\Feature;

use App\Menu;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MenuTest extends TestCase
{
    use DatabaseTransactions;

    private $correctData = [
        'name' => 'Тест меню',
        'enabledFrom' => '2017-08-01 00:00:00',
        'enabledUntil' => '2017-09-01 00:00:00',
    ];

    private $incorrectData = [
        'name' => 'Тест меню',
        'enabledFrom' => '2017-05-09 00:00:00',
        'enabledUntil' => '2017-09-01 00:00:00',
    ];

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $menus = Menu::with('products')->get()->toArray();
        $response = $this->json('GET', '/api/menu');
        $response
            ->assertStatus(200)
            ->assertJson($menus);
    }

    public function testStore()
    {
        $response = $this->json('POST', '/api/menu', $this->correctData);
        $response
            ->assertRedirect($response->getTargetUrl());

        $response =$this->json('POST', '/api/menu', $this->incorrectData);
        $response
            ->assertStatus(400);
    }

    public function testShow()
    {
        $menu = Menu::with('products')->where('id', 1)->get()->toArray();
        $response = $this->json('GET', '/api/menu/1');

        $response
            ->assertJson($menu);
    }

    public function testUpdate()
    {
        $response = $this->json('PUT', '/api/menu/1', $this->correctData);
        $response
            ->assertStatus(200);

        $response = $this->json('PUT', '/api/menu/1', $this->incorrectData);
        $response
            ->assertStatus(400);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', '/api/menu/1');
        $response
            ->assertStatus(200);

        $response = $this->json('DELETE', '/api/menu/1');
        $response
            ->assertStatus(404);
    }
}
