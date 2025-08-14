<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create assignments') || auth()->user()->can('create syllabi') || auth()->user()->can('create notes');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'note_name'         => 'sometimes|string|max:255',
            'file'              => 'required|file|mimes:jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,zip',
            'class_id'          => 'sometimes|integer|exists:school_classes,id',
            'section_id'        => 'sometimes|integer|exists:sections,id',
            'course_id'         => 'sometimes|integer|exists:courses,id',
            'semester_id'       => 'sometimes|integer|exists:semesters,id'
        ];
    }
}
