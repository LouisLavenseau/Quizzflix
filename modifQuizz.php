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
		<link rel="stylesheet"  href="style/styleModifQuizz.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	
	<body>

	
<?php
//on récupère toutes les informations concernant le quizz nécessaires à la suite des évènements 
$numQuizz=$_GET['numQuizz'];
    $_SESSION['numQuizz']=$numQuizz;
    $requeteQuizz = "SELECT * FROM QUIZZ WHERE Numquizz = $numQuizz";
    $resultatQuizz=$BDD->query($requeteQuizz);
    $ligneQuizz=$resultatQuizz->fetch();
    $nomQuizz=$ligneQuizz['Nomquizz'];
    $imageQuizz=$ligneQuizz['Image'];
    $descrQuizz=$ligneQuizz['Description'];
    $categorie1Quizz=$ligneQuizz['Categorie_1'];
    $categorie2Quizz=$ligneQuizz['Categorie_2'];
    $categorie3Quizz=$ligneQuizz['Categorie_3'];

    $requeteMAX = ("SELECT * FROM QUESTION WHERE Quizz = $numQuizz AND Numquest >= (SELECT MAX(Numquest) FROM QUESTION)");
    $resultatMAX=$BDD->query($requeteMAX);
    $ligneMAX = $resultatMAX->fetch();
    $nbrQuests=$ligneMAX['Numquest']; 
//on écrit les champs qui peuvent être modifiés
echo'    
<h1> Modification du quizz : '.$nomQuizz.' </h1> <br/>
<div id="conteneur">


<h2> Informations générales du quizz </h2>
Appuyez sur un champ pour le modifier ou le supprimer

<h3> Nom de la série </h3>
<div class="ligne"> <a href="modifElement.php?champ=Nomquizz&amp;numQuizz='.$numQuizz.'"> '.$nomQuizz.' </a> </div>

<h3> Description du quizz </h3>
<div class="ligne"> <a href="modifElement.php?champ=Description&amp;numQuizz='.$numQuizz.'"> '.$descrQuizz.' </a> </div>

<h3> Image du quizz </h3>
<div class="ligne"> <a href="modifElement.php?champ=Image&amp;numQuizz='.$numQuizz.'"> <img src="images/'.$imageQuizz.'" id="image"> </a> </div>

<h3> Catégories du quizz </h3>
<div class="ligne">  Catégorie 1 : <a href="modifElement.php?champ=Categorie_1&amp;numQuizz='.$numQuizz.'">';if(is_null($categorie1Quizz)){echo'vide';}else{echo''.$categorie1Quizz.'';}echo' </a> </div>
<div class="ligne">  Catégorie 2 : <a href="modifElement.php?champ=Categorie_2&amp;numQuizz='.$numQuizz.'">';if(is_null($categorie2Quizz)){echo'vide';}else{echo''.$categorie2Quizz.'';}echo' </a> </div>
<div class="ligne">  Catégorie 3 : <a href="modifElement.php?champ=Categorie_3&amp;numQuizz='.$numQuizz.'">';if(is_null($categorie3Quizz)){echo'vide';}else{echo''.$categorie3Quizz.'';}echo' </a> </div>

<h2> Questions </h2>';
$numQuest;
$cpt=0;//on select toutes les questions qui ont le même numéro de quizz en clé étrangère
    $requeteQuest = ("SELECT * FROM QUESTION WHERE Quizz = $numQuizz");
    $resultatQuest=$BDD->query($requeteQuest);
    while ($ligneQuest = $resultatQuest->fetch())//pour chacune de ces questions, on récupère des informations
    {$numQuest=$ligneQuest['Numquest'];
    $question=$ligneQuest['Question'];
    $typeQuestionFacile=$ligneQuest['Typefacile'];
    $typeQuestionMoyen=$ligneQuest['Typemoyen'];
    $typeQuestionDifficile=$ligneQuest['Typedifficile'];
    $cpt++;//on écrit la question avec un lien pour la modifier, et si ce n'est pas une question vrai/faux, on met un bouton pour changer ses types de question selon la difficulté 
    echo'<h3> Question '.$cpt.' </h3> 
    <div class="ligne"><a href="modifElement.php?champ=Question&amp;numQuizz='.$numQuizz.'&amp;numQuest='.$numQuest.'&amp;choix=texte">'.$question.'</a>';
    if($typeQuestionFacile=="QCM" or  $typeQuestionFacile=="OUV"){echo'<a href="modifElement.php?champ=Question&amp;numQuizz='.$numQuizz.'&amp;numQuest='.$numQuest.'&amp;choix=autre">
        <p class="bouton"><input type="submit" value="Changer le type de question"></a></div>';}
    echo'
    <h4> Liste des réponses </h4>';

        //pour chaque question on parcourt toutes les réponses associées 
    $requeteRep = ("SELECT * FROM REPONSE WHERE Quizz = $numQuizz AND Quest = $numQuest");
    $resultatRep=$BDD->query($requeteRep);
    while ($ligneRep = $resultatRep->fetch())
    {
        $reponse=$ligneRep['Reponse'];
        $numRep=$ligneRep['Numrep'];//on récupère des informations concernant ces réponses. On écrit la réponse avec le lien pour la modifier
        echo'<div class="ligne"><p><a href="modifElement.php?champ=Reponse&amp;numRep='.$numRep.'&amp;choix=texte">'.$reponse.'</a>
        <a href="modifElement.php?champ=Reponse&amp;numQuizz='.$numQuizz.'&amp;numRep='.$numRep.'&amp;choix=autre">
        <p class="bouton"><input type="submit" value="Bonne ou mauvaise réponse"></a></p></div>';
    }
    $requeteRep = ("SELECT COUNT(Numrep) FROM REPONSE WHERE Quizz = $numQuizz AND Quest = $numQuest");
        $resultatRep=$BDD->query($requeteRep);
        $tabNbrReps= $resultatRep->fetch();
        $nbrReps=$tabNbrReps[0];//on récupère le nombre de réponses que comporte la question. Selon le nombre de réponses et les types de questions selon la difficulé, on affiche ou pas le bouton pour permettre d'ajouter une réponse
        if ($nbrReps<3 and $typeQuestionFacile!="VF")
        {
            echo'<br/><a href="ajouterElement.php?champ=Reponse&amp;numQuizz='.$numQuizz.'&amp;numQuest='.$numQuest.'"><p class="bouton"><input type="submit" value="Ajouter une réponse"></p></a>';
        }
        if ($nbrReps==3 and $typeQuestionMoyen=="QCM")
        {
            echo'<br/><a href="ajouterElement.php?champ=Reponse&amp;numQuizz='.$numQuizz.'&amp;numQuest='.$numQuest.'"><p class="bouton"><input type="submit" value="Ajouter une réponse"></p></a>';
        }
        if ($nbrReps<6 and $nbrReps>3 and $typeQuestionDifficile=="QCM")
        {
            echo'<br/><a href="ajouterElement.php?champ=Reponse&amp;numQuizz='.$numQuizz.'&amp;numQuest='.$numQuest.'"><p class="bouton"><input type="submit" value="Ajouter une réponse"></p></a>';
        }
    
}//bouton pour ajouter une question
echo'<br/><a href="ajouterElement.php?champ=Question&amp;numQuizz='.$numQuizz.'"><p class="bouton"><input type="submit" value="Ajouter une question"></p></a></div>';
//tous les liens de la page comportent des paramètres de type get
?>





<a href="accueilAdmin.php"><p class="bouton"><input type="submit" id="valider" value="Retour accueil"></p></a>

</body>
<?php //include("includes/footer.html"); ?>
</html>