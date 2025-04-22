<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CoreSettings extends Settings
{
    public string $site_name;
    public string $site_url;
    public bool $is_maintenance_mode;
    public string $meta_title;
    public string $meta_description;

    public static function group(): string
    {
        return 'core';
    }
}
