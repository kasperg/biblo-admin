<?php
/**
 * @file
 * Definition of HasQuarantinesTrait.
 */

namespace Drupal\dbcdk_community\Profile;

/**
 * This trait is useful when extending model classes related to quarantines.
 */
trait HasQuarantinesTrait {

  /**
   * The related quarantines.
   *
   * @var DBCDK\CommunityServices\Model\Quarantine[] $quarantines
   */
  protected $quarantines = [];

  /**
   * Get quarantines related to this object.
   *
   * @return \Drupal\dbcdk_community\Profile\DBCDK\CommunityServices\Model\Quarantine[]
   *   Related quarantines.
   */
  public function getQuarantines() {
    return $this->quarantines;
  }

  /**
   * Set quarantines related to this object.
   *
   * @param |DBCDK\CommunityServices\Model\Quarantine[] $quarantines
   *   The quarantines to set.
   */
  public function setQuarantines(array $quarantines) {
    $this->quarantines = $quarantines;
  }

}
