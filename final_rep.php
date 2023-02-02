<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/mensajes.css">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	
	<link href="favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="css/ui.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
	    
	<script type="text/javascript" src="js/jquery.js"></script>
	<title>View Replay - InfoRep</title>
<?

	/*header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0*/

//Disco donde esta instalado www
$unidad_disco = "d:\\";

//Directorio donde se guardan las repeticiones y las imagenes
$prefix_repe=$unidad_disco."www\\repes\\";

//Directorio donde se guardan las imagenes de los mapas y las imagenes chicas de las razas
$prefix_img=$unidad_disco."www\\info_rep\\img\\";

//Directorio donde se guardan las imagenes de los mapas y las imagenes chicas de las razas
$prefix_img_rel="img/";

//Directorio donde esta instalado el Starcraft
$dir_starcraft = "C:/Program Files/Starcraft";
//$dir_starcraft=$unidad_disco."Starcraft";

//Borrando imagenes de mapas antiguos
unlink($prefix_img."low.jpg");
unlink($prefix_img."high.jpg");

//datos del arhivo
$nombre_archivo = $HTTP_POST_FILES['userfile']['name'];

//Tipo de archivo, opcional
$tipo_archivo = $HTTP_POST_FILES['userfile']['type'];

//Tomando el tama�o del archivo, opcional
$tamano_archivo = $HTTP_POST_FILES['userfile']['size'];

//Opcion para generar el mapa en alta calidad, es mas lento, por defecto esta desactivado
$op_mapa_high = $_POST["mapa"];
	
	//Toma el archivo desde el cliente y lo envia al servidor, se comprueba que se recive exitosamente, en caso de error aplica exit;	
    if (move_uploaded_file($HTTP_POST_FILES['userfile']['tmp_name'], $prefix_repe.$nombre_archivo)){
       //Mostrando mensaje de carga exitosa, opcional
	   //echo "El archivo ha sido cargado correctamente.";
    }else{
       //Ocurre error, mostrando mensaje de error	   
	   echo "<div class=\"error mensajes\">Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse.</div>";
	   exit;
    }
?>

	<script type="text/javascript" src="js/niceToolTips-1.js"></script>
    <script language="javascript" type="text/javascript">			
    	$(document).ready(function()
		{
			niceToolTip(".infoP");
		});
	</script>

</head>

<body>

<?php
	//Ruta completa al archivo
	$file_repe=$prefix_repe.$nombre_archivo;
	
	//Abriendo y Cargando el archivo
    $info = php_bw_load_replay($file_repe);

	include "conn.php";
	$conn = pg_connect("host=$dbhost port=$dbport dbname=$dbname user=$dbuser password=$dbpass") or die('<div width="100%" class="error">OCURRIO UN ERROR AL INTENTAR CONECTAR A LA BASE DE DATOS <B>'. $dbname.' </B></div>');
	
/* 	for($i=0;$i<count($info->Teams);$i++)
	 foreach($info->Teams[$i] as $c=>$v)
	 {
		echo "Equipo $i Nombre: $v </p>";
		$query="insert into Equipo values('$v','$i');";
		echo $query;
		pg_query( $conn,$query);
		}
	
	foreach($info->Players as $player)
	{
		$query="insert into Players values('1','1','$player->ColorHTML','$player->RaceName','$player->Name','$player->APM','$player->StartingLocation12');";
		echo $query;
		pg_query( $conn,$query);
	} */
	
	
	//Si ocurre un error en la carga, salir
    if($info->ErrorCode != 0){
        die( "<div class=\"error mensajes\">Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse.</div>");
		//die("Could not load the replay ! Message : " . $info->ErrorString);
	}

?>

<div id="contents" class="main-content">
<div class="column1-unit">
<div id="replayContainer">
<table id="infoTable">
<thead>
<tr bgcolor="#FFFFFF">
	<th>
	<img src="img/info_002.png">General
	</th>
	<th>
	<img src="img/players.png"> Players
	</th>
	<th>
	<img src="img/map.png"> Map
	</th>
</tr>
<tr bgcolor="#FFFFFF">
<td id="repInfo">
	<?php

		//Calculando la duracion del game en minutos
		$minutos=(int)($info->GameLength/60);
		
		//Calculando la duracion del game segundos
		$segundos= (($info->GameLength/60)-$minutos)*60;
		
		//Mostrando Informacion del juego: Raza vs Raza
		echo "<img src=\"img/matchup.png\" title=\"Matchup\">".$info->Matchup.'('.$info->GameType.')'."<br>";
		
		//Mostrando Informacion del juego: Tiempo
		echo " <img src=\"img/time.png\" title=\"Duration\">". $minutos  . " min ". $segundos. " seg". "<br> ";
		
		//Mostrando Informacion del juego: Fecha
		echo "<img src=\"img/date.png\" title=\"Game date\">". date("d/m/o g:i a" ,$info->GameDate) ."<br>";

		//Mostrando Informacion del juego: Version
		echo "<img src=\"img/bw.png\" title=\"Version\">".$info->Version->VersionName."</a>";
	?>
</td>

<td id="players">
<div id="plCell">
<div class="player">

	<?
	if( strlen ($info->Matchup) != 3 && ($info->GameType == 'Top vs Bottom' || $info->GameType == 'Melee'))
	{
	?>
		<div class="team">
	<?
	/*
	Aqui hay que agregar el codigo
	*/
	if( strlen ($info->Matchup) > 3 )
	{
		$Team = split( 'v', $info->Matchup );
		$num_Teams = count($Team);
		$cont = 0;
		
		foreach($Team as $player)
		{
			$num_integrantes_equipo[$cont] = strlen($player);			
			//echo "Nume_int".$num_integrantes_equipo[$cont];
			$cont ++;
		}

	}
}
?>
	<?
	if( strlen ($info->Matchup) != 3 && $info->GameType != 'Top vs Bottom' && $info->GameType != 'Melee')
	{
	?>
		<div class="player">
	<?
	}
	
	$cont_equipo = 0;
	$mostrar_num_team = TRUE ;
	//Mostrando informacion de cada jugador
	foreach($info->Players as $player)
	{
		if( strlen ($info->Matchup) != 3 && ($info->GameType == 'Top vs Bottom' || $info->GameType == 'Melee'))
		{
			if ($mostrar_num_team)
			{
				$temp = $cont_equipo+1;
				echo 'Team '. $temp;
			}
		$mostrar_num_team = FALSE ;
		}
		
		//Mostrando info SOLO de los jugadores
		//$player->Human no sirve, posible bug ???
		if ( !$player->IsObserver && !$player->Computer)
		{
		//Obteniendo el color del jugador
		$colHTML = dechex($player->ColorHTML);
		if(strlen($colHTML) < 6) 
		{
			$padding = str_repeat("0", 6 - strlen($colHTML));
			$colHTML = $padding . $colHTML; 
		}

		//echo $colHTML;
		//Etiqueta para definir la imagen de la raza, luego se concatena con la imagen de raza que corresponda
		$imagen_raza="<img src=\"img/";
		
		//Definiendo la raza
		switch($player->RaceName){
		
		//En el caso de Terran, se concatena con terran.png, terminando el anterior img
		case 'Terran':$imagen_raza=$imagen_raza."terran.png\" title=\"Terran\" />";break;
		
		//En el caso de protoss, se concatena con terran.png, terminando el anterior img
		case 'Protoss':$imagen_raza=$imagen_raza."protoss.png\" title=\"Protoss\" />";break;
		
		//En el caso de zerg, se concatena con terran.png, terminando el anterior img
		case 'Zerg':$imagen_raza=$imagen_raza."zerg.png\" title=\"Zerg\" />";break;
		default:break;
		}

		//Define el div donde se muestra la informacion del player (tanto raza, apm)
		echo "<div class=\"player\">";
		
		//Mostrando imagen de raza
		echo "".$imagen_raza."";
		
		//Definiendo el color de la raza
		switch ( $colHTML )
		{
			case 'dcdc3c': echo "<span class=\"yellow\">" . $player->Name . "</span><br>" ;
					       break;
			case 'ffffff': echo "<span class=\"white\">" . $player->Name . "</span><br>" ;
					       break;
			case 'fcfc8f': echo "<span class=\"paleyellow\">" . $player->Name . "</span><br>" ;
					       break;
			case '209070' :echo "<span class=\"teal\">" . $player->Name . "</span><br>" ;
					       break;
			case 'e87824': echo "<span class=\"orange\">" . $player->Name . "</span><br>" ;
					       break;
			case 'ff0000': echo "<span class=\"red\">" . $player->Name . "</span><br>" ;
					       break;
			case '5c2c14': echo "<span class=\"brown\">" . $player->Name . "</span><br>" ;
					       break;
			case '0f930f': echo "<span class=\"green\">" . $player->Name . "</span><br>" ;
					       break;
			case '547cdc': echo "<span class=\"aqua\">" . $player->Name . "</span><br>" ;
					       break;
			case 'efcebd': echo "<span class=\"tan\">" . $player->Name . "</span><br>" ;
					       break;
			default :      echo "<font color=\"#$colHTML\"><b>" . $player->Name . "</b></font><br>";
					       break;
		}
		
		//mmmmm no cacho pa que sirve :P, de cualquier forma si se saca los resultados se ven mal
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//Mostrando informacion de APM
		echo "<img src=\"img/apm.png\" title=\"APM\">".$player->APM." APM<br>&nbsp;&nbsp;&nbsp;&nbsp;";
		
		//Mostrando informacion de Pocision inicial, usando sistema 12
		echo "<img src=\"img/sl.png\" title=\"Starting Location\"> ". $player->StartingLocation12 ."</div>";
		
		if( strlen ($info->Matchup) != 3 && ($info->GameType == 'Top vs Bottom' || $info->GameType == 'Melee'))
		{
			$num_integrantes_equipo[$cont_equipo] -- ;		
			if ($num_integrantes_equipo[$cont_equipo] == 0)
			{	
				$cont_equipo ++;
				$num_Teams -- ;
				echo '</div>';
				if ($num_Teams != 0)
				{
					echo '<div class="team">';
				 	$mostrar_num_team = TRUE ; 
				}
						
			}
		}
		
		//Definiendo imagen y nombre del Winner
		if ( $info->Winner->Name == $player->Name )
		{
			$nombre_winner = $info->Winner->Name;
			$imagen_winner = $imagen_raza;
		}
		
		}		
	}
	
	?>
</div>

<? 	/*
	Contando el numero de jugadores, lo hice	
	con $info->Matchup por que ese retorna el juego 1v1 solamente, es decir no
	depende de los observer o la CPU, por lo que siempre retorna 3 caracteres en 
	el caso de ser un match 1v1.
	*/
	if(  strlen($info->Matchup ) == 3 ) {
	

	?>

<div id="winner">
	<img src="img/winner.gif"> <a id="winnerRev" class="spoilerRev">Show Winner</a>
	<p id="winnerName">
	<?
		echo $imagen_winner . $nombre_winner ;
	?>
	</p>
</div>
	<script>
	$(document).ready(function(){
	$("#winnerRev").click(function (){
	$("#winnerName").toggle(); });});
	</script>

	 <? } ?>

		<?
		//Mostrando al ganador (segun un cierto nivel de probabilidad)
		//echo "<br><img src=\"img/winner.gif\" title=\"Ganador\"><b>Ganador:</b>" ;
		ECHO "<BR>";
		//ECHO "<BR>";
		//echo $imagen_winner . $nombre_winner ;
		/*ECHO "<BR>";
		ECHO "<BR>";*/
		
		//probability, in %, that this guess is true. Because the replay does not include any information about who really won a game, we can only guess who won
		//echo "<br><b>Probabilidad: </b>" . $info->Winner->Probability ."%";
		
	//Mostrando informacion de NO jugadores ( Observer, CPU )
	foreach($info->Players as $player)
	{
		$imagen_raza="<img src=\"img/";
		
		if ( $player->IsObserver || $player->Computer)
		{
		
		switch (1)
		{
		case $player->IsObserver : $imagen_raza=$imagen_raza."obs.png\" title=\"Observer\" />";
									break;
		//Si el jugador es la CPU, se muestra nombre y raza solamente
		case $player->Computer :
								//Definiendo la raza
								switch($player->RaceName){
								
								//En el caso de Terran, se concatena con terran.png, terminando el anterior img
								case 'Terran':$imagen_raza=$imagen_raza."terran.png\" title=\"Computer\" />";break;
								
								//En el caso de protoss, se concatena con terran.png, terminando el anterior img
								case 'Protoss':$imagen_raza=$imagen_raza."protoss.png\" title=\"Computer\" />";break;
								
								//En el caso de zerg, se concatena con terran.png, terminando el anterior img
								case 'Zerg':$imagen_raza=$imagen_raza."zerg.png\" title=\"Computer\" />";break;
								default:break;
								}
		default:break;
		}

		//Define el div donde se muestra la informacion de los Observer o CPU
		echo "<div>";

		//Mostrando imagen de raza
		echo "".$imagen_raza."";

		echo  $player->Name ;
		
		//mmmmm no cacho pa que sirve :P, de cualquier forma si se saca los resultados se ven mal
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "</div>";
		
		}
	}
	?>
</td>
<td id="mapInfo">
<?
	//Verificando si se desea generar el mapa en alta calidad
	if( !strcmp ($op_mapa_high,'mapa_alta_calidad' ) )
	{
		//Generando mapa en alta calidad, lo guarda en high.jpg
		$map = php_bw_jpg_from_rep_ratio($dir_starcraft, 
										 $file_repe,
										 $prefix_img."high.jpg", 1, REPASM_HIGH_QUALITY, 40 );

		//Mostrando mapa en alta calidad		
		if( $map->ErrorCode == 0 )
			echo "<a class=\"infoP\" target=\"_blank\" href=\"".$prefix_img_rel."high.jpg\">";
		else
			echo "Error : " . $map->ErrorString;
	}
	
	//Definiendo el ratio del minimap, dependiendo del ancho del mapa
	switch ( $info->Map->Height )
	{
		case '128':$ratio = 22 ;
		           break;
		
		default   :$ratio = 10 ;
		           break;
	}
	
	//Generando mapa en baja calidad, lo guarda en low.jpg
    $map = php_bw_jpg_from_rep_dim($dir_starcraft, 
									 $file_repe,
									 $prefix_img."low.jpg", 192, REPASM_LOW_QUALITY, 85 );

	//Mostrando mapa, si no hay error
    if($map->ErrorCode == 0)
	{
		//Mostrando el mapa peque�o (como una vista previa)
		echo "<img id=\"minimap\" src=\"".$prefix_img_rel."low.jpg\"></a>";
		
		//Para que muestre el nombre del mapa en un tooltip
		echo "<span class=\"tooltip\"><span class=\"highlight\">".$info->Map->Name."</span><br>Click to enlarge</span>";
	}
    else
        echo "Error : " . $map->ErrorString;
?>

<?
	//echo "<br>";
	
	//Nombre del mapa
	echo "<b>".$info->Map->Name."</b>";
	echo "<br>";
	
	//Tipo de terreno
	echo "<b>"."Terreno: " . $info->Map->TilesetName."</b>";
	echo "<br>";
	
	//Mostrando dimensiones
	echo "<b>"."Dimensiones: ".$info->Map->Width."x".$info->Map->Height."</b>";
?>
<br>

<img src="img/map_info.png">
<?
	//Verificando si se desea generar el mapa en alta calidad
	if( !strcmp ($op_mapa_high,'mapa_alta_calidad' ) ) {
	//Mostrando mapa en alta calidad
	echo "<a class=\"infoP\" target=\"_blank\" href=\"".$prefix_img_rel."high.jpg\"><B>Ver en alta calidad</B></a>";
	echo "<span class=\"tooltip\">Click to enlarge</span>";
	}
?>
</td>
</tr>
</thead>
</table>                		
</div>
<br>
<br>
<div class="footer">
<p>Copyright 2011  <img src="img/scratch.gif"></p>
</div>
</body>
</html>