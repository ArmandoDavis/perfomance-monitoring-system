<?php
namespace App\Http\Requests\Admin\Task;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TaskRequest extends Request
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
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'department_id' => 'required|exists:departments,id',
                    'status_cv_id' => 'required|exists:code_values,id',
                ];
                $optional = [
                    'allocated_budget' => 'nullable',
                    'spent_amount' => 'nullable',
                    'remaining_budget' => 'nullable',
                    'start_date' => 'nullable',
                    'end_date' => 'nullable',
                    'is_active' => 'nullable|boolean'
                ];
                break;
            case 2:
                /*When Editing*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'department_id' => 'required|exists:departments,id',
                    'status_cv_id' => 'required|exists:code_values,id',
                ];
                $optional = [
                    'allocated_budget' => 'nullable',
                    'spent_amount' => 'nullable',
                    'remaining_budget' => 'nullable',
                    'start_date' => 'nullable',
                    'end_date' => 'nullable',
                    'is_active' => 'nullable|boolean'
                ];
                break;
            case 3:
                /*when change status*/
                $basic = [
                    'action' => 'required',
                ];
                break;
            case 4:
                /*when change status*/
                $basic = [
                    'mark_ad_complete' => 'required',
                ];
                break;
            case 5:
                /*when archive*/
                $basic = [
                    'archive' => 'required',
                ];
                break;
        }
        return array_merge($basic, $optional);
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['allocated_budget'] = isset($input['allocated_budget']) ? str_replace(",", "", $input['allocated_budget']) : null;
        $input['remaining_budget'] = isset($input['remaining_budget']) ? str_replace(",", "", $input['remaining_budget']) : null;
        $input['spent_amount'] = isset($input['spent_amount']) ? str_replace(",", "", $input['spent_amount']) : null;
        $input['start_date'] = isset($input['start_date']) ? standard_date_format($input['start_date']) : null;
        $input['end_date'] = isset($input['end_date']) ? standard_date_format($input['end_date']) : null;
        $this->replace($input);
        return $this->all();
    }
}

