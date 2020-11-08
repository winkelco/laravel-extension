<?php

namespace WinkelCo\LaravelExtension\Support;

use WinkelCo\LaravelExtension\Facades\Hook;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $extension;

    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct(app());
        $this->extension = explode('\\', str_replace('Extension\\', '', get_called_class()))[0];
    }

    /**
     * Add action
     *
     * @param string $name action name / action key
     * @param \Closure|Array $callback action event
     * @param int $priority action priority on call
     * @return void
     */
    public function addAction(string $name, $callback, int $priority = 10)
    {
        return Hook::addAction($this->extension, $name, $callback, $priority);
    }

    /**
     * Remove action
     *
     * @param string $name
     * @param integer $priority
     * @return void
     */
    public function removeAction(string $name, int $priority = 100)
    {
        return Hook::removeAction($name, $priority);
    }

    /**
     * Add filter
     *
     * @param string $name filter name / filter key
     * @param \Closure|Array $callback filter event
     * @param int $priority filter priority on call
     * @return void
     */
    public function addFilter(string $name, $callback, int $priority = 10)
    {
        return Hook::addFilter($this->extension, $name, $callback, $priority = 10);
    }
}
