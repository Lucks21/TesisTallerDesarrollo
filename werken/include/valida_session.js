function valida_session (tmp_sess) {
	if  (tmp_sess != "no_session") {
		document.location=tmp_sess;
	}
	return true;
}