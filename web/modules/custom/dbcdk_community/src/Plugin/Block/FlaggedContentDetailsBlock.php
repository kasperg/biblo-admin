<?php

/**
 * @file
 * Contains \Drupal\dbcdk_community\Plugin\Block\FlaggedContentDetails.
 */

namespace Drupal\dbcdk_community\Plugin\Block;

use DBCDK\CommunityServices\Api\ProfileApi;
use DBCDK\CommunityServices\ApiException;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\dbcdk_community\Url\ModelUrlGenerator;
use Drupal\dbcdk_community\Url\PropertyUrlGenerator;
use Drupal\dbcdk_community\Url\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dbcdk_community\Content\FlaggableContentRepository;

/**
 * Provides a 'FlaggedContentDetails' block.
 *
 * @Block(
 *  id = "flagged_content_details_block",
 *  admin_label = @Translation("Flagged content details"),
 *  context = {
 *    "flag_id" = @ContextDefinition("integer")
 *  }
 * )
 */
class FlaggedContentDetailsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The repository to use when retrieving flaggable content.
   *
   * @var FlaggableContentRepository
   */
  protected $flaggableContentRepository;

  /**
   * The profile api to use when retrieving information about content owners.
   *
   * @var ProfileApi
   */
  protected $profileApi;

  /**
   * The url generator to use when creating links to the biblo.dk site.
   *
   * @var UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * The logger to use.
   *
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param LoggerInterface $logger
   *   The logger to use.
   * @param FlaggableContentRepository $repository
   *   The repository to use when retrieving flaggable content.
   * @param ProfileApi $profile_api
   *   The profile api to use when retrieving information about content owners.
   * @param UrlGeneratorInterface $url_generator
   *   The url generator to use when generating links to the biblo.dk site.
   */
  public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        LoggerInterface $logger,
        FlaggableContentRepository $repository,
        ProfileApi $profile_api,
        UrlGeneratorInterface $url_generator
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger;
    $this->flaggableContentRepository = $repository;
    $this->profileApi = $profile_api;
    $this->urlGenerator = $url_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /* @var \Drupal\Core\Logger\LoggerChannelFactory $logger_factory */
    $logger_factory = $container->get('logger.factory');

    // Setup url generation for flaggable content.
    /* @var \Drupal\Core\Config\Config $config */
    $config = $container->get('config.factory')->get('dbcdk_community.settings');
    $base_url = $config->get('community_site_url');
    $url_generator = new ModelUrlGenerator();
    $url_generator->registerClassGenerator(
      'DBCDK\CommunityServices\Model\Post',
      new PropertyUrlGenerator(
        $base_url . $config->get('community_site_post_url_pattern')
      )
    );
    $url_generator->registerClassGenerator(
      'DBCDK\CommunityServices\Model\Comment',
      new PropertyUrlGenerator(
        $base_url . $config->get('community_site_comment_url_pattern')
      )
    );

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $logger_factory->get('DBCDK Community Service'),
      $container->get('dbcdk_community.content.flaggable_content_repository'),
      $container->get('dbcdk_community.api.profile'),
      $url_generator
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $flag_id = $this->getContextValue('flag_id');

    try {
      $content = $this->flaggableContentRepository->getContentById($flag_id);
      $profile = $this->profileApi->profileFindById($content->getCreatorId());

      $profile_link = Link::createFromRoute(
        $profile->getUsername(),
        'page_manager.page_view_dbcdk_community_profile',
        ['username' => $profile->getUsername()]
      );

      $url = $this->urlGenerator->generate($content->getObject());
      $content_link = Link::fromTextAndUrl($url, Url::fromUri($url));

      $rows = [
        [$this->t('Content'), $content->getContent()],
        [$this->t('Created on'), $content->getTimeCreated()->format('H:i d-m-Y')],
        [$this->t('Author'), $profile_link],
        [$this->t('View on biblo.dk'), $content_link],
      ];
    }
    catch (ApiException $e) {
      // If an error occours then log it and display an empty table.
      $this->logger->error($e);
      $rows = [];
    }

    $table = [
      '#theme' => 'table',
      '#header' => [
        $this->t('Subject'),
        $this->t('Information'),
      ],
      '#rows' => $rows,
      '#empty' => $this->t('No information found for content related to flag "%flag_id".', [
        '%flag_id' => $flag_id,
      ]),
      '#cache' => [
        'max-age' => 0,
      ],
    ];

    return [
      'table' => $table,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
