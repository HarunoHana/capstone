DROP TABLE IF EXISTS account CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS appointments CASCADE;
DROP TABLE IF EXISTS cart CASCADE;
DROP TABLE IF EXISTS cart_item CASCADE;
DROP TABLE IF EXISTS donation CASCADE;
DROP TABLE IF EXISTS ingredients CASCADE;
DROP TABLE IF EXISTS item CASCADE;
DROP TABLE IF EXISTS links CASCADE;
DROP TABLE IF EXISTS nutrition CASCADE;
DROP TABLE IF EXISTS order_details CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS recipe CASCADE;
DROP TABLE IF EXISTS schedule CASCADE;
DROP TABLE IF EXISTS user CASCADE;
DROP TABLE IF EXISTS user_stu CASCADE;


-- CREATE TABLE account (
--    account_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    user_id INT(11),
--    info_text VARCHAR(255) NOT NULL,
--    register_date VARCHAR(10) NOT NULL,
--    role VARCHAR(255) NOT NULL,
--    INDEX user_id (user_id)
-- );


-- CREATE TABLE admin (
--    admin_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    name VARCHAR(255) NOT NULL,
--    role VARCHAR(255) NOT NULL,
--    email VARCHAR(255) NOT NULL
-- );


CREATE TABLE appointments (
   app_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   date VARCHAR(10) NOT NULL,
   time VARCHAR(5) NOT NULL,
   status VARCHAR(255) NOT NULL,
   user_id INT(11),
   INDEX user_id (user_id)
);


CREATE TABLE cart (
   cart_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   user_id INT(11),
   INDEX user_id (user_id)
);


CREATE TABLE cart_item (
   cartItem_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   cart_id INT(11),
   item_id INT(11),
   item_quantity INT(11),
   INDEX cart_id (cart_id),
   INDEX item_id (item_id)
);


CREATE TABLE donation (
   date DATE,
   time TIME,
   item VARCHAR(255),
   quantity INT(11),
   confirmation_num INT(11)
);


-- CREATE TABLE ingredients (
--    ingredient_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    expiration_date VARCHAR(10) NOT NULL,
--    item_id INT(11),
--    item_quantity INT(11),
--    item_category VARCHAR(20) NOT NULL,
--    INDEX item_id (item_id)
-- );


CREATE TABLE item (
   item_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   item_name VARCHAR(255) NOT NULL,
   item_quantity INT(11) NOT NULL,
   item_category VARCHAR(20) NOT NULL,
   nutrition_id INT(11),
   item_image VARCHAR(255),
   INDEX nutrition_id (nutrition_id)
);


-- CREATE TABLE items (
--    item_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    item_name VARCHAR(30) NOT NULL,
--    item_category VARCHAR(30) NOT NULL,
--    item_quantity INT(11) NOT NULL,
--    item_image VARCHAR(100)
-- );


-- CREATE TABLE links (
--    schedule_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    date VARCHAR(10),
--    time VARCHAR(5),
--    type VARCHAR(255),
--    status VARCHAR(255),
--    user_id INT(11),
--    INDEX user_id (user_id)
-- );


-- CREATE TABLE nutrition (
--    nutrition_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    calories INT(11),
--    nutrients VARCHAR(255),
--    portion_size VARCHAR(255),
--    allergens VARCHAR(255)
-- );


CREATE TABLE order_details (
   order_detail_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   order_id INT(11),
   item_id INT(11),
   quantity INT(11),
   INDEX order_id (order_id),
   INDEX item_id (item_id)
);


CREATE TABLE orders (
   order_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   cart_id INT(11),
   user_id INT(11),
   is_completed TINYINT(1) DEFAULT 0,
   order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
   INDEX cart_id (cart_id),
   INDEX user_id (user_id)
);


-- CREATE TABLE recipe (
--    recipe_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    meal_name VARCHAR(255),
--    cooking_inst VARCHAR(255)
-- );


-- CREATE TABLE schedule (
--    schedule_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    user_id INT(11),
--    text VARCHAR(255) NOT NULL,
--    url VARCHAR(255) NOT NULL,
--    INDEX user_id (user_id)
-- );


CREATE TABLE user (
   user_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(255) NOT NULL,
   IU_id VARCHAR(10) NOT NULL,
   email VARCHAR(255) UNIQUE NOT NULL,
   password VARCHAR(255) NOT NULL,
   UNIQUE (email)
);


-- CREATE TABLE user_stu (
--    user_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
--    name VARCHAR(255) NOT NULL,
--    email VARCHAR(255) NOT NULL,
--    IU_id VARCHAR(10) NOT NULL
-- );

INSERT INTO appointments (date, time, status, user_id) VALUES 
('2024-04-30', '10:00', 'Confirmed', 2),
('2024-04-21', '15:00', 'Pending', 1),
('2024-05-01', '09:00', 'Confirmed', 3);

INSERT INTO cart (user_id) VALUES 
(1),
(2);

INSERT INTO cart_item (cart_id, item_id, item_quantity) VALUES 
(1, 1, 3),
(2, 2, 2);

INSERT INTO donation (date, time, item, quantity) VALUES
('2024-02-10', '14:30', 'Egg', 40);

INSERT INTO item (item_name, item_quantity, item_category, item_image) VALUES 
('Banana', 150, 'Fruit', 'images/banana.jpg'),
('Grape', 10, 'Fruit', 'images/grapes.jpg'),
('Pear', 10, 'Fruit', 'images/pear.jpg'),
('Butter', 10, 'Dairy', 'images/butter.jpg'),
('Carrot', 10, 'Vegetable', 'images/carrot.jpeg'),
('Cereal Bars', 10, 'Snack', 'images/cerealBars.jpg'),
('Bacon', 10, 'Meats', 'images/bacon.jpg'),
('Bread', 50, 'Grain', 'images/bread.jpg');

INSERT INTO user (name, IU_id, email, password) VALUES 
('testUser', 'test', 'test@iu.edu', 'test'),
('Admin', 'Admin', 'admin@iu.edu', 'Admin');

INSERT INTO orders (cart_id, user_id, is_completed) VALUES
(1,1,1);






