/* 
 * MySQL script that creates the tables for the 
 * Users, Orders, Product, and ShoppingBasket relations.
 */

CREATE DATABASE ecommerce;
USE ecommerce;

/* database tables */
CREATE TABLE Users (userID INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    username VARCHAR(40) NOT NULL, password VARCHAR(40) NOT NULL, 
    firstname VARCHAR(40) NOT NULL, lastname VARCHAR(40) NOT NULL, 
    address VARCHAR(120), isStaff BOOLEAN NOT NULL, isManager BOOLEAN NOT NULL); 
CREATE TABLE Products (productID INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    name VARCHAR(40) NOT NULL, price DECIMAL(10,2) NOT NULL, inventory INT NOT NULL, 
    category VARCHAR(30) NOT NULL, promotions INT NOT NULL);
CREATE TABLE Orders (orderID INT NOT NULL, userID INT NOT NULL, prodID INT NOT NULL, 
    quantity INT NOT NULL, FOREIGN KEY (userID) REFERENCES Users(userID), 
    FOREIGN KEY (prodID) REFERENCES Products(productID), status VARCHAR(30) NOT NULL, 
    money_saved FLOAT NOT NULL, order_datetime DATETIME NOT NULL);
CREATE TABLE ShoppingBasket (userID INT NOT NULL, prodID INT NOT NULL, 
    quantity INT NOT NULL, FOREIGN KEY (userID) REFERENCES Users(userID), 
    FOREIGN KEY (prodID) REFERENCES Products(productID));

/* Create initial users */
INSERT INTO Users (username, password, firstname, lastname, address, isStaff, isManager) VALUES ('staff', 'password', 'Staff', 'Personnel', NULL, TRUE, FALSE);
INSERT INTO Users (username, password, firstname, lastname, address, isStaff, isManager) VALUES ('manager', 'password', 'Store', 'Manager', NULL, FALSE, TRUE);
INSERT INTO Users (username, password, firstname, lastname, address, isStaff, isManager) VALUES ('user', 'password', 'Test', 'User', '329 Rose St., Lexington, KY USA 40508' ,FALSE, FALSE); 
/* professor access */
INSERT INTO Users (username, password, firstname, lastname, address, isStaff, isManager) VALUES ('cs405', 'cs405', 'Dr.', 'Liu', '329 Rose St., Lexington, KY USA 40508', FALSE, FALSE); 
INSERT INTO Users (username, password, firstname, lastname, address, isStaff, isManager) VALUES ('cs405m', 'cs405m', 'Dr.', 'Liu', '329 Rose St., Lexington, KY USA 40508', FALSE, TRUE); 

/*
Products sample data:
    - categories: action figures, bikes, dolls, games, outdoors
 */
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Yoda', 34.99, 25, 'action figures', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Spiderman', 8.75, 12, 'action figures', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Superman', 4.47, 36, 'action figures', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Batman', 4.47, 102, 'action figures', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Transformer Optimus Prime', 30.49, 5, 'action figures', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('GI Joe', 15.99, 44, 'action figures', 0);

INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('LOL Suprise - Pink Girls', 89.00, 10, 'bikes', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Transformer Bumblebee - Yellow Boys', 124.99, 8, 'bikes', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Hot Wheels - Red/Silver Unisex', 98.99, 22, 'bikes', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Disney Princess - Purple Girls', 94.99, 13, 'bikes', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Disney Frozen - Blue Girls', 89.00, 50, 'bikes', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Trolls - Green/Pink Unisex', 74.99, 37, 'bikes', 0);

INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Dora', 25.99, 1, 'dolls', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Barbie', 17.99, 4, 'dolls', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Bratz', 20.24, 19, 'dolls', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Ken', 19.29, 28, 'dolls', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('American Girl', 98.00, 76, 'dolls', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Cinderella', 35.90, 62, 'dolls', 0);

INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Pictionary', 16.99, 3, 'games', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Monopoly', 12.59, 27, 'games', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Candyland', 5.99, 11, 'games', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Game of Life', 17.99, 21, 'games', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Hungry Hungry Hippo', 9.89, 16, 'games', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Trouble', 6.99, 30, 'games', 0);

INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Nerf Elite', 9.79, 56, 'outdoors', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Pedal Car', 79.99, 9, 'outdoors', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Walkie Talkies', 29.99, 20, 'outdoors', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Mini Trampoline', 63.00, 6, 'outdoors', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Scooter', 39.99, 14, 'outdoors', 0);
INSERT INTO Products (name, price, inventory, category, promotions) VALUES ('Outdoor Playset', 1399.99, 2, 'outdoors', 0);

/* Sample orders to test customer order page */
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773477, 3, 22, 1, 'Pending', 0, '2019-11-30 13:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773477, 3, 23, 20, 'Pending', 0, '2019-11-30 13:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773477, 3, 24, 40, 'Pending', 0, '2019-11-30 13:00:00');

INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773477, 3, 1, 1, 'Pending', 0, '2019-10-31 13:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773477, 3, 2, 2, 'Pending', 0, '2019-10-31 13:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773477, 3, 3, 3, 'Pending', 0, '2019-10-31 13:00:00');

INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773478, 3, 4, 4, 'Shipped', 0, '2019-10-31 12:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773478, 3, 5, 5, 'Shipped', 0, '2019-10-31 12:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773478, 3, 6, 6, 'Shipped', 0, '2019-10-31 12:00:00');

INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773479, 3, 7, 7, 'Canceled', 0, '2019-10-31 11:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773479, 3, 8, 8, 'Canceled', 0, '2019-10-31 11:00:00');
INSERT INTO Orders (orderID, userID, prodID, quantity, status, money_saved, order_datetime) VALUES (1421773479, 3, 9, 9, 'Canceled', 0, '2019-10-31 11:00:00');
