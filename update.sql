

-- 游戏库
CREATE TABLE `recommend` (
  `account` varchar(100) NOT NULL,
  `recommend_id` int(10) NOT NULL,
  `agent_id` int(10) NOT NULL,
  `create_time` int(10) DEFAULT '0',
  PRIMARY KEY (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8