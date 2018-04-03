var xid;
var xurl;
var xmlhttp;
var xxp;
function asciiConvertNative(str) {
    var asciicode = str.split("\\u");
    var nativeValue = asciicode[0];
    for (var i = 1; i < asciicode.length; i++) {
        var code = asciicode[i];
        nativeValue += String.fromCharCode(parseInt("0x" + code.substring(0, 4)));
        if (code.length > 4) {
            nativeValue += code.substring(4, code.length);
        }
    }
    return nativeValue;
}
function loadXMLDoc(url,id)
{
xmlhttp=null;
xid=id;
xurl=url;
if (window.XMLHttpRequest)
  {// code for IE7, Firefox, Opera, etc.
  xmlhttp=new XMLHttpRequest();
  }
else if (window.ActiveXObject)
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
if (xmlhttp!=null)
  {
  xmlhttp.onreadystatechange=state_Change;
  xmlhttp.open("GET",url,true);
  xmlhttp.send(null);
  }
else
  {
  alert("Your browser does not support XMLHTTP.");
  }
}

function state_Change()
{
if (xmlhttp.readyState==4)
  {// 4 = "loaded"
  if (xmlhttp.status==200)
    {// 200 = "OK"
  document.getElementById(xid).innerHTML=asciiConvertNative(xmlhttp.responseText).replace(/\n/g,'<br/>');
    }
  else
    {
	document.getElementById(xid).innerHTML='<div style="padding:10px;margin:5px;border:1px solid #ccc" align="center">Not Found&nbsp;<a href="javascript:;" onclick="loadXMLDoc(xurl,xid);">Again</a></div>';
   }
  }
}