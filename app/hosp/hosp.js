function prepareHosp(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=2&vtyp=1") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=2&vtyp=2") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=2&vtyp=4") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=3") !== -1)
        {
            /*$(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="tabajxrptdet"]').click(function (e) {
                    alert("Hello");
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    //var linkArgs = 'grp=14&typ=1' + dttrgt;
                    var linkArgs = dttrgt;
                    $(targ + 'tab').tab('show');
                    if (targ.indexOf('prfBCOPAddPrsnDataEDT') >= 0) {
                        openATab(targ, linkArgs);
                    }
                });
            });*/
                        
            if (!$.fn.DataTable.isDataTable('#allRcmddSrvsMainsTable')) {
                table2 = $('#allRcmddSrvsMainsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allRcmddSrvsMainsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRcmddSrvsMainsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allRcmddSrvsMainsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainID').val() === 'undefined' ? -1 : $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainID').val();
                var appntmntID = typeof $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainApptmntID').val() === 'undefined' ? -1 : $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainApptmntID').val();
                var srvsTypeSysCode = typeof $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainSysCode').val() === 'undefined' ? '' : $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainSysCode').val();
                openATab('#allRcmddSrvsMainsHdrInfo','grp=14&typ=1&pg=102&mdl=Clinic/Hospital&q=ADTNL-DATA-FORM&vtyp=1&appntmntID='+appntmntID+'&formType='+srvsTypeSysCode+'&vtypActn=EDIT&srcRcmddSrvsID='+pKeyID);
            });
            $('#allRcmddSrvsMainsTable tbody') .on('mouseenter', 'tr', function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    table2.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
                }
            });
        } else if (lnkArgs.indexOf("&pg=5") !== -1)
        {
            var table2 = null;
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
            if (!$.fn.DataTable.isDataTable('#allPrvdrGroupsTable')) {
                table2 = $('#allPrvdrGroupsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allPrvdrGroupsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allPrvdrGroupsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allPrvdrGroupPersonsTable')) {
                var table3 = $('#allPrvdrGroupPersonsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allPrvdrGroupPersonsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allPrvdrGroupsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allPrvdrGroupsRow' + rndmNum + '_PrvdrGroupID').val() === 'undefined' ? '%' : $('#allPrvdrGroupsRow' + rndmNum + '_PrvdrGroupID').val();
                getOnePrvdrGroupDetail(pKeyID, 1);
            });
            $('#allPrvdrGroupsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });

            
            $('#allOtherInputData99').val(0);
        } 
        htBody.removeClass("mdlloading");
    });
}

function getHospData(actionText, slctr, linkArgs, fieldsPrfx)
{
    var srchFor;
    var srchIn;
    var dflt1;
    if (typeof fieldsPrfx === 'undefined' || fieldsPrfx === null)
    {
        fieldsPrfx = 'dataAdmin';
    }
    if ($("#addOrEditForm").val() === 'Add') {
        $("#" + fieldsPrfx + "SrchFor").val($("#idNo").val());
        var srchFor = $("#" + fieldsPrfx + "SrchFor").val();
        $("#" + fieldsPrfx + "SrchIn").val("ID");
        var srchIn = $("#" + fieldsPrfx + "SrchIn").val();
    } else {
        srchFor = typeof $("#" + fieldsPrfx + "SrchFor").val() === 'undefined' ? '%' : $("#" + fieldsPrfx + "SrchFor").val();
        srchIn = typeof $("#" + fieldsPrfx + "SrchIn").val() === 'undefined' ? 'Both' : $("#" + fieldsPrfx + "SrchIn").val();
    }

    var pageNo = typeof $("#" + fieldsPrfx + "PageNo").val() === 'undefined' ? 1 : $("#" + fieldsPrfx + "PageNo").val();
    var limitSze = typeof $("#" + fieldsPrfx + "DsplySze").val() === 'undefined' ? 10 : $("#" + fieldsPrfx + "DsplySze").val();
    var sortBy = typeof $("#" + fieldsPrfx + "SortBy").val() === 'undefined' ? '' : $("#" + fieldsPrfx + "SortBy").val();
    var otherPrsnType = typeof $("#" + fieldsPrfx + "OtherPrsnType").val() === 'undefined' ? 'All' : $("#" + fieldsPrfx + "OtherPrsnType").val();
    var trnsType = typeof $("#trnsType").val() === 'undefined' ? '' : $("#trnsType").val();
    var isEnabled = $('#' + fieldsPrfx + 'IsEnabled:checked').length > 0;
    var branchSrchIn = typeof $('#' + fieldsPrfx + "BranchSrchIn").val() === 'undefined' ? 'All Branches' : $('#' + fieldsPrfx + "BranchSrchIn").val(); 
    var statusSrchIn = typeof $('#' + fieldsPrfx + "StatusSrchIn").val() === 'undefined' ? 'All Statuses' : $('#' + fieldsPrfx + "StatusSrchIn").val();    
    var prdtTypeSrchIn = typeof $('#' + fieldsPrfx + "PrdtTypeSrchIn").val() === 'undefined' ? 'Savings' : $('#' + fieldsPrfx + "PrdtTypeSrchIn").val();
    var crdtTypeSrchIn = typeof $('#' + fieldsPrfx + "CrdtTypeSrchIn").val() === 'undefined' ? 'All Credit Types' : $('#' + fieldsPrfx + "CrdtTypeSrchIn").val();
    var rqstStatusSrchIn = typeof $('#' + fieldsPrfx + "RqstStatusSrchIn").val() === 'undefined' ? 'All Statuses' : $('#' + fieldsPrfx + "RqstStatusSrchIn").val(); 
    var bnkPrdtTypeSrchInID = typeof $('#' + fieldsPrfx + "BnkPrdtTypeSrchInID").val() === 'undefined' ? -1 : $('#' + fieldsPrfx + "BnkPrdtTypeSrchInID").val(); 
    
   
    var qStrtDte = typeof $('#' + fieldsPrfx + "StrtDate").val() === 'undefined' ? '' :  $('#' + fieldsPrfx + "StrtDate").val();
    var qEndDte = typeof $('#' + fieldsPrfx + "EndDate").val() === 'undefined' ? '' :  $('#' + fieldsPrfx + "EndDate").val();

    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&prsnType=" + otherPrsnType + "&trnsType=" + trnsType + 
                "&isEnabled=" + isEnabled + "&branchSrchIn=" + branchSrchIn + "&statusSrchIn=" + statusSrchIn + "&prdtTypeSrchIn=" + prdtTypeSrchIn + "&crdtTypeSrchIn="+crdtTypeSrchIn
        +"&qStrtDte="+qStrtDte + "&qEndDte="+qEndDte + "&rqstStatusSrchIn="+rqstStatusSrchIn + "&bnkPrdtTypeSrchInID="+bnkPrdtTypeSrchInID;
    $body = $('body');
    openATab(slctr, linkArgs);
}

function enterKeyFuncCust(e, actionText, slctr, linkArgs, fieldsPrfx)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getHospData(actionText, slctr, linkArgs, fieldsPrfx);
    }
}

function getHospDetailsForm(elementID, modalBodyID, titleElementID, formTitle, pgNo, vtyp, vtypActn, pKeyID, listTableID, rowID, srcPgNo, callBackFunc)
{
    
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }  
    
    if (typeof srcPgNo === 'undefined' || srcPgNo === null)
    {
        srcPgNo = 2;
    }

    if (!$.fn.DataTable.isDataTable('#' + listTableID)) {
        var table1 = $('#' + listTableID).DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#' + listTableID + ' tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        $('#' + listTableID + ' tbody').on('mouseenter', 'tr', function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
    }

    var p_statusTitle = "Incomplete";
    if (vtypActn == "EDIT") {

    }


    $body = $("body");
    $body.addClass("mdlloadingDiag");
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        /*code for IE7+, Firefox, Chrome, Opera, Safari*/
        xmlhttp = new XMLHttpRequest();
    } else
    {
        /*code for IE6, IE5*/
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            $('#' + titleElementID).html(formTitle);
            $('#' + modalBodyID).html(xmlhttp.responseText);
            /*$('.modal-content').resizable({
             //alsoResize: ".modal-dialog",
             minHeight: 600,
             minWidth: 300
             });*/
            /*$('.modal-dialog').draggable();*/
           
            
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
            
            
            $('#' + elementID).on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            
            
            $('#'+elementID).off('hidden.bs.modal');
            $('#'+elementID).one('hidden.bs.modal', function (e) {
                $('#' + modalBodyID).html('');
                localStorage.setItem('activeTab', '');
                localStorage.setItem('activeTabRhoData', ''); 
                if(pgNo == srcPgNo) {
                    getHospData('', '#allmodules', 'grp=14&typ=1&pg='+pgNo+'&mdl=Clinic/Hospital');
                } else if(srcPgNo == 2){
                    //var vstId = $('#vstID').val(); //UNCOMMENT THIS AND NEXT LINE TO RELOAD VISIT FORM
                    //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit', 2,  1, 'EDIT', vstId, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1');
                }
                //var fuVal = $("#frmUpdate").val();
                                
                $(e.currentTarget).unbind();
            });
            $body.removeClass("mdlloadingDiag");
            //$('#' + elementID).modal({backdrop: 'static',keyboard:false});
            $('#' + elementID).modal({backdrop: 'static',keyboard:false});
            $body = $("body");
            
             $('#allOtherInputData99').val('0');
            if (!$.fn.DataTable.isDataTable('#rptsStpPrmsTable')) {
                var table1 = $('#rptsStpPrmsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#rptsStpPrmsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#diagnosisTable')) {
                var table2 = $('#diagnosisTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#diagnosisTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#invstgtnTable')) {
                var table2 = $('#invstgtnTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#invstgtnTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#medicalForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            /*$('#myFormsModalLg').off('hidden.bs.modal');
            $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
                if (srcCaller === 'NORMAL') {
                    getAllRpts('Clear', '#allmodules', 'grp=9&typ=1&pg=1&vtyp=0');
                } else if (srcCaller === 'ALL_RUNS') {
                    getAllPrcsRuns('', '#allmodules', 'grp=9&typ=1&pg=5&vtyp=0');
                }
                $(e.currentTarget).unbind();
            });*/
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="tabajxrptdet"]').off('click');
            $('[data-toggle="tabajxrptdet"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = dttrgt;
                if (targ.indexOf('cnsltnMainTbPage') >= 0) {
                    $('#cnsltnMainTbPage').removeClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('vitalsTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').removeClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('inHouseAdmsnTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').removeClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('medicationTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').removeClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('invstgtnTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').removeClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('rcmddSrvsTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').removeClass('hideNotice');
                    var rcSvId = typeof $("#rcmdSrvsMainForm").val() === 'undefined' ? -1 : $("#rcmdSrvsMainForm").val();
    
                    if(rcSvId <= 0){
                        openATab(targ, linkArgs);
                    } 
                } else if (targ.indexOf('prfBCOPAddPrsnDataEDT') >= 0) {
                    var srcTyp = typeof $("#formTypeInpt").val() === 'undefined' ? '' : $("#formTypeInpt").val();
    
                    if(srcTyp === ''){
                        openATab(targ, linkArgs);
                    } 
                    //openATab(targ, linkArgs);
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                    $('#prfBCOPAddPrsnDataEDTPage').removeClass('hideNotice');
                }
                //return;
            });
            
            $('#allOtherInputData99').val(0);
            
            $(document).ready(function () {
                /*$('#' + formElementID).submit(function (e) {
                 e.preventDefault();
                 return false;
                 });*/

                callBackFunc();
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=14&typ=1&pg=" + pgNo + "&mdl=Clinic/Hospital&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID+"&srcPgNo"+srcPgNo);
}

function getTblRowsCount(tblElementID) {
    var rowCount = 0;
    $('#' + tblElementID).find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {

            } else {
                rowCount = rowCount + 1;
            }
        }
    });
    return rowCount;
}

function onHospCustomerChange() {
    var rowCount = getTblRowsCount("visitAppointmentTblAdd");
    if (rowCount <= 0) {
        getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Hospital Patients', 'gnrlOrgID', '', '', 'radio', true, '', 'prsnId', 'prsnNm', 'clear', 1, '');
    } else {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red; font-weight:bold !important;'>To change Customer, delete all appointment records first!</span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }
}

function viewAppointmentLinesForm(appntmntID, formTitle, vtypActn, lnkdSrvsTypeCode, lnkdSrvsTypeVstID)
{
    var vstID = -1;
    
    if (typeof lnkdSrvsTypeCode === 'undefined' || lnkdSrvsTypeCode === null)
    {
        lnkdSrvsTypeCode = '';
    }
    
    if (typeof lnkdSrvsTypeVstID === 'undefined' || lnkdSrvsTypeCode === null)
    {
        vstID = typeof $("#vstId").val() === 'undefined' ? -1 : $("#vstId").val();
    } else {
        vstID = lnkdSrvsTypeVstID;
    }

    if (vstID == "" || vstID == -1) {
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;font-weight:bold !important;'>Please save Header First!</span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    $body = $("body");
    $body.addClass("mdlloadingDiag");
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        /*code for IE7+, Firefox, Chrome, Opera, Safari*/
        xmlhttp = new XMLHttpRequest();
    } else
    {
        /*code for IE6, IE5*/
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            $('#myFormsModalyTitle').html(formTitle);
            $('#myFormsModalyBody').html(xmlhttp.responseText);
            /*$('.modal-dialog').draggable();*/
            $(function () {
                $('.form_date').datetimepicker({
                    format: "d-M-yyyy ",
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
            });
            
            $('#myFormsModaly').on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            $body.removeClass("mdlloadingDiag");
            $('#myFormsModaly').modal({backdrop: 'static', keyboard: false});
            
            $(document).ready(function () {
                $('#appointmentDetForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=14&typ=1&pg=2&mdl=Clinic/Hospital&vtyp=2&appntmntID=" + appntmntID + "&PKeyID=" + vstID + "&vtypActn=" + vtypActn+"&lnkdSrvsTypeCode="+lnkdSrvsTypeCode);
}

function viewAppointmentLinesFormLnkdAppntmnts(appntmntID, formTitle, vtypActn, lnkdSrvsTypeCode, lnkdSrvsTypeVstID, lnkdCnsltnID, callBackFunc)
{
    var vstID = -1;
    
    if (typeof lnkdSrvsTypeCode === 'undefined' || lnkdSrvsTypeCode === null)
    {
        lnkdSrvsTypeCode = '';
    }
    
    if (typeof lnkdSrvsTypeVstID === 'undefined' || lnkdSrvsTypeCode === null)
    {
        vstID = typeof $("#vstId").val() === 'undefined' ? -1 : $("#vstId").val();
    } else {
        vstID = lnkdSrvsTypeVstID;
    }

    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () { };
    }
    //alert(vstID);

    if (vstID == "" || vstID == -1) {
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;font-weight:bold !important;'>Please save Header First!</span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    $body = $("body");
    $body.addClass("mdlloadingDiag");
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        /*code for IE7+, Firefox, Chrome, Opera, Safari*/
        xmlhttp = new XMLHttpRequest();
    } else
    {
        /*code for IE6, IE5*/
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            $('#myFormsModalyHTitle').html(formTitle);
            $('#myFormsModalyHBody').html(xmlhttp.responseText);
            /*$('.modal-dialog').draggable();*/
            $(function () {
                $('.form_date').datetimepicker({
                    format: "d-M-yyyy ",
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
            });
            
            $('#myFormsModalyH').on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            
            if (typeof callBackFunc === 'undefined' || callBackFunc === null)
            {
                //callBackFunc = function () { };
            } else {
                callBackFunc();
            }
            
            $body.removeClass("mdlloadingDiag");
            $('#myFormsModalyH').modal({backdrop: 'static', keyboard: false});
            
            $(document).ready(function () {
                $('#appointmentDetForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=14&typ=1&pg=2&mdl=Clinic/Hospital&vtyp=2&appntmntID=" + appntmntID + "&vstID=" + vstID + "&vtypActn=" + vtypActn
            +"&lnkdSrvsTypeCode="+lnkdSrvsTypeCode+"&lnkdCnsltnID="+lnkdCnsltnID);
}

function getServiceProvider() {
//alert('Him');
    var frmPrvdrType = $('#frmPrvdrType').val();
    
    //alert(frmPrvdrType);

    if (frmPrvdrType === "" || frmPrvdrType === undefined) {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "Please select Provider Type First!",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var lovName = "";
    var extraWhere = " AND 1 = 1";
    switch (frmPrvdrType) {
        case 'G':
            lovName = "Service Provider Groups";
            break;
        case 'I':
            lovName = "Service Providers";
            break;
    }

    getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', lovName, 'frmSrvsTypeId', '', '', 'radio', true, '', 'frmSrvsPrvdrId', 'frmSrvsPrvdr', 'clear', 1, extraWhere);
}

function resetFormOnPrvdrTypeChange() {
    //var frmSrvsType = $("#frmSrvsType").val();
    var frmPrvdrType = $("#frmPrvdrType option:selected").val();
    if (frmPrvdrType === "I" || frmPrvdrType === "G") {
        //$('#custGrpDiv').css('display', 'block');
        $('#frmSrvsPrvdrId').val(-1);
        $('#frmSrvsPrvdr').val('');
    }
}

//Appointment Data Items
function getOneAppntmntDataItemsForm(pKeyID, vwtype, actionTxt, trnsNo, appntmntStatus)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }

    var lnkArgs = 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital&vtyp=' + vwtype + '&sbmtdAppntmntDataItemsAppntmntID=' + pKeyID + '&sbmtdTrsNo=' + trnsNo + '&sbmtdAppntmntStatus=' + appntmntStatus;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalyH', actionTxt, 'Appointment Items for '+trnsNo, 'myFormsModalyHTitle', 'myFormsModalyHBody', function () {
        $('#allOtherInputData99').val('0');
        $('#allAppntmntDataItemsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalyH').off('hidden.bs.modal');
        $('#myFormsModalyH').one('hidden.bs.modal', function (e) {
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#allAppntmntDataItemsTable')) {
            var table1 = $('#allAppntmntDataItemsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allAppntmntDataItemsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();



    });
}

function saveAppntmntDataItems(trnsNo, appntmntStatus)
{
    var appntmntID = $('#sbmtdAppntmntDataItemsAppntmntID').val();
    var dsplyMsg = "";
    var slctdAppntmntDataItems = "";

    var errCount = 0;
    var rcdCount = 0;
    var lineCnta = 1;

    $('#allAppntmntDataItemsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allAppntmntDataItemsRow' + rndmNum + '_ItemID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {

                    if ($('#allAppntmntDataItemsRow' + rndmNum + '_ItemID').val() == "" || $('#allAppntmntDataItemsRow' + rndmNum + '_ItemID').val() == "-1") {
                        $('#allAppntmntDataItemsRow' + rndmNum + '_ItemDesc').css('border-color', 'red');
                        $('#allAppntmntDataItemsRow' + rndmNum + '_ItemDesc').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allAppntmntDataItemsRow' + rndmNum + '_ItemDesc').css('border-color', '#ccc');
                        $('#allAppntmntDataItemsRow' + rndmNum + '_ItemDesc').css('border-width', '1px');
                    }
                    if ($('#allAppntmntDataItemsRow' + rndmNum + '_Qty').val() == "" || $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').val() == "-1") {
                        $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').css('border-color', 'red');
                        $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').css('border-color', '#ccc');
                        $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').css('border-width', '1px');
                    }
                    if (errCount <= 0) {
                        slctdAppntmntDataItems = slctdAppntmntDataItems
                                + $('#allAppntmntDataItemsRow' + rndmNum + '_AppntmntDataItemsID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#sbmtdAppntmntDataItemsAppntmntID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allAppntmntDataItemsRow' + rndmNum + '_ItemID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allAppntmntDataItemsRow' + rndmNum + '_Cmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allAppntmntDataItemsRow' + rndmNum + '_UomID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        rcdCount = rcdCount + 1;
                    }
                    lineCnta = lineCnta + 1;
                }
            }
        }
    });

    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Payment Plan Setup record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dsplyMsg = "Saving Appointment Item(s)...Please Wait...";
    var dsplyMsgTtle = "Save Appointment Item(s)?";
    var dsplyMsgRtrn = "Appointment Item(s) Saved";

    var dialog = bootbox.alert({
        title: dsplyMsgTtle,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> ' + dsplyMsg + '</p>',
        callback: function () {
            var recCnt = typeof $("#recCnt").val() === 'undefined' ? 0 : $("#recCnt").val();

            if (parseInt(recCnt) > 0) {
                getOneAppntmntDataItemsForm(appntmntID, 3, 'ReloadDialog', trnsNo, appntmntStatus);
            }
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
                    grp: 14,
                    typ: 1,
                    pg: 2,
                    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 3,
                    slctdAppntmntDataItems: slctdAppntmntDataItems
                },
                success: function (result) {
                    var data = result;
                    setTimeout(function () {
                        if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                            var obj = $.parseJSON(data);
                            $("#recCnt").val(parseInt(obj.recCntInst) + parseInt(obj.recCntUpdt));
                            var msg = "<span style='color:green;font-weight:bold !important;'>" + dsplyMsgRtrn + "</br><i>" + obj.recCntInst + " record(s) inserted</br>"
                                    + obj.recCntUpdt + " Setup record(s) updated</i></span>"
                            dialog.find('.bootbox-body').html(msg);
                        } else {
                            dialog.find('.bootbox-body').html(data);
                        }

                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function deleteAppntmntDataItems(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var ItemID = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_AppntmntDataItemsID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_AppntmntDataItemsID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        ItemID = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Row?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Row?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Row?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Row...Please Wait...</p>',
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 2,
                                    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 3,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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


function saveCreditItem()
{

    var box;
    var box2;

    $("#svCreditItm").attr('disabled', 'disabled');
    $("#svCreditItm").removeAttr('disabled');
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {

        var obj;
        var creditItmId = typeof $("#frmCreditItmId").val() === 'undefined' ? -1 : $("#frmCreditItmId").val();
        var cnsmrCreditId = typeof $("#cnsmrCreditID").val() === 'undefined' ? -1 : $("#cnsmrCreditID").val();
        ;
        var noOfPymnt = typeof $("#noOfPymnts").val() === 'undefined' ? 1 : $("#noOfPymnts").val();
        var itemId = typeof $("#frmItemId").val() === 'undefined' ? -1 : $("#frmItemId").val();
        var vendorId = typeof $("#frmVendorId").val() === 'undefined' ? -1 : $("#frmVendorId").val();
        var itmPymntPlanId = typeof $("#frmItmPymntPlanId").val() === 'undefined' ? -1 : $("#frmItmPymntPlanId").val();
        var qty = typeof $("#frmQty").val() === 'undefined' ? 1 : $("#frmQty").val();
        var unitSellingPrice = typeof $("#frmUnitSellingPrice").val() === 'undefined' ? 0.00 : $("#frmUnitSellingPrice").val();
        var itmPlanInitDeposit = typeof $("#frmItmPlanInitDeposit").val() === 'undefined' ? 0.00 : $("#frmItmPlanInitDeposit").val();

        if (itemId == -1) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Select Product</span>",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } else if (vendorId == -1) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Select Vendor</span>",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } else if (itmPymntPlanId == -1) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Select Payment Plan</span>",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } else if (qty == "" || qty == 0) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter purchase quantity</span>",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } else if (itmPlanInitDeposit == "" || itmPlanInitDeposit < 0) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Initial Deposit or Zero(0)</span>",
                callback: function () { /* your callback code */
                }
            });
            return false;
        }


        $body.removeClass("mdlloading");
        $body.removeClass("mdlloadingDiag");
        box = bootbox.dialog({size: "small",
            message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i><span style="font-weight:bold; color:green;"> Saving. Please Wait...</span></div>'});
        box.find('.modal-content').css({
            'margin-top': function () {
                var w = $(window).height();
                var b = $(".modal-dialog").height();
                // should not be (w-h)/2
                var h = w / 2; //(w - b) / 2;
                return h + "px";
            }
        });
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('q', 'UPDATE');
        formData.append('pg', 15);
        formData.append('actyp', 2);
        formData.append('creditItmId', creditItmId);
        formData.append('cnsmrCreditId', cnsmrCreditId);
        formData.append('noOfPymnt', noOfPymnt);
        formData.append('itemId', itemId);
        formData.append('vendorId', vendorId);
        formData.append('itmPymntPlanId', itmPymntPlanId);
        formData.append('qty', qty);
        formData.append('itmPlanInitDeposit', itmPlanInitDeposit);
        formData.append('unitSellingPrice', unitSellingPrice);

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                var msg = "";
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                        replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                        replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                    obj = $.parseJSON(data);
                    box.modal('hide');
                    $('#myFormsModaly').modal('hide');
                    getOneCnsmrCrdtAnalysisForm(cnsmrCreditId, 1, 'ReloadDialog', function () {
                        msg = "Saved Successfully!";
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: msg,
                            callback: function () { /* your callback code */
                            }
                        });
                    });

                } else {

                    msg = data;
                    box.modal('hide');
                    box2 = bootbox.alert({
                        size: "small",
                        title: "Rhomicom Message",
                        message: msg,
                        callback: function () { /* your callback code */
                        }
                    });
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}

//Appointment Data Items
function getDosageForm(vwtype, rowIDAttrb)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    
    var drugDosage = $('#' + rowIDAttrb).val();
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    //alert(rowPrfxNm);
    var doseQty = $('#' + rowPrfxNm +"_DoseQty").val();
    var doseQtyUOM = $('#' + rowPrfxNm +"_DoseQtyUOM").val();
    var frqncyNo = $('#' + rowPrfxNm +"_FrqncyNo").val();
    var frqncyUOM = $('#' + rowPrfxNm +"_FrqncyUOM").val();
    var drtnNo = $('#' + rowPrfxNm +"_DrtnNo").val();
    var drtnUOM = $('#' + rowPrfxNm +"_DrtnUOM").val();
        
    //alert('drugDosage '+drugDosage);

    var lnkArgs = 'grp=14&typ=1&pg=3&mdl=Clinic/Hospital&vtyp=' + vwtype +"&doseQty="+doseQty+"&doseQtyUOM="+doseQtyUOM
            +"&frqncyNo="+frqncyNo+"&frqncyUOM="+frqncyUOM+"&drtnNo="+drtnNo+"&drtnUOM="+drtnUOM+"&rowPrfxNm="+rowPrfxNm;
    doAjaxWthCallBck(lnkArgs, 'myLovModal', actionTxt, 'Dosage Form', 'myLovModalTitle', 'myLovModalBody', function () {
        $('#allOtherInputData99').val('0');
        $('#dosageForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        //$('#myLovModal .modal-content').css('width','80%');
        $('#myLovModal').off('hidden.bs.modal');
        $('#myLovModal').one('hidden.bs.modal', function (e) {
            //alert('Help');
            $(e.currentTarget).unbind();
            //$('.modal-content').css('width','100%');
        });
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function sendDosage(rowID){
    

    var rowPrfxNm = rowID;
   /* var doseQty = $('#' + rowPrfxNm +"_DoseQty").val();
    var doseQtyUOM = $('#' + rowPrfxNm +"_DoseQtyUOM").val();
    var frqncyNo = $('#' + rowPrfxNm +"_FrqncyNo").val();
    var frqncyUOM = $('#' + rowPrfxNm +"_FrqncyUOM").val();
    var drtnNo = $('#' + rowPrfxNm +"_DrtnNo").val();
    var drtnUOM = $('#' + rowPrfxNm +"_DrtnUOM").val();*/
    
    var doseQty = typeof $("#doseQty").val() === 'undefined' ? '' : $("#doseQty").val();
    var doseQtyUOM = typeof $("#doseQtyUOM").val() === 'undefined' ? '' : $("#doseQtyUOM").val();
    var frqncyNo = typeof $("#frqncyNo").val() === 'undefined' ? '' : $("#frqncyNo").val();
    var frqncyUOM = typeof $("#frqncyUOM").val() === 'undefined' ? '' : $("#frqncyUOM").val();
    var drtnNo = typeof $("#drtnNo").val() === 'undefined' ? '' : $("#drtnNo").val();
    var drtnUOM = typeof $("#drtnUOM").val() === 'undefined' ? '' : $("#drtnUOM").val();
    
    //Pupulate Dosage Field
    $("#"+rowPrfxNm+"_DoseQty").val(doseQty);
    $("#"+rowPrfxNm+"_DoseQtyUOM").val(doseQtyUOM);
    $("#"+rowPrfxNm+"_FrqncyNo").val(frqncyNo);
    $("#"+rowPrfxNm+"_FrqncyUOM").val(frqncyUOM);
    $("#"+rowPrfxNm+"_DrtnNo").val(drtnNo);
    $("#"+rowPrfxNm+"_DrtnUOM").val(drtnUOM);
    
    $("#"+rowPrfxNm+"_Instruction").val(doseQty+" "+doseQtyUOM+" "+frqncyNo+" times a "+frqncyUOM+" for "+drtnNo+" "+drtnUOM);
    
    //Clear Modal Form
    $("#doseQty").val('');
    $("#doseQtyUOM").val('');
    $("#frqncyNo").val('');
    $("#frqncyUOM").val('');
    $("#drtnNo").val('');
    $("#drtnUOM").val('');
    
    $('#myLovModal').modal('hide'); 
    
}

function delDiagnosis(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var diseaseNm = "";
    if (typeof $('#diagnosisRow' + rndmNum + '_DiagID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> diagnosisRow1_RoleID*/
    } else {
        pKeyID = $('#diagnosisRow' + rndmNum + '_DiagID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        diseaseNm = $.trim($tds.eq(1).text());
    }
    //alert(pKeyID);
    var dialog = bootbox.confirm({
        title: 'Delete Diagnosis?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Diagnosis?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Role?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Diagnosis...Please Wait...</p>',
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
                                    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 2, 
                                    sctn: 1,
                                    diagID: pKeyID,
                                    diseaseNm: diseaseNm
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function delInvestigation(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var invstgtnNm = "";
    if (typeof $('#invstgtnRow' + rndmNum + '_PkeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> rptsStpRolesRow1_RoleID*/
    } else {
        pKeyID = $('#invstgtnRow' + rndmNum + '_PkeyID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        invstgtnNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Sub-Program?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Investigation?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Sub-Program?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Sub-Program...Please Wait...</p>',
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
                                    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 3, //5
                                    pKeyID: pKeyID,
                                    invstgtnNm: invstgtnNm
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function getOneScmUOMBrkdwnForm(pKeyID, vwtype, rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var sbmtdTblRowID = 'oneINVQtyBrkDwnTable';
    var sbmtdItemID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
    var varTtlQtyStr = $('#' + rowPrfxNm + rndmNum + '_QTY').val();
    var varTtlQty = Number(varTtlQtyStr.replace(/[^-?0-9\.]/g, ''));
    var sbmtdCrncyNm = typeof $("#scmSalesInvcInvcCur1").text() === 'undefined' ? '' : $("#scmSalesInvcInvcCur1").text();
    if (rowIDAttrb.indexOf("CnsgnRcpt") !== -1)
    {
        sbmtdCrncyNm = typeof $("#scmCnsgnRcptInvcCur1").text() === 'undefined' ? '' : $("#scmCnsgnRcptInvcCur1").text();
    } else if (rowIDAttrb.indexOf("StockTrnsfr") !== -1)
    {
        sbmtdCrncyNm = typeof $("#scmStockTrnsfrInvcCur1").text() === 'undefined' ? '' : $("#scmStockTrnsfrInvcCur1").text();
    }
    var lnkArgs = 'grp=12&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdScmTrnsHdrID=' + pKeyID +
            "&sbmtdItemID=" + sbmtdItemID + "&varTtlQty=" + varTtlQty +
            "&sbmtdRwNum=" + rndmNum + "&sbmtdCrncyNm=" + sbmtdCrncyNm +
            "&sbmtdTblRowID=" + sbmtdTblRowID + "&rowIDAttrb=" + rowIDAttrb;

    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'Item QTY UOM Breakdown', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        var table1 = $('#oneINVQtyBrkDwnTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneINVQtyBrkDwnTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#oneSalesTrnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(".invUmbTtl").focus(function () {
            $(this).select();
        });
        $(".invUmbQty").focus(function () {
            $(this).select();
        });
    });
}

//SERVICE TYPES
function saveSrvcOffrd()
{
    var slctdSrvcOffrds = "";
    var errCount = 0;
    var rcdCount = 0;
    $('#allSrvcOffrdsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdNm').val() == "") {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdNm').css('border-color', 'red');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdNm').css('border-color', '#ccc');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdNm').css('border-width', '1px');
                    }
                    if ($('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdDesc').val() == "") {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdDesc').css('border-color', 'red');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdDesc').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdDesc').css('border-color', '#ccc');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdDesc').css('border-width', '1px');
                    }
					/*if ($('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdItm').val() == "") {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdItm').css('border-color', 'red');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdItm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdItm').css('border-color', '#ccc');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdItm').css('border-width', '1px');
                    }*/
					if ($('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdSys').val() == "") {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdSys').css('border-color', 'red');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdSys').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdSys').css('border-color', '#ccc');
                        $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdSys').css('border-width', '1px');
                    }
                    
                    var teleEnabled = typeof $("input[name='allSrvcOffrdsRow" + rndmNum + "_TelemedicineEnabled']:checked").val() === 'undefined' ? 'NO' : $("input[name='allSrvcOffrdsRow" + rndmNum + "_TelemedicineEnabled']:checked").val();
                    
                    if (errCount <= 0) {
                        slctdSrvcOffrds = slctdSrvcOffrds
                                + $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdSys').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdIsEnabled').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + teleEnabled + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Service Type record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Service Type record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Service Types?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Service Types...Please Wait...</p>',
        callback: function () {
            getAllSrvcOffrds('', "#allmodules", "grp=14&typ=1&pg=4&vtyp=0&mdl=Clinic/Hospital");
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
                    grp: 14,
                    typ: 1,
                    pg: 4,
					mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 1,
                    slctdSrvcOffrds: slctdSrvcOffrds
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delSrvcOffrd(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var RiskFactorID = '';
    if (typeof $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allSrvcOffrdsRow' + rndmNum + '_SrvcOffrdID').val();
        //alert('pKeyID'+pKeyID);
    }
    var dialog = bootbox.confirm({
        title: 'Remove Service?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Service Type?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Service Type?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Service Type...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=6&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 4,
									mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 1,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function getAllSrvcOffrds(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allSrvcOffrdsSrchFor").val() === 'undefined' ? '%' : $("#allSrvcOffrdsSrchFor").val();
    var srchIn = typeof $("#allSrvcOffrdsSrchIn").val() === 'undefined' ? 'Both' : $("#allSrvcOffrdsSrchIn").val();
    var pageNo = typeof $("#allSrvcOffrdsPageNo").val() === 'undefined' ? 1 : $("#allSrvcOffrdsPageNo").val();
    var limitSze = typeof $("#allSrvcOffrdsDsplySze").val() === 'undefined' ? 15 : $("#allSrvcOffrdsDsplySze").val();
    var sortBy = typeof $("#allSrvcOffrdsSortBy").val() === 'undefined' ? '' : $("#allSrvcOffrdsSortBy").val();
    var isEnabled = $('#allSrvcOffrdsIsEnabled:checked').length > 0;
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&isEnabled=" + isEnabled;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllSrvcOffrds(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSrvcOffrds(actionText, slctr, linkArgs);
    }
}

//DIAGNOSIS LIST
function saveDiagnsList()
{
    var slctdDiagnsLists = "";
    var errCount = 0;
    var rcdCount = 0;
    $('#allDiagnsListsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allDiagnsListsRow' + rndmNum + '_DiagnsListID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#allDiagnsListsRow' + rndmNum + '_DiagnsListNm').val() == "") {
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListNm').css('border-color', 'red');
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListNm').css('border-color', '#ccc');
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListNm').css('border-width', '1px');
                    }
                    if ($('#allDiagnsListsRow' + rndmNum + '_DiagnsListDesc').val() == "") {
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListDesc').css('border-color', 'red');
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListDesc').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListDesc').css('border-color', '#ccc');
                        $('#allDiagnsListsRow' + rndmNum + '_DiagnsListDesc').css('border-width', '1px');
                    }
                    if (errCount <= 0) {
                        slctdDiagnsLists = slctdDiagnsLists
                                + $('#allDiagnsListsRow' + rndmNum + '_DiagnsListID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allDiagnsListsRow' + rndmNum + '_DiagnsListNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allDiagnsListsRow' + rndmNum + '_DiagnsListDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allDiagnsListsRow' + rndmNum + '_DiagnsListSymtms').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allDiagnsListsRow' + rndmNum + '_DiagnsListPssblTrtmntMdctn').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allDiagnsListsRow' + rndmNum + '_DiagnsListIsEnabled').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Diagnosis record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Diagnosis record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Diagnosiss?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Diagnosiss...Please Wait...</p>',
        callback: function () {
            getAllDiagnsLists('', "#allmodules", "grp=14&typ=1&pg=6&vtyp=0&mdl=Clinic/Hospital");
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
                    grp: 14,
                    typ: 1,
                    pg: 6,
					mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 1,
                    slctdDiagnsLists: slctdDiagnsLists
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delDiagnsList(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var RiskFactorID = '';
    if (typeof $('#allDiagnsListsRow' + rndmNum + '_DiagnsListID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allDiagnsListsRow' + rndmNum + '_DiagnsListID').val();
        //alert('pKeyID'+pKeyID);
    }
    var dialog = bootbox.confirm({
        title: 'Remove Service?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Diagnosis?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Diagnosis?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Diagnosis...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=6&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 6,
									mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 1,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function getAllDiagnsLists(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allDiagnsListsSrchFor").val() === 'undefined' ? '%' : $("#allDiagnsListsSrchFor").val();
    var srchIn = typeof $("#allDiagnsListsSrchIn").val() === 'undefined' ? 'Both' : $("#allDiagnsListsSrchIn").val();
    var pageNo = typeof $("#allDiagnsListsPageNo").val() === 'undefined' ? 1 : $("#allDiagnsListsPageNo").val();
    var limitSze = typeof $("#allDiagnsListsDsplySze").val() === 'undefined' ? 15 : $("#allDiagnsListsDsplySze").val();
    var sortBy = typeof $("#allDiagnsListsSortBy").val() === 'undefined' ? '' : $("#allDiagnsListsSortBy").val();
    var isEnabled = $('#allDiagnsListsIsEnabled:checked').length > 0;
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&isEnabled=" + isEnabled;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllDiagnsLists(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllDiagnsLists(actionText, slctr, linkArgs);
    }
}


//INVESTIGATION LIST
//SERVICE TYPES
function saveInvstgtnList()
{
    var slctdInvstgtnLists = "";
    var errCount = 0;
    var rcdCount = 0;
    $('#allInvstgtnListsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListNm').val() == "") {
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListNm').css('border-color', 'red');
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListNm').css('border-color', '#ccc');
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListNm').css('border-width', '1px');
                    }
                    if ($('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListDesc').val() == "") {
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListDesc').css('border-color', 'red');
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListDesc').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListDesc').css('border-color', '#ccc');
                        $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListDesc').css('border-width', '1px');
                    }
                    if (errCount <= 0) {
                        slctdInvstgtnLists = slctdInvstgtnLists
                                + $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListDesc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListIsEnabled').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allInvstgtnListsRow' + rndmNum + '_SrvcOffrdItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Lab Investigation record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Lab Investigation record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Lab Investigation?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Lab Investigation...Please Wait...</p>',
        callback: function () {
            getAllInvstgtnLists('', "#allmodules", "grp=14&typ=1&pg=7&vtyp=0&mdl=Clinic/Hospital");
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
                    grp: 14,
                    typ: 1,
                    pg: 7,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 1,
                    slctdInvstgtnLists: slctdInvstgtnLists
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delInvstgtnList(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var RiskFactorID = '';
    if (typeof $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allInvstgtnListsRow' + rndmNum + '_InvstgtnListID').val();
        //alert('pKeyID'+pKeyID);
    }
    var dialog = bootbox.confirm({
        title: 'Remove Service?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Lab Investigation?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Lab Investigation?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Lab Investigation...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=6&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 7,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 1,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function getAllInvstgtnLists(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allInvstgtnListsSrchFor").val() === 'undefined' ? '%' : $("#allInvstgtnListsSrchFor").val();
    var srchIn = typeof $("#allInvstgtnListsSrchIn").val() === 'undefined' ? 'Both' : $("#allInvstgtnListsSrchIn").val();
    var pageNo = typeof $("#allInvstgtnListsPageNo").val() === 'undefined' ? 1 : $("#allInvstgtnListsPageNo").val();
    var limitSze = typeof $("#allInvstgtnListsDsplySze").val() === 'undefined' ? 15 : $("#allInvstgtnListsDsplySze").val();
    var sortBy = typeof $("#allInvstgtnListsSortBy").val() === 'undefined' ? '' : $("#allInvstgtnListsSortBy").val();
    var isEnabled = $('#allInvstgtnListsIsEnabled:checked').length > 0;
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&isEnabled=" + isEnabled;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllInvstgtnLists(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllInvstgtnLists(actionText, slctr, linkArgs);
    }
}

//SERVICE PROVIDERS
//BANKS
function deletePrvdrGroup(prvdrGrpId)
{
    var box;
    var box2;
    var mnBox;
    mnBox = bootbox.confirm({
        size: "small",
        title: 'Delete Provider Group?',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Provider Group?<br/>Action cannot be Undone!</p>',
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
            /* your callback code */
            if (result) {

                box = bootbox.dialog({size: "small",
                    message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Deleting. Please Wait...</div>'});
                box.find('.modal-content').css({
                    'margin-top': function () {
                        var w = $(window).height();
                        var b = $(".modal-dialog").height();
                        // should not be (w-h)/2
                        var h = (w - b) / 2;
                        return h + "px";
                    }
                });
                var xmlhttp;
                if (window.XMLHttpRequest)
                {
                    /*code for IE7+, Firefox, Chrome, Opera, Safari*/
                    xmlhttp = new XMLHttpRequest();
                } else
                {
                    /*code for IE6, IE5*/
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function ()
                {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
                    {

                        var data = xmlhttp.responseText;
                        if (data == "SORRY") {
                            $msg = "SORRY! Can't Delete an Provider Group with Persons</br>Delete all branches first.";
                            box.modal('hide');
                            box2 = bootbox.alert({
                                size: "small",
                                title: "Rhomicom Message",
                                message: $msg,
                                callback: function () { /* your callback code */
                                }
                            });
                            box2.find('.modal-content').css({
                                'margin-top': function () {
                                    var w = $(window).height();
                                    var b = $(".modal-dialog").height();
                                    // should not be (w-h)/2
                                    var h = (w - b - 150) / 2;
                                    return h + "px";
                                }
                            });
                        } else {

                            box.modal('hide');
                            box2 = bootbox.alert({
                                size: "small",
                                title: "Rhomicom Message",
                                message: data,
                                callback: function () { /* your callback code */
                                }
                            });
                            box2.find('.modal-content').css({
                                'margin-top': function () {
                                    var w = $(window).height();
                                    var b = $(".modal-dialog").height();
                                    // should not be (w-h)/2
                                    var h = (w - b - 150) / 2;
                                    return h + "px";
                                }
                            });
                            getAllPrvdrGroups('', "#allmodules", "grp=14&typ=1&pg=5&vtyp=0&mdl=Clinic/Hospital");
                        }
                    }
                };
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("grp=14&typ=1&pg=5&vtyp=0&q=DELETE&PKeyID=" + prvdrGrpId + "&mdl=Clinic/Hospital");
            }

        }
    });
    mnBox.find('.modal-content').css({
        'margin-top': function () {
            var w = $(window).height();
            var b = $(".modal-dialog").height();
            // should not be (w-h)/2
            var h = (w - b - 150) / 2;
            return h + "px";
        }
    });
}

function savePrvdrGroup(pgNo, vtyp)
{

    var box;
    var box2;
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {

        var obj;
        var prvdrGrpId = typeof $("#onePrvdrGroupDetID").val() === 'undefined' ? '-1' : $("#onePrvdrGroupDetID").val();
        var prvdrGroupName = typeof $("#onePrvdrGroupDetName").val() === 'undefined' ? '' : $("#onePrvdrGroupDetName").val();
        var prvdrGroupDesc = typeof $("#onePrvdrGroupDetDesc").val() === 'undefined' ? '' : $("#onePrvdrGroupDetDesc").val();	
	var prvdrGroupMainSrvcTypeId = typeof $("#onePrvdrGroupDetMainServiceId").val() === 'undefined' ? '-1' : $("#onePrvdrGroupDetMainServiceId").val();
	var prvdrGroupDetMaxDailyAppntmnts = typeof $("#onePrvdrGroupDetMaxDailyAppntmnts").val() === 'undefined' ? '1' : $("#onePrvdrGroupDetMaxDailyAppntmnts").val();	
        var isEnabled = typeof $("#onePrvdrGroupDetIsEnbld").val() === 'undefined' ? 'Yes' : $("#onePrvdrGroupDetIsEnbld").val();
        var prvdrGrpCostItmId = typeof $("#onePrvdrGroupDetCostItmId").val() === 'undefined' ? '-1' : $("#onePrvdrGroupDetCostItmId").val();
        
        //alert(prvdrGroupMainSrvcTypeId);
        
        if (prvdrGroupName.trim() == "" || prvdrGroupName == undefined) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "Please provide Provider Group Name",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } else if (prvdrGroupDesc.trim() == "" || prvdrGroupDesc == undefined) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "Please provide Provider Group Description",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } else if (prvdrGroupMainSrvcTypeId.trim() == "-1" || prvdrGroupMainSrvcTypeId === undefined) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "Please provide Main Service Offered",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } else if (prvdrGroupDetMaxDailyAppntmnts == "" || parseFloat(prvdrGroupDetMaxDailyAppntmnts) <= 0) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "Please provide Daily Maximum Appointments Number",
                callback: function () { /* your callback code */
                }
            });
            return false;
        } 

        $body.removeClass("mdlloading");
        $body.removeClass("mdlloadingDiag");
        box = bootbox.dialog({size: "small",
            message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i><span style="font-weight:bold; color:green;"> Saving. Please Wait...</span></div>'});
        box.find('.modal-content').css({
            'margin-top': function () {
                var w = $(window).height();
                var b = $(".modal-dialog").height();
                // should not be (w-h)/2
                var h = w / 2; //(w - b) / 2;
                return h + "px";
            }
        });
        var formData = new FormData();
        formData.append('grp', 14);
        formData.append('typ', 1);
        formData.append('q', 'UPDATE');
        formData.append('pg', 5);
        formData.append('mdl','Clinic/Hospital');
        formData.append('actyp', 0);
        formData.append('prvdrGrpId', prvdrGrpId);
        formData.append('prvdrGroupName', prvdrGroupName);
        formData.append('prvdrGroupDesc', prvdrGroupDesc);
	formData.append('prvdrGroupMainSrvcTypeId',prvdrGroupMainSrvcTypeId);
	formData.append('prvdrGroupDetMaxDailyAppntmnts',prvdrGroupDetMaxDailyAppntmnts);
        formData.append('isEnabled', isEnabled);
        formData.append('prvdrGrpCostItmId',prvdrGrpCostItmId);
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {
                //$body.removeClass("mdlloadingDiag");
                //$body.removeClass("mdlloading");

                var msg = "";
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                        replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                        replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                    obj = $.parseJSON(data);
                    //SET PRODUCT ID
                    box.modal('hide');
                    getAllPrvdrGroups('', "#allmodules", "grp=14&typ=1&pg=5&vtyp=0&mdl=Clinic/Hospital");
                    box2 = bootbox.alert({
                        size: "small",
                        title: "Rhomicom Message",
                        message: "Provider Group Saved Successfully",
                        callback: function () { /* your callback code */
                        }
                    });
                } else {

                    msg = data;
                    box.modal('hide');
                    box2 = bootbox.alert({
                        size: "small",
                        title: "Rhomicom Message",
                        message: msg,
                        callback: function () { /* your callback code */
                        }
                    });
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}

function getOnePrvdrGroupDetail(pKeyID, vwtype)
{
//alert('Hi');
    var lnkArgs = 'grp=14&typ=1&pg=5&vtyp=1&sbmtdPrvdrGroupID=' + pKeyID + '&mdl=Clinic/Hospital';
    doAjaxWthCallBck(lnkArgs, 'allPrvdrGroupsDetailInfo', 'PasteDirect', '', '', '', function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            var table2 = $('#allPrvdrGroupPersonsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allPrvdrGroupPersonsTable').wrap('<div class="dataTables_scroll"/>');
        });
    });
}

function getAllPrvdrGroups(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allPrvdrGroupsSrchFor").val() === 'undefined' ? '%' : $("#allPrvdrGroupsSrchFor").val();
    var srchIn = typeof $("#allPrvdrGroupsSrchIn").val() === 'undefined' ? 'Both' : $("#allPrvdrGroupsSrchIn").val();
    var pageNo = typeof $("#allPrvdrGroupsPageNo").val() === 'undefined' ? 1 : $("#allPrvdrGroupsPageNo").val();
    var limitSze = typeof $("#allPrvdrGroupsDsplySze").val() === 'undefined' ? 10 : $("#allPrvdrGroupsDsplySze").val();
    var sortBy = typeof $("#allPrvdrGroupsSortBy").val() === 'undefined' ? '' : $("#allPrvdrGroupsSortBy").val();
    var isEnabled = $('#allPrvdrGroupsIsEnabled:checked').length > 0;
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&isEnabled=" + isEnabled;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllPrvdrGroups(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPrvdrGroups(actionText, slctr, linkArgs);
    }
}

function getAllPrvdrGroupPersons(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allPrvdrGroupPersonsSrchFor").val() === 'undefined' ? '%' : $("#allPrvdrGroupPersonsSrchFor").val();
    var srchIn = typeof $("#allPrvdrGroupPersonsSrchIn").val() === 'undefined' ? 'Both' : $("#allPrvdrGroupPersonsSrchIn").val();
    var pageNo = typeof $("#allPrvdrGroupPersonsPageNo").val() === 'undefined' ? 1 : $("#allPrvdrGroupPersonsPageNo").val();
    var limitSze = typeof $("#allPrvdrGroupPersonsDsplySze").val() === 'undefined' ? 10 : $("#allPrvdrGroupPersonsDsplySze").val();
    var sortBy = typeof $("#allPrvdrGroupPersonsSortBy").val() === 'undefined' ? '' : $("#allPrvdrGroupPersonsSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllPrvdrGroupPersons(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPrvdrGroupPersons(actionText, slctr, linkArgs);
    }
}

function getOnePrvdrGroupForm(pKeyID)
{
    var lnkArgs = 'grp=14&typ=1&pg=5&vtyp=1&sbmtdPrvdrGroupID=' + pKeyID+ '&mdl=Clinic/Hospital';
    doAjaxWthCallBck(lnkArgs, 'allPrvdrGroupsDetailInfo', 'PasteDirect', '', '', '', function () {
        var table1 = $('#allPrvdrGroupPersonsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#allPrvdrGroupPersonsTable').wrap('<div class="dataTables_scroll"/>');
    });
    $('#allPrvdrGroupsForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
}

//BANK BRANCHES
function savePrvdrGroupPerson()
{
//alert("Hi");
    var sbmtdPrvdrGroupID = typeof $("#sbmtdPrvdrGroupID").val() === 'undefined' ? '-1' : $("#sbmtdPrvdrGroupID").val();
    //var srvsTypeId = typeof $("#srvsTypeId").val() === 'undefined' ? '-1' : $("#srvsTypeId").val();
    var slctdPrvdrGroupPersons = "";
    var errCount = 0;
    var rcdCount = 0;
    $('#allPrvdrGroupPersonsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupSrvsPrvdrID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupPersonNm').val() == "") {
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupPersonNm').css('border-color', 'red');
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupPersonNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupPersonNm').css('border-color', '#ccc');
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupPersonNm').css('border-width', '1px');
                    }
                    if ($('#allPrvdrGroupPersonsRow' + rndmNum + '_StartDate').val() == "") {
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_StartDate').css('border-color', 'red');
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_StartDate').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_StartDate').css('border-color', '#ccc');
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_StartDate').css('border-width', '1px');
                    }
                    /*if ($('#allPrvdrGroupPersonsRow' + rndmNum + '_EndDate').val() == "") {
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_EndDate').css('border-color', 'red');
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_EndDate').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_EndDate').css('border-color', '#ccc');
                        $('#allPrvdrGroupPersonsRow' + rndmNum + '_EndDate').css('border-width', '1px');
                    }*/
                    if (errCount <= 0) {
                        slctdPrvdrGroupPersons = slctdPrvdrGroupPersons
                                + $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupSrvsPrvdrID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
				+ $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupPersonID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
				+ $('#allPrvdrGroupPersonsRow' + rndmNum + '_StartDate').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allPrvdrGroupPersonsRow' + rndmNum + '_EndDate').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Service Provider Record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Service Provider record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Person?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Provider Group Persons...Please Wait...</p>',
        callback: function () {
            getAllPrvdrGroups('', "#allmodules", "grp=14&typ=1&pg=5&vtyp=0&mdl=Clinic/Hospital");
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
                    grp: 14,
                    typ: 1,
                    pg: 5,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 1,
                    sbmtdPrvdrGroupID: sbmtdPrvdrGroupID,
                    slctdPrvdrGroupPersons: slctdPrvdrGroupPersons
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delPrvdrGroupPerson(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var srvsPrvdrID = -1;
    if (typeof $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupSrvsPrvdrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupSrvsPrvdrID').val();
        srvsPrvdrID = $('#allPrvdrGroupPersonsRow' + rndmNum + '_PrvdrGroupPersonID').val();
        //alert('pKeyID'+pKeyID);
    }
    var dialog = bootbox.confirm({
        title: 'Remove Person?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Person?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Person?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Person...Please Wait...</p>',
                    callback: function () {
                        getAllPrvdrGroups('', "#allmodules", "grp=14&typ=1&pg=5&vtyp=0&mdl=Clinic/Hospital");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 5,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 1,
                                    PrvdrRowID: pKeyID,
                                    SrvsPrvdrID: srvsPrvdrID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function getInvstgtnListItemsForm(pKeyID, vwtype, actionTxt, recNm, sbmtdSrcType)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }

    var lnkArgs = 'grp=14&typ=1&pg=7&mdl=Clinic/Hospital&vtyp=' + vwtype + '&sbmtdSrcRecID=' + pKeyID +'&sbmtdSrcType='+sbmtdSrcType+'&InvstgnNm='+recNm;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', actionTxt, 'Item/Services for '+recNm, 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        $('#allOtherInputData99').val('0');
        $('#allAppntmntDataItemsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModaly').off('hidden.bs.modal');
        $('#myFormsModaly').one('hidden.bs.modal', function (e) {
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#allAppntmntDataItemsTable')) {
            var table1 = $('#allAppntmntDataItemsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allAppntmntDataItemsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();



    });
}

function generateSetupScript()
{
    var sortCol = typeof $("#sortCol").val() === 'undefined' ? '' : $("#sortCol").val();
    var tableNm = typeof $("#tableNm").val() === 'undefined' ? '' : $("#tableNm").val();
    var pKeyField = typeof $("#pKeyField").val() === 'undefined' ? '' : $("#pKeyField").val();
    var fxnNm = typeof $("#fxnNm").val() === 'undefined' ? '' : $("#fxnNm").val();
    var dpndntTable = typeof $("#dpndntTable").val() === 'undefined' ? '' : $("#dpndntTable").val();
    var foreignKey = typeof $("#foreignKey").val() === 'undefined' ? '' : $("#foreignKey").val();
    var formType = typeof $("#formType").val() === 'undefined' ? 'tabular' : $("#formType").val();
    var noOfCols = typeof $("#noOfCols").val() === 'undefined' ? 1 : $("#noOfCols").val();
    
    var tblColRowList = "";
    var errCount = 0;
    var rcdCount = 0;
    $('#allTechSetupsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allTechSetupsRow' + rndmNum + '_ColNm').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#allTechSetupsRow' + rndmNum + '_ColNm').val() == "") {
                        $('#allTechSetupsRow' + rndmNum + '_ColNm').css('border-color', 'red');
                        $('#allTechSetupsRow' + rndmNum + '_ColNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allTechSetupsRow' + rndmNum + '_ColNm').css('border-color', '#ccc');
                        $('#allTechSetupsRow' + rndmNum + '_ColNm').css('border-width', '1px');
                    }
                    if ($('#allTechSetupsRow' + rndmNum + '_DataType').val() == "") {
                        $('#allTechSetupsRow' + rndmNum + '_DataType').css('border-color', 'red');
                        $('#allTechSetupsRow' + rndmNum + '_DataType').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allTechSetupsRow' + rndmNum + '_DataType').css('border-color', '#ccc');
                        $('#allTechSetupsRow' + rndmNum + '_DataType').css('border-width', '1px');
                    }
                    if ($('#allTechSetupsRow' + rndmNum + '_SearchAllowed').val() == "Yes" && $('#allTechSetupsRow' + rndmNum + '_SearchInLabel').val() == "" ) {
                        $('#allTechSetupsRow' + rndmNum + '_SearchInLabel').css('border-color', 'red');
                        $('#allTechSetupsRow' + rndmNum + '_SearchInLabel').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allTechSetupsRow' + rndmNum + '_SearchInLabel').css('border-color', '#ccc');
                        $('#allTechSetupsRow' + rndmNum + '_SearchInLabel').css('border-width', '1px');
                    }
                    if (errCount <= 0) {
                        tblColRowList = tblColRowList
                        + $('#allTechSetupsRow' + rndmNum + '_ColNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_UpdateExclude').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_SearchAllowed').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_SearchInLabel').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_DataType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" 
                        + $('#allTechSetupsRow' + rndmNum + '_ElementID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_ElementLabel').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_ElementType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_LovID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                        + $('#allTechSetupsRow' + rndmNum + '_Rqrd').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Rows record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Row record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Generate Script?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Generating...Please Wait...</p>',
        callback: function () {
            //dialog.modal('hide');
        }
    });
    
    $('#myFormsModalLg').on('show.bs.modal', function (e) {
        $(this).find('.modal-body').css({
            'max-height': '100%',
            'overflow-y': 'auto'
        });
        $('#myFormsModalLg').css('overflow-y','auto');
    });
    
    
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 14,
                    typ: 1,
                    pg: 8,
		    mdl: 'Clinic/Hospital',
                    q: 'PHP',
                    actyp: 1,
                    tblColRowList: tblColRowList,
                    sortCol: sortCol,
                    tableNm: tableNm,
                    pKeyField: pKeyField,
                    fxnNm: fxnNm,
                    dpndntTable: dpndntTable,
                    foreignKey: foreignKey,
                    formType: formType,
                    noOfCols: noOfCols
                },
                success: function (result) {
                    setTimeout(function () {
                        $('#myFormsModalBodyLg').html(result);
                        
                        $('#myFormsModalLg').modal({backdrop: 'static',keyboard:false});
                        dialog.modal('hide');
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

/*function getRoomNumLovsPage(strDteElementID, endDteElementID, elementID, titleElementID, modalBodyID, lovNm, criteriaID,
        criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
        selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn,
        addtnlWhere, callBackFunc, psblValIDElmntID)
{
    var strtDte = typeof $("#" + strDteElementID).val() === 'undefined' ? '-1' : $("#" + strDteElementID).val();
    var endDte = typeof $("#" + endDteElementID).val() === 'undefined' ? '-1' : $("#" + endDteElementID).val();
    var extrWhere = " and (tbl1.a NOT IN (select z.room_id || '' from hotl.rooms z where z.needs_hse_keeping='1')) " +
            " and tbl1.a NOT IN (Select service_det_id || '' " +
            " FROM hotl.checkins_hdr WHERE ((doc_status='Reserved' or doc_status = 'Rented Out') " +
            " and (to_timestamp('" + strtDte + "', 'DD-Mon-YYYY HH24:MI:SS') between " +
            " to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS') " +
            " AND to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS') or to_timestamp('" + endDte +
            "','DD-Mon-YYYY HH24:MI:SS') between to_timestamp(start_date,'YYYY-MM-DD HH24:MI:SS') " +
            " AND to_timestamp(end_date,'YYYY-MM-DD HH24:MI:SS'))))";

    getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
            criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
            selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn,
            extrWhere, callBackFunc, psblValIDElmntID);
}*/

function getOneHotlChckinDocForm2(pKeyID, vwtype, actionTxt, hotlChckinDocVchType, srcPage, srcModule, ln_FcltyType, ln_SrvsTypID, ln_RoomID, admsnID)
{
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (typeof hotlChckinDocVchType === 'undefined' || hotlChckinDocVchType === null)
    {
        hotlChckinDocVchType = 'Sales Invoice-Hospitality';
    }
    if (typeof srcPage === 'undefined' || srcPage === null)
    {
        srcPage = 'RENT';
    }
    if (typeof srcModule === 'undefined' || srcModule === null)
    {
        srcModule = 'allmodules';
    }
    if (typeof ln_FcltyType === 'undefined' || ln_FcltyType === null)
    {
        ln_FcltyType = '';
    }
    if (typeof ln_SrvsTypID === 'undefined' || ln_SrvsTypID === null)
    {
        ln_SrvsTypID = -1;
    }
    if (typeof ln_RoomID === 'undefined' || ln_RoomID === null)
    {
        ln_RoomID = -1;
    }
    
    var lnkArgs = 'grp=18&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdHotlChckinDocID=' + pKeyID + '&hotlChckinDocVchType=' + hotlChckinDocVchType + '&srcModule=' + srcModule + '&ln_FcltyType=' + ln_FcltyType + '&ln_SrvsTypID=' + ln_SrvsTypID + '&ln_RoomID=' + ln_RoomID
    + '&admsnID='+admsnID;
    var   diagTitle = hotlChckinDocVchType + ' Document Details (ID:' + pKeyID + ')';
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, diagTitle, 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
    //doAjaxWthCallBck(lnkArgs, 'myFormsModalLgYH', actionTxt, diagTitle, 'myFormsModalLgYHTitle', 'myFormsModalLgYHBody', function () {
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
        $('#allOtherInputData99').val('0');
        $('#oneHotlChckinDocEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            //getHotlChckinDoc('', '#' + srcModule, 'grp=18&typ=1&pg=2&vtyp=0');
            $(e.currentTarget).unbind();
        });
        /*oneScmSalesInvcSmryLinesTable
         oneScmSalesInvcSmry1Table
         oneSalesRqstdItmLinesTable*/
        if (!$.fn.DataTable.isDataTable('#oneSalesRqstdItmLinesTable')) {
            var table1 = $('#oneSalesRqstdItmLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneSalesRqstdItmLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneScmSalesInvcSmryLinesTable')) {
            var table1 = $('#oneScmSalesInvcSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneScmSalesInvcSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneScmSalesInvcSmry1Table')) {
            var table1 = $('#oneScmSalesInvcSmry1Table').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneScmSalesInvcSmry1Table').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="tabajxsalesinvc"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=18&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('salesInvcExtraInfo') >= 0) {
                $('#addNwHotlChckinDocSmryBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcSmryBtn').addClass('hideNotice');
            } else if (targ.indexOf('salesInvcDetLines') >= 0) {
                $('#addNwHotlChckinDocSmryBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcSmryBtn').removeClass('hideNotice');
            } else {
                $('#addNwHotlChckinDocSmryBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcSmryBtn').addClass('hideNotice');
            }
        });
        $(".jbDetDesc").focus(function () {
            $(this).select();
        });
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
        $(".jbDetCrdt").focus(function () {
            $(this).select();
        });
        $(".jbDetAccRate").off('focus');
        $(".jbDetAccRate").focus(function () {
            $(this).select();
        });
        $('#oneScmSalesInvcSmryLinesTable tr').off('click');
        $('#oneScmSalesInvcSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneScmSalesInvcSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllHotlChckinDocSmryTtl(1);
        autoCreateSalesLns = -1;
        var scmSalesInvcApprvlStatus = typeof $("#scmSalesInvcApprvlStatus").val() === 'undefined' ? '' : $("#scmSalesInvcApprvlStatus").val();
        if (scmSalesInvcApprvlStatus === "Not Validated") {
            $("#oneScmSalesInvcSmryLinesTable tr:nth-of-type(1) .jbDetDesc").focus();
        } else {
            /*$("#hotlChckinDocTndrdAmnt").focus();*/
        }
    });
}

/*function getHotlChckinDoc(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#hotlChckinDocSrchFor").val() === 'undefined' ? '%' : $("#hotlChckinDocSrchFor").val();
    var srchIn = typeof $("#hotlChckinDocSrchIn").val() === 'undefined' ? 'Both' : $("#hotlChckinDocSrchIn").val();
    var pageNo = typeof $("#hotlChckinDocPageNo").val() === 'undefined' ? 1 : $("#hotlChckinDocPageNo").val();
    var limitSze = typeof $("#hotlChckinDocDsplySze").val() === 'undefined' ? 10 : $("#hotlChckinDocDsplySze").val();
    var sortBy = typeof $("#hotlChckinDocSortBy").val() === 'undefined' ? '' : $("#hotlChckinDocSortBy").val();
    var qShwUnpstdOnly = $('#hotlChckinDocShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#hotlChckinDocShwUnpaidOnly:checked').length > 0;
    var qShwSelfOnly = $('#hotlChckinDocShwSelfOnly:checked').length > 0;
    var qShwMyBranch = $('#hotlChckinDocShwMyBranchOnly:checked').length > 0;
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
            "&qShwUnpaidOnly=" + qShwUnpaidOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly + "&qShwSelfOnly=" + qShwSelfOnly + "&qShwMyBranch=" + qShwMyBranch;
    //alert(linkArgs);
    openATab(slctr, linkArgs);
}*/

function checkInAppointment(appntmntID, vwtyp)
{
    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
    var dialog = bootbox.confirm({
        title: 'Start Processing?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CHECK-IN</span> this Patient?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Check-In Patient?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Starting...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 3,
                                mdl: 'Clinic/Hospital',
                                q: 'CHECK-IN',
                                actyp: 1,
                                PKeyID: appntmntID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, vwtyp, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

function checkOutAppointment(appntmntID, vwtyp)
{
    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
    var dialog = bootbox.confirm({
        title: 'Complete Appointment',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">COMPLETE</span> and CLOSE this appointment?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Check-Out Appointment?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Completing and Closing Appointment...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 3,
                                mdl: 'Clinic/Hospital',
                                q: 'CHECK-OUT',
                                actyp: 1,
                                PKeyID: appntmntID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, vwtyp, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

function saveInptntAdmsn()
{
    var slctdInptntAdmsn = "";
    var errCount = 0;
    var rcdCount = 0;
	
    var cnsltnID = typeof $('#cnsltnID').val() === undefined ? -1 : $('#cnsltnID').val();
    var appntmntID = typeof $('#appntmntID').val() === undefined ? -1 : $('#appntmntID').val();
    var refCheckInId = -1;

    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
	
    $('#allInptntAdmsnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allInptntAdmsnsRow' + rndmNum + '_InptntAdmsnID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#allInptntAdmsnsRow' + rndmNum + '_AdmissionDate').val() == "") {
                        $('#allInptntAdmsnsRow' + rndmNum + '_AdmissionDate').css('border-color', 'red');
                        $('#allInptntAdmsnsRow' + rndmNum + '_AdmissionDate').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allInptntAdmsnsRow' + rndmNum + '_AdmissionDate').css('border-color', '#ccc');
                        $('#allInptntAdmsnsRow' + rndmNum + '_AdmissionDate').css('border-width', '1px');
                    }
                    if ($('#allInptntAdmsnsRow' + rndmNum + '_CheckOutDate').val() == "") {
                        $('#allInptntAdmsnsRow' + rndmNum + '_CheckOutDate').css('border-color', 'red');
                        $('#allInptntAdmsnsRow' + rndmNum + '_CheckOutDate').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allInptntAdmsnsRow' + rndmNum + '_CheckOutDate').css('border-color', '#ccc');
                        $('#allInptntAdmsnsRow' + rndmNum + '_CheckOutDate').css('border-width', '1px');
                    }
					if ($('#allInptntAdmsnsRow' + rndmNum + '_SrvcTypID').val() == "-1") {
                        $('#allInptntAdmsnsRow' + rndmNum + '_SrvcTyp').css('border-color', 'red');
                        $('#allInptntAdmsnsRow' + rndmNum + '_SrvcTyp').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allInptntAdmsnsRow' + rndmNum + '_SrvcTyp').css('border-color', '#ccc');
                        $('#allInptntAdmsnsRow' + rndmNum + '_SrvcTyp').css('border-width', '1px');
                    }
                    if ($('#allInptntAdmsnsRow' + rndmNum + '_RmID').val() == "-1") {
                        $('#allInptntAdmsnsRow' + rndmNum + '_RmNum').css('border-color', 'red');
                        $('#allInptntAdmsnsRow' + rndmNum + '_RmNum').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allInptntAdmsnsRow' + rndmNum + '_RmNum').css('border-color', '#ccc');
                        $('#allInptntAdmsnsRow' + rndmNum + '_RmNum').css('border-width', '1px');
                    }
                    if (errCount <= 0) {
                        slctdInptntAdmsn = slctdInptntAdmsn
                                + $('#allInptntAdmsnsRow' + rndmNum + '_InptntAdmsnID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"								
                                + cnsltnID + "~"
                                + $('#allInptntAdmsnsRow' + rndmNum + '_SrvcTypID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allInptntAdmsnsRow' + rndmNum + '_RmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + appntmntID + "~"
                                + $('#allInptntAdmsnsRow' + rndmNum + '_AdmissionDate').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allInptntAdmsnsRow' + rndmNum + '_CheckOutDate').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
				+ $('#allInptntAdmsnsRow' + rndmNum + '_RefCheckInId').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Admission record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Admission record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Lab Investigation?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Admissions...Please Wait...</p>',
        callback: function () {
            getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 5, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
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
                    grp: 14,
                    typ: 1,
                    pg: 3,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 6,
                    slctdInptntAdmsn: slctdInptntAdmsn
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delInptntAdmsn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#allInptntAdmsnsRow' + rndmNum + '_InptntAdmsnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allInptntAdmsnsRow' + rndmNum + '_InptntAdmsnID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Record?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this record?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Record?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Admission Record...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=6&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 6,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

//NEW 10082020
function closeHotlChkInForm(){
    $('#myFormsModalLgYHTitle').html('');
    $('#myFormsModalLgYHBody').html('');    
    $('#myFormsModalLgYH').modal('toggle');
}

//MEDICATION
function saveMedication(intPrscrbrPrsnId)
{
    var slctdMedication = "";
    var errCount = 0;
    var rcdCount = 0;
	
    var cnsltnID = typeof $('#cnsltnID').val() === undefined ? -1 : $('#cnsltnID').val();
    var appntmntID = typeof $('#appntmntID').val() === undefined ? -1 : $('#appntmntID').val();
    var refCheckInId = -1;

    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
	
    $('#medicationTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#medicationRow' + rndmNum + '_MedicationID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#medicationRow' + rndmNum + '_DrugItm').val() == "") {
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-color', 'red');
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-color', '#ccc');
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-width', '1px');
                    }
                    if ($('#medicationRow' + rndmNum + '_DoseQty').val() == "" ||
						$('#medicationRow' + rndmNum + '_FrqncyNo').val() == "" ||
						$('#medicationRow' + rndmNum + '_DrtnNo').val() == "") {
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-color', 'red');
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-color', '#ccc');
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-width', '1px');
                    }
                    
                    var isDspnsd = typeof $("input[name='medicationRow" + rndmNum + "_IsDspnsd']:checked").val() === 'undefined' ? 'NO' : $("input[name='medicationRow" + rndmNum + "_IsDspnsd']:checked").val();
                    var subAllowed = typeof $("input[name='medicationRow" + rndmNum + "_SubAllowed']:checked").val() === 'undefined' ? 'NO' : $("input[name='medicationRow" + rndmNum + "_SubAllowed']:checked").val();
                    //alert(i+' isDspnsd='+isDspnsd+' subAllowed='+subAllowed);
                    if (errCount <= 0) {
                        slctdMedication = slctdMedication
                                + $('#medicationRow' + rndmNum + '_MedicationID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"								
                                + cnsltnID + "~"
                                + $('#medicationRow' + rndmNum + '_DocCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_PhrmcyCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + isDspnsd + "~"
				+ appntmntID + "~"
                                + $('#medicationRow' + rndmNum + '_DrugItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DoseQty').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DoseQtyUOM').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_FrqncyNo').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_FrqncyUOM').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DrtnNo').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DrtnUOM').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DoseForm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_AdminTimes').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + subAllowed + "~"
                                + intPrscrbrPrsnId + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Medication record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Medication record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Medication?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Medications...Please Wait...</p>',
        callback: function () {
            if(cnsltnID > 0){
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
            } else {
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 4, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
            }
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
                    grp: 14,
                    typ: 1,
                    pg: 3,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 5,
                    slctdMedication: slctdMedication
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delMedication(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#medicationRow' + rndmNum + '_MedicationID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#medicationRow' + rndmNum + '_MedicationID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Record?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this record?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Record?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Medication Record...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=5&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
								    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 5,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function saveNFinalizeMedication(intPrscrbrPrsnId, lnkdVstId, lnkdSrvsTypeID, lnkdSrvsTypeCode, lnkdSrvsPrvdrGrpID, lnkdSrcCnsltnID, lnkdAppnmntDate)
{
    var slctdMedication = "";
    var errCount = 0;
    var rcdCount = 0;
	
    var cnsltnID = typeof $('#cnsltnID').val() === undefined ? -1 : $('#cnsltnID').val();
    var appntmntID = typeof $('#appntmntID').val() === undefined ? -1 : $('#appntmntID').val();
    var refCheckInId = -1;

    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
        /**FINALIZE **/
    var frmAppntmntCmnts =  "";
    var docAdmsnCheckInDate = "";
    var docAdmsnCheckInNoOfDays = "";
    //var pName = typeof $("#patientNm").val() === "undefined" ? '' : $("#patientNm").val();
    //var pAppntmntNo = typeof $("#appntmntNo").val() === "undefined" ? '' : $("#appntmntNo").val();
    if(lnkdSrvsTypeCode === "IA-0001"){
        frmAppntmntCmnts = typeof $("#docAdmsnInstructions").val() === 'undefined' ? '' : $("#docAdmsnInstructions").val();
        docAdmsnCheckInDate = typeof $("#docAdmsnCheckInDate").val() === 'undefined' ? '' : $("#docAdmsnCheckInDate").val();
        docAdmsnCheckInNoOfDays = typeof $("#docAdmsnCheckInNoOfDays").val() === 'undefined' ? 1 : $("#docAdmsnCheckInNoOfDays").val();
    }
    
    var cnsltnAppntmnt_id = typeof $("#appntmntID").val() === 'undefined' ? '' : $("#appntmntID").val();
    
    var vstId = lnkdVstId;
    var frmCnsltnID = lnkdSrcCnsltnID;
    
    var frmAppntmntID = -1;
    var frmAppntmntDate = lnkdAppnmntDate;
    var frmSrvsTypeId  = lnkdSrvsTypeID;
    var frmPrvdrType = "G";
    var frmSrvsPrvdrId  =  lnkdSrvsPrvdrGrpID; 
    /**FINALIZE **/
	
    $('#medicationTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#medicationRow' + rndmNum + '_MedicationID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#medicationRow' + rndmNum + '_DrugItm').val() == "") {
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-color', 'red');
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-color', '#ccc');
                        $('#medicationRow' + rndmNum + '_DrugItm').css('border-width', '1px');
                    }
                    if ($('#medicationRow' + rndmNum + '_DoseQty').val() == "" ||
						$('#medicationRow' + rndmNum + '_FrqncyNo').val() == "" ||
						$('#medicationRow' + rndmNum + '_DrtnNo').val() == "") {
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-color', 'red');
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-color', '#ccc');
                        $('#medicationRow' + rndmNum + '_Instruction').css('border-width', '1px');
                    }
                    
                    var isDspnsd = typeof $("input[name='medicationRow" + rndmNum + "_IsDspnsd']:checked").val() === 'undefined' ? 'NO' : $("input[name='medicationRow" + rndmNum + "_IsDspnsd']:checked").val();
                    var subAllowed = typeof $("input[name='medicationRow" + rndmNum + "_SubAllowed']:checked").val() === 'undefined' ? 'NO' : $("input[name='medicationRow" + rndmNum + "_SubAllowed']:checked").val();
                    //alert(i+' isDspnsd='+isDspnsd+' subAllowed='+subAllowed);
                    if (errCount <= 0) {
                        slctdMedication = slctdMedication
                                + $('#medicationRow' + rndmNum + '_MedicationID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"								
                                + cnsltnID + "~"
                                + $('#medicationRow' + rndmNum + '_DocCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_PhrmcyCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + isDspnsd + "~"
				+ appntmntID + "~"
                                + $('#medicationRow' + rndmNum + '_DrugItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DoseQty').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DoseQtyUOM').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_FrqncyNo').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_FrqncyUOM').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DrtnNo').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DrtnUOM').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_DoseForm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#medicationRow' + rndmNum + '_AdminTimes').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + subAllowed + "~"
                                + intPrscrbrPrsnId + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Medication record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Medication record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Finalize Prescriptions?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Medications...Please Wait...</p>',
        callback: function () {
            if(cnsltnID > 0){
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
            } else {
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 4, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
            }
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
                    grp: 14,
                    typ: 1,
                    pg: 3,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 5.1,
                    slctdMedication: slctdMedication,
                    vstId: vstId,
                    frmAppntmntID: frmAppntmntID,   
                    frmAppntmntDate: frmAppntmntDate,   
                    frmSrvsTypeId: frmSrvsTypeId,
                    frmPrvdrType: frmPrvdrType,   
                    frmSrvsPrvdrId: frmSrvsPrvdrId,    
                    frmAppntmntCmnts: frmAppntmntCmnts, 
                    lnkdSrvsTypeCode:lnkdSrvsTypeCode,
                    frmCnsltnID: frmCnsltnID,
                    docAdmsnCheckInDate:docAdmsnCheckInDate,
                    docAdmsnCheckInNoOfDays: docAdmsnCheckInNoOfDays,
                    cnsltnAppntmnt_id: cnsltnAppntmnt_id
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getHospAppntmntDataItems(rowIDAttrb, actionTxt, sbmtdDocType, qCnsgnOnly, callBackFunc)
{
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }

    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }

    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var storeID = -1;
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
        storeID = typeof $('#' + rowPrfxNm + rndmNum + '_StoreID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_StoreID').val();
    }
    var scmSalesInvcCstmrSiteID = typeof $("#scmSalesInvcCstmrSiteID").val() === 'undefined' ? -1 : $("#scmSalesInvcCstmrSiteID").val();
    if (sbmtdDocType.indexOf("Receipt") !== -1) {
        scmSalesInvcCstmrSiteID = typeof $("#scmCnsgnRcptSpplrSiteID").val() === 'undefined' ? -1 : $("#scmCnsgnRcptSpplrSiteID").val();
    } else if (sbmtdDocType.indexOf("Purchase") !== -1) {
        scmSalesInvcCstmrSiteID = typeof $("#scmPrchsDocSpplrSiteID").val() === 'undefined' ? -1 : $("#scmPrchsDocSpplrSiteID").val();
    }
    var lnkArgs = 'grp=14&typ=1&pg=2&vtyp=4&mdl=Clinic/Hospital&sbmtdItemID=' + pKeyID + '&scmSalesInvcCstmrSiteID=' + scmSalesInvcCstmrSiteID +
            '&sbmtdDocType=' + sbmtdDocType + '&qCnsgnOnly=' + qCnsgnOnly + '&sbmtdRowIDAttrb=' + rowIDAttrb +
            '&sbmtdCallBackFunc=' + callBackFunc + '&sbmtdStoreID=' + storeID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLxH', actionTxt, 'Sales/Inventory Items', 'myFormsModalLxHTitle', 'myFormsModalLxHBody', function () {
        if (!$.fn.DataTable.isDataTable('#scmSalesInvItmsHdrsTable')) {
            scmInvItmtable1 = $('#scmSalesInvItmsHdrsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#scmSalesInvItmsHdrsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#scmSalesInvItmsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allOtherInputData99').val(0);


        $('#scmSalesInvItmsHdrsTable tbody').on('dblclick', 'tr', function () {
            scmInvItmtable1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

            $checkedBoxes = $(this).find('input[type=checkbox]');
            $checkedBoxes.each(function (i, checkbox) {
                checkbox.checked = true;
            });
            $radioBoxes = $(this).find('input[type=radio]');
            $radioBoxes.each(function (i, radio) {
                radio.checked = true;
            });
            var sbmtdCallBackFunc = typeof $("#sbmtdCallBackFunc").val() === 'undefined' ? 'function(){var a=1;}' : $("#sbmtdCallBackFunc").val();
            if (sbmtdDocType.indexOf("Receipt") !== -1) {
                applySlctdSalesInvItms(rowIDAttrb, qCnsgnOnly, 'oneScmCnsgnRcptSmryLinesTable', sbmtdCallBackFunc);
            } else if (sbmtdDocType.indexOf("Stock") !== -1) {
                applySlctdSalesInvItms(rowIDAttrb, qCnsgnOnly, 'oneScmStockTrnsfrSmryLinesTable', sbmtdCallBackFunc);
            } else if (sbmtdDocType.indexOf("Purchase") !== -1) {
                applySlctdSalesInvItms(rowIDAttrb, qCnsgnOnly, 'oneScmPrchsDocSmryLinesTable', sbmtdCallBackFunc);
            } else {
                applySlctdSalesInvItms(rowIDAttrb, qCnsgnOnly, 'oneScmSalesInvcSmryLinesTable', sbmtdCallBackFunc);
            }
        });

        $('#scmSalesInvItmsHdrsTable tbody').on('click', 'tr', function () {
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
                scmInvItmtable1.$('tr.selected').removeClass('selected');
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
        $('#scmSalesInvItmsHdrsTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        scmInvItmtable1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });

        $('#myFormsModalLxH').off('hidden.bs.modal');
        $('#myFormsModalLxH').one('hidden.bs.modal', function (e) {
            $('#myFormsModalLxHTitle').html('');
            $('#myFormsModalLxHBody').html('');
            callBackFunc();
            $(e.currentTarget).unbind();
        });
    });
}

function  applySlctdHospAppntmntDataItms(rowIDAttrb, qCnsgnOnly, tableElmntID, callBackFunc) {
    /*nwSalesDocLineHtm
     */
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    var inptHtml = typeof $("#nwSalesDocLineHtm").val() === 'undefined' ? '' : $("#nwSalesDocLineHtm").val();
    var scmSalesInvcVchType = 'Sales Invoice'; //typeof $("#scmSalesInvcVchType").val() === 'undefined' ? '' : $("#scmSalesInvcVchType").val();
    var form = document.getElementById("scmSalesInvItmsForm");
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
    if (cbResults.length > 1)
    {
        fnl_res = cbResults.slice(0, -1);
    }
    if (radioResults.length > 1)
    {
        fnl_res = radioResults.slice(0, -1);
    }
    var bigArry = [];
    bigArry = fnl_res.split("|");
    var i = 0;
    var ln_ItmCnsgns = "";
    var ln_CnsgnNo = "";
    var ln_ItmID = -1;
    var ln_ItmNm = "";
    var ln_UomID = -1;
    var ln_UomNm = "";
    var ln_SellPrice = 0;
    var ln_CostPrice = 0;
    var ln_TaxID = -1;
    var ln_DscntID = -1;
    var ln_ChrgID = -1;
    var ln_StoreID = -1;
    var ln_StoreNm = "";
    var ln_InvAcntID = -1;
    var ln_CogsAcntID = -1;
    var ln_SalesRevAcntID = -1;
    var ln_SalesRetAcntID = -1;
    var ln_PrchsRetAcntID = -1;
    var ln_ExpnsAcntID = -1;

    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    for (i = 0; i < bigArry.length; i++) {
        var rndmNum1 = bigArry[i].split("_")[1];
        var rowPrfxNm1 = bigArry[i].split("_")[0];
        ln_ItmID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_ItmID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_ItmID').val();
        ln_ItmNm = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_ItmNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm1 + rndmNum1 + '_ItmNm').val();
        ln_UomID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_UomID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_UomID').val();
        ln_UomNm = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_UomNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm1 + rndmNum1 + '_UomNm').val();
        ln_CnsgnNo = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_UomNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm1 + rndmNum1 + '_UomNm').val();
        ln_SellPrice = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_SellPrice').val() === 'undefined' ? '0.00' : $('#' + rowPrfxNm1 + rndmNum1 + '_SellPrice').val();
        ln_TaxID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_TaxID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_TaxID').val();
        ln_DscntID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_DscntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_DscntID').val();
        ln_ChrgID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_ChrgID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_ChrgID').val();
        ln_StoreID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_StoreID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_StoreID').val();
        ln_InvAcntID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_InvAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_InvAcntID').val();
        ln_CogsAcntID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_CogsAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_CogsAcntID').val();
        ln_SalesRevAcntID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_SalesRevAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_SalesRevAcntID').val();
        ln_SalesRetAcntID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_SalesRetAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_SalesRetAcntID').val();
        ln_PrchsRetAcntID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_PrchsRetAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_PrchsRetAcntID').val();
        ln_ExpnsAcntID = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_ExpnsAcntID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm1 + rndmNum1 + '_ExpnsAcntID').val();
        ln_StoreNm = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_StoreNm').val() === 'undefined' ? '' : $('#' + rowPrfxNm1 + rndmNum1 + '_StoreNm').val();
        ln_CostPrice = typeof $('#' + rowPrfxNm1 + rndmNum1 + '_CostPrice').val() === 'undefined' ? '0.00' : $('#' + rowPrfxNm1 + rndmNum1 + '_CostPrice').val();
        if (qCnsgnOnly === "true" || qCnsgnOnly === true) {
            ln_ItmCnsgns = ln_ItmCnsgns + ln_CnsgnNo + ",";
        } else {
            
            var nwRndm = insertOnlyScmSalesInvcRows(tableElmntID, 1, inptHtml);
            $('#allAppntmntDataItemsRow' + rndmNum + '_ItemID').val(ln_ItmID);
            $('#allAppntmntDataItemsRow' + rndmNum + '_ItemDesc').val(ln_ItmNm);
            $('#allAppntmntDataItemsRow' + rndmNum + '_UomID').val(ln_UomID);
            $('#allAppntmntDataItemsRow'  + rndmNum + '_UomNm1').text(ln_UomNm);
            var curQTY = typeof $('#allAppntmntDataItemsRow'  + rndmNum + '_Qty').val() === 'undefined' ? '0' : $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').val();
            if (Number(curQTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').val(1);
            }


            $('#allAppntmntDataItemsRow' + rndmNum + '_UnitPrice').val(ln_SellPrice);
            /*$('#allAppntmntDataItemsRow' + rowPrfxNm + nwRndm + '_TaxID').val(ln_TaxID);
            $('#allAppntmntDataItemsRow' + rowPrfxNm + nwRndm + '_DscntID').val(ln_DscntID);
            $('#allAppntmntDataItemsRow' + rowPrfxNm + nwRndm + '_ChrgID').val(ln_ChrgID);*/
            $('#allAppntmntDataItemsRow' + rndmNum + '_StoreID').val(ln_StoreID);
            //$('#allAppntmntDataItemsRow' + rowPrfxNm + nwRndm + '_ItmAccnts').val(ln_InvAcntID + ";" + ln_CogsAcntID + ";" + ln_SalesRevAcntID + ";" + ln_SalesRetAcntID + ";" + ln_PrchsRetAcntID + ";" + ln_ExpnsAcntID);

            //
        }
    }
    if (qCnsgnOnly === "true" || qCnsgnOnly === true) {
        $('#' + rowPrfxNm + rndmNum + '_CnsgnIDs').val(ln_ItmCnsgns);
    } else {
        $('[data-toggle="tooltip"]').tooltip();
        $(".jbDetDesc").focus(function () {
            $(this).select();
        });
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
        $(".jbDetCrdt").focus(function () {
            $(this).select();
        });
        $(".jbDetAccRate").off('focus');
        $(".jbDetAccRate").focus(function () {
            $(".jbDetAccRate").off('focus');
            $(this).select();
            $(".jbDetAccRate").focus(function () {
                $(this).select();
            });
        });
        var cntr = 0;
        $('#' + tableElmntID).find('tr').each(function (i, el) {
            if (i > 0)
            {
                if (typeof $(el).attr('id') === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr('id').split("_")[0];
                    var rndmNum1 = $(el).attr('id').split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
                    $tds.eq(0).html(cntr);
                }
            }
        });
    }
    $('#myFormsModalLxH').modal('hide');

    var ttlrows = $('#' + tableElmntID + ' > tbody > tr').length;
    $('#allOtherInputData99').val(ttlrows);
    /*$('#' + rowPrfxNm + rndmNum + '_QTY').focus();*/
}

function getOneHospAppntmntDataUOMBrkdwnForm(pKeyID, vwtype, rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var sbmtdTblRowID = 'oneINVQtyBrkDwnTable';
    var sbmtdItemID = $('#allAppntmntDataItemsRow' + rndmNum + '_ItemID').val();
    var varTtlQtyStr = $('#allAppntmntDataItemsRow' + rndmNum + '_Qty').val();
    var varTtlQty = Number(varTtlQtyStr.replace(/[^-?0-9\.]/g, ''));
    var sbmtdCrncyNm = typeof $("#scmSalesInvcInvcCur1").text() === 'undefined' ? '' : $("#scmSalesInvcInvcCur1").text();
    
    var lnkArgs = 'grp=14&typ=1&pg=2&vtyp=' + vwtype + '&mdl=Clinic/Hospital&sbmtdScmTrnsHdrID=' + pKeyID +
            "&sbmtdItemID=" + sbmtdItemID + "&varTtlQty=" + varTtlQty +
            "&sbmtdRwNum=" + rndmNum + "&sbmtdCrncyNm=" + sbmtdCrncyNm +
            "&sbmtdTblRowID=" + sbmtdTblRowID + "&rowIDAttrb=" + rowIDAttrb;

    doAjaxWthCallBck(lnkArgs, 'myFormsModalxH', 'ShowDialog', 'Item QTY UOM Breakdown', 'myFormsModalxHTitle', 'myFormsModalxHBody', function () {
        var table1 = $('#oneINVQtyBrkDwnTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneINVQtyBrkDwnTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#oneSalesTrnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(".invUmbTtl").focus(function () {
            $(this).select();
        });
        $(".invUmbQty").focus(function () {
            $(this).select();
        });
    });
}

function applyNewINVQtyValClinicHosp(v_rndmNum, modalID, tblRowID)
{
    var rowPrfxNm = tblRowID.split("_")[0];
    var rndmNum = tblRowID.split("_")[1];
    var qtyVal = $('#myCptrdQtyTtlVal').val();
    var ttlAmnt = $('#myCptrdUmValsTtlVal').val();
    $('#allAppntmntDataItemsRow' +  rndmNum + '_Qty').val(Number(qtyVal));
    //$('#allAppntmntDataItemsRow' +  rndmNum + '_UnitPrice').val(addCommas((Number(ttlAmnt) / Number(qtyVal)).toFixed(5)));
    $('#' + modalID).modal('hide');
}

function getItemServiceLOV(itmIDElmntID,itmCodeElmntID,srvsTypeIDElmntID){
    var lovNm = "Inventory Items";
    if($('#'+srvsTypeIDElmntID).val() == 'Service'){
        lovNm = "Inventory Services";
    }
    getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', lovNm, 'gnrlOrgID', '', '', 'radio', true, '', itmIDElmntID, itmCodeElmntID, 'clear', 1, '');
}

function saveItmServsRqrd()
{
    var slctdItmServsRqrd = "";
    var errCount = 0;
    var rcdCount = 0;
	
    var InvstgnNm = typeof $('#InvstgnNm').val() === 'undefined' ? '' : $('#InvstgnNm').val();	
    var InvstgtnListId = typeof $('#InvstgtnListId').val() === 'undefined' ? -1 : $('#InvstgtnListId').val();
    var ServsTypeId = typeof $('#ServsTypeId').val() === 'undefined' ? -1 : $('#ServsTypeId').val();
    
    var recVal = InvstgtnListId;
    var srvsType = "LAB";
    if(ServsTypeId > 0){
        srvsType = "OTHERS";
        recVal = ServsTypeId;
    }
    $('#allItmServsRqrdTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allItmServsRqrdRow' + rndmNum + '_ItmServsRqrdID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    
                    if ($('#allItmServsRqrdRow' + rndmNum + 'ItemID').val() == "-1") {
                        $('#allItmServsRqrdRow' + rndmNum + '_ItemDesc').css('border-color', 'red');
                        $('#allItmServsRqrdRow' + rndmNum + '_ItemDesc').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmServsRqrdRow' + rndmNum + '_ItemDesc').css('border-color', '#ccc');
                        $('#allItmServsRqrdRow' + rndmNum + '_ItemDesc').css('border-width', '1px');
                    }
					if ($('#allItmServsRqrdRow' + rndmNum + '_Qty').val() == "") {
                        $('#allItmServsRqrdRow' + rndmNum + '_Qty').css('border-color', 'red');
                        $('#allItmServsRqrdRow' + rndmNum + '_Qty').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmServsRqrdRow' + rndmNum + '_Qty').css('border-color', '#ccc');
                        $('#allItmServsRqrdRow' + rndmNum + '_Qty').css('border-width', '1px');
                    }
                    if (errCount <= 0) {
                        slctdItmServsRqrd = slctdItmServsRqrd
                                + $('#allItmServsRqrdRow' + rndmNum + '_ItmServsRqrdID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
								+ InvstgtnListId + "~"
								+ $('#allItmServsRqrdRow' + rndmNum + '_Qty').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allItmServsRqrdRow' + rndmNum + '_Cmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#allItmServsRqrdRow' + rndmNum + '_ItemID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
								+ ServsTypeId + "~"
                                + $('#allItmServsRqrdRow' + rndmNum + '_ItmType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Records?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Records...Please Wait...</p>',
        callback: function () {
            //getAllItmServsRqrd('', "#allmodules", "grp=14&typ=1&pg=7&vtyp=0&mdl=Clinic/Hospital");
            getInvstgtnListItemsForm(recVal, 1, 'ShowDialog', InvstgnNm, srvsType);
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
                    grp: 14,
                    typ: 1,
                    pg: 7,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 2,
                    slctdItmServsRqrd: slctdItmServsRqrd
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delItmServsRqrd(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var RiskFactorID = '';
    if (typeof $('#allItmServsRqrdRow' + rndmNum + '_ItmServsRqrdID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allItmServsRqrdRow' + rndmNum + '_ItmServsRqrdID').val();
        //alert('pKeyID'+pKeyID);
    }
    var dialog = bootbox.confirm({
        title: 'Remove Record?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Record?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Record?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Record...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=6&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 7,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 2,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function closeSrvItmDialog(){
    $('#myFormsModalyTitle').html('');
    $('#myFormsModalyBody').html('');    
    $('#myFormsModaly').modal('toggle');
}

//INVESTIGATION
function saveInvestigation()
{
    var slctdInvstgtn = "";
    var errCount = 0;
    var rcdCount = 0;
	
    var cnsltnID = typeof $('#cnsltnID').val() === undefined ? -1 : $('#cnsltnID').val();
    var appntmntID = typeof $('#appntmntID').val() === undefined ? -1 : $('#appntmntID').val();
    var refCheckInId = -1;

    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
	
    $('#invstgtnTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#invstgtnRow' + rndmNum + '_InvstgtnID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#invstgtnRow' + rndmNum + '_RqstNm').val() == "") {
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-color', 'red');
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-color', '#ccc');
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-width', '1px');
                    }
                    
                    var doInhouse = typeof $("input[name='invstgtnRow" + rndmNum + "_DoInhouse']:checked").val() === 'undefined' ? 'NO' : $("input[name='invstgtnRow" + rndmNum + "_DoInhouse']:checked").val();

                    if (errCount <= 0) {
                        slctdInvstgtn = slctdInvstgtn
                                + $('#invstgtnRow' + rndmNum + '_InvstgtnID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"								
                                + cnsltnID + "~"
                                + $('#invstgtnRow' + rndmNum + '_DocCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#invstgtnRow' + rndmNum + '_LabCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#invstgtnRow' + rndmNum + '_LabLoc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#invstgtnRow' + rndmNum + '_LabRslt').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + appntmntID + "~"
                                + $('#invstgtnRow' + rndmNum + '_RqstID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + doInhouse + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Investigation record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Investigation record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Lab Investigation?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Investigations...Please Wait...</p>',
        callback: function () {
            if(cnsltnID > 0){
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);  
            } else {
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 3, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
            }
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
                    grp: 14,
                    typ: 1,
                    pg: 3,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 4,
                    slctdInvstgtn: slctdInvstgtn
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delInvestigation(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#invstgtnRow' + rndmNum + '_InvstgtnID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#invstgtnRow' + rndmNum + '_InvstgtnID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Record?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this record?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Remove Record?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Investigation Record...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=5&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 4,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function saveNFinalizeInvestigation(lnkdVstId, lnkdSrvsTypeID, lnkdSrvsTypeCode, lnkdSrvsPrvdrGrpID, lnkdSrcCnsltnID, lnkdAppnmntDate)
{
    var slctdInvstgtn = "";
    var errCount = 0;
    var rcdCount = 0;
	
    var cnsltnID = typeof $('#cnsltnID').val() === undefined ? -1 : $('#cnsltnID').val();
    var appntmntID = typeof $('#appntmntID').val() === undefined ? -1 : $('#appntmntID').val();
    var refCheckInId = -1;

    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
    
    /**FINALIZE **/
    var frmAppntmntCmnts =  "";
    var docAdmsnCheckInDate = "";
    var docAdmsnCheckInNoOfDays = "";
    //var pName = typeof $("#patientNm").val() === "undefined" ? '' : $("#patientNm").val();
    //var pAppntmntNo = typeof $("#appntmntNo").val() === "undefined" ? '' : $("#appntmntNo").val();
    if(lnkdSrvsTypeCode === "IA-0001"){
        frmAppntmntCmnts = typeof $("#docAdmsnInstructions").val() === 'undefined' ? '' : $("#docAdmsnInstructions").val();
        docAdmsnCheckInDate = typeof $("#docAdmsnCheckInDate").val() === 'undefined' ? '' : $("#docAdmsnCheckInDate").val();
        docAdmsnCheckInNoOfDays = typeof $("#docAdmsnCheckInNoOfDays").val() === 'undefined' ? 1 : $("#docAdmsnCheckInNoOfDays").val();
    }
    
    var cnsltnAppntmnt_id = typeof $("#appntmntID").val() === 'undefined' ? '' : $("#appntmntID").val();
    
    var vstId = lnkdVstId;
    var frmCnsltnID = lnkdSrcCnsltnID;
    
    var frmAppntmntID = -1;
    var frmAppntmntDate = lnkdAppnmntDate;
    var frmSrvsTypeId  = lnkdSrvsTypeID;
    var frmPrvdrType = "G";
    var frmSrvsPrvdrId  =  lnkdSrvsPrvdrGrpID; 
    /**FINALIZE **/
	
    $('#invstgtnTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#invstgtnRow' + rndmNum + '_InvstgtnID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#invstgtnRow' + rndmNum + '_RqstNm').val() == "") {
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-color', 'red');
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-color', '#ccc');
                        $('#invstgtnRow' + rndmNum + '_RqstNm').css('border-width', '1px');
                    }
                    
                    var doInhouse = typeof $("input[name='invstgtnRow" + rndmNum + "_DoInhouse']:checked").val() === 'undefined' ? 'NO' : $("input[name='invstgtnRow" + rndmNum + "_DoInhouse']:checked").val();

                    if (errCount <= 0) {
                        slctdInvstgtn = slctdInvstgtn
                                + $('#invstgtnRow' + rndmNum + '_InvstgtnID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"								
                                + cnsltnID + "~"
                                + $('#invstgtnRow' + rndmNum + '_DocCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#invstgtnRow' + rndmNum + '_LabCmnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#invstgtnRow' + rndmNum + '_LabLoc').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#invstgtnRow' + rndmNum + '_LabRslt').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + appntmntID + "~"
                                + $('#invstgtnRow' + rndmNum + '_RqstID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + doInhouse + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
    if (errCount > 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Investigation record(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Investigation record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Finalize Investigation?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Investigations...Please Wait...</p>',
        callback: function () {
            if(cnsltnID > 0){
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);  
            } else {
                getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, 3, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
            }
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
                    grp: 14,
                    typ: 1,
                    pg: 3,
		    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 4.1,
                    slctdInvstgtn: slctdInvstgtn,
                    vstId: vstId,
                    frmAppntmntID: frmAppntmntID,   
                    frmAppntmntDate: frmAppntmntDate,   
                    frmSrvsTypeId: frmSrvsTypeId,
                    frmPrvdrType: frmPrvdrType,   
                    frmSrvsPrvdrId: frmSrvsPrvdrId,    
                    frmAppntmntCmnts: frmAppntmntCmnts, 
                    lnkdSrvsTypeCode:lnkdSrvsTypeCode,
                    frmCnsltnID: frmCnsltnID,
                    docAdmsnCheckInDate:docAdmsnCheckInDate,
                    docAdmsnCheckInNoOfDays: docAdmsnCheckInNoOfDays,
                    cnsltnAppntmnt_id: cnsltnAppntmnt_id
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

//VISIT
function saveVisit()
{	
    var vstId = typeof $('#vstId').val() === undefined ? -1 : $('#vstId').val();
    var prsnId = typeof $('#prsnId').val() === undefined ? -1 : $('#prsnId').val();
    var vstDate = typeof $("#vstDate").val() === 'undefined' ? '' : $("#vstDate").val();
    var cmnts = typeof $("#cmnts").val() === 'undefined' ? '' : $("#cmnts").val();
    var vstOption = typeof $("#vstOption").val() === 'undefined' ? 'Hospital Visit' : $("#vstOption").val();
    var billThisVisit =  typeof $("#billThisVisit").val() === 'undefined' ? 'Y' : $("#billThisVisit").val();
    var branchId =  typeof $("#branchId").val() === 'undefined' ? -1 : $("#branchId").val();
    var errCount= 0;
    
    if ($('#prsnId').val() === "-1" || $('#prsnId').val() === "undefined") {
            $('#prsnNm').css('border-color', 'red');
            $('#prsnNm').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#prsnNm').css('border-color', '#ccc');
            $('#prsnNm').css('border-width', '1px');
    }

    if (errCount > 0) {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted field(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Visit?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Visit...Please Wait...</p>',
        callback: function () {
            //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit & Appointment', 2, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
        }
    });
    
    
    var formData = new FormData();
    formData.append('grp', 14);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('pg', 2);
    formData.append('actyp', 1);
    formData.append('mdl', 'Clinic/Hospital');
    formData.append('vstId', vstId);
    formData.append('prsnId', prsnId);   
    formData.append('vstDate', vstDate);   
    formData.append('cmnts', cmnts);
    formData.append('vstOption', vstOption);   
    formData.append('billThisVisit', billThisVisit);    
    formData.append('branchId', branchId); 
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: formData,
                async:true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                   var msg = "";
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(data);

                            dialog.modal('hide');
                            //$('#myFormsModalLg').modal('hide');
                            getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Visit & Appointment', 2, 1, 'EDIT', obj.vstId, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 2, function () {
                                    msg = "Successfully!";
                                    box2 = bootbox.alert({
                                            size: "small",
                                            title: "Rhomicom Message",
                                            message: msg,
                                            callback: function () { /* your callback code */
                                            }
                                    });
                                    
                                    //$("#frmUpdate").val(1);
                            });
                    } else {

                            msg = data;
                            dialog.modal('hide');
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delVisit(vstId)
{
 
    var pKeyID = vstId;

    var dialog = bootbox.confirm({
        title: 'Delete Visit?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this visit?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Visit?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Visit...Please Wait...</p>',
                    callback: function () {

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
                                    grp: 14,
                                    typ: 1,
                                    pg: 2,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 1,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    getHospData('', '#allmodules', 'grp=14&typ=1&pg=2&mdl=Clinic/Hospital');
                                    dialog1.find('.bootbox-body').html(result1);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
                        bootbox.alert({
                                size: "small",
                                title: "Rhomicom Message",
                                message: "<span style='color:red;'><b><i>Failed to delete Visit</i></b></span>",
                                callback: function () { /* your callback code */
                                }
                        });
                        return false;
                    }
                });
            }
        }
    });
}

//VISIT APPOINTMENT
function saveVisitAppointment(lnkdSrvsTypeCode)
{	
    var vstId = -1;
    var frmCnsltnID = -1;
    
    if (typeof lnkdSrvsTypeCode === 'undefined' || lnkdSrvsTypeCode === null || lnkdSrvsTypeCode === '')
    {
        lnkdSrvsTypeCode = '';
        vstId = typeof $('#vstId').val() === undefined ? -1 : $('#vstId').val();
    } else {
        vstId = typeof $('#frmVstID').val() === undefined ? -1 : $('#frmVstID').val();
        frmCnsltnID = typeof $('#frmCnsltnID').val() === undefined ? -1 : $('#frmCnsltnID').val();
    }
        
    var frmAppntmntID = typeof $('#frmAppntmntID').val() === undefined ? -1 : $('#frmAppntmntID').val();
    var frmAppntmntDate = typeof $("#frmAppntmntDate").val() === 'undefined' ? '' : $("#frmAppntmntDate").val();
    var frmSrvsTypeId  = typeof $("#frmSrvsTypeId ").val() === 'undefined' ? -1 : $("#frmSrvsTypeId ").val();
    var frmPrvdrType = typeof $("#frmPrvdrType").val() === 'undefined' ? 'G' : $("#frmPrvdrType").val();
    var frmSrvsPrvdrId  =  typeof $("#frmSrvsPrvdrId ").val() === 'undefined' ? -1 : $("#frmSrvsPrvdrId ").val();
    var frmAppntmntCmnts =  typeof $("#frmAppntmntCmnts").val() === 'undefined' ? -1 : $("#frmAppntmntCmnts").val();
    var errCount= 0;
    
    if ($('#frmSrvsTypeId').val() === "-1" || $('#frmSrvsTypeId').val() === "undefined") {
            $('#frmSrvsType').css('border-color', 'red');
            $('#frmSrvsType').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmSrvsType').css('border-color', '#ccc');
            $('#frmSrvsType').css('border-width', '1px');
    }
	if ($('#frmPrvdrType').val() === "" || $('#frmPrvdrType').val() === "undefined") {
            $('#frmPrvdrType').css('border-color', 'red');
            $(frmPrvdrType).css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $(frmPrvdrType).css('border-color', '#ccc');
            $('#frmPrvdrType').css('border-width', '1px');
    }
    if ($('#frmSrvsPrvdr').val() === "" || $('#frmSrvsPrvdrId').val() === "undefined") {
            $('#frmSrvsPrvdr').css('border-color', 'red');
            $('#frmSrvsPrvdr').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmSrvsPrvdr').css('border-color', '#ccc');
            $('#frmSrvsPrvdr').css('border-width', '1px');
    }

    if (errCount > 0) {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted field(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Appointment?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Appointment...Please Wait...</p>',
        callback: function () {
            //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit & Appointment', 2, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
        }
    });
    
    
    var formData = new FormData();
    formData.append('grp', 14);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('pg', 2);
    formData.append('actyp', 2);
    formData.append('mdl', 'Clinic/Hospital');
    formData.append('vstId', vstId);
    formData.append('frmAppntmntID', frmAppntmntID);   
    formData.append('frmAppntmntDate', frmAppntmntDate);   
    formData.append('frmSrvsTypeId', frmSrvsTypeId);
    formData.append('frmPrvdrType', frmPrvdrType);   
    formData.append('frmSrvsPrvdrId', frmSrvsPrvdrId);    
    formData.append('frmAppntmntCmnts', frmAppntmntCmnts); 
    formData.append('lnkdSrvsTypeCode',lnkdSrvsTypeCode);
    formData.append('frmCnsltnID', frmCnsltnID);
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: formData,
                async:true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                   var msg = "";
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(data);

                            dialog.modal('hide');
                            $('#myFormsModaly').modal('hide');
                            
                            if (typeof lnkdSrvsTypeCode === 'undefined' || lnkdSrvsTypeCode === null || lnkdSrvsTypeCode === '')
                            {
                                getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Edit Appointment', 2, 1, 'EDIT', vstId, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 2, function () {
                                        msg = "Appointment Scheduled Successfully!";
                                        box2 = bootbox.alert({
                                                size: "small",
                                                title: "Rhomicom Message",
                                                message: msg,
                                                callback: function () { /* your callback code */
                                                }
                                        });
                                });
                            } else {
                                viewAppointmentLinesFormLnkdAppntmnts(obj.frmAppntmntID, 'Add Appointment Request','EDIT', lnkdSrvsTypeCode ,vstId, frmCnsltnID, function(){                                  
                                    msg = "Appointment Scheduled Successfully!";
                                    box2 = bootbox.alert({
                                            size: "small",
                                            title: "Rhomicom Message",
                                            message: msg,
                                            callback: function () { /* your callback code */
                                            }
                                    });
                                });
                                
                            }
                            
                            //If value 1 then reload visits landing page
                            //$("#frmUpdate").val(1);
                            
                    } else {

                            msg = data;
                            dialog.modal('hide');
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delVisitAppointment(appntmntID)
{
 
    var pKeyID = appntmntID;
	
    var vstId = typeof $('#vstId').val() === undefined ? -1 : $('#vstId').val();
    var dialog = bootbox.confirm({
        title: 'Delete Appointment?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this appointment?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Appointment?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Appointment...Please Wait...</p>',
                    callback: function () {

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
                                    grp: 14,
                                    typ: 1,
                                    pg: 2,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 2,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
				    dialog1.modal('hide');
                                    getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Edit Appointment', 2, 1, 'EDIT', vstId, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 2, function () {
                                        var msg = "Successfully!";
                                        box2 = bootbox.alert({
                                            size: "small",
                                            title: "Rhomicom Message",
                                            message: msg,
                                            callback: function () { /* your callback code */
                                            }
                                        });
                                    });
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
                        bootbox.alert({
                                size: "small",
                                title: "Rhomicom Message",
                                message: "<span style='color:red;'><b><i>Failed to delete Appointment</i></b></span>",
                                callback: function () { /* your callback code */
                                }
                        });
                        return false;
                    }
                });
            }
        }
    });
}

//VITALS
function saveVitals()
{	
    var frmAppntmntID = typeof $('#frmAppntmntID').val() === undefined ? -1 : $('#frmAppntmntID').val();
    var frmVitalsID = typeof $('#frmVitalsID').val() === undefined ? -1 : $('#frmVitalsID').val();
    var frmVitalsWeight = typeof $("#frmVitalsWeight").val() === 'undefined' ? '' : $("#frmVitalsWeight").val();
    var frmVitalsHeight = typeof $("#frmVitalsHeight").val() === 'undefined' ? '' : $("#frmVitalsHeight").val();
	var frmVitalsBMI =  typeof $("#frmVitalsBMI").val() === 'undefined' ? '' : $("#frmVitalsBMI").val();
	var frmVitalsBMIStatus =  typeof $("#frmVitalsBMIStatus").val() === 'undefined' ? '' : $("#frmVitalsBMIStatus").val();
    var frmVitalsBPSystolic = typeof $("#frmVitalsBPSystolic").val() === 'undefined' ? '' : $("#frmVitalsBPSystolic").val();
    var frmVitalsBPDiastolic =  typeof $("#frmVitalsBPDiastolic").val() === 'undefined' ? '' : $("#frmVitalsBPDiastolic").val();
	var frmVitalsPBStatus =  typeof $("#frmVitalsPBStatus").val() === 'undefined' ? '' : $("#frmVitalsPBStatus").val();
	var frmVitalsPulse =  typeof $("#frmVitalsPulse").val() === 'undefined' ? '' : $("#frmVitalsPulse").val();
	var frmVitalsBodyTemp =  typeof $("#frmVitalsBodyTemp").val() === 'undefined' ? '' : $("#frmVitalsBodyTemp").val();
	var frmVitalsTempLoc =  typeof $("#frmVitalsTempLoc").val() === 'undefined' ? '' : $("#frmVitalsTempLoc").val();
	var frmVitalsRsprtn =  typeof $("#frmVitalsRsprtn").val() === 'undefined' ? '' : $("#frmVitalsRsprtn").val();
	var frmVitalsOxygenStrtn =  typeof $("#frmVitalsOxygenStrtn").val() === 'undefined' ? '' : $("#frmVitalsOxygenStrtn").val();
	var frmVitalsHeadCircm =  typeof $("#frmVitalsHeadCircm").val() === 'undefined' ? '' : $("#frmVitalsHeadCircm").val();
	var frmVitalsWaistCircm =  typeof $("#frmVitalsWaistCircm").val() === 'undefined' ? '' : $("#frmVitalsWaistCircm").val();
	var frmVitalsBowelAction =  typeof $("#frmVitalsBowelAction").val() === 'undefined' ? '' : $("#frmVitalsBowelAction").val();
	var frmVitalsCmnts =  typeof $("#frmVitalsCmnts").val() === 'undefined' ? '' : $("#frmVitalsCmnts").val();
	
	var frmVitalsPatientNm = typeof $("#frmVitalsPatientNm").val() === 'undefined' ? '' : $("#frmVitalsPatientNm").val();
    var frmVitalsAppntmntNo = typeof $("#frmVitalsAppntmntNo").val() === 'undefined' ? '' : $("#frmVitalsAppntmntNo").val();
	
	var errCount= 0;
    
    if ($('#frmVitalsWeight').val() === "" || $('#frmVitalsWeight').val() === "undefined") {
            $('#frmVitalsWeight').css('border-color', 'red');
            $('#frmVitalsWeight').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsWeight').css('border-color', '#ccc');
            $('#frmVitalsWeight').css('border-width', '1px');
    }
	if ($('#frmVitalsHeight').val() === "" || $('#frmVitalsHeight').val() === "undefined") {
            $('#frmVitalsHeight').css('border-color', 'red');
            $('#frmVitalsHeight').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsHeight').css('border-color', '#ccc');
            $('#frmVitalsHeight').css('border-width', '1px');
    }
	if ($('#frmVitalsBPSystolic').val() === "" || $('#frmVitalsBPSystolic').val() === "undefined") {
            $('#frmVitalsBPSystolic').css('border-color', 'red');
            $('#frmVitalsBPSystolic').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsBPSystolic').css('border-color', '#ccc');
            $('#frmVitalsBPSystolic').css('border-width', '1px');
    }
	if ($('#frmVitalsBPDiastolic').val() === "" || $('#frmVitalsBPDiastolic').val() === "undefined") {
            $('#frmVitalsBPDiastolic').css('border-color', 'red');
            $('#frmVitalsBPDiastolic').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsBPDiastolic').css('border-color', '#ccc');
            $('#frmVitalsBPDiastolic').css('border-width', '1px');
    }
	if ($('#frmVitalsPulse').val() === "" || $('#frmVitalsPulse').val() === "undefined") {
            $('#frmVitalsPulse').css('border-color', 'red');
            $('#frmVitalsPulse').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsPulse').css('border-color', '#ccc');
            $('#frmVitalsPulse').css('border-width', '1px');
    }
	if ($('#frmVitalsBodyTemp').val() === "" || $('#frmVitalsBodyTemp').val() === "undefined") {
            $('#frmVitalsBodyTemp').css('border-color', 'red');
            $('#frmVitalsBodyTemp').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsBodyTemp').css('border-color', '#ccc');
            $('#frmVitalsBodyTemp').css('border-width', '1px');
    }
	if ($('#frmVitalsTempLoc').val() === "" || $('#frmVitalsTempLoc').val() === "undefined") {
            $('#frmVitalsTempLoc').css('border-color', 'red');
            $('#frmVitalsTempLoc').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsTempLoc').css('border-color', '#ccc');
            $('#frmVitalsTempLoc').css('border-width', '1px');
    }
	if ($('#frmVitalsBowelAction').val() === "" || $('#frmVitalsBowelAction').val() === "undefined") {
            $('#frmVitalsBowelAction').css('border-color', 'red');
            $('#frmVitalsBowelAction').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmVitalsBowelAction').css('border-color', '#ccc');
            $('#frmVitalsBowelAction').css('border-width', '1px');
    }	
	

    if (errCount > 0) {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted field(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Vitals?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Vitals...Please Wait...</p>',
        callback: function () {
            //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit & Appointment', 3, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
        }
    });
    
    
    var formData = new FormData();
    formData.append('grp', 14);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('pg', 3);
    formData.append('actyp', 1);
    formData.append('mdl', 'Clinic/Hospital');
    formData.append('frmAppntmntID', frmAppntmntID);
    formData.append('frmVitalsID', frmVitalsID);   
    formData.append('frmVitalsWeight', frmVitalsWeight);   
    formData.append('frmVitalsHeight', frmVitalsHeight);
    formData.append('frmVitalsBMI', frmVitalsBMI);  
    formData.append('frmVitalsBMIStatus', frmVitalsBMIStatus);  
    formData.append('frmVitalsBPSystolic', frmVitalsBPSystolic);     
    formData.append('frmVitalsBPDiastolic', frmVitalsBPDiastolic);
	formData.append('frmVitalsPBStatus',frmVitalsPBStatus);
	formData.append('frmVitalsPulse',frmVitalsPulse);
	formData.append('frmVitalsBodyTemp',frmVitalsBodyTemp);
	formData.append('frmVitalsTempLoc',frmVitalsTempLoc);
	formData.append('frmVitalsRsprtn',frmVitalsRsprtn);
	formData.append('frmVitalsOxygenStrtn',frmVitalsOxygenStrtn);
	formData.append('frmVitalsHeadCircm',frmVitalsHeadCircm);
	formData.append('frmVitalsWaistCircm',frmVitalsWaistCircm);
	formData.append('frmVitalsBowelAction',frmVitalsBowelAction);
	formData.append('frmVitalsCmnts',frmVitalsCmnts);	
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: formData,
                async:true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                   var msg = "";
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(data);

                            dialog.modal('hide');
                            //$('#myFormsModalLg').modal('hide');
                            getHospDetailsForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', 'Vitals of'+frmVitalsPatientNm+' - '+frmVitalsAppntmntNo, 3, 2, 'EDIT', frmAppntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 3, function () {
                                    msg = "Successfully!";
                                    box2 = bootbox.alert({
                                            size: "small",
                                            title: "Rhomicom Message",
                                            message: msg,
                                            callback: function () { /* your callback code */
                                            }
                                    });
                            });
                    } else {

                            msg = data;
                            dialog.modal('hide');
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delVitals(frmAppntmntID)
{
 
    var pKeyID = frmAppntmntID;

    var dialog = bootbox.confirm({
        title: 'Delete Vitals?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this visit?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Vitals?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Vitals...Please Wait...</p>',
                    callback: function () {

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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 1,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    getHospData('', '#allmodules', 'grp=14&typ=1&pg=3&mdl=Clinic/Hospital');
                                    dialog1.find('.bootbox-body').html(result1);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
                        bootbox.alert({
                                size: "small",
                                title: "Rhomicom Message",
                                message: "<span style='color:red;'><b><i>Failed to delete Vitals</i></b></span>",
                                callback: function () { /* your callback code */
                                }
                        });
                        return false;
                    }
                });
            }
        }
    });
}

function checkInVitalsAppointment(appntmntID, vwtyp)
{
    var patientNm = typeof $("#frmVitalsPatientNm").val() === 'undefined' ? '' : $("#frmVitalsPatientNm").val();
    var appntmntNo = typeof $("#frmVitalsAppntmntNo").val() === 'undefined' ? '' : $("#frmVitalsAppntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
    var dialog = bootbox.confirm({
        title: 'Start Processing?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CHECK-IN</span> this Patent?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Check-In Patient?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Starting...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 3,
                                mdl: 'Clinic/Hospital',
                                q: 'CHECK-IN',
                                actyp: 1,
                                PKeyID: appntmntID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', dialogTitle, 3, vwtyp, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

//CONSULTATION
//VITALS
function saveConsultation()
{	
	var cnsltnID = typeof $('#cnsltnID').val() === undefined ? -1 : $('#cnsltnID').val();
    var appntmntID = typeof $('#appntmntID').val() === undefined ? -1 : $('#appntmntID').val();
    var refCheckInId = -1;

    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;

    var patientCmplnt = typeof $("#patientCmplnt").val() === 'undefined' ? '' : $("#patientCmplnt").val();
    var physicalExam = typeof $("#physicalExam").val() === 'undefined' ? '' : $("#physicalExam").val();
	var cnsltnCmnts =  typeof $("#cnsltnCmnts").val() === 'undefined' ? '' : $("#cnsltnCmnts").val();
        
    var docAdmsnInstructions = typeof $("#docAdmsnInstructions").val() === 'undefined' ? '' : $("#docAdmsnInstructions").val();
    var docAdmsnCheckInDate = typeof $("#docAdmsnCheckInDate").val() === 'undefined' ? '' : $("#docAdmsnCheckInDate").val();
    var docAdmsnCheckInNoOfDays =  typeof $("#docAdmsnCheckInNoOfDays").val() === 'undefined' ? 0 : $("#docAdmsnCheckInNoOfDays").val();
	
	var diagCmnts = '';
	
	var errCount= 0;
	var rcdCount = 0;
    var slctdDiagnosis  = ""
    
    if ($('#patientCmplnt').val() === "" || $('#patientCmplnt').val() === "undefined") {
            $('#patientCmplnt').css('border-color', 'red');
            $('#patientCmplnt').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#patientCmplnt').css('border-color', '#ccc');
            $('#patientCmplnt').css('border-width', '1px');
    }
    if ($('#docAdmsnCheckInDate').val() !== "" && ($('#docAdmsnCheckInNoOfDays').val() === "" || parseInt($('#docAdmsnCheckInNoOfDays').val()) == 0)) {
            $('#docAdmsnCheckInNoOfDays').css('border-color', 'red');
            $('#docAdmsnCheckInNoOfDays').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#docAdmsnCheckInNoOfDays').css('border-color', '#ccc');
            $('#docAdmsnCheckInNoOfDays').css('border-width', '1px');
    }
    if ($('#docAdmsnCheckInDate').val() !== "" && $('#docAdmsnInstructions').val() === "") {
            $('#docAdmsnInstructions').css('border-color', 'red');
            $('#docAdmsnInstructions').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#docAdmsnInstructions').css('border-color', '#ccc');
            $('#docAdmsnInstructions').css('border-width', '1px');
    }
	
	$('#diagnosisTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#diagnosisRow' + rndmNum + '_DiagID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    if ($('#diagnosisRow' + rndmNum + '_DiseaseNm').val() == "") {
                        $('#diagnosisRow' + rndmNum + '_DiseaseNm').css('border-color', 'red');
                        $('#diagnosisRow' + rndmNum + '_DiseaseNm').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#diagnosisRow' + rndmNum + '_DiseaseNm').css('border-color', '#ccc');
                        $('#diagnosisRow' + rndmNum + '_DiseaseNm').css('border-width', '1px');
                    }
                    
                    if (errCount <= 0) {
                        slctdDiagnosis = slctdDiagnosis
                                + $('#diagnosisRow' + rndmNum + '_DiagID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#diagnosisRow' + rndmNum + '_DiseaseID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"								
                                + cnsltnID + "~"
								+ diagCmnts + "|";
                        rcdCount = rcdCount + 1;
                    }

                }
            }
        }
    });
	

    if (errCount > 0) {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted field(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    } else if (rcdCount <= 0) {
        box2 = bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please provide at lease One Diagnosis record</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Consultation?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Consultation...Please Wait...</p>',
        callback: function () {
            //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit & Appointment', 3, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
        }
    });
    
    
    var formData = new FormData();
    formData.append('grp', 14);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('pg', 3);
    formData.append('actyp', 2);
    formData.append('mdl', 'Clinic/Hospital');
    formData.append('cnsltnID', cnsltnID);
    formData.append('appntmntID', appntmntID);
    formData.append('patientCmplnt', patientCmplnt);   
    formData.append('physicalExam', physicalExam);
    formData.append('cnsltnCmnts', cnsltnCmnts);  
    formData.append('docAdmsnInstructions', docAdmsnInstructions);   
    formData.append('docAdmsnCheckInDate', docAdmsnCheckInDate);
    formData.append('docAdmsnCheckInNoOfDays', docAdmsnCheckInNoOfDays);  
    formData.append('slctdDiagnosis', slctdDiagnosis);  
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: formData,
                async:true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                   var msg = "";
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(data);

                            dialog.modal('hide');
                            //$('#myFormsModalLg').modal('hide');
                            getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', 'Consultation of'+patientNm+' - '+appntmntNo, 3, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 3, function () {
                                    msg = obj.dspMsg;
                                    box2 = bootbox.alert({
                                            size: "small",
                                            title: "Rhomicom Message",
                                            message: msg,
                                            callback: function () { /* your callback code */
                                            }
                                    });
                            });
                    } else {

                            msg = data;
                            dialog.modal('hide');
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delConsultation(frmAppntmntID)
{
 
    var pKeyID = frmAppntmntID;

    var dialog = bootbox.confirm({
        title: 'Delete Consultation?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Consultation?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Consultation?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Consultation...Please Wait...</p>',
                    callback: function () {

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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 2,
                                    PKeyID: pKeyID
                                },
                                success: function (result1) {
                                    getHospData('', '#allmodules', 'grp=14&typ=1&pg=3&mdl=Clinic/Hospital');
                                    dialog1.find('.bootbox-body').html(result1);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
                        bootbox.alert({
                                size: "small",
                                title: "Rhomicom Message",
                                message: "<span style='color:red;'><b><i>Failed to delete Consultation</i></b></span>",
                                callback: function () { /* your callback code */
                                }
                        });
                        return false;
                    }
                });
            }
        }
    });
}

function newAppointment(vstId, elementID, elementBodyID, elementTitleID, srcPgNo, svsTypCode, srcCnsltnID){
    if (typeof svsTypCode === 'undefined' || svsTypCode === null || svsTypCode === '')
    {  
        $('#' + elementTitleID).html('');
        $('#' + elementBodyID).html('');    
        $('#' + elementID).modal('toggle');
        getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Visits & Appointment', 2, 1, 'EDIT', vstId, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);
    } else {
        viewAppointmentLinesFormLnkdAppntmnts(-1, 'Add Appointment Request','ADD', svsTypCode ,vstId, srcCnsltnID);
    }
}

function createLinkedCnsltnAppointment(lnkdVstId, lnkdSrvsTypeID, lnkdSrvsTypeCode, lnkdSrvsPrvdrGrpID, lnkdSrcCnsltnID, lnkdAppnmntDate)
{	   
    var frmAppntmntCmnts =  "";
    var docAdmsnCheckInDate = "";
    var docAdmsnCheckInNoOfDays = "";
    var pName = typeof $("#patientNm").val() === "undefined" ? '' : $("#patientNm").val();
    var pAppntmntNo = typeof $("#appntmntNo").val() === "undefined" ? '' : $("#appntmntNo").val();
    if(lnkdSrvsTypeCode === "IA-0001"){
        frmAppntmntCmnts = typeof $("#docAdmsnInstructions").val() === 'undefined' ? '' : $("#docAdmsnInstructions").val();
        docAdmsnCheckInDate = typeof $("#docAdmsnCheckInDate").val() === 'undefined' ? '' : $("#docAdmsnCheckInDate").val();
        docAdmsnCheckInNoOfDays = typeof $("#docAdmsnCheckInNoOfDays").val() === 'undefined' ? 1 : $("#docAdmsnCheckInNoOfDays").val();
    }
    
    var cnsltnAppntmnt_id = typeof $("#appntmntID").val() === 'undefined' ? '' : $("#appntmntID").val();
    
    var vstId = lnkdVstId;
    var frmCnsltnID = lnkdSrcCnsltnID;
    
    var frmAppntmntID = -1;
    var frmAppntmntDate = lnkdAppnmntDate;
    var frmSrvsTypeId  = lnkdSrvsTypeID;
    var frmPrvdrType = "G";
    var frmSrvsPrvdrId  =  lnkdSrvsPrvdrGrpID; 
    
    var dialog = bootbox.alert({
        title: 'Finalize?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Finalizing...Please Wait...</p>',
        callback: function () {}
    });
    
    var formData = new FormData();
    formData.append('grp', 14);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('pg', 2);
    formData.append('actyp', 2);
    formData.append('mdl', 'Clinic/Hospital');
    formData.append('vstId', vstId);
    formData.append('frmAppntmntID', frmAppntmntID);   
    formData.append('frmAppntmntDate', frmAppntmntDate);   
    formData.append('frmSrvsTypeId', frmSrvsTypeId);
    formData.append('frmPrvdrType', frmPrvdrType);   
    formData.append('frmSrvsPrvdrId', frmSrvsPrvdrId);    
    formData.append('frmAppntmntCmnts', frmAppntmntCmnts); 
    formData.append('lnkdSrvsTypeCode',lnkdSrvsTypeCode);
    formData.append('frmCnsltnID', frmCnsltnID);
    formData.append('docAdmsnCheckInDate',docAdmsnCheckInDate);
    formData.append('docAdmsnCheckInNoOfDays', docAdmsnCheckInNoOfDays);
    formData.append('cnsltnAppntmnt_id', cnsltnAppntmnt_id);
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: formData,
                async:true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                   var msg = "";
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(data);
                            dialog.modal('hide');
                            msg = "Finalized Successfully!";
                            
                            getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', 'Medical Consultation for '+pName+' - '+pAppntmntNo, 3, 1, 'EDIT', cnsltnAppntmnt_id, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', 3, function(){
                                box2 = bootbox.alert({
                                        size: "small",
                                        title: "Rhomicom Message",
                                        message: msg,
                                        callback: function () { /* your callback code */
                                        }
                                });
                            });

                            //reload form

                            //HIDE FINALIZE BUTTON AFTER FINALIZE
                            /*if(lnkdSrvsTypeCode === "IA-0001"){
                                 $("#inhouseAdmsnBtn").css('display','none');
                            } else if(lnkdSrvsTypeCode === "PH-0001"){
                                 $("#medicationBtn").css('display','none');
                            } else if(lnkdSrvsTypeCode === "LI-0001"){
                                $("#invstgtnBtn").css('display','none');
                            }*/

                    } else {

                            msg = data;
                            dialog.modal('hide');
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getHstrcPatientHospDetailsForm(patientPrsnID, elementID, modalBodyID, titleElementID, formTitle, pgNo, vtyp, vtypActn, pKeyID, listTableID, rowID, srcPgNo, callBackFunc)
{
    
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }  
    
    if (typeof srcPgNo === 'undefined' || srcPgNo === null)
    {
        srcPgNo = 2;
    }

    if (!$.fn.DataTable.isDataTable('#' + listTableID)) {
        var table1 = $('#' + listTableID).DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#' + listTableID + ' tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        $('#' + listTableID + ' tbody').on('mouseenter', 'tr', function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
    }

    var p_statusTitle = "Incomplete";
    if (vtypActn == "EDIT") {

    }


    $body = $("body");
    $body.addClass("mdlloadingDiag");
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        /*code for IE7+, Firefox, Chrome, Opera, Safari*/
        xmlhttp = new XMLHttpRequest();
    } else
    {
        /*code for IE6, IE5*/
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            $('#' + titleElementID).html(formTitle);
            $('#' + modalBodyID).html(xmlhttp.responseText);
            /*$('.modal-content').resizable({
             //alsoResize: ".modal-dialog",
             minHeight: 600,
             minWidth: 300
             });*/
            /*$('.modal-dialog').draggable();*/
           
            
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
            
            
            $('#' + elementID).on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            
            
            $('#'+elementID).off('hidden.bs.modal');
            $('#'+elementID).one('hidden.bs.modal', function (e) {
                $('#' + modalBodyID).html('');
                $(e.currentTarget).unbind();
            });
            $body.removeClass("mdlloadingDiag");
            //$('#' + elementID).modal({backdrop: 'static',keyboard:false});
            $('#' + elementID).modal({backdrop: 'static',keyboard:false});
            $body = $("body");
            
             $('#allOtherInputData99').val('0');
            if (!$.fn.DataTable.isDataTable('#diagnosisTable')) {
                var table2 = $('#diagnosisTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#diagnosisTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#invstgtnTable')) {
                var table2 = $('#invstgtnTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#invstgtnTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allHstrcMdclCnsltnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allHstrcMdclCnsltnsTable')) {
                table2 = $('#allHstrcMdclCnsltnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allHstrcMdclCnsltnsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allHstrcMdclCnsltnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allHstrcMdclCnsltnPersonsTable')) {
                var table3 = $('#allHstrcMdclCnsltnPersonsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allHstrcMdclCnsltnPersonsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allHstrcMdclCnsltnsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allHstrcMdclCnsltnsRow' + rndmNum + '_HstrcMdclCnsltnID').val() === 'undefined' ? '%' : $('#allHstrcMdclCnsltnsRow' + rndmNum + '_HstrcMdclCnsltnID').val();
                getOneHstrcPatientDetail(pKeyID, 1);
            });
            $('#allHstrcMdclCnsltnsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
            $('#allOtherInputData99').val(0);
         
        //htBody.removeClass("mdlloading");
            /*$('#myFormsModalLg').off('hidden.bs.modal');
            $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
                if (srcCaller === 'NORMAL') {
                    getAllRpts('Clear', '#allmodules', 'grp=9&typ=1&pg=1&vtyp=0');
                } else if (srcCaller === 'ALL_RUNS') {
                    getAllPrcsRuns('', '#allmodules', 'grp=9&typ=1&pg=5&vtyp=0');
                }
                $(e.currentTarget).unbind();
            });*/
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="hstrctabajxrptdet"]').off('click');
            $('[data-toggle="hstrctabajxrptdet"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = dttrgt;
                if (targ.indexOf('cnsltnMainTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').removeClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('vitalsTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').removeClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('inHouseAdmsnTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').removeClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('medicationTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').removeClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('invstgtnTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').removeClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('prfBCOPAddPrsnDataEDT') >= 0) {
                    var srcTyp = typeof $("#formTypeInpt").val() === 'undefined' ? '' : $("#formTypeInpt").val();
    
                    if(srcTyp === ''){
                        openATab(targ, linkArgs);
                    } 
                    //openATab(targ, linkArgs);
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#prfBCOPAddPrsnDataEDTPage').removeClass('hideNotice');
                }
                return;
            });
            
            $(document).ready(function () {
                /*$('#' + formElementID).submit(function (e) {
                 e.preventDefault();
                 return false;
                 });*/

                callBackFunc();
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=14&typ=1&pg=" + pgNo + "&mdl=Clinic/Hospital&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID+"&srcPgNo"+srcPgNo+"&patientPrsnID="+patientPrsnID);
}

function getOneHstrcPatientDetail(pKeyID, vwtype)
{
    //alert('Hi');
    var lnkArgs = 'grp=14&typ=1&pg=101&vtyp=1&mdl=Clinic/Hospital&sbmtdHstrcMdclCnsltnID=' + pKeyID;
    //doAjaxWthCallBck(linkArgs, elementID, actionAfter, titleMsg, titleElementID, modalBodyID, rqstdCallBack)
    $body = $("body");
    //$body.addClass("mdlloadingDiag");
    //alert("On");
    $("#custoverlay").css('display','block'); 
    doAjaxWthCallBck(lnkArgs, 'allHstrcMdclCnsltnsDetailInfo', '', '', '', '', function () {
        $(document).ready(function () {
            $("#custoverlay").css('display','none'); 
            //alert("Off");
            //$body.removeClass("mdlloadingDiag");
            $('[data-toggle="tooltip"]').tooltip();
            var table2 = $('#allHstrcMdclCnsltnPersonsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allHstrcMdclCnsltnPersonsTable').wrap('<div class="dataTables_scroll"/>');
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="hstrctabajxrptdet"]').off('click');
            $('[data-toggle="hstrctabajxrptdet"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = dttrgt;
                if (targ.indexOf('cnsltnMainTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').removeClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('vitalsTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').removeClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('inHouseAdmsnTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').removeClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('medicationTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').removeClass('hideNotice');
                    $('#invstgtnTbPageHstrc').addClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('invstgtnTbPageHstrc') >= 0) {
                    $('#cnsltnMainTbPageHstrc').addClass('hideNotice');
                    $('#vitalsTbPageHstrc').addClass('hideNotice');
                    $('#inHouseAdmsnTbPageHstrc').addClass('hideNotice');
                    $('#medicationTbPageHstrc').addClass('hideNotice');
                    $('#invstgtnTbPageHstrc').removeClass('hideNotice');
                    $('#rptAlwdRolesTbPageHstrc').addClass('hideNotice');
                } else if (targ.indexOf('prfBCOPAddPrsnDataEDT') >= 0) {
                    var srcTyp = typeof $("#formTypeInpt").val() === 'undefined' ? '' : $("#formTypeInpt").val();
    
                    if(srcTyp === ''){
                        openATab(targ, linkArgs);
                    } 
                    //openATab(targ, linkArgs);
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#prfBCOPAddPrsnDataEDTPage').removeClass('hideNotice');
                }
                return;
            });
        });
    });
}

function getHstrcPatientHstrcMdclCnsltns(patientPrsnID, patientNm, actionText, slctr, linkArgs, pKeyID)
{
    var srchFor = typeof $("#allHstrcMdclCnsltnsSrchFor").val() === 'undefined' ? '%' : $("#allHstrcMdclCnsltnsSrchFor").val();
    var srchIn = typeof $("#allHstrcMdclCnsltnsSrchIn").val() === 'undefined' ? 'Both' : $("#allHstrcMdclCnsltnsSrchIn").val();
    var pageNo = typeof $("#allHstrcMdclCnsltnsPageNo").val() === 'undefined' ? 1 : $("#allHstrcMdclCnsltnsPageNo").val();
    var limitSze = typeof $("#allHstrcMdclCnsltnsDsplySze").val() === 'undefined' ? 15 : $("#allHstrcMdclCnsltnsDsplySze").val();
    var sortBy = typeof $("#allHstrcMdclCnsltnsSortBy").val() === 'undefined' ? '' : $("#allHstrcMdclCnsltnsSortBy").val();
    var isEnabled = $('#allHstrcMdclCnsltnsIsEnabled:checked').length > 0;
    
    var qStrtDte = typeof $("#allHstrcMdclCnsltnsStrtDate").val() === 'undefined' ? '' :  $("#allHstrcMdclCnsltnsStrtDate").val();
    var qEndDte = typeof $("#allHstrcMdclCnsltnsEndDate").val() === 'undefined' ? '' :  $("#allHstrcMdclCnsltnsEndDate").val();

    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }

    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&isEnabled=" + isEnabled +"&qStrtDte="+qStrtDte + "&qEndDte="+qEndDte;
    $("#custoverlay").css('display','block');
    getHstrcPatientHospDetailsForm(patientPrsnID, 'myFormsModalLgZH', 'myFormsModalLgZHBody', 'myFormsModalLgZHTitle', 'Historic Appointments for '+patientNm, 101, 0, 'VIEW', pKeyID, 'allHstrcMdclCnsltnsTable', 'allHstrcMdclCnsltnsTblAddRow1', 3, function(){
        $("#custoverlay").css('display','none');
    });
    //openATab(slctr, linkArgs);
}

function checkOutVitalsAppointment(appntmntID, vwtyp)
{
    var patientNm = typeof $("#frmVitalsPatientNm").val() === 'undefined' ? '' : $("#frmVitalsPatientNm").val();
    var appntmntNo = typeof $("#frmVitalsAppntmntNo").val() === 'undefined' ? '' : $("#frmVitalsAppntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
    var dialog = bootbox.confirm({
        title: 'Transfer Vitals Data',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">TRANSFER</span> the vitals to the Doctor and Close the Appointment?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Check-Out Appointment?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Transfering Vitals and Closing Appointment...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 3,
                                mdl: 'Clinic/Hospital',
                                q: 'CHECK-OUT',
                                actyp: 1,
                                PKeyID: appntmntID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', dialogTitle, 3, vwtyp, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

//ADDITIONAL SERVICE DATA
function loadSrvsExtrData()
{
    var srcType = typeof $("#formSrcType").val() === 'undefined' ? '--Please Select--' : $("#formSrcType").val();
    openATab('#allmodules', 'grp=14&typ=1&pg=102&vtyp=4&srcType=' + srcType);
}

function saveSrvsExtrDataCol(slctr, linkArgs)
{
    var slctdExtrDataCols = "";
    var isVld = true;
    var errMsg = "";
    var srcType = typeof $("#formSrcType").val() === 'undefined' ? '' : $("#formSrcType").val();
    if (srcType == "") {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "No Service Defaulted",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    $('#extrSrvsDtColsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var fieldLbl = $('#extrSrvsDtColsRow' + rndmNum + '_FieldLbl').val();
                var colNum = $('#extrSrvsDtColsRow' + rndmNum + '_ColNum').val();
                if (typeof $('#extrSrvsDtColsRow' + rndmNum + '_ColNum').val() === 'undefined')
                {
                    isVld = false;
                }
                if (colNum.trim() === '')
                {
                    isVld = false;
                } else {
                    if (fieldLbl.trim() !== '') {
                        var dtTyp = $('#extrSrvsDtColsRow' + rndmNum + '_DtTyp').val();
                        if (dtTyp.trim() === '')
                        {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Data Type for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrSrvsDtColsRow' + rndmNum + '_DtTyp').addClass('rho-error');
                        } else {
                            $('#extrSrvsDtColsRow' + rndmNum + '_DtTyp').removeClass('rho-error');
                        }
                        var ctgry = $('#extrSrvsDtColsRow' + rndmNum + '_Ctgry').val();
                        if (ctgry.trim() === '')
                        {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Category for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrSrvsDtColsRow' + rndmNum + '_Ctgry').addClass('rho-error');
                        } else {
                            $('#extrSrvsDtColsRow' + rndmNum + '_Ctgry').removeClass('rho-error');
                        }
                        var dspTyp = $('#extrSrvsDtColsRow' + rndmNum + '_DspTyp').val();
                        if (dspTyp.trim() === '')
                        {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Display Type for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrSrvsDtColsRow' + rndmNum + '_DspTyp').addClass('rho-error');
                        } else {
                            $('#extrSrvsDtColsRow' + rndmNum + '_DspTyp').removeClass('rho-error');
                        }
                        var dtLen = $('#extrSrvsDtColsRow' + rndmNum + '_DtLen').val();
                        if (dtLen.trim() === '')
                        {
                            $('#extrSrvsDtColsRow' + rndmNum + '_DtLen').val(200);
                        }
                        var order = $('#extrSrvsDtColsRow' + rndmNum + '_Order').val();
                        if (order.trim() === '')
                        {
                            $('#extrSrvsDtColsRow' + rndmNum + '_Order').val(1);
                        }
                        var tblColsNum = $('#extrSrvsDtColsRow' + rndmNum + '_TblColsNum').val();
                        if (tblColsNum.trim() === '')
                        {
                            if (dspTyp === 'Tabular')
                            {
                                $('#extrSrvsDtColsRow' + rndmNum + '_TblColsNum').val(1);
                            } else {
                                $('#extrSrvsDtColsRow' + rndmNum + '_TblColsNum').val(0);
                            }
                        }
                        var tblrColNms = $('#extrSrvsDtColsRow' + rndmNum + '_TblrColNms').val();
                        if (tblrColNms.trim() === '' && dspTyp === 'Tabular')
                        {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Tabular Column Names for Column No. ' + colNum + ' cannot be empty!</span></p>';
                            $('#extrSrvsDtColsRow' + rndmNum + '_TblrColNms').addClass('rho-error');
                        } else {
                            $('#extrSrvsDtColsRow' + rndmNum + '_TblrColNms').removeClass('rho-error');
                            //$('#extrSrvsDtColsRow' + rndmNum + '_TblrColNms').val('');
                        }
                    }
                }
                if (isVld === false)
                {
                    /*Do Nothing*/
                } else {
                    var isRqrd = typeof $("input[name='extrSrvsDtColsRow" + rndmNum + "_IsRqrd']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    slctdExtrDataCols = slctdExtrDataCols + $('#extrSrvsDtColsRow' + rndmNum + '_ColNum').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_ExtrDtID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_FieldLbl').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_LovNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_DtTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_Ctgry').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_DtLen').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_DspTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_TblColsNum').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_Order').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#extrSrvsDtColsRow' + rndmNum + '_TblrColNms').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + isRqrd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                }
            }
        }
    });
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Fields/Columns',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Fields/Columns...Please Wait...</p>',
        callback: function () {
            openATab(slctr, linkArgs /*+ '&srcType=' + srcType*/);
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
                    grp: 14,
                    typ: 1,
                    pg: 102,
                    vtyp: 0,
                    mdl: 'Clinic/Hospital',
                    q: 'UPDATE',
                    actyp: 1,
                    slctdExtrDataCols: slctdExtrDataCols,
                    srcType: srcType
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delSrvsExtrDataCol(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var colNum = '';
    if (typeof $('#extrSrvsDtColsRow' + rndmNum + '_ExtrDtID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#extrSrvsDtColsRow' + rndmNum + '_ExtrDtID').val();
        colNum = $('#extrSrvsDtColsRow' + rndmNum + '_ColNum').val();
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
            if (result === true)
            {
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 102,
                                    vtyp: 0,
                                    q: 'DELETE',
                                    mdl: 'Clinic/Hospital',
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
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function getSrvsAddtnlDataForm(elementID, modalBodyID, titleElementID, formElementID,
        tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, pipeSprtdFieldIDs, extDtColNum, tableElmntID, pformType)
{
    var formType = pformType; //typeof $("#formTypeInpt").val() === 'undefined' ? '' : $("#formTypeInpt").val();//
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else
        {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                $('#' + titleElementID).html(formTitle);
                $('#' + modalBodyID).html(xmlhttp.responseText);
                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).one('hidden.bs.modal', function (e) {
                    $('#' + titleElementID).html('');
                    $('#' + modalBodyID).html('');
                    $(e.currentTarget).unbind();
                });
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
                if (addOrEdit === 'EDIT')
                {
                    var str_flds_array = pipeSprtdFieldIDs.split('|');
                    var $tds = $('#' + tRowElementID).find('td');
                    for (var i = 0; i < str_flds_array.length; i++) {
                        // Trim the excess whitespace.
                        var fldID = str_flds_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
                        $('#' + fldID).val($.trim($tds.eq(i + 1).text()));
                    }
                }
                $('#' + elementID).on('show.bs.modal', function (e) {
                    $(this).find('.modal-body').css({
                        'max-height': '100%'
                    });
                });
                $body.removeClass("mdlloadingDiag");
                $('#' + elementID).modal({backdrop: 'static', keyboard: false});
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
        xmlhttp.send("grp=14&typ=1&pg=102&mdl=Clinic/Hospital&q=ADTNL-DATA-FORM&vtyp=" + vtyp + "&addtnlSrvsPkey=" + pKeyID +
                "&extDtColNum=" + extDtColNum
                + "&pipeSprtdFieldIDs=" + pipeSprtdFieldIDs +
                "&tableElmntID=" + tableElmntID + "&tRowElementID=" + tRowElementID
                + "&addOrEdit=" + addOrEdit +
                "&formType=" + formType);
    });
}

function saveSrvsAddtnlDataForm(modalBodyID, addtnlSrvsPkey, pipeSprtdFieldIDs, extDtColNum, tableElmntID, tRowElementID, addOrEdit, pformType)
{
    
    var appntmntID = typeof $("#appntmntID").val() === 'undefined' ? -1 : $("#appntmntID").val();
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var str_flds_array = pipeSprtdFieldIDs.split('|');
        var str_flds_array_val = pipeSprtdFieldIDs.split('|');
        var lnkArgs = "";
        var tdsAppend = "";
        var $tds = $('#' + tRowElementID).find('td');
        var rndmNum = Math.floor((Math.random() * 99999) + parseInt(extDtColNum));
        for (var i = 0; i < str_flds_array.length; i++) {
            // Trim the excess whitespace.
            var fldID = str_flds_array[i].replace(/^\s*/, "").replace(/\s*$/, "");
            str_flds_array[i] = fldID;
            str_flds_array_val[i] = typeof $('#' + fldID).val() === 'undefined' ? '%' : $('#' + fldID).val();
            lnkArgs = lnkArgs + "&" + fldID + "=" + str_flds_array_val[i];
            if (addOrEdit === 'EDIT')
            {
                $tds.eq(i + 1).html(str_flds_array_val[i]);
            } else
            {
                tdsAppend = tdsAppend + "<td>" + str_flds_array_val[i] + "</td>";
            }
        }
        if (addOrEdit === 'ADD')
        {
            var btnHtml = '<td><button type="button" class="btn btn-default btn-sm" onclick="getSrvsAddtnlDataForm(\'myFormsModalH\', \'myFormsModalHBody\', \'myFormsModalHTitle\', \'addtnlSrvsTblrDataForm\',' +
                    '\'srvsExtrTblrDtCol_' + extDtColNum + '_Row' + rndmNum + '\', \'Add/Edit Data\', 12, \'EDIT\', ' + addtnlSrvsPkey + ', \'' + pipeSprtdFieldIDs + '\', ' + extDtColNum + ', \'extDataTblCol_' + extDtColNum + '\',\'' + pformType + '\');">' +
                    '<img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;"></button></td>';
            $('#' + tableElmntID).append('<tr id="srvsExtrTblrDtCol_' + extDtColNum + '_Row' + rndmNum + '">' + btnHtml + tdsAppend + '</tr>');
        }
        var allTblValues = "";
        $('#' + tableElmntID).find('tr').each(function (i, el) {
            var $tds1 = $(this).find('td');
            for (var i = 0; i < str_flds_array.length; i++) {
                if (i == str_flds_array.length - 1)
                {
                    allTblValues = allTblValues + $tds1.eq(i + 1).text();
                } else {
                    allTblValues = allTblValues + $tds1.eq(i + 1).text() + "~";
                }
            }
            allTblValues = allTblValues + "|";
        });
        $('#addtnlSrvsDataCol' + extDtColNum).val(allTblValues);
        var xmlhttp;
        if (window.XMLHttpRequest)
        {
            /*code for IE7+, Firefox, Chrome, Opera, Safari*/
            xmlhttp = new XMLHttpRequest();
        } else
        {
            /*code for IE6, IE5*/
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
            {
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                $('#' + modalBodyID).html(xmlhttp.responseText);
                //$('#' + modalBodyID).modal('hide')
                var msg = '<span style="font-weight:bold;">Status: </span>' +
                        '<span style="color:red;font-weight: bold;">Requires Approval </span>';
                $("#mySelfStatusBtn").html(msg);
                $('#myFormsModalH').modal('hide');
                
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=14&typ=1&pg=102&mdl=Clinic/Hospital&q=UPDATE&actyp=4&addtnlSrvsPkey=" + addtnlSrvsPkey
                + "&extDtColNum=" + extDtColNum + "&tableElmntID=" + tableElmntID
                + "&allTblValues=" + allTblValues + "&appntmntID=" + appntmntID);
    });
}

function viewRecommendedSrvsAppntmntsForm(appntmntID, formTitle, vtypActn, cnsltnID, callBackFunc)
{
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () { };
    }
    //alert(vstID);

    $body = $("body");
    $body.addClass("mdlloadingDiag");
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        /*code for IE7+, Firefox, Chrome, Opera, Safari*/
        xmlhttp = new XMLHttpRequest();
    } else
    {
        /*code for IE6, IE5*/
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            $('#myFormsModalHTitle').html(formTitle);
            $('#myFormsModalHBody').html(xmlhttp.responseText);
            /*$('.modal-dialog').draggable();*/
            $(function () {
                $('.form_date').datetimepicker({
                    format: "d-M-yyyy ",
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
            });
            
            $('#myFormsModalH').on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            
            if (typeof callBackFunc === 'undefined' || callBackFunc === null)
            {
                //callBackFunc = function () { };
            } else {
                callBackFunc();
            }
            
            $body.removeClass("mdlloadingDiag");
            $('#myFormsModalH').modal({backdrop: 'static', keyboard: false});
            
            $(document).ready(function () {
                $('#rcmddSrvsDialogForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=14&typ=1&pg=3&mdl=Clinic/Hospital&vtyp=101&appntmntID=" + appntmntID + "&vtypActn=" + vtypActn + "&cnsltnID="+cnsltnID);
}

function saveRcmnddSrvsAppointment(appntmntID, cnsltnID)
{	
    var frmAppntmntID = appntmntID;
    var frmCnsltnID = cnsltnID;
    var frmAppntmntDate = typeof $("#frmRcmddSrvsAppntmntDate").val() === 'undefined' ? '' : $("#frmRcmddSrvsAppntmntDate").val();
    var frmSrvsTypeId  = typeof $("#frmRcmddSrvsSrvsTypeId ").val() === 'undefined' ? -1 : $("#frmRcmddSrvsSrvsTypeId ").val();
    var frmAppntmntCmnts =  typeof $("#frmRcmddSrvsAppntmntCmnts").val() === 'undefined' ? -1 : $("#frmRcmddSrvsAppntmntCmnts").val();
    var errCount= 0;
    
    if ($('#frmRcmddSrvsSrvsTypeId').val() === "-1" || $('#frmRcmddSrvsSrvsTypeId').val() === "undefined") {
            $('#frmRcmddSrvsSrvsType').css('border-color', 'red');
            $('#frmRcmddSrvsSrvsType').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmRcmddSrvsSrvsType').css('border-color', '#ccc');
            $('#frmRcmddSrvsSrvsType').css('border-width', '1px');
    }

    if ($('#frmRcmddSrvsAppntmntCmnts').val() === "" || $('#frmRcmddSrvsAppntmntCmnts').val() === "undefined") {
            $('#frmRcmddSrvsAppntmntCmnts').css('border-color', 'red');
            $('#frmRcmddSrvsAppntmntCmnts').css('border-width', '2px');
            errCount = errCount + 1;
    } else {
            $('#frmRcmddSrvsAppntmntCmnts').css('border-color', '#ccc');
            $('#frmRcmddSrvsAppntmntCmnts').css('border-width', '1px');
    }

    if (errCount > 0) {
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted field(s)</i></b></span>",
            callback: function () { /* your callback code */
            }
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: 'Save Appointment?',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Appointment...Please Wait...</p>',
        callback: function () {
            //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit & Appointment', 2, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
        }
    });
    
    
    var formData = new FormData();
    formData.append('grp', 14);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('pg', 3);
    formData.append('actyp', 101);
    formData.append('mdl', 'Clinic/Hospital');
    formData.append('frmAppntmntID', frmAppntmntID);   
    formData.append('frmCnsltnID', frmCnsltnID);
    formData.append('frmAppntmntDate', frmAppntmntDate);   
    formData.append('frmSrvsTypeId', frmSrvsTypeId);   
    formData.append('frmAppntmntCmnts', frmAppntmntCmnts); 
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: formData,
                async:true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                   var msg = "";
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(data);

                            dialog.modal('hide');
                            $('#myFormsModalH').modal('hide');
                            
                            msg = "Appointment Scheduled Successfully!";
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                            
                            openATab('#rcmddSrvsTbPage', 'grp=14&typ=1&pg=3&mdl=Clinic/Hospital&vtyp=8&cnsltnID='+frmCnsltnID);
                            
                    } else {

                            msg = data;
                            dialog.modal('hide');
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveAddtnlSrvsData(appntmntID, srcType)
{	
    var dialog = bootbox.alert({
        title: 'Save Data',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ...Please Wait...</p>',
        callback: function () {
            //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit & Appointment', 2, 1, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);                           
        }
    });
    
    var addtnlSrvsDataCol1 = typeof $("#addtnlSrvsDataCol1").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol1").val();
    var addtnlSrvsDataCol2 = typeof $("#addtnlSrvsDataCol2").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol2").val();
    var addtnlSrvsDataCol3 = typeof $("#addtnlSrvsDataCol3").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol3").val();
    var addtnlSrvsDataCol4 = typeof $("#addtnlSrvsDataCol4").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol4").val();
    var addtnlSrvsDataCol5 = typeof $("#addtnlSrvsDataCol5").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol5").val();
    var addtnlSrvsDataCol6 = typeof $("#addtnlSrvsDataCol6").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol6").val();
    var addtnlSrvsDataCol7 = typeof $("#addtnlSrvsDataCol7").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol7").val();
    var addtnlSrvsDataCol8 = typeof $("#addtnlSrvsDataCol8").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol8").val();
    var addtnlSrvsDataCol9 = typeof $("#addtnlSrvsDataCol9").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol9").val();
    var addtnlSrvsDataCol10 = typeof $("#addtnlSrvsDataCol10").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol10").val();
    var addtnlSrvsDataCol11 = typeof $("#addtnlSrvsDataCol11").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol11").val();
    var addtnlSrvsDataCol12 = typeof $("#addtnlSrvsDataCol12").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol12").val();
    var addtnlSrvsDataCol13 = typeof $("#addtnlSrvsDataCol13").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol13").val();
    var addtnlSrvsDataCol14 = typeof $("#addtnlSrvsDataCol14").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol14").val();
    var addtnlSrvsDataCol15 = typeof $("#addtnlSrvsDataCol15").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol15").val();
    var addtnlSrvsDataCol16 = typeof $("#addtnlSrvsDataCol16").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol16").val();
    var addtnlSrvsDataCol17 = typeof $("#addtnlSrvsDataCol17").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol17").val();
    var addtnlSrvsDataCol18 = typeof $("#addtnlSrvsDataCol18").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol18").val();
    var addtnlSrvsDataCol19 = typeof $("#addtnlSrvsDataCol19").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol19").val();
    var addtnlSrvsDataCol20 = typeof $("#addtnlSrvsDataCol20").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol20").val();
    var addtnlSrvsDataCol21 = typeof $("#addtnlSrvsDataCol21").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol21").val();
    var addtnlSrvsDataCol22 = typeof $("#addtnlSrvsDataCol22").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol22").val();
    var addtnlSrvsDataCol23 = typeof $("#addtnlSrvsDataCol23").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol23").val();
    var addtnlSrvsDataCol24 = typeof $("#addtnlSrvsDataCol24").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol24").val();
    var addtnlSrvsDataCol25 = typeof $("#addtnlSrvsDataCol25").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol25").val();
    var addtnlSrvsDataCol26 = typeof $("#addtnlSrvsDataCol26").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol26").val();
    var addtnlSrvsDataCol27 = typeof $("#addtnlSrvsDataCol27").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol27").val();
    var addtnlSrvsDataCol28 = typeof $("#addtnlSrvsDataCol28").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol28").val();
    var addtnlSrvsDataCol29 = typeof $("#addtnlSrvsDataCol29").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol29").val();
    var addtnlSrvsDataCol30 = typeof $("#addtnlSrvsDataCol30").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol30").val();
    var addtnlSrvsDataCol31 = typeof $("#addtnlSrvsDataCol31").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol31").val();
    var addtnlSrvsDataCol32 = typeof $("#addtnlSrvsDataCol32").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol32").val();
    var addtnlSrvsDataCol33 = typeof $("#addtnlSrvsDataCol33").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol33").val();
    var addtnlSrvsDataCol34 = typeof $("#addtnlSrvsDataCol34").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol34").val();
    var addtnlSrvsDataCol35 = typeof $("#addtnlSrvsDataCol35").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol35").val();
    var addtnlSrvsDataCol36 = typeof $("#addtnlSrvsDataCol36").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol36").val();
    var addtnlSrvsDataCol37 = typeof $("#addtnlSrvsDataCol37").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol37").val();
    var addtnlSrvsDataCol38 = typeof $("#addtnlSrvsDataCol38").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol38").val();
    var addtnlSrvsDataCol39 = typeof $("#addtnlSrvsDataCol39").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol39").val();
    var addtnlSrvsDataCol40 = typeof $("#addtnlSrvsDataCol40").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol40").val();
    var addtnlSrvsDataCol41 = typeof $("#addtnlSrvsDataCol41").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol41").val();
    var addtnlSrvsDataCol42 = typeof $("#addtnlSrvsDataCol42").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol42").val();
    var addtnlSrvsDataCol43 = typeof $("#addtnlSrvsDataCol43").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol43").val();
    var addtnlSrvsDataCol44 = typeof $("#addtnlSrvsDataCol44").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol44").val();
    var addtnlSrvsDataCol45 = typeof $("#addtnlSrvsDataCol45").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol45").val();
    var addtnlSrvsDataCol46 = typeof $("#addtnlSrvsDataCol46").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol46").val();
    var addtnlSrvsDataCol47 = typeof $("#addtnlSrvsDataCol47").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol47").val();
    var addtnlSrvsDataCol48 = typeof $("#addtnlSrvsDataCol48").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol48").val();
    var addtnlSrvsDataCol49 = typeof $("#addtnlSrvsDataCol49").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol49").val();
    var addtnlSrvsDataCol50 = typeof $("#addtnlSrvsDataCol50").val() === 'undefined' ? '' : $("#addtnlSrvsDataCol50").val();
   
    
    var formData = new FormData();
    formData.append('grp', 14);
    formData.append('typ', 1);
    formData.append('q', 'UPDATE');
    formData.append('pg', 3);
    formData.append('actyp', 100);
    formData.append('mdl', 'Clinic/Hospital');
    formData.append('appntmntID', appntmntID);   
    formData.append('srcType', srcType); 
    formData.append('addtnlSrvsDataCol1', addtnlSrvsDataCol1);
    formData.append('addtnlSrvsDataCol2', addtnlSrvsDataCol2);
    formData.append('addtnlSrvsDataCol3', addtnlSrvsDataCol3);
    formData.append('addtnlSrvsDataCol4', addtnlSrvsDataCol4);
    formData.append('addtnlSrvsDataCol5', addtnlSrvsDataCol5);
    formData.append('addtnlSrvsDataCol6', addtnlSrvsDataCol6);
    formData.append('addtnlSrvsDataCol7', addtnlSrvsDataCol7);
    formData.append('addtnlSrvsDataCol8', addtnlSrvsDataCol8);
    formData.append('addtnlSrvsDataCol9', addtnlSrvsDataCol9);
    formData.append('addtnlSrvsDataCol10', addtnlSrvsDataCol10);
    formData.append('addtnlSrvsDataCol11', addtnlSrvsDataCol11);
    formData.append('addtnlSrvsDataCol12', addtnlSrvsDataCol12);
    formData.append('addtnlSrvsDataCol13', addtnlSrvsDataCol13);
    formData.append('addtnlSrvsDataCol14', addtnlSrvsDataCol14);
    formData.append('addtnlSrvsDataCol15', addtnlSrvsDataCol15);
    formData.append('addtnlSrvsDataCol16', addtnlSrvsDataCol16);
    formData.append('addtnlSrvsDataCol17', addtnlSrvsDataCol17);
    formData.append('addtnlSrvsDataCol18', addtnlSrvsDataCol18);
    formData.append('addtnlSrvsDataCol19', addtnlSrvsDataCol19);
    formData.append('addtnlSrvsDataCol20', addtnlSrvsDataCol20);
    formData.append('addtnlSrvsDataCol21', addtnlSrvsDataCol21);
    formData.append('addtnlSrvsDataCol22', addtnlSrvsDataCol22);
    formData.append('addtnlSrvsDataCol23', addtnlSrvsDataCol23);
    formData.append('addtnlSrvsDataCol24', addtnlSrvsDataCol24);
    formData.append('addtnlSrvsDataCol25', addtnlSrvsDataCol25);
    formData.append('addtnlSrvsDataCol26', addtnlSrvsDataCol26);
    formData.append('addtnlSrvsDataCol27', addtnlSrvsDataCol27);
    formData.append('addtnlSrvsDataCol28', addtnlSrvsDataCol28);
    formData.append('addtnlSrvsDataCol29', addtnlSrvsDataCol29);
    formData.append('addtnlSrvsDataCol30', addtnlSrvsDataCol30);
    formData.append('addtnlSrvsDataCol31', addtnlSrvsDataCol31);
    formData.append('addtnlSrvsDataCol32', addtnlSrvsDataCol32);
    formData.append('addtnlSrvsDataCol33', addtnlSrvsDataCol33);
    formData.append('addtnlSrvsDataCol34', addtnlSrvsDataCol34);
    formData.append('addtnlSrvsDataCol35', addtnlSrvsDataCol35);
    formData.append('addtnlSrvsDataCol36', addtnlSrvsDataCol36);
    formData.append('addtnlSrvsDataCol37', addtnlSrvsDataCol37);
    formData.append('addtnlSrvsDataCol38', addtnlSrvsDataCol38);
    formData.append('addtnlSrvsDataCol39', addtnlSrvsDataCol39);
    formData.append('addtnlSrvsDataCol40', addtnlSrvsDataCol40);
    formData.append('addtnlSrvsDataCol41', addtnlSrvsDataCol41);
    formData.append('addtnlSrvsDataCol42', addtnlSrvsDataCol42);
    formData.append('addtnlSrvsDataCol43', addtnlSrvsDataCol43);
    formData.append('addtnlSrvsDataCol44', addtnlSrvsDataCol44);
    formData.append('addtnlSrvsDataCol45', addtnlSrvsDataCol45);
    formData.append('addtnlSrvsDataCol46', addtnlSrvsDataCol46);
    formData.append('addtnlSrvsDataCol47', addtnlSrvsDataCol47);
    formData.append('addtnlSrvsDataCol48', addtnlSrvsDataCol48);
    formData.append('addtnlSrvsDataCol49', addtnlSrvsDataCol49);
    formData.append('addtnlSrvsDataCol50', addtnlSrvsDataCol50);
    
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: formData,
                async:true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                   var msg = "";
                    if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                                    replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                                    replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                            obj = $.parseJSON(data);

                            dialog.modal('hide');
                            //$('#myFormsModalH').modal('hide');
                            
                            msg = "Data Saved Successfully!";
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                            
                    } else {

                            msg = data;
                            dialog.modal('hide');
                            box2 = bootbox.alert({
                                    size: "small",
                                    title: "Rhomicom Message",
                                    message: msg,
                                    callback: function () { /* your callback code */
                                    }
                            });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}


function openAppntmntTab(slctr, linkArgs){   
    var srcTyp = typeof $("#formTypeInpt").val() === 'undefined' ? '' : $("#formTypeInpt").val();
    
    if(srcTyp === ''){
        openATab(slctr, linkArgs);
    }    
}

function deleteRcmddSrvsMain(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Service?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Recommended Service?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Service?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Service...Please Wait...</p>',
                    callback: function () {
                        //getAllPrvdrGroups('', "#allmodules", "grp=42&typ=1&pg=6&vtyp=0");
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 3,
				    mdl: 'Clinic/Hospital',
                                    q: 'DELETE',
                                    actyp: 8,
                                    rcmddSrvsID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function getAllRcmddSrvsMains(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allRcmddSrvsMainsSrchFor").val() === 'undefined' ? '%' : $("#allRcmddSrvsMainsSrchFor").val();
    var srchIn = typeof $("#allRcmddSrvsMainsSrchIn").val() === 'undefined' ? 'Both' : $("#allRcmddSrvsMainsSrchIn").val();
    var pageNo = typeof $("#allRcmddSrvsMainsPageNo").val() === 'undefined' ? 1 : $("#allRcmddSrvsMainsPageNo").val();
    var limitSze = typeof $("#allRcmddSrvsMainsDsplySze").val() === 'undefined' ? 10 : $("#allRcmddSrvsMainsDsplySze").val();
    var sortBy = typeof $("#allRcmddSrvsMainsSortBy").val() === 'undefined' ? '' : $("#allRcmddSrvsMainsSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
            "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllRcmddSrvsMains(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllRcmddSrvsMains(actionText, slctr, linkArgs);
    }
}


//DOCUMENT ATTACHMENTS
function getOneFscDocsForm_Gnrl(pKeyID, trnsType, vwtype, formTitle)
{
    $('#allOtherInputData99').val('0');
    var lnkArgs = 'grp=14&typ=1&pg=103&mdl=Clinic/Hospital&vtyp=' + vwtype + '&sbmtdHdrID=' + pKeyID + '&docType=' + trnsType + '&subPgNo=' + vwtype + '.1';
    doAjaxWthCallBck(lnkArgs, 'myFormsModalyH', 'ShowDialog', formTitle, 'myFormsModalyHTitle', 'myFormsModalyHBody', function () {
        var table1 = $('#attchdFSCDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdFSCDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdFSCDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function getOneFscDocsForm(trnsType, vwtype)
{
    var pKeyID = $('#acctTrnsId').val();
    if (pKeyID == "" || pKeyID == undefined) {
        pKeyID = -1;
    }
    var acctTitle = $('#acctTitle').val();
    var acctID = $('#acctID').val();

    if (acctTitle == "" || acctTitle == undefined) {
        bootbox.alert({
            size: "small",
            title: "System Alert!",
            message: "Account Details required!",
            callback: function () { /* your callback code */
            }
        });
        return;
    }
    var lnkArgs = 'grp=14&typ=1&pg=103&mdl=Clinic/Hospital&vtyp=' + vwtype + '&sbmtdHdrID=' + pKeyID + '&docType=' + trnsType + '&subPgNo=' + vwtype + '.1&pAcctID=' + acctID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalyH', 'ShowDialog', 'FSC Attached Documents', 'myFormsModalyHTitle', 'myFormsModalyHBody', function () {
        var table1 = $('#attchdFSCDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#acctTrnsId').val($('#attchdFSCDocsNwTrnsId').val());
        $('#attchdFSCDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdFSCDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToFscDocs(inptElmntID, attchIDElmntID, docNmElmntID, docFileTypeElmntID, docTrnsTypeElmntID, sbmtdHdrID)
{
    var docCtrgrName = $('#' + docNmElmntID).val();
    var docFileType = $('#' + docFileTypeElmntID).val();
    var docTrsType = $('#' + docTrnsTypeElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === ''
            || docFileType.trim() === ''
            || docTrsType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Doc. Name/Description, File Type and Transaction Type must all be provided!</span></p>';
    }
    /*if (sbmtdHdrID <= 0)
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
     }*/
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    $("#" + inptElmntID).val('');
    $("#" + inptElmntID).off('change');
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToFscDocs(input.files[0], docNmElmntID, docFileTypeElmntID, docTrnsTypeElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            $('#attchdFSCDocsNwTrnsId').val(data.NwTrnsId);
            if (docTrsType == "DEPOSIT" || docTrsType == "WITHDRAWAL" || docTrsType == "LOAN_REPAY" || docTrsType == "LOAN_PYMNT") {
                $('#acctTrnsId').val(data.NwTrnsId);
            }
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {
                    }
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToFscDocs(file, docNmElmntID, docFileTypeElmntID, docTrnsTypeElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daFSCAttchmnt', file);
    data1.append('grp', 14);
    data1.append('typ', 1);
    data1.append('pg', 103);
	data1.append('mdl', 'Clinic/Hospital');
    data1.append('subPgNo', '140.1');
    data1.append('q', 'UPDATEDOC');
    data1.append('actyp', 1);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('docFileType', $('#' + docFileTypeElmntID).val());
    data1.append('docTrsType', $('#' + docTrnsTypeElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    if ($('#' + docTrnsTypeElmntID).val() == "DEPOSIT" || $('#' + docTrnsTypeElmntID).val() == "WITHDRAWAL" ||
            $('#' + docTrnsTypeElmntID).val() == "LOAN_REPAY" || $('#' + docTrnsTypeElmntID).val() == "LOAN_PYMNT") {
        data1.append('sbmtdHdrID', $('#acctTrnsId').val());
        data1.append('pAcctID', $('#acctID').val());
    } else {
        data1.append('sbmtdHdrID', sbmtdHdrID);
        data1.append('pAcctID', -1);
    }
    var dialog1 = bootbox.alert({
        title: 'Uploading File...',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>'
    });
    dialog1.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                type: 'POST',
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    dialog1.modal('hide');
                    callBackFunc(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getAttchdFscDocs(actionText, slctr, linkArgs)
{
    var pKeyID = $('#acctTrnsId').val();
    if (pKeyID == "" || pKeyID == undefined) {
        pKeyID = -1;
    }
    var acctTitle = $('#acctTitle').val();
    var acctID = $('#acctID').val();
    var srchFor = typeof $("#attchdFSCDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdFSCDocsSrchFor").val();
    var srchIn = typeof $("#attchdFSCDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdFSCDocsSrchIn").val();
    var pageNo = typeof $("#attchdFSCDocsPageNo").val() === 'undefined' ? 1 : $("#attchdFSCDocsPageNo").val();
    var limitSze = typeof $("#attchdFSCDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdFSCDocsDsplySze").val();
    var sortBy = typeof $("#attchdFSCDocsSortBy").val() === 'undefined' ? '' : $("#attchdFSCDocsSortBy").val();
    if (actionText == 'clear')
    {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next')
    {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous')
    {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs.replace('sbmtdHdrID=-1', 'sbmtdHdrID=' + pKeyID) + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModalyH', 'ReloadDialog', 'Banking Attached Documents', 'myFormsModalyHTitle', 'myFormsModalyHBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdFSCDocsTable')) {
            var table1 = $('#attchdFSCDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdFSCDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdFSCDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdFscDocs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdFscDocs(actionText, slctr, linkArgs);
    }
}

function delAttchdFscDoc(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdHdrID").val() === 'undefined' ? -1 : $("#sbmtdHdrID").val();
    var docNum = typeof $("#mcfTrnsNum").val() === 'undefined' ? '' : $("#mcfTrnsNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdFSCDocsRow' + rndmNum + '_AttchdFSCDocsID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdFSCDocsRow' + rndmNum + '_AttchdFSCDocsID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Document?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Document?<br/>Action cannot be Undone!</p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Delete Document?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
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
                                    grp: 14,
                                    typ: 1,
                                    pg: 103,
									mdl: 'Clinic/Hospital',
                                    q: 'DELETEDOC',
                                    subPgNo: '140.1',
                                    actyp: 1,
                                    attchmentID: pKeyID,
                                    sbmtdHdrID: sbmtdHdrID,
                                    docTrnsNum: docNum
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else
                    {
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

function onChangeOfPrvdrType(cntr){
    var prvdrPrsnID  = $("#allPrvdrGroupPersonsRow"+ cntr +"_PrvdrGroupPersonID").val();
    if(prvdrPrsnID > 0){
        $("#allPrvdrGroupPersonsRow"+ cntr +"_PrvdrGroupPersonID").val(-1);
        $("#allPrvdrGroupPersonsRow"+ cntr +"_PrvdrGroupPersonNm").val("");
    }
}

function getServiceProviderPersonList(cntr){
    var prvdrType = $("#allPrvdrGroupPersonsRow"+ cntr +"_PrvdrType").val();
    var lovNm = "Hospital Staff";
    if(prvdrType === "Locum"){
        lovNm = "Suppliers";
    }
        
    getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', lovNm, 'lovOrgID', '', '', 'radio', true, '', 'allPrvdrGroupPersonsRow'+ cntr +'_PrvdrGroupPersonID', 'allPrvdrGroupPersonsRow'+cntr+'_PrvdrGroupPersonNm', 'clear', 0, '');
}

function getHospDetailsFormCheckedIn(elementID, modalBodyID, titleElementID, formTitle, pgNo, vtyp, vtypActn, pKeyID, listTableID, rowID, srcPgNo, callBackFunc)
{
    
    if (typeof callBackFunc === 'undefined' || callBackFunc === null)
    {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }  
    
    if (typeof srcPgNo === 'undefined' || srcPgNo === null)
    {
        srcPgNo = 2;
    }

    if (!$.fn.DataTable.isDataTable('#' + listTableID)) {
        var table1 = $('#' + listTableID).DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#' + listTableID + ' tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
        $('#' + listTableID + ' tbody').on('mouseenter', 'tr', function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
    }

    var p_statusTitle = "Incomplete";
    if (vtypActn == "EDIT") {

    }


    $body = $("body");
    $body.addClass("mdlloadingDiag");
    var xmlhttp;
    if (window.XMLHttpRequest)
    {
        /*code for IE7+, Firefox, Chrome, Opera, Safari*/
        xmlhttp = new XMLHttpRequest();
    } else
    {
        /*code for IE6, IE5*/
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
        {
            $('#' + titleElementID).html(formTitle);
            $('#' + modalBodyID).html(xmlhttp.responseText);
            /*$('.modal-content').resizable({
             //alsoResize: ".modal-dialog",
             minHeight: 600,
             minWidth: 300
             });*/
            /*$('.modal-dialog').draggable();*/
           
            
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
            
            
            $('#' + elementID).on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            
            
            $('#'+elementID).off('hidden.bs.modal');
            $('#'+elementID).one('hidden.bs.modal', function (e) {
                $('#' + modalBodyID).html('');
                if(pgNo == srcPgNo) {
                    getHospData('', '#allmodules', 'grp=14&typ=1&pg='+pgNo+'&mdl=Clinic/Hospital');
                } else if(srcPgNo == 2){
                    //var vstId = $('#vstID').val(); //UNCOMMENT THIS AND NEXT LINE TO RELOAD VISIT FORM
                    //getHospDetailsForm('myFormsModalLg', 'myFormsModalBodyLg', 'myFormsModalTitleLg', 'Edit Visit', 2,  1, 'EDIT', vstId, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1');
                }
                $(e.currentTarget).unbind();
            });
            $body.removeClass("mdlloadingDiag");
            //$('#' + elementID).modal({backdrop: 'static',keyboard:false});
            $('#' + elementID).modal({backdrop: 'static',keyboard:false});
            $body = $("body");
            
             $('#allOtherInputData99').val('0');
            if (!$.fn.DataTable.isDataTable('#rptsStpPrmsTable')) {
                var table1 = $('#rptsStpPrmsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#rptsStpPrmsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#diagnosisTable')) {
                var table2 = $('#diagnosisTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#diagnosisTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#invstgtnTable')) {
                var table2 = $('#invstgtnTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#invstgtnTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#medicalForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            /*$('#myFormsModalLg').off('hidden.bs.modal');
            $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
                if (srcCaller === 'NORMAL') {
                    getAllRpts('Clear', '#allmodules', 'grp=9&typ=1&pg=1&vtyp=0');
                } else if (srcCaller === 'ALL_RUNS') {
                    getAllPrcsRuns('', '#allmodules', 'grp=9&typ=1&pg=5&vtyp=0');
                }
                $(e.currentTarget).unbind();
            });*/
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="tabajxrptdet"]').off('click');
            $('[data-toggle="tabajxrptdet"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = dttrgt;
                if (targ.indexOf('cnsltnMainTbPage') >= 0) {
                    $('#cnsltnMainTbPage').removeClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('vitalsTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').removeClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('inHouseAdmsnTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').removeClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('medicationTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').removeClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('invstgtnTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').removeClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                } else if (targ.indexOf('rcmddSrvsTbPage') >= 0) {
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').removeClass('hideNotice');
                    var rcSvId = typeof $("#rcmdSrvsMainForm").val() === 'undefined' ? -1 : $("#rcmdSrvsMainForm").val();
    
                    if(rcSvId <= 0){
                        openATab(targ, linkArgs);
                    } 
                } else if (targ.indexOf('prfBCOPAddPrsnDataEDT') >= 0) {
                    var srcTyp = typeof $("#formTypeInpt").val() === 'undefined' ? '' : $("#formTypeInpt").val();
    
                    if(srcTyp === ''){
                        openATab(targ, linkArgs);
                    } 
                    //openATab(targ, linkArgs);
                    $('#cnsltnMainTbPage').addClass('hideNotice');
                    $('#vitalsTbPage').addClass('hideNotice');
                    $('#inHouseAdmsnTbPage').addClass('hideNotice');
                    $('#medicationTbPage').addClass('hideNotice');
                    $('#invstgtnTbPage').addClass('hideNotice');
                    $('#rcmddSrvsTbPage').addClass('hideNotice');
                    $('#prfBCOPAddPrsnDataEDTPage').removeClass('hideNotice');
                }
                //return;
            });
            
            $('#allOtherInputData99').val(0);
            
            $(document).ready(function () {
                /*$('#' + formElementID).submit(function (e) {
                 e.preventDefault();
                 return false;
                 });*/

                callBackFunc();
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=14&typ=1&pg=" + pgNo + "&mdl=Clinic/Hospital&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID+"&srcPgNo"+srcPgNo+"&chkIn=Y");
}

function calcBMI(){
    var wgt = typeof $("#frmVitalsWeight").val() == 'undefined' ? '' : $("#frmVitalsWeight").val();
    var hgt = typeof $("#frmVitalsHeight").val() == 'undefined' ? '' : $("#frmVitalsHeight").val();
    var bmi = "";
    var bmiVal = "0";
    var bmiStatus = "";
    if(wgt != "" && hgt != ""){
        bmi = parseFloat(wgt)/(parseFloat(hgt) * parseFloat(hgt));
        bmiVal = bmi.toFixed(2);
         $("#frmVitalsBMI").val(bmiVal); 
        
        if(bmiVal < 18.5){
            bmiStatus = "Underweight";
        } else if(bmiVal >= 18.5 && bmiVal <= 24.9){
            bmiStatus = "Healthy";
        } else if(bmiVal >= 25.0 && bmiVal <= 29.9){
            bmiStatus = "Overweight";
        } else if(bmiVal >= 30.0){
            bmiStatus = "Obese";
        } else {
            bmiStatus = "Invalid";
        }
        $("#frmVitalsBMIStatus").val(bmiStatus); 
    }  else {
        $("#frmVitalsBMI").val(bmiVal);
        $("#frmVitalsBMIStatus").val("INVALID"); 
    }    
}

function computeBPStatus(){
    var bpSystlc = typeof $("#frmVitalsBPSystolic").val() == 'undefined' ? '' : $("#frmVitalsBPSystolic").val();
    var bpDystlc = typeof $("#frmVitalsBPDiastolic").val() == 'undefined' ? '' : $("#frmVitalsBPDiastolic").val();

    var bpStatus = "";
    var bpSystlcVal = "";
    var bpDystlcVal = "";
    if(bpSystlc != "" && bpDystlc != ""){    
        bpSystlcVal = parseFloat(bpSystlc);
        bpDystlcVal = parseFloat(bpDystlc);
        
        if(bpSystlcVal < 40 ||  bpDystlcVal < 40){
            bpStatus = "Invalid Low Reading";
        } else if(bpSystlcVal >= 40 && bpSystlcVal < 90 &&  bpDystlcVal >= 40 && bpDystlcVal < 60){
            bpStatus = "Low Blood Pressure";
        } else if(bpSystlcVal >= 90 && bpSystlcVal < 120 &&  bpDystlcVal >= 60 && bpDystlcVal < 80){
            bpStatus = "Normal";
        } else if(bpSystlcVal >= 120 && bpSystlcVal < 130 &&  bpDystlcVal >= 60 && bpDystlcVal < 80){
            bpStatus = "Elevated";
        } else if((bpSystlcVal >= 130 && bpSystlcVal < 140) ||  (bpDystlcVal >= 80 && bpDystlcVal < 90)){
            bpStatus = "Hypertension Stage 1";
        } else if((bpSystlcVal >= 140 && bpSystlcVal <= 180) || (bpDystlcVal >= 90 && bpDystlcVal <= 120)){
            bpStatus = "Hypertension Stage 2";
        } else if(bpSystlcVal > 180 ||  bpDystlcVal > 120){
            bpStatus = "Hypertension Crisis";
        } else {
            bpStatus = "Invalid Reading";
        }
        
        $("#frmVitalsPBStatus").val(bpStatus); 
    }  else {
        $("#frmVitalsPBStatus").val("INVALID"); 
    }    
}

function cancelAppointment(vstID, appntmntID)
{
    //var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Visits and Appointment";
    
    var dialog = bootbox.confirm({
        title: 'Cancel Appointment',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CANCEL</span> this appointment?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Cancel Appointment?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Cancelling Appointment...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 3,
                                mdl: 'Clinic/Hospital',
                                q: 'CANCEL-APPOINTMENT',
                                actyp: 1,
                                PKeyID: appntmntID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', dialogTitle, 2, 1, 'EDIT', vstID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1');
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

function reopenAppointment(appntmntID, vwtyp)
{
    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
    var dialog = bootbox.confirm({
        title: 'Cancel Appointment',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">RE-OPEN</span> this appointment?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Re-Open Appointment?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Re-Opening Appointment...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 3,
                                mdl: 'Clinic/Hospital',
                                q: 'REOPEN-APPOINTMENT',
                                actyp: 1,
                                PKeyID: appntmntID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModalLgHZ', 'myFormsModalLgHZBody', 'myFormsModalLgHZTitle', dialogTitle, 3, vwtyp, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', srcPgNo);
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

function reopenVitalsAppointment(appntmntID, vwtyp)
{
    var patientNm = typeof $("#frmVitalsPatientNm").val() === 'undefined' ? '' : $("#frmVitalsPatientNm").val();
    var appntmntNo = typeof $("#frmVitalsAppntmntNo").val() === 'undefined' ? '' : $("#frmVitalsAppntmntNo").val();
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    var dialogTitle =  "Appointment for Patient "+patientNm+" - "+appntmntNo;
    
    var dialog = bootbox.confirm({
        title: 'Re-Open Appointment',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REI<-OPENspan> this CLOSED Appointment?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Re-Open Appointment?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Reopening Appointment...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 3,
                                mdl: 'Clinic/Hospital',
                                q: 'REOPEN-APPOINTMENT',
                                actyp: 1,
                                PKeyID: appntmntID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModaly', 'myFormsModalyBody', 'myFormsModalyTitle', dialogTitle, 3, vwtyp, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

function closeVisit(vstID, vwtyp)
{
    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
    
    var dialog = bootbox.confirm({
        title: 'Close Visit?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CLOSE</span> this visit?<br/></p>',
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
            if (result === true)
            {
                var dialog1 = bootbox.alert({
                    title: 'Close Visit?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Closing...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                    }
                });
                dialog1.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 14,
                                typ: 1,
                                pg: 2,
                                mdl: 'Clinic/Hospital',
                                q: 'CLOSE-VISIT',
                                actyp: 1,
                                PKeyID: vstID
                            },
                            success: function (result1) {
                                setTimeout(function () {
                                    dialog1.find('.bootbox-body').html(result1);
                                    if (result1.indexOf("Success") !== -1) {
                                        getHospDetailsForm('myFormsModalLgH', 'myFormsModalBodyLgH', 'myFormsModalTitleLgH', 'Edit Visit', 2, vwtyp, 'EDIT', vstID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow1', srcPgNo);
                                    }
                                }, 500);
                            },
                            error: function (jqXHR1, textStatus1, errorThrown1)
                            {
                                dialog1.find('.bootbox-body').html(errorThrown1);
                            }
                        });
                    });
                });
            }
        }
    });
}

function setCurStoreHosp() {
    var accbFSRptStoreID = typeof $("#accbFSRptStoreID").val() === 'undefined' ? '-1' : $("#accbFSRptStoreID").val();
    var accbFSRptStore = typeof $("#accbFSRptStore").val() === 'undefined' ? '' : $("#accbFSRptStore").val();
    openATab('#allmodules', 'grp=14&typ=1&mdl=Clinic/Hospital&vtyp=0&accbFSRptStoreID=' + accbFSRptStoreID);
}