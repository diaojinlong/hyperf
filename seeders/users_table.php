<?php

declare(strict_types=1);

use App\Model\UserModel;
use Hyperf\Database\Seeders\Seeder;

class UsersTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salt = getRandomStr(4);
        $model = new UserModel();
        $model->tel = '13800138000';
        $model->pwd = (new UserModel())->createPwd('123456', $salt);
        $model->salt = $salt;
        $model->save();
    }
}
