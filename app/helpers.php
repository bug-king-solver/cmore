<?php

use App\Models\Enums\CompanyCategories;
use App\Models\Enums\Risk;
use App\Models\Enums\Territory\County;
use App\Models\Enums\Territory\District;
use App\Models\Enums\Territory\EuCountry;
use App\Models\Enums\User\UserType;
use App\Models\Tenant\BusinessSector;
use App\Models\Tenant\Permission;
use App\Models\Tenant\Question;
use App\Models\Tenant\Role;
use App\Models\Tenant\Sdg;
use App\Models\Tenant\Tag;
use Carbon\Carbon;
use Cartalyst\Converter\Laravel\Facades\Converter;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Currencies;

if (!function_exists('tenantPrivateAsset')) {
    /**
     * Generate an asset path for the application.
     * @description This function is used to generate the path of the assets.
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function tenantPrivateAsset($path, $disk = 'public')
    {
        return route('tenant.asset', ['disk' => $disk, 'path' => $path]);
    }
}

if (!function_exists('privateAssetDownload')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function privateAssetDownload($path, $disk = 'public')
    {
        return route('tenant.asset.download', ['disk' => $disk, 'path' => $path]);
    }
}


if (!function_exists('centralPrivateAsset')) {
    /**
     * Generate an asset path for the application.
     * @description This function is used to generate the path of the assets.
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function centralPrivateAsset($path, $disk = 'public')
    {
        return route('central.asset', ['disk' => $disk, 'path' => $path]);
    }
}


if (!function_exists('centralPrivateAssetDownload')) {
    /**
     * Generate an asset path for the application.
     * @description This function is used to generate the path of the assets.
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function centralPrivateAssetDownload($path, $disk = 'public')
    {
        return route('central.asset.download', ['disk' => $disk, 'path' => $path]);
    }
}

if (!function_exists('downloadMedia')) {
    /**
     * Generate an asset path for the media library tenancy.
     *
     * @param  string  $path
     * @return string
     */
    function downloadMedia($mediaItem)
    {
        return response()->download($mediaItem->getPath(), $mediaItem->file_name);
    }
}

if (!function_exists('getUsernameLabel')) {
    /**
     * Get the username label.
     */
    function getUsernameLabel()
    {
        return tenant()->features['authentication']['username']['label'] ?? 'E-mail';
    }
}

if (!function_exists('setUserLocale')) {
    function setUserLocale($locale = null, $redirect = true)
    {
        $supportedLocales = config('app.locales');

        if (!in_array($locale, $supportedLocales, false)) {
            $locale = $supportedLocales[0];
        }

        if (auth('web')->check()) {
            /** @var \App\Models\User $user * */
            $user = auth()->user();
            $user->locale = $locale;
            $user->save();
        }

        Session::put('locale', $locale);

        if ($redirect) {
            return redirect()->back();
        }
    }
}

if (!function_exists('getCurrenciesForSelect')) {
    function getCurrenciesForSelect()
    {
        $allCurrencies = Currencies::getCurrencyCodes();

        foreach ($allCurrencies as $key => $code) {
            $symbol = Currencies::getSymbol($code, auth()->user()->locale ?? app()->getLocale());

            $countries[$key] = [
                'id' => $code,
                'title' => "{$code} ({$symbol})",
            ];
        }

        return $countries;
    }
}

if (!function_exists('getCountriesForSelect')) {
    function getCountriesForSelect()
    {
        $allCountries = Countries::getAlpha3Names(auth()->user()->locale);

        foreach ($allCountries as $cca3 => $name) {
            $cca2 = Countries::getAlpha2Code($cca3);

            $countries[$cca3] = [
                'id' => $cca3,
                'title' => $name,
            ];
        }

        return $countries;
    }
}

if (!function_exists('getCountriesForSelectWhereIn')) {
    function getCountriesForSelectWhereIn($filter)
    {
        $countries = [];

        if ($filter) {
            $allCountries = Countries::getAlpha3Names(auth()->user()->locale);

            foreach ($allCountries as $cca3 => $name) {
                if (!in_array($cca3, $filter, false)) {
                    continue;
                }

                $cca2 = Countries::getAlpha2Code($cca3);

                $countries[$cca3] = [
                    'id' => $cca3,
                    'title' => $name,
                    'img' => strlen($cca2) === 2
                        ? view('vendor/flag-icons/flags/4x3/' . strtolower($cca2))->render()
                        : '',
                ];
            }
        }

        return $countries;
    }
}

if (!function_exists('getCountriesWhereIn')) {
    function getCountriesWhereIn($filter)
    {
        $countries = [];

        if ($filter) {
            $allCountries = Countries::getAlpha3Names(auth()->user()->locale);

            foreach ($allCountries as $cca3 => $name) {
                if (!in_array($cca3, $filter, false)) {
                    continue;
                }

                $countries[$cca3] = [
                    'cca2' => Countries::getAlpha2Code($cca3),
                    'cca3' => $cca3,
                    'name' => $name,
                ];
            }
        }

        return $countries;
    }
}

if (!function_exists('parseDataForSelect')) {
    /**
     * Function to make parseDataForSelect with ID and Title from array.
     * @param array $array
     * @return array
     */
    function parseDataForSelect($data, $id, $title = null, $avatar = null)
    {
        if (!$data) {
            return [];
        }

        if (is_null($title)) {
            $title = $id;
        }

        $parsedData = [];
        foreach ($data as $row) {
            $img = $row[$avatar] ?? null;

            $row = [
                'id' => $row[$id],
                'title' => $row[$title] ?? $row[$id],
            ];

            if ($img) {
                $row['img'] = "<img class='rounded-full' src='$img'>";
            }

            $parsedData[] = $row;
        }

        return $parsedData;
    }
}

if (!function_exists('parseKeyValueForSelect')) {
    /**
     * Function to make parseDataForSelect with ID and Title from array.
     * @param array $array
     * @return array
     */
    function parseKeyValueForSelect(array $array = []): array
    {
        $parsedData = [];
        foreach ($array as $key => $value) {
            $parsedData[] = [
                'id' => $key,
                'title' => $value,
            ];
        }

        return $parsedData;
    }
}

if (!function_exists('parseValueByKeyForSelect')) {
    /**
     * Function to make parseDataForSelect with ID and Title from array.
     * @param array $array
     * @return array
     */
    function parseValueByKeyForSelect(array $array = []): array
    {
        $parsedData = [];
        $locale = str_replace('-', '_', app()->getLocale());
        foreach ($array as $value => $key) {
            $parsedData[] = [
                'id' => $key,
                'title' => json_decode($value)->{$locale} ?? $value,
            ];
        }

        return $parsedData;
    }
}

if (!function_exists('parseKeyByValueToSimpleSelect')) {
    /**
     * Function to make parseDataForSelect with ID and Title from array.
     * @param array $array
     * @return array
     */
    function parseKeyByValueToSimpleSelect(array $array = []): array
    {
        $parsedData = [];
        $locale = str_replace('-', '_', app()->getLocale());
        foreach ($array as $value => $key) {
            $parsedData[$key] = json_decode($value)->{$locale} ?? $value;
        }
        return $parsedData;
    }
}

if (!function_exists('parseValueByKeyToSimpleSelect')) {
    /**
     * Function to make parseDataForSelect with ID and Title from array.
     * @param array $array
     * @return array
     */
    function parseValueByKeyToSimpleSelect(array $array = []): array
    {
        $parsedData = [];
        $locale = str_replace('-', '_', app()->getLocale());
        foreach ($array as $key => $value) {
            $parsedData[$key] = json_decode($value)->{$locale} ?? $value;
        }
        return $parsedData;
    }
}

if (!function_exists('getDistrictsAsOptgroups')) {
    function getDistrictsAsOptgroups()
    {
        return array_map(
            fn (District $district) => [
                'optgroup' => $district->value,
                'id' => $district->value,
                'title' => $district->name(),
            ],
            District::cases()
        );
    }
}

if (!function_exists('getCountiesAsOptions')) {
    function getCountiesAsOptions()
    {
        return array_map(
            fn (County $county) => [
                'optgroup' => $county->district()->value,
                'id' => $county->value,
                'title' => $county->name(),
            ],
            County::cases()
        );
    }
}

if (!function_exists('getEuCountries')) {
    function getEuCountries()
    {
        return array_keys(EuCountry::toArray());
    }
}

if (!function_exists('validateCounty')) {
    function validateCounty()
    {
        return County::casesArray('value');
    }
}

if (!function_exists('getRolesForSelect')) {
    function getRolesForSelect()
    {
        return Role::all()->map(function ($role) {
            return [
                'id' => $role->id,
                'title' => $role->name,
            ];
        });
    }
}

if (!function_exists('getPermissionsForSelect')) {
    function getPermissionsForSelect()
    {
        $optgroups = [];

        $options = Permission::all()->map(function ($permission) use (&$optgroups) {
            $optgroups[] = $permission->group;

            return [
                'optgroup' => slugable($permission->group),
                'id' => $permission->id,
                'title' => $permission->description,
            ];
        });

        $optgroups = collect($optgroups)->unique()->map(function ($group) {
            $slug = slugable($group);

            return [
                'optgroup' => $slug,
                'id' => $slug,
                'title' => $group,
            ];
        });

        return [
            'optgroups' => $optgroups,
            'options' => $options,
        ];
    }
}
if (!function_exists('getSdgsForSelect')) {
    function getSdgsForSelect()
    {
        return Sdg::all()
            ->map(function ($sdg) {
                return [
                    'id' => $sdg->id,
                    'title' => $sdg->id . '. ' . $sdg->name,
                    'img' => view('icons/sdgs/' . $sdg->id)->render(),
                ];
            })
            ->toArray();
    }
}

if (!function_exists('getBusinessSectorsForSelect')) {
    function getBusinessSectorsForSelect()
    {
        $key = App::getLocale() . ':business_sectors_select';

        return Cache::rememberForever($key, function () {
            $businessSectors = BusinessSector::whereHas('sectorType')->get()
                ->map(function ($businessSector) {
                    return [
                        'optgroup' => $businessSector->parent_id,
                        'id' => $businessSector->id,
                        'title' => $businessSector->name,
                    ];
                })
                ->toArray();

            $optgroups = array_filter($businessSectors, fn ($businessSec) => !$businessSec['optgroup']);
            $options = array_filter($businessSectors, fn ($businessSec) => $businessSec['optgroup']);

            $data = !$optgroups && !$options
                ? []
                : ['optgroups' => $optgroups, 'options' => $options];

            if ($data) {
                return $data;
            }
        });
    }
}

if (!function_exists('getSizeForHumans')) {
    function getSizeForHumans($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = [' bytes', ' KB', ' MB', ' GB', ' TB'];

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }

        return $size;
    }
}
if (!function_exists('showSupportFeatures')) {

    function showSupportFeatures()
    {
        return auth()->user()->email === 'test@esg-maturity.com';
    }
}
if (!function_exists('color')) {
    function color($name)
    {
        $name = is_numeric($name)
            ? 'esg' . $name
            : $name;

        return tenant()->colors[$name] ?? config('theme.default.colors.' . $name);
    }
}

if (!function_exists('customInclude')) {
    /**
     * Custom include for views.
     */
    function customInclude($view = null)
    {
        $isCentral = str_contains('.central', $view);
        $prefix = "instances." . config('app.instance') . ".";
        if (isset(tenant()->views) && view()->exists(tenant()->views . $view)) {
            return tenant()->views . $view;
        } elseif (view()->exists($prefix . $view)) {
            return $prefix . $view;
        }

        if ($isCentral) {
            abort(404, __("View not found"));
        }

        return $view;
    }
}

if (!function_exists('customView')) {
    /**
     * Custom view for views.
     */
    function customView($view = null, $mergeData = [], $plainText = false)
    {
        $view = customInclude($view);
        return $plainText
            ? $view
            : view($view, $mergeData);
    }
}

if (!function_exists('tenantView')) {
    /**
     * Custom view for tenants views.
     */
    function tenantView($view = null, $data = [], $mergeData = [])
    {
        return view()->first([tenant()->views . $view, $view], $data, $mergeData);
    }
}

if (!function_exists('getUnitsForSelect')) {
    function getUnitsForSelect($unityQty)
    {
        $measure = Converter::getMeasurements();
        if (!isset($measure[$unityQty])) {
            return [];
        }
        $units = collect($measure[$unityQty]);

        return $units->map(function ($value, $key) use ($unityQty) {
            return [
                'id' => "{$unityQty}.{$key}",
                'title' => __($value['label']) ?? '',
            ];
        })
            ->toArray();
    }
}

if (!function_exists('enableRowWhenSeeding')) {

    /**
     * Check if the row is to enable or not on Seeders
     */
    function enableRowWhenSeeding($data)
    {
        $tenant = tenant();

        $only = !empty($data['only'])
            ? explode(',', $data['only'])
            : [];
        $except = !empty($data['except'])
            ? explode(',', $data['except'])
            : [];

        $only = array_map('trim', $only);
        $except = array_map('trim', $except);

        return in_array($tenant->id, $only, false)
            || (!$only && !in_array($tenant->id, $except, false));
    }
}
if (!function_exists('getRolePermissionsGroup')) {
    /**
     * get roles permission group.
     *
     * @param array $permissions
     *
     * @return array
     */
    function getRolePermissionsGroup($permissions = [])
    {
        $rolesGroup = [];

        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                $permissionName = explode('»', $permission['description']);
                $rolesGroup[$permission['group']][] = $permissionName[1];
            }
        }

        return $rolesGroup;
    }
}

if (!function_exists('parseDynamicProperty')) {
    function parseDynamicProperty(string $prop)
    {
        if (strpos($prop, 'php:') !== 0) {
            return $prop;
        }

        // Remove the prefix `php:`
        $prop = substr($prop, 4);
        $parts = explode(':::', $prop);

        $callback = $parts[0];
        unset($parts[0]);

        // Check if the parameter is a function and execute it.
        // Otherwise, use the literal value.
        $params = array_map(
            fn ($param) => str_ends_with($param, '()')
                ? call_user_func(substr($param, 0, -2))
                : $param,
            $parts
        );

        return strpos($callback, 'new ') === 0
            ? new (substr($callback, 4))
            : call_user_func($callback, ...$params);
    }
}

if (!function_exists('mergeCustomRules')) {
    function mergeCustomRules(array $rules, array $customRules, array $customColumnRules = []): array
    {
        $customRules = array_map(
            function ($customRule) {
                return [
                    $customRule['id'] => array_map(fn ($rule) => parseDynamicProperty($rule), explode('|', $customRule['rules'])),
                ];
            },
            $customRules
        );

        return array_merge($rules, ...$customColumnRules, ...$customRules);
    }
}

if (!function_exists('implodeArray')) {
    /**
     * Implode array with custom glue.
     * @param array $array
     * @param string $glue
     * @return string
     */
    function implodeArray($array, string $glue = ','): string
    {
        if (!is_array($array) || empty($array)) {
            return '';
        }

        return implode($glue, array_filter($array));
    }
}

if (!function_exists('livewireDebug')) {
    /**
     * Simple function to debug vars in Livewire components.
     * @param mixed ...$args
     * @return void
     * @example livewireDebug($var1, $var2, $var3);
     */
    function livewireDebug(...$args): void
    {
        if (!config('app.debug') || config('app.env') !== 'local') {
            return;
        }

        if (!$args) {
            Log::debug('livewireDebug() called without arguments');
        }

        foreach ($args as $i => $arg) {
            if (!is_array($arg)) {
                $arg = [$arg];
            }
            Log::debug("Debug $i: ", $arg);
        }
    }
}

if (!function_exists('carbon')) {
    /**
     * Create a Carbon instance from a string.
     *
     * @param  string|null  $time
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     */
    function carbon()
    {
        return new \Carbon\Carbon();
    }
}

if (!function_exists('formatDate')) {
    /**
     * Create a Carbon instance from a string.
     *
     * @param  string|null  $time
     * @param  \DateTimeZone|string|null  $tz
     * @return \Illuminate\Support\Carbon
     */
    function formatDate(string $date, string $format = "Y-m-d")
    {
        $date = Carbon::parse($date);
        return $date->format($format);
    }
}

if (!function_exists('getTagsForSelect')) {
    /**
     * Get tags for select.
     * @return array $tags
     */
    function getTagsForSelect(): array
    {
        $tags = Tag::all();

        return $tags->count() > 0
            ? parseDataForSelect($tags, 'id', 'name')
            : [];
    }
}

if (!function_exists('slugable')) {
    /**
     * Slugify a string.
     */
    function slugable($string): string
    {
        return Str::slug($string);
    }
}

if (!function_exists('makeResourcAble')) {
    /**
     * The resource to search for. Will be apply some rules to check if the resource exists
     * @param string $resource - Its the resource name
     * @return string
     */
    function makeResourcAble(string $resource)
    {
        /**  Replace spaces to underscores and convert to lowercase    */
        $resource = str_replace(' ', '_', trim(strtolower($resource)));
        /** remove the last character if was a letter 's' */
        if (substr($resource, -1) == 's') {
            $resource = substr($resource, 0, -1);
        }
        /** if has an _ remove the _ and set the next letter to uppercase */
        if (strpos($resource, '_') !== false) {
            $resource = explode('_', $resource);
            $resource = $resource[0] . ucfirst($resource[1]);
        }

        /** set the first letter uppercase */
        $resource = ucfirst($resource);

        /** if resource == User , namespaceh is App Models , if not is App Models Tenant */
        $namespace = "\App\Models\Tenant";
        if ($resource == 'User') {
            $namespace = "\App\Models";
        }

        /** if resource was not found, try some hooks */
        if (!class_exists($namespace . '\\' . $resource)) {
            /** if resource ends with ie, change ie to y  */
            if (substr($resource, -2) == 'ie') {
                $resource = substr($resource, 0, -2) . 'y';
            }
        }

        return $namespace . '\\' . $resource;
    }
}
if (!function_exists('tenantCustomImages')) {
    /**
     * get file path from tenant customisation folder.
     * @param string $path
     * @return string
     */

    //TODO change this to the instancesImages
    function tenantCustomImages($path, $defaultPath)
    {
        $customFolder = '/images/customizations/tenant' . tenant()->getTenantKey();
        $customFilePath = $customFolder . '/' . $path;

        return file_exists(public_path($customFilePath))
            ? $customFilePath
            : $defaultPath;
    }
}

if (!function_exists('instanceImages')) {
    function instanceImages($img)
    {
        $filePath = str_replace('.', '/', tenant()->views) . "images/{$img}";
        return file_exists(public_path($filePath))
            ? $filePath
            : global_asset('images/' . $img);
    }
}

if (!function_exists('userTypeList')) {
    function userTypeList()
    {
        return [
            UserType::INTERNAL->value => UserType::INTERNAL->label(),
            UserType::EXTERNAL->value => UserType::EXTERNAL->label(),
        ];
    }
}

if (!function_exists('calculatePercentage')) {
    /**
     * Calculate a percentage to avoid division by 0 error.
     * Denominator can be 0, null, false, etc.
     *
     * Example:
     * 50 ($denominator) » 100%
     * 20 ($numerator)   » x%
     * x = 40%
     */
    function calculatePercentage($numerator, $denominator, $precision = 0)
    {
        return round(calculateDivision($numerator, $denominator) * 100, $precision);
    }
}
if (!function_exists('calculateDivision')) {
    /**
     * Calculate a percentage to avoid division by 0 error.
     * Denominator can be 0, null, false, etc.
     * @param float $numerator
     * @param float $denominator
     * @param int|null $precision - Number of decimal places
     */
    function calculateDivision($numerator, $denominator, $precision = null)
    {
        $numerator = is_numeric($numerator)
            ? (float) $numerator
            : 0;
        $denominator = is_numeric($denominator)
            ? (float) $denominator
            : 0;

        $result = $denominator
            ? $numerator / $denominator
            : 0;

        return is_null($precision)
            ? $result
            : round($result, $precision);
    }
}

/** check if a $var is a instance of Groups */
if (!function_exists('isGroup')) {
    function isGroup($var): bool
    {
        return $var instanceof \App\Models\Tenant\Groups;
    }
}

/**
 * Combine 1 or more arrays into a single multidimensional array
 * @param array ...$arrays
 * @return array
 * @example makeMultiArray([1,2,3], [4,5,6], [7,8,9]);
 * @results [
 *  [1,2,3],
 *  [4,5,6],
 * ]
 */
if (!function_exists('makeMultiArray')) {
    function makeMultiArray(...$arrays): array
    {
        return $arrays ?? [];
    }
}

/**
 * Mount a breadcrumb from an array . Receive a array of data, the route to redirect.
 * @param array $data
 * @param string $route
 * @param string $routeParam
 * @param string $key
 */
if (!function_exists('mountBreadcrumb')) {
    function mountBreadcrumb(array $data, string $route, string $routeParam = 'id', string $key = 'name'): array
    {
        $breadcrumb = [];
        $limit = 50;
        foreach ($data as $value) {
            $text = strlen($value[$key]) > $limit
                ? substr($value[$key], 0, $limit) . '...'
                : $value[$key];
            $breadcrumb[] = [
                'text' => $text,
                'href' => route($route, [$routeParam => $value['id']]),
            ];
        }

        return $breadcrumb;
    }
}

if (!function_exists('calcTimeDiffHuman')) {
    function calcTimeDiffHuman($start, $end)
    {
        if ($start == '' || $end == '') {
            return null;
        }

        $startTime = new \Carbon\Carbon($start);

        return $startTime->diffInHours($end) . ' Hrs ' . $startTime->diff($end)->format('%I') . ' Mins';
    }
}
if (!function_exists('calcTimeDiffInMin')) {
    function calcTimeDiffInMin($start, $end)
    {
        if ($start == '' || $end == '') {
            return null;
        }
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        return $end->diffInMinutes($start);
    }
}
if (!function_exists('extractTextAndValueFromString')) {
    /**
     * Extract text and value from string.
     * @param string $item
     * @param Model $res
     */
    function extractTextAndValueFromString($item, $res)
    {
        if (preg_match('/::/', $item)) {
            $item = explode('::', $item);
            $text = $item[0];
            $text = $res->$text ?? '';
            $value = $item[1];
        } elseif (strpos($item, '.') != false) {
            // Relations
            $item = explode('.', $item);
            $relation = $item[0];
            $value = $item[1];
            $value = $res->$relation->$value ?? '';
        } elseif (strpos($item, '->') != false) {
            // Carbon
            $item = explode('->', $item);
            $column = $item[0];
            $arr = $item[1];
            $arr = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $arr);
            $arr = str_replace('format', '', $arr);
            $value = carbon()->parse($res->$column)->format($arr) ?? '';
        } elseif (strpos($item, ' ') != false) {
            // concat string with space
            $item = explode(' ', $item);
            $column = $item[0];
            $arr = $item[1];
            $value = $res->$column . ' ' . $arr;
        } else {
            $value = $res->$item ?? '';
        }

        return [
            'text' => $text ?? '',
            'value' => $value ?? '',
        ];
    }
}
if (!function_exists('generateDateRange')) {
    function generateDateRange($start_date, $end_date)
    {
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $dates = [];

        for ($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        unset($dates[0]);

        return $dates;
    }
}
if (!function_exists('addMissingDates')) {
    function addMissingDates($start_date, $end_date, $labels, $data, $onlyData = false)
    {
        $dateRanges = generateDateRange($end_date, $start_date);

        $returnLabel = [];
        $returnData = [];
        foreach ($dateRanges as $key => $date) {
            $labelKey = array_search($date, $labels);
            $returnLabel[] = $date;
            $returnData[] = ($labelKey != '' && isset($data[$labelKey]))
                ? $data[$labelKey]
                : 0;
        }
        if ($onlyData) {
            return $returnData;
        }

        return [
            'labels' => $returnLabel,
            'data' => $returnData,
        ];
    }
}
if (!function_exists('removeNullDataFromArray')) {
    /**
     * Remove null or 0 value data from array.
     * This function will help you eliminate empty data from arrary to avoid display in charts
     * @param $labels
     * @param $data
     *
     * return array with data
     */
    function removeNullDataFromArray($labels, $rowdata)
    {
        $label = [];
        $data = [];
        $loop = 0;

        foreach ($rowdata as $row) {
            if ($row != 0 && $row != null) {
                array_push($label, $labels[$loop]);
                array_push($data, $row);
            }

            $loop++;
        }

        if (empty($label) && empty($data)) {
            return null;
        }

        return [
            'label' => $label,
            'data' => $data,
        ];
    }
}
if (!function_exists('showActivityString')) {
    function showActivityString($attributes)
    {
        $dataAttributes = array_map(function ($value, $key) {
            $value = is_array($value)
                ? '[' . implode(', ', $value) . ']'
                : $value;

            return $key . '="' . $value . '"';
        }, array_values($attributes), array_keys($attributes));

        return implode(', ', $dataAttributes);
    }
}
if (!function_exists('showUpdatedActivityString')) {
    /**
     * Generate updated activity log string
     */
    function showUpdatedActivityString($old, $new)
    {
        $dataAttributes = array_map(function ($value, $key, $new) {
            $value = is_array($value)
                ? '[' . implode(', ', $value) . ']'
                : $value;
            $new = is_array($new)
                ? '[' . implode(', ', $new) . ']'
                : $new;

            return $key . '="' . $value . ' To ' . $new . '"';
        }, array_values($old), array_keys($old), $new);

        return implode(', ', $dataAttributes);
    }
}
if (!function_exists('removeBooleanFromArray')) {
    /**
     * Remove boolean value from array
     */
    function removeBooleanFromArray($array)
    {
        return array_map(function ($value) {
            return is_numeric($value)
                ? $value
                : 0;
        }, $array);
    }
}
if (!function_exists('calculateMedian')) {
    /**
     * Calculate median number from array
     */
    function calculateMedian($array)
    {
        sort($array);
        $count = sizeof($array);
        $index = floor($count / 2);
        $return = 0;
        if (!$count) {
            $return = 0;
        } elseif ($count & 1) {    // count is odd
            $return = $array[$index];
        } else {
            $return = ($array[$index - 1] + $array[$index]) / 2;
        }
        return $return;
    }
}
if (!function_exists('standardDeviation')) {
    /**
     * Calculate stand deviation from array
     */
    function standardDeviation($arr)
    {
        $num_of_elements = count($arr);

        $variance = 0.0;
        $average = array_sum($arr) / $num_of_elements;
        foreach ($arr as $i) {
            $variance += pow(($i - $average), 2);
        }
        return (float)sqrt($variance / $num_of_elements);
    }
}
if (!function_exists('parseStringToArray')) {
    /**
     * Parse string to array
     * @param string|array $data
     * @return string|array|null
     */
    function parseStringToArray($data): string|array|null|object
    {
        if (is_string($data)) {
            $decode = @json_decode($data, true);

            //Double check if the data is a string
            if (is_string($decode)) {
                $decode = @json_decode($decode, true);
            }

            return (json_last_error() === JSON_ERROR_NONE)
                ? $decode
                : [$data];
        }
        return $data;
    }
}

if (!function_exists('lastTwelweMonth')) {
    /**
     * get last twelwe month array
     */
    function lastTwelweMonth()
    {
        $period = carbon()->now()->subMonths(12)->monthsUntil(carbon()->now()->subMonths(1));
        $data = [];
        foreach ($period as $date) {
            // years only last 2 digits
            $data[] = strtolower($date->shortMonthName) . '-' . $date->format('y');
        }
        return $data;
    }
}
if (!function_exists('convertToShortMonthName')) {
    /**
     * get short month name form number
     */
    function convertToShortMonthName($monthNum)
    {
        return carbon()->createFromFormat('m', $monthNum)->shortMonthName;
    }
}

if (!function_exists('convertUnits')) {
    function convertUnits($unitFrom, $unitTo, $value, $decimal = 3)
    {
        $unitFrom = strtolower($unitFrom);
        $unitTo = strtolower($unitTo);
        $value = Converter::from($unitFrom)->to($unitTo)->convert($value)->getValue();
        return round($value, $decimal);
    }
}

if (!function_exists('calcEmissionFactor')) {
    function calcEmissionFactor($unitFrom, $value)
    {
        $unitFrom = strtolower($unitFrom);
        return Converter::from($unitFrom)->emission_factor * $value;
    }
}

if (!function_exists('encryptValue')) {
    /**
     * Encrypt a value and store it in session.
     */
    function encryptValue($value)
    {
        if (!$value) {
            return '';
        }

        if (!is_int($value)) {
            $value = (int)$value;
        }

        if (!session()->has($value)) {
            session([$value => Crypt::encrypt($value)]);
        }
        return session()->get($value);
    }
}



if (!function_exists('decryptValue')) {
    function decryptValue($value)
    {
        try {
            return $value
                ? Crypt::decrypt($value)
                : '';
        } catch (DecryptException  $ex) {
            abort(422, __('The value provided is not a valid encrypted value.'));
        }
    }
}

if (!function_exists('encryptMd5')) {
    function encryptMd5($value)
    {
        return md5('CMORESUSTAINABILITY' . $value);
    }
}


if (!function_exists('showGrowth')) {
    function showGrowth($growth = 0)
    {
        if ($growth > 0) {
            return '+' . $growth;
        }
        return $growth;
    }
}


if (!function_exists('getRiskLevelLabel')) {
    /**
     * Get the risk level label.
     * @param string $slug - the slug of the risk level
     */
    function getRiskLevelLabel($slug): string
    {
        return Risk::fromSlug($slug)->label() ?? '';
    }
}

if (!function_exists('getRiskLevelColor')) {
    /**
     * Get the risk level label.
     * @param string $slug - the slug of the risk level
     */
    function getRiskLevelColor($slug, $type = 'formatted'): string
    {
        if ($type == 'formatted') {
            return Risk::fromSlug($slug)->formattedColor() ?? '';
        } else {
            return Risk::fromSlug($slug)->color() ?? '';
        }
    }
}
if (!function_exists('getCompanyCategoriesOptions')) {
    function getCompanyCategoriesOptions()
    {
        return array_map(
            fn (CompanyCategories $category) => [
                'id' => $category->value,
                'title' => $category->label(),
            ],
            CompanyCategories::cases()
        );
    }
}
if (!function_exists('getYesNoOptions')) {
    function getYesNoOptions()
    {
        return parseKeyValueForSelect([
            1 => 'Yes',
            0 => 'No'
        ]);
    }
}
if (!function_exists('getStringsBetweenDollars')) {
    function getStringsBetweenDollars($string)
    {
        preg_match_all('/\$(\w+)\$/', $string, $matches);
        return $matches[1] ?? [];
    }
}

if (!function_exists('replaceIndicatorWithValueInCalc')) {
    function replaceIndicatorWithValueInCalc($valueArr, $calc)
    {
        $returnString = $calc;
        foreach ($valueArr as $indicatorId => $value) {
            if ($value == null) {
                return "";
            }
            $returnString = str_replace('$' . $indicatorId . '$', $value, $returnString);
        }
        return str_replace('$', '', $returnString);
    }
}

if (!function_exists('evalmath')) {
    function evalmath($equation)
    {
        try {
            eval('$content = (' .  $equation . ');');
        } catch (\Throwable $t) {
            $content = 0;
        }
        return round($content, 2);
    }
}

if (!function_exists('translateJson')) {
    function translateJson($arr)
    {
        $locale = app()->getLocale();
        $arr = parseStringToArray($arr);
        $firstArrayKey = array_key_first($arr);
        return $arr[$locale] ?? $arr['en'] ?? $arr[$firstArrayKey];
    }
}

if (!function_exists('getAnswerValue')) {
    /**
     * Get the answer value.
     */
    function getAnswerValue($model)
    {
        if ($model instanceof App\Models\Tenant\QuestionOption) {
            $model = $model->question->answer;
        } elseif ($model instanceof App\Models\Tenant\Question) {
            $model = $model->answer;
        }
        return parseStringToArray($model->value) ?? [];
    }
}
if (!function_exists('parseOnlyAndExcepts')) {
    function parseOnlyAndExcepts($value)
    {
        if (!$value) {
            $value = [];
        } else {
            $value = is_array($value)
                ? $value
                : explode(',', str_replace(' ', '', $value));
        }

        return $value;
    }
}

if (!function_exists('forceTranslations')) {
    /**
     * This function should not be used.
     * It only serves to force translations
     */
    function forceTranslations()
    {
        return [
            __('Entity Type'),
            __('River flood'),
            __('Coastal flood'),
            __('Landslide'),
            __('Volcano'),
            __('Volcano'),
            __('Wildfire'),
            __('Urban flood'),
            __('Earthquake'),
            __('Tsunami'),
            __('Cyclone'),
            __('Extreme heat'),
            __('Mitigação das alterações climáticas'),
            __('Adaptação às alterações climáticas'),
            __('Recursos hídricos e marinhos'),
            __('Economia circular'),
            __('Poluição'),
            __('Biodiversidade e ecossistemas'),
            __('Cancel'),
            __("Small"),
            __("Micro"),
            __("Medium"),
            __("Large"),
            __("Very large"),
            __("Municipality"),
        ];
    }
}


if (!function_exists('formatToCurrency')) {
    /**
     * Format value to currency output
     */
    function formatToCurrency($value, $withoutSymbol = false, $currency = null)
    {
        /**
         * if value is blank, null or non numeric return as it is, because we cant show 0 if value if blank, null or non numeric
         */
        if ($value === "" || is_null($value) || !is_numeric($value)) {
            return $value;
        }

        $formatter = $withoutSymbol
            ? NumberFormatter::DECIMAL
            : NumberFormatter::CURRENCY;
        $formatterCurrency = $currency ?: tenant()->get_default_currency;

        $format = new NumberFormatter(auth()->user()->locale, $formatter);
        $format->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);

        if ($withoutSymbol) {
            return $format->format($value);
        } else {
            $formattedCurrency = $format->formatCurrency($value, $formatterCurrency);
            /**
             * Sometime there is currency symobls like k€, M€, so it'll not format currency, so here we format value as number
             * and symply concat the currency.
             */
            if (!$formattedCurrency) {
                return formatNumber($value) . ' ' . $currency;
            }
            return $formattedCurrency;
        }
    }
}

if (!function_exists('roundValues')) {
    function roundValues($value, $precision = 2)
    {
        return round($value, $precision);
    }
}

if (!function_exists('arrReplaceValueFromArray')) {
    function arrReplaceValueFromArray($baseArr = [], $fromArray = [], $chart = false)
    {
        $returnArr = [];
        if (!empty($baseArr)) {
            foreach ($baseArr as $keyInFromArray) {
                if ($chart) {
                    $returnArr[] = isset($fromArray[$keyInFromArray]) && trim($fromArray[$keyInFromArray]) != "" ?
                        $fromArray[$keyInFromArray] : 0;
                } else {
                    $returnArr[$keyInFromArray] = $fromArray[$keyInFromArray] ?? null;
                }
            }
        }
        return $returnArr;
    }
}

if (!function_exists('hex2rgba')) {

    function hex2rgba($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color)) {
            return $default;
        }

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6 || strlen($color) == 8) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }
}

if (!function_exists('getRowsByValue')) {
    function getRowsByValue($array, $key)
    {
        return array_filter($array, function ($value) use ($key) {
            return !empty($value[$key]);
        });
    }
}

if (!function_exists('filterArrayByKeyValue')) {
    function filterArrayByKeyValue($array, $key, $keyValue, $isRange = false)
    {
        $keyValue = $isRange
            ? explode('-', $keyValue)
            : $keyValue;
        return array_filter($array, function ($value) use ($key, $keyValue, $isRange) {
            if ($isRange) {
                return ($value[$key] >= $keyValue[0] && $value[$key] <= $keyValue[1]);
            } elseif (is_array($keyValue)) {
                return in_array($value[$key], $keyValue);
            } else {
                return $value[$key] == $keyValue;
            }
        });
    }
}

if (!function_exists('filterArrayWithArray')) {
    function filterArrayWithArray($array, $searchList)
    {
        $result = array();
        foreach ($array as $key => $value) {
            foreach ($searchList as $k => $v) {
                if (is_array($v)) {
                    if (!in_array($value[$k], $v)) {
                        continue 2;
                    }
                } else {
                    if (!isset($value[$k]) || strtolower($value[$k]) != strtolower($v)) {
                        continue 2;
                    }
                }
            }
            $result[] = $value;
        }
        return $result;
    }
}

if (!function_exists('filterArrayWithKeys')) {
    function filterArrayWithKeys($array, $allowed)
    {
        $filtered = array_filter(
            $array,
            function ($key) use ($allowed) {
                return in_array($key, $allowed);
            },
            ARRAY_FILTER_USE_KEY
        );
        return $filtered;
    }
}

if (!function_exists('formatNumber')) {
    function formatNumber($value, $decimal = 2)
    {
        /**
         * if value is blank, null or non numeric return as it is, because we cant show 0 if value if blank, null or non numeric
         */
        if ($value === "" || is_null($value) || !is_numeric($value)) {
            return $value;
        }

        $format = new NumberFormatter(auth()->user()->locale, NumberFormatter::DECIMAL);
        $format->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $decimal);
        return $format->format($value);
    }
}

if (!function_exists('getLetterByIndex')) {
    /**
     * Get letter by index.
     */
    function getLetterByIndex($index)
    {
        $letters = range('A', 'Z');
        return $letters[$index];
    }
}


if (!function_exists('hasForeignKey')) {
    /**
     * Check if a column has a foreign key.
     * @param string $table
     * @param string $column
     * @return bool
     */
    function hasForeignKey(string $table, string $column): bool
    {
        $fkColumns = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableForeignKeys($table);

        return collect($fkColumns)->map(function ($fkColumn) {
            return $fkColumn->getColumns();
        })->flatten()->contains($column);
    }
}

if (!function_exists('hasPrimaryKey')) {
    /**
     * Check if a column has a primary key.
     * @param string $table
     * @param string $column
     * @return bool
     */
    function hasPrimaryKey(string $table, string $column): bool
    {
        $result = collect(DB::select("describe $table"));
        return $result->where('Field', $column)
            ->where('Key', 'PRI')
            ->isNotEmpty();
    }
}

if (!function_exists('encodeHtmlToString')) {
    /**
     * Encode HTML special characters in a string.
     * @param string $text - Its the resource name
     * @return string
     */
    function encodeHtmlToString(string $text)
    {
        return e($text);
    }
}


if (!function_exists('findInCollection')) {
    /**
     * Find a value in a collection.
     * @param array|Collection $search -The array or collection to search
     * @param string $key - The key to search
     * @param string $value - The value to search
     */
    function findInCollection(array|Collection $search, string $key, string $value)
    {
        if (is_array($search)) {
            $search = collect($search);
        }

        return $search->where($key, $value)->first();
    }
}

if (!function_exists('createQuestionnaireSpiderChart')) {
    /**
     * Create a questionnaire spider chart.
     * @param array $data - The data to create the chart
     * @param array $labels - The labels to create the chart
     * @return array
     */
    function createQuestionnaireSpiderChart($data, $labels, $modelForLegends = null): array
    {
        $chartData = [];

        $mainColor = color(5);
        $datasets = [
            [
                'label' => __('Data'),
                'mainColor' => hex2rgba($mainColor, 0.5),
                'backgroundColor' => hex2rgba($mainColor, 0.5),
                'borderColor' => $mainColor,
                'pointBorderColor' => $mainColor,
                'pointHoverBackgroundColor' => $mainColor,
                'pointHoverBorderColor' => '#FFF',
                'backgroundLightColor' => hex2rgba($mainColor, 0.15),
                'pointBackgroundColor' => 'white',
                'borderWidth' => 1,
                'pointRadius' => 6,
                'data' => $data,
            ]
        ];

        $chartData['labels'] = $labels;
        $chartData['data'] = $datasets;

        if ($modelForLegends) {
            $chartData['legends'] = $modelForLegends;
        }

        return $chartData;
    }
}


if (!function_exists('createQuestionnaireSpiderChartWithCategories')) {
    /**
     * Create a questionnaire spider chart.
     * @param array $data - The data to create the chart
     * @param array $labels - The labels to create the chart
     * @return array
     */
    function createQuestionnaireSpiderChartWithCategories($data): array
    {
        $chartData = [];
        $datasets = [];

        foreach ($data as $key => $d) {
            $mainColor = colorFromText($key);
            $datasets[] = [
                'label' => $key,
                'backgroundColor' => hex2rgba($mainColor, 0.5),
                'borderColor' => $mainColor,
                'pointBorderColor' => $mainColor,
                'pointHoverBackgroundColor' => $mainColor,
                'pointHoverBorderColor' => '#FFF',
                'backgroundLightColor' => hex2rgba($mainColor, 0.15),
                'pointBackgroundColor' => 'white',
                'borderWidth' => 1,
                'pointRadius' => 6,
                'data' => array_values($d),
            ];
        }

        $chartData['labels'] = array_keys($data[array_key_first($data)]);
        $chartData['data'] = $datasets;
        return $chartData;
    }
}

if (!function_exists('colorFromText')) {

    /**
     * Generate a color from a text.
     * @param string $text - The text to generate the color
     */
    function colorFromText(string $text): string
    {
        $code = dechex(crc32($text));
        $code = substr($code, 0, 6);
        return '#' . str_pad($code, 6, '0', STR_PAD_LEFT);
    }
}
