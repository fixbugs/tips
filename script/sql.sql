CREATE TABLE `user` (
`id` BIGINT(20) NOT NULL,
`username` CHAR(32) NOT NULL COMMENT '用户名',
`password` CHAR(32) NOT NULL COMMENT '密码',
`truename CHAR(40) NOT NULL DEFAULT '' COMMENT '真实姓名',
`email` VARCHAR(60) NOT NULL DEFAULT '' COMMENT '邮箱',
`create_time` INT(11) NOT NULL DEFAULT '0',
`update_time` INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `username` (`username`),
KEY `email` (`email`),
KEY `create_update_time` (`create_time`,`update_time`),
KEY `user_name_password` (`username`,`password`),
UNIQUE KEY `username` (`username`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT='用户表';
