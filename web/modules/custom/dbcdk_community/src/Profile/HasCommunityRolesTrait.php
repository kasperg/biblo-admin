<?php
/**
 * @file
 * Definition of HasCommunityRolesTrait.
 */

namespace Drupal\dbcdk_community\Profile;


/**
 * This trait is useful when extending model classes related to community roles.
 */
trait HasCommunityRolesTrait {

  /**
   * The community roles related to this object.
   *
   * @var \DBCDK\CommunityServices\Model\CommunityRole[]
   */
  protected $communityRoles = [];

  /**
   * Get the community roles related to this object.
   *
   * @return \DBCDK\CommunityServices\Model\CommunityRole[]
   *   Related community files.
   */
  public function getCommunityRoles() {
    return $this->communityRoles;
  }

  /**
   * Set the community roles related to this object.
   *
   * @param \DBCDK\CommunityServices\Model\CommunityRole[] $community_roles
   *   The community roles to set.
   */
  public function setCommunityRoles(array $community_roles) {
    $this->communityRoles = $community_roles;
  }

}
