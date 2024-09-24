<?php
namespace Database\Seeders;

use App\Models\MtqItemClass;
use Illuminate\Database\Seeder;

class MtqItemClassesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['slug' => 'selector', 'name' => 'Selector', 'icon' => 'selector.svg', 'description' => 'Selection tool', 'default_quantity' => 1],
            ['slug' => 'clue', 'name' => 'Clue', 'icon' => 'clue.svg', 'description' => 'Hint to the next location', 'default_quantity' => 2],
            ['slug' => 'flag', 'name' => 'Flag', 'icon' => 'flag.svg', 'description' => 'Marking tool', 'default_quantity' => 8],
        ];

        MtqItemClass::factory()->createMany($items);
    }
}
