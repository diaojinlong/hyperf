<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;

class BaseController extends AbstractController
{

    /**
     * success
     * 成功返回请求结果
     * @param array $data
     * @param null $msg
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success($data = [], $msg = null)
    {
        return $this->response->success($data, $msg);
    }

    /**
     * error
     * 业务相关错误结果返回
     * @param int $code
     * @param null $msg
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function error($code = ErrorCode::ERR_EXCEPTION, $msg = null)
    {
        return $this->response->error($code, $msg);
    }
}
