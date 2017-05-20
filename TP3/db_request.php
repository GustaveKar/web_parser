<?php
function timeElapsedAvgSearchDoc($conn)
{
    $results = mysqli_query($conn,"SELECT AVG(TIME_TO_SEC(`TimeElapsed`)) FROM SearchDocuments;");       
    return mysqli_fetch_array($results)[0];	
}
 //deuxième requete qui retourne le TimeSeekRead moyen pour la table SearchDocuments
function timeSeekReadAvgSearchDoc($conn)
{
    $results = mysqli_query($conn,"SELECT AVG(TIME_TO_SEC(`TimeSeekRead`)) FROM SearchDocuments;");
    return mysqli_fetch_array($results)[0];
	
}
//fonction qui retourne le nombre d'appels moyens pour chaque utilisateur present dans la base 
function noUserCallsAvg($conn)
{
  	$results = mysqli_query($conn,"SELECT UserName from SearchDocuments UNION SELECT UserName from UpdateDocuments 
  							UNION SELECT UserName from GetDocumentResSpace UNION SELECT UserName from GetDocument
  							UNION SELECT UserName from SetDocuments");
  	$maxRow = mysqli_num_rows($results);
        
        $userCalls = [$maxRow];
        print_r($userCalls);
        for($i=0; $i < $maxRow; $i++ )
        {
            $row = mysqli_fetch_array($results);
            $avg = noUserCalls($row[0], $conn);
            $userCalls[$row[0]] = $avg;
        }
        print_r($userCalls);
        return $userCalls;
}

//fonction qui retourne le nombre d'appels moyens pour un utilisateur donné "utilisateur"
function noUserCalls($utilisateur,$conn)
{
	$tab1 = mysqli_query($conn,"SELECT UserName from SearchDocuments");
	
	$tab2 = mysqli_query($conn,"SELECT UserName from UpdateDocuments");
	
	$tab3 = mysqli_query($conn,"SELECT UserName from GetDocument");
	
	$tab4 = mysqli_query($conn,"SELECT UserName from SetDocuments");
	
	$tab5 = mysqli_query($conn,"SELECT UserName from GetDocumentResSpace");
	
	$compteur = 0;
	$max1 = mysqli_num_rows($tab1);
	$max2 = mysqli_num_rows($tab2);
	$max3 = mysqli_num_rows($tab3);
	$max4 = mysqli_num_rows($tab4);
	$max5 = mysqli_num_rows($tab5);
	$maxtotal = $max1 + $max2 + $max3 + $max4 + $max5;
	
	for($i = 0; $i <$max1; $i++)
	{
            $row = mysqli_fetch_array($tab1);
            if ($utilisateur == $row[0])
            {
                    $compteur = $compteur+1;
            }
	}
	
	for($i = 0; $i <$max2; $i++)
	{
            $row = mysqli_fetch_array($tab2);
            if ($utilisateur == $row[0])
            {
		$compteur = $compteur+1;
            }
	}
	for($i = 0; $i <$max3; $i++)
	{
            $row = mysqli_fetch_array($tab3);
            if ($utilisateur == $row[0])
            {
		$compteur = $compteur+1;
            }
	}
	
	for($i = 0; $i <$max4; $i++)
	{
            $row = mysqli_fetch_array($tab4);
            if ($utilisateur == $row[0])
            {
		$compteur = $compteur+1;
            }
	}
	
	for($i = 0; $i <$max5; $i++)
	{
            $row = mysqli_fetch_array($tab4);
            if ($utilisateur == $row[0])
            {
		$compteur = $compteur+1;
            }
	}
	
	$nbre_appel_moyen = $compteur/ $maxtotal ;
	
        echo "Nombre d'appel moyen: $nbre_appel_moyen";
	return $nbre_appel_moyen;
}


function getTimeElapsedFromFunction($conn, $functionName)
{
    $query = "SELECT TIME_TO_SEC(dateTime), TimeElapsed FROM $functionName LIMIT 250;";
    $results = mysqli_query($conn, $query);

    for($i=1; $i < mysqli_num_rows($results); $i++)
    {
        $row = mysqli_fetch_array($results);
 
        $array["$row[0]"] = convertToMilliseconds($row[1]);
    }
    if(isset($array))       
        return fillArrayWith($array, 0);
    else
        return array();
}


function getTimeSeekReadFromSearchDocuments($conn)
{
    $query = "SELECT TIME_TO_SEC(dateTime), TimeSeekRead FROM SearchDocuments LIMIT 250;";
    $results = mysqli_query($conn, $query);
    for($i=1; $i < mysqli_num_rows($results); $i++)
    {
        $row = mysqli_fetch_array($results);
        
        $secondsArray = explode(".",$row[1]);
        $seconds = $secondsArray[0];
        $milliseconds = $secondsArray[1];
        
        $array["$row[0]"] = $seconds * 1000 + $milliseconds/10;
    }
    if(isset($array))       
        return fillArrayWith($array, 0);
    else
        return array();
}

//TODO Ajouter 5 minutes d'intervalles + gerer plusieurs noms de fonctions 
function getNoCallsFromUserAndFunction($conn, $functionName, $userName, $startTime, $endTime)
{
    if($userName != "")
    {
       $query = "SELECT * FROM $functionName WHERE UserName = '$userName' AND dateTime > '$startTime' AND dateTime < '$endTime'";
    }
    else 
    {
       $query = "SELECT * FROM $functionName WHERE dateTime > '$startTime' AND dateTime < '$endTime'"; 
    }
    $results = mysqli_query($conn, $query);

    return  mysqli_num_rows($results);
}

function getEarliestDate($conn)
{
    $query = "SELECT dateTime from SearchDocuments UNION SELECT dateTime from UpdateDocuments 
  							UNION SELECT dateTime from GetDocumentResSpace UNION SELECT dateTime from GetDocument
  							UNION SELECT dateTime from SetDocuments;"; 
    
    $results = mysqli_query($conn, $query);
    if (mysqli_num_rows( $results) > 0)
    {
        $row = mysqli_fetch_array($results);
        return $row[0];
    }
    else
    {
        die("Cannot find the earliest date");
    }

}


//Fonction pour aller chercher les utilisateurs
function GetUsers($conn)
{
    $query = "SELECT UserName from SearchDocuments UNION SELECT UserName from UpdateDocuments 
  							UNION SELECT UserName from GetDocumentResSpace UNION SELECT UserName from GetDocument
  							UNION SELECT UserName from SetDocuments";
    $results = mysqli_query($conn, $query);
    
    $users = [];
    
    $maxRow = mysqli_num_rows($results);
    
    for($i=0; $i< $maxRow; $i++)
    {
        $row = mysqli_fetch_array($results);
        $users[$i] = $row[0];
    }
    
    return $users;
   
}

function convertToMilliseconds($time)
{
    $timeArray = explode(":", $time);
    
    $hours = $timeArray[0];
    $minutes = $timeArray[1];
    $seconds = explode(".", $timeArray[2])[0];
    $milliseconds = explode(".", $timeArray[2])[1];
    
    return $hours * 60 * 60 * 1000 + $minutes * 60 * 1000 + $seconds * 1000 + $milliseconds / 10;
}

//La donnée que l'on reçoit de la base de donnée ne contient pas toutes les index de 0 à n. Cette fonction rajoute ces index avec la valeur $filler
//Utilisé car phpchart requiert que les index soient de 0 à n
function fillArrayWith($array, $filler)    
{
    end($array);
    $lastKey = key($array);
    reset($array);

    for($i = 0; $i < $lastKey; $i++)
    {
        if(array_key_exists($i, $array))
        {
            continue;
        }
        else
        {
            $array[$i] = $filler;
        }
    }
    ksort($array);
    return $array;
}


?>