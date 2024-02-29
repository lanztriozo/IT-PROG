-- -------------------
-- Schema confectionary_db
-- -------------------
DROP SCHEMA IF EXISTS confectionary_db;
CREATE SCHEMA IF NOT EXISTS confectionary_db;
USE confectionary_db;

-- -----------------------------------------------------
-- Table `mydb`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User`;
CREATE TABLE IF NOT EXISTS `User`
(
user_id INT PRIMARY KEY NOT NULL,
user_name VARCHAR(45) NOT NULL,
user_password VARCHAR(45) NOT NULL,
isAdmin TINYINT(1) NOT NULL,
wallet INT NOT NULL
);

DROP TABLE IF EXISTS Company;
CREATE TABLE IF NOT EXISTS Company
(
company_id INT PRIMARY KEY NOT NULL,
company_name VARCHAR(45) NOT NULL
);


DROP TABLE IF EXISTS Category;
CREATE TABLE IF NOT EXISTS Category
(
category_id INT PRIMARY KEY NOT NULL,
category_name VARCHAR(45) NOT NULL
);

DROP TABLE IF EXISTS Item;
CREATE TABLE IF NOT EXISTS Item
(
item_id INT PRIMARY KEY NOT NULL,
company_id INT NOT NULL,
category_id INT NOT NULL,
item_desc VARCHAR(100) NOT NULL,
item_name VARCHAR(45) NOT NULL,
item_price INT NOT NULL,
item_stock INT NOT NULL,
INDEX `ind_item_company_id` (company_id),
INDEX `ind_item_category_id` (category_id),
CONSTRAINT `fk_item_company_id`
FOREIGN KEY (company_id)
REFERENCES confectionary_db.Company (company_id),
CONSTRAINT `fk_item_category_id`
FOREIGN KEY (category_id)
REFERENCES confectionary_db.Category (category_id)
);

DROP TABLE IF EXISTS Chocolate;
CREATE TABLE IF NOT EXISTS Chocolate
(
chocolate_item_id INT PRIMARY KEY NOT NULL,
CONSTRAINT `fk_chocolate_item_id`
FOREIGN KEY (chocolate_item_id)
REFERENCES confectionary_db.Item (item_id)
);

DROP TABLE IF EXISTS Pastry;
CREATE TABLE IF NOT EXISTS Pastry
(
pastry_item_id INT PRIMARY KEY NOT NULL,
CONSTRAINT `fk_pastry_item_id`
FOREIGN KEY (pastry_item_id)
REFERENCES confectionary_db.Item (item_id)
);

DROP TABLE IF EXISTS Cake;
CREATE TABLE IF NOT EXISTS Cake
(
cake_item_id INT PRIMARY KEY NOT NULL,
CONSTRAINT `fk_cake_item_id`
FOREIGN KEY (cake_item_id)
REFERENCES confectionary_db.Item (item_id)
);

DROP TABLE IF EXISTS Candy;
CREATE TABLE IF NOT EXISTS Candy
(
candy_item_id INT PRIMARY KEY NOT NULL,
CONSTRAINT `fk_candy_item_id`
FOREIGN KEY (candy_item_id)
REFERENCES confectionary_db.Item (item_id)
);

DROP TABLE IF EXISTS `Set`;
CREATE TABLE IF NOT EXISTS `Set`
(
set_id INT PRIMARY KEY NOT NULL,
chocolate_item_id INT NOT NULL,
pastry_item_id INT NOT NULL,
cake_item_id INT NOT NULL,
candy_item_id INT NOT NULL,
set_price INT NOT NULL,
CONSTRAINT `fk_set_chocolate_item_id`
FOREIGN KEY (chocolate_item_id)
REFERENCES confectionary_db.Chocolate (chocolate_item_id),
CONSTRAINT `fk_set_pastry_item_id`
FOREIGN KEY (pastry_item_id)
REFERENCES confectionary_db.Pastry (pastry_item_id),
CONSTRAINT `fk_set_cake_item_id`
FOREIGN KEY (cake_item_id)
REFERENCES confectionary_db.Cake (cake_item_id),
CONSTRAINT `fk_set_candy_item_id`
FOREIGN KEY (candy_item_id)
REFERENCES confectionary_db.Candy (candy_item_id)
);

DROP TABLE IF EXISTS Catalog;
CREATE TABLE IF NOT EXISTS Catalog
(
catalog_id INT PRIMARY KEY NOT NULL,
set_id INT UNIQUE NOT NULL,
item_id INT UNIQUE NOT NULL,
CONSTRAINT `fk_set_id`
FOREIGN KEY (set_id)
REFERENCES confectionary_db.`Set` (set_id),
CONSTRAINT `fk_item_id`
FOREIGN KEY (item_id)
REFERENCES confectionary_db.Item (item_id)
);

DROP TABLE IF EXISTS Cart;
CREATE TABLE IF NOT EXISTS Cart
(
user_id INT PRIMARY KEY NOT NULL,
quantity INT NOT NULL,
catalog_id INT NOT NULL,
CONSTRAINT `fk_user_id`
FOREIGN KEY (user_id)
REFERENCES confectionary_db.`User` (user_id),
CONSTRAINT `fk_catalog_id`
FOREIGN KEY (catalog_id)
REFERENCES confectionary_db.Catalog (catalog_id)
);

ALTER TABLE Cart
DROP FOREIGN KEY fk_catalog_id;  -- Drop existing constraint

ALTER TABLE Cart
ADD CONSTRAINT fk_catalog_id
FOREIGN KEY (catalog_id)
REFERENCES Catalog (catalog_id)
ON DELETE CASCADE;

DROP TABLE IF EXISTS item_list;
CREATE TABLE IF NOT EXISTS item_list
(
catalog_id INT NOT NULL,
item_id INT NOT NULL,
CONSTRAINT `fk_catalogue_id`
FOREIGN KEY (catalog_id)
REFERENCES confectionary_db.`Catalog` (catalog_id),
CONSTRAINT `fk_item_id`
FOREIGN KEY (item_id)
REFERENCES confectionary_db.item (item_id)
);
-- User
INSERT INTO `User` (user_id,user_name,user_password,isAdmin, wallet)
VALUES
	(111200,'Zooke','delrosario',0, 1000),
    (111201,'Argos','airatiu',0, 950),
    (111202,'NoVau','pacana',0, 100),
    (111203,'vourgers','santamaria',0, 150),
    (111204,'Venomstrike','trias',0,  2000),
    (111205,'admin','admin123', 1, 0);

-- Company
INSERT INTO Company (company_id,company_name)
VALUES
	(1000,'Hersheys'),
    (1001,'Ferrero Rocher'),
    (1002,'Nestle'),
    (1003,'MrBeast Feastables'),
    (2000,'Krispy Kreme'),
    (2001,'Emys'),
    (2002,'Hollands Pies'),
    (2003,'Laduree'),
    (3000,'Eileens'),
    (3001,'Contis'),
    (3002,'Goldilocks'),
    (3003,'Collin Street Bakery'),
    (4000,'Haribo'),
    (4001,'Trolli'),
    (4002,'Hubbabubba'),
    (4003,'Espeez Candy');

-- Category    
INSERT INTO Category (category_id,category_name)
VALUES
	(1,'Chocolate'),
    (2,'Pastry'),
    (3,'Cake'),
    (4,'Candy');

-- Item
INSERT INTO Item (item_id, company_id, category_id, item_desc,item_name,item_price,item_stock)
VALUES
	(100, 1000, 1, 'Conical Shaped Chocolate, sold in 25pc. pack','Kisses', 225, 10),
    (101, 1001, 1, 'Almond Encased Chocolate, sold in 10pc. pack','Ferrero Rocher', 300, 10),
    (102, 1002, 1, 'Simple Chocolate Bar','Crunch', 95, 10),
    (103, 1003, 1, 'Snack Bar From Famous Youtuber MrBeast','Feastables', 150, 10),
    (200, 2000, 2, 'Circular Shaped Dough','Doughnut', 125, 10),
    (201, 2001, 2, 'Pastry Base with Filling, sold in 4pc. pack', 'Tarts', 160, 10),
    (202, 2002, 2, 'Packaged Pie', 'Pie', 350, 10),
    (203, 2003, 2, 'Small, Colorful Cookie, sold in 10pc. pack', 'Macaroon', 350, 10),
    (300, 3000, 3, 'Very Cheesy','Cheese Cake', 675, 10),
    (301, 3001, 3, 'Sweet and Creamy', 'Blueberry Cake', 750, 10),
    (302, 3002, 3, 'A Light Cake','Sponge Cake', 450, 10),
    (303, 3003, 3, 'Made with Dried Fruits', 'Fruit Cake', 500, 10),
    (400, 4000, 4, 'Bear Shaped Gum, sold in 30pc. pack', 'Gummy Bear', 150, 10),
    (401, 4001, 4, 'Strip of Sugar, sold in 8pc. pack', 'Candy Strip', 60, 10),
    (402, 4002, 4, 'Chewing Gum, sold in 15pc. pack', 'Bubblegum', 75, 10),
    (403, 4003, 4, 'Large Sugar Crystals, sold in 20pc. pack', 'Rock Candy', 250, 10);

-- Chocolate
INSERT INTO Chocolate (chocolate_item_id)
VALUES
	(100),
    (101),
    (102),
    (103);

-- Pastry
INSERT INTO Pastry (pastry_item_id)
VALUES
	(200),
    (201),
    (202),
    (203);

-- Cake
INSERT INTO Cake (cake_item_id)
VALUES
	(300),
	(301),
    (302),
    (303);
    
-- Candy
INSERT INTO Candy (candy_item_id)
VALUES
	(400),
    (401),
    (402),
    (403);

-- Set
INSERT INTO `Set` (set_ID, chocolate_item_id, pastry_item_id, cake_item_id, candy_item_id, set_price)
VALUES
	(10000,100,200,300,400,750),
    (10001,101,201,301,401,800),
    (10002,102,202,302,402,650),
    (10003,103,203,303,403,900);

-- Catalog
INSERT INTO Catalog (catalog_ID,set_id)
VALUES
	(22000,10000),
    (22001,10001),
    (22002,10002),
    (22003,10003);
-- Cart
INSERT INTO Cart (user_ID,quantity,catalog_id)
VALUES
	(111200, 2, 22000),
    (111201, 3, 22001), 
    (111202, 1, 22002),
    (111203, 4, 22003),
    (111204, 3, 22003);

-- Catalog
INSERT INTO item_list (catalog_ID,item_id)
VALUES
	(22000,100),
    (22001,101),
    (22002,102),
    (22003,103);
