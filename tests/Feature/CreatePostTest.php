<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function create_a_post()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        
        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews Post',
            'content' => 'Some Content'
        ]);

        $post->assertStatus(201);
        
        $this->assertEquals(
            $post->decodeResponseJson()['title'],
            'Lews Post'
        );
        $this->assertEquals(
            $post->decodeResponseJson()['slug'],
            'lews-post'
        );
        $this->assertEquals(
            $post->decodeResponseJson()['user_id'],
            1
        );
    }

    /** @test **/
    public function post_validation()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $post = $this->json("POST", "/api/blog-post/create",[]);

        $post->assertStatus(422);

        /*We need to provide content*/
        $post = $this->json("POST", "/api/blog-post/create",[
            "title" => "Foobar"
        ]);
        $post->assertStatus(422);
        
        /*We shouldn't be able to duplicate a post name*/
        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews Post',
            'content' => 'Some Content'
        ]); 
        $post->assertStatus(201); //first instance

        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews Post',
            'content' => 'Some Content'
        ]);
        
        $post->assertStatus(422);
    }

    /** @test **/
    public function we_can_assign_categories_to_posts()
    {
        factory(\App\User::class)->create();
        $category = factory(\App\Category::class)->create([
            'title' => 'Cat One',
            'slug' => 'cat-one'
        ]);
        factory(\App\Category::class)->create([
            'title' => 'Cat Two',
            'slug' => 'cat-two'
        ]);
        factory(\App\Category::class)->create([
            'title' => 'Cat Three',
            'slug' => 'cat-three'
        ]);
        factory(\App\Category::class)->create([
            'title' => 'Cat Four',
            'slug' => 'cat-four'
        ]);
        prepr(\App\Category::listings()->toArray());
    }
}
