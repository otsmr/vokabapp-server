<?php


function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

$success = false;

if (
    isset($_GET['token']) &&
    isset($_GET['return_to']) &&
    startsWith($_GET['return_to'], "tmp_"))
{
    
    require_once __DIR__ . "/db.php";
    
    $token = htmlentities($_GET['token']);
    $tmpSessionID = htmlentities($_GET['return_to']);

    //! check token
    //* odminUserID holen und speichern, tmpSessionID löschen

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
        <?php if ($success): ?>
            <header>
                <h1>VokabApp</h1>
                <p class='desc'>Account erfolgreich erstellt</a></p>
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