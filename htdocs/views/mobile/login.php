<?php
if($_POST) {
	$u = $_POST['email'];
	$p = $_POST['password'];
	
	if ($_SESSION['connected'] == true) 
	{
?>
        <ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="a">
		<li data-role="list-divider">Administration</li>
		<li><a href="http://www.google.fr">Mes informations</a></li>
		<li><a href="http://www.google.de">Mes voitures</a></li>
		<li><a href="http://www.google.com">Ajouter une voiture</a></li>
		<li><a href="http://www.google.it">Supprimer une voiture</a></li>
		
	</ul>
<?php
	</ul><?php
	}
	
	else { 
?>
	<p>Vous devez etre connecte pour acceder a cette page.</p>
	<p>Vous serez redirige dans 5 secondes ...</p>
	<img id="disconnect" src='../../images/deco.gif' />

<?php	
	}
?>
