<?php
namespace App\Http\Requests\Admin\Task;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TaskPerformanceRequest extends Request
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
                    'user_id' => 'required|exists:users,id',
                    'timeliness_score' => 'required|integer|min:1|max:10',
                    'quality_score' => 'required|integer|min:1|max:10',
                    'budget_score' => 'required|integer|min:1|max:10',
                    'kpi_score' => 'required|integer|min:1|max:10',
                ];
                $optional = [
                    'remarks' => 'nullable|string',
                ];
                break;
            case 2:
                /*When Editing*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'user_id' => 'required|exists:users,id',
                    'timeliness_score' => 'required|integer|min:1|max:10',
                    'quality_score' => 'required|integer|min:1|max:10',
                    'budget_score' => 'required|integer|min:1|max:10',
                    'kpi_score' => 'required|integer|min:1|max:10',
                ];
                $optional = [
                    'remarks' => 'nullable|string',
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

