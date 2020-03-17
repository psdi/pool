<?php

return function($config) {
    $templateDir = $config['basepath'] . 'templates';

    $loader = new \Twig\Loader\FilesystemLoader();
    $paths = $config['templates'] ?? [];
    foreach ($paths as $namespace => $path) {
        $loader->addPath($templateDir . $path, $namespace);
    }

    // workaround for Twig function 'asset'
    $twig = new \Twig\Environment($loader);
    $twig->addFunction(new \Twig\TwigFunction('asset', function ($asset) {
        return sprintf('../public/%s', ltrim($asset, '/'));
    }));

    return $twig;
};
