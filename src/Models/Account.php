<?php

namespace Gtd\WeChatOfficialAccount\Models;

use EasyWeChat\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Osi\LaravelControllerTrait\Models\FilterAndSorting;
use EasyWeChat\OfficialAccount\Application;

class Account extends Model
{
    use FilterAndSorting;

    const TYPE_SUBSCRIBE_ACCOUNT = '订阅号';

    const TYPE_SERVICE_ACCOUNT = '服务号';

    public static $types = [
        self::TYPE_SUBSCRIBE_ACCOUNT,
        self::TYPE_SERVICE_ACCOUNT,
    ];

    protected $keyType = 'string';

    protected $table = 'we_chat_official_accounts';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $account) {
            $account->id = (string) Str::uuid();
        });
    }

    /**
     * 获取微信公众号服务网关
     *
     * @param array $config 自定义配置 @see https://www.easywechat.com/docs/4.1/official-account/configuration
     * @return Application
     */
    public function gateway(array $config = []): Application
    {
        $config['app_id'] = $this->app_id;
        $config['secret'] = $this->app_secret;
        $config['token'] = $this->callback_token;
        $config['aes_key'] = $this->encoding_aes_key;
        $config['oauth']['callback'] = $this->callback_url;

        return Factory::officialAccount($config);
    }

    /**
     * 同步微信用户列表
     */
    public function syncUsers(): void
    {
        /**
         * @return \Generator 用户分块数据生成器
         */
        $generator = function (): \Generator
        {
            $gateway = $this->gateway();
            $nextOpenId = null;
            $hasNextPage = true;

            do {
                $response = $gateway->user->list($nextOpenId);

                $nextOpenId = $response['next_openid'];

                $ids = $response['data']['openid'] ?? [];

                // 查询的openId为空或者不足1W条，说明没有下一页
                empty($ids) || count($ids) !== 10000 and $hasNextPage = false;

                foreach (array_chunk($ids, 200) as $chunk)

                yield $gateway->user->select($chunk)['user_info_list'] ?? [];
            } while ($hasNextPage);
        };

        $table = (new User)->getTable();
        $now = (string) now();

        // 遍历同步至数据库
        foreach ($generator() as $users) foreach ($users as $user) {
            $user['tagid_list'] = json_encode($user['tagid_list']);
            $user['last_sync_at'] = $now;
            $user['account_id'] = $this->getKey();

            DB::table($table)->updateOrInsert(['openid' => $user['openid']], $user);
        }
    }

    /**
     * @return HasMany 用户列表关联
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
