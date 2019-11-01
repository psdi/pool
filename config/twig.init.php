<?php

return function($config) {
    $templateDir = $config['basepath'] . 'templates';

    $loader = new \Twig\Loader\FilesystemLoader();
    $paths = $config['twig_paths'] ?? [];
    foreach ($paths as $namespace => $path) {
        $loader->addPath($templateDir . $path, $namespace);
    }
    return new \Twig\Environment($loader);
};
