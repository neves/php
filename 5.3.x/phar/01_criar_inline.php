<?php
// criar um arquivo PHAR inline
$phar = new Phar('phpinfo.phar.php');
$phar['index.php'] = '<? phpinfo() ?>';
$phar->setStub('<?php
Phar::webPhar();
__HALT_COMPILER(); ?>');

?>