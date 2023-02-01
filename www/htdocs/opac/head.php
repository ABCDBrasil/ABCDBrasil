<?php
include($_SERVER['DOCUMENT_ROOT'] . "/central/config_opac.php");
$modo = "";
if (isset($_REQUEST["base"]))
	$actualbase = $_REQUEST["base"];
else
	$actualbase = "";
if (isset($_REQUEST["xmodo"]) and $_REQUEST["xmodo"] != "") {
	unset($_REQUEST["base"]);
	$modo = "integrado";
}

function wxisLlamar($base, $query, $IsisScript)
{
	global $db_path, $Wxis, $xWxis;
	include("wxis_llamar.php");
	return $contenido;
}
//include ("get_ip_address.php");
header('Content-Type: text/html; charset=".$charset."');
$meta_encoding = $charset;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset ?>" />
	<?php if (isset($shortIcon) and $shortIcon != "") {
		echo "<link rel=\"shortcut icon\" href=\"<?php echo $ShorcutIcon?>\" type=\"image/x-icon\">\n";
	}
	?>
	<title><?php echo $TituloPagina ?></title>
 <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
	<link href="/assets/css/colors.css" rel="stylesheet">
	<link href="/assets/css/buttons.css" rel="stylesheet">
	<link href="/assets/css/normalize.css" rel="stylesheet">

	<link href="assets/css/styles.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" media="screen" />
	<script src=/opac/assets/js/script_b.js?<?php echo time(); ?>></script>
	<script src=/opac/assets/js/highlight.js?<?php echo time(); ?>></script>
	<script src=/opac/assets/js/lr_trim.js></script>
	<script src=/opac/assets/js/selectbox.js></script>

	<!--FontAwesome-->
	<link href="/assets/css/all.min.css" rel="stylesheet">

	<script>
		document.cookie = 'ORBITA; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/'
		document.cookie = 'ORBITA=;';

		/* Marcado y presentación de registros*/
		function getCookie(cname) {
			var name = cname + "=";
			var decodedCookie = decodeURIComponent(document.cookie);
			var ca = decodedCookie.split(';');
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
		}

		function Seleccionar(Ctrl) {
			cookie = getCookie('ORBITA')
			if (Ctrl.checked) {
				if (cookie != "") {
					c = cookie + "|"
					if (c.indexOf(Ctrl.name + "|") == -1)
						cookie = cookie + "|" + Ctrl.name
				} else {
					cookie = Ctrl.name
				}
			} else {
				sel = Ctrl.name + "|"
				c = cookie + "|"
				n = c.indexOf(sel)
				if (n != -1) {
					cookie = cookie.substr(0, n) + cookie.substr(n + sel.length)
				}

			}
			document.cookie = "ORBITA=" + cookie
			Ctrl = document.getElementById("cookie_div")
			Ctrl.style.display = "inline-block"
		}

		function delCookie() {
			document.cookie = 'ORBITA=;';

		}
		var user = getCookie("ORBITA");
		if (user != "") {
			alert("Welcome again " + user);
		} else {

		}
	</script>


	<script>
		msgstr = Array()
		msgstr["no_rsel"] = "<?php echo $msgstr["no_rsel"] ?>"
		msgstr["sel_term"] = "<?php echo $msgstr["sel_term"] ?>"
		msgstr["miss_se"] = "<?php echo $msgstr["miss_se"] ?>"
		msgstr["rsel_no"] = "<?php echo $msgstr["rsel_no"] ?>"
		msgstr["reserv_no"] = "<?php echo $msgstr["reserv_no"] ?>"
		actualScript = "<?php echo $actualScript ?>"
	</script>
</head>

<body class="page-template-default page page-id-446 page-child parent-pageid-444 noHeaderFilter">
<header>
    <div class="container">
        <div class="flex flex-between">
            <a href="https://memoria.mackenzie.br" class="a-logo">
                <img src="https://memoria.mackenzie.br/wp-content/themes/theme_chcm/assets/images/logo.png" alt="Logo" class="logo">
                <img src="https://memoria.mackenzie.br/wp-content/themes/theme_chcm/assets/images/logo_sup.png" alt="Logo" class="logo-sup">
            </a>
            <div class="sd">
                <div class="flex align-center">
                    <div class="media-social">
                        <div class="flex align-center">
                            <a href="https://www.facebook.com/chcmackenzie" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16.067" height="16.078" viewBox="0 0 16.067 16.078">
                                    <defs>
                                        <clipPath id="clip-path">
                                            <rect id="Retângulo_46" data-name="Retângulo 46" width="16.067" height="16.078" fill="#fff"></rect>
                                        </clipPath>
                                    </defs>
                                    <g id="Grupo_418" data-name="Grupo 418" transform="translate(-7.983 -7.983)">
                                        <g id="Grupo_53" data-name="Grupo 53" transform="translate(7.983 7.983)">
                                            <g id="Grupo_52" data-name="Grupo 52" clip-path="url(#clip-path)">
                                                <path id="Caminho_351" data-name="Caminho 351" d="M16.067,7.56v.973c-.011.046-.025.092-.032.139-.06.41-.085.83-.184,1.23a8.077,8.077,0,0,1-5.412,5.935c-.271.1-.556.16-.847.242v-5.8h1.567l.353-2.26H9.129c0-.07,0-.123,0-.176,0-.513-.026-1.029.014-1.539a1.01,1.01,0,0,1,.991-.977c.5-.04,1.014-.026,1.521-.035.062,0,.125,0,.188,0V3.312c-.126-.018-.243-.037-.361-.051a8.132,8.132,0,0,0-2.163-.053A2.717,2.717,0,0,0,6.894,5.862c-.048.656-.026,1.317-.035,1.976,0,.057,0,.114,0,.186h-1.8v2.268H6.848v5.763a.792.792,0,0,1-.091-.005,8.36,8.36,0,0,1-2.689-.941A8.058,8.058,0,0,1,.093,6.836a7.774,7.774,0,0,1,2.6-4.794A7.837,7.837,0,0,1,9.4.115a7.571,7.571,0,0,1,3.894,1.852A7.907,7.907,0,0,1,15.97,6.858c.035.234.065.468.1.7" transform="translate(0 0)" fill="#fff"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                            <a href="https://www.twitter.com/chcmackenzie" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15.433" height="12.71" viewBox="0 0 15.433 12.71">
                                    <path id="Caminho_75" data-name="Caminho 75" d="M18.948,18.026a8.5,8.5,0,0,0,1.423-2.908,12.2,12.2,0,0,0,.371-2.97c0-.124.124-.124.186-.186a7.8,7.8,0,0,0,1.238-1.238.468.468,0,0,0,.062-.248c0-.062,0,0-.062,0a4.438,4.438,0,0,1-1.547.433,1.968,1.968,0,0,0,.743-.681,1.947,1.947,0,0,0,.495-.928V9.239h-.062a6.046,6.046,0,0,1-1.856.681c-.062,0-.062,0-.124-.062l-.186-.186a2.6,2.6,0,0,0-.866-.557A3.33,3.33,0,0,0,17.4,8.868a3.328,3.328,0,0,0-2.351,1.238,3.329,3.329,0,0,0-.619,1.238,3.078,3.078,0,0,0-.062,1.3l-1.98-.309A9.065,9.065,0,0,1,8,9.549a.116.116,0,0,0-.186,0,3.19,3.19,0,0,0,.557,3.775,1.638,1.638,0,0,0,.371.309A4.088,4.088,0,0,1,7.5,13.323c-.062-.062-.124,0-.124.062v.371a3.308,3.308,0,0,0,1.98,2.6.894.894,0,0,0,.433.124,5.078,5.078,0,0,1-1.176.062c-.062,0-.124,0-.062.124a3.373,3.373,0,0,0,2.475,2.1.464.464,0,0,1,.309.062,4.267,4.267,0,0,1-1.733.928,5.3,5.3,0,0,1-2.6.309H6.82c-.062,0,0,.062.062.124a2.69,2.69,0,0,0,.433.248A10.091,10.091,0,0,0,8.49,21a9.744,9.744,0,0,0,7.116-.186A9.826,9.826,0,0,0,18.948,18.026Z" transform="translate(-6.794 -8.868)" fill="#fff"></path>
                                </svg>
                            </a>
                            <a href="https://youtube.com/channel/UCUQM3RmmgTuMpnRr1AG2e2w" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15.798" height="11.065" viewBox="0 0 15.798 11.065">
                                    <path id="Caminho_79" data-name="Caminho 79" d="M19,21.213a3.439,3.439,0,0,0,3.454-3.454V13.6A3.439,3.439,0,0,0,19,10.148h-8.89A3.439,3.439,0,0,0,6.659,13.6v4.157a3.439,3.439,0,0,0,3.454,3.454Zm-6.076-3.07a.282.282,0,0,1-.384-.256V13.666c0-.192.512-.32.7-.192l3.837,2.047c.128.064.32.32.192.384Z" transform="translate(-6.659 -10.148)" fill="#fff"></path>
                                </svg>

                            </a>
                            <a href="https://www.tiktok.com/@chcmackenzie?_t=8WVoFcM4PQM&amp;_r=1" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16.434" height="18.962" viewBox="0 0 16.434 18.962">
                                    <path id="logo-tiktok" d="M62.347,20.055a4.318,4.318,0,0,1-.373-.217,5.248,5.248,0,0,1-.959-.815,4.522,4.522,0,0,1-1.08-2.229h0A2.746,2.746,0,0,1,59.9,16H56.639V28.592c0,.169,0,.336-.007.5,0,.021,0,.04,0,.062a.136.136,0,0,1,0,.028v.007a2.765,2.765,0,0,1-1.391,2.195,2.718,2.718,0,0,1-1.347.356,2.765,2.765,0,0,1,0-5.53,2.722,2.722,0,0,1,.846.134l0-3.316a6.049,6.049,0,0,0-4.661,1.364,6.391,6.391,0,0,0-1.394,1.72,5.959,5.959,0,0,0-.719,2.735,6.455,6.455,0,0,0,.35,2.162v.008a6.364,6.364,0,0,0,.884,1.612,6.618,6.618,0,0,0,1.411,1.331v-.008l.008.008a6.094,6.094,0,0,0,3.324,1,5.885,5.885,0,0,0,2.467-.546,6.192,6.192,0,0,0,2-1.506A6.26,6.26,0,0,0,59.5,31.1,6.8,6.8,0,0,0,59.9,29.02V22.34c.04.024.566.372.566.372a7.532,7.532,0,0,0,1.941.8,11.176,11.176,0,0,0,1.992.273V20.553A4.223,4.223,0,0,1,62.347,20.055Z" transform="translate(-47.959 -16)" fill="#fff"></path>
                                </svg>
                            </a>
                            <a href="https://www.instagram.com/chcmackenzie">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16.768" height="16.765" viewBox="0 0 16.768 16.765">
                                    <g id="Grupo_599" data-name="Grupo 599" transform="translate(0 0)">
                                        <path id="Caminho_348" data-name="Caminho 348" d="M4.132,0h8.511c.17.032.342.055.51.1a4.484,4.484,0,0,1,3.479,3.36c.061.219.091.446.135.669v8.511c-.045.229-.08.461-.137.687A4.5,4.5,0,0,1,12.4,16.75c-2.673.026-5.346.009-8.018,0a3.793,3.793,0,0,1-1.615-.361,4.423,4.423,0,0,1-2.749-4.2c-.029-2.542-.01-5.084,0-7.626A4.131,4.131,0,0,1,.1,3.64,4.462,4.462,0,0,1,3.479.131C3.693.073,3.914.042,4.132,0M.99,8.367c0,1.249,0,2.5,0,3.747a3.892,3.892,0,0,0,.083.843,3.544,3.544,0,0,0,3.535,2.819q3.78.006,7.56,0a3.412,3.412,0,0,0,1.506-.317,3.478,3.478,0,0,0,2.1-3.3c.021-2.514.008-5.029,0-7.543a3.768,3.768,0,0,0-.078-.8A3.538,3.538,0,0,0,12.2.984q-3.8-.016-7.609,0a3.483,3.483,0,0,0-2.937,1.5A3.531,3.531,0,0,0,.99,4.62q0,1.874,0,3.747" transform="translate(0 0.001)" fill="#fff"></path>
                                        <path id="Caminho_349" data-name="Caminho 349" d="M125.2,120.374a4.582,4.582,0,1,1-4.577-4.58,4.588,4.588,0,0,1,4.577,4.58m-4.6-3.6a3.6,3.6,0,1,0,3.614,3.583,3.594,3.594,0,0,0-3.614-3.583" transform="translate(-112.229 -111.997)" fill="#fff"></path>
                                        <path id="Caminho_350" data-name="Caminho 350" d="M358.914,68.737a1.356,1.356,0,0,1,0-2.711,1.38,1.38,0,0,1,1.362,1.366,1.366,1.366,0,0,1-1.362,1.345m.366-1.356a.358.358,0,0,0-.366-.358.372.372,0,0,0-.364.365.386.386,0,0,0,.371.365.373.373,0,0,0,.359-.372" transform="translate(-345.837 -63.86)" fill="#fff"></path>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="sand">
                        <div onclick="javascript:openNav()" class="flex align-center">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="43" height="43" viewBox="0 0 43 43" style="margin-right: 10px">
                                <defs>
                                    <pattern id="pattern" preserveAspectRatio="none" width="100%" height="100%" viewBox="0 0 300 300">
                                        <image width="300" height="300" xlink:href="data:image/png;base64,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"></image>
                                    </pattern>
                                </defs>
                                <rect id="lf30_editor_isiq8q22" width="43" height="43" fill="url(#pattern)"></rect>
                            </svg>
                            <span>
                                Menu
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

	<?php
	if (!file_exists($db_path . "opac_conf/$lang/lang.tab")) {
		echo $msgstr["missing"] . " " . $db_path . "opac_conf/$lang/lang.tab";
		die;
	}

	include_once 'components/topbar.php';

		if ((!isset($_REQUEST["existencias"]) or $_REQUEST["existencias"] == "") and !isset($sidebar)) include("components/sidebar.php");
		?>

		<div id="page">
			<div id="content" <?php if (isset($desde) and $desde = "ecta")
									echo "style='float:left;border: #cccccc 1px solid;border-radius:15px; background: red;'"; ?>>

				<?php
				if (!isset($indice_alfa)) 
				include("components/submenu_bases.php");
				$_REQUEST["base"] = $actualbase;
				?>