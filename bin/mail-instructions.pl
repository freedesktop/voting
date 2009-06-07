#!/usr/bin/perl
use utf8;

# Script to send instructions to all voters.
#
# How to use this script
# ======================
#
# You probably want to first update the subject of the e-mail that will be
# sent. The second line of the instructions.txt will be the subject.
#
# So let's suppose that the instructions are in instructions.txt and that you
# made a list of voters in maildata.txt (probably using create-tmp-tokens.pl).
# The format of this file should be:
# name;email;token
#
# You should use this script like this:
# $ ./mail-instructions.pl maildata.txt instructions.txt
#
# This script needs a MTA to send the e-mails. If you don't have one or if
# you're not sure that your ISP allows you to directly send mails, it's
# probably better and safer to run the script from a gnome.org server.
# Please test this script with your own email address by creating a 
# maildata.txt with a single entry like
# foo;your@address;bar
#
# You may want to look at your mail server logs (and maybe keep them) to
# know if the mail was delivered. There are usually 10-15 errors. In case of
# such errors, you can try to look for the new e-mail addresses of the voters
# to ask them if they want to update their registered e-mail address and
# receive the instructions.

use Mail::Internet;

die "Usage: mail-instructions.pl <recipient list> <instructions template>\n" unless $#ARGV == 1;

open INSTRUCTIONS, "<$ARGV[1]" || die "Cannot open instructions file $ARGV: $!";
my @instructions = <INSTRUCTIONS>;
close INSTRUCTIONS;
my $from_header = shift @instructions;
my $subject_header = shift @instructions;
my $head = Mail::Header->new ( [ $from_header, "Subject" => $subject_header ]);
$head->add("Subject", $subject_header);
for (my $i = 0; $i <= $#instructions; $i++) {
    push @dear_indexes,     $i if $instructions[$i] =~ /^\s*Dear <member>/;
    push @addr_indexes,     $i if $instructions[$i] =~ /^\s*E-mail:/;
    push @token_indexes,    $i if $instructions[$i] =~ /^\s*Vote token:/;
}

open RECIPS, "<$ARGV[0]" || die "Cannot open file $ARGV: $!";

my $sent = 0;
my $errors = 0;

while (<RECIPS>) {
    chomp; 
    next if (/^\#/ || /^$/);

    if (!(/^ *(.*);(.*@.*);(.*) *$/)) {
        print "Error for line: $_\n";
        next;
    }
    my $identity = $1;
    my $addr = $2;
    my $token = $3;

    foreach $index (@dear_indexes) {
        $instructions[$index] = "Dear $identity,\n";
    }

    foreach $index (@addr_indexes) {
        $instructions[$index] = "E-mail: $addr\n";
    }

    foreach $index (@token_indexes) {
        $instructions[$index] = "Vote token: $token\n";
    }

    $head->replace ("To", $addr);
    my $mail = Mail::Internet->new (Header => $head, Body => \@instructions);
    unless ($mail->smtpsend ()) {
        print "Error: Could not send to $addr ($identity)!\n";
        $errors++;
    } else {
        $sent++;
    }
}

close RECIPS;

print "Mailed $sent instructions; $errors could not be mailed.\n";
