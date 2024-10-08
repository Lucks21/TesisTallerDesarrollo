function valida_solicitud_material(f) {

	var msg = "\t Atención : \n\n"
	var error = 0;
	
	if ( !f.titulo.value && !f.autor_editor.value && !f.editorial.value && !f.isbn_issn.value ) {
			msg = msg + " - Ingrese la mayor cantidad de información descriptiva del material solicitado ";
			msg = msg + "en cualquiera de los campos: Titulo, Autor/Editor, Editorial, Isbn/Issn, Año Publicación.\n\n";
			error = 1;			
	}
	
	 if (f.cantidad.value < 0 || !f.cantidad.value) {
		msg = msg + "- Es obligatorio ingresar la cantidad requerida. \n\n";
		error = 1;
	 }
	if (isNaN(f.fecha_publicacion.value)) {
		msg = msg + "- Debe ingresar solo números en el año de publicación ";
		error=1;
	}
	if (((f.fecha_publicacion.value < 1900) || (f.fecha_publicacion.value > 2050)) && f.fecha_publicacion.value) {
		msg = msg + "- El año de publicación debe estar en un rango apropiado [1900...]";
		error=1;
	}		
	if (error) {
			alert(msg);
			return false;
	}     
	
	if (f.urgencia.checked) { f.urgencia.value=1; }

   return true;
	
}
