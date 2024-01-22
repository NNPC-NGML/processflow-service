<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateWorkflowHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'task_id' => 'required|integer',
            'step_id' => 'required|integer',
            'process_flow_id' => 'required|integer',
            'status' => 'required|string',
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'The user ID is required.',
            'task_id.required' => 'The task ID is required.',
            
        ];
    }}
