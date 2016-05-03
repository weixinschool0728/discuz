alter table `ultrax20160427`.`pre_tom_pintuan_goods` 
   add column `shangjia_time` datetime DEFAULT '0000-00-00 00:00:00' NULL COMMENT '上架时间' after `part3`, 
   add column `xiajia_time` datetime DEFAULT '0000-00-00 00:00:00' NULL COMMENT '下架时间' after `shangjia_time`