<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="icon" type="image/x-icon" href="https://www.ryerson.ca/tmu_favicon.ico">
        <link href="styles.css" rel="stylesheet" type="text/css">
        <title>CPS 510 ( Assignment 9)</title>
    </head>

    <header>
	    <h1 class="center">
            Soccer leagues DMBS with GUI
        </h1>
        <?php
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
            // Create connection to Oracle
            $conn = oci_connect('h5dhaliw', '04163590',
            '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(Host=oracle.scs.ryerson.ca)(Port=1521))(CONNECT_DATA=(SID=orcl)))');
            if (!$conn) {
                $m = oci_error();
                echo $m['message'];
            }else{
                echo "Successfully connected with oracle database<br>";
            }
        ?>
        <hr size="10" class="divider">
    </header>
    
    <body>
        <?php
            // Drop Tables ---------------------------------------------------------------------
            if (isset($_POST['droptables'])) {
                $drop = 'DROP TABLE referees CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table referees dropped<br>";
                }

                $drop = 'DROP TABLE league CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table league dropped<br>";
                }

                $drop = 'DROP TABLE clubs CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table clubs dropped<br>";
                }

                $drop = 'DROP TABLE stadium CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table stadium dropped<br>";
                }

                $drop = 'DROP TABLE managers CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table managers dropped<br>";
                }

                $drop = 'DROP TABLE players CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table players dropped<br>";
                }

                $drop = 'DROP TABLE matchess CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table matchess dropped<br>";
                }

                $drop = 'DROP TABLE matchesPlayed CASCADE CONSTRAINTS';
                $stid = oci_parse($conn, $drop);
                $droptable = oci_execute($stid);
                if($droptable){
                    echo "Table matchesPlayed dropped<br>";
                }
            
                echo "<br>";
            }

            // Create Tables -------------------------------------------------------------------
            if (isset($_POST['createtables'])) {    
                
                $create = 'CREATE TABLE referees (
                    ref_id         VARCHAR2(2 CHAR),
                    ref_name       VARCHAR2(30 CHAR) NOT NULL,
                    CONSTRAINT ref_pk PRIMARY KEY (ref_id)
                    )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table referees created<br>";
                }


                $create = 'CREATE TABLE league (
                    country         VARCHAR2(30 CHAR),
                    leagueName      VARCHAR2(30 CHAR) NOT NULL,
                    CONSTRAINT country_pk PRIMARY KEY (country)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table league created<br>";
                }

                $create = 'CREATE TABLE clubs (
                    club_id               VARCHAR2(2 CHAR),
                    c_name                VARCHAR2(30 CHAR) NOT NULL,
                    home_jersey_color     VARCHAR2(30 CHAR),
                    away_jersey_color     VARCHAR2(30 CHAR),
                    country_fk             VARCHAR2(30 CHAR),
                    CONSTRAINT  clubid_pk PRIMARY KEY (club_id),
                    CONSTRAINT cont_fk FOREIGN KEY (country_fk) REFERENCES league(country)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table clubs created<br>";
                }

                $create = 'CREATE TABLE stadium (
                    club_id               VARCHAR2(2 CHAR) PRIMARY KEY, --same key as club
                    stadium_name          VARCHAR2(30 CHAR) NOT NULL,
                    city                  VARCHAR2(30 CHAR) NOT NULL,
                    CONSTRAINT clubid_fk FOREIGN KEY (club_id) REFERENCES clubs(club_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table stadium created<br>";
                }


                $create = 'CREATE TABLE managers (
                    manager_id   VARCHAR2(2 CHAR),
                    m_name      VARCHAR2(30 CHAR) NOT NULL,
                    age         NUMBER,
                    clubid_fk    VARCHAR2(2 CHAR), --fk
                    CONSTRAINT  managerid_pk PRIMARY KEY (manager_id),
                    CONSTRAINT c_fk FOREIGN KEY (clubid_fk) REFERENCES clubs(club_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table managers created<br>";
                }


                $create = 'CREATE TABLE players (
                    player_id   VARCHAR2(2 CHAR),
                    p_name      VARCHAR2(30 CHAR) NOT NULL,
                    age         NUMBER,
                    p_position  VARCHAR2(2 CHAR),
                    shirt_number     NUMBER CHECK (shirt_number >=0 AND shirt_number <=99),
                    managerid_fk  VARCHAR2(2 CHAR), --fk
                    club_fk     VARCHAR2(2 CHAR), --fk
                    CONSTRAINT  player_pk PRIMARY KEY (player_id),
                    CONSTRAINT m_fk FOREIGN KEY (managerid_fk) REFERENCES managers(manager_id),
                    CONSTRAINT c_fl FOREIGN KEY (club_fk) REFERENCES clubs(club_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table players created<br>";
                }

                $create = 'CREATE TABLE matchess (
                    match_id    VARCHAR2(2 CHAR),
                    GameDay     DATE,
                    fk_gameRef  VARCHAR2(2 CHAR) NOT NULL,
                    FOREIGN KEY (fk_gameRef) REFERENCES referees(ref_id),
                    CONSTRAINT uc_ref_for_game  UNIQUE (fk_gameRef,GameDay),
                    CONSTRAINT match_pk PRIMARY KEY (match_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table matchess created<br>";
                }

                $create = 'CREATE TABLE matchesPlayed (
                    fk_match_id     VARCHAR2(2 CHAR) NOT NULL,
                    fk_club_home    VARCHAR2(2 CHAR) NOT NULL,
                    fk_club_away    VARCHAR2(2 CHAR) NOT NULL,
                    FOREIGN KEY (fk_match_id) REFERENCES matchess(match_id),
                    FOREIGN KEY (fk_club_home) REFERENCES clubs(club_id),
                    FOREIGN KEY (fk_club_away) REFERENCES clubs(club_id),
                    CHECK (fk_club_home != fk_club_away),
                    CONSTRAINT games UNIQUE (fk_match_id)
                )';
                $stid = oci_parse($conn, $create);
                $createtable = oci_execute($stid);
                if($createtable){
                    echo "Table matchesPlayed created<br>";
                }
                echo "<br>";
            }

            // Populate Tables -----------------------------------------------------------------
            if (isset($_POST['populatetables'])) {     
                $Array = ["INSERT INTO referees VALUES ('r1', 'Michael Oliver')",
                "INSERT INTO referees VALUES ('r2', 'Mike Dean')",
                "INSERT INTO referees VALUES ('r3', 'Anthony Taylor')",
                "INSERT INTO referees VALUES ('r4', 'Howard Webb')",
                "INSERT INTO referees VALUES ('r5', 'Antonio Mateu Lahoz')"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated referees table: </strong><br>";
                $query = "Select * From referees" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }




                $Array = ["INSERT INTO league VALUES ('England', 'Premier League' )",
                "INSERT INTO league VALUES ('Spain', 'La Liga' )",
                "INSERT INTO league VALUES ('Italy', 'Serie A' )",
                "INSERT INTO league VALUES ('France', 'Ligue One' )",
                "INSERT INTO league VALUES ('Germany', 'Bundesliga' )"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated league table: </strong><br>";
                $query = "Select * From league" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }


                $Array = ["INSERT INTO matchess VALUES('m1', TO_DATE('2022-10-10', 'yyyy-mm-dd'), 'r1' )",
                "INSERT INTO matchess VALUES('m2', TO_DATE('2022-10-17', 'yyyy-mm-dd'), 'r1' )",
                "INSERT INTO matchess VALUES('m3', TO_DATE('2022-10-10', 'yyyy-mm-dd'), 'r2' )"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated matchess table: </strong><br>";
                $query = "Select * From matchess" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }


                $Array = ["INSERT INTO clubs VALUES('C1','Manchester united','Red','White','England')",
                "INSERT INTO clubs VALUES('C2','Arsenal','Red','Black','England')",
                "INSERT INTO clubs VALUES('C3','PSG','Blue','White','France')",
                "INSERT INTO clubs VALUES('C4','Real Madrid','White','Purple','Spain')",
                "INSERT INTO clubs VALUES('C5','Manchester City','Blue','White','England')"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated clubs table: </strong><br>";
                $query = "Select * From clubs" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }



                $Array = ["INSERT INTO stadium VALUES('C3','Le Parc Des Princes','Paris')",
                "INSERT INTO stadium VALUES('C1','Old Trafford','Manchester')",
                "INSERT INTO stadium VALUES('C2','Emirates','London')",
                "INSERT INTO stadium VALUES('C4','Bernabeu','Madrid')",
                "INSERT INTO stadium VALUES('C5','Ethihad','Manchester')"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated stadium table: </strong><br>";
                $query = "Select * From stadium" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }


                $Array = ["INSERT INTO managers VALUES('M1','Pep Guardiola','51','C5')",
                "INSERT INTO managers VALUES('M2','mauricio pochettino','50','C3')",
                "INSERT INTO managers VALUES('M3','carlo ancelotti','63','C4')",
                "INSERT INTO managers VALUES('M4','Eric ten hag','52','C1')",
                "INSERT INTO managers VALUES('M5','Mike arteta','40','C2')"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated managers table: </strong><br>";
                $query = "Select * From managers" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }


                $Array = ["INSERT INTO players VALUES('P1','Cristiano Ronaldo','38','ST','7','M4','C1')",
                "INSERT INTO players VALUES('P2','Lionel Messi','36','RW','30','M2','C3')",
                "INSERT INTO players VALUES('P3','Eric Halland','22','ST','9','M1','C5')",
                "INSERT INTO players VALUES('P4','Neymar','29','LW','10','M2','C3')",
                "INSERT INTO players VALUES('P5','Karim Benzema','33','ST','9','M3','C4')",
                "INSERT INTO players VALUES('P6','Harman','33','ST','9','M3','C4')"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated players table: </strong><br>";
                $query = "Select * From players" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }



                $Array = ["INSERT INTO matchesPlayed VALUES ('m1','C1','C2')",
                "INSERT INTO matchesPlayed VALUES ('m2','C2','C1')"];
                foreach ($Array as $pop){
                    $stid = oci_parse($conn, $pop);
                    $populate = oci_execute($stid);
                }
                echo "<strong>Populated matchesPlayed table: </strong><br>";
                $query = "Select * From matchesPlayed" ;
                $stid = oci_parse($conn, $query);
                $r = oci_execute($stid);
                if($r){
                // Fetch each row in an associative array
                while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                foreach ($row as $item) {
                echo $item,", ";
                }
                echo "<br/>";
               
                }
                echo "<hr color='#0000ff' size='5px'>";
                }





                echo "<br>";
            }
            // Query Tables --------------------------------------------------------------------
            if (isset($_POST['queries'])) {     
                echo "<strong>Queries below:</strong><br><br><br>";

                $Array=["SELECT p_name, age 
                FROM players 
                WHERE age <= 37
                ORDER BY club_fk", 

                "SELECT ref_name  
                from referees
                order by ref_name asc",

                "SELECT *
                FROM clubs
                WHERE c_name = 'PSG'",

                "SELECT m_name, age FROM managers ORDER BY age DESC",

                "SELECT * FROM league 
                WHERE country <> 'England'
                ORDER BY leagueName",

                "SELECT DISTINCT home_jersey_color, c_name
                FROM clubs c
                WHERE c.home_jersey_color <> 'Blue'",

                "SELECT stadium_name, city
                FROM stadium
                where city = 'Manchester'
                ORDER BY stadium_name asc",

                "SELECT ref_name  
                from referees
                order by ref_name asc",


                "SELECT * from matchesPlayed
                WHERE fk_club_home = 'C1'",

                "Select ref_name, gameday  
                from referees, matchess
                where fk_gameref= 'r1'
                and ref_id = 'r1'",

                "Select p_name, m_name  
                from players, managers 
                where managerid_fk= 'M2'
                and club_fk = 'C3' 
                and manager_id = 'M2'
                and clubid_fk = 'C3'",

                "SELECT c_name, stadium_name, country_fk
                FROM clubs, stadium
                WHERE clubs.club_id = 'C1'
                and stadium.club_id = 'C1'",

                "SELECT p_name,m_name 
                FROM players,managers 
                WHERE clubid_fk='C4' AND club_fk='C3'"
                ];
                foreach ($Array as $query){
                    $stid = oci_parse($conn, $query);
                    $r = oci_execute($stid);
                    if($r){
                        echo "<strong>$query</strong>:<br> ";
                        while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
                            foreach ($row as $item) {
                                echo $item,", ";
                            }
                            echo "<br/>";
                        }
                    }
                    echo "<br>";
                }


              
            }
        ?>
    </body>
    <div>
    <form method="post"> 
        <input type="submit" name="droptables"
                value="Drop Tables"/> 
        <input type="submit" name="createtables"
                value="Create Tables"/>
        <input type="submit" name="populatetables"
                value="Populate Tables"/>
        <input type="submit" name="queries"
                value="Query Tables"/>   
    </form> 

    </div>
 
</html>
