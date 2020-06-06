<?php

namespace Gtd\WeChatOfficialAccount\Components;

use SmallRuralDog\Admin\Components\Component;

class WeChatOfficialAccount extends Component
{
    //组件的名称，等下注册vue组件的时候名称需要一致
    protected $componentName = "WeChatOfficialAccount";

    //需要隐藏的属性，设置后不会在json中输出，这个功能由父类实现
    public $hideAttrs = [];

    //定义一个make，这里的$value 是你组件的默认值，类型由你来决定
    public static function make()
    {
        return new static;
    }

    //自定义字段输出值，在表单编辑时会调用这个方法获取值，如果没定义则使用默认值
    public function getValue($data)
    {
        return $data;
    }
}
