function onBodyLoad() {
	document.addEventListener("deviceready", PGcargado, false);
}

var usuario = {};

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

	console.log("existo");

	$("#pruebacors").click(function() {
		CORS( "", "respuestaJSONP" );
	})

	/*setTimeout( function () {
		$(".splash").fadeOut(3000);
	}, 3000);*/

}

function CORS ( url, respuesta ) {
	var loading = "<div class='splash'></div>";
	//$('[data-role=page]').append(loading);
	$.ajax({
		type: "GET",
		//url: "http://localhost/ebetracking/php/webservice.php"+url,
		url: "http://appevt.zz.com.ve/webservice.php"+url,
		dataType: "jsonp",
		jsonpCallback: respuesta,
	}).done(function() {
		$(".splash").remove();
	});
}

function respuestaJSONP (datos) {
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