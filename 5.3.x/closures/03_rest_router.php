<?

class RestRouter
{
	public $GET = array();
	public $POST = array();
	public $PUT = array();
	public $DELETE = array();
	public $NOT_FOUND;

	public function __construct()
	{
		$this->NOT_FOUND = function($path, $method) {
			return "404 $method $path\n";
		};
	}

	public function dispatch($path, $method)
	{
		$router = $this->$method;
		foreach($router as $regexp => $closure)
		{
			if (preg_match("!^$regexp$!", $path, $matches) > 0)
			{
				array_shift($matches); // retira o path completo
				return call_user_func_array($closure, $matches);
			}
		}
		$closure = $this->NOT_FOUND;
		return $closure($path, $method);
	}
}

$R = new RestRouter();

$R->GET["/posts"] = function() {
	return "GET /posts\n";
};

$R->POST["/posts/(\d+)"] = function($id) {
	return "POST /posts/$id\n";
};

echo $R->dispatch("/posts", "GET");
echo $R->dispatch("/posts", "POST");
echo $R->dispatch("/posts/23", "POST");

?>