<?php
namespace App\Component;

class Globals
{
    // Statuses
    const STATUS_OK                 = 'ok';
    const STATUS_ERROR              = 'error';
    
    const STATUS_ERROR_TYPE_ALERT   = 'alert';
    
    const VAGRANT_MACHINE_GROUPS    = [
        'developement_machines' => 'Developement Machines',
        'test_machines'         => 'Test Machines',
    ];
}
