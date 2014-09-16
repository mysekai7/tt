<?php
/* 
 * @File: Company.Class.php
 * @Description: mytootoo产品详细页公司 逻辑操作类(根据业务需求修改)
 * @Date：2009-12-31 AM
 */


/*
//----------------------------------------------------------------------------
Company类方法调用 测试文件:test_company.php
Company::init() //初始化 必须参数（$db:数据库链接句柄 $lang, $sid:公司ID, $gid:group分组ID, $ccid:公司类别ID, $action可能是缓存标记）
Company::getProductGroup()  //调用当前公司分组
Company::getCompanyInfo()   //调用公司信息
Company::getCountryName()   //当前公司国家名称
Company::getRelatedLinks()  //暂时没用上 返回结果为空
Company::getCurrentCatName()    //当前分类名称
Company::getRelateWord()    //当前分类相关词
Company::getStaticUrl()     //获得静态链接
Company::getSeo()           //获得头部seo信息
Company::CheckStatus()      //检查用户状态
Company::getRecommendProducts() //获得公司推荐产品8个
Company::getCompanyInquire()    //获得公司询盘
Company::getRandomNum()    //获得随机数
Company::dealProducts()     //产品结果处理

//----------------------------------------------------------------------------
 */

class Company
{
    private static $__CONN__;           //数据库句柄
    private static $_sid;               //公司ID
    private static $_gid;               //company_group分类ID
    private static $_ccid;              //company_category分类ID
    private static $_action;            //可能是缓存标记 ($_action == 'p')该类中没有做action判断 默认$_action=p
    private static $_lang;              //
    
    public static $company;             //公司信息
    public static $seo;                 //页面头部seo信息
    public static $product_group;       //公司产品分类
    public static $current_cat_name;    //当前分类名称

    //初始化
    public static function init()
    {
        global $DB, $lang, $sid, $gid, $ccid, $action;
        self::$__CONN__ = $DB;
        self::$_lang = $lang;
        self::$_sid = isset($sid) ? (int)$sid : die('no infomation about this sid');
        self::$_gid = isset($gid) ? $gid : null;
        self::$_ccid = isset($ccid) ? $ccid : null;
        self::$_action = isset($action) ? $action : null;
    }//end func

    //获得公司信息
    public static function getCompanyInfo()
    {
        if(isset(self::$company))
            return self::$company;
        
        self::$company = array();
        self::$company = self::_company();

        //处理公司信息
        if( is_array(self::$company) && count(self::$company) > 0 )
        {
            self::$company['comp_name']     = str_replace(array("'", '"'), '', trim(self::$company['comp_name']));
            self::$company['company_pic']   = PictrueUrl(self::$_sid, 7);   //公司图片
            self::$company['comp_desc']     = self::dealDesc(self::$company['comp_desc']);
            self::$company['comp_advert']   = substr( preg_replace("/(\s)+/", ' ', self::$company['comp_advert']), 0, 60 );     //去除广告词中多余空白并截取60个字符
            self::$company['business_type'] = self::$_lang['companyBusinessType'][self::$company['business_type']];
            self::$company['annual_sales']  = self::$_lang['companyAnnualSales'][self::$company['annual_sales']];
            self::$company['employees']     = self::$_lang['companyEmployee'][self::$company['employees']];
            self::$company['established']   = substr(self::$company['established'], 0, 4);
            $userinfo_c = get_userInfo_by_compID(self::$company["comp_id"]);
            self::$company['userstatus']    = $userinfo_c["status"];
            self::$company['website']       = stripos(self::$company['website'], 'http:') === FALSE ? 'http://'.trim(self::$company['website']) : trim(self::$company['website']);

            //出口国家处理
            $market_ids = explode(",", self::$company['main_market']);
            $market_count = count($market_ids);
            for($i = 0; $i < $market_count; $i++) {
                $market_name .= self::$_lang['productMainMarketCk'][$market_ids[$i]]."; ";
            }
            self::$company['main_market']   = substr($market_name, 0, -2);
            self::$company['main_product']  = str_replace("####", ";&nbsp;", self::$company['main_product']);

            //电话 传真 Email处理
            self::$company['contact_email'] = urlencode(base64_encode(self::$company['contact_email']));
            self::$company['tel_mobile'] = urlencode(base64_encode(self::$company['tel_mobile']));
            
            $tel = '';  //电话
            if(self::$company['tel_country_code'] != '')
                $tel .= '+'. (int)self::$company['tel_country_code'] .' ';
            if(self::$company['tel_area_code'] != '')
                $tel .= (int)self::$company['tel_area_code'] .' ';
            if(self::$company['tel'] != '') {
                if(strlen(self::$company['tel']) == 7)
                    self::$company['tel'] = substr(self::$company['tel'], 0, 3).' '.substr(self::$company['tel'], 3);
                else
                    self::$company['tel'] = substr(self::$company['tel'], 0, 4).' '.substr(self::$company['tel'], 4);

                $tel .= self::$company['tel'];
            }
            if(self::$company['tel_ext'] != '')
                $tel .= " Ext:".self::$company['tel_ext'];

            self::$company['tel'] = urlencode(base64_encode($tel));
            self::$company['tel_home'] = urlencode(base64_encode(self::$company['tel_home']));

            $fax = '';  //传真
            if(self::$company['fax_country_code'] != '')
                $fax .= '+'. (int)self::$company['fax_country_code'] .' ';

            if(self::$company['fax_area_code'] != '')
                $fax .= (int)$company['fax_area_code'].' ';

            if(self::$company['fax'] != '') {
                if(strlen(self::$company['fax']) == 7)
                    self::$company['fax'] = substr(self::$company['fax'], 0, 3).' '.substr(self::$company['fax'], 3);
                else
                    self::$company['fax'] = substr(self::$company['fax'], 0, 4)." ".substr(self::$company['fax'], 4);
                $fax .= self::$company['fax'];
            }

            self::$company['fax'] = urlencode(base64_encode($fax));
        }//end if

        return self::$company;
    }//end func

    //从数据库读取公司源始数据
    private function _company()
    {
        $company = array();
        $sql = "select * from t_company where comp_id='". self::$_sid ."' limit 1";
        $company = self::$__CONN__->fetch_one_array($sql, MYSQL_ASSOC);
        if( $compnay ) {
            //使用下面替换前必须使用nl2br()把\n转换成规则的<br />形式
            //$company['COMPANYCNAME'] = preg_replace("/(<br \/>(\s)+)+/im", '', $company['COMPANYCNAME']); 
            $repl_arr = array( '\n'=>'', '<br>'=>'', '<BR>'=>'', '<BR />'=>'', '<br />'=>'');   //新增2种替换<br /> <BR />
            foreach ($repl_arr as $key => $var) {
                $company['COMPANYCNAME']  = str_replace($key, $var, $company['COMPANYCNAME']);
                if(!eregi("^((ht|f)tp://)((([a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))|(([0-9]{1,3}\.){3}([0-9]{1,3})))((/|\?)[a-z0-9~#%&'_\+=:\?\.-]*)*)$", $company['WEBSITE'])) {
                    $company['WEBSITE'] = '';
                }
            }
        }

        return $company;
    }//end func

    //描述处理,只保留段落之间只存在一个br, 去html标签,实体化,替换多个br为1个
    public function dealDesc($str)
    {
        return preg_replace("/(<br \/>(\s)+)+/im", '<br />', nl2br(htmlspecialchars(strip_tags(trim( $str )), ENT_COMPAT)));
    }

    //获得国家名称
    public static function getCountryName()
    {
        if(!isset(self::$company))
            self::$company = self::getCompanyInfo();
        return self::$_lang['CountryNameCk'][self::$company['contact_countryid']];
    }

    //获得分类信息
    public static function getProductGroup()
    {
        self::$product_group = array();

        //company_catgory
        $sql = " SELECT cat_id as cate_code, count(*) as num  FROM `t_products` WHERE comp_id ='". self::$_sid ."' and del_flag=0 group by cat_id";
        $company_catgory = self::$__CONN__->fetch_arrays($sql, MYSQL_ASSOC);
        
        if(is_array($company_catgory) && count($company_catgory) > 0)
        {
            $company_category_count = count($company_catgory);
            
            for($i = 0; $i < $company_category_count; $i++)
            {
                $company_catgory[$i]['cate_name'] = $company_catgory[$i]['cate_code'] != '' ? Getinfo($company_catgory[$i]['cate_code']) : '';
                $company_catgory[$i]['cate_name_cut'] = strlen($company_catgory[$i]['cate_name']) > 18 ? base::rm_substr($company_catgory[$i]['cate_name'],18) . '...' : $company_catgory[$i]['cate_name'];
                $parStr = isset(self::$_action) ? 'action_'.self::$_action.',' : '';
                $parStr .= "ccid_".$company_catgory[$i]['cate_code'];
                $company_catgory[$i]['url_static'] = get_showroom_html_url2(self::$_sid, 'p', $company_catgory[$i]['cate_name'], $parStr);
            }
            
            self::$product_group['company_catgory'] = $company_catgory;
        }

        //company_group
        $sql = "SELECT count(*) as num  FROM `t_products` WHERE comp_id = '". self::$_sid ."' and del_flag = 0";
        $pro_count_arr = self::$__CONN__->fetch_arrays($sql);
        $pro_count = $pro_count_arr[0]['num'];

        $sql  = "SELECT * FROM `t_product_group` WHERE cid =". self::$_sid;
        $company_group = self::$__CONN__->fetch_arrays($sql, MYSQL_ASSOC);
        if (is_array($company_group) && count($company_group) > 0)
        {
            foreach( $company_group as $key => $val ) {
                $sql="select count(*) as count from `t_products` where gid=".$val['gid']." and del_flag=0 limit 1";
                $num_array= self::$__CONN__->fetch_one_array( $sql );

                $company_group[$key]['g_num'] = $num_array['count'];
                $group_flag += $num_array['count'];
            }
        }

        $brr_num = count($company_group);

        for($i = 0; $i < $brr_num; $i++)
        {
            $pagStr = isset(self::$_action) ? "action_". self::$_action ."," : '';
            $pagStr .= "gid_".$company_group[$i]['gid'];
            $company_group[$i]['url_static'] = get_showroom_html_url2(self::$_sid, 'p', $company_group[$i]['gname'], $pagStr);
        }

        $group_g_num = $pro_count - $group_flag;
        if( $group_g_num > 0 ) {
            $group['gid']= '0';
            $group['cid']= self::$_sid;
            $group['gname']= 'Other';
            $group['orderid']='';
            $group['g_num']= $group_g_num;
            $parStr = '';

            if(self::$_action)
                $parStr = "action_". self::$_action .",";

            $parStr .= "gid_0";
            $group['url_static'] = get_showroom_html_url2(self::$_sid, 'p', $group['gname'], $parStr);
            $company_group[$brr_num] = $group;
        }

        if($company_group) {
            ////////   处理没有产品的分组
            foreach ($company_group as $key=>$var) {
                if ($var[g_num]==0) {
                    unset( $company_group[$key] );
                }
            }
        }

        self::$product_group['company_group'] = $company_group;

        return self::$product_group;

    }// end func

    public static function getRelatedLinks()
    {
        if(self::$_action == 'p') {
            $sql = "select obj_name,obj_url from t_mylist where obj_id = '" . self::$_sid . "' and obj_type =3 order by create_time desc limit 5";
            $relatedLinks = self::$__CONN__->fetch_arrays( $sql );
        } else {
            $relatedLinks = fetch_static_array(self::$_sid, 'links');
        }
        
        return $relateLinks;
    }//end func

    //获得当前分类名称
    public static function getCurrentCatName()
    {
        self::$current_cat_name = false;
        if(!isset(self::$product_group))
            self::$product_group = self::getProductGroup();

        if(isset(self::$_ccid)) {
            $count = count(self::$product_group['company_catgory']);
            for($i=0; $i < $count; $i++ ) {
                if(self::$_ccid == self::$product_group['company_catgory'][$i]['cate_code'])
                    self::$current_cat_name = self::$product_group['company_catgory'][$i]['cate_name'];
            }
        } else if(isset(self::$_gid)) {
            $count = count(self::$product_group['company_group']);
            for($i=0; $i < $count; $i++ ) {
                if(self::$_gid == self::$product_group['company_group'][$i]['gid'])
                    self::$current_cat_name = self::$product_group['company_group'][$i]['gname'];
            }
        }
        
        return trim(self::$current_cat_name);
    }//end func

    //获得相关词
    public static function getRelateWord()
    {
        require_once('unify_int/getRelateWord.inc');

        if(!isset(self::$company))
            self::$company = self::getCompanyInfo();
        if(!isset(self::$current_cat_name))
            self::$current_cat_name = self::getCurrentCatName();

        if (self::$current_cat_name) {
            $word = self::$current_cat_name;
        } else if (self::$company['comp_name'] != "") {
            $word = str_ireplace("CO., LTD", "", self::$company['comp_name']);
        }

        if ($word != "") {
            //$word = str_replace("-", " ", $word);
            //$word = trim($word);
            $word = self::dealWords($word);
            $relate_class = new RelateSearch;
            $keyword = $relate_class->Search($word, 0);
            //hot search
            foreach($keyword as $k=>$v) {
                $ten_keyword[$k]['word'] = $v;
                $ten_keyword[$k]['word_rewriteurl'] = url_rewrite( 'search_product', "kw=" . $v  );
            }
        }
        
        return $ten_keyword;
    }//end func

    //获得头部seo信息（只对部分页面） 部分description不同的页面单独处理
    public static function getSeo()
    {
        if(!isset(self::$company))
            self::$company = self::getCompanyInfo();
        
        self::$seo = array();
        $country_name = self::getCountryName();
        //title
        self::$seo['seo_title'] = trim(self::$company['comp_name']);
        if($country_name != '')
            self::$seo['seo_title'] .= ' '.trim($country_name);
        self::$seo['seo_title'] .= ' - Tootoo.com';

        //keywords
        self::$seo['seo_keywords'] = 'Many products, Such as';
        if(!isset(self::$product_group))
            self::$product_group = self::getProductGroup();
        if(is_array(self::$product_group) && count(self::$product_group) > 0) {
            foreach( self::$product_group as $key => $val) {
                if(is_array($val) && count($val) > 0) {
                    if($key == 'company_catgory') {
                        foreach($val as $k => $v) {
                            self::$seo['seo_keywords'] .= ' '. trim($v['cate_name']) .',';
                        }
                    } else if($key == 'company_group'){
                        foreach($val as $k => $v) {
                            self::$seo['seo_keywords'] .= ' '. trim($v['gname']) .',';
                        }
                    }
                }
            }
        }
        self::$seo['seo_keywords'] = rtrim(self::$seo['seo_keywords'], ',');

        //destription
        //截取前50个字符查看公司名是否在里面, 如果在里面description结尾默认公司名去掉
        $descCut = strtolower(substr(self::$company['comp_desc'], 0, 50));
        if(strpos($descCut, substr(strtolower(trim(self::$company['comp_name'])), 0, -9)) !== false)
            self::$seo['seo_des'] = self::w_substr(str_replace(array('&quot;', "'"), '', self::$company['comp_desc']), 200);
        else
            self::$seo['seo_des'] = self::w_substr(str_replace(array('&quot;', "'"), '', self::$company['comp_desc']), 160) . ' ' .trim(self::$company['comp_name']);
            
        return self::$seo;
    }//end func

    //获得静态链接
    public static function getStaticUrl()
    {
        $parStr = isset(self::$_action) ? 'action_'.self::$_action : '';
        if(!isset(self::$company))
            self::$company = self::getCompanyInfo();

        $staticUrl['url_hom']           = get_showroom_html_url2(self::$_sid, 'hom', self::$company['comp_name'], $parStr);
        $staticUrl['url_plist']         = get_showroom_html_url2(self::$_sid, 'p', self::$company['comp_name'], $parStr);
        $staticUrl['url_cp']            = get_showroom_html_url2(self::$_sid, 'cp', self::$company['comp_name'], $parStr);
        $staticUrl['url_tradeinfo']     = get_showroom_html_url2(self::$_sid, 'ti', self::$company['comp_name'], $parStr);
        $staticUrl['url_export_record'] = get_showroom_html_url2(self::$_sid, 'er', self::$company['comp_name'], $parStr);
        $staticUrl['url_news']          = get_showroom_html_url2(self::$_sid, 'n', self::$company['comp_name'], $parStr);
        $staticUrl['url_tqs']           = get_showroom_html_url2(self::$_sid, 'tp', self::$company['comp_name'], $parStr);
        $staticUrl['url_certi']         = get_showroom_html_url2(self::$_sid, 'pc', self::$company['comp_name'], $parStr);
        $staticUrl['url_hon']           = get_showroom_html_url2(self::$_sid, 'hon', self::$company['comp_name'], $parStr);
        $staticUrl['url_comment']       = get_showroom_html_url2(self::$_sid, 'bc', self::$company['comp_name'], $parStr);
        $staticUrl['url_factory']       = get_showroom_html_url2(self::$_sid, 'ft', self::$company['comp_name'], $parStr);
        $staticUrl['url_index']         = get_showroom_html_url2(self::$_sid, 'i', self::$company['comp_name'], $parStr);

        return $staticUrl;
    }//end func

    //检查用户状态
    public function CheckStatus()
    {
        if(!isset(self::$company))
            self::$company = self::getCompanyInfo();
        if(self::$company['check_status'] == 2)
            return true;
        else
            return false;
    }

    //产品结果处理
    private function dealProducts($products)
    {
        if(is_array($products) && count($products) > 0) {
            foreach($products as $key => $val) {
                $products[$key]['prod_name'] = str_replace(array('"', "'"), "", $products[$key]['prod_name']);
                $products[$key]['prod_name_cut'] = strlen(trim($products[$key]['prod_name'])) > 14 ? substr( trim($products[$key]['prod_name']) , 0, 13 ).'...' : trim($products[$key]['prod_name']);
                $temp = ProductPhoto($products[$key]['prod_id'], $products[$key]['photoform'], 's');
                $products[$key]['photo_url'] = $temp[0]['url'];
                $products[$key]['update_time'] = substr($products[$key]['update_time'], 0, 10);
                $parStr = isset(self::$_action) ? 'action_'.self::$_action.',' : '';
                $parStr .= "pid_".$products[$key]['prod_id'];
                $products[$key]['url_static'] = get_showroom_html_url2(self::$_sid, 'ps', $products[$key]['prod_name'], $parStr);
                $products[$key]['extra_prod_name'] = "/".preg_replace("/[^A-Za-z0-9]/", "_", $products[$key]['prod_name']);
                
                //相关词及连接
                $products[$key]['rel_word'] = explode(",", trim($products[$key]['keyword']));
                if(is_array($products[$key]["rel_word"]) && count($products[$key]['rel_word']) > 0) {
                    foreach($products[$key]["rel_word"] as $k=>$v) {
                        $products[$key]["related_word"][$k]['word'] = trim($v);
                        $products[$key]["related_word"][$k]['url'] = "/buy-".str_replace(' ', '_', strtolower(trim($v)))."/";
                    }
                    $products[$key]["related_word"] = array_slice($products[$key]["related_word"], 0, 5);
                }
                
                //描述
                $products[$key]['prod_desc'] = self::w_substr(self::dealDesc($products[$key]['prod_desc']), 250)."...";
                
                //产品询盘
                $subarr = array(
                            "src"=>1,
                            "pid"=>$products[$key]['prod_id'],
                            "pname"=>$products[$key]['prod_name'],
                            "pcode"=>$products[$key]['cat_id'],
                            "pimg"=>$products[$key]['photo_url'],
                            "purl"=>$products[$key]['url_static'],
                            "cid"=>self::$company['comp_id'],
                            "cname"=>self::$company['comp_name'],
                            "country"=>self::getCountryName(),
                            "desc"=>substr($products[$key]['prod_desc'], 0, 47)."...");
                $products[$key]['mylist'] = serialize_for_post( $subarr );
            }
        }
        return $products;
    }

    //获得推荐产品
    public static function getRecommendProducts()
    {
        if(!isset(self::$company))
            self::$company = self::getCompanyInfo();
        
        $recommend = array();
        $sql = "select * from t_products where comp_id='". self::$_sid ."' and del_flag=0";
        $sql.=" order by recommend_time desc limit 8 ";
        $recommend = self::$__CONN__->fetch_arrays($sql, MYSQL_ASSOC );
        return self::dealProducts($recommend);
    }

    //获得公司询盘
    public static function getCompanyInquire()
    {
        if(!isset(self::$company))
            self::getCompanyInfo();
        $comp_url = get_showroom_html_url2(self::$company['comp_id'], 'hom', self::$company['comp_name']);
        $mylistStr = serialize_for_post(array(
                                        'src'      => 1,
                                        'pid'      => '',
                                        'pname'    => '',
                                        'pcode'    => '',
                                        'purl'     => '',
                                        'pimg'     => '',
                                        'cid'      => self::$company['comp_id'],
                                        'cname'    => self::$company['comp_name'],
                                        'country'  => self::getCountryName(),
                                        'desc'     => '',
                                        'comp_url' => $comp_url));
        return $mylistStr;
    }

    //获得产品列表
    public static function getProductList($products)
    {
        global $seo;
        $seo['seo_des'] = '';
        if(is_array($products) && count($products) > 0) {
            $products = self::dealProducts($products);

            $i = 0;
            foreach($products as $key => $value) {
                $query_str = "select * from t_pro_specification where PID = ".$value['prod_id']." and SPC_NAME != '' limit 3;";
                $pro_specification = self::$__CONN__->fetch_arrays($query_str);
                $products[$key]['pro'] = $pro_specification;

                //seo_des
                if($i < 10)
                    $seo['seo_des'] .= $value['prod_name'].', ';
                $i++;
            }
            $seo['seo_des'] .= self::$company['comp_name'];
            return $products;
        }
    }//end func

    //获得数据数组
    public static function GetListMsg($company_id, $condition, $compositor, $datanum, $pagenum)
    {
        global $DB, $page, $ccid, $gid;

        $sql = "select * from t_products left join t_attachments on t_products.photo_id = t_attachments.a_id where comp_id=$company_id and del_flag=0  ";

        if($ccid && $ccid != "All_Products")
            $sql .= " and cat_id = ".$ccid." ";
        if($gid && $gid!='0')
            $sql .= " and gid = ".$gid." ";
        if($gid == '0')
            $sql .= " and gid = 0 ";
        if($kw)
            $sql .= " and(prod_name like '%".$kw."%' or prod_desc like '%".$kw."%')";

        $sql .= " order by $compositor desc";

        $sql_count = "select count(*) from t_products where comp_id = $company_id and del_flag = 0 ";
        if($ccid && $ccid != "All_Products")
            $sql_count .= " and cat_id = '" . $ccid . "' ";
        if($gid)
            $sql_count .= " and gid = ".$gid." ";
        if($kw)
            $sql_count .= " and (prod_name like '%".$kw."%' or prod_desc like '%".$kw."%') ";

        if($debug == 1) {
            echo "sql:<br>";
            print_r($sql);
            echo "sql_count:<br>";
            print_r($sql_count);
        }

        $page_obj = new page();
        $array = $page_obj->list_info($sql_count,$sql,$datanum,$page,'');
        $arr_page = $page_obj->sect_page_type($pagenum);
        $array['extend'] = $arr_page;
        return $array;
    }

    //字符串截取 按英文单词截取
    public function noBreakWord($string, $max) {
        $testChar = substr($string, $max, 1);
        if ($testChar == " ") {
            return substr($string, 0, $max);
        } else {
            while ($testChar<>" ") {
                $testChar = substr($string, $max, 1);
                if ($testChar == " ") {
                    return substr($string, 0, $max);
                } else {
                    $max = $max-1;
                }
            }
        }
    }

    function w_substr( $str , $slen)
    {
            $str = del_html( $str );
            $str_len = strlen( $str );
            if ( $str_len < $startdd + 1 ) return "";
            $strw_arr = str_word_count( $str , 2 );
            $abreak = false;
            $startdd = 0;
            if ( $strw_arr )
            {
                    foreach( $strw_arr as $key => $var )
                    {
                            if ( $key > ( $startdd + $slen ) )
                            {
                                    break;
                            }
                            $newend = $key;
                    }
                    if ( !$newend ) $newend = $str_len;
            }
            if ( ( $startdd + $slen ) >= $str_len ) $newend = $str_len;
            return substr( $str , $startdd , ( $newend - $startdd ) );
    }


    //制作随机数
    public function getRandomNum()
    {
        $ot = NULL;
        for($j = 0;$j <= 5;$j++)        //随机数字的长度，本例随机数长度为6
        {
          srand((double)microtime()*1000000);
          $randname = rand(!$j ? 1: 0,9);    //产生随机数，不以0为第一个数，有些特殊的地方0开头被系统省略
          $ot .= $randname;
        }
        return $ot;
    }

    //关键词处理
    public function dealWords($str)
    {
        preg_match_all ("/([a-z0-9]+)/im",$str, $out, PREG_SET_ORDER);
        foreach ($out as $k=>$v)
        {
            $searchword .= $v[0]." ";
        }
        $searchword = trim($searchword);
        $searchword = strtolower($searchword);
        return $searchword;
    }

    //调试
    function debug($var)
    {
        echo '<pre style="text-align:left;">';
        var_dump($var);
        echo '</pre>';
        echo '<hr />';
    }
    //----------------------------------------------------
    
}

?>
