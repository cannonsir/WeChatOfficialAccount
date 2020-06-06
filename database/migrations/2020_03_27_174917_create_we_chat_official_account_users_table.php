<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeChatOfficialAccountUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('we_chat_official_account_users', function (Blueprint $table) {
            $table->uuid('account_id')->index()->comment('所属账户');
            /**
             * @see https://developers.weixin.qq.com/doc/offiaccount/User_Management/Get_users_basic_information_UnionID.html#UinonId
             */
            $table->tinyInteger('subscribe')->comment('用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。');
            $table->string('openid')->primary()->comment('用户的标识，对当前公众号唯一');
            $table->string('nickname')->nullable()->comment('用户的昵称');
            $table->tinyInteger('sex')->index()->nullable()->comment('用户的性别，值为1时是男性，值为2时是女性，值为0时是未知');
            $table->string('language')->nullable()->comment('用户的语言，简体中文为zh_CN');
            $table->string('city')->nullable()->comment('用户所在城市');
            $table->string('province')->nullable()->comment('用户所在省份');
            $table->string('country')->nullable()->comment('用户所在国家');
            $table->string('headimgurl')->nullable()->comment('用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。');
            $table->string('subscribe_time')->nullable()->comment('用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间');
            $table->string('unionid')->nullable()->comment('只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。');
            $table->string('remark')->nullable()->comment('公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注');
            $table->integer('groupid')->index()->nullable()->comment('用户所在的分组ID（兼容旧的用户分组接口）');
            $table->json('tagid_list')->nullable()->comment('用户被打上的标签ID列表');
            $table->string('subscribe_scene')->nullable()->comment('返回用户关注的渠道来源，ADD_SCENE_SEARCH 公众号搜索，ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，ADD_SCENE_PROFILE_CARD 名片分享，ADD_SCENE_QR_CODE 扫描二维码，ADD_SCENE_PROFILE_LINK 图文页内名称点击，ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，ADD_SCENE_PAID 支付后关注，ADD_SCENE_OTHERS 其他');
            $table->string('qr_scene')->nullable()->comment('二维码扫码场景（开发者自定义）');
            $table->string('qr_scene_str')->nullable()->comment('二维码扫码场景描述（开发者自定义）');

            $table->timestamp('last_sync_at')->comment('上一次同步时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_chat_official_account_users');
    }
}
