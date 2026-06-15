<?php

//le scrript qui integre autre) la logique pour importer les données partagées par le client dans les tables de la base de données

$urls = [
    'magasins' => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSawI56WBC64foMT9pKCiY594fBZk9Lyj8_bxfgmq-8ck_jw1Z49qDeMatCWqBxehEVoM6U1zdYx73V/pub?gid=714623615&single=true&output=csv',
    'produits'  => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSawI56WBC64foMT9pKCiY594fBZk9Lyj8_bxfgmq-8ck_jw1Z49qDeMatCWqBxehEVoM6U1zdYx73V/pub?gid=0&single=true&output=csv',
    'ventes'    => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSawI56WBC64foMT9pKCiY594fBZk9Lyj8_bxfgmq-8ck_jw1Z49qDeMatCWqBxehEVoM6U1zdYx73V/pub?gid=760830694&single=true&output=csv',
];

try {
    function fetchCSV(string $url): array {
        $contenu = file_get_contents($url);
        if ($contenu === false) {
            throw new Exception("Impossible de télécharger : $url");
        }
        $lignes = explode("\n", trim($contenu));
        $entetes = str_getcsv(array_shift($lignes));
        $data = [];
        foreach ($lignes as $ligne) {
            if (empty(trim($ligne))) continue;
            $data[] = array_combine($entetes, str_getcsv($ligne));
        }
        return $data;
    }


    $magasins = fetchCSV($urls['magasins']);
    
    foreach ($magasins as $row) {
        $requestMagasin = $bdd->prepare("INSERT OR IGNORE INTO magasins (id_magasin, ville, nombre_salaries) VALUES (?, ?, ?)");
        $requestMagasin->execute([$row['ID Magasin'], $row['Ville'], $row['Nombre de salariés']]);
    }
    echo "Magasins importés.\n";

    
    $produits = fetchCSV($urls['produits']);

    foreach ($produits as $row) {
        $requestProduit = $bdd->prepare("INSERT OR IGNORE INTO produits (id_reference, nom, prix, stock) VALUES (?, ?, ?, ?)");
        $requestProduit->execute([$row['ID Référence produit'], $row['Nom'], $row['Prix'], $row['Stock']]);
    }
    echo "Produits importés.\n";


    $ventes = fetchCSV($urls['ventes']);
    $checkIfExist = $bdd->prepare("SELECT COUNT(*) FROM ventes WHERE date = ? AND id_reference = ? AND quantite = ? AND id_magasin = ?");
    $insert = $bdd->prepare("INSERT INTO ventes (date, id_reference, quantite, id_magasin) VALUES (?, ?, ?, ?)");

    $nbNouvelleVente = 0;
    foreach ($ventes as $row) {
        $date = $row['Date'];
        $id_reference = $row['ID Référence produit'];
        $quantite = (int)$row['Quantité'];
        $id_magasin = (int)$row['ID Magasin'];

        $checkIfExist->execute([$date, $id_reference, $quantite, $id_magasin]);
        if ($checkIfExist->fetchColumn() == 0) {
            $insert->execute([$date, $id_reference, $quantite, $id_magasin]);
            $nbNouvelleVente++;
        }
    }
    echo "$nbNouvelleVente nouvelles ventes importées.\n";

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
