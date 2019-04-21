<?php

namespace Drupal\votingapi_rating\Plugin\VoteResultFunction;

use Drupal\votingapi_rating\FieldVoteResultBase;

/**
 * A sum of a set of votes.
 *
 * @VoteResultFunction(
 *   id = "vote_field_count",
 *   label = @Translation("Count"),
 *   description = @Translation("The number of votes cast."),
 *   deriver = "Drupal\votingapi_rating\Plugin\Derivative\FieldResultFunction",
 * )
 */
class FieldCount extends FieldVoteResultBase {

  /**
   * {@inheritdoc}
   */
  public function calculateResult($votes) {
    $votes = $this->getVotesForField($votes);
    return count($votes);
  }

}
