<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ArticleTest extends TestCase
{
    use WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('article')
                    ->assertStatus(200)
                    ->assertJsonStructure(
                        [
                            'data' =>  [
                                '*' => [
                                    "id",
                                    "title",
                                    "description",
                                    "create_at",
                                    "update_at",
                                    "user" => [
                                        '*' => [
                                            "id",
                                            "email",
                                            "email_verified_at",
                                            "name",
                                            "create_at",
                                            "update_at"
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );
    }
}
