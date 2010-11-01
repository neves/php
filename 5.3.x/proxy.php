<?

class Proxy
{
  protected $__subject = null;

  public function __construct($subject)
  {
    $this->__subject = $subject;
    $this->__rfobj = new ReflectionObject($subject);
  }

  public function __get($name)
  {
    $p = $this->__rfobj->getProperty($name);
    $p->setAccessible(true);
    return $p->getValue($this->__subject);
  }
}

class User
{
  private $nome;
  
  public function __construct($nome)
  {
    $this->nome = $nome;
  }
}

$user = new User("Marcos");

$puser = new Proxy($user);

echo $puser->nome;

?>