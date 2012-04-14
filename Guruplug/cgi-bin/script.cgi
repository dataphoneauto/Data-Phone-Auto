#!/usr/bin/perl -w

print("content-type: text/html\n\n");


my $file="/var/www/identifiant/file.txt";


# Récupération des informations du formulaire

  if ($ENV{'REQUEST_METHOD'} eq "POST" ) {
    read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
    $Recu="STDIN (Methode POST)" }
  else {
    $Recu="QUERY_STRING (Methode GET)";
    $buffer = $ENV{'QUERY_STRING'};
  }

# Traitement et découpage.
    @pairs = split(/&/, $buffer);
    foreach $pair (@pairs) {
      ($name, $value) = split(/=/, $pair);
      $value =~ tr/+/ /;
      $value =~ s/%(..)/pack("C", hex($1))/eg;
      $FORM{$name} = $value;
  }

  print("Affichage des donnees saisies\n");
  print "<br />";
  
# Ecriture dans le fichier:
open(ECRITURE, ">$file") || die "Erreur E/S:$!\n";
foreach $match (keys (%FORM)) {
    if($FORM{$name} eq ""){
        print ("pas de changement\n");
    }
    else{
        print ECRITURE "\n$match: ".$FORM{$match};
    }
}
close(ECRITURE);

# Lecture du fichier:
open(LIRE, "$file") || die "Erreur E/S:$!\n";
while ( $ligne = <LIRE> ){
    print "$ligne";
    print "<br />";
}
close(LIRE);

print "<br />";
print "<a href =/index.php>Retour au site</a>";


