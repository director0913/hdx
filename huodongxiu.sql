/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50547
Source Host           : 127.0.0.1:3306
Source Database       : huodongxiu

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-05-19 15:08:08
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cmf_activity
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity`;
CREATE TABLE `cmf_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '活动id',
  `uid` int(11) DEFAULT '0' COMMENT '活动发布者id',
  `title` varchar(255) DEFAULT NULL COMMENT '活动标题',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `begintime` int(11) DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) DEFAULT '0' COMMENT '结束时间',
  `day_num` int(11) DEFAULT '0' COMMENT '每人每天限制次数',
  `total_num` int(11) DEFAULT '0' COMMENT '总次数限制',
  `per_num` int(11) DEFAULT '0' COMMENT '每个人的总次数限制',
  `rule` longtext COMMENT '活动规则',
  `share_title` varchar(255) DEFAULT NULL COMMENT '分享标题',
  `share_img` varchar(255) DEFAULT '' COMMENT '分享图片',
  `share_des` varchar(255) DEFAULT NULL COMMENT '分享描述',
  `thumb` varchar(255) DEFAULT NULL COMMENT '封面图',
  `type` varchar(255) DEFAULT NULL COMMENT '活动类型',
  `recommend` tinyint(3) DEFAULT '0' COMMENT '是否推荐',
  `valid` tinyint(3) DEFAULT '1' COMMENT '是否有效',
  `activityprize` longtext COMMENT '活动奖励',
  `activityremark` longtext COMMENT '活动说明',
  `school_desc` longtext COMMENT '学校介绍',
  `hits` int(11) DEFAULT '0' COMMENT '访问量',
  `share_total` int(11) DEFAULT '0' COMMENT '分享次数统计',
  `join_total` int(11) DEFAULT NULL COMMENT '参与者总次数限制',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='微营销活动';

-- ----------------------------
-- Records of cmf_activity
-- ----------------------------
INSERT INTO `cmf_activity` VALUES ('1', '5', 'qqq', '1493092639', '0', '0', '1', '1', '0', '', '111', 'http://resacc.tongyishidai.com/20170425_58fec91871852.png', '111', 'http://resacc.tongyishidai.com/20170425_58fec8fdc17f1.png', 'goddess', '0', '0', '', '', '', '0', '0', '5');
INSERT INTO `cmf_activity` VALUES ('2', '5', '测试-成绩查询', '1493111611', '0', '0', '0', '0', '0', '', '测试-成绩查询', '', '测试-成绩查询', '', 'score', '0', '1', '', '', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('3', '5', '测试-成语接力', '1493112483', '1493078400', '1493510400', '2', '0', '0', '', '测试-成语接力', '', '测试-成语接力', '', 'weidiom', '0', '1', '', '', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('4', '5', '测试-我要做学霸', '1493205302', '1493164800', '1496188800', '2', '0', '0', '', '测试-我要做学霸', '', '测试-我要做学霸', '', 'challenge', '0', '1', '', '', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('5', '5', '邀请有礼-测试', '1493257428', '1493251200', '1496188800', '0', '0', '0', '&lt;p&gt;11111111111&lt;/p&gt;', '11111111111', '', '111111111', '', 'invitegift', '0', '1', '', '&lt;p&gt;11111111111111&lt;/p&gt;', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('6', '5', '有奖问答-测试', '1493258053', '1493251200', '1493510400', '0', '0', '0', '&lt;p&gt;钱钱钱&lt;/p&gt;', '去去去', '', '去去去', null, 'questionnaire', '0', '1', '', '&lt;p&gt;钱钱钱&lt;/p&gt;', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('7', '5', '奥运加油', '1493259700', '1493251200', '1493510400', '0', '11', '11', '&lt;p&gt;吾问无为谓无&lt;/p&gt;', '我问问', '', '我问问', null, 'olympicrefuel', '0', '1', '&lt;p&gt;吾问无为谓无&lt;/p&gt;', '&lt;p&gt;吾问无为谓无无无&lt;/p&gt;', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('8', '5', '刮刮乐-测试', '1493260548', '0', '0', '122', '122', '122', '&lt;p&gt;2222&lt;/p&gt;', '222', '', '2222', null, 'scratch', '0', '1', '', '', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('9', '5', '邀请函-测试', '1493261701', '1493251200', '1493510400', '0', '0', '0', '&lt;p&gt;11&lt;/p&gt;', '1', '', '1', '', 'weimeet', '0', '1', '', '', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('10', '5', '微助力-测试', '1493262113', '1493251200', '1493510400', '11', '0', '11', '&lt;p&gt;111&lt;/p&gt;', '1', '', '11', '', 'weishare', '0', '1', '', '', '', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('11', '5', '投票-测试', '1493262481', '1493251200', '1493510400', '11', '11', '0', '&lt;p&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;凄凄切切&lt;br/&gt;&lt;/p&gt;', '11', 'http://resacc.tongyishidai.com/20170427_5901608ef41b6.png', '11', 'http://resacc.tongyishidai.com/20170427_590160703fe72.png', 'yyvote', '0', '1', '', '', '', '0', '0', '11');
INSERT INTO `cmf_activity` VALUES ('12', '5', '团团购-测试', '1493292970', '0', '0', '0', '0', '0', '&lt;p&gt;嘻嘻嘻&lt;/p&gt;', '11', '', '111', null, 'groupbuy', '0', '0', '', '&lt;p&gt;嘻嘻嘻&lt;/p&gt;', '&lt;p&gt;嘻嘻嘻&lt;/p&gt;', '0', '0', null);
INSERT INTO `cmf_activity` VALUES ('13', '5', '团团购-测试', '1493292972', '0', '0', '0', '0', '0', '&lt;p&gt;嘻嘻嘻&lt;/p&gt;', '11', '', '111', null, 'groupbuy', '0', '0', '', '&lt;p&gt;嘻嘻嘻&lt;/p&gt;', '&lt;p&gt;嘻嘻嘻&lt;/p&gt;', '0', '0', null);

-- ----------------------------
-- Table structure for cmf_activity_friend
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity_friend`;
CREATE TABLE `cmf_activity_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `aid` int(11) DEFAULT '0' COMMENT '活动id',
  `from_user` varchar(255) DEFAULT NULL COMMENT '好友标识',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `createtime` int(11) DEFAULT '0' COMMENT '参与日期',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `day_num` int(11) DEFAULT '0' COMMENT '日参与次数',
  `total_num` int(11) DEFAULT '0' COMMENT '总参与次数',
  `per_num` int(11) DEFAULT '0' COMMENT '每人参与次数',
  `update_time` int(11) DEFAULT '0' COMMENT '更新日期',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `day_value` varchar(32) DEFAULT NULL COMMENT '当天数值',
  `total_value` varchar(32) DEFAULT NULL COMMENT '总共数值',
  `parent_comment` varchar(500) DEFAULT NULL COMMENT '家长评论',
  `teacher_comment` varchar(500) DEFAULT NULL COMMENT '老师评论',
  `parent_time` int(11) DEFAULT '0' COMMENT '家长评论时间',
  `teacher_time` int(11) DEFAULT '0' COMMENT '老师评论时间',
  PRIMARY KEY (`id`),
  KEY `IDX_FR_UI_AI` (`from_user`,`uid`,`aid`) USING BTREE,
  KEY `IDX_UI_AI_PE` (`uid`,`aid`,`per_num`) USING BTREE,
  KEY `IDX_AID` (`aid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='营销活动-好友互动';

-- ----------------------------
-- Records of cmf_activity_friend
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_activity_groupbuy
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity_groupbuy`;
CREATE TABLE `cmf_activity_groupbuy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT '0' COMMENT '活动id',
  `course_name` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `course_yprice` varchar(20) DEFAULT '0' COMMENT '课程原价',
  `course_nprice` varchar(32) DEFAULT '0' COMMENT '团购价',
  `course_total` int(11) DEFAULT '0' COMMENT '数量',
  `course_join_total` int(11) DEFAULT '0' COMMENT '参团人数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='团购活动规格';

-- ----------------------------
-- Records of cmf_activity_groupbuy
-- ----------------------------
INSERT INTO `cmf_activity_groupbuy` VALUES ('1', '12', '11', '11', '1', '1', '0');
INSERT INTO `cmf_activity_groupbuy` VALUES ('2', '13', '11', '11', '1', '1', '0');

-- ----------------------------
-- Table structure for cmf_activity_params
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity_params`;
CREATE TABLE `cmf_activity_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT '0' COMMENT '活动id',
  `name` varchar(255) DEFAULT NULL COMMENT '参数名称',
  `value` text COMMENT '参数值',
  `field` varchar(255) DEFAULT NULL COMMENT '字段',
  `type` varchar(255) DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8 COMMENT='活动参数配置';

-- ----------------------------
-- Records of cmf_activity_params
-- ----------------------------
INSERT INTO `cmf_activity_params` VALUES ('1', '1', '报名需填信息', '', 'other_remark', null);
INSERT INTO `cmf_activity_params` VALUES ('2', '1', '显示前多少人', '', 'show_total', null);
INSERT INTO `cmf_activity_params` VALUES ('3', '1', '每页显示条数', '', 'page_total', null);
INSERT INTO `cmf_activity_params` VALUES ('4', '1', '是否开启前台报名', '0', 'is_front_join', null);
INSERT INTO `cmf_activity_params` VALUES ('5', '1', '是否开启音乐', '0', 'miuce', null);
INSERT INTO `cmf_activity_params` VALUES ('6', '1', '是否开启报名审核', '', 'is_check', null);
INSERT INTO `cmf_activity_params` VALUES ('7', '3', '设置积分值', '1', 'pvalue', null);
INSERT INTO `cmf_activity_params` VALUES ('8', '4', '奖品1图片', '', 'prize1_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('9', '4', '奖品1名称', '', 'prize1_name', null);
INSERT INTO `cmf_activity_params` VALUES ('10', '4', '奖品1数量', '', 'prize1_total', null);
INSERT INTO `cmf_activity_params` VALUES ('11', '4', '奖品1概率', '', 'prize1_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('12', '4', '奖品1类型', '', 'prize1_type', null);
INSERT INTO `cmf_activity_params` VALUES ('13', '4', '奖品1是否必中', '0', 'prize1_only', null);
INSERT INTO `cmf_activity_params` VALUES ('14', '4', '奖品2图片', '', 'prize2_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('15', '4', '奖品2名称', '', 'prize2_name', null);
INSERT INTO `cmf_activity_params` VALUES ('16', '4', '奖品2数量', '', 'prize2_total', null);
INSERT INTO `cmf_activity_params` VALUES ('17', '4', '奖品2概率', '', 'prize2_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('18', '4', '奖品2类型', '', 'prize2_type', null);
INSERT INTO `cmf_activity_params` VALUES ('19', '4', '奖品2是否必中', '0', 'prize2_only', null);
INSERT INTO `cmf_activity_params` VALUES ('20', '4', '奖品3图片', '', 'prize3_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('21', '4', '奖品3名称', '', 'prize3_name', null);
INSERT INTO `cmf_activity_params` VALUES ('22', '4', '奖品3数量', '', 'prize3_total', null);
INSERT INTO `cmf_activity_params` VALUES ('23', '4', '奖品3概率', '', 'prize3_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('24', '4', '奖品3类型', '', 'prize3_type', null);
INSERT INTO `cmf_activity_params` VALUES ('25', '4', '奖品3是否必中', '0', 'prize3_only', null);
INSERT INTO `cmf_activity_params` VALUES ('26', '4', '奖品4图片', '', 'prize4_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('27', '4', '奖品4名称', '', 'prize4_name', null);
INSERT INTO `cmf_activity_params` VALUES ('28', '4', '奖品4数量', '', 'prize4_total', null);
INSERT INTO `cmf_activity_params` VALUES ('29', '4', '奖品4概率', '', 'prize4_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('30', '4', '奖品4类型', '', 'prize4_type', null);
INSERT INTO `cmf_activity_params` VALUES ('31', '4', '奖品4是否必中', '0', 'prize4_only', null);
INSERT INTO `cmf_activity_params` VALUES ('32', '4', '奖品5图片', '', 'prize5_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('33', '4', '奖品5名称', '', 'prize5_name', null);
INSERT INTO `cmf_activity_params` VALUES ('34', '4', '奖品5数量', '', 'prize5_total', null);
INSERT INTO `cmf_activity_params` VALUES ('35', '4', '奖品5概率', '', 'prize5_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('36', '4', '奖品5类型', '', 'prize5_type', null);
INSERT INTO `cmf_activity_params` VALUES ('37', '4', '奖品5是否必中', '0', 'prize5_only', null);
INSERT INTO `cmf_activity_params` VALUES ('38', '4', '奖品6图片', '', 'prize6_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('39', '4', '奖品6名称', '', 'prize6_name', null);
INSERT INTO `cmf_activity_params` VALUES ('40', '4', '奖品6数量', '', 'prize6_total', null);
INSERT INTO `cmf_activity_params` VALUES ('41', '4', '奖品6概率', '', 'prize6_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('42', '4', '奖品6类型', '', 'prize6_type', null);
INSERT INTO `cmf_activity_params` VALUES ('43', '4', '奖品6是否必中', '0', 'prize6_only', null);
INSERT INTO `cmf_activity_params` VALUES ('44', '4', '答题时间(秒)', '100', 'game_time', null);
INSERT INTO `cmf_activity_params` VALUES ('45', '4', '题目分值', '5', 'amount', null);
INSERT INTO `cmf_activity_params` VALUES ('46', '4', '题目数量', '1', 'question_total', null);
INSERT INTO `cmf_activity_params` VALUES ('47', '4', '挑战成功所需的分数', '100', 'success_num', null);
INSERT INTO `cmf_activity_params` VALUES ('48', '4', '结束提示', '你完了', 'prompt', null);
INSERT INTO `cmf_activity_params` VALUES ('49', '5', '单位LOGO', '', 'logo', null);
INSERT INTO `cmf_activity_params` VALUES ('50', '5', '活动日期', '111111', 'timestr', null);
INSERT INTO `cmf_activity_params` VALUES ('51', '5', '单位名称', '11111', 'school', null);
INSERT INTO `cmf_activity_params` VALUES ('52', '5', '联系电话', '11111', 'tel', null);
INSERT INTO `cmf_activity_params` VALUES ('53', '6', '奖品1图片', '', 'prize1_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('54', '6', '奖品1名称', '', 'prize1_name', null);
INSERT INTO `cmf_activity_params` VALUES ('55', '6', '奖品1数量', '', 'prize1_total', null);
INSERT INTO `cmf_activity_params` VALUES ('56', '6', '奖品1概率', '', 'prize1_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('57', '6', '奖品1类型', '', 'prize1_type', null);
INSERT INTO `cmf_activity_params` VALUES ('58', '6', '奖品1是否必中', '0', 'prize1_only', null);
INSERT INTO `cmf_activity_params` VALUES ('59', '6', '奖品2图片', '', 'prize2_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('60', '6', '奖品2名称', '', 'prize2_name', null);
INSERT INTO `cmf_activity_params` VALUES ('61', '6', '奖品2数量', '', 'prize2_total', null);
INSERT INTO `cmf_activity_params` VALUES ('62', '6', '奖品2概率', '', 'prize2_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('63', '6', '奖品2类型', '', 'prize2_type', null);
INSERT INTO `cmf_activity_params` VALUES ('64', '6', '奖品2是否必中', '0', 'prize2_only', null);
INSERT INTO `cmf_activity_params` VALUES ('65', '6', '奖品3图片', '', 'prize3_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('66', '6', '奖品3名称', '', 'prize3_name', null);
INSERT INTO `cmf_activity_params` VALUES ('67', '6', '奖品3数量', '', 'prize3_total', null);
INSERT INTO `cmf_activity_params` VALUES ('68', '6', '奖品3概率', '', 'prize3_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('69', '6', '奖品3类型', '', 'prize3_type', null);
INSERT INTO `cmf_activity_params` VALUES ('70', '6', '奖品3是否必中', '0', 'prize3_only', null);
INSERT INTO `cmf_activity_params` VALUES ('71', '6', '奖品4图片', '', 'prize4_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('72', '6', '奖品4名称', '', 'prize4_name', null);
INSERT INTO `cmf_activity_params` VALUES ('73', '6', '奖品4数量', '', 'prize4_total', null);
INSERT INTO `cmf_activity_params` VALUES ('74', '6', '奖品4概率', '', 'prize4_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('75', '6', '奖品4类型', '', 'prize4_type', null);
INSERT INTO `cmf_activity_params` VALUES ('76', '6', '奖品4是否必中', '0', 'prize4_only', null);
INSERT INTO `cmf_activity_params` VALUES ('77', '6', '奖品5图片', '', 'prize5_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('78', '6', '奖品5名称', '', 'prize5_name', null);
INSERT INTO `cmf_activity_params` VALUES ('79', '6', '奖品5数量', '', 'prize5_total', null);
INSERT INTO `cmf_activity_params` VALUES ('80', '6', '奖品5概率', '', 'prize5_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('81', '6', '奖品5类型', '', 'prize5_type', null);
INSERT INTO `cmf_activity_params` VALUES ('82', '6', '奖品5是否必中', '0', 'prize5_only', null);
INSERT INTO `cmf_activity_params` VALUES ('83', '6', '奖品6图片', '', 'prize6_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('84', '6', '奖品6名称', '', 'prize6_name', null);
INSERT INTO `cmf_activity_params` VALUES ('85', '6', '奖品6数量', '', 'prize6_total', null);
INSERT INTO `cmf_activity_params` VALUES ('86', '6', '奖品6概率', '', 'prize6_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('87', '6', '奖品6类型', '', 'prize6_type', null);
INSERT INTO `cmf_activity_params` VALUES ('88', '6', '奖品6是否必中', '0', 'prize6_only', null);
INSERT INTO `cmf_activity_params` VALUES ('89', '6', '学校名称', '测试', 'school', null);
INSERT INTO `cmf_activity_params` VALUES ('90', '7', '奖品1图片', '', 'prize1_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('91', '7', '奖品1名称', '', 'prize1_name', null);
INSERT INTO `cmf_activity_params` VALUES ('92', '7', '奖品1数量', '', 'prize1_total', null);
INSERT INTO `cmf_activity_params` VALUES ('93', '7', '奖品1概率', '', 'prize1_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('94', '7', '奖品1类型', '', 'prize1_type', null);
INSERT INTO `cmf_activity_params` VALUES ('95', '7', '奖品1是否必中', '0', 'prize1_only', null);
INSERT INTO `cmf_activity_params` VALUES ('96', '7', '奖品2图片', '', 'prize2_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('97', '7', '奖品2名称', '', 'prize2_name', null);
INSERT INTO `cmf_activity_params` VALUES ('98', '7', '奖品2数量', '', 'prize2_total', null);
INSERT INTO `cmf_activity_params` VALUES ('99', '7', '奖品2概率', '', 'prize2_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('100', '7', '奖品2类型', '', 'prize2_type', null);
INSERT INTO `cmf_activity_params` VALUES ('101', '7', '奖品2是否必中', '0', 'prize2_only', null);
INSERT INTO `cmf_activity_params` VALUES ('102', '7', '奖品3图片', '', 'prize3_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('103', '7', '奖品3名称', '', 'prize3_name', null);
INSERT INTO `cmf_activity_params` VALUES ('104', '7', '奖品3数量', '', 'prize3_total', null);
INSERT INTO `cmf_activity_params` VALUES ('105', '7', '奖品3概率', '', 'prize3_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('106', '7', '奖品3类型', '', 'prize3_type', null);
INSERT INTO `cmf_activity_params` VALUES ('107', '7', '奖品3是否必中', '0', 'prize3_only', null);
INSERT INTO `cmf_activity_params` VALUES ('108', '7', '奖品4图片', '', 'prize4_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('109', '7', '奖品4名称', '', 'prize4_name', null);
INSERT INTO `cmf_activity_params` VALUES ('110', '7', '奖品4数量', '', 'prize4_total', null);
INSERT INTO `cmf_activity_params` VALUES ('111', '7', '奖品4概率', '', 'prize4_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('112', '7', '奖品4类型', '', 'prize4_type', null);
INSERT INTO `cmf_activity_params` VALUES ('113', '7', '奖品4是否必中', '0', 'prize4_only', null);
INSERT INTO `cmf_activity_params` VALUES ('114', '7', '奖品5图片', '', 'prize5_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('115', '7', '奖品5名称', '', 'prize5_name', null);
INSERT INTO `cmf_activity_params` VALUES ('116', '7', '奖品5数量', '', 'prize5_total', null);
INSERT INTO `cmf_activity_params` VALUES ('117', '7', '奖品5概率', '', 'prize5_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('118', '7', '奖品5类型', '', 'prize5_type', null);
INSERT INTO `cmf_activity_params` VALUES ('119', '7', '奖品5是否必中', '0', 'prize5_only', null);
INSERT INTO `cmf_activity_params` VALUES ('120', '7', '奖品6图片', '', 'prize6_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('121', '7', '奖品6名称', '', 'prize6_name', null);
INSERT INTO `cmf_activity_params` VALUES ('122', '7', '奖品6数量', '', 'prize6_total', null);
INSERT INTO `cmf_activity_params` VALUES ('123', '7', '奖品6概率', '', 'prize6_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('124', '7', '奖品6类型', '', 'prize6_type', null);
INSERT INTO `cmf_activity_params` VALUES ('125', '7', '奖品6是否必中', '0', 'prize6_only', null);
INSERT INTO `cmf_activity_params` VALUES ('126', '8', '奖品1图片', '', 'prize1_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('127', '8', '奖品1名称', '22', 'prize1_name', null);
INSERT INTO `cmf_activity_params` VALUES ('128', '8', '奖品1数量', '22', 'prize1_total', null);
INSERT INTO `cmf_activity_params` VALUES ('129', '8', '奖品1概率', '22', 'prize1_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('130', '8', '奖品1类型', '222', 'prize1_type', null);
INSERT INTO `cmf_activity_params` VALUES ('131', '8', '奖品1是否必中', '0', 'prize1_only', null);
INSERT INTO `cmf_activity_params` VALUES ('132', '8', '奖品2图片', '', 'prize2_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('133', '8', '奖品2名称', '', 'prize2_name', null);
INSERT INTO `cmf_activity_params` VALUES ('134', '8', '奖品2数量', '', 'prize2_total', null);
INSERT INTO `cmf_activity_params` VALUES ('135', '8', '奖品2概率', '', 'prize2_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('136', '8', '奖品2类型', '', 'prize2_type', null);
INSERT INTO `cmf_activity_params` VALUES ('137', '8', '奖品2是否必中', '0', 'prize2_only', null);
INSERT INTO `cmf_activity_params` VALUES ('138', '8', '奖品3图片', '', 'prize3_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('139', '8', '奖品3名称', '', 'prize3_name', null);
INSERT INTO `cmf_activity_params` VALUES ('140', '8', '奖品3数量', '', 'prize3_total', null);
INSERT INTO `cmf_activity_params` VALUES ('141', '8', '奖品3概率', '', 'prize3_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('142', '8', '奖品3类型', '', 'prize3_type', null);
INSERT INTO `cmf_activity_params` VALUES ('143', '8', '奖品3是否必中', '0', 'prize3_only', null);
INSERT INTO `cmf_activity_params` VALUES ('144', '8', '奖品4图片', '', 'prize4_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('145', '8', '奖品4名称', '', 'prize4_name', null);
INSERT INTO `cmf_activity_params` VALUES ('146', '8', '奖品4数量', '', 'prize4_total', null);
INSERT INTO `cmf_activity_params` VALUES ('147', '8', '奖品4概率', '', 'prize4_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('148', '8', '奖品4类型', '', 'prize4_type', null);
INSERT INTO `cmf_activity_params` VALUES ('149', '8', '奖品4是否必中', '0', 'prize4_only', null);
INSERT INTO `cmf_activity_params` VALUES ('150', '8', '奖品5图片', '', 'prize5_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('151', '8', '奖品5名称', '', 'prize5_name', null);
INSERT INTO `cmf_activity_params` VALUES ('152', '8', '奖品5数量', '', 'prize5_total', null);
INSERT INTO `cmf_activity_params` VALUES ('153', '8', '奖品5概率', '', 'prize5_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('154', '8', '奖品5类型', '', 'prize5_type', null);
INSERT INTO `cmf_activity_params` VALUES ('155', '8', '奖品5是否必中', '0', 'prize5_only', null);
INSERT INTO `cmf_activity_params` VALUES ('156', '8', '奖品6图片', '', 'prize6_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('157', '8', '奖品6名称', '', 'prize6_name', null);
INSERT INTO `cmf_activity_params` VALUES ('158', '8', '奖品6数量', '', 'prize6_total', null);
INSERT INTO `cmf_activity_params` VALUES ('159', '8', '奖品6概率', '', 'prize6_rate', null);
INSERT INTO `cmf_activity_params` VALUES ('160', '8', '奖品6类型', '', 'prize6_type', null);
INSERT INTO `cmf_activity_params` VALUES ('161', '8', '奖品6是否必中', '0', 'prize6_only', null);
INSERT INTO `cmf_activity_params` VALUES ('162', '9', '单位名称', '11', 'school', null);
INSERT INTO `cmf_activity_params` VALUES ('163', '9', '活动价格', '11', 'price', null);
INSERT INTO `cmf_activity_params` VALUES ('164', '9', '活动日期', '11', 'timestr', null);
INSERT INTO `cmf_activity_params` VALUES ('165', '9', '单位logo', '', 'logo', null);
INSERT INTO `cmf_activity_params` VALUES ('166', '9', '活动类型', '1', 'activity_type', null);
INSERT INTO `cmf_activity_params` VALUES ('167', '10', '助力值名称', '', 'markname', null);
INSERT INTO `cmf_activity_params` VALUES ('168', '10', '是否显示倒计时', '1', 'iscountdown', null);
INSERT INTO `cmf_activity_params` VALUES ('169', '10', '卡片名称', '', 'cardname', null);
INSERT INTO `cmf_activity_params` VALUES ('170', '10', '卡片数量', '1000', 'count', null);
INSERT INTO `cmf_activity_params` VALUES ('171', '10', '显示前', '100', 'sortcount', null);
INSERT INTO `cmf_activity_params` VALUES ('172', '10', '最高得分限制', '', 'max', null);
INSERT INTO `cmf_activity_params` VALUES ('173', '10', '初始分值', '10', 'start', null);
INSERT INTO `cmf_activity_params` VALUES ('174', '10', '积分单位', '分', 'unit', null);
INSERT INTO `cmf_activity_params` VALUES ('175', '10', '固定助力积分', '1', 'step', null);
INSERT INTO `cmf_activity_params` VALUES ('176', '10', '助力随机积分范围', '3,7', 'steprandom', null);
INSERT INTO `cmf_activity_params` VALUES ('177', '10', '助力积分方式', '0', 'steptype', null);
INSERT INTO `cmf_activity_params` VALUES ('178', '11', '报名需填信息', '', 'other_remark', null);
INSERT INTO `cmf_activity_params` VALUES ('179', '11', '显示前多少人', '', 'show_total', null);
INSERT INTO `cmf_activity_params` VALUES ('180', '11', '每页显示条数', '', 'page_total', null);
INSERT INTO `cmf_activity_params` VALUES ('181', '11', '是否开启前台报名', '1', 'is_front_join', null);
INSERT INTO `cmf_activity_params` VALUES ('182', '11', '是否开启报名审核', '', 'is_check', null);
INSERT INTO `cmf_activity_params` VALUES ('183', '12', '学校名称', '团团购-测试', 'school_name', null);
INSERT INTO `cmf_activity_params` VALUES ('184', '12', '宣传语', '团团购-测试', 'slogan', null);
INSERT INTO `cmf_activity_params` VALUES ('185', '12', '报名需填信息', '11', 'other_remark', null);
INSERT INTO `cmf_activity_params` VALUES ('186', '12', '是否需要支付', '0', 'is_pay', null);
INSERT INTO `cmf_activity_params` VALUES ('187', '12', '支付二维码', '', 'pay_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('188', '12', '参团人数', '3', 'total_limit', null);
INSERT INTO `cmf_activity_params` VALUES ('189', '13', '学校名称', '团团购-测试', 'school_name', null);
INSERT INTO `cmf_activity_params` VALUES ('190', '13', '宣传语', '团团购-测试', 'slogan', null);
INSERT INTO `cmf_activity_params` VALUES ('191', '13', '报名需填信息', '11', 'other_remark', null);
INSERT INTO `cmf_activity_params` VALUES ('192', '13', '是否需要支付', '0', 'is_pay', null);
INSERT INTO `cmf_activity_params` VALUES ('193', '13', '支付二维码', '', 'pay_thumb', null);
INSERT INTO `cmf_activity_params` VALUES ('194', '13', '参团人数', '3', 'total_limit', null);

-- ----------------------------
-- Table structure for cmf_activity_user
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity_user`;
CREATE TABLE `cmf_activity_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `from_user` varchar(255) DEFAULT NULL COMMENT '用户标示',
  `nickname` varchar(255) DEFAULT '' COMMENT '微信昵称',
  `username` varchar(255) DEFAULT '' COMMENT '用户名称',
  `mobile` varchar(255) DEFAULT NULL COMMENT '用户手机号',
  `createtime` varchar(255) DEFAULT '' COMMENT '参与时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `day_num` int(11) DEFAULT '0' COMMENT '日参与次数',
  `total_num` int(11) DEFAULT '0' COMMENT '总参与次数',
  `per_num` int(11) DEFAULT '0' COMMENT '个人参与次数',
  `update_time` int(11) DEFAULT '0' COMMENT '更新日期',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `pid` int(11) DEFAULT '0' COMMENT '关联关系的父id',
  `pname` varchar(255) DEFAULT NULL COMMENT '推荐人姓名',
  `course` varchar(500) DEFAULT NULL COMMENT '课程-团购专属',
  `thumb` longtext COMMENT '附件-喝彩专用',
  `award_num` int(11) DEFAULT '0' COMMENT '中奖次数',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态',
  `listorder` int(11) DEFAULT '0' COMMENT '排序',
  `parent_msg` text COMMENT '给父母寄语—母亲节专用',
  `teacher_msg` text COMMENT '给老师寄语',
  PRIMARY KEY (`id`,`aid`),
  UNIQUE KEY `forindex` (`aid`,`id`) USING BTREE,
  KEY `aid_index` (`aid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='活动参与用户';

-- ----------------------------
-- Records of cmf_activity_user
-- ----------------------------
INSERT INTO `cmf_activity_user` VALUES ('1', '2', null, null, '', null, '1493111656', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('2', '3', null, null, 'q\'q\'q', '12345678900', '1493112556', '羊入虎口', '1', '0', '0', '1493112556', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('3', '4', null, null, '', null, '1493204016', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('4', '4', null, null, '', null, '1493204160', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('5', '4', null, null, '', null, '1493204166', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('6', '4', null, null, '', null, '1493204202', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('7', '4', null, null, '', null, '1493204209', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('8', '4', null, null, '', null, '1493204255', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('9', '4', null, null, '', null, '1493204262', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('10', '4', null, null, '', null, '1493204331', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('11', '4', null, null, '', null, '1493204360', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('12', '4', null, null, '', null, '1493204436', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('13', '4', null, null, '', null, '1493204984', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('14', '4', null, null, '', null, '1493205110', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('15', '4', null, null, '', null, '1493205221', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('16', '4', null, null, '', null, '1493205244', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('17', '4', null, null, '', null, '1493205313', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('18', '4', null, null, '', null, '1493205514', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('19', '4', null, null, '', null, '1493208350', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('20', '4', null, null, '', null, '1493208408', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('21', '2', null, null, '', null, '1493256002', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('22', '2', null, null, '', null, '1493256040', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('23', '2', null, null, '', null, '1493256240', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('24', '5', null, null, '', null, '1493257463', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('25', '5', null, null, '', null, '1493257484', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('26', '5', null, null, '', null, '1493257526', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('27', '5', null, null, '', null, '1493257637', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('28', '5', null, null, '', null, '1493257688', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('29', '5', null, null, '', null, '1493257722', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('30', '5', null, null, '', null, '1493257736', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('31', '5', null, null, '', null, '1493257759', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('32', '6', null, null, '', null, '1493259442', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('33', '6', null, null, '', null, '1493259478', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('34', '6', null, null, '', null, '1493259514', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('35', '7', null, null, '', null, '1493259763', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('36', '7', null, null, '', null, '1493259862', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('37', '7', null, null, '', null, '1493259867', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('38', '7', null, null, '', null, '1493259894', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('39', '7', null, null, '', null, '1493259917', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('40', '7', null, null, '', null, '1493260363', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('41', '7', null, null, '', null, '1493260386', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);
INSERT INTO `cmf_activity_user` VALUES ('42', '2', null, null, '', null, '1493343542', null, '0', '0', '0', '0', null, '0', null, null, null, '0', '0.00', '0', '0', null, null);

-- ----------------------------
-- Table structure for cmf_activity_user_answer
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity_user_answer`;
CREATE TABLE `cmf_activity_user_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT '0' COMMENT '活动id',
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `round` int(11) DEFAULT '0' COMMENT '第几次答题',
  `type` varchar(255) DEFAULT NULL COMMENT '题目类型',
  `prolblem_id` int(11) DEFAULT '0' COMMENT '题目id',
  `answer` varchar(255) DEFAULT NULL COMMENT '答案',
  `is_right` int(11) DEFAULT '0' COMMENT '是否正确 1正确0 不正确',
  `createtime` varchar(255) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_activity_user_answer
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_activity_user_data
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity_user_data`;
CREATE TABLE `cmf_activity_user_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT '0',
  `uid` varchar(255) DEFAULT NULL COMMENT '用户id',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `value` varchar(1000) DEFAULT NULL COMMENT '数据值',
  `field` varchar(255) DEFAULT '' COMMENT '字段',
  `createtime` int(11) DEFAULT '0' COMMENT '更新日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动参与用户数据征集';

-- ----------------------------
-- Records of cmf_activity_user_data
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_activity_user_prize
-- ----------------------------
DROP TABLE IF EXISTS `cmf_activity_user_prize`;
CREATE TABLE `cmf_activity_user_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `from_user` varchar(255) DEFAULT NULL COMMENT '用户标示',
  `username` varchar(255) DEFAULT '' COMMENT '用户名称',
  `mobile` varchar(255) DEFAULT NULL COMMENT '用户手机号',
  `thumb` varchar(255) DEFAULT NULL COMMENT '奖品图片',
  `type` varchar(255) DEFAULT NULL COMMENT '奖品类型',
  `name` varchar(255) DEFAULT NULL COMMENT '奖品名称',
  `createtime` varchar(255) DEFAULT '' COMMENT '创建时间',
  `remark` varchar(32) DEFAULT NULL COMMENT '备注',
  `pname` varchar(32) DEFAULT NULL COMMENT '老生名字',
  `pschool` varchar(32) DEFAULT NULL COMMENT '老生校区',
  `age` varchar(32) DEFAULT NULL COMMENT '年龄',
  PRIMARY KEY (`id`),
  KEY `index` (`aid`) USING BTREE,
  KEY `uid_index` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动参与用户获奖记录表';

-- ----------------------------
-- Records of cmf_activity_user_prize
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_ad
-- ----------------------------
DROP TABLE IF EXISTS `cmf_ad`;
CREATE TABLE `cmf_ad` (
  `ad_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `ad_name` varchar(255) NOT NULL COMMENT '广告名称',
  `ad_content` text COMMENT '广告内容',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  PRIMARY KEY (`ad_id`),
  KEY `ad_name` (`ad_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_ad
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_answer_type
-- ----------------------------
DROP TABLE IF EXISTS `cmf_answer_type`;
CREATE TABLE `cmf_answer_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) NOT NULL COMMENT '类型名称',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_answer_type
-- ----------------------------
INSERT INTO `cmf_answer_type` VALUES ('1', '钱钱钱', '0', '4');

-- ----------------------------
-- Table structure for cmf_appcenter
-- ----------------------------
DROP TABLE IF EXISTS `cmf_appcenter`;
CREATE TABLE `cmf_appcenter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `index_thumb` varchar(255) DEFAULT NULL COMMENT '首页封面',
  `thumb` varchar(255) DEFAULT NULL,
  `link` varchar(1000) DEFAULT NULL COMMENT '活动链接',
  `listorder` int(11) DEFAULT '0',
  `status` tinyint(3) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='应用中心';

-- ----------------------------
-- Records of cmf_appcenter
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_asset
-- ----------------------------
DROP TABLE IF EXISTS `cmf_asset`;
CREATE TABLE `cmf_asset` (
  `aid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户 id',
  `key` varchar(50) NOT NULL COMMENT '资源 key',
  `filename` varchar(50) DEFAULT NULL COMMENT '文件名',
  `filesize` int(11) DEFAULT NULL COMMENT '文件大小,单位Byte',
  `filepath` varchar(200) NOT NULL COMMENT '文件路径，相对于 upload 目录，可以为 url',
  `uploadtime` int(11) NOT NULL COMMENT '上传时间',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1：可用，0：删除，不可用',
  `meta` text COMMENT '其它详细信息，JSON格式',
  `suffix` varchar(50) DEFAULT NULL COMMENT '文件后缀名，不包括点',
  `download_times` int(11) NOT NULL DEFAULT '0' COMMENT '下载次数',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资源表';

-- ----------------------------
-- Records of cmf_asset
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_auth_access
-- ----------------------------
DROP TABLE IF EXISTS `cmf_auth_access`;
CREATE TABLE `cmf_auth_access` (
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '角色',
  `rule_name` varchar(255) NOT NULL COMMENT '规则唯一英文标识,全小写',
  `type` varchar(30) DEFAULT NULL COMMENT '权限规则分类，请加应用前缀,如admin_',
  KEY `role_id` (`role_id`) USING BTREE,
  KEY `rule_name` (`rule_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限授权表';

-- ----------------------------
-- Records of cmf_auth_access
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `cmf_auth_rule`;
CREATE TABLE `cmf_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` varchar(30) NOT NULL DEFAULT '1' COMMENT '权限规则分类，请加应用前缀,如admin_',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识,全小写',
  `param` varchar(255) DEFAULT NULL COMMENT '额外url参数',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限规则表';

-- ----------------------------
-- Records of cmf_auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_class_join
-- ----------------------------
DROP TABLE IF EXISTS `cmf_class_join`;
CREATE TABLE `cmf_class_join` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '班级名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '班级图片',
  `join_des` varchar(500) DEFAULT NULL COMMENT '报名须知',
  `lesson_des` varchar(500) DEFAULT NULL COMMENT '课程大纲',
  `createtime` int(11) DEFAULT '0' COMMENT '创建日期',
  `aid` int(11) DEFAULT '0' COMMENT '活动id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分班级报名';

-- ----------------------------
-- Records of cmf_class_join
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_comments
-- ----------------------------
DROP TABLE IF EXISTS `cmf_comments`;
CREATE TABLE `cmf_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_table` varchar(100) NOT NULL COMMENT '评论内容所在表，不带表前缀',
  `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评论内容 id',
  `url` varchar(255) DEFAULT NULL COMMENT '原文地址',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '发表评论的用户id',
  `to_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被评论的用户id',
  `full_name` varchar(50) DEFAULT NULL COMMENT '评论者昵称',
  `email` varchar(255) DEFAULT NULL COMMENT '评论者邮箱',
  `createtime` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '评论时间',
  `content` text NOT NULL COMMENT '评论内容',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '评论类型；1实名评论',
  `parentid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '被回复的评论id',
  `path` varchar(500) DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '1' COMMENT '状态，1已审核，0未审核',
  PRIMARY KEY (`id`),
  KEY `comment_post_ID` (`post_id`) USING BTREE,
  KEY `comment_approved_date_gmt` (`status`) USING BTREE,
  KEY `comment_parent` (`parentid`) USING BTREE,
  KEY `table_id_status` (`post_table`,`post_id`,`status`) USING BTREE,
  KEY `createtime` (`createtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of cmf_comments
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_common_action_log
-- ----------------------------
DROP TABLE IF EXISTS `cmf_common_action_log`;
CREATE TABLE `cmf_common_action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` bigint(20) DEFAULT '0' COMMENT '用户id',
  `object` varchar(100) DEFAULT NULL COMMENT '访问对象的id,格式：不带前缀的表名+id;如posts1表示xx_posts表里id为1的记录',
  `action` varchar(50) DEFAULT NULL COMMENT '操作名称；格式规定为：应用名+控制器+操作名；也可自己定义格式只要不发生冲突且惟一；',
  `count` int(11) DEFAULT '0' COMMENT '访问次数',
  `last_time` int(11) DEFAULT '0' COMMENT '最后访问的时间戳',
  `ip` varchar(15) DEFAULT NULL COMMENT '访问者最后访问ip',
  PRIMARY KEY (`id`),
  KEY `user_object_action` (`user`,`object`,`action`) USING BTREE,
  KEY `user_object_action_ip` (`user`,`object`,`action`,`ip`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='访问记录表';

-- ----------------------------
-- Records of cmf_common_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_course
-- ----------------------------
DROP TABLE IF EXISTS `cmf_course`;
CREATE TABLE `cmf_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '课程封面',
  `keyword` varchar(255) DEFAULT NULL COMMENT '关键词',
  `video_url` varchar(255) DEFAULT NULL COMMENT '视频地址',
  `author` varchar(255) DEFAULT NULL COMMENT '作者',
  `auth_avatar` varchar(255) DEFAULT NULL COMMENT '作者头像',
  `status` tinyint(3) DEFAULT '1' COMMENT '显示',
  `class` varchar(255) DEFAULT NULL COMMENT '年级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='课程体系';

-- ----------------------------
-- Records of cmf_course
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_form
-- ----------------------------
DROP TABLE IF EXISTS `cmf_form`;
CREATE TABLE `cmf_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '表单名称',
  `contact` varchar(255) DEFAULT NULL COMMENT '联系人',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `rule` text COMMENT '规则',
  `type` tinyint(3) DEFAULT '1' COMMENT '1案例 2模板',
  `uid` int(11) DEFAULT '0' COMMENT '发布者id',
  `thumb` varchar(1000) DEFAULT '' COMMENT '背景图',
  `description` text COMMENT '表单模板描述',
  `status` tinyint(3) DEFAULT '1' COMMENT '1显示0隐藏',
  `option1` varchar(255) DEFAULT NULL COMMENT '配置项1',
  `option2` varchar(255) DEFAULT NULL COMMENT '配置项2',
  `option3` varchar(255) DEFAULT NULL COMMENT '配置项3',
  `option4` varchar(255) DEFAULT NULL COMMENT '配置项4',
  `option5` varchar(255) DEFAULT NULL COMMENT '配置项5',
  `begintime` int(11) DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) DEFAULT '0' COMMENT '结束时间',
  `is_distri` tinyint(3) DEFAULT '0' COMMENT '0不启用分销 1启用分销',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `tid` int(11) DEFAULT '0' COMMENT '模板id',
  `hits` int(11) DEFAULT '0' COMMENT '点击量',
  `index_thumb` varchar(500) DEFAULT NULL COMMENT '首页封面-模板专用',
  `listorder` int(11) DEFAULT '0' COMMENT '排序',
  `total_limit` int(11) DEFAULT '0' COMMENT '最多报名人数限制 0为不限制',
  `preview_thumb` varchar(500) DEFAULT NULL COMMENT '预览效果图',
  `hide_username` tinyint(3) DEFAULT '1' COMMENT '0隐藏1显示 是否隐藏用户名',
  `hide_mobile` tinyint(3) DEFAULT '1' COMMENT '隐藏手机号',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `sharehits` int(11) DEFAULT '0' COMMENT '分享次数',
  `coupon_price` varchar(255) DEFAULT '0' COMMENT '红包金额',
  `recommend` tinyint(3) DEFAULT '0' COMMENT '1推荐 0不推荐',
  `coupon_title` varchar(255) DEFAULT NULL COMMENT '红包标题',
  `valid` tinyint(3) DEFAULT '1' COMMENT '0删除1 不删除',
  `paycode_thumb` varchar(255) DEFAULT NULL COMMENT '支付二维码',
  `price` varchar(32) DEFAULT '0.00' COMMENT '报名费用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COMMENT='报名表单';

-- ----------------------------
-- Records of cmf_form
-- ----------------------------
INSERT INTO `cmf_form` VALUES ('73', '素色花边模板', '张老师', '137837778505', '一款微活动模板，页面丰富，形式感强，通用于多种场景。', '1、打开活动点击“邀请有礼”输入信息，然后邀请好友报名2、邀请5人奖励精美礼品箱一个3、邀请10人以上奖励200元榨汁机一台', '2', '0', '[{\"url\":\"20160718\\/578c953179e60.jpg\",\"alt\":\"\\u5b66\\u4e60\\u603b\\u52a8\\u5458\"},{\"url\":\"20160718\\/578c953a63aad.jpg\",\"alt\":\"\\u5f00\\u5b66\\u5b63\"},{\"url\":\"20160718\\/578c95425afdd.jpg\",\"alt\":\"\\u6b22\\u8fce\\u65b0\\u540c\\u5b66\"},{\"url\":\"20160718\\/578c99f334ca4.jpg\",\"alt\":\"0cef252ec8a095cd4359988199a82b60baf09b8b1162ee-oNtAAl_fw658\"}]', '<p style=\"line-height: 1.5em;\"><span style=\"color: rgb(38, 38, 38);\">这是一个微活动报名模板，界面丰富，形式感强，通用于各个场景。可以自由设置活动海报、主题、时间地点联系人、以及详细的活动介绍。还有邀请有礼功能哦。</span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160719/578dcae13e117.jpg\" title=\"未标题-2.jpg\" alt=\"未标题-2.jpg\"/></p>', '1', 'form_tpl2', 'coupon_price', 'templet2', '', '', '1468771200', '1475164800', '0', '郑州', '0', '143', '/data/upload/20160719/578dc8e4dcdc2.jpg', '0', '100', '/data/upload/20160719/578dcb018e367.jpg', '1', '1', '1468771200', '0', '0', '0', null, '0', null, null);
INSERT INTO `cmf_form` VALUES ('74', '优惠券', '张老师', '137837778505', '', '红包分销', '2', '0', '[{\"url\":\"20160722\\/5791886288655.jpg\",\"alt\":\"youhuiquan\"},{\"url\":\"20160719\\/578d97f39c3fc.jpg\",\"alt\":\"\\u5f00\\u5b66\\u5b63\"},{\"url\":\"20160722\\/5791886d1266a.jpg\",\"alt\":\"e8fe3fa19b6f5af83435f91b6c82e094\"},{\"url\":\"20160719\\/578d97fe5f244.jpg\",\"alt\":\"\\u5b66\\u4e60\\u603b\\u52a8\\u5458\"}]', '<p style=\"line-height: 1.5em;\"><span style=\"color: rgb(38, 38, 38);\">这是优惠券、红包专用模板，适用于派发优惠券或红包的场景。可以自由设置活动海报、主题、红包金额、红包描述，活动细则。活动细则里面可以使用多种微信编辑器样式，让你的活动报名更精彩。</span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160719/578dcbbc9e86a.jpg\" title=\"未标题-3.jpg\" alt=\"未标题-3.jpg\"/></p>', '1', 'form_tpl3', 'coupon_price', 'templet3', '', '', '1468771200', '1475164800', '0', '郑州', '0', '74', '/data/upload/20160719/578d9793eed03.jpg', '0', '200', '/data/upload/20160719/578dcb76e7f9f.jpg', '1', '1', '1468771200', '0', '0', '0', '仅限本校老生使用', '0', null, null);
INSERT INTO `cmf_form` VALUES ('100', '素色花边模板', '张老师', '137837778505', null, '<p>1、打开活动点击“邀请有礼”输入信息，然后邀请好友报名2、邀请5人奖励精美礼品箱一个3、邀请10人以上奖励200元榨汁机一台</p>', '1', '2', '20160718/578c953179e60.jpg', '<p style=\"line-height: 1.5em;\"><span style=\"color: rgb(38, 38, 38);\">这是一个微活动报名模板，界面丰富，形式感强，通用于各个场景。可以自由设置活动海报、主题、时间地点联系人、以及详细的活动介绍。还有邀请有礼功能哦。</span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160719/578dcae13e117.jpg\" title=\"未标题-2.jpg\" alt=\"未标题-2.jpg\"/></p>', '1', 'form_tpl2', 'coupon_price', 'templet2', '', '', '1493596800', '1496244600', '0', '郑州', '73', '2', null, '0', '100', null, '1', '1', '1494905521', '0', '0', '1', '', '1', '', '0.00');
INSERT INTO `cmf_form` VALUES ('102', '优惠券', '张老师', '137837778505', null, '<p>红包分销</p>', '1', '2', '20160722/5791886288655.jpg', '<p style=\"line-height: 1.5em;\"><span style=\"color: rgb(38, 38, 38);\">这是优惠券、红包专用模板，适用于派发优惠券或红包的场景。可以自由设置活动海报、主题、红包金额、红包描述，活动细则。活动细则里面可以使用多种微信编辑器样式，让你的活动报名更精彩。</span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160719/578dcbbc9e86a.jpg\" title=\"未标题-3.jpg\" alt=\"未标题-3.jpg\"/></p>', '1', 'form_tpl3', 'coupon_price', 'templet3', '', '', '1493596800', '1496244600', '0', '郑州', '74', '21', null, '0', '200', null, '1', '1', '1494915391', '0', '100', '1', '仅限本校老生使用', '1', '', '0.00');
INSERT INTO `cmf_form` VALUES ('103', '普通活动报名', '路老师', '15000000000', '', '1、打开活动点击“邀请有礼”输入信息，然后邀请好友报名 2、邀请5人报名奖励精美礼品箱一个 3、邀请10人以上奖励200元榨汁机一台', '2', '0', '[{\"url\":\"20170517\\/591ba4ca17869.jpg\",\"alt\":\"1\"},{\"url\":\"20170517\\/591ba4d0c45a7.jpg\",\"alt\":\"2\"},{\"url\":\"20170517\\/591ba4d74a9c4.jpg\",\"alt\":\"3\"},{\"url\":\"20170517\\/591ba4de62d5f.jpg\",\"alt\":\"4\"}]', '<section id=\"m812\" class=\"yead_editor\" title=\"812，更新于2016-08-04 22:48\" style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; margin: 5px auto;\"><section class=\"wx-bg\" data-wxsrc=\"https://mmbiz.qlogo.cn/mmbiz/ianq03UUWGmIpwAQC8PpNCD8pUpQqmshV4Q5wrbETPaZtT8Uu4se1m48yjbFuVx1c7icEtiaERuKiaNlAwF8iafFdFg/0?wx_fmt=png\" style=\"border-style: solid; -webkit-border-image: url(https://img.yzcdn.cn/upload_files/2016/08/04/Fsv0P43uze4RzcGAIPLKuo_nmAyu.png) 60 140 115 63 fill repeat; border-width: 30px 70px 68px 32px;\"><section style=\"margin-bottom: -35px; line-height: 30px;\"><p>亲爱的家长朋友们，国庆节马上就要到了，为丰富孩子们的假期生活，大牛教育特举办一场别开生面“水果盛宴”大趴体哦，不仅有众水果宝贝捧场，更有有趣的游戏，PK等您来high！</p></section></section></section><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px;\"><br/></p><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; text-align: center;\">想不想和老师、小朋友们一起制作水果拼盘？</p><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; text-align: center;\">想不想玩各种水果游戏？</p><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; text-align: center;\">想不想赢取国庆礼物呢？</p><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; text-align: center;\">不要犹豫，一起来参加吧！</p><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; text-align: center;\">一边享受着创意和美味带来的美妙感受...</p><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; text-align: center;\">水果的造型就是这么美观，这么任性~</p><p style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160914/57d8b911f1a58.JPG\" title=\"201205181147547185.JPG\" alt=\"201205181147547185.JPG\"/></p><p style=\"white-space: normal;\"><br/></p><p><br/></p><section id=\"m735\" class=\"yead_editor yead-selected\" title=\"735，更新于2016-06-25 18:35\" style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; margin: 5px auto;\"><section class=\"wx-bg\" data-wxsrc=\"https://mmbiz.qlogo.cn/mmbiz/ianq03UUWGmKBiaFRAtInJGYgEuY433yBFf7Qj2DmwzJibMcvprO6xDBzSXk6gOokibCxibXmxFxbCUy1saJFZG34qQ/0?wx_fmt=png\" style=\"border: 10px solid rgb(255, 129, 36); -webkit-border-image: url(http://img.yzcdn.cn/upload_files/2016/06/25/FmWmE10_awxo7s1VipEZ6-RcKBoZ.png) 30 fill; line-height: 30px; text-indent: 2em;\"><p style=\"text-align: center;\"><span style=\"text-indent: 2em;\">哇，这些美美的水果盛宴都在等着你来完成，想学习更多丰富的英文知识吗？</span></p><p style=\"text-align: center;\">来跟着老师一起，动动手，动动嘴。</p><p style=\"text-align: center;\"><strong><span style=\"color: rgb(192, 0, 0);\">边玩边学，开启你的国庆假期水果之旅吧！</span></strong></p></section></section><p><br/></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; text-align: center;\">重要的事情说三遍！</p><p style=\"white-space: normal; text-align: center;\">此活动</p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 18px; color: rgb(255, 0, 0);\"><strong>免费！免费！免费！</strong></span></p><p style=\"white-space: normal; text-align: center;\">老生不仅免费，邀请一位小朋友参与，</p><p style=\"white-space: normal; text-align: center;\">会再送您一份精美礼品哦！</p><p style=\"white-space: normal;\"><br/></p><p><br/></p><section id=\"m497\" class=\"yead_editor yead-selected\" title=\"497，更新于2015-12-09 17:21\" style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; margin: 5px auto;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); line-height: 25px; border: 0px none; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; text-align: center; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px 15px; max-width: 100%; box-sizing: border-box; display: inline-block; word-wrap: break-word !important; background-color: rgb(254, 254, 254);\"><section class=\"yead_bgc\" style=\"margin: 0px; padding: 8px 5px; max-width: 100%; box-sizing: border-box; width: 3.5em; height: 3.5em; border-radius: 50%; display: inline-block; color: rgb(255, 255, 255); word-wrap: break-word !important; background-color: rgb(216, 40, 33);\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160914/57d8bc19dca53.png\" style=\"max-width: 100%; margin: 0px; padding: 0px; height: auto !important; box-sizing: border-box !important; word-wrap: break-word !important; width: 2.5em !important; visibility: visible !important;\"/></section></section></section><section style=\"margin: -2em 0px 0px; padding: 30px 10px 10px; max-width: 100%; box-sizing: border-box; line-height: 30px; border: 1px solid rgb(198, 198, 198); word-wrap: break-word !important;\"><p><span style=\"font-family: 宋体; font-size: 16px;\">活动对象：5——<span style=\"font-family: Cambria;\">12</span>岁儿童</span></p><p><span style=\"font-family: 宋体; font-size: 16px;\">活动时间：</span></p><p style=\"text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">10</span><span style=\"font-family: 宋体; font-size: 16px;\">月</span><span style=\"font-size: 16px; font-family: Cambria;\">3</span><span style=\"font-family: 宋体; font-size: 16px;\">日</span></p><p style=\"text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">第一场：15:00——<span style=\"font-family: Cambria;\">16:00</span></span></p><p style=\"text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">第二场：</span><span style=\"font-family: 宋体; font-size: 16px;\">16:30</span><span style=\"font-family: 宋体; font-size: 16px;\">——</span><span style=\"font-size: 16px; font-family: Cambria;\">17:30</span></p><p style=\"text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">10月<span style=\"font-family: Cambria;\">4</span>日</span></p><p style=\"text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">第一场：</span><span style=\"font-family: 宋体; font-size: 16px;\">15:00</span><span style=\"font-family: 宋体; font-size: 16px;\">——</span><span style=\"font-size: 16px; font-family: Cambria;\">16:00</span></p><p style=\"text-align: center;\"><span style=\"font-family: 宋体; font-size: 16px;\">第二场：</span><span style=\"font-family: 宋体; font-size: 16px;\">16:30</span><span style=\"font-family: 宋体; font-size: 16px;\">——</span><span style=\"font-size: 16px; font-family: Cambria;\">17:30</span></p><p><span style=\"font-size: 16px; font-family: Cambria;\"><br/></span></p></section></section></section><p><br/></p><p><br/></p>', '1', 'form_tpl1', 'coupon_price', 'templet1', '', '', '1493568000', '0', '1', '北京', '0', '0', '/data/upload/20170517/591ba44574529.jpg', '0', '10000', '/data/upload/20170517/591ba44b88d03.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');
INSERT INTO `cmf_form` VALUES ('104', '七天乐模板', '路老师', '15000000000', '', '国庆系列报名', '2', '0', '[{\"url\":\"20170517\\/591ba62132344.jpg\",\"alt\":\"1\"}]', '<section id=\"m728\" class=\"yead_editor yead-selected\" title=\"728，更新于2016-06-24 17:59\" style=\"white-space: normal; font-family: 微软雅黑, 宋体; font-size: 14px; margin: 5px auto;\"><br/></section><p class=\"shifubrush\" style=\"white-space: normal;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160914/57d8ef98ec231.jpg\" title=\"第一天.jpg\" alt=\"第一天.jpg\"/></p><p style=\"white-space: normal;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160914/57d8ef996cab3.jpg\" title=\"第二天.jpg\" alt=\"第二天.jpg\"/></p><p style=\"white-space: normal;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160914/57d8ef99c45c9.jpg\" title=\"第三天.jpg\" alt=\"第三天.jpg\"/></p><p style=\"white-space: normal;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160914/57d8ef9a27b59.jpg\" title=\"第四天.jpg\" alt=\"第四天.jpg\"/></p><p style=\"white-space: normal;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160914/57d8ef9a87f97.jpg\" title=\"第五天.jpg\" alt=\"第五天.jpg\"/></p><p><br/></p>', '1', 'form_tpl4', 'coupon_price', 'templet4', '', '', '1493568000', '0', '1', '北京市星光影视园', '0', '0', '/data/upload/20170517/591ba59a8cb70.jpg', '0', '10000', '/data/upload/20170517/591ba59ed8221.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');
INSERT INTO `cmf_form` VALUES ('105', '蓝色海洋模版', '路老师', '15000000000', '', '', '2', '0', '[{\"url\":\"20170517\\/591ba6e960bc4.jpg\",\"alt\":\"t1\"}]', '<p style=\"text-align: left;\"><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\">喜迎国庆！成兰金喇叭口才“百元成才”计划！<br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb23020fcca.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/>只需100元就能享受价值4次室内+1次户外实践课，并赠送一个月“读书会”系列微课！<br style=\"margin: 0px; padding: 0px;\"/>招生年龄：4-12岁（中班至六年级）（只限新生）<br style=\"margin: 0px; padding: 0px;\"/>参加费用:100元/人<br style=\"margin: 0px; padding: 0px;\"/>上课时间：2016年10月5日—7日（具体时间段电话通知您）<br style=\"margin: 0px; padding: 0px;\"/>报名截止日期：&nbsp;10月4日中午12:00点<br style=\"margin: 0px; padding: 0px;\"/><br style=\"margin: 0px; padding: 0px;\"/>总校地址：******<br style=\"margin: 0px; padding: 0px;\"/>实验校区：******<br style=\"margin: 0px; padding: 0px;\"/>咨询热线：******<br style=\"margin: 0px; padding: 0px;\"/>报名方式：点击右下角“我要报名”即可成功<br style=\"margin: 0px; padding: 0px;\"/>温馨提示：报名时请填写学员姓名及电话<br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb230273292.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/>您的孩子敢于在公众场合自信的开口说话吗？<br style=\"margin: 0px; padding: 0px;\"/>您的孩子能够面对镜头大方的表现自己吗？<br style=\"margin: 0px; padding: 0px;\"/>您想让自己的孩子不再害怕当众表达自己的见解吗？<br style=\"margin: 0px; padding: 0px;\"/>你想让自己孩子的口语表达有个飞跃式的进步吗？<br style=\"margin: 0px; padding: 0px;\"/>快来报名成兰金喇叭少儿口才吧！<br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\"><br/></span></p><p><br/></p><p><br/></p><p style=\"text-align: left;\"><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">成兰金喇叭口才培训目标：</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">&nbsp;&nbsp;&nbsp;克服胆怯&nbsp;&nbsp;&nbsp;&nbsp;增强自信</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">&nbsp;&nbsp;&nbsp;活跃思维&nbsp;&nbsp;&nbsp;&nbsp;展示自我</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">&nbsp;&nbsp;&nbsp;懂得礼仪&nbsp;&nbsp;&nbsp;&nbsp;学会才艺</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">&nbsp;&nbsp;&nbsp;发音标准&nbsp;&nbsp;&nbsp;&nbsp;口脑协调</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">&nbsp;&nbsp;&nbsp;培养气质&nbsp;&nbsp;&nbsp;&nbsp;领导能力&nbsp;&nbsp;</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/><br style=\"margin: 0px; padding: 0px;\"/>成兰金喇叭提醒您</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">不容忽视孩子的三大现象：</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">现象一</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\">：说话胆怯，不自信。在班上不敢回答老师的问题，更不敢向老师提出问题，直接造成孩子成绩下降；<br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">现象二：</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\">性格外向，爱说话，思路不清，语言逻辑差。上课注意力不集中；<br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">现象三：</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\">性格内向，思维活跃，但表达能力差，不爱讲话，平时在家和父母能说，但遇到生人就不说了，也说不出来。<br style=\"margin: 0px; padding: 0px;\"/><br style=\"margin: 0px; padding: 0px;\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本校旨在提高每一位学员的语言表达能力、作文写作能力、个人才艺展示能力和文艺主持能力。从基础的语言发声到舞台表演都将有专业的老师进行系统的培训指导。<br style=\"margin: 0px; padding: 0px;\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;从小锻炼孩子的演讲、解说、辩论、主持能力便可以增强孩子的自信心，提高孩子的心理素质，对孩子未来的学习能力以及人际交往能力都有着不可估量的影响。<br style=\"margin: 0px; padding: 0px;\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;学校采用综合的艺术培训形式，从基础的语言发音、形体训练、情景表演、儿歌演唱、朗诵练习到专业的影视对话、曲艺表演、模拟主持等逐步的克服学生的胆怯、羞涩……不自信、不善言谈等缺点，完全提高学生的自我表现意识和个性的张扬能力！</span></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">百元体验课程设置：</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">1、律动舞蹈</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">2、成兰脱口秀</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">3、妙语学堂</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">4、注意力学院</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">5、天天舞台秀</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">6、超强记忆小天地</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-weight:=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\" color:=\"\">7、户外实践活动</span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/><br style=\"margin: 0px; padding: 0px;\"/><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb23033d191.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb23036da62.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb2303baf69.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb23040748b.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb2304316e3.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb23047a968.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb2304aa90d.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb2304dd21a.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p><p><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb2305300c8.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160928/57eb2305563df.jpg\" style=\"margin: 0px; padding: 0px; border: 0px; font-family: inherit; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; vertical-align: top; max-width: 100%; display: inline;\"/></p></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"><br style=\"margin: 0px; padding: 0px;\"/></span><span style=\"margin: 0px; padding: 0px; border: 0px; font-family: \" microsoft=\"\" font-stretch:=\"\" line-height:=\"\" vertical-align:=\"\" word-break:=\"\" word-wrap:=\"\"></span></p><p></p>', '1', 'form_tpl5', 'coupon_price', 'templet5', '', '', '1493568000', '0', '1', '北京星光影视园', '0', '0', '/data/upload/20170517/591ba67bcc977.jpg', '0', '100', '/data/upload/20170517/591ba680817a1.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');
INSERT INTO `cmf_form` VALUES ('106', '青色打印模版', '路老师', '15000000000', '', '', '2', '0', '[{\"url\":\"20170517\\/591ba79251fe7.jpg\",\"alt\":\"t1\"},{\"url\":\"20170517\\/591ba798b1481.jpg\",\"alt\":\"t2\"},{\"url\":\"20170517\\/591ba79f22bc3.jpg\",\"alt\":\"t3\"},{\"url\":\"20170517\\/591ba7a3d17d0.jpg\",\"alt\":\"4\"}]', '<section style=\"margin: 0px 0px 0px -0.5em; padding: 0px; max-width: 100%; box-sizing: border-box; font-family: 微软雅黑; white-space: pre-wrap; line-height: 1.4; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 3px 8px; max-width: 100%; box-sizing: border-box; border-color: rgb(0, 0, 0); border-radius: 4px; color: rgb(167, 23, 0); font-size: 1em; display: inline-block; transform: rotate(-10deg); transform-origin: 0% 100% 0px; word-wrap: break-word !important; background-color: rgb(255, 179, 167);\"><span class=\"\" data-brushtype=\"text\" style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(255, 255, 255); box-sizing: border-box !important; word-wrap: break-word !important;\">暑期课程</span></section></section><p><br/></p><section class=\"\" data-style=\"line-height:24px;color:rgb(89, 89, 89); font-size:14px\" style=\"margin: -24px 0px 0px; padding: 22px 16px 16px; max-width: 100%; box-sizing: border-box; font-family: 微软雅黑; line-height: 25px; white-space: pre-wrap; border: 1px solid rgb(255, 179, 167); font-size: 14px; word-wrap: break-word !important;\"><section class=\"\" data-source=\"bj.96weixin.com\" style=\"margin: 0px; padding: 0px; max-width: 100%; line-height: 25.6px; white-space: normal; box-sizing: border-box !important; word-wrap: break-word !important;\"><section><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(192, 0, 0);\"><strong><span style=\"font-size: 18px;\"><br/></span></strong></span></p><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(192, 0, 0);\"><strong><span style=\"font-size: 18px;\">暑期开课时间：7月1日（针对暑期生）</span></strong></span></p><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(192, 0, 0);\"><strong><span style=\"font-size: 18px;\"><br/></span></strong></span></p></section></section><section data-source=\"bj.96weixin.com\"><section><section><p style=\"line-height: 1.5em;\"><strong><span style=\"font-size: 18px; color: rgb(84, 141, 212);\">>>>></span></strong><span style=\"color: rgb(31, 73, 125); font-size: 18px; line-height: 1.5em;\">学员年龄：3.5岁-12岁</span></p></section></section></section><section data-source=\"bj.96weixin.com\"><section><section><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(84, 141, 212);\"><strong><span style=\"font-size: 18px;\">>>>></span></strong></span><span style=\"font-size: 18px; color: rgb(31, 73, 125);\">班级人数：每班12人，小班制教学</span></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px; color: rgb(31, 73, 125);\"><br/></span></p></section></section></section><section data-source=\"bj.96weixin.com\"><section><section><p style=\"line-height: 1.5em;\"><strong><span style=\"font-size: 18px; color: rgb(192, 0, 0);\">庆缤纷鸟课程升级及南街校区开馆，特惠如下：</span></strong></p><p style=\"line-height: 1.5em;\"><strong><span style=\"font-size: 18px; color: rgb(192, 0, 0);\"><br/></span></strong></p><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(84, 141, 212);\"><strong><span style=\"font-size: 18px;\">>>>></span></strong></span><span style=\"font-size: 18px; color: rgb(31, 73, 125);\">暑期费用：298元每人（原价800元）</span></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px; color: rgb(31, 73, 125);\"><br/></span></p></section></section></section><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(192, 0, 0);\"><strong><span style=\"font-size: 18px;\">优惠一</span></strong></span><span style=\"font-size: 18px;\">：凡前30名新生报名暑期课程即可获赠价值30元缤纷鸟T恤衫一件</span></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px;\"><br/></span></p><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(192, 0, 0);\"><strong><span style=\"font-size: 18px;\">优惠二</span></strong></span><span style=\"font-size: 18px;\">：新生报长年，100元上800元暑期</span></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px;\"><br/></span></p><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(192, 0, 0);\"><strong><span style=\"font-size: 18px;\">优惠三</span></strong></span><span style=\"font-size: 18px;\">：老带新报长年，老生获200元代金券/幸运转盘抽奖一次（限7月10日前）</span></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px;\"><br/></span></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px;\">新生报长年，100元上800元暑期，获老带新100元卡一张（可冲抵）</span></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px; color: rgb(192, 0, 0);\"><br/></span></p><p style=\"line-height: 1.5em;\"><span style=\"color: rgb(192, 0, 0);\"><strong><span style=\"font-size: 18px;\">优惠四</span></strong></span><span style=\"font-size: 18px;\">：新老学员家长转发此消息至朋友圈，连续转发7天即可到校领取缤纷鸟特制文具盒一个</span></p><p><br/></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px;\"><br/></span></p><p style=\"line-height: 1.5em;\"><strong><span style=\"font-size: 18px;\">翠柳路校区：</span></strong></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px;\">每周二、四/三、五授课（晚上免费故事会）</span></p><p style=\"line-height: 1.5em;\"><strong><span style=\"font-size: 18px;\">南街校区：</span></strong></p><p style=\"line-height: 1.5em;\"><span style=\"font-size: 18px;\">每周三、五、日/二、四、六授课。</span></p></section><section data-source=\"bj.96weixin.com\" style=\"white-space: normal;\"><section data-source=\"bj.96weixin.com\"><section><section><section><section></section><section style=\"margin: 0px; padding: 0px; max-width: 100%; clear: both; height: 0px; box-sizing: border-box !important; word-wrap: break-word !important;\"></section></section></section></section></section><p style=\"padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/></p><section class=\"\" data-source=\"bj.96weixin.com\" style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 1em auto; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 0px auto; padding: 0px; max-width: 100%; width: 490px; height: auto; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: -26px auto 0px; padding: 0px; max-width: 100%; color: rgb(51, 51, 51); width: 294px; box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; clear: both; height: 0px; box-sizing: border-box !important; word-wrap: break-word !important;\"></section></section></section></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px auto; padding: 0px; max-width: 100%; clear: both; width: 502px; overflow: hidden; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 0.5em 0.5em 1.5em; padding: 0px; max-width: 100%; border: 0px; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0em 0px 0px; padding: 0px; max-width: 100%; border: 2px solid rgb(0, 176, 80); border-radius: 0.8em; color: rgb(51, 51, 51); font-size: 1em; box-shadow: rgb(189, 189, 189) 0px 3px 5px; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 1.4em 5em 1em 1em; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; line-height: 1.4; text-decoration: inherit; box-sizing: border-box !important; word-wrap: break-word !important;\"><p><strong><span style=\"font-size: 20px; color: rgb(151, 72, 6);\">亲爱的小朋友们让你们久等啦！</span></strong></p><p><strong><span style=\"font-size: 20px; color: rgb(151, 72, 6);\">这个暑期缤纷鸟带你环球旅行喽！！</span></strong></p><p><strong><span style=\"font-size: 20px; color: rgb(151, 72, 6);\">我们需要你准备好：活跃的大脑、饱满的热情、蠢蠢欲动的画笔！</span></strong></p><p><strong><span style=\"font-size: 20px; color: rgb(151, 72, 6);\">其它的交给我们您给我时间！</span></strong></p><p><strong><span style=\"font-size: 20px; color: rgb(151, 72, 6);\">我还您精彩！！！</span></strong><span style=\"font-size: 20px; color: rgb(151, 72, 6);\">&nbsp;</span><strong><span style=\"color: rgb(151, 72, 6);\"></span></strong><span style=\"text-align: right; line-height: 1.4; text-decoration: inherit;\">&nbsp;&nbsp;&nbsp;</span><span style=\"text-align: right; line-height: 1.4; text-decoration: inherit; font-size: 1em;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></p></section></section></section></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px auto; padding: 0px; max-width: 100%; clear: both; width: 502px; overflow: hidden; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 0.5em 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: -1px 5px; padding: 4px; max-width: 100%; border: 1px solid rgb(0, 176, 80); box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 1em; max-width: 100%; border: 1px solid rgb(0, 176, 80); box-sizing: border-box !important; word-wrap: break-word !important;\"><p><span style=\"color: rgb(227, 108, 9);\"><strong><span style=\"font-size: 20px;\">环球主题课程：</span></strong></span></p><p><br/></p><p><span style=\"color: rgb(227, 108, 9);\"><strong><span style=\"font-size: 20px;\">巴黎《时装周》 &nbsp;巴西《足球之夜》</span></strong></span></p><p><span style=\"color: rgb(227, 108, 9);\"><strong><span style=\"font-size: 20px;\">德国《环球旅行》 &nbsp;韩国《韩国美食文化》</span></strong></span></p><p><span style=\"color: rgb(227, 108, 9);\"><strong><span style=\"font-size: 20px;\">马尔代夫《缤纷假日》 &nbsp;美国《迪士尼》</span></strong></span></p><p><span style=\"color: rgb(227, 108, 9);\"><strong><span style=\"font-size: 20px;\">日本《樱花盛开》 &nbsp;意大利《我和大师有个约会》</span></strong></span></p><p><span style=\"color: rgb(227, 108, 9);\"><strong><span style=\"font-size: 20px;\">印度《神秘国度》 &nbsp;中国《传统文化》</span></strong></span></p><p><span style=\"color: rgb(227, 108, 9);\"><strong><span style=\"font-size: 20px;\">.............</span></strong></span><strong><span style=\"font-size: 20px; color: rgb(227, 108, 9);\"></span></strong><strong><span style=\"font-size: 20px; color: rgb(227, 108, 9);\"></span></strong><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/></p></section></section><section style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; height: 6px; color: inherit; word-wrap: break-word !important;\"><p><br/></p></section></section></section></section><p><br/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"color: rgb(255, 0, 0); font-size: 20px;\"><strong>当然，你需要这个！</strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\"><br/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160721/57908fa8cc073.png\" title=\"3.png\" alt=\"3.png\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><br/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\">你是不是很想看看啊？？！</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\">&nbsp;</p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\"><br/></p><p style=\"white-space: normal;\"><br/></p><p><br/></p><section data-source=\"bj.96weixin.com\" style=\"white-space: normal;\"><section><section data-source=\"bj.96weixin.com\"><section><section><section><section></section></section><section style=\"margin: -40px 0px 0px; padding: 0px; max-width: 100%; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><p style=\"padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(30, 178, 225); line-height: 32px; font-size: 24px; box-sizing: border-box !important; word-wrap: break-word !important;\">让我们先睹为快吧！</span></strong></p></section></section></section></section></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section weixin-id=\"22\" class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; border-top-color: rgb(27, 0, 235); box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 15px 0px 0px; padding: 0px; max-width: 100%; -webkit-box-reflect: below 0px -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), color-stop(0.2, transparent), to(rgba(250, 250, 250, 0.298039))); line-height: 20px; box-sizing: border-box !important; word-wrap: break-word !important;\"><p style=\"padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><strong style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 20px; line-height: 32px; white-space: normal; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">第一站：中国——江南水乡</strong><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/></p></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 2em auto 1em; padding: 0px; max-width: 100%; border: none; text-align: center; width: 20em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; width: 16em; border-top-style: solid; border-top-width: 2px; border-top-color: rgb(5, 52, 109); display: inline-block; color: rgb(21, 187, 60); box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: -0.9em 0px 0px; padding: 0px; max-width: 100%; height: 1.4em; line-height: 1.4em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 1.25em; color: rgb(0, 15, 105); min-width: 6em; display: inline-block; border-top-color: rgb(5, 52, 109); border-right-color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px -1px 0px 0px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section><p class=\"\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px 10px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">江南水乡</p><section class=\"\" style=\"margin: 0px 0px 0px -2px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section></section></section></section><section style=\"margin: 1em auto; padding: 0px; max-width: 100%; width: 20em; overflow: hidden; box-sizing: border-box !important; word-wrap: break-word !important;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160721/57909025ed310.png\" title=\"4.png\" alt=\"4.png\"/><section style=\"margin: 0px 0px 0px -2em; padding: 0px; max-width: 100%; height: 0px; border-style: solid; border-width: 2em 0em 2em 2em; border-color: rgb(255, 255, 255) transparent transparent; display: inline-block; vertical-align: top; width: 0px; box-sizing: border-box !important; word-wrap: break-word !important;\"><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/></section></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section weixin-id=\"22\" class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; border-top-color: rgb(27, 0, 235); box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 15px 0px 0px; padding: 0px; max-width: 100%; -webkit-box-reflect: below 0px -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), color-stop(0.2, transparent), to(rgba(250, 250, 250, 0.298039))); line-height: 20px; box-sizing: border-box !important; word-wrap: break-word !important;\"><p style=\"padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><strong style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 20px; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">第二站：韩国——韩式烤肉</strong></p></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 2em auto 1em; padding: 0px; max-width: 100%; border: none; text-align: center; width: 20em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; width: 16em; border-top-style: solid; border-top-width: 2px; border-top-color: rgb(5, 52, 109); display: inline-block; color: rgb(21, 187, 60); box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: -0.9em 0px 0px; padding: 0px; max-width: 100%; height: 1.4em; line-height: 1.4em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 1.25em; color: rgb(0, 15, 105); min-width: 6em; display: inline-block; border-top-color: rgb(5, 52, 109); border-right-color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px -1px 0px 0px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section><p class=\"\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px 10px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">韩式烤肉</p><section class=\"\" style=\"margin: 0px 0px 0px -2px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section></section></section></section><section style=\"margin: 1em auto; padding: 0px; max-width: 100%; width: 20em; overflow: hidden; box-sizing: border-box !important; word-wrap: break-word !important;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160721/5790903129bb1.png\" title=\"6.png\" alt=\"6.png\"/><section style=\"margin: 0px 0px 0px -2em; padding: 0px; max-width: 100%; height: 0px; border-style: solid; border-width: 2em 0em 2em 2em; border-color: rgb(255, 255, 255) transparent transparent; display: inline-block; vertical-align: top; width: 0px; box-sizing: border-box !important; word-wrap: break-word !important;\"><br/></section></section></section></section><p><br/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><strong style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 20px; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">第三站：日本——樱花祭</strong></p><p><br/></p><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 2em auto 1em; padding: 0px; max-width: 100%; border: none; text-align: center; width: 20em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; width: 16em; border-top-style: solid; border-top-width: 2px; border-top-color: rgb(5, 52, 109); display: inline-block; color: rgb(21, 187, 60); box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: -0.9em 0px 0px; padding: 0px; max-width: 100%; height: 1.4em; line-height: 1.4em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 1.25em; color: rgb(0, 15, 105); min-width: 6em; display: inline-block; border-top-color: rgb(5, 52, 109); border-right-color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px -1px 0px 0px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section><p class=\"\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px 10px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">樱花祭</p><section class=\"\" style=\"margin: 0px 0px 0px -2px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section></section></section></section><section><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160721/579090457515c.png\" title=\"8.png\" alt=\"8.png\"/><section style=\"margin: 0px 0px 0px -2em; padding: 0px; max-width: 100%; height: 0px; border-style: solid; border-width: 2em 0em 2em 2em; border-color: rgb(255, 255, 255) transparent transparent; display: inline-block; vertical-align: top; width: 0px; box-sizing: border-box !important; word-wrap: break-word !important;\"><br/></section></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section weixin-id=\"22\" class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; border-top-color: rgb(27, 0, 235); box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 15px 0px 0px; padding: 0px; max-width: 100%; -webkit-box-reflect: below 0px -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), color-stop(0.2, transparent), to(rgba(250, 250, 250, 0.298039))); line-height: 20px; box-sizing: border-box !important; word-wrap: break-word !important;\"><p style=\"padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><strong style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 20px; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">第四站：马尔代夫——缤纷假日</strong></p></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 2em auto 1em; padding: 0px; max-width: 100%; border: none; text-align: center; width: 20em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; width: 16em; border-top-style: solid; border-top-width: 2px; border-top-color: rgb(5, 52, 109); display: inline-block; color: rgb(21, 187, 60); box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: -0.9em 0px 0px; padding: 0px; max-width: 100%; height: 1.4em; line-height: 1.4em; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; font-size: 1.25em; color: rgb(0, 15, 105); min-width: 6em; display: inline-block; border-top-color: rgb(5, 52, 109); border-right-color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px -1px 0px 0px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section><p class=\"\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px 10px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);\">缤纷假日</p><section class=\"\" style=\"margin: 0px 0px 0px -2px; padding: 0px; max-width: 100%; font-size: 14px; display: inline-block; color: rgb(5, 52, 109); box-sizing: border-box !important; word-wrap: break-word !important;\">●</section></section></section></section><section><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/><p><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160721/57909056b8fca.png\" title=\"9.png\" alt=\"9.png\"/></p><p><br/></p></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section style=\"margin: 0.5em 0px; padding: 0px; max-width: 100%; box-sizing: border-box; border: none; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; text-align: center; font-size: 1em; font-weight: inherit; text-decoration: inherit; color: rgb(254, 254, 254); border-color: rgb(30, 178, 225); clear: both; word-wrap: break-word !important;\"><section style=\"margin: 60px 0px -10px; padding: 0px; max-width: 100%; box-sizing: border-box; color: inherit; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; border-top-width: 2px; border-top-style: solid; width: 502px; border-color: rgb(30, 178, 225); color: inherit; word-wrap: break-word !important;\"></section></section><section style=\"margin: -50px 0px 0px; padding: 0px; max-width: 100%; width: 376.5px; display: inline-block; color: inherit; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px 0px 10px; padding: 0px; max-width: 100%; width: 376.5px; border: 1px solid rgb(30, 178, 225); color: inherit; display: inline-block; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 8px; padding: 12px; max-width: 100%; color: rgb(255, 255, 238); border-color: rgb(203, 224, 249); box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(30, 178, 225);\"><p class=\"\" style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; line-height: 24px; border-color: rgb(30, 178, 225); color: rgb(255, 255, 255); font-size: 20px; box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"font-size: 24px;\">暑期环球大师班期待与你一起起航哦~</span></p></section></section></section></section></section></section><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; border: 0px none; box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; line-height: 1.6; box-sizing: border-box !important; word-wrap: break-word !important;\">三所校区同时招生，限招200名</span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; border: 0px none; box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; line-height: 1.6; box-sizing: border-box !important; word-wrap: break-word !important;\">另暑期特惠暑期夏令营开始报名啦，详情校内咨询。</span></p></section><p><br/></p><p style=\"white-space: normal;\"><br/></p><p><br/></p><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; border: 0px none; box-sizing: border-box !important; word-wrap: break-word !important;\"><section class=\"\" style=\"margin: 0px; padding: 5px 30px 5px 5px; max-width: 100%; box-sizing: border-box; border-radius: 0px 20px 20px 0px; display: inline-block; color: rgb(255, 255, 238); word-wrap: break-word !important; background-color: rgb(30, 178, 225);\"><section style=\"margin: 0px; padding: 0px; max-width: 100%; font-weight: bold; color: rgb(255, 255, 255); box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; white-space: pre-wrap; line-height: 1.6; font-size: 24px; box-sizing: border-box !important; word-wrap: break-word !important;\">连续转发7天即可到校领取缤纷鸟特制文具盒一个！</span></section></section></section></section><p><br/></p><p style=\"white-space: normal;\"><br/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160721/5790906f9be00.png\" title=\"11.png\" alt=\"11.png\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\"><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(255, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important;\"><strong style=\"margin: 0px; padding: 0px; max-width: 100%; font-family: 宋体; font-size: 20px; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important;\"><br/></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(255, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important;\"><strong style=\"margin: 0px; padding: 0px; max-width: 100%; font-family: 宋体; font-size: 20px; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important;\">精彩暑期就在缤纷鸟，不要犹豫和我们一起踏上环球之旅吧！</strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; box-sizing: border-box !important; word-wrap: break-word !important;\"><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(255, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important;\"><strong style=\"margin: 0px; padding: 0px; max-width: 100%; font-family: 宋体; font-size: 20px; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important;\"><br/></strong></span></p><p><br/></p><section class=\"\" data-source=\"bj.96weixin.com\" style=\"white-space: normal; margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; text-align: center; box-sizing: border-box !important; word-wrap: break-word !important;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160721/5790909e630dc.png\" title=\"10.png\" alt=\"10.png\"/></p></section><p><br/></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\">【我们的教学理念】</span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\">用思维的话语画出缤纷的墨彩</span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\"><br/></span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\">【我们的价值观】</span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\">激情 责任 团队合作 教学相长</span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\"><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/>【我们的团队精神】</span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\">创新、协作、勤学、超越</span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\"><br style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;\"/>【我们的学校校训】</span></strong></span></p><p style=\"white-space: normal; text-align: center;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\">行有不得，反求诸己</span></strong></span></p><p style=\"white-space: normal;\"><span style=\"font-size: 20px;\"><strong><span style=\"color: rgb(112, 48, 160);\"><br/></span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160720/578f563da8477.png\" title=\"截图1469010495.png\" alt=\"截图1469010495.png\"/></span></strong></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><br/></span></strong></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><br/></span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">总校：<span style=\"margin: 0px; padding: 0px; max-width: 100%; line-height: 25.6px; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">******</span></span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"color: rgb(0, 0, 0);\"><span times=\"\" new=\"\" style=\"margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">南街校址：</span><span style=\"margin: 0px; padding: 0px; max-width: 100%; font-family: 微软雅黑; letter-spacing: 0px; line-height: 1.6; box-sizing: border-box !important; word-wrap: break-word !important;\">******</span></span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">电话：******</span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">微信公众号：******</span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">我们的网址：******</span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" text-align:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\">全国免费服务电话：******</span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><span style=\"background-color: rgb(146, 208, 80);\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><br/></span></strong></span></p><p style=\"margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; min-height: 1em; white-space: pre-wrap; color: rgb(62, 62, 62); font-family: \" helvetica=\"\" hiragino=\"\" sans=\"\" microsoft=\"\" line-height:=\"\" box-sizing:=\"\" border-box=\"\" word-wrap:=\"\" break-word=\"\" background-color:=\"\"><strong><span style=\"margin: 0px; padding: 0px; max-width: 100%; color: rgb(0, 0, 0); box-sizing: border-box !important; word-wrap: break-word !important; background-image: initial; background-attachment: initial; background-size: initial; background-origin: initial; background-clip: initial; background-position: initial; background-repeat: initial;\"><br/></span></strong></p><p style=\"white-space: normal; text-align: center;\"><img src=\"http://huodong.tongyishidai.com/data/upload/ueditor/20160720/578f564e6c2c3.png\" title=\"1.png\" alt=\"1.png\"/></p><p><br/></p>', '1', 'form_tpl6', 'coupon_price', 'templet6', '', '', '1493568000', '1717084800', '1', '北京', '0', '0', '/data/upload/20170517/591ba72959bed.jpg', '0', '100', '/data/upload/20170517/591ba72ed04d0.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');
INSERT INTO `cmf_form` VALUES ('107', '温馨系列模版', '路老师', '15000000000', '', '', '2', '0', '[{\"url\":\"20170517\\/591ba8c302af7.jpg\",\"alt\":\"1\"},{\"url\":\"20170517\\/591ba8c84ce0c.jpg\",\"alt\":\"2\"},{\"url\":\"20170517\\/591ba8cdeba21.jpg\",\"alt\":\"3\"},{\"url\":\"20170517\\/591ba8d319868.jpg\",\"alt\":\"4\"}]', '', '1', 'form_tpl7', 'coupon_price', 'templet7', '', '', '1493568000', '0', '1', '北京', '0', '0', '/data/upload/20170517/591ba87aa3ae3.jpg', '0', '100', '/data/upload/20170517/591ba87f934f3.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');
INSERT INTO `cmf_form` VALUES ('108', '边框矢量模版', '路老师', '15000000000', '', '分销规则', '2', '0', '[{\"url\":\"20170517\\/591ba9628a62e.jpg\",\"alt\":\"1\"},{\"url\":\"20170517\\/591ba967d32b0.jpg\",\"alt\":\"2\"},{\"url\":\"20170517\\/591ba96d9e6bc.jpg\",\"alt\":\"3\"},{\"url\":\"20170517\\/591ba9727e65f.jpg\",\"alt\":\"4\"}]', '', '1', 'form_tpl8', 'coupon_price', 'templet8', '', '', '1493568000', '0', '1', '北京', '0', '0', '/data/upload/20170517/591ba912a4957.jpg', '0', '100', '/data/upload/20170517/591ba918a9eda.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');
INSERT INTO `cmf_form` VALUES ('109', '欢乐周年庆', '路老师', '15000000000', '', '', '2', '0', '[{\"url\":\"20170517\\/591baa0c0714c.jpg\",\"alt\":\"1\"},{\"url\":\"20170517\\/591baa128ccee.jpg\",\"alt\":\"2\"},{\"url\":\"20170517\\/591baa18b9cfe.jpg\",\"alt\":\"3\"},{\"url\":\"20170517\\/591baa1ec4127.jpg\",\"alt\":\"4\"}]', '', '1', 'form_tpl9', 'coupon_price', 'templet9', '', '', '1493568000', '1559232000', '1', '北京', '0', '0', '/data/upload/20170517/591ba9a2e0e9e.jpg', '0', '100', '/data/upload/20170517/591ba9a81d02c.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');
INSERT INTO `cmf_form` VALUES ('110', '万圣节模板', '路老师', '15000000000', '', '', '2', '0', '[{\"url\":\"20170517\\/591baa975a234.jpg\",\"alt\":\"1\"}]', '<p style=\"line-height: 1.5em;\"><strong><span style=\"font-size: 18px;\">活动将在万圣节当天晚六点正式开始！小伙伴们快来报名参加吧！<br/></span></strong></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\"><span style=\"font-size: 24px; color: rgb(255, 192, 0);\">万圣节的由来：</span></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\">万圣节是西方国家的传统节日。传说在公元前五百年，那时的人们相信，故人的亡魂会在万圣节前夜回到故居地，在活人身上找寻生灵，借此再生，而且这是人在死后能获得再生的唯一希望。而活着的人则惧怕死人的魂灵来夺生，于是人们就在这一天熄掉炉火、烛光，让死人的魂灵无法找到活人，又把自己打扮成妖魔鬼怪把死人的魂灵吓走。之后，他们又会把火种、烛光重新燃起，开始新的一年的生活。经历岁月的变迁，万圣夜早期的习俗也保留了部分下来。在这一天，孩子们带着开玩笑的心理穿戴上各种奇怪的服饰和面具，参加万圣夜舞会、向点灯的人家讨糖果。</p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\"><span style=\"font-size: 24px; color: rgb(255, 192, 0);\">当天活动：</span></p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\">马修斯邀请孩子和家长一同来参加万圣夜化妆舞会！活动当天，孩子们可以和装扮成巫师、天使和恶魔的老师们一起做游戏、得奖品。</p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\"><span style=\"color: rgb(255, 0, 0); font-size: 20px;\">活动一：塔罗牌算命</span></p><p style=\"white-space: normal; line-height: 1.5em;\">小朋友持门票免费进行塔罗牌测试一次，算出不同结果可以兑换不同的糖果和礼品，还有机会获得南瓜灯制作大赛名额。</p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\"><span style=\"color: rgb(255, 0, 0); font-size: 20px;\">活动二：南瓜灯制作大赛</span></p><p style=\"white-space: normal; line-height: 1.5em;\">获得南瓜灯制作大赛名额的孩子在家长陪同下上台参赛，在十分钟内两人合作完成一个南瓜灯。完成后现场观众持糖果投票，糖果票数最高的一组可以获得特殊大奖。</p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\"><span style=\"color: rgb(255, 0, 0); font-size: 20px;\">活动三：南瓜灯猜谜</span></p><p style=\"white-space: normal; line-height: 1.5em;\">老师们会分散站在会场的各个猜谜点，给小朋友出题，答对的小朋友可以获得糖果若干。</p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\"><span style=\"color: rgb(255, 0, 0); font-size: 20px;\">活动四：糖果大派送</span></p><p style=\"white-space: normal; line-height: 1.5em;\">每位小朋友都可领取一个纸袋，写上自己的名字，赠送给身边的小伙伴，作为共同参加活动的纪念。</p><p style=\"white-space: normal;\"><br/></p><p style=\"white-space: normal; line-height: 1.5em;\"><span style=\"color: rgb(255, 0, 0); font-size: 20px;\">活动五：群魔乱舞</span></p><p style=\"white-space: normal; line-height: 1.5em;\">音乐响起，老师在台上教几个简单的舞蹈动作，集体起舞，晚会在热闹的气氛中结束。</p><p><br/></p>', '1', 'form_tpl10', 'coupon_price', 'templet10', '', '', '1493568000', '2032617600', '0', '北京', '0', '0', '/data/upload/20170517/591baa464697e.jpg', '0', '100', '/data/upload/20170517/591baa4a846bf.jpg', '1', '1', null, '0', '0', '0', null, '1', null, '0.00');

-- ----------------------------
-- Table structure for cmf_form_data
-- ----------------------------
DROP TABLE IF EXISTS `cmf_form_data`;
CREATE TABLE `cmf_form_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) DEFAULT '0' COMMENT '表单id',
  `item_id` int(11) DEFAULT NULL COMMENT '表单项id',
  `value` text COMMENT '数值',
  `uid` int(11) DEFAULT '0' COMMENT '用户标示',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `name` varchar(255) DEFAULT NULL COMMENT '邀请人名字',
  `tel` varchar(255) DEFAULT NULL COMMENT '邀请人电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报名表单数据';

-- ----------------------------
-- Records of cmf_form_data
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_form_item
-- ----------------------------
DROP TABLE IF EXISTS `cmf_form_item`;
CREATE TABLE `cmf_form_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '表单名称',
  `form_id` int(11) NOT NULL DEFAULT '0' COMMENT '表单id',
  `type` varchar(255) DEFAULT '1' COMMENT '1 文本框 2代表选择框 3代表日期',
  `default` varchar(500) DEFAULT NULL COMMENT '默认值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='表单项目';

-- ----------------------------
-- Records of cmf_form_item
-- ----------------------------
INSERT INTO `cmf_form_item` VALUES ('1', '', '103', '2', '');
INSERT INTO `cmf_form_item` VALUES ('2', '', '103', '', '');
INSERT INTO `cmf_form_item` VALUES ('3', '', '103', '', '');
INSERT INTO `cmf_form_item` VALUES ('4', '', '104', '2', '');
INSERT INTO `cmf_form_item` VALUES ('5', '', '104', '', '');
INSERT INTO `cmf_form_item` VALUES ('6', '', '104', '', '');
INSERT INTO `cmf_form_item` VALUES ('7', '', '105', '2', '');
INSERT INTO `cmf_form_item` VALUES ('8', '', '105', '', '');
INSERT INTO `cmf_form_item` VALUES ('9', '', '105', '', '');
INSERT INTO `cmf_form_item` VALUES ('10', '', '106', '2', '');
INSERT INTO `cmf_form_item` VALUES ('11', '', '106', '', '');
INSERT INTO `cmf_form_item` VALUES ('12', '', '106', '', '');
INSERT INTO `cmf_form_item` VALUES ('13', '', '107', '2', '');
INSERT INTO `cmf_form_item` VALUES ('14', '', '107', '', '');
INSERT INTO `cmf_form_item` VALUES ('15', '', '107', '', '');
INSERT INTO `cmf_form_item` VALUES ('16', '', '108', '2', '');
INSERT INTO `cmf_form_item` VALUES ('17', '', '108', '', '');
INSERT INTO `cmf_form_item` VALUES ('18', '', '108', '', '');
INSERT INTO `cmf_form_item` VALUES ('19', '', '109', '2', '');
INSERT INTO `cmf_form_item` VALUES ('20', '', '109', '', '');
INSERT INTO `cmf_form_item` VALUES ('21', '', '109', '', '');
INSERT INTO `cmf_form_item` VALUES ('22', '', '110', '2', '');
INSERT INTO `cmf_form_item` VALUES ('23', '', '110', '1', '');
INSERT INTO `cmf_form_item` VALUES ('24', '', '110', '1', '');

-- ----------------------------
-- Table structure for cmf_form_rows
-- ----------------------------
DROP TABLE IF EXISTS `cmf_form_rows`;
CREATE TABLE `cmf_form_rows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user` varchar(255) DEFAULT NULL COMMENT '用户标示',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `form_id` int(11) DEFAULT '0' COMMENT '表单id',
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(1000) DEFAULT NULL COMMENT '头像',
  `pid` int(11) DEFAULT '0' COMMENT '邀请者id',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `mobile` varchar(255) DEFAULT NULL COMMENT '手机号',
  `type` varchar(255) DEFAULT NULL COMMENT '报名类型',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` tinyint(3) DEFAULT '1' COMMENT '1为有效报名 0为取消报名',
  `share_hits` int(11) DEFAULT '0' COMMENT '分享次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='报名函数';

-- ----------------------------
-- Records of cmf_form_rows
-- ----------------------------
INSERT INTO `cmf_form_rows` VALUES ('1', null, '1494915346', '102', null, null, '0', '路人甲', '15090067067', '1', null, '1', '0');
INSERT INTO `cmf_form_rows` VALUES ('2', null, '1494920230', '102', null, null, '0', '路人丙', '15090067066', '1', null, '1', '0');
INSERT INTO `cmf_form_rows` VALUES ('3', null, '1494920252', '100', null, null, '0', '路人甲', '15090067067', '1', null, '1', '0');

-- ----------------------------
-- Table structure for cmf_guestbook
-- ----------------------------
DROP TABLE IF EXISTS `cmf_guestbook`;
CREATE TABLE `cmf_guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) NOT NULL COMMENT '留言者姓名',
  `email` varchar(100) DEFAULT NULL COMMENT '留言者邮箱',
  `title` varchar(255) DEFAULT NULL COMMENT '留言标题',
  `msg` text COMMENT '留言内容',
  `createtime` datetime DEFAULT NULL COMMENT '留言时间',
  `status` smallint(2) NOT NULL DEFAULT '1' COMMENT '留言状态，1：正常，0：删除',
  `tel` varchar(32) DEFAULT NULL COMMENT '留言电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='留言表';

-- ----------------------------
-- Records of cmf_guestbook
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_links
-- ----------------------------
DROP TABLE IF EXISTS `cmf_links`;
CREATE TABLE `cmf_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL COMMENT '友情链接地址',
  `link_name` varchar(255) NOT NULL COMMENT '友情链接名称',
  `link_image` varchar(255) DEFAULT NULL COMMENT '友情链接图标',
  `link_target` varchar(25) NOT NULL DEFAULT '_blank' COMMENT '友情链接打开方式',
  `link_description` text NOT NULL COMMENT '友情链接描述',
  `link_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `link_rating` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接评级',
  `link_rel` varchar(255) DEFAULT NULL COMMENT '链接与网站的关系',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='友情链接表';

-- ----------------------------
-- Records of cmf_links
-- ----------------------------
INSERT INTO `cmf_links` VALUES ('1', 'http://www.thinkcmf.com', 'ThinkCMF', '', '_blank', '', '1', '0', '', '0');

-- ----------------------------
-- Table structure for cmf_menu
-- ----------------------------
DROP TABLE IF EXISTS `cmf_menu`;
CREATE TABLE `cmf_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `app` char(20) NOT NULL COMMENT '应用名称app',
  `model` char(20) NOT NULL COMMENT '控制器',
  `action` char(20) NOT NULL COMMENT '操作名称',
  `data` char(50) NOT NULL COMMENT '额外参数',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单类型  1：权限认证+菜单；0：只作为菜单',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，1显示，0不显示',
  `name` varchar(50) NOT NULL COMMENT '菜单名称',
  `icon` varchar(50) DEFAULT NULL COMMENT '菜单图标',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `parentid` (`parentid`) USING BTREE,
  KEY `model` (`model`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of cmf_menu
-- ----------------------------
INSERT INTO `cmf_menu` VALUES ('1', '0', 'Admin', 'Content', 'default', '', '0', '1', '内容管理', 'th', '', '30');
INSERT INTO `cmf_menu` VALUES ('2', '1', 'Api', 'Guestbookadmin', 'index', '', '1', '1', '所有留言', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('3', '2', 'Api', 'Guestbookadmin', 'delete', '', '1', '0', '删除网站留言', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('4', '1', 'Comment', 'Commentadmin', 'index', '', '1', '1', '评论管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('5', '4', 'Comment', 'Commentadmin', 'delete', '', '1', '0', '删除评论', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('6', '4', 'Comment', 'Commentadmin', 'check', '', '1', '0', '评论审核', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('7', '1', 'Portal', 'AdminPost', 'index', '', '1', '1', '文章管理', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('8', '7', 'Portal', 'AdminPost', 'listorders', '', '1', '0', '文章排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('9', '7', 'Portal', 'AdminPost', 'top', '', '1', '0', '文章置顶', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('10', '7', 'Portal', 'AdminPost', 'recommend', '', '1', '0', '文章推荐', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('11', '7', 'Portal', 'AdminPost', 'move', '', '1', '0', '批量移动', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('12', '7', 'Portal', 'AdminPost', 'check', '', '1', '0', '文章审核', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('13', '7', 'Portal', 'AdminPost', 'delete', '', '1', '0', '删除文章', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('14', '7', 'Portal', 'AdminPost', 'edit', '', '1', '0', '编辑文章', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('15', '14', 'Portal', 'AdminPost', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('16', '7', 'Portal', 'AdminPost', 'add', '', '1', '0', '添加文章', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('17', '16', 'Portal', 'AdminPost', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('18', '1', 'Portal', 'AdminTerm', 'index', '', '0', '1', '分类管理', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('19', '18', 'Portal', 'AdminTerm', 'listorders', '', '1', '0', '文章分类排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('20', '18', 'Portal', 'AdminTerm', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('21', '18', 'Portal', 'AdminTerm', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('22', '21', 'Portal', 'AdminTerm', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('23', '18', 'Portal', 'AdminTerm', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('24', '23', 'Portal', 'AdminTerm', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('25', '1', 'Portal', 'AdminPage', 'index', '', '1', '1', '页面管理', '', '', '3');
INSERT INTO `cmf_menu` VALUES ('26', '25', 'Portal', 'AdminPage', 'listorders', '', '1', '0', '页面排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('27', '25', 'Portal', 'AdminPage', 'delete', '', '1', '0', '删除页面', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('28', '25', 'Portal', 'AdminPage', 'edit', '', '1', '0', '编辑页面', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('29', '28', 'Portal', 'AdminPage', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('30', '25', 'Portal', 'AdminPage', 'add', '', '1', '0', '添加页面', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('31', '30', 'Portal', 'AdminPage', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('32', '1', 'Admin', 'Recycle', 'default', '', '1', '1', '回收站', '', '', '4');
INSERT INTO `cmf_menu` VALUES ('33', '32', 'Portal', 'AdminPost', 'recyclebin', '', '1', '1', '文章回收', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('34', '33', 'Portal', 'AdminPost', 'restore', '', '1', '0', '文章还原', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('35', '33', 'Portal', 'AdminPost', 'clean', '', '1', '0', '彻底删除', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('36', '32', 'Portal', 'AdminPage', 'recyclebin', '', '1', '1', '页面回收', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('37', '36', 'Portal', 'AdminPage', 'clean', '', '1', '0', '彻底删除', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('38', '36', 'Portal', 'AdminPage', 'restore', '', '1', '0', '页面还原', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('39', '0', 'Admin', 'Extension', 'default', '', '0', '1', '扩展工具', 'cloud', '', '40');
INSERT INTO `cmf_menu` VALUES ('40', '39', 'Admin', 'Backup', 'default', '', '1', '1', '备份管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('41', '40', 'Admin', 'Backup', 'restore', '', '1', '1', '数据还原', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('42', '40', 'Admin', 'Backup', 'index', '', '1', '1', '数据备份', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('43', '42', 'Admin', 'Backup', 'index_post', '', '1', '0', '提交数据备份', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('44', '40', 'Admin', 'Backup', 'download', '', '1', '0', '下载备份', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('45', '40', 'Admin', 'Backup', 'del_backup', '', '1', '0', '删除备份', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('46', '40', 'Admin', 'Backup', 'import', '', '1', '0', '数据备份导入', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('47', '39', 'Admin', 'Plugin', 'index', '', '1', '1', '插件管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('48', '47', 'Admin', 'Plugin', 'toggle', '', '1', '0', '插件启用切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('49', '47', 'Admin', 'Plugin', 'setting', '', '1', '0', '插件设置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('50', '49', 'Admin', 'Plugin', 'setting_post', '', '1', '0', '插件设置提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('51', '47', 'Admin', 'Plugin', 'install', '', '1', '0', '插件安装', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('52', '47', 'Admin', 'Plugin', 'uninstall', '', '1', '0', '插件卸载', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('53', '39', 'Admin', 'Slide', 'default', '', '1', '1', '幻灯片', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('54', '53', 'Admin', 'Slide', 'index', '', '1', '1', '幻灯片管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('55', '54', 'Admin', 'Slide', 'listorders', '', '1', '0', '幻灯片排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('56', '54', 'Admin', 'Slide', 'toggle', '', '1', '0', '幻灯片显示切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('57', '54', 'Admin', 'Slide', 'delete', '', '1', '0', '删除幻灯片', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('58', '54', 'Admin', 'Slide', 'edit', '', '1', '0', '编辑幻灯片', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('59', '58', 'Admin', 'Slide', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('60', '54', 'Admin', 'Slide', 'add', '', '1', '0', '添加幻灯片', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('61', '60', 'Admin', 'Slide', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('62', '53', 'Admin', 'Slidecat', 'index', '', '1', '1', '幻灯片分类', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('63', '62', 'Admin', 'Slidecat', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('64', '62', 'Admin', 'Slidecat', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('65', '64', 'Admin', 'Slidecat', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('66', '62', 'Admin', 'Slidecat', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('67', '66', 'Admin', 'Slidecat', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('68', '39', 'Admin', 'Ad', 'index', '', '1', '1', '网站广告', '', '', '2');
INSERT INTO `cmf_menu` VALUES ('69', '68', 'Admin', 'Ad', 'toggle', '', '1', '0', '广告显示切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('70', '68', 'Admin', 'Ad', 'delete', '', '1', '0', '删除广告', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('71', '68', 'Admin', 'Ad', 'edit', '', '1', '0', '编辑广告', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('72', '71', 'Admin', 'Ad', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('73', '68', 'Admin', 'Ad', 'add', '', '1', '0', '添加广告', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('74', '73', 'Admin', 'Ad', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('75', '39', 'Admin', 'Link', 'index', '', '0', '1', '友情链接', '', '', '3');
INSERT INTO `cmf_menu` VALUES ('76', '75', 'Admin', 'Link', 'listorders', '', '1', '0', '友情链接排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('77', '75', 'Admin', 'Link', 'toggle', '', '1', '0', '友链显示切换', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('78', '75', 'Admin', 'Link', 'delete', '', '1', '0', '删除友情链接', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('79', '75', 'Admin', 'Link', 'edit', '', '1', '0', '编辑友情链接', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('80', '79', 'Admin', 'Link', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('81', '75', 'Admin', 'Link', 'add', '', '1', '0', '添加友情链接', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('82', '81', 'Admin', 'Link', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('83', '39', 'Api', 'Oauthadmin', 'setting', '', '1', '1', '第三方登陆', 'leaf', '', '4');
INSERT INTO `cmf_menu` VALUES ('84', '83', 'Api', 'Oauthadmin', 'setting_post', '', '1', '0', '提交设置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('85', '0', 'Admin', 'Menu', 'default', '', '1', '1', '菜单管理', 'list', '', '20');
INSERT INTO `cmf_menu` VALUES ('86', '85', 'Admin', 'Navcat', 'default1', '', '1', '1', '前台菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('87', '86', 'Admin', 'Nav', 'index', '', '1', '1', '菜单管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('88', '87', 'Admin', 'Nav', 'listorders', '', '1', '0', '前台导航排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('89', '87', 'Admin', 'Nav', 'delete', '', '1', '0', '删除菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('90', '87', 'Admin', 'Nav', 'edit', '', '1', '0', '编辑菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('91', '90', 'Admin', 'Nav', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('92', '87', 'Admin', 'Nav', 'add', '', '1', '0', '添加菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('93', '92', 'Admin', 'Nav', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('94', '86', 'Admin', 'Navcat', 'index', '', '1', '1', '菜单分类', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('95', '94', 'Admin', 'Navcat', 'delete', '', '1', '0', '删除分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('96', '94', 'Admin', 'Navcat', 'edit', '', '1', '0', '编辑分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('97', '96', 'Admin', 'Navcat', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('98', '94', 'Admin', 'Navcat', 'add', '', '1', '0', '添加分类', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('99', '98', 'Admin', 'Navcat', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('100', '85', 'Admin', 'Menu', 'index', '', '1', '1', '后台菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('101', '100', 'Admin', 'Menu', 'add', '', '1', '0', '添加菜单', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('102', '101', 'Admin', 'Menu', 'add_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('103', '100', 'Admin', 'Menu', 'listorders', '', '1', '0', '后台菜单排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('104', '100', 'Admin', 'Menu', 'export_menu', '', '1', '0', '菜单备份', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('105', '100', 'Admin', 'Menu', 'edit', '', '1', '0', '编辑菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('106', '105', 'Admin', 'Menu', 'edit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('107', '100', 'Admin', 'Menu', 'delete', '', '1', '0', '删除菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('108', '100', 'Admin', 'Menu', 'lists', '', '1', '0', '所有菜单', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('109', '0', 'Admin', 'Setting', 'default', '', '0', '1', '设置', 'cogs', '', '0');
INSERT INTO `cmf_menu` VALUES ('110', '109', 'Admin', 'Setting', 'userdefault', '', '0', '1', '个人信息', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('111', '110', 'Admin', 'User', 'userinfo', '', '1', '1', '修改信息', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('112', '111', 'Admin', 'User', 'userinfo_post', '', '1', '0', '修改信息提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('113', '110', 'Admin', 'Setting', 'password', '', '1', '1', '修改密码', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('114', '113', 'Admin', 'Setting', 'password_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('115', '109', 'Admin', 'Setting', 'site', '', '1', '1', '网站信息', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('116', '115', 'Admin', 'Setting', 'site_post', '', '1', '0', '提交修改', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('117', '115', 'Admin', 'Route', 'index', '', '1', '0', '路由列表', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('118', '115', 'Admin', 'Route', 'add', '', '1', '0', '路由添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('119', '118', 'Admin', 'Route', 'add_post', '', '1', '0', '路由添加提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('120', '115', 'Admin', 'Route', 'edit', '', '1', '0', '路由编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('121', '120', 'Admin', 'Route', 'edit_post', '', '1', '0', '路由编辑提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('122', '115', 'Admin', 'Route', 'delete', '', '1', '0', '路由删除', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('123', '115', 'Admin', 'Route', 'ban', '', '1', '0', '路由禁止', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('124', '115', 'Admin', 'Route', 'open', '', '1', '0', '路由启用', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('125', '115', 'Admin', 'Route', 'listorders', '', '1', '0', '路由排序', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('126', '109', 'Admin', 'Mailer', 'default', '', '1', '1', '邮箱配置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('127', '126', 'Admin', 'Mailer', 'index', '', '1', '1', 'SMTP配置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('128', '127', 'Admin', 'Mailer', 'index_post', '', '1', '0', '提交配置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('129', '126', 'Admin', 'Mailer', 'active', '', '1', '1', '邮件模板', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('130', '129', 'Admin', 'Mailer', 'active_post', '', '1', '0', '提交模板', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('131', '109', 'Admin', 'Setting', 'clearcache', '', '1', '1', '清除缓存', '', '', '1');
INSERT INTO `cmf_menu` VALUES ('132', '0', 'User', 'Indexadmin', 'default', '', '1', '1', '用户管理', 'group', '', '10');
INSERT INTO `cmf_menu` VALUES ('133', '132', 'User', 'Indexadmin', 'default1', '', '1', '1', '用户组', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('134', '133', 'User', 'Indexadmin', 'index', '', '1', '1', '本站用户', 'leaf', '', '0');
INSERT INTO `cmf_menu` VALUES ('135', '134', 'User', 'Indexadmin', 'ban', '', '1', '0', '拉黑会员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('136', '134', 'User', 'Indexadmin', 'cancelban', '', '1', '0', '启用会员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('137', '133', 'User', 'Oauthadmin', 'index', '', '1', '1', '第三方用户', 'leaf', '', '0');
INSERT INTO `cmf_menu` VALUES ('138', '137', 'User', 'Oauthadmin', 'delete', '', '1', '0', '第三方用户解绑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('139', '132', 'User', 'Indexadmin', 'default3', '', '1', '1', '管理组', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('140', '139', 'Admin', 'Rbac', 'index', '', '1', '1', '角色管理', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('141', '140', 'Admin', 'Rbac', 'member', '', '1', '0', '成员管理', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('142', '140', 'Admin', 'Rbac', 'authorize', '', '1', '0', '权限设置', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('143', '142', 'Admin', 'Rbac', 'authorize_post', '', '1', '0', '提交设置', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('144', '140', 'Admin', 'Rbac', 'roleedit', '', '1', '0', '编辑角色', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('145', '144', 'Admin', 'Rbac', 'roleedit_post', '', '1', '0', '提交编辑', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('146', '140', 'Admin', 'Rbac', 'roledelete', '', '1', '1', '删除角色', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('147', '140', 'Admin', 'Rbac', 'roleadd', '', '1', '1', '添加角色', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('148', '147', 'Admin', 'Rbac', 'roleadd_post', '', '1', '0', '提交添加', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('149', '139', 'Admin', 'User', 'index', '', '1', '1', '管理员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('150', '149', 'Admin', 'User', 'delete', '', '1', '0', '删除管理员', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('151', '149', 'Admin', 'User', 'edit', '', '1', '0', '管理员编辑', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('152', '151', 'Admin', 'User', 'edit_post', '', '1', '0', '编辑提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('153', '149', 'Admin', 'User', 'add', '', '1', '0', '管理员添加', '', '', '1000');
INSERT INTO `cmf_menu` VALUES ('154', '153', 'Admin', 'User', 'add_post', '', '1', '0', '添加提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('155', '47', 'Admin', 'Plugin', 'update', '', '1', '0', '插件更新', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('156', '39', 'Admin', 'Storage', 'index', '', '1', '1', '文件存储', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('157', '156', 'Admin', 'Storage', 'setting_post', '', '1', '0', '文件存储设置提交', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('158', '54', 'Admin', 'Slide', 'ban', '', '1', '0', '禁用幻灯片', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('159', '54', 'Admin', 'Slide', 'cancelban', '', '1', '0', '启用幻灯片', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('160', '149', 'Admin', 'User', 'ban', '', '1', '0', '禁用管理员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('161', '149', 'Admin', 'User', 'cancelban', '', '1', '0', '启用管理员', '', '', '0');
INSERT INTO `cmf_menu` VALUES ('162', '0', 'Admin', 'Form', 'default', '', '1', '1', '表单报名模板管理', 'plus-square', '定义活动模板', '11');
INSERT INTO `cmf_menu` VALUES ('163', '162', 'Admin', 'Form', 'add', '', '1', '1', '添加活动模板', '', '发布活动模板', '0');
INSERT INTO `cmf_menu` VALUES ('164', '162', 'Admin', 'Form', 'index', '', '1', '1', '活动模板管理', '', '活动模板管理', '0');
INSERT INTO `cmf_menu` VALUES ('165', '162', 'Admin', 'Form', 'all_list', '', '1', '1', '活动管理', '', '所有的活动管理', '0');
INSERT INTO `cmf_menu` VALUES ('166', '132', 'Admin', 'User', 'regcode', 'index', '1', '1', '注册码', '', '激活码', '0');
INSERT INTO `cmf_menu` VALUES ('167', '0', 'Admin', 'Activity', 'index', '', '1', '1', '营销活动管理', 'info', '', '12');

-- ----------------------------
-- Table structure for cmf_nav
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nav`;
CREATE TABLE `cmf_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '导航分类 id',
  `parentid` int(11) NOT NULL COMMENT '导航父 id',
  `label` varchar(255) NOT NULL COMMENT '导航标题',
  `target` varchar(50) DEFAULT NULL COMMENT '打开方式',
  `href` varchar(255) NOT NULL COMMENT '导航链接',
  `icon` varchar(255) NOT NULL COMMENT '导航图标',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `listorder` int(6) DEFAULT '0' COMMENT '排序',
  `path` varchar(255) NOT NULL DEFAULT '0' COMMENT '层级关系',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='前台导航表';

-- ----------------------------
-- Records of cmf_nav
-- ----------------------------
INSERT INTO `cmf_nav` VALUES ('1', '1', '0', '首页', '', 'home', '', '1', '0', '0-1');
INSERT INTO `cmf_nav` VALUES ('2', '1', '0', '列表演示', '', 'a:2:{s:6:\"action\";s:17:\"Portal/List/index\";s:5:\"param\";a:1:{s:2:\"id\";s:1:\"1\";}}', '', '1', '0', '0-2');
INSERT INTO `cmf_nav` VALUES ('3', '1', '0', '瀑布流', '', 'a:2:{s:6:\"action\";s:17:\"Portal/List/index\";s:5:\"param\";a:1:{s:2:\"id\";s:1:\"2\";}}', '', '1', '0', '0-3');
INSERT INTO `cmf_nav` VALUES ('4', '1', '0', '活动报名', '', 'http://huodong.tongyishidai.com/index.php/activity', '', '1', '0', '0-4');

-- ----------------------------
-- Table structure for cmf_nav_cat
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nav_cat`;
CREATE TABLE `cmf_nav_cat` (
  `navcid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '导航分类名',
  `active` int(1) NOT NULL DEFAULT '1' COMMENT '是否为主菜单，1是，0不是',
  `remark` text COMMENT '备注',
  PRIMARY KEY (`navcid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台导航分类表';

-- ----------------------------
-- Records of cmf_nav_cat
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_nyyvote_msg
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nyyvote_msg`;
CREATE TABLE `cmf_nyyvote_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `createtime` int(11) DEFAULT '0' COMMENT '留言时间',
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新投票留言管理';

-- ----------------------------
-- Records of cmf_nyyvote_msg
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_nyyvote_user
-- ----------------------------
DROP TABLE IF EXISTS `cmf_nyyvote_user`;
CREATE TABLE `cmf_nyyvote_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `mobile` varchar(255) DEFAULT NULL COMMENT '手机号',
  `school` varchar(255) DEFAULT NULL COMMENT '校区',
  `class` varchar(255) DEFAULT NULL COMMENT '班级',
  `parent_message` longblob COMMENT '家长寄语',
  `teacher_message` text COMMENT '老师寄语',
  `avatar` varchar(255) DEFAULT '' COMMENT '头像',
  `work_photo1` varchar(255) DEFAULT NULL COMMENT '作品照片1',
  `work_photo2` varchar(255) DEFAULT NULL COMMENT '作品照片2',
  `work_photo3` varchar(255) DEFAULT NULL COMMENT '作品照片3',
  `self_photo1` varchar(255) DEFAULT NULL COMMENT '个人照片1',
  `self_photo2` varchar(255) DEFAULT NULL COMMENT '个人照片2',
  `self_photo3` varchar(255) DEFAULT NULL COMMENT '个人照片3',
  `self_photo4` varchar(255) DEFAULT NULL COMMENT '个人照片4',
  `self_photo5` varchar(255) DEFAULT NULL COMMENT '个人照片5',
  `createtime` int(11) DEFAULT '0' COMMENT '创建时间',
  `from_user` varchar(255) DEFAULT NULL COMMENT '用户openid',
  `nickname` varchar(32) DEFAULT NULL COMMENT '昵称',
  `aid` int(11) DEFAULT NULL COMMENT '活动id',
  `total_num` int(11) DEFAULT '0' COMMENT '获得的总票数',
  `day_num` int(11) DEFAULT '0' COMMENT '获得的日投票次数',
  `teacher` varchar(32) DEFAULT NULL COMMENT '指导老师',
  `head_img` varchar(255) DEFAULT NULL COMMENT '背景图片',
  `self_photo6` varchar(255) DEFAULT NULL COMMENT '个人照片6',
  `work_photo4` varchar(255) DEFAULT NULL COMMENT '作品4',
  `work_photo5` varchar(255) DEFAULT NULL COMMENT '作品5',
  `work_photo6` varchar(255) DEFAULT NULL COMMENT '作品6',
  `work_photo7` varchar(255) DEFAULT NULL COMMENT '作品7',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新投票用户表';

-- ----------------------------
-- Records of cmf_nyyvote_user
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_oauth_user
-- ----------------------------
DROP TABLE IF EXISTS `cmf_oauth_user`;
CREATE TABLE `cmf_oauth_user` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `from` varchar(20) NOT NULL COMMENT '用户来源key',
  `name` varchar(30) NOT NULL COMMENT '第三方昵称',
  `head_img` varchar(200) NOT NULL COMMENT '头像',
  `uid` int(20) NOT NULL COMMENT '关联的本站用户id',
  `create_time` datetime NOT NULL COMMENT '绑定时间',
  `last_login_time` datetime NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(16) NOT NULL COMMENT '最后登录ip',
  `login_times` int(6) NOT NULL COMMENT '登录次数',
  `status` tinyint(2) NOT NULL,
  `access_token` varchar(512) NOT NULL,
  `expires_date` int(11) NOT NULL COMMENT 'access_token过期时间',
  `openid` varchar(40) NOT NULL COMMENT '第三方用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第三方用户表';

-- ----------------------------
-- Records of cmf_oauth_user
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_options
-- ----------------------------
DROP TABLE IF EXISTS `cmf_options`;
CREATE TABLE `cmf_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL COMMENT '配置名',
  `option_value` longtext NOT NULL COMMENT '配置值',
  `autoload` int(2) NOT NULL DEFAULT '1' COMMENT '是否自动加载',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='全站配置表';

-- ----------------------------
-- Records of cmf_options
-- ----------------------------
INSERT INTO `cmf_options` VALUES ('1', 'member_email_active', '{\"title\":\"ThinkCMF\\u90ae\\u4ef6\\u6fc0\\u6d3b\\u901a\\u77e5.\",\"template\":\"<p>\\u672c\\u90ae\\u4ef6\\u6765\\u81ea<a href=\\\"http:\\/\\/www.thinkcmf.com\\\">ThinkCMF<\\/a><br\\/><br\\/>&nbsp; &nbsp;<strong>---------------<\\/strong><br\\/>&nbsp; &nbsp;<strong>\\u5e10\\u53f7\\u6fc0\\u6d3b\\u8bf4\\u660e<\\/strong><br\\/>&nbsp; &nbsp;<strong>---------------<\\/strong><br\\/><br\\/>&nbsp; &nbsp; \\u5c0a\\u656c\\u7684<span style=\\\"FONT-SIZE: 16px; FONT-FAMILY: Arial; COLOR: rgb(51,51,51); LINE-HEIGHT: 18px; BACKGROUND-COLOR: rgb(255,255,255)\\\">#username#\\uff0c\\u60a8\\u597d\\u3002<\\/span>\\u5982\\u679c\\u60a8\\u662fThinkCMF\\u7684\\u65b0\\u7528\\u6237\\uff0c\\u6216\\u5728\\u4fee\\u6539\\u60a8\\u7684\\u6ce8\\u518cEmail\\u65f6\\u4f7f\\u7528\\u4e86\\u672c\\u5730\\u5740\\uff0c\\u6211\\u4eec\\u9700\\u8981\\u5bf9\\u60a8\\u7684\\u5730\\u5740\\u6709\\u6548\\u6027\\u8fdb\\u884c\\u9a8c\\u8bc1\\u4ee5\\u907f\\u514d\\u5783\\u573e\\u90ae\\u4ef6\\u6216\\u5730\\u5740\\u88ab\\u6ee5\\u7528\\u3002<br\\/>&nbsp; &nbsp; \\u60a8\\u53ea\\u9700\\u70b9\\u51fb\\u4e0b\\u9762\\u7684\\u94fe\\u63a5\\u5373\\u53ef\\u6fc0\\u6d3b\\u60a8\\u7684\\u5e10\\u53f7\\uff1a<br\\/>&nbsp; &nbsp; <a title=\\\"\\\" href=\\\"http:\\/\\/#link#\\\" target=\\\"_self\\\">http:\\/\\/#link#<\\/a><br\\/>&nbsp; &nbsp; (\\u5982\\u679c\\u4e0a\\u9762\\u4e0d\\u662f\\u94fe\\u63a5\\u5f62\\u5f0f\\uff0c\\u8bf7\\u5c06\\u8be5\\u5730\\u5740\\u624b\\u5de5\\u7c98\\u8d34\\u5230\\u6d4f\\u89c8\\u5668\\u5730\\u5740\\u680f\\u518d\\u8bbf\\u95ee)<br\\/>&nbsp; &nbsp; \\u611f\\u8c22\\u60a8\\u7684\\u8bbf\\u95ee\\uff0c\\u795d\\u60a8\\u4f7f\\u7528\\u6109\\u5feb\\uff01<br\\/><br\\/>&nbsp; &nbsp; \\u6b64\\u81f4<br\\/>&nbsp; &nbsp; ThinkCMF \\u7ba1\\u7406\\u56e2\\u961f.<\\/p>\"}', '1');
INSERT INTO `cmf_options` VALUES ('2', 'site_options', '{\"site_name\":\"\\u540c\\u7ffc\\u65f6\\u4ee3\\u5fae\\u6d3b\\u52a8\\u53d1\\u5e03\\u5e73\\u53f0\",\"site_host\":\"http:\\/\\/huodong.tongyishidai.com\\/\",\"site_admin_url_password\":\"\",\"site_tpl\":\"simplebootx\",\"site_adminstyle\":\"bluesky\",\"site_icp\":\"\",\"site_admin_email\":\"810915275@qq.com\",\"site_tongji\":\"\",\"site_copyright\":\"\\u540c\\u7ffc\\u65f6\\u4ee3\",\"site_seo_title\":\"\\u540c\\u7ffc\\u65f6\\u4ee3\\u5fae\\u6d3b\\u52a8\\u53d1\\u5e03\\u5e73\\u53f0\",\"site_seo_keywords\":\"huodong.tongyishidai.com \\u540c\\u7ffc\\u65f6\\u4ee3\",\"site_seo_description\":\"\\u540c\\u7ffc\\u65f6\\u4ee3\\u5fae\\u6d3b\\u52a8\\u53d1\\u5e03\\u5e73\\u53f0\",\"urlmode\":\"1\",\"html_suffix\":\".shtml\",\"comment_time_interval\":60}', '1');
INSERT INTO `cmf_options` VALUES ('3', 'cmf_settings', '{\"banned_usernames\":\"\",\"storage\":{\"type\":\"Qiniu\",\"Qiniu\":{\"accessKey\":\"ULwZ56pyKQdbcjlulk2z_LfFFKAmKcWxojDLc9f4\",\"secretKey\":\"dkw_CBX1R8gDqMwzYsMGUCIOEwKeOJ6f8ukRA5vt\",\"domain\":\"resacc.tongyishidai.com\",\"bucket\":\"tysd-activity\"},\"Aliyun\":{\"accessKey\":\"SQNMkFXt6c7DYpMR\",\"secretKey\":\"FdXXANjQHntKzuRiwdA0EnDv2MkPVT\",\"domain\":\"ossactivity.tongyishidai.com\",\"bucket\":\"oss-activity\",\"Endpoint\":\"60\"}}}', '1');

-- ----------------------------
-- Table structure for cmf_plugins
-- ----------------------------
DROP TABLE IF EXISTS `cmf_plugins`;
CREATE TABLE `cmf_plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '插件名，英文',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '插件名称',
  `description` text COMMENT '插件描述',
  `type` tinyint(2) DEFAULT '0' COMMENT '插件类型, 1:网站；8;微信',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态；1开启；',
  `config` text COMMENT '插件配置',
  `hooks` varchar(255) DEFAULT NULL COMMENT '实现的钩子;以“，”分隔',
  `has_admin` tinyint(2) DEFAULT '0' COMMENT '插件是否有后台管理界面',
  `author` varchar(50) DEFAULT '' COMMENT '插件作者',
  `version` varchar(20) DEFAULT '' COMMENT '插件版本号',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '插件安装时间',
  `listorder` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of cmf_plugins
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_posts
-- ----------------------------
DROP TABLE IF EXISTS `cmf_posts`;
CREATE TABLE `cmf_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned DEFAULT '0' COMMENT '发表者id',
  `post_keywords` varchar(150) NOT NULL COMMENT 'seo keywords',
  `post_source` varchar(150) DEFAULT NULL COMMENT '转载文章的来源',
  `post_date` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post创建日期，永久不变，一般不显示给用户',
  `post_content` longtext COMMENT 'post内容',
  `post_title` text COMMENT 'post标题',
  `post_excerpt` text COMMENT 'post摘要',
  `post_status` int(2) DEFAULT '1' COMMENT 'post状态，1已审核，0未审核',
  `comment_status` int(2) DEFAULT '1' COMMENT '评论状态，1允许，0不允许',
  `post_modified` datetime DEFAULT '2000-01-01 00:00:00' COMMENT 'post更新时间，可在前台修改，显示给用户',
  `post_content_filtered` longtext,
  `post_parent` bigint(20) unsigned DEFAULT '0' COMMENT 'post的父级post id,表示post层级关系',
  `post_type` int(2) DEFAULT NULL,
  `post_mime_type` varchar(100) DEFAULT '',
  `comment_count` bigint(20) DEFAULT '0',
  `smeta` text COMMENT 'post的扩展字段，保存相关扩展属性，如缩略图；格式为json',
  `post_hits` int(11) DEFAULT '0' COMMENT 'post点击数，查看数',
  `post_like` int(11) DEFAULT '0' COMMENT 'post赞数',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶 1置顶； 0不置顶',
  `recommended` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐 1推荐 0不推荐',
  `audio_link1` varchar(255) DEFAULT NULL COMMENT '音频地址1',
  `audio_link2` varchar(255) DEFAULT NULL COMMENT '音频地址2',
  `audio_link3` varchar(255) DEFAULT NULL COMMENT '音频地址3',
  PRIMARY KEY (`id`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`id`) USING BTREE,
  KEY `post_parent` (`post_parent`) USING BTREE,
  KEY `post_author` (`post_author`) USING BTREE,
  KEY `post_date` (`post_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Portal文章表';

-- ----------------------------
-- Records of cmf_posts
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_questionnaire_problem
-- ----------------------------
DROP TABLE IF EXISTS `cmf_questionnaire_problem`;
CREATE TABLE `cmf_questionnaire_problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `aid` int(11) NOT NULL COMMENT '问卷调查id',
  `title` varchar(500) NOT NULL COMMENT '题目',
  `item1` varchar(500) NOT NULL COMMENT '问题选项1',
  `item2` varchar(500) NOT NULL COMMENT '问题选项2',
  `item3` varchar(500) NOT NULL COMMENT '问题选项3',
  `item4` varchar(500) DEFAULT NULL COMMENT '问题选项4',
  `right` varchar(1000) NOT NULL DEFAULT '0' COMMENT '正确选项',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `sort` tinyint(3) DEFAULT '0' COMMENT '序号',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='问卷调查问题表';

-- ----------------------------
-- Records of cmf_questionnaire_problem
-- ----------------------------
INSERT INTO `cmf_questionnaire_problem` VALUES ('1', '4', '凄凄切切', '去', '去', '去', '去', '1', '1', '0', '0');
INSERT INTO `cmf_questionnaire_problem` VALUES ('2', '4', '去', '去', '去', '去', '去', '1', '1', '0', '0');
INSERT INTO `cmf_questionnaire_problem` VALUES ('3', '6', '亲情', '去', '去', '去', null, '0', '0', '0', '0');

-- ----------------------------
-- Table structure for cmf_questionnaire_user_data
-- ----------------------------
DROP TABLE IF EXISTS `cmf_questionnaire_user_data`;
CREATE TABLE `cmf_questionnaire_user_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `aid` int(11) NOT NULL COMMENT '问卷调查id',
  `uid` int(11) NOT NULL COMMENT '参与人员id',
  `qid` int(11) NOT NULL DEFAULT '0' COMMENT '问题id、得分值',
  `iid` int(11) NOT NULL COMMENT '选项',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '答题时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户答题信息表';

-- ----------------------------
-- Records of cmf_questionnaire_user_data
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_recharge
-- ----------------------------
DROP TABLE IF EXISTS `cmf_recharge`;
CREATE TABLE `cmf_recharge` (
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '会员ID',
  `nickname` varchar(50) DEFAULT NULL COMMENT '会员昵称',
  `order_sn` varchar(30) NOT NULL COMMENT '充值单号',
  `account` float(10,2) DEFAULT '0.00' COMMENT '充值金额',
  `ctime` int(11) DEFAULT NULL COMMENT '充值时间',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `pay_code` varchar(20) DEFAULT NULL,
  `pay_name` varchar(80) DEFAULT NULL COMMENT '支付方式',
  `pay_status` tinyint(1) DEFAULT '0' COMMENT '充值状态0:待支付 1:充值成功 2:交易关闭',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_recharge
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_regcode
-- ----------------------------
DROP TABLE IF EXISTS `cmf_regcode`;
CREATE TABLE `cmf_regcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL COMMENT '注册码',
  `status` tinyint(3) DEFAULT '1' COMMENT '有效性',
  `type` tinyint(3) DEFAULT '1' COMMENT '1表示399 2 3999会员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='注册码';

-- ----------------------------
-- Records of cmf_regcode
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_role
-- ----------------------------
DROP TABLE IF EXISTS `cmf_role`;
CREATE TABLE `cmf_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '角色名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父角色ID',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` int(3) NOT NULL DEFAULT '0' COMMENT '排序字段',
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of cmf_role
-- ----------------------------
INSERT INTO `cmf_role` VALUES ('1', '超级管理员', '0', '1', '拥有网站最高管理员权限！', '1329633709', '1329633709', '0');
INSERT INTO `cmf_role` VALUES ('2', 'admin888', null, '1', '', '1472633166', '0', '0');

-- ----------------------------
-- Table structure for cmf_role_user
-- ----------------------------
DROP TABLE IF EXISTS `cmf_role_user`;
CREATE TABLE `cmf_role_user` (
  `role_id` int(11) unsigned DEFAULT '0' COMMENT '角色 id',
  `user_id` int(11) DEFAULT '0' COMMENT '用户id',
  KEY `group_id` (`role_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色对应表';

-- ----------------------------
-- Records of cmf_role_user
-- ----------------------------
INSERT INTO `cmf_role_user` VALUES ('1', '1');

-- ----------------------------
-- Table structure for cmf_route
-- ----------------------------
DROP TABLE IF EXISTS `cmf_route`;
CREATE TABLE `cmf_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '路由id',
  `full_url` varchar(255) DEFAULT NULL COMMENT '完整url， 如：portal/list/index?id=1',
  `url` varchar(255) DEFAULT NULL COMMENT '实际显示的url',
  `listorder` int(5) DEFAULT '0' COMMENT '排序，优先级，越小优先级越高',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态，1：启用 ;0：不启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='url路由表';

-- ----------------------------
-- Records of cmf_route
-- ----------------------------
INSERT INTO `cmf_route` VALUES ('1', 'portal/list/index', 'list/:id\\d', '0', '0');

-- ----------------------------
-- Table structure for cmf_session
-- ----------------------------
DROP TABLE IF EXISTS `cmf_session`;
CREATE TABLE `cmf_session` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  UNIQUE KEY `session_id` (`session_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_session
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_slide
-- ----------------------------
DROP TABLE IF EXISTS `cmf_slide`;
CREATE TABLE `cmf_slide` (
  `slide_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slide_cid` int(11) NOT NULL COMMENT '幻灯片分类 id',
  `slide_name` varchar(255) NOT NULL COMMENT '幻灯片名称',
  `slide_pic` varchar(255) DEFAULT NULL COMMENT '幻灯片图片',
  `slide_url` varchar(255) DEFAULT NULL COMMENT '幻灯片链接',
  `slide_des` varchar(255) DEFAULT NULL COMMENT '幻灯片描述',
  `slide_content` text COMMENT '幻灯片内容',
  `slide_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  `listorder` int(10) DEFAULT '0' COMMENT '排序',
  `slide_index` varchar(255) DEFAULT NULL COMMENT '封面长图',
  `module` varchar(255) DEFAULT NULL,
  `template_id` int(11) NOT NULL DEFAULT '0' COMMENT '模板id值',
  `slide_description` text COMMENT '活动简介',
  `example_ids` varchar(32) DEFAULT NULL COMMENT '案例id集合',
  `smeta` text COMMENT '案例图片集合',
  PRIMARY KEY (`slide_id`),
  KEY `slide_cid` (`slide_cid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

-- ----------------------------
-- Records of cmf_slide
-- ----------------------------
INSERT INTO `cmf_slide` VALUES ('3', '1', '微助力', '20160719/578d8946d49ff.jpg', 'http://tongyishidai.com/app/index.php?i=330&c=entry&id=243&do=newshare&m=weishare', '最基础的微信传播工具，邀请朋友助力，简单有效地带动老生续费或新生报名。', '', '1', '4', '20160719/578dbf39b5af8.jpg', 'weishare', '213', '', '213,906', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d67371deaf1.jpg\",\"alt\":\"20160912\\/57d67371deaf1\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d67378517c0.jpg\",\"alt\":\"20160912\\/57d67378517c0\"}]}');
INSERT INTO `cmf_slide` VALUES ('6', '1', '投票', '20160719/578d89e90305d.jpg', 'http://tongyishidai.com/app/index.php?i=330&c=entry&id=182&do=share&m=yyvote', '结合线下活动发起投票，增强传播力与影响力。', '湘乡市首届“最美妈妈”评选活动', '1', '7', '20160719/578d9b8d744bb.jpg', 'yyvote', '449', '', '449,900', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d6624a0ce66.jpg\",\"alt\":\"20160912\\/57d6624a0ce66\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d66250da522.jpg\",\"alt\":\"20160912\\/57d66250da522\"}]}');
INSERT INTO `cmf_slide` VALUES ('8', '1', '刮刮乐', '20160719/578d8a31bbf5e.jpg', 'http://tongyishidai.com/app/index.php?i=330&c=entry&id=26&do=scratch&m=scratch', '将现实抽奖微信场景化，在简单抽奖的基础上增强形式感。', '你刮奖，我送礼！新学期文具免费领！', '1', '8', '20160719/578dc0659c08a.jpg', 'scratch', '291', '', '917,291', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d6831e06ae0.jpg\",\"alt\":\"20160912\\/57d6831e06ae0\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d6832713550.jpg\",\"alt\":\"20160912\\/57d6832713550\"}]}');
INSERT INTO `cmf_slide` VALUES ('11', '1', '成绩查询', '20160719/578d8acea6638.jpg', 'http://train.jialepai100.com/app/index.php?i=2&c=entry&id=1&do=score&m=score', '一款方便教培机构查询成绩的简易工具。', '2015年第一学期期中考试', '1', '27', '20160719/578dc0e527a20.jpg', 'score', '679', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('17', '1', '活动邀请函', '20160725/57957f9bc692a.png', 'http://huodong.tongyishidai.com/index.php/apps/weimeet/index/id/738.shtml', '增加老生黏度的同时，激励老生转发邀请新客户，促进转化。', '', '1', '6', '20160725/5795816f6a379.jpg', 'weimeet', '738', '', '738,912', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d67baa8c09b.jpg\",\"alt\":\"20160912\\/57d67baa8c09b\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d67bb340a56.jpg\",\"alt\":\"20160912\\/57d67bb340a56\"}]}');
INSERT INTO `cmf_slide` VALUES ('19', '1', '我要当学霸', '20160719/578d8c25de6e5.jpg', 'http://huodong.tongyishidai.com/index.php/apps/Challenge/index/id/289.shtml', '发起竞赛答题活动的同时，结合线上抽奖，活动影响力大形式感强。', '同翼时代 学霸PK赛', '1', '15', '20160719/578dc1e9d007c.jpg', 'challenge', '746', '', '746,909', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d68df3be430.jpg\",\"alt\":\"20160912\\/57d68df3be430\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d68dfb97e5a.jpg\",\"alt\":\"20160912\\/57d68dfb97e5a\"}]}');
INSERT INTO `cmf_slide` VALUES ('24', '1', '邀请有礼', '20160719/578d8cf940f92.jpg', 'http://huodong.tongyishidai.com/index.php/apps/invitegift/index/id/239.shtml', '以二维码的形式实现老带新招生，有详细邀请参加的课程或活动。', '英博教育秋季课程限时优惠正在进行,赶快来参加吧！', '1', '5', 'http://resacc.tongyishidai.com/20160819_57b6ee0f709ae.jpg', 'invitegift', '239', '', '908,239', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d67794e56c3.jpg\",\"alt\":\"20160912\\/57d67794e56c3\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d6779b94ab2.jpg\",\"alt\":\"20160912\\/57d6779b94ab2\"}]}');
INSERT INTO `cmf_slide` VALUES ('26', '1', '成语接力', '20160719/578d8d50474a2.jpg', 'http://huodong.tongyishidai.com/index.php/apps/weidiom/index/id/366.shtml', '用趣味形式进行微信传播，邀请朋友帮忙接成语。', '超级学霸，一起玩成语接龙赢奖品啦~~', '1', '11', '20160719/578db5610dbaa.jpg', 'weidiom', '366', '', '366,916', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d68d6878e4e.jpg\",\"alt\":\"20160912\\/57d68d6878e4e\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d68d725d3f9.jpg\",\"alt\":\"20160912\\/57d68d725d3f9\"}]}');
INSERT INTO `cmf_slide` VALUES ('27', '1', '奥运加油', '20160719/aoyunjiayou.jpg', 'http://huodong.tongyishidai.com/index.php/apps/Olympicrefuel/index/id/5.shtml', '以奥运为热点，所有人一起为奥运加油为中国队加油。', '为奥运加油，为中国加油。', '1', '13', '20160719/aoyunjiayoumuban.jpg', 'olympicrefuel', '771', '', '911,771', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d68d906856d.jpg\",\"alt\":\"20160912\\/57d68d906856d\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d68d9f319d5.jpg\",\"alt\":\"20160912\\/57d68d9f319d5\"}]}');
INSERT INTO `cmf_slide` VALUES ('29', '1', '有奖问答', '20160719/youjiangwenda.jpg', 'http://huodong.tongyishidai.com/index.php/apps/Questionnaire/index/id/196.shtml', '答题赢大奖，测智力还有豪礼。', '答题赢大奖，测智力还有豪礼。', '1', '6', '20160719/youjiangwendamuban.jpg', 'questionnaire', '902', '', '902,196', '{\"photo\":[{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d6666334239.jpg\",\"alt\":\"20160912\\/57d6666334239\"},{\"url\":\"http:\\/\\/resacc.tongyishidai.com\\/20160912_57d6666ac41d3.jpg\",\"alt\":\"20160912\\/57d6666ac41d3\"}]}');
INSERT INTO `cmf_slide` VALUES ('40', '5', '庆中秋 集月饼 赢大礼', 'http://resacc.tongyishidai.com/20160923_57e4a6f0c08bf.png', 'http://huodong.tongyishidai.com/index.php/apps/midautumn/index/id/1124.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e491d28a1ac.jpg', 'midautumn', '1124', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('41', '5', '艺佳邀您集月饼赢大礼！', 'http://resacc.tongyishidai.com/20160923_57e4a66e66a56.jpg', 'http://huodong.tongyishidai.com/index.php/apps/midautumn/index/id/1126.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e491ea843cb.jpg', 'midautumn', '1126', '', '1126', 'null');
INSERT INTO `cmf_slide` VALUES ('42', '5', '妙音缘艺术学校  任一学科体验课只需1元', 'http://resacc.tongyishidai.com/20160923_57e4a70c10b9a.jpg', 'http://huodong.tongyishidai.com/index.php/apps/groupbuy/index/id/903.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e4921103cee.jpg', 'groupbuy', '903', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('43', '5', '童慧教育邀你共度国庆', 'http://resacc.tongyishidai.com/20160923_57e4a71ab49ed.jpg', 'http://huodong.tongyishidai.com/index.php/apps/nationday/index/id/1146.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e493e0580d6.jpg', 'nationday', '1146', '', '1146', 'null');
INSERT INTO `cmf_slide` VALUES ('44', '5', '图语美术邀您赢大奖', 'http://resacc.tongyishidai.com/20160923_57e4a72b4ddb7.jpg', 'http://huodong.tongyishidai.com/index.php/apps/giftbox/index/id/921.shtml', '', '图语美术邀您赢大奖', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e494333d48b.jpg', 'giftbox', '921', '<p>图语美术邀您赢大奖</p>', '921', 'null');
INSERT INTO `cmf_slide` VALUES ('45', '6', '恩卓尔国庆马戏团门票免费送啦！', 'http://resacc.tongyishidai.com/20160923_57e4a77380b8f.jpg', 'http://huodong.tongyishidai.com/index.php/portal/mjoin/index/id/381.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e49d2c40541.jpg', null, '381', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('46', '6', '盛华教育国庆节“水果盛宴“——教你动手学英语', 'http://resacc.tongyishidai.com/20160923_57e4a7844ecc2.jpg', 'http://huodong.tongyishidai.com/index.php/portal/mjoin/index/id/382.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e49bf023445.jpg', null, '382', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('47', '6', '奇恩艺术创意中秋～月饼DIY亲子活动文化沙龙邀你免费参加', 'http://resacc.tongyishidai.com/20160923_57e4a798e76f3.png', 'http://huodong.tongyishidai.com/index.php/portal/mjoin/index/id/288.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e49a8ca725f.jpg', null, '288', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('48', '6', '1元体验4次课 ，全城限额300名！ 【数学思维，最强大脑】', 'http://resacc.tongyishidai.com/20160923_57e4a7a822fda.png', 'http://huodong.tongyishidai.com/index.php/portal/mjoin/index/id/383.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e4994ec5596.jpg', null, '383', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('49', '6', '少儿古筝【免费】体验—开发孩子的音乐才能', 'http://resacc.tongyishidai.com/20160923_57e4a7b75475e.png', 'http://huodong.tongyishidai.com/index.php/portal/mjoin/index/id/384.shtml', '', '', '1', '0', 'http://resacc.tongyishidai.com/20160923_57e498105dcf9.jpg', null, '384', '', '', 'null');
INSERT INTO `cmf_slide` VALUES ('95', '0', '测试', '', 'http://huodong.tongyishidai.com/index.php/apps/adventure/index/id/3307.shtml', '', '测试', '1', '0', '', '', '3307', '<p>测试</p>', '3307', 'null');

-- ----------------------------
-- Table structure for cmf_slide_cat
-- ----------------------------
DROP TABLE IF EXISTS `cmf_slide_cat`;
CREATE TABLE `cmf_slide_cat` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL COMMENT '幻灯片分类',
  `cat_idname` varchar(255) NOT NULL COMMENT '幻灯片分类标识',
  `cat_remark` text COMMENT '分类备注',
  `cat_status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1显示，0不显示',
  PRIMARY KEY (`cid`),
  KEY `cat_idname` (`cat_idname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='幻灯片分类表';

-- ----------------------------
-- Records of cmf_slide_cat
-- ----------------------------
INSERT INTO `cmf_slide_cat` VALUES ('1', '应用中心', 'app_center', '', '1');
INSERT INTO `cmf_slide_cat` VALUES ('2', '精选活动', 'select_activity', '精选活动', '1');
INSERT INTO `cmf_slide_cat` VALUES ('5', '营销活动-当季TOP5活动', 'top5_activity', '自行发布当前最热的5个营销工具案例', '1');
INSERT INTO `cmf_slide_cat` VALUES ('6', '报名表单-当季TOP5', 'top5_form', '自行发布前5个当季最热的报名表单', '1');

-- ----------------------------
-- Table structure for cmf_terms
-- ----------------------------
DROP TABLE IF EXISTS `cmf_terms`;
CREATE TABLE `cmf_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `name` varchar(200) DEFAULT NULL COMMENT '分类名称',
  `slug` varchar(200) DEFAULT '',
  `taxonomy` varchar(32) DEFAULT NULL COMMENT '分类类型',
  `description` longtext COMMENT '分类描述',
  `parent` bigint(20) unsigned DEFAULT '0' COMMENT '分类父id',
  `count` bigint(20) DEFAULT '0' COMMENT '分类文章数',
  `path` varchar(500) DEFAULT NULL COMMENT '分类层级关系路径',
  `seo_title` varchar(500) DEFAULT NULL,
  `seo_keywords` varchar(500) DEFAULT NULL,
  `seo_description` varchar(500) DEFAULT NULL,
  `list_tpl` varchar(50) DEFAULT NULL COMMENT '分类列表模板',
  `one_tpl` varchar(50) DEFAULT NULL COMMENT '分类文章页模板',
  `listorder` int(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1发布，0不发布',
  PRIMARY KEY (`term_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Portal 文章分类表';

-- ----------------------------
-- Records of cmf_terms
-- ----------------------------
INSERT INTO `cmf_terms` VALUES ('1', '列表演示', '', 'article', '', '0', '0', '0-1', '', '', '', 'list', 'article', '0', '1');
INSERT INTO `cmf_terms` VALUES ('2', '瀑布流', '', 'article', '', '0', '0', '0-2', '', '', '', 'list_masonry', 'article', '0', '1');
INSERT INTO `cmf_terms` VALUES ('3', '帮助中心', '', 'article', '', '0', '0', '0-3', '', '', '', 'list', 'article', '0', '1');
INSERT INTO `cmf_terms` VALUES ('4', '新微官网文章', '', 'article', '', '0', '0', '0-4', '', '', '', 'list', 'article', '0', '1');
INSERT INTO `cmf_terms` VALUES ('5', '往期回顾', '', 'article', '', '4', '0', '0-4-5', '', '', '', 'list', 'article', '0', '1');
INSERT INTO `cmf_terms` VALUES ('6', '会员专属', '', 'article', '', '4', '0', '0-4-6', '', '', '', 'list', 'article', '0', '1');
INSERT INTO `cmf_terms` VALUES ('7', '活动规则', '', 'article', '', '0', '0', '0-7', '', '', '', 'list', 'article', '0', '1');

-- ----------------------------
-- Table structure for cmf_term_relationships
-- ----------------------------
DROP TABLE IF EXISTS `cmf_term_relationships`;
CREATE TABLE `cmf_term_relationships` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'posts表里文章id',
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
  `listorder` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1发布，0不发布',
  PRIMARY KEY (`tid`),
  KEY `term_taxonomy_id` (`term_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='Portal 文章分类对应表';

-- ----------------------------
-- Records of cmf_term_relationships
-- ----------------------------
INSERT INTO `cmf_term_relationships` VALUES ('1', '1', '1', '0', '0');
INSERT INTO `cmf_term_relationships` VALUES ('2', '2', '1', '0', '0');
INSERT INTO `cmf_term_relationships` VALUES ('4', '4', '3', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('5', '5', '3', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('6', '6', '3', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('7', '3', '3', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('8', '7', '4', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('9', '8', '4', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('10', '9', '4', '0', '0');
INSERT INTO `cmf_term_relationships` VALUES ('11', '10', '4', '0', '0');
INSERT INTO `cmf_term_relationships` VALUES ('12', '11', '4', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('13', '12', '4', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('14', '13', '4', '0', '1');
INSERT INTO `cmf_term_relationships` VALUES ('16', '14', '5', '4', '1');
INSERT INTO `cmf_term_relationships` VALUES ('17', '15', '5', '5', '1');
INSERT INTO `cmf_term_relationships` VALUES ('18', '16', '5', '2', '1');
INSERT INTO `cmf_term_relationships` VALUES ('19', '17', '5', '1', '1');
INSERT INTO `cmf_term_relationships` VALUES ('20', '18', '5', '6', '1');
INSERT INTO `cmf_term_relationships` VALUES ('21', '19', '5', '3', '1');

-- ----------------------------
-- Table structure for cmf_users
-- ----------------------------
DROP TABLE IF EXISTS `cmf_users`;
CREATE TABLE `cmf_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` varchar(64) NOT NULL DEFAULT '' COMMENT '登录密码；sp_password加密',
  `user_nicename` varchar(50) DEFAULT '' COMMENT '用户美名',
  `user_email` varchar(100) NOT NULL DEFAULT '' COMMENT '登录邮箱',
  `user_url` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人网站',
  `avatar` varchar(255) DEFAULT NULL COMMENT '用户头像，相对于upload/avatar目录',
  `sex` smallint(1) DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `signature` varchar(255) DEFAULT NULL COMMENT '个性签名',
  `last_login_ip` varchar(16) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '最后登录时间',
  `create_time` datetime NOT NULL DEFAULT '2000-01-01 00:00:00' COMMENT '注册时间',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '' COMMENT '激活码',
  `user_status` int(11) NOT NULL DEFAULT '1' COMMENT '用户状态 0：禁用； 1：正常 ；2：未验证',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `user_type` smallint(1) DEFAULT '1' COMMENT '用户类型，1:admin ;2:会员',
  `coin` int(11) NOT NULL DEFAULT '0' COMMENT '金币',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `school` varchar(255) DEFAULT NULL COMMENT '机构名称',
  `province` varchar(255) DEFAULT NULL COMMENT '省',
  `city` varchar(255) DEFAULT NULL COMMENT '市',
  `area` varchar(255) DEFAULT NULL COMMENT '区',
  `item` varchar(500) DEFAULT NULL COMMENT '经营项目',
  `amount` varchar(255) DEFAULT NULL COMMENT '规模',
  `level` tinyint(3) DEFAULT '0' COMMENT '0为试用用户1为付费用户',
  `contact` varchar(255) DEFAULT NULL COMMENT '校长或者负责人',
  `contact_mobile` varchar(255) DEFAULT NULL COMMENT '校长负责人电话',
  `update_time` int(11) DEFAULT '0' COMMENT '更新日期',
  `permission` varchar(1000) DEFAULT NULL COMMENT '权限',
  `valid_time` int(11) DEFAULT '0' COMMENT '账号有效期',
  `remark1` varchar(100) DEFAULT NULL COMMENT '备注1',
  `remark2` varchar(100) DEFAULT NULL COMMENT '其他备注',
  `is_deal` tinyint(2) DEFAULT '0' COMMENT '是否指定账号有效期',
  `from_user` varchar(100) DEFAULT NULL COMMENT '微信标示',
  `nickname` varchar(100) DEFAULT NULL COMMENT '微信昵称',
  `wx_avatar` varchar(255) DEFAULT NULL COMMENT '微信头像',
  `pid` int(11) DEFAULT NULL COMMENT '邀请者id',
  `pname` varchar(32) DEFAULT NULL COMMENT '邀请者信息',
  `invite_code` varchar(32) DEFAULT NULL COMMENT '邀请码',
  PRIMARY KEY (`id`),
  KEY `user_login_key` (`user_login`) USING BTREE,
  KEY `user_nicename` (`user_nicename`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of cmf_users
-- ----------------------------
INSERT INTO `cmf_users` VALUES ('1', 'admin', '###39d39a59dcf80625b58001d032b3c868', 'admin', '526273673@qq.com', '', null, '0', null, null, '127.0.0.1', '2017-05-19 10:40:17', '2016-07-04 03:54:29', '', '1', '0', '1', '0', '', null, null, null, null, null, null, '0', null, null, '0', null, '0', null, null, '0', null, null, null, null, null, null);
INSERT INTO `cmf_users` VALUES ('3', '17301050760', '###ccf0e0bf20ace8f4d18c5b3ef5361386', '路人甲', '', '', null, '0', null, null, '127.0.0.1', '2017-05-19 12:00:28', '2017-05-19 11:18:40', '', '1', '0', '2', '0', '17301050760', null, null, null, null, null, null, '0', null, null, '0', null, '1497842320', null, null, '0', null, null, null, null, null, null);
INSERT INTO `cmf_users` VALUES ('297', '15090067067', '', '樊琦', '', '', null, '0', null, null, '127.0.0.1', '2017-05-19 14:43:59', '2017-05-19 14:43:59', '', '1', '0', '2', '0', '15090067067', null, null, null, null, null, null, '4', null, null, '0', null, '0', null, null, '0', null, null, null, null, null, null);

-- ----------------------------
-- Table structure for cmf_user_favorites
-- ----------------------------
DROP TABLE IF EXISTS `cmf_user_favorites`;
CREATE TABLE `cmf_user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) DEFAULT NULL COMMENT '用户 id',
  `title` varchar(255) DEFAULT NULL COMMENT '收藏内容的标题',
  `url` varchar(255) DEFAULT NULL COMMENT '收藏内容的原文地址，不带域名',
  `description` varchar(500) DEFAULT NULL COMMENT '收藏内容的描述',
  `table` varchar(50) DEFAULT NULL COMMENT '收藏实体以前所在表，不带前缀',
  `object_id` int(11) DEFAULT NULL COMMENT '收藏内容原来的主键id',
  `createtime` int(11) DEFAULT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收藏表';

-- ----------------------------
-- Records of cmf_user_favorites
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_vote_data
-- ----------------------------
DROP TABLE IF EXISTS `cmf_vote_data`;
CREATE TABLE `cmf_vote_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_vote_data
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_weicircle_content
-- ----------------------------
DROP TABLE IF EXISTS `cmf_weicircle_content`;
CREATE TABLE `cmf_weicircle_content` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '消息头像',
  `time` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `praise` varchar(255) NOT NULL DEFAULT '',
  `pic1` varchar(255) NOT NULL DEFAULT '' COMMENT '消息图片1',
  `pic2` varchar(255) NOT NULL DEFAULT '' COMMENT '消息图片2',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT '推广连接',
  `listorder` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_weicircle_content
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_weicircle_msg
-- ----------------------------
DROP TABLE IF EXISTS `cmf_weicircle_msg`;
CREATE TABLE `cmf_weicircle_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '对应内容id',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `backnickname` varchar(255) NOT NULL DEFAULT '' COMMENT '回复的姓名',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '回复内容',
  `createtime` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_weicircle_msg
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_weidioms_chengyu
-- ----------------------------
DROP TABLE IF EXISTS `cmf_weidioms_chengyu`;
CREATE TABLE `cmf_weidioms_chengyu` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0',
  `idioms` varchar(255) NOT NULL DEFAULT '',
  `idioms_des` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cmf_weidioms_chengyu
-- ----------------------------
INSERT INTO `cmf_weidioms_chengyu` VALUES ('1', '3', '羊入虎口', '一种动物要把另一种动物吃掉');
INSERT INTO `cmf_weidioms_chengyu` VALUES ('2', '3', '彬彬有礼', '从前有个人叫彬彬，他很有礼貌');

-- ----------------------------
-- Table structure for cmf_weiprice_rule
-- ----------------------------
DROP TABLE IF EXISTS `cmf_weiprice_rule`;
CREATE TABLE `cmf_weiprice_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `aid` int(11) NOT NULL COMMENT '砍价信息id',
  `pice` varchar(200) NOT NULL DEFAULT '' COMMENT '大于金额数多个',
  `start` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价范围起始',
  `end` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍价范围截至',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='砍价规则表';

-- ----------------------------
-- Records of cmf_weiprice_rule
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_weisite
-- ----------------------------
DROP TABLE IF EXISTS `cmf_weisite`;
CREATE TABLE `cmf_weisite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '主题名称',
  `thumb` varchar(255) DEFAULT NULL COMMENT '封面',
  `theme_name` varchar(255) DEFAULT NULL COMMENT '风格名称',
  `school` varchar(255) DEFAULT NULL COMMENT '学校名称',
  `slogan` varchar(100) DEFAULT NULL COMMENT '宣传语',
  `contact` varchar(255) DEFAULT NULL COMMENT '联系人',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `createtime` int(11) DEFAULT '0' COMMENT '创建日期',
  `description` text COMMENT '机构介绍',
  `address` varchar(255) DEFAULT NULL COMMENT '学校地址',
  `school_qrcode` varchar(500) DEFAULT NULL COMMENT '公众号二维码',
  `share_title` varchar(255) DEFAULT NULL COMMENT '分享标题',
  `share_img` varchar(255) DEFAULT NULL COMMENT '分享图片',
  `share_des` varchar(255) DEFAULT NULL COMMENT '分享内容',
  `course` text COMMENT '课程介绍',
  `teacher` text COMMENT '师资团队',
  `hits` int(11) DEFAULT '0' COMMENT '点击次数',
  `share_total` int(11) DEFAULT '0' COMMENT '分享次数',
  `uid` int(11) DEFAULT NULL COMMENT '发布者id',
  `module` varchar(32) DEFAULT NULL COMMENT '模板风格',
  `logo` varchar(500) DEFAULT NULL COMMENT '学校logo',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微官网';

-- ----------------------------
-- Records of cmf_weisite
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_weisite_data
-- ----------------------------
DROP TABLE IF EXISTS `cmf_weisite_data`;
CREATE TABLE `cmf_weisite_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL COMMENT '微官网id',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `description` varchar(1000) DEFAULT NULL COMMENT '描述',
  `thumb` varchar(500) DEFAULT NULL COMMENT '附件',
  `type` varchar(32) DEFAULT NULL COMMENT '类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微官网附件表';

-- ----------------------------
-- Records of cmf_weisite_data
-- ----------------------------

-- ----------------------------
-- Table structure for cmf_weisite_params
-- ----------------------------
DROP TABLE IF EXISTS `cmf_weisite_params`;
CREATE TABLE `cmf_weisite_params` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT '0' COMMENT '微官网id',
  `field` varchar(32) DEFAULT NULL COMMENT '字段',
  `name` varchar(255) DEFAULT NULL COMMENT '参数名称',
  `value` text COMMENT '参数值',
  `createtime` int(11) DEFAULT '0' COMMENT '活动创建日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微官网个性化参数';

-- ----------------------------
-- Records of cmf_weisite_params
-- ----------------------------

-- ----------------------------
-- Table structure for think_session
-- ----------------------------
DROP TABLE IF EXISTS `think_session`;
CREATE TABLE `think_session` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  UNIQUE KEY `session_id` (`session_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_session
-- ----------------------------

-- ----------------------------
-- Table structure for tp_user_level
-- ----------------------------
DROP TABLE IF EXISTS `tp_user_level`;
CREATE TABLE `tp_user_level` (
  `level_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
  `level_name` varchar(30) DEFAULT NULL COMMENT '头衔名称',
  `amount` decimal(10,2) DEFAULT NULL COMMENT '等级必要金额',
  `discount` smallint(4) DEFAULT NULL COMMENT '折扣',
  `describe` varchar(200) DEFAULT NULL COMMENT '头街 描述',
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tp_user_level
-- ----------------------------
