<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gtd\WeChatOfficialAccount\Models\Account;

/**
 * 公众号自定义菜单
 *
 * Class MenuController
 * @package Plugins\WeChat\Controllers\OfficialAccount、
 */
class MenuController extends Controller
{
    /**
     * 创建菜单
     */
    public function store(Account $account, Request $request)
    {
        $request->validate([
            'button' => 'required|array',
            'matchrule' => 'array'
        ]);

        return $account->gateway()->menu->create($request->button, $request->matchrule ?? []);
    }
}
