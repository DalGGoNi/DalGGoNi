var classCommon = function(){
    /**
     * 회사 관련 정보 팝업창
     */
    this.getWinPopupCompanyInfo = function (ACompNo, ACompName, ACompNick){
        try {
            var ObjCode = eval(document.getElementById(ACompNo));
            var ObjName = eval(document.getElementById(ACompName));
            var ObjNick = eval(document.getElementById(ACompNick));
            var furl    = "/common/company_popup.php?of_comp_no=" + ACompNo + "&of_comp_name=" + ACompName + "&of_comp_nick=" + ACompNick;
            var retunObj;
            returnObj = Fun.winOpenPopup(furl,'find_comp','300','600','yes','left=300,top=200');
            return returnObj;
        } catch (e) {
            return null;
        }
    }
    /**
     * PM 관련 정보 팝업창
     */
    this.getWinPopupMemberPM = function (AKey, AName, ACode){
        try {
            var furl    = "/common/member_pm_popup.php?of_key=" + AKey + "&of_code=" + ACode + "&of_name=" + AName;
            var retunObj;
            returnObj = Fun.winOpenPopup(furl,'find_pm','300','600','yes','left=310,top=210');
            return returnObj;
        } catch (e) {
            return null;
        }
    }
    /**
     * 고객사 담당자 관련 정보 팝업창
     */
    this.getWinPopupContact = function (AKey, AName){
        try {
            var furl    = "/common/contact_popup.php?of_key=" + AKey + "&of_name=" + AName;
            var retunObj;
            returnObj = Fun.winOpenPopup(furl,'find_pm','300','600','yes','left=320,top=220');
            return returnObj;
        } catch (e) {
            return null;
        }
    }
    /**
     * 고객사 담당자 관련 정보 팝업창
     */
    this.getWinPopupJobInfo = function (num){
        try {
            var furl    = "/timesheet/ts_myjob.php?mode=view&jno=" + num;
            var retunObj;
            returnObj = Fun.winOpenPopup(furl,'find_pm','800','700','yes','');
            return returnObj;
        } catch (e) {
            return null;
        }
    }
}
var common = new classCommon;
