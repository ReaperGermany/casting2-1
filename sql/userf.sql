CREATE  TABLE `userf` (
  `pk_id` INT NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `role_id` INT NULL ,
  PRIMARY KEY (`pk_id`) ,
  UNIQUE INDEX `login_UNIQUE` (`login` ASC) )
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;

ALTER TABLE `userf`
  ADD CONSTRAINT `fk_roles`
  FOREIGN KEY (`role_id` )
  REFERENCES `roles` (`pk_id` )
  ON DELETE SET NULL ON UPDATE NO ACTION
, ADD INDEX `fk_roles` (`role_id` ASC) ;

INSERT INTO `userf` (`login`, `password`, `role_id`) VALUES ('dimon', 'dimon', 1);