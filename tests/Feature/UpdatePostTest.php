<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test **/
    public function update_a_post()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        
        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews post',
            'content' => 'Some Content'
        ]);
        
        $updated = $this->json("POST", "/api/blog-post/" . $post->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews post Edited',
            'content' => 'Some Content'
        ]);

        $updated->assertStatus(200);
        
        $this->assertEquals(
            $updated->decodeResponseJson()['title'],
            'Lews post Edited'
        );
        $this->assertEquals(
            $updated->decodeResponseJson()['slug'],
            'lews-post-edited'
        );
        
        $this->assertEquals(
            $updated->decodeResponseJson()['user_id'],
            1
        );
    }

    /** @test **/
    public function post_validation()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews post',
            'content' => 'Some Content'
        ]);
        $post->assertStatus(200);
        
        /*Create another dummy post*/
        $this->json("POST", "/api/blog-post/create", [
            'title' => 'Lews post Two',
            'content' => 'Some Content'
        ]);
        
        /*We shouldn't be able to post an empty post name*/
        $updated = $this->json("POST", "/api/blog-post/" . $post->decodeResponseJson()['id'] . "/update", []);
        $updated->assertStatus(422);

        /*We shouldn't be able to change our name to another post name*/
        $updated = $this->json("POST", "/api/blog-post/" . $post->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews post Two',
            'content' => 'Some Content'
        ]);
        $updated->assertStatus(422);
        
        /*We should be able to keep the same name for our current record*/
        $updated = $this->json("POST", "/api/blog-post/" . $post->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews post',
            'content' => 'Some Content'
        ]);
        $updated->assertStatus(200);
        
        /*Once the authenticated user is associated with the post - this can't be overridden*/
        $user2 = factory(\App\User::class)->create();
        $this->actingAs($user2, 'api');
        $updated = $this->json("POST", "/api/blog-post/" . $post->decodeResponseJson()['id'] . "/update", [
            'title' => 'Lews post Edited',
            'content' => 'Some Content'
        ]);
        $updated->assertStatus(200);
        $this->assertEquals(
            $updated->decodeResponseJson()['user_id'],
            1
        );
    }
}
