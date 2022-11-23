<?php
    try
    {
        // On se connecte a MySQL
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host=localhost;dbname=moodle', 'root', '', $pdo_options);
        echo"connexion reussie";
    }
        catch(Exception $e)
    {
        //En cas d'erreur precedemment, on affiche un message et on arrete tout
        die('Erreur : '.$e->getMessage());
    }
?>
