#!/bin/bash

awk 'BEGIN{ printf "BEGIN; SET NAMES 'utf8';\n\
INSERT into `foundation`.`electorate`\n\
	   (fullname, email)\n\
       VALUES \n"} { print "(" $0 ")," }\
END{ print ";\n\
COMMIT;"}'
