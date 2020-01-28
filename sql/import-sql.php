<?php
die();
$filename = __DIR__ . '/vokabapp-liste.sql';

require_once __DIR__ . "/../api/db.php";

$templine = '';
$lines = file($filename);

foreach ($lines as $line) {
    
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;
        
    $templine .= $line;
    
    if (substr(trim($line), -1, 1) == ';') {
        echo $templine;
        $db->query($templine);
        $templine = '';
    }
}

echo "Datei wurde erfolgreich importiert.";