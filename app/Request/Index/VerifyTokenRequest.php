<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Request\Index;

use Hyperf\Validation\Request\FormRequest;

class VerifyTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'ToUserName'   => 'required|string',
            'FromUserName' => 'required|string',
            'CreateTime'   => 'required|integer',
            'MsgType'      => 'required|string',
            'Content'      => 'required|string',
            'MsgId'        => 'required|integer',
            'openid'       => 'required|string',
            'signature'    => 'required|string',
            'timestamp'    => 'required|int',
            'nonce'        => 'required|string',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息.
     *
     * @return string[]
     */
    public function messages(): array
    {
        // TODO: Change the autogenerated stub
        return [
            'ToUserName'   => '开发者微信号',
            'FromUserName' => '发送方帐号（一个OpenID）',
            'CreateTime'   => '消息创建时间 （整型）',
            'MsgType'      => '消息类型，文本为text',
            'Content'      => '文本消息内容',
            'MsgId'        => '消息id，64位整型',
            'openid'       => 'openId',
            'signature'    => '验签',
            'timestamp'    => '时间戳',
            'nonce'        => '随机数',
        ];
    }

    /**
     * 获取验证错误的自定义属性.
     */
    public function attributes(): array
    {
        // TODO: Change the autogenerated stub
        return parent::attributes();
    }
}
