<?php
die();
$sql = "";

$dir = __DIR__ . "/raw/physik/";
$files = scandir($dir);

foreach ($files as $key => $value) {
    $path = $dir . $value;

    
    if (pathinfo($path, PATHINFO_EXTENSION) === "json") {
        echo "Datei: " . $path . "<br>";

        $listID = str_split($value, 2)[0];
        $sql .= "\n\nINSERT INTO `items` (`a`, `b`, `listID`) VALUES";

        $json = json_decode(file_get_contents($path));

        print_r($json);
        
        foreach ($json as $key => $value) {

            $sql .= "\n(\"$value->title\", \"$value->formel\", $listID),";

        }

        $sql = substr($sql, 0, strlen($sql)-1);
        $sql .= ";";

    }
}

$f = fopen("./out.sql", "w");

fwrite($f, $sql);

fclose($f);