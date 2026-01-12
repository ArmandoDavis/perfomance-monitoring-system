<?php
namespace App\Http\Requests\Admin\Department;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class DepartmentRequest extends Request
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
                    'name' => 'required|string|max:255|unique:departments',
                ];
                $optional = [
                    'abbreviation' => 'nullable|string|max:20',
                    'is_active' => 'nullable|boolean'
                ];
                break;
            case 2:
                /*When Editing*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'name' =>  ['required','max:150', Rule::unique('departments')->where(function ($query) use($resource_id) { $query->where('id','<>',$resource_id); })],
                ];
                $optional = [
                    'abbreviation' => 'nullable|string|max:20',
                    'is_active' => 'nullable|boolean'
                ];
                break;
            case 3:
                /*when change status*/
                $basic = [
                    'action' => 'required',
                ];
                break;
        }
        return array_merge($basic, $optional);
    }
}

