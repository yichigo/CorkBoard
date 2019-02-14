/*written by xiao xiangjun CS6400-TEAM040*/

SELECT Name FROM User WHERE Email='xxiao1@hotmail.com';

/*My CorkBoards */

SELECT MAX(Email) AS Email, Title, COUNT(*) AS number_of_pushpins, MAX(Type) AS Type
FROM
(SELECT P.Title, C.Email,
CASE
        WHEN PB.Password IS NOT NULL THEN 'PRIVATE'
        ELSE 'PUBLIC'
END AS Type
FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_CorkBoard` AS PB ON C.Email=PB.Email AND C.Title=PB.Title INNER JOIN `Pushpin` AS P ON P.Email=C.Email AND P.Title=C.Title
WHERE C.Email='xxiao1@hotmail.com') AS TEMP
GROUP BY Title
ORDER BY Title;

/* watched or followed */

CREATE OR REPLACE VIEW Watch_List AS
(SELECT U.Name, U.Email, W.Title, P.Add_Datetime, 'PUBLIC' as Type
FROM `Watch` AS W INNER JOIN `Pushpin` AS P ON W.Watched_Email=P.Email AND W.Title=P.Title INNER JOIN `User` AS U ON U.Email=W.Watched_Email WHERE W.Email='xxiao1@hotmail.com');

#  Get the most recently updated datetime for each Corkborad

CREATE OR REPLACE VIEW Watch_List_recent AS
(SELECT MAX(Name) AS Name, MAX(Email) AS Email, MAX(Title) AS Title, MAX(Add_Datetime) AS updated_datetime, MAX(Type) AS Type FROM Watch_List GROUP BY Name, Title);

# For a User, his/her followed CockBoard list

CREATE OR REPLACE VIEW Follow_List AS
(SELECT Name, U.Email, C_ALL.Title, Add_Datetime, Type
FROM `Follow` AS F INNER JOIN
(SELECT C.Email, C.Title, 
        CASE
        WHEN P.Password IS NOT NULL THEN 'PRIVATE'
        ELSE 'PUBLIC'
        END AS Type 
FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_CorkBoard` AS P on C.Email=P.Email AND C.Title=P.Title) AS C_ALL ON C_ALL.Email=F.Followed_Email INNER JOIN `User` AS U ON U.Email=C_ALL.Email INNER JOIN `Pushpin` AS PUSH ON PUSH.Email=C_ALL.Email and PUSH.Title=C_ALL.Title WHERE F.Email='xxiao1@hotmail.com');

# Get the most recently updated datetime for each Corkborad

CREATE OR REPLACE VIEW Follow_List_recent AS
(SELECT MAX(Name) AS Name, MAX(Email) AS Email, MAX(Title) AS Title, MAX(Add_Datetime) AS updated_datetime, MAX(Type) AS Type FROM Follow_List GROUP BY Name, Title);

/* Union set of Watch_List_recent and Follow_List_recent, ordered reversely by updated datetime */

SELECT Name, Email, Title, updated_datetime, Type FROM
                        (SELECT * FROM Watch_List_recent
                        UNION 
                        SELECT * FROM Follow_List_recent) AS TEMP
                        ORDER BY updated_datetime DESC



/*Popular Tags*/
-- Only public corkboard
-- Create All_Tag containing all CockBoards with Pushpins

CREATE OR REPLACE VIEW All_Tag AS
(SELECT Tag, PP.Email, PP.Title, PP.Add_Datetime
FROM `Public_CorkBoard` AS PC INNER JOIN `CorkBoard` AS C ON PC.Email=C.Email AND PC.Title=C.Title INNER JOIN `PushPin` as PP ON PP.Email=PC.Email AND PP.Title=PC.Title INNER JOIN  `PushPin_Tag` AS PT ON PT.Email=PP.Email AND PT.Title=PP.Title AND PT.Add_Datetime=PP.Add_Datetime);

-- the number of PushPins and unique corkboards
SELECT TEMP1.Tag, number_pushpins, number_unique_cork FROM
(SELECT Tag, COUNT(*) as number_pushpins
FROM All_Tag
GROUP BY Tag) AS TEMP1 INNER JOIN (SELECT Tag, COUNT(*) as number_unique_cork FROM (SELECT DISTINCT Email, Title, Tag FROM `All_Tag`) AS TEMP
GROUP BY Tag) AS TEMP2 on TEMP1.Tag= TEMP2.Tag ORDER BY number_pushpins;

/* popular sites */

CREATE OR REPLACE VIEW All_Sites AS
(SELECT PP.Email, PP.Title, PP.Add_Datetime, SUBSTRING_INDEX(SUBSTRING_INDEX(URL, '/', 3), '/', -1) as Site
FROM `CorkBoard` AS C INNER JOIN `PushPin` as PP ON PP.Email=C.Email AND PP.Title=C.Title);

SELECT Site, COUNT(*) AS number_pushpins
FROM `All_Sites`
GROUP BY Site
ORDER BY COUNT(*) DESC;

/* statistics */

CREATE OR REPLACE VIEW Corkboard_ALL  AS
(SELECT C.Email, C.Title,
        CASE
        WHEN P.Password IS NOT NULL THEN 'PRIVATE'
        ELSE 'PUBLIC'
        END AS Type 
FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_CorkBoard` AS P on C.Email=P.Email AND C.Title=P.Title);

                   
/*  Create All_Pushpin */
CREATE OR REPLACE VIEW All_Pushpin AS
(SELECT PP.Email, PP.Title, PP.Add_Datetime, Name, Type
FROM Corkboard_ALL as CA INNER JOIN `PushPin` AS PP ON CA.Email=PP.Email AND CA.Title=PP.Title INNER JOIN `User` AS U ON U.Email=PP.Email);

/* the number of public CorkBoard */
CREATE OR REPLACE VIEW public_cork AS
(SELECT Name, COUNT(*) as public_cork
FROM (SELECT DISTINCT Email, Title, Name FROM All_Pushpin WHERE Type='PUBLIC') AS TEMP
GROUP BY Name);

/* the number of private CorkBoard */
CREATE OR REPLACE VIEW private_cork AS
(SELECT Name, COUNT(*) as private_cork
FROM (SELECT DISTINCT Email, Title, Name FROM All_Pushpin WHERE Type='PRIVATE') AS TEMP
GROUP BY Name);

/* the number of public PushPins */
CREATE OR REPLACE VIEW public_push AS
(SELECT Name, COUNT(*) as public_push
FROM (SELECT DISTINCT Email, Title, Add_Datetime, Name FROM All_Pushpin WHERE Type='PUBLIC') AS TEMP
GROUP BY Name);

/*  the number of private PushPins */
CREATE OR REPLACE VIEW private_push AS
(SELECT Name, COUNT(*) as private_push
FROM (SELECT DISTINCT Email, Title, Add_Datetime, Name FROM All_Pushpin WHERE Type='PRIVATE') AS TEMP
GROUP BY Name);

SELECT TEMP1.Name, COALESCE(public_cork,0) as public_cork, COALESCE(public_push,0) as public_push, COALESCE(private_cork,0) as private_cork, COALESCE(private_push,0) as private_push FROM
(SELECT P1.Name, public_cork, public_push
FROM public_cork AS P1 LEFT OUTER JOIN public_push AS P2 ON P1.Name = P2.Name) AS TEMP1 LEFT OUTER JOIN
(SELECT P1.Name, private_cork, private_push
FROM private_cork AS P1 LEFT OUTER JOIN private_push AS P2 ON P1.Name = P2.Name) AS TEMP2 ON TEMP1.Name = TEMP2.Name 
ORDER BY public_cork DESC;

/* pushpin search */
/* Searching keywords will be parsed to a list of unique key words, and assign them to variables, such as $Word1, $Word2, …… (Preferable a single meaningful word, a single variable) */
/*Only public corkboard */
/*For each word, create a search result */

CREATE OR REPLACE VIEW Search_Result AS
(SELECT Name, Tag, Description, Category, PP.Email, PP.Title, PP.Add_Datetime
FROM `Public_CorkBoard` AS PC INNER JOIN `CorkBoard` AS C ON PC.Email=C.Email AND PC.Title=C.Title INNER JOIN `Category` AS CAT ON CAT.CategoryID=C.CategoryID INNER JOIN `PushPin` as PP ON PP.Email=PC.Email AND PP.Title=PC.Title INNER JOIN  `PushPin_Tag` AS PT ON PT.Email=PP.Email AND PT.Title=PP.Title AND PT.Add_Datetime=PP.Add_Datetime INNER JOIN `User` as U ON U.email=PC.Email
WHERE UPPER(Description) LIKE '%$word%' 
OR         UPPER(Tag) LIKE '%$word%'
OR         UPPER(Category) LIKE '%$word%');

/*For a given PushPin (Email, Title, Add_Dateime), Tag may have different values, and Description, Category are repeated */
SELECT MAX(Name) AS Name, Title, MAX(Description) AS Description
FROM `Search_Result`
GROUP BY Email, Title, Add_Datetime ORDER BY Description;

/* view corkboard */
/* $Email and $Title were assigned */

/* Create Corkboard_ALL with status of public and private */
CREATE OR REPLACE VIEW Corkboard_ALL  AS
(SELECT C.Email, C.Title, CategoryID,
       CASE
        WHEN P.Password IS NOT NULL THEN 'PRIVATE'
        ELSE 'PUBLIC'
        END AS Type 
FROM `CorkBoard` AS C LEFT OUTER JOIN `Private_Corkboard` AS P on C.Email=P.Email AND C.Title=P.Title);

/*
 List pushpins, Title, Category, URL (pictures)
 If Corkboard_ALL’s Type=’PRIVATE’, User needs to Enter Password, and Watch button 
 is disabled
 */
 

CREATE OR REPLACE VIEW View_Cockboard AS
(SELECT Name, CA.Email, CA.Title, URL, Description, Add_Datetime, Category, Type
FROM `Corkboard_ALL` as CA INNER JOIN Pushpin AS P ON CA.Email=P.Email AND CA.Title=P.Title INNER JOIN `Category` AS C ON C.CategoryID=CA.CategoryID INNER JOIN `User` AS U on U.Email=CA.Email
WHERE CA.Email=$_GET['Email'] AND CA.Title=$_GET['Title']);

/* Show Name, Title, Category and Last Updated Datetime, for a Cockboard, all Names, #Titles, Categories are the same, Add_Datetimes are different.*/
SELECT MAX(Name) AS Name, MAX(Title) AS Title, MAX(Category) AS Category, MAX(Add_Datetime) AS Add_Datetime
FROM View_Cockboard;

SELECT Name, Email, Title, URL, Add_Datetime, Description, Category, Type 
FROM View_Cockboard;

/*
# the number of watchers is only for PUBLIC Cockboard
# Owner cannot watch his/her own Corkboard, if $Email = Watched_Email
*/

SELECT COUNT(*) AS number_watchers
FROM (Select DISTINCT Email, Title FROM `View_Cockboard` ) AS VC INNER JOIN `Watch` AS W ON VC.Email=W.Watched_Email AND VC.Title=W.Title;


/* add corkboard */
/* $Email, $Title, $Category, $Password are assigned (if private), $Title and $Category are not empty. */
/* Pull list of Category provided by system */
/*
SELECT Category
FROM `Category`;
INSERT INTO `CorkBoard` (Email, Title, Category)
VALUES ($Email, $Title, $Category);
 If CockBoard is private 
INSERT INTO `Private_CockBoard` (Email, Title, Password)
VALUES ($Email, $Title, $Password);
 If CockBoard is public 
INSERT INTO `Public_CockBoard` (Email, Title)
VALUES ($Email, $Title, $Password);
*/











