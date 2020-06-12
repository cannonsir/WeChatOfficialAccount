<?php

namespace Gtd\WeChatOfficialAccount\Controllers;

use Gtd\WeChatOfficialAccount\Requests\MaterialRequest;
use EasyWeChat\Kernel\Messages\Article;
use Gtd\WeChatOfficialAccount\Models\Account;
use Illuminate\Support\Facades\Cache;

/**
 * Class MaterialController
 *
 * copied from:
 * @see \Gtd\Material\Controllers\MaterialController
 * @package Gtd\WeChatOfficialAccount\Controllers
 */
class MaterialController
{
    public function index(MaterialRequest $request, Account $account)
    {
        $data = $request->validated();

        $pageSize = $request->pageSize ?? 10;
        $page = $request->page ?? 1;
        $page = ($page - 1) * $pageSize;

        $key = md5('material' . $data['type'] . $page . $pageSize);
        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $res = $account->gateway()->material->list($data['type'], $page, $pageSize);

        /**
         * 缓存数据，缓存key
         */
        Cache::put($key, $res, now()->addHours(3));
        $typekeys_key = md5($data['type']);
        $typekeys =  Cache::get($typekeys_key, []);
        $typekeys[] = $key;
        Cache::put($typekeys_key, $typekeys);

        return $res;
    }

    private function rmCache($type)
    {
        $typekeys =  Cache::get(md5($type), []);
        array_map(function ($key) {
            Cache::forget($key);
        }, $typekeys);
    }

    public function store(MaterialRequest $request, Account $account)
    {
        $data = $request->validated();

        if (isset($data['url'])) {
            $res = explode('storage', $data['url']);
            if (count($res) != 2) {
                return abort(500, "文件不存在");
            }
            $path = storage_path('app/public' . $res[1]);

            if (!file_exists($path)) {
                return abort(500, "文件不存在");
            }
        }

        $this->rmCache($data['type']);

        switch ($data['type']) {
            case 'image':
                return $account->gateway()->material->uploadImage($path);
                break;
            case 'video':
                return $account->gateway()->material->uploadVideo($path, $data['title'], $data['introduction']);
                break;
            case 'voice':
                return $account->gateway()->material->uploadVoice($path);
                break;
            case 'news':
                $article = new Article($data);
                return $account->gateway()->material->uploadArticle($article);
            case 'thumb':
                return $account->gateway()->material->uploadThumb($path);
                break;
            default:
                break;
        }
    }

    public function destroy(MaterialRequest $request, Account $account, string $materialId)
    {
        $data  = $request->validated();

        $this->rmCache($data['type']);

        return $account->gateway()->material->delete($materialId);
    }
}
