create table if not exists ipl_actions
(
	`ID` int(11) NOT NULL auto_increment,
    `NAME` varchar(255) NOT NULL,
	`ACTION` varchar(255) NOT NULL,
    `TYPE` varchar(255) NOT NULL,
    `ACTIVE` char(1) NOT NULL default 'Y',
    `SORT` int(10) NOT NULL default 100,
    `BACKGROUND` char(1) NOT NULL default 'N',
    `NEW_WINDOW` char(1) NOT NULL default 'Y',
    `GROUP` int(10) NOT NULL default '0',
	PRIMARY KEY (`ID`)
);

create table if not exists ipl_actions_group
(
    `ID` int(11) NOT NULL auto_increment,
    `NAME` varchar(255) NOT NULL,
    `DESCRIPTION` varchar(255) NULL,
    `ACTIVE` char(1) NOT NULL default 'Y',
    `SORT` int(10) NOT NULL default 100,
    PRIMARY KEY (`ID`)
);

create table if not exists ipl_actions_rights
(
    ID int(10) NOT NULL auto_increment,
    ENTITY_TYPE varchar(20) NOT NULL,
    ENTITY_ID int(10) NOT NULL,
    GROUP_ID int(10) NOT NULL,
    TASK_ID int(10) NOT NULL,
    PRIMARY KEY (ID),
    INDEX (ENTITY_ID),
    INDEX (GROUP_ID)
);