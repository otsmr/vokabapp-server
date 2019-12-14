<p align="center">
  <a href="https://vokabapp.oproj.de/">
    <img src="https://vokabapp.oproj.de/logo.png" width="150">
  </a>
</p>

<h3 align="center">VokabApp Server</h3>

<p align="center">
    Server für die VokabApp
    <br>
    <a href="https://github.com/otsmr/vokabapp"><strong>-- Zur App --</strong></a>
    <br>
    <br>
    <img src="https://img.shields.io/badge/platform-android%20%7C%20ios%20%7C%20web%20%7C%20windows%20%7C%20linux%20%7C%20mac-%23097aba" alt="Platform">
</p>



# Inhaltsverzeichnis
* <a href="#dokumentation">Dokumentation</a>
* <a href="#benutzeranmeldung">Benutzeranmeldung</a>
* <a href="#copyright-und-lizenz">Copyright und Lizenz</a>

# Dokumentation

# Benutzeranmeldung

1. **App stellt Anfrage beim Server**

    ``` GET /api/sync.php ```  
 
    Antwort vom Server
    ```JSON
    {
        "sessionID":"",
        "siginPath":""
    }
    ```
2. **Anmelden**  

    Die ```sessionID``` wird lokal gespeichert.  
    Die App öffnet den ```siginPath```, damit sich der Benutzer anmelden / registrieren kann.  
    Nach der Anmeldung wird der Benutzer weitergeleitet um die Session zu aktivieren.

    ``` GET /api/oauth.php ```  
    
    ```JSON
    {
        "tmpSessionID":"",
        "token":""
    }
    ```
    Mithilfe des Tokens wird die ```sessionID``` freigeschaltet.

3. **Fertig**

    Die ```sessionID``` ist freigeschaltet und der Benutzer kann den Browser schließen und zurück zur App gehen.

# Copyright und Lizenz
Copyright by <a href="https://tsmr.eu">TSMR</a>