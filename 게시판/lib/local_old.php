<?php
class DB extends DB_Sql {
    var $Host     = _DB_Host_;
    var $Database = _DB_Name_;
    var $User     = _DB_User_;
    var $Password = _DB_Pass_;
    var $RecordAll= array();
    var $CurrRow;
    var $RowCount;
    var $isNumRow = false;
    var $phpVer   = 4;
    var $Character  = "KO16MSWIN949";	//KO16MSWIN949,AL32UTF8
    var	$DefaultCharacter  = "KO16MSWIN949";
    /**
     * PHP5용 Class 생성자
     */
    function __construct(){
        $this->phpVer = 5;
    }
    function query($SQL, $bind = null, $link_ID = null) {
        unset($this->RecordAll);
        $this->isNumRow = false;
        $SQL = trim($SQL);
        if ($SQL == "")
            return 0;
        $this->connect();
        
        if(!$link_ID && $this->Link_ID){
            $link_ID = $this->Link_ID;
        }
        
        if(!$link_ID)
            return 0;
        
        //$this->Parse=OCIParse($LinkID,$Query_String);
        $this->Parse = oci_parse($link_ID, $SQL);

        if(!$this->Parse) {
             $this->Error=OCIError($this->Parse);
        } else {
            OCIExecute($this->Parse);
            $this->Error=OCIError($this->Parse);
        }

        $this->Row=0;

        if($this->Debug) {
            printf("Debug: query = %s<br>\n", $SQL);
        }

        if (isset($this->Error) && isset($this->sqoe) && isset($this->Error["code"]) && $this->Error["code"]!=1403 && $this->Error["code"]!=0 && $this->sqoe)
            echo "<BR><FONT color=red><B>".$this->Error["message"]."<BR>Query :\"$SQL\"</B></FONT>";

        if ($this->phpVer >= 5 && (strtoupper(substr($SQL, 0, 6)) == "SELECT" || strtoupper(substr($SQL, 0, 4)) == "WITH" )){
            //oci_fetch_all($stmt, $row, "0", "-1", OCI_ASSOC+OCI_FETCHSTATEMENT_BY_ROW);
            //ocifetchstatement
            $this->isNumRow = true;
            //OCI_ASSOC+OCI_RETURN_NULLS
            //OCI_ASSOC+OCI_FETCHSTATEMENT_BY_ROW
            $this->RowCount = oci_fetch_all($this->Parse, $this->RecordAll,"0", "-1",OCI_ASSOC+OCI_FETCHSTATEMENT_BY_ROW);
            //Fun::print_r($this->RecordAll);
        }
        return $this->Parse;
    }

    function next_record() {
        unset($this->Record);
        if ($this->isNumRow)
        {
            if ($this->RowCount > $this->Row){
                for($ix=1;$ix<=OCINumcols($this->Parse);$ix++) {
                    $col=strtoupper(OCIColumnname($this->Parse,$ix));
                    $colreturn=strtolower($col);
                    $this->Record[$ix - 1] = $this->RecordAll[$this->Row]["$col"];
                    $this->Record[ "$colreturn" ] = $this->RecordAll[$this->Row]["$col"];
                    if($this->Debug) echo"<b>[$col]</b>:".$this->RecordAll[$this->Row]["$col"]."<br />\n";
                }
                $this->Row += 1;
                $stat = 1;
            } else {
                if ($this->Debug) {
                    printf("<br>ID: %d,Rows: %d<br>\n",
                    $this->Link_ID,$this->num_rows());
                }
                $this->Row += 1;
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

    function query_limit($SQL, $Offset = 0, $Count = 0){
        //echo $Query_String . " LIMIT " . $Offset . ", " . $Count;
        if($Offset >= 0 && $Count >= 0){
            switch(_DB_Class_){
                case "mysql":
                    $Offset >= 0 ? $Offset = $Offset . ", ": "";
                    $strQueryString = $SQL . " LIMIT " . $Offset . $Count;
                    //echo $SQL;
                    break;
                case "oci8":
                case "oracle":
                    $nStartRow = $Offset;
                    $nEndRow = $nStartRow + $Count;
/*
                    $SQL  = "SELECT * FROM ";
                    $SQL .= "       (SELECT T.*,rownum AS rnum FROM ";
                    $SQL .= "           (" . $Query_String . ") T ";
                    $SQL .= "       ) ";
                    $SQL .= "WHERE rnum > " . $nStartRow . " AND rownum <= " . $nEndRow;*/
                    /* 위아래 쿼리중 뭐가 좋을까 생각 고민고민) */
                    $strQueryString =  "SELECT * FROM ";
                    $strQueryString .= "  (SELECT rownum AS rnum, T.* FROM ";
                    $strQueryString .= "      (" . $SQL . ") T";
                    $strQueryString .= "  WHERE rownum <= " . $nEndRow . ") ";
                    $strQueryString .= "WHERE rnum > " . $nStartRow;
                    //echo $SQL;
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

    function getOne($SQL){
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
    public function getTNSConnString($dbHost, $dbName, $dbPort = "1521"){
        return "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = {$dbHost} )(PORT = {$dbPort} ))(CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME = {$dbName} ) ) )";
    }
    
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


    public function free($link_ID = ""){
    	if($link_ID != ""){
            $link_ID = $this->Link_ID;
        }
        $this->disconnect($link_ID);
    }
}
?>