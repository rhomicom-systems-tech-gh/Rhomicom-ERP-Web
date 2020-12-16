var rptTable1;
var alrtTable1;
var schdlsTable1;
function prepareRpts(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(function () {
            $('[data-toggle="tabajxalrt"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=9&typ=1' + dttrgt;
                return openATab(targ, linkArgs);
            });
        });
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allRptsTable')) {
                rptTable1 = $('#allRptsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allRptsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRptsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            if (!$.fn.DataTable.isDataTable('#rptRunsTable')) {
                var table2 = $('#rptRunsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#rptRunsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRptsTable tbody').off('click', 'tr');
            $('#allRptsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    rptTable1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#allRptsRow' + rndmNum + '_RptID').val() === 'undefined' ? '%' : $('#allRptsRow' + rndmNum + '_RptID').val();
                //alert(curPlcyID);
                getOneRptsForm(pkeyID, 1);
            });
            $('#allRptsTable tbody')
                    .off('mouseenter', 'tr');
            $('#allRptsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            rptTable1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=1") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#rptRunsTable')) {
                var table2 = $('#rptRunsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#rptRunsTable').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allAlrtsTable')) {
                alrtTable1 = $('#allAlrtsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allAlrtsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allAlrtsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            if (!$.fn.DataTable.isDataTable('#alrtRunsTable')) {
                var table2 = $('#alrtRunsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#alrtRunsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allAlrtsTable tbody').off('click', 'tr');
            $('#allAlrtsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    alrtTable1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#allAlrtsRow' + rndmNum + '_AlrtID').val() === 'undefined' ? '%' : $('#allAlrtsRow' + rndmNum + '_AlrtID').val();
                //alert(curPlcyID);
                getOneAlrtsForm(pkeyID, 1);
            });
            $('#allAlrtsTable tbody')
                    .off('mouseenter', 'tr');
            $('#allAlrtsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            alrtTable1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=2&vtyp=1") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#alrtRunsTable')) {
                var table2 = $('#alrtRunsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#alrtRunsTable').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&pg=2&vtyp=3") !== -1) {
            if (!$.fn.DataTable.isDataTable('#alrtSchdlPrmsTable')) {
                var table1 = $('#alrtSchdlPrmsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#alrtSchdlPrmsTable').wrap('<div class="dataTables_scroll"/>');
            }
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
            $('.form_date_tme1').datetimepicker({
                format: "yyyy-mm-dd hh:ii:ss",
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

            $('.form_date1').datetimepicker({
                format: "yyyy-mm-dd",
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
                                    $('#alrtsMsgBody').summernote('createLink', {
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
            $('#alrtsMsgBody').summernote({
                minHeight: 262,
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
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['codeview']],
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
                                    $('#alrtsMsgBody').summernote("insertImage", inptUrl, 'filename');
                                });
                            }
                        }
            });
            var markupStr1 = typeof $("#alrtsMsgBodyEncded").val() === 'undefined' ? '' : $("#alrtsMsgBodyEncded").val();
            $('#alrtsMsgBody').summernote('code', urldecode(markupStr1));
        } else if (lnkArgs.indexOf("&pg=1&vtyp=8") !== -1)
        {
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
            if (!$.fn.DataTable.isDataTable('#rptsStpRolesTable')) {
                var table2 = $('#rptsStpRolesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#rptsStpRolesTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#rptsStpPrgmsTable')) {
                var table2 = $('#rptsStpPrgmsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#rptsStpPrgmsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#rptsStpForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=3") !== -1)
        {

            if (!$.fn.DataTable.isDataTable('#allSchdlPrmsTable')) {
                var table1 = $('#allSchdlPrmsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSchdlPrmsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSchdlsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allSchdlsTable')) {
                schdlsTable1 = $('#allSchdlsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSchdlsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSchdlsTable tbody').off('click', 'tr');
            $('#allSchdlsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    schdlsTable1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#allSchdlsRow' + rndmNum + '_RptID').val() === 'undefined' ? '%' : $('#allSchdlsRow' + rndmNum + '_RptID').val();
                var pkeyID1 = typeof $('#allSchdlsRow' + rndmNum + '_SchdlID').val() === 'undefined' ? '%' : $('#allSchdlsRow' + rndmNum + '_SchdlID').val();
                //alert(curPlcyID);
                getOneSchdlsForm(pkeyID, pkeyID1, 6);
            });
            $('#allSchdlsTable tbody')
                    .off('mouseenter', 'tr');
            $('#allSchdlsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            schdlsTable1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
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
        } else if (lnkArgs.indexOf("&pg=5") !== -1)
        {
            $('#allPrcsRunsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
        htBody.removeClass("mdlloading");
    });
}

function getAllRpts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allRptsSrchFor").val() === 'undefined' ? '%' : $("#allRptsSrchFor").val();
    var srchIn = typeof $("#allRptsSrchIn").val() === 'undefined' ? 'Both' : $("#allRptsSrchIn").val();
    var pageNo = typeof $("#allRptsPageNo").val() === 'undefined' ? 1 : $("#allRptsPageNo").val();
    var limitSze = typeof $("#allRptsDsplySze").val() === 'undefined' ? 10 : $("#allRptsDsplySze").val();
    var sortBy = typeof $("#allRptsSortBy").val() === 'undefined' ? '' : $("#allRptsSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllRpts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllRpts(actionText, slctr, linkArgs);
    }
}

function getOneRptsForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=9&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdRptID=' + pKeyID;
    var srchFor = typeof $("#rptRunsSrchFor").val() === 'undefined' ? '%' : $("#rptRunsSrchFor").val();
    var srchIn = typeof $("#rptRunsSrchIn").val() === 'undefined' ? 'Both' : $("#rptRunsSrchIn").val();
    var pageNo = typeof $("#rptRunsPageNo").val() === 'undefined' ? 1 : $("#rptRunsPageNo").val();
    var limitSze = typeof $("#rptRunsDsplySze").val() === 'undefined' ? 10 : $("#rptRunsDsplySze").val();
    var sortBy = typeof $("#rptRunsSortBy").val() === 'undefined' ? '' : $("#rptRunsSortBy").val();
    srchFor = "%";
    pageNo = 1;
    lnkArgs = lnkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;

    doAjaxWthCallBck(lnkArgs, 'rptsDetailInfo', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#rptRunsTable')) {
            var table1 = $('#rptRunsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptRunsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#allRptsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        /*$('[data-toggle="tabajxrpt"]').click(function (e) {
         e.preventDefault();
         var $this = $(this);
         var targ = $this.attr('href');
         var dttrgt = $this.attr('data-rhodata');
         var linkArgs = 'grp=9&typ=1' + dttrgt;
         return openATab(targ, linkArgs);
         });*/
    });
}

function getOneNewRptForm()
{
    var lnkArgs = 'grp=9&typ=1&pg=1&vtyp=9';
    doAjaxWthCallBck(lnkArgs, 'rptsDetailInfo', 'PasteDirect', '', '', '', function () {
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
        if (!$.fn.DataTable.isDataTable('#rptsStpRolesTable')) {
            var table2 = $('#rptsStpRolesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptsStpRolesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#rptsStpPrgmsTable')) {
            var table2 = $('#rptsStpPrgmsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptsStpPrgmsTable').wrap('<div class="dataTables_scroll"/>');
        }

        $('[data-toggle="tabajxrptdet"]').off('click');
        $('[data-toggle="tabajxrptdet"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            $(targ + 'tab').tab('show');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=9&typ=1' + dttrgt;
            if (targ.indexOf('rptMainSQLTbPage') >= 0) {
                $('#rptMainSQLTbPage').removeClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptPreSQLTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').removeClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptPostSQLTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').removeClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptParamsTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').removeClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptSubPrgmsTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').removeClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptAlwdRolesTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').removeClass('hideNotice');
            }
            return;
        });
    });

}

function getAllRptRuns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#rptRunsSrchFor").val() === 'undefined' ? '%' : $("#rptRunsSrchFor").val();
    var srchIn = typeof $("#rptRunsSrchIn").val() === 'undefined' ? 'Both' : $("#rptRunsSrchIn").val();
    var pageNo = typeof $("#rptRunsPageNo").val() === 'undefined' ? 1 : $("#rptRunsPageNo").val();
    var limitSze = typeof $("#rptRunsDsplySze").val() === 'undefined' ? 10 : $("#rptRunsDsplySze").val();
    var sortBy = typeof $("#rptRunsSortBy").val() === 'undefined' ? '' : $("#rptRunsSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncRptRuns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllRptRuns(actionText, slctr, linkArgs);
    }
}


function getAllPrcsRuns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allPrcsRunsSrchFor").val() === 'undefined' ? '%' : $("#allPrcsRunsSrchFor").val();
    var srchIn = typeof $("#allPrcsRunsSrchIn").val() === 'undefined' ? 'Both' : $("#allPrcsRunsSrchIn").val();
    var pageNo = typeof $("#allPrcsRunsPageNo").val() === 'undefined' ? 1 : $("#allPrcsRunsPageNo").val();
    var limitSze = typeof $("#allPrcsRunsDsplySze").val() === 'undefined' ? 10 : $("#allPrcsRunsDsplySze").val();
    var sortBy = typeof $("#allPrcsRunsSortBy").val() === 'undefined' ? '' : $("#allPrcsRunsSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllPrcsRuns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPrcsRuns(actionText, slctr, linkArgs);
    }
}

function getOneRptsLogForm(pKeyID)
{
    var lnkArgs = 'grp=1&typ=11&q=Report Log Message&run_id=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLgZI', 'ShowDialog', 'REPORT/PROCESS RUN LOGS FOR (' + pKeyID + ')', 'myFormsModalTitleLgZI', 'myFormsModalBodyLgZI', function () {
        if (!$.fn.DataTable.isDataTable('#rptParamsLogTable')) {
            var table1 = $('#rptParamsLogTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptParamsLogTable').wrap('<div class="dataTables_scroll"/>');
        }
    });
}

function getOneRptsParamsForm(pKeyID, prvRunID, rptName, vwtype, alertID, loadOption)
{
    if (typeof loadOption === 'undefined' || loadOption === null)
    {
        loadOption = 'ShowDialog';
    }
    var lnkArgs = 'grp=9&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdRptID=' + pKeyID +
            "&sbmtdPrvRunID=" + prvRunID +
            "&sbmtdAlrtID=" + alertID;
    rptName = urldecode(rptName);
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', loadOption, 'PARAMETERS FOR (' + rptName + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        if (!$.fn.DataTable.isDataTable('#rptParamsTable')) {
            var table1 = $('#rptParamsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptParamsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#rptParamsForm').submit(function (e) {
            e.preventDefault();
            return false;
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
        $('.form_date_tme1').datetimepicker({
            format: "yyyy-mm-dd hh:ii:ss",
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

        $('.form_date1').datetimepicker({
            format: "yyyy-mm-dd",
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

}

function reloadParams()
{
    var pKeyID = typeof $("#rptParamRptID").val() === 'undefined' ? -1 : $("#rptParamRptID").val();
    var alertID = typeof $("#rptParamAlrtID").val() === 'undefined' ? -1 : $("#rptParamAlrtID").val();
    var valValue = typeof $("#rptParamPrvRunID").val() === 'undefined' ? -1 : $("#rptParamPrvRunID").val();
    var rptName = typeof $("#rptParamRptName").val() === 'undefined' ? '' : $("#rptParamRptName").val();
    getOneRptsParamsForm(pKeyID, valValue, rptName, 2, alertID, 'ReloadDialog');
}

function closeOneRptsRnStsForm()
{
    globaltmerCntr = 0;
    window.clearInterval(globaltmer);
    window.clearInterval(globaltmer);
    ClearAllIntervals();
    window.clearInterval(globaltmer);
    ClearAllIntervals();
    $('#myFormsModalNrmlZI').modal('hide');
}

function getOneRptsRnStsForm(pKeyID, prvRunID, vwtype, mustReRun, alrtID)
{
    var lnkArgs = 'grp=9&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdRptID=' + pKeyID + "&sbmtdRunID=" + prvRunID + "&sbmtdAlrtID=" + alrtID + "&mustReRun=" + mustReRun;
    var slctdParams = "";
    $('#rptParamsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            var rndmNum = $(el).attr('id').split("_")[1];
            slctdParams = slctdParams + $('#rptParamsRow' + rndmNum + '_ParamID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                    + $('#rptParamsRow' + rndmNum + '_ParamVal').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
        }
    });
    lnkArgs = lnkArgs + "&slctdParams=" + urlencode(slctdParams.slice(0, -1));
    $('#myFormsModalNrml').modal('hide');
    var actionTxt = 'ShowDialog';
    if ((($("#myFormsModalNrmlZI").data('bs.modal') || {}).isShown)
            || $("#myFormsModalNrmlBodyZI").text().trim() !== "")
    {
        actionTxt = 'ReloadDialog';
    }
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrmlZI', actionTxt, 'Report Run Details FOR (' + prvRunID + ')', 'myFormsModalNrmlTitleZI', 'myFormsModalNrmlBodyZI', function () {
        if (!$.fn.DataTable.isDataTable('#rptParamsStsTable')) {
            var table1 = $('#rptParamsStsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptParamsStsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#rptParamsStsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        if (prvRunID <= 0)
        {
            if (alrtID > 0)
            {
                if (($("#myFormsModalNrmlZI").data('bs.modal') || {}).isShown)
                {
                } else {
                    getAllAlrtRuns('', '#alrtsDetailInfo', 'grp=9&typ=1&pg=2&vtyp=1&sbmtdAlrtID=' + alrtID);
                }
            } else {
                if (($("#myFormsModalNrmlZI").data('bs.modal') || {}).isShown)
                {
                    getMyMdlRptRuns('', 'ReloadDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=' + pKeyID);
                } else {
                    getAllRptRuns('', '#rptsDetailInfo', 'grp=9&typ=1&pg=1&vtyp=1&sbmtdRptID=' + pKeyID);
                }
            }
            prvRunID = typeof $("#rptParamsStsRunID").val() === 'undefined' ? -1 : $("#rptParamsStsRunID").val();
            if (prvRunID > 0)
            {
                autoRfrshRptsRnSts(pKeyID, prvRunID, 0, alrtID);
            }
        } else if (prvRunID > 0 && mustReRun === '1')
        {
            autoRfrshRptsRnSts(pKeyID, prvRunID, 0, alrtID);
        }
    });
}

var globaltmer;
var globaltmerCntr = 0;

function autoRfrshRptsRnSts(pKeyID, prvRunID, mstStop, alrtID)
{
    if (mstStop > 0)
    {
        globaltmerCntr = 0;
        window.clearInterval(globaltmer);
        window.clearInterval(globaltmer);
        ClearAllIntervals();
        window.clearInterval(globaltmer);
        ClearAllIntervals();
        getOneRptsRnStsForm(pKeyID, prvRunID, 3, '0', alrtID);
        return;
    }
    var runPrcnt = typeof $("#rptParamsStsPrgrs").val() === 'undefined' ? -1 : parseInt($("#rptParamsStsPrgrs").val());
    if (runPrcnt < 100 && mstStop <= 0)
    {
        var lnkArgs = 'grp=9&typ=1&pg=1&vtyp=3&sbmtdRptID=' + pKeyID + "&sbmtdRunID=" + prvRunID + "&autoRfrsh=1";
        doAjaxWthCallBck(lnkArgs, 'myFormsModalNrmlZI', 'ReloadDialog', 'Report Run Details FOR (' + prvRunID + ')', 'myFormsModalNrmlTitleZI', 'myFormsModalNrmlBodyZI', function () {
            if (!$.fn.DataTable.isDataTable('#rptParamsStsTable')) {
                var table1 = $('#rptParamsStsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#rptParamsStsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#rptParamsStsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            runPrcnt = typeof $("#rptParamsStsPrgrs").val() === 'undefined' ? -1 : parseInt($("#rptParamsStsPrgrs").val());
            if ((runPrcnt < 100 && globaltmerCntr <= 0 && mstStop <= 0))
            {
                globaltmerCntr = globaltmerCntr + 1;
                globaltmer = window.setInterval(function () {
                    autoRfrshRptsRnSts(pKeyID, prvRunID, mstStop, alrtID);
                }, 5000);
            } else if (runPrcnt >= 100 || mstStop > 0) {
                window.clearInterval(globaltmer);
                globaltmerCntr = 0;
                window.clearInterval(globaltmer);
                ClearAllIntervals();
                if (alrtID > 0)
                {
                    if (($("#myFormsModalNrmlZI").data('bs.modal') || {}).isShown)
                    {
                    } else {
                        getAllAlrtRuns('', '#alrtsDetailInfo', 'grp=9&typ=1&pg=2&vtyp=1&sbmtdAlrtID=' + alrtID);
                    }
                } else {
                    if (($("#myFormsModalNrmlZI").data('bs.modal') || {}).isShown)
                    {
                        getMyMdlRptRuns('', 'ReloadDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=' + pKeyID);
                    } else {
                        getAllRptRuns('', '#rptsDetailInfo', 'grp=9&typ=1&pg=1&vtyp=1&sbmtdRptID=' + pKeyID);
                    }
                }
            }
        });
    } else
    {
        globaltmer = window.clearInterval(globaltmer);
        globaltmerCntr = 0;
        window.clearInterval(globaltmer);
        ClearAllIntervals();
        window.clearInterval(globaltmer);
        ClearAllIntervals();
        getOneRptsRnStsForm(pKeyID, prvRunID, 3, '0', alrtID);
    }
}

function cancelRptRun(pKeyID, prvRunID, alrtID)
{
    globaltmerCntr = 0;
    window.clearInterval(globaltmer);
    window.clearInterval(globaltmer);
    ClearAllIntervals();
    window.clearInterval(globaltmer);
    ClearAllIntervals();
    var lnkArgs = 'grp=9&typ=1&pg=1&vtyp=4&sbmtdRptID=' + pKeyID + "&sbmtdRunID=" + prvRunID + "";
    doAjaxWthCallBck(lnkArgs, 'myFormsModal', 'ShowDialog', 'System Alert!', 'myFormsModalTitle', 'myFormsModalBody', function () {
        window.clearInterval(globaltmer);
        globaltmerCntr = 0;
        $('#myFormsModalNrmlZI').modal('hide');
        ClearAllIntervals();
        autoRfrshRptsRnSts(pKeyID, prvRunID, 1, alrtID);
    });
    autoRfrshRptsRnSts(pKeyID, prvRunID, 1, alrtID);
}
/*
 if (alrtID > 0)
 {
 if (($("#myFormsModalNrmlZI").data('bs.modal') || {}).isShown)
 {
 } else {
 getAllAlrtRuns('', '#alrtsDetailInfo', 'grp=9&typ=1&pg=2&vtyp=1&sbmtdAlrtID=' + alrtID);
 }
 } else {
 if (($("#myFormsModalNrmlZI").data('bs.modal') || {}).isShown)
 {
 getMyMdlRptRuns('', 'ReloadDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=' + pKeyID);
 } else {
 getAllRptRuns('', '#rptsDetailInfo', 'grp=9&typ=1&pg=1&vtyp=1&sbmtdRptID=' + pKeyID);
 }
 }*/
function rfrshCncldRptRun(pKeyID, alrtID)
{
    $('#myFormsModal').modal('hide');
    if (alrtID > 0)
    {
        if (typeof $("#alrtsDetailInfo").html() === 'undefined')
        {
        } else {
            getAllAlrtRuns('', '#alrtsDetailInfo', 'grp=9&typ=1&pg=2&vtyp=1&sbmtdAlrtID=' + alrtID);
        }
    } else {
        if (typeof $("#rptsDetailInfo").html() === 'undefined')
        {
            getMyMdlRptRuns('', 'ReloadDialog', 'grp=9&typ=1&pg=1&vtyp=50&sbmtdRptID=' + pKeyID);
        } else {
            getAllRptRuns('', '#rptsDetailInfo', 'grp=9&typ=1&pg=1&vtyp=1&sbmtdRptID=' + pKeyID);
        }
    }
}

function getAllSchdls(actionText, slctr, linkArgs, useDiag)
{
    if (typeof useDiag === 'undefined') {
        useDiag = typeof $("#shdSchdlUseDiag").val() === 'undefined' ? 0 : $("#shdSchdlUseDiag").val();
    }
    var srchFor = typeof $("#allSchdlsSrchFor").val() === 'undefined' ? '%' : $("#allSchdlsSrchFor").val();
    var srchIn = typeof $("#allSchdlsSrchIn").val() === 'undefined' ? 'Both' : $("#allSchdlsSrchIn").val();
    var pageNo = typeof $("#allSchdlsPageNo").val() === 'undefined' ? 1 : $("#allSchdlsPageNo").val();
    var limitSze = typeof $("#allSchdlsDsplySze").val() === 'undefined' ? 10 : $("#allSchdlsDsplySze").val();
    var sortBy = typeof $("#allSchdlsSortBy").val() === 'undefined' ? '' : $("#allSchdlsSortBy").val();
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
    var actionTxt = 'ShowDialog';
    if ((($("#myFormsModalLg").data('bs.modal') || {}).isShown)
            || $("#myFormsModalBodyLg").text().trim() !== "")
    {
        actionTxt = 'ReloadDialog';
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo +
            "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&useDiag=" + useDiag;
    if (useDiag <= 0) {
        openATab(slctr, linkArgs);
    } else {
        doAjaxWthCallBck(linkArgs, 'myFormsModalLg', actionTxt, 'Report/Process Schedules', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
            var table1;
            var table2;
            if (!$.fn.DataTable.isDataTable('#allSchdlPrmsTable')) {
                table1 = $('#allSchdlPrmsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSchdlPrmsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSchdlsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allSchdlsTable')) {
                table2 = $('#allSchdlsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSchdlsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSchdlsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#allSchdlsRow' + rndmNum + '_RptID').val() === 'undefined' ? '%' : $('#allSchdlsRow' + rndmNum + '_RptID').val();
                var pkeyID1 = typeof $('#allSchdlsRow' + rndmNum + '_SchdlID').val() === 'undefined' ? '%' : $('#allSchdlsRow' + rndmNum + '_SchdlID').val();
                getOneSchdlsForm(pkeyID, pkeyID1, 6);
            });
            $('#allSchdlsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
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
    }
}

function enterKeyFuncAllSchdls(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSchdls(actionText, slctr, linkArgs);
    }
}
function getOneSchdlsNwForm()
{
    var pkeyID = typeof $("#nwRptSchdlRptID").val() === 'undefined' ? -1 : $("#nwRptSchdlRptID").val();
    var pkeyID1 = -1;
    getOneSchdlsForm(pkeyID, pkeyID1, 7);
}

function getOneSchdlsForm(pKeyID, pKeyID1, vwtype)
{
    var lnkArgs = 'grp=9&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdRptID=' + pKeyID + "&sbmtdSchdlID=" + pKeyID1;
    doAjaxWthCallBck(lnkArgs, 'allSchdlsDetailInfo', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#allSchdlPrmsTable')) {
            var table1 = $('#allSchdlPrmsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allSchdlPrmsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#allSchdlsForm').submit(function (e) {
            e.preventDefault();
            return false;
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
}

function getAllAlrts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allAlrtsSrchFor").val() === 'undefined' ? '%' : $("#allAlrtsSrchFor").val();
    var srchIn = typeof $("#allAlrtsSrchIn").val() === 'undefined' ? 'Both' : $("#allAlrtsSrchIn").val();
    var pageNo = typeof $("#allAlrtsPageNo").val() === 'undefined' ? 1 : $("#allAlrtsPageNo").val();
    var limitSze = typeof $("#allAlrtsDsplySze").val() === 'undefined' ? 10 : $("#allAlrtsDsplySze").val();
    var sortBy = typeof $("#allAlrtsSortBy").val() === 'undefined' ? '' : $("#allAlrtsSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllAlrts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllAlrts(actionText, slctr, linkArgs);
    }
}

function getOneAlrtsForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=9&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdAlrtID=' + pKeyID;
    var srchFor = typeof $("#rptRunsSrchFor").val() === 'undefined' ? '%' : $("#rptRunsSrchFor").val();
    var srchIn = typeof $("#rptRunsSrchIn").val() === 'undefined' ? 'Both' : $("#rptRunsSrchIn").val();
    var pageNo = typeof $("#rptRunsPageNo").val() === 'undefined' ? 1 : $("#rptRunsPageNo").val();
    var limitSze = typeof $("#rptRunsDsplySze").val() === 'undefined' ? 10 : $("#rptRunsDsplySze").val();
    var sortBy = typeof $("#rptRunsSortBy").val() === 'undefined' ? '' : $("#rptRunsSortBy").val();
    srchFor = "%";
    pageNo = 1;
    lnkArgs = lnkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;

//alert(lnkArgs);
    doAjaxWthCallBck(lnkArgs, 'alrtsDetailInfo', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#alrtRunsTable')) {
            var table1 = $('#alrtRunsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#alrtRunsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#allAlrtsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            /*var numelms=$('[data-toggle="tabajxgst"]').size();*/
            $('[data-toggle="tabajxalrt"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=9&typ=1' + dttrgt;
                return openATab(targ, linkArgs);
            });
        });
    });
}
function getOneAlertStpForm(pKeyID, vwtype, actionTxt, shdDplct)
{
    if (typeof shdDplct === 'undefined' || shdDplct === null)
    {
        shdDplct = '0';
    }
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    var sbmtdAlertRptID = typeof $("#sbmtdAlertRptID").val() === 'undefined' ? -1 : $("#sbmtdAlertRptID").val();
    var lnkArgs = 'grp=9&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdAlrtID=' + pKeyID + '&sbmtdRptID=' + sbmtdAlertRptID + '&shdDplct=' + shdDplct;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Alert Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#allOtherInputData99').val('0');

        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getAllAlrts('', '#allmodules', 'grp=9&typ=1&pg=2&vtyp=0');
            $(e.currentTarget).unbind();
        });
        $('[data-toggle="tooltip"]').tooltip();
        if (!$.fn.DataTable.isDataTable('#alrtSchdlPrmsTable')) {
            var table1 = $('#alrtSchdlPrmsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#alrtSchdlPrmsTable').wrap('<div class="dataTables_scroll"/>');
        }
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
        $('.form_date_tme1').datetimepicker({
            format: "yyyy-mm-dd hh:ii:ss",
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

        $('.form_date1').datetimepicker({
            format: "yyyy-mm-dd",
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
        $("#allOtherFileInput1").val('');
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
                                $('#alrtsMsgBody').summernote('createLink', {
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
        $('#alrtsMsgBody').summernote({
            minHeight: 262,
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
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['codeview']],
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
                                $('#alrtsMsgBody').summernote("insertImage", inptUrl, 'filename');
                            });
                        }
                    }
        });
        var markupStr1 = typeof $("#alrtsMsgBodyEncded").val() === 'undefined' ? '' : $("#alrtsMsgBodyEncded").val();
        $('#alrtsMsgBody').summernote('code', urldecode(markupStr1));
    });
}

function getAllAlrtRuns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#alrtRunsSrchFor").val() === 'undefined' ? '%' : $("#alrtRunsSrchFor").val();
    var srchIn = typeof $("#alrtRunsSrchIn").val() === 'undefined' ? 'Both' : $("#alrtRunsSrchIn").val();
    var pageNo = typeof $("#alrtRunsPageNo").val() === 'undefined' ? 1 : $("#alrtRunsPageNo").val();
    var limitSze = typeof $("#alrtRunsDsplySze").val() === 'undefined' ? 10 : $("#alrtRunsDsplySze").val();
    var sortBy = typeof $("#alrtRunsSortBy").val() === 'undefined' ? '' : $("#alrtRunsSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAlrtRuns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllAlrtRuns(actionText, slctr, linkArgs);
    }
}

function getOneAlrtsDetForm(pKeyID)
{
    var lnkArgs = 'grp=9&typ=1&pg=2&vtyp=2&sbmtdMsgSntID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', 'ShowDialog', 'Alert Message Details for (' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#alertDetTable').wrap('<div class="dataTables_scroll"/>');
    });
}

var prgstimerid2;
var exprtBtn;
var exprtBtn2;
function importReports()
{
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: 'Import Reports?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT REPORTS</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>',
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
                                        }
                                        ;
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
                                                if (isFileValid == true) {
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
                                        $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing Reports...Please Wait...</span>');
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var number = "";
                                            var processName = "";
                                            var processDesc = "";
                                            var processQuery = "";
                                            var ownerModule = "";
                                            var processType = "";
                                            var processRnnr = "";
                                            var jrxmlFile = "";
                                            var colsToGrp = "";
                                            var colsToCnt = "";
                                            var colsToFrmt = "";
                                            var colsToSum = "";
                                            var colsToAvrg = "";
                                            var outptType = "";
                                            var orntation = "";
                                            var layout = "";
                                            var dlmtr = "";
                                            var dtailImgCols = "";
                                            var preQuery = "";
                                            var postQuery = "";
                                            var isEnbld = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            number = data[row][item];
                                                            break;
                                                        case 2:
                                                            processName = data[row][item];
                                                            break;
                                                        case 3:
                                                            processDesc = data[row][item];
                                                            break;
                                                        case 4:
                                                            processQuery = data[row][item];
                                                            break;
                                                        case 5:
                                                            ownerModule = data[row][item];
                                                            break;
                                                        case 6:
                                                            processType = data[row][item];
                                                            break;
                                                        case 7:
                                                            processRnnr = data[row][item];
                                                            break;
                                                        case 8:
                                                            jrxmlFile = data[row][item];
                                                            break;
                                                        case 9:
                                                            colsToGrp = data[row][item];
                                                            break;
                                                        case 10:
                                                            colsToCnt = data[row][item];
                                                            break;
                                                        case 11:
                                                            colsToFrmt = data[row][item];
                                                            break;
                                                        case 12:
                                                            colsToSum = data[row][item];
                                                            break;
                                                        case 13:
                                                            colsToAvrg = data[row][item];
                                                            break;
                                                        case 14:
                                                            outptType = data[row][item];
                                                            break;
                                                        case 15:
                                                            orntation = data[row][item];
                                                            break;
                                                        case 16:
                                                            layout = data[row][item];
                                                            break;
                                                        case 17:
                                                            dlmtr = data[row][item];
                                                            break;
                                                        case 18:
                                                            dtailImgCols = data[row][item];
                                                            break;
                                                        case 19:
                                                            preQuery = data[row][item];
                                                            break;
                                                        case 20:
                                                            postQuery = data[row][item];
                                                            break;
                                                        case 21:
                                                            isEnbld = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: 'Error-Validating Selected File',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                    ;
                                                }
                                                if (rwCntr === 0) {
                                                    //alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());
                                                    if (number.toUpperCase() === "NO." && processName.toUpperCase() === "PROCESS NAME**"
                                                            && isEnbld.toUpperCase() === "IS ENABLED?")
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: 'Error-Import Reports',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                if (processQuery.trim() !== "" && processName.trim() !== ""
                                                        && ownerModule.trim() !== ""
                                                        && isEnbld.trim() !== "")
                                                {
                                                    //if valid data
                                                    /*.replace(/(~)+/g, "{-;-;}") .replace(/(\|)+/g, "{:;:;}")*/
                                                    dataToSend = dataToSend + number.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + processName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + processDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + processQuery.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + ownerModule.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + processType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + processRnnr.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + jrxmlFile.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + colsToGrp.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + colsToCnt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + colsToFrmt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + colsToSum.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + colsToAvrg.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + outptType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + orntation.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + layout.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + dlmtr.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + dtailImgCols.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + preQuery.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + postQuery.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + isEnbld.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output += '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' + vldRwCntr;
                                            output += '<br/>Total No. of Rows:' + rwCntr + '</span>';
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import Reports',
                                                size: 'small',
                                                message: 'Error:' + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                }
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
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveRptsPrcs(dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import Reports',
                                                size: 'small',
                                                message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () {
                                                }
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

function saveRptsPrcs(dataToSend)
{
    if (dataToSend.trim() === '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;">No Data to Send!</span></p>'
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Importing Reports/Processes',
        size: 'small',
        message: '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Reports/Processes...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            getAllRpts('clear', '#allmodules', 'grp=9&typ=1&pg=1&vtyp=0');
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
                    grp: 9,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 1,
                    dataToSend: dataToSend
                }
            });
            prgstimerid2 = window.setInterval(rfrshSaveRptsPrcs, 1000);
        });
    });
}

function rfrshSaveRptsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 9,
            typ: 1,
            pg: 1,
            q: 'UPDATE',
            actyp: 2
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

function delRpts(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var rptNm = '';
    if (typeof $('#allRptsRow' + rndmNum + '_RptID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allRptsRow' + rndmNum + '_RptID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        rptNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Remove Report/Process?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Report/Process?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Report/Process?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Report/Process...Please Wait...</p>'
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 2,
                                    rptNm: rptNm,
                                    pKeyID: pKeyID
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

function exprtRpts()
{
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
            '<p style="color:#000;">' +
            'How many Reports/Processes will you like to Export?' +
            '<br/>1=No Reports/Processes(Empty Template)' +
            '<br/>2=All Reports/Processes' +
            '<br/>3-Infinity=Specify the exact number of Reports/Processes to Export<br/>' +
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
        title: 'Export Reports/Processes!',
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {
        },
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
                    if (!isNumber(inptNum))
                    {
                        var dialog = bootbox.alert({
                            title: 'Exporting Reports/Processes',
                            size: 'small',
                            message: 'Please provide a valid Number!',
                            callback: function () {
                            }
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 1,
                                    q: 'UPDATE',
                                    actyp: 3,
                                    inptNum: inptNum
                                }
                            });
                            prgstimerid2 = window.setInterval(rfrshExprtRptsPrcs, 1000);
                        });
                    }
                }
            }]
    });
}

function rfrshExprtRptsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 9,
            typ: 1,
            pg: 1,
            q: 'UPDATE',
            actyp: 4
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf('Error') !== -1)
                {
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
                $("#msgAreaExprt").html('<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message);
                document.getElementById("msgAreaExprt").innerHTML = '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function getOneReportStpForm(pKeyID, vwtype, actionTxt, shdDplct, srcCaller)
{
    if (typeof shdDplct === 'undefined' || shdDplct === null)
    {
        shdDplct = '0';
    }
    if (typeof actionTxt === 'undefined' || actionTxt === null)
    {
        actionTxt = 'ShowDialog';
    }
    if (typeof srcCaller === 'undefined' || srcCaller === null)
    {
        srcCaller = 'NORMAL';
    }
    var lnkArgs = 'grp=9&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdRptID=' + pKeyID + '&shdDplct=' + shdDplct + '&srcCaller=' + srcCaller;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Report/Program Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        if (!$.fn.DataTable.isDataTable('#rptsStpRolesTable')) {
            var table2 = $('#rptsStpRolesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptsStpRolesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#rptsStpPrgmsTable')) {
            var table2 = $('#rptsStpPrgmsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#rptsStpPrgmsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#rptsStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            if (srcCaller === 'NORMAL') {
                getAllRpts('Clear', '#allmodules', 'grp=9&typ=1&pg=1&vtyp=0');
            } else if (srcCaller === 'ALL_RUNS') {
                getAllPrcsRuns('', '#allmodules', 'grp=9&typ=1&pg=5&vtyp=0');
            }
            $(e.currentTarget).unbind();
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="tabajxrptdet"]').off('click');
        $('[data-toggle="tabajxrptdet"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            $(targ + 'tab').tab('show');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=9&typ=1' + dttrgt;
            if (targ.indexOf('rptMainSQLTbPage') >= 0) {
                $('#rptMainSQLTbPage').removeClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptPreSQLTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').removeClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptPostSQLTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').removeClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptParamsTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').removeClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptSubPrgmsTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').removeClass('hideNotice');
                $('#rptAlwdRolesTbPage').addClass('hideNotice');
            } else if (targ.indexOf('rptAlwdRolesTbPage') >= 0) {
                $('#rptMainSQLTbPage').addClass('hideNotice');
                $('#rptPreSQLTbPage').addClass('hideNotice');
                $('#rptPostSQLTbPage').addClass('hideNotice');
                $('#rptParamsTbPage').addClass('hideNotice');
                $('#rptSubPrgmsTbPage').addClass('hideNotice');
                $('#rptAlwdRolesTbPage').removeClass('hideNotice');
            }
            return;
        });
    });
}

function saveReportsForm(srcCaller)
{
    if (typeof srcCaller === 'undefined' || srcCaller === null)
    {
        srcCaller = 'NORMAL';
    }
    var rptsRptID = typeof $("#rptsRptID").val() === 'undefined' ? -1 : $("#rptsRptID").val();
    var rptsRptNm = typeof $("#rptsRptNm").val() === 'undefined' ? "" : $("#rptsRptNm").val();
    var rptsRptDesc = typeof $("#rptsRptDesc").val() === 'undefined' ? '' : $("#rptsRptDesc").val();
    var rptsOwnrMdl = typeof $("#rptsOwnrMdl").val() === 'undefined' ? "" : $("#rptsOwnrMdl").val();
    var rptsProcessTyp = typeof $("#rptsProcessTyp").val() === 'undefined' ? '' : $("#rptsProcessTyp").val();
    var rptsPrcsRnnr = typeof $("#rptsPrcsRnnr").val() === 'undefined' ? '' : $("#rptsPrcsRnnr").val();
    var rptsColsToGrp = typeof $("#rptsColsToGrp").val() === 'undefined' ? '' : $("#rptsColsToGrp").val();
    var rptsColsToCnt = typeof $("#rptsColsToCnt").val() === 'undefined' ? '' : $("#rptsColsToCnt").val();
    var rptsColsToFrmt = typeof $("#rptsColsToFrmt").val() === 'undefined' ? '' : $("#rptsColsToFrmt").val();
    var rptsColsToSum = typeof $("#rptsColsToSum").val() === 'undefined' ? '' : $("#rptsColsToSum").val();
    var rptsColsToAvrg = typeof $("#rptsColsToAvrg").val() === 'undefined' ? '' : $("#rptsColsToAvrg").val();
    var rptsOutputType = typeof $("#rptsOutputType").val() === 'undefined' ? '' : $("#rptsOutputType").val();
    var rptsOrntn = typeof $("#rptsOrntn").val() === 'undefined' ? '' : $("#rptsOrntn").val();
    var rptsLayout = typeof $("#rptsLayout").val() === 'undefined' ? '' : $("#rptsLayout").val();
    var rptsDelimiter = typeof $("#rptsDelimiter").val() === 'undefined' ? '' : $("#rptsDelimiter").val();
    var rptsDetImgCols = typeof $("#rptsDetImgCols").val() === 'undefined' ? '' : $("#rptsDetImgCols").val();
    var rptsQuerySQL = typeof $("#rptsQuerySQL").val() === 'undefined' ? '' : $("#rptsQuerySQL").val();
    var rptsPreSQL = typeof $("#rptsPreSQL").val() === 'undefined' ? '' : $("#rptsPreSQL").val();
    var rptsPostSQL = typeof $("#rptsPostSQL").val() === 'undefined' ? '' : $("#rptsPostSQL").val();
    var rptsJrxmlFileSrc = typeof $("#rptsJrxmlFileSrc").val() === 'undefined' ? '' : $("#rptsJrxmlFileSrc").val();
    var rptsIsEnabled = typeof $("input[name='rptsIsEnabled']:checked").val() === 'undefined' ? 'NO' : $("input[name='rptsIsEnabled']:checked").val();
    var isNew = typeof $("#isNew123").val() === 'undefined' ? 0 : $("#isNew123").val();

    var errMsg = "";
    if (rptsRptNm.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Report/Process Name cannot be empty!</span></p>';
    }
    if (rptsOwnrMdl.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Report/Process Owner Module cannot be empty!</span></p>';
    }
    if (rptsProcessTyp.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Report/Process Type cannot be empty!</span></p>';
    }
    if (rptsPrcsRnnr.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Report/Process Runner cannot be empty!</span></p>';
    }
    if (rptsOutputType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Output Type cannot be empty!</span></p>';
    }
    if (rptsOrntn.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Orientation cannot be empty!</span></p>';
    }
    if (rptsQuerySQL.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">SQL Query or Script cannot be empty!</span></p>';
    }
    var slctdRptParams = "";
    var isVld = true;
    $('#rptsStpPrmsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#rptsStpPrmsRow' + rndmNum + '_ParamID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var isRqrd = typeof $("input[name='rptsStpPrmsRow" + rndmNum + "_IsRqrd']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    var isDsplyd = typeof $("input[name='rptsStpPrmsRow" + rndmNum + "_IsDsplyd']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    var parmNm = $('#rptsStpPrmsRow' + rndmNum + '_ParamNm').val();
                    if (parmNm.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Parameter Name for Row ' + i + ' cannot be empty!</span></p>';
                        $('#rptsStpPrmsRow' + rndmNum + '_ParamNm').addClass('rho-error');
                    } else {
                        $('#rptsStpPrmsRow' + rndmNum + '_ParamNm').removeClass('rho-error');
                    }
                    var sQLRep = $('#rptsStpPrmsRow' + rndmNum + '_SQLRep').val();
                    if (sQLRep.trim() === '')
                    {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">SQL Rep. for Row ' + i + ' cannot be empty!</span></p>';
                        $('#rptsStpPrmsRow' + rndmNum + '_SQLRep').addClass('rho-error');
                    } else {
                        $('#rptsStpPrmsRow' + rndmNum + '_SQLRep').removeClass('rho-error');
                    }
                    if (isVld === false)
                    {
                        /*Do Nothing*/
                    } else {
                        slctdRptParams = slctdRptParams + $('#rptsStpPrmsRow' + rndmNum + '_ParamID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrmsRow' + rndmNum + '_ParamNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrmsRow' + rndmNum + '_SQLRep').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrmsRow' + rndmNum + '_DfltVal').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrmsRow' + rndmNum + '_LovNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrmsRow' + rndmNum + '_LovID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrmsRow' + rndmNum + '_DataTyp').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrmsRow' + rndmNum + '_DateFrmt').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + isRqrd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + isDsplyd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });

    var slctdRptAllwdRoles = "";
    isVld = true;
    $('#rptsStpRolesTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#rptsStpRolesRow' + rndmNum + '_RoleID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var roleID = $('#rptsStpRolesRow' + rndmNum + '_RoleID').val();
                    var roleNm = typeof $('#rptsStpRolesRow' + rndmNum + '_RoleNm').val() === 'undefined' ? '' : $('#rptsStpRolesRow' + rndmNum + '_RoleNm').val();
                    if (isVld === false || roleID <= 0 || roleNm.trim() === '')
                    {
                        /*Do Nothing*/
                    } else {
                        slctdRptAllwdRoles = slctdRptAllwdRoles + $('#rptsStpRolesRow' + rndmNum + '_RoleID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpRolesRow' + rndmNum + '_RoleNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    var slctdRptSbPrgms = "";
    isVld = true;
    $('#rptsStpPrgmsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#rptsStpPrgmsRow' + rndmNum + '_PrgmID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var prgrmID = $('#rptsStpPrgmsRow' + rndmNum + '_PrgmID').val();
                    var prgrmNm = typeof $('#rptsStpPrgmsRow' + rndmNum + '_PrgmNm').val() === 'undefined' ? '' : $('#rptsStpPrgmsRow' + rndmNum + '_PrgmNm').val();
                    if (isVld === false || prgrmID <= 0 || prgrmNm.trim() === '')
                    {
                        /*Do Nothing*/
                    } else {
                        slctdRptSbPrgms = slctdRptSbPrgms + $('#rptsStpPrgmsRow' + rndmNum + '_PrgmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                + $('#rptsStpPrgmsRow' + rndmNum + '_PrgmNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Report/Process',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Report/Process...Please Wait...</p>',
        callback: function () {
            if (isNew > 0 && srcCaller === 'NORMAL') {
                getAllRpts('clear', '#allmodules', 'grp=9&typ=1&pg=1&vtyp=0');
            } else if (srcCaller === 'ALL_RUNS') {
                getAllPrcsRuns('', '#allmodules', 'grp=9&typ=1&pg=5&vtyp=0');
            }
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");

            var formData = new FormData();
            formData.append('rptsJrxmlFile', $('#rptsJrxmlFile')[0].files[0]);
            formData.append('grp', 9);
            formData.append('typ', 1);
            formData.append('pg', 1);
            formData.append('q', 'UPDATE');
            formData.append('actyp', 5);
            formData.append('rptsRptID', rptsRptID);
            formData.append('rptsRptNm', rptsRptNm);
            formData.append('rptsRptDesc', rptsRptDesc);
            formData.append('rptsJrxmlFileSrc', rptsJrxmlFileSrc);

            formData.append('rptsOwnrMdl', rptsOwnrMdl);
            formData.append('rptsProcessTyp', rptsProcessTyp);
            formData.append('rptsPrcsRnnr', rptsPrcsRnnr);
            formData.append('rptsColsToGrp', rptsColsToGrp);

            formData.append('rptsColsToCnt', rptsColsToCnt);
            formData.append('rptsColsToFrmt', rptsColsToFrmt);
            formData.append('rptsColsToSum', rptsColsToSum);
            formData.append('rptsColsToAvrg', rptsColsToAvrg);

            formData.append('rptsOutputType', rptsOutputType);
            formData.append('rptsOrntn', rptsOrntn);
            formData.append('rptsLayout', rptsLayout);
            formData.append('rptsDelimiter', rptsDelimiter);

            formData.append('rptsDetImgCols', rptsDetImgCols);
            formData.append('rptsQuerySQL', rptsQuerySQL);
            formData.append('rptsPreSQL', rptsPreSQL);
            formData.append('rptsPostSQL', rptsPostSQL);
            formData.append('rptsIsEnabled', rptsIsEnabled);
            formData.append('slctdRptParams', slctdRptParams);
            formData.append('slctdRptAllwdRoles', slctdRptAllwdRoles);
            formData.append('slctdRptSbPrgms', slctdRptSbPrgms);
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
                        dialog.find('.bootbox-body').html(result.message);
                        $("#rptsRptID").val(result.rptsRptID);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }});
        });
    });
}

function delRptParam(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var paramNm = "";
    if (typeof $('#rptsStpPrmsRow' + rndmNum + '_ParamID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#rptsStpPrmsRow' + rndmNum + '_ParamID').val();
        paramNm = $('#rptsStpPrmsRow' + rndmNum + '_ParamNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Parameter?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Parameter?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Parameter?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Parameter...Please Wait...</p>',
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    paramNm: paramNm
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

function delRptRole(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var roleNm = "";
    if (typeof $('#rptsStpRolesRow' + rndmNum + '_RptRoleID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> rptsStpRolesRow1_RoleID*/
    } else {
        pKeyID = $('#rptsStpRolesRow' + rndmNum + '_RptRoleID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        roleNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Role?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Role?<br/>Action cannot be Undone!</p>',
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
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Role...Please Wait...</p>',
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 4,
                                    pKeyID: pKeyID,
                                    roleNm: roleNm
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
function delRptPrgm(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var prgmNm = "";
    if (typeof $('#rptsStpPrgmsRow' + rndmNum + '_PkeyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> rptsStpRolesRow1_RoleID*/
    } else {
        pKeyID = $('#rptsStpPrgmsRow' + rndmNum + '_PkeyID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        prgmNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Sub-Program?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Sub-Program?<br/>Action cannot be Undone!</p>',
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 5,
                                    pKeyID: pKeyID,
                                    prgmNm: prgmNm
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

function saveAlertsForm()
{
    var alrtsAlertID = typeof $("#alrtsAlertID").val() === 'undefined' ? -1 : $("#alrtsAlertID").val();
    var alrtsRptID = typeof $("#alrtsRptID").val() === 'undefined' ? -1 : $("#alrtsRptID").val();
    var alrtsAlertNm = typeof $("#alrtsAlertNm").val() === 'undefined' ? "" : $("#alrtsAlertNm").val();
    var alrtsAlrtTyp = typeof $("#alrtsAlrtTyp").val() === 'undefined' ? '' : $("#alrtsAlrtTyp").val();
    var alrtsAlertDesc = typeof $("#alrtsAlertDesc").val() === 'undefined' ? "" : $("#alrtsAlertDesc").val();
    var alrtSchdlsStrtDte = typeof $("#alrtSchdlsStrtDte").val() === 'undefined' ? '' : $("#alrtSchdlsStrtDte").val();
    var alrtsIntrvl = typeof $("#alrtsIntrvl").val() === 'undefined' ? -1 : $("#alrtsIntrvl").val();
    var alrtsIntvlUom = typeof $("#alrtsIntvlUom").val() === 'undefined' ? '' : $("#alrtsIntvlUom").val();
    var alrtsEndHour = typeof $("#alrtsEndHour").val() === 'undefined' ? -1 : $("#alrtsEndHour").val();
    var alrtsMailAttchmnts = typeof $("#alrtsMailAttchmnts").val() === 'undefined' ? '' : $("#alrtsMailAttchmnts").val();
    var alrtsMailSubject = typeof $("#alrtsMailSubject").val() === 'undefined' ? '' : $("#alrtsMailSubject").val();
    var alrtsMailBcc = typeof $("#alrtsMailBcc").val() === 'undefined' ? '' : $("#alrtsMailBcc").val();
    var alrtsMailCc = typeof $("#alrtsMailCc").val() === 'undefined' ? '' : $("#alrtsMailCc").val();
    var alrtsMailTo = typeof $("#alrtsMailTo").val() === 'undefined' ? '' : $("#alrtsMailTo").val();
    var alrtsParamSQL = typeof $("#alrtsParamSQL").val() === 'undefined' ? '' : $("#alrtsParamSQL").val();
    var alrtsRnAtHr = typeof $("input[name='alrtsRnAtHr']:checked").val() === 'undefined' ? 'NO' : $("input[name='alrtsRnAtHr']:checked").val();
    var alrtsRnLnkdRpt = typeof $("input[name='alrtsRnLnkdRpt']:checked").val() === 'undefined' ? 'NO' : $("input[name='alrtsRnLnkdRpt']:checked").val();
    var alrtsIsEnabled = typeof $("input[name='alrtsIsEnabled']:checked").val() === 'undefined' ? 'NO' : $("input[name='alrtsIsEnabled']:checked").val();

    var alrtsMsgBody = typeof $("#alrtsMsgBody").val() === 'undefined' ? '' : ($('#alrtsMsgBody').summernote('code'));

    var isNew = typeof $("#isNew123").val() === 'undefined' ? 0 : $("#isNew123").val();

    var errMsg = "";
    if (alrtsRptID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Report/Process Name cannot be empty!</span></p>';
    }
    if (alrtsAlertNm.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Alert Name cannot be empty!</span></p>';
    }
    if (alrtsAlrtTyp.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Alert Type cannot be empty!</span></p>';
    }
    var strippedMsgBody = alrtsMsgBody.replace(/<\/p>/gi, " ")
            .replace(/<br\/?>/gi, " ")
            .replace(/<\/?[^>]+(>|$)/g, "");
    if (strippedMsgBody.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Message Body cannot be empty!</span></p>';
    }
    if (alrtsAlrtTyp.trim() === 'SMS')
    {
        alrtsMsgBody = strippedMsgBody;
    }
    if (alrtSchdlsStrtDte.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Scheduled Start Date cannot be empty!</span></p>';
    }
    if (alrtsIntvlUom.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Interval UOM cannot be empty!</span></p>';
    }
    if (!isNumber(alrtsIntrvl))
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Repeat Interval must be a Number!</span></p>';
    }
    if (!isNumber(alrtsEndHour))
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">End Hour must be a Number!</span></p>';
    }
    var slctdSchdlPrms = "";
    var isVld = true;
    $('#alrtSchdlPrmsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#alrtSchdlPrmsRow' + rndmNum + '_ParamID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    slctdSchdlPrms = slctdSchdlPrms + $('#alrtSchdlPrmsRow' + rndmNum + '_ParamID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#alrtSchdlPrmsRow' + rndmNum + '_ParamVal').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                }
            }
        }
    });

    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Alert',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Alert...Please Wait...</p>',
        callback: function () {
            if (isNew > 0) {
                getAllAlrts('clear', '#allmodules', 'grp=9&typ=1&pg=2&vtyp=0');
            }
        }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");

            var formData = new FormData();
            formData.append('grp', 9);
            formData.append('typ', 1);
            formData.append('pg', 2);
            formData.append('q', 'UPDATE');
            formData.append('actyp', 1);
            formData.append('alrtsRptID', alrtsRptID);
            formData.append('alrtsAlertID', alrtsAlertID);
            formData.append('alrtsAlertNm', alrtsAlertNm);
            formData.append('alrtsAlrtTyp', alrtsAlrtTyp);

            formData.append('alrtsAlertDesc', alrtsAlertDesc);
            formData.append('alrtSchdlsStrtDte', alrtSchdlsStrtDte);
            formData.append('alrtsIntrvl', alrtsIntrvl);
            formData.append('alrtsIntvlUom', alrtsIntvlUom);

            formData.append('alrtsEndHour', alrtsEndHour);
            formData.append('alrtsMailAttchmnts', alrtsMailAttchmnts);
            formData.append('alrtsMailSubject', alrtsMailSubject);
            formData.append('alrtsMailBcc', alrtsMailBcc);

            formData.append('alrtsMailCc', alrtsMailCc);
            formData.append('alrtsMailTo', alrtsMailTo);
            formData.append('alrtsParamSQL', alrtsParamSQL);
            formData.append('alrtsRnAtHr', alrtsRnAtHr);

            formData.append('alrtsRnLnkdRpt', alrtsRnLnkdRpt);
            formData.append('alrtsIsEnabled', alrtsIsEnabled);
            formData.append('alrtsMsgBody', alrtsMsgBody);
            formData.append('slctdSchdlPrms', slctdSchdlPrms);
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
                        dialog.find('.bootbox-body').html(result.message);
                        $("#alrtsAlertID").val(result.alrtsAlertID);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }});
        });
    });
}

function delAlrts(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var alrtNm = '';
    if (typeof $('#allAlrtsRow' + rndmNum + '_AlrtID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allAlrtsRow' + rndmNum + '_AlrtID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        alrtNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Remove Alert?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Alert?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Alert?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Alert...Please Wait...</p>'
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 1,
                                    alrtNm: alrtNm,
                                    pKeyID: pKeyID
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

function getOneNewAlrtForm()
{
    /*Let user select Report First be4 ajax call*/
    var pKeyID = typeof $("#sbmtdAlertRptID").val() === 'undefined' ? -1 : $("#sbmtdAlertRptID").val();
    var lnkArgs = 'grp=9&typ=1&pg=2&vtyp=4&sbmtdRptID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'alrtsDetailInfo', 'PasteDirect', '', '', '', function () {
        if (!$.fn.DataTable.isDataTable('#alrtSchdlPrmsTable')) {
            var table1 = $('#alrtSchdlPrmsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#alrtSchdlPrmsTable').wrap('<div class="dataTables_scroll"/>');
        }
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
        $('.form_date_tme1').datetimepicker({
            format: "yyyy-mm-dd hh:ii:ss",
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

        $('.form_date1').datetimepicker({
            format: "yyyy-mm-dd",
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
        $("#allOtherFileInput1").val('');
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
                                $('#alrtsMsgBody').summernote('createLink', {
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
        $('#alrtsMsgBody').summernote({
            minHeight: 262,
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
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['codeview']],
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
                                $('#alrtsMsgBody').summernote("insertImage", inptUrl, 'filename');
                            });
                        }
                    }
        });
        var markupStr1 = typeof $("#alrtsMsgBodyEncded").val() === 'undefined' ? '' : $("#alrtsMsgBodyEncded").val();
        $('#alrtsMsgBody').summernote('code', urldecode(markupStr1));
    });

}
function importRptParams()
{
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: 'Import Reports?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT REPORT PARAMETERS</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>',
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
                                        }
                                        ;
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
                                                if (isFileValid == true) {
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
                                        $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing Reports...Please Wait...</span>');
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var number = "";
                                            var processName = "";
                                            var paramName = "";
                                            var sqlRep = "";
                                            var dfltVal = "";
                                            var prmLovNm = "";
                                            var isValRqrd = "";
                                            var isValDsplyd = "";
                                            var prmDataTyp = "";
                                            var prmDateFrmt = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            number = data[row][item];
                                                            break;
                                                        case 2:
                                                            processName = data[row][item];
                                                            break;
                                                        case 3:
                                                            paramName = data[row][item];
                                                            break;
                                                        case 4:
                                                            sqlRep = data[row][item];
                                                            break;
                                                        case 5:
                                                            dfltVal = data[row][item];
                                                            break;
                                                        case 6:
                                                            prmLovNm = data[row][item];
                                                            break;
                                                        case 7:
                                                            isValRqrd = data[row][item];
                                                            break;
                                                        case 8:
                                                            prmDataTyp = data[row][item];
                                                            break;
                                                        case 9:
                                                            prmDateFrmt = data[row][item];
                                                            break;
                                                        case 10:
                                                            isValDsplyd = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: 'Error-Validating Selected File',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                    ;
                                                }
                                                if (rwCntr === 0) {
                                                    //alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());
                                                    if (number.toUpperCase() === "NO."
                                                            && processName.toUpperCase() === "PROCESS NAME**"
                                                            && paramName.toUpperCase() === "PARAMETER NAME/PROMPT**"
                                                            && prmDateFrmt.toUpperCase() === "DATE FORMAT"
                                                            && isValDsplyd.toUpperCase() === "IS DISPLAYED?")
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: 'Error-Import Parameters',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                if (processName.trim() !== "" && paramName.trim() !== ""
                                                        && sqlRep.trim() !== ""
                                                        && prmDataTyp.trim() !== "")
                                                {
                                                    //if valid data
                                                    /*.replace(/(~)+/g, "{-;-;}") .replace(/(\|)+/g, "{:;:;}")*/
                                                    dataToSend = dataToSend + number.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + processName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + paramName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + sqlRep.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + dfltVal.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + prmLovNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + isValRqrd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + prmDataTyp.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + prmDateFrmt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + isValDsplyd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output += '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' + vldRwCntr;
                                            output += '<br/>Total No. of Rows:' + rwCntr + '</span>';
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import Reports',
                                                size: 'small',
                                                message: 'Error:' + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                }
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
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveRptParams(dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: 'Error-Import Parameters',
                                                size: 'small',
                                                message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () {
                                                }
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

function saveRptParams(dataToSend)
{
    if (dataToSend.trim() === '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;">No Data to Send!</span></p>'
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Importing Parameters',
        size: 'small',
        message: '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Parameters...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            getAllRpts('clear', '#allmodules', 'grp=9&typ=1&pg=1&vtyp=0');
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
                    grp: 9,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 6,
                    dataToSend: dataToSend
                }
            });
            prgstimerid2 = window.setInterval(rfrshSaveRptParams, 1000);
        });
    });
}

function rfrshSaveRptParams() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 9,
            typ: 1,
            pg: 1,
            q: 'UPDATE',
            actyp: 7
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

function exprtRptParams()
{
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
            '<p style="color:#000;">' +
            'How many Parameters will you like to Export?' +
            '<br/>1=No Parameters(Empty Template)' +
            '<br/>2=All Parameters' +
            '<br/>3-Infinity=Specify the exact number of Parameters to Export<br/>' +
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
        title: 'Export Reports/Processes!',
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {
        },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton('btn_exprt_param');
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
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    ClearAllIntervals();
                }
            }, {
                id: 'btn_exprt_param',
                label: 'Export',
                icon: 'glyphicon glyphicon-menu-right',
                cssClass: 'btn-primary',
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $('#recsToExprt').val();
                    if (!isNumber(inptNum))
                    {
                        var dialog = bootbox.alert({
                            title: 'Exporting Parameters',
                            size: 'small',
                            message: 'Please provide a valid Number!',
                            callback: function () {
                            }
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 1,
                                    q: 'UPDATE',
                                    actyp: 8,
                                    inptNum: inptNum
                                }
                            });
                            prgstimerid2 = window.setInterval(rfrshExprtRptParams, 1000);
                        });
                    }
                }
            }]
    });
}

function rfrshExprtRptParams() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 9,
            typ: 1,
            pg: 1,
            q: 'UPDATE',
            actyp: 9
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf('Error') !== -1)
                {
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
                $("#msgAreaExprt").html('<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message);
                document.getElementById("msgAreaExprt").innerHTML = '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>'
                        + data.message;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function attchFileToAlrt()
{
    var crntAttchMnts = $("#alrtsMailAttchmnts").val();
    $("#allOtherFileInput2").change(function () {
        var fileName = $(this).val();
        var input = document.getElementById('allOtherFileInput2');
        sendAlrtFile(input.files[0], function () {
            var inptUrl = $("#allOtherInputData2").val();
            crntAttchMnts = crntAttchMnts + ";" + inptUrl;
            $("#alrtsMailAttchmnts").val(crntAttchMnts);
        });
    });
    performFileClick('allOtherFileInput2');
}

function sendAlrtFile(file, callBackFunc) {
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
            console.warn(jqXHR.responseText);
        }
    });
}


function getMyMdlRptRuns(actionText, loadOption, linkArgs)
{
    var srchFor = typeof $("#mdlRptRnsSrchFor").val() === 'undefined' ? '%' : $("#mdlRptRnsSrchFor").val();
    var srchIn = typeof $("#mdlRptRnsSrchIn").val() === 'undefined' ? 'Report Run ID' : $("#mdlRptRnsSrchIn").val();
    var pageNo = typeof $("#mdlRptRnsPageNo").val() === 'undefined' ? 1 : $("#mdlRptRnsPageNo").val();
    var limitSze = typeof $("#mdlRptRnsDsplySze").val() === 'undefined' ? 10 : $("#mdlRptRnsDsplySze").val();
    var sortBy = typeof $("#mdlRptRnsSortBy").val() === 'undefined' ? '' : $("#mdlRptRnsSortBy").val();
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
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;

    doAjaxWthCallBck(linkArgs, 'myFormsModalNm', loadOption, 'Report Run Details', 'myFormsModalNmTitle', 'myFormsModalNmBody', function () {
        if (!$.fn.DataTable.isDataTable('#mdlRptRnsTable')) {
            var table1 = $('#mdlRptRnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#mdlRptRnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#mdlRptRnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncMdlRptRns(e, actionText, loadOption, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyMdlRptRuns(actionText, loadOption, linkArgs);
    }
}

function getAllModuleRpts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allMdlRptsSrchFor").val() === 'undefined' ? '%' : $("#allMdlRptsSrchFor").val();
    var srchIn = typeof $("#allMdlRptsSrchIn").val() === 'undefined' ? 'Both' : $("#allMdlRptsSrchIn").val();
    var pageNo = typeof $("#allMdlRptsPageNo").val() === 'undefined' ? 1 : $("#allMdlRptsPageNo").val();
    var limitSze = typeof $("#allMdlRptsDsplySze").val() === 'undefined' ? 10 : $("#allMdlRptsDsplySze").val();
    var sortBy = typeof $("#allMdlRptsSortBy").val() === 'undefined' ? '' : $("#allMdlRptsSortBy").val();
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

function enterKeyFuncAllModuleRpts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllModuleRpts(actionText, slctr, linkArgs);
    }
}

var prgstimerid5;
var prgstimerid5Rndm;
var prgstimerid5CallBckFunc;

function getSilentRptsRnSts(rptID, alrtID, paramsStr, callBackFnc)
{
    if (typeof callBackFnc === 'undefined' || callBackFnc === null)
    {
        callBackFnc = function () {
            var wabcd = 1;
        };
    }
    prgstimerid5CallBckFunc = callBackFnc;
    var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
    prgstimerid5Rndm = nwRndm;
    var dialog = bootbox.alert({
        title: 'Run Report/Process',
        size: 'small',
        message: '<div id="myRptProgress"><div id="myRptBar"></div></div><div id="myRptInformation"><i class="fa fa-spin fa-spinner"></i> Running Report/Process...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid5);
            window.clearInterval(prgstimerid5);
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
                    grp: 9,
                    typ: 1,
                    pg: 1,
                    q: 'UPDATE',
                    actyp: 51,
                    rptID: rptID,
                    alrtID: alrtID,
                    paramsStr: paramsStr,
                    nwRndm: prgstimerid5Rndm
                }
            });
            prgstimerid5 = window.setInterval(rfrshSilentRptsPrgrs, 1500);
        });
    });
}
/*,
 success: function (data) {
 console.warn(data);
 },
 error: function (jqXHR, textStatus, errorThrown) {
 console.log(textStatus + " " + errorThrown);
 console.warn(jqXHR.responseText);
 }*/
function rfrshSilentRptsPrgrs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 9,
            typ: 1,
            pg: 1,
            q: 'UPDATE',
            actyp: 52,
            nwRndm: prgstimerid5Rndm
        },
        success: function (data) {
            if (data.percent >= 100) {
                clearInterval(prgstimerid5);
                window.clearInterval(prgstimerid5);
                ClearAllIntervals();
            }
            var elem = document.getElementById('myRptBar');
            elem.style.width = data.percent + '%';
            $("#myRptInformation").html(data.message);
            if (data.percent >= 100) {
                clearInterval(prgstimerid5);
                ClearAllIntervals();
                window.clearInterval(prgstimerid5);
                if (typeof prgstimerid5CallBckFunc === 'undefined' || prgstimerid5CallBckFunc === null)
                {
                    prgstimerid5CallBckFunc = function () {
                        var wabcd = 1;
                    };
                }
                prgstimerid5CallBckFunc();
                if (data.dwnld_url != 'None') {
                    window.clearInterval(prgstimerid5);
                    window.open(data.dwnld_url, '_blank');
                    window.clearInterval(prgstimerid5);
                }
                window.clearInterval(prgstimerid5);
                ClearAllIntervals();
            }
        }
    });
}

function savePrcsRnnrs()
{
    var errMsg = "";
    var slctdPrcsRnnrs = "";
    var isVld = true;
    $('#allPrcsRnnrsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_RnnrID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnRnnrID = typeof $('#' + rowPrfxNm + rndmNum + '_RnnrID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_RnnrID').val();
                    var lnRnnrNm = typeof $('#' + rowPrfxNm + rndmNum + '_RnnrNm').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_RnnrNm').val();
                    var lnRnnrDesc = typeof $('#' + rowPrfxNm + rndmNum + '_RnnrDesc').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_RnnrDesc').val();
                    var lnRnnrFileNm = typeof $('#' + rowPrfxNm + rndmNum + '_FileNm').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_FileNm').val();
                    var lnRnnrPrty = typeof $('#' + rowPrfxNm + rndmNum + '_Priorty').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_Priorty').val();
                    if (lnRnnrNm.trim() === "") {
                        /*Do Nothing*/
                    } else {
                        if (lnRnnrDesc.trim() === "") {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_RnnrDesc').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_RnnrDesc').removeClass('rho-error');
                            if (lnRnnrFileNm.trim() === "") {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_FileNm').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_FileNm').removeClass('rho-error');
                            }
                            if (lnRnnrPrty.trim() === "") {
                                isVld = false;
                                $('#' + rowPrfxNm + rndmNum + '_Priorty').addClass('rho-error');
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_Priorty').removeClass('rho-error');
                            }
                            if (isVld === true)
                            {
                                slctdPrcsRnnrs = slctdPrcsRnnrs + lnRnnrID + "~"
                                        + lnRnnrNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + lnRnnrDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + lnRnnrFileNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                        + lnRnnrPrty.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                            }
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Process Runners',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Process Runners...Please Wait...</p>',
        callback: function () {
            openATab('#allmodules', 'grp=9&typ=1&pg=4&vtyp=0');
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
                    grp: 9,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 1,
                    slctdPrcsRnnrs: slctdPrcsRnnrs
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }});
        });
    });
}

function delPrcsRnnrs(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var rnnrIDDesc = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RnnrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RnnrID').val();
        rnnrIDDesc = $('#' + rowPrfxNm + rndmNum + '_RnnrNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Process Runner?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Process Runner?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Process Runner?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Process Runner...Please Wait...</p>',
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 1,
                                    rnnrID: pKeyID,
                                    rnnrIDDesc: rnnrIDDesc
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

function delPrcsRun(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var runIDDesc = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RunID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RunID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Process Run?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Process Run?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Process Run?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Process Run...Please Wait...</p>',
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 1,
                                    runID: pKeyID
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

var rnnrRfrshTimer;
function startRnnrsRfrsh() {
    openATab('#allmodules', 'grp=9&typ=1&pg=4&vtyp=0&mstAutoRefresh=1');
    rnnrRfrshTimer = window.setInterval(function () {
        openATab('#allmodules', 'grp=9&typ=1&pg=4&vtyp=0&mstAutoRefresh=1');
    }, 5000);
}

function stopRnnrsRfrsh() {
    window.clearInterval(rnnrRfrshTimer);
    window.clearInterval(rnnrRfrshTimer);
    openATab('#allmodules', 'grp=9&typ=1&pg=4&vtyp=0&mstAutoRefresh=0');
    ClearAllIntervals();
}

function startProcessRunner(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var rnnrNm = "";
    var shdStart = 1;
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RnnrID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RnnrID').val();
        rnnrNm = $('#' + rowPrfxNm + rndmNum + '_RnnrNm').val();
        shdStart = $('#' + rowPrfxNm + rndmNum + '_ShdStart').val();
    }
    var msgStr = "Start Process Runner";
    var msgStr1 = "START";
    var msgStr2 = "Starting";
    if (Number(shdStart) <= 0) {
        msgStr = "Stop Process Runner";
        msgStr1 = "STOP";
        msgStr2 = "Stoping";
    }
    var dialog = bootbox.confirm({
        title: msgStr + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">' + msgStr1 + '</span> this Process Runner?<br/>Action cannot be Undone!</p>',
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
                    title: msgStr + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> ' + msgStr2 + ' Process Runner...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                        startRnnrsRfrsh();
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 4,
                                    q: 'UPDATE',
                                    actyp: 2,
                                    rnnrID: pKeyID,
                                    rnnrNm: rnnrNm,
                                    shdStart: shdStart
                                },
                                success: function (result) {
                                    //alert(result);
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result.message);
                                    }, 5000);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    /*dialog1.find('.bootbox-body').html(errorThrown);*/
                                    console.warn(jqXHR1.responseText);
                                }
                            });
                        });
                    } else
                    {
                        setTimeout(function () {
                            dialog1.find('.bootbox-body').html('No Runner Selected!');
                        }, 500);
                    }
                });
            }
        }
    });
}

function quickStartMainRunner()
{
    var pKeyID = -1;
    var rnnrNm = "GET_MAIN_RUNNER";
    var shdStart = 1;
    $body = $("body");
    $body.removeClass("mdlloading");
    console.log('About Starting Main Runner');
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 9,
            typ: 1,
            pg: 4,
            q: 'UPDATE',
            actyp: 2,
            rnnrID: pKeyID,
            rnnrNm: rnnrNm,
            shdStart: shdStart
        },
        success: function (result) {
            //alert(result);
            setTimeout(function () {
                var msg= $("<div>").html(result.message).text();
                console.log(msg);
            }, 5000);
        },
        error: function (jqXHR1, textStatus1, errorThrown1)
        {
            /*dialog1.find('.bootbox-body').html(errorThrown);*/
            console.warn(jqXHR1.responseText);
        }
    });
}

function saveOneSchdlsForm()
{
    var errMsg = "";
    var slctdSchdlParams = "";
    var isVld = true;
    var allSchdlsSchdlID = typeof $("#allSchdlsSchdlID").val() === 'undefined' ? '-1' : $("#allSchdlsSchdlID").val();
    var allSchdlsRptID = typeof $("#allSchdlsRptID").val() === 'undefined' ? '-1' : $("#allSchdlsRptID").val();
    var allSchdlsIntrvl = typeof $("#allSchdlsIntrvl").val() === 'undefined' ? '-5' : $("#allSchdlsIntrvl").val();
    var allSchdlsStrtDte = typeof $("#allSchdlsStrtDte").val() === 'undefined' ? '' : $("#allSchdlsStrtDte").val();
    var allSchdlsIntvlUom = typeof $("#allSchdlsIntvlUom").val() === 'undefined' ? '' : $("#allSchdlsIntvlUom").val();
    var allSchdlsRnAtHr = typeof $("input[name='allSchdlsRnAtHr']:checked").val() === 'undefined' ? 'NO' : $("input[name='allSchdlsRnAtHr']:checked").val();
    var errMsg = "";
    if (Number(allSchdlsRptID) <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Report cannot be empty!</span></p>';
    }
    if (allSchdlsStrtDte.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Schedule Start Date cannot be empty!</span></p>';
    }
    if (allSchdlsIntvlUom.trim() === "")
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Interval UOM cannot be empty!</span></p>';
    }
    $('#allSchdlPrmsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_ParamID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var lnParamID = typeof $('#' + rowPrfxNm + rndmNum + '_ParamID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_ParamID').val();
                    var lnParamVal = typeof $('#' + rowPrfxNm + rndmNum + '_ParamVal').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_ParamVal').val();
                    var lnShcdlPrmID = typeof $('#' + rowPrfxNm + rndmNum + '_SchdlParamID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_SchdlParamID').val();
                    if (Number(lnParamID) <= 0) {
                        /*Do Nothing*/
                    } else {
                        if (isVld === true)
                        {
                            slctdSchdlParams = slctdSchdlParams + lnShcdlPrmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnParamID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                    + lnParamVal.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Report Schedules',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving  Report Schedules...Please Wait...</p>',
        callback: function () {
            getAllSchdls('clear', '#allmodules', 'grp=9&typ=1&pg=3&vtyp=0');
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
                    grp: 9,
                    typ: 1,
                    pg: 3,
                    q: 'UPDATE',
                    actyp: 1,
                    allSchdlsSchdlID: allSchdlsSchdlID,
                    allSchdlsRptID: allSchdlsRptID,
                    allSchdlsIntrvl: allSchdlsIntrvl,
                    allSchdlsStrtDte: allSchdlsStrtDte,
                    allSchdlsIntvlUom: allSchdlsIntvlUom,
                    allSchdlsRnAtHr: allSchdlsRnAtHr,
                    slctdSchdlParams: slctdSchdlParams
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }});
        });
    });
}

function delOneSchdl(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var rptDesc = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SchdlID').val() === 'undefined')
    {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SchdlID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        rptDesc = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Report Schedule?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Report Schedule?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Report Schedule?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Report Schedule...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right", "0px");
                        getAllSchdls('clear', '#allmodules', 'grp=9&typ=1&pg=3&vtyp=0');
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
                                    grp: 9,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 1,
                                    schdlID: pKeyID,
                                    rptDesc: rptDesc
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