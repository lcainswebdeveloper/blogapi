<?php
use Cocur\Slugify\Slugify;

function clean($str)
{
    return htmlentities($str, ENT_QUOTES, 'UTF-8', false);
}

function slugify($str, $delimiter = '-')
{
    $slugify = new Slugify();
    return $slugify->slugify($str, $delimiter);
}