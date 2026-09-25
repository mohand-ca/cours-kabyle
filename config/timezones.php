<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Learner timezone preview options
    |--------------------------------------------------------------------------
    |
    | Cities offered in the teacher availability calendar so a teacher can
    | preview their published slots in a learner's timezone. Keys map to IANA
    | identifiers; human labels live in lang/{fr,en}/teacher.php under
    | `availability.timezones.*` to respect the translation rule.
    |
    */
    'preview' => [
        'paris' => 'Europe/Paris',
        'london' => 'Europe/London',
        'montreal' => 'America/Montreal',
        'la' => 'America/Los_Angeles',
        'dubai' => 'Asia/Dubai',
    ],
];
