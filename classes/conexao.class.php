<?php 	
	
    try
    {
        $pdo = new PDO('mysql:host=localhost;dbname=libertet_amigosecreto', 'libertet_amigosecreto', 'HcmZ[uCE6]X1');
    }
    catch(PDOException $e)
    {
        throw new PDOException($e);
    } 
	
?>