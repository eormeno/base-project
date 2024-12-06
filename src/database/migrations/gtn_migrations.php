<?php

use App\Models\GameService;
use App\Contracts\IPersistent;
use App\Utils\ReflectionUtils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    public function up(): void
    {
        // collect(File::allFiles(app_path('GameApps/gtn/Services')))->each(function ($file) {
        //     $class_name = Str::before($file->getPathname(), '.php');
        //     $class_name = 'App' . Str::after($class_name, app_path());
        //     $class_name = str_replace('/', '\\', $class_name);
        //     $class = new ReflectionClass($class_name);
        //     if (
        //         $class->isSubclassOf(GameService::class) &&
        //         $class->implementsInterface(IPersistent::class)
        //     ) {
        //         $table_name = $class->getMethod('getTable')->invoke(new $class_name);
        //         $config = $class->getMethod('config')->invoke(null);
        //         Schema::create($table_name, function (Blueprint $table) use ($config) {
        //             $table->id(); // PK and FK to components
        //             $table->foreign('id')->references('id')->on('game_services')->onDelete('cascade');
        //             foreach ($config as $column => $type) {
        //                 $macro = $type[0];
        //                 $default = $type[1];
        //                 $table->$macro($column)->default($default);
        //             }
        //         });
        //     }
        // });
        $tables = 0;
        ReflectionUtils::findClassesInPath('GameApps/gtn/Services', function ($class) use (&$tables) {
            $table_name = $class->getMethod('getTable')->invoke(new $class->name);
            $config = $class->getMethod('config')->invoke(null);
            Schema::create($table_name, function (Blueprint $table) use ($config, &$tables) {
                $table->id(); // PK and FK to components
                $table->foreign('id')->references('id')->on('game_services')->onDelete('cascade');
                foreach ($config as $column => $type) {
                    $macro = $type[0];
                    $default = $type[1];
                    $table->$macro($column)->default($default)->nullable();
                }
                $tables++;
            });
        }, GameService::class, IPersistent::class);

        print "Created {$tables} tables";
    }

    public function down(): void
    {
        ReflectionUtils::findClassesInPath('GameApps/gtn/Services', function ($class) {
            $table_name = $class->getMethod('getTable')->invoke(new $class->name);
            Schema::dropIfExists($table_name);
        }, GameService::class, IPersistent::class);
    }
};
