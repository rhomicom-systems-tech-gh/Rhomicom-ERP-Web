function prepareAttn(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1)
        {
            var table1 = $('#myEvntsAtndTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myEvntsAtndTable').wrap('<div class="dataTables_scroll"/>');
            $('#myEvntsAtndForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else {
            loadScript("app/attn/attn_admin.js?v=" + jsFilesVrsn, function () {
                if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#attnRegisterTable')) {
                        table1 = $('#attnRegisterTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#attnRegisterTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    if (!$.fn.DataTable.isDataTable('#oneAttnRegisterSmryLinesTable')) {
                        var table2 = $('#oneAttnRegisterSmryLinesTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#oneAttnRegisterSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    if (!$.fn.DataTable.isDataTable('#oneAttnRgstrHeadCntTable')) {
                        var table2 = $('#oneAttnRgstrHeadCntTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#oneAttnRgstrHeadCntTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    if (!$.fn.DataTable.isDataTable('#oneAttnRgstrHeadCntTable')) {
                        var table2 = $('#oneAttnRgstrHeadCntTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#oneAttnRgstrHeadCntTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnRegisterForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                    $('#attnRegisterTable tbody').off('click');
                    $('#attnRegisterTable tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected');
                        } else {
                            table1.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');
                        }
                        var rndmNum = $(this).attr('id').split("_")[1];
                        var pkeyID = typeof $('#attnRegisterRow' + rndmNum + '_RegisterID').val() === 'undefined' ? '-1' : $('#attnRegisterRow' + rndmNum + '_RegisterID').val();
                        getOneAttnRegisterForm(pkeyID, 1);
                    });
                    $('#attnRegisterTable tbody')
                            .off('mouseenter', 'tr');
                    $('#attnRegisterTable tbody')
                            .on('mouseenter', 'tr', function () {
                                if ($(this).hasClass('highlight')) {
                                    $(this).removeClass('highlight');
                                } else {
                                    table1.$('tr.highlight').removeClass('highlight');
                                    $(this).addClass('highlight');
                                }
                            });

                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-toggle="tabajxattnrgstr"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=16&typ=1' + dttrgt;
                        $(targ + 'tab').tab('show');
                        if (targ.indexOf('attnRegisterHeadCount') >= 0) {
                            $('#addNwAttnRegisterSmryBtn').addClass('hideNotice');
                            $('#addNwAttnRegisterHdCntBtn').removeClass('hideNotice');
                            $('#addNwAttnRgstrEvntCostBtn').addClass('hideNotice');
                        } else if (targ.indexOf('attnRgstrEvntCost') >= 0) {
                            $('#addNwAttnRegisterSmryBtn').addClass('hideNotice');
                            $('#addNwAttnRegisterHdCntBtn').addClass('hideNotice');
                            $('#addNwAttnRgstrEvntCostBtn').removeClass('hideNotice');
                        } else {
                            $('#addNwAttnRegisterSmryBtn').removeClass('hideNotice');
                            $('#addNwAttnRegisterHdCntBtn').addClass('hideNotice');
                            $('#addNwAttnRgstrEvntCostBtn').addClass('hideNotice');
                        }
                    });
                } else if (lnkArgs.indexOf("&pg=2&vtyp=2") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#oneAttnRegisterSmryLinesTable')) {
                        var table2 = $('#oneAttnRegisterSmryLinesTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#oneAttnRegisterSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnRegisterForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#attnTmeTblTable')) {
                        table1 = $('#attnTmeTblTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#attnTmeTblTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    if (!$.fn.DataTable.isDataTable('#oneAttnTmeTblSmryLinesTable')) {
                        var table2 = $('#oneAttnTmeTblSmryLinesTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#oneAttnTmeTblSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnTmeTblForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                    $('#attnTmeTblTable tbody').off('click');
                    $('#attnTmeTblTable tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected');
                        } else {
                            table1.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');
                        }
                        var rndmNum = $(this).attr('id').split("_")[1];
                        var pkeyID = typeof $('#attnTmeTblRow' + rndmNum + '_AttnTmeTblID').val() === 'undefined' ? '-1' : $('#attnTmeTblRow' + rndmNum + '_AttnTmeTblID').val();
                        getOneAttnTmeTblForm(pkeyID, 1);
                    });
                    $('#attnTmeTblTable tbody')
                            .off('mouseenter', 'tr');
                    $('#attnTmeTblTable tbody')
                            .on('mouseenter', 'tr', function () {
                                if ($(this).hasClass('highlight')) {
                                    $(this).removeClass('highlight');
                                } else {
                                    table1.$('tr.highlight').removeClass('highlight');
                                    $(this).addClass('highlight');
                                }
                            });

                } else if (lnkArgs.indexOf("&pg=3&vtyp=2") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#oneAttnTmeTblSmryLinesTable')) {
                        var table2 = $('#oneAttnTmeTblSmryLinesTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#oneAttnTmeTblSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnTmeTblForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=4&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#attnEvntStpHdrsTable')) {
                        table1 = $('#attnEvntStpHdrsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#attnEvntStpHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnEvntStpForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#attnVenueTable')) {
                        table1 = $('#attnVenueTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#attnVenueTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnVenueForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });

                    $('#attnVenueTable tbody').off('click');
                    $('#attnVenueTable tbody').on('click', 'tr', function () {
                        if ($(this).hasClass('selected')) {
                            $(this).removeClass('selected');
                        } else {
                            table1.$('tr.selected').removeClass('selected');
                            $(this).addClass('selected');
                        }
                        var rndmNum = $(this).attr('id').split("_")[1];
                        var pkeyID = typeof $('#attnVenueRow' + rndmNum + '_CodeID').val() === 'undefined' ? '-1' : $('#attnVenueRow' + rndmNum + '_CodeID').val();
                        getOneAttnVenueForm(pkeyID, 1);
                    });
                    $('#attnVenueTable tbody')
                            .off('mouseenter', 'tr');
                    $('#attnVenueTable tbody')
                            .on('mouseenter', 'tr', function () {
                                if ($(this).hasClass('highlight')) {
                                    $(this).removeClass('highlight');
                                } else {
                                    table1.$('tr.highlight').removeClass('highlight');
                                    $(this).addClass('highlight');
                                }
                            });
                } else if (lnkArgs.indexOf("&pg=6&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#attnRegstrSrchHdrsTable')) {
                        table1 = $('#attnRegstrSrchHdrsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#attnRegstrSrchHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnRegstrSrchForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=7&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#hotlChckinDocHdrsTable')) {
                        table1 = $('#hotlChckinDocHdrsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#hotlChckinDocHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#hotlChckinDocForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=8&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#attnDfltAcntsHdrsTable')) {
                        table1 = $('#attnDfltAcntsHdrsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#attnDfltAcntsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnDfltAcntsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=9&vtyp=0") !== -1)
                {
                    var table1;
                    if (!$.fn.DataTable.isDataTable('#attnResultsHdrsTable')) {
                        table1 = $('#attnResultsHdrsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#attnResultsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#attnResultsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                }
            });
        }
        htBody.removeClass("mdlloading");
        $('[data-toggle="tooltip"]').tooltip();
        $(".jbDetRfDc").focus(function () {
            $(this).select();
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
        $(".jbDetFuncRate").focus(function () {
            $(this).select();
        });
        $(".jbDetAccRate").focus(function () {
            $(this).select();
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
    });
}

function getMyEvntsAtnd(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#myEvntsAtndSrchFor").val() === 'undefined' ? '%' : $("#myEvntsAtndSrchFor").val();
    var srchIn = typeof $("#myEvntsAtndSrchIn").val() === 'undefined' ? 'Both' : $("#myEvntsAtndSrchIn").val();
    var pageNo = typeof $("#myEvntsAtndPageNo").val() === 'undefined' ? 1 : $("#myEvntsAtndPageNo").val();
    var limitSze = typeof $("#myEvntsAtndDsplySze").val() === 'undefined' ? 10 : $("#myEvntsAtndDsplySze").val();
    var sortBy = typeof $("#myEvntsAtndSortBy").val() === 'undefined' ? '' : $("#myEvntsAtndSortBy").val();
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

function enterKeyFuncMyEvntsAtnd(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyEvntsAtnd(actionText, slctr, linkArgs);
    }
}

function getOneMyEvntsAtndForm(pKeyID, pKeyID1, vwtype)
{
    var lnkArgs = 'grp=16&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdPyReqID=' + pKeyID + '&sbmtdMspyID=' + pKeyID1;
    doAjaxWthCallBck(lnkArgs, 'myEvntsAtndDetailInfo', 'PasteDirect', '', '', '', function () {
        var table1 = $('#myPayRnLinesTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myPayRnLinesTable').wrap('<div class="dataTables_scroll"/>');
        $("#myPyRnTotal1").html(urldecode($("#myPyRnTotal2").val()));
    });

}
function grpTypAttnChangeV()
{
    var lovChkngElementVal = typeof $("#attnEvntStpGrpTyp").val() === 'undefined' ? '' : $("#attnEvntStpGrpTyp").val();
    if (lovChkngElementVal === "Everyone")
    {
        $('#attnEvntStpGrpName').attr("disabled", "true");
        $('#attnEvntStpGrpName').val("");
        $('#attnEvntStpGrpID').val("-1");
        $('#groupNameLbl').attr("disabled", "true");
    } else
    {
        $('#attnEvntStpGrpName').removeAttr("disabled");
        $('#attnEvntStpGrpName').val("");
        $('#attnEvntStpGrpID').val("-1");
        $('#groupNameLbl').removeAttr("disabled");
    }
}