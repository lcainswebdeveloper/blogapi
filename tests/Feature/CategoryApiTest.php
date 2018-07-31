<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function get_category_listings()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $category = $this->json("POST", "/api/category/create", [
            'title' => 'Second Title'
        ]);
        
        $category2 = $this->json("POST", "/api/category/create", [
            'title' => 'First Title'
        ]);
        
        $listings = $this->json("GET", "/api/posts/categories");
        $listings->assertStatus(200);
        $this->assertEquals(
            $listings->decodeResponseJson()[0]['user_id'],
            1
        );
        $this->assertEquals(
            $listings->decodeResponseJson()[0]['id'],
            2
        ); //confirm ordering is correct
    }

    /** @test **/
    public function get_category_listing()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $this->json("POST", "/api/category/create", [
            'title' => 'My Category'
        ]);

        $listing = $this->json("GET", "/api/post/categories/1");
        $listing->assertStatus(200);

        $this->assertEquals(
            $listing->decodeResponseJson()['user_id'],
            1
        );
        $this->assertEquals(
            $listing->decodeResponseJson()['title'],
            'My Category'
        );

        /*Check we can search by slug*/
        $listing = $this->json("GET", "/api/post/categories/my-category");
        $listing->assertStatus(200);

        $this->assertEquals(
            $listing->decodeResponseJson()['user_id'],
            1
        );
        $this->assertEquals(
            $listing->decodeResponseJson()['title'],
            'My Category'
        );
    }
}
