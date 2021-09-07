<?php
    session_start();
    require("includes/connect.php");
    include("includes/barre.php");
    include("includes/fonctions.php");
?>

<html>
    <head>
        <title> Réponse </title>
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
            $Numquizz=$_GET['Numquizz'];
            $Numquest=$_GET['Numquest'];
            $difficulte=$_GET['difficulte'];
            $typeQuest=$_GET['typeQuest'];
            $score=$_GET['score'];
            $rep[0] = $_GET['rep0'];
            if(isset($_GET['rep1'])) {$rep[1] = $_GET['rep1'];}
            $temps1 = $_GET['temps1'];

            //récupération du nombre de questions du quizz
            $nbQuestTot = nbQuestTot($BDD,$Numquizz);

            //récupération du nombre de bonnes réponses
            $nbBRep = recupNbBRep($BDD,$Numquizz,$Numquest);

            //récupération des bonnes réponses
            $bonneRep = recupBonneRep($BDD,$nbBRep,$Numquest,$Numquizz);

            //récupération de la ou des réponses choisies
            $augmentation = augmenterScore($typeQuest,$nbBRep,$bonneRep,$score,$rep);
            $score += $augmentation;
            
            if($augmentation==1)
            {
                echo"<div id='conteneur'><h2 id='bravo'>Bien joué !</h2>";
            }
            else
            {
                echo "<div id='conteneur'><h3 id='dommage'>Dommage, tu y étais presque...</h3><br/><h4>La bonne réponse était :</h4>";
                for($i=0;$i<$nbBRep;$i++)
                {
                    echo "<h2>".$bonneRep[$i]."</h2>";
                }
            }

            
            if($Numquest<$nbQuestTot)
            {
                echo "<br/><br/><br/><br/><form method='POST' action='question.php?Numquizz=".$Numquizz."&Numquest=".($Numquest+1)."&difficulte=".$difficulte."&score=".$score."&temps1=".$temps1."'><button type='submit' class='btn btn-default' value='Question suivante'>Question suivante</button></form></div>";
            }
            else
            {
                echo "<br/><br/><br/><br/><form method='POST' action='score.php?score=".$score."&Numquizz=".$Numquizz."&nbQuestTot=".$nbQuestTot."&difficulte=".$difficulte."&temps1=".$temps1."'><button type='submit' class='btn btn-default' value='Finir le quizz'>Finir le quizz</button></form></div>";
            }
        ?>
    </div>
    </div>
    </body>
    <?php include("includes/footer.html");?>
</html>