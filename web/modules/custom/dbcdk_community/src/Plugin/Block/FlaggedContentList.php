<?php

/**
 * @file
 * FlaggedContentList class definition.
 */

namespace Drupal\dbcdk_community\Plugin\Block;

use DBCDK\CommunityServices\ApiException;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\Core\Utility\LinkGeneratorInterface;
use Drupal\dbcdk_community\Content\FlaggableContentRepository;
use Drupal\dbcdk_community\Url\ModelUrlGenerator;
use Drupal\dbcdk_community\Url\PropertyUrlGenerator;
use Drupal\dbcdk_community\Url\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block displaying a list of flagged content.
 *
 * @Block(
 *   id = "flagged_content_list",
 *   admin_label = @Translation("Flagged content list"),
 * )
 */
class FlaggedContentList extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The repository to use when retrieving flagged content.
   *
   * @var FlaggableContentRepository $flaggableContentRepository
   */
  protected $flaggableContentRepository;

  /**
   * Generator to use when creating urls for community content on frontend site.
   *
   * @var UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * The link generator to use.
   *
   * @var LinkGeneratorInterface $linkGenerator
   */
  protected $linkGenerator;

  /**
   * FlaggedContentList constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param FlaggableContentRepository $repository
   *   The repository to use when retrieving flagged content.
   * @param UrlGeneratorInterface $url_generator
   *   The generator to use when creating urls to the frontend site.
   * @param LinkGeneratorInterface $link_generator
   *   The generator to use when creating links to the frontend site.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FlaggableContentRepository $repository, UrlGeneratorInterface $url_generator, LinkGeneratorInterface $link_generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->flaggableContentRepository = $repository;
    $this->urlGenerator = $url_generator;
    $this->linkGenerator = $link_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /* @var FlaggableContentRepository $repo */
    $repo = $container->get('dbcdk_community.content.flaggable_content_repository');

    // We cannot set the logger instance in the service definition so we set it
    // up here instead.
    /* @var \Drupal\Core\Logger\LoggerChannelFactory $logger_factory */
    $logger_factory = $container->get('logger.factory');
    $repo->setLogger($logger_factory->get('DBCDK Community Service'));

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

    /* @var LinkGeneratorInterface $link_generator */
    $link_generator = $container->get('link_generator');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $repo,
      $url_generator,
      $link_generator
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    try {
      $all_content_elements = $this->flaggableContentRepository->getContentWithUnreadFlags();
    }
    catch (ApiException $e) {
      \Drupal::logger('DBCDK Community Service')->error($e);
      drupal_set_message($this->t('An error occurred when retrieving data from the community service. Please try again later or contact an administrator.'), 'error');
      $all_content_elements = [];
    }

    $content_per_page = 10;
    $page = pager_default_initialize(count($all_content_elements), $content_per_page);

    $table = [
      '#theme' => 'table',
      '#caption' => $this->t(
        'Showing %from-%to of %total pieces of flagged content', [
          '%from' => ($page * $content_per_page) + 1,
          '%to' => ($page + 1) * $content_per_page,
          '%total' => count($all_content_elements),
        ]),
      '#header' => [
        $this->t('Content'),
        $this->t('Active flags'),
        $this->t('Latest flag'),
        $this->t('Flag comment'),
        $this->t('Link'),
      ],
      '#data' => [],
      '#empty' => $this->t('There is currently no flagged content'),
    ];

    /* @var \Drupal\dbcdk_community\Content\FlaggableContent[] $page_content_elements */
    $page_content_elements = array_slice($all_content_elements, $page, $content_per_page);
    foreach ($page_content_elements as $content_element) {

      try {
        $community_site_url = Url::fromUri($this->urlGenerator->generate($content_element->getObject()));
        $link = $this->linkGenerator->generate(
          $this->t('See on community site'),
          $community_site_url
        );
      }
      catch (\Exception $e) {
        // If generating a link fails then do not output anything.
        $link = '';
      }

      $table['#rows'][] = [
        $content_element->getContent(),
        count($content_element->getFlags()),
        $content_element->getLatestFlag()->getTimeFlagged()->format('Y-m-d H:i'),
        $content_element->getLatestFlag()->getDescription(),
        $link,
      ];
    }

    return [
      'table' => $table,
      'pager' => [
        '#type' => 'pager',
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
