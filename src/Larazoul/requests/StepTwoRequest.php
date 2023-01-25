<?php

namespace Larazoul\Larazoul\Larazoul\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StepTwoRequest extends FormRequest
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
            'name.*' => 'required|min:1|max:191',
            'type.*' => 'required|min:1|max:191',
            'function.*' => 'min:1|max:191|nullable',
            'multi_lang' => 'required'
        ];
    }
}
