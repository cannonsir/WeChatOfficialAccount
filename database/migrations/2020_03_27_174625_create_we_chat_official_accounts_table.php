<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Gtd\WeChatOfficialAccount\Models\Account;

class CreateWeChatOfficialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('we_chat_official_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('avatar')->comment('头像');
            $table->string('name')->comment('名称');
            $table->string('desc')->nullable();
            $table->string('app_id');
            $table->string('app_secret');
            $table->string('callback_url')->nullable()->comment('接口回调地址');
            $table->string('callback_token')->nullable()->comment('接口回调token');
            $table->string('encoding_aes_key')->nullable()->comment('用作消息体加解密密钥');
            $table->enum('type', Account::$types)->comment('类型');
            $table->string('qr_code')->nullable()->comment('二维码');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('we_chat_official_accounts');
    }
}
