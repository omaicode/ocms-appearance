<?php

Route::prefix(config('core.admin_prefix', 'admin'))->group(function() {
    Route::group([
        'namespace' => 'Admin',
        'prefix'    => 'appearance',
        'as'        => 'admin.appearance.',
        'middleware'=> 'auth:admins'
    ], function() {
        Route::get('/themes', 'ThemeController@index')->name('themes');
        Route::get('/theme-options', 'ThemeOptionController@index')->name('theme-options');
        Route::get('/theme-options/seo', 'ThemeOptionController@seo')->name('theme-options.seo');
        Route::get('/theme-options/images', 'ThemeOptionController@images')->name('theme-options.images');
        Route::get('/theme-options/social-links', 'ThemeOptionController@socials')->name('theme-options.socials');

        Route::post('/themes/set', 'ThemeController@setTheme')->name('themes.set');
        Route::post('/theme-options', 'ThemeOptionController@update')->name('theme-options.save');

        Route::post('/menus/update-order', 'MenuController@updateOrder')->name('menus.update-order');
        Route::delete('/menus/{id?}', 'MenuController@destroy')->name('menus.destroy');
        Route::resource('menus', 'MenuController', ['except' => ['update', 'edit', 'create', 'destroy']]);
    });
});