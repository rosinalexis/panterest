<?php

namespace App\Twig;


use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'doSomething']),
            new TwigFunction('set_active_route', [$this,'setActiveRoute']),
        ];
    }

    public function setActiveRoute(string $route, ?string $activeClass ='active'): string
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        return $currentRoute == $route ? 'active' : '';
    }

    /**
     * doSomething permet de mettre au pluriel ou au singulier un mot dans un titre en fonction du nombre
     * d'objet
     * @param int $count  le nombre d'objet
     * @param string $singular  le mot au singlier
     * @param string|null $plural  le mot au pluriel
     * @return string  le mot à retourner
     */
    public function doSomething(int $count,string $singular, ?string $plural = null) :string
    {
        $plural ??= $singular.'s';
        $result = $count === 1 ?  $singular : $plural ;
        return "$count $result";
    }
}
