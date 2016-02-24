<?php

/**
 * @file
 * Contains \Drupal\dbcdk_community\BaseCommunityBlock.
 */

namespace Drupal\dbcdk_community;

use Drupal\Core\Block\BlockBase;
use Drupal\dbcdk_community\CommunityApiClient as ApiClient;

/**
 * Defines a base community block implementation.
 *
 * This abstract class defines the API Client that every Community Service
 * requires. - We define the client here, so other Community Blocks simply
 * can extend on this class.
 */
abstract class BaseCommunityBlock extends BlockBase {

  /**
   * The Community service client.
   *
   * The Community Service API requires an instance of the ApiClient to work.
   *
   * @var ApiClient
   */
  protected $communityClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    // Define a new instance of the Community Service Client so we can pass it
    // to different community services that we want to take advantage of.
    $client = new ApiClient();
    $this->communityClient = $client->getClient();
  }

}
