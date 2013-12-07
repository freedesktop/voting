<?php

require_once ("include/election-sql.php");

// Shamelessly stolen from:
//    http://www.php.net/manual/en/function.mt-rand.php#55013
//
//
// Returns a random code of the specified length, containing characters that
// are equally likely to be any of the digits, uppercase letters, or  lowercase
// letters.
//
// The default length of 10 provides 839299365868340224 (62^10) possible codes.
//
// NOTE: Do not call wt_srand().  It is handled automatically in PHP 4.2.0 and
//       above and any additional calls are likely to DECREASE the randomness.
////
function randomCode ($length=10){
   $retVal = "";
   while(strlen($retVal) < $length){
       $nextChar = mt_rand(0, 61); // 10 digits + 26 uppercase + 26 lowercase = 62 chars
       if(($nextChar >=10) && ($nextChar < 36)){ // uppercase letters
           $nextChar -= 10; // bases the number at 0 instead of 10
           $nextChar = chr($nextChar + 65); // ord('A') == 65
       } else if($nextChar >= 36){ // lowercase letters
           $nextChar -= 36; // bases the number at 0 instead of 36
           $nextChar = chr($nextChar + 97); // ord('a') == 97
       } else { // 0-9
           $nextChar = chr($nextChar + 48); // ord('0') == 48
       }
       $retVal .= $nextChar;
   }
   return $retVal;
}


function basic_vote_validation($votes_array) {
  $votes_set=array();
  foreach($votes_array as $vote) {
    // should probably check here again if the vote is still in the valid range
    if($votes_set[$vote] === true) {
      $error .= "preference for $vote cast twice. <br />\n";
      return false;
    }
    $votes_set[$vote] = true;
  }
  return true;
}

function step4_do () {
  global $error;
  global $handle;
  global $election_id;
  global $vote;
  global $votes_array;
  global $email;
  global $tmp_token;

  $result = "";

  if (!basic_vote_validation ($votes_array)) {
    $error .= "Your vote failed basic validation, please start over.<br />\n";
    return $result;
  }

  $res = elec_sql_start_transaction ($handle);
  if (!$res) {
    $error .= "Can not start a SQL transaction for the vote.<br />\n";
    return $result;
  }

  $i = 0;
  do {
    $anon_token = randomCode (32);
    $anon_token_id = elec_insert_new_anon_token ($handle, $election_id, $anon_token);
    $i++;
  } while ($anon_token_id === FALSE && $i < 10);

  if ($anon_token_id === FALSE) {
    $error .= "Can not create an anonymous token: ".htmlspecialchars(mysql_error ($handle))."<br />\n";
    elec_sql_rollback ($handle);
    return $result;
  }

    $index=0;
    foreach ($votes_array as $vote) {
      //FIXME verify that $vote is valid for this election/referendum
      $index++;
      error_log($vote.", ".$index.", ".$anon_token_id, 0);
      $res = elec_insert_new_vote ($handle, $anon_token_id, $vote, $index);

      if (!$res) {
        $error .= "Can not insert a vote: ".htmlspecialchars(mysql_error ($handle))."<br />\n";
        elec_sql_rollback ($handle);
        return $result;
      }

  }

  $res = elec_sql_remove_tmp_token ($handle, $election_id, $email, $tmp_token);

  if (!$res) {
    $error .= "Can not remove temporary token: ".htmlspecialchars(mysql_error ($handle))."<br />\n";
    elec_sql_rollback ($handle);
    return $result;
  }

  $res = elec_sql_commit ($handle);
  if (!$res) {
    $error .= "Can not commit the vote: ".htmlspecialchars(mysql_error ($handle))."<br />\n";
    return $result;
  }

  $result .= "<h2>Step 4/4 - Keep your anonymous token</h2>\n";
  $result .= "<h3>Your vote has been received.</h3>\n";
  $result .= "<p>Please write this anonymous token somewhere:</p>\n";
  $result .= "<div class=\"votedata\"><p><strong><span class=\"token\">".htmlspecialchars($anon_token)."</span></strong></p></div>\n";
  $result .= "<p>This anonymous token will enable you to verify your vote, when the preliminary ";
  $result .= "results are published. Nobody, not even the Membership Committee, except you knows ";
  $result .= "that this token is associated with you, and only you will be able to verify your ";
  $result .= "vote. It is therefore <em>not</em> possible to request this anonymous token later.</p>";
  $result .= "<p>Click <a href=\"./\">here</a> to return to the elections start page.</p>\n";

  return $result;
}

?>
