var fsrptTable1;
var msPytable1;
var scmInvItmtable1;

function prepareInvAdmin(lnkArgs, htBody, targ, rspns) {
    if (lnkArgs === 'grp=12&typ=1&pg=9&vtyp=10' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=0' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=20' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=30' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=40' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=50' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=60' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=70' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=80' ||
        lnkArgs === 'grp=12&typ=1&pg=9&vtyp=90') {
        shdHideFSRpt = 0;
    }
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("grp=12&") !== -1) {
            if (lnkArgs.indexOf("&pg=1&vtyp=1") !== -1 || lnkArgs.indexOf("&pg=1&vtyp=3") !== -1) {
                $('#allOtherInputData99').val('0');
                $('#oneScmSalesInvcEDTForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
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
                    var linkArgs = 'grp=12&typ=1' + dttrgt;
                    $(targ + 'tab').tab('show');
                    if (targ.indexOf('salesInvcExtraInfo') >= 0) {
                        $('#addNwScmSalesInvcSmryBtn').addClass('hideNotice');
                        $('#addNwScmSalesInvcTaxBtn').addClass('hideNotice');
                        $('#addNwScmSalesInvcDscntBtn').addClass('hideNotice');
                        $('#addNwScmSalesInvcChrgBtn').addClass('hideNotice');
                        $('#addNwScmSalesInvcPrepayBtn').addClass('hideNotice');
                    } else {
                        $('#addNwScmSalesInvcSmryBtn').removeClass('hideNotice');
                        $('#addNwScmSalesInvcTaxBtn').removeClass('hideNotice');
                        $('#addNwScmSalesInvcDscntBtn').removeClass('hideNotice');
                        $('#addNwScmSalesInvcChrgBtn').removeClass('hideNotice');
                        $('#addNwScmSalesInvcPrepayBtn').removeClass('hideNotice');
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
                $("#scmSalesInvcTndrdAmnt").off('focus');
                $("#scmSalesInvcTndrdAmnt").focus(function () {
                    $(this).select();
                });
                $('#oneScmSalesInvcSmryLinesTable tr').off('click');
                $('#oneScmSalesInvcSmryLinesTable tr').click(function () {
                    var rowIndex = $('#oneScmSalesInvcSmryLinesTable tr').index(this);
                    $('#allOtherInputData99').val(rowIndex);
                });
                calcAllScmSalesInvcSmryTtl(1);
                autoCreateSalesLns = -1;
                var scmSalesInvcApprvlStatus = typeof $("#scmSalesInvcApprvlStatus").val() === 'undefined' ? '' : $("#scmSalesInvcApprvlStatus").val();
                if (scmSalesInvcApprvlStatus !== "Not Validated") {
                    $("#scmSalesInvcTndrdAmnt").focus();
                } else {
                    $("#oneScmSalesInvcSmryLinesTable tr:nth-of-type(1) .jbDetDesc").focus();
                }
            } else if (lnkArgs.indexOf("&pg=1&vtyp=2") !== -1) {
                if (!$.fn.DataTable.isDataTable('#scmSalesInvcHdrsTable')) {
                    var table1 = $('#scmSalesInvcHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#scmSalesInvcHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#scmSalesInvcForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#scmPrchsDocHdrsTable')) {
                    var table1 = $('#scmPrchsDocHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#scmPrchsDocHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#scmPrchsDocForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#allINVItmsTable')) {
                    var table1 = $('#allINVItmsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allINVItmsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allINVItmsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=3&vtyp=4") !== -1) {
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
                    var qCnsgnOnly = $('#scmSalesInvItmsShwCnsgnOnly:checked').length > 0;
                    var sbmtdCallBackFunc = typeof $("#sbmtdCallBackFunc").val() === 'undefined' ? 'function(){var a=1;}' : $("#sbmtdCallBackFunc").val();
                    var sbmtdRowIDAttrb = typeof $("#sbmtdRowIDAttrb").val() === 'undefined' ? '' : $("#sbmtdRowIDAttrb").val();
                    var sbmtdDocType = typeof $("#sbmtdDocType").val() === 'undefined' ? '' : $("#sbmtdDocType").val();
                    if (sbmtdDocType.indexOf("Receipt") !== -1) {
                        applySlctdSalesInvItms(sbmtdRowIDAttrb, qCnsgnOnly, 'oneScmCnsgnRcptSmryLinesTable', sbmtdCallBackFunc);
                    } else if (sbmtdDocType.indexOf("Stock") !== -1) {
                        applySlctdSalesInvItms(sbmtdRowIDAttrb, qCnsgnOnly, 'oneScmStockTrnsfrSmryLinesTable', sbmtdCallBackFunc);
                    } else if (sbmtdDocType.indexOf("Purchase") !== -1) {
                        applySlctdSalesInvItms(sbmtdRowIDAttrb, qCnsgnOnly, 'oneScmPrchsDocSmryLinesTable', sbmtdCallBackFunc);
                    } else {
                        applySlctdSalesInvItms(sbmtdRowIDAttrb, qCnsgnOnly, 'oneScmSalesInvcSmryLinesTable', sbmtdCallBackFunc);
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

                $('#myFormsModalLx').off('hidden.bs.modal');
                $('#myFormsModalLx').one('hidden.bs.modal', function (e) {
                    $('#myFormsModalLxTitle').html('');
                    $('#myFormsModalLxBody').html('');
                    /*callBackFunc();*/
                    $(e.currentTarget).unbind();
                });
            } else if (lnkArgs.indexOf("&pg=4&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#invPrdtCtgryTable')) {
                    msPytable1 = $('#invPrdtCtgryTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#invPrdtCtgryTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#invPrdtCtgryForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                $('#invPrdtCtgryTable tbody').off('click');
                $('#invPrdtCtgryTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        msPytable1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pkeyID = typeof $('#invPrdtCtgryRow' + rndmNum + '_CtgryID').val() === 'undefined' ? '-1' : $('#invPrdtCtgryRow' + rndmNum + '_CtgryID').val();
                    getOneInvPrdtCtgryForm(pkeyID, 0);
                });
                $('#invPrdtCtgryTable tbody')
                    .off('mouseenter', 'tr');
                $('#invPrdtCtgryTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            msPytable1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });

                $('[data-toggle="tooltip"]').tooltip();
            } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#allStoresWhsTable')) {
                    var table1 = $('#allStoresWhsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allStoresWhsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allStoresWhsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=6&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#scmCnsgnRcptHdrsTable')) {
                    var table1 = $('#scmCnsgnRcptHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#scmCnsgnRcptHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#scmCnsgnRcptForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=7&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#scmCnsgnRtrnHdrsTable')) {
                    var table1 = $('#scmCnsgnRtrnHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#scmCnsgnRtrnHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#scmCnsgnRtrnForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=8&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#allItmTmpltsTable')) {
                    var table1 = $('#allItmTmpltsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allItmTmpltsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allItmTmpltsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=9&vtyp=10") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=20") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=30") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=40") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=50") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=60") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=70") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=80") !== -1 ||
                lnkArgs.indexOf("&pg=9&vtyp=90") !== -1) {
                if (!$.fn.DataTable.isDataTable('#accbFSRptTable')) {
                    fsrptTable1 = $('#accbFSRptTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#accbFSRptTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#accbFSRptForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                $('#accbFSRptShwSmmry').off('click');
                $('#accbFSRptShwSmmry').on('click', function (event) {
                    var accbFSRptShwSmmry = $('#accbFSRptShwSmmry:checked').length > 0 ? "YES" : "NO";
                    if (accbFSRptShwSmmry === "YES") {
                        $("#accbFSRptMaxAcntLvl").val(1);
                    } else {
                        $("#accbFSRptMaxAcntLvl").val(100);
                    }
                });
                $('#accbFSRptTable tbody').off('dblclick');
                $('#accbFSRptTable tbody').on('dblclick', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        fsrptTable1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pkeyID = typeof $('#oneAccbFSRptRow' + rndmNum + '_AccountID').val() === 'undefined' ? '-1' : $('#oneAccbFSRptRow' + rndmNum + '_AccountID').val();
                    var pkeyID2 = typeof $('#oneAccbFSRptRow' + rndmNum + '_TransLineID').val() === 'undefined' ? '-1' : $('#oneAccbFSRptRow' + rndmNum + '_TransLineID').val();
                    var pIsParent = typeof $('#oneAccbFSRptRow' + rndmNum + '_IsParent').val() === 'undefined' ? '0' : $('#oneAccbFSRptRow' + rndmNum + '_IsParent').val();
                    var pAccntNum = typeof $('#oneAccbFSRptRow' + rndmNum + '_AccntNum').val() === 'undefined' ? '0' : $('#oneAccbFSRptRow' + rndmNum + '_AccntNum').val();
                    var p_TrnsDate = typeof $('#oneAccbFSRptRow' + rndmNum + '_TrnsDate').val() === 'undefined' ? accbStrtFSRptDte : $('#oneAccbFSRptRow' + rndmNum + '_TrnsDate').val();
                    var accbFSRptDte = typeof $("#accbFSRptDte").val() === 'undefined' ? '' : $("#accbFSRptDte").val();
                    var accbStrtFSRptDte = typeof $("#accbStrtFSRptDte").val() === 'undefined' ? '' : $("#accbStrtFSRptDte").val();
                    /*if (pIsParent === '1' && Number(pkeyID2) <= -1) {
                     getAccbFSRptRpts(1, '#allmodules', lnkArgs, pkeyID);
                     } else if (lnkArgs.indexOf("&pg=5&vtyp=80") !== -1) {
                     getAccbTransSrchDet(pAccntNum, 'Account Number', true, true, p_TrnsDate.substring(0, 11), p_TrnsDate.substring(0, 11), 'Breakdown of Account Transactions', 'ShowDialog', function () {});
                     } else if (!(lnkArgs.indexOf("&pg=5&vtyp=50") !== -1) && !(lnkArgs.indexOf("&pg=19&vtyp=0") !== -1)
                     && !(lnkArgs.indexOf("&pg=5&vtyp=90") !== -1)) {
                     getAccbTransSrchDet(pAccntNum, 'Account Number', true, true, accbStrtFSRptDte, accbFSRptDte, 'Breakdown of Account Transactions', 'ShowDialog', function () {});
                     } else if (Number(pkeyID2) > 0) {
                     getAccbCashBreakdown(pkeyID2, 'ShowDialog', 'Transaction Amount Breakdown', 'VIEW', 'Transaction Amount Breakdown Parameters', '', '');
                     }*/
                });
                $('#accbFSRptTable tbody')
                    .off('mouseenter', 'tr');
                $('#accbFSRptTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            fsrptTable1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
                if (shdHideFSRpt >= 1) {
                    shwHideFSRptDivs('hide');
                }
                if (lnkArgs.indexOf("&pg=19&vtyp=0") !== -1 ||
                    lnkArgs.indexOf("&pg=5&vtyp=10") !== -1 ||
                    lnkArgs.indexOf("&pg=5&vtyp=90") !== -1) {
                    $('[data-toggle="tabajxaccrcncl"]').off('click');
                    $('[data-toggle="tabajxaccrcncl"]').click(function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        var targ = $this.attr('href');
                        var dttrgt = $this.attr('data-rhodata');
                        var linkArgs = 'grp=6&typ=1' + dttrgt;
                        $(targ + 'tab').tab('show');
                    });
                }
            } else if (lnkArgs.indexOf("&pg=10&vtyp=0") !== -1) {

                if (!$.fn.DataTable.isDataTable('#invUOMStpTable')) {
                    msPytable1 = $('#invUOMStpTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#invUOMStpTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#invUOMStpForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                $('#invUOMStpTable tbody').off('click');
                $('#invUOMStpTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        msPytable1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pkeyID = typeof $('#invUOMStpRow' + rndmNum + '_UOMStpID').val() === 'undefined' ? '-1' : $('#invUOMStpRow' + rndmNum + '_UOMStpID').val();
                    getOneInvUOMStpForm(pkeyID, 0);
                });
                $('#invUOMStpTable tbody')
                    .off('mouseenter', 'tr');
                $('#invUOMStpTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            msPytable1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });

                $('[data-toggle="tooltip"]').tooltip();
            } else if (lnkArgs.indexOf("&pg=11&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#scmStockTrnsfrHdrsTable')) {
                    var table1 = $('#scmStockTrnsfrHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#scmStockTrnsfrHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#scmStockTrnsfrForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=13&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#allINVGLIntrfcsHdrsTable')) {
                    var table1 = $('#allINVGLIntrfcsHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#allINVGLIntrfcsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#allINVGLIntrfcsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=14&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#scmPrdctCrtnHdrsTable')) {
                    var table1 = $('#scmPrdctCrtnHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#scmPrdctCrtnHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#scmPrdctCrtnForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            }
        } else if (lnkArgs.indexOf("grp=18&") !== -1) {
            if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1) {
                $('#hotlsmrydshbrdForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                const menu = document.querySelector(".menu1");
                var menuVisible = false;

                const toggleMenu = function (command) {
                    menu.style.display = (command === "show") ? "block" : "none";
                    menuVisible = (command === "show") ? true : false;
                };

                const setPosition = function (origin) {
                    menu.style.left = ",px".replace(/,/g, origin.left);
                    menu.style.top = ",px".replace(/,/g, origin.top);
                    toggleMenu("show");
                };

                window.removeEventListener("click", function (e) {
                    if (menuVisible) {
                        toggleMenu("hide");
                    }
                });
                window.addEventListener("click", function (e) {
                    if (menuVisible) {
                        toggleMenu("hide");
                    }
                });
                const divBtns = document.getElementsByClassName("mycntxtmenu");
                for (var i = 0; i < divBtns.length; i++) {
                    var divBtn = divBtns[i];
                    divBtn.addEventListener("click", function (e) {
                        if (menuVisible) {
                            toggleMenu("hide");
                        }
                    });

                    divBtn.addEventListener("contextmenu", function (e) {
                        e.preventDefault();
                        var w = window.innerWidth || document.body.clientWidth || document.documentElement.clientWidth;
                        var h = window.innerHeight || document.body.clientHeight || document.documentElement.clientHeight;
                        var scrOfY = window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop;
                        var scrOfX = window.pageXOffset || document.body.scrollLeft || document.documentElement.scrollLeft;
                        if ((w - e.pageX) < 300) {
                            w = e.pageX - 300 - scrOfX;
                        } else {
                            w = e.pageX;
                        }
                        if ((h - e.pageY) < 300) {
                            h = e.pageY - 180 - scrOfY;
                        } else {
                            h = e.pageY;
                        }
                        var origin = {
                            left: w,
                            top: h
                        };
                        setPosition(origin);
                        return false;
                    });
                }
            } else if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1 ||
                lnkArgs.indexOf("&pg=4&vtyp=0") !== -1 ||
                lnkArgs.indexOf("&pg=5&vtyp=0") !== -1) {
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
            } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1) {
                var table1;
                if (!$.fn.DataTable.isDataTable('#hotlSrvsTypTable')) {
                    table1 = $('#hotlSrvsTypTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#hotlSrvsTypTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneHotlSrvsTypSmryLinesTable')) {
                    var table2 = $('#oneHotlSrvsTypSmryLinesTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneHotlSrvsTypSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneHotlSrvsTypPricesTable')) {
                    var table2 = $('#oneHotlSrvsTypPricesTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneHotlSrvsTypPricesTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#hotlSrvsTypForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                $('#hotlSrvsTypTable tbody').off('click');
                $('#hotlSrvsTypTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pkeyID = typeof $('#hotlSrvsTypRow' + rndmNum + '_SrvsTypID').val() === 'undefined' ? '-1' : $('#hotlSrvsTypRow' + rndmNum + '_SrvsTypID').val();
                    getOneHotlSrvsTypForm(pkeyID, 1);
                });
                $('#hotlSrvsTypTable tbody')
                    .off('mouseenter', 'tr');
                $('#hotlSrvsTypTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });

                $('[data-toggle="tabajxfcltytyp"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=18&typ=1' + dttrgt;
                    $(targ + 'tab').tab('show');
                    if (targ.indexOf('hotlSrvsTypExtraInfo') >= 0) {
                        $('#addNwScmHotlSrvsTypSmryBtn').addClass('hideNotice');
                        $('#addNwScmHotlSrvsTypPriceBtn').removeClass('hideNotice');
                        $('.fcltyTypDetNav').addClass('hideNotice');
                    } else {
                        $('#addNwScmHotlSrvsTypPriceBtn').addClass('hideNotice');
                        $('#addNwScmHotlSrvsTypSmryBtn').removeClass('hideNotice');
                        $('.fcltyTypDetNav').removeClass('hideNotice');
                    }
                });
            } else if (lnkArgs.indexOf("&pg=3&vtyp=2") !== -1) {
                var table1;
                if (!$.fn.DataTable.isDataTable('#oneHotlSrvsTypSmryLinesTable')) {
                    var table2 = $('#oneHotlSrvsTypSmryLinesTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneHotlSrvsTypSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#hotlSrvsTypForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=6&vtyp=0") !== -1) {
                var table1;
                if (!$.fn.DataTable.isDataTable('#hotlComplntsHdrsTable')) {
                    var table2 = $('#hotlComplntsHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#hotlComplntsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#hotlComplntsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            } else if (lnkArgs.indexOf("&pg=7&vtyp=0") !== -1) {
                if (!$.fn.DataTable.isDataTable('#hotlChckinDocHdrsTable')) {
                    var table1 = $('#hotlChckinDocHdrsTable').DataTable({
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
            }
        }
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
        $(".acbBrkdwnDesc").focus(function () {
            $(this).select();
        });
        $(".acbBrkdwnQTY1").focus(function () {
            $(this).select();
        });
        $(".acbBrkdwnQTY2").focus(function () {
            $(this).select();
        });
        $(".acbBrkdwnUVl").focus(function () {
            $(this).select();
        });
        $(".acbBrkdwnTtl").focus(function () {
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
        htBody.removeClass("mdlloading");
    });
}

function getAllINVItms(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#allINVItmsSrchFor").val() === 'undefined' ? '%' : $("#allINVItmsSrchFor").val();
    var srchIn = typeof $("#allINVItmsSrchIn").val() === 'undefined' ? 'Both' : $("#allINVItmsSrchIn").val();
    var pageNo = typeof $("#allINVItmsPageNo").val() === 'undefined' ? 1 : $("#allINVItmsPageNo").val();
    var limitSze = typeof $("#allINVItmsDsplySze").val() === 'undefined' ? 10 : $("#allINVItmsDsplySze").val();
    var sortBy = typeof $("#allINVItmsSortBy").val() === 'undefined' ? '' : $("#allINVItmsSortBy").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllINVItms(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllINVItms(actionText, slctr, linkArgs);
    }
}

function getOneINVItmsForm(pKeyID, actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=12&typ=1&pg=3&vtyp=1&sbmtdItmID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Item (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#storeItmStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            $('[data-toggle="tabajxinvitms"]').off('click');
            $('[data-toggle="tabajxinvitms"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                if (targ.indexOf('invItmsGnrl') >= 0) {
                    $('#invItmsGnrl').removeClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsGl') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').removeClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsStores') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').removeClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsExtInfo') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').removeClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsUOM') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').removeClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsDrugLbl') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').removeClass('hideNotice');
                    $('#invItmsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('invItmsDrugIntrctns') >= 0) {
                    $('#invItmsGnrl').addClass('hideNotice');
                    $('#invItmsGl').addClass('hideNotice');
                    $('#invItmsStores').addClass('hideNotice');
                    $('#invItmsExtInfo').addClass('hideNotice');
                    $('#invItmsUOM').addClass('hideNotice');
                    $('#invItmsDrugLbl').addClass('hideNotice');
                    $('#invItmsDrugIntrctns').removeClass('hideNotice');
                }
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            getAllINVItms('', '#allmodules', 'grp=12&typ=1&pg=3&vtyp=0');
            $(e.currentTarget).unbind();
        });
    });
}

function getOneINVItmPricesForm(rowIDAttrb, actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var storeID = -1;
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
        storeID = typeof $('#' + rowPrfxNm + rndmNum + '_StoreID').val() === 'undefined' ? -1 : $('#' + rowPrfxNm + rndmNum + '_StoreID').val();
    }
    var lnkArgs = 'grp=12&typ=1&pg=3&vtyp=101&sbmtdItmID=' + pKeyID + '&rowIDAttrb=' + rowIDAttrb;
    doAjaxWthCallBck(lnkArgs, 'myFormsModal', actionTxt, 'Item (ID:' + pKeyID + ')', 'myFormsModalTitle', 'myFormsModalBody', function () {
        $('#storeItmStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('[data-toggle="tabajxinvitms"]').off('click');
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModal').off('hidden.bs.modal');
        $('#myFormsModal').one('hidden.bs.modal', function (e) {
            $('#myFormsModalTitle').html('');
            $('#myFormsModalBody').html('');
            if (rowPrfxNm === 'allINVItmsRow') {
                getAllINVItms('clear', '#allmodules', 'grp=12&typ=1&pg=3&vtyp=0');
            }
            $(e.currentTarget).unbind();
        });
    });
}

function saveINVItmsForm() {
    var sbmtdItmID = typeof $("#sbmtdItmID").val() === 'undefined' ? '-1' : $("#sbmtdItmID").val();
    var invItemNm = typeof $("#invItemNm").val() === 'undefined' ? '' : $("#invItemNm").val();
    var invItemDesc = typeof $("#invItemDesc").val() === 'undefined' ? '' : $("#invItemDesc").val();
    var invTmpltID = typeof $("#invTmpltID").val() === 'undefined' ? '-1' : $("#invTmpltID").val();
    var invItemType = typeof $("#invItemType").val() === 'undefined' ? '' : $("#invItemType").val();
    var invItemCtgry = typeof $("#invItemCtgry").val() === 'undefined' ? '' : $("#invItemCtgry").val();
    var invItemCtgryID = typeof $("#invItemCtgryID").val() === 'undefined' ? '-1' : $("#invItemCtgryID").val();
    var invBaseUom = typeof $("#invBaseUom").val() === 'undefined' ? '' : $("#invBaseUom").val();
    var invBaseUomID = typeof $("#invBaseUomID").val() === 'undefined' ? '-1' : $("#invBaseUomID").val();
    var invTxCodeName = typeof $("#invTxCodeName").val() === 'undefined' ? '' : $("#invTxCodeName").val();
    var invTxCodeID = typeof $("#invTxCodeID").val() === 'undefined' ? '-1' : $("#invTxCodeID").val();
    var invDscntCodeName = typeof $("#invDscntCodeName").val() === 'undefined' ? '' : $("#invDscntCodeName").val();
    var invDscntCodeID = typeof $("#invDscntCodeID").val() === 'undefined' ? '-1' : $("#invDscntCodeID").val();
    var invChrgCodeName = typeof $("#invChrgCodeName").val() === 'undefined' ? '' : $("#invChrgCodeName").val();
    var invChrgCodeID = typeof $("#invChrgCodeID").val() === 'undefined' ? '-1' : $("#invChrgCodeID").val();
    var isItmEnbld = typeof $("input[name='isItmEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var isPlnngEnbld = typeof $("input[name='isPlnngEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var autoLoadInVMS = typeof $("input[name='autoLoadInVMS']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var invMinItmQty = typeof $("#invMinItmQty").val() === 'undefined' ? '0' : $("#invMinItmQty").val();
    var invMaxItmQty = typeof $("#invMaxItmQty").val() === 'undefined' ? '0' : $("#invMaxItmQty").val();
    var invValCrncyNm = typeof $("#invValCrncyNm").val() === 'undefined' ? '' : $("#invValCrncyNm").val();
    var invValCrncyID = typeof $("#invValCrncyID").val() === 'undefined' ? '-1' : $("#invValCrncyID").val();
    var invPriceLessTax = typeof $("#invPriceLessTax").val() === 'undefined' ? '0' : $("#invPriceLessTax").val();
    var invSllngPrice = typeof $("#invSllngPrice").val() === 'undefined' ? '0' : $("#invSllngPrice").val();
    var invNwPrftAmnt = typeof $("#invNwPrftAmnt").val() === 'undefined' ? '0' : $("#invNwPrftAmnt").val();
    var invNewSllngPrice = typeof $("#invNewSllngPrice").val() === 'undefined' ? '0' : $("#invNewSllngPrice").val();
    var invPrftMrgnPrcnt = typeof $("#invPrftMrgnPrcnt").val() === 'undefined' ? '0' : $("#invPrftMrgnPrcnt").val();
    var invPrftMrgnAmnt = typeof $("#invPrftMrgnAmnt").val() === 'undefined' ? '0' : $("#invPrftMrgnAmnt").val();
    var invAssetAcntNm = typeof $("#invAssetAcntNm").val() === 'undefined' ? '' : $("#invAssetAcntNm").val();
    var invAssetAcntID = typeof $("#invAssetAcntID").val() === 'undefined' ? '-1' : $("#invAssetAcntID").val();
    var invCogsAcntID = typeof $("#invCogsAcntID").val() === 'undefined' ? '-1' : $("#invCogsAcntID").val();
    var invCogsAcntNm = typeof $("#invCogsAcntNm").val() === 'undefined' ? '' : $("#invCogsAcntNm").val();
    var invSRvnuAcntID = typeof $("#invSRvnuAcntID").val() === 'undefined' ? '-1' : $("#invSRvnuAcntID").val();
    var invSRvnuAcntNm = typeof $("#invSRvnuAcntNm").val() === 'undefined' ? '' : $("#invSRvnuAcntNm").val();
    var invSRetrnAcntID = typeof $("#invSRetrnAcntID").val() === 'undefined' ? '-1' : $("#invSRetrnAcntID").val();
    var invSRetrnAcntNm = typeof $("#invSRetrnAcntNm").val() === 'undefined' ? '' : $("#invSRetrnAcntNm").val();
    var invPRetrnAcntID = typeof $("#invPRetrnAcntID").val() === 'undefined' ? '-1' : $("#invPRetrnAcntID").val();
    var invPRetrnAcntNm = typeof $("#invPRetrnAcntNm").val() === 'undefined' ? '' : $("#invPRetrnAcntNm").val();
    var invExpnsAcntID = typeof $("#invExpnsAcntID").val() === 'undefined' ? '-1' : $("#invExpnsAcntID").val();
    var invExpnsAcntNm = typeof $("#invExpnsAcntNm").val() === 'undefined' ? '' : $("#invExpnsAcntNm").val();
    var invItmOthrDesc = typeof $("#invItmOthrDesc").val() === 'undefined' ? '' : $("#invItmOthrDesc").val();
    var invItmExtrInfo = typeof $("#invItmExtrInfo").val() === 'undefined' ? '' : $("#invItmExtrInfo").val();
    var invItmGnrcNm = typeof $("#invItmGnrcNm").val() === 'undefined' ? '' : $("#invItmGnrcNm").val();
    var invItmTradeNm = typeof $("#invItmTradeNm").val() === 'undefined' ? '' : $("#invItmTradeNm").val();
    var invItmUslDsge = typeof $("#invItmUslDsge").val() === 'undefined' ? '' : $("#invItmUslDsge").val();
    var invItmMaxDsge = typeof $("#invItmMaxDsge").val() === 'undefined' ? '' : $("#invItmMaxDsge").val();
    var invItmCntrIndctns = typeof $("#invItmCntrIndctns").val() === 'undefined' ? '' : $("#invItmCntrIndctns").val();
    var invItmFoodIntrctns = typeof $("#invItmFoodIntrctns").val() === 'undefined' ? '' : $("#invItmFoodIntrctns").val();
    var errMsg = "";
    if (invItemNm.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item name cannot be empty!</span></p>';
    }
    if (invItemDesc.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item Description cannot be empty!</span></p>';
    }
    if (Number(invItemCtgryID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Category cannot be empty!</span></p>';
    }
    if (Number(invBaseUomID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Base UOM cannot be empty!</span></p>';
    }
    if (Number(invValCrncyID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item\'s Value Currency cannot be empty!</span></p>';
    }
    if (Number(invAssetAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Asset Account cannot be empty!</span></p>';
    }
    if (Number(invSRetrnAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Sales Return Account cannot be empty!</span></p>';
    }
    if (Number(invSRvnuAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Sales Revenue Account cannot be empty!</span></p>';
    }
    if (Number(invPRetrnAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Purchase Return Account cannot be empty!</span></p>';
    }
    if (Number(invExpnsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">GL Account cannot be empty!</span></p>';
    }
    if (Number(invCogsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cost of Sales Account cannot be empty!</span></p>';
    }
    var slctdItmStores = "";
    var slctdItmUOMs = "";
    var slctdItmIntrctns = "";
    var isVld = true;
    $('#oneItmStoresTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_StockID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var lnStoreID = typeof $('#' + rowPrfxNm + rndmNum + '_StoreID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_StoreID').val();
                    if (Number(lnStoreID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        /*Do Nothing*/
                    } else {
                        if (isVld === true) {
                            slctdItmStores = slctdItmStores + $('#' + rowPrfxNm + rndmNum + '_StockID').val() + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_StoreID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_ShlvNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_ShlvIDs').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_StrtDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_EndDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    $('#oneItmUOMsTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var lnUomID = typeof $('#' + rowPrfxNm + rndmNum + '_UOMID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_UOMID').val();
                    if (Number(lnUomID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        /*Do Nothing*/
                    } else {
                        if (isVld === true) {
                            slctdItmUOMs = slctdItmUOMs + $('#' + rowPrfxNm + rndmNum + '_LineID').val() + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_UOMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_CnvrsnFctr').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_SortOrdr').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_SllgnPrce').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_PriceLsTx').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    $('#oneItmDrugIntrctnsTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var lnDrugID = typeof $('#' + rowPrfxNm + rndmNum + '_DrugID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_DrugID').val();
                    if (Number(lnDrugID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        /*Do Nothing*/
                    } else {
                        var lnDrgIntrctn = typeof $('#' + rowPrfxNm + rndmNum + '_Intrctn').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_Intrctn').val();
                        if (lnDrgIntrctn.trim() === "") {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Intrctn').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Intrctn').removeClass('rho-error');
                        }
                        var lnDrgActn = typeof $('#' + rowPrfxNm + rndmNum + '_Action').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_Action').val();
                        if (lnDrgActn.trim() === "") {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_Action').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_Action').removeClass('rho-error');
                        }
                        if (isVld === true) {
                            slctdItmIntrctns = slctdItmIntrctns + $('#' + rowPrfxNm + rndmNum + '_LineID').val() + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_DrugID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_Intrctn').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_Action').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Item',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Item...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneINVItmsForm(sbmtdItmID, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('daItemPicture', $('#daItemPicture')[0].files[0]);
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 3);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdItmID', sbmtdItmID);
    formData.append('invItemNm', invItemNm);
    formData.append('invItemDesc', invItemDesc);
    formData.append('invTmpltID', invTmpltID);
    formData.append('invItemType', invItemType);
    formData.append('invItemCtgryID', invItemCtgryID);
    formData.append('invBaseUomID', invBaseUomID);
    formData.append('invTxCodeID', invTxCodeID);
    formData.append('invDscntCodeID', invDscntCodeID);
    formData.append('invChrgCodeID', invChrgCodeID);
    formData.append('isItmEnbld', isItmEnbld);
    formData.append('isPlnngEnbld', isPlnngEnbld);
    formData.append('autoLoadInVMS', autoLoadInVMS);
    formData.append('invMinItmQty', invMinItmQty);
    formData.append('invMaxItmQty', invMaxItmQty);
    formData.append('invValCrncyID', invValCrncyID);
    formData.append('invPriceLessTax', invPriceLessTax);
    formData.append('invSllngPrice', invSllngPrice);
    formData.append('invNwPrftAmnt', invNwPrftAmnt);
    formData.append('invNewSllngPrice', invNewSllngPrice);
    formData.append('invPrftMrgnPrcnt', invPrftMrgnPrcnt);
    formData.append('invPrftMrgnAmnt', invPrftMrgnAmnt);
    formData.append('invAssetAcntID', invAssetAcntID);
    formData.append('invCogsAcntID', invCogsAcntID);
    formData.append('invSRvnuAcntID', invSRvnuAcntID);
    formData.append('invSRetrnAcntID', invSRetrnAcntID);
    formData.append('invPRetrnAcntID', invPRetrnAcntID);
    formData.append('invExpnsAcntID', invExpnsAcntID);
    formData.append('invItmOthrDesc', invItmOthrDesc);
    formData.append('invItmExtrInfo', invItmExtrInfo);
    formData.append('invItmGnrcNm', invItmGnrcNm);
    formData.append('invItmTradeNm', invItmTradeNm);
    formData.append('invItmUslDsge', invItmUslDsge);
    formData.append('invItmMaxDsge', invItmMaxDsge);
    formData.append('invItmCntrIndctns', invItmCntrIndctns);
    formData.append('invItmFoodIntrctns', invItmFoodIntrctns);
    formData.append('slctdItmStores', slctdItmStores);
    formData.append('slctdItmUOMs', slctdItmUOMs);
    formData.append('slctdItmIntrctns', slctdItmIntrctns);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdItmID = result.itemid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveINVItmPricesForm(rowIDAttrb) {
    var sbmtdItmID = typeof $("#sbmtdItmID").val() === 'undefined' ? '-1' : $("#sbmtdItmID").val();
    var invItemNm = typeof $("#invItemNm").val() === 'undefined' ? '' : $("#invItemNm").val();
    var invItemDesc = typeof $("#invItemDesc").val() === 'undefined' ? '' : $("#invItemDesc").val();
    var invTxCodeName = typeof $("#invTxCodeName").val() === 'undefined' ? '' : $("#invTxCodeName").val();
    var invTxCodeID = typeof $("#invTxCodeID").val() === 'undefined' ? '-1' : $("#invTxCodeID").val();
    var invDscntCodeName = typeof $("#invDscntCodeName").val() === 'undefined' ? '' : $("#invDscntCodeName").val();
    var invDscntCodeID = typeof $("#invDscntCodeID").val() === 'undefined' ? '-1' : $("#invDscntCodeID").val();
    var invChrgCodeName = typeof $("#invChrgCodeName").val() === 'undefined' ? '' : $("#invChrgCodeName").val();
    var invChrgCodeID = typeof $("#invChrgCodeID").val() === 'undefined' ? '-1' : $("#invChrgCodeID").val();
    var invValCrncyNm = typeof $("#invValCrncyNm").val() === 'undefined' ? '' : $("#invValCrncyNm").val();
    var invValCrncyID = typeof $("#invValCrncyID").val() === 'undefined' ? '-1' : $("#invValCrncyID").val();
    var invPriceLessTax = typeof $("#invPriceLessTax").val() === 'undefined' ? '0' : $("#invPriceLessTax").val();
    var invSllngPrice = typeof $("#invSllngPrice").val() === 'undefined' ? '0' : $("#invSllngPrice").val();
    var invNwPrftAmnt = typeof $("#invNwPrftAmnt").val() === 'undefined' ? '0' : $("#invNwPrftAmnt").val();
    var invNewSllngPrice = typeof $("#invNewSllngPrice").val() === 'undefined' ? '0' : $("#invNewSllngPrice").val();
    var invPrftMrgnPrcnt = typeof $("#invPrftMrgnPrcnt").val() === 'undefined' ? '0' : $("#invPrftMrgnPrcnt").val();
    var invPrftMrgnAmnt = typeof $("#invPrftMrgnAmnt").val() === 'undefined' ? '0' : $("#invPrftMrgnAmnt").val();
    var errMsg = "";
    if (invItemNm.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item name cannot be empty!</span></p>';
    }
    if (invItemDesc.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item Description cannot be empty!</span></p>';
    }
    if (Number(invValCrncyID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item\'s Value Currency cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Item Prices',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Item Prices...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneINVItmPricesForm(rowIDAttrb, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 3);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 101);
    formData.append('sbmtdItmID', sbmtdItmID);
    formData.append('invItemNm', invItemNm);
    formData.append('invItemDesc', invItemDesc);
    formData.append('invTxCodeID', invTxCodeID);
    formData.append('invDscntCodeID', invDscntCodeID);
    formData.append('invChrgCodeID', invChrgCodeID);
    formData.append('invValCrncyID', invValCrncyID);
    formData.append('invPriceLessTax', invPriceLessTax);
    formData.append('invSllngPrice', invSllngPrice);
    formData.append('invNwPrftAmnt', invNwPrftAmnt);
    formData.append('invNewSllngPrice', invNewSllngPrice);
    formData.append('invPrftMrgnPrcnt', invPrftMrgnPrcnt);
    formData.append('invPrftMrgnAmnt', invPrftMrgnAmnt);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdItmID = result.itemid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delINVItms(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var itemNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItemID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItemID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        itemNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 1,
                                    sbmtdItmID: pKeyID,
                                    itemNm: itemNm
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

function delINVItmStores(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var stockNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_StockID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_StockID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        stockNm = $('#' + rowPrfxNm + rndmNum + '_StoreNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item Store?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item Store?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item Store?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item Store...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 2,
                                    sbmtdStckID: pKeyID,
                                    stockNm: stockNm
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

function delINVItmUoMs(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var uomNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        uomNm = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        //$.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item UOM?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item UOM?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item UOM?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item UOM...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 3,
                                    sbmtdLineID: pKeyID,
                                    uomNm: uomNm
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

function delINVItmDrgIntrctns(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var drugNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        drugNm = $('#' + rowPrfxNm + rndmNum + '_DrugNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Drug Interaction?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Drug Interaction?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Drug Interaction?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Drug Interaction...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 4,
                                    sbmtdLineID: pKeyID,
                                    drugNm: drugNm
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

function getAllStoresWhs(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#allStoresWhsSrchFor").val() === 'undefined' ? '%' : $("#allStoresWhsSrchFor").val();
    var srchIn = typeof $("#allStoresWhsSrchIn").val() === 'undefined' ? 'Both' : $("#allStoresWhsSrchIn").val();
    var pageNo = typeof $("#allStoresWhsPageNo").val() === 'undefined' ? 1 : $("#allStoresWhsPageNo").val();
    var limitSze = typeof $("#allStoresWhsDsplySze").val() === 'undefined' ? 10 : $("#allStoresWhsDsplySze").val();
    var sortBy = typeof $("#allStoresWhsSortBy").val() === 'undefined' ? '' : $("#allStoresWhsSortBy").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllStoresWhs(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllStoresWhs(actionText, slctr, linkArgs);
    }
}

function getOneStoresWhsForm(pKeyID, actionTxt, frmWhere, sbmtdSiteID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof frmWhere === 'undefined' || frmWhere === null) {
        frmWhere = 'FROMSTP';
    }
    if (typeof sbmtdSiteID === 'undefined' || sbmtdSiteID === null) {
        sbmtdSiteID = -1;
    }
    var lnkArgs = 'grp=12&typ=1&pg=5&vtyp=1&sbmtdStoreWhsID=' + pKeyID + '&sbmtdSiteID=' + sbmtdSiteID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Store (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#storeStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            $('[data-toggle="tabajxstores"]').off('click');
            $('[data-toggle="tabajxstores"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                if (targ.indexOf('Cage') >= 0) {
                    $('#storeUsers').addClass('hideNotice');
                    $('#storeCages').removeClass('hideNotice');
                } else {
                    $('#storeCages').addClass('hideNotice');
                    $('#storeUsers').removeClass('hideNotice');
                }
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            getAllStoresWhs('clear', '#allmodules', 'grp=12&typ=1&pg=5&vtyp=0');
            $(e.currentTarget).unbind();
        });
    });
}

function saveStoresWhsForm(sbmtdSiteID) {
    if (typeof sbmtdSiteID === 'undefined' || sbmtdSiteID === null) {
        sbmtdSiteID = -1;
    }
    var storeNm = typeof $("#storeNm").val() === 'undefined' ? '' : $("#storeNm").val();
    var sbmtdStoreWhsID = typeof $("#sbmtdStoreWhsID").val() === 'undefined' ? '-1' : $("#sbmtdStoreWhsID").val();
    var storeDesc = typeof $("#storeDesc").val() === 'undefined' ? '' : $("#storeDesc").val();
    var storeAddress = typeof $("#storeAddress").val() === 'undefined' ? '' : $("#storeAddress").val();
    var lnkdSiteID = typeof $("#lnkdSiteID").val() === 'undefined' ? '-1' : $("#lnkdSiteID").val();
    var lnkdGLAccountID = typeof $("#lnkdGLAccountID").val() === 'undefined' ? '-1' : $("#lnkdGLAccountID").val();
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var allwdGroupNm = typeof $("#allwdGroupNm").val() === 'undefined' ? '' : $("#allwdGroupNm").val();
    var allwdGroupID = typeof $("#allwdGroupID").val() === 'undefined' ? '' : $("#allwdGroupID").val();
    var storewhsMngrsPrsnID = typeof $("#storewhsMngrsPrsnID").val() === 'undefined' ? '-1' : $("#storewhsMngrsPrsnID").val();
    var isStoreWhsEnbld = typeof $("input[name='isStoreWhsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var isSalesAllwd = typeof $("input[name='isSalesAllwd']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (storeNm.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Store name cannot be empty!</span></p>';
    }
    if (storeDesc.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Store Description cannot be empty!</span></p>';
    }
    if (Number(lnkdSiteID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Site cannot be empty!</span></p>';
    }
    if (Number(lnkdGLAccountID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked GL Account cannot be empty!</span></p>';
    }
    var slctdUsers = "";
    var isVld = true;
    $('#oneStoreUsersTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_UsrID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var userID = typeof $('#' + rowPrfxNm + rndmNum + '_UsrID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_UsrID').val();
                    var lnID = typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_LineID').val();
                    if (Number(userID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        /*Do Nothing if (lnkdItmID > 0)*/
                    } else {
                        var lnStartDte = typeof $('#' + rowPrfxNm + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#' + rowPrfxNm + rndmNum + '_StrtDte').val();
                        if (lnStartDte.trim() === "") {
                            isVld = false;
                            $('#' + rowPrfxNm + rndmNum + '_StrtDte').addClass('rho-error');
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_StrtDte').removeClass('rho-error');
                        }
                        if (isVld === true) {
                            slctdUsers = slctdUsers + lnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                userID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                lnStartDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_EndDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Store',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Store...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                if (sbmtdSiteID > 0) {
                    getOneStoresWhsForm(sbmtdStoreWhsID, 'ReloadDialog', 'FROMBRNCH', sbmtdSiteID);
                } else {
                    getOneStoresWhsForm(sbmtdStoreWhsID, 'ReloadDialog');
                }
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
                    grp: 12,
                    typ: 1,
                    pg: 5,
                    q: 'UPDATE',
                    actyp: 1,
                    storeNm: storeNm,
                    sbmtdStoreWhsID: sbmtdStoreWhsID,
                    storeDesc: storeDesc,
                    storeAddress: storeAddress,
                    lnkdSiteID: lnkdSiteID,
                    lnkdGLAccountID: lnkdGLAccountID,
                    grpType: grpType,
                    allwdGroupNm: allwdGroupNm,
                    allwdGroupID: allwdGroupID,
                    storewhsMngrsPrsnID: storewhsMngrsPrsnID,
                    isStoreWhsEnbld: isStoreWhsEnbld,
                    isSalesAllwd: isSalesAllwd,
                    slctdUsers: slctdUsers
                },
                success: function (result) {
                    //console.log(result);
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdStoreWhsID = result.storewhsid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delStoresWhs(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var storewhsNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_StoreWhsID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_StoreWhsID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        storewhsNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Store?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Store?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Store?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Store...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 1,
                                    sbmtdStoreWhsID: pKeyID,
                                    storewhsNm: storewhsNm
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

function delINVStoreWhsUsrs(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var usrNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        usrNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Store User?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Store User?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Store User?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Store User...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 2,
                                    lineID: pKeyID,
                                    usrNm: usrNm
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

function getOneINVCgFormV(pKeyID, srcApp, inStoreID, sbmtdSiteID) {
    if (typeof inStoreID === 'undefined' || inStoreID === null) {
        inStoreID = -1;
    }
    if (typeof srcApp === 'undefined' || srcApp === null) {
        srcApp = 'Cages';
    }
    var lnkArgs = 'grp=12&typ=1&pg=5&vtyp=2&sbmtdCageID=' + pKeyID + '&cageStoreID=' + inStoreID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'Store Cage (ID:' + pKeyID + ')', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        $('#mcfTillStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allOtherInputData99').val(0);
        if (inStoreID > 0 && srcApp !== 'branches') {
            $('#cageStoreNm').val($('#storeNm').val());
            $('#cageStoreID').val(inStoreID);
        }
        $('#myFormsModalx').off('hidden.bs.modal');
        $('#myFormsModalx').one('hidden.bs.modal', function (e) {
            $('#myFormsModalxTitle').html('');
            $('#myFormsModalxBody').html('');
            $(e.currentTarget).unbind();
        });
    });
}

function saveINVCgFormV() {
    var cageShelfNm = typeof $("#cageShelfNm").val() === 'undefined' ? '' : $("#cageShelfNm").val();
    var cageLineID = typeof $("#cageLineID").val() === 'undefined' ? '-1' : $("#cageLineID").val();
    var cageShelfID = typeof $("#cageShelfID").val() === 'undefined' ? '-1' : $("#cageShelfID").val();
    var cageDesc = typeof $("#cageDesc").val() === 'undefined' ? '' : $("#cageDesc").val();
    var cageStoreID = typeof $("#cageStoreID").val() === 'undefined' ? '-1' : $("#cageStoreID").val();
    var cageOwnersCstmrID = typeof $("#cageOwnersCstmrID").val() === 'undefined' ? '-1' : $("#cageOwnersCstmrID").val();
    var lnkdCgGLAccountID = typeof $("#lnkdCgGLAccountID").val() === 'undefined' ? '-1' : $("#lnkdCgGLAccountID").val();
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var allwdGroupNm = typeof $("#allwdGroupNm").val() === 'undefined' ? '' : $("#allwdGroupNm").val();
    var allwdGroupID = typeof $("#allwdGroupID").val() === 'undefined' ? '' : $("#allwdGroupID").val();
    var cageMngrsPrsnID = typeof $("#cageMngrsPrsnID").val() === 'undefined' ? '-1' : $("#cageMngrsPrsnID").val();
    var mngrsWithdrawlLmt = typeof $("#mngrsWithdrawlLmt").val() === 'undefined' ? '0' : $("#mngrsWithdrawlLmt").val();
    var mngrsDepositLmt = typeof $("#mngrsDepositLmt").val() === 'undefined' ? '0' : $("#mngrsDepositLmt").val();
    var dfltItemType = typeof $("#dfltItemType").val() === 'undefined' ? '' : $("#dfltItemType").val();
    var dfltItemState = typeof $("#dfltItemState").val() === 'undefined' ? '' : $("#dfltItemState").val();
    var isCageEnbld = typeof $("input[name='isCageEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';

    mngrsWithdrawlLmt = fmtAsNumber('mngrsWithdrawlLmt').toFixed(2);
    mngrsDepositLmt = fmtAsNumber('mngrsDepositLmt').toFixed(2);
    var errMsg = "";

    if (cageShelfNm.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Store Cage name cannot be empty!</span></p>';
    }
    if (cageDesc.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Store Cage Description cannot be empty!</span></p>';
    }
    if (dfltItemType.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Default Item Type cannot be empty!</span></p>';
    }
    if (dfltItemState.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Default Item State cannot be empty!</span></p>';
    }
    if (Number(cageStoreID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Store cannot be empty!</span></p>';
    }
    if (Number(lnkdCgGLAccountID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked GL Account cannot be empty!</span></p>';
    }
    /*if (Number(cageMngrsPrsnID.replace(/[^-?0-9\.]/g, '')) <= 0)
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Cage Manager cannot be empty!</span></p>';
     }*/
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Cage/Till',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Cage/Till...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                $('#myFormsModalx').modal('hide');
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
                    grp: 12,
                    typ: 1,
                    pg: 5,
                    q: 'UPDATE',
                    actyp: 2,
                    cageShelfNm: cageShelfNm,
                    cageLineID: cageLineID,
                    cageShelfID: cageShelfID,
                    cageDesc: cageDesc,
                    cageStoreID: cageStoreID,
                    cageOwnersCstmrID: cageOwnersCstmrID,
                    lnkdGLAccountID: lnkdCgGLAccountID,
                    grpType: grpType,
                    allwdGroupNm: allwdGroupNm,
                    allwdGroupID: allwdGroupID,
                    cageMngrsPrsnID: cageMngrsPrsnID,
                    mngrsWithdrawlLmt: mngrsWithdrawlLmt,
                    mngrsDepositLmt: mngrsDepositLmt,
                    dfltItemType: dfltItemType,
                    dfltItemState: dfltItemState,
                    isCageEnbld: isCageEnbld
                },
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delINVCgV(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var cageNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        cageNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Cage/Till?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Cage/Till?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Cage/Till?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Cage/Till...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 5,
                                    q: 'DELETE',
                                    actyp: 3,
                                    lineID: pKeyID,
                                    cageNm: cageNm
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

function grpTypMcfChangeV() {
    var lovChkngElementVal = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    lovNm = "";
    if (lovChkngElementVal === "Everyone") {
        $('#allwdGroupName').attr("disabled", "true");
        $('#allwdGroupName').val("");
        $('#allwdGroupID').val("-1");
        $('#groupNameLbl').attr("disabled", "true");
    } else {
        $('#allwdGroupName').removeAttr("disabled");
        $('#allwdGroupName').val("");
        $('#allwdGroupID').val("-1");
        $('#groupNameLbl').removeAttr("disabled");
    }
}

function getAllINVGLIntrfcs(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#allINVGLIntrfcsSrchFor").val() === 'undefined' ? '%' : $("#allINVGLIntrfcsSrchFor").val();
    var srchIn = typeof $("#allINVGLIntrfcsSrchIn").val() === 'undefined' ? 'Both' : $("#allINVGLIntrfcsSrchIn").val();
    var pageNo = typeof $("#allINVGLIntrfcsPageNo").val() === 'undefined' ? 1 : $("#allINVGLIntrfcsPageNo").val();
    var limitSze = typeof $("#allINVGLIntrfcsDsplySze").val() === 'undefined' ? 10 : $("#allINVGLIntrfcsDsplySze").val();
    var sortBy = typeof $("#allINVGLIntrfcsSortBy").val() === 'undefined' ? '' : $("#allINVGLIntrfcsSortBy").val();
    var qStrtDte = typeof $("#allINVGLIntrfcsStrtDate").val() === 'undefined' ? '' : $("#allINVGLIntrfcsStrtDate").val();
    var qEndDte = typeof $("#allINVGLIntrfcsEndDate").val() === 'undefined' ? '' : $("#allINVGLIntrfcsEndDate").val();
    var qNotSentToGl = $('#allINVGLIntrfcsSntToGl:checked').length > 0;
    var qUnbalncdOnly = $('#allINVGLIntrfcsUnbalncd:checked').length > 0;
    var qUsrGnrtd = $('#allINVGLIntrfcsUsrTrns:checked').length > 0;
    var qLowVal = typeof $("#allINVGLIntrfcsLowVal").val() === 'undefined' ? 0 : $("#allINVGLIntrfcsLowVal").val();
    var qHighVal = typeof $("#allINVGLIntrfcsHighVal").val() === 'undefined' ? 0 : $("#allINVGLIntrfcsHighVal").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllINVGLIntrfcs(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllINVGLIntrfcs(actionText, slctr, linkArgs);
    }
}

function getOneINVGLIntrfcForm(pKeyID, pRowIDAttrb) {
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
    var rowIDAttrb = "";
    $('#allINVGLIntrfcsHdrsTable').find('tr').each(function (i, el) {
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
                    if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g, '')) > 0 && isTcked === "YES") {
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
        var trnsAmnt = Math.abs(Number(dbtAmnt.replace(/[^-?0-9\.]/g, '')) - Number(crdtAmnt.replace(/[^-?0-9\.]/g, ''))).toFixed(2);
        var lnkArgs = 'grp=12&typ=1&pg=13&vtyp=1&sbmtdIntrfcID=' + pKeyID;
        doAjaxWthCallBck(lnkArgs, 'myFormsModalp', 'ShowDialog', 'GL Interface Line (ID:' + pKeyID + ')', 'myFormsModalpTitle', 'myFormsModalpBody', function () {
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
            $('#myFormsModalx').one('hidden.bs.modal', function (e) {
                $('#myFormsModalxTitle').html('');
                $('#myFormsModalxBody').html('');
                getAllINVGLIntrfcs('', '#allmodules', 'grp=12&typ=1&pg=13&vtyp=0');
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

function afterINVIntrfcItemSlctn() {
    fmtAsNumber('enteredAmount');
    $('#funcCrncyRate').val(1);
    $('#accntCrncyRate').val(1);
    var enteredCrncyNm = $('#enteredCrncyNm').val();
    var enteredAmount = Number($('#enteredAmount').val().replace(/[^-?0-9\.]/g, ''));
    var intrfcAccntID = $('#intrfcAccntID').val();
    var glIntrfcTrnsDate = typeof $("#glIntrfcTrnsDate").val() === 'undefined' ? '' : $("#glIntrfcTrnsDate").val();
    var funcCrncyRate = typeof $("#funcCrncyRate").val() === 'undefined' ? '0' : $("#funcCrncyRate").val();
    var accntCrncyRate = typeof $("#accntCrncyRate").val() === 'undefined' ? '0' : $("#accntCrncyRate").val();
    if (Number(intrfcAccntID) > 0) {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 12);
            formData.append('typ', 1);
            formData.append('pg', 13);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 2);
            formData.append('intrfcAccntID', intrfcAccntID);
            formData.append('enteredCrncyNm', enteredCrncyNm);
            formData.append('enteredAmount', enteredAmount);
            formData.append('glIntrfcTrnsDate', glIntrfcTrnsDate);
            formData.append('funcCrncyRate', funcCrncyRate);
            formData.append('accntCrncyRate', accntCrncyRate);
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
                error: function (jqXHR, textStatus, errorThrown) {
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

function saveINVGLIntrfcForm() {
    afterINVIntrfcItemSlctn();
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
    if (Number(intrfcAccntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">GL Account cannot be empty!</span></p>';
    }
    if (Number(enteredAmount.replace(/[^-?0-9\.]/g, '')) == 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Entered Amount cannot be Zero!</span></p>';
    }
    if (Number(funcCrncyRate.replace(/[^-?0-9\.]/g, '')) == 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Functional Currency Rate cannot be empty!</span></p>';
    }
    if (Number(accntCrncyRate.replace(/[^-?0-9\.]/g, '')) == 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Account Currency Rate cannot be empty!</span></p>';
    }
    if (Number(funcCrncyAmount.replace(/[^-?0-9\.]/g, '')) == 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Functional Currency Amount cannot be empty!</span></p>';
    }
    if (Number(accntCrncyAmount.replace(/[^-?0-9\.]/g, '')) == 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Account Currency Rate cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
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
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 12,
                    typ: 1,
                    pg: 13,
                    q: 'UPDATE',
                    actyp: 1,
                    glIntrfcTrnsDate: glIntrfcTrnsDate,
                    glIntrfcTrnsID: glIntrfcTrnsID,
                    glIntrfcTrnsDesc: glIntrfcTrnsDesc,
                    intrfcAccntID: intrfcAccntID,
                    incrsDcrs: incrsDcrs,
                    enteredCrncyNm: enteredCrncyNm,
                    enteredAmount: enteredAmount.replace(/[^-?0-9\.]/g, ''),
                    funcCrncyRate: funcCrncyRate.replace(/[^-?0-9\.]/g, ''),
                    accntCrncyRate: accntCrncyRate.replace(/[^-?0-9\.]/g, ''),
                    funcCrncyAmount: funcCrncyAmount.replace(/[^-?0-9\.]/g, ''),
                    accntCrncyAmount: accntCrncyAmount.replace(/[^-?0-9\.]/g, '')
                },
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delSlctdINVIntrfcLines() {
    var slctdIntrfcIDs = "";
    var slctdCnt = 0;
    $('#allINVGLIntrfcsHdrsTable').find('tr').each(function (i, el) {
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
                    if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g, '')) > 0 && isTcked === "YES") {
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
                $('#allINVGLIntrfcsHdrsTable').find('tr').each(function (i, el) {
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
                                if (Number(lnIntrfcID.replace(/[^-?0-9\.]/g, '')) > 0 && isTcked === "YES") {
                                    slctdIntrfcIDs = slctdIntrfcIDs + lnIntrfcID + "~" +
                                        intrfcIDDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                            $("body").css("padding-right", "0px");
                            if (result2.indexOf("Success") !== -1) {
                                getAllINVGLIntrfcs('', '#allmodules', 'grp=12&typ=1&pg=13&vtyp=0');
                            }
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 13,
                                    q: 'DELETE',
                                    actyp: 1,
                                    slctdIntrfcIDs: slctdIntrfcIDs
                                },
                                success: function (result1) {
                                    result2 = result1;
                                    setTimeout(function () {
                                        dialog1.find('.bootbox-body').html(result1);
                                    }, 500);
                                },
                                error: function (jqXHR1, textStatus1, errorThrown1) {
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

function getGnrlScmUOMBrkdwnForm(pKeyID, vwtype, sbmtdItemID, varTtlQty, sbmtdCrncyNm, rowIDAttrb) {
    if (typeof rowIDAttrb === 'undefined' || rowIDAttrb === null) {
        rowIDAttrb = 'CnsgnRcpt';
    }
    var lnkArgs = 'grp=12&typ=1&pg=3&vtyp=' + vwtype + '&pKeyID=' + pKeyID +
        "&sbmtdItemID=" + sbmtdItemID + "&varTtlQty=" + varTtlQty + "&sbmtdCrncyNm=" + sbmtdCrncyNm + '&rowIDAttrb=' + rowIDAttrb;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalx', 'ShowDialog', 'INV Trns. QTY UOM Breakdown', 'myFormsModalxTitle', 'myFormsModalxBody', function () {
        if (!$.fn.DataTable.isDataTable('#oneINVQtyBrkDwnTable')) {
            var table1 = $('#oneINVQtyBrkDwnTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneINVQtyBrkDwnTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#oneINVTrnsForm').submit(function (e) {
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

function getInvPrdtCtgry(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#invPrdtCtgrySrchFor").val() === 'undefined' ? '%' : $("#invPrdtCtgrySrchFor").val();
    var srchIn = typeof $("#invPrdtCtgrySrchIn").val() === 'undefined' ? 'Both' : $("#invPrdtCtgrySrchIn").val();
    var pageNo = typeof $("#invPrdtCtgryPageNo").val() === 'undefined' ? 1 : $("#invPrdtCtgryPageNo").val();
    var limitSze = typeof $("#invPrdtCtgryDsplySze").val() === 'undefined' ? 10 : $("#invPrdtCtgryDsplySze").val();
    var sortBy = typeof $("#invPrdtCtgrySortBy").val() === 'undefined' ? '' : $("#invPrdtCtgrySortBy").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncInvPrdtCtgry(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getInvPrdtCtgry(actionText, slctr, linkArgs);
    }
}

function getOneInvPrdtCtgryForm(tmpltID, vwtype) {
    var lnkArgs = 'grp=12&typ=1&pg=4&vtyp=' + vwtype + '&sbmtdPrdtCgtryID=' + tmpltID + "&subPgNo=1";
    doAjaxWthCallBck(lnkArgs, 'invPrdtCtgryDetailInfo', 'PasteDirect', '', '', '', function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
    });
}

function saveInvPrdtCtgryForm() {
    var invPrdtCtgryID = typeof $("#invPrdtCtgryID").val() === 'undefined' ? '-1' : $("#invPrdtCtgryID").val();
    var invPrdtCtgryName = typeof $("#invPrdtCtgryName").val() === 'undefined' ? '' : $("#invPrdtCtgryName").val();
    var invPrdtCtgryDesc = typeof $("#invPrdtCtgryDesc").val() === 'undefined' ? '' : $("#invPrdtCtgryDesc").val();
    var invPrdtCtgryIsEnbld = typeof $("input[name='invPrdtCtgryIsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (invPrdtCtgryName.trim() === '' || invPrdtCtgryDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Category Name and Description cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save Product Category',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Product Category...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 4);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('invPrdtCtgryID', invPrdtCtgryID);
    formData.append('invPrdtCtgryName', invPrdtCtgryName);
    formData.append('invPrdtCtgryDesc', invPrdtCtgryDesc);
    formData.append('invPrdtCtgryIsEnbld', invPrdtCtgryIsEnbld);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                            invPrdtCtgryID = data.invPrdtCtgryID;
                            getInvPrdtCtgry('', '#allmodules', 'grp=12&typ=1&pg=4&vtyp=0');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delInvPrdtCtgry(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CtgryID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CtgryID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CtgryNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Product Category?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Product Category?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Product Category?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Product Category...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 4,
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

function getInvUOMStp(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#invUOMStpSrchFor").val() === 'undefined' ? '%' : $("#invUOMStpSrchFor").val();
    var srchIn = typeof $("#invUOMStpSrchIn").val() === 'undefined' ? 'Both' : $("#invUOMStpSrchIn").val();
    var pageNo = typeof $("#invUOMStpPageNo").val() === 'undefined' ? 1 : $("#invUOMStpPageNo").val();
    var limitSze = typeof $("#invUOMStpDsplySze").val() === 'undefined' ? 10 : $("#invUOMStpDsplySze").val();
    var sortBy = typeof $("#invUOMStpSortBy").val() === 'undefined' ? '' : $("#invUOMStpSortBy").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncInvUOMStp(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getInvUOMStp(actionText, slctr, linkArgs);
    }
}

function getOneInvUOMStpForm(tmpltID, vwtype) {
    var lnkArgs = 'grp=12&typ=1&pg=10&vtyp=' + vwtype + '&sbmtdPrdtCgtryID=' + tmpltID + "&subPgNo=1";
    doAjaxWthCallBck(lnkArgs, 'invUOMStpDetailInfo', 'PasteDirect', '', '', '', function () {
        $('[data-toggle="tooltip"]').tooltip();
        $(".jbDetDbt").focus(function () {
            $(this).select();
        });
    });
}

function saveInvUOMStpForm() {
    var invUOMStpID = typeof $("#invUOMStpID").val() === 'undefined' ? '-1' : $("#invUOMStpID").val();
    var invUOMStpName = typeof $("#invUOMStpName").val() === 'undefined' ? '' : $("#invUOMStpName").val();
    var invUOMStpDesc = typeof $("#invUOMStpDesc").val() === 'undefined' ? '' : $("#invUOMStpDesc").val();
    var invUOMStpIsEnbld = typeof $("input[name='invUOMStpIsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var errMsg = "";
    if (invUOMStpName.trim() === '' || invUOMStpDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">UOM Name and Description cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Save UOM',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving UOM...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 10);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('invUOMStpID', invUOMStpID);
    formData.append('invUOMStpName', invUOMStpName);
    formData.append('invUOMStpDesc', invUOMStpDesc);
    formData.append('invUOMStpIsEnbld', invUOMStpIsEnbld);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                            invUOMStpID = data.invUOMStpID;
                            getInvUOMStp('', '#allmodules', 'grp=12&typ=1&pg=10&vtyp=0');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delInvUOMStp(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_UOMStpID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_UOMStpID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_UOMStpNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete UOM?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this UOM?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete UOM?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting UOM...Please Wait...</p>',
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
                                    grp: 12,
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

function getAllItmTmplts(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#allItmTmpltsSrchFor").val() === 'undefined' ? '%' : $("#allItmTmpltsSrchFor").val();
    var srchIn = typeof $("#allItmTmpltsSrchIn").val() === 'undefined' ? 'Both' : $("#allItmTmpltsSrchIn").val();
    var pageNo = typeof $("#allItmTmpltsPageNo").val() === 'undefined' ? 1 : $("#allItmTmpltsPageNo").val();
    var limitSze = typeof $("#allItmTmpltsDsplySze").val() === 'undefined' ? 10 : $("#allItmTmpltsDsplySze").val();
    var sortBy = typeof $("#allItmTmpltsSortBy").val() === 'undefined' ? '' : $("#allItmTmpltsSortBy").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllItmTmplts(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllItmTmplts(actionText, slctr, linkArgs);
    }
}

function getOneItmTmpltsForm(pKeyID, actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=12&typ=1&pg=8&vtyp=1&sbmtdItmTmpltID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNrml', actionTxt, 'Item (ID:' + pKeyID + ')', 'myFormsModalNrmlTitle', 'myFormsModalNrmlBody', function () {
        $('#itmTmpltStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            $('[data-toggle="tabajxitmtmplts"]').off('click');
            $('[data-toggle="tabajxitmtmplts"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                if (targ.indexOf('itmTmpltsGnrl') >= 0) {
                    $('#itmTmpltsGnrl').removeClass('hideNotice');
                    $('#itmTmpltsGl').addClass('hideNotice');
                    $('#itmTmpltsStores').addClass('hideNotice');
                    $('#itmTmpltsExtInfo').addClass('hideNotice');
                    $('#itmTmpltsUOM').addClass('hideNotice');
                } else if (targ.indexOf('itmTmpltsGl') >= 0) {
                    $('#itmTmpltsGnrl').addClass('hideNotice');
                    $('#itmTmpltsGl').removeClass('hideNotice');
                    $('#itmTmpltsStores').addClass('hideNotice');
                    $('#itmTmpltsExtInfo').addClass('hideNotice');
                    $('#itmTmpltsUOM').addClass('hideNotice');
                } else if (targ.indexOf('itmTmpltsStores') >= 0) {
                    $('#itmTmpltsGnrl').addClass('hideNotice');
                    $('#itmTmpltsGl').addClass('hideNotice');
                    $('#itmTmpltsStores').removeClass('hideNotice');
                    $('#itmTmpltsExtInfo').addClass('hideNotice');
                    $('#itmTmpltsUOM').addClass('hideNotice');
                    $('#itmTmpltsDrugLbl').addClass('hideNotice');
                    $('#itmTmpltsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('itmTmpltsExtInfo') >= 0) {
                    $('#itmTmpltsGnrl').addClass('hideNotice');
                    $('#itmTmpltsGl').addClass('hideNotice');
                    $('#itmTmpltsStores').addClass('hideNotice');
                    $('#itmTmpltsExtInfo').removeClass('hideNotice');
                    $('#itmTmpltsUOM').addClass('hideNotice');
                } else if (targ.indexOf('itmTmpltsUOM') >= 0) {
                    $('#itmTmpltsGnrl').addClass('hideNotice');
                    $('#itmTmpltsGl').addClass('hideNotice');
                    $('#itmTmpltsStores').addClass('hideNotice');
                    $('#itmTmpltsExtInfo').addClass('hideNotice');
                    $('#itmTmpltsUOM').removeClass('hideNotice');
                    $('#itmTmpltsDrugLbl').addClass('hideNotice');
                    $('#itmTmpltsDrugIntrctns').addClass('hideNotice');
                } else if (targ.indexOf('itmTmpltsDrugLbl') >= 0) {
                    $('#itmTmpltsGnrl').addClass('hideNotice');
                    $('#itmTmpltsGl').addClass('hideNotice');
                    $('#itmTmpltsStores').addClass('hideNotice');
                    $('#itmTmpltsExtInfo').addClass('hideNotice');
                    $('#itmTmpltsUOM').addClass('hideNotice');
                } else if (targ.indexOf('itmTmpltsDrugIntrctns') >= 0) {
                    $('#itmTmpltsGnrl').addClass('hideNotice');
                    $('#itmTmpltsGl').addClass('hideNotice');
                    $('#itmTmpltsStores').addClass('hideNotice');
                    $('#itmTmpltsExtInfo').addClass('hideNotice');
                    $('#itmTmpltsUOM').addClass('hideNotice');
                }
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal', function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            getAllItmTmplts('clear', '#allmodules', 'grp=12&typ=1&pg=8&vtyp=0');
            $(e.currentTarget).unbind();
        });
    });
}

function saveItmTmpltsForm() {
    var sbmtdItmTmpltID = typeof $("#sbmtdItmTmpltID").val() === 'undefined' ? '-1' : $("#sbmtdItmTmpltID").val();
    var itmTmpltNm = typeof $("#itmTmpltNm").val() === 'undefined' ? '' : $("#itmTmpltNm").val();
    var itmTmpltDesc = typeof $("#itmTmpltDesc").val() === 'undefined' ? '' : $("#itmTmpltDesc").val();
    var itmTmpltType = typeof $("#itmTmpltType").val() === 'undefined' ? '' : $("#itmTmpltType").val();
    var itmTmpltCtgry = typeof $("#itmTmpltCtgry").val() === 'undefined' ? '' : $("#itmTmpltCtgry").val();
    var itmTmpltCtgryID = typeof $("#itmTmpltCtgryID").val() === 'undefined' ? '-1' : $("#itmTmpltCtgryID").val();
    var invBaseUom = typeof $("#invBaseUom").val() === 'undefined' ? '' : $("#invBaseUom").val();
    var invBaseUomID = typeof $("#invBaseUomID").val() === 'undefined' ? '-1' : $("#invBaseUomID").val();
    var invTxCodeName = typeof $("#invTxCodeName").val() === 'undefined' ? '' : $("#invTxCodeName").val();
    var invTxCodeID = typeof $("#invTxCodeID").val() === 'undefined' ? '-1' : $("#invTxCodeID").val();
    var invDscntCodeName = typeof $("#invDscntCodeName").val() === 'undefined' ? '' : $("#invDscntCodeName").val();
    var invDscntCodeID = typeof $("#invDscntCodeID").val() === 'undefined' ? '-1' : $("#invDscntCodeID").val();
    var invChrgCodeName = typeof $("#invChrgCodeName").val() === 'undefined' ? '' : $("#invChrgCodeName").val();
    var invChrgCodeID = typeof $("#invChrgCodeID").val() === 'undefined' ? '-1' : $("#invChrgCodeID").val();
    var isItmEnbld = typeof $("input[name='isItmEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var isPlnngEnbld = typeof $("input[name='isPlnngEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var autoLoadInVMS = typeof $("input[name='autoLoadInVMS']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var invMinItmQty = typeof $("#invMinItmQty").val() === 'undefined' ? '0' : $("#invMinItmQty").val();
    var invMaxItmQty = typeof $("#invMaxItmQty").val() === 'undefined' ? '0' : $("#invMaxItmQty").val();
    var invValCrncyNm = typeof $("#invValCrncyNm").val() === 'undefined' ? '' : $("#invValCrncyNm").val();
    var invValCrncyID = typeof $("#invValCrncyID").val() === 'undefined' ? '-1' : $("#invValCrncyID").val();
    var invSllngPrice = typeof $("#invSllngPrice").val() === 'undefined' ? '0' : $("#invSllngPrice").val();
    var invAssetAcntNm = typeof $("#invAssetAcntNm").val() === 'undefined' ? '' : $("#invAssetAcntNm").val();
    var invAssetAcntID = typeof $("#invAssetAcntID").val() === 'undefined' ? '-1' : $("#invAssetAcntID").val();
    var invCogsAcntID = typeof $("#invCogsAcntID").val() === 'undefined' ? '-1' : $("#invCogsAcntID").val();
    var invCogsAcntNm = typeof $("#invCogsAcntNm").val() === 'undefined' ? '' : $("#invCogsAcntNm").val();
    var invSRvnuAcntID = typeof $("#invSRvnuAcntID").val() === 'undefined' ? '-1' : $("#invSRvnuAcntID").val();
    var invSRvnuAcntNm = typeof $("#invSRvnuAcntNm").val() === 'undefined' ? '' : $("#invSRvnuAcntNm").val();
    var invSRetrnAcntID = typeof $("#invSRetrnAcntID").val() === 'undefined' ? '-1' : $("#invSRetrnAcntID").val();
    var invSRetrnAcntNm = typeof $("#invSRetrnAcntNm").val() === 'undefined' ? '' : $("#invSRetrnAcntNm").val();
    var invPRetrnAcntID = typeof $("#invPRetrnAcntID").val() === 'undefined' ? '-1' : $("#invPRetrnAcntID").val();
    var invPRetrnAcntNm = typeof $("#invPRetrnAcntNm").val() === 'undefined' ? '' : $("#invPRetrnAcntNm").val();
    var invExpnsAcntID = typeof $("#invExpnsAcntID").val() === 'undefined' ? '-1' : $("#invExpnsAcntID").val();
    var invExpnsAcntNm = typeof $("#invExpnsAcntNm").val() === 'undefined' ? '' : $("#invExpnsAcntNm").val();
    var errMsg = "";
    if (itmTmpltNm.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item name cannot be empty!</span></p>';
    }
    if (itmTmpltDesc.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item Description cannot be empty!</span></p>';
    }
    if (Number(itmTmpltCtgryID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Category cannot be empty!</span></p>';
    }
    if (Number(invBaseUomID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Base UOM cannot be empty!</span></p>';
    }
    if (Number(invValCrncyID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item\'s Value Currency cannot be empty!</span></p>';
    }
    if (Number(invAssetAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Asset Account cannot be empty!</span></p>';
    }
    if (Number(invSRetrnAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Sales Return Account cannot be empty!</span></p>';
    }
    if (Number(invSRvnuAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Sales Revenue Account cannot be empty!</span></p>';
    }
    if (Number(invPRetrnAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Purchase Return Account cannot be empty!</span></p>';
    }
    if (Number(invExpnsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">GL Account cannot be empty!</span></p>';
    }
    if (Number(invCogsAcntID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cost of Sales Account cannot be empty!</span></p>';
    }
    var slctdItmStores = "";
    var slctdItmUOMs = "";
    var isVld = true;
    $('#oneItmStoresTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_StockID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var lnStoreID = typeof $('#' + rowPrfxNm + rndmNum + '_StoreID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_StoreID').val();
                    if (Number(lnStoreID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        /*Do Nothing*/
                    } else {
                        if (isVld === true) {
                            slctdItmStores = slctdItmStores + $('#' + rowPrfxNm + rndmNum + '_StockID').val() + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_StoreID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_ShlvNm').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_ShlvIDs').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_StrtDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_EndDte').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    $('#oneItmUOMsTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var lnUomID = typeof $('#' + rowPrfxNm + rndmNum + '_UOMID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_UOMID').val();
                    if (Number(lnUomID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        /*Do Nothing*/
                    } else {
                        if (isVld === true) {
                            slctdItmUOMs = slctdItmUOMs + $('#' + rowPrfxNm + rndmNum + '_LineID').val() + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_UOMID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_CnvrsnFctr').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                $('#' + rowPrfxNm + rndmNum + '_SortOrdr').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        }
    });
    if (isVld === false) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: 'Save Item Template',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Item Template...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneItmTmpltsForm(sbmtdItmTmpltID, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 8);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdItmTmpltID', sbmtdItmTmpltID);
    formData.append('itmTmpltNm', itmTmpltNm);
    formData.append('itmTmpltDesc', itmTmpltDesc);
    formData.append('itmTmpltType', itmTmpltType);
    formData.append('itmTmpltCtgryID', itmTmpltCtgryID);
    formData.append('invBaseUomID', invBaseUomID);
    formData.append('invTxCodeID', invTxCodeID);
    formData.append('invDscntCodeID', invDscntCodeID);
    formData.append('invChrgCodeID', invChrgCodeID);
    formData.append('isItmEnbld', isItmEnbld);
    formData.append('isPlnngEnbld', isPlnngEnbld);
    formData.append('autoLoadInVMS', autoLoadInVMS);
    formData.append('invMinItmQty', invMinItmQty);
    formData.append('invMaxItmQty', invMaxItmQty);
    formData.append('invValCrncyID', invValCrncyID);
    formData.append('invSllngPrice', invSllngPrice);
    formData.append('invAssetAcntID', invAssetAcntID);
    formData.append('invCogsAcntID', invCogsAcntID);
    formData.append('invSRvnuAcntID', invSRvnuAcntID);
    formData.append('invSRetrnAcntID', invSRetrnAcntID);
    formData.append('invPRetrnAcntID', invPRetrnAcntID);
    formData.append('invExpnsAcntID', invExpnsAcntID);
    formData.append('slctdItmStores', slctdItmStores);
    formData.append('slctdItmUOMs', slctdItmUOMs);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdItmTmpltID = result.itemid;
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delItmTmplts(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var itemNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItemTmpltID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItemTmpltID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        itemNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item Template?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item Template?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item Template?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item Template... Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETE',
                                    actyp: 1,
                                    sbmtdItmTmpltID: pKeyID,
                                    itemNm: itemNm
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

function delItmTmpltstores(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var stockNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_StockID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_StockID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        stockNm = $('#' + rowPrfxNm + rndmNum + '_StoreNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item Store?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item Store?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item Store?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item Store...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETE',
                                    actyp: 2,
                                    sbmtdStckID: pKeyID,
                                    stockNm: stockNm
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

function delItmTmpltUoMs(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var uomNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LineID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        uomNm = $('#' + rowPrfxNm + rndmNum + '_LineID').val();
        //$.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Item UOM?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Item UOM?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Item UOM?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Item UOM...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 8,
                                    q: 'DELETE',
                                    actyp: 3,
                                    sbmtdLineID: pKeyID,
                                    uomNm: uomNm
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

function getScmSalesInvc(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#scmSalesInvcSrchFor").val() === 'undefined' ? '%' : $("#scmSalesInvcSrchFor").val();
    var srchIn = typeof $("#scmSalesInvcSrchIn").val() === 'undefined' ? 'Both' : $("#scmSalesInvcSrchIn").val();
    var pageNo = typeof $("#scmSalesInvcPageNo").val() === 'undefined' ? 1 : $("#scmSalesInvcPageNo").val();
    var limitSze = typeof $("#scmSalesInvcDsplySze").val() === 'undefined' ? 10 : $("#scmSalesInvcDsplySze").val();
    var sortBy = typeof $("#scmSalesInvcSortBy").val() === 'undefined' ? '' : $("#scmSalesInvcSortBy").val();
    var qShwUnpstdOnly = $('#scmSalesInvcShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#scmSalesInvcShwUnpaidOnly:checked').length > 0;
    var qShwSelfOnly = $('#scmSalesInvcShwSelfOnly:checked').length > 0;
    var qShwMyBranch = $('#scmSalesInvcShwMyBranchOnly:checked').length > 0;
    var qStrtDte = typeof $("#scmSalesInvcStrtDate").val() === 'undefined' ? '' : $("#scmSalesInvcStrtDate").val();
    var qEndDte = typeof $("#scmSalesInvcEndDate").val() === 'undefined' ? '' : $("#scmSalesInvcEndDate").val();
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
        "&qShwUnpaidOnly=" + qShwUnpaidOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly +
        "&qShwSelfOnly=" + qShwSelfOnly + "&qShwMyBranch=" + qShwMyBranch +
        "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte;
    openATab(slctr, linkArgs);
    $("#allOtherInputData3").val(0);
}

function enterKeyFuncScmSalesInvc(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getScmSalesInvc(actionText, slctr, linkArgs);
    }
}

function getScmSalesInvcItems(rowIDAttrb, actionTxt, sbmtdDocType, qCnsgnOnly, callBackFunc) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }

    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }

    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var storeID = -1;
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmID').val() === 'undefined') {
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
    var lnkArgs = 'grp=12&typ=1&pg=3&vtyp=4&sbmtdItemID=' + pKeyID + '&scmSalesInvcCstmrSiteID=' + scmSalesInvcCstmrSiteID +
        '&sbmtdDocType=' + sbmtdDocType + '&qCnsgnOnly=' + qCnsgnOnly + '&sbmtdRowIDAttrb=' + rowIDAttrb +
        '&sbmtdCallBackFunc=' + callBackFunc + '&sbmtdStoreID=' + storeID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLx', actionTxt, 'Sales/Inventory Items', 'myFormsModalLxTitle', 'myFormsModalLxBody', function () {
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

        $('#myFormsModalLx').off('hidden.bs.modal');
        $('#myFormsModalLx').one('hidden.bs.modal', function (e) {
            $('#myFormsModalLxTitle').html('');
            $('#myFormsModalLxBody').html('');
            callBackFunc();
            $(e.currentTarget).unbind();
        });
    });
}

function getScmSalesInvItms(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#scmSalesInvItmsSrchFor").val() === 'undefined' ? '%' : $("#scmSalesInvItmsSrchFor").val();
    var srchIn = typeof $("#scmSalesInvItmsSrchIn").val() === 'undefined' ? 'Both' : $("#scmSalesInvItmsSrchIn").val();
    var pageNo = typeof $("#scmSalesInvItmsPageNo").val() === 'undefined' ? 1 : $("#scmSalesInvItmsPageNo").val();
    var limitSze = typeof $("#scmSalesInvItmsDsplySze").val() === 'undefined' ? 10 : $("#scmSalesInvItmsDsplySze").val();
    var sortBy = typeof $("#scmSalesInvItmsSortBy").val() === 'undefined' ? '' : $("#scmSalesInvItmsSortBy").val();
    var sbmtdDocType = typeof $("#sbmtdDocType").val() === 'undefined' ? '' : $("#sbmtdDocType").val();
    var sbmtdItemID = typeof $("#sbmtdItemID").val() === 'undefined' ? '-1' : $("#sbmtdItemID").val();
    var sbmtdStoreID = typeof $("#sbmtdStoreID").val() === 'undefined' ? '-1' : $("#sbmtdStoreID").val();
    var qCnsgnOnly = $('#scmSalesInvItmsShwCnsgnOnly:checked').length > 0;
    var sbmtdCallBackFunc = typeof $("#sbmtdCallBackFunc").val() === 'undefined' ? 'function(){var a=1;}' : $("#sbmtdCallBackFunc").val();
    var sbmtdRowIDAttrb = typeof $("#sbmtdRowIDAttrb").val() === 'undefined' ? '' : $("#sbmtdRowIDAttrb").val();
    var scmSalesInvcCstmrID = typeof $("#scmSalesInvcCstmrID").val() === 'undefined' ? -1 : $("#scmSalesInvcCstmrID").val();

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
        "&qCnsgnOnly=" + qCnsgnOnly + "&sbmtdItemID=" + sbmtdItemID + "&sbmtdDocType=" + sbmtdDocType +
        '&scmSalesInvcCstmrID=' + scmSalesInvcCstmrID + '&sbmtdRowIDAttrb=' + sbmtdRowIDAttrb + '&sbmtdCallBackFunc=' + sbmtdCallBackFunc + '&sbmtdStoreID=' + sbmtdStoreID;
    openATab(slctr, linkArgs);
}

function enterKeyFuncScmSalesInvItms(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getScmSalesInvItms(actionText, slctr, linkArgs);
    }
}

function applySlctdSalesInvItms(rowIDAttrb, qCnsgnOnly, tableElmntID, callBackFunc) {
    /*nwSalesDocLineHtm
     */
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    var inptHtml = typeof $("#nwSalesDocLineHtm").val() === 'undefined' ? '' : $("#nwSalesDocLineHtm").val();
    var scmSalesInvcVchType = typeof $("#scmSalesInvcVchType").val() === 'undefined' ? '' : $("#scmSalesInvcVchType").val();
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
    if (cbResults.length > 1) {
        fnl_res = cbResults.slice(0, -1);
    }
    if (radioResults.length > 1) {
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
            var tmpID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
            if (Number(tmpID) <= 0) {
                $('#' + rowPrfxNm + rndmNum + '_ItmID').val(ln_ItmID);
                $('#' + rowPrfxNm + rndmNum + '_LineDesc').val(ln_ItmNm);
                $('#' + rowPrfxNm + rndmNum + '_UomID').val(ln_UomID);
                $('#' + rowPrfxNm + rndmNum + '_UomNm1').text(ln_UomNm);
                var curQTY = $('#' + rowPrfxNm + rndmNum + '_QTY').val();
                if (Number(curQTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                    $('#' + rowPrfxNm + rndmNum + '_QTY').val(1);
                }
                if (tableElmntID.indexOf("CnsgnRcpt") !== -1) {
                    $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(ln_CostPrice);
                    $('#' + rowPrfxNm + rndmNum + '_StoreID').val(ln_StoreID);
                    $('#' + rowPrfxNm + rndmNum + '_StoreNm').val(ln_StoreNm);
                } else if (tableElmntID.indexOf("StockTrnsfr") !== -1) {
                    $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(ln_CostPrice);
                } else if (tableElmntID.indexOf("PrchsDoc") !== -1) {
                    $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(ln_CostPrice);
                    $('#' + rowPrfxNm + rndmNum + '_TaxID').val(ln_TaxID);
                    $('#' + rowPrfxNm + rndmNum + '_DscntID').val(ln_DscntID);
                    $('#' + rowPrfxNm + rndmNum + '_ChrgID').val(ln_ChrgID);
                    $('#' + rowPrfxNm + rndmNum + '_StoreID').val(ln_StoreID);
                } else {
                    if (scmSalesInvcVchType === "Item Issue-Unbilled" || scmSalesInvcVchType === "Internal Item Request") {
                        $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(ln_CostPrice);
                    } else {
                        $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(ln_SellPrice);
                    }
                    $('#' + rowPrfxNm + rndmNum + '_TaxID').val(ln_TaxID);
                    $('#' + rowPrfxNm + rndmNum + '_DscntID').val(ln_DscntID);
                    $('#' + rowPrfxNm + rndmNum + '_ChrgID').val(ln_ChrgID);
                    $('#' + rowPrfxNm + rndmNum + '_StoreID').val(ln_StoreID);
                    $('#' + rowPrfxNm + rndmNum + '_ItmAccnts').val(ln_InvAcntID + ";" + ln_CogsAcntID + ";" + ln_SalesRevAcntID + ";" + ln_SalesRetAcntID + ";" + ln_PrchsRetAcntID + ";" + ln_ExpnsAcntID);
                }
            } else {
                var nwRndm = insertOnlyScmSalesInvcRows(tableElmntID, 1, inptHtml);
                $('#' + rowPrfxNm + nwRndm + '_ItmID').val(ln_ItmID);
                $('#' + rowPrfxNm + nwRndm + '_LineDesc').val(ln_ItmNm);
                $('#' + rowPrfxNm + nwRndm + '_UomID').val(ln_UomID);
                $('#' + rowPrfxNm + nwRndm + '_UomNm1').text(ln_UomNm);
                var curQTY = typeof $('#' + rowPrfxNm + nwRndm + '_QTY').val() === 'undefined' ? '0.00' : $('#' + rowPrfxNm + nwRndm + '_QTY').val();
                if (Number(curQTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                    $('#' + rowPrfxNm + nwRndm + '_QTY').val(1);
                }

                if (tableElmntID.indexOf("CnsgnRcpt") !== -1) {
                    $('#' + rowPrfxNm + nwRndm + '_UnitPrice').val(ln_CostPrice);
                    $('#' + rowPrfxNm + nwRndm + '_StoreID').val(ln_StoreID);
                    $('#' + rowPrfxNm + nwRndm + '_StoreNm').val(ln_StoreNm);
                } else if (tableElmntID.indexOf("StockTrnsfr") !== -1) {
                    $('#' + rowPrfxNm + nwRndm + '_UnitPrice').val(ln_CostPrice);
                } else if (tableElmntID.indexOf("PrchsDoc") !== -1) {
                    $('#' + rowPrfxNm + nwRndm + '_UnitPrice').val(ln_CostPrice);
                    $('#' + rowPrfxNm + nwRndm + '_TaxID').val(ln_TaxID);
                    $('#' + rowPrfxNm + nwRndm + '_DscntID').val(ln_DscntID);
                    $('#' + rowPrfxNm + nwRndm + '_ChrgID').val(ln_ChrgID);
                    $('#' + rowPrfxNm + nwRndm + '_StoreID').val(ln_StoreID);
                } else {
                    if (scmSalesInvcVchType === "Item Issue-Unbilled" || scmSalesInvcVchType === "Internal Item Request") {
                        $('#' + rowPrfxNm + nwRndm + '_UnitPrice').val(ln_CostPrice);
                    } else {
                        $('#' + rowPrfxNm + nwRndm + '_UnitPrice').val(ln_SellPrice);
                    }
                    $('#' + rowPrfxNm + nwRndm + '_TaxID').val(ln_TaxID);
                    $('#' + rowPrfxNm + nwRndm + '_DscntID').val(ln_DscntID);
                    $('#' + rowPrfxNm + nwRndm + '_ChrgID').val(ln_ChrgID);
                    $('#' + rowPrfxNm + nwRndm + '_StoreID').val(ln_StoreID);
                    $('#' + rowPrfxNm + nwRndm + '_ItmAccnts').val(ln_InvAcntID + ";" + ln_CogsAcntID + ";" + ln_SalesRevAcntID + ";" + ln_SalesRetAcntID + ";" + ln_PrchsRetAcntID + ";" + ln_ExpnsAcntID);
                }
            }
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
        if (tableElmntID.indexOf("StockTrnsfr") !== -1) {
            chngStockTrnsfrStores();
        } else {
            var cntr = 0;
            $('#' + tableElmntID).find('tr').each(function (i, el) {
                if (i > 0) {
                    if (typeof $(el).attr('id') === 'undefined') {
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
    }
    $('#myFormsModalLx').modal('hide');
    if (tableElmntID.indexOf("CnsgnRcpt") !== -1) {
        calcAllScmCnsgnRcptSmryTtl();
    } else if (tableElmntID.indexOf("StockTrnsfr") !== -1) {
        calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');
    } else if (tableElmntID.indexOf("PrchsDoc") !== -1) {
        calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');
    } else {
        calcAllScmSalesInvcSmryTtl();
    }
    var ttlrows = $('#' + tableElmntID + ' > tbody > tr').length;
    $('#allOtherInputData99').val(ttlrows);
    var freeRowNum = getFreeRowNum('_ItmID', tableElmntID);
    if (freeRowNum.trim() !== "") {
        $('#' + rowPrfxNm + freeRowNum + '_LineDesc').focus();
    } else {
        var nwRndm1 = insertOnlyScmSalesInvcRows(tableElmntID, 1, inptHtml);
        $('#' + rowPrfxNm + nwRndm1 + '_LineDesc').focus();
    }
    /*$('#' + rowPrfxNm + rndmNum + '_QTY').focus();*/
}

function getSalesInvItmsFrmSrc(slctr) {
    var accbPyblsInvcInvcCur = typeof $("#accbPyblsInvcInvcCur1").text() === 'undefined' ? '' : $("#accbPyblsInvcInvcCur1").text();
    var sbmtdTempltLovID = typeof $('#accbPyblsInvcDocTmpltID').val() === 'undefined' ? '' : $('#accbPyblsInvcDocTmpltID').val();
    var lnkArgs = 'grp=6&typ=1&pg=10&vtyp=4&sbmtdTempltLovID=' + sbmtdTempltLovID +
        '&accbPyblsInvcInvcCur=' + accbPyblsInvcInvcCur;
    openATab('#' + slctr, lnkArgs);
}

function insertOnlyScmSalesInvcRows(tableElmntID, cntr, inptHtml) {
    var nwRndm = 0;
    var curPos = Number($('#allOtherInputData99').val());
    var ttlrows = $('#' + tableElmntID + ' > tbody > tr').length;
    for (var i = 0; i < cntr; i++) {
        nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq(ttlrows - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq(ttlrows - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                if (curPos === ttlrows) {
                    $('#' + tableElmntID).append(nwInptHtml);
                } else if (curPos >= 1) {
                    $('#' + tableElmntID).append(nwInptHtml);
                    /*$('#' + tableElmntID + ' > tbody > tr').eq(curPos - 1).after(nwInptHtml);*/
                } else {
                    $('#' + tableElmntID).append(nwInptHtml);
                    /*$('#' + tableElmntID + ' > tbody > tr').eq(curPos).after(nwInptHtml);*/
                }
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }

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
    return nwRndm;
}

function insertNewScmSalesInvcRows(tableElmntID, position, inptHtml) {
    var nwRndm = insertOnlyScmSalesInvcRows(tableElmntID, 1, inptHtml);
    if (tableElmntID.indexOf("StockTrnsfr") !== -1) {
        chngStockTrnsfrStores();
    } else {
        var cntr = 0;
        $('#' + tableElmntID).find('tr').each(function (i, el) {
            if (i > 0) {
                if (typeof $(el).attr('id') === 'undefined') {
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
}

function lnkdEvntScmSalesInvcChng() {
    var scmSalesInvcEvntDocTyp = typeof $("#scmSalesInvcEvntDocTyp").val() === 'undefined' ? '' : $("#scmSalesInvcEvntDocTyp").val();
    var lovNm = "";
    if (scmSalesInvcEvntDocTyp === "None") {
        $("#scmSalesInvcEvntCtgryLbl").attr("disabled", "true");
        $("#scmSalesInvcEvntCtgry").val("");
        $("#scmSalesInvcEvntRgstr").val("");
        $("#scmSalesInvcEvntRgstrID").val("-1");
        $("#scmSalesInvcEvntRgstrLbl").attr("disabled", "true");
    } else if (scmSalesInvcEvntDocTyp === "Customer File Number") {
        $("#scmSalesInvcEvntCtgryLbl").attr("disabled", "true");
        $("#scmSalesInvcEvntCtgry").val("Petty Cash");
        $("#scmSalesInvcEvntRgstr").val("");
        $("#scmSalesInvcEvntRgstrID").val("-1");
        $("#scmSalesInvcEvntRgstrLbl").attr("disabled", "true");
    } else {
        $("#scmSalesInvcEvntCtgryLbl").removeAttr("disabled");
        $("#scmSalesInvcEvntCtgry").val("");
        $("#scmSalesInvcEvntRgstr").val("");
        $("#scmSalesInvcEvntRgstrID").val("-1");
        $("#scmSalesInvcEvntRgstrLbl").removeAttr("disabled");
    }
}

function getlnkdEvtAccbRILovCtgry(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc) {
    var scmSalesInvcEvntDocTyp = typeof $("#scmSalesInvcEvntDocTyp").val() === 'undefined' ? '' : $("#scmSalesInvcEvntDocTyp").val();
    var scmSalesInvcEvntRgstrID = typeof $("#scmSalesInvcEvntRgstrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcEvntRgstrID").val();
    if (scmSalesInvcEvntDocTyp === "Attendance Register" || scmSalesInvcEvntDocTyp === "Project Management") {
        lovNm = "Event Cost Categories";
    } else if (scmSalesInvcEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Run Stages";
    } else {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: 'Category not Required for this Type'
        });
        return false;
    }
    getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
        criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
        selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc);
}

function getlnkdEvtAccbRILovEvnt(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc) {
    var scmSalesInvcEvntDocTyp = typeof $("#scmSalesInvcEvntDocTyp").val() === 'undefined' ? '' : $("#scmSalesInvcEvntDocTyp").val();
    var scmSalesInvcEvntRgstrID = typeof $("#scmSalesInvcEvntRgstrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcEvntRgstrID").val();
    if (scmSalesInvcEvntDocTyp === "Attendance Register") {
        lovNm = "Attendance Registers";
    } else if (scmSalesInvcEvntDocTyp === "Project Management") {
        return false;
    } else if (scmSalesInvcEvntDocTyp === "Customer File Number") {
        lovNm = "Customer File Numbers";
    } else if (scmSalesInvcEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Runs";
    } else {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: 'Linked Document not Required for this Type'
        });
        return false;
    }
    getLovsPage(elementID, titleElementID, modalBodyID, lovNm, criteriaID,
        criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
        selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn, addtnlWhere, callBackFunc);
}

function getOneScmSalesInvcDocsForm(pKeyID, vwtype) {
    var lnkArgs = 'grp=12&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdScmSalesInvcID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', 'Sales Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        var table1 = $('#attchdSalesInvcDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdSalesInvcDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdSalesInvcDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToSalesInvcDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdHdrID, rowIDAttrb) {
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
    if (rhotrim(errMsg, '; ') !== '') {
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
        sendFileToSalesInvcDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {}
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToSalesInvcDocs(file, docNmElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daSalesInvcAttchmnt', file);
    data1.append('grp', 12);
    data1.append('typ', 1);
    data1.append('pg', 1);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 20);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdScmSalesInvcID', sbmtdHdrID);
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

function getAttchdSalesInvcDocs(actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdSalesInvcDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdSalesInvcDocsSrchFor").val();
    var srchIn = typeof $("#attchdSalesInvcDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdSalesInvcDocsSrchIn").val();
    var pageNo = typeof $("#attchdSalesInvcDocsPageNo").val() === 'undefined' ? 1 : $("#attchdSalesInvcDocsPageNo").val();
    var limitSze = typeof $("#attchdSalesInvcDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdSalesInvcDocsDsplySze").val();
    var sortBy = typeof $("#attchdSalesInvcDocsSortBy").val() === 'undefined' ? '' : $("#attchdSalesInvcDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModaly', actionDialog, 'Receivable Invoice Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdSalesInvcDocsTable')) {
            var table1 = $('#attchdSalesInvcDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdSalesInvcDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdSalesInvcDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdSalesInvcDocs(e, actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdSalesInvcDocs(actionText, slctr, linkArgs, actionDialog);
    }
}

function delAttchdSalesInvcDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdScmSalesInvcID").val() === 'undefined' ? -1 : $("#sbmtdScmSalesInvcID").val();
    var docNum = typeof $("#scmSalesInvcDocNum").val() === 'undefined' ? '' : $("#scmSalesInvcDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdSalesInvcDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdSalesInvcDocsRow' + rndmNum + '_AttchdDocsID').val();
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 1,
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

function getOneScmSalesInvcForm_bkp(pKeyID, vwtype, actionTxt, scmSalesInvcVchType, musAllwDues, srcCaller) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof scmSalesInvcVchType === 'undefined' || scmSalesInvcVchType === null) {
        scmSalesInvcVchType = 'Sales Invoice';
    }
    if (typeof musAllwDues === 'undefined' || musAllwDues === null) {
        musAllwDues = 'NO';
    }
    if (typeof srcCaller === 'undefined' || srcCaller === null) {
        srcCaller = 'SALES';
    }
    var sbmtdScmRtrnSrcDocID = typeof $('#sbmtdScmRtrnSrcDocID').val() === 'undefined' ? '-1' : $('#sbmtdScmRtrnSrcDocID').val();
    var qckPayPrsns_PrsnID = typeof $("#qckPayPrsns_PrsnID").val() === 'undefined' ? -1 : $("#qckPayPrsns_PrsnID").val();
    var qckPayItmSetID = typeof $("#qckPayItmSetID").val() === 'undefined' ? -1 : $("#qckPayItmSetID").val();
    var lnkArgs = 'grp=12&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdScmSalesInvcID=' + pKeyID +
        '&scmSalesInvcVchType=' + scmSalesInvcVchType + '&sbmtdScmRtrnSrcDocID=' + sbmtdScmRtrnSrcDocID +
        '&musAllwDues=' + musAllwDues + '&srcCaller=' + srcCaller +
        "&qckPayPrsns_PrsnID=" + qckPayPrsns_PrsnID + "&qckPayItmSetID=" + qckPayItmSetID;

    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Sales Document Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneScmSalesInvcEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            if (srcCaller === 'QUICK_PAY') {
                getQckPayPrsns('', '#allmodules', 'grp=7&typ=1&pg=4&vtyp=0');
            } else {
                getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=2');
            }
            $(e.currentTarget).unbind();
        });
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
            var linkArgs = 'grp=12&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('salesInvcExtraInfo') >= 0) {
                $('#addNwScmSalesInvcSmryBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcTaxBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcDscntBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcChrgBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcPrepayBtn').addClass('hideNotice');
            } else {
                $('#addNwScmSalesInvcSmryBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcTaxBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcDscntBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcChrgBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcPrepayBtn').removeClass('hideNotice');
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
        /*$(".jbDetAccRate").off('focus');
         $(".jbDetAccRate").focus(function () {
         $(this).select();
         });*/
        $('#oneScmSalesInvcSmryLinesTable tr').off('click');
        $('#oneScmSalesInvcSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneScmSalesInvcSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllScmSalesInvcSmryTtl(1);
        autoCreateSalesLns = -1;
        var scmSalesInvcApprvlStatus = typeof $("#scmSalesInvcApprvlStatus").val() === 'undefined' ? '' : $("#scmSalesInvcApprvlStatus").val();
        if (scmSalesInvcApprvlStatus === "Not Validated") {
            $("#oneScmSalesInvcSmryLinesTable tr:nth-of-type(1) .jbDetDesc").focus();
        } else {
            /*$("#scmSalesInvcTndrdAmnt").focus();*/
        }
    });
}

function getOneScmSalesInvcForm(pKeyID, vwtype, actionTxt, scmSalesInvcVchType, musAllwDues, srcCaller, crdtAnalysisID, appntmntID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof scmSalesInvcVchType === 'undefined' || scmSalesInvcVchType === null) {
        scmSalesInvcVchType = 'Sales Invoice';
    }
    if (typeof musAllwDues === 'undefined' || musAllwDues === null) {
        musAllwDues = 'NO';
    }
    if (typeof srcCaller === 'undefined' || srcCaller === null) {
        srcCaller = 'SALES';
    }
    if (typeof crdtAnalysisID === 'undefined' || crdtAnalysisID === null) {
        crdtAnalysisID = -1;
    }
    if (typeof appntmntID === 'undefined' || appntmntID === null) {
        appntmntID = -1;
    }

    var qckPayPrsnSetID = typeof $("#qckPayPrsnSetID").val() === 'undefined' ? '-1' : $("#qckPayPrsnSetID").val();
    var qckPayItmSetID = typeof $("#qckPayItmSetID").val() === 'undefined' ? '-1' : $("#qckPayItmSetID").val();
    var qckPayPrsns_PrsnID = typeof $("#qckPayPrsns_PrsnID").val() === 'undefined' ? '-1' : $("#qckPayPrsns_PrsnID").val();

    var sbmtdScmRtrnSrcDocID = typeof $('#sbmtdScmRtrnSrcDocID').val() === 'undefined' ? '-1' : $('#sbmtdScmRtrnSrcDocID').val();
    var lnkArgs = 'grp=12&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdScmSalesInvcID=' + pKeyID +
        '&scmSalesInvcVchType=' + scmSalesInvcVchType + '&sbmtdScmRtrnSrcDocID=' + sbmtdScmRtrnSrcDocID +
        '&musAllwDues=' + musAllwDues + '&srcCaller=' + srcCaller +
        '&crdtAnalysisID=' + crdtAnalysisID + '&appntmntID=' + appntmntID +
        "&qckPayPrsnSetID=" + qckPayPrsnSetID + "&qckPayItmSetID=" + qckPayItmSetID + "&qckPayPrsns_PrsnID=" + qckPayPrsns_PrsnID;

    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Sales Document Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
    //doAjaxWthCallBck(lnkArgs, 'myFormsModalLgYH', actionTxt, 'Sales Document Details (ID:' + pKeyID + ')', 'myFormsModalLgYHTitle', 'myFormsModalLgYHBody', function () {
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
        $('#oneScmSalesInvcEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        //$('#myFormsModalLgYH').off('hidden.bs.modal');
        //$('#myFormsModalLgYH').one('hidden.bs.modal', function (e) {
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            if (srcCaller === 'QUICK_PAY') {
                getQckPayPrsns('', '#allmodules', 'grp=7&typ=1&pg=4&vtyp=0');
            } else {
                if (appntmntID > 0) {

                } else {
                    getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=2');
                }
            }
            $(e.currentTarget).unbind();
        });
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
            var linkArgs = 'grp=12&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('salesInvcExtraInfotab') >= 0) {
                $('#addNwScmSalesInvcSmryBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcTaxBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcDscntBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcChrgBtn').addClass('hideNotice');
                $('#addNwScmSalesInvcPrepayBtn').addClass('hideNotice');
            } else {
                $('#addNwScmSalesInvcSmryBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcTaxBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcDscntBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcChrgBtn').removeClass('hideNotice');
                $('#addNwScmSalesInvcPrepayBtn').removeClass('hideNotice');
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
        calcAllScmSalesInvcSmryTtl(1);
        autoCreateSalesLns = -1;
        var scmSalesInvcApprvlStatus = typeof $("#scmSalesInvcApprvlStatus").val() === 'undefined' ? '' : $("#scmSalesInvcApprvlStatus").val();
        if (scmSalesInvcApprvlStatus === "Not Validated") {
            $("#oneScmSalesInvcSmryLinesTable tr:nth-of-type(1) .jbDetDesc").focus();
        } else {
            /*$("#scmSalesInvcTndrdAmnt").focus();*/
        }
    });
}

function calcAllScmSalesInvcSmryTtl(isServerRfrsh) {
    if (typeof isServerRfrsh === 'undefined' || isServerRfrsh === null) {
        isServerRfrsh = 0;
    }
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    var ln_UnitPrice = 0;
    var ln_QTY = 0;
    var ln_RentedQty = 0;
    $('#oneScmSalesInvcSmryLinesTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var prfxName = $(el).attr('id').split("_")[0];
                ln_QTY = ($("#" + prfxName + rndmNum + "_QTY").val() + ',').replace(/,/g, "");
                ln_RentedQty = ((typeof $("#" + prfxName + rndmNum + "_RentedQty").val() === 'undefined' ? '1' : $("#" + prfxName + rndmNum + "_RentedQty").val()) + ',').replace(/,/g, "");
                ln_UnitPrice = ($("#" + prfxName + rndmNum + "_UnitPrice").val() + ',').replace(/,/g, "");
                ttlRwAmount = Number(ln_QTY) * Number(ln_UnitPrice) * Number(ln_RentedQty);
                $("#" + prfxName + rndmNum + "_LineAmt").val(addCommas(ttlRwAmount.toFixed(2)));
                ttlAmount = ttlAmount + Number(ttlRwAmount);
            }
        }
    });
    $('#myCptrdSalesInvcValsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdSalesInvcValsTtlVal').val(ttlAmount.toFixed(2));
    $('#myCptrdRIJbSmryAmtTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdRIJbSmryAmtTtlVal').val(ttlAmount.toFixed(2));
    var myScmSalesInvcStatusBtn = $('#myScmSalesInvcStatusBtn').html();
    var scmRealInvcGrndTtl = Number($('#scmRealInvcGrndTtl').val().replace(/,/g, ""));
    if (isServerRfrsh === 0) {
        scmRealInvcGrndTtl = ttlAmount;
    }
    if (myScmSalesInvcStatusBtn.indexOf('Approved') === -1) {
        $('#scmSalesInvcTtlAmnt').val(addCommas(scmRealInvcGrndTtl.toFixed(2)));
    }
    var scmSalesInvcPaidAmnt = $('#scmSalesInvcPaidAmnt').val().replace(/,/g, "");
    $('#scmSalesInvcOustndngAmnt').val(addCommas((scmRealInvcGrndTtl - Number(scmSalesInvcPaidAmnt)).toFixed(2)));
}

function delScmSalesInvc(rowIDAttrb) {
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
    var msgPrt = "Item Transaction Document";
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 1,
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

function delScmSalesInvcDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#scmSalesInvcDocNum').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Document Line";
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 1,
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

function populateSalesDesc() {
    var scmSalesInvcDesc = typeof $("#scmSalesInvcDesc").val() === 'undefined' ? '' : $("#scmSalesInvcDesc").val();
    var scmSalesInvcClssfctn = typeof $("#scmSalesInvcClssfctn").val() === 'undefined' ? 'Standard' : $("#scmSalesInvcClssfctn").val();
    if (scmSalesInvcDesc.trim().length <= 50) {
        $("#scmSalesInvcDesc").val(scmSalesInvcClssfctn);
    } else if (scmSalesInvcDesc.trim().length > 50 && scmSalesInvcDesc.indexOf('vmsCstmrsGnrl') >= 0) {
        $("#scmSalesInvcDesc").val(scmSalesInvcDesc + " - " + scmSalesInvcClssfctn);
    }
}

function shwHideDuesPayDivs(whtToDo) {
    if (typeof whtToDo === 'undefined' || whtToDo === null) {
        whtToDo = typeof $("input[name='scmSalesInvcAllwDues']:checked").val() === 'undefined' ? 'hide' : 'show';
    }
    if (whtToDo === 'hide') {
        $('#scmSalesInvcAllwDuesDiv').addClass('hideNotice');
    } else {
        $('#scmSalesInvcAllwDuesDiv').removeClass('hideNotice');
    }
}

function shwHideDuesPayDivs1(srcElmnt) {
    if (typeof srcElmnt === 'undefined' || srcElmnt === null) {
        srcElmnt = 'LABEL';
    }
    var whtToDo = typeof $("input[name='scmSalesInvcAllwDues']:checked").val() === 'undefined' ? 'hide' : 'show';
    var whtToDo1 = (whtToDo === 'hide') ? 'show' : 'hide';
    if (srcElmnt !== 'LABEL') {
        whtToDo1 = whtToDo;
    } else {
        $("input[name='scmSalesInvcAllwDues']").trigger("click");
    }
    shwHideDuesPayDivs(whtToDo1);
}

function runMassPyValuesForm(rptID, alrtID, paramsStr, invcSrc, srcCaller) {
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof srcCaller === 'undefined' || srcCaller === null) {
        srcCaller = 'SALES';
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
    var sbmtdScmSalesInvcID = typeof $("#sbmtdScmSalesInvcID").val() === 'undefined' ? '-1' : $("#sbmtdScmSalesInvcID").val();
    getSilentRptsRnSts(rptID, alrtID, paramsStr, function () {
        if (srcCaller === 'QUICK_PAY') {
            getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', '1', srcCaller);
        } else {
            if (invcSrc === 'QUICK_SALE') {
                getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1');
            } else {
                getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', '1', srcCaller);
            }
        }
    });
    return false;
}

function saveScmSalesInvcForm(funcur, shdSbmt, invcSrc, musAllwDues, srcCaller) {
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof musAllwDues === 'undefined' || musAllwDues === null) {
        musAllwDues = 'NO';
    }
    if (typeof srcCaller === 'undefined' || srcCaller === null) {
        srcCaller = 'SALES';
    }
    calcAllScmSalesInvcSmryTtl();
    var sbmtdScmSalesInvcID = typeof $("#sbmtdScmSalesInvcID").val() === 'undefined' ? -1 : $("#sbmtdScmSalesInvcID").val();
    var scmSalesInvcDocNum = typeof $("#scmSalesInvcDocNum").val() === 'undefined' ? '' : $("#scmSalesInvcDocNum").val();
    var scmSalesInvcDfltTrnsDte = typeof $("#scmSalesInvcDfltTrnsDte").val() === 'undefined' ? '' : $("#scmSalesInvcDfltTrnsDte").val();
    var scmSalesInvcVchType = typeof $("#scmSalesInvcVchType").val() === 'undefined' ? '' : $("#scmSalesInvcVchType").val();
    var scmSalesInvcInvcCur = typeof $("#scmSalesInvcInvcCur").val() === 'undefined' ? '' : $("#scmSalesInvcInvcCur").val();
    var scmSalesInvcTtlAmnt = typeof $("#scmSalesInvcTtlAmnt").val() === 'undefined' ? '0.00' : $("#scmSalesInvcTtlAmnt").val();
    var scmSalesInvcEvntDocTyp = typeof $("#scmSalesInvcEvntDocTyp").val() === 'undefined' ? '' : $("#scmSalesInvcEvntDocTyp").val();
    var scmSalesInvcEvntCtgry = typeof $("#scmSalesInvcEvntCtgry").val() === 'undefined' ? '' : $("#scmSalesInvcEvntCtgry").val();
    var scmSalesInvcEvntRgstrID = typeof $("#scmSalesInvcEvntRgstrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcEvntCtgry").val();
    var scmSalesInvcCstmrID = typeof $("#scmSalesInvcCstmrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcCstmrID").val();
    var scmSalesInvcCstmrSiteID = typeof $("#scmSalesInvcCstmrSiteID").val() === 'undefined' ? '-1' : $("#scmSalesInvcCstmrSiteID").val();
    var scmSalesInvcBrnchID = typeof $("#scmSalesInvcBrnchID").val() === 'undefined' ? '-1' : $("#scmSalesInvcBrnchID").val();

    var scmSalesInvcDesc = typeof $("#scmSalesInvcDesc").val() === 'undefined' ? '' : $("#scmSalesInvcDesc").val();
    var scmSalesInvcClssfctn = typeof $("#scmSalesInvcClssfctn").val() === 'undefined' ? 'Standard' : $("#scmSalesInvcClssfctn").val();
    var scmSalesInvcPayTerms = typeof $("#scmSalesInvcPayTerms").val() === 'undefined' ? '' : $("#scmSalesInvcPayTerms").val();
    var scmSalesInvcDfltBalsAcntID = typeof $("#scmSalesInvcDfltBalsAcntID").val() === 'undefined' ? -1 : $("#scmSalesInvcDfltBalsAcntID").val();
    var myCptrdSalesInvcValsTtlVal = typeof $("#myCptrdSalesInvcValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdSalesInvcValsTtlVal").val();

    var scmSalesInvcPayMthdID = typeof $("#scmSalesInvcPayMthdID").val() === 'undefined' ? '-1' : $("#scmSalesInvcPayMthdID").val();
    var otherModuleDocTyp = typeof $("#otherModuleDocTyp").val() === 'undefined' ? '' : $("#otherModuleDocTyp").val();
    var otherModuleDocId = typeof $("#otherModuleDocId").val() === 'undefined' ? '-1' : $("#otherModuleDocId").val();

    var scmSalesInvcExRate = typeof $("#scmSalesInvcExRate").val() === 'undefined' ? '1.000' : $("#scmSalesInvcExRate").val();
    var srcSalesInvcDocID = typeof $("#srcSalesInvcDocID").val() === 'undefined' ? '-1' : $("#srcSalesInvcDocID").val();
    var scmSalesInvcCstmrInvcNum = typeof $("#scmSalesInvcCstmrInvcNum").val() === 'undefined' ? '' : $("#scmSalesInvcCstmrInvcNum").val();

    var scmSalesInvcPyItmSetID = typeof $("#scmSalesInvcPyItmSetID").val() === 'undefined' ? '-1' : $("#scmSalesInvcPyItmSetID").val();
    var scmSalesInvcPyItmSetNm = typeof $("#scmSalesInvcPyItmSetNm").val() === 'undefined' ? '' : $("#scmSalesInvcPyItmSetNm").val();
    var scmSalesInvcPyAmntGvn = typeof $("#scmSalesInvcPyAmntGvn").val() === 'undefined' ? '0.00' : $("#scmSalesInvcPyAmntGvn").val();
    var scmSalesInvcPyChqNumber = typeof $("#scmSalesInvcPyChqNumber").val() === 'undefined' ? '' : $("#scmSalesInvcPyChqNumber").val();
    var scmSalesInvcPySignCode = typeof $("#scmSalesInvcPySignCode").val() === 'undefined' ? '' : $("#scmSalesInvcPySignCode").val();
    var scmSalesInvcAllwDues = typeof $("input[name='scmSalesInvcAllwDues']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var scmSalesInvcAplyAdvnc = typeof $("input[name='scmSalesInvcAplyAdvnc']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var scmSalesInvcKeepExcss = typeof $("input[name='scmSalesInvcKeepExcss']:checked").val() === 'undefined' ? 'NO' : 'YES';

    var errMsg = "";
    /*if (scmSalesInvcDocNum.trim() === '' || scmSalesInvcVchType.trim() === '' || scmSalesInvcClssfctn.trim() === '')
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Document Number/Type and Classification cannot be empty!</span></p>';
     }*/
    if (Number(otherModuleDocId.replace(/[^-?0-9\.]/g, '')) > 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cannot work on Documents from Other Modules Here!</span></p>';
    }
    /*if (scmSalesInvcDesc.trim() === '')
     {
     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
     'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
     }*/
    if (scmSalesInvcDfltTrnsDte.trim() === '' || scmSalesInvcInvcCur.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Batch Date/Currency cannot be empty!</span></p>';
    }
    scmSalesInvcTtlAmnt = fmtAsNumber('scmSalesInvcTtlAmnt').toFixed(2);
    myCptrdSalesInvcValsTtlVal = fmtAsNumber('myCptrdSalesInvcValsTtlVal').toFixed(2);
    scmSalesInvcExRate = fmtAsNumber2('scmSalesInvcExRate').toFixed(4);
    if (myCptrdSalesInvcValsTtlVal !== scmSalesInvcTtlAmnt) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    if (scmSalesInvcAllwDues === 'YES') {
        scmSalesInvcPyAmntGvn = fmtAsNumber('scmSalesInvcPyAmntGvn').toFixed(2);
        if (Number(scmSalesInvcPyItmSetID.replace(/[^-?0-9\.]/g, '')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Item Set cannot be empty for Dues/Bills-Enabled Invoices!</span></p>';
        }
    }

    var isVld = true;
    var slctdDetTransLines = "";
    var slctdExtraInfoLines = "";
    $('#oneScmSalesInvcSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_TrnsLnID').val();
                var ln_ItmID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ItmID').val();
                var ln_StoreID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_StoreID').val();
                var ln_CnsgnIDs = $('#oneScmSalesInvcSmryRow' + rndmNum + '_CnsgnIDs').val();
                var ln_ItmAccnts = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ItmAccnts').val();
                var ln_LnkdPrsnID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_LnkdPrsnID').val();
                var ln_LineDesc = $('#oneScmSalesInvcSmryRow' + rndmNum + '_LineDesc').val();
                var ln_QTY = $('#oneScmSalesInvcSmryRow' + rndmNum + '_QTY').val();
                var ln_UnitPrice = $('#oneScmSalesInvcSmryRow' + rndmNum + '_UnitPrice').val();
                var ln_TaxID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_TaxID').val();
                var ln_DscntID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_DscntID').val();
                var ln_ChrgID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ChrgID').val();
                var ln_SrcDocLnID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_SrcDocLnID').val();
                var ln_ExtraDesc = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ExtraDesc').val();
                var ln_RentedQty = $('#oneScmSalesInvcSmryRow' + rndmNum + '_RentedQty').val();
                var ln_OthrMdlID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_OthrMdlID').val();
                var ln_OthrMdlTyp = $('#oneScmSalesInvcSmryRow' + rndmNum + '_OthrMdlTyp').val();
                if (Number(ln_ItmID.replace(/[^-?0-9\.]/g, '')) > 0) {
                    if (ln_LineDesc.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Item Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneScmSalesInvcSmryRow' + rndmNum + '_LineDesc').addClass('rho-error');
                    } else {
                        $('#oneScmSalesInvcSmryRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                    }
                    if (Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Quantity for Row No. ' + i + ' cannot be zero or less!</span></p>';
                        $('#oneScmSalesInvcSmryRow' + rndmNum + '_QTY').addClass('rho-error');
                    } else {
                        $('#oneScmSalesInvcSmryRow' + rndmNum + '_QTY').removeClass('rho-error');
                    }
                    /*if (Number(ln_UnitPrice.replace(/[^-?0-9\.]/g, '')) === 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Selling Price for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneScmSalesInvcSmryRow' + rndmNum + '_UnitPrice').addClass('rho-error');
                     } else {
                     $('#oneScmSalesInvcSmryRow' + rndmNum + '_UnitPrice').removeClass('rho-error');
                     }*/
                    if (isVld === true) {
                        slctdDetTransLines = slctdDetTransLines +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StoreID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CnsgnIDs.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmAccnts.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LnkdPrsnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_QTY.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_UnitPrice.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_TaxID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_DscntID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ChrgID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SrcDocLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ExtraDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_RentedQty.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_OthrMdlID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_OthrMdlTyp.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
        title: 'Save ' + scmSalesInvcVchType,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + scmSalesInvcVchType + '...Please Wait...</p>',
        callback: function () {
            if (shdSbmt === 2) {
                if (srcCaller === 'QUICK_PAY') {
                    getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', musAllwDues, srcCaller);
                } else {
                    if (invcSrc === 'QUICK_SALE') {
                        getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1');
                    } else {
                        getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', musAllwDues, srcCaller);
                    }
                }
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 1);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdScmSalesInvcID', sbmtdScmSalesInvcID);
    formData.append('scmSalesInvcDocNum', scmSalesInvcDocNum);
    formData.append('scmSalesInvcDfltTrnsDte', scmSalesInvcDfltTrnsDte);
    formData.append('scmSalesInvcVchType', scmSalesInvcVchType);
    formData.append('scmSalesInvcInvcCur', scmSalesInvcInvcCur);
    formData.append('scmSalesInvcTtlAmnt', scmSalesInvcTtlAmnt);
    formData.append('scmSalesInvcEvntDocTyp', scmSalesInvcEvntDocTyp);
    formData.append('scmSalesInvcEvntCtgry', scmSalesInvcEvntCtgry);
    formData.append('scmSalesInvcEvntRgstrID', scmSalesInvcEvntRgstrID);
    formData.append('scmSalesInvcCstmrID', scmSalesInvcCstmrID);
    formData.append('scmSalesInvcCstmrSiteID', scmSalesInvcCstmrSiteID);
    formData.append('scmSalesInvcDfltBalsAcntID', scmSalesInvcDfltBalsAcntID);
    formData.append('scmSalesInvcPayTerms', scmSalesInvcPayTerms);
    formData.append('scmSalesInvcDesc', scmSalesInvcDesc);
    formData.append('scmSalesInvcClssfctn', scmSalesInvcClssfctn);

    formData.append('scmSalesInvcPayMthdID', scmSalesInvcPayMthdID);
    formData.append('scmSalesInvcExRate', scmSalesInvcExRate);
    formData.append('otherModuleDocTyp', otherModuleDocTyp);
    formData.append('otherModuleDocId', otherModuleDocId);
    formData.append('srcSalesInvcDocID', srcSalesInvcDocID);
    formData.append('scmSalesInvcCstmrInvcNum', scmSalesInvcCstmrInvcNum);
    formData.append('scmSalesInvcBrnchID', scmSalesInvcBrnchID);

    formData.append('scmSalesInvcPyItmSetID', scmSalesInvcPyItmSetID);
    formData.append('scmSalesInvcAllwDues', scmSalesInvcAllwDues);
    formData.append('scmSalesInvcAplyAdvnc', scmSalesInvcAplyAdvnc);
    formData.append('scmSalesInvcKeepExcss', scmSalesInvcKeepExcss);
    formData.append('scmSalesInvcPyAmntGvn', scmSalesInvcPyAmntGvn);
    formData.append('scmSalesInvcPyChqNumber', scmSalesInvcPyChqNumber);
    formData.append('scmSalesInvcPySignCode', scmSalesInvcPySignCode);

    formData.append('musAllwDues', musAllwDues);
    formData.append('srcCaller', srcCaller);
    formData.append('shdSbmt', shdSbmt);
    formData.append('slctdDetTransLines', slctdDetTransLines);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                        if (data.message.indexOf("Success") !== -1 || data.message.indexOf("SUCCESS") !== -1) {
                            sbmtdScmSalesInvcID = data.sbmtdScmSalesInvcID;
                            if (srcCaller === 'QUICK_PAY') {
                                getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', musAllwDues, srcCaller);
                            } else {
                                dialog.modal('hide');
                                if (invcSrc === 'QUICK_SALE') {
                                    getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1');
                                } else {
                                    getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', musAllwDues, srcCaller);
                                }
                            }
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveScmSalesInvcRvrslForm(funcCur, shdSbmt, invcSrc, musAllwDues, srcCaller) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslScmSalesInvcBtn");
    }
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof musAllwDues === 'undefined' || musAllwDues === null) {
        musAllwDues = 'NO';
    }
    if (typeof srcCaller === 'undefined' || srcCaller === null) {
        srcCaller = 'SALES';
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdScmSalesInvcID = typeof $("#sbmtdScmSalesInvcID").val() === 'undefined' ? -1 : $("#sbmtdScmSalesInvcID").val();
    var scmSalesInvcDesc = typeof $("#scmSalesInvcDesc").val() === 'undefined' ? '' : $("#scmSalesInvcDesc").val();
    var scmSalesInvcDesc1 = typeof $("#scmSalesInvcDesc1").val() === 'undefined' ? '' : $("#scmSalesInvcDesc1").val();
    var scmSalesInvcVchType = typeof $("#scmSalesInvcVchType").val() === 'undefined' ? '' : $("#scmSalesInvcVchType").val();
    var otherModuleDocTyp = typeof $("#otherModuleDocTyp").val() === 'undefined' ? '' : $("#otherModuleDocTyp").val();
    var otherModuleDocId = typeof $("#otherModuleDocId").val() === 'undefined' ? '-1' : $("#otherModuleDocId").val();
    var errMsg = "";
    if (sbmtdScmSalesInvcID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (Number(otherModuleDocId.replace(/[^-?0-9\.]/g, '')) > 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cannot work on Documents from Other Modules Here!</span></p>';
    }
    if (scmSalesInvcDesc === "" || scmSalesInvcDesc === scmSalesInvcDesc1) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#scmSalesInvcDesc").addClass('rho-error');
        $("#scmSalesInvcDesc").attr("readonly", false);
        $("#fnlzeRvrslScmSalesInvcBtn").attr("disabled", false);
        if (invcSrc === 'QUICK_SALE') {
            $('#salesInvcExtraInfotab').tab('show');
        }
    } else {
        $("#scmSalesInvcDesc").removeClass('rho-error');
    }
    if (rhotrim(errMsg, '; ') !== '') {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var msgsTitle = scmSalesInvcVchType;
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' + msgsTitle + '?<br/>This action cannot be UNDONE!</p>';
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
                var msg = 'Item Transaction';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        sbmtdScmSalesInvcID = typeof $("#sbmtdScmSalesInvcID").val() === 'undefined' ? -1 : $("#sbmtdScmSalesInvcID").val();
                        if (sbmtdScmSalesInvcID > 0) {
                            if (srcCaller === 'QUICK_PAY') {
                                getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', '1', srcCaller);
                            } else {
                                if (invcSrc === 'QUICK_SALE') {
                                    getScmSalesInvc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1');
                                } else {
                                    getOneScmSalesInvcForm(sbmtdScmSalesInvcID, 3, 'ReloadDialog', '', '1', srcCaller);
                                }
                            }
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
                                grp: 12,
                                typ: 1,
                                pg: 1,
                                actyp: 1,
                                q: 'VOID',
                                scmSalesInvcDesc: scmSalesInvcDesc,
                                sbmtdScmSalesInvcID: sbmtdScmSalesInvcID,
                                musAllwDues: musAllwDues,
                                srcCaller: srcCaller,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                                    obj = $.parseJSON(data);
                                    sbmtdScmSalesInvcID = obj.sbmtdScmSalesInvcID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdScmSalesInvcID > 0) {
                                        $("#sbmtdScmSalesInvcID").val(sbmtdScmSalesInvcID);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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
        }
    });
}

function getOneScmUOMBrkdwnForm(pKeyID, vwtype, rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var sbmtdTblRowID = 'oneINVQtyBrkDwnTable';
    var sbmtdItemID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
    var varTtlQtyStr = $('#' + rowPrfxNm + rndmNum + '_QTY').val();
    var varTtlQty = Number(varTtlQtyStr.replace(/[^-?0-9\.]/g, ''));
    var sbmtdCrncyNm = typeof $("#scmSalesInvcInvcCur1").text() === 'undefined' ? '' : $("#scmSalesInvcInvcCur1").text();
    if (rowIDAttrb.indexOf("CnsgnRcpt") !== -1) {
        sbmtdCrncyNm = typeof $("#scmCnsgnRcptInvcCur1").text() === 'undefined' ? '' : $("#scmCnsgnRcptInvcCur1").text();
    } else if (rowIDAttrb.indexOf("StockTrnsfr") !== -1) {
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

function calcScmUomBrkdwnRowVal(tblRowID) {
    var rndmNum = tblRowID.split("_")[1];
    var ttlAmount = 0;
    var ttlQty = 0;
    var ttlDenomVal = 0;
    var qty = $('#oneINVQtyBrkRow' + rndmNum + '_BaseQty').val();
    var cnvFctr = $('#oneINVQtyBrkRow' + rndmNum + '_CnvFctr').val();
    var val = $('#oneINVQtyBrkRow' + rndmNum + '_UntVal').val();
    ttlDenomVal = (Number(qty.replace(/[^-?0-9\.]/g, '')) * Number(val.replace(/[^-?0-9\.]/g, '')));
    $('#oneINVQtyBrkRow' + rndmNum + '_BaseQty').val(Number(qty.replace(/[^-?0-9\.]/g, '')));
    $('#oneINVQtyBrkRow' + rndmNum + '_EquivQty').val((Number(qty.replace(/[^-?0-9\.]/g, '')) * cnvFctr));
    $('#oneINVQtyBrkRow' + rndmNum + '_TtlVal').val(addCommas(ttlDenomVal.toFixed(2)));
    $('#oneINVQtyBrkDwnTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum1 = $(el).attr('id').split("_")[1];
                var qty1 = $('#oneINVQtyBrkRow' + rndmNum1 + '_BaseQty').val();
                var qty2 = $('#oneINVQtyBrkRow' + rndmNum1 + '_EquivQty').val();
                var val1 = $('#oneINVQtyBrkRow' + rndmNum1 + '_UntVal').val();
                ttlQty = ttlQty + Number(qty2.replace(/[^-?0-9\.]/g, ''));
                ttlAmount = ttlAmount + (Number(qty1.replace(/[^-?0-9\.]/g, '')) * Number(val1.replace(/[^-?0-9\.]/g, '')));
            }
        }
    });
    $('#myCptrdQtyTtlBtn').text(ttlQty);
    $('#myCptrdQtyTtlVal').val(ttlQty);
    var oneINVQtyBrkRowIDAttrb = $('#oneINVQtyBrkRowIDAttrb').val();
    var crncyNm = $('#scmSalesInvcInvcCur1').text();
    if (oneINVQtyBrkRowIDAttrb.indexOf("CnsgnRcpt") !== -1) {
        crncyNm = $('#scmCnsgnRcptInvcCur1').text();
    }
    $('#myCptrdUmValsTtlBtn').text(crncyNm + ' ' + addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdUmValsTtlVal').val(ttlAmount.toFixed(2));
}

function applyNewINVQtyVal(v_rndmNum, modalID, tblRowID) {
    var rowPrfxNm = tblRowID.split("_")[0];
    var rndmNum = tblRowID.split("_")[1];
    var qtyVal = $('#myCptrdQtyTtlVal').val();
    var ttlAmnt = $('#myCptrdUmValsTtlVal').val();
    $('#' + rowPrfxNm + rndmNum + '_QTY').val(Number(qtyVal));
    $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(addCommas((Number(ttlAmnt) / Number(qtyVal)).toFixed(5)));
    if (tblRowID.indexOf("CnsgnRcpt") !== -1) {
        calcAllScmCnsgnRcptSmryTtl('oneScmCnsgnRcptSmryLinesTable');
    } else if (tblRowID.indexOf("StockTrnsfr") !== -1) {
        calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');
    } else if (tblRowID.indexOf("PrchsDoc") !== -1) {
        calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');
    } else {
        calcAllScmSalesInvcSmryTtl();
    }
    $('#' + modalID).modal('hide');
}

function invTrnsUomFormKeyPress(event, rowIDAttrb) {
    //alert(event.which);
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var curItemVal = getRowIndx(rowIDAttrb, 'oneINVQtyBrkDwnTable');
        if (curItemVal === getTtlRows('oneINVQtyBrkDwnTable') - 1) {
            nextItem = $('.invUmbQty').eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $('.invUmbQty').eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function afterSalesInvcItmSlctn(rowIDAttrb) {
    if (autoCreateSalesLns === -1) {
        return false;
    }
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var nwItemNm = $('#' + rowPrfxNm + rndmNum + '_LineDesc').val();
    var nwItemID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
    var sbmtdStoreID = typeof $('#' + rowPrfxNm + rndmNum + '_StoreID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_StoreID').val();
    var scmSalesInvcCstmrID = typeof $("#scmSalesInvcCstmrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcCstmrID").val();
    var scmSalesInvcVchType = typeof $("#scmSalesInvcVchType").val() === 'undefined' ? '' : $("#scmSalesInvcVchType").val();
    if (rowIDAttrb.indexOf("CnsgnRcpt") !== -1) {
        scmSalesInvcCstmrID = typeof $("#scmCnsgnRcptCstmrID").val() === 'undefined' ? '-1' : $("#scmCnsgnRcptCstmrID").val();
        scmSalesInvcVchType = typeof $("#scmCnsgnRcptType").val() === 'undefined' ? '' : $("#scmCnsgnRcptType").val();
    } else if (rowIDAttrb.indexOf("StockTrnsfr") !== -1) {
        scmSalesInvcCstmrID = -1;
        scmSalesInvcVchType = 'Stock Transfer';
        sbmtdStoreID = typeof $('#scmStockTrnsfrSrcStoreID').val() === 'undefined' ? '-1' : $("#scmStockTrnsfrSrcStoreID").val();
    } else if (rowIDAttrb.indexOf("PrchsDoc") !== -1) {
        scmSalesInvcCstmrID = typeof $("#scmPrchsDocSpplrID").val() === 'undefined' ? '-1' : $("#scmPrchsDocSpplrID").val();
        scmSalesInvcVchType = typeof $("#scmPrchsDocVchType").val() === 'undefined' ? '' : $("#scmPrchsDocVchType").val();
    }
    var nwSalesDocLineHtm = typeof $("#nwSalesDocLineHtm").val() === 'undefined' ? '' : $("#nwSalesDocLineHtm").val();
    if (nwItemNm.trim() !== "") {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp', 12);
            formData.append('typ', 1);
            formData.append('pg', 3);
            formData.append('q', 'VIEW');
            formData.append('vtyp', 5);
            formData.append('searchfor', nwItemNm);
            formData.append('sbmtdItemID', nwItemID);
            formData.append('sbmtdStoreID', sbmtdStoreID);
            formData.append('qCnsgnOnly', 'false');
            formData.append('sbmtdDocType', scmSalesInvcVchType);
            formData.append('sbmtdCstmrSiteID', scmSalesInvcCstmrID);
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
                        $('#' + rowPrfxNm + rndmNum + '_ItmID').val(data.ln_ItmID);
                        $('#' + rowPrfxNm + rndmNum + '_LineDesc').val(data.ln_ItmNm);
                        $('#' + rowPrfxNm + rndmNum + '_UomID').val(data.ln_UomID);
                        $('#' + rowPrfxNm + rndmNum + '_UomNm1').text(data.ln_UomNm);

                        if (rowIDAttrb.indexOf("CnsgnRcpt") !== -1) {
                            $('#' + rowPrfxNm + rndmNum + '_StoreID').val(data.ln_StoreID);
                            $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(data.ln_CostPrice);
                            $('#' + rowPrfxNm + rndmNum + '_StoreNm').val(data.ln_StoreNm);
                        } else if (rowIDAttrb.indexOf("StockTrnsfr") !== -1) {
                            if (Number(sbmtdStoreID.replace(/[^-?0-9\.]/g, '')) > 0) {
                                $('#' + rowPrfxNm + rndmNum + '_SrcQTY').val(data.ln_AvlblQty);
                            }
                            $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(data.ln_CostPrice);
                        } else if (rowIDAttrb.indexOf("PrchsDoc") !== -1) {
                            $('#' + rowPrfxNm + rndmNum + '_StoreID').val(data.ln_StoreID);
                            $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(data.ln_CostPrice);
                            $('#' + rowPrfxNm + rndmNum + '_TaxID').val(data.ln_TaxID);
                            $('#' + rowPrfxNm + rndmNum + '_DscntID').val(data.ln_DscntID);
                            $('#' + rowPrfxNm + rndmNum + '_ChrgID').val(data.ln_ChrgID);
                        } else {
                            $('#' + rowPrfxNm + rndmNum + '_StoreID').val(data.ln_StoreID);
                            if (scmSalesInvcVchType === "Item Issue-Unbilled" || scmSalesInvcVchType === "Internal Item Request") {
                                $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(data.ln_CostPrice);
                            } else {
                                $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val(data.ln_SellPrice);
                            }
                            $('#' + rowPrfxNm + rndmNum + '_TaxID').val(data.ln_TaxID);
                            $('#' + rowPrfxNm + rndmNum + '_DscntID').val(data.ln_DscntID);
                            $('#' + rowPrfxNm + rndmNum + '_ChrgID').val(data.ln_ChrgID);
                            $('#' + rowPrfxNm + rndmNum + '_ItmAccnts').val(data.ln_InvAcntID + ";" + data.ln_CogsAcntID + ";" + data.ln_SalesRevAcntID + ";" + data.ln_SalesRetAcntID + ";" + data.ln_PrchsRetAcntID + ";" + data.ln_ExpnsAcntID);
                        }
                        var curQTY = $('#' + rowPrfxNm + rndmNum + '_QTY').val();
                        if (Number(curQTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            $('#' + rowPrfxNm + rndmNum + '_QTY').val(1);
                        }
                        var nwTableElmntID = 'oneScmSalesInvcSmryLinesTable';
                        if (rowIDAttrb.indexOf("CnsgnRcpt") !== -1) {
                            nwTableElmntID = 'oneScmCnsgnRcptSmryLinesTable';
                            calcAllScmCnsgnRcptSmryTtl(nwTableElmntID);
                        } else if (rowIDAttrb.indexOf("StockTrnsfr") !== -1) {
                            nwTableElmntID = 'oneScmStockTrnsfrSmryLinesTable';
                            chngStockTrnsfrStores();
                            calcAllScmCnsgnRcptSmryTtl(nwTableElmntID);
                        } else if (rowIDAttrb.indexOf("PrchsDoc") !== -1) {
                            nwTableElmntID = 'oneScmPrchsDocSmryLinesTable';
                            calcAllScmCnsgnRcptSmryTtl(nwTableElmntID);
                        } else {
                            nwTableElmntID = 'oneScmSalesInvcSmryLinesTable';
                            calcAllScmSalesInvcSmryTtl();
                        }
                        var ttlrows = $('#' + nwTableElmntID + ' > tbody > tr').length;
                        $('#allOtherInputData99').val(ttlrows);
                        autoCreateSalesLns = -1;
                        var freeRowNum = getFreeRowNum('_ItmID', nwTableElmntID);
                        if (freeRowNum.trim() !== "") {
                            $('#' + rowPrfxNm + freeRowNum + '_LineDesc').focus();
                        } else {
                            var nwRndm = insertOnlyScmSalesInvcRows(nwTableElmntID, 1, nwSalesDocLineHtm);
                            $('#' + rowPrfxNm + nwRndm + '_LineDesc').focus();
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                    autoCreateSalesLns = -1;
                }
            });
        });
    } else {
        $('#' + rowPrfxNm + rndmNum + '_ItmID').val(-1);
        $('#' + rowPrfxNm + rndmNum + '_LineDesc').val("");
        $('#' + rowPrfxNm + rndmNum + '_UomID').val(-1);
        $('#' + rowPrfxNm + rndmNum + '_UomNm1').text("Each");
        $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val("0.00");
        $('#' + rowPrfxNm + rndmNum + '_QTY').val(0);
        if (rowIDAttrb.indexOf("CnsgnRcpt") !== -1) {
            $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val("0.00");
            /*$('#' + rowPrfxNm + rndmNum + '_StoreNm').val("");*/
        } else if (rowIDAttrb.indexOf("StockTrnsfr") !== -1) {
            $('#' + rowPrfxNm + rndmNum + '_UnitPrice').val("0.00");
            $('#' + rowPrfxNm + rndmNum + '_SrcQTY').val(0);
        } else if (rowIDAttrb.indexOf("PrchsDoc") !== -1) {
            $('#' + rowPrfxNm + rndmNum + '_TaxID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_DscntID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_ChrgID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_StoreID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_SrcQTY').val(0);
        } else {
            $('#' + rowPrfxNm + rndmNum + '_TaxID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_DscntID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_ChrgID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_StoreID').val(-1);
            $('#' + rowPrfxNm + rndmNum + '_ItmAccnts').val(",");
        }
    }
}

function getInvRcvblsAcntInfo() {
    var scmSalesInvcCstmrID = typeof $("#scmSalesInvcCstmrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcCstmrID").val();
    var scmSalesInvcCstmrSiteID = typeof $("#scmSalesInvcCstmrSiteID").val() === 'undefined' ? '-1' : $("#scmSalesInvcCstmrSiteID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('pg', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        formData.append('scmSalesInvcCstmrID', scmSalesInvcCstmrID);
        formData.append('scmSalesInvcCstmrSiteID', scmSalesInvcCstmrSiteID);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#scmSalesInvcDfltBalsAcnt').val(data.BalsAcntNm);
                $('#scmSalesInvcDfltBalsAcntID').val(data.BalsAcntID);
                $('#scmSalesInvcCstmrSite').val(data.scmSalesInvcCstmrSiteNm);
                $('#scmSalesInvcCstmrSiteID').val(data.scmSalesInvcCstmrSiteID);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}


function quickSalePayAmntKeyFunc(e, pKeyID, extraPKeyID, extraPKeyType) {
    var actionTxt = 'ShowDialog';
    var entrCount = typeof $("#allOtherInputData3").val() === 'undefined' ? '0' : $("#allOtherInputData3").val();
    entrCount = Number(entrCount.replace(/[^-?0-9\.]/g, '')) + 1;
    $("#allOtherInputData3").val(entrCount);
    if (entrCount > 1) {
        actionTxt = 'ReloadDialog';
    }
    var scmSalesInvcCanTakeDpsts = typeof $("#scmSalesInvcCanTakeDpsts").val() === 'undefined' ? '0' : $("#scmSalesInvcCanTakeDpsts").val();
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode === 13) {
        if (Number(scmSalesInvcCanTakeDpsts.replace(/[^-?0-9\.]/g, '')) === 99) {
            getOneAccbPayInvcForm(pKeyID, 'Customer Payments', actionTxt, -1, extraPKeyID, extraPKeyType, 'scmSalesInvcTndrdAmnt', 'QUICK_SALE', 'scmSalesInvcCstmrID', 'scmSalesInvcInvcCur');
        } else if (Number(scmSalesInvcCanTakeDpsts.replace(/[^-?0-9\.]/g, '')) === 98) {
            getOneAccbPayInvcForm(pKeyID, 'Customer Payments', actionTxt, -1, extraPKeyID, extraPKeyType, 'scmSalesInvcTndrdAmnt', 'QUICK_SALE');
        }
        var btnExists = typeof $("#saveQuickInvPayBtn").val() === 'undefined' ? false : true;
        setTimeout(function () {
            btnExists = typeof $("#saveQuickInvPayBtn").val() === 'undefined' ? false : true;
            if (btnExists === true) {
                $('#saveQuickInvPayBtn').focus();
                $("#saveQuickInvPayBtn").select();
            } else {
                setTimeout(function () {
                    btnExists = typeof $("#saveQuickInvPayBtn").val() === 'undefined' ? false : true;
                    if (btnExists === true) {
                        $('#saveQuickInvPayBtn').focus();
                        $("#saveQuickInvPayBtn").select();
                    } else {
                        setTimeout(function () {
                            btnExists = typeof $("#saveQuickInvPayBtn").val() === 'undefined' ? false : true;
                            if (btnExists === true) {
                                $('#saveQuickInvPayBtn').focus();
                                $("#saveQuickInvPayBtn").select();
                            }
                        }, 2500);
                    }
                }, 2000);
            }
        }, 1000);
    }
}

function getScmPrchsDoc(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#scmPrchsDocSrchFor").val() === 'undefined' ? '%' : $("#scmPrchsDocSrchFor").val();
    var srchIn = typeof $("#scmPrchsDocSrchIn").val() === 'undefined' ? 'Both' : $("#scmPrchsDocSrchIn").val();
    var pageNo = typeof $("#scmPrchsDocPageNo").val() === 'undefined' ? 1 : $("#scmPrchsDocPageNo").val();
    var limitSze = typeof $("#scmPrchsDocDsplySze").val() === 'undefined' ? 10 : $("#scmPrchsDocDsplySze").val();
    var sortBy = typeof $("#scmPrchsDocSortBy").val() === 'undefined' ? '' : $("#scmPrchsDocSortBy").val();
    var qShwUnpstdOnly = $('#scmPrchsDocShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#scmPrchsDocShwUnpaidOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwUnpstdOnly=" + qShwUnpstdOnly + "&qShwUnpaidOnly=" + qShwUnpaidOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncScmPrchsDoc(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getScmPrchsDoc(actionText, slctr, linkArgs);
    }
}

function getOneScmPrchsDocForm(pKeyID, vwtype, actionTxt, scmPrchsDocVchType) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof scmPrchsDocVchType === 'undefined' || scmPrchsDocVchType === null) {
        scmPrchsDocVchType = 'Purchase Order';
    }
    var sbmtdScmPrchsReqID = typeof $('#sbmtdScmPrchsReqID').val() === 'undefined' ? '-1' : $('#sbmtdScmPrchsReqID').val();
    var lnkArgs = 'grp=12&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdScmPrchsDocID=' + pKeyID + '&scmPrchsDocVchType=' + scmPrchsDocVchType + '&sbmtdScmPrchsReqID=' + sbmtdScmPrchsReqID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Purchasing Document Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneScmPrchsDocEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getScmPrchsDoc('', '#allmodules', 'grp=12&typ=1&pg=2&vtyp=0');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneScmPrchsDocSmryLinesTable')) {
            var table1 = $('#oneScmPrchsDocSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneScmPrchsDocSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneScmPrchsDocSmry1Table')) {
            var table1 = $('#oneScmPrchsDocSmry1Table').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneScmPrchsDocSmry1Table').wrap('<div class="dataTables_scroll"/>');
        }
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
            $(this).select();
        });
        $('#oneScmPrchsDocSmryLinesTable tr').off('click');
        $('#oneScmPrchsDocSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneScmPrchsDocSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');
        autoCreateSalesLns = -1;
        var scmPrchsDocApprvlStatus = typeof $("#scmPrchsDocApprvlStatus").val() === 'undefined' ? '' : $("#scmPrchsDocApprvlStatus").val();
        if (scmPrchsDocApprvlStatus === "Not Validated") {
            $("#oneScmPrchsDocSmryLinesTable tr:nth-of-type(1) .jbDetDesc").focus();
        } else {
            /*$("#scmPrchsDocTndrdAmnt").focus();*/
        }
    });
}

function delScmPrchsDoc(rowIDAttrb) {
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
    var msgPrt = "Purchasing Document";
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 2,
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

function delScmPrchsDocDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#scmPrchsDocDocNum').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Document Line";
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 2,
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

function saveScmPrchsDocForm(funcur, shdSbmt) {
    calcAllScmCnsgnRcptSmryTtl('oneScmPrchsDocSmryLinesTable');
    var sbmtdScmPrchsDocID = typeof $("#sbmtdScmPrchsDocID").val() === 'undefined' ? -1 : $("#sbmtdScmPrchsDocID").val();
    var scmPrchsDocDocNum = typeof $("#scmPrchsDocDocNum").val() === 'undefined' ? '' : $("#scmPrchsDocDocNum").val();
    var scmPrchsDocDfltTrnsDte = typeof $("#scmPrchsDocDfltTrnsDte").val() === 'undefined' ? '' : $("#scmPrchsDocDfltTrnsDte").val();
    var scmPrchsDocNeedByDte = typeof $("#scmPrchsDocNeedByDte").val() === 'undefined' ? '' : $("#scmPrchsDocNeedByDte").val();
    var scmPrchsDocVchType = typeof $("#scmPrchsDocVchType").val() === 'undefined' ? '' : $("#scmPrchsDocVchType").val();
    var scmPrchsDocInvcCur = typeof $("#scmPrchsDocInvcCur").val() === 'undefined' ? '' : $("#scmPrchsDocInvcCur").val();
    var scmPrchsDocTtlAmnt = typeof $("#scmPrchsDocTtlAmnt").val() === 'undefined' ? '0.00' : $("#scmPrchsDocTtlAmnt").val();
    var scmPrchsDocSpplrID = typeof $("#scmPrchsDocSpplrID").val() === 'undefined' ? '-1' : $("#scmPrchsDocSpplrID").val();
    var scmPrchsDocSpplrSiteID = typeof $("#scmPrchsDocSpplrSiteID").val() === 'undefined' ? '-1' : $("#scmPrchsDocSpplrSiteID").val();
    var scmPrchsDocDesc = typeof $("#scmPrchsDocDesc").val() === 'undefined' ? '' : $("#scmPrchsDocDesc").val();
    var scmPrchsDocPayTerms = typeof $("#scmPrchsDocPayTerms").val() === 'undefined' ? '' : $("#scmPrchsDocPayTerms").val();
    var myCptrdPrchsDocValsTtlVal = typeof $("#myCptrdPrchsDocValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdPrchsDocValsTtlVal").val();
    var scmPrchsDocBrnchID = typeof $("#scmPrchsDocBrnchID").val() === 'undefined' ? '-1' : $("#scmPrchsDocBrnchID").val();

    var scmPrchsDocExRate = typeof $("#scmPrchsDocExRate").val() === 'undefined' ? '1.000' : $("#scmPrchsDocExRate").val();
    var srcPrchsDocDocID = typeof $("#srcPrchsDocDocID").val() === 'undefined' ? '-1' : $("#srcPrchsDocDocID").val();
    var errMsg = "";
    if (scmPrchsDocDocNum.trim() === '' || scmPrchsDocVchType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number/Type cannot be empty!</span></p>';
    }
    if (scmPrchsDocDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
    }
    if (scmPrchsDocDfltTrnsDte.trim() === '' || scmPrchsDocInvcCur.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Batch Date/Currency cannot be empty!</span></p>';
    }
    scmPrchsDocTtlAmnt = fmtAsNumber('scmPrchsDocTtlAmnt').toFixed(2);
    myCptrdPrchsDocValsTtlVal = fmtAsNumber('myCptrdPrchsDocValsTtlVal').toFixed(2);
    scmPrchsDocExRate = fmtAsNumber2('scmPrchsDocExRate').toFixed(4);
    if (myCptrdPrchsDocValsTtlVal !== scmPrchsDocTtlAmnt) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    $('#oneScmPrchsDocSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#oneScmPrchsDocSmryRow' + rndmNum + '_TrnsLnID').val();
                var ln_ItmID = $('#oneScmPrchsDocSmryRow' + rndmNum + '_ItmID').val();
                var ln_StoreID = $('#oneScmPrchsDocSmryRow' + rndmNum + '_StoreID').val();
                var ln_LineDesc = $('#oneScmPrchsDocSmryRow' + rndmNum + '_LineDesc').val();
                var ln_QTY = $('#oneScmPrchsDocSmryRow' + rndmNum + '_QTY').val();
                var ln_UnitPrice = $('#oneScmPrchsDocSmryRow' + rndmNum + '_UnitPrice').val();
                var ln_TaxID = $('#oneScmPrchsDocSmryRow' + rndmNum + '_TaxID').val();
                var ln_DscntID = $('#oneScmPrchsDocSmryRow' + rndmNum + '_DscntID').val();
                var ln_ChrgID = $('#oneScmPrchsDocSmryRow' + rndmNum + '_ChrgID').val();
                var ln_SrcDocLnID = $('#oneScmPrchsDocSmryRow' + rndmNum + '_SrcDocLnID').val();
                if (Number(ln_ItmID.replace(/[^-?0-9\.]/g, '')) > 0) {
                    if (ln_LineDesc.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Item Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneScmPrchsDocSmryRow' + rndmNum + '_LineDesc').addClass('rho-error');
                    } else {
                        $('#oneScmPrchsDocSmryRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                    }
                    if (Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Quantity for Row No. ' + i + ' cannot be zero or less!</span></p>';
                        $('#oneScmPrchsDocSmryRow' + rndmNum + '_QTY').addClass('rho-error');
                    } else {
                        $('#oneScmPrchsDocSmryRow' + rndmNum + '_QTY').removeClass('rho-error');
                    }
                    /*if (Number(ln_UnitPrice.replace(/[^-?0-9\.]/g, '')) === 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Selling Price for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneScmPrchsDocSmryRow' + rndmNum + '_UnitPrice').addClass('rho-error');
                     } else {
                     $('#oneScmPrchsDocSmryRow' + rndmNum + '_UnitPrice').removeClass('rho-error');
                     }*/
                    if (isVld === true) {
                        slctdDetTransLines = slctdDetTransLines +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StoreID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_QTY.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_UnitPrice.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_TaxID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_DscntID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ChrgID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SrcDocLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
        title: 'Save ' + scmPrchsDocVchType,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + scmPrchsDocVchType + '...Please Wait...</p>',
        callback: function () {
            if (shdSbmt === 2) {
                getOneScmPrchsDocForm(sbmtdScmPrchsDocID, 1, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 2);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdScmPrchsDocID', sbmtdScmPrchsDocID);
    formData.append('scmPrchsDocDocNum', scmPrchsDocDocNum);
    formData.append('scmPrchsDocDfltTrnsDte', scmPrchsDocDfltTrnsDte);
    formData.append('scmPrchsDocVchType', scmPrchsDocVchType);
    formData.append('scmPrchsDocInvcCur', scmPrchsDocInvcCur);
    formData.append('scmPrchsDocTtlAmnt', scmPrchsDocTtlAmnt);
    formData.append('scmPrchsDocNeedByDte', scmPrchsDocNeedByDte);
    formData.append('scmPrchsDocSpplrID', scmPrchsDocSpplrID);
    formData.append('scmPrchsDocSpplrSiteID', scmPrchsDocSpplrSiteID);
    formData.append('scmPrchsDocPayTerms', scmPrchsDocPayTerms);
    formData.append('scmPrchsDocDesc', scmPrchsDocDesc);
    formData.append('scmPrchsDocExRate', scmPrchsDocExRate);
    formData.append('srcPrchsDocDocID', srcPrchsDocDocID);
    formData.append('scmPrchsDocBrnchID', scmPrchsDocBrnchID);

    formData.append('shdSbmt', shdSbmt);
    formData.append('slctdDetTransLines', slctdDetTransLines);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                        if (data.message.indexOf("Success") !== -1 || data.message.indexOf("SUCCESS") !== -1) {
                            sbmtdScmPrchsDocID = data.sbmtdScmPrchsDocID;
                            dialog.modal('hide');
                            getOneScmPrchsDocForm(sbmtdScmPrchsDocID, 1, 'ReloadDialog');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveScmPrchsDocRvrslForm(funcCur, shdSbmt, invcSrc) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslScmPrchsDocBtn");
    }
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdScmPrchsDocID = typeof $("#sbmtdScmPrchsDocID").val() === 'undefined' ? -1 : $("#sbmtdScmPrchsDocID").val();
    var scmPrchsDocDesc = typeof $("#scmPrchsDocDesc").val() === 'undefined' ? '' : $("#scmPrchsDocDesc").val();
    var scmPrchsDocDesc1 = typeof $("#scmPrchsDocDesc1").val() === 'undefined' ? '' : $("#scmPrchsDocDesc1").val();
    var scmPrchsDocVchType = typeof $("#scmPrchsDocVchType").val() === 'undefined' ? '' : $("#scmPrchsDocVchType").val();
    var errMsg = "";
    if (sbmtdScmPrchsDocID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (scmPrchsDocDesc === "" || scmPrchsDocDesc === scmPrchsDocDesc1) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#scmPrchsDocDesc").addClass('rho-error');
        $("#scmPrchsDocDesc").attr("readonly", false);
        $("#fnlzeRvrslScmPrchsDocBtn").attr("disabled", false);
    } else {
        $("#scmPrchsDocDesc").removeClass('rho-error');
    }
    if (rhotrim(errMsg, '; ') !== '') {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var msgsTitle = scmPrchsDocVchType;
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' + msgsTitle + '?<br/>This action cannot be UNDONE!</p>';
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
                var msg = 'Purchasing Transaction';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        sbmtdScmPrchsDocID = typeof $("#sbmtdScmPrchsDocID").val() === 'undefined' ? -1 : $("#sbmtdScmPrchsDocID").val();
                        if (sbmtdScmPrchsDocID > 0) {
                            getOneScmPrchsDocForm(sbmtdScmPrchsDocID, 1, 'ReloadDialog');
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
                                grp: 12,
                                typ: 1,
                                pg: 2,
                                actyp: 1,
                                q: 'VOID',
                                scmPrchsDocDesc: scmPrchsDocDesc,
                                sbmtdScmPrchsDocID: sbmtdScmPrchsDocID,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                                    obj = $.parseJSON(data);
                                    sbmtdScmPrchsDocID = obj.sbmtdScmPrchsDocID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdScmPrchsDocID > 0) {
                                        $("#sbmtdScmPrchsDocID").val(sbmtdScmPrchsDocID);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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
        }
    });
}

/**Pu Attachments */
function getOneScmPrchsDocDocsForm(pKeyID, vwtype) {
    var lnkArgs = 'grp=12&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdScmPrchsDocID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', 'Purchasing Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        var table1 = $('#attchdPrchsDocDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdPrchsDocDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdPrchsDocDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToPrchsDocDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdHdrID, rowIDAttrb) {
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
    if (rhotrim(errMsg, '; ') !== '') {
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
        sendFileToPrchsDocDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {}
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToPrchsDocDocs(file, docNmElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daPrchsDocAttchmnt', file);
    data1.append('grp', 12);
    data1.append('typ', 1);
    data1.append('pg', 2);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 20);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdScmPrchsDocID', sbmtdHdrID);
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

function getAttchdPrchsDocDocs(actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdPrchsDocDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdPrchsDocDocsSrchFor").val();
    var srchIn = typeof $("#attchdPrchsDocDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdPrchsDocDocsSrchIn").val();
    var pageNo = typeof $("#attchdPrchsDocDocsPageNo").val() === 'undefined' ? 1 : $("#attchdPrchsDocDocsPageNo").val();
    var limitSze = typeof $("#attchdPrchsDocDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdPrchsDocDocsDsplySze").val();
    var sortBy = typeof $("#attchdPrchsDocDocsSortBy").val() === 'undefined' ? '' : $("#attchdPrchsDocDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModaly', actionDialog, 'Purchase Document Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdPrchsDocDocsTable')) {
            var table1 = $('#attchdPrchsDocDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdPrchsDocDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdPrchsDocDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdPrchsDocDocs(e, actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdPrchsDocDocs(actionText, slctr, linkArgs, actionDialog);
    }
}

function delAttchdPrchsDocDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdScmPrchsDocID").val() === 'undefined' ? -1 : $("#sbmtdScmPrchsDocID").val();
    var docNum = typeof $("#scmPrchsDocDocNum").val() === 'undefined' ? '' : $("#scmPrchsDocDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdPrchsDocDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> 
        attchdPrchsDocDocsRow2_AttchdDocsID
        */
    } else {
        pKeyID = $('#attchdPrchsDocDocsRow' + rndmNum + '_AttchdDocsID').val();
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 2,
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
/**Consignment Receipts */
function getScmCnsgnRcpt(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#scmCnsgnRcptSrchFor").val() === 'undefined' ? '%' : $("#scmCnsgnRcptSrchFor").val();
    var srchIn = typeof $("#scmCnsgnRcptSrchIn").val() === 'undefined' ? 'Both' : $("#scmCnsgnRcptSrchIn").val();
    var pageNo = typeof $("#scmCnsgnRcptPageNo").val() === 'undefined' ? 1 : $("#scmCnsgnRcptPageNo").val();
    var limitSze = typeof $("#scmCnsgnRcptDsplySze").val() === 'undefined' ? 10 : $("#scmCnsgnRcptDsplySze").val();
    var sortBy = typeof $("#scmCnsgnRcptSortBy").val() === 'undefined' ? '' : $("#scmCnsgnRcptSortBy").val();
    var qShwUnpstdOnly = $('#scmCnsgnRcptShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#scmCnsgnRcptShwUnpaidOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwUnpstdOnly=" + qShwUnpstdOnly + "&qShwUnpaidOnly=" + qShwUnpaidOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncScmCnsgnRcpt(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getScmCnsgnRcpt(actionText, slctr, linkArgs);
    }
}

function getOneScmCnsgnRcptForm(pKeyID, vwtype, actionTxt, scmCnsgnRcptType, scmCnsgnRcptSRC, sbmtdScmCnsgnRcptITEMID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof scmCnsgnRcptType === 'undefined' || scmCnsgnRcptType === null) {
        scmCnsgnRcptType = 'Miscellaneous Receipt';
    }
    if (typeof scmCnsgnRcptSRC === 'undefined' || scmCnsgnRcptSRC === null) {
        scmCnsgnRcptSRC = 'NORMAL';
    }
    if (typeof sbmtdScmCnsgnRcptITEMID === 'undefined' || sbmtdScmCnsgnRcptITEMID === null) {
        sbmtdScmCnsgnRcptITEMID = -1;
    }
    var scmCnsgnRcptDsplySze1 = typeof $("#scmCnsgnRcptDsplySze1").val() === 'undefined' ? '50' : $("#scmCnsgnRcptDsplySze1").val();
    var sbmtdScmCnsgnRcptPOID = typeof $('#sbmtdScmCnsgnRcptPOID').val() === 'undefined' ? '-1' : $('#sbmtdScmCnsgnRcptPOID').val();
    var lnkArgs = 'grp=12&typ=1&pg=6&vtyp=' + vwtype + '&sbmtdScmCnsgnRcptID=' + pKeyID +
        '&scmCnsgnRcptType=' + scmCnsgnRcptType + '&scmCnsgnRcptSRC=' + scmCnsgnRcptSRC +
        '&sbmtdScmCnsgnRcptITEMID=' + sbmtdScmCnsgnRcptITEMID +
        '&sbmtdScmCnsgnRcptPOID=' + sbmtdScmCnsgnRcptPOID +
        '&scmCnsgnRcptDsplySze=' + scmCnsgnRcptDsplySze1;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Item Receipt Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneScmCnsgnRcptEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            if (scmCnsgnRcptSRC === 'QUICK_RCPT') {
                getAllINVItms('', '#allmodules', 'grp=12&typ=1&pg=3&vtyp=0');
            } else {
                getScmCnsgnRcpt('', '#allmodules', 'grp=12&typ=1&pg=6&vtyp=0');
            }
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneScmCnsgnRcptSmryLinesTable')) {
            var table1 = $('#oneScmCnsgnRcptSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneScmCnsgnRcptSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
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
            $(this).select();
        });
        $('#oneScmCnsgnRcptSmryLinesTable tr').off('click');
        $('#oneScmCnsgnRcptSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneScmCnsgnRcptSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllScmCnsgnRcptSmryTtl();
        autoCreateSalesLns = -1;
        var scmCnsgnRcptApprvlStatus = typeof $("#scmCnsgnRcptApprvlStatus").val() === 'undefined' ? '' : $("#scmCnsgnRcptApprvlStatus").val();
        if (scmCnsgnRcptApprvlStatus === "Incomplete") {
            $("#oneScmCnsgnRcptSmryLinesTable tr:nth-of-type(1) .jbDetDesc").focus();
        } else {
            /*$("#scmCnsgnRcptTndrdAmnt").focus();*/
        }
    });
}

function calcAllScmCnsgnRcptSmryTtl(tableElemntID) {
    var partNm = 'CnsgnRcpt';
    if (typeof tableElemntID === 'undefined' || tableElemntID === null) {
        partNm = 'CnsgnRcpt';
        tableElemntID = 'oneScmCnsgnRcptSmryLinesTable';
    } else if (tableElemntID === 'oneScmStockTrnsfrSmryLinesTable') {
        partNm = 'StockTrnsfr';
    } else if (tableElemntID === 'oneScmPrchsDocSmryLinesTable') {
        partNm = 'PrchsDoc';
    } else if (tableElemntID === 'oneScmCnsgnRtrnSmryLinesTable') {
        partNm = 'CnsgnRtrn';
    }
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    var ln_UnitPrice = 0;
    var ln_QTY = 0;
    $('#' + tableElemntID).find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var prfxName = $(el).attr('id').split("_")[0];
                ln_QTY = ($("#" + prfxName + rndmNum + "_QTY").val() + ',').replace(/,/g, "");
                ln_UnitPrice = ($("#" + prfxName + rndmNum + "_UnitPrice").val() + ',').replace(/,/g, "");
                ttlRwAmount = Number(ln_QTY) * Number(ln_UnitPrice);
                $("#" + prfxName + rndmNum + "_LineAmt").val(addCommas(ttlRwAmount.toFixed(2)));
                ttlAmount = ttlAmount + Number(ttlRwAmount);
            }
        }
    });
    $('#myCptrd' + partNm + 'ValsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrd' + partNm + 'ValsTtlVal').val(ttlAmount.toFixed(2));
    $('#myCptrdRIJbSmryAmtTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdRIJbSmryAmtTtlVal').val(ttlAmount.toFixed(2));

    $('#scm' + partNm + 'TtlAmnt').val(addCommas(ttlAmount.toFixed(2)));
}

function getInvPyblsAcntInfo() {
    var scmCnsgnRcptSpplrID = typeof $("#scmCnsgnRcptSpplrID").val() === 'undefined' ? '-1' : $("#scmCnsgnRcptSpplrID").val();
    var scmCnsgnRcptSpplrSiteID = typeof $("#scmCnsgnRcptSpplrSiteID").val() === 'undefined' ? '-1' : $("#scmCnsgnRcptSpplrSiteID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('pg', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 5);
        formData.append('scmCnsgnRcptSpplrID', scmCnsgnRcptSpplrID);
        formData.append('scmCnsgnRcptSpplrSiteID', scmCnsgnRcptSpplrSiteID);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#scmCnsgnRcptDfltBalsAcnt').val(data.BalsAcntNm);
                $('#scmCnsgnRcptDfltBalsAcntID').val(data.BalsAcntID);
                $('#scmCnsgnRcptSpplrSite').val(data.scmCnsgnRcptSpplrSiteNm);
                $('#scmCnsgnRcptSpplrSiteID').val(data.scmCnsgnRcptSpplrSiteID);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function delScmCnsgnRcpt(rowIDAttrb) {
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
    var msgPrt = "Item Receipt";
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 6,
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

function delScmCnsgnRcptDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#scmCnsgnRcptDocNum').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Document Line";
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 6,
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

function saveScmCnsgnRcptForm(funcur, shdSbmt, invcSrc, sbmtdScmCnsgnRcptITEMID) {
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof sbmtdScmCnsgnRcptITEMID === 'undefined' || sbmtdScmCnsgnRcptITEMID === null) {
        sbmtdScmCnsgnRcptITEMID = -1;
    }
    calcAllScmCnsgnRcptSmryTtl();
    var sbmtdScmCnsgnRcptID = typeof $("#sbmtdScmCnsgnRcptID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRcptID").val();
    var scmCnsgnRcptDocNum = typeof $("#scmCnsgnRcptDocNum").val() === 'undefined' ? '' : $("#scmCnsgnRcptDocNum").val();
    var scmCnsgnRcptDfltTrnsDte = typeof $("#scmCnsgnRcptDfltTrnsDte").val() === 'undefined' ? '' : $("#scmCnsgnRcptDfltTrnsDte").val();
    var scmCnsgnRcptType = typeof $("#scmCnsgnRcptType").val() === 'undefined' ? '' : $("#scmCnsgnRcptType").val();
    var scmCnsgnRcptInvcCur = typeof $("#scmCnsgnRcptInvcCur").val() === 'undefined' ? '' : $("#scmCnsgnRcptInvcCur").val();
    var scmCnsgnRcptExRate = typeof $("#scmCnsgnRcptExRate").val() === 'undefined' ? '1.00' : $("#scmCnsgnRcptExRate").val();
    var scmCnsgnRcptTtlAmnt = typeof $("#scmCnsgnRcptTtlAmnt").val() === 'undefined' ? '0.00' : $("#scmCnsgnRcptTtlAmnt").val();
    var scmCnsgnRcptSpplrID = typeof $("#scmCnsgnRcptSpplrID").val() === 'undefined' ? '-1' : $("#scmCnsgnRcptSpplrID").val();
    var scmCnsgnRcptSpplrSiteID = typeof $("#scmCnsgnRcptSpplrSiteID").val() === 'undefined' ? '' : $("#scmCnsgnRcptSpplrSiteID").val();
    var scmCnsgnRcptDesc = typeof $("#scmCnsgnRcptDesc").val() === 'undefined' ? '' : $("#scmCnsgnRcptDesc").val();
    var scmCnsgnRcptDfltBalsAcntID = typeof $("#scmCnsgnRcptDfltBalsAcntID").val() === 'undefined' ? -1 : $("#scmCnsgnRcptDfltBalsAcntID").val();
    var myCptrdCnsgnRcptValsTtlVal = typeof $("#myCptrdCnsgnRcptValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdCnsgnRcptValsTtlVal").val();
    var srcCnsgnRcptDocID = typeof $("#srcCnsgnRcptDocID").val() === 'undefined' ? '-1' : $("#srcCnsgnRcptDocID").val();
    var srcCnsgnRcptDocNum = typeof $("#srcCnsgnRcptDocNum").val() === 'undefined' ? '' : $("#srcCnsgnRcptDocNum").val();

    var errMsg = "";
    if (scmCnsgnRcptDocNum.trim() === '' || scmCnsgnRcptType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number/Type cannot be empty!</span></p>';
    }
    if (scmCnsgnRcptDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
    }
    if (scmCnsgnRcptDfltTrnsDte.trim() === '' || scmCnsgnRcptInvcCur.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Date/Currency cannot be empty!</span></p>';
    }
    scmCnsgnRcptTtlAmnt = fmtAsNumber('scmCnsgnRcptTtlAmnt').toFixed(2);
    myCptrdCnsgnRcptValsTtlVal = fmtAsNumber('myCptrdCnsgnRcptValsTtlVal').toFixed(2);
    if (myCptrdCnsgnRcptValsTtlVal !== scmCnsgnRcptTtlAmnt) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    $('#oneScmCnsgnRcptSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_TrnsLnID').val();
                var ln_ItmID = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_ItmID').val();
                var ln_StoreID = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_StoreID').val();
                var ln_LineDesc = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_LineDesc').val();
                var ln_QTY = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_QTY').val();
                var ln_UnitPrice = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_UnitPrice').val();
                var ln_PODocLnID = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_PODocLnID').val();
                var ln_ManDte = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_ManDte').val();
                var ln_ExpryDte = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_ExpryDte').val();
                var ln_TagNo = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_TagNo').val();
                var ln_SerialNo = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_SerialNo').val();
                var ln_CnsgnCdtn = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_CnsgnCdtn').val();
                var ln_ExtraDesc = $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_ExtraDesc').val();
                if (Number(ln_ItmID.replace(/[^-?0-9\.]/g, '')) > 0 && Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) > 0) {
                    if (ln_LineDesc.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Item Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_LineDesc').addClass('rho-error');
                    } else {
                        $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                    }
                    if (Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Quantity for Row No. ' + i + ' cannot be zero or less!</span></p>';
                        $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_QTY').addClass('rho-error');
                    } else {
                        $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_QTY').removeClass('rho-error');
                    }
                    /*if (Number(ln_UnitPrice.replace(/[^-?0-9\.]/g, '')) === 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Selling Price for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_UnitPrice').addClass('rho-error');
                     } else {
                     $('#oneScmCnsgnRcptSmryRow' + rndmNum + '_UnitPrice').removeClass('rho-error');
                     }*/
                    if (isVld === true) {
                        slctdDetTransLines = slctdDetTransLines +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StoreID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_QTY.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_UnitPrice.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PODocLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ManDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ExpryDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_TagNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SerialNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CnsgnCdtn.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ExtraDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
        title: 'Save ' + scmCnsgnRcptType,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + scmCnsgnRcptType + '...Please Wait...</p>',
        callback: function () {
            if (shdSbmt === 2) {
                if (invcSrc === 'QUICK_RCPT') {
                    /*getAllINVItms('', '#allmodules', 'grp=12&typ=1&pg=3&vtyp=0');*/
                    getOneScmCnsgnRcptForm(sbmtdScmCnsgnRcptID, 1, 'ReloadDialog', scmCnsgnRcptType, 'QUICK_RCPT', sbmtdScmCnsgnRcptITEMID);
                } else {
                    getOneScmCnsgnRcptForm(sbmtdScmCnsgnRcptID, 1, 'ReloadDialog');
                }
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 6);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdScmCnsgnRcptID', sbmtdScmCnsgnRcptID);
    formData.append('scmCnsgnRcptDocNum', scmCnsgnRcptDocNum);
    formData.append('scmCnsgnRcptDfltTrnsDte', scmCnsgnRcptDfltTrnsDte);
    formData.append('scmCnsgnRcptType', scmCnsgnRcptType);
    formData.append('scmCnsgnRcptInvcCur', scmCnsgnRcptInvcCur);
    formData.append('scmCnsgnRcptExRate', scmCnsgnRcptExRate);
    formData.append('scmCnsgnRcptTtlAmnt', scmCnsgnRcptTtlAmnt);
    formData.append('scmCnsgnRcptSpplrID', scmCnsgnRcptSpplrID);
    formData.append('scmCnsgnRcptSpplrSiteID', scmCnsgnRcptSpplrSiteID);
    formData.append('scmCnsgnRcptDfltBalsAcntID', scmCnsgnRcptDfltBalsAcntID);
    formData.append('scmCnsgnRcptDesc', scmCnsgnRcptDesc);

    formData.append('srcCnsgnRcptDocID', srcCnsgnRcptDocID);
    formData.append('srcCnsgnRcptDocNum', srcCnsgnRcptDocNum);

    formData.append('shdSbmt', shdSbmt);
    formData.append('slctdDetTransLines', slctdDetTransLines);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                        if (data.message.indexOf("Success") !== -1 || data.message.indexOf("SUCCESS") !== -1) {
                            sbmtdScmCnsgnRcptID = data.sbmtdScmCnsgnRcptID;
                            dialog.modal('hide');
                            if (invcSrc === 'QUICK_RCPT') {
                                /*getAllINVItms('', '#allmodules', 'grp=12&typ=1&pg=3&vtyp=0');*/
                                getOneScmCnsgnRcptForm(sbmtdScmCnsgnRcptID, 1, 'ReloadDialog', scmCnsgnRcptType, 'QUICK_RCPT', sbmtdScmCnsgnRcptITEMID);
                            } else {
                                getOneScmCnsgnRcptForm(sbmtdScmCnsgnRcptID, 1, 'ReloadDialog');
                            }
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveScmCnsgnRcptRvrslForm(funcCur, shdSbmt, invcSrc, sbmtdScmCnsgnRcptITEMID) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslScmCnsgnRcptBtn");
    }
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof sbmtdScmCnsgnRcptITEMID === 'undefined' || sbmtdScmCnsgnRcptITEMID === null) {
        sbmtdScmCnsgnRcptITEMID = -1;
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdScmCnsgnRcptID = typeof $("#sbmtdScmCnsgnRcptID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRcptID").val();
    var scmCnsgnRcptDesc = typeof $("#scmCnsgnRcptDesc").val() === 'undefined' ? '' : $("#scmCnsgnRcptDesc").val();
    var scmCnsgnRcptDesc1 = typeof $("#scmCnsgnRcptDesc1").val() === 'undefined' ? '' : $("#scmCnsgnRcptDesc1").val();
    var scmCnsgnRcptType = typeof $("#scmCnsgnRcptType").val() === 'undefined' ? '' : $("#scmCnsgnRcptType").val();
    var errMsg = "";
    if (sbmtdScmCnsgnRcptID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (scmCnsgnRcptDesc === "" || scmCnsgnRcptDesc === scmCnsgnRcptDesc1) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#scmCnsgnRcptDesc").addClass('rho-error');
        $("#scmCnsgnRcptDesc").attr("readonly", false);
        $("#fnlzeRvrslScmCnsgnRcptBtn").attr("disabled", false);
    } else {
        $("#scmCnsgnRcptDesc").removeClass('rho-error');
    }
    if (rhotrim(errMsg, '; ') !== '') {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var msgsTitle = scmCnsgnRcptType;
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' + msgsTitle + '?<br/>This action cannot be UNDONE!</p>';
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
                var msg = 'Item Receipt Reversal';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        sbmtdScmCnsgnRcptID = typeof $("#sbmtdScmCnsgnRcptID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRcptID").val();
                        if (sbmtdScmCnsgnRcptID > 0) {
                            if (invcSrc === 'QUICK_RCPT') {
                                /*getAllINVItms('', '#allmodules', 'grp=12&typ=1&pg=3&vtyp=0');*/
                                getOneScmCnsgnRcptForm(sbmtdScmCnsgnRcptID, 1, 'ReloadDialog', scmCnsgnRcptType, 'QUICK_RCPT', sbmtdScmCnsgnRcptITEMID);
                            } else {
                                getOneScmCnsgnRcptForm(sbmtdScmCnsgnRcptID, 1, 'ReloadDialog');
                            }
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
                                grp: 12,
                                typ: 1,
                                pg: 6,
                                actyp: 1,
                                q: 'VOID',
                                scmCnsgnRcptDesc: scmCnsgnRcptDesc,
                                sbmtdScmCnsgnRcptID: sbmtdScmCnsgnRcptID,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                                    obj = $.parseJSON(data);
                                    sbmtdScmCnsgnRcptID = obj.sbmtdScmCnsgnRcptID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdScmCnsgnRcptID > 0) {
                                        $("#sbmtdScmCnsgnRcptID").val(sbmtdScmCnsgnRcptID);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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
        }
    });
}

/**Consignment Receipt Attachments */
function getOneScmCnsgnRcptDocsForm(pKeyID, vwtype) {
    var lnkArgs = 'grp=12&typ=1&pg=6&vtyp=' + vwtype + '&sbmtdScmCnsgnRcptID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', 'Goods Receipt Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        var table1 = $('#attchdCnsgnRcptDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdCnsgnRcptDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdCnsgnRcptDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToCnsgnRcptDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdHdrID, rowIDAttrb) {
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
    if (rhotrim(errMsg, '; ') !== '') {
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
        sendFileToCnsgnRcptDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {}
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToCnsgnRcptDocs(file, docNmElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daCnsgnRcptAttchmnt', file);
    data1.append('grp', 12);
    data1.append('typ', 1);
    data1.append('pg', 6);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 20);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdScmCnsgnRcptID', sbmtdHdrID);
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

function getAttchdCnsgnRcptDocs(actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdCnsgnRcptDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdCnsgnRcptDocsSrchFor").val();
    var srchIn = typeof $("#attchdCnsgnRcptDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdCnsgnRcptDocsSrchIn").val();
    var pageNo = typeof $("#attchdCnsgnRcptDocsPageNo").val() === 'undefined' ? 1 : $("#attchdCnsgnRcptDocsPageNo").val();
    var limitSze = typeof $("#attchdCnsgnRcptDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdCnsgnRcptDocsDsplySze").val();
    var sortBy = typeof $("#attchdCnsgnRcptDocsSortBy").val() === 'undefined' ? '' : $("#attchdCnsgnRcptDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModaly', actionDialog, 'Receivable Invoice Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdCnsgnRcptDocsTable')) {
            var table1 = $('#attchdCnsgnRcptDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdCnsgnRcptDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdCnsgnRcptDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdCnsgnRcptDocs(e, actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdCnsgnRcptDocs(actionText, slctr, linkArgs, actionDialog);
    }
}

function delAttchdCnsgnRcptDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdScmCnsgnRcptID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRcptID").val();
    var docNum = typeof $("#scmCnsgnRcptDocNum").val() === 'undefined' ? '' : $("#scmCnsgnRcptDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdCnsgnRcptDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> 
        attchdCnsgnRcptDocsRow2_AttchdDocsID
        */
    } else {
        pKeyID = $('#attchdCnsgnRcptDocsRow' + rndmNum + '_AttchdDocsID').val();
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 6,
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
/**Consignment Receipt Returns */
function getScmCnsgnRtrn(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#scmCnsgnRtrnSrchFor").val() === 'undefined' ? '%' : $("#scmCnsgnRtrnSrchFor").val();
    var srchIn = typeof $("#scmCnsgnRtrnSrchIn").val() === 'undefined' ? 'Both' : $("#scmCnsgnRtrnSrchIn").val();
    var pageNo = typeof $("#scmCnsgnRtrnPageNo").val() === 'undefined' ? 1 : $("#scmCnsgnRtrnPageNo").val();
    var limitSze = typeof $("#scmCnsgnRtrnDsplySze").val() === 'undefined' ? 10 : $("#scmCnsgnRtrnDsplySze").val();
    var sortBy = typeof $("#scmCnsgnRtrnSortBy").val() === 'undefined' ? '' : $("#scmCnsgnRtrnSortBy").val();
    var qShwUnpstdOnly = $('#scmCnsgnRtrnShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#scmCnsgnRtrnShwUnpaidOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwUnpstdOnly=" + qShwUnpstdOnly + "&qShwUnpaidOnly=" + qShwUnpaidOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncScmCnsgnRtrn(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getScmCnsgnRtrn(actionText, slctr, linkArgs);
    }
}

function getOneScmCnsgnRtrnForm(pKeyID, vwtype, actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var sbmtdScmCnsgnRcptID = typeof $('#sbmtdScmCnsgnRcptID').val() === 'undefined' ? '-1' : $('#sbmtdScmCnsgnRcptID').val();
    if (pKeyID > 0) {
        sbmtdScmCnsgnRcptID = '-1';
        $('#sbmtdScmCnsgnRcptID').val('-1');
    } else if (Number(sbmtdScmCnsgnRcptID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        return false;
    }
    var lnkArgs = 'grp=12&typ=1&pg=7&vtyp=' + vwtype + '&sbmtdScmCnsgnRtrnID=' + pKeyID + '&sbmtdScmCnsgnRcptID=' + sbmtdScmCnsgnRcptID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Receipt Return Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneScmCnsgnRtrnEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getScmCnsgnRtrn('', '#allmodules', 'grp=12&typ=1&pg=7&vtyp=0');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneScmCnsgnRtrnSmryLinesTable')) {
            var table1 = $('#oneScmCnsgnRtrnSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneScmCnsgnRtrnSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
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
            $(this).select();
        });
        $('#oneScmCnsgnRtrnSmryLinesTable tr').off('click');
        $('#oneScmCnsgnRtrnSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneScmCnsgnRtrnSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllScmCnsgnRcptSmryTtl('oneScmCnsgnRtrnSmryLinesTable');
        autoCreateSalesLns = -1;
        var scmCnsgnRtrnApprvlStatus = typeof $("#scmCnsgnRtrnApprvlStatus").val() === 'undefined' ? '' : $("#scmCnsgnRtrnApprvlStatus").val();
        if (scmCnsgnRtrnApprvlStatus === "Incomplete") {
            $("#oneScmCnsgnRtrnSmryLinesTable tr:nth-of-type(2) .jbDetAccRate").focus();
        } else {
            /*$("#scmCnsgnRtrnTndrdAmnt").focus();*/
        }
    });
}

function delScmCnsgnRtrn(rowIDAttrb) {
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
    var msgPrt = "Item Receipt";
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
                                    grp: 12,
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

function delScmCnsgnRtrnDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#scmCnsgnRtrnDocNum').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Document Line";
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
                                    grp: 12,
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

function saveScmCnsgnRtrnForm(funcur, shdSbmt) {
    calcAllScmCnsgnRcptSmryTtl('oneScmCnsgnRtrnSmryLinesTable');
    var sbmtdScmCnsgnRtrnID = typeof $("#sbmtdScmCnsgnRtrnID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRtrnID").val();
    var scmCnsgnRtrnDocNum = typeof $("#scmCnsgnRtrnDocNum").val() === 'undefined' ? '' : $("#scmCnsgnRtrnDocNum").val();
    var scmCnsgnRtrnDfltTrnsDte = typeof $("#scmCnsgnRtrnDfltTrnsDte").val() === 'undefined' ? '' : $("#scmCnsgnRtrnDfltTrnsDte").val();
    var scmCnsgnRtrnType = typeof $("#scmCnsgnRtrnType").val() === 'undefined' ? '' : $("#scmCnsgnRtrnType").val();
    var scmCnsgnRtrnInvcCur = typeof $("#scmCnsgnRtrnInvcCur").val() === 'undefined' ? '' : $("#scmCnsgnRtrnInvcCur").val();
    var scmCnsgnRtrnExRate = typeof $("#scmCnsgnRtrnExRate").val() === 'undefined' ? '0.00' : $("#scmCnsgnRtrnExRate").val();
    var scmCnsgnRtrnTtlAmnt = typeof $("#scmCnsgnRtrnTtlAmnt").val() === 'undefined' ? '0.00' : $("#scmCnsgnRtrnTtlAmnt").val();
    var scmCnsgnRtrnSpplrID = typeof $("#scmCnsgnRtrnSpplrID").val() === 'undefined' ? '-1' : $("#scmCnsgnRtrnSpplrID").val();
    var scmCnsgnRtrnSpplrSiteID = typeof $("#scmCnsgnRtrnSpplrSiteID").val() === 'undefined' ? '' : $("#scmCnsgnRtrnSpplrSiteID").val();
    var scmCnsgnRtrnDesc = typeof $("#scmCnsgnRtrnDesc").val() === 'undefined' ? '' : $("#scmCnsgnRtrnDesc").val();
    var scmCnsgnRtrnDfltBalsAcntID = typeof $("#scmCnsgnRtrnDfltBalsAcntID").val() === 'undefined' ? -1 : $("#scmCnsgnRtrnDfltBalsAcntID").val();
    var myCptrdCnsgnRtrnValsTtlVal = typeof $("#myCptrdCnsgnRtrnValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdCnsgnRtrnValsTtlVal").val();
    var srcCnsgnRtrnDocID = typeof $("#srcCnsgnRtrnDocID").val() === 'undefined' ? '-1' : $("#srcCnsgnRtrnDocID").val();
    var srcCnsgnRtrnDocNum = typeof $("#srcCnsgnRtrnDocNum").val() === 'undefined' ? '' : $("#srcCnsgnRtrnDocNum").val();

    var errMsg = "";
    if (scmCnsgnRtrnDocNum.trim() === '' || scmCnsgnRtrnType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number/Type cannot be empty!</span></p>';
    }
    if (scmCnsgnRtrnDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
    }
    if (scmCnsgnRtrnDfltTrnsDte.trim() === '' || scmCnsgnRtrnInvcCur.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Date/Currency cannot be empty!</span></p>';
    }
    scmCnsgnRtrnTtlAmnt = fmtAsNumber('scmCnsgnRtrnTtlAmnt').toFixed(2);
    myCptrdCnsgnRtrnValsTtlVal = fmtAsNumber('myCptrdCnsgnRtrnValsTtlVal').toFixed(2);
    if (myCptrdCnsgnRtrnValsTtlVal !== scmCnsgnRtrnTtlAmnt) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    $('#oneScmCnsgnRtrnSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_TrnsLnID').val();
                var ln_ItmID = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_ItmID').val();
                var ln_StoreID = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_StoreID').val();
                var ln_LineDesc = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_LineDesc').val();
                var ln_QTY = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_QTY').val();
                var ln_UnitPrice = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_UnitPrice').val();
                var ln_PODocLnID = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_PODocLnID').val();
                var ln_CnsgnCdtn = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_CnsgnCdtn').val();
                var ln_ExtraDesc = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_ExtraDesc').val();
                var ln_CnsgnID = $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_CnsgnID').val();
                if (Number(ln_ItmID.replace(/[^-?0-9\.]/g, '')) > 0 && Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) > 0) {
                    if (ln_LineDesc.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Item Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_LineDesc').addClass('rho-error');
                    } else {
                        $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                    }
                    if (Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Quantity for Row No. ' + i + ' cannot be zero or less!</span></p>';
                        $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_QTY').addClass('rho-error');
                    } else {
                        $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_QTY').removeClass('rho-error');
                    }
                    /*if (Number(ln_UnitPrice.replace(/[^-?0-9\.]/g, '')) === 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Selling Price for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_UnitPrice').addClass('rho-error');
                     } else {
                     $('#oneScmCnsgnRtrnSmryRow' + rndmNum + '_UnitPrice').removeClass('rho-error');
                     }*/
                    if (isVld === true) {
                        slctdDetTransLines = slctdDetTransLines +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StoreID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_QTY.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_UnitPrice.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PODocLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CnsgnCdtn.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ExtraDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CnsgnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
        title: 'Save ' + scmCnsgnRtrnType,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + scmCnsgnRtrnType + '...Please Wait...</p>',
        callback: function () {
            if (shdSbmt === 2) {
                getOneScmCnsgnRtrnForm(sbmtdScmCnsgnRtrnID, 1, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 7);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdScmCnsgnRtrnID', sbmtdScmCnsgnRtrnID);
    formData.append('scmCnsgnRtrnDocNum', scmCnsgnRtrnDocNum);
    formData.append('scmCnsgnRtrnDfltTrnsDte', scmCnsgnRtrnDfltTrnsDte);
    formData.append('scmCnsgnRtrnType', scmCnsgnRtrnType);
    formData.append('scmCnsgnRtrnInvcCur', scmCnsgnRtrnInvcCur);
    formData.append('scmCnsgnRtrnExRate', scmCnsgnRtrnExRate);
    formData.append('scmCnsgnRtrnTtlAmnt', scmCnsgnRtrnTtlAmnt);
    formData.append('scmCnsgnRtrnSpplrID', scmCnsgnRtrnSpplrID);
    formData.append('scmCnsgnRtrnSpplrSiteID', scmCnsgnRtrnSpplrSiteID);
    formData.append('scmCnsgnRtrnDfltBalsAcntID', scmCnsgnRtrnDfltBalsAcntID);
    formData.append('scmCnsgnRtrnDesc', scmCnsgnRtrnDesc);

    formData.append('srcCnsgnRtrnDocID', srcCnsgnRtrnDocID);
    formData.append('srcCnsgnRtrnDocNum', srcCnsgnRtrnDocNum);

    formData.append('shdSbmt', shdSbmt);
    formData.append('slctdDetTransLines', slctdDetTransLines);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                        if (data.message.indexOf("Success") !== -1 || data.message.indexOf("SUCCESS") !== -1) {
                            sbmtdScmCnsgnRtrnID = data.sbmtdScmCnsgnRtrnID;
                            dialog.modal('hide');
                            getOneScmCnsgnRtrnForm(sbmtdScmCnsgnRtrnID, 1, 'ReloadDialog');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveScmCnsgnRtrnRvrslForm(funcCur, shdSbmt) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslScmCnsgnRtrnBtn");
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdScmCnsgnRtrnID = typeof $("#sbmtdScmCnsgnRtrnID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRtrnID").val();
    var scmCnsgnRtrnDesc = typeof $("#scmCnsgnRtrnDesc").val() === 'undefined' ? '' : $("#scmCnsgnRtrnDesc").val();
    var scmCnsgnRtrnDesc1 = typeof $("#scmCnsgnRtrnDesc1").val() === 'undefined' ? '' : $("#scmCnsgnRtrnDesc1").val();
    var scmCnsgnRtrnType = typeof $("#scmCnsgnRtrnType").val() === 'undefined' ? '' : $("#scmCnsgnRtrnType").val();
    var errMsg = "";
    if (sbmtdScmCnsgnRtrnID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (scmCnsgnRtrnDesc === "" || scmCnsgnRtrnDesc === scmCnsgnRtrnDesc1) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#scmCnsgnRtrnDesc").addClass('rho-error');
        $("#scmCnsgnRtrnDesc").attr("readonly", false);
        $("#fnlzeRvrslScmCnsgnRtrnBtn").attr("disabled", false);
    } else {
        $("#scmCnsgnRtrnDesc").removeClass('rho-error');
    }
    if (rhotrim(errMsg, '; ') !== '') {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var msgsTitle = scmCnsgnRtrnType;
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' + msgsTitle + '?<br/>This action cannot be UNDONE!</p>';
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
                var msg = 'Item Receipt Reversal';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        sbmtdScmCnsgnRtrnID = typeof $("#sbmtdScmCnsgnRtrnID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRtrnID").val();
                        if (sbmtdScmCnsgnRtrnID > 0) {
                            getOneScmCnsgnRtrnForm(sbmtdScmCnsgnRtrnID, 1, 'ReloadDialog');
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
                                grp: 12,
                                typ: 1,
                                pg: 7,
                                actyp: 1,
                                q: 'VOID',
                                scmCnsgnRtrnDesc: scmCnsgnRtrnDesc,
                                sbmtdScmCnsgnRtrnID: sbmtdScmCnsgnRtrnID,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                                    obj = $.parseJSON(data);
                                    sbmtdScmCnsgnRtrnID = obj.sbmtdScmCnsgnRtrnID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdScmCnsgnRtrnID > 0) {
                                        $("#sbmtdScmCnsgnRtrnID").val(sbmtdScmCnsgnRtrnID);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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
        }
    });
}

/**Consignment Receipt Returns Attachments */
function getOneScmCnsgnRtrnDocsForm(pKeyID, vwtype) {
    var lnkArgs = 'grp=12&typ=1&pg=7&vtyp=' + vwtype + '&sbmtdScmCnsgnRtrnID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', 'Goods Return Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        var table1 = $('#attchdCnsgnRtrnDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdCnsgnRtrnDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdCnsgnRtrnDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToCnsgnRtrnDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdHdrID, rowIDAttrb) {
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
    if (rhotrim(errMsg, '; ') !== '') {
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
        sendFileToCnsgnRtrnDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {}
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToCnsgnRtrnDocs(file, docNmElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daCnsgnRtrnAttchmnt', file);
    data1.append('grp', 12);
    data1.append('typ', 1);
    data1.append('pg', 7);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 20);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdScmCnsgnRtrnID', sbmtdHdrID);
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

function getAttchdCnsgnRtrnDocs(actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdCnsgnRtrnDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdCnsgnRtrnDocsSrchFor").val();
    var srchIn = typeof $("#attchdCnsgnRtrnDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdCnsgnRtrnDocsSrchIn").val();
    var pageNo = typeof $("#attchdCnsgnRtrnDocsPageNo").val() === 'undefined' ? 1 : $("#attchdCnsgnRtrnDocsPageNo").val();
    var limitSze = typeof $("#attchdCnsgnRtrnDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdCnsgnRtrnDocsDsplySze").val();
    var sortBy = typeof $("#attchdCnsgnRtrnDocsSortBy").val() === 'undefined' ? '' : $("#attchdCnsgnRtrnDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModaly', actionDialog, 'Receipt Return Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdCnsgnRtrnDocsTable')) {
            var table1 = $('#attchdCnsgnRtrnDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdCnsgnRtrnDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdCnsgnRtrnDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdCnsgnRtrnDocs(e, actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdCnsgnRtrnDocs(actionText, slctr, linkArgs, actionDialog);
    }
}

function delAttchdCnsgnRtrnDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdScmCnsgnRtrnID").val() === 'undefined' ? -1 : $("#sbmtdScmCnsgnRtrnID").val();
    var docNum = typeof $("#scmCnsgnRtrnDocNum").val() === 'undefined' ? '' : $("#scmCnsgnRtrnDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdCnsgnRtrnDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> 
        attchdCnsgnRtrnDocsRow2_AttchdDocsID
        */
    } else {
        pKeyID = $('#attchdCnsgnRtrnDocsRow' + rndmNum + '_AttchdDocsID').val();
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 7,
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
/**Stock Transfers */
function getScmStockTrnsfr(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#scmStockTrnsfrSrchFor").val() === 'undefined' ? '%' : $("#scmStockTrnsfrSrchFor").val();
    var srchIn = typeof $("#scmStockTrnsfrSrchIn").val() === 'undefined' ? 'Both' : $("#scmStockTrnsfrSrchIn").val();
    var pageNo = typeof $("#scmStockTrnsfrPageNo").val() === 'undefined' ? 1 : $("#scmStockTrnsfrPageNo").val();
    var limitSze = typeof $("#scmStockTrnsfrDsplySze").val() === 'undefined' ? 10 : $("#scmStockTrnsfrDsplySze").val();
    var sortBy = typeof $("#scmStockTrnsfrSortBy").val() === 'undefined' ? '' : $("#scmStockTrnsfrSortBy").val();
    var qShwUnpstdOnly = $('#scmStockTrnsfrShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#scmStockTrnsfrShwUnpaidOnly:checked').length > 0;
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
        "&qShwUnpstdOnly=" + qShwUnpstdOnly + "&qShwUnpaidOnly=" + qShwUnpaidOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncScmStockTrnsfr(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getScmStockTrnsfr(actionText, slctr, linkArgs);
    }
}


function getScmPrdctCrtn(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#scmPrdctCrtnSrchFor").val() === 'undefined' ? '%' : $("#scmPrdctCrtnSrchFor").val();
    var srchIn = typeof $("#scmPrdctCrtnSrchIn").val() === 'undefined' ? 'Both' : $("#scmPrdctCrtnSrchIn").val();
    var pageNo = typeof $("#scmPrdctCrtnPageNo").val() === 'undefined' ? 1 : $("#scmPrdctCrtnPageNo").val();
    var limitSze = typeof $("#scmPrdctCrtnDsplySze").val() === 'undefined' ? 10 : $("#scmPrdctCrtnDsplySze").val();
    var sortBy = typeof $("#scmPrdctCrtnSortBy").val() === 'undefined' ? '' : $("#scmPrdctCrtnSortBy").val();
    var qShwProcessOnly = typeof $("input[name='accbTxCdeIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='accbTxCdeIsEnbld']:checked").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwProcessOnly=" + qShwProcessOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncScmPrdctCrtn(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getScmPrdctCrtn(actionText, slctr, linkArgs);
    }
}

function getOneScmStockTrnsfrForm(pKeyID, vwtype, actionTxt, scmStockTrnsfrType) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof scmStockTrnsfrType === 'undefined' || scmStockTrnsfrType === null) {
        scmStockTrnsfrType = 'Stock Transfer';
    }
    var lnkArgs = 'grp=12&typ=1&pg=11&vtyp=' + vwtype + '&sbmtdScmStockTrnsfrID=' + pKeyID +
        '&scmStockTrnsfrType=' + scmStockTrnsfrType;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Stock Transfer Details (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneScmStockTrnsfrEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getScmStockTrnsfr('', '#allmodules', 'grp=12&typ=1&pg=11&vtyp=0');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneScmStockTrnsfrSmryLinesTable')) {
            var table1 = $('#oneScmStockTrnsfrSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneScmStockTrnsfrSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
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
            $(this).select();
        });
        $('#oneScmStockTrnsfrSmryLinesTable tr').off('click');
        $('#oneScmStockTrnsfrSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneScmStockTrnsfrSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');
        autoCreateSalesLns = -1;
        var scmStockTrnsfrApprvlStatus = typeof $("#scmStockTrnsfrApprvlStatus").val() === 'undefined' ? '' : $("#scmStockTrnsfrApprvlStatus").val();
        if (scmStockTrnsfrApprvlStatus === "Incomplete") {
            $("#oneScmStockTrnsfrSmryLinesTable tr:nth-of-type(1) .jbDetDesc").focus();
        } else {
            /*$("#scmStockTrnsfrTndrdAmnt").focus();*/
        }
    });
}

function delScmStockTrnsfr(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_HdrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_HdrID').val();
        pKeyNm = $('#scmStockTrnsfrDocNum').val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();
         var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(3).text());*/
    }
    var msgPrt = "Stock Transfer";
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
                                    grp: 12,
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

function delScmStockTrnsfrDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#scmStockTrnsfrDocNum').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Document Line";
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
                                    grp: 12,
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

function saveScmStockTrnsfrForm(funcur, shdSbmt) {
    calcAllScmCnsgnRcptSmryTtl('oneScmStockTrnsfrSmryLinesTable');
    var sbmtdScmStockTrnsfrID = typeof $("#sbmtdScmStockTrnsfrID").val() === 'undefined' ? -1 : $("#sbmtdScmStockTrnsfrID").val();
    var scmStockTrnsfrDocNum = typeof $("#scmStockTrnsfrDocNum").val() === 'undefined' ? '' : $("#scmStockTrnsfrDocNum").val();
    var scmStockTrnsfrDfltTrnsDte = typeof $("#scmStockTrnsfrDfltTrnsDte").val() === 'undefined' ? '' : $("#scmStockTrnsfrDfltTrnsDte").val();
    var scmStockTrnsfrInvcCur = typeof $("#scmStockTrnsfrInvcCur").val() === 'undefined' ? '' : $("#scmStockTrnsfrInvcCur").val();
    var scmStockTrnsfrTtlAmnt = typeof $("#scmStockTrnsfrTtlAmnt").val() === 'undefined' ? '0.00' : $("#scmStockTrnsfrTtlAmnt").val();
    var scmStockTrnsfrSrcStoreID = typeof $("#scmStockTrnsfrSrcStoreID").val() === 'undefined' ? '-1' : $("#scmStockTrnsfrSrcStoreID").val();
    var scmStockTrnsfrDestStoreID = typeof $("#scmStockTrnsfrDestStoreID").val() === 'undefined' ? '-1' : $("#scmStockTrnsfrDestStoreID").val();
    var scmStockTrnsfrDesc = typeof $("#scmStockTrnsfrDesc").val() === 'undefined' ? '' : $("#scmStockTrnsfrDesc").val();
    var myCptrdStockTrnsfrValsTtlVal = typeof $("#myCptrdStockTrnsfrValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdStockTrnsfrValsTtlVal").val();

    var errMsg = "";
    if (scmStockTrnsfrDocNum.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number cannot be empty!</span></p>';
    }
    if (scmStockTrnsfrDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
    }
    if (scmStockTrnsfrDfltTrnsDte.trim() === '' || scmStockTrnsfrInvcCur.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Date/Currency cannot be empty!</span></p>';
    }
    if (Number(scmStockTrnsfrSrcStoreID.replace(/[^-?0-9\.]/g, '')) <= 0 || Number(scmStockTrnsfrDestStoreID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Source and Destination Stores cannot be empty!</span></p>';
    }
    scmStockTrnsfrTtlAmnt = fmtAsNumber('scmStockTrnsfrTtlAmnt').toFixed(2);
    myCptrdStockTrnsfrValsTtlVal = fmtAsNumber('myCptrdStockTrnsfrValsTtlVal').toFixed(2);
    if (myCptrdStockTrnsfrValsTtlVal !== scmStockTrnsfrTtlAmnt) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    $('#oneScmStockTrnsfrSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_TrnsLnID').val();
                var ln_ItmID = $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_ItmID').val();
                var ln_LineDesc = $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_LineDesc').val();
                var ln_QTY = $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_QTY').val();
                var ln_UnitPrice = $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_UnitPrice').val();
                var ln_CnsgnIDs = $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_CnsgnIDs').val();
                if (Number(ln_ItmID.replace(/[^-?0-9\.]/g, '')) > 0 && Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) > 0) {
                    if (ln_LineDesc.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Item Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_LineDesc').addClass('rho-error');
                    } else {
                        $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                    }
                    if (Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) <= 0) {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Quantity for Row No. ' + i + ' cannot be zero or less!</span></p>';
                        $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_QTY').addClass('rho-error');
                    } else {
                        $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_QTY').removeClass('rho-error');
                    }
                    /*if (Number(ln_UnitPrice.replace(/[^-?0-9\.]/g, '')) === 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Selling Price for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_UnitPrice').addClass('rho-error');
                     } else {
                     $('#oneScmStockTrnsfrSmryRow' + rndmNum + '_UnitPrice').removeClass('rho-error');
                     }*/
                    if (isVld === true) {
                        slctdDetTransLines = slctdDetTransLines +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_QTY.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_UnitPrice.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CnsgnIDs.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
    var scmStockTrnsfrType = 'Stock Transfer';
    var dialog = bootbox.alert({
        title: 'Save ' + scmStockTrnsfrType,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + scmStockTrnsfrType + '...Please Wait...</p>',
        callback: function () {
            if (shdSbmt === 2) {
                getOneScmStockTrnsfrForm(sbmtdScmStockTrnsfrID, 1, 'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 11);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('sbmtdScmStockTrnsfrID', sbmtdScmStockTrnsfrID);
    formData.append('scmStockTrnsfrDocNum', scmStockTrnsfrDocNum);
    formData.append('scmStockTrnsfrDfltTrnsDte', scmStockTrnsfrDfltTrnsDte);
    formData.append('scmStockTrnsfrType', scmStockTrnsfrType);
    formData.append('scmStockTrnsfrInvcCur', scmStockTrnsfrInvcCur);
    formData.append('scmStockTrnsfrTtlAmnt', scmStockTrnsfrTtlAmnt);
    formData.append('scmStockTrnsfrSrcStoreID', scmStockTrnsfrSrcStoreID);
    formData.append('scmStockTrnsfrDestStoreID', scmStockTrnsfrDestStoreID);
    formData.append('scmStockTrnsfrDesc', scmStockTrnsfrDesc);
    formData.append('shdSbmt', shdSbmt);
    formData.append('slctdDetTransLines', slctdDetTransLines);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                        if (data.message.indexOf("Success") !== -1 || data.message.indexOf("SUCCESS") !== -1) {
                            sbmtdScmStockTrnsfrID = data.sbmtdScmStockTrnsfrID;
                            dialog.modal('hide');
                            getOneScmStockTrnsfrForm(sbmtdScmStockTrnsfrID, 1, 'ReloadDialog');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function saveScmStockTrnsfrRvrslForm(funcCur, shdSbmt) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslScmStockTrnsfrBtn");
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdScmStockTrnsfrID = typeof $("#sbmtdScmStockTrnsfrID").val() === 'undefined' ? -1 : $("#sbmtdScmStockTrnsfrID").val();
    var scmStockTrnsfrDesc = typeof $("#scmStockTrnsfrDesc").val() === 'undefined' ? '' : $("#scmStockTrnsfrDesc").val();
    var scmStockTrnsfrDesc1 = typeof $("#scmStockTrnsfrDesc1").val() === 'undefined' ? '' : $("#scmStockTrnsfrDesc1").val();
    var scmStockTrnsfrType = 'Stock Transfer';
    var errMsg = "";
    if (sbmtdScmStockTrnsfrID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (scmStockTrnsfrDesc === "" || scmStockTrnsfrDesc === scmStockTrnsfrDesc1) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#scmStockTrnsfrDesc").addClass('rho-error');
        $("#scmStockTrnsfrDesc").attr("readonly", false);
        $("#fnlzeRvrslScmStockTrnsfrBtn").attr("disabled", false);
    } else {
        $("#scmStockTrnsfrDesc").removeClass('rho-error');
    }
    if (rhotrim(errMsg, '; ') !== '') {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var msgsTitle = scmStockTrnsfrType;
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' + msgsTitle + '?<br/>This action cannot be UNDONE!</p>';
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
                var msg = 'Stock Transfer Reversal';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        sbmtdScmStockTrnsfrID = typeof $("#sbmtdScmStockTrnsfrID").val() === 'undefined' ? -1 : $("#sbmtdScmStockTrnsfrID").val();
                        if (sbmtdScmStockTrnsfrID > 0) {
                            getOneScmStockTrnsfrForm(sbmtdScmStockTrnsfrID, 1, 'ReloadDialog');
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
                                grp: 12,
                                typ: 1,
                                pg: 11,
                                actyp: 1,
                                q: 'VOID',
                                scmStockTrnsfrDesc: scmStockTrnsfrDesc,
                                sbmtdScmStockTrnsfrID: sbmtdScmStockTrnsfrID,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                                    obj = $.parseJSON(data);
                                    sbmtdScmStockTrnsfrID = obj.sbmtdScmStockTrnsfrID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdScmStockTrnsfrID > 0) {
                                        $("#sbmtdScmStockTrnsfrID").val(sbmtdScmStockTrnsfrID);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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
        }
    });
}

function chngStockTrnsfrStores() {
    var scmStockTrnsfrSrcStoreID = typeof $("#scmStockTrnsfrSrcStoreID").val() === 'undefined' ? '-1' : $("#scmStockTrnsfrSrcStoreID").val();
    var scmStockTrnsfrSrcStore = typeof $("#scmStockTrnsfrSrcStore").val() === 'undefined' ? '-1' : $("#scmStockTrnsfrSrcStore").val();
    var scmStockTrnsfrDestStoreID = typeof $("#scmStockTrnsfrDestStoreID").val() === 'undefined' ? '' : $("#scmStockTrnsfrDestStoreID").val();
    var scmStockTrnsfrDestStore = typeof $("#scmStockTrnsfrDestStore").val() === 'undefined' ? '' : $("#scmStockTrnsfrDestStore").val();
    var cntr = 0;
    $('#oneScmStockTrnsfrSmryLinesTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                cntr++;
                var prfxName1 = $(el).attr('id').split("_")[0];
                var rndmNum1 = $(el).attr('id').split("_")[1];
                $('#' + prfxName1 + rndmNum1 + '_SrcStore').val(scmStockTrnsfrSrcStore);
                $('#' + prfxName1 + rndmNum1 + '_StoreID').val(scmStockTrnsfrSrcStoreID);
                $('#' + prfxName1 + rndmNum1 + '_DestStore').val(scmStockTrnsfrDestStore);
                $('#' + prfxName1 + rndmNum1 + '_DestStoreID').val(scmStockTrnsfrDestStoreID);
                var $tds = $("#" + prfxName1 + "_" + rndmNum1).find('td');
                $tds.eq(0).html(cntr);
            }
        }
    });
}

/**Stock Transfer Attachments */
function getOneScmStockTrnsfrDocsForm(pKeyID, vwtype) {
    var lnkArgs = 'grp=12&typ=1&pg=11&vtyp=' + vwtype + '&sbmtdScmStockTrnsfrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', 'ShowDialog', 'Stock Transfer Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        var table1 = $('#attchdStockTrnsfrDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdStockTrnsfrDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdStockTrnsfrDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToStockTrnsfrDocs(inptElmntID, attchIDElmntID, docNmElmntID, sbmtdHdrID, rowIDAttrb) {
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
    if (rhotrim(errMsg, '; ') !== '') {
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
        sendFileToStockTrnsfrDocs(input.files[0], docNmElmntID, attchIDElmntID, sbmtdHdrID, function (data) {
            $("#" + attchIDElmntID).val(data.attchID);
            var dialog = bootbox.alert({
                title: 'Server Response!',
                size: 'small',
                message: '<div id="myInformation">' + data.message + '</div>',
                callback: function () {
                    if (data.message.indexOf("Success") !== -1) {}
                }
            });
        });
    });
    performFileClick(inptElmntID);
}

function sendFileToStockTrnsfrDocs(file, docNmElmntID, attchIDElmntID, sbmtdHdrID, callBackFunc) {
    var data1 = new FormData();
    data1.append('daStockTrnsfrAttchmnt', file);
    data1.append('grp', 12);
    data1.append('typ', 1);
    data1.append('pg', 11);
    data1.append('q', 'UPDATE');
    data1.append('actyp', 20);
    data1.append('docCtrgrName', $('#' + docNmElmntID).val());
    data1.append('attchmentID', $('#' + attchIDElmntID).val());
    data1.append('sbmtdScmStockTrnsfrID', sbmtdHdrID);
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

function getAttchdStockTrnsfrDocs(actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdStockTrnsfrDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdStockTrnsfrDocsSrchFor").val();
    var srchIn = typeof $("#attchdStockTrnsfrDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdStockTrnsfrDocsSrchIn").val();
    var pageNo = typeof $("#attchdStockTrnsfrDocsPageNo").val() === 'undefined' ? 1 : $("#attchdStockTrnsfrDocsPageNo").val();
    var limitSze = typeof $("#attchdStockTrnsfrDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdStockTrnsfrDocsDsplySze").val();
    var sortBy = typeof $("#attchdStockTrnsfrDocsSortBy").val() === 'undefined' ? '' : $("#attchdStockTrnsfrDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs, 'myFormsModaly', actionDialog, 'Receipt Return Attached Documents', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        if (!$.fn.DataTable.isDataTable('#attchdStockTrnsfrDocsTable')) {
            var table1 = $('#attchdStockTrnsfrDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdStockTrnsfrDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdStockTrnsfrDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdStockTrnsfrDocs(e, actionText, slctr, linkArgs, actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdStockTrnsfrDocs(actionText, slctr, linkArgs, actionDialog);
    }
}

function delAttchdStockTrnsfrDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdScmStockTrnsfrID").val() === 'undefined' ? -1 : $("#sbmtdScmStockTrnsfrID").val();
    var docNum = typeof $("#scmStockTrnsfrDocNum").val() === 'undefined' ? '' : $("#scmStockTrnsfrDocNum").val();
    var pKeyID = -1;
    if (typeof $('#attchdStockTrnsfrDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> 
        attchdStockTrnsfrDocsRow2_AttchdDocsID
        */
    } else {
        pKeyID = $('#attchdStockTrnsfrDocsRow' + rndmNum + '_AttchdDocsID').val();
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 11,
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

function setCurStore() {
    var accbFSRptStoreID = typeof $("#accbFSRptStoreID").val() === 'undefined' ? '-1' : $("#accbFSRptStoreID").val();
    var accbFSRptStore = typeof $("#accbFSRptStore").val() === 'undefined' ? '' : $("#accbFSRptStore").val();
    openATab('#allmodules', 'grp=12&typ=1&vtyp=0&accbFSRptStoreID=' + accbFSRptStoreID);
}

function getHotlChckinDoc(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#hotlChckinDocSrchFor").val() === 'undefined' ? '%' : $("#hotlChckinDocSrchFor").val();
    var srchIn = typeof $("#hotlChckinDocSrchIn").val() === 'undefined' ? 'Both' : $("#hotlChckinDocSrchIn").val();
    var pageNo = typeof $("#hotlChckinDocPageNo").val() === 'undefined' ? 1 : $("#hotlChckinDocPageNo").val();
    var limitSze = typeof $("#hotlChckinDocDsplySze").val() === 'undefined' ? 10 : $("#hotlChckinDocDsplySze").val();
    var sortBy = typeof $("#hotlChckinDocSortBy").val() === 'undefined' ? '' : $("#hotlChckinDocSortBy").val();
    var qShwUnpstdOnly = $('#hotlChckinDocShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#hotlChckinDocShwUnpaidOnly:checked').length > 0;
    var qShwSelfOnly = $('#hotlChckinDocShwSelfOnly:checked').length > 0;
    var qShwMyBranch = $('#hotlChckinDocShwMyBranchOnly:checked').length > 0;
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
        "&qShwUnpaidOnly=" + qShwUnpaidOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly + "&qShwSelfOnly=" + qShwSelfOnly + "&qShwMyBranch=" + qShwMyBranch;
    //alert(linkArgs);
    openATab(slctr, linkArgs);
}

function enterKeyFuncHotlChckinDoc(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getHotlChckinDoc(actionText, slctr, linkArgs);
    }
}

function getOneHotlChckinDocForm(pKeyID, vwtype, actionTxt, hotlChckinDocVchType, srcPage, srcModule, ln_FcltyType, ln_SrvsTypID, ln_RoomID) {

    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof hotlChckinDocVchType === 'undefined' || hotlChckinDocVchType === null) {
        hotlChckinDocVchType = 'Sales Invoice-Hospitality';
    }
    if (typeof srcPage === 'undefined' || srcPage === null) {
        srcPage = 'RENT';
    }
    if (typeof srcModule === 'undefined' || srcModule === null) {
        srcModule = 'allmodules';
    }
    if (typeof ln_FcltyType === 'undefined' || ln_FcltyType === null) {
        ln_FcltyType = '';
    }
    if (typeof ln_SrvsTypID === 'undefined' || ln_SrvsTypID === null) {
        ln_SrvsTypID = -1;
    }
    if (typeof ln_RoomID === 'undefined' || ln_RoomID === null) {
        ln_RoomID = -1;
    }
    var diagTitle = 'General Rental Document Details (ID:' + pKeyID + ')';
    var lnkArgs = 'grp=18&typ=1&pg=7&vtyp=' + vwtype + '&sbmtdHotlChckinDocID=' + pKeyID + '&hotlChckinDocVchType=' + hotlChckinDocVchType + '&srcModule=' + srcModule + '&ln_FcltyType=' + ln_FcltyType + '&ln_SrvsTypID=' + ln_SrvsTypID + '&ln_RoomID=' + ln_RoomID;
    if (srcPage === 'CHECK-IN') {
        lnkArgs = 'grp=18&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdHotlChckinDocID=' + pKeyID + '&hotlChckinDocVchType=' + hotlChckinDocVchType + '&srcModule=' + srcModule + '&ln_FcltyType=' + ln_FcltyType + '&ln_SrvsTypID=' + ln_SrvsTypID + '&ln_RoomID=' + ln_RoomID;
        diagTitle = hotlChckinDocVchType + ' Document Details (ID:' + pKeyID + ')';
    } else if (srcPage === 'RESTAURANT') {
        lnkArgs = 'grp=18&typ=1&pg=4&vtyp=' + vwtype + '&sbmtdHotlChckinDocID=' + pKeyID + '&hotlChckinDocVchType=' + hotlChckinDocVchType + '&srcModule=' + srcModule + '&ln_FcltyType=' + ln_FcltyType + '&ln_SrvsTypID=' + ln_SrvsTypID + '&ln_RoomID=' + ln_RoomID;
        diagTitle = hotlChckinDocVchType + ' Document Details (ID:' + pKeyID + ')';
    } else if (srcPage === 'GYM') {
        lnkArgs = 'grp=18&typ=1&pg=5&vtyp=' + vwtype + '&sbmtdHotlChckinDocID=' + pKeyID + '&hotlChckinDocVchType=' + hotlChckinDocVchType + '&srcModule=' + srcModule + '&ln_FcltyType=' + ln_FcltyType + '&ln_SrvsTypID=' + ln_SrvsTypID + '&ln_RoomID=' + ln_RoomID;
        diagTitle = hotlChckinDocVchType + ' Document Details (ID:' + pKeyID + ')';
    } else if (srcPage === 'EVENT_CHECK-IN') {
        diagTitle = hotlChckinDocVchType + ' Document Details (ID:' + pKeyID + ')';
        lnkArgs = 'grp=16&typ=1&pg=7&vtyp=' + vwtype + '&sbmtdHotlChckinDocID=' + pKeyID + '&hotlChckinDocVchType=' + hotlChckinDocVchType + '&srcModule=' + srcModule + '&ln_FcltyType=' + ln_FcltyType + '&ln_SrvsTypID=' + ln_SrvsTypID + '&ln_RoomID=' + ln_RoomID;
    }
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
            if (srcModule === 'DASHBOARD') {
                getHotlSmryDshBrd('', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');
            } else if (srcPage === 'RENT') {
                getHotlChckinDoc('', '#' + srcModule, 'grp=18&typ=1&pg=7&vtyp=0');
            } else if (srcPage === 'CHECK-IN') {
                var admsn_id = typeof $("#admsn_id").val() === 'undefined' ? -1 : $("#admsn_id").val();
                if (admsn_id > 0) {
                    var patientNm = typeof $("#patientNm").val() === 'undefined' ? '' : $("#patientNm").val();
                    var appntmntNo = typeof $("#appntmntNo").val() === 'undefined' ? '' : $("#appntmntNo").val();
                    var srcPgNo = typeof $("#srcPgNo").val() === 'undefined' ? 3 : $("#srcPgNo").val();
                    var dialogTitle = "Appointment for Patient " + patientNm + " - " + appntmntNo;
                    var appntmntID = typeof $("#appntmntID").val() === 'undefined' ? -1 : $("#appntmntID").val();
                    if (appntmntID > 0) {
                        getHospDetailsForm('myFormsModalLgZ', 'myFormsModalLgZBody', 'myFormsModalLgZTitle', dialogTitle, 3, 5, 'EDIT', appntmntID, 'visitAppointmentTblAdd', 'visitAppointmentTblAddRow2', srcPgNo);
                    } else {
                        getHotlChckinDoc('', '#' + srcModule, 'grp=18&typ=1&pg=2&vtyp=0');
                    }
                } else {
                    getHotlChckinDoc('', '#' + srcModule, 'grp=18&typ=1&pg=2&vtyp=0');
                }
            } else if (srcPage === 'RESTAURANT') {
                getHotlChckinDoc('', '#' + srcModule, 'grp=18&typ=1&pg=4&vtyp=0');
            } else if (srcPage === 'GYM') {
                getHotlChckinDoc('', '#' + srcModule, 'grp=18&typ=1&pg=5&vtyp=0');
            } else if (srcPage === 'EVENT_CHECK-IN') {
                getHotlChckinDoc('', '#' + srcModule, 'grp=16&typ=1&pg=7&vtyp=0');
            }
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

function calcAllHotlChckinDocSmryTtl(isServerRfrsh) {
    if (typeof isServerRfrsh === 'undefined' || isServerRfrsh === null) {
        isServerRfrsh = 0;
    }
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    var ln_UnitPrice = 0;
    var ln_QTY = 0;
    var ln_RentedQty = 0;
    $('#oneScmSalesInvcSmryLinesTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var prfxName = $(el).attr('id').split("_")[0];
                ln_QTY = ($("#" + prfxName + rndmNum + "_QTY").val() + ',').replace(/,/g, "");
                ln_RentedQty = ((typeof $("#" + prfxName + rndmNum + "_RentedQty").val() === 'undefined' ? '1' : $("#" + prfxName + rndmNum + "_RentedQty").val()) + ',').replace(/,/g, "");
                ln_UnitPrice = ($("#" + prfxName + rndmNum + "_UnitPrice").val() + ',').replace(/,/g, "");
                ttlRwAmount = Number(ln_QTY) * Number(ln_UnitPrice) * Number(ln_RentedQty);
                $("#" + prfxName + rndmNum + "_LineAmt").val(addCommas(ttlRwAmount.toFixed(2)));
                ttlAmount = ttlAmount + Number(ttlRwAmount);
            }
        }
    });
    $('#myCptrdSalesInvcValsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdSalesInvcValsTtlVal').val(ttlAmount.toFixed(2));
    $('#myCptrdRIJbSmryAmtTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdRIJbSmryAmtTtlVal').val(ttlAmount.toFixed(2));
    var myScmSalesInvcStatusBtn = $('#myScmSalesInvcStatusBtn').html();
    var scmRealInvcGrndTtl = Number($('#scmRealInvcGrndTtl').val().replace(/,/g, ""));
    if (isServerRfrsh === 0) {
        scmRealInvcGrndTtl = ttlAmount;
    }
    if (myScmSalesInvcStatusBtn.indexOf('Approved') === -1) {
        $('#scmSalesInvcTtlAmnt').val(addCommas(scmRealInvcGrndTtl.toFixed(2)));
    }
    var scmSalesInvcPaidAmnt = $('#scmSalesInvcPaidAmnt').val().replace(/,/g, "");
    $('#scmSalesInvcOustndngAmnt').val(addCommas((scmRealInvcGrndTtl - Number(scmSalesInvcPaidAmnt)).toFixed(2)));
}
/*
 ln_UnitPrice = ($("#" + prfxName + rndmNum + "_UnitPrice").val() + ',').replace(/,/g, "");
 ttlRwAmount = Number(ln_QTY) * Number(ln_UnitPrice);*/
function insertNewHotlChckinDocRows(tableElmntID, position, inptHtml) {
    var curPos = Number($('#allOtherInputData99').val());
    var ttlrows = $('#' + tableElmntID + ' > tbody > tr').length;
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq(ttlrows - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq(ttlrows - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                if (curPos === ttlrows) {
                    $('#' + tableElmntID).append(nwInptHtml);
                } else if (curPos >= 1) {
                    $('#' + tableElmntID).append(nwInptHtml);
                    /*$('#' + tableElmntID + ' > tbody > tr').eq(curPos - 1).after(nwInptHtml);*/
                } else {
                    $('#' + tableElmntID).append(nwInptHtml);
                    /*$('#' + tableElmntID + ' > tbody > tr').eq(curPos).after(nwInptHtml);*/
                }
            }
        } else {
            $('#' + tableElmntID).append(nwInptHtml);
        }
    }

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

    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
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

function delHotlChckinDoc(rowIDAttrb) {
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
    var msgPrt = "Item Transaction Document";
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
                                    grp: 18,
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

function delHotlChckinDocDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#hotlChckinDocNum').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Document Line";
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
                                    grp: 18,
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

function delHotlChckinDocGymLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#hotlChckinDocNum').val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
         pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Activity Line";
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
                                    grp: 18,
                                    typ: 1,
                                    pg: 5,
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

function saveHotlChckinDocForm(funcur, shdSbmt, invcSrc, srcPage, srcModule) {
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof srcPage === 'undefined' || srcPage === null) {
        srcPage = 'RENT';
    }
    calcAllHotlChckinDocSmryTtl();
    var sbmtdHotlChckinDocID = typeof $("#sbmtdHotlChckinDocID").val() === 'undefined' ? -1 : $("#sbmtdHotlChckinDocID").val();
    var hotlChckinDocVchType = typeof $("#hotlChckinDocVchType").val() === 'undefined' ? '' : $("#hotlChckinDocVchType").val();
    var hotlChckinDocNum = typeof $("#hotlChckinDocNum").val() === 'undefined' ? '' : $("#hotlChckinDocNum").val();
    var hotlChckinFcltyType = typeof $("#hotlChckinFcltyType").val() === 'undefined' ? '' : $("#hotlChckinFcltyType").val();
    var hotlChckinDocStrtDte = typeof $("#hotlChckinDocStrtDte").val() === 'undefined' ? '' : $("#hotlChckinDocStrtDte").val();
    var hotlChckinDocEndDte = typeof $("#hotlChckinDocEndDte").val() === 'undefined' ? '' : $("#hotlChckinDocEndDte").val();
    var hotlChckinDocSrvcTypID = typeof $("#hotlChckinDocSrvcTypID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSrvcTypID").val();
    var hotlChckinDocSrvcTyp = typeof $("#hotlChckinDocSrvcTyp").val() === 'undefined' ? '' : $("#hotlChckinDocSrvcTyp").val();
    var hotlChckinDocRmID = typeof $("#hotlChckinDocRmID").val() === 'undefined' ? '-1' : $("#hotlChckinDocRmID").val();
    var hotlChckinDocRmNum = typeof $("#hotlChckinDocRmNum").val() === 'undefined' ? '-1' : $("#hotlChckinDocRmNum").val();
    var hotlChckinDocCalcMthd = typeof $("#hotlChckinDocCalcMthd").val() === 'undefined' ? '0' : $("#hotlChckinDocCalcMthd").val();
    var hotlChckinDocNoAdlts = typeof $("#hotlChckinDocNoAdlts").val() === 'undefined' ? '0.00' : $("#hotlChckinDocNoAdlts").val();
    var hotlChckinDocNoChldrn = typeof $("#hotlChckinDocNoChldrn").val() === 'undefined' ? '0.00' : $("#hotlChckinDocNoChldrn").val();
    var hotlChckinDocSpnsrID = typeof $("#hotlChckinDocSpnsrID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnsrID").val();
    var hotlChckinDocSpnsr = typeof $("#hotlChckinDocSpnsr").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnsr").val();
    var hotlChckinDocSpnsrSiteID = typeof $("#hotlChckinDocSpnsrSiteID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnsrSiteID").val();
    var hotlChckinDocSpnsrSite = typeof $("#hotlChckinDocSpnsrSite").val() === 'undefined' ? '' : $("#hotlChckinDocSpnsrSite").val();
    var hotlChckinDocSpnseeID = typeof $("#hotlChckinDocSpnseeID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnseeID").val();
    var hotlChckinDocSpnsee = typeof $("#hotlChckinDocSpnsee").val() === 'undefined' ? '' : $("#hotlChckinDocSpnsee").val();
    var hotlChckinDocSpnseeSiteID = typeof $("#hotlChckinDocSpnseeSiteID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnseeSiteID").val();
    var hotlChckinDocSpnseeSite = typeof $("#hotlChckinDocSpnseeSite").val() === 'undefined' ? '' : $("#hotlChckinDocSpnseeSite").val();
    var hotlChckinDocOthrInfo = typeof $("#hotlChckinDocOthrInfo").val() === 'undefined' ? '' : $("#hotlChckinDocOthrInfo").val();
    var hotlChckinDocArvlFrm = typeof $("#hotlChckinDocArvlFrm").val() === 'undefined' ? '' : $("#hotlChckinDocArvlFrm").val();
    var hotlChckinDocPrcdTo = typeof $("#hotlChckinDocPrcdTo").val() === 'undefined' ? '' : $("#hotlChckinDocPrcdTo").val();
    var hotlChckinDocPrntChcknID = typeof $("#hotlChckinDocPrntChcknID").val() === 'undefined' ? '-1' : $("#hotlChckinDocPrntChcknID").val();
    var hotlChckinDocPrntChcknTyp = typeof $("#hotlChckinDocPrntChcknTyp").val() === 'undefined' ? '' : $("#hotlChckinDocPrntChcknTyp").val();

    var errMsg = "";
    if (hotlChckinDocNum.trim() === '' || hotlChckinDocVchType.trim() === '' || hotlChckinFcltyType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number/Type and Facility Type cannot be empty!</span></p>';
    }
    if (hotlChckinDocStrtDte.trim() === '' || hotlChckinDocEndDte.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Start and End Dates cannot be empty!</span></p>';
    }
    if (srcPage === 'CHECK-IN') {
        if (hotlChckinDocSpnsee.trim() === '' || hotlChckinDocSpnsr.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Sponsor and Occupant cannot be empty!</span></p>';
        }
        if (hotlChckinDocSrvcTyp.trim() === '' || hotlChckinDocRmNum.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Service Type and Number cannot be empty!</span></p>';
        }
    } else if (srcPage === 'EVENT_CHECK-IN') {
        if (hotlChckinDocSpnsee.trim() === '' || hotlChckinDocSpnsr.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Sponsor and Participant cannot be empty!</span></p>';
        }
        if (hotlChckinDocSrvcTyp.trim() === '' || hotlChckinDocRmNum.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Event and Price Category cannot be empty!</span></p>';
        }
    } else if (srcPage === 'RESTAURANT') {
        if (hotlChckinDocSrvcTyp.trim() === '' || hotlChckinDocRmNum.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Service Type and Number cannot be empty!</span></p>';
        }
    } else if (srcPage === 'GYM') {
        if (hotlChckinDocSpnsee.trim() === '' || hotlChckinDocSpnsr.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Sponsor and Participant cannot be empty!</span></p>';
        }
        if (hotlChckinDocSrvcTyp.trim() === '') {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Service Type cannot be empty!</span></p>';
        }
    }
    var sbmtdScmSalesInvcID = typeof $("#sbmtdScmSalesInvcID").val() === 'undefined' ? -1 : $("#sbmtdScmSalesInvcID").val();
    var scmSalesInvcDocNum = typeof $("#scmSalesInvcDocNum").val() === 'undefined' ? '' : $("#scmSalesInvcDocNum").val();
    var scmSalesInvcDfltTrnsDte = typeof $("#scmSalesInvcDfltTrnsDte").val() === 'undefined' ? '' : $("#scmSalesInvcDfltTrnsDte").val();
    var scmSalesInvcVchType = typeof $("#scmSalesInvcVchType").val() === 'undefined' ? '' : $("#scmSalesInvcVchType").val();
    var scmSalesInvcInvcCur = typeof $("#scmSalesInvcInvcCur").val() === 'undefined' ? '' : $("#scmSalesInvcInvcCur").val();
    var scmSalesInvcTtlAmnt = typeof $("#scmSalesInvcTtlAmnt").val() === 'undefined' ? '0.00' : $("#scmSalesInvcTtlAmnt").val();
    var scmSalesInvcEvntDocTyp = typeof $("#scmSalesInvcEvntDocTyp").val() === 'undefined' ? '' : $("#scmSalesInvcEvntDocTyp").val();
    var scmSalesInvcEvntCtgry = typeof $("#scmSalesInvcEvntCtgry").val() === 'undefined' ? '' : $("#scmSalesInvcEvntCtgry").val();
    var scmSalesInvcEvntRgstrID = typeof $("#scmSalesInvcEvntRgstrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcEvntCtgry").val();
    var scmSalesInvcCstmrID = typeof $("#scmSalesInvcCstmrID").val() === 'undefined' ? '-1' : $("#scmSalesInvcCstmrID").val();
    var scmSalesInvcCstmrSiteID = typeof $("#scmSalesInvcCstmrSiteID").val() === 'undefined' ? '-1' : $("#scmSalesInvcCstmrSiteID").val();
    var scmSalesInvcBrnchID = typeof $("#scmSalesInvcBrnchID").val() === 'undefined' ? '-1' : $("#scmSalesInvcBrnchID").val();
    var scmSalesInvcDesc = typeof $("#scmSalesInvcDesc").val() === 'undefined' ? '' : $("#scmSalesInvcDesc").val();
    var scmSalesInvcPayTerms = typeof $("#scmSalesInvcPayTerms").val() === 'undefined' ? '' : $("#scmSalesInvcPayTerms").val();
    var scmSalesInvcDfltBalsAcntID = typeof $("#scmSalesInvcDfltBalsAcntID").val() === 'undefined' ? -1 : $("#scmSalesInvcDfltBalsAcntID").val();
    var myCptrdSalesInvcValsTtlVal = typeof $("#myCptrdSalesInvcValsTtlVal").val() === 'undefined' ? 0 : $("#myCptrdSalesInvcValsTtlVal").val();
    var scmSalesInvcRcvblDocID = typeof $("#scmSalesInvcRcvblDocID").val() === 'undefined' ? -1 : $("#scmSalesInvcRcvblDocID").val();
    var scmSalesInvcRcvblDoc = typeof $("#scmSalesInvcRcvblDoc").val() === 'undefined' ? '' : $("#scmSalesInvcRcvblDoc").val();

    var scmSalesInvcPayMthdID = typeof $("#scmSalesInvcPayMthdID").val() === 'undefined' ? '-1' : $("#scmSalesInvcPayMthdID").val();
    var otherModuleDocTyp = typeof $("#otherModuleDocTyp").val() === 'undefined' ? '' : $("#otherModuleDocTyp").val();
    var otherModuleDocId = typeof $("#otherModuleDocId").val() === 'undefined' ? '-1' : $("#otherModuleDocId").val();

    var scmSalesInvcExRate = typeof $("#scmSalesInvcExRate").val() === 'undefined' ? '1.000' : $("#scmSalesInvcExRate").val();
    var srcSalesInvcDocID = typeof $("#srcSalesInvcDocID").val() === 'undefined' ? '-1' : $("#srcSalesInvcDocID").val();
    var scmSalesInvcCstmrInvcNum = typeof $("#scmSalesInvcCstmrInvcNum").val() === 'undefined' ? '' : $("#scmSalesInvcCstmrInvcNum").val();
    scmSalesInvcDfltTrnsDte = hotlChckinDocStrtDte;
    if (scmSalesInvcDfltTrnsDte.trim() === '' || scmSalesInvcInvcCur.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Batch Date/Currency cannot be empty!</span></p>';
    }
    scmSalesInvcTtlAmnt = fmtAsNumber('scmSalesInvcTtlAmnt').toFixed(2);
    myCptrdSalesInvcValsTtlVal = fmtAsNumber('myCptrdSalesInvcValsTtlVal').toFixed(2);
    scmSalesInvcExRate = fmtAsNumber2('scmSalesInvcExRate').toFixed(4);
    if (myCptrdSalesInvcValsTtlVal !== scmSalesInvcTtlAmnt) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    var slctdExtraInfoLines = "";
    var slctdFcltiesInfoLines = "";
    if (srcPage === 'GYM') {
        $('#oneSalesRqstdItmLinesTable').find('tr').each(function (i, el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr('id') === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr('id').split("_")[1];
                    var ln_TrnsLnID = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_TrnsLnID').val();
                    var ln_RoomID = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_RoomID').val();
                    var ln_RoomName = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_RoomName').val();
                    var ln_RoomDesc = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_RoomDesc').val();
                    var ln_HrsExpctd = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_HrsExpctd').val();
                    var ln_StrtDte = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_StrtDte').val();
                    var ln_EndDte = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_EndDte').val();
                    var ln_HrsDone = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_HrsDone').val();
                    var ln_LineDesc = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_LineDesc').val();
                    var ln_IsCmpltd = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_IsCmpltd:checked').length > 0 ? "YES" : "NO";
                    if (Number(ln_RoomID.replace(/[^-?0-9\.]/g, '')) > 0) {
                        if (Number(ln_RoomID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Activity for Row No. ' + i + ' cannot be empty!</span></p>';
                            $('#oneHotlChckinDocSmryRow' + rndmNum + '_RoomName').addClass('rho-error');
                        } else {
                            $('#oneHotlChckinDocSmryRow' + rndmNum + '_RoomName').removeClass('rho-error');
                        }
                        if (isVld === true) {
                            slctdFcltiesInfoLines = slctdFcltiesInfoLines +
                                ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_RoomID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_RoomName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_RoomDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_HrsExpctd.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_StrtDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_EndDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_HrsDone.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_IsCmpltd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        });
    } else if (srcPage === 'RENT') {
        $('#oneSalesRqstdItmLinesTable').find('tr').each(function (i, el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr('id') === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr('id').split("_")[1];
                    var ln_TrnsLnID = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_TrnsLnID').val();
                    var ln_SrvcTypID = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_SrvcTypID').val();
                    var ln_RoomID = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_RoomID').val();
                    var ln_RoomNum = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_RoomNum').val();
                    var ln_CstmrID = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_CstmrID').val();
                    var ln_CstmrNm = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_CstmrNm').val();
                    var ln_CstmrSiteID = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_CstmrSiteID').val();
                    var ln_StrtDte = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_StrtDte').val();
                    var ln_EndDte = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_EndDte').val();
                    var ln_LineDesc = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_LineDesc').val();
                    var ln_DocNum = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_DocNum').val();
                    var ln_RentedQty = $('#oneSalesRqstdItmLinesRow' + rndmNum + '_RentedQty').val();
                    if (Number(ln_SrvcTypID.replace(/[^-?0-9\.]/g, '')) > 0) {
                        if (Number(ln_SrvcTypID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Facility Type for Row No. ' + i + ' cannot be empty!</span></p>';
                            $('#oneHotlChckinDocSmryRow' + rndmNum + '_SrvcTyp').addClass('rho-error');
                        } else {
                            $('#oneHotlChckinDocSmryRow' + rndmNum + '_SrvcTyp').removeClass('rho-error');
                        }
                        if (Number(ln_RoomID.replace(/[^-?0-9\.]/g, '')) <= 0) {
                            isVld = false;
                            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Facility Number for Row No. ' + i + ' cannot be empty!</span></p>';
                            $('#oneHotlChckinDocSmryRow' + rndmNum + '_RoomID').addClass('rho-error');
                        } else {
                            $('#oneHotlChckinDocSmryRow' + rndmNum + '_RoomID').removeClass('rho-error');
                        }
                        if (isVld === true) {
                            slctdFcltiesInfoLines = slctdFcltiesInfoLines +
                                ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_SrvcTypID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_RoomID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_RoomNum.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_CstmrID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_CstmrNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_StrtDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_EndDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_DocNum.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_CstmrSiteID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                ln_RentedQty.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                        }
                    }
                }
            }
        });
    }
    $('#oneScmSalesInvcSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_TrnsLnID').val();
                var ln_ItmID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ItmID').val();
                var ln_StoreID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_StoreID').val();
                var ln_CnsgnIDs = $('#oneScmSalesInvcSmryRow' + rndmNum + '_CnsgnIDs').val();
                var ln_ItmAccnts = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ItmAccnts').val();
                var ln_LnkdPrsnID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_LnkdPrsnID').val();
                var ln_LineDesc = $('#oneScmSalesInvcSmryRow' + rndmNum + '_LineDesc').val();
                var ln_QTY = $('#oneScmSalesInvcSmryRow' + rndmNum + '_QTY').val();
                var ln_UnitPrice = $('#oneScmSalesInvcSmryRow' + rndmNum + '_UnitPrice').val();
                var ln_TaxID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_TaxID').val();
                var ln_DscntID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_DscntID').val();
                var ln_ChrgID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ChrgID').val();
                var ln_SrcDocLnID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_SrcDocLnID').val();
                var ln_ExtraDesc = $('#oneScmSalesInvcSmryRow' + rndmNum + '_ExtraDesc').val();
                var ln_RentedQty = $('#oneScmSalesInvcSmryRow' + rndmNum + '_RentedQty').val();
                var ln_OthrMdlID = $('#oneScmSalesInvcSmryRow' + rndmNum + '_OthrMdlID').val();
                var ln_OthrMdlTyp = $('#oneScmSalesInvcSmryRow' + rndmNum + '_OthrMdlTyp').val();
                var ln_IsDlvrd = typeof $("input[name='oneScmSalesInvcSmryRow" + rndmNum + "_IsDlvrd']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (Number(ln_ItmID.replace(/[^-?0-9\.]/g, '')) > 0) {
                    if (ln_LineDesc.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Item Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneScmSalesInvcSmryRow' + rndmNum + '_LineDesc').addClass('rho-error');
                    } else {
                        $('#oneScmSalesInvcSmryRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                    }
                    /*if (Number(ln_QTY.replace(/[^-?0-9\.]/g, '')) <= 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Quantity for Row No. ' + i + ' cannot be zero or less!</span></p>';
                     $('#oneScmSalesInvcSmryRow' + rndmNum + '_QTY').addClass('rho-error');
                     } else {
                     $('#oneScmSalesInvcSmryRow' + rndmNum + '_QTY').removeClass('rho-error');
                     }
                     if (Number(ln_UnitPrice.replace(/[^-?0-9\.]/g, '')) === 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Selling Price for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneScmSalesInvcSmryRow' + rndmNum + '_UnitPrice').addClass('rho-error');
                     } else {
                     $('#oneScmSalesInvcSmryRow' + rndmNum + '_UnitPrice').removeClass('rho-error');
                     }*/
                    if (isVld === true) {
                        slctdDetTransLines = slctdDetTransLines +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StoreID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CnsgnIDs.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ItmAccnts.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LnkdPrsnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_QTY.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_UnitPrice.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_TaxID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_DscntID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ChrgID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SrcDocLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ExtraDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_RentedQty.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_OthrMdlID.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_OthrMdlTyp.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_IsDlvrd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
    if (shdSbmt == 2) {
        var msgPrt = hotlChckinDocVchType;
        if (hotlChckinDocVchType === "Rent Out") {
            msgPrt = "DO FACILITY RETURN and CLOSE";
        } else if (hotlChckinDocVchType === "Check-In") {
            msgPrt = "CHECK-OUT";
        } else if (hotlChckinDocVchType === "Reservations" || hotlChckinDocVchType === "Booking") {
            msgPrt = "CANCEL and UNRESERVE";
        } else if (hotlChckinDocVchType === "Gym/Sport Subscription") {
            msgPrt = "FINALIZE";
        }
        var dialog = bootbox.confirm({
            title: '' + msgPrt + '?',
            size: 'small',
            message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">' + msgPrt + '</span> this Transaction?<br/>Action cannot be Undone!</p>',
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
                        title: 'Save ' + hotlChckinDocVchType,
                        size: 'small',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + hotlChckinDocVchType + '...Please Wait...</p>',
                        callback: function () {
                            if (shdSbmt === 2) {
                                if (invcSrc === 'QUICK_SALE') {
                                    getHotlChckinDoc('', '#allmodules', 'grp=18&typ=1&pg=7&vtyp=1');
                                } else {
                                    getOneHotlChckinDocForm(sbmtdHotlChckinDocID, 3, 'ReloadDialog', hotlChckinDocVchType, srcPage);
                                }
                            }
                        }
                    });
                    var formData = new FormData();
                    if (srcPage === 'EVENT_CHECK-IN') {
                        formData.append('grp', 16);
                    } else {
                        formData.append('grp', 18);
                    }
                    formData.append('typ', 1);

                    if (srcPage === 'CHECK-IN') {
                        formData.append('pg', 2);
                    } else if (srcPage === 'RESTAURANT') {
                        formData.append('pg', 4);
                    } else if (srcPage === 'GYM') {
                        formData.append('pg', 5);
                    } else if (srcPage === 'EVENT_CHECK-IN') {
                        formData.append('pg', 7);
                    } else {
                        formData.append('pg', 7);
                    }
                    formData.append('q', 'UPDATE');
                    formData.append('actyp', 1);
                    formData.append('sbmtdHotlChckinDocID', sbmtdHotlChckinDocID);
                    formData.append('hotlChckinDocVchType', hotlChckinDocVchType);
                    formData.append('hotlChckinDocNum', hotlChckinDocNum);
                    formData.append('hotlChckinFcltyType', hotlChckinFcltyType);
                    formData.append('hotlChckinDocStrtDte', hotlChckinDocStrtDte);
                    formData.append('hotlChckinDocEndDte', hotlChckinDocEndDte);
                    formData.append('hotlChckinDocSrvcTypID', hotlChckinDocSrvcTypID);
                    formData.append('hotlChckinDocSrvcTyp', hotlChckinDocSrvcTyp);
                    formData.append('hotlChckinDocRmID', hotlChckinDocRmID);
                    formData.append('hotlChckinDocRmNum', hotlChckinDocRmNum);
                    formData.append('hotlChckinDocNoAdlts', hotlChckinDocNoAdlts);
                    formData.append('hotlChckinDocNoChldrn', hotlChckinDocNoChldrn);
                    formData.append('hotlChckinDocSpnsrID', hotlChckinDocSpnsrID);
                    formData.append('hotlChckinDocSpnsr', hotlChckinDocSpnsr);
                    formData.append('hotlChckinDocSpnsrSiteID', hotlChckinDocSpnsrSiteID);
                    formData.append('hotlChckinDocSpnsrSite', hotlChckinDocSpnsrSite);
                    formData.append('hotlChckinDocSpnseeID', hotlChckinDocSpnseeID);
                    formData.append('hotlChckinDocSpnsee', hotlChckinDocSpnsee);
                    formData.append('hotlChckinDocSpnseeSiteID', hotlChckinDocSpnseeSiteID);
                    formData.append('hotlChckinDocSpnseeSite', hotlChckinDocSpnseeSite);
                    formData.append('hotlChckinDocOthrInfo', hotlChckinDocOthrInfo);
                    formData.append('hotlChckinDocCalcMthd', hotlChckinDocCalcMthd);
                    formData.append('hotlChckinDocArvlFrm', hotlChckinDocArvlFrm);
                    formData.append('hotlChckinDocPrcdTo', hotlChckinDocPrcdTo);
                    formData.append('hotlChckinDocPrntChcknID', hotlChckinDocPrntChcknID);
                    formData.append('hotlChckinDocPrntChcknTyp', hotlChckinDocPrntChcknTyp);

                    formData.append('sbmtdScmSalesInvcID', sbmtdScmSalesInvcID);
                    formData.append('scmSalesInvcDocNum', scmSalesInvcDocNum);
                    formData.append('scmSalesInvcDfltTrnsDte', scmSalesInvcDfltTrnsDte);
                    formData.append('scmSalesInvcVchType', scmSalesInvcVchType);
                    formData.append('scmSalesInvcInvcCur', scmSalesInvcInvcCur);
                    formData.append('scmSalesInvcTtlAmnt', scmSalesInvcTtlAmnt);

                    formData.append('scmSalesInvcEvntDocTyp', scmSalesInvcEvntDocTyp);
                    formData.append('scmSalesInvcEvntCtgry', scmSalesInvcEvntCtgry);
                    formData.append('scmSalesInvcEvntRgstrID', scmSalesInvcEvntRgstrID);
                    formData.append('scmSalesInvcCstmrID', scmSalesInvcCstmrID);
                    formData.append('scmSalesInvcCstmrSiteID', scmSalesInvcCstmrSiteID);
                    formData.append('scmSalesInvcBrnchID', scmSalesInvcBrnchID);
                    formData.append('scmSalesInvcDesc', scmSalesInvcDesc);
                    formData.append('scmSalesInvcRcvblDocID', scmSalesInvcRcvblDocID);
                    formData.append('scmSalesInvcRcvblDoc', scmSalesInvcRcvblDoc);
                    formData.append('scmSalesInvcPayTerms', scmSalesInvcPayTerms);
                    formData.append('scmSalesInvcDfltBalsAcntID', scmSalesInvcDfltBalsAcntID);
                    formData.append('myCptrdSalesInvcValsTtlVal', myCptrdSalesInvcValsTtlVal);
                    formData.append('scmSalesInvcPayMthdID', scmSalesInvcPayMthdID);
                    formData.append('otherModuleDocTyp', otherModuleDocTyp);
                    formData.append('otherModuleDocId', otherModuleDocId);
                    formData.append('scmSalesInvcExRate', scmSalesInvcExRate);
                    formData.append('srcSalesInvcDocID', srcSalesInvcDocID);
                    formData.append('scmSalesInvcCstmrInvcNum', scmSalesInvcCstmrInvcNum);

                    formData.append('slctdDetTransLines', slctdDetTransLines);
                    formData.append('slctdExtraInfoLines', slctdExtraInfoLines);
                    formData.append('slctdFcltiesInfoLines', slctdFcltiesInfoLines);

                    formData.append('shdSbmt', shdSbmt);
                    formData.append('slctdDetTransLines', slctdDetTransLines);
                    dialog1.init(function () {
                        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                                        dialog1.find('.bootbox-body').html(data.message);
                                        //alert(data.message);
                                        if (data.message.indexOf("Error") !== -1 || data.message.indexOf("ERROR") !== -1 || data.message.indexOf("red") !== -1) {
                                            sbmtdHotlChckinDocID = data.sbmtdHotlChckinDocID;
                                        } else if (data.message.indexOf("Success") !== -1 || data.message.indexOf("SUCCESS") !== -1) {
                                            sbmtdHotlChckinDocID = data.sbmtdHotlChckinDocID;
                                            dialog1.modal('hide');
                                            if (invcSrc === 'QUICK_SALE') {
                                                getHotlChckinDoc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1');
                                            } else {
                                                getOneHotlChckinDocForm(sbmtdHotlChckinDocID, 3, 'ReloadDialog', hotlChckinDocVchType, srcPage, srcModule);
                                            }
                                        }
                                    }, 500);
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
    } else {
        var dialog1 = bootbox.alert({
            title: 'Save ' + hotlChckinDocVchType,
            size: 'small',
            message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + hotlChckinDocVchType + '...Please Wait...</p>',
            callback: function () {
                if (shdSbmt === 2) {
                    if (invcSrc === 'QUICK_SALE') {
                        getHotlChckinDoc('', '#allmodules', 'grp=18&typ=1&pg=7&vtyp=1');
                    } else {
                        getOneHotlChckinDocForm(sbmtdHotlChckinDocID, 3, 'ReloadDialog', hotlChckinDocVchType, srcPage);
                    }
                }
            }
        });
        var formData = new FormData();
        if (srcPage === 'EVENT_CHECK-IN') {
            formData.append('grp', 16);
        } else {
            formData.append('grp', 18);
        }
        formData.append('typ', 1);
        if (srcPage === 'CHECK-IN') {
            formData.append('pg', 2);
        } else if (srcPage === 'RESTAURANT') {
            formData.append('pg', 4);
        } else if (srcPage === 'GYM') {
            formData.append('pg', 5);
        } else if (srcPage === 'EVENT_CHECK-IN') {
            formData.append('pg', 7);
        } else {
            formData.append('pg', 7);
        }
        formData.append('q', 'UPDATE');
        formData.append('actyp', 1);
        formData.append('sbmtdHotlChckinDocID', sbmtdHotlChckinDocID);
        formData.append('hotlChckinDocVchType', hotlChckinDocVchType);
        formData.append('hotlChckinDocNum', hotlChckinDocNum);
        formData.append('hotlChckinFcltyType', hotlChckinFcltyType);
        formData.append('hotlChckinDocStrtDte', hotlChckinDocStrtDte);
        formData.append('hotlChckinDocEndDte', hotlChckinDocEndDte);
        formData.append('hotlChckinDocSrvcTypID', hotlChckinDocSrvcTypID);
        formData.append('hotlChckinDocSrvcTyp', hotlChckinDocSrvcTyp);
        formData.append('hotlChckinDocRmID', hotlChckinDocRmID);
        formData.append('hotlChckinDocRmNum', hotlChckinDocRmNum);
        formData.append('hotlChckinDocNoAdlts', hotlChckinDocNoAdlts);
        formData.append('hotlChckinDocNoChldrn', hotlChckinDocNoChldrn);
        formData.append('hotlChckinDocSpnsrID', hotlChckinDocSpnsrID);
        formData.append('hotlChckinDocSpnsr', hotlChckinDocSpnsr);
        formData.append('hotlChckinDocSpnsrSiteID', hotlChckinDocSpnsrSiteID);
        formData.append('hotlChckinDocSpnsrSite', hotlChckinDocSpnsrSite);
        formData.append('hotlChckinDocSpnseeID', hotlChckinDocSpnseeID);
        formData.append('hotlChckinDocSpnsee', hotlChckinDocSpnsee);
        formData.append('hotlChckinDocSpnseeSiteID', hotlChckinDocSpnseeSiteID);
        formData.append('hotlChckinDocSpnseeSite', hotlChckinDocSpnseeSite);
        formData.append('hotlChckinDocOthrInfo', hotlChckinDocOthrInfo);
        formData.append('hotlChckinDocCalcMthd', hotlChckinDocCalcMthd);
        formData.append('hotlChckinDocArvlFrm', hotlChckinDocArvlFrm);
        formData.append('hotlChckinDocPrcdTo', hotlChckinDocPrcdTo);
        formData.append('hotlChckinDocPrntChcknID', hotlChckinDocPrntChcknID);
        formData.append('hotlChckinDocPrntChcknTyp', hotlChckinDocPrntChcknTyp);

        formData.append('sbmtdScmSalesInvcID', sbmtdScmSalesInvcID);
        formData.append('scmSalesInvcDocNum', scmSalesInvcDocNum);
        formData.append('scmSalesInvcDfltTrnsDte', scmSalesInvcDfltTrnsDte);
        formData.append('scmSalesInvcVchType', scmSalesInvcVchType);
        formData.append('scmSalesInvcInvcCur', scmSalesInvcInvcCur);
        formData.append('scmSalesInvcTtlAmnt', scmSalesInvcTtlAmnt);

        formData.append('scmSalesInvcEvntDocTyp', scmSalesInvcEvntDocTyp);
        formData.append('scmSalesInvcEvntCtgry', scmSalesInvcEvntCtgry);
        formData.append('scmSalesInvcEvntRgstrID', scmSalesInvcEvntRgstrID);
        formData.append('scmSalesInvcCstmrID', scmSalesInvcCstmrID);
        formData.append('scmSalesInvcCstmrSiteID', scmSalesInvcCstmrSiteID);
        formData.append('scmSalesInvcBrnchID', scmSalesInvcBrnchID);
        formData.append('scmSalesInvcDesc', scmSalesInvcDesc);
        formData.append('scmSalesInvcRcvblDocID', scmSalesInvcRcvblDocID);
        formData.append('scmSalesInvcRcvblDoc', scmSalesInvcRcvblDoc);
        formData.append('scmSalesInvcPayTerms', scmSalesInvcPayTerms);
        formData.append('scmSalesInvcDfltBalsAcntID', scmSalesInvcDfltBalsAcntID);
        formData.append('myCptrdSalesInvcValsTtlVal', myCptrdSalesInvcValsTtlVal);
        formData.append('scmSalesInvcPayMthdID', scmSalesInvcPayMthdID);
        formData.append('otherModuleDocTyp', otherModuleDocTyp);
        formData.append('otherModuleDocId', otherModuleDocId);
        formData.append('scmSalesInvcExRate', scmSalesInvcExRate);
        formData.append('srcSalesInvcDocID', srcSalesInvcDocID);
        formData.append('scmSalesInvcCstmrInvcNum', scmSalesInvcCstmrInvcNum);

        formData.append('slctdDetTransLines', slctdDetTransLines);
        formData.append('slctdExtraInfoLines', slctdExtraInfoLines);
        formData.append('slctdFcltiesInfoLines', slctdFcltiesInfoLines);

        formData.append('shdSbmt', shdSbmt);
        formData.append('slctdDetTransLines', slctdDetTransLines);
        dialog1.init(function () {
            getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                            dialog1.find('.bootbox-body').html(data.message);
                            //alert(data.message);
                            if (data.message.indexOf("Error") !== -1 || data.message.indexOf("ERROR") !== -1 || data.message.indexOf("red") !== -1) {
                                sbmtdHotlChckinDocID = data.sbmtdHotlChckinDocID;
                            } else if (data.message.indexOf("Success") !== -1 || data.message.indexOf("SUCCESS") !== -1) {
                                sbmtdHotlChckinDocID = data.sbmtdHotlChckinDocID;
                                dialog1.modal('hide');
                                if (invcSrc === 'QUICK_SALE') {
                                    getHotlChckinDoc('', '#allmodules', 'grp=12&typ=1&pg=1&vtyp=1');
                                } else {
                                    getOneHotlChckinDocForm(sbmtdHotlChckinDocID, 3, 'ReloadDialog', hotlChckinDocVchType, srcPage, srcModule);
                                }
                            }

                        }, 500);
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

function saveHotlChckinDocRvrslForm(funcCur, shdSbmt, invcSrc, srcPage, srcModule) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslScmSalesInvcBtn");
    }
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof srcPage === 'undefined' || srcPage === null) {
        srcPage = 'RENT';
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdHotlChckinDocID = typeof $("#sbmtdHotlChckinDocID").val() === 'undefined' ? -1 : $("#sbmtdHotlChckinDocID").val();
    var hotlChckinDocDesc = typeof $("#hotlChckinDocOthrInfo").val() === 'undefined' ? '' : $("#hotlChckinDocOthrInfo").val();
    var hotlChckinDocDesc1 = typeof $("#hotlChckinDocOthrInfo1").val() === 'undefined' ? '' : $("#hotlChckinDocOthrInfo1").val();
    var hotlChckinDocVchType = typeof $("#hotlChckinDocVchType").val() === 'undefined' ? '' : $("#hotlChckinDocVchType").val();
    var hotlChckinDocNum = typeof $("#hotlChckinDocNum").val() === 'undefined' ? '' : $("#hotlChckinDocNum").val();
    var errMsg = "";
    if (sbmtdHotlChckinDocID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (hotlChckinDocDesc === "" || hotlChckinDocDesc === hotlChckinDocDesc1) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#hotlChckinDocOthrInfo").addClass('rho-error');
        $("#hotlChckinDocOthrInfo").attr("readonly", false);
        $("#fnlzeRvrslScmSalesInvcBtn").attr("disabled", false);
        if (invcSrc === 'QUICK_SALE') {
            $('#salesInvcExtraInfotab').tab('show');
        }
    } else {
        $("#hotlChckinDocOthrInfo").removeClass('rho-error');
    }
    if (rhotrim(errMsg, '; ') !== '') {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var msgsTitle = hotlChckinDocVchType;
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody = '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' + msgsTitle + '?<br/>This action cannot be UNDONE!</p>';
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
                var msg = 'Facility Transaction';
                var dialog = bootbox.alert({
                    title: 'Save ' + msg,
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msg + '...Please Wait...</p>',
                    callback: function () {
                        sbmtdHotlChckinDocID = typeof $("#sbmtdHotlChckinDocID").val() === 'undefined' ? -1 : $("#sbmtdHotlChckinDocID").val();
                        if (sbmtdHotlChckinDocID > 0) {
                            if (invcSrc === 'QUICK_SALE') {
                                getHotlChckinDoc('', '#allmodules', 'grp=18&typ=1&pg=7&vtyp=1');
                            } else {
                                getOneHotlChckinDocForm(sbmtdHotlChckinDocID, 3, 'ReloadDialog', hotlChckinDocVchType, srcPage, srcModule);
                            }
                        }
                    }
                });
                dialog.init(function () {
                    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
                        var pgNo = 7;
                        var grpNo = 18;
                        if (srcPage === 'CHECK-IN') {
                            pgNo = 2;
                        } else if (srcPage === 'RESTAURANT') {
                            pgNo = 4;
                        } else if (srcPage === 'GYM') {
                            pgNo = 5;
                        } else if (srcPage === 'EVENT_CHECK-IN') {
                            grpNo = 16;
                            pgNo = 7;
                        }
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: grpNo,
                                typ: 1,
                                pg: pgNo,
                                actyp: 1,
                                q: 'VOID',
                                hotlChckinDocDesc: hotlChckinDocDesc,
                                sbmtdHotlChckinDocID: sbmtdHotlChckinDocID,
                                hotlChckinDocVchType: hotlChckinDocVchType,
                                hotlChckinDocNum: hotlChckinDocNum,
                                shdSbmt: shdSbmt
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                                    obj = $.parseJSON(data);
                                    sbmtdHotlChckinDocID = obj.sbmtdHotlChckinDocID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdHotlChckinDocID > 0) {
                                        $("#sbmtdHotlChckinDocID").val(sbmtdHotlChckinDocID);
                                    }
                                    if (msg.trim() === '') {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find('.bootbox-body').html(msg);
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
        }
    });
}

function getHotlRcvblsAcntInfo() {
    var scmSalesInvcCstmrID = typeof $("#hotlChckinDocSpnsrID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnsrID").val();
    var scmSalesInvcCstmrSiteID = typeof $("#hotlChckinDocSpnsrSiteID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnsrSiteID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('pg', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        formData.append('hotlChckinDocSpnsrID', scmSalesInvcCstmrID);
        formData.append('hotlChckinDocSpnsrSiteID', scmSalesInvcCstmrSiteID);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#scmSalesInvcDfltBalsAcnt').val(data.BalsAcntNm);
                $('#scmSalesInvcDfltBalsAcntID').val(data.BalsAcntID);
                $('#hotlChckinDocSpnsrSite').val(data.scmSalesInvcCstmrSiteNm);
                $('#hotlChckinDocSpnsrSiteID').val(data.scmSalesInvcCstmrSiteID);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function getHotlOccpntRcvblsAcntInfo() {
    var scmSalesInvcCstmrID = typeof $("#hotlChckinDocSpnseeID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnseeID").val();
    var scmSalesInvcCstmrSiteID = typeof $("#hotlChckinDocSpnseeSiteID").val() === 'undefined' ? '-1' : $("#hotlChckinDocSpnseeSiteID").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('pg', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        formData.append('hotlChckinDocSpnsrID', scmSalesInvcCstmrID);
        formData.append('hotlChckinDocSpnsrSiteID', scmSalesInvcCstmrSiteID);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#hotlChckinDocSpnseeSite').val(data.scmSalesInvcCstmrSiteNm);
                $('#hotlChckinDocSpnseeSiteID').val(data.scmSalesInvcCstmrSiteID);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function getHotlLineRcvblsAcntInfo(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var scmSalesInvcCstmrID = typeof $('#' + rowPrfxNm + rndmNum + '_CstmrID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_CstmrID').val();
    var scmSalesInvcCstmrSiteID = typeof $('#' + rowPrfxNm + rndmNum + '_CstmrSiteID').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_CstmrSiteID').val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('pg', 1);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        formData.append('#' + rowPrfxNm + rndmNum + '_CstmrID', scmSalesInvcCstmrID);
        formData.append('#' + rowPrfxNm + rndmNum + '_CstmrSiteID', scmSalesInvcCstmrSiteID);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //$('#scmSalesInvcDfltBalsAcnt').val(data.BalsAcntNm);
                //$('#scmSalesInvcDfltBalsAcntID').val(data.BalsAcntID);
                //$('#hotlChckinDocSpnsrSite').val(data.scmSalesInvcCstmrSiteNm);
                $('#' + rowPrfxNm + rndmNum + '_CstmrSiteID').val(data.scmSalesInvcCstmrSiteID);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function getAttnEvntRgstrInfo() {
    var sbmtdRegisterID = typeof $("#sbmtdRegisterID").val() === 'undefined' ? '-1' : $("#sbmtdRegisterID").val();
    //alert(sbmtdRegisterID);
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp', 16);
        formData.append('typ', 1);
        formData.append('pg', 7);
        formData.append('q', 'VIEW');
        formData.append('vtyp', 4);
        formData.append('sbmtdRegisterID', sbmtdRegisterID);
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert(data);
                $('#hotlChckinDocSrvcTypID').val(data.srvcTypeIDTextBox);
                $('#hotlChckinDocSrvcTyp').val(data.srvcTypeTextBox);
                $('#hotlChckinDocStrtDte').val(data.sbmtdStrdDte);
                $('#hotlChckinDocEndDte').val(data.sbmtdEndDte);
                $('#sbmtdTmTblID').val(data.sbmtdTmTblID);
                $('#sbmtdEvntID').val(data.sbmtdEvntID);
                $('#hotlChckinDocRmID').val(data.hotlChckinDocRmID);
                $('#hotlChckinDocRmNum').val(data.hotlChckinDocRmNum);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function getRoomNumLovsPage(strDteElementID, endDteElementID, elementID, titleElementID, modalBodyID, lovNm, criteriaID,
    criteriaID2, criteriaID3, chkOrRadio, mustSelSth,
    selVals, valueElmntID, descElemntID, actionText, colNoForChkBxCmprsn,
    addtnlWhere, callBackFunc, psblValIDElmntID) {
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
}

function getHotlSmryDshBrd(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#hotlSmryDshBrdSrchFor").val() === 'undefined' ? '%' : $("#hotlSmryDshBrdSrchFor").val();
    var srchIn = typeof $("#hotlSmryDshBrdSrchIn").val() === 'undefined' ? 'Both' : $("#hotlSmryDshBrdSrchIn").val();
    var pageNo = typeof $("#hotlSmryDshBrdPageNo").val() === 'undefined' ? 1 : $("#hotlSmryDshBrdPageNo").val();
    var limitSze = typeof $("#hotlSmryDshBrdDsplySze").val() === 'undefined' ? 10 : $("#hotlSmryDshBrdDsplySze").val();
    var sortBy = typeof $("#hotlSmryDshBrdSortBy").val() === 'undefined' ? '' : $("#hotlSmryDshBrdSortBy").val();
    var qShwUnpstdOnly = $('#hotlSmryDshBrdShwUnpstdOnly:checked').length > 0;
    var qShwUnpaidOnly = $('#hotlSmryDshBrdShwUnpaidOnly:checked').length > 0;
    var qShwSelfOnly = $('#hotlSmryDshBrdShwSelfOnly:checked').length > 0;
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
        "&qShwUnpaidOnly=" + qShwUnpaidOnly + "&qShwUnpstdOnly=" + qShwUnpstdOnly + "&qShwSelfOnly=" + qShwSelfOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncHotlSmryDshBrd(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getHotlSmryDshBrd(actionText, slctr, linkArgs);
    }
}

function mySmmryBtnFuction(e1, ln_RoomNm, pgNo, searchIn, titleMsg) {
    //Facility Number
    //Rent Out(s)
    var lnkArgs = 'grp=18&typ=1&pg=' + pgNo + '&vtyp=0&searchfor=' + ln_RoomNm + '&searchin=' + searchIn + '&srcModule=myFormsModalNmBody&limitSze=10';
    doAjaxWthCallBck(lnkArgs, 'myFormsModalNm', 'ShowDialog', titleMsg + " for (" + ln_RoomNm + ")", 'myFormsModalNmTitle', 'myFormsModalNmBody', function () {
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
        $('#myFormsModalNm').off('hidden.bs.modal');
        $('#myFormsModalNm').one('hidden.bs.modal', function (e) {
            getHotlSmryDshBrd('', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');
            $(e.currentTarget).unbind();
        });
    });
    $(e1.currentTarget).unbind();
}

function callHotlBtnClickFunc(e1, inptBtnElmntID) {

    var fcltyTypeComboBox = typeof $("#hotlSmryDshBrdSortBy").val() === 'undefined' ? '' : $("#hotlSmryDshBrdSortBy").val();
    var curText = typeof $("#" + inptBtnElmntID + "_Status").val() === 'undefined' ? '' : $("#" + inptBtnElmntID + "_Status").val();
    var ln_BtnText = typeof $("#" + inptBtnElmntID + "_BtnText").val() === 'undefined' ? '' : $("#" + inptBtnElmntID + "_BtnText").val();
    var ln_RoomNm = typeof $("#" + inptBtnElmntID + "_RoomNm").val() === 'undefined' ? '' : $("#" + inptBtnElmntID + "_RoomNm").val();
    var ln_SrvsTypID = typeof $("#" + inptBtnElmntID + '_SrvsTypID').val() === 'undefined' ? '-1' : $("#" + inptBtnElmntID + '_SrvsTypID').val();
    var ln_RoomID = typeof $("#" + inptBtnElmntID + '_RoomID').val() === 'undefined' ? '-1' : $("#" + inptBtnElmntID + '_RoomID').val();
    var ln_FcltyType = fcltyTypeComboBox;

    if (fcltyTypeComboBox.indexOf("Table") !== -1) {
        mySmmryBtnFuction(e1, ln_RoomNm, 4, 'Table/Room Number', 'Table Order(s)');
    } else if (fcltyTypeComboBox.indexOf("Rental Item") !== -1) {
        mySmmryBtnFuction(e1, ln_RoomNm, 7, 'Facility Number', 'Rent Out(s)');
    } else {
        mySmmryBtnFuction(e1, ln_RoomNm, 2, 'Facility Number', 'Check-In(s)/Reservation(s)');
    }
}

function enblDisableHotlMenu(inptBtnElmntID) {
    var fcltyTypeComboBox = typeof $("#hotlSmryDshBrdSortBy").val() === 'undefined' ? '' : $("#hotlSmryDshBrdSortBy").val();
    var curText = typeof $("#" + inptBtnElmntID + "_Status").val() === 'undefined' ? '' : $("#" + inptBtnElmntID + "_Status").val();
    var ln_BtnText = typeof $("#" + inptBtnElmntID + "_BtnText").val() === 'undefined' ? '' : $("#" + inptBtnElmntID + "_BtnText").val();
    var ln_RoomNm = typeof $("#" + inptBtnElmntID + "_RoomNm").val() === 'undefined' ? '' : $("#" + inptBtnElmntID + "_RoomNm").val();
    var ln_SrvsTypID = typeof $("#" + inptBtnElmntID + '_SrvsTypID').val() === 'undefined' ? '-1' : $("#" + inptBtnElmntID + '_SrvsTypID').val();
    var ln_RoomID = typeof $("#" + inptBtnElmntID + '_RoomID').val() === 'undefined' ? '-1' : $("#" + inptBtnElmntID + '_RoomID').val();
    var ln_FcltyType = fcltyTypeComboBox;

    if (fcltyTypeComboBox.indexOf("Table") !== -1) {
        $("#openCheckinMenuItem").html("<img src=\"cmn_images/openfileicon.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Open Table Order(s)");
        $("#createChckInMenuItem").html("<img src=\"cmn_images/person.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Create Table Order");
        $("#createRsrvtnMenuItem").addClass("hideNotice");
        $('#openCheckinMenuItem').off('click');
        $('#createChckInMenuItem').off('click');
        $('#openCheckinMenuItem').on('click', function (e1) {
            mySmmryBtnFuction(e1, ln_RoomNm, 4, 'Table/Room Number', 'Table Order(s)');
        });
        $('#createChckInMenuItem').on('click', function () {
            getOneHotlChckinDocForm(-1, 3, 'ShowDialog', 'Check-In', 'CHECK-IN', 'DASHBOARD', ln_FcltyType, ln_SrvsTypID, ln_RoomID);
        });
    } else if (fcltyTypeComboBox.indexOf("Rental Item") !== -1) {
        $("#openCheckinMenuItem").html("<img src=\"cmn_images/openfileicon.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Open Rent Out(s)");
        $("#createChckInMenuItem").html("<img src=\"cmn_images/person.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Create Rent Out");
        $("#createRsrvtnMenuItem").removeClass("hideNotice");
        $('#createRsrvtnMenuItem').off('click');
        $('#openCheckinMenuItem').off('click');
        $('#createChckInMenuItem').off('click');
        $('#openCheckinMenuItem').on('click', function (e1) {
            mySmmryBtnFuction(e1, ln_RoomNm, 7, 'Facility Number', 'Rent Out(s)');
        });
        $('#createChckInMenuItem').on('click', function () {
            getOneHotlChckinDocForm(-1, 3, 'ShowDialog', 'Rent Out', '', 'DASHBOARD', ln_FcltyType, ln_SrvsTypID, ln_RoomID);
        });
        $('#createRsrvtnMenuItem').on('click', function () {
            getOneHotlChckinDocForm(-1, 3, 'ShowDialog', 'Reservation', '', 'DASHBOARD', ln_FcltyType, ln_SrvsTypID, ln_RoomID);
        });
    } else {
        $("#openCheckinMenuItem").html("<img src=\"cmn_images/openfileicon.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Open Check-In(s)/Reservation(s)");
        $("#createChckInMenuItem").html("<img src=\"cmn_images/person.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Create Check-In/Rent Out");
        $("#createRsrvtnMenuItem").removeClass("hideNotice");
        $('#createRsrvtnMenuItem').off('click');
        $('#openCheckinMenuItem').off('click');
        $('#createChckInMenuItem').off('click');
        $('#openCheckinMenuItem').on('click', function (e1) {
            mySmmryBtnFuction(e1, ln_RoomNm, 2, 'Facility Number', 'Check-In(s)/Reservation(s)');
        });
        $('#createChckInMenuItem').on('click', function () {
            //alert(ln_FcltyType + "::" + ln_SrvsTypID + "::" + ln_RoomID);
            getOneHotlChckinDocForm(-1, 3, 'ShowDialog', 'Check-In', 'CHECK-IN', 'DASHBOARD', ln_FcltyType, ln_SrvsTypID, ln_RoomID);
        });
        $('#createRsrvtnMenuItem').on('click', function () {
            //alert(ln_FcltyType + "::" + ln_SrvsTypID + "::" + ln_RoomID);
            getOneHotlChckinDocForm(-1, 3, 'ShowDialog', 'Reservation', 'CHECK-IN', 'DASHBOARD', ln_FcltyType, ln_SrvsTypID, ln_RoomID);
        });
    }
    $('#vwRoomMenuItem').off('click');
    $('#vwRoomMenuItem').on('click', function () {
        getOneHotlSrvsTypForm(ln_SrvsTypID, 1, 'ShowDialog', 'myFormsModalNm', "Facility Type Setup for (" + ln_RoomNm + ")", 'myFormsModalNmTitle', 'myFormsModalNmBody');
    });
    if (curText.indexOf("AVAILABLE") !== -1) {
        $("#blckUnblockMenuItem").removeClass("hideNotice");
        $("#createChckInMenuItem").removeClass("hideNotice");
        $("#createRsrvtnMenuItem").removeClass("hideNotice");
        $("#openCheckinMenuItem").addClass("hideNotice");
    } else if (curText.indexOf("ISSUED OUT") !== -1 ||
        curText.indexOf("RESERVED") !== -1 ||
        curText.indexOf("OVERLOADED") !== -1) {
        $("#blckUnblockMenuItem").addClass("hideNotice");
        if (curText.indexOf("PARTIALLY") !== -1) {
            $("#createChckInMenuItem").removeClass("hideNotice");
        } else {
            $("#createChckInMenuItem").addClass("hideNotice");
        }
        $("#createRsrvtnMenuItem").removeClass("hideNotice");
        $("#openCheckinMenuItem").removeClass("hideNotice");
    } else if (curText.indexOf("BLOCKED") !== -1 ||
        curText.indexOf("DIRTY") !== -1) {
        $("#createChckInMenuItem").addClass("hideNotice");
        $("#createRsrvtnMenuItem").addClass("hideNotice");
        $("#openCheckinMenuItem").addClass("hideNotice");
    }

    //$("#mkDirtyMenuItem").removeClass("hideNotice");
    //$("#mkCleanMenuItem").removeClass("hideNotice");
    if (ln_BtnText.toUpperCase().indexOf("[DIRTY]") !== -1) {
        $("#mkCleanMenuItem").removeClass("hideNotice");
        $("#mkDirtyMenuItem").addClass("hideNotice");
    } else {
        $("#mkCleanMenuItem").addClass("hideNotice");
        $("#mkDirtyMenuItem").removeClass("hideNotice");
    }
    //if (curText.toUpperCase().indexOf("CLEAN") !== -1)
    $('#mkCleanMenuItem').off('click');
    $('#mkCleanMenuItem').on('click', function () {
        chngFcltyClnEnbldState(ln_RoomID, "IsClean", "0");
    });
    $('#mkDirtyMenuItem').off('click');
    $('#mkDirtyMenuItem').on('click', function () {
        chngFcltyClnEnbldState(ln_RoomID, "IsClean", "1");
    });

    if (ln_BtnText.toUpperCase().indexOf("BLOCKED") !== -1) {
        $("#blckUnblockMenuItem").html("<img src=\"cmn_images/tick_64.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Unblock Facility");
        $("#blckUnblockMenuItem").removeClass("hideNotice");
        $('#blckUnblockMenuItem').off('click');
        $('#blckUnblockMenuItem').on('click', function () {
            chngFcltyClnEnbldState(ln_RoomID, "IsEnabled", "1");
        });
    } else {
        $("#blckUnblockMenuItem").html("<img src=\"cmn_images/90.png\" style=\"left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;\">Block Facility");
        $("#blckUnblockMenuItem").removeClass("hideNotice");
        $('#blckUnblockMenuItem').off('click');
        $('#blckUnblockMenuItem').on('click', function () {
            chngFcltyClnEnbldState(ln_RoomID, "IsEnabled", "0");
        });
    }
}

function chngFcltyClnEnbldState(ln_RoomID, changType, changValue) {
    if (typeof changType === 'undefined' || changType === null) {
        changType = 'IsClean'; //IsEnabled
    }
    var dialog = bootbox.alert({
        title: 'Change Facility State',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Changing Facility State...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 18);
    formData.append('typ', 1);
    formData.append('pg', 3);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 2);
    formData.append('ln_RoomID', ln_RoomID);
    formData.append('changType', changType);
    formData.append('changValue', changValue);
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                        getHotlSmryDshBrd('', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getHotlSrvsTyp(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#hotlSrvsTypSrchFor").val() === 'undefined' ? '%' : $("#hotlSrvsTypSrchFor").val();
    var srchIn = typeof $("#hotlSrvsTypSrchIn").val() === 'undefined' ? 'Both' : $("#hotlSrvsTypSrchIn").val();
    var pageNo = typeof $("#hotlSrvsTypPageNo").val() === 'undefined' ? 1 : $("#hotlSrvsTypPageNo").val();
    var limitSze = typeof $("#hotlSrvsTypDsplySze").val() === 'undefined' ? 10 : $("#hotlSrvsTypDsplySze").val();
    var sortBy = typeof $("#hotlSrvsTypSortBy").val() === 'undefined' ? '' : $("#hotlSrvsTypSortBy").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncHotlSrvsTyp(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getHotlSrvsTyp(actionText, slctr, linkArgs);
    }
}

function getHotlSrvsTypDet(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#hotlSrvsTypDetSrchFor").val() === 'undefined' ? '%' : $("#hotlSrvsTypDetSrchFor").val();
    var srchIn = typeof $("#hotlSrvsTypDetSrchIn").val() === 'undefined' ? 'Both' : $("#hotlSrvsTypDetSrchIn").val();
    var pageNo = typeof $("#hotlSrvsTypDetPageNo").val() === 'undefined' ? 1 : $("#hotlSrvsTypDetPageNo").val();
    var limitSze = typeof $("#hotlSrvsTypDetDsplySze").val() === 'undefined' ? 10 : $("#hotlSrvsTypDetDsplySze").val();
    var sortBy = typeof $("#hotlSrvsTypDetSortBy").val() === 'undefined' ? '' : $("#hotlSrvsTypDetSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    $("#hotlSrvsTypDetPageNo").val(pageNo);
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncHotlSrvsTypDet(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getHotlSrvsTypDet(actionText, slctr, linkArgs);
    }
}

function getOneHotlSrvsTypForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'hotlSrvsTypDetailInfo';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = '';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = '';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = '';
    }
    var lnkArgs = 'grp=18&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdSrvsTypID=' + tmpltID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID;
    //doAjaxWthCallBck(linkArgs, elementID, actionAfter, titleMsg, titleElementID, modalBodyID, rqstdCallBack)
    //doAjaxWthCallBck(lnkArgs, 'myFormsModalNm', 'ShowDialog', "Check-In(s)/Reservation(s) for (" + ln_RoomNm + ")", 'myFormsModalNmTitle', 'myFormsModalNmBody', function () {
    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        if (!$.fn.DataTable.isDataTable('#oneHotlSrvsTypSmryLinesTable')) {
            var table2 = $('#oneHotlSrvsTypSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneHotlSrvsTypSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneHotlSrvsTypPricesTable')) {
            var table2 = $('#oneHotlSrvsTypPricesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneHotlSrvsTypPricesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#hotlSrvsTypForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        if (destElmntID !== 'hotlSrvsTypDetailInfo') {
            $('#myFormsModalNm').off('hidden.bs.modal');
            $('#myFormsModalNm').one('hidden.bs.modal', function (e) {
                getHotlSmryDshBrd('', '#allmodules', 'grp=18&typ=1&pg=1&vtyp=0');
                $(e.currentTarget).unbind();
            });
        }
        $('[data-toggle="tabajxfcltytyp"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=18&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');

            if (targ.indexOf('hotlSrvsTypExtraInfo') >= 0) {
                $('#addNwScmHotlSrvsTypSmryBtn').addClass('hideNotice');
                $('#addNwScmHotlSrvsTypPriceBtn').removeClass('hideNotice');
                $('.fcltyTypDetNav').addClass('hideNotice');
            } else {
                $('#addNwScmHotlSrvsTypPriceBtn').addClass('hideNotice');
                $('#addNwScmHotlSrvsTypSmryBtn').removeClass('hideNotice');
                $('.fcltyTypDetNav').removeClass('hideNotice');
            }
        });
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
    });
}

function saveHotlSrvsTypForm(actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'hotlSrvsTypDetailInfo';
    }
    var hotlSrvsTypID = typeof $("#hotlSrvsTypID").val() === 'undefined' ? '-1' : $("#hotlSrvsTypID").val();
    var hotlSrvsTypName = typeof $("#hotlSrvsTypName").val() === 'undefined' ? '' : $("#hotlSrvsTypName").val();
    var hotlSrvsTypDesc = typeof $("#hotlSrvsTypDesc").val() === 'undefined' ? '' : $("#hotlSrvsTypDesc").val();
    var hotlSrvsTypType = typeof $("#hotlSrvsTypType").val() === 'undefined' ? '' : $("#hotlSrvsTypType").val();
    var hotlSrvsTypLnkdSalesItmID = typeof $("#hotlSrvsTypLnkdSalesItmID").val() === 'undefined' ? '-1' : $("#hotlSrvsTypLnkdSalesItmID").val();
    var hotlSrvsTypLnkdPnltyItmID = typeof $("#hotlSrvsTypLnkdPnltyItmID").val() === 'undefined' ? '-1' : $("#hotlSrvsTypLnkdPnltyItmID").val();
    var hotlSrvsTypRqrPnlty = typeof $("#hotlSrvsTypRqrPnlty").val() === 'undefined' ? '0' : $("#hotlSrvsTypRqrPnlty").val();
    var hotlSrvsTypPnltyPrd = typeof $("#hotlSrvsTypPnltyPrd").val() === 'undefined' ? '0' : $("#hotlSrvsTypPnltyPrd").val();

    var hotlSrvsTypIsEnbld = typeof $("input[name='hotlSrvsTypIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='hotlSrvsTypIsEnbld']:checked").val();
    var hotlSrvsTypMltplyAdlts = typeof $("input[name='hotlSrvsTypMltplyAdlts']:checked").val() === 'undefined' ? 'NO' : $("input[name='hotlSrvsTypMltplyAdlts']:checked").val();
    var hotlSrvsTypMltplyChldrn = typeof $("input[name='hotlSrvsTypMltplyChldrn']:checked").val() === 'undefined' ? 'NO' : $("input[name='hotlSrvsTypMltplyChldrn']:checked").val();
    var errMsg = "";
    if (hotlSrvsTypName.trim() === '' || hotlSrvsTypType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Facility Type Name and Type cannot be empty!</span></p>';
    }
    if (Number(hotlSrvsTypLnkdSalesItmID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Sales Item cannot be empty!</span></p>';
    }
    if (Number(hotlSrvsTypLnkdSalesItmID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Sales Item cannot be empty!</span></p>';
    }
    if (Number(hotlSrvsTypRqrPnlty.replace(/[^-?0-9\.]/g, '')) > 0) {
        if (Number(hotlSrvsTypLnkdPnltyItmID.replace(/[^-?0-9\.]/g, '')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Linked Penalty Item cannot be empty if Penalty is Required!</span></p>';
        }
    }
    var isVld = true;
    var slctdSrvsTypDetIDs = "";
    var slctdSrvsTypPriceIDs = "";
    $('#oneHotlSrvsTypSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_FcltyLnID = typeof $('#oneHotlSrvsTypSmryRow' + rndmNum + '_FcltyLnID').val() === 'undefined' ? '-1' : $('#oneHotlSrvsTypSmryRow' + rndmNum + '_FcltyLnID').val();
                var ln_AssetItmID = typeof $('#oneHotlSrvsTypSmryRow' + rndmNum + '_AssetItmID').val() === 'undefined' ? '-1' : $('#oneHotlSrvsTypSmryRow' + rndmNum + '_AssetItmID').val();
                var ln_LineName = typeof $('#oneHotlSrvsTypSmryRow' + rndmNum + '_LineName').val() === 'undefined' ? '' : $('#oneHotlSrvsTypSmryRow' + rndmNum + '_LineName').val();
                var ln_LineDesc = typeof $('#oneHotlSrvsTypSmryRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#oneHotlSrvsTypSmryRow' + rndmNum + '_LineDesc').val();
                if (ln_LineDesc.trim() === '') {
                    $('#oneHotlSrvsTypSmryRow' + rndmNum + '_LineDesc').val(ln_LineName);
                    ln_LineDesc = ln_LineName;
                }
                var ln_IsEnabled = typeof $("input[name='oneHotlSrvsTypSmryRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'NO' : 'YES';
                var ln_MaxRntOuts = typeof $('#oneHotlSrvsTypSmryRow' + rndmNum + '_MaxRntOuts').val() === 'undefined' ? '0' : $('#oneHotlSrvsTypSmryRow' + rndmNum + '_MaxRntOuts').val();
                var ln_NeedsClng = typeof $("input[name='oneHotlSrvsTypSmryRow" + rndmNum + "_NeedsClng']:checked").val() === 'undefined' ? 'NO' : 'YES';

                if (ln_LineName.trim() !== '') {
                    if (ln_LineDesc.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Facility Name and Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneHotlSrvsTypSmryRow' + rndmNum + '_LineDesc').addClass('rho-error');
                    } else {
                        $('#oneHotlSrvsTypSmryRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdSrvsTypDetIDs = slctdSrvsTypDetIDs +
                            ln_FcltyLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_AssetItmID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_IsEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_MaxRntOuts.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_NeedsClng.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
                }
            }
        }
    });
    $('#oneHotlSrvsTypPricesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#oneHotlSrvsTypPricesRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#oneHotlSrvsTypPricesRow' + rndmNum + '_TrnsLnID').val();
                var ln_StrtDte = typeof $('#oneHotlSrvsTypPricesRow' + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#oneHotlSrvsTypPricesRow' + rndmNum + '_StrtDte').val();
                var ln_EndDte = typeof $('#oneHotlSrvsTypPricesRow' + rndmNum + '_EndDte').val() === 'undefined' ? '' : $('#oneHotlSrvsTypPricesRow' + rndmNum + '_EndDte').val();
                var ln_PrcLsTx = typeof $('#oneHotlSrvsTypPricesRow' + rndmNum + '_PrcLsTx').val() === 'undefined' ? '0.00' : $('#oneHotlSrvsTypPricesRow' + rndmNum + '_PrcLsTx').val();
                var ln_IsEnbld = typeof $("input[name='oneHotlSrvsTypPricesRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';

                if (ln_StrtDte.trim() !== '') {
                    if (ln_EndDte.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Start Date and End Date for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneHotlSrvsTypPricesRow' + rndmNum + '_EndDte').addClass('rho-error');
                    } else {
                        $('#oneHotlSrvsTypPricesRow' + rndmNum + '_EndDte').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdSrvsTypPriceIDs = slctdSrvsTypPriceIDs +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StrtDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_EndDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PrcLsTx.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_IsEnbld.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
        title: 'Save Facility Type',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Facility Type...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 18);
    formData.append('typ', 1);
    formData.append('pg', 3);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('hotlSrvsTypID', hotlSrvsTypID);
    formData.append('hotlSrvsTypName', hotlSrvsTypName);
    formData.append('hotlSrvsTypDesc', hotlSrvsTypDesc);
    formData.append('hotlSrvsTypType', hotlSrvsTypType);
    formData.append('hotlSrvsTypLnkdSalesItmID', hotlSrvsTypLnkdSalesItmID);
    formData.append('hotlSrvsTypLnkdPnltyItmID', hotlSrvsTypLnkdPnltyItmID);
    formData.append('hotlSrvsTypRqrPnlty', hotlSrvsTypRqrPnlty);

    formData.append('hotlSrvsTypPnltyPrd', hotlSrvsTypPnltyPrd);
    formData.append('hotlSrvsTypIsEnbld', hotlSrvsTypIsEnbld);
    formData.append('hotlSrvsTypMltplyAdlts', hotlSrvsTypMltplyAdlts);
    formData.append('hotlSrvsTypMltplyChldrn', hotlSrvsTypMltplyChldrn);

    formData.append('slctdSrvsTypDetIDs', slctdSrvsTypDetIDs);
    formData.append('slctdSrvsTypPriceIDs', slctdSrvsTypPriceIDs);

    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                            hotlSrvsTypID = data.hotlSrvsTypID;
                            if (destElmntID === 'hotlSrvsTypDetailInfo') {
                                getHotlSrvsTyp('', '#allmodules', 'grp=18&typ=1&pg=3&vtyp=0');
                            } else {
                                getOneHotlSrvsTypForm(hotlSrvsTypID, 1, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID);
                            }
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function insertNewHotlSrvsTypRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
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
    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
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

function delHotlSrvsTyp(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SrvsTypID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SrvsTypID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SrvsTypNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Facility Type?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Facility Type?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Facility Type?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Facility Type...Please Wait...</p>',
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
                                    grp: 18,
                                    typ: 1,
                                    pg: 3,
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

function delHotlSrvsTypLne(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_FcltyLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_FcltyLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_LineName').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Facility?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Facility?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Facility?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Facility...Please Wait...</p>',
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
                                    grp: 18,
                                    typ: 1,
                                    pg: 3,
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

function delHotlSrvsTypPrice(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_StrtDte').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Price?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Price?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Price?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Price...Please Wait...</p>',
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
                                    grp: 18,
                                    typ: 1,
                                    pg: 3,
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

function getHotlComplnts(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#hotlComplntsSrchFor").val() === 'undefined' ? '%' : $("#hotlComplntsSrchFor").val();
    var srchIn = typeof $("#hotlComplntsSrchIn").val() === 'undefined' ? 'Both' : $("#hotlComplntsSrchIn").val();
    var pageNo = typeof $("#hotlComplntsPageNo").val() === 'undefined' ? 1 : $("#hotlComplntsPageNo").val();
    var limitSze = typeof $("#hotlComplntsDsplySze").val() === 'undefined' ? 10 : $("#hotlComplntsDsplySze").val();
    var sortBy = typeof $("#hotlComplntsSortBy").val() === 'undefined' ? '' : $("#hotlComplntsSortBy").val();
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
    openATab(slctr, linkArgs);
}

function enterKeyFuncHotlComplnts(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getHotlComplnts(actionText, slctr, linkArgs);
    }
}

function saveHotlComplntsForm() {
    var errMsg = "";
    var isVld = true;
    var slctdComplaintIDs = "";
    $('#hotlComplntsHdrsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#hotlComplntsHdrsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#hotlComplntsHdrsRow' + rndmNum + '_TrnsLnID').val();
                var ln_CstmrID = typeof $('#hotlComplntsHdrsRow' + rndmNum + '_CstmrID').val() === 'undefined' ? '-1' : $('#hotlComplntsHdrsRow' + rndmNum + '_CstmrID').val();
                var ln_PrsnID = typeof $('#hotlComplntsHdrsRow' + rndmNum + '_PrsnID').val() === 'undefined' ? '-1' : $('#hotlComplntsHdrsRow' + rndmNum + '_PrsnID').val();
                var ln_CmplntTyp = typeof $('#hotlComplntsHdrsRow' + rndmNum + '_CmplntTyp').val() === 'undefined' ? '' : $('#hotlComplntsHdrsRow' + rndmNum + '_CmplntTyp').val();
                var ln_LineDesc = typeof $('#hotlComplntsHdrsRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#hotlComplntsHdrsRow' + rndmNum + '_LineDesc').val();
                var ln_CmplnSoltn = typeof $('#hotlComplntsHdrsRow' + rndmNum + '_CmplnSoltn').val() === 'undefined' ? '' : $('#hotlComplntsHdrsRow' + rndmNum + '_CmplnSoltn').val();
                var ln_IsRslvd = typeof $("input[name='hotlComplntsHdrsRow" + rndmNum + "_IsRslvd']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (ln_LineDesc.trim() !== '') {
                    if (ln_CmplntTyp.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Complaint Type and Description for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#hotlComplntsHdrsRow' + rndmNum + '_CmplntTyp').addClass('rho-error');
                    } else {
                        $('#hotlComplntsHdrsRow' + rndmNum + '_CmplntTyp').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdComplaintIDs = slctdComplaintIDs +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CstmrID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PrsnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CmplntTyp.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CmplnSoltn.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_IsRslvd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                    }
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
        title: 'Save Complaints',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Complaints...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 18);
    formData.append('typ', 1);
    formData.append('pg', 6);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('slctdComplaintIDs', slctdComplaintIDs);

    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                            getHotlComplnts('', '#allmodules', 'grp=18&typ=1&pg=6&vtyp=0');
                        }
                    }, 500);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function insertNewHotlComplntsRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
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
    var cntr = 0;
    $('#' + tableElmntID).find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
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

function delHotlComplnts(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CmplntTyp').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete Complaint/Observation?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Complaint/Observation?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Complaint/Observation?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Complaint/Observation...Please Wait...</p>',
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
                                    grp: 18,
                                    typ: 1,
                                    pg: 6,
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

function printEmailFullInvc(pKeyID) {
    var dialog = bootbox.alert({
        title: 'GET PDF',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Getting PDF...Please Wait...</p>',
        callback: function () {}
    });
    var formData = new FormData();
    formData.append('grp', 12);
    formData.append('typ', 1);
    formData.append('pg', 1);
    formData.append('q', 'VIEW');
    formData.append('vtyp', 701);
    formData.append('pKeyID', pKeyID);
    var dwnldURL = "";
    var mailTo = "";
    var mailCc = "";
    var mailSubject = "";
    var bulkMessageBody = "";
    var mailAttchmnts = "";
    dialog.init(function () {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
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
                            dialog.modal('hide');

                            var dsplyForm = '<div id="allRhoPDFDocDisplays"></div>';

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
                                    dwnldURL = urldecode(data.URL);
                                    mailTo = data.mailTo;
                                    mailCc = data.mailCc;
                                    mailSubject = data.mailSubject;
                                    bulkMessageBody = data.bulkMessageBody;
                                    mailAttchmnts = dwnldURL + ";";
                                    var options = {
                                        height: "550px"
                                        /*,
                                                                                 page: '2',
                                                                                 pdfOpenParams: {
                                                                                 view: 'FitV',
                                                                                 pagemode: 'thumbs',
                                                                                 search: 'lorem ipsum'
                                                                                 }*/
                                    };
                                    PDFObject.embed(dwnldURL, "#allRhoPDFDocDisplays", options);
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
                                        label: 'RELOAD PDF',
                                        icon: 'glyphicon glyphicon glyphicon-refresh',
                                        cssClass: 'btn-default',
                                        action: function (dialogItself) {
                                            popDialogItself = dialogItself;

                                            var options = {
                                                height: "550px"
                                                /*,
                                                                                                 page: '2',
                                                                                                 pdfOpenParams: {
                                                                                                 view: 'FitV',
                                                                                                 pagemode: 'thumbs',
                                                                                                 search: 'lorem ipsum'
                                                                                                 }*/
                                            };
                                            PDFObject.embed(dwnldURL, "#allRhoPDFDocDisplays", options);
                                        }
                                    }
                                    /*, {
                                                                         id: 'popOKBtnHtml',
                                                                         label: 'VIEW HTML',
                                                                         icon: 'glyphicon glyphicon-list-alt',
                                                                         cssClass: 'btn-primary',
                                                                         action: function (dialogItself) {
                                                                         popDialogItself = dialogItself;
                                                                         window.open(dwnldURL.replace(/(.pdf)/gi, ".html"), '_blank');
                                                                         }
                                                                         }*/
                                    , {
                                        id: 'popOKBtnEmail',
                                        label: 'SEND MAIL',
                                        icon: 'glyphicon glyphicon-envelope',
                                        cssClass: 'btn-primary',
                                        action: function (dialogItself) {
                                            popDialogItself = dialogItself;
                                            sendGeneralMessage1('Email', mailTo, mailCc, mailSubject, bulkMessageBody, mailAttchmnts);
                                            /*window.open(dwnldURL.replace(/(.pdf)/gi, ".html"), '_blank');*/
                                            dialogItself.setClosable(true);
                                            dialogItself.close();
                                        }
                                    }
                                ]
                            });
                        }
                    }, 50);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}


var prgstimerid2;
var exprtBtn;
var exprtBtn2;

function exprtInvntryItems() {
    var exprtMsg = '<form role="form">' +
        '<p style="color:#000;">' +
        'How many Accounts will you like to Export?' +
        '<br/>1=No Accounts(Empty Template)' +
        '<br/>2=All Accounts' +
        '<br/>3-Infinity=Specify the exact number of Accounts to Export<br/>' +
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
        title: 'Export Inventory Items!',
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {},
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton('btn_exprt_rpt');
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
                        title: 'Exporting Inventory Items',
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
                                grp: 12,
                                typ: 1,
                                pg: 3,
                                q: 'UPDATE',
                                actyp: 3,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshInvntryItemsPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshInvntryItemsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 12,
            typ: 1,
            pg: 3,
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

function importInvntryItems() {
    var dataToSend = "";
    var isFileValid = true;
    var errMsg1 = '';
    var dialog1 = bootbox.confirm({
        title: 'Import Inventory Items?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT INVENTORY ITEMS</span> to overwrite existing ones?<br/><span style="color:red;font-weight:bold;font-style:italic;">NB: Specified Template Values may be used to Overwrite that of Existing Items!</span><br/>Action cannot be Undone!</p>',
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
                                        $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing Inventory Items...Please Wait...</span>');
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var itemName = "";
                                            var itemDesc = "";
                                            var enabled = "";
                                            var itemTemplate = "";
                                            var itemType = "";
                                            var itemCategory = "";
                                            var baseUOM = "";
                                            var taxCode = "";
                                            var discountCode = "";
                                            var chargeCode = "";
                                            var itemCrncy = "";
                                            var itmSllngPrice = "";
                                            var itmQty = "";
                                            var itmUnitCost = "";
                                            var itmStores = "";
                                            var itmUOMs = "";
                                            var itmExtraInfo = "";
                                            var itmOtherDesc = "";
                                            var itmGenericNm = "";
                                            var itmTradeNm = "";
                                            var itmUsualDosage = "";
                                            var itmMaxDosage = "";
                                            var itmContraIndic = "";
                                            var itmFoodInter = "";
                                            var itmDrugIntera = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            itemName = data[row][item];
                                                            break;
                                                        case 2:
                                                            itemDesc = data[row][item];
                                                            break;
                                                        case 3:
                                                            enabled = data[row][item];
                                                            break;
                                                        case 4:
                                                            itemTemplate = data[row][item];
                                                            break;
                                                        case 5:
                                                            itemType = data[row][item];
                                                            break;
                                                        case 6:
                                                            itemCategory = data[row][item];
                                                            break;
                                                        case 7:
                                                            baseUOM = data[row][item];
                                                            break;
                                                        case 8:
                                                            taxCode = data[row][item];
                                                            break;
                                                        case 9:
                                                            discountCode = data[row][item];
                                                            break;
                                                        case 10:
                                                            chargeCode = data[row][item];
                                                            break;
                                                        case 11:
                                                            itemCrncy = data[row][item];
                                                            break;
                                                        case 12:
                                                            itmSllngPrice = data[row][item];
                                                            break;
                                                        case 13:
                                                            itmQty = data[row][item];
                                                            break;
                                                        case 14:
                                                            itmUnitCost = data[row][item];
                                                            break;
                                                        case 15:
                                                            itmStores = data[row][item];
                                                            break;
                                                        case 16:
                                                            itmUOMs = data[row][item];
                                                            break;
                                                        case 17:
                                                            itmExtraInfo = data[row][item];
                                                            break;
                                                        case 18:
                                                            itmOtherDesc = data[row][item];
                                                            break;
                                                        case 19:
                                                            itmGenericNm = data[row][item];
                                                            break;
                                                        case 20:
                                                            itmTradeNm = data[row][item];
                                                            break;
                                                        case 21:
                                                            itmUsualDosage = data[row][item];
                                                            break;
                                                        case 22:
                                                            itmMaxDosage = data[row][item];
                                                            break;
                                                        case 23:
                                                            itmContraIndic = data[row][item];
                                                            break;
                                                        case 24:
                                                            itmFoodInter = data[row][item];
                                                            break;
                                                        case 25:
                                                            itmDrugIntera = data[row][item];
                                                            break;
                                                        default:
                                                            isFileValid = false;
                                                            reader.abort();
                                                            errMsg1 = 'An error occurred reading this file.Invalid Column in File! Remove any Commas in the actual data/fields!';
                                                            var dialog = bootbox.alert({
                                                                title: 'Error-Validating Selected File',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File! Remove any Commas in the actual data/fields!</span>',
                                                                callback: function () {}
                                                            });
                                                    };
                                                }
                                                if (rwCntr === 0) {
                                                    if (itemName.toUpperCase() === "Item Name**".toUpperCase() &&
                                                        itemDesc.toUpperCase() === "Item Description**".toUpperCase() &&
                                                        itemTemplate.toUpperCase() === "Item Template (For Accounts)**".toUpperCase() &&
                                                        itmDrugIntera.toUpperCase() === "Drug Interactions(Drug Code~Interaction Effect~Action|)".toUpperCase()) {
                                                        //alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());
                                                    } else {
                                                        isFileValid = false;
                                                        errMsg1 = 'Invalid File Selected!';
                                                        reader.abort();
                                                        var dialog = bootbox.alert({
                                                            title: 'Error-Import Inventory Items',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {}
                                                        });
                                                    }
                                                }
                                                if (itemName.trim() !== "" && itemDesc.trim() !== "" &&
                                                    itemCategory.trim() !== "" &&
                                                    baseUOM.trim() !== "") {
                                                    if (itemTemplate.trim() === "") {
                                                        isFileValid = false;
                                                        errMsg1 = 'Every Item must have an Item Template!';
                                                        reader.abort();
                                                        var dialog = bootbox.alert({
                                                            title: 'Error-Import Inventory Items',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Every Item must have an Item Template!</span>',
                                                            callback: function () {}
                                                        });
                                                    }
                                                    //alert('isFileValid11:'+isFileValid);
                                                    if (isFileValid === true) {
                                                        dataToSend = dataToSend + itemName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itemDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            enabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itemTemplate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itemType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itemCategory.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            baseUOM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            taxCode.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            discountCode.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            chargeCode.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itemCrncy.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmSllngPrice.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmQty.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmUnitCost.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmStores.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmUOMs.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmExtraInfo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmOtherDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmGenericNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmTradeNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmUsualDosage.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmMaxDosage.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmContraIndic.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmFoodInter.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                                                            itmDrugIntera.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
                                                title: 'Error-Import Inventory Items',
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
                                        saveInvItemsExcl(dataToSend);
                                    } else {
                                        errMsg1 = 'Invalid File Selected!';
                                        var dialog = bootbox.alert({
                                            title: 'Error-Import Inventory Items',
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

function saveInvItemsExcl(dataToSend) {
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
        title: 'Importing Inventory Items',
        size: 'small',
        message: '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Inventory Items...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            getAllINVItms('clear', '#allmodules', 'grp=12&typ=1&pg=3&vtyp=0');
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
                    grp: 12,
                    typ: 1,
                    pg: 3,
                    q: 'UPDATE',
                    actyp: 102,
                    dataToSend: dataToSend
                }
            });
            prgstimerid2 = window.setInterval(rfrshSaveInvItems, 1000);
        });
    });
}

function rfrshSaveInvItems() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 12,
            typ: 1,
            pg: 3,
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



/*CONSUMER CREDIT ASSESSMENT*/
function getOneItmPymntPlansForm(pKeyID, vwtype, actionTxt, sbmtdItmPymntPlansITEMID, itmCodeDesc) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof sbmtdItmPymntPlansITEMID === 'undefined' || sbmtdItmPymntPlansITEMID === null) {
        sbmtdItmPymntPlansITEMID = -1;
    }
    //var sbmtdItmPymntPlansPOID = typeof $('#sbmtdItmPymntPlansPOID').val() === 'undefined' ? '-1' : $('#sbmtdItmPymntPlansPOID').val();
    var lnkArgs = 'grp=12&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdItmPymntPlansID=' + pKeyID + '&sbmtdItmPymntPlansITEMID=' + sbmtdItmPymntPlansITEMID + '&sbmtdItmDesc=' + itmCodeDesc;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Item Payment Plans (ITEM: ' + itmCodeDesc + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneItmPymntPlansForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneItmPymntPlansTable')) {
            var table1 = $('#oneItmPymntPlansTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneItmPymntPlansTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();



    });
}

function getOneCnsmrCrdtAnalysisForm(pKeyID, vwtype, actionTxt, callBackFunc) {

    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }

    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof pKeyID === 'undefined' || pKeyID === null) {
        pKeyID = -1;
    }

    var lnkArgs = 'grp=12&typ=1&pg=15&vtyp=' + vwtype + '&PKeyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Credit Analysis (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneCnsmrCrdtAnalysisForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getAllCnsmrCrdtAnalysis('', '#allmodules', 'grp=12&typ=1&pg=15&vtyp=0');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneCnsmrCrdtAnalysisTable')) {
            var table1 = $('#oneCnsmrCrdtAnalysisTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneCnsmrCrdtAnalysisTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(document).ready(function () {
            callBackFunc();
        });

    });
}

function saveCnsmrCrdtAnalysis(optn) {

    var optnMsg = 'Saving';
    if (optn == '1') {
        optnMsg = 'Finalizing';
    }
    //alert(optnMsg+'-'+optn);

    var box;
    var box2;
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {

        var obj;

        var cnsmrCreditID = typeof $("#cnsmrCreditID").val() === 'undefined' ? -1 : $("#cnsmrCreditID").val();
        var transactionNo = typeof $("#transactionNo").val() === 'undefined' ? "" : $("#transactionNo").val();
        var custSupId = typeof $("#custSupId").val() === 'undefined' ? -1 : $("#custSupId").val();
        var salaryIncome = typeof $("#salaryIncome").val() === 'undefined' ? 0.00 : $("#salaryIncome").val();
        var fuelAllowance = typeof $("#fuelAllowance").val() === 'undefined' ? 0.00 : $("#fuelAllowance").val();
        var rentAllowance = typeof $("#rentAllowance").val() === 'undefined' ? 0.00 : $("#rentAllowance").val();
        var clothingAllowance = typeof $("#clothingAllowance").val() === 'undefined' ? 0.00 : $("#clothingAllowance").val();
        var otherAllowances = typeof $("#otherAllowances").val() === 'undefined' ? 0.00 : $("#otherAllowances").val();
        var loanDeductions = typeof $("#loanDeductions").val() === 'undefined' ? 0.00 : $("#loanDeductions").val();
        var trnsDate = typeof $("#trnsDate").val() === 'undefined' ? '' : $("#trnsDate").val();
        var pymntOption = typeof $("#pymntOption").val() === 'undefined' ? '' : $("#pymntOption").val();
        var guarantorName = typeof $("#guarantorName").val() === 'undefined' ? '' : $("#guarantorName").val();
        var guarantorContactNos = typeof $("#guarantorContactNos").val() === 'undefined' ? '' : $("#guarantorContactNos").val();
        var guarantorOccupation = typeof $("#guarantorOccupation").val() === 'undefined' ? '' : $("#guarantorOccupation").val();
        var guarantorPlaceOfWork = typeof $("#guarantorPlaceOfWork").val() === 'undefined' ? '' : $("#guarantorPlaceOfWork").val();
        var periodAtWorkplace = typeof $("#periodAtWorkplace").val() === 'undefined' ? 0 : $("#periodAtWorkplace").val();
        var periodUomAtWorkplace = typeof $("#periodUomAtWorkplace").val() === 'undefined' ? 'Year(s)' : $("#periodUomAtWorkplace").val();
        var guarantorEmail = typeof $("#guarantorEmail").val() === 'undefined' ? '' : $("#guarantorEmail").val();
        var noOfPymnts = typeof $("#noOfPymnts").val() === 'undefined' ? 0 : $("#noOfPymnts").val();
        var ttlInitialDeposit = typeof $("#ttlInitialDeposit").val() === 'undefined' ? 0.00 : $("#ttlInitialDeposit").val();
        var mnthlyRpymnts = typeof $("#mnthlyRpymnts").val() === 'undefined' ? 0.00 : $("#mnthlyRpymnts").val();
        var initDpstType = typeof $("#initDpstType").val() === 'undefined' ? '' : $("#initDpstType").val();
        var marketerPersonId = typeof $("#marketerPersonId").val() === 'undefined' ? -1 : $("#marketerPersonId").val();
        var salesStoreNmID = typeof $("#salesStoreNmID").val() === 'undefined' ? -1 : $("#salesStoreNmID").val();

        if (noOfPymnts == "" || noOfPymnts <= 0) {
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Please enter a valid <i>Tenor</i>!</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (pymntOption == "") {
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Please select a <i>Payment Option</i>!</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if ((salesStoreNmID == "-1" || salesStoreNmID < 0) && optn == '1') {
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Please select a <i>Sales Store</i>!</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        }


        /*var rslt = validateCnsmrCrdtAnalysis(receiptDate, bankCodeID, bookTypeID);
         if (!rslt) {
         return false;
         }*/

        $body.removeClass("mdlloading");
        $body.removeClass("mdlloadingDiag");
        box = bootbox.dialog({
            size: "small",
            message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i><span style="font-weight:bold; color:green;">' + optnMsg + '. Please Wait...</span></div>'
        });
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
        formData.append('actyp', 1);
        formData.append('cnsmrCreditID', cnsmrCreditID);
        formData.append('transactionNo', transactionNo);
        formData.append('custSupId', custSupId);
        formData.append('salaryIncome', salaryIncome);
        formData.append('fuelAllowance', fuelAllowance);
        formData.append('rentAllowance', rentAllowance);
        formData.append('clothingAllowance', clothingAllowance);
        formData.append('otherAllowances', otherAllowances)
        formData.append('loanDeductions', loanDeductions);
        formData.append('trnsDate', trnsDate);
        formData.append('pymntOption', pymntOption);
        formData.append('guarantorName', guarantorName);
        formData.append('guarantorContactNos', guarantorContactNos);
        formData.append('guarantorOccupation', guarantorOccupation);
        formData.append('guarantorPlaceOfWork', guarantorPlaceOfWork);
        formData.append('periodAtWorkplace', periodAtWorkplace);
        formData.append('periodUomAtWorkplace', periodUomAtWorkplace);
        formData.append('guarantorEmail', guarantorEmail);
        formData.append('noOfPymnts', noOfPymnts);
        formData.append('ttlInitialDeposit', ttlInitialDeposit);
        formData.append('mnthlyRpymnts', mnthlyRpymnts);
        formData.append('initDpstType', 'Automatic'); // initDpstType);
        formData.append('marketerPersonId', marketerPersonId);
        formData.append('optn', optn);
        formData.append('salesStoreNmID', salesStoreNmID);

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                var msg = "";
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                    obj = $.parseJSON(data);

                    box.modal('hide');
                    //$('#myFormsModalLg').modal('hide');
                    getOneCnsmrCrdtAnalysisForm(obj.cnsmrCreditID, 1, 'ReloadDialog', function () {
                        msg = "Successfully!";
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: msg,
                            callback: function () {
                                /* your callback code */
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
                        callback: function () {
                            /* your callback code */
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

function saveCnsmrCrdtAnalysis_bkp() {

    var box;
    var box2;
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {

        var obj;

        var cnsmrCreditID = typeof $("#cnsmrCreditID").val() === 'undefined' ? -1 : $("#cnsmrCreditID").val();
        var transactionNo = typeof $("#transactionNo").val() === 'undefined' ? "" : $("#transactionNo").val();
        var custSupId = typeof $("#custSupId").val() === 'undefined' ? -1 : $("#custSupId").val();
        var salaryIncome = typeof $("#salaryIncome").val() === 'undefined' ? 0.00 : $("#salaryIncome").val();
        var fuelAllowance = typeof $("#fuelAllowance").val() === 'undefined' ? 0.00 : $("#fuelAllowance").val();
        var rentAllowance = typeof $("#rentAllowance").val() === 'undefined' ? 0.00 : $("#rentAllowance").val();
        var clothingAllowance = typeof $("#clothingAllowance").val() === 'undefined' ? 0.00 : $("#clothingAllowance").val();
        var otherAllowances = typeof $("#otherAllowances").val() === 'undefined' ? 0.00 : $("#otherAllowances").val();
        var loanDeductions = typeof $("#loanDeductions").val() === 'undefined' ? 0.00 : $("#loanDeductions").val();
        var trnsDate = typeof $("#trnsDate").val() === 'undefined' ? '' : $("#trnsDate").val();
        var pymntOption = typeof $("#pymntOption").val() === 'undefined' ? '' : $("#pymntOption").val();
        var guarantorName = typeof $("#guarantorName").val() === 'undefined' ? '' : $("#guarantorName").val();
        var guarantorContactNos = typeof $("#guarantorContactNos").val() === 'undefined' ? '' : $("#guarantorContactNos").val();
        var guarantorOccupation = typeof $("#guarantorOccupation").val() === 'undefined' ? '' : $("#guarantorOccupation").val();
        var guarantorPlaceOfWork = typeof $("#guarantorPlaceOfWork").val() === 'undefined' ? '' : $("#guarantorPlaceOfWork").val();
        var periodAtWorkplace = typeof $("#periodAtWorkplace").val() === 'undefined' ? 0 : $("#periodAtWorkplace").val();
        var periodUomAtWorkplace = typeof $("#periodUomAtWorkplace").val() === 'undefined' ? 'Year(s)' : $("#periodUomAtWorkplace").val();
        var guarantorEmail = typeof $("#guarantorEmail").val() === 'undefined' ? '' : $("#guarantorEmail").val();
        var noOfPymnts = typeof $("#noOfPymnts").val() === 'undefined' ? 0 : $("#noOfPymnts").val();
        var ttlInitialDeposit = typeof $("#ttlInitialDeposit").val() === 'undefined' ? 0.00 : $("#ttlInitialDeposit").val();
        var mnthlyRpymnts = typeof $("#mnthlyRpymnts").val() === 'undefined' ? 0.00 : $("#mnthlyRpymnts").val();
        var initDpstType = typeof $("#initDpstType").val() === 'undefined' ? '' : $("#initDpstType").val();
        var marketerPersonId = typeof $("#marketerPersonId").val() === 'undefined' ? -1 : $("#marketerPersonId").val();

        if (noOfPymnts == "" || noOfPymnts <= 0) {
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Please enter a valid <i>Tenor</i>!</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (pymntOption == "") {
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Please select a <i>Payment Option</i>!</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        }



        /*var rslt = validateCnsmrCrdtAnalysis(receiptDate, bankCodeID, bookTypeID);
         if (!rslt) {
         return false;
         }*/

        $body.removeClass("mdlloading");
        $body.removeClass("mdlloadingDiag");
        box = bootbox.dialog({
            size: "small",
            message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i><span style="font-weight:bold; color:green;"> Saving. Please Wait...</span></div>'
        });
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
        formData.append('actyp', 1);
        formData.append('cnsmrCreditID', cnsmrCreditID);
        formData.append('transactionNo', transactionNo);
        formData.append('custSupId', custSupId);
        formData.append('salaryIncome', salaryIncome);
        formData.append('fuelAllowance', fuelAllowance);
        formData.append('rentAllowance', rentAllowance);
        formData.append('clothingAllowance', clothingAllowance);
        formData.append('otherAllowances', otherAllowances)
        formData.append('loanDeductions', loanDeductions);
        formData.append('trnsDate', trnsDate);
        formData.append('pymntOption', pymntOption);
        formData.append('guarantorName', guarantorName);
        formData.append('guarantorContactNos', guarantorContactNos);
        formData.append('guarantorOccupation', guarantorOccupation);
        formData.append('guarantorPlaceOfWork', guarantorPlaceOfWork);
        formData.append('periodAtWorkplace', periodAtWorkplace);
        formData.append('periodUomAtWorkplace', periodUomAtWorkplace);
        formData.append('guarantorEmail', guarantorEmail);
        formData.append('noOfPymnts', noOfPymnts);
        formData.append('ttlInitialDeposit', ttlInitialDeposit);
        formData.append('mnthlyRpymnts', mnthlyRpymnts);
        formData.append('initDpstType', 'Automatic'); // initDpstType);
        formData.append('marketerPersonId', marketerPersonId);

        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                var msg = "";
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                    obj = $.parseJSON(data);

                    box.modal('hide');
                    //$('#myFormsModalLg').modal('hide');
                    getOneCnsmrCrdtAnalysisForm(obj.cnsmrCreditID, 1, 'ReloadDialog', function () {
                        msg = "Saved Successfully!";
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: msg,
                            callback: function () {
                                /* your callback code */
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
                        callback: function () {
                            /* your callback code */
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

function getAllCnsmrCrdtAnalysis(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#allCnsmrCrdtAnalysisSrchFor").val() === 'undefined' ? '%' : $("#allCnsmrCrdtAnalysisSrchFor").val();
    var srchIn = typeof $("#allCnsmrCrdtAnalysisSrchIn").val() === 'undefined' ? 'Both' : $("#allCnsmrCrdtAnalysisSrchIn").val();
    var pageNo = typeof $("#allCnsmrCrdtAnalysisPageNo").val() === 'undefined' ? 1 : $("#allCnsmrCrdtAnalysisPageNo").val();
    var limitSze = typeof $("#allCnsmrCrdtAnalysisDsplySze").val() === 'undefined' ? 10 : $("#allCnsmrCrdtAnalysisDsplySze").val();
    var sortBy = typeof $("#allCnsmrCrdtAnalysisSortBy").val() === 'undefined' ? '' : $("#allCnsmrCrdtAnalysisSortBy").val();
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
    openATab(slctr, linkArgs);
}

function viewCreditItemsRecForm(pKeyID, formTitle) {
    var cnsmrCreditId = typeof $("#cnsmrCreditID").val() === 'undefined' ? -1 : $("#cnsmrCreditID").val();
    var noOfPymnt = typeof $("#noOfPymnts").val() === 'undefined' ? 1 : $("#noOfPymnts").val();

    if (cnsmrCreditId == "" || cnsmrCreditId == -1) {
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;font-weight:bold !important;'>Please save Header First!</span>",
            callback: function () {
                /* your callback code */
            }
        });
        return false;
    }

    var creditItmId = pKeyID;
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
            $('#myFormsModalyTitle').html(formTitle);
            $('#myFormsModalyBody').html(xmlhttp.responseText);
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
            $('#myFormsModaly').on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            $body.removeClass("mdlloadingDiag");
            $('#myFormsModaly').modal({
                backdrop: 'static',
                keyboard: false
            });
            //$body.removeClass("mdlloading");
            //$body = $("body");
            $(document).ready(function () {
                $('#creditItemsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=12&typ=1&pg=15&vtyp=2&creditItmId=" + creditItmId + "&cnsmrCreditId=" + cnsmrCreditId + "&noOfPymnt=" + noOfPymnt);
}

function saveCreditItem() {

    var box;
    var box2;

    $("#svCreditItm").attr('disabled', 'disabled');
    $("#svCreditItm").removeAttr('disabled');
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {

        var obj;
        var creditItmId = typeof $("#frmCreditItmId").val() === 'undefined' ? -1 : $("#frmCreditItmId").val();
        var cnsmrCreditId = typeof $("#cnsmrCreditID").val() === 'undefined' ? -1 : $("#cnsmrCreditID").val();;
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
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (vendorId == -1) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Select Vendor</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (itmPymntPlanId == -1) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Select Payment Plan</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (qty == "" || qty == 0) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter purchase quantity</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (itmPlanInitDeposit == "" || itmPlanInitDeposit < 0) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Initial Deposit or Zero(0)</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        }


        $body.removeClass("mdlloading");
        $body.removeClass("mdlloadingDiag");
        box = bootbox.dialog({
            size: "small",
            message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i><span style="font-weight:bold; color:green;"> Saving. Please Wait...</span></div>'
        });
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
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                    obj = $.parseJSON(data);
                    box.modal('hide');
                    $('#myFormsModaly').modal('hide');
                    getOneCnsmrCrdtAnalysisForm(cnsmrCreditId, 1, 'ReloadDialog', function () {
                        msg = "Saved Successfully!";
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: msg,
                            callback: function () {
                                /* your callback code */
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
                        callback: function () {
                            /* your callback code */
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

function getInvItmPaymentPlanDets() {
    getMsgAsync('grp=1&typ=11&q=Check Session', function () {
        $body = $("body");
        $body.addClass("mdlloadingDiag");
        var json;
        var itmPymntPlanId = typeof $("#frmItmPymntPlanId").val() === 'undefined' ? -1 : $("#frmItmPymntPlanId").val();
        var formData = new FormData();
        formData.append('grp', 12);
        formData.append('typ', 1);
        formData.append('pg', 15);
        formData.append('vtyp', 3);
        formData.append('itmPymntPlanId', itmPymntPlanId);
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                    json = $.parseJSON(data);
                    $("#frmUnitSellingPrice").val(json.itmPlanSllnPriceArry.itemSellingPrice);
                    $("#frmItmPlanInitDeposit").val(json.itmPlanSllnPriceArry.initialDeposit);
                } else {
                    alert(data + "hiii");
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}

function viewPostdatedChqRecForm(pKeyID, formTitle) {
    var cnsmrCreditId = typeof $("#cnsmrCreditID").val() === 'undefined' ? -1 : $("#cnsmrCreditID").val();

    if (cnsmrCreditId == "" || cnsmrCreditId == -1) {
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red;font-weight:bold !important;'>Please save Header First!</span>",
            callback: function () {
                /* your callback code */
            }
        });
        return false;
    }

    var postdatedChqId = pKeyID;
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
            $('#myFormsModalyTitle').html(formTitle);
            $('#myFormsModalyBody').html(xmlhttp.responseText);
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
            $('#myFormsModaly').on('show.bs.modal', function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            $body.removeClass("mdlloadingDiag");
            $('#myFormsModaly').modal({
                backdrop: 'static',
                keyboard: false
            });
            //$body.removeClass("mdlloading");
            //$body = $("body");
            $(document).ready(function () {
                $('#creditItemsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
            });
        }
    };
    xmlhttp.open("POST", "index.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("grp=12&typ=1&pg=15&vtyp=4&postdatedChqId=" + postdatedChqId + "&cnsmrCreditId=" + cnsmrCreditId);
}

function savePostdatedChq() {

    var box;
    var box2;

    $("#svPostdatedChq").attr('disabled', 'disabled');
    $("#svPostdatedChq").removeAttr('disabled');
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {

        var obj;
        var postdatedChqId = typeof $("#frmPostdatedChqId").val() === 'undefined' ? -1 : $("#frmPostdatedChqId").val();
        var cnsmrCreditId = typeof $("#cnsmrCreditID").val() === 'undefined' ? -1 : $("#cnsmrCreditID").val();;
        var chqNo = typeof $("#frmChqNo").val() === 'undefined' ? '' : $("#frmChqNo").val();
        var chqIssuerName = typeof $("#frmChqIssuerName").val() === 'undefined' ? '' : $("#frmChqIssuerName").val();
        var chqBank = typeof $("#frmChqBank").val() === 'undefined' ? '' : $("#frmChqBank").val();
        var amount = typeof $("#frmAmount").val() === 'undefined' ? 1 : $("#frmAmount").val();

        if (chqNo == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Provide Cheque Number</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (chqIssuerName == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Provide Cheque Isser's Name</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (chqBank == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Select Bank</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (amount == "" || amount == 0) {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Cheque Amount</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        }


        $body.removeClass("mdlloading");
        $body.removeClass("mdlloadingDiag");
        box = bootbox.dialog({
            size: "small",
            message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i><span style="font-weight:bold; color:green;"> Saving. Please Wait...</span></div>'
        });
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
        formData.append('actyp', 4);
        formData.append('postdatedChqId', postdatedChqId);
        formData.append('cnsmrCreditId', cnsmrCreditId);
        formData.append('chqNo', chqNo);
        formData.append('chqIssuerName', chqIssuerName);
        formData.append('chqBank', chqBank);
        formData.append('amount', amount);


        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: formData,
            async: true,
            success: function (data) {

                var msg = "";
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                    obj = $.parseJSON(data);
                    box.modal('hide');
                    $('#myFormsModaly').modal('hide');
                    getOneCnsmrCrdtAnalysisForm(cnsmrCreditId, 1, 'ReloadDialog', function () {
                        msg = "Saved Successfully!";
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: msg,
                            callback: function () {
                                /* your callback code */
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
                        callback: function () {
                            /* your callback code */
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

function deleteCreditItem(creditItmId, cnsmrCreditId, noOfPymnt) {
    var box;
    var box2;
    var mnBox;
    mnBox = bootbox.confirm({
        size: "small",
        message: "Are you sure you want to delete this Line?",
        callback: function (result) {
            /* your callback code */
            if (result) {

                box = bootbox.dialog({
                    size: "small",
                    message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Deleting. Please Wait...</div>'
                });
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
                if (window.XMLHttpRequest) {
                    /*code for IE7+, Firefox, Chrome, Opera, Safari*/
                    xmlhttp = new XMLHttpRequest();
                } else {
                    /*code for IE6, IE5*/
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                        var data = xmlhttp.responseText;
                        box.modal('hide');
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: data,
                            callback: function () {
                                /* your callback code */
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
                        getOneCnsmrCrdtAnalysisForm(cnsmrCreditId, 1, 'ReloadDialog');
                    }
                };
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("grp=12&typ=1&pg=15&actyp=2&q=DELETE&PKeyID=" + creditItmId + "&cnsmrCreditId=" + cnsmrCreditId + "&noOfPymnt=" + noOfPymnt);
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

function deletePostdatedChqId(postdatedChqId, cnsmrCreditId) {
    var box;
    var box2;
    var mnBox;
    mnBox = bootbox.confirm({
        size: "small",
        message: "Are you sure you want to delete this Line?",
        callback: function (result) {
            /* your callback code */
            if (result) {

                box = bootbox.dialog({
                    size: "small",
                    message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Deleting. Please Wait...</div>'
                });
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
                if (window.XMLHttpRequest) {
                    /*code for IE7+, Firefox, Chrome, Opera, Safari*/
                    xmlhttp = new XMLHttpRequest();
                } else {
                    /*code for IE6, IE5*/
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                        var data = xmlhttp.responseText;
                        box.modal('hide');
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: data,
                            callback: function () {
                                /* your callback code */
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
                        getOneCnsmrCrdtAnalysisForm(cnsmrCreditId, 1, 'ReloadDialog');
                    }
                };
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("grp=12&typ=1&pg=15&actyp=4&q=DELETE&PKeyID=" + postdatedChqId);
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

function saveItmPymntPlans() {

    var dsplyMsg = "";
    var sbmtdItmPymntPlansITEMID = typeof $("#sbmtdItmPymntPlansITEMID").val() === 'undefined' ? '-1' : $("#sbmtdItmPymntPlansITEMID").val();
    var sbmtdItmDesc = typeof $("#sbmtdItmDesc").val() === 'undefined' ? '' : $("#sbmtdItmDesc").val();

    var slctdItmPymntPlans = "";

    var errCount = 0;
    var rcdCount = 0;
    var lineCnta = 1;

    var ttlUnmtchdRows = 0;

    $('#allItmPymntPlansTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allItmPymntPlansRow' + rndmNum + '_PlanName').val() === 'undefined') {
                    /*Do Nothing*/
                } else {

                    if ($('#allItmPymntPlansRow' + rndmNum + '_PlanName').val() == "" || $('#allItmPymntPlansRow' + rndmNum + '_PlanName').val() == "") {
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanName').css('border-color', 'red');
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanName').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanName').css('border-color', '#ccc');
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanName').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansRow' + rndmNum + '_PlanPrice').val() == "" || $('#allItmPymntPlansRow' + rndmNum + '_PlanPrice').val() <= 0) {
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanPrice').css('border-color', 'red');
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanPrice').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanPrice').css('border-color', '#ccc');
                        $('#allItmPymntPlansRow' + rndmNum + '_PlanPrice').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansRow' + rndmNum + '_NoOfPymnts').val() == "" || parseFloat($('#allItmPymntPlansRow' + rndmNum + '_NoOfPymnts').val()) < 0) {
                        $('#allItmPymntPlansRow' + rndmNum + '_NoOfPymnts').css('border-color', 'red');
                        $('#allItmPymntPlansRow' + rndmNum + '_NoOfPymnts').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansRow' + rndmNum + '_NoOfPymnts').css('border-color', '#ccc');
                        $('#allItmPymntPlansRow' + rndmNum + '_NoOfPymnts').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansRow' + rndmNum + '_InitDpst').val() == "" || parseFloat($('#allItmPymntPlansRow' + rndmNum + '_InitDpst').val()) < 0) {
                        $('#allItmPymntPlansRow' + rndmNum + '_InitDpst').css('border-color', 'red');
                        $('#allItmPymntPlansRow' + rndmNum + '_InitDpst').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansRow' + rndmNum + '_InitDpst').css('border-color', '#ccc');
                        $('#allItmPymntPlansRow' + rndmNum + '_InitDpst').css('border-width', '1px');
                    }

                    if (errCount <= 0) {
                        slctdItmPymntPlans = slctdItmPymntPlans +
                            $('#allItmPymntPlansRow' + rndmNum + '_ItmPymntPlansID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansRow' + rndmNum + '_ItmID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansRow' + rndmNum + '_PlanName').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansRow' + rndmNum + '_PlanPrice').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansRow' + rndmNum + '_NoOfPymnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansRow' + rndmNum + '_InitDpst').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansRow' + rndmNum + '_IsEnbld').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted Payment Plan record(s)</i></b></span>",
            callback: function () {
                /* your callback code */
            }
        });
        return false;
    }

    var dsplyMsg = "Saving Payment Plan(s)...Please Wait...";
    var dsplyMsgTtle = "Save Payment Plan(s)?";
    var dsplyMsgRtrn = "Payment Plan(s) Saved";

    var dialog = bootbox.alert({
        title: dsplyMsgTtle,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> ' + dsplyMsg + '</p>',
        callback: function () {
            sbmtdItmPymntPlansITEMID = typeof $("#sbmtdItmPymntPlansITEMID").val() === 'undefined' ? '-1' : $("#sbmtdItmPymntPlansITEMID").val();
            var recCnt = typeof $("#recCnt").val() === 'undefined' ? 0 : $("#recCnt").val();

            if (parseInt(recCnt) > 0) {
                getOneItmPymntPlansForm(-1, 500, 'ReloadDialog', sbmtdItmPymntPlansITEMID, sbmtdItmDesc);
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
                    grp: 12,
                    typ: 1,
                    pg: 3,
                    q: 'UPDATE',
                    actyp: 500,
                    sbmtdItmPymntPlansITEMID: sbmtdItmPymntPlansITEMID,
                    slctdItmPymntPlans: slctdItmPymntPlans
                },
                success: function (result) {
                    var data = result;
                    setTimeout(function () {
                        //var sbmtdItmPymntPlansITEMID = typeof $("#sbmtdItmPymntPlansITEMID").val() === 'undefined' ? '-1' : $("#sbmtdItmPymntPlansITEMID").val();
                        if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                            var obj = $.parseJSON(data);
                            $("#sbmtdItmPymntPlansITEMID").val(obj.sbmtdItmPymntPlansITEMID);
                            $("#recCnt").val(parseInt(obj.recCntInst) + parseInt(obj.recCntUpdt));
                            var msg = "<span style='color:green;font-weight:bold !important;'>" + dsplyMsgRtrn + "</br><i>" + obj.recCntInst + " Fee record(s) inserted</br>" +
                                obj.recCntUpdt + " Fee record(s) updated</i></span>"
                            dialog.find('.bootbox-body').html(msg);
                        } else {
                            dialog.find('.bootbox-body').html(data);
                        }

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

function delCnsmrCrdtAnalysis(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var CustNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CnsmrCreditID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CnsmrCreditID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        CustNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Credit Analysis?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Credit Analysis?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Credit Analysis?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Credit Analysis...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 15,
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

function delCnsmrCrdtAnalysis(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var CustNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CnsmrCreditID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CnsmrCreditID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        CustNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Credit Analysis?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Credit Analysis?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Credit Analysis?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Credit Analysis...Please Wait...</p>',
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 15,
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

function reverseCnsmrCrdtAnalysis(cnsmrCreditId) {
    var box;
    var box2;
    var mnBox;
    mnBox = bootbox.confirm({
        size: "small",
        message: "Are you sure you want to reverse this Finalized Credit Analysis?",
        callback: function (result) {
            /* your callback code */
            if (result) {

                box = bootbox.dialog({
                    size: "small",
                    message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Reversing. Please Wait...</div>'
                });
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
                if (window.XMLHttpRequest) {
                    /*code for IE7+, Firefox, Chrome, Opera, Safari*/
                    xmlhttp = new XMLHttpRequest();
                } else {
                    /*code for IE6, IE5*/
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                        var data = xmlhttp.responseText;
                        box.modal('hide');
                        box2 = bootbox.alert({
                            size: "small",
                            title: "Rhomicom Message",
                            message: data,
                            callback: function () {
                                /* your callback code */
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
                        getOneCnsmrCrdtAnalysisForm(cnsmrCreditId, 1, 'ReloadDialog');
                    }
                };
                xmlhttp.open("POST", "index.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("grp=12&typ=1&pg=15&actyp=1&q=REVERSE&PKeyID=" + cnsmrCreditId);
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

function getOneItmPymntPlansSetupForm(pKeyID, vwtype, actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }

    var lnkArgs = 'grp=12&typ=1&pg=15&vtyp=' + vwtype + '&sbmtdItmPymntPlansSetupID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Payment Plans Setups', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
        $('#allOtherInputData99').val('0');
        $('#allItmPymntPlansSetupForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#allItmPymntPlansSetupTable')) {
            var table1 = $('#allItmPymntPlansSetupTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allItmPymntPlansSetupTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();



    });
}

function saveItmPymntPlansSetup() {

    var dsplyMsg = "";
    var slctdItmPymntPlansSetup = "";

    var errCount = 0;
    var rcdCount = 0;
    var lineCnta = 1;

    $('#allItmPymntPlansSetupTable').find('tr').each(function (i, el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                if (typeof $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').val() === 'undefined') {
                    /*Do Nothing*/
                } else {

                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').val() == "" || $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').val() == "") {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPriceTypeID').val() == "" || $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPriceTypeID').val() == "-1") {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPriceType').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPriceType').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPriceType').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPriceType').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPrice').val() == "" || $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPrice').val() <= 0) {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPrice').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPrice').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPrice').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPrice').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_NoOfPymnts').val() == "" || parseFloat($('#allItmPymntPlansSetupRow' + rndmNum + '_NoOfPymnts').val()) < 0) {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_NoOfPymnts').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_NoOfPymnts').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_NoOfPymnts').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_NoOfPymnts').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpstTypeID').val() == "" || parseFloat($('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpstTypeID').val()) == "-1") {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpstType').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpstType').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpstType').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpstType').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpst').val() == "" || parseFloat($('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpst').val()) < 0) {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpst').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpst').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpst').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpst').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_OrderNo').val() == "" || parseFloat($('#allItmPymntPlansSetupRow' + rndmNum + '_OrderNo').val()) < 0) {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_OrderNo').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_OrderNo').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_OrderNo').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_OrderNo').css('border-width', '1px');
                    }

                    if (errCount <= 0) {
                        slctdItmPymntPlansSetup = slctdItmPymntPlansSetup +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_ItmPymntPlansSetupID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanName').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPriceType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_PlanPrice').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_NoOfPymnts').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpstType').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_InitDpst').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_OrderNo').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_IsEnbld').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
            callback: function () {
                /* your callback code */
            }
        });
        return false;
    }

    var dsplyMsg = "Saving Payment Plan Setup(s)...Please Wait...";
    var dsplyMsgTtle = "Save Payment Plan Setups(s)?";
    var dsplyMsgRtrn = "Payment Plan Setup(s) Saved";

    var dialog = bootbox.alert({
        title: dsplyMsgTtle,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> ' + dsplyMsg + '</p>',
        callback: function () {
            var recCnt = typeof $("#recCnt").val() === 'undefined' ? 0 : $("#recCnt").val();

            if (parseInt(recCnt) > 0) {
                getOneItmPymntPlansSetupForm(-1, 500, 'ReloadDialog');
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
                    grp: 12,
                    typ: 1,
                    pg: 15,
                    q: 'UPDATE',
                    actyp: 500,
                    slctdItmPymntPlansSetup: slctdItmPymntPlansSetup
                },
                success: function (result) {
                    var data = result;
                    setTimeout(function () {
                        if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                            var obj = $.parseJSON(data);
                            $("#recCnt").val(parseInt(obj.recCntInst) + parseInt(obj.recCntUpdt));
                            var msg = "<span style='color:green;font-weight:bold !important;'>" + dsplyMsgRtrn + "</br><i>" + obj.recCntInst + " Setup record(s) inserted</br>" +
                                obj.recCntUpdt + " Setup record(s) updated</i></span>"
                            dialog.find('.bootbox-body').html(msg);
                        } else {
                            dialog.find('.bootbox-body').html(data);
                        }

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

function deleteItmPymntPlansSetup(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var PlanName = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmPymntPlansSetupID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItmPymntPlansSetupID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        PlanName = $.trim($tds.eq(2).text());
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
            if (result === true) {
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 15,
                                    q: 'DELETE',
                                    actyp: 500,
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

function deleteItmPymntPlans(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var PlanName = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ItmPymntPlansID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ItmPymntPlansID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        PlanName = $.trim($tds.eq(2).text());
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
            if (result === true) {
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
                                    grp: 12,
                                    typ: 1,
                                    pg: 3,
                                    q: 'DELETE',
                                    actyp: 500,
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

//NEW 13042020
function closeScmSalesInvForm() {
    $('#myFormsModalTitleLg').html('');
    $('#myFormsModalBodyLg').html('');
    $('#myFormsModalLg').modal('toggle');

}