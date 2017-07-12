#!/usr/bin/php
<?php

include "Syncproject.class.php";

$fech = false;
if ($argc == 1) {
	$argv[1] = str_replace("\n", "", `pwd`);
} else if ($argc == 3) {
	if ( $argv[2] == 'fetch' ) {
		$fech = true;
	} else {
		echo "USAGE : ".$argv[0]." [FOLDER] [fetch]\n"; die();
	}
} else if ($argc != 2) {
	echo "USAGE : ".$argv[0]." [FOLDER] [fetch]\n"; die();
}

try {
	$app = new SyncProject($argv[1], $fech);
	$app->execute();
} catch (Exception $e) {
	echo 'Erreur : ',  $e->getMessage(), "\n";
}