<?php
    //统计开始时间 日历用的
    $C->TJ_STARTTIME = array(
        'tootoo' => '2010-7-16',
        'yaphon' => '2010-7-17',
        'tootoomart' => '2010-7-17'
    );

    //-----------------------------------------------------
    //收录配置
    $C->RECORD_ENGINE = array(
        'google' => 'http://www.google.com/search?hl=en&source=hp&q=site%3A*****&aq=f&aqi=g4g-s1g5&aql=&oq=&gs_rfai=',
    );

    //需要查询的urls
    $C->RECORD_URLS = array(
        'tootoo' =>  array(
            'www.tootoo.com',
            'www.tootoo.com/buy-',
            'www.tootoo.com/s-',
            'www.tootoo.com/d-',
            'www.tootoo.com/idetail/',
            'www.tootoo.com/inquire/',
        ),
        'yaphon' =>  array(
            'www.yaphon.com',
            'www.yaphon.com/buy-',
            'www.yaphon.com/kb-',
            'www.yaphon.com/kp-',
            'www.yaphon.com/kpr-',
        ),
        'tootoomart' => array(
            'www.tootoomart.com',
            'www.tootoomart.com/buy-',
            'www.tootoomart.com/orderlist/',
            'www.tootoomart.com/product-/',
            'www.tootoomart.com/wholesale-/'
        ),
		'chinatopsupplier' => array(
            'www.chinatopsupplier.com',
            'www.chinatopsupplier.com/buy-',
            'www.chinatopsupplier.com/manufacturer-',
            'www.chinatopsupplier.com/ps-',
            'www.chinatopsupplier.com/d-',
        ),
    );

    //-----------------------------------------------------
	//抓取结果
    //抓取配置
    $C->CRAWL = array(
        'google' => 'Googlebot',
    );

    //需要查询的链接
    $C->CRAWL_URLS = array(
        'tootoo' => array('all','buy-','d-rp','d-c','d-p','s-','company'),
        'yaphon' => array('all', 'buy-', 'kb-', 'kp-', 'kpr-'),
        'tootoomart' => array('all','buy-','product-','orderlist','wholesale-'),
		'chinatopsupplier' => array('all','buy-','manufacturer-','ps-','d-p', 'd-c')
    );

    //-----------------------------------------------------
	//点击率
    //需要查询的链接
    $C->HITS_URLS = array(
        'tootoo' => array('list','inquire_list','detail','translation'),
        'yaphon' => array(),
        'tootoomart' => array(),
		'chinatopsupplier' => array()
    );



