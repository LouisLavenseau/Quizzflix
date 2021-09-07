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
		<link rel="stylesheet"  href="style/styleCreationQuestions.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>

	
<?php

//Vérification que tous les champs ont été remplis
if (empty($_POST['nomQuizz']) or empty($_POST['descrQuizz']) or empty($_POST['nbrQuestions']) or empty($_POST['categories']) or empty($_FILES['fichier']['name']) )
{
	echo'<h5 class="text-danger">Erreur : un des champs au moins est vide</h5>';
}
else
{
$cpt=0;
foreach ($_POST["categories"] as $categorie)
        {$cpt=$cpt+1;}
        if ($cpt>3) {echo'Erreur : le nombre de catégories choisies est trop grand';}

else
{
	$cpt=0;
	foreach ($_POST["categories"] as $categorie)
			{$cpt=$cpt+1;
			}
	if ($cpt<=3)		
	{
		
	

//Contrôle et déplacement dans le dossier "image" de l'image uploadée

    //Vérification de si le fichier a correctement été uploadé
    if(is_uploaded_file($_FILES['fichier']['tmp_name']))
{
    $typeMime = mime_content_type($_FILES['fichier']['tmp_name']);

    //Vérification de si le fichier a un format autorisé
    if($typeMime == "image/jpeg" or $typeMime == "image/png")
    {
        $taille = filesize($_FILES['fichier']['tmp_name']);

        //Vérification que l'image n'est pas trop lourde
        if ($taille <5000000 )
        {
            
            $cheminEtNomTemporaire = $_FILES['fichier']['tmp_name'];
$cheminEtNomDefinitif = 'images/'.$_FILES['fichier']['name'];

$deplacementEstOk = move_uploaded_file($cheminEtNomTemporaire, $cheminEtNomDefinitif);

if (!$deplacementEstOk)
{
    $message = "Suite à une erreur, l'image n'a pas été uploadée";
    echo $message;
}

        }
        else
        {
            echo'Le fichier ne doit pas dépasser 5,5 Mo';

        }
    }
    else
    {
        $message2 = "Le format de l'image n'est pas accepté";
        echo $message2;
    }
}
}



//Récupération des variables POST
$nomQuizz=$_POST['nomQuizz'];
$_SESSION['nomQuizz']=$_POST['nomQuizz'];
$description=$_POST['descrQuizz'];
$_SESSION['descrQuizz']=$_POST['descrQuizz'];
$nbrQuestions=$_POST['nbrQuestions'];
$_SESSION['nbrQuestions']=$_POST['nbrQuestions'];
$nomImage=$_FILES['fichier']['name'];
$_SESSION['nomImage']=$_FILES['fichier']['name'];
$_SESSION['categories']=$_POST["categories"];


echo'<div id="grandConteneur">';
echo'<h1> Création du quizz : '.$nomQuizz.'</h1>';

?>

<br/>
<br/>

<div class="conteneur">
<h5 class='text-muted'>Attention ! Si vous voulez créer une question de type Vrai/Faux, vous devez sélectioner 'Vrai/Faux' pour tous les niveaux de difficulté.
<br/> Pour une même question, vous ne pouvez pas non plus choisir une question de type ouverte, puis une question de type QCM pour un niveau plus élevé.</h5>
<form method="POST" action="creationReponses.php">


<?php        
//Parcourt de toutes les questions
for ($numQuestion=1; $numQuestion<=$nbrQuestions; $numQuestion++)
{
	
echo'<div class="ligne">';
echo'<h4>&nbsp;&nbsp; Question '.$numQuestion.'</h4>';
echo'<br/>';//champs de texte de la question
echo'<p> &nbsp;&nbsp;&nbsp;&nbsp; <label for="pseudo" class="text"></label> <input type="text" placeholder="Question" name="question'.$numQuestion.'" id="question" /> </p>';
echo'<p><div class="niveaux">';

echo'<div class="niveau">';
echo'<div class="niveau2">';
echo'<h4>&nbsp; Niveau facile </h4>';//Pour chaque niveau de difficulté, l'admin choisit entre qcm, vrai/faux, ou question ouverte grâce à des types radio
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionFacile'.$numQuestion.'" value="QCM">  QCM </div>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionFacile'.$numQuestion.'" value="VF" id="VF'.$numQuestion.'Facile"> Vrai/faux </div>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionFacile'.$numQuestion.'" value="OUV"> Question ouverte </div>';
echo'</div>';
echo'</div>';

echo'<div class="niveau">';
echo'<div class="niveau2">';
echo'<h4>&nbsp; Niveau moyen</h4>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionMoyen'.$numQuestion.'" value="QCM"> QCM </div>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionMoyen'.$numQuestion.'" value="VF" id="VF'.$numQuestion.'Moyen"> Vrai/faux </div>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionMoyen'.$numQuestion.'" value="OUV"> Question ouverte </div>';
echo'</div>';
echo'</div>';

echo'<div class="niveau">';
echo'<div class="niveau2">';
echo'<h4>&nbsp; Niveau difficile </h4>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionDifficile'.$numQuestion.'" value="QCM"> QCM </div>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionDifficile'.$numQuestion.'" value="VF" id="VF'.$numQuestion.'Difficile"> Vrai/faux </div>';
echo'<div class="typeQuestion">&nbsp;<input type="radio" name="choixQuestionDifficile'.$numQuestion.'" value="OUV"> Question ouverte </div>';
echo'</div>';
echo'</div>';
echo'</div>';
echo'<br/>';
echo'</div></p>';
echo'<br/>';
}

//récupération du numéro de quizz le plus grand, et mise dans une variable session pour y avoir accès à au dernier script de création de quizz (requêtes sql)
$requeteMAX = "SELECT * FROM QUIZZ WHERE Numquizz >= (SELECT MAX(Numquizz) FROM QUIZZ)";
$resultatMAX=$BDD->query($requeteMAX);
$ligneMAX = $resultatMAX->fetch();
$numQuizz=$ligneMAX['Numquizz']; 
$newNumQuizz=$numQuizz+1;

$_SESSION['newNumQuizz']=$newNumQuizz;
//de même pour les catégories du quizz de la page précédente
$cpt=0;
foreach ($_POST["categories"] as $categorie)
		{
            $cpt=$cpt+1;
            $_SESSION['categorie'.$cpt.'']=$categorie;
        }

$_SESSION['nbrCategories']=$cpt;


//ancre pour avoir accès en méthode post à ces variables pour la page d'après
echo'<input type="hidden" name="nbrQuestions" value="'.$nbrQuestions.'"></input>';
echo'<input type="hidden" name="newNumQuizz" value="'.$newNumQuizz.'"></input>';

}
echo'<div id="conteneurBouton"><p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/></p></div>';
}

?>
</div>
</form>
</div>
</div>




    </body>
    <?php include("includes/footer.html"); ?>
</html>