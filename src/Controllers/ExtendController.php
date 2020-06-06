<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use Gtd\WeChatOfficialAccount\Components\WeChatOfficialAccount;
use SmallRuralDog\Admin\Components\Widgets\Html;
use SmallRuralDog\Admin\Controllers\AdminController;

class ExtendController extends AdminController
{
    public function grid() {
        return WeChatOfficialAccount::make();
    }
}
