<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use File;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {className : Class name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear recursos CRUD';

    protected $className;
    protected $folderName;
    protected $routeName;

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
        $this->gatherParameters();
    }

    protected function gatherParameters()
    {
        $this->className  = $this->argument('className');
        $this->folderName = $this->ask('¿Cuál es el nombre de la carpeta para las vistas? (Debe ser en plural, capitalize, sin caracteres especiales ni tildes) Ej: SemillerosInvestigacion');
        $this->routeName  = $this->ask('¿Cuál es el nombre para la ruta? (Debe ser en minúscula, plural, sin caracteres especiales ni tildes y los espacios se reemplazan por -)? Ej: semilleros-investigacion');

        Artisan::call("make:model $this->className -c -r");
        Artisan::call("make:request $this->className" . "Request");

        File::makeDirectory(base_path() . "/resources/views/pages/{$this->folderName}", 0777, true);
        File::put(base_path() . "/resources/views/pages/{$this->folderName}/Index.blade.php", 'Contents');
        File::put(base_path() . "/resources/views/pages/{$this->folderName}/Show.blade.php", 'Contents');
        File::put(base_path() . "/resources/views/pages/{$this->folderName}/Edit.blade.php", 'Contents');
        File::put(base_path() . "/resources/views/pages/{$this->folderName}/Create.blade.php", 'Contents');
        $this->info(__("Copie y pegue el siguiente código en protected \$policies del AuthServiceProvider.php: 'App\Models\:className' => 'App\Policies\:classNamePolicy',", ['classNamePolicy' => $this->className . 'Policy', 'className' => $this->className]));
        $this->info('=======================================================================');
        $this->info('Copie y pegue el siguiente código en web.php:');
        $this->info(__('use App\Http\Controllers\:controllerName', ['controllerName' => $this->className . 'Controller;']));
        $this->info("Route::resource('{$this->routeName}', {$this->className}Controller::class)->parameters(['{$this->routeName}' => 'reemplazar ruta en singular']);");
        $this->info('=======================================================================');
    }
}
