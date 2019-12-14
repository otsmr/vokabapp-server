<?php
header('Content-Type: application/json');

require_once __DIR__ . "/db.php";

//? Anmeldung
/**
 * 
 ** Benutzer registriert sich bei api/sync.php 
 *? -> Bekommt sessionID (sicher) und tmpSessionID (beginnt mit 'tmp_') und speichert den sicheren
 * Im Hintergrund wird ein User angelegt ohne odminUserID
 *? -> Wird auf odmin.de weitergeleitet (return_to ist tmpSessionID) und von dort nach api/oauth.php
 *? -> Mit dem tmpSessionID kann der User identifiziert werden -> wird freigeschaltet
 *! -> Nachricht: Der Account wurde Erfolgreich angelegt
 * 
 *? sessionID tauschen?
 **/ 

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

$tmpSessionID = $_POST["sessionID"];

$user = $db->get("SELECT * FROM `users` WHERE `userID` = (SELECT `userID` FROM `sessions` WHERE `valid` = 1 AND `sessionID` = ?)", [$tmpSessionID]);

if (!$user || startsWith($tmpSessionID, "tmp_") || $user["valid"] === 0) {
    die(json_encode([
        "error" => "sessionID nicht gültig."
    ]));
}

/**
 * # Mögliche Anfragen
 * -> GET historys/[letzte]time
 * -> PUT users/config -> config, lastUpdate
 * -> PUT historys -> itemID, box, time
 */