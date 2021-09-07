<?php
    session_start();
    include("includes/fonctions.php");
    require("includes/connect.php");
    
    $Numquest=$_GET['Numquest'];           
    $Numquizz=$_GET['Numquizz'];
    $score=$_GET['score']; 
    if (isset($_GET['temps1']))
    {
        $temps1=$_GET['temps1'];
    }
    
    //récupération de la question
    $requeteQuest = $BDD -> prepare("SELECT * FROM QUESTION WHERE Numquest = ? AND Quizz=?");
    $requeteQuest->execute(array($Numquest,$Numquizz));
    $question=$requeteQuest->fetch();

    //récupération du type de question en fonction de la difficulté choisie
    $difficulte=$_GET['difficulte'];
    $typeQuest = typeQuest($difficulte,$question);

    // vérification qu'une réponse (ou que le bon nombre de réponses a bien été coché)    
    if(isset($_POST['Valider']))
    {
        if($typeQuest=="QCM")
        {
            //récupération du nombre de bonnes réponses associées à la question
            $nbBRep = recupNbBRep($BDD,$Numquizz,$Numquest);
            // définition du nombre de réponses totales en fonction de la difficulté
            $nbRepTot=nbRepTot($difficulte);
        }
        else
        {
            $nbBRep = 1;
            if($typeQuest=="VF")
            {
                $nbRepTot=2;
            }
            else
            {
                $nbRepTot=1;
            }
        }    
        $k=0;
        for($i=0;$i<$nbRepTot;$i++)
        {
            if(isset($_POST["rep$i"]))
            { 
                $rep[$k] = $_POST["rep$i"];
                $k++;
            }  
        }

        if($k==$nbBRep) //si bon nombre de réponses choisies on on passe à la correction
        {
            if($nbBRep==1)
            {
                $reponse = "rep0=".$rep[0];
            }
            else
            {
                $reponse = "&rep1=".$rep[1]."&rep0=".$rep[0];
            }
            header("location:correction.php?Numquizz=".$Numquizz."&score=".$score."&nbRepTot=".$nbRepTot."&typeQuest=".$typeQuest."&Numquest=".($Numquest)."&difficulte=".$difficulte."&".$reponse."&temps1=".$temps1);
        }        
        else // sinon message d'erreur 
        {
            if($nbBRep==1){$message = "<div><h5 class='text-danger'>Il faut choisir une réponse</h5></div>";}
            else {$message = "<div id='message'><h5 class='text-danger'>Il faut choisir deux réponses</h5></div>";}
        }
    }
    
    include("includes/barre.php");
?>

<html>
    <head>
        <?php
        echo"<title>Question ".$Numquest."</title>";
        ?>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="style/styleQuestion.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>    
    </head>

    <body>
        <div id=grandConteneur>
        <div id=fond>
        <?php                                
            if ($Numquest==1 && empty($_POST['Valider']))
            {
                $temps1 = time();
            }
            
            echo "<div id=conteneur><h2 id='question'>Question $Numquest</h2>";
            echo "<br/><h4>".$question['Question']."</h4>";
            if(isset($message))
            {
                echo $message;
            }
            //affichage des réponses si QCM
            if($typeQuest=="QCM")
            {
                //récupération du nombre de bonnes réponses associées à la question
                $nbBRep = recupNbBRep($BDD,$Numquizz,$Numquest);

                // définition du nombre de réponses totales en fonction de la difficulté
                $nbRepTot=nbRepTot($difficulte);
				
				//récupération des bonnes réponses
				$bonneRep = recupBonneRep($BDD,$nbBRep,$Numquest,$Numquizz);
				$k=0;
				while ($k<$nbBRep)
				{
					$REP[$k]=$bonneRep[$k];
					$k+=1;
				}
				
				//récupération du bon nombre de mauvaises réponses 
                $requeteRep = $BDD -> prepare("SELECT * FROM REPONSE WHERE Bonnerep =? AND Quest =? AND Quizz=? "); 
                $requeteRep -> execute(array('non', $Numquest,$Numquizz));
                for($j=$k;$j<$nbRepTot;$j++)
                {
                    $ligne=$requeteRep->fetch();
                    $REP[$j]=$ligne['Reponse'];
                }

                shuffle($REP); //mélange
                echo "<form method=POST action='question.php?Numquizz=".$Numquizz."&Numquest=".$Numquest."&score=".$score."&difficulte=".$difficulte."&temps1=".$temps1."'><div>";
                for($i=0;$i<$nbRepTot;$i++)
                {
                    if ($nbBRep==1)
                    {
                        echo '<br/><input type="radio" name="rep'.$i.'" value= "'.$REP[$i].'" /><label for="rep'.$i.'">&nbsp'.$REP[$i].'</label>'; 
                    }
                    else
                    {
                        if ($nbBRep==2)
                        {
                            echo '<br/><input type="checkbox" name="rep'.$i.'" value= "'.$REP[$i].'" /><label for="rep'.$i.'">&nbsp'.$REP[$i].'</label>'; 
                        }
                    }    
                }
                echo "</div>";
            }

            //affichage des réponses si VF
            elseif($typeQuest=="VF")
            {
                $requeteRep = $BDD -> prepare("SELECT * FROM REPONSE WHERE Quest = ? AND Quizz=?"); 
                $requeteRep -> execute(array($Numquest,$Numquizz));
                echo"<form method=POST action='question.php?Numquizz=".$Numquizz."&Numquest=".$Numquest."&score=".$score."&difficulte=".$difficulte."&temps1=".$temps1."'><div>";
                while ($ligne=$requeteRep->fetch())
                {
                    echo "<br/><input type='radio' name='rep0' value=".$ligne['Reponse']." /><label for=".$ligne['Reponse'].">&nbsp".$ligne['Reponse']."</label>"; 
                }
                echo"</div>";
            }
                
            //affichage zone de réponse si OUV
            elseif($typeQuest=="OUV")
            {
                echo "<form method='POST' action='question.php?Numquizz=".$Numquizz."&Numquest=".$Numquest."&score=".$score."&difficulte=".$difficulte."&temps1=".$temps1."'><div><input name='rep0' type='text' style='color:#222222'/></div>";
            }
            
            echo "<br/><br/><div id='bouton'><button type='submit' class='btn btn-default' name='Valider' value='Valider'>Valider</button></div></form></div>";

        ?>
        </div>
        </div>
    </body>
    <?php include("includes/footer.html");?>
</html>