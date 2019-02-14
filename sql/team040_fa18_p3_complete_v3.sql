
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

CREATE TABLE `User`(
    Email VARCHAR(50) NOT NULL,
    Pin INT(4) NOT NULL,
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
    Description VARCHAR(200) NOT NULL,
    PRIMARY KEY (Email, Title, Add_Datetime),
    FOREIGN KEY (Email, Title)
        REFERENCES `CorkBoard`(Email,Title)
ON DELETE CASCADE ON UPDATE CASCADE);
 
CREATE TABLE `PushPin_Tag`(
    Email VARCHAR(50) NOT NULL,
    Title VARCHAR(50) NOT NULL,
    Add_Datetime DATETIME NOT NULL,
    Tag VARCHAR(20) NULL,
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
      ('xxiao4@hotmail.com','5811','xxiao4'),
	  ('xxiao5@hotmail.com','5811','xxiao5'),
	  ('xxiao6@hotmail.com','5811','xxiao6');

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
	  
insert into `Follow`
values('xxiao1@hotmail.com','xxiao2@hotmail.com'),
      ('xxiao1@hotmail.com','xxiao3@hotmail.com'),
      ('xxiao2@hotmail.com','xxiao3@hotmail.com'),
	  ('xxiao2@hotmail.com','xxiao4@hotmail.com'),
	  ('xxiao3@hotmail.com','xxiao5@hotmail.com'),
	  ('xxiao3@hotmail.com','xxiao6@hotmail.com'),  
	  ('xxiao4@hotmail.com','xxiao2@hotmail.com'),
	  ('xxiao4@hotmail.com','xxiao1@hotmail.com'),	  
	  ('xxiao5@hotmail.com','xxiao6@hotmail.com'),
	  ('xxiao5@hotmail.com','xxiao3@hotmail.com'),
	  ('xxiao6@hotmail.com','xxiao1@hotmail.com'),
	  ('xxiao6@hotmail.com','xxiao3@hotmail.com');  
	  
	  

insert into CorkBoard
values('xxiao1@hotmail.com','Education-Sample-CorkBoard',1),
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard',8),

      ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard',2),

      ('xxiao3@hotmail.com','Sports-Sample-CorkBoard',7),
      ('xxiao3@hotmail.com','People-Sample-CorkBoard',4),

      ('xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard',11),
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard',9),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard',5);  
	  
	  
insert into Private_CorkBoard
values('xxiao1@hotmail.com','Pets-Sample-CorkBoard','5811'),
      ('xxiao3@hotmail.com','People-Sample-CorkBoard','5811');

insert into Public_CorkBoard
values('xxiao1@hotmail.com','Education-Sample-CorkBoard'),
      ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard'),
      ('xxiao3@hotmail.com','Sports-Sample-CorkBoard'),
      ('xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard'),
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard'),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard');


insert into Watch
values('xxiao1@hotmail.com','xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard'),
      ('xxiao1@hotmail.com','xxiao5@hotmail.com','Technology-Sample-CorkBoard'),
	  
      ('xxiao2@hotmail.com','xxiao5@hotmail.com','Technology-Sample-CorkBoard'),
      ('xxiao2@hotmail.com','xxiao6@hotmail.com','Travel-Sample-CorkBoard'),	  
	  
      ('xxiao3@hotmail.com','xxiao1@hotmail.com','Education-Sample-CorkBoard'),	  
      ('xxiao3@hotmail.com','xxiao2@hotmail.com','Architecture-Sample-CorkBoard'),
	  
      ('xxiao4@hotmail.com','xxiao3@hotmail.com','Sports-Sample-CorkBoard'),	  
      ('xxiao4@hotmail.com','xxiao6@hotmail.com','Travel-Sample-CorkBoard'),	  
	  
      ('xxiao5@hotmail.com','xxiao1@hotmail.com','Education-Sample-CorkBoard'),	  
      ('xxiao5@hotmail.com','xxiao2@hotmail.com','Architecture-Sample-CorkBoard'),		  
	  
      ('xxiao6@hotmail.com','xxiao2@hotmail.com','Architecture-Sample-CorkBoard'),	  
      ('xxiao6@hotmail.com','xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard');	  
	    

insert into PushPin(Email,Title,Add_Datetime,Description,URL)
values('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 13:17:17','OMSCS program logo','https://www.cc.gatech.edu/sites/default/files/images/mercury/oms-cs-web-rotator_2_0_3.jpeg'),
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 14:17:17','student ID for Georgia Tech','http://www.buzzcard.gatech.edu/sites/default/files/uploads/images/superblock_images/img_2171.jpg'),
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 15:17:17','logo for Piazza','https://www.news.gatech.edu/sites/default/files/uploads/mercury_images/piazza-icon.png'),
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 16:18:17','official seal of Georgia Tech','http://www.comm.gatech.edu/sites/default/files/images/brand-graphics/gt-seal.png'),
	  
	  ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 14:17:17','Buzz','https://hr.gatech.edu/sites/default/files/uploads/images/superblock_images/nee-buzz.jpg'),
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 15:17:17','Uga the "dog"','https://georgiadogs.com/images/2018/4/6/18_Uga_VIII.jpg'),
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 16:18:17','Sideways the dog','https://www.news.gatech.edu/sites/default/files/pictures/feature_images/running%20sideways.jpg'),  
	  
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:11','Tech Tower interior photo','http://daily.gatech.edu/sites/default/files/styles/1170_x_x/public/hgt-tower-crop.jpg'), 
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:12','Tech Tower exterior photo','http://www.livinghistory.gatech.edu/s/1481/images/content_images/techtower1_636215523842964533.jpg'), 	  
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:13','Kessler Campanile at Georgia Tech','https://www.ece.gatech.edu/sites/default/files/styles/1500_x_scale/public/images/mercury/kessler2.0442077-p16-49.jpg'), 	  
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:14','Klaus building','https://www.scs.gatech.edu/sites/scs.gatech.edu/files/files/klaus-building.jpg'), 	  
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:15','Tech tower sign','https://www.news.gatech.edu/sites/default/files/styles/740_x_scale/public/uploads/mercury_images/Tech_Tower_WebFeature_1.jpg'), 	  
  
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 12:18:14','Ramblin'' wreck today','http://traditions.gatech.edu/images/mantle-reck3.jpg'), 
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 13:18:14','Driving the mini wreck','http://www.swag.gatech.edu/sites/default/files/buzz-android-tablet.jpg'), 	  
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 14:18:14','Ramblin'' Wreck of the past','http://www.livinghistory.gatech.edu/s/1481/images/content_images/ramblinwreck1_636215542678295357.jpg'),   
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 15:18:14','Bobby Dodd stadium','https://www.news.gatech.edu/sites/default/files/uploads/mercury_images/screen_shot_2016-08-11_at_12.45.48_pm_10.png'), 

	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:11:14','the struggle is real!','http://www.me.gatech.edu/sites/default/files/styles/180_240/public/gpburdell.jpg'), 
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:12:14','Leo Mark, CS 6400 professor','https://www.cc.gatech.edu/projects/XMLApe/people/imgs/leo.jpg'), 
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:13:14','fearless leader of OMSCS','https://www.cc.gatech.edu/sites/default/files/images/27126038747_06d417015b_z.jpg'), 	

      ('xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard','2018-11-8 10:11:11','The Varsity','http://www.livinghistory.gatech.edu/s/1481/images/content_images/thevarsity1_636215546286483906.jpg'),
      ('xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard','2018-11-8 11:12:12','Chick-fil-a Waffle Fries','http://blogs.iac.gatech.edu/food14/files/2014/09/wafflefries2.jpg'),

      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-8 9:11:11','iMac','http://it.studentlife.gatech.edu/sites/default/files/uploads/images/superblock_images/it_imac.png'),	  
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-9 9:12:12','Computer lab','https://pe.gatech.edu/sites/pe.gatech.edu/files/component_assets/Computer_Lab_Tech_750_x_500.jpg'),	  
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-10 9:13:13','Database server','https://www.scs.gatech.edu/sites/scs.gatech.edu/files/files/cs-research-databases.jpg'),	  

      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-18 9:13:15','DGeorgia Tech Transette','https://pbs.twimg.com/media/DZzi7dyU8AAUSJe.jpg'),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-10 9:14:12','Mini 500','https://www.calendar.gatech.edu/sites/default/files/events/related-images/mini_500_0_0.jpg'),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-12 9:11:19','Tech Trolley','https://www.gatech.edu/sites/default/files/uploads/images/superblock_images/tech-trolly.png');
	  
	 
insert into PushPin_Tag
values('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 13:17:17','OMSCS'),
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 14:17:17','buzzcard'),
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 15:17:17','Piazza'),
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 16:18:17','Georgia tech seal'),
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 16:18:17','great seal'),	  
      ('xxiao1@hotmail.com','Education-Sample-CorkBoard','2018-11-18 16:18:17','official'),	  
	  
	  ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 14:17:17','mascot'),
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 15:17:17','tohellwithgeorgia'),
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 15:17:17','dawgs'),	  
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 15:17:17','not our mascot'),		    
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 16:18:17','mascot'),  
      ('xxiao1@hotmail.com','Pets-Sample-CorkBoard','2018-11-19 16:18:17','traditions'), 	  
	  
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:11','administration building'), 
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:11','facilities'), 	  
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:12','administration building'), 	
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:12','facilities'), 
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:14','student facilities'), 	
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:14','computing'), 	
	  ('xxiao2@hotmail.com','Architecture-Sample-CorkBoard','2018-11-20 16:18:14','gtcomputing'), 	 
	  
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 12:18:14','tohellwithgeorgia'), 
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 12:18:14','decked out'), 	  
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 12:18:14','parade'), 	  
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 13:18:14','ramblin wreck'), 	
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 13:18:14','buzz'), 	
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 13:18:14','mascot'), 	
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 13:18:14','parade'), 	
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 14:18:14','football game'), 
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 14:18:14','parade'), 
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 15:18:14','football'), 
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 15:18:14','game day'), 
	  ('xxiao3@hotmail.com','Sports-Sample-CorkBoard','2018-11-21 15:18:14','tohellwithgeorgia'), 
	  
	  
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:11:14','burdell'),
      ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:11:14','george p burdell'),
      ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:11:14','student'), 
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:12:14','database faculty'), 
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:12:14','computing'), 	  
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:12:14','gtcomputing'), 	   
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:13:14','zvi'), 
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:13:14','dean'), 	  
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:13:14','computer science'), 	  
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:13:14','computing'), 	  
	  ('xxiao3@hotmail.com','People-Sample-CorkBoard','2018-11-22 10:13:14','gtcomputing'), 	  
	  

      ('xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard','2018-11-8 10:11:11','traditions'),
      ('xxiao4@hotmail.com','Food/Drink-Sample-CorkBoard','2018-11-8 11:12:12','delicious'),

      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-8 9:11:11','Macintosh'),	 
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-8 9:11:11','computer'),	
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-8 9:11:11','macOS'),	  
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-9 9:12:12','PCs'),	
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-9 9:12:12','student facilities'),
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-9 9:12:12','gtcomputing'),
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-10 9:13:13','computing'),	  
      ('xxiao5@hotmail.com','Technology-Sample-CorkBoard','2018-7-10 9:13:13','blades'),	  
	  
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-18 9:13:15','personal rapid transit'),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-18 9:13:15','historical oddity'),	  	    
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-10 9:14:12','tricycle'),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-10 9:14:12','race'),	  
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-10 9:14:12','traditions'),	  
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-12 9:11:19','free'),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-12 9:11:19','transit'),
      ('xxiao6@hotmail.com','Travel-Sample-CorkBoard','2018-8-12 9:11:19','connections');












