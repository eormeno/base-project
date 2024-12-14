<?php

namespace Tests\Helpers;

use Illuminate\Support\Facades\Artisan;

class TableTools
{
    public static function showTable($table, $columns =[], $limit = 10)
    {
        Artisan::call('db:show', [
            'table' => $table,
            'columns' => $columns,
            '--limit' => $limit
        ]);
        $consoleOutput = Artisan::output();
        echo PHP_EOL . $consoleOutput;
    }
}
