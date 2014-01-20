<?php

/**
 * @file
 * Contains \Drupal\currency\Test\Hook\ElementInfoUnitTest.
 */

namespace Drupal\currency\Tests\Hook;

use Drupal\Core\Render\Element;
use Drupal\currency\Hook\ElementInfo;
use Drupal\Tests\UnitTestCase;

/**
 * Tests \Drupal\currency\Hook\ElementInfo.
 */
class ElementInfoUnitTest extends UnitTestCase {

  /**
   * The service under test.
   *
   * @var \Drupal\currency\Hook\ElementInfo.
   */
  protected $service;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'description' => '',
      'name' => '\Drupal\currency\Hook\ElementInfo unit test',
      'group' => 'Currency',
    );
  }

  /**
   * {@inheritdoc
   */
  public function setUp() {
    $this->service = new ElementInfo();
  }

  /**
   * @covers \Drupal\currency\Hook\ElementInfo::invoke()
   */
  public function testInvoke() {
    $elements = $this->service->invoke();
    $this->assertInternalType('array', $elements);
    foreach ($elements as $element) {
      $this->assertInternalType('array', $element);
      $this->assertSame(0, count(Element::children($element)));
    }
  }
}