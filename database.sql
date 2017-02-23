-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: localhost    Database: app
-- ------------------------------------------------------
-- Server version	5.7.9

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
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` char(200) NOT NULL COMMENT '评论',
  `approve` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '赞同数',
  `review_id` int(10) unsigned NOT NULL,
  `creator_id` int(10) unsigned NOT NULL COMMENT '评论者ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='评论表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `opinion`
--

DROP TABLE IF EXISTS `opinion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `opinion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attitude` tinyint(4) NOT NULL COMMENT '态度',
  `judger_id` int(10) unsigned NOT NULL COMMENT '评价者',
  `review_id` int(10) unsigned NOT NULL COMMENT '点评ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='看法表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `picture`
--

DROP TABLE IF EXISTS `picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `src` varchar(255) NOT NULL COMMENT '引用',
  `width` int(4) unsigned NOT NULL COMMENT '宽度',
  `height` int(4) unsigned NOT NULL COMMENT '高度',
  `type` tinyint(1) unsigned NOT NULL COMMENT '类型',
  `class` tinyint(1) unsigned NOT NULL COMMENT '分类',
  `uploader_id` int(10) unsigned NOT NULL COMMENT '上传者ID',
  `upload_time` int(10) unsigned NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `source_UNIQUE` (`src`)
) ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='图片表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `grade` int(1) unsigned NOT NULL COMMENT '评分',
  `content` text NOT NULL COMMENT '评价',
  `photo` json DEFAULT NULL COMMENT '照片',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `creator_id` int(10) unsigned NOT NULL COMMENT '评论者ID',
  `scenery_id` int(10) unsigned NOT NULL COMMENT '景点ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='评价表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `scenery`
--

DROP TABLE IF EXISTS `scenery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scenery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(16) NOT NULL COMMENT '名称',
  `address` char(8) NOT NULL COMMENT '所在地区',
  `belong` char(8) NOT NULL COMMENT '所属景区',
  `cover` json DEFAULT NULL COMMENT '封面',
  `introduce` text COMMENT '介绍',
  `sumscore` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价总分',
  `sumtimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价次数',
  `commend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '推荐量',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `creator_id` int(10) unsigned NOT NULL COMMENT '创建者ID',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='景点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `profile` varchar(255) NOT NULL COMMENT '头像',
  `nickname` varchar(12) NOT NULL COMMENT '昵称',
  `gender` tinyint(1) unsigned DEFAULT NULL COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `profession` varchar(12) DEFAULT NULL COMMENT '职业',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `ip_address` varchar(16) DEFAULT NULL COMMENT 'IP地址',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_UNIQUE` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8 COMMENT='用户表';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-19 10:52:51
