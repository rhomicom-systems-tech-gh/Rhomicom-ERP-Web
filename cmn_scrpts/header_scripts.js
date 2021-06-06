var popDialogItself;
var popOKBtnSsn;

function popUpDisplay(srcInputTextAreaID) {
    /*var srcTxt = typeof $("#" + srcInputTextAreaID).text() === 'undefined' ? '' : $("#" + srcInputTextAreaID).text();
     srcTxt = srcTxt.replace(new RegExp('<br/>', 'g'), '&#13;&#10;');
     srcTxt = srcTxt.replace(new RegExp('<br />', 'g'), '&#13;&#10;');*/
    var dsplyForm = '<div><textarea class="form-control" aria-label="..." id="popUpDsplyTxtArea1" name="popUpDsplyTxtArea1" style="width:100%;resize:both;" cols=20 rows="30"></textarea></div>';

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
        }, {
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
                minHeight: 375,
                focus: true,
                disableDragAndDrop: false,
                dialogsInBody: true,
                toolbar: [
                    ['style', ['style']],
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph', 'height']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']],
                    ['misc', ['print']],
                    ['mybutton', ['upload']]
                ],
                callbacks: {
                    onImageUpload: function (file, editor, welEditable) {
                        sendNoticesFile(file[0], editor, welEditable, "IMAGES", function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#popUpDsplyTxtAreaHtm2').summernote("insertImage", inptUrl, 'filename');
                        });
                    }
                }
            });
            $('.note-editable').trigger('focus');
            var markupStr1 = typeof $("#" + srcInputTextAreaID).val() === 'undefined' ? '' : $("#" + srcInputTextAreaID).val();
            $('#popUpDsplyTxtAreaHtm2').summernote('code', urldecode(markupStr1));
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
        }, {
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


function loadScript(url, callback) {
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

function loadCss(url, callback) {
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

function showRoles() {
    openATab('#profile', 'grp=3&typ=1&pg=1&vtyp=4');
}

function getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn,
    addtnlWhere, callBackFunc, psblValIDElmntID) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
        } else {
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
                $('#myLovModal').draggable();
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).one('show.bs.modal', function (e) {
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
                $('#' + elementID).on('shown.bs.modal', function () {
                    /*$('#' + elementID).draggable();*/
                    $('#lovSrchFor').focus();
                });
                $body.removeClass("mdlloading");
                $body.css("overflow", "auto");
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
                        $('#lovTblRO').wrap('<div class="dataTables_scroll"/>');
                        $('#lovTblRO tbody').on('dblclick', 'tr', function () {

                            table.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');

                            $checkedBoxes = $(this).find('input[type=checkbox]');
                            $checkedBoxes.each(function (i, checkbox) {
                                checkbox.checked = true;
                            });
                            $radioBoxes = $(this).find('input[type=radio]');
                            $radioBoxes.each(function (i, radio) {
                                radio.checked = true;
                            });
                            applySlctdLov(elementID, 'lovForm', valueElmntID, descElemntID, callBackFunc, psblValIDElmntID);
                        });

                        $('#lovTblRO tbody').on('click', 'tr', function () {
                            if ($(this).hasClass('selected')) {
                                $(this).removeClass('selected');
                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i, checkbox) {
                                    checkbox.checked = false;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i, radio) {
                                    radio.checked = false;
                                });
                            } else {
                                table.$('tr.selected').removeClass('selected');
                                $(this).addClass('selected');

                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i, checkbox) {
                                    checkbox.checked = true;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i, radio) {
                                    radio.checked = true;
                                });
                            }
                        });
                        $('#lovTblRO tbody')
                            .on('mouseenter', 'tr', function () {
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
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
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

function enterKeyFuncLov(e, elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc, psblValIDElmntID) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
            criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
            selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc, psblValIDElmntID);
    }
}

function applySlctdLov(modalElementID, formElmntID, valueElmntID, descElemntID, callBackFunc, psblValIDElmntID) {
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
        fnl_res = cbResults.slice(0, -1);
    }
    if (radioResults.length > 1) {
        fnl_res = radioResults.slice(0, -1);
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
        descValue = descValue.slice(0, -1);
        valValue = valValue.slice(0, -1);
        psblValIDValue = psblValIDValue.slice(0, -1);
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

function applySlctdLov_mcf(modalElementID, formElmntID, valueElmntID, descElemntID, callBackFunc) {
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
        fnl_res = cbResults.slice(0, -1);
    }
    if (radioResults.length > 1) {
        fnl_res = radioResults.slice(0, -1);
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
        descValue = descValue.slice(0, -1);
        valValue = valValue.slice(0, -1);
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

function getLovsPage_mcf(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
        } else {
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
                $('#myLovModal').draggable();

                /*$('#' + elementID).on('show.bs.modal', function (e) {
                 $(this).find('.modal-body').css({
                 'max-height': '100%'
                 });
                 });*/
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal('show', {
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
                        $('#lovTblRO').wrap('<div class="dataTables_scroll"/>');
                        $('#lovTblRO tbody').on('dblclick', 'tr', function () {

                            table.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');

                            $checkedBoxes = $(this).find('input[type=checkbox]');
                            $checkedBoxes.each(function (i, checkbox) {
                                checkbox.checked = true;
                            });
                            $radioBoxes = $(this).find('input[type=radio]');
                            $radioBoxes.each(function (i, radio) {
                                radio.checked = true;
                            });
                            applySlctdLov_mcf(elementID, 'lovForm', valueElmntID, descElemntID, callBackFunc);
                        });

                        $('#lovTblRO tbody').on('click', 'tr', function () {
                            if ($(this).hasClass('selected')) {
                                $(this).removeClass('selected');
                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i, checkbox) {
                                    checkbox.checked = false;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i, radio) {
                                    radio.checked = false;
                                });
                            } else {
                                table.$('tr.selected').removeClass('selected');
                                $(this).addClass('selected');

                                $checkedBoxes = $(this).find('input[type=checkbox]');
                                $checkedBoxes.each(function (i, checkbox) {
                                    checkbox.checked = true;
                                });
                                $radioBoxes = $(this).find('input[type=radio]');
                                $radioBoxes.each(function (i, radio) {
                                    radio.checked = true;
                                });
                            }
                        });
                        $('#lovTblRO tbody')
                            .on('mouseenter', 'tr', function () {
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
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
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

function doAjax(linkArgs, elementID, actionAfter, titleMsg, titleElementID, modalBodyID) {
    $('#allOtherInputData99').val(0);
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
                    window.open(xmlhttp.responseText, '_blank');
                } else if (actionAfter == 'ShowDialog') {
                    $body.removeClass("mdlloading");
                    $('#' + titleElementID).html(titleMsg);
                    $('#' + modalBodyID).html(xmlhttp.responseText);
                    $('#' + elementID).off('show.bs.modal');
                    $('#' + elementID).off('hidden.bs.modal');
                    $('#' + elementID).one('hidden.bs.modal', function (e) {
                        $('#' + titleElementID).html('');
                        $('#' + modalBodyID).html('');
                        $(e.currentTarget).unbind();
                    });
                    $('#' + elementID).one('show.bs.modal', function (e) {
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
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    });
}


function doAjaxWthCallBck(linkArgs, elementID, actionAfter, titleMsg, titleElementID, modalBodyID, rqstdCallBack) {
    $('#allOtherInputData99').val(0);
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
                    window.open(xmlhttp.responseText, '_blank');
                    rqstdCallBack();
                } else if (actionAfter == 'ShowDialog') {
                    $body.removeClass("mdlloading");
                    $('#' + titleElementID).html(titleMsg);
                    $('#' + modalBodyID).html(xmlhttp.responseText);
                    $('#' + elementID).off('show.bs.modal');
                    $('#' + elementID).off('hidden.bs.modal');
                    $('#' + elementID).one('hidden.bs.modal', function (e) {
                        $('#' + titleElementID).html('');
                        $('#' + modalBodyID).html('');
                        $(e.currentTarget).unbind();
                    });
                    $('#' + elementID).one('show.bs.modal', function (e) {
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
                    $('#' + elementID).on('shown.bs.modal', function () {
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
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    });
}

function openATab(slctr, linkArgs) {
    $('#allOtherInputData99').val(0);
    $body = $("body");
    var $this = $(slctr + 'tab');
    var targ = slctr;
    $("body").css("padding", "0px 0px 0px 0px");
    if ($.trim($(targ).text()) !== "" &&
        (slctr.match("^#prfl") ||
            slctr.match("^#profile") ||
            slctr.indexOf("#home") !== -1)) {
        $this.tab('show');
        $body.removeClass("mdlloading");
    } else {
        getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
                    if (xmlhttp.responseText.indexOf("RedirectTo:") !== -1) {
                        window.location = xmlhttp.responseText.replace(/RedirectTo:/g, '');
                        return false;
                    }
                    if (linkArgs.indexOf("grp=40&typ=2") !== -1) {
                        loadScript("app/cmncde/myinbox.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareInbox(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=8&typ=1") !== -1) {
                        loadScript("app/prs/prsn.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareProfile(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=9&typ=1") !== -1) {
                        $this.tab('show');
                        prepareRpts(linkArgs, $body, targ, xmlhttp.responseText);
                    } else if (linkArgs.indexOf("grp=40&typ=3") !== -1) {
                        $this.tab('show');
                        prepareNotices(linkArgs, $body, targ, xmlhttp.responseText);
                    } else if (linkArgs.indexOf("grp=40&typ=4") !== -1) {
                        $this.tab('show');
                        prepareDashboard(linkArgs, $body, targ, xmlhttp.responseText);
                    } else if (linkArgs.indexOf("grp=3&typ=1") !== -1) {
                        if (linkArgs.indexOf("pg=1&vtyp=4") !== -1) {
                            $this.tab('show');
                            prepareUsrPrfl(linkArgs, $body, targ, xmlhttp.responseText);
                        } else {
                            loadScript("app/sec/sys_admin.js?v=" + jsFilesVrsn, function () {
                                $this.tab('show');
                                prepareSysAdmin(linkArgs, $body, targ, xmlhttp.responseText);
                            });
                        }
                    } else if (linkArgs.indexOf("grp=4&typ=1") !== -1) {
                        loadScript("app/gst/gst_admin.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareGstAdmin(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=5&typ=1") !== -1) {
                        loadScript("app/org/org_admin.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareOrgAdmin(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=6&typ=1") !== -1) {
                        loadScript("app/accb/accb.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareAccbAdmin(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=11&typ=1") !== -1) {
                        loadScript("app/wkf/wkf_admin.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareWkfAdmin(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=12&typ=1") !== -1 || linkArgs.indexOf("grp=18&typ=1") !== -1) {
                        loadScript("app/inv/inv.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareInvAdmin(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=15&typ=1") !== -1) {
                        loadScript("app/aca/aca.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareAcademcs(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=7&typ=1") !== -1) {
                        loadScript("app/pay/pay.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            preparePay(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                        loadScript("app/inv/inv.js?v=" + jsFilesVrsn, function () {});
                    } else if (linkArgs.indexOf("grp=16&typ=1") !== -1) {
                        loadScript("app/attn/attn.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareAttn(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                        loadScript("app/inv/inv.js?v=" + jsFilesVrsn, function () {});
                    } else if (linkArgs.indexOf("grp=17&typ=1") !== -1) {
                        loadScript("app/mcf/mcf2.js?v=" + jsFilesVrsn, function () {
                            loadScript("app/mcf/mcf.js?v=" + jsFilesVrsn, function () {
                                $this.tab('show');
                                prepareMcf(linkArgs, $body, targ, xmlhttp.responseText);
                            });
                        });
                    } else if (linkArgs.indexOf("grp=19&typ=10") !== -1) {
                        loadScript("app/evote/evote.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareEvote(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=25&typ=1") !== -1) {
                        loadScript("app/vms/vms.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareVMS(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=26&typ=1") !== -1) {
                        loadScript("app/agnt/agnt.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareAgnt(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=41&typ=1") !== -1) {
                        loadScript("app/hlpd/hlpd.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareHLPD(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    } else if (linkArgs.indexOf("grp=14&typ=1") !== -1) {
                        //alert(linkArgs+' slctr '+slctr + ' targ'+targ+ ' xmlhttp.responseText'+xmlhttp.responseText);
                        loadScript("app/hosp/hosp.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareHosp(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                        loadScript("app/inv/inv.js?v=" + jsFilesVrsn, function () {});
                    } else if (linkArgs.indexOf("grp=50&typ=1") !== -1) {
                        loadScript("app/ccs/ccs.js?v=" + jsFilesVrsn,function () {
                            $this.tab('show');
                            prepareCcs(linkArgs,$body,targ,xmlhttp.responseText);
                        });
                    } else {
                        $(targ).html(xmlhttp.responseText);
                        $this.tab('show');
                        $body.removeClass("mdlloading");
                    }
                }
            };
            xmlhttp.open("POST", "index.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send(linkArgs.trim());
        });
    }
}

function openATab1(slctr, linkArgs) {
    $('#allOtherInputData99').val(0);
    $body = $("body");
    var $this = $(slctr + 'tab');
    var targ = slctr;
    $("body").css("padding", "0px 0px 0px 0px");
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
                if (xmlhttp.responseText.indexOf("RedirectTo:") !== -1) {
                    window.location = xmlhttp.responseText.replace(/RedirectTo:/g, '');
                    return false;
                }
                if (linkArgs.indexOf("grp=40&typ=2") !== -1) {
                    loadScript("app/cmncde/myinbox.js?v=" + jsFilesVrsn, function () {
                        $this.tab('show');
                        prepareInbox(linkArgs, $body, targ, xmlhttp.responseText);
                    });
                } else if (linkArgs.indexOf("grp=8&typ=1") !== -1) {
                    loadScript("app/prs/prsn.js?v=" + jsFilesVrsn, function () {
                        $this.tab('show');
                        prepareProfile(linkArgs, $body, targ, xmlhttp.responseText);
                    });
                } else if (linkArgs.indexOf("grp=40&typ=3") !== -1) {
                    $this.tab('show');
                    prepareNotices(linkArgs, $body, targ, xmlhttp.responseText);
                } else if (linkArgs.indexOf("grp=3&typ=1") !== -1) {
                    if (linkArgs.indexOf("pg=1&vtyp=4") !== -1) {
                        $this.tab('show');
                        prepareUsrPrfl(linkArgs, $body, targ, xmlhttp.responseText);
                    } else {
                        loadScript("app/sec/sys_admin.js?v=" + jsFilesVrsn, function () {
                            $this.tab('show');
                            prepareSysAdmin(linkArgs, $body, targ, xmlhttp.responseText);
                        });
                    }
                } else if (linkArgs.indexOf("grp=17&typ=1") !== -1) {
                    loadScript("app/mcf/mcf2.js?v=" + jsFilesVrsn, function () {});
                    loadScript("app/mcf/mcf.js?v=" + jsFilesVrsn, function () {
                        $this.tab('show');
                        prepareMcf(linkArgs, $body, targ, xmlhttp.responseText);
                    });
                } else {
                    $(targ).html(xmlhttp.responseText);
                    $this.tab('show');
                    $body.removeClass("mdlloading");
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(linkArgs.trim());
    });

}

function prepareUsrPrfl(lnkArgs, htBody, targ, rspns) {
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

function getUsrPrfl(actionText, slctr, linkArgs) {
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
    openATab1(slctr, linkArgs);
}

function enterKeyFuncUsrPrfl(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getUsrPrfl(actionText, slctr, linkArgs);
    }
}

function prepareNotices(lnkArgs, htBody, targ, rspns) {
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
                $('#allnoticesTable').wrap('<div class="dataTables_scroll"/>');
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
                    tooltip: 'Upload File',
                    click: function () {
                        $(function () {
                            $("#allOtherFileInput1").change(function () {
                                var fileName = $(this).val();
                                var input = document.getElementById('allOtherFileInput1');
                                sendNoticesFile(input.files[0], "", "", "OTHERS", function () {
                                    var inptUrl = $("#allOtherInputData1").val();
                                    var inptText = $("#allOtherInputData2").val();
                                    var inptNwWndw = $("#allOtherInputData2").val();
                                    if (inptText === "") {
                                        inptText = "Read More...";
                                    }
                                    if (inptNwWndw === "") {
                                        inptNwWndw = true;
                                    }
                                    $('#fdbckMsgBody').summernote('createLink', {
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
                minHeight: 350,
                focus: true,
                disableDragAndDrop: false,
                dialogsInBody: true,
                toolbar: [
                    ['style', ['style']],
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph', 'height']],
                    ['height', ['height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen']],
                    ['help', ['help']],
                    ['misc', ['print']],
                    ['mybutton', ['upload']]
                ],
                buttons: {
                    upload: fileLink
                },
                callbacks: {
                    onImageUpload: function (file, editor, welEditable) {
                        sendNoticesFile(file[0], editor, welEditable, "IMAGES", function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#fdbckMsgBody').summernote("insertImage", inptUrl, 'filename');
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
                    "scrollX": false
                });
                $('#allForumsTable').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&vtyp=7") !== -1) {
            var fileLink = function (context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="fa fa-file"/> Upload',
                    tooltip: 'Upload File',
                    click: function () {
                        $(function () {
                            $("#allOtherFileInput1").change(function () {
                                var fileName = $(this).val();
                                var input = document.getElementById('allOtherFileInput1');
                                sendNoticesFile(input.files[0], "", "", "OTHERS", function () {
                                    var inptUrl = $("#allOtherInputData1").val();
                                    var inptText = $("#allOtherInputData2").val();
                                    var inptNwWndw = $("#allOtherInputData2").val();
                                    if (inptText === "") {
                                        inptText = "Read More...";
                                    }
                                    if (inptNwWndw === "") {
                                        inptNwWndw = true;
                                    }
                                    $('#articleNwCmmntsMsg').summernote('createLink', {
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
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph', 'height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['mybutton', ['upload']]
                ],
                buttons: {
                    upload: fileLink
                },
                callbacks: {
                    onImageUpload: function (file, editor, welEditable) {
                        sendNoticesFile(file[0], editor, welEditable, "IMAGES", function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#articleNwCmmntsMsg').summernote("insertImage", inptUrl, 'filename');
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
                    tooltip: 'Upload File',
                    click: function () {
                        $(function () {
                            $("#allOtherFileInput1").change(function () {
                                var fileName = $(this).val();
                                var input = document.getElementById('allOtherFileInput1');
                                sendNoticesFile(input.files[0], "", "", "OTHERS", function () {
                                    var inptUrl = $("#allOtherInputData1").val();
                                    var inptText = $("#allOtherInputData2").val();
                                    var inptNwWndw = $("#allOtherInputData2").val();
                                    if (inptText === "") {
                                        inptText = "Read More...";
                                    }
                                    if (inptNwWndw === "") {
                                        inptNwWndw = true;
                                    }
                                    $('#articleNwCmmntsMsg').summernote('createLink', {
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
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph', 'height']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['mybutton', ['upload']]
                ],
                buttons: {
                    upload: fileLink
                },
                callbacks: {
                    onImageUpload: function (file, editor, welEditable) {
                        sendNoticesFile(file[0], editor, welEditable, "IMAGES", function () {
                            var inptUrl = $("#allOtherInputData1").val();
                            $('#articleNwCmmntsMsg').summernote("insertImage", inptUrl, 'filename');
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

function prepareDashboard(lnkArgs, htBody, targ, rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&vtyp=0") !== -1) {}
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

function getAllNotices(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#allnoticesSrchFor").val() === 'undefined' ? '%' : $("#allnoticesSrchFor").val();
    /*var srchIn = typeof $("#allnoticesSrchIn").val() === 'undefined' ? 'Both' : $("#allnoticesSrchIn").val();*/
    var pageNo = typeof $("#allnoticesPageNo").val() === 'undefined' ? 1 : $("#allnoticesPageNo").val();
    var limitSze = typeof $("#allnoticesDsplySze").val() === 'undefined' ? 10 : $("#allnoticesDsplySze").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncNotices(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllNotices(actionText, slctr, linkArgs);
    }
}

function getAllComments(actionText, slctr, linkArgs, ttlCmnts, srcBtn) {
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
    openATab(slctr, linkArgs);
}

function getMoreComments(actionText, slctr, linkArgs) {
    var srchFor = '%';
    var srchIn = 'All';
    var pageNo = typeof $("#onenoticesPageNo1").val() === 'undefined' ? 1 : $("#onenoticesPageNo1").val();
    var sbmtdNoticeID = typeof $("#allcommentsArticleID").val() === 'undefined' ? 1 : $("#allcommentsArticleID").val();
    var prntCmmntID = typeof $("#onenoticesCmntID1").val() === 'undefined' ? 1 : $("#onenoticesCmntID1").val();
    var limitSze = 10;
    var sortBy = "";
    pageNo = parseInt(pageNo) + 1;
    linkArgs = linkArgs + "&prntCmmntID=" + prntCmmntID + "&sbmtdNoticeID=" + sbmtdNoticeID + "&searchfor1=" + srchFor + "&searchin1=All&pageNo1=" + pageNo + "&limitSze1=" + limitSze + "&sortBy1=" + sortBy;
    openATab(slctr, linkArgs);
}

function getMoreComments1(actionText, slctr, linkArgs) {
    var srchFor = '%';
    var srchIn = 'All';
    var pageNo = typeof $("#onenoticesPageNo1").val() === 'undefined' ? 1 : $("#onenoticesPageNo1").val();
    var sbmtdNoticeID = typeof $("#allcommentsArticleID").val() === 'undefined' ? 1 : $("#allcommentsArticleID").val();
    var limitSze = 10;
    var sortBy = "";
    pageNo = parseInt(pageNo) + 1;
    linkArgs = linkArgs + "&sbmtdNoticeID=" + sbmtdNoticeID + "&searchfor1=" + srchFor + "&searchin1=All&pageNo1=" + pageNo + "&limitSze1=" + limitSze + "&sortBy1=" + sortBy;
    openATab(slctr, linkArgs);
}

function newComments(crntPrnCmntID) {
    $("#crntPrnCmntID").val(crntPrnCmntID);
    $('#cmmntsNewMsgs').removeClass('hideNotice');
}

function getOneNotice(actionText, slctr, linkArgs, srcBtn, srcBtnNo) {
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
    openATab(slctr, linkArgs);
}

function getOneNoticeForm(elementID, modalBodyID, titleElementID, formElementID,
    formTitle, articleID, vtyp, pgNo) {
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
                        ['style', ['style']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph', 'height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'hr']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    callbacks: {
                        onImageUpload: function (file, editor, welEditable) {
                            sendNoticesFile(file[0], editor, welEditable, "IMAGES", function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                $('#articleIntroMsg').summernote("insertImage", inptUrl, 'filename');
                            });
                        }
                    }
                });
                var fileLink = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="fa fa-file"/> Upload',
                        tooltip: 'Upload File',
                        click: function () {
                            $(function () {
                                $("#allOtherFileInput1").change(function () {
                                    var fileName = $(this).val();
                                    var input = document.getElementById('allOtherFileInput1');
                                    sendNoticesFile(input.files[0], "", "", "OTHERS", function () {
                                        var inptUrl = $("#allOtherInputData1").val();
                                        var inptText = $("#allOtherInputData2").val();
                                        var inptNwWndw = $("#allOtherInputData2").val();
                                        if (inptText === "") {
                                            inptText = "Read More...";
                                        }
                                        if (inptNwWndw === "") {
                                            inptNwWndw = true;
                                        }
                                        $('#articleBodyText').summernote('createLink', {
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
                        ['style', ['style']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph', 'height']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video', 'hr']],
                        ['view', ['fullscreen', 'codeview']],
                        ['help', ['help']],
                        ['misc', ['print']],
                        ['mybutton', ['upload']]
                    ],
                    buttons: {
                        upload: fileLink
                    },
                    callbacks: {
                        onImageUpload: function (file, editor, welEditable) {
                            sendNoticesFile(file[0], editor, welEditable, "IMAGES", function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                $('#articleBodyText').summernote("insertImage", inptUrl, 'filename');
                            });
                        }
                    }
                });
                if (vtyp != 3) {
                    /*Do Nothing*/
                } else {
                    var markupStr1 = typeof $("#articleIntroMsgDecoded").val() === 'undefined' ? '' : $("#articleIntroMsgDecoded").val();
                    var markupStr2 = typeof $("#articleBodyTextDecoded").val() === 'undefined' ? '' : $("#articleBodyTextDecoded").val();
                    $('#articleIntroMsg').summernote('code', urldecode(markupStr1));
                    $('#articleBodyText').summernote('code', urldecode(markupStr2));
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
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=40&typ=3&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdNoticeID=" + articleID);
    });
}

function performFileClick(elemId) {
    var elem = document.getElementById(elemId);
    if (elem && document.createEvent) {
        var evt = document.createEvent("MouseEvents");
        evt.initEvent("click", true, false);
        elem.dispatchEvent(evt);
    }
}

function sendNoticesFile(file, editor, welEditable, fileTypes, callBackFunc) {
    var data1 = new FormData();
    data1.append("file", file);
    if (fileTypes !== "IMAGES") {
        $.ajax({
            url: "dwnlds/uploader1.php",
            data: data1,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                $("#allOtherInputData1").val(data);
                callBackFunc();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + " " + errorThrown);
            }
        });
    } else {
        $.ajax({
            url: "dwnlds/uploader.php",
            data: data1,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                $("#allOtherInputData1").val(data);
                callBackFunc();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus + " " + errorThrown);
            }
        });
    }
}

function grpTypNoticesChange() {
    var lovChkngElementVal = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    lovNm = "";
    if (lovChkngElementVal === "Everyone" || lovChkngElementVal === "Public") {
        $('#groupName').attr("disabled", "true");
        $('#groupName').val("");
        $('#groupNameLbl').attr("disabled", "true");
    } else {
        $('#groupName').removeAttr("disabled");
        $('#groupName').val("");
        $('#groupNameLbl').removeAttr("disabled");
    }
}

function getNoticeLovs(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc) {
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
    getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
        criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
        selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc)
}

function getNoticeLovsTblr(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere,
    grpTypeElmntID, callBackFunc) {
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
    getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
        criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
        selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc)
}

function saveOneNoticeForm(actionText, slctr, linkArgs) {
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
            getAllNotices(actionText, slctr, linkArgs);
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function closeNoticeForm(actionText, slctr, linkArgs) {
    $('#myFormsModalLg').modal('hide');
    /*$('#myFormsModalLg').html("");
     $('#modal').modal('toggle');*/
    getAllNotices(actionText, slctr, linkArgs);
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
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find('.bootbox-body').html('Row Removed Successfully!');
                        }, 500);
                    }
                });
            }
        }
    });
}

function pblshUnplsh(rowIDAttrb, action, actionText, slctr, linkArgs) {
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
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                                            getAllNotices(actionText, slctr, linkArgs);
                                            dialog1.modal('hide');
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                    dialog1.modal('hide');
                                }
                            });
                        });
                    } else {
                        setTimeout(function () {
                            getAllNotices(actionText, slctr, linkArgs);
                            dialog1.modal('hide');
                        }, 500);
                    }
                });
            }
        }
    });
}

function sendComment(actionText, slctr, linkArgs) {
    var articleNwCmmntsMsg = typeof $("#articleNwCmmntsMsg").val() === 'undefined' ? '' : ($('#articleNwCmmntsMsg').summernote('code'));
    var allcommentsArticleID = typeof $("#allcommentsArticleID").val() === 'undefined' ? -1 : $("#allcommentsArticleID").val();
    var prntCmntID = typeof $("#crntPrnCmntID").val() === 'undefined' ? -1 : $("#crntPrnCmntID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                actyp: 3,
                articleID: allcommentsArticleID,
                prntCmntID: prntCmntID,
                articleComment: articleNwCmmntsMsg
            },
            success: function (result1) {
                getAllComments(actionText, slctr, linkArgs, 3);
                $('#articleNwCmmntsMsg').summernote('reset');
            },
            error: function (jqXHR1, textStatus1, errorThrown1) {
                console.log(errorThrown1);
            }
        });
    });
}

function showNoticeDetails(sbmtdNoticeID, ctgry) {
    openATab('#' + noticesElmntNm, 'grp=40&typ=3&pg=0&vtyp=4&sbmtdNoticeID=' + sbmtdNoticeID + '&sbmtdNoticeCtgry=' + ctgry);
}

function showArticleDetails(sbmtdNoticeID, ctgry) {
    openATab('#' + noticesElmntNm, 'grp=40&typ=3&pg=0&vtyp=4&sbmtdNoticeID=' + sbmtdNoticeID + '&sbmtdNoticeCtgry=' + ctgry);
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
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                            error: function (jqXHR, textStatus, errorThrown) {
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
                $('#fdbckMsgBody').summernote('code', '<p></p>');
            }
        }
    });
}

function attchFileToFdbck() {
    var crntAttchMnts = $("#fdbckMailAttchmnts").val();
    $("#allOtherFileInput2").change(function () {
        var fileName = $(this).val();
        var input = document.getElementById('allOtherFileInput2');
        sendFdbckFile(input.files[0], function () {
            var inptUrl = $("#allOtherInputData2").val();
            crntAttchMnts = crntAttchMnts + ";" + inptUrl;
            $("#fdbckMailAttchmnts").val(crntAttchMnts);
        });
    });
    performFileClick('allOtherFileInput2');
}

function sendFdbckFile(file, callBackFunc) {
    var data1 = new FormData();
    data1.append("file", file);
    $.ajax({
        url: "dwnlds/uploader1.php",
        data: data1,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            $("#allOtherInputData2").val(data);
            callBackFunc();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
        }
    });
}

function checkWkfRqstStatus(pKeyID, pKeyTitle) {
    var lnkArgs = 'grp=40&typ=2&pg=0&vtyp=8&RoutingID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', pKeyTitle, 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#gnrlInbxActionsTable')) {
            var table1 = $('#gnrlInbxActionsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#gnrlInbxActionsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function insertNewRowBe4(tableElmntID, position, inptHtml, callBackFunc) {
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
    var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
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

function insertNewRowAfta(tableElmntID, position, inptHtml, callBackFunc) {
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
    var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
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
    return unescape(decodeURIComponent(str.replace(/\+/g, ' ')));
}

function urlencode(str) {
    return escape(encodeURIComponent(str.replace(/\+/g, ' ')));
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
        autodestroy: true,
        onshow: function (dialog) {},
        buttons: [{
            label: 'Cancel',
            icon: 'glyphicon glyphicon-ban-circle',
            action: function (dialogItself) {
                dialogItself.close();
            }
        }, {
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
                        q: 'logout'
                    },
                    success: function (result) {
                        window.location = "index.php";
                    }
                });
            }
        }]
    });
}

var isLgnSccfl = 0;
var curDialogItself = null;
var lgnBtnSsn = null;
var curCallBack = null;

function getMsgAsync(linkArgs, callback) {
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
                    '<fieldset>' +
                    '<div class="form-group" style="margin-bottom:10px !important;">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon" id="basic-addon1">' +
                    '<i class="fa fa-user fa-fw fa-border"></i></span>' +
                    '<input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" id="usrnm" name="usrnm" onkeyup="enterKeyFuncLgn(event);" autofocus>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group" style="margin-bottom:10px !important;">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-key fa-fw fa-border"></i></span>' +
                    '<input class="form-control" placeholder="Password" id="pwd" name="pwd" type="password" value=""  onkeyup="enterKeyFuncLgn(event);">' +
                    '<input type="hidden" id="machdet" name="machdet" value="Unknown">' +
                    '</div>' +
                    '</div>' +
                    '<p class="label" id="msgArea">' +
                    '<label style="color:red;font-size:12px;text-align: center;">' +
                    '&nbsp;' +
                    '</label>' +
                    '</p>' +
                    '</fieldset>' +
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
                                    q: 'logout'
                                },
                                success: function (result) {
                                    window.location = "index.php";
                                }
                            });
                        }
                    }, {
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
                            homePageLgn(dialogItself, callback);
                        }
                    }]
                });
            } else {
                callback();
            }
        } else {

        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(linkArgs.trim());
}

function getMsgAsyncSilent(linkArgs, callback) {
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
                    '<fieldset>' +
                    '<div class="form-group" style="margin-bottom:10px !important;">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon" id="basic-addon1">' +
                    '<i class="fa fa-user fa-fw fa-border"></i></span>' +
                    '<input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" id="usrnm" name="usrnm" onkeyup="enterKeyFuncLgn(event);" autofocus>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group" style="margin-bottom:10px !important;">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon" id="basic-addon1"><i class="fa fa-key fa-fw fa-border"></i></span>' +
                    '<input class="form-control" placeholder="Password" id="pwd" name="pwd" type="password" value=""  onkeyup="enterKeyFuncLgn(event);">' +
                    '<input type="hidden" id="machdet" name="machdet" value="Unknown">' +
                    '</div>' +
                    '</div>' +
                    '<p class="label" id="msgArea">' +
                    '<label style="color:red;font-size:12px;text-align: center;">' +
                    '&nbsp;' +
                    '</label>' +
                    '</p>' +
                    '</fieldset>' +
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
                                    q: 'logout'
                                },
                                success: function (result) {
                                    window.location = "index.php";
                                }
                            });
                        }
                    }, {
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
                            homePageLgn(dialogItself, callback);
                        }
                    }]
                });
            } else {
                callback();
            }
        } else {

        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(linkArgs.trim());
}

function enterKeyFuncLgn(e) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        lgnBtnSsn.disable();
        lgnBtnSsn.spin();
        curDialogItself.setClosable(false);
        homePageLgn(curDialogItself, curCallBack);
    }
    //return false;
}

function homePageLgn(dialogItself, callback) {
    //alert("test" + myCountry() + myIP());
    var xmlhttp;
    var usrNm = "";
    var old_pswd = "";
    var lnkArgs = "";
    var machdet = "";
    usrNm = document.getElementById("usrnm").value;
    old_pswd = document.getElementById("pwd").value;
    machdet = document.getElementById("machdet").value;

    if (usrNm === "" || usrNm === null) {
        $('#modal-7 .modal-body').html('User Name cannot be empty!');
        $('#modal-7').modal('show', {
            backdrop: 'static'
        });
        return false;
    }
    if (old_pswd === "" || old_pswd === null) {
        $('#modal-7 .modal-body').html('Password cannot be empty!');
        $('#modal-7').modal('show', {
            backdrop: 'static'
        });
        return false;
    }
    lnkArgs = "usrnm=" + usrNm + "&pwd=" + old_pswd + "&machdet=" + machdet + "&screenwdth=" + screen.width;

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
                callback();
            } else {
                lgnBtnSsn.enable();
                lgnBtnSsn.stopSpin();
                dialogItself.setClosable(true);
                isLgnSccfl = 0;
                document.getElementById("msgArea").innerHTML = "<span style=\"color:red;font-size:12px;text-align: center;margin-top:0px;\">&nbsp;" + rspns + "</span>";
            }
        } else {
            isLgnSccfl = 0;
            document.getElementById("msgArea").innerHTML = "<img style=\"width:105px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='cmn_images/ajax-loader2.gif'/><span style=\"color:blue;font-size:11px;text-align: center;margin-top:0px;\">Loading...Please Wait...</span>";
        }
    };

    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(lnkArgs); //+ "&machdetls=" + machDet
}

function dwnldAjxCall(linkArgs, elemtnID) {
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
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
                window.open(xmlhttp.responseText, '_blank');
            } else {
                document.getElementById(elemtnID).innerHTML = "<p><img style=\"width:80px;height:25px;display:inline;float:left;margin-right:5px;clear: left;\" src='cmn_images/animated_loading.gif'/>Loading...Please Wait...</p>";
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
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

    xmlhttp.open("GET", "http://api.hostip.info/get_html.php", false);
    var res = "";
    var hostipInfo, ipAddress;
    var t = setTimeout(function () {
        xmlhttp.abort();
        xmlhttp = null;
        res = "Unknown";
    }, 3000);
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
    var hostipInfo, ipAddress;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("GET", "http://api.hostip.info/get_html.php", false);
    var t = setTimeout(function () {
        xmlhttp.abort();
        xmlhttp = null;
        res = "Unknown";
    }, 3000);
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