<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gtd\WeChatOfficialAccount\Models\Account;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
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

    public function index(Request $request, Account $account)
    {
        $cacheKey = self::CACHE_KEY_PREFIX . $account->getKey();

        if (empty($tags = Cache::get($cacheKey))) {
            $tags = $account->gateway()->user_tag->list();
            isset($tags['errcode']) and abort(500, $tags['errmsg']);
            Cache::put($cacheKey, $tags, now()->addDay());
        }

        return $tags;
    }

    /*
     * 批量为部分用户设定某标签
     */
    public function attachTagForUsers(Request $request, Account $account)
    {
        $request->validate([
            'tagIds'=> 'required|array',
            'openIds' => 'required|array',
        ]);

        foreach($request->tagIds as $tagId) {
            $account->gateway()->user_tag->tagUsers($request->openIds, $tagId);
        }

        $account->syncUsers();

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return response(null, Response::HTTP_ACCEPTED);
    }

    /*
     * 批量为部分用户取消设定某标签
     */
    public function detachTagForUsers(Request $request, Account $account)
    {
        $request->validate([
            'tagId'=> 'required',
            'openIds' => 'required|array',
        ]);

        return $account->gateway()->user_tag->untagUsers($request->openIds, $request->tagId);
    }

    public function syncTagForUsers(Request $request, Account $account)
    {
        $request->validate([
            'tagIds'=> 'present|array',
            'openIds' => 'required|array',
        ]);

        // 同步一下标签
        $tags = $this->index($request, $account)['tags'];

        $exceptTagIds = array_values(array_diff(array_map(fn ($tag) => $tag['id'], $tags), $request->tagIds));

        // attach
        foreach($request->tagIds as $tagId) $account->gateway()->user_tag->tagUsers($request->openIds, $tagId);

        // detach
        foreach ($exceptTagIds as $tagId) $account->gateway()->user_tag->untagUsers($request->openIds, $tagId);

        $account->syncUsers();

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return response(null, Response::HTTP_ACCEPTED);
    }

    public function store(Request $request, Account $account)
    {
        $request->validate(['name' => 'required']);

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return $account->gateway()->user_tag->create($request->name);
    }

    public function update(Account $account, Request $request, string $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return $account->gateway()->user_tag->update($id, $request->name);
    }

    public function destroy(Request $request, Account $account, string $id)
    {
        $account->syncUsers();

        Cache::forget(self::CACHE_KEY_PREFIX . $account->getKey());

        return $account->gateway()->user_tag->delete($id);
    }
}
