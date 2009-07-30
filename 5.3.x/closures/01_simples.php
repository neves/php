<?

// funcao tradicional
function soma($a, $b) {
	return $a + $b;
}

// closure
$soma = function($a, $b) {
	return $a + $b;
};

echo soma(15, 45);
echo "\n";
echo $soma(10, 20);

?>

