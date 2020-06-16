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
        $router->apiResource('accounts', 'AccountController');

        $router->prefix('accounts/{wx_account}')->group(function ($router) {
            $router->get('users', 'UserController@index');
            $router->put('users', 'UserController@sync');

            $router->post('menu', 'MenuController@store');
            $router->get('menu', 'MenuController@index');
            $router->get('menu/current', 'MenuController@current');
            $router->get('menu/individuation', 'MenuController@individuationIndex');
            $router->delete('menu/{menuId}', 'MenuController@destroy');

            $router->post('user_tags/attach', 'UserTagController@attachTagForUsers');
            $router->post('user_tags/detach', 'UserTagController@detachTagForUsers');
            $router->post('user_tags/sync', 'UserTagController@syncTagForUsers');

            $router->apiResources([
                'user_tags' => 'UserTagController',
                'materials' => 'MaterialController'
            ]);
        });
    });
});
