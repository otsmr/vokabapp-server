<?php

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

if (!isset($_POST["sessionID"])) {

    // tmpSessionID anlegen

    die();
}

/**
 * # Mögliche Anfragen
 * -> GET historys/[letzte]time
 * -> PUT users/config -> config, lastUpdate
 * -> PUT historys -> itemID, box, time
 */