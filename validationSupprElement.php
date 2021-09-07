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
//récup champ
$champ=$_GET['champ'];
//on supprime la réponse
if ($champ=="Reponse")
{
	$numQuizz=$_GET['numQuizz'];
	$numRep=$_GET['numRep'];
	$requeteDelRep = $BDD->prepare("DELETE FROM REPONSE WHERE Numrep =:numRep" );
    $requeteDelRep->bindValue('numRep',$numRep,PDO::PARAM_INT);
	$boolDelRep = $requeteDelRep->execute();
	//si ça n'a pas marché on avertit l'admin
    if ($boolDelRep)
{
    echo'Opération réussie <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
}
else
{
    echo'L\'opération a échoué <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
}
}
//de même pour le cas où c'est une réponse
elseif($champ=="Question")
{
	$numQuizz=$_GET['numQuizz'];
	$numQuest=$_GET['numQuest'];
	$requeteDelQuest = $BDD->prepare("DELETE FROM QUESTION WHERE Quizz=:numQuizz AND Numquest=:numQuest" );
	$requeteDelQuest->bindValue('numQuizz',$numQuizz,PDO::PARAM_INT);
	$requeteDelQuest->bindValue('numQuest',$numQuest,PDO::PARAM_INT);
	$boolDelQuest = $requeteDelQuest->execute();

	$requeteDelRep = $BDD->prepare("DELETE FROM REPONSE WHERE Quizz=:numQuizz AND Quest=:numQuest" );
	$requeteDelRep->bindValue('numQuizz',$numQuizz,PDO::PARAM_INT);
	$requeteDelRep->bindValue('numQuest',$numQuest,PDO::PARAM_INT);
	$boolDelRep = $requeteDelRep->execute();

	if ($boolDelQuest and $boolDelRep)
	{
		echo'Opération réussie <br/>';
		echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
	}
	else
	{
		echo'L\'opération a échoué <br/>';
		echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="Retour" value="Retour"/></a>';
	}
}


?>
</body>
<?php include("includes/footer.html"); ?>
</html>