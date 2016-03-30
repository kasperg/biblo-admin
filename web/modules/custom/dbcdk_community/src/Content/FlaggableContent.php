<?php

/**
 * @file
 * FlaggableContent class definition.
 */

namespace Drupal\dbcdk_community\Content;

use DBCDK\CommunityServices\Model\Flag;

/**
 * A piece of community content with 0 or more flags attached.
 *
 * Users can flag content to bring it to attention of the moderators
 * of the site. Objects of this class represent pieces of such content
 * with attached flags.
 */
class FlaggableContent {

  /**
   * The object representing the piece of content which can be flagged.
   *
   * @var object $object
   */
  protected $object;

  /**
   * The flags attached to the content.
   *
   * @var Flag[] $flags
   */
  protected $flags = [];

  /**
   * FlaggableContent constructor.
   *
   * @param object $object
   *   The flaggable object.
   */
  public function __construct($object) {
    $this->object = $object;
  }

  /**
   * Get the content object that was flagged.
   *
   * @return object
   *   The flagged object.
   */
  public function getObject() {
    return $this->object;
  }

  /**
   * Function to check equality of two FlaggableContent objects.
   *
   * @param FlaggableContent $other
   *   The object to compare against.
   *
   * @return bool
   *   Whether the two objects are considered equal.
   */
  public function equals(FlaggableContent $other) {
    return $this->object == $other->object;
  }

  /**
   * Attach a flag to this piece of content.
   *
   * @param Flag $flag
   *   The flag to attach.
   *
   * @return FlaggableContent
   *   Return the current object.
   */
  public function addFlag(Flag $flag) {
    $this->flags = array_unique(array_merge($this->flags, [$flag]));
    usort($this->flags, function(Flag $a, Flag $b) {
      $a_timestamp = NULL;
      $b_timestamp = NULL;
      if (!empty($a->getTimeFlagged())) {
        $a_timestamp = $a->getTimeFlagged()->getTimestamp();
      }
      if (!empty($b->getTimeFlagged())) {
        $b_timestamp = $b->getTimeFlagged()->getTimestamp();
      }
      return $a_timestamp - $b_timestamp;
    });
    return $this;
  }

  /**
   * Attach multiple flags to this piece of content.
   *
   * @param Flag[] $flags
   *   The flags to attach.
   *
   * @return FlaggableContent
   *   Return the current object.
   */
  public function addFlags(array $flags) {
    array_walk($flags, function(Flag $flag) {
      $this->addFlag($flag);
    });
    return $this;
  }

  /**
   * Get all flags attached to this piece of content.
   *
   * @return Flag[]
   *   All attached flags.
   */
  public function getFlags() {
    return $this->flags;
  }

  /**
   * Get the content for the piece of content.
   *
   * @return string
   *   The actual flagged content.
   */
  public function getContent() {
    $content = '';
    if (method_exists($this->object, 'getTitle')) {
      $content .= $this->object->getTitle() . ': ';
    }
    $content .= $this->object->getContent();
    return $content;
  }

  /**
   * Get the id of the profile that created this piece of content.
   *
   * @see DBCDK\CommunityServices\Api\ProfileApi
   *
   * @return int
   *   The creator profile id.
   */
  public function getCreatorId() {
    $creator_id = NULL;
    // Creator id is stored in different attributes in the different types of
    // content classes supported. We use reflection to determine which.
    $creator_getter = [
      'getCommentownerid',
      'getPostownerid',
    ];
    foreach ($creator_getter as $getter) {
      if (method_exists($this->object, $getter)) {
        $creator_id = $this->object->{$getter}();
        break;
      }
    }
    return $creator_id;
  }

  /**
   * Get the time when the piece of content was created.
   *
   * @return \DateTime
   *   The time when the object was created.
   */
  public function getTimeCreated() {
    return $this->object->getTimeCreated();
  }

  /**
   * Get the most recent flag attached to this piece of content.
   *
   * Most recent is the time where the flag was created.
   *
   * @return Flag
   *   The most recent flag.
   */
  public function getLatestFlag() {
    $flags = array_reverse($this->flags);
    return array_shift($flags);
  }

  /**
   * Get all unread flags attached to this piece of content.
   *
   * @return Flag[]
   *   All unread flags.
   */
  public function getUnreadFlags() {
    return array_filter($this->flags, function(Flag $flag) {
      return !$flag->getMarkedAsRead();
    });
  }

}
