<?php

function str_ends_with($haystack, $needle)
{
    
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;

}

function str_starts_with($haystack, $needle)
{

    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);

}

?>