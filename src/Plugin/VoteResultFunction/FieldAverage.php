<?php

namespace Drupal\votingapi_rating\Plugin\VoteResultFunction;

use Drupal\votingapi_rating\FieldVoteResultBase;

/**
 * A sum of a set of votes.
 *
 * @VoteResultFunction(
 *   id = "vote_field_average",
 *   label = @Translation("Average"),
 *   description = @Translation("The average vote value."),
 *   deriver = "Drupal\votingapi_rating\Plugin\Derivative\FieldResultFunction",
 * )
 */
class FieldAverage extends FieldVoteResultBase {

  /**
   * {@inheritdoc}
   */
  public function calculateResult($votes) {
    $total = 0;
    $votes = $this->getVotesForField($votes);
    foreach ($votes as $vote) {
      $total += (int) $vote->getValue();
    }
    if ($total == 0) {
      return 0;
    }
    return ($total / count($votes));
  }

}
