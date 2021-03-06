<?php

/**
 * @file
 * Contains \Drupal\Tests\currency\Unit\Controller\CurrencyImportFormUnitTest.
 */

namespace Drupal\Tests\currency\Unit\Controller {

  use Drupal\Core\Url;
  use Drupal\currency\Controller\CurrencyImportForm;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @coversDefaultClass \Drupal\currency\Controller\CurrencyImportForm
 *
 * @group Currency
 */
class CurrencyImportFormUnitTest extends UnitTestCase {

  /**
   * The controller under test.
   *
   * @var \Drupal\currency\Controller\CurrencyImportForm
   */
  protected $controller;

  /**
   * The config importer.
   *
   * @var \Drupal\currency\ConfigImporterInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $configImporter;

  /**
   * The form helper.
   *
   * @var \Drupal\currency\FormHelperInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $formHelper;

  /**
   * The string translation service.
   *
   * @var \Drupal\Core\StringTranslation\TranslationInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $stringTranslation;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->configImporter = $this->getMock('\Drupal\currency\ConfigImporterInterface');

    $this->formHelper = $this->getMock('\Drupal\currency\FormHelperInterface');

    $this->stringTranslation = $this->getStringTranslationStub();

    $this->controller = new CurrencyImportForm($this->stringTranslation, $this->configImporter, $this->formHelper);
  }

  /**
   * @covers ::create
   * @covers ::__construct
   */
  function testCreate() {
    $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerInterface');
    $map = [
      ['currency.config_importer', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->configImporter],
      ['currency.form_helper', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->formHelper],
      ['string_translation', ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $this->stringTranslation],
    ];
    $container->expects($this->any())
      ->method('get')
      ->will($this->returnValueMap($map));

    $form = CurrencyImportForm::create($container);
    $this->assertInstanceOf('\Drupal\currency\Controller\CurrencyImportForm', $form);
  }

  /**
   * @covers ::getFormId
   */
  public function testGetFormId() {
    $this->assertInternalType('string', $this->controller->getFormId());
  }

  /**
   * @covers ::buildForm
   */
  public function testBuildFormWithoutImportableCurrencies() {
    $this->configImporter->expects($this->once())
      ->method('getImportableCurrencies')
      ->willReturn([]);

    $form_state = $this->getMock('\Drupal\Core\Form\FormStateInterface');

    $form = $this->controller->buildForm([], $form_state);

    // There should be one element and it must not be the currency selector or a
    // group of actions.
    $this->assertCount(1, $form);
    $this->assertArrayNotHasKey('actions', $form);
    $this->assertArrayNotHasKey('currency_code', $form);
  }

  /**
   * @covers ::buildForm
   */
  public function testBuildFormWithImportableCurrencies() {
    $currency_a = $this->getMock('\Drupal\currency\Entity\CurrencyInterface');
    $currency_b = $this->getMock('\Drupal\currency\Entity\CurrencyInterface');

    $this->configImporter->expects($this->once())
      ->method('getImportableCurrencies')
      ->willReturn([$currency_a, $currency_b]);

    $form_state = $this->getMock('\Drupal\Core\Form\FormStateInterface');

    $form = $this->controller->buildForm([], $form_state);

    // There should a currency selector and a group of actions.
    $this->assertArrayHasKey('currency_code', $form);
    $this->assertArrayHasKey('actions', $form);
    $this->assertArrayHasKey('import', $form['actions']);
    $this->assertArrayHasKey('import_edit', $form['actions']);
  }

  /**
   * @covers ::submitForm
   */
  public function testSubmitFormImport() {
    $currency_code = $this->randomMachineName();

    $currency = $this->getMock('\Drupal\currency\Entity\CurrencyInterface');

    $this->configImporter->expects($this->once())
      ->method('importCurrency')
      ->with($currency_code)
      ->willReturn($currency);

    $form = [
      'actions' => [
        'import' => [
          '#name' => 'import',
        ],
        'import_edit' => [
          '#name' => 'import_edit',
        ],
      ],
    ];
    $form_state = $this->getMock('\Drupal\Core\Form\FormStateInterface');
    $form_state->expects($this->atLeastOnce())
      ->method('getValues')
      ->willReturn([
        'currency_code' => $currency_code,
      ]);
    $form_state->expects($this->atLeastOnce())
      ->method('getTriggeringElement')
      ->willReturn($form['actions']['import']);
    $form_state->expects($this->atLeastOnce())
      ->method('setRedirectUrl');

    $this->controller->submitForm($form, $form_state);
  }

  /**
   * @covers ::submitForm
   */
  public function testSubmitFormImportEdit() {
    $currency_code = $this->randomMachineName();

    $url = new Url($this->randomMachineName());

    $currency = $this->getMock('\Drupal\currency\Entity\CurrencyInterface');
    $currency->expects($this->atLeastOnce())
      ->method('urlInfo')
      ->with('edit-form')
      ->willReturn($url);

    $this->configImporter->expects($this->once())
      ->method('importCurrency')
      ->with($currency_code)
      ->willReturn($currency);

    $form = [
      'actions' => [
        'import' => [
          '#name' => 'import',
        ],
        'import_edit' => [
          '#name' => 'import_edit',
        ],
      ],
    ];
    $form_state = $this->getMock('\Drupal\Core\Form\FormStateInterface');
    $form_state->expects($this->atLeastOnce())
      ->method('getValues')
      ->willReturn([
        'currency_code' => $currency_code,
      ]);
    $form_state->expects($this->atLeastOnce())
      ->method('getTriggeringElement')
      ->willReturn($form['actions']['import_edit']);
    $form_state->expects($this->atLeastOnce())
      ->method('setRedirectUrl');

    $this->controller->submitForm($form, $form_state);
  }

}

}

namespace {

  if (!function_exists('drupal_set_message')) {
    function drupal_set_message() {}
  }

}
