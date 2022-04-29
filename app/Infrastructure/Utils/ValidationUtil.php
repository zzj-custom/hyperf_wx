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

namespace App\Infrastructure\Utils;

use Contract\Exceptions\ValidationException;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

class ValidationUtil
{
    /** @var string[] 默认自定义信息 */
    public const DEFAULT_ATTRIBUTES = [
        'required' => ':attribute is required',
    ];

    /**
     * @throws ValidationException
     */
    public static function validateParams(array $params, array $rules, array $messages = [], array $customAttributes = []): void
    {
        $validationFactory = ApplicationContext::getContainer()->get(ValidatorFactoryInterface::class);
        if (empty($customAttributes)) {
            $customAttributes = static::DEFAULT_ATTRIBUTES;
        }
        $validator = $validationFactory->make($params, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            throw new ValidationException($validator->errors()->first(), 10001);
        }
    }
}
