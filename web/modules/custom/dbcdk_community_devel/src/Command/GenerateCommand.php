<?php

/**
 * @file
 * Contains \Drupal\dbcdk_community_devel\Command\GenerateCommand.
 */

namespace Drupal\dbcdk_community_devel\Command;

use DBCDK\CommunityServices\Api\CommentApi;
use DBCDK\CommunityServices\Api\FlagApi;
use DBCDK\CommunityServices\Api\PostApi;
use DBCDK\CommunityServices\Api\ProfileApi;
use DBCDK\CommunityServices\Api\QuarantineApi;
use DBCDK\CommunityServices\ApiClient;
use DBCDK\CommunityServices\Model\Comment;
use DBCDK\CommunityServices\Model\Flag;
use DBCDK\CommunityServices\Model\Post;
use DBCDK\CommunityServices\Model\Profile;
use DBCDK\CommunityServices\Model\Quarantine;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\Command;
use Drupal\Console\Style\DrupalStyle;

/**
 * This command generates dummy content in the community service.
 *
 * This can be used one of more times to generate a data set for development
 * purposes.
 *
 * @package Drupal\dbcdk_community_devel
 */
class GenerateCommand extends Command {

  /**
   * The generated profiles
   *
   * @var Profile[]
   */
  protected $profiles = [];

  /**
   * The generated quarantines.
   *
   * @var Quarantine[]
   */
  protected $quarantines = [];

  /**
   * The generated posts.
   *
   * @var Post[]
   */
  protected $posts = [];

  /**
   * The generated comments.
   *
   * @var Comment[]
   */
  protected $comments = [];

  /**
   * The generated flags.
   *
   * @var Flag[]
   */
  protected $flags = [];

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('dbcdk_community_devel:generate')
      ->setDescription($this->trans('command.dbcdk_community_devel.generate.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);

    $faker = \Faker\Factory::create();

    // First we create some profiles.
    /* @var ProfileApi $profile_api */
    $profile_api = \Drupal::service('dbcdk_community.api.profile');
    foreach (range(1, 10) as $i) {
      $profile = new Profile();
      $profile->setUsername($faker->userName);
      $profile->setDisplayName($faker->firstName);
      $profile->setFullName($faker->name);
      $profile->setDescription($faker->sentence());
      $profile->setEmail($faker->email);
      $profile->setPhone($faker->phoneNumber);
      $profile->setBirthday($faker->dateTimeThisDecade);
      $profile->setCreated($faker->dateTimeThisYear);

      $this->profiles[] = $profile_api->profileCreate($profile);
    };
    $io->success(sprintf('Created %d profiles', count($this->profiles)));

    // Put some users in quarantine.
    /* @var QuarantineApi $quarantine_api */
    $quarantine_api = \Drupal::service('dbcdk_community.api.quarantine');
    foreach (range(1, 5) as $i) {
      $quarantine = new Quarantine();

      $quarantined = $this->profiles[rand(0, count($this->profiles) - 1)];
      $quarantine->setQuarantinedId($quarantined->getId());

      $quarantine->setStart($faker->dateTimeBetween($quarantined->getCreated()));
      $quarantine->setEnd($faker->dateTimeBetween($quarantine->getStart()));
      $quarantine->setReason($faker->sentence());

      $this->quarantines[] = $quarantine_api->quarantineCreate($quarantine);
    }
    $io->success(sprintf('Created %d quarantines', count($this->quarantines)));

    // Then we create some content.
    /* @var PostApi $post_api */
    $post_api = \Drupal::service('dbcdk_community.api.post');
    foreach (range(1, 10) as $i) {
      $post = new Post();
      $post->setTitle($faker->sentence());
      $post->setContent($faker->paragraphs());

      $owner = $this->profiles[rand(0, count($this->profiles) - 1)];
      $post->setPostownerid($owner->getId());

      $post->setTimeCreated($faker->dateTimeBetween($owner->getCreated()));

      $this->posts[] = $post_api->postCreate($post);
    }
    $io->success(sprintf('Created %d posts', count($this->posts)));

    /* @var CommentApi $comment_api */
    $comment_api = \Drupal::service('dbcdk_community.api.comment');
    foreach (range(1, 50) as $i) {
      $comment = new Comment();
      $comment->setContent($faker->paragraph);

      $post = $this->posts[rand(0, count($this->posts) - 1)];
      $comment->setPostid($post->getId());

      /* @var Profile $owner */
      $owner = $this->profiles[rand(0, count($this->profiles) - 1)];
      $comment->setCommentownerid($owner->getId());

      $comment->setTimeCreated($faker->dateTimeBetween($post->getTimeCreated()));

      $this->comments[] = $comment_api->commentCreate($comment);
    }
    $io->success(sprintf('Created %d comments', count($this->comments)));

    // Flag some content.
    /* @var FlagApi $flag_api */
    $flags_api = \Drupal::service('dbcdk_community.api.flag');
    foreach (range(1, 20) as $i) {
      $flag = new Flag();
      $flag->setDescription($faker->sentence());
      $flag->setMarkedAsRead((bool) rand(0, 1));

      $owner = $this->profiles[rand(0, count($this->profiles) - 1)];
      $flag->setOwnerId($owner->getId());

      $flag->setTimeFlagged($faker->dateTimeBetween($owner->getCreated()));

      $this->flags[] = $flags_api->flagCreate($flag);
    }
    $io->success(sprintf('Created %d flags', count($this->flags)));

    // Link flags to posts.
    foreach (array_slice($this->flags, 0, 10) as $flag) {
      $post = $this->posts[rand(0, count($this->posts) - 1)];
      $flags_api->flagPrototypeLinkPosts($post->getId(), $flag->getId());
    }
    $io->success(sprintf('Linked flags to posts'));

    // Link flags to comments.
    foreach (array_slice($this->flags, 10) as $flag) {
      $comment = $this->comments[rand(0, count($this->comments) - 1)];
      $flags_api->flagPrototypeLinkComments($comment->getId(), $flag->getId());
    }
    $io->success(sprintf('Linked flags to comments'));
  }

}
