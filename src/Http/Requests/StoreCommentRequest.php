<?php

namespace SagorRoy\Comments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $maxLength = config('comments.max_length', 1000);
        $guestNameRequired = config('comments.guest_name_required', true);
        $guestNameMax = config('comments.guest_name_max_length', 100);

        $rules = [
            'commentable_type' => ['required', 'string'],
            'commentable_id' => ['required', 'integer'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
            'content' => ['required', 'string', 'max:'.$maxLength],
        ];

        if (!auth()->check()) {
            if ($guestNameRequired) {
                $rules['guest_name'] = ['required', 'string', 'max:'.$guestNameMax];
            } else {
                $rules['guest_name'] = ['nullable', 'string', 'max:'.$guestNameMax];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'content.required' => trans('comments::comments.validation.content_required'),
            'content.max' => trans('comments::comments.validation.content_max'),
            'guest_name.required' => trans('comments::comments.validation.guest_name_required'),
            'guest_name.max' => trans('comments::comments.validation.guest_name_max'),
        ];
    }
}
