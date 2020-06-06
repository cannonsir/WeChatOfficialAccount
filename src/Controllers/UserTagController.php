<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gtd\WeChatOfficialAccount\Models\Account;

/**
 * 公众号用户标签
 *
 * Class UserTagController
 * @package Plugins\WeChat\Controllers\OfficialAccount
 */
class UserTagController extends Controller
{
    public function index(Account $account, Request $request)
    {
        return $account->gateway()->user_tag->list();
    }

    /**
     * 批量为部分用户设定某标签
     */
    public function attachTagForUsers(Account $account, Request $request)
    {
        $request->validate([
            'openIds' => 'required|array',
            'tagId'=> 'required'
        ]);

        return $account->gateway()->user_tag->tagUsers($request->openIds, $request->tagId);
    }

    /**
     * 批量为部分用户取消设定某标签
     */
    public function detachTagForUsers(Account $account, Request $request)
    {
        $request->validate([
            'openIds' => 'required|array',
            'tagId'=> 'required'
        ]);

        return $account->gateway()->user_tag->untagUsers($request->openIds, $request->tagId);
    }

    public function store(Account $account, Request $request)
    {
        $request->validate(['name' => 'required']);

        return $account->gateway()->user_tag->create($request->name);
    }

    public function update(Account $account, Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required'
        ]);

        return $account->gateway()->user_tag->update($request->id, $request->name);
    }

    public function destroy(Account $account, Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        return $account->gateway()->user_tag->update($request->id);
    }
}
