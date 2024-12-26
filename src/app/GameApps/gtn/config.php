<?php

return [
    'image' => 'guess-the-number.jpeg',
    'name' => 'Adivina el número',
    'description' => 'Un simple juego donde adivinas un número entre 1 y 1024.',
    'prefab_name' => 'gtn.root-prefab', // This is the name of the prefab that will be used to create the game.
    'prefab_attributes' => ['value' => 1],  // These are the attributes that will be passed to the prefab when it is created. It is equivalent to the constructor parameters of a class.
    'client' => 'blade',
];
