<?php
error_reporting(0);

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

$status = "error";

if (
    isset($_GET['token']) &&
    isset($_GET['return_to']) &&
    startsWith($_GET['return_to'], "tmp_"))
{
    
    require_once __DIR__ . "/db.php";

    $token = htmlentities($_GET['token']);
    $tmpSessionID = htmlentities($_GET['return_to']);

    $url = ODMIN_BASE_URL . "/api/istokenvalid/" . $_GET['token'];

    try {

        $res = json_decode(file_get_contents($url));
    
        if(isset($res->valid) && $res->valid) {
            $odminUserID = $res->user->id;
    
            $userBySession = $db->get("SELECT `userID` from `sessions` WHERE `sessionID` = ? AND (`time` > DATE_SUB(now(), INTERVAL 1 DAY))", [$tmpSessionID]);
            $userByOdmin = $db->get("SELECT `userID`, `valid` from `users` WHERE `odminUserID` = ?", [$odminUserID]);
    
            if ($userByOdmin) {
    
                if ($userBySession && $userBySession["userID"] !== $userByOdmin["userID"]) {
                    
                    $db->query("UPDATE `sessions` SET `userID` = ?, `valid` = '1' WHERE `userID` = ?", [$userByOdmin["userID"], $userBySession["userID"]]);
                    
                    $db->query("DELETE FROM `users` WHERE `userID` = ?", [$userBySession["userID"]]);
                    $db->query("DELETE FROM `sessions` WHERE `sessionID` = ?", [$tmpSessionID]);
                    
                    $status = "signIn";
                    
                }
                
            } else if ($userBySession) {
    
                $db->query("UPDATE `users` SET `odminUserID` = ?, `identity` = NULL, `valid` = '1' WHERE `userID` = ?", [$odminUserID, $userBySession["userID"]]);
                $db->query("UPDATE `sessions` SET `valid` = '1' WHERE `userID` = ?", [$userBySession["userID"]]);
    
                $db->query("DELETE FROM `sessions` WHERE `sessionID` = ?", [$tmpSessionID]);
    
                $status = "newAccount";
    
            }
            
        }



    } catch (\Throwable $th) {

    }

}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VokabApp Server</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <main>
        <?php if ($status === "newAccount"): ?>
            <header>
                <h1>VokabApp</h1>
                <p class='desc' style="color: #4ac14a;">Account erfolgreich erstellt</a></p>
            </header>
            <p style="font-weight: bold; font-size: 24px;">Diese Seite kann geschlossen werden.</p>
        <?php elseif ($status === "signIn"): ?>
            <header>
                <h1>VokabApp</h1>
                <p class='desc' style="color: #4ac14a;">Erfolgreich angemeldet</a></p>
            </header>
            <p style="font-weight: bold; font-size: 24px;">Diese Seite kann geschlossen werden.</p>
        <?php else: ?>
            <header>
                <h1>VokabApp</h1>
                <p class='desc'>Angaben überprüfen und erneut probieren</a></p>
            </header>
            <p style="font-weight: bold; font-size: 24px;">Account konnte nicht erstellt werden.</p>
        <?php endif; ?>
        <a class='imprint' href='https://oproj.de/privacy'>Privacy & Imprint</a>
    </main>
</body>
</html>