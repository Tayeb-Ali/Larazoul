<?php

namespace Larazoul\Larazoul\Larazoul\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class StepFourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       return [
            'module_to_id' => 'required|integer',
            'column_id' => 'required|integer',
            'type' => 'required',
            'input_type' => 'required'
        ];
    }
}
