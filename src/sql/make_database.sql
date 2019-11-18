/* 
 * MySQL script that creates the tables for the 
 * Users, Orders, Product, and ShoppingBasket relations.
 */

CREATE DATABASE ecommerce;
USE ecommerce;

/* database tables */
CREATE TABLE Users (userID INT PRIMARY KEY NOT NULL AUTO_INCREMENT, username VARCHAR(40) NOT NULL, password VARCHAR(40) NOT NULL, address VARCHAR(80) NOT NULL, isStaff BOOLEAN NOT NULL, isManager BOOLEAN NOT NULL); 
CREATE TABLE Products (productID INT PRIMARY KEY NOT NULL AUTO_INCREMENT, name VARCHAR(40) NOT NULL, price FLOAT NOT NULL, inventory INT NOT NULL, category VARCHAR(30) NOT NULL);
CREATE TABLE Orders (orderID INT PRIMARY KEY NOT NULL AUTO_INCREMENT, userID INT NOT NULL, prodID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES Users(userID), FOREIGN KEY (prodID) REFERENCES Products(productID),
    status VARCHAR(30) NOT NULL, money_saved FLOAT NOT NULL, isCancelled BOOLEAN NOT NULL);
CREATE TABLE ShoppingBasket (userID INT NOT NULL, prodID INT NOT NULL, 
    FOREIGN KEY (userID) REFERENCES Users(userID), FOREIGN KEY (prodID) REFERENCES Products(productID));

/* Create initial users */
INSERT INTO Users (username, password, isStaff, isManager) VALUES ('staff', 'password', TRUE, FALSE);
INSERT INTO Users (username, password, isStaff, isManager) VALUES ('manager', 'password', TRUE, TRUE);
INSERT INTO Users (username, password, isStaff, isManager) VALUES ('user', 'password', FALSE, FALSE);   

/*
Products sample data:
    - categories: action figures, bikes, dolls, games, outdoors
 */
INSERT INTO Products (name, price, inventory, category) VALUES ('Yoda', 34.99, 25, 'action figures');
INSERT INTO Products (name, price, inventory, category) VALUES ('Spiderman', 8.75, 'action figures');
INSERT INTO Products (name, price, inventory, category) VALUES ('Superman', 4.47, 'action figures');
INSERT INTO Products (name, price, inventory, category) VALUES ('Batman', 4.47, 'action figures');
INSERT INTO Products (name, price, inventory, category) VALUES ('Transformer Optimus Prime', 30.49, 'action figures');
INSERT INTO Products (name, price, inventory, category) VALUES ('GI Joe', 15.99, 'action figures');

INSERT INTO Products (name, price, inventory, category) VALUES ('LOL Suprise - Pink Girls', 89.00, 'bikes');
INSERT INTO Products (name, price, inventory, category) VALUES ('Transformer Bumblebee - Yellow Boys', 124.99, 'bikes');
INSERT INTO Products (name, price, inventory, category) VALUES ('Hot Wheels - Red/Silver Unisex', 98.99, 'bikes');
INSERT INTO Products (name, price, inventory, category) VALUES ('Disney Princess - Purple Girls', 94.99, 'bikes');
INSERT INTO Products (name, price, inventory, category) VALUES ('Disney Frozen - Blue Girls', 89.00, 'bikes');
INSERT INTO Products (name, price, inventory, category) VALUES ('Trolls - Green/Pink Unisex', 74.99, 'bikes');

INSERT INTO Products (name, price, inventory, category) VALUES ('Dora', 25.99, 'dolls');
INSERT INTO Products (name, price, inventory, category) VALUES ('Barbie', 17.99, 'dolls');
INSERT INTO Products (name, price, inventory, category) VALUES ('Bratz', 20.24, 'dolls');
INSERT INTO Products (name, price, inventory, category) VALUES ('Ken', 19.29, 'dolls');
INSERT INTO Products (name, price, inventory, category) VALUES ('American Girl', 98.00, 'dolls');
INSERT INTO Products (name, price, inventory, category) VALUES ('Cinderella', 35.90, 'dolls');

INSERT INTO Products (name, price, inventory, category) VALUES ('Pictionary', 16.99, 'games');
INSERT INTO Products (name, price, inventory, category) VALUES ('Monopoly', 12.59, 'games');
INSERT INTO Products (name, price, inventory, category) VALUES ('Candyland', 5.99, 'games');
INSERT INTO Products (name, price, inventory, category) VALUES ('Game of Life', 17.99, 'games');
INSERT INTO Products (name, price, inventory, category) VALUES ('Hungry Hungry Hippo', 9.89, 'games');
INSERT INTO Products (name, price, inventory, category) VALUES ('Trouble', 6.99, 'games');

INSERT INTO Products (name, price, inventory, category) VALUES ('Nerf Elite', 9.79, 'outdoors');
INSERT INTO Products (name, price, inventory, category) VALUES ('Pedal Car', 79.99, 'outdoors');
INSERT INTO Products (name, price, inventory, category) VALUES ('Walkie Talkies', 29.99, 'outdoors');
INSERT INTO Products (name, price, inventory, category) VALUES ('Mini Trampoline', 63.00, 'outdoors');
INSERT INTO Products (name, price, inventory, category) VALUES ('Scooter', 39.99, 'outdoors');
INSERT INTO Products (name, price, inventory, category) VALUES ('Outdoor Playset', 1399.99, 'outdoors');
