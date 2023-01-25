<?php

namespace Larazoul\Larazoul\Larazoul\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class ItemsRequest extends FormRequest
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
            'name_ar' => 'required|min:1|max:191',
            'name_en' => 'required|min:1|max:191',
            'menu_id' => 'required'
        ];
    }
}
