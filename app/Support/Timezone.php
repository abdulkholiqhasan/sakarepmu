<?php

namespace App\Support;

class Timezone
{
    public static function list(): array
    {
        // minimal curated list for brevity; can be expanded later
        return [
            'UTC',
            'Europe/London',
            'Europe/Paris',
            'America/New_York',
            'America/Los_Angeles',
            'Asia/Jakarta',
            'Asia/Tokyo',
            'Australia/Sydney',
        ];
    }
}
