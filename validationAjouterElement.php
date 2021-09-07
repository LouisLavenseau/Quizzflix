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
// récup champ
$champ=$_POST['champ'];

if ($champ=="Reponse")
{   //récup du plus haut numrep pour l'assignation du numrep de la nouvelle réponse créée
    $requeteMax = ("SELECT * FROM REPONSE WHERE Numrep >= (SELECT MAX(Numrep) FROM REPONSE)");
    $resultatMax=$BDD->query($requeteMax);
    $ligneMax = $resultatMax->fetch();
    $NumRepMax=$ligneMax['Numrep']; 
    $newNumRepMax=$NumRepMax+1;

    $numQuizz=$_POST['numQuizz'];
    $numQuest=$_POST['numQuest'];
    $reponse=$_POST['text'];//si le champs saisi est vide, on renvoit un message d'erreur
    if (empty($reponse)){echo'Erreur : saisie vide<a href="modifQuizz.php?numQuizz='.$numQuizz.'">
        <p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a></p>';}else{
    $bonneRep=$_POST['bonneRep'];//requête pour entrer la réponse dans la BDD
    $requeteAddRep = $BDD->prepare("INSERT INTO REPONSE (Numrep, Quizz, Quest, Reponse, Bonnerep) VALUES (:numRep, :quizz, :numQuest, :reponse, :bonneRep)");
	$requeteAddRep->bindValue('numRep',$newNumRepMax,PDO::PARAM_INT);
    $requeteAddRep->bindValue('quizz',$numQuizz,PDO::PARAM_INT);
    $requeteAddRep->bindValue('numQuest',$numQuest,PDO::PARAM_INT);
    $requeteAddRep->bindValue('reponse',$reponse,PDO::PARAM_STR);
    $requeteAddRep->bindValue('bonneRep',$bonneRep,PDO::PARAM_STR);
    $boolAddRep = $requeteAddRep->execute();
    //on envoie un message selon si la requête a échoué ou non 
    if ($boolAddRep)
{
    echo'Opération réussie <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
}
else
{
    echo'L\'opération a  échoué <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
}
}
}//si c'est une question, on fait une requête adapté à une question
elseif ($champ=="Question")
{
    $numQuizz=$_POST['numQuizz'];
    $requeteMax = ("SELECT MAX(Numquest)FROM QUESTION WHERE Quizz= $numQuizz");
    $resultatMax=$BDD->query($requeteMax);
    $ligneMax = $resultatMax->fetch();
    $NumQuestMax=$ligneMax[0]; 
    $newNumQuestMax=$NumQuestMax+1;//récup du plus haut numéro de question pour ce quizz pour l'assignation de la nouvelle question

    
    $question=$_POST['text'];
    $typeFacile=$_POST['typeFacile'];
    $typeMoyen=$_POST['typeMoyen'];
    $typeDifficile=$_POST['typeDifficile'];//récup des types de questions saisis selon le niveau de difficulté
    if (empty($question)){echo'Erreur : saisie vide<a href="modifQuizz.php?numQuizz='.$numQuizz.'">
        <p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a></p>';}else{

$erreur1="";
$erreur2="";//si les niveaux de difficultés ne sont pas compatibles, on renvoie une erreur
    if(($typeFacile=='OUV' and ($typeMoyen=='QCM' or $typeDifficile=='QCM'))
    or ($typeMoyen=='OUV' and $typeDifficile=='QCM'))
    {
        $erreur1='oui';       
    }
if (($typeFacile=='VF' and ($typeMoyen!='VF' or $typeDifficile!='VF' ))
    or ($typeMoyen=='VF' and ($typeFacile='VF' or $typeDifficile!='VF' ))
    or ($typeDifficile=='VF' and ($typeMoyen!='VF' or $typeFacile!='VF' )))
{
    $erreur2='oui';
}

if ($erreur1=='oui'){echo 'Erreur : vous ne pouvez pas placer une question ouverte sur un niveau plus facile qu\'un niveau de QCM plus difficile pour la même question';}
elseif($erreur2=='oui'){echo'Erreur : pour au moins une question, vous avez sélectionné Vrai/Faux pour un niveau de difficulté mais pas pour les autres.';  }


//requete
    $requeteAddQuest = $BDD->prepare("INSERT INTO QUESTION (Numquest, Quizz, Question, Typefacile, Typemoyen, Typedifficile) VALUES (:numQuest, :numQuizz, :question, :typeFacile, :typeMoyen, :typeDifficile)");
	$requeteAddQuest->bindValue('numQuest',$newNumQuestMax,PDO::PARAM_INT);
    $requeteAddQuest->bindValue('numQuizz',$numQuizz,PDO::PARAM_INT);
    $requeteAddQuest->bindValue('question',$question,PDO::PARAM_STR);
    $requeteAddQuest->bindValue('typeFacile',$typeMoyen,PDO::PARAM_STR);
    $requeteAddQuest->bindValue('typeMoyen',$typeMoyen,PDO::PARAM_STR);
    $requeteAddQuest->bindValue('typeDifficile',$typeDifficile,PDO::PARAM_STR);
    $boolAddQuest = $requeteAddQuest->execute();
//si la requete échoue on avertit l'admin
    if ($boolAddQuest)
    {
        echo'Opération réussie <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
    }
    else
    {
        echo'L\'opération a  échoué <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
    }
}}
?>
</form>
</body>
<?php include("includes/footer.html"); ?>
</html>