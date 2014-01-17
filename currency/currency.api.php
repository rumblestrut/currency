<?php

/**
 * @file Contains Currency hook documentation.
 */

/**
 * Alters exchange rate provider plugins.
 *
 * @param array $definitions
 *   Keys are plugin IDs. Values are plugin definitions.
 */
function hook_currency_exchange_rate_provider_alter(array &$definitions) {
  // Remove an exchange rate provider plugin.
  unset($definitions['foo_plugin_id']);

  // Replace an exchange rate provider plugin with another.
  $definitions['foo_plugin_id']['class'] = 'Drupal\foo\FooExchangeRateProvider';
}