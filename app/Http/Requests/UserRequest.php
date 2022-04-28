<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check() && backpack_user()->is_admin == 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->get('id') ?? request()->route('id');

        return [
            'email'    => 'required|email:filter|unique:users,email' . ($id ? ','.$id : ''),
            'name'     => 'required|min:3',
            'password' => $id ? 'nullable|min:4' : 'required|min:4',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'company_id' => 'Şirket',
            'name' => 'Adı Soyadı',
            'email' => 'E-posta',
            'password' => 'Şifre',
            'is_admin' => 'Yönetici',
            'active' => 'Aktif'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
