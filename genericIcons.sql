ALTER TABLE `engine_module_generic_icon`  
ADD `iconLocation` INT(2) NOT NULL DEFAULT '1'  AFTER `iconWidth`,  
ADD `iconRole` INT(2) NOT NULL DEFAULT '1'  AFTER `iconLocation`,
ADD `iconProductAvail` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `iconRole`
;