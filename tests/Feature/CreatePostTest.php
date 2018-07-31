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

        $post->assertStatus(200);
        
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
        $post->assertStatus(200); //first instance

        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews Post',
            'content' => 'Some Content'
        ]);
        
        $post->assertStatus(422);
    }

    /** @test **/
    public function we_can_assign_categories_to_posts()
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
        factory(\App\Category::class)->create([
            'title' => 'Cat Three',
            'slug' => 'cat-three'
        ]);
        factory(\App\Category::class)->create([
            'title' => 'Cat Four',
            'slug' => 'cat-four'
        ]);
        //prepr(\App\Category::listings()->toArray());
        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews Post',
            'content' => 'Some Content',
            'categories' => [3,4]
        ]); 

        $this->assertEquals(3, $post->decodeResponseJson()['categories'][0]['id']);
        $this->assertEquals(4, $post->decodeResponseJson()['categories'][1]['id']);

        /*Also check that the categories can be altered when post is edited*/
        $updated = $this->json("POST", "/api/blog-post/" . $post->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews post edited',
            'content' => 'Some Content',
            'categories' => [1,2]
        ]);
        $updated->assertStatus(200);
        $this->assertEquals(1, $updated->decodeResponseJson()['categories'][0]['id']);
        $this->assertEquals(2, $updated->decodeResponseJson()['categories'][1]['id']);
    }
}

