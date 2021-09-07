<?php
    session_start(); 
    require("includes/connect.php");
    include("includes/barre.php");	
?>

<!DOCTYPE html>
<html>
    <head>
	    <title>Quizzflix</title>
        <meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet"  href="style/styleAccueilAdmin.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	
	<body>
     
        <div id="grandConteneur">
        <h1>&nbsp;&nbsp;&nbsp; Quizzs existants &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        <a href="creationQuizz.php"><span class="glyphicon glyphicon-plus" id="plus" style="font-size:25px" href="#"></span></a></h1>  
        <br/>

        <?php

            //Recupération du numéro de quizz le plus grand
            $requeteMAX = "SELECT * FROM QUIZZ WHERE Numquizz >= (SELECT MAX(Numquizz) FROM QUIZZ)";
            $resultatMAX=$BDD->query($requeteMAX);
            $ligneMAX = $resultatMAX->fetch();
            $numQuizzMax=$ligneMAX['Numquizz']; 
            //Pour chaque numéro entre 1 et le plus grand, on vérifie si on a appuyé sur le bouton supprimer du menu déroulant avant d'arriver sur la page
            for ($Numquizz=1;$Numquizz<=$numQuizzMax;$Numquizz++)
            {
                if (!empty($_POST['boutton'.$Numquizz.'']))//Si oui, on le supprime de la BDD
                {
                    $supBDD = $BDD->prepare('DELETE FROM QUIZZ WHERE Numquizz=:num');
                    $supBDD->bindValue(':num',$Numquizz,PDO::PARAM_INT);
                    $boolSupBDD = $supBDD->execute();



                    if($boolSupBDD)
                    {
                        echo'<p> Le quizz a été supprimé </p>';
                    }
                    else
                    {
                        echo'Echec de la suppression du(des) quizz(s)';
                    }
                }
            }



            //Pareil avec avec le bouton supprimer du bas de page
            if (!empty($_POST["serie"]))
            {
                foreach ($_POST["serie"] as $serie)
                {
                    $supBDD = $BDD->prepare('DELETE FROM QUIZZ WHERE Numquizz=:num');
                    $supBDD->bindValue(':num',$serie,PDO::PARAM_INT);
                    $boolSupBDD = $supBDD->execute();
                    if($boolSupBDD)
                    {
                        echo'<p> Le(s) quizz(s) a(ont) été supprimé(s) </p>';
                    }
                    else
                    {
                        echo'Echec de la suppression du(des) quizz(s)';
                    }
                }       
            }
            else 
            {
            echo '<p>Veuillez cocher le(s) quizz(s) que vous voulez supprimer</p>';
            }
        ?>

        <br/>
	
	
	
	    <div class="conteneur">
            <form method="POST">
            <?php             //Recupération du numéro, nom de chaque quizz 
                $requete = "SELECT * FROM QUIZZ ";
                $resultat = $BDD -> query($requete);
                while ($ligne = $resultat->fetch())
                {
	
                    echo'<div class="ligne"> &nbsp;';

                    $Numquizz = $ligne['Numquizz'];
                    $nomQuizz = $ligne['Nomquizz'];
                    //On les place chacun dans un div
                    echo'<label  for="'.$nomQuizz.'" > </label><input type="checkbox" class="check" name="serie[]" value='.$Numquizz.' /> &nbsp;';
                    //On récupère juste après aussi l'image correspondante. On associe un lien à la div pour la page de modification pour ce quizz
                    ;echo '<div class="quizz"><input class="image" type="image" src=images/'.$ligne['Image'].' value='.$ligne['Numquizz'].' title="'.$nomQuizz.'" height="60px" width="100px" /></div>';
                    echo'<span class="serie">&nbsp;&nbsp; <a class="nomQuizz" href="modifQuizz.php?numQuizz='.$Numquizz.'">'.$nomQuizz.'</a></span>
                    <div class="dropdown" >
                    <a  class="dropdown-toggle" data-toggle="dropdown" href="#"> 
                    <span class="points"><span class="glyphicon glyphicon-option-vertical " class="troisPoints" style="font-size:20px"></span></span>
                    </a>'.//Menu déroulant
                    <ul class="dropdown-menu" >
                    <li class="liste"><a href="modifQuizz.php?numQuizz='.$Numquizz.'"> <input type="button" name="supp" id="modif" value="Modifier"/></a></li>
                    <li class="liste"> <input type="submit" name="boutton'.$Numquizz.'" value="Supprimer"/> </li>
                    </ul>
                    </div>
                    </div><br/>';
                }        
            ?>
            </div id="boutton"><p class="bouton"><input type="submit" id="valider" value="Supprimer"/> </p></div>
            </form> 
        </div> 
        </div>  
    </body>
    <?php include("includes/footer.html"); ?>
</html>