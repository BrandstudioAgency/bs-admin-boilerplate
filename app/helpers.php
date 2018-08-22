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

 if(!function_exists('seo_generate')) {

    /**
     * Set seo metas and opengraph
     *
     * @param string $title
     * @param string $description
     * @param string $openGraphUrl
     * @param string $canonical
     * @param array $openGraphProperties
     * @param string $twitterSite
     * 
     * @return void
     */
    function seo_generate(string $title, string $description, string $openGraphUrl, string $canonical, array $openGraphProperties = [], string $twitterSite): void
    {
        SEO::setTitle($title);
        SEO::setDescription($description);
        SEO::opengraph()->setUrl($openGraphUrl);
        SEO::setCanonical($canonical);
        foreach($openGraphProperties as $key => $value) {
            SEO::opengraph()->addProperty($key, $value);
        }
        SEO::twitter()->setSite($twitterSite);
    }
 }

