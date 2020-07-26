<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'logo'=>'required_without:id|mimes:jpg,jpeg,png',
            'name'=>'required|string|max:150',
            'mobile'=>'required|unique:vendors,mobile|max:100',
            'email'=>'required|unique:vendors,email|email',
            'category_id'=>'required|exists:main_categories,id',
            'address'=>'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'required'=>'هده الحقل مطلوب',
            'max'=>'هده الحقل طويل',
            'category_id.exists'=>'هده القسم غير موجود',
            'email.email'=>'صيغة البريد الالكتروني غير صحيح',
            'address.string'=>'العنوان لابد ان يكون حروف او ارقام  ',
            'name.string'=>'الاسم لابد ان يكون حروف او ارقام ',
            'logo.required_without'=>'الصورة مطلوبة ',

        ];


    }
}
