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
    <div id="grandConteneur">
    <div id="conteneur">
	<form action="validationModifElement.php" method="POST" enctype="multipart/form-data">
<?php

//récupération de la variable champ
$champ=$_GET['champ'];

//on la rend disponible pour le script suivant
echo'<input type="hidden" name="champ" value="'.$champ.'">  ';

//si on a une image, on met un input type file pour l'upload
if($champ=="Image")
{   
    $numQuizz=$_GET['numQuizz'];
    echo'<h3>Choisissez un une image</h3>';
    echo'<input type="hidden" name="numQuizz" value="'.$numQuizz.'">
    <input type="file" name="fichier" id="upload">
    Attention à vérifier que son nom ne dépasse pas 10 caractères et soit exclusivement composé de lettres et chiffres
    <p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';

}
// si on a une question, on récupère le paramètre choix qui détermine s'il faut modifier le texte de la question ou alors ses types de questions selon le niveau de difficulté
elseif ($champ=="Question")
{
    $numQuizz=$_GET['numQuizz'];

    echo'<input type="hidden" name="numQuizz" value="'.$numQuizz.'">';
    $choix=$_GET['choix'];
    echo'<input type="hidden" name="choix" value="'.$choix.'">';
    $numQuest=$_GET['numQuest'];
    echo'<input type="hidden" name="numQuest" value="'.$numQuest.'">';
    if($choix=="texte")
    {//modif texte
        echo'<h3>Saisissez la nouvelle question</h3>';
        echo'<input type="text" class="text" name="'.$champ.'" /><br/><br/>
        <p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';
    }
else{//modif type question
echo'
<h3>Types de questions par niveau</h3>
<h2>Niveau facile</h2>
<ul>
<p><input type="radio" name="typeFacile" value="QCM"> QCM</p>
<p><input type="radio" name="typeFacile" value="OUV"> Question ouverte </p>
</ul>
<h2>Niveau moyen</h2>
<ul>
<p><input type="radio" name="typeMoyen" value="QCM"> QCM</p>
<p><input type="radio" name="typeMoyen" value="OUV"> Question ouverte </p>
</ul>
<h2>Niveau difficile</h2>
<ul>
<p><input type="radio" name="typeDifficile" value="QCM"> QCM</p>
<p><input type="radio" name="typeDifficile" value="OUV"> Question ouverte </p>
</ul> 
<br/>
<p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';
}
echo'</form><br/><br/>
<h3> Supprimer la question </h3>
<a href="validationSupprElement.php?champ=Question&amp;numQuizz='.$numQuizz.'&amp;numQuest='.$numQuest.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Supprimer"/></a> <br/><br/>';
}//dessus, possibilité de supprimer la question
//si on a une réponse, on récupère aussi le paramètre choix pour déterminer le formulaire à proposer
elseif ($champ=="Reponse")
{
    $choix=$_GET['choix'];
    $numRep=$_GET['numRep'];
    $numQuizz=$_SESSION['numQuizz'];

    echo'<input type="hidden" name="choix" value="'.$choix.'">
    <input type="hidden" name="numRep" value="'.$numRep.'">
    <input type="hidden" name="numQuizz" value="'.$numQuizz.'">';
    if($choix=="texte")
    {//texte
        echo'<h3>Saisissez la nouvelle réponse</h3>';
        echo'<input type="text" class="text" name="'.$champ.'" /><br/><br/>
        <p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';
    }
elseif ($choix=="autre")//si c'est une bonne ou mauvaise réponse
{
echo'
<ul>
<p><input type="radio" name="bonneRep" value="oui"> Bonne réponse </p>
<p><input type="radio" name="bonneRep" value="non"> Mauvaise réponse </p>
</ul>
<br/>
<p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';
}
}
//si c'est autre chose que réponse, question, image, c'est à dire titre quizz, description, catégories (un champ de texte dans tous les cas)
else
{
    echo'<h3>Choisissez le nouveau texte</h3>';
    echo'<h5 class="text-muted">Attention, dans le cas de modification des catégories, vous devez recopier lettre pour lettre le nom des catégories sous peine de voir l\'action échouée.</h5><br/>';
    $numQuizz=$_GET['numQuizz'];
    echo'<input type="hidden" name="numQuizz" value="'.$numQuizz.'">
    <p><input type="text" class="text" name="text"/></p>
    <p class="bouton"><input type="submit" class="btn btn-primary" id="valider" value="Valider"/>';
}

?>
</div>
</div>
</body>
<?php include("includes/footer.html"); ?>
</html>