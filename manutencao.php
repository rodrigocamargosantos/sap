<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Manutenção</title>
</head>
<body>
<div id='principal' style='position:relative; width:100p%; height:100%; text-align:center; vertical-align: middle;' >	
		<img src="./imagens/VidaDeProgramador.png" />	
</div>
</body>
<style>
body{
/*-webkit-transform: rotate(-369deg); 
-moz-transform: rotate(-369deg);
filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);*/	
}
</style>
<script type="text/javascript" src="./js/jquery-2.0.2.js"></script>
<script type="text/javascript">
$(document).ready(function()
{	
	setTimeout("divFormiga()",1000);
});
function rand(min,max) {
    var result = Math.floor(Math.random() * (max+1));
    if(result < min){
        return rand(min,max);
    } else {
        return result;
    }
}
function divFormiga(){
	
	var heightDiv = $("#principal").height();
	var widthDiv = $("#principal").width();

	var top = rand(0,heightDiv);
	var left = rand(0,widthDiv);
	var grau = rand(0,360);
	$("#principal").append("<div style='position:absolute; top:"+top+"px; left:"+left+"px; -webkit-transform: rotate("+grau+"deg);'><img src='./imagens/bug.gif'/></div>");
	setTimeout("divFormiga()",1000);
}
</script>
</html>