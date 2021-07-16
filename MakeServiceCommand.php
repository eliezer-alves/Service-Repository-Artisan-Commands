<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {className} {--rep=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    protected $className;
    protected $file;
    protected $repositoryClasses;
    protected $servicePath;

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
     * Hydrate class parameters.
     *
     * @return void
     */

    private function hydrator()
    {
        $this->className = $this->argument('className');
        $this->file = app_path("Services/$this->className.php");
        $this->repositoryClasses = $this->option('rep');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->hydrator();
        if(!File::exists(app_path('Services'))){
            File::makeDirectory(app_path('Services'));
        }

        $template = file_get_contents(__DIR__ . './stubs/ServiceClass.stub');
        $contents = str_replace('{{ $className }}', $this->className, str_replace('{{ $since }}', date('d/m/Y'), $template));

        File::put($this->file, $contents);
    }
}
