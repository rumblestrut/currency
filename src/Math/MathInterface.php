<?php

/**
 * @file Contains \Drupal\currency\Math\MathInterface.
 */

namespace Drupal\currency\Math;

/**
 * Defines mathematical functions.
 */
interface MathInterface {

  /**
   * Adds two numbers.
   *
   * @param int|float|string $number_a
   * @param int|float|string $number_b
   *
   * @return int|float|string
   */
  public function add($number_a, $number_b);

  /**
   * Subtracts one number from another.
   *
   * @param int|float|string $number_a
   *   The number to subtract from.
   * @param int|float|string $number_b
   *   The number to subtract.
   *
   * @return int|float|string
   */
  public function subtract($number_a, $number_b);

  /**
   * Multiplies two numbers.
   *
   * @param int|float|string $number_a
   * @param int|float|string $number_b
   *
   * @return int|float|string
   */
  public function multiply($number_a, $number_b);

  /**
   * Divdes one number by another.
   *
   * @param int|float|string $number_a
   *   The number to divide.
   * @param int|float|string $number_b
   *   The number to divide by.
   *
   * @return int|float|string
   */
  public function divide($number_a, $number_b);

  /**
   * Divides one number by another.
   *
   * @param int|float|string $number
   *   The number to round.
   * @param int|float|string $rounding_step
   *   The step to round by. Example: when the step is 0.25, values will be
   *   rounded to the nearest quarter.
   *
   * @return int|float|string
   */
  public function round($number, $rounding_step);

  /**
   * Compares two numbers to each other.
   *
   * @param int|float|string $number_a
   * @param int|float|string $number_b
   *
   * @return int
   *   0 if both numbers are identical, 1 if $number_a is larger than $number_b
   *   and -1 if $number_b is larger than $number_a.
   */
  public function compare($number_a, $number_b);

}
