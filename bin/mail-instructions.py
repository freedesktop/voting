#!/usr/bin/python

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

import smtplib
import sys
from email.mime.text import MIMEText

re_template_fixes = [
    (re.compile(r'^(\s*Dear )<member>'), '\1 $member'),
    (re.compile(r'^(\s*E-mail:)'), '\1 $email'),
    (re.compile(r'^(\s*Vote token:)'), '\1 $token')
]

sub email_it(recipients_file, instructions_file):
    instructions = file(instructions_file, "r").read().splitlines()

    from_header = instructions.pop(0)
    subject_header = instructions.pop(0)

    template = string.Template("\n".join(instructions))
    for re_fix in re_template_fixes:
        template = re_fix[0].sub(re_fix[1], template)

    f = file(recipients_file, "r")

    sent = 0
    errors = 0
    s = None

    for line in f:
        l = line.strip()
        if l.beginswith("#") or l = "":
            continue

        l = l.split(";", 2)
        if len(l) <> 2:
            print "ERROR in recipients file, invalid line:"
            print line,
            continue

        member_name, member_email, token = l

        msg = MIMEText(template.substitute(member=member_name, email=member_email, token=token))
        msg['To'] = member_email
        msg['From'] = from_header
        msg['Subject'] = subject_header

        if s is None:
            s = smtplib.SMTP()

        try:
            s.sendmail(from_header, ['olav@bkor.dhs.org'], msg.as_string())
        except smtplib.SMTPException:
            print "Error: Could not send to %s (%s)!" % (member_email, member_name)
            errors += 1
        else:
            sent += 1

    if s:
        s.quit()

    f.close()

    print "Mailed %s instructions; %s could not be mailed." % (sent, errors)


if __name__ == '__main__':
    if len(sys.argv) != 2:
        print "Usage: mail-instructions.py <recipient list> <instructions template>"
        sys.exit(1)

    email_it(sys.argv[1], sys.argv[2])
