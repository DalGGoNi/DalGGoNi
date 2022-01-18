<?php
class DB extends DB_Sql {
    var $phpVer;
    /**
     * PHP5용 Class 생성자
     */
    function __construct(){
        $this->phpVer = (float)PHP_VERSION;
        $this->Host     = _DB_Host_;
        $this->Database = _DB_Name_;
        $this->User     = _DB_User_;
        $this->Password = _DB_Pass_;
        parent::__construct();
    }
    
    /**
    public function next_record($Parse = "") {
        if(!$Parse) $Parse = $this->Parse;
        unset($this->Record);
        if ($this->isNumRow)
        {
            if ($this->RowCount > $this->Row){
                for($ix=1;$ix<=OCINumcols($Parse);$ix++) {
                    $col=strtoupper(OCIColumnname($Parse,$ix));
                    $colreturn=strtolower($col);
                    $this->Record[$ix - 1] = $this->RecordAll[$this->Row][$col];
                    $this->Record[ "$colreturn" ] = $this->RecordAll[$this->Row][$col];
                    if($this->Debug) echo"<b>[$col]</b>:".$this->RecordAll[$this->Row][$col]."<br />\n";
                }
                $this->Row += 1;
                $stat = 1;
            } else {
                if ($this->Debug) {
                    printf("<br>ID: %d,Rows: %d<br>\n",
                    $this->Link_ID,$this->num_rows());
                }
                $this->Row += 1;
                $errno=OCIError($Parse);
                if(1403 == $errno) { # 1043 means no more records found
                    $this->Error="";
                    $this->disconnect();
                    $stat=0;
                } else {
                    $this->Error=OCIError($Parse);
                    if($this->Debug) {
                        printf("<br>Error: %s",
                        $this->Error["message"]);
                    }
                    $stat=0;
                }
                $stat = 0;
            }
            //$stat = 0;
        } else {
            if(0 == OCIFetchInto($this->Parse,$result,OCI_ASSOC+OCI_RETURN_NULLS)) {
                if ($this->Debug) {
                    printf("<br>ID: %d,Rows: %d<br>\n",
                    $this->Link_ID,$this->num_rows());
                }
                $this->Row        +=1;

                $errno=OCIError($this->Parse);
                if(1403 == $errno) { # 1043 means no more records found
                    $this->Error="";
                    $this->disconnect();
                    $stat=0;
                } else {
                    $this->Error=OCIError($this->Parse);
                    if($this->Debug) {
                        printf("<br>Error: %s",
                        $this->Error["message"]);
                    }
                    $stat=0;
                }
            } else {
                for($ix=1;$ix<=OCINumcols($this->Parse);$ix++) {
                    $col=strtoupper(OCIColumnname($this->Parse,$ix));
                    $colreturn=strtolower($col);
                    $this->Record[ "$colreturn" ] = $result["$col"];
                    if($this->Debug) echo"<b>[$col]</b>:".$result["$col"]."<br>\n";
                }
                $stat=1;
            }
        }

        return $stat;
    }
    function f($Name) {
        $Name = strtolower($Name);
        if (is_object($this->Record[$Name])){
            return $this->Record[$Name]->load();
        } else {
            return $this->Record[$Name];
        }
    }
     */

    function query_limit($SQL, $limitStartROw = 0, $limitCount = 0){
        //echo $Query_String . " LIMIT " . $Offset . ", " . $Count;
        if($limitStartROw >= 0 && $limitCount >= 0){
            switch(_DB_Class_){
                case "mysql":
                    $limitStartROw >= 0 ? $limitStartROw = $limitStartROw . ", ": "";
                    $strQueryString = $SQL . " LIMIT " . $limitStartROw . $limitCount;
                    //echo $SQL;
                    break;
                case "oci8":
                case "oracle":
                    $nStartRow = $limitStartROw;
                    $nEndRow = $nStartRow + $limitCount;
/*
                    $strQueryString  = "SELECT * FROM ";
                    $strQueryString .= "       (SELECT T.*,rownum AS rnum FROM ";
                    $strQueryString .= "           (" . $SQL . ") T ";
                    $strQueryString .= "       ) ";
                    $strQueryString .= "WHERE rnum > " . $nStartRow . " AND rownum <= " . $nEndRow;*/
                    /* 위아래 쿼리중 뭐가 좋을까 생각 고민고민) */
                    $strQueryString =  "SELECT * FROM ";
                    $strQueryString .= "  (SELECT rownum AS rnum, T.* FROM ";
                    $strQueryString .= "      (" . $SQL . ") T";
                    $strQueryString .= "  WHERE rownum <= " . $nEndRow . ") ";
                    $strQueryString .= "WHERE rnum > " . $nStartRow;
                    //echo $strQueryString;
                    break;
                default:
                    $strQueryString = $SQL;
            }//end switch(_DB_Class_)
        } else {
            $strQueryString = $SQL;
        }//end if($Offset >= 0 AND $Count > 0)
        //echo $SQL;
        return $this->query($strQueryString);
    }//end function query_limit();

    function query_one($SQL){
        try {
            $this->query($SQL);
            if ($this->nf() > 0) {
                $this->next_record();
                //Fun::print_r($this);
                return $this->f(0);
            } else {
                return NULL;
            }
        } catch(Exception $e) {
            return NULL;
        }
    }
    //=================================================
    //2021.12.08 추가 항목
    //=================================================
    //public function getTNSConnString($dbHost, $dbName, $dbPort = "1521"){
    //    return "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = {$dbHost} )(PORT = {$dbPort} ))(CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME = {$dbName} ) ) )";
    //}
    /*
    public function connect($dbUser = "", $dbPassword = "", $dbDatabase = "", $dbCharacter = "") 
    {
        if(!$dbUser) $dbUser = $this->User;
        if(!$dbPassword) $dbPassword = $this->Password;
        if(!$dbDatabase) $dbDatabase = $this->Database;
        $strSetCharacter = null;
        if(isset($dbCharacter) && $dbCharacter)
        {
            $strInputCharacter = strtoupper($dbCharacter);
            if($strInputCharacter == "CP949" || $strInputCharacter == "MS949" || $strInputCharacter == "EUC-KR"|| $strInputCharacter == "EUCKR")
            {
                $strSetCharacter = "KO16MSWIN949";
            }
            else if($strInputCharacter == "UTF-8" || $strInputCharacter == "UTF8")
            {
                $strSetCharacter = "AL32UTF8";
            }
        }
        if($strSetCharacter){
            $this->Character = $strSetCharacter;
        }
        if ( 0 == $this->Link_ID ) {
            if($this->Debug) {
                printf("<br>Connecting to $this->Database...<br>\n");
            }
            if(!$this->Character)
            {
                $this->Link_ID=oci_connect($this->User,$this->Password,$this->Database);
            }
            else {
                $this->Link_ID=oci_connect($this->User,$this->Password,$this->Database, $this->Character);
            }

            if (!$this->Link_ID) {
                $this->halt("Link-ID == false " . "($this->Link_ID), OCILogon failed");
            }

            if($this->Debug) {
                printf("<br>Obtained the Link_ID: $this->Link_ID<br>\n");
            }
        }
    }


    public function free($LinkID = ""){
    	if($LinkID != ""){
            $LinkID = $this->Link_ID;
        }
        $this->disconnect($LinkID);
    }
     
     */
}
?>