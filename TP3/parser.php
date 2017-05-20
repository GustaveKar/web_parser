<?php  
include 'tp3_connection.php';
include "functions.php";
require_once 'db_request.php';

$file = "";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    //$conn provient du fichier tp3_connection et est la connection a mysqli.
    clearDatabase($conn);
    $stream = fopen($_FILES["file"]["tmp_name"], "r"); 
    if($stream)
    {
        $max = 0;
        $start = false;
        
        //Taille maximum d'une query
        $max_allowed_packet = mysqli_fetch_array( mysqli_query($conn, "SHOW VARIABLES LIKE 'max_allowed_packet';"))[1];
        print_r($max_allowed_packet);
        while((($line = fgets($stream)) !==false)  && $max < 1000 )
        { 
          //On ignore ce qui a avant les *
          if($start == false && strcmp(substr($line, 0, 2), "**") == 0)
          {
              $start = true; 
          }
          else if($start == true)
          {

            $function = FunctionBase::createFunction($line);
            if($function !== 0)
            {    
               $query = $function->createSqlQuery();
               
               if( strlen($query) < $max_allowed_packet && !mysqli_query($conn, $query))
               {
                     echo "<br>Failure... " . $query . "<br>" . mysqli_error($conn);                    
               }         
            }
            $max++;
          }
         
        }
        
        fclose($stream);
        echo "<br>TimeElapsed Temps moyen: " . timeElapsedAvgSearchDoc($conn);
        echo "<br>TimeSeekRead Temps moyen: " . timeSeekReadAvgSearchDoc($conn);
        $userCallsAvg = noUserCallsAvg($conn);
        foreach($userCallsAvg as $key => $value)
        {
            echo "<br>User: $key";
            echo "<br>Temps moyen: $value <br><br>";
        }
        
        
        mysqli_close($conn);
    }
    else
    {  
        echo "Impossible d'ouvrir le fichier. ", error_get_last();
    }
    //on va a la partie visualizer.
    header("Location: visualizer.php");
    exit;
}

//S'occupe de nettoyer toutes les tables de la base de donnée
function clearDatabase($conn)
{
    if(!($results = mysqli_query($conn, "SHOW tables")))
    {
       echo "Failure... " . "SHOW tables" . "<br>" . mysqli_error($conn);
    }
    while($table = mysqli_fetch_array($results))
    {
        $tableName = $table[0];
        $query = "TRUNCATE TABLE $tableName";
        mysqli_query($conn, $query);
        echo "<br> $tableName cleared";
    }
    
}
 //première requete qui retourne le TimeElapsed moyen pour la table SearchDocuments
//function timeElapsedAvgSearchDoc($conn)
//{
//    $results = mysqli_query($conn,"SELECT AVG(TIME_TO_SEC(`TimeElapsed`)) FROM SearchDocuments;");       
//    return mysqli_fetch_array($results)[0];	
//}
// //deuxième requete qui retourne le TimeSeekRead moyen pour la table SearchDocuments
//function timeSeekReadAvgSearchDoc($conn)
//{
//    $results = mysqli_query($conn,"SELECT AVG(TIME_TO_SEC(`TimeSeekRead`)) FROM SearchDocuments;");
//    return mysqli_fetch_array($results)[0];
//	
//}
////fonction qui retourne le nombre d'appels moyens pour chaque utilisateur present dans la base 
//function noUserCallsAvg($conn)
//{
//  	$results = mysqli_query($conn,"SELECT UserName from SearchDocuments UNION SELECT UserName from UpdateDocuments 
//  							UNION SELECT UserName from GetDocumentResSpace UNION SELECT UserName from GetDocument
//  							UNION SELECT UserName from SetDocuments");
//  	$maxRow = mysqli_num_rows($results);
//        
//        $userCalls = [$maxRow];
//        print_r($userCalls);
//        for($i=0; $i < $maxRow; $i++ )
//        {
//            $row = mysqli_fetch_array($results);
//            $avg = noUserCalls($row[0], $conn);
//            $userCalls[$row[0]] = $avg;
//        }
//        print_r($userCalls);
//        return $userCalls;
//}
//
////fonction qui retourne le nombre d'appels moyens pour un utilisateur donné "utilisateur"
//function noUserCalls($utilisateur,$conn)
//{
//	$tab1 = mysqli_query($conn,"SELECT UserName from SearchDocuments");
//	
//	$tab2 = mysqli_query($conn,"SELECT UserName from UpdateDocuments");
//	
//	$tab3 = mysqli_query($conn,"SELECT UserName from GetDocument");
//	
//	$tab4 = mysqli_query($conn,"SELECT UserName from SetDocuments");
//	
//	$tab5 = mysqli_query($conn,"SELECT UserName from GetDocumentResSpace");
//	
//	$compteur = 0;
//	$max1 = mysqli_num_rows($tab1);
//	$max2 = mysqli_num_rows($tab2);
//	$max3 = mysqli_num_rows($tab3);
//	$max4 = mysqli_num_rows($tab4);
//	$max5 = mysqli_num_rows($tab5);
//	$maxtotal = $max1 + $max2 + $max3 + $max4 + $max5;
//	
//	for($i = 0; $i <$max1; $i++)
//	{
//            $row = mysqli_fetch_array($tab1);
//            if ($utilisateur == $row[0])
//            {
//                    $compteur = $compteur+1;
//            }
//	}
//	
//	for($i = 0; $i <$max2; $i++)
//	{
//            $row = mysqli_fetch_array($tab2);
//            if ($utilisateur == $row[0])
//            {
//		$compteur = $compteur+1;
//            }
//	}
//	for($i = 0; $i <$max3; $i++)
//	{
//            $row = mysqli_fetch_array($tab3);
//            if ($utilisateur == $row[0])
//            {
//		$compteur = $compteur+1;
//            }
//	}
//	
//	for($i = 0; $i <$max4; $i++)
//	{
//            $row = mysqli_fetch_array($tab4);
//            if ($utilisateur == $row[0])
//            {
//		$compteur = $compteur+1;
//            }
//	}
//	
//	for($i = 0; $i <$max5; $i++)
//	{
//            $row = mysqli_fetch_array($tab4);
//            if ($utilisateur == $row[0])
//            {
//		$compteur = $compteur+1;
//            }
//	}
//	
//	$nbre_appel_moyen = $compteur/ $maxtotal ;
//	
//        echo "Nombre d'appel moyen: $nbre_appel_moyen";
//	return $nbre_appel_moyen;
//}

?>