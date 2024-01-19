<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileExtension implements Rule
{
    /**
     * Extensions with all mimes
     * 
     * https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
     */
    protected array $extensionsMimes = [
        'pdf' => [
            'application/pdf',
        ],
        'csv' => [
            'text/csv',
        ],
        'txt' => [
            'text/plain',
        ],
        'xls' => [
            'application/vnd.ms-excel',
        ],
        'xlsx' => [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ],
        'jpg' => [
            'image/jpeg',
        ],
        'jpeg' => [
            'image/jpeg',
        ],
        'png' => [
            'image/png',
        ],
        'docx' => [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
    ];

    protected array $extensions;

    protected string $fileOriginalExtension;

    protected string $fileExtension;
    
    protected string $fileMime;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->extensions = array_keys($this->extensionsMimes);
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
        $this->fileOriginalExtension = $value->getClientOriginalExtension();

        $fileName = explode('.', $value->getClientOriginalName());
        $this->fileExtension = $fileName[array_key_last($fileName)] ?? '';

        $this->fileMime = $value->getMimeType();

        // is not valid extension
        if ($this->fileOriginalExtension != $this->fileExtension
            || ! in_array($this->fileOriginalExtension, $this->extensions)) {
            return false;
        }

        // if the mime doesn't belongs to extension
        if (!in_array($this->fileMime, $this->extensionsMimes[$this->fileOriginalExtension])) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The file you are trying needs to have one of the following extensions: ' . implode(', ', $this->extensions);
    }
}
