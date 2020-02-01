<?php
die();
$filename = __DIR__ . '/out.sql';

require_once __DIR__ . "/../api/db.php";

$templine = '';
$lines = file($filename);

foreach ($lines as $line) {
    
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;
        
    $templine .= $line;
    
    if (substr(trim($line), -1, 1) == ';') {
        // echo $templine;
        print_r($db->insert($templine));
        $templine = '';
    }
}

echo "Datei wurde erfolgreich importiert.";