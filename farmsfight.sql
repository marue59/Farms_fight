-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema farmsfight
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema farmsfight
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `farmsfight` DEFAULT CHARACTER SET latin1 ;
USE `farmsfight` ;

-- -----------------------------------------------------
-- Table `farmsfight`.`animals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`animals` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`animals` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `species` VARCHAR(45) NOT NULL,
  `health` INT NOT NULL,
  `attack` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `farmsfight`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`user` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `farm` INT(11) NOT NULL,
  `current_level` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `farmsfight`.`login_history`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`login_history` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`login_history` (
  `login_history_id` INT(11) NOT NULL AUTO_INCREMENT,
  `login_time` TIMESTAMP NULL DEFAULT NULL,
  `logout_time` TIMESTAMP NULL DEFAULT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`login_history_id`),
  INDEX `user_is_idx` (`user_id` ASC),
  CONSTRAINT `user_is`
    FOREIGN KEY (`user_id`)
    REFERENCES `farmsfight`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `farmsfight`.`ressources`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`ressources` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`ressources` (
  `ressources_id` INT(11) NOT NULL,
  `ressources_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ressources_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `farmsfight`.`structures`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`structures` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`structures` (
  `structures_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  PRIMARY KEY (`structures_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `farmsfight`.`user_animals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`user_animals` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`user_animals` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `animals_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `is_alive` TINYINT(1) NOT NULL,
  INDEX `fk_user_animals_1_idx` (`user_id` ASC),
  INDEX `fk_user_animals_2_idx` (`animals_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_animals_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `farmsfight`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_animals_2`
    FOREIGN KEY (`animals_id`)
    REFERENCES `farmsfight`.`animals` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `farmsfight`.`user_ressources`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`user_ressources` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`user_ressources` (
  `user_id` INT(11) NOT NULL,
  `ressources_id` INT(11) NOT NULL,
  `user_ressources_id` INT NOT NULL AUTO_INCREMENT,
  INDEX `fk_user_ressources_1_idx` (`user_id` ASC),
  INDEX `fk_user_ressources_2_idx` (`ressources_id` ASC),
  PRIMARY KEY (`user_ressources_id`),
  CONSTRAINT `fk_user_ressources_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `farmsfight`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_ressources_2`
    FOREIGN KEY (`ressources_id`)
    REFERENCES `farmsfight`.`ressources` (`ressources_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `farmsfight`.`user_structures`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `farmsfight`.`user_structures` ;

CREATE TABLE IF NOT EXISTS `farmsfight`.`user_structures` (
  `user_id` INT(11) NOT NULL,
  `structures_id` INT(11) NOT NULL,
  `user_id1` INT(11) NOT NULL,
  `structures_structures_id` INT(11) NOT NULL,
  INDEX `fk_user_structures_user1_idx` (`user_id1` ASC),
  INDEX `fk_user_structures_structures1_idx` (`structures_structures_id` ASC),
  CONSTRAINT `fk_user_structures_user1`
    FOREIGN KEY (`user_id1`)
    REFERENCES `farmsfight`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_structures_structures1`
    FOREIGN KEY (`structures_structures_id`)
    REFERENCES `farmsfight`.`structures` (`structures_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
