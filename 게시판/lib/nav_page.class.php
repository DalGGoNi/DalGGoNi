<?php
define("_NAVPAGE_INCLUDE_" , "NAV. PAGE INCLUDE OK");
class NavPage
{
    /** @readonly */
    public int $page_scale;
    /** @readonly */
    public int $row_scale;
    
    public $start_row;
    public $total_row = 0;
    public $total_page;
    
    public $curr_page;
    public $method;
    
    public function __construct($row_scale = 20,$page_scale = 10,$method = "get") {
        
        $this->start($row_scale, $page_scale, $method);
    }
    public function start($row_scale = 20,$page_scale = 10,$method = "get"){
        
        $this->row_scale    = $row_scale;
        $this->page_scale   = $page_scale;
        $this->method       = $method;
        
        //global $cur_page;
        /*
         $this->row_scale    = $row_scale;
         $this->page_scale   = $page_scale;
         $this->method       = $method;
         */
        if(isset($_REQUEST["cur_page"]) == true && $_REQUEST["cur_page"]){
            $this->curr_page = $_REQUEST["cur_page"]??1;
        }
        if(!$this->curr_page){
            $this->curr_page = 1;
        }
        $this->start_row = ($this->curr_page - 1) * $this->row_scale;
    }
    private function pagenum()
    {
        if($this->total_row != 0){
            $page_total = (int)($this->total_row / $this->row_scale) + 1;
            $mod = $this->total_row % $this->row_scale;
            if($mod == 0){
                $page_total = $page_total - 1;
            }
        } else {
            $page_total = 0;
            $this->curr_page = 0;
        }
        
        $this->total_page = $page_total;
    }
    public function page($total_rows, $text_color="#000000", $first_text="처음", $last_text="마지막")
    {
        return $this->navpage($total_rows, $text_color, $first_text, $last_text);
    }
    
    public function navpage($total_rows, $text_color="#000000", $first_text="처음", $last_text="마지막")
    {
        global $_SERVER;
        $_script_name = $_SERVER["SCRIPT_NAME"];
        
        $this->total_row = $total_rows;
        $this->pagenum($this->total_row);
        
        $split_page_bar = null;
        
        $get_uri = $this->returnurl();
        
        if($this->total_row > 0){
            $page_start = (int)($this->curr_page / $this->page_scale) * $this->page_scale + 1;
            if(($this->curr_page % $this->page_scale)==0)$page_start -= $this->page_scale;
            $end_page = $page_start + $this->page_scale;
            
            if($page_start > $this->page_scale){
                $send_page = $page_start - 1;
                $split_page_bar .= "<a href=\"$_script_name?$get_uri&cur_page=$send_page\"><img src=\"" . _LIB_URL_ . "/images/prevpage.gif\" width=\"16\" height=\"10\" border=\"0\" alt=\"Prev\" align=\"absmiddle\" style=\"text-align: center;vertical-align:middle;\" /></a>&nbsp;";
            }
            
            for($i = $page_start; ($i < $end_page) && ($i <= $this->total_page); $i++){
                if($i == $this->curr_page){
                    $split_page_bar .= "&nbsp;<button type='button' class='btn btn btn-dark'>$i</button>&nbsp;";
                }else{
                    $split_page_bar .= "<a href='$_script_name?$get_uri"."&cur_page=$i'><button type='button' class='btn btn-secondary'>$i</button></a>";
                }
            }
            
            if($end_page <= $this->total_page){
                $split_page_bar .= "&nbsp;<a href=\"$_script_name?$get_uri"."&cur_page=$end_page\"><img src=\"". _LIB_URL_ ."/images/nextpage.gif\" width=\"16\" height=\"10\" border=\"0\" alt=\"Next\" align=\"absmiddle\" style=\"text-align: center;justify-content:center;align-items:center;vertical-align:middle;\" /></a>";
            }
        }
        
        if($split_page_bar == ""){
            $split_page_bar = "&nbsp;";
        }else{
            $split_page_bar = "<button type='button' class='btn btn-secondary' onclick= location.href='$_script_name?$get_uri"."&cur_page=1'>$first_text..</button>&nbsp;".$split_page_bar."&nbsp;<button type='button' class='btn btn-secondary' onclick= location.href='$_script_name?$get_uri"."&cur_page=".$this->total_page."'>..$last_text</button>";
        }
        
        $Result = "<div style='justify-content:center; align-items: center;vertical-align:middle;'>{$split_page_bar}</div>";
        return $Result;
    }
    
    public function returnurl()
    {
        global $_GET;
        global $_POST;
        $get_uri = null;
        
        if(strtolower($this->method) == "get"){
            //RESET($HTTP_GET_VARS);
            RESET($_GET);
            $vars = $_GET;
        } else {
            RESET($_GET);
            RESET($_POST);
            $vars = array_merge($_GET,$_POST);
        }
        
        $gn = 1;
        $gl = count($vars);
        if($gl > 0){
            foreach ($vars as $key => $val)
            {
                if($key == "cur_page"){
                    $gn++;
                    continue;
                }
                $val = urlencode($val);
                $get_uri .= $key."=".$val;
                if($gn < $gl){
                    $get_uri .= "&";
                }
                $gn++;
            }
        }
        return $get_uri;
    }
    
}//class End

//$navPage = new splitPage;
?>
