<?php

namespace Drupal\custom_parser;

use Drupal\file\Entity\File;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Class Parser.
 *
 * @package Drupal\custom_parser
 */
class Parser {

    protected static $siteUrl;

    /**
     * Parsed array of items.
     *
     * @var array
     */
    protected static $items = [1 => 'http://bitcoin-zone.ru/feed/'];

  /**
   * {@inheritdoc}
   */
  public function __construct() {

  }

  public static function parseSite(){

      $config = \Drupal::config('custom_parser.parser.form');
      self::$siteUrl = self::$items[$config->get('site_select')];

      if(empty(self::$siteUrl))
      {
        return false;
      }

      $feed = implode(file(self::$siteUrl));
      $xml = simplexml_load_string($feed);
      $json = json_encode($xml);
      $array = json_decode($json,TRUE);

      if (gettype($array['channel']['item']) == 'array'){
          return $array['channel']['item'];
      }
      return false;

  }


}
