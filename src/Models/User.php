<?php

namespace Gtd\WeChatOfficialAccount\Models;

use Illuminate\Database\Eloquent\Model;
use Osi\LaravelControllerTrait\Models\FilterAndSorting;

class User extends Model
{
    use FilterAndSorting;

    const GENDER_MAN = 1;
    const GENDER_WOMAN = 2;

    const SOURCE_ADD_SCENE_SEARCH = 'ADD_SCENE_SEARCH';
    const SOURCE_ADD_SCENE_ACCOUNT_MIGRATION = 'ADD_SCENE_ACCOUNT_MIGRATION';
    const SOURCE_ADD_SCENE_PROFILE_CARD = 'ADD_SCENE_PROFILE_CARD';
    const SOURCE_ADD_SCENE_QR_CODE = 'ADD_SCENE_QR_CODE';
    const SOURCE_ADD_SCENE_PROFILE_LINK = 'ADD_SCENE_PROFILE_LINK';
    const SOURCE_ADD_SCENE_PROFILE_ITEM = 'ADD_SCENE_PROFILE_ITEM';
    const SOURCE_ADD_SCENE_PAID = 'ADD_SCENE_PAID';
    const SOURCE_ADD_SCENE_OTHERS = 'ADD_SCENE_OTHERS';

    public static $genders = [
        self::GENDER_MAN,
        self::GENDER_WOMAN,
    ];

    public static $sources = [
        self::SOURCE_ADD_SCENE_SEARCH,
        self::SOURCE_ADD_SCENE_ACCOUNT_MIGRATION,
        self::SOURCE_ADD_SCENE_PROFILE_CARD,
        self::SOURCE_ADD_SCENE_QR_CODE,
        self::SOURCE_ADD_SCENE_PROFILE_LINK,
        self::SOURCE_ADD_SCENE_PROFILE_ITEM,
        self::SOURCE_ADD_SCENE_PAID,
        self::SOURCE_ADD_SCENE_OTHERS,
    ];

    public static $sourceCnMap = [
        self::SOURCE_ADD_SCENE_SEARCH => '公众号搜索',
        self::SOURCE_ADD_SCENE_ACCOUNT_MIGRATION => '公众号迁移',
        self::SOURCE_ADD_SCENE_PROFILE_CARD => '名片分享',
        self::SOURCE_ADD_SCENE_QR_CODE => '扫描二维码',
        self::SOURCE_ADD_SCENE_PROFILE_LINK => '图文页内名称点击',
        self::SOURCE_ADD_SCENE_PROFILE_ITEM => '图文页右上角菜单',
        self::SOURCE_ADD_SCENE_PAID => '支付后关注',
        self::SOURCE_ADD_SCENE_OTHERS => '其他',
    ];

    protected $table = 'we_chat_official_account_users';

    protected $guarded = [];

    protected $casts = [
        'tagid_list' => 'array',
        'subscribe' => 'bool',
        'sex' => 'integer',
        'subscribe_time' => 'date:Y-m-d H:i:s'
    ];
}
