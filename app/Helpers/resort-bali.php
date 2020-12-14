<?php
use App\Models\Log;
use App\Models\SocialMedia;
if (!function_exists('store_log')) {
    function store_log($ip, $browser, $page, $action, $user_id)
    {
        Log::create([
            'ip_address' => $ip,
            'browser'    => $browser,
            'page'       => $page,
            'action'     => $action,
            'user_id'    => $user_id
        ]);

    }
}

