<?

// utiliza closure para definir o autoload
spl_autoload_register(function($class_name) {
	// converte \ em / para incluir o arquivo.
	$class_name = str_replace('\\', '/', $class_name);
	require_once $class_name.".php";
});

$tabela = new neves\Tabela();

var_dump($tabela);

?>