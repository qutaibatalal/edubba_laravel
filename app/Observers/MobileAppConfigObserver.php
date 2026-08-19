<?php

namespace App\Observers;

use App\Models\MobileAppConfig;
use Illuminate\Support\Facades\Cache;

class MobileAppConfigObserver
{
    public function saved(MobileAppConfig $config): void
    {
        Cache::forget('app_config_public');
    }

    public function deleted(MobileAppConfig $config): void
    {
        Cache::forget('app_config_public');
    }
}
