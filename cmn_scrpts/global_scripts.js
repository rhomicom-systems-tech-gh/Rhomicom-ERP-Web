Number.prototype.padLeft = function (base,chr) {
  var len = (String(base || 10).length - String(this).length) + 1;
  return len > 0 ? new Array(len).join(chr || '0') + this : this;
};
$(document).on("hidden.bs.modal",".bootbox.modal",function (e) {
  $("body").css("padding","0px 0px 0px 0px");
});
$(document).on("change",".form_date input",function (e) {
  $("body").css("overflow","auto");
});

function isMyScriptLoaded(url) {
  var scripts = document.getElementsByTagName('script');
  for (var i = scripts.length; i--;) {
    var cururl = scripts[i].src;
    if (cururl.indexOf(url) >= 0) {
      return true;
    }
  }
  return false;
}

function isMyCssLoaded(url) {
  var scripts = document.getElementsByTagName('link');
  for (var i = scripts.length; i--;) {
    var cururl = scripts[i].href;
    if (cururl.indexOf(url) >= 0) {
      return true;
    }
  }
  return false;
}

function isMobileNumValid(mobileNum) {
  if (/^\+?[1-9]\d{4,14}$/.test(mobileNum)) {
    return (true);
  }
  return (false);
  /* var regex = /^\+(?:[0-9] ?){6,14}[0-9]$/;*/
}

function isEmailValid(email) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
    return (true);
  }
  return (false);
  /*var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   return re.test(email);*/
}

function getTtlRows(tblID) {
  var cntr = 0;
  $('#' + tblID).find('tr').each(function (i,el) {
    if (i > 0) {
      cntr++;
    }
  });
  return cntr;
}

function clearTblRows(tblID) {
  var cntr = 0;
  $('#' + tblID).find('tr').each(function (i,el) {
    if (i > 0) {
      /*var rowIDAttrb = $(el).attr('id');
       $("#" + rowIDAttrb).remove();*/
      $(el).remove();
    }
  });
}

function getRowIndx(rowIDAttrb,tblID) {
  var indx = 0;
  $('#' + tblID).find('tr').each(function (i,el) {
    if (i > 0 && $(el).attr('id') === rowIDAttrb) {
      indx = i;
    }
  });
  return indx;
}

function getFreeRowNum(freePKeyRowSffx,tblID) {
  var indx = 0;
  var rwNumber = "";
  $('#' + tblID).find('tr').each(function (i,el) {
    if (i > 0) {
      if (typeof $(el).attr('id') === 'undefined') {
        /*Do Nothing*/
      } else {
        var prfx = $(el).attr('id').split("_")[0];
        var rndmNum = $(el).attr('id').split("_")[1];
        var ln_TrnsLnID = typeof $('#' + prfx + rndmNum + freePKeyRowSffx).val() === 'undefined' ? '-1' : $('#' + prfx + rndmNum + freePKeyRowSffx).val();
        if (Number(ln_TrnsLnID.replace(/[^-?0-9\.]/g,'')) <= 0) {
          indx = i;
          rwNumber = rndmNum;
        }
      }
    }
  });
  return rwNumber;
}

function addCommas(nStr) {
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(-?\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx,'$1' + ',' + '$2');
  }
  return x1 + x2;
}

function checkAllBtns(elmntID) {
  var form = document.getElementById(elmntID);

  for (var i = 0; i < form.elements.length; i++) {
    if (form.elements[i].type === 'checkbox') {
      form.elements[i].checked = true;
    }
  }
}

function unCheckAllBtns(elmntID) {
  var form = document.getElementById(elmntID);

  for (var i = 0; i < form.elements.length; i++) {
    if (form.elements[i].type === 'checkbox') {
      form.elements[i].checked = false;
    }
  }
}

function changeImgSrc(input,imgId,imgSrcLocID) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $(imgId).attr('src',e.target.result);
      $(imgSrcLocID).attr('value',$(input).val());
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function setFileLoc(input,nwSrcLocElmnt) {
  if (input.files && input.files[0]) {
    $(nwSrcLocElmnt).attr('value',$(input).val());
  }
}

function scrollTo(hash) {
  location.hash = "#" + hash;
}

function rhotrim(s,mask) {
  while (~mask.indexOf(s[0])) {
    s = s.slice(1);
  }
  while (~mask.indexOf(s[s.length - 1])) {
    s = s.slice(0,-1);
  }
  return s;
}

function rhoFrmtDate() {
  var m_names = new Array("Jan","Feb","Mar",
    "Apr","May","Jun","Jul","Aug","Sep",
    "Oct","Nov","Dec");

  var d = new Date();
  var curr_date = d.getDate().padLeft();
  var curr_month = d.getMonth();
  var curr_year = d.getFullYear();
  var first_part = curr_date + "-" + m_names[curr_month]
    + "-" + curr_year;

  var dformat = first_part + ' ' +
    [d.getHours().padLeft(),
    d.getMinutes().padLeft(),
    d.getSeconds().padLeft()].join(':');
  return dformat;
}


function isReaderAPIAvlbl() {
  if (window.File && window.FileReader && window.FileList && window.Blob) {
    return true;
  } else {
    var errMsg = "";
    errMsg += 'The HTML5 APIs used in this form are only available in the following browsers:<br />';
    errMsg += ' - Google Chrome: 13.0 or later<br />';
    errMsg += ' - Mozilla Firefox: 6.0 or later<br />';
    errMsg += ' - Internet Explorer: Not supported (partial support expected in 10.0)<br />';
    errMsg += ' - Safari: Not supported<br />';
    errMsg += ' - Opera: Not supported';
    var dialog = bootbox.alert({
      title: 'System Alert',
      size: 'small',
      message: '<p style="color:red;font-weight:bold;">' + errMsg + '</p>',
      callback: function () {
      }
    });
    return false;
  }
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function isInt(n) {
  return Number(n) === n && n % 1 === 0;
}

function isFloat(n) {
  return Number(n) === n && n % 1 !== 0;
}

function fmtAsNumber(elementID) {
  var num = $("#" + elementID).val();
  $("#" + elementID).val(addCommas(Number(num.replace(/[^-?0-9\.]/g,'')).toFixed(2)));
  return Number(num.replace(/[^-?0-9\.]/g,''));
}

function fmtAsNumber2(elementID) {
  var num = $("#" + elementID).val();
  $("#" + elementID).val(addCommas(Number(num.replace(/[^-?0-9\.]/g,'')).toFixed(4)));
  return Number(num.replace(/[^-?0-9\.]/g,''));
}

function newLoanRequestOpenATab(slctr,linkArgs) {
  openATab(slctr,linkArgs + "&lnprflFctrId=" + $("#lnprflFctrId").val() + "&lnRqstID=" + $("#lnRqstID").val() +
    "&optn=" + $("#optn").val() + "&status=" + $("#status").val() + "&assmntCmnts=" + $("#assmntCmnts").val());
}

function ClearAllIntervals() {
  for (var i = 1; i < 99999; i++)
    window.clearInterval(i);
}

function gnrlFldKeyPress(event,elementIDAttrb,sbmtdTblRowID,classNm) {
  if (event.which === 13) {
    var nextItem;
    var nextItemVal = 0;
    var curItemVal = 0;
    var indx = 0;
    var ttlElmnts = 0;
    $('#' + sbmtdTblRowID + ' .' + classNm).each(function (i,el) {
      ttlElmnts++;
      if ($(el).attr('id') === elementIDAttrb) {
        indx = i;
      }
    });
    curItemVal = indx + 1;
    if (curItemVal >= ttlElmnts) {
      nextItem = $('#' + sbmtdTblRowID + ' .' + classNm).eq(0);
    } else {
      nextItemVal = Number(curItemVal);
      nextItem = $('#' + sbmtdTblRowID + ' .' + classNm).eq(nextItemVal);
    }
    nextItem.focus();
  }
}

function disableBtnFunc(btnElementID) {
  document.getElementById(btnElementID).disabled = 'true';
}
function enableBtnFunc(btnElementID) {
  document.getElementById(btnElementID).disabled = 'false';
}

function changeElmntTitleFunc(htmElementID) {
  $('[data-toggle="tooltip"]').tooltip('destroy');
  var titleTxt = $("#" + htmElementID).val();
  document.getElementById(htmElementID).title = titleTxt;

  $("body").tooltip({ selector: '[data-toggle=tooltip]' });
}

function changeBtnTitleFunc(htmElementID,btnElementID) {
  $('[data-toggle="tooltip"]').tooltip('destroy');
  var titleTxt = $("#" + htmElementID).val();
  document.getElementById(btnElementID).title = titleTxt;

  $("body").tooltip({ selector: '[data-toggle=tooltip]' });
}

function startDashBoardRpts() {
  var linkArgs = 'grp=40&typ=401';

  $body = $("body");
  var xmlhttp;
  if (window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange = function () {
    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
      console.log("Report Run Finished");
    }
  };
  xmlhttp.open("POST","index.php",true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send(linkArgs.trim());
  console.log("Report Run Started");
  quickStartMainRunner();
}

function funcHtmlToExcel(tableid) {
  var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
  var isFirefox = typeof InstallTrigger !== 'undefined' || navigator.userAgent.indexOf(' Firefox/') >= 0;
  var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
    return p.toString() === "[object SafariRemoteNotification]";
  })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification)) || (navigator.userAgent.indexOf(' Safari/') >= 0 && navigator.vendor.indexOf('Apple') >= 0);
  var isIE = false || !!document.documentMode || navigator.userAgent.indexOf(' Trident/') >= 0;
  var isEdge = (!isIE && !!window.StyleMedia) || navigator.userAgent.indexOf(' Edge/') >= 0;
  var isChrome = (!!window.chrome) || (navigator.userAgent.indexOf(' Chrome/') >= 0 && navigator.vendor.indexOf('Google') >= 0);
  var isBlink = (isChrome || isOpera) && !!window.CSS;
  var dt = new Date();
  var day = dt.getDate();
  var month = dt.getMonth() + 1;
  var year = dt.getFullYear();
  var hour = dt.getHours();
  var mins = dt.getMinutes();
  var secns = dt.getSeconds();
  var postfix = day + "." + month + "." + year + "_" + hour + "." + mins + "." + secns + "_" + getRandomInt2(100,999);
  var myURL = window.URL || window.webkitURL;
  if (isChrome || isFirefox || isEdge || isBlink) {
    var a = document.getElementById('allOtherATag1');
    var data_type = 'data:application/vnd.ms-excel';

    var table_div = document.getElementById(tableid);
    var cptnTxt = $("caption").text();
    var tab_text = "<table border='2px'><caption style=\"font-weight:bold;font-size: 18px;\">" + cptnTxt + "</caption><tr bgcolor='#87AFC6'>";
    var textRange;
    var j = 0;
    tab = document.getElementById(tableid);


    for (j = 0; j < tab.rows.length; j++) {
      if (j === 0) {
        tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
      } else {
        tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
      }
    }

    tab_text = tab_text + "</table>";
    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g,"");
    tab_text = tab_text.replace(/<a[^>]*>|<\/a>/g,"").replace(/Â»/g,"");
    tab_text = tab_text.replace(/<img[^>]*>/gi,"");
    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi,"");

    var binaryData = [];
    binaryData.push(tab_text);
    var url = myURL.createObjectURL(new Blob(binaryData,{ type: "data:application/vnd.ms-excel" }));
    var table_html = url;
    a.href = table_html;
    a.download = 'exported_table_' + postfix + '.xls';
    a.click();
  } else {
    fnExcelReport(tableid,'exported_table_' + postfix);
  }
  return false;
}

function fnExcelReport(tableid,fileNm) {
  var x = document.getElementById('allOtherIframe1');
  var frame = (x.contentWindow || x.contentDocument);
  var cptnTxt = $("caption").text();
  var tab_text = "<table border='2px'><caption style=\"font-weight:bold;font-size: 18px;\">" + cptnTxt + "</caption><tr bgcolor='#87AFC6'>";
  var textRange;
  var j = 0;
  tab = document.getElementById(tableid);

  for (j = 0; j < tab.rows.length; j++) {
    if (j === 0) {
      tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
    } else {
      tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
    }
  }

  tab_text = tab_text + "</table>";
  tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g,"");
  tab_text = tab_text.replace(/<a[^>]*>|<\/a>/g,"").replace(/Â»/g,"");
  tab_text = tab_text.replace(/<img[^>]*>/gi,"");
  tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi,"");

  var ua = window.navigator.userAgent;
  var msie = ua.indexOf("MSIE ");
  if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./) || navigator.userAgent.indexOf(' Trident/') >= 0 || navigator.userAgent.indexOf(' Edge/') >= 0) {
    frame.document.open("txt/html","replace");
    frame.document.write(tab_text);
    frame.document.close();
    frame.focus();
    sa = frame.document.execCommand("SaveAs",true,fileNm + ".xls");
  } else {
    sa = generateHtmlExcel(tableid);
  }
  return (sa);
}

function generateHtmlExcel(tableid) {
  var cptnTxt = $("caption").text();
  var tab_text = "<table border='2px'><caption style=\"font-weight:bold;font-size: 18px;\">" + cptnTxt + "</caption><tr bgcolor='#87AFC6'>";
  var textRange;
  var j = 0;
  tab = document.getElementById(tableid);


  for (j = 0; j < tab.rows.length; j++) {
    if (j === 0) {
      tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
    } else {
      tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
    }
  }

  tab_text = tab_text + "</table>";
  tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g,"");
  tab_text = tab_text.replace(/<a[^>]*>|<\/a>/g,"").replace(/Â»/g,"");
  tab_text = tab_text.replace(/<img[^>]*>/gi,"");
  tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi,"");
  /*table.outerHTML*/
  var myURL = window.URL || window.webkitURL;
  var table = document.getElementById(tableid);
  var html = tab_text;
  var binaryData = [];
  binaryData.push(html);
  var url = myURL.createObjectURL(new Blob(binaryData,{ type: "data:application/vnd.ms-excel" }));
  return window.open(url);
}

function getRandomInt1(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

function getRandomInt2(min,max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min)) + min;
}

function rhovoid() {
  return false;
}

function escapeRegExp(str) {
  return str.replace(/[.*+?^${}()|[\]\\]/g,"\\$&");
}

function tickUntickChckBx(chckBxElmnt) {
  $("input[name='" + chckBxElmnt + "']").trigger("click");
}

String.prototype.replaceAll = function (search,replacement) {
  var target = this;
  return target.replace(new RegExp(escapeRegExp(search),'g'),replacement);
};


jQuery.fn.ForceNumericOnly =
  function () {
    return this.each(function () {
      $(this).keydown(function (e) {
        var key = e.charCode || e.keyCode || 0;
        return (
          key == 8 ||
          key == 9 ||
          key == 13 ||
          key == 46 ||
          key == 110 ||
          key == 190 ||
          (key >= 35 && key <= 40) ||
          (key >= 48 && key <= 57) ||
          (key >= 96 && key <= 105));
      });
    });
  };


/*
PDFObject v2.1.1
https://github.com/pipwerks/PDFObject
Copyright (c) 2008-2018 Philip Hutchison
MIT-style license: http://pipwerks.mit-license.org/
UMD module pattern from https://github.com/umdjs/umd/blob/master/templates/returnExports.js
*/

(function (root,factory) { if (typeof define === 'function' && define.amd) { define([],factory); } else if (typeof module === 'object' && module.exports) { module.exports = factory(); } else { root.PDFObject = factory(); } }(this,function () {
  "use strict"; if (typeof window === "undefined" || typeof navigator === "undefined") { return false; }
  var pdfobjectversion = "2.1.1",ua = window.navigator.userAgent,supportsPDFs,isIE,supportsPdfMimeType = (typeof navigator.mimeTypes['application/pdf'] !== "undefined"),supportsPdfActiveX,isModernBrowser = (function () { return (typeof window.Promise !== "undefined"); })(),isFirefox = (function () { return (ua.indexOf("irefox") !== -1); })(),isFirefoxWithPDFJS = (function () {
    if (!isFirefox) { return false; }
    return (parseInt(ua.split("rv:")[1].split(".")[0],10) > 18);
  })(),isIOS = (function () { return (/iphone|ipad|ipod/i.test(ua.toLowerCase())); })(),createAXO,buildFragmentString,log,embedError,embed,getTargetElement,generatePDFJSiframe,generateEmbedElement; createAXO = function (type) {
    var ax; try { ax = new ActiveXObject(type); } catch (e) { ax = null; }
    return ax;
  }; isIE = function () { return !!(window.ActiveXObject || "ActiveXObject" in window); }; supportsPdfActiveX = function () { return !!(createAXO("AcroPDF.PDF") || createAXO("PDF.PdfCtrl")); }; supportsPDFs = (!isIOS && (isFirefoxWithPDFJS || supportsPdfMimeType || (isIE() && supportsPdfActiveX()))); buildFragmentString = function (pdfParams) {
    var string = "",prop; if (pdfParams) {
      for (prop in pdfParams) { if (pdfParams.hasOwnProperty(prop)) { string += encodeURIComponent(prop) + "=" + encodeURIComponent(pdfParams[prop]) + "&"; } }
      if (string) { string = "#" + string; string = string.slice(0,string.length - 1); }
    }
    return string;
  }; log = function (msg) { if (typeof console !== "undefined" && console.log) { console.log("[PDFObject] " + msg); } }; embedError = function (msg) { log(msg); return false; }; getTargetElement = function (targetSelector) {
    var targetNode = document.body; if (typeof targetSelector === "string") { targetNode = document.querySelector(targetSelector); } else if (typeof jQuery !== "undefined" && targetSelector instanceof jQuery && targetSelector.length) { targetNode = targetSelector.get(0); } else if (typeof targetSelector.nodeType !== "undefined" && targetSelector.nodeType === 1) { targetNode = targetSelector; }
    return targetNode;
  }; generatePDFJSiframe = function (targetNode,url,pdfOpenFragment,PDFJS_URL,id) { var fullURL = PDFJS_URL + "?file=" + encodeURIComponent(url) + pdfOpenFragment; var scrollfix = (isIOS) ? "-webkit-overflow-scrolling: touch; overflow-y: scroll; " : "overflow: hidden; "; var iframe = "<div style='" + scrollfix + "position: absolute; top: 0; right: 0; bottom: 0; left: 0;'><iframe  " + id + " src='" + fullURL + "' style='border: none; width: 100%; height: 100%;' frameborder='0'></iframe></div>"; targetNode.className += " pdfobject-container"; targetNode.style.position = "relative"; targetNode.style.overflow = "auto"; targetNode.innerHTML = iframe; return targetNode.getElementsByTagName("iframe")[0]; }; generateEmbedElement = function (targetNode,targetSelector,url,pdfOpenFragment,width,height,id) {
    var style = ""; if (targetSelector && targetSelector !== document.body) { style = "width: " + width + "; height: " + height + ";"; } else { style = "position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; height: 100%;"; }
    targetNode.className += " pdfobject-container"; targetNode.innerHTML = "<embed " + id + " class='pdfobject' src='" + url + pdfOpenFragment + "' type='application/pdf' style='overflow: auto; " + style + "'/>"; return targetNode.getElementsByTagName("embed")[0];
  }; embed = function (url,targetSelector,options) {
    if (typeof url !== "string") { return embedError("URL is not valid"); }
    targetSelector = (typeof targetSelector !== "undefined") ? targetSelector : false; options = (typeof options !== "undefined") ? options : {}; var id = (options.id && typeof options.id === "string") ? "id='" + options.id + "'" : "",page = (options.page) ? options.page : false,pdfOpenParams = (options.pdfOpenParams) ? options.pdfOpenParams : {},fallbackLink = (typeof options.fallbackLink !== "undefined") ? options.fallbackLink : true,width = (options.width) ? options.width : "100%",height = (options.height) ? options.height : "100%",assumptionMode = (typeof options.assumptionMode === "boolean") ? options.assumptionMode : true,forcePDFJS = (typeof options.forcePDFJS === "boolean") ? options.forcePDFJS : false,PDFJS_URL = (options.PDFJS_URL) ? options.PDFJS_URL : false,targetNode = getTargetElement(targetSelector),fallbackHTML = "",pdfOpenFragment = "",fallbackHTML_default = "<p>This browser does not support inline PDFs. Please download the PDF to view it: <a href='[url]'>Download PDF</a></p>"; if (!targetNode) { return embedError("Target element cannot be determined"); }
    if (page) { pdfOpenParams.page = page; }
    pdfOpenFragment = buildFragmentString(pdfOpenParams); if (forcePDFJS && PDFJS_URL) { return generatePDFJSiframe(targetNode,url,pdfOpenFragment,PDFJS_URL,id); } else if (supportsPDFs || (assumptionMode && isModernBrowser && !isIOS)) { return generateEmbedElement(targetNode,targetSelector,url,pdfOpenFragment,width,height,id); } else if (PDFJS_URL) { return generatePDFJSiframe(targetNode,url,pdfOpenFragment,PDFJS_URL,id); } else {
      if (fallbackLink) { fallbackHTML = (typeof fallbackLink === "string") ? fallbackLink : fallbackHTML_default; targetNode.innerHTML = fallbackHTML.replace(/\[url\]/g,url); }
      return embedError("This browser does not support embedded PDFs");
    }
  }; return { embed: function (a,b,c) { return embed(a,b,c); },pdfobjectversion: (function () { return pdfobjectversion; })(),supportsPDFs: (function () { return supportsPDFs; })() };
}));



/**
 * jQuery-csv (jQuery Plugin)
 * version: 0.70 (2012-11-04)
 *
 * This document is licensed as free software under the terms of the
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * Acknowledgements:
 * The original design and influence to implement this library as a jquery
 * plugin is influenced by jquery-json (http://code.google.com/p/jquery-json/).
 * If you're looking to use native JSON.Stringify but want additional backwards
 * compatibility for browsers that don't support it, I highly recommend you
 * check it out.
 *
 * A special thanks goes out to rwk@acm.org for providing a lot of valuable
 * feedback to the project including the core for the new FSM
 * (Finite State Machine) parsers. If you're looking for a stable TSV parser
 * be sure to take a look at jquery-tsv (http://code.google.com/p/jquery-tsv/).

 * For legal purposes I'll include the "NO WARRANTY EXPRESSED OR IMPLIED.
 * USE AT YOUR OWN RISK.". Which, in 'layman's terms' means, by using this
 * library you are accepting responsibility if it breaks your code.
 *
 * Legal jargon aside, I will do my best to provide a useful and stable core
 * that can effectively be built on.
 *
 * Copyrighted 2012 by Evan Plaice.
 */

RegExp.escape = function (s) {
  return s.replace(/[-\/\\^$*+?.()|[\]{}]/g,'\\$&');
};

(function (undefined) {
  'use strict';

  var $;

  if (typeof jQuery !== 'undefined' && jQuery) {
    $ = jQuery;
  } else {
    $ = {};
  }


  /**
   * jQuery.csv.defaults
   * Encapsulates the method paramater defaults for the CSV plugin module.
   */

  $.csv = {
    defaults: {
      separator: ',',
      delimiter: '"',
      headers: true
    },

    hooks: {
      castToScalar: function (value,state) {
        var hasDot = /\./;
        if (isNaN(value)) {
          return value;
        } else {
          if (hasDot.test(value)) {
            return parseFloat(value);
          } else {
            var integer = parseInt(value);
            if (isNaN(integer)) {
              return null;
            } else {
              return integer;
            }
          }
        }
      }
    },

    parsers: {
      parse: function (csv,options) {
        var separator = options.separator;
        var delimiter = options.delimiter;
        if (!options.state.rowNum) {
          options.state.rowNum = 1;
        }
        if (!options.state.colNum) {
          options.state.colNum = 1;
        }
        var data = [];
        var entry = [];
        var state = 0;
        var value = '';
        var exit = false;

        function endOfEntry() {
          state = 0;
          value = '';

          if (options.start && options.state.rowNum < options.start) {
            entry = [];
            options.state.rowNum++;
            options.state.colNum = 1;
            return;
          }

          if (options.onParseEntry === undefined) {
            data.push(entry);
          } else {
            var hookVal = options.onParseEntry(entry,options.state);
            if (hookVal !== false) {
              data.push(hookVal);
            }
          }
          entry = [];
          if (options.end && options.state.rowNum >= options.end) {
            exit = true;
          }
          options.state.rowNum++;
          options.state.colNum = 1;
        }

        function endOfValue() {
          if (options.onParseValue === undefined) {
            entry.push(value);
          } else {
            var hook = options.onParseValue(value,options.state);
            if (hook !== false) {
              entry.push(hook);
            }
          }
          value = '';
          state = 0;
          options.state.colNum++;
        }

        var escSeparator = RegExp.escape(separator);
        var escDelimiter = RegExp.escape(delimiter);
        var match = /(D|S|\r\n|\n|\r|[^DS\r\n]+)/;
        var matchSrc = match.source;
        matchSrc = matchSrc.replace(/S/g,escSeparator);
        matchSrc = matchSrc.replace(/D/g,escDelimiter);
        match = new RegExp(matchSrc,'gm');
        csv.replace(match,function (m0) {
          if (exit) {
            return;
          }
          switch (state) {
            case 0:
              if (m0 === separator) {
                value += '';
                endOfValue();
                break;
              }
              if (m0 === delimiter) {
                state = 1;
                break;
              }
              if (/^(\r\n|\n|\r)$/.test(m0)) {
                endOfValue();
                endOfEntry();
                break;
              }
              value += m0;
              state = 3;
              break;
            case 1:
              if (m0 === delimiter) {
                state = 2;
                break;
              }
              value += m0;
              state = 1;
              break;
            case 2:
              if (m0 === delimiter) {
                value += m0;
                state = 1;
                break;
              }
              if (m0 === separator) {
                endOfValue();
                break;
              }
              if (/^(\r\n|\n|\r)$/.test(m0)) {
                endOfValue();
                endOfEntry();
                break;
              }
              throw new Error('CSVDataError: Illegal State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
            case 3:
              if (m0 === separator) {
                endOfValue();
                break;
              }
              if (/^(\r\n|\n|\r)$/.test(m0)) {
                endOfValue();
                endOfEntry();
                break;
              }
              if (m0 === delimiter) {
                throw new Error('CSVDataError: Illegal Quote [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
              }
              throw new Error('CSVDataError: Illegal Data [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
            default:
              throw new Error('CSVDataError: Unknown State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
          }
        });
        if (entry.length !== 0) {
          endOfValue();
          endOfEntry();
        }

        return data;
      },
      splitLines: function (csv,options) {
        var separator = options.separator;
        var delimiter = options.delimiter;
        if (!options.state.rowNum) {
          options.state.rowNum = 1;
        }
        var entries = [];
        var state = 0;
        var entry = '';
        var exit = false;

        function endOfLine() {
          state = 0;
          if (options.start && options.state.rowNum < options.start) {
            entry = '';
            options.state.rowNum++;
            return;
          }

          if (options.onParseEntry === undefined) {
            entries.push(entry);
          } else {
            var hookVal = options.onParseEntry(entry,options.state);
            if (hookVal !== false) {
              entries.push(hookVal);
            }
          }
          entry = '';
          if (options.end && options.state.rowNum >= options.end) {
            exit = true;
          }
          options.state.rowNum++;
        }

        var escSeparator = RegExp.escape(separator);
        var escDelimiter = RegExp.escape(delimiter);
        var match = /(D|S|\n|\r|[^DS\r\n]+)/;
        var matchSrc = match.source;
        matchSrc = matchSrc.replace(/S/g,escSeparator);
        matchSrc = matchSrc.replace(/D/g,escDelimiter);
        match = new RegExp(matchSrc,'gm');
        csv.replace(match,function (m0) {
          if (exit) {
            return;
          }
          switch (state) {
            case 0:
              if (m0 === separator) {
                entry += m0;
                state = 0;
                break;
              }
              if (m0 === delimiter) {
                entry += m0;
                state = 1;
                break;
              }
              if (m0 === '\n') {
                endOfLine();
                break;
              }
              if (/^\r$/.test(m0)) {
                break;
              }
              entry += m0;
              state = 3;
              break;
            case 1:
              if (m0 === delimiter) {
                entry += m0;
                state = 2;
                break;
              }
              entry += m0;
              state = 1;
              break;

            case 2:
              var prevChar = entry.substr(entry.length - 1);
              if (m0 === delimiter && prevChar === delimiter) {
                entry += m0;
                state = 1;
                break;
              }
              if (m0 === separator) {
                entry += m0;
                state = 0;
                break;
              }
              if (m0 === '\n') {
                endOfLine();
                break;
              }
              if (m0 === '\r') {
                break;
              }
              throw new Error('CSVDataError: Illegal state [Row:' + options.state.rowNum + ']');
            case 3:
              if (m0 === separator) {
                entry += m0;
                state = 0;
                break;
              }
              if (m0 === '\n') {
                endOfLine();
                break;
              }
              if (m0 === '\r') {
                break;
              }
              if (m0 === delimiter) {
                throw new Error('CSVDataError: Illegal quote [Row:' + options.state.rowNum + ']');
              }
              throw new Error('CSVDataError: Illegal state [Row:' + options.state.rowNum + ']');
            default:
              throw new Error('CSVDataError: Unknown state [Row:' + options.state.rowNum + ']');
          }
        });
        if (entry !== '') {
          endOfLine();
        }

        return entries;
      },

      parseEntry: function (csv,options) {
        var separator = options.separator;
        var delimiter = options.delimiter;
        if (!options.state.rowNum) {
          options.state.rowNum = 1;
        }
        if (!options.state.colNum) {
          options.state.colNum = 1;
        }
        var entry = [];
        var state = 0;
        var value = '';

        function endOfValue() {
          if (options.onParseValue === undefined) {
            entry.push(value);
          } else {
            var hook = options.onParseValue(value,options.state);
            if (hook !== false) {
              entry.push(hook);
            }
          }
          value = '';
          state = 0;
          options.state.colNum++;
        }
        if (!options.match) {
          var escSeparator = RegExp.escape(separator);
          var escDelimiter = RegExp.escape(delimiter);
          var match = /(D|S|\n|\r|[^DS\r\n]+)/;
          var matchSrc = match.source;
          matchSrc = matchSrc.replace(/S/g,escSeparator);
          matchSrc = matchSrc.replace(/D/g,escDelimiter);
          options.match = new RegExp(matchSrc,'gm');
        }
        csv.replace(options.match,function (m0) {
          switch (state) {
            case 0:
              if (m0 === separator) {
                value += '';
                endOfValue();
                break;
              }
              if (m0 === delimiter) {
                state = 1;
                break;
              }
              if (m0 === '\n' || m0 === '\r') {
                break;
              }
              value += m0;
              state = 3;
              break;
            case 1:
              if (m0 === delimiter) {
                state = 2;
                break;
              }
              value += m0;
              state = 1;
              break;
            case 2:
              if (m0 === delimiter) {
                value += m0;
                state = 1;
                break;
              }
              if (m0 === separator) {
                endOfValue();
                break;
              }
              if (m0 === '\n' || m0 === '\r') {
                break;
              }
              throw new Error('CSVDataError: Illegal State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');

            case 3:
              if (m0 === separator) {
                endOfValue();
                break;
              }
              if (m0 === '\n' || m0 === '\r') {
                break;
              }
              if (m0 === delimiter) {
                throw new Error('CSVDataError: Illegal Quote [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
              }
              throw new Error('CSVDataError: Illegal Data [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
            default:
              throw new Error('CSVDataError: Unknown State [Row:' + options.state.rowNum + '][Col:' + options.state.colNum + ']');
          }
        });

        endOfValue();

        return entry;
      }
    },

    helpers: {

      /**
       * $.csv.helpers.collectPropertyNames(objectsArray)
       * Collects all unique property names from all passed objects.
       *
       * @param {Array} objects Objects to collect properties from.
       *
       * Returns an array of property names (array will be empty,
       * if objects have no own properties).
       */
      collectPropertyNames: function (objects) {

        var o,propName,props = [];
        for (o in objects) {
          for (propName in objects[o]) {
            if ((objects[o].hasOwnProperty(propName)) &&
              (props.indexOf(propName) < 0) &&
              (typeof objects[o][propName] !== 'function')) {

              props.push(propName);
            }
          }
        }
        return props;
      }
    },

    /**
     * $.csv.toArray(csv)
     * Converts a CSV entry string to a javascript array.
     *
     * @param {Array} csv The string containing the CSV data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     *
     * This method deals with simple CSV strings only. It's useful if you only
     * need to parse a single entry. If you need to parse more than one line,
     * use $.csv2Array instead.
     */
    toArray: function (csv,options,callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof (callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;
      var state = (options.state !== undefined ? options.state : {});

      options = {
        delimiter: config.delimiter,
        separator: config.separator,
        onParseEntry: options.onParseEntry,
        onParseValue: options.onParseValue,
        state: state
      };

      var entry = $.csv.parsers.parseEntry(csv,options);

      if (!config.callback) {
        return entry;
      } else {
        config.callback('',entry);
      }
    },

    /**
     * $.csv.toArrays(csv)
     * Converts a CSV string to a javascript array.
     *
     * @param {String} csv The string containing the raw CSV data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     *
     * This method deals with multi-line CSV. The breakdown is simple. The first
     * dimension of the array represents the line (or entry/row) while the second
     * dimension contains the values (or values/columns).
     */
    toArrays: function (csv,options,callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof (callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;

      var data = [];
      options = {
        delimiter: config.delimiter,
        separator: config.separator,
        onPreParse: options.onPreParse,
        onParseEntry: options.onParseEntry,
        onParseValue: options.onParseValue,
        onPostParse: options.onPostParse,
        start: options.start,
        end: options.end,
        state: {
          rowNum: 1,
          colNum: 1
        }
      };

      if (options.onPreParse !== undefined) {
        options.onPreParse(csv,options.state);
      }

      data = $.csv.parsers.parse(csv,options);

      if (options.onPostParse !== undefined) {
        options.onPostParse(data,options.state);
      }

      if (!config.callback) {
        return data;
      } else {
        config.callback('',data);
      }
    },

    /**
     * $.csv.toObjects(csv)
     * Converts a CSV string to a javascript object.
     * @param {String} csv The string containing the raw CSV data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     * @param {Boolean} [headers] Indicates whether the data contains a header line. Defaults to true.
     *
     * This method deals with multi-line CSV strings. Where the headers line is
     * used as the key for each value per entry.
     */
    toObjects: function (csv,options,callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof (callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;
      config.headers = 'headers' in options ? options.headers : $.csv.defaults.headers;
      options.start = 'start' in options ? options.start : 1;

      if (config.headers) {
        options.start++;
      }
      if (options.end && config.headers) {
        options.end++;
      }

      var lines = [];
      var data = [];

      options = {
        delimiter: config.delimiter,
        separator: config.separator,
        onPreParse: options.onPreParse,
        onParseEntry: options.onParseEntry,
        onParseValue: options.onParseValue,
        onPostParse: options.onPostParse,
        start: options.start,
        end: options.end,
        state: {
          rowNum: 1,
          colNum: 1
        },
        match: false,
        transform: options.transform
      };

      var headerOptions = {
        delimiter: config.delimiter,
        separator: config.separator,
        start: 1,
        end: 1,
        state: {
          rowNum: 1,
          colNum: 1
        }
      };

      if (options.onPreParse !== undefined) {
        options.onPreParse(csv,options.state);
      }

      var headerLine = $.csv.parsers.splitLines(csv,headerOptions);
      var headers = $.csv.toArray(headerLine[0],options);

      lines = $.csv.parsers.splitLines(csv,options);

      options.state.colNum = 1;
      if (headers) {
        options.state.rowNum = 2;
      } else {
        options.state.rowNum = 1;
      }

      for (var i = 0,len = lines.length; i < len; i++) {
        var entry = $.csv.toArray(lines[i],options);
        var object = {};
        for (var j = 0; j < headers.length; j++) {
          object[headers[j]] = entry[j];
        }
        if (options.transform !== undefined) {
          data.push(options.transform.call(undefined,object));
        } else {
          data.push(object);
        }

        options.state.rowNum++;
      }

      if (options.onPostParse !== undefined) {
        options.onPostParse(data,options.state);
      }

      if (!config.callback) {
        return data;
      } else {
        config.callback('',data);
      }
    },

    /**
    * $.csv.fromArrays(arrays)
    * Converts a javascript array to a CSV String.
    *
    * @param {Array} arrays An array containing an array of CSV entries.
    * @param {Object} [options] An object containing user-defined options.
    * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
    * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
    *
    * This method generates a CSV file from an array of arrays (representing entries).
    */
    fromArrays: function (arrays,options,callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof (callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;

      var output = '',
        line,
        lineValues,
        i,j;

      for (i = 0; i < arrays.length; i++) {
        line = arrays[i];
        lineValues = [];
        for (j = 0; j < line.length; j++) {
          var strValue = (line[j] === undefined || line[j] === null) ? '' : line[j].toString();
          if (strValue.indexOf(config.delimiter) > -1) {
            strValue = strValue.replace(config.delimiter,config.delimiter + config.delimiter);
          }

          var escMatcher = '\n|\r|S|D';
          escMatcher = escMatcher.replace('S',config.separator);
          escMatcher = escMatcher.replace('D',config.delimiter);

          if (strValue.search(escMatcher) > -1) {
            strValue = config.delimiter + strValue + config.delimiter;
          }
          lineValues.push(strValue);
        }
        output += lineValues.join(config.separator) + '\r\n';
      }

      if (!config.callback) {
        return output;
      } else {
        config.callback('',output);
      }
    },

    /**jquery.csv.js
     * $.csv.fromObjects(objects)
     * Converts a javascript dictionary to a CSV string.
     *
     * @param {Object} objects An array of objects containing the data.
     * @param {Object} [options] An object containing user-defined options.
     * @param {Character} [separator] An override for the separator character. Defaults to a comma(,).
     * @param {Character} [delimiter] An override for the delimiter character. Defaults to a double-quote(").
     * @param {Character} [sortOrder] Sort order of columns (named after
     *   object properties). Use 'alpha' for alphabetic. Default is 'declare',
     *   which means, that properties will _probably_ appear in order they were
     *   declared for the object. But without any guarantee.
     * @param {Character or Array} [manualOrder] Manually order columns. May be
     * a strin in a same csv format as an output or an array of header names
     * (array items won't be parsed). All the properties, not present in
     * `manualOrder` will be appended to the end in accordance with `sortOrder`
     * option. So the `manualOrder` always takes preference, if present.
     *
     * This method generates a CSV file from an array of objects (name:value pairs).
     * It starts by detecting the headers and adding them as the first line of
     * the CSV file, followed by a structured dump of the data.
     */
    fromObjects: function (objects,options,callback) {
      options = (options !== undefined ? options : {});
      var config = {};
      config.callback = ((callback !== undefined && typeof (callback) === 'function') ? callback : false);
      config.separator = 'separator' in options ? options.separator : $.csv.defaults.separator;
      config.delimiter = 'delimiter' in options ? options.delimiter : $.csv.defaults.delimiter;
      config.headers = 'headers' in options ? options.headers : $.csv.defaults.headers;
      config.sortOrder = 'sortOrder' in options ? options.sortOrder : 'declare';
      config.manualOrder = 'manualOrder' in options ? options.manualOrder : [];
      config.transform = options.transform;

      if (typeof config.manualOrder === 'string') {
        config.manualOrder = $.csv.toArray(config.manualOrder,config);
      }

      if (config.transform !== undefined) {
        var origObjects = objects;
        objects = [];

        var i;
        for (i = 0; i < origObjects.length; i++) {
          objects.push(config.transform.call(undefined,origObjects[i]));
        }
      }

      var props = $.csv.helpers.collectPropertyNames(objects);

      if (config.sortOrder === 'alpha') {
        props.sort();
      }

      if (config.manualOrder.length > 0) {

        var propsManual = [].concat(config.manualOrder);
        var p;
        for (p = 0; p < props.length; p++) {
          if (propsManual.indexOf(props[p]) < 0) {
            propsManual.push(props[p]);
          }
        }
        props = propsManual;
      }

      var o,p,line,output = [],propName;
      if (config.headers) {
        output.push(props);
      }

      for (o = 0; o < objects.length; o++) {
        line = [];
        for (p = 0; p < props.length; p++) {
          propName = props[p];
          if (propName in objects[o] && typeof objects[o][propName] !== 'function') {
            line.push(objects[o][propName]);
          } else {
            line.push('');
          }
        }
        output.push(line);
      }

      return $.csv.fromArrays(output,options,config.callback);
    }
  };

  $.csvEntry2Array = $.csv.toArray;
  $.csv2Array = $.csv.toArrays;
  $.csv2Dictionary = $.csv.toObjects;

  if (typeof module !== 'undefined' && module.exports) {
    module.exports = $.csv;
  }

}).call(this);

/*!
 * jScrollPane - v2.0.23 - 2016-01-28
 * http://jscrollpane.kelvinluck.com/
 *
 * Copyright (c) 2014 Kelvin Luck
 * Dual licensed under the MIT or GPL licenses.
 */
!(function (a) {
  "function" == typeof define && define.amd ? define(["jquery"],a) : "object" == typeof exports ? (module.exports = a(require("jquery"))) : a(jQuery);
})(function (a) {
  (a.fn.jScrollPane = function (b) {
    function c(b,c) {
      function d(c) {
        var f,
          h,
          j,
          k,
          l,
          o,
          p = !1,
          q = !1;
        if (((N = c),void 0 === O))
          (l = b.scrollTop()),
            (o = b.scrollLeft()),
            b.css({ overflow: "hidden",padding: 0 }),
            (P = b.innerWidth() + rb),
            (Q = b.innerHeight()),
            b.width(P),
            (O = a('<div class="jspPane" />').css("padding",qb).append(b.children())),
            (R = a('<div class="jspContainer" />')
              .css({ width: P + "px",height: Q + "px" })
              .append(O)
              .appendTo(b));
        else {
          if (
            (b.css("width",""),
              (p = N.stickToBottom && A()),
              (q = N.stickToRight && B()),
              (k = b.innerWidth() + rb != P || b.outerHeight() != Q),
              k && ((P = b.innerWidth() + rb),(Q = b.innerHeight()),R.css({ width: P + "px",height: Q + "px" })),
              !k && sb == S && O.outerHeight() == T)
          )
            return void b.width(P);
          (sb = S),O.css("width",""),b.width(P),R.find(">.jspVerticalBar,>.jspHorizontalBar").remove().end();
        }
        O.css("overflow","auto"),
          (S = c.contentWidth ? c.contentWidth : O[0].scrollWidth),
          (T = O[0].scrollHeight),
          O.css("overflow",""),
          (U = S / P),
          (V = T / Q),
          (W = V > 1),
          (X = U > 1),
          X || W
            ? (b.addClass("jspScrollable"),
              (f = N.maintainPosition && ($ || bb)),
              f && ((h = y()),(j = z())),
              e(),
              g(),
              i(),
              f && (w(q ? S - P : h,!1),v(p ? T - Q : j,!1)),
              F(),
              C(),
              L(),
              N.enableKeyboardNavigation && H(),
              N.clickOnTrack && m(),
              J(),
              N.hijackInternalLinks && K())
            : (b.removeClass("jspScrollable"),O.css({ top: 0,left: 0,width: R.width() - rb }),D(),G(),I(),n()),
          N.autoReinitialise && !pb
            ? (pb = setInterval(function () {
              d(N);
            },N.autoReinitialiseDelay))
            : !N.autoReinitialise && pb && clearInterval(pb),
          l && b.scrollTop(0) && v(l,!1),
          o && b.scrollLeft(0) && w(o,!1),
          b.trigger("jsp-initialised",[X || W]);
      }
      function e() {
        W &&
          (R.append(
            a('<div class="jspVerticalBar" />').append(
              a('<div class="jspCap jspCapTop" />'),
              a('<div class="jspTrack" />').append(a('<div class="jspDrag" />').append(a('<div class="jspDragTop" />'),a('<div class="jspDragBottom" />'))),
              a('<div class="jspCap jspCapBottom" />')
            )
          ),
            (cb = R.find(">.jspVerticalBar")),
            (db = cb.find(">.jspTrack")),
            (Y = db.find(">.jspDrag")),
            N.showArrows &&
            ((hb = a('<a class="jspArrow jspArrowUp" />').bind("mousedown.jsp",k(0,-1)).bind("click.jsp",E)),
              (ib = a('<a class="jspArrow jspArrowDown" />').bind("mousedown.jsp",k(0,1)).bind("click.jsp",E)),
              N.arrowScrollOnHover && (hb.bind("mouseover.jsp",k(0,-1,hb)),ib.bind("mouseover.jsp",k(0,1,ib))),
              j(db,N.verticalArrowPositions,hb,ib)),
            (fb = Q),
            R.find(">.jspVerticalBar>.jspCap:visible,>.jspVerticalBar>.jspArrow").each(function () {
              fb -= a(this).outerHeight();
            }),
            Y.hover(
              function () {
                Y.addClass("jspHover");
              },
              function () {
                Y.removeClass("jspHover");
              }
            ).bind("mousedown.jsp",function (b) {
              a("html").bind("dragstart.jsp selectstart.jsp",E),Y.addClass("jspActive");
              var c = b.pageY - Y.position().top;
              return (
                a("html")
                  .bind("mousemove.jsp",function (a) {
                    p(a.pageY - c,!1);
                  })
                  .bind("mouseup.jsp mouseleave.jsp",o),
                !1
              );
            }),
            f());
      }
      function f() {
        db.height(fb + "px"),($ = 0),(eb = N.verticalGutter + db.outerWidth()),O.width(P - eb - rb);
        try {
          0 === cb.position().left && O.css("margin-left",eb + "px");
        } catch (a) { }
      }
      function g() {
        X &&
          (R.append(
            a('<div class="jspHorizontalBar" />').append(
              a('<div class="jspCap jspCapLeft" />'),
              a('<div class="jspTrack" />').append(a('<div class="jspDrag" />').append(a('<div class="jspDragLeft" />'),a('<div class="jspDragRight" />'))),
              a('<div class="jspCap jspCapRight" />')
            )
          ),
            (jb = R.find(">.jspHorizontalBar")),
            (kb = jb.find(">.jspTrack")),
            (_ = kb.find(">.jspDrag")),
            N.showArrows &&
            ((nb = a('<a class="jspArrow jspArrowLeft" />').bind("mousedown.jsp",k(-1,0)).bind("click.jsp",E)),
              (ob = a('<a class="jspArrow jspArrowRight" />').bind("mousedown.jsp",k(1,0)).bind("click.jsp",E)),
              N.arrowScrollOnHover && (nb.bind("mouseover.jsp",k(-1,0,nb)),ob.bind("mouseover.jsp",k(1,0,ob))),
              j(kb,N.horizontalArrowPositions,nb,ob)),
            _.hover(
              function () {
                _.addClass("jspHover");
              },
              function () {
                _.removeClass("jspHover");
              }
            ).bind("mousedown.jsp",function (b) {
              a("html").bind("dragstart.jsp selectstart.jsp",E),_.addClass("jspActive");
              var c = b.pageX - _.position().left;
              return (
                a("html")
                  .bind("mousemove.jsp",function (a) {
                    r(a.pageX - c,!1);
                  })
                  .bind("mouseup.jsp mouseleave.jsp",o),
                !1
              );
            }),
            (lb = R.innerWidth()),
            h());
      }
      function h() {
        R.find(">.jspHorizontalBar>.jspCap:visible,>.jspHorizontalBar>.jspArrow").each(function () {
          lb -= a(this).outerWidth();
        }),
          kb.width(lb + "px"),
          (bb = 0);
      }
      function i() {
        if (X && W) {
          var b = kb.outerHeight(),
            c = db.outerWidth();
          (fb -= b),
            a(jb)
              .find(">.jspCap:visible,>.jspArrow")
              .each(function () {
                lb += a(this).outerWidth();
              }),
            (lb -= c),
            (Q -= c),
            (P -= b),
            kb.parent().append(a('<div class="jspCorner" />').css("width",b + "px")),
            f(),
            h();
        }
        X && O.width(R.outerWidth() - rb + "px"),
          (T = O.outerHeight()),
          (V = T / Q),
          X && ((mb = Math.ceil((1 / U) * lb)),mb > N.horizontalDragMaxWidth ? (mb = N.horizontalDragMaxWidth) : mb < N.horizontalDragMinWidth && (mb = N.horizontalDragMinWidth),_.width(mb + "px"),(ab = lb - mb),s(bb)),
          W && ((gb = Math.ceil((1 / V) * fb)),gb > N.verticalDragMaxHeight ? (gb = N.verticalDragMaxHeight) : gb < N.verticalDragMinHeight && (gb = N.verticalDragMinHeight),Y.height(gb + "px"),(Z = fb - gb),q($));
      }
      function j(a,b,c,d) {
        var e,
          f = "before",
          g = "after";
        "os" == b && (b = /Mac/.test(navigator.platform) ? "after" : "split"),b == f ? (g = b) : b == g && ((f = b),(e = c),(c = d),(d = e)),a[f](c)[g](d);
      }
      function k(a,b,c) {
        return function () {
          return l(a,b,this,c),this.blur(),!1;
        };
      }
      function l(b,c,d,e) {
        d = a(d).addClass("jspActive");
        var f,
          g,
          h = !0,
          i = function () {
            0 !== b && tb.scrollByX(b * N.arrowButtonSpeed),0 !== c && tb.scrollByY(c * N.arrowButtonSpeed),(g = setTimeout(i,h ? N.initialDelay : N.arrowRepeatFreq)),(h = !1);
          };
        i(),
          (f = e ? "mouseout.jsp" : "mouseup.jsp"),
          (e = e || a("html")),
          e.bind(f,function () {
            d.removeClass("jspActive"),g && clearTimeout(g),(g = null),e.unbind(f);
          });
      }
      function m() {
        n(),
          W &&
          db.bind("mousedown.jsp",function (b) {
            if (void 0 === b.originalTarget || b.originalTarget == b.currentTarget) {
              var c,
                d = a(this),
                e = d.offset(),
                f = b.pageY - e.top - $,
                g = !0,
                h = function () {
                  var a = d.offset(),
                    e = b.pageY - a.top - gb / 2,
                    j = Q * N.scrollPagePercent,
                    k = (Z * j) / (T - Q);
                  if (0 > f) $ - k > e ? tb.scrollByY(-j) : p(e);
                  else {
                    if (!(f > 0)) return void i();
                    e > $ + k ? tb.scrollByY(j) : p(e);
                  }
                  (c = setTimeout(h,g ? N.initialDelay : N.trackClickRepeatFreq)),(g = !1);
                },
                i = function () {
                  c && clearTimeout(c),(c = null),a(document).unbind("mouseup.jsp",i);
                };
              return h(),a(document).bind("mouseup.jsp",i),!1;
            }
          }),
          X &&
          kb.bind("mousedown.jsp",function (b) {
            if (void 0 === b.originalTarget || b.originalTarget == b.currentTarget) {
              var c,
                d = a(this),
                e = d.offset(),
                f = b.pageX - e.left - bb,
                g = !0,
                h = function () {
                  var a = d.offset(),
                    e = b.pageX - a.left - mb / 2,
                    j = P * N.scrollPagePercent,
                    k = (ab * j) / (S - P);
                  if (0 > f) bb - k > e ? tb.scrollByX(-j) : r(e);
                  else {
                    if (!(f > 0)) return void i();
                    e > bb + k ? tb.scrollByX(j) : r(e);
                  }
                  (c = setTimeout(h,g ? N.initialDelay : N.trackClickRepeatFreq)),(g = !1);
                },
                i = function () {
                  c && clearTimeout(c),(c = null),a(document).unbind("mouseup.jsp",i);
                };
              return h(),a(document).bind("mouseup.jsp",i),!1;
            }
          });
      }
      function n() {
        kb && kb.unbind("mousedown.jsp"),db && db.unbind("mousedown.jsp");
      }
      function o() {
        a("html").unbind("dragstart.jsp selectstart.jsp mousemove.jsp mouseup.jsp mouseleave.jsp"),Y && Y.removeClass("jspActive"),_ && _.removeClass("jspActive");
      }
      function p(c,d) {
        if (W) {
          0 > c ? (c = 0) : c > Z && (c = Z);
          var e = new a.Event("jsp-will-scroll-y");
          if ((b.trigger(e,[c]),!e.isDefaultPrevented())) {
            var f = c || 0,
              g = 0 === f,
              h = f == Z,
              i = c / Z,
              j = -i * (T - Q);
            void 0 === d && (d = N.animateScroll),
              d
                ? tb.animate(Y,"top",c,q,function () {
                  b.trigger("jsp-user-scroll-y",[-j,g,h]);
                })
                : (Y.css("top",c),q(c),b.trigger("jsp-user-scroll-y",[-j,g,h]));
          }
        }
      }
      function q(a) {
        void 0 === a && (a = Y.position().top),R.scrollTop(0),($ = a || 0);
        var c = 0 === $,
          d = $ == Z,
          e = a / Z,
          f = -e * (T - Q);
        (ub != c || wb != d) && ((ub = c),(wb = d),b.trigger("jsp-arrow-change",[ub,wb,vb,xb])),t(c,d),O.css("top",f),b.trigger("jsp-scroll-y",[-f,c,d]).trigger("scroll");
      }
      function r(c,d) {
        if (X) {
          0 > c ? (c = 0) : c > ab && (c = ab);
          var e = new a.Event("jsp-will-scroll-x");
          if ((b.trigger(e,[c]),!e.isDefaultPrevented())) {
            var f = c || 0,
              g = 0 === f,
              h = f == ab,
              i = c / ab,
              j = -i * (S - P);
            void 0 === d && (d = N.animateScroll),
              d
                ? tb.animate(_,"left",c,s,function () {
                  b.trigger("jsp-user-scroll-x",[-j,g,h]);
                })
                : (_.css("left",c),s(c),b.trigger("jsp-user-scroll-x",[-j,g,h]));
          }
        }
      }
      function s(a) {
        void 0 === a && (a = _.position().left),R.scrollTop(0),(bb = a || 0);
        var c = 0 === bb,
          d = bb == ab,
          e = a / ab,
          f = -e * (S - P);
        (vb != c || xb != d) && ((vb = c),(xb = d),b.trigger("jsp-arrow-change",[ub,wb,vb,xb])),u(c,d),O.css("left",f),b.trigger("jsp-scroll-x",[-f,c,d]).trigger("scroll");
      }
      function t(a,b) {
        N.showArrows && (hb[a ? "addClass" : "removeClass"]("jspDisabled"),ib[b ? "addClass" : "removeClass"]("jspDisabled"));
      }
      function u(a,b) {
        N.showArrows && (nb[a ? "addClass" : "removeClass"]("jspDisabled"),ob[b ? "addClass" : "removeClass"]("jspDisabled"));
      }
      function v(a,b) {
        var c = a / (T - Q);
        p(c * Z,b);
      }
      function w(a,b) {
        var c = a / (S - P);
        r(c * ab,b);
      }
      function x(b,c,d) {
        var e,
          f,
          g,
          h,
          i,
          j,
          k,
          l,
          m,
          n = 0,
          o = 0;
        try {
          e = a(b);
        } catch (p) {
          return;
        }
        for (f = e.outerHeight(),g = e.outerWidth(),R.scrollTop(0),R.scrollLeft(0); !e.is(".jspPane");) if (((n += e.position().top),(o += e.position().left),(e = e.offsetParent()),/^body|html$/i.test(e[0].nodeName))) return;
        (h = z()),
          (j = h + Q),
          h > n || c ? (l = n - N.horizontalGutter) : n + f > j && (l = n - Q + f + N.horizontalGutter),
          isNaN(l) || v(l,d),
          (i = y()),
          (k = i + P),
          i > o || c ? (m = o - N.horizontalGutter) : o + g > k && (m = o - P + g + N.horizontalGutter),
          isNaN(m) || w(m,d);
      }
      function y() {
        return -O.position().left;
      }
      function z() {
        return -O.position().top;
      }
      function A() {
        var a = T - Q;
        return a > 20 && a - z() < 10;
      }
      function B() {
        var a = S - P;
        return a > 20 && a - y() < 10;
      }
      function C() {
        R.unbind(zb).bind(zb,function (a,b,c,d) {
          bb || (bb = 0),$ || ($ = 0);
          var e = bb,
            f = $,
            g = a.deltaFactor || N.mouseWheelSpeed;
          return tb.scrollBy(c * g,-d * g,!1),e == bb && f == $;
        });
      }
      function D() {
        R.unbind(zb);
      }
      function E() {
        return !1;
      }
      function F() {
        O.find(":input,a")
          .unbind("focus.jsp")
          .bind("focus.jsp",function (a) {
            x(a.target,!1);
          });
      }
      function G() {
        O.find(":input,a").unbind("focus.jsp");
      }
      function H() {
        function c() {
          var a = bb,
            b = $;
          switch (d) {
            case 40:
              tb.scrollByY(N.keyboardSpeed,!1);
              break;
            case 38:
              tb.scrollByY(-N.keyboardSpeed,!1);
              break;
            case 34:
            case 32:
              tb.scrollByY(Q * N.scrollPagePercent,!1);
              break;
            case 33:
              tb.scrollByY(-Q * N.scrollPagePercent,!1);
              break;
            case 39:
              tb.scrollByX(N.keyboardSpeed,!1);
              break;
            case 37:
              tb.scrollByX(-N.keyboardSpeed,!1);
          }
          return (e = a != bb || b != $);
        }
        var d,
          e,
          f = [];
        X && f.push(jb[0]),
          W && f.push(cb[0]),
          O.bind("focus.jsp",function () {
            b.focus();
          }),
          b
            .attr("tabindex",0)
            .unbind("keydown.jsp keypress.jsp")
            .bind("keydown.jsp",function (b) {
              if (b.target === this || (f.length && a(b.target).closest(f).length)) {
                var g = bb,
                  h = $;
                switch (b.keyCode) {
                  case 40:
                  case 38:
                  case 34:
                  case 32:
                  case 33:
                  case 39:
                  case 37:
                    (d = b.keyCode),c();
                    break;
                  case 35:
                    v(T - Q),(d = null);
                    break;
                  case 36:
                    v(0),(d = null);
                }
                return (e = (b.keyCode == d && g != bb) || h != $),!e;
              }
            })
            .bind("keypress.jsp",function (b) {
              return b.keyCode == d && c(),b.target === this || (f.length && a(b.target).closest(f).length) ? !e : void 0;
            }),
          N.hideFocus ? (b.css("outline","none"),"hideFocus" in R[0] && b.attr("hideFocus",!0)) : (b.css("outline",""),"hideFocus" in R[0] && b.attr("hideFocus",!1));
      }
      function I() {
        b.attr("tabindex","-1").removeAttr("tabindex").unbind("keydown.jsp keypress.jsp"),O.unbind(".jsp");
      }
      function J() {
        if (location.hash && location.hash.length > 1) {
          var b,
            c,
            d = escape(location.hash.substr(1));
          try {
            b = a("#" + d + ', a[name="' + d + '"]');
          } catch (e) {
            return;
          }
          b.length &&
            O.find(d) &&
            (0 === R.scrollTop()
              ? (c = setInterval(function () {
                R.scrollTop() > 0 && (x(b,!0),a(document).scrollTop(R.position().top),clearInterval(c));
              },50))
              : (x(b,!0),a(document).scrollTop(R.position().top)));
        }
      }
      function K() {
        a(document.body).data("jspHijack") ||
          (a(document.body).data("jspHijack",!0),
            a(document.body).delegate('a[href*="#"]',"click",function (b) {
              var c,
                d,
                e,
                f,
                g,
                h,
                i = this.href.substr(0,this.href.indexOf("#")),
                j = location.href;
              if ((-1 !== location.href.indexOf("#") && (j = location.href.substr(0,location.href.indexOf("#"))),i === j)) {
                c = escape(this.href.substr(this.href.indexOf("#") + 1));
                try {
                  d = a("#" + c + ', a[name="' + c + '"]');
                } catch (k) {
                  return;
                }
                d.length &&
                  ((e = d.closest(".jspScrollable")),
                    (f = e.data("jsp")),
                    f.scrollToElement(d,!0),
                    e[0].scrollIntoView && ((g = a(window).scrollTop()),(h = d.offset().top),(g > h || h > g + a(window).height()) && e[0].scrollIntoView()),
                    b.preventDefault());
              }
            }));
      }
      function L() {
        var a,
          b,
          c,
          d,
          e,
          f = !1;
        R.unbind("touchstart.jsp touchmove.jsp touchend.jsp click.jsp-touchclick")
          .bind("touchstart.jsp",function (g) {
            var h = g.originalEvent.touches[0];
            (a = y()),(b = z()),(c = h.pageX),(d = h.pageY),(e = !1),(f = !0);
          })
          .bind("touchmove.jsp",function (g) {
            if (f) {
              var h = g.originalEvent.touches[0],
                i = bb,
                j = $;
              return tb.scrollTo(a + c - h.pageX,b + d - h.pageY),(e = e || Math.abs(c - h.pageX) > 5 || Math.abs(d - h.pageY) > 5),i == bb && j == $;
            }
          })
          .bind("touchend.jsp",function () {
            f = !1;
          })
          .bind("click.jsp-touchclick",function () {
            return e ? ((e = !1),!1) : void 0;
          });
      }
      function M() {
        var a = z(),
          c = y();
        b.removeClass("jspScrollable").unbind(".jsp"),O.unbind(".jsp"),b.replaceWith(yb.append(O.children())),yb.scrollTop(a),yb.scrollLeft(c),pb && clearInterval(pb);
      }
      var N,
        O,
        P,
        Q,
        R,
        S,
        T,
        U,
        V,
        W,
        X,
        Y,
        Z,
        $,
        _,
        ab,
        bb,
        cb,
        db,
        eb,
        fb,
        gb,
        hb,
        ib,
        jb,
        kb,
        lb,
        mb,
        nb,
        ob,
        pb,
        qb,
        rb,
        sb,
        tb = this,
        ub = !0,
        vb = !0,
        wb = !1,
        xb = !1,
        yb = b.clone(!1,!1).empty(),
        zb = a.fn.mwheelIntent ? "mwheelIntent.jsp" : "mousewheel.jsp";
      "border-box" === b.css("box-sizing")
        ? ((qb = 0),(rb = 0))
        : ((qb = b.css("paddingTop") + " " + b.css("paddingRight") + " " + b.css("paddingBottom") + " " + b.css("paddingLeft")),(rb = (parseInt(b.css("paddingLeft"),10) || 0) + (parseInt(b.css("paddingRight"),10) || 0))),
        a.extend(tb,{
          reinitialise: function (b) {
            (b = a.extend({},N,b)),d(b);
          },
          scrollToElement: function (a,b,c) {
            x(a,b,c);
          },
          scrollTo: function (a,b,c) {
            w(a,c),v(b,c);
          },
          scrollToX: function (a,b) {
            w(a,b);
          },
          scrollToY: function (a,b) {
            v(a,b);
          },
          scrollToPercentX: function (a,b) {
            w(a * (S - P),b);
          },
          scrollToPercentY: function (a,b) {
            v(a * (T - Q),b);
          },
          scrollBy: function (a,b,c) {
            tb.scrollByX(a,c),tb.scrollByY(b,c);
          },
          scrollByX: function (a,b) {
            var c = y() + Math[0 > a ? "floor" : "ceil"](a),
              d = c / (S - P);
            r(d * ab,b);
          },
          scrollByY: function (a,b) {
            var c = z() + Math[0 > a ? "floor" : "ceil"](a),
              d = c / (T - Q);
            p(d * Z,b);
          },
          positionDragX: function (a,b) {
            r(a,b);
          },
          positionDragY: function (a,b) {
            p(a,b);
          },
          animate: function (a,b,c,d,e) {
            var f = {};
            (f[b] = c),a.animate(f,{ duration: N.animateDuration,easing: N.animateEase,queue: !1,step: d,complete: e });
          },
          getContentPositionX: function () {
            return y();
          },
          getContentPositionY: function () {
            return z();
          },
          getContentWidth: function () {
            return S;
          },
          getContentHeight: function () {
            return T;
          },
          getPercentScrolledX: function () {
            return y() / (S - P);
          },
          getPercentScrolledY: function () {
            return z() / (T - Q);
          },
          getIsScrollableH: function () {
            return X;
          },
          getIsScrollableV: function () {
            return W;
          },
          getContentPane: function () {
            return O;
          },
          scrollToBottom: function (a) {
            p(Z,a);
          },
          hijackInternalLinks: a.noop,
          destroy: function () {
            M();
          },
        }),
        d(c);
    }
    return (
      (b = a.extend({},a.fn.jScrollPane.defaults,b)),
      a.each(["arrowButtonSpeed","trackClickSpeed","keyboardSpeed"],function () {
        b[this] = b[this] || b.speed;
      }),
      this.each(function () {
        var d = a(this),
          e = d.data("jsp");
        e ? e.reinitialise(b) : (a("script",d).filter('[type="text/javascript"],:not([type])').remove(),(e = new c(d,b)),d.data("jsp",e));
      })
    );
  }),
    (a.fn.jScrollPane.defaults = {
      showArrows: !1,
      maintainPosition: !0,
      stickToBottom: !1,
      stickToRight: !1,
      clickOnTrack: !0,
      autoReinitialise: !1,
      autoReinitialiseDelay: 500,
      verticalDragMinHeight: 0,
      verticalDragMaxHeight: 99999,
      horizontalDragMinWidth: 0,
      horizontalDragMaxWidth: 99999,
      contentWidth: void 0,
      animateScroll: !1,
      animateDuration: 300,
      animateEase: "linear",
      hijackInternalLinks: !1,
      verticalGutter: 4,
      horizontalGutter: 4,
      mouseWheelSpeed: 3,
      arrowButtonSpeed: 0,
      arrowRepeatFreq: 50,
      arrowScrollOnHover: !1,
      trackClickSpeed: 0,
      trackClickRepeatFreq: 70,
      verticalArrowPositions: "split",
      horizontalArrowPositions: "split",
      enableKeyboardNavigation: !0,
      hideFocus: !1,
      keyboardSpeed: 0,
      initialDelay: 300,
      speed: 30,
      scrollPagePercent: 0.8,
    });
});
/**mwheelIntent.js
 * @author trixta
 * @version 1.2
 */
(function ($) {

  var mwheelI = {
    pos: [-260,-260]
  },
    minDif = 3,
    doc = document,
    root = doc.documentElement,
    body = doc.body,
    longDelay,shortDelay
    ;

  function unsetPos() {
    if (this === mwheelI.elem) {
      mwheelI.pos = [-260,-260];
      mwheelI.elem = false;
      minDif = 3;
    }
  }

  $.event.special.mwheelIntent = {
    setup: function () {
      var jElm = $(this).bind('mousewheel',$.event.special.mwheelIntent.handler);
      if (this !== doc && this !== root && this !== body) {
        jElm.bind('mouseleave',unsetPos);
      }
      jElm = null;
      return true;
    },
    teardown: function () {
      $(this)
        .unbind('mousewheel',$.event.special.mwheelIntent.handler)
        .unbind('mouseleave',unsetPos)
        ;
      return true;
    },
    handler: function (e,d) {
      var pos = [e.clientX,e.clientY];
      if (this === mwheelI.elem || Math.abs(mwheelI.pos[0] - pos[0]) > minDif || Math.abs(mwheelI.pos[1] - pos[1]) > minDif) {
        mwheelI.elem = this;
        mwheelI.pos = pos;
        minDif = 250;

        clearTimeout(shortDelay);
        shortDelay = setTimeout(function () {
          minDif = 10;
        },200);
        clearTimeout(longDelay);
        longDelay = setTimeout(function () {
          minDif = 3;
        },1500);
        e = $.extend({},e,{ type: 'mwheelIntent' });
        return ($.event.dispatch || $.event.handle).apply(this,arguments);
      }
    }
  };
  $.fn.extend({
    mwheelIntent: function (fn) {
      return fn ? this.bind("mwheelIntent",fn) : this.trigger("mwheelIntent");
    },

    unmwheelIntent: function (fn) {
      return this.unbind("mwheelIntent",fn);
    }
  });

  $(function () {
    body = doc.body;
    $(doc).bind('mwheelIntent.mwheelIntentDefault',$.noop);
  });
})(jQuery);

/*//jquery.mousewheel.js
!
* jQuery Mousewheel 3.1.12
*
* Copyright 2014 jQuery Foundation and other contributors
* Released under the MIT license.
* http://jquery.org/license
*/

(function (factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'],factory);
  } else if (typeof exports === 'object') {
    module.exports = factory;
  } else {
    factory(jQuery);
  }
}(function ($) {

  var toFix = ['wheel','mousewheel','DOMMouseScroll','MozMousePixelScroll'],
    toBind = ('onwheel' in document || document.documentMode >= 9) ?
      ['wheel'] : ['mousewheel','DomMouseScroll','MozMousePixelScroll'],
    slice = Array.prototype.slice,
    nullLowestDeltaTimeout,lowestDelta;

  if ($.event.fixHooks) {
    for (var i = toFix.length; i;) {
      $.event.fixHooks[toFix[--i]] = $.event.mouseHooks;
    }
  }

  var special = $.event.special.mousewheel = {
    version: '3.1.12',

    setup: function () {
      if (this.addEventListener) {
        for (var i = toBind.length; i;) {
          this.addEventListener(toBind[--i],handler,false);
        }
      } else {
        this.onmousewheel = handler;
      }
      $.data(this,'mousewheel-line-height',special.getLineHeight(this));
      $.data(this,'mousewheel-page-height',special.getPageHeight(this));
    },

    teardown: function () {
      if (this.removeEventListener) {
        for (var i = toBind.length; i;) {
          this.removeEventListener(toBind[--i],handler,false);
        }
      } else {
        this.onmousewheel = null;
      }
      $.removeData(this,'mousewheel-line-height');
      $.removeData(this,'mousewheel-page-height');
    },

    getLineHeight: function (elem) {
      var $elem = $(elem),
        $parent = $elem['offsetParent' in $.fn ? 'offsetParent' : 'parent']();
      if (!$parent.length) {
        $parent = $('body');
      }
      return parseInt($parent.css('fontSize'),10) || parseInt($elem.css('fontSize'),10) || 16;
    },

    getPageHeight: function (elem) {
      return $(elem).height();
    },

    settings: {
      adjustOldDeltas: true,
      normalizeOffset: true
    }
  };

  $.fn.extend({
    mousewheel: function (fn) {
      return fn ? this.bind('mousewheel',fn) : this.trigger('mousewheel');
    },

    unmousewheel: function (fn) {
      return this.unbind('mousewheel',fn);
    }
  });


  function handler(event) {
    var orgEvent = event || window.event,
      args = slice.call(arguments,1),
      delta = 0,
      deltaX = 0,
      deltaY = 0,
      absDelta = 0,
      offsetX = 0,
      offsetY = 0;
    event = $.event.fix(orgEvent);
    event.type = 'mousewheel';

    if ('detail' in orgEvent) { deltaY = orgEvent.detail * -1; }
    if ('wheelDelta' in orgEvent) { deltaY = orgEvent.wheelDelta; }
    if ('wheelDeltaY' in orgEvent) { deltaY = orgEvent.wheelDeltaY; }
    if ('wheelDeltaX' in orgEvent) { deltaX = orgEvent.wheelDeltaX * -1; }

    if ('axis' in orgEvent && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
      deltaX = deltaY * -1;
      deltaY = 0;
    }

    delta = deltaY === 0 ? deltaX : deltaY;

    if ('deltaY' in orgEvent) {
      deltaY = orgEvent.deltaY * -1;
      delta = deltaY;
    }
    if ('deltaX' in orgEvent) {
      deltaX = orgEvent.deltaX;
      if (deltaY === 0) { delta = deltaX * -1; }
    }

    if (deltaY === 0 && deltaX === 0) { return; }

    if (orgEvent.deltaMode === 1) {
      var lineHeight = $.data(this,'mousewheel-line-height');
      delta *= lineHeight;
      deltaY *= lineHeight;
      deltaX *= lineHeight;
    } else if (orgEvent.deltaMode === 2) {
      var pageHeight = $.data(this,'mousewheel-page-height');
      delta *= pageHeight;
      deltaY *= pageHeight;
      deltaX *= pageHeight;
    }

    absDelta = Math.max(Math.abs(deltaY),Math.abs(deltaX));

    if (!lowestDelta || absDelta < lowestDelta) {
      lowestDelta = absDelta;

      if (shouldAdjustOldDeltas(orgEvent,absDelta)) {
        lowestDelta /= 40;
      }
    }

    if (shouldAdjustOldDeltas(orgEvent,absDelta)) {
      delta /= 40;
      deltaX /= 40;
      deltaY /= 40;
    }

    delta = Math[delta >= 1 ? 'floor' : 'ceil'](delta / lowestDelta);
    deltaX = Math[deltaX >= 1 ? 'floor' : 'ceil'](deltaX / lowestDelta);
    deltaY = Math[deltaY >= 1 ? 'floor' : 'ceil'](deltaY / lowestDelta);

    if (special.settings.normalizeOffset && this.getBoundingClientRect) {
      var boundingRect = this.getBoundingClientRect();
      offsetX = event.clientX - boundingRect.left;
      offsetY = event.clientY - boundingRect.top;
    }

    event.deltaX = deltaX;
    event.deltaY = deltaY;
    event.deltaFactor = lowestDelta;
    event.offsetX = offsetX;
    event.offsetY = offsetY;
    event.deltaMode = 0;

    args.unshift(event,delta,deltaX,deltaY);

    if (nullLowestDeltaTimeout) { clearTimeout(nullLowestDeltaTimeout); }
    nullLowestDeltaTimeout = setTimeout(nullLowestDelta,200);

    return ($.event.dispatch || $.event.handle).apply(this,args);
  }

  function nullLowestDelta() {
    lowestDelta = null;
  }

  function shouldAdjustOldDeltas(orgEvent,absDelta) {
    return special.settings.adjustOldDeltas && orgEvent.type === 'mousewheel' && absDelta % 120 === 0;
  }

}));
