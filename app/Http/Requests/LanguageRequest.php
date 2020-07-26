<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
            'name'=>'required|string|max:100',
            'abbr'=>'required|string|max:10',
            'direction'=>'required|in:rtl,ltr',
            //'active'=>'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'required'=>'هده الحقل مطلوب',
            'in'=>'القيم المدخلة غير صحيحة',
            'name.string'=>'اسم اللغة لابد ان يكون أحرف',
            'abbr.max'=>'هده الحقل لابد الا يزيد عن 10 احرف',
            'abbr.string'=>'هده الحقل لابد ان يكون احرف',
            'name.max'=>'اسم اللغة لابد ان يزيد عن 100 حرف',

        ];

    }
}
