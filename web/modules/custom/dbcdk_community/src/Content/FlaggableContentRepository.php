<?php

/**
 * @file
 * FlaggableContentRepository class definition.
 */

namespace Drupal\dbcdk_community\Content;

use DBCDK\CommunityServices\Api\FlagApi;
use DBCDK\CommunityServices\ApiException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * A repository for retrieving content with attached flags.
 *
 * Multiple types of content can be flagged. The purpose of this class is
 * to provide a simple way to retrieve all such types instead of using
 * one class per type.
 */
class FlaggableContentRepository {

  /**
   * The API to use when retrieving flagged content.
   *
   * @var FlagApi $flagApi
   */
  protected $flagApi;

  /**
   * The logger to use.
   *
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * FlaggableContentRepository constructor.
   *
   * @param FlagApi $flag_api
   *   The FlagApi class to use when retrieving content.
   */
  public function __construct(FlagApi $flag_api) {
    $this->flagApi = $flag_api;
    $this->logger = new NullLogger();
  }

  /**
   * Set the logger to use.
   *
   * @param LoggerInterface $logger
   *   The logger.
   */
  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  /**
   * Get all content which has unread flags.
   *
   * The content will be sorted by reverse chronological order based on
   * time of the latest flag ie. the content which has most recently been
   * flagged will be returned first.
   *
   * @return FlaggableContent[]
   *   All content with unread flags.
   *
   * @throws ApiException
   *   Thrown if we are not able to retrieve any content from the API.
   */
  public function getContentWithUnreadFlags() {
    /* @var FlaggableContent[] $content */
    $content = [];

    $filter = json_encode(['where' => ['markedAsRead' => FALSE]]);
    $flags = (array) $this->flagApi->flagFind($filter);

    // Loop over *all* flags to find which content it is attached to.
    // This is not very efficient but our generated APIs do not offer a
    // better way to do this. In general the number of unread flags should
    // not accumulate over time so it should be manageable.
    foreach ($flags as $flag) {
      // We do not know which type of content our flag is attached to and
      // in theory it could be attached to multiple. We get all of them and
      // convert them to FlaggableContent instances.
      try {
        $posts = (array) $this->flagApi->flagPrototypeGetPosts($flag->getId());
        $comments = (array) $this->flagApi->flagPrototypeGetComments($flag->getId());
      }
      catch (ApiException $e) {
        // If any errors occur here we just log it. It may be caused by the
        // individual flag so we try to retrieve information for the
        // remaining flags as well.
        $this->logger->warning($e);

        $posts = [];
        $comments = [];
      }
      $flagged_content = array_map(function($content) use ($flag) {
        return (new FlaggableContent($content))->addFlag($flag);
      }, array_merge($posts, $comments));
      $content = array_merge($content, $flagged_content);
    }

    // Group flags belonging to the same content.
    /* @var FlaggableContent[] $grouped_content */
    $grouped_content = [];
    while ($content_element = array_pop($content)) {
      foreach ($content as $i => $other_content) {
        if ($content_element->equals($other_content)) {
          // Add flags attached to the same content element to the same
          // FlaggedContent instance and remove the duplicate to prevent
          // further processing.
          $content_element->addFlags($other_content->getFlags());
          unset($content[$i]);
        }
      }
      $grouped_content[] = $content_element;
    }

    // Sort the content in reverse chronological order based on the latest
    // flag.
    uasort($grouped_content, function(FlaggableContent $a, FlaggableContent $b) {
      return $a->getLatestFlag()->getTimeFlagged()->getTimestamp() - $b->getLatestFlag()->getTimeFlagged()->getTimestamp();
    });
    $grouped_content = array_reverse($grouped_content);

    return $grouped_content;
  }

}
