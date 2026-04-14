<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:100',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date|after_or_equal:today',
        ];
    }
}
