<?
define("_SPLIT_INCLUDE_" , "SPLIT PAGE INCLUDE OK");
class splitpage{

    var $page = "";
    var $msg_per_page;
    var $page_row;
    var $page_scale;
    var $page_total;
    var $start_page;
    var $cur_page;
    var $total_rows = 0;
    var $method;

    function start($msg = 20,$scale = 10,$method = "get"){

        global $cur_page;

        $this->msg_per_page = $msg;
        $this->page_row     = $msg;
        $this->page_scale   = $scale;
        $this->method       = $method;
        if(!$cur_page && $_REQUEST["cur_page"]){
            $cur_page = $_REQUEST["cur_page"];
        }
        if(!$this->cur_page){
            if(!$cur_page) $cur_page = 1;
            $this->cur_page = $cur_page;
        }
        $this->start_page = ($this->cur_page - 1) * $this->msg_per_page;
    }
    function pagenum()
    {
        if($this->total_rows != 0){
            $page_total = (int)($this->total_rows / $this->msg_per_page) + 1;
            $mod = $this->total_rows % $this->msg_per_page;
            if($mod == 0){
              $page_total = $page_total - 1;
            }
        } else {
            $page_total = 0;
            $this->cur_page = 0;
        }

         $this->page_total = $page_total;
     }
     function page($total_rows)
     {
         return $this->navpage($total_rows);
     }

     function navpage($total_rows, $text_color="#000000", $first_text="처음", $last_text="마지막")
     {
         global $SCRIPT_NAME;

         $this->total_rows = $total_rows;
         $this->pagenum($this->total_rows);
         $get_uri = $this->returnurl();

         if($this->total_rows > 0){
              $page_start = (int)($this->cur_page / $this->page_scale) * $this->page_scale + 1;
              if(($this->cur_page % $this->page_scale)==0)$page_start -= $this->page_scale;
              $end_page = $page_start + $this->page_scale;

              if($page_start > $this->page_scale){
                  $send_page = $page_start - 1;
                  $page .= "<a href=\"$SCRIPT_NAME?$get_uri&cur_page=$send_page\"><img src=\"" . _LIB_URL_ . "/images/prevpage.gif\" width=\"16\" height=\"10\" border=\"0\" alt=\"Prev\" align=\"absmiddle\"></a>&nbsp;";
              }

              for($i = $page_start; ($i < $end_page) && ($i <= $this->page_total); $i++){
                  if($i == $this->cur_page){
                       $page .= "&nbsp;<font color='red'  face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">$i</font>&nbsp;";
                  }else{
                       $page .= "[<a href='$SCRIPT_NAME?$get_uri"."&cur_page=$i'><font color='$text_color' face=\"Verdana, Arial, Helvetica, sans-serif\" style='font-size:8pt'>$i</font></a>]";
                  }
              }

              if($end_page <= $this->page_total){
                  $page .= "&nbsp;<a href=\"$SCRIPT_NAME?$get_uri"."&cur_page=$end_page\"><img src=\"". _LIB_URL_ ."/images/nextpage.gif\" width=\"16\" height=\"10\" border=\"0\" alt=\"Next\" align=\"absmiddle\"></a>";
              }
         }

         if($page == ""){
              $page = "&nbsp;";
         }else{
              $page = "<font style='font-size:8pt'><a href='$SCRIPT_NAME?$get_uri"."&cur_page=1'><font color='$text_color'>$first_text..</font></a></font>&nbsp;".$page."&nbsp;<font style='font-size:8pt'><a href='$SCRIPT_NAME?$get_uri"."&cur_page=".$this->page_total."'><font color='$text_color'>..$last_text</font></a></font>";
          }

         return $page;
     }

     function returnurl()
     {
         global $HTTP_GET_VARS;
         global $HTTP_POST_VARS;

         if($this->method == "get"){
              RESET($HTTP_GET_VARS);
              $vars = $HTTP_GET_VARS;
         } else {
              RESET($HTTP_GET_VARS);
              RESET($HTTP_POST_VARS);
              $vars = array_merge($HTTP_POST_VARS,$HTTP_GET_VARS);
         }

         $gn = 1;
         $gl = count($vars);

         while(list($key,$val) = each($vars)){
              if($key == "cur_page"){
                  $gn++;
                  CONTINUE;
              }
              $val = urlencode($val);
              $get_uri .= $key."=".$val;
              if($gn < $gl){
                  $get_uri .= "&";
              }

              $gn++;

         }
         return $get_uri;
     }

}//class End

//$navPage = new splitPage;
?>
