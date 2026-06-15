<?php

try {
    echo 'Hello World';

    $magasin = $bdd->prepare("CREATE TABLE IF NOT EXISTS magasins (id_magasin INTEGER PRIMARY KEY, ville TEXT, nombre_salaries INTEGER)");
    $magasin->execute();

    $produit = $bdd->prepare("CREATE TABLE IF NOT EXISTS produits (id_reference TEXT PRIMARY KEY, nom TEXT, prix INTEGER, stock INTEGER)");
    $produit->execute();

    $vente = $bdd->prepare("CREATE TABLE IF NOT EXISTS ventes (id_vente INTEGER PRIMARY KEY AUTOINCREMENT, id_reference TEXT, id_magasin INTEGER, date DATE, quantite INTEGER, 
                            FOREIGN KEY (id_reference) REFERENCES produits(id_reference), FOREIGN KEY (id_magasin) REFERENCES magasins(id_magasin))");
    $vente->execute();


    $resultat = $bdd->prepare("CREATE TABLE IF NOT EXISTS resultats (id_resultat INTEGER PRIMARY KEY AUTOINCREMENT, type_analyse TEXT, valeur TEXT, description TEXT, date_execution DATE)");
    $resultat->execute();

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}