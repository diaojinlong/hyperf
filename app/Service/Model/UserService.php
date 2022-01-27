<?php

namespace App\Service\Model;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Cache\Annotation\Cacheable;

class UserService extends BaseService
{


    /**
     * userAdd
     * 新增用户
     * @param $tel
     * @param $pwd
     * @return mixed
     */
    public function userAdd($tel, $pwd)
    {
        $userId = $this->userModel->telExist($tel);
        if ($userId) {
            throw new BusinessException(trans('user.TelRegistered'), ErrorCode::ERR_EXCEPTION);
        }
        return $this->userModel->add($tel, $pwd);
    }

    /**
     * telExist
     * 验证手机号是否存在，返回用户id
     * @param $tel
     * @return integer
     */
    public function telExist($tel)
    {
        return $this->userModel->telExist($tel);
    }


    /**
     * getInfoById
     * 通过用户ID查询用户信息
     * @Cacheable(prefix="user", ttl=9000)
     */
    public function getInfoById($id)
    {
        $data = $this->userModel->getInfoById($id);
        return $data ?: null;
    }

    /**
     * validationPwd
     * 验证登录并返回用户信息
     * @param $tel
     * @param $pwd
     * @return |null
     */
    public function validationPwd($tel, $pwd)
    {
        $user = $this->userModel->getInfoByTel($tel);
        if (empty($user)) {
            throw new BusinessException(ErrorCode::ERR_USER_ABSENT);
        }
        if ($user['pwd'] !== $this->userModel->createPwd($pwd, $user['salt'])) {
            throw new BusinessException(ErrorCode::ERR_EXCEPTION_USER);
        }
        return $this->getInfoById($user['id']);
    }
}