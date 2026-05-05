-- Database: carproject

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ------------------------
-- Table: admin
-- ------------------------
CREATE TABLE `admin` (
  `ADMIN_ID` varchar(255) NOT NULL,
  `ADMIN_PASSWORD` varchar(255) NOT NULL,
  PRIMARY KEY (`ADMIN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------
-- Table: booking
-- ------------------------
CREATE TABLE `booking` (
  `BOOK_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CAR_ID` int(11) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `BOOK_PLACE` varchar(255) NOT NULL,
  `BOOK_DATE` date NOT NULL,
  `DURATION` int(11) NOT NULL,
  `PHONE_NUMBER` bigint(20) NOT NULL,
  `DESTINATION` varchar(255) NOT NULL,
  `RETURN_DATE` date NOT NULL,
  `PRICE` int(11) NOT NULL,
  `BOOK_STATUS` varchar(255) NOT NULL DEFAULT 'UNDER PROCESSING',
  PRIMARY KEY (`BOOK_ID`),
  KEY `CAR_ID` (`CAR_ID`),
  KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------
-- Table: cars
-- ------------------------
CREATE TABLE `cars` (
  `CAR_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CAR_NAME` varchar(255) NOT NULL,
  `FUEL_TYPE` varchar(255) NOT NULL,
  `CAPACITY` int(11) NOT NULL,
  `PRICE` int(11) NOT NULL,
  `CAR_IMG` varchar(255) NOT NULL,
  `AVAILABLE` varchar(255) NOT NULL,
  PRIMARY KEY (`CAR_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------
-- Table: feedback
-- ------------------------
CREATE TABLE `feedback` (
  `FED_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(255) NOT NULL,
  `COMMENT` text NOT NULL,
  PRIMARY KEY (`FED_ID`),
  KEY `TEST` (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------
-- Table: payment
-- ------------------------
CREATE TABLE `payment` (
  `PAY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `BOOK_ID` int(11) NOT NULL,
  `CARD_NO` varchar(255) NOT NULL,
  `EXP_DATE` varchar(255) NOT NULL,
  `CVV` int(11) NOT NULL,
  `PRICE` int(11) NOT NULL,
  PRIMARY KEY (`PAY_ID`),
  UNIQUE KEY `BOOK_ID` (`BOOK_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------
-- Table: users
-- ------------------------
CREATE TABLE `users` (
  `FNAME` varchar(255) NOT NULL,
  `LNAME` varchar(255) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `LIC_NUM` varchar(255) NOT NULL,
  `PHONE_NUMBER` bigint(11) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `GENDER` varchar(255) NOT NULL,
  PRIMARY KEY (`EMAIL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------
-- Foreign Keys
-- ------------------------
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`CAR_ID`) REFERENCES `cars` (`CAR_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`EMAIL`) REFERENCES `users` (`EMAIL`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `feedback`
  ADD CONSTRAINT `TEST` FOREIGN KEY (`EMAIL`) REFERENCES `users` (`EMAIL`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`BOOK_ID`) REFERENCES `booking` (`BOOK_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;