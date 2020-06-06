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
}
