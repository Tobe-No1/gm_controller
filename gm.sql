-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: jhDB
-- ------------------------------------------------------
-- Server version	5.6.19-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `mg_base_statistic`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_base_statistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_reg_count` int(11) DEFAULT NULL COMMENT '当天用户总数',
  `online_count` int(11) DEFAULT NULL COMMENT '上线用户数',
  `charge_count` int(11) DEFAULT NULL COMMENT '充值总额',
  `user_total_count` int(11) DEFAULT NULL COMMENT '总用户数',
  `create_time` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='基本统计';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mg_config_param`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_config_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `key` varchar(20) DEFAULT NULL COMMENT '键',
  `value` varchar(30) DEFAULT NULL COMMENT '值',
  `type` tinyint(4) DEFAULT NULL COMMENT '类别：type=流水类型',
  `remark` varchar(50) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='配置表，可放参数';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_config_param`
--

LOCK TABLES `mg_config_param` WRITE;
/*!40000 ALTER TABLE `mg_config_param` DISABLE KEYS */;
INSERT INTO `mg_config_param` VALUES (1,'faka','1',1,'售(发)卡'),(2,'boka','2',1,'拨卡'),(3,'zengka','3',1,'赠卡'),(4,'chaoguan','0',2,'超管'),(5,'guangfang','1',2,'官方'),(6,'kefu','2',2,'客服'),(7,'zongdai','3',2,'总代'),(8,'gouka','4',1,'购(买)卡');
/*!40000 ALTER TABLE `mg_config_param` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mg_products`
--

DROP TABLE IF EXISTS `mg_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_products` (
  `charge_id` int(11) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '""',
  `active` char(1) NOT NULL DEFAULT '1' COMMENT '1ï¼šæ¿€æ´»',
  `pid` varchar(50) NOT NULL DEFAULT '""' COMMENT 'å•†å“åœ¨app storeä¸Šçš„product_id',
  `num` int(10) DEFAULT '0',
  `price` float NOT NULL DEFAULT '0' COMMENT 'å•†å“ä»·æ ¼',
  `desc` varchar(500) DEFAULT '""' COMMENT 'å•†å“æè¿°',
  `desc1` text,
  `desc2` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_products`
--

LOCK TABLES `mg_products` WRITE;
/*!40000 ALTER TABLE `mg_products` DISABLE KEYS */;
INSERT INTO `mg_products` VALUES (13,'500张房卡','1','15',500,260,'￥250','500张房卡','260元=500张房卡'),(14,'1000张房卡','1','16',1000,500,'￥500','1100张房卡','500元=1000张房卡'),(15,'5000张房卡','1','17',5000,2250,'￥1000','2500张房卡','2250元=5000张房卡'),(16,'10000张房卡','1','18',10000,4000,'￥1500','4500张房卡','4000元=10000张房卡'),(17,'50000张房卡','1','19',50000,15000,'￥2000','6400张房卡','15000元=50000张房卡'),(18,'1张放开-测试','1','20',1,1,'￥2500','1张房卡','1元测试');
/*!40000 ALTER TABLE `mg_products` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `mg_session`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_session` (
  `ci_session` varchar(50) NOT NULL COMMENT 'session的值',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_session`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_user` (
  `mg_user_id` int(10) NOT NULL,
  `mg_user_name` varchar(20) NOT NULL DEFAULT '""',
  `mg_user_pwd` varchar(50) NOT NULL DEFAULT '""',
  `mg_name` varchar(20) DEFAULT '""',
  `email` varchar(50) DEFAULT '""',
  `phone` varchar(50) DEFAULT '""',
  `status` int(1) DEFAULT '1',
  `last_login_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `level` int(4) DEFAULT '0',
  `p_mg_user_id` int(4) DEFAULT '0',
  `invotecode` varchar(100) DEFAULT '""',
  `mg_pwd` varchar(32) DEFAULT '',
  PRIMARY KEY (`mg_user_id`),
  UNIQUE KEY `invotecode` (`invotecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_user`
--

LOCK TABLES `mg_user` WRITE;
/*!40000 ALTER TABLE `mg_user` DISABLE KEYS */;
INSERT INTO `mg_user` VALUES (1,'admin','5cd47a89f66a7130be092ce2a18db1bb','创始人','admin@qq.com','18682336690',1,'2016-09-13 14:20:05','2013-04-18 09:43:36',0,0,'1',NULL);
/*!40000 ALTER TABLE `mg_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mg_user_account_props`
--

DROP TABLE IF EXISTS `mg_user_account_props`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_user_account_props` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mg_user_id` int(11) DEFAULT '0',
  `props_type_id` int(4) DEFAULT '36',
  `count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_user_account_props`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_user_charge` (
  `auto_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `product_id` int(11) NOT NULL,
  `rmb` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL DEFAULT '""' COMMENT 'äº¤æ˜“id',
  `order_id` varchar(50) DEFAULT NULL COMMENT 'æ”¯ä»˜æ¸ é“äº¤æ˜“id',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '1:å®Œæˆ, 0:æœªä»˜æ¬¾',
  `client_ip` varchar(20) DEFAULT '""',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_time` datetime NOT NULL,
  PRIMARY KEY (`auto_id`),
  UNIQUE KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COMMENT='ç”¨æˆ·å……å€¼';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_user_charge`
--

--
-- Table structure for table `mg_user_props_consume_history`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mg_user_props_consume_history` (
  `user_props_consume_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_id` int(11) DEFAULT '0',
  `props_type_id` int(4) NOT NULL DEFAULT '36',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `accept_user_id` int(11) DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `count` int(11) DEFAULT '0',
  `message` varchar(500) DEFAULT NULL,
  `over_time` int(11) DEFAULT '0' COMMENT '道具过期具体时间',
  `valid_hour` int(11) DEFAULT '0' COMMENT '道具有效时间（单位：小时）',
  `flag` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`user_props_consume_history_id`),
  KEY `NewIndex1` (`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mg_user_props_consume_history`
--

--
-- Table structure for table `recharge_callback_record`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recharge_callback_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(6000) CHARACTER SET utf8 DEFAULT '""',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `NewIndex1` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recharge_callback_record`
--

LOCK TABLES `recharge_callback_record` WRITE;
/*!40000 ALTER TABLE `recharge_callback_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `recharge_callback_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_props_consume_history`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_props_consume_history` (
  `user_props_consume_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_id` int(11) DEFAULT '0',
  `props_type_id` int(4) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `accept_user_id` int(11) DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `count` int(11) DEFAULT '0',
  `message` varchar(500) DEFAULT NULL,
  `over_time` int(11) DEFAULT '0' COMMENT '道具过期具体时间',
  `valid_hour` int(11) DEFAULT '0' COMMENT '道具有效时间（单位：小时）',
  `flag` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`user_props_consume_history_id`),
  KEY `NewIndex1` (`create_time`)
) ENGINE=MyISAM AUTO_INCREMENT=333 DEFAULT CHARSET=utf8 COMMENT='道具消耗记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_props_consume_history`
--

--
-- Table structure for table `user_props_purchase_history`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_props_purchase_history` (
  `user_props_purchase_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_id` int(4) DEFAULT '0',
  `props_type_id` int(11) NOT NULL DEFAULT '0',
  `send_user_id` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '购买个数',
  `props_price` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `props_valid_hour` int(11) DEFAULT '0' COMMENT '道具有效时间（单位：小时）',
  `send_flag` int(1) DEFAULT '0' COMMENT '是否是赠送的道具，0：否，1：是',
  `flag` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`user_props_purchase_history_id`),
  KEY `user_id` (`user_id`,`props_type_id`),
  KEY `NewIndex1` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='道具购买记录';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_props_purchase_history`
--

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-04 10:14:23
