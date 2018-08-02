<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlogPostCategoriesPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts_categories_pivot', function (Blueprint $table) {
            $table->integer('blog_posts_id');
            $table->integer('category_id');
            $table->primary(['blog_posts_id', 'category_id'], 'blog_posts_category_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
