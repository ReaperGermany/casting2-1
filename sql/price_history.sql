CREATE  TABLE `ci_dimex`.`price_history` (
  `pk_id` INT NOT NULL AUTO_INCREMENT ,
  `offer_id` INT NOT NULL ,
  `currency_id` INT NOT NULL ,
  `price` FLOAT NOT NULL ,
  `created_at` DATETIME NOT NULL ,
  PRIMARY KEY (`pk_id`) );