<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
 <head>
  <title>The GNOME Foundation: Membership Application</title>
 </head>
 <body bgcolor="#ffffff">
  <center>
   <table border="2" cellpadding="5" width="595"
   summary="GNOME Foundation links">
    <tr valign="top">
     <td valign="top">
      <center>
       <a href="http://www.gnome.org">
       <img src="gnome.jpg" alt="" height="250" width="198" border="0" /></a>
       <img src="text.png" alt="The GNOME Foundation" height="198" width="354" />
      </center>
     </td>
    </tr>

    <tr>
     <td>
      <center>
       <a href="index.html">Home</a> :&nbsp;
       <a href="press.html">Press</a> :&nbsp;
       <a href="organization.html">Organization</a> :&nbsp;
       <a href="documentation.html">Documentation</a> :&nbsp;
       Membership :&nbsp;
       <a href="elections.html">Elections</a> :&nbsp;
       <a href="directory.html">Directory</a></center>
      </center>
      <br />

      <center>
       <a href="membership.html">Membership Information</a> :&nbsp;
       <a href="membership-policy.html">Membership Policy</a> :&nbsp;
       <a href="membership-list.html">Membership List</a> :&nbsp;
       Membership Application
      </center>
     </td>
    </tr>

    <!-- End of Boilerplate -->
    <tr>
     <td>
      <br />
      <h2 align="center">GNOME Foundation Membership Application</h2>

<?
  $bad_elements = array();
  $errors = array();
  
  if ($submit) {
      /* remove the slashes */
      $name = stripslashes($name);
      $email = stripslashes($email);
      $summary = stripslashes($summary);
      $details = stripslashes($details);
      $contacts = stripslashes($contacts);
      $comments = stripslashes($comments);

      if (!$name || trim($name) == "") {
        $bad_elements[] = "name";
        $errors[] = "Please enter your name.";
      }
      if (!$email || !ereg("^[a-zA-Z0-9_.-]+@[a-zA-Z0-9_.-]+\.[a-zA-Z]+$", trim($email))) {
        $bad_elements[] = "email";
        $errors[] = "Please enter an email address we can use to contact you.";
      }
      if (!$summary || trim($summary) == "") {
        $bad_elements[] = "summary";
        $errors[] = "Please enter a list of areas of GNOME to which you have made a non-trivial contribution.";
      } else if (!$details || trim($details) == "") {
        $bad_elements[] = "details";
        $errors[] = "Please enter a more detailed description of your contributions.";
      }
      if (!$contacts || trim($contacts) == "") {
        $bad_elements[] = "contacts";
        $errors[] = "Please enter a list of individuals who can confirm your contributions or indicate us how the membership committee can verify your contributions.";
      }

      if (count($bad_elements) == 0)
      {
        // make the mail 

        $formmail = "Contact Information:\n--------------------\n";
        $formmail .= "Name: " . $name . "\n";
        $formmail .= "E-mail: " . $email . "\n";
        $formmail .= "irc.gnome.org nickname (if any): " . $ircnick . "\n";
        $formmail .= "cvs.gnome.org username (if any): " . $cvsuser . "\n";
        $formmail .= "\n";
	$formmail .= "Previous GNOME Foundation member: ";
        if ($previousmember) {
            $formmail .= "yes\n";
	} else {
            $formmail .= "no\n";
	}
        $formmail .= "\n";
        $formmail .= "GNOME contributions:\n--------------------\n";
        $formmail .= "\n";
        $formmail .= "Summary:\n" . $summary . "\n";
        $formmail .= "\n";
        $formmail .= "Detailed description:\n" . $details . "\n";
        $formmail .= "\n";
        $formmail .= "Contacts:\n" . $contacts . "\n";
        $formmail .= "\n";
        $formmail .= "Other comments:\n---------------\n";
        $formmail .= $comments . "\n";
        $formmail .= "\n";
        $formmail .= "[Application received at " . date("D M j G:i:s Y") . " (Eastern time)]";

        $headers = "From: GNOME Foundation Membership Committee Script <gnome-membership@gnome.org>\n";

        if ($previousmember) {
	    $subject = "[RENEWAL] Application received from $name ($email)";
	} else {
	    $subject = "Application received from $name ($email)";
	}
        // send the mail

        mail("GNOME Foundation Membership Committee <membership-committee@gnome.org>", $subject, $formmail, $headers);

        // print the thank you page

        print ("
          <p>Thank you for your submission.  It has been forwarded to the
	     membership committee, which will inform you when it has been
	     processed.</p>
	  <p>If you have any questions, please e-mail
	     <a href=\"mailto:membership@gnome.org\">membership@gnome.org.</p>
	  <br> <br>
	  <center><a href=\"http://foundation.gnome.org\">Return to the
	          GNOME Foundation home page</a></center>
          ");
      }
  }

  if (! $submit || count($bad_elements) != 0) {  ?>

<p>To apply for membership in the GNOME Foundation, please complete
the following form as completely as possible. Your application will
then be reviewed by the Foundation's Membership and Elections
Committee, which will notify you when your application has been
accepted or rejected; the committee may also ask you for additional
information. For details on the standards used in evaluating
applications, see the <a href="membership-policy.html">GNOME
Foundation Membership Policy</a>, adopted by the Board of
Directors.</p>

<p>If you have any questions regarding the application process,
please feel free to e-mail the committee at <a
href="mailto:membership@gnome.org">membership@gnome.org</a>.</p>

      <?  if (count($bad_elements) != 0)
            {
             print("<font color=\"red\">");
             foreach ($errors as $error) { 
		print("<li>$error</li>");
	     }
             print("</font>");
            }
      ?>
      
<form action="<? echo $PHP_SELF; ?>" method="POST">
<h3>Contact Information</h3>

<table summary="Membership form">
 <tr>
  <td>Full Name:</td>
  <td><input type="text" name="name" size="40"
             value="<? if ($name) { echo $name; } ?>" /></td>
 </tr>

 <tr>
  <td>E-mail address:</td>
  <td><input type="text" name="email" size="40"
             value="<? if ($email) { echo $email; } ?>" /></td>
 </tr>

 <tr>
  <td>irc.gnome.org nickname (if any):</td>
  <td><input type="text" name="ircnick" size="20"
             value="<? if ($ircnick) { echo $ircnick; } ?>" /></td>
 </tr>

 <tr>
  <td>cvs.gnome.org username (if any):</td>
  <td><input type="text" name="cvsuser" size="20"
             value="<? if ($cvsuser) { echo $cvsuser; } ?>" /></td>
 </tr>

 <tr>
  <td colspan="2">&nbsp;</td>
 </tr>

 <tr>
  <td colspan="2">
   <input type="checkbox" name="previousmember"
          <? if ($previousmember) { ?> checked <? } ?> />
   Previous GNOME Foundation member
  </td>
 </tr>
</table>

<h3>GNOME Contributions</h3>

<p>Membership in the GNOME Foundation requires that the candidate
has contributed to a non-trivial improvement in the GNOME Project.
Please use the following sections to explain how you have
contributed to the project, providing enough detail to allow the
committee to verify your application.</p>

<p>Please provide a short list of areas of GNOME to which you have
made a non-trivial contribution (for entry into the public
membership list). For example, "Documentation, gnomecal, Debian
packaging.":</p>

<textarea name="summary" rows="3" cols="50">
<? if (trim($summary)) { echo trim($summary); } ?>
</textarea> 

<p>Please provide a more detailed description of your contributions
to help the membership committee determine your eligibility. In
general, anything listed above should be explained here, and
additional contributions can be included. For example: "Wrote a
chapter "How to Use the GNOME Calendaring System" for the GNOME
Users Guide. Several patches for gnomecal related to color support.
Packaged the 1.4 release for Debian.":</p>

<textarea name="details" rows="10" cols="50">
<? if (trim($details)) { echo trim($details); } ?>
</textarea> 

<p>Please list individuals (frequently, but not necessarily,
project maintainers) who can help the membership committee
determine your eligibility. You should provide their name, e-mail
address, and a brief description of their role as a reference. If
there is nobody you can think to list, feel free to suggest another
way for the committee to verify your contributions, such as a
pointer to a project ChangeLog, etc. To continue the earlier
example: "Dan Mueth (d-mueth@uchicago.edu) (GDP coordinator);
Russell Steinthal (rms39@columbia.edu) (gnome-pim maintainer);
packaging logs at http://www.debian.org/packages/gnome/":</p>

<textarea name="contacts" rows="5" cols="50">
<? if (trim($contacts)) { echo trim($contacts); } ?>
</textarea> 

<h3>Other Comments</h3>

<p>Please feel free to include any additional information which you
believe the committee should consider while reviewing your
application:</p>

<textarea name="comments" rows="10" cols="50">
<? if (trim($comments)) { echo trim($comments); } ?>
</textarea> 

<p>
 <center>
  <input type="submit" name="submit" value="Submit Application" />
  <input type="reset" />
 </center>
</p>

</form>
</td>
</tr>

<? } ?>

    <!-- Start of Boilerplate -->
    <tr>
     <td>
      <center>
       <font size=-1>
        This site is maintained by
        <a href="mailto:membership@gnome.org">the GNOME Foundation Membership
        and Elections Committee</a> and was designed by
        <a href="mailto:jpsc@users.sourceforge.net">JP Schnapper-Casteras</a>
        and <a href="mailto:bart@eazel.com">Bart Decrem</a>.
       </font>
      </center>
     </td>
    </tr>
   </table>
  </center>
 </body>
</html>
