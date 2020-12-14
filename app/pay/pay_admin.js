function prepareItemSetItms() {
    var table2 = $('#itemSetItmsTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#itemSetItmsTable').wrap('<div class="dataTables_scroll"/>');
}

function prepareItemSets() {
    var table1 = $('#allItemSetsTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#allItemSetsTable').wrap('<div class="dataTables_scroll"/>');

    var table2 = $('#itemSetItmsTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#itemSetItmsTable').wrap('<div class="dataTables_scroll"/>');

    $('#allItemSetsTable tbody').on('click','tr',function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

        } else {
            table1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

        }
        var rndmNum = $(this).attr('id').split("_")[1];
        var pkeyID = typeof $('#allItemSetsRow' + rndmNum + '_ItemSetID').val() === 'undefined' ? '%' : $('#allItemSetsRow' + rndmNum + '_ItemSetID').val();
        getOneItemSetItms(pkeyID,1);
    });

    $('#allItemSetsTable tbody')
        .on('mouseenter','tr',function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
    $('#allItemSetsForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
}

var msPytable1;

function prepareMassPay() {
    if (!$.fn.DataTable.isDataTable('#payMassPyTable')) {
        msPytable1 = $('#payMassPyTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payMassPyTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#payMassPyRunDetsTable')) {
        var table2 = $('#payMassPyRunDetsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payMassPyRunDetsTable').wrap('<div class="dataTables_scroll"/>');
    }
    $('#payMassPyForm').submit(function (e) {
        e.preventDefault();
        return false;
    });

    $('#payMassPyTable tbody').off('click');
    $('#payMassPyTable tbody').on('click','tr',function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            msPytable1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var rndmNum = $(this).attr('id').split("_")[1];
        var pkeyID = typeof $('#payMassPyRow' + rndmNum + '_CodeID').val() === 'undefined' ? '-1' : $('#payMassPyRow' + rndmNum + '_CodeID').val();
        getOnePayMassPyForm(pkeyID,1);
    });
    $('#payMassPyTable tbody')
        .off('mouseenter','tr');
    $('#payMassPyTable tbody')
        .on('mouseenter','tr',function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                msPytable1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tabajxpaymasspy"]').off('click');
    $('[data-toggle="tabajxpaymasspy"]').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var targ = $this.attr('href');
        var dttrgt = $this.attr('data-rhodata');
        var linkArgs = 'grp=7&typ=1' + dttrgt;
        $(targ + 'tab').tab('show');
        if (targ.indexOf('payMassPyAttchdVals') >= 0) {
            $('#payMassPyDtTabNo').val(2);
        } else if (targ.indexOf('payMassPyQckPayVals') >= 0) {
            $('#payMassPyDtTabNo').val(3);
        } else {
            $('#payMassPyDtTabNo').val(1);
        }
    });

    $(".jbDetDbt").focus(function () {
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
}

function preparePayItems() {
    if (!$.fn.DataTable.isDataTable('#payPayItmsTable')) {
        msPytable1 = $('#payPayItmsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payPayItmsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#payPayItmsBalsFeedsTable')) {
        var table2 = $('#payPayItmsBalsFeedsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payPayItmsBalsFeedsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#payPayItmsPsblValsTable')) {
        var table2 = $('#payPayItmsPsblValsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payPayItmsPsblValsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#payPayItmsExtrInfTable')) {
        var table2 = $('#payPayItmsExtrInfTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payPayItmsExtrInfTable').wrap('<div class="dataTables_scroll"/>');
    }
    $('#payPayItmsForm').submit(function (e) {
        e.preventDefault();
        return false;
    });

    $('#payPayItmsTable tbody').off('click');
    $('#payPayItmsTable tbody').on('click','tr',function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            msPytable1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var rndmNum = $(this).attr('id').split("_")[1];
        var pkeyID = typeof $('#payPayItmsRow' + rndmNum + '_ItemID').val() === 'undefined' ? '-1' : $('#payPayItmsRow' + rndmNum + '_ItemID').val();
        getOnePayPayItmsForm(pkeyID,0);
    });
    $('#payPayItmsTable tbody')
        .off('mouseenter','tr');
    $('#payPayItmsTable tbody')
        .on('mouseenter','tr',function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                msPytable1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tabajxpaypayitms"]').off('click');
    $('[data-toggle="tabajxpaypayitms"]').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var targ = $this.attr('href');
        var dttrgt = $this.attr('data-rhodata');
        var linkArgs = 'grp=7&typ=1' + dttrgt;
        $(targ + 'tab').tab('show');
        if (targ.indexOf('payPayItmsPsblVals') >= 0) {
            $('#addNewPayItmsBtn').addClass('hideNotice');
            $('#addNewPayItmsAttchdValBtn').removeClass('hideNotice');
            $('#payPayItmsDtTabNo').val(2);
        } else if (targ.indexOf('payPayItmsExtraInfo') >= 0) {
            $('#payPayItmsDtTabNo').val(3);
            $('#addNewPayItmsBtn').addClass('hideNotice');
            $('#addNewPayItmsAttchdValBtn').addClass('hideNotice');
        } else {
            $('#addNewPayItmsBtn').removeClass('hideNotice');
            $('#addNewPayItmsAttchdValBtn').addClass('hideNotice');
            $('#payPayItmsDtTabNo').val(1);
        }
    });

    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
}

function prepareGlobalVals() {
    if (!$.fn.DataTable.isDataTable('#payGlblValsTable')) {
        msPytable1 = $('#payGlblValsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payGlblValsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#payGlblValsRunDetsTable')) {
        var table2 = $('#payGlblValsRunDetsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#payGlblValsRunDetsTable').wrap('<div class="dataTables_scroll"/>');
    }
    $('#payGlblValsForm').submit(function (e) {
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
    $('#payGlblValsTable tbody').off('click');
    $('#payGlblValsTable tbody').on('click','tr',function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            msPytable1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var rndmNum = $(this).attr('id').split("_")[1];
        var pkeyID = typeof $('#payGlblValsRow' + rndmNum + '_GBVID').val() === 'undefined' ? '-1' : $('#payGlblValsRow' + rndmNum + '_GBVID').val();
        getOnePayGlblValsForm(pkeyID,1);
    });
    $('#payGlblValsTable tbody')
        .off('mouseenter','tr');
    $('#payGlblValsTable tbody')
        .on('mouseenter','tr',function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                msPytable1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });

    $('[data-toggle="tooltip"]').tooltip();

    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
}

function preparePrsSetPrsns() {
    var table2 = $('#prsSetPrsnsTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#prsSetPrsnsTable').wrap('<div class="dataTables_scroll"/>');
}

function preparePrsSets() {
    var table1 = $('#allPrsSetsTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#allPrsSetsTable').wrap('<div class="dataTables_scroll"/>');
    var table2 = $('#prsSetPrsnsTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#prsSetPrsnsTable').wrap('<div class="dataTables_scroll"/>');

    $('#allPrsSetsTable tbody').on('click','tr',function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

        } else {
            table1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

        }
        var rndmNum = $(this).attr('id').split("_")[1];
        var pkeyID = typeof $('#allPrsSetsRow' + rndmNum + '_PrsSetID').val() === 'undefined' ? '%' : $('#allPrsSetsRow' + rndmNum + '_PrsSetID').val();
        getOnePrsnSetPrsns(pkeyID,1);
    });
    $('#allPrsSetsTable tbody')
        .on('mouseenter','tr',function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
    $('#allPrsSetsForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
}

function preparePrsAcnts() {
    if (!$.fn.DataTable.isDataTable('#prsnBanksTable')) {
        var table1 = $('#prsnBanksTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#prsnBanksTable').wrap('<div class="dataTables_scroll"/>');
    }
}

function preparePrsItmAsgn() {
    if (!$.fn.DataTable.isDataTable('#prsnItmsTable')) {
        var table1 = $('#prsnItmsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#prsnItmsTable').wrap('<div class="dataTables_scroll"/>');
    }
    $('#prsnItmsForm').submit(function (e) {
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
}

function prepareQckPay() {
    $(function () {
        $('[data-toggle="tabajxqpay"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=7&typ=1' + dttrgt;
            return openATab(targ,linkArgs);
        });
    });

    var table1 = $('#qckPayPrsnsTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#qckPayPrsnsTable').wrap('<div class="dataTables_scroll"/>');
    $('#qckPayPrsnsForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
    var table2 = $('#prsnPyHstrysTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "bFilter": false,
        "scrollX": false
    });
    $('#prsnPyHstrysTable').wrap('<div class="dataTables_scroll"/>');
    $('#qckPayPrsnsTable tbody').on('click','tr',function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

        } else {
            table1.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

        }
        var rndmNum = $(this).attr('id').split("_")[1];
        var pkeyID = typeof $('#qckPayPrsnsRow' + rndmNum + '_PrsnID').val() === 'undefined' ? '%' : $('#qckPayPrsnsRow' + rndmNum + '_PrsnID').val();
        getOneQckPayPrsnForm(pkeyID,1);
    });
    $('#qckPayPrsnsTable tbody')
        .on('mouseenter','tr',function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table1.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
    $('#prsnPyHstrysTable tbody').on('click','tr',function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

        } else {
            table2.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

        }
    });
    $('#prsnPyHstrysTable tbody')
        .on('mouseenter','tr',function () {
            if ($(this).hasClass('highlight')) {
                $(this).removeClass('highlight');
            } else {
                table2.$('tr.highlight').removeClass('highlight');
                $(this).addClass('highlight');
            }
        });
}

function rfrshQckPayPrsns() {
    getQckPayPrsns('','#allmodules','grp=7&typ=1&pg=4&vtyp=0');
}

function getQckPayPrsns(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#qckPayPrsnsSrchFor").val() === 'undefined' ? '%' : $("#qckPayPrsnsSrchFor").val();
    var srchIn = typeof $("#qckPayPrsnsSrchIn").val() === 'undefined' ? 'Both' : $("#qckPayPrsnsSrchIn").val();
    var pageNo = typeof $("#qckPayPrsnsPageNo").val() === 'undefined' ? 1 : $("#qckPayPrsnsPageNo").val();
    var limitSze = typeof $("#qckPayPrsnsDsplySze").val() === 'undefined' ? 10 : $("#qckPayPrsnsDsplySze").val();
    var sortBy = typeof $("#qckPayPrsnsSortBy").val() === 'undefined' ? '' : $("#qckPayPrsnsSortBy").val();
    var sortBy = typeof $("#qckPayPrsnsSortBy").val() === 'undefined' ? '' : $("#qckPayPrsnsSortBy").val();
    var qckPayPrsnSetID = typeof $("#qckPayPrsnSetID").val() === 'undefined' ? -1 : $("#qckPayPrsnSetID").val();
    var qckPayItmSetID = typeof $("#qckPayItmSetID").val() === 'undefined' ? -1 : $("#qckPayItmSetID").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" +
        pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy +
        "&sbmtdPrsnSetID=" + qckPayPrsnSetID + "&sbmtdItmSetID=" + qckPayItmSetID;
    openATab(slctr,linkArgs);
}

function enterKeyFuncQckPayPrsns(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getQckPayPrsns(actionText,slctr,linkArgs);
    }
}

function getOneQckPayPrsnForm(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=4&vtyp=' + vwtype + '&sbmtdPrsnSetMmbrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'qckPayPrsnsDetailInfo','PasteDirect','','','',function () {
        $(function () {
            /*var numelms=$('[data-toggle="tabajxgst"]').size();*/
            $('[data-toggle="tabajxqpay"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=7&typ=1' + dttrgt;
                return openATab(targ,linkArgs);
            });
        });
        var table1 = $('#prsnPyHstrysTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#prsnPyHstrysTable').wrap('<div class="dataTables_scroll"/>');
        $('#prsnPyHstrysTable tbody').on('click','tr',function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');

            } else {
                table1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');

            }
        });
        $('#prsnPyHstrysTable tbody')
            .on('mouseenter','tr',function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    table1.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
                }
            });
    });
}

function getPrsnPyHstrys(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#prsnPyHstrysSrchFor").val() === 'undefined' ? '%' : $("#prsnPyHstrysSrchFor").val();
    var srchIn = typeof $("#prsnPyHstrysSrchIn").val() === 'undefined' ? 'Both' : $("#prsnPyHstrysSrchIn").val();
    var pageNo = typeof $("#prsnPyHstrysPageNo").val() === 'undefined' ? 1 : $("#prsnPyHstrysPageNo").val();
    var limitSze = typeof $("#prsnPyHstrysDsplySze").val() === 'undefined' ? 10 : $("#prsnPyHstrysDsplySze").val();
    var sortBy = typeof $("#prsnPyHstrysSortBy").val() === 'undefined' ? '' : $("#prsnPyHstrysSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs,'prsnPyHstrysList','PasteDirect','','','',function () {
        var table1 = $('#prsnPyHstrysTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#prsnPyHstrysTable').wrap('<div class="dataTables_scroll"/>');
        $('#prsnPyHstrysTable tbody').on('click','tr',function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');

            } else {
                table1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');

            }
        });
        $('#prsnPyHstrysTable tbody')
            .on('mouseenter','tr',function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    table1.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
                }
            });
    });
}

function enterKeyFuncPrsnPyHstrys(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPrsnPyHstrys(actionText,slctr,linkArgs);
    }
}

function getOnePrsnPyHstrysForm(pKeyID,pKeyID1,vwtype,sbmtdPrsnSetMmbrID,dialogTitle) {
    var lnkArgs = 'grp=7&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdPyReqID=' + pKeyID1 + '&sbmtdMspyID=' + pKeyID + '&sbmtdPrsnSetMmbrID=' + sbmtdPrsnSetMmbrID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalLg','ShowDialog',dialogTitle,'myFormsModalTitleLg','myFormsModalBodyLg',function () {
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

function getAllPrsnItms(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#prsnItmsSrchFor").val() === 'undefined' ? '%' : $("#prsnItmsSrchFor").val();
    var srchIn = typeof $("#prsnItmsSrchIn").val() === 'undefined' ? 'Both' : $("#prsnItmsSrchIn").val();
    var pageNo = typeof $("#prsnItmsPageNo").val() === 'undefined' ? 1 : $("#prsnItmsPageNo").val();
    var limitSze = typeof $("#prsnItmsDsplySze").val() === 'undefined' ? 10 : $("#prsnItmsDsplySze").val();
    var sortBy = typeof $("#prsnItmsSortBy").val() === 'undefined' ? '' : $("#prsnItmsSortBy").val();
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

function enterKeyFuncPrsnItms(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPrsnItms(actionText,slctr,linkArgs);
    }
}

function savePrsnItmsForm() {
    var sbmtdPrsnSetMmbrID = typeof $("#qckPayPrsns_PrsnID").val() === 'undefined' ? '-1' : $("#qckPayPrsns_PrsnID").val();
    var slctdPrsnItems = "";
    $('#prsnItmsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#prsnItmsRow' + rndmNum + '_PKeyID').val() === 'undefined' || typeof $('#prsnItmsRow' + rndmNum + '_StrtDte').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    slctdPrsnItems = slctdPrsnItems +
                        $('#prsnItmsRow' + rndmNum + '_PKeyID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnItmsRow' + rndmNum + '_PrsItmID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnItmsRow' + rndmNum + '_PrsItmValID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnItmsRow' + rndmNum + '_StrtDte').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnItmsRow' + rndmNum + '_EndDte').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Person Items',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Person Items...Please Wait...</p>',
        callback: function () {
            getAllPrsnItms('','#prsnPyItmsAsgndPage','grp=7&typ=1&pg=4&vtyp=3&sbmtdPrsnSetMmbrID=' + sbmtdPrsnSetMmbrID);;
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
                    grp: 7,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 1,
                    sbmtdPrsnSetMmbrID: sbmtdPrsnSetMmbrID,
                    slctdPrsnItems: slctdPrsnItems
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

function delPrsnItem(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var prsnLocID = '';
    if (typeof $('#prsnItmsRow' + rndmNum + '_PKeyID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#prsnItmsRow' + rndmNum + '_PKeyID').val();
        prsnLocID = typeof $("#qckPayPrsns_PrsnID").val() === 'undefined' ? '-1' : $("#qckPayPrsns_PrsnID").val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Person Item?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Person Item?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Person Item?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Person Item...Please Wait...</p>'
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 1,
                                    prsnLocID: prsnLocID,
                                    pKeyID: pKeyID
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

function savePrsnAccountForm() {
    var sbmtdPrsnSetMmbrID = typeof $("#qckPayPrsns_PrsnID").val() === 'undefined' ? '-1' : $("#qckPayPrsns_PrsnID").val();
    var slctdPrsnAccount = "";
    $('#prsnBanksTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#prsnBanksRow' + rndmNum + '_PKeyID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    slctdPrsnAccount = slctdPrsnAccount +
                        $('#prsnBanksRow' + rndmNum + '_PKeyID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnBanksRow' + rndmNum + '_BankNm').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnBanksRow' + rndmNum + '_BankBrnchs').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnBanksRow' + rndmNum + '_AcntNm').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnBanksRow' + rndmNum + '_AcntNum').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnBanksRow' + rndmNum + '_AcntTyp').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnBanksRow' + rndmNum + '_NetPrtn').val().replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsnBanksRow' + rndmNum + '_PrtnUOM').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Person Accounts',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Person Accounts...Please Wait...</p>',
        callback: function () {
            getAllPrsnItms('','#prsnBankAcntsPage','grp=7&typ=1&pg=4&vtyp=4&sbmtdPrsnSetMmbrID=' + sbmtdPrsnSetMmbrID);
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
                    grp: 7,
                    typ: 1,
                    pg: 4,
                    q: 'UPDATE',
                    actyp: 2,
                    sbmtdPrsnSetMmbrID: sbmtdPrsnSetMmbrID,
                    slctdPrsnAccount: slctdPrsnAccount
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

function delPrsnAccount(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var prsnLocID = '';
    if (typeof $('#prsnBanksRow' + rndmNum + '_PKeyID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#prsnBanksRow' + rndmNum + '_PKeyID').val();
        prsnLocID = typeof $("#qckPayPrsns_PrsnID").val() === 'undefined' ? '-1' : $("#qckPayPrsns_PrsnID").val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Person Account?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Person Account?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Person Account?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Person Account...Please Wait...</p>'
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 4,
                                    q: 'DELETE',
                                    actyp: 2,
                                    prsnLocID: prsnLocID,
                                    pKeyID: pKeyID
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

function getAllPrsSets(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#allPrsSetsSrchFor").val() === 'undefined' ? '%' : $("#allPrsSetsSrchFor").val();
    var srchIn = typeof $("#allPrsSetsSrchIn").val() === 'undefined' ? 'Both' : $("#allPrsSetsSrchIn").val();
    var pageNo = typeof $("#allPrsSetsPageNo").val() === 'undefined' ? 1 : $("#allPrsSetsPageNo").val();
    var limitSze = typeof $("#allPrsSetsDsplySze").val() === 'undefined' ? 10 : $("#allPrsSetsDsplySze").val();
    var sortBy = typeof $("#allPrsSetsSortBy").val() === 'undefined' ? '' : $("#allPrsSetsSortBy").val();
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

function enterKeyFuncAllPrsSets(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllItemSets(actionText,slctr,linkArgs);
    }
}

function getOnePrsnSetPrsns(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=6&vtyp=' + vwtype + '&sbmtdPrsnSetHdrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'allPrsSetsDetailInfo','PasteDirect','','','',function () {
        var table1 = $('#prsSetPrsnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#prsSetPrsnsTable').wrap('<div class="dataTables_scroll"/>');

    });
}

function getAllPrsSetPrsns(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#prsSetPrsnsSrchFor").val() === 'undefined' ? '%' : $("#prsSetPrsnsSrchFor").val();
    var srchIn = typeof $("#prsSetPrsnsSrchIn").val() === 'undefined' ? 'Both' : $("#prsSetPrsnsSrchIn").val();
    var pageNo = typeof $("#prsSetPrsnsPageNo").val() === 'undefined' ? 1 : $("#prsSetPrsnsPageNo").val();
    var limitSze = typeof $("#prsSetPrsnsDsplySze").val() === 'undefined' ? 10 : $("#prsSetPrsnsDsplySze").val();
    var sortBy = typeof $("#prsSetPrsnsSortBy").val() === 'undefined' ? '' : $("#prsSetPrsnsSortBy").val();
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

function enterKeyFuncPrsSetPrsns(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPrsSetPrsns(actionText,slctr,linkArgs);
    }
}

function getOnePrsSetForm(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=6&vtyp=' + vwtype + '&sbmtdPrsnSetHdrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalNrml','ShowDialog','Person Set Details (ID:' + pKeyID + ')','myFormsModalNrmlTitle','myFormsModalNrmlBody',function () {
        var table1 = $('#prsSetAllwdRolesTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#prsSetAllwdRolesTable').wrap('<div class="dataTables_scroll"/>');
    });
    $('#prsSetDetailsForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
}

function savePrsnSetForm(actionText,slctr,linkArgs) {
    var prsnSetNm = typeof $("#prsnSetNm").val() === 'undefined' ? '' : $("#prsnSetNm").val();
    var prsnSetID = typeof $("#prsnSetID").val() === 'undefined' ? '-1' : $("#prsnSetID").val();
    var prsnSetDesc = typeof $("#prsnSetDesc").val() === 'undefined' ? '' : $("#prsnSetDesc").val();
    var prsnSetUsesSQL = typeof $("input[name='prsnSetUsesSQL']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var prsnSetEnbld = typeof $("input[name='prsnSetEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var prsnSetIsDflt = typeof $("input[name='prsnSetIsDflt']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var prsnSetSQL = typeof $("#prsnSetSQL").val() === 'undefined' ? '' : $("#prsnSetSQL").val();

    if (prsnSetNm === "" || prsnSetNm === null) {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Person Set Name cannot be empty!</span></p>'
        });
        return false;
    }
    if (prsnSetUsesSQL !== 'NO' && prsnSetSQL === "") {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Person Set Query cannot be empty if this Set Uses SQL!</span></p>'
        });
        return false;
    }
    var slctdPrsnSetRoles = "";
    $('#prsSetAllwdRolesTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#prsSetAlwdRlsRow' + rndmNum + '_RoleID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    slctdPrsnSetRoles = slctdPrsnSetRoles + $('#prsSetAlwdRlsRow' + rndmNum + '_PayRoleID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsSetAlwdRlsRow' + rndmNum + '_RoleNm').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsSetAlwdRlsRow' + rndmNum + '_RoleID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Person Set',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Person Set...Please Wait...</p>',
        callback: function () {
            $('#myFormsModalNrml').modal('hide');
            getAllItemSets(actionText,slctr,linkArgs);
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
                    grp: 7,
                    typ: 1,
                    pg: 6,
                    q: 'UPDATE',
                    actyp: 1,
                    prsnSetNm: prsnSetNm,
                    prsnSetID: prsnSetID,
                    prsnSetDesc: prsnSetDesc,
                    prsnSetUsesSQL: prsnSetUsesSQL,
                    prsnSetEnbld: prsnSetEnbld,
                    prsnSetIsDflt: prsnSetIsDflt,
                    prsnSetSQL: prsnSetSQL,
                    slctdPrsnSetRoles: slctdPrsnSetRoles
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

function delRoleSet(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#prsSetAlwdRlsRow' + rndmNum + '_PayRoleID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#prsSetAlwdRlsRow' + rndmNum + '_PayRoleID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Role Set?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this linked role set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Role Set?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Role Set...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 6,
                                    q: 'DELETE',
                                    actyp: 3,
                                    payRoleID: pKeyID
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

function savePrsnSetPrsns(actionText,slctr,linkArgs) {
    var sbmtdPrsnSetHdrID = typeof $("#sbmtdPrsnSetHdrID").val() === 'undefined' ? '-1' : $("#sbmtdPrsnSetHdrID").val();
    var slctdPrsnSetPrsns = "";
    $('#prsSetPrsnsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#prsSetPrsnsRow' + rndmNum + '_PrsnLocID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    slctdPrsnSetPrsns = slctdPrsnSetPrsns + $('#prsSetPrsnsRow' + rndmNum + '_PrsnLocID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#prsSetPrsnsRow' + rndmNum + '_PrsnNm').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Persons',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Persons...Please Wait...</p>',
        callback: function () {
            getAllPrsSetPrsns(actionText,slctr,linkArgs);
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
                    grp: 7,
                    typ: 1,
                    pg: 6,
                    q: 'UPDATE',
                    actyp: 2,
                    sbmtdPrsnSetHdrID: sbmtdPrsnSetHdrID,
                    slctdPrsnSetPrsns: slctdPrsnSetPrsns
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

function delPrsnSetPrsn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var prsnLocID = '';
    if (typeof $('#prsSetPrsnsRow' + rndmNum + '_PrsnLocID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#prsSetPrsnsRow' + rndmNum + '_PrsnSetDetID').val();
        prsnLocID = $('#prsSetPrsnsRow' + rndmNum + '_PrsnLocID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Person?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Person from the Set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Person?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Person...Please Wait...</p>'
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 6,
                                    q: 'DELETE',
                                    actyp: 2,
                                    prsnLocID: prsnLocID,
                                    prsnSetDetID: pKeyID
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

function delPersonSet(rowIDAttrb,actionText,slctr,linkArgs) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#allPrsSetsRow' + rndmNum + '_PrsSetID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allPrsSetsRow' + rndmNum + '_PrsSetID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Person Set?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Person Set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Person Set?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Person Set...Please Wait...</p>',
                    callback: function (result) {
                        getAllItemSets(actionText,slctr,linkArgs);
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 6,
                                    q: 'DELETE',
                                    actyp: 1,
                                    prsSetID: pKeyID
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
                            dialog1.find('.bootbox-body').html('Person Set Removed Successfully!');
                        },500);
                    }
                });
            }
        }
    });
}

function getAllItemSets(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#allItemSetsSrchFor").val() === 'undefined' ? '%' : $("#allItemSetsSrchFor").val();
    var srchIn = typeof $("#allItemSetsSrchIn").val() === 'undefined' ? 'Both' : $("#allItemSetsSrchIn").val();
    var pageNo = typeof $("#allItemSetsPageNo").val() === 'undefined' ? 1 : $("#allItemSetsPageNo").val();
    var limitSze = typeof $("#allItemSetsDsplySze").val() === 'undefined' ? 10 : $("#allItemSetsDsplySze").val();
    var sortBy = typeof $("#allItemSetsSortBy").val() === 'undefined' ? '' : $("#allItemSetsSortBy").val();
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

function enterKeyFuncAllItemSets(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllItemSets(actionText,slctr,linkArgs);
    }
}

function getOneItemSetItms(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=5&vtyp=' + vwtype + '&sbmtdItemSetHdrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'allItemSetsDetailInfo','PasteDirect','','','',function () {
        var table1 = $('#prsSetPrsnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#prsSetPrsnsTable').wrap('<div class="dataTables_scroll"/>');
    });
}

function getAllItemSetItms(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#prsSetPrsnsSrchFor").val() === 'undefined' ? '%' : $("#prsSetPrsnsSrchFor").val();
    var srchIn = typeof $("#prsSetPrsnsSrchIn").val() === 'undefined' ? 'Both' : $("#prsSetPrsnsSrchIn").val();
    var pageNo = typeof $("#prsSetPrsnsPageNo").val() === 'undefined' ? 1 : $("#prsSetPrsnsPageNo").val();
    var limitSze = typeof $("#prsSetPrsnsDsplySze").val() === 'undefined' ? 10 : $("#prsSetPrsnsDsplySze").val();
    var sortBy = typeof $("#prsSetPrsnsSortBy").val() === 'undefined' ? '' : $("#prsSetPrsnsSortBy").val();
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

function enterKeyFuncItemSetItms(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllItemSetItms(actionText,slctr,linkArgs);
    }
}

function getOneItemSetForm(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=5&vtyp=' + vwtype + '&sbmtdItemSetHdrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalNrml','ShowDialog','Item Set Details (ID:' + pKeyID + ')','myFormsModalNrmlTitle','myFormsModalNrmlBody',function () {
        var table1 = $('#itemSetAllwdRolesTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#itemSetAllwdRolesTable').wrap('<div class="dataTables_scroll"/>');
    });
    $('#itemSetDetailsForm').submit(function (e) {
        e.preventDefault();
        return false;
    });
}

function saveItemSetForm(actionText,slctr,linkArgs) {
    var itemSetNm = typeof $("#itemSetNm").val() === 'undefined' ? '' : $("#itemSetNm").val();
    var itemSetID = typeof $("#itemSetID").val() === 'undefined' ? '-1' : $("#itemSetID").val();
    var itemSetDesc = typeof $("#itemSetDesc").val() === 'undefined' ? '' : $("#itemSetDesc").val();
    var itemSetUsesSQL = typeof $("input[name='itemSetUsesSQL']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var itemSetEnbld = typeof $("input[name='itemSetEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var itemSetIsDflt = typeof $("input[name='itemSetIsDflt']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var itemSetSQL = typeof $("#itemSetSQL").val() === 'undefined' ? '' : $("#itemSetSQL").val();

    if (itemSetNm === "" || itemSetNm === null) {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Item Set Name cannot be empty!</span></p>'
        });
        return false;
    }
    if (itemSetUsesSQL !== 'NO' && itemSetSQL === "") {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Item Set Query cannot be empty if this Set Uses SQL!</span></p>'
        });
        return false;
    }
    var slctdItemSetRoles = "";
    $('#itemSetAllwdRolesTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#itemSetAlwdRlsRow' + rndmNum + '_RoleID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    slctdItemSetRoles = slctdItemSetRoles + $('#itemSetAlwdRlsRow' + rndmNum + '_PayRoleID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#itemSetAlwdRlsRow' + rndmNum + '_RoleNm').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#itemSetAlwdRlsRow' + rndmNum + '_RoleID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Item Set',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Item Set...Please Wait...</p>',
        callback: function () {
            $('#myFormsModalNrml').modal('hide');
            getAllItemSets(actionText,slctr,linkArgs);
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
                    grp: 7,
                    typ: 1,
                    pg: 5,
                    q: 'UPDATE',
                    actyp: 1,
                    itemSetNm: itemSetNm,
                    itemSetID: itemSetID,
                    itemSetDesc: itemSetDesc,
                    itemSetUsesSQL: itemSetUsesSQL,
                    itemSetEnbld: itemSetEnbld,
                    itemSetIsDflt: itemSetIsDflt,
                    itemSetSQL: itemSetSQL,
                    slctdItemSetRoles: slctdItemSetRoles
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

function delItmSetRoleSet(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    if (typeof $('#itemSetAlwdRlsRow' + rndmNum + '_PayRoleID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#itemSetAlwdRlsRow' + rndmNum + '_PayRoleID').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Role Set?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this linked role set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Role Set?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Role Set...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 3,
                                    payRoleID: pKeyID
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

function saveItemSetItms() {
    var sbmtdItemSetHdrID = typeof $("#sbmtdItemSetHdrID").val() === 'undefined' ? '-1' : $("#sbmtdItemSetHdrID").val();
    var slctdItemSetItms = "";
    $('#itemSetItmsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#itemSetItmsRow' + rndmNum + '_ItemID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    slctdItemSetItms = slctdItemSetItms + $('#itemSetItmsRow' + rndmNum + '_ItemID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#itemSetItmsRow' + rndmNum + '_ItemName').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Items',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Items...Please Wait...</p>',
        callback: function () {
            getOneItemSetItms(sbmtdItemSetHdrID,1);
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
                    grp: 7,
                    typ: 1,
                    pg: 5,
                    q: 'UPDATE',
                    actyp: 2,
                    sbmtdItemSetHdrID: sbmtdItemSetHdrID,
                    slctdItemSetItms: slctdItemSetItms
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

function delItemSetItm(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var pItemName = '';
    if (typeof $('#itemSetItmsRow' + rndmNum + '_PKeyID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#itemSetItmsRow' + rndmNum + '_PKeyID').val();
        pItemName = $('#itemSetItmsRow' + rndmNum + '_ItemName').val();
    }
    var dialog = bootbox.confirm({
        title: 'Remove Item?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this Item from the Set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Remove Item?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Removing Item...Please Wait...</p>'
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pItemName: pItemName,
                                    pKeyID: pKeyID
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

function delItemSet(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#allItemSetsRow' + rndmNum + '_ItemSetID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#allItemSetsRow' + rndmNum + '_ItemSetID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item Set?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item Set?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item Set?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item Set...Please Wait...</p>',
                    callback: function (result) {
                        getAllItemSets('','#allmodules','grp=7&typ=1&pg=5&vtyp=0');
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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
                            dialog1.find('.bootbox-body').html('Person Set Removed Successfully!');
                        },500);
                    }
                });
            }
        }
    });
}

function getPayMassPy(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payMassPySrchFor").val() === 'undefined' ? '%' : $("#payMassPySrchFor").val();
    var srchIn = typeof $("#payMassPySrchIn").val() === 'undefined' ? 'Both' : $("#payMassPySrchIn").val();
    var pageNo = typeof $("#payMassPyPageNo").val() === 'undefined' ? 1 : $("#payMassPyPageNo").val();
    var limitSze = typeof $("#payMassPyDsplySze").val() === 'undefined' ? 10 : $("#payMassPyDsplySze").val();
    var sortBy = typeof $("#payMassPySortBy").val() === 'undefined' ? '' : $("#payMassPySortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayMassPy(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayMassPy(actionText,slctr,linkArgs);
    }
}

function getPayMassPyDt(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payMassPyDtSrchFor").val() === 'undefined' ? '%' : $("#payMassPyDtSrchFor").val();
    var srchIn = typeof $("#payMassPyDtSrchIn").val() === 'undefined' ? 'Both' : $("#payMassPyDtSrchIn").val();
    var pageNo1 = typeof $("#payMassPyDtPageNo1").val() === 'undefined' ? 1 : $("#payMassPyDtPageNo1").val();
    var pageNo2 = typeof $("#payMassPyDtPageNo2").val() === 'undefined' ? 1 : $("#payMassPyDtPageNo2").val();
    var pageNo3 = typeof $("#payMassPyDtPageNo3").val() === 'undefined' ? 1 : $("#payMassPyDtPageNo3").val();
    var payMassPyDtTabNo = typeof $("#payMassPyDtTabNo").val() === 'undefined' ? 1 : $("#payMassPyDtTabNo").val();
    payMassPyDtTabNo = parseInt(payMassPyDtTabNo);
    var limitSze = typeof $("#payMassPyDtDsplySze").val() === 'undefined' ? 10 : $("#payMassPyDtDsplySze").val();
    var sortBy = typeof $("#payMassPyDtSortBy").val() === 'undefined' ? '' : $("#payMassPyDtSortBy").val();
    var payMassPyID = typeof $("#payMassPyID").val() === 'undefined' ? -1 : $("#payMassPyID").val();
    var vtyp = 1;
    var slctr1 = '#payMassPyRunDets';
    if (payMassPyDtTabNo === 1) {
        vtyp = 101;
        slctr1 = '#payMassPyRunDets';
    } else if (payMassPyDtTabNo === 2) {
        vtyp = 102;
        slctr1 = '#payMassPyAttchdVals';
    } else if (payMassPyDtTabNo === 3) {
        vtyp = 103;
        slctr1 = '#payMassPyQckPayVals';
    }
    if (actionText === 'clear') {
        srchFor = "%";
        if (payMassPyDtTabNo === 1) {
            pageNo1 = 1;
        } else if (payMassPyDtTabNo === 2) {
            pageNo2 = 1;
        } else if (payMassPyDtTabNo === 3) {
            pageNo3 = 1;
        }
    } else if (actionText === 'next') {
        if (payMassPyDtTabNo === 1) {
            pageNo1 = parseInt(pageNo1) + 1;
        } else if (payMassPyDtTabNo === 2) {
            pageNo2 = parseInt(pageNo2) + 1;
        } else if (payMassPyDtTabNo === 3) {
            pageNo3 = parseInt(pageNo3) + 1;
        }
    } else if (actionText === 'previous') {
        if (payMassPyDtTabNo === 1) {
            pageNo1 = parseInt(pageNo1) - 1;
        } else if (payMassPyDtTabNo === 2) {
            pageNo2 = parseInt(pageNo2) - 1;
        } else if (payMassPyDtTabNo === 3) {
            pageNo3 = parseInt(pageNo3) - 1;
        }
    }
    $("#payMassPyDtPageNo1").val(pageNo1);
    $("#payMassPyDtPageNo2").val(pageNo2);
    $("#payMassPyDtPageNo3").val(pageNo3);
    $("#payMassPyDtSrchFor").val(srchFor);
    linkArgs = linkArgs + "&vtyp=" + vtyp + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo1=" + pageNo1 + "&pageNo2=" + pageNo2 + "&pageNo3=" + pageNo3 +
        "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&sbmtdMassPayRunID=" + payMassPyID;
    openATab(slctr1,linkArgs);
}

function enterKeyFuncPayMassPyDt(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayMassPyDt(actionText,slctr,linkArgs);
    }
}

function getOnePayMassPyForm(tmpltID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=7&vtyp=' + vwtype + '&sbmtdMassPayRunID=' + tmpltID;
    doAjaxWthCallBck(lnkArgs,'payMassPyDetailInfo','PasteDirect','','','',function () {
        if (!$.fn.DataTable.isDataTable('#payMassPyRunDetsTable')) {
            var table2 = $('#payMassPyRunDetsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payMassPyRunDetsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="tabajxpaymasspy"]').off('click');
        $('[data-toggle="tabajxpaymasspy"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=7&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('payMassPyAttchdVals') >= 0) {
                $('#payMassPyDtTabNo').val(2);
            } else if (targ.indexOf('payMassPyQckPayVals') >= 0) {
                $('#payMassPyDtTabNo').val(3);
            } else {
                $('#payMassPyDtTabNo').val(1);
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
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
    });
}

function getOnePayMassPyDiag(tmpltID,vwtype,isDiagForm,actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var qckPayPrsnSetID = typeof $("#qckPayPrsnSetID").val() === 'undefined' ? -1 : $("#qckPayPrsnSetID").val();
    var qckPayItmSetID = typeof $("#qckPayItmSetID").val() === 'undefined' ? -1 : $("#qckPayItmSetID").val();
    var qckPayPrsns_PrsnID = typeof $("#qckPayPrsns_PrsnID").val() === 'undefined' ? -1 : $("#qckPayPrsns_PrsnID").val();
    var lnkArgs = 'grp=7&typ=1&pg=7&vtyp=' + vwtype + '&sbmtdMassPayRunID=' + tmpltID + '&isDiagForm=' + isDiagForm +
        "&qckPayPrsnSetID=" + qckPayPrsnSetID + "&qckPayItmSetID=" + qckPayItmSetID + "&qckPayPrsns_PrsnID=" + qckPayPrsns_PrsnID;

    doAjaxWthCallBck(lnkArgs,'myFormsModalLg',actionTxt,'New Bill/Payment','myFormsModalTitleLg','myFormsModalBodyLg',function () {
        if (!$.fn.DataTable.isDataTable('#payMassPyRunDetsTable')) {
            var table2 = $('#payMassPyRunDetsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payMassPyRunDetsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="tabajxpaymasspy"]').off('click');
        $('[data-toggle="tabajxpaymasspy"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=7&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('payMassPyAttchdVals') >= 0) {
                $('#payMassPyDtTabNo').val(2);
            } else if (targ.indexOf('payMassPyQckPayVals') >= 0) {
                $('#payMassPyDtTabNo').val(3);
            } else {
                $('#payMassPyDtTabNo').val(1);
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
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal',function (e) {
            $('#myFormsModalTitleLg').html('');
            $('#myFormsModalBodyLg').html('');
            getQckPayPrsns('','#allmodules','grp=7&typ=1&pg=4&vtyp=0');
            $(e.currentTarget).unbind();
        });
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
    });
}

function payMassPyGrpTypChange() {
    var lovChkngElementVal = typeof $("#payMassPyGrpType").val() === 'undefined' ? '' : $("#payMassPyGrpType").val();
    lovNm = "";
    if (lovChkngElementVal === "Everyone") {
        $('#payMassPyGroupName').attr("disabled","true");
        $('#payMassPyGroupName').val("");
        $('#payMassPyGroupNameLbl').attr("disabled","true");
    } else {
        $('#payMassPyGroupName').removeAttr("disabled");
        $('#payMassPyGroupName').val("");
        $('#payMassPyGroupNameLbl').removeAttr("disabled");
    }
}

function savePayMassPyForm(funccrnm,actyp,rptID,alrtID,paramsStr,isDiagForm) {
    if (typeof funccrnm === 'undefined' || funccrnm === null) {
        funccrnm = 'GHS';
    }
    if (typeof actyp === 'undefined' || actyp === null) {
        actyp = 0;
    }
    if (typeof rptID === 'undefined' || rptID === null) {
        rptID = -1;
    }
    if (typeof alrtID === 'undefined' || alrtID === null) {
        alrtID = -1;
    }
    if (typeof paramsStr === 'undefined' || paramsStr === null) {
        paramsStr = '';
    }
    if (typeof isDiagForm === 'undefined' || isDiagForm === null) {
        isDiagForm = 'NO';
    }
    var payMassPyID = typeof $("#payMassPyID").val() === 'undefined' ? '-1' : $("#payMassPyID").val();
    if (actyp === 7) {
        getSilentRptsRnSts(rptID,alrtID,paramsStr,function () {
            if (Number(payMassPyID) > 0) {
                if (isDiagForm === 'NO') {
                    getOnePayMassPyForm(payMassPyID,1);
                } else {
                    getOnePayMassPyDiag(payMassPyID,1,isDiagForm,'ReloadDialog');
                }
            }
        });
    } else {
        var payMassPyName = typeof $("#payMassPyName").val() === 'undefined' ? '' : $("#payMassPyName").val();
        var payMassPyDesc = typeof $("#payMassPyDesc").val() === 'undefined' ? '' : $("#payMassPyDesc").val();
        var payMassPyPrsnStID = typeof $("#payMassPyPrsnStID").val() === 'undefined' ? '-1' : $("#payMassPyPrsnStID").val();
        var payMassPyPrsnStNm = typeof $("#payMassPyPrsnStNm").val() === 'undefined' ? '' : $("#payMassPyPrsnStNm").val();
        var payMassPyItmSetID = typeof $("#payMassPyItmSetID").val() === 'undefined' ? '-1' : $("#payMassPyItmSetID").val();
        var payMassPyItmSetNm = typeof $("#payMassPyItmSetNm").val() === 'undefined' ? '' : $("#payMassPyItmSetNm").val();
        var payMassPyDate = typeof $("#payMassPyDate").val() === 'undefined' ? '' : $("#payMassPyDate").val();
        var payMassPyGlDate = typeof $("#payMassPyGlDate").val() === 'undefined' ? '' : $("#payMassPyGlDate").val();
        var payMassPyIsQckPay = typeof $("input[name='payMassPyIsQckPay']:checked").val() === 'undefined' ? 'NO' : 'YES';
        var payMassPyAutoAsgng = typeof $("input[name='payMassPyAutoAsgng']:checked").val() === 'undefined' ? 'NO' : 'YES';
        var payMassPyAplyAdvnc = typeof $("input[name='payMassPyAplyAdvnc']:checked").val() === 'undefined' ? 'NO' : 'YES';
        var payMassPyKeepExcss = typeof $("input[name='payMassPyKeepExcss']:checked").val() === 'undefined' ? 'NO' : 'YES';
        var payMassPyGrpType = typeof $("#payMassPyGrpType").val() === 'undefined' ? '' : $("#payMassPyGrpType").val();
        var payMassPyGroupName = typeof $("#payMassPyGroupName").val() === 'undefined' ? '' : $("#payMassPyGroupName").val();
        var payMassPyGroupID = typeof $("#payMassPyGroupID").val() === 'undefined' ? '-1' : $("#payMassPyGroupID").val();
        var payMassPyWorkPlaceName = typeof $("#payMassPyWorkPlaceName").val() === 'undefined' ? '' : $("#payMassPyWorkPlaceName").val();
        var payMassPyWorkPlaceID = typeof $("#payMassPyWorkPlaceID").val() === 'undefined' ? '-1' : $("#payMassPyWorkPlaceID").val();
        var payMassPyWorkPlaceSiteName = typeof $("#payMassPyWorkPlaceSiteName").val() === 'undefined' ? '' : $("#payMassPyWorkPlaceSiteName").val();
        var payMassPyWorkPlaceSiteID = typeof $("#payMassPyWorkPlaceSiteID").val() === 'undefined' ? '-1' : $("#payMassPyWorkPlaceSiteID").val();

        var payMassPyAmntGvn = typeof $("#payMassPyAmntGvn").val() === 'undefined' ? '0' : $("#payMassPyAmntGvn").val();
        payMassPyAmntGvn = fmtAsNumber('payMassPyAmntGvn');
        var payMassPyChqNumber = typeof $("#payMassPyChqNumber").val() === 'undefined' ? '' : $("#payMassPyChqNumber").val();
        var payMassPySignCode = typeof $("#payMassPySignCode").val() === 'undefined' ? '' : $("#payMassPySignCode").val();
        var errMsg = "";
        if (payMassPyName.trim() === '' || payMassPyDesc.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Pay Run Name and Description cannot be empty!</span></p>';
        }
        if (payMassPyDate.trim() === '' || payMassPyGlDate.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Pay Date and GL Date cannot be empty!</span></p>';
        }
        if (payMassPyIsQckPay.trim() === 'NO' && Number(payMassPyPrsnStID.replace(/[^-?0-9\.]/g,'')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Person Set cannot be empty when Quick Pay is not Checked!</span></p>';
        }
        if (payMassPyItmSetNm.trim() === '' || Number(payMassPyItmSetID.replace(/[^-?0-9\.]/g,'')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Item Set cannot be empty!</span></p>';
        }

        var isVld = true;
        var slctdAttchdVals = "";
        $('#payMassPyAttchdValsTable').find('tr').each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr('id') === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr('id').split("_")[1];
                    var ln_TrnsLnID = $('#payMassPyAttchdValsRow' + rndmNum + '_TrnsLnID').val();
                    var ln_PrsnID = $('#payMassPyAttchdValsRow' + rndmNum + '_PrsnID').val();
                    var ln_ItemID = $('#payMassPyAttchdValsRow' + rndmNum + '_ItemID').val();
                    var ln_ItemValID = $('#payMassPyAttchdValsRow' + rndmNum + '_ItemValID').val();
                    var ln_ValToUse = $('#payMassPyAttchdValsRow' + rndmNum + '_ValToUse').val();
                    var ln_CanEdt = $('#payMassPyAttchdValsRow' + rndmNum + '_CanEdt').val();
                    if (Number(ln_PrsnID.replace(/[^-?0-9\.]/g,'')) > 0 && Number(ln_ItemID.replace(/[^-?0-9\.]/g,'')) > 0 && Number(ln_ItemValID.replace(/[^-?0-9\.]/g,'')) > 0) {
                        slctdAttchdVals = slctdAttchdVals +
                            ln_TrnsLnID + "~" +
                            ln_PrsnID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ItemID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ItemValID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ValToUse.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_CanEdt.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                    }
                }
            }
        });

        if (rhotrim(errMsg,'; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                /*size: 'small',*/
                message: errMsg
            });
            return false;
        }
        var dialog = bootbox.alert({
            title: 'Save Bulk Pay Run',
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Bulk Pay Run...Please Wait...</p>',
            callback: function () { }
        });
        var formData = new FormData();
        formData.append('grp',7);
        formData.append('typ',1);
        formData.append('pg',7);
        formData.append('q','UPDATE');
        formData.append('actyp',1);
        formData.append('payactyp',actyp);
        formData.append('payMassPyID',payMassPyID);
        formData.append('payMassPyName',payMassPyName);
        formData.append('payMassPyDesc',payMassPyDesc);
        formData.append('payMassPyPrsnStID',payMassPyPrsnStID);
        formData.append('payMassPyPrsnStNm',payMassPyPrsnStNm);
        formData.append('payMassPyItmSetID',payMassPyItmSetID);
        formData.append('payMassPyItmSetNm',payMassPyItmSetNm);

        formData.append('payMassPyDate',payMassPyDate);
        formData.append('payMassPyGlDate',payMassPyGlDate);
        formData.append('payMassPyIsQckPay',payMassPyIsQckPay);
        formData.append('payMassPyAutoAsgng',payMassPyAutoAsgng);
        formData.append('payMassPyAplyAdvnc',payMassPyAplyAdvnc);
        formData.append('payMassPyKeepExcss',payMassPyKeepExcss);
        formData.append('payMassPyGrpType',payMassPyGrpType);
        formData.append('payMassPyGroupName',payMassPyGroupName);
        formData.append('payMassPyGroupID',payMassPyGroupID);

        formData.append('payMassPyWorkPlaceName',payMassPyWorkPlaceName);
        formData.append('payMassPyWorkPlaceID',payMassPyWorkPlaceID);
        formData.append('payMassPyWorkPlaceSiteName',payMassPyWorkPlaceSiteName);
        formData.append('payMassPyWorkPlaceSiteID',payMassPyWorkPlaceSiteID);

        formData.append('payMassPyAmntGvn',payMassPyAmntGvn);
        formData.append('payMassPyChqNumber',payMassPyChqNumber);
        formData.append('payMassPySignCode',payMassPySignCode);

        formData.append('slctdAttchdVals',slctdAttchdVals);

        dialog.init(function () {
            getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                $body = $("body");
                $body.removeClass("mdlloading");
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        setTimeout(function () {
                            dialog.find('.bootbox-body').html(data.message);
                            if (data.message.indexOf("Success") !== -1) {
                                payMassPyID = data.payMassPyID;
                                if (actyp === 5) {
                                    dialog.modal('hide');
                                    if (isDiagForm === 'NO') {
                                        getPayMassPy('','#allmodules','grp=7&typ=1&pg=7&vtyp=0');
                                    } else {
                                        getOnePayMassPyDiag(payMassPyID,1,isDiagForm,'ReloadDialog');
                                    }
                                    getSilentRptsRnSts(rptID,alrtID,paramsStr,function () {
                                        if (Number(payMassPyID) > 0) {
                                            if (isDiagForm === 'NO') {
                                                getOnePayMassPyForm(payMassPyID,1);
                                            } else {
                                                getOnePayMassPyDiag(payMassPyID,1,isDiagForm,'ReloadDialog');
                                            }
                                        }
                                    });
                                } else {
                                    if (isDiagForm === 'NO') {
                                        getPayMassPy('','#allmodules','grp=7&typ=1&pg=7&vtyp=0');
                                    } else {
                                        getOnePayMassPyDiag(payMassPyID,1,isDiagForm,'ReloadDialog');
                                    }
                                }
                            }
                        },1000);
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

function delPayMassPy(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CodeID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CodeID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CodeNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Pay Run?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Pay Run?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Bulk Pay Run?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Bulk Pay Run...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 7,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                        getPayMassPy('','#allmodules','grp=7&typ=1&pg=7&vtyp=0');
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

function delPayMassPyAttchdVal(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = "MassPyAttchdVal";
    }
    var dialog = bootbox.confirm({
        title: 'Delete Attached Value?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Attached Value?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Attached Value?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Attached Value...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 7,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function shwHideQuckPayDivs() {
    var whtToDo = typeof $("input[name='payMassPyIsQckPay']:checked").val() === 'undefined' ? 'hide' : 'show';
    if (whtToDo === 'hide') {
        $('#payMassPyIsQckPayDiv').addClass('hideNotice');
    } else {
        $('#payMassPyIsQckPayDiv').removeClass('hideNotice');
    }
}

function getAllPayRnTrns(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#allPayRnTrnsSrchFor").val() === 'undefined' ? '%' : $("#allPayRnTrnsSrchFor").val();
    var srchIn = typeof $("#allPayRnTrnsSrchIn").val() === 'undefined' ? 'Both' : $("#allPayRnTrnsSrchIn").val();
    var pageNo = typeof $("#allPayRnTrnsPageNo").val() === 'undefined' ? 1 : $("#allPayRnTrnsPageNo").val();
    var limitSze = typeof $("#allPayRnTrnsDsplySze").val() === 'undefined' ? 10 : $("#allPayRnTrnsDsplySze").val();
    var sortBy = typeof $("#allPayRnTrnsSortBy").val() === 'undefined' ? '' : $("#allPayRnTrnsSortBy").val();
    var qStrtDte = typeof $("#allPayRnTrnsStrtDate").val() === 'undefined' ? '' : $("#allPayRnTrnsStrtDate").val();
    var qEndDte = typeof $("#allPayRnTrnsEndDate").val() === 'undefined' ? '' : $("#allPayRnTrnsEndDate").val();
    var qUnathrzdOnly = $('#allPayRnTrnsShwUnaprvd:checked').length > 0;
    var qInvalidOnly = $('#allPayRnTrnsShwInvld:checked').length > 0;
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
        "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte +
        "&qUnathrzdOnly=" + qUnathrzdOnly + "&qInvalidOnly=" + qInvalidOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAllPayRnTrns(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPayRnTrns(actionText,slctr,linkArgs);
    }
}

function getAllPayGLIntrfcs(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#allPayGLIntrfcsSrchFor").val() === 'undefined' ? '%' : $("#allPayGLIntrfcsSrchFor").val();
    var srchIn = typeof $("#allPayGLIntrfcsSrchIn").val() === 'undefined' ? 'Both' : $("#allPayGLIntrfcsSrchIn").val();
    var pageNo = typeof $("#allPayGLIntrfcsPageNo").val() === 'undefined' ? 1 : $("#allPayGLIntrfcsPageNo").val();
    var limitSze = typeof $("#allPayGLIntrfcsDsplySze").val() === 'undefined' ? 10 : $("#allPayGLIntrfcsDsplySze").val();
    var sortBy = typeof $("#allPayGLIntrfcsSortBy").val() === 'undefined' ? '' : $("#allPayGLIntrfcsSortBy").val();
    var qStrtDte = typeof $("#allPayGLIntrfcsStrtDate").val() === 'undefined' ? '' : $("#allPayGLIntrfcsStrtDate").val();
    var qEndDte = typeof $("#allPayGLIntrfcsEndDate").val() === 'undefined' ? '' : $("#allPayGLIntrfcsEndDate").val();
    var qNotSentToGl = $('#allPayGLIntrfcsSntToGl:checked').length > 0;
    var qUnbalncdOnly = $('#allPayGLIntrfcsUnbalncd:checked').length > 0;
    var qUsrGnrtd = $('#allPayGLIntrfcsUsrTrns:checked').length > 0;
    var qLowVal = typeof $("#allPayGLIntrfcsLowVal").val() === 'undefined' ? 0 : $("#allPayGLIntrfcsLowVal").val();
    var qHighVal = typeof $("#allPayGLIntrfcsHighVal").val() === 'undefined' ? 0 : $("#allPayGLIntrfcsHighVal").val();
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
        "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte +
        "&qNotSentToGl=" + qNotSentToGl + "&qUnbalncdOnly=" + qUnbalncdOnly +
        "&qUsrGnrtd=" + qUsrGnrtd + "&qLowVal=" + qLowVal + "&qHighVal=" + qHighVal;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAllPayGLIntrfcs(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllPayGLIntrfcs(actionText,slctr,linkArgs);
    }
}

function getOnePAYGLIntrfcForm(pKeyID,pRowIDAttrb) {
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
    var rowIDAttrb = "";
    $('#allPayGLIntrfcsHdrsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var lnIntrfcID = typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val();
                    var isTcked = (typeof $("input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked").val() === 'undefined' ? 'NO' : 'YES');
                    if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g,'')) > 0 && isTcked === "YES") {
                        slctdCnt = slctdCnt + 1;
                        rowIDAttrb = $(el).attr('id');
                    }
                }
            }
        }
    });
    if (slctdCnt == 1 || pKeyID > 0) {
        if (pKeyID > 0) {
            rowIDAttrb = pRowIDAttrb;
        }
        var $tds = $('#' + rowIDAttrb).find('td');
        var trnsDesc = $.trim($tds.eq(3).text());
        var trnsDate = $.trim($tds.eq(7).text());
        var dbtAmnt = $.trim($tds.eq(5).text());
        var crdtAmnt = $.trim($tds.eq(6).text());
        var trnsCur = $.trim($tds.eq(4).text());
        var trnsAmnt = Math.abs(Number(dbtAmnt.replace(/[^-?0-9\.]/g,'')) - Number(crdtAmnt.replace(/[^-?0-9\.]/g,''))).toFixed(2);
        var lnkArgs = 'grp=7&typ=1&pg=9&vtyp=1&sbmtdIntrfcID=' + pKeyID;
        doAjaxWthCallBck(lnkArgs,'myFormsModalp','ShowDialog','GL Interface Line (ID:' + pKeyID + ')','myFormsModalpTitle','myFormsModalpBody',function () {
            $('#addGLIntrfcsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allOtherInputData99').val(0);
            $('#glIntrfcTrnsDesc').val(trnsDesc);
            $('#glIntrfcTrnsDate').val(trnsDate);
            $('#enteredAmount').val(trnsAmnt);
            $('#enteredCrncyNm').val(trnsCur);
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
            $('#myFormsModalx').off('hidden.bs.modal');
            $('#myFormsModalx').one('hidden.bs.modal',function (e) {
                $('#myFormsModalxTitle').html('');
                $('#myFormsModalxBody').html('');
                getAllPayGLIntrfcs('','#allmodules','grp=7&typ=1&pg=9&vtyp=0');
                $(e.currentTarget).unbind();
            });
        });
    } else {
        var errMsg = '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please first select ONE unbalanced transaction!</span></p>';
        var dialog1 = bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
    }
}

function afterPAYIntrfcItemSlctn() {
    fmtAsNumber('enteredAmount');
    $('#funcCrncyRate').val(1);
    $('#accntCrncyRate').val(1);
    var enteredCrncyNm = $('#enteredCrncyNm').val();
    var enteredAmount = Number($('#enteredAmount').val().replace(/[^-?0-9\.]/g,''));
    var intrfcAccntID = $('#intrfcAccntID').val();
    var glIntrfcTrnsDate = typeof $("#glIntrfcTrnsDate").val() === 'undefined' ? '' : $("#glIntrfcTrnsDate").val();
    var funcCrncyRate = typeof $("#funcCrncyRate").val() === 'undefined' ? '0' : $("#funcCrncyRate").val();
    var accntCrncyRate = typeof $("#accntCrncyRate").val() === 'undefined' ? '0' : $("#accntCrncyRate").val();
    if (Number(intrfcAccntID) > 0) {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp',7);
            formData.append('typ',1);
            formData.append('pg',9);
            formData.append('q','VIEW');
            formData.append('vtyp',2);
            formData.append('intrfcAccntID',intrfcAccntID);
            formData.append('enteredCrncyNm',enteredCrncyNm);
            formData.append('enteredAmount',enteredAmount);
            formData.append('glIntrfcTrnsDate',glIntrfcTrnsDate);
            formData.append('funcCrncyRate',funcCrncyRate);
            formData.append('accntCrncyRate',accntCrncyRate);
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
                        $('#funcCrncyRate').val(data.FuncCrncyRate);
                        $('#accntCrncyRate').val(data.AccntCrncyRate);
                        $('#funcCrncyNm').html(data.FuncCrncyNm);
                        $('#funcCrncyAmount').val(data.FuncCrncyAmount);
                        $('#accntCrncyNm').html(data.AccntCrncyNm);
                        $('#accntCrncyAmount').val(data.AccntCrncyAmount);
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    } else {
        $('#funcCrncyRate').val(1);
        $('#accntCrncyRate').val(1);
        $('#funcCrncyNm').val('');
        $('#funcCrncyAmount').val(0.00);
        $('#accntCrncyNm').val('');
        $('#accntCrncyAmount').val(0.00);
    }
}

function savePAYGLIntrfcForm() {
    afterPAYIntrfcItemSlctn();
    setTimeout(function () {
        var glIntrfcTrnsDate = typeof $("#glIntrfcTrnsDate").val() === 'undefined' ? '' : $("#glIntrfcTrnsDate").val();
        var glIntrfcTrnsID = typeof $("#glIntrfcTrnsID").val() === 'undefined' ? '-1' : $("#glIntrfcTrnsID").val();
        var glIntrfcTrnsDesc = typeof $("#glIntrfcTrnsDesc").val() === 'undefined' ? '' : $("#glIntrfcTrnsDesc").val();
        var intrfcAccntID = typeof $("#intrfcAccntID").val() === 'undefined' ? '-1' : $("#intrfcAccntID").val();
        var incrsDcrs = typeof $("#incrsDcrs").val() === 'undefined' ? '' : $("#incrsDcrs").val();
        var enteredCrncyNm = typeof $("#enteredCrncyNm").val() === 'undefined' ? '' : $("#enteredCrncyNm").val();
        var enteredAmount = typeof $("#enteredAmount").val() === 'undefined' ? '0' : $("#enteredAmount").val();
        var funcCrncyRate = typeof $("#funcCrncyRate").val() === 'undefined' ? '0' : $("#funcCrncyRate").val();
        var accntCrncyRate = typeof $("#accntCrncyRate").val() === 'undefined' ? '0' : $("#accntCrncyRate").val();
        var funcCrncyAmount = typeof $("#funcCrncyAmount").val() === 'undefined' ? '0' : $("#funcCrncyAmount").val();
        var accntCrncyAmount = typeof $("#accntCrncyAmount").val() === 'undefined' ? '0' : $("#accntCrncyAmount").val();
        var errMsg = "";
        if (glIntrfcTrnsDate.trim() === "") {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Date cannot be empty!</span></p>';
        }
        if (glIntrfcTrnsDesc.trim() === "") {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Transaction Description cannot be empty!</span></p>';
        }
        if (enteredCrncyNm.trim() === "") {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Entered Currency cannot be empty!</span></p>';
        }
        if (incrsDcrs.trim() === "") {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Action cannot be empty!</span></p>';
        }
        if (Number(intrfcAccntID.replace(/[^-?0-9\.]/g,'')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">GL Account cannot be empty!</span></p>';
        }
        if (Number(enteredAmount.replace(/[^-?0-9\.]/g,'')) == 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Entered Amount cannot be Zero!</span></p>';
        }
        if (Number(funcCrncyRate.replace(/[^-?0-9\.]/g,'')) == 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Functional Currency Rate cannot be empty!</span></p>';
        }
        if (Number(accntCrncyRate.replace(/[^-?0-9\.]/g,'')) == 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Account Currency Rate cannot be empty!</span></p>';
        }
        if (Number(funcCrncyAmount.replace(/[^-?0-9\.]/g,'')) == 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Functional Currency Amount cannot be empty!</span></p>';
        }
        if (Number(accntCrncyAmount.replace(/[^-?0-9\.]/g,'')) == 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Account Currency Rate cannot be empty!</span></p>';
        }
        if (rhotrim(errMsg,'; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                /*size: 'small',*/
                message: errMsg
            });
            return false;
        }
        var shdClose = 0;
        var dialog = bootbox.alert({
            title: 'Save Transaction',
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction...Please Wait...</p>',
            callback: function () {
                if (shdClose > 0) {
                    $('#myFormsModalx').modal('hide');
                }
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
                        grp: 7,
                        typ: 1,
                        pg: 9,
                        q: 'UPDATE',
                        actyp: 1,
                        glIntrfcTrnsDate: glIntrfcTrnsDate,
                        glIntrfcTrnsID: glIntrfcTrnsID,
                        glIntrfcTrnsDesc: glIntrfcTrnsDesc,
                        intrfcAccntID: intrfcAccntID,
                        incrsDcrs: incrsDcrs,
                        enteredCrncyNm: enteredCrncyNm,
                        enteredAmount: enteredAmount.replace(/[^-?0-9\.]/g,''),
                        funcCrncyRate: funcCrncyRate.replace(/[^-?0-9\.]/g,''),
                        accntCrncyRate: accntCrncyRate.replace(/[^-?0-9\.]/g,''),
                        funcCrncyAmount: funcCrncyAmount.replace(/[^-?0-9\.]/g,''),
                        accntCrncyAmount: accntCrncyAmount.replace(/[^-?0-9\.]/g,'')
                    },
                    success: function (result) {
                        dialog.find('.bootbox-body').html(result.message);
                        if (result.message.indexOf("Success") !== -1) {
                            shdClose = 1;
                        }
                    },
                    error: function (jqXHR,textStatus,errorThrown) {
                        /*dialog.find('.bootbox-body').html(errorThrown);*/
                        console.warn(jqXHR.responseText);
                    }
                });
            });
        });
    },500);
}

function delSlctdPAYIntrfcLines() {
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
    $('#allPayGLIntrfcsHdrsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var lnIntrfcID = typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val();
                    var isTcked = (typeof $("input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked").val() === 'undefined' ? 'NO' : 'YES');
                    if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g,'')) > 0 && isTcked === "YES") {
                        slctdCnt = slctdCnt + 1;
                    }
                }
            }
        }
    });
    if (slctdCnt > 0) {
        var dialog = bootbox.confirm({
            title: 'Delete Selected User Transactions?',
            size: 'small',
            message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> the ' + slctdCnt + ' selected User Transactions(s)?<br/>Action cannot be Undone!</p>',
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
                $('#allPayGLIntrfcsHdrsTable').find('tr').each(function (i,el) {
                    if (i > 0) {
                        if (typeof $(el).attr('id') === 'undefined') {
                            /*Do Nothing*/
                        } else {
                            var rndmNum = $(el).attr('id').split("_")[1];
                            var rowPrfxNm = $(el).attr('id').split("_")[0];
                            if (typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined') {
                                /*Do Nothing*/
                            } else {
                                var lnIntrfcID = typeof $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_IntrfcID').val();
                                var rowIDAttrb = $(el).attr('id');
                                var $tds = $('#' + rowIDAttrb).find('td');
                                var intrfcIDDesc = $.trim($tds.eq(4).text());
                                var isTcked = (typeof $("input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked").val() === 'undefined' ? 'NO' : 'YES');
                                if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g,'')) > 0 && isTcked === "YES") {
                                    slctdIntrfcIDs = slctdIntrfcIDs + lnIntrfcID + "~" +
                                        intrfcIDDesc.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                                }
                                $("#" + rowPrfxNm + rndmNum).remove();
                            }
                        }
                    }
                });
                var result2 = "";
                if (result === true) {
                    var dialog1 = bootbox.alert({
                        title: 'Delete Selected User Transactions?',
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Selected User Transactions...Please Wait...</p>',
                        callback: function () {
                            $("body").css("padding-right","0px");
                            if (result2.indexOf("Success") !== -1) {
                                getAllPayGLIntrfcs('','#allmodules','grp=7&typ=1&pg=9&vtyp=0');
                            }
                        }
                    });
                    dialog1.init(function () {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 7,
                                    typ: 1,
                                    pg: 9,
                                    q: 'DELETE',
                                    actyp: 1,
                                    slctdIntrfcIDs: slctdIntrfcIDs
                                },
                                success: function (result1) {
                                    result2 = result1;
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    });
                }
            }
        });
    } else {
        var errMsg = '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please first select transactions to Void/Delete!</span></p>';
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
    }
}

function testPAYETax() {

    var allPayeRatesUnitPrc = typeof $("#allPayeRatesUnitPrc").val() === 'undefined' ? '0' : $("#allPayeRatesUnitPrc").val();
    allPayeRatesUnitPrc = fmtAsNumber('allPayeRatesUnitPrc');
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp',7);
        formData.append('typ',1);
        formData.append('pg',12);
        formData.append('q','VIEW');
        formData.append('vtyp',1);
        formData.append('allPayeRatesUnitPrc',allPayeRatesUnitPrc);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#allPayeRatesSQLTestRslts').html(data.message);
            },
            error: function (jqXHR,textStatus,errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function insertNewTaxRateRows(tableElmntID,position,inptHtml) {
    $("#allOtherInputData5").val(0);
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g,nwRndm + "_").replace(/(_WWW123WWW)+/g,nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID).append(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }

    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                cntr++;
                var prfxName1 = $(el).attr('id').split("_")[0];
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
                //alert($tds.eq(0).text()+cntr);
                $tds.eq(0).html(cntr);
                //alert($tds.eq(0).text()+cntr);
            }
        }
    });
}

function getPayGlblVals(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payGlblValsSrchFor").val() === 'undefined' ? '%' : $("#payGlblValsSrchFor").val();
    var srchIn = typeof $("#payGlblValsSrchIn").val() === 'undefined' ? 'Both' : $("#payGlblValsSrchIn").val();
    var pageNo = typeof $("#payGlblValsPageNo").val() === 'undefined' ? 1 : $("#payGlblValsPageNo").val();
    var limitSze = typeof $("#payGlblValsDsplySze").val() === 'undefined' ? 10 : $("#payGlblValsDsplySze").val();
    var sortBy = typeof $("#payGlblValsSortBy").val() === 'undefined' ? '' : $("#payGlblValsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayGlblVals(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayGlblVals(actionText,slctr,linkArgs);
    }
}

function getPayGlblValsDt(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payGlblValsDtSrchFor").val() === 'undefined' ? '%' : $("#payGlblValsDtSrchFor").val();
    var srchIn = typeof $("#payGlblValsDtSrchIn").val() === 'undefined' ? 'Both' : $("#payGlblValsDtSrchIn").val();
    var pageNo = typeof $("#payGlblValsDtPageNo").val() === 'undefined' ? 1 : $("#payGlblValsDtPageNo").val();
    var limitSze = typeof $("#payGlblValsDtDsplySze").val() === 'undefined' ? 10 : $("#payGlblValsDtDsplySze").val();
    var sortBy = typeof $("#payGlblValsDtSortBy").val() === 'undefined' ? '' : $("#payGlblValsDtSortBy").val();
    var payGlblValsID = typeof $("#payGlblValsID").val() === 'undefined' ? -1 : $("#payGlblValsID").val();
    var vtyp = 101;
    var slctr1 = '#payGlblValsRunDets';
    if (actionText === 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText === 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText === 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&vtyp=" + vtyp + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo +
        "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&sbmtdGBVID=" + payGlblValsID;
    openATab(slctr1,linkArgs);
}

function enterKeyFuncPayGlblValsDt(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayGlblValsDt(actionText,slctr,linkArgs);
    }
}

function getOnePayGlblValsForm(tmpltID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=11&vtyp=' + vwtype + '&sbmtdGBVID=' + tmpltID;
    doAjaxWthCallBck(lnkArgs,'payGlblValsDetailInfo','PasteDirect','','','',function () {
        if (!$.fn.DataTable.isDataTable('#payGlblValsRunDetsTable')) {
            var table2 = $('#payGlblValsRunDetsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payGlblValsRunDetsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();

        $(".jbDetDbt").focus(function () {
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

function insertNewPayGlblValsRows(tableElmntID,position,inptHtml) {
    var payGlblValsCritType = typeof $("#payGlblValsCritType option:selected").text() === 'undefined' ? '' : $("#payGlblValsCritType option:selected").text();
    for (var i = 0; i < 5; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g,nwRndm + "_").replace(/(_WWW123WWW)+/g,nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(position).before(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
        $("#payGlblValsRunDetsRow" + nwRndm + "_CrtriaType").val(payGlblValsCritType);
    }

    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetDbt").focus(function () {
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
    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                cntr++;
                var prfxName1 = $(el).attr('id').split("_")[0];
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
                //alert($tds.eq(0).text()+cntr);
                $tds.eq(0).html(cntr);
                //alert($tds.eq(0).text()+cntr);
            }
        }
    });

}

function payGlblValsGrpChange(grpNameElmntID,grpIDElmntID) {
    $("#" + grpNameElmntID).val("");
    $("#" + grpIDElmntID).val("-1");
}

function savePayGlblValsForm() {
    var payGlblValsID = typeof $("#payGlblValsID").val() === 'undefined' ? '-1' : $("#payGlblValsID").val();
    var payGlblValsName = typeof $("#payGlblValsName").val() === 'undefined' ? '' : $("#payGlblValsName").val();
    var payGlblValsIsEnbld = typeof $("input[name='payGlblValsIsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var payGlblValsDesc = typeof $("#payGlblValsDesc").val() === 'undefined' ? '' : $("#payGlblValsDesc").val();
    var payGlblValsCritType = typeof $("#payGlblValsCritType").val() === 'undefined' ? '' : $("#payGlblValsCritType").val();
    var errMsg = "";
    if (payGlblValsName.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Global Value Name cannot be empty!</span></p>';
    }
    if (payGlblValsCritType.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Criteria Type cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }

    var errMsg = "";
    var isVld = true;
    var slctdGlobalVals = "";
    $('#payGlblValsRunDetsTable').find('tr').each(function (i,el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#payGlblValsRunDetsRow' + rndmNum + '_TrnsLnID').val();
                var ln_CrtriaType = $('#payGlblValsRunDetsRow' + rndmNum + '_CrtriaType').val();
                var ln_CrtriaID = $('#payGlblValsRunDetsRow' + rndmNum + '_CrtriaID').val();
                var ln_CrtriaNm = $('#payGlblValsRunDetsRow' + rndmNum + '_CrtriaNm').val();
                var ln_GBVAmnt = $('#payGlblValsRunDetsRow' + rndmNum + '_GBVAmnt').val();
                var ln_StrtDte = $('#payGlblValsRunDetsRow' + rndmNum + '_StrtDte').val();
                var ln_EndDte = $('#payGlblValsRunDetsRow' + rndmNum + '_EndDte').val();
                //Number(ln_Level.replace(/[^-?0-9\.]/g, ''))
                if (ln_GBVAmnt.replace(/[^-?0-9\.]/g,'').trim() === "") {
                    isVld = false;
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                        'font-weight:bold;color:red;">Amount for Row No. ' + i + ' cannot be empty!</span></p>';
                    $('#payGlblValsRunDetsRow' + rndmNum + '_GBVAmnt').addClass('rho-error');
                } else {
                    $('#payGlblValsRunDetsRow' + rndmNum + '_GBVAmnt').removeClass('rho-error');
                }
                if (ln_CrtriaID.replace(/[^-?0-9\.]/g,'').trim() === "" || ln_CrtriaType.trim() === "") {
                    isVld = false;
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                        'font-weight:bold;color:red;">Criteria Type and Name for Row No. ' + i + ' cannot be empty!</span></p>';
                    $('#payGlblValsRunDetsRow' + rndmNum + '_CrtriaNm').addClass('rho-error');
                    $('#payGlblValsRunDetsRow' + rndmNum + '_CrtriaType').addClass('rho-error');
                } else {
                    $('#payGlblValsRunDetsRow' + rndmNum + '_CrtriaNm').removeClass('rho-error');
                    $('#payGlblValsRunDetsRow' + rndmNum + '_CrtriaType').removeClass('rho-error');
                }
                if (isVld === true) {
                    slctdGlobalVals = slctdGlobalVals +
                        ln_TrnsLnID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_CrtriaType.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_CrtriaID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_CrtriaNm.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_GBVAmnt.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_StrtDte.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_EndDte.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    var dialog = bootbox.alert({
        title: 'Save Global Values',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Global Values...Please Wait...</p>',
        callback: function () { }
    });
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 7,
                    typ: 1,
                    pg: 11,
                    q: 'UPDATE',
                    actyp: 1,
                    payGlblValsID: payGlblValsID,
                    payGlblValsName: payGlblValsName,
                    payGlblValsIsEnbld: payGlblValsIsEnbld,
                    payGlblValsDesc: payGlblValsDesc,
                    payGlblValsCritType: payGlblValsCritType,
                    slctdGlobalVals: slctdGlobalVals
                },
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        getPayGlblValsDt('','#allmodules','grp=7&typ=1&pg=11');
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

function delPayGlblVals(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_GBVID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_GBVID').val();
        pKeyNm = $('#payGlblValsName').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Global Value";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 11,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function delPayGlblValsLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#payGlblValsName').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Global Value Line";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 11,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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


function getPayPayItms(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payPayItmsSrchFor").val() === 'undefined' ? '%' : $("#payPayItmsSrchFor").val();
    var srchIn = typeof $("#payPayItmsSrchIn").val() === 'undefined' ? 'Both' : $("#payPayItmsSrchIn").val();
    var pageNo = typeof $("#payPayItmsPageNo").val() === 'undefined' ? 1 : $("#payPayItmsPageNo").val();
    var limitSze = typeof $("#payPayItmsDsplySze").val() === 'undefined' ? 10 : $("#payPayItmsDsplySze").val();
    var sortBy = typeof $("#payPayItmsSortBy").val() === 'undefined' ? '' : $("#payPayItmsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayPayItms(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayPayItms(actionText,slctr,linkArgs);
    }
}

function getPayPayItmsDt(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payPayItmsDtSrchFor").val() === 'undefined' ? '%' : $("#payPayItmsDtSrchFor").val();
    var srchIn = typeof $("#payPayItmsDtSrchIn").val() === 'undefined' ? 'Both' : $("#payPayItmsDtSrchIn").val();
    var pageNo1 = typeof $("#payPayItmsDtPageNo1").val() === 'undefined' ? 1 : $("#payPayItmsDtPageNo1").val();
    var pageNo2 = typeof $("#payPayItmsDtPageNo2").val() === 'undefined' ? 1 : $("#payPayItmsDtPageNo2").val();
    var pageNo3 = typeof $("#payPayItmsDtPageNo3").val() === 'undefined' ? 1 : $("#payPayItmsDtPageNo3").val();
    var payPayItmsDtTabNo = typeof $("#payPayItmsDtTabNo").val() === 'undefined' ? 1 : $("#payPayItmsDtTabNo").val();
    payPayItmsDtTabNo = parseInt(payPayItmsDtTabNo);
    var limitSze = typeof $("#payPayItmsDtDsplySze").val() === 'undefined' ? 10 : $("#payPayItmsDtDsplySze").val();
    var sortBy = typeof $("#payPayItmsDtSortBy").val() === 'undefined' ? '' : $("#payPayItmsDtSortBy").val();
    var payPayItmsID = typeof $("#payPayItmsID").val() === 'undefined' ? -1 : $("#payPayItmsID").val();
    var subPgNo = 1;
    var slctr1 = '#payPayItmsBalsFeeds';
    if (payPayItmsDtTabNo === 1) {
        subPgNo = 101;
        slctr1 = '#payPayItmsBalsFeeds';
    } else if (payPayItmsDtTabNo === 2) {
        subPgNo = 102;
        slctr1 = '#payPayItmsPsblVals';
    } else if (payPayItmsDtTabNo === 3) {
        subPgNo = 103;
        slctr1 = '#payPayItmsExtraInfo';
    }
    if (actionText === 'clear') {
        srchFor = "%";
        if (payPayItmsDtTabNo === 1) {
            pageNo1 = 1;
        } else if (payPayItmsDtTabNo === 2) {
            pageNo2 = 1;
        } else if (payPayItmsDtTabNo === 3) {
            pageNo3 = 1;
        }
    } else if (actionText === 'next') {
        if (payPayItmsDtTabNo === 1) {
            pageNo1 = parseInt(pageNo1) + 1;
        } else if (payPayItmsDtTabNo === 2) {
            pageNo2 = parseInt(pageNo2) + 1;
        } else if (payPayItmsDtTabNo === 3) {
            pageNo3 = parseInt(pageNo3) + 1;
        }
    } else if (actionText === 'previous') {
        if (payPayItmsDtTabNo === 1) {
            pageNo1 = parseInt(pageNo1) - 1;
        } else if (payPayItmsDtTabNo === 2) {
            pageNo2 = parseInt(pageNo2) - 1;
        } else if (payPayItmsDtTabNo === 3) {
            pageNo3 = parseInt(pageNo3) - 1;
        }
    }
    $("#payPayItmsDtPageNo1").val(pageNo1);
    $("#payPayItmsDtPageNo2").val(pageNo2);
    $("#payPayItmsDtPageNo3").val(pageNo3);
    $("#payPayItmsDtSrchFor").val(srchFor);
    linkArgs = linkArgs + "&vtyp=0&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo1=" + pageNo1 + "&pageNo2=" + pageNo2 + "&pageNo3=" + pageNo3 +
        "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&sbmtdPayItmID=" + payPayItmsID + "&subPgNo=" + subPgNo;
    openATab(slctr1,linkArgs);
}

function enterKeyFuncPayPayItmsDt(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayPayItmsDt(actionText,slctr,linkArgs);
    }
}

function getOnePayPayItmsForm(tmpltID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=10&vtyp=' + vwtype + '&sbmtdPayItmID=' + tmpltID + "&subPgNo=1";
    doAjaxWthCallBck(lnkArgs,'payPayItmsDetailInfo','PasteDirect','','','',function () {

        if (!$.fn.DataTable.isDataTable('#payPayItmsBalsFeedsTable')) {
            var table2 = $('#payPayItmsBalsFeedsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payPayItmsBalsFeedsTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#payPayItmsPsblValsTable')) {
            var table2 = $('#payPayItmsPsblValsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payPayItmsPsblValsTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#payPayItmsExtrInfTable')) {
            var table2 = $('#payPayItmsExtrInfTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payPayItmsExtrInfTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();

        $('[data-toggle="tabajxpaypayitms"]').off('click');
        $('[data-toggle="tabajxpaypayitms"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=7&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('payPayItmsPsblVals') >= 0) {
                $('#addNewPayItmsBtn').addClass('hideNotice');
                $('#addNewPayItmsAttchdValBtn').removeClass('hideNotice');
                $('#payPayItmsDtTabNo').val(2);
            } else if (targ.indexOf('payPayItmsExtraInfo') >= 0) {
                $('#payPayItmsDtTabNo').val(3);
                $('#addNewPayItmsBtn').addClass('hideNotice');
                $('#addNewPayItmsAttchdValBtn').addClass('hideNotice');
            } else {
                $('#addNewPayItmsBtn').removeClass('hideNotice');
                $('#addNewPayItmsAttchdValBtn').addClass('hideNotice');
                $('#payPayItmsDtTabNo').val(1);
            }
        });

        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
    });
}

function payPayItmsGrpTypChange() {
    var lovChkngElementVal = typeof $("#payPayItmsGrpType").val() === 'undefined' ? '' : $("#payPayItmsGrpType").val();
    lovNm = "";
    if (lovChkngElementVal === "Everyone") {
        $('#payPayItmsGroupName').attr("disabled","true");
        $('#payPayItmsGroupName').val("");
        $('#payPayItmsGroupNameLbl').attr("disabled","true");
    } else {
        $('#payPayItmsGroupName').removeAttr("disabled");
        $('#payPayItmsGroupName').val("");
        $('#payPayItmsGroupNameLbl').removeAttr("disabled");
    }
}

function savePayPayItmsForm() {
    var payPayItmsID = typeof $("#payPayItmsID").val() === 'undefined' ? '-1' : $("#payPayItmsID").val();
    var payPayItmsName = typeof $("#payPayItmsName").val() === 'undefined' ? '' : $("#payPayItmsName").val();
    var payPayItmsDesc = typeof $("#payPayItmsDesc").val() === 'undefined' ? '' : $("#payPayItmsDesc").val();
    var payPayItmsLocClsfctn = typeof $("#payPayItmsLocClsfctn").val() === 'undefined' ? '' : $("#payPayItmsLocClsfctn").val();
    var payPayItmsMajTyp = typeof $("#payPayItmsMajTyp").val() === 'undefined' ? '' : $("#payPayItmsMajTyp").val();
    var payPayItmsMinTyp = typeof $("#payPayItmsMinTyp").val() === 'undefined' ? '' : $("#payPayItmsMinTyp").val();
    var payPayItmsUOM = typeof $("#payPayItmsUOM").val() === 'undefined' ? '' : $("#payPayItmsUOM").val();

    var payPayItmsPyaFeq = typeof $("#payPayItmsPyaFeq").val() === 'undefined' ? '' : $("#payPayItmsPyaFeq").val();
    var payPayItmsBalsType = typeof $("#payPayItmsBalsType").val() === 'undefined' ? '' : $("#payPayItmsBalsType").val();
    var payPayItmsEffctOrg = typeof $("#payPayItmsEffctOrg").val() === 'undefined' ? '' : $("#payPayItmsEffctOrg").val();
    var payPayItmsRunPriority = typeof $("#payPayItmsRunPriority").val() === 'undefined' ? '1500' : $("#payPayItmsRunPriority").val();

    var payPayItmsIsEnbld = typeof $("input[name='payPayItmsIsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var payPayItmsAllowEdtng = typeof $("input[name='payPayItmsAllowEdtng']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var payPayItmsCreatesActng = typeof $("input[name='payPayItmsCreatesActng']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var payPayItmsUsesSQL = typeof $("input[name='payPayItmsUsesSQL']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var payPayItmsIsRetro = typeof $("input[name='payPayItmsIsRetro']:checked").val() === 'undefined' ? 'NO' : 'YES';

    var payPayItmsRetroItmID = typeof $("#payPayItmsRetroItmID").val() === 'undefined' ? '-1' : $("#payPayItmsRetroItmID").val();
    var payPayItmsInvItmID = typeof $("#payPayItmsInvItmID").val() === 'undefined' ? '-1' : $("#payPayItmsInvItmID").val();
    var payPayItmsIncrsDcrs1 = typeof $("#payPayItmsIncrsDcrs1").val() === 'undefined' ? '' : $("#payPayItmsIncrsDcrs1").val();
    var payPayItmsCostAcntID = typeof $("#payPayItmsCostAcntID").val() === 'undefined' ? '-1' : $("#payPayItmsCostAcntID").val();
    var payPayItmsIncrsDcrs2 = typeof $("#payPayItmsIncrsDcrs2").val() === 'undefined' ? '' : $("#payPayItmsIncrsDcrs2").val();
    var payPayItmsBalsAcntID = typeof $("#payPayItmsBalsAcntID").val() === 'undefined' ? '-1' : $("#payPayItmsBalsAcntID").val();

    var errMsg = "";
    if (payPayItmsName.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item Name cannot be empty!</span></p>';
    }
    if (payPayItmsMajTyp.trim() === '' || payPayItmsMinTyp.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Major and Minor Type cannot be empty!</span></p>';
    }
    if (payPayItmsUOM.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">UOM cannot be empty!</span></p>';
    }
    if (payPayItmsEffctOrg.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Effect on Person\'s Org. Debt cannot be empty!</span></p>';
    }
    if (payPayItmsCreatesActng === 'YES') {
        if (payPayItmsIncrsDcrs1.trim() === "" || payPayItmsIncrsDcrs1.trim() === "None" || Number(payPayItmsCostAcntID.replace(/[^-?0-9\.]/g,'')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Costing Account information must be provided!</span></p>';
        }
        if (payPayItmsIncrsDcrs2.trim() === "" || payPayItmsIncrsDcrs2.trim() === "None" || Number(payPayItmsBalsAcntID.replace(/[^-?0-9\.]/g,'')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Balancing Account information must be provided!</span></p>';
            return;
        }
    }
    var isVld = true;
    var slctdItemIDs = "";
    $('#payPayItmsBalsFeedsTable').find('tr').each(function (i,el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#payPayItmsBalsFeedsRow' + rndmNum + '_TrnsLnID').val();
                var ln_ItemID = $('#payPayItmsBalsFeedsRow' + rndmNum + '_ItemID').val();
                var ln_ItemNm = $('#payPayItmsBalsFeedsRow' + rndmNum + '_ItemNm').val();
                var ln_Action = $('#payPayItmsBalsFeedsRow' + rndmNum + '_Action').val();
                var ln_ScaleFctr = $('#payPayItmsBalsFeedsRow' + rndmNum + '_ScaleFctr').val();

                if (Number(ln_ItemID.replace(/[^-?0-9\.]/g,'')) > 0) {
                    if (ln_Action.trim() === "") {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Action for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#payPayItmsBalsFeedsRow' + rndmNum + '_Action').addClass('rho-error');
                    } else {
                        $('#payPayItmsBalsFeedsRow' + rndmNum + '_Action').removeClass('rho-error');
                    }
                    if (ln_ScaleFctr.replace(/[^-?0-9\.]/g,'').trim() === "") {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Action for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#payPayItmsBalsFeedsRow' + rndmNum + '_ScaleFctr').addClass('rho-error');
                    } else {
                        $('#payPayItmsBalsFeedsRow' + rndmNum + '_ScaleFctr').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdItemIDs = slctdItemIDs +
                            ln_TrnsLnID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ItemID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_Action.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ScaleFctr.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                    }
                }
            }
        }
    });

    isVld = true;
    var slctdItemValueIDs = "";
    $('#payPayItmsPsblValsTable').find('tr').each(function (i,el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_ItemValID = $('#payPayItmsPsblValsRow' + rndmNum + '_ItemValID').val();
                var ln_ValNm = $('#payPayItmsPsblValsRow' + rndmNum + '_ValNm').val();
                var ln_ValSQL = typeof $('#payPayItmsPsblValsRow' + rndmNum + '_ValSQL').val() === 'undefined' ? '0' : $('#payPayItmsPsblValsRow' + rndmNum + '_ValSQL').val();
                var ln_ValAmnt = typeof $('#payPayItmsPsblValsRow' + rndmNum + '_ValAmnt').val() === 'undefined' ? '0' : $('#payPayItmsPsblValsRow' + rndmNum + '_ValAmnt').val();

                if (ln_ValNm.trim() !== "") {
                    if (payPayItmsUsesSQL === 'NO') {
                        if (ln_ValAmnt.replace(/[^-?0-9\.]/g,'') === "") {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Value Amount for Row No. ' + i + ' cannot be empty!</span></p>';
                            $('#payPayItmsBalsFeedsRow' + rndmNum + '_ValAmnt').addClass('rho-error');
                        } else {
                            $('#payPayItmsBalsFeedsRow' + rndmNum + '_ValAmnt').removeClass('rho-error');
                        }
                    }
                    if (payPayItmsUsesSQL === 'YES') {
                        if (ln_ValSQL.replace(/[^-?0-9\.]/g,'') === "") {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Value SQL for Row No. ' + i + ' cannot be empty!</span></p>';
                            $('#payPayItmsBalsFeedsRow' + rndmNum + '_ValSQL').addClass('rho-error');
                        } else {
                            $('#payPayItmsBalsFeedsRow' + rndmNum + '_ValSQL').removeClass('rho-error');
                        }
                    }
                    if (isVld === true) {
                        slctdItemValueIDs = slctdItemValueIDs +
                            ln_ItemValID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ValNm.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ValSQL.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                            ln_ValAmnt.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                    }
                }
            }
        }
    });
    isVld = true;
    var slctdExtraInfoLines = "";
    $('#payPayItmsExtrInfTable').find('tr').each(function (i,el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_DfltRowID = $('#payPayItmsExtrInfRow' + rndmNum + '_DfltRowID').val();
                var ln_CombntnID = $('#payPayItmsExtrInfRow' + rndmNum + '_CombntnID').val();
                var ln_TableID = $('#payPayItmsExtrInfRow' + rndmNum + '_TableID').val();
                var ln_Value = $('#payPayItmsExtrInfRow' + rndmNum + '_Value').val();
                var ln_extrInfoCtgry = $('#payPayItmsExtrInfRow' + rndmNum + '_extrInfoCtgry').val();
                var ln_extrInfoLbl = $('#payPayItmsExtrInfRow' + rndmNum + '_extrInfoLbl').val();

                if (isVld === true) {
                    slctdExtraInfoLines = slctdExtraInfoLines +
                        ln_DfltRowID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_CombntnID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_TableID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_extrInfoCtgry.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_extrInfoLbl.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_Value.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Pay Item',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Pay Item...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',7);
    formData.append('typ',1);
    formData.append('pg',10);
    formData.append('q','UPDATE');
    formData.append('actyp',1);
    formData.append('payPayItmsID',payPayItmsID);
    formData.append('payPayItmsName',payPayItmsName);
    formData.append('payPayItmsDesc',payPayItmsDesc);
    formData.append('payPayItmsLocClsfctn',payPayItmsLocClsfctn);
    formData.append('payPayItmsMajTyp',payPayItmsMajTyp);
    formData.append('payPayItmsMinTyp',payPayItmsMinTyp);
    formData.append('payPayItmsUOM',payPayItmsUOM);

    formData.append('payPayItmsPyaFeq',payPayItmsPyaFeq);
    formData.append('payPayItmsBalsType',payPayItmsBalsType);
    formData.append('payPayItmsEffctOrg',payPayItmsEffctOrg);
    formData.append('payPayItmsRunPriority',payPayItmsRunPriority);

    formData.append('payPayItmsIsEnbld',payPayItmsIsEnbld);
    formData.append('payPayItmsAllowEdtng',payPayItmsAllowEdtng);
    formData.append('payPayItmsCreatesActng',payPayItmsCreatesActng);
    formData.append('payPayItmsUsesSQL',payPayItmsUsesSQL);
    formData.append('payPayItmsIsRetro',payPayItmsIsRetro);

    formData.append('payPayItmsRetroItmID',payPayItmsRetroItmID);
    formData.append('payPayItmsInvItmID',payPayItmsInvItmID);
    formData.append('payPayItmsIncrsDcrs1',payPayItmsIncrsDcrs1);
    formData.append('payPayItmsCostAcntID',payPayItmsCostAcntID);
    formData.append('payPayItmsIncrsDcrs2',payPayItmsIncrsDcrs2);
    formData.append('payPayItmsBalsAcntID',payPayItmsBalsAcntID);

    formData.append('slctdItemIDs',slctdItemIDs);
    formData.append('slctdItemValueIDs',slctdItemValueIDs);
    formData.append('slctdExtraInfoLines',slctdExtraInfoLines);

    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            payPayItmsID = data.payPayItmsID;
                            getOnePayPayItmsForm(payPayItmsID,0);
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delPayPayItms(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItemID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItemID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_ItemNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Pay Item?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Pay Item?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Pay Item?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Pay Item...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 10,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function delPayPayItmFeeds(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_ItemNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item Feed?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item Feed?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item Feed?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item Feed...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 10,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function delPayPayItmsAttchdVal(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItemValID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItemValID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_ValNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item Possible Value?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item Possible Value?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item Possible Value?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item Possible Value...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 10,
                                    q: 'DELETE',
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function insertNewPayItmFeedsRows(tableElmntID,position,inptHtml) {
    $("#allOtherInputData5").val(0);
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g,nwRndm + "_").replace(/(_WWW123WWW)+/g,nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID).append(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }

    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                cntr++;
                var prfxName1 = $(el).attr('id').split("_")[0];
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
                //alert($tds.eq(0).text()+cntr);
                $tds.eq(0).html(cntr);
                //alert($tds.eq(0).text()+cntr);
            }
        }
    });
}

function insertNewPayItmsAttchdValRows(tableElmntID,position,inptHtml) {
    $("#allOtherInputData5").val(0);
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g,nwRndm + "_").replace(/(_WWW123WWW)+/g,nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID).append(nwInptHtml);
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }

    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".jbDetFuncRate").focus(function () {
        $(this).select();
    });
    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                cntr++;
                var prfxName1 = $(el).attr('id').split("_")[0];
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
                //alert($tds.eq(0).text()+cntr);
                $tds.eq(0).html(cntr);
                //alert($tds.eq(0).text()+cntr);
            }
        }
    });
}

function saveGRAPAYEForm() {
    var errMsg = "";
    var isVld = true;
    var slctdRateIDs = "";
    $('#allPayeRatesTable').find('tr').each(function (i,el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_RatesID = $('#allPayeRatesRow' + rndmNum + '_RatesID').val();
                var ln_Level = $('#allPayeRatesRow' + rndmNum + '_Level').val();
                var ln_Taxable = $('#allPayeRatesRow' + rndmNum + '_Taxable').val();
                var ln_Rate = $('#allPayeRatesRow' + rndmNum + '_Rate').val();
                //Number(ln_Level.replace(/[^-?0-9\.]/g, ''))
                if (ln_Level.replace(/[^-?0-9\.]/g,'').trim() === "") {
                    isVld = false;
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                        'font-weight:bold;color:red;">Level for Row No. ' + i + ' cannot be empty!</span></p>';
                    $('#allPayeRatesRow' + rndmNum + '_Level').addClass('rho-error');
                } else {
                    $('#allPayeRatesRow' + rndmNum + '_Level').removeClass('rho-error');
                }
                if (ln_Taxable.replace(/[^-?0-9\.]/g,'').trim() === "") {
                    isVld = false;
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                        'font-weight:bold;color:red;">Taxable Amount for Row No. ' + i + ' cannot be empty!</span></p>';
                    $('#allPayeRatesRow' + rndmNum + '_Taxable').addClass('rho-error');
                } else {
                    $('#allPayeRatesRow' + rndmNum + '_Taxable').removeClass('rho-error');
                }
                if (ln_Rate.replace(/[^-?0-9\.]/g,'').trim() === "") {
                    isVld = false;
                    errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                        'font-weight:bold;color:red;">Rate for Row No. ' + i + ' cannot be empty!</span></p>';
                    $('#allPayeRatesRow' + rndmNum + '_Rate').addClass('rho-error');
                } else {
                    $('#allPayeRatesRow' + rndmNum + '_Rate').removeClass('rho-error');
                }
                if (isVld === true) {
                    slctdRateIDs = slctdRateIDs +
                        ln_RatesID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_Level.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_Taxable.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        ln_Rate.replace(/[^-?0-9\.]/g,'').replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });

    if (rhotrim(slctdRateIDs,', ') === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Rates cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Tax Rates',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Tax Rates...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',7);
    formData.append('typ',1);
    formData.append('pg',12);
    formData.append('q','UPDATE');
    formData.append('actyp',1);
    formData.append('slctdRateIDs',slctdRateIDs);

    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            openATab('#allmodules','grp=7&typ=1&pg=12&vtyp=0');;
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delPayTaxRateLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RatesID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RatesID').val();
        pKeyNm = "Paye Rate";
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "PAYE Rate Line";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 12,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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
//TRANSACTION TYPES
function getPayTransTyps(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payTransTypsSrchFor").val() === 'undefined' ? '%' : $("#payTransTypsSrchFor").val();
    var srchIn = typeof $("#payTransTypsSrchIn").val() === 'undefined' ? 'Both' : $("#payTransTypsSrchIn").val();
    var pageNo = typeof $("#payTransTypsPageNo").val() === 'undefined' ? 1 : $("#payTransTypsPageNo").val();
    var limitSze = typeof $("#payTransTypsDsplySze").val() === 'undefined' ? 10 : $("#payTransTypsDsplySze").val();
    var sortBy = typeof $("#payTransTypsSortBy").val() === 'undefined' ? '' : $("#payTransTypsSortBy").val();
    var qShwUsrOnly = $('#payTransTypsShwUsrOnly:checked').length > 0;
    var qShwUnpstdOnly = $('#payTransTypsShwUnpstdOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwUsrOnly=" + qShwUsrOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayTransTyps(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayTransTyps(actionText,slctr,linkArgs);
    }
}

function getOnePayTransTypsForm(pKeyID,vwtype,actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=7&typ=1&pg=15&vtyp=' + vwtype + '&sbmtdPayTransTypsID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalxLG',actionTxt,'Transaction Types (ID:' + pKeyID + ')','myFormsModalxLGTitle','myFormsModalxLGBody',function () {
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
        }).on('hide',function (ev) {
            $('#myFormsModalxLG').css("overflow","auto");
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
        }).on('hide',function (ev) {
            $('#myFormsModalxLG').css("overflow","auto");
        });
        if (!$.fn.DataTable.isDataTable('#payTrnTypClsfctnsTable')) {
            var table1 = $('#payTrnTypClsfctnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payTrnTypClsfctnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#allOtherInputData99').val('0');
        $('#onePayTransTypsEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalxLG').off('hidden.bs.modal');
        $('#myFormsModalxLG').one('hidden.bs.modal',function (e) {
            getPayTransTyps('','#allmodules','grp=7&typ=1&pg=15&vtyp=0');
            $(e.currentTarget).unbind();
        });
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
        $(".trnTypPrdNum").ForceNumericOnly();

        if (!$.fn.DataTable.isDataTable('#payTrnTypClsfctnsTable')) {
            var table1 = $('#payTrnTypClsfctnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#payTrnTypClsfctnsTable').wrap('<div class="dataTables_scroll"/>');
        }

    });
}

function shwHideTrnsTypsDivs() {
    var whtToDo = typeof $("#payTransTyp").val() === 'undefined' ? 'LOAN' : $("#payTransTyp").val();
    if (whtToDo === 'LOAN' || whtToDo === 'PAYMENT' || whtToDo === 'SETTLEMENT') {
        $('#payTransIvstmntDiv').addClass('hideNotice');
        $('#payTransItemsDiv').removeClass('hideNotice');
        if (whtToDo === 'SETTLEMENT') {
            $('#payTransItemLoansDiv').addClass('hideNotice');
            $('#payTransItemSettlDiv').removeClass('hideNotice');
        }
        if (whtToDo === 'LOAN') {
            $('#payTransItemLoansDiv').removeClass('hideNotice');
            $('#payTransItemSettlDiv').addClass('hideNotice');
        }
    } else {
        $('#payTransItemsDiv').addClass('hideNotice');
        $('#payTransIvstmntDiv').removeClass('hideNotice');
    }
}


function savePayTransTypsForm(funcur,shdSbmt) {
    var sbmtdPayTransTypsID = typeof $("#sbmtdPayTransTypsID").val() === 'undefined' ? '-1' : $("#sbmtdPayTransTypsID").val();
    var payTransTypsName = typeof $("#payTransTypsName").val() === 'undefined' ? '' : $("#payTransTypsName").val();
    var payTransTypsDesc = typeof $("#payTransTypsDesc").val() === 'undefined' ? '' : $("#payTransTypsDesc").val();
    var payTransTyp = typeof $("#payTransTyp").val() === 'undefined' ? '' : $("#payTransTyp").val();
    var payTransPeriodTyp = typeof $("#payTransPeriodTyp").val() === 'undefined' ? '' : $("#payTransPeriodTyp").val();
    var payTransTypsPrd = typeof $("#payTransTypsPrd").val() === 'undefined' ? '0' : $("#payTransTypsPrd").val();
    var payTransTypsItmStID = typeof $("#payTransTypsItmStID").val() === 'undefined' ? '-1' : $("#payTransTypsItmStID").val();
    var payTransTypsMnItmID = typeof $("#payTransTypsMnItmID").val() === 'undefined' ? '-1' : $("#payTransTypsMnItmID").val();
    var payTransCshAcntID = typeof $("#payTransCshAcntID").val() === 'undefined' ? '-1' : $("#payTransCshAcntID").val();
    var payTransAssetAcntID = typeof $("#payTransAssetAcntID").val() === 'undefined' ? '-1' : $("#payTransAssetAcntID").val();
    var payTransRcvblAcntID = typeof $("#payTransRcvblAcntID").val() === 'undefined' ? '-1' : $("#payTransRcvblAcntID").val();
    var payTransLbltyAcntID = typeof $("#payTransLbltyAcntID").val() === 'undefined' ? '-1' : $("#payTransLbltyAcntID").val();
    var payTransRvnuAcntID = typeof $("#payTransRvnuAcntID").val() === 'undefined' ? '-1' : $("#payTransRvnuAcntID").val();
    var payTransTypsIsEnbld = typeof $("input[name='payTransTypsIsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';

    var payTransTypsIntrst = typeof $("#payTransTypsIntrst").val() === 'undefined' ? '0' : $("#payTransTypsIntrst").val();
    var payTransTypsIntrstTyp = typeof $("#payTransTypsIntrstTyp").val() === 'undefined' ? '' : $("#payTransTypsIntrstTyp").val();
    var payTransTypsRepay = typeof $("#payTransTypsRepay").val() === 'undefined' ? '0' : $("#payTransTypsRepay").val();
    var payTransTypsRepayTyp = typeof $("#payTransTypsRepayTyp").val() === 'undefined' ? '' : $("#payTransTypsRepayTyp").val();
    var payTransTypsPFrmlr = typeof $("#payTransTypsPFrmlr").val() === 'undefined' ? '0' : $("#payTransTypsPFrmlr").val();
    var payTransTypsNetAmntFmlr = typeof $("#payTransTypsNetAmntFmlr").val() === 'undefined' ? '0' : $("#payTransTypsNetAmntFmlr").val();
    var payTransTypsMxAmntFrmlr = typeof $("#payTransTypsMxAmntFrmlr").val() === 'undefined' ? '0' : $("#payTransTypsMxAmntFrmlr").val();
    var payTransTypsEnfrcMx = typeof $("input[name='payTransTypsEnfrcMx']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var lnkdPayTransTypsID = typeof $("#lnkdPayTransTypsID").val() === 'undefined' ? '-1' : $("#lnkdPayTransTypsID").val();
    var payTransTypsMinAmntFrmlr = typeof $("#payTransTypsMinAmntFrmlr").val() === 'undefined' ? '' : $("#payTransTypsMinAmntFrmlr").val();
    var payTransTypsMnBalsItmID = typeof $("#payTransTypsMnBalsItmID").val() === 'undefined' ? '-1' : $("#payTransTypsMnBalsItmID").val();

    var errMsg = "";
    if (payTransTyp.trim() !== 'SETTLEMENT') {
        lnkdPayTransTypsID = '-1';
        payTransTypsMnBalsItmID = '-1';
    }
    if ((payTransTyp.trim() === 'SETTLEMENT') && (Number(lnkdPayTransTypsID.replace(/[^-?0-9\.]/g,'')) <= 0 || Number(payTransTypsMnBalsItmID.replace(/[^-?0-9\.]/g,'')) <= 0)) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Loan Type and Main Balance Item cannot be empty for SETTLEMENT!</span></p>';
    }
    if (payTransTyp.trim() === '' || payTransTypsName.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Type or Name cannot be empty!</span></p>';
    }
    if ((payTransTyp.trim() === 'LOAN' || payTransTyp.trim() === 'PAYMENT') && Number(payTransTypsItmStID.replace(/[^-?0-9\.]/g,'')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item Set cannot be empty!</span></p>';
    }
    if ((payTransTyp.trim() === 'LOAN' || payTransTyp.trim() === 'PAYMENT') && Number(payTransTypsMnItmID.replace(/[^-?0-9\.]/g,'')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Main Pay Item cannot be empty!</span></p>';
    }
    if ((payTransTyp.trim() === 'INVESTMENT') && (Number(payTransCshAcntID.replace(/[^-?0-9\.]/g,'')) <= 0 ||
        Number(payTransAssetAcntID.replace(/[^-?0-9\.]/g,'')) <= 0 ||
        Number(payTransRcvblAcntID.replace(/[^-?0-9\.]/g,'')) <= 0 ||
        Number(payTransLbltyAcntID.replace(/[^-?0-9\.]/g,'')) <= 0 ||
        Number(payTransRvnuAcntID.replace(/[^-?0-9\.]/g,'')) <= 0)) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">All Accounts fields are required!</span></p>';
    }
    if ((payTransTyp.trim() === 'INVESTMENT') && (payTransPeriodTyp.trim() === '')) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Period Type cannot be empty!</span></p>';
    }
    var slctdTypClsfctns = "";
    var isVld = true;
    var errMsg = "";
    $('#payTrnTypClsfctnsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var majClsfctn = $('#payTrnTypClsfctnsRow' + rndmNum + '_MajClsfctn').val();
                var minClsfctn = $('#payTrnTypClsfctnsRow' + rndmNum + '_MinClsfctn').val();
                var ln_OrdrNum = $('#payTrnTypClsfctnsRow' + rndmNum + '_OrdrNum').val();
                var isEnabled = typeof $("input[name='payTrnTypClsfctnsRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (majClsfctn.trim() === '') {
                    $('#payTrnTypClsfctnsRow' + rndmNum + '_MajClsfctn').addClass('rho-error');
                } else {
                    $('#payTrnTypClsfctnsRow' + rndmNum + '_MajClsfctn').removeClass('rho-error');
                    slctdTypClsfctns = slctdTypClsfctns + $('#payTrnTypClsfctnsRow' + rndmNum + '_ClsfctnID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#payTrnTypClsfctnsRow' + rndmNum + '_OrdrNum').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#payTrnTypClsfctnsRow' + rndmNum + '_MajClsfctn').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#payTrnTypClsfctnsRow' + rndmNum + '_MinClsfctn').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        isEnabled.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
                }
            }
        }
    });
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Transaction Types',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction Types...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',7);
    formData.append('typ',1);
    formData.append('pg',15);
    formData.append('q','UPDATE');
    formData.append('actyp',1);
    formData.append('sbmtdPayTransTypsID',sbmtdPayTransTypsID);
    formData.append('payTransTypsName',payTransTypsName);
    formData.append('payTransTypsDesc',payTransTypsDesc);
    formData.append('payTransTyp',payTransTyp);
    formData.append('payTransTypsPrd',payTransTypsPrd);
    formData.append('payTransPeriodTyp',payTransPeriodTyp);
    formData.append('payTransTypsItmStID',payTransTypsItmStID);
    formData.append('payTransTypsMnItmID',payTransTypsMnItmID);
    formData.append('payTransCshAcntID',payTransCshAcntID);
    formData.append('payTransAssetAcntID',payTransAssetAcntID);
    formData.append('payTransRcvblAcntID',payTransRcvblAcntID);
    formData.append('payTransLbltyAcntID',payTransLbltyAcntID);
    formData.append('payTransRvnuAcntID',payTransRvnuAcntID);
    formData.append('payTransTypsIsEnbld',payTransTypsIsEnbld);

    formData.append('payTransTypsIntrst',payTransTypsIntrst);
    formData.append('payTransTypsIntrstTyp',payTransTypsIntrstTyp);
    formData.append('payTransTypsRepay',payTransTypsRepay);
    formData.append('payTransTypsRepayTyp',payTransTypsRepayTyp);
    formData.append('payTransTypsPFrmlr',payTransTypsPFrmlr);
    formData.append('payTransTypsNetAmntFmlr',payTransTypsNetAmntFmlr);
    formData.append('payTransTypsMxAmntFrmlr',payTransTypsMxAmntFrmlr);
    formData.append('payTransTypsEnfrcMx',payTransTypsEnfrcMx);
    formData.append('slctdTypClsfctns',slctdTypClsfctns);
    formData.append('lnkdPayTransTypsID',lnkdPayTransTypsID);
    formData.append('payTransTypsMnBalsItmID',payTransTypsMnBalsItmID);
    formData.append('payTransTypsMinAmntFrmlr',payTransTypsMinAmntFrmlr);

    formData.append('shdSbmt',shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPayTransTypsID = data.sbmtdPayTransTypsID;
                            getOnePayTransTypsForm(sbmtdPayTransTypsID,1,'ReloadDialog');
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delPayTrnTypClsfctn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ClsfctnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ClsfctnID').val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();
         var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Classifications";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 15,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function delPayTransTyps(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_HdrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_HdrID').val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Transaction Setup";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 15,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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
//Investment Transactions - Fund Management
function getPayInvstTrans(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payInvstTransSrchFor").val() === 'undefined' ? '%' : $("#payInvstTransSrchFor").val();
    var srchIn = typeof $("#payInvstTransSrchIn").val() === 'undefined' ? 'Both' : $("#payInvstTransSrchIn").val();
    var pageNo = typeof $("#payInvstTransPageNo").val() === 'undefined' ? 1 : $("#payInvstTransPageNo").val();
    var limitSze = typeof $("#payInvstTransDsplySze").val() === 'undefined' ? 10 : $("#payInvstTransDsplySze").val();
    var sortBy = typeof $("#payInvstTransSortBy").val() === 'undefined' ? '' : $("#payInvstTransSortBy").val();
    var qShwUsrOnly = $('#payInvstTransShwUsrOnly:checked').length > 0;
    var qShwUnpstdOnly = $('#payInvstTransShwUnpstdOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwUsrOnly=" + qShwUsrOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayInvstTrans(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayInvstTrans(actionText,slctr,linkArgs);
    }
}

function getOnePayInvstTransForm(pKeyID,vwtype,actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=7&typ=1&pg=14&vtyp=' + vwtype + '&sbmtdPayInvstTransID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalNrml',actionTxt,'Fund Management Voucher Details (ID:' + pKeyID + ')','myFormsModalNrmlTitle','myFormsModalNrmlBody',function () {
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
        }).on('hide',function (ev) {
            $('#myFormsModalNrml').css("overflow","auto");
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
        }).on('hide',function (ev) {
            $('#myFormsModalLg').css("overflow","auto");
        });
        $('#allOtherInputData99').val('0');
        $('#onePayInvstTransEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal',function (e) {
            getPayInvstTrans('','#allmodules','grp=7&typ=1&pg=14&vtyp=0');
            $(e.currentTarget).unbind();
        });
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
        $(".fundNumber").ForceNumericOnly();
    });
}

function getOnePInvstRedeemForm(pKeyID,vwtype,actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=7&typ=1&pg=14&vtyp=' + vwtype + '&sbmtdPayInvstTransID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModaly',actionTxt,'Investment Redemption Details (ID:' + pKeyID + ')','myFormsModalyTitle','myFormsModalyBody',function () {
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
        }).on('hide',function (ev) {
            $('#myFormsModaly').css("overflow","auto");
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
        }).on('hide',function (ev) {
            $('#myFormsModaly').css("overflow","auto");
        });
        $('#allOtherInputData99').val('0');
        $('#onePayInvstTransEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModaly').off('hidden.bs.modal');
        $('#myFormsModaly').one('hidden.bs.modal',function (e) {
            getOnePayInvstTransForm(pKeyID,1,'ReloadDialog');
            $(e.currentTarget).unbind();
        });
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
        $(".fundNumber").ForceNumericOnly();
    });
}
//INVESTMENT CALCULATOR
function getOnePayInvstCalcForm(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=14&vtyp=' + vwtype + '&sbmtdPayInvstTransID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModaly','ShowDialog','Investment Calculator','myFormsModalyTitle','myFormsModalyBody',function () {
        $('[data-toggle="tooltip"]').tooltip();


        (function ($) {
            var typingTimeout;
            $.fn.detect = function (method) {
                var $field = $(this),
                    field = this;
                $(field).on("keypress",function (e) {
                    startTypingTimer($(e.target));
                });
                $(field).keyup(function (e) {
                    if (e.keyCode == 8)
                        startTypingTimer($(e.target));
                });
                $(field).on('input paste',function (e) {
                    startTypingTimer($(e.target));
                });
            }
            $("#billrate").on("change",function (e) {
                var rate = Number($('#billrate').val()) * 100;
                $('#erate').val(rate);
                calculate_bill();
            });
            $("#rate").on("change",function (e) {
                $("#erate").val('');
                calculate();
            });

            function startTypingTimer(field_input) {
                if (typingTimeout != undefined)
                    clearTimeout(typingTimeout);
                typingTimeout = setTimeout(function () {
                    eval(field_input.attr("onfinishinput"));
                },200);
            }

            function replaceAll(find,replace,str) {
                return str.replace(new RegExp(find,'g'),replace);
            }

            function numberWithCommas(x) {
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",");
            }

            function calculate_bill() {
                var principal = $("input#principal").val();
                principal = replaceAll(',','',principal);
                principal = parseFloat(principal);
                if (!principal)
                    return false;

                var rate = parseFloat($('input#erate').val()) || "";
                if (rate == "") {
                    rate = $("select#billrate").val();
                } else {
                    rate = parseFloat(rate) / 100.0;
                }

                $('.total-91').text('GHS ' + fv_tbill(rate,1,principal,1));
                $('.total-180').text('GHS ' + fv_tbill(rate,2,principal,1));
                $('.total-360').text('GHS ' + fv_tbill(rate,4,principal,1));
                $('.total-720').text('GHS ' + fv_tbill(rate,8,principal,1));
                $('.total-1080').text('GHS ' + fv_tbill(rate,12,principal,1));
                $('.total-1800').text('GHS ' + fv_tbill(rate,20,principal,1));
                $('.total-7200').text('GHS ' + fv_tbill(rate,80,principal,1));
            }

            function calculate() {

                var principal = $("input#principal").val();
                principal = replaceAll(',','',principal);
                principal = parseFloat(principal);
                if (!principal)
                    return false;

                var rate = parseFloat($('input#erate').val()) || "";
                if (rate == "") {
                    rate = $("select#rate").val();
                } else {
                    rate = parseFloat(rate) / 100.0;
                }
                $('.total-91').text('GHS ' + fv_a(rate,1,0,principal,1));
                $('.total-180').text('GHS ' + fv_a(rate,2,0,principal,1));
                $('.total-360').text('GHS ' + fv_a(rate,4,0,principal,1));
                $('.total-720').text('GHS ' + fv_a(rate,8,0,principal,1));
                $('.total-1080').text('GHS ' + fv_a(rate,12,0,principal,1));
                $('.total-1800').text('GHS ' + fv_a(rate,20,0,principal,1));
                $('.total-7200').text('GHS ' + fv_a(rate,80,principal,1));
            }

            function fv_a(rate,nper,pmt,pv,type) {
                var fv = pv * Math.pow(1 + (rate / 4),nper)
                return numberWithCommas(fv.toFixed(2));
            }

            function fv_tbill(rate,nper,pv,type) {
                var pmt = 0;
                var rate = Math.pow(1 + rate,(1 / 4)) - 1;
                var pv = '-' + pv;
                var pow = Math.pow(1 + rate,nper),
                    fv;
                if (rate) {
                    fv = (pmt * (1 + rate * type) * (1 - pow) / rate) - pv * pow;
                } else {
                    fv = -1 * (pv + pmt * nper);
                }
                return numberWithCommas(fv.toFixed(2));
            }

            function fv(pv,r,n,nper) {
                r = r / 100;
                return ((((r / nper) * pv) / n) + parseFloat(pv)).toFixed(3);
            }

        }(jQuery));
        $("input#principal").detect('calculate');
        $("input#erate").detect('calculate');
        $('#attchdInvstTransDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function getOnePayInvstTransDocsForm(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=14&vtyp=' + vwtype + '&sbmtdPayInvstTransID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModaly','ShowDialog','Fund Management Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        var table1 = $('#attchdInvstTransDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdInvstTransDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdInvstTransDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToInvstTransDocs(inptElmntID,attchIDElmntID,docNmElmntID,sbmtdHdrID,rowIDAttrb) {
    var docCtrgrName = $('#' + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    $("#" + inptElmntID).val('');
    $("#" + inptElmntID).off('change');
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToInvstTransDocs(input.files[0],docNmElmntID,attchIDElmntID,sbmtdHdrID,function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) { }
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToInvstTransDocs(file,docNmElmntID,attchIDElmntID,sbmtdHdrID,callBackFunc) {
    var data1 = new FormData();
    data1.append('daInvstTransAttchmnt',file);
    data1.append('grp',7);
    data1.append('typ',1);
    data1.append('pg',14);
    data1.append('q','UPDATE');
    data1.append('actyp',2);
    data1.append('docCtrgrName',$('#' + docNmElmntID).val());
    data1.append('attchmentID',$('#' + attchIDElmntID).val());
    data1.append('sbmtdPayInvstTransID',sbmtdHdrID);
    var dialog1 = bootbox.alert({
        title: 'Uploading File...',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>'
    });
    dialog1.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
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
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getAttchdInvstTransDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdInvstTransDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdInvstTransDocsSrchFor").val();
    var srchIn = typeof $("#attchdInvstTransDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdInvstTransDocsSrchIn").val();
    var pageNo = typeof $("#attchdInvstTransDocsPageNo").val() === 'undefined' ? 1 : $("#attchdInvstTransDocsPageNo").val();
    var limitSze = typeof $("#attchdInvstTransDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdInvstTransDocsDsplySze").val();
    var sortBy = typeof $("#attchdInvstTransDocsSortBy").val() === 'undefined' ? '' : $("#attchdInvstTransDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs,'myFormsModaly',actionDialog,'Fund Management Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        if (!$.fn.DataTable.isDataTable('#attchdInvstTransDocsTable')) {
            var table1 = $('#attchdInvstTransDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdInvstTransDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdInvstTransDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdInvstTransDocs(e,actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdInvstTransDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdInvstTransDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdPayInvstTransID").val() === 'undefined' ? -1 : $("#sbmtdPayInvstTransID").val();
    var docNum = typeof $("#payInvstTransDocNum").val() === 'undefined' ? '' : $("#payInvstTransDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdInvstTransDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdInvstTransDocsRow' + rndmNum + '_AttchdDocsID').val();
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
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: 'Delete Document?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE',
                                    actyp: 5,
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

function delPayInvstTrans(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_HdrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_HdrID').val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Fund Management";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 14,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function savePayInvstTransForm(funcur,shdSbmt) {
    var sbmtdPayInvstTransID = typeof $("#sbmtdPayInvstTransID").val() === 'undefined' ? -1 : $("#sbmtdPayInvstTransID").val();
    var payInvstTrnsType = typeof $("#payInvstTrnsType").val() === 'undefined' ? '' : $("#payInvstTrnsType").val();
    var payInvstItemType = typeof $("#payInvstItemType").val() === 'undefined' ? '' : $("#payInvstItemType").text();
    var payInvstItemTypID = typeof $("#payInvstItemType").val() === 'undefined' ? '-1' : $("#payInvstItemType").val();
    var payInvstRollOvrType = typeof $("#payInvstRollOvrType").val() === 'undefined' ? '' : $("#payInvstRollOvrType").val();
    var payInvstPrchsDte = typeof $("#payInvstPrchsDte").val() === 'undefined' ? '' : $("#payInvstPrchsDte").val();
    var payInvstMatureDte = typeof $("#payInvstMatureDte").val() === 'undefined' ? '' : $("#payInvstMatureDte").val();
    var payInvstTransDesc = typeof $("#payInvstTransDesc").val() === 'undefined' ? '' : $("#payInvstTransDesc").val();
    var payInvstRefNum = typeof $("#payInvstRefNum").val() === 'undefined' ? '' : $("#payInvstRefNum").val();
    var payInvstTransInvcCur = typeof $("#payInvstTransInvcCur").val() === 'undefined' ? '' : $("#payInvstTransInvcCur").val();
    var payInvstPrchsAmnt = typeof $("#payInvstPrchsAmnt").val() === 'undefined' ? '0.00' : $("#payInvstPrchsAmnt").val();
    var payInvstMatureAmnt = typeof $("#payInvstMatureAmnt").val() === 'undefined' ? '0.00' : $("#payInvstMatureAmnt").val();
    var payInvstIntrstRate = typeof $("#payInvstIntrstRate").val() === 'undefined' ? '0.00' : $("#payInvstIntrstRate").val();
    var payInvstExchngRate = typeof $("#payInvstExchngRate").val() === 'undefined' ? '0.00' : $("#payInvstExchngRate").val();
    var payInvstClientID = typeof $("#payInvstClientID").val() === 'undefined' ? '' : $("#payInvstClientID").val();
    var errMsg = "";
    if (payInvstTrnsType.trim() === '' || payInvstItemType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction and Item Type cannot be empty!</span></p>';
    }
    if (payInvstTransDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Narration cannot be empty!</span></p>';
    }
    if (payInvstRefNum.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reference Number cannot be empty!</span></p>';
    }
    if (payInvstPrchsDte.trim() === '' || payInvstTransInvcCur.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Purchase Date/Currency cannot be empty!</span></p>';
    }
    payInvstPrchsAmnt = fmtAsNumber('payInvstPrchsAmnt').toFixed(2);
    payInvstMatureAmnt = fmtAsNumber('payInvstMatureAmnt').toFixed(2);
    payInvstIntrstRate = fmtAsNumber('payInvstIntrstRate').toFixed(2);
    payInvstExchngRate = fmtAsNumber2('payInvstExchngRate').toFixed(4);
    if (payInvstPrchsAmnt <= 0 && payInvstMatureAmnt <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Purchase and Maturity Amounts cannot be both zero or less at the same time!</span></p>';
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Fund Management',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Fund Management...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',7);
    formData.append('typ',1);
    formData.append('pg',14);
    formData.append('q','UPDATE');
    formData.append('actyp',1);
    formData.append('sbmtdPayInvstTransID',sbmtdPayInvstTransID);
    formData.append('payInvstTrnsType',payInvstTrnsType);
    formData.append('payInvstItemType',payInvstItemType);
    formData.append('payInvstItemTypID',payInvstItemTypID);
    formData.append('payInvstTransDesc',payInvstTransDesc);
    formData.append('payInvstRollOvrType',payInvstRollOvrType);
    formData.append('payInvstPrchsDte',payInvstPrchsDte);
    formData.append('payInvstMatureDte',payInvstMatureDte);
    formData.append('payInvstRefNum',payInvstRefNum);
    formData.append('payInvstTransInvcCur',payInvstTransInvcCur);
    formData.append('payInvstPrchsAmnt',payInvstPrchsAmnt);
    formData.append('payInvstMatureAmnt',payInvstMatureAmnt);
    formData.append('payInvstIntrstRate',payInvstIntrstRate);
    formData.append('payInvstExchngRate',payInvstExchngRate);
    formData.append('payInvstClientID',payInvstClientID);
    formData.append('shdSbmt',shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPayInvstTransID = data.sbmtdPayInvstTransID;
                            getOnePayInvstTransForm(sbmtdPayInvstTransID,1,'ReloadDialog');
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function savePInvstRedeemForm(funcur,shdSbmt) {
    var sbmtdPayInvstTransID = typeof $("#sbmtdPayInvstTransID2").val() === 'undefined' ? -1 : $("#sbmtdPayInvstTransID2").val();
    var payInvstRollOvrType = typeof $("#payInvstRollOvrType2").val() === 'undefined' ? '' : $("#payInvstRollOvrType2").val();
    var payInvstMatureDte = typeof $("#payInvstMatureDte2").val() === 'undefined' ? '' : $("#payInvstMatureDte2").val();
    var payInvstTransDesc = typeof $("#payInvstTransDesc2").val() === 'undefined' ? '' : $("#payInvstTransDesc2").val();
    var payOLDMatureAmnt = typeof $("#payOLDMatureAmnt2").val() === 'undefined' ? '0.00' : $("#payOLDMatureAmnt2").val();
    var payInvstMatureAmnt = typeof $("#payInvstMatureAmnt2").val() === 'undefined' ? '0.00' : $("#payInvstMatureAmnt2").val();
    var errMsg = "";
    if (payInvstMatureDte.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Marurity Date cannot be empty!</span></p>';
    }
    payInvstMatureAmnt = fmtAsNumber('payInvstMatureAmnt2').toFixed(2);
    if (payInvstMatureAmnt <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Maturity Amount cannot be zero or less!</span></p>';
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Investment Redemption',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Investment Redemption...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',7);
    formData.append('typ',1);
    formData.append('pg',14);
    formData.append('q','UPDATE');
    formData.append('actyp',5);
    formData.append('sbmtdPayInvstTransID',sbmtdPayInvstTransID);
    formData.append('payInvstTransDesc',payInvstTransDesc);
    formData.append('payInvstRollOvrType',payInvstRollOvrType);
    formData.append('payInvstMatureDte',payInvstMatureDte);
    formData.append('payInvstMatureAmnt',payInvstMatureAmnt);
    formData.append('payOLDMatureAmnt',payOLDMatureAmnt);
    formData.append('shdSbmt',shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPayInvstTransID = data.sbmtdPayInvstTransID;
                            getOnePayInvstTransForm(sbmtdPayInvstTransID,1,'ReloadDialog');
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function savePayInvstTransRvrslForm(funcCur,shdSbmt) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslPayInvstTransBtn");
    }

    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdPayInvstTransID = typeof $("#sbmtdPayInvstTransID").val() === 'undefined' ? -1 : $("#sbmtdPayInvstTransID").val();
    var payInvstTransDesc = typeof $("#payInvstTransDesc").val() === 'undefined' ? '' : $("#payInvstTransDesc").val();
    var payInvstTransDesc1 = typeof $("#payInvstTransDesc1").val() === 'undefined' ? '' : $("#payInvstTransDesc1").val();
    var errMsg = "";
    if (sbmtdPayInvstTransID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (payInvstTransDesc === "" || payInvstTransDesc === payInvstTransDesc1) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#payInvstTransDesc").addClass('rho-error');
        $("#payInvstTransDesc").attr("readonly",false);
        $("#fnlzeRvrslPayInvstTransBtn").attr("disabled",false);
    } else {
        $("#payInvstTransDesc").removeClass('rho-error');
    }
    if (rhotrim(errMsg,'; ') !== '') {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var msgsTitle = 'Fund Management Voucher';
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' + msgsTitle + '?<br/>This action cannot be UNDONE!</p>';
    } else {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">INITIATE REVERSAL</span> of this ' + msgsTitle + '?<br/>After submission you must come back here to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE the REVERSAL</span>!</p>';
    }
    var dialog = bootbox.confirm({
        title: 'Void ' + msgsTitle + '?',
        size: 'small',
        message: msgBody,
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
                var msg = 'Fund Management Voucher';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        sbmtdPayInvstTransID = typeof $("#sbmtdPayInvstTransID").val() === 'undefined' ? -1 : $("#sbmtdPayInvstTransID").val();
                        if (sbmtdPayInvstTransID > 0) {
                            getOnePayInvstTransForm(sbmtdPayInvstTransID,1,'ReloadDialog');
                        }
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
                                grp: 7,
                                typ: 1,
                                pg: 14,
                                actyp: 1,
                                q: 'VOID',
                                payInvstTransDesc: payInvstTransDesc,
                                sbmtdPayInvstTransID: sbmtdPayInvstTransID,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))) {
                                    obj = $.parseJSON(data);
                                    sbmtdPayInvstTransID = obj.sbmtdPayInvstTransID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdPayInvstTransID > 0) {
                                        $("#sbmtdPayInvstTransID").val(sbmtdPayInvstTransID);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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
        }
    });
}
//Loan Requests
function getPayTrnsRqsts(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#payTrnsRqstsSrchFor").val() === 'undefined' ? '%' : $("#payTrnsRqstsSrchFor").val();
    var srchIn = typeof $("#payTrnsRqstsSrchIn").val() === 'undefined' ? 'Both' : $("#payTrnsRqstsSrchIn").val();
    var pageNo = typeof $("#payTrnsRqstsPageNo").val() === 'undefined' ? 1 : $("#payTrnsRqstsPageNo").val();
    var limitSze = typeof $("#payTrnsRqstsDsplySze").val() === 'undefined' ? 10 : $("#payTrnsRqstsDsplySze").val();
    var sortBy = typeof $("#payTrnsRqstsSortBy").val() === 'undefined' ? '' : $("#payTrnsRqstsSortBy").val();
    var qShwUsrOnly = $('#payTrnsRqstsShwUsrOnly:checked').length > 0;
    var qShwUnpstdOnly = $('#payTrnsRqstsShwUnpstdOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'vious') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwUsrOnly=" + qShwUsrOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayTrnsRqsts(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayTrnsRqsts(actionText,slctr,linkArgs);
    }
}

function getOnePayTrnsRqstsForm(pKeyID,vwtype,actionTxt,payTrnsRqstsType) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof payTrnsRqstsType === 'undefined' || payTrnsRqstsType === null) {
        payTrnsRqstsType = 'LOAN';
    }
    var lnkArgs = 'grp=7&typ=1&pg=13&vtyp=' + vwtype + '&sbmtdPayTrnsRqstsID=' + pKeyID + '&payTrnsRqstsType=' + payTrnsRqstsType;
    doAjaxWthCallBck(lnkArgs,'myFormsModalxLG',actionTxt,'Transaction Request Details (ID:' + pKeyID + ')','myFormsModalxLGTitle','myFormsModalxLGBody',function () {
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
        }).on('hide',function (ev) {
            $('#myFormsModalxLG').css("overflow","auto");
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
        }).on('hide',function (ev) {
            $('#myFormsModalxLG').css("overflow","auto");
        });
        $('#allOtherInputData99').val('0');
        $('#onePayTrnsRqstsEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalxLG').off('hidden.bs.modal');
        $('#myFormsModalxLG').one('hidden.bs.modal',function (e) {
            getPayTrnsRqsts('','#allmodules','grp=7&typ=1&pg=13&vtyp=0');
            $(e.currentTarget).unbind();
        });
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
        shwHidePayTrnsFlds();
    });
}

function shwHidePayTrnsFlds() {
    var payTrnsRqstsType = typeof $("#payTrnsRqstsType").val() === 'undefined' ? 'LOAN' : $("#payTrnsRqstsType").val();
    var sbmtdPayTrnsAppCODE = typeof $("#sbmtdPayTrnsAppCODE").val() === 'undefined' ? 'RHOMICOM_MAIN_ERP_APP_1' : $("#sbmtdPayTrnsAppCODE").val();
    var payTrnsRqstsItmTypID = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsItmTypID").val();
    var payTrnsRqstsItmTyp = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '' : $("#payTrnsRqstsItmTypID").text();
    var payTrnsRqstsClsfctn = typeof $("#payTrnsRqstsClsfctn").val() === 'undefined' ? '' : $("#payTrnsRqstsClsfctn").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp',7);
        formData.append('typ',1);
        formData.append('pg',15);
        formData.append('q','VIEW');
        formData.append('vtyp',4);
        formData.append('payTrnsRqstsItmTypID',payTrnsRqstsItmTypID);
        formData.append('payTrnsRqstsType',payTrnsRqstsType);
        formData.append('payTrnsRqstsClsfctn',payTrnsRqstsClsfctn);
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
                    $("#payTrnsRqstsClsfctn").empty();
                    for (var i = 0; i < options.length; i++) {
                        var defaultSlctd = false;
                        var isSlctd = false;
                        if (payTrnsRqstsClsfctn === options[i]) {
                            defaultSlctd = true;
                            isSlctd = true;
                        }
                        var o = new Option(options[i],options[i],defaultSlctd,isSlctd);
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(o).html(options[i]);
                        $("#payTrnsRqstsClsfctn").append(o);
                    }
                    if (payTrnsRqstsType === 'SETTLEMENT') {
                        shwHidePayPrevLoans();
                    }
                }
            },
            error: function (jqXHR,textStatus,errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function shwHidePayPrevLoans() {
    var myPayTrnsRqstsStatusBtn = typeof $("#myPayTrnsRqstsStatusBtn").text() === 'undefined' ? '' : $("#myPayTrnsRqstsStatusBtn").text();
    var payTrnsRqstsType = typeof $("#payTrnsRqstsType").val() === 'undefined' ? 'LOAN' : $("#payTrnsRqstsType").val();
    var sbmtdPayTrnsAppCODE = typeof $("#sbmtdPayTrnsAppCODE").val() === 'undefined' ? 'RHOMICOM_MAIN_ERP_APP_1' : $("#sbmtdPayTrnsAppCODE").val();
    var payTrnsRqstsItmTypID = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsItmTypID").val();
    var payTrnsRqstsItmTyp = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '' : $("#payTrnsRqstsItmTypID").text();
    var lnkdPayTrnsRqstsID = typeof $("#lnkdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#lnkdPayTrnsRqstsID").val();
    var payTrnsRqstsPrsnID = typeof $("#payTrnsRqstsPrsnID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsPrsnID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp',7);
        formData.append('typ',1);
        formData.append('pg',15);
        formData.append('q','VIEW');
        formData.append('vtyp',5);
        formData.append('payTrnsRqstsItmTypID',payTrnsRqstsItmTypID);
        formData.append('payTrnsRqstsType',payTrnsRqstsType);
        formData.append('lnkdPayTrnsRqstsID',lnkdPayTrnsRqstsID);
        formData.append('payTrnsRqstsPrsnID',payTrnsRqstsPrsnID);
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
                    $("#lnkdPayTrnsRqstsID").empty();
                    for (var i = 0; i < options.length; i++) {
                        var defaultSlctd = false;
                        var isSlctd = false;
                        var nwOptions = options[i].split("|");
                        if (lnkdPayTrnsRqstsID === nwOptions[0]) {
                            defaultSlctd = true;
                            isSlctd = true;
                        }
                        var o = new Option(nwOptions[0],nwOptions[0],defaultSlctd,isSlctd);
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(o).html(nwOptions[1]);
                        $("#lnkdPayTrnsRqstsID").append(o);
                    }
                    var payTrnsRqstsAmnt = typeof $("#payTrnsRqstsAmnt").val() === 'undefined' ? '0' : $("#payTrnsRqstsAmnt").val();
                    if (Number(payTrnsRqstsAmnt.replace(/[^-?0-9\.]/g,'')) <= 0 &&
                        (myPayTrnsRqstsStatusBtn.indexOf('Not Submitted') !== -1 ||
                            myPayTrnsRqstsStatusBtn.indexOf('Withdrawn') !== -1 ||
                            myPayTrnsRqstsStatusBtn.indexOf('Rejected') !== -1)) {
                        var outDefaultAmnt = data.DefaultAmnt;
                        $("#payTrnsRqstsAmnt").val(outDefaultAmnt);
                        payTrnsRqstsAmnt = fmtAsNumber('payTrnsRqstsAmnt');
                    }
                }
            },
            error: function (jqXHR,textStatus,errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function getOnePayTrnsRqstsDocsForm(pKeyID,vwtype) {
    var lnkArgs = 'grp=7&typ=1&pg=13&vtyp=' + vwtype + '&sbmtdPayTrnsRqstsID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModaly','ShowDialog','Request Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        var table1 = $('#attchdTrnsRqstsDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdTrnsRqstsDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdTrnsRqstsDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToTrnsRqstsDocs(inptElmntID,attchIDElmntID,docNmElmntID,sbmtdHdrID,rowIDAttrb) {
    var docCtrgrName = $('#' + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    $("#" + inptElmntID).val('');
    $("#" + inptElmntID).off('change');
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToTrnsRqstsDocs(input.files[0],docNmElmntID,attchIDElmntID,sbmtdHdrID,function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) { }
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToTrnsRqstsDocs(file,docNmElmntID,attchIDElmntID,sbmtdHdrID,callBackFunc) {
    var data1 = new FormData();
    data1.append('daInvstTransAttchmnt',file);
    data1.append('grp',7);
    data1.append('typ',1);
    data1.append('pg',13);
    data1.append('q','UPDATE');
    data1.append('actyp',2);
    data1.append('docCtrgrName',$('#' + docNmElmntID).val());
    data1.append('attchmentID',$('#' + attchIDElmntID).val());
    data1.append('sbmtdPayTrnsRqstsID',sbmtdHdrID);
    var dialog1 = bootbox.alert({
        title: 'Uploading File...',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>'
    });
    dialog1.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
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
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getAttchdTrnsRqstsDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdTrnsRqstsDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdTrnsRqstsDocsSrchFor").val();
    var srchIn = typeof $("#attchdTrnsRqstsDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdTrnsRqstsDocsSrchIn").val();
    var pageNo = typeof $("#attchdTrnsRqstsDocsPageNo").val() === 'undefined' ? 1 : $("#attchdTrnsRqstsDocsPageNo").val();
    var limitSze = typeof $("#attchdTrnsRqstsDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdTrnsRqstsDocsDsplySze").val();
    var sortBy = typeof $("#attchdTrnsRqstsDocsSortBy").val() === 'undefined' ? '' : $("#attchdTrnsRqstsDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs,'myFormsModaly',actionDialog,'Transaction Request Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        if (!$.fn.DataTable.isDataTable('#attchdTrnsRqstsDocsTable')) {
            var table1 = $('#attchdTrnsRqstsDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdTrnsRqstsDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdTrnsRqstsDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdTrnsRqstsDocs(e,actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdTrnsRqstsDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdTrnsRqstsDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdPayTrnsRqstsID").val() === 'undefined' ? -1 : $("#sbmtdPayTrnsRqstsID").val();
    var docNum = typeof $("#payTrnsRqstsDocNum").val() === 'undefined' ? '' : $("#payTrnsRqstsDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdTrnsRqstsDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdTrnsRqstsDocsRow' + rndmNum + '_AttchdDocsID').val();
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
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: 'Delete Document?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 13,
                                    q: 'DELETE',
                                    actyp: 5,
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

function delPayTrnsRqsts(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_HdrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_HdrID').val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Transaction Request";
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPrt + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPrt + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPrt + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPrt + '...Please Wait...</p>',
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 13,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm
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

function savePayTrnsRqstsForm(funcur,shdSbmt) {
    var sbmtdPayTrnsRqstsID = typeof $("#sbmtdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#sbmtdPayTrnsRqstsID").val();
    var lnkdPayTrnsRqstsID = typeof $("#lnkdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#lnkdPayTrnsRqstsID").val();
    var payTrnsRqstsType = typeof $("#payTrnsRqstsType").val() === 'undefined' ? '' : $("#payTrnsRqstsType").val();
    var payTrnsRqstsPrsnID = typeof $("#payTrnsRqstsPrsnID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsPrsnID").val();
    var payTrnsRqstsPrsnNm = typeof $("#payTrnsRqstsPrsnNm").val() === 'undefined' ? '' : $("#payTrnsRqstsPrsnNm").val();
    var payTrnsRqstsItmTypID = typeof $("#payTrnsRqstsItmTypID").val() === 'undefined' ? '-1' : $("#payTrnsRqstsItmTypID").val();
    var payTrnsRqstsItmTypNm = typeof $("#payTrnsRqstsItmTypNm").val() === 'undefined' ? '' : $("#payTrnsRqstsItmTypNm").val();
    var payTrnsRqstsClsfctn = typeof $("#payTrnsRqstsClsfctn").val() === 'undefined' ? '' : $("#payTrnsRqstsClsfctn").val();
    var payTrnsRqstsDesc = typeof $("#payTrnsRqstsDesc").val() === 'undefined' ? '' : $("#payTrnsRqstsDesc").val();
    var payTrnsRqstsDate = typeof $("#payTrnsRqstsDate").val() === 'undefined' ? '' : $("#payTrnsRqstsDate").val();

    var payTrnsRqstsAmnt = typeof $("#payTrnsRqstsAmnt").val() === 'undefined' ? '0.00' : $("#payTrnsRqstsAmnt").val();
    var payTrnsRqstsHsAgreed = typeof $("input[name='payTrnsRqstsHsAgreed']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (Number(payTrnsRqstsPrsnID) <= 0 || Number(payTrnsRqstsItmTypID) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Requestor cannot be empty!</span></p>';
    }
    if (payTrnsRqstsClsfctn.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Classification cannot be empty!</span></p>';
    }
    if (payTrnsRqstsDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Remark/Narration cannot be empty!</span></p>';
    }
    payTrnsRqstsAmnt = fmtAsNumber('payTrnsRqstsAmnt').toFixed(2);
    if (payTrnsRqstsAmnt <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Amount cannot be zero or less!</span></p>';
    }
    var isVld = true;
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Transaction Request',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction Request...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',7);
    formData.append('typ',1);
    formData.append('pg',13);
    formData.append('q','UPDATE');
    formData.append('actyp',1);
    formData.append('sbmtdPayTrnsRqstsID',sbmtdPayTrnsRqstsID);
    formData.append('lnkdPayTrnsRqstsID',lnkdPayTrnsRqstsID);
    formData.append('payTrnsRqstsType',payTrnsRqstsType);
    formData.append('payTrnsRqstsPrsnID',payTrnsRqstsPrsnID);
    formData.append('payTrnsRqstsPrsnNm',payTrnsRqstsPrsnNm);
    formData.append('payTrnsRqstsItmTypID',payTrnsRqstsItmTypID);
    formData.append('payTrnsRqstsItmTypNm',payTrnsRqstsItmTypNm);
    formData.append('payTrnsRqstsClsfctn',payTrnsRqstsClsfctn);
    formData.append('payTrnsRqstsDesc',payTrnsRqstsDesc);
    formData.append('payTrnsRqstsDate',payTrnsRqstsDate);
    formData.append('payTrnsRqstsAmnt',payTrnsRqstsAmnt);
    formData.append('payTrnsRqstsHsAgreed',payTrnsRqstsHsAgreed);
    formData.append('shdSbmt',shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find('.bootbox-body').html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPayTrnsRqstsID = data.sbmtdPayTrnsRqstsID;
                            getOnePayTrnsRqstsForm(sbmtdPayTrnsRqstsID,1,'ReloadDialog');
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function actOnLoanRqst(acttype) {
    var pKeyID = typeof $("#sbmtdPayTrnsRqstsID").val() === 'undefined' ? '-1' : $("#sbmtdPayTrnsRqstsID").val();
    var dialog = bootbox.confirm({
        title: acttype + ' Request?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">' + acttype + '</span> this Request?<br/>Action cannot be Undone!</p>',
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
                    title: acttype + ' Request?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Acting on Request...Please Wait...</p>',
                    callback: function () {
                        if (pKeyID > 0) {
                            openATab('#allmodules','grp=7&typ=1&pg=13&vtyp=0');
                            getOnePayTrnsRqstsForm(pKeyID,1,'ReloadDialog');
                        }
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
                                    grp: 7,
                                    typ: 1,
                                    pg: 13,
                                    q: 'UPDATE',
                                    actyp: 40,
                                    actiontyp: acttype,
                                    sbmtdPayTrnsRqstsID: pKeyID
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find('.bootbox-body').html(errorThrown1);
                                }
                            });
                        });
                    } else {
                        setTimeout(function () {
                            dialog1.find('.bootbox-body').html('<span style="color:red;">Nothing to Act On!</span>');
                        },500);
                    }
                });
            }
        }
    });
}

function afterBulkRnItmSet() {

    var payMassPyItmSetNm = typeof $('#payMassPyItmSetNm').val() === 'undefined' ? '' : $('#payMassPyItmSetNm').val();
    var payMassPyDesc = typeof $('#payMassPyDesc').val() === 'undefined' ? '' : $('#payMassPyDesc').val();
    if (payMassPyDesc.trim() === '') {
        $('#payMassPyDesc').val('Payment of ' + payMassPyItmSetNm);
    }
}