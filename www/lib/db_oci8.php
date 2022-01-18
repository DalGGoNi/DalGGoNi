<?php
/*
 * Created on 2010. 11. 08
 *
 */
// Connects to the MYDB database described in tnsnames.ora file,
// One example tnsnames.ora entry for MYDB could be:
//   MYDB =
//     (DESCRIPTION =
//       (ADDRESS = (PROTOCOL = TCP)(HOST = mymachine.oracle.com)(PORT = 1521))
//       (CONNECT_DATA =
//         (SERVER = DEDICATED)
//         (SERVICE_NAME = XE)
//       )
//     )
class DB_Sql {
    public		$Debug		= false;
    public		$PConnect	= false;
    
    protected           $Host		= "";
    protected           $Database	= "";
    protected           $User		= "";
    protected           $Password	= "";
    protected           $Character      = "KO16MSWIN949";	//KO16MSWIN949,AL32UTF8
    
    //BEGIN 사용자 정의 기능
    public              $DefaultCharacter       = "AL32UTF8"; //KO16MSWIN949,AL32UTF8
    public              $DefaultFetchArrayMode  = OCI_BOTH + OCI_RETURN_NULLS; //OCI_BOTH = OCI_ASSOC + OCI_NUM, OCI_RETURN_NULLS, OCI_RETURN_LOBS
    public              $IsColumnNameToCaseType = 1; // -2 : CASE_UPPER - Key Convert Only, -1 : 변경 없음, 0:CASE_LOWER - Key Convert Only, 1 : CASE_LOWER - Key Append, 2 : CASE_UPPER - Key Append, 3 : CASE_BOTH (LOWER, UPPER) - Key Append
    public              $IsGetRecordAll         = true;
    //END 사용자 정의 기능


    protected           $TNSConnString  = "";
    protected           $isTransaction  = false;

    protected           $sqoe		= 1;

    public	 	$Link_ID	= null;
    public		$Record		= array();
    public		$RecordAll	= array();
    public		$Parse		= null;

    protected           $Bind		= null;
    public		$Row		= 0;
    public		$Error		= array();
    public		$nRows		= 0;
    public		$nCols		= 0;

    /*
     * PHP5용 생성자
     */

    public function __construct(){
        $this->DB_Sql();
        //$this->isTransaction = false;
        //echo "__Construct By Class " . get_class();
    }
    function DB_Sql(){
        //echo "__Construct By Class " . get_class();
        $this->isTransaction = false;
        
        $this->TestMessage = "TestMessage";
        
        $this->debugMessage("__Construct By Class " . get_class());
        //echo "Create By Class DB_Sql\n<br />";
        //$this->setConnection($User, $Password, $Database, $Character);
        //if($query != ""){
        //        $this->query($query);
        //}
        //$this->connect();
    }

    public function __destruct(){
        //echo "__Destruct By Class " . get_class();
        $this->debugMessage("__Destruct By Class " . get_class());
        if($this->Link_ID){
                //echo "FREE";
                $this->disconnect();
        }
        //echo "__destruct";
    }
    public function close($Parse = ""){
        if($Parse === ""){
                $Parse = $this->Parse;
        }
        if(is_resource($Parse)){
            if(@oci_free_statement($Parse)){
                    return true;
            } else {
                    return false;
            }
        } else {
            return false;
        }
    }
    public function free($LinkID = ""){
        if($LinkID != ""){
            $LinkID = $this->Link_ID;
        }
        $this->disconnect($LinkID);
    }
    public function setTNSConnString($AHost, $AName, $APort = "1521"){
        $this->TNSConnString = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = {$AHost} )(PORT = {$APort} ))(CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME = {$AName} ) ) )";
    }
    public function getTNSConnString($AHost = "", $AName = "", $APort = "1521"){
        if($AHost && $AName){
                $this->setTNSConnString($AHost, $AName, $APort);
        }
        return $this->TNSConnString;
    }
    /**
     * Connect
     */
    public function connect($User = "", $Password = "", $Database = "", $Character = ""){
        if(!is_resource($this->Link_ID))
        {
            if(!$User){
                $User = $this->User;
            }
            if(!$Password)
            {
                $Password = $this->Password;
            }
            if(!$Database){
                if($this->Host && $this->Database){
                        $Database = $this->Host . "/" . $this->Database;
                }
                $Database = $this->Database;
                //$Database = $this->getTNSConnString($this->Host, $this->Database);
            }
            if(isset($Character) && $Character)
            {
                $Character = strtoupper($Character);
                if($Character == "CP949" || $Character == "MS949" || $Character == "MSWIN949" || $Character == "EUC-KR"|| $Character == "EUCKR")
                {
                    $Character = "KO16MSWIN949";
                }
                else if($Character == "UTF-8" || $Character == "UTF8")
                {
                    $Character = "AL32UTF8";
                }
            }
            if(!$Character)
            {
                $Character = $this->DefaultCharacter;
            }
            $this->Link_ID = $this->getConnection($User, $Password, $Database, $Character);
        }
        return $this->Link_ID;
        //echo "OK";
    }
    public function getConn($User = "", $Password = "", $Database = "", $Character = ""){
        return $this->getConnection($User, $Password, $Database, $Character);
    }
    public function getConnection($User = "", $Password = "", $Database = "", $Character = ""){
        $this->debugMessage("Database Connection\n");
        if(is_resource($this->Link_ID))
        {
            $this->debugMessage("Connection Link ID : " . $this->Link_ID);
        } else {
            //$this->debugMessage("Connection New Link ID : " . $this->Link_ID);
        }
        if($User != "" && !$this->User)
        {
            $this->User = $User;
        }
        if($Password != "" && !$this->Password)
        {
            $this->Password = $Password;
        }
        if($Database != "" && !$this->Database)
        {
            $this->Database = $Database;
        }
        if($Character != "" && !$this->Character)
        {
            $this->Character = $Character;
        }
        //$Character = "AL32UTF8";
        //$this->debugMessage("Execute Connection");
        $this->debugMessage("Connecting to " . $Database . "...");
        if(isset($this->PConnect) && $this->PConnect == true)
        {
            if($Character != "")
            {
                $Link_ID = @oci_pconnect($User, $Password, $Database, $Character);
            }
            else 
            {
                $Link_ID = @oci_pconnect($User, $Password, $Database);
            }
        } else {
            if($Character != "")
            {
                $Link_ID = @oci_connect($User, $Password, $Database, $Character);
            }
            else 
            {
                $Link_ID = @oci_connect($User, $Password, $Database);
            }
        }
        if(!is_resource($Link_ID)){
            $this->Error = oci_error();
            $this->halt();
            //trigger_error(htmlspecialchars($this->Error["message"], ENT_QUOTES));
        }
        $this->debugMessage("Connecting to " . $Database . " : " . $Link_ID . "...");
        return $Link_ID;
    }
    public function setConn($User = "", $Password = "", $Database = "", $Character = ""){
        return $this->setConnection($User, $Password, $Database, $Character);
    }
    public function setConnection($User = "", $Password = "", $Database = "", $Character = ""){
        return $this->getConnection($User, $Password, $Database, $Character);
    }

    public function halt($msg = "") {
        //$msg = "";
        if($msg){
            @Fun::iconv_utf8All($msg);
            printf("<b>Database error:</b> %s<br>\n", $msg);
            die("Database halted.[" . get_class() . "]");
        } else {
            @Fun::iconv_utf8All($this->Error);
            printf("<br><b>ORACLE Error</b>: (%s)<br />%s<br>\n", $this->Error["code"], $this->Error["message"]);	//code, message, offset, sqltext
            die("Database halted.[" . get_class() . "]");
        }
        //exit;
    }
    public function debugMessage($msg){
        if($this->Debug == true){
            @Fun::iconv_utf8All($msg);
            echo "<br>[<strong>Debug</strong> - " . get_class($this) . "]  " .  $msg . "...<br>\n";
        }
    }
    /*
     * Query
     */
    public function query($Query_String, $Bind = null, $ALinkID = null){
        if (!trim($Query_String))
            return 0;

        if($ALinkID){
            $LinkID = $ALinkID;
        } else {
            $LinkID = $this->Link_ID;
        }
        if(!is_resource($LinkID)){
            $LinkID = $this->connect();
        }
        $Parse = 0;
        if(is_resource($LinkID)){
            $this->debugMessage("Execute Query [{$LinkID}] : " . $Query_String);
            $Parse = oci_parse($LinkID, $Query_String);
            //$this->Error = @oci_error($Parse);
            if(!is_resource($Parse)){
                $this->halt("Parse Error!");
                //trigger_error(htmlspecialchars($this->Error["message"], ENT_QUOTES));
            } else {
                if(is_array($Bind)){
                    $this->Bind = $Bind;
                    $bind_keys = array_keys($Bind);
                    $bind_cnt  = count($bind_keys);
                    $bind_vals = array_values($Bind);
                    for($i = 0; $i < $bind_cnt; $i++){
                        if(!@oci_bind_by_name($Parse, ":" . $bind_keys[$i], $bind_vals[$i])){
                                //echo $bind_keys[$i] . "=>" .$bind_vals[$i];
                        }
                    }
                    /*
                    foreach($Bind as $_key => $_val){
                            oci_bind_by_name($Parse, ":" . $_key, $_val);
                    }
                    */
                }
            }
            if("SELECT" == strtoupper(substr(trim($Query_String), 0, 6)) || "WITH" == strtoupper(substr(trim($Query_String), 0, 4))){
                @oci_execute($Parse);
                $this->Error = @oci_error($Parse);
                @oci_fetch_all($Parse, $result, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
                
                if(is_array($result)){
                    $this->nRows = count($result);
                    if(isset($this->IsGetRecordAll) && isset($this->nRows) && $this->IsGetRecordAll == true && $this->nRows > 0)
                    {
                        $this->RecordAll = $result;
                    }
                }
                @oci_execute($Parse);
                $this->Parse = $Parse;
                unset($this->Record);
            } else {
                if($this->isTransaction != true){
                        @oci_execute($Parse, OCI_COMMIT_ON_SUCCESS);
                        $this->debugMessage("Commit Mode : OCI_COMMIT_ON_SUCCESS ({$LinkID} : {$Parse})");
                } else {
                        @oci_execute($Parse, OCI_DEFAULT); //OCI_NO_AUTO_COMMIT : Do not automatically commit changes. Prior to PHP 5.3.2 (PECL OCI8 1.4) use OCI_DEFAULT which is an alias for OCI_NO_AUTO_COMMIT.
                        $this->debugMessage("Commit Mode : OCI_NO_AUTO_COMMIT ({$LinkID} : {$Parse})");
                }
                $this->Error = @oci_error($Parse);
                //echo $this->Error;
                //echo "<pre>";
                //echo "Query : " . $Query_String;
                //echo "</pre>";
            }
            //echo "<pre>";
            //echo count($result1);
            //echo "\n";
            //print_r($result1);
            //echo "</pre>";
            if (isset($this->Error["code"]) && $this->Error["code"] != 1403 && $this->Error["code"]!= 0 && $this->sqoe > 0)
            $this->halt("({$this->Error["code"]})<br /><font color=red><b>{$this->Error["message"]}<br />Query :\"$Query_String\"</b></font>");
            //$this->debugMessage("Execute Query : " . $Query_String);
            //echo $this->Parse;
        }
        return $Parse;
    }
    public function next_record($Parse = null, $FetchArrayMode = null){
        $Result = false;
        if(isset($Parse) != true || $Parse == null || $Parse == "" || $Parse == 0 || $Parse == "0"){
            //@oci_execute($this->Parse);
            $Parse = $this->Parse;
            //echo OCI_NUM + OCI_RETURN_NULLS;
        }
        //$data = @oci_fetch_array($Parse, OCI_ASSOC + OCI_NUM + OCI_RETURN_NULLS); //OCI_BOTH = OCI_ASSOC + OCI_NUM, OCI_RETURN_NULLS, OCI_RETURN_LOBS
        $optFetchArrayMode = $FetchArrayMode;
        if(isset($optFetchArrayMode) != true || !$optFetchArrayMode){
            $optFetchArrayMode = $this->DefaultFetchArrayMode;
        }
        if(isset($optFetchArrayMode) != true || !$optFetchArrayMode){
            $optFetchArrayMode = OCI_BOTH + OCI_RETURN_NULLS;
        }
        $data = @oci_fetch_array($Parse, $optFetchArrayMode);
        if(!is_array($data))
        {
            $this->debugMessage("ID: " . $this->Link_ID . ",Rows: " . $this->num_rows());
            $this->Error = @oci_error($Parse);
            if (isset($this->Error) && is_array($this->Error) && $this->Error["code"] == 1403){	//1043 means no more records found
                $Result = false;
                $this->halt();
            } else {
                $Result = false;	//0
            }
        } 
        else 
        {
            unset($this->Record);
            $this->Row += 1;
            $this->Record = $data;
            $this->nCols = $this->num_fields($Parse);
            if($this->nCols > 0)
            {
                if($this->IsColumnNameToCaseType == 0)
                {
                    //컬럼명 LOWER로 강제 변경
                    $this->Record = array_change_key_case($this->Record, CASE_LOWER);
                }
                else if($this->IsColumnNameToCaseType == -2)
                {
                    //컬럼명 UPPER로 강제 변경
                    $this->Record = array_change_key_case($this->Record, CASE_UPPER);
                }
                else if($this->IsColumnNameToCaseType >= 1)
                {
                    for($i = 0 ; $i < $this->nCols; $i++) {
                        $colNameUpper=strtoupper(oci_field_name($Parse,$i+1));
                        $colNameLower=strtolower($colNameUpper);
                        //컬럼명 LOWER 추가
                        if(($this->IsColumnNameToCaseType == 1 || $this->IsColumnNameToCaseType >= 3) && array_key_exists($colNameLower, $this->Record) != true)
                        {
                            //$colreturn=strtolower($col);
                            //$this->Record[$i - 1] = $row[$i];
                            //$this->Record[$col] = $row[$i];
                            if(is_object($data[$i])){
                                    $this->Record[$colNameLower] = $data[$i]->load();
                                    $this->debugMessage("<b>[$colNameUpper]</b>:".$data[$i]->load());
                            } else {
                                    $this->Record[$colNameLower] = $data[$i];
                                    $this->debugMessage("<b>[$colNameUpper]</b>:".$data[$i]);
                            }
                        }
                        //컬럼명 UPPER 추가
                        if( ($this->IsColumnNameToCaseType == 2 || $this->IsColumnNameToCaseType >= 3) && array_key_exists($colNameUpper, $this->Record) != true )
                        {
                            //$colreturn=strtolower($col);
                            //$this->Record[$i - 1] = $row[$i];
                            //$this->Record[$col] = $row[$i];
                            if(is_object($data[$i])){
                                    $this->Record[$colNameUpper] = $data[$i]->load();
                                    $this->debugMessage("<b>[$colNameUpper]</b>:".$data[$i]->load());
                            } else {
                                    $this->Record[$colNameUpper] = $data[$i];
                                    $this->debugMessage("<b>[$colNameUpper]</b>:".$data[$i]);
                            }
                        }
                    }
                }
            }
            if(isset($this->Record) && $this->Record && $this->nCols > 0)
            {
                $Result = true;	//1
            }
        }
        //echo $this->Record["tname"] . " - " . $this->Record["tname"];
        return $Result;
    }
    public function num_rows($Parse = ""){
            if($Parse == ""){
                    $Parse = $this->Parse;
            }
            return $this->nRows;
            //return oci_num_rows($Parse);
            //return oci_num_rows ($Parse);
    }
    public function num_fields($Parse = "") {
            if($Parse == ""){
                    $Parse = $this->Parse;
            }
            return oci_num_fields($Parse);
    }
    public function seek($pos){
            $this->Row = $pos;
    }
    public function nf($Parse = ""){
            return $this->num_rows($Parse);
    }
    public function np($Parse = ""){
            echo $this->num_rows($Parse);
    }
    public function f($ColName){
            if(is_object($this->Record[$ColName])){
                    return $this->Record[$ColName]->load();
            } else {
                    return $this->Record[$ColName];
            }
    }
    public function p($ColName){
            echo $this->f($ColName);
    }
    public function nextid($SeqName){
            $this->connect();
            $Query_ID = @oci_parse($this->Link_ID, "SELECT " . $SeqName . ".NEXTVAL FROM DUAL");
            if(!@oci_execute($Query_ID)){
                    $this->Error=@oci_error($Query_ID);
                    if($this->Error["code"] == 2289){
                            $this->debugMessage("Execute Create Sequence");
                            $Query_ID=oci_parse($this->Link_ID,"CREATE SEQUENCE " . $SeqName);
                            if(!oci_execute($Query_ID)){
                                    $this->Error=oci_error($Query_ID);
                                    $this->halt("<br /> nextid() function - unable to create sequence<br />".$this->Error["message"]);
                            } else {
                                    $Query_ID = oci_parse($this->Link_ID, "SELECT " . $SeqName . ".NEXTVAL FROM DUAL");
                                    oci_execute($Query_ID);
                            }
                    }
            }
            if (oci_fetch($Query_ID)){
                    $this->debugMessage("Execute GetSequence");
            $next_id = oci_result($Query_ID,"NEXTVAL");
        } else {
            $next_id = 0;
        }
        oci_free_statement($Query_ID);
        return $next_id;
    }
    public function disconnect($LinkID = ""){
            if($LinkID != ""){
                $LinkID = $this->Link_ID;
            }
            if($LinkID){
                $this->debugMessage("Disconnecting...Link ID : {$Link_ID}");
                @oci_close($LinkID);
            }
    }
    /**
     * Database Begin Transaction
     */
    public function BeginTransaction(){
        $this->debugMessage("{$this->Link_ID} : Begin Transaction..........................");
        $this->isTransaction = true;
    }
    /**
     * Database End Transaction
     */
    public function EndTransaction(){
        $this->rollback();
        $this->isTransaction = false;
        $this->debugMessage("{$this->Link_ID} : End Transaction..........................");
    }
    public function getTransaction(){
        return $this->isTransaction;
    }
    public function isTrans(){
        return $this->getTransaction();
    }
    /**
     * Database Transaction - Commit
     */
    public function commit($LinkID = null){
            if($this->isTransaction == true){
                if(!$LinkID){
                        $LinkID = $this->Link_ID;
                }
                $this->debugMessage("{$LinkID} : Transaction Commit");
                $r = @oci_commit($LinkID);
                if (!$r) {
                    $e = @oci_error($LinkID);
                    trigger_error(htmlentities($e['message']), E_USER_ERROR);
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
    }
    /**
     * Database Transaction - rollback
     */
    public function rollback($LinkID = null)
    {
        if(isset($this->isTransaction) && $this->isTransaction == true)
        {
            if(!$LinkID){
                $LinkID = $this->Link_ID;
            }
            $this->debugMessage("{$LinkID} : Transaction Roolback");
            $r = @oci_rollback($LinkID);
            if (!$r) {
                $e = @oci_error($LinkID);
                return false;
                trigger_error(htmlentities($e['message']), E_USER_ERROR);
            } else {
                return true;
            }
        } else {
            return true;
        }
        return false;
    }
}