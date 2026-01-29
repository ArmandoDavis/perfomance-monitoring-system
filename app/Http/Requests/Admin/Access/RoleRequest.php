<?php

namespace App\Http\Requests\Admin\Access;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends  FormRequest
{
    public  function authorize()
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

        switch ($action_type) {
            case 1:
                // When Adding
                $basic = [
                    'name' => [
                        'required',
                        Rule::unique('roles')->where(function ($query) {
                            return $query->whereNull('deleted_at');
                        }),
                    ],
                    'permissions' => 'array',
                    'permissions.*' => 'exists:permissions,id',
                ];
                break;
            case 2:
                // When Editing
                $resource_id = $input['resource_id'];
                $basic = [
                    'name' =>  ['required','max:30', Rule::unique('roles')->where(function ($query) use($resource_id) { $query->where('id','<>',$resource_id); })],
                    'permissions' => 'array',
                    'permissions.*' => 'exists:permissions,id',
                ];
                break;
        }
        return array_merge($basic, $optional);
    }
}
