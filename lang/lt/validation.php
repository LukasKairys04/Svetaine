<?php

return [
    'required' => 'Laukas :attribute privalomas.',
    'email' => 'Laukas :attribute turi būti galiojantis el. pašto adresas.',
    'min' => [
        'string' => 'Laukas :attribute turi būti bent :min simbolių.',
        'numeric' => 'Laukas :attribute turi būti bent :min.',
    ],
    'max' => [
        'string' => 'Laukas :attribute negali būti ilgesnis nei :max simbolių.',
        'numeric' => 'Laukas :attribute negali būti didesnis nei :max.',
    ],
    'confirmed' => 'Laukas :attribute nesutampa su patvirtinimu.',
    'unique' => 'Laukas :attribute jau naudojamas.',
    'regex' => 'Laukas :attribute formatas neteisingas.',
];
