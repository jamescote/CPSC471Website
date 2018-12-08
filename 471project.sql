SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS cpsc471 ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS cpsc471 DEFAULT CHARACTER SET utf8 ;
USE cpsc471;

-- -----------------------------------------------------
-- Table cpsc471.Credit_Card
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Credit_Card (
  CCID 				INT 		NOT NULL AUTO_INCREMENT,
  CCType 			VARCHAR(45) NOT NULL,
  CCName 			VARCHAR(45) NOT NULL,
  CCSecurityCode 	INT 		NOT NULL,
  CCNumber 			CHAR(10)	NOT NULL,
  CCMonth 			INT 		NOT NULL,
  CCYear 			INT 		NOT NULL,
  PRIMARY KEY (CCID),
  UNIQUE(CCNumber));
 INSERT INTO cpsc471.Credit_Card (CCID, CCType, CCName, CCSecurityCode, CCNumber, CCMonth, CCYear) VALUES
 (1, 'Visa', 'James C Cote', '123', '1234567890', '09', '21'),
 (2, 'MasterCard', 'Big Bob', '124', '1111111111', '09', '22'),
 (3, 'AMEX', 'Mr. Expired', '113', '2222222222', '09', '17');


-- -----------------------------------------------------
-- Table cpsc471.Fan
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Fan (
  FanID INT NOT NULL AUTO_INCREMENT,
  FLogin VARCHAR(45) NOT NULL,
  FPassword VARCHAR(45) NOT NULL,
  FName VARCHAR(45) NOT NULL,
  FBirthDate DATE NOT NULL,
  PRIMARY KEY (FanID),
  UNIQUE(FLogin));
INSERT INTO cpsc471.Fan (FLogin, FPassword, FName, FBirthDate) VALUES
('ADMIN', 'ADMIN', 'James Coté', '1989-06-14');


-- -----------------------------------------------------
-- Table cpsc471.Payment_Info
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Payment_Info (
  CCID INT NOT NULL,
  FanID INT NOT NULL,
  StreetNum INT NOT NULL,
  StreetName VARCHAR(45) NOT NULL,
  City VARCHAR(45) NOT NULL,
  Province VARCHAR(45) NOT NULL,
  PRIMARY KEY (CCID),
  CONSTRAINT PICCID
    FOREIGN KEY (CCID)
    REFERENCES cpsc471.Credit_Card (CCID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT PIFanID
    FOREIGN KEY (FanID)
    REFERENCES cpsc471.Fan (FanID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
INSERT INTO cpsc471.Payment_Info (CCID, FanID, StreetNum, StreetName, City, Province) VALUES
(1, 1, 45, 'Boulevarde of Broken Dreams', 'Fresco', 'Nunavut'),
(2, 1, 123, '123 ST NW', 'Vancouver', 'British Columbia'),
(3, 1, 556, 'HollowPoint Cres', 'Mason', 'Masonia');


-- -----------------------------------------------------
-- Table cpsc471.Promoter
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Promoter (
  PromoterID INT NOT NULL AUTO_INCREMENT,
  Name VARCHAR(45) NOT NULL,
  Login VARCHAR(45) NOT NULL,
  Password VARCHAR(45) NOT NULL,
  Description VARCHAR(140) NOT NULL,
  PromoterType VARCHAR(45) NOT NULL,
  PRIMARY KEY (PromoterID),
  UNIQUE (Login),
  UNIQUE (Name));
INSERT INTO cpsc471.Promoter (Name, Login, Password, Description, PromoterType) VALUES
('Baseline', 'BASELINE', 'BASELINE', 'The Progenitor of all things Rock and or Roll!', 'Artist');


-- -----------------------------------------------------
-- Table cpsc471.Followed_By
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Followed_By (
  FanID INT NOT NULL,
  PromoterID INT NOT NULL,
  PRIMARY KEY (FanID, PromoterID),
  CONSTRAINT FBFanID
    FOREIGN KEY (FanID)
    REFERENCES cpsc471.Fan (FanID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT FBPromoterID
    FOREIGN KEY (PromoterID)
    REFERENCES cpsc471.Promoter (PromoterID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table cpsc471.Sale
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Sale (
  SaleID INT NOT NULL AUTO_INCREMENT,
  FanID INT NOT NULL,
  DollarAmount DECIMAL(10,2) NOT NULL,
  SaleDate DATE NOT NULL,
  PRIMARY KEY (SaleID),
  CONSTRAINT SaleFanID
    FOREIGN KEY (FanID)
    REFERENCES cpsc471.Fan (FanID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
INSERT INTO cpsc471.Sale (FanID, DollarAmount, SaleDate) VALUE
(1, 2049.33, NOW());


-- -----------------------------------------------------
-- Table cpsc471.Series
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Series (
  SeriesID INT NOT NULL AUTO_INCREMENT,
  PromoterID INT NOT NULL,
  Description VARCHAR(140) NOT NULL,
  NumEvents INT NOT NULL,
  Name VARCHAR(45) NOT NULL,
  StartEventID INT NOT NULL,
  EndEventID INT NOT NULL,
  NumTicketsRemaining INT NOT NULL,
  TicketPrice DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (SeriesID),
  UNIQUE (Name),
  CONSTRAINT SeriesPromoterID
    FOREIGN KEY (PromoterID)
    REFERENCES cpsc471.Promoter (PromoterID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT SeriesStartEvent
    FOREIGN KEY (StartEventID)
    REFERENCES cpsc471.Event (EventID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
   CONSTRAINT SeriesEndEvent
    FOREIGN KEY (EndEventID)
    REFERENCES cpsc471.Event (EventID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
INSERT INTO cpsc471.Series (PromoterID, Description, NumEvents, Name, StartEventID, EndEventID, NumTicketsRemaining, TicketPrice) VALUES
(1, 'The Series to end all Serieses', 25, 'The Series', 1, 2, 500, 2049.33);


-- -----------------------------------------------------
-- Table cpsc471.Event
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Event (
  EventID INT NOT NULL AUTO_INCREMENT,
  SeriesID INT,
  PromoterID INT NOT NULL,
  Name VARCHAR(45) NOT NULL,
  EventTimestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  Description VARCHAR(140) NOT NULL,
  Duration INT NOT NULL,
  NumTicketsRemaining INT NOT NULL,
  TicketPrice DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (EventID),
  UNIQUE (Name),
  CONSTRAINT EventPromoterID
    FOREIGN KEY (PromoterID)
    REFERENCES cpsc471.Promoter (PromoterID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT EventSeriesID
    FOREIGN KEY (SeriesID)
    REFERENCES cpsc471.Series (SeriesID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
INSERT INTO cpsc471.Event (SeriesID, PromoterID, Name, EventTimestamp, Description, Duration, NumTicketsRemaining, TicketPrice) VALUES
(NULL, 1, 'Event 1', '2019-01-01 17:00', 'The coolest Event this side of mount olympus, rocking the socks off err-body!', 60, 100, 49.99),
(NULL, 1, 'Event 2', '2019-11-01 17:00', 'The coolest Event FFFFFFF this side of mount olympus, rocking the socks off err-body!', 60, 0, 49.99),
(NULL, 1, 'Event 3', '2020-01-01 17:00', 'The coolest Event GGGGGGG this side of mount olympus, rocking the socks off err-body!', 60, 100, 49.99),
(NULL, 1, 'Event 4', '2018-12-15 17:00', 'The coolest Event ZZZZZZZ this side of mount olympus, rocking the socks off err-body!', 60, 3, 49.99);

-- -----------------------------------------------------
-- Table cpsc471.Ticket
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Ticket (
  TicketNumber INT NOT NULL AUTO_INCREMENT,
  EventID INT CHECK( SeriesOrEvent = FALSE ),
  SeriesID INT CHECK( SeriesOrEvent = TRUE ),
  SellerID INT,
  SaleID INT NOT NULL,
  PriceSold DECIMAL(10,2) NOT NULL,
  CurrentPrice DECIMAL(10,2) NOT NULL,
  SeriesOrEvent BOOLEAN NOT NULL,
  PRIMARY KEY (TicketNumber),
  CONSTRAINT TicketSellerID
    FOREIGN KEY (SellerID)
    REFERENCES cpsc471.Fan (FanID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT TicketSaleID
    FOREIGN KEY (SaleID)
    REFERENCES cpsc471.Sale (SaleID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT TicketSeriesID
    FOREIGN KEY (SeriesID)
    REFERENCES cpsc471.Series (SeriesID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT TicketEventID
    FOREIGN KEY (EventID)
    REFERENCES cpsc471.Event (EventID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
INSERT INTO cpsc471.Ticket (SeriesID, SaleID, PriceSold, CurrentPrice, SeriesOrEvent) VALUE
(1, 1, 2049.33, 2049.33, TRUE);


-- -----------------------------------------------------
-- Table cpsc471.Sold_By
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Sold_By (
  SaleID INT NOT NULL,
  FanID INT CHECK(FanOrPromoterSale = FALSE),
  PromoterID INT CHECK(FanOrPromoterSale = TRUE),
  FanOrPromoterSale BOOLEAN NOT NULL,
  PRIMARY KEY (SaleID),
  CONSTRAINT SBSaleID
    FOREIGN KEY (SaleID)
    REFERENCES cpsc471.Sale (SaleID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT SBFanID
    FOREIGN KEY (FanID)
    REFERENCES cpsc471.Fan (FanID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT SBPromoterID
    FOREIGN KEY (PromoterID)
    REFERENCES cpsc471.Promoter (PromoterID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table cpsc471.Venue
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Venue (
  Name VARCHAR(45) NOT NULL,
  StreetNum INT NOT NULL,
  StreetName VARCHAR(45) NOT NULL,
  City VARCHAR(45) NOT NULL,
  Province VARCHAR(45) NOT NULL,
  Capacity INT NOT NULL,
  PRIMARY KEY (Name),
  UNIQUE KEY (StreetNum, StreetName));
INSERT INTO cpsc471.Venue (Name, StreetNum, StreetName, City, Province, Capacity) VALUES
('Scotiabank Saddledome', 555, 'Saddledome Rise SE', 'Calgary', 'Alberta', 19289);

-- -----------------------------------------------------
-- Table cpsc471.Event_Venues
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Event_Venues (
	VenueName VARCHAR(45) NOT NULL,
	EventID INT NOT NULL,
	PRIMARY KEY (VenueName, EventID),
	CONSTRAINT VenueNameFK
		FOREIGN KEY (VenueName)
		REFERENCES cpsc471.Venue (Name)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	CONSTRAINT VenueEventID
		FOREIGN KEY (EventID)
		REFERENCES cpsc471.Event (EventID)
		ON DELETE CASCADE
		ON UPDATE CASCADE);
INSERT INTO cpsc471.Event_Venues (VenueName, EventID) VALUES
('Scotiabank Saddledome', 1),
('Scotiabank Saddledome', 2),
('Scotiabank Saddledome', 3),
('Scotiabank Saddledome', 4);
-- -----------------------------------------------------
-- Table cpsc471.Sports
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Sports (
  PromoterID INT NOT NULL,
  League VARCHAR(45) NOT NULL,
  PRIMARY KEY (PromoterID),
  CONSTRAINT SportsPromoterID
    FOREIGN KEY (PromoterID)
    REFERENCES cpsc471.Promoter (PromoterID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table cpsc471.Music
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Music (
  PromoterID INT NOT NULL,
  Artist VARCHAR(45) NOT NULL,
  Genre VARCHAR(45) NOT NULL,
  PRIMARY KEY (PromoterID),
  CONSTRAINT MusicPromoterID
    FOREIGN KEY (PromoterID)
    REFERENCES cpsc471.Promoter (PromoterID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
