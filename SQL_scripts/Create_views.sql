use dataproject;

select Comments, Iname, Price, Decript
from orders
join o_items on orders.ID = o_items.O_ID
join items on o_items.Item_ID = items.ID
where orders.ID >= 1;

SELECT id, iname, price, rid
FROM items AS i1
WHERE price > ANY (
    SELECT AVG(price)
    FROM items AS i2
    WHERE i1.rid = i2.rid
    GROUP BY i2.rid
);

SELECT o.ID, o.TotalPrice, o.RID
FROM Orders o
WHERE (
    SELECT AVG(i.Price)
    FROM Items i
    WHERE i.RID = o.RID
) < ANY (
    SELECT io.Price
    FROM O_Items oi
    JOIN Items io ON oi.Item_ID = io.ID
    WHERE oi.O_ID = o.ID
);

SELECT *
FROM Driver
LEFT JOIN Orders ON Driver.ID = Orders.DID
UNION
SELECT *
FROM Driver
RIGHT JOIN Orders ON Driver.ID = Orders.DID;

SELECT ID, Iname, Price
FROM Items
WHERE RID = 253617
UNION
SELECT ID, Iname, Price
FROM Items
WHERE RID = 266291;

SELECT Funds, Fname, Lname
FROM Users
WHERE ID = 147018;

SELECT Rname, Address
FROM Restaurant
WHERE ID = 253617; 

SELECT O_ID, COUNT(Item_ID) AS NumberOfItems
FROM O_Items
GROUP BY O_ID;

SELECT R.Rname AS RestaurantName, I.Iname AS MostExpensiveItem, I.Price AS ItemPrice
FROM (
    SELECT RID, MAX(Price) AS MaxPrice
    FROM Items
    GROUP BY RID
) MaxPrices
JOIN Items I ON MaxPrices.RID = I.RID AND MaxPrices.MaxPrice = I.Price
JOIN Restaurant R ON MaxPrices.RID = R.ID;

SELECT r.Rname AS RestaurantName, i.Iname AS ItemName, i.Price AS ItemPrice
FROM Restaurant r
INNER JOIN Items i ON r.ID = i.RID
WHERE i.Price = (
    SELECT MIN(Price)
    FROM Items
    WHERE RID = r.ID
);





