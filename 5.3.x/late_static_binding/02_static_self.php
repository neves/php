<?

class Table {
	public static function select() {
    // utilizar static ao inves de self
		$table = static::TABLE_NAME;
		return "SELECT * FROM $table";
	}
}

class Produtos extends Table {
	const TABLE_NAME = "produtos";
}

echo Produtos::select();

?>