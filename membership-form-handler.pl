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
print MAIL <<END;
From: gnome-membership\@condor.nj.org (GNOME Membership Script)
To: membership\@gnome.org (GNOME Membership Committee)
Subject: Application received from $FORM::name ($FORM::email)

Contact Information:
--------------------
Name: $FORM::name
E-mail: $FORM::email
irc.gnome.org nickname (if any): $FORM::ircnick
cvs.gnome.org username (if any): $FORM::cvsuser

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


print "<h1>Thank you</h1>\n";
print "<p>Thank you for your submission.  It has been forwarded to the membership committee, which will inform you when it has been processed.</p>\n";
print "<p>If you have any questions, please e-mail <a href=\"mailto:membership\@gnome.org\">membership\@gnome.org.</p>\n";



