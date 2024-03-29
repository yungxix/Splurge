<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateOrTimeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (preg_match('/^\\d{4}\\-\\d{1,2}\\-\\d{1,2}/', $value)) {
            return true;
        }
        return preg_match('/^\\d{1,2}\\:\\d{1,2}/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must be a date or time';
    }
}
