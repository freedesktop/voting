<?php

function step2_do () {
  global $election;
  global $options_nb;
  global $options;
  global $vote;
  global $votes_array;

  $result = "<h2>Step 2/4 - Choose your vote</h2>\n";

  $result .= "<p><strong>".$election["question"]."</strong></p>\n";
  $result .= "<p>Possible answers:</p>\n";

  $result .= "<div class=\"votedata\">\n";
  if ($options_nb == 1) {
    $result .= "<p>\n";
    foreach ($options as $option) {
      $checked = "";
      if ($option["id"] == $vote) {
        $checked = " checked=\"checked\"";
      }

      $result .= "<input type=\"radio\" name=\"vote\" value=\"".$option["id"]."\"".$checked."> ".$option["option"]."<br />\n";
    }
    $result .= "</p>\n";

  } else {

    $result .= "<p>\n";
    foreach ($options as $option) {
      $checked = "";
      if (in_array ($option["id"], $votes_array)) {
        $checked = " checked=\"checked\"";
      }

      $result .= "<input type=\"checkbox\" name=\"vote".$option["id"]."\"".$checked."> ".$option["option"]."<br />\n";
    }
    $result .= "</p>\n";

  }
  $result .= "</div>\n";
  if ($options_nb > 1)
    $result .= "<p><em>You can choose up to ".$options_nb." answers.</em></p>\n";

  return $result;
}

?>
