#!/usr/bin/perl
#
# A *really* simple CGI handler for the GNOME Foundation membership
# form.
#
# Author: Russell Steinthal <rms39@columbia.edu>
#
#

my $MAIL_COMMAND = "/usr/lib/sendmail";

use CGI qw /-debug/;

my $form = new CGI;
$form->import_names ("FORM");

print $form->header;

my $date = localtime;

open MAIL, "|$MAIL_COMMAND";
print MAIL <<HEADER;
From: gnome-membership\@gnome.org (GNOME Membership Script)
To: membership\@gnome.org (GNOME Membership Committee)
HEADER

if ($FORM::previousmember == "on") {
  print MAIL "Subject: [RENEWAL] Application received from $FORM::name ($FORM::email)";
} else {
  print MAIL "Subject: Application received from $FORM::name ($FORM::email)";
}
print MAIL <<END;

Contact Information:
--------------------
Name: $FORM::name
E-mail: $FORM::email
irc.gnome.org nickname (if any): $FORM::ircnick
cvs.gnome.org username (if any): $FORM::cvsuser
Previous GNOME Foundation member:  $FORM::previousmember

GNOME contributions:
--------------------

Summary:
$FORM::summary

Detailed description:
$FORM::details

Contacts:
$FORM::contacts

Other comments:
---------------
$FORM::comments

[Application received at $date (Eastern time)]
END

close MAIL;


print "<html>\n";
print "<head><title>Application Received</title></head>\n";
print "<body bgcolor=\"#FFFFFF\">";
print "<h1>Thank you</h1>\n";
print "<p>Thank you for your submission.  It has been forwarded to the membership committee, which will inform you when it has been processed.</p>\n";
print "<p>If you have any questions, please e-mail <a href=\"mailto:membership\@gnome.org\">membership\@gnome.org.</p>\n";
print "<br> <br>\n";
print "<center><a href=\"http://foundation.gnome.org\">Return to the GNOME Foundation home page</a></center>\n";
print "</body>\n";
print "</html>\n";


