<?php
    try
    {
        // On se connecte à MySQL
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO('mysql:host=localhost;dbname=moodle', 'root', '', $pdo_options);
    }
        catch(Exception $e)
    {
        //En cas d'erreur précédemment, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
    }
?>
