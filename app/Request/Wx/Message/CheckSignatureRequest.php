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

namespace App\Request\Wx\Message;

use Hyperf\Validation\Request\FormRequest;

class CheckSignatureRequest extends FormRequest
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
            'signature' => 'required|string',
            'timestamp' => 'required|int',
            'nonce'     => 'required|string',
            'echostr'   => 'required| string',
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
            'required' => ':attribute 必填',
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
