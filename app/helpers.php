<?php

/**
 * Application helper functions.
 *
 * This file existed previously in the project and is required by Composer's
 * autoload. Create a minimal safe file to avoid fatal require errors. Add
 * real helpers here as needed.
 */

if (!function_exists('dd')) {
    /**
     * Dump the given variables and end the script.
     * Minimal implementation in case the project relied on `dd`.
     *
     * @param mixed ...$vars
     * @return void
     */
    function dd(...$vars)
    {
        foreach ($vars as $v) {
            var_dump($v);
        }
        exit(1);
    }
}

// Add other project-specific helpers below as needed.
