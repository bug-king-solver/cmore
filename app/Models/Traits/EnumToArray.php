<?php

namespace App\Models\Traits;

trait EnumToArray
{
    public static function casesArray($value = 'label', $key = 'value'): array
    {
        $data = [];
        foreach (self::cases() as $enum) {
            $data[in_array($key, ['value', 'name'], true) ? $enum->$key : $enum->$key()] =
                in_array($value, ['value', 'name'], true) ? $enum->$value : $enum->$value();
        }

        return $data;
    }

    /**
     * Get the keys of the enum
     */
    public static function keys(): array
    {
        return array_keys(self::casesArray());
    }

    /**
     * Get the values of the enum
     */
    public static function values(): array
    {
        return array_values(self::casesArray());
    }

    /**
     * Get the value of the enum from the name
     * @param string $name
     * @return string
     * @example Trait::fromName('target') // 'target'
     */
    public static function fromName(string $name): string
    {
        foreach (self::casesArray() as $key => $value) {
            if (strtolower(trim($name)) === strtolower(trim($key))) {
                return $value;
            }
        }

        return '';
    }

    /**
     * Get the name of the enum from the value
     * @param string $value
     * @return string
     * @example Trait::fromValue('target') // 'target'
     */
    public static function fromValue(string $value): string
    {
        foreach (self::casesArray() as $key => $val) {
            if (strtolower(trim($value)) === strtolower(trim($val))) {
                return $key;
            }
        }

        return '';
    }
}
