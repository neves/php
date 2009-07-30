
<?

class Table {
	public static function select() {
		$table = get_called_class();
		$table = strtolower($table);
		return "SELECT * FROM $table";
	}
}

class Produtos extends Table {
	
}

echo Table::select();
echo "\n";
echo Produtos::select();

?>


