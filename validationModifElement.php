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

//recup du champ
$champ=$_POST['champ'];

//si c'est une image, on traite le fichier upladé
if($champ=="Image")
    {
        $numQuizz=$_POST['numQuizz'];
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

    $requeteModifQuizz = $BDD->prepare("UPDATE QUIZZ SET $champ =:champ WHERE Numquizz = $numQuizz" );
    $requeteModifQuizz->bindValue('champ',$_FILES['fichier']['name'],PDO::PARAM_STR);
    $boolModifQuizz = $requeteModifQuizz->execute();
    if ($boolModifQuizz)
    {
        echo'Opération réussie <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
    }   
    else
    {
        echo'L\'opération a échoué <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="Retour"/></a>';
    }
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
//si c'est une question, on check si on doit changer le texte ou les types de question selon le niveau de difficulté
elseif ($champ=="Question")
{
    $numQuest=$_POST['numQuest'];
    $numQuizz=$_POST['numQuizz'];
    $choix=$_POST['choix'];
    if ($choix=="texte")
    {//on change le texte de la question
    $nouvelleValeur=$_POST[''.$champ.''];//check de si la saisie n'est pas vide
    if (empty($nouvelleValeur)){echo'Erreur :saisie vide <p><a href="modifQuizz.php?numQuizz='.$numQuizz.'">
        <p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a></p>';}else{
    $requeteModifQuizz = $BDD->prepare("UPDATE QUESTION SET $champ =:champ WHERE Quizz =:numQuizz AND Numquest=:numQuest" );
    $requeteModifQuizz->bindValue('champ',$nouvelleValeur,PDO::PARAM_STR);
    $requeteModifQuizz->bindValue('numQuizz',$numQuizz,PDO::PARAM_INT);
    $requeteModifQuizz->bindValue('numQuest',$numQuest,PDO::PARAM_INT);
    $boolModifQuizz = $requeteModifQuizz->execute();
    if ($boolModifQuizz)
{
    echo'Opération réussie <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
}
else
{
    echo'L\'opération a échoué <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
}
    }
}
    else
    {//sinon on change les catégories
    $typeFacile=$_POST['typeFacile'];
    $typeMoyen=$_POST['typeMoyen'];
    $typeDifficile=$_POST['typeDifficile'];
    $requeteModifQuizz = $BDD->prepare("UPDATE QUESTION SET Typefacile=:typeFacile, Typemoyen=:typeMoyen, TypeDifficile=:typeDifficile WHERE Quizz =:numQuizz AND Numquest=:numQuest");
    $requeteModifQuizz->bindValue('typeFacile',$typeFacile,PDO::PARAM_STR);
    $requeteModifQuizz->bindValue('typeMoyen',$typeMoyen,PDO::PARAM_STR);
    $requeteModifQuizz->bindValue('typeDifficile',$typeDifficile,PDO::PARAM_STR);
    $requeteModifQuizz->bindValue('numQuizz',$numQuizz,PDO::PARAM_INT);
    $requeteModifQuizz->bindValue('numQuest',$numQuest,PDO::PARAM_INT);

    $boolModifQuizz = $requeteModifQuizz->execute();
    if ($boolModifQuizz)
{
    echo'Opération réussie <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
}
else
{
    echo'L\'opération a échoué <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
}
    }
}
//si c'est réponse, pareil
elseif ($champ=="Reponse")
{
    $choix=$_POST['choix'];
    $numRep=$_POST['numRep'];
    $numQuizz=$_POST['numQuizz'];
    if ($choix=="texte")
    {//on change le texte
    $nouvelleValeur=$_POST[''.$champ.''];
    if (empty($nouvelleValeur)){echo'Erreur :aisie vide<a href="modifQuizz.php?numQuizz='.$numQuizz.'">
        <p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a></p>';}else{
    $requeteModifQuizz = $BDD->prepare("UPDATE REPONSE SET $champ =:champ WHERE Numrep =:numRep" );
    $requeteModifQuizz->bindValue('champ',$nouvelleValeur,PDO::PARAM_STR);
    $requeteModifQuizz->bindValue('numRep',$numRep,PDO::PARAM_INT);
    $boolModifQuizz = $requeteModifQuizz->execute();
    if ($boolModifQuizz)
    {
        echo'Opération réussie <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
    }
    else
    {
        echo'L\'opération a échoué <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
    }
    }
}
    else
    {//on change le fait que ce soit une bonne réponse ou pas
        $bonneRep=$_POST['bonneRep'];
        $requeteModifQuizz = $BDD->prepare("UPDATE REPONSE SET Bonnerep =:bonneRep WHERE Numrep =:numRep" );
        $requeteModifQuizz->bindValue('bonneRep',$bonneRep,PDO::PARAM_STR);
        $requeteModifQuizz->bindValue('numRep',$numRep,PDO::PARAM_INT);
        $boolModifQuizz = $requeteModifQuizz->execute();

        if ($boolModifQuizz)
    {
        echo'Opération réussie <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
    }
    else
    {
        echo'L\'opération a échoué <br/>';
        echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
    }
    }
}

else
{//sinon on change le texte dans le cas d'une description, titre quizz, catégorie
$numQuizz=$_POST['numQuizz'];
$nouvelleValeur=$_POST['text'];
if (empty($nouvelleValeur)){echo'Erreur :aisie vide<a href="modifQuizz.php?numQuizz='.$numQuizz.'">
    <p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a></p>';}else{
$requeteModifQuizz = $BDD->prepare("UPDATE QUIZZ SET $champ =:champ WHERE Numquizz =:numQuizz" );
$requeteModifQuizz->bindValue('champ',$nouvelleValeur,PDO::PARAM_STR);
$requeteModifQuizz->bindValue('numQuizz',$numQuizz,PDO::PARAM_INT);
$boolModifQuizz = $requeteModifQuizz->execute();

if ($boolModifQuizz)
{
    echo'Opération réussie <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
}
else
{
    echo'L\'opération a échoué <br/>';
    echo'<br/><a href="modifQuizz.php?numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" class="btn btn-primary" id="retour" value="retour"/></a>';
}
}
}

?>
</body>
<?php include("includes/footer.html"); ?>
</html>