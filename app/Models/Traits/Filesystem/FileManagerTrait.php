<?php

namespace App\Models\Traits\Filesystem;

trait FileManagerTrait
{
    /**
     * Saves the given data to a JSON file in the specified directory.
     * @param  string $dir  The directory to save the file in.
     * @param  string $name The name of the file to save.
     * @param  array  $data The data to save to the file.
     * @return void
     */
    public function saveFileLocally($dir, $name, $data)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!preg_match('/var\/www\/html/', $dir)) {
            if (!starts_with($dir, '/')) {
                $dir = '/' . $dir;
            }

            $dir = base_path() . "{$dir}";
        }

        if (!ends_with($dir, '/')) {
            $dir .= '/';
        }

        file_put_contents("{$dir}{$name}.json", json_encode($data, true));

        if (config('app.env') == 'local') {
            chgrp($dir, 'www-data');
            chown($dir, 'www-data');

            chgrp("{$dir}{$name}.json", 'www-data');
            chown("{$dir}{$name}.json", 'www-data');
        }
    }
}
