<?php
/**
 * @file
 * Class definition for Profiles.
 */

namespace Drupal\dbcdk_community\Profile;

use \DBCDK\CommunityServices\Model\Profile as ModelProfile;

/**
 * This class extends the generated model with relations to other models.
 */
class Profile extends ModelProfile {
  use HasQuarantinesTrait;
  use HasCommunityRolesTrait;

  /**
   * Profile constructor.
   *
   * @param \DBCDK\CommunityServices\Model\Profile|NULL $profile
   *   The model object to base this upon.
   */
  public function __construct(ModelProfile $profile = NULL) {
    $data = get_object_vars($profile);
    parent::__construct($data);
  }

}
