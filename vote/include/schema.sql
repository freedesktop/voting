 CREATE database elections;

 use elections;

 CREATE TABLE `elections` (
   `id` int(11) NOT NULL auto_increment,
   `type` enum('elections','referendum') NOT NULL default 'elections',
   `name` varchar(150) NOT NULL default '',
   `voting_start` datetime default NULL,
   `voting_end` datetime default '0000-00-00 00:00:00',
   `choices_nb` int(11) NOT NULL default '0',
   'enforce_nb'	BOOL NOT NULL default '0',
   `question` text NOT NULL,
   PRIMARY KEY  (`id`) 
 ) DEFAULT CHARSET=utf8;

 CREATE TABLE `election_anon_tokens` (
   `id` int(11) NOT NULL auto_increment,
   `anon_token` varchar(200) NOT NULL default '',
   `election_id` int(11) NOT NULL default '0',
   PRIMARY KEY  (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=903 DEFAULT CHARSET=utf8;

 CREATE TABLE `election_choices` (
   `id` int(11) NOT NULL auto_increment,
   `election_id` int(11) NOT NULL default '0',
   `choice` varchar(150) NOT NULL default '',
   PRIMARY KEY  (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

 CREATE TABLE `election_tmp_tokens` (
   `id` int(11) NOT NULL auto_increment,
   `election_id` int(11) NOT NULL default '0',
   `member_id` int(11) NOT NULL default '0',
   `tmp_token` varchar(200) NOT NULL default ''
   PRIMARY KEY  (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 
 from members database we prepare anon tokens
 then insert those anon tokens to database 
 of course before a new election record should be created since its id is needed for anon_tokens
 and election_choices are to be inserted
 rest is handled by itself iirc 
*/

 CREATE TABLE `election_votes` (
   `id` int(11) NOT NULL auto_increment,
   `choice_id` int(11) NOT NULL default '0',
   `anon_id` int(11) NOT NULL default '0',
   `preference` int(11) NOT NULL default '0',
   PRIMARY KEY  (`id`)
 ) ENGINE=InnoDB;

 CREATE TABLE `election_results` (
   `id` int(11) NOT NULL auto_increment,
   `election_id` int(11) NOT NULL default '0',
   `result` text NOT NULL,
   PRIMARY KEY  (`id`)
 ) DEFAULT CHARSET=utf8;

 /* this user has elevated rights - not to be used from php */
 CREATE USER 'voting'@'localhost' IDENTIFIED BY 'secure_pw';
 GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON elections.* TO 'voting'@'localhost';

 /* this user has lowest-possible rights - to be used from php */
 CREATE USER 'web'@'localhost' IDENTIFIED BY 'whatever';
 GRANT SELECT ON elections.* TO 'web'@'localhost';
 GRANT SELECT,INSERT ON elections.election_anon_tokens TO 'web'@'localhost';
 GRANT SELECT,INSERT ON elections.election_votes TO 'web'@'localhost';
 GRANT SELECT,DELETE ON elections.election_tmp_tokens TO 'web'@'localhost';

