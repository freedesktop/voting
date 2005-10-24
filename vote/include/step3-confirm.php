<?php

function step3_do () {
  global $election;
  global $choices_nb;
  global $choices;
  global $vote;
  global $votes_array;

  $result = "<h2>Step 3/4 - Confirm your vote</h2>\n";

  $result .= "<p><strong>".$election["question"]."</strong></p>\n";
  if (($choices_nb == 1 && $vote < 0) ||
      ($choices_nb > 1 && count ($votes_array) >= 1)) {
    $result .= "<p>You choose to vote for:</p>\n";

    $result .= "<div class=\"votedata\">\n";
    if ($choices_nb == 1) {

      $choice = null;
      foreach ($choices as $opt) {
        if ($opt["id"] == $vote) {
          $choice = $opt;
          break;
        }
      }
      if ($choice != null)
        $result .= "<p>".$choice["choice"]."</p>\n";
      else {
        $result .= "<p>Unknown vote: ".$vote."</p>\n";
        $error .= "There was an unkown vote: ".$vote."<br />\n";
      }

    } else {

      $result .= "<ul>\n";
      foreach ($votes_array as $vote) {
        $found = FALSE;
        foreach ($choices as $choice) {
          if ($choice["id"] == $vote) {
            $result .= "<li>".$choice["choice"]."</li>\n";
            $found = TRUE;
            break;
          }
        }

        if (!$found) {
          $result .= "<li>Unknown vote: ".$vote."</li>\n";
          $error .= "There was an unkown vote: ".$vote."<br />\n";
        }
      }
      $result .= "</ul>\n";

    }
    $result .= "</div>\n";

  } else {
    $result .= "<div class=\"votedata\">\n";
    $result .= "<p>You choose to vote for none of the possible answers.</p>\n";
    $result .= "</div>\n";
  }

  $result .= "<p>To confirm this vote, please continue to the next step.</p>\n";

  return $result;
}

?>
