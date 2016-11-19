DROP TABLE IF EXISTS `{pre}ip`;
CREATE TABLE IF NOT EXISTS `{pre}ip` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `addtime` bigint(10) NOT NULL,
  `endtime` bigint(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}tag`;
CREATE TABLE IF NOT EXISTS `{pre}tag` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `letter` varchar(200) NOT NULL,
  `listorder` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `letter` (`letter`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}tag_cache`;
CREATE TABLE IF NOT EXISTS `{pre}tag_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `params` varchar(32) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `addtime` bigint(10) unsigned NOT NULL DEFAULT '0',
  `sql` mediumtext NOT NULL,
  `total` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `params` (`params`,`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}block`;
CREATE TABLE `{pre}block` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `type` tinyint(1) NOT NULL,
  `name` varchar(50) NOT NULL,
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}linkage`;
CREATE TABLE `{pre}linkage` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `name` varchar(30) NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `child` tinyint(1) NOT NULL,
  `arrchilds` varchar(200) NOT NULL,
  `keyid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `list` (`site`,`parentid`,`keyid`,`listorder`),
  KEY `keyid` (`site`,`keyid`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `{pre}member`;
-- CREATE TABLE `{pre}member` (
--   `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
--   `username` char(20) NOT NULL DEFAULT '',
--   `password` char(32) NOT NULL DEFAULT '',
--   `salt` CHAR(10) NOT NULL,
--   `email` varchar(100) NOT NULL,
--   `nickname` varchar(50) NOT NULL,
--   `avatar` varchar(100) NOT NULL DEFAULT '',
--   `groupid` smallint(5) NOT NULL DEFAULT '1',
--   `modelid` smallint(5) NOT NULL,
--   `credits` int(10) NOT NULL,
--   `regdate` bigint(10) unsigned NOT NULL DEFAULT '0',
--   `regip` varchar(50) NOT NULL,
--   `status` tinyint(1) NOT NULL DEFAULT '0',
--   `randcode` varchar(32) NOT NULL,
--   `lastloginip` varchar(15) NOT NULL,
--   `lastlogintime` bigint(10) NOT NULL,
--   `loginip` varchar(15) NOT NULL,
--   `logintime` bigint(10) NOT NULL,
--   PRIMARY KEY (`id`),
--   KEY `username` (`username`),
--   KEY `groupid` (`groupid`),
--   KEY `status` (`status`),
--   KEY `modelid` (`modelid`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `{pre}member_count`;
-- CREATE TABLE `{pre}member_count` (
--   `id` mediumint(8) NOT NULL,
--   `post` mediumint(5) NOT NULL,
--   `pms` mediumint(5) NOT NULL,
--   `updatetime` bigint(10) NOT NULL,
--   KEY `id` (`id`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `{pre}member_group`;
-- CREATE TABLE `{pre}member_group` (
--   `id` smallint(5) NOT NULL AUTO_INCREMENT,
--   `name` varchar(20) NOT NULL,
--   `credits` mediumint(8) NOT NULL,
--   `allowpost` mediumint(8) NOT NULL,
--   `allowpms` mediumint(8) NOT NULL,
--   `allowattachment` tinyint(1) NOT NULL,
--   `postverify` tinyint(1) NOT NULL,
--   `auto` tinyint(1) NOT NULL DEFAULT '0',
--   `filesize` smallint(5) NOT NULL,
--   `listorder` tinyint(3) NOT NULL,
--   `disabled` tinyint(1) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- INSERT INTO `{pre}member_group` VALUES('1','新手上路','0','3','1','0','1','0','5','0','0');
-- INSERT INTO `{pre}member_group` VALUES('2','普通会员','20','1','0','0','1','0','10','0','0');
-- INSERT INTO `{pre}member_group` VALUES('3','中级会员','50','10','0','0','0','0','20','0','0');
-- INSERT INTO `{pre}member_group` VALUES('4','高级会员','100','12','0','1','0','0','50','0','0');
-- INSERT INTO `{pre}member_group` VALUES('5','金牌会员','200','100','10','1','0','0','0','0','0');

-- DROP TABLE IF EXISTS `{pre}member_pms`;
-- CREATE TABLE `{pre}member_pms` (
--   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `sendname` varchar(30) NOT NULL DEFAULT '',
--   `sendid` mediumint(8) unsigned NOT NULL DEFAULT '0',
--   `toname` varchar(30) NOT NULL DEFAULT '',
--   `toid` mediumint(8) NOT NULL,
--   `isadmin` tinyint(1) NOT NULL,
--   `title` varchar(60) NOT NULL DEFAULT '',
--   `sendtime` bigint(10) unsigned NOT NULL DEFAULT '0',
--   `hasview` tinyint(1) unsigned NOT NULL DEFAULT '0',
--   `senddel` mediumint(8) NOT NULL,
--   `todel` mediumint(8) NOT NULL,
--   `content` text,
--   PRIMARY KEY (`id`),
--   KEY `sendtime` (`sendtime`),
--   KEY `sendid` (`sendid`),
--   KEY `hasview` (`hasview`),
--   KEY `isadmin` (`isadmin`),
--   KEY `toid` (`toid`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{pre}oauth`;
CREATE TABLE `{pre}oauth` (
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


DROP TABLE IF EXISTS `{pre}plugin`;
CREATE TABLE IF NOT EXISTS `{pre}plugin` (
  `pluginid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(1) NOT NULL,
  `markid` smallint(5) NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `controller` varchar(30) NOT NULL DEFAULT '',
  `dir` varchar(30) NOT NULL,
  `author` varchar(100) NOT NULL DEFAULT '',
  `version` varchar(20) NOT NULL DEFAULT '',
  `disable` tinyint(1) NOT NULL DEFAULT '0',
  `setting` text NOT NULL,
  PRIMARY KEY (`pluginid`),
  KEY `dir` (`dir`),
  KEY `markid` (`markid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}position`;
CREATE TABLE `{pre}position` (
  `posid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `catid` smallint(5) unsigned DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `maxnum` smallint(5) NOT NULL DEFAULT '20',
  PRIMARY KEY (`posid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}position_data`;
CREATE TABLE `{pre}position_data` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) NOT NULL,
  `posid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `contentid` mediumint(8) NULL,
  `thumb` varchar(100) NOT NULL DEFAULT '0',
  `file` varchar(100) NOT NULL,
  `isblank` int(1) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `tstyle` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(200) NOT NULL,
  `listorder` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `posid` (`posid`),
  KEY `listorder` (`listorder`),
  KEY `catid` (`catid`),
  KEY `contentid` (`contentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}navbar`;
CREATE TABLE IF NOT EXISTS `{pre}navbar` (
  `navid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `name` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`navid`)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}navbar_data`;
CREATE TABLE IF NOT EXISTS `{pre}navbar_data` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `navid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `arrparentid` varchar(255) NOT NULL,
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在子栏目，1，存在',
  `arrchildid` varchar(255) NOT NULL,
  `thumb` varchar(100) NOT NULL DEFAULT '0',
  `file` varchar(100) NOT NULL,
  `isblank` int(1) NOT NULL,
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否为菜单',
  `title` varchar(200) DEFAULT NULL,
  `tstyle` varchar(1024) NOT NULL COMMENT '标题样式',
  `description` text NOT NULL,
  `setting` text NOT NULL,
  `url` varchar(200) NOT NULL,
  `listorder` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `navid` (`navid`),
  KEY `ismenu` (`ismenu`),
  KEY `parentid` (`parentid`),
  KEY `site` (`site`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}menu`;
CREATE TABLE IF NOT EXISTS `{pre}menu` (
  `menuid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `select` varchar(255) NOT NULL DEFAULT '',
  `namespace` varchar(255) NOT NULL DEFAULT '',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`menuid`)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}menu_data`;
CREATE TABLE IF NOT EXISTS `{pre}menu_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) NOT NULL COMMENT '站点id',
  `menuid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `arrparentid` varchar(255) NOT NULL,
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在子栏目，1，存在',
  `arrchildid` varchar(255) NOT NULL,
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  `isblank` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用',
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `query` text NOT NULL,
  `namespace` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `listorder` mediumint(8) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `menuid` (`menuid`),
  KEY `ismenu` (`ismenu`),
  KEY `parentid` (`parentid`),
  KEY `site` (`site`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}relatedlink`;
CREATE TABLE `{pre}relatedlink` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pre}role`;
CREATE TABLE `{pre}role` (
  `roleid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rolename` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `{pre}role` VALUES('1','超级管理员','超级管理员');
INSERT INTO `{pre}role` VALUES('2','总编','总编');
INSERT INTO `{pre}role` VALUES('3','编辑','编辑');

DROP TABLE IF EXISTS `{pre}user`;
CREATE TABLE `{pre}user` (
  `userid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `site` tinyint(3) DEFAULT NULL COMMENT '站点id',
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` CHAR(10) NOT NULL,
  `roleid` int(3) NOT NULL,
  `lastloginip` varchar(15) DEFAULT NULL,
  `lastlogintime` bigint(10) unsigned DEFAULT '0',
  `loginip` varchar(15) DEFAULT NULL, 
  `logintime` bigint(10) DEFAULT NULL, 
  `email` varchar(40) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL DEFAULT '',
  `usermenu` text DEFAULT NULL,
  PRIMARY KEY (`userid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{pre}user` (`username`, `password`, `roleid`, `salt`, `realname`) VALUES ('{username}', '{password}', 1, '{salt}', '网站创始人');

-- INSERT INTO `{pre}model_field` VALUES(39, 6, 'xingming', '姓名', 'VARCHAR', '255', '', 1, '', 0, '', '', 'input', 'a:2:{s:4:"size";s:3:"150";s:7:"default";s:0:"";}', 0, 0);

-- DROP TABLE IF EXISTS `{pre}member_geren`;
-- CREATE TABLE IF NOT EXISTS `{pre}member_geren` (
--   `id` mediumint(8) NOT NULL,
--   `xingming` varchar(255) DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- INSERT INTO `{pre}model` VALUES(6, 1, 2, '个人会员', 'member_geren', 'category_geren.html', 'list_geren.html', 'show_geren.html', NULL, '');

INSERT INTO `{pre}menu` (`menuid`, `site`, `name`, `url`, `select`, `namespace`, `ismenu`) VALUES
(1, 1, '管理首页', 'admin/index/main', '2', '', 1),
(2, 1, '核心管理', 'admin/plugin/index', '4', '', 1);

INSERT INTO `{pre}menu_data` (`id`, `site`, `menuid`, `parentid`, `arrparentid`, `child`, `arrchildid`, `ismenu`, `isblank`, `name`, `url`, `query`, `namespace`, `description`, `listorder`) VALUES
(1, 1, 1, 0, '', 1, '2', 1, 0, '快捷菜单', '#', '#', '#', '快捷菜单', 0),
(2, 1, 1, 1, '', 0, '', 1, 0, '后台首页', 'admin/index/main', '', '', '后台首页', 0),
(3, 1, 2, 0, '', 1, '', 1, 0, '内容模块', '#', '#', '#', '', 1),
(4, 1, 2, 3, '', 0, '', 1, 0, '模块管理', 'admin/plugin/index', '', '', '', 0),
(5, 1, 2, 3, '', 0, '', 1, 0, '导航管理', 'admin/navbar/index', '', '', '', 0),
(6, 1, 2, 3, '', 0, '', 1, 0, '文字块管理', 'admin/block/index', '', '', '', 0),
(7, 1, 2, 3, '', 0, '', 1, 0, '推荐位管理', 'admin/position/index', '', '', '', 0),
(8, 1, 2, 3, '', 0, '', 0, 0, 'Tag标签管理', 'admin/tag/index', '', '', '', 0),
(9, 1, 2, 3, '', 0, '', 0, 0, '关联链接管理', 'admin/relatedlink/index', '', '', '', 0),
(10, 1, 2, 3, '', 0, '', 0, 0, '联动菜单管理', 'admin/linkage/index', '', '', '', 0),

(11, 1, 2, 0, '', 1, '', 1, 0, '系统设置', '#', '#', '#', '系统设置', 3),
(13, 1, 2, 11, '', 0, '', 1, 0, '后台菜单', 'admin/menu/index', '', '', '', 0),
(14, 1, 2, 11, '', 0, '', 1, 0, '系统相关', 'admin/index/config', '&amp;type=1', '', '', 0),
(15, 1, 2, 11, '', 0, '', 0, 0, '邮件设置', 'admin/index/config', '&amp;type=2', '', '', 0),
(16, 1, 2, 11, '', 0, '', 0, 0, '网站地图', 'admin/index/config', '&amp;type=3', '', '', 0),
(17, 1, 2, 11, '', 0, '', 0, 0, '搜索设置', 'admin/index/config', '&amp;type=4', '', '', 0),
(18, 1, 2, 11, '', 0, '', 0, 0, 'TAG相关', 'admin/index/config', '&amp;type=5', '', '', 0),
(19, 1, 2, 11, '', 0, '', 1, 0, '操作日志', 'admin/index/log', '', '', '', 0),
(20, 1, 2, 11, '', 0, '', 1, 0, '攻击日志', 'admin/index/attack', '', '', '', 0),
(21, 1, 2, 11, '', 0, '', 1, 0, '禁止访问', 'admin/ip/index', '', '', '', 0),

(22, 1, 2, 0, '', 1, '', 1, 0, '网站管理', '#', '#', '#', '', 4),
(23, 1, 2, 22, '', 0, '', 1, 0, '网站配置', 'admin/site/config', '', '', '', 0),
(24, 1, 2, 22, '', 0, '', 0, 0, '多网站管理', 'admin/site/index', '', '', '', 0),
(25, 1, 2, 22, '', 1, '', 1, 0, '添加管理员', 'admin/user/add', '', '', '', 0),
(26, 1, 2, 22, '', 1, '', 1, 0, '管理员管理', 'admin/user/index', '', '', '', 0),
(27, 1, 2, 22, '', 0, '', 0, 0, '角色权限管理', 'admin/auth/index', '', '', '', 0),
(28, 1, 2, 22, '', 0, '', 0, 0, '更新权限缓存', 'admin/auth/cache', '', '', '', 0),
(29, 1, 2, 22, '', 0, '', 0, 0, '更新SiteMap', 'admin/index/updatemap', '', '', '', 0),

(30, 1, 2, 0, '', 1, '', 0, 0, '模板管理', '#', '#', '#', '', 5),
(31, 1, 2, 30, '', 0, '', 1, 0, '网站模板', 'admin/theme/index', '', '', '', 0),
(32, 1, 2, 30, '', 0, '', 1, 0, '标签向导', 'admin/theme/demo', '', '', '', 0),
(33, 1, 2, 30, '', 0, '', 1, 0, '更新缓存', 'admin/theme/cache', '', '', '', 0);


-- (21, 1, 3, 0, '1,21,26,27,28,35,41,42,58,3,13,48,52,16', 1, '22,23,60,24,25', 1, 0, '栏目模型', '#', '#', '', '', 0),
-- (22, 1, 3, 21, '22,23,60,24,25', 0, '', 1, 0, '栏目管理', 'admin/category/index', '', 'category-index', '', 1),
-- (23, 1, 3, 21, '22,23,60,24,25', 0, '', 1, 0, '附件管理', 'admin/attachment/index', '', 'attachment-index', '', 2),
-- (24, 1, 3, 21, '22,23,60,24,25', 0, '', 1, 0, '内容模型', 'admin/model/index', '', 'model-index', '', 4),
-- (25, 1, 3, 21, '22,23,60,24,25', 0, '', 1, 0, '表单模型', 'admin/model/index', '&amp;typeid=3', 'model-index', '', 5),
-- (26, 1, 3, 0, '1,21,26,27,28,35,41,42,58,3,13,48,52,16', 0, '22,23,60,24,25', 1, 0, '内容管理', '#', '#', '', '', 0),
-- (27, 1, 3, 0, '1,21,26,27,28,35,41,42,58,3,13,48,52,16', 0, '22,23,60,24,25', 1, 0, '表单管理', '#', '#', '', '', 0),
-- (60, 1, 3, 21, '22,23,60,24,25', 0, '', 1, 0, '菜单模型', 'admin/model/index', '&amp;typeid=5', 'model-index', '', 3);

-- (35, 1, 4, 0, '1,21,26,27,28,35,41,42,58,3,13,48,52,16', 1, '36,37,38,39,40', 1, 0, '会员管理', '#', '#', '#', '', 0),
-- (36, 1, 4, 35, '36,37,38,39,40', 0, '', 1, 0, '会员列表', 'admin/member/index', '', 'member-index', '', 0),
-- (37, 1, 4, 35, '36,37,38,39,40', 0, '', 1, 0, '短 消 息', 'admin/member/pms', '', 'member-pms', '', 0),
-- (38, 1, 4, 35, '36,37,38,39,40', 0, '', 1, 0, '会 员 组', 'admin/member/group', '', 'member-group', '', 0),
-- (39, 1, 4, 35, '36,37,38,39,40', 0, '', 1, 0, '会员模型', 'admin/model/index', '&amp;typeid=2', 'model-index', '', 0),
-- (40, 1, 4, 35, '36,37,38,39,40', 0, '', 1, 0, '会员扩展', 'admin/model/index', '&amp;typeid=4', 'model-index', '', 0),
-- (41, 1, 4, 0, '1,21,26,27,28,35,41,42,58,3,13,48,52,16', 0, '36,37,38,39,40', 1, 0, '会员扩展', '#', '#', '#', '', 0),
-- (42, 1, 4, 0, '1,21,26,27,28,35,41,42,58,3,13,48,52,16', 1, '43,44,45,46,47', 1, 0, '会员配置', '#', '#', '#', '', 0),
-- (43, 1, 4, 42, '43,44,45,46,47', 0, '', 1, 0, '基本配置', 'admin/member/config', '&amp;type=user', 'member-confg', '', 0),
-- (44, 1, 4, 42, '43,44,45,46,47', 0, '', 1, 0, '注册配置', 'admin/member/config', '&amp;type=reg', 'member-confg', '', 0),
-- (45, 1, 4, 42, '43,44,45,46,47', 0, '', 1, 0, '一键登录', 'admin/member/config', '&amp;type=oauth', 'member-confg', '', 0),
-- (46, 1, 4, 42, '43,44,45,46,47', 0, '', 1, 0, '邮件模板', 'admin/member/config', '&amp;type=email', 'member-confg', '', 0),
-- (47, 1, 4, 42, '43,44,45,46,47', 0, '', 0, 0, 'UCenter', 'admin/member/config', '&amp;type=ucenter', 'member-confg', '', 0),

