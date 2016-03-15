<?php

/**
 * @file
 * FlaggableContentRepositoryTest class definition.
 */

namespace Drupal\dbcdk_community\Test\Content;

use DBCDK\CommunityServices\ApiException;
use DBCDK\CommunityServices\Model\Comment;
use DBCDK\CommunityServices\Model\Flag;
use DBCDK\CommunityServices\Model\Post;
use Drupal\dbcdk_community\Content\FlaggableContent;
use Drupal\dbcdk_community\Content\FlaggableContentRepository;
use Drupal\Tests\UnitTestCase;

/**
 * Tests flaggable content retrieval
 *
 * @group dbcdk_community
 */
class FlaggableContentRepositoryTest extends UnitTestCase {

  /**
   * Test that we can assemble a list of content with flags.
   *
   * @param Flag[] $flags
   *   The flags to return from the Flag API in the test.
   * @param Post[] $flag_post_map
   *   A map of flag ids to posts in the test.
   * @param Comment[] $flag_comment_map
   *   A map of flag ids to comments in the test.
   *
   * @dataProvider unreadWithFlagsDataProvider
   */
  public function testUnreadWithFlags(array $flags, array $flag_post_map, array $flag_comment_map) {
    // Create a stub flag api using the model.
    $flag_api_stub = $this->getMock('DBCDK\CommunityServices\Api\FlagApi');
    $flag_api_stub->method('flagFind')->willReturn($flags);
    $flag_api_stub->method('flagPrototypeGetPosts')->will($this->returnCallback(function($id) use($flag_post_map) {
      return (!empty($flag_post_map[$id])) ? $flag_post_map[$id] : [];
    }));
    $flag_api_stub->method('flagPrototypeGetComments')->will($this->returnCallback(function($id) use ($flag_comment_map) {
      return (!empty($flag_comment_map[$id])) ? $flag_comment_map[$id] : [];
    }));

    $repo = new FlaggableContentRepository($flag_api_stub);
    $flaggable_content = $repo->getContentWithUnreadFlags();

    // @TODO These assertions currently are thightly coupled with the data
    // provider.
    // We should have two pieces of flagged content: 1 post and 1 comment.
    $this->assertCount(2, $flaggable_content);

    // The first flaggable content should be the post and have two flags.
    $flaggable_post = new FlaggableContent($flag_post_map[1][0]);
    $flaggable_post->addFlags(array_slice($flags, 0, 2));
    $this->assertEquals($flaggable_post, $flaggable_content[0]);

    // The second flaggable content should be the comment and have one flag.
    $flaggable_comment = new FlaggableContent($flag_comment_map[3][0]);
    $flaggable_comment->addFlag($flags[2]);
    $this->assertEquals($flaggable_comment, $flaggable_content[1]);
  }

  /**
   * Data provider for testUnreadWithFlags.
   *
   * @return array
   *   Arrays of data sets to use with the test.
   */
  public function unreadWithFlagsDataProvider() {
    $flags = [
      (new Flag())->setId(1)->setTimeFlagged(new \DateTime('now')),
      (new Flag())->setId(2)->setTimeFlagged(new \DateTime('-1 days')),
      (new Flag())->setId(3)->setTimeFlagged(new \DateTime('-2 days')),
    ];

    // This post should have two flags.
    $post = (new Post())->setId(1);
    $unflagged_post = (new Post())->setId(2);
    $flag_post_map = [
      0 => [ $unflagged_post ],
      1 => [ $post ],
      2 => [ $post ],
    ];

    // This comment should have a single flag.
    $comment = (new Comment())->setId(1);
    $unflagged_comment = (new Comment())->setId(2);
    $flag_comment_map = [
      3 => [ $comment ],
      4 => [ $unflagged_comment ],
    ];

    return [
      [$flags, $flag_post_map, $flag_comment_map]
    ];
  }

  /**
   * Test that exceptions are if flag retrieval fails.
   */
  public function testExceptionThrowing() {
    $flag_api_stub = $this->getMock('DBCDK\CommunityServices\Api\FlagApi');
    $flag_api_stub->method('flagFind')->willThrowException(new ApiException());

    $repo = new FlaggableContentRepository($flag_api_stub);

    $this->setExpectedException('DBCDK\CommunityServices\ApiException');
    $repo->getContentWithUnreadFlags();
  }

  /**
   * Test handling if content for individual flags fail.
   */
  public function testErrorHandling() {
    $flag_api_stub = $this->getMock('DBCDK\CommunityServices\Api\FlagApi');
    $flag_api_stub->method('flagFind')->willReturn([ new Flag() ]);
    $flag_api_stub->method('flagPrototypeGetPosts')->willThrowException(new ApiException());

    $logger_stub = $this->getMock('Psr\Log\LoggerInterface');
    $logger_stub->expects($this->once())->method('warning');

    $repo = new FlaggableContentRepository($flag_api_stub);
    $repo->setLogger($logger_stub);

    $content = $repo->getContentWithUnreadFlags();
    $this->assertEmpty($content);
  }

}
