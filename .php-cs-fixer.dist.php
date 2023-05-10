<?php

$finder = (new PhpCsFixer\Finder)->in(__DIR__ . '/src');

return (new \Clinkards\PhpCsFixerConfig\Config)->setFinder($finder);
