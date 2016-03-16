<?php

/**
 * @file
 * FlaggedContentList class definition.
 */

namespace Drupal\dbcdk_community\Plugin\Block;

use DBCDK\CommunityServices\ApiException;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dbcdk_community\Content\FlaggableContent;
use Drupal\dbcdk_community\Content\FlaggableContentRepository;
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
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FlaggableContentRepository $repository) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->flaggableContentRepository = $repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /* @var FlaggableContentRepository $repo */
    $repo = $container->get('dbcdk_community.content.flaggable_content_repository');

    // We cannot set the logger instance in the service definition so we set it
    // up here instead.
    /* @var LoggerChannelFactory $logger_factory */
    $logger_factory = $container->get('logger.factory');
    $repo->setLogger($logger_factory->get('DBCDK Community Service'));

    return new static($configuration, $plugin_id, $plugin_definition, $repo);
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
      drupal_set_message($this->t('An error occurred when retrieving data from the community service. Please try again later or contact an administrator.'));
      $all_content_elements = [];
    }

    $content_per_page = 10;
    $page = pager_default_initialize(count($all_content_elements), $content_per_page);

    $caption = '';
    if (count($all_content_elements) > $content_per_page) {
      $caption = $this->t(
        'Showing %from-%to of %total pieces of flagged content', [
        '%from' => ($page * $content_per_page) + 1,
        '%to' => ($page + 1) * $content_per_page,
        '%total' => count($all_content_elements),
      ]);
    }
    
    $table = [
      '#theme' => 'table',
      '#caption' => $caption,
      '#header' => [
        $this->t('Content'),
        $this->t('Active flags'),
        $this->t('Latest flag'),
        $this->t('Flag comment')
      ],
      '#data' => [],
      '#empty' => $this->t('There is currently no flagged content'),
    ];

    /* @var FlaggableContent[] $page_content_elements */
    $page_content_elements = array_slice($all_content_elements, $page, $content_per_page);
    foreach ($page_content_elements as $content_element) {
      $table['#rows'][] = [
        $content_element->getContent(),
        count($content_element->getFlags()),
        $content_element->getLatestFlag()->getTimeFlagged()->format('Y-m-d H:i'),
        $content_element->getLatestFlag()->getDescription(),
      ];
    }

    return [
      'table' => $table,
      'pager' => [
        '#type' => 'pager'
      ],
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }

}
