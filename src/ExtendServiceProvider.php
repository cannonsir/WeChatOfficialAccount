<?php

namespace Gtd\WeChatOfficialAccount;

use Gtd\WeChatOfficialAccount\Models\Account;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use SmallRuralDog\Admin\Admin;

class ExtendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Admin::script('WeChatOfficialAccount', __DIR__.'/../dist/js/extend.js');

        // 路由显示绑定
        Route::model('wx_account', Account::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRouter();
        $this->publishMigration();
    }

    /**
     * 注册路由
     *
     * @author osi
     */
    private function registerRouter()
    {
        if (strpos($this->app->version(), 'Lumen') === false && !$this->app->routesAreCached()) {
            app('router')->namespace('Gtd\WeChatOfficialAccount\Controllers')->group(__DIR__ . '/../routes/route.php');
        } else {
            $this->loadRoutesFrom(__DIR__ . '/../routes/route.php');
        }
    }

    /**
     * 发布迁移文件
     */
    private function publishMigration()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')]);
        }
    }
}
