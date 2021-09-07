<?php
    session_start();
    require("includes/connect.php");
    include("includes/barre.php");
?>
<html>
    <head>
        <title>A propos</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style/styleAPropos.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>

    <body>
        <div id="conteneur">
            <h2>Qui sommes-nous ?</h2>
            <br/>
            <h4>Nous sommes deux élèves de l'<a href="https://ensc.bordeaux-inp.fr/fr" target="_blank">Ecole Nationale Supérieure de Cognitique</a>.
            <br/><br/> Quizzflix est notre projet web de 1ère année.</h4>
            <br/>
            <a href="https://ensc.bordeaux-inp.fr/fr" target="_blank"><img src="images/ensc.jpg" alt="ensc"/></a>
        </div>
    </body>
    <?php include("includes/footer.html");?>
</html>