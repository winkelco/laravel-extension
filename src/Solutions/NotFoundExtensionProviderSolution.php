<?php

namespace WinkelCo\LaravelExtension\Solutions;

use Facade\IgnitionContracts\RunnableSolution;

class NotFoundExtensionProviderSolution implements RunnableSolution
{
    public function getSolutionTitle(): string
    {
        return 'Extension Provider Class Not Found';
    }

    public function getSolutionDescription(): string
    {
        return 'You must create service provider in working directory extension. ';
    }

    public function getDocumentationLinks(): array
    {
        return ['Laravel Extension Docs' => 'https://www.github.com/winkelco/laravel-extension'];
    }

    public function getSolutionActionDescription(): string
    {
        return 'press the button below for create example service provider.';
    }

    public function getRunButtonText(): string
    {
        return 'Fix this for me';
    }

    public function run(array $parameters = [])
    {
        return "";
    }

    public function getRunParameters(): array
    {
        return [];
    }
}
