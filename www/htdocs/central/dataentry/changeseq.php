<?php
/*
2021-11-24 rogercgui checks if $arrHttp["prefix"] exists
20220713 fho4abcd Use $actparfolder as location for .par files
*/

session_start();
if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include("../common/get_post.php");
include("../config.php");
require_once ("../lang/admin.php");
include("../common/header.php");
?>
<body>
<div class="middle form">
			<div class="formContent">
<?php
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
// Se ubica el n�mero de control en la lista invertida para ver si no est� asignado
$Formato=$db_path.$arrHttp["base"]."/pfts/".$_SESSION["lang"]."/".$arrHttp["base"].".pft" ;

if (isset($arrHttp["prefix"])) {
	$cn_prefix=$arrHttp["prefix"];
} else {
	$cn_prefix="";	
}

if (!file_exists($Formato)) $Formato=$db_path.$arrHttp["base"]."/pfts/".$lang_db."/".$arrHttp["base"].".pft" ;
$Expresion=$cn_prefix.$arrHttp["cn"];
$query = "&base=".$arrHttp["base"]."&cipar=$db_path".$actparfolder.$arrHttp["base"].".par"."&Expresion=$Expresion&Formato=@$Formato&Opcion=buscar";
$IsisScript=$xWxis."imprime.xis";
include("../common/wxis_llamar.php");
$cont_database=implode('',$contenido);
if (trim($cont_database)!="") {
	$exist="Y";
}else{
	$exist="N";
}
if ($exist=="N"){
	if (isset($max_cn_length)) $arrHttp["cn"]=str_pad($arrHttp["cn"], $max_cn_length, '0', STR_PAD_LEFT);
	echo "
	<script>
	window.opener.document.forma1.tag".$arrHttp["tag"].".value=\"".$arrHttp["cn"]."\"
	self.close()
	</script>
	";
}else{
	echo "<h4>".$msgstr["cnexists"]."</h4>";
	echo "<a class='bt bt-red' href=javascript:self.close()>".$msgstr["cerrar"]."</a>&nbsp; &nbsp; &nbsp;";
	echo "<a class='bt bt-blue' href=javascript:history.back()>".$msgstr["regresar"]."</a><p>";
	echo "$cont_database";
}

?>
</div><div>
</body>

</html>