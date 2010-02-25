<?

// STDCLASS NÃO É CLASSE PAI DE TODOS OS OBJETOS, COMO A CLASSE OBJECT DE OUTRAS LINGUAGENS.

function describe($obj)
{
  $get_class = get_class($obj);
  $get_parent_class = get_parent_class($obj);
  $is_subclass_of = var_export(is_subclass_of($obj, "stdClass"), true);
  $is_a = var_export(is_a($obj, "stdClass"), true);
  $instanceof_stdClass = var_export($obj instanceof stdClass, true);
  echo "

get_class: $get_class
get_parent_class: $get_parent_class
is_subclass_of(stdClass): $is_subclass_of
is_a(stdClass): $is_a
instanceof stdClass: $instanceof_stdClass

";
}

class MinhaClasseImplicita {}
$minha_classe_implicita = new MinhaClasseImplicita();

class MinhaClasseExplicita extends stdClass {}
$minha_classe_explicita = new MinhaClasseExplicita();

$explicito = new stdClass();
$explicito->nome = "Marcos Neves";

$implicito->nome = "Marcos";

describe($explicito);
describe($implicito);
describe($minha_classe_implicita);
describe($minha_classe_explicita);

function receber_objeto(stdClass $obj) {}

receber_objeto($explicito);
receber_objeto($implicito);
receber_objeto($minha_classe_implicita);
receber_objeto($minha_classe_explicita);


?>