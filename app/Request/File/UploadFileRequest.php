<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Request\File;

use Hyperf\Validation\Request\FormRequest;

class UploadFileRequest extends FormRequest
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
            'upload' => 'required|file',
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
            'upload' => '上传文件',
        ];
    }
}