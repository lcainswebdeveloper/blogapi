<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function update_a_category()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        
        $category = $this->json("POST", "/api/category/create", [
            'title' => 'Lews Category'
        ]);
        
        $updated = $this->json("POST", "/api/category/" . $category->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews Category Edited'
        ]);

        $updated->assertStatus(200);
        
        $this->assertEquals(
            $updated->decodeResponseJson()['title'],
            'Lews Category Edited'
        );
        $this->assertEquals(
            $updated->decodeResponseJson()['slug'],
            'lews-category-edited'
        );
        
        $this->assertEquals(
            $updated->decodeResponseJson()['user_id'],
            1
        );
    }

    /** @test **/
    public function category_validation()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $category = $this->json("POST", "/api/category/create", [
            'title' => 'Lews Category'
        ]);
        $category->assertStatus(201);
        
        /*Create another dummy category*/
        $this->json("POST", "/api/category/create", [
            'title' => 'Lews Category Two'
        ]);
        
        /*We shouldn't be able to post an empty category name*/
        $updated = $this->json("POST", "/api/category/" . $category->decodeResponseJson()['id'] . "/update", []);
        $updated->assertStatus(422);

        /*We shouldn't be able to change our name to another category name*/
        $updated = $this->json("POST", "/api/category/" . $category->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews Category Two'
        ]);
        $updated->assertStatus(422);
        
        /*We should be able to keep the same name for our current record*/
        $updated = $this->json("POST", "/api/category/" . $category->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews Category'
        ]);
        $updated->assertStatus(200);
        
        /*Once the authenticated user is associated with the post - this can't be overridden*/
        $user2 = factory(\App\User::class)->create();
        $this->actingAs($user2, 'api');
        $updated = $this->json("POST", "/api/category/" . $category->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews Category Edited'
        ]);
        $updated->assertStatus(200);
        $this->assertEquals(
            $updated->decodeResponseJson()['user_id'],
            1
        );
    }
}
