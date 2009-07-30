<?

// criar um arquivo PHAR apartir de um diretorio

$phar = new Phar('site.phar.php');
$phar->buildFromDirectory(dirname(__FILE__) . '/../../security/estrutura');
$phar->setStub($phar->createDefaultStub('public/index.php', 'public/index.php'));

?>