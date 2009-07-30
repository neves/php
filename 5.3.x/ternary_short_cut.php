
<?
error_reporting(E_ALL);
// modo antigo
$nome = isset($_REQUEST["nome"]) ? $_REQUEST["nome"] : "nome nao informado";
echo $nome;
echo "\n";
// modo novo utilizando ternary_shortcut
$nome = @$_REQUEST["nome"] ?: "nome nao informado";
echo $nome;
?>


