<?

class GChart
{
    private $atributos = array();

    public function __construct()
    {
        $this->setSize(400, 200);
        $this->setTitle("Título\ndo Gráfico");
        $this->cht = "lc";
		$this->chxt = "y";
    }

    public function setWidth($valor)
    {
        $this->setSize($valor, $this->getHeight());
    }

    public function getWidth()
    {
        $size = $this->getSize();
        return $size["width"];
    }

    public function setHeight($valor)
    {
        $this->setSize($this->getWidth(), $valor);
    }

    public function getHeight()
    {
        $size = $this->getSize();
        return $size["height"];
    }

    public function setSize($width, $height)
    {
		$this->validateSize($width, "width");
		$this->validateSize($height, "height");
		$this->validateArea($width, $height);
        $this->chs = "{$width}x{$height}";
    }

	private function validateSize($size, $label)
	{
		if ($size > 1000) 
			throw new Exception("Tamanho máximo permitido para $label é 1000, $size informado.");
	}
	
	private function validateArea($width, $height)
	{
		$area = $width * $height;
		$area_maxima = 300 * 1000;
		if ($area > $area_maxima)
			throw new Exception("Área máxima permitida é $area_maxima, informado $area = $width x $height");
	}

    public function getSize()
    {
        array_combine(array("width", "height"), explode("x", $this->chs));
    }

    public function setTitle($valor)
    {
        $this->chtt = strtr($valor, "\n", "|");
    }

    public function getTitle()
    {
        return strtr($this->chtt, "|", "\n");
    }

    public function setDados($dados)
    {
        $this->setLabels(array_keys($dados));
        $this->setValues(array_values($dados));
    }

    public function setLabels($labels)
    {
        $this->chl = implode("|", $labels);
    }

    public function setValues($values)
    {
        $this->chd = "t:" . implode(",", $values);
        $min = min($values);
        $max = max($values);
        $this->setMinMax($min, $max);
    }

    public function setMinMax($min, $max)
    {
        //$this->chds = "$min,$max";
    }

	public function setColors($cores)
	{
		if (is_array($cores)) $cores = explode(",", $cores);
		$this->chco = $cores;
	}

    public function __set($atributo, $valor)
    {
        if ($valor === null)
            unset($this->atributos[$atributo]);
        else
            $this->atributos[$atributo] = $valor;
    }

    public function __get($atributo)
    {
        return @$this->atributos[$atributo];
    }

    public function url()
    {
        return "http://chart.apis.google.com/chart?" . $this->parametros();
    }

    public function img()
    {
        return sprintf('<img src="%s" />', $this->url());
    }

    public function parametros()
    {
        $atributos = array();
        foreach($this->atributos as $atributo => $valor):
            $atributos[] = "$atributo=" . urlencode(utf8_encode($valor));
        endforeach;
        return implode("&", $atributos);
    }
}

$chart = new GChart;
$chart->setDados(array(
    "php" => 150,
    "java" => -15,
    "ruby" => 75
));
$chart->setColors("FF0000");
$chart->cht = "bvs";
$chart->chxt = "y,x,r";
$chart->chxl = "0:-100,100";

echo $chart->img();
echo urldecode($chart->parametros());
/*
LINE
	lc chart
	ls spark
	lxy duas linhas
BAR
	bhs barra horizontal
	bcs barra vertical
	bhg barra horizontal agrupada
	bvg barra vertical agrupada
	chbh barra horizontal
PIE
	p 2d
	p3 3d
	pc concentric
Venn diagrams
	v
Scatter plots
	s
Radar charts
	r
	rs com fill area
MAP
	cht=t, and chtm=<geographical area>
Google-o-meters
	gom
QR codes
	qr
*/
?>