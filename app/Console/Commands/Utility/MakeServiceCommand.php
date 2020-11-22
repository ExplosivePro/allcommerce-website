<?php

namespace App\Console\Commands\Utility;

use Illuminate\Console\Command;
use App\Console\Commands\BaseGeneratorCommand;

class MakeServiceCommand extends BaseGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new Service Layer class';

    public $cron_name = 'Make new Service Command';
    public $cron_log = 'make-new-service';

    protected $type = 'Services';

    protected function getStub()
    {
        //dd(base_path().'/stubs/services.stub');
        return base_path().'/stubs/services.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }
}
