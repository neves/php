<?
// precisa ser a primeira declaracao do arquivo
namespace neves {
	class MySql {}
}

// nao pode existir codigo fora de um namespace,
// por isso o codigo abaixo eh colocado no namespace global.
namespace {
	$obj = new neves\MySql();
	var_dump($obj);
}

?>