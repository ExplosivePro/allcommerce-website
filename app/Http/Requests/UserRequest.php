<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
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
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|required|min:5|max:255',
            //'first_name' => 'bail|required|min:2|max:255',
            //'last_name' => 'bail|required|min:2|max:255',
            'email' => 'bail|required|email:rfc,dns',
            //'phone' => 'sometimes',
            'client_id' => 'exclude_unless:assigned_role,client|exists:clients,id|',
            'assigned_role' => 'bail|required|in:admin,client',
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
            'client_id' => 'Client users require a client assigned to them. Your input '
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
