<?php


function getRand($n)
{
    $max = $n + 1;
    $bigend = ((1+$max)*$max)/2;
    $rand = rand();
    $x = abs(intval($rand)%$bigend);
    $sum = 0;
    for($i = 1; $i<$max; $i++)
    {
        $sum += ($max - $i);
        if($sum > $x) {
            return $i;
        }
    }
    return 1;
}


echo getRand(5);