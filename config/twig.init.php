<?php

return function($config) {
    $templateDir = $config['basepath'] . 'templates';

    $loader = new \Twig\Loader\FilesystemLoader();
    $paths = $config['templates'] ?? [];
    foreach ($paths as $namespace => $path) {
        $loader->addPath($templateDir . $path, $namespace);
    }
    return new \Twig\Environment($loader);
};
