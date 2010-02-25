<?

$array = array("a", "b");
unset($array[1]);
$array[] = "c";

print_r($array);

unset($array[2]);

// O indice maximo deveria ser serializado junto com o array,
// o mesmo para var_export
$array = unserialize(serialize($array));

$array[] = "b";

print_r($array);

?>