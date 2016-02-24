<?php

/**
 * @file
 * Contains \Drupal\dbcdk_community\CommunityApiClient.
 */

namespace Drupal\dbcdk_community;

use \DBCDK\CommunityServices\Configuration;
use \DBCDK\CommunityServices\ApiClient;

/**
 * Community API Client Class.
 *
 * Used to communicate with the DBCDK Community API.
 */
class CommunityApiClient {

  /**
   * The Community service config.
   *
   * @var Configuration
   */
  protected $config;

  /**
   * The Community service client.
   *
   * @var ApiClient
   */
  protected $client;

  /**
   * Instantiate a new Community Api Client for other services.
   */
  public function __construct(Configuration $config = NULL, ApiClient $client = NULL) {
    // Set client configuration.
    if (empty($config)) {
      $this->config = new Configuration();
      $config = \Drupal::config('dbcdk_community.settings');
      $this->config->setHost($config->get('community_service_url'));
    }
    else {
      $this->config = $config;
    }

    // Set the client.
    empty($client) ? ($this->client = new ApiClient($this->config)) : ($this->client = $client);
  }

  /**
   * Get Community API Client.
   *
   * @return ApiClient
   *   Return an instance of the Community API Client.
   */
  public function getClient() {
    return $this->client;
  }

}
