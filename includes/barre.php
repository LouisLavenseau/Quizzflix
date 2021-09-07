<html>   
    <nav class="navbar navbar-inverse" id="barre">
    <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="logo" style="height:200%; margin-top:-8px;"/></a>
        </div>
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Catégories
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="categorie.php?categorie=Action et aventure">Action et aventure</a></li>
              <li><a href="categorie.php?categorie=Ados">Ados</a></li>
              <li><a href="categorie.php?categorie=Animes">Animes</a></li>
              <li><a href="categorie.php?categorie=Comédie">Comédie</a></li>
              <li><a href="categorie.php?categorie=Drames">Drames</a></li>
              <li><a href="categorie.php?categorie=Enfants">Enfants</a></li>
              <li><a href="categorie.php?categorie=Horreur">Horreur</a></li>
              <li><a href="categorie.php?categorie=Policiers">Policiers</a></li>
              <li><a href="categorie.php?categorie=Romance">Romance</a></li>
              <li><a href="categorie.php?categorie=SF et fantastique">SF et fantastique</a></li>
              <li><a href="categorie.php?categorie=Thrillers">Thrillers</a></li>
            </ul>
          </li>
          <?php
            //si utilisateur admin
            if(isset($_SESSION['login']))
            {
              $requete = $BDD -> prepare("SELECT Type FROM COMPTE WHERE Login=?");
              $requete -> execute(array($_SESSION['login']));
              $type=$requete->fetch()[0];
              if($type=='admin')
              {
                echo '<li><a href="accueilAdmin.php">Modification quizz <span class="glyphicon glyphicon-pencil"></span></a></li>';
              }
            }           
          ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
        <li><a href="tuto.php"><span class="glyphicon glyphicon-question-sign"></span> Aide</a></li>
        <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Profil
        <span class="glyphicon glyphicon-user"></span></a>
        <ul class="dropdown-menu">
        <?php
        if(empty($_SESSION['login'])||(isset($_GET['deco'])&&$_GET['deco']=="oui")) //si utiilisateur déconnecté
        {
          echo '<li><a href="connexion.php"><span class="glyphicon glyphicon-log-in"></span> Connexion/Inscription</a></li>';
        }
        elseif(isset($_SESSION['login'])) // si utilisateur connecté
        {
          echo '<li><a href="historique.php"><span class="glyphicon glyphicon-user"></span> Mon profil</a></li>
          <li><a href="connexion.php?deco=oui"><span class="glyphicon glyphicon-log-in"></span> Déconnexion</a></li>';
        }
        
        ?>
        </ul></li></ul>
    </div>
    </nav>

  
</html>