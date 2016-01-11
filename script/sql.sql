CREATE DATABASE `tips` if not exists;
USE tips;

CREATE TABLE `user` if not exists (
`user_id` BIGINT(20) NOT NULL,
`username` CHAR(32) NOT NULL COMMENT '用户名',
`password` CHAR(32) NOT NULL COMMENT '密码',
`truename` CHAR(40) NOT NULL DEFAULT '' COMMENT '真实姓名',
`email` VARCHAR(80) NOT NULL DEFAULT '' COMMENT '邮箱',
`is_enabled` INT(1) NOT NULL DEFAULT 1 COMMENT '1为允许,0为禁止',
`create_time` INT(11) NOT NULL DEFAULT '0',
`update_time` INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`user_id`),
KEY `create_update_time` (`create_time`,`update_time`),
KEY `user_name_password` (`username`,`password`),
UNIQUE KEY `username` (`username`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='用户表';

CREATE TABLE `log` if not exists(
`id` BIGINT(20) NOT NULL,
`lever` CHAR(10) NOT NULL COMMENT '等级',
`message` CHAR(80) NOT NULL COMMENT '信息内容',
`user_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
`create_time` INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `lever` (`lever`),
KEY `create_time` (`create_time`),
KEY `user_id` (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='日志表';

CREATE TABLE `api_log` if not exists(
`id` BIGINT(20) NOT NULL,
`lever` CHAR(10) NOT NULL COMMENT '等级',
`error_num` INT(4) NOT NULl DEFAULT '0' COMMENT '错误状态码',
`message` CHAR(80) NOT NULL COMMENT '信息内容',
`user_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
`create_time` INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `lever` (`lever`),
KEY `create_time` (`create_time`),
KEY `user_id` (`user_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='API日志表';

CREATE TABLE `trace` if not exists(
`id` BIGINT(20) NOT NULL,
`lever` CHAR(10) NOT NULL COMMENT '等级',
`message` CHAR(80) NOT NULL COMMENT '信息内容',
`user_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '用户ID',
`obj_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '对象ID',
`obj_type` CHAR(20) NOT NULL DEFAULT 'other' COMMENT '对象操作类型,add,edit,deleted,other',
`create_time` INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `lever` (`lever`),
KEY `create_time` (`create_time`),
KEY `user_id` (`user_id`),
KEY `obj_id_type` (`obj_id`,`obj_type`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='操作日志表';

CREATE TABLE `tips` if not exists(
`tips_id` BIGINT(20) NOT NULL,
`parent_id` BIGINT(20) NOT NULL DEFAULT '0' COMMENT '父ID',
`tips_message` TEXT NOT NULL COMMENT '信息内容',
`user_id` BIGINT(20) NOT NULL COMMENT '用户ID',
`create_time` INT(11) NOT NULL DEFAULT '0',
`edit_time` INT(11) NOT NULL DEFAULT '0',
`status` CHAR(20) NOT NULL DEFAULT 'nonstart' COMMENT '任务状态,nonstart->start->executions->finished,unneed',
PRIMARY KEY (`tips_id`),
KEY `tips_paranet_id` (`tips_id`,`parent_id`),
KEY `tips_user_status` (`tips_id`,`user_id`,`status`),
KEY `create_time` (`create_time`),
KEY `edit_time` (`edit_time`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='提示任务表';
