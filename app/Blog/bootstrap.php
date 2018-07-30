<?php
function autoload_helpers()
{
    foreach (glob(__DIR__ . '/Helpers/*.php') as $file) :
        require_once $file;
    endforeach;
}

/*All extra config files can be done from in here*/
autoload_helpers();
