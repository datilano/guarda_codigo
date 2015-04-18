$(document).ready(function(){
  $("a.new_window").attr("target", "_blank");
 });

 function frmEdit(registroId) {
	 location.href = location.pathname + "?a=edit&id_reg=" + registroId;
 }

function confirmaBorraRegistro(registroId) {
	if(confirm("¿Estas seguro de eliminar el registro?")){
		location.href = location.pathname + "?a=delete&id_reg=" + registroId;
	}
}

function Objeto(id){
	if (document.getElementById)
		x = document.getElementById(id);
	else if (document.all)
		x = document.all[id];
	else if (document.layers)
		x = document.layers[id];
	return x;
}