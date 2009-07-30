
<?

class A {
	public static function __callStatic($sufixo, $args) {
		$function_name = "array_$sufixo";
		return call_user_func_array($function_name, $args);
	}
}

$array = A::fill(0, 5, 7);
print_r($array);

echo A::sum($array);
echo "\n";
echo A::product($array);

?>


