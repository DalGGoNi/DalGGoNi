<!DOCTYPE html> 
<html lang="en">
<head> 
<meta charset="UTF-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>SummernoteTest</title> 
<!-- include libraries(jQuery, bootstrap) --> 
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"> 
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js">
</script> 
<!-- include summernote css/js --> 
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet"> 
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-ko-KR.js"></script> 
<style> 
@font-face { 
font-family: 'NotoSansKR'; 
src: url('font/NotoSansKR-Black.otf') format('opentype') 
} 
</style> 
<script> 
$(document).ready(function() { 
var fontList = ['맑은 고딕','굴림','돋움','바탕','궁서','NotoSansKR','Arial','Courier New','Verdana','Tahoma','Times New Roamn'];
 $('#summernote').summernote({ 
 lang: 'ko-KR', 
 height: 100, 
 fontNames: fontList, 
 fontNamesIgnoreCheck: fontList, 
 fontSizes: ['8','9','10','11','12','14','18','24','36'],
  toolbar: [ 
  ['font', ['fontname','fontsize','fontsizeunit']], 
  ['fontstyle', ['bold','italic','underline','strikethrough','forecolor','backcolor','superscript','subscript','clear']], 
  ['style', ['style']], 
  ['paragraph', ['paragraph','height','ul','ol']], 
  ['insert', ['table','hr','link','picture','video']], 
  ['codeview'], 
  ],
  }); 
  // $('#summernote').summernote('fontName', '맑은 고딕'); 
  // $('#summernote').summernote('fontSize', 11); 
  // $('#summernote').summernote('fontSizeUnit', 'pt'); 
  }); 
  </script> 
  </head> 
  <body> 
  <textarea id="summernote">Summernote 테스트</textarea> 
  </body> 
  </html>

