<?php

/**
 *
 * class-pffy-idioms.php
 * PHP reference implementation of Pffy idioms
 *
 * @version 0.2
 * @license http://unlicense.org/ The Unlicense
 * @link https://github.com/pffy/php-idioms
 * @author The Pffy Authors
 *
 * SYSTEM REQUIREMENTS: PHP 5.2.0 or better
 *
 */
class Idioms {

  const SINGLE_SPACE = " ";
  const TWO_SPACES = "  ";
  const EMPTY_STRING = "";

  const CRLF = PHP_EOL;
  const LF = "\n";
  const CR = "\r";


  // GENERAL idioms

  // returns string reducing number of spaces between words to exactly one
  public static function vacuum($str) {
    return trim(preg_replace('/[^\S\n]{2,}/u', self::SINGLE_SPACE, $str));
  }

  // returns string with no spaces between words
  public static function nospaces($str) {
    return trim(preg_replace('/[^\S\n]{1,}/u', self::EMPTY_STRING, $str));
  }

  // returns string with all whitespace removed
  public static function airtight($str) {
    return trim(preg_replace('/\s{1,}/u', self::EMPTY_STRING, $str));
  }

  // returns string with no punctuation
  public static function nopunct($str) {
    return trim(preg_replace('/[[:punct:]]/u', self::EMPTY_STRING, $str));
  }

  // returns string with no digits
  public static function nodigits($str) {
    return trim(preg_replace('/[[:digit:]]/u', self::EMPTY_STRING, $str));
  }

  // returns string with only digits 0 to 9
  public static function digitsonly($str) {
    return trim(preg_replace('/[^[:digit:]]/u', self::EMPTY_STRING, $str));
  }

  // returns string with only alpha characters
  public static function alphaonly($str) {
    return trim(preg_replace('/[^[:alpha:]]/u', self::EMPTY_STRING, $str));
  }

  // returns string with only alphanumeric characters
  public static function alphanumonly($str) {
    return trim(preg_replace('/[^[:alnum:]]/u', self::EMPTY_STRING, $str));
  }

  // returns string with only ascii characters
  public static function asciionly($str) {
    return trim(preg_replace('/[^[:ascii:]]/u', self::EMPTY_STRING, $str));
  }

  // returns string after removing ascii characters
  public static function noascii($str) {
    return trim(preg_replace('/[[:ascii:]]/u', self::EMPTY_STRING, $str));
  }

  // returns string without CRLF
  public static function noCRLF($str) {
     return str_replace(self::CRLF, self::EMPTY_STRING, $str);
  }

  // returns string without carriage return
  public static function nocr($str) {
    return str_replace(self::CR, self::EMPTY_STRING, $str);
  }

  // returns string without new line (linefeed)
  public static function nolf($str) {
    return str_replace(self::LF, self::EMPTY_STRING, $str);
  }



  // PINYIN idioms

  // returns a string with no spaces
  public static function pmash($str) {
    return self::nospaces(strtolower($str));
  }

  // returns strings with no spaces or tone numbers (digits)
  public static function pbash($str) {
    return self::nospaces(self::nodigits(strtolower($str)));
  }

  // returns string of unique alpha characters (if available), ordered by alpha
  public static function phash($str) {

    $str = strtolower($str);
    $str = self::alphaonly($str);
    $arr = array_unique(str_split($str));
    sort($arr);

    return trim(implode($arr));
  }

  // returns a string with the initial letters of each word
  public static function psmash($str) {

    $psmash = self::EMPTY_STRING;

    $str = strtolower($str);

    $str = self::nopunct(self::nodigits($str));
    $str = self::vacuum($str);

    if(!strpos($str, self::SINGLE_SPACE)) {
      return $str;
    }

    $arr = explode(self::SINGLE_SPACE, $str);

    foreach($arr as $a) {
      $psmash .= substr($a, 0, 1);
    }

    return $psmash;
  }

  // returns a pinyin string with the umlaut-u normalized to uu
  public static function pumlaut($str) {
    return str_replace(explode(",", "u:,ǚ,ǘ,ǜ,ǖ,ü,v"), "uu", $str);
  }

  // returns a numbered pinyin strings into smaller units
  // NOTE: low-cost, low-accuracy atomization
  public static function atomize($str) {
    return self::vacuum(preg_replace('/(\w{1,6}[1-5]{1})/u', '${1} ', $str));
  }


  // MULTIBYTE IDIOMS

  // returns a string exactly one space between multibyte characters
  public static function aerate($str) {
    $json = str_replace("\u", " \u", json_encode(array($str)));
    return self::vacuum(json_decode($json)[0]);
  }


  // DEFINITION idioms

  // ** EXPERIMENTAL **
  // returns string that indexes definitions
  public static function dmash($str) {
    return strtolower(self::nodigits(self::nospaces(self::nopunct(
      iconv('Windows-1252', 'ASCII//TRANSLIT//IGNORE',
          self::asciionly($str))))));
  }
}


