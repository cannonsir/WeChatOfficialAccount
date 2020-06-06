<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use App\Http\Controllers\Controller;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Http\Request;
use \EasyWeChat\Factory;
use Carbon\Carbon;

class WeChatController extends Controller
{
    public static function getTestWeChatAccount(): Application
    {
        return Factory::officialAccount([
            'app_id' => 'wxd87a9207a76938d1',
            'secret' => 'eddf583e488d51bdb444dcdf331b0845',
            'response_type' => 'array',
        ]);
    }

    public function createMenu(Request $request)
    {
        $request->validate([
            'button' => 'required|array',
            'matchrule' => 'array'
        ]);

        return self::getTestWeChatAccount()->menu->create($request->button, $request->matchrule ?? []);
    }

    public function getUserList(Request $request)
    {
        return $this->transformUserListFormat(self::getTestWeChatAccount()->user->list($request->next ?? null));
    }

    protected function transformUserListFormat(array $meta)
    {
        $users = array_map(function ($openid) {
            $user = self::getTestWeChatAccount()->user->get($openid);
            $user['subscribe_time_'] = new Carbon($user['subscribe_time']);
            return $user;
        }, $meta['data']['openid'] ?? []);

        return compact('users', 'meta');
    }

    public function getAllUserTags(Request $request)
    {
        return self::getTestWeChatAccount()->user_tag->list();
    }

    public function createUserTag(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        return self::getTestWeChatAccount()->user_tag->create($validated['name']);
    }

    public function updateUserTag(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'name' => 'required'
        ]);

        return self::getTestWeChatAccount()->user_tag->update($validated['id'], $validated['name']);
    }

    public function deleteUserTag(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required'
        ]);

        return self::getTestWeChatAccount()->user_tag->update($validated['id']);
    }

    public function getUsersOfTag(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'next' => 'string',
        ]);

        $users = self::getTestWeChatAccount()->user_tag->usersOfTag($validated['id'], $validated['next'] ?? null);

        return $this->transformUserListFormat($users);
    }

    public function attachTags(Request $request)
    {
        $request->validate([
            'openIds' => 'required|array',
            'tagId'=> 'required'
        ]);

        return self::getTestWeChatAccount()->user_tag->tagUsers($request->openIds, $request->tagId);
    }

    public function detachTags(Request $request)
    {
        $request->validate([
            'openIds' => 'required|array',
            'tagId'=> 'required'
        ]);

        return self::getTestWeChatAccount()->user_tag->untagUsers($request->openIds, $request->tagId);
    }

    public function syncUserList()
    {
        $account = self::getTestWeChatAccount();

        $openIds = [];
        $nextOpenId = null;
        $hasNextPage = true;

        do {
            $response = $account->user->list($nextOpenId);

            $nextOpenId = $response['next_openid'];

            $ids = $response['data']['openid'] ?? [];

            empty($ids) || count($ids) !== 10000 and $hasNextPage = false;

            $openIds = array_merge($openIds,$ids);
        } while ($hasNextPage);

        $users = $account->user->select($openIds)['user_info_list'] ?? [];

        // TODO 同步至指定公众号用户数据中

        dd($openIds);

        dd($users);
    }
}
