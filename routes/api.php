<?php


/*Route::post('/v1/users/login', 'api\v1\Users\Authentication@login')->name('v1.login.user');
Route::post('/v1/users/register', 'api\v1\Users\Authentication@register')->name('v1.register.user');
Route::post('/v1/users/logout', 'api\v1\Users\Authentication@logout')->name('v1.logout.user');*/

Route::prefix('v1')->namespace('api\v1')->group(static function () {
    Route::prefix('users')->namespace('Users')->group(static function () {
        Route::post('login', 'Authentication@login');
        Route::post('register', 'Authentication@register');
        Route::middleware('auth:api')->post('logout', 'Authentication@logout');
    });

    // user Todo List
    Route::prefix('todo')->namespace('Todo')->middleware('auth:api')->group(static function() {
        Route::get('list', 'Tasks@listTasks');
        Route::post('add-task', 'Tasks@addTask');
        Route::post('edit-task/{task}', 'Tasks@editTask');
        Route::delete('delete-task/{task}', 'Tasks@deleteTask');
        Route::post('mark-complete/{task}', 'Tasks@markComplete');
        Route::post('unmark-complete/{task}', 'Tasks@unMarkComplete');
    });
});
