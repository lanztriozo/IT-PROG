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

DROP TABLE IF EXISTS item;
CREATE TABLE IF NOT EXISTS item
(
  item_ID INT(8) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  company_ID INT(8) NOT NULL,
  category ENUM('Cake','Candy','Chocolate','Pastry') NOT NULL,
  item_desc VARCHAR(45) NOT NULL,
  item_name VARCHAR(45) NOT NULL,
  item_price INT(10) NOT NULL,
  item_stock INT(10) NOT NULL,
  KEY `company_id` (`company_ID`),
  CONSTRAINT `company_id` FOREIGN KEY (`company_ID`) REFERENCES `company` (`company_ID`) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS `set`;
CREATE TABLE IF NOT EXISTS `set`
(
  set_ID INT(8) PRIMARY KEY NOT NULL,
  item_ID_1 INT(8) NOT NULL,
  item_ID_2 INT(8) NOT NULL,
  item_ID_3 INT(8) NOT NULL,
  item_ID_4 INT(8) NOT NULL,
  set_price INT(10) NOT NULL,
  CONSTRAINT `fk_set_item_ID_1` FOREIGN KEY (`item_ID_1`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_set_item_ID_2` FOREIGN KEY (`item_ID_2`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_set_item_ID_3` FOREIGN KEY (`item_ID_3`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_set_item_ID_4` FOREIGN KEY (`item_ID_4`) REFERENCES `item` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE
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

INSERT INTO `user` (user_name, user_password, user_admin, user_funds) VALUES
('john_doe', 'password123', 'N', 100),
('admin_user', 'adminpass', 'Y', 500),
('alice_smith', 'alicepass', 'N', 250),
('bob_jones', 'bobpass', 'N', 300),
('emma_davis', 'emmapass', 'N', 150),
('charlie_brown', 'charliepass', 'N', 200),
('sophia_wilson', 'sophiapass', 'N', 400),
('oliver_taylor', 'oliverpass', 'N', 350),
('isabella_clark', 'isabellapass', 'N', 450),
('jackson_white', 'jacksonpass', 'N', 275);

-- Insert into `company` table
INSERT INTO company (company_name) VALUES
('Sweet Delights'),
('ChocoMasters');

-- Insert into `item` table
INSERT INTO item (company_ID, category, item_desc, item_name, item_price, item_stock) VALUES
(1, 'Chocolate', 'Milk Chocolate Bar', 'Choco Delight', 5, 100),
(2, 'Pastry', 'Creamy Pastry', 'Pastry Paradise', 10, 50),
(1, 'Cake', 'Chocolate Fudge Cake', 'Fudgy Bliss Cake', 20, 30),
(2, 'Candy', 'Assorted Candy Pack', 'Sweet Treats', 3, 200),
(1, 'Chocolate', 'Dark Chocolate Bar', 'Dark Delight', 6, 80),
(2, 'Chocolate', 'White Chocolate Bar', 'White Wonder', 7, 70),
(1, 'Pastry', 'Fruit Tart', 'Fruity Delight', 12, 40),
(2, 'Cake', 'Red Velvet Cake', 'Velvety Red', 25, 25),
(1, 'Candy', 'Hard Candy Mix', 'Sweet Mix', 4, 150),
(2, 'Pastry', 'Chocolate Croissant', 'Choco Croissant', 8, 60),
(1, 'Chocolate', 'Hazelnut Chocolate Bar', 'Hazelnut Heaven', 7, 75),
(2, 'Cake', 'Cheesecake Slice', 'Creamy Cheesecake', 15, 35),
(1, 'Candy', 'Gummy Bears', 'Bear Treats', 3, 180),
(2, 'Chocolate', 'Mint Chocolate Bar', 'Minty Bliss', 6, 85);

-- Insert into `set` table
INSERT INTO `set` (set_ID, item_ID_1, item_ID_2, item_ID_3, item_ID_4, set_price) VALUES
(1, 1, 2, 3, 4, 35);

-- Insert into `catalog` table
INSERT INTO catalog (item_ID, set_ID) VALUES
(NULL, 1);

-- Insert into `cart`
INSERT INTO cart (user_ID, catalog_ID, quantity) VALUES
(1, 1, 2),
(2, 1, 1);
