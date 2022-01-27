<?php

namespace App\Service;

use App\Constants\ErrorCode;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Annotation\Inject;

class ResponseService
{
    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * success
     * 成功返回请求结果
     * @param array $data
     * @param string|null $msg
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success($data = [], string $msg = null)
    {
        $msg = $msg ?? ErrorCode::getMessage(ErrorCode::SUCCESS);
        $data = [
            'code' => ErrorCode::SUCCESS,
            'msg' => $msg,
            'data' => $data
        ];
        return $this->response->json($data);
    }

    /**
     * error
     * 业务相关错误结果返回
     * @param $code
     * @param $msg
     * @param $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function error(int $code = ErrorCode::ERR_EXCEPTION, string $msg = null, $data = [])
    {
        $msg = $msg ?? ErrorCode::getMessage($code);;
        $data = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];

        return $this->response->json($data);
    }

    /**
     * json
     * 直接返回数据
     * @param $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function json(array $data)
    {
        return $this->response->json($data);
    }

    /**
     * xml
     * 返回xml数据
     * @param $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function xml(array $data)
    {
        return $this->response->xml($data);
    }

    /**
     * redirect
     * 重定向
     * @param string $url
     * @param string $schema
     * @param int $status
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect(string $url,string $schema = 'http', int $status = 302 )
    {
        return $this->response->redirect($url,$status,$schema);
    }

    /**
     * download
     * 下载文件
     * @param string $file
     * @param string $name
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function download(string $file, string $name = '')
    {
        return $this->response->redirect($file,$name);
    }
}