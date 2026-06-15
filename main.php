<?php

$bdd = new PDO("sqlite:/data/vente.db");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

require 'db.php';
require 'script-import.php';
require 'script-resultats.php';