
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_fa18_team040`;
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_fa18_team040
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE cs6400_fa18_team040;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_fa18_team040`.* TO 'gatechUser'@'localhost';

FLUSH PRIVILEGES;

/* Tables */
CREATE TABLE `User`(
    Email VARCHAR(50) NOT NULL,
    Pin VARCHAR(4) NOT NULL,
    Name VARCHAR(50) NOT NULL,
    PRIMARY KEY (Email)
);
 
CREATE TABLE `Follow`(
    Email VARCHAR(50) NOT NULL,
    Followed_Email VARCHAR(50) NOT NULL,
    PRIMARY KEY (Email, Followed_Email),
    FOREIGN KEY (Email)
        REFERENCES `User`(Email),
    FOREIGN KEY (Followed_Email)
        REFERENCES `User`(Email)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `Category`(
    CategoryID VARCHAR(50) NOT NULL,
    Category VARCHAR(50) NOT NULL,
    PRIMARY KEY (CategoryID)
);
 
CREATE TABLE `CorkBoard`(
    Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    CategoryID VARCHAR(50) NOT NULL,
    PRIMARY KEY (Email, Title),
    FOREIGN KEY (Email)
        REFERENCES User(Email),
    FOREIGN KEY (CategoryID)
        REFERENCES `Category`(CategoryID)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `Private_CorkBoard`(
    Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    PRIMARY KEY (Email, Title),
    FOREIGN KEY (Email, Title)
        REFERENCES `CorkBoard`(Email, Title)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `Public_CorkBoard`(
    Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    PRIMARY KEY (Email, Title),
    FOREIGN KEY (Email, Title)
        REFERENCES `CorkBoard`(Email, Title)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `Watch`(
    Email VARCHAR(50) NOT NULL,
    Watched_Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    PRIMARY KEY (Email, Watched_Email, Title),
    FOREIGN KEY (Email)
        REFERENCES `User`(Email),
    FOREIGN KEY (Watched_Email, Title)
        REFERENCES `CorkBoard`(Email,Title)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `PushPin`(
    Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    Add_Datetime DATETIME NOT NULL,
    URL VARCHAR(255) NOT NULL,
    Description VARCHAR(255) NOT NULL,
    PRIMARY KEY (Email, Title, Add_Datetime),
    FOREIGN KEY (Email, Title)
        REFERENCES `CorkBoard`(Email,Title)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `PushPin_Tag`(
    Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    Add_Datetime DATETIME NOT NULL,
    Tag VARCHAR(50) NULL,
    UNIQUE (Email, Title, Add_Datetime,Tag),
    FOREIGN KEY (Email, Title, Add_Datetime)
        REFERENCES `PushPin`(Email,Title, Add_Datetime)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `Liked`(
    Email VARCHAR(50) NOT NULL,
    Liked_Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    Add_Datetime DATETIME NOT NULL,
    PRIMARY KEY (Email, Liked_Email, Title, Add_Datetime),
    FOREIGN KEY (Email)
        REFERENCES `User`(Email),
    FOREIGN KEY (Liked_Email, Title, Add_Datetime)
        REFERENCES `PushPin`(Email,Title, Add_Datetime)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `Comment`(
    Email VARCHAR(50) NOT NULL,
    Commented_Email  VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    Add_Datetime DATETIME NOT NULL,
    Comment_Datetime  DATETIME NOT NULL,
    Comment VARCHAR(255) NOT NULL,
    PRIMARY KEY (Email, Commented_Email, Title, Add_Datetime, Comment_Datetime),
    FOREIGN KEY (Email)
        REFERENCES `User`(Email),
    FOREIGN KEY (Commented_Email, Title, Add_Datetime)
        REFERENCES `PushPin`(Email,Title, Add_Datetime)
ON DELETE CASCADE ON UPDATE CASCADE);


insert into User(Email,Pin,Name)
values('xxiao1@hotmail.com','5811','xxiao1'),
      ('xxiao2@hotmail.com','5811','xxiao2'),
      ('xxiao3@hotmail.com','5811','xxiao3'),
      ('xxiao4@hotmail.com','5811','xxiao4');

	  /*
--xxiao1 follow xxiao2
--xxiao1 follow xxiao3
--xxiao2 follow xxiao3
      */

insert into `Follow`
values('xxiao1@hotmail.com','xxiao2@hotmail.com'),
      ('xxiao1@hotmail.com','xxiao3@hotmail.com'),
      ('xxiao2@hotmail.com','xxiao3@hotmail.com');

insert into Category
values(1,'Education'),
      (2,'Architecture'),
      (3,'Home & Garden'),	
      (4,'People'),
      (5,'Travel'),
      (6,'Photography'),
      (7,'Sports'),
      (8,'Pets'),
      (9,'Technology'),
      (10,'Other'),
      (11,'Food & Drink'),
      (12,'Art');
	  
insert into CorkBoard
values('xxiao1@hotmail.com','xxiao1-test1',1),
      ('xxiao1@hotmail.com','xxiao1-test2',2), /*private*/
      ('xxiao2@hotmail.com','xxiao2-test1',3),
      ('xxiao2@hotmail.com','xxiao2-test2',4), /*private*/
      ('xxiao3@hotmail.com','xxiao3-test1',5),
      ('xxiao3@hotmail.com','xxiao3-test2',6),
      ('xxiao4@hotmail.com','xxiao4-test1',7),
      ('xxiao4@hotmail.com','xxiao4-test2',8);

insert into Private_CorkBoard
values('xxiao1@hotmail.com','xxiao1-test2','5811'),
      ('xxiao2@hotmail.com','xxiao2-test2','5811');

insert into Public_CorkBoard
values('xxiao1@hotmail.com','xxiao1-test1'),
      ('xxiao2@hotmail.com','xxiao2-test1'),
      ('xxiao3@hotmail.com','xxiao3-test1'),
      ('xxiao3@hotmail.com','xxiao3-test2'),
      ('xxiao4@hotmail.com','xxiao4-test1'),
      ('xxiao4@hotmail.com','xxiao4-test2');

/*
--xxiao1 watch xxiao2's test1
--xxiao3 watch xxiao1's test1
--xxiao1 watch xxiao3's test1
*/

insert into Watch
values('xxiao1@hotmail.com','xxiao2@hotmail.com','xxiao2-test1'),
      ('xxiao1@hotmail.com','xxiao3@hotmail.com','xxiao3-test1'),
      ('xxiao3@hotmail.com','xxiao1@hotmail.com','xxiao1-test1');

insert into PushPin(Email,Title,Add_Datetime,Description,URL)
values('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17','xxiao1-test1-pushpin1','https://image.freepik.com/free-vector/animals-cartoon-collection_1042-39.jpg'),
      ('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 18:17:17','xxiao1-test1-pushpin2','https://image.freepik.com/free-vector/baby-horse-animal-in-cartoon-style_72147496402.jpg'),
      ('xxiao1@hotmail.com','xxiao1-test2','2011-12-18 15:17:17','xxiao1-test2-pushpin1','https://image.freepik.com/free-vector/cartoon-ostrich-animal-in-the-jungle_72147496458.jpg'),
      ('xxiao1@hotmail.com','xxiao1-test2','2011-12-18 18:18:17','xxiao1-test2-pushpin2','https://image.freepik.com/free-vector/cartoon-animals-and-bugs-vectors_7874.jpg'),

      ('xxiao2@hotmail.com','xxiao2-test1','2011-12-18 11:17:17','xxiao2-test1-pushpin1','https://image.freepik.com/free-vector/cartoon-mice-collection_23-2147743732.jpg'),
      ('xxiao2@hotmail.com','xxiao2-test1','2011-12-18 12:17:17','xxiao2-test1-pushpin2','https://image.freepik.com/free-vector/stone-age-woman-in-animal-hide-pelt_3446-332.jpg'),
      ('xxiao2@hotmail.com','xxiao2-test2','2011-12-18 13:17:17','xxiao2-test2-pushpin1','https://image.freepik.com/free-vector/elephant-cartoon-in-countryside_1017-345.jpg'),
      ('xxiao2@hotmail.com','xxiao2-test2','2011-12-18 14:18:17','xxiao2-test2-pushpin2','https://image.freepik.com/free-vector/illustration-of-alphabet-letter-with-animal-picture_53876-20570.jpg'),

      ('xxiao3@hotmail.com','xxiao3-test1','2011-12-18 11:11:17','xxiao3-test1-pushpin1','https://image.freepik.com/free-vector/cartoon-turkey-vector-illustration_439-2147502091.jpg'),
      ('xxiao3@hotmail.com','xxiao3-test1','2011-12-18 12:12:17','xxiao3-test1-pushpin2','https://static.vecteezy.com/system/resources/previews/000/071/662/non_2x/vector-ladybird-cartoon.jpg'),
      ('xxiao3@hotmail.com','xxiao3-test2','2011-12-18 13:13:17','xxiao3-test2-pushpin1','https://image.freepik.com/free-vector/cute-animal-heads_1042-41.jpg'),
      ('xxiao3@hotmail.com','xxiao3-test2','2011-12-18 14:14:17','xxiao3-test2-pushpin2','https://static.vecteezy.com/system/resources/previews/000/073/369/non_2x/sad-teddy-bear-vector.jpg'),

      ('xxiao4@hotmail.com','xxiao4-test1','2011-12-18 11:11:11','xxiao4-test1-pushpin1','https://image.freepik.com/free-vector/animals-cartoon-collection_1042-39.jpg'),
      ('xxiao4@hotmail.com','xxiao4-test1','2011-12-18 12:12:12','xxiao4-test1-pushpin2','https://image.freepik.com/free-vector/cartoon-mice-collection_23-2147743732.jpg'),
      ('xxiao4@hotmail.com','xxiao4-test2','2011-12-18 13:13:13','xxiao4-test2-pushpin1','https://image.freepik.com/free-vector/cute-animal-heads_1042-41.jpg'),
      ('xxiao4@hotmail.com','xxiao4-test2','2011-12-18 14:14:14','xxiao4-test2-pushpin2','https://static.vecteezy.com/system/resources/previews/000/073/369/non_2x/sad-teddy-bear-vector.jpg');

insert into PushPin_Tag
values('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17','good'),
      ('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17','best'),
      ('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17','normal'),

      ('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 18:17:17','good'),
      ('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 18:17:17','bad'),
      ('xxiao1@hotmail.com','xxiao1-test1','2011-12-18 18:17:17','better'),

      ('xxiao1@hotmail.com','xxiao1-test2','2011-12-18 15:17:17','better'),
      ('xxiao1@hotmail.com','xxiao1-test2','2011-12-18 15:17:17','bad'),

      ('xxiao1@hotmail.com','xxiao1-test2','2011-12-18 18:18:17','best'),
      ('xxiao1@hotmail.com','xxiao1-test2','2011-12-18 18:18:17','good'),

      ('xxiao2@hotmail.com','xxiao2-test1','2011-12-18 11:17:17','best'),
      ('xxiao2@hotmail.com','xxiao2-test1','2011-12-18 11:17:17','normal'),

      ('xxiao2@hotmail.com','xxiao2-test1','2011-12-18 12:17:17','normal'),
      ('xxiao2@hotmail.com','xxiao2-test1','2011-12-18 12:17:17','better'),

      ('xxiao2@hotmail.com','xxiao2-test2','2011-12-18 13:17:17','better'),
      ('xxiao2@hotmail.com','xxiao2-test2','2011-12-18 13:17:17','normal'),
      ('xxiao2@hotmail.com','xxiao2-test2','2011-12-18 13:17:17','bad'),

      ('xxiao2@hotmail.com','xxiao2-test2','2011-12-18 14:18:17','bad'),
      ('xxiao2@hotmail.com','xxiao2-test2','2011-12-18 14:18:17','best'),

      ('xxiao3@hotmail.com','xxiao3-test1','2011-12-18 11:11:17','normal'),
      ('xxiao3@hotmail.com','xxiao3-test1','2011-12-18 11:11:17','bad'),

      ('xxiao3@hotmail.com','xxiao3-test1','2011-12-18 12:12:17','better'),
      ('xxiao3@hotmail.com','xxiao3-test1','2011-12-18 12:12:17','good'),

      ('xxiao3@hotmail.com','xxiao3-test2','2011-12-18 13:13:17','normal'),
      ('xxiao3@hotmail.com','xxiao3-test2','2011-12-18 13:13:17','best'),

      ('xxiao3@hotmail.com','xxiao3-test2','2011-12-18 14:14:17','better'),
      ('xxiao3@hotmail.com','xxiao3-test2','2011-12-18 14:14:17','normal'),

      ('xxiao4@hotmail.com','xxiao4-test1','2011-12-18 11:11:11','best'),
      ('xxiao4@hotmail.com','xxiao4-test1','2011-12-18 11:11:11','normal'),

      ('xxiao4@hotmail.com','xxiao4-test1','2011-12-18 12:12:12','best'),
      ('xxiao4@hotmail.com','xxiao4-test1','2011-12-18 12:12:12','bad'),

      ('xxiao4@hotmail.com','xxiao4-test2','2011-12-18 13:13:13','normal'),
      ('xxiao4@hotmail.com','xxiao4-test2','2011-12-18 13:13:13','worst'),

      ('xxiao4@hotmail.com','xxiao4-test2','2011-12-18 14:14:14','worst'),
      ('xxiao4@hotmail.com','xxiao4-test2','2011-12-18 14:14:14','normal');

insert into Liked
values('xxiao2@hotmail.com','xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17'),
      ('xxiao3@hotmail.com','xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17'),
      ('xxiao4@hotmail.com','xxiao3@hotmail.com','xxiao3-test1','2011-12-18 11:11:17'),
      ('xxiao1@hotmail.com','xxiao3@hotmail.com','xxiao3-test2','2011-12-18 14:14:17');

insert into `Comment`
values('xxiao1@hotmail.com','xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17','2011-12-19 13:17:17','own is good'),
      ('xxiao4@hotmail.com','xxiao1@hotmail.com','xxiao1-test1','2011-12-18 13:17:17','2011-12-21 13:17:17','xxiao4-xxiao1 is good');










