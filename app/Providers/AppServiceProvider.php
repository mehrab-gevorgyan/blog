<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Post_Tag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('delete-update-post', function(User $user, Post $post){
            return $user->id === $post->user_id || $user->email === 'mehrabgevorgyan@gmail.com';
        });

        // MyGate::before(function (User $user) {
        //     return $user->email === 'mehrabgevorgyan@gmail.com';
        // });

        Paginator::defaultView('vendor.pagination.default');

        // $post = Comment::all()->groupBy('post_id')->all();
        // dd($post, count(array_values($post)[27]->toArray()));
        // foreach ($post as $key => $value) {
        //     $post[] = array_values($post)[$key]->count();
        // }

        // every tag all posts count
        $tag_all_posts = array_count_values(Post_Tag::pluck('tag_id')->all());
        asort($tag_all_posts);

        // 10 tags by many posts
        $_10_tags_by_many_posts = array_slice($tag_all_posts, -10, 10, true);
        //dd($_10_tag_many_posts)

        // 10 tags by many posts ids
        $_10_tags_by_many_posts_ids = array_keys(array_slice($_10_tags_by_many_posts, -10, 10, true));
        //dd($_10_tags_by_many_posts_ids);

        // 10 tags by many posts names
        $tags_name = [];
        foreach($_10_tags_by_many_posts_ids as $k => $v){
            $tags_name[$k] = Tag::where('id', $v)->value('name');
        }

        $tags = array_reverse(array_combine($tags_name, array_values($_10_tags_by_many_posts)));

        View::share('tags', $tags);
        View::share('tags_id', array_reverse($_10_tags_by_many_posts_ids));


        $likes = Like::all()->groupBy('post_id')->toArray();
        foreach ($likes as $post_id => $likes_collect) {
            $rating = 0;
            foreach ($likes_collect as $collect_to_array) {
                $rating+= $collect_to_array['like'] - $collect_to_array['dislike'];
            }
            $likes[$post_id] = $rating;
        }
        arsort($likes);
        //dd(intdiv(31, 10));
        $likes = array_slice($likes, 0, 10, true);

        // add post short title
        foreach($likes as $post_id => $likes_count) {
            // add title, pagination page to assoc arr
            $likes[$post_id] = [$likes_count, Post::find($post_id)->title, intdiv(Post::where('id', '<', $post_id)->count(), 10) + 1];

            // add short title
            if(mb_strlen($likes[$post_id][1]) > 15) {
                $likes[$post_id][1] = substr($likes[$post_id][1], 0, 15).' ...';
            }
        }
        //dd($likes);
        View::share('likes', $likes);

    }
}
