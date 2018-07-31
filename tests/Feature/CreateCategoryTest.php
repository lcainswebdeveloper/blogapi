<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function create_a_category()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        
        $category = $this->json("POST", "/api/category/create", [
            'title' => 'Lews Category'
        ]);

        $category->assertStatus(200);
        
        $this->assertEquals(
            $category->decodeResponseJson()['title'],
            'Lews Category'
        );
        $this->assertEquals(
            $category->decodeResponseJson()['slug'],
            'lews-category'
        );
        $this->assertEquals(
            $category->decodeResponseJson()['user_id'],
            1
        );
    }

    /** @test **/
    public function category_validation()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $category = $this->json("POST", "/api/category/create",[]);

        $category->assertStatus(422);
        
        $category = $this->json("POST", "/api/category/create", [
            'title' => 'Lews Category'
        ]);
        
        $category->assertStatus(200);

        $category = $this->json("POST", "/api/category/create", [
            'title' => 'Lews Category'
        ]);

        /*We shouldn't be able to duplicate a category name*/
        $category->assertStatus(422);
    }
}
