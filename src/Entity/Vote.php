<?php

namespace Drupal\votingapi_rating\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\votingapi\Entity\Vote as VoteDefault;

/**
 * Defines the Vote entity.
 */
class Vote extends VoteDefault {

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    ContentEntityBase::preSave($storage);
    // Prevent the Entity to delete itself, Vote::preSave() may be invoked
    // twice. First by the EntityStorage, secondary by a FieldStorage.
    if ($this->id() !== NULL) {
      return;
    }

    $config = \Drupal::config('votingapi.settings');

    $window = $this->getOwnerId() == 0
      ? $config->get('anonymous_window')
      : $config->get('user_window');

    $query = $storage->getQuery()
      ->addTag('debug')
      ->condition('user_id', $this->getOwnerId())
      ->condition('type', $this->get('type')->target_id)
      ->condition('entity_type', $this->getVotedEntityType())
      // Added a field_name condition for preventing deletion of values from
      // other votingapi_widgets fields on the same entity.
      ->condition('field_name', $this->get('field_name')->value)
      ->condition('entity_id', $this->getVotedEntityId());

    if ($this->getOwnerId() == 0) {
      $query->condition('vote_source', $storage::defaultVoteSource($this->getSource()));
    }

    if ($window > -1) {
      $query->condition('timestamp', $this->get('timestamp')->value - $window, '>');
    }

    $votes = $query->execute();

    if (!empty($votes)) {
      $controller = \Drupal::entityManager()
        ->getStorage('vote');
      $entities = $controller->loadMultiple($votes);
      $controller->delete($entities);
    }
  }

}
