<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UserRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $input = $this->all();
        $basic = [];
        $optional = [];
        $array = [];
        $action_type = $input['action_type'];

        switch ($action_type){
            case 1:
                /*When Adding*/
                $basic = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|string|max:20|unique:users,phone',

                ];
                $optional = [
                    'is_active' => 'nullable|boolean',
                    'agree_terms_and_condition' => 'nullable|boolean',
                ];
                $array = [
                    'roles'   => 'required|array',
                    'roles.*' => 'exists:roles,id',
                ];
                break;
            case 2:
                /*When Editing*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'name' => 'required|string|max:255',
                    'email' =>  ['required','max:250', Rule::unique('users')
                        ->where(function ($query) use($resource_id) { $query->where('id','<>',$resource_id); })],
                    'phone' =>  ['required','max:20', Rule::unique('users')
                        ->where(function ($query) use($resource_id) { $query->where('id','<>',$resource_id); })],
                ];
                $optional = [
                    'is_active' => 'nullable|boolean',
                    'user_type_id' => 'nullable|exists:code_values,id',
                ];
                $array = [
                    'roles'   => 'required|array',
                    'roles.*' => 'exists:roles,id',
                ];
                break;
            case 3:
                /*When Editing Client*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'name' => 'required|string|max:255',
                    'email' =>  ['required','max:250', Rule::unique('users')
                        ->where(function ($query) use($resource_id) { $query->where('id','<>',$resource_id); })],
                    'phone' =>  ['required','max:20', Rule::unique('users')
                        ->where(function ($query) use($resource_id) { $query->where('id','<>',$resource_id); })],
                ];
                $optional = [
                    'is_active' => 'nullable|boolean',
                    'is_super_admin' => 'nullable|boolean',
                ];
                break;
            case 4:
                /*When Adding client*/
                $basic = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|string|max:15|unique:users,phone',

                ];
                $optional = [
                    'is_active' => 'nullable|boolean',
                ];
                break;
            case 5:
                /*When update client password*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'password' => 'required|string|max:20|min:8',
                    'confirm_password' => 'required|string|same:password|max:20|min:8',
                ];
                break;
            case 6:
                /*When update client status*/
                $basic = [
                    'is_active' => 'boolean',
                    'action' => 'required|string|max:20',
                ];
                break;
        }
        return array_merge($basic, $optional, $array);
    }
}
