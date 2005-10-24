<?php

function step3_do () {
  global $election;
  global $options_nb;
  global $options;
  global $vote;
  global $votes_array;

  $result = "<h2>Step 3/4 - Confirm your vote</h2>\n";

  $result .= "<p><strong>".$election["question"]."</strong></p>\n";
  if (($options_nb == 1 && $vote < 0) ||
      ($options_nb > 1 && count ($votes_array) >= 1)) {
    $result .= "<p>You choose to vote for:</p>\n";

    $result .= "<div class=\"votedata\">\n";
    if ($options_nb == 1) {

      $option = null;
      foreach ($options as $opt) {
        if ($opt["id"] == $vote) {
          $option = $opt;
          break;
        }
      }
      if ($option != null)
        $result .= "<p>".$option["option"]."</p>\n";
      else {
        $result .= "<p>Unknown vote: ".$vote."</p>\n";
        $error .= "There was an unkown vote: ".$vote."<br />\n";
      }

    } else {

      $result .= "<ul>\n";
      foreach ($votes_array as $vote) {
        $found = FALSE;
        foreach ($options as $option) {
          if ($option["id"] == $vote) {
            $result .= "<li>".$option["option"]."</li>\n";
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
