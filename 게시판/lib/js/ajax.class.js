/**
 * Ajax(XMLHttp) 관련 Class
 */
var classAjax =
    /**
     * Class 호출과 동시에 XMLHttp 생성
     * @Param furl {String}
     * @Return {String}
     */
    function(furl){
    //생성된 XMLHTTP(XMLHttpRequest)를 가지고 있을 Object
    var objectXmlHttp = null;
    //클래스내 각종 결과를 담고있을 String
    var objectResultString = null;
    try{
        // IE일 경우
        objectXmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch(e){
        try{
            //IE일 경우(Msxml2.XMLHTTP 미지원시)
            objectXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(e){
            objectXmlHttp = null;
        }
    }
    if(!objectXmlHttp || objectXmlHttp == null){ // && typeof objectXmlHttp == 'undefined'
        //비 IE일 경우
        //alert('no IE');
        try{
            objectXmlHttp = new XMLHttpRequest();
        }catch(e){
            objectXmlHttp = null;
        }
    }
    if(objectXmlHttp == null){
        //alert("지원되지 않는 브라우저 입니다.\n\n자세한 내용은 관리자에게 문의하세요");
        objectResultString = "지원되지 않는 브라우저 입니다.\n\n자세한 내용은 관리자에게 문의하세요";
        return null;
    } else {
        objectResultString = "Created";
        //alert(objectResultString);
        //return this.xmlHttp;
    }
    if(!furl){
        ;;
    } else {
        this.callCheck(furl);
    }
    /**
     * Ajax 초기화
     * @Param furl {String}
     * @Param mode {String}
     */
    this.setOpen = function (furl,mode){
        objectResultString = null;
        if(!objectXmlHttp || objectXmlHttp == null){
            objectResultString = "Ajax가 지원되지 않는 브라우저 입니다.\n\n자세한 내용은 관리자에게 문의하세요";
            return objectResultString;
        }
        if(!furl){
            objectResultString = "URL이 지정되지 않았습니다.";
            return objectResultString;
        }
        if(mode == null || mode == "undefined" || !mode){
            mode = "GET";
        }
        objectXmlHttp.open(mode, furl,false); //GET, POST, PUT / true : 비동기방식
    }
    /**
     * Ajax 언어지정
     * @Param furl {String}
     */
    this.setCharset = function (strLang){
        if(strLang == null || strLang == "undefined" || !strLang){
            strLang = "UTF-8";
        }
        objectXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=" + strLang);
    }
    /**
     * Ajax 이벤트지정
     * @Param furl {String}
     * @Return {String}
     */
    this.setEvent = function (str){
        if(str == null || str == "undefined" || !str){
            objectXmlHttp.onreadystatechange = this.callResult;
        } else {
            //오류가 있을려나 몰라도(미구현)
            try{
                objectXmlHttp.onreadystatechange = eval(str);
            } catch(e){
                objectXmlHttp.onreadystatechange = this.callResult;
            }
        }
    }
    /**
     * Ajax 값 전달
     * @Param str {String}
     * @Return {String}
     */
    this.setSend = function (str){
        if(str == null || str == "undefined" || !str){
            objectXmlHttp.send(null);
        } else {
            //오류가 있을려나 몰라도(미완료)
            try{
                objectXmlHttp.send(str);
            } catch(e){
                objectXmlHttp.send(null);
            }
        }
        return objectResultString;
    }
    /**
     * Ajax로 Check결과 반환 함수
     * @Param furl {String}
     * @Return {String}
     */
    this.callCheck = function (furl,mode){
        objectResultString = null;
        /*
        if(!objectXmlHttp || objectXmlHttp == null){
            objectResultString = "Ajax가 지원되지 않는 브라우저 입니다.\n\n자세한 내용은 관리자에게 문의하세요";
            return objectResultString;
        }
        if(!furl){
            objectResultString = "URL이 지정되지 않았습니다.";
            return objectResultString;
        }
        if(mode == null || mode == "undefined" || !mode){
            mode = "GET";
        }
        objectXmlHttp.open(mode, furl,false); //GET, POST, PUT / true : 비동기방식
        objectXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
        objectXmlHttp.onreadystatechange = this.callResult;
        objectXmlHttp.send(null);
        */
        this.setOpen(furl,mode);
        this.setCharset("UTF-8");
        this.setEvent();
        this.setSend();
        return objectResultString;
    }
    /**
     * Ajax 결과 처리
     * @Return {String}
     */
    this.callResult = function (){
        //objectResultString = null;
        if(objectXmlHttp.readyState == 4){ //서버로부터 응답이 도착한 것을 확인
            //alert(objectXmlHttp.responseText);
            if(objectXmlHttp.status == 200){ //서버가 요청을 올바르게 수행했는지를 확인
                objectResultString = objectXmlHttp.responseText;
            } else {
                objectResultString = objectXmlHttp.status + " : " + objectXmlHttp.statusText;
            }
        } else {
            objectResultString = null;
        }
    }
}
