<?php

/**
 * @file
 * FlaggableContentTest class definition.
 */

namespace Drupal\dbcdk_community\Test\Content;

use DateTime;
use DBCDK\CommunityServices\Model\Comment;
use DBCDK\CommunityServices\Model\Flag;
use Drupal\dbcdk_community\Content\FlaggableContent;
use Drupal\Tests\UnitTestCase;

/**
 * Tests flaggable content
 *
 * @group dbcdk_community
 */
class FlaggableContentTest extends UnitTestCase {

  /**
   * Test that a single flag can be set and retrieved.
   */
  public function testAddFlag() {
    $flaggable = new FlaggableContent(new Comment());
    $flag = (new Flag())->setId(1)->setTimeFlagged(new DateTime('now'));
    $flaggable->addFlag($flag);
    $this->assertEquals([ $flag ], $flaggable->getFlags());
  }

  /**
   * Test that a multiple flags can be set and retrieved.
   */
  public function testMultipleFlags() {
    $flaggable = new FlaggableContent(new Comment());
    // It is important for the equals to work that these flags are in
    // chronological order as they are sorted internally when added to the
    // FlaggableContent object.
    $flags = [
      (new Flag())->setId(1)->setTimeFlagged(new DateTime('yesterday')),
      (new Flag())->setId(1)->setTimeFlagged(new DateTime('now')),
    ];
    $flaggable->addFlags($flags);
    $this->assertEquals($flags, $flaggable->getFlags());
  }


  /**
   * Test that the latest flag is retrieved correctly.
   */
  public function testLatestFlag() {
    $flaggable = new FlaggableContent(new Comment());

    $flag_now = (new Flag())
        ->setId(1)
        ->setTimeFlagged(new \DateTime('now'));
    $flag_yesterday = (new Flag())
        ->setId(2)
        ->setTimeFlagged(new \DateTime('yesterday'));

    $flaggable->addFlag($flag_now)->addFlag($flag_yesterday);

    $this->assertEquals($flag_now, $flaggable->getLatestFlag());
  }

}
