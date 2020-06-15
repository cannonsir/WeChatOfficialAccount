<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gtd\WeChatOfficialAccount\Models\Account;
use Illuminate\Support\Facades\Cache;

/**
 * 公众号用户标签
 *
 * Class UserTagController
 * @package Plugins\WeChat\Controllers\OfficialAccount
 */
class UserTagController extends Controller
{
    const CACHE_KEY_PREFIX = 'WeChatOfficialAccount:user_tags:';

    public function index(Account $account, Request $request)
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $account->getKey();

        if (empty($tags = Cache::get($cacheKey))) {
            $tags = $account->gateway()->user_tag->list();
            isset($tags['errcode']) and abort(500, $tags['errmsg']);
            Cache::put($cacheKey, $tags, now()->addDay());
        }

        return $tags;
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

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return $account->gateway()->user_tag->create($request->name);
    }

    public function update(Account $account, Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required'
        ]);

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return $account->gateway()->user_tag->update($request->id, $request->name);
    }

    public function destroy(Account $account, Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return $account->gateway()->user_tag->update($request->id);
    }
}
