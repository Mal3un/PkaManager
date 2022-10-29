<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClasseRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:classes',
            'class_type' => 'required|int',
            'major_id' => 'required|int',
            'course_id' => 'required|int',
//            'teacher_id' => 'required|int',
            'subject_id' => 'required|int',
        ];
    }
}
