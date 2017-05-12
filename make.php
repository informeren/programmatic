#!/usr/bin/env php
<?php

$phar = new Phar("dist/programmatic.phar");

$phar->startBuffering();

$preStub = "#!/usr/bin/env php
<?php
if (file_exists( __DIR__ . '/programmatic.ini')) {
    Phar::mount( 'etc/programmatic.ini', __DIR__ . '/programmatic.ini');
} else {
    Phar::mount('etc/programmatic.ini', 'phar://' . __FILE__ . '/etc/default_programmatic.ini');
}
?>
";

$defaultStub = $phar->createDefaultStub('bin/programmatic.php');

$stub = $preStub . $defaultStub;

$phar->buildFromDirectory(dirname(__FILE__));

$phar->setStub($stub);

$phar->stopBuffering();
