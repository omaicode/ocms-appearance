<?php

namespace Modules\Appearance\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ordered_list' => 'required|array',
            'ordered_list.*' => 'required|numeric',
            'parent_id' => 'nullable|exists:appearance_menus,id'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
