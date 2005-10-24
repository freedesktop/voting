<?php

function step2_do () {
  global $election;
  global $choices_nb;
  global $choices;
  global $vote;
  global $votes_array;

  $result = "<h2>Step 2/4 - Choose your vote</h2>\n";

  $result .= "<p><strong>".$election["question"]."</strong></p>\n";
  $result .= "<p>Possible answers:</p>\n";

  $result .= "<div class=\"votedata\">\n";
  if ($choices_nb == 1) {
    $result .= "<p>\n";
    foreach ($choices as $choice) {
      $checked = "";
      if ($choice["id"] == $vote) {
        $checked = " checked=\"checked\"";
      }

      $result .= "<input type=\"radio\" name=\"vote\" value=\"".$choice["id"]."\"".$checked."> ".$choice["choice"]."<br />\n";
    }
    $result .= "</p>\n";

  } else {

    $result .= "<p>\n";
    foreach ($choices as $choice) {
      $checked = "";
      if (in_array ($choice["id"], $votes_array)) {
        $checked = " checked=\"checked\"";
      }

      $result .= "<input type=\"checkbox\" name=\"vote".$choice["id"]."\"".$checked."> ".$choice["choice"]."<br />\n";
    }
    $result .= "</p>\n";

  }
  $result .= "</div>\n";
  if ($choices_nb > 1)
    $result .= "<p><em>You can choose up to ".$choices_nb." answers.</em></p>\n";

  return $result;
}

?>
