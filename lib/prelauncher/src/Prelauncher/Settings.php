<?php

namespace Prelauncher;

class Settings
{
    const VERSION = '1.0.1';

    public static $url_root = 'http://api.prelauncher.info/companies/',
                  $api_key = null,
                  $agent = 'prelauncher-php',
                  $version = Settings::VERSION,
                  $accept = 'application/json',
                  $company_id = null;

    /**
     * Configure all settings.
     *
     * @param string url_root The root (schema://hostname[:port]) to use when constructing api URLs.
     * @param string api_key The api key secret to use for authenticating when talking to the api. If null then api usage is limited to uauthenticated endpoints.
     */
    public static function configure($company_id, $api_key)
    {
      if (is_null(self::$company_id)){
        self::$company_id = $company_id;
        self::$url_root = self::$url_root . self::$company_id;
      }
      self::$api_key = $api_key;	      
    }
}
