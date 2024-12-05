<?php

use App\Contracts\IPersistent;
use App\Models\GameService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $app_folder = app_path('GameApps/gtn/Services') . '/*.php';
        $services_in_folder = glob($app_folder);
        // only interested in classes that extend GameService and implement IPersistent
        $services = array_filter($services_in_folder, function ($file) {
            if ($this->isPhpFileAClass($file->getPathname())) {
                $class_name = Str::before($file->getPathname(), '.php');
                $class_name = 'App' . Str::after($class_name, app_path());
                $class_name = str_replace('/', '\\', $class_name);
                $class = new ReflectionClass($class_name);
                return $class->isSubclassOf(GameService::class) && $class->implementsInterface(IPersistent::class);
            }
            return false;
        });

        //if (isset($this->command)) {
        echo 'Creating tables for ' . count($services) . ' services';
        //}

        // Schema::create('gtn_services', function (Blueprint $table) {
        //     $table->id(); // PK and FK to components
        //     $table->foreign('id')->references('id')->on('game_services')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('gtn_services');
    }

    private function isPhpFileAClass($file): bool
    {
        $content = file_get_contents($file);
        $tokens = token_get_all($content);
        $class_token = false;
        foreach ($tokens as $token) {
            if ($token[0] === T_CLASS) {
                $class_token = true;
            }
            if ($class_token && $token[0] === T_STRING) {
                return true;
            }
        }
        return false;
    }
};
