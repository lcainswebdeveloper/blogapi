<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function get_post_listings()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $category = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Second Title',
            'content' => 'Some content'
        ]);
        
        $category2 = $this->json("POST", "/api/blog-post/create", [
            'title' => 'First Title',
            'content' => 'Some content'
        ]);
        
        $listings = $this->json("GET", "/api/posts/blog-posts");
        $listings->assertStatus(200);
        $this->assertEquals(
            $listings->decodeResponseJson()[0]['user_id'],
            1
        );
        $this->assertEquals(
            $listings->decodeResponseJson()[0]['id'],
            1
        ); //confirm ordering is correct
    }

    /** @test **/
    public function get_post_listing()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $this->json("POST", "/api/blog-post/create", [
            'title' => 'My Post',
            'content' => 'Some content'
        ]);

        $listing = $this->json("GET", "/api/post/blog-posts/1");
        $listing->assertStatus(200);

        $this->assertEquals(
            $listing->decodeResponseJson()['user_id'],
            1
        );
        $this->assertEquals(
            $listing->decodeResponseJson()['title'],
            'My Post'
        );

        /*Check we can search by slug*/
        $listing = $this->json("GET", "/api/post/blog-posts/my-post");
        $listing->assertStatus(200);
        
        $this->assertEquals(
            $listing->decodeResponseJson()['user_id'],
            1
        );
        $this->assertEquals(
            $listing->decodeResponseJson()['title'],
            'My Post'
        );
    }
}
