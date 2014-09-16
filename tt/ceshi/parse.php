<?php
require_once('hit_count.php');


$h = new hit_count;

$h->search = array(
    'list' => 'search_list.txt',
    'inquire_list' => 'search_ilist.txt',
    'detail' => 'search_detail.txt',
    'advertis' => 'search_adv.txt'
);

$tmp = $h->parse_file();

var_dump($tmp);