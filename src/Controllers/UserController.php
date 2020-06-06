<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Gtd\WeChatOfficialAccount\Models\Account;

/**
 * 公众号用户相关
 *
 * Class UserController
 * @package Plugins\WeChat\Controllers\OfficialAccount
 */
class UserController extends Controller
{
    public function index(Account $account, Request $request)
    {
        return new ResourceCollection(
            $account->users()->setFilterAndRelationsAndSort($request)->paginate($request->get('pageSize', 15))
        );
    }

    /**
     * 同步用户数据
     */
    public function sync(Account $account)
    {
        $account->syncUsers();

        return response(null, Response::HTTP_ACCEPTED);
    }
}
