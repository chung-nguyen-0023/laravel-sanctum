<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all(['id']);
        Post::truncate();

        for ($i = 0; $i < 100; $i++) {
            $post = new Post;
            $post->name = 'Post '. ($i + 1);
            $post->slug = 'post-' . ($i + 1);
            $post->description = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
            $post->status = Post::STATUS_ACTIVE;
            $post->save();

            $post->categories()->sync([$categories->random()->id]);
        }
    }
}
