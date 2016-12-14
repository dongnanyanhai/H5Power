DROP TABLE IF EXISTS `{prefix}content`;
CREATE TABLE IF NOT EXISTS `{prefix}content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}content_1`;
CREATE TABLE `{prefix}content_1` (
  `id` int(10) unsigned NOT NULL,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `catid2` varchar(255) NOT NULL,
  `modelid` smallint(5) NOT NULL,
  `title` varchar(80) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `keywords` char(40) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `url` char(100) NOT NULL,
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `hits` smallint(5) unsigned NOT NULL DEFAULT '0',
  `sysadd` tinyint(1) NOT NULL COMMENT '是否后台添加',
  `userid` smallint(8) NOT NULL,
  `username` char(20) NOT NULL,
  `inputtime` bigint(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` bigint(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY `id` (`id`),
  KEY `admin` (`modelid`,`status`,`listorder`,`updatetime`),
  KEY `catid` (`catid`,`status`,`updatetime`),
  KEY `member` (`userid`,`modelid`,`status`,`sysadd`,`updatetime`),
  KEY `status` (`status`,`updatetime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}content_1_verify`;
CREATE TABLE IF NOT EXISTS `{prefix}content_1_verify` (
  `id` int(10) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `modelid` smallint(5) NOT NULL,
  `userid` mediumint(8) NOT NULL,
  `username` CHAR( 20 ) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tablename` char(30) NOT NULL,
  `updatetime` bigint(10) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `content` longtext NOT NULL,
  KEY `id` (`id`),
  KEY `catid` (`catid`),
  KEY `modelid` (`modelid`,`updatetime`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}content_1_extend`;
CREATE TABLE IF NOT EXISTS `{prefix}content_1_extend` (
  `id` int(10) NOT NULL,
  `catid` smallint(5) NOT NULL,
  `relation` varchar(255) NOT NULL,
  `verify` varchar(255) NOT NULL,
  KEY `id` (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}search`;
CREATE TABLE IF NOT EXISTS `{prefix}search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` SMALLINT(5) NOT NULL,
  `catid` SMALLINT(5) NOT NULL,
  `params` varchar(32) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `addtime` bigint(10) unsigned NOT NULL DEFAULT '0',
  `sql` text NOT NULL,
  `total` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `params` (`params`,`addtime`),
  KEY `modelid` (`modelid`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}category`;
CREATE TABLE `{prefix}category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `typeid` tinyint(1) NOT NULL COMMENT '类别(1内容,2单页,3外链)',
  `catmid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目自身模型ID',
  `catsid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目扩展设置ID',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目内容模型ID',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `arrparentid` varchar(255) NOT NULL,
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在子栏目，1，存在',
  `arrchildid` varchar(255) NOT NULL,
  `catname` varchar(30) NOT NULL COMMENT '栏目名称',
  `image` varchar(100) NOT NULL COMMENT '图片',
  `content` mediumtext NOT NULL COMMENT '单网页内容',
  `meta_title` varchar(255) NOT NULL,
  `meta_image` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_url` text NOT NULL,
  `meta_description` text NOT NULL,
  `catdir` varchar(30) NOT NULL COMMENT '栏目URL目录',
  `url` varchar(100) NOT NULL COMMENT 'URL地址',
  `urlpath` varchar(255) NOT NULL,
  `items` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '内容数量',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否为菜单',
  `categorytpl` varchar(50) NOT NULL,
  `listtpl` varchar(50) NOT NULL,
  `showtpl` varchar(50) NOT NULL,
  `setting` text NOT NULL,
  `pagesize` smallint(5) NOT NULL,
  PRIMARY KEY (`catid`),
  KEY `listorder` (`listorder`,`child`),
  KEY `ismenu` (`ismenu`),
  KEY `parentid` (`parentid`),
  KEY `site` (`site`),
  KEY `modelid` (`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}block`;
CREATE TABLE `{prefix}block` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `name` varchar(50) NOT NULL,
  `setting` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}pluginmenu`;
CREATE TABLE IF NOT EXISTS `{prefix}pluginmenu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `arrparentid` varchar(255) NOT NULL,
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在子栏目，1，存在',
  `arrchildid` varchar(255) NOT NULL,
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  `isblank` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用',
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `query` text NOT NULL,
  `description` text NOT NULL,
  `listorder` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ismenu` (`ismenu`),
  KEY `parentid` (`parentid`),
  KEY `site` (`site`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}model`;
CREATE TABLE `{prefix}model` (
  `modelid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `typeid` tinyint(3) NOT NULL,
  `modelname` char(30) NOT NULL,
  `tablename` varchar(30) NOT NULL,
  `categorytpl` varchar(30) NOT NULL,
  `listtpl` varchar(30) NOT NULL,
  `showtpl` varchar(30) NOT NULL,
  `joinid` smallint(5) NULL,
  `setting` TEXT NULL,
  PRIMARY KEY (`modelid`),
  KEY `typeid` (`typeid`),
  KEY `site` (`site`),
  KEY `joinid` (`joinid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}model_field`;
CREATE TABLE `{prefix}model_field` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `type` varchar(15) NOT NULL,
  `length` char(10) NOT NULL,
  `indexkey` varchar(10) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  `tips` text NOT NULL,
  `not_null` tinyint(1) NOT NULL DEFAULT '0',
  `pattern` varchar(255) NOT NULL,
  `errortips` varchar(255) NOT NULL,
  `formtype` varchar(20) NOT NULL,
  `setting` mediumtext NOT NULL,
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `modelid` (`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}member`;
CREATE TABLE `{prefix}member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(20) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `salt` CHAR(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `groupid` smallint(5) NOT NULL DEFAULT '1',
  `modelid` smallint(5) NOT NULL,
  `credits` int(10) NOT NULL,
  `regdate` bigint(10) unsigned NOT NULL DEFAULT '0',
  `regip` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `randcode` varchar(32) NOT NULL,
  `lastloginip` varchar(15) NOT NULL,
  `lastlogintime` bigint(10) NOT NULL,
  `loginip` varchar(15) NOT NULL,
  `logintime` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `groupid` (`groupid`),
  KEY `status` (`status`),
  KEY `modelid` (`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}member_count`;
CREATE TABLE `{prefix}member_count` (
  `id` mediumint(8) NOT NULL,
  `post` mediumint(5) NOT NULL,
  `pms` mediumint(5) NOT NULL,
  `updatetime` bigint(10) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}member_group`;
CREATE TABLE `{prefix}member_group` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `credits` mediumint(8) NOT NULL,
  `allowpost` mediumint(8) NOT NULL,
  `allowpms` mediumint(8) NOT NULL,
  `allowattachment` tinyint(1) NOT NULL,
  `postverify` tinyint(1) NOT NULL,
  `auto` tinyint(1) NOT NULL DEFAULT '0',
  `filesize` smallint(5) NOT NULL,
  `listorder` tinyint(3) NOT NULL,
  `disabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{prefix}member_group` VALUES('1','新手上路','0','3','1','0','1','0','5','0','0');
INSERT INTO `{prefix}member_group` VALUES('2','普通会员','20','1','0','0','1','0','10','0','0');
INSERT INTO `{prefix}member_group` VALUES('3','中级会员','50','10','0','0','0','0','20','0','0');
INSERT INTO `{prefix}member_group` VALUES('4','高级会员','100','12','0','1','0','0','50','0','0');
INSERT INTO `{prefix}member_group` VALUES('5','金牌会员','200','100','10','1','0','0','0','0','0');

DROP TABLE IF EXISTS `{prefix}member_pms`;
CREATE TABLE `{prefix}member_pms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sendname` varchar(30) NOT NULL DEFAULT '',
  `sendid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `toname` varchar(30) NOT NULL DEFAULT '',
  `toid` mediumint(8) NOT NULL,
  `isadmin` tinyint(1) NOT NULL,
  `title` varchar(60) NOT NULL DEFAULT '',
  `sendtime` bigint(10) unsigned NOT NULL DEFAULT '0',
  `hasview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `senddel` mediumint(8) NOT NULL,
  `todel` mediumint(8) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `sendtime` (`sendtime`),
  KEY `sendid` (`sendid`),
  KEY `hasview` (`hasview`),
  KEY `isadmin` (`isadmin`),
  KEY `toid` (`toid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{prefix}oauth`;
CREATE TABLE `{prefix}oauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL DEFAULT '',
  `oauth_openid` varchar(80) NOT NULL DEFAULT '',
  `oauth_name` varchar(30) NOT NULL DEFAULT '',
  `oauth_data` text NOT NULL,
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `logintimes` bigint(10) unsigned NOT NULL DEFAULT '0',
  `logintime` bigint(10) unsigned NOT NULL DEFAULT '0',
  `addtime` bigint(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `site` (`oauth_openid`,`oauth_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{prefix}pluginmenu` (`id`, `site`, `parentid`, `arrparentid`, `child`, `arrchildid`, `ismenu`, `isblank`, `name`, `url`, `query`, `description`, `listorder`) VALUES
(1, 1, 0, '', 1, '', 1, 0, '模块功能', '#', '#', '', 0),
(2, 1, 1, '', 0, '', 1, 0, '栏目管理', '{namespace}/admin_category/index', '', '', 1),
(3, 1, 1, '', 0, '', 1, 0, '模块配置', '{namespace}/admin_block/index', '', '', 2),
(4, 1, 1, '', 0, '', 1, 0, '模块菜单', '{namespace}/admin_menu/index', '', '', 3),
(5, 1, 1, '', 0, '', 1, 0, '菜单模型', '{namespace}/admin_model/index', '&amp;typeid=5', '', 4),
(6, 1, 1, '', 0, '', 1, 0, '内容模型', '{namespace}/admin_model/index', '', '', 5),
(7, 1, 1, '', 0, '', 1, 0, '表单模型', '{namespace}/admin_model/index', '&amp;typeid=3', '', 6),
(8, 1, 0, '', 1, '', 1, 0, '会员管理', '#', '#', '', 0),
(9, 1, 8, '', 0, '', 1, 0, '会员列表', '{namespace}/admin_member/index', '', '', 1),
(10, 1, 8, '', 0, '', 1, 0, '短 消 息', '{namespace}/admin_member/pms', '', '', 1),
(11, 1, 8, '', 0, '', 1, 0, '会 员 组', '{namespace}/admin_member/group', '', '', 1),
(12, 1, 8, '', 0, '', 1, 0, '会员模型', '{namespace}/admin_model/index', '&amp;typeid=2', '', 1),
(13, 1, 8, '', 0, '', 1, 0, '会员扩展', '{namespace}/admin_model/index', '&amp;typeid=4', '', 1),
(14, 1, 8, '', 0, '', 1, 0, '会员配置', '{namespace}/admin_member/config', '&amp;type=user', '', 1);