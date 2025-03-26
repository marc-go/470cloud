<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="page-content">
        <div class="overlay"></div>
        <div class="block">
            <div id="start">
                <h1>Willkommen bei 470Cloud!</h1>
                <button onclick="next(1)">Fortfahren</button>
            </div>
            <div id="user">
                <h3>Admin Account</h3>
                <input type="text" id="a-name" placeholder="Benutername"><br>
                <input type="mail" id="a-mail" placeholder="Email Adresse"><br>
                <input type="password" id="a-pw1" placeholder="Passwort"><br>
                <input type="password" id="a-pw2" placeholder="Passwort widerholen"><br>
                <button onclick="addUser()">Fortfahren</button><br>
            </div>
            <div id="database">
                <h3>Datenbank</h3>
                <p>Die Datenbank wird automatisch erstellt.</p>
                <input type="text" id="d-host" placeholder="Host" value="localhost"><br>
                <input type="text" id="d-user" placeholder="Benutzer"><br>
                <input type="password" id="d-pass" placeholder="Passwort"><br>
                <input type="text" id="d-name" placeholder="Name"><br>
                <button onclick="addDatabase()">Fortfahren</button>
            </div>
        </div>
    </div>
</body>
</html>