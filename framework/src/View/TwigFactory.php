<?php

namespace Igarevv\Micrame\View;

use Igarevv\Micrame\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{

    public function __construct(
      private string $pathToViews,
      private SessionInterface $session
    ) {}

    public function initialize(): Environment
    {
        $loader = new FilesystemLoader($this->pathToViews);

        $twig = new Environment($loader, [
          'debug' => true,
          'cache' => false,
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));
        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

}