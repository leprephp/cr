DROP TABLE IF EXISTS `cr_languages`;
CREATE TABLE `cr_languages` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cr_nodes`;
CREATE TABLE `cr_nodes` (
  `node_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nodetype_language_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(100) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `properties` json NOT NULL,
  PRIMARY KEY (`node_id`),
  KEY `nodetype_language_id` (`nodetype_language_id`),
  CONSTRAINT `cr_nodes_ibfk_1` FOREIGN KEY (`nodetype_language_id`) REFERENCES `cr_nodetypes_languages` (`nodetype_language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cr_nodetypes`;
CREATE TABLE `cr_nodetypes` (
  `nodetype_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`nodetype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cr_nodetypes_languages`;
CREATE TABLE `cr_nodetypes_languages` (
  `nodetype_language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nodetype_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`nodetype_language_id`),
  KEY `nodetype_id` (`nodetype_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `cr_nodetypes_languages_ibfk_1` FOREIGN KEY (`nodetype_id`) REFERENCES `cr_nodetypes` (`nodetype_id`),
  CONSTRAINT `cr_nodetypes_languages_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `cr_languages` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cr_languages` (`language_id`, `name`) VALUES ('1', 'it_IT');

INSERT INTO `cr_nodes` (`node_id`, `nodetype_language_id`, `title`, `slug`, `path`, `properties`) VALUES ('1', '1', 'Home Page', '', '/', '{}'),
('2', '1', 'Contatti', 'contatti', '/contatti/', '{}'),
('3', '1', 'Chi siamo', 'chi-siamo', '/chi-siamo/', '{}');

INSERT INTO `cr_nodetypes` (`nodetype_id`, `name`) VALUES ('1', 'page');

INSERT INTO `cr_nodetypes_languages` (`nodetype_language_id`, `nodetype_id`, `language_id`) VALUES ('1', '1', '1');
