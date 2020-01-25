CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `odminUserID` int(11) NULL DEFAULT NULL,
  `identity` VARCHAR(32) NULL DEFAULT NULL,
  `valid` BOOLEAN NOT NULL DEFAULT FALSE,
  `config` text NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users` ADD PRIMARY KEY (`userID`);
ALTER TABLE `users` ADD UNIQUE(`odminUserID`);
ALTER TABLE `users` MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

CREATE TABLE `sessions` (
  `sessionID` varchar(80) NOT NULL,
  `userID` int(11) NOT NULL,
  `valid` BOOLEAN NOT NULL DEFAULT FALSE,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `sessions` ADD PRIMARY KEY (`sessionID`);
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

CREATE TABLE `groups` (
  `groupID` int(11) NOT NULL,
  `groupName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `groups` ADD PRIMARY KEY (`groupID`);
ALTER TABLE `groups` MODIFY `groupID` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

CREATE TABLE `subGroups` (
  `subGroupID` int(11) NOT NULL,
  `subGroupName` varchar(30) NOT NULL,
  `groupID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `subGroups` ADD PRIMARY KEY (`subGroupID`);
ALTER TABLE `subgroups` ADD INDEX(`groupID`);
ALTER TABLE `subGroups` MODIFY `subGroupID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `subgroups`
  ADD CONSTRAINT `subgroups_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

CREATE TABLE `lists` (
  `listID` int(11) NOT NULL,
  `listName` varchar(30) NOT NULL,
  `author` varchar(30) NOT NULL,
  `homepage` varchar(80) NOT NULL,
  `aTitel` varchar(30) NOT NULL,
  `bTitel` varchar(30) NOT NULL,
  `subGroupID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `lists` ADD PRIMARY KEY (`listID`);
ALTER TABLE `lists` ADD INDEX(`subGroupID`);
ALTER TABLE `lists` MODIFY `listID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `lists`
  ADD CONSTRAINT `lists_ibfk_1` FOREIGN KEY (`subGroupID`) REFERENCES `subgroups` (`subGroupID`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

CREATE TABLE `items` (
  `itemID` int(11) NOT NULL,
  `a` text NOT NULL,
  `b` text NOT NULL,
  `listID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `items` ADD PRIMARY KEY (`itemID`);
ALTER TABLE `items` ADD INDEX(`listID`);
ALTER TABLE `items` MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`listID`) REFERENCES `lists` (`listID`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

CREATE TABLE `historys` (
  `historyDBID` int(11) NOT NULL,
  `historyID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `box` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `historys` ADD PRIMARY KEY (`historyDBID`);
ALTER TABLE `historys` ADD INDEX(`userID`);
ALTER TABLE `historys` ADD INDEX(`itemID`);
ALTER TABLE `historys` MODIFY `historyDBID` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `historys`
  ADD CONSTRAINT `historys_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `historys_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE;