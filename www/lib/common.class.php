<?php
class common {
    var $db;
    var $_table = array();
    var $_col   = array();
    /**
     * PHP5용 Class 생성자
     */
    function __construct(){
        $this->common();
    }
    function __destruct(){
        if($this->db != null && !$this->db->PConnect) {
          unset($this->db);
        }
        unset($_table);
        unset($_col);
      //echo "__destruct";
    }
    /**
     * Class 생성자
     */
    function common() {
        $this->_table["location"]       = "loc_code";
        $this->_col["location"]["no"]   = "no";
        $this->_col["location"]["name"] = "loc_name";
        $this->_col["location"]["code"] = "loc_code";
        $this->_col["location"]["kind"] = "loc_kind";
    }
    /**
     * DB 객체 생성
     * @Return {NULL}
     */
    function dbOpen(){
        global $db;
        if(!$this->db){
            if ($db){
                $this->db = $db;
            } else {
                $this->db = new DB;
            }
        }
    }
    /**
     * 쿼리
     * @Param $AQty_Type {String} : 쿼리 타입
     * @Return {String}
     */
    function getQueryString($AQty_Type){
        switch(strtoupper($AQty_Type)){
            case "LOC" :
            case "LOCATION" :
                $SQL = "SELECT "
                      . $this->_col["location"]["no"] . " NO"
                      . ", " . $this->_col["location"]["name"] . " NAME"
                      . ", " . $this->_col["location"]["code"] . " CODE"
                      . ", " . $this->_col["location"]["kind"] . " KIND"
                      . " FROM " . $this->_table["location"] . " WHERE 1 = 1";
                break;
        }
        return $SQL;
    }

    /**
     * LOCATION(본사/지사/사업소) 정보
     * @Return {Array}
     */
    function getLocationArray(){
        if (!$this->db){
            $this->dbOpen();
        }
        $SQL = $this->getQueryString("LOCATION");
        //$this->db->Debug = 1;
        $this->db->query($SQL);
        //$this->db->next_record();
        $i = 0;
        //echo $this->db->nf();
        while($this->db->next_record()){
            $row[$i] = $this->db->Record;
            $i++;
        }
        //Fun::print_r($row);
        if ($i > 0){
            return $row;
        } else {
            return NULL;
        }
    }
}
?>