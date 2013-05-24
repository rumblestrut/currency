<?php

/**
 * @file
 * Contains class \Drupal\currency\Tests\Plugin\Core\Entity\CurrencyCRUD.
 */

namespace Drupal\currency\Tests\Plugin\Core\Entity;

use Drupal\currency\Plugin\Core\Entity\Currency;
use Drupal\currency\Usage;
use Drupal\simpletest\WebTestBase;

/**
 * Tests \Drupal\currency\Plugin\Core\Entity\Currency.
 */
class CurrencyCRUD extends WebTestBase {

  public static $modules = array('currency');

  /**
   * Overrides parent::getInfo().
   */
  public static function getInfo() {
    return array(
      'name' => 'Drupal\currency\Plugin\Core\Entity\Currency entity CRUD',
      'group' => 'Currency',
    );
  }

  /**
   * Test CRUD functionality.
   */
  function testCRUD() {
    $currency_code = 'ABC';

    // Test that no currency with this currency code exists yet.
    $config = config('currency.currency.' . $currency_code);
    $this->assertIdentical($config->get('currencyNumber'), NULL);

    // Test creating a custom currency.
    $currency = entity_create('currency', array());
    $this->assertTrue($currency instanceof Currency);
    $this->assertTrue($currency->uuid);

    // Test saving a custom currency.
    $currency->set('currencyCode', $currency_code);
    $currency->set('currencyNumber', '123');
    $currency->save();
    $config = config('currency.currency.' . $currency_code);
    $this->assertEqual($config->get('currencyNumber'), '123');

    // Test loading a custom currency.
    $currency_loaded = entity_load('currency', $currency_code);
    $this->assertEqual($currency->get('currencyNumber'), $currency_loaded->get('currencyNumber'));

    // Test loading a default currency.
    $currency_loaded = entity_load('currency', 'EUR');
    $this->assertTrue($currency_loaded instanceof Currency);
    foreach ($currency_loaded->usage as $usage) {
      $this->assertTrue($usage instanceof Usage);
    }

    // Test deleting a custom currency.
    $currency->delete();
    $config = config('currency.currency.' . $currency_code);
    $this->assertIdentical($config->get('currencyNumber'), NULL);
  }
}