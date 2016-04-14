<?php
/**
 * @file
 * Definition of BlockTestBase.
 */

namespace Drupal\Tests\dbcdk_community\Unit\Plugin\Block;

use Drupal\Tests\UnitTestCase;

/**
 * Base class for tests dealing with community service blocks.
 */
class BlockTestBase extends UnitTestCase {

  /* @var \PHPUnit_Framework_MockObject_MockObject */
  protected $urlGenerator;

  /* @var \PHPUnit_Framework_MockObject_MockObject */

  protected $logger;
  /* @var \PHPUnit_Framework_MockObject_MockObject */

  protected $profileApi;

  /* @var \PHPUnit_Framework_MockObject_MockObject */
  protected $translation;

  /* @var \PHPUnit_Framework_MockObject_MockObject */
  protected $dateFormatter;

  /* @var \PHPUnit_Framework_MockObject_MockObject */
  protected $flaggableContentRepository;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $this->logger = $this->getMock('\Psr\Log\LoggerInterface');

    $this->flaggableContentRepository = $this->getMockBuilder(
      '\Drupal\dbcdk_community\Content\FlaggableContentRepository'
    )->disableOriginalConstructor()->getMock();

    $this->profileApi = $this->getMock(
      '\DBCDK\CommunityServices\Api\ProfileApi'
    );

    $this->urlGenerator = $this->getMock(
      '\Drupal\dbcdk_community\Url\UrlGeneratorInterface'
    );

    $this->translation = $this->getMock(
      '\Drupal\Core\StringTranslation\TranslationInterface'
    );
    // Make the translation stub return the source string.
    $this->translation->method('translate')->willReturnArgument(0);

    $this->dateFormatter = $this->getMockBuilder(
      'Drupal\Core\Datetime\DateFormatter'
    )->disableOriginalConstructor()->getMock();
  }

  /**
   * Assert that an entry in a details table has the expected value.
   *
   * Rows in a details table have two columns:
   * - Name/label
   * - Value.
   *
   * @param mixed $expected
   *   The expected value for the row.
   * @param array $table
   *   The details table.
   * @param string|int $row_id
   *   The id of the row to check.
   * @param string|NULL $message
   *   The message to display if the assertion fails.
   */
  protected function assertDetailsRowEquals($expected, array $table, $row_id, $message = NULL) {
    $this->assertEquals($expected, $table['#rows'][$row_id][1], $message);
  }

  /**
   * Assert that an entry in a details table is not empty.
   *
   * @param array $table
   *   The details table.
   * @param string|int $row_id
   *   The id of the row to check.
   * @param string|null $message
   *   The message to display if the assertion fails.
   */
  protected function assertDetailsRowNotEmpty(array $table, $row_id, $message = NULL) {
    $this->assertNotEmpty($table['#rows'][$row_id][1], $message);
  }

  /**
   * Assert that an entry in a details table is a link to a specific url.
   *
   * @param string $expected_url
   *   The url the row should link to.
   * @param array $table
   *   The details table.
   * @param string|int $row_id
   *   The id of the row to check.
   * @param string|null $message
   *   The message to display if the assertion fails.
   */
  protected function assertDetailsRowLinkUrl($expected_url, array $table, $row_id, $message = NULL) {
    $this->assertInstanceOf('\Drupal\Core\Link', $table['#rows'][$row_id][1]);
    /* @var \Drupal\Core\Link $actual_link */
    $actual_link = $table['#rows'][$row_id][1];
    $this->assertEquals($expected_url, $actual_link->getUrl()->getUri(), $message);
  }

  /**
   * Assert that an entry in a details table is a link with a specific text.
   *
   * @param string $expected
   *   The expected text for the link.
   * @param array $table
   *   The details table.
   * @param string|int $row_id
   *   The id of the row to check.
   * @param string|null $message
   *   The message to display if the assertion fails.
   */
  protected function assertDetailsRowLinkText($expected, array $table, $row_id, $message = NULL) {
    $this->assertInstanceOf('\Drupal\Core\Link', $table['#rows'][$row_id][1]);
    /* @var \Drupal\Core\Link $actual_link */
    $actual_link = $table['#rows'][$row_id][1];
    $this->assertEquals($actual_link->getText(), $expected, $message);
  }

  /**
   * Assert that a details table contains no details.
   *
   * @param array $table
   *   The details table.
   * @param string|null $message
   *   The message to display if the assertion fails.
   */
  protected function assertNoDetails(array $table, $message = NULL) {
    $this->assertEmpty($table['#rows'], $message);
  }

}
