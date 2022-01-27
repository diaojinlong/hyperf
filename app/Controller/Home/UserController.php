<?php

declare(strict_types=1);

namespace App\Controller\Home;

use App\Constants\ErrorCode;
use App\Controller\BaseController;
use App\Request\Home\UserLoginRequest;
use App\Request\Home\UserRegisterRequest;
use App\Service\Job\UserQueueService;
use App\Service\JwtService;
use App\Service\Model\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Request;

class UserController extends BaseController
{

    /**
     * @Inject
     * @var UserService
     */
    protected $userService;

    /**
     * @Inject
     * @var UserQueueService
     */
    protected $userQueueService;


    /**
     * showdoc
     * @catalog 用户端/用户相关
     * @title 注册
     * @description
     * @method POST
     * @url https://www.xxx.com/user/register
     * @header Content-Type 必选 string application/json
     * @header Accept 必选 string application/json
     * @json_param {"tel":"13800138000","pwd":"123456","code":"1111"}
     * @param tel 必选 string 手机号
     * @param pwd 必选 string 密码
     * @param code 必选 string 验证码
     * @return {"code":200,"msg":"成功","data":{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJleHAiOjE2NDMwMTYxMTEsImlhdCI6MTY0MzAwODkxMSwibmJmIjoxNjQzMDA4OTExLCJ1c2VyX2lkIjoxLCJ0b2tlbl90eXBlIjoiYWNjZXNzIn0.IhlfIvuHzRSDtxPUdiL3OLnDfTPVz3Wj0Et7hxyJSLw","expires_in":"7200","refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJleHAiOjE2NDQzMDQ5MTEsImlhdCI6MTY0MzAwODkxMSwibmJmIjoxNjQzMDA4OTExLCJ1c2VyX2lkIjoxLCJ0b2tlbl90eXBlIjoicmVmcmVzaCJ9.p5dXre4egjnA7yvlYORyJUxURMakr9F3-Pu7VefR888"}}
     * @return_param code int 200成功
     * @return_param msg string 错误信息
     * @return_param data object 接口相关数据
     * @return_param data->access_token string 身份令牌
     * @return_param data->expires_in string 身份令牌有效时长
     * @return_param data->refresh_token string 刷新身份令牌的token
     * @remark
     * @number 1
     * @throws
     */
    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();
        $userId = $this->userService->userAdd($data['tel'], $data['pwd']);
        $data = $this->getToken($userId);
        $this->userQueueService->push($userId);
        return $this->success($data);
    }

    /**
     * showdoc
     * @catalog 用户端/用户相关
     * @title 登录
     * @description
     * @method POST
     * @url https://www.xxx.com/user/login
     * @header Authorization 必选 string 身份令牌
     * @header Content-Type 必选 string application/json
     * @header Accept 必选 string application/json
     * @json_param {"tel":"13800138000","pwd":"123456"}
     * @param tel 必选 string 手机号
     * @param pwd 必选 string 密码
     * @return {"code":200,"msg":"成功","data":{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJleHAiOjE2NDMwMTYxMTEsImlhdCI6MTY0MzAwODkxMSwibmJmIjoxNjQzMDA4OTExLCJ1c2VyX2lkIjoxLCJ0b2tlbl90eXBlIjoiYWNjZXNzIn0.IhlfIvuHzRSDtxPUdiL3OLnDfTPVz3Wj0Et7hxyJSLw","expires_in":"7200","refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJleHAiOjE2NDQzMDQ5MTEsImlhdCI6MTY0MzAwODkxMSwibmJmIjoxNjQzMDA4OTExLCJ1c2VyX2lkIjoxLCJ0b2tlbl90eXBlIjoicmVmcmVzaCJ9.p5dXre4egjnA7yvlYORyJUxURMakr9F3-Pu7VefR888"}}
     * @return_param code int 200成功
     * @return_param msg string 错误信息
     * @return_param data object 接口相关数据
     * @return_param data->access_token string 身份令牌
     * @return_param data->expires_in string 身份令牌有效时长
     * @return_param data->refresh_token string 刷新身份令牌的token
     * @remark
     * @number 10
     * @throws
     */
    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->validationPwd($data['tel'], $data['pwd']);
        $data = $this->getToken($user['id']);
        return $this->success($data);
    }

    /**
     * showdoc
     * @catalog 用户端/用户相关
     * @title 刷新ACCESS_TOKEN
     * @description
     * @method POST
     * @url https://www.xxx.com/user/refresh_token
     * @header Authorization 必选 string 身份令牌
     * @header Content-Type 必选 string application/json
     * @header Accept 必选 string application/json
     * @json_param {"refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJleHAiOjE2NDQzMDQ5MTEsImlhdCI6MTY0MzAwODkxMSwibmJmIjoxNjQzMDA4OTExLCJ1c2VyX2lkIjoxLCJ0b2tlbl90eXBlIjoicmVmcmVzaCJ9.p5dXre4egjnA7yvlYORyJUxURMakr9F3-Pu7VefR888"}
     * @param refresh_token 必选 string REFRESH_TOKEN
     * @return {"code":200,"msg":"成功","data":{"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJleHAiOjE2NDMwMTYxMTEsImlhdCI6MTY0MzAwODkxMSwibmJmIjoxNjQzMDA4OTExLCJ1c2VyX2lkIjoxLCJ0b2tlbl90eXBlIjoiYWNjZXNzIn0.IhlfIvuHzRSDtxPUdiL3OLnDfTPVz3Wj0Et7hxyJSLw","expires_in":"7200","refresh_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIiLCJhdWQiOiIiLCJleHAiOjE2NDQzMDQ5MTEsImlhdCI6MTY0MzAwODkxMSwibmJmIjoxNjQzMDA4OTExLCJ1c2VyX2lkIjoxLCJ0b2tlbl90eXBlIjoicmVmcmVzaCJ9.p5dXre4egjnA7yvlYORyJUxURMakr9F3-Pu7VefR888"}}
     * @return_param code int 200成功
     * @return_param msg string 错误信息
     * @return_param data object 接口相关数据
     * @return_param data->access_token string 身份令牌
     * @return_param data->expires_in string 身份令牌有效时长
     * @return_param data->refresh_token string 刷新身份令牌的token
     * @remark
     * @number 10
     * @throws
     */
    public function refreshToken(Request $request)
    {
        $refreshToken = $request->input('refresh_token');
        if (empty($refreshToken)) {
            return $this->error(ErrorCode::ERR_EXCEPTION_PARAMETER);
        }
        $data = JwtService::decode($refreshToken);
        if (!isset($data['user_id']) || $data['token_type'] != 'refresh') {
            return $this->error(ErrorCode::ERR_INVALID_TOKEN);
        }
        $data = $this->getToken($data['user_id']);
        return $this->success($data);
    }

    /**
     * showdoc
     * @catalog 用户端/用户相关
     * @title 用户信息
     * @description
     * @method GET
     * @url https://www.xxx.com/user/info
     * @header Authorization 必选 string 身份令牌
     * @header Content-Type 必选 string application/json
     * @header Accept 必选 string application/json
     * @return {"code":200,"msg":"ok","data":[{"id":1,"gift_guid":"86afe6b5-c658-4bff-bbdb-624415d966c5","gift_name":"保温水杯","gift_pic":"https://img.alicdn.com/tfs/TB1WMyJU.H1gK0jSZSyXXXtlpXa-1160-700.png","start_time":"2021-01-04 00:00:00","end_time":"2021-01-10 00:00:00","status":0},{"id":2,"gift_guid":"86afe6b5-c658-4bff-bbdb-624415d966c5","gift_name":"笔记本电脑","gift_pic":"https://img.alicdn.com/tfs/TB1WMyJU.H1gK0jSZSyXXXtlpXa-1160-700.png","start_time":"2021-01-04 00:00:00","end_time":"2021-01-10 00:00:00","status":0}]}
     * @return_param code int 200成功
     * @return_param msg string 错误信息
     * @return_param data array 接口相关数据
     * @return_param data->id int 领取编号
     * @return_param data->gift_guid string 礼品编号
     * @return_param data->gift_name string 礼品名称
     * @return_param data->gift_pic string 礼品图片
     * @return_param data->start_time string 开始领取时间
     * @return_param data->end_time string 结束领取时间
     * @return_param data->status int 领取状态:0=未领取,1=已领取,2=已过期
     * @remark
     * @number 30
     * @throws
     */
    public function info(Request $request)
    {
        $user = $request->getAttribute('user');
        return $this->success($user);
    }


    /**
     * getToken
     * 获取token
     * @param $userId
     * @return array
     * @throws
     */
    protected function getToken($userId)
    {
        $accessToken = JwtService::encode(['user_id' => $userId, 'token_type' => 'access']);
        $refreshToken = JwtService::encode(['user_id' => $userId, 'token_type' => 'refresh', 'exp' => strtotime('+15 day')]);
        return [
            'access_token' => $accessToken,
            'expires_in' => intval(config('jwt.exp_seconds')),
            'refresh_token' => $refreshToken
        ];
    }
}
