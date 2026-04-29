<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileNumber implements Rule
{
    public function passes($attribute, $value)
    {
        // Remove non-digit characters from the value
        $cleanedValue = preg_replace('/[^0-9]/', '', $value);

        // Check the length and prefix based on different cases
        if (preg_match('/^(09|07)\d{8}$/', $cleanedValue)) {
            return strlen($cleanedValue) === 10;
        } elseif (preg_match('/^251\d{9}$/', $cleanedValue)) {
            return strlen($cleanedValue) === 12;
        } elseif (preg_match('/^\+251\d{10}$/', $cleanedValue)) {
            return strlen($cleanedValue) === 13;
        }

        return false;
    }

    public function message()
    {
        return 'The :attribute must start with "09," "07," "251," or "+251" and have the correct total number of digits.';
    }
}

