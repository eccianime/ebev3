$(function() {

	$("#boton-login").click(function() {
		var usr = $("[name=tx_correo]").val();
		switch(usr){
			case "spr":
				$.mobile.changePage("super/perfil.html");
			break;
			case "adm":
				$.mobile.changePage("admin/perfil.html");
			break;
			case "usr":
				$.mobile.changePage("usuario/perfil.html");
			break;
			default:
				$.mobile.changePage("mensajes/usuarioError.html");
			break;
		}
	})
})
