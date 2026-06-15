<?php

try {
    $bdd = new PDO("sqlite:vente.db");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'Connexion réussie';
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}