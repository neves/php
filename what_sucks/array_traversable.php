<?

function loop(Traversable $traversable)
{
  foreach($traversable as $k => $v)
    echo "$k => $v\n";
}

$primos = explode(" ", "1 2 3 5 7 11 13 17 19");

// loop($primos);
loop(new ArrayIterator($primos));

?>