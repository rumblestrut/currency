<?php

/**
 * @file
 * Contains class \Drupal\currency\Tests\CurrencyFormControllerTest.
 */

namespace Drupal\currency\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests the currency UI.
 */
class CurrencyFormControllerTest extends WebTestBase {

  public static $modules = array('currency');

  /**
   * Implements DrupalTestCase::getInfo().
   */
  static function getInfo() {
    return array(
      'name' => 'Drupal\currency\CurrencyFormController',
      'group' => 'Currency',
    );
  }

  /**
   * Test Currency's UI.
   */
  function testUI() {
    $user = $this->drupalCreateUser(array('currency.currency.view', 'currency.currency.create', 'currency.currency.update', 'currency.currency.delete'));
    $this->drupalLogin($user);
    $path = 'admin/config/regional/currency/add';

    // Test valid values.
    $valid_values = array(
      'currency_code' => 'ABC',
      'currency_number' => '123',
      'label' => 'foo',
      'rounding_step' => '1',
      'sign[sign]' => CURRENCY_SIGN_FORM_ELEMENT_CUSTOM_VALUE,
      'sign[sign_custom]' => 'foobar',
      'subunits' => 2,
    );
    $this->drupalPost($path, $valid_values, t('Save'));
    $currency = entity_load('currency', 'ABC');
    $this->assertTrue($currency);

    // Test invalid values.
    $valid_values['currency_code'] = 'XYZ';
    $invalid_values =  array(
      'currency_code' => 'EUR',
      'currency_number' => 'abc',
      'rounding_step' => 'x',
      'subunits' => 'x',
    );
    foreach ($invalid_values as $name => $invalid_value) {
      $values = array(
        $name => $invalid_value,
      ) + $valid_values;
      $this->drupalPost($path, $values, t('Save'));
      // Test that the invalid element is the only element to be flagged.
      $this->assertFieldByXPath("//input[@name='$name' and contains(@class, 'error')]");
      $this->assertNoFieldByXPath("//input[not(@name='$name') and contains(@class, 'error')]");
    }

    // Edit and save an existing currency.
    $path = 'admin/config/regional/currency/list/ABC/edit';
    $this->drupalPost($path, array(), t('Save'));
    $this->assertUrl('admin/config/regional/currency');
  }
}