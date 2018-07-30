<?php

function prepr($str, $color = 'black')
{
    echo '<pre style="color:' . $color . '">';
    print_r($str);
    echo '</pre>';
}