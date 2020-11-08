<?php

namespace WinkelCo\LaravelExtension\Commands;

use Illuminate\Console\Command;
use WinkelCo\LaravelExtension\Exceptions\ExtensionException;
use WinkelCo\LaravelExtension\Facades\Extension;

class ExtensionDisableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extension:disable {name : Extension code name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a extension';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        
        // disabling
        try {
            $enabled = Extension::disable($name);
        } catch (\Exception $th) {
            if ($th instanceof ExtensionException) $this->error($th->getMessage());
            exit;
        }

        // info
        $this->info("[*] Disable extension `{$name}`");
    }
}
