<?php

//常用分页

class Page {

    public $Page = 1;       //当前页
    public $Count = 0;      //总数
    public $PerDiv = 10;    //波段
    public $PerPage = 20;   //每页显示条数
    public $Condition ='';  //传递条件
    public $url = '';

    //数据库查询起始处
    public function getBegin() {
        if($this->Page < 1)
            $this->Page = 1;
        return $this->PerPage * ($this->Page - 1);
    }

    //获得分页html
    public function getPage() {
        $page = (int)$this->Page;
        if(!$page)
            $page = 1;

        if($page < 1)
            $page = 1;

        $maxPage = ceil($this->Count / $this->PerPage); //最大页数

        if($page > $maxPage)
            $page = $maxPage;

        $area = (int)$this->PerDiv;
        if($area < 4)
            $area = 4;

        $areaPage = ceil($page / $area);    //当前页两侧波段
        $prevPage = ($areaPage - 2) * $area + 1;
        $nextPage = $areaPage * $area + 1;
        $startPage = ($areaPage - 1) * $area + 1;

        $html_page = '';
        $http = $this->url;    //分页url

        if($page == 1)
            $html_page = '';

        $html_page .= "Page of $maxPage: ";

        //前一页
        if($prevPage > 0)

            $html_page .= "<a href='{$http}$prevPage/'>Prev</a> ";

        for($i = $startPage; $i < $startPage + $area; $i++)
        {
            if($i > $maxPage)
                break;
            if($i < 1)
                break;
            if($i == $this->Page)
                $html_page .= "<b>{$i}</b> ";
            else
                $html_page .= "<a href='{$http}$i/'>{$i}</a> ";
        }

        //后一页
        if($nextPage <= $maxPage)
            $html_page .= "<a href='{$http}$nextPage/'>Next</a> ";

        return $html_page;
    }// end fucn

}//end