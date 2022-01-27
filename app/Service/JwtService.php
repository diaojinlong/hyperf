<?php

namespace App\Service;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected static $logger;

    /**
     * key
     * 获取JWT的key
     * @return mixed
     * @throws \Exception
     */
    protected static function key()
    {
        $key = config('jwt.key');
        if (empty($key))
            throw new BusinessException(ErrorCode::ERR_SERVER, trans('errors.JwtKeyException'));
        return $key;
    }

    /**
     * encode
     * 生成jwt
     * @param array $payload
     * @return string
     * @throws \Exception
     */
    public static function encode($payload = [])
    {
        $time = time();
        $key = self::key();
        $payload = array_merge(array(
            "iss" => config('jwt.iss'),
            "aud" => config('jwt.aud'),
            "exp" => $time + intval(config('jwt.exp_seconds')),
            "iat" => $time,
            "nbf" => $time
        ), $payload);
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }

    /**
     * decode
     * 解开jwt
     * @param $jwt
     * @return array
     * @throws \Exception
     */
    public static function decode($jwt)
    {
        $key = self::key();
        if (empty($jwt)) {
            throw new BusinessException(ErrorCode::ERR_NOT_EXIST_TOKEN);
        }
        try {
            JWT::$leeway = config('jwt.leeway_seconds');
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            switch ($message) {
                case 'Expired token':
                    throw new BusinessException(ErrorCode::ERR_EXPIRE_TOKEN);
                case 'Signature verification failed':
                    throw new BusinessException(ErrorCode::ERR_INVALID_TOKEN);
                default:
                    self::$logger->error('JWT错误：' . $message);
                    throw new BusinessException(ErrorCode::ERR_EXCEPTION);
            }
        }
        return (array)$decoded;
    }
}