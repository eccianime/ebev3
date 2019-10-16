function onBodyLoad() {
	document.addEventListener("deviceready", PGcargado, false);
}

function PGcargado(){

	$.mobile.defaultPageTransition = 'flip';
	$.mobile.loadingMessage = "Cargando...";
	$.mobile.loadingMessageTextVisible = true;
	$.mobile.loadingMessageTheme = "b";
	$.mobile.pageLoadErrorMessage = "Disculpe, su solicitud no pudo ser procesada.";
	$.mobile.pageLoadErrorMessageTheme = "b";
	$.mobile.pageLoadErrorMessageTheme = "b";
	
	$.support.cors = true;
	$.mobile.allowCrossDomainPages = true;
	$.mobile.pushState = false;

	var w = parseInt($("body").css("width").substring(0,3));
	var coord = {
		"cabeza": [0.00, 13.13, 22.50, 28.13, 30.00, 28.13, 22.50, 13.13, 0.00 ],
		"pie": [40.00, 26.88, 17.50, 11.88, 10.00, 11.88, 17.50, 26.88, 40.00 ],
	};


	$("canvas").each(function() {
		var c = $(this);
		var clase = $(this).attr("data-soy");
		var color = clase == "cabeza" ? "rgb(31,139,0)" : "rgb(244,141,40)";

		c.width = w;
		var ctx = c[0].getContext("2d");

		ctx.beginPath();

		if( clase == "cabeza" ){
			ctx.moveTo(coord[clase][0],0);
		}else{
			ctx.moveTo(0, coord[clase][0]);
		}

		for (var i = 1, j = .125 ; i < coord[clase].length; i++) {
			ctx.lineTo((w*j), coord[clase][i]);
			j+=.125;
		}

		ctx.closePath();

		ctx.strokeStyle = color;
		ctx.lineWidth = 10;
		ctx.lineJoin = "round";
		ctx.stroke();

		ctx.fillStyle = color;
		ctx.fill();
	})

	setTimeout( function () {
		$(".splash").fadeOut(3000);
	}, 3000);

}

function corsinaction () {

	$.ajax({
		type: "GET",
		url: "http://appevt.zz.com.ve/webservice.php",
		dataType: "jsonp",
		jsonpCallback: 'respuestaJSONP',
	});
}

function respuestaJSONP (datos) {
	console.log(datos);
	$.each(datos,function (i, v) {
		$("#empieza").append("<br/><span>Índice: "+i+" - Valor: "+v+"</span>");
	});
}
function obtenerUbicacion () {
	navigator.geolocation.getCurrentPosition( bien, mal );

	function bien (posi) {
		$("#lati").html(posi.coords.latitude);
		$("#longi").html(posi.coords.longitude);
		$("#alti").html(posi.coords.altitude);
	};

	function mal (error) {
		switch(error.code.toString()){
			case "1":
				$("#lati").html("PERMISO DENEGADO");
				$("#longi").html("PERMISO DENEGADO");
				$("#alti").html("PERMISO DENEGADO");
			break;
			case "2":
				$("#lati").html("NO DISPONIBLE");
				$("#longi").html("NO DISPONIBLE");
				$("#alti").html("NO DISPONIBLE");
			break;
			case "3":
				$("#lati").html("TIEMPO DE RESPUESTA AGOTADO");
				$("#longi").html("TIEMPO DE RESPUESTA AGOTADO");
				$("#alti").html("TIEMPO DE RESPUESTA AGOTADO");
			break;
			default:
				$("#lati").html("ERROR DESCONOCIDO");
				$("#longi").html("ERROR DESCONOCIDO");
				$("#alti").html("ERROR DESCONOCIDO");
			break;
		}
	}
}