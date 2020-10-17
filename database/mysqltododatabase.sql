--
-- Database: `mysqltododatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `UserID`, `Name`, `Deleted`) VALUES
(1, 1, 'Sample Category 1', 0),
(2, 1, 'Sample Category 2', 0),
(3, 1, 'Sample Category 3', 0);

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE `todo` (
  `TodoID` int(10) NOT NULL,
  `UserID` int(10) NOT NULL,
  `CategoryID` int(10) NOT NULL,
  `Task` varchar(100) NOT NULL,
  `DueDate` date NOT NULL,
  `CompletedDate` date DEFAULT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`TodoID`, `UserID`, `CategoryID`, `Task`, `DueDate`, `CompletedDate`, `Deleted`) VALUES
(1, 1, 1, 'Sample Task 1', '2020-11-16', NULL, 0),
(2, 1, 2, 'Sample Task 2', '2020-10-16', NULL, 0),
(3, 1, 3, 'Sample Task 3', '2020-10-16', '2020-10-16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(10) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Deleted`) VALUES
(1, 'Sample User 1', '$2y$10$UqCwdCgMKzXCDAV53gptxeO/bNmi2fhiV64S6kDm/1bqb9yt8g5W6', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`TodoID`),
  ADD KEY `FK_Todo_User` (`UserID`),
  ADD KEY `FK_Todo_Category` (`CategoryID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `todo`
--
ALTER TABLE `todo`
  MODIFY `TodoID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_Category_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `todo`
--
ALTER TABLE `todo`
  ADD CONSTRAINT `FK_Todo_Category` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  ADD CONSTRAINT `FK_Todo_User` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
