<?php



/*
C'est ce qu'on appelle de "L'extrem programming", c'est à dire du code craché en 20 minutes... n'y perdez pas vos yeux ;)
...
Le but :
transformer un tableau PHP, même mutli-dimensionnel, en un tableau JS
!!! Ce tableau ne doit pas contenir d'objet ou ressource PHP !!! (pas de contrôle, mais le script JS plantera)

En entree :
(array) $php_array => le tableau PHP à traduire en JS
(STRING) $js_array_name => le nom du tableau JS qui sera construit

En sortie :
(string) => le script JS permettant la construction du tableau

En cas d'errur :
retourne FALSE et une erreur de type E_USER_NOTICE est generée
*/
function php2js( $php_array, $js_array_name ) {
	// contrôle des parametres d'entrée
	if( !is_array( $php_array ) ) {
		trigger_error( "php2js() => 'array' attendu en parametre 1, '".gettype($array)."' fourni !?!");
		return false;
	}
	if( !is_string( $js_array_name ) ) {
		trigger_error( "php2js() => 'string' attendu en parametre 2, '".gettype($array)."' fourni !?!");
		return false;
	}

	// Création du tableau en JS
	$script_js = "var $js_array_name = new Array();\n";
	
	// on rempli le tableau JS à partir des valeurs de son homologue PHP
	foreach( $php_array as $key => $value ) {
	
		// pouf, on tombe sur une dimension supplementaire
		if( is_array($value) ) {
			// On va demander la création d'un tableau JS temporaire
			$temp = uniqid('temp_'); // on lui choisi un nom bien barbare
			$t = php2js( $value, $temp ); // et on creer le script JS
			// En cas d'erreur, remonter l'info aux récursions supérieures
			if( $t===false ) return false;

			// Ajout du script de création du tableau JS temporaire
			$script_js.= $t;
			// puis on applique ce tableau temporaire à celui en cours de construction
			$script_js.= "{$js_array_name}['{$key}'] = {$temp};\n";
		}
		
		// Si la clef est un entier, pas de guillemets
		elseif( is_int($key) )  $script_js.= "{$js_array_name}[{$key}] = '{$value}';\n";
		
		// sinon avec les guillemets
		else $script_js.= "{$js_array_name}['{$key}'] = '{$value}';\n";
	}

	// Et retourn le script JS
	return $script_js;
}

// fin de la fonction php2js

function php3js($tableauPHP, $nomTableauJS){
	echo $nomTableauJS." = new Array();";
	for($i = 0; $i < count($tableauPHP); $i++){
		if(!is_array($tableauPHP[$i])){
			echo $nomTableauJS."[".$i."] = '".$tableauPHP[$i]."';";
		}
		else {
			construisTableauJS($tableauPHP[$i], $nomTableauJS."[".$i."]");
		}
	}
	return;
}
?>

