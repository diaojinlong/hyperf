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
    }
}