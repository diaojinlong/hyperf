<?php

declare(strict_types=1);

namespace App\Request\Home;

use Hyperf\Validation\Request\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'tel' => 'required|tel',
            'pwd' => 'required',
            'code' => 'required',
        ];
    }

    /**
     * 获取验证错误的自定义属性
     */
    public function attributes(): array
    {
        return [
            'tel' => trans('user.tel'),
            'pwd' => trans('user.pwd'),
            'code' => trans('user.code'),
        ];
    }
}
