-- -------------------
-- Schema confectionary
-- -------------------
DROP SCHEMA IF EXISTS confectionary;
CREATE SCHEMA IF NOT EXISTS confectionary;
USE confectionary;

-- -----------------------------------------------------
-- Table `confectionary`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user`
(
  user_ID INT(8) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  user_name VARCHAR(45) NOT NULL,
  user_password VARCHAR(45) NOT NULL,
  user_admin ENUM('Y','N') NOT NULL,
  user_funds INT(10) NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS company;
CREATE TABLE IF NOT EXISTS company
(
  company_ID INT(8) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  company_name VARCHAR(45) NOT NULL,
  UNIQUE KEY `company_name` (`company_name`)
);

DROP TABLE IF EXISTS category;
CREATE TABLE IF NOT EXISTS category
(
  category_ID INT(8) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  category_name VARCHAR(45) NOT NULL,
  UNIQUE KEY `category_name` (`category_name`)
);

DROP TABLE IF EXISTS item;
CREATE TABLE IF NOT EXISTS item
(
  item_ID INT(8) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  company_ID INT(8) NOT NULL,
  category_ID INT(8) NOT NULL,
  item_desc VARCHAR(45) NOT NULL,
  item_name VARCHAR(45) NOT NULL,
  item_price INT(10) NOT NULL,
  item_stock INT(10) NOT NULL,
  KEY `category_id` (`category_ID`),
  KEY `company_id` (`company_ID`),
  CONSTRAINT `category_id` FOREIGN KEY (`category_ID`) REFERENCES `category` (`category_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `company_id` FOREIGN KEY (`company_ID`) REFERENCES `company` (`company_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS chocolate;
CREATE TABLE IF NOT EXISTS chocolate
(
  chocolate_item_ID INT(8) PRIMARY KEY NOT NULL,
  CONSTRAINT `choc_item_ID` FOREIGN KEY (`chocolate_item_ID`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS pastry;
CREATE TABLE IF NOT EXISTS pastry
(
  pastry_item_ID INT(8) PRIMARY KEY NOT NULL,
  CONSTRAINT `past_item_ID` FOREIGN KEY (`pastry_item_ID`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS cake;
CREATE TABLE IF NOT EXISTS cake
(
  cake_item_ID INT(8) PRIMARY KEY NOT NULL,
  CONSTRAINT `cake_item_ID` FOREIGN KEY (`cake_item_ID`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS candy;
CREATE TABLE IF NOT EXISTS candy
(
  candy_item_ID INT(8) PRIMARY KEY NOT NULL,
  CONSTRAINT `cand_item_ID` FOREIGN KEY (`candy_item_ID`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS `set`;
CREATE TABLE IF NOT EXISTS `set`
(
  set_ID INT(8) PRIMARY KEY NOT NULL,
  chocolate_item_ID INT(8) NOT NULL,
  pastry_item_ID INT(8) NOT NULL,
  cake_item_ID INT(8) NOT NULL,
  candy_item_ID INT(8) NOT NULL,
  set_price INT(10) NOT NULL,
  CONSTRAINT `fk_set_chocolate_item_ID` FOREIGN KEY (`chocolate_item_ID`) REFERENCES `chocolate` (`chocolate_item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_set_pastry_item_ID` FOREIGN KEY (`pastry_item_ID`) REFERENCES `pastry` (`pastry_item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_set_cake_item_ID` FOREIGN KEY (`cake_item_ID`) REFERENCES `cake` (`cake_item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_set_candy_item_ID` FOREIGN KEY (`candy_item_ID`) REFERENCES `candy` (`candy_item_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS catalog;
CREATE TABLE IF NOT EXISTS catalog
(
  catalog_ID INT(8) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  item_ID INT(8) DEFAULT NULL,
  `set_ID` INT(8) DEFAULT NULL,
  KEY `item_ID` (`item_ID`),
  KEY `set_ID` (`set_ID`),
  CONSTRAINT `item_ID` FOREIGN KEY (`item_ID`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_catalog_set_ID` FOREIGN KEY (`set_ID`) REFERENCES `set` (`set_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS cart;
CREATE TABLE IF NOT EXISTS cart
(
  user_ID INT(8) NOT NULL,
  catalog_ID INT(8) NOT NULL,
  quantity INT(10) NOT NULL,
  PRIMARY KEY (`user_ID`, `catalog_ID`),
  KEY `catalog_ID` (`catalog_ID`),
  CONSTRAINT `user_ID` FOREIGN KEY (`user_ID`) REFERENCES `user` (`user_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `catalog_ID` FOREIGN KEY (`catalog_ID`) REFERENCES `catalog` (`catalog_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insert into `user` table
INSERT INTO `user` (user_name, user_password, user_admin, user_funds) VALUES 
('john_doe', 'password123', 'N', 100),
('admin_user', 'adminpass', 'Y', 500);

-- Insert into `company` table
INSERT INTO company (company_name) VALUES
('Sweet Delights'),
('ChocoMasters');

-- Insert into `category` table
INSERT INTO category (category_name) VALUES
('Chocolate'),
('Pastry'),
('Cake'),
('Candy');

-- Insert into `item` table
INSERT INTO item (company_ID, category_ID, item_desc, item_name, item_price, item_stock) VALUES
(1, 1, 'Milk Chocolate Bar', 'Choco Delight', 5, 100),
(2, 2, 'Creamy Pastry', 'Pastry Paradise', 10, 50),
(1, 3, 'Chocolate Fudge Cake', 'Fudgy Bliss Cake', 20, 30),
(2, 4, 'Assorted Candy Pack', 'Sweet Treats', 3, 200);

-- Insert into `chocolate` table
INSERT INTO chocolate (chocolate_item_ID) VALUES
(1);

-- Insert into `pastry` table
INSERT INTO pastry (pastry_item_ID) VALUES
(2);

-- Insert into `cake` table
INSERT INTO cake (cake_item_ID) VALUES
(3);

-- Insert into `candy` table
INSERT INTO candy (candy_item_ID) VALUES
(4);

-- Insert into `set` table
INSERT INTO `set` (set_ID, chocolate_item_ID, pastry_item_ID, cake_item_ID, candy_item_ID, set_price) VALUES
(1, 1, 2, 3, 4, 35);

-- Insert into `catalog` table
INSERT INTO catalog (item_ID, set_ID) VALUES
(1, NULL),
(2, NULL),
(3, NULL),
(NULL, 1);

-- Insert into `cart` table
INSERT INTO cart (user_ID, catalog_ID, quantity) VALUES
(1, 1, 2),
(2, 3, 1);
