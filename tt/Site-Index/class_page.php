<?php

/**
 * 波段分页类
 *
 * @update:2010-4-1
 *
 */

class page {

    public $Page = 1;       //当前页
    public $Count = 0;      //总数
    public $PerDiv = 6;    //波段
    public $PerDiv2 = 4;    //波段用于getDiv， getDiv2波段数量显示
    public $PerPage = 20;   //每页显示条数
    public $Url ='';  //传递条件
    public $Condition ='';  //传递条件

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
        $prevArea = ($areaPage - 2) * $area + 1;
        $nextArea = $areaPage * $area + 1;
        $startPage = ($areaPage - 1) * $area + 1;
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        //$startPage = ($areaPage - 1) * $area + 1;

        $html_page = '';

        if($this->Count)
            $html_page .= "<span>Page:</span>";

        //前一页
        if($prevPage > 0)
            $html_page .= "<a href='{$this->Url}$prevPage{$this->Condition}'>Previous</a> ";
        //前一波段
        if($prevArea > 0){
            $html_page .= "<a href='{$this->Url}1{$this->Condition}'>1</a> ";
            $html_page .= "<a href='{$this->Url}$prevArea{$this->Condition}'>...</a> ";
        }

        for($i = $startPage; $i < $startPage + $area; $i++)
        {
            if($i > $maxPage)
                break;
            if($i < 1)
                break;
            if($i == $this->Page)
                $html_page .= "<b>{$i}</b> ";
            else
                $html_page .= "<a href='{$this->Url}$i{$this->Condition}'>{$i}</a> ";
        }

        //下一波段
        if($nextArea <= $maxPage)
            $html_page .= "<a href='{$this->Url}$nextArea{$this->Condition}'>...</a> ";

        //最后一页
        if($startPage + $area > 4 && $nextArea <= $maxPage)
            $html_page .= "<a href='{$this->Url}$maxPage{$this->Condition}'>$maxPage</a> ";

        //下一页
        if($nextPage <= $maxPage)
            $html_page .= "<a href='{$this->Url}$nextPage{$this->Condition}'>Next</a> ";

        return $html_page;
    }//end func


    /**
     *  获得波段分页html
     *
     */
    public function getDiv($type=1)
    {
        $page = $this->Page;
        $maxPage = ceil($this->Count / $this->PerPage);
        if($page > $maxPage)
            $page = $maxPage;
            $area = (int)$this->PerDiv;
            if($area < 4)
                $area = 4;


            $areaPage = ceil($page / ( $area *  $this->PerDiv2));    //当前页两侧波段
            $prevArea = ($areaPage - 2) * $area * $this->PerDiv2 + 1;
            $nextArea = $areaPage * $area * $this->PerDiv2 + 1;
            $startPage = ($areaPage - 1) * $this->PerDiv2 + 1;

            $html_page = '';

            //if($this->Count)
                //$html_page .= "<span>Division:</span>";

            //前一波段
            if($prevArea > 0){
                $html_page .= "<a href='{$this->Url}$prevArea{$this->Condition}'>&lt;&lt;</a> ";
            }

            for($i = $startPage; $i < $startPage + $this->PerDiv2; $i++)
            {
                if($i > $maxPage)
                    break;

                if($i < 1)
                    break;


                $start = ($i - 1) * $area + 1;
                $end = $i * $area;

                if($end > $maxPage)
                    $end = $maxPage;

                if($end < $area )
                     break;

                if($start <= $page && $page <=$end) {
                    if($start != $end)
                        $html_page .= "<b>".$start.'-'.$end."</b> ";
                    else
                        $html_page .= "<b>".$start."</b> ";
                }else{
                    if($start != $end)
                        $html_page .= "<a href='{$this->Url}$start{$this->Condition}'>".$start.'-'.$end."</a> ";
                    else
                        $html_page .= "<a href='{$this->Url}$start{$this->Condition}'>".$start."</a> ";
                }
                if($end == $maxPage)
                    break;
            }

            //下一波段
            if($nextArea <= $maxPage)
                $html_page .= "<a href='{$this->Url}$nextArea{$this->Condition}'>&gt;&gt;</a> ";

            return $html_page;
    }//func





    /**
     *  获得波段分页html
     *
     */
    public function getDiv2()
    {
        $page = $this->Page;
        $maxPage = ceil($this->Count / $this->PerPage);
        if($page > $maxPage)
            $page = $maxPage;

        $area = (int)$this->PerDiv;
        if($area < 4)
            $area = 4;

        $areaPage = ceil($page / ( $area *  $this->PerDiv2*  $this->PerDiv2));    //当前页两侧波段
        $prevArea = ($areaPage - 2) * $area * $this->PerDiv2 * $this->PerDiv2 + 1;
        $nextArea = $areaPage * $area * $this->PerDiv2 * $this->PerDiv2 + 1;
        $startPage = ($areaPage - 1) * $this->PerDiv2 + 1;



        $html_page = '';

        //for($i = $startPage; $i < $startPage + $this->PerDiv2; $i++)
        for($i = 1; $i < $maxPage; $i++) {
            $start = ($i-1) * $area * $this->PerDiv2 + 1;
            $end = $start + $area * $this->PerDiv2 - 1;

            if($end > $maxPage)
                $end = $maxPage;

            if($end < $area * $this->PerDiv2)
                 break;

            if($start <= $page && $page <=$end) {
                if($start != $end)
                    $html_page .= "<b>".$start.'-'.$end."</b> ";
                else
                    $html_page .= "<b>".$start."</b> ";
            }else {
                if($start != $end)
                    $html_page .= "<a href='{$this->Url}$start{$this->Condition}'>".$start.'-'.$end."</a> ";
                else
                    $html_page .= "<a href='{$this->Url}$start{$this->Condition}'>".$start."</a> ";
            }
            if($end == $maxPage)
                break;

        }

        return $html_page;
    }//func

}