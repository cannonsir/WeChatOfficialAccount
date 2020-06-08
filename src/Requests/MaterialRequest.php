<?php

namespace Gtd\WeChatOfficialAccount\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialRequest extends FormRequest
{
    /**
     * 用户是否有权限请求
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 精确到控制器方法的验证
     *
     * @return array
     */
    public function rules()
    {
        switch (request()->route()->getActionMethod()) {
            case 'index':
                return $this->indexRule();
                break;
            case 'store':
                return $this->storeRule();
                break;
            case 'update':
                return $this->updateRule();
                break;
            case 'destroy':
                return $this->destroyRule();
                break;

            default:
                return [];
                break;
        }
    }

    private function indexRule()
    {
        $rule = [
            'type' => [
                'required',
                'string',
                Rule::in(['image', 'video', 'news', 'voice', 'thumb'])
            ],
        ];

        return $rule;
    }

    private function storeRule()
    {
        $rule = [
            'type' => [
                'required',
                'string',
                Rule::in(['image', 'video', 'news', 'voice', 'thumb'])
            ],
        ];
        $type =  request('type');

        if ($type == 'image') {
            $tmp = [
                'url' => 'required|string|url'
            ];
            return array_merge($rule, $tmp);
        }
        if ($type == 'thumb') {
            $tmp = [
                'url' => 'required|string|url'
            ];
            return array_merge($rule, $tmp);
        }

        if ($type == 'video') {
            $tmp = [
                'title' => 'required|string',
                'introduction' => 'required|string',
                'url' => 'required|string|url'
            ];
            return array_merge($rule, $tmp);
        }
        if ($type == 'voice') {
            $tmp = [
                'url' => 'required|string|url'
            ];
            return array_merge($rule, $tmp);
            return $rule;
        }
        if ($type == 'news') {
            $tmp = [
                'title' => 'required|string',
                'content' => 'required|string',
                'author' => 'required|string',
                'digest' => 'nullable|string',
                'source_url' => 'nullable|string',
                'thumb_media_id' => 'required|string',
                'show_cover' => 'required|boolean',

            ];
            return array_merge($rule, $tmp);
            return $rule;
        }
        return $rule;
    }

    private function updateRule()
    {
        $rule = [
            #DummyRule
        ];

        return $rule;
    }
    private function destroyRule()
    {
        $rule = [
            'type' => [
                'required',
                'string',
                Rule::in(['image', 'video', 'news', 'voice', 'thumb'])
            ]
        ];
        return $rule;
    }


    public function attributes()
    {
        return [
            #DummyAttr
        ];
    }

    // 常用验证方法 https://learnku.com/docs/laravel/5.8/validation/3899#rule-accepted
}
