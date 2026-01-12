<?php
namespace App\Http\Requests\Admin\Task;

use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CommentRequest extends Request
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
                    'content' => 'required|string',
                ];
                break;
            case 2:
                /*When Editing*/
                $resource_id = $input['resource_id'];
                $basic = [
                    'content' => 'required|string',
                ];
                break;
        }
        return array_merge($basic, $optional);
    }
}

