<?
// agora nao eh mais preciso criar uma funcao para utilizar com outras funcoes do php como:

// filtrar um array separando numeros pares e numeros impares.

$numeros = range(1, 20, 3);
$pares = array_filter($numeros, function($v) {
	return $v % 2 == 0;
});

$impares = array_filter($numeros, function($v) {
	return $v % 2 != 0;
});

echo "Array completo:";
print_r($numeros);

echo "PARES:";
print_r($pares);

echo "IMPARES:";
print_r($impares);

?>