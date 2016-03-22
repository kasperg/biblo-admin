<?php
/**
 * @file
 * Class definition for PropertyUrlGeneratorTest.
 */

namespace Drupal\dbcdk_community\Test\Url;

use Drupal\dbcdk_community\Url\PropertyUrlGenerator;

/**
 * Class ObjectUrlGeneratorTest.
 *
 * @group dbcdk_community
 */
class PropertyUrlGeneratorTest extends \PHPUnit_Framework_TestCase {

  /**
   * Test property based generation.
   */
  public function testGenerate() {
    $generator = new PropertyUrlGenerator('http://biblo.dk/post/[id]');
    $url = $generator->generate(new MockUrlObjectClass(1));
    $this->assertEquals('http://biblo.dk/post/1', $url);
  }

}

/**
 * Mock class with a simple getter that always return the same.
 */
class MockUrlObjectClass {

  /**
   * The return value to use.
   *
   * @var $returnValue
   */
  protected $returnValue;

  /**
   * MockUrlObjectClass constructor.
   *
   * @param mixed $return_value
   *   The return value for accessors.
   */
  public function __construct($return_value) {
    $this->returnValue = $return_value;
  }

  /**
   * The return value for the object.
   *
   * @return mixed
   *   The return value for the object.
   */
  public function getId() {
    return $this->returnValue;
  }

}
