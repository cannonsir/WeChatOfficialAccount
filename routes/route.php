<?php
$router = app('router');

$router->group([
    'prefix' => config('admin.route.prefix'),
    'middleware' => config('admin.route.middleware'),
], function ($router) {

});

//
$router->group([
    'prefix' => config('admin.route.api_prefix'),
    'middleware' => config('admin.route.middleware'),
], function ($router) {
    $router->resource('wechat-official-accounts', 'ExtendController');

    $router->prefix('WeChatOfficialAccount')->group(function ($router) {
        $router->prefix('accounts/{account}')->group(function ($router) {
            $router->get('users', 'UserController@index');
            $router->put('users', 'UserController@sync');

            $router->post('menu', 'MenuController@store');

            $router->post('user_tags/attach', 'UserTagController@attachTagForUsers');
            $router->post('user_tags/detach', 'UserTagController@detachTagForUsers');

            $router->apiResources([
                'user_tags' => 'UserTagController',
            ]);
        });

        $router->apiResource('accounts', 'AccountController');
    });
});
