<?php
    session_start();
    require("includes/connect.php");
    include("includes/barre.php");   
    include("includes/fonctions.php");
?>

<html>
    <head>
        <title> Résultat </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style/styleQuestion.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>    
    </head>

    <body>
        <div id=grandConteneur>
        <div id=fond>
        <?php

            $score=$_GET['score'];
            $Numquizz=$_GET['Numquizz'];
            $nbQuestTot=$_GET['nbQuestTot'];
            $difficulte=$_GET['difficulte'];
            
            // chrono
            $temps1= $_GET['temps1'];
            $temps2 = time();
            $temps = $temps2-$temps1;
            $strTemps = AfficheTemps($temps);
            
            //préparation de l'historique
            $requete = "SELECT MAX(Numhist) FROM HISTORIQUE";
            $resultat = $BDD-> query($requete);
            $numHist = $resultat->fetch()[0]+1; 

            //écriture des résultats dans l'historique
            $requete = $BDD -> prepare("INSERT INTO HISTORIQUE VALUES(:num,:login,:quizz,:difficulte,:score,:temps,CURDATE())");
            $requete -> bindValue('num',$numHist,PDO::PARAM_INT);
            $requete -> bindValue('login',$_SESSION['login'],PDO::PARAM_STR);
            $requete -> bindValue('quizz',$Numquizz,PDO::PARAM_INT);
            $requete -> bindValue('difficulte',$difficulte,PDO::PARAM_STR);
            $requete -> bindValue('score',$score,PDO::PARAM_INT);
            $requete -> bindValue('temps',$temps,PDO::PARAM_INT);
            $requete->execute();

            //recherche du meilleur score pour ce quizz
            //scoreMax
            $requete = $BDD -> prepare("SELECT MAX(Score) FROM HISTORIQUE WHERE Quizz=?");
            $requete -> execute(array($Numquizz));
            $meilleurScore=$requete->fetch()[0];
            //tempsMin
            $requete = $BDD -> prepare("SELECT MIN(Temps) FROM HISTORIQUE WHERE Quizz=? AND Score=?");
            $requete -> execute(array($Numquizz,$meilleurScore));
            $meilleurTemps=$requete->fetch()[0];
           
            //login
            $requete = $BDD -> prepare("SELECT Login FROM HISTORIQUE WHERE Quizz=? AND Score=? AND Temps=?");
            $requete -> execute(array($Numquizz,$meilleurScore,$meilleurTemps));
            $meilleurLogin = $requete->fetch()[0];

            //Affichage des résultats
            if($score==$nbQuestTot)
            {
                echo "<div id='conteneur'><h2 class='perfection'>La perfection</h2><h1 class='perfection'>".$score."/".$nbQuestTot." </h1>";
            }
            else
            {
                if($score>=3*$nbQuestTot/4)
                {
                    echo "<div id='conteneur'><h2 class='bienJoue'>Bien joué !</h2><h1 class='bienJoue'>".$score."/".$nbQuestTot." </h1>";
                }
                else 
                {
                    if($score>=$nbQuestTot/2)
                    {
                        echo "<div id='conteneur'><h2 class='pasMal'>Pas mal</h2><h1 class='pasMal'>".$score."/".$nbQuestTot." </h1>";
                    }
                    else
                    {
                        if($score>=$nbQuestTot/4)
                        {
                            echo "<div id='conteneur'><h2 class='bof'>C'est pas si mal...</h2><h1 class='bof'>".$score."/".$nbQuestTot." </h1>";
                        }
                        else
                        {
                            echo "<div id='conteneur'><h2 class='nul'>Tu feras mieux la prochaine fois...</h2><h1 class='nul'>".$score."/".$nbQuestTot." </h1>";
                        }
                    }
                }
            }
            echo "<h3>Chrono : &emsp;".$strTemps."</h3><br/>";
            echo "<h4>Le meilleur score pour ce quizz est de <b class='best'>".$meilleurScore."/".$nbQuestTot."</b></h4><h4>il a été réalisé par <b class='best'>".$meilleurLogin."</b> en ".AfficheTemps($meilleurTemps)."</h4><br/>";
            echo '<form method="POST" action="index.php"><button type="submit" class="btn btn-default" value="Retour accueil">Retour accueil</button></form></div>';
        ?>
    </div>
    </div>
    </body>
    <?php include("includes/footer.html");?>
</html>