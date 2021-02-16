<?php

namespace WinkelCo\LaravelExtension;

use Illuminate\Support\Collection;

class Menu
{
    protected $menu = [];

    /**
     * Add item mene
     *
     * @param string $name
     * @param mixed ...$params
     * @return void
     */
    public function add(string $name, ...$params)
    {
        $args = func_get_args(); array_splice($args, 0, 1);
        $this->menu[$name][] = $args;
    }

    /**
     * Get list of menu
     *
     * @param string $name
     * @return \Illuminate\Support\Collection
     */
    public function get(string $name = null)
    {
        $menu = $this->menu;
        if ($name != null) {
            if (!isset($this->menu[$name])) return null;
            $menu = $this->menu[$name];
        }
        return (new Collection($menu));
    }

    /**
     * Render menu to string
     *
     * @param string $name
     * @param \Closure $callback
     * @return string
     */
    public function render(string $name, \Closure $callback) : string
    {
        if (!isset($this->menu[$name])) return null;

        $result = "";
        foreach($this->menu[$name] as $menu)
        {
            $result .= call_user_func($callback, ...$menu);
        }        

        return $result;
    }
}
