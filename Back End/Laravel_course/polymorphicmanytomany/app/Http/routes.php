<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Post;
use App\Tag;
use App\Video;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create', function() {
    $post = Post::create(['name'=>'first post']);
    $tag1 = Tag::find(1);
    $post->tags()->save($tag1);

    $video = Video::create(['name'=>'video.mp4']);
    $tag2 = Tag::find(2);
    $video->tags()->save($tag2);
});

Route::get('/read', function() {
   $post = Post::find(3);
   foreach ($post->tags as $tag) {
       echo $tag;
   }
});
Route::get('/update', function() {
   $post = Post::find(3) ;
   foreach($post->tags as $tag){
       return $tag->whereName('php')->update(['name'=>'updated php']);
   }
});
Route::get('/update2', function() {
    $post = Post::find(3);
    $tag = Tag::find(2);
    $post->tags()->save($tag);
//    $post->tags()->attach($tag);
//    $post->tags()->sync($tag);
});
Route::get('/delete', function(){
   $post = Post::find(1);
   foreach ($post->tags as $tag){
       $tag->whereId(2)->delete();
   }
});