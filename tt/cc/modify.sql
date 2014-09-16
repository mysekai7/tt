ALTER TABLE `posts` ADD `recommend` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `attached` ;

ALTER TABLE `posts` ADD INDEX ( `recommend` ) ;