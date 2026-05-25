<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'initiative_id' => 'nullable|exists:initiatives,id',
            'partner_id' => 'nullable|exists:partners,id',
            'activities' => 'required|string',
            'request_status_id' => 'nullable|exists:request_statuses,id',
            'priority' => 'required|in:L,M,H',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|string',
            'expenditure' => 'nullable|string',
            'completion' => 'nullable|numeric|min:0|max:100',
            'activity_status_id' => 'nullable|exists:activity_statuses,id',
            'request_type' => 'nullable|in:New,Current',
            'interested_partners' => 'nullable|array',
            'interested_partners.*' => 'exists:partners,id',
            'directorates' => 'nullable|array',
            'directorates.*' => 'exists:directorates,id',
        ];
    }
}
