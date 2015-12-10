SELECT OrderName,
       GROUP_CONCAT(O.TesterName, " ", O.OrganizationName SEPARATOR ", ") AS TesterName
FROM
  (SELECT Orders.id AS ID,
          Orders.Name AS OrderName,
          Testers.ID AS TesterID,
          GROUP_CONCAT(Testers.LastName, " ", Testers.FirstName SEPARATOR ", ") AS TesterName,
          CONCAT("(", Organizations.Name, ")") AS OrganizationName,
          Organizations.ID AS OrganizationID
   FROM Orders
   LEFT JOIN Order_Tester ON Orders.ID = Order_Tester.OrderID
   LEFT JOIN Testers ON Testers.ID = Order_Tester.TesterID
   LEFT JOIN Organizations ON Organizations.ID = Testers.OrganizationID
   GROUP BY Orders.ID,
            Organizations.id
   ORDER BY Organizations.Name) AS O
GROUP BY O.ID