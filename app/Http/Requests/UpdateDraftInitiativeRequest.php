<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDraftInitiativeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'objective_id' => 'required|exists:objectives,id',
            'directorate_id' => 'required|exists:directorates,id',
            'implementation_status_id' => 'nullable|exists:implementation_statuses,id',
            'note' => 'nullable|string',
        ];
    }
}
