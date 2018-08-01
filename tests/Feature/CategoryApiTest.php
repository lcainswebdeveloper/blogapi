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

    /** @test **/
    public function categories_will_display_correct_posts()
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

        $post1 = $this->json("POST", "/api/blog-post/create", [
            'title' => 'My Post',
            'abstract' => 'My Abstract',
            'content' => 'Some content',
            'categories' => [1,2]
        ]);

        $post2 = $this->json("POST", "/api/blog-post/create", [
            'title' => 'My Post 2',
            'abstract' => 'My Abstract 2',
            'content' => 'Some content 2',
            'categories' => [1]
        ]);

        $listing = $this->json("GET", "/api/post/categories/cat-one");
        $listing->assertStatus(200);
        
        $this->assertEquals(
            count($listing->decodeResponseJson()['posts']),
            2
        );

        foreach($listing->decodeResponseJson()['posts'] as $post):
            $this->assertEquals(
                $post['pivot']['category_id'],
                1
            );
        endforeach;

        $listing2 = $this->json("GET", "/api/post/categories/cat-two");
        $listing2->assertStatus(200);
        
        $this->assertEquals(
            count($listing2->decodeResponseJson()['posts']),
            1
        );

        foreach($listing2->decodeResponseJson()['posts'] as $post):
            $this->assertEquals(
                $post['pivot']['category_id'],
                2
            );
        endforeach;
    }

    /** @test **/
    public function posts_in_categories_should_have_the_user_record_attached()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');

        factory(\App\Category::class)->create([
            'title' => 'Cat One',
            'slug' => 'cat-one'
        ]);

        $post = $this->json("POST", "/api/blog-post/create", [
            'title' => 'My Post',
            'abstract' => 'My Abstract',
            'content' => 'Some content',
            'categories' => [1]
        ]);

        $listing = $this->json("GET", "/api/post/categories/cat-one");
        $listing->assertStatus(200);
        
        $post = $listing->decodeResponseJson()['posts'][0];
        $this->assertTrue(isset($post['user']));
        $this->assertTrue($post['user']['id'] == $user->id);
    }
}
