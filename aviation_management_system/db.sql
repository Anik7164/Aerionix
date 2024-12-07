drop database if exists airlinedb;
create database airlinedb;
use airlinedb;

CREATE TABLE `aircraft` (
  `aircraft_id` int(11) NOT NULL,
  `model` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `flight_range` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aircraft`
--

INSERT INTO `aircraft` (`aircraft_id`, `model`, `capacity`, `flight_range`) VALUES
(1, 'Boeing 737', 160, 3000),
(2, 'Airbus A320', 180, 3500),
(3, 'Boeing 777', 400, 6000),
(4, 'Airbus A380', 600, 8000),
(5, 'Boeing 747', 400, 8000),
(6, 'Embraer E190', 100, 2500),
(7, 'Bombardier CRJ900', 90, 2500),
(8, 'Boeing 787', 250, 7000),
(9, 'Airbus A350', 300, 8000),
(10, 'Boeing 767', 200, 5000),
(11, 'Airbus A321', 220, 4000),
(12, 'Boeing 757', 200, 4000),
(13, 'McDonnell Douglas MD-80', 150, 3000),
(14, 'Airbus A220', 130, 3000),
(15, 'Boeing 737 MAX', 200, 3500),
(16, 'Airbus A310', 280, 5000),
(17, 'Boeing 737-800', 189, 2900),
(18, 'Airbus A319', 140, 3500),
(19, 'Boeing 787-9', 296, 7000),
(20, 'Airbus A350-900', 440, 8000),
(21, 'Boeing 737-900', 220, 3000),
(22, 'Airbus A321neo', 240, 4000),
(23, 'Boeing 757-200', 200, 4000),
(24, 'Airbus A330', 300, 6000),
(25, 'Boeing 767-300', 250, 5000),
(26, 'Boeing 737-700', 143, 3000),
(27, 'Airbus A300', 250, 5000),
(28, 'Boeing 737-800ER', 189, 3000),
(29, 'Airbus A321XLR', 244, 4000),
(30, 'Boeing 787-8', 242, 7000),
(31, 'Airbus A330-200', 240, 6000),
(32, 'Boeing 757-300', 280, 4000),
(33, 'Airbus A340', 300, 7000),
(34, 'Boeing 737 MAX 8', 210, 3500),
(35, 'Airbus A350-1000', 410, 8000),
(36, 'Boeing 777-200', 317, 6000),
(37, 'Airbus A380-800', 555, 8000),
(38, 'Boeing 737-900ER', 220, 3000),
(39, 'Airbus A330-300', 277, 6000),
(40, 'Boeing 767-200', 216, 5000),
(41, 'Boeing 787-10', 318, 7000),
(42, 'Airbus A321LR', 206, 4000),
(43, 'Boeing 737-600', 110, 3000),
(44, 'Airbus A310-300', 280, 5000),
(45, 'Boeing 757-200PF', 200, 4000),
(46, 'Airbus A330-900', 260, 6000),
(47, 'Boeing 777-300', 368, 6000),
(48, 'Airbus A350-900ULR', 440, 9000),
(49, 'Boeing 737 MAX 9', 220, 3500),
(50, 'Airbus A321neoLR', 240, 4000);

-- --------------------------------------------------------

--
-- Table structure for table `airports`
--

CREATE TABLE `airports` (
  `airport_id` int(11) NOT NULL,
  `code` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airports`
--

INSERT INTO `airports` (`airport_id`, `code`, `name`, `city`, `country`) VALUES
(1, 'LAX', 'Los Angeles International Airport', 'Los Angeles', 'USA'),
(2, 'SFO', 'San Francisco International Airport', 'San Francisco', 'USA'),
(3, 'JFK', 'John F. Kennedy International Airport', 'New York City', 'USA'),
(4, 'ORD', 'O\'Hare International Airport', 'Chicago', 'USA'),
(5, 'MIA', 'Miami International Airport', 'Miami', 'USA'),
(6, 'DFW', 'Dallas/Fort Worth International Airport', 'Dallas', 'USA'),
(7, 'ATL', 'Hartsfield-Jackson Atlanta International Airport', 'Atlanta', 'USA'),
(8, 'BOS', 'Logan International Airport', 'Boston', 'USA'),
(9, 'DEN', 'Denver International Airport', 'Denver', 'USA'),
(10, 'LAS', 'McCarran International Airport', 'Las Vegas', 'USA'),
(11, 'SAN', 'San Diego International Airport', 'San Diego', 'USA'),
(12, 'SEA', 'Seattle-Tacoma International Airport', 'Seattle', 'USA'),
(13, 'MCO', 'Orlando International Airport', 'Orlando', 'USA'),
(14, 'FLL', 'Fort Lauderdale-Hollywood International Airport', 'Fort Lauderdale', 'USA'),
(15, 'TPA', 'Tampa International Airport', 'Tampa', 'USA'),
(16, 'BWI', 'Baltimore/Washington International Thurgood Marsha', 'Baltimore', 'USA'),
(17, 'PIT', 'Pittsburgh International Airport', 'Pittsburgh', 'USA'),
(18, 'CLE', 'Cleveland Hopkins International Airport', 'Cleveland', 'USA'),
(19, 'CVG', 'Cincinnati/Northern Kentucky International Airport', 'Cincinnati', 'USA'),
(20, 'IND', 'Indianapolis International Airport', 'Indianapolis', 'USA'),
(21, 'CMH', 'John Glenn Columbus International Airport', 'Columbus', 'USA'),
(22, 'SLC', 'Salt Lake City International Airport', 'Salt Lake City', 'USA'),
(23, 'PDX', 'Portland International Airport', 'Portland', 'USA'),
(24, 'CLT', 'Charlotte Douglas International Airport', 'Charlotte', 'USA'),
(25, 'IAH', 'George Bush Intercontinental Airport', 'Houston', 'USA'),
(26, 'PHX', 'Phoenix Sky Harbor International Airport', 'Phoenix', 'USA'),
(27, 'DTW', 'Detroit Metropolitan Wayne County Airport', 'Detroit', 'USA'),
(28, 'MSP', 'Minneapolis-Saint Paul International Airport', 'Minneapolis', 'USA'),
(29, 'STL', 'St. Louis Lambert International Airport', 'St. Louis', 'USA'),
(30, 'RDU', 'Raleigh-Durham International Airport', 'Raleigh', 'USA'),
(31, 'AUS', 'Austin-Bergstrom International Airport', 'Austin', 'USA'),
(32, 'DAL', 'Dallas Love Field', 'Dallas', 'USA'),
(33, 'HOU', 'William P. Hobby Airport', 'Houston', 'USA'),
(34, 'EWR', 'Newark Liberty International Airport', 'Newark', 'USA'),
(35, 'LGA', 'LaGuardia Airport', 'New York City', 'USA'),
(36, 'JAX', 'Jacksonville International Airport', 'Jacksonville', 'USA'),
(37, 'MKE', 'General Mitchell International Airport', 'Milwaukee', 'USA'),
(38, 'ABQ', 'Albuquerque International Sunport', 'Albuquerque', 'USA'),
(39, 'OKC', 'Will Rogers World Airport', 'Oklahoma City', 'USA'),
(40, 'OMA', 'Eppley Airfield', 'Omaha', 'USA'),
(41, 'SJC', 'Norman Y. Mineta San Jose International Airport', 'San Jose', 'USA'),
(42, 'SMF', 'Sacramento International Airport', 'Sacramento', 'USA'),
(43, 'BOI', 'Boise Airport', 'Boise', 'USA'),
(44, 'GEG', 'Spokane International Airport', 'Spokane', 'USA'),
(45, 'PSP', 'Palm Springs International Airport', 'Palm Springs', 'USA'),
(46, 'BUR', 'Bob Hope Airport', 'Burbank', 'USA'),
(47, 'ONT', 'Ontario International Airport', 'Ontario', 'USA'),
(48, 'SNA', 'John Wayne Airport', 'Santa Ana', 'USA'),
(49, 'LGB', 'Long Beach Airport', 'Long Beach', 'USA'),
(50, 'OAK', 'Oakland International Airport', 'Oakland', 'USA');

-- --------------------------------------------------------

--
-- Table structure for table `baggage`
--

CREATE TABLE `baggage` (
  `baggage_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `baggage_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baggage`
--

INSERT INTO `baggage` (`baggage_id`, `flight_id`, `passenger_id`, `baggage_type`) VALUES
(1, 1, 1, 'Checked'),
(2, 1, 2, 'Carry-on'),
(3, 1, 3, 'Checked'),
(4, 2, 4, 'Carry-on'),
(5, 2, 5, 'Checked'),
(6, 2, 6, 'Carry-on'),
(7, 3, 7, 'Checked'),
(8, 3, 8, 'Carry-on'),
(9, 3, 9, 'Checked'),
(10, 4, 10, 'Carry-on'),
(11, 4, 11, 'Checked'),
(12, 4, 12, 'Carry-on'),
(13, 5, 13, 'Checked'),
(14, 5, 14, 'Carry-on'),
(15, 5, 15, 'Checked'),
(16, 6, 16, 'Carry-on'),
(17, 6, 17, 'Checked'),
(18, 6, 18, 'Carry-on'),
(19, 7, 19, 'Checked'),
(20, 7, 20, 'Carry-on'),
(21, 7, 21, 'Checked'),
(22, 8, 22, 'Carry-on'),
(23, 8, 23, 'Checked'),
(24, 8, 24, 'Carry-on'),
(25, 9, 25, 'Checked'),
(26, 9, 26, 'Carry-on'),
(27, 9, 27, 'Checked'),
(28, 10, 28, 'Carry-on'),
(29, 10, 29, 'Checked'),
(30, 10, 30, 'Carry-on'),
(31, 11, 31, 'Checked'),
(32, 11, 32, 'Carry-on'),
(33, 11, 33, 'Checked'),
(34, 12, 34, 'Carry-on'),
(35, 12, 35, 'Checked'),
(36, 12, 36, 'Carry-on'),
(37, 13, 37, 'Checked'),
(38, 13, 38, 'Carry-on'),
(39, 13, 39, 'Checked'),
(40, 14, 40, 'Carry-on'),
(41, 14, 41, 'Checked'),
(42, 14, 42, 'Carry-on'),
(43, 15, 43, 'Checked'),
(44, 15, 44, 'Carry-on'),
(45, 15, 45, 'Checked'),
(46, 16, 46, 'Carry-on'),
(47, 16, 47, 'Checked'),
(48, 16, 48, 'Carry-on'),
(49, 17, 49, 'Checked'),
(50, 17, 50, 'Carry-on');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `flight_id`, `passenger_id`, `seat_number`) VALUES
(1, 1, 1, 'A1'),
(2, 1, 2, 'A2'),
(3, 1, 3, 'A3'),
(4, 2, 4, 'B1'),
(5, 2, 5, 'B2'),
(6, 2, 6, 'B3'),
(7, 3, 7, 'C1'),
(8, 3, 8, 'C2'),
(9, 3, 9, 'C3'),
(10, 4, 10, 'D1'),
(11, 4, 11, 'D2'),
(12, 4, 12, 'D3'),
(13, 5, 13, 'E1'),
(14, 5, 14, 'E2'),
(15, 5, 15, 'E3'),
(16, 6, 16, 'F1'),
(17, 6, 17, 'F2'),
(18, 6, 18, 'F3'),
(19, 7, 19, 'G1'),
(20, 7, 20, 'G2'),
(21, 7, 21, 'G3'),
(22, 8, 22, 'H1'),
(23, 8, 23, 'H2 '),
(24, 8, 24, 'H3'),
(25, 9, 25, 'I1'),
(26, 9, 26, 'I2'),
(27, 9, 27, 'I3'),
(28, 10, 28, 'J1'),
(29, 10, 29, 'J2'),
(30, 10, 30, 'J3'),
(31, 11, 31, 'K1'),
(32, 11, 32, 'K2'),
(33, 11, 33, 'K3'),
(34, 12, 34, 'L1'),
(35, 12, 35, 'L2'),
(36, 12, 36, 'L3'),
(37, 13, 37, 'M1'),
(38, 13, 38, 'M2'),
(39, 13, 39, 'M3'),
(40, 14, 40, 'N1'),
(41, 14, 41, 'N2'),
(42, 14, 42, 'N3'),
(43, 15, 43, 'O1'),
(44, 15, 44, 'O2'),
(45, 15, 45, 'O3'),
(46, 16, 46, 'P1'),
(47, 16, 47, 'P2'),
(48, 16, 48, 'P3'),
(49, 17, 49, 'Q1'),
(50, 17, 50, 'Q2');

-- --------------------------------------------------------

--
-- Table structure for table `catering`
--

CREATE TABLE `catering` (
  `catering_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `menu` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catering`
--

INSERT INTO `catering` (`catering_id`, `flight_id`, `date`, `menu`) VALUES
(1, 1, '2022-01-01', 'Breakfast'),
(2, 2, '2022-01-01', 'Lunch'),
(3, 3, '2022-01-01', 'Dinner'),
(4, 4, '2022-01-01', 'Snack'),
(5, 5, '2022-01-01', 'Breakfast'),
(6, 6, '2022-01-01', 'Lunch'),
(7, 7, '2022-01-01', 'Dinner'),
(8, 8, '2022-01-01', 'Snack'),
(9, 9, '2022-01-01', 'Breakfast'),
(10, 10, '2022-01-01', 'Lunch'),
(11, 11, '2022-01-01', 'Dinner'),
(12, 12, '2022-01-01', 'Snack'),
(13, 13, '2022-01-01', 'Breakfast'),
(14, 14, '2022-01-01', 'Lunch'),
(15, 15, '2022-01-01', 'Dinner'),
(16, 16, '2022-01-01', 'Snack'),
(17, 17, '2022-01-01', 'Breakfast'),
(18, 18, '2022-01-01', 'Lunch'),
(19, 19, '2022-01-01', 'Dinner'),
(20, 20, '2022-01-01', 'Snack'),
(21, 21, '2022-01-01', 'Breakfast'),
(22, 22, '2022-01-01', 'Lunch'),
(23, 23, '2022-01-01', 'Dinner'),
(24, 24, '2022-01-01', 'Snack'),
(25, 25, '2022-01-01', 'Breakfast'),
(26, 26, '2022-01-01', 'Lunch'),
(27, 27, '2022-01-01', 'Dinner'),
(28, 28, '2022-01-01', 'Snack'),
(29, 29, '2022-01-01', 'Breakfast'),
(30, 30, '2022-01-01', 'Lunch'),
(31, 31, '2022-01-01', 'Dinner'),
(32, 32, '2022-01-01', 'Snack'),
(33, 33, '2022-01-01', 'Breakfast'),
(34, 34, '2022-01-01', 'Lunch'),
(35, 35, '2022-01-01', 'Dinner'),
(36, 36, '2022-01-01', 'Snack'),
(37, 37, '2022-01-01', 'Breakfast'),
(38, 38, '2022-01-01', 'Lunch'),
(39, 39, '2022-01-01', 'Dinner'),
(40, 40, '2022-01-01', 'Snack'),
(41, 41, '2022-01-01', 'Breakfast'),
(42, 42, '2022-01-01', 'Lunch'),
(43, 43, '2022-01-01', 'Dinner'),
(44, 44, '2022-01-01', 'Snack'),
(45, 45, '2022-01-01', 'Breakfast'),
(46, 46, '2022-01-01', 'Lunch'),
(47, 47, '2022-01-01', 'Dinner'),
(48, 48, '2022-01-01', 'Snack'),
(49, 49, '2022-01-01', 'Breakfast'),
(50, 50, '2022-01-01', 'Lunch');

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE `checkin` (
  `checkin_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `checkin_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkin`
--

INSERT INTO `checkin` (`checkin_id`, `flight_id`, `passenger_id`, `checkin_time`) VALUES
(1, 1, 1, '2022-01-01 01:00:00'),
(2, 1, 2, '2022-01-01 01:30:00'),
(3, 1, 3, '2022-01-01 02:00:00'),
(4, 2, 4, '2022-01-01 03:00:00'),
(5, 2, 5, '2022-01-01 03:30:00'),
(6, 2, 6, '2022-01-01 04:00:00'),
(7, 3, 7, '2022-01-01 05:00:00'),
(8, 3, 8, '2022-01-01 05:30:00'),
(9, 3, 9, '2022-01-01 06:00:00'),
(10, 4, 10, '2022-01-01 07:00:00'),
(11, 4, 11, '2022-01-01 07:30:00'),
(12, 4, 12, '2022-01-01 08:00:00'),
(13, 5, 13, '2022-01-01 09:00:00'),
(14, 5, 14, '2022-01-01 09:30:00'),
(15, 5, 15, '2022-01-01 10:00:00'),
(16, 6, 16, '2022-01-01 11:00:00'),
(17, 6, 17, '2022-01-01 11:30:00'),
(18, 6, 18, '2022-01-01 12:00:00'),
(19, 7, 19, '2022-01-01 13:00:00'),
(20, 7, 20, '2022-01-01 13:30:00'),
(21, 7, 21, '2022-01-01 14:00:00'),
(22, 8, 22, '2022-01-01 15:00:00'),
(23, 8, 23, '2022-01-01 15:30:00'),
(24, 8, 24, '2022-01-01 16:00:00'),
(25, 9, 25, '2022-01-01 17:00:00'),
(26, 9, 26, '2022-01-01 17:30:00'),
(27, 9, 27, '2022-01-01 18:00:00'),
(28, 10, 28, '2022-01-01 19:00:00'),
(29, 10, 29, '2022-01-01 19:30:00'),
(30, 10, 30, '2022-01-01 20:00:00'),
(31, 11, 31, '2022-01-01 21:00:00'),
(32, 11, 32, '2022-01-01 21:30:00'),
(33, 11, 33, '2022-01-01 22:00:00'),
(34, 12, 34, '2022-01-01 23:00:00'),
(35, 12, 35, '2022-01-01 23:30:00'),
(36, 12, 36, '2022-01-02 00:00:00'),
(37, 13, 37, '2022-01-02 01:00:00'),
(38, 13, 38, '2022-01-02 01:30:00'),
(39, 13, 39, '2022-01-02 02:00:00'),
(40, 14, 40, '2022-01-02 03:00:00'),
(41, 14, 41, '2022-01-02 03:30:00'),
(42, 14, 42, '2022-01-02 04:00:00'),
(43, 15, 43, '2022-01-02 05:00:00'),
(44, 15, 44, '2022-01-02 05:30:00'),
(45, 15, 45, '2022-01-02 06:00:00'),
(46, 16, 46, '2022-01-02 07:00:00'),
(47, 16, 47, '2022-01-02 07:30:00'),
(48, 16, 48, '2022-01-02 08:00:00'),
(49, 17, 49, '2022-01-02 09:00:00'),
(50, 17, 50, '2022-01-02 09:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `crew`
--

CREATE TABLE `crew` (
  `crew_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `experience` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `crew`
--

INSERT INTO `crew` (`crew_id`, `name`, `role`, `experience`) VALUES
(1, 'John Doe', 'Pilot', 10),
(2, 'Jane Smith', ' Co-Pilot', 5),
(3, 'Bob Johnson', 'Flight Attendant', 8),
(4, 'Alice Brown', 'Flight Attendant', 3),
(5, 'Mike Davis', 'Pilot', 12),
(6, 'Emily Taylor', 'Co-Pilot', 7),
(7, 'Sarah Lee', 'Flight Attendant', 4),
(8, 'Kevin White', 'Pilot', 15),
(9, 'Lisa Nguyen', 'Co-Pilot', 9),
(10, 'David Kim', 'Flight Attendant', 6),
(11, 'Jessica Martin', 'Flight Attendant', 2),
(12, 'Michael Brown', 'Pilot', 11),
(13, 'Hannah Davis', 'Co-Pilot', 8),
(14, 'Brian Lee', 'Flight Attendant', 5),
(15, 'Rebecca Taylor', 'Flight Attendant', 3),
(16, 'Christopher Kim', 'Pilot', 14),
(17, 'Amanda Nguyen', 'Co-Pilot', 10),
(18, 'Matthew Martin', 'Flight Attendant', 7),
(19, 'Samantha Brown', 'Flight Attendant', 4),
(20, 'Daniel Davis', 'Pilot', 13),
(21, 'Elizabeth Lee', 'Co-Pilot', 9),
(22, 'Benjamin Taylor', 'Flight Attendant', 6),
(23, 'Rachel Kim', 'Flight Attendant', 5),
(24, 'Alexander Nguyen', 'Pilot', 12),
(25, 'Madison Martin', 'Co-Pilot', 8),
(26, 'Gabriella Brown', 'Flight Attendant', 4),
(27, 'Julian Davis', 'Pilot', 11),
(28, 'Sofia Lee', 'Co-Pilot', 7),
(29, 'Ethan Taylor', 'Flight Attendant', 6),
(30, 'Lily Kim', 'Flight Attendant', 5),
(31, 'Olivia Nguyen', 'Pilot', 14),
(32, 'Ava Martin', 'Co-Pilot', 10),
(33, 'Isabella Brown', 'Flight Attendant', 4),
(34, 'Logan Davis', 'Pilot', 13),
(35, 'Evelyn Lee', 'Co-Pilot', 9),
(36, 'Parker Taylor', 'Flight Attendant', 7),
(37, 'Ruby Kim', 'Flight Attendant', 6),
(38, 'Caleb Nguyen', 'Pilot', 12),
(39, 'Lila Martin', 'Co-Pilot', 8),
(40, 'Gavin Brown', 'Flight Attendant', 5),
(41, 'Harrison Davis', 'Pilot', 11),
(42, 'Aubrey Lee', 'Co-Pilot', 7),
(43, 'Rowan Taylor', 'Flight Attendant', 6),
(44, 'Sage Kim', 'Flight Attendant', 5),
(45, 'Riley Nguyen', 'Pilot', 14),
(46, 'Paisley Martin', 'Co-Pilot', 10),
(47, 'Remi Brown', 'Flight Attendant', 4),
(48, 'Cameron Davis', 'Pilot', 13),
(49, 'Everley Lee', 'Co-Pilot', 9),
(50, 'August Taylor', 'Flight Attendant', 7);

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `flight_id` int(11) NOT NULL,
  `departure_airport` varchar(3) NOT NULL,
  `arrival_airport` varchar(3) NOT NULL,
  `departure_time` timestamp NOT NULL DEFAULT '2021-12-31 18:00:00',
  `arrival_time` timestamp NOT NULL DEFAULT '2021-12-31 18:00:00',
  `aircraft_id` int(11) NOT NULL
) ;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`flight_id`, `departure_airport`, `arrival_airport`, `departure_time`, `arrival_time`, `aircraft_id`) VALUES
(1, 'LAX', 'SFO', '2022-01-01 02:00:00', '2022-01-01 03:00:00', 1),
(2, 'SFO', 'LAX', '2022-01-01 04:00:00', '2022-01-01 05:00:00', 2),
(3, 'LAX', 'JFK', '2022-01-01 06:00:00', '2022-01-01 13:00:00', 3),
(4, 'JFK', 'LAX', '2022-01-01 14:00:00', '2022-01-01 21:00:00', 4),
(5, 'SFO', 'ORD', '2022-01-01 08:00:00', '2022-01-01 13:00:00', 5),
(6, 'ORD', 'SFO', '2022-01-01 14:00:00', '2022-01-01 19:00:00', 6),
(7, 'LAX', 'MIA', '2022-01-01 10:00:00', '2022-01-01 19:00:00', 7),
(8, 'MIA', 'LAX', '2022-01-01 20:00:00', '2022-01-02 03:00:00', 8),
(9, 'SFO', 'DFW', '2022-01-01 12:00:00', '2022-01-01 19:00:00', 9),
(10, 'DFW', 'SFO', '2022-01-01 20:00:00', '2022-01-02 01:00:00', 10),
(11, 'LAX', 'ATL', '2022-01-01 14:00:00', '2022-01-01 23:00:00', 11),
(12, 'ATL', 'LAX', '2022-01-02 00:00:00', '2022-01-02 05:00:00', 12),
(13, 'SFO', 'BOS', '2022-01-01 16:00:00', '2022-01-02 01:00:00', 13),
(14, 'BOS', 'SFO', '2022-01-02 02:00:00', '2022-01-02 09:00:00', 14),
(15, 'LAX', 'DEN', '2021-12-31 18:00:00', '2021-12-31 21:00:00', 15),
(16, 'DEN', 'LAX', '2021-12-31 22:00:00', '2022-01-01 01:00:00', 16),
(17, 'SFO', 'IAH', '2021-12-31 20:00:00', '2022-01-01 01:00:00', 17),
(18, 'IAH', 'SFO', '2022-01-01 02:00:00', '2022-01-01 07:00:00', 18),
(19, 'LAX', 'PHX', '2022-01-01 00:00:00', '2022-01-01 03:00:00', 19),
(20, 'PHX', 'LAX', '2022-01-01 04:00:00', '2022-01-01 07:00:00', 20),
(21, 'SFO', 'CLT', '2022-01-01 06:00:00', '2022-01-01 13:00:00', 21),
(22, 'CLT', 'SFO', '2022-01-01 14:00:00', '2022-01-01 21:00:00', 22),
(23, 'LAX', 'PDX', '2022-01-01 08:00:00', '2022-01-01 11:00:00', 23),
(24, 'PDX', 'LAX', '2022-01-01 12:00:00', '2022-01-01 15:00:00', 24),
(25, 'SFO', 'SLC', '2022-01-01 10:00:00', '2022-01-01 13:00:00', 25),
(26, 'SLC', 'SFO', '2022-01-01 14:00:00', '2022-01-01 19:00:00', 26),
(27, 'LAX', 'LAS', '2022-01-01 12:00:00', '2022-01-01 15:00:00', 27),
(28, 'LAS', 'LAX', '2022-01-01 16:00:00', '2022-01-01 19:00:00', 28),
(29, 'SFO', 'SAN', '2022-01-01 14:00:00', '2022-01-01 19:00:00', 29),
(30, 'SAN', 'SFO', '2022-01-01 20:00:00', '2022-01-01 23:00:00', 30),
(31, 'LAX', 'SEA', '2022-01-01 16:00:00', '2022-01-01 19:00:00', 31),
(32, 'SEA', 'LAX', '2022-01-01 20:00:00', '2022-01-01 23:00:00', 32),
(33, 'SFO', 'MCO', '2021-12-31 18:00:00', '2022-01-01 01:00:00', 33),
(34, 'MCO', 'SFO', '2022-01-01 02:00:00', '2022-01-01 09:00:00', 34),
(35, 'LAX', 'FLL', '2021-12-31 20:00:00', '2022-01-01 03:00:00', 35),
(36, 'FLL', 'LAX', '2022-01-01 04:00:00', '2022-01-01 11:00:00', 36),
(37, 'SFO', 'TPA', '2021-12-31 22:00:00', '2022-01-01 05:00:00', 37),
(38, 'TPA', 'SFO', '2022-01-01 06:00:00', '2022-01-01 13:00:00', 38),
(39, 'LAX', 'BWI', '2022-01-01 00:00:00', '2022-01-01 07:00:00', 39),
(40, 'BWI', 'LAX', '2022-01-01 08:00:00', '2022-01-01 15:00:00', 40),
(41, 'SFO', 'PIT', '2022-01-01 02:00:00', '2022-01-01 09:00:00', 41),
(42, 'PIT', 'SFO', '2022-01-01 10:00:00', '2022-01-01 17:00:00', 42),
(43, 'LAX', 'CLE', '2022-01-01 04:00:00', '2022-01-01 11:00:00', 43),
(44, 'CLE', 'LAX', '2022-01-01 12:00:00', '2022-01-01 19:00:00', 44),
(45, 'SFO', 'CVG', '2022-01-01 06:00:00', '2022-01-01 13:00:00', 45),
(46, 'CVG', 'SFO', '2022-01-01 14:00:00', '2022-01-01 21:00:00', 46),
(47, 'LAX', 'IND', '2022-01-01 08:00:00', '2022-01-01 15:00:00', 47),
(48, 'IND', 'LAX', '2022-01-01 16:00:00', '2022-01-01 23:00:00', 48),
(49, 'SFO', 'CMH', '2022-01-01 10:00:00', '2022-01-01 17:00:00', 49),
(50, 'CMH', 'SFO', '2022-01-01 18:00:00', '2022-01-02 01:00:00', 50);

-- --------------------------------------------------------

--
-- Table structure for table `flight_crew`
--

CREATE TABLE `flight_crew` (
  `flight_id` int(11) NOT NULL,
  `crew_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight_crew`
--

INSERT INTO `flight_crew` (`flight_id`, `crew_id`, `role`) VALUES
(1, 1, 'Pilot'),
(1, 2, 'Co-Pilot'),
(1, 3, 'Flight Attendant'),
(2, 4, 'Pilot'),
(2, 5, 'Co-Pilot'),
(2, 6, 'Flight Attendant'),
(3, 7, 'Pilot'),
(3, 8, 'Co-Pilot'),
(3, 9, 'Flight Attendant'),
(4, 10, 'Pilot'),
(4, 11, 'Co-Pilot'),
(4, 12, 'Flight Attendant'),
(5, 13, 'Pilot'),
(5, 14, 'Co-Pilot'),
(5, 15, 'Flight Attendant'),
(6, 16, 'Pilot'),
(6, 17, 'Co-Pilot'),
(6, 18, 'Flight Attendant'),
(7, 19, 'Pilot'),
(7, 20, 'Co-Pilot'),
(7, 21, 'Flight Attendant'),
(8, 22, 'Pilot'),
(8, 23, 'Co-Pilot'),
(8, 24, 'Flight Attendant'),
(9, 25, 'Pilot'),
(9, 26, 'Co-Pilot'),
(9, 27, 'Flight Attendant'),
(10, 28, 'Pilot'),
(10, 29, 'Co-Pilot'),
(10, 30, 'Flight Attendant'),
(11, 31, 'Pilot'),
(11, 32, 'Co-Pilot'),
(11, 33, 'Flight Attendant'),
(12, 34, 'Pilot'),
(12, 35, 'Co-Pilot'),
(12, 36, 'Flight Attendant'),
(13, 37, 'Pilot'),
(13, 38, 'Co-Pilot'),
(13, 39, 'Flight Attendant'),
(14, 40, 'Pilot'),
(14, 41, 'Co-Pilot'),
(14, 42, 'Flight Attendant'),
(15, 43, 'Pilot'),
(15, 44, 'Co-Pilot'),
(15, 45, 'Flight Attendant'),
(16, 46, 'Pilot'),
(16, 47, 'Co-Pilot'),
(16, 48, 'Flight Attendant'),
(17, 1, 'Flight Attendant'),
(17, 49, 'Pilot'),
(17, 50, 'Co-Pilot'),
(18, 2, 'Pilot'),
(18, 3, 'Co-Pilot'),
(18, 4, 'Flight Attendant'),
(19, 5, 'Pilot'),
(19, 6, 'Co-Pilot'),
(19, 7, 'Flight Attendant'),
(20, 8, 'Pilot'),
(20, 9, 'Co-Pilot'),
(20, 10, 'Flight Attendant'),
(21, 11, 'Pilot'),
(21, 12, 'Co-Pilot'),
(21, 13, 'Flight Attendant'),
(22, 14, 'Pilot'),
(22, 15, 'Co-Pilot'),
(22, 16, 'Flight Attendant'),
(23, 17, 'Pilot'),
(23, 18, 'Co-Pilot'),
(23, 19, 'Flight Attendant'),
(24, 20, 'Pilot'),
(24, 21, 'Co-Pilot'),
(24, 22, 'Flight Attendant'),
(25, 23, 'Pilot'),
(25, 24, 'Co-Pilot'),
(25, 25, 'Flight Attendant'),
(26, 26, 'Pilot'),
(26, 27, 'Co-Pilot'),
(26, 28, 'Flight Attendant'),
(27, 29, 'Pilot'),
(27, 30, 'Co-Pilot'),
(27, 31, 'Flight Attendant'),
(28, 32, 'Pilot'),
(28, 33, 'Co-Pilot'),
(28, 34, 'Flight Attendant'),
(29, 35, 'Pilot'),
(29, 36, 'Co-Pilot'),
(29, 37, 'Flight Attendant'),
(30, 38, 'Pilot'),
(30, 39, 'Co-Pilot'),
(30, 40, 'Flight Attendant'),
(31, 41, 'Pilot'),
(31, 42, 'Co-Pilot'),
(31, 43, 'Flight Attendant'),
(32, 44, 'Pilot'),
(32, 45, 'Co-Pilot'),
(32, 46, 'Flight Attendant'),
(33, 47, 'Pilot'),
(33, 48, 'Co-Pilot'),
(33, 49, 'Flight Attendant'),
(34, 1, 'Co-Pilot'),
(34, 2, 'Flight Attendant'),
(34, 50, 'Pilot'),
(35, 3, 'Pilot'),
(35, 4, 'Co-Pilot'),
(35, 5, 'Flight Attendant'),
(36, 6, 'Pilot'),
(36, 7, 'Co-Pilot'),
(36, 8, 'Flight Attendant'),
(37, 9, 'Pilot'),
(37, 10, 'Co-Pilot'),
(37, 11, 'Flight Attendant'),
(38, 12, 'Pilot'),
(38, 13, 'Co-Pilot'),
(38, 14, 'Flight Attendant'),
(39, 15, 'Pilot'),
(39, 16, 'Co-Pilot'),
(39, 17, 'Flight Attendant'),
(40, 18, 'Pilot'),
(40, 19, 'Co-Pilot'),
(40, 20, 'Flight Attendant'),
(41, 21, 'Pilot'),
(41, 22, 'Co-Pilot'),
(41, 23, 'Flight Attendant'),
(42, 24, 'Pilot'),
(42, 25, 'Co-Pilot'),
(42, 26, 'Flight Attendant'),
(43, 27, 'Pilot'),
(43, 28, 'Co-Pilot'),
(43, 29, 'Flight Attendant'),
(44, 30, 'Pilot'),
(44, 31, 'Co-Pilot'),
(44, 32, 'Flight Attendant'),
(45, 33, 'Pilot'),
(45, 34, 'Co-Pilot'),
(45, 35, 'Flight Attendant'),
(46, 36, 'Pilot'),
(46, 37, 'Co-Pilot'),
(46, 38, 'Flight Attendant'),
(47, 39, 'Pilot'),
(47, 40, 'Co-Pilot'),
(47, 41, 'Flight Attendant'),
(48, 42, 'Pilot'),
(48, 43, 'Co-Pilot'),
(48, 44, 'Flight Attendant'),
(49, 45, 'Pilot'),
(49, 46, 'Co-Pilot'),
(49, 47, 'Flight Attendant'),
(50, 48, 'Pilot'),
(50, 49, 'Co-Pilot'),
(50, 50, 'Flight Attendant');

-- --------------------------------------------------------

--
-- Table structure for table `flight_stats`
--

CREATE TABLE `flight_stats` (
  `flight_stats_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `departure_delay` int(11) DEFAULT NULL,
  `arrival_delay` int(11) DEFAULT NULL,
  `flight_duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight_stats`
--

INSERT INTO `flight_stats` (`flight_stats_id`, `flight_id`, `departure_delay`, `arrival_delay`, `flight_duration`) VALUES
(1, 1, 0, 0, 60),
(2, 2, 0, 0, 60),
(3, 3, 0, 0, 420),
(4, 4, 0, 0, 420),
(5, 5, 0, 0, 300),
(6, 6, 0, 0, 300),
(7, 7, 0, 0, 420),
(8, 8, 0, 0, 420),
(9, 9, 0, 0, 300),
(10, 10, 0, 0, 300),
(11, 11, 0, 0, 420),
(12, 12, 0, 0, 420),
(13, 13, 0, 0, 300),
(14, 14, 0, 0, 300),
(15, 15, 0, 0, 180),
(16, 16, 0, 0, 180),
(17, 17, 0, 0, 300),
(18, 18, 0, 0, 300),
(19, 19, 0, 0, 180),
(20, 20, 0, 0, 180),
(21, 21, 0, 0, 420),
(22, 22, 0, 0, 420),
(23, 23, 0, 0, 180),
(24, 24, 0, 0, 180),
(25, 25, 0, 0, 300),
(26, 26, 0, 0, 300),
(27, 27, 0, 0, 420),
(28, 28, 0, 0, 420),
(29, 29, 0, 0, 300),
(30, 30, 0, 0, 300),
(31, 31, 0, 0, 420),
(32, 32, 0, 0, 420),
(33, 33, 0, 0, 300),
(34, 34, 0, 0, 300),
(35, 35, 0, 0, 180),
(36, 36, 0, 0, 180),
(37, 37, 0, 0, 300),
(38, 38, 0, 0, 300),
(39, 39, 0, 0, 180),
(40, 40, 0, 0, 180),
(41, 41, 0, 0, 420),
(42, 42, 0, 0, 420),
(43, 43, 0, 0, 180),
(44, 44, 0, 0, 180),
(45, 45, 0, 0, 300),
(46, 46, 0, 0, 300),
(47, 47, 0, 0, 420),
(48, 48, 0, 0, 420),
(49, 49, 0, 0, 300),
(50, 50, 0, 0, 300);

-- --------------------------------------------------------

--
-- Table structure for table `fuel`
--

CREATE TABLE `fuel` (
  `fuel_id` int(11) NOT NULL,
  `aircraft_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fuel`
--

INSERT INTO `fuel` (`fuel_id`, `aircraft_id`, `date`, `quantity`) VALUES
(1, 1, '2022-01-01', 1000),
(2, 2, '2022-01-02', 1200),
(3, 3, '2022-01-03', 1500),
(4, 4, '2022-01-04', 1800),
(5, 5, '2022-01-05', 2000),
(6, 6, '2022-01-06', 2200),
(7, 7, '2022-01-07', 2500),
(8, 8, '2022-01-08', 2800),
(9, 9, '2022-01-09', 3000),
(10, 10, '2022-01-10', 3200),
(11, 11, '2022-01-11', 3500),
(12, 12, '2022-01-12', 3800),
(13, 13, '2022-01-13', 4000),
(14, 14, '2022-01-14', 4200),
(15, 15, '2022-01-15', 4500),
(16, 16, '2022-01-16', 4800),
(17, 17, '2022-01-17', 5000),
(18, 18, '2022-01-18', 5200),
(19, 19, '2022-01-19', 5500),
(20, 20, '2022-01-20', 5800),
(21, 21, '2022-01-21', 6000),
(22, 22, '2022-01-22', 6200),
(23, 23, '2022-01-23', 6500),
(24, 24, '2022-01-24', 6800),
(25, 25, '2022-01-25', 7000),
(26, 26, '2022-01-26', 7200),
(27, 27, '2022-01-27', 7500),
(28, 28, '2022-01-28', 7800),
(29, 29, '2022-01-29', 8000),
(30, 30, '2022-01-30', 8200),
(31, 31, '2022-01-31', 8500),
(32, 32, '2022-02-01', 8800),
(33, 33, '2022-02-02', 9000),
(34, 34, '2022-02-03', 9200),
(35, 35, '2022-02-04', 9500),
(36, 36, '2022-02-05', 9800),
(37, 37, '2022-02-06', 10000),
(38, 38, '2022-02-07', 10200),
(39, 39, '2022-02-08', 10500),
(40, 40, '2022-02-09', 10800),
(41, 41, '2022-02-10', 11000),
(42, 42, '2022-02-11', 11200),
(43, 43, '2022-02-12', 11500),
(44, 44, '2022-02-13', 11800),
(45, 45, '2022-02-14', 12000),
(46, 46, '2022-02-15', 12200),
(47, 47, '2022-02-16', 12500),
(48, 48, '2022-02-17', 12800),
(49, 49, '2022-02-18', 13000),
(50, 50, '2022-02-19', 13200);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `maintenance_id` int(11) NOT NULL,
  `aircraft_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`maintenance_id`, `aircraft_id`, `date`, `description`) VALUES
(1, 1, '2022-01-01', 'Routine maintenance'),
(2, 2, '2022-01-02', 'Engine repair'),
(3, 3, '2022-01-03', 'Tire replacement'),
(4, 4, '2022-01-04', 'Oil change'),
(5, 5, '2022-01-05', 'Battery replacement'),
(6, 6, '2022-01-06', 'Air filter replacement'),
(7, 7, '2022-01-07', 'Spark plug replacement'),
(8, 8, '2022-01-08', 'Fuel system maintenance'),
(9, 9, '2022-01-09', 'Electrical system maintenance'),
(10, 10, '2022-01-10', 'Hydraulic system maintenance'),
(11, 11, '2022-01-11', 'Landing gear maintenance'),
(12, 12, '2022-01-12', 'Avionics system maintenance'),
(13, 13, '2022-01-13', 'Cabin pressure maintenance'),
(14, 14, '2022-01-14', 'Oxygen system maintenance'),
(15, 15, '2022-01-15', 'Fire suppression system maintenance'),
(16, 16, '2022-01-16', 'Emergency exit maintenance'),
(17, 17, '2022-01-17', 'Seat maintenance'),
(18, 18, '2022-01-18', 'Cabin interior maintenance'),
(19, 19, '2022-01-19', 'Galley maintenance'),
(20, 20, '2022-01-20', 'Lavatory maintenance'),
(21, 21, '2022-01-21', 'Water system maintenance'),
(22, 22, '2022-01-22', 'Waste system maintenance'),
(23, 23, '2022-01-23', 'Air conditioning system maintenance'),
(24, 24, '2022-01-24', 'Heating system maintenance'),
(25, 25, '2022-01-25', 'Pressurization system maintenance'),
(26, 26, '2022-01-26', 'Ice protection system maintenance'),
(27, 27, '2022-01-27', 'Rain repellent system maintenance'),
(28, 28, '2022-01-28', 'Windshield maintenance'),
(29, 29, '2022-01-29', 'Window maintenance'),
(30, 30, '2022-01-30', 'Door maintenance'),
(31, 31, '2022-01-31', 'Cargo door maintenance'),
(32, 32, '2022-02-01', 'Cargo system maintenance'),
(33, 33, '2022-02-02', 'Fuel tank maintenance'),
(34, 34, '2022-02-03', 'Fuel pump maintenance'),
(35, 35, '2022-02-04', 'Fuel filter maintenance'),
(36, 36, '2022-02-05', 'Fuel injector maintenance'),
(37, 37, '2022-02-06', 'Engine mount maintenance'),
(38, 38, '2022-02-07', 'Engine control system maintenance'),
(39, 39, '2022-02-08', 'Engine monitoring system maintenance'),
(40, 40, '2022-02-09', 'Engine oil system maintenance'),
(41, 41, '2022-02-10', 'Engine fuel system maintenance'),
(42, 42, '2022-02-11', 'Engine ignition system maintenance'),
(43, 43, '2022-02-12', 'Engine starting system maintenance'),
(44, 44, '2022-02-13', 'Engine exhaust system maintenance'),
(45, 45, '2022-02-14', 'Engine cooling system maintenance'),
(46, 46, '2022-02-15', 'Engine lubrication system maintenance'),
(47, 47, '2022-02-16', 'Engine hydraulic system maintenance'),
(48, 48, '2022-02-17', 'Engine pneumatic system maintenance'),
(49, 49, '2022-02-18', 'Engine electrical system maintenance'),
(50, 50, '2022-02-19', 'Engine avionics system maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `passengers`
--

CREATE TABLE `passengers` (
  `passenger_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL CHECK (octet_length(`phone_number`) = 10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passengers`
--

INSERT INTO `passengers` (`passenger_id`, `name`, `email`, `phone_number`) VALUES
(1, 'John Doe', 'john.doe@example.com', '1234567890'),
(2, 'Jane Smith', 'jane.smith@example.com', '9876543210'),
(3, 'Bob Johnson', 'bob.johnson@example.com', '5551234567'),
(4, 'Alice Brown', 'alice.brown@example.com', '5559876543'),
(5, 'Mike Davis', 'mike.davis@example.com', '5555551234'),
(6, 'Emily Taylor', 'emily.taylor@example.com', '5555559876'),
(7, 'Sarah Lee', 'sarah.lee@example.com', '5551230987'),
(8, 'Kevin White', 'kevin.white@example.com', '5555555555'),
(9, 'Lisa Nguyen', 'lisa.nguyen@example.com', '5559876123'),
(10, 'David Kim', 'david.kim@example.com', '5555554321'),
(11, 'Jessica Martin', 'jessica.martin@example.com', '5551234567'),
(12, 'Michael Brown', 'michael.brown@example.com', '5559876543'),
(13, 'Hannah Davis', 'hannah.davis@example.com', '5555551234'),
(14, 'Brian Lee', 'brian.lee@example.com', '5555559876'),
(15, 'Rebecca Taylor', 'rebecca.taylor@example.com', '5551230987'),
(16, 'Christopher Kim', 'christopher.kim@example.com', '5555555555'),
(17, 'Amanda Nguyen', 'amanda.nguyen@example.com', '5559876123'),
(18, 'Matthew Martin', 'matthew.martin@example.com', '5551234567'),
(19, 'Saman tha Brown', 'samantha.brown@example.com', '5559876543'),
(20, 'Daniel Davis', 'daniel.davis@example.com', '5555551234'),
(21, 'Elizabeth Lee', 'elizabeth.lee@example.com', '5555559876'),
(22, 'Benjamin Taylor', 'benjamin.taylor@example.com', '5551230987'),
(23, 'Rachel Kim', 'rachel.kim@example.com', '5555555555'),
(24, 'Alexander Nguyen', 'alexander.nguyen@example.com', '5559876123'),
(25, 'Madison Martin', 'madison.martin@example.com', '5551234567'),
(26, 'Gabriella Brown', 'gabriella.brown@example.com', '5559876543'),
(27, 'Julian Davis', 'julian.davis@example.com', '5555551234'),
(28, 'Sofia Lee', 'sofia.lee@example.com', '5555559876'),
(29, 'Ethan Taylor', 'ethan.taylor@example.com', '5551230987'),
(30, 'Lily Kim', 'lily.kim@example.com', '5555555555'),
(31, 'Olivia Nguyen', 'olivia.nguyen@example.com', '5559876123'),
(32, 'Ava Martin', 'ava.martin@example.com', '5551234567'),
(33, 'Isabella Brown', 'isabella.brown@example.com', '5559876543'),
(34, 'Logan Davis', 'logan.davis@example.com', '5555551234'),
(35, 'Evelyn Lee', 'evelyn.lee@example.com', '5555559876'),
(36, 'Parker Taylor', 'parker.taylor@example.com', '5551230987'),
(37, 'Ruby Kim', 'ruby.kim@example.com', '5555555555'),
(38, 'Caleb Nguyen', 'caleb.nguyen@example.com', '5559876123'),
(39, 'Lila Martin', 'lila.martin@example.com', '5551234567'),
(40, 'Gavin Brown', 'gavin.brown@example.com', '5559876543'),
(41, 'Harrison Davis', 'harrison.davis@example.com', '5555551234'),
(42, 'Aubrey Lee', 'aubrey.lee@example.com', '5555559876'),
(43, 'Rowan Taylor', 'rowan.taylor@example.com', '5551230987'),
(44, 'Sage Kim', 'sage.kim@example.com', '5555555555'),
(45, 'Riley Nguyen', 'riley.nguyen@example.com', '5559876123'),
(46, 'Paisley Martin', 'paisley.martin@example.com', '5551234567'),
(47, 'Remi Brown', 'remi.brown@example.com', '5559876543'),
(48, 'Cameron Davis', 'cameron.davis@example.com', '5555551234'),
(49, 'Everley Lee', 'everley.lee@example.com', '5555559876'),
(50, 'August Taylor', 'august.taylor@example.com', '5551230987');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `departure_airport_id` int(11) NOT NULL,
  `arrival_airport_id` int(11) NOT NULL,
  `distance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `departure_airport_id`, `arrival_airport_id`, `distance`) VALUES
(1, 1, 2, 560),
(2, 2, 1, 560),
(3, 1, 3, 2500),
(4, 3, 1, 2500),
(5, 2, 4, 1800),
(6, 4, 2, 1800),
(7, 1, 5, 2300),
(8, 5, 1, 2300),
(9, 2, 6, 1700),
(10, 6, 2, 1700),
(11, 1, 7, 2000),
(12, 7, 1, 2000),
(13, 2, 8, 2600),
(14, 8, 2, 2600),
(15, 1, 9, 1000),
(16, 9, 1, 1000),
(17, 2, 10, 2400),
(18, 10, 2, 2400),
(19, 1, 11, 1200),
(20, 11, 1, 1200),
(21, 2, 12, 1400),
(22, 12, 2, 1400),
(23, 1, 13, 2200),
(24, 13, 1, 2200),
(25, 2, 14, 2800),
(26, 14, 2, 2800),
(27, 1, 15, 2600),
(28, 15, 1, 2600),
(29, 2, 16, 2900),
(30, 16, 2, 2900),
(31, 1, 17, 1900),
(32, 17, 1, 1900),
(33, 2, 18, 2100),
(34, 18, 2, 2100),
(35, 1, 19, 2400),
(36, 19, 1, 2400),
(37, 2, 20, 2700),
(38, 20, 2, 2700),
(39, 1, 21, 2000),
(40, 21, 1, 2000),
(41, 2, 22, 2300),
(42, 22, 2, 2300),
(43, 1, 23, 1800),
(44, 23, 1, 1800),
(45, 2, 24, 2500),
(46, 24, 2, 2500),
(47, 1, 25, 2200),
(48, 25, 1, 2200),
(49, 2, 26, 2600),
(50, 26, 2, 2600);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `departure_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `arrival_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `flight_id`, `departure_time`, `arrival_time`) VALUES
(1, 1, '2022-01-01 02:00:00', '2022-01-01 03:00:00'),
(2, 2, '2022-01-01 04:00:00', '2022-01-01 05:00:00'),
(3, 3, '2022-01-01 06:00:00', '2022-01-01 13:00:00'),
(4, 4, '2022-01-01 14:00:00', '2022-01-01 21:00:00'),
(5, 5, '2022-01-01 08:00:00', '2022-01-01 13:00:00'),
(6, 6, '2022-01-01 14:00:00', '2022-01-01 19:00:00'),
(7, 7, '2022-01-01 10:00:00', '2022-01-01 19:00:00'),
(8, 8, '2022-01-01 20:00:00', '2022-01-02 03:00:00'),
(9, 9, '2022-01-01 12:00:00', '2022-01-01 19:00:00'),
(10, 10, '2022-01-01 20:00:00', '2022-01-02 01:00:00'),
(11, 11, '2022-01-01 14:00:00', '2022-01-01 23:00:00'),
(12, 12, '2022-01-02 00:00:00', '2022-01-02 05:00:00'),
(13, 13, '2022-01-01 16:00:00', '2022-01-02 01:00:00'),
(14, 14, '2022-01-02 02:00:00', '2022-01-02 09:00:00'),
(15, 15, '2021-12-31 18:00:00', '2021-12-31 21:00:00'),
(16, 16, '2021-12-31 22:00:00', '2022-01-01 01:00:00'),
(17, 17, '2021-12-31 20:00:00', '2022-01-01 01:00:00'),
(18, 18, '2022-01-01 02:00:00', '2022-01-01 07:00:00'),
(19, 19, '2022-01-01 00:00:00', '2022-01-01 03:00:00'),
(20, 20, '2022-01-01 04:00:00', '2022-01-01 07:00:00'),
(21, 21, '2022-01-01 06:00:00', '2022-01-01 13:00:00'),
(22, 22, '2022-01-01 14:00:00', '2022-01-01 21:00:00'),
(23, 23, '2022-01-01 08:00:00', '2022-01-01 11:00:00'),
(24, 24, '2022-01-01 12:00:00', '2022-01-01 15:00:00'),
(25, 25, '2022-01-01 10:00:00', '2022-01-01 13:00:00'),
(26, 26, '2022-01-01 14:00:00', '2022-01-01 19:00:00'),
(27, 27, '2022-01-01 12:00:00', '2022-01-01 15:00:00'),
(28, 28, '2022-01-01 16:00:00', '2022-01-01 19:00:00'),
(29, 29, '2022-01-01 14:00:00', '2022-01-01 19:00:00'),
(30, 30, '2022-01-01 20:00:00', '2022-01-01 23:00:00'),
(31, 31, '2022-01-01 16:00:00', '2022-01-01 19:00:00'),
(32, 32, '2022-01-01 20:00:00', '2022-01-01 23:00:00'),
(33, 33, '2021-12-31 18:00:00', '2022-01-01 01:00:00'),
(34, 34, '2022-01-01 02:00:00', '2022-01-01 09:00:00'),
(35, 35, '2021-12-31 20:00:00', '2022-01-01 03:00:00'),
(36, 36, '2022-01-01 04:00:00', '2022-01-01 11:00:00'),
(37, 37, '2021-12-31 22:00:00', '2022-01-01 05:00:00'),
(38, 38, '2022-01-01 06:00:00', '2022-01-01 13:00:00'),
(39, 39, '2022-01-01 00:00:00', '2022-01-01 07:00:00'),
(40, 40, '2022-01-01 08:00:00', '2022-01-01 15:00:00'),
(41, 41, '2022-01-01 02:00:00', '2022-01-01 09:00:00'),
(42, 42, '2022-01-01 10:00:00', '2022-01-01 17:00:00'),
(43, 43, '2022-01-01 04:00:00', '2022-01-01 11:00:00'),
(44, 44, '2022-01-01 12:00:00', '2022-01-01 19:00:00'),
(45, 45, '2022-01-01 06:00:00', '2022-01-01 13:00:00'),
(46, 46, '2022-01-01 14:00:00', '2022-01-01 21:00:00'),
(47, 47, '2022-01-01 08:00:00', '2022-01-01 15:00:00'),
(48, 48, '2022-01-01 16:00:00', '2022-01-01 23:00:00'),
(49, 49, '2022-01-01 10:00:00', '2022-01-01 17:00:00'),
(50, 50, '2022-01-01 18:00:00', '2022-01-02 01:00:00');
