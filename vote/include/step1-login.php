<?php

function step1_do () {
  global $email;
  global $token;

  $result = "<h2>Step 1/4 - Login</h2>\n";

  $result .= "<p>Please login using the informations that was sent to you in a ballot by e-mail.</p>\n";

  $result .= "<div class=\"votedata\">\n";
  $result .= "<p><label for=\"email\">E-mail: </label><input type=\"text\" name=\"email\" value=\"".htmlspecialchars ($email)."\" /></p>\n";
  $result .= "<p><label for=\"token\">Vote token: </label><input type=\"text\" name=\"token\" value=\"".htmlspecialchars ($token)."\" /></p>\n";
  $result .= "</div>\n";

  return $result;
}

?>
