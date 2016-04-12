<?php

/**
 * @file
 * FlaggableContentRepository class definition.
 */

namespace Drupal\dbcdk_community\Content;

use DBCDK\CommunityServices\Api\CommentApi;
use DBCDK\CommunityServices\Api\FlagApi;
use DBCDK\CommunityServices\Api\PostApi;
use DBCDK\CommunityServices\ApiException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * A repository for retrieving content with attached flags.
 *
 * Multiple types of content can be flagged. The purpose of this class is
 * to provide a simple way to retrieve all such types instead of using
 * one class per type.
 */
class FlaggableContentRepository {
  use LoggerAwareTrait;

  /**
   * The API to use when retrieving flagged content.
   *
   * @var FlagApi $flagApi
   */
  protected $flagApi;

  /**
   * The API to use when handling Post objects.
   *
   * @var PostApi $postApi
   */
  protected $postApi = [];

  /**
   * The API to use when handling Comment objects.
   *
   * @var CommentApi $commentApi
   */
  protected $commentApi = [];

  /**
   * FlaggableContentRepository constructor.
   *
   * @param FlagApi $flag_api
   *   The FlagApi class to use when retrieving content.
   * @param PostApi $post_api
   *   The PostApi class to use when handling Post objects.
   * @param CommentApi $comment_api
   *   The CommentApi class to use when handling Comment objects.
   */
  public function __construct(FlagApi $flag_api, PostApi $post_api, CommentApi $comment_api) {
    $this->flagApi = $flag_api;
    $this->postApi = $post_api;
    $this->commentApi = $comment_api;
    $this->logger = new NullLogger();
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
   * @throws \DBCDK\CommunityServices\ApiException
   *   Thrown if we are not able to retrieve any content from the API.
   */
  public function getContentWithUnreadFlags() {
    return $this->getContent(['where' => ['markedAsRead' => FALSE]]);
  }

  /**
   * Retrieve flaggable content from the repository matching a filter.
   *
   * @param array $filter
   *   An array of filters to use when retrieving the content. If an empty
   *   filter is used then all flaggable content available will be returned.
   *
   * @return \Drupal\dbcdk_community\Content\FlaggableContent[]
   *   All flaggable content matching the filter.
   */
  protected function getContent(array $filter = []) {
    /* @var FlaggableContent[] $content */
    $content = [];

    // The API cannot handle an empty array being passed so we convert it to
    // an empty string.
    $filter_string = (!empty($filter)) ? json_encode($filter) : NULL;
    /* @var \DBCDK\CommunityServices\Model\Flag[] $flags */
    $flags = (array) $this->flagApi->flagFind($filter_string);

    // Loop over *all* flags to find which content it is attached to.
    // This is not very efficient but our generated APIs do not offer a
    // better way to do this. In general the number of unread flags should
    // not accumulate over time so it should be manageable.
    foreach ($flags as $flag) {
      // We do not know which type of content our flag is attached to and
      // in theory it could be attached to multiple. We get all of them and
      // convert them to FlaggableContent instances.
      $post = $flag->getPosts();
      $comment = $flag->getComments();

      $flagged_content = array_map(function($content) use ($flag) {
        return (new FlaggableContent($content))->addFlag($flag);
      }, array_filter([$post, $comment]));

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

  /**
   * Get a piece of flaggable content attached to a specific flag.
   *
   * Note that this function will not return a flaggable object with all flags
   * attached to the content - only the flag with the provided id.
   *
   * @param int $flag_id
   *   The id of the flag for which to retrieve content.
   *
   * @return FlaggableContent|NULL
   *   The content attached to the flag.
   *
   * @throws \DBCDK\CommunityServices\ApiException
   *   Thrown if we are not able to retrieve any content from the API.
   */
  public function getContentById($flag_id) {
    $content = $this->getContent(['where' => ['id' => $flag_id], 'limit' => 1]);
    return array_shift($content);
  }

  /**
   * Get a piece of flaggable content and ALL flags attached to a specific flag.
   *
   * @param int $flag_id
   *   The id of the flag for which to retrieve content.
   *
   * @return FlaggableContent|NULL
   *   The content attached to the flag with all other attached flags included
   *   as well.
   *
   * @throws \DBCDK\CommunityServices\ApiException
   *   Thrown if we are not able to retrieve any content from the API.
   */
  public function getContentByIdAllFlags($flag_id) {
    $flagged_content = $this->getContentById($flag_id);
    if (!empty($flagged_content)) {
      $content_all_flags = $this->getContent();
      foreach ($content_all_flags as $content) {
        if ($flagged_content->equals($content)) {
          $flagged_content->addFlags($content->getFlags());
        }
      }
    }
    return $flagged_content;
  }

  /**
   * Get collections for a FlaggableContent object.
   *
   * This method provides a simple way to fetch collections for a
   * FlaggableContent object.
   *
   * @param \Drupal\dbcdk_community\Content\FlaggableContent $content
   *   The FlaggableContent object we wish to fetch collections from.
   * @param string $type
   *   The type of collection we wish to fetch.
   *
   * @return array
   *   An array of collections for the provided FlaggableContent object.
   *
   * @throws \DBCDK\CommunityServices\ApiException
   *   Throw an exception if the service failed to fetch collections.
   */
  protected function getCollections(FlaggableContent $content, $type = 'image') {
    $object = $content->getObject();
    // Instantiate a reflection of the class to find out what type of object
    // we're fetching collections for.
    $object_reflection = new \ReflectionClass($object);
    $object_name = $object_reflection->getShortName();

    // Get the API corresponding to the type of content (ex: $this->postApi).
    $api = $this->{lcfirst($object_name) . 'Api'};
    // Format the method we wish to use when fetching collections from the API
    // defined above (ex: PostApi::postPrototypeGetImage()).
    $collection_method = lcfirst($object_name) . 'PrototypeGet' . ucfirst(strtolower($type));
    if (method_exists($api, $collection_method)) {
      try {
        return $api->{$collection_method}($object->getId());
      }
      catch (ApiException $e) {
        // If there's no collections for the ID, then the service will return a
        // 404. We don't want to log the exception because this happens for
        // every post, comment etc. without images or videos.
        if ($e->getCode() !== 404) {
          throw $e;
        }
        return [];
      }
    }
    else {
      return [];
    }
  }

  /**
   * Get ImageCollection's for a FlaggableContent object.
   *
   * This method wraps the getCollection() method to provide a simple way to
   * fetch image collections for a FlaggableContent object.
   *
   * @param \Drupal\dbcdk_community\Content\FlaggableContent $content
   *   The FlaggableContent object we wish to fetch collections from.
   *
   * @return \DBCDK\CommunityServices\Model\ImageCollection[]
   *   On array of image collections for the provided FlaggableContent object.
   */
  public function getImageCollections(FlaggableContent $content) {
    return $this->getCollections($content, 'image');
  }

  /**
   * Get VideoCollection's for a FlaggableContent object.
   *
   * This method wraps the getCollection() method to provide a simple way to
   * fetch video collections for a FlaggableContent object.
   *
   * @param \Drupal\dbcdk_community\Content\FlaggableContent $content
   *   The FlaggableContent object we wish to fetch collections from.
   *
   * @return \DBCDK\CommunityServices\Model\VideoCollection[]
   *   On array of video collections for the provided FlaggableContent object.
   */
  public function getVideoCollections(FlaggableContent $content) {
    return $this->getCollections($content, 'video');
  }

}
