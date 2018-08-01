<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_creator_of_a_category_should_be_the_only_one_able_to_delete_it()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        factory(\App\Category::class)->create([
            'title' => 'Cat One',
            'slug' => 'cat-one'
        ]);

        $user2 = factory(\App\User::class)->create();
        $this->actingAs($user2, 'api');

        $delete = $this->json("POST", "/api/category/1/delete");
        $delete->assertStatus(404);
    }

    /** @test **/
    public function delete_a_category()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        
        /*We should be able to delete a category*/
        factory(\App\Category::class)->create([
            'title' => 'Cat One',
            'slug' => 'cat-one'
        ]);


        $listing = $this->json("GET", "/api/post/categories/1");
        $listing->assertStatus(200);

        $delete = $this->json("POST", "/api/category/1/delete");
        $delete->assertStatus(200);

        $listing = $this->json("GET", "/api/post/categories/1");
        
        $listing->assertStatus(404);
    }

    /** @test **/
    public function ensure_associated_posts_get_disassociated_with_a_category_when_deleted()
    {
        $user = factory(\App\User::class)->create();
        $this->actingAs($user, 'api');
        
        /*We should be able to delete a category*/
        factory(\App\Category::class)->create([
            'title' => 'Cat One',
            'slug' => 'cat-one'
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
            'categories' => [1]
        ]);

        $this->assertEquals(\DB::table('blog_posts_categories_pivot')->where('category_id', 1)->count(),2);
            
        $delete = $this->json("POST", "/api/category/1/delete");

        $this->assertEquals(\DB::table('blog_posts_categories_pivot')->where('category_id', 1)->count(), 0);
           
    }
}
