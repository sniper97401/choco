<?php 
include "../authentification/authcheck.php" ;
if ($_SESSION['droits']<>1) { header("Location: ../index.php");exit;};
require_once('../Connections/conn_cahier_de_texte.php');
require_once('../inc/functions_inc.php');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cahier de textes - <?php echo $_SESSION['identite']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
<LINK media=screen href="../styles/style_default.css" type=text/css rel=stylesheet>
<LINK media=screen href="../templates/default/header_footer.css" type=text/css rel=stylesheet>
</HEAD>
<BODY>
<DIV id=page>
  <?php 
$header_description="Suppression des fichiers joints obsol&egrave;tes";
require_once "../templates/default/header.php";
?>
  <HR>
<?php

$dir    = '../fichiers_joints';

 
 mysqli_select_db($conn_cahier_de_texte, $database_conn_cahier_de_texte);
 $query_fichiers_joints='SELECT nom_fichier from cdt_fichiers_joints';
 $fichiers_joints = mysqli_query($conn_cahier_de_texte, $query_fichiers_joints) or die(mysqli_error($conn_cahier_de_texte));
 $row_fichiers_joints = mysqli_fetch_assoc($fichiers_joints);
 $totalrow_fichiers_joints = mysqli_num_rows($fichiers_joints);
 //echo $totalrow_fichiers_joints. ' fichiers dans la table cdt_fichiers_joints';


$i=1;
 if ($totalrow_fichiers_joints>0){ 

do { 
					$tab[$i]=$row_fichiers_joints['nom_fichier'];
					$ref[$i]=0;
					$nom[$i]='Annee en cours';
					$i=$i+1;
					
} while ($row_fichiers_joints = mysqli_fetch_assoc($fichiers_joints));
}
mysqli_free_result($fichiers_joints);



//mise en tableau des fichiers de la table fichiers_joints save_
mysqli_select_db($conn_cahier_de_texte, $database_conn_cahier_de_texte);
$query_archive = "SELECT NumArchive,NomArchive FROM cdt_archive";
$archive = mysqli_query($conn_cahier_de_texte, $query_archive) or die(mysqli_error($conn_cahier_de_texte));
$row_archive = mysqli_fetch_assoc($archive);

//echo 'Mise en tableau <br>';

do { 

	  


	$table = 'cdt_fichiers_joints_save'.$row_archive['NumArchive'];

	mysqli_select_db($conn_cahier_de_texte, $database_conn_cahier_de_texte);
	$sql = "SHOW TABLES FROM $database_conn_cahier_de_texte";
	$result = mysqli_query($conn_cahier_de_texte,$sql);

	if (!$result) {
	   echo "Erreur DB, impossible de lister les tables".$table."\n";
	   echo 'Erreur MySQL : ' . mysql_error();
	   exit;
	}
	
		
	while ($row = mysqli_fetch_row($result)) {
       //echo "Table : {$row[0]}\n";
	   if ($row[0]==$table) {
		
			//echo $table. '<br>';
			$query_fichiers_joints='SELECT nom_fichier from cdt_fichiers_joints_save'.$row_archive['NumArchive'];
			$fichiers_joints = mysqli_query($conn_cahier_de_texte, $query_fichiers_joints) or die(mysqli_error($conn_cahier_de_texte));
			$row_fichiers_joints = mysqli_fetch_assoc($fichiers_joints);
			
			do  {
					//echo '<br>';
					//echo $row_fichiers_joints['nom_fichier'];
					$tab[$i]=$row_fichiers_joints['nom_fichier'];
					$ref[$i]=$row_archive['NumArchive'];
					$nom[$i]=$row_archive['NomArchive'];
					$i=$i+1;
			} while ($row_fichiers_joints = mysqli_fetch_assoc($fichiers_joints));
		};
	}
} while ($row_archive = mysqli_fetch_assoc($archive));


	
// FIN mise en tableau


//affichage controle
/*
echo '<br>';echo 'Fichiers presents dans la base<br>';
for ($n=1; $n<$i; $n++) {
   echo $n. '  Save '.$ref[$n].'   '. $nom[$n]. '  '.$tab[$n].'<br>'; 
}


echo '<br>';echo '<br>';
*/


//Mise en tableau des fichiers presents sur le serveur dans le dossier fichiers joints
//echo 'Mise en tableau des fichiers presents sur le serveur dans le dossier fichiers joints<br><br>';
$x=-1;


$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {

    if (($filename<>'edt')&&($filename<>'index.php')&&($filename<>'information.txt')&&($filename<>'import_sconet_log.html')){
	$files[$x] = $filename;
	//echo $x. '  '.$files[$x];echo '<br>';
	$x=$x+1;
	}
}



?>

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordure">

      <tr>
        <td width="50%" class="Style6"><div align="left">Fichiers supprim&eacute;s dans le dossier fichiers_joints</div></td>
        <td width="50%" colspan="2" class="Style6"><div align="left">Etat</div></td>
      </tr>

<?php

for ($y=1; $y<$x; $y++) {
  // echo $y. ' > '. $files[$y].'<br>'; 
   //on regarde s'il est utilise
   $trouve=0;echo '<tr>';
   for ($n=1; $n<$i; $n++) {
     //echo $n. '  '. $tab[$n].'<br>'; 
	   
   		if ($files[$y]==$tab[$n]) { 
			//echo ' <tr class="menu_detail"><td width="50%"><div align="left">'. $tab[$n]. '</div></td><td><div align="left"> utilis&eacute; dans '.$nom[$n].' ('.$ref[$n].')</div></td></tr>';
			$trouve=1;
			
		}; 
		
   };
   
  
   if ($trouve==0){ 
   		
		$fichier = $dir.'/'. $files[$y];
		if( file_exists ( $fichier)){unlink($fichier) ;echo ' <tr class="tab_detail_gris" ><td width="50%" style="text-indent:10px"><div align="left">'. $files[$y].'</div></td><td style="text-indent:10px"><div align="left"> Supprim&eacute; </div></td></tr>';};
	
		};

};
   
   



?>
</table> 
 <p>&nbsp; </p>
 <p align="center"><a href="index.php">Retour au Menu Administrateur</a></p>
  <p>&nbsp; </p>
<DIV id=footer></DIV>
</DIV>
</body>
</html>
<?php
mysqli_free_result($fichiers_joints);
?>

