<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_returns_successful_response()
{
    $response = $this->get('/tasks/create');
    $response->assertStatus(200); // Проверяет успешный ответ
    $response->assertViewIs('tasks.create'); // Проверяет, что вернулось нужное представление
}
}
