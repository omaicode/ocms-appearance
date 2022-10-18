<?php

Route::prefix(config('core.admin_prefix', 'admin'))->group(function() {
    Route::group([
        'namespace' => 'Admin',
        'prefix'    => 'appearance',
        'as'        => 'admin.appearance.',
        'middleware'=> 'auth:admins'
    ], function() {
        Route::get('/themes', 'ThemeController@index')->name('themes');
    });
});