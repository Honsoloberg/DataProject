create database dataproject;
create user 'site'@'localhost' identified by 'password';
flush privileges;

GRANT SELECT, UPDATE, INSERT, DELETE ON dataproject.* to 'site'@'localhost';

use dataproject;

-- DROP TABLE O_Items;
-- DROP TABLE orders;
-- DROP TABLE users;
-- DROP TABLE items;
-- DROP TABLE restaurant;
-- DROP TABLE driver;

create table Users(
ID INT(6) NOT NULL,
Fname VARCHAR(50) NOT NULL,
Lname VARCHAR(50),
UserName VARCHAR(50) NOT NULL,
Upass VARCHAR(50) NOT NULL,
Funds DOUBLE(6, 2) NOT NULL,
Address VARCHAR(100) NOT NULL,
Birthday VARCHAR(10),
Email VARCHAR(100) NOT NULL,
PRIMARY KEY (ID)
);

CREATE TABLE Restaurant(
ID INT(6) NOT NULL,
Rname VARCHAR(50) NOT NULL,
Address VARCHAR(100) NOT NULL,
PRIMARY KEY (ID)
);

CREATE TABLE Driver(
ID INT(6) NOT NULL,
Fname VARCHAR(50) NOT NULL,
Lname VARCHAR(50) NOT NULL,
CarModel VARCHAR(75) NOT NULL,
Plate VARCHAR(6) NOT NULL,
Insurance VARCHAR(17) NOT NULL,
UserName VARCHAR(50) NOT NULL,
Dpass VARCHAR(50) NOT NULL,
PRIMARY KEY (ID)
);

CREATE TABLE Orders(
ID INT(10) NOT NULL,
TotalPrice Double(6,2) NOT NULL,
RID INT(6) NOT NULL,
DID INT(6) NOT NULL,
UID INT(6) NOT NULL,
PRIMARY KEY (ID),
FOREIGN KEY (DID) REFERENCES Driver(ID),
FOREIGN KEY (UID) REFERENCES Users(ID),
FOREIGN KEY (RID) REFERENCES Restaurant(ID)
);

CREATE TABLE Items(
ID INT(16) NOT NULL,
RID INT(6) NOT NULL,
Iname VARCHAR(50) NOT NULL,
Price DOUBLE(4,2) NOT NULL,
Decript VARCHAR(1000),
PRIMARY KEY (ID),
FOREIGN KEY (RID) REFERENCES Restaurant(ID)
);

CREATE TABLE O_Items(
O_ID INT (10) NOT NULL,
Item_ID INT(16) NOT NULL,
Quant INT(2) NOT NULL,
Comments VARCHAR(1000),
CONSTRAINT PK_O_Items PRIMARY KEY(O_ID, Item_ID),
FOREIGN KEY (O_ID) REFERENCES Orders(ID),
FOREIGN KEY (Item_ID) REFERENCES Items(ID)
);

INSERT INTO users (ID, Fname, Lname, UserName, Upass, Funds, Address, Birthday, Email)
VALUES
    (147018, 'Eren', 'Yeager', 'TheTitan', 'pass', 50, 'Oshawa', '01/01/2001', 'Erenyeager@gmail.com'),
    (333661, 'Light', 'Yagmi', 'Kira', 'pass', 50, 'Oshawa', '01/01/2002', 'Lightyagmi@gmail.com'),
    (994755, 'Naruto', 'Uzumaki', 'LordSeventh', 'pass', 50, 'Oshawa', '01/01/2003', 'narutouzumaki@gmail.com'),
    (828546, 'Minato', 'Namikaze', 'LordFourth', 'pass', 50, 'Oshawa', '01/01/2004', 'Minatonamikaze@gmail.com'),
    (199284, 'Sasuke', 'Uchiha', 'LastUchiha', 'pass', 50, 'Oshawa', '01/01/2005', 'Sasukeuchiha@gmail.com'),
    (826915, 'Hinata', 'Hyuga', 'LadySeventh', 'pass', 50, 'Oshawa', '01/01/2006', 'Hinatahyuga@gmail.com'),
    (745331, 'Ivo', 'Robotnik', 'Eggman', 'pass', 50, 'Oshawa', '01/01/2007', 'Ivorobotnik@gmail.com');

INSERT INTO restaurant (ID, Rname, Address)
VALUES
    (817024, 'McDonalds', 'Oshawa, 1349 Simcoe St. N'),
    (266291, 'Wendys', 'Oshawa, 1362 Harmony Rd. N'),
    (340492, 'Osmows', 'Oshawa, 1900 Simcoe St. N'),
    (426441, 'Tim Hortons', 'Oshawa, 3309 Simcoe St. N'),
    (733348, 'Mary Browns', 'Oshawa, 15 Taunton Rd. E'),
    (253617, 'Starbucks', 'Oshawa, 2670 Simcoe St. N');

INSERT INTO driver (ID, Fname, Lname, CarModel, Plate, Insurance, UserName, Dpass)
VALUES
    (94659, 'Jeff', 'Bezos', 'Lamborghini Aventador', 'ABC123', 'INS12345', 'JB', 'Pass'),
    (461103, 'Fred', 'Krugar', 'Toyota Corolla', 'NITMAR', 'POL9876', 'FK', 'Pass'),
    (911692, 'Abby', 'Smith', 'Honda Civic', 'DEF456', 'COV54321', 'AS', 'Pass'),
    (225821, 'Tom', 'Jerry', 'Volkswagen Beetle', 'GETAWY', 'INSUR4567', 'TJ', 'Pass'),
    (213804, 'Jack', 'Jill', 'Ford F150', 'WATERS', 'PLY99888', 'JJ', 'Pass'),
    (277934, 'Muhammed', 'Ali', 'Chevrolet Impala', 'LITOUT', 'SEC77777', 'MA', 'Pass'),
    (616636, 'Lilly', 'Potter', 'Dodge Caravan', 'POTTER', 'PROTECT1', 'LP', 'Pass');

INSERT INTO orders (ID, TotalPrice, RID, DID, UID)
VALUES
    (1, 10, 817024, 94659, 147018),
    (2, 10, 266291, 461103, 333661),
    (3, 10, 340492, 911692, 994755),
    (4, 10, 733348, 225821, 828546),
    (5, 20, 253617, 213804, 199284),
    (6, 2, 253617, 277934, 826915);

INSERT INTO Items (ID, RID, Iname, Price, Decript)
VALUES
    (569170, 817024, 'Big Mac', 10, 'Big Burger'),
    (183030, 817024, 'Jr. Chicken', 5, 'Small Sandwich'),
    (156056, 266291, 'Baconator', 10, 'Bacon Burger'),
    (269487, 266291, 'Chili', 5, 'Tasty Chili'),
    (590706, 340492, 'Lg Wrap', 10, 'Big Boi Wrap'),
    (794685, 340492, 'Bowl', 5, 'Big Bowl of salad'),
    (862625, 426441, 'Coffee', 2, 'Any Size Coffee'),
    (894913, 426441, 'Donut', 3, 'Perfectly Glazed donut'),
    (513738, 733348, 'Chicken Thighs', 10, 'Perfectly cooked S-Tier Chicken'),
    (318574, 733348, 'Potato Wedges', 5, 'Perfectly seasoned Potato wedges'),
    (589861, 253617, 'Latte', 20, 'Not a big cup of overpriced coffee'),
    (150454, 253617, 'Lollipop Donut', 2, 'Bite Size crumbs');

INSERT INTO O_Items (O_ID, Item_ID, Quant, Comments)
VALUES
    (1, 569170, 1, 'n/a'),
    (2, 156056, 1, 'n/a'),
    (3, 590706, 1, 'n/a'),
    (4, 513738, 1, 'n/a'),
    (5, 589861, 1, 'n/a'),
    (6, 150454, 1, 'n/a');
