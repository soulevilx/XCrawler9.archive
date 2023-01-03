<?php

namespace Modules\Core\Services;

use Modules\Core\Models\Setting;

class SettingService
{
    public function __construct(private Setting $setting)
    {
    }

    public function remember(string $group, string $key, callable $callback)
    {
        if ($this->setting->newQuery()->group($group)->key($key)->exists()) {
            return $this->setting->newQuery()->group($group)->key($key)->first()->value;
        }

        return $this->setting->newQuery()->group($group)->key($key)->firstOrCreate([
            'group' => $group,
            'key' => $key,
            'value' => $callback(),
        ])->value;
    }

    public function get(string $group, string $key)
    {
        return $this->setting->newQuery()->group($group)->key($key)->first()->value;
    }

    public function forget(string $group, string $key): void
    {
        $this->setting->newQuery()->group($group)->key($key)->delete();
    }
}
