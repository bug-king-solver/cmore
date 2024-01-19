<?php

namespace App\Rules;

use App\Models\Tenant\Attachment;
use Illuminate\Contracts\Validation\Rule;

class AttachmentName implements Rule
{
    protected string $filename;

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
        $this->filename = $value->getClientOriginalName();

        return ! Attachment::fileExists($this->filename);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The file you are trying to upload already exists: ' . $this->filename;
    }
}
