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
		<link rel="stylesheet"  href="style/styleModifElement.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	
	<body>
    <div id="conteneur">
	<form action="validationAjouterElement.php" method="POST" enctype="multipart/form-data">
	
<?php

//Recupération du champ
$champ=$_GET['champ'];

//On place de manière invisible le champ pour le récupérer sur le script d'après avec la méthode post 
echo'<input type="hidden" name="champ" value="'.$champ.'">  ';
//Si le champ c'est Reponse, alors on propose le formulaire pour créer une nouvelle réponse
if ($champ=="Reponse")
{
    echo'<input type="hidden" name="numQuizz" value="'.$_GET['numQuizz'].'">';
    echo'<input type="hidden" name="numQuest" value="'.$_GET['numQuest'].'">';
    echo'<h3>Reponse</h3>
    <input type="text" class="text" name="text" /><br/>
    <h3>Bonne ou mauvaise réponse</h3>
    <ul>
    <p><input type="radio" name="bonneRep" value="oui"> Bonne réponse</p>
    <p><input type="radio" name="bonneRep" value="non"> Mauvaise réponse</p>
    </ul><br/>
    <p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';
}
//De même pour question
elseif ($champ=="Question")
{
    echo'<input type="hidden" name="numQuizz" value="'.$_GET['numQuizz'].'">';
    echo'<h3>Question</h3>
    <input type="text" class="text" name="text" /><br/>
    <h3>Types de questions par niveau</h3>
    <h2>Niveau facile</h2>
<ul>
<p><input type="radio" name="typeFacile" value="QCM"> QCM</p>
<p><input type="radio" name="typeFacile" value="OUV"> Question ouverte </p>
<p><input type="radio" name="typeFacile" value="VF"> Vrai/faux </p>
</ul>
<h2>Niveau moyen</h2>
<ul>
<p><input type="radio" name="typeMoyen" value="QCM"> QCM</p>
<p><input type="radio" name="typeMoyen" value="OUV"> Question ouverte </p>
<p><input type="radio" name="typeMoyen" value="VF"> Vrai/faux </p>
</ul>
<h2>Niveau diffcile</h2>
<ul>
<p><input type="radio" name="typeDifficile" value="QCM"> QCM</p>
<p><input type="radio" name="typeDifficile" value="OUV"> Question ouverte </p>
<p><input type="radio" name="typeDifficile" value="VF"> Vrai/faux </p>
</ul> 
    <p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';
}

?>
</div>
</body>
<?php include("includes/footer.html"); ?>
</html>
