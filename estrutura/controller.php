<?
$DIR = dirname(__FILE__);

// CONFIGURA��O
$PUBLIC_DIR = "$DIR/public";
$ACTION_VAR = "pag";
$ACTION_DEFAULT = "home";
$TPL_DIR = "$DIR/tpl";
$ACTION_DIR = "$DIR/action";
$TITULO_PADRAO = "T�tulo Padr�o do Site";

// pega o diret�rio atual

// fun��es padr�es
require_once "$DIR/funcoes.php";

$PAGINA_ATUAL = isset($_REQUEST[$ACTION_VAR]) ? $_REQUEST[$ACTION_VAR] : $ACTION_DEFAULT;

$TPL = "p�gina <b>$PAGINA_ATUAL</b> n�o encontrada!";

$ACTION_FILE = "$ACTION_DIR/$PAGINA_ATUAL.php";
if (file_exists($ACTION_FILE))
	require_once $ACTION_FILE;

$TPL_FILE = "$TPL_DIR/$PAGINA_ATUAL.php";
if (file_exists($TPL_FILE)):
	ob_start();
	require_once $TPL_FILE;
	$TPL = ob_get_clean();
endif;

// layout ACTION
require_once "$DIR/layout.action.php";

// layout TPL
require_once "$DIR/layout.tpl.php";

?>