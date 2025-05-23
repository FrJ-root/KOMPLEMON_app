<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Settings
{
    protected $settings = [];
    protected $loaded = false;

    public function __construct()
    {
        $this->load();
    }

    protected function load()
    {
        if ($this->loaded) {
            return;
        }

        $this->settings = Cache::remember('settings', 60 * 24, function () {
            return DB::table('settings')->pluck('value', 'key')->toArray();
        });

        $this->loaded = true;
    }

    public function get($key, $default = null)
    {
        $this->load();
        return $this->settings[$key] ?? $default;
    }

    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
            return;
        }

        $this->settings[$key] = $value;
    }

    public function save()
    {
        foreach ($this->settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        Cache::forget('settings');
        $this->load();
    }

    public function all()
    {
        $this->load();
        return $this->settings;
    }
}
