<?php
/* Modifications
2021-02-03 fho4abcd: Set metaencoding only for active session (from repository ABCD2.2)
2021-02-03 fho4abcd: moved comment for cisis version to actual statement
2021-02-04 fho4abcd: ensure trailing slash for $db_path
2021-02-10 fho4abcd: new/improved checks $ABCD_path, $db_path, abcd.def
2021-02-10 fho4abcd: replace fixed server port by autodetection of server port, code formatting
2021-02-25 fho4abcd: don't send header info (wrong and out of order here)
2021-04-02 fho4abcd: autodetected protocol (port was already detected since 2021-02-10))
2021-04-15 fho4abcd: Send header info to server. (revert 2021-02-25)
2021-11-04 fho4abcd: Check that extension mbstring is loaded
2021-11-11 fho4abcd: Remove ffi
2021-11-23 fho4abcd: Allow trailing backslash for $db_path
2021-11-28 fho4abcd: Ensure trailing slash in ABCD_path
2022-01-29 rogercgui Added custom abcd.def parameters.
2022-02-08 rogercgui Added $_REQUEST["lang"] check to decrease the amount of $_REQUEST through the system. Languages should be triggered by the variable $lang
2022-02-22 fho4abcd  Added check on $_SESSION["lang"] + set $_SESSION["lang"]. This works for old scripts & updated scripts (without any lang settings)
2022-03-21 fho4abcd  Added variable to set the par folder
2022-03-31 fho4abcd  Replace fixed localhost by detection of the server name
*/

ini_set('error_reporting', E_ALL);
$cisis_versions_allowed="16-60;bigisis";

// *** Main server configuration
// URL for internal server actions, with autodetected protocol and port
// Note: protocol/port are determined by server config vhosts file. (also if defaults are used)
$protocol="http:"; // default protocol
$servername="localhost";//default host name
if ( isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']=="on") $protocol="https:";
if ( isset($_SERVER['SERVER_NAME']) ) $servername=$_SERVER['SERVER_NAME'];
$server_url=$protocol."//".$servername.":".$_SERVER['SERVER_PORT'];

$postMethod=1;      // if set to '1' (or true) ABCD will use POST-method; if set to '0' the GET-method will be used. Use with caution
$dirtree=1;         // USE THIS PARAMETER TO SHOW THE ICON THAT ALLOWS THE BASES FOLDER EXPLORATION
$MD5=0;             // USE THIS PARAMETER TO ENABLE/DISABLE THE MD5 PASSWORD ENCRIPTYON (0=OFF 1=ON)
$EmpWeb=0;          // use EmpWeb or not
$use_ldap=0;        // use LDAP or not

// Set operation system depending variables
if (stripos($_SERVER["SERVER_SOFTWARE"],"Win") > 0) {
        // Windows variables
	$ABCD_path="/xampp/htdocs/ABCDBrasil/";             // base path to ABCD-installation
 	$db_path="/xampp/htdocs/ABCDBrasil/www/bases-examples_Windows/";     // path where the databases are to be located
 	$exe_ext=".exe";                 // extension for executables
}else{
        // Linux variables
 	$ABCD_path="/opt/ABCD/";         // base path to ABCD-installation
 	$db_path="/var/opt/ABCD/bases/"; // path where the databases are to be located
 	$exe_ext="";                     // extension for executables
}
// Ensure ABCD_path has trailing / or \.
if ( (substr($ABCD_path, strlen($ABCD_path)-1,1) <> "/") AND (substr($ABCD_path, strlen($ABCD_path)-1,1) <> "\\")) $ABCD_path.="/";

$ABCD_scripts_path=$ABCD_path. "www/htdocs/";  //PATH

//IF THERE ARE MULTIPLE BASES FOLDERS THE FOLDER SELECTED IS SET
if ( isset($_SESSION["db_path"]) and  $_SESSION["db_path"]!="")
 	$db_path=$_SESSION["db_path"];

if (isset($_REQUEST["db_path"])) {    //PARA PERMITIR MANEJAR VARIAS CARPETAS BASES DESDE EL OPAC
	$_REQUEST["db_path"]=urldecode($_REQUEST["db_path"]);
	$db_path=$_REQUEST["db_path"];
	if (isset($_SESSION)) $_SESSION["db_path"]=$db_path; //CUANDO VIENE DEL OPAC NO SE TRABAJA CON SESIONES
}


// *** NO CHANGES NEEDED BELOW HERE
// Ensure db_path has trailing / or \.
if ( (substr($db_path, strlen($db_path)-1,1) <> "/") AND (substr($db_path, strlen($db_path)-1,1) <> "\\")) $db_path.="/";


// Construction of executable path and URL                             

$app_path="central";                    // Folder with the administration module
$wxis_exec="wxis".$exe_ext;                // name and extension of wxis executable
$mx_exec="mx".$exe_ext;                    // name and extension of mx executable
$msg_path=$db_path;                        // path where the message-files are stored, typical the database-directory
$img_path=$db_path;                        // legacy path to the folder where the uploaded images are to be stored (the database name will be added to this path)
$cgibin_path=$ABCD_path."www/cgi-bin/";    // path to the basic directory for CISIS-utilities
$xWxis=$ABCD_path."www/htdocs/$app_path/dataentry/wxis/";    // path to the wxis scripts .xis for Central

$unicode="";
$institution_name="";
$cisis_ver=""; // initialisation of $cisis_ver as empty = default standard CISIS-version
$base="";
if (isset($arrHttp["base"])) $base=$arrHttp["base"]; // The base folder (if any)
$actparfolder="par/";                                // The folder where the <bases>.par file is located

// Check existence of locations/files
// These checks are not bulletproof: succesfull check does not guarantee correct configuration
$document_root=$_SERVER['DOCUMENT_ROOT'];
if (!fnmatch("*".$ABCD_path."*", $document_root)) {
    echo "<div>Variable '\$ABCD_path' in the configuration file must be a substring of 'DocumentRoot' in the virtual host file</div>";
    echo "<div>E.g. \"$ABCD_path\" (configured path) must be substring of \"$document_root\" (DocumentRoot)</div>";
    echo "<div>&#8594; Please check your virtual host file and variable '\$ABCD_path' in file ".__FILE__."</div>";
    die;
}
if (!file_exists($ABCD_path)) {
    echo "<div>Missing Base path to ABCD installation folder: $ABCD_path</div>";
    echo "<div>&#8594; Please check variable '\$ABCD_path' in file ".__FILE__."</div>";
    die;
} 
if (!file_exists($db_path)){
	echo "<div>Missing database folder :$db_path </div>";
    echo "<div>&#8594; Please check variable '\$db_path' in file ".__FILE__."</div>";
    echo "<div>&#8594; Please check paths in file ".dirname(__DIR__,1).DIRECTORY_SEPARATOR."dbpath.dat"."</div>";
    die;
}
// Check the loaded extensions
// Extension mbstring:several functions use mbstring functions even for iso databases
if ( extension_loaded("mbstring") == false ) {
    echo "<div style='color:red'>PHP extension 'mbstring' is not loaded. </div>";
    echo "<div style='color:red'>&#8594; Please install this extension and restart the websserver</div>";
    die;
}
// Other extension can be added here if really necessary

if (!file_exists($db_path."abcd.def")){
	echo "Missing file abcd.def in database folder $db_path";
    die;
}
 

$def = parse_ini_file($db_path."abcd.def",true);      // read variables from abcd.def

// Other local settings to be configured
               
$inventory_numeric ="N";                // This variable erases the left zeroes in the inventory number
$max_inventory_length=1;                // Add Zeroes to the left for reaching the max length of the inventory number
$max_cn_length=1;                       // Add Zeroes to the left for reaching the max length of the control number

                       

$ext_allowed=array("jpg","gif","png","pdf","doc","docx","xls","xlsx","odt");    //extensions allowed for uploading files (used in dataentry/)


if (isset($def["INSTITUTION_NAME"])){
	$institution_name=$def["INSTITUTION_NAME"];  // Institution name defined by abcd.def 'INSTITUTION_NAME'
} else {
	$institution_name="Only for testing - not for distribution";
}


//Sets the default system language, if absent the system detects the browser language, if none of the previous options are valid, the default language is English.

if (isset($_REQUEST["lang"])){
	 $lang= $_REQUEST["lang"];
} elseif (isset($def["DEFAULT_LANG"])){
	$lang=$def["DEFAULT_LANG"];
} else {
	$lang='en';
}
// If the session has a language: keep that
if (isset($_SESSION["lang"])) $lang=$_SESSION["lang"];
$_SESSION["lang"]=$lang;

// Default langue for the databases definition
if (isset($def["DEFAULT_DBLANG"])){
	$lang_db=$def["DEFAULT_DBLANG"];
} else {
	$lang_db=$lang;
}


// USED FOR ALL THE DATE FUNCTIONS. DD=DAYS, MM=MONTH, AA=YEAR. USE / AS SEPARATOR
if (isset($def["DATE_FORMAT"])){
	$config_date_format=$def["DATE_FORMAT"];
} else {
	$config_date_format="d/m/Y";
}


// Open the Central module in a new window for avoiding the use of the browse buttons
if (isset($def["NEW_WINDOW"])=="Y"){
	$open_new_window=$def["NEW_WINDOW"];  
} else {
	$open_new_window="N";
}


if (isset($def["UNICODE"]) and $def["UNICODE"]==1)
	$meta_encoding="UTF-8";
else
	$meta_encoding="ISO-8859-1";


//allow change password
if ((isset($def["REG_LOG"])) && ($def["REG_LOG"]=="N")) {
	$log="N"; 
	$def["REG_LOG"]=$log;	
} else {
	$log="Y"; 
	$def["REG_LOG"]=$log;
}
               


if ((isset($def["CHANGE_PASSWORD"])) && ($def["CHANGE_PASSWORD"]=="N")) {
	$change_password="N"; 
	$def["CHANGE_PASSWORD"]=$change_password;	
} else {
	$change_password="Y"; 
	$def["CHANGE_PASSWORD"]=$change_password;
}



if (file_exists(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."config_extended.php")){
    //Include config_extended.php that reads extra configuration parameters
 	include (realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR."config_extended.php");
}
//en el config_extended.php se pueden cambiar los valores de $def["UNICODE"],$def[["CISIS_VERSION"]] 0 $def["UNICODE"]
//se determina la versión del cisis; Si el parámetro no existe se asuma 16-60
//echo "<pre>";echo print_r($def);echo "</pre>";

 if (isset($def["UNICODE"]) and $def["UNICODE"]==1){
	$unicode="utf8";
	$charset="UTF-8";
}else{
	$unicode="ansi";
	$def["UNICODE"]=0;
	$charset="ISO-8859-1";
}
// Send characterset to the server. Note that the browser gets it via the html header.
header('Content-type: text/html; charset=$charset');

if (session_status() != PHP_SESSION_NONE )  $_SESSION["meta_encoding"]=$meta_encoding;




if (isset($def["CISIS_VERSION"]) and $def["CISIS_VERSION"]!="16-60" and $def["CISIS_VERSION"]!="" ){
	$cisis_ver=$def["CISIS_VERSION"];
}

$cisis_path=$cgibin_path.$unicode;
if ($cisis_ver!="")
	$cisis_path.="/".$cisis_ver."/";   // path to directory with correct CISIS-executables
else
	$cisis_path.="/";

$mx_path=$cisis_path.$mx_exec;             // path to mx-executable
if ($postMethod == '1'){
	$wxisUrl=$server_url."/cgi-bin/";
	if ($unicode!="") $wxisUrl.="$unicode/";
	if ($cisis_ver!="") $wxisUrl.=$cisis_ver."/";
	$wxisUrl.=$wxis_exec;  // POST method used
	$Wxis="";
}else{
	$wxisUrl="";
 	$Wxis=$cgibin_path;
 	if ($unicode!="") $Wxis.="$unicode/";
 	if ($cisis_ver!="") $Wxis.=$cisis_ver."/";
 	$Wxis.=$wxis_exec;   //GET method is used
}



$FCKConfigurationsPath="/".$app_path."/dataentry/fckconfig.js";  // path to CKeditor configuration
$FCKEditorPath="/site/bvs-mod/FCKeditor/";                       // path to CKEditor

if ($use_ldap) {                   //LDAP parameters if used
	$ldap_host = "ldap://zflexldap.com";
	$ldap_dn = "cn=ro_admin,ou=sysadmins,dc=zflexsoftware,dc=com";
	$ldap_search_context = "ou=guests,dc=zflexsoftware,dc=com";
	$ldap_port = "389";
	$ldap_pass = "zflexpass";
}
if ($EmpWeb) {                     //EmpWeb parameters if used
	$empwebservicequerylocation = "http://localhost:8086/ewengine/services/EmpwebQueryService";
	$empwebservicetranslocation = "http://localhost:8086/ewengine/services/EmpwebTransactionService";
	$empwebserviceobjectsdb = "objetos";
	$empwebserviceusersdb = "*";
}
$adm_login="";                     // emergency username for administrator
$adm_password="";                  // emergency password for administrator
?>
