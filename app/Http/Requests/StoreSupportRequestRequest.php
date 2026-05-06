<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'initiative_id' => 'nullable|exists:initiatives,id',
            'partner_id' => 'required|exists:partners,id',
            'activities' => 'required|string',
            'request_status_id' => 'required|exists:request_statuses,id',
            'priority' => 'required|in:L,M,H',
        ];
    }
}
