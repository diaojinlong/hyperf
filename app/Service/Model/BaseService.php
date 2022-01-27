<?php

namespace App\Service\Model;


use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Annotation\Inject;

class BaseService
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * __get
     * 隐式注入服务类
     * @param $key
     * @return \Psr\Container\ContainerInterface|void
     */
    public function __get($key)
    {
        if ($key == 'app') {
            return $this->container;
        } elseif (substr($key, -5) == 'Model') {
            return $this->getModelInstance($key);
        } else {
            throw new BusinessException(ErrorCode::ERR_SERVER, trans('errors.ServiceModelDoesError', ['key' => $key]));
        }
    }

    /**
     * getModelInstance
     * 获取数据模型类实例
     * @param $key
     * @return mixed
     */
    public function getModelInstance($key)
    {
        $key = ucfirst($key);
        $fileName = BASE_PATH . "/app/Model/{$key}.php";
        $className = "App\\Model\\{$key}";
        if (file_exists($fileName)) {
            return make($className);
        } else {
            throw new BusinessException(ErrorCode::ERR_SERVER, trans('errors.ServiceModelNotExist', ['key' => $key]));
        }
    }
}