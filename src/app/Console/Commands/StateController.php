<?php

namespace App\Console\Commands;

use App\Utils\CaseConverters;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class StateController extends Command
{
    protected $signature = 'make:state {name}';
    protected $description = 'Create a set of classes';

    public function handle()
    {$name = $this->argument('name');
        $pascalName = CaseConverters::toPascal($name);
        $camelName = CaseConverters::toCamel($name);
        $controllerName = "{$pascalName}Controller";
        $serviceName = "{$pascalName}Service";
        $serviceVariable = "\${$camelName}Service";

        $controllerContent = <<<EOT
<?php

namespace App\Http\Controllers;

use App\Services\\{$serviceName};
use App\Http\Controllers\BaseController;
use App\Http\Requests\EventRequestFilter;

class {$controllerName} extends BaseController
{
    protected {$serviceVariable};
    protected \$stateManager;

    public function __construct({$serviceName} \${$name}Service)
    {
        \$this->{$name}Service = \${$name}Service;
    }

    public function event(EventRequestFilter \$request)
    {
        \$game = \$this->{$name}Service->gameService->getGame();
        return \$this->stateManager->getState(\$game, \$request->eventInfo());
    }

    public function reset() : void
    {
        \$this->stateManager->reset(\$this->{$name}Service->gameService->getGame());
    }
}
EOT;

        // Crear el archivo del controlador
        $controllerPath = app_path("Http/Controllers/{$controllerName}.php");
        File::put($controllerPath, $controllerContent);

        // Crear la clase de servicio
        $serviceContent = <<<EOT
<?php

namespace App\Services;

class {$serviceName}
{
    // Métodos y propiedades del servicio
}
EOT;

        // Crear el archivo del servicio
        $servicePath = app_path("Services/{$serviceName}.php");
        File::put($servicePath, $serviceContent);

        // Definir las rutas predefinidas
        $routes = <<<EOT
Route::prefix('guess-the-number')->group(function () {
    Route::get('/', [GuessTheNumberController::class, 'index'])->name('guess-the-number');
    Route::post('/', [GuessTheNumberController::class, 'event'])->name('guess-the-number');
    Route::get('/reset', [GuessTheNumberController::class, '_reset'])->name('guess-the-number.reset');
});
EOT;

        File::append(base_path('routes/web.php'), PHP_EOL . $routes);

        $this->info('Controller, service and routes created successfully!');
    }
}
