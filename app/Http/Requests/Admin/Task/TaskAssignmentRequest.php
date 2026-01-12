<?php
namespace App\Http\Requests\Admin\Task;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TaskAssignmentRequest extends Request
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
                    'user_ids'   => ['required', 'array'],
                    'user_ids.*' => ['exists:users,id'],
                    'assigned_budget' => ['required', 'numeric', 'min:1'],
                ];
                $optional = [
                    'is_active' => 'nullable|boolean'
                ];
                break;
            case 2:
                /*When Editing*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'assigned_budget' => ['required', 'numeric', 'min:1'],
                ];
                $optional = [
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

    public function sanitize()
    {
        $input = $this->all();
        $input['assigned_budget'] = isset($input['assigned_budget']) ? str_replace(",", "", $input['assigned_budget']) : null;
        $this->replace($input);
        return $this->all();
    }
}

