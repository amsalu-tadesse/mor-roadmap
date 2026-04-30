<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShelfInitiativeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Required base fields
            'name' => 'required|string|max:255',
            'objective_id' => 'required|exists:objectives,id',
            'directorate_id' => 'required|exists:directorates,id',
            'implementation_status_id' => 'nullable|exists:implementation_statuses,id',
            'note' => 'nullable|string',

            // Implementation fields (in case they are filled during creation)
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|string|max:255',
            'expenditure' => 'nullable|string',
            'partner_id' => 'nullable|exists:partners,id',
            'completion' => 'nullable|numeric|min:0|max:100',
            'initiative_status_id' => 'nullable|exists:initiative_statuses,id',
            'request' => 'nullable|in:New,Current',
        ];
    }
}
