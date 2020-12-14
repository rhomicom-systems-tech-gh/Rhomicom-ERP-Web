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

function loadScript(url,callback) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    if (script.readyState) {
        script.onreadystatechange = function () {
            if (script.readyState == "loaded" ||
                script.readyState == "complete") {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {
        script.onload = function () {
            callback();
        };
    }

    script.src = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}

function loadCss(url,callback) {
    var script = document.createElement("link");
    script.type = "text/css";
    script.rel = "stylesheet";
    if (script.readyState) {
        script.onreadystatechange = function () {
            if (script.readyState == "loaded" ||
                script.readyState == "complete") {
                script.onreadystatechange = null;
                callback();
            }
        };
    } else {
        script.onload = function () {
            callback();
        };
    }
    script.href = url;
    document.getElementsByTagName("head")[0].appendChild(script);
}

function getLovsPage(elementID,titleElementID,modalBodyID,lovNm,criteriaID,
    criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
    selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,
    addtnlWhere,callBackFunc,psblValIDElmntID) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (typeof valueElmntID === 'undefined' || valueElmntID === null || valueElmntID == '') {
        valueElmntID = "RhoUndefined";
    }
    if (typeof descElemntID === 'undefined' || descElemntID === null || descElemntID == '') {
        descElemntID = "RhoUndefined";
    }
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        if (criteriaID == '') {
            criteriaID = "RhoUndefined";
        }
        if (criteriaID2 == '') {
            criteriaID2 = "RhoUndefined";
        }
        if (criteriaID3 == '') {
            criteriaID3 = "RhoUndefined";
        }
        var srchFor = typeof $("#lovSrchFor").val() === 'undefined' ? '%' : $("#lovSrchFor").val();
        var srchIn = typeof $("#lovSrchIn").val() === 'undefined' ? 'Both' : $("#lovSrchIn").val();
        var pageNo = typeof $("#lovPageNo").val() === 'undefined' ? 1 : $("#lovPageNo").val();
        var limitSze = typeof $("#lovDsplySze").val() === 'undefined' ? 10 : $("#lovDsplySze").val();

        var criteriaIDVal = typeof $("#" + criteriaID).val() === 'undefined' ? -1 : $("#" + criteriaID).val();
        if (criteriaID === 'RHO_SPEC_1') {
            criteriaIDVal = 1;
        }
        var criteriaID2Val = typeof $("#" + criteriaID2).val() === 'undefined' ? '' : $("#" + criteriaID2).val();
        var criteriaID3Val = typeof $("#" + criteriaID3).val() === 'undefined' ? '' : $("#" + criteriaID3).val();
        if (colNoForChkBxCmprsn == 1) {
            /*Match by Description:Set both Value and Desc Element IDs*/
            selVals = typeof $("#" + descElemntID).val() === 'undefined' ? selVals : $("#" + descElemntID).val();
        } {
            /* Match using Possible Value itself*/
            /* For Dynamic LOVs use 0 and set both value and descElement IDs */
            /* For Static LOVs use 0 and set only valueElement ID */
            selVals = typeof $("#" + valueElmntID).val() === 'undefined' ? selVals : $("#" + valueElmntID).val();
        }
        if (actionText == 'clear') {
            srchFor = "%";
            pageNo = 1;
        } else if (actionText == 'next') {
            pageNo = parseInt(pageNo) + 1;
        } else if (actionText == 'previous') {
            pageNo = parseInt(pageNo) - 1;
        }

        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#' + titleElementID).html(lovNm);

                $('#' + modalBodyID).html(xmlhttp.responseText);
                //$('#myLovModal').draggable();
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).one('show.bs.modal',function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $(e.currentTarget).unbind();
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#' + elementID).off('shown.bs.modal');
                $('#' + elementID).on('shown.bs.modal',function () {
                    /*$('#' + elementID).draggable();*/
                    $('#lovSrchFor').focus();
                });
                $body.removeClass("mdlloading");
                $body.css("overflow-y","auto");
                $(document).ready(function () {
                    $("#lovForm").submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                    var cntr = 0;
                    if (!$.fn.DataTable.isDataTable('#lovTblRO')) {
                        var table = $('#lovTblRO').DataTable({
                            retrieve: true,
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#lovTblRO').wrap('<div class="table-responsive">');
                        $('#lovTblRO tbody').on('dblclick','tr',function () {
                            table.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');
                            $checkedBoxes = $(this).find('input[type=checkbox]');
                            $checkedBoxes.each(function (i,checkbox) {
                                checkbox.checked = true;
                            });
                            $radioBoxes = $(this).find('input[type=radio]');
                            $radioBoxes.each(function (i,radio) {
                                radio.checked = true;
                            });
                            applySlctdLov(elementID,'lovForm',valueElmntID,descElemntID,callBackFunc,psblValIDElmntID);
                        });

                        $('#lovTblRO tbody').on('click','tr',function () {
                            if ($(this).hasClass('selected')) {
                                $(this).removeClass('selected');
                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i,checkbox) {
                                    checkbox.checked = false;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i,radio) {
                                    radio.checked = false;
                                });
                            } else {
                                table.$('tr.selected').removeClass('selected');
                                $(this).addClass('selected');

                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i,checkbox) {
                                    checkbox.checked = true;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i,radio) {
                                    radio.checked = true;
                                });
                            }
                        });
                        $('#lovTblRO tbody')
                            .on('mouseenter','tr',function () {
                                if ($(this).hasClass('highlight')) {
                                    $(this).removeClass('highlight');
                                } else {
                                    table.$('tr.highlight').removeClass('highlight');
                                    $(this).addClass('highlight');
                                }
                            });
                    }
                });
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        //alert(criteriaIDVal);
        xmlhttp.send("grp=2&typ=1&lovNm=" + lovNm +
            "&criteriaID=" + criteriaID + "&criteriaID2=" + criteriaID2 +
            "&criteriaID3=" + criteriaID3 + "&criteriaIDVal=" + criteriaIDVal + "&criteriaID2Val=" + criteriaID2Val +
            "&criteriaID3Val=" + criteriaID3Val + "&chkOrRadio=" + chkOrRadio +
            "&mustSelSth=" + mustSelSth + "&selvals=" + selVals +
            "&valElmntID=" + valueElmntID + "&descElmntID=" + descElemntID +
            "&modalElementID=" + elementID + "&lovModalBody=" + modalBodyID +
            "&lovModalTitle=" + titleElementID + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze +
            "&colNoForChkBxCmprsn=" + colNoForChkBxCmprsn +
            "&addtnlWhere=" + addtnlWhere + "&callBackFunc=" + callBackFunc + "&psblValIDElmntID=" + psblValIDElmntID);
    });
}

function enterKeyFuncLov(e,elementID,titleElementID,modalBodyID,lovNm,criteriaID,
    criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
    selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,addtnlWhere,callBackFunc,psblValIDElmntID) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getLovsPage(elementID,titleElementID,modalBodyID,lovNm,criteriaID,
            criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
            selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,addtnlWhere,callBackFunc,psblValIDElmntID);
    }
}

function applySlctdLov(modalElementID,formElmntID,valueElmntID,descElemntID,callBackFunc,psblValIDElmntID) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    var form = document.getElementById(formElmntID);
    var cbResults = '';
    var radioResults = '';
    var fnl_res = '';
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type === 'checkbox') {
            if (form.elements[i].checked === true) {
                cbResults += form.elements[i].value + '|';
            }
        }
        if (form.elements[i].type === 'radio') {
            if (form.elements[i].checked === true) {
                radioResults += form.elements[i].value + '|';
            }
        }
    }
    if (cbResults.length > 1) {
        fnl_res = cbResults.slice(0,-1);
    }
    if (radioResults.length > 1) {
        fnl_res = radioResults.slice(0,-1);
    }
    var bigArry = [];
    var tempArry = [];
    bigArry = fnl_res.split("|");
    var i = 0;
    var descValue = "";
    var valValue = "";
    var psblValIDValue = "";

    for (i = 0; i < bigArry.length; i++) {
        tempArry = bigArry[i].split(";");
        if (tempArry.length > 1) {
            if (cbResults.length > 1) {
                descValue = descValue + tempArry[2] + ",";
                valValue = valValue + tempArry[1] + ",";
                psblValIDValue = psblValIDValue + tempArry[0] + ",";
            } else {
                descValue = tempArry[2];
                valValue = tempArry[1];
                psblValIDValue = tempArry[0];
            }
        }
    }
    if (cbResults.length > 1) {
        descValue = descValue.slice(0,-1);
        valValue = valValue.slice(0,-1);
        psblValIDValue = psblValIDValue.slice(0,-1);
    }
    /*alert(descValue);
     alert(valValue);
     alert(psblValIDValue);*/
    if (typeof ($('#' + descElemntID).val()) !== 'undefined') {
        document.getElementById(descElemntID).value = descValue;
        $('#' + descElemntID).val(descValue);
    }
    if (typeof ($('#' + valueElmntID).val()) !== 'undefined') {
        document.getElementById(valueElmntID).value = valValue;
        $('#' + valueElmntID).val(valValue);
    }
    if (typeof $('#' + psblValIDElmntID).val() !== 'undefined') {
        document.getElementById(psblValIDElmntID).value = psblValIDValue;
        $('#' + psblValIDElmntID).val(psblValIDValue);
    }
    $('#' + modalElementID).modal('hide');
    callBackFunc();
}

function applySlctdLov_mcf(modalElementID,formElmntID,valueElmntID,descElemntID,callBackFunc) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    var form = document.getElementById(formElmntID);
    var cbResults = '';
    var radioResults = '';
    var fnl_res = '';
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type === 'checkbox') {
            if (form.elements[i].checked === true) {
                cbResults += form.elements[i].value + '|';
            }
        }
        if (form.elements[i].type === 'radio') {
            if (form.elements[i].checked === true) {
                radioResults += form.elements[i].value + '|';
            }
        }
    }
    if (cbResults.length > 1) {
        fnl_res = cbResults.slice(0,-1);
    }
    if (radioResults.length > 1) {
        fnl_res = radioResults.slice(0,-1);
    }
    var bigArry = [];
    var tempArry = [];
    bigArry = fnl_res.split("|");
    var i = 0;
    var descValue = "";
    var valValue = "";

    for (i = 0; i < bigArry.length; i++) {
        tempArry = bigArry[i].split(";");
        if (tempArry.length > 1) {
            if (cbResults.length > 1) {
                descValue = descValue + tempArry[2] + ",";
                valValue = valValue + tempArry[1] + ",";
            } else {
                descValue = tempArry[2];
                valValue = tempArry[1];
            }
        }
    }
    if (cbResults.length > 1) {
        descValue = descValue.slice(0,-1);
        valValue = valValue.slice(0,-1);
    }
    if (typeof ($('#' + descElemntID).val()) !== 'undefined') {
        document.getElementById(descElemntID).value = descValue;
        $('#acctTitle').val(descValue); //NEW 22022017
    }
    if (typeof ($('#' + valueElmntID).val()) !== 'undefined') {
        document.getElementById(valueElmntID).value = valValue;
    }
    $('#' + modalElementID).modal('hide');
    callBackFunc();
}

function getLovsPage_mcf(elementID,titleElementID,modalBodyID,lovNm,criteriaID,
    criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
    selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,addtnlWhere,callBackFunc) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        if (criteriaID == '') {
            criteriaID = "RhoUndefined";
        }
        if (criteriaID2 == '') {
            criteriaID2 = "RhoUndefined";
        }
        if (criteriaID3 == '') {
            criteriaID3 = "RhoUndefined";
        }
        var srchFor = typeof $("#lovSrchFor").val() === 'undefined' ? '%' : $("#lovSrchFor").val();
        var srchIn = typeof $("#lovSrchIn").val() === 'undefined' ? 'Both' : $("#lovSrchIn").val();
        var pageNo = typeof $("#lovPageNo").val() === 'undefined' ? 1 : $("#lovPageNo").val();
        var limitSze = typeof $("#lovDsplySze").val() === 'undefined' ? 10 : $("#lovDsplySze").val();

        var criteriaIDVal = typeof $("#" + criteriaID).val() === 'undefined' ? -1 : $("#" + criteriaID).val();
        var criteriaID2Val = typeof $("#" + criteriaID2).val() === 'undefined' ? '' : $("#" + criteriaID2).val();
        var criteriaID3Val = typeof $("#" + criteriaID3).val() === 'undefined' ? '' : $("#" + criteriaID3).val();
        if (colNoForChkBxCmprsn == 1) {
            /*Match by Description:Set both Value and Desc Element IDs*/
            selVals = typeof $("#" + descElemntID).val() === 'undefined' ? selVals : $("#" + descElemntID).val();
        } {
            /* Match using Possible Value itself*/
            /* For Dynamic LOVs use 0 and set both value and descElement IDs */
            /* For Static LOVs use 0 and set only valueElement ID */
            selVals = typeof $("#" + valueElmntID).val() === 'undefined' ? selVals : $("#" + valueElmntID).val();
        }
        if (actionText == 'clear') {
            srchFor = "%";
            pageNo = 1;
        } else if (actionText == 'next') {
            pageNo = parseInt(pageNo) + 1;
        } else if (actionText == 'previous') {
            pageNo = parseInt(pageNo) - 1;
        }

        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#' + titleElementID).html(lovNm);

                $('#' + modalBodyID).html(xmlhttp.responseText);
                //$('#myLovModal').draggable();

                /*$('#' + elementID).on('show.bs.modal', function (e) {
                 $(this).find('.modal-body').css({
                 'max-height': '100%'
                 });
                 });*/
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal('show',{
                    backdrop: 'static'
                });;
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $("#lovForm").submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                    var cntr = 0;
                    if (!$.fn.DataTable.isDataTable('#lovTblRO')) {
                        var table = $('#lovTblRO').DataTable({
                            retrieve: true,
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#lovTblRO').wrap('<div class="table-responsive">');
                        $('#lovTblRO tbody').on('dblclick','tr',function () {

                            table.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');

                            $checkedBoxes = $(this).find('input[type=checkbox]');
                            $checkedBoxes.each(function (i,checkbox) {
                                checkbox.checked = true;
                            });
                            $radioBoxes = $(this).find('input[type=radio]');
                            $radioBoxes.each(function (i,radio) {
                                radio.checked = true;
                            });
                            applySlctdLov_mcf(elementID,'lovForm',valueElmntID,descElemntID,callBackFunc);
                        });

                        $('#lovTblRO tbody').on('click','tr',function () {
                            if ($(this).hasClass('selected')) {
                                $(this).removeClass('selected');
                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i,checkbox) {
                                    checkbox.checked = false;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i,radio) {
                                    radio.checked = false;
                                });
                            } else {
                                table.$('tr.selected').removeClass('selected');
                                $(this).addClass('selected');

                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i,checkbox) {
                                    checkbox.checked = true;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i,radio) {
                                    radio.checked = true;
                                });
                            }
                        });
                        $('#lovTblRO tbody')
                            .on('mouseenter','tr',function () {
                                if ($(this).hasClass('highlight')) {
                                    $(this).removeClass('highlight');
                                } else {
                                    table.$('tr.highlight').removeClass('highlight');
                                    $(this).addClass('highlight');
                                }
                            });
                    }
                });
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        //alert(criteriaIDVal);
        xmlhttp.send("grp=2&typ=2&lovNm=" + lovNm +
            "&criteriaID=" + criteriaID + "&criteriaID2=" + criteriaID2 +
            "&criteriaID3=" + criteriaID3 + "&criteriaIDVal=" + criteriaIDVal + "&criteriaID2Val=" + criteriaID2Val +
            "&criteriaID3Val=" + criteriaID3Val + "&chkOrRadio=" + chkOrRadio +
            "&mustSelSth=" + mustSelSth + "&selvals=" + selVals +
            "&valElmntID=" + valueElmntID + "&descElmntID=" + descElemntID +
            "&modalElementID=" + elementID + "&lovModalBody=" + modalBodyID +
            "&lovModalTitle=" + titleElementID + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze +
            "&colNoForChkBxCmprsn=" + colNoForChkBxCmprsn +
            "&addtnlWhere=" + addtnlWhere + "&callBackFunc=" + callBackFunc);
    });

}

function doAjax(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID) {
    $('#allOtherInputData99').val(0);
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");

        $body.addClass("mdlloading");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                if (actionAfter == 'Redirect') {
                    $body.removeClass("mdlloading");
                    window.open(xmlhttp.responseText,'_blank');
                } else if (actionAfter == 'ShowDialog') {
                    $body.removeClass("mdlloading");
                    $('#' + titleElementID).html(titleMsg);
                    $('#' + modalBodyID).html(xmlhttp.responseText);
                    $('#' + elementID).off('show.bs.modal');
                    $('#' + elementID).off('hidden.bs.modal');
                    $('#' + elementID).one('hidden.bs.modal',function (e) {
                        $('#' + titleElementID).html('');
                        $('#' + modalBodyID).html('');
                        $(e.currentTarget).unbind();
                    });
                    $('#' + elementID).one('show.bs.modal',function (e) {
                        /*console.debug('modal shown!');*/
                        $(this).find('.modal-body').css({
                            'max-height': '100%'
                        });
                        $(e.currentTarget).unbind();
                    });
                    $('#' + elementID).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    /*$('#' + elementID).draggable();*/
                } else if (actionAfter == 'ReloadDialog') {
                    $body.removeClass("mdlloading");
                    $('#' + titleElementID).html(titleMsg);
                    $('#' + modalBodyID).html(xmlhttp.responseText);
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $('#' + elementID).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    /*$('#' + elementID).draggable();*/
                } else {
                    document.getElementById(elementID).innerHTML = xmlhttp.responseText;
                    $body.removeClass("mdlloading");
                }
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    });
}


function doAjaxWthCallBck(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID,rqstdCallBack) {
    $('#allOtherInputData99').val(0);
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.addClass("mdlloading");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                if (actionAfter == 'Redirect') {
                    $body.removeClass("mdlloading");
                    window.open(xmlhttp.responseText,'_blank');
                    rqstdCallBack();
                } else if (actionAfter == 'ShowDialog') {
                    $body.removeClass("mdlloading");
                    $('#' + titleElementID).html(titleMsg);
                    $('#' + modalBodyID).html(xmlhttp.responseText);
                    $('#' + elementID).off('show.bs.modal');
                    $('#' + elementID).off('hidden.bs.modal');
                    $('#' + elementID).one('hidden.bs.modal',function (e) {
                        $('#' + titleElementID).html('');
                        $('#' + modalBodyID).html('');
                        $(e.currentTarget).unbind();
                    });
                    $('#' + elementID).one('show.bs.modal',function (e) {
                        /*console.debug('modal shown!');*/
                        $(this).find('.modal-body').css({
                            'max-height': '100%'
                        });
                        $(e.currentTarget).unbind();
                    });
                    $('#' + elementID).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#' + elementID).off('shown.bs.modal');
                    $('#' + elementID).on('shown.bs.modal',function () {
                        /*$('#' + elementID).draggable();*/
                        rqstdCallBack();
                    });
                } else if (actionAfter == 'ReloadDialog') {
                    $body.removeClass("mdlloading");
                    $('#' + titleElementID).html(titleMsg);
                    $('#' + modalBodyID).html(xmlhttp.responseText);
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $('#' + elementID).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    /*$('#' + elementID).draggable();*/
                    rqstdCallBack();
                } else {
                    $('#' + elementID).html(xmlhttp.responseText);
                    $body.removeClass("mdlloading");
                    rqstdCallBack();
                }
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    });
}

function openATab(slctr,linkArgs) {
    $('#allOtherInputData99').val(0);
    $body = $("body");
    $body.addClass("mdlloading");
    var targ = slctr;
    $("body").css("padding","0px 0px 0px 0px");
    if (linkArgs.indexOf("grp=210&typ") !== -1 ||
        linkArgs.indexOf("grp=40&typ=1") !== -1 ||
        linkArgs.indexOf("grp=60&typ=1") !== -1) {
        $body = $("body");
        var $this = $(slctr + 'tab');
        var targ = slctr;
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                if (linkArgs.indexOf("grp=210&typ") !== -1) {
                    $(targ).html(xmlhttp.responseText);
                    $body.removeClass("mdlloading");
                    $("#usrnm").focus();
                } else if (linkArgs.indexOf("grp=40&typ=1") !== -1) {
                    prepareNotices(linkArgs,$body,targ,xmlhttp.responseText);
                } else {
                    $(targ).html(xmlhttp.responseText);
                    $body.removeClass("mdlloading");
                }
            }
            $body.removeClass("mdlloading");
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    } else {
        getMsgAsync('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            var $this = $(slctr + 'tab');
            var targ = slctr;
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    if (linkArgs.indexOf("grp=50&typ=1") !== -1) {
                        loadScript("js_self/self_prsn.js?v=" + jsFilesVrsn,function () {
                            $this.tab('show');
                            prepareProfile(linkArgs,$body,targ,xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=45&typ=1") !== -1) {
                        prepareInbox(linkArgs,$body,targ,xmlhttp.responseText);
                    } else if (linkArgs.indexOf("grp=9&typ=1") !== -1) {
                        loadScript("js_self/self_rpt.js?v=" + jsFilesVrsn,function () {
                            $this.tab('show');
                            prepareRpts(linkArgs,$body,targ,xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=80&typ=1") !== -1) {
                        loadScript("js_self/self_pay.js?v=" + jsFilesVrsn,function () {
                            $this.tab('show');
                            prepareSelfPay(linkArgs,$body,targ,xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=110&typ=1") !== -1) {
                        loadScript("js_self/self_aca.js?v=" + jsFilesVrsn,function () {
                            $this.tab('show');
                            prepareSelfAca(linkArgs,$body,targ,xmlhttp.responseText);
                        });
                    } else {
                        $(targ).html(xmlhttp.responseText);
                        $body.removeClass("mdlloading");
                    }
                    $body.removeClass("mdlloading");
                }
            };
            xmlhttp.open("POST","index.php",true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xmlhttp.send(linkArgs.trim());
        });
    }
}

function openATab1(slctr,linkArgs) {
    $('#allOtherInputData99').val(0);
    $body = $("body");
    $body.addClass("mdlloading");
    var $this = $(slctr + 'tab');
    var targ = slctr;
    $("body").css("padding","0px 0px 0px 0px");
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        var $this = $(slctr + 'tab');
        var targ = slctr;
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                if (linkArgs.indexOf("grp=45&typ=1") !== -1) {
                    $this.tab('show');
                    prepareInbox(linkArgs,$body,targ,xmlhttp.responseText);
                } else if (linkArgs.indexOf("grp=8&typ=1") !== -1) {
                    loadScript("app/prs/prsn.js?v=" + jsFilesVrsn,function () {
                        $this.tab('show');
                        prepareProfile(linkArgs,$body,targ,xmlhttp.responseText);
                    });
                } else if (linkArgs.indexOf("grp=40&typ=3") !== -1) {
                    $this.tab('show');
                    prepareNotices(linkArgs,$body,targ,xmlhttp.responseText);
                } else if (linkArgs.indexOf("grp=3&typ=1") !== -1) {
                    if (linkArgs.indexOf("pg=1&vtyp=4") !== -1) {
                        $this.tab('show');
                        prepareUsrPrfl(linkArgs,$body,targ,xmlhttp.responseText);
                    } else {
                        loadScript("app/sec/sys_admin.js?v=" + jsFilesVrsn,function () {
                            $this.tab('show');
                            prepareSysAdmin(linkArgs,$body,targ,xmlhttp.responseText);
                        });
                    }
                } else if (linkArgs.indexOf("grp=17&typ=1") !== -1) {
                    loadScript("app/mcf/mcf2.js?v=" + jsFilesVrsn,function () { });
                    loadScript("app/mcf/mcf.js?v=" + jsFilesVrsn,function () {
                        $this.tab('show');
                        prepareMcf(linkArgs,$body,targ,xmlhttp.responseText);
                    });
                } else {
                    $(targ).html(xmlhttp.responseText);
                    $this.tab('show');
                    $body.removeClass("mdlloading");
                }
                $body.removeClass("mdlloading");
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    });

}

function prepareUsrPrfl(lnkArgs,htBody,targ,rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=4") !== -1) {
            if (!$.fn.DataTable.isDataTable('#usrPrflTable')) {
                var table1 = $('#usrPrflTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#usrPrflTable').wrap('<div class="dataTables_scroll" />');
            }
            $('#usrPrflForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
        htBody.removeClass("mdlloading");
    });
}

function getUsrPrfl(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#usrPrflSrchFor").val() === 'undefined' ? '%' : $("#usrPrflSrchFor").val();
    var srchIn = 'Role Name';
    var pageNo = typeof $("#usrPrflPageNo").val() === 'undefined' ? 1 : $("#usrPrflPageNo").val();
    var limitSze = typeof $("#usrPrflDsplySze").val() === 'undefined' ? 10 : $("#usrPrflDsplySze").val();
    var sortBy = typeof $("#usrPrflSortBy").val() === 'undefined' ? '' : $("#usrPrflSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab1(slctr,linkArgs);
}

function enterKeyFuncUsrPrfl(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getUsrPrfl(actionText,slctr,linkArgs);
    }
}

function prepareNotices(lnkArgs,htBody,targ,rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=1") !== -1) {
            if (!$.fn.DataTable.isDataTable('#allnoticesTable')) {
                var table1 = $('#allnoticesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allnoticesTable').wrap('<div class="table-responsive">');
            }
            $('#allnoticesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&vtyp=5") !== -1) {
            var fileLink = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="fa fa-file"/> Upload',
                    tooltip: 'Upload File Inline Within Message Body',
                    click: function () {
                        $(function () {
                            $("#allOtherFileInput1").change(function () {
                                var fileName = $(this).val();
                                var input = document.getElementById('allOtherFileInput1');
                                sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                    var inptUrl = $("#allOtherInputData1").val();
                                    var inptText = $("#allOtherInputData2").val();
                                    var inptNwWndw = $("#allOtherInputData2").val();
                                    if (inptText === "") {
                                        inptText = "Read More...";
                                    }
                                    if (inptNwWndw === "") {
                                        inptNwWndw = true;
                                    }
                                    $('#fdbckMsgBody').summernote('createLink',{
                                        text: inptText,
                                        url: inptUrl,
                                        newWindow: inptNwWndw
                                    });
                                });
                            });
                        });
                        performFileClick('allOtherFileInput1');
                    }
                });
                return button.render();
            };
            $('#fdbckMsgBody').summernote({
                minHeight: 200,
                focus: true,
                disableDragAndDrop: false,
                dialogsInBody: true,
                toolbar: [
                    ['style',['style']],
                    ['style',['bold','italic','underline','clear']],
                    ['font',['strikethrough','superscript','subscript']],
                    ['fontsize',['fontsize']],
                    ['fontname',['fontname']],
                    ['color',['color']],
                    ['para',['ul','ol','paragraph','height']],
                    ['height',['height']],
                    ['table',['table']],
                    ['insert',['link','picture','video','hr']],
                    ['view',['fullscreen']],
                    ['help',['help']],
                    ['misc',['print']],
                    ['mybutton',['upload']]
                ],
                buttons: {
                    upload: fileLink
                },
                callbacks: {
                    onImageUpload: function (file,editor,welEditable) {
                        sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#fdbckMsgBody').summernote("insertImage",inptUrl,'filename');
                        });
                    }
                }
            });
            $('.note-editable').trigger('focus');
            $('#cmntsFdbckForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&vtyp=6") !== -1) {
            if (!$.fn.DataTable.isDataTable('#allForumsTable')) {
                var table1 = $('#allForumsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "responsive": true,
                    "autoWidth": true
                });
                $('#allForumsTable').wrap('<div class="table-responsive">');
            }
        } else if (lnkArgs.indexOf("&vtyp=7") !== -1) {
            var fileLink = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="fa fa-file"/> Upload',
                    tooltip: 'Upload File Inline Within Message Body',
                    click: function () {
                        $(function () {
                            $("#allOtherFileInput1").change(function () {
                                var fileName = $(this).val();
                                var input = document.getElementById('allOtherFileInput1');
                                sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                    var inptUrl = $("#allOtherInputData1").val();
                                    var inptText = $("#allOtherInputData2").val();
                                    var inptNwWndw = $("#allOtherInputData2").val();
                                    if (inptText === "") {
                                        inptText = "Read More...";
                                    }
                                    if (inptNwWndw === "") {
                                        inptNwWndw = true;
                                    }
                                    $('#articleNwCmmntsMsg').summernote('createLink',{
                                        text: inptText,
                                        url: inptUrl,
                                        newWindow: inptNwWndw
                                    });
                                });
                            });
                        });
                        performFileClick('allOtherFileInput1');
                    }
                });
                return button.render();
            };
            $('#articleNwCmmntsMsg').summernote({
                minHeight: 100,
                focus: true,
                disableDragAndDrop: false,
                dialogsInBody: true,
                toolbar: [
                    ['style',['bold','italic','underline','clear']],
                    ['font',['strikethrough','superscript','subscript']],
                    ['fontsize',['fontsize']],
                    ['fontname',['fontname']],
                    ['color',['color']],
                    ['para',['ul','ol','paragraph','height']],
                    ['table',['table']],
                    ['insert',['link','picture','video','hr']],
                    ['mybutton',['upload']]
                ],
                buttons: {
                    upload: fileLink
                },
                callbacks: {
                    onImageUpload: function (file,editor,welEditable) {
                        sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#articleNwCmmntsMsg').summernote("insertImage",inptUrl,'filename');
                        });
                    }
                }
            });
            $('#articleNwCmmntsMsg').summernote('reset');
        } else if (lnkArgs.indexOf("&vtyp=8") !== -1) {
            var fileLink = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="fa fa-file"/> Upload',
                    tooltip: 'Upload File Inline Within Message Body',
                    click: function () {
                        $(function () {
                            $("#allOtherFileInput1").change(function () {
                                var fileName = $(this).val();
                                var input = document.getElementById('allOtherFileInput1');
                                sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                    var inptUrl = $("#allOtherInputData1").val();
                                    var inptText = $("#allOtherInputData2").val();
                                    var inptNwWndw = $("#allOtherInputData2").val();
                                    if (inptText === "") {
                                        inptText = "Read More...";
                                    }
                                    if (inptNwWndw === "") {
                                        inptNwWndw = true;
                                    }
                                    $('#articleNwCmmntsMsg').summernote('createLink',{
                                        text: inptText,
                                        url: inptUrl,
                                        newWindow: inptNwWndw
                                    });
                                });
                            });
                        });
                        performFileClick('allOtherFileInput1');
                    }
                });
                return button.render();
            };
            $('#articleNwCmmntsMsg').summernote({
                minHeight: 100,
                focus: true,
                disableDragAndDrop: false,
                dialogsInBody: true,
                toolbar: [
                    ['style',['bold','italic','underline','clear']],
                    ['font',['strikethrough','superscript','subscript']],
                    ['fontsize',['fontsize']],
                    ['fontname',['fontname']],
                    ['color',['color']],
                    ['para',['ul','ol','paragraph','height']],
                    ['table',['table']],
                    ['insert',['link','picture','video','hr']],
                    ['mybutton',['upload']]
                ],
                buttons: {
                    upload: fileLink
                },
                callbacks: {
                    onImageUpload: function (file,editor,welEditable) {
                        sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#articleNwCmmntsMsg').summernote("insertImage",inptUrl,'filename');
                        });
                    }
                }
            });
        } else if (lnkArgs.indexOf("&vtyp=10") !== -1 ||
            lnkArgs.indexOf("&vtyp=11") !== -1) {
            $('#articleNwCmmntsMsg').summernote('reset');
        }
        htBody.removeClass("mdlloading");
    });
}

function prepareDashboard(lnkArgs,htBody,targ,rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=0") !== -1) { }
        $('.form_date').datetimepicker({
            format: "dd-M-yyyy",
            language: 'en',
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 2,
            maxView: 4,
            forceParse: true
        });
        htBody.removeClass("mdlloading");
    });
}

function getAllNotices(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#allnoticesSrchFor").val() === 'undefined' ? '%' : $("#allnoticesSrchFor").val();
    /*var srchIn = typeof $("#allnoticesSrchIn").val() === 'undefined' ? 'Both' : $("#allnoticesSrchIn").val();*/
    var pageNo = typeof $("#allnoticesPageNo").val() === 'undefined' ? 1 : $("#allnoticesPageNo").val();
    var limitSze = typeof $("#allnoticesDsplySze").val() === 'undefined' ? 5 : $("#allnoticesDsplySze").val();
    var sortBy = typeof $("#allnoticesSortBy").val() === 'undefined' ? 'Date Published' : $("#allnoticesSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=All&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncNotices(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllNotices(actionText,slctr,linkArgs);
    }
}

function getAllComments(actionText,slctr,linkArgs,ttlCmnts,srcBtn) {
    var srchFor = '%';
    var srchIn = 'All';
    var pageNo = typeof $("#allcommentsPageNo1").val() === 'undefined' ? 1 : $("#allcommentsPageNo1").val();
    var sbmtdNoticeID = typeof $("#allcommentsArticleID").val() === 'undefined' ? 1 : $("#allcommentsArticleID").val();
    var limitSze = 10;
    var sortBy = "";
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    var shwHide = $('#shwCmmntsBtn').html();
    if (srcBtn <= 1) {
        if (shwHide.indexOf('Show') !== -1) {
            $('#cmmntsNavBtns').removeClass('hideNotice');
            $('#cmmntsDetailMsgsCntnr').removeClass('hideNotice');
            $('#shwCmmntsRfrshBtn').removeClass('hideNotice');
            $('#nwCmmntsBtn').removeClass('hideNotice');
            $('#shwCmmntsBtn').html(' « Hide Comments');
        } else {
            $('#cmmntsNavBtns').addClass('hideNotice');
            $('#cmmntsDetailMsgsCntnr').addClass('hideNotice');
            $('#shwCmmntsRfrshBtn').addClass('hideNotice');
            $('#nwCmmntsBtn').addClass('hideNotice');
            $('#shwCmmntsBtn').html('Show Comments (<span style="color:whitesmoke;">' + ttlCmnts + '</span>) »');
            return false;
        }
    }
    linkArgs = linkArgs + "&sbmtdNoticeID=" + sbmtdNoticeID + "&searchfor=" + srchFor + "&searchin=All&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr,linkArgs);
}

function getMoreComments(actionText,slctr,linkArgs) {
    var srchFor = '%';
    var srchIn = 'All';
    var pageNo = typeof $("#onenoticesPageNo1").val() === 'undefined' ? 1 : $("#onenoticesPageNo1").val();
    var sbmtdNoticeID = typeof $("#allcommentsArticleID").val() === 'undefined' ? 1 : $("#allcommentsArticleID").val();
    var prntCmmntID = typeof $("#onenoticesCmntID1").val() === 'undefined' ? 1 : $("#onenoticesCmntID1").val();
    var limitSze = 10;
    var sortBy = "";
    pageNo = parseInt(pageNo) + 1;
    linkArgs = linkArgs + "&prntCmmntID=" + prntCmmntID + "&sbmtdNoticeID=" + sbmtdNoticeID + "&searchfor1=" + srchFor + "&searchin1=All&pageNo1=" + pageNo + "&limitSze1=" + limitSze + "&sortBy1=" + sortBy;
    openATab(slctr,linkArgs);
}

function getMoreComments1(actionText,slctr,linkArgs) {
    var srchFor = '%';
    var srchIn = 'All';
    var pageNo = typeof $("#onenoticesPageNo1").val() === 'undefined' ? 1 : $("#onenoticesPageNo1").val();
    var sbmtdNoticeID = typeof $("#allcommentsArticleID").val() === 'undefined' ? 1 : $("#allcommentsArticleID").val();
    var limitSze = 10;
    var sortBy = "";
    pageNo = parseInt(pageNo) + 1;
    linkArgs = linkArgs + "&sbmtdNoticeID=" + sbmtdNoticeID + "&searchfor1=" + srchFor + "&searchin1=All&pageNo1=" + pageNo + "&limitSze1=" + limitSze + "&sortBy1=" + sortBy;
    openATab(slctr,linkArgs);
}

function newComments(crntPrnCmntID) {
    $("#crntPrnCmntID").val(crntPrnCmntID);
    $('#cmmntsNewMsgs').removeClass('hideNotice');
}

function getOneNotice(actionText,slctr,linkArgs,srcBtn,srcBtnNo) {
    var srchFor = '%';
    var srchIn = 'All';
    var pageNo = 1;
    var limitSze = 1;
    var sortBy = "";
    if (srcBtn <= 0) {
        srchFor = typeof $("#allnoticesSrchFor").val() === 'undefined' ? '%' : $("#allnoticesSrchFor").val();
        srchIn = 'All';
        pageNo = srcBtnNo;
        limitSze = 1;
        sortBy = typeof $("#allnoticesSortBy").val() === 'undefined' ? '' : $("#allnoticesSortBy").val();
    } else {
        srchFor = typeof $("#onenoticesSrchFor").val() === 'undefined' ? '%' : $("#onenoticesSrchFor").val();
        srchIn = 'All';
        pageNo = typeof $("#onenoticesPageNo").val() === 'undefined' ? 1 : $("#onenoticesPageNo").val();
        limitSze = 1;
        sortBy = typeof $("#onenoticesSortBy").val() === 'undefined' ? '' : $("#onenoticesSortBy").val();
    }
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr,linkArgs);
}

function getOneNoticeForm(elementID,modalBodyID,titleElementID,formElementID,
    formTitle,articleID,vtyp,pgNo) {
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");

        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#' + titleElementID).html(formTitle);
                $('#' + modalBodyID).html(xmlhttp.responseText);
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "dd-M-yyyy hh:ii:ss",
                        language: 'en',
                        weekStart: 0,
                        todayBtn: true,
                        autoclose: true,
                        todayHighlight: true,
                        keyboardNavigation: true,
                        startView: 2,
                        minView: 0,
                        maxView: 4,
                        forceParse: true
                    });
                });

                $('#articleIntroMsg').summernote({
                    minHeight: 145,
                    focus: true,
                    disableDragAndDrop: false,
                    dialogsInBody: true,
                    toolbar: [
                        ['style',['style']],
                        ['style',['bold','italic','underline','clear']],
                        ['font',['strikethrough','superscript','subscript']],
                        ['fontsize',['fontsize']],
                        ['fontname',['fontname']],
                        ['color',['color']],
                        ['para',['ul','ol','paragraph','height']],
                        ['table',['table']],
                        ['insert',['link','picture','hr']],
                        ['view',['fullscreen','codeview']]
                    ],
                    callbacks: {
                        onImageUpload: function (file,editor,welEditable) {
                            sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                $('#articleIntroMsg').summernote("insertImage",inptUrl,'filename');
                            });
                        }
                    }
                });
                var fileLink = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="fa fa-file"/> Upload',
                        tooltip: 'Upload File Inline Within Message Body',
                        click: function () {
                            $(function () {
                                $("#allOtherFileInput1").change(function () {
                                    var fileName = $(this).val();
                                    var input = document.getElementById('allOtherFileInput1');
                                    sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                        var inptUrl = $("#allOtherInputData1").val();
                                        var inptText = $("#allOtherInputData2").val();
                                        var inptNwWndw = $("#allOtherInputData2").val();
                                        if (inptText === "") {
                                            inptText = "Read More...";
                                        }
                                        if (inptNwWndw === "") {
                                            inptNwWndw = true;
                                        }
                                        $('#articleBodyText').summernote('createLink',{
                                            text: inptText,
                                            url: inptUrl,
                                            newWindow: inptNwWndw
                                        });
                                    });
                                });
                            });
                            performFileClick('allOtherFileInput1');
                        }
                    });
                    return button.render();
                };
                $('#articleBodyText').summernote({
                    minHeight: 270,
                    focus: true,
                    disableDragAndDrop: false,
                    dialogsInBody: true,
                    toolbar: [
                        ['style',['style']],
                        ['style',['bold','italic','underline','clear']],
                        ['font',['strikethrough','superscript','subscript']],
                        ['fontsize',['fontsize']],
                        ['fontname',['fontname']],
                        ['color',['color']],
                        ['para',['ul','ol','paragraph','height']],
                        ['height',['height']],
                        ['table',['table']],
                        ['insert',['link','picture','video','hr']],
                        ['view',['fullscreen','codeview']],
                        ['help',['help']],
                        ['misc',['print']],
                        ['mybutton',['upload']]
                    ],
                    buttons: {
                        upload: fileLink
                    },
                    callbacks: {
                        onImageUpload: function (file,editor,welEditable) {
                            sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                $('#articleBodyText').summernote("insertImage",inptUrl,'filename');
                            });
                        }
                    }
                });
                if (vtyp != 3) {
                    /*Do Nothing*/
                } else {
                    var markupStr1 = typeof $("#articleIntroMsgDecoded").val() === 'undefined' ? '' : $("#articleIntroMsgDecoded").val();
                    var markupStr2 = typeof $("#articleBodyTextDecoded").val() === 'undefined' ? '' : $("#articleBodyTextDecoded").val();
                    $('#articleIntroMsg').summernote('code',urldecode(markupStr1));
                    $('#articleBodyText').summernote('code',urldecode(markupStr2));
                }
                $('.note-editable').trigger('focus');
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $body.removeClass("mdlloading");
                $(document).ready(function () {
                    $('#' + formElementID).submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                });
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("grp=40&typ=3&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdNoticeID=" + articleID);
    });
}

function performFileClick(elemId) {
    var elem = document.getElementById(elemId);
    if (elem && document.createEvent) {
        var evt = document.createEvent("MouseEvents");
        evt.initEvent("click",true,false);
        elem.dispatchEvent(evt);
    }
}

function sendNoticesFile(file,editor,welEditable,fileTypes,callBackFunc) {
    var data1 = new FormData();
    data1.append("file",file);
    if (fileTypes !== "IMAGES") {
        $.ajax({
            url: "../dwnlds/uploader1.php",
            data: data1,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                $("#allOtherInputData1").val(data);
                callBackFunc();
            },
            error: function (jqXHR,textStatus,errorThrown) {
                console.log(textStatus + " " + errorThrown);
            }
        });
    } else {
        $.ajax({
            url: "../dwnlds/uploader.php",
            data: data1,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                $("#allOtherInputData1").val(data);
                callBackFunc();
            },
            error: function (jqXHR,textStatus,errorThrown) {
                console.log(textStatus + " " + errorThrown);
            }
        });
    }
}

function grpTypNoticesChange() {
    var lovChkngElementVal = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    lovNm = "";
    if (lovChkngElementVal === "Everyone" || lovChkngElementVal === "Public") {
        $('#groupName').attr("disabled","true");
        $('#groupName').val("");
        $('#groupNameLbl').attr("disabled","true");
    } else {
        $('#groupName').removeAttr("disabled");
        $('#groupName').val("");
        $('#groupNameLbl').removeAttr("disabled");
    }
}

function getNoticeLovs(elementID,titleElementID,modalBodyID,lovNm,criteriaID,
    criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
    selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,addtnlWhere,callBackFunc) {
    /*alert(lovChkngElementVal);*/
    var lovChkngElementVal = typeof $("#grpType").val() === 'undefined' ? "" : $("#grpType").val();
    if (lovChkngElementVal.trim() === "") {
        lovChkngElementVal = typeof $("#payMassPyGrpType").val() === 'undefined' ? "" : $("#payMassPyGrpType").val();
    }
    lovNm = "";
    if (lovChkngElementVal === "Divisions/Groups") {
        lovNm = "Divisions/Groups";
    } else if (lovChkngElementVal === "Grade") {
        lovNm = "Grades";
    } else if (lovChkngElementVal === "Job") {
        lovNm = "Jobs";
    } else if (lovChkngElementVal === "Position") {
        lovNm = "Positions";
    } else if (lovChkngElementVal === "Site/Location") {
        lovNm = "Sites/Locations";
    } else if (lovChkngElementVal === "Person Type") {
        lovNm = "Person Types";
    } else if (lovChkngElementVal === "Single Person") {
        lovNm = "All Person IDs";
    } else {
        return false;
    }
    getLovsPage(elementID,titleElementID,modalBodyID,lovNm,criteriaID,
        criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
        selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,addtnlWhere,callBackFunc)
}

function getNoticeLovsTblr(elementID,titleElementID,modalBodyID,lovNm,criteriaID,
    criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
    selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,addtnlWhere,
    grpTypeElmntID,callBackFunc) {
    var lovChkngElementVal = typeof $("#" + grpTypeElmntID).val() === 'undefined' ? 10 : $("#" + grpTypeElmntID).val();
    lovNm = "";
    if (lovChkngElementVal === "Divisions/Groups") {
        lovNm = "Divisions/Groups";
    } else if (lovChkngElementVal === "Grade") {
        lovNm = "Grades";
    } else if (lovChkngElementVal === "Job") {
        lovNm = "Jobs";
    } else if (lovChkngElementVal === "Position") {
        lovNm = "Positions";
    } else if (lovChkngElementVal === "Site/Location") {
        lovNm = "Sites/Locations";
    } else if (lovChkngElementVal === "Person Type") {
        lovNm = "Person Types";
    } else if (lovChkngElementVal === "Single Person") {
        lovNm = "All Person IDs";
    } else {
        return false;
    }
    getLovsPage(elementID,titleElementID,modalBodyID,lovNm,criteriaID,
        criteriaID2,criteriaID3,chkOrRadio,mustSelSth,
        selVals,valueElmntID,descElemntID,actionText,colNoForChkBxCmprsn,addtnlWhere,callBackFunc)
}

function saveOneNoticeForm(actionText,slctr,linkArgs) {
    var articleCategory = typeof $("#articleCategory").val() === 'undefined' ? '' : $("#articleCategory").val();
    var articleLoclClsfctn = typeof $("#articleLoclClsfctn").val() === 'undefined' ? '' : $("#articleLoclClsfctn").val();
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var groupID = typeof $("#groupID").val() === 'undefined' ? '' : $("#groupID").val();
    var datePublished = typeof $("#datePublished").val() === 'undefined' ? '' : $("#datePublished").val();
    var articleTitle = typeof $("#articleTitle").val() === 'undefined' ? '' : $("#articleTitle").val();
    var articleIntroMsg = typeof $("#articleIntroMsg").val() === 'undefined' ? '' : ($('#articleIntroMsg').summernote('code'));
    var articleBodyText = typeof $("#articleBodyText").val() === 'undefined' ? '' : ($('#articleBodyText').summernote('code'));
    var articleID = typeof $("#articleID").val() === 'undefined' ? -1 : $("#articleID").val();
    /*urlencode*/
    if (articleCategory === "") {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Category cannot be empty!</span></p>'
        });
        return false;
    }
    if (articleLoclClsfctn === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Classification cannot be empty!</span></p>'
        });
        return false;
    }
    if (articleTitle === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Title cannot be empty!</span></p>'
        });
        return false;
    }
    if (articleIntroMsg === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Intro Message cannot be empty!</span></p>'
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Forum/Notice',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Forum/Notice...Please Wait...</p>',
        callback: function () {
            getAllNotices(actionText,slctr,linkArgs);
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 40,
                    typ: 3,
                    pg: 0,
                    q: 'UPDATE',
                    actyp: 1,
                    articleID: articleID,
                    articleCategory: articleCategory,
                    articleLoclClsfctn: articleLoclClsfctn,
                    grpType: grpType,
                    groupID: groupID,
                    datePublished: datePublished,
                    articleTitle: articleTitle,
                    articleIntroMsg: articleIntroMsg,
                    articleBodyText: articleBodyText
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function closeNoticeForm(actionText,slctr,linkArgs) {
    $('#myFormsModalLg').modal('hide');
    /*$('#myFormsModalLg').html("");
     $('#modal').modal('toggle');*/
    getAllNotices(actionText,slctr,linkArgs);
}

function delOneNotice(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#allnoticesRow' + rndmNum + '_ArticleID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#allnoticesRow' + rndmNum + '_ArticleID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Notice?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Notice?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: 'Delete Notice?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Notice...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    }
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 40,
                                    typ: 3,
                                    pg: 0,
                                    q: 'DELETE',
                                    actyp: 1,
                                    articleID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find('.bootbox-body').html('Row Removed Successfully!');
                        },500);
                    }
                });
            }
        }
    });
}

function pblshUnplsh(rowIDAttrb,action,actionText,slctr,linkArgs) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#allnoticesRow' + rndmNum + '_ArticleID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#allnoticesRow' + rndmNum + '_ArticleID').val();
    }
    var msgTxt = action + " Notice";
    var dialog = bootbox.confirm({
        title: msgTxt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;font-family:georgia, times;">' + msgTxt + '</span>?<br/></p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: msgTxt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Working...Please Wait...</p>'
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 40,
                                    typ: 3,
                                    pg: 0,
                                    q: 'UPDATE',
                                    actyp: 2,
                                    articleID: pKeyID,
                                    actionNtice: action
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            getAllNotices(actionText,slctr,linkArgs);
                                            dialog1.modal('hide');
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                    dialog1.modal('hide');
                                }
                            });
                        });
                    } else {
                        setTimeout(function () {
                            getAllNotices(actionText,slctr,linkArgs);
                            dialog1.modal('hide');
                        },500);
                    }
                });
            }
        }
    });
}

function sendComment(actionText,slctr,linkArgs) {
    var articleNwCmmntsMsg = typeof $("#articleNwCmmntsMsg").val() === 'undefined' ? '' : ($('#articleNwCmmntsMsg').summernote('code'));
    var allcommentsArticleID = typeof $("#allcommentsArticleID").val() === 'undefined' ? -1 : $("#allcommentsArticleID").val();
    var prntCmntID = typeof $("#crntPrnCmntID").val() === 'undefined' ? -1 : $("#crntPrnCmntID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.removeClass("mdlloading");
        $.ajax({
            method: "POST",
            url: "index.php",
            data: {
                grp: 40,
                typ: 1,
                pg: 0,
                q: 'UPDATE',
                actyp: 3,
                articleID: allcommentsArticleID,
                prntCmntID: prntCmntID,
                articleComment: articleNwCmmntsMsg
            },
            success: function (result1) {
                getAllComments(actionText,slctr,linkArgs,3);
                $('#articleNwCmmntsMsg').summernote('reset');
            },
            error: function (jqXHR1,textStatus1,errorThrown1) {
                console.log(errorThrown1);
            }
        });
    });
}

function sendComment2(actionText,slctr,linkArgs) {
    var articleNwCmmntsMsg = typeof $("#articleNwCmmntsMsg2").val() === 'undefined' ? '' : $('#articleNwCmmntsMsg2').val();
    var allcommentsArticleID = typeof $("#allcommentsArticleID").val() === 'undefined' ? -1 : $("#allcommentsArticleID").val();
    var prntCmntID = typeof $("#crntPrnCmntID").val() === 'undefined' ? -1 : $("#crntPrnCmntID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.removeClass("mdlloading");
        $.ajax({
            method: "POST",
            url: "index.php",
            data: {
                grp: 40,
                typ: 1,
                pg: 0,
                q: 'UPDATE',
                actyp: 3,
                articleID: allcommentsArticleID,
                prntCmntID: prntCmntID,
                articleComment: articleNwCmmntsMsg
            },
            success: function (result1) {
                getAllComments(actionText,slctr,linkArgs,3);
                $('#articleNwCmmntsMsg').summernote('reset');
                $('#articleNwCmmntsMsg2').val("");
            },
            error: function (jqXHR1,textStatus1,errorThrown1) {
                console.log(errorThrown1);
            }
        });
    });
}

function enterKeyFuncSndCmnt(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        sendComment2(actionText,slctr,linkArgs);
    }
}

function showNoticeDetails(sbmtdNoticeID,ctgry) {
    openATab('#' + noticesElmntNm,'grp=40&typ=1&pg=0&vtyp=4&sbmtdNoticeID=' + sbmtdNoticeID + '&sbmtdNoticeCtgry=' + ctgry);
}

function showArticleDetails(sbmtdNoticeID,ctgry) {
    openATab('#' + noticesElmntNm,'grp=40&typ=1&pg=0&vtyp=4&sbmtdNoticeID=' + sbmtdNoticeID + '&sbmtdNoticeCtgry=' + ctgry);
}

function autoQueueFdbck() {
    var mailCc = typeof $("#fdbckMailCc").val() === 'undefined' ? '' : $("#fdbckMailCc").val();
    var mailAttchmnts = typeof $("#fdbckMailAttchmnts").val() === 'undefined' ? '' : $("#fdbckMailAttchmnts").val();
    var mailSubject = typeof $("#fdbckSubject").val() === 'undefined' ? '' : $("#fdbckSubject").val();

    var bulkMessageBody = typeof $("#fdbckMsgBody").val() === 'undefined' ? '' : ($('#fdbckMsgBody').summernote('code'));

    if (mailSubject.trim() === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Message Title cannot be empty!</span></p>'
        });
        return false;
    }
    if (bulkMessageBody.trim() === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Message Body cannot be empty!</span></p>'
        });
        return false;
    }
    var dialog1 = bootbox.confirm({
        title: 'Send Feedback?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">SEND THIS MESSAGE</span> to the Portal Administrator?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true) {
                var dialog = bootbox.alert({
                    title: 'Sending Feedback',
                    size: 'small',
                    message: '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Sending Messages...Please Wait...</div>',
                    callback: function () {

                    }
                });
                dialog.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");

                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 40,
                                typ: 1,
                                pg: 0,
                                q: 'UPDATE',
                                actyp: 4,
                                mailCc: mailCc,
                                mailAttchmnts: mailAttchmnts,
                                mailSubject: mailSubject,
                                bulkMessageBody: bulkMessageBody
                            },
                            success: function (data) {
                                var elem = document.getElementById('myBar');
                                elem.style.width = data.percent + '%';
                                $("#myInformation").html(data.message);
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                console.log(textStatus + " " + errorThrown);
                                console.warn(jqXHR.responseText);
                            }
                        });
                    });
                });
            }
        }
    });
}

function clearFdbckForm() {
    var dialog = bootbox.confirm({
        title: 'Clear Form?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CLEAR</span> this Form?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true) {
                $("#fdbckMailCc").val('');
                $("#fdbckMailAttchmnts").val('');
                $("#fdbckSubject").val('');
                $('#fdbckMsgBody').summernote('code','<p></p>');
            }
        }
    });
}

function attchFileToFdbck() {
    var crntAttchMnts = $("#fdbckMailAttchmnts").val();
    $("#allOtherFileInput2").change(function () {
        var fileName = $(this).val();
        var input = document.getElementById('allOtherFileInput2');
        sendFdbckFile(input.files[0],function () {
            var inptUrl = $("#allOtherInputData2").val();
            crntAttchMnts = crntAttchMnts + ";" + inptUrl;
            $("#fdbckMailAttchmnts").val(crntAttchMnts);
        });
    });
    performFileClick('allOtherFileInput2');
}

function sendFdbckFile(file,callBackFunc) {
    var data1 = new FormData();
    data1.append("file",file);
    $.ajax({
        url: "../dwnlds/uploader1.php",
        data: data1,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            $("#allOtherInputData2").val(data);
            callBackFunc();
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
        }
    });
}

function checkWkfRqstStatus(pKeyID,pKeyTitle) {
    var lnkArgs = 'grp=45&typ=1&pg=0&vtyp=8&RoutingID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalx','ShowDialog',pKeyTitle,'myFormsModalxTitle','myFormsModalxBody',function () {
        if (!$.fn.DataTable.isDataTable('#gnrlInbxActionsTable')) {
            var table1 = $('#gnrlInbxActionsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#gnrlInbxActionsTable').wrap('<div class="table-responsive">');
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function insertNewRowBe4(tableElmntID,position,inptHtml,callBackFunc) {
    var pos = typeof $('#allOtherInputData99').val() === 'undefined' ? '0' : $('#allOtherInputData99').val();
    if (pos > 0) {
        position = pos;
    }
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            $('#' + tableElmntID + ' tr').off('click');
            $('#' + tableElmntID + ' tr').click(function () {
                var rowIndex = $('#' + tableElmntID + ' tr').index(this);
                $('#allOtherInputData99').val(rowIndex);
            });
        };
    }
    var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
    var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g,nwRndm + "_").replace(/(_WWW123WWW)+/g,nwRndm));
    if ($('#' + tableElmntID + ' > tbody > tr').length <= 0) {
        $('#' + tableElmntID).append(nwInptHtml);
    } else {
        $('#' + tableElmntID + ' > tbody > tr').eq(position).before(nwInptHtml);
    }
    $(function () {
        $('.form_date_tme').datetimepicker({
            format: "dd-M-yyyy hh:ii:ss",
            language: 'en',
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 0,
            maxView: 4,
            forceParse: true
        });
        $('.form_date').datetimepicker({
            format: "dd-M-yyyy",
            language: 'en',
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 2,
            maxView: 4,
            forceParse: true
        });
        $('[data-toggle="tooltip"]').tooltip();
        callBackFunc();
    });
}

function insertNewRowAfta(tableElmntID,position,inptHtml,callBackFunc) {
    var pos = typeof $('#allOtherInputData99').val() === 'undefined' ? '0' : $('#allOtherInputData99').val();
    if (pos > 0) {
        position = pos;
    }
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            $('#' + tableElmntID + ' tr').off('click');
            $('#' + tableElmntID + ' tr').click(function () {
                var rowIndex = $('#' + tableElmntID + ' tr').index(this);
                $('#allOtherInputData99').val(rowIndex);
            });
        };
    }
    var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
    var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g,nwRndm + "_").replace(/(_WWW123WWW)+/g,nwRndm));
    $('#' + tableElmntID).append(nwInptHtml);
    $(function () {
        $('.form_date_tme').datetimepicker({
            format: "dd-M-yyyy hh:ii:ss",
            language: 'en',
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 0,
            maxView: 4,
            forceParse: true
        });
        $('.form_date').datetimepicker({
            format: "dd-M-yyyy",
            language: 'en',
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 2,
            maxView: 4,
            forceParse: true
        });
        $('[data-toggle="tooltip"]').tooltip();
        callBackFunc();
    });
}

function urldecode(str) {
    return unescape(decodeURIComponent(str.replace(/\+/g,' ')));
}

function urlencode(str) {
    return escape(encodeURIComponent(str.replace(/\+/g,' ')));
}

function logOutFunc() {
    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: 'System Alert!',
        message: 'Are you sure you want to Logout?',
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialog) { },
        buttons: [{
            label: 'Cancel',
            icon: 'glyphicon glyphicon-ban-circle',
            action: function (dialogItself) {
                dialogItself.close();
            }
        },{
            label: 'Logout',
            icon: 'glyphicon glyphicon-menu-left',
            cssClass: 'btn-primary',
            action: function (dialogItself) {
                var $button = this;
                $button.disable();
                $button.spin();
                dialogItself.setClosable(false);
                $.ajax({
                    method: "POST",
                    url: "index.php",
                    data: {
                        q: 'logout',
                        grp: 200,
                        typ: 2
                    },
                    success: function (result) {
                        window.location = "index.php";
                    }
                });
            }
        }]
    });
}

function showBootDiagMsg(btitle,bmsg,bsize) {
    if (typeof bsize === 'undefined' || bsize === null) {
        bsize = BootstrapDialog.SIZE_SMALL;
    }

    BootstrapDialog.show({
        size: bsize,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: btitle,
        message: bmsg,
        buttons: [{
            id: 'btn-ok',
            icon: 'glyphicon glyphicon-check',
            label: 'OK',
            cssClass: 'btn-primary',
            autospin: false,
            action: function (dialogRef) {
                dialogRef.close();
            }
        }]
    });
}
var isLgnSccfl = 0;
var curDialogItself = null;
var lgnBtnSsn = null;
var curCallBack = null;

function getMsgAsync(linkArgs,callback) {
    $body = $("body");
    $body.addClass("mdlloading");
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            var sessionvld = xmlhttp.responseText;
            if (sessionvld != 1 ||
                sessionvld == '' ||
                (typeof sessionvld === 'undefined')) {
                sessionvld = '<div class="login"><form role="form" autocomplete="off">' +
                    '<div class="input-group mb-3">' +
                    '<input type="text" class="form-control" placeholder="Username" id="usrnm" name="usrnm"  onkeyup="enterKeyFuncLgn(event);" autofocus>' +
                    '<div class="input-group-append">' +
                    '<div class="input-group-text">' +
                    '<span class="fas fa-user"></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group mb-3">' +
                    '<input type="password" class="form-control" placeholder="Password" id="pwd" name="pwd" value=""  onkeyup="enterKeyFuncLgn(event);">' +
                    '<input type="hidden" id="machdet" name="machdet" value="Unknown">' +
                    '<div class="input-group-append">' +
                    '<div class="input-group-text">' +
                    '<span class="fas fa-lock"></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<p class="label" id="msgArea">' +
                    '<label style="color:red;font-size:12px;text-align: center;">' +
                    '&nbsp;' +
                    '</label>' +
                    '</p>' +
                    '</form></div>';
            }

            if (sessionvld != 1) {
                $body.removeClass("mdlloading");
                BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_SMALL,
                    type: BootstrapDialog.TYPE_DEFAULT,
                    title: 'Session Expired!',
                    message: sessionvld,
                    animate: true,
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    onshow: function (dialogItself) {
                        curDialogItself = dialogItself;
                        curCallBack = callback;
                        lgnBtnSsn = dialogItself.getButton('lgnBtnSsn');
                    },
                    onshown: function (dialogItself) {
                        $('#usrnm').focus();
                        lgnBtnSsn = dialogItself.getButton('lgnBtnSsn');
                    },
                    buttons: [{
                        label: 'Logout',
                        icon: 'glyphicon glyphicon-menu-left',
                        cssClass: 'btn-default',
                        action: function (dialogItself) {
                            var $button = this;
                            $button.disable();
                            $button.spin();
                            dialogItself.setClosable(false);
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    q: 'logout',
                                    grp: 200,
                                    typ: 2
                                },
                                success: function (result) {
                                    window.location = "index.php";
                                }
                            });
                        }
                    },{
                        id: 'lgnBtnSsn',
                        label: 'Login',
                        icon: 'glyphicon glyphicon-menu-right',
                        cssClass: 'btn-primary',
                        action: function (dialogItself) {
                            curDialogItself = dialogItself;
                            curCallBack = callback;
                            var $button = this;
                            $button.disable();
                            $button.spin();
                            dialogItself.setClosable(false);
                            homePageLgn(dialogItself,callback);
                        }
                    }]
                });
            } else {
                callback();
            }
        } else {

        }
    };
    xmlhttp.open("POST","index.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send(linkArgs.trim());
}

function getMsgAsyncSilent(linkArgs,callback) {
    $body = $("body");
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            var sessionvld = xmlhttp.responseText;
            if (sessionvld != 1 ||
                sessionvld == '' ||
                (typeof sessionvld === 'undefined')) {
                sessionvld = '<div class="login"><form role="form" autocomplete="off">' +
                    '<div class="input-group mb-3">' +
                    '<input type="text" class="form-control" placeholder="Username" id="usrnm" name="usrnm"  onkeyup="enterKeyFuncLgn(event);" autofocus>' +
                    '<div class="input-group-append">' +
                    '<div class="input-group-text">' +
                    '<span class="fas fa-user"></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="input-group mb-3">' +
                    '<input type="password" class="form-control" placeholder="Password" id="pwd" name="pwd" value=""  onkeyup="enterKeyFuncLgn(event);">' +
                    '<input type="hidden" id="machdet" name="machdet" value="Unknown">' +
                    '<div class="input-group-append">' +
                    '<div class="input-group-text">' +
                    '<span class="fas fa-lock"></span>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<p class="label" id="msgArea">' +
                    '<label style="color:red;font-size:12px;text-align: center;">' +
                    '&nbsp;' +
                    '</label>' +
                    '</p>' +
                    '</form></div>';
            }

            if (sessionvld != 1) {
                BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_SMALL,
                    type: BootstrapDialog.TYPE_DEFAULT,
                    title: 'Session Expired!',
                    message: sessionvld,
                    animate: true,
                    closable: true,
                    closeByBackdrop: false,
                    closeByKeyboard: false,
                    onshow: function (dialogItself) {
                        curDialogItself = dialogItself;
                        curCallBack = callback;
                        lgnBtnSsn = dialogItself.getButton('lgnBtnSsn');
                    },
                    onshown: function (dialogItself) {
                        $('#usrnm').focus();
                        lgnBtnSsn = dialogItself.getButton('lgnBtnSsn');
                    },
                    buttons: [{
                        label: 'Logout',
                        icon: 'glyphicon glyphicon-menu-left',
                        cssClass: 'btn-default',
                        action: function (dialogItself) {
                            var $button = this;
                            $button.disable();
                            $button.spin();
                            dialogItself.setClosable(false);
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    q: 'logout',
                                    grp: 200,
                                    typ: 2
                                },
                                success: function (result) {
                                    window.location = "index.php";
                                }
                            });
                        }
                    },{
                        id: 'lgnBtnSsn',
                        label: 'Login',
                        icon: 'glyphicon glyphicon-menu-right',
                        cssClass: 'btn-primary',
                        action: function (dialogItself) {
                            curDialogItself = dialogItself;
                            curCallBack = callback;
                            var $button = this;
                            $button.disable();
                            $button.spin();
                            dialogItself.setClosable(false);
                            homePageLgn(dialogItself,callback);
                        }
                    }]
                });
            } else {
                callback();
            }
        } else {

        }
    };
    xmlhttp.open("POST","index.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send(linkArgs.trim());
}

function enterKeyFuncLgn(e) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        lgnBtnSsn.disable();
        lgnBtnSsn.spin();
        curDialogItself.setClosable(false);
        homePageLgn(curDialogItself,curCallBack);
    }
    //return false;
}

function homePageLgn(dialogItself,callback) {
    var xmlhttp;
    var usrNm = "";
    var old_pswd = "";
    var lnkArgs = "";
    var machdet = "";
    usrNm = document.getElementById("usrnm").value;
    old_pswd = document.getElementById("pwd").value;
    machdet = document.getElementById("machdet").value;
    if (usrNm === "" || usrNm === null) {
        showBootDiagMsg('System Alert!','User Name cannot be empty!');
        return false;
    }
    if (old_pswd === "" || old_pswd === null) {
        showBootDiagMsg('System Alert!','Password cannot be empty!');
        return false;
    }
    lnkArgs = "grp=200&typ=1&usrnm=" + usrNm + "&pwd=" + old_pswd + "&machdet=" + machdet + "&screenwdth=" + screen.width;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function () {
        //var newDoc = document.open("text/html", "replace");
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            //newDoc.close();
            var rspns = xmlhttp.responseText;
            if (rspns.indexOf('change password') > -1 ||
                rspns.indexOf('select role') > -1) {
                dialogItself.setClosable(true);
                isLgnSccfl = 1;
                dialogItself.close();
                $body = $("body");
                $body.addClass("mdlloading");
                if (document.getElementById("lgnProfileUname").innerHTML == "GUEST") {
                    window.location = "index.php";
                } else {
                    callback();
                }
            } else {
                lgnBtnSsn.enable();
                lgnBtnSsn.stopSpin();
                dialogItself.setClosable(true);
                isLgnSccfl = 0;
                document.getElementById("msgArea").innerHTML = "<span style=\"color:red;font-size:12px;text-align: center;margin-top:0px;\">&nbsp;" + rspns + "</span>";
            }
        } else {
            isLgnSccfl = 0;
            document.getElementById("msgArea").innerHTML = "<img style=\"width:105px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='../cmn_images/ajax-loader2.gif'/><span style=\"color:blue;font-size:11px;text-align: center;margin-top:0px;\">Loading...Please Wait...</span>";
        }
    };

    xmlhttp.open("POST","index.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send(lnkArgs); //+ "&machdetls=" + machDet
}

function showRoles() {
    window.location = 'index.php';
}

function dwnldAjxCall(linkArgs,elemtnID) {
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $body = $("body");
                $body.removeClass("mdlloading");
                document.getElementById(elemtnID).innerHTML = "&nbsp;";
                window.open(xmlhttp.responseText,'_blank');
            } else {
                document.getElementById(elemtnID).innerHTML = "<p><img style=\"width:80px;height:25px;display:inline;float:left;margin-right:5px;clear: left;\" src='../cmn_images/animated_loading.gif'/>Loading...Please Wait...</p>";
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    });
}

function myIP() {
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("GET","http://api.hostip.info/get_html.php",false);
    var res = "";
    var hostipInfo,ipAddress;
    var t = setTimeout(function () {
        xmlhttp.abort();
        xmlhttp = null;
        res = "Unknown";
    },3000);
    xmlhttp.send();
    if (res === "Unknown") {
        return res;
    } else if (xmlhttp.readyState === 4) {
        var i = 0;
        hostipInfo = xmlhttp.responseText.split("\n");
        for (i = 0; hostipInfo.length >= i; i++) {
            ipAddress = hostipInfo[i].split(":");
            if (ipAddress[0] === "IP") {
                return ipAddress[1];
            }
        }
    }
}

function myCountry() {
    var res = "";
    var xmlhttp;
    var hostipInfo,ipAddress;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("GET","http://api.hostip.info/get_html.php",false);
    var t = setTimeout(function () {
        xmlhttp.abort();
        xmlhttp = null;
        res = "Unknown";
    },3000);
    xmlhttp.send();
    if (res === "Unknown") {
        return res;
    } else if (xmlhttp.readyState === 4) {
        var i;
        hostipInfo = xmlhttp.responseText.split("\n");
        for (i = 0; hostipInfo.length >= i; i++) {
            ipAddress = hostipInfo[i].split(":");
            if (ipAddress[0] === "Country") {
                return ipAddress[1];
            }
        }
    }
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

function toggleCheckBx(elmntID,callback) {
    if ($('#' + elmntID + ':checked').length > 0) {
        $('#' + elmntID).prop('checked',false);
    } else {
        $('#' + elmntID).prop('checked',true);
    }
    callback();
}

function changeImgSrc(input,imgId,imgSrcLocID) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            //$('#img1Test')
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
    var first_part = curr_date + "-" + m_names[curr_month] +
        "-" + curr_year;

    var dformat = first_part + ' ' + [d.getHours().padLeft(),
    d.getMinutes().padLeft(),
    d.getSeconds().padLeft()
    ].join(':');
    return dformat;
}

function isReaderAPIAvlbl() {
    // Check for the various File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        // Great success! All the File APIs are supported.
        return true;
    } else {
        // source: File API availability - http://caniuse.com/#feat=fileapi
        // source: <output> availability - http://html5doctor.com/the-output-element/
        var errMsg = "";
        errMsg += 'The HTML5 APIs used in this form are only available in the following browsers:<br />';
        // 6.0 File API & 13.0 <output>
        errMsg += ' - Google Chrome: 13.0 or later<br />';
        // 3.6 File API & 6.0 <output>
        errMsg += ' - Mozilla Firefox: 6.0 or later<br />';
        // 10.0 File API & 10.0 <output>
        errMsg += ' - Internet Explorer: Not supported (partial support expected in 10.0)<br />';
        // ? File API & 5.1 <output>
        errMsg += ' - Safari: Not supported<br />';
        // ? File API & 9.2 <output>
        errMsg += ' - Opera: Not supported';
        var dialog = bootbox.alert({
            title: 'System Alert',
            size: 'small',
            message: '<p style="color:red;font-weight:bold;">' + errMsg + '</p>',
            callback: function () { }
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
    //$('body').tooltip('dispose');
    $('[data-toggle="tooltip"]').tooltip('destroy');
    var titleTxt = $("#" + htmElementID).val();
    document.getElementById(htmElementID).title = titleTxt;
    //$('[data-toggle="tooltip"]').tooltip();

    $("body").tooltip({
        selector: '[data-toggle=tooltip]'
    });
}

function changeBtnTitleFunc(htmElementID,btnElementID) {
    //$('body').tooltip('dispose');
    $('[data-toggle="tooltip"]').tooltip('destroy');
    var titleTxt = $("#" + htmElementID).val();
    document.getElementById(btnElementID).title = titleTxt;
    //$('[data-toggle="tooltip"]').tooltip();

    $("body").tooltip({
        selector: '[data-toggle=tooltip]'
    });
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
}

function prepareInbox(lnkArgs,htBody,targ,rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        if (lnkArgs.indexOf("&vtyp=0") !== -1) {
            var table = $('#myInbxTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myInbxTable').wrap('<div class="table-responsive">');

            $('.rho-DatePicker').datetimepicker({
                format: 'DD-MMM-YYYY'
            });
            /*
             
             $(function () {
             $('#datetimepicker7').datetimepicker();
             $('#datetimepicker8').datetimepicker({
             useCurrent: false
             });
             $("#datetimepicker7").on("change.datetimepicker", function (e) {
             $('#datetimepicker8').datetimepicker('minDate', e.date);
             });
             $("#datetimepicker8").on("change.datetimepicker", function (e) {
             $('#datetimepicker7').datetimepicker('maxDate', e.date);
             });
             });
             $('.datetimepicker-input').inputmask();
             $('.rho-DatePicker').datetimepicker({
             format: "dd-M-yyyy",
             language: 'en',
             weekStart: 0,
             todayBtn: true,
             autoclose: true,
             todayHighlight: true,
             keyboardNavigation: true,
             startView: 2,
             minView: 2,
             maxView: 4,
             forceParse: true
             });*/
            $('#myInbxTable tbody')
                .on('mouseenter','tr',function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
        }
    });
    htBody.removeClass("mdlloading");
}


function getMyInbx(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#myInbxSrchFor").val() === 'undefined' ? '%' : $("#myInbxSrchFor").val();
    var srchIn = typeof $("#myInbxSrchIn").val() === 'undefined' ? 'Both' : $("#myInbxSrchIn").val();
    var pageNo = typeof $("#myInbxPageNo").val() === 'undefined' ? 1 : $("#myInbxPageNo").val();
    var limitSze = typeof $("#myInbxDsplySze").val() === 'undefined' ? 10 : $("#myInbxDsplySze").val();
    var sortBy = typeof $("#myInbxSortBy").val() === 'undefined' ? '' : $("#myInbxSortBy").val();
    var qStrtDte = typeof $("#myInbxStrtDate").val() === 'undefined' ? '' : $("#myInbxStrtDate").val();
    var qEndDte = typeof $("#myInbxEndDate").val() === 'undefined' ? '' : $("#myInbxEndDate").val();
    var qActvOnly = $('#myInbxShwActvNtfs:checked').length > 0;
    var qNonLgn = $('#myInbxShwNonLgnNtfs:checked').length > 0;
    var qNonAknwldg = $('#myInbxShwNonAknwNtfs:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
        "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qActvOnly=" + qActvOnly + "&qNonLgn=" + qNonLgn + "&qNonAknwldg=" + qNonAknwldg;
    //alert(linkArgs);
    openATab(slctr,linkArgs);
}

function enterKeyFuncMyInbx(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyInbx(actionText,slctr,linkArgs);
    }
}

function getOneMyInbxForm(elementID,modalBodyID,titleElementID,formElementID,
    formTitle,routingID,vtyp,pgNo) {
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#' + titleElementID).html(formTitle);
                $('#' + modalBodyID).html(xmlhttp.responseText);

                $('#myFormsModal').css("z-index","9998!important");
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).one('show.bs.modal',function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $(e.currentTarget).unbind();
                });
                $('#' + elementID).one("hidden.bs.modal",function (e) {
                    $('#myFormsModal').css("z-index","9997!important");
                    $(e.currentTarget).unbind();
                });
                if (!$.fn.DataTable.isDataTable('#myInbxDetTable')) {
                    var table1 = $('#myInbxDetTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#myInbxDetTable').wrap('<div class="dataTables_scroll" />');
                }
                if (!$.fn.DataTable.isDataTable('#myInbxActionsTable')) {
                    var table2 = $('#myInbxActionsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#myInbxActionsTable').wrap('<div class="dataTables_scroll" />');
                }
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $body.removeClass("mdlloading");

                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("grp=45&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&RoutingID=" + routingID);
    });
}

function getAllInbx(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#allInbxSrchFor").val() === 'undefined' ? '%' : $("#allInbxSrchFor").val();
    var srchIn = typeof $("#allInbxSrchIn").val() === 'undefined' ? 'Both' : $("#allInbxSrchIn").val();
    var pageNo = typeof $("#allInbxPageNo").val() === 'undefined' ? 1 : $("#allInbxPageNo").val();
    var limitSze = typeof $("#allInbxDsplySze").val() === 'undefined' ? 10 : $("#allInbxDsplySze").val();
    var sortBy = typeof $("#allInbxSortBy").val() === 'undefined' ? '' : $("#allInbxSortBy").val();
    var qStrtDte = typeof $("#allInbxStrtDate").val() === 'undefined' ? '' : $("#allInbxStrtDate").val();
    var qEndDte = typeof $("#allInbxEndDate").val() === 'undefined' ? '' : $("#allInbxEndDate").val();
    var qActvOnly = $('#allInbxShwActvNtfs:checked').length > 0;
    var qNonLgn = $('#allInbxShwNonLgnNtfs:checked').length > 0;
    var qNonAknwldg = $('#allInbxShwNonAknwNtfs:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
        "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte + "&qActvOnly=" + qActvOnly + "&qNonLgn=" + qNonLgn + "&qNonAknwldg=" + qNonAknwldg;

    openATab(slctr,linkArgs);
}

function enterKeyFuncAllInbx(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllInbx(actionText,slctr,linkArgs);
    }
}

function getOneAllInbxForm(elementID,modalBodyID,titleElementID,formElementID,
    formTitle,routingID,vtyp,pgNo) {
    getMsgAsync('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest) {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                $('#' + titleElementID).html(formTitle);
                $('#' + modalBodyID).html(xmlhttp.responseText);

                $('#myFormsModal').css("z-index","9998!important");
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).one('show.bs.modal',function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                    $(e.currentTarget).unbind();
                });
                $('#' + elementID).one("hidden.bs.modal",function (e) {
                    $('#myFormsModal').css("z-index","9997!important");
                    $(e.currentTarget).unbind();
                });
                $(document).ready(function () {
                    $('#allInbxDetTable').wrap('<div class="table-responsive">');
                    var table1 = $('#allInbxActionsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allInbxActionsTable').wrap('<div class="table-responsive">');
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $body.removeClass("mdlloading");

                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            }
        };
        xmlhttp.open("POST","index.php",true);
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("grp=45&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&RoutingID=" + routingID);
    });
}

function actionProcess(cmpID,RoutingID,actionNm,isResDiag,
    toPrsnLocID,toPrsNm,msgSubjct,msgDate,wkfAppNm) {
    if (actionNm === "Reject") {
        onReject(RoutingID,msgSubjct,msgDate);
    } else if (actionNm === "Request for Information") {
        onInfoRequest(RoutingID,1,msgSubjct,msgDate,toPrsnLocID,toPrsNm);
    } else if (actionNm === "Respond") {
        onResponse(RoutingID,1,msgSubjct,msgDate,toPrsnLocID,toPrsNm);
    } else if (actionNm === "View Attachments") {
        onAttachment(RoutingID,msgSubjct);
    } else {
        onAct(RoutingID,actionNm,isResDiag,
            '',toPrsnLocID,wkfAppNm);
    }
}

function onReject(RoutingID,msgSubjct,msgDate) {
    var lnkArgs = 'grp=45&typ=1&pg=0&vtyp=3&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct + '&msgDate=' + msgDate;
    doAjaxWthCallBck(lnkArgs,'myFormsModalx','ShowDialog','  REJECTION OF SELECTED WORKFLOW DOCUMENT','myFormsModalxTitle','myFormsModalxBody',function () {
        $('#wkfActionMsg').focus();
    });
}

function onInfoRequest(RoutingID,id,msgSubjct,msgDate,toPrsnLocID,toPrsNm) {
    var lnkArgs = 'grp=45&typ=1&pg=0&vtyp=4&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct + '&msgDate=' + msgDate;
    doAjaxWthCallBck(lnkArgs,'myFormsModalx','ShowDialog','  REQUEST INFORMATION ON SELECTED WORKFLOW DOCUMENT','myFormsModalxTitle','myFormsModalxBody',function () {
        $('#wkfActionMsg').focus();
    });
}

function onResponse(RoutingID,id,msgSubjct,msgDate,toPrsnLocID,toPrsNm) {
    var lnkArgs = 'grp=45&typ=1&pg=0&vtyp=5&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct + '&msgDate=' + msgDate + '&toPrsnLocID=' + toPrsnLocID + '&toPrsNm=' + toPrsNm;
    doAjaxWthCallBck(lnkArgs,'myFormsModalx','ShowDialog','  RESPONSE TO INFORMATION REQUEST ON SELECTED WORKFLOW DOCUMENTS','myFormsModalxTitle','myFormsModalxBody',function () {
        $('#wkfActionMsg').focus();
    });
}

function onAttachment(RoutingID,msgSubjct) {
    var lnkArgs = 'grp=45&typ=1&pg=0&vtyp=6&RoutingID=' + RoutingID + '&msgSubjct=' + msgSubjct;
    doAjaxWthCallBck(lnkArgs,'myFormsModalx','ShowDialog',' Attachments for Notification Number: ' + RoutingID,'myFormsModalxTitle','myFormsModalxBody',function () {
        $('#wkfActionMsg').focus();
    });
}

function onReassign(elmntID) {
    var inRoutingIDs = "";
    var inCount = 0;

    var form = document.getElementById(elmntID);
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type === 'checkbox') {
            if (form.elements[i].checked === true) {
                inRoutingIDs = inRoutingIDs + form.elements[i].value.split(";")[0] + '|';
                inCount++;
            }
        }
    }
    if (inRoutingIDs.length > 1) {
        inRoutingIDs = inRoutingIDs.slice(0,-1);
    }
    var lnkArgs = 'grp=45&typ=1&pg=0&vtyp=7&routingIDs=' + inRoutingIDs + '&inCount=' + inCount;
    doAjaxWthCallBck(lnkArgs,'myFormsModalx','ShowDialog','  RE-ASSIGN SELECTED WORKFLOW DOCUMENTS','myFormsModalxTitle','myFormsModalxBody',function () {
        $('#wkfActionMsg').focus();
    });
}

function onAct(RoutingID,actionNm,isResDiag,actionReason,toPrsnLocID,wkfAppNm,wkfSlctdRoutingIDs) {

    var isSelf4Open = false;
    var finalURL = "../index.php";
    if (actionNm === 'Open' ||
        actionNm === 'Reject' ||
        actionNm === 'Request for Information' ||
        actionNm === 'Respond') {
        var dialog = bootbox.alert({
            title: 'Action Pending...',
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Performing Action...Please Wait...</p>',
            callback: function () {
                $('#myFormsModalLg').modal('hide');
                $('#myFormsModalx').modal('hide');
            }
        });
        dialog.init(function () {
            getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                $body = $("body");
                $body.removeClass("mdlloading");

                var formData = new FormData();
                formData.append('grp',1);
                formData.append('typ',11);
                formData.append('q','action_url');
                formData.append('actyp',1);
                formData.append('RoutingID',RoutingID);
                formData.append('actyp',actionNm);
                formData.append('actReason',actionReason);
                formData.append('toPrsLocID',toPrsnLocID);
                $.ajax({
                    method: "POST",
                    url: "index.php",
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        var urlParams = result;

                        if (urlParams.indexOf('q=SELF_') > -1) {
                            isSelf4Open = true;
                            finalURL = "index.php";
                        }
                        if (urlParams.indexOf('|ERROR|') > -1) {
                            setTimeout(function () {
                                dialog.find('.bootbox-body').html(result);
                            },500);
                        } else {
                            $.ajax({
                                method: "POST",
                                url: finalURL,
                                data: urlParams.trim(),
                                success: function (result1) {
                                    setTimeout(function () {
                                        if (isResDiag >= 1) {
                                            dialog.find('.bootbox-body').html(result1);
                                            if (!(typeof $("#allInbxSrchFor").val() === 'undefined')) {
                                                getAllInbx('','#allmodules','grp=45&typ=1&pg=0&vtyp=2&qMaster=1');
                                            }
                                            if (!(typeof $("#myInbxSrchFor").val() === 'undefined')) {
                                                getMyInbx('','#allmodules','grp=45&typ=1&pg=0&vtyp=0');
                                            }
                                        } else {
                                            //Show Larger DIalog
                                            dialog.modal('hide');
                                            if (isSelf4Open === false) {
                                                loadScript("../app/cmncde/cmncde.js?v=" + jsFilesVrsn,function () {
                                                    $('#myFormsModalLxTitle').html('Action Result!');
                                                    result1 = result1 + '<style>label {display: inline-block !important;margin-bottom: 0px !important;}</style>';
                                                    $('#myFormsModalLxBody').html(result1.replace(/<span class="glyphicon glyphicon-th-list"><\/span>/g,"<span class=\"input-group-text rhoclickable\"><i class=\"fas fa-th-list\"></i></span>").replace(/ 15px/g," 7.5px").replace(/"cmn_images/g,"\"../cmn_images").replace(/col-md-/g,"col-sm-").replace(/<caption>/g,"<div class=\"caption basic_person_lg\">").replace(/caption/g,"div"));
                                                    //replace(/col-sm-12/g, "col-sm-12 row")..replace(/<\/label>/g, "</div>")..replace(/class="btn btn-primary btn-file input-group-addon/g, "class=\"input-group-prepend handCursor").replace(/class="btn btn-info btn-file input-group-addon/g, "class=\"input-group-prepend handCursor")
                                                    $('#myFormsModalLx').off('hidden.bs.modal');
                                                    $('#myFormsModalLx').off('show.bs.modal');
                                                    $('#myFormsModalLx').one('show.bs.modal',function (e) {
                                                        $(this).find('.modal-body').css({
                                                            'max-height': '100%'
                                                        });
                                                        $(e.currentTarget).unbind();
                                                    });
                                                    $('#myFormsModalLx').modal({
                                                        backdrop: 'static',
                                                        keyboard: false
                                                    });
                                                    $('.table').each(function (i,el) {
                                                        var tblID = $(el).attr('id');
                                                        if (!$.fn.DataTable.isDataTable('#' + tblID)) {
                                                            $('#' + tblID).DataTable({
                                                                "paging": false,
                                                                "ordering": false,
                                                                "info": false,
                                                                "bFilter": false,
                                                                "scrollX": false
                                                            });
                                                            $('#' + tblID).wrap('<div class="table-responsive">');
                                                        }
                                                    });
                                                    afterShowActions(wkfAppNm);
                                                });
                                            } else {
                                                $('#myFormsModalLxTitle').html('Action Result!');
                                                $('#myFormsModalLxBody').html(result1);

                                                $('#myFormsModalLx').off('hidden.bs.modal');
                                                $('#myFormsModalLx').off('show.bs.modal');
                                                $('#myFormsModalLx').one('show.bs.modal',function (e) {
                                                    $(this).find('.modal-body').css({
                                                        'max-height': '100%'
                                                    });
                                                    $(e.currentTarget).unbind();
                                                });
                                                $('#myFormsModalLx').modal({
                                                    backdrop: 'static',
                                                    keyboard: false
                                                });
                                                afterShowActions(wkfAppNm);
                                            }
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    console.warn(jqXHR.responseText);
                                }
                            });
                        }
                    },
                    error: function (jqXHR,textStatus,errorThrown) {
                        /*dialog.find('.bootbox-body').html(errorThrown);*/
                        console.warn(jqXHR.responseText);
                    }
                });
            });
        });
    } else if (actionNm === 'Re-Assign') {
        var dialog = bootbox.alert({
            title: 'Action Pending...',
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Performing Action...Please Wait...</p>',
            callback: function () {
                $('#myFormsModalLg').modal('hide');
                $('#myFormsModalx').modal('hide');
            }
        });
        dialog.init(function () {
            getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                $body = $("body");
                $body.removeClass("mdlloading");

                var formData = new FormData();
                formData.append('grp',45);
                formData.append('typ',1);
                formData.append('q','UPDATE');
                formData.append('vtyp',0);
                formData.append('actyp',1);
                formData.append('RoutingID',RoutingID);
                formData.append('actionNm',actionNm);
                formData.append('actReason',actionReason);
                formData.append('toPrsLocID',toPrsnLocID);
                formData.append('wkfSlctdRoutingIDs',wkfSlctdRoutingIDs);
                $.ajax({
                    method: "POST",
                    url: "index.php",
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        setTimeout(function () {
                            dialog.find('.bootbox-body').html(result);
                            if (!(typeof $("#allInbxSrchFor").val() === 'undefined')) {
                                getAllInbx('','#allmodules','grp=45&typ=1&pg=0&vtyp=2&qMaster=1');
                            }
                            if (!(typeof $("#myInbxSrchFor").val() === 'undefined')) {
                                getMyInbx('','#allmodules','grp=45&typ=1&pg=0&vtyp=0');
                            }
                        },500);
                    },
                    error: function (jqXHR,textStatus,errorThrown) {
                        /*dialog.find('.bootbox-body').html(errorThrown);*/
                        console.warn(jqXHR.responseText);
                    }
                });
            });
        });
    } else {
        var dialog = bootbox.confirm({
            title: 'Action Pending...',
            size: 'small',
            message: '<p style="text-align:center;">Are you sure you want to Perform this Action <span style="color:red;font-weight:bold;font-style:italic;">(' + actionNm + ')</span> on this Notifications?<br/>Action cannot be Undone!</p>',
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result === true) {
                    var dialog = bootbox.alert({
                        title: 'Action Pending...',
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Performing Action...Please Wait...</p>',
                        callback: function () {
                            $('#myFormsModalLg').modal('hide');
                            $('#myFormsModalx').modal('hide');
                        }
                    });
                    dialog.init(function () {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");

                            var formData = new FormData();
                            formData.append('grp',1);
                            formData.append('typ',11);
                            formData.append('q','action_url');
                            formData.append('actyp',1);
                            formData.append('RoutingID',RoutingID);
                            formData.append('actyp',actionNm);
                            formData.append('actReason',actionReason);
                            formData.append('toPrsLocID',toPrsnLocID);
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: formData,
                                async: true,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (result) {
                                    var urlParams = result;
                                    if (urlParams.indexOf('q=SELF_') > -1) {
                                        isSelf4Open = true;
                                        finalURL = "index.php";
                                    }
                                    if (urlParams.indexOf('|ERROR|') > -1) {
                                        setTimeout(function () {
                                            dialog.find('.bootbox-body').html(result);
                                        },500);
                                    } else {
                                        $.ajax({
                                            method: "POST",
                                            url: finalURL,
                                            data: urlParams.trim(),
                                            success: function (result1) {
                                                setTimeout(function () {
                                                    if (isResDiag >= 1) {
                                                        dialog.find('.bootbox-body').html(result1);
                                                        if (!(typeof $("#allInbxSrchFor").val() === 'undefined')) {
                                                            getAllInbx('','#allmodules','grp=45&typ=1&pg=0&vtyp=2&qMaster=1');
                                                        }
                                                        if (!(typeof $("#myInbxSrchFor").val() === 'undefined')) {
                                                            getMyInbx('','#allmodules','grp=45&typ=1&pg=0&vtyp=0');
                                                        }
                                                    } else {
                                                        //Show Larger DIalog
                                                        dialog.modal('hide');
                                                        BootstrapDialog.show({
                                                            size: BootstrapDialog.SIZE_WIDE,
                                                            type: BootstrapDialog.TYPE_DEFAULT,
                                                            title: 'Action Result!',
                                                            message: result1,
                                                            animate: true,
                                                            closable: true,
                                                            closeByBackdrop: false,
                                                            closeByKeyboard: false,
                                                            onshow: function (dialog) { },
                                                            buttons: [{
                                                                label: 'Close',
                                                                icon: 'glyphicon glyphicon-ban-circle',
                                                                action: function (dialogItself) {
                                                                    dialogItself.close();
                                                                }
                                                            }]
                                                        });
                                                    }
                                                },500);
                                            },
                                            error: function (jqXHR1,textStatus1,errorThrown1) {
                                                console.warn(jqXHR.responseText);
                                            }
                                        });
                                    }
                                },
                                error: function (jqXHR,textStatus,errorThrown) {
                                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                                    console.warn(jqXHR.responseText);
                                }
                            });
                        });
                    });
                }
            }
        });
    }
}

function directApprove(rowIDAttrb,actionsToPrfrm) {
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var msgTyp = $('#' + prfxNm + '' + rndmNum + '_MsgType').val();
    var actType = "Approve;Authorize;Acknowledge";
    if (msgTyp === 'Informational') {
        actType = "Acknowledge";
    } else if (actionsToPrfrm.indexOf("Approve") !== -1) {
        actType = "Approve";
    } else if (actionsToPrfrm.indexOf("Authorize") !== -1) {
        actType = "Authorize";
    } else if (actionsToPrfrm.indexOf("Acknowledge") !== -1) {
        actType = "Acknowledge";
    } else {
        actType = "Approve";
    }

    if (RoutingID <= 0) {
        var dialog = bootbox.alert({
            title: 'No Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Please select a Notification First!</p>',
            callback: function () { }
        });
    } else {
        onAct(RoutingID,actType,1,'','','');
    }
}

function directReject(rowIDAttrb) {
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var msgTyp = $('#' + prfxNm + '' + rndmNum + '_MsgType').val();
    var sbjct = $('#' + prfxNm + '' + rndmNum + '_MsgSubject').val();
    var dateSnt = $('#' + prfxNm + '' + rndmNum + '_DateSent').val();
    var rowCnt = 0;
    if (msgTyp === 'Informational') {
        var dialog = bootbox.alert({
            title: 'Informational Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Cannot Reject an Informational Notification!</p>',
            callback: function () { }
        });
        return;
    } else if (RoutingID > 0) {
        rowCnt = 1;
    }
    if (rowCnt <= 0) {
        var dialog = bootbox.alert({
            title: 'No Document Based Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-spin fa-circle"></i> Please select a Document Based Notification First!</p>',
            callback: function () { }
        });
    } else {
        onReject(RoutingID,sbjct,dateSnt);
    }
}

function directInfoRqst(rowIDAttrb) {
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var msgTyp = $('#' + prfxNm + '' + rndmNum + '_MsgType').val();
    var sbjct = $('#' + prfxNm + '' + rndmNum + '_MsgSubject').val();
    var dateSnt = $('#' + prfxNm + '' + rndmNum + '_DateSent').val();
    var toPrsLocID = $('#' + prfxNm + '' + rndmNum + '_FromPersonLocID').val();
    var toPrsNm = $('#' + prfxNm + '' + rndmNum + '_FromPerson').val();
    var rowCnt = 0;
    if (msgTyp === 'Informational') {
        var dialog = bootbox.alert({
            title: 'Informational Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Cannot Request for Information on an Informational Notification!</p>',
            callback: function () { }
        });
        return;
    } else if (RoutingID > 0) {
        rowCnt = 1;
    }

    if (rowCnt <= 0) {
        var dialog = bootbox.alert({
            title: 'No Document Based Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Please select a Document Based Notification First!</p>',
            callback: function () { }
        });
    } else {
        onInfoRequest(RoutingID,1,sbjct,dateSnt,toPrsLocID,toPrsNm);
    }
}

function directAttachment(rowIDAttrb) {
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();
    var sbjct = $('#' + prfxNm + '' + rndmNum + '_MsgSubject').val();
    if (RoutingID <= 0) {
        var dialog = bootbox.alert({
            title: 'No Notification Selected',
            size: 'small',
            message: '<p style="text-align:center;"><i class="fa fa-exclamation-triangle" style="color:red;"></i> Please select a Notification First!</p>',
            callback: function () { }
        });
    } else {
        onAttachment(RoutingID,sbjct);
    }
}

function be4OnAct(RoutingID,actionNm,isResDiag) {
    var actionReason = typeof $("#wkfActionMsg").val() === 'undefined' ? "" : $("#wkfActionMsg").val();
    var toPrsnLocID = typeof $("#wkfToPrsnLocID").val() === 'undefined' ? "" : $("#wkfToPrsnLocID").val();
    var wkfSlctdRoutingIDs = typeof $("#wkfSlctdRoutingIDs").val() === 'undefined' ? "" : $("#wkfSlctdRoutingIDs").val();

    var errMsg = "";
    if (toPrsnLocID.trim() === '' && (actionNm === "Request for Information" || actionNm === "Re-Assign" || actionNm === "Respond")) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Message Recipient cannot be empty!</span></p>';
    }
    if (wkfSlctdRoutingIDs.trim() === '' && actionNm === "Re-Assign") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Selected Notifications cannot be empty!</span></p>';
    }
    if (actionReason.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Action Message/Reason cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    onAct(RoutingID,actionNm,isResDiag,actionReason,toPrsnLocID,'',wkfSlctdRoutingIDs);
}

function autoQueueMsgs() {
    var rho_msg_source = typeof $("#rho_msg_source").val() === 'undefined' ? '' : $("#rho_msg_source").val();
    var msgType = typeof $("#msgType").val() === 'undefined' ? '' : $("#msgType").val();
    var sndMsgOneByOne = typeof $("input[name='sndMsgOneByOne']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var groupID = typeof $("#groupID").val() === 'undefined' ? '-1' : $("#groupID").val();
    var grpName = typeof $("#groupName").val() === 'undefined' ? '' : $("#groupName").val();
    var workPlaceID = typeof $("#workPlaceID").val() === 'undefined' ? -1 : $("#workPlaceID").val();
    var workPlaceSiteID = typeof $("#workPlaceSiteID").val() === 'undefined' ? -1 : $("#workPlaceSiteID").val();
    if (rho_msg_source == "SELF-SERVICE-MSG" && Number(groupID.replace(/[^-?0-9\.]/g,"")) > 0) {
        $("#mailTo").val(groupID + ";");
    }

    var mailTo = typeof $("#mailTo").val() === 'undefined' ? '' : $("#mailTo").val();
    var mailCc = typeof $("#mailCc").val() === 'undefined' ? '' : $("#mailCc").val();
    var mailBcc = typeof $("#mailBcc").val() === 'undefined' ? '' : $("#mailBcc").val();
    var mailAttchmnts = typeof $("#mailAttchmnts").val() === 'undefined' ? '' : $("#mailAttchmnts").val();
    var mailSubject = typeof $("#mailSubject").val() === 'undefined' ? '' : $("#mailSubject").val();
    var bulkMessageBody = typeof $("#bulkMessageBody").val() === 'undefined' ? '' : ($('#bulkMessageBody').summernote('code'));
    if (msgType === "SMS") {
        bulkMessageBody = bulkMessageBody.replace(/<\/p>/gi," ")
            .replace(/<br\/?>/gi," ")
            .replace(/<\/?[^>]+(>|$)/g,"");
        /*
          bulkMessageBody = bulkMessageBody.replace(/<\/p>/gi, "\n")
         .replace(/<br\/?>/gi, "\n")
         .replace(/<\/?[^>]+(>|$)/g, "");
         */
    }
    if (mailSubject.trim() === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Subject cannot be empty!</span></p>'
        });
        return false;
    }
    if (bulkMessageBody.trim() === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Message Body cannot be empty!</span></p>'
        });
        return false;
    }
    if (rhotrim(mailTo,'; ') === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Mail To cannot be empty!</span></p>'
        });
        return false;
    }
    var dialog1 = bootbox.confirm({
        title: 'Queue Messages?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">SEND THIS MESSAGE</span> to the Indicated Receipients?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true) {
                var dialog = bootbox.alert({
                    title: 'Queue Messages',
                    size: 'small',
                    message: '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Queuing Messages...Please Wait...</div>',
                    callback: function () {
                        clearInterval(prgstimerid1);
                    }
                });
                dialog.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 45,
                                typ: 2,
                                q: 'UPDATE',
                                actyp: 3,
                                msgType: msgType,
                                sndMsgOneByOne: sndMsgOneByOne,
                                grpType: grpType,
                                grpName: grpName,
                                groupID: groupID,
                                workPlaceID: workPlaceID,
                                workPlaceSiteID: workPlaceSiteID,
                                mailTo: mailTo,
                                mailCc: mailCc,
                                mailBcc: mailBcc,
                                mailAttchmnts: mailAttchmnts,
                                mailSubject: mailSubject,
                                bulkMessageBody: bulkMessageBody
                            }
                        });
                        prgstimerid1 = window.setInterval(rfrshQueueMsgsPrgrs,1000);
                    });
                });
            }
        }
    });
}

function rfrshQueueMsgsPrgrs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 45,
            typ: 2,
            q: 'UPDATE',
            actyp: 4
        },
        success: function (data) {
            var elem = document.getElementById('myBar');
            elem.style.width = data.percent + '%';
            $("#myInformation").html(data.message);
            if (data.percent == 100) {
                window.clearInterval(prgstimerid1);
            }
        }
    });
}

/**Self-Service Msg */
function sendGeneralMessage2(msgType,mailToElmtID,mailCcElmtID,mailSubjectElmtID,bulkMessageBodyElmtID,mailAttchmntsElmtID,elementID,actionAfter,titleMsg,titleElementID,modalBodyID) {
    if (typeof mailAttchmntsElmtID === 'undefined' || mailAttchmntsElmtID === null) {
        mailAttchmntsElmtID = 'XXX_RHO_UNDEFINED';
    }
    var mailTo = typeof $("#" + mailToElmtID).val() === 'undefined' ? '' : $("#" + mailToElmtID).val();
    var mailCc = typeof $("#" + mailCcElmtID).val() === 'undefined' ? '' : $("#" + mailCcElmtID).val();
    var mailSubject = typeof $("#" + mailSubjectElmtID).val() === 'undefined' ? '' : $("#" + mailSubjectElmtID).val();
    var bulkMessageBody = typeof $("#" + bulkMessageBodyElmtID).val() === 'undefined' ? '' : $("#" + bulkMessageBodyElmtID).val();
    var mailAttchmnts = typeof $("#" + mailAttchmntsElmtID).val() === 'undefined' ? '' : $("#" + mailAttchmntsElmtID).val();
    var linkArgs = 'grp=45&typ=2&vtyp=0&q=SELF-SERVICE-MSG&msgType=' + msgType + '&mailTo=' + mailTo + '&mailCc=' + mailCc + '&mailSubject=' + mailSubject + '&bulkMessageBody=' + bulkMessageBody + "&mailAttchmnts=" + mailAttchmnts;
    if (typeof msgType === 'undefined' || msgType === null) {
        msgType = 'Email';
    }
    if (typeof elementID === 'undefined' || elementID === null) {
        elementID = 'sndBlkMsgForm';
    }
    if (typeof actionAfter === 'undefined' || actionAfter === null) {
        actionAfter = 'ShowDialog';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = 'Send Bulk Email/SMS';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = 'sndBlkMsgFormTitle';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = 'sndBlkMsgFormBody';
    }
    doAjaxWthCallBck(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID,function () {
        var fileLink = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-file"/> Upload',
                tooltip: 'Upload File Inline Within Message Body',
                click: function () {
                    $(function () {
                        $("#allOtherFileInput1").change(function () {
                            var fileName = $(this).val();
                            var input = document.getElementById('allOtherFileInput1');
                            sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                var inptText = $("#allOtherInputData2").val();
                                var inptNwWndw = $("#allOtherInputData2").val();
                                if (inptText === "") {
                                    inptText = "Read More...";
                                }
                                if (inptNwWndw === "") {
                                    inptNwWndw = true;
                                }
                                $('#bulkMessageBody').summernote('createLink',{
                                    text: inptText,
                                    url: inptUrl,
                                    newWindow: inptNwWndw
                                });
                            });
                        });
                    });
                    performFileClick('allOtherFileInput1');
                }
            });
            return button.render();
        };
        $('#bulkMessageBody').summernote({
            minHeight: 375,
            focus: true,
            disableDragAndDrop: false,
            dialogsInBody: true,
            toolbar: [
                ['style',['style']],
                ['style',['bold','italic','underline','clear']],
                ['font',['strikethrough','superscript','subscript']],
                ['fontsize',['fontsize']],
                ['fontname',['fontname']],
                ['color',['color']],
                ['para',['ul','ol','paragraph','height']],
                ['height',['height']],
                ['table',['table']],
                ['insert',['link','picture','video','hr']],
                ['view',['fullscreen','codeview']],
                ['help',['help']],
                ['misc',['print']],
                ['mybutton',['upload']]
            ],
            buttons: {
                upload: fileLink
            },
            callbacks: {
                onImageUpload: function (file,editor,welEditable) {
                    sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                        var inptUrl = $("#allOtherInputData1").val();
                        $('#bulkMessageBody').summernote("insertImage",inptUrl,'filename');
                    });
                }
            }
        });
        $('.note-editor').css("min-width","100%");
        $('.note-editable').trigger('focus');
        $('#bulkMessageBody').summernote('code',urldecode(bulkMessageBody));
        $('#mailTo').val(urldecode(mailTo));
        $('#mailCc').val(urldecode(mailCc));
        $('#mailSubject').val(urldecode(mailSubject));
        $('#mailAttchmnts').val(mailAttchmnts);
        $('#' + elementID).off('hidden.bs.modal');
        $('#' + elementID).one("hidden.bs.modal",function (e) {
            $(e.currentTarget).unbind();
        });
    });
}

function sendGeneralMessage3(rowIDAttrb,msgType,mailToElmtID,mailCcElmtID,mailSubjectElmtID,bulkMessageBodyElmtID,mailAttchmntsElmtID,elementID,actionAfter,titleMsg,titleElementID,modalBodyID) {
    if (typeof mailAttchmntsElmtID === 'undefined' || mailAttchmntsElmtID === null) {
        mailAttchmntsElmtID = 'XXX_RHO_UNDEFINED';
    }
    if (typeof mailSubjectElmtID === 'undefined' || mailSubjectElmtID === null) {
        mailSubjectElmtID = 'prevMailSubject';
    }
    if (typeof bulkMessageBodyElmtID === 'undefined' || bulkMessageBodyElmtID === null) {
        bulkMessageBodyElmtID = 'prevBulkMessageBody';
    }
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();

    var rowCnt = 0;
    var mailTo = typeof $("#" + mailToElmtID).val() === 'undefined' ? '' : $("#" + mailToElmtID).val();
    var mailCc = typeof $("#" + mailCcElmtID).val() === 'undefined' ? '' : $("#" + mailCcElmtID).val();
    var mailSubject = typeof $("#" + mailSubjectElmtID).val() === 'undefined' ? '' : $("#" + mailSubjectElmtID).val();
    var bulkMessageBody = typeof $("#" + bulkMessageBodyElmtID).val() === 'undefined' ? '' : $("#" + bulkMessageBodyElmtID).val();
    var mailAttchmnts = typeof $("#" + mailAttchmntsElmtID).val() === 'undefined' ? '' : $("#" + mailAttchmntsElmtID).val();
    var linkArgs = 'grp=45&typ=2&vtyp=0&q=SELF-SERVICE-MSG&msgType=' + msgType + '&mailTo=' + mailTo + '&mailCc=' + mailCc + '&mailSubject=' + mailSubject + '&bulkMessageBody=' + bulkMessageBody + "&mailAttchmnts=" + mailAttchmnts + "&RoutingID=" + RoutingID;
    if (typeof msgType === 'undefined' || msgType === null) {
        msgType = 'Email';
    }
    if (typeof elementID === 'undefined' || elementID === null) {
        elementID = 'sndBlkMsgForm';
    }
    if (typeof actionAfter === 'undefined' || actionAfter === null) {
        actionAfter = 'ShowDialog';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = 'Send Bulk Email/SMS';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = 'sndBlkMsgFormTitle';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = 'sndBlkMsgFormBody';
    }
    doAjaxWthCallBck(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID,function () {
        var fileLink = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-file"/> Upload',
                tooltip: 'Upload File Inline Within Message Body',
                click: function () {
                    $(function () {
                        $("#allOtherFileInput1").change(function () {
                            var fileName = $(this).val();
                            var input = document.getElementById('allOtherFileInput1');
                            sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                var inptText = $("#allOtherInputData2").val();
                                var inptNwWndw = $("#allOtherInputData2").val();
                                if (inptText === "") {
                                    inptText = "Read More...";
                                }
                                if (inptNwWndw === "") {
                                    inptNwWndw = true;
                                }
                                $('#bulkMessageBody').summernote('createLink',{
                                    text: inptText,
                                    url: inptUrl,
                                    newWindow: inptNwWndw
                                });
                            });
                        });
                    });
                    performFileClick('allOtherFileInput1');
                }
            });
            return button.render();
        };
        $('#bulkMessageBody').summernote({
            minHeight: 375,
            focus: true,
            disableDragAndDrop: false,
            dialogsInBody: true,
            toolbar: [
                ['style',['style']],
                ['style',['bold','italic','underline','clear']],
                ['font',['strikethrough','superscript','subscript']],
                ['fontsize',['fontsize']],
                ['fontname',['fontname']],
                ['color',['color']],
                ['para',['ul','ol','paragraph','height']],
                ['height',['height']],
                ['table',['table']],
                ['insert',['link','picture','video','hr']],
                ['view',['fullscreen','codeview']],
                ['help',['help']],
                ['misc',['print']],
                ['mybutton',['upload']]
            ],
            buttons: {
                upload: fileLink
            },
            callbacks: {
                onImageUpload: function (file,editor,welEditable) {
                    sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                        var inptUrl = $("#allOtherInputData1").val();
                        $('#bulkMessageBody').summernote("insertImage",inptUrl,'filename');
                    });
                }
            }
        });
        var mailSubject = typeof $("#" + mailSubjectElmtID).val() === 'undefined' ? '' : $("#" + mailSubjectElmtID).val();
        var bulkMessageBody = typeof $("#" + bulkMessageBodyElmtID).val() === 'undefined' ? '' : $("#" + bulkMessageBodyElmtID).val();
        $('.note-editable').trigger('focus');
        $('#bulkMessageBody').summernote('code',urldecode(bulkMessageBody));
        $('#mailTo').val(urldecode(mailTo));
        $('#mailCc').val(urldecode(mailCc));
        $('#mailSubject').val(urldecode(mailSubject));
        $('#mailAttchmnts').val(mailAttchmnts);
        $('#' + elementID).off('hidden.bs.modal');
        $('#' + elementID).one("hidden.bs.modal",function (e) {
            $(e.currentTarget).unbind();
        });
    });
}
function clearMsgForm() {
    var dialog = bootbox.confirm({
        title: 'Clear Form?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CLEAR</span> this Form?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result === true) {
                $("#mailTo").val('');
                $("#mailCc").val('');
                $("#mailBcc").val('');
                $("#mailAttchmnts").val('');
                $("#mailSubject").val('');
                $('#bulkMessageBody').summernote('code','<p></p>');
            }
        }
    });
}

function attchFileToMsg() {
    var crntAttchMnts = $("#mailAttchmnts").val();
    $("#allOtherFileInput3").change(function () {
        var fileName = $(this).val();
        var input = document.getElementById('allOtherFileInput3');
        sendMsgsFile(input.files[0],function () {
            var inptUrl = $("#allOtherInputData3").val();
            crntAttchMnts = crntAttchMnts + ";" + inptUrl;
            $("#mailAttchmnts").val(crntAttchMnts);
        });
    });
    performFileClick('allOtherFileInput3');
}

function sendMsgsFile(file,callBackFunc) {
    var data1 = new FormData();
    data1.append("file",file);
    $.ajax({
        url: "../dwnlds/uploader1.php",
        data: data1,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            $("#allOtherInputData3").val(data);
            callBackFunc();
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
        }
    });
}

function afterShowActions(wkfAppNm) {
    if (wkfAppNm === "Banking Transactions") {
        afterShowBNK();
    } else if (wkfAppNm === "Vault Transactions") {
        afterShowVMS();
    } else if (wkfAppNm === "Personal Records Change") {

    } else if (wkfAppNm === "Bulk/Batch Transactions") {
        afterShowBulk();
    } else if (wkfAppNm === "Transfer Transactions") {
        afterShowTrsfr();
    }
}

function funcHtmlToExcel(tableid) {
    // Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined' || navigator.userAgent.indexOf(' Firefox/') >= 0;
    // Safari 3.0+ "[object HTMLElementConstructor]" 
    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
        return p.toString() === "[object SafariRemoteNotification]";
    })(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification)) || (navigator.userAgent.indexOf(' Safari/') >= 0 && navigator.vendor.indexOf('Apple') >= 0);
    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/ false || !!document.documentMode || navigator.userAgent.indexOf(' Trident/') >= 0;
    // Edge 20+
    var isEdge = (!isIE && !!window.StyleMedia) || navigator.userAgent.indexOf(' Edge/') >= 0;
    // Chrome 1+
    var isChrome = (!!window.chrome) || (navigator.userAgent.indexOf(' Chrome/') >= 0 && navigator.vendor.indexOf('Google') >= 0);
    // && !!window.chrome.webstore
    //alert(navigator.userAgent.indexOf(' Chrome/') + ':HH:' + navigator.vendor.indexOf('Google'));
    // Blink engine detection
    var isBlink = (isChrome || isOpera) && !!window.CSS;
    //getting values of current time for generating the file name
    var dt = new Date();
    var day = dt.getDate();
    var month = dt.getMonth() + 1;
    var year = dt.getFullYear();
    var hour = dt.getHours();
    var mins = dt.getMinutes();
    var secns = dt.getSeconds();
    var postfix = day + "." + month + "." + year + "_" + hour + "." + mins + "." + secns + "_" + getRandomInt2(100,999);
    var myURL = window.URL || window.webkitURL;
    //alert(navigator.userAgent + ':HH:' + navigator.vendor);
    if (isChrome || isFirefox || isEdge || isBlink) {
        //alert('Here1');
        //creating a temporary HTML link element (they support setting file names)

        var a = document.getElementById('allOtherATag1');
        //getting data from our div that contains the HTML table
        var data_type = 'data:application/vnd.ms-excel';

        var table_div = document.getElementById(tableid);
        var cptnTxt = $("caption").text();
        var tab_text = "<table border='2px'><caption style=\"font-weight:bold;font-size: 18px;\">" + cptnTxt + "</caption><tr bgcolor='#87AFC6'>";
        var textRange;
        var j = 0;
        tab = document.getElementById(tableid); // id of table


        for (j = 0; j < tab.rows.length; j++) {
            if (j === 0) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            } else {
                tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
            }
            //tab_text=tab_text+"</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g,""); //remove if u want links in your table
        tab_text = tab_text.replace(/<a[^>]*>|<\/a>/g,"").replace(/Â»/g,""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi,""); // reomves input params

        var binaryData = [];
        /*table_div.outerHTML*/
        binaryData.push(tab_text);
        var url = myURL.createObjectURL(new Blob(binaryData,{
            type: "data:application/vnd.ms-excel"
        }));
        var table_html = url;
        a.href = table_html;
        //setting the file name
        a.download = 'exported_table_' + postfix + '.xls';
        //triggering the function
        a.click();
    } else {
        //alert('Here2');
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
    tab = document.getElementById(tableid); // id of table

    for (j = 0; j < tab.rows.length; j++) {
        if (j === 0) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        } else {
            tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
        }
    }

    tab_text = tab_text + "</table>";
    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g,""); //remove if u want links in your table
    tab_text = tab_text.replace(/<a[^>]*>|<\/a>/g,"").replace(/Â»/g,""); //remove if u want links in your table
    tab_text = tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi,""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    /*if (event.data.browser !== 'IE') {
     $.util.open('data:application/vnd.ms-excel,' + event.data.content);
     } else {
     // The iframe already has to be included in the HTML, or you'll get a 'no access error'.
     frame.document.open("txt/html", "replace");
     frame.document.write(event.data.content);
     frame.document.close();
     frame.focus();
     command = frame.document.execCommand("SaveAs", true, "data_table.xls");
     }*/
    /**/
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./) || navigator.userAgent.indexOf(' Trident/') >= 0 || navigator.userAgent.indexOf(' Edge/') >= 0) // If Internet Explorer
    {
        frame.document.open("txt/html","replace");
        frame.document.write(tab_text);
        frame.document.close();
        frame.focus();
        sa = frame.document.execCommand("SaveAs",true,fileNm + ".xls");
        /*// IE10+ : (has Blob, but not a[download] or URL)
         if (navigator.msSaveBlob) {
         return navigator.msSaveBlob(blob, fileName);
         }*/
    } else {
        //other browser not tested on IE 11
        sa = generateHtmlExcel(tableid);
    }
    return (sa);
}

function generateHtmlExcel(tableid) {
    var cptnTxt = $("caption").text();
    var tab_text = "<table border='2px'><caption style=\"font-weight:bold;font-size: 18px;\">" + cptnTxt + "</caption><tr bgcolor='#87AFC6'>";
    var textRange;
    var j = 0;
    tab = document.getElementById(tableid); // id of table


    for (j = 0; j < tab.rows.length; j++) {
        if (j === 0) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        } else {
            tab_text = tab_text + "<tr>" + tab.rows[j].innerHTML + "</tr>";
        }
    }

    tab_text = tab_text + "</table>";
    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g,""); //remove if u want links in your table
    tab_text = tab_text.replace(/<a[^>]*>|<\/a>/g,"").replace(/Â»/g,""); //remove if u want links in your table
    tab_text = tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi,""); // reomves input params
    /*table.outerHTML*/
    var myURL = window.URL || window.webkitURL;
    var table = document.getElementById(tableid);
    var html = tab_text;
    var binaryData = [];
    binaryData.push(html);
    var url = myURL.createObjectURL(new Blob(binaryData,{
        type: "data:application/vnd.ms-excel"
    }));
    return window.open(url);
}

function getRandomInt1(max) {
    return Math.floor(Math.random() * Math.floor(max));
}

function getRandomInt2(min,max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min; //The maximum is exclusive and the minimum is inclusive
}

function rhovoid() {
    return false;
}

function escapeRegExp(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g,"\\$&"); // $& means the whole matched string
}

function tickUntickChckBx(chckBxElmnt) {
    $("input[name='" + chckBxElmnt + "']").trigger("click");
}
var popDialogItself;
var popOKBtnSsn;

function popUpDisplay(srcInputTextAreaID) {
    /*var srcTxt = typeof $("#" + srcInputTextAreaID).text() === 'undefined' ? '' : $("#" + srcInputTextAreaID).text();
     srcTxt = srcTxt.replace(new RegExp('<br/>', 'g'), '&#13;&#10;');
     srcTxt = srcTxt.replace(new RegExp('<br />', 'g'), '&#13;&#10;');*/
    var dsplyForm = '<div><textarea class="form-control" aria-label="..." id="popUpDsplyTxtArea1" name="popUpDsplyTxtArea1" style="width:100%;resize:both;" cols=20 rows="20"></textarea></div>';

    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_WIDE,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: 'Popup Display!',
        message: dsplyForm,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {
            popDialogItself = dialogItself;
            popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');
        },
        onshown: function (dialogItself) {
            $('#popUpDsplyTxtArea1').focus();
            popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');
            $('#popUpDsplyTxtArea1').val($("#" + srcInputTextAreaID).val());
        },
        buttons: [{
            label: 'CLOSE',
            icon: 'glyphicon glyphicon-menu-left',
            cssClass: 'btn-default',
            action: function (dialogItself) {
                var $button = this;
                dialogItself.setClosable(true);
                dialogItself.close();
            }
        },{
            id: 'popOKBtnSsn',
            label: 'OKAY',
            icon: 'glyphicon glyphicon-menu-right',
            cssClass: 'btn-primary',
            action: function (dialogItself) {
                popDialogItself = dialogItself;
                $("#" + srcInputTextAreaID).val($('#popUpDsplyTxtArea1').val());
                var $button = this;
                dialogItself.setClosable(true);
                dialogItself.close();
            }
        }]
    });
}


function popUpDisplayHtml(srcInputTextAreaID) {
    /*var srcTxt = typeof $("#" + srcInputTextAreaID).text() === 'undefined' ? '' : $("#" + srcInputTextAreaID).text();
     srcTxt = srcTxt.replace(new RegExp('<br/>', 'g'), '&#13;&#10;');
     srcTxt = srcTxt.replace(new RegExp('<br />', 'g'), '&#13;&#10;');*/
    var dsplyForm = '<div class="row" style="padding:0px 15px 0px 15px !important;"><div id="popUpDsplyTxtAreaHtm2"></div></div>';
    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_WIDE,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: 'Popup Display!',
        message: dsplyForm,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {
            popDialogItself = dialogItself;
            popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');
        },
        onshown: function (dialogItself) {
            popOKBtnSsn = dialogItself.getButton('popOKBtnSsn');

            $('#popUpDsplyTxtAreaHtm2').summernote({
                minHeight: 275,
                focus: true,
                disableDragAndDrop: false,
                dialogsInBody: true,
                toolbar: [
                    ['style',['style']],
                    ['style',['bold','italic','underline','clear']],
                    ['font',['strikethrough','superscript','subscript']],
                    ['fontsize',['fontsize']],
                    ['fontname',['fontname']],
                    ['color',['color']],
                    ['para',['ul','ol','paragraph','height']],
                    ['height',['height']],
                    ['table',['table']],
                    ['insert',['link','picture','video','hr']],
                    ['view',['fullscreen','codeview']],
                    ['help',['help']],
                    ['misc',['print']],
                    ['mybutton',['upload']]
                ],
                callbacks: {
                    onImageUpload: function (file,editor,welEditable) {
                        sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#popUpDsplyTxtAreaHtm2').summernote("insertImage",inptUrl,'filename');
                        });
                    }
                }
            });
            $('.note-editable').trigger('focus');
            var markupStr1 = typeof $("#" + srcInputTextAreaID).val() === 'undefined' ? '' : $("#" + srcInputTextAreaID).val();
            $('#popUpDsplyTxtAreaHtm2').summernote('code',urldecode(markupStr1));
        },
        buttons: [{
            label: 'CLOSE',
            icon: 'glyphicon glyphicon-menu-left',
            cssClass: 'btn-default',
            action: function (dialogItself) {
                var $button = this;
                dialogItself.setClosable(true);
                dialogItself.close();
            }
        },{
            id: 'popOKBtnSsn',
            label: 'OKAY',
            icon: 'glyphicon glyphicon-menu-right',
            cssClass: 'btn-primary',
            action: function (dialogItself) {
                popDialogItself = dialogItself;
                var popUpDsplyTxtAreaHtm2 = typeof $("#popUpDsplyTxtAreaHtm2").val() === 'undefined' ? '' : ($('#popUpDsplyTxtAreaHtm2').summernote('code'));
                $("#" + srcInputTextAreaID).val(popUpDsplyTxtAreaHtm2);
                var $button = this;
                dialogItself.setClosable(true);
                dialogItself.close();
            }
        }]
    });
}

String.prototype.replaceAll = function (search,replacement) {
    var target = this;
    return target.replace(new RegExp(escapeRegExp(search),'g'),replacement);
};

// Numeric only control handler
jQuery.fn.ForceNumericOnly =
    function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
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


