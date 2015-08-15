SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `heroku_898f5c8bdd31c1b` ;
CREATE SCHEMA IF NOT EXISTS `heroku_898f5c8bdd31c1b` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `heroku_898f5c8bdd31c1b` ;

-- -----------------------------------------------------
-- Table `heroku_898f5c8bdd31c1b`.`user_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `heroku_898f5c8bdd31c1b`.`user_status` ;

CREATE  TABLE IF NOT EXISTS `heroku_898f5c8bdd31c1b`.`user_status` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `heroku_898f5c8bdd31c1b`.`invite_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `heroku_898f5c8bdd31c1b`.`invite_status` ;

CREATE  TABLE IF NOT EXISTS `heroku_898f5c8bdd31c1b`.`invite_status` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(8) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `heroku_898f5c8bdd31c1b`.`invite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `heroku_898f5c8bdd31c1b`.`invite` ;

CREATE  TABLE IF NOT EXISTS `heroku_898f5c8bdd31c1b`.`invite` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user1_id` INT NOT NULL ,
  `user2_id` INT NOT NULL ,
  `invite_status_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_invite_user1` (`user1_id` ASC) ,
  INDEX `fk_invite_user2` (`user2_id` ASC) ,
  INDEX `fk_invite_invite_status1` (`invite_status_id` ASC) ,
  CONSTRAINT `fk_invite_user1`
    FOREIGN KEY (`user1_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`user` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_invite_user2`
    FOREIGN KEY (`user2_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`user` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_invite_invite_status1`
    FOREIGN KEY (`invite_status_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`invite_status` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `heroku_898f5c8bdd31c1b`.`match_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `heroku_898f5c8bdd31c1b`.`match_status` ;

CREATE  TABLE IF NOT EXISTS `heroku_898f5c8bdd31c1b`.`match_status` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(8) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `heroku_898f5c8bdd31c1b`.`match`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `heroku_898f5c8bdd31c1b`.`match` ;

CREATE  TABLE IF NOT EXISTS `heroku_898f5c8bdd31c1b`.`match` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user1_id` INT NOT NULL ,
  `user2_id` INT NOT NULL ,
  `u1_msg` TEXT NULL ,
  `u2_msg` TEXT NULL ,
  `board_state` BLOB NULL ,
  `match_status_id` INT NOT NULL ,
  INDEX `fk_match_user` (`user1_id` ASC) ,
  INDEX `fk_match_user1` (`user2_id` ASC) ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_match_match_status1` (`match_status_id` ASC) ,
  CONSTRAINT `fk_match_user`
    FOREIGN KEY (`user1_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`user` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_match_user1`
    FOREIGN KEY (`user2_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`user` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_match_match_status1`
    FOREIGN KEY (`match_status_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`match_status` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `heroku_898f5c8bdd31c1b`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `heroku_898f5c8bdd31c1b`.`user` ;

CREATE  TABLE IF NOT EXISTS `heroku_898f5c8bdd31c1b`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `login` VARCHAR(10) NOT NULL ,
  `first` VARCHAR(10) NOT NULL ,
  `last` VARCHAR(10) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `salt` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `user_status_id` INT NOT NULL ,
  `invite_id` INT NULL DEFAULT NULL ,
  `match_id` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `login_UNIQUE` (`login` ASC) ,
  UNIQUE INDEX `password_UNIQUE` (`password` ASC) ,
  INDEX `fk_user_user_status1` (`user_status_id` ASC) ,
  INDEX `fk_user_invite1` (`invite_id` ASC) ,
  INDEX `fk_user_match1` (`match_id` ASC) ,
  CONSTRAINT `fk_user_user_status1`
    FOREIGN KEY (`user_status_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`user_status` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_invite1`
    FOREIGN KEY (`invite_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`invite` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_match1`
    FOREIGN KEY (`match_id` )
    REFERENCES `heroku_898f5c8bdd31c1b`.`match` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `heroku_898f5c8bdd31c1b`.`user_status`
-- -----------------------------------------------------
START TRANSACTION;
USE `heroku_898f5c8bdd31c1b`;
INSERT INTO `heroku_898f5c8bdd31c1b`.`user_status` (`id`, `name`) VALUES (1, 'offline');
INSERT INTO `heroku_898f5c8bdd31c1b`.`user_status` (`id`, `name`) VALUES (2, 'available');
INSERT INTO `heroku_898f5c8bdd31c1b`.`user_status` (`id`, `name`) VALUES (3, 'waiting');
INSERT INTO `heroku_898f5c8bdd31c1b`.`user_status` (`id`, `name`) VALUES (4, 'invited');
INSERT INTO `heroku_898f5c8bdd31c1b`.`user_status` (`id`, `name`) VALUES (5, 'playing');

COMMIT;

-- -----------------------------------------------------
-- Data for table `heroku_898f5c8bdd31c1b`.`invite_status`
-- -----------------------------------------------------
START TRANSACTION;
USE `heroku_898f5c8bdd31c1b`;
INSERT INTO `heroku_898f5c8bdd31c1b`.`invite_status` (`id`, `name`) VALUES (1, 'pending');
INSERT INTO `heroku_898f5c8bdd31c1b`.`invite_status` (`id`, `name`) VALUES (2, 'accepted');
INSERT INTO `heroku_898f5c8bdd31c1b`.`invite_status` (`id`, `name`) VALUES (3, 'rejected');
INSERT INTO `heroku_898f5c8bdd31c1b`.`invite_status` (`id`, `name`) VALUES (4, 'timeout');

COMMIT;

-- -----------------------------------------------------
-- Data for table `heroku_898f5c8bdd31c1b`.`match_status`
-- -----------------------------------------------------
START TRANSACTION;
USE `heroku_898f5c8bdd31c1b`;
INSERT INTO `heroku_898f5c8bdd31c1b`.`match_status` (`id`, `name`) VALUES (1, 'active');
INSERT INTO `heroku_898f5c8bdd31c1b`.`match_status` (`id`, `name`) VALUES (2, 'u1win');
INSERT INTO `heroku_898f5c8bdd31c1b`.`match_status` (`id`, `name`) VALUES (3, 'u2win');
INSERT INTO `heroku_898f5c8bdd31c1b`.`match_status` (`id`, `name`) VALUES (4, 'tie');

COMMIT;
