<?php

$web = 'public/index.php';

if (in_array('phar', stream_get_wrappers()) && class_exists('Phar', 0)) {
Phar::interceptFileFuncs();
set_include_path('phar://' . __FILE__ . PATH_SEPARATOR . get_include_path());
Phar::webPhar(null, $web);
include 'phar://' . __FILE__ . '/' . Extract_Phar::START;
return;
}

if (@(isset($_SERVER['REQUEST_URI']) && isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST'))) {
Extract_Phar::go(true);
$mimes = array(
'phps' => 2,
'c' => 'text/plain',
'cc' => 'text/plain',
'cpp' => 'text/plain',
'c++' => 'text/plain',
'dtd' => 'text/plain',
'h' => 'text/plain',
'log' => 'text/plain',
'rng' => 'text/plain',
'txt' => 'text/plain',
'xsd' => 'text/plain',
'php' => 1,
'inc' => 1,
'avi' => 'video/avi',
'bmp' => 'image/bmp',
'css' => 'text/css',
'gif' => 'image/gif',
'htm' => 'text/html',
'html' => 'text/html',
'htmls' => 'text/html',
'ico' => 'image/x-ico',
'jpe' => 'image/jpeg',
'jpg' => 'image/jpeg',
'jpeg' => 'image/jpeg',
'js' => 'application/x-javascript',
'midi' => 'audio/midi',
'mid' => 'audio/midi',
'mod' => 'audio/mod',
'mov' => 'movie/quicktime',
'mp3' => 'audio/mp3',
'mpg' => 'video/mpeg',
'mpeg' => 'video/mpeg',
'pdf' => 'application/pdf',
'png' => 'image/png',
'swf' => 'application/shockwave-flash',
'tif' => 'image/tiff',
'tiff' => 'image/tiff',
'wav' => 'audio/wav',
'xbm' => 'image/xbm',
'xml' => 'text/xml',
);

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

$basename = basename(__FILE__);
if (!strpos($_SERVER['REQUEST_URI'], $basename)) {
chdir(Extract_Phar::$temp);
include $web;
return;
}
$pt = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], $basename) + strlen($basename));
if (!$pt || $pt == '/') {
$pt = $web;
header('HTTP/1.1 301 Moved Permanently');
header('Location: ' . $_SERVER['REQUEST_URI'] . '/' . $pt);
exit;
}
$a = realpath(Extract_Phar::$temp . DIRECTORY_SEPARATOR . $pt);
if (!$a || strlen(dirname($a)) < strlen(Extract_Phar::$temp)) {
header('HTTP/1.0 404 Not Found');
echo "<html>\n <head>\n  <title>File Not Found<title>\n </head>\n <body>\n  <h1>404 - File ", $pt, " Not Found</h1>\n </body>\n</html>";
exit;
}
$b = pathinfo($a);
if (!isset($b['extension'])) {
header('Content-Type: text/plain');
header('Content-Length: ' . filesize($a));
readfile($a);
exit;
}
if (isset($mimes[$b['extension']])) {
if ($mimes[$b['extension']] === 1) {
include $a;
exit;
}
if ($mimes[$b['extension']] === 2) {
highlight_file($a);
exit;
}
header('Content-Type: ' .$mimes[$b['extension']]);
header('Content-Length: ' . filesize($a));
readfile($a);
exit;
}
}

class Extract_Phar
{
static $temp;
static $origdir;
const GZ = 0x1000;
const BZ2 = 0x2000;
const MASK = 0x3000;
const START = 'public/index.php';
const LEN = 6699;

static function go($return = false)
{
$fp = fopen(__FILE__, 'rb');
fseek($fp, self::LEN);
$L = unpack('V', $a = (binary)fread($fp, 4));
$m = (binary)'';

do {
$read = 8192;
if ($L[1] - strlen($m) < 8192) {
$read = $L[1] - strlen($m);
}
$last = (binary)fread($fp, $read);
$m .= $last;
} while (strlen($last) && strlen($m) < $L[1]);

if (strlen($m) < $L[1]) {
die('ERROR: manifest length read was "' .
strlen($m) .'" should be "' .
$L[1] . '"');
}

$info = self::_unpack($m);
$f = $info['c'];

if ($f & self::GZ) {
if (!function_exists('gzinflate')) {
die('Error: zlib extension is not enabled -' .
' gzinflate() function needed for zlib-compressed .phars');
}
}

if ($f & self::BZ2) {
if (!function_exists('bzdecompress')) {
die('Error: bzip2 extension is not enabled -' .
' bzdecompress() function needed for bz2-compressed .phars');
}
}

$temp = self::tmpdir();

if (!$temp || !is_writable($temp)) {
$sessionpath = session_save_path();
if (strpos ($sessionpath, ";") !== false)
$sessionpath = substr ($sessionpath, strpos ($sessionpath, ";")+1);
if (!file_exists($sessionpath) || !is_dir($sessionpath)) {
die('Could not locate temporary directory to extract phar');
}
$temp = $sessionpath;
}

$temp .= '/pharextract/'.basename(__FILE__, '.phar');
self::$temp = $temp;
self::$origdir = getcwd();
@mkdir($temp, 0777, true);
$temp = realpath($temp);

if (!file_exists($temp . DIRECTORY_SEPARATOR . md5_file(__FILE__))) {
self::_removeTmpFiles($temp, getcwd());
@mkdir($temp, 0777, true);
@file_put_contents($temp . '/' . md5_file(__FILE__), '');

foreach ($info['m'] as $path => $file) {
$a = !file_exists(dirname($temp . '/' . $path));
@mkdir(dirname($temp . '/' . $path), 0777, true);
clearstatcache();

if ($path[strlen($path) - 1] == '/') {
@mkdir($temp . '/' . $path, 0777);
} else {
file_put_contents($temp . '/' . $path, self::extractFile($path, $file, $fp));
@chmod($temp . '/' . $path, 0666);
}
}
}

chdir($temp);

if (!$return) {
include self::START;
}
}

static function tmpdir()
{
if (strpos(PHP_OS, 'WIN') !== false) {
if ($var = getenv('TMP') ? getenv('TMP') : getenv('TEMP')) {
return $var;
}
if (is_dir('/temp') || mkdir('/temp')) {
return realpath('/temp');
}
return false;
}
if ($var = getenv('TMPDIR')) {
return $var;
}
return realpath('/tmp');
}

static function _unpack($m)
{
$info = unpack('V', substr($m, 0, 4));
 $l = unpack('V', substr($m, 10, 4));
$m = substr($m, 14 + $l[1]);
$s = unpack('V', substr($m, 0, 4));
$o = 0;
$start = 4 + $s[1];
$ret['c'] = 0;

for ($i = 0; $i < $info[1]; $i++) {
 $len = unpack('V', substr($m, $start, 4));
$start += 4;
 $savepath = substr($m, $start, $len[1]);
$start += $len[1];
   $ret['m'][$savepath] = array_values(unpack('Va/Vb/Vc/Vd/Ve/Vf', substr($m, $start, 24)));
$ret['m'][$savepath][3] = sprintf('%u', $ret['m'][$savepath][3]
& 0xffffffff);
$ret['m'][$savepath][7] = $o;
$o += $ret['m'][$savepath][2];
$start += 24 + $ret['m'][$savepath][5];
$ret['c'] |= $ret['m'][$savepath][4] & self::MASK;
}
return $ret;
}

static function extractFile($path, $entry, $fp)
{
$data = '';
$c = $entry[2];

while ($c) {
if ($c < 8192) {
$data .= @fread($fp, $c);
$c = 0;
} else {
$c -= 8192;
$data .= @fread($fp, 8192);
}
}

if ($entry[4] & self::GZ) {
$data = gzinflate($data);
} elseif ($entry[4] & self::BZ2) {
$data = bzdecompress($data);
}

if (strlen($data) != $entry[0]) {
die("Invalid internal .phar file (size error " . strlen($data) . " != " .
$stat[7] . ")");
}

if ($entry[3] != sprintf("%u", crc32((binary)$data) & 0xffffffff)) {
die("Invalid internal .phar file (checksum error)");
}

return $data;
}

static function _removeTmpFiles($temp, $origdir)
{
chdir($temp);

foreach (glob('*') as $f) {
if (file_exists($f)) {
is_dir($f) ? @rmdir($f) : @unlink($f);
if (file_exists($f) && is_dir($f)) {
self::_removeTmpFiles($f, getcwd());
}
}
}

@rmdir($temp);
clearstatcache();
chdir($origdir);
}
}

Extract_Phar::go();
__HALT_COMPILER(); ?>
T                    action/empresa/visao.php=   ��RJ=   �j�޶         action/home.php    ��RJ        �         controller.phpG  ��RJG  քC¶         funcoes.php�  ��RJ�  t�E��         layout.action.phpI   ��RJI   ���         layout.tpl.php�  ��RJ�  �0��         public/css/layout.cssm  ��RJm  ���4�         public/index.phpl  ��RJl  �ŉ�         public/js/global.js   ��RJ   42�/�         tpl/contato.phpB   ��RJB   �ݙ$�         tpl/empresa/visao.php  ��RJ  7=�s�         tpl/empresa.php8   ��RJ8   |ƿ�         tpl/home.php   ��RJ   V�Q��      <?
$TITULO = "Empresa > Vis�o";
$horas = date("H:i:s");
?><?
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

?><?

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

?><?
$banners = array("ban1", "ban2", "ban3", "ban4", "ban6", "ban6");
?><html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title><?= isset($TITULO) ? $TITULO : $TITULO_PADRAO ?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="">
<meta name="description" content="" />
<meta name="keywords" content="" />

<link rel="stylesheet" href="css/layout.css" />

<script wsrc="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="js/global.js"></script>

</head>
<body>

<div id="site">
	<table id="topo" class="layout">
		<tr>
			<td>LOGO</td>
			<td>BANNER</td>
		</tr>
	</table>

	<table class="layout">
		<tr>
			<td id="menu">
				<h4>MENU</h4>
				<ul>
					<li><a href="?">HOME</a></li>
					<li>
						<a href="?pag=empresa">EMPRESA</a>
						<ul>
							<li><a href="?pag=empresa/visao">VIS�O</a></li>
							<li><a href="?pag=empresa/inexistente">INEXISTENTE</a></li>
						</ul>
					</li>
					<li><a href="?pag=contato">CONTATO</a></li>
				</ul>
			</td>
			<td id="conteudo"><?= $TPL ?></td>
			<td id="lateral">
			<ul>
			<? foreach($banners as $banner): ?>
				<li><?= $banner ?></li>
			<? endforeach ?>
			</ul>
			</td>
		</tr>
	</table>

	<div id="rodape">
		RODAP�
	</div>
</div>

</body>
</html>html, body {
margin: 0;
padding: 0;
border: none;
height: 100%;
}

html, td {
font-family: verdana;
font-size: 12px;
}

#site {
width: 780px;
margin: auto;
margin-top: 10px;
background: #EEE;
}

#topo {
height: 120px;
}

table.layout {
border-collapse: collapse;
width: 100%;
}

table.layout td{
padding: 0;
vertical-align: top;
}

#menu {
background-color: #E7F6FD;
width: 200px;
}

#rodape {
padding: 10px;
text-align: center;
background-color: #FCFFBA;
}

#lateral {
width: 140px;
background-color: #E7F6FD;
}

#conteudo {
background-color: #FDEBEB;
padding: 10px;
}<?
/**
Este arquivo e chamado de boot, pois apenas esta aqui para ser executado pelo servidor de pagina.
Todos os demais arquivos ficam fora da pasta public.
Na pasta public, ficam apenas os arquivos publicos como estilos, javascripts, imagens e flash.
*/

// inclui o arquivo que ira processar todas as requisicoes.
require_once "../controller.php";

?>// javascript global<h2>Contato</h2>
<p>
	Colocar aqui o formul�rio de contato
</p><h2>Empresa > Vis�o</h2>
<p>
	Exemplo de como estruturar sub menus.
</p>

<p>
	Veja que o t�tulo da janela tamb�m foi alterado pela vari�vel $TITULO definida no arquivo action/empresa/visao.php
</p>

<p>
	Agora s�o <?= $horas ?>, valor vindo do arquivo de action.
</p><h2>Empresa</h2>
<p>
	Conte�do da p�gina empresa
</p><h2>Bem Vindo</h2>���$��wY�l[�'����X `   GBMB