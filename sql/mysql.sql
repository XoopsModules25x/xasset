#
# Structure for the `xasset_app_product` table : 
#

CREATE TABLE `xasset_app_product` (
  `id` int(11) NOT NULL auto_increment,
  `application_id` int(11) NOT NULL default '0',
  `tax_class_id` int(11) NOT NULL default '0',
  `base_currency_id` int(11) NOT NULL default '0',
  `package_group_id` int(11) default NULL,
  `sample_package_group_id` int(11) default NULL,
  `display_order` tinyint(4) NOT NULL default '0',
  `item_code` varchar(10) default NULL,
  `item_description` varchar(100) default NULL,
  `unit_price` decimal(16,4) NOT NULL default '0.0000',
  `old_unit_price` decimal(16,4) default NULL,
  `min_unit_count` int(11) default NULL,
  `max_access` int(11) default NULL,
  `max_days` int(11) default NULL,
  `expires` int(11) default NULL,
  `credits` int(11) default NULL,
  `add_to_group` int(11) default NULL,
  `add_to_group2` int(11) default NULL,
  `item_rich_description` text,
  `enabled` tinyint(4) default '1',
  `group_expire_date` int(11) default NULL,
  `group_expire_date2` int(11) default NULL,
  `extra_instructions` text,  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `applicationid` (`application_id`),
  KEY `tax_class_id` (`tax_class_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_application` table : 
#

CREATE TABLE `xasset_application` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `description` tinytext,
  `platform` tinytext,
  `version` varchar(10) default NULL,
  `datePublished` int(11) default NULL,
  `requiresLicense` tinyint(4) default '1',
  `hasSamples` tinyint(4) default '0',
  `listInEval` tinyint(4) default NULL,
  `richDescription` text,
  `mainMenu` tinyint(4) default '0',
  `menuItem` varchar(20) default NULL,
  `productsVisible` tinyint(4) default NULL,
  `product_list_template` text,
  `image` varchar(250) default NULL,
   PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_application_groups` table : 
#

CREATE TABLE `xasset_application_groups` (
  `id` int(11) NOT NULL auto_increment,
  `application_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_config` table : 
#

CREATE TABLE `xasset_config` (
  `id` int(11) NOT NULL auto_increment,
  `dkey` varchar(20) NOT NULL default '',
  `dvalue` varchar(50) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_country` table : 
#

CREATE TABLE `xasset_country` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(75) NOT NULL default '',
  `iso2` char(2) NOT NULL default '',
  `iso3` char(3) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `iso3` (`iso3`),
  UNIQUE KEY `iso2` (`iso2`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM;


INSERT INTO `xasset_country` (`id`, `name`, `iso2`, `iso3`) VALUES 
  (1,'Afghanistan','AF','AFG'),
  (2,'Albania','AL','ALB'),
  (3,'Algeria','DZ','DZA'),
  (4,'American Samoa','AS','ASM'),
  (5,'Andorra','AD','AND'),
  (6,'Angola','AO','AGO'),
  (7,'Anguilla','AI','AIA'),
  (8,'Antarctica','AQ','ATA'),
  (9,'Antigua and Barbuda','AG','ATG'),
  (10,'Argentina','AR','ARG'),
  (11,'Armenia','AM','ARM'),
  (12,'Aruba','AW','ABW'),
  (13,'Australia','AU','AUS'),
  (14,'Austria','AT','AUT'),
  (15,'Azerbaijan','AZ','AZE'),
  (16,'Bahamas','BS','BHS'),
  (17,'Bahrain','BH','BHR'),
  (18,'Bangladesh','BD','BGD'),
  (19,'Barbados','BB','BRB'),
  (20,'Belarus','BY','BLR'),
  (21,'Belgium','BE','BEL'),
  (22,'Belize','BZ','BLZ'),
  (23,'Benin','BJ','BEN'),
  (24,'Bermuda','BM','BMU'),
  (25,'Bhutan','BT','BTN'),
  (26,'Bolivia','BO','BOL'),
  (27,'Bosnia and Herzegowina','BA','BIH'),
  (28,'Botswana','BW','BWA'),
  (29,'Bouvet Island','BV','BVT'),
  (30,'Brazil','BR','BRA'),
  (31,'British Indian Ocean Territory','IO','IOT'),
  (32,'Brunei Darussalam','BN','BRN'),
  (33,'Bulgaria','BG','BGR'),
  (34,'Burkina Faso','BF','BFA'),
  (35,'Burundi','BI','BDI'),
  (36,'Cambodia','KH','KHM'),
  (37,'Cameroon','CM','CMR'),
  (38,'Canada','CA','CAN'),
  (39,'Cape Verde','CV','CPV'),
  (40,'Cayman Islands','KY','CYM'),
  (41,'Central African Republic','CF','CAF'),
  (42,'Chad','TD','TCD'),
  (43,'Chile','CL','CHL'),
  (44,'China','CN','CHN'),
  (45,'Christmas Island','CX','CXR'),
  (46,'Cocos (Keeling) Islands','CC','CCK'),
  (47,'Colombia','CO','COL'),
  (48,'Comoros','KM','COM'),
  (49,'Congo','CG','COG'),
  (50,'Cook Islands','CK','COK'),
  (51,'Costa Rica','CR','CRI'),
  (52,'Cote D\'Ivoire','CI','CIV'),
  (53,'Croatia','HR','HRV'),
  (54,'Cuba','CU','CUB'),
  (55,'Cyprus','CY','CYP'),
  (56,'Czech Republic','CZ','CZE'),
  (57,'Denmark','DK','DNK'),
  (58,'Djibouti','DJ','DJI'),
  (59,'Dominica','DM','DMA'),
  (60,'Dominican Republic','DO','DOM'),
  (61,'East Timor','TP','TMP'),
  (62,'Ecuador','EC','ECU'),
  (63,'Egypt','EG','EGY'),
  (64,'El Salvador','SV','SLV'),
  (65,'Equatorial Guinea','GQ','GNQ'),
  (66,'Eritrea','ER','ERI'),
  (67,'Estonia','EE','EST'),
  (68,'Ethiopia','ET','ETH'),
  (69,'Falkland Islands (Malvinas)','FK','FLK'),
  (70,'Faroe Islands','FO','FRO'),
  (71,'Fiji','FJ','FJI'),
  (72,'Finland','FI','FIN'),
  (73,'France','FR','FRA'),
  (74,'France, Metropolitan','FX','FXX'),
  (75,'French Guiana','GF','GUF'),
  (76,'French Polynesia','PF','PYF'),
  (77,'French Southern Territories','TF','ATF'),
  (78,'Gabon','GA','GAB'),
  (79,'Gambia','GM','GMB'),
  (80,'Georgia','GE','GEO'),
  (81,'Germany','DE','DEU'),
  (82,'Ghana','GH','GHA'),
  (83,'Gibraltar','GI','GIB'),
  (84,'Greece','GR','GRC'),
  (85,'Greenland','GL','GRL'),
  (86,'Grenada','GD','GRD'),
  (87,'Guadeloupe','GP','GLP'),
  (88,'Guam','GU','GUM'),
  (89,'Guatemala','GT','GTM'),
  (90,'Guinea','GN','GIN'),
  (91,'Guinea-bissau','GW','GNB'),
  (92,'Guyana','GY','GUY'),
  (93,'Haiti','HT','HTI'),
  (94,'Heard and Mc Donald Islands','HM','HMD'),
  (95,'Honduras','HN','HND'),
  (96,'Hong Kong','HK','HKG'),
  (97,'Hungary','HU','HUN'),
  (98,'Iceland','IS','ISL'),
  (99,'India','IN','IND'),
  (100,'Indonesia','ID','IDN'),
  (101,'Iran (Islamic Republic of)','IR','IRN'),
  (102,'Iraq','IQ','IRQ'),
  (103,'Ireland','IE','IRL'),
  (104,'Israel','IL','ISR'),
  (105,'Italy','IT','ITA'),
  (106,'Jamaica','JM','JAM'),
  (107,'Japan','JP','JPN'),
  (108,'Jordan','JO','JOR'),
  (109,'Kazakhstan','KZ','KAZ'),
  (110,'Kenya','KE','KEN'),
  (111,'Kiribati','KI','KIR'),
  (112,'Korea, Democratic People\'s Republic of','KP','PRK'),
  (113,'Korea, Republic of','KR','KOR'),
  (114,'Kuwait','KW','KWT'),
  (115,'Kyrgyzstan','KG','KGZ'),
  (116,'Lao People\'s Democratic Republic','LA','LAO'),
  (117,'Latvia','LV','LVA'),
  (118,'Lebanon','LB','LBN'),
  (119,'Lesotho','LS','LSO'),
  (120,'Liberia','LR','LBR'),
  (121,'Libyan Arab Jamahiriya','LY','LBY'),
  (122,'Liechtenstein','LI','LIE'),
  (123,'Lithuania','LT','LTU'),
  (124,'Luxembourg','LU','LUX'),
  (125,'Macau','MO','MAC'),
  (126,'Macedonia, The Former Yugoslav Republic of','MK','MKD'),
  (127,'Madagascar','MG','MDG'),
  (128,'Malawi','MW','MWI'),
  (129,'Malaysia','MY','MYS'),
  (130,'Maldives','MV','MDV'),
  (131,'Mali','ML','MLI'),
  (132,'Malta','MT','MLT'),
  (133,'Marshall Islands','MH','MHL'),
  (134,'Martinique','MQ','MTQ'),
  (135,'Mauritania','MR','MRT'),
  (136,'Mauritius','MU','MUS'),
  (137,'Mayotte','YT','MYT'),
  (138,'Mexico','MX','MEX'),
  (139,'Micronesia, Federated States of','FM','FSM'),
  (140,'Moldova, Republic of','MD','MDA'),
  (141,'Monaco','MC','MCO'),
  (142,'Mongolia','MN','MNG'),
  (143,'Montserrat','MS','MSR'),
  (144,'Morocco','MA','MAR'),
  (145,'Mozambique','MZ','MOZ'),
  (146,'Myanmar','MM','MMR'),
  (147,'Namibia','NA','NAM'),
  (148,'Nauru','NR','NRU'),
  (149,'Nepal','NP','NPL'),
  (150,'Netherlands','NL','NLD'),
  (151,'Netherlands Antilles','AN','ANT'),
  (152,'New Caledonia','NC','NCL'),
  (153,'New Zealand','NZ','NZL'),
  (154,'Nicaragua','NI','NIC'),
  (155,'Niger','NE','NER'),
  (156,'Nigeria','NG','NGA'),
  (157,'Niue','NU','NIU'),
  (158,'Norfolk Island','NF','NFK'),
  (159,'Northern Mariana Islands','MP','MNP'),
  (160,'Norway','NO','NOR'),
  (161,'Oman','OM','OMN'),
  (162,'Pakistan','PK','PAK'),
  (163,'Palau','PW','PLW'),
  (164,'Panama','PA','PAN'),
  (165,'Papua New Guinea','PG','PNG'),
  (166,'Paraguay','PY','PRY'),
  (167,'Peru','PE','PER'),
  (168,'Philippines','PH','PHL'),
  (169,'Pitcairn','PN','PCN'),
  (170,'Poland','PL','POL'),
  (171,'Portugal','PT','PRT'),
  (172,'Puerto Rico','PR','PRI'),
  (173,'Qatar','QA','QAT'),
  (174,'Reunion','RE','REU'),
  (175,'Romania','RO','ROM'),
  (176,'Russian Federation','RU','RUS'),
  (177,'Rwanda','RW','RWA'),
  (178,'Saint Kitts and Nevis','KN','KNA'),
  (179,'Saint Lucia','LC','LCA'),
  (180,'Saint Vincent and the Grenadines','VC','VCT'),
  (181,'Samoa','WS','WSM'),
  (182,'San Marino','SM','SMR'),
  (183,'Sao Tome and Principe','ST','STP'),
  (184,'Saudi Arabia','SA','SAU'),
  (185,'Senegal','SN','SEN'),
  (186,'Seychelles','SC','SYC'),
  (187,'Sierra Leone','SL','SLE'),
  (188,'Singapore','SG','SGP'),
  (189,'Slovakia (Slovak Republic)','SK','SVK'),
  (190,'Slovenia','SI','SVN'),
  (191,'Solomon Islands','SB','SLB'),
  (192,'Somalia','SO','SOM'),
  (193,'South Africa','ZA','ZAF'),
  (194,'South Georgia and the South Sandwich Islands','GS','SGS'),
  (195,'Spain','ES','ESP'),
  (196,'Sri Lanka','LK','LKA'),
  (197,'St. Helena','SH','SHN'),
  (198,'St. Pierre and Miquelon','PM','SPM'),
  (199,'Sudan','SD','SDN'),
  (200,'Suriname','SR','SUR'),
  (201,'Svalbard and Jan Mayen Islands','SJ','SJM'),
  (202,'Swaziland','SZ','SWZ'),
  (203,'Sweden','SE','SWE'),
  (204,'Switzerland','CH','CHE'),
  (205,'Syrian Arab Republic','SY','SYR'),
  (206,'Taiwan','TW','TWN'),
  (207,'Tajikistan','TJ','TJK'),
  (208,'Tanzania, United Republic of','TZ','TZA'),
  (209,'Thailand','TH','THA'),
  (210,'Togo','TG','TGO'),
  (211,'Tokelau','TK','TKL'),
  (212,'Tonga','TO','TON'),
  (213,'Trinidad and Tobago','TT','TTO'),
  (214,'Tunisia','TN','TUN'),
  (215,'Turkey','TR','TUR'),
  (216,'Turkmenistan','TM','TKM'),
  (217,'Turks and Caicos Islands','TC','TCA'),
  (218,'Tuvalu','TV','TUV'),
  (219,'Uganda','UG','UGA'),
  (220,'Ukraine','UA','UKR'),
  (221,'United Arab Emirates','AE','ARE'),
  (222,'United Kingdom','GB','GBR'),
  (223,'United States','US','USA'),
  (224,'United States Minor Outlying Islands','UM','UMI'),
  (225,'Uruguay','UY','URY'),
  (226,'Uzbekistan','UZ','UZB'),
  (227,'Vanuatu','VU','VUT'),
  (228,'Vatican City State (Holy See)','VA','VAT'),
  (229,'Venezuela','VE','VEN'),
  (230,'Viet Nam','VN','VNM'),
  (231,'Virgin Islands (British)','VG','VGB'),
  (232,'Virgin Islands (U.S.)','VI','VIR'),
  (233,'Wallis and Futuna Islands','WF','WLF'),
  (234,'Western Sahara','EH','ESH'),
  (235,'Yemen','YE','YEM'),
  (236,'Yugoslavia','YU','YUG'),
  (237,'Zaire','ZR','ZAR'),
  (238,'Zambia','ZM','ZMB'),
  (239,'Zimbabwe','ZW','ZWE');

  
#
# Structure for the `xasset_currency` table : 
#

CREATE TABLE `xasset_currency` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `code` char(3) default NULL,
  `decimal_places` tinyint(4) default NULL,
  `symbol_left` varchar(10) default NULL,
  `symbol_right` varchar(10) default NULL,
  `decimal_point` char(1) default NULL,
  `thousands_point` char(1) default NULL,
  `value` float(13,8) NOT NULL default '0.00000000',
  `enabled` tinyint(4) default '1',
  `updated` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM;

INSERT INTO `xasset_currency` VALUES (1, 'US Dollar', 'USD', 2, '$', '', '.', ',', 1.66999996, 1, null);
INSERT INTO `xasset_currency` VALUES (2, 'Pound Sterling', 'GBP', 2, '£', '', '.', ',', 1.00000000, 1, null);
INSERT INTO `xasset_currency` VALUES (3, 'Brazil Reais', 'BRL', 2, 'R$', '', '.', ',', 4.0649474155, 1, null);

#
# Structure for the `xasset_gateway` table : 
#

CREATE TABLE `xasset_gateway` (
  `id` tinyint(4) NOT NULL auto_increment,
  `code` varchar(10) NOT NULL default '',
  `enabled` tinyint(4) default '0',
  `gorder` smallint(6) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_gateway_detail` table : 
#

CREATE TABLE `xasset_gateway_detail` (
  `id` int(11) NOT NULL auto_increment,
  `gateway_id` int(11) NOT NULL default '0',
  `gorder` tinyint(4) default NULL,
  `gkey` varchar(30) default NULL,
  `gvalue` text,
  `description` varchar(200) default NULL,
  `list_ov` varchar(200) default NULL,
  `gtype` char(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `gateway_id` (`gateway_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_gateway_log` table : 
#

CREATE TABLE `xasset_gateway_log` (
  `id` int(11) NOT NULL auto_increment,
  `gateway_id` int(11) default NULL,
  `order_id` int(11) default NULL,
  `date` int(11) default NULL,
  `order_stage` tinyint(4) default NULL,
  `log_text` mediumtext,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_license` table : 
#

CREATE TABLE `xasset_license` (
  `id` int(4) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `applicationid` int(11) NOT NULL default '0',
  `authKey` varchar(50) default NULL,
  `authCode` varchar(100) default NULL,
  `expires` int(11) default NULL,
  `dateIssued` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `applicationid` (`applicationid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_links` table : 
#

CREATE TABLE `xasset_links` (
  `id` int(11) NOT NULL auto_increment,
  `applicationid` int(11) default NULL,
  `name` tinytext,
  `link` tinytext,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `applicationid` (`applicationid`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_order_detail` table : 
#

CREATE TABLE `xasset_order_detail` (
  `id` int(11) NOT NULL auto_increment,
  `order_index_id` int(11) NOT NULL default '0',
  `app_prod_id` int(11) NOT NULL default '0',
  `qty` int(9) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `order_index_id` (`order_index_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_order_index` table : 
#

CREATE TABLE `xasset_order_index` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `user_detail_id` int(11) NOT NULL default '0',
  `currency_id` int(11) default NULL,
  `number` int(11) NOT NULL default '0',
  `date` int(11) default NULL,
  `status` tinyint(4) NOT NULL default '0',
  `gateway` int(11) default NULL,
  `trans_id` varchar(200) default NULL,
  `value` DOUBLE(15,3) DEFAULT NULL,
  `fee` DOUBLE(15,3) DEFAULT NULL,  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `user_detail_id` (`user_detail_id`),
  KEY `uid` (`uid`),
  KEY `trans_id` (`trans_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_package` table : 
#

CREATE TABLE `xasset_package` (
  `id` int(11) NOT NULL auto_increment,
  `packagegroupid` int(11) default NULL,
  `filename` varchar(32) default NULL,
  `serverFilePath` varchar(255) default NULL,
  `filesize` int(11) default NULL,
  `filetype` varchar(10) default NULL,
  `protected` tinyint(4) default '1',
  `isVideo` tinyint(4) default '0',  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `packagegroupid` (`packagegroupid`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_packagegroup` table : 
#

CREATE TABLE `xasset_packagegroup` (
  `id` int(11) NOT NULL auto_increment,
  `applicationid` int(11) default NULL,
  `name` varchar(50) default NULL,
  `grpDesc` tinytext,
  `version` varchar(10) default NULL,
  `datePublished` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `applicationid` (`applicationid`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_region` table : 
#

CREATE TABLE `xasset_region` (
  `id` int(4) NOT NULL auto_increment,
  `region` varchar(30) NOT NULL default '',
  `description` varchar(200) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_tax_class` table : 
#

CREATE TABLE `xasset_tax_class` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(30) NOT NULL default '',
  `description` varchar(200) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;


INSERT INTO `xasset_tax_class` VALUES (1, 'none', 'none');

#
# Structure for the `xasset_tax_rates` table : 
#

CREATE TABLE `xasset_tax_rates` (
  `id` int(11) NOT NULL auto_increment,
  `region_id` int(11) NOT NULL default '0',
  `tax_class_id` int(11) NOT NULL default '0',
  `rate` decimal(7,4) NOT NULL default '0.0000',
  `priority` tinyint(4) default NULL,
  `description` varchar(200) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `zone_id` (`region_id`),
  KEY `tax_class_id` (`tax_class_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_tax_zone` table : 
#

CREATE TABLE `xasset_tax_zone` (
  `id` int(11) NOT NULL auto_increment,
  `region_id` int(30) NOT NULL default '0',
  `country_id` int(11) NOT NULL default '0',
  `zone_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `country_id` (`country_id`),
  KEY `zone_id` (`zone_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_user_details` table : 
#

CREATE TABLE `xasset_user_details` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `zone_id` int(11) default '0',
  `country_id` int(11) NOT NULL default '0',
  `first_name` varchar(50) NOT NULL default '',
  `last_name` varchar(50) NOT NULL default '',
  `street_address1` varchar(200) NOT NULL default '',
  `street_address2` varchar(200) default NULL,
  `town` varchar(30) default NULL,
  `state` varchar(30) default NULL,
  `zip` varchar(20) default NULL,
  `tel_no` varchar(30) default NULL,
  `fax_no` varchar(30) default NULL,
  `company_name` varchar(100) NOT NULL default '',
  `company_reg` varchar(50) default '',
  `vat_no` varchar(30) default '',
  `client_type` tinyint(4) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `uid` (`uid`),
  KEY `zone_id` (`zone_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_user_products` table : 
#

CREATE TABLE `xasset_user_products` (
  `id` int(11) NOT NULL auto_increment,
  `application_product_id` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_userpackagestats` table : 
#

CREATE TABLE `xasset_userpackagestats` (
  `id` int(11) NOT NULL auto_increment,
  `packageid` int(11) NOT NULL default '0',
  `uid` int(11) default NULL,
  `ip` varchar(50) default NULL,
  `date` int(11) default NULL,
  `dns` varchar(250) default NULL,  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `packageid` (`packageid`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_zone` table : 
#

CREATE TABLE `xasset_zone` (
  `id` int(11) NOT NULL auto_increment,
  `country_id` int(11) NOT NULL default '0',
  `code` varchar(20) NOT NULL default '',
  `name` varchar(30) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=MyISAM;

#
# Structure for the `xasset_app_prod_memb` table : 
#

CREATE TABLE `xasset_app_prod_memb` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0',
  `order_detail_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  `expiry_date` int(11) NOT NULL default '0',
  `sent_warning` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `uid` (`uid`),
  KEY `order_detail_id` (`order_detail_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM

