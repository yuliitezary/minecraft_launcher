<?php
/**
 * Minecraft Server Status Class
 * @copyright	© 2011 Nox Nebula - Patrick Kleinschmidt
 * @website	https://github.com/NoxNebula/MC-Server-Status
 * @license	GNU Public Licence - Version 3
 * @author	Nox Nebula - Patrick Kleinschmidt <link rel="stylesheet" type="text/css" href="<?php .SITEURL.?>/<?php .THEME.?>/style.css">
 * Modify by Bimmy 2012 http://vk.com/bimmy
 **/

// Include MC-SS Class 
include('MinecraftStatus.class.php');
// ниже вписывайте айпи своего сервера
$Server = new MinecraftStatus('212.22.85.129');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="/mon/style.css">
</head>

<body>
	<a id="mon_link" href="#">OurWorld</a>
	<div class="mon">
		<div class="progressbar">
			<?php
				if ($Server->Online == true) {
					$z = ($Server->CurPlayers / $Server->MaxPlayers)*100;
					if ($z == 0) {
						echo '<span style="width: 1%"></span>';
					}
					else {
						echo '<span style="width: '.$z.'%"></span>'; 
					};
					echo '<div id="mon_text">Игроков: '.$Server->CurPlayers.' / '.$Server->MaxPlayers.'</div>';
				}
				else {
					echo '<span style="width: 100%; background-color: rgb(217, 0, 0)"></span>';
					echo '<div id="mon_text">Сервер оффлайн</div>';
				};
				
			?>
		</div>
	</div>
</body>
</html>