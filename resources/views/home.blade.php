@extends('layout.layout')

@section('content')
    {{-- post content --}}
    <main class="w-[830px]">
        {{-- post --}}
        @foreach($posts as $post)
            <div class="shadow-md rounded-md mb-[20px] bg-white px-[10px] pt-[20px] relative {{ $post->id }}">

                {{-- post top user, actions, rating --}}
                <div class="px-[15px] flex items-center mb-[20px]">
                    {{-- post user --}}
                    <div class="flex items-center">
                        {{-- user img --}}
                        <div class="mr-[20px]">
                            <img class="rounded-full w-[50px]" src="{{ asset($post->user->user_image) }}">
                        </div>

                        {{-- user name --}}
                        <div class="mr-[20px]">
                            <p class="user-name">
                                <a class="font-semibold hover:underline" href="{{ url('user/'.$post->user->id) }}">
                                    {{ $post->user->login }}
                                </a>
                            </p>
                        </div>
                    </div>

                    {{-- post time --}}
                    <div class="italic mr-[20px]">
                        <p>{{ $post->created_at->diffForHumans() }}</p>
                    </div>

                    {{-- actions --}}
                    <div class="post-actions mr-[20px]">
                        @can('delete-update-post', $post)
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="flex flex-row">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('posts.edit', $post->id) }}" class="w-[80px] px-[15px] py-[8px] mr-[10px] rounded-md border-none flex items-center bg-[#6c8d5e] hover:bg-[#709e5d] text-white">
                                    Update
                                </a>
                                <input class="w-[80px] px-[15px] py-[8px] bg-[#b9e246] rounded-md hover:bg-[#e6ee78] cursor-pointer" type="submit" value="Delete">
                            </form>
                        @endcan
                    </div>

                    {{-- post rating --}}
                    <div class="absolute flex items-center right-[15px] text-[22px]">
                        {{-- like form --}}
                        <form class="formlike{{ $post->id }} ml-[70px]" method="POST">
                            @csrf
                            <input class="w-[40px] mx-[0px] my-[10px] h-[40px] bg-white cursor-pointer bg-no-repeat  like" type="submit" value="">
                        </form>

                        {{-- get post rating --}}
                        <span class="mx-[10px] rating{{$post->id}}">
                            {{ array_sum($post->likes->pluck('rating')->toArray()) }}
                        </span>

                        {{-- dislike form --}}
                        <form class="formdislike{{ $post->id }}" method="POST">
                            @csrf
                            <input class="w-[40px] mx-[0] my-[10px] h-[40px] bg-white cursor-pointer bg-no-repeat  dislike" type="submit" value="">
                        </form>

                        {{-- like dislike send ajax--}}
                        <script>
                            $(document).ready(function(){
                                $(".formlike{{$post->id}}").on('submit', function(event){

                                    event.preventDefault();

                                    $.ajax({
                                        url: "{{ url('posts/'.$post->id.'/like') }}",
                                        data: $(".formlike{{$post->id}}").serialize(),
                                        type: 'post',

                                        success: function (result) {
                                            $(".rating{{$post->id}}").html(result.likes);
                                            $(".formlike{{$post->id}}")[0].reset();
                                        }
                                    });
                                });

                                $(".formdislike{{$post->id}}").on('submit', function(event){

                                    event.preventDefault();

                                    $.ajax({
                                        url: "{{ url('posts/'.$post->id.'/dislike') }}",
                                        data: $(".formdislike{{$post->id}}").serialize(),
                                        type: 'post',

                                        success: function (result) {
                                            $(".rating{{$post->id}}").html(result.likes);
                                            $(".formdislike{{$post->id}}")[0].reset();
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>

                {{-- post content starting tags end comments --}}
                <div class="p-[15px]">

                    {{-- post tags --}}
                    <div class="post-tags">
                        @foreach ($post->tags as $tag)
                            <a class="mb-[10px] bg-blue-50 inline-block rounded-md px-[15px] py-[5px] mr-[10px] transition hover:bg-blue-100 text-blue-600" href="{{ url('tag/'.$tag->id) }}">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>

                    {{-- post title --}}
                    <div class="post-title">
                        <h3 class="font-semibold text-[24px] mt-[10px]">{{ $post->title }}</h3>
                    </div>

                    {{-- post desctiption --}}
                    <div>
                        <p class="leading-[1.6] mt-[10px] mb-[15px] text-justify first-letter:text-[red] first-letter:text-[24px]">
                            {{ $post->desc}}
                        </p>
                    </div>

                    {{-- post img --}}
                    @if($post->image)
                        <div class="w-full max-w-[820px] post-img post-img{{ $post->id }}">
                            <img src="{{ asset($post->image) }}">
                        </div>
                    @endif

                    {{-- emojies ajax COMMENTS VIEWS count--}}
                    <div class="flex justify-between items-center relative h-[60px]">

                        {{-- comments views count --}}
                        <div class="flex gap-2">
                            {{-- comments count --}}
                            <div class="px-[10px] py-[5px] rounded-md flex bg-gray-100 hover:cursor-pointer comments-info{{ $post->id }}">
                                <div class="flex items-center">
                                    <span class="inline-block rotate-90 text-[22px]">&raquo;</span>
                                    <span>Comments&nbsp;</span>
                                    <span class="comments-count{{$post->id}}">
                                        {{ $post->comments->count() }}
                                    </span>
                                </div>
                            </div>

                            {{-- views count --}}
                            <div class="flex gap-2">
                                <img class="w-[20px]" src="{{ asset('img/views-icon.jpg') }}">
                                <span class="flex items-center">100</span>
                            </div>
                        </div>

                        <script>
                            $('.comments-info{{ $post->id }}').on('click',function(){
                                $('.comments_container{{ $post->id }}').slideToggle();
                            });
                        </script>

                        {{-- emojies --}}
                        <div class="flex items-center absolute right-0 mb-[0px] emojies{{ $post->id }}">
                            {{-- emojies click --}}
                            <span class="flex items-center cursor-pointer bg-white rounded-md px-[10px] py-[5px] bg-[#f3f6f4] emojies-click{{ $post->id }}">
                                <span class="mr-4">emojies</span>
                                <img src="{{ asset('emoji/thinking.png') }}">
                            </span>

                            {{-- ajax add emoji in db --}}
                            <div class="w-[250px] p-[10px] p-[10px] z-[9999] bg-white rounded-md absolute -top-[45px] -left-[135px] hidden justify-between emojies-group{{ $post->id }} shadow-md">
                                <div class="flex gap-2 z-[9999]">
                                    <form class="w-[25px] rounded-none smileys{{ $post->id }} flex flex-col" method="POST" action="{{ route('emoji', $post->id) }}">
                                        @csrf
                                        <input name="emoji" type="hidden" value="smileys">
                                        <input class="smileys mb-[10px]  w-[24px] h-24[px] border-0 bg-no-repeat bg-[50%] bg-white mt-0 cursor-pointer" type="submit" value="">
                                        <p class="text-center smileys-count{{ $post->id }}">
                                            {{ $post->emojis->pluck('smileys')->sum() }}
                                        </p>
                                    </form>
                                    <script>
                                        $('.smileys{{ $post->id }}').on('submit', function(event){

                                            event.preventDefault();

                                            $.ajax({
                                                url: "{{ url('posts/'.$post->id.'/emoji') }}",
                                                data: $('.smileys{{ $post->id }}').serialize(),
                                                type: 'post',

                                                success: function (result) {
                                                    $('.smileys-count{{ $post->id }}').html(result.emoji);
                                                    $('.smileys{{ $post->id }}')[0].reset();
                                                }
                                            });
                                        });
                                    </script>

                                    <form class="w-[25px] laughing{{ $post->id }} flex flex-col" action="{{ route('emoji', $post->id) }}" method="POST">
                                        @csrf
                                        <input name="emoji" type="hidden" value="laughing">
                                        <input class="smileys mb-[10px]  w-[24px] h-24[px] border-0 bg-no-repeat bg-[50%] bg-white mt-0 cursor-pointer laughing" type="submit" value="">
                                        <p class="text-center laughing-count{{ $post->id }}">
                                            {{ $post->emojis->pluck('laughing')->sum() }}
                                        </p>
                                    </form>
                                    <script>
                                        $('.laughing{{ $post->id }}').on('submit', function(event){

                                            event.preventDefault();

                                            $.ajax({
                                                url: "{{ url('posts/'.$post->id.'/emoji') }}",
                                                data: $('.laughing{{ $post->id }}').serialize(),
                                                type: 'post',

                                                success: function (result) {
                                                    $('.laughing-count{{ $post->id }}').html(result.emoji);
                                                    $('.laughing{ $post->id }}')[0].reset();
                                                }
                                            });
                                        });
                                    </script>

                                    <form class="w-[25px] love{{ $post->id }} flex flex-col" action="{{ route('emoji', $post->id) }}" method="POST">
                                        @csrf
                                        <input name="emoji" type="hidden" value="love">
                                        <input class="love mb-[10px] w-[24px] h-24[px] border-0 bg-no-repeat bg-[50%] bg-white mt-0 cursor-pointer laughing" type="submit" value="">
                                        <p class="text-center love-count{{ $post->id }}">
                                            {{ $post->emojis->pluck('love')->sum() }}
                                        </p>
                                    </form>
                                    <script>
                                        $('.love{{ $post->id }}').on('submit', function(event){

                                            event.preventDefault();

                                            $.ajax({
                                                url: "{{ url('posts/'.$post->id.'/emoji') }}",
                                                data: $('.love{{ $post->id }}').serialize(),
                                                type: 'post',

                                                success: function (result) {
                                                    $('.love-count{{ $post->id }}').html(result.emoji);
                                                    $('.love{{ $post->id }}')[0].reset();
                                                }
                                            });
                                        });
                                    </script>

                                    <form class="w-[25px] shocked{{ $post->id }} flex flex-col" action="{{ route('emoji', $post->id) }}" method="POST">
                                        @csrf
                                        <input name="emoji" type="hidden" value="shocked">
                                        <input class="shocked mb-[10px] w-[24px] h-24[px] border-0 bg-no-repeat bg-[50%] bg-white mt-0 cursor-pointer laughing" type="submit" value="">
                                        <p class="text-center shocked-count{{ $post->id }}">
                                            {{ $post->emojis->pluck('shocked')->sum() }}
                                        </p>
                                    </form>
                                    <script>
                                        $('.shocked{{ $post->id }}').on('submit', function(event){

                                            event.preventDefault();

                                            $.ajax({
                                                url: "{{ url('posts/'.$post->id.'/emoji') }}",
                                                data: $('.shocked{{ $post->id }}').serialize(),
                                                type: 'post',

                                                success: function (result) {
                                                    $('.shocked-count{{ $post->id }}').html(result.emoji);
                                                    $('.shocked{{ $post->id }}')[0].reset();
                                                }
                                            });
                                        });
                                    </script>

                                    <form class="w-[25px] cute{{ $post->id }} flex flex-col" action="{{ route('emoji', $post->id) }}" method="POST">
                                        @csrf
                                        <input name="emoji" type="hidden" value="cute">
                                        <input class="cute mb-[10px] w-[24px] h-24[px] border-0 bg-no-repeat bg-[50%] bg-white mt-0 cursor-pointer laughing" type="submit" value="">
                                        <p class="text-center cute-count{{ $post->id }}">
                                            {{ $post->emojis->pluck('cute')->sum() }}
                                        </p>
                                    </form>
                                    <script>
                                        $('.cute{{ $post->id }}').on('submit', function(event){

                                            event.preventDefault();

                                            $.ajax({
                                                url: "{{ url('posts/'.$post->id.'/emoji') }}",
                                                data: $('.cute{{ $post->id }}').serialize(),
                                                type: 'post',

                                                success: function (result) {
                                                    $('.cute-count{{ $post->id }}').html(result.emoji);
                                                    $('.cute{{ $post->id }}')[0].reset();
                                                }
                                            });
                                        });
                                    </script>

                                    <form class="w-[25px] sad{{ $post->id }} flex flex-col" action="{{ route('emoji', $post->id) }}" method="POST">
                                        @csrf
                                        <input name="emoji" type="hidden" value="sad">
                                        <input class="sad mb-[10px] w-[24px] h-24[px] border-0 bg-no-repeat bg-[50%] bg-white mt-0 cursor-pointer laughing" type="submit" value="">
                                        <p class="text-center sad-count{{ $post->id }}">
                                            {{ $post->emojis->pluck('sad')->sum() }}
                                        </p>
                                    </form>
                                    <script>
                                        $('.sad{{ $post->id }}').on('submit', function(event){

                                            event.preventDefault();

                                            $.ajax({
                                                url: "{{ url('posts/'.$post->id.'/emoji') }}",
                                                data: $('.sad{{ $post->id }}').serialize(),
                                                type: 'post',

                                                success: function (result) {
                                                    $('.sad-count{{ $post->id }}').html(result.emoji);
                                                    $('.sad{{ $post->id }}')[0].reset();
                                                }
                                            });
                                        });
                                    </script>

                                    <form class="rounded-none w-[25px] angry{{ $post->id }} flex flex-col" action="{{ route('emoji', $post->id) }}" method="POST">
                                        @csrf
                                        <input name="emoji" type="hidden" value="angry">
                                        <input class="angry mb-[10px] w-[24px] h-24[px] border-0 bg-no-repeat bg-[50%] bg-white mt-0 cursor-pointer laughing" type="submit" value="">
                                        <p class="text-center angry-count{{ $post->id }}">
                                            {{ $post->emojis->pluck('angry')->sum() }}
                                        </p>
                                    </form>
                                    <script>
                                        $('.angry{{ $post->id }}').on('submit', function(event){

                                            event.preventDefault();

                                            $.ajax({
                                                url: "{{ url('posts/'.$post->id.'/emoji') }}",
                                                data: $('.angry{{ $post->id }}').serialize(),
                                                type: "post",

                                                success: function(result) {
                                                    $('.angry-count{{ $post->id }}').html(result.emoji);
                                                    $('.angry{{ $post->id }}')[0].reset();
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- увеличение картинки поста и появление групы емоджи --}}
                    <script>
                        $(document).ready(function(){
                            $('.emojies-click{{ $post->id }}').on('click', function(){
                                $('.emojies-group{{ $post->id }}').slideToggle();
                                //$('.emojies-group{{ $post->id }}').css({'display':'flex'});
                            });
                        });

                        $(document).click(function(e) {
                            $(document).mouseup( function(e){ // событие клика по веб-документу
                                    var div = $( ".emojies-group{{ $post->id }}" ); // тут указываем ID элемента
                                    if ( !div.is(e.target) // если клик был не по нашему блоку
                                        && div.has(e.target).length === 0 ) { // и не по его дочерним элементам
                                        div.fadeOut(); // скрываем его
                                    }
                            });
                        });


                        // увеличение картинки поста по клику
                        $('.post-img{{ $post->id }}').hover(function(){
                            $('.post-img{{ $post->id }}').css({'cursor':'zoom-in'});
                        });
                        $('.post-img{{ $post->id }}').click(function(){
                            $('.post-img{{ $post->id }}').css({'transform':'scale(1.3)','transition':'0.5s','cursor':'default', 'position': 'relative', 'z-index': 2{{ $post->id }}});
                        });
                        $('.post-img{{ $post->id }}').mouseout(function(){
                            $('.post-img{{ $post->id }}').css({'transform':'scale(1)','transition':'0.5s'});
                        });

                    </script>

                    {{-- comments --}}
                    <div class="hidden w-full comments_container{{ $post->id }}">
                        {{-- comment Form --}}
                        @auth
                            {{-- form --}}
                            <form method="POST" action="{{ route('addComment', $post->id) }}" class="mt-[10px] flex commentsForm{{ $post->id }}">
                                <div class="flex flex-col items-center">
                                    @csrf
                                    <span class="w-[50px] mb-[10px]">
                                        <img src="{{ asset(auth()->user()->user_image) }}">
                                    </span>
                                    <button class="w-[150px] inline-block px-[20px] py-[10px] bg-[#baae69] text-white rounded-md cursor-pointer" type="submit">Add comment</button>
                                </div>
                                <textarea class="border outline-none p-[15px] ml-[30px] h-[105px] rounded-md w-[585px]" name="comment" cols="30" rows="10" placeholder="Comment..."></textarea>
                            </form>

                            {{--  add new Comment ajax --}}
                            @if (!is_null(auth()->user()))
                                <script>
                                    $('.commentsForm{{ $post->id }}').on('submit', function(event) {
                                        event.preventDefault();

                                        $.ajax({
                                            data : $('.commentsForm{{ $post->id }}').serialize(),
                                            url : "{{ url('posts/'.$post->id.'/addComment') }}",
                                            type : "post",

                                            success: function(result) {
                                            $('.comments{{ $post->id }}').prepend(
                                                    '<div class="p-[20px] mt-[30px] border border-gray-400 rounded-md text-justify">'+
                                                        '<div class="flex items-center gap-[30px]">'+
                                                            '<div class="comment-user-img">'+
                                                                '<img class="w-[50px] rounded-full" src="{{ asset(auth()->user()->user_image) }}">'+
                                                            '</div>'+
                                                            '<p class="comment-user-name">'+
                                                                '<a href="{{ url('user/'.auth()->user()->id) }}" class="font-semibold hover:underline">{{ (auth()->user()->login) }}</a>'+
                                                            '</p>'+
                                                            '<p class="italic">'+ result.date +'</p>'+
                                                        '</div>'+
                                                        '<div class="mt-[20px] leading-[1.6]">'+ result.comment.comment_text +'</div>'+
                                                    '</div>'
                                                );

                                            $ ('html, body') .animate ({
                                                scrollTop: $ (".comments{{ $post->id }}") .offset().top
                                            }, 500);

                                            $('.comments-count{{$post->id}}').text(parseInt($('.comments-count{{$post->id}}').text()) + 1);
                                            $('.commentsForm{{ $post->id }}')[0].reset();
                                            }
                                        });
                                    });
                                </script>
                            @endif
                        @endauth

                        {{-- get comments in DB--}}
                        <div class="mb-[20px] comments{{ $post->id }}">
                            @foreach ($post->comments as $comment)
                                <div class="p-[20px] mt-[30px] border-t-2 border-gray-100 rounded-md shadow-md text-justify">
                                    <div class="flex items-center gap-[30px]">
                                        <div class="comment-user-img">
                                            <img class="w-[50px] rounded-full" src="{{ asset($comment->user->user_image) }}">
                                        </div>
                                        <p class="comment-user-name">
                                            <a class="font-semibold hover:underline" href="{{ url('user/'.$comment->user->id) }}">
                                                {{ ($comment->user->login) }}
                                            </a>
                                        </p>
                                        <p class="italic">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="mt-[20px] leading-[1.6]">{{ $comment->comment_text }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        @endforeach

        {{-- пагинация --}}
        <div class="pag">{{ $posts->links() }}</div>

    </main>

    {{-- sidebar--}}
    <sidebar class="p-[20px] ml-[20px] w-[310px] bg-white rounded-md shadow-md grow-0 self-start">
        {{-- auth user --}}
        <div>
            @auth
                <p class="auth-user">Hello <span class="text-[18px] text-red-500 font-bold">{{ auth()->user()->login }} !</span></p>
            @endauth

            @guest
                <form action="{{ route('login') }}" method="GET" class="flex flex-col items-center">
                    @csrf
                    <h3 class="font-semibold text-[18px] mb-[10px]">Login</h3>
                    <div>
                        <input class="py-2 px-4 mb-[10px] w-[270px] border rounded-md outline-none" name="email" type="email" placeholder="Email">
                    </div>
                    <div>
                        <input class="py-2 px-4 mb-[10px] w-[270px] border rounded-md outline-none" name="password" type="password" placeholder="Password">
                    </div>
                    <div>
                        <input class="py-2 px-4 w-[270px] bg-[#baae69] text-white rounded-md border-none cursor-pointer inline-block font-bold" type="submit" value="Login">
                    </div>
                    <div class="mt-[10px]">
                        <a href="{{ route('reg.create') }}">Haven't account ? Create !</a>
                    </div>
                </form>
            @endguest
        </div>

        {{-- popular posts --}}
        <div class="mt-[50px]">
            <h2 class="font-medium text-[20px] mb-[20px]">Popular Posts</h2>
            <ul>
                @foreach ($likes as $post_id => $post_likes)
                    <li class="flex">
                        <div class="w-[170px]">
                            <span class="font-medium">{{ $loop->index + 1 }}. </span>
                            <a href="{{ url("/posts?page=".intdiv($post_id, 10) + 1)."&scroll=$post_id" }}" class="font-medium underline mb-[10px]">
                                @php
                                    $p = App\Models\Post::find($post_id)->title;
                                    if(mb_strlen($p) > 15) {
                                        $p = htmlentities(substr($p, 0, 15));
                                        echo $p.' ...';
                                    }
                                @endphp
                            </a>
                        </div>
                        <span class="italic"> - {{ $post_likes }} like</span>
                    </li>
                @endforeach

                {{-- scroll --}}
                <script>
                    $.urlParam = function(name){
                        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
                        if (results == null) {
                            return null;
                        }
                        return decodeURI(results[1]) || 0;
                    }
                    //console.log('.' + $.urlParam('scroll'));

                    $('html, body').animate({
                        scrollTop: $('.' + $.urlParam('scroll')).offset().top
                    }, 1000);
                </script>
                <li></li>
            </ul>
        </div>

        {{-- popular tags --}}
        <div class="mt-[50px]">
            <h2 class="font-medium text-[20px] mb-[20px]">Popular Tags</h2>
            <ul>
                @foreach ($tags as $k => $v)
                    <li class="flex gap-2">
                        <div class="w-[160px]">
                            <span class="font-medium">{{ $loop->index + 1 }}. </span>
                            <a href="{{ url("/tag/".$tags_id[$loop->index])}}" class="font-medium underline text-blue-500">
                                {{ $k }}
                                @php
                                    if(mb_strlen($k) > 15) {
                                        $k = htmlentities(substr($k, 0, 15));
                                        echo $k.' ...';
                                    }
                                @endphp
                            </a>
                        </div>
                        <span class="italic"> - {{ $v }} post</span>
                    </li>
                @endforeach
                <li></li>
            </ul>
        </div>
    </sidebar>
@endsection
