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
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"/>
		<link rel="stylesheet"  href="style/styleCreationQuizz.css"/>		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	
	<body>
		<div id="grandConteneur">
		<h1> Création du quizz </h1> <br/>
		 
		<form action="creationQuestions.php" method="POST" enctype="multipart/form-data">
			<div id="grosConteneur">
			<div class="conteneur">
				<h3> Nom de la série </h3>
				<p>
					<div id=nomEtUpload> 
						<p><label for="pseudo" ></label> <input type="text" class="text" name="nomQuizz" /> </p>
						<div id="upload"><input type="file" name="fichier">
						<h6 class='text-muted'>Attention : le nom de l'image ne doit pas dépasser 10 caractères et ne contenir aucun caractère spécial</h6></div>
					</div>
				</p>
				<h3> Description du quizz </h3>
				<p>
					<div>
						<label for="pseudo" ></label> <input type="text" class="text"  name="descrQuizz" id="descrQuizz"/> 
					</div>
				</p>
				<h3> Catégories du quizz (max 3) </h3>
				<p>
					<div>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Action et aventure' /> &nbsp; Action et aventure </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Comédie' /> &nbsp; Comédie </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Drames' /> &nbsp; Drame </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Romance' /> &nbsp; Romance </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='SF et fantastique' /> &nbsp; SF et fantastique </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Enfants' /> &nbsp; Enfants </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Animes' /> &nbsp; Animes </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Thrillers' /> &nbsp; Thrillers </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Policiers' /> &nbsp; Policiers </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Horreur' /> &nbsp; Horreur </p>
						<p><label  for="categories" > </label><input type="checkbox" class="check" name="categories[]" value='Ados' /> &nbsp; Ados </p>
					</div>
				</p>

				<div>
					<h3> Nombre de questions </h3>
					<p>
						<div>
							<label for="pseudo" ></label> <input type="text" class="text"  name="nbrQuestions" /> 
						</div>
					</p>

				</div>
			</div>
			<br/>
			<p><input type="submit" class="btn btn-primary" id="valider" value="Enregistrer"/></p>
			</div>
		</form>
		</div>
	</body>
	<?php include("includes/footer.html"); ?>
</html>