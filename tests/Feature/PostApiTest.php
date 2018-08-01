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

        factory(\App\Category::class)->create([
            'title' => 'Cat One',
            'slug' => 'cat-one'
        ]);
        factory(\App\Category::class)->create([
            'title' => 'Cat Two',
            'slug' => 'cat-two'
        ]);

        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Second Title',
            'abstract' => 'My Abstract',
            'content' => 'Some content',
            'categories' => [1]
        ]);
        
        $post2 = $this->json("POST", "/api/blog-post/create", [
            'title' => 'First Title',
            'abstract' => 'My Abstract',
            'content' => 'Some content',
            'categories' => [1,2]
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

        /*Confirm categories are present also*/
        $this->assertEquals(1, $listings->decodeResponseJson()[0]['categories'][0]['id']);
        $this->assertEquals(1, $listings->decodeResponseJson()[1]['categories'][0]['id']);
        $this->assertEquals(2, $listings->decodeResponseJson()[1]['categories'][1]['id']);
    }

    /** @test **/
    public function get_post_listing()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        factory(\App\Category::class)->create([
            'title' => 'Cat One',
            'slug' => 'cat-one'
        ]);
        factory(\App\Category::class)->create([
            'title' => 'Cat Two',
            'slug' => 'cat-two'
        ]);

        $this->json("POST", "/api/blog-post/create", [
            'title' => 'My Post',
            'abstract' => 'My Abstract',
            'content' => 'Some content',
            'categories' => [1,2]
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

       /*Confirm categories are present also*/
        $this->assertEquals(1, $listing->decodeResponseJson()['categories'][0]['id']);
        $this->assertEquals(2, $listing->decodeResponseJson()['categories'][1]['id']);
    
    }
}
