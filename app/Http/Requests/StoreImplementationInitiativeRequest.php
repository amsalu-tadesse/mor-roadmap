<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImplementationInitiativeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Required base fields
            'name' => 'required|string|max:255',
            'objective_id' => 'required|exists:objectives,id',
            'directorates' => 'required|array|min:1',
            'directorates.*' => 'exists:directorates,id',
            'implementation_status_id' => 'nullable|exists:implementation_statuses,id',
            'note' => 'nullable|string',
        ];
    }
}
