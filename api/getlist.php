<?php
header('Content-Type: application/json');
// error_reporting(0);
error_reporting(1);

require_once __DIR__ . "/db.php";

$sessionID = null;

if (isset($_POST["sessionID"])) {
    $sessionID = $_POST["sessionID"];
}

if (isset($_POST["getMetaData"])) {
    
    $groups = $db->query("SELECT * FROM groups");
    $subGroups = $db->query("SELECT * FROM subgroups");
    $lists = $db->query("SELECT * FROM lists");
    
    die(json_encode([
        "groups" => $groups->fetchAll(PDO::FETCH_ASSOC),
        "subGroups" => $subGroups->fetchAll(PDO::FETCH_ASSOC),
        "lists" => $lists->fetchAll(PDO::FETCH_ASSOC)
    ]));

}

if ($_POST["getListsByIDs"]) {

    $listIDs = $_POST["getListsByIDs"];
    $sql = "";
    foreach ($listIDs as $key => $listID) {
        //! Abfrage unsicher?
        $sql .= "listID = " . ((int) $listID) . " or ";
    }
    $sql .= "0";
    
    $items = $db->query("SELECT * FROM items WHERE $sql");

    die(json_encode([
        "items" => $items->fetchAll(PDO::FETCH_ASSOC)
    ]));

}


die(json_encode([
    "error" => "Anfrage nicht gültig."
]));