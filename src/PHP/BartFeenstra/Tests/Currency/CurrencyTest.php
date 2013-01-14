<?php

/**
 * @file
 * Contains class \BartFeenstra\Tests\Currency\CurrencyTest.
 */

namespace BartFeenstra\Tests\Currency;

use BartFeenstra\Currency\Currency;
use BartFeenstra\Currency\Usage;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

/**
 * Tests \BartFeenstra\Currency\Currency
 */
class CurrencyTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test listing .
   */
  function testResourceList() {
    $list = Currency::resourceListAll();
    foreach ($list as $iso_4217_code) {
      $this->assertSame(strlen($iso_4217_code), 3, 'Currency::getList() returns an array with three-letter strings (ISO 4217 codes).');
    }
  }

  /**
   * Returns YAML for a Currency object.
   *
   * @return string
   */
  function yaml() {
    return <<<'EOD'
alternativeSigns: {  }
conversionRates: {  }
ISO4217Code: EUR
minorUnit: 2
ISO4217Number: '978'
sign: ¤
title: Euro
usage:
    - { ISO8601From: '2003-02-04', ISO8601To: '2006-06-03', ISO3166Code: CS }
EOD;
  }

  /**
   * Returns a Currency object.
   *
   * @return Currency
   */
  function currency() {
    $usage = new Usage();
    $usage->ISO8601From = '2003-02-04';
    $usage->ISO8601To = '2006-06-03';
    $usage->ISO3166Code = 'CS';
    $currency = new Currency();
    $currency->ISO4217Code = 'EUR';
    $currency->minorUnit = 2;
    $currency->ISO4217Number = '978';
    $currency->sign = '¤';
    $currency->title = 'Euro';
    $currency->usage = array($usage);

    return $currency;
  }

  /**
   * Test YAML parsing .
   */
  function testResourceParse() {
    $yaml = $this->yaml();
    $currency_parsed = Currency::resourceParse($yaml);
    $this->assertInstanceOf('BartFeenstra\Currency\Currency', $currency_parsed, 'Currency::parse() parses YAML code to a Currency object.');
    $this->assertInstanceOf('BartFeenstra\Currency\Usage', $currency_parsed->usage[0], 'Currency::parse() parses YAML code to a Usage object.');
    $currency = $this->currency();
    $this->assertSame(get_object_vars($currency->usage[0]), get_object_vars($currency_parsed->usage[0]), 'Currency::parse() parses YAML code to an identical Usage object.');
    unset($currency->usage);
    unset($currency_parsed->usage);
    $this->assertSame(get_object_vars($currency), get_object_vars($currency_parsed), 'Currency::parse() parses YAML code to an identical currency object.');
  }

  /**
   * Tests loading a single currency.
   */
  function testResourceLoad() {
    $currency = Currency::resourceLoad('EUR');
    $this->assertInstanceOf('BartFeenstra\Currency\Currency', $currency, 'Currency::load() loads a single currency from file.');
  }

  /**
   * Tests loading all currencies.
   *
   * @depends testResourceLoad
   */
  function testResourceLoadAll() {
    Currency::resourceLoadAll();
  }
}