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

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');


//displaying the post and comment section in each post
Route::get('/post/{id}', ['as'=>'home.post' ,'uses'=> 'AdminPostsController@post']);

Route::group(['middleware'=>'admin'], function(){
    Route::get('/admin', function(){
        return view('admin.index');
    });

    Route::resource('admin/users', 'AdminUsersController');

    Route::resource('admin/posts', 'AdminPostsController');

    Route::resource('admin/categories', 'AdminCategoriesController');

    Route::resource('admin/media', 'AdminMediasController');

    Route::delete('admin/delete/media', 'AdminMediasController@deleteMedia');

    Route::resource('admin/comments', 'PostCommentsController');

    Route::resource('admin/comment/replies', 'CommentRepliesController');

});

Route::group(['middle'=>'auth'], function() {
    Route::post('comment/reply', 'CommentRepliesController@createReply');
});
