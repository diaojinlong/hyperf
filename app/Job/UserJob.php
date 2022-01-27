<?php

declare(strict_types=1);

namespace App\Job;

use Hyperf\AsyncQueue\Job;

class UserJob extends Job
{
    public $user_id;

    /**
     * 任务执行失败后的重试次数，即最大执行次数为 $maxAttempts+1 次
     *
     * @var int
     */
    protected $maxAttempts = 2;

    public function __construct($userId)
    {
        // 这里最好是普通数据，不要使用携带 IO 的对象，比如 PDO 对象
        $this->user_id = $userId;
    }

    public function handle()
    {
        // 根据参数处理具体逻辑
        // 通过具体参数获取模型等
        // 这里的逻辑会在 ConsumerProcess 进程中执行
        echo '新注册的用户【' . $this->user_id . '】请自己完善队列内容！' . PHP_EOL;
    }
}