function prepareDataAdmin(lnkArgs, htBody, targ, rspns) {
    $body = htBody;
    if (lnkArgs.indexOf("&pg=7") !== -1) {
        /*$("#allOtherContent").html(rspns);
         $(document).ready(function () {
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
         if (inptText === "")
         {
         inptText = "Read More...";
         }
         if (inptNwWndw === "")
         {
         inptNwWndw = true;
         }
         $('#bulkMessageBody').summernote('createLink', {
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
         callbacks:
         {
         onImageUpload: function (file, editor, welEditable)
         {
         sendNoticesFile(file[0], editor, welEditable, "IMAGES", function () {
         var inptUrl = $("#allOtherInputData1").val();
         $('#bulkMessageBody').summernote("insertImage", inptUrl, 'filename');
         });
         }
         }
         });
         $('.note-editable').trigger('focus');
         $('#sndBlkMsgForm').on('show.bs.modal', function (e) {
         $(this).find('.modal-body').css({
         'max-height': '100%'
         });
         });
         $body.removeClass("mdlloadingDiag");
         $('#sndBlkMsgForm').modal({backdrop: 'static', keyboard: false});
         $body.removeClass("mdlloading");
         });*/
    } else {
        $(targ).html(rspns);
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1 ||
                lnkArgs.indexOf("&pg=6&vtyp=0") !== -1) {
                var table1 = $('#dataAdminTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#dataAdminTable').wrap('<div class="dataTables_scroll"/>');
                $('#dataAdminForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=8") !== -1) {
                var table1 = $('#extrDtColsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#extrDtColsTable').wrap('<div class="dataTables_scroll"/>');
                $('#extrDtColsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            }
            htBody.removeClass("mdlloading");
        });
    }
}

function getDataAdmin(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#dataAdminSrchFor").val() === 'undefined' ? '%' : $("#dataAdminSrchFor").val();
    var srchIn = typeof $("#dataAdminSrchIn").val() === 'undefined' ? 'Both' : $("#dataAdminSrchIn").val();
    var pageNo = typeof $("#dataAdminPageNo").val() === 'undefined' ? 1 : $("#dataAdminPageNo").val();
    var limitSze = typeof $("#dataAdminDsplySze").val() === 'undefined' ? 30 : $("#dataAdminDsplySze").val();
    var sortBy = typeof $("#dataAdminSortBy").val() === 'undefined' ? '' : $("#dataAdminSortBy").val();
    var dataAdminFilterBy = typeof $("#dataAdminFilterBy").val() === 'undefined' ? '' : $("#dataAdminFilterBy").val();
    var dataAdminFilterByVal = typeof $("#dataAdminFilterByVal").val() === 'undefined' ? '' : $("#dataAdminFilterByVal").val();
    var dataAdminShwAllOrgs = $('#dataAdminShwAllOrgs:checked').length > 0;
    if (actionText === 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText === 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText === 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
        "&dataAdminFilterBy=" + dataAdminFilterBy + "&dataAdminFilterByVal=" + dataAdminFilterByVal +
        "&dataAdminShwAllOrgs=" + dataAdminShwAllOrgs;
    openATab(slctr, linkArgs);
}

function enterKeyFuncDtAdmn(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode === 13) {
        getDataAdmin(actionText, slctr, linkArgs);
    }
}

function getBscProfileForm(elementID, modalBodyID, titleElementID, formElementID,
    formTitle, personID, vtyp, pgNo, addOrEdit, actionText, daGender, daNationality, daReligion, daRelType, daRelCause, iDPrfxComboBox) {
    if (typeof actionText === 'undefined' || actionText === null) {
        actionText = 'ShowDialog';
    }
    if (typeof formTitle === 'undefined' || formTitle === null) {
        formTitle = '';
    }
    if (typeof daGender === 'undefined' || daGender === null) {
        daGender = '';
    }
    if (typeof daNationality === 'undefined' || daNationality === null) {
        daNationality = '';
    }
    if (typeof daReligion === 'undefined' || daReligion === null) {
        daReligion = '';
    }
    if (typeof daRelType === 'undefined' || daRelType === null) {
        daRelType = '';
    }
    if (typeof daRelCause === 'undefined' || daRelCause === null) {
        daRelCause = '';
    }
    if (typeof iDPrfxComboBox === 'undefined' || iDPrfxComboBox === null) {
        iDPrfxComboBox = '';
    }
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        $('#' + modalBodyID).html("");
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
                /*$('.modal-dialog').draggable();*/
                if (pgNo == 1) {
                    var table1 = $('#nationalIDTblRO').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#nationalIDTblRO').wrap('<div class="dataTables_scroll" />');

                    $('[data-toggle="tabajxprflro"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=8&typ=1' + dttrgt;
                        return openATab(targ, linkArgs);
                    });
                } else if (pgNo == 2) {
                    var table1 = $('#nationalIDTblEDT').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#nationalIDTblEDT').wrap('<div class="dataTables_scroll" />');

                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="tabajxprfledt"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=8&typ=1' + dttrgt;
                        $(targ + 'tab').tab('show');
                        if (targ.indexOf('prflHomeEDT') >= 0) {
                            $('#prflAddPrsnDataEDT').addClass('hideNotice');
                            $('#prflOrgAsgnEDT').addClass('hideNotice');
                            $('#prflCVEDT').addClass('hideNotice');
                            $('#prflOthrInfoEDT').addClass('hideNotice');
                            $('#prflHomeEDT').removeClass('hideNotice');
                        } else {
                            openATab(targ, linkArgs);
                            if (targ.indexOf('prflAddPrsnDataEDT') >= 0) {
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').addClass('hideNotice');
                                $('#prflCVEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').addClass('hideNotice');
                                $('#prflAddPrsnDataEDT').removeClass('hideNotice');
                            } else if (targ.indexOf('prflOrgAsgnEDT') >= 0) {
                                $('#prflAddPrsnDataEDT').addClass('hideNotice');
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflCVEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').removeClass('hideNotice');
                            } else if (targ.indexOf('prflCVEDT') >= 0) {
                                $('#prflAddPrsnDataEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').addClass('hideNotice');
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').addClass('hideNotice');
                                $('#prflCVEDT').removeClass('hideNotice');
                            } else if (targ.indexOf('prflOthrInfoEDT') >= 0) {
                                $('#prflAddPrsnDataEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').addClass('hideNotice');
                                $('#prflCVEDT').addClass('hideNotice');
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').removeClass('hideNotice');
                            }
                        }
                    });
                }
                $(function () {
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
                    }).on('hide', function (ev) {
                        $('#myFormsModalLg').css("overflow", "auto");
                    });
                });
                /*$('#' + elementID).off('show.bs.modal');
                 $('#' + elementID).on('show.bs.modal', function (e) {
                 $(this).find('.modal-body').css({
                 'max-height': '100%'
                 });
                 });*/
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).one("hidden.bs.modal", function (e) {
                    if (formTitle.trim() === 'Add Person Basic Profile (Direct)' || formTitle.trim().indexOf('(Direct)') >= 0) {
                        /*Do nothing*/
                    } else {
                        getDataAdmin('', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');
                    }
                    $(e.currentTarget).unbind();
                });
                $body.removeClass("mdlloadingDiag");
                if (actionText === 'ShowDialog') {
                    $('#' + elementID).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
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
        xmlhttp.send("grp=8&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdPersonID=" + personID + "&addOrEdit=" + addOrEdit +
            "&elementID=" + elementID + "&modalBodyID=" + modalBodyID + "&titleElementID=" + titleElementID + "&formElementID=" + formElementID +
            "&formTitle=" + formTitle + "&daGender=" + daGender + "&daNationality=" + daNationality + "&daReligion=" + daReligion + "&daRelType=" + daRelType +
            "&daRelCause=" + daRelCause + "&iDPrfxComboBox=" + iDPrfxComboBox);
    });
}

function getBscProfile1Form(elementID, modalBodyID, titleElementID, formElementID,
    formTitle, personID, vtyp, pgNo, addOrEdit, actionText, daGender, daNationality, daReligion, daRelType, daRelCause, iDPrfxComboBox) {
    if (typeof actionText === 'undefined' || actionText === null) {
        actionText = 'ShowDialog';
    }
    if (typeof formTitle === 'undefined' || formTitle === null) {
        formTitle = '';
    }
    if (typeof daGender === 'undefined' || daGender === null) {
        daGender = '';
    }
    if (typeof daNationality === 'undefined' || daNationality === null) {
        daNationality = '';
    }
    if (typeof daReligion === 'undefined' || daReligion === null) {
        daReligion = '';
    }
    if (typeof daRelType === 'undefined' || daRelType === null) {
        daRelType = '';
    }
    if (typeof daRelCause === 'undefined' || daRelCause === null) {
        daRelCause = '';
    }
    if (typeof iDPrfxComboBox === 'undefined' || iDPrfxComboBox === null) {
        iDPrfxComboBox = '';
    }
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
                /*$('.modal-dialog').draggable();*/
                if (pgNo == 1) {
                    var table1 = $('#nationalIDTblRO').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#nationalIDTblRO').wrap('<div class="dataTables_scroll" />');

                    $('[data-toggle="tabajxprflro"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=8&typ=1' + dttrgt;
                        return openATab(targ, linkArgs);
                    });
                } else if (pgNo == 2) {
                    var table1 = $('#nationalIDTblEDT').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#nationalIDTblEDT').wrap('<div class="dataTables_scroll" />');

                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="tabajxprfledt"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=8&typ=1' + dttrgt;
                        $(targ + 'tab').tab('show');
                        if (targ.indexOf('prflHomeEDT') >= 0) {
                            $('#prflAddPrsnDataEDT').addClass('hideNotice');
                            $('#prflOrgAsgnEDT').addClass('hideNotice');
                            $('#prflCVEDT').addClass('hideNotice');
                            $('#prflOthrInfoEDT').addClass('hideNotice');
                            $('#prflHomeEDT').removeClass('hideNotice');
                        } else {
                            openATab(targ, linkArgs);
                            if (targ.indexOf('prflAddPrsnDataEDT') >= 0) {
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').addClass('hideNotice');
                                $('#prflCVEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').addClass('hideNotice');
                                $('#prflAddPrsnDataEDT').removeClass('hideNotice');
                            } else if (targ.indexOf('prflOrgAsgnEDT') >= 0) {
                                $('#prflAddPrsnDataEDT').addClass('hideNotice');
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflCVEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').removeClass('hideNotice');
                            } else if (targ.indexOf('prflCVEDT') >= 0) {
                                $('#prflAddPrsnDataEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').addClass('hideNotice');
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').addClass('hideNotice');
                                $('#prflCVEDT').removeClass('hideNotice');
                            } else if (targ.indexOf('prflOthrInfoEDT') >= 0) {
                                $('#prflAddPrsnDataEDT').addClass('hideNotice');
                                $('#prflOrgAsgnEDT').addClass('hideNotice');
                                $('#prflCVEDT').addClass('hideNotice');
                                $('#prflHomeEDT').addClass('hideNotice');
                                $('#prflOthrInfoEDT').removeClass('hideNotice');
                            }
                        }
                    });
                }
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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
                });

                $('#' + elementID).off('show.bs.modal');
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).one("hidden.bs.modal", function (e) {
                    if (formTitle.trim() === 'Add Person Basic Profile (Direct)' || formTitle.trim().indexOf('(Direct)') >= 0) {
                        /*Do nothing*/
                    } else {
                        getDataAdmin('', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=1');
                    }
                    $(e.currentTarget).unbind();
                });
                $body.removeClass("mdlloadingDiag");
                if (actionText === 'ShowDialog') {
                    $('#' + elementID).modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
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
        xmlhttp.send("grp=8&typ=1&pg=" + pgNo + "&vtyp=" + vtyp + "&sbmtdPersonID=" + personID + "&addOrEdit=" + addOrEdit +
            "&elementID=" + elementID + "&modalBodyID=" + modalBodyID + "&titleElementID=" + titleElementID + "&formElementID=" + formElementID +
            "&formTitle=" + formTitle + "&daGender=" + daGender + "&daNationality=" + daNationality + "&daReligion=" + daReligion + "&daRelType=" + daRelType +
            "&daRelCause=" + daRelCause + "&iDPrfxComboBox=" + iDPrfxComboBox);
    });
}

function getDivsGroupsForm(elementID, modalBodyID, titleElementID, formElementID,
    tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, personID) {
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
                $('#' + modalBodyID + 'Diag').draggable();
                $(function () {
                    $('.form_date').datetimepicker({
                        format: "d-M-yyyy",
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
                });

                if (addOrEdit === 'EDIT') {
                    /*Get various field element IDs and populate values*/
                    var $tds = $('#' + tRowElementID).find('td');
                    $('#divGrpName').val($.trim($tds.eq(1).text()));
                    $('#divGrpTyp').val($.trim($tds.eq(2).text()));
                    $('#divGrpStartDate').val($.trim($tds.eq(3).text()));
                    $('#divGrpEndDate').val($.trim($tds.eq(4).text()));
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
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
        xmlhttp.send("grp=8&typ=1&pg=2&vtyp=" + vtyp + "&divsGrpsPkeyID=" + pKeyID + "&sbmtdPersonID=" + personID);
    });
}

function saveDivsGroupsForm(elementID, pKeyID, personID, tableElementID) {
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var divGrpName = typeof $("#divGrpName").val() === 'undefined' ? '%' : $("#divGrpName").val();
        var divGrpTyp = typeof $("#divGrpTyp").val() === 'undefined' ? 'Both' : $("#divGrpTyp").val();
        var divGrpStartDate = typeof $("#divGrpStartDate").val() === 'undefined' ? 1 : $("#divGrpStartDate").val();
        var divGrpEndDate = typeof $("#divGrpEndDate").val() === 'undefined' ? 10 : $("#divGrpEndDate").val();
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
                $('#' + tableElementID).append('<tr><td></td><td colspan="6">' + xmlhttp.responseText + '</td></tr>');
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                $('#' + elementID).modal('hide');
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=8&typ=1&pg=2&q=UPDATE&actyp=4" +
            "&divGrpName=" + divGrpName +
            "&divGrpTyp=" + divGrpTyp +
            "&divGrpStartDate=" + divGrpStartDate +
            "&divGrpEndDate=" + divGrpEndDate +
            "&divsGrpsPkeyID=" + pKeyID +
            "&sbmtdPersonID=" + personID);
    });
}

function saveExtrDataCol(slctr, linkArgs) {
    var slctdExtrDataCols = "";
    var isVld = true;
    var errMsg = "";
    $('#extrDtColsTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var fieldLbl = $('#extrDtColsRow' + rndmNum + '_FieldLbl').val();
                var colNum = $('#extrDtColsRow' + rndmNum + '_ColNum').val();
                if (typeof $('#extrDtColsRow' + rndmNum + '_ColNum').val() === 'undefined') {
                    isVld = false;
                }
                if (colNum.trim() === '') {
                    isVld = false;
                } else {
                    if (fieldLbl.trim() !== '') {
                        var dtTyp = $('#extrDtColsRow' + rndmNum + '_DtTyp').val();
                        if (dtTyp.trim() === '') {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Data Type for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrDtColsRow' + rndmNum + '_DtTyp').addClass('rho-error');
                        } else {
                            $('#extrDtColsRow' + rndmNum + '_DtTyp').removeClass('rho-error');
                        }
                        var ctgry = $('#extrDtColsRow' + rndmNum + '_Ctgry').val();
                        if (ctgry.trim() === '') {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Category for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrDtColsRow' + rndmNum + '_Ctgry').addClass('rho-error');
                        } else {
                            $('#extrDtColsRow' + rndmNum + '_Ctgry').removeClass('rho-error');
                        }
                        var dspTyp = $('#extrDtColsRow' + rndmNum + '_DspTyp').val();
                        if (dspTyp.trim() === '') {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Display Type for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrDtColsRow' + rndmNum + '_DspTyp').addClass('rho-error');
                        } else {
                            $('#extrDtColsRow' + rndmNum + '_DspTyp').removeClass('rho-error');
                        }
                        var dtLen = $('#extrDtColsRow' + rndmNum + '_DtLen').val();
                        if (dtLen.trim() === '') {
                            $('#extrDtColsRow' + rndmNum + '_DtLen').val(200);
                        }
                        var order = $('#extrDtColsRow' + rndmNum + '_Order').val();
                        if (order.trim() === '') {
                            $('#extrDtColsRow' + rndmNum + '_Order').val(1);
                        }
                        var tblColsNum = $('#extrDtColsRow' + rndmNum + '_TblColsNum').val();
                        if (tblColsNum.trim() === '') {
                            if (dspTyp === 'Tabular') {
                                $('#extrDtColsRow' + rndmNum + '_TblColsNum').val(1);
                            } else {
                                $('#extrDtColsRow' + rndmNum + '_TblColsNum').val(0);
                            }
                        }
                        var tblrColNms = $('#extrDtColsRow' + rndmNum + '_TblrColNms').val();
                        if (tblrColNms.trim() === '' && dspTyp === 'Tabular') {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Tabular Column Names for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrDtColsRow' + rndmNum + '_TblrColNms').addClass('rho-error');
                        } else {
                            $('#extrDtColsRow' + rndmNum + '_TblrColNms').removeClass('rho-error');
                            if (dspTyp !== 'Tabular') {
                                $('#extrDtColsRow' + rndmNum + '_TblrColNms').val('');
                            }
                        }
                    }
                }
                if (isVld === false) {
                    /*Do Nothing*/
                } else {
                    var isRqrd = typeof $("input[name='extrDtColsRow" + rndmNum + "_IsRqrd']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    slctdExtrDataCols = slctdExtrDataCols + $('#extrDtColsRow' + rndmNum + '_ColNum').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_ExtrDtID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_FieldLbl').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_LovNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_DtTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_Ctgry').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_DtLen').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_DspTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_TblColsNum').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_Order').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        $('#extrDtColsRow' + rndmNum + '_TblrColNms').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        isRqrd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                }
            }
        }
    });
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Fields/Columns',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Fields/Columns...Please Wait...</p>',
        callback: function () {
            openATab(slctr, linkArgs);
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
                    grp: 8,
                    typ: 1,
                    pg: 8,
                    q: 'UPDATE',
                    actyp: 1,
                    slctdExtrDataCols: slctdExtrDataCols
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

function delExtrDataCol(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var colNum = '';
    if (typeof $('#extrDtColsRow' + rndmNum + '_ExtrDtID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#extrDtColsRow' + rndmNum + '_ExtrDtID').val();
        colNum = $('#extrDtColsRow' + rndmNum + '_ColNum').val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Field/Column?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Field/Column?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Field/Column?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Field/Column...Please Wait...</p>'
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
                                    grp: 8,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETE',
                                    actyp: 1,
                                    colNum: colNum,
                                    extrdataID: pKeyID
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

function onPrsnFilterByChange() {
    var dataAdminFilterBy = typeof $("#dataAdminFilterBy").val() === 'undefined' ? '' : $("#dataAdminFilterBy").val();
    var dataAdminFilterByVal = typeof $("#dataAdminFilterByVal").val() === 'undefined' ? '' : $("#dataAdminFilterByVal").val();

    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 8);
        formData.append('typ', 1);
        formData.append('pg', 5);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        formData.append('dataAdminFilterBy', dataAdminFilterBy);
        formData.append('dataAdminFilterByVal', dataAdminFilterByVal);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.message.indexOf("Success") !== -1) {
                    var options = data.FilterOptions.split(";");
                    $("#dataAdminFilterByVal").empty();
                    for (var i = 0; i < options.length; i++) {
                        var defaultSlctd = false;
                        var isSlctd = false;
                        if (dataAdminFilterByVal === options[i]) {
                            defaultSlctd = true;
                            isSlctd = true;
                        }
                        var o = new Option(options[i], options[i], defaultSlctd, isSlctd);
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(o).html(options[i]);
                        $("#dataAdminFilterByVal").append(o);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function onPrsnFltrValChange() {
    getDataAdmin('', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');
}


var prgstimerid2;
var exprtBtn;
var exprtBtn2;

function exprtPersons() {
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
        '<p style="color:#000;">' +
        'How many Persons will you like to Export?' +
        '<br/>1=No Persons(Empty Template)' +
        '<br/>2=All Persons' +
        '<br/>3-Infinity=Specify the exact number of Persons to Export<br/>' +
        '</p>' +
        '<div class="form-group" style="margin-bottom:10px !important;">' +
        '<div class="input-group">' +
        '<span class="input-group-addon" id="basic-addon1">' +
        '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
        '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
        '</div>' +
        '</div>' +
        '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
        '</p>' +
        '</form>';

    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: 'Export Persons!',
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {},
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton('btn_exprt_rpt');
            $("#recsToExprtForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#recsToExprt").keyup(function (e) {
                var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
                if (charCode == 13) {
                    $("#btn_exprt_rpt").click();
                }
            });
            $('#recsToExprt').val(2);
            $('#recsToExprt').focus();
        },
        buttons: [{
            label: 'Cancel',
            icon: 'glyphicon glyphicon-menu-left',
            cssClass: 'btn-default',
            action: function (dialogItself) {
                window.clearInterval(prgstimerid2);
                dialogItself.close();
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        }, {
            id: 'btn_exprt_rpt',
            label: 'Export',
            icon: 'glyphicon glyphicon-menu-right',
            cssClass: 'btn-primary',
            action: function (dialogItself) {
                /*Validate Input and Do Ajax if OK*/
                var inptNum = $('#recsToExprt').val();
                if (!isNumber(inptNum)) {
                    var dialog = bootbox.alert({
                        title: 'Exporting Persons',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () {}
                    });
                    return false;
                } else {
                    var $button = this;
                    $button.disable();
                    $button.spin();
                    dialogItself.setClosable(false);
                    document.getElementById("msgAreaExprt").innerHTML = "<img style=\"width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;\" src='cmn_images/ajax-loader2.gif'/><br/><span style=\"color:blue;font-size:11px;text-align: left;margin-top:0px;\">Working on Export...Please Wait...</span>";
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 8,
                                typ: 1,
                                pg: 5,
                                q: 'UPDATE',
                                actyp: 3,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshPersonsPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshPersonsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 8,
            typ: 1,
            pg: 5,
            q: 'UPDATE',
            actyp: 4
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf('Error') !== -1) {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(data.message + '<br/><a href="' + data.dwnld_url + '">Click to Download File!</a>');
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html('<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message);
                document.getElementById("msgAreaExprt").innerHTML = '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function importPersons() {
    var dataToSend = "";
    var isFileValid = true;
    var errMsg1 = '';
    var dialog1 = bootbox.confirm({
        title: 'Import Persons?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT PERSONS</span> to overwrite existing ones?<br/><span style="color:red;font-weight:bold;font-style:italic;">NB: Specified Template Values may be used to Overwrite that of Existing Persons!</span><br/>Action cannot be Undone!</p>',
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
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val('');
                    $("#allOtherFileInput6").off('change');
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById('allOtherFileInput6');
                        var file = input.files[0];
                        // read the file metadata
                        var output = '';
                        output += '<span style="font-weight:bold;">' + escape(file.name) + '</span><br />\n';
                        output += ' - FileType: ' + (file.type || 'n/a') + '<br />\n';
                        output += ' - FileSize: ' + file.size + ' bytes<br />\n';
                        output += ' - LastModified: ' + (file.lastModifiedDate ? file.lastModifiedDate.toLocaleDateString() : 'n/a') + '<br />\n';

                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: 'Validating Selected File',
                            message: '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' + output + '</div>',
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton('btn-srvr-prcs');
                                    $footerButton.disable();
                                    // read the file content
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert('File Not Found!');
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert('File is not readable');
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break; // noop
                                            default:
                                                alert('An error occurred reading this file.');
                                        };
                                    };
                                    reader.onprogress = function (evt) {
                                        // evt is an ProgressEvent.
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
                                            // Increase the progress bar length.
                                            var elem = document.getElementById('myBar');
                                            elem.style.width = percentLoaded + '%';
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' + percentLoaded + '% Validating Selected File...Please Wait...</span>');
                                            } else {
                                                $("#myInformation").html('<span style="color:green;"><i class="fa fa-check"></i>' + percentLoaded + '% Validating Selected File Completed!</span>');

                                                var $footerButton = dialogItself.getButton('btn-srvr-prcs');
                                                if (isFileValid === true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert('File read cancelled');
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById('myBar');
                                        elem.style.width = '1%';
                                        $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing Persons...Please Wait...</span>');
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            /*"ID NO.**", "TITLE**", "FIRST NAME**", "SURNAME**", "OTHER NAMES",
                                             "GENDER**", "MARITAL STATUS**", "DATE OF BIRTH**", "PLACE OF BIRTH", "HOME TOWN", "RELIGION", "RESIDENTIAL ADDRESS", "POSTAL ADDRESS",
                                             "EMAIL", "TEL.", "MOBILE", "FAX", "NATIONALITY**", "IMAGE FILE NAME", "PERSON TYPE**", "PERSON TYPE REASON**", "PERSON TYPE FUTHER DETAILS",
                                             "FROM", "TO", "LINKED FIRM /WORKPLACE", "SITE/BRANCH", "PERSON TYPE HISTORY(Relation/Person Type~Cause of Relation~Start Date~End Date|)", 
                                             "DIVISIONS/GROUPS(Group Code~Start Date~End Date|)", "IMMEDIATE SUPERVISORS(Supervisor ID No.~Start Date~End Date|)", 
                                             "SITES/LOCATIONS(Site/Location Code~Start Date~End Date|)", "JOBS(Job Code~Start Date~End Date|)",
                                             "GRADES(Grade Code~Start Date~End Date|)", "POSITIONS(Position Code~Division/Group~Start Date~End Date|)",
                                             "EDUCATIONAL BACKGROUND(Course Name~School/Institution~School Location~Certificate Obtained~Certificate Type~Date Awarded~Start Date~End Date|)",
                                             "WORKING EXPERIENCE(Job Name/Title~Institution Name~Job Location~Job Description~Feats/Achievements~Start Date~End Date|)",
                                             "SKILLS/NATURE(Languages~Hobbies~Interests~Conduct~Attitude~From Date~To Date|)"*/
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var locIDNo = "";
                                            var prsnTitle = "";
                                            var prsnFirstNm = "";
                                            var prsnSurname = "";
                                            var prsnOthrNames = "";
                                            var prsnGender = "";
                                            var prsnMarStatus = "";
                                            var prsnDOB = "";
                                            var prsnBirthPlace = "";
                                            var prsnHomeTown = "";
                                            var prsnReligion = "";
                                            var prsnResAddrs = "";
                                            var prsnPostlAddrs = "";
                                            var prsnEmail = "";
                                            var prsnTelNo = "";
                                            var prsnMobile = "";
                                            var prsnFax = "";
                                            var prsnNatnlTy = "";
                                            var prsnImageFile = "";
                                            var prsnType = "";
                                            var prsnTypeRsn = "";
                                            var prsnTypDet = "";
                                            var prsnTypStrt = "";
                                            var prsnTypEnd = "";
                                            var lnkdFirm = "";
                                            var lnkdFirmSite = "";
                                            var prsnTypeHstry = "";
                                            var prsnDivGrpHstry = "";
                                            var prsnSpvsrHstry = "";
                                            var prsnSiteLocsHstry = "";
                                            var prsnJobsHstry = "";
                                            var prsnGradesHstry = "";
                                            var prsnPostnHstry = "";
                                            var prsnEducHstry = "";
                                            var prsnWorkHstry = "";
                                            var prsnSkillsHstry = "";
                                            var prsnIDCardHstry = "";
                                            var prsnAdtnlData1 = "";
                                            var prsnAdtnlData2 = "";
                                            var prsnAdtnlData3 = "";
                                            var prsnAdtnlData4 = "";
                                            var prsnAdtnlData5 = "";
                                            var prsnAdtnlData6 = "";
                                            var prsnAdtnlData7 = "";
                                            var prsnAdtnlData8 = "";
                                            var prsnAdtnlData9 = "";
                                            var prsnAdtnlData10 = "";
                                            var prsnAdtnlData11 = "";
                                            var prsnAdtnlData12 = "";
                                            var prsnAdtnlData13 = "";
                                            var prsnAdtnlData14 = "";
                                            var prsnAdtnlData15 = "";
                                            var prsnAdtnlData16 = "";
                                            var prsnAdtnlData17 = "";
                                            var prsnAdtnlData18 = "";
                                            var prsnAdtnlData19 = "";
                                            var prsnAdtnlData20 = "";
                                            var prsnAdtnlData21 = "";
                                            var prsnAdtnlData22 = "";
                                            var prsnAdtnlData23 = "";
                                            var prsnAdtnlData24 = "";
                                            var prsnAdtnlData25 = "";
                                            var prsnAdtnlData26 = "";
                                            var prsnAdtnlData27 = "";
                                            var prsnAdtnlData28 = "";
                                            var prsnAdtnlData29 = "";
                                            var prsnAdtnlData30 = "";
                                            var prsnAdtnlData31 = "";
                                            var prsnAdtnlData32 = "";
                                            var prsnAdtnlData33 = "";
                                            var prsnAdtnlData34 = "";
                                            var prsnAdtnlData35 = "";
                                            var prsnAdtnlData36 = "";
                                            var prsnAdtnlData37 = "";
                                            var prsnAdtnlData38 = "";
                                            var prsnAdtnlData39 = "";
                                            var prsnAdtnlData40 = "";
                                            var prsnAdtnlData41 = "";
                                            var prsnAdtnlData42 = "";
                                            var prsnAdtnlData43 = "";
                                            var prsnAdtnlData44 = "";
                                            var prsnAdtnlData45 = "";
                                            var prsnAdtnlData46 = "";
                                            var prsnAdtnlData47 = "";
                                            var prsnAdtnlData48 = "";
                                            var prsnAdtnlData49 = "";
                                            var prsnAdtnlData50 = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            locIDNo = data[row][item];
                                                            break;
                                                        case 2:
                                                            prsnTitle = data[row][item];
                                                            break;
                                                        case 3:
                                                            prsnFirstNm = data[row][item];
                                                            break;
                                                        case 4:
                                                            prsnSurname = data[row][item];
                                                            break;
                                                        case 5:
                                                            prsnOthrNames = data[row][item];
                                                            break;
                                                        case 6:
                                                            prsnGender = data[row][item];
                                                            break;
                                                        case 7:
                                                            prsnMarStatus = data[row][item];
                                                            break;
                                                        case 8:
                                                            prsnDOB = data[row][item];
                                                            break;
                                                        case 9:
                                                            prsnBirthPlace = data[row][item];
                                                            break;
                                                        case 10:
                                                            prsnHomeTown = data[row][item];
                                                            break;
                                                        case 11:
                                                            prsnReligion = data[row][item];
                                                            break;
                                                        case 12:
                                                            prsnResAddrs = data[row][item];
                                                            break;
                                                        case 13:
                                                            prsnPostlAddrs = data[row][item];
                                                            break;
                                                        case 14:
                                                            prsnEmail = data[row][item];
                                                            break;
                                                        case 15:
                                                            prsnTelNo = data[row][item];
                                                            break;
                                                        case 16:
                                                            prsnMobile = data[row][item];
                                                            break;
                                                        case 17:
                                                            prsnFax = data[row][item];
                                                            break;
                                                        case 18:
                                                            prsnNatnlTy = data[row][item];
                                                            break;
                                                        case 19:
                                                            prsnImageFile = data[row][item];
                                                            break;
                                                        case 20:
                                                            prsnType = data[row][item];
                                                            break;
                                                        case 21:
                                                            prsnTypeRsn = data[row][item];
                                                            break;
                                                        case 22:
                                                            prsnTypDet = data[row][item];
                                                            break;
                                                        case 23:
                                                            prsnTypStrt = data[row][item];
                                                            break;
                                                        case 24:
                                                            prsnTypEnd = data[row][item];
                                                            break;
                                                        case 25:
                                                            lnkdFirm = data[row][item];
                                                            break;
                                                        case 26:
                                                            lnkdFirmSite = data[row][item];
                                                            break;
                                                        case 27:
                                                            prsnTypeHstry = data[row][item];
                                                            break;
                                                        case 28:
                                                            prsnDivGrpHstry = data[row][item];
                                                            break;
                                                        case 29:
                                                            prsnSpvsrHstry = data[row][item];
                                                            break;
                                                        case 30:
                                                            prsnSiteLocsHstry = data[row][item];
                                                            break;
                                                        case 31:
                                                            prsnJobsHstry = data[row][item];
                                                            break;
                                                        case 32:
                                                            prsnGradesHstry = data[row][item];
                                                            break;
                                                        case 33:
                                                            prsnPostnHstry = data[row][item];
                                                            break;
                                                        case 34:
                                                            prsnEducHstry = data[row][item];
                                                            break;
                                                        case 35:
                                                            prsnWorkHstry = data[row][item];
                                                            break;
                                                        case 36:
                                                            prsnSkillsHstry = data[row][item];
                                                            break;
                                                        case 37:
                                                            prsnIDCardHstry = data[row][item];
                                                            break;
                                                        case 38:
                                                            prsnAdtnlData1 = data[row][item];
                                                            break;
                                                        case 39:
                                                            prsnAdtnlData2 = data[row][item];
                                                            break;
                                                        case 40:
                                                            prsnAdtnlData3 = data[row][item];
                                                            break;
                                                        case 41:
                                                            prsnAdtnlData4 = data[row][item];
                                                            break;
                                                        case 42:
                                                            prsnAdtnlData5 = data[row][item];
                                                            break;
                                                        case 43:
                                                            prsnAdtnlData6 = data[row][item];
                                                            break;
                                                        case 44:
                                                            prsnAdtnlData7 = data[row][item];
                                                            break;
                                                        case 45:
                                                            prsnAdtnlData8 = data[row][item];
                                                            break;
                                                        case 46:
                                                            prsnAdtnlData9 = data[row][item];
                                                            break;
                                                        case 47:
                                                            prsnAdtnlData10 = data[row][item];
                                                            break;
                                                        case 48:
                                                            prsnAdtnlData11 = data[row][item];
                                                            break;
                                                        case 49:
                                                            prsnAdtnlData12 = data[row][item];
                                                            break;
                                                        case 50:
                                                            prsnAdtnlData13 = data[row][item];
                                                            break;
                                                        case 51:
                                                            prsnAdtnlData14 = data[row][item];
                                                            break;
                                                        case 52:
                                                            prsnAdtnlData15 = data[row][item];
                                                            break;
                                                        case 53:
                                                            prsnAdtnlData16 = data[row][item];
                                                            break;
                                                        case 54:
                                                            prsnAdtnlData17 = data[row][item];
                                                            break;
                                                        case 55:
                                                            prsnAdtnlData18 = data[row][item];
                                                            break;
                                                        case 56:
                                                            prsnAdtnlData19 = data[row][item];
                                                            break;
                                                        case 57:
                                                            prsnAdtnlData20 = data[row][item];
                                                            break;
                                                        case 58:
                                                            prsnAdtnlData21 = data[row][item];
                                                            break;
                                                        case 59:
                                                            prsnAdtnlData22 = data[row][item];
                                                            break;
                                                        case 60:
                                                            prsnAdtnlData23 = data[row][item];
                                                            break;
                                                        case 61:
                                                            prsnAdtnlData24 = data[row][item];
                                                            break;
                                                        case 62:
                                                            prsnAdtnlData25 = data[row][item];
                                                            break;
                                                        case 63:
                                                            prsnAdtnlData26 = data[row][item];
                                                            break;
                                                        case 64:
                                                            prsnAdtnlData27 = data[row][item];
                                                            break;
                                                        case 65:
                                                            prsnAdtnlData28 = data[row][item];
                                                            break;
                                                        case 66:
                                                            prsnAdtnlData29 = data[row][item];
                                                            break;
                                                        case 67:
                                                            prsnAdtnlData30 = data[row][item];
                                                            break;
                                                        case 68:
                                                            prsnAdtnlData31 = data[row][item];
                                                            break;
                                                        case 69:
                                                            prsnAdtnlData32 = data[row][item];
                                                            break;
                                                        case 70:
                                                            prsnAdtnlData33 = data[row][item];
                                                            break;
                                                        case 71:
                                                            prsnAdtnlData34 = data[row][item];
                                                            break;
                                                        case 72:
                                                            prsnAdtnlData35 = data[row][item];
                                                            break;
                                                        case 73:
                                                            prsnAdtnlData36 = data[row][item];
                                                            break;
                                                        case 74:
                                                            prsnAdtnlData37 = data[row][item];
                                                            break;
                                                        case 75:
                                                            prsnAdtnlData38 = data[row][item];
                                                            break;
                                                        case 76:
                                                            prsnAdtnlData39 = data[row][item];
                                                            break;
                                                        case 77:
                                                            prsnAdtnlData40 = data[row][item];
                                                            break;
                                                        case 78:
                                                            prsnAdtnlData41 = data[row][item];
                                                            break;
                                                        case 79:
                                                            prsnAdtnlData42 = data[row][item];
                                                            break;
                                                        case 80:
                                                            prsnAdtnlData43 = data[row][item];
                                                            break;
                                                        case 81:
                                                            prsnAdtnlData44 = data[row][item];
                                                            break;
                                                        case 82:
                                                            prsnAdtnlData45 = data[row][item];
                                                            break;
                                                        case 83:
                                                            prsnAdtnlData46 = data[row][item];
                                                            break;
                                                        case 84:
                                                            prsnAdtnlData47 = data[row][item];
                                                            break;
                                                        case 85:
                                                            prsnAdtnlData48 = data[row][item];
                                                            break;
                                                        case 86:
                                                            prsnAdtnlData49 = data[row][item];
                                                            break;
                                                        case 87:
                                                            prsnAdtnlData50 = data[row][item];
                                                            break;
                                                        default:
                                                            isFileValid = false;
                                                            reader.abort();
                                                            errMsg1 = 'An error occurred reading this file. Invalid Column in File! Remove any Commas in the actual data/fields!';
                                                            var dialog = bootbox.alert({
                                                                title: 'Error-Validating Selected File',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File! Remove any Commas in the actual data/fields!</span>',
                                                                callback: function () {}
                                                            });
                                                    };
                                                }
                                                if (rwCntr === 0) {
                                                    if (locIDNo.toUpperCase() === "ID NO.**".toUpperCase() &&
                                                        prsnFirstNm.toUpperCase() === "FIRST NAME**".toUpperCase() &&
                                                        prsnType.toUpperCase() === "PERSON TYPE**".toUpperCase() &&
                                                        prsnSkillsHstry.toUpperCase() === "SKILLS/NATURE(Languages~Hobbies~Interests~Conduct~Attitude~From Date~To Date|)".toUpperCase()) {
                                                        //alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());
                                                    } else {
                                                        isFileValid = false;
                                                        errMsg1 = 'Invalid File Selected!';
                                                        reader.abort();
                                                        var dialog = bootbox.alert({
                                                            title: 'Error-Import Persons',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {}
                                                        });
                                                    }
                                                }
                                                /*
                                                        && prsnTitle.trim() !== ""*/
                                                if (locIDNo.trim() !== "" && prsnFirstNm.trim() !== "" &&
                                                    prsnType.trim() !== "" &&
                                                    prsnSurname.trim() !== "" &&
                                                    prsnGender.trim() !== "" &&
                                                    prsnMarStatus.trim() !== "" &&
                                                    prsnDOB.trim() !== "" &&
                                                    prsnNatnlTy.trim() !== "" &&
                                                    prsnTypeRsn.trim() !== "") {
                                                    if (isFileValid === true) {
                                                        dataToSend = dataToSend + locIDNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnTitle.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnFirstNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnSurname.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnOthrNames.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnGender.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnMarStatus.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnDOB.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnBirthPlace.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnHomeTown.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnReligion.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnResAddrs.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnPostlAddrs.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnEmail.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnTelNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnMobile.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnFax.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnNatnlTy.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnImageFile.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnTypeRsn.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnTypDet.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnTypStrt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnTypEnd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            lnkdFirm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            lnkdFirmSite.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnTypeHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnDivGrpHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnSpvsrHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnSiteLocsHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnJobsHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnGradesHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnPostnHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnEducHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnWorkHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnSkillsHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnIDCardHstry.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData1.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData2.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData3.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData4.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData5.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData6.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData7.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData8.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData9.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData10.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData11.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData12.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData13.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData14.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData15.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData16.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData17.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData18.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData19.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData20.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData21.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData22.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData23.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData24.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData25.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData26.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData27.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData28.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData29.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData30.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData31.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData32.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData33.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData34.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData35.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData36.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData37.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData38.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData39.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData40.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData41.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData42.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData43.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData44.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData45.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData46.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData47.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData48.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData49.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            prsnAdtnlData50.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                        vldRwCntr++;
                                                    } else {
                                                        //alert('isFileValid22:'+isFileValid);
                                                        break;
                                                    }
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output += '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' + vldRwCntr;
                                            if (rhotrim(errMsg1, '; ') !== '') {
                                                output += '<br/><span style="color:red;font-weight:bold;">Error at Row:' + (rwCntr + 1) + ' Message:' + errMsg1;
                                            } else {
                                                output += '<br/>Total No. of Rows:' + rwCntr + '</span>';
                                            }
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            isFileValid = false;
                                            errMsg1 = err.message;
                                            reader.abort();
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import Persons',
                                                size: 'small',
                                                message: 'Error:' + err.message,
                                                callback: function () {}
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                }, 500);
                            },
                            buttons: [{
                                label: 'Cancel',
                                icon: 'glyphicon glyphicon-menu-left',
                                cssClass: 'btn-default',
                                action: function (dialogItself) {
                                    isFileValid = false;
                                    reader.abort();
                                    dialogItself.close();
                                }
                            }, {
                                id: 'btn-srvr-prcs',
                                label: 'Start Server Processing',
                                icon: 'glyphicon glyphicon-menu-right',
                                cssClass: 'btn-primary',
                                action: function (dialogItself) {
                                    if (isFileValid === true) {
                                        dialogItself.close();
                                        savePersonsExcl(dataToSend);
                                    } else {
                                        errMsg1 = 'Invalid File Selected!';
                                        var dialog = bootbox.alert({
                                            title: 'Error-Import Persons',
                                            size: 'small',
                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                            callback: function () {}
                                        });
                                    }
                                }
                            }]
                        });
                    });
                    performFileClick('allOtherFileInput6');
                }
            }
        }
    });
}

function savePersonsExcl(dataToSend) {
    if (dataToSend.trim() === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">No Data to Send!</span></p>'
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Importing Persons',
        size: 'small',
        message: '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Persons...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            getDataAdmin('clear', '#allmodules', 'grp=8&typ=1&pg=5&vtyp=0');
            ClearAllIntervals();
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
                    grp: 8,
                    typ: 1,
                    pg: 5,
                    q: 'UPDATE',
                    actyp: 102,
                    dataToSend: dataToSend
                }
            });
            prgstimerid2 = window.setInterval(rfrshPersonsImprt, 1000);
        });
    });
}

function rfrshPersonsImprt() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 8,
            typ: 1,
            pg: 5,
            q: 'UPDATE',
            actyp: 103
        },
        success: function (data) {
            var elem = document.getElementById('myBar1');
            elem.style.width = data.percent + '%';
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}