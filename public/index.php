<?php

// ini_set('error_reporting', E_ALL); // FULL DEBUG
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

require_once(__DIR__ . '/../vendor/autoload.php');

(new \Gravita\Http\Methods\MethodInit)->execute();
