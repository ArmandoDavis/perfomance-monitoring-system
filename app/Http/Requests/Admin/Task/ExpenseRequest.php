<?php
namespace App\Http\Requests\Admin\Task;

use App\Http\Requests\Request;

class ExpenseRequest extends Request
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
                    'amount' => 'required',
                ];
                $optional = [
                    'description' => 'nullable|string',
                    'receipt' => 'nullable|file|max:5120'
                ];
                break;
            case 2:
                /*When Editing*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'amount' => 'required',
                ];
                $optional = [
                    'description' => 'nullable|string',
                    'receipt' => 'nullable|file|max:5120'
                ];
                break;
            case 3:
                /*record from expense module*/
                $basic = [
                    'amount' => 'required',
                    'task_id' => 'required|exists:tasks,id',
                ];
                $optional = [
                    'description' => 'nullable|string',
                    'receipt' => 'nullable|file|max:5120'
                ];
                break;
            case 4:
                /*record from expense module*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'amount' => 'required',
                    'task_id' => 'required|exists:tasks,id',
                ];
                $optional = [
                    'description' => 'nullable|string',
                    'receipt' => 'nullable|file|max:5120'
                ];
                break;
        }
        return array_merge($basic, $optional);
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['amount'] = isset($input['amount']) ? str_replace(",", "", $input['amount']) : null;
        $this->replace($input);
        return $this->all();
    }
}

