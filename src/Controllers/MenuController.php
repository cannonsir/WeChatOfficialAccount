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
    /*
     * 创建菜单
     */
    public function store(Request $request, Account $account)
    {
        $request->validate([
            'button' => 'required|array',
            'matchrule' => 'array'
        ]);

        return $account->gateway()->menu->create($request->button, $request->matchrule ?? []);
    }

    /*
     * 获取个性化菜单列表
     */
    public function individuationIndex(Request $request, Account $account)
    {
        $response = $account->gateway()->menu->list();

        $menus = $response['conditionalmenu'] ?? [];

        return response()->json($menus);
    }

    /*
     * 获取当前菜单
     */
    public function current(Request $request, Account $account)
    {
        $response = $account->gateway()->menu->current();

        isset($response['errcode']) and abort(500, $response['errmsg']);

        $deep = function (array $buttons) use (&$deep) {
            foreach ($buttons as &$button) {
                if (isset($button['sub_button']['list'])) {
                    $button['sub_button'] = $button['sub_button']['list'];
                    $deep($button['sub_button']);
                }
            }

            return $buttons;
        };

        $button = $deep($response['selfmenu_info']['button']);

        return response()->json(compact('button'));
    }

    /*
     * 删除菜单
     */
    public function destroy(Request $request, Account $account, string $menuId)
    {
        return $account->gateway()->menu->delete($menuId);
    }
}
