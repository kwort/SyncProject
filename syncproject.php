#!/usr/bin/php
<?php

include "Syncproject.class.php";

if ($argc == 1) {
	$argv[1] = str_replace("\n", "", `pwd`);
} else if ($argc != 2) {
	echo "USAGE : ".$argv[0]." [FOLDER]\n"; die();
}

try {
	$app = new SyncProject($argv[1]);
	$app->execute();
} catch (Exception $e) {
	echo 'Erreur : ',  $e->getMessage(), "\n";
}