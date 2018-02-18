CREATE DATABASE IF NOT EXISTS `footywebsite` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `footywebsite`;


DROP TABLE IF EXISTS `PREMIERSHIP`;
DROP TABLE IF EXISTS `Product_Order`;
DROP TABLE IF EXISTS `Sales_Order`;
DROP TABLE IF EXISTS `CUSTOMER`;
DROP TABLE IF EXISTS `Store`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `GoogleAuth`;

CREATE TABLE CUSTOMER(
	`CUSTOMERID` INTEGER AUTO_INCREMENT, 
	`USERNAME` VARCHAR(20) NOT NULL UNIQUE,
	`PASSWORD` VARCHAR(20) NOT NULL,
	`LASTNAME` VARCHAR(30) NOT NULL,
	`FIRSTNAME` Varchar(30) NOT NULL,
	`AddressLine1` VARCHAR(30) NOT NULL,
	`AddressLine2` VARCHAR(30) default NULL ,
	`CITY` VARCHAR(25) NOT NULL,
	`POSTALCODE` VARCHAR(10) default NULL,
	`PHONE` VARCHAR(15) NOT NULL,
	`user_Type` BOOLEAN NOT NULL,
	`googleAuth` BOOLEAN NOT NULL,
	PRIMARY KEY  (`CUSTOMERID`)
)
;

INSERT INTO CUSTOMER (USERNAME, PASSWORD, LASTNAME, FIRSTNAME, AddressLine1,AddressLine2,CITY,POSTALCODE,PHONE,user_Type,googleAuth) 
VALUES ('Test User', 'password1', 'Test1', 'Test', '100 Test Rd', 'Testing', 'Test', 'TES4453','3',true,false);
INSERT INTO CUSTOMER (USERNAME, PASSWORD, LASTNAME, FIRSTNAME, AddressLine1,AddressLine2,CITY,POSTALCODE,PHONE,user_Type,googleAuth)
VALUES ('paddy1', 'pad1', 'Smith', 'Paddy', '46 Dundalk Rd', 'Hoeys Lane', 'Dundalk', '000023','56546353',false,false);
INSERT INTO CUSTOMER (USERNAME, PASSWORD, LASTNAME, FIRSTNAME, AddressLine1,AddressLine2,CITY,POSTALCODE,PHONE,user_Type,googleAuth)
VALUES ('foot10', 'football', 'Hughes', 'Mary', '78 mowhan Rd', 'Antrim', 'Antrim', 'bt65 8ep','656456',false,false);


CREATE TABLE PREMIERSHIP(
	`TEAMID` INTEGER AUTO_INCREMENT, 
	`POSITION` INTEGER,
	`TEAMNAME` VARCHAR(20) NOT NULL UNIQUE,
	`GAMESPLAYED` INTEGER NOT NULL,
	`GOALSFOR` INTEGER NOT NULL,
	`GOALSAGAINST` INTEGER NOT NULL,
	`GOALDIFFERENCE` INTEGER NOT NULL,
	`POINTS` INTEGER NOT NULL,
	PRIMARY KEY  (`TEAMID`)
);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME,GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE,POINTS)
VALUES (1,1,'Arsenal ',8,18,9,9,19);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (2,2,'Chelsea ',8,14,5,9,17);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (3,3,'Liverpool ',8,13,7,6,17);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (4,4,'Man City  ',8,20,9,11,16);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (5,5,'Tottenham',8,8,5,3,16);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (6,6,'Southampton',8,8,3,5,15);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (7,7,'Everton',8,12,10,2,15);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (8,8,'Man Utd',8,11,10,1,11);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (9,9,'Hull',8,7,9,-2,11);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (10,10,'Newcastle',8,11,14,-3,11);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (11,11,'Swansea',8,12,11,1,10);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (12,12,'West Brom',8,7,6,1,10);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (13,13,'Aston Villa',8,9,10,-1,10);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (14,14,'West Ham',8,8,8,0,8);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (15,15,'Stoke',8,4,7,-3,8);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (16,16,'Cardiff',8,8,13,-5,8);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (17,17,'Fulham',8,5,9,-4,7);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (18,18,'Norwich',8,6,13,-7,7);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (19,19,'Crystal Palace',8,5,13,-8,3);
INSERT INTO PREMIERSHIP(TEAMID, POSITION, TEAMNAME, GAMESPLAYED, GOALSFOR, GOALSAGAINST, GOALDIFFERENCE, POINTS)
VALUES (20,20,'Sunderland',8,5,20,-15,1);

CREATE TABLE Store (
	`ItemID` INTEGER AUTO_INCREMENT, 
	`ItemName` VARCHAR(20) NOT NULL,
	`ItemType` VARCHAR(20) NOT NULL,
	`Quantity` INTEGER NOT NULL,
	`Price` Decimal(4,2) NOT NULL,
	`ItemImage` VARCHAR(50),
	PRIMARY KEY  (`ItemID`)
);

INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (1,"Addidas Football","Football",1000,5.99,"images/football.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (2,"Man Utd Shirt Home","Jersey",100,29.99,"images/manutdHomeShirt.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (3,"Man City Shirt Home","Jersey",20,29.99,"images/CityHomeJersey.jpg");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (4,"Everton Hat","clothing",30,9.99,"images/evertonHat.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (5,"Football boots","shoes",20,19.99,"images/footballBoots.jpg");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (6,"Man Utd Shirt Away","Jersey",400,39.99,"images/manutdAwayShirt.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (7,"Man City Shirt Away","Jersey",200,39.99,"images/mancityAwayShirt.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (8,"Arsenal Away Shirt","Jersey",200,39.99,"images/arsenalShirtAway.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (9,"Arsenal Home Shirt","Jersey",300,39.99,"images/arsenalShirtHome.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (10,"Everton Home Shirt","Jersey",300,35.99,"images/evertonShirtHome.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (11,"Liverpool Home Shirt","Jersey",500,39.99,"images/liverpoolHomeShirt.png");
INSERT INTO Store (ItemID, ItemName, ItemType,Quantity, Price,ItemImage)
Values (12,"Red Football","Football",1000,9.99,"images/redFootball.png");

CREATE TABLE Sales_Order (
	`SalesID` INTEGER AUTO_INCREMENT, 
	`CUSTOMERID` INTEGER,
	`TotalItems` INTEGER,
	`Total` Double,
	PRIMARY KEY  (`SalesID`),
	CONSTRAINT PLAYED_BY FOREIGN KEY (CUSTOMERID) REFERENCES CUSTOMER (CUSTOMERID)
);
/*Testing Purposes */
INSERT INTO Sales_Order(SalesID,CUSTOMERID,TotalItems,Total)
Values(1,1,4,119.96);
INSERT INTO Sales_Order(SalesID,CUSTOMERID,TotalItems,Total)
Values(2,1,3,89.97);

CREATE TABLE Product_Order (
	`ProductOrderID` INTEGER AUTO_INCREMENT, 
	`SalesID` INTEGER,
	`ItemID` INTEGER,
	`Amount` INTEGER,
	PRIMARY KEY  (`ProductOrderID`),
	CONSTRAINT Fk_SalesID FOREIGN KEY (SalesID) REFERENCES Sales_Order (SalesID),
	CONSTRAINT Fk_ItemID FOREIGN KEY (ItemID) REFERENCES Store (ItemID)
);	

/*Testing Purposes */
INSERT INTO Product_Order(ProductOrderID,SalesID,ItemID,Amount)
VALUES(1,1,3,4);
INSERT INTO Product_Order(ProductOrderID,SalesID,ItemID,Amount)
VALUES(2,2,2,3);

CREATE TABLE news
(
	`id` INTEGER NOT NULL AUTO_INCREMENT,
	`description` VARCHAR(128) NOT NULL,
	PRIMARY KEY(id)
);

CREATE TABLE GoogleAuth (
	`authID` INTEGER AUTO_INCREMENT, 
	`Username` VARCHAR(20) NOT NULL,
	`SecretKey` VARCHAR(20) NOT NULL,
	PRIMARY KEY(`authID`)
);

DROP TRIGGER IF EXISTS customer_news;
DELIMITER //
CREATE TRIGGER customer_news BEFORE INSERT ON CUSTOMER
FOR EACH ROW
BEGIN
	INSERT INTO news (description)
	VALUES
	(CONCAT("New customer has joined: ", NEW.USERNAME)); 
END //
DELIMITER ;

DROP TRIGGER IF EXISTS customer_news1;
DELIMITER //
CREATE TRIGGER customer_news1 After DELETE ON CUSTOMER
FOR EACH ROW
BEGIN
	INSERT INTO news (description)
	VALUES
	(CONCAT("Customer has deleted their account: ", OLD.USERNAME)); 
END //
DELIMITER ;

DROP TRIGGER IF EXISTS store_news;
DELIMITER //
CREATE TRIGGER store_news BEFORE INSERT ON Store
FOR EACH ROW
BEGIN
	INSERT INTO news (description)
	VALUES
	(CONCAT("New Item added to store: ", NEW.ItemName)); 
END //
DELIMITER ;

DROP TRIGGER IF EXISTS store_news1;
DELIMITER //
CREATE TRIGGER store_news1 After DELETE ON Store
FOR EACH ROW
BEGIN
	INSERT INTO news (description)
	VALUES
	(CONCAT("Item has been removed from store: ", OLD.ItemName)); 
END //
DELIMITER ;

DROP PROCEDURE IF EXISTS ADD_NEWS;
DELIMITER //
CREATE PROCEDURE ADD_NEWS
( 
	IN news1 VARCHAR(128)
)
BEGIN
	DECLARE news VARCHAR(128);
	
	INSERT INTO news (description) VALUES (news1); 
	
END //
DELIMITER ;

call ADD_NEWS("New customer has joined mmckee");
call ADD_NEWS("New customer has joined paddy1");
call ADD_NEWS("New customer has joined foot10");

DROP PROCEDURE IF EXISTS calculatediscount;

DELIMITER //
CREATE PROCEDURE calculatediscount
(
	IN percentage Double
)
BEGIN
	
	/* first declare local variables */
	DECLARE discount, thisPrice Double;
	DECLARE thisID Int;
	DECLARE done BOOLEAN ; 

	/* then declare cursors */ 
	
	DECLARE Cursor1 CURSOR FOR SELECT ItemID, Price FROM store; 

	/* finally declare handlers */ 

	DECLARE EXIT HANDLER
	FOR NOT FOUND ROLLBACK; 
	DECLARE CONTINUE HANDLER FOR 1054 ROLLBACK;
	START TRANSACTION; 
	BEGIN
		SET done = TRUE ; 
	END ; 
	SET done = FALSE ; 
	

	/* open the cursor */
	OPEN Cursor1 ; 
	WHILE NOT done
	do
	/* loop while not done (controlled by handler) */ 
	
		/* fetch the next row into the local variable */ 
		FETCH Cursor1 INTO thisID, thisPrice;
		

		if (percentage > 1 && percentage < 100)  then 
		SET discount = thisPrice/100 * percentage;
		update store set price = (price-discount) where ItemID = thisID;
		Commit;
		END IF;

	END WHILE;

END //
DELIMITER ;
