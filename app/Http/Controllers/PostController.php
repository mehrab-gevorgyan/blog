<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Emoji;
use App\Models\Comment;
use App\Models\Post_Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function addComment(Request $request, $id)
    {
        $comment = new Comment();
        $comment->user_id = auth()->user()->id;
        $comment->post_id = $id;
        $comment->comment_text = $request->comment;
        $comment->save();

        $date = date("Y-m-d H:i:s");

        return response()->json(['comment' => $comment, 'date' => $date]);
    }

    public function userAllPosts($user_id)
    {
        $posts = Post::where('user_id', '=', $user_id)->latest()->paginate(10);
        return view('home', ['posts' => $posts]);
    }

    public function tagAllPosts($tag_id)
    {
        $posts = Tag::find($tag_id)->posts()->paginate(10);
        return view('home',['posts' => $posts]);
    }

    public function emoji(Request $request, $post_id)
    {
        $emoji = Emoji::where('post_id', $post_id)->where('user_id', auth()->id())->first();

        function addEmoji($emoji, $emoji_name, $post_id)
        {
            if ($emoji->$emoji_name == 0)
                {$emoji->$emoji_name++;}
            else
                {$emoji->$emoji_name--;}

            $emoji->save();

            return ['emoji' => Emoji::where('post_id', $post_id)->where($emoji_name, 1)->count()];
        }

        function createEmoji($emoji_name, $post_id)
        {
            $emoji = new Emoji();
            $emoji->user_id = auth()->user()->id;
            $emoji->post_id = $post_id;
            $emoji->$emoji_name++;
            $emoji->save();

            return ['emoji' => Emoji::where('post_id', $post_id)->where($emoji_name, 1)->count()];
        }

        if($emoji !== null)
        {
            switch($request->emoji) {
                case 'smileys': return response()->json(addEmoji($emoji, 'smileys', $post_id));
                case 'laughing': return response()->json(addEmoji($emoji, 'laughing', $post_id));
                case 'love': return response()->json(addEmoji($emoji, 'love', $post_id));
                case 'shocked': return response()->json(addEmoji($emoji, 'shocked', $post_id));
                case 'sad': return response()->json(addEmoji($emoji, 'sad', $post_id));
                case 'cute': return response()->json(addEmoji($emoji, 'cute', $post_id));
                case 'angry': return response()->json(addEmoji($emoji, 'angry', $post_id));
            }
        }
        else
        {
            switch($request->emoji) {
                case 'smileys': return response()->json(createEmoji('smileys', $post_id));
                case 'laughing': return response()->json(createEmoji('laughing', $post_id));
                case 'love': return response()->json(createEmoji('love', $post_id));
                case 'shocked': return response()->json(createEmoji('shocked', $post_id));
                case 'cute': return response()->json(createEmoji('cute', $post_id));
                case 'sad': return response()->json(createEmoji('sad', $post_id));
                case 'angry': return response()->json(createEmoji('angry', $post_id));
            }
        }
    }

    public function postLike($id)
    {
        $like = Like::where('post_id', $id)->where('user_id', auth()->id())->first();

        if($like === null) {
            $like = new Like();
            $like->post_id = $id;
            $like->user_id = auth()->id();
            $like->like++;
            $like->save();
        }
        elseif ($like->like === 1) {
            $like->like--;
            $like->save();
        }
        else {
            $like->like++;
            $like->save();
        }

        $rating = array_sum(Post::find($id)->likes->pluck('rating')->toArray());

        return response()->json(['likes' => $rating]);
    }

    public function postDislike($id)
    {
        $like = Like::where('post_id', '=', $id)->where('user_id', '=', auth()->id())->first();

        if($like === null) {
            $like = new Like();
            $like->post_id = $id;
            $like->user_id = auth()->id();
            $like->dislike++;
            $like->save();
        }
        elseif ($like->dislike === 1) {
            $like->dislike--;
            $like->save();
        }
        else {
            $like->dislike++;
            $like->save();
        }

        $rating = array_sum(Post::find($id)->likes->pluck('rating')->toArray());

        return response()->json(['likes' => $rating]);
    }

    public function index(Request $request )
    {
        $search = $request->search;

        $tags_id = Tag::whereLike('name', "%{$search}%")->pluck('id')->all();
        $post_tags_post_id = Post_Tag::whereIn('tag_id', $tags_id)->pluck('post_id')->all();

        // withTrashed()-> with deleted posts  restore()-> restore deleted

        $posts = Post::whereIn('id', $post_tags_post_id)
        ->orWhereLike('title', "%{$search}%")
        ->orWhereLike('desc', "%{$search}%")
        ->orWhereIn('user_id', User::select('id')->whereLike('login', "%{$search}%"))
        ->paginate(10)
        ->withQueryString();

        return view('home', ['posts' => $posts]);
    }

    public function create()
    {
        return view('post.create-post');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'desc' => 'required',
            'image' => ['extensions:jpg, jpeg, png'],
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->user_id = auth()->user()->id;
        $post->desc = $request->desc;

        if($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $post->image = 'storage/images/'.$imageName;
            $request->image->move(storage_path('app/public/images'), $imageName);
        }

        $post->save();

        $tags = explode(',', $request->tags);
        $tags_filter = [];

        for($i = 0; $i < count($tags); $i++)
        {
            $tags[$i] = trim(str_replace('  ', ' ', $tags[$i]));
            $tags[$i] = preg_replace('/\s\s+/', ' ', $tags[$i]);

            if($tags[$i] !== "") {
                $tags_filter[] = $tags[$i];

                if(Tag::where('name', '=', $tags[$i])->get()->isEmpty()) {
                    $tag = new Tag();
                    $tag->name = $tags[$i];
                    $tag->save();
                }
            }
        }

        $tags_filter =  array_values(array_unique($tags_filter));

        for($i = 0; $i < count($tags_filter); $i++) {
            $id = Tag::select('id')->where('name', '=', $tags_filter[$i])->get();

            $post_tag = new Post_Tag();
            $post_tag->post_id = $post->id;
            $post_tag->tag_id = $id[0]->id;
            $post_tag->save();

        }

        return redirect('/');

    }

    public function show(string $id)
    {
        ////////////////////////////////
    }

    public function edit(string $id)
    {
        $post = Post::find($id);
        return view('post.update-post',['post' => $post]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => ['required', 'max:255'],
            'desc' => ['required'],
        ]);

        $post = Post::find($id);
        $post->title = $request->title;
        $post->desc = $request->desc;
        $post->save();

        $tags = explode(',', $request->tags);
        $tags_filter = [];

        for($i = 0; $i < count($tags); $i++)
        {
            $tags[$i] = trim(str_replace('  ', ' ', $tags[$i]));
            $tags[$i] = preg_replace('/\s\s+/', ' ', $tags[$i]);

            if($tags[$i] !== "") {
                $tags_filter[] = $tags[$i];

                // create new row in db tags
                if(Tag::where('name', '=', $tags[$i])->get()->isEmpty()) {
                    $tag = new Tag();
                    $tag->name = $tags[$i];
                    $tag->save();
                }
            }
        }

        $tags_filter =  array_values(array_unique($tags_filter));
        for($i = 0; $i < count($tags_filter); $i++) {
            // получаем новый тег у поста
            $tag = Tag::where('name', '=', $tags_filter[$i])->get();
            // получаем связи поста и тега
            $post_tag = Post_Tag::where('tag_id', '=', $tag[0]->id)->where( 'post_id', '=', $post->id)->get();

            // если нету связи и тег новый добавляем
            if($post_tag->isEmpty()) {
                $new_post_tag = new Post_Tag();
                $new_post_tag->post_id = $post->id;
                $new_post_tag->tag_id = $tag[0]->id;
                $new_post_tag->save();
            }
        }

        $all_tags_id = Post_Tag::where('post_id', $post->id)->pluck('tag_id')->all();
        // $new_tags_id = [];
        // foreach ($tags_filter as $name) {
            //     $tag = Tag::where('name', '=', $name)->get();
            //     $new_tags_id[] = $tag[0]->id;
            // }

        $new_tags_id = Tag::whereIn('name', $tags_filter)->pluck('id')->all();
        //dd($all_tags_id);

        if($all_tags_id != $new_tags_id){
            for ($i = 0; $i < count($new_tags_id); $i++) {
                for ($j = 0; $j  < count($all_tags_id); $j ++) {
                    if($all_tags_id[$j] === $new_tags_id[$i]) {
                        $all_tags_id[$j] = null;
                    }
                }
            }

            for ($i = 0; $i < count($all_tags_id); $i++) {
                if($all_tags_id[$i] != null) {
                    Post_Tag::where('post_id', '=', $post->id)->where('tag_id', '=', $all_tags_id[$i])->delete();
                }
            }
        }

        return redirect()->route('posts.index');
    }

    public function destroy(string $id)
    {
        Post::destroy($id);
        return redirect()->back();
    }
}
