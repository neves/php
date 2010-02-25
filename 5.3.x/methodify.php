<?

$entrada = <<<S
 Nome:  Marco's Neves 
Email:  marcos.neves@gmail.com 
  Idade:  28  
S;

$linhas = preg_split("/\r\n|\r|\n/", $entrada);
$linhas_trim = array();
foreach($linhas as $linha):
	$linhas_trim[] = trim($linha);
endforeach;

$dados = array();

foreach($linhas_trim as $linha):
	list($key, $value) = explode(":", $linha, 2);
	$key = strtolower(trim($key));
	$dados[$key] = trim($value);
endforeach;

$partes = array();

foreach($dados as $campo => $valor):
	$valor = addslashes($valor);
	$partes[] = "$campo = \"$valor\"";
endforeach;

$sql = join(", ", $partes);

$saida = <<<S
nome = "Marco\'s Neves", email = "marcos.neves@gmail.com", idade = "28"
S;

class Methodify
{
	public function __construct($valor)
	{
		$this->valor = $valor;
	}

	public function __get($name)
	{
		return $this->__call($name);
	}

	public function __call($name, array $arguments = array())
	{
		array_unshift($arguments, $this->valor);
		$return = call_user_func_array($name, $arguments);
		return new Methodify($return);
	}

	public function explode($delimiter, int $limit = null)
	{
		$params = array();
		$params[] = $delimiter;
		$params[] = $this->valor;
		if ($limit !== null) $params[] = $delimiter;
		$return = call_user_func_array("explode", $params);
		return new Methodify($return);
	}

	public function split($delimiter, int $limit = null)
	{
		$params = array();
		$params[] = $delimiter;
		$params[] = $this->valor;
		if ($limit !== null) $params[] = $delimiter;
		$return = call_user_func_array("preg_split", $params);
		return new Methodify($return);
	}
	
	public function array_map($callback, array $extra = array())
	{
		array_unshift($extra, $this->valor);
		array_unshift($extra, $callback);
		$return = call_user_func_array("array_map", $extra);
		return new Methodify($return);
	}

	public function each($callback)
	{
		$array = array();
		foreach($this->valor as $k => $v):
			$array[$k] = call_user_func_array($callback, array($v, $k));
		endforeach;
		return new Methodify($array);
	}

	public function map($callback)
	{
		$array = array();
		foreach($this->valor as $k => $v):
			$array[$k] = call_user_func($callback, $v);
		endforeach;
		return new Methodify($array);
	}
}

$M = new Methodify($entrada);
$sql = $M->split("/\r\n|\r|\n/")
         ->map("addslashes")
         ->each(function($linha){
           list($key, $value) = explode(":", $linha, 2);
           $key = strtolower(trim($key));
           $value = trim($value);
           return "$key = \"$value\"";
         })
         ->join(", ")
         ->valor;

echo "
$sql
$saida
";

?>