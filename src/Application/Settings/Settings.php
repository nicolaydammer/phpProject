<?php
declare(strict_types=1);

namespace App\Application\Settings;

class Settings implements SettingsInterface
{
    private $settings;

    //inject settings
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    //get setting by key name
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}