/**
 * Javascript용 trim함수 구현
 * @Param str {String}
 * @Return {String}
 */
function trim(str){
      //정규 표현식을 사용하여 화이트스페이스를 빈문자로 전환
      str = str.replace(/^\s*/,'').replace(/\s*$/, '');
      return str; //변환한 스트링을 리턴.
}
/**
 * 새창 띄우기
 * @Param furl {String} : 주소
 * @Param winName {String} : window 이름
 * @Param winWidth {int,String} : window 폭
 * @Param winHeight {int, String} : window 높이
 * @Param winScroll {String} : window 스크롤바 옵션
 * @Param winOpt {String} : 기타 window 옵션
 * @Return {Object}
 */
function winOpenPopup(furl, winName, winWidth, winHeight, winScroll, winOpt){
    var winPoup = null;
    var winOption = null;
    //winName = eval(winName);
    if(winScroll == ""){
        winScroll = "no";
    }
    winOption = "width=" + winWidth + ", height=" + winHeight + ",scrollbars=" + winScroll + "," + winOpt;
    try{
        winPoup.location = furl;
    }catch(e){
        winPoup = window.open(furl,winName,winOption);
    }
    winPoup.focus();
    return winPoup;
}

var classFun = function(){
    /**
     * Javascript용 trim함수 구현
     * @Param str {String}
     * @Return {String}
     */
    this.trim = function(str){
        return trim(str);
    }
    this.getObj = function(id){
        var Obj = null;
        try {
            Obj = document.getElementById(id);
        } catch(e) {
            try{
                Obj = document.all[id];
            } catch(e) {
                Obj = document.layers[id];
            }
        }
        return Obj;
    }
    /**
     * 값이 Null인지 체크
     */
    this.is_null = function(str){
        switch(str){
            case 'unknown':
            case 'undefined':
            case null:
                return true;
                break;
        }
        return false;
    }
    this.isNull = function(str){
      return this.is_null(str);
    }
    /**
     * 값이 선언 안되었거나 Null인지 체크
     */
    this.isset = function(str){
        if(this.is_null(str) == true){
            return false;
        } else {
            return true;
        }
    }
    /**
     * 값 선언여부
     */
    this.empty = function(str){
        if(str == "undefined"){
            return false;
        } else if(this.isNull(str) == true){
          return false;
        } else if(str == ""){
          return false;
        } else {
            return true;
        }
    }
    /**
     * 공백이 들어갔는지 체크
     */
    this.isWithSpace = function(str){
      var re = new RegExp("\s","ig");;
      if(re.test(str)){
        return true;
      } else {
        return false;
      }
    }
    /**
     *값이 숫자만 입력되었는지 체크
     */
    this.isNumber = function(str){
      var re = new RegExp("[0-9]{1,}", "ig");
      if(this.is_null(str) == true){
        return false;
      } else if(re.test(str)){
        return true;
      } else {
        return false;
      }
    }
    /**
     *값이 해당 크기만큼 숫자만 입력되었는지 체크
     */
    this.isNumberLen = function(str,stLen, edLen){
      var re = new RegExp("[0-9]{" + stLen + "," + edLen + "}", "ig");
      if(this.isNumber(str) == false){
        return false;
      } else if(re.test(str)){
        return true;
      } else {
        return false;
      }
    }

    /**
     * 배열의 값들을 delimiter(구분자)를 넣어서 String형태로 반환하기
     */
    this.getArrayVal = function (arrayObj, delimiter){
        var str = "";
        if(this.isset(arrayObj) == true){
            if(this.isset(delimiter) == false){
                delimiter = ",";
            }
            for(var i=0;i<arrayObj.length;i++){
                if(str == ""){
                    str = arrayObj[i];
                } else {
                    str += delimiter + arrayObj[i];
                }
            }
        }
        return str;
    }
    /**
     * 두 배열의 값들을 delimiter(구분자)를 넣어서 String형태로 Join 후 반환하기(주의 두개의 Array 수는 같아야함)
     */
    this.getArrayJoin = function(obj1, obj2, delimiter_key, delimiter_val){
        var str = "";
        if(this.isset(obj1) == true && this.isset(obj2) == true){
            if(this.isset(delimiter_key) == false){
                delimiter_key = "=>";
            }
            if(this.isset(delimiter_val) == false){
                delimiter_key = ",";
            }
            for(var i=0;i<obj1.length;i++){
                if(str == ""){
                    str = obj1[i] + delimiter_key + obj2[i];
                } else {
                    str += delimiter_key + obj1[i] + delimiter_key + obj2[i];
                }
            }
        }
        return str;
    }
    /**
     * 두 문자열을 delimiter(구분자)로 잘라서 delimiter(구분자)를 넣어서 String형태로 Join 후 반환하기
     * (주의 두개의 문자열의 delimiter로 나눈 수는 같아야함)
     */
    this.getStringJoin = function(str1, str2, delimiter, delimiter_key, delimiter_val){
        var str = "";
        if(this.isset(str1) == true && this.isset(str2) == true){
            if(this.isset(delimiter_key) == false){
                delimiter = ",";
            }
            if(this.isset(delimiter_key) == false){
                delimiter_key = "=>";
            }
            if(this.isset(delimiter_val) == false){
                delimiter_val = ",";
            }
            var exp1 = str1.split(delimiter);
            var exp2 = str2.split(delimiter);
            for(var i=0;i<exp1.length;i++){
                if(str == ""){
                    str = exp1[i] + delimiter_key + exp2[i];
                } else {
                    str += delimiter_val + exp1[i] + delimiter_key + exp2[i];
                }
            }
        }
        return str;
    }
    /**
     * 새창 띄우기
     * @Param furl {String} : 주소
     * @Param winName {String} : window 이름
     * @Param winWidth {int,String} : window 폭
     * @Param winHeight {int, String} : window 높이
     * @Param winScroll {String} : window 스크롤바 옵션
     * @Param winOpt {String} : 기타 window 옵션
     * @Return {Object}
     */
    this.winOpenPopup = function (furl, winName, winWidth, winHeight, winScroll, winOpt){
        return winOpenPopup(furl, winName, winWidth, winHeight, winScroll, winOpt);
    }
    /**
     * 특정영역 인쇄(Print 창 띠우기)
     */
    this.printLayer = function (sourceName,is_msg, msg){
    this.printXLayer(sourceName,is_msg, msg);
    /*
        var print_flag = true;
        if(is_msg == true){
            if(!msg){
                msg = "현재 페이지를 인쇄하려면 새로운 창을 띄워야 합니다 계속하시겠습니까?";
            }
            if (!confirm(msg)) {
                print_flag = false;
            }
        }
        if(print_flag == true){
            var obj = eval("document.all." + sourceName);
            //var winPrintFreeview = window.open('', 'winPrintFreeview', 'width=635,height=560,resizable=no,scrollbars=yes,toolbar=no,menubar=no');
            var winPrintFreeview = window.open('', 'winPrintFreeview', 'width=700,height=560,resizable=no,scrollbars=yes,toolbar=no,menubar=no');
            var strHtml = "";
            strHtml += "<html>\n<head>\n";
            strHtml += "<link title=style href='/inc/tpcc.css' type='text/css' rel='styleSheet' />\n";
      strHtml += "<link title=style href='/tcms/UB/jntp/css/style.css' type='text/css' rel='styleSheet' />\n";
            strHtml += "<style type=text/css>\n";
            strHtml += "BODY {MARGIN: 0px}\n";
            strHtml += ".style2 {font-size: 18px;font-weight: bold;}\n";
            strHtml += ".style1 {font-size: 16px;font-weight: bold;}\n";
            strHtml += "@media print { .printhidden { display:none; } }\n";
            strHtml += "@media screen { .printhidden { border:solid black 1px; display:inline; } }\n";
            strHtml += "</style>\n";
            strHtml += "</head>\n";
            strHtml += "<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onload='print()'>\n";
            strHtml += "<table width='100%' border='0' align='center'>\n";
            strHtml += "<tr><td align='center'>\n";
        if(obj.length > 1){
            strHtml += obj[1].innerHTML;
        }else{
            strHtml += obj.innerHTML;
        }
            strHtml += "</td></tr></table>";
            strHtml += "<br>";
            strHtml += "</body>";
            strHtml += "</html>";
            winPrintFreeview.document.open();
            winPrintFreeview.document.write(strHtml);
            winPrintFreeview.document.close();
        }*/
    }

  /**
     * 특정영역 인쇄(Print 창 띠우기) - ScriptX이용
     */
    this.printXLayer = function (sourceName,is_msg, msg){
        var print_flag = true;
        if(is_msg == true){
            if(!msg){
                msg = "현재 페이지를 인쇄하려면 새로운 창을 띄워야 합니다 계속하시겠습니까?";
            }
            if (!confirm(msg)) {
                print_flag = false;
            }
        }
        if(print_flag == true){
            var obj = eval("document.all." + sourceName);
            //var winPrintFreeview = window.open('', 'winPrintFreeview', 'width=635,height=560,resizable=no,scrollbars=yes,toolbar=no,menubar=no');
            var winPrintFreeview = window.open('', 'winPrintFreeview', 'width=700,height=560,resizable=no,scrollbars=yes,toolbar=no,menubar=no');
            var strHtml = "";
            strHtml += "<html>\n<head>\n";
            strHtml += "<link title=style href='/inc/tpcc.css' type='text/css' rel='styleSheet' />\n";
      strHtml += "<link title=style href='/tcms/UB/jntp/css/style.css' type='text/css' rel='styleSheet' />\n";
            strHtml += "<style type=text/css>\n";
            strHtml += "BODY {MARGIN: 0px}\n";
            strHtml += ".style2 {font-size: 18px;font-weight: bold;}\n";
            strHtml += ".style1 {font-size: 16px;font-weight: bold;}\n";
            strHtml += "@media print { .printhidden { display:none; } }\n";
            strHtml += "@media screen { .printhidden { border:solid black 1px; display:inline; } }\n";
            strHtml += "</style>\n";
      strHtml += "<script language='javascript' type='text/javascript'>\n";
      strHtml += "function printWindow(){\n";
      strHtml += "  factory.printing.Header          = '';\n";
      strHtml += "  factory.printing.Footer          = '&w&b페이지 &p / &P';\n";
      strHtml += "  factory.printing.portrait        = true; // 세로로 출력할것인지 가로로 출력할것인지 설정합니다. true:세로 false:가로\n";
      strHtml += "  factory.printing.leftMargin      = 10.0;   // 좌측여백\n";
      strHtml += "  factory.printing.topMargin       = 20.0;   // 상단여백\n";
      strHtml += "  factory.printing.rightMargin     = 10.0;   // 우측여백\n";
      strHtml += "  factory.printing.bottomMargin    = 15.0;   // 하단여백\n";
      //strHtml += "  self.close();\n";
      strHtml += "  factory.printing.Preview();\n";
      strHtml += "  self.close();\n";
      strHtml += "}\n";
      strHtml += "</script>";
            strHtml += "</head>\n";
          strHtml += "<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onload='printWindow()'>\n";

      strHtml += "<OBJECT id='factory' style='DISPLAY: none' codeBase='/bins/smsx.cab#Version=6,2,433,14' classid='clsid:1663ed61-23eb-11d2-b92f-008048fdd814' viewastext></OBJECT>\n";
            strHtml += "<table width='100%' border='0' align='center'>\n";
            strHtml += "<tr><td align='center'>\n";
        if(obj.length > 1){
            strHtml += obj[1].innerHTML;
        }else{
            strHtml += obj.innerHTML;
        }
            strHtml += "</td></tr></table>";
            strHtml += "<br>";
            strHtml += "</body>";
            strHtml += "</html>";
      //strHtml += "<p style='page-break-before:always'>";
            winPrintFreeview.document.open();
            winPrintFreeview.document.write(strHtml);
            winPrintFreeview.document.close();
      //winPrintFreeview.printWindow(strHtml);
        }
    }
    /**
     * HTML Form Object 전체 길이 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 갯수
     */
    this.getObjectLen = function(obj){
        return obj.length;
    }
    /**
     * Select(Combo)Box 전체 길이 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : Option 갯수
     */
    this.getOptionLen = function(obj){
        return obj.options.length;
        //return obj.length;
    }
    /**
     * Select(Combo)Box 선택여부
     * @Param obj {Object} : 대상 Object
     * @Return {Bool}      : 선택여부
     */
    this.isSelected = function(obj){
        return obj.selected;
    }
    /**
     * Select(Combo)Box 선택된 Index 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int, null} : 선택된 Option Index(0부터 시작), 선택된 값 없을 경우 null 반환
     */
    this.getOptionIndex = function(obj){
        var iIndex = -1;
        try{
            iIndex = obj.options.selectedIndex;
        } catch(e){
            try{
                for(var i=0;i<this.getOptionLen(obj);i++){
                    if(this.isSelected(obj.options[i]) == true){
                        iIndex = i;
                    }
                }
            } catch(e) {
                iIndex = -1;
            }
        }
        return iIndex;
    }
    /**
     * Select(Combo)Box 선택된 Value값 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 선택된 Value 값
     */
    this.getOptionVal = function(obj){
        var str = null;
        try{
            str = obj[this.getOptionIndex(obj)].value;
        } catch(e){
            try{
                str = obj.value;
            }catch(e){
                str = null;
            }
        }
        return str;
    }
    /**
     * Select(Combo)Box 선택된 Text값 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 선택된 Text 값
     */
    this.getOptionText = function(obj){
        var str = null;
        try{
            str = obj[this.getOptionIndex(obj)].text;
        } catch(e){
            try{
                str = obj.text;
            }catch(e){
                str = null;
            }
        }
        return str;
    }
    /**
     * Select(Combo)Box 내에 Value값 존재여부
     * @Param obj {Object} : 대상 Object
     * @Param val {String} : 체크할 Value값
     * @Return {Bool}      : 존재여부(true:존재,false:비존재)
     */
    this.isOptionVal = function (obj,val){
        for(var i=0;i<this.getOptionLen(obj);i++){
            if(obj[i].value == val){
                return true;
            }
        }
        return false;
    }
    /**
     * Select(Combo)Box 내에 Value을 Array형태로 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Array}     : 값을 Array형태로 반환
     */
    this.getOptionArray = function (obj,mode){
        if(this.isset(obj) == true){
            var arr_val = new Array(this.getOptionLen(obj));
            if(this.isset(mode) == false){
                mode = "value";
            }
            for(var i=0;i<this.getOptionLen(obj);i++){
                if(mode == "text"){
                    arr_val[i] = obj[i].text;
                } else {
                    arr_val[i] = obj[i].value;
                }
            }
            return arr_val;
        }
        //return null;
    }
    /**
     * Select(Combo)Box 내에 Value와 Text값을 구분자로 반환
     */
    /**
     * Select(Combo)Box에 Option 추가하기
     * @Param obj {Object} : 대상 Object
     * @Param val {String} : Object Value 값
     * @Param str {String} : Object Text 값
     * @Param opt {Bool}   : 추가한 Object Option에 포커수 주기
     */
    this.setOptionAdd = function (obj, val, str, opt){
        var obj_opt =  document.createElement("OPTION"); //new Option()
        var num = this.getOptionLen(obj);
        obj_opt.text = str;
        obj_opt.value = val;
        try{
            /* IE용 */
            obj.add(obj_opt,num);
        } catch(e){
            /* 비 IE용 */
            obj.appendChild(obj_opt.cloneNode(true));
        }
        if(opt){
            obj.options[num].selected = true;
            obj.options[num].focus();
        }
    }
    /**
     * Select(Combo)Box 중 선택된 하나 Option 삭제
     */
    this.setOptionDel = function (obj, iIndex){
        if(iIndex == null || iIndex == "undefined"){
            iIndex = this.getOptionIndex(obj);
            if(iIndex == -1 || iIndex == null || iIndex == "undefined"){
                return false;
            }
        }
        if(iIndex != null && iIndex != "undefined" && iIndex != -1){
            obj.remove(iIndex);
            //alert(iIndex);
            return true;
        }
        return false;
    }
    /**
     * Select(Combo)Box 중 선택된 전체 Option 삭제
     */
    this.setOptionDelMulti = function(obj){
        for(var i = this.getOptionLen(obj) - 1 ; i >= 0;i--){
            if(this.isSelected(obj[i]) == true){
                this.setOptionDel(obj,i);
            }
        }
    }
    /**
     * Select(Combo)Box 중 전체 Option 삭제
     */
    this.setOptionDelAll = function(obj){
        //obj.removeAll();
        for(var i = this.getOptionLen(obj) - 1 ; i >= 0;i--){
            //obj.remove(i);
            //obj[i].clearAttributes();
            this.setOptionDel(obj,i);
        }
    }
    /**
     * Select(Combo)Box Assing
     */
    this.setOptionAssign = function(sourceObj, targetObj){
        //obj.removeAll();
        //this.setOptionDelAll(targetObj);
        for(var i = 0 ; i < this.getOptionLen(sourceObj); i++){
        		//alert(sourceObj[i].value);
            //this.setOptionAdd(targetObj, sourceObj[i].value, sourceObj[i].text);
        }
    }
  /**
     * Radio Object 전체 길이 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 갯수
     */
    this.getCheckLen = function(obj){
        return this.getObjectLen(obj);
    }
    /**
     * Radio Object 전체 길이 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 갯수
     */
    this.getRadioLen = function(obj){
        return this.getObjectLen(obj);
    }
    /**
     * Radio/Checkbox Object 선택(체크)여부
     * @Param obj {Object} : 대상 Object
     * @Return {Bool}      : 선택(체크)여부
     */
    this.isChecked = function(obj){
        return obj.checked;
    }
  /**
     * Radio/Checkbox 전체 Object 선택(체크)여부
     * @Param obj {Object} : 대상 Object
     * @Return {Bool}      : 선택(체크)여부
     */
    this.isCheckedCount = function(obj){
    var cnt = 0;
    for(var i = 0; i < obj.length;i++){
      if(this.isChecked(obj[i]) == true){
        cnt++;
      }
    }
        return cnt;
    }
  /**
     * Radio/Checkbox 전체 Object 선택(체크)여부
     * @Param obj {Object} : 대상 Object
     * @Return {Bool}      : 선택(체크)여부
     */
    this.getCheckedCount = function(obj){
        return this.isCheckedCount(obj);
    }
  /**
     * CheckBox Object 선택(체크)된 Index 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 선택(체크)된 Index(0부터 시작)
     */
    this.getCheckIndex = function(obj){
        var iIndex = null;
        for(var i=0;i<this.getCheckLen(obj);i++){
            if(this.isChecked(obj[i]) == true){
                iIndex = i;
            }
        }
        return iIndex;
    }
  /**
     * Radio Object 선택(체크)된 Value값 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 선택(체크)된 Index(0부터 시작)
     */
    this.getCheckVal = function(obj){
        var str = null;
        try{
            str = obj[this.getCheckIndex(obj)].value;
        } catch(e){
            try{
                str = obj.value;
            }catch(e){
                str = null;
            }
        }
        return str;
    }
    /**
     * Radio/Checkbox Object Checked된 값들을 delimiter(구분자)를 넣어서 String형태로 반환하기
     */
    this.getCheckVals = function (arrayObj, delimiter){
        var str = "";
        if(this.isset(arrayObj) == true){
            if(this.isset(delimiter) == false){
                delimiter = ",";
            }
            for(var i=0;i<arrayObj.length;i++){
                if(arrayObj[i].checked == true){
                    if(str == ""){
                        str = arrayObj[i].value;
                    } else {
                        str += delimiter + arrayObj[i].value;
                    }
                }
            }
        }
        return str;
    }
    /**
     * Radio Object 선택(체크)된 Index 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 선택(체크)된 Index(0부터 시작)
     */
    this.getRadioIndex = function(obj){
        var iIndex = null;
        for(var i=0;i<this.getRadioLen(obj);i++){
            if(this.isChecked(obj[i]) == true){
                iIndex = i;
            }
        }
        return iIndex;
    }
    /**
     * Radio Object 선택(체크)된 Value값 반환
     * @Param obj {Object} : 대상 Object
     * @Return {Int}       : 선택(체크)된 Index(0부터 시작)
     */
    this.getRadioVal = function(obj){
        var str = null;
        try{
            str = obj[this.getRadioIndex(obj)].value;
        } catch(e){
            try{
                str = obj.value;
            }catch(e){
                str = null;
            }
        }
        return str;
    }
  this.checkKeyEnter = function(e){
    var evt = e ? e : window.event;
    var checkKey=evt.keyCode;
    if (checkKey==13) {
      return true;
    } else {
      return false;
    }
  }
  this.mouseOverTd = function (obj,color){
    if(!color){
      color = "#f6f6f6";
    }
    obj.style.backgroundColor = color;
    obj.style.cursor = "hand";
  }
  this.mouseOutTd = function (obj,color){
    if(!color){
      color = "";
    }
    obj.style.backgroundColor = color;
    obj.style.cursor = "auto";
  }
    /**
     * 값 입력
     */
    this.commaDel = function(str){
        return str.toString().split(',').join('');
        //return str.split(',').join('');
    }
    this.commaApp = function(str){
        var num = this.commaDel(str);
        var arr = num.split('.');
        var val = new Array();
        for (var i = 0; i <= arr[0].length-1; i++) {
            val[i] = arr[0].substr(arr[0].length-1-i,1);
            if(i%3 == 0 && i != 0) val[i] += ',';
        }
        if(arr[1]){
            val[i+1] = arr[1];
        }
        return val.reverse().join('');
    }
    this.setMoneyFocus = function(obj){
        obj.value = this.commaDel(obj.value);
    }
    this.setMoneyOutFocus = function(obj){
        obj.value = this.commaApp(obj.value);
    }

    this.setCookie = function(c_name, value, expiredays) {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + expiredays);
        document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
    }
    this.getCookie = function(c_name) {
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) c_end = document.cookie.length;
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return "";
    }
}
var Fun = new classFun();