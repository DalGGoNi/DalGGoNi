<?php
class Fun
{
    private static $bInitialized  = false;
    public static float $PHP_VER = 0.0;
    //public float $php_ver = 0.0;
    public static ?self $instance = null;
        
    /**
     * PHP5용 Class 생성자
     */
    private function __construct()
    {
        $this->Fun();
    }

    /**
     * PHP4용 Class 생성자
     */
    private function Fun()
    {
        if (!self::$bInitialized) {
            self::init();
        }
       //$this->php_ver = self::$PHP_VER;
    }

    public static function Create() : ?self
    {
        if ( isset( self::$instance ) == true || is_null( self::$instance ) == true )
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public static function callStatic() {
        if (!self::$bInitialized) {
                self::init();
        }
    }

    public static function init()
    {
        self::$bInitialized = true; 
        //is_callable($class, '__init__')
        $_php_ver = (float)PHP_VERSION;
        if (!defined("_PHP_VER_"))
        {
            if ($_php_ver >= 8.1)
            {
                define("_PHP_VER_", 0x8100);
            }
            else if ($_php_ver >= 8.0)
            {
                define("_PHP_VER_", 0x8000);
            }
            else if ($_php_ver >= 7.4)
            {
                define("_PHP_VER_", 0x7400);
            }
            else if ($_php_ver >= 7.1)
            {
                define("_PHP_VER_", 0x7100);
            }
            else if ($_php_ver >= 7.0)
            {
                define("_PHP_VER_", 0x7000);
            }
            else if ($_php_ver >= 5.2)
            {
                define("_PHP_VER_", 0x5200);
            }
            else if ($_php_ver >= 5.0)
            {
                define("_PHP_VER_", 0x5000);
            }
            else
            {
                die("PHP5 or later required. You are running " . PHP_VERSION);
            }
        }
        self::$PHP_VER = $_php_ver;
    }


    /**
     * PHP함수 print_r에 <pre></pre>붙여서 보기 좋게 출력
     * @Param &$obj {Object}
     */
    public static function print_r($obj){
        echo "<pre style='text-align:left'>";
        print_r($obj);
        echo "</pre>";
    }
    /**
     * PHP함수 var_dump에 <pre></pre>붙여서 보기 좋게출력
     * @Param &$obj {Object}
     */
    public static function var_dump($obj){
        echo "<pre style='text-align:left'>";
        var_dump($obj);
        echo "</pre>";
    }
    /**
     * PHP함수 echo에 <pre></pre>붙여서 보기 좋게출력
     * @Param $str {String}
     */
    public static function print_($str){
        echo "<pre style='text-align:left'>";
        echo $str;
        echo "</pre>";
    }
    /**
     * 현재 페이지 URL을 돌려줌
     * @Param string $method="GET" {String}
     * @Return $page_url {String}
     */
    public static function url(string $method = "GET"){
        if($method == "GET" && $_SERVER["QUERY_STRING"]){
            $str_query = "";
            foreach($_GET AS $key => $val){
                if($key != "logout"){
                    if(!$str_query){
                        $str_query = "?" . $key . "=" . $val;
                    } else {
                        $str_query .= "&" . $key . "=" . $val;
                    }
                }
            }
            $page_url = $_SERVER["SCRIPT_NAME"] . $str_query;
        } else {
            $page_url = $_SERVER["SCRIPT_NAME"];
        }
        return $page_url;
    }
    /**
     * 현재 페이지 URL(URI)를 지정한 값 제외 후 돌려줌
     * @Param string $expKey="logout" {String}
     * @Return $page_url {String}
     */
    public static function getExpUrl(string $expKey = "logout", string $putVal = null): string
    {
        $Result = null;
        if($putVal){
            $str_query = "?" . $putVal;
        } else {
            $str_query = "";
        }
        if(!$_SERVER["QUERY_STRING"]){
            $Result = $_SERVER["REQUEST_URI"] . $str_query;
        } else {
            $expKey = str_replace(",","|",$expKey);
            $matches = null;
            $pattern = "/^({$expKey})$/";
            foreach($_GET AS $_key => $_val){
                preg_match($pattern, $_key, $matches);
                if($_key != "logout" && !$matches && $_val != ""){ //!eregi(pattern, $_key)
                    $_val = urlencode($_val);
                    if(!$str_query){
                        $str_query = "?" . $_key . "=" . $_val;
                    } else {
                        $str_query .= "&" . $_key . "=" . $_val;
                    }
                    //echo $expKey . "<br />";
                    //echo $str_query . "<br />";
                }
            }
            $Result = $_SERVER["SCRIPT_NAME"] . $str_query;
        }

        $matches = null;
        $pattern = "/\?/";
        preg_match($pattern, $Result, $matches);
        if(isset($Result) && !$matches){ //!eregi("\?", $page_url
            $Result .= "?";
        }
        return $Result;
    }
    
    
    public static function getParamUrl(string $expKey = "logout", string $putVal = null): string
    {
        $Result = null;
        if($putVal){
            $str_query = "?" . $putVal;
        } else {
            $str_query = "";
        }
        if(!$_SERVER["QUERY_STRING"]){
            $Result = $str_query;
        } else {
            $expKey = str_replace(",","|",$expKey);
            $matches = null;
            $pattern = "/^({$expKey})$/";
            foreach($_GET AS $_key => $_val){
                preg_match($pattern, $_key, $matches);
                if($_key != "logout" && !$matches && $_val != ""){ //!eregi(pattern, $_key)
                    $_val = urlencode($_val);
                    $str_query .= "&" . $_key . "=" . $_val;
                    /*
                    if(!$str_query){
                        $str_query = $_key . "=" . $_val;
                    } else {
                        $str_query .= "&" . $_key . "=" . $_val;
                    }
                     */
                    //echo $expKey . "<br />";
                    //echo $str_query . "<br />";
                }
            }
            $Result = $str_query;
        }
        
        /*
        $matches = null;
        $pattern = "/^(\?)/";
        preg_match($pattern, $Result, $matches);
        if(isset($Result) && !$matches){ //!eregi("\?", $page_url
            $Result = "?" . $Result;
        }
        */
        return $Result;
    }
    
    /* BEGIN 메세지 / 이동 함수들 */
    /**
     * javascript 메세지 박스 alert 기능($method="exit 옵션시 종료)
     * @Param $msg = "" {String} : 보여줄 메세지
     * @Param $method = "" {String} : 옵션
     */
    public static function msg($msg = "", $method = ""){
        echo "<script lanugag='javascript' type='text/javascript'>alert(\"" . $msg . "\");</script>";
        if($method == "exit"){
            exit;
        }
    }
    /**
     * javascript 메세지 뿌린후 자기 자신창 닫기(self.opener=3 : IE버그...향후 수정될 소지 다분함)
     * @Call msg()
     * @Param $msg {String}
     */
    public static function self_close($msg=""){
        if($msg) self::msg($msg);
        echo "<script lanugag='javascript' type='text/javascript'>self.opener=3;self.close();</script>";
        exit;
    }
    /**
     * javascript 메세지 뿌린후 부모창 새로고침후 자기 자신창 닫기(self.opener=3 : IE버그...향후 수정될 소지 다분함)
     * @Call msg()
     * @Param $msg {String}
     */
    public static function popup_close($msg=""){
        if($msg) self::msg($msg);
        echo "<script lanugag='javascript' type='text/javascript'>opener.history.go(0);self.opener=3;self.close();</script>";
        exit;
    }
    /**
     * javascript 뒤로 이동
     */
    public static function goBack(){
        echo "<script lanugag='javascript' type='text/javascript'>history.back();</script>";
        exit;
    }
    /**
     * javascript/meta(html)/php 지정된 URL로 이동
     * @Param $location {String} : 이동할 URL
     * @Param $method {String} : 이동할 방법(기본:javascript)
     */
    public static function goUrl($location = "", $method = "javascript"){
        switch($method){
            case "meta":
                echo "<META http-equiv=\"Refresh\" content=\"0; URL=" . $location . "\">";
                break;
            case "javascript":
                echo "<script lanugag='javascript' type='text/javascript'>location.href=\"" . $location . "\";</script>";
                break;
            case "php":
                @Header("Location: ".$location);
                break;
            default:
                self::goBack();
                break;
        }
        exit;
    }
    /**
     * javascript 메세지 뿌린후 URL로 이동
     * @Param $msg {String} : 출력할 메세지
     * @Param $location : 이동할 URL
     * @Param $method : 이동할 방법(javascript(기본) / meta / php)
     */
    public static function alert($msg = "", $location = "", $method = "javascript"){
        //if($msg){
        self::msg($msg);
        //}
        //exit;
        if($location != "" && $location != "self"){
            self::goUrl($location, $method);
        } else if($location != "self" && $location != "blank") {
            self::goBack();
        }
        if($location != "self")
        {
            exit;
        }
    }
    /**
     * 지정된 URL로 이동
     * @Call goUrl()
     * @Param $location {String} : 이동할 URL
     * @Param $method {String} : 이동할 방법(기본:javascript)
     */
    public static function goPage($location = "", $method = "javascript"){
        self::goUrl($location, $method);
    }
    /**
     * HTML 메세지 박스 만들기
     * @Param $title {String} : 메세지박스 타이틀
     * @Param $memo {String} : 메세지 내용
     * @Param $print {Boolean} : 메세지 박스 만들고 바로 화면에 출력여부(기본값 : false)
     * @Return $str {String} : 만들어진 메세지 박스 HTML
     */
    public static function Msg_Box($title, $memo, $print = false){
        $str  = "<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
        $str .= "  <tr><td bgcolor=\"#808000\"><strong>" . $title . "</strong></td></tr>\n";
        $str .= "  <tr><td bgcolor=\"#ffffff\">" . $memo . "</td></tr>";
        $str .= "</table>";
        if($print === true){
            echo $str;
        }
        return $str;
    }
    /* END 메세지 / 이동 함수들 */
    /* BEGIN 문자열 처리 */
    /**
     * 배열값들중 숫자에 대해서 number_format
     * @Call trimAll() : 재귀호출(자기자신 호출)
     * @Param &$array {array}
     * @Return $array {array} : &변수이므로 그값을 그대로 가짐(C의 포인터 참조)
     */
    public static function number_formatAll(&$array) {
        if( is_array($array) ) {
            // class 일경우 array_walk ($array, array($this, 'trimAll'));
            //array_walk ($array, array(self, 'number_formatAll'));
            array_walk ($array, 'self::number_formatAll');
        } else {
            if(is_numeric($array)){
                if(!$array){
                    $array = "";
                } else {
                    //echo $array;
                    $array = number_format($array);
                }
            }
        }
    }
    /**
     * 배열값들의 앞뒤 공백 없애기
     * @Call trimAll() : 재귀호출(자기자신 호출)
     * @Param &$array {array}
     * @Return $array {array} : &변수이므로 그값을 그대로 가짐(C의 포인터 참조)
     */
    public static function trimAll(&$array) {
        if( is_array($array) ) {
            // class 일경우 array_walk ($array, array($this, 'trimAll'));
            //array_walk ($array, array($this, 'trimAll'));
            array_walk ($array, 'self::trimAll');
        } else {
            $array = trim($array);
        }
    }
    //배열값들에 addslashes
    public static function addslashesAll(&$array) {
        if (function_exists('get_magic_quotes_gpc') != true){
            if(is_array($array)) {
                //array_walk ($array, array($this, 'addslashesAll'));
                array_walk ($array, 'self::addslashesAll');
            } else {
                $array = addslashes($array);
            }
        }
    }
    //배열값들에 stripslashes
    public static function stripslashesAll(&$array) {
        if(is_array($array)) {
            //array_walk ($array, array($this, 'stripslashesAll'));
            array_walk ($array, 'self::stripslashesAll');
        } else {
            $array = stripslashes($array);
        }
    }
    //EUC-KR(한글 코드페이지[949])을 UTF-8로
    public static function iconv_utf8All(&$array){
        //$slang = "CP949";
        if( is_array($array) ) {
            // class 일경우 array_walk ($array, array($this, 'trimAll'));
            //array_walk ($array, array($this, 'iconv_utf8All'));
            array_walk ($array, 'self::iconv_utf8All');
        } else {
            //$array = iconv($slang,$tlang,$array);
            $str = $array;
            //$encoding = mb_detect_encoding($str, mb_detect_order(), false);
            if (@iconv("UTF-8","UTF-8//TRANSLIT",$str) == $array) {
                $array = $str;
                //exit;
            } else {
                //$encoding = mb_detect_encoding($str, mb_detect_order(), false);
                $array = @iconv("CP949","UTF-8//TRANSLIT",$str);
            }
        }
    }
    //UTF-8을 EUC-KR(한글 코드페이지[949])로
    public static function iconv_CP949All(&$array){
        if( is_array($array) ) {
            // class 일경우 array_walk ($array, array($this, 'trimAll'));
            //array_walk ($array, array($this, 'iconv_CP949All'));
            array_walk ($array, 'self::iconv_CP949All');
        } else {
            //$array = iconv($slang,$tlang,$array);
            if (iconv("CP949","CP949",$array) == $array) {
                $array = $array;
            } else {
                $array = iconv("UTF-8","CP949",$array);
            }

        }
    }
    //Array형태의 str_replace (ADODB의 adodb.inc -> ADODB_str_replace)
    public static function arry_str_replace($src, $dest, $data){
        if (PHP_VER >= 0x4050) return str_replace($src,$dest,$data);
        $s = reset($src);
        $d = reset($dest);
        while ($s !== false) {
            $data = str_replace($s,$d,$data);
            $s = next($src);
            $d = next($dest);
        }
        return $data;
    }
    //입력된 값을 DB에 넣기 위해 충돌성 있는것들을 변환
    public static function convVal2DB($str){
        //$str = str_replace ( '\'', '\'\'', $str); //Oracle용
        $str = str_replace ( "&", "&amp;", $str );
        $str = str_replace ( "\'", "&#039;", $str );
        $str = str_replace ( "'", "&#039;", $str );
        $str = str_replace ( '\"', "&quot;", $str );
        $str = str_replace ( '"', "&quot;", $str );
        $str = str_replace ( "<", "&lt;", $str );
        $str = str_replace ( ">", "&gt;", $str );
        return $str;
    }
    //입력된 배열의 값들을 DB에 넣기 위해 충돌성 있는것들을 변환
    public static function convVal2DBAll(&$array){
        if(is_array($array)) {
            //array_walk ($array, array($this, 'convVal2DBAll'));
            array_walk ($array, array(self, 'convVal2DBAll'));
        } else {
            $array = self::convVal2DB($array);
        }
    }
    //입력된 DB의 값을 Object(Input, Textarea)에 쓰기 위해서 다시 원상복귀
    public static function convDB2Val($str){
        if(isset($str) && $str)
        {
            $str = str_replace ( "&#039;", "'", $str );
            $str = str_replace ( "&quot;", '"', $str );
            $str = str_replace ( "&amp;", "&", $str );
        }
        return $str;
    }
    //입력된 DB의 값들을 Object(Input, Textarea)에 쓰기 위해서 다시 원상복귀
    public static function convDB2ValAll(&$array){
        if(is_array($array)) {
            //array_walk ($array, array($this, 'convDB2ValAll'));
            array_walk ($array, 'self::convDB2ValAll');
        } else {
            $array = self::convDB2Val($array);
        }
    }
    //htmlspecialchars_decode 같은 기능이라고 해야하나???
    public static function html2char( $string ){
        $string = str_replace ( "&amp;", "&", $string );
        $string = str_replace ( "&#039;", "'", $string );
        $string = str_replace ( "&quot;", '"', $string );
        $string = str_replace ( "&lt;", "<", $string );
        $string = str_replace ( "&gt;", ">", $string );
        return $string;
    }
    /**
     * 왼쪽을 지정 자리 만큼 구분자(delimiter)로 채우기
     */
    public static function lpad($str, $len = 1, $delimiter = ""){
        return str_pad($str, $len, $delimiter, STR_PAD_LEFT);
    }
    /**
     * 오른쪽을 지정 자리 만큼 구분자(delimiter)로 채우기
     */
    public static function rpad($str, $len = 1, $delimiter = ""){
        return str_pad($str, $len, $delimiter, STR_PAD_RIGHT);
    }
    /**
     * 양쪽(오른쪽,왼쪽)을 지정 자리 만큼 구분자(delimiter)로 채우기
     */
    public static function bothpad($str, $len = 1, $delimiter = ""){
        return str_pad($str, $len, $delimiter, STR_PAD_BOTH);
    }
    /**
     * 크기만큼만 보여주기
     */
    public static function cut_view($str,$width = "90%",$delimiter="ellipsis"){
        $str = "<span style='cursor:hand;width: " . $width . "; border: 0px solid blue; overflow: hidden; text-overflow:" . $delimiter . "'><nobr>" . $str . "</nobr></span>";
        return $str;
    }
    /**
     * 글자 자르기(테스트 안해봄, 추후 수정요지 있음)
     */
    public static function cut_str($str,$length = "0", $delimiter = "...", $charset = "CP949"){
        /*
        try{
            $str = iconv_substr($str,0,$length,$charset) . $delimiter;
        } catch (exception $e) {
            $str = mb_substr($str,0,$length) . $delimiter;
        }*/
        if(!$str = @iconv_substr($str,0,$length,$charset)){
            $str = mb_substr($str,0,$length);
        }
        $str = $str . $delimiter;
        return $str;
    }

    /* END 문자열 처리 */
//==========================================================================
// Begin ADODB의 crypt.inc.php
//==========================================================================
    private static $rand_len = 4;
    //암호화, 복호화 값 생성
    private static function keyED($txt,$encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);
        $ctr=0;
        $tmp = "";
        for ($i=0;$i<strlen($txt);$i++){
            if ($ctr==strlen($encrypt_key)) $ctr=0;
            $tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
            $ctr++;
        }
        return $tmp;
    }
    //암호화
    public static function Encrypt($txt,$key)
    {
        srand((double)microtime()*1000000);
        $encrypt_key = md5(rand(0,32000));
        $ctr=0;
        $tmp = "";
        for ($i=0;$i<strlen($txt);$i++)
        {
            if ($ctr==strlen($encrypt_key)) $ctr=0;
            $tmp.= substr($encrypt_key,$ctr,1) .
                (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
            $ctr++;
        }
        return base64_encode(self::keyED($tmp,$key));
    }
    //복호화
    public static function Decrypt($txt,$key)
    {
        $txt = self::keyED(base64_decode($txt),$key);
        $tmp = "";
        for ($i=0;$i<strlen($txt);$i++){
            $md5 = substr($txt,$i,1);
            $i++;
            $tmp.= (substr($txt,$i,1) ^ $md5);
        }
        return $tmp;
    }
    //랜덤 문자 생성
    public static function RandPass()
    {
        $randomPassword = "";
        srand((double)microtime()*1000000);
        for($i=0;$i<self::$rand_len;$i++)
        {
            $randnumber = rand(48,120);

            while (($randnumber >= 58 && $randnumber <= 64) || ($randnumber >= 91 && $randnumber <= 96))
            {
                $randnumber = rand(48,120);
            }

            $randomPassword .= chr($randnumber);
        }
        return $randomPassword;
    }
//==========================================================================
// End ADODB의 crypt.inc.php
//==========================================================================

    /**
     * POST, GET 모드에 따른 값 반환
     * @Return $vars {array}
     */
    public static function getVars(){
        if($_SERVER["REQUEST_METHOD"] == "GET"){
            RESET($_GET);
            $vars = $_GET;
        } else {
            RESET($_GET);
            RESET($_POST);
            if(preg_match("/^multipart\/form-data/", $_SERVER["CONTENT_TYPE"])){
                RESET($_FILES);
                $vars = array_merge($_FILES,$_POST, $_GET);
                //$vars = array_merge($_POST, $_GET);
            } else {
                $vars = array_merge($_POST, $_GET);
            }
        }
        return $vars;
    }
    /**
     *
     */
    public static function isEmpty($val){
        if(!isset($val) || $val == ""){
            return false;
        } else {
            return true;
        }
    }
    /**
     * 섬네일 생성
     */
    //       GD 2.0 이상일시 : ImageCreate -> ImageCreateTrueColor
    public static function thumb_make($img_src="", $max_x="", $max_y=""){
        $types = array(
            1 => 'GIF',
            2 => 'JPG',
            3 => 'PNG',
            4 => 'SWF',
            5 => 'PSD',
            6 => 'BMP',
            7 => 'TIFF(intel byte order)',
            8 => 'TIFF(motorola byte order)',
            9 => 'JPC',
            10 => 'JP2',
            11 => 'JPX',
            12 => 'JB2',
            13 => 'SWC',
            14 => 'IFF',
            15 => 'WBMP',
            16 => 'XBM'
        );
        if(!$max_x) $max_x=150;
        if(!$max_y) $max_y=150;
        $img_info = getimagesize ($img_src);
        $sx = $img_info[0];
        $sy = $img_info[1];
        //echo $sx ." - ".$sy;
        if ($sx>$max_x || $sy>$max_y) {
            if ($sx>$sy) {
                $thumb_y=ceil(($sy*$max_x)/$sx);
                $thumb_x=$max_x;
            } else {
                $thumb_x=ceil(($sx*$max_y)/$sy);
                $thumb_y=$max_y;
            }
        } else {
            $thumb_y=$sy;
            $thumb_x=$sx;
        }
        $file_name = basename($img_src);
        $file_dir = str_replace($file_name,"",$img_src);
        $thumb_file=$file_dir.$file_name.".thumb";

        if($img_info[2]=="1"){
            //Header("Content-type: image/gif");
            $thumb=@ImageCreateFromgif($img_src);
        } else if($img_info[2]=="2"){
            //Header("Content-type: image/jpeg");
            $thumb=@ImageCreateFromjpeg($img_src);
        } else if($img_info[2]=="3"){
            //Header("Content-type: image/png");
            $thumb=@ImageCreateFrompng($img_src);
        }

        //$dst_img=ImageCreate($thumb_x, $thumb_y);
        $dst_img=ImageCreateTrueColor($thumb_x, $thumb_y); //GD 2.0이상일시 지원(대체)
        @ImageCopyResized($dst_img,$thumb,0,0,0,0,$thumb_x+1,$thumb_y+1,$sx,$sy);

        if($img_info[2]=="1") @Imagegif($dst_img,$thumb_file);
        else if($img_info[2]=="2") @ImageJpeg($dst_img,$thumb_file,100);
        else if($img_info[2]=="3") @ImagePNG($dst_img,$thumb_file);

        @ImageDestroy($dst_img);
        if(isset($src_img))
        {
            @ImageDestroy($src_img);
        }
        return $thumb_file;
    }
    /**
     * 파일 MIME 타입
     */
    public static function getMimeType($str){
        global $mime_type;
        $_ext  = strtolower(substr(strrchr($str,"."),1));
        if(!$_ext){
            $_ext = "." . $str;
        } else {
            $_ext = "." . $_ext;
        }
        //echo $_ext;
        $mime = $mime_type[$_ext];
        if(!$mime){
            //$mime = "application/octet-stream";
            $mime = "application/force-download";
        }
        return $mime;
    }
    /**
     * 업로드 파일 사이즈 체크
     */
    public static function uploadFileSizeCheck($file_size, $limit_size){
        if($file_size > $limit_size){
            self::alert("파일업로드가능 크기를 초과하였습니다. MaxSize[$limit_size]Byte");
        }
    }
    /**
     * 파일업로드 확장자 체크
     */
    public static function uploadFileExtCheck($file_ext = "", $avail_ext = ""){
        if($avail_ext != ""){//업로드 가능 확장자가 지정되어 있을 때..
            $extarr = explode(",", chop($avail_ext));
            for($i = 0 ; $i < count($extarr) ; $i++){
                if(preg_match("/{$extarr[$i]}/i", $file_ext)){
                    $ok = 1;
                    break;
                }
            }

            if(!$ok){
                //$this->alert("지정된 확장자($avail_ext)외에는 업로드가 불가합니다.");
                self::alert("지정된 확장자($avail_ext)외에는 업로드가 불가합니다.");
            }
        } else {
            if($file_ext == "php3" || $file_ext == "html" || $file_ext == "htm" || $file_ext == "php" || $file_ext == "phtml" || $file_ext == "inc" || $file_ext == "phps" || $file_ext == "phtm" || $file_ext == "cgi" || $file_ext == "asp" || $file_ext == "jsp" || $file_ext == "java" || $file_ext == "pl" || $file_ext == "c" || $file_ext == "h"){//스크립트 파일의 경우
                self::alert("스크립트 파일은 등록이 불가합니다.");
            }
        }
    }
    /**
     * 확장자 추출
     */
    public static function getFileExt($file_name){
        return strtolower(substr(strrchr($file_name,"."),1));
    }
    /**
     * 파일업로드 확장자 체크
     */
    public static function getFileExtCheck($file_ext, $avail_ext = ""){
        if($avail_ext != ""){//사용 가능 확장자가 지정되어 있을 때..
            $extarr = explode(",", chop($avail_ext));
            for($i = 0 ; $i < count($extarr) ; $i++){
                if(preg_match("/{$extarr[$i]}/i", $file_ext)){
                    $ok = 1;
                    break;
                }
            }

            if(!$ok){
                //$this->alert("지정된 확장자($avail_ext)외에는 업로드가 불가합니다.");
                return false;
            }
        } else {
            if($file_ext == "php3" || $file_ext == "html" || $file_ext == "htm" || $file_ext == "php" || $file_ext == "phtml" || $file_ext == "inc" || $file_ext == "phps" || $file_ext == "phtm" || $file_ext == "cgi" || $file_ext == "asp" || $file_ext == "jsp" || $file_ext == "java" || $file_ext == "pl" || $file_ext == "c" || $file_ext == "h"){//스크립트 파일의 경우
                return false;
            }
        }
        return true;
    }
    /**
     * 폴더 생성
     */
    public static function mk_dir($path,$mode=0777){
        if (is_dir($path) || @mkdir($path,$mode)) return TRUE;
        if (!$self::mk_dir(dirname($path),$mode)) return FALSE;
        return @mkdir($path,$mode);
        /*
       if(!@is_dir($path)){
           if(!@mkdir($path,$mode)){
               $this->alert("디렉토리(폴더)가 존재(생성)하지 않습니다.");
               exit;
           }
       }
       if(!is_writable($path)){
           $this->alert("업로드 디렉토리(폴더)에 쓰기권한이 없습니다.(경로:" . $path . ")");
           exit;
       }*/
    }
    /**
     * 폴더 생성
     */
    public static function rm_dir($path){
        if (is_dir($path) && @rmdir($path)) return TRUE;
    }
    /**
     * 파일명 생성
     */
    public static function uploadFileName($file_name, $type = false){
        $file_ext = self::getFileExt($file_name);
        $s_file_name = preg_replace("/(\." . $file_ext . ")$/i", "" ,$file_name);
        if($type == "md5") { //파일 암호화
            $f_name = md5(uniqid("MD5"));
        } else if($type == "date"){
            $f_name = date("YmdHis");
        }else{
            //$temp_name = explode(".", $file_name);
            $f_name = str_replace(" ", "_", $s_file_name);
        }
        return $f_name;
    }
    /**
     * 파일 업로드
     */
    public static function uploadFile(string $uFileName, string $savedir = "", string $avail_ext = "", int $limit_size = 0, string $type = null){
        //echo $savedir;
        //exit;
        if(!@is_dir($savedir)){
            if(!@self::mk_dir($savedir,0777)){
                self::alert("디렉토리(폴더)가 존재(생성)하지 않습니다.");
                exit;
            }
        }
        if(!is_writable($savedir)){
            self::alert("업로드 디렉토리(폴더)에 쓰기권한이 없습니다.(경로:" . $savedir . ")");
            exit;
        }
        if(!$limit_size){
            $limit_size = get_cfg_var("upload_max_filesize"); //get_cfg_var() => getenv() 대체가능
        } else if($limit_size > get_cfg_var("upload_max_filesize")) {
            $limit_size = get_cfg_var("upload_max_filesize");
        }
        if(preg_match("/(Mbyte|MB|M)/i",$limit_size)){
            $limit_size = preg_replace("/(Mbyte|MB|M)/i","",$limit_size);
            $limit_size = $limit_size*(1024*1024);
        } else {
            $limit_size = $limit_size/1024;
        }
        $file_name = $_FILES[$uFileName]['name'];
        $file_size = $_FILES[$uFileName]['size'];
        // * ----------------- 파일크기 체크 -------------------- * //
        self::uploadFileSizeCheck($file_size, $limit_size);
        // * ------------------ 확장자 체크 --------------------- * //
        $file_ext  = strtolower(substr(strrchr($file_name,"."),1));
        self::uploadFileExtCheck($file_ext, $avail_ext);
        $f_name = self::uploadFileName($file_name,$type);
        $save_file = $f_name . "." . $file_ext;
        $save = $savedir . "/" . $save_file;
        if(file_exists($save) == true){
            /*for($m = 1; file_exists($save); $m++){                         // 중복파일 체크
                $svae_file = $f_name . "_" . $m  . "." . $file_ext;
                $save = $savedir . "/" . $save_file;
            }*/
            $m = 1;
            do{
                $save_file = $f_name . "_" . $m  . "." . $file_ext;
                $save = $savedir . "/" . $save_file;
                //echo " - " . $save . "<br />";
                $m++;
            }while(file_exists($save));
        }
        // ------------------ Upload --------------------------- //
        //$tempresult = move_uploaded_file($_FILES[$uFileName]['tmp_name'], $save);
        //echo $save . "<br>" . $tempresult . "<br>";
        //echo "aaaaaaaaa";
        $tmp_file = $_FILES[$uFileName]['tmp_name'];
        $tmp_file = str_replace("\\", "/", $tmp_file);
        if(!move_uploaded_file($tmp_file, $save)){
            if(!copy($tmp_file, $save)){
                self::print_r($_FILES[$uFileName]);
                self::msg("파일을 업로드 하지 못했습니다." . $uFileName);
                exit;
            } else {
                @unlink($tmp_file);
            }
        }
        //$savedir = str_replace(_ROOT_PATH_, "", $savedir);
        $_FILES[$uFileName]["save_dir"] = $savedir;
        $_FILES[$uFileName]["save_name"] = $save_file;
        $_FILES[$uFileName]["save"] = $save;
        $_FILES[$uFileName]["save_path"] = $save;
        $_FILES[$uFileName]["mime"] = self::getMimeType($save_file);
        $_FILES[$uFileName]["ext"]  = self::getFileExt($save_file);
        //기존 $_FILES 속성 정보
        //$_FILES[$uFileName]["name"] //사용 비추 save_name을 이용하세요~
        //$_FILES[$uFileName]["type"]
        //$_FILES[$uFileName]["tmp_name"]
        //$_FILES[$uFileName]["error"]
        //$_FILES[$uFileName]["size"]
        //self::print_r($_FILES);
        //exit;
        return $_FILES[$uFileName];
    }
    public static function fileUpload($uFileName, $save_dir, $avail_ext = "", $limit_size = 0){
        return self::uploadFile($uFileName, $save_dir, $$avail_ext, $limit_size);
    }
    public static function fileUploadMD5($uFileName, $save_dir, $avail_ext = "", $limit_size = 0){
        return self::uploadFile($uFileName, $save_dir, $avail_ext, $limit_size, "md5");
    }
    public static function fileUploadDate($uFileName, $save_dir, $avail_ext = "", $limit_size = 0){
        return self::uploadFile($uFileName, $save_dir, $avail_ext, $limit_size, "date");
    }
    public static function fileUploadImg($uFileName, $save_dir, $avail_ext = "gif,jpg,png,jpeg,tif", $limit_size = 0){
        return self::uploadFile($uFileName, $save_dir, $avail_ext, $limit_size, "false");
    }
    public static function fileUploadImgMD5($uFileName, $save_dir, $avail_ext = "", $limit_size = 0){
        return self::uploadFile($uFileName, $save_dir, $avail_ext, $limit_size, "md5");
    }
    public static function fileUploadImgDate($uFileName, $save_dir, $avail_ext = "",$limit_size = 0){
        return self::uploadFile($uFileName, $save_dir, $avail_ext,$limit_size, "date");
    }
    public static function fileDelete($uFileName, $save_dir): bool
    {
        if($save_dir){
            $save_name = $save_dir . "/" . $uFileName;
        } else {
            $save_name = $uFileName;
        }
        return @unlink($save_name);
    }
    /**
     * 조건절 생성(기존 조건절 없을시 Where를 붙이고, 없을 경우 조건타입을 붙여서 조건절 반환)
     * @Param $where {String} : 기존 조건
     * @Param $query_string {String} : 새로 추가할 조건
     * @Param $type {String} : 조건 타입(And(기본), Or)
     * @Return {String} : 조합된 조건절
     */
    public static function query_where($where= "", $query_string = "", $type = " AND "){
        $where = trim($where);
        if($query_string){
            if(!preg_match("/^where/i", $where)) $where  = " AND " . $query_string;
            else                         $where .= " " . $type . " "  . $query_string;
        }
        return $where;
    }
    /**
     * 정렬조건절(order by) 생성
     * @Param $order {String} : 기존 정렬조건
     * @Param $order_string {String} : 새로 추가할 정렬조건
     * @Return {String} : 조합된 정렬조건절
     */
    public static function query_order($order= "", $order_string = ""){
        $order = trim($order);
        if($order_string){
            if(!preg_match("/^order by/i", $order)) $order  = " ORDER BY " . $order_string;
            else                         $order .= "," . $order_string;
        }
        return $order;
    }
    //파일 용량 단위 붙여서 반환
    public static function getStrSize($size){
        $i=0;
        $iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
        while (($size/1024)>1) {
            $size=$size/1024;
            $i++;
        }
        if(($size/1024)>=1){
            $size = $size/1024;
            $i++;
        }
        return number_format(substr($size,0,strpos($size,'.')+4)).$iec[$i];
    }
    public static function download($file, $dir = true){
        if($file){
            $file_name = $file;
            //if($dir != true) echo "aaaa";
            if($dir == false) {
                $file_name = $file;
                //echo $file_name;
            } else if($dir == true){
                if(!str_ends_with($dir, "/")){
                    $dir .= "/";
                }
                $file_name = $dir . $file;
            }
            if(file_exists($file_name) && is_file($file_name)){
                $file_ext = self::getFileExt($file_name);
                if(self::getFileExtCheck($file_ext) == true){
                    $file_type = self::getMimeType($file_ext);
                    @header("Content-type: " . $file_type);
                    @header("Content-Length: " . filesize($file_name));
                    @header("Content-Disposition: attachment; filename=" . basename($file_name));
                    if ($fp = fopen ($file_name, "rb")) {
                        fpassthru($fp);
                        @fclose($fp);
                    }
                } else {
                    echo "Script File Not Support!!";
                }
            } else {
                echo $file_name;
                echo "File Not Found!!";
            }
        } else {
            echo "File Name Param Error!!";
        }
    }
    public static function prev_month($year, $month){
        $setStemp = mktime(0,0,0, $month, 0, $year);
        $prev["Y"] = date("Y", $setStemp);
        $prev["m"] = date("m", $setStemp);
        $prev["d"] = date("d", $setStemp); // == date("t", $setStemp)
        return $prev;
    }
    public static function next_month($year, $month){
        $setStemp = mktime(0,0,0, $month+1, 1, $year);
        $next["Y"] = date("Y", $setStemp);
        $next["m"] = date("m", $setStemp);
        $next["d"] = date("d", $setStemp);
        return $next;
    }
    public static function getWeekDateBetween($year, $month, $day, &$start_day,&$end_day, $start_week = 0){
        if($start_week > 0){
            $start_week = 1;
        }
        $nowday = mktime(0,0,0,$month, $day, $year);
        $now_week_day = date("w", mktime(0,0,0, $month, $day, $year));
        $last_day = date("t",$nowday);
        $start_week_term = $now_week_day - $start_week;
        if ($start_week_term < 0){
            $start_week_term = 7 - $start_week;
        }
        $start_day = date("Y-m-d", mktime(0,0,0,$month, $day - $start_week_term, $year));
        $end_day   = date("Y-m-d", mktime(0,0,0,$month, ($day - $start_week_term + 6), $year));
    }
    public static function getWeekDateMonBetween($year, $month, $day, &$start_day,&$end_day){
        self::getWeekDateBetween($year, $month, $day, $start_day,$end_day, 1);
    }
    public static function getThatMonthWeekDateBetween($year, $month, $day, &$start_day,&$end_day){
        $nowday = mktime(0,0,0,$month, $day, $year);
        $now_week_day = date("w", mktime(0,0,0, $month, $day, $year));
        $last_day = date("t",$nowday);
        //마지막 날의 요일을 구한다.
        $end_week_day = date("w", mktime(0,0,0, $month, $last_day, $year));
        if($day >= 1 && $day <= $now_week_day+1){//현재주가 시작 주이면
            $start_day = date("Y-m-d", mktime(0,0,0, $month, 1, $year));
            $end_day = date("Y-m-d", mktime(0,0,0,$month, $day + (6-$now_week_day), $year));
        }elseif($day >= ($last_day - ($end_week_day+1)) && $day <= $last_day){//현재주가 마지막 주이면
            $start_day = date("Y-m-d", mktime(0,0,0, $month, ($day - $now_week_day), $year));
            $end_day = date("Y-m-d", mktime(0,0,0,$month, $last_day, $year));
        }else{
            $start_day = date("Y-m-d", mktime(0,0,0, $month,  $day - $now_week_day, $year));
            $end_day = date("Y-m-d", mktime(0,0,0,$month, $day + (6-$now_week_day), $year));
        }
    }
    public static function getNumOfWeek($num){
        switch($num){
            case 0:
                $str = "일";
                break;
            case 1:
                $str = "월";
                break;
            case 2:
                $str = "화";
                break;
            case 3:
                $str = "수";
                break;
            case 4:
                $str = "목";
                break;
            case 5:
                $str = "금";
                break;
            case 6:
                $str = "토";
                break;
        }
        return $str;
    }
    public static function getExtIcon($ext){
        $ext = strtolower(self::getFileExt($ext));
        switch($ext){
            case "exe" :
            case "com" :
                $icon_ext = "exe";
                break;
            case "hlp" :
            case "chm" :
                $icon_ext = "chm";
                break;
            case "mpg" :
            case "mpeg" :
            case "mov" :
            case "avi" :
                $icon_ext = "mpg";
                break;
            case "mp3" :
            case "mp2" :
            case "wav" :
            case "mid" :
                $icon_ext = "mp3";
                break;
            case "bmp" :
            case "gif" :
            case "png" :
            case "jpg" :
            case "jpeg" :
            case "tif" :
            case "tiff" :
                $icon_ext = "gif";
                break;
            case "doc" :
            case "rtf" :
                $icon_ext = "doc";
                break;
            case "inf" :
            case "css" :
                $icon_ext = "inf";
                break;
            case "txt" :
            case "sql" :
            case "log" :
            case "conf" :
                $icon_ext = "txt";
                break;
            case "gul":
            case "pdf":
            case "js":
            case "ppt":
            case "srd":
            case "srw":
            case "srf":
            case "pbl":
                $icon_ext = "gul";
                break;
            Case "pbd" :
                $icon_ext = "pbl";
                break;
            Case "html" :
            case "htm" :
            case "asp":
            case "php":
            case "php3":
            case "cgi" :
                $icon_ext = "html";
                break;
            case "zip" :
            case "rar" :
            case "alz" :
            case "7z"  :
            case "tar" :
            case "gz"  :
            case "z"   :
            case "cab" :
            case "ace":
            case "tgz":
            case "arc":
            case "pak":
            case "lha" :
                $icon_ext = "zip";
                break;
            Case "xls" :
                $icon_ext = "xls";
                break;
            Case "hwp" :
                $icon_ext = "hwp";
                break;
            Case "ttf" :
                $icon_ext = "ttf";
                break;
            default :
                $icon_ext = "unknown";
        }
        $icon = "<img src='" . _LIB_URL_ . "images/ext/ext_" . $icon_ext . ".gif' align='absmiddle' border=0 />";
        return $icon;
    }

    public static function getHompageUrl($url){
        if(!preg_match("/^http/i",$url)){
            $url = "http://" . $url;
        }
        return $url;
    }
    public static function getHomepageTag($url){
        if($url){
            $url = "<a href='" . self::getHompageUrl($url) . "' target='_blank'>" . self::getHompageUrl($url) . "</a>";
        }
        return $url;
    }
    /**
     * 파일 인클루드 후 내용 리턴
     * @Param $filename {String} : 파일경로
     * @Return {String or boolean}
     */
    public static function getInclude($filename) {
        global $user;
        global $post;
        if (is_file($filename)) {
            ob_start();
            @include $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }
}