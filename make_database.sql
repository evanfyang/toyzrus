/* 
 * MySQL script that creates the tables for the 
 * Users, Orders, Product, and ShoppingBasket relations.
 */

CREATE DATABASE ecommerce;
USE ecommerce;

CREATE TABLE Users (userID INT PRIMARY KEY NOT NULL, username VARCHAR(30), password VARCHAR(30), isStaff BOOL, isManager BOOL); 
CREATE TABLE Products (productID INT PRIMARY KEY NOT NULL, price FLOAT, inventory INT, category VARCHAR(30));
CREATE TABLE Orders (orderID INT PRIMARY KEY NOT NULL, 
    FOREIGN KEY fk(cID) REFERENCES Users(userID), FOREIGN KEY fk(pID) REFERENCES Products(productID),
    status VARCHAR(30), money_saved FLOAT, isCancelled BOOL);
CREATE TABLE ShoppingBasket (FOREIGN KEY fk(cID) REFERENCES Users(userID), FOREIGN KEY fk(pID) REFERENCES Products(productID));
