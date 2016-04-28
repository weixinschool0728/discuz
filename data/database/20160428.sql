alter table `ultrax20160427`.`pre_tom_pintuan_goods` 
   add column `is_expresses` tinyint(4) DEFAULT '0' NULL COMMENT '是否开启多级运费' after `express_id`
   