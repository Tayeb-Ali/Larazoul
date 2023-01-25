<?php

namespace Larazoul\Larazoul\Larazoul\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class StepOneRequest extends FormRequest
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
        $id = Route::input('id');
        $array = [
            'admin' => 'required',
            'website' => 'required',
            'api' => 'required'
        ];
        if($id){
            return $array + ['name' => 'min:1|max:191|unique:modules,name,'.$id];
        }
        return $array + ['name' => 'min:1|max:191|unique:modules'];
    }
}
