<?

$phar = new Phar("class.phar.php");
$phar->addFile('class.php');
$phar->setStub("<? __HALT_COMPILER(); ?>");

?>