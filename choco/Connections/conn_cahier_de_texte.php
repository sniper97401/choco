<?PHP
 $hostname_conn_cahier_de_texte = 'database';
 $database_conn_cahier_de_texte = 'cahier_de_textes';
 $username_conn_cahier_de_texte = 'root';
 $password_conn_cahier_de_texte = 'root';
 $conn_cahier_de_texte = mysqli_connect($hostname_conn_cahier_de_texte, $username_conn_cahier_de_texte, $password_conn_cahier_de_texte) or die(mysqli_connect_errno());
//si probleme accent a l'affichage (points d'interrogation)decommenter la ligne ci-dessous; 
header('Content-Type: text/html; charset=ISO-8859-1');ini_set( 'default_charset', 'ISO-8859-1' );
//si probleme accent pour les donn�es extraites de la base decommenter la ligne ci-dessous;
mysqli_query($conn_cahier_de_texte, "SET NAMES latin1");

?>