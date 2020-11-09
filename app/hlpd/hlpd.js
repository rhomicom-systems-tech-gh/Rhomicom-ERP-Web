function prepareHLPD(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1
                || lnkArgs.indexOf("&pg=3&vtyp=0") !== -1
                || lnkArgs.indexOf("&pg=4&vtyp=0") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allRqstTcktsHdrsTable')) {
                var table1 = $('#allRqstTcktsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allRqstTcktsHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRqstTcktsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allRqstTcktsHdrsForm').submit(function (e) {
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
        } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1)
        {
            if (!$.fn.DataTable.isDataTable('#allRqstCtgrysTable')) {
                var table2 = $('#allRqstCtgrysTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allRqstCtgrysTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRqstCtgrysForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
        htBody.removeClass("mdlloading");
    });
}

function getAllRqstTckts(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allRqstTcktsSrchFor").val() === 'undefined' ? '%' : $("#allRqstTcktsSrchFor").val();
    var srchIn = typeof $("#allRqstTcktsSrchIn").val() === 'undefined' ? 'Both' : $("#allRqstTcktsSrchIn").val();
    var pageNo = typeof $("#allRqstTcktsPageNo").val() === 'undefined' ? 1 : $("#allRqstTcktsPageNo").val();
    var limitSze = typeof $("#allRqstTcktsDsplySze").val() === 'undefined' ? 10 : $("#allRqstTcktsDsplySze").val();
    var sortBy = typeof $("#allRqstTcktsSortBy").val() === 'undefined' ? '' : $("#allRqstTcktsSortBy").val();
    var qStrtDte = typeof $("#allRqstTcktsStrtDate").val() === 'undefined' ? '' : $("#allRqstTcktsStrtDate").val();
    var qEndDte = typeof $("#allRqstTcktsEndDate").val() === 'undefined' ? '' : $("#allRqstTcktsEndDate").val();
    var qNotClosed = $('#allRqstTcktsNotClosed:checked').length > 0;
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
            "&qStrtDte=" + qStrtDte + "&qEndDte=" + qEndDte +
            "&qNotClosed=" + qNotClosed;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAllRqstTckts(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllRqstTckts(actionText, slctr, linkArgs);
    }
}

function getAllRqstCtgrys(actionText, slctr, linkArgs)
{
    var srchFor = typeof $("#allRqstCtgrysSrchFor").val() === 'undefined' ? '%' : $("#allRqstCtgrysSrchFor").val();
    var srchIn = typeof $("#allRqstCtgrysSrchIn").val() === 'undefined' ? 'Both' : $("#allRqstCtgrysSrchIn").val();
    var pageNo = typeof $("#allRqstCtgrysPageNo").val() === 'undefined' ? 1 : $("#allRqstCtgrysPageNo").val();
    var limitSze = typeof $("#allRqstCtgrysDsplySze").val() === 'undefined' ? 10 : $("#allRqstCtgrysDsplySze").val();
    var sortBy = typeof $("#allRqstCtgrysSortBy").val() === 'undefined' ? '' : $("#allRqstCtgrysSortBy").val();
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

function enterKeyFuncAllRqstCtgrys(e, actionText, slctr, linkArgs)
{
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllRqstCtgrys(actionText, slctr, linkArgs);
    }
}
