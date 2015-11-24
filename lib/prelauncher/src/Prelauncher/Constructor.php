<?php

namespace Prelauncher;

use Prelauncher\Resource;
use \RESTful\URISpec;


class Constructor extends Resource
{
    protected static $_uri_spec = null;

    public static function init()
    {
        self::$_uri_spec = new URISpec('constructor', 'id', '/');
        self::$_registry->add(get_called_class());
    }


    public function metaTags(){
      $uri = "/constructors/meta";
      
      $response = self::getClient()->get($uri);
     
      $structure = $response->body;
      
      return $structure;
    }
    
    public function referPage()
    {
      $uri = "/constructors/refer";
      
      $response = self::getClient()->get($uri);
     
      $structure = $response->body;
      
      return $structure;

    }
      
    public function landingPage()
    {
      $uri = "/constructors/landing";
      
      $response = self::getClient()->get($uri);
     
      
      $structure = $response->body;

      
      return $structure;

    }
}
