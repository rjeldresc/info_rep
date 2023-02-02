<?

$prefix_img="img\\";
unlink($prefix_img."low.jpg");
unlink($prefix_img."high.jpg");

//"checked" en el input type="checkbox" para que por defecto este con el check
?>

<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">	
	<link href="favicon.ico" rel="icon" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="css/style.css">	
	<link rel="stylesheet" type="text/css" href="css/Button.css" media="all" >
	<link rel="stylesheet" type="text/css" href="css/svti.css" media="all" >
	
	<title>View Replay - InfoRep</title>
	</head>
<body>

<form action="final_rep.php" method="post" enctype="multipart/form-data">    
    <input type="hidden" name="MAX_FILE_SIZE" value="1024000">
    <br>
    <br>
	<img src="img/upload.png">
	<span class="Estilo">Replay a cargar:</span>
	<br/>
	<input name="userfile" type="file" /><br />
	<span class="Estilo">Gererar Mapa en alta calidad?</span><input type="checkbox" value="mapa_alta_calidad" name="mapa" >
	<br>
	<label class="uiButton uiButtonSpecial uiButtonLarge"><input type="submit" value="Enviar!"></label>
</form> 
    <br>
    <br>
	<br>
    <br>	
<div class="footer">
<p>Copyright 2010  <img src="img/scratch.gif"></p>
</div>

</body>
</html>