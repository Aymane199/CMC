<?php
    include 'database_configuration.php' ;

    if(array_key_exists("user",$_POST) && $_POST["user"] != null){
        $name = $_POST["user"];
    }else{
        $name ='tdelille';
    }
    if(array_key_exists("datestart",$_POST) && $_POST["datestart"] != null){
        $datestart = $_POST["datestart"];
    }else{
        $datestart='2009-02-01';
    }
    if(array_key_exists("dateend",$_POST) && $_POST["dateend"] != null){
        $dateend = $_POST["dateend"];
    }else{
        $dateend='2009-05-31';
    }
    if(array_key_exists("forum",$_POST) && $_POST["forum"] != null){
        $forum = $_POST["forum"];
    }else{
        $forum='';
    }
    $conn = new mysqli($server, $user, $pass,$db);
    mysqli_set_charset($conn,'utf8');

    $tabnom = array();
    $querry2 = "SELECT a.Date, a.nbActivitée, b.avg

                FROM
                
                    (SELECT Utilisateur,COUNT(`Utilisateur`) as 'nbActivitée' ,`Date`
                     FROM `transition`
                     where `Utilisateur`='".$name."' 
                     AND `Date` BETWEEN '".$datestart."' AND '".$dateend."' 
                     AND  `Attribut` like '%IDForum=".$forum."%'
                      GROUP BY `Date`) as a,
                
                    (SELECT count(`titre`)/ (SELECT COUNT( DISTINCT `Utilisateur`) as nbUser FROM transition ) as 'avg' ,`Date` 
                    FROM `transition` 
                    WHERE `Date` BETWEEN '".$datestart."' AND '".$dateend."' 
                    AND  `Attribut` like '%IDForum=".$forum."%'
                    GROUP BY `Date`) as b
                
                WHERE a.date = b.date
                
                order by a.date ";
    $result2 = $conn->query($querry2);
    while($r = $result2 -> fetch_row()){
        $tabnom[] = $r;
    }

    $totalevsavg = array();
    $querry3 ="SELECT a.nbActivitée,b.avg

                FROM
                    (   SELECT
                
                        COUNT(`Utilisateur`) as 'nbActivitée' 
                
                
                        FROM `transition`
                
                        where `Utilisateur`='".$name."' 
                        AND `Date` BETWEEN '".$datestart."' AND '".$dateend."'
                        AND  `Attribut` like '%IDForum=".$forum."%'
                     ) as a,
                     
                    (   SELECT 
                
                        count(`titre`)/ (SELECT COUNT( DISTINCT `Utilisateur`) as nbUser FROM transition ) as 'avg'  
                    
                        FROM `transition` 
                    
                        where `Date` BETWEEN '".$datestart."' AND '".$dateend."'
                        AND  `Attribut` like '%IDForum=".$forum."%'
                    ) as b ";

    $result3 = $conn->query($querry3);
    while($r = $result3 -> fetch_row()){
        $totalevsavg[] = $r;
    }

    $tabresult = array();
    $dataheure = "SELECT SUBSTRING_INDEX(`Heure`, ':', 1)+1 as heure, count(`Titre`)/DATEDIFF( '".$dateend."'  ,'".$datestart."') as total FROM `transition` 
    
                        WHERE `Date` BETWEEN '".$datestart."' AND '".$dateend."'                   
                         
                        AND  `Attribut` like '%IDForum=".$forum."%'
                        
                        GROUP by SUBSTRING_INDEX(`Heure`, ':', 1)
                                            
                        ORDER BY heure desc
                       ";

    $resultdataheure = $conn->query($dataheure);
    while($r = $resultdataheure -> fetch_row()) {
        $tabresult[] = $r;
    }


?>
