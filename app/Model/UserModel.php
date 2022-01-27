<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $tel
 * @property string $pwd
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * getInfoByTel
     * 通过tel获取用户信息
     * @param $tel
     * @return array
     */
    public function getInfoByTel($tel)
    {
        $query = $this->query();
        $query->where('tel', $tel);
        $query = $query->first();
        return $query ? $query->toArray() : [];
    }

    /**
     * getInfoById
     * 通过id获取用户信息
     * @param $id
     * @return array
     */
    public function getInfoById($id)
    {
        $query = $this->query()->select('id', 'tel');
        $query->where('id', $id);
        $query = $query->first();
        return $query ? $query->toArray() : [];
    }

    /**
     * createPwd
     * 生成密码
     * @param $pwd
     * @param $salt
     * @return string
     */
    public function createPwd($pwd, $salt)
    {
        return md5(md5($pwd) . $salt);
    }

    /**
     * telExist
     * 通过手机号查询用户返回id,0=不存在
     * @param $tel
     * @return int
     */
    public function telExist($tel)
    {
        $userId = $this->query()->where('tel', $tel)->value('id');
        return $userId ?: 0;
    }

    /**
     * add
     * 新增用户返回用户ID
     * @param $tel
     * @param $pwd
     * @return int
     */
    public function add($tel, $pwd)
    {
        $salt = getRandomStr(4);
        $model = new self();
        $model->tel = $tel;
        $model->pwd = $this->createPwd($pwd, $salt);
        $model->salt = $salt;
        $model->save();
        return $model->id;
    }
}