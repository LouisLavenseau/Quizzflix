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
		<link rel="stylesheet"  href="style/styleValidationCreationQuizz.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>

<body>


<?php
       
    

    //détermination du numéro de réponse le plus grand pour avoir ceux des prochaines réponses créées
    $requeteMAX = "SELECT * FROM REPONSE WHERE Numrep >= (SELECT MAX(Numrep) FROM REPONSE)";
    $resultatMAX=$BDD->query($requeteMAX);
    $ligneMAX = $resultatMAX->fetch();
    $numRep=$ligneMAX['Numrep']; 
    $newNumRep=$numRep+1;

    $iQuestions=$_POST['nbrQuestions'];
    //$newNumQuizz=$_POST['newNumQuizz'];
    //récupérations de varaibles
    $newNumQuizz=$_SESSION['newNumQuizz'];
    $nomImage=$_SESSION['nomImage'];
    $nbrCategories=$_SESSION['nbrCategories'];
    $nomQuizz=$_SESSION['nomQuizz'];
    $descrQuizz=$_SESSION['descrQuizz'];
    $nbrQuestions=$_SESSION['nbrQuestions'];
    

$tropDeReponses="";

//détermination de si le nombre de bonnes réponses mises dans le cas de qcms ne dépasse pas 2
   for ($numQuestion=1; $numQuestion<=$nbrQuestions; $numQuestion++)
   {
        $choixFacile = $_POST['choixQuestionFacile'.$numQuestion.''];
        $choixMoyen = $_POST['choixQuestionMoyen'.$numQuestion.''];
        $choixDifficile = $_POST['choixQuestionDifficile'.$numQuestion.''];

    
        if ($choixFacile=="QCM" or $choixMoyen=="QCM" or $choixDifficile=="QCM")
        {   $cpt=0;
            $tropDeReponses="";
            foreach ($_POST['bonneRep'.$numQuestion.''] as $bonneRep)
            {
                $cpt++;
            }
            if($cpt>2){$tropDeReponses="oui";}       
        }
    }


if ($tropDeReponses=="oui"){echo'Erreur : vous avez sélectionné au moins une fois plus de 2 bonnes réponses pour les questions avec des réponses QCM';}
else{

    $erreur="non";

    //requetes quizz
    $addBDD=$BDD->prepare('INSERT INTO QUIZZ  VALUES (:num, :image, :cat1, :cat2, :cat3, :nom , :desc)');
    $addBDD->bindValue(':num',$newNumQuizz,PDO::PARAM_INT);
    $addBDD->bindValue(':image',$nomImage,PDO::PARAM_STR);
    $cpt=0;
    for ($i=1;$i<=$nbrCategories;$i++)
    {
        $addBDD->bindValue(':cat'.$i.'',$_SESSION['categorie'.$i.''],PDO::PARAM_STR);
    }
    if ($nbrCategories==1)
    {
        $addBDD->bindValue(':cat2',NULL,PDO::PARAM_STR);
        $addBDD->bindValue(':cat3',NULL,PDO::PARAM_STR);
    }
    if ($nbrCategories==2)
    {
        $addBDD->bindValue(':cat3',NULL,PDO::PARAM_STR);
    }
    $addBDD->bindValue(':nom',$nomQuizz,PDO::PARAM_STR);
    $addBDD->bindValue(':desc',$descrQuizz,PDO::PARAM_STR);
    $boolAddQuizz = $addBDD->execute();
    if (!$boolAddQuizz){echo'Une erreur s\'est produite, quizz non créé';}
    //FIN QUIZZ






    //requets QUESTION pour chaque question
    for ($numQuestion=1; $numQuestion<=$nbrQuestions; $numQuestion++)
    {
        $choixFacile = $_POST['choixQuestionFacile'.$numQuestion.''];
        $choixMoyen = $_POST['choixQuestionMoyen'.$numQuestion.''];
        $choixDifficile = $_POST['choixQuestionDifficile'.$numQuestion.''];
        $question=$_POST['question'.$numQuestion.''];
        $addBDD=$BDD->prepare('INSERT INTO QUESTION (Numquest, Quizz, Question, Typefacile, Typemoyen, Typedifficile)  VALUES (:num, :quizz, :question, :facile, :moyen, :difficile)');
    $addBDD->bindValue(':num',$numQuestion,PDO::PARAM_INT);
    $addBDD->bindValue(':quizz',$newNumQuizz,PDO::PARAM_INT);
    $addBDD->bindValue(':question',$question,PDO::PARAM_STR);
    $addBDD->bindValue(':facile',$choixFacile,PDO::PARAM_STR);
    $addBDD->bindValue(':moyen',$choixMoyen,PDO::PARAM_STR);
    $addBDD->bindValue(':difficile',$choixDifficile,PDO::PARAM_STR);
    $boolAddQuest = $addBDD->execute();
    if (!$boolAddQuest){echo'Une erreur s\'est produite, quizz non créé';}
    }

//FIN QUESTION



//DEBUT REPONSE, on parcourt pour chaque question
for ($numQuestion=1; $numQuestion<=$iQuestions; $numQuestion++)
{
    $choixFacile = $_POST['choixQuestionFacile'.$numQuestion.''];
    $choixMoyen = $_POST['choixQuestionMoyen'.$numQuestion.''];
    $choixDifficile = $_POST['choixQuestionDifficile'.$numQuestion.''];

    //Traitement du cas où la question est de type VRAI/FAUX pour tous les niveaux
    if ($choixFacile=='VF')
    {
            //Assignation aux deux réponses VRAI/FAUX de si elles sont une bonne réponse ou pas
            if($_POST['repQuest'.$numQuestion.'']=='vrai') {$bonneRepVrai="oui"; $bonneRepFaux="non";}
            else  {$bonneRepVrai="non"; $bonneRepFaux="oui";}

            //Remplissage de la base de donnée pour la réponse VRAI
            $addBDD=$BDD->prepare('INSERT INTO REPONSE VALUES (:numRep, :numQuizz, :numQuest, :rep, :bonneRep)');
            $addBDD->bindValue(':numRep',$newNumRep,PDO::PARAM_INT);
            $addBDD->bindValue(':numQuizz',$newNumQuizz,PDO::PARAM_INT);
            $addBDD->bindValue(':numQuest',$numQuestion,PDO::PARAM_INT);
            $addBDD->bindValue(':rep',"VRAI",PDO::PARAM_STR);
            $addBDD->bindValue(':bonneRep',$bonneRepVrai,PDO::PARAM_STR);
            $boolAddRep = $addBDD->execute();
            if (!$boolAddRep){echo'Une erreur s\'est produite, quizz non créé';}
            $newNumRep=$newNumRep+1;

            //Remplissage de la base de donnée pour la réponse FAUX
            $addBDD=$BDD->prepare('INSERT INTO REPONSE VALUES (:numRep, :numQuizz, :numQuest, :rep, :bonneRep)');
            $addBDD->bindValue(':numRep',$newNumRep,PDO::PARAM_INT);
            $addBDD->bindValue(':numQuizz',$newNumQuizz,PDO::PARAM_INT);
            $addBDD->bindValue(':numQuest',$numQuestion,PDO::PARAM_INT);
            $addBDD->bindValue(':rep',"FAUX",PDO::PARAM_STR);
            $addBDD->bindValue(':bonneRep',$bonneRepFaux,PDO::PARAM_STR);
            $boolAddRep = $addBDD->execute();
            if (!$boolAddRep){echo'Une erreur s\'est produite, quizz non créé';}
            $newNumRep=$newNumRep+1;
        
    }


    //Traitement du cas où la question est de type question ouverte pour tous les niveaux
    if ($choixDifficile=="OUV" and $choixMoyen=="OUV" and $choixFacile=="OUV")
    {   
                $rep=$_POST['repQuest'.$numQuestion.''];

                //Remplissage de la base de donnée pour cette réponse
                $addBDD=$BDD->prepare('INSERT INTO REPONSE VALUES (:numRep, :numQuizz, :numQuest, :rep, :bonneRep)');
                $addBDD->bindValue(':numRep',$newNumRep,PDO::PARAM_INT);
                $addBDD->bindValue(':numQuizz',$newNumQuizz,PDO::PARAM_INT);
                $addBDD->bindValue(':numQuest',$numQuestion,PDO::PARAM_INT);
                $addBDD->bindValue(':rep',$rep,PDO::PARAM_STR);
                $addBDD->bindValue(':bonneRep',"oui",PDO::PARAM_STR);
                $boolAddRep = $addBDD->execute();
                if (!$boolAddRep){echo'Une erreur s\'est produite, quizz non créé';}
                //Incrémentation du numéro de réponse
                $newNumRep=$newNumRep+1;
                
    }
        
    
    //cas où il y a un qcm sur au moins un des niveaux de difficultés
    if ($choixFacile=="QCM" or $choixMoyen=="QCM" or $choixDifficile=="QCM")
    {   //détermination de quelles réponses sont bonnes
        $nbrRep=$_POST['nbrRepQ'.$numQuestion.''];
        $bonnesReps=array();
        $cpt=0;
        $estBonneRep="";
        foreach ($_POST['bonneRep'.$numQuestion.''] as $bonneRep)
        {
            $cpt++;
            $bonnesReps[$cpt]=$bonneRep;
        }
        
        for ($i=1;$i<=$nbrRep;$i++)
        {
            //Récupération de si la réponse est bonne ou fausse
            for ($num=1;$num<=$cpt;$num++)
            {
                if ($bonnesReps[$num]==$i) {$estBonneRep="oui";}
                else {$estBonneRep="non";}
            }
            
    
            //Récupération de la réponse
            $rep = $_POST['rep'.$i.'quest'.$numQuestion.''];
    
            //Remplissage de la base de donnée pour cette réponse
            $addBDD=$BDD->prepare('INSERT INTO REPONSE VALUES (:numRep, :numQuizz, :numQuest, :rep, :bonneRep)');
            $addBDD->bindValue(':numRep',$newNumRep,PDO::PARAM_INT);
            $addBDD->bindValue(':numQuizz',$newNumQuizz,PDO::PARAM_INT);
            $addBDD->bindValue(':numQuest',$numQuestion,PDO::PARAM_INT);
            $addBDD->bindValue(':rep',$rep,PDO::PARAM_STR);
            $addBDD->bindValue(':bonneRep',$estBonneRep,PDO::PARAM_STR);
            $boolAddRep = $addBDD->execute();
            if (!$boolAddRep){echo'Une erreur s\'est produite, quizz non créé';}
            //Incrémentation du numéro de réponse
            $newNumRep=$newNumRep+1;
        }


    }
       
}


}

?>
<div id="grandConteneur">
<div id="conteneur">
    <h4 class="text-muted">Le quizz a bien été ajouté</h4>
    <form method="POST" action="accueilAdmin.php">
    <p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="Retour aux quizz"/>
    </form>
</div>
</div>
</body>
<?php include("includes/footer.html"); ?>
</html>