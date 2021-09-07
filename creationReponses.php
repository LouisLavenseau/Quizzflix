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
		<link rel="stylesheet"  href="style/styleCreationReponses.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
<div id="grandConteneur">
	<h1> Création du quizz </h1>

<br/>
<br/>

<div class="conteneur">

<form method="POST" action="validationCreationQuizz.php">


<?php        



$nbrQuestions=$_POST['nbrQuestions'];
$newNumQuizz=$_POST['newNumQuizz'];





$erreur1="";
$erreur2="";

for ($numQuestion=1; $numQuestion<=$nbrQuestions; $numQuestion++)

{    
//Test de si l'admin a rentré une question de type ouverte pour un niveau inférieur à un niveau où le type est qcm
if(($_POST['choixQuestionFacile'.$numQuestion.'']=='OUV' and ($_POST['choixQuestionMoyen'.$numQuestion.'']=='QCM' or $_POST['choixQuestionDifficile'.$numQuestion.'']=='QCM'))
    or ($_POST['choixQuestionMoyen'.$numQuestion.'']=='OUV' and $_POST['choixQuestionDifficile'.$numQuestion.'']=='QCM'))
    {
        $erreur1='oui';       
    }//Test de si vrai/faux est sélectionné quelque part mais pas ailleurs (autres niveaux de difficulté de la même question)
if (($_POST['choixQuestionFacile'.$numQuestion.'']=='VF' and ($_POST['choixQuestionMoyen'.$numQuestion.'']!='VF' or $_POST['choixQuestionDifficile'.$numQuestion.'']!='VF' ))
    or ($_POST['choixQuestionMoyen'.$numQuestion.'']=='VF' and ($_POST['choixQuestionFacile'.$numQuestion.'']!='VF' or $_POST['choixQuestionDifficile'.$numQuestion.'']!='VF' ))
    or ($_POST['choixQuestionDifficile'.$numQuestion.'']=='VF' and ($_POST['choixQuestionMoyen'.$numQuestion.'']!='VF' or $_POST['choixQuestionFacile'.$numQuestion.'']!='VF' )))
{
    $erreur2='oui';
}
}//affichage des erreurs si elles se réalisent
if ($erreur1=='oui'){echo 'Erreur : vous ne pouvez pas placer une question ouverte sur un niveau plus facile qu\'un niveau de QCM plus difficile pour la même question';}
elseif($erreur2=='oui'){echo'Erreur : pour au moins une question, vous avez sélectionné Vrai/Faux pour un niveau de difficulté mais pas pour les autres.';  }
else
{

//sinon on exécute le reste du code





//on parcourt toutes les questions
for ($numQuestion=1; $numQuestion<=$nbrQuestions; $numQuestion++)
{
//on pose une ancre invisible pour récupérer sur le script suivants ces variables (moins de problèmes de bugs qu'avec les variables SESSION)
    echo'<input type="hidden" name="choixQuestionFacile'.$numQuestion.'" value="'.$_POST['choixQuestionFacile'.$numQuestion.''].'">';
    echo'<input type="hidden" name="choixQuestionMoyen'.$numQuestion.'" value="'.$_POST['choixQuestionMoyen'.$numQuestion.''].'">';
    echo'<input type="hidden" name="choixQuestionDifficile'.$numQuestion.'" value="'.$_POST['choixQuestionDifficile'.$numQuestion.''].'">';
    echo'<input type="hidden" name="question'.$numQuestion.'" value="'.$_POST['question'.$numQuestion.''].'">';

//affichage de la question
echo'<div class="ligne">';
echo'<h4>&nbsp;&nbsp; Question '.$numQuestion.' : '.$_POST['question'.$numQuestion.''].'</h4>';
echo'<br/>';
echo'<p> &nbsp;&nbsp;&nbsp;&nbsp;' ;
echo'<br/>';
echo'<div class="niveaux">';


echo'<div class="niveau">';
echo'<div class="niveau2">';
//si c'est une question de type qcm/question ouverte, on met autant de champs de texte pour les réponses qu'il en faut (3 ou 4 ou 6) plus des checbox qui sont cochées pour les réponses justes
if ($_POST['choixQuestionFacile'.$numQuestion.'']=="QCM" or $_POST['choixQuestionMoyen'.$numQuestion.'']=="QCM" or $_POST['choixQuestionDifficile'.$numQuestion.'']=="QCM")
{
    $nbr=0;//détermination du nombres de champs de réponses à mettre
    if ($_POST['choixQuestionDifficile'.$numQuestion.'']=="QCM"){$nbr=6;}
    else{if ($_POST['choixQuestionMoyen'.$numQuestion.'']=="QCM"){$nbr=4;}
    else{$nbr=3;}}
    for ($i=1;$i<=$nbr;$i++)
    {
        echo'<p><div><input type="checkbox" class="check" name="bonneRep'.$numQuestion.'[]" value="'.$i.'" />&nbsp;   <label for="pseudo" class="text"></label> <input type="text" placeholder="Réponse" name="rep'.$i.'quest'.$numQuestion.'" class="text"/> </div> </p>';
    }
    echo'<input type="hidden" name="nbrRepQ'.$numQuestion.'" value="'.$nbr.'">';
 
}
//Si il y a que des questions ouvertes on met un champ de texte
if ($_POST['choixQuestionFacile'.$numQuestion.'']=="OUV" and $_POST['choixQuestionMoyen'.$numQuestion.'']=="OUV" and $_POST['choixQuestionDifficile'.$numQuestion.'']=="OUV")
{
    echo'<p><label for="pseudo" class="text"></label> <input type="text" placeholder="Réponse" name="repQuest'.$numQuestion.'" class="text"/> </div> </p>';
}
//de types radio si vrai/faux
if($_POST['choixQuestionFacile'.$numQuestion.'']=="VF")
{
    echo'<div class="rep">&nbsp;<input type="radio" name="repQuest'.$numQuestion.'" value="vrai"> &nbsp; VRAI </div>' ;
    echo'<div class="rep">&nbsp;<input type="radio" name="repQuest'.$numQuestion.'" value="faux"> &nbsp; FAUX </div>';
}
echo'</div>';
echo'</div>';

echo'</div>';
echo'<br/>';
echo'</div>';
echo'<br/>';
}
echo'<p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/></p>';
}
//ancre pour récupérer ces variables
echo'<input type="hidden" name="nbrQuestions" value="'.$nbrQuestions.'"></input>';
echo'<input type="hidden" name="newNumQuizz" value="'.$newNumQuizz.'"></input>';

?>


</div>
</form>
</div>
    </body>
    <?php include("includes/footer.html"); ?>
</html>