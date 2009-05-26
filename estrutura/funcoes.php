<?

/**
 * conecta no banco de dados e seleciona o banco.
 */
function conectar($host = "localhost", $usuario = "root", $senha = "", $banco = null)
{
	mysql_pconnect($host, $usuario, $senha) or die(mysql_error());
	mysql_select_db($banco) or die(mysql_error());
}

/**
 * executa uma sql e morre com uma mensagem de erro caso ocorrer.
 */
function query($sql)
{
	$result = mysql_query($sql) or die(mysql_error());
	return $result;
}

?>