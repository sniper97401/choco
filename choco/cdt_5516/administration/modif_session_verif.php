<?php
include "../authentification/authcheck.php";
if ($_SESSION['droits']<>1) { header("Location: ../index.php");exit;};
require_once('../Connections/conn_cahier_de_texte.php');
require_once('../inc/functions_inc.php');
mysqli_select_db($conn_cahier_de_texte, $database_conn_cahier_de_texte) or die(mysqli_error($conn_cahier_de_texte));

if(isset($_POST['choice']))
	{
	$choice = GetSQLValueString($_POST['choice'], "text");
	$query_write = "UPDATE `cdt_params` SET `param_val`=$choice WHERE `param_nom`='session_verif';";
	$result_write = mysqli_query($conn_cahier_de_texte, $query_write);
	}

$query_read = "SELECT `param_val` FROM `cdt_params` WHERE `param_nom`='session_verif' LIMIT 1;";
$result_read = mysqli_query($conn_cahier_de_texte, $query_read);
$row = mysqli_fetch_row($result_read);
$access = $row[0];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cahier de textes - <?php echo $_SESSION['identite']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK media=screen href="../styles/style_default.css" type=text/css rel=stylesheet>
<LINK media=screen href="../templates/default/header_footer.css" type=text/css rel=stylesheet>
</HEAD>
<BODY>
<DIV id=page>
<?php 
$header_description="Activation ou non de la v&eacute;rification de session";
require_once "../templates/default/header.php";
?>

  <blockquote>
    <blockquote>
      <blockquote>
        <p style="color:red;">Etat actuel &gt; Activation de la v&eacute;rification de session :  <?php echo $access; ?></p>
        <p align="left">&nbsp;</p>
        <p align="left">Par d&eacute;faut, il y a v&eacute;rification du temps d'ouverture de session par souci de s&eacute;curit&eacute;. </p>
        <p align="left">En saisie de s&eacute;ance, un timer en haut d'&eacute;cran indique le temps restant avant la coupure de session conduisant &agrave; s'identifier &agrave; nouveau.</p>
        <p align="left">Ce temps est d&eacute;fini dans les <strong>Param&egrave;tres g&eacute;n&eacute;raux</strong> (Menu administrateur) / une heure par d&eacute;faut. </p>
        <p align="left">Mais cette valeur indiqu&eacute;e dans vos param&egrave;tres g&eacute;n&eacute;raux est tributaire prioritairement de la valeur d&eacute;finie dans le php.ini de votre serveur et donc le plus souvent inaccessible. </p>
        <p align="left">Je vous propose ici de d&eacute;sactiver la v&eacute;rification du temps d'ouverture de session. (Plus de timer  &agrave; l'affichage en saisie de s&eacute;ance). </p>
        <p align="left">Mais comme enonc&eacute; plus haut, vous restez tributaire de la valeur du param&egrave;tre session.gc_maxlifetime du php.ini du serveur (1440 secondes soit 24 min par d&eacute;faut). </p>
        <p align="left">Il vous appartient alors de bien veiller &agrave; la fermeture de l'application cahier de textes pour &eacute;viter les saisies clandestines ! </p>
        <p align="left">&nbsp;</p>
        <form method="post">
<?php 
if($access=="Oui") echo "<input type=\"hidden\" name=\"choice\" value=\"Non\"/><input type=\"submit\" value=\"D&eacute;sactiver le timer de coupure de session\"/>";
else echo "<input type=\"hidden\" name=\"choice\" value=\"Oui\"/><input type=\"submit\" value=\"Activer le timer de coupure de session\"/>";
?>       
        </form></p>
        <p align="left">&nbsp;</p>
      </blockquote>
    </blockquote>
  </blockquote>
  <p align="center"><a href="index.php">Retour au Menu Administrateur  (Saisie des Enseignants, mati&egrave;res, classes... )</a></p>
  <p>&nbsp; </p>
<DIV id=footer></DIV>
</DIV>
</body>
</html>


