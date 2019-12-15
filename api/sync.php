<?php
header('Content-Type: application/json');
// error_reporting(0);

require_once __DIR__ . "/db.php";

function randomString($length = 20, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $rand = '';
    for ($i = 0; $i < $length; $i++) $rand .= $characters[rand(0, strlen($characters) - 1)];
    return $rand;
}

if (!isset($_POST["sessionID"])) {

    $identity = md5("88gjbvasvipsdfbujp" . $_SERVER['REMOTE_ADDR'] . "kbgipz426alsdhjoadb");
    $count = $db->get("SELECT COUNT(*) FROM `users` WHERE (`lastUpdate` > DATE_SUB(now(), INTERVAL 1 HOUR)) AND `identity` = ?", [$identity]);

    if ($count["COUNT(*)"] > 5) {
        die(json_encode([
            "error" => "Maximal 5 Accounts in einer Stunde."
        ]));
    }

    // tmpSessionID anlegen
    $sessionID = randomString();
    $tmpSessionID = "tmp_" . randomString();

    $userID = $db->insert("INSERT INTO `users` (`identity`) VALUES (?)", [$identity]);

    $db->query("INSERT INTO `sessions`(`sessionID`, `userID`) VALUES (?, ?)", [$sessionID, $userID]);
    $db->query("INSERT INTO `sessions`(`sessionID`, `userID`) VALUES (?, ?)", [$tmpSessionID, $userID]);

    die(json_encode([
        "sessionID" => $sessionID,
        "siginPath" => "https://odmin.de/signin?return_to=".$tmpSessionID."&clientID=". ODMIN_CLIENT_ID
    ]));

}

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

$sessionID = $_POST["sessionID"];

$user = $db->get("SELECT *, UNIX_TIMESTAMP(lastUpdate) as timeServer FROM `users` WHERE `userID` = (SELECT `userID` FROM `sessions` WHERE `valid` = 1 AND `sessionID` = ?)", [$sessionID]);

if (!$user || startsWith($sessionID, "tmp_") || $user["valid"] === 0) {
    die(json_encode([
        "error" => "sessionID nicht gültig."
    ]));
}

if (isset($_POST["checkSessionID"])) {
    die(json_encode([
        "ok" => true
    ]));
}
if (isset($_POST["destroySession"])) {

    $db->query("DELETE FROM `sessions` WHERE `sessionID` = ?", [$sessionID]);
    die(json_encode([
        "ok" => true
    ]));
}

if (isset($_POST["config"])) {

    $post = $_POST["config"];

    $configClient = json_encode($post["config"]);
    $timeClient = $post["time"];
    $timeServer = $user["timeServer"];

    if ($timeServer == $timeClient) {
        die(json_encode([
            "ok" => "Einstellungen sind aktuell."
        ]));
    }
    if ($configClient) {
        
        if ($user["config"] === "" || $timeServer < $timeClient) {

            $db->query("UPDATE `users` SET `config` = ?, `lastUpdate` = FROM_UNIXTIME(?) WHERE `users`.`userID` = ?", [$configClient, $timeClient, $user["userID"]]);

            die(json_encode([
                "ok" => "Einstellungen wurden hochgeladen."
            ]));

        } else {

            die(json_encode([
                "config" => json_decode($user["config"]),
                "time" => (int) $user["timeServer"]
            ]));
        }      

    }

    die(json_encode([
        "error" => "Anfrage nicht gültig."
    ]));

}

die(json_encode([
    "error" => "Anfrage nicht gültig."
]));

/**
 * # Mögliche Anfragen
 * -> GET historys/[letzte]time
 * -> PUT historys -> itemID, box, time
 * -> PUT users/config -> config, lastUpdate
 * -> GET sessions 
 * -> DELETE sessions 
 */