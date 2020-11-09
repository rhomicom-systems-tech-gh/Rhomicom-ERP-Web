function prepareEvote(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $(function () {
            $('[data-toggle="tabajxsrvy"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=19&typ=10' + dttrgt;
                return openATab(targ, linkArgs);
            });
        });
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1)
        {
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
            var table1 = $('#allSrvysTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allSrvysTable').wrap('<div class="dataTables_scroll"/>');
            $('#allSrvysForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allSrvysTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#allSrvysRow' + rndmNum + '_SrvyID').val() === 'undefined' ? '%' : $('#allSrvysRow' + rndmNum + '_SrvyID').val();
                getOneAllSrvyForm(pkeyID, 1);
            });
            $('#allSrvysTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
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
                                    if (inptText === "")
                                    {
                                        inptText = "Read More...";
                                    }
                                    if (inptNwWndw === "")
                                    {
                                        inptNwWndw = true;
                                    }
                                    $('#srvyInstructions').summernote('createLink', {
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
            $('#srvyInstructions').summernote({
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
                                    $('#srvyInstructions').summernote("insertImage", inptUrl, 'filename');
                                });
                            }
                        }
            });
            var markupStr1 = typeof $("#allSrvysInstructns").val() === 'undefined' ? '' : $("#allSrvysInstructns").val();
            $('#srvyInstructions').summernote('code', urldecode(markupStr1));
        } else if (lnkArgs.indexOf("&pg=2&vtyp=1") !== -1)
        {
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
        } else if (lnkArgs.indexOf("&pg=2&vtyp=2") !== -1)
        {
            $('#allSrvysForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#srvyQstnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#srvyQstnsTable').wrap('<div class="dataTables_scroll"/>');
        } else if (lnkArgs.indexOf("&pg=2&vtyp=4") !== -1)
        {
            var table1 = $('#srvyQstnAnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#srvyQstnAnsTable').wrap('<div class="dataTables_scroll"/>');
            $('#srvyQstnAnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1)
        {
            var table1 = $('#allQstnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allQstnsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allQstnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#srvyQstnsAnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#srvyQstnsAnsTable').wrap('<div class="dataTables_scroll"/>');
            $('#allQstnsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#allQstnsRow' + rndmNum + '_QstnID').val() === 'undefined' ? '%' : $('#allQstnsRow' + rndmNum + '_QstnID').val();
                getOneQstnAnsForm(pkeyID, 1);
            });
            $('#allQstnsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1)
        {
            var table1 = $('#srvyResultsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#srvyResultsTable').wrap('<div class="dataTables_scroll"/>');
        }
        htBody.removeClass("mdlloading");
    });
}
function getAllSrvys(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allSrvysSrchFor").val() === 'undefined' ? '%' : $("#allSrvysSrchFor").val();
    var srchIn = typeof $("#allSrvysSrchIn").val() === 'undefined' ? 'Both' : $("#allSrvysSrchIn").val();
    var pageNo = typeof $("#allSrvysPageNo").val() === 'undefined' ? 1 : $("#allSrvysPageNo").val();
    var limitSze = typeof $("#allSrvysDsplySze").val() === 'undefined' ? 10 : $("#allSrvysDsplySze").val();
    var sortBy = typeof $("#allSrvysSortBy").val() === 'undefined' ? '' : $("#allSrvysSortBy").val();
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

function enterKeyFuncAllSrvys(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSrvys(actionText, slctr, linkArgs);
    }
}

function getOneAllSrvyForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=19&typ=10&pg=2s&vtyp=' + vwtype + '&sbmtdSrvyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'allSrvysDetailInfo', 'PasteDirect', '', '', '', function () {
        $(function () {
            $('[data-toggle="tabajxsrvy"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=19&typ=10' + dttrgt;
                return openATab(targ, linkArgs);
            });
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
                                $('#srvyInstructions').summernote('createLink', {
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
        $('#srvyInstructions').summernote({
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
                                $('#srvyInstructions').summernote("insertImage", inptUrl, 'filename');
                            });
                        }
                    }
        });
        var markupStr1 = typeof $("#allSrvysInstructns").val() === 'undefined' ? '' : $("#allSrvysInstructns").val();
        $('#srvyInstructions').summernote('code', urldecode(markupStr1));
    });

}

function getSrvyQstns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#srvyQstnsSrchFor").val() === 'undefined' ? '%' : $("#srvyQstnsSrchFor").val();
    var srchIn = typeof $("#srvyQstnsSrchIn").val() === 'undefined' ? 'Both' : $("#srvyQstnsSrchIn").val();
    var pageNo = typeof $("#srvyQstnsPageNo").val() === 'undefined' ? 1 : $("#srvyQstnsPageNo").val();
    var limitSze = typeof $("#srvyQstnsDsplySze").val() === 'undefined' ? 10 : $("#srvyQstnsDsplySze").val();
    var sortBy = typeof $("#srvyQstnsSortBy").val() === 'undefined' ? '' : $("#srvyQstnsSortBy").val();
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

function enterKeyFuncSrvyQstns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getSrvyQstns(actionText, slctr, linkArgs);
    }
}

function getOneSrvyQstnsForm(pKeyID, pKeyID1, vwtype, dialogTitle)
{
    var lnkArgs = 'grp=19&typ=10&pg=2&vtyp=' + vwtype + '&sbmtdSrvyID=' + pKeyID + '&sbmtdQstnID=' + pKeyID1;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', 'ShowDialog', dialogTitle, 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        var table1 = $('#srvyQstnAnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#srvyQstnAnsTable').wrap('<div class="dataTables_scroll"/>');
        $('#srvyQstnAnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $("#myFormsModalNrml").on("hidden.bs.modal", function () {
            getSrvyQstns('clear', '#srvysQstnsPage', 'grp=19&typ=10&pg=2&vtyp=2&sbmtdSrvyID=' + pKeyID);
        });
    });
}


function getSrvyQstnAns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#srvyQstnAnsSrchFor").val() === 'undefined' ? '%' : $("#srvyQstnAnsSrchFor").val();
    var srchIn = typeof $("#srvyQstnAnsSrchIn").val() === 'undefined' ? 'Both' : $("#srvyQstnAnsSrchIn").val();
    var pageNo = typeof $("#srvyQstnAnsPageNo").val() === 'undefined' ? 1 : $("#srvyQstnAnsPageNo").val();
    var limitSze = typeof $("#srvyQstnAnsDsplySze").val() === 'undefined' ? 10 : $("#srvyQstnAnsDsplySze").val();
    var sortBy = typeof $("#srvyQstnAnsSortBy").val() === 'undefined' ? '' : $("#srvyQstnAnsSortBy").val();
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

function enterKeyFuncSrvyQstnAns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getSrvyQstnAns(actionText, slctr, linkArgs);
    }
}
function getOneSrvyRspnsForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=19&typ=10&pg=2&vtyp=' + vwtype + '&sbmtdSrvyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', 'ShowDialog', 'Survey ID:' + pKeyID, 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        var table1 = $('#srvyAnsChsnTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#srvyAnsChsnTable').wrap('<div class="dataTables_scroll"/>');
        $('#srvyAnsChsnForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}
function getOneSrvyResults(pKeyID, vwtype)
{
    var lnkArgs = 'grp=19&typ=10&pg=2&vtyp=' + vwtype + '&sbmtdSrvyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', 'ShowDialog', 'Survey ID:' + pKeyID, 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        var table1 = $('#srvyResultsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#srvyResultsTable').wrap('<div class="dataTables_scroll"/>');
    });
}
function getSrvyAnsChsn(actionText, pKeyID, linkArgs)
{
    var srchFor = typeof $("#srvyAnsChsnSrchFor").val() === 'undefined' ? '%' : $("#srvyAnsChsnSrchFor").val();
    var srchIn = typeof $("#srvyAnsChsnSrchIn").val() === 'undefined' ? 'Both' : $("#srvyAnsChsnSrchIn").val();
    var pageNo = typeof $("#srvyAnsChsnPageNo").val() === 'undefined' ? 1 : $("#srvyAnsChsnPageNo").val();
    var limitSze = typeof $("#srvyAnsChsnDsplySze").val() === 'undefined' ? 10 : $("#srvyAnsChsnDsplySze").val();
    var sortBy = typeof $("#srvyAnsChsnSortBy").val() === 'undefined' ? '' : $("#srvyAnsChsnSortBy").val();
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
    doAjaxWthCallBck(linkArgs, 'myFormsModalNrml', 'ShowDialog', 'Survey ID:' + pKeyID, 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        var table1 = $('#srvyAnsChsnTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#srvyAnsChsnTable').wrap('<div class="dataTables_scroll"/>');
        $('#srvyAnsChsnForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncSrvyAnsChsn(e, actionText, pKeyID, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getSrvyAnsChsn(actionText, pKeyID, linkArgs);
    }
}

function getAllQstns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allQstnsSrchFor").val() === 'undefined' ? '%' : $("#allQstnsSrchFor").val();
    var srchIn = typeof $("#allQstnsSrchIn").val() === 'undefined' ? 'Both' : $("#allQstnsSrchIn").val();
    var pageNo = typeof $("#allQstnsPageNo").val() === 'undefined' ? 1 : $("#allQstnsPageNo").val();
    var limitSze = typeof $("#allQstnsDsplySze").val() === 'undefined' ? 10 : $("#allQstnsDsplySze").val();
    var sortBy = typeof $("#allQstnsSortBy").val() === 'undefined' ? '' : $("#allQstnsSortBy").val();
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

function enterKeyFuncAllQstns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllQstns(actionText, slctr, linkArgs);
    }
}

function getOneQstnAnsForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=19&typ=10&pg=3&vtyp=' + vwtype + '&sbmtdQstnID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'allQstnsDetailInfo', 'PasteDirect', '', '', '', function () {
        var table2 = $('#srvyQstnsAnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#srvyQstnsAnsTable').wrap('<div class="dataTables_scroll"/>');
    });

}
function getOneQstnForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=19&typ=10&pg=3&vtyp=' + vwtype + '&sbmtdQstnID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', 'ShowDialog', 'Question Form (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#qstnsForm').submit(function (e) {
            e.preventDefault();
            return false;
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
                                $('#qstnHint').summernote('createLink', {
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
        $('#qstnHint').summernote({
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
                                $('#qstnHint').summernote("insertImage", inptUrl, 'filename');
                            });
                        }
                    }
        });
        var markupStr1 = typeof $("#allQstnHint").val() === 'undefined' ? '' : $("#allQstnHint").val();
        $('#qstnHint').summernote('code', urldecode(markupStr1));

        $('#myFormsModalLg').off('hidden.bs.modal');
        $("#myFormsModalLg").on("hidden.bs.modal", function () {
            getAllQstns('clear', '#allmodules', 'grp=19&typ=10&pg=3&vtyp=0');
        });
    });
}

function getOneAnsForm(pKeyID, qstnID, vwtype)
{
    var lnkArgs = 'grp=19&typ=10&pg=3&vtyp=' + vwtype + '&sbmtdAnsID=' + pKeyID + '&sbmtdQstnID=' + qstnID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', 'ShowDialog', 'Answer Form (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#answrForm').submit(function (e) {
            e.preventDefault();
            return false;
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
                                $('#answrHint').summernote('createLink', {
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
        $('#answrHint').summernote({
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
                                $('#answrHint').summernote("insertImage", inptUrl, 'filename');
                            });
                        }
                    }
        });
        var markupStr1 = typeof $("#allAnswrHint").val() === 'undefined' ? '' : $("#allAnswrHint").val();
        $('#answrHint').summernote('code', urldecode(markupStr1));

        $('#myFormsModalLg').off('hidden.bs.modal');
        $("#myFormsModalLg").on("hidden.bs.modal", function () {
            getOneQstnAnsForm(qstnID, 1);
        });
    });
}

function validateQstnAns(elementID, pgNo, srvyNm, pkID, offset, action)
{
    var rspnsTx = '';
    var slctdAnswrIDs = '';
    var slctdAnswrTxts = '';
    var questionID = -1;
    var x = document.getElementsByName("answer");
    var i;
    for (i = 0; i < x.length; i++) {
        if (x[i].type == "checkbox" || x[i].type == "radio") {
            if (x[i].checked == true)
            {
                slctdAnswrIDs = slctdAnswrIDs + x[i].value + "|";
                slctdAnswrTxts = slctdAnswrTxts + x[i].nextSibling.data + "|";
            }
        } else
        {
            slctdAnswrIDs = slctdAnswrIDs + '-1' + "|";
            slctdAnswrTxts = slctdAnswrTxts + x[i].value + "|";
        }
    }

    x = document.getElementsByName("questionID");
    for (i = 0; i < x.length; i++) {
        questionID = x[i].value;
    }

    if (questionID > 0 && slctdAnswrIDs == '' && action == 'NEXT')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:normal;">Please provide an Answer/Option First!</span></p>'});
        return;
    }
    var shdSubmit = 0;
    x = document.getElementsByName("shdSubmit");
    for (i = 0; i < x.length; i++) {
        shdSubmit = x[i].value;
    }
    $body = $("body");
    if (action != 'FINISH')
    {
        shdSubmit = 0;
        getMsgAsync('grp=1&typ=11&q=Check Session', function () {
            $body.addClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 19,
                    typ: 10,
                    vwtyp: 0,
                    sbmtdSrvyID: pkID,
                    pkeyID: pkID,
                    actionNm: action,
                    nxQstnIdx: offset,
                    questionID: questionID,
                    answer: slctdAnswrIDs,
                    answerTxt: slctdAnswrTxts,
                    shdSubmit: shdSubmit,
                    pg: pgNo
                },
                success: function (result) {
                    if (elementID === '') {
                        $body.removeClass("mdlloading");
                        $('#myFormsModalNrmlTitle').html(srvyNm);
                        $('#myFormsModalNrmlBody').html(result);
                        $('#myFormsModalNrml').on('show.bs.modal', function (e) {
                            $(this).find('.modal-body').css({
                                'max-height': '100%'
                            });
                        });
                        $('#myFormsModalNrml').modal({backdrop: 'static', keyboard: false});
                    } else {
                        $(elementID).html(result);
                        $body.removeClass("mdlloading");
                        var table1 = $('#srvyResultsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#srvyResultsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $body.removeClass("mdlloading");
                }});
        });
    } else {
        bootbox.confirm({
            title: "SUBMIT?",
            message: 'Are you sure you want to <strong><em>SUBMIT and FINALIZE</em></strong> your Selected Options?<br/>This action CANNOT be UNDONE!',
            size: 'small',
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm'
                }
            },
            callback: function (result) {
                console.log('This was logged in the callback: ' + result);
                if (result === true)
                {
                    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
                        $body.addClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 19,
                                typ: 10,
                                vwtyp: 0,
                                sbmtdSrvyID: pkID,
                                pkeyID: pkID,
                                nxQstnIdx: offset,
                                questionID: questionID,
                                answer: slctdAnswrIDs,
                                answerTxt: slctdAnswrTxts,
                                shdSubmit: shdSubmit,
                                pg: pgNo},
                            success: function (result) {
                                $(elementID).html(result);
                                $body.removeClass("mdlloading");
                                var table1 = $('#srvyResultsTable').DataTable({
                                    "paging": false,
                                    "ordering": false,
                                    "info": false,
                                    "bFilter": false,
                                    "scrollX": false
                                });
                                $('#srvyResultsTable').wrap('<div class="dataTables_scroll"/>');
                            },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                                $body.removeClass("mdlloading");
                            }});
                    });
                }
            }
        });
    }
}

function saveSrvyForm()
{
    var srvysSurveyID = typeof $("#srvysSurveyID").val() === 'undefined' ? -1 : $("#srvysSurveyID").val();
    var srvysSurveyName = typeof $("#srvysSurveyName").val() === 'undefined' ? '' : $("#srvysSurveyName").val();
    var srvysSurveyDesc = typeof $("#srvysSurveyDesc").val() === 'undefined' ? '' : $("#srvysSurveyDesc").val();
    var srvysSurveyType = typeof $("#srvysSurveyType").val() === 'undefined' ? '' : $("#srvysSurveyType").val();
    var srvysStartDate = typeof $("#srvysStartDate").val() === 'undefined' ? '' : $("#srvysStartDate").val();
    var srvysEndDate = typeof $("#srvysEndDate").val() === 'undefined' ? '' : $("#srvysEndDate").val();
    var srvysIncPrsnSetID = typeof $("#srvysIncPrsnSetID").val() === 'undefined' ? -1 : $("#srvysIncPrsnSetID").val();
    var srvysExcPrsnSetID = typeof $("#srvysExcPrsnSetID").val() === 'undefined' ? -1 : $("#srvysExcPrsnSetID").val();
    var srvyInstructions = typeof $("#srvyInstructions") === 'undefined' ? '' : ($('#srvyInstructions').summernote('code'));

    var errMsg = "";
    if (srvysSurveyName.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Survey Name cannot be empty!</span></p>';
    }
    if (srvysSurveyType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Survey Type cannot be empty!</span></p>';
    }
    var strippedInstrctns = srvyInstructions.replace(/<\/p>/gi, " ")
            .replace(/<br\/?>/gi, " ")
            .replace(/<\/?[^>]+(>|$)/g, "");
    if (strippedInstrctns.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Survey Instructions cannot be empty!</span></p>';
    }
    if (srvysIncPrsnSetID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Eligible Person Set cannot be empty!</span></p>';
    }
    if (srvysExcPrsnSetID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Exclusion Person Set cannot be empty!</span></p>';
    }
    if (srvysStartDate.trim().length === 19)
    {
        srvysStartDate = '0' + srvysStartDate;
        $("#srvysStartDate").val(srvysStartDate);
    }
    if (srvysEndDate.trim().length === 19)
    {
        srvysEndDate = '0' + srvysEndDate;
        $("#srvysEndDate").val(srvysEndDate);
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
        title: 'Save Survey/Election',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Survey/Election...Please Wait...</p>',
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
                    grp: 19,
                    typ: 10,
                    pg: 2,
                    q: 'UPDATE',
                    actyp: 1,
                    surveyID: srvysSurveyID,
                    surveyName: srvysSurveyName,
                    surveyDesc: srvysSurveyDesc,
                    surveyType: srvysSurveyType,
                    sStartDate: srvysStartDate,
                    sEndDate: srvysEndDate,
                    includePrsnSetID: srvysIncPrsnSetID,
                    excludePrsnSetID: srvysExcPrsnSetID,
                    surveyInstructions: srvyInstructions
                },
                success: function (result) {
                    //alert("TEST:"+result);
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                        if (srvysSurveyID <= 0) {
                            var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
                            var nwInptHtml = '<tr id="allSrvysRow_' + nwRndm + '" class="hand_cursor">' +
                                    '<td class="lovtd">New</td>' +
                                    '<td class="lovtd">' + srvysSurveyName +
                                    '<input type="hidden" class="form-control" aria-label="..." id="allSrvysRow' + nwRndm + '_SrvyID" value="' + result.srvyID + '">' +
                                    '<input type="hidden" class="form-control" aria-label="..." id="allSrvysRow' + nwRndm + '_SrvyNm" value="' + srvysSurveyName + '">' +
                                    '</td>' +
                                    '<td class="lovtd">' +
                                    '<button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delSrvy(\'allSrvysRow_' + nwRndm + '\');" data-toggle="tooltip" data-placement="bottom" title="Delete Survey">' +
                                    '<img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">' +
                                    '</button>' +
                                    '</td>' +
                                    '</tr>';
                            if ($('#allSrvysTable > tbody > tr').length <= 0)
                            {
                                $('#allSrvysTable').append(nwInptHtml);
                            } else {
                                $('#allSrvysTable > tbody > tr').eq(0).before(nwInptHtml);
                            }
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    //alert('Eror'+jqXHR.responseText);
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }});
        });
    });
}

function delSrvy(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var srvyNm = "";
    if (typeof $('#allSrvysRow' + rndmNum + '_SrvyID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#allSrvysRow' + rndmNum + '_SrvyID').val();
        srvyNm = $('#allSrvysRow' + rndmNum + '_SrvyNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Survey/Election?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Survey/Election?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Survey/Election?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Survey/Election...Please Wait...</p>',
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
                                    grp: 19,
                                    typ: 10,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 1,
                                    surveyID: pKeyID,
                                    surveyNm: srvyNm
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

function saveSrvyQstnAnsForm(actionText, slctr, linkArgs)
{
    var srvysSrvyQstnID = typeof $("#srvysSrvyQstnID").val() === 'undefined' ? -1 : $("#srvysSrvyQstnID").val();
    var srvysQstnID = typeof $("#srvysQstnID").val() === 'undefined' ? -1 : $("#srvysQstnID").val();
    var srvyID = typeof $("#srvyID").val() === 'undefined' ? '-1' : $("#srvyID").val();
    var srvysQstnOrder = typeof $("#srvysQstnOrder").val() === 'undefined' ? 1 : $("#srvysQstnOrder").val();
    var srvysQstnDsplyCndtn = typeof $("#srvysQstnDsplyCndtn").val() === 'undefined' ? '' : $("#srvysQstnDsplyCndtn").val();
    var srvysInvldChoicesQry = typeof $("#srvysInvldChoicesQry").val() === 'undefined' ? '' : $("#srvysInvldChoicesQry").val();
    var srvysQstnInvldDsplyMsg = typeof $("#srvysQstnInvldDsplyMsg").val() === 'undefined' ? '' : $("#srvysQstnInvldDsplyMsg").val();
    var isNew = typeof $("#isNew123").val() === 'undefined' ? 0 : $("#isNew123").val();

    var errMsg = "";
    if (srvysQstnDsplyCndtn.trim() === '')
    {
        srvysQstnDsplyCndtn = 'select 1';
        $("#srvysQstnDsplyCndtn").val(srvysQstnDsplyCndtn);
    }
    if (srvysQstnID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Question cannot be empty!</span></p>';
    }
    if (srvysQstnOrder.trim() === '')
    {
        srvysQstnOrder = 1;
        $("#srvysQstnOrder").val(srvysQstnOrder);
    }
    if (rhotrim(errMsg, '; ') !== '')
    {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg});
        return false;
    }
    var slctdSrvyQstnAns = "";
    $('#srvyQstnAnsTable').find('tr').each(function (i, el) {
        if (i > 0)
        {
            if (typeof $(el).attr('id') === 'undefined')
            {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#srvyQstnAnsRow' + rndmNum + '_SrvyQstnAnsID').val() === 'undefined')
                {
                    /*Do Nothing*/
                } else {
                    var isCrct = typeof $("input[name='srvyQstnAnsRow" + rndmNum + "_IsCrct']:checked").val() === 'undefined' ? 'NO' : 'YES';
                    var ansOrdrNo = $('#srvyQstnAnsRow' + rndmNum + '_OrderNo').val();
                    if (ansOrdrNo.trim() === '')
                    {
                        $('#srvyQstnAnsRow' + rndmNum + '_OrderNo').val(1);
                    }
                    var dsplCdtn = $('#srvyQstnAnsRow' + rndmNum + '_DsplyCndtn').val();
                    if (dsplCdtn.trim() === '')
                    {
                        $('#srvyQstnAnsRow' + rndmNum + '_DsplyCndtn').val('select 1');
                    }
                    slctdSrvyQstnAns = slctdSrvyQstnAns + $('#srvyQstnAnsRow' + rndmNum + '_SrvyQstnAnsID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#srvyQstnAnsRow' + rndmNum + '_PssblAnsID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#srvyQstnAnsRow' + rndmNum + '_OrderNo').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + $('#srvyQstnAnsRow' + rndmNum + '_DsplyCndtn').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                            + isCrct.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Survey Question',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Survey Question...Please Wait...</p>',
        callback: function () {
            if (srvysSrvyQstnID > 0 && isNew <= 0) {
                getSrvyQstnAns(actionText, slctr, linkArgs);
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
                    grp: 19,
                    typ: 10,
                    pg: 2,
                    q: 'UPDATE',
                    actyp: 2,
                    surveyID: srvyID,
                    surveyQstnID: srvysSrvyQstnID,
                    sQuestionID: srvysQstnID,
                    questionOrderNo: srvysQstnOrder,
                    questionDsplySQL: srvysQstnDsplyCndtn,
                    srvysInvldChoicesQry: srvysInvldChoicesQry,
                    srvysQstnInvldDsplyMsg: srvysQstnInvldDsplyMsg,
                    slctdSrvyQstnAns: slctdSrvyQstnAns
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                        $("#srvysSrvyQstnID").val(result.srvyQstnID);
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

function delSrvyQstn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var qstnNm = "";
    if (typeof $('#srvyQstnsRow' + rndmNum + '_SrvyQstnID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#srvyQstnsRow' + rndmNum + '_SrvyQstnID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        qstnNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Survey/Election Question?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Survey/Election Question?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Survey/Election Question?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Survey/Election Question...Please Wait...</p>',
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
                                    grp: 19,
                                    typ: 10,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    qstnNm: qstnNm
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

function delSrvyQstnAns(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var ansNm = "";
    if (typeof $('#srvyQstnAnsRow' + rndmNum + '_SrvyQstnAnsID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#srvyQstnAnsRow' + rndmNum + '_SrvyQstnAnsID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        ansNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Possible Answer?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Possible Answer?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Survey/Election?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Survey/Election...Please Wait...</p>',
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
                                    grp: 19,
                                    typ: 10,
                                    pg: 2,
                                    q: 'DELETE',
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    ansNm: ansNm
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

function saveQstnForm()
{
    var qstnID = typeof $("#qstnID").val() === 'undefined' ? -1 : $("#qstnID").val();
    var qstnDesc = typeof $("#qstnDesc").val() === 'undefined' ? '' : $("#qstnDesc").val();
    var qstnAnsType = typeof $("#qstnAnsType").val() === 'undefined' ? '' : $("#qstnAnsType").val();
    var qstnCtgry = typeof $("#qstnCtgry").val() === 'undefined' ? '' : $("#qstnCtgry").val();
    var qstnAllwdAns = typeof $("#qstnAllwdAns").val() === 'undefined' ? 1 : $("#qstnAllwdAns").val();
    var qstnImgLoc = typeof $("#qstnImgLoc").val() === 'undefined' ? '' : $("#qstnImgLoc").val();
    var qstnHint = typeof $("#qstnHint") === 'undefined' ? '' : ($('#qstnHint').summernote('code'));

    var errMsg = "";
    if (qstnDesc.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Question cannot be empty!</span></p>';
    }
    if (qstnAnsType.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Answer Type cannot be empty!</span></p>';
    }
    var strippedQstnHint = qstnHint.replace(/<\/p>/gi, " ")
            .replace(/<br\/?>/gi, " ")
            .replace(/<\/?[^>]+(>|$)/g, "");
    if (strippedQstnHint.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Question Hint cannot be empty!</span></p>';
    }
    if (qstnAllwdAns.trim() === '')
    {
        qstnAllwdAns = 1;
        $("#qstnAllwdAns").val(qstnAllwdAns);
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
        title: 'Save Question',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Question...Please Wait...</p>',
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
                    grp: 19,
                    typ: 10,
                    pg: 3,
                    q: 'UPDATE',
                    actyp: 1,
                    qstnID: qstnID,
                    qstnDesc: qstnDesc,
                    qstnAnsType: qstnAnsType,
                    qstnCtgry: qstnCtgry,
                    qstnAllwdAns: qstnAllwdAns,
                    qstnImgLoc: qstnImgLoc,
                    qstnHint: qstnHint
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                        $("#qstnID").val(result.qstnID);
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

function delQstn(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var qstnNm = "";
    if (typeof $('#allQstnsRow' + rndmNum + '_QstnID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#allQstnsRow' + rndmNum + '_QstnID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        qstnNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Question?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Question?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Question?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Question...Please Wait...</p>',
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
                                    grp: 19,
                                    typ: 10,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    qstnNm: qstnNm
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

function saveAnsForm()
{
    var ansQstnID = typeof $("#ansQstnID").val() === 'undefined' ? -1 : $("#ansQstnID").val();
    var ansPssblID = typeof $("#ansPssblID").val() === 'undefined' ? -1 : $("#ansPssblID").val();
    var answrDesc = typeof $("#answrDesc").val() === 'undefined' ? '' : $("#answrDesc").val();
    var ansSortNo = typeof $("#ansSortNo").val() === 'undefined' ? '' : $("#ansSortNo").val();
    var ans_IsCrct = typeof $("input[name='ans_IsCrct']:checked").val() === 'undefined' ? 'NO' : $("input[name='ans_IsCrct']:checked").val();
    var ans_IsEnbld = typeof $("input[name='ans_IsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='ans_IsEnbld']:checked").val();
    var ansImgLoc = typeof $("#ansImgLoc").val() === 'undefined' ? '' : $("#ansImgLoc").val();
    var ansHintTxt = typeof $("#ansHintTxt").val() === 'undefined' ? '' : $("#ansHintTxt").val();
    var answrHint = typeof $("#answrHint") === 'undefined' ? '' : ($('#answrHint').summernote('code'));

    var errMsg = "";
    if (answrDesc.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Possible Answer cannot be empty!</span></p>';
    }
    if (ansQstnID <= 0)
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Question cannot be empty!</span></p>';
    }
    if (ansSortNo.trim() === '')
    {
        ansSortNo = 1;
        $("#qstnAllwdAns").val(ansSortNo);
    }
    var strippedAnswrHint = answrHint.replace(/<\/p>/gi, " ")
            .replace(/<br\/?>/gi, " ")
            .replace(/<\/?[^>]+(>|$)/g, "");
    if (strippedAnswrHint.trim() === '')
    {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Answer Hint cannot be empty!</span></p>';
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
        title: 'Save Possible Answer',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Possible Answer...Please Wait...</p>',
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
                    grp: 19,
                    typ: 10,
                    pg: 3,
                    q: 'UPDATE',
                    actyp: 2,
                    ansQstnID: ansQstnID,
                    ansPssblID: ansPssblID,
                    answrDesc: answrDesc,
                    ansSortNo: ansSortNo,
                    ans_IsCrct: ans_IsCrct,
                    ans_IsEnbld: ans_IsEnbld,
                    ansImgLoc: ansImgLoc,
                    ansHintTxt: ansHintTxt,
                    answrHint: answrHint
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(result.message);
                        $("#ansPssblID").val(result.ansPssblID);
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

function delAns(rowIDAttrb)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var ansNm = "";
    if (typeof $('#srvyQstnsAnsRow' + rndmNum + '_PsblAnsID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#srvyQstnsAnsRow' + rndmNum + '_PsblAnsID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        ansNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Question?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Question?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Question?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Question...Please Wait...</p>',
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
                                    grp: 19,
                                    typ: 10,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    ansNm: ansNm
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

function reopenSurvey(rowIDAttrb, action)
{
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var pKeyID1 = -1;
    var votrNm = "";
    if (typeof $('#srvyAnsChsnRow' + rndmNum + '_PrsnAnsID').val() === 'undefined')
    {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#srvyAnsChsnRow' + rndmNum + '_PrsnAnsID').val();
        pKeyID1 = $('#srvyAnsChsnRow' + rndmNum + '_SrvyID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        votrNm = $.trim($tds.eq(1).text());
    }
    var msgTxt = action + " Vote";
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
            if (result === true)
            {
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
                                    grp: 19,
                                    typ: 10,
                                    pg: 2,
                                    q: 'UPDATE',
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    nwStatus: action.toUpperCase(),
                                    votrNm: votrNm
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            getSrvyAnsChsn('', pKeyID1, 'grp=19&typ=10&pg=2&vtyp=7&sbmtdSrvyID=' + pKeyID1);
                                            dialog1.modal('hide');
                                        }
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1)
                                {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                    dialog1.modal('hide');
                                }
                            });
                        });
                    } else
                    {
                        setTimeout(function () {
                            getSrvyAnsChsn('', pKeyID1, 'grp=19&typ=10&pg=2&vtyp=7&sbmtdSrvyID=' + pKeyID1);
                            dialog1.modal('hide');
                        }, 500);
                    }
                });
            }
        }
    });
}