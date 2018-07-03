

-- 游戏库
CREATE TABLE `recommend` (
  `account` varchar(100) NOT NULL,
  `recommend_id` int(10) NOT NULL,
  `agent_id` int(10) NOT NULL,
  `create_time` int(10) DEFAULT '0',
  PRIMARY KEY (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


alter table mg_user add column agree_privary tinyint(4) default 0;
alter table mg_user add column agree_time int(10) default 0;