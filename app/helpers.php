<?php

use Jenssegers\Date\Date;

/**
 * GLOBAL HELPER FUNCTIONS
 */

 if(!function_exists('format_date')) {

    
    /**
     * Create a formatted and localized date string
     *
     * @param mixed $date
     * @param string $format
     * 
     * @return string
     */
    function format_date($date, string $format = 'j F Y'): string
    {
        Date::setLocale(app()->getLocale());

        return Date::parse($date)->format($format);
    }
 }

