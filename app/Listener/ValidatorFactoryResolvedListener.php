<?php

namespace App\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;

/**
 * @Listener
 */
class ValidatorFactoryResolvedListener implements ListenerInterface
{

    public function listen(): array
    {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process(object $event)
    {
        /**
         * @var ValidatorFactoryInterface $validatorFactory
         */
        $validatorFactory = $event->validatorFactory;
        // 注册了 tel 验证器
        $validatorFactory->extend('tel', function ($attribute, $value, $parameters, $validator) {
            return (boolean) preg_match('/^1\d{10}$/', $value);
        });

        // 注册 数组键 验证器 'user_info'=> 'array_key_exists:name,sex'  校验user_info数组键必须包含name和sex
        $validatorFactory->extend('array_key_exists', function ($attribute, $value, $parameters, $validator) {
            if (!is_array($value))
                return false;
            foreach ($parameters as $field)
                if (!isset($value[$field]))
                    return false;
            return true;
        });
        $validatorFactory->replacer('array_key_exists', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':keys', join(' ', $parameters), $message);
        });
    }
}