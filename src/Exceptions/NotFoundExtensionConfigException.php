<?php

namespace WinkelCo\LaravelExtension\Exceptions;

use Exception;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\ProvidesSolution;
use WinkelCo\LaravelExtension\Solutions\NotFoundExtensionConfigSolution;

class NotFoundExtensionConfigException extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        return new NotFoundExtensionConfigSolution;
    }
}
