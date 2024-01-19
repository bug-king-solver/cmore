<?php

namespace App\Models\Enums;

enum PasswordStrength: string
{
    case WEAK = 'weak';
    case MODERATE = 'moderate';
    case STRONG = 'strong';
    case VERY_STRONG = 'very_strong';

    public function regex(): string
    {
        return match ($this) {
            self::WEAK => '/^(?=.*[0-9a-zA-Z!@#$%^&*()_+{}|:;<>?~`-]).{0,}$/i',
            self::MODERATE => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!]).{8,}$/',
            self::STRONG => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=.*[^\da-zA-Z]).{12,}$/',
            self::VERY_STRONG => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=.*[^\da-zA-Z]).{16,}$/',
        };
    }

    public function error(): string
    {
        return match ($this) {
            self::WEAK => __('The password should contain at least one character or special character.'),
            self::MODERATE => __('The password should be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.'),
            self::STRONG => __('The password should be at least 12 characters long and contain at least one uppercase letter, one lowercase letter, one number, one special character, and one non-alphanumeric character.'),
            self::VERY_STRONG => __('The password should be at least 16 characters long and contain at least one uppercase letter, one lowercase letter, one number, one special character, and one non-alphanumeric character.'),
        };
    }
}