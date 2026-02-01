<?php

namespace App\Http\Requests\Admin\Task;

use App\Http\Requests\Request;

class TaskShareRequest extends Request
{
    public function authorize(): bool
    {
        return true;
//        return $this->user()->can('share', $this->route('task'));
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
                $optional = [
                    'access_level_id' => 'nullable|exists:code_values,id',
                    'user_id' => 'nullable|required_without:department_id|exists:users,id',
                    'department_id' => 'nullable|required_without:user_id|exists:departments,id',
                    'remarks' => 'nullable|string|max:255',
                    'transfer' => 'nullable|boolean'
                ];
                break;
        }
        return array_merge($basic, $optional);
    }

    public function sanitize()
    {
        $input = $this->all();
        $this->replace($input);
        return $this->all();
    }
}
