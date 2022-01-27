<?php

declare(strict_types=1);

namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * 成功
     * @Message("errors.OK")
     */
    const SUCCESS = 200;

    /**
     * 内部服务器错误
     * @Message("errors.InternalServerError")
     */
    const ERR_SERVER = 500;


    /**
     * 无权限访问
     * @Message("errors.UnauthorizedAccess")
     */
    const ERR_NOT_ACCESS = 1001;

    /**
     * 令牌过期
     * @Message("errors.TokenTimeout")
     */
    const ERR_EXPIRE_TOKEN = 1002;

    /**
     * 令牌无效
     * @Message("errors.TokenInvalid")
     */
    const ERR_INVALID_TOKEN = 1003;

    /**
     * 令牌不存在
     * @Message("errors.TokenNotExist")
     */
    const ERR_NOT_EXIST_TOKEN = 1004;


    /**
     * 请登录
     * @Message("errors.PleaseLogIn")
     */
    const ERR_NOT_LOGIN = 2001;

    /**
     * 用户信息错误
     * @Message("errors.UserInformationError")
     */
    const ERR_USER_INFO = 2002;

    /**
     * 用户不存在
     * @Message("errors.TheUserDoesNotExist")
     */
    const ERR_USER_ABSENT = 2003;


    /**
     * 业务逻辑异常
     * @Message("errors.BusinessLogicException")
     */
    const ERR_EXCEPTION = 3001;

    /**
     * 用户密码不正确
     * @Message("errors.IncorrectUserPassword")
     */
    const ERR_EXCEPTION_USER = 3002;

    /**
     * 文件上传异常
     * @Message("errors.FileUploadException")
     */
    const ERR_EXCEPTION_UPLOAD = 3003;

    /**
     * 请求参数异常
     * @Message("errors.ParameterException")
     */
    const ERR_EXCEPTION_PARAMETER = 3004;

}