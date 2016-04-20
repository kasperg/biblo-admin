<?php

/**
 * @file
 * FlaggableContentTest class definition.
 */

namespace Drupal\dbcdk_community\Test\Content;

use DateTime;
use DBCDK\CommunityServices\Model\Comment;
use DBCDK\CommunityServices\Model\Flag;
use DBCDK\CommunityServices\Model\Post;
use Drupal\dbcdk_community\Content\FlaggableContent;
use Drupal\Tests\UnitTestCase;

/**
 * Tests flaggable content.
 *
 * @group dbcdk_community
 */
class FlaggableContentTest extends UnitTestCase {

  /**
   * Test that objects can be wrapped as flaggable and returned.
   */
  public function testObject() {
    $object = new \stdClass();
    $flaggable = new FlaggableContent($object);
    $this->assertEquals($object, $flaggable->getObject());
  }

  /**
   * Test expected string content.
   *
   * @param \Drupal\dbcdk_community\Content\FlaggableContent $flaggable
   *   The flaggable content to test.
   * @param string $title
   *   The expected title for the content.
   * @param string $content
   *   The expected content.
   *
   * @dataProvider contentProvider
   */
  public function testContent(FlaggableContent $flaggable, $title, $content) {
    if (!empty($title)) {
      $this->assertContains($title, $flaggable->getContent());
    }
    if (!empty($content)) {
      $this->assertContains($content, $flaggable->getContent());
    }

    // If we have both title and content then we should also have a separator.
    // Otherwise we should not.
    $separator = ':';
    if (!empty($title) && !empty($content)) {
      $this->assertContains($separator, $flaggable->getContent());
    }
    else {
      $this->assertNotContains($separator, $flaggable->getContent());
    }
  }

  /**
   * Provider of content variations.
   *
   * @return array
   *   An array of test variations.
   */
  public function contentProvider() {
    $title = 'Some title';
    $content = 'More content';
    $variations = [
      [
        new FlaggableContent((new Post())->setTitle($title)->setContent($content)),
        $title,
        $content,
      ], [
        new FlaggableContent((new Comment())->setContent($content)),
        NULL,
        $content,
      ],
    ];

    return $variations;
  }

  /**
   * Test that a single flag can be set and retrieved.
   */
  public function testAddFlag() {
    $flaggable = new FlaggableContent(new Comment());
    $flag = (new Flag())->setId(1)->setTimeFlagged(new DateTime('now'));
    $flaggable->addFlag($flag);
    $this->assertEquals([$flag], $flaggable->getFlags());
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

  /**
   * Test that unread flags can be retrieved.
   */
  public function testUnreadFlags() {
    $flaggable = new FlaggableContent(new Comment());

    $flag_unread = (new Flag())
      ->setId(1)
      ->setMarkedAsRead(FALSE);
    $flag_read = (new Flag())
      ->setId(2)
      ->setMarkedAsRead(TRUE);

    $flaggable->addFlag($flag_unread)->addFlag($flag_read);

    $this->assertCount(1, $flaggable->getUnreadFlags());
    $this->assertContains($flag_unread, $flaggable->getUnreadFlags());
  }

}
