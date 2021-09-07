<?php
    function nbQuestTot($BDD,$Numquizz)
    {  
        $requeteNbQuest = $BDD -> prepare ("SELECT COUNT(*) FROM QUESTION WHERE Quizz=?");
        $requeteNbQuest -> execute(array($Numquizz));
        $nbQuestTot = $requeteNbQuest->fetch();
        return $nbQuestTot[0];
    }
        
    function recupNbBRep($BDD,$Numquizz,$Numquest)
    {
        $requeteNbBRep = $BDD -> prepare("SELECT COUNT(*) FROM REPONSE WHERE Quizz= ? AND Quest = ? AND Bonnerep=?");
        $requeteNbBRep -> execute(array($Numquizz,$Numquest,'oui'));
        $nbBRep=$requeteNbBRep->fetch();
        $nbBRep=$nbBRep[0];
        return $nbBRep;
    }

    function typeQuest($difficulte,$question)
    {
        if($difficulte=="facile")
        {
            $typeQuest = $question['Typefacile'];
        }
        elseif($difficulte=="moyen")
        {
            $typeQuest = $question['Typemoyen'];
        }
        elseif($difficulte=="difficile")
        {
            $typeQuest = $question['Typedifficile'];
        }   
        return $typeQuest; 
    }

    function nbRepTot($difficulte)
    {
        if($difficulte=="facile")
        {
            $nbRepTot = 3;
        }
        elseif($difficulte=="moyen")
        {
            $nbRepTot = 4;
        }
        elseif($difficulte=="difficile")
        {
            $nbRepTot = 6;
        }
        return $nbRepTot;
    }
        
        
    function recupBonneRep($BDD,$nbBRep,$Numquest,$Numquizz)
    {
        $requeteBRep = $BDD -> prepare("SELECT Reponse FROM REPONSE WHERE Bonnerep =? AND Quest = ? AND Quizz=?"); 
        $requeteBRep -> execute(array('oui',$Numquest,$Numquizz));
        
        if($nbBRep==1)
        {
            $bonneRep=$requeteBRep->fetch(); 
            $bonneRep[0]=$bonneRep[0];      
        }
        elseif($nbBRep==2)
        {
            $bonneRep1=$requeteBRep->fetch();
            $bonneRep[0]=$bonneRep1[0];
            $bonneRep2=$requeteBRep->fetch();
            $bonneRep[1]=$bonneRep2[0];
        }
        return $bonneRep;
    }

    function augmenterScore($typeQuest,$nbBRep,$bonneRep,$score,$rep)
    {
        $augmentation=0;
        if($typeQuest=="QCM")
        {
            if ($nbBRep==1)
            {                   
                if($rep[0]==$bonneRep[0])
                {
                    $augmentation=1;
                }
            }
            elseif($nbBRep==2)
            {
                if(($rep[0]==$bonneRep[0] || $rep[0]==$bonneRep[1])&($rep[1]==$bonneRep[0] || $rep[1]==$bonneRep[1]))
                {
                    $augmentation=1;
                }
            }
        }
        else
        {
            $reponse = $rep[0];

            if($reponse==$bonneRep[0])
            {
                $augmentation=1;
            }

        }
        return $augmentation;
    }

    function deconnexion()
    {
        session_destroy();
    }

    function AfficheTemps($temps)
    {
        $min = (int)($temps/60);
        $sec = $temps-$min*60;
        $strTemps = "$min min $sec s";
        return $strTemps;
    }

    function tableauQuizzRecurents($occurences,$nbQuizz,$lignes)
    {
        //tri des occurences pour avoir les quizz du plus populaire au moins populaire
        arsort($occurences);

        for($i=$nbQuizz;$i>8;$i--)
        {
            array_pop($occurences); //je veux garder les 8 plus populaires
        }

        //tableau avec les numéros des quizz populaires
        $l=0;    
        foreach($occurences as $quizz => $occurence)
        {
            $quizzPopulaires[$l]=$lignes[$quizz];
            $l++;
        }  
        return $quizzPopulaires ;        
    }
?>
