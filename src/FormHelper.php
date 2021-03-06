<?php

/**
 * @file
 * Definition of Drupal\currency\FormHelper.
 */

namespace Drupal\currency;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Provides form helpers.
 */
class FormHelper implements FormHelperInterface {

  use StringTranslationTrait;

  /**
   * The currency storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $currencyStorage;

  /**
   * The currency locale storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $currencyLocaleStorage;

  /**
   * Constructs a new class instance.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   */
  public function __construct(TranslationInterface $string_translation, EntityManagerInterface $entity_manager) {
    $this->currencyStorage = $entity_manager->getStorage('currency');
    $this->currencyLocaleStorage = $entity_manager->getStorage('currency_locale');
    $this->stringTranslation = $string_translation;
  }


  /**
   * {@inheritdoc}
   */
  public function getCurrencyOptions(array $currencies = NULL) {
    $options = array();
    /** @var \Drupal\currency\Entity\CurrencyInterface[] $currencies */
    if (is_null($currencies)) {
      $currencies = $this->currencyStorage->loadMultiple();
    }
    foreach ($currencies as $currency) {
      // Do not show disabled currencies.
      if ($currency->status()) {
        $options[$currency->id()] = $this->t('@currency_title (@currency_code)', array(
          '@currency_title' => $currency->label(),
          '@currency_code' => $currency->id(),
        ));
      }
    }
    natcasesort($options);

    return $options;
  }


  /**
   * {@inheritdoc}
   */
  public function getCurrencyLocaleOptions(array $currency_locales = NULL) {
    $options = array();
    /** @var \Drupal\currency\Entity\CurrencyLocaleInterface[] $currency_locales */
    if (is_null($currency_locales)) {
      $currency_locales = $this->currencyLocaleStorage->loadMultiple();
    }
    foreach ($currency_locales as $currency_locale) {
      // Do not show disabled currencies.
      if ($currency_locale->status()) {
        $options[$currency_locale->id()] = $currency_locale->label();
      }
    }
    natcasesort($options);

    return $options;
  }

}
