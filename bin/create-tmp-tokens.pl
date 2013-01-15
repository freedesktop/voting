#!/usr/bin/env perl
use Text::CSV;
use IO::Handle;

# Script to create temporary tokens for voters.
#
# How to use this script
# ======================
#
# Look for the elections/referendum id in the database. Like
# "SELECT * FROM elections"
# Look for the current one and remember its id.
#
#
# If don't don't have a row for the current election yet, consider using
# BEGIN; SET NAMES 'utf8';
# INSERT INTO elections (name, voting_start, voting_end, choices_nb, question, enforce_nb)
# VALUES ('2010 Spring Board of Directors Election',
#   TIMESTAMP('2009-06-08 00:00:00'),
#   TIMESTAMP('2009-06-22 23:59:59'),
#   7,
#   'Which candidates would you like to see in the TDF Board?',
#   0);
#
# INSERT INTO election_choices (election_id, choice)
# VALUES ((SELECT LAST_INSERT_ID()), 'Firstname Lastname1'),
# ((SELECT LAST_INSERT_ID()), 'Firstname Lastname2'),
# ((SELECT LAST_INSERT_ID()), 'Youget Theidea');
# And "COMMIT;" if there were no errors. Or "ROLLBACK;" if there were errors.
#
# Likely you need to update the member table - easiest is to delete
# the old and create from scratch:
#
# DROP TABLE foundationmembers;
# CREATE TABLE foundationmembers (
#	   id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
#	   email VARCHAR(100)
#	   );
#
# $ ./mcm list --active --format=sql > members.sql
# $ mysql --user=voting -p < members.sql
#
# You should then use this script like this:
# $ ./mcm list --active --format=csv-email > members.csv
# $ ./create-tmp-tokens.pl 42 tokens.sql maildata.txt < members.csv
#
# where 42 is the elections/referendum id in the database.
#
# tokens.txt now contains SQL statements you can use to create the temporary
# tokens in the database. You can do that with, e.g.
# mysql -u voting -p election2011 < tokens.sql
#
# maildata.txt now contains the data that will be used by mail-instructions.pl
#
# NOTE: this has changed from the original GNOME version of this
# script, which was retrieving members directly from the database

die "Usage: create-tmp-tokens.pl <election id> <output file for tokens> <output file for mail data>\n" unless $#ARGV == 2;

my $election_id = $ARGV[0];

my $stdin = IO::Handle->new_from_fd(fileno(STDIN),"r");
binmode($stdin,":utf8");
open TOKENS, ">:encoding(utf8)", "$ARGV[1]" || die "Cannot open file $ARGV[1]: $!";
open MAILDATA, ">:encoding(utf8)", "$ARGV[2]" || die "Cannot open file $ARGV[2]: $!";

my $csv = Text::CSV->new ( { binary => 1 } ) || die "Cannot use CSV: ".Text::CSV->error_diag ();
my $id = 1;

print TOKENS "SET NAMES 'utf8';\n";
while ( my $row = $csv->getline( $stdin ) ) {
    @chars = ( "A" .. "Z", "a" .. "z", 0 .. 9 );
    $token = join("", @chars[ map { rand @chars } ( 1 .. 10 ) ]);

    print TOKENS "INSERT INTO election_tmp_tokens (election_id, member_id, tmp_token) VALUES ($election_id,$id,'$token');\n";
    print MAILDATA "$row->[0];$row->[1];$token\n";

    $id++;
}

close TOKENS;
close MAILDATA;
close $stdin;
