<?php

//script pour l’exécution de requêtes SQL, pour répondre aux questions clés sur les ventes de l'entreprise

try {
    $chiffreAffaire = $bdd->prepare("SELECT SUM(produits.prix * ventes.quantite) AS total 
                                        FROM ventes
                                        JOIN produits
                                        ON ventes.id_reference = produits.id_reference
                                    ");
    $chiffreAffaire->execute();

    $total = $chiffreAffaire->fetchColumn();
    echo "Chiffre d'affaires : ". $total ." €\n";

    $insert = $bdd->prepare("INSERT INTO resultats (type_analyse, valeur, description, date_execution) 
                            VALUES (?, ?, ?, DATE('now'))");

    $insert->execute(['chiffre d\'affaires', $total, 'Chiffre d\'affaires total\n']);





    $venteParProduit = $bdd->prepare(" SELECT produits.nom, SUM(ventes.quantite) AS quantite
                                        FROM ventes
                                        JOIN produits
                                        ON ventes.id_reference = produits.id_reference
                                        GROUP BY produits.nom 
                                    ");
    $venteParProduit->execute();

    echo "Ventes par produit\n";
    foreach ($venteParProduit as $v) {
        echo "nom : " . $v['nom'] . " - " . "quantite : " . $v['quantite'] . "\n";
        $insert = $bdd->prepare("INSERT INTO resultats (type_analyse, valeur, description, date_execution) VALUES (?, ?, ?, DATE('now'))");
        $insert->execute(['ca_produit', $v['quantite'], "Produit : {$v['nom']}"]);
    }





    $venteParVille = $bdd->prepare("SELECT magasins.ville, SUM(produits.prix * ventes.quantite) AS ca
                                        FROM ventes
                                        JOIN produits
                                        ON ventes.id_reference = produits.id_reference
                                        JOIN magasins
                                        ON ventes.id_magasin = magasins.id_magasin
                                        GROUP BY magasins.ville
                                    ");
    $venteParVille->execute();

    echo "Ventes par ville\n";
    foreach ($venteParVille as $v) {
        echo "ville : " . $v['ville'] ." - " .$v['ca'] ." €\n";
        $insert = $bdd->prepare("INSERT INTO resultats (type_analyse, valeur, description, date_execution) VALUES (?, ?, ?, DATE('now'))");
        $insert->execute(['ca_region', $v['ca'], "Ville : {$v['ville']}"]);
    }

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
