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
    protected $signature = 'make:service {class} {--rep=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    protected $namespace;
    protected $class;
    protected $file;
    protected $repositoryClass;
    protected $servicePath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->namespace = 'App\Services';
    }

    /**
     * Hydrate class parameters.
     *
     * @return void
     */

    private function hydrator()
    {
        $this->class = $this->argument('class');
        $this->file = app_path("Services/$this->class.php");
        $this->repositoryClass = $this->option('rep');
    }

    private function setContents()
    {
        $template = file_get_contents(__DIR__ . './stubs/service.stub');
        if($this->repositoryClass){
            $template = file_get_contents(__DIR__ . './stubs/service.repository.stub');
        }
        
        return str_replace('{{ namespace }}', $this->namespace,            
            str_replace('{{ class }}', $this->class,
            str_replace('{{ repositoryClassInterface }}', $this->repositoryClass . 'Interface',
            str_replace('{{ attributeRepositoryClass }}', lcfirst($this->repositoryClass), $template)
        )));
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

        File::put($this->file, $this->setContents());

        $this->info('Service created successfully.');
    }
}
