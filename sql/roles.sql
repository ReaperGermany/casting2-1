CREATE  TABLE `roles` (
  `pk_id` INT NOT NULL AUTO_INCREMENT ,
  `code` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`pk_id`) ,
  UNIQUE INDEX `code_UNIQUE` (`code` ASC) )
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

INSERT INTO `roles` (`code`) VALUES ('admin');
INSERT INTO `roles` (`code`) VALUES ('salesman');