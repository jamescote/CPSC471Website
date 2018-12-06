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
  CCID 				INT 		NOT NULL,
  CCType 			VARCHAR(45) NOT NULL,
  CCName 			VARCHAR(45) NOT NULL,
  CCSecutityCode 	INT 		NOT NULL,
  CCNumber 			INT 		NOT NULL,
  CCMonth 			INT 		NOT NULL,
  CCYear 			INT 		NOT NULL,
  PRIMARY KEY (CCID),
  UNIQUE(CCNumber));


-- -----------------------------------------------------
-- Table cpsc471.Fan
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Fan (
  FanID INT NOT NULL,
  FLogin VARCHAR(45) NOT NULL,
  FPassword VARCHAR(45) NOT NULL,
  FName VARCHAR(45) NOT NULL,
  FBirthDate DATE NOT NULL,
  PRIMARY KEY (FanID),
  UNIQUE(FLogin));


-- -----------------------------------------------------
-- Table cpsc471.Payment_Info
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Payment_Info (
  CCID INT NOT NULL,
  FanID INT NOT NULL,
  PIStreetNum INT NOT NULL,
  PIStreetName VARCHAR(45) NOT NULL,
  PICity VARCHAR(45) NOT NULL,
  PIProvince VARCHAR(45) NOT NULL,
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


-- -----------------------------------------------------
-- Table cpsc471.Promoter
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Promoter (
  PromoterID INT NOT NULL,
  PrName VARCHAR(45) NOT NULL,
  PrLogin VARCHAR(45) NOT NULL,
  PrPassword VARCHAR(45) NOT NULL,
  PrDescription VARCHAR(140) NOT NULL,
  PromoterType VARCHAR(45) NOT NULL,
  PRIMARY KEY (PromoterID, PrName),
  UNIQUE (PrLogin),
  UNIQUE (PrName));


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
  SaleID INT NOT NULL,
  FanID INT NOT NULL,
  DollarAmount DECIMAL(2) NOT NULL,
  SaleDate DATE NOT NULL,
  PRIMARY KEY (SaleID),
  CONSTRAINT SaleFanID
    FOREIGN KEY (FanID)
    REFERENCES cpsc471.Fan (FanID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table cpsc471.Series
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Series (
  SeriesID INT NOT NULL,
  PromoterID INT NOT NULL,
  Description VARCHAR(140) NOT NULL,
  NumEvents INT NOT NULL,
  Name VARCHAR(45) NOT NULL,
  StartEventID INT NOT NULL,
  EndEventID INT NOT NULL,
  NumTicketsRemaining INT NOT NULL,
  TicketPrice DECIMAL(2) NOT NULL,
  PRIMARY KEY (SeriesID),
  UNIQUE (Name),
  CONSTRAINT SeriesPromoterID
    FOREIGN KEY (PromoterID)
    REFERENCES cpsc471.Promoter (PromoterID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table cpsc471.Event
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Event (
  EventID INT NOT NULL,
  SeriesID INT,
  PromoterID INT NOT NULL,
  Name VARCHAR(45) NOT NULL,
  EventTimestamp TIMESTAMP NOT NULL,
  Description VARCHAR(140) NOT NULL,
  Duration INT NOT NULL,
  NumTicketsRemaining INT NOT NULL,
  TicketPrice DECIMAL(2) NOT NULL,
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
INSERT INTO cpsc471.Event (EventID, SeriesID, PromoterID, Name, EventTimestamp, Description, Duration, NumTicketsRemaining, TicketPrice) VALUES
(1, NULL, 12, 'Event 1', '2019-01-01 17:00', 'The coolest Event this side of mount olympus, rocking the socks off err-body!', 60, 100, 49.99),
(2, NULL, 12, 'Event 2', '2019-11-01 17:00', 'The coolest Event FFFFFFF this side of mount olympus, rocking the socks off err-body!', 60, 100, 49.99),
(3, NULL, 12, 'Event 3', '2020-01-01 17:00', 'The coolest Event GGGGGGG this side of mount olympus, rocking the socks off err-body!', 60, 100, 49.99),
(4, NULL, 12, 'Event 4', '2018-12-15 17:00', 'The coolest Event ZZZZZZZ this side of mount olympus, rocking the socks off err-body!', 60, 100, 49.99);

-- -----------------------------------------------------
-- Table cpsc471.Ticket
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Ticket (
  Number INT NOT NULL,
  EventID INT CHECK( SeriesOrEvent = FALSE ),
  SeriesID INT CHECK( SeriesOrEvent = TRUE ),
  SellerID INT NOT NULL,
  SaleID INT NOT NULL,
  PriceSold DECIMAL(2) NOT NULL,
  CurrentPrice DECIMAL(2) NOT NULL,
  SeriesOrEvent BOOLEAN NOT NULL,
  PRIMARY KEY (Number),
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


-- -----------------------------------------------------
-- Table cpsc471.Sold_By
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cpsc471.Sold_By (
  SaleID INT NOT NULL,
  FanID INT CHECK(FanOrPromoterSale = FALSE),
  PromoterID INT CHECK(FanOrPromoterSale = TRUE),
  FanOrPromoterSale BOOLEAN NOT NULL,
  PRIMARY KEY (SaleID, FanID, PromoterID),
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
  EventID INT NOT NULL,
  StreetNum INT NOT NULL,
  StreetName VARCHAR(45) NOT NULL,
  City VARCHAR(45) NOT NULL,
  Province VARCHAR(45) NOT NULL,
  Capacity INT NOT NULL,
  PRIMARY KEY (Name),
  CONSTRAINT VenueEventID
    FOREIGN KEY (EventID)
    REFERENCES cpsc471.Event (EventID)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


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
