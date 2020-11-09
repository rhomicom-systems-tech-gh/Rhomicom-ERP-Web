function preparePay(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1)
        {
            var table1 = $('#myPayRnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPayRnsTable').wrap('<div class="dataTables_scroll"/>');
            $('#myPayRnsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#myPayRnLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPayRnLinesTable').wrap('<div class="dataTables_scroll"/>');
            $('#myPayRnsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#myPayRnsRow' + rndmNum + '_PyReqID').val() === 'undefined' ? '%' : $('#myPayRnsRow' + rndmNum + '_PyReqID').val();
                var pkeyID1 = typeof $('#myPayRnsRow' + rndmNum + '_MspyID').val() === 'undefined' ? '%' : $('#myPayRnsRow' + rndmNum + '_MspyID').val();
                getOneMyPyRnsForm(pkeyID, pkeyID1, 1);
            });
            $('#myPayRnsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=2&vtyp=2") !== -1)
        {
            var table1 = $('#myRcvblInvcsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myRcvblInvcsTable').wrap('<div class="dataTables_scroll"/>');
            $('#myRcvblInvcsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#myRcvblInvcLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myRcvblInvcLinesTable').wrap('<div class="dataTables_scroll"/>');

            var table3 = $('#myRcvblInvcSmryTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myRcvblInvcSmryTable').wrap('<div class="dataTables_scroll"/>');
            $('#myRcvblInvcsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#myRcvblInvcsRow' + rndmNum + '_RcvblID').val() === 'undefined' ? '%' : $('#myRcvblInvcsRow' + rndmNum + '_RcvblID').val();
                var pkeyID1 = typeof $('#myRcvblInvcsRow' + rndmNum + '_SalesID').val() === 'undefined' ? '%' : $('#myRcvblInvcsRow' + rndmNum + '_SalesID').val();
                getOneMyRcvblInvcsForm(pkeyID, pkeyID1, 3);
            });
            $('#myRcvblInvcsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else if (lnkArgs.indexOf("&pg=3&vtyp=4") !== -1)
        {
            var table1 = $('#myPyblInvcsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPyblInvcsTable').wrap('<div class="dataTables_scroll"/>');
            $('#myPyblInvcsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            var table2 = $('#myPyblInvcLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPyblInvcLinesTable').wrap('<div class="dataTables_scroll"/>');

            var table3 = $('#myPyblInvcSmryTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#myPyblInvcSmryTable').wrap('<div class="dataTables_scroll"/>');
            $('#myPyblInvcsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#myPyblInvcsRow' + rndmNum + '_PyblID').val() === 'undefined' ? '%' : $('#myPyblInvcsRow' + rndmNum + '_PyblID').val();
                getOneMyPyblInvcsForm(pkeyID, 5);
            });
            $('#myPyblInvcsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
        } else {
            loadScript("app/pay/pay_admin.js?v=" + jsFilesVrsn, function () {
                if (lnkArgs.indexOf("&pg=4&vtyp=0") !== -1)
                {
                    prepareQckPay();
                } else if (lnkArgs.indexOf("&pg=4&vtyp=3") !== -1)
                {
                    preparePrsItmAsgn();
                } else if (lnkArgs.indexOf("&pg=4&vtyp=4") !== -1)
                {
                    preparePrsAcnts();
                } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1)
                {
                    prepareItemSets();
                } else if (lnkArgs.indexOf("&pg=5&vtyp=1") !== -1)
                {
                    prepareItemSetItms();
                } else if (lnkArgs.indexOf("&pg=6&vtyp=0") !== -1)
                {
                    preparePrsSets();
                } else if (lnkArgs.indexOf("&pg=6&vtyp=1") !== -1)
                {
                    preparePrsSetPrsns();
                } else if (lnkArgs.indexOf("&pg=7&vtyp=") !== -1)
                {
                    prepareMassPay();
                } else if (lnkArgs.indexOf("&pg=8&vtyp=0") !== -1)
                {
                    var table1 = $('#allPayRnTrnsHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allPayRnTrnsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    $('#allPayRnTrnsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
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
                } else if (lnkArgs.indexOf("&pg=9&vtyp=0") !== -1)
                {
                    var table1 = $('#allPayGLIntrfcsHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allPayGLIntrfcsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    $('#allPayGLIntrfcsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
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
                } else if (lnkArgs.indexOf("&pg=10&vtyp=0") !== -1)
                {
                    preparePayItems();
                } else if (lnkArgs.indexOf("&pg=11&vtyp=0") !== -1 || lnkArgs.indexOf("&pg=11") !== -1)
                {
                    prepareGlobalVals();
                } else if (lnkArgs.indexOf("&pg=12&vtyp=0") !== -1)
                {
                    if (!$.fn.DataTable.isDataTable('#allPayeRatesTable')) {
                        var table1 = $('#allPayeRatesTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#allPayeRatesTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#allPayeRatesForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=13&vtyp=0") !== -1)
                {
                    if (!$.fn.DataTable.isDataTable('#payTrnsRqstsHdrsTable')) {
                        var table1 = $('#payTrnsRqstsHdrsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#payTrnsRqstsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#payTrnsRqstsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=14&vtyp=0") !== -1)
                {
                    if (!$.fn.DataTable.isDataTable('#payInvstTransHdrsTable')) {
                        var table1 = $('#payInvstTransHdrsTable').DataTable({
                            "paging": false,
                            "ordering": false,
                            "info": false,
                            "bFilter": false,
                            "scrollX": false
                        });
                        $('#payInvstTransHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    }
                    $('#payInvstTransForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                } else if (lnkArgs.indexOf("&pg=15&vtyp=0") !== -1)
                {
                    var table1 = $('#payTransTypsHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#payTransTypsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                    $('#payTransTypsForm').submit(function (e) {
                        e.preventDefault();
                        return false;
                    });
                }
            });
        }
        htBody.removeClass("mdlloading");
    });
}

function getMyPayRns(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#myPayRnsSrchFor").val() === 'undefined' ? '%' : $("#myPayRnsSrchFor").val();
    var srchIn = typeof $("#myPayRnsSrchIn").val() === 'undefined' ? 'Both' : $("#myPayRnsSrchIn").val();
    var pageNo = typeof $("#myPayRnsPageNo").val() === 'undefined' ? 1 : $("#myPayRnsPageNo").val();
    var limitSze = typeof $("#myPayRnsDsplySze").val() === 'undefined' ? 10 : $("#myPayRnsDsplySze").val();
    var sortBy = typeof $("#myPayRnsSortBy").val() === 'undefined' ? '' : $("#myPayRnsSortBy").val();
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

function enterKeyFuncMyPayRns(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyPayRns(actionText, slctr, linkArgs);
    }
}

function getOneMyPyRnsForm(pKeyID, pKeyID1, vwtype)
{
    var lnkArgs = 'grp=7&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdPyReqID=' + pKeyID + '&sbmtdMspyID=' + pKeyID1;
    doAjaxWthCallBck(lnkArgs, 'myPayRnsDetailInfo', 'PasteDirect', '', '', '', function () {
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

function saveMyPayRnyForm() {
    var a = 1;
}

function getMyRcvblInvcs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#myRcvblInvcsSrchFor").val() === 'undefined' ? '%' : $("#myRcvblInvcsSrchFor").val();
    var srchIn = typeof $("#myRcvblInvcsSrchIn").val() === 'undefined' ? 'Both' : $("#myRcvblInvcsSrchIn").val();
    var pageNo = typeof $("#myRcvblInvcsPageNo").val() === 'undefined' ? 1 : $("#myRcvblInvcsPageNo").val();
    var limitSze = typeof $("#myRcvblInvcsDsplySze").val() === 'undefined' ? 10 : $("#myRcvblInvcsDsplySze").val();
    var sortBy = typeof $("#myRcvblInvcsSortBy").val() === 'undefined' ? '' : $("#myRcvblInvcsSortBy").val();
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

function enterKeyFuncMyRcvblInvcs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyRcvblInvcs(actionText, slctr, linkArgs);
    }
}

function getOneMyRcvblInvcsForm(pKeyID, pKeyID1, vwtype)
{
    var lnkArgs = 'grp=7&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdRcvblInvcID=' + pKeyID + '&sbmtdSalesInvcID=' + pKeyID1;
    doAjaxWthCallBck(lnkArgs, 'myRcvblInvcsDetailInfo', 'PasteDirect', '', '', '', function () {
        var table1 = $('#myRcvblInvcLinesTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myRcvblInvcLinesTable').wrap('<div class="dataTables_scroll"/>');

        var table3 = $('#myRcvblInvcSmryTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myRcvblInvcSmryTable').wrap('<div class="dataTables_scroll"/>');
    });

}


function getMyPyblInvcs(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#myPyblInvcsSrchFor").val() === 'undefined' ? '%' : $("#myPyblInvcsSrchFor").val();
    var srchIn = typeof $("#myPyblInvcsSrchIn").val() === 'undefined' ? 'Both' : $("#myPyblInvcsSrchIn").val();
    var pageNo = typeof $("#myPyblInvcsPageNo").val() === 'undefined' ? 1 : $("#myPyblInvcsPageNo").val();
    var limitSze = typeof $("#myPyblInvcsDsplySze").val() === 'undefined' ? 10 : $("#myPyblInvcsDsplySze").val();
    var sortBy = typeof $("#myPyblInvcsSortBy").val() === 'undefined' ? '' : $("#myPyblInvcsSortBy").val();
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

function enterKeyFuncMyPyblInvcs(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getMyPyblInvcs(actionText, slctr, linkArgs);
    }
}

function getOneMyPyblInvcsForm(pKeyID, vwtype)
{
    var lnkArgs = 'grp=7&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdPyblInvcID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myPyblInvcsDetailInfo', 'PasteDirect', '', '', '', function () {
        var table1 = $('#myPyblInvcLinesTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myPyblInvcLinesTable').wrap('<div class="dataTables_scroll"/>');

        var table3 = $('#myPyblInvcSmryTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#myPyblInvcSmryTable').wrap('<div class="dataTables_scroll"/>');
    });
}