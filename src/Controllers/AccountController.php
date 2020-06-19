<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Osi\LaravelControllerTrait\Traits\ControllerBaseTrait;
use Gtd\WeChatOfficialAccount\Models\Account;

/**
 * 公众号账号
 *
 * Class OfficialAccountController
 * @package Plugins\WeChat\Controllers\OfficialAccount、
 */
class AccountController extends Controller
{
    use ControllerBaseTrait;

    public function __construct()
    {
        $this->model = new Account;
    }

    /*
     * 清理接口调用次数
     */
    public function cleanApiInvokeRecord(Request $request, Account $account)
    {
        return $account->gateway()->base->clearQuota();
    }

    /*
     * 获取微信服务器 IP (或IP段)
     */
    public function fetchWxIp(Request $request, Account $account)
    {
        return $account->gateway()->base->getValidIps();
    }
}
