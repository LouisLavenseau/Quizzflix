<?php
    session_start();
    require("includes/connect.php");
    include("includes/barre.php");
    include("includes/fonctions.php");    
?>

<html>
    <head>
        <title> Profil </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/styleHist.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>    
    </head>

    <body>
        <div id="conteneur">
            <div class="elmt">
                <h2> Mes meilleurs scores </h2><br/>
                <table class="table">
                    <tr><th>Quizz</th><th>Difficulté</th><th>Score</th><th>Chrono</th><th>Date</th></tr>
                    <?php
                    //recherche des 5 meilleurs scores
                    $login= $_SESSION['login'];
                    $requete=$BDD -> prepare("SELECT * FROM HISTORIQUE WHERE Login=? ORDER BY Score DESC, Temps LIMIT 5");
                    $requete -> execute(array($login));
                    while($ligne=$requete->fetch())
                    {
                        //récup nom du quizz
                        $r = $BDD -> prepare("SELECT Nomquizz FROM QUIZZ WHERE Numquizz=?");
                        $r -> execute(array($ligne['Quizz']));
                        $Nomquizz = $r->fetch()[0];

                        $nbQuest = nbQuestTot($BDD,$ligne['Quizz']); // nb de questions du quizz pour affichage du score

                        $date = date("d-m-Y", strtotime($ligne['Date'])); //changement de format de la date 

                        //calcul chrono
                        $temps = $ligne['Temps'];
                        $strTemps = AfficheTemps($temps);

                        echo "<tr><td>".$Nomquizz."</td><td>".$ligne['Difficulte']."</td><td>".$ligne['Score']."/".$nbQuest."</td><td>".$strTemps."</td><td>".$date."</td></tr>";
                    }
                    ?>
                </table>
            </div>
            <div class="elmt">
                <h2> Historique des parties </h2><br/>
                <table class="table">
                    <tr><th>Quizz</th><th>Difficulté</th><th>Score</th><th>Chrono</th><th>Date</th></tr>
                    <?php
                        //recup des 10 derniers quizz effectués
                        $requete = $BDD -> prepare("SELECT * FROM HISTORIQUE WHERE Login=? ORDER BY Numhist DESC LIMIT 10");
                        $requete->execute(array($_SESSION['login']));
                        while($ligne=$requete->fetch())
                        {
                            //récup nom du quizz
                            $r = $BDD -> prepare("SELECT Nomquizz FROM QUIZZ WHERE Numquizz=?");
                            $r -> execute(array($ligne['Quizz']));
                            $Nomquizz = $r->fetch()[0];

                            $nbQuest = nbQuestTot($BDD,$ligne['Quizz']); // nb de questions du quizz pour affichage du score

                            $date = date("d-m-Y", strtotime($ligne['Date'])); //changement de format de la date 

                            //calcul chrono
                            $temps = $ligne['Temps'];
                            $strTemps = AfficheTemps($temps);

                            echo "<tr><td>".$Nomquizz."</td><td>".$ligne['Difficulte']."</td><td>".$ligne['Score']."/".$nbQuest."</td><td>".$strTemps."</td><td>".$date."</td></tr>";
                        }
                        
                    ?>
                </table>
            </div>
        </div>
    </body>
    <?php include("includes/footer.html");?>
</html>