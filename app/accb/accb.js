var fsrptTable1;

function prepareAccbAdmin(lnkArgs,htBody,targ,rspns) {
    if (
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=10" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=0" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=20" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=30" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=40" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=50" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=60" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=70" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=80" ||
        lnkArgs === "grp=6&typ=1&pg=5&vtyp=90" ||
        lnkArgs === "grp=6&typ=1&pg=19&vtyp=0"
    ) {
        shdHideFSRpt = 0;
    }
    $(targ).html(rspns);
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#accbAcntChrtHdrsTable")) {
                var table1 = $("#accbAcntChrtHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbAcntChrtHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbAcntChrtForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#accbJrnlEntrsHdrsTable")) {
                var table1 = $("#accbJrnlEntrsHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbJrnlEntrsHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbJrnlEntrsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=2&vtyp=2") !== -1) {
            if (!$.fn.DataTable.isDataTable("#oneJrnlBatchEditLinesTable")) {
                var table1 = $("#oneJrnlBatchEditLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneJrnlBatchEditLinesTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
        } else if (lnkArgs.indexOf("&pg=2&vtyp=3") !== -1) {
            if (!$.fn.DataTable.isDataTable("#oneJrnlBatchSmryLinesTable")) {
                var table1 = $("#oneJrnlBatchSmryLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneJrnlBatchSmryLinesTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
        } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#accbPttyCashHdrsTable")) {
                var table1 = $("#accbPttyCashHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbPttyCashHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbPttyCashForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (
            lnkArgs.indexOf("&pg=6&vtyp=0") !== -1 ||
            lnkArgs.indexOf("&pg=6&vtyp=1") !== -1
        ) {
            var table1;
            if (!$.fn.DataTable.isDataTable("#accbBdgtsHdrsTable")) {
                var table1 = $("#accbBdgtsHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbBdgtsHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbBdgtsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable("#accbBdgtDetsTable")) {
                var table2 = $("#accbBdgtDetsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbBdgtDetsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbBdgtDetsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable("#accbBdgtFurthDetsTable")) {
                var table2 = $("#accbBdgtFurthDetsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbBdgtFurthDetsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbBdgtFurthDetsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('[data-toggle="tabajxaccbbdgts"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr("href");
                var dttrgt = $this.attr("data-rhodata");
                var linkArgs = "grp=6&typ=1" + dttrgt;
                $(targ + "tab").tab("show");
                if (targ.indexOf("accbBdgtsDetList") >= 0) {
                    if ($("#accbBdgtsDetList").text().trim().length <= 0) {
                        var accbSbmtdBudgetID =
                            typeof $("#accbBudgetID").val() === "undefined"
                                ? "-1"
                                : $("#accbBudgetID").val();
                        var accbSbmtdBudgetNm =
                            typeof $("#accbBudgetNm").val() === "undefined"
                                ? ""
                                : $("#accbBudgetNm").val();
                        return getAccbBdgtDets(
                            "clear",
                            "#accbBdgtsDetList",
                            "grp=6&typ=1&pg=6&vtyp=1" +
                            "&accbSbmtdBudgetID=" +
                            accbSbmtdBudgetID +
                            "&accbSbmtdBudgetNm=" +
                            accbSbmtdBudgetNm,
                            accbSbmtdBudgetID
                        );
                    }
                } else if (targ.indexOf("accbBdgtsFurthDetList") >= 0) {
                    if ($("#accbBdgtsFurthDetList").text().trim().length <= 0) {
                        var accbSbmtdBudgetID =
                            typeof $("#accbBudgetID").val() === "undefined"
                                ? "-1"
                                : $("#accbBudgetID").val();
                        var accbSbmtdBudgetNm =
                            typeof $("#accbBudgetNm").val() === "undefined"
                                ? ""
                                : $("#accbBudgetNm").val();
                        return getAccbBdgtFurthDets(
                            "clear",
                            "#accbBdgtsFurthDetList",
                            "grp=6&typ=1&pg=6&vtyp=3" +
                            "&accbSbmtdBudgetID=" +
                            accbSbmtdBudgetID +
                            "&accbSbmtdBudgetNm=" +
                            accbSbmtdBudgetNm,
                            accbSbmtdBudgetID
                        );
                    }
                }
            });
        } else if (lnkArgs.indexOf("&pg=7&vtyp=0") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable("#accbTmpltsHdrsTable")) {
                table1 = $("#accbTmpltsHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTmpltsHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable("#accbTmpltsLinesTable")) {
                var table2 = $("#accbTmpltsLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTmpltsLinesTable").wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable("#accbTmpltsUsersTable")) {
                var table3 = $("#accbTmpltsUsersTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTmpltsUsersTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbTmpltsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#accbTmpltsHdrsTable tbody").off("click");
            $("#accbTmpltsHdrsTable tbody").on("click","tr",function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    table1.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");
                }
                var rndmNum = $(this).attr("id").split("_")[1];
                var pkeyID =
                    typeof $("#accbTmpltsHdrsRow" + rndmNum + "_HdrID").val() ===
                        "undefined"
                        ? "-1"
                        : $("#accbTmpltsHdrsRow" + rndmNum + "_HdrID").val();
                getOneAccbTmpltsForm(pkeyID,1);
            });
            $("#accbTmpltsHdrsTable tbody").off("mouseenter","tr");
            $("#accbTmpltsHdrsTable tbody").on("mouseenter","tr",function () {
                if ($(this).hasClass("highlight")) {
                    $(this).removeClass("highlight");
                } else {
                    table1.$("tr.highlight").removeClass("highlight");
                    $(this).addClass("highlight");
                }
            });
        } else if (lnkArgs.indexOf("&pg=70&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#payInvstTransHdrsTable")) {
                var table1 = $("#payInvstTransHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#payInvstTransHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#payInvstTransForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=72&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#smplVchrTransHdrsTable")) {
                var table1 = $("#smplVchrTransHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#smplVchrTransHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#smplVchrTransForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=71&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#payTransTypsHdrsTable")) {
                var table1 = $("#payTransTypsHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#payTransTypsHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#payTransTypsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=8&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#accbPeriodsHdrsTable")) {
                var table1 = $("#accbPeriodsHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbPeriodsHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#onePeriodEDTForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (
            lnkArgs.indexOf("&pg=9&vtyp=0") !== -1 ||
            lnkArgs.indexOf("&pg=9&vtyp=1") !== -1
        ) {
            if (!$.fn.DataTable.isDataTable("#accbAssetsHdrsTable")) {
                var table1 = $("#accbAssetsHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbAssetsHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbAssetsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#accbAstHdrRmngLife1").val($("#accbAstHdrRmngLife").val());
            var table1;
            if (!$.fn.DataTable.isDataTable("#accbAssetsPMStpsTable")) {
                table1 = $("#accbAssetsPMStpsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbAssetsPMStpsTable").wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable("#oneAccbAssetsExtrInfTable")) {
                var table2 = $("#oneAccbAssetsExtrInfTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneAccbAssetsExtrInfTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            $("#accbAstHdrForm").submit(function (e) {
                e.preventDefault();
                return false;
            });

            if (!$.fn.DataTable.isDataTable("#oneAccbAssetTransLinesTable")) {
                var table2 = $("#oneAccbAssetTransLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneAccbAssetTransLinesTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            if (!$.fn.DataTable.isDataTable("#oneAccbAssetPMRecsTable")) {
                var table2 = $("#oneAccbAssetPMRecsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneAccbAssetPMRecsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $('[data-toggle="tabajxassetdetls"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr("href");
                var dttrgt = $this.attr("data-rhodata");
                var linkArgs = "grp=6&typ=1" + dttrgt;
                $(targ + "tab").tab("show");
                if (targ.indexOf("assetDetlsTrans") >= 0) {
                    if ($("#assetDetlsTrans").text().trim().length <= 0) {
                        var accbSbmtdAssetID =
                            typeof $("#accbSbmtdAssetID").val() === "undefined"
                                ? "-1"
                                : $("#accbSbmtdAssetID").val();
                        var accbSbmtdAssetNm =
                            typeof $("#accbSbmtdAssetNm").val() === "undefined"
                                ? ""
                                : $("#accbSbmtdAssetNm").val();
                        return getAccbAstHdr(
                            "clear",
                            "#assetDetlsTrans",
                            "grp=6&typ=1&pg=9&vtyp=2" +
                            "&accbSbmtdAssetID=" +
                            accbSbmtdAssetID +
                            "&accbSbmtdAssetNm=" +
                            accbSbmtdAssetNm,
                            accbSbmtdAssetID
                        );
                    }
                } else if (targ.indexOf("assetDetlsPMRecs") >= 0) {
                    if ($("#assetDetlsPMRecs").text().trim().length <= 0) {
                        var accbSbmtdAssetID =
                            typeof $("#accbSbmtdAssetID").val() === "undefined"
                                ? "-1"
                                : $("#accbSbmtdAssetID").val();
                        var accbSbmtdAssetNm =
                            typeof $("#accbSbmtdAssetNm").val() === "undefined"
                                ? ""
                                : $("#accbSbmtdAssetNm").val();
                        return getAccbAstPmRec(
                            "clear",
                            "#assetDetlsPMRecs",
                            "grp=6&typ=1&pg=9&vtyp=3" +
                            "&accbSbmtdAssetID=" +
                            accbSbmtdAssetID +
                            "&accbSbmtdAssetNm=" +
                            accbSbmtdAssetNm,
                            accbSbmtdAssetID
                        );
                    }
                }
            });
            $('[data-toggle="tabajxassetrgstr"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr("href");
                var dttrgt = $this.attr("data-rhodata");
                var linkArgs = "grp=6&typ=1" + dttrgt;
                $(targ + "tab").tab("show");
                if (targ.indexOf("assetRgstrDetList") >= 0) {
                    if ($("#assetRgstrDetList").text().trim().length <= 0) {
                        var accbSbmtdAssetID =
                            typeof $("#accbAssetID").val() === "undefined"
                                ? "-1"
                                : $("#accbAssetID").val();
                        var accbSbmtdAssetNm =
                            typeof $("#accbAssetNm").val() === "undefined"
                                ? ""
                                : $("#accbAssetNm").val();
                        return getAccbAssetDets(
                            "clear",
                            "#assetRgstrDetList",
                            "grp=6&typ=1&pg=9&vtyp=1" +
                            "&accbSbmtdAssetID=" +
                            accbSbmtdAssetID +
                            "&accbSbmtdAssetNm=" +
                            accbSbmtdAssetNm,
                            accbSbmtdAssetID
                        );
                    }
                }
            });
        } else if (lnkArgs.indexOf("&pg=10&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#accbPyblsInvcHdrsTable")) {
                var table1 = $("#accbPyblsInvcHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbPyblsInvcHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbPyblsInvcForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=11&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#accbRcvblsInvcHdrsTable")) {
                var table1 = $("#accbRcvblsInvcHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbRcvblsInvcHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbRcvblsInvcForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=12&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#accbPymntsHdrsTable")) {
                var table1 = $("#accbPymntsHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbPymntsHdrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbPymntsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=13&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#allCstmrsTable")) {
                var table1 = $("#allCstmrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#allCstmrsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#allCstmrsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (
            lnkArgs.indexOf("&pg=5&vtyp=10") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=20") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=30") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=40") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=50") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=60") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=70") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=80") !== -1 ||
            lnkArgs.indexOf("&pg=5&vtyp=90") !== -1 ||
            lnkArgs.indexOf("&pg=19&vtyp=0") !== -1
        ) {
            if (!$.fn.DataTable.isDataTable("#accbFSRptTable")) {
                fsrptTable1 = $("#accbFSRptTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbFSRptTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbFSRptForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#accbFSRptShwSmmry").off("click");
            $("#accbFSRptShwSmmry").on("click",function (event) {
                var accbFSRptShwSmmry =
                    $("#accbFSRptShwSmmry:checked").length > 0 ? "YES" : "NO";
                if (accbFSRptShwSmmry === "YES") {
                    $("#accbFSRptMaxAcntLvl").val(1);
                } else {
                    $("#accbFSRptMaxAcntLvl").val(100);
                }
            });
            $("#accbFSRptTable tbody").off("dblclick");
            $("#accbFSRptTable tbody").on("dblclick","tr",function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    fsrptTable1.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");
                }
                var rndmNum = $(this).attr("id").split("_")[1];
                var pkeyID =
                    typeof $("#oneAccbFSRptRow" + rndmNum + "_AccountID").val() ===
                        "undefined"
                        ? "-1"
                        : $("#oneAccbFSRptRow" + rndmNum + "_AccountID").val();
                var pkeyID2 =
                    typeof $("#oneAccbFSRptRow" + rndmNum + "_TransLineID").val() ===
                        "undefined"
                        ? "-1"
                        : $("#oneAccbFSRptRow" + rndmNum + "_TransLineID").val();
                var pIsParent =
                    typeof $("#oneAccbFSRptRow" + rndmNum + "_IsParent").val() ===
                        "undefined"
                        ? "0"
                        : $("#oneAccbFSRptRow" + rndmNum + "_IsParent").val();
                var pAccntNum =
                    typeof $("#oneAccbFSRptRow" + rndmNum + "_AccntNum").val() ===
                        "undefined"
                        ? "0"
                        : $("#oneAccbFSRptRow" + rndmNum + "_AccntNum").val();
                var p_TrnsDate =
                    typeof $("#oneAccbFSRptRow" + rndmNum + "_TrnsDate").val() ===
                        "undefined"
                        ? accbStrtFSRptDte
                        : $("#oneAccbFSRptRow" + rndmNum + "_TrnsDate").val();
                var accbFSRptDte =
                    typeof $("#accbFSRptDte").val() === "undefined"
                        ? ""
                        : $("#accbFSRptDte").val();
                var accbStrtFSRptDte =
                    typeof $("#accbStrtFSRptDte").val() === "undefined"
                        ? ""
                        : $("#accbStrtFSRptDte").val();
                if (pIsParent === "1" && Number(pkeyID2) <= -1) {
                    getAccbFSRptRpts(1,"#allmodules",lnkArgs,pkeyID);
                } else if (lnkArgs.indexOf("&pg=5&vtyp=80") !== -1) {
                    getAccbTransSrchDet(
                        pAccntNum,
                        "Account Number",
                        true,
                        true,
                        p_TrnsDate.substring(0,11),
                        p_TrnsDate.substring(0,11),
                        "Breakdown of Account Transactions",
                        "ShowDialog",
                        function () { }
                    );
                } else if (
                    !(lnkArgs.indexOf("&pg=5&vtyp=50") !== -1) &&
                    !(lnkArgs.indexOf("&pg=19&vtyp=0") !== -1) &&
                    !(lnkArgs.indexOf("&pg=5&vtyp=90") !== -1)
                ) {
                    getAccbTransSrchDet(
                        pAccntNum,
                        "Account Number",
                        true,
                        true,
                        accbStrtFSRptDte,
                        accbFSRptDte,
                        "Breakdown of Account Transactions",
                        "ShowDialog",
                        function () { }
                    );
                } else if (Number(pkeyID2) > 0) {
                    getAccbCashBreakdown(
                        pkeyID2,
                        "ShowDialog",
                        "Transaction Amount Breakdown",
                        "VIEW",
                        "Transaction Amount Breakdown Parameters",
                        "",
                        ""
                    );
                }
            });
            $("#accbFSRptTable tbody").off("mouseenter","tr");
            $("#accbFSRptTable tbody").on("mouseenter","tr",function () {
                if ($(this).hasClass("highlight")) {
                    $(this).removeClass("highlight");
                } else {
                    fsrptTable1.$("tr.highlight").removeClass("highlight");
                    $(this).addClass("highlight");
                }
            });
            if (shdHideFSRpt >= 1) {
                shwHideFSRptDivs("hide");
            }
            if (
                lnkArgs.indexOf("&pg=19&vtyp=0") !== -1 ||
                lnkArgs.indexOf("&pg=5&vtyp=10") !== -1 ||
                lnkArgs.indexOf("&pg=5&vtyp=90") !== -1
            ) {
                $('[data-toggle="tabajxaccrcncl"]').off("click");
                $('[data-toggle="tabajxaccrcncl"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr("href");
                    var dttrgt = $this.attr("data-rhodata");
                    var linkArgs = "grp=6&typ=1" + dttrgt;
                    $(targ + "tab").tab("show");
                });
            }
        } else if (lnkArgs.indexOf("&pg=16&vtyp=0") !== -1) {
            if (!$.fn.DataTable.isDataTable("#acbExchRatesTable")) {
                var table1 = $("#acbExchRatesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#acbExchRatesTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#acbExchRatesForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=17&vtyp=0") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable("#accbDocTmpltsTable")) {
                table1 = $("#accbDocTmpltsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbDocTmpltsTable").wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable("#accbDocTmpltAdtTblsTable")) {
                var table2 = $("#accbDocTmpltAdtTblsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbDocTmpltAdtTblsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbDocTmpltsForm").submit(function (e) {
                e.preventDefault();
                return false;
            });

            $("#accbDocTmpltsTable tbody").off("click");
            $("#accbDocTmpltsTable tbody").on("click","tr",function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    table1.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");
                }
                var rndmNum = $(this).attr("id").split("_")[1];
                var pkeyID =
                    typeof $("#accbDocTmpltsRow" + rndmNum + "_TmpltID").val() ===
                        "undefined"
                        ? "-1"
                        : $("#accbDocTmpltsRow" + rndmNum + "_TmpltID").val();
                getOneAccbDocTmpltForm(pkeyID,1);
            });
            $("#accbDocTmpltsTable tbody").off("mouseenter","tr");
            $("#accbDocTmpltsTable tbody").on("mouseenter","tr",function () {
                if ($(this).hasClass("highlight")) {
                    $(this).removeClass("highlight");
                } else {
                    table1.$("tr.highlight").removeClass("highlight");
                    $(this).addClass("highlight");
                }
            });
        } else if (lnkArgs.indexOf("&pg=14&vtyp=0") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable("#accbTxCdeTable")) {
                table1 = $("#accbTxCdeTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTxCdeTable").wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable("#accbTaxCodeAdtTblsTable")) {
                var table2 = $("#accbTaxCodeAdtTblsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTaxCodeAdtTblsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#accbTxCdeForm").submit(function (e) {
                e.preventDefault();
                return false;
            });

            $("#accbTxCdeTable tbody").off("click");
            $("#accbTxCdeTable tbody").on("click","tr",function () {
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                } else {
                    table1.$("tr.selected").removeClass("selected");
                    $(this).addClass("selected");
                }
                var rndmNum = $(this).attr("id").split("_")[1];
                var pkeyID =
                    typeof $("#accbTxCdeRow" + rndmNum + "_CodeID").val() === "undefined"
                        ? "-1"
                        : $("#accbTxCdeRow" + rndmNum + "_CodeID").val();
                getOneAccbTaxCodeForm(pkeyID,1);
            });
            $("#accbTxCdeTable tbody").off("mouseenter","tr");
            $("#accbTxCdeTable tbody").on("mouseenter","tr",function () {
                if ($(this).hasClass("highlight")) {
                    $(this).removeClass("highlight");
                } else {
                    table1.$("tr.highlight").removeClass("highlight");
                    $(this).addClass("highlight");
                }
            });
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
        $(".form_date_tme").datetimepicker({
            format: "dd-M-yyyy hh:ii:ss",
            language: "en",
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 0,
            maxView: 4,
            forceParse: true,
        });
        $(".form_date").datetimepicker({
            format: "dd-M-yyyy",
            language: "en",
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 2,
            maxView: 4,
            forceParse: true,
        });
        htBody.removeClass("mdlloading");
    });
}

function getAccbAcntChrt(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbAcntChrtSrchFor").val() === "undefined"
            ? "%"
            : $("#accbAcntChrtSrchFor").val();
    var srchIn =
        typeof $("#accbAcntChrtSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbAcntChrtSrchIn").val();
    var pageNo =
        typeof $("#accbAcntChrtPageNo").val() === "undefined"
            ? 1
            : $("#accbAcntChrtPageNo").val();
    var limitSze =
        typeof $("#accbAcntChrtDsplySze").val() === "undefined"
            ? 10
            : $("#accbAcntChrtDsplySze").val();
    var sortBy =
        typeof $("#accbAcntChrtSortBy").val() === "undefined"
            ? ""
            : $("#accbAcntChrtSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbAcntChrt(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbAcntChrt(actionText,slctr,linkArgs);
    }
}

function delAccbAcntChrt(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var msgPrt = "Account";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 1,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function getAccbJrnlEntrs(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbJrnlEntrsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbJrnlEntrsSrchFor").val();
    var srchIn =
        typeof $("#accbJrnlEntrsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbJrnlEntrsSrchIn").val();
    var pageNo =
        typeof $("#accbJrnlEntrsPageNo").val() === "undefined"
            ? 1
            : $("#accbJrnlEntrsPageNo").val();
    var limitSze =
        typeof $("#accbJrnlEntrsDsplySze").val() === "undefined"
            ? 10
            : $("#accbJrnlEntrsDsplySze").val();
    var sortBy =
        typeof $("#accbJrnlEntrsSortBy").val() === "undefined"
            ? ""
            : $("#accbJrnlEntrsSortBy").val();
    var qShwUsrOnly = $("#accbJrnlEntrsShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#accbJrnlEntrsShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbJrnlEntrs(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbJrnlEntrs(actionText,slctr,linkArgs);
    }
}

function getJrnlFrmTmplate(vtype,slctr) {
    var jrnlBatchAmountCrncy =
        typeof $("#jrnlBatchAmountCrncy").text() === "undefined"
            ? ""
            : $("#jrnlBatchAmountCrncy").text();
    var jrnlBatchDfltTrnsDte =
        typeof $("#jrnlBatchDfltTrnsDte").val() === "undefined"
            ? ""
            : $("#jrnlBatchDfltTrnsDte").val();
    var jrnlBatchDfltBalsAcnt =
        typeof $("#jrnlBatchDfltBalsAcnt").val() === "undefined"
            ? ""
            : $("#jrnlBatchDfltBalsAcnt").val();
    var jrnlBatchDfltBalsAcntID =
        typeof $("#jrnlBatchDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#jrnlBatchDfltBalsAcntID").val();
    var sbmtdTempltLovID =
        typeof $("#sbmtdTempltLovID").val() === "undefined"
            ? ""
            : $("#sbmtdTempltLovID").val();
    var lnkArgs =
        "grp=6&typ=1&pg=2&vtyp=" +
        vtype +
        "&sbmtdTempltLovID=" +
        sbmtdTempltLovID +
        "&jrnlBatchAmountCrncy=" +
        jrnlBatchAmountCrncy +
        "&jrnlBatchDfltTrnsDte=" +
        jrnlBatchDfltTrnsDte +
        "&jrnlBatchDfltBalsAcnt=" +
        jrnlBatchDfltBalsAcnt +
        "&jrnlBatchDfltBalsAcntID=" +
        jrnlBatchDfltBalsAcntID;
    /*alert(sbmtdTempltLovID);*/
    openATab("#" + slctr,lnkArgs);
}

function calcAllJrnlBatchEditTtl() {
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    $("#oneJrnlBatchEditLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var prfxName = $(el).attr("id").split("_")[0];
                    ttlRwAmount = (
                        $("#" + prfxName + rndmNum + "_EntrdAmt").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount = ttlAmount + Number(ttlRwAmount);
                }
            }
        });
    $("#myCptrdEntrdAmntTtlBtn").text(addCommas(ttlAmount.toFixed(2)));
    $("#myCptrdEntrdAmntTtlVal").val(ttlAmount.toFixed(2));
}

function calcAllJrnlBatchSmryTtl() {
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    $("#oneJrnlBatchSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var prfxName = $(el).attr("id").split("_")[0];
                    ttlRwAmount = (
                        $("#" + prfxName + rndmNum + "_EntrdAmt").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount = ttlAmount + Number(ttlRwAmount);
                }
            }
        });
    $("#myCptrdJbSmryAmtTtlBtn").text(addCommas(ttlAmount.toFixed(2)));
    $("#myCptrdJbSmryAmtTtlVal").val(ttlAmount.toFixed(2));
}

function getOneJrnlBatchDocsForm(pKeyID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=2&vtyp=" + vwtype + "&sbmtdJrnlBatchID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        "ShowDialog",
        "Journal Batch Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            var table1 = $("#attchdJrnlBatchDocsTable").DataTable({
                paging: false,
                ordering: false,
                info: false,
                bFilter: false,
                scrollX: false,
            });
            $("#attchdJrnlBatchDocsTable").wrap('<div class="dataTables_scroll"/>');
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdJrnlBatchDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function uploadFileToJrnlBatchDocs(
    inptElmntID,
    attchIDElmntID,
    docNmElmntID,
    sbmtdHdrID,
    rowIDAttrb
) {
    var docCtrgrName = $("#" + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    $("#" + inptElmntID).val("");
    $("#" + inptElmntID).off("change");
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToJrnlBatchDocs(
            input.files[0],
            docNmElmntID,
            attchIDElmntID,
            sbmtdHdrID,
            function (data) {
                $("#" + attchIDElmntID).val(data.attchID);
                var dialog = bootbox.alert({
                    title: "Server Response!",
                    size: "small",
                    message: '<div id="myInformation">' + data.message + "</div>",
                    callback: function () {
                        if (data.message.indexOf("Success") !== -1) {
                        }
                    },
                });
            }
        );
    });
    performFileClick(inptElmntID);
}

function sendFileToJrnlBatchDocs(
    file,
    docNmElmntID,
    attchIDElmntID,
    sbmtdHdrID,
    callBackFunc
) {
    var data1 = new FormData();
    data1.append("daJrnlBatchAttchmnt",file);
    data1.append("grp",6);
    data1.append("typ",1);
    data1.append("pg",2);
    data1.append("q","UPDATE");
    data1.append("actyp",2);
    data1.append("docCtrgrName",$("#" + docNmElmntID).val());
    data1.append("attchmentID",$("#" + attchIDElmntID).val());
    data1.append("sbmtdJrnlBatchID",sbmtdHdrID);
    var dialog1 = bootbox.alert({
        title: "Uploading File...",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>',
    });
    dialog1.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    dialog1.modal("hide");
                    callBackFunc(data);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAttchdJrnlBatchDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var srchFor =
        typeof $("#attchdJrnlBatchDocsSrchFor").val() === "undefined"
            ? "%"
            : $("#attchdJrnlBatchDocsSrchFor").val();
    var srchIn =
        typeof $("#attchdJrnlBatchDocsSrchIn").val() === "undefined"
            ? "Both"
            : $("#attchdJrnlBatchDocsSrchIn").val();
    var pageNo =
        typeof $("#attchdJrnlBatchDocsPageNo").val() === "undefined"
            ? 1
            : $("#attchdJrnlBatchDocsPageNo").val();
    var limitSze =
        typeof $("#attchdJrnlBatchDocsDsplySze").val() === "undefined"
            ? 10
            : $("#attchdJrnlBatchDocsDsplySze").val();
    var sortBy =
        typeof $("#attchdJrnlBatchDocsSortBy").val() === "undefined"
            ? ""
            : $("#attchdJrnlBatchDocsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    doAjaxWthCallBck(
        linkArgs,
        "myFormsModaly",
        actionDialog,
        "Journal Batch Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            if (!$.fn.DataTable.isDataTable("#attchdJrnlBatchDocsTable")) {
                var table1 = $("#attchdJrnlBatchDocsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#attchdJrnlBatchDocsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdJrnlBatchDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function enterKeyFuncAttchdJrnlBatchDocs(
    e,
    actionText,
    slctr,
    linkArgs,
    actionDialog
) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdJrnlBatchDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdJrnlBatchDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID =
        typeof $("#sbmtdJrnlBatchID").val() === "undefined"
            ? -1
            : $("#sbmtdJrnlBatchID").val();
    var docNum =
        typeof $("#jrnlBatchNum").val() === "undefined"
            ? ""
            : $("#jrnlBatchNum").val();
    var pKeyID = -1;
    if (
        typeof $("#attchdJrnlBatchDocsRow" + rndmNum + "_AttchdDocsID").val() ===
        "undefined"
    ) {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $("#attchdJrnlBatchDocsRow" + rndmNum + "_AttchdDocsID").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Document?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Document?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Document?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 2,
                                    q: "DELETE",
                                    actyp: 5,
                                    attchmentID: pKeyID,
                                    sbmtdHdrID: sbmtdHdrID,
                                    docTrnsNum: docNum,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function insertNewJrnlBatcRows(tableElmntID,position,inptHtml) {
    var jrnlBatchAmountCrncy =
        typeof $("#jrnlBatchAmountCrncy").text() === "undefined"
            ? ""
            : $("#jrnlBatchAmountCrncy").text();
    var jrnlBatchDfltTrnsDte =
        typeof $("#jrnlBatchDfltTrnsDte").val() === "undefined"
            ? ""
            : $("#jrnlBatchDfltTrnsDte").val();
    var jrnlBatchDfltBalsAcnt =
        typeof $("#jrnlBatchDfltBalsAcnt").val() === "undefined"
            ? ""
            : $("#jrnlBatchDfltBalsAcnt").val();
    var jrnlBatchDfltBalsAcntID =
        typeof $("#jrnlBatchDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#jrnlBatchDfltBalsAcntID").val();
    $("#allOtherInputData5").val(0);
    getCurSlctnExchRate("allOtherInputData5",function () {
        var funcRate = $("#allOtherInputData5").val();
        for (var i = 0; i < 5; i++) {
            var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
            var nwInptHtml = urldecode(
                inptHtml
                    .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                    .replace(/(_WWW123WWW)+/g,nwRndm)
            );
            if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
                if (
                    $("#" + tableElmntID + " > tbody > tr > td")
                        .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                        .text() == "No data available in table"
                ) {
                    $("#" + tableElmntID + " > tbody > tr > td")
                        .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                        .remove();
                    $("#" + tableElmntID).append(nwInptHtml);
                } else {
                    $("#" + tableElmntID + " > tbody > tr")
                        .eq(0)
                        .before(nwInptHtml);
                }
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
            if (tableElmntID.indexOf("oneJrnlBatchEditLinesTable") >= 0) {
                $("#oneJrnlBatchEditRow" + nwRndm + "_TrnsCurNm").val(
                    jrnlBatchAmountCrncy
                );
                $("#oneJrnlBatchEditRow" + nwRndm + "_TrnsCurNm1").text(
                    jrnlBatchAmountCrncy
                );
                $("#oneJrnlBatchEditRow" + nwRndm + "_TransDte").val(
                    jrnlBatchDfltTrnsDte
                );
                $("#oneJrnlBatchEditRow" + nwRndm + "_FuncExchgRate").val(funcRate);
            } else if (tableElmntID.indexOf("oneJrnlBatchSmryLinesTable") >= 0) {
                $("#oneJrnlBatchSmryRow" + nwRndm + "_TrnsCurNm").val(
                    jrnlBatchAmountCrncy
                );
                $("#oneJrnlBatchSmryRow" + nwRndm + "_TrnsCurNm1").text(
                    jrnlBatchAmountCrncy
                );
                $("#oneJrnlBatchSmryRow" + nwRndm + "_TransDte").val(
                    jrnlBatchDfltTrnsDte
                );
                $("#oneJrnlBatchSmryRow" + nwRndm + "_AccountID2").val(
                    jrnlBatchDfltBalsAcntID
                );
                $("#oneJrnlBatchSmryRow" + nwRndm + "_AccountNm2").val(
                    jrnlBatchDfltBalsAcnt
                );
                $("#oneJrnlBatchSmryRow" + nwRndm + "_FuncExchgRate").val(funcRate);
            } else if (tableElmntID.indexOf("oneJrnlBatchDetLinesTable") >= 0) {
                $("#oneJrnlBatchDetRow" + nwRndm + "_TrnsCurNm").val(
                    jrnlBatchAmountCrncy
                );
                $("#oneJrnlBatchDetRow" + nwRndm + "_TrnsCurNm1").text(
                    jrnlBatchAmountCrncy
                );
                $("#oneJrnlBatchDetRow" + nwRndm + "_TransDte").val(
                    jrnlBatchDfltTrnsDte
                );
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
        $(".form_date_tme")
            .datetimepicker({
                format: "dd-M-yyyy hh:ii:ss",
                language: "en",
                weekStart: 0,
                todayBtn: true,
                autoclose: true,
                todayHighlight: true,
                keyboardNavigation: true,
                startView: 2,
                minView: 0,
                maxView: 4,
                forceParse: true,
            })
            .on("hide",function (ev) {
                $("#myFormsModalLg").css("overflow","auto");
            });
        $(".form_date")
            .datetimepicker({
                format: "dd-M-yyyy",
                language: "en",
                weekStart: 0,
                todayBtn: true,
                autoclose: true,
                todayHighlight: true,
                keyboardNavigation: true,
                startView: 2,
                minView: 2,
                maxView: 4,
                forceParse: true,
            })
            .on("hide",function (ev) {
                $("#myFormsModalLg").css("overflow","auto");
            });
    });
}

function insertOnlyJrnlBatcRows(tableElmntID,cntr,inptHtml) {
    var nwRndm = 0;
    var curPos = Number($("#allOtherInputData99").val());
    var ttlrows = $("#" + tableElmntID + " > tbody > tr").length;
    for (var i = 0; i < cntr; i++) {
        nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq(ttlrows - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq(ttlrows - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                if (curPos === ttlrows) {
                    $("#" + tableElmntID).append(nwInptHtml);
                } else if (curPos >= 1) {
                    $("#" + tableElmntID).append(nwInptHtml);
                    /*$('#' + tableElmntID + ' > tbody > tr').eq(curPos - 1).after(nwInptHtml);*/
                } else {
                    $("#" + tableElmntID).append(nwInptHtml);
                    /*$('#' + tableElmntID + ' > tbody > tr').eq(curPos).after(nwInptHtml);*/
                }
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
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
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    return nwRndm;
}

function saveJrnlBatchForm(
    funccrnm,
    actyp,
    rptID,
    alrtID,
    paramsStr,
    extraPKeyID,
    extraPKeyType,
    srcPAGE
) {
    if (typeof funccrnm === "undefined" || funccrnm === null) {
        funccrnm = "GHS";
    }
    if (typeof srcPAGE === "undefined" || srcPAGE === null) {
        srcPAGE = "";
    }
    if (typeof actyp === "undefined" || actyp === null) {
        actyp = 0;
    }
    if (typeof rptID === "undefined" || rptID === null) {
        rptID = -1;
    }
    if (typeof alrtID === "undefined" || alrtID === null) {
        alrtID = -1;
    }
    if (typeof paramsStr === "undefined" || paramsStr === null) {
        paramsStr = "";
    }
    if (typeof extraPKeyID === "undefined" || extraPKeyID === null) {
        extraPKeyID = -1;
    }
    if (typeof extraPKeyType === "undefined" || extraPKeyType === null) {
        extraPKeyType = "";
    }
    var sbmtdJrnlBatchID =
        typeof $("#sbmtdJrnlBatchID").val() === "undefined"
            ? "-1"
            : $("#sbmtdJrnlBatchID").val();
    if (actyp === 5) {
        getSilentRptsRnSts(rptID,alrtID,paramsStr,function () {
            if (Number(sbmtdJrnlBatchID) > 0) {
                getOneJrnlBatchForm(
                    sbmtdJrnlBatchID,
                    1,
                    "ReloadDialog",
                    extraPKeyID,
                    extraPKeyType
                );
            } else {
                getAccbJrnlEntrs("","#allmodules","grp=6&typ=1&pg=2&vtyp=0");
            }
        });
        return false;
    } else {
        var sbmtdTempltLovID =
            typeof $("#sbmtdTempltLovID").val() === "undefined"
                ? -1
                : $("#sbmtdTempltLovID").val();
        var voidedJrnlBatchID =
            typeof $("#voidedJrnlBatchID").val() === "undefined"
                ? -1
                : $("#voidedJrnlBatchID").val();
        var jrnlBatchNum =
            typeof $("#jrnlBatchNum").val() === "undefined"
                ? ""
                : $("#jrnlBatchNum").val();
        var jrnlBatchCreationDate =
            typeof $("#jrnlBatchCreationDate").val() === "undefined"
                ? ""
                : $("#jrnlBatchCreationDate").val();
        var jrnlBatchSource =
            typeof $("#jrnlBatchSource").val() === "undefined"
                ? "Manual"
                : $("#jrnlBatchSource").val();
        var jrnlBatchDfltCurNm =
            typeof $("#jrnlBatchDfltCurNm").val() === "undefined"
                ? ""
                : $("#jrnlBatchDfltCurNm").val();
        var jrnlBatchDfltTrnsDte =
            typeof $("#jrnlBatchDfltTrnsDte").val() === "undefined"
                ? ""
                : $("#jrnlBatchDfltTrnsDte").val();
        var jrnlBatchDesc =
            typeof $("#jrnlBatchDesc").val() === "undefined"
                ? ""
                : $("#jrnlBatchDesc").val();
        var jrnlBatchDfltBalsAcntID =
            typeof $("#jrnlBatchDfltBalsAcntID").val() === "undefined"
                ? -1
                : $("#jrnlBatchDfltBalsAcntID").val();
        var errMsg = "";
        if (jrnlBatchNum.trim() === "" || jrnlBatchSource.trim() === "") {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Batch Name/Source cannot be empty!</span></p>';
        }
        if (jrnlBatchDesc.trim() === "") {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Batch Description cannot be empty!</span></p>';
        }
        if (
            jrnlBatchDfltTrnsDte.trim() === "" ||
            jrnlBatchDfltCurNm.trim() === ""
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Batch Date/Currency cannot be empty!</span></p>';
        }
        var slctdBatchDetLines = "";
        var isVld = true;
        $("#oneJrnlBatchDetLinesTable")
            .find("tr")
            .each(function (i,el) {
                isVld = true;
                if (i > 0) {
                    if (typeof $(el).attr("id") === "undefined") {
                        /*Do Nothing*/
                    } else {
                        var rndmNum = $(el).attr("id").split("_")[1];
                        if (
                            typeof $("#oneJrnlBatchDetRow" + rndmNum + "_AccountID").val() ===
                            "undefined"
                        ) {
                            /*Do Nothing*/
                        } else {
                            var lineAccntID = $(
                                "#oneJrnlBatchDetRow" + rndmNum + "_AccountID"
                            ).val();
                            var lineTrnsSmryLnID = $(
                                "#oneJrnlBatchDetRow" + rndmNum + "_TrnsSmryLnID"
                            ).val();
                            var lineDesc = $(
                                "#oneJrnlBatchDetRow" + rndmNum + "_LineDesc"
                            ).val();
                            var lineCurNm = $(
                                "#oneJrnlBatchDetRow" + rndmNum + "_TrnsCurNm"
                            ).val();
                            var lineDbtAmt = $(
                                "#oneJrnlBatchDetRow" + rndmNum + "_DebitAmnt"
                            ).val();
                            var lineCrdtAmt = $(
                                "#oneJrnlBatchDetRow" + rndmNum + "_CreditAmnt"
                            ).val();
                            var lineTransDate = $(
                                "#oneJrnlBatchDetRow" + rndmNum + "_TransDte"
                            ).val();
                            if (
                                Number(lineAccntID.replace(/[^-?0-9\.]/g,"")) > 0 &&
                                Number(lineTrnsSmryLnID.replace(/[^-?0-9\.]/g,"")) <= 0
                            ) {
                                if (lineDesc.trim() === "") {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Narration/Description for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_LineDesc").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_LineDesc").removeClass(
                                        "rho-error"
                                    );
                                }
                                if (lineCurNm.trim() === "") {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Currency for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_TrnsCurNm").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_TrnsCurNm").removeClass(
                                        "rho-error"
                                    );
                                }
                                if (lineTransDate.trim() === "") {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Transaction Date for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_TransDte").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_TransDte").removeClass(
                                        "rho-error"
                                    );
                                }
                                if (Number(lineAccntID.replace(/[^-?0-9\.]/g,"")) <= 0) {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">GL Account for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_AccountNm").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_AccountNm").removeClass(
                                        "rho-error"
                                    );
                                }
                                if (
                                    Number(lineDbtAmt.replace(/[^-?0-9\.]/g,"")) === 0 &&
                                    Number(lineCrdtAmt.replace(/[^-?0-9\.]/g,"")) === 0
                                ) {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Transaction Amount for Row No. ' +
                                        i +
                                        " cannot be zero!</span></p>";
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_DebitAmnt").addClass(
                                        "rho-error"
                                    );
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_CreditAmnt").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchDetRow" + rndmNum + "_DebitAmnt").removeClass(
                                        "rho-error"
                                    );
                                    $(
                                        "#oneJrnlBatchDetRow" + rndmNum + "_CreditAmnt"
                                    ).removeClass("rho-error");
                                }
                                if (isVld === true) {
                                    slctdBatchDetLines =
                                        slctdBatchDetLines +
                                        $("#oneJrnlBatchDetRow" + rndmNum + "_TrnsLnID")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchDetRow" + rndmNum + "_TrnsSmryLnID")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchDetRow" + rndmNum + "_SlctdAmtBrkdwns")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchDetRow" + rndmNum + "_RefDoc")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineAccntID
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineDesc
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineCurNm
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineDbtAmt
                                            .replace(/[^-?0-9\.]/g,"")
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineCrdtAmt
                                            .replace(/[^-?0-9\.]/g,"")
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineTransDate
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "|";
                                }
                            }
                        }
                    }
                }
            });
        var slctdBatchSmryLines = "";
        $("#oneJrnlBatchSmryLinesTable")
            .find("tr")
            .each(function (i,el) {
                isVld = true;
                if (i > 0) {
                    if (typeof $(el).attr("id") === "undefined") {
                        /*Do Nothing*/
                    } else {
                        var rndmNum = $(el).attr("id").split("_")[1];
                        var lineDesc = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_LineDesc"
                        ).val();
                        var lineCurNm = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_TrnsCurNm"
                        ).val();
                        var lineEntrdAmt = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_EntrdAmt"
                        ).val();
                        var lineIncrsDcrs1 = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_IncrsDcrs1"
                        ).val();
                        var lineAccntID1 = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_AccountID1"
                        ).val();
                        var lineIncrsDcrs2 = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_IncrsDcrs2"
                        ).val();
                        var lineAccntID2 = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_AccountID2"
                        ).val();
                        var lineTransDate = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_TransDte"
                        ).val();
                        var lineTransDate = $(
                            "#oneJrnlBatchSmryRow" + rndmNum + "_TransDte"
                        ).val();
                        if (Number(lineAccntID1.replace(/[^-?0-9\.]/g,"")) > 0) {
                            if (lineDesc.trim() === "") {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Narration/Description for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_LineDesc").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_LineDesc").removeClass(
                                    "rho-error"
                                );
                            }
                            if (lineCurNm.trim() === "") {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Currency for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_TrnsCurNm").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_TrnsCurNm").removeClass(
                                    "rho-error"
                                );
                            }
                            if (lineTransDate.trim() === "") {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Transaction Date for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_TransDte").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_TransDte").removeClass(
                                    "rho-error"
                                );
                            }
                            if (
                                lineIncrsDcrs1.trim() === "" ||
                                lineIncrsDcrs2.trim() === ""
                            ) {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Increase/Decrease 1 and 2 for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_IncrsDcrs1").addClass(
                                    "rho-error"
                                );
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_IncrsDcrs2").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_IncrsDcrs1").removeClass(
                                    "rho-error"
                                );
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_IncrsDcrs2").removeClass(
                                    "rho-error"
                                );
                            }
                            if (Number(lineAccntID1.replace(/[^-?0-9\.]/g,"")) <= 0) {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">GL Charge Account for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_AccountNm1").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_AccountNm1").removeClass(
                                    "rho-error"
                                );
                            }
                            if (Number(lineAccntID2.replace(/[^-?0-9\.]/g,"")) <= 0) {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">GL Balancing Account for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_AccountNm2").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_AccountNm2").removeClass(
                                    "rho-error"
                                );
                            }
                            /*if (Number(lineAccntID1.replace(/[^-?0-9\.]/g, '')) === Number(lineAccntID2.replace(/[^-?0-9\.]/g, '')))
                                                                   {
                                                                   isVld = false;
                                                                   errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                                                   'font-weight:bold;color:red;">GL Balancing Account and Charger Account for Row No. ' + i + ' cannot be the same!<br/>Use Direct Debits/Credits Page/Tab for such kind of Trasactions!</span></p>';
                                                                   $('#oneJrnlBatchSmryRow' + rndmNum + '_AccountNm1').addClass('rho-error');
                                                                   $('#oneJrnlBatchSmryRow' + rndmNum + '_AccountNm2').addClass('rho-error');
                                                                   } else if (Number(lineAccntID1.replace(/[^-?0-9\.]/g, '')) > 0 && Number(lineAccntID2.replace(/[^-?0-9\.]/g, '')) > 0) {
                                                                   $('#oneJrnlBatchSmryRow' + rndmNum + '_AccountNm1').removeClass('rho-error');
                                                                   $('#oneJrnlBatchSmryRow' + rndmNum + '_AccountNm2').removeClass('rho-error');
                                                                   }*/
                            if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) === 0) {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Transaction Amount for Row No. ' +
                                    i +
                                    " cannot be zero!</span></p>";
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_EntrdAmt").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneJrnlBatchSmryRow" + rndmNum + "_EntrdAmt").removeClass(
                                    "rho-error"
                                );
                            }
                            if (isVld === true) {
                                slctdBatchSmryLines =
                                    slctdBatchSmryLines +
                                    $("#oneJrnlBatchSmryRow" + rndmNum + "_TrnsLnID")
                                        .val()
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    $("#oneJrnlBatchSmryRow" + rndmNum + "_FuncExchgRate")
                                        .val()
                                        .replace(/[^-?0-9\.]/g,"")
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    $("#oneJrnlBatchSmryRow" + rndmNum + "_SlctdAmtBrkdwns")
                                        .val()
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    $("#oneJrnlBatchSmryRow" + rndmNum + "_RefDoc")
                                        .val()
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineIncrsDcrs1
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineAccntID1
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineIncrsDcrs2
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineAccntID2
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineDesc
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineCurNm
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineEntrdAmt
                                        .replace(/[^-?0-9\.]/g,"")
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineTransDate
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "|";
                            }
                        }
                    }
                }
            });
        var slctdBatchEditLines = "";
        $("#oneJrnlBatchEditLinesTable")
            .find("tr")
            .each(function (i,el) {
                isVld = true;
                if (i > 0) {
                    if (typeof $(el).attr("id") === "undefined") {
                        /*Do Nothing*/
                    } else {
                        var rndmNum = $(el).attr("id").split("_")[1];
                        if (
                            typeof $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_AccountNm1"
                            ).val() === "undefined"
                        ) {
                            /*Do Nothing*/
                        } else {
                            var lineTrnsSmryLnID = $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_TrnsSmryLnID"
                            ).val();
                            var lineDesc = $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_LineDesc"
                            ).val();
                            var lineCurNm = $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_TrnsCurNm"
                            ).val();
                            var lineEntrdAmt = $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_EntrdAmt"
                            ).val();
                            var lineIncrsDcrs1 = $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_IncrsDcrs1"
                            ).val();
                            var lineAccntID1 = $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_AccountID1"
                            ).val();
                            var lineTransDate = $(
                                "#oneJrnlBatchEditRow" + rndmNum + "_TransDte"
                            ).val();
                            if (
                                Number(lineAccntID1.replace(/[^-?0-9\.]/g,"")) > 0 &&
                                Number(lineTrnsSmryLnID.replace(/[^-?0-9\.]/g,"")) <= 0
                            ) {
                                if (lineDesc.trim() === "") {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Narration/Description for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_LineDesc").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_LineDesc").removeClass(
                                        "rho-error"
                                    );
                                }
                                if (lineCurNm.trim() === "") {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Currency for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_TrnsCurNm").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $(
                                        "#oneJrnlBatchEditRow" + rndmNum + "_TrnsCurNm"
                                    ).removeClass("rho-error");
                                }
                                if (lineTransDate.trim() === "") {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Transaction Date for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_TransDte").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_TransDte").removeClass(
                                        "rho-error"
                                    );
                                }
                                if (lineIncrsDcrs1.trim() === "") {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Increase/Decrease 1 and 2 for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_IncrsDcrs1").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $(
                                        "#oneJrnlBatchEditRow" + rndmNum + "_IncrsDcrs1"
                                    ).removeClass("rho-error");
                                }
                                if (Number(lineAccntID1.replace(/[^-?0-9\.]/g,"")) <= 0) {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">GL Charge Account for Row No. ' +
                                        i +
                                        " cannot be empty!</span></p>";
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_AccountNm1").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $(
                                        "#oneJrnlBatchEditRow" + rndmNum + "_AccountNm1"
                                    ).removeClass("rho-error");
                                }
                                if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) === 0) {
                                    isVld = false;
                                    errMsg +=
                                        '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                        'font-weight:bold;color:red;">Transaction Amount for Row No. ' +
                                        i +
                                        " cannot be zero!</span></p>";
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_EntrdAmt").addClass(
                                        "rho-error"
                                    );
                                } else {
                                    $("#oneJrnlBatchEditRow" + rndmNum + "_EntrdAmt").removeClass(
                                        "rho-error"
                                    );
                                }
                                if (isVld === true) {
                                    slctdBatchEditLines =
                                        slctdBatchEditLines +
                                        $("#oneJrnlBatchEditRow" + rndmNum + "_TrnsLnID")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchEditRow" + rndmNum + "_TrnsSmryLnID")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchEditRow" + rndmNum + "_FuncExchgRate")
                                            .val()
                                            .replace(/[^-?0-9\.]/g,"")
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchEditRow" + rndmNum + "_AcntExchgRate")
                                            .val()
                                            .replace(/[^-?0-9\.]/g,"")
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchEditRow" + rndmNum + "_SlctdAmtBrkdwns")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        $("#oneJrnlBatchEditRow" + rndmNum + "_RefDoc")
                                            .val()
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineIncrsDcrs1
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineAccntID1
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineDesc
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineCurNm
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineEntrdAmt
                                            .replace(/[^-?0-9\.]/g,"")
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "~" +
                                        lineTransDate
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "|";
                                }
                            }
                        }
                    }
                }
            });
        if (rhotrim(errMsg,"; ") !== "") {
            bootbox.alert({
                title: "System Alert!",
                /*size: 'small',*/
                message: errMsg,
            });
            return false;
        }
        var dialog = bootbox.alert({
            title: "Save Batch",
            size: "small",
            message:
                '<p><i class="fa fa-spin fa-spinner"></i> Saving Batch...Please Wait...</p>',
            callback: function () { },
        });
        var formData = new FormData();
        formData.append("grp",6);
        formData.append("typ",1);
        formData.append("pg",2);
        formData.append("q","UPDATE");
        formData.append("actyp",1);
        formData.append("sbmtdTempltLovID",sbmtdTempltLovID);
        formData.append("sbmtdJrnlBatchID",sbmtdJrnlBatchID);
        formData.append("voidedJrnlBatchID",voidedJrnlBatchID);
        formData.append("jrnlBatchNum",jrnlBatchNum);
        formData.append("jrnlBatchCreationDate",jrnlBatchCreationDate);
        formData.append("jrnlBatchSource",jrnlBatchSource);
        formData.append("jrnlBatchDfltCurNm",jrnlBatchDfltCurNm);
        formData.append("jrnlBatchDfltTrnsDte",jrnlBatchDfltTrnsDte);
        formData.append("jrnlBatchDesc",jrnlBatchDesc);
        formData.append("jrnlBatchDfltBalsAcntID",jrnlBatchDfltBalsAcntID);
        formData.append("slctdBatchDetLines",slctdBatchDetLines);
        formData.append("slctdBatchSmryLines",slctdBatchSmryLines);
        formData.append("slctdBatchEditLines",slctdBatchEditLines);
        dialog.init(function () {
            getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                $body = $("body");
                $body.removeClass("mdlloading");
                $.ajax({
                    url: "index.php",
                    method: "POST",
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        setTimeout(function () {
                            dialog.find(".bootbox-body").html(data.message);
                            if (data.message.indexOf("Success") !== -1) {
                                sbmtdJrnlBatchID = data.sbmtdJrnlBatchID;
                                if (srcPAGE === "TBALS") {
                                    getOneJrnlBatchForm(
                                        sbmtdJrnlBatchID,
                                        11,
                                        "ReloadDialog",
                                        -1,
                                        "",
                                        "#accbRcnclJrnlTrnsLines"
                                    );
                                } else {
                                    getOneJrnlBatchForm(
                                        sbmtdJrnlBatchID,
                                        1,
                                        "ReloadDialog",
                                        extraPKeyID,
                                        extraPKeyType
                                    );
                                }
                            }
                        },500);
                    },
                    error: function (jqXHR,textStatus,errorThrown) {
                        console.log(textStatus + " " + errorThrown);
                        console.warn(jqXHR.responseText);
                    },
                });
            });
        });
    }
}

function saveJrnlRvrsBatchForm(funcCur,shdSbmt,extraPKeyID,extraPKeyType) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslJrnlBatchBtn");
    }
    if (typeof extraPKeyID === "undefined" || extraPKeyID === null) {
        extraPKeyID = -1;
    }
    if (typeof extraPKeyType === "undefined" || extraPKeyType === null) {
        extraPKeyType = "";
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdJrnlBatchID =
        typeof $("#sbmtdJrnlBatchID").val() === "undefined"
            ? -1
            : $("#sbmtdJrnlBatchID").val();
    var jrnlBatchDesc =
        typeof $("#jrnlBatchDesc").val() === "undefined"
            ? ""
            : $("#jrnlBatchDesc").val();
    var jrnlBatchDesc1 =
        typeof $("#jrnlBatchDesc1").val() === "undefined"
            ? ""
            : $("#jrnlBatchDesc1").val();
    var errMsg = "";
    if (sbmtdJrnlBatchID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Batch! Cannot Reverse</span></p>';
    }
    if (jrnlBatchDesc === "" || jrnlBatchDesc === jrnlBatchDesc1) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#jrnlBatchDesc").addClass("rho-error");
        $("#jrnlBatchDesc").attr("readonly",false);
        $("#fnlzeRvrslJrnlBatchBtn").attr("disabled",false);
    } else {
        $("#jrnlBatchDesc").removeClass("rho-error");
    }
    if (rhotrim(errMsg,"; ") !== "") {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var msgsTitle = "Journal Batch";
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' +
            msgsTitle +
            "?<br/>This action cannot be UNDONE!</p>";
    } else {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">INITIATE REVERSAL</span> of this ' +
            msgsTitle +
            '?<br/>After submission you must come back here to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE the REVERSAL</span>!</p>';
    }
    var dialog = bootbox.confirm({
        title: "Void " + msgsTitle + "?",
        size: "small",
        message: msgBody,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var msg = "Journal Batch";
                var dialog = bootbox.alert({
                    title: "Save " + msg,
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
                        msg +
                        "...Please Wait...</p>",
                    callback: function () {
                        sbmtdJrnlBatchID =
                            typeof $("#sbmtdJrnlBatchID").val() === "undefined"
                                ? -1
                                : $("#sbmtdJrnlBatchID").val();
                        if (sbmtdJrnlBatchID > 0) {
                            getOneJrnlBatchForm(
                                sbmtdJrnlBatchID,
                                1,
                                "ReloadDialog",
                                extraPKeyID,
                                extraPKeyType
                            );
                        }
                    },
                });
                dialog.init(function () {
                    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 6,
                                typ: 1,
                                pg: 2,
                                actyp: 1,
                                q: "VOID",
                                jrnlBatchDesc: jrnlBatchDesc,
                                sbmtdJrnlBatchID: sbmtdJrnlBatchID,
                                shdSbmt: shdSbmt,
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (
                                    /^[\],:{}\s]*$/.test(
                                        data
                                            .replace(/\\["\\\/bfnrtu]/g,"@")
                                            .replace(
                                                /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                                                "]"
                                            )
                                            .replace(/(?:^|:|,)(?:\s*\[)+/g,"")
                                    )
                                ) {
                                    obj = $.parseJSON(data);
                                    sbmtdJrnlBatchID = obj.sbmtdJrnlBatchID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdJrnlBatchID > 0) {
                                        $("#sbmtdJrnlBatchID").val(sbmtdJrnlBatchID);
                                    }
                                    if (msg.trim() === "") {
                                        msg = "Batch Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find(".bootbox-body").html(msg);
                                },500);
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            },
                        });
                    });
                });
            }
        },
    });
}

function delAccbJrnlBatch(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var msgPrt = "Journal Batch";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 2,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function afterJrnlBatchCurSlctn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var jrnlBatchAmountCrncy =
        typeof $("#jrnlBatchAmountCrncy").text() === "undefined"
            ? ""
            : $("#jrnlBatchAmountCrncy").text();
    var jrnlBatchDfltTrnsDte =
        typeof $("#jrnlBatchDfltTrnsDte").val() === "undefined"
            ? ""
            : $("#jrnlBatchDfltTrnsDte").val();
    var jrnlBatchDfltBalsAcntID =
        typeof $("#jrnlBatchDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#jrnlBatchDfltBalsAcntID").val();
    var lineAmountCrncy =
        typeof $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm1").text() === "undefined"
            ? jrnlBatchAmountCrncy
            : $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm1").text();
    var lineAmountAcntID1 =
        typeof $("#" + rowPrfxNm + rndmNum + "_AccountID1").val() === "undefined"
            ? jrnlBatchDfltBalsAcntID
            : $("#" + rowPrfxNm + rndmNum + "_AccountID1").val();
    var lineAmountTransDte =
        typeof $("#" + rowPrfxNm + rndmNum + "_TransDte").val() === "undefined"
            ? jrnlBatchDfltTrnsDte
            : $("#" + rowPrfxNm + rndmNum + "_TransDte").val();
    if (lineAmountCrncy !== "" && lineAmountTransDte !== "") {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append("grp",6);
            formData.append("typ",1);
            formData.append("pg",16);
            formData.append("q","VIEW");
            formData.append("vtyp",1);
            formData.append("lineAmountCrncy",lineAmountCrncy);
            formData.append("lineAmountAcntID1",lineAmountAcntID1);
            formData.append("lineAmountTransDte",lineAmountTransDte);
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.message.indexOf("Success") !== -1) {
                        $("#" + rowPrfxNm + rndmNum + "_FuncExchgRate").val(
                            data.FuncExchgRate
                        );
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    } else {
        $("#" + rowPrfxNm + rndmNum + "_FuncExchgRate").val(1.0);
    }
}

function getCurSlctnExchRate(destValEmntID,callBackFunc) {
    $("#allOtherInputData5").val(0);
    var jrnlBatchAmountCrncy =
        typeof $("#jrnlBatchAmountCrncy").text() === "undefined"
            ? ""
            : $("#jrnlBatchAmountCrncy").text();
    var jrnlBatchDfltTrnsDte =
        typeof $("#jrnlBatchDfltTrnsDte").val() === "undefined"
            ? ""
            : $("#jrnlBatchDfltTrnsDte").val();
    var jrnlBatchDfltBalsAcntID =
        typeof $("#jrnlBatchDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#jrnlBatchDfltBalsAcntID").val();
    var lineAmountCrncy = jrnlBatchAmountCrncy;
    var lineAmountAcntID1 = jrnlBatchDfltBalsAcntID;
    var lineAmountTransDte = jrnlBatchDfltTrnsDte;
    if (lineAmountCrncy !== "" && lineAmountTransDte !== "") {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append("grp",6);
            formData.append("typ",1);
            formData.append("pg",16);
            formData.append("q","VIEW");
            formData.append("vtyp",1);
            formData.append("lineAmountCrncy",lineAmountCrncy);
            formData.append("lineAmountAcntID1",lineAmountAcntID1);
            formData.append("lineAmountTransDte",lineAmountTransDte);
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.message.indexOf("Success") !== -1) {
                        $("#" + destValEmntID).val(data.FuncExchgRate);
                        callBackFunc();
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    } else {
        $("#" + destValEmntID).val(1.0);
    }
}

function getAccbPttyCash(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbPttyCashSrchFor").val() === "undefined"
            ? "%"
            : $("#accbPttyCashSrchFor").val();
    var srchIn =
        typeof $("#accbPttyCashSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbPttyCashSrchIn").val();
    var pageNo =
        typeof $("#accbPttyCashPageNo").val() === "undefined"
            ? 1
            : $("#accbPttyCashPageNo").val();
    var limitSze =
        typeof $("#accbPttyCashDsplySze").val() === "undefined"
            ? 10
            : $("#accbPttyCashDsplySze").val();
    var sortBy =
        typeof $("#accbPttyCashSortBy").val() === "undefined"
            ? ""
            : $("#accbPttyCashSortBy").val();
    var qShwUsrOnly = $("#accbPttyCashShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#accbPttyCashShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbPttyCash(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbPttyCash(actionText,slctr,linkArgs);
    }
}

function getOneAccbPttyCashForm(
    pKeyID,
    vwtype,
    actionTxt,
    accbPttyCashVchType
) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    if (
        typeof accbPttyCashVchType === "undefined" ||
        accbPttyCashVchType === null
    ) {
        accbPttyCashVchType = "Petty Cash Payments";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=3&vtyp=" +
        vwtype +
        "&sbmtdAccbPttyCashID=" +
        pKeyID +
        "&accbPttyCashVchType=" +
        accbPttyCashVchType;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalLg",
        actionTxt,
        "Petty Cash Voucher Details (ID:" + pKeyID + ")",
        "myFormsModalTitleLg",
        "myFormsModalBodyLg",
        function () {
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLg").css("overflow","auto");
                });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLg").css("overflow","auto");
                });
            $("#allOtherInputData99").val("0");
            $("#oneAccbPttyCashEDTForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#myFormsModalLg").off("hidden.bs.modal");
            $("#myFormsModalLg").one("hidden.bs.modal",function (e) {
                getAccbPttyCash("","#allmodules","grp=6&typ=1&pg=3&vtyp=0");
                $(e.currentTarget).unbind();
            });
            if (!$.fn.DataTable.isDataTable("#oneAccbPttyCashSmryLinesTable")) {
                var table1 = $("#oneAccbPttyCashSmryLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneAccbPttyCashSmryLinesTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
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
            $("#oneJrnlBatchSmryLinesTable tr").off("click");
            $("#oneJrnlBatchSmryLinesTable tr").click(function () {
                var rowIndex = $("#oneJrnlBatchSmryLinesTable tr").index(this);
                $("#allOtherInputData99").val(rowIndex);
            });
            calcAllAccbPttyCashSmryTtl();
        }
    );
}

function insertNewAccbPttyCashRows(tableElmntID,position,inptHtml) {
    var accbPttyCashInvcCur1 =
        typeof $("#accbPttyCashInvcCur1").text() === "undefined"
            ? ""
            : $("#accbPttyCashInvcCur1").text();
    var accbPttyCashDesc =
        typeof $("#accbPttyCashDesc").val() === "undefined"
            ? ""
            : $("#accbPttyCashDesc").val();
    var accbPttyCashDfltBalsAcnt =
        typeof $("#accbPttyCashDfltBalsAcnt").val() === "undefined"
            ? ""
            : $("#accbPttyCashDfltBalsAcnt").val();
    var accbPttyCashDfltBalsAcntID =
        typeof $("#accbPttyCashDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#accbPttyCashDfltBalsAcntID").val();
    for (var i = 0; i < 5; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
        $("#oneAccbPttyCashSmryRow" + nwRndm + "_TrnsCurNm").val(
            accbPttyCashInvcCur1
        );
        $("#oneAccbPttyCashSmryRow" + nwRndm + "_TrnsCurNm1").text(
            accbPttyCashInvcCur1
        );
        $("#oneAccbPttyCashSmryRow" + nwRndm + "_AccountID2").val(
            accbPttyCashDfltBalsAcntID
        );
        $("#oneAccbPttyCashSmryRow" + nwRndm + "_AccountNm2").val(
            accbPttyCashDfltBalsAcnt
        );
        $("#oneAccbPttyCashSmryRow" + nwRndm + "_LineDesc").val(accbPttyCashDesc);
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
    $(".form_date_tme")
        .datetimepicker({
            format: "dd-M-yyyy hh:ii:ss",
            language: "en",
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 0,
            maxView: 4,
            forceParse: true,
        })
        .on("hide",function (ev) {
            $("#myFormsModalLg").css("overflow","auto");
        });
    $(".form_date")
        .datetimepicker({
            format: "dd-M-yyyy",
            language: "en",
            weekStart: 0,
            todayBtn: true,
            autoclose: true,
            todayHighlight: true,
            keyboardNavigation: true,
            startView: 2,
            minView: 2,
            maxView: 4,
            forceParse: true,
        })
        .on("hide",function (ev) {
            $("#myFormsModalLg").css("overflow","auto");
        });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function afterAccbPttyCashAccSlctn() {
    var accbPttyCashDfltBalsAcntID =
        typeof $("#accbPttyCashDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#accbPttyCashDfltBalsAcntID").val();
    if (accbPttyCashDfltBalsAcntID > 0) {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append("grp",6);
            formData.append("typ",1);
            formData.append("pg",3);
            formData.append("q","VIEW");
            formData.append("vtyp",2);
            formData.append("accbPttyCashDfltBalsAcntID",accbPttyCashDfltBalsAcntID);
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.message.indexOf("Success") !== -1) {
                        $("#accbPttyCashLtstBals").text(data.accbPttyCashLtstBals);
                        $("#accbPttyCashUnpstdBals").text(data.accbPttyCashUnpstdBals);
                        $("#accbPttyCashNetBals").text(data.accbPttyCashNetBals);
                        $("#accbPttyCashNetBals").css("color",data.Style1);
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    } else {
        $("#accbPttyCashLtstBals").text("0.00");
        $("#accbPttyCashUnpstdBals").text("0.00");
        $("#accbPttyCashNetBals").text("0.00");
        $("#accbPttyCashNetBals").css("color","red");
    }
}

function lnkdEvntAccbPttyCashChng() {
    var accbPttyCashEvntDocTyp =
        typeof $("#accbPttyCashEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPttyCashEvntDocTyp").val();
    var lovNm = "";
    if (accbPttyCashEvntDocTyp === "None") {
        $("#accbPttyCashEvntCtgryLbl").attr("disabled","true");
        $("#accbPttyCashEvntCtgry").val("");
        $("#accbPttyCashEvntRgstr").val("");
        $("#accbPttyCashEvntRgstrID").val("-1");
        $("#accbPttyCashEvntRgstrLbl").attr("disabled","true");
    } else if (accbPttyCashEvntDocTyp === "Customer File Number") {
        $("#accbPttyCashEvntCtgryLbl").attr("disabled","true");
        $("#accbPttyCashEvntCtgry").val("Petty Cash");
        $("#accbPttyCashEvntRgstr").val("");
        $("#accbPttyCashEvntRgstrID").val("-1");
        $("#accbPttyCashEvntRgstrLbl").attr("disabled","true");
    } else {
        $("#accbPttyCashEvntCtgryLbl").removeAttr("disabled");
        $("#accbPttyCashEvntCtgry").val("");
        $("#accbPttyCashEvntRgstr").val("");
        $("#accbPttyCashEvntRgstrID").val("-1");
        $("#accbPttyCashEvntRgstrLbl").removeAttr("disabled");
    }
}

function getlnkdEvtAccbPCLovCtgry(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc
) {
    var accbPttyCashEvntDocTyp =
        typeof $("#accbPttyCashEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPttyCashEvntDocTyp").val();
    var accbPttyCashEvntRgstrID =
        typeof $("#accbPttyCashEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbPttyCashEvntRgstrID").val();
    if (
        accbPttyCashEvntDocTyp === "Attendance Register" ||
        accbPttyCashEvntDocTyp === "Project Management"
    ) {
        lovNm = "Event Cost Categories";
    } else if (accbPttyCashEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Run Stages";
    } else {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: "Category not Required for this Type",
        });
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc
    );
}

function getlnkdEvtAccbPCLovEvnt(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc
) {
    var accbPttyCashEvntDocTyp =
        typeof $("#accbPttyCashEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPttyCashEvntDocTyp").val();
    var accbPttyCashEvntRgstrID =
        typeof $("#accbPttyCashEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbPttyCashEvntRgstrID").val();
    if (accbPttyCashEvntDocTyp === "Attendance Register") {
        lovNm = "Attendance Registers";
    } else if (accbPttyCashEvntDocTyp === "Project Management") {
        return false;
    } else if (accbPttyCashEvntDocTyp === "Customer File Number") {
        lovNm = "Customer File Numbers";
    } else if (accbPttyCashEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Runs";
    } else {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: "Linked Document not Required for this Type",
        });
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc
    );
}

function calcAllAccbPttyCashSmryTtl() {
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    $("#oneAccbPttyCashSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var prfxName = $(el).attr("id").split("_")[0];
                    ttlRwAmount = (
                        $("#" + prfxName + rndmNum + "_EntrdAmt").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount = ttlAmount + Number(ttlRwAmount);
                }
            }
        });
    $("#myCptrdPttyCashValsTtlBtn").text(addCommas(ttlAmount.toFixed(2)));
    $("#myCptrdPttyCashValsTtlVal").val(ttlAmount.toFixed(2));
    $("#myCptrdPCJbSmryAmtTtlBtn").text(addCommas(ttlAmount.toFixed(2)));
    $("#myCptrdPCJbSmryAmtTtlVal").val(ttlAmount.toFixed(2));
}

function getOneAccbPttyCashDocsForm(pKeyID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=3&vtyp=" + vwtype + "&sbmtdAccbPttyCashID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        "ShowDialog",
        "Petty Cash Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            var table1 = $("#attchdPttyCashDocsTable").DataTable({
                paging: false,
                ordering: false,
                info: false,
                bFilter: false,
                scrollX: false,
            });
            $("#attchdPttyCashDocsTable").wrap('<div class="dataTables_scroll"/>');
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdPttyCashDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function uploadFileToPttyCashDocs(
    inptElmntID,
    attchIDElmntID,
    docNmElmntID,
    sbmtdHdrID,
    rowIDAttrb
) {
    var docCtrgrName = $("#" + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    $("#" + inptElmntID).val("");
    $("#" + inptElmntID).off("change");
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToPttyCashDocs(
            input.files[0],
            docNmElmntID,
            attchIDElmntID,
            sbmtdHdrID,
            function (data) {
                $("#" + attchIDElmntID).val(data.attchID);
                var dialog = bootbox.alert({
                    title: "Server Response!",
                    size: "small",
                    message: '<div id="myInformation">' + data.message + "</div>",
                    callback: function () {
                        if (data.message.indexOf("Success") !== -1) {
                        }
                    },
                });
            }
        );
    });
    performFileClick(inptElmntID);
}

function sendFileToPttyCashDocs(
    file,
    docNmElmntID,
    attchIDElmntID,
    sbmtdHdrID,
    callBackFunc
) {
    var data1 = new FormData();
    data1.append("daPttyCashAttchmnt",file);
    data1.append("grp",6);
    data1.append("typ",1);
    data1.append("pg",3);
    data1.append("q","UPDATE");
    data1.append("actyp",2);
    data1.append("docCtrgrName",$("#" + docNmElmntID).val());
    data1.append("attchmentID",$("#" + attchIDElmntID).val());
    data1.append("sbmtdAccbPttyCashID",sbmtdHdrID);
    var dialog1 = bootbox.alert({
        title: "Uploading File...",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>',
    });
    dialog1.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    dialog1.modal("hide");
                    callBackFunc(data);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAttchdPttyCashDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var srchFor =
        typeof $("#attchdPttyCashDocsSrchFor").val() === "undefined"
            ? "%"
            : $("#attchdPttyCashDocsSrchFor").val();
    var srchIn =
        typeof $("#attchdPttyCashDocsSrchIn").val() === "undefined"
            ? "Both"
            : $("#attchdPttyCashDocsSrchIn").val();
    var pageNo =
        typeof $("#attchdPttyCashDocsPageNo").val() === "undefined"
            ? 1
            : $("#attchdPttyCashDocsPageNo").val();
    var limitSze =
        typeof $("#attchdPttyCashDocsDsplySze").val() === "undefined"
            ? 10
            : $("#attchdPttyCashDocsDsplySze").val();
    var sortBy =
        typeof $("#attchdPttyCashDocsSortBy").val() === "undefined"
            ? ""
            : $("#attchdPttyCashDocsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    doAjaxWthCallBck(
        linkArgs,
        "myFormsModaly",
        actionDialog,
        "Petty Cash Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            if (!$.fn.DataTable.isDataTable("#attchdPttyCashDocsTable")) {
                var table1 = $("#attchdPttyCashDocsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#attchdPttyCashDocsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdPttyCashDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function enterKeyFuncAttchdPttyCashDocs(
    e,
    actionText,
    slctr,
    linkArgs,
    actionDialog
) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdPttyCashDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdPttyCashDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID =
        typeof $("#sbmtdAccbPttyCashID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbPttyCashID").val();
    var docNum =
        typeof $("#accbPttyCashDocNum").val() === "undefined"
            ? ""
            : $("#accbPttyCashDocNum").val();
    var pKeyID = -1;
    if (
        typeof $("#attchdPttyCashDocsRow" + rndmNum + "_AttchdDocsID").val() ===
        "undefined"
    ) {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $("#attchdPttyCashDocsRow" + rndmNum + "_AttchdDocsID").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Document?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Document?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Document?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 3,
                                    q: "DELETE",
                                    actyp: 5,
                                    attchmentID: pKeyID,
                                    sbmtdHdrID: sbmtdHdrID,
                                    docTrnsNum: docNum,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbPttyCash(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Petty Cash Document";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 3,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbPttyCashDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#accbPttyCashDocNum").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Petty Cash Line";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 3,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function saveAccbPttyCashForm(funcur,shdSbmt) {
    calcAllAccbPttyCashSmryTtl();
    var sbmtdAccbPttyCashID =
        typeof $("#sbmtdAccbPttyCashID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbPttyCashID").val();
    var accbPttyCashDocNum =
        typeof $("#accbPttyCashDocNum").val() === "undefined"
            ? ""
            : $("#accbPttyCashDocNum").val();
    var accbPttyCashDfltTrnsDte =
        typeof $("#accbPttyCashDfltTrnsDte").val() === "undefined"
            ? ""
            : $("#accbPttyCashDfltTrnsDte").val();
    var accbPttyCashVchType =
        typeof $("#accbPttyCashVchType").val() === "undefined"
            ? ""
            : $("#accbPttyCashVchType").val();
    var accbPttyCashInvcCur =
        typeof $("#accbPttyCashInvcCur").val() === "undefined"
            ? ""
            : $("#accbPttyCashInvcCur").val();
    var accbPttyCashTtlAmnt =
        typeof $("#accbPttyCashTtlAmnt").val() === "undefined"
            ? "0.00"
            : $("#accbPttyCashTtlAmnt").val();
    var accbPttyCashEvntDocTyp =
        typeof $("#accbPttyCashEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPttyCashEvntDocTyp").val();
    var accbPttyCashEvntCtgry =
        typeof $("#accbPttyCashEvntCtgry").val() === "undefined"
            ? ""
            : $("#accbPttyCashEvntCtgry").val();
    var accbPttyCashEvntRgstrID =
        typeof $("#accbPttyCashEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbPttyCashEvntCtgry").val();
    var accbPttyCashSpplrID =
        typeof $("#accbPttyCashSpplrID").val() === "undefined"
            ? "-1"
            : $("#accbPttyCashSpplrID").val();
    var accbPttyCashSpplrSiteID =
        typeof $("#accbPttyCashSpplrSiteID").val() === "undefined"
            ? ""
            : $("#accbPttyCashSpplrSiteID").val();
    var accbPttyCashDesc =
        typeof $("#accbPttyCashDesc").val() === "undefined"
            ? ""
            : $("#accbPttyCashDesc").val();
    var accbPttyCashDfltBalsAcntID =
        typeof $("#accbPttyCashDfltBalsAcntID").val() === "undefined"
            ? -1
            : $("#accbPttyCashDfltBalsAcntID").val();
    var myCptrdPttyCashValsTtlVal =
        typeof $("#myCptrdPttyCashValsTtlVal").val() === "undefined"
            ? 0
            : $("#myCptrdPttyCashValsTtlVal").val();
    var errMsg = "";
    if (accbPttyCashDocNum.trim() === "" || accbPttyCashVchType.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number/Type cannot be empty!</span></p>';
    }
    if (accbPttyCashDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
    }
    if (
        accbPttyCashDfltTrnsDte.trim() === "" ||
        accbPttyCashInvcCur.trim() === ""
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Batch Date/Currency cannot be empty!</span></p>';
    }
    accbPttyCashTtlAmnt = fmtAsNumber("accbPttyCashTtlAmnt").toFixed(2);
    myCptrdPttyCashValsTtlVal = fmtAsNumber("myCptrdPttyCashValsTtlVal").toFixed(
        2
    );
    if (myCptrdPttyCashValsTtlVal !== accbPttyCashTtlAmnt) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    $("#oneAccbPttyCashSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var lineDesc = $(
                        "#oneAccbPttyCashSmryRow" + rndmNum + "_LineDesc"
                    ).val();
                    var lineCurNm = $(
                        "#oneAccbPttyCashSmryRow" + rndmNum + "_TrnsCurNm1"
                    ).text();
                    var lineEntrdAmt = $(
                        "#oneAccbPttyCashSmryRow" + rndmNum + "_EntrdAmt"
                    ).val();
                    var lineIncrsDcrs1 = $(
                        "#oneAccbPttyCashSmryRow" + rndmNum + "_IncrsDcrs1"
                    ).val();
                    var lineAccntID1 = $(
                        "#oneAccbPttyCashSmryRow" + rndmNum + "_AccountID1"
                    ).val();
                    if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) !== 0) {
                        if (lineDesc.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Narration/Description for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_LineDesc").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_LineDesc").removeClass(
                                "rho-error"
                            );
                        }
                        if (lineCurNm.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Currency for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_TrnsCurNm").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_TrnsCurNm").removeClass(
                                "rho-error"
                            );
                        }
                        if (lineIncrsDcrs1.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Increase/Decrease 1 for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_IncrsDcrs1").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbPttyCashSmryRow" + rndmNum + "_IncrsDcrs1"
                            ).removeClass("rho-error");
                        }
                        if (Number(lineAccntID1.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">GL Charge Account for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_AccountNm1").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbPttyCashSmryRow" + rndmNum + "_AccountNm1"
                            ).removeClass("rho-error");
                        }
                        if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) === 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Transaction Amount for Row No. ' +
                                i +
                                " cannot be zero!</span></p>";
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_EntrdAmt").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#oneAccbPttyCashSmryRow" + rndmNum + "_EntrdAmt").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === true) {
                            slctdDetTransLines =
                                slctdDetTransLines +
                                $("#oneAccbPttyCashSmryRow" + rndmNum + "_TrnsLnID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbPttyCashSmryRow" + rndmNum + "_SlctdAmtBrkdwns")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbPttyCashSmryRow" + rndmNum + "_RefDoc")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineIncrsDcrs1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineAccntID1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineDesc.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineCurNm.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineEntrdAmt
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Petty Cash",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Petty Cash...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",3);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("sbmtdAccbPttyCashID",sbmtdAccbPttyCashID);
    formData.append("accbPttyCashDocNum",accbPttyCashDocNum);
    formData.append("accbPttyCashDfltTrnsDte",accbPttyCashDfltTrnsDte);
    formData.append("accbPttyCashVchType",accbPttyCashVchType);
    formData.append("accbPttyCashInvcCur",accbPttyCashInvcCur);
    formData.append("accbPttyCashTtlAmnt",accbPttyCashTtlAmnt);
    formData.append("accbPttyCashEvntDocTyp",accbPttyCashEvntDocTyp);
    formData.append("accbPttyCashEvntCtgry",accbPttyCashEvntCtgry);
    formData.append("accbPttyCashEvntRgstrID",accbPttyCashEvntRgstrID);
    formData.append("accbPttyCashSpplrID",accbPttyCashSpplrID);
    formData.append("accbPttyCashSpplrSiteID",accbPttyCashSpplrSiteID);
    formData.append("accbPttyCashDfltBalsAcntID",accbPttyCashDfltBalsAcntID);
    formData.append("accbPttyCashDesc",accbPttyCashDesc);
    formData.append("shdSbmt",shdSbmt);
    formData.append("slctdDetTransLines",slctdDetTransLines);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdAccbPttyCashID = data.sbmtdAccbPttyCashID;
                            getOneAccbPttyCashForm(sbmtdAccbPttyCashID,1,"ReloadDialog");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function saveAccbPttyCashRvrslForm(funcCur,shdSbmt) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslAccbPttyCashBtn");
    }

    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdAccbPttyCashID =
        typeof $("#sbmtdAccbPttyCashID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbPttyCashID").val();
    var accbPttyCashDesc =
        typeof $("#accbPttyCashDesc").val() === "undefined"
            ? ""
            : $("#accbPttyCashDesc").val();
    var accbPttyCashDesc1 =
        typeof $("#accbPttyCashDesc1").val() === "undefined"
            ? ""
            : $("#accbPttyCashDesc1").val();
    var errMsg = "";
    if (sbmtdAccbPttyCashID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (accbPttyCashDesc === "" || accbPttyCashDesc === accbPttyCashDesc1) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#accbPttyCashDesc").addClass("rho-error");
        $("#accbPttyCashDesc").attr("readonly",false);
        $("#fnlzeRvrslAccbPttyCashBtn").attr("disabled",false);
    } else {
        $("#accbPttyCashDesc").removeClass("rho-error");
    }
    if (rhotrim(errMsg,"; ") !== "") {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var msgsTitle = "Petty Cash Voucher";
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' +
            msgsTitle +
            "?<br/>This action cannot be UNDONE!</p>";
    } else {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">INITIATE REVERSAL</span> of this ' +
            msgsTitle +
            '?<br/>After submission you must come back here to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE the REVERSAL</span>!</p>';
    }
    var dialog = bootbox.confirm({
        title: "Void " + msgsTitle + "?",
        size: "small",
        message: msgBody,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var msg = "Petty Cash Voucher";
                var dialog = bootbox.alert({
                    title: "Save " + msg,
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
                        msg +
                        "...Please Wait...</p>",
                    callback: function () {
                        sbmtdAccbPttyCashID =
                            typeof $("#sbmtdAccbPttyCashID").val() === "undefined"
                                ? -1
                                : $("#sbmtdAccbPttyCashID").val();
                        if (sbmtdAccbPttyCashID > 0) {
                            getOneAccbPttyCashForm(sbmtdAccbPttyCashID,1,"ReloadDialog");
                        }
                    },
                });
                dialog.init(function () {
                    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 6,
                                typ: 1,
                                pg: 3,
                                actyp: 1,
                                q: "VOID",
                                accbPttyCashDesc: accbPttyCashDesc,
                                sbmtdAccbPttyCashID: sbmtdAccbPttyCashID,
                                shdSbmt: shdSbmt,
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (
                                    /^[\],:{}\s]*$/.test(
                                        data
                                            .replace(/\\["\\\/bfnrtu]/g,"@")
                                            .replace(
                                                /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                                                "]"
                                            )
                                            .replace(/(?:^|:|,)(?:\s*\[)+/g,"")
                                    )
                                ) {
                                    obj = $.parseJSON(data);
                                    sbmtdAccbPttyCashID = obj.sbmtdAccbPttyCashID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdAccbPttyCashID > 0) {
                                        $("#sbmtdAccbPttyCashID").val(sbmtdAccbPttyCashID);
                                    }
                                    if (msg.trim() === "") {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find(".bootbox-body").html(msg);
                                },500);
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            },
                        });
                    });
                });
            }
        },
    });
}

function getAllVmsGLIntrfcs(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#allVmsGLIntrfcsSrchFor").val() === "undefined"
            ? "%"
            : $("#allVmsGLIntrfcsSrchFor").val();
    var srchIn =
        typeof $("#allVmsGLIntrfcsSrchIn").val() === "undefined"
            ? "Both"
            : $("#allVmsGLIntrfcsSrchIn").val();
    var pageNo =
        typeof $("#allVmsGLIntrfcsPageNo").val() === "undefined"
            ? 1
            : $("#allVmsGLIntrfcsPageNo").val();
    var limitSze =
        typeof $("#allVmsGLIntrfcsDsplySze").val() === "undefined"
            ? 10
            : $("#allVmsGLIntrfcsDsplySze").val();
    var sortBy =
        typeof $("#allVmsGLIntrfcsSortBy").val() === "undefined"
            ? ""
            : $("#allVmsGLIntrfcsSortBy").val();
    var qStrtDte =
        typeof $("#allVmsGLIntrfcsStrtDate").val() === "undefined"
            ? ""
            : $("#allVmsGLIntrfcsStrtDate").val();
    var qEndDte =
        typeof $("#allVmsGLIntrfcsEndDate").val() === "undefined"
            ? ""
            : $("#allVmsGLIntrfcsEndDate").val();
    var qNotSentToGl = $("#allVmsGLIntrfcsSntToGl:checked").length > 0;
    var qUnbalncdOnly = $("#allVmsGLIntrfcsUnbalncd:checked").length > 0;
    var qUsrGnrtd = $("#allVmsGLIntrfcsUsrTrns:checked").length > 0;
    var qLowVal =
        typeof $("#allVmsGLIntrfcsLowVal").val() === "undefined"
            ? 0
            : $("#allVmsGLIntrfcsLowVal").val();
    var qHighVal =
        typeof $("#allVmsGLIntrfcsHighVal").val() === "undefined"
            ? 0
            : $("#allVmsGLIntrfcsHighVal").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qStrtDte=" +
        qStrtDte +
        "&qEndDte=" +
        qEndDte +
        "&qNotSentToGl=" +
        qNotSentToGl +
        "&qUnbalncdOnly=" +
        qUnbalncdOnly +
        "&qUsrGnrtd=" +
        qUsrGnrtd +
        "&qLowVal=" +
        qLowVal +
        "&qHighVal=" +
        qHighVal;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAllVmsGLIntrfcs(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllVmsGLIntrfcs(actionText,slctr,linkArgs);
    }
}

function getAccbTransSrch(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbTransSrchSrchFor").val() === "undefined"
            ? "%"
            : $("#accbTransSrchSrchFor").val();
    var srchIn =
        typeof $("#accbTransSrchSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbTransSrchSrchIn").val();
    var pageNo =
        typeof $("#accbTransSrchPageNo").val() === "undefined"
            ? 1
            : $("#accbTransSrchPageNo").val();
    var limitSze =
        typeof $("#accbTransSrchDsplySze").val() === "undefined"
            ? 10
            : $("#accbTransSrchDsplySze").val();
    var sortBy =
        typeof $("#accbTransSrchSortBy").val() === "undefined"
            ? ""
            : $("#accbTransSrchSortBy").val();
    var qStrtDte =
        typeof $("#accbTransSrchStrtDate").val() === "undefined"
            ? ""
            : $("#accbTransSrchStrtDate").val();
    var qEndDte =
        typeof $("#accbTransSrchEndDate").val() === "undefined"
            ? ""
            : $("#accbTransSrchEndDate").val();
    var qShwPstdOnly = $("#accbTransSrchShwPstdOnly:checked").length > 0;
    var qShwIntrfc = $("#accbTransSrchShwIntrfc:checked").length > 0;
    var qLowVal =
        typeof $("#accbTransSrchLowVal").val() === "undefined"
            ? 0
            : $("#accbTransSrchLowVal").val();
    var qHighVal =
        typeof $("#accbTransSrchHighVal").val() === "undefined"
            ? 0
            : $("#accbTransSrchHighVal").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwPstdOnly=" +
        qShwPstdOnly +
        "&qShwIntrfc=" +
        qShwIntrfc +
        "&qStrtDte=" +
        qStrtDte +
        "&qEndDte=" +
        qEndDte +
        "&qLowVal=" +
        qLowVal +
        "&qHighVal=" +
        qHighVal;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbTransSrch(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbTransSrch(actionText,slctr,linkArgs);
    }
}

function getAccbTransSrchDet(
    srchFor,
    srchIn,
    qShwPstdOnly,
    qShwIntrfc,
    qStrtDte,
    qEndDte,
    pKeyTitle,
    actionTxt,
    callBackFunc
) {
    if (typeof callBackFunc === "undefined" || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }

    var lnkArgs = "grp=6&typ=1&pg=4&vtyp=1";
    lnkArgs =
        lnkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&qShwPstdOnly=" +
        qShwPstdOnly +
        "&qShwIntrfc=" +
        qShwIntrfc +
        "&qStrtDte=" +
        qStrtDte +
        "&qEndDte=" +
        qEndDte;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalLx",
        actionTxt,
        pKeyTitle,
        "myFormsModalLxTitle",
        "myFormsModalLxBody",
        function () {
            $("#accbTransSrchDiagForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable("#accbTransSrchDiagHdrsTable")) {
                var table1 = $("#accbTransSrchDiagHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTransSrchDiagHdrsTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }

            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLx").css("overflow","auto");
                });
            $("#allOtherInputData99").val(0);
            $("#myFormsModalLx").off("hidden.bs.modal");
            $("#myFormsModalLx").one("hidden.bs.modal",function (e) {
                $("#myFormsModalLxTitle").html("");
                $("#myFormsModalLxBody").html("");
                callBackFunc();
                $(e.currentTarget).unbind();
            });
        }
    );
}

function getAccbTransSrchDiag(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbTransSrchDiagSrchFor").val() === "undefined"
            ? "%"
            : $("#accbTransSrchDiagSrchFor").val();
    var srchIn =
        typeof $("#accbTransSrchDiagSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbTransSrchDiagSrchIn").val();
    var pageNo =
        typeof $("#accbTransSrchDiagPageNo").val() === "undefined"
            ? 1
            : $("#accbTransSrchDiagPageNo").val();
    var limitSze =
        typeof $("#accbTransSrchDiagDsplySze").val() === "undefined"
            ? 10
            : $("#accbTransSrchDiagDsplySze").val();
    var sortBy =
        typeof $("#accbTransSrchDiagSortBy").val() === "undefined"
            ? ""
            : $("#accbTransSrchDiagSortBy").val();
    var qStrtDte =
        typeof $("#accbTransSrchDiagStrtDate").val() === "undefined"
            ? ""
            : $("#accbTransSrchDiagStrtDate").val();
    var qEndDte =
        typeof $("#accbTransSrchDiagEndDate").val() === "undefined"
            ? ""
            : $("#accbTransSrchDiagEndDate").val();
    var qShwPstdOnly = $("#accbTransSrchDiagShwPstdOnly:checked").length > 0;
    var qShwIntrfc = $("#accbTransSrchDiagShwIntrfc:checked").length > 0;
    var qLowVal =
        typeof $("#accbTransSrchDiagLowVal").val() === "undefined"
            ? 0
            : $("#accbTransSrchDiagLowVal").val();
    var qHighVal =
        typeof $("#accbTransSrchDiagHighVal").val() === "undefined"
            ? 0
            : $("#accbTransSrchDiagHighVal").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwPstdOnly=" +
        qShwPstdOnly +
        "&qShwIntrfc=" +
        qShwIntrfc +
        "&qStrtDte=" +
        qStrtDte +
        "&qEndDte=" +
        qEndDte +
        "&qLowVal=" +
        qLowVal +
        "&qHighVal=" +
        qHighVal;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbTransSrchDiag(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbTransSrchDiag(actionText,slctr,linkArgs);
    }
}

function getAccbCashBreakdown(
    pKeyID,
    actionTxt,
    pKeyTitle,
    vtypActn,
    sbmtdlovName,
    trnsAmntElmntID,
    trnsAmtBrkdwnSaveElID
) {
    var slctdBrkdwnLines = "";
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    if (
        typeof trnsAmtBrkdwnSaveElID === "undefined" ||
        trnsAmtBrkdwnSaveElID === null
    ) {
        trnsAmtBrkdwnSaveElID = "";
    }
    if (trnsAmtBrkdwnSaveElID.trim() !== "") {
        slctdBrkdwnLines = $("#" + trnsAmtBrkdwnSaveElID).val();
    }
    var lnkArgs = "grp=6&typ=1&pg=4&vtyp=2";
    lnkArgs =
        lnkArgs +
        "&vtypActn=" +
        vtypActn +
        "&sbmtdTrnsID=" +
        pKeyID +
        "&sbmtdlovName=" +
        sbmtdlovName +
        "&trnsAmntElmntID=" +
        trnsAmntElmntID +
        "&trnsAmtBrkdwnSaveElID=" +
        trnsAmtBrkdwnSaveElID +
        "&slctdBrkdwnLines=" +
        slctdBrkdwnLines;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        actionTxt,
        pKeyTitle,
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            $("#accbTransBrkdwnDiagForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable("#accbTransBrkdwnDiagHdrsTable")) {
                var table1 = $("#accbTransBrkdwnDiagHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTransBrkdwnDiagHdrsTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            $(".acbBrkdwnDesc").focus(function () {
                $(this).select();
            });
            $(".acbBrkdwnQTY").focus(function () {
                $(this).select();
            });
            $(".acbBrkdwnUVl").focus(function () {
                $(this).select();
            });
            $(".acbBrkdwnTtl").focus(function () {
                $(this).select();
            });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModaly").css("overflow","auto");
                });
            $("#allOtherInputData99").val(0);
            $("#myFormsModaly").off("hidden.bs.modal");
            $("#myFormsModaly").one("hidden.bs.modal",function (e) {
                $("#myFormsModalyTitle").html("");
                $("#myFormsModalyBody").html("");
                $(e.currentTarget).unbind();
            });
        }
    );
}

function applyNewAccbCashBrkdwn(
    modalID,
    trnsAmntElmntID,
    trnsAmtBrkdwnSaveElID
) {
    var slctdBrkdwnTtl = 0;
    var slctdBrkdwnLines = "";
    $("#accbTransBrkdwnDiagHdrsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowUnitAmount = $(
                        "#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnUnitVal"
                    ).val();
                    var rowQty = $(
                        "#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY"
                    ).val();
                    var rowTtl =
                        Number(rowQty.replace(/[^-?0-9\.]/g,"")) *
                        Number(rowUnitAmount.replace(/[^-?0-9\.]/g,""));
                    $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnTtl").val(
                        addCommas(rowTtl.toFixed(2))
                    );
                    slctdBrkdwnTtl = slctdBrkdwnTtl + rowTtl;
                    slctdBrkdwnLines =
                        slctdBrkdwnLines +
                        $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnLnID")
                            .val()
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_LnkdPValID")
                            .val()
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnDesc")
                            .val()
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnUnitVal")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnTtl")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "|";
                }
            }
        });
    $("#" + trnsAmntElmntID).val(addCommas(slctdBrkdwnTtl.toFixed(2)));
    $("#" + trnsAmtBrkdwnSaveElID).val(slctdBrkdwnLines);
    if (trnsAmtBrkdwnSaveElID.indexOf("oneJrnlBatchEditRow") >= 0) {
        calcAllJrnlBatchEditTtl();
    } else if (trnsAmtBrkdwnSaveElID.indexOf("oneJrnlBatchSmryRow") >= 0) {
        calcAllJrnlBatchSmryTtl();
    } else if (trnsAmtBrkdwnSaveElID.indexOf("oneJrnlBatchDetRow") >= 0) {
        calcAllJrnlBatchDetTtl();
    } else if (trnsAmtBrkdwnSaveElID.indexOf("oneAccbPyblsInvcSmry") >= 0) {
        calcAllAccbPyblsInvcSmryTtl();
    } else if (trnsAmtBrkdwnSaveElID.indexOf("oneAccbPttyCashSmryRow") >= 0) {
        calcAllAccbPttyCashSmryTtl();
    } else if (trnsAmtBrkdwnSaveElID.indexOf("oneAccbRcvblsInvcSmry") >= 0) {
        calcAllAccbRcvblsInvcSmryTtl();
    }
    $("#" + modalID).modal("hide");
}

function calcAllAccbCashBrkdwn() {
    var slctdBrkdwnTtl = 0;
    $("#accbTransBrkdwnDiagHdrsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowUnitAmount = $(
                        "#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnUnitVal"
                    ).val();
                    var rowQty = $(
                        "#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY"
                    ).val();
                    var rowTtl =
                        Number(rowQty.replace(/[^-?0-9\.]/g,"")) *
                        Number(rowUnitAmount.replace(/[^-?0-9\.]/g,""));
                    slctdBrkdwnTtl = slctdBrkdwnTtl + rowTtl;
                    $("#accbTransBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnTtl").val(
                        addCommas(rowTtl.toFixed(2))
                    );
                }
            }
        });
    /*alert(slctdBrkdwnTtl);*/
    $("#myTransAmtBrkdwnTtlBtn").text(addCommas(slctdBrkdwnTtl.toFixed(2)));
    $("#myTransAmtBrkdwnTtlVal").val(slctdBrkdwnTtl.toFixed(2));
}

function delAccbTrnsAmntBrkdwn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (
        typeof $("#" + rowPrfxNm + rndmNum + "_BrkdwnLnID").val() === "undefined"
    ) {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_BrkdwnLnID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_BrkdwnDesc").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Amount Breakdown";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 4,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbJrnlBatchDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#jrnlBatchNum").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Journal Line";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 2,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbJrnlBatchSmmryLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#jrnlBatchNum").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Journal Line";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 2,
                                    q: "DELETE",
                                    actyp: 4,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function getAccbBdgts(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbBdgtsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbBdgtsSrchFor").val();
    var srchIn =
        typeof $("#accbBdgtsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbBdgtsSrchIn").val();
    var pageNo =
        typeof $("#accbBdgtsPageNo").val() === "undefined"
            ? 1
            : $("#accbBdgtsPageNo").val();
    var limitSze =
        typeof $("#accbBdgtsDsplySze").val() === "undefined"
            ? 10
            : $("#accbBdgtsDsplySze").val();
    var sortBy =
        typeof $("#accbBdgtsSortBy").val() === "undefined"
            ? ""
            : $("#accbBdgtsSortBy").val();
    var qShwUsrOnly = $("#accbBdgtsShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#accbBdgtsShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbBdgts(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbBdgts(actionText,slctr,linkArgs);
    }
}

function getAccbBdgtDets(actionText,slctr,linkArgs,accbBudgetID) {
    if (typeof accbBudgetID === "undefined" || accbBudgetID === null) {
        accbBudgetID = -1;
    }
    var srchFor =
        typeof $("#accbBdgtDetsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbBdgtDetsSrchFor").val();
    var srchIn =
        typeof $("#accbBdgtDetsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbBdgtDetsSrchIn").val();
    var pageNo =
        typeof $("#accbBdgtDetsPageNo").val() === "undefined"
            ? 1
            : $("#accbBdgtDetsPageNo").val();
    var limitSze =
        typeof $("#accbBdgtDetsDsplySze").val() === "undefined"
            ? 15
            : $("#accbBdgtDetsDsplySze").val();
    var sortBy =
        typeof $("#accbBdgtDetsSortBy").val() === "undefined"
            ? ""
            : $("#accbBdgtDetsSortBy").val();
    var qShwUsrOnly = $("#accbBdgtDetsShwUsrOnly:checked").length > 0;
    var qShwNonZeroOnly = $("#accbBdgtDetsNonZeroOnly:checked").length > 0;

    var accbBudgetID1 =
        typeof $("#accbBudgetID").val() === "undefined"
            ? "-1"
            : $("#accbBudgetID").val();
    var accbBudgetNm1 =
        typeof $("#accbBudgetNm").val() === "undefined"
            ? ""
            : $("#accbBudgetNm").val();
    var accbSbmtdBudgetID =
        typeof $("#accbSbmtdBudgetID").val() === "undefined"
            ? "-1"
            : $("#accbSbmtdBudgetID").val();
    var accbSbmtdBudgetNm =
        typeof $("#accbSbmtdBudgetNm").val() === "undefined"
            ? ""
            : $("#accbSbmtdBudgetNm").val();
    if (Number(accbSbmtdBudgetID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        accbSbmtdBudgetID = accbBudgetID1;
        accbSbmtdBudgetNm = accbBudgetNm1;
    }
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    if (!(typeof $("#accbBdgtDetsNonZeroOnly").val() === "undefined")) {
        linkArgs = linkArgs + "&qShwNonZeroOnly=" + qShwNonZeroOnly;
    }
    if (accbBudgetID <= 0) {
        linkArgs =
            linkArgs +
            "&accbSbmtdBudgetID=" +
            accbSbmtdBudgetID +
            "&accbSbmtdBudgetNm=" +
            accbSbmtdBudgetNm;
    }
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbBdgtDets(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbBdgtDets(actionText,slctr,linkArgs);
    }
}

function getAccbBdgtFurthDets(actionText,slctr,linkArgs,accbBudgetID) {
    if (typeof accbBudgetID === "undefined" || accbBudgetID === null) {
        accbBudgetID = -1;
    }
    var srchFor =
        typeof $("#accbBdgtFurthDetsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbBdgtFurthDetsSrchFor").val();
    var srchIn =
        typeof $("#accbBdgtFurthDetsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbBdgtFurthDetsSrchIn").val();
    var pageNo =
        typeof $("#accbBdgtFurthDetsPageNo").val() === "undefined"
            ? 1
            : $("#accbBdgtFurthDetsPageNo").val();
    var limitSze =
        typeof $("#accbBdgtFurthDetsDsplySze").val() === "undefined"
            ? 15
            : $("#accbBdgtFurthDetsDsplySze").val();
    var sortBy =
        typeof $("#accbBdgtFurthDetsSortBy").val() === "undefined"
            ? ""
            : $("#accbBdgtFurthDetsSortBy").val();
    var qShwUsrOnly = $("#accbBdgtFurthDetsShwUsrOnly:checked").length > 0;
    var qShwNonZeroOnly = $("#accbBdgtFurthDetsNonZeroOnly:checked").length > 0;
    var accbBudgetID1 =
        typeof $("#accbBudgetID").val() === "undefined"
            ? "-1"
            : $("#accbBudgetID").val();
    var accbBudgetNm1 =
        typeof $("#accbBudgetNm").val() === "undefined"
            ? ""
            : $("#accbBudgetNm").val();
    var accbSbmtdBudgetID =
        typeof $("#accbSbmtdBudgetID1").val() === "undefined"
            ? "-1"
            : $("#accbSbmtdBudgetID1").val();
    var accbSbmtdBudgetNm =
        typeof $("#accbSbmtdBudgetNm1").val() === "undefined"
            ? ""
            : $("#accbSbmtdBudgetNm1").val();
    if (Number(accbSbmtdBudgetID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        accbSbmtdBudgetID = accbBudgetID1;
        accbSbmtdBudgetNm = accbBudgetNm1;
    }
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwNonZeroOnly=" +
        qShwNonZeroOnly;
    if (accbBudgetID <= 0) {
        linkArgs =
            linkArgs +
            "&accbSbmtdBudgetID=" +
            accbSbmtdBudgetID +
            "&accbSbmtdBudgetNm=" +
            accbSbmtdBudgetNm;
    }
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbBdgtFurthDets(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbBdgtFurthDets(actionText,slctr,linkArgs);
    }
}

function afterAccbBdgtDetCurSlctn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var lineAmountCrncy =
        typeof $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm1").text() === "undefined"
            ? jrnlBatchAmountCrncy
            : $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm1").text();
    var lineAmountAcntID1 =
        typeof $("#" + rowPrfxNm + rndmNum + "_AccountID1").val() === "undefined"
            ? jrnlBatchDfltBalsAcntID
            : $("#" + rowPrfxNm + rndmNum + "_AccountID1").val();
    var lineAmountTransDte =
        typeof $("#" + rowPrfxNm + rndmNum + "_StrtDte").val() === "undefined"
            ? jrnlBatchDfltTrnsDte
            : $("#" + rowPrfxNm + rndmNum + "_TransDte").val();
    if (lineAmountCrncy !== "" && lineAmountTransDte !== "") {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append("grp",6);
            formData.append("typ",1);
            formData.append("pg",16);
            formData.append("q","VIEW");
            formData.append("vtyp",1);
            formData.append("lineAmountCrncy",lineAmountCrncy);
            formData.append("lineAmountAcntID1",lineAmountAcntID1);
            formData.append("lineAmountTransDte",lineAmountTransDte);
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.message.indexOf("Success") !== -1) {
                        $("#" + rowPrfxNm + rndmNum + "_FuncExchgRate").val(
                            data.FuncExchgRate
                        );
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    } else {
        $("#" + rowPrfxNm + rndmNum + "_FuncExchgRate").val(1.0);
    }
}

function insertNewAccbBdgtsRows(tableElmntID,position,inptHtml,lineType) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID + " > tbody > tr")
                    .eq(position)
                    .before(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function insertNewAccbBdgtDetsRows(tableElmntID,position,inptHtml,lineType) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID + " > tbody > tr")
                    .eq(position)
                    .before(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
}

function insertNewAccbBdgtFurthDetsRows(
    tableElmntID,
    position,
    inptHtml,
    lineType
) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID + " > tbody > tr")
                    .eq(position)
                    .before(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();

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
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function insertNewAccbBdgtBrkdwnRows(tableElmntID,position,inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();

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
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function getAccbBdgtDetBrkdwn(
    pKeyID,
    actionTxt,
    pKeyTitle,
    vtypActn,
    slctdRowNameID,
    trnsAmntElmntID,
    trnsAmtBrkdwnSaveElID
) {
    var slctdBrkdwnLines = "";
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    if (
        typeof trnsAmtBrkdwnSaveElID === "undefined" ||
        trnsAmtBrkdwnSaveElID === null
    ) {
        trnsAmtBrkdwnSaveElID = "";
    }
    if (trnsAmtBrkdwnSaveElID.trim() !== "") {
        slctdBrkdwnLines = $("#" + trnsAmtBrkdwnSaveElID).val();
    }
    var prfxName1 = slctdRowNameID.split("_")[0];
    var rndmNum1 = slctdRowNameID.split("_")[1];
    var sbmtdAccntID =
        typeof $("#" + prfxName1 + rndmNum1 + "_AccountID1").val() === "undefined"
            ? -1
            : $("#" + prfxName1 + rndmNum1 + "_AccountID1").val();
    var sbmtdAccntName =
        typeof $("#" + prfxName1 + rndmNum1 + "_AccountNm1").val() === "undefined"
            ? -1
            : $("#" + prfxName1 + rndmNum1 + "_AccountNm1").val();
    var lnkArgs = "grp=6&typ=1&pg=6&vtyp=2";
    lnkArgs =
        lnkArgs +
        "&vtypActn=" +
        vtypActn +
        "&sbmtdBdgtDetID=" +
        pKeyID +
        "&sbmtdAccntID=" +
        sbmtdAccntID +
        "&sbmtdAccntName=" +
        sbmtdAccntName +
        "&trnsAmntElmntID=" +
        trnsAmntElmntID +
        "&trnsAmtBrkdwnSaveElID=" +
        trnsAmtBrkdwnSaveElID +
        "&slctdBrkdwnLines=" +
        slctdBrkdwnLines;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalLx",
        actionTxt,
        pKeyTitle,
        "myFormsModalLxTitle",
        "myFormsModalLxBody",
        function () {
            $("#accbBdgtDtBrkdwnDiagForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable("#accbBdgtDtBrkdwnDiagHdrsTable")) {
                var table1 = $("#accbBdgtDtBrkdwnDiagHdrsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbBdgtDtBrkdwnDiagHdrsTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
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
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLx").css("overflow","auto");
                });
            $("#allOtherInputData99").val(0);
            $("#myFormsModalLx").off("hidden.bs.modal");
            $("#myFormsModalLx").one("hidden.bs.modal",function (e) {
                $("#myFormsModalLxTitle").html("");
                $("#myFormsModalLxBody").html("");
                $(e.currentTarget).unbind();
            });
        }
    );
}

function applyNewAccbBdgtDetBrkdwn(
    modalID,
    trnsAmntElmntID,
    trnsAmtBrkdwnSaveElID
) {
    var slctdBrkdwnTtl = 0;
    var slctdBrkdwnLines = "";
    $("#accbBdgtDtBrkdwnDiagHdrsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowUnitAmount = $(
                        "#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnUnitVal"
                    ).val();
                    var rowQty1 = $(
                        "#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY1"
                    ).val();
                    var rowQty2 = $(
                        "#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY2"
                    ).val();
                    var rowTtl =
                        Number(rowQty1.replace(/[^-?0-9\.]/g,"")) *
                        Number(rowQty2.replace(/[^-?0-9\.]/g,"")) *
                        Number(rowUnitAmount.replace(/[^-?0-9\.]/g,""));
                    $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnTtl").val(
                        addCommas(rowTtl.toFixed(2))
                    );
                    slctdBrkdwnTtl = slctdBrkdwnTtl + rowTtl;
                    slctdBrkdwnLines =
                        slctdBrkdwnLines +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnLnID")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_DetID")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_ItemID")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_ItemName")
                            .val()
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_DetType")
                            .val()
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY1")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY2")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnUnitVal")
                            .val()
                            .replace(/[^-?0-9\.]/g,"")
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "~" +
                        $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnDesc")
                            .val()
                            .replace(/(~)/g,"-")
                            .replace(/(\|)/g,":") +
                        "|";
                }
            }
        });
    $("#" + trnsAmntElmntID).val(addCommas(slctdBrkdwnTtl.toFixed(2)));
    $("#" + trnsAmtBrkdwnSaveElID).val(slctdBrkdwnLines);
    calcAllAccbBdgtDetBrkdwn();
    $("#" + modalID).modal("hide");
}

function calcAllAccbBdgtDetBrkdwn() {
    var slctdBrkdwnTtl = 0;
    $("#accbBdgtDtBrkdwnDiagHdrsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowUnitAmount = $(
                        "#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnUnitVal"
                    ).val();
                    var rowQty1 = $(
                        "#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY1"
                    ).val();
                    var rowQty2 = $(
                        "#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnQTY2"
                    ).val();
                    var rowTtl =
                        Number(rowQty1.replace(/[^-?0-9\.]/g,"")) *
                        Number(rowQty2.replace(/[^-?0-9\.]/g,"")) *
                        Number(rowUnitAmount.replace(/[^-?0-9\.]/g,""));
                    slctdBrkdwnTtl = slctdBrkdwnTtl + rowTtl;
                    $("#accbBdgtDtBrkdwnDiagHdrsRow" + rndmNum + "_BrkdwnTtl").val(
                        addCommas(rowTtl.toFixed(2))
                    );
                }
            }
        });
    /*alert(slctdBrkdwnTtl);*/
    $("#myBdgtAmtBrkdwnTtlBtn").text(addCommas(slctdBrkdwnTtl.toFixed(2)));
    $("#myBdgtAmtBrkdwnTtlVal").val(slctdBrkdwnTtl.toFixed(2));
}

function delAccbBdgtHdr(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: "Delete Budget?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Budget?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Budget?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Budget...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 6,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbBdgtDetBrkdwn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (
        typeof $("#" + rowPrfxNm + rndmNum + "_BrkdwnLnID").val() === "undefined"
    ) {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_BrkdwnLnID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_BrkdwnDesc").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Budget Breakdown";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 6,
                                    q: "DELETE",
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delSlctdAcbBdgtLines() {
    var slctdBdgtLineIDs = "";
    var slctdCnt = 0;
    $("#accbBdgtDetsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowPrfxNm = $(el).attr("id").split("_")[0];
                    if (
                        typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() ===
                        "undefined"
                    ) {
                        /*Do Nothing*/
                    } else {
                        var ln_TrnsLnID =
                            typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() ===
                                "undefined"
                                ? "-1"
                                : $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
                        var isTcked =
                            typeof $(
                                "input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked"
                            ).val() === "undefined"
                                ? "NO"
                                : "YES";
                        if (
                            Number(ln_TrnsLnID.replace(/[^-?0-9\.]/g,"")) > 0 &&
                            isTcked === "YES"
                        ) {
                            slctdCnt = slctdCnt + 1;
                        }
                        $("#" + rowPrfxNm + rndmNum).remove();
                    }
                }
            }
        });
    if (slctdCnt > 0) {
        var dialog = bootbox.confirm({
            title: "Delete Selected Budget Lines?",
            size: "small",
            message:
                '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> the ' +
                slctdCnt +
                " selected Budget Line(s)?<br/>Action cannot be Undone!</p>",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes',
                    className: "btn-success",
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> No',
                    className: "btn-danger",
                },
            },
            callback: function (result) {
                $("#accbBdgtDetsTable")
                    .find("tr")
                    .each(function (i,el) {
                        if (i > 0) {
                            if (typeof $(el).attr("id") === "undefined") {
                                /*Do Nothing*/
                            } else {
                                var rndmNum = $(el).attr("id").split("_")[1];
                                var rowPrfxNm = $(el).attr("id").split("_")[0];
                                if (
                                    typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() ===
                                    "undefined"
                                ) {
                                    /*Do Nothing*/
                                } else {
                                    var ln_TrnsLnID =
                                        typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() ===
                                            "undefined"
                                            ? "-1"
                                            : $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
                                    var ln_TrnsLnIDDesc =
                                        $("#" + rowPrfxNm + rndmNum + "_AccountNm1").val() +
                                        " (" +
                                        $("#" + rowPrfxNm + rndmNum + "_StrtDte").val() +
                                        ")";
                                    var isTcked =
                                        typeof $(
                                            "input[name='" +
                                            rowPrfxNm +
                                            rndmNum +
                                            "_CheckBox']:checked"
                                        ).val() === "undefined"
                                            ? "NO"
                                            : "YES";
                                    if (
                                        Number(ln_TrnsLnID.replace(/[^-?0-9\.]/g,"")) > 0 &&
                                        isTcked === "YES"
                                    ) {
                                        slctdBdgtLineIDs =
                                            slctdBdgtLineIDs +
                                            ln_TrnsLnID +
                                            "~" +
                                            ln_TrnsLnIDDesc
                                                .replace(/(~)/g,"{-;-;}")
                                                .replace(/(\|)/g,"{:;:;}") +
                                            "|";
                                    }
                                    $("#" + rowPrfxNm + rndmNum).remove();
                                }
                            }
                        }
                    });
                var result2 = "";
                if (result === true) {
                    var dialog1 = bootbox.alert({
                        title: "Delete Selected Budget Lines?",
                        size: "small",
                        message:
                            '<p><i class="fa fa-spin fa-spinner"></i> Deleting Selected Budget Lines...Please Wait...</p>',
                        callback: function () {
                            $("body").css("padding-right","0px");
                            if (result2.indexOf("Success") !== -1) {
                                getAccbBdgtDets(
                                    "",
                                    "#accbBdgtsDetList",
                                    "grp=6&typ=1&pg=6&vtyp=1"
                                );
                            }
                        },
                    });
                    dialog1.init(function () {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 6,
                                    q: "DELETE",
                                    actyp: 2,
                                    slctdBdgtLineIDs: slctdBdgtLineIDs,
                                },
                                success: function (result1) {
                                    result2 = result1;
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    });
                }
            },
        });
    }
}

function saveAccbBudgets() {
    var errMsg = "";
    var slctdBudgets = "";
    var isVld = true;
    $("#accbBdgtsHdrsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowPrfxNm = $(el).attr("id").split("_")[0];
                    if (
                        typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined"
                    ) {
                        /*Do Nothing*/
                    } else {
                        var lnBdgtID =
                            typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() ===
                                "undefined"
                                ? "-1"
                                : $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
                        var ln_LineNm =
                            typeof $("#" + rowPrfxNm + rndmNum + "_LineNm").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_LineNm").val();
                        var ln_AcntType =
                            typeof $("#" + rowPrfxNm + rndmNum + "_AcntType").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_AcntType").val();
                        var ln_StrtDte =
                            typeof $("#" + rowPrfxNm + rndmNum + "_StrtDte").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_StrtDte").val();
                        var ln_EndDte =
                            typeof $("#" + rowPrfxNm + rndmNum + "_EndDte").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_EndDte").val();
                        var ln_PrdType =
                            typeof $("#" + rowPrfxNm + rndmNum + "_PrdType").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_PrdType").val();
                        var ln_IsActive =
                            typeof $(
                                "input[name='" + rowPrfxNm + rndmNum + "_IsActive']:checked"
                            ).val() === "undefined"
                                ? "NO"
                                : "YES";
                        if (ln_LineNm.trim() === "") {
                            /*Do Nothing*/
                        } else {
                            if (ln_AcntType.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_AcntType").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_AcntType").removeClass(
                                    "rho-error"
                                );
                            }
                            if (ln_StrtDte.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_StrtDte").addClass("rho-error");
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_StrtDte").removeClass(
                                    "rho-error"
                                );
                            }
                            if (ln_EndDte.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_EndDte").addClass("rho-error");
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_EndDte").removeClass(
                                    "rho-error"
                                );
                            }
                            if (ln_PrdType.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_PrdType").addClass("rho-error");
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_PrdType").removeClass(
                                    "rho-error"
                                );
                            }
                            if (isVld === true) {
                                slctdBudgets =
                                    slctdBudgets +
                                    lnBdgtID.replace(/[^-?0-9\.]/g,"") +
                                    "~" +
                                    ln_LineNm
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_AcntType
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_StrtDte
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_EndDte
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_PrdType
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_IsActive
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "|";
                            }
                        }
                    }
                }
            }
        });
    if (isVld === false) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Budgets",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Budgets...Please Wait...</p>',
        callback: function () {
            getAccbBdgts("","#allmodules","grp=6&typ=1&pg=6&vtyp=0");
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 6,
                    q: "UPDATE",
                    actyp: 1,
                    slctdBudgets: slctdBudgets,
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(result.message);
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function saveAccbBudgetLines() {
    var accbSbmtdBudgetID =
        typeof $("#accbSbmtdBudgetID").val() === "undefined"
            ? "-1"
            : $("#accbSbmtdBudgetID").val();
    var accbSbmtdBudgetNm =
        typeof $("#accbSbmtdBudgetNm").val() === "undefined"
            ? ""
            : $("#accbSbmtdBudgetNm").val();
    var errMsg = "";
    var slctdBudgetLines = "";
    var isVld = true;
    if (Number(accbSbmtdBudgetID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Budget Header Name cannot be empty!</span></p>';
    }
    $("#accbBdgtDetsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowPrfxNm = $(el).attr("id").split("_")[0];
                    if (
                        typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() ===
                        "undefined"
                    ) {
                        /*Do Nothing*/
                    } else {
                        var ln_TrnsLnID =
                            typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() ===
                                "undefined"
                                ? "-1"
                                : $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
                        var ln_AccountID1 =
                            typeof $("#" + rowPrfxNm + rndmNum + "_AccountID1").val() ===
                                "undefined"
                                ? "-1"
                                : $("#" + rowPrfxNm + rndmNum + "_AccountID1").val();
                        var ln_SlctdAmtBrkdwns =
                            typeof $("#" + rowPrfxNm + rndmNum + "_SlctdAmtBrkdwns").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_SlctdAmtBrkdwns").val();
                        var ln_TrnsCurNm =
                            typeof $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm").val();
                        var ln_EntrdAmt =
                            typeof $("#" + rowPrfxNm + rndmNum + "_EntrdAmt").val() ===
                                "undefined"
                                ? "0.00"
                                : $("#" + rowPrfxNm + rndmNum + "_EntrdAmt").val();
                        var ln_StrtDte =
                            typeof $("#" + rowPrfxNm + rndmNum + "_StrtDte").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_StrtDte").val();
                        var ln_EndDte =
                            typeof $("#" + rowPrfxNm + rndmNum + "_EndDte").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_EndDte").val();

                        var ln_Action =
                            typeof $("#" + rowPrfxNm + rndmNum + "_Action").val() ===
                                "undefined"
                                ? ""
                                : $("#" + rowPrfxNm + rndmNum + "_Action").val();
                        var ln_FuncExchgRate =
                            typeof $("#" + rowPrfxNm + rndmNum + "_FuncExchgRate").val() ===
                                "undefined"
                                ? "0.00"
                                : $("#" + rowPrfxNm + rndmNum + "_FuncExchgRate").val();
                        if (Number(ln_AccountID1.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            /*Do Nothing*/
                        } else {
                            if (ln_TrnsCurNm.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm1").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_TrnsCurNm1").removeClass(
                                    "rho-error"
                                );
                            }
                            if (ln_StrtDte.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_StrtDte").addClass("rho-error");
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_StrtDte").removeClass(
                                    "rho-error"
                                );
                            }
                            if (ln_EndDte.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_EndDte").addClass("rho-error");
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_EndDte").removeClass(
                                    "rho-error"
                                );
                            }
                            if (ln_Action.trim() === "") {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_Action").addClass("rho-error");
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_Action").removeClass(
                                    "rho-error"
                                );
                            }
                            if (isVld === true) {
                                slctdBudgetLines =
                                    slctdBudgetLines +
                                    ln_TrnsLnID.replace(/[^-?0-9\.]/g,"") +
                                    "~" +
                                    ln_AccountID1.replace(/[^-?0-9\.]/g,"") +
                                    "~" +
                                    ln_SlctdAmtBrkdwns
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_TrnsCurNm
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_EntrdAmt.replace(/[^-?0-9\.]/g,"") +
                                    "~" +
                                    ln_StrtDte
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_EndDte
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_Action
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    ln_FuncExchgRate
                                        .replace(/[^-?0-9\.]/g,"")
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "|";
                            }
                        }
                    }
                }
            }
        });
    if (isVld === false) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Budget Lines",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Budget Lines...Please Wait...</p>',
        callback: function () {
            getAccbBdgtDets("","#accbBdgtsDetList","grp=6&typ=1&pg=6&vtyp=1");
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 6,
                    q: "UPDATE",
                    actyp: 2,
                    accbSbmtdBudgetID: accbSbmtdBudgetID,
                    slctdBudgetLines: slctdBudgetLines,
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(result.message);
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAccbTmplts(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbTmpltsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbTmpltsSrchFor").val();
    var srchIn =
        typeof $("#accbTmpltsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbTmpltsSrchIn").val();
    var pageNo =
        typeof $("#accbTmpltsPageNo").val() === "undefined"
            ? 1
            : $("#accbTmpltsPageNo").val();
    var limitSze =
        typeof $("#accbTmpltsDsplySze").val() === "undefined"
            ? 10
            : $("#accbTmpltsDsplySze").val();
    var sortBy =
        typeof $("#accbTmpltsSortBy").val() === "undefined"
            ? ""
            : $("#accbTmpltsSortBy").val();
    var qShwUsrOnly = $("#accbTmpltsShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#accbTmpltsShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbTmplts(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbTmplts(actionText,slctr,linkArgs);
    }
}

function getOneAccbTmpltsForm(tmpltID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=7&vtyp=" + vwtype + "&sbmtdTrnsTmpltID=" + tmpltID;
    doAjaxWthCallBck(
        lnkArgs,
        "accbTrnsTmpltsDetailInfo",
        "PasteDirect",
        "",
        "",
        "",
        function () {
            if (!$.fn.DataTable.isDataTable("#accbTmpltsLinesTable")) {
                var table2 = $("#accbTmpltsLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTmpltsLinesTable").wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable("#accbTmpltsUsersTable")) {
                var table3 = $("#accbTmpltsUsersTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTmpltsUsersTable").wrap('<div class="dataTables_scroll"/>');
            }

            $(".form_date_tme").datetimepicker({
                format: "dd-M-yyyy hh:ii:ss",
                language: "en",
                weekStart: 0,
                todayBtn: true,
                autoclose: true,
                todayHighlight: true,
                keyboardNavigation: true,
                startView: 2,
                minView: 0,
                maxView: 4,
                forceParse: true,
            });
            $(".form_date").datetimepicker({
                format: "dd-M-yyyy",
                language: "en",
                weekStart: 0,
                todayBtn: true,
                autoclose: true,
                todayHighlight: true,
                keyboardNavigation: true,
                startView: 2,
                minView: 2,
                maxView: 4,
                forceParse: true,
            });
        }
    );
}

function saveAccbTmpltsForm() {
    var accbTrnsTmpltID =
        typeof $("#accbTrnsTmpltID").val() === "undefined"
            ? -1
            : $("#accbTrnsTmpltID").val();
    var accbTrnsTmpltName =
        typeof $("#accbTrnsTmpltName").val() === "undefined"
            ? ""
            : $("#accbTrnsTmpltName").val();
    var accbTrnsTmpltDesc =
        typeof $("#accbTrnsTmpltDesc").val() === "undefined"
            ? ""
            : $("#accbTrnsTmpltDesc").val();
    var accbTrnsTmpltDocTyp =
        typeof $("#accbTrnsTmpltDocTyp").val() === "undefined"
            ? ""
            : $("#accbTrnsTmpltDocTyp").val();
    var accbTrnsTmpltEnbld =
        typeof $("input[name='accbTrnsTmpltEnbld']:checked").val() === "undefined"
            ? "NO"
            : "YES";
    var errMsg = "";
    if (accbTrnsTmpltName.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Template Name cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdTransLines = "";
    var slctdTransUsersLines = "";
    $("#accbTmpltsLinesTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_LineDesc = $(
                        "#accbTmpltsLinesTblRow" + rndmNum + "_LineDesc"
                    ).val();
                    var ln_IncrsDcrs1 = $(
                        "#accbTmpltsLinesTblRow" + rndmNum + "_IncrsDcrs1"
                    ).val();
                    var ln_AccountID1 = $(
                        "#accbTmpltsLinesTblRow" + rndmNum + "_AccountID1"
                    ).val();
                    if (ln_LineDesc.trim() !== "") {
                        if (Number(ln_AccountID1.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Account for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbTmpltsLinesTblRow" + rndmNum + "_AccountNm1").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbTmpltsLinesTblRow" + rndmNum + "_AccountNm1").removeClass(
                                "rho-error"
                            );
                        }
                        if (ln_IncrsDcrs1.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Increase/Decrease for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbTmpltsLinesTblRow" + rndmNum + "_IncrsDcrs1").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbTmpltsLinesTblRow" + rndmNum + "_IncrsDcrs1").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === true) {
                            slctdTransLines =
                                slctdTransLines +
                                $("#accbTmpltsLinesTblRow" + rndmNum + "_TrnsLnID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_LineDesc
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_IncrsDcrs1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_AccountID1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    $("#accbTmpltsUsersTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_UserID = $(
                        "#accbTmpltsUsersTblRow" + rndmNum + "_UserID"
                    ).val();
                    var ln_StrtDte = $(
                        "#accbTmpltsUsersTblRow" + rndmNum + "_StrtDte"
                    ).val();
                    var ln_EndDte = $(
                        "#accbTmpltsUsersTblRow" + rndmNum + "_EndDte"
                    ).val();
                    if (Number(ln_UserID.replace(/[^-?0-9\.]/g,"")) > 0) {
                        if (isVld === true) {
                            slctdTransUsersLines =
                                slctdTransUsersLines +
                                $("#accbTmpltsUsersTblRow" + rndmNum + "_TrnsLnID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_UserID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_StrtDte
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_EndDte.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Templates",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Templates...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",7);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("accbTrnsTmpltID",accbTrnsTmpltID);
    formData.append("accbTrnsTmpltName",accbTrnsTmpltName);
    formData.append("accbTrnsTmpltDesc",accbTrnsTmpltDesc);
    formData.append("accbTrnsTmpltDocTyp",accbTrnsTmpltDocTyp);
    formData.append("accbTrnsTmpltEnbld",accbTrnsTmpltEnbld);
    formData.append("slctdTransUsersLines",slctdTransUsersLines);
    formData.append("slctdTransLines",slctdTransLines);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            accbTrnsTmpltID = data.accbTrnsTmpltID;
                            getAccbTmplts("","#allmodules","grp=6&typ=1&pg=7&vtyp=0");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function delAccbTmplts(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_HdrNm").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Template?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Template?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Template?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Template...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 7,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbTmpltTrans(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_LineDesc").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Template Transaction?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Template Transaction?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Template?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Template Transaction...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 7,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function insertNewAccbTmpltsRows(tableElmntID,position,inptHtml,lineType) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function insertNewAccbTmpltUsrsRows(
    tableElmntID,
    position,
    inptHtml,
    lineType
) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetRfDc").focus(function () {
        $(this).select();
    });
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function getAccbPeriods(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbPeriodsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbPeriodsSrchFor").val();
    var srchIn =
        typeof $("#accbPeriodsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbPeriodsSrchIn").val();
    var pageNo =
        typeof $("#accbPeriodsPageNo").val() === "undefined"
            ? 1
            : $("#accbPeriodsPageNo").val();
    var limitSze =
        typeof $("#accbPeriodsDsplySze").val() === "undefined"
            ? 10
            : $("#accbPeriodsDsplySze").val();
    var sortBy =
        typeof $("#accbPeriodsSortBy").val() === "undefined"
            ? ""
            : $("#accbPeriodsSortBy").val();
    var qShwUsrOnly = $("#accbPeriodsShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#accbPeriodsShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbPeriods(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbPeriods(actionText,slctr,linkArgs);
    }
}

function insertNewAccbPeriodsRows(tableElmntID,position,inptHtml,lineType) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID + " > tbody > tr")
                    .eq(position)
                    .before(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetDesc").focus(function () {
        $(this).select();
    });
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function saveAccbPeriodsForm() {
    var sbmtdPrdHdrID =
        typeof $("#sbmtdPrdHdrID").val() === "undefined"
            ? -1
            : $("#sbmtdPrdHdrID").val();
    var periodHdrNm =
        typeof $("#periodHdrNm").val() === "undefined"
            ? ""
            : $("#periodHdrNm").val();
    var clndrDesc =
        typeof $("#clndrDesc").val() === "undefined" ? "" : $("#clndrDesc").val();
    var noTrnsDaysLov =
        typeof $("#noTrnsDaysLov").val() === "undefined"
            ? ""
            : $("#noTrnsDaysLov").val();
    var noTrnsDatesLov =
        typeof $("#noTrnsDatesLov").val() === "undefined"
            ? ""
            : $("#noTrnsDatesLov").val();
    var periodType =
        typeof $("#periodType").val() === "undefined" ? "" : $("#periodType").val();
    var shdUsePeriods =
        typeof $("input[name='shdUsePeriods']:checked").val() === "undefined"
            ? "NO"
            : "YES";
    var errMsg = "";
    if (periodHdrNm.trim() === "" || periodType.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Period Header Name and Type cannot be empty!</span></p>';
    }
    if (noTrnsDaysLov.trim() === "" || noTrnsDatesLov.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Days/Dates LOV cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdPeriodLines = "";
    $("#accbPeriodsHdrsTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var lnPeriodNm = $(
                        "#accbPeriodsHdrsRow" + rndmNum + "_PeriodNm"
                    ).val();
                    var lnStrtDte = $("#accbPeriodsHdrsRow" + rndmNum + "_StrtDte").val();
                    var lnEndDte = $("#accbPeriodsHdrsRow" + rndmNum + "_EndDte").val();
                    if (lnPeriodNm.trim() !== "") {
                        if (lnStrtDte.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Start Date for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbPeriodsHdrsRow" + rndmNum + "_StrtDte").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbPeriodsHdrsRow" + rndmNum + "_StrtDte").removeClass(
                                "rho-error"
                            );
                        }
                        if (lnEndDte.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">End Date for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbPeriodsHdrsRow" + rndmNum + "_EndDte").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbPeriodsHdrsRow" + rndmNum + "_EndDte").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === true) {
                            slctdPeriodLines =
                                slctdPeriodLines +
                                $("#accbPeriodsHdrsRow" + rndmNum + "_PeriodDetID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lnPeriodNm
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lnStrtDte.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lnEndDte.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Periods",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Periods...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",8);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("sbmtdPrdHdrID",sbmtdPrdHdrID);
    formData.append("periodHdrNm",periodHdrNm);
    formData.append("clndrDesc",clndrDesc);
    formData.append("noTrnsDaysLov",noTrnsDaysLov);
    formData.append("noTrnsDatesLov",noTrnsDatesLov);
    formData.append("periodType",periodType);
    formData.append("shdUsePeriods",shdUsePeriods);
    formData.append("slctdPeriodLines",slctdPeriodLines);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPrdHdrID = data.sbmtdPrdHdrID;
                            openATab("#allmodules","grp=6&typ=1&pg=8&vtyp=0");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAccbAssets(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbAssetsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbAssetsSrchFor").val();
    var srchIn =
        typeof $("#accbAssetsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbAssetsSrchIn").val();
    var pageNo =
        typeof $("#accbAssetsPageNo").val() === "undefined"
            ? 1
            : $("#accbAssetsPageNo").val();
    var limitSze =
        typeof $("#accbAssetsDsplySze").val() === "undefined"
            ? 10
            : $("#accbAssetsDsplySze").val();
    var sortBy =
        typeof $("#accbAssetsSortBy").val() === "undefined"
            ? ""
            : $("#accbAssetsSortBy").val();
    var qShwUsrOnly = $("#accbAssetsShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#accbAssetsShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbAssets(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbAssets(actionText,slctr,linkArgs);
    }
}

function getAccbAssetDets(actionText,slctr,linkArgs,accbAssetID) {
    if (typeof accbAssetID === "undefined" || accbAssetID === null) {
        accbAssetID = -1;
    }
    var accbSbmtdAssetID =
        typeof $("#accbAssetID").val() === "undefined"
            ? "-1"
            : $("#accbAssetID").val();
    var accbSbmtdAssetNm =
        typeof $("#accbAssetNm").val() === "undefined"
            ? ""
            : $("#accbAssetNm").val();
    if (accbAssetID <= 0) {
        linkArgs =
            linkArgs +
            "&accbSbmtdAssetID=" +
            accbSbmtdAssetID +
            "&accbSbmtdAssetNm=" +
            accbSbmtdAssetNm;
    }
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbAssetDets(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbAssetDets(actionText,slctr,linkArgs);
    }
}

function insertNewAccbAssetPMStpRows(
    tableElmntID,
    position,
    inptHtml,
    lineType
) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".jbDetDbt").focus(function () {
        $(this).select();
    });
    $(".jbDetCrdt").focus(function () {
        $(this).select();
    });
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function getAccbAstHdr(actionText,slctr,linkArgs,accbAssetID) {
    if (typeof accbAssetID === "undefined" || accbAssetID === null) {
        accbAssetID = -1;
    }
    var srchFor =
        typeof $("#accbAstHdrSrchFor").val() === "undefined"
            ? "%"
            : $("#accbAstHdrSrchFor").val();
    var srchIn =
        typeof $("#accbAstHdrSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbAstHdrSrchIn").val();
    var pageNo =
        typeof $("#accbAstHdrPageNo").val() === "undefined"
            ? 1
            : $("#accbAstHdrPageNo").val();
    var limitSze =
        typeof $("#accbAstHdrDsplySze").val() === "undefined"
            ? 15
            : $("#accbAstHdrDsplySze").val();
    var sortBy =
        typeof $("#accbAstHdrSortBy").val() === "undefined"
            ? ""
            : $("#accbAstHdrSortBy").val();
    var qShwUsrOnly = $("#accbAstHdrShwUsrOnly:checked").length > 0;
    var qShwNonZeroOnly = $("#accbAstHdrNonZeroOnly:checked").length > 0;
    var accbAssetID1 =
        typeof $("#accbAssetID").val() === "undefined"
            ? "-1"
            : $("#accbAssetID").val();
    var accbAssetNm1 =
        typeof $("#accbAssetNm").val() === "undefined"
            ? ""
            : $("#accbAssetNm").val();
    var accbSbmtdAssetID =
        typeof $("#accbSbmtdAssetID").val() === "undefined"
            ? "-1"
            : $("#accbSbmtdAssetID").val();
    var accbSbmtdAssetNm =
        typeof $("#accbSbmtdAssetNm").val() === "undefined"
            ? ""
            : $("#accbSbmtdAssetNm").val();
    if (Number(accbSbmtdAssetID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        accbSbmtdAssetID = accbAssetID1;
        accbSbmtdAssetNm = accbAssetNm1;
    }
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwNonZeroOnly=" +
        qShwNonZeroOnly;
    if (accbAssetID <= 0) {
        linkArgs =
            linkArgs +
            "&accbSbmtdAssetID=" +
            accbSbmtdAssetID +
            "&accbSbmtdAssetNm=" +
            accbSbmtdAssetNm;
    }
    $("#myFormsModalLg").modal("hide");
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbAstHdr(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbAstHdr(actionText,slctr,linkArgs);
    }
}

function saveAccbAstHdrForm() {
    var accbAstHdrID =
        typeof $("#accbAstHdrID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrID").val();
    var accbAstHdrName =
        typeof $("#accbAstHdrName").val() === "undefined"
            ? ""
            : $("#accbAstHdrName").val();
    var accbAstHdrDesc =
        typeof $("#accbAstHdrDesc").val() === "undefined"
            ? ""
            : $("#accbAstHdrDesc").val();
    var accbAstHdrInvItemID =
        typeof $("#accbAstHdrInvItemID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrInvItemID").val();
    var accbAstHdrClsfctn =
        typeof $("#accbAstHdrClsfctn").val() === "undefined"
            ? ""
            : $("#accbAstHdrClsfctn").val();
    var accbAstHdrCtgry =
        typeof $("#accbAstHdrCtgry").val() === "undefined"
            ? ""
            : $("#accbAstHdrCtgry").val();
    var accbAstHdrDivGrpID =
        typeof $("#accbAstHdrDivGrpID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrDivGrpID").val();

    var accbAstHdrSiteID =
        typeof $("#accbAstHdrSiteID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrSiteID").val();
    var accbAstHdrBuildLoc =
        typeof $("#accbAstHdrBuildLoc").val() === "undefined"
            ? ""
            : $("#accbAstHdrBuildLoc").val();
    var accbAstHdrRoomNum =
        typeof $("#accbAstHdrRoomNum").val() === "undefined"
            ? ""
            : $("#accbAstHdrRoomNum").val();
    var accbAstHdrPrsnID =
        typeof $("#accbAstHdrPrsnID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrPrsnID").val();
    var accbAstHdrTagNum =
        typeof $("#accbAstHdrTagNum").val() === "undefined"
            ? ""
            : $("#accbAstHdrTagNum").val();
    var accbAstHdrSerialNum =
        typeof $("#accbAstHdrSerialNum").val() === "undefined"
            ? ""
            : $("#accbAstHdrSerialNum").val();
    var accbAstHdrBarCode =
        typeof $("#accbAstHdrBarCode").val() === "undefined"
            ? ""
            : $("#accbAstHdrBarCode").val();
    var accbAstHdrStrtDte =
        typeof $("#accbAstHdrStrtDte").val() === "undefined"
            ? ""
            : $("#accbAstHdrStrtDte").val();
    var accbAstHdrEndDte =
        typeof $("#accbAstHdrEndDte").val() === "undefined"
            ? ""
            : $("#accbAstHdrEndDte").val();
    var accbAstHdrAstAcntID =
        typeof $("#accbAstHdrAstAcntID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrAstAcntID").val();
    var accbAstHdrDprcAcntID =
        typeof $("#accbAstHdrDprcAcntID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrDprcAcntID").val();
    var accbAstHdrExpnsAcntID =
        typeof $("#accbAstHdrExpnsAcntID").val() === "undefined"
            ? "-1"
            : $("#accbAstHdrExpnsAcntID").val();
    var accbAstHdrSlvgValue =
        typeof $("#accbAstHdrSlvgValue").val() === "undefined"
            ? "0"
            : $("#accbAstHdrSlvgValue").val();
    var accbAstHdrSQLFrmlr =
        typeof $("#accbAstHdrSQLFrmlr").val() === "undefined"
            ? "0"
            : $("#accbAstHdrSQLFrmlr").val();

    var accbAstHdrAutoDprctn =
        typeof $("input[name='accbAstHdrAutoDprctn']:checked").val() === "undefined"
            ? "NO"
            : $("input[name='accbAstHdrAutoDprctn']:checked").val();
    var errMsg = "";
    if (accbAstHdrName.trim() === "" || accbAstHdrDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Asset Name and Description cannot be empty!</span></p>';
    }
    if (accbAstHdrClsfctn.trim() === "" || accbAstHdrCtgry.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Asset Classification and Category cannot be empty!</span></p>';
    }
    if (accbAstHdrStrtDte.trim() === "" || accbAstHdrEndDte.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Asset Start Date and End Date cannot be empty!</span></p>';
    }
    if (
        Number(accbAstHdrDivGrpID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
        Number(accbAstHdrSiteID.replace(/[^-?0-9\.]/g,"")) <= 0
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Asset Division and Site/Location cannot be empty!</span></p>';
    }
    if (
        accbAstHdrTagNum.trim() === "" &&
        accbAstHdrSerialNum.trim() === "" &&
        accbAstHdrBarCode.trim() === ""
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please provide either Tag Number, Serial Number or Barcode Info!</span></p>';
    }
    if (
        Number(accbAstHdrAstAcntID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
        Number(accbAstHdrDprcAcntID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
        Number(accbAstHdrExpnsAcntID.replace(/[^-?0-9\.]/g,"")) <= 0
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Asset GL Accounts must all be provided!</span></p>';
    }
    if (accbAstHdrSQLFrmlr.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">SQL Formula cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdMeasurmntTyps = "";
    var slctdExtraInfoLines = "";
    $("#accbAssetsPMStpsTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_LineID = $("#accbAssetsPMStpsRow" + rndmNum + "_LineID").val();
                    var ln_MsrmntNm = $(
                        "#accbAssetsPMStpsRow" + rndmNum + "_MsrmntNm"
                    ).val();
                    var ln_UOM = $("#accbAssetsPMStpsRow" + rndmNum + "_UOM").val();
                    var ln_MxFigure = $(
                        "#accbAssetsPMStpsRow" + rndmNum + "_MxFigure"
                    ).val();
                    var ln_PMFigure = $(
                        "#accbAssetsPMStpsRow" + rndmNum + "_PMFigure"
                    ).val();

                    if (ln_MsrmntNm.trim() !== "") {
                        if (ln_UOM.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">UOM for Row No. ' +
                                (i - 1) +
                                " cannot be empty!</span></p>";
                            $("#accbAssetsPMStpsRow" + rndmNum + "_UOM1").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbAssetsPMStpsRow" + rndmNum + "_UOM1").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === true) {
                            slctdMeasurmntTyps =
                                slctdMeasurmntTyps +
                                ln_LineID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_MsrmntNm
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_UOM.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_MxFigure
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_PMFigure
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });

    $("#oneAccbAssetsExtrInfTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_DfltRowID = $(
                        "#oneAccbAssetsExtrInfRow" + rndmNum + "_DfltRowID"
                    ).val();
                    var ln_CombntnID = $(
                        "#oneAccbAssetsExtrInfRow" + rndmNum + "_CombntnID"
                    ).val();
                    var ln_TableID = $(
                        "#oneAccbAssetsExtrInfRow" + rndmNum + "_TableID"
                    ).val();
                    var ln_Value = $(
                        "#oneAccbAssetsExtrInfRow" + rndmNum + "_Value"
                    ).val();
                    var ln_extrInfoCtgry = $(
                        "#oneAccbAssetsExtrInfRow" + rndmNum + "_extrInfoCtgry"
                    ).val();
                    var ln_extrInfoLbl = $(
                        "#oneAccbAssetsExtrInfRow" + rndmNum + "_extrInfoLbl"
                    ).val();

                    if (isVld === true) {
                        slctdExtraInfoLines =
                            slctdExtraInfoLines +
                            ln_DfltRowID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_CombntnID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_TableID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoCtgry
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoLbl
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_Value.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "|";
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Asset/Investment",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Asset/Investment...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",9);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("accbAstHdrID",accbAstHdrID);
    formData.append("accbAstHdrName",accbAstHdrName);
    formData.append("accbAstHdrDesc",accbAstHdrDesc);
    formData.append("accbAstHdrInvItemID",accbAstHdrInvItemID);
    formData.append("accbAstHdrClsfctn",accbAstHdrClsfctn);
    formData.append("accbAstHdrCtgry",accbAstHdrCtgry);
    formData.append("accbAstHdrDivGrpID",accbAstHdrDivGrpID);

    formData.append("accbAstHdrSiteID",accbAstHdrSiteID);
    formData.append("accbAstHdrBuildLoc",accbAstHdrBuildLoc);
    formData.append("accbAstHdrRoomNum",accbAstHdrRoomNum);
    formData.append("accbAstHdrPrsnID",accbAstHdrPrsnID);
    formData.append("accbAstHdrTagNum",accbAstHdrTagNum);
    formData.append("accbAstHdrSerialNum",accbAstHdrSerialNum);

    formData.append("accbAstHdrBarCode",accbAstHdrBarCode);
    formData.append("accbAstHdrStrtDte",accbAstHdrStrtDte);
    formData.append("accbAstHdrEndDte",accbAstHdrEndDte);

    formData.append("accbAstHdrAstAcntID",accbAstHdrAstAcntID);
    formData.append("accbAstHdrDprcAcntID",accbAstHdrDprcAcntID);
    formData.append("accbAstHdrExpnsAcntID",accbAstHdrExpnsAcntID);
    formData.append("accbAstHdrSlvgValue",accbAstHdrSlvgValue);
    formData.append("accbAstHdrSQLFrmlr",accbAstHdrSQLFrmlr);
    formData.append("accbAstHdrAutoDprctn",accbAstHdrAutoDprctn);

    formData.append("slctdMeasurmntTyps",slctdMeasurmntTyps);
    formData.append("slctdExtraInfoLines",slctdExtraInfoLines);

    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            accbAstHdrID = data.accbAstHdrID;
                            getAccbAssetDets(
                                "clear",
                                "#assetRgstrDetList",
                                "grp=6&typ=1&pg=9&vtyp=1&accbSbmtdAssetID=" +
                                accbAstHdrID +
                                "&accbSbmtdAssetNm=" +
                                accbAstHdrName,
                                accbAstHdrID
                            );
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function delAccbAstHdr(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: "Delete Asset/Investment?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Asset/Investment?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Asset/Investment?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Asset/Investment...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 9,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbAstPMStp(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_LineID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_LineID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_MsrmntNm").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Measurement Setup?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Measurement Setup?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Measurement Setup?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Measurement Setup...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 9,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function getOneAccbAssetTransForm(pKeyID,sbmtdAssetID,actionTxt) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=9&vtyp=201&sbmtdAssetTransID=" +
        pKeyID +
        "&sbmtdAssetID=" +
        sbmtdAssetID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalNrml",
        actionTxt,
        "Asset/Investment Transaction (ID:" + pKeyID + ")",
        "myFormsModalNrmlTitle",
        "myFormsModalNrmlBody",
        function () {
            $("#astTrnsDetForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalNrml").css("overflow","auto");
                });
            $("#allOtherInputData99").val(0);
            $("#myFormsModalNrml").off("hidden.bs.modal");
            $("#myFormsModalNrml").one("hidden.bs.modal",function (e) {
                $("#myFormsModalNrmlTitle").html("");
                $("#myFormsModalNrmlBody").html("");
                getAccbAstHdr("clear","#assetDetlsTrans","grp=6&typ=1&pg=9&vtyp=2");
                $(e.currentTarget).unbind();
            });
        }
    );
}

function saveAccbAssetTransForm(shdSbmt) {
    if (typeof shdSbmt === "undefined" || shdSbmt === null) {
        shdSbmt = 0;
    }
    var sbmtdAssetID =
        typeof $("#sbmtdAssetID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAssetID").val();
    var sbmtdAssetTransID =
        typeof $("#sbmtdAssetTransID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAssetTransID").val();
    var astTrnsType =
        typeof $("#astTrnsType").val() === "undefined"
            ? ""
            : $("#astTrnsType").val();
    var astTrnsDate =
        typeof $("#astTrnsDate").val() === "undefined"
            ? ""
            : $("#astTrnsDate").val();
    var astTrnsDesc =
        typeof $("#astTrnsDesc").val() === "undefined"
            ? ""
            : $("#astTrnsDesc").val();
    var astTrnsCurNm =
        typeof $("#astTrnsCurNm").val() === "undefined"
            ? ""
            : $("#astTrnsCurNm").val();
    var astTrnsAmount =
        typeof $("#astTrnsAmount").val() === "undefined"
            ? "0.00"
            : $("#astTrnsAmount").val();
    astTrnsAmount = fmtAsNumber("astTrnsAmount");
    var astTrnsIncrsDcrs1 =
        typeof $("#astTrnsIncrsDcrs1").val() === "undefined"
            ? ""
            : $("#astTrnsIncrsDcrs1").val();
    var astTrnsAccountID1 =
        typeof $("#astTrnsAccountID1").val() === "undefined"
            ? "-1"
            : $("#astTrnsAccountID1").val();
    var astTrnsIncrsDcrs2 =
        typeof $("#astTrnsIncrsDcrs2").val() === "undefined"
            ? ""
            : $("#astTrnsIncrsDcrs2").val();
    var astTrnsAccountID2 =
        typeof $("#astTrnsAccountID2").val() === "undefined"
            ? "-1"
            : $("#astTrnsAccountID2").val();
    var astTrnsFuncCurRate =
        typeof $("#astTrnsFuncCurRate").val() === "undefined"
            ? "1.0000"
            : $("#astTrnsFuncCurRate").val();
    astTrnsFuncCurRate = Number(astTrnsFuncCurRate.replace(/[^-?0-9\.]/g,""));
    var errMsg = "";
    if (Number(sbmtdAssetID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Asset cannot be empty!</span></p>';
    }
    if (astTrnsDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Description cannot be empty!</span></p>';
    }
    if (astTrnsDate.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Date cannot be empty!</span></p>';
    }
    if (astTrnsCurNm.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Currency cannot be empty!</span></p>';
    }
    if (astTrnsAmount <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Amount cannot be zero or less!</span></p>';
    }
    if (astTrnsIncrsDcrs1.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Increase/Decrease 1 cannot be empty!</span></p>';
    }
    if (Number(astTrnsAccountID1.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Costing Account cannot be empty!</span></p>';
    }
    if (astTrnsIncrsDcrs2.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Increase/Decrease 2 cannot be empty!</span></p>';
    }
    if (Number(astTrnsAccountID2.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Balancing Account cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var yesNoResult = false;
    if (shdSbmt === 5) {
        var dialog1 = bootbox.confirm({
            title: "Create Accounting?",
            size: "small",
            message:
                '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CREATE ACCOUNTING</span> for this Transaction?<br/>Action cannot be Undone!</p>',
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes',
                    className: "btn-success",
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> No',
                    className: "btn-danger",
                },
            },
            callback: function (result) {
                yesNoResult = result;
                if (yesNoResult === true) {
                    var shdClose = 0;
                    var dialog = bootbox.alert({
                        title: "Save Transaction",
                        size: "small",
                        message:
                            '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction...Please Wait...</p>',
                        callback: function () {
                            if (shdClose > 0) {
                                $("#myFormsModalNrml").modal("hide");
                                getAccbAstHdr(
                                    "",
                                    "#assetDetlsTrans",
                                    "grp=6&typ=1&pg=9&vtyp=2"
                                );
                            }
                        },
                    });
                    dialog.init(function () {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 9,
                                    q: "UPDATE",
                                    actyp: 2,
                                    sbmtdAssetID: sbmtdAssetID,
                                    sbmtdAssetTransID: sbmtdAssetTransID,
                                    astTrnsType: astTrnsType,
                                    astTrnsDate: astTrnsDate,
                                    astTrnsDesc: astTrnsDesc,
                                    astTrnsCurNm: astTrnsCurNm,
                                    astTrnsAmount: astTrnsAmount,
                                    astTrnsIncrsDcrs1: astTrnsIncrsDcrs1,
                                    astTrnsAccountID1: astTrnsAccountID1,
                                    astTrnsIncrsDcrs2: astTrnsIncrsDcrs2,
                                    astTrnsAccountID2: astTrnsAccountID2,
                                    astTrnsFuncCurRate: astTrnsFuncCurRate,
                                    shdSbmt: shdSbmt,
                                },
                                success: function (result) {
                                    dialog.find(".bootbox-body").html(result.message);
                                    if (result.message.indexOf("Success") !== -1) {
                                        shdClose = 1;
                                        sbmtdAssetTransID = result.sbmtdAssetTransID;
                                    }
                                },
                                error: function (jqXHR,textStatus,errorThrown) {
                                    console.warn(jqXHR.responseText);
                                },
                            });
                        });
                    });
                }
            },
        });
    } else {
        var shdClose = 0;
        var dialog = bootbox.alert({
            title: "Save Transaction",
            size: "small",
            message:
                '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction...Please Wait...</p>',
            callback: function () {
                if (shdClose > 0) {
                    $("#myFormsModalNrml").modal("hide");
                    getAccbAstHdr("","#assetDetlsTrans","grp=6&typ=1&pg=9&vtyp=2");
                }
            },
        });
        dialog.init(function () {
            getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                $body = $("body");
                $body.removeClass("mdlloading");
                $.ajax({
                    method: "POST",
                    url: "index.php",
                    data: {
                        grp: 6,
                        typ: 1,
                        pg: 9,
                        q: "UPDATE",
                        actyp: 2,
                        sbmtdAssetID: sbmtdAssetID,
                        sbmtdAssetTransID: sbmtdAssetTransID,
                        astTrnsType: astTrnsType,
                        astTrnsDate: astTrnsDate,
                        astTrnsDesc: astTrnsDesc,
                        astTrnsCurNm: astTrnsCurNm,
                        astTrnsAmount: astTrnsAmount,
                        astTrnsIncrsDcrs1: astTrnsIncrsDcrs1,
                        astTrnsAccountID1: astTrnsAccountID1,
                        astTrnsIncrsDcrs2: astTrnsIncrsDcrs2,
                        astTrnsAccountID2: astTrnsAccountID2,
                        astTrnsFuncCurRate: astTrnsFuncCurRate,
                        shdSbmt: shdSbmt,
                    },
                    success: function (result) {
                        dialog.find(".bootbox-body").html(result.message);
                        if (result.message.indexOf("Success") !== -1) {
                            shdClose = 1;
                            sbmtdAssetTransID = result.sbmtdAssetTransID;
                        }
                    },
                    error: function (jqXHR,textStatus,errorThrown) {
                        console.warn(jqXHR.responseText);
                    },
                });
            });
        });
    }
}

function delAccbAssetTrans(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: "Delete Transaction?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Transaction?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Transaction?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Transaction...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 9,
                                    q: "DELETE",
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function getAccbAstPmRec(actionText,slctr,linkArgs,accbAssetID) {
    if (typeof accbAssetID === "undefined" || accbAssetID === null) {
        accbAssetID = -1;
    }
    var srchFor =
        typeof $("#accbAstPmRecSrchFor").val() === "undefined"
            ? "%"
            : $("#accbAstPmRecSrchFor").val();
    var srchIn =
        typeof $("#accbAstPmRecSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbAstPmRecSrchIn").val();
    var pageNo =
        typeof $("#accbAstPmRecPageNo").val() === "undefined"
            ? 1
            : $("#accbAstPmRecPageNo").val();
    var limitSze =
        typeof $("#accbAstPmRecDsplySze").val() === "undefined"
            ? 15
            : $("#accbAstPmRecDsplySze").val();
    var sortBy =
        typeof $("#accbAstPmRecSortBy").val() === "undefined"
            ? ""
            : $("#accbAstPmRecSortBy").val();
    var qShwUsrOnly = $("#accbAstPmRecShwUsrOnly:checked").length > 0;
    var qShwNonZeroOnly = $("#accbAstPmRecNonZeroOnly:checked").length > 0;

    var accbAssetID1 =
        typeof $("#accbAssetID").val() === "undefined"
            ? "-1"
            : $("#accbAssetID").val();
    var accbAssetNm1 =
        typeof $("#accbAssetNm").val() === "undefined"
            ? ""
            : $("#accbAssetNm").val();
    var accbSbmtdAssetID =
        typeof $("#accbSbmtdAssetID").val() === "undefined"
            ? "-1"
            : $("#accbSbmtdAssetID").val();
    var accbSbmtdAssetNm =
        typeof $("#accbSbmtdAssetNm").val() === "undefined"
            ? ""
            : $("#accbSbmtdAssetNm").val();
    if (Number(accbSbmtdAssetID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        accbSbmtdAssetID = accbAssetID1;
        accbSbmtdAssetNm = accbAssetNm1;
    }
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwNonZeroOnly=" +
        qShwNonZeroOnly;
    if (accbAssetID <= 0) {
        linkArgs =
            linkArgs +
            "&accbSbmtdAssetID=" +
            accbSbmtdAssetID +
            "&accbSbmtdAssetNm=" +
            accbSbmtdAssetNm;
    }
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbAstPmRec(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbAstPmRec(actionText,slctr,linkArgs);
    }
}

function getOneAccbAssetPmRecForm(pKeyID,sbmtdAssetID,actionTxt) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=9&vtyp=301&sbmtdAssetPmRecID=" +
        pKeyID +
        "&sbmtdAssetID=" +
        sbmtdAssetID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalNrml",
        actionTxt,
        "Asset/Investment PM Record (ID:" + pKeyID + ")",
        "myFormsModalNrmlTitle",
        "myFormsModalNrmlBody",
        function () {
            $("#astPmRecDetForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable("#oneAccbAstPmRecExtrInfTable")) {
                var table1 = $("#oneAccbAstPmRecExtrInfTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneAccbAstPmRecExtrInfTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalNrml").css("overflow","auto");
                });
            $("#allOtherInputData99").val(0);
            $("#myFormsModalNrml").off("hidden.bs.modal");
            $("#myFormsModalNrml").one("hidden.bs.modal",function (e) {
                $("#myFormsModalNrmlTitle").html("");
                $("#myFormsModalNrmlBody").html("");
                getAccbAstPmRec(
                    "clear",
                    "#assetDetlsPMRecs",
                    "grp=6&typ=1&pg=9&vtyp=3"
                );
                $(e.currentTarget).unbind();
            });
        }
    );
}

function saveAccbAssetPmRecForm() {
    var astPmRecDate =
        typeof $("#astPmRecDate").val() === "undefined"
            ? ""
            : $("#astPmRecDate").val();
    var sbmtdAssetID =
        typeof $("#sbmtdAssetID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAssetID").val();
    var sbmtdAssetPmRecID =
        typeof $("#sbmtdAssetPmRecID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAssetPmRecID").val();
    var astPmRecType =
        typeof $("#astPmRecType").val() === "undefined"
            ? ""
            : $("#astPmRecType").val();

    var astPmRecUOMNm =
        typeof $("#astPmRecUOMNm").val() === "undefined"
            ? ""
            : $("#astPmRecUOMNm").val();
    var astPmRecStrtFig =
        typeof $("#astPmRecStrtFig").val() === "undefined"
            ? "0.00"
            : $("#astPmRecStrtFig").val();
    var astPmRecEndFig =
        typeof $("#astPmRecEndFig").val() === "undefined"
            ? "0.00"
            : $("#astPmRecEndFig").val();
    var astPmRecPMAction =
        typeof $("#astPmRecPMAction").val() === "undefined"
            ? ""
            : $("#astPmRecPMAction").val();
    var astPmRecPMDesc =
        typeof $("#astPmRecPMDesc").val() === "undefined"
            ? ""
            : $("#astPmRecPMDesc").val();
    var astPmRecIsPMDone =
        typeof $("input[name='astPmRecIsPMDone']:checked").val() === "undefined"
            ? "NO"
            : $("input[name='astPmRecIsPMDone']:checked").val();
    var errMsg = "";
    if (Number(sbmtdAssetID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Asset cannot be empty!</span></p>';
    }
    if (astPmRecDate.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Record Date cannot be empty!</span></p>';
    }
    if (astPmRecPMDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">PM Description cannot be empty!</span></p>';
    }
    if (astPmRecType.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Measurement Type cannot be empty!</span></p>';
    }
    if (astPmRecUOMNm.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Unit of Measure cannot be empty!</span></p>';
    }
    if (astPmRecPMAction.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">PM Action Taken cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdExtraInfoLines = "";
    $("#oneAccbAssetsExtrInfTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_DfltRowID = $(
                        "#oneAccbAstPmRecExtrInfRow" + rndmNum + "_DfltRowID"
                    ).val();
                    var ln_CombntnID = $(
                        "#oneAccbAstPmRecExtrInfRow" + rndmNum + "_CombntnID"
                    ).val();
                    var ln_TableID = $(
                        "#oneAccbAstPmRecExtrInfRow" + rndmNum + "_TableID"
                    ).val();
                    var ln_Value = $(
                        "#oneAccbAstPmRecExtrInfRow" + rndmNum + "_Value"
                    ).val();
                    var ln_extrInfoCtgry = $(
                        "#oneAccbAstPmRecExtrInfRow" + rndmNum + "_extrInfoCtgry"
                    ).val();
                    var ln_extrInfoLbl = $(
                        "#oneAccbAstPmRecExtrInfRow" + rndmNum + "_extrInfoLbl"
                    ).val();

                    if (isVld === true) {
                        slctdExtraInfoLines =
                            slctdExtraInfoLines +
                            ln_DfltRowID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_CombntnID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_TableID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoCtgry
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoLbl
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_Value.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "|";
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var shdClose = 0;
    var dialog = bootbox.alert({
        title: "Save PM Record",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving PM Record...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                $("#myFormsModalNrml").modal("hide");
                getAccbAstPmRec(
                    "clear",
                    "#assetDetlsPMRecs",
                    "grp=6&typ=1&pg=9&vtyp=3"
                );
            }
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 9,
                    q: "UPDATE",
                    actyp: 3,
                    sbmtdAssetID: sbmtdAssetID,
                    sbmtdAssetPmRecID: sbmtdAssetPmRecID,
                    astPmRecDate: astPmRecDate,
                    astPmRecType: astPmRecType,
                    astPmRecUOMNm: astPmRecUOMNm,
                    astPmRecStrtFig: astPmRecStrtFig,
                    astPmRecEndFig: astPmRecEndFig,
                    astPmRecPMAction: astPmRecPMAction,
                    astPmRecPMDesc: astPmRecPMDesc,
                    astPmRecIsPMDone: astPmRecIsPMDone,
                    slctdExtraInfoLines: slctdExtraInfoLines,
                },
                success: function (result) {
                    dialog.find(".bootbox-body").html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdVltID = result.vltid;
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function delAccbAssetPmRec(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: "Delete PM Record?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this PM Record?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete PM Record?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting PM Record...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 9,
                                    q: "DELETE",
                                    actyp: 4,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function getAccbPyblsInvc(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbPyblsInvcSrchFor").val() === "undefined"
            ? "%"
            : $("#accbPyblsInvcSrchFor").val();
    var srchIn =
        typeof $("#accbPyblsInvcSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbPyblsInvcSrchIn").val();
    var pageNo =
        typeof $("#accbPyblsInvcPageNo").val() === "undefined"
            ? 1
            : $("#accbPyblsInvcPageNo").val();
    var limitSze =
        typeof $("#accbPyblsInvcDsplySze").val() === "undefined"
            ? 10
            : $("#accbPyblsInvcDsplySze").val();
    var sortBy =
        typeof $("#accbPyblsInvcSortBy").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcSortBy").val();
    var qShwUnpstdOnly = $("#accbPyblsInvcShwUnpstdOnly:checked").length > 0;
    var qShwUnpaidOnly = $("#accbPyblsInvcShwUnpaidOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly +
        "&qShwUnpaidOnly=" +
        qShwUnpaidOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbPyblsInvc(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbPyblsInvc(actionText,slctr,linkArgs);
    }
}

function getPyblsFrmTmplate(slctr) {
    var accbPyblsInvcInvcCur =
        typeof $("#accbPyblsInvcInvcCur1").text() === "undefined"
            ? ""
            : $("#accbPyblsInvcInvcCur1").text();
    var sbmtdTempltLovID =
        typeof $("#accbPyblsInvcDocTmpltID").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDocTmpltID").val();
    var lnkArgs =
        "grp=6&typ=1&pg=10&vtyp=4&sbmtdTempltLovID=" +
        sbmtdTempltLovID +
        "&accbPyblsInvcInvcCur=" +
        accbPyblsInvcInvcCur;
    openATab("#" + slctr,lnkArgs);
}

function insertNewAccbPyblsInvcRows(
    tableElmntID,
    position,
    inptHtml,
    lineType
) {
    var accbPyblsInvcInvcCur1 =
        typeof $("#accbPyblsInvcInvcCur1").text() === "undefined"
            ? ""
            : $("#accbPyblsInvcInvcCur1").text();
    var accbPyblsInvcDesc =
        typeof $("#accbPyblsInvcDesc").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDesc").val();
    var accbPyblsInvcDfltBalsAcnt =
        typeof $("#accbPyblsInvcDfltBalsAcnt").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDfltBalsAcnt").val();
    var accbPyblsInvcDfltBalsAcntID =
        typeof $("#accbPyblsInvcDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcDfltBalsAcntID").val();
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
        $("#oneAccbPyblsInvcSmryRow" + nwRndm + "_TrnsCurNm").val(
            accbPyblsInvcInvcCur1
        );
        $("#oneAccbPyblsInvcSmryRow" + nwRndm + "_TrnsCurNm1").text(
            accbPyblsInvcInvcCur1
        );
        $("#oneAccbPyblsInvcSmryRow" + nwRndm + "_AccountID2").val(
            accbPyblsInvcDfltBalsAcntID
        );
        $("#oneAccbPyblsInvcSmryRow" + nwRndm + "_AccountNm2").val(
            accbPyblsInvcDfltBalsAcnt
        );
        if (lineType !== "1Initial Amount") {
            $("#oneAccbPyblsInvcSmryRow" + nwRndm + "_LineDesc").val(lineType);
            $("#oneAccbPyblsInvcSmryRow_" + nwRndm + " td:nth-child(7)").text("");
            $("#oneAccbPyblsInvcSmryRow_" + nwRndm + " td:nth-child(8)").text("");
            $("#oneAccbPyblsInvcSmryRow_" + nwRndm + " td:nth-child(9)").text("");
            $("#oneAccbPyblsInvcSmryRow_" + nwRndm + " td:nth-child(10)").text("");
        } else {
            $("#oneAccbPyblsInvcSmryRow" + nwRndm + "_LineDesc").val(
                accbPyblsInvcDesc
            );
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
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function getAccbPyblsCodeBhndInfo(rowIDAttrb) {
    if (typeof rowIDAttrb === "undefined" || rowIDAttrb === null) {
        rowIDAttrb = "NOTAROW_1";
    }
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdAccbPyblsInvcID =
        typeof $("#sbmtdAccbPyblsInvcID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAccbPyblsInvcID").val();
    var accbPyblsInvcSpplrID =
        typeof $("#accbPyblsInvcSpplrID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcSpplrID").val();
    var accbPyblsInvcSpplrSiteID =
        typeof $("#accbPyblsInvcSpplrSiteID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcSpplrSiteID").val();
    var accbPyblsInvcInvcCurID =
        typeof $("#accbPyblsInvcInvcCurID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcInvcCurID").val();
    var accbPyblsInvcVchType =
        typeof $("#accbPyblsInvcVchType").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcVchType").val();
    var ln_CodeBhndID =
        typeof $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_CodeBhndID").val() ===
            "undefined"
            ? "-1"
            : $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_CodeBhndID").val();
    var ln_ItemType =
        typeof $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_ItemType").val() ===
            "undefined"
            ? "-1"
            : $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_ItemType").val();
    var accbPyblsInvcDesc =
        typeof $("#accbPyblsInvcDesc").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcDesc").val();

    var ttlInitAmount = 0;
    var ttlRwAmount = 0;
    var ttlDscntAmount = 0;
    $("#oneAccbPyblsInvcSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var prfxName = $(el).attr("id").split("_")[0];
                    var lineType = $("#" + prfxName + rndmNum + "_ItemType").val();
                    var lnsWHTax = $("#" + prfxName + rndmNum + "_IsWHTax").val();
                    ttlRwAmount = (
                        $("#" + prfxName + rndmNum + "_EntrdAmt").val() + ","
                    ).replace(/,/g,"");
                    if (lineType == "3Discount") {
                        ttlDscntAmount = ttlDscntAmount + Number(ttlRwAmount);
                    } else if (lineType == "1Initial Amount") {
                        ttlInitAmount = ttlInitAmount + Number(ttlRwAmount);
                    }
                }
            }
        });
    if (accbPyblsInvcVchType !== "" && sbmtdAccbPyblsInvcID > 0) {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append("grp",6);
            formData.append("typ",1);
            formData.append("pg",10);
            formData.append("q","VIEW");
            formData.append("vtyp",5);
            formData.append("sbmtdAccbPyblsInvcID",sbmtdAccbPyblsInvcID);
            formData.append("accbPyblsInvcSpplrID",accbPyblsInvcSpplrID);
            formData.append("accbPyblsInvcSpplrSiteID",accbPyblsInvcSpplrSiteID);
            formData.append("accbPyblsInvcInvcCurID",accbPyblsInvcInvcCurID);
            formData.append("accbPyblsInvcVchType",accbPyblsInvcVchType);
            formData.append("accbPyblsInvcDesc",accbPyblsInvcDesc);
            formData.append("ln_CodeBhndID",ln_CodeBhndID);
            formData.append("ln_ItemType",ln_ItemType);
            formData.append("ttlInitAmount",ttlInitAmount);
            formData.append("ttlDscntAmount",ttlDscntAmount);
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (rowIDAttrb === "NOTAROW_1") {
                        $("#accbPyblsInvcDfltBalsAcnt").val(data.BalsAcntNm);
                        $("#accbPyblsInvcDfltBalsAcntID").val(data.BalsAcntID);
                        $("#accbPyblsInvcSpplrSite").val(data.accbPyblsInvcSpplrSiteNm);
                        $("#accbPyblsInvcSpplrSiteID").val(data.accbPyblsInvcSpplrSiteID);
                    } else {
                        $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_ApldDocNum").val(
                            data.txsmmryNm
                        );
                        $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_LineDesc").val(
                            data.txlineDesc
                        );
                        $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_EntrdAmt").val(
                            data.codeAmnt
                        );
                        $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_IncrsDcrs1").val(
                            data.CostAcntIncsDcrs
                        );
                        $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_AccountNm1").val(
                            data.CostAcntNm
                        );
                        $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_AccountID1").val(
                            data.CostAcntID
                        );
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    }
}

function getAccbPyblsInvcLovsPage(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm1,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc,
    psblValIDElmntID
) {
    var criteriaID2Nw = criteriaID2;
    var criteriaID3Nw = criteriaID3;
    var addtnlWhereNw = addtnlWhere;
    if (typeof callBackFunc === "undefined" || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (lovNm1 === "") {
        lovNm1 = "RhoUndefined";
    }
    var lovNm1Val =
        typeof $("#" + lovNm1).val() === "undefined" ? "" : $("#" + lovNm1).val();
    var sbmtdAccbPyblsInvcID =
        typeof $("#sbmtdAccbPyblsInvcID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAccbPyblsInvcID").val();
    var accbPyblsInvcSpplrID =
        typeof $("#accbPyblsInvcSpplrID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcSpplrID").val();
    var accbPyblsInvcInvcCurID =
        typeof $("#accbPyblsInvcInvcCurID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcInvcCurID").val();
    var accbPyblsInvcVchType =
        typeof $("#accbPyblsInvcVchType").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcVchType").val();
    var lovNm = "";
    if (lovNm1Val === "2Tax") {
        lovNm = "Tax Codes";
    } else if (lovNm1Val === "3Discount") {
        lovNm = "Discount Codes";
    } else if (lovNm1Val === "4Extra Charge") {
        lovNm = "Extra Charges";
    } else if (lovNm1Val === "5Applied Prepayment") {
        if (
            accbPyblsInvcVchType === "Supplier Advance Payment" ||
            accbPyblsInvcVchType === "Supplier Credit Memo (InDirect Refund)" ||
            accbPyblsInvcVchType === "Supplier Debit Memo (InDirect Topup)"
        ) {
            var errMsg = "Cannot Apply Prepayments to this Document Type!";
            bootbox.alert({
                title: "System Alert!",
                /*size: 'small',*/
                message: errMsg,
            });
            return false;
        }
        criteriaID2Nw = accbPyblsInvcSpplrID;
        criteriaID3Nw = accbPyblsInvcInvcCurID;
        addtnlWhereNw =
            " and (chartonumeric(tbl1.a) NOT IN (Select appld_prepymnt_doc_id FROM accb.accb_pybls_amnt_smmrys WHERE src_pybls_hdr_id =" +
            sbmtdAccbPyblsInvcID +
            "))";
        lovNm = "Supplier Prepayments";
        if (accbPyblsInvcVchType === "Direct Refund from Supplier") {
            lovNm = "Supplier Debit Memos";
        }
    } else {
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2Nw,
        criteriaID3Nw,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhereNw,
        callBackFunc,
        psblValIDElmntID
    );
}

function lnkdEvntAccbPyblsInvcChng() {
    var accbPyblsInvcEvntDocTyp =
        typeof $("#accbPyblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcEvntDocTyp").val();
    var lovNm = "";
    if (accbPyblsInvcEvntDocTyp === "None") {
        $("#accbPyblsInvcEvntCtgryLbl").attr("disabled","true");
        $("#accbPyblsInvcEvntCtgry").val("");
        $("#accbPyblsInvcEvntRgstr").val("");
        $("#accbPyblsInvcEvntRgstrID").val("-1");
        $("#accbPyblsInvcEvntRgstrLbl").attr("disabled","true");
    } else if (accbPyblsInvcEvntDocTyp === "Customer File Number") {
        $("#accbPyblsInvcEvntCtgryLbl").attr("disabled","true");
        $("#accbPyblsInvcEvntCtgry").val("Petty Cash");
        $("#accbPyblsInvcEvntRgstr").val("");
        $("#accbPyblsInvcEvntRgstrID").val("-1");
        $("#accbPyblsInvcEvntRgstrLbl").attr("disabled","true");
    } else {
        $("#accbPyblsInvcEvntCtgryLbl").removeAttr("disabled");
        $("#accbPyblsInvcEvntCtgry").val("");
        $("#accbPyblsInvcEvntRgstr").val("");
        $("#accbPyblsInvcEvntRgstrID").val("-1");
        $("#accbPyblsInvcEvntRgstrLbl").removeAttr("disabled");
    }
}

function getlnkdEvtAccbPILovCtgry(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc
) {
    var accbPyblsInvcEvntDocTyp =
        typeof $("#accbPyblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcEvntDocTyp").val();
    var accbPyblsInvcEvntRgstrID =
        typeof $("#accbPyblsInvcEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcEvntRgstrID").val();
    if (
        accbPyblsInvcEvntDocTyp === "Attendance Register" ||
        accbPyblsInvcEvntDocTyp === "Project Management"
    ) {
        lovNm = "Event Cost Categories";
    } else if (accbPyblsInvcEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Run Stages";
    } else {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: "Category not Required for this Type",
        });
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc
    );
}

function getlnkdEvtAccbPILovEvnt(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc
) {
    var accbPyblsInvcEvntDocTyp =
        typeof $("#accbPyblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcEvntDocTyp").val();
    var accbPyblsInvcEvntRgstrID =
        typeof $("#accbPyblsInvcEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcEvntRgstrID").val();
    if (accbPyblsInvcEvntDocTyp === "Attendance Register") {
        lovNm = "Attendance Registers";
    } else if (accbPyblsInvcEvntDocTyp === "Project Management") {
        return false;
    } else if (accbPyblsInvcEvntDocTyp === "Customer File Number") {
        lovNm = "Customer File Numbers";
    } else if (accbPyblsInvcEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Runs";
    } else {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: "Linked Document not Required for this Type",
        });
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc
    );
}

function getOneAccbPyblsInvcDocsForm(pKeyID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=10&vtyp=" + vwtype + "&sbmtdAccbPyblsInvcID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        "ShowDialog",
        "Payables Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            var table1 = $("#attchdPyblsInvcDocsTable").DataTable({
                paging: false,
                ordering: false,
                info: false,
                bFilter: false,
                scrollX: false,
            });
            $("#attchdPyblsInvcDocsTable").wrap('<div class="dataTables_scroll"/>');
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdPyblsInvcDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function uploadFileToPyblsInvcDocs(
    inptElmntID,
    attchIDElmntID,
    docNmElmntID,
    sbmtdHdrID,
    rowIDAttrb
) {
    var docCtrgrName = $("#" + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    $("#" + inptElmntID).val("");
    $("#" + inptElmntID).off("change");
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToPyblsInvcDocs(
            input.files[0],
            docNmElmntID,
            attchIDElmntID,
            sbmtdHdrID,
            function (data) {
                $("#" + attchIDElmntID).val(data.attchID);
                var dialog = bootbox.alert({
                    title: "Server Response!",
                    size: "small",
                    message: '<div id="myInformation">' + data.message + "</div>",
                    callback: function () {
                        if (data.message.indexOf("Success") !== -1) {
                        }
                    },
                });
            }
        );
    });
    performFileClick(inptElmntID);
}

function sendFileToPyblsInvcDocs(
    file,
    docNmElmntID,
    attchIDElmntID,
    sbmtdHdrID,
    callBackFunc
) {
    var data1 = new FormData();
    data1.append("daPyblsInvcAttchmnt",file);
    data1.append("grp",6);
    data1.append("typ",1);
    data1.append("pg",10);
    data1.append("q","UPDATE");
    data1.append("actyp",20);
    data1.append("docCtrgrName",$("#" + docNmElmntID).val());
    data1.append("attchmentID",$("#" + attchIDElmntID).val());
    data1.append("sbmtdAccbPyblsInvcID",sbmtdHdrID);
    var dialog1 = bootbox.alert({
        title: "Uploading File...",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>',
    });
    dialog1.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    dialog1.modal("hide");
                    callBackFunc(data);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAttchdPyblsInvcDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var srchFor =
        typeof $("#attchdPyblsInvcDocsSrchFor").val() === "undefined"
            ? "%"
            : $("#attchdPyblsInvcDocsSrchFor").val();
    var srchIn =
        typeof $("#attchdPyblsInvcDocsSrchIn").val() === "undefined"
            ? "Both"
            : $("#attchdPyblsInvcDocsSrchIn").val();
    var pageNo =
        typeof $("#attchdPyblsInvcDocsPageNo").val() === "undefined"
            ? 1
            : $("#attchdPyblsInvcDocsPageNo").val();
    var limitSze =
        typeof $("#attchdPyblsInvcDocsDsplySze").val() === "undefined"
            ? 10
            : $("#attchdPyblsInvcDocsDsplySze").val();
    var sortBy =
        typeof $("#attchdPyblsInvcDocsSortBy").val() === "undefined"
            ? ""
            : $("#attchdPyblsInvcDocsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    doAjaxWthCallBck(
        linkArgs,
        "myFormsModaly",
        actionDialog,
        "Payable Invoice Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            if (!$.fn.DataTable.isDataTable("#attchdPyblsInvcDocsTable")) {
                var table1 = $("#attchdPyblsInvcDocsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#attchdPyblsInvcDocsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdPyblsInvcDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function enterKeyFuncAttchdPyblsInvcDocs(
    e,
    actionText,
    slctr,
    linkArgs,
    actionDialog
) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdPyblsInvcDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdPyblsInvcDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID =
        typeof $("#sbmtdAccbPyblsInvcID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbPyblsInvcID").val();
    var docNum =
        typeof $("#accbPyblsInvcDocNum").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDocNum").val();
    var pKeyID = -1;
    if (
        typeof $("#attchdPyblsInvcDocsRow" + rndmNum + "_AttchdDocsID").val() ===
        "undefined"
    ) {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $("#attchdPyblsInvcDocsRow" + rndmNum + "_AttchdDocsID").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Document?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Document?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Document?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 10,
                                    q: "DELETE",
                                    actyp: 5,
                                    attchmentID: pKeyID,
                                    sbmtdHdrID: sbmtdHdrID,
                                    docTrnsNum: docNum,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbPyblsInvc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Payables Document";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 10,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbPyblsInvcDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#accbPyblsInvcDocNum").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Payables Line";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 10,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function saveAccbPyblsInvcForm(funcur,shdSbmt) {
    calcAllAccbPyblsInvcSmryTtl();
    var sbmtdAccbPyblsInvcID =
        typeof $("#sbmtdAccbPyblsInvcID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAccbPyblsInvcID").val();
    var accbPyblsInvcDocNum =
        typeof $("#accbPyblsInvcDocNum").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDocNum").val();
    var accbPyblsInvcDfltTrnsDte =
        typeof $("#accbPyblsInvcDfltTrnsDte").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDfltTrnsDte").val();
    var accbPyblsInvcVchType =
        typeof $("#accbPyblsInvcVchType").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcVchType").val();
    var accbPyblsInvcPayMthdID =
        typeof $("#accbPyblsInvcPayMthdID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcPayMthdID").val();
    var accbPyblsInvcDocTmplt =
        typeof $("#accbPyblsInvcDocTmplt").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDocTmplt").val();
    var srcPyblsInvcDocTyp =
        typeof $("#srcPyblsInvcDocTyp").val() === "undefined"
            ? ""
            : $("#srcPyblsInvcDocTyp").val();
    var srcPyblsInvcDocID =
        typeof $("#srcPyblsInvcDocID").val() === "undefined"
            ? "-1"
            : $("#srcPyblsInvcDocID").val();
    var accbPyblsInvcPayTerms =
        typeof $("#accbPyblsInvcPayTerms").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcPayTerms").val();
    var firtsChequeNum =
        typeof $("#firtsChequeNum").val() === "undefined"
            ? ""
            : $("#firtsChequeNum").val();
    var nextPartPayment =
        typeof $("#nextPartPayment").val() === "undefined"
            ? "0.00"
            : $("#nextPartPayment").val();
    var accbPyblsInvcSpplrInvcNum =
        typeof $("#accbPyblsInvcSpplrInvcNum").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcSpplrInvcNum").val();
    var accbPyblsInvcInvcCur =
        typeof $("#accbPyblsInvcInvcCur").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcInvcCur").val();
    var accbPyblsInvcTtlAmnt =
        typeof $("#accbPyblsInvcTtlAmnt").val() === "undefined"
            ? "0.00"
            : $("#accbPyblsInvcTtlAmnt").val();
    var accbPyblsInvcEvntDocTyp =
        typeof $("#accbPyblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcEvntDocTyp").val();
    var accbPyblsInvcEvntCtgry =
        typeof $("#accbPyblsInvcEvntCtgry").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcEvntCtgry").val();
    var accbPyblsInvcEvntRgstrID =
        typeof $("#accbPyblsInvcEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcEvntCtgry").val();
    var accbPyblsInvcSpplrID =
        typeof $("#accbPyblsInvcSpplrID").val() === "undefined"
            ? "-1"
            : $("#accbPyblsInvcSpplrID").val();
    var accbPyblsInvcSpplrSiteID =
        typeof $("#accbPyblsInvcSpplrSiteID").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcSpplrSiteID").val();
    var accbPyblsInvcDesc =
        typeof $("#accbPyblsInvcDesc").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDesc").val();
    var accbPyblsInvcDfltBalsAcntID =
        typeof $("#accbPyblsInvcDfltBalsAcntID").val() === "undefined"
            ? -1
            : $("#accbPyblsInvcDfltBalsAcntID").val();
    var myCptrdPyblsInvcValsTtlVal =
        typeof $("#myCptrdPyblsInvcValsTtlVal").val() === "undefined"
            ? 0
            : $("#myCptrdPyblsInvcValsTtlVal").val();
    var accbPyblsInvcFuncCrncyRate =
        typeof $("#accbPyblsInvcFuncCrncyRate").val() === "undefined"
            ? "1.0000"
            : $("#accbPyblsInvcFuncCrncyRate").val();
    var errMsg = "";
    if (accbPyblsInvcDocNum.trim() === "" || accbPyblsInvcVchType.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number/Type cannot be empty!</span></p>';
    }
    if (
        accbPyblsInvcDfltTrnsDte.trim() === "" ||
        accbPyblsInvcInvcCur.trim() === ""
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Date/Currency cannot be empty!</span></p>';
    }
    if (Number(accbPyblsInvcPayMthdID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Payment Method cannot be empty!</span></p>';
    }
    if (Number(accbPyblsInvcSpplrID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Vendor/Supplier cannot be empty!</span></p>';
    }
    if (Number(accbPyblsInvcSpplrSiteID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Vendor/Supplier Site cannot be empty!</span></p>';
    }
    if (Number(accbPyblsInvcDfltBalsAcntID.replace(/[^-?0-9\.]/g,"")) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Liability Account cannot be empty!</span></p>';
    }
    if (accbPyblsInvcDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
    }
    if (accbPyblsInvcSpplrInvcNum.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            "font-weight:bold;color:red;\">Supplier's Invoice Number cannot be empty!</span></p>";
    }
    accbPyblsInvcTtlAmnt = fmtAsNumber("accbPyblsInvcTtlAmnt").toFixed(2);
    myCptrdPyblsInvcValsTtlVal = fmtAsNumber(
        "myCptrdPyblsInvcValsTtlVal"
    ).toFixed(2);
    nextPartPayment = fmtAsNumber("nextPartPayment").toFixed(2);
    accbPyblsInvcFuncCrncyRate = fmtAsNumber2(
        "accbPyblsInvcFuncCrncyRate"
    ).toFixed(4);
    if (myCptrdPyblsInvcValsTtlVal !== accbPyblsInvcTtlAmnt) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    var slctdExtraInfoLines = "";
    $("#oneAccbPyblsInvcSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var lineType = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_ItemType"
                    ).val();
                    var lineDesc = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_LineDesc"
                    ).val();
                    var ln_CodeBhndID = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_CodeBhndID"
                    ).val();
                    var lineCurNm = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_TrnsCurNm1"
                    ).text();
                    var lineEntrdAmt = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_EntrdAmt"
                    ).val();
                    var lineIncrsDcrs1 = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_IncrsDcrs1"
                    ).val();
                    var lineAccntID1 = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_AccountID1"
                    ).val();
                    var ln_InitAmntLnID = $(
                        "#oneAccbPyblsInvcSmryRow" + rndmNum + "_InitAmntLnID"
                    ).val();
                    var ln_TaxID =
                        typeof $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_TaxID").val() ===
                            "undefined"
                            ? "-1"
                            : $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_TaxID").val();
                    var ln_WHTaxID =
                        typeof $(
                            "#oneAccbPyblsInvcSmryRow" + rndmNum + "_WHTaxID"
                        ).val() === "undefined"
                            ? "-1"
                            : $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_WHTaxID").val();
                    var ln_DscntID =
                        typeof $(
                            "#oneAccbPyblsInvcSmryRow" + rndmNum + "_DscntID"
                        ).val() === "undefined"
                            ? "-1"
                            : $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_DscntID").val();
                    var ln_AutoCalc =
                        typeof $(
                            "input[name='oneAccbPyblsInvcSmryRow" +
                            rndmNum +
                            "_AutoCalc']:checked"
                        ).val() === "undefined"
                            ? "NO"
                            : "YES";
                    if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) !== 0) {
                        if (lineType.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Line Type for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_ItemType").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_ItemType").removeClass(
                                "rho-error"
                            );
                        }
                        if (lineDesc.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Narration/Description for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_LineDesc").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_LineDesc").removeClass(
                                "rho-error"
                            );
                        }
                        if (lineCurNm.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Currency for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_TrnsCurNm").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbPyblsInvcSmryRow" + rndmNum + "_TrnsCurNm"
                            ).removeClass("rho-error");
                        }
                        if (lineIncrsDcrs1.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Increase/Decrease 1 for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_IncrsDcrs1").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbPyblsInvcSmryRow" + rndmNum + "_IncrsDcrs1"
                            ).removeClass("rho-error");
                        }
                        if (
                            Number(lineAccntID1.replace(/[^-?0-9\.]/g,"")) <= 0 &&
                            (lineType === "1Initial Amount" ||
                                lineType === "5Applied Prepayment")
                        ) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">GL Charge Account for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_AccountNm1").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbPyblsInvcSmryRow" + rndmNum + "_AccountNm1"
                            ).removeClass("rho-error");
                        }
                        if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) === 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Transaction Amount for Row No. ' +
                                i +
                                " cannot be zero!</span></p>";
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_EntrdAmt").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_EntrdAmt").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === true) {
                            slctdDetTransLines =
                                slctdDetTransLines +
                                $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_TrnsLnID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_SlctdAmtBrkdwns")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_RefDoc")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineType.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineIncrsDcrs1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineAccntID1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineDesc.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_CodeBhndID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineCurNm.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineEntrdAmt
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_AutoCalc
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbPyblsInvcSmryRow" + rndmNum + "_FuncExchgRate")
                                    .val()
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_TaxID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_WHTaxID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_DscntID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_InitAmntLnID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    $("#oneAccbPyblsInvcExtrInfTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_DfltRowID = $(
                        "#oneAccbPyblsInvcExtrInfRow" + rndmNum + "_DfltRowID"
                    ).val();
                    var ln_CombntnID = $(
                        "#oneAccbPyblsInvcExtrInfRow" + rndmNum + "_CombntnID"
                    ).val();
                    var ln_TableID = $(
                        "#oneAccbPyblsInvcExtrInfRow" + rndmNum + "_TableID"
                    ).val();
                    var ln_Value = $(
                        "#oneAccbPyblsInvcExtrInfRow" + rndmNum + "_Value"
                    ).val();
                    var ln_extrInfoCtgry = $(
                        "#oneAccbPyblsInvcExtrInfRow" + rndmNum + "_extrInfoCtgry"
                    ).val();
                    var ln_extrInfoLbl = $(
                        "#oneAccbPyblsInvcExtrInfRow" + rndmNum + "_extrInfoLbl"
                    ).val();
                    if (isVld === true) {
                        slctdExtraInfoLines =
                            slctdExtraInfoLines +
                            ln_DfltRowID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_CombntnID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_TableID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoCtgry
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoLbl
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_Value.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "|";
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Payable Invoice",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Payable Invoice...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",10);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("sbmtdAccbPyblsInvcID",sbmtdAccbPyblsInvcID);
    formData.append("accbPyblsInvcDocNum",accbPyblsInvcDocNum);
    formData.append("accbPyblsInvcDfltTrnsDte",accbPyblsInvcDfltTrnsDte);
    formData.append("accbPyblsInvcVchType",accbPyblsInvcVchType);
    formData.append("accbPyblsInvcPayMthdID",accbPyblsInvcPayMthdID);
    formData.append("accbPyblsInvcDocTmplt",accbPyblsInvcDocTmplt);
    formData.append("srcPyblsInvcDocTyp",srcPyblsInvcDocTyp);
    formData.append("srcPyblsInvcDocID",srcPyblsInvcDocID);
    formData.append("accbPyblsInvcPayTerms",accbPyblsInvcPayTerms);
    formData.append("firtsChequeNum",firtsChequeNum);
    formData.append("nextPartPayment",nextPartPayment);
    formData.append("accbPyblsInvcSpplrInvcNum",accbPyblsInvcSpplrInvcNum);
    formData.append("accbPyblsInvcInvcCur",accbPyblsInvcInvcCur);
    formData.append("accbPyblsInvcTtlAmnt",accbPyblsInvcTtlAmnt);
    formData.append("accbPyblsInvcFuncCrncyRate",accbPyblsInvcFuncCrncyRate);
    formData.append("accbPyblsInvcEvntDocTyp",accbPyblsInvcEvntDocTyp);
    formData.append("accbPyblsInvcEvntCtgry",accbPyblsInvcEvntCtgry);
    formData.append("accbPyblsInvcEvntRgstrID",accbPyblsInvcEvntRgstrID);
    formData.append("accbPyblsInvcSpplrID",accbPyblsInvcSpplrID);
    formData.append("accbPyblsInvcSpplrSiteID",accbPyblsInvcSpplrSiteID);
    formData.append("accbPyblsInvcDfltBalsAcntID",accbPyblsInvcDfltBalsAcntID);
    formData.append("accbPyblsInvcDesc",accbPyblsInvcDesc);
    formData.append("myCptrdPyblsInvcValsTtlVal",myCptrdPyblsInvcValsTtlVal);
    formData.append("shdSbmt",shdSbmt);
    formData.append("slctdDetTransLines",slctdDetTransLines);
    formData.append("slctdExtraInfoLines",slctdExtraInfoLines);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (
                            data.message.indexOf("Success") !== -1 ||
                            data.message.indexOf("SUCCESS") !== -1
                        ) {
                            sbmtdAccbPyblsInvcID = data.sbmtdAccbPyblsInvcID;
                            getOneAccbPyblsInvcForm(sbmtdAccbPyblsInvcID,1,"ReloadDialog");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function saveAccbPyblsInvcRvrslForm(funcCur,shdSbmt) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslAccbPyblsInvcBtn");
    }

    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdAccbPyblsInvcID =
        typeof $("#sbmtdAccbPyblsInvcID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbPyblsInvcID").val();
    var accbPyblsInvcDesc =
        typeof $("#accbPyblsInvcDesc").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDesc").val();
    var accbPyblsInvcDesc1 =
        typeof $("#accbPyblsInvcDesc1").val() === "undefined"
            ? ""
            : $("#accbPyblsInvcDesc1").val();
    var errMsg = "";
    if (sbmtdAccbPyblsInvcID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (accbPyblsInvcDesc === "" || accbPyblsInvcDesc === accbPyblsInvcDesc1) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#accbPyblsInvcDesc").addClass("rho-error");
        $("#accbPyblsInvcDesc").attr("readonly",false);
        $("#fnlzeRvrslAccbPyblsInvcBtn").attr("disabled",false);
    } else {
        $("#accbPyblsInvcDesc").removeClass("rho-error");
    }
    if (rhotrim(errMsg,"; ") !== "") {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var msgsTitle = "Payable Invoice";
    var msgBody = "";
    msgBody =
        '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' +
        msgsTitle +
        "?<br/>This action cannot be UNDONE!</p>";

    var dialog = bootbox.confirm({
        title: "Void " + msgsTitle + "?",
        size: "small",
        message: msgBody,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var msg = "Payable Invoice";
                var dialog = bootbox.alert({
                    title: "Save " + msg,
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
                        msg +
                        "...Please Wait...</p>",
                    callback: function () {
                        sbmtdAccbPyblsInvcID =
                            typeof $("#sbmtdAccbPyblsInvcID").val() === "undefined"
                                ? -1
                                : $("#sbmtdAccbPyblsInvcID").val();
                        if (sbmtdAccbPyblsInvcID > 0) {
                            getOneAccbPyblsInvcForm(sbmtdAccbPyblsInvcID,1,"ReloadDialog");
                        }
                    },
                });
                dialog.init(function () {
                    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 6,
                                typ: 1,
                                pg: 10,
                                actyp: 1,
                                q: "VOID",
                                accbPyblsInvcDesc: accbPyblsInvcDesc,
                                sbmtdAccbPyblsInvcID: sbmtdAccbPyblsInvcID,
                                shdSbmt: shdSbmt,
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                if (
                                    /^[\],:{}\s]*$/.test(
                                        data
                                            .replace(/\\["\\\/bfnrtu]/g,"@")
                                            .replace(
                                                /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                                                "]"
                                            )
                                            .replace(/(?:^|:|,)(?:\s*\[)+/g,"")
                                    )
                                ) {
                                    obj = $.parseJSON(data);
                                    sbmtdAccbPyblsInvcID = obj.sbmtdAccbPyblsInvcID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdAccbPyblsInvcID > 0) {
                                        $("#sbmtdAccbPyblsInvcID").val(sbmtdAccbPyblsInvcID);
                                    }
                                    if (msg.trim() === "") {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find(".bootbox-body").html(msg);
                                },500);
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            },
                        });
                    });
                });
            }
        },
    });
}

function getAccbRcvblsInvc(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbRcvblsInvcSrchFor").val() === "undefined"
            ? "%"
            : $("#accbRcvblsInvcSrchFor").val();
    var srchIn =
        typeof $("#accbRcvblsInvcSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbRcvblsInvcSrchIn").val();
    var pageNo =
        typeof $("#accbRcvblsInvcPageNo").val() === "undefined"
            ? 1
            : $("#accbRcvblsInvcPageNo").val();
    var limitSze =
        typeof $("#accbRcvblsInvcDsplySze").val() === "undefined"
            ? 10
            : $("#accbRcvblsInvcDsplySze").val();
    var sortBy =
        typeof $("#accbRcvblsInvcSortBy").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcSortBy").val();
    var qShwUnpstdOnly = $("#accbRcvblsInvcShwUnpstdOnly:checked").length > 0;
    var qShwUnpaidOnly = $("#accbRcvblsInvcShwUnpaidOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUnpaidOnly=" +
        qShwUnpaidOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbRcvblsInvc(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbRcvblsInvc(actionText,slctr,linkArgs);
    }
}

function getRcvblsFrmTmplate(slctr) {
    var accbRcvblsInvcInvcCur =
        typeof $("#accbRcvblsInvcInvcCur1").text() === "undefined"
            ? ""
            : $("#accbRcvblsInvcInvcCur1").text();
    var sbmtdTempltLovID =
        typeof $("#accbRcvblsInvcDocTmpltID").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDocTmpltID").val();
    var lnkArgs =
        "grp=6&typ=1&pg=11&vtyp=4&sbmtdTempltLovID=" +
        sbmtdTempltLovID +
        "&accbRcvblsInvcInvcCur=" +
        accbRcvblsInvcInvcCur;
    openATab("#" + slctr,lnkArgs);
}

function insertNewAccbRcvblsInvcRows(
    tableElmntID,
    position,
    inptHtml,
    lineType
) {
    var accbRcvblsInvcInvcCur1 =
        typeof $("#accbRcvblsInvcInvcCur1").text() === "undefined"
            ? ""
            : $("#accbRcvblsInvcInvcCur1").text();
    var accbRcvblsInvcDesc =
        typeof $("#accbRcvblsInvcDesc").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDesc").val();
    var accbRcvblsInvcDfltBalsAcnt =
        typeof $("#accbRcvblsInvcDfltBalsAcnt").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDfltBalsAcnt").val();
    var accbRcvblsInvcDfltBalsAcntID =
        typeof $("#accbRcvblsInvcDfltBalsAcntID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcDfltBalsAcntID").val();
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
        }
        $("#oneAccbRcvblsInvcSmryRow" + nwRndm + "_TrnsCurNm").val(
            accbRcvblsInvcInvcCur1
        );
        $("#oneAccbRcvblsInvcSmryRow" + nwRndm + "_TrnsCurNm1").text(
            accbRcvblsInvcInvcCur1
        );
        $("#oneAccbRcvblsInvcSmryRow" + nwRndm + "_AccountID2").val(
            accbRcvblsInvcDfltBalsAcntID
        );
        $("#oneAccbRcvblsInvcSmryRow" + nwRndm + "_AccountNm2").val(
            accbRcvblsInvcDfltBalsAcnt
        );
        if (lineType !== "1Initial Amount") {
            $("#oneAccbRcvblsInvcSmryRow" + nwRndm + "_LineDesc").val(lineType);
            $("#oneAccbRcvblsInvcSmryRow_" + nwRndm + " td:nth-child(7)").text("");
            $("#oneAccbRcvblsInvcSmryRow_" + nwRndm + " td:nth-child(8)").text("");
            $("#oneAccbRcvblsInvcSmryRow_" + nwRndm + " td:nth-child(9)").text("");
            $("#oneAccbRcvblsInvcSmryRow_" + nwRndm + " td:nth-child(10)").text("");
        } else {
            $("#oneAccbRcvblsInvcSmryRow" + nwRndm + "_LineDesc").val(
                accbRcvblsInvcDesc
            );
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
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function getAccbRcvblsCodeBhndInfo(rowIDAttrb) {
    if (typeof rowIDAttrb === "undefined" || rowIDAttrb === null) {
        rowIDAttrb = "NOTAROW_1";
    }
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdAccbRcvblsInvcID =
        typeof $("#sbmtdAccbRcvblsInvcID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAccbRcvblsInvcID").val();
    var accbRcvblsInvcCstmrID =
        typeof $("#accbRcvblsInvcCstmrID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcCstmrID").val();
    var accbRcvblsInvcCstmrSiteID =
        typeof $("#accbRcvblsInvcCstmrSiteID").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcCstmrSiteID").val();
    var accbRcvblsInvcInvcCurID =
        typeof $("#accbRcvblsInvcInvcCurID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcInvcCurID").val();
    var accbRcvblsInvcVchType =
        typeof $("#accbRcvblsInvcVchType").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcVchType").val();
    var ln_CodeBhndID =
        typeof $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_CodeBhndID").val() ===
            "undefined"
            ? "-1"
            : $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_CodeBhndID").val();
    var ln_ItemType =
        typeof $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_ItemType").val() ===
            "undefined"
            ? "-1"
            : $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_ItemType").val();
    var accbRcvblsInvcDesc =
        typeof $("#accbRcvblsInvcDesc").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcDesc").val();

    var ttlInitAmount = 0;
    var ttlRwAmount = 0;
    var ttlDscntAmount = 0;
    $("#oneAccbPyblsInvcSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var prfxName = $(el).attr("id").split("_")[0];
                    var lineType = $("#" + prfxName + rndmNum + "_ItemType").val();
                    var lnsWHTax = $("#" + prfxName + rndmNum + "_IsWHTax").val();
                    ttlRwAmount = (
                        $("#" + prfxName + rndmNum + "_EntrdAmt").val() + ","
                    ).replace(/,/g,"");
                    if (lineType == "3Discount") {
                        ttlDscntAmount = ttlDscntAmount + Number(ttlRwAmount);
                    } else if (lineType == "1Initial Amount") {
                        ttlInitAmount = ttlInitAmount + Number(ttlRwAmount);
                    }
                }
            }
        });
    if (accbRcvblsInvcVchType !== "" && sbmtdAccbRcvblsInvcID > 0) {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append("grp",6);
            formData.append("typ",1);
            formData.append("pg",11);
            formData.append("q","VIEW");
            formData.append("vtyp",5);
            formData.append("sbmtdAccbRcvblsInvcID",sbmtdAccbRcvblsInvcID);
            formData.append("accbRcvblsInvcCstmrID",accbRcvblsInvcCstmrID);
            formData.append("accbRcvblsInvcCstmrSiteID",accbRcvblsInvcCstmrSiteID);
            formData.append("accbRcvblsInvcInvcCurID",accbRcvblsInvcInvcCurID);
            formData.append("accbRcvblsInvcVchType",accbRcvblsInvcVchType);
            formData.append("accbRcvblsInvcDesc",accbRcvblsInvcDesc);
            formData.append("ln_CodeBhndID",ln_CodeBhndID);
            formData.append("ln_ItemType",ln_ItemType);
            formData.append("ttlInitAmount",ttlInitAmount);
            formData.append("ttlDscntAmount",ttlDscntAmount);
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (rowIDAttrb === "NOTAROW_1") {
                        $("#accbRcvblsInvcDfltBalsAcnt").val(data.BalsAcntNm);
                        $("#accbRcvblsInvcDfltBalsAcntID").val(data.BalsAcntID);
                        $("#accbRcvblsInvcCstmrSite").val(data.accbRcvblsInvcCstmrSite);
                        $("#accbRcvblsInvcCstmrSiteID").val(data.accbRcvblsInvcCstmrSiteID);
                    } else {
                        $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_ApldDocNum").val(
                            data.txsmmryNm
                        );
                        $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_LineDesc").val(
                            data.txlineDesc
                        );
                        $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_EntrdAmt").val(
                            data.codeAmnt
                        );
                        $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_IncrsDcrs1").val(
                            data.CostAcntIncsDcrs
                        );
                        $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_AccountNm1").val(
                            data.CostAcntNm
                        );
                        $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_AccountID1").val(
                            data.CostAcntID
                        );
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    }
}

function getAccbRcvblsInvcLovsPage(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm1,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc,
    psblValIDElmntID
) {
    var criteriaID2Nw = criteriaID2;
    var criteriaID3Nw = criteriaID3;
    var addtnlWhereNw = addtnlWhere;
    if (typeof callBackFunc === "undefined" || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (lovNm1 === "") {
        lovNm1 = "RhoUndefined";
    }
    var lovNm1Val =
        typeof $("#" + lovNm1).val() === "undefined" ? "" : $("#" + lovNm1).val();
    var sbmtdAccbRcvblsInvcID =
        typeof $("#sbmtdAccbRcvblsInvcID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAccbRcvblsInvcID").val();
    var accbRcvblsInvcCstmrID =
        typeof $("#accbRcvblsInvcCstmrID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcCstmrID").val();
    var accbRcvblsInvcInvcCurID =
        typeof $("#accbRcvblsInvcInvcCurID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcInvcCurID").val();
    var accbRcvblsInvcVchType =
        typeof $("#accbRcvblsInvcVchType").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcVchType").val();
    var lovNm = "";
    if (lovNm1Val === "2Tax") {
        lovNm = "Tax Codes";
    } else if (lovNm1Val === "3Discount") {
        lovNm = "Discount Codes";
    } else if (lovNm1Val === "4Extra Charge") {
        lovNm = "Extra Charges";
    } else if (lovNm1Val === "5Applied Prepayment") {
        if (
            accbRcvblsInvcVchType === "Customer Advance Payment" ||
            accbRcvblsInvcVchType === "Customer Credit Memo (InDirect Topup)" ||
            accbRcvblsInvcVchType === "Supplier Debit Memo (InDirect Topup)"
        ) {
            var errMsg = "Cannot Apply Prepayments to this Document Type!";
            bootbox.alert({
                title: "System Alert!",
                /*size: 'small',*/
                message: errMsg,
            });
            return false;
        }
        criteriaID2Nw = accbRcvblsInvcCstmrID;
        criteriaID3Nw = accbRcvblsInvcInvcCurID;
        addtnlWhereNw =
            " and (chartonumeric(tbl1.a) NOT IN (Select appld_prepymnt_doc_id FROM accb.accb_rcvbl_amnt_smmrys WHERE src_rcvbl_hdr_id =" +
            sbmtdAccbRcvblsInvcID +
            "))";
        lovNm = "Customer Prepayments";
        if (accbRcvblsInvcVchType === "Direct Refund from Supplier") {
            lovNm = "Customer Credit Memos";
        }
    } else {
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2Nw,
        criteriaID3Nw,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhereNw,
        callBackFunc,
        psblValIDElmntID
    );
}

function lnkdEvntAccbRcvblsInvcChng() {
    var accbRcvblsInvcEvntDocTyp =
        typeof $("#accbRcvblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcEvntDocTyp").val();
    var lovNm = "";
    if (accbRcvblsInvcEvntDocTyp === "None") {
        $("#accbRcvblsInvcEvntCtgryLbl").attr("disabled","true");
        $("#accbRcvblsInvcEvntCtgry").val("");
        $("#accbRcvblsInvcEvntRgstr").val("");
        $("#accbRcvblsInvcEvntRgstrID").val("-1");
        $("#accbRcvblsInvcEvntRgstrLbl").attr("disabled","true");
    } else if (accbRcvblsInvcEvntDocTyp === "Customer File Number") {
        $("#accbRcvblsInvcEvntCtgryLbl").attr("disabled","true");
        $("#accbRcvblsInvcEvntCtgry").val("Petty Cash");
        $("#accbRcvblsInvcEvntRgstr").val("");
        $("#accbRcvblsInvcEvntRgstrID").val("-1");
        $("#accbRcvblsInvcEvntRgstrLbl").attr("disabled","true");
    } else {
        $("#accbRcvblsInvcEvntCtgryLbl").removeAttr("disabled");
        $("#accbRcvblsInvcEvntCtgry").val("");
        $("#accbRcvblsInvcEvntRgstr").val("");
        $("#accbRcvblsInvcEvntRgstrID").val("-1");
        $("#accbRcvblsInvcEvntRgstrLbl").removeAttr("disabled");
    }
}

function getlnkdEvtAccbRILovCtgry(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc
) {
    var accbRcvblsInvcEvntDocTyp =
        typeof $("#accbRcvblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcEvntDocTyp").val();
    var accbRcvblsInvcEvntRgstrID =
        typeof $("#accbRcvblsInvcEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcEvntRgstrID").val();
    if (
        accbRcvblsInvcEvntDocTyp === "Attendance Register" ||
        accbRcvblsInvcEvntDocTyp === "Project Management"
    ) {
        lovNm = "Event Cost Categories";
    } else if (accbRcvblsInvcEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Run Stages";
    } else {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: "Category not Required for this Type",
        });
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc
    );
}

function getlnkdEvtAccbRILovEvnt(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc
) {
    var accbRcvblsInvcEvntDocTyp =
        typeof $("#accbRcvblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcEvntDocTyp").val();
    var accbRcvblsInvcEvntRgstrID =
        typeof $("#accbRcvblsInvcEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcEvntRgstrID").val();
    if (accbRcvblsInvcEvntDocTyp === "Attendance Register") {
        lovNm = "Attendance Registers";
    } else if (accbRcvblsInvcEvntDocTyp === "Project Management") {
        return false;
    } else if (accbRcvblsInvcEvntDocTyp === "Customer File Number") {
        lovNm = "Customer File Numbers";
    } else if (accbRcvblsInvcEvntDocTyp === "Production Process Run") {
        lovNm = "Production Process Runs";
    } else {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: "Linked Document not Required for this Type",
        });
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc
    );
}

function getOneAccbRcvblsInvcDocsForm(pKeyID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=11&vtyp=" + vwtype + "&sbmtdAccbRcvblsInvcID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        "ShowDialog",
        "Receivables Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            var table1 = $("#attchdRcvblsInvcDocsTable").DataTable({
                paging: false,
                ordering: false,
                info: false,
                bFilter: false,
                scrollX: false,
            });
            $("#attchdRcvblsInvcDocsTable").wrap('<div class="dataTables_scroll"/>');
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdRcvblsInvcDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function uploadFileToRcvblsInvcDocs(
    inptElmntID,
    attchIDElmntID,
    docNmElmntID,
    sbmtdHdrID,
    rowIDAttrb
) {
    var docCtrgrName = $("#" + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    $("#" + inptElmntID).val("");
    $("#" + inptElmntID).off("change");
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToRcvblsInvcDocs(
            input.files[0],
            docNmElmntID,
            attchIDElmntID,
            sbmtdHdrID,
            function (data) {
                $("#" + attchIDElmntID).val(data.attchID);
                var dialog = bootbox.alert({
                    title: "Server Response!",
                    size: "small",
                    message: '<div id="myInformation">' + data.message + "</div>",
                    callback: function () {
                        if (data.message.indexOf("Success") !== -1) {
                        }
                    },
                });
            }
        );
    });
    performFileClick(inptElmntID);
}

function sendFileToRcvblsInvcDocs(
    file,
    docNmElmntID,
    attchIDElmntID,
    sbmtdHdrID,
    callBackFunc
) {
    var data1 = new FormData();
    data1.append("daRcvblsInvcAttchmnt",file);
    data1.append("grp",6);
    data1.append("typ",1);
    data1.append("pg",11);
    data1.append("q","UPDATE");
    data1.append("actyp",20);
    data1.append("docCtrgrName",$("#" + docNmElmntID).val());
    data1.append("attchmentID",$("#" + attchIDElmntID).val());
    data1.append("sbmtdAccbRcvblsInvcID",sbmtdHdrID);
    var dialog1 = bootbox.alert({
        title: "Uploading File...",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>',
    });
    dialog1.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    dialog1.modal("hide");
                    callBackFunc(data);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAttchdRcvblsInvcDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var srchFor =
        typeof $("#attchdRcvblsInvcDocsSrchFor").val() === "undefined"
            ? "%"
            : $("#attchdRcvblsInvcDocsSrchFor").val();
    var srchIn =
        typeof $("#attchdRcvblsInvcDocsSrchIn").val() === "undefined"
            ? "Both"
            : $("#attchdRcvblsInvcDocsSrchIn").val();
    var pageNo =
        typeof $("#attchdRcvblsInvcDocsPageNo").val() === "undefined"
            ? 1
            : $("#attchdRcvblsInvcDocsPageNo").val();
    var limitSze =
        typeof $("#attchdRcvblsInvcDocsDsplySze").val() === "undefined"
            ? 10
            : $("#attchdRcvblsInvcDocsDsplySze").val();
    var sortBy =
        typeof $("#attchdRcvblsInvcDocsSortBy").val() === "undefined"
            ? ""
            : $("#attchdRcvblsInvcDocsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    doAjaxWthCallBck(
        linkArgs,
        "myFormsModaly",
        actionDialog,
        "Receivable Invoice Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            if (!$.fn.DataTable.isDataTable("#attchdRcvblsInvcDocsTable")) {
                var table1 = $("#attchdRcvblsInvcDocsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#attchdRcvblsInvcDocsTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdRcvblsInvcDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function enterKeyFuncAttchdRcvblsInvcDocs(
    e,
    actionText,
    slctr,
    linkArgs,
    actionDialog
) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdRcvblsInvcDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdRcvblsInvcDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID =
        typeof $("#sbmtdAccbRcvblsInvcID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbRcvblsInvcID").val();
    var docNum =
        typeof $("#accbRcvblsInvcDocNum").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDocNum").val();
    var pKeyID = -1;
    if (
        typeof $("#attchdRcvblsInvcDocsRow" + rndmNum + "_AttchdDocsID").val() ===
        "undefined"
    ) {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $("#attchdRcvblsInvcDocsRow" + rndmNum + "_AttchdDocsID").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Document?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Document?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Document?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 11,
                                    q: "DELETE",
                                    actyp: 5,
                                    attchmentID: pKeyID,
                                    sbmtdHdrID: sbmtdHdrID,
                                    docTrnsNum: docNum,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbRcvblsInvc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Receivables Document";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 11,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbRcvblsInvcDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#accbRcvblsInvcDocNum").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Receivables Line";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 11,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function saveAccbRcvblsInvcForm(funcur,shdSbmt) {
    calcAllAccbRcvblsInvcSmryTtl();
    var sbmtdAccbRcvblsInvcID =
        typeof $("#sbmtdAccbRcvblsInvcID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbRcvblsInvcID").val();
    var accbRcvblsInvcDocNum =
        typeof $("#accbRcvblsInvcDocNum").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDocNum").val();
    var accbRcvblsInvcDfltTrnsDte =
        typeof $("#accbRcvblsInvcDfltTrnsDte").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDfltTrnsDte").val();
    var accbRcvblsInvcVchType =
        typeof $("#accbRcvblsInvcVchType").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcVchType").val();
    var accbRcvblsInvcInvcCur =
        typeof $("#accbRcvblsInvcInvcCur").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcInvcCur").val();
    var accbRcvblsInvcTtlAmnt =
        typeof $("#accbRcvblsInvcTtlAmnt").val() === "undefined"
            ? "0.00"
            : $("#accbRcvblsInvcTtlAmnt").val();
    var accbRcvblsInvcFuncCrncyRate =
        typeof $("#accbRcvblsInvcFuncCrncyRate").val() === "undefined"
            ? "1.0000"
            : $("#accbRcvblsInvcFuncCrncyRate").val();
    var accbRcvblsInvcEvntDocTyp =
        typeof $("#accbRcvblsInvcEvntDocTyp").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcEvntDocTyp").val();
    var accbRcvblsInvcEvntCtgry =
        typeof $("#accbRcvblsInvcEvntCtgry").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcEvntCtgry").val();
    var accbRcvblsInvcEvntRgstrID =
        typeof $("#accbRcvblsInvcEvntRgstrID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcEvntCtgry").val();
    var accbRcvblsInvcCstmrID =
        typeof $("#accbRcvblsInvcCstmrID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcCstmrID").val();
    var accbRcvblsInvcCstmrSiteID =
        typeof $("#accbRcvblsInvcCstmrSiteID").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcCstmrSiteID").val();
    var accbRcvblsInvcDesc =
        typeof $("#accbRcvblsInvcDesc").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDesc").val();
    var accbRcvblsInvcPayTerms =
        typeof $("#accbRcvblsInvcPayTerms").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcPayTerms").val();
    var accbRcvblsInvcDfltBalsAcntID =
        typeof $("#accbRcvblsInvcDfltBalsAcntID").val() === "undefined"
            ? -1
            : $("#accbRcvblsInvcDfltBalsAcntID").val();
    var myCptrdRcvblsInvcValsTtlVal =
        typeof $("#myCptrdRcvblsInvcValsTtlVal").val() === "undefined"
            ? 0
            : $("#myCptrdRcvblsInvcValsTtlVal").val();

    var accbRcvblsInvcPayMthdID =
        typeof $("#accbRcvblsInvcPayMthdID").val() === "undefined"
            ? "-1"
            : $("#accbRcvblsInvcPayMthdID").val();
    var accbRcvblsInvcDocTmplt =
        typeof $("#accbRcvblsInvcDocTmplt").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDocTmplt").val();
    var srcRcvblsInvcDocTyp =
        typeof $("#srcRcvblsInvcDocTyp").val() === "undefined"
            ? ""
            : $("#srcRcvblsInvcDocTyp").val();
    var srcRcvblsInvcDocID =
        typeof $("#srcRcvblsInvcDocID").val() === "undefined"
            ? "-1"
            : $("#srcRcvblsInvcDocID").val();
    var accbRcvblsInvcCstmrInvcNum =
        typeof $("#accbRcvblsInvcCstmrInvcNum").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcCstmrInvcNum").val();

    var errMsg = "";
    if (
        Number(srcRcvblsInvcDocID.replace(/[^-?0-9\.]/g,"")) > 0 &&
        srcRcvblsInvcDocTyp.indexOf("Customer") === -1
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cannot work on Documents from Other Modules Here!</span></p>';
    }
    if (
        accbRcvblsInvcDocNum.trim() === "" ||
        accbRcvblsInvcVchType.trim() === ""
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Document Number/Type cannot be empty!</span></p>';
    }
    if (accbRcvblsInvcDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
    }
    if (
        accbRcvblsInvcDfltTrnsDte.trim() === "" ||
        accbRcvblsInvcInvcCur.trim() === ""
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Batch Date/Currency cannot be empty!</span></p>';
    }
    accbRcvblsInvcTtlAmnt = fmtAsNumber("accbRcvblsInvcTtlAmnt").toFixed(2);
    myCptrdRcvblsInvcValsTtlVal = fmtAsNumber(
        "myCptrdRcvblsInvcValsTtlVal"
    ).toFixed(2);
    accbRcvblsInvcFuncCrncyRate = fmtAsNumber2(
        "accbRcvblsInvcFuncCrncyRate"
    ).toFixed(4);
    if (myCptrdRcvblsInvcValsTtlVal !== accbRcvblsInvcTtlAmnt) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Header Total Amount must agree with Transaction Lines Total!</span></p>';
    }
    var isVld = true;
    var slctdDetTransLines = "";
    var slctdExtraInfoLines = "";
    $("#oneAccbRcvblsInvcSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var lineType = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_ItemType"
                    ).val();
                    var lineDesc = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_LineDesc"
                    ).val();
                    var ln_CodeBhndID = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_CodeBhndID"
                    ).val();
                    var lineCurNm = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_TrnsCurNm1"
                    ).text();
                    var lineEntrdAmt = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_EntrdAmt"
                    ).val();
                    var lineIncrsDcrs1 = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_IncrsDcrs1"
                    ).val();
                    var lineAccntID1 = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_AccountID1"
                    ).val();
                    var lineQty = $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_QTY").val();
                    var ln_AutoCalc =
                        typeof $(
                            "input[name='oneAccbRcvblsInvcSmryRow" +
                            rndmNum +
                            "_AutoCalc']:checked"
                        ).val() === "undefined"
                            ? "NO"
                            : "YES";
                    var ln_InitAmntLnID = $(
                        "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_InitAmntLnID"
                    ).val();
                    var ln_TaxID =
                        typeof $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_TaxID").val() ===
                            "undefined"
                            ? "-1"
                            : $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_TaxID").val();
                    var ln_WHTaxID =
                        typeof $(
                            "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_WHTaxID"
                        ).val() === "undefined"
                            ? "-1"
                            : $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_WHTaxID").val();
                    var ln_DscntID =
                        typeof $(
                            "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_DscntID"
                        ).val() === "undefined"
                            ? "-1"
                            : $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_DscntID").val();
                    if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) !== 0) {
                        if (lineType.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Line Type for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_ItemType").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_ItemType"
                            ).removeClass("rho-error");
                        }
                        if (lineDesc.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Narration/Description for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_LineDesc").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_LineDesc"
                            ).removeClass("rho-error");
                        }
                        if (lineCurNm.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Currency for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_TrnsCurNm").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_TrnsCurNm"
                            ).removeClass("rho-error");
                        }
                        if (lineIncrsDcrs1.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Increase/Decrease 1 for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_IncrsDcrs1").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_IncrsDcrs1"
                            ).removeClass("rho-error");
                        }
                        if (Number(lineAccntID1.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">GL Charge Account for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_AccountNm1").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_AccountNm1"
                            ).removeClass("rho-error");
                        }
                        if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) === 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Transaction Amount for Row No. ' +
                                i +
                                " cannot be zero!</span></p>";
                            $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_EntrdAmt").addClass(
                                "rho-error"
                            );
                        } else {
                            $(
                                "#oneAccbRcvblsInvcSmryRow" + rndmNum + "_EntrdAmt"
                            ).removeClass("rho-error");
                        }
                        if (isVld === true) {
                            slctdDetTransLines =
                                slctdDetTransLines +
                                $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_TrnsLnID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_SlctdAmtBrkdwns")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_RefDoc")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineType.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineIncrsDcrs1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineAccntID1
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineDesc.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_CodeBhndID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineCurNm.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineEntrdAmt
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_AutoCalc
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                lineQty
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#oneAccbRcvblsInvcSmryRow" + rndmNum + "_FuncExchgRate")
                                    .val()
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_TaxID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_WHTaxID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_DscntID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_InitAmntLnID
                                    .replace(/[^-?0-9\.]/g,"")
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    $("#oneAccbRcvblsInvcExtrInfTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_DfltRowID = $(
                        "#oneAccbRcvblsInvcExtrInfRow" + rndmNum + "_DfltRowID"
                    ).val();
                    var ln_CombntnID = $(
                        "#oneAccbRcvblsInvcExtrInfRow" + rndmNum + "_CombntnID"
                    ).val();
                    var ln_TableID = $(
                        "#oneAccbRcvblsInvcExtrInfRow" + rndmNum + "_TableID"
                    ).val();
                    var ln_extrInfoCtgry = $(
                        "#oneAccbRcvblsInvcExtrInfRow" + rndmNum + "_extrInfoCtgry"
                    ).val();
                    var ln_extrInfoLbl = $(
                        "#oneAccbRcvblsInvcExtrInfRow" + rndmNum + "_extrInfoLbl"
                    ).val();
                    var ln_Value = $(
                        "#oneAccbRcvblsInvcExtrInfRow" + rndmNum + "_Value"
                    ).val();
                    if (isVld === true) {
                        slctdExtraInfoLines =
                            slctdExtraInfoLines +
                            ln_DfltRowID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_CombntnID
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_TableID.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoCtgry
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_extrInfoLbl
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_Value.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "|";
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Receivable Invoice",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Receivable Invoice...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",11);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("sbmtdAccbRcvblsInvcID",sbmtdAccbRcvblsInvcID);
    formData.append("accbRcvblsInvcDocNum",accbRcvblsInvcDocNum);
    formData.append("accbRcvblsInvcDfltTrnsDte",accbRcvblsInvcDfltTrnsDte);
    formData.append("accbRcvblsInvcVchType",accbRcvblsInvcVchType);
    formData.append("accbRcvblsInvcInvcCur",accbRcvblsInvcInvcCur);
    formData.append("accbRcvblsInvcTtlAmnt",accbRcvblsInvcTtlAmnt);
    formData.append("accbRcvblsInvcFuncCrncyRate",accbRcvblsInvcFuncCrncyRate);
    formData.append("accbRcvblsInvcEvntDocTyp",accbRcvblsInvcEvntDocTyp);
    formData.append("accbRcvblsInvcEvntCtgry",accbRcvblsInvcEvntCtgry);
    formData.append("accbRcvblsInvcEvntRgstrID",accbRcvblsInvcEvntRgstrID);
    formData.append("accbRcvblsInvcCstmrID",accbRcvblsInvcCstmrID);
    formData.append("accbRcvblsInvcCstmrSiteID",accbRcvblsInvcCstmrSiteID);
    formData.append("accbRcvblsInvcDfltBalsAcntID",accbRcvblsInvcDfltBalsAcntID);
    formData.append("accbRcvblsInvcPayTerms",accbRcvblsInvcPayTerms);
    formData.append("accbRcvblsInvcDesc",accbRcvblsInvcDesc);

    formData.append("accbRcvblsInvcPayMthdID",accbRcvblsInvcPayMthdID);
    formData.append("accbRcvblsInvcDocTmplt",accbRcvblsInvcDocTmplt);
    formData.append("srcRcvblsInvcDocTyp",srcRcvblsInvcDocTyp);
    formData.append("srcRcvblsInvcDocID",srcRcvblsInvcDocID);
    formData.append("accbRcvblsInvcCstmrInvcNum",accbRcvblsInvcCstmrInvcNum);

    formData.append("shdSbmt",shdSbmt);
    formData.append("slctdDetTransLines",slctdDetTransLines);
    formData.append("slctdExtraInfoLines",slctdExtraInfoLines);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (
                            data.message.indexOf("Success") !== -1 ||
                            data.message.indexOf("SUCCESS") !== -1
                        ) {
                            sbmtdAccbRcvblsInvcID = data.sbmtdAccbRcvblsInvcID;
                            getOneAccbRcvblsInvcForm(
                                sbmtdAccbRcvblsInvcID,
                                1,
                                "ReloadDialog"
                            );
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function saveAccbRcvblsInvcRvrslForm(funcCur,shdSbmt) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslAccbRcvblsInvcBtn");
    }

    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdAccbRcvblsInvcID =
        typeof $("#sbmtdAccbRcvblsInvcID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbRcvblsInvcID").val();
    var accbRcvblsInvcDesc =
        typeof $("#accbRcvblsInvcDesc").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDesc").val();
    var accbRcvblsInvcDesc1 =
        typeof $("#accbRcvblsInvcDesc1").val() === "undefined"
            ? ""
            : $("#accbRcvblsInvcDesc1").val();
    var srcRcvblsInvcDocTyp =
        typeof $("#srcRcvblsInvcDocTyp").val() === "undefined"
            ? ""
            : $("#srcRcvblsInvcDocTyp").val();
    var srcRcvblsInvcDocID =
        typeof $("#srcRcvblsInvcDocID").val() === "undefined"
            ? "-1"
            : $("#srcRcvblsInvcDocID").val();
    var errMsg = "";
    if (
        Number(srcRcvblsInvcDocID.replace(/[^-?0-9\.]/g,"")) > 0 &&
        srcRcvblsInvcDocTyp.indexOf("Customer") === -1
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cannot work on Documents from Other Modules Here!</span></p>';
    }
    if (sbmtdAccbRcvblsInvcID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (accbRcvblsInvcDesc === "" || accbRcvblsInvcDesc === accbRcvblsInvcDesc1) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#accbRcvblsInvcDesc").addClass("rho-error");
        $("#accbRcvblsInvcDesc").attr("readonly",false);
        $("#fnlzeRvrslAccbRcvblsInvcBtn").attr("disabled",false);
    } else {
        $("#accbRcvblsInvcDesc").removeClass("rho-error");
    }
    if (rhotrim(errMsg,"; ") !== "") {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var msgsTitle = "Receivable Invoice";
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' +
            msgsTitle +
            "?<br/>This action cannot be UNDONE!</p>";
    }
    var dialog = bootbox.confirm({
        title: "Void " + msgsTitle + "?",
        size: "small",
        message: msgBody,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var msg = "Receivable Invoice";
                var dialog = bootbox.alert({
                    title: "Save " + msg,
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
                        msg +
                        "...Please Wait...</p>",
                    callback: function () {
                        sbmtdAccbRcvblsInvcID =
                            typeof $("#sbmtdAccbRcvblsInvcID").val() === "undefined"
                                ? -1
                                : $("#sbmtdAccbRcvblsInvcID").val();
                        if (sbmtdAccbRcvblsInvcID > 0) {
                            getOneAccbRcvblsInvcForm(
                                sbmtdAccbRcvblsInvcID,
                                1,
                                "ReloadDialog"
                            );
                        }
                    },
                });
                dialog.init(function () {
                    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 6,
                                typ: 1,
                                pg: 11,
                                actyp: 1,
                                q: "VOID",
                                accbRcvblsInvcDesc: accbRcvblsInvcDesc,
                                sbmtdAccbRcvblsInvcID: sbmtdAccbRcvblsInvcID,
                                shdSbmt: shdSbmt,
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (
                                    /^[\],:{}\s]*$/.test(
                                        data
                                            .replace(/\\["\\\/bfnrtu]/g,"@")
                                            .replace(
                                                /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                                                "]"
                                            )
                                            .replace(/(?:^|:|,)(?:\s*\[)+/g,"")
                                    )
                                ) {
                                    obj = $.parseJSON(data);
                                    sbmtdAccbRcvblsInvcID = obj.sbmtdAccbRcvblsInvcID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdAccbRcvblsInvcID > 0) {
                                        $("#sbmtdAccbRcvblsInvcID").val(sbmtdAccbRcvblsInvcID);
                                    }
                                    if (msg.trim() === "") {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find(".bootbox-body").html(msg);
                                },500);
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            },
                        });
                    });
                });
            }
        },
    });
}

function getAccbPymnts(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbPymntsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbPymntsSrchFor").val();
    var srchIn =
        typeof $("#accbPymntsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbPymntsSrchIn").val();
    var pageNo =
        typeof $("#accbPymntsPageNo").val() === "undefined"
            ? 1
            : $("#accbPymntsPageNo").val();
    var limitSze =
        typeof $("#accbPymntsDsplySze").val() === "undefined"
            ? 10
            : $("#accbPymntsDsplySze").val();
    var sortBy =
        typeof $("#accbPymntsSortBy").val() === "undefined"
            ? ""
            : $("#accbPymntsSortBy").val();
    var qShwUsrOnly = $("#accbPymntsShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#accbPymntsShwUnpstdOnly:checked").length > 0;
    var qStrtDte =
        typeof $("#accbPymntsStrtDate").val() === "undefined"
            ? ""
            : $("#accbPymntsStrtDate").val();
    var qEndDte =
        typeof $("#accbPymntsEndDate").val() === "undefined"
            ? ""
            : $("#accbPymntsEndDate").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly +
        "&qStrtDte=" +
        qStrtDte +
        "&qEndDte=" +
        qEndDte;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbPymnts(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbPymnts(actionText,slctr,linkArgs);
    }
}

function getOneAccbPymntsForm(pKeyID,vwtype,actionTxt,accbPymntsDocType) {
    var accbPymntsVchType = "Supplier Payments";
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    if (typeof accbPymntsDocType === "undefined" || accbPymntsDocType === null) {
        accbPymntsDocType = "Supplier Standard Payment";
    }
    if (accbPymntsDocType.indexOf("Customer") !== -1) {
        accbPymntsVchType = "Customer Payments";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=12&vtyp=" +
        vwtype +
        "&sbmtdAccbPymntsID=" +
        pKeyID +
        "&accbPymntsDocType=" +
        accbPymntsDocType +
        "&accbPymntsVchType=" +
        accbPymntsVchType;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalLg",
        actionTxt,
        "Payment Details (ID:" + pKeyID + ")",
        "myFormsModalTitleLg",
        "myFormsModalBodyLg",
        function () {
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLg").css("overflow","auto");
                });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLg").css("overflow","auto");
                });
            $("#allOtherInputData99").val("0");
            $("#oneAccbPymntsEDTForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#myFormsModalLg").off("hidden.bs.modal");
            $("#myFormsModalLg").one("hidden.bs.modal",function (e) {
                getAccbPymnts("","#allmodules","grp=6&typ=1&pg=12&vtyp=0");
                $(e.currentTarget).unbind();
            });
            if (!$.fn.DataTable.isDataTable("#oneAccbPymntsSmryLinesTable")) {
                var table1 = $("#oneAccbPymntsSmryLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneAccbPymntsSmryLinesTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            if (!$.fn.DataTable.isDataTable("#oneAccbPymntsRmvdLinesTable")) {
                var table1 = $("#oneAccbPymntsRmvdLinesTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#oneAccbPymntsRmvdLinesTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            $('[data-toggle="tooltip"]').tooltip();

            $('[data-toggle="tabajxpymntsinvc"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr("href");
                var dttrgt = $this.attr("data-rhodata");
                var linkArgs = "grp=6&typ=1" + dttrgt;
                $(targ + "tab").tab("show");
                if (targ.indexOf("pymntsInvcSelected") >= 0) {
                    $("#addNwAccbPymntsRmvBtn").removeClass("hideNotice");
                    $("#addNwAccbPymntsRstrBtn").addClass("hideNotice");
                } else if (targ.indexOf("pymntsInvcRemoved") >= 0) {
                    $("#addNwAccbPymntsRmvBtn").addClass("hideNotice");
                    $("#addNwAccbPymntsRstrBtn").removeClass("hideNotice");
                } else {
                    $("#addNwAccbPymntsRmvBtn").addClass("hideNotice");
                    $("#addNwAccbPymntsRstrBtn").addClass("hideNotice");
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
            $(".jbDetFuncRate").focus(function () {
                $(this).select();
            });
            $(".jbDetAccRate").focus(function () {
                $(this).select();
            });
            calcAllAccbPymntsSmryTtl();
        }
    );
}

function calcAllAccbPymntsSmryTtl() {
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    var ttlAmount1 = 0;
    var ttlRwAmount1 = 0;
    var ttlAmount2 = 0;
    var ttlRwAmount2 = 0;
    var ttlAmount3 = 0;
    var ttlRwAmount3 = 0;
    var ttlAmount4 = 0;
    var ttlRwAmount4 = 0;
    var ttlAmount5 = 0;
    var ttlRwAmount5 = 0;
    $("#oneAccbPymntsSmryLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var prfxName = $(el).attr("id").split("_")[0];
                    ttlRwAmount = (
                        $("#" + prfxName + rndmNum + "_AmtGvn").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount = ttlAmount + Number(ttlRwAmount);
                    ttlRwAmount1 = (
                        $("#" + prfxName + rndmNum + "_AmtPaid").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount1 = ttlAmount1 + Number(ttlRwAmount1);
                    ttlRwAmount2 = (
                        $("#" + prfxName + rndmNum + "_ChngBals").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount2 = ttlAmount2 + Number(ttlRwAmount2);
                }
            }
        });
    $("#oneAccbPymntsRmvdLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var prfxName = $(el).attr("id").split("_")[0];
                    ttlRwAmount3 = (
                        $("#" + prfxName + rndmNum + "_AmtGvn").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount3 = ttlAmount3 + Number(ttlRwAmount3);
                    ttlRwAmount4 = (
                        $("#" + prfxName + rndmNum + "_AmtPaid").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount4 = ttlAmount4 + Number(ttlRwAmount4);
                    ttlRwAmount5 = (
                        $("#" + prfxName + rndmNum + "_ChngBals").val() + ","
                    ).replace(/,/g,"");
                    ttlAmount5 = ttlAmount5 + Number(ttlRwAmount5);
                }
            }
        });
    $("#myCptrdPymntsValsTtlBtn").text(addCommas(ttlAmount.toFixed(2)));
    $("#myCptrdPymntsValsTtlVal").val(ttlAmount.toFixed(2));

    $("#myCptrdPymntsRmvdValsTtlBtn").text(addCommas(ttlAmount3.toFixed(2)));
    $("#myCptrdPymntsRmvdValsTtlVal").val(ttlAmount3.toFixed(2));

    $("#myCptrdPMTGvnSmryAmtTtlBtn").text(addCommas(ttlAmount.toFixed(2)));
    $("#myCptrdPMTGvnSmryAmtTtlVal").val(ttlAmount.toFixed(2));
    $("#myCptrdPMTMdeSmryAmtTtlBtn").text(addCommas(ttlAmount1.toFixed(2)));
    $("#myCptrdPMTMdeSmryAmtTtlVal").val(ttlAmount1.toFixed(2));
    $("#myCptrdPMTBalsSmryAmtTtlBtn").text(addCommas(ttlAmount2.toFixed(2)));
    $("#myCptrdPMTBalsSmryAmtTtlVal").val(ttlAmount2.toFixed(2));

    $("#myCptrdPMTGvnRmvdAmtTtlBtn").text(addCommas(ttlAmount3.toFixed(2)));
    $("#myCptrdPMTGvnRmvdAmtTtlVal").val(ttlAmount3.toFixed(2));
    $("#myCptrdPMTMdeRmvdAmtTtlBtn").text(addCommas(ttlAmount4.toFixed(2)));
    $("#myCptrdPMTMdeRmvdAmtTtlVal").val(ttlAmount4.toFixed(2));
    $("#myCptrdPMTBalsRmvdAmtTtlBtn").text(addCommas(ttlAmount5.toFixed(2)));
    $("#myCptrdPMTBalsRmvdAmtTtlVal").val(ttlAmount5.toFixed(2));
}

function delAccbPymnts(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Payment Batch";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 12,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbPymntsDetLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#accbPymntsBatchNum").val();
        /*var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(2).text());*/
    }
    var msgPrt = "Payment Line";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 12,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function rmvRstrAccbPymntsDetLn(rowIDAttrb,rmvRstrStatus) {
    var sbmtdAccbPymntsID =
        typeof $("#sbmtdAccbPymntsID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAccbPymntsID").val();
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#accbPymntsBatchNum").val();
    }
    var msgPrt = "Payment Line";
    var msgPrt1 = "Restore";
    var msgPrt2 = "Restoring";
    if (rmvRstrStatus === "1") {
        msgPrt1 = "Remove";
        msgPrt2 = "Removing";
    }
    var dialog = bootbox.confirm({
        title: msgPrt1 + " " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">' +
            msgPrt1 +
            "</span> this " +
            msgPrt +
            "?!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: msgPrt1 + " " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> ' +
                        msgPrt2 +
                        " " +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 12,
                                    q: "DELETE",
                                    actyp: 3,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                    pKeyRmvRstr: rmvRstrStatus,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            var nwRandm = getRandomInt2(100000,999999);
                                            var row = $("#" + rowIDAttrb)
                                                .html()
                                                .replaceAll(
                                                    rowPrfxNm + rndmNum + "_",
                                                    rowPrfxNm + nwRandm + "_"
                                                );
                                            row = row.replaceAll(
                                                rowIDAttrb,
                                                rowPrfxNm + "_" + nwRandm
                                            );
                                            if (rmvRstrStatus === "1") {
                                                var search1 = "Remove Payment Document";
                                                var search2 = ", '1');\" data-toggle";
                                                var search3 = 'src="cmn_images/delete.png"';
                                                row = row.replaceAll(
                                                    search1,
                                                    "Restore Payment Document"
                                                );
                                                row = row.replaceAll(search2,", '0');\" data-toggle");
                                                row = row.replaceAll(
                                                    search3,
                                                    'src="cmn_images/undo_256.png"'
                                                );
                                                if (
                                                    $("#oneAccbPymntsRmvdLinesTable > tbody > tr > td")
                                                        .eq(
                                                            $("#oneAccbPymntsRmvdLinesTable > tbody > tr")
                                                                .length - 1
                                                        )
                                                        .text() == "No data available in table"
                                                ) {
                                                    $("#oneAccbPymntsRmvdLinesTable > tbody > tr > td")
                                                        .eq(
                                                            $("#oneAccbPymntsRmvdLinesTable > tbody > tr")
                                                                .length - 1
                                                        )
                                                        .remove();
                                                }
                                                $("#oneAccbPymntsRmvdLinesTable tbody").append(
                                                    '<tr id="' +
                                                    rowPrfxNm +
                                                    "_" +
                                                    nwRandm +
                                                    '">' +
                                                    row +
                                                    "</tr>"
                                                );
                                            } else {
                                                var search1 = "Restore Payment Document";
                                                var search2 = ", '0');\" data-toggle";
                                                var search3 = 'src="cmn_images/undo_256.png"';
                                                row = row.replaceAll(
                                                    search1,
                                                    "Remove Payment Document"
                                                );
                                                row = row.replaceAll(search2,", '1');\" data-toggle");
                                                row = row.replaceAll(
                                                    search3,
                                                    'src="cmn_images/delete.png"'
                                                );
                                                if (
                                                    $("#oneAccbPymntsSmryLinesTable > tbody > tr > td")
                                                        .eq(
                                                            $("#oneAccbPymntsSmryLinesTable > tbody > tr")
                                                                .length - 1
                                                        )
                                                        .text() == "No data available in table"
                                                ) {
                                                    $("#oneAccbPymntsSmryLinesTable > tbody > tr > td")
                                                        .eq(
                                                            $("#oneAccbPymntsSmryLinesTable > tbody > tr")
                                                                .length - 1
                                                        )
                                                        .remove();
                                                }
                                                $("#oneAccbPymntsSmryLinesTable tbody").append(
                                                    '<tr id="' +
                                                    rowPrfxNm +
                                                    "_" +
                                                    nwRandm +
                                                    '">' +
                                                    row +
                                                    "</tr>"
                                                );
                                                getOneAccbPymntsForm(
                                                    sbmtdAccbPymntsID,
                                                    1,
                                                    "ReloadDialog"
                                                );
                                            }
                                            $("#" + rowIDAttrb).remove();
                                            calcAllAccbPymntsSmryTtl();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                            calcAllAccbPymntsSmryTtl();
                        },500);
                    }
                });
            }
        },
    });
}

function saveAccbPymntsForm(funccrnm,shdSbmt,rptID,alrtID,paramsStr) {
    if (typeof funccrnm === "undefined" || funccrnm === null) {
        funccrnm = "GHS";
    }
    if (typeof shdSbmt === "undefined" || shdSbmt === null) {
        shdSbmt = 0;
    }
    if (typeof rptID === "undefined" || rptID === null) {
        rptID = -1;
    }
    if (typeof alrtID === "undefined" || alrtID === null) {
        alrtID = -1;
    }
    if (typeof paramsStr === "undefined" || paramsStr === null) {
        paramsStr = "";
    }
    var errMsg = "";
    calcAllAccbPymntsSmryTtl();
    var sbmtdAccbPymntsID =
        typeof $("#sbmtdAccbPymntsID").val() === "undefined"
            ? "-1"
            : $("#sbmtdAccbPymntsID").val();
    var accbPymntsGvnAmnt =
        typeof $("#accbPymntsGvnAmnt").val() === "undefined"
            ? "0.00"
            : $("#accbPymntsGvnAmnt").val();
    var myCptrdPymntsValsTtlVal =
        typeof $("#myCptrdPymntsValsTtlVal").val() === "undefined"
            ? 0
            : $("#myCptrdPymntsValsTtlVal").val();
    accbPymntsGvnAmnt = fmtAsNumber("accbPymntsGvnAmnt").toFixed(2);
    myCptrdPymntsValsTtlVal = fmtAsNumber("myCptrdPymntsValsTtlVal").toFixed(2);
    if (myCptrdPymntsValsTtlVal > accbPymntsGvnAmnt) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Sum of Amount Given in Payment Lines cannot be more than Total Batch Amount Given!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    if (shdSbmt === 5) {
        getSilentRptsRnSts(rptID,alrtID,paramsStr,function () {
            getOneAccbPymntsForm(sbmtdAccbPymntsID,1,"ReloadDialog");
        });
        return false;
    } else {
        var accbPymntsBatchNum =
            typeof $("#accbPymntsBatchNum").val() === "undefined"
                ? ""
                : $("#accbPymntsBatchNum").val();
        var accbPymntsTrnsStrtDte =
            typeof $("#accbPymntsTrnsStrtDte").val() === "undefined"
                ? ""
                : $("#accbPymntsTrnsStrtDte").val();
        var accbPymntsTrnsEndDte =
            typeof $("#accbPymntsTrnsEndDte").val() === "undefined"
                ? ""
                : $("#accbPymntsTrnsEndDte").val();
        var accbPymntsDocType =
            typeof $("#accbPymntsDocType").val() === "undefined"
                ? ""
                : $("#accbPymntsDocType").val();
        var accbPymntsMthdType =
            typeof $("#accbPymntsMthdType").val() === "undefined"
                ? ""
                : $("#accbPymntsMthdType").val();
        var accbPymntsPayMthdID =
            typeof $("#accbPymntsPayMthdID").val() === "undefined"
                ? "-1"
                : $("#accbPymntsPayMthdID").val();
        var accbPymntsDocTmplt =
            typeof $("#accbPymntsDocTmplt").val() === "undefined"
                ? ""
                : $("#accbPymntsDocTmplt").val();
        var accbPymntsSpplrID =
            typeof $("#accbPymntsSpplrID").val() === "undefined"
                ? "-1"
                : $("#accbPymntsSpplrID").val();
        var accbPymntsDesc =
            typeof $("#accbPymntsDesc").val() === "undefined"
                ? ""
                : $("#accbPymntsDesc").val();
        var accbPymntsGLBatchID =
            typeof $("#accbPymntsGLBatchID").val() === "undefined"
                ? "-1"
                : $("#accbPymntsGLBatchID").val();

        var accbPymntsDfltTrnsDte =
            typeof $("#accbPymntsDfltTrnsDte").val() === "undefined"
                ? ""
                : $("#accbPymntsDfltTrnsDte").val();
        var accbPymntsInvcCur =
            typeof $("#accbPymntsInvcCur").val() === "undefined"
                ? ""
                : $("#accbPymntsInvcCur").val();
        var accbPymntsChqName =
            typeof $("#accbPymntsChqName").val() === "undefined"
                ? ""
                : $("#accbPymntsChqName").val();
        var accbPymntsChqNumber =
            typeof $("#accbPymntsChqNumber").val() === "undefined"
                ? ""
                : $("#accbPymntsChqNumber").val();
        var accbPymntsSignCode =
            typeof $("#accbPymntsSignCode").val() === "undefined"
                ? ""
                : $("#accbPymntsSignCode").val();

        if (
            accbPymntsBatchNum.trim() === "" ||
            accbPymntsMthdType.trim() === "" ||
            accbPymntsDocType.trim() === ""
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Document Number/Type cannot be empty!</span></p>';
        }
        if (accbPymntsDesc.trim() === "") {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Description cannot be empty!</span></p>';
        }
        if (
            accbPymntsDfltTrnsDte.trim() === "" ||
            accbPymntsInvcCur.trim() === ""
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Payment Date/Currency cannot be empty!</span></p>';
        }
        if (Number(accbPymntsPayMthdID.replace(/[^-?0-9\.]/g,"")) <= 0) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Payment Method cannot be empty!</span></p>';
        }

        var isVld = true;
        var slctdDetTransLines = "";
        var slctdRmvdTransLines = "";
        $("#oneAccbPymntsSmryLinesTable")
            .find("tr")
            .each(function (i,el) {
                isVld = true;
                if (i > 0) {
                    if (typeof $(el).attr("id") === "undefined") {
                        /*Do Nothing*/
                    } else {
                        var rndmNum = $(el).attr("id").split("_")[1];
                        var lineDesc = $(
                            "#oneAccbPymntsSmryRow" + rndmNum + "_LineDesc"
                        ).val();
                        var lineCurNm = $(
                            "#oneAccbPymntsSmryRow" + rndmNum + "_TrnsCurNm1"
                        ).text();
                        var lineEntrdAmt = $(
                            "#oneAccbPymntsSmryRow" + rndmNum + "_AmtGvn"
                        ).val();
                        if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) !== 0) {
                            if (lineDesc.trim() === "") {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Narration/Description for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneAccbPymntsSmryRow" + rndmNum + "_LineDesc").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneAccbPymntsSmryRow" + rndmNum + "_LineDesc").removeClass(
                                    "rho-error"
                                );
                            }
                            if (lineCurNm.trim() === "") {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Currency for Row No. ' +
                                    i +
                                    " cannot be empty!</span></p>";
                                $("#oneAccbPymntsSmryRow" + rndmNum + "_TrnsCurNm").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneAccbPymntsSmryRow" + rndmNum + "_TrnsCurNm").removeClass(
                                    "rho-error"
                                );
                            }
                            if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g,"")) <= 0) {
                                isVld = false;
                                errMsg +=
                                    '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Amount Given for Row No. ' +
                                    i +
                                    " cannot be zero or less!</span></p>";
                                $("#oneAccbPymntsSmryRow" + rndmNum + "_AmtGvn").addClass(
                                    "rho-error"
                                );
                            } else {
                                $("#oneAccbPymntsSmryRow" + rndmNum + "_AmtGvn").removeClass(
                                    "rho-error"
                                );
                            }
                            if (isVld === true) {
                                slctdDetTransLines =
                                    slctdDetTransLines +
                                    $("#oneAccbPymntsSmryRow" + rndmNum + "_TrnsLnID")
                                        .val()
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineDesc
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineCurNm
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "~" +
                                    lineEntrdAmt
                                        .replace(/(~)/g,"{-;-;}")
                                        .replace(/(\|)/g,"{:;:;}") +
                                    "|";
                            }
                        }
                    }
                }
            });
        /*$('#oneAccbPymntsRmvdLinesTable').find('tr').each(function (i, el) {
                     isVld = true;
                     if (i > 0)
                     {
                     if (typeof $(el).attr('id') === 'undefined')
                     {
                     
                     } else {
                     var rndmNum = $(el).attr('id').split("_")[1];
                     var lineDesc = $('#oneAccbPymntsRmvdRow' + rndmNum + '_LineDesc').val();
                     var lineCurNm = $('#oneAccbPymntsRmvdRow' + rndmNum + '_TrnsCurNm1').text();
                     var lineEntrdAmt = $('#oneAccbPymntsRmvdRow' + rndmNum + '_AmtGvn').val();
                     if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g, '')) !== 0) {
                     if (lineDesc.trim() === '')
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Narration/Description for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneAccbPymntsRmvdRow' + rndmNum + '_LineDesc').addClass('rho-error');
                     } else {
                     $('#oneAccbPymntsRmvdRow' + rndmNum + '_LineDesc').removeClass('rho-error');
                     }
                     if (lineCurNm.trim() === '')
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Currency for Row No. ' + i + ' cannot be empty!</span></p>';
                     $('#oneAccbPymntsRmvdRow' + rndmNum + '_TrnsCurNm').addClass('rho-error');
                     } else {
                     $('#oneAccbPymntsRmvdRow' + rndmNum + '_TrnsCurNm').removeClass('rho-error');
                     }
                     if (Number(lineEntrdAmt.replace(/[^-?0-9\.]/g, '')) === 0)
                     {
                     isVld = false;
                     errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                     'font-weight:bold;color:red;">Transaction Amount for Row No. ' + i + ' cannot be zero!</span></p>';
                     $('#oneAccbPymntsRmvdRow' + rndmNum + '_AmtGvn').addClass('rho-error');
                     } else {
                     $('#oneAccbPymntsRmvdRow' + rndmNum + '_AmtGvn').removeClass('rho-error');
                     }
                     if (isVld === true) {
                     slctdRmvdTransLines = slctdRmvdTransLines
                     + $('#oneAccbPymntsRmvdRow' + rndmNum + '_TrnsLnID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                     + lineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                     + lineCurNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                     + lineEntrdAmt.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                     }
                     }
                     }
                     }
                     });*/
        if (rhotrim(errMsg,"; ") !== "") {
            bootbox.alert({
                title: "System Alert!",
                /*size: 'small',*/
                message: errMsg,
            });
            return false;
        }
        var dialog = bootbox.alert({
            title: "Save Batch Payment",
            size: "small",
            message:
                '<p><i class="fa fa-spin fa-spinner"></i> Saving Batch Payment...Please Wait...</p>',
            callback: function () { },
        });
        var formData = new FormData();
        formData.append("grp",6);
        formData.append("typ",1);
        formData.append("pg",12);
        formData.append("q","UPDATE");
        formData.append("actyp",1);
        formData.append("sbmtdAccbPymntsID",sbmtdAccbPymntsID);
        formData.append("accbPymntsBatchNum",accbPymntsBatchNum);
        formData.append("accbPymntsTrnsStrtDte",accbPymntsTrnsStrtDte);
        formData.append("accbPymntsTrnsEndDte",accbPymntsTrnsEndDte);
        formData.append("accbPymntsDocType",accbPymntsDocType);
        formData.append("accbPymntsMthdType",accbPymntsMthdType);
        formData.append("accbPymntsPayMthdID",accbPymntsPayMthdID);
        formData.append("accbPymntsDocTmplt",accbPymntsDocTmplt);
        formData.append("accbPymntsSpplrID",accbPymntsSpplrID);
        formData.append("accbPymntsDesc",accbPymntsDesc);
        formData.append("accbPymntsGLBatchID",accbPymntsGLBatchID);
        formData.append("myCptrdPymntsValsTtlVal",myCptrdPymntsValsTtlVal);
        formData.append("accbPymntsDfltTrnsDte",accbPymntsDfltTrnsDte);
        formData.append("accbPymntsInvcCur",accbPymntsInvcCur);
        formData.append("accbPymntsGvnAmnt",accbPymntsGvnAmnt);
        formData.append("accbPymntsChqName",accbPymntsChqName);
        formData.append("accbPymntsChqNumber",accbPymntsChqNumber);
        formData.append("accbPymntsSignCode",accbPymntsSignCode);
        formData.append("shdSbmt",shdSbmt);
        formData.append("slctdDetTransLines",slctdDetTransLines);
        dialog.init(function () {
            getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                $body = $("body");
                $body.removeClass("mdlloading");
                $.ajax({
                    url: "index.php",
                    method: "POST",
                    data: formData,
                    async: true,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        setTimeout(function () {
                            dialog.find(".bootbox-body").html(data.message);
                            if (
                                data.message.indexOf("Success") !== -1 ||
                                data.message.indexOf("SUCCESS") !== -1
                            ) {
                                sbmtdAccbPymntsID = data.sbmtdAccbPymntsID;
                                getOneAccbPymntsForm(sbmtdAccbPymntsID,1,"ReloadDialog");
                            }
                        },500);
                    },
                    error: function (jqXHR,textStatus,errorThrown) {
                        console.log(textStatus + " " + errorThrown);
                        console.warn(jqXHR.responseText);
                    },
                });
            });
        });
    }
}

function saveAccbPymntsRvrslForm(funccrnm,shdSbmt,rptID,alrtID,paramsStr) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslAccbPymntsBtn");
    }
    if (typeof funccrnm === "undefined" || funccrnm === null) {
        funccrnm = "GHS";
    }
    if (typeof shdSbmt === "undefined" || shdSbmt === null) {
        shdSbmt = 0;
    }
    if (typeof rptID === "undefined" || rptID === null) {
        rptID = -1;
    }
    if (typeof alrtID === "undefined" || alrtID === null) {
        alrtID = -1;
    }
    if (typeof paramsStr === "undefined" || paramsStr === null) {
        paramsStr = "";
    }
    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdAccbPymntsID =
        typeof $("#sbmtdAccbPymntsID").val() === "undefined"
            ? -1
            : $("#sbmtdAccbPymntsID").val();
    var accbPymntsDesc =
        typeof $("#accbPymntsDesc").val() === "undefined"
            ? ""
            : $("#accbPymntsDesc").val();
    var accbPymntsDesc1 =
        typeof $("#accbPymntsDesc1").val() === "undefined"
            ? ""
            : $("#accbPymntsDesc1").val();
    var errMsg = "";
    if (sbmtdAccbPymntsID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (accbPymntsDesc === "" || accbPymntsDesc === accbPymntsDesc1) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#accbPymntsDesc").addClass("rho-error");
        $("#accbPymntsDesc").attr("readonly",false);
        $("#fnlzeRvrslAccbPymntsBtn").attr("disabled",false);
    } else {
        $("#accbPymntsDesc").removeClass("rho-error");
    }
    if (rhotrim(errMsg,"; ") !== "") {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var msgsTitle = "Payments Batch";
    var msgBody =
        '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' +
        msgsTitle +
        "?<br/>This action cannot be UNDONE!</p>";

    var dialog = bootbox.confirm({
        title: "Void " + msgsTitle + "?",
        size: "small",
        message: msgBody,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var msg = "Payments Batch";
                var dialog = bootbox.alert({
                    title: "Save " + msg,
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
                        msg +
                        "...Please Wait...</p>",
                    callback: function () {
                        sbmtdAccbPymntsID =
                            typeof $("#sbmtdAccbPymntsID").val() === "undefined"
                                ? -1
                                : $("#sbmtdAccbPymntsID").val();
                        if (sbmtdAccbPymntsID > 0) {
                            getOneAccbPymntsForm(sbmtdAccbPymntsID,1,"ReloadDialog");
                        }
                    },
                });
                dialog.init(function () {
                    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 6,
                                typ: 1,
                                pg: 12,
                                actyp: 1,
                                q: "VOID",
                                accbPymntsDesc: accbPymntsDesc,
                                sbmtdAccbPymntsID: sbmtdAccbPymntsID,
                                shdSbmt: shdSbmt,
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (
                                    /^[\],:{}\s]*$/.test(
                                        data
                                            .replace(/\\["\\\/bfnrtu]/g,"@")
                                            .replace(
                                                /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                                                "]"
                                            )
                                            .replace(/(?:^|:|,)(?:\s*\[)+/g,"")
                                    )
                                ) {
                                    obj = $.parseJSON(data);
                                    sbmtdAccbPymntsID = obj.sbmtdAccbPymntsID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdAccbPymntsID > 0) {
                                        $("#sbmtdAccbPymntsID").val(sbmtdAccbPymntsID);
                                    }
                                    if (msg.trim() === "") {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                if (msg.indexOf("Success") !== -1) {
                                    getSilentRptsRnSts(rptID,alrtID,paramsStr,function () {
                                        dialog.modal("hide");
                                        getOneAccbPymntsForm(sbmtdAccbPymntsID,1,"ReloadDialog");
                                    });
                                } else {
                                    setTimeout(function () {
                                        dialog.find(".bootbox-body").html(msg);
                                    },500);
                                }
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            },
                        });
                    });
                });
            }
        },
    });
}

function getAllCstmrs(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#allCstmrsSrchFor").val() === "undefined"
            ? "%"
            : $("#allCstmrsSrchFor").val();
    var srchIn =
        typeof $("#allCstmrsSrchIn").val() === "undefined"
            ? "Both"
            : $("#allCstmrsSrchIn").val();
    var pageNo =
        typeof $("#allCstmrsPageNo").val() === "undefined"
            ? 1
            : $("#allCstmrsPageNo").val();
    var limitSze =
        typeof $("#allCstmrsDsplySze").val() === "undefined"
            ? 10
            : $("#allCstmrsDsplySze").val();
    var sortBy =
        typeof $("#allCstmrsSortBy").val() === "undefined"
            ? ""
            : $("#allCstmrsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAllCstmrs(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllCstmrs(actionText,slctr,linkArgs);
    }
}

function getOneCstmrsForm(pKeyID,actionTxt) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=13&vtyp=1&srcMenu=VMS&sbmtdCstmrSpplrID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalLg",
        actionTxt,
        "Customer/Supplier (ID:" + pKeyID + ")",
        "myFormsModalTitleLg",
        "myFormsModalBodyLg",
        function () {
            $("#vaultCstmrStpForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $(function () {
                $('[data-toggle="tabajxvmscstmrs"]').off("click");
                $('[data-toggle="tabajxvmscstmrs"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr("href");
                    $(targ + "tab").tab("show");
                    if (targ.indexOf("vmsCstmrsGnrl") >= 0) {
                        $("#vmsCstmrsGnrl").removeClass("hideNotice");
                        $("#vmsCstmrsSites").addClass("hideNotice");
                        $("#vmsCstmrsSrvcs").addClass("hideNotice");
                    } else if (targ.indexOf("vmsCstmrsSites") >= 0) {
                        $("#vmsCstmrsGnrl").addClass("hideNotice");
                        $("#vmsCstmrsSites").removeClass("hideNotice");
                        $("#vmsCstmrsSrvcs").addClass("hideNotice");
                    } else if (targ.indexOf("vmsCstmrsSrvcs") >= 0) {
                        $("#vmsCstmrsGnrl").addClass("hideNotice");
                        $("#vmsCstmrsSites").addClass("hideNotice");
                        $("#vmsCstmrsSrvcs").removeClass("hideNotice");
                    }
                });
            });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLg").css("overflow","auto");
                });
            $("#allOtherInputData99").val(0);
            $("#myFormsModalLg").off("hidden.bs.modal");
            $("#myFormsModalLg").one("hidden.bs.modal",function (e) {
                $("#myFormsModalTitleLg").html("");
                $("#myFormsModalBodyLg").html("");
                getAllCstmrs("clear","#allmodules","grp=6&typ=1&pg=13&vtyp=0");
                $(e.currentTarget).unbind();
            });
        }
    );
}

function delCstmrs(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var cstmrNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_CstmrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_CstmrID").val();
        var $tds = $("#" + rowIDAttrb).find("td");
        cstmrNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: "Delete Customer/Supplier?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Customer/Supplier?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Customer/Supplier?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Customer/Supplier...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 25,
                                    typ: 1,
                                    pg: 4,
                                    q: "DELETE",
                                    actyp: 6,
                                    pKeyID: pKeyID,
                                    cstmrNm: cstmrNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function getAcbExchRates(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#acbExchRatesSrchFor").val() === "undefined"
            ? "%"
            : $("#acbExchRatesSrchFor").val();
    var srchIn =
        typeof $("#acbExchRatesSrchIn").val() === "undefined"
            ? "Both"
            : $("#acbExchRatesSrchIn").val();
    var pageNo =
        typeof $("#acbExchRatesPageNo").val() === "undefined"
            ? 1
            : $("#acbExchRatesPageNo").val();
    var limitSze =
        typeof $("#acbExchRatesDsplySze").val() === "undefined"
            ? 10
            : $("#acbExchRatesDsplySze").val();
    var sortBy =
        typeof $("#acbExchRatesSortBy").val() === "undefined"
            ? ""
            : $("#acbExchRatesSortBy").val();
    var qStrtDte =
        typeof $("#acbExchRatesStrtDate").val() === "undefined"
            ? ""
            : $("#acbExchRatesStrtDate").val();
    var qEndDte =
        typeof $("#acbExchRatesEndDate").val() === "undefined"
            ? ""
            : $("#acbExchRatesEndDate").val();
    var qNwStrtDte =
        typeof $("#acbExchRatesNewDate").val() === "undefined"
            ? ""
            : $("#acbExchRatesNewDate").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qStrtDte=" +
        qStrtDte +
        "&qEndDte=" +
        qEndDte +
        "&qNwStrtDte=" +
        qNwStrtDte;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAcbExchRates(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcbExchRates(actionText,slctr,linkArgs);
    }
}

function acbCnfExRateKeyPress(event,rowIDAttrb) {
    if (event.which === 13) {
        var nextItem;
        var nextItemVal = 0;
        var rowPrfxNm = rowIDAttrb.split("_")[0];
        var sbmtdTblRowID = "acbExchRatesTable";
        var curItemVal = getRowIndx(rowIDAttrb,sbmtdTblRowID);
        if (curItemVal === getTtlRows(sbmtdTblRowID)) {
            nextItem = $("#" + sbmtdTblRowID + " .mcfExRate").eq(0);
        } else {
            nextItemVal = Number(curItemVal);
            nextItem = $("#" + sbmtdTblRowID + " .mcfExRate").eq(nextItemVal);
        }
        nextItem.focus();
    }
}

function saveAcbExchRates(actionText,slctr,linkArgs,actyp) {
    var errMsg = "";
    var newRateDate =
        typeof $("#acbExchRatesNewDate").val() === "undefined"
            ? ""
            : $("#acbExchRatesNewDate").val();
    var slctdRateIDs = "";
    var isVld = true;
    $("#acbExchRatesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowPrfxNm = $(el).attr("id").split("_")[0];
                    if (
                        typeof $("#" + rowPrfxNm + rndmNum + "_RateID").val() ===
                        "undefined"
                    ) {
                        /*Do Nothing*/
                    } else {
                        var lnRateID =
                            typeof $("#" + rowPrfxNm + rndmNum + "_RateID").val() ===
                                "undefined"
                                ? "-1"
                                : $("#" + rowPrfxNm + rndmNum + "_RateID").val();
                        var lnRateVal =
                            typeof $("#" + rowPrfxNm + rndmNum + "_ExRate").val() ===
                                "undefined"
                                ? "-1"
                                : $("#" + rowPrfxNm + rndmNum + "_ExRate").val();
                        /*var lnRateVal1 = typeof $('#' + rowPrfxNm + rndmNum + '_ExRate1').val() === 'undefined' ? '-1' : $('#' + rowPrfxNm + rndmNum + '_ExRate1').val();*/
                        if (Number(lnRateID.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            /*Do Nothing*/
                        } else {
                            if (Number(lnRateVal.replace(/[^-?0-9\.]/g,"")) <= 0) {
                                isVld = false;
                                $("#" + rowPrfxNm + rndmNum + "_ExRate").addClass("rho-error");
                            } else {
                                $("#" + rowPrfxNm + rndmNum + "_ExRate").removeClass(
                                    "rho-error"
                                );
                                if (isVld === true) {
                                    slctdRateIDs =
                                        slctdRateIDs +
                                        lnRateID +
                                        "~" +
                                        lnRateVal
                                            .replace(/(~)/g,"{-;-;}")
                                            .replace(/(\|)/g,"{:;:;}") +
                                        "|";
                                }
                            }
                        }
                    }
                }
            }
        });
    if (isVld === false) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Exchange Rates",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Exchange Rates...Please Wait...</p>',
        callback: function () {
            $("#myFormsModalNm").modal("hide");
            getAcbExchRates(actionText,slctr,linkArgs);
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 16,
                    q: "UPDATE",
                    actyp: actyp,
                    newRateDate: newRateDate,
                    slctdRateIDs: slctdRateIDs,
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(result.message);
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function delAcbExchRate(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var rateIDDesc = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_RateID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_RateID").val();
        rateIDDesc =
            $("#" + rowPrfxNm + rndmNum + "_FromCur").val() +
            " (" +
            $("#" + rowPrfxNm + rndmNum + "_RateDate").val() +
            ")";
    }
    var dialog = bootbox.confirm({
        title: "Delete Exchange Rate?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Exchange Rate?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Exchange Rate?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Exchange Rate...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 16,
                                    q: "DELETE",
                                    actyp: 1,
                                    rateID: pKeyID,
                                    rateIDDesc: rateIDDesc,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delSlctdAcbExchRates() {
    var slctdRateIDs = "";
    var slctdCnt = 0;
    $("#acbExchRatesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var rowPrfxNm = $(el).attr("id").split("_")[0];
                    if (
                        typeof $("#" + rowPrfxNm + rndmNum + "_RateID").val() ===
                        "undefined"
                    ) {
                        /*Do Nothing*/
                    } else {
                        var lnRateID =
                            typeof $("#" + rowPrfxNm + rndmNum + "_RateID").val() ===
                                "undefined"
                                ? "-1"
                                : $("#" + rowPrfxNm + rndmNum + "_RateID").val();
                        var isTcked =
                            typeof $(
                                "input[name='" + rowPrfxNm + rndmNum + "_CheckBox']:checked"
                            ).val() === "undefined"
                                ? "NO"
                                : "YES";
                        if (
                            Number(lnRateID.replace(/[^-?0-9\.]/g,"")) > 0 &&
                            isTcked === "YES"
                        ) {
                            slctdCnt = slctdCnt + 1;
                        }
                        $("#" + rowPrfxNm + rndmNum).remove();
                    }
                }
            }
        });
    if (slctdCnt > 0) {
        var dialog = bootbox.confirm({
            title: "Delete Selected Exchange Rates?",
            size: "small",
            message:
                '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> the ' +
                slctdCnt +
                " selected Exchange Rate(s)?<br/>Action cannot be Undone!</p>",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes',
                    className: "btn-success",
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> No',
                    className: "btn-danger",
                },
            },
            callback: function (result) {
                $("#acbExchRatesTable")
                    .find("tr")
                    .each(function (i,el) {
                        if (i > 0) {
                            if (typeof $(el).attr("id") === "undefined") {
                                /*Do Nothing*/
                            } else {
                                var rndmNum = $(el).attr("id").split("_")[1];
                                var rowPrfxNm = $(el).attr("id").split("_")[0];
                                if (
                                    typeof $("#" + rowPrfxNm + rndmNum + "_RateID").val() ===
                                    "undefined"
                                ) {
                                    /*Do Nothing*/
                                } else {
                                    var lnRateID =
                                        typeof $("#" + rowPrfxNm + rndmNum + "_RateID").val() ===
                                            "undefined"
                                            ? "-1"
                                            : $("#" + rowPrfxNm + rndmNum + "_RateID").val();
                                    var rateIDDesc =
                                        $("#" + rowPrfxNm + rndmNum + "_FromCur").val() +
                                        " (" +
                                        $("#" + rowPrfxNm + rndmNum + "_RateDate").val() +
                                        ")";
                                    var isTcked =
                                        typeof $(
                                            "input[name='" +
                                            rowPrfxNm +
                                            rndmNum +
                                            "_CheckBox']:checked"
                                        ).val() === "undefined"
                                            ? "NO"
                                            : "YES";
                                    if (
                                        Number(lnRateID.replace(/[^-?0-9\.]/g,"")) > 0 &&
                                        isTcked === "YES"
                                    ) {
                                        slctdRateIDs =
                                            slctdRateIDs +
                                            lnRateID +
                                            "~" +
                                            rateIDDesc
                                                .replace(/(~)/g,"{-;-;}")
                                                .replace(/(\|)/g,"{:;:;}") +
                                            "|";
                                    }
                                    $("#" + rowPrfxNm + rndmNum).remove();
                                }
                            }
                        }
                    });
                var result2 = "";
                if (result === true) {
                    var dialog1 = bootbox.alert({
                        title: "Delete Selected Exchange Rates?",
                        size: "small",
                        message:
                            '<p><i class="fa fa-spin fa-spinner"></i> Deleting Selected Exchange Rates...Please Wait...</p>',
                        callback: function () {
                            $("body").css("padding-right","0px");
                            if (result2.indexOf("Success") !== -1) {
                                getAcbExchRates("","#allmodules","grp=6&typ=1&pg=16&vtyp=0");
                            }
                        },
                    });
                    dialog1.init(function () {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 16,
                                    q: "DELETE",
                                    actyp: 2,
                                    slctdRateIDs: slctdRateIDs,
                                },
                                success: function (result1) {
                                    result2 = result1;
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    });
                }
            },
        });
    }
}

function actOnPeriodStatus(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var currentStats = "";
    var nwAction = "";
    if (
        typeof $("#" + rowPrfxNm + rndmNum + "_PeriodDetID").val() === "undefined"
    ) {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_PeriodDetID").val();
        var $tds = $("#" + rowIDAttrb).find("td");
        currentStats = $.trim($tds.eq(4).text());
    }
    if (currentStats === "Never Opened") {
        nwAction = "OPEN";
    } else if (currentStats === "Open") {
        nwAction = "DEACTIVATE/CLOSE";
    } else if (currentStats === "Closed") {
        nwAction = "RE-OPEN";
    } else {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: "Invalid Current Status!",
        });
        return false;
    }
    var dialog = bootbox.confirm({
        title: "Authorize Request?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">' +
            nwAction +
            "</span> this Period?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                if (result === true) {
                    var dialog1 = bootbox.alert({
                        title: "" + nwAction + " PERIOD?",
                        size: "small",
                        message:
                            '<p><i class="fa fa-spin fa-spinner"></i> Acting on Period...Please Wait...</p>',
                        callback: function () {
                            getAccbPeriods("","#allmodules","grp=6&typ=1&pg=8&vtyp=0");
                        },
                    });
                    dialog1.init(function () {
                        if (pKeyID > 0) {
                            getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                                $body = $("body");
                                $body.removeClass("mdlloading");
                                $.ajax({
                                    method: "POST",
                                    url: "index.php",
                                    data: {
                                        grp: 6,
                                        typ: 1,
                                        pg: 8,
                                        q: "FINALIZE",
                                        actyp: 1,
                                        prdDetID: pKeyID,
                                        currentStats: currentStats,
                                    },
                                    success: function (result1) {
                                        setTimeout(function () {
                                            dialog1.find(".bootbox-body").html(result1);
                                        },500);
                                    },
                                    error: function (jqXHR1,textStatus1,errorThrown1) {
                                        dialog1.find(".bootbox-body").html(errorThrown1);
                                    },
                                });
                            });
                        } else {
                            setTimeout(function () {
                                dialog1
                                    .find(".bootbox-body")
                                    .html('<span style="color:red;">Nothing to Act On!</span>');
                            },500);
                        }
                    });
                }
            }
        },
    });
}

function delAcbAcntngPeriod(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (
        typeof $("#" + rowPrfxNm + rndmNum + "_PeriodDetID").val() === "undefined"
    ) {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_PeriodDetID").val();
        pKeyNm =
            $("#" + rowPrfxNm + rndmNum + "_PeriodNm").val() +
            " (" +
            $("#" + rowPrfxNm + rndmNum + "_PeriodNm").val() +
            ")";
    }
    var dialog = bootbox.confirm({
        title: "Delete Period?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Period?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Period?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Period...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 8,
                                    q: "DELETE",
                                    actyp: 5,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function getAllAccbPayMthds(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#allAccbPayMthdsSrchFor").val() === "undefined"
            ? "%"
            : $("#allAccbPayMthdsSrchFor").val();
    var srchIn =
        typeof $("#allAccbPayMthdsSrchIn").val() === "undefined"
            ? "Both"
            : $("#allAccbPayMthdsSrchIn").val();
    var pageNo =
        typeof $("#allAccbPayMthdsPageNo").val() === "undefined"
            ? 1
            : $("#allAccbPayMthdsPageNo").val();
    var limitSze =
        typeof $("#allAccbPayMthdsDsplySze").val() === "undefined"
            ? 10
            : $("#allAccbPayMthdsDsplySze").val();
    var sortBy =
        typeof $("#allAccbPayMthdsSortBy").val() === "undefined"
            ? ""
            : $("#allAccbPayMthdsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAllAccbPayMthds(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllAccbPayMthds(actionText,slctr,linkArgs);
    }
}

function delAccbPayMthds(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var pKeyID = -1;
    var methodNm = "";
    var msgsTitle = "Payment Methods";
    if (
        typeof $("#accbPayMthdsRow" + rndmNum + "_LimitID").val() === "undefined"
    ) {
        /*Do Nothing*/
    } else {
        pKeyID = $("#accbPayMthdsRow" + rndmNum + "_LimitID").val();
        athrzrNm = $("#accbPayMthdsRow" + rndmNum + "_AthrzrNm").val();
    }
    var dialog = bootbox.confirm({
        title: "Remove " + msgsTitle + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">REMOVE</span> this ' +
            msgsTitle +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Remove " + msgsTitle + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Removing ' +
                        msgsTitle +
                        "...Please Wait...</p>",
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 18,
                                    q: "DELETE",
                                    actyp: 2,
                                    methodNm: methodNm,
                                    pKeyID: pKeyID,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function saveAccbPayMthds(slctr,linkArgs) {
    var msgsTitle = "Payment Methods";
    var slctdAccbPayMthds = "";
    var isVld = true;
    var errMsg = "";
    $("#accbPayMthdsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_PayMthdNm =
                        typeof $("#accbPayMthdsRow" + rndmNum + "_PayMthdNm").val() ===
                            "undefined"
                            ? ""
                            : $("#accbPayMthdsRow" + rndmNum + "_PayMthdNm").val();
                    var ln_AccountID =
                        typeof $("#accbPayMthdsRow" + rndmNum + "_AccountID").val() ===
                            "undefined"
                            ? "-1"
                            : $("#accbPayMthdsRow" + rndmNum + "_AccountID").val();
                    var ln_SprtDocTyp =
                        typeof $("#accbPayMthdsRow" + rndmNum + "_SprtDocTyp").val() ===
                            "undefined"
                            ? ""
                            : $("#accbPayMthdsRow" + rndmNum + "_SprtDocTyp").val();
                    var isEnabled =
                        typeof $(
                            "input[name='accbPayMthdsRow" + rndmNum + "_IsEnbld']:checked"
                        ).val() === "undefined"
                            ? "NO"
                            : "YES";
                    if (ln_PayMthdNm.trim() !== "") {
                        if (Number(ln_AccountID.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Charge Account for Column No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbPayMthdsRow" + rndmNum + "_AccountNm").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbPayMthdsRow" + rndmNum + "_AccountNm").removeClass(
                                "rho-error"
                            );
                        }
                        if (ln_SprtDocTyp.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Supported Document Type for Column No. ' +
                                i +
                                " cannot be Empty!</span></p>";
                            $("#accbPayMthdsRow" + rndmNum + "_SprtDocTyp").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbPayMthdsRow" + rndmNum + "_SprtDocTyp").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === false) {
                            /*Do Nothing*/
                        } else {
                            slctdAccbPayMthds =
                                slctdAccbPayMthds +
                                $("#accbPayMthdsRow" + rndmNum + "_PayMthdID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_PayMthdNm
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#accbPayMthdsRow" + rndmNum + "_PayMthdDesc")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_AccountID
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_SprtDocTyp
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                $("#accbPayMthdsRow" + rndmNum + "_BckGrndPrcs")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                isEnabled.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save " + msgsTitle,
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
            msgsTitle +
            "...Please Wait...</p>",
        callback: function () {
            openATab(slctr,linkArgs);
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 18,
                    q: "UPDATE",
                    actyp: 1,
                    slctdAccbPayMthds: slctdAccbPayMthds,
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(result.message);
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function saveAccbDfltAcnts(slctr,linkArgs) {
    var msgsTitle = "Default Accounts";
    var slctdDfltAccnts = "";
    var isVld = true;
    var errMsg = "";
    $("#allDfltAcntsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_Ctgry =
                        typeof $("#allDfltAcntsRow" + rndmNum + "_Ctgry").val() ===
                            "undefined"
                            ? ""
                            : $("#allDfltAcntsRow" + rndmNum + "_Ctgry").val();
                    var ln_AccountID =
                        typeof $("#allDfltAcntsRow" + rndmNum + "_GLAcntID").val() ===
                            "undefined"
                            ? "-1"
                            : $("#allDfltAcntsRow" + rndmNum + "_GLAcntID").val();
                    var ln_SysName =
                        typeof $("#allDfltAcntsRow" + rndmNum + "_SysName").val() ===
                            "undefined"
                            ? ""
                            : $("#allDfltAcntsRow" + rndmNum + "_SysName").val();
                    if (ln_Ctgry.trim() !== "" && ln_SysName.trim() !== "") {
                        /*if (Number(ln_AccountID.replace(/[^-?0-9\.]/g, '')) <= 0)
                                                         {
                                                         isVld = false;
                                                         errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                                         'font-weight:bold;color:red;">Default GL Account for Column No. ' + i + ' cannot be empty!</span></p>';
                                                         $('#allDfltAcntsRow' + rndmNum + '_GLAcntNm').addClass('rho-error');
                                                         } else {
                                                         $('#allDfltAcntsRow' + rndmNum + '_GLAcntNm').removeClass('rho-error');
                                                         }*/
                        if (isVld === false) {
                            /*Do Nothing*/
                        } else {
                            slctdDfltAccnts =
                                slctdDfltAccnts +
                                ln_Ctgry.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_SysName
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_AccountID
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save " + msgsTitle,
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
            msgsTitle +
            "...Please Wait...</p>",
        callback: function () {
            openATab(slctr,linkArgs);
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 15,
                    q: "UPDATE",
                    actyp: 1,
                    slctdDfltAccnts: slctdDfltAccnts,
                },
                success: function (result) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(result.message);
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    /*dialog.find('.bootbox-body').html(errorThrown);*/
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAccbDocTmplts(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbDocTmpltsSrchFor").val() === "undefined"
            ? "%"
            : $("#accbDocTmpltsSrchFor").val();
    var srchIn =
        typeof $("#accbDocTmpltsSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbDocTmpltsSrchIn").val();
    var pageNo =
        typeof $("#accbDocTmpltsPageNo").val() === "undefined"
            ? 1
            : $("#accbDocTmpltsPageNo").val();
    var limitSze =
        typeof $("#accbDocTmpltsDsplySze").val() === "undefined"
            ? 10
            : $("#accbDocTmpltsDsplySze").val();
    var sortBy =
        typeof $("#accbDocTmpltsSortBy").val() === "undefined"
            ? ""
            : $("#accbDocTmpltsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbDocTmplts(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbDocTmplts(actionText,slctr,linkArgs);
    }
}

function getOneAccbDocTmpltForm(tmpltID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=17&vtyp=" + vwtype + "&sbmtdDcTmpltID=" + tmpltID;
    doAjaxWthCallBck(
        lnkArgs,
        "accbDocTmpltDetailInfo",
        "PasteDirect",
        "",
        "",
        "",
        function () {
            if (!$.fn.DataTable.isDataTable("#accbDocTmpltAdtTblsTable")) {
                var table2 = $("#accbDocTmpltAdtTblsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbDocTmpltAdtTblsTable").wrap('<div class="dataTables_scroll"/>');
            }
        }
    );
}

function getAccbDocTmpltLovsPage(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm1,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc,
    psblValIDElmntID
) {
    if (typeof callBackFunc === "undefined" || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (lovNm1 === "") {
        lovNm1 = "RhoUndefined";
    }
    var lovNm1Val =
        typeof $("#" + lovNm1).val() === "undefined" ? "" : $("#" + lovNm1).val();
    var lovNm = "";
    if (lovNm1Val === "2Tax") {
        lovNm = "Tax Codes";
    } else if (lovNm1Val === "3Discount") {
        lovNm = "Discount Codes";
    } else if (lovNm1Val === "4Extra Charge") {
        lovNm = "Extra Charges";
    } else {
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc,
        psblValIDElmntID
    );
}

function saveAccbDocTmpltForm() {
    var accbDocTmpltsID =
        typeof $("#accbDocTmpltsID").val() === "undefined"
            ? -1
            : $("#accbDocTmpltsID").val();
    var accbDocTmpltsName =
        typeof $("#accbDocTmpltsName").val() === "undefined"
            ? ""
            : $("#accbDocTmpltsName").val();
    var accbDocTmpltsDesc =
        typeof $("#accbDocTmpltsDesc").val() === "undefined"
            ? ""
            : $("#accbDocTmpltsDesc").val();
    var accbDocTmpltsType =
        typeof $("#accbDocTmpltsType").val() === "undefined"
            ? ""
            : $("#accbDocTmpltsType").val();
    var accbDocTmpltsIsEnbld =
        typeof $("input[name='accbDocTmpltsIsEnbld']:checked").val() === "undefined"
            ? "NO"
            : $("input[name='accbDocTmpltsIsEnbld']:checked").val();
    var errMsg = "";
    if (accbDocTmpltsName.trim() === "" || accbDocTmpltsType.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Template Name and Type cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdTransLines = "";
    $("#accbDocTmpltAdtTblsTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_ItemType = $(
                        "#accbDocTmpltAdtTblsRow" + rndmNum + "_ItemType"
                    ).val();
                    var ln_CodeBhndID = $(
                        "#accbDocTmpltAdtTblsRow" + rndmNum + "_CodeBhndID"
                    ).val();
                    var ln_LineDesc = $(
                        "#accbDocTmpltAdtTblsRow" + rndmNum + "_LineDesc"
                    ).val();
                    var ln_IncrsDcrs = $(
                        "#accbDocTmpltAdtTblsRow" + rndmNum + "_IncrsDcrs"
                    ).val();
                    var ln_AccountID = $(
                        "#accbDocTmpltAdtTblsRow" + rndmNum + "_AccountID"
                    ).val();
                    var ln_AutoCalc =
                        typeof $(
                            "input[name='accbDocTmpltAdtTblsRow" +
                            rndmNum +
                            "_AutoCalc']:checked"
                        ).val() === "undefined"
                            ? "NO"
                            : "YES";

                    if (ln_LineDesc.trim() !== "") {
                        if (
                            Number(ln_CodeBhndID.replace(/[^-?0-9\.]/g,"")) <= 0 &&
                            (ln_ItemType === "2Tax" ||
                                ln_ItemType === "3Discount" ||
                                ln_ItemType === "4Extra Charge")
                        ) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Tax/Charge/Discount Code for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbDocTmpltAdtTblsRow" + rndmNum + "_LineDesc").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbDocTmpltAdtTblsRow" + rndmNum + "_LineDesc").removeClass(
                                "rho-error"
                            );
                        }
                        if (Number(ln_AccountID.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Account for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbDocTmpltAdtTblsRow" + rndmNum + "_AccountNm").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbDocTmpltAdtTblsRow" + rndmNum + "_AccountNm").removeClass(
                                "rho-error"
                            );
                        }
                        if (ln_IncrsDcrs.trim() === "") {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Increase/Decrease for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbDocTmpltAdtTblsRow" + rndmNum + "_IncrsDcrs").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbDocTmpltAdtTblsRow" + rndmNum + "_IncrsDcrs").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === true) {
                            slctdTransLines =
                                slctdTransLines +
                                $("#accbDocTmpltAdtTblsRow" + rndmNum + "_DetID")
                                    .val()
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_ItemType
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_CodeBhndID
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_LineDesc
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_IncrsDcrs
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_AccountID
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "~" +
                                ln_AutoCalc
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                "|";
                        }
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Templates",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Templates...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",17);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("accbDocTmpltsID",accbDocTmpltsID);
    formData.append("accbDocTmpltsName",accbDocTmpltsName);
    formData.append("accbDocTmpltsDesc",accbDocTmpltsDesc);
    formData.append("accbDocTmpltsType",accbDocTmpltsType);
    formData.append("accbDocTmpltsIsEnbld",accbDocTmpltsIsEnbld);
    formData.append("slctdTransLines",slctdTransLines);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            accbDocTmpltsID = data.accbDocTmpltsID;
                            getAccbDocTmplts("","#allmodules","grp=6&typ=1&pg=17&vtyp=0");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function delAccbDocTmplts(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_HdrNm").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Template?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Template?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Template?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Template...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 17,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbDocTmpltTrans(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_TrnsLnID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_LineDesc").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Template Transaction?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Template Transaction?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Template?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Template Transaction...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 17,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function insertNewAccbDocTmpltRows(tableElmntID,position,inptHtml,lineType) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
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
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function getAccbTxCde(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#accbTxCdeSrchFor").val() === "undefined"
            ? "%"
            : $("#accbTxCdeSrchFor").val();
    var srchIn =
        typeof $("#accbTxCdeSrchIn").val() === "undefined"
            ? "Both"
            : $("#accbTxCdeSrchIn").val();
    var pageNo =
        typeof $("#accbTxCdePageNo").val() === "undefined"
            ? 1
            : $("#accbTxCdePageNo").val();
    var limitSze =
        typeof $("#accbTxCdeDsplySze").val() === "undefined"
            ? 10
            : $("#accbTxCdeDsplySze").val();
    var sortBy =
        typeof $("#accbTxCdeSortBy").val() === "undefined"
            ? ""
            : $("#accbTxCdeSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    openATab(slctr,linkArgs);
}

function enterKeyFuncAccbTxCde(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAccbTxCde(actionText,slctr,linkArgs);
    }
}

function getOneAccbTaxCodeForm(tmpltID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=14&vtyp=" + vwtype + "&sbmtdTaxCodeID=" + tmpltID;
    doAjaxWthCallBck(
        lnkArgs,
        "accbTaxCodeDetailInfo",
        "PasteDirect",
        "",
        "",
        "",
        function () {
            if (!$.fn.DataTable.isDataTable("#accbTaxCodeAdtTblsTable")) {
                var table2 = $("#accbTaxCodeAdtTblsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#accbTaxCodeAdtTblsTable").wrap('<div class="dataTables_scroll"/>');
            }
        }
    );
}

function saveAccbTaxCodeForm() {
    var accbTxCdeID =
        typeof $("#accbTxCdeID").val() === "undefined"
            ? "-1"
            : $("#accbTxCdeID").val();
    var accbTxCdeName =
        typeof $("#accbTxCdeName").val() === "undefined"
            ? ""
            : $("#accbTxCdeName").val();
    var accbTxCdeDesc =
        typeof $("#accbTxCdeDesc").val() === "undefined"
            ? ""
            : $("#accbTxCdeDesc").val();
    var accbTxCdeType =
        typeof $("#accbTxCdeType").val() === "undefined"
            ? ""
            : $("#accbTxCdeType").val();
    var accbTxCdeSQL =
        typeof $("#accbTxCdeSQL").val() === "undefined"
            ? ""
            : $("#accbTxCdeSQL").val();
    var accbTxCdeUnitPrc =
        typeof $("#accbTxCdeUnitPrc").val() === "undefined"
            ? "0"
            : $("#accbTxCdeUnitPrc").val();
    var accbTxCdeQty =
        typeof $("#accbTxCdeQty").val() === "undefined"
            ? "0"
            : $("#accbTxCdeQty").val();
    accbTxCdeUnitPrc = fmtAsNumber("accbTxCdeUnitPrc");
    accbTxCdeQty = fmtAsNumber("accbTxCdeQty");

    var accbTxCdePyblAcntID =
        typeof $("#accbTxCdePyblAcntID").val() === "undefined"
            ? "-1"
            : $("#accbTxCdePyblAcntID").val();
    var accbTxCdeExpnsAcntID =
        typeof $("#accbTxCdeExpnsAcntID").val() === "undefined"
            ? "-1"
            : $("#accbTxCdeExpnsAcntID").val();
    var accbTxCdeRvnuAcntID =
        typeof $("#accbTxCdeRvnuAcntID").val() === "undefined"
            ? "-1"
            : $("#accbTxCdeRvnuAcntID").val();
    var accbTxCdeTxExpAccID =
        typeof $("#accbTxCdeTxExpAccID").val() === "undefined"
            ? "-1"
            : $("#accbTxCdeTxExpAccID").val();
    var accbTxCdePrchDscAccID =
        typeof $("#accbTxCdePrchDscAccID").val() === "undefined"
            ? "-1"
            : $("#accbTxCdePrchDscAccID").val();
    var accbTxCdeChrgExpAccID =
        typeof $("#accbTxCdeChrgExpAccID").val() === "undefined"
            ? "-1"
            : $("#accbTxCdeChrgExpAccID").val();

    var accbTxCdeIsEnbld =
        typeof $("input[name='accbTxCdeIsEnbld']:checked").val() === "undefined"
            ? "NO"
            : $("input[name='accbTxCdeIsEnbld']:checked").val();
    var accbTxCdeIsParnt =
        typeof $("input[name='accbTxCdeIsParnt']:checked").val() === "undefined"
            ? "NO"
            : $("input[name='accbTxCdeIsParnt']:checked").val();
    var accbTxCdeIsWthHldng =
        typeof $("input[name='accbTxCdeIsWthHldng']:checked").val() === "undefined"
            ? "NO"
            : $("input[name='accbTxCdeIsWthHldng']:checked").val();
    var accbTxCdeIsTxRcvrbl =
        typeof $("input[name='accbTxCdeIsTxRcvrbl']:checked").val() === "undefined"
            ? "NO"
            : $("input[name='accbTxCdeIsTxRcvrbl']:checked").val();
    var errMsg = "";
    if (accbTxCdeName.trim() === "" || accbTxCdeType.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Code Name and Type cannot be empty!</span></p>';
    }
    if (accbTxCdeSQL.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">SQL Formula cannot be empty!</span></p>';
    }
    if (accbTxCdeIsParnt === "NO") {
        if (
            accbTxCdeType === "Tax" &&
            Number(accbTxCdePyblAcntID.replace(/[^-?0-9\.]/g,"")) <= 0
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Taxes Payable Account CANNOT be EMPTY if Item Type is Tax!</span></p>';
        }
        if (
            accbTxCdeType !== "Tax" &&
            Number(accbTxCdePyblAcntID.replace(/[^-?0-9\.]/g,"")) > 0
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Taxes Payable Account MUST be EMPTY if Item Type is not Tax!</span></p>';
            return;
        }
        if (
            accbTxCdeType === "Discount" &&
            Number(accbTxCdeExpnsAcntID.replace(/[^-?0-9\.]/g,"")) <= 0
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Contra Revenue Account CANNOT be EMPTY if Item Type is Discount!</span></p>';
        }
        if (
            accbTxCdeType !== "Discount" &&
            Number(accbTxCdeExpnsAcntID.replace(/[^-?0-9\.]/g,"")) > 0
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Contra Revenue Account MUST be EMPTY if Item Type is not Discount!</span></p>';
        }
        if (
            accbTxCdeType === "Extra Charge" &&
            Number(accbTxCdeRvnuAcntID.replace(/[^-?0-9\.]/g,"")) <= 0
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Revenue Account CANNOT be EMPTY if Item Type is Extra Charge!</span></p>';
        }
        if (
            accbTxCdeType !== "Extra Charge" &&
            Number(accbTxCdeRvnuAcntID.replace(/[^-?0-9\.]/g,"")) > 0
        ) {
            errMsg +=
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Revenue Account MUST be EMPTY if Item Type is not Extra Charge!</span></p>';
            return;
        }
    }
    var isVld = true;
    var slctdCodeIDs = "";
    $("#accbTaxCodeAdtTblsTable")
        .find("tr")
        .each(function (i,el) {
            isVld = true;
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_CodeBhndID = $(
                        "#accbTaxCodeAdtTblsRow" + rndmNum + "_CodeBhndID"
                    ).val();
                    var ln_LineDesc = $(
                        "#accbTaxCodeAdtTblsRow" + rndmNum + "_LineDesc"
                    ).val();

                    if (ln_LineDesc.trim() !== "") {
                        if (Number(ln_CodeBhndID.replace(/[^-?0-9\.]/g,"")) <= 0) {
                            isVld = false;
                            errMsg +=
                                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                'font-weight:bold;color:red;">Tax/Charge/Discount Code for Row No. ' +
                                i +
                                " cannot be empty!</span></p>";
                            $("#accbTaxCodeAdtTblsRow" + rndmNum + "_LineDesc").addClass(
                                "rho-error"
                            );
                        } else {
                            $("#accbTaxCodeAdtTblsRow" + rndmNum + "_LineDesc").removeClass(
                                "rho-error"
                            );
                        }
                        if (isVld === true) {
                            slctdCodeIDs =
                                slctdCodeIDs +
                                ln_CodeBhndID
                                    .replace(/(~)/g,"{-;-;}")
                                    .replace(/(\|)/g,"{:;:;}") +
                                ",";
                        }
                    }
                }
            }
        });

    if (rhotrim(slctdCodeIDs,", ") === "" && accbTxCdeIsParnt === "YES") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Child Items cannot be empty for a Parent Item!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Tax Codes",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Tax Codes...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",14);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("accbTxCdeID",accbTxCdeID);
    formData.append("accbTxCdeName",accbTxCdeName);
    formData.append("accbTxCdeDesc",accbTxCdeDesc);
    formData.append("accbTxCdeType",accbTxCdeType);
    formData.append("accbTxCdeSQL",accbTxCdeSQL);
    formData.append("accbTxCdeUnitPrc",accbTxCdeUnitPrc);
    formData.append("accbTxCdeQty",accbTxCdeQty);

    formData.append("accbTxCdePyblAcntID",accbTxCdePyblAcntID);
    formData.append("accbTxCdeExpnsAcntID",accbTxCdeExpnsAcntID);
    formData.append("accbTxCdeRvnuAcntID",accbTxCdeRvnuAcntID);
    formData.append("accbTxCdeTxExpAccID",accbTxCdeTxExpAccID);
    formData.append("accbTxCdePrchDscAccID",accbTxCdePrchDscAccID);
    formData.append("accbTxCdeChrgExpAccID",accbTxCdeChrgExpAccID);

    formData.append("accbTxCdeIsEnbld",accbTxCdeIsEnbld);
    formData.append("accbTxCdeIsParnt",accbTxCdeIsParnt);
    formData.append("accbTxCdeIsWthHldng",accbTxCdeIsWthHldng);
    formData.append("accbTxCdeIsTxRcvrbl",accbTxCdeIsTxRcvrbl);

    formData.append("slctdCodeIDs",slctdCodeIDs);

    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            accbTxCdeID = data.accbTxCdeID;
                            getAccbTxCde("","#allmodules","grp=6&typ=1&pg=14&vtyp=0");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function insertNewAccbTaxCodeRows(tableElmntID,position,inptHtml,lineType) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor(Math.random() * 9999999 + 1000000);
        var nwInptHtml = urldecode(
            inptHtml
                .replace(/(_WWW123WWW_)+/g,nwRndm + "_")
                .replace(/(_WWW123WWW)+/g,nwRndm)
        );
        if ($("#" + tableElmntID + " > tbody > tr").length >= 1) {
            if (
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .text() == "No data available in table"
            ) {
                $("#" + tableElmntID + " > tbody > tr > td")
                    .eq($("#" + tableElmntID + " > tbody > tr").length - 1)
                    .remove();
                $("#" + tableElmntID).append(nwInptHtml);
            } else {
                $("#" + tableElmntID).append(nwInptHtml);
            }
        } else {
            $("#" + tableElmntID).append(nwInptHtml);
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
    $(".form_date_tme").datetimepicker({
        format: "dd-M-yyyy hh:ii:ss",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 0,
        maxView: 4,
        forceParse: true,
    });
    $(".form_date").datetimepicker({
        format: "dd-M-yyyy",
        language: "en",
        weekStart: 0,
        todayBtn: true,
        autoclose: true,
        todayHighlight: true,
        keyboardNavigation: true,
        startView: 2,
        minView: 2,
        maxView: 4,
        forceParse: true,
    });
    var cntr = 0;
    $("#" + tableElmntID)
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
}

function delAccbTxCde(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_CodeID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_CodeID").val();
        pKeyNm = $("#" + rowPrfxNm + rndmNum + "_CodeNm").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Tax Code?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Tax Code?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Tax Code?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Tax Code...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 14,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delAccbTxCdeLne(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (
        typeof $("#" + rowPrfxNm + rndmNum + "_CodeBhndID").val() === "undefined"
    ) {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_CodeBhndID").val();
        pKeyNm = ""; /*$('#' + rowPrfxNm + rndmNum + '_CodeNm').val();*/
    }
    var dialog = bootbox.confirm({
        title: "Delete Child Code?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Child Code?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Child Code?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Child Code...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    setTimeout(function () {
                        $("#" + rowIDAttrb).remove();
                        dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                    },500);
                });
            }
        },
    });
}

function getAccbTxCdeLovsPage(
    elementID,
    titleElementID,
    modalBodyID,
    lovNm1,
    criteriaID,
    criteriaID2,
    criteriaID3,
    chkOrRadio,
    mustSelSth,
    selVals,
    valueElmntID,
    descElemntID,
    actionText,
    colNoForChkBxCmprsn,
    addtnlWhere,
    callBackFunc,
    psblValIDElmntID
) {
    if (typeof callBackFunc === "undefined" || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (lovNm1 === "") {
        lovNm1 = "RhoUndefined";
    }
    var lovNm1Val =
        typeof $("#" + lovNm1).val() === "undefined" ? "" : $("#" + lovNm1).val();
    var lovNm = "";
    if (lovNm1Val === "Tax") {
        lovNm = "Tax Codes";
    } else if (lovNm1Val === "Discount") {
        lovNm = "Discount Codes";
    } else if (lovNm1Val === "Extra Charge") {
        lovNm = "Extra Charges";
    } else {
        return false;
    }
    getLovsPage(
        elementID,
        titleElementID,
        modalBodyID,
        lovNm,
        criteriaID,
        criteriaID2,
        criteriaID3,
        chkOrRadio,
        mustSelSth,
        selVals,
        valueElmntID,
        descElemntID,
        actionText,
        colNoForChkBxCmprsn,
        addtnlWhere,
        callBackFunc,
        psblValIDElmntID
    );
}

function testTxCdeSQLQuery() {
    var accbTxCdeSQL =
        typeof $("#accbTxCdeSQL").val() === "undefined"
            ? ""
            : $("#accbTxCdeSQL").val();
    var accbTxCdeUnitPrc =
        typeof $("#accbTxCdeUnitPrc").val() === "undefined"
            ? "0"
            : $("#accbTxCdeUnitPrc").val();
    var accbTxCdeQty =
        typeof $("#accbTxCdeQty").val() === "undefined"
            ? "0"
            : $("#accbTxCdeQty").val();
    accbTxCdeUnitPrc = fmtAsNumber("accbTxCdeUnitPrc");
    accbTxCdeQty = fmtAsNumber("accbTxCdeQty");
    if (accbTxCdeSQL !== "" && (accbTxCdeUnitPrc !== 0 || accbTxCdeQty !== 0)) {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append("grp",6);
            formData.append("typ",1);
            formData.append("pg",14);
            formData.append("q","VIEW");
            formData.append("vtyp",3);
            formData.append("accbTxCdeQty",accbTxCdeQty);
            formData.append("accbTxCdeUnitPrc",accbTxCdeUnitPrc);
            formData.append("accbTxCdeSQL",accbTxCdeSQL);
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#accbTxCdeSQLTestRslts").html(data.message);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                },
            });
        });
    } else {
        $("#accbTxCdeSQLTestRslts").html("Testing Failed!");
    }
}

function adjustSelectedBals(inptHtml) {
    var jrnlBatchAmountCrncy =
        typeof $("#jrnlBatchAmountCrncy").text() === "undefined"
            ? ""
            : $("#jrnlBatchAmountCrncy").text();
    var jrnlBatchDesc =
        typeof $("#jrnlBatchDesc").val() === "undefined"
            ? ""
            : $("#jrnlBatchDesc").val();
    if (jrnlBatchDesc.trim() === "") {
        jrnlBatchDesc = "Quick Adjustment of Account Balances";
        $("#jrnlBatchDesc").val(jrnlBatchDesc);
    }
    var form = document.getElementById("accbFSRptDetForm");
    var cbResults = "";
    var fnl_res = "";
    var entrdFgrs = "";
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type === "checkbox") {
            if (form.elements[i].checked === true) {
                cbResults += form.elements[i].value + "|";
            }
        }
    }
    if (cbResults.length > 1) {
        fnl_res = cbResults.slice(0,-1);
    }
    var bigArry = [];
    var bigArry2 = [];
    bigArry = fnl_res.split("|");
    var i = 0;
    var ln_AccntNum = "";
    var ln_AccntNm = "";
    var ln_AccntID = -1;
    var ln_Rmrks = "";
    var ln_CrncyID = -1;
    var ln_CrncyNm = "";
    var ln_DbtAmt = 0;
    var ln_CrdtAmnt = 0;
    var ln_TrnsDte = "";
    var ln_IsParent = "";
    var rowPrfxNm = "oneJrnlBatchDetRow";
    for (i = 0; i < bigArry.length; i++) {
        var rndmNum1 = bigArry[i].split("_")[1];
        var rowPrfxNm1 = bigArry[i].split("_")[0];
        ln_AccntID =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_AccountID").val() === "undefined"
                ? "-1"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_AccountID").val();
        ln_AccntNum =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_AccntNum").val() === "undefined"
                ? ""
                : $("#" + rowPrfxNm1 + rndmNum1 + "_AccntNum").val();
        ln_AccntNm =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_AccntNm").val() === "undefined"
                ? ""
                : $("#" + rowPrfxNm1 + rndmNum1 + "_AccntNm").val();
        ln_DbtAmt =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_DbtAmnt").val() === "undefined"
                ? "0.00"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_DbtAmnt").val();
        ln_CrdtAmnt =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_CrdtAmnt").val() === "undefined"
                ? "0.00"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_CrdtAmnt").val();
        ln_TrnsDte =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_TrnsDte").val() === "undefined"
                ? ""
                : $("#" + rowPrfxNm1 + rndmNum1 + "_TrnsDte").val();
        ln_IsParent =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_IsParent").val() === "undefined"
                ? "0"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_IsParent").val();
        ln_DbtAmt = Number(ln_DbtAmt.replace(/[^-?0-9\.]/g,""));
        ln_CrdtAmnt = Number(ln_CrdtAmnt.replace(/[^-?0-9\.]/g,""));
        var oldNetAmnt = ln_DbtAmt - ln_CrdtAmnt;
        if (ln_CrdtAmnt > ln_DbtAmt) {
            oldNetAmnt = ln_CrdtAmnt - ln_DbtAmt;
        }
        var newNetAmnt = 0;
        if (ln_IsParent.trim() === "0") {
            var result = prompt(
                "A/C:" +
                ln_AccntNm.trim() +
                " Current. Net Amnt:" +
                oldNetAmnt +
                "\nEnter New Net Amount:"
            );
            newNetAmnt = Number(result.replace(/[^-?0-9\.]/g,""));
            if (ln_CrdtAmnt > ln_DbtAmt) {
                ln_DbtAmt = ln_DbtAmt + newNetAmnt;
            } else {
                ln_CrdtAmnt = ln_CrdtAmnt + newNetAmnt;
            }
            var nwRndm = insertOnlyJrnlBatcRows(
                "oneJrnlBatchDetLinesTable",
                1,
                inptHtml
            );
            $("#" + rowPrfxNm + nwRndm + "_AccountID").val(ln_AccntID);
            $("#" + rowPrfxNm + nwRndm + "_AccountNm").val(ln_AccntNm.trim());
            $("#" + rowPrfxNm + nwRndm + "_LineDesc").val(
                "Adjustment of Balance on Account as at " + ln_TrnsDte
            );
            $("#" + rowPrfxNm + nwRndm + "_TrnsCurNm").val(jrnlBatchAmountCrncy);
            $("#" + rowPrfxNm + nwRndm + "_TrnsCurNm1").text(jrnlBatchAmountCrncy);
            $("#" + rowPrfxNm + nwRndm + "_CreditAmnt").val(ln_DbtAmt);
            $("#" + rowPrfxNm + nwRndm + "_DebitAmnt").val(ln_CrdtAmnt);
            $("#" + rowPrfxNm + nwRndm + "_TransDte").val(ln_TrnsDte + " 12:00:00");
        }
    }

    var cntr = 0;
    $("#oneJrnlBatchDetLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
    $("#accbRcnclJrnlTrnsLinestab").tab("show");
    calcAllJrnlBatchDetTtl();
    calcAllJrnlBatchEditTtl();
}

function moveSelectedTrans() {
    var inptHtml =
        typeof $("#nwRowHtml2").val() === "undefined" ? "" : $("#nwRowHtml2").val();
    var rcnclAccntID =
        typeof $("#rcnclAccntID").val() === "undefined"
            ? "-1"
            : $("#rcnclAccntID").val();
    var rcnclAccntNm =
        typeof $("#rcnclAccntNm").val() === "undefined"
            ? ""
            : $("#rcnclAccntNm").val();
    var jrnlBatchAmountCrncy =
        typeof $("#jrnlBatchAmountCrncy").text() === "undefined"
            ? ""
            : $("#jrnlBatchAmountCrncy").text();
    var jrnlBatchDesc =
        typeof $("#jrnlBatchDesc").val() === "undefined"
            ? ""
            : $("#jrnlBatchDesc").val();
    if (jrnlBatchDesc.trim() === "") {
        jrnlBatchDesc = "Reconciliation Movement of Account Transactions";
        $("#jrnlBatchDesc").val(jrnlBatchDesc);
    }
    var form = document.getElementById("accbFSRptDetForm");
    var cbResults = "";
    var fnl_res = "";
    var entrdFgrs = "";
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].type === "checkbox") {
            if (form.elements[i].checked === true) {
                cbResults += form.elements[i].value + "|";
            }
        }
    }
    if (cbResults.length > 1) {
        fnl_res = cbResults.slice(0,-1);
    }
    var bigArry = [];
    var bigArry2 = [];
    bigArry = fnl_res.split("|");
    var i = 0;
    var ln_TransLineID = "";
    var ln_AccntNm = "";
    var ln_LineDesc = "";
    var ln_AccntID = -1;
    var ln_Rmrks = "";
    var ln_CrncyID = -1;
    var ln_CrncyNm = "";
    var ln_DbtAmt = 0;
    var ln_CrdtAmnt = 0;
    var ln_TrnsDte = "";
    var ln_IsParent = "";
    var rowPrfxNm = "oneJrnlBatchDetRow";
    for (i = 0; i < bigArry.length; i++) {
        var rndmNum1 = bigArry[i].split("_")[1];
        var rowPrfxNm1 = bigArry[i].split("_")[0];
        ln_AccntID =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_AccountID").val() === "undefined"
                ? "-1"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_AccountID").val();
        ln_TransLineID =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_TransLineID").val() ===
                "undefined"
                ? "-1"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_TransLineID").val();
        ln_AccntNm =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_AccntNm").val() === "undefined"
                ? ""
                : $("#" + rowPrfxNm1 + rndmNum1 + "_AccntNm").val();
        ln_LineDesc =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_LineDesc").val() === "undefined"
                ? ""
                : $("#" + rowPrfxNm1 + rndmNum1 + "_LineDesc").val();
        ln_DbtAmt =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_DbtAmnt").val() === "undefined"
                ? "0.00"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_DbtAmnt").val();
        ln_CrdtAmnt =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_CrdtAmnt").val() === "undefined"
                ? "0.00"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_CrdtAmnt").val();
        ln_TrnsDte =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_TrnsDte").val() === "undefined"
                ? ""
                : $("#" + rowPrfxNm1 + rndmNum1 + "_TrnsDte").val();
        ln_IsParent =
            typeof $("#" + rowPrfxNm1 + rndmNum1 + "_IsParent").val() === "undefined"
                ? "0"
                : $("#" + rowPrfxNm1 + rndmNum1 + "_IsParent").val();
        ln_DbtAmt = Number(ln_DbtAmt.replace(/[^-?0-9\.]/g,""));
        ln_CrdtAmnt = Number(ln_CrdtAmnt.replace(/[^-?0-9\.]/g,""));
        var newNetAmnt = 0;
        if (Number(ln_TransLineID.replace(/[^-?0-9\.]/g,"")) > 0) {
            /*Reverse current accounts trans*/
            var nwRndm = insertOnlyJrnlBatcRows(
                "oneJrnlBatchDetLinesTable",
                1,
                inptHtml
            );
            $("#" + rowPrfxNm + nwRndm + "_AccountID").val(ln_AccntID);
            $("#" + rowPrfxNm + nwRndm + "_AccountNm").val(ln_AccntNm.trim());
            $("#" + rowPrfxNm + nwRndm + "_LineDesc").val(
                "(Reconciliation Movement) " + ln_LineDesc
            );
            $("#" + rowPrfxNm + nwRndm + "_TrnsCurNm").val(jrnlBatchAmountCrncy);
            $("#" + rowPrfxNm + nwRndm + "_TrnsCurNm1").text(jrnlBatchAmountCrncy);
            $("#" + rowPrfxNm + nwRndm + "_CreditAmnt").val(ln_DbtAmt);
            $("#" + rowPrfxNm + nwRndm + "_DebitAmnt").val(ln_CrdtAmnt);
            $("#" + rowPrfxNm + nwRndm + "_TransDte").val(ln_TrnsDte + " 12:00:00");
            /*Recreate accounts trans in new account*/
            nwRndm = insertOnlyJrnlBatcRows("oneJrnlBatchDetLinesTable",1,inptHtml);
            $("#" + rowPrfxNm + nwRndm + "_AccountID").val(rcnclAccntID);
            $("#" + rowPrfxNm + nwRndm + "_AccountNm").val(rcnclAccntNm.trim());
            $("#" + rowPrfxNm + nwRndm + "_LineDesc").val(
                "(Reconciliation Movement) " + ln_LineDesc
            );
            $("#" + rowPrfxNm + nwRndm + "_TrnsCurNm").val(jrnlBatchAmountCrncy);
            $("#" + rowPrfxNm + nwRndm + "_TrnsCurNm1").text(jrnlBatchAmountCrncy);
            $("#" + rowPrfxNm + nwRndm + "_CreditAmnt").val(ln_CrdtAmnt);
            $("#" + rowPrfxNm + nwRndm + "_DebitAmnt").val(ln_DbtAmt);
            $("#" + rowPrfxNm + nwRndm + "_TransDte").val(ln_TrnsDte + " 12:00:00");
        }
    }

    var cntr = 0;
    $("#oneJrnlBatchDetLinesTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    cntr++;
                    var prfxName1 = $(el).attr("id").split("_")[0];
                    var rndmNum1 = $(el).attr("id").split("_")[1];
                    var $tds = $("#" + prfxName1 + "_" + rndmNum1).find("td");
                    $tds.eq(0).html(cntr);
                }
            }
        });
    $("#accbRcnclJrnlTrnsLinestab").tab("show");
    calcAllJrnlBatchDetTtl();
    calcAllJrnlBatchEditTtl();
}

var prgstimerid2;
var exprtBtn;
var exprtBtn2;

function exprtAccntChart() {
    var exprtMsg =
        '<form role="form">' +
        '<p style="color:#000;">' +
        "How many Accounts will you like to Export?" +
        "<br/>1=No Accounts(Empty Template)" +
        "<br/>2=All Accounts" +
        "<br/>3-Infinity=Specify the exact number of Accounts to Export<br/>" +
        "</p>" +
        '<div class="form-group" style="margin-bottom:10px !important;">' +
        '<div class="input-group">' +
        '<span class="input-group-addon" id="basic-addon1">' +
        '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
        '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
        "</div>" +
        "</div>" +
        '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
        "</p>" +
        "</form>";

    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: "Export Accounts!",
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) { },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton("btn_exprt_rpt");
            $("#recsToExprt").focus();
        },
        buttons: [
            {
                label: "Cancel",
                icon: "glyphicon glyphicon-menu-left",
                cssClass: "btn-default",
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    window.clearInterval(prgstimerid2);
                    ClearAllIntervals();
                },
            },
            {
                id: "btn_exprt_rpt",
                label: "Export",
                icon: "glyphicon glyphicon-menu-right",
                cssClass: "btn-primary",
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $("#recsToExprt").val();
                    if (!isNumber(inptNum)) {
                        var dialog = bootbox.alert({
                            title: "Exporting Accounts",
                            size: "small",
                            message: "Please provide a valid Number!",
                            callback: function () { },
                        });
                        return false;
                    } else {
                        var $button = this;
                        $button.disable();
                        $button.spin();
                        dialogItself.setClosable(false);
                        document.getElementById("msgAreaExprt").innerHTML =
                            '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src=\'cmn_images/ajax-loader2.gif\'/><br/><span style="color:blue;font-size:11px;text-align: left;margin-top:0px;">Working on Export...Please Wait...</span>';
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 1,
                                    q: "UPDATE",
                                    actyp: 3,
                                    inptNum: inptNum,
                                },
                            });
                            prgstimerid2 = window.setInterval(rfrshAccntChartPrcs,1000);
                        });
                    }
                },
            },
        ],
    });
}

function rfrshAccntChartPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 1,
            q: "UPDATE",
            actyp: 4,
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf("Error") !== -1) {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(
                        data.message +
                        '<br/><a href="' +
                        data.dwnld_url +
                        '">Click to Download File!</a>'
                    );
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html(
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message
                );
                document.getElementById("msgAreaExprt").innerHTML =
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message;
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function importAccntChart() {
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: "Import Accounts?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT ACCOUNTS</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val("");
                    $("#allOtherFileInput6").off("change");
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById("allOtherFileInput6");
                        var file = input.files[0];
                        /* read the file metadata*/
                        var output = "";
                        output +=
                            '<span style="font-weight:bold;">' +
                            escape(file.name) +
                            "</span><br />\n";
                        output += " - FileType: " + (file.type || "n/a") + "<br />\n";
                        output += " - FileSize: " + file.size + " bytes<br />\n";
                        output +=
                            " - LastModified: " +
                            (file.lastModifiedDate
                                ? file.lastModifiedDate.toLocaleDateString()
                                : "n/a") +
                            "<br />\n";

                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: "Validating Selected File",
                            message:
                                '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' +
                                output +
                                "</div>",
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton("btn-srvr-prcs");
                                    $footerButton.disable();
                                    /* read the file content*/
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert("File Not Found!");
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert("File is not readable");
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break; 
                                            default:
                                                alert("An error occurred reading this file.");
                                        }
                                    };
                                    reader.onprogress = function (evt) {
                                        /* evt is an ProgressEvent.*/
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round(
                                                (evt.loaded / evt.total) * 100
                                            );
                                            /* Increase the progress bar length.*/
                                            var elem = document.getElementById("myBar");
                                            elem.style.width = percentLoaded + "%";
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File...Please Wait...</span>"
                                                );
                                            } else {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-check"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File Completed!</span>"
                                                );

                                                var $footerButton = dialogItself.getButton(
                                                    "btn-srvr-prcs"
                                                );
                                                if (isFileValid == true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert("File read cancelled");
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById("myBar");
                                        elem.style.width = "1%";
                                        $("#myInformation").html(
                                            '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing Accounts...Please Wait...</span>'
                                        );
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var accntnumber = "";
                                            var accntName = "";
                                            var accntDesc = "";
                                            var accntType = "";
                                            var prntAccntName = "";
                                            var isParentAccnt = "";
                                            var isRetErngAccnt = "";
                                            var isNetIncmAccnt = "";
                                            var isContraAccnt = "";
                                            var reportLineNo = "";
                                            var hasSubledgers = "";
                                            var cntrlAccntName = "";
                                            var accntCrncyCode = "";
                                            var isSuspenseAccnt = "";
                                            var accntClsfctn = "";
                                            var sgmnt1Value = "";
                                            var sgmnt2Value = "";
                                            var sgmnt3Value = "";
                                            var sgmnt4Value = "";
                                            var sgmnt5Value = "";
                                            var sgmnt6Value = "";
                                            var sgmnt7Value = "";
                                            var sgmnt8Value = "";
                                            var sgmnt9Value = "";
                                            var sgmnt10Value = "";
                                            var mappedGrpAccntNum = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            accntnumber = data[row][item];
                                                            break;
                                                        case 2:
                                                            accntName = data[row][item];
                                                            break;
                                                        case 3:
                                                            accntDesc = data[row][item];
                                                            break;
                                                        case 4:
                                                            accntType = data[row][item];
                                                            break;
                                                        case 5:
                                                            prntAccntName = data[row][item];
                                                            break;
                                                        case 6:
                                                            isParentAccnt = data[row][item];
                                                            break;
                                                        case 7:
                                                            isRetErngAccnt = data[row][item];
                                                            break;
                                                        case 8:
                                                            isNetIncmAccnt = data[row][item];
                                                            break;
                                                        case 9:
                                                            isContraAccnt = data[row][item];
                                                            break;
                                                        case 10:
                                                            reportLineNo = data[row][item];
                                                            break;
                                                        case 11:
                                                            hasSubledgers = data[row][item];
                                                            break;
                                                        case 12:
                                                            cntrlAccntName = data[row][item];
                                                            break;
                                                        case 13:
                                                            accntCrncyCode = data[row][item];
                                                            break;
                                                        case 14:
                                                            isSuspenseAccnt = data[row][item];
                                                            break;
                                                        case 15:
                                                            accntClsfctn = data[row][item];
                                                            break;
                                                        case 16:
                                                            sgmnt1Value = data[row][item];
                                                            break;
                                                        case 17:
                                                            sgmnt2Value = data[row][item];
                                                            break;
                                                        case 18:
                                                            sgmnt3Value = data[row][item];
                                                            break;
                                                        case 19:
                                                            sgmnt4Value = data[row][item];
                                                            break;
                                                        case 20:
                                                            sgmnt5Value = data[row][item];
                                                            break;
                                                        case 21:
                                                            sgmnt6Value = data[row][item];
                                                            break;
                                                        case 22:
                                                            sgmnt7Value = data[row][item];
                                                            break;
                                                        case 23:
                                                            sgmnt8Value = data[row][item];
                                                            break;
                                                        case 24:
                                                            sgmnt9Value = data[row][item];
                                                            break;
                                                        case 25:
                                                            sgmnt10Value = data[row][item];
                                                            break;
                                                        case 26:
                                                            mappedGrpAccntNum = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: "Error-Validating Selected File",
                                                                size: "small",
                                                                message:
                                                                    '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                },
                                                            });
                                                    }
                                                }
                                                if (rwCntr === 0) {
                                                    if (
                                                        accntnumber.toUpperCase() ===
                                                        "Account Number**".toUpperCase() &&
                                                        accntName.toUpperCase() ===
                                                        "Account Name**".toUpperCase() &&
                                                        accntType.toUpperCase() ===
                                                        "Account Type**".toUpperCase() &&
                                                        mappedGrpAccntNum.toUpperCase() ===
                                                        "Mapped Group Org Account No.".toUpperCase()
                                                    ) {
                                                        /*alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());*/
                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: "Error-Import Accounts",
                                                            size: "small",
                                                            message:
                                                                '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            },
                                                        });
                                                    }
                                                }
                                                if (
                                                    accntnumber.trim() !== "" &&
                                                    accntName.trim() !== "" &&
                                                    accntType.trim() !== "" &&
                                                    accntCrncyCode.trim() !== ""
                                                ) {
                                                    dataToSend =
                                                        dataToSend +
                                                        accntnumber
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        accntName
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        accntDesc
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        accntType
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        prntAccntName
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        isParentAccnt
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        isRetErngAccnt
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        isNetIncmAccnt
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        isContraAccnt
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        reportLineNo
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        hasSubledgers
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        cntrlAccntName
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        accntCrncyCode
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        isSuspenseAccnt
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        accntClsfctn
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt1Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt2Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt3Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt4Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt5Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt6Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt7Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt8Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt9Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        sgmnt10Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        mappedGrpAccntNum
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "|";
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output +=
                                                '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' +
                                                vldRwCntr;
                                            output += "<br/>Total No. of Rows:" + rwCntr + "</span>";
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import Accounts",
                                                size: "small",
                                                message: "Error:" + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                },
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                },500);
                            },
                            buttons: [
                                {
                                    label: "Cancel",
                                    icon: "glyphicon glyphicon-menu-left",
                                    cssClass: "btn-default",
                                    action: function (dialogItself) {
                                        isFileValid = false;
                                        reader.abort();
                                        dialogItself.close();
                                    },
                                },
                                {
                                    id: "btn-srvr-prcs",
                                    label: "Start Server Processing",
                                    icon: "glyphicon glyphicon-menu-right",
                                    cssClass: "btn-primary",
                                    action: function (dialogItself) {
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveAccntChartExcl(dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import Accounts",
                                                size: "small",
                                                message:
                                                    '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () { },
                                            });
                                        }
                                    },
                                },
                            ],
                        });
                    });
                    performFileClick("allOtherFileInput6");
                }
            }
        },
    });
}

function saveAccntChartExcl(dataToSend) {
    if (dataToSend.trim() === "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message:
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">No Data to Send!</span></p>',
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Importing Accounts",
        size: "small",
        message:
            '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Accounts...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            getAccbAcntChrt("clear","#allmodules","grp=6&typ=1&pg=1&vtyp=0");
            ClearAllIntervals();
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 1,
                    q: "UPDATE",
                    actyp: 101,
                    dataToSend: dataToSend,
                },
            });
            prgstimerid2 = window.setInterval(rfrshSaveAccntChart,1000);
        });
    });
}

function rfrshSaveAccntChart() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 1,
            q: "UPDATE",
            actyp: 102,
        },
        success: function (data) {
            var elem = document.getElementById("myBar1");
            elem.style.width = data.percent + "%";
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function exprtAccntDetTrns() {
    var sbmtdJrnlBatchID =
        typeof $("#sbmtdJrnlBatchID").val() === "undefined"
            ? "-1"
            : $("#sbmtdJrnlBatchID").val();
    var partMsg = "Debit/Credit Transactions";
    var exprtMsg =
        '<form role="form">' +
        '<p style="color:#000;">' +
        "How many " +
        partMsg +
        " will you like to Export?" +
        "<br/>1=No " +
        partMsg +
        "(Empty Template)" +
        "<br/>2=All " +
        partMsg +
        "" +
        "<br/>3-Infinity=Specify the exact number of " +
        partMsg +
        " to Export<br/>" +
        "</p>" +
        '<div class="form-group" style="margin-bottom:10px !important;">' +
        '<div class="input-group">' +
        '<span class="input-group-addon" id="basic-addon1">' +
        '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
        '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
        "</div>" +
        "</div>" +
        '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
        "</p>" +
        "</form>";

    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: "Export " + partMsg + "!",
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) { },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton("btn_exprt_rpt");
            $("#recsToExprt").focus();
        },
        buttons: [
            {
                label: "Cancel",
                icon: "glyphicon glyphicon-menu-left",
                cssClass: "btn-default",
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    window.clearInterval(prgstimerid2);
                    ClearAllIntervals();
                },
            },
            {
                id: "btn_exprt_rpt",
                label: "Export",
                icon: "glyphicon glyphicon-menu-right",
                cssClass: "btn-primary",
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $("#recsToExprt").val();
                    if (!isNumber(inptNum)) {
                        var dialog = bootbox.alert({
                            title: "Exporting " + partMsg + "",
                            size: "small",
                            message: "Please provide a valid Number!",
                            callback: function () { },
                        });
                        return false;
                    } else {
                        var $button = this;
                        $button.disable();
                        $button.spin();
                        dialogItself.setClosable(false);
                        document.getElementById("msgAreaExprt").innerHTML =
                            '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src=\'cmn_images/ajax-loader2.gif\'/><br/><span style="color:blue;font-size:11px;text-align: left;margin-top:0px;">Working on Export...Please Wait...</span>';
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 2,
                                    q: "UPDATE",
                                    actyp: 3,
                                    inptNum: inptNum,
                                    sbmtdJrnlBatchID: sbmtdJrnlBatchID,
                                },
                            });
                            prgstimerid2 = window.setInterval(rfrshAccntDetTrns,1000);
                        });
                    }
                },
            },
        ],
    });
}

function rfrshAccntDetTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 2,
            q: "UPDATE",
            actyp: 4,
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf("Error") !== -1) {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(
                        data.message +
                        '<br/><a href="' +
                        data.dwnld_url +
                        '">Click to Download File!</a>'
                    );
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html(
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message
                );
                document.getElementById("msgAreaExprt").innerHTML =
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message;
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function importAccntDetTrns(sbmtdJrnlBatchID) {
    var partMsg = "Debit/Credit Transactions";
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: "Import " + partMsg + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT ' +
            partMsg.toUpperCase() +
            "</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val("");
                    $("#allOtherFileInput6").off("change");
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById("allOtherFileInput6");
                        var file = input.files[0];
                        /* read the file metadata*/
                        var output = "";
                        output +=
                            '<span style="font-weight:bold;">' +
                            escape(file.name) +
                            "</span><br />\n";
                        output += " - FileType: " + (file.type || "n/a") + "<br />\n";
                        output += " - FileSize: " + file.size + " bytes<br />\n";
                        output +=
                            " - LastModified: " +
                            (file.lastModifiedDate
                                ? file.lastModifiedDate.toLocaleDateString()
                                : "n/a") +
                            "<br />\n";

                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: "Validating Selected File",
                            message:
                                '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' +
                                output +
                                "</div>",
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton("btn-srvr-prcs");
                                    $footerButton.disable();
                                    /* read the file content*/
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert("File Not Found!");
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert("File is not readable");
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break; 
                                            default:
                                                alert("An error occurred reading this file.");
                                        }
                                    };
                                    reader.onprogress = function (evt) {
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round(
                                                (evt.loaded / evt.total) * 100
                                            );
                                            /* Increase the progress bar length.*/
                                            var elem = document.getElementById("myBar");
                                            elem.style.width = percentLoaded + "%";
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File...Please Wait...</span>"
                                                );
                                            } else {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-check"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File Completed!</span>"
                                                );

                                                var $footerButton = dialogItself.getButton(
                                                    "btn-srvr-prcs"
                                                );
                                                if (isFileValid == true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert("File read cancelled");
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById("myBar");
                                        elem.style.width = "1%";
                                        $("#myInformation").html(
                                            '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing ' +
                                            partMsg +
                                            "...Please Wait...</span>"
                                        );
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var rownumber = "";
                                            var accntnumber = "";
                                            var accntName = "";
                                            var transDesc = "";
                                            var debitAmount = "";
                                            var creditAmount = "";
                                            var transDate = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            rownumber = data[row][item];
                                                            break;
                                                        case 2:
                                                            accntnumber = data[row][item];
                                                            break;
                                                        case 3:
                                                            accntName = data[row][item];
                                                            break;
                                                        case 4:
                                                            transDesc = data[row][item];
                                                            break;
                                                        case 5:
                                                            debitAmount = data[row][item];
                                                            break;
                                                        case 6:
                                                            creditAmount = data[row][item];
                                                            break;
                                                        case 7:
                                                            transDate = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: "Error-Validating Selected File",
                                                                size: "small",
                                                                message:
                                                                    '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                },
                                                            });
                                                    }
                                                }
                                                if (rwCntr === 0) {
                                                    if (
                                                        accntnumber.toUpperCase() ===
                                                        "Account Number**".toUpperCase() &&
                                                        accntName.toUpperCase() ===
                                                        "Account Name".toUpperCase() &&
                                                        transDesc.toUpperCase() ===
                                                        "Transaction Description**".toUpperCase() &&
                                                        debitAmount.toUpperCase() ===
                                                        "DEBIT**".toUpperCase() &&
                                                        creditAmount.toUpperCase() ===
                                                        "CREDIT**".toUpperCase() &&
                                                        transDate.toUpperCase() ===
                                                        "Transaction Date**".toUpperCase()
                                                    ) {
                                                        /*alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());*/
                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: "Error-Import " + partMsg + "",
                                                            size: "small",
                                                            message:
                                                                '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            },
                                                        });
                                                    }
                                                }
                                                if (
                                                    accntnumber.trim() !== "" &&
                                                    transDesc.trim() !== "" &&
                                                    transDate.trim() !== ""
                                                ) {
                                                    if (rwCntr === 0) {
                                                        dataToSend =
                                                            dataToSend +
                                                            accntnumber
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDesc
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            debitAmount
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            creditAmount
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDate
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "|";
                                                    } else {
                                                        dataToSend =
                                                            dataToSend +
                                                            accntnumber
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDesc
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            debitAmount
                                                                .replace(/[^-?0-9\.]/g,"")
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            creditAmount
                                                                .replace(/[^-?0-9\.]/g,"")
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDate
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "|";
                                                    }
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output +=
                                                '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' +
                                                vldRwCntr;
                                            output += "<br/>Total No. of Rows:" + rwCntr + "</span>";
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import " + partMsg + "",
                                                size: "small",
                                                message: "Error:" + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                },
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                },500);
                            },
                            buttons: [
                                {
                                    label: "Cancel",
                                    icon: "glyphicon glyphicon-menu-left",
                                    cssClass: "btn-default",
                                    action: function (dialogItself) {
                                        isFileValid = false;
                                        reader.abort();
                                        dialogItself.close();
                                    },
                                },
                                {
                                    id: "btn-srvr-prcs",
                                    label: "Start Server Processing",
                                    icon: "glyphicon glyphicon-menu-right",
                                    cssClass: "btn-primary",
                                    action: function (dialogItself) {
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveAccntDetTrnsExcl(sbmtdJrnlBatchID,dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import " + partMsg + "",
                                                size: "small",
                                                message:
                                                    '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () { },
                                            });
                                        }
                                    },
                                },
                            ],
                        });
                    });
                    performFileClick("allOtherFileInput6");
                }
            }
        },
    });
}

function saveAccntDetTrnsExcl(sbmtdJrnlBatchID,dataToSend) {
    var partMsg = "Debit/Credit Transactions";
    if (dataToSend.trim() === "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message:
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">No Data to Send!</span></p>',
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Importing " + partMsg + "",
        size: "small",
        message:
            '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing ' +
            partMsg +
            "...Please Wait...</div>",
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            /*getAccbAcntChrt('clear', '#allmodules', 'grp=6&typ=1&pg=1&vtyp=0');*/
            var sbmtdJrnlBatchID =
                typeof $("#sbmtdJrnlBatchID").val() === "undefined"
                    ? "-1"
                    : $("#sbmtdJrnlBatchID").val();
            getOneJrnlBatchForm(sbmtdJrnlBatchID,1,"ReloadDialog",-1,"");
            ClearAllIntervals();
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 2,
                    q: "UPDATE",
                    actyp: 103,
                    sbmtdJrnlBatchID: sbmtdJrnlBatchID,
                    dataToSend: dataToSend,
                },
            });
            prgstimerid2 = window.setInterval(rfrshSaveDetTrns,1000);
        });
    });
}

function rfrshSaveDetTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 2,
            q: "UPDATE",
            actyp: 104,
        },
        success: function (data) {
            var elem = document.getElementById("myBar1");
            elem.style.width = data.percent + "%";
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function exprtAccntEditTrns() {
    var sbmtdJrnlBatchID =
        typeof $("#sbmtdJrnlBatchID").val() === "undefined"
            ? "-1"
            : $("#sbmtdJrnlBatchID").val();
    var partMsg = "Increase/Decrease Transactions";
    var exprtMsg =
        '<form role="form">' +
        '<p style="color:#000;">' +
        "How many " +
        partMsg +
        " will you like to Export?" +
        "<br/>1=No " +
        partMsg +
        "(Empty Template)" +
        "<br/>2=All " +
        partMsg +
        "" +
        "<br/>3-Infinity=Specify the exact number of " +
        partMsg +
        " to Export<br/>" +
        "</p>" +
        '<div class="form-group" style="margin-bottom:10px !important;">' +
        '<div class="input-group">' +
        '<span class="input-group-addon" id="basic-addon1">' +
        '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
        '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
        "</div>" +
        "</div>" +
        '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
        "</p>" +
        "</form>";

    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: "Export " + partMsg + "!",
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) { },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton("btn_exprt_rpt");
            $("#recsToExprt").focus();
        },
        buttons: [
            {
                label: "Cancel",
                icon: "glyphicon glyphicon-menu-left",
                cssClass: "btn-default",
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    window.clearInterval(prgstimerid2);
                    ClearAllIntervals();
                },
            },
            {
                id: "btn_exprt_rpt",
                label: "Export",
                icon: "glyphicon glyphicon-menu-right",
                cssClass: "btn-primary",
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $("#recsToExprt").val();
                    if (!isNumber(inptNum)) {
                        var dialog = bootbox.alert({
                            title: "Exporting " + partMsg + "",
                            size: "small",
                            message: "Please provide a valid Number!",
                            callback: function () { },
                        });
                        return false;
                    } else {
                        var $button = this;
                        $button.disable();
                        $button.spin();
                        dialogItself.setClosable(false);
                        document.getElementById("msgAreaExprt").innerHTML =
                            '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src=\'cmn_images/ajax-loader2.gif\'/><br/><span style="color:blue;font-size:11px;text-align: left;margin-top:0px;">Working on Export...Please Wait...</span>';
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 2,
                                    q: "UPDATE",
                                    actyp: 5,
                                    inptNum: inptNum,
                                    sbmtdJrnlBatchID: sbmtdJrnlBatchID,
                                },
                            });
                            prgstimerid2 = window.setInterval(rfrshAccntEditTrns,1000);
                        });
                    }
                },
            },
        ],
    });
}

function rfrshAccntEditTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 2,
            q: "UPDATE",
            actyp: 6,
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf("Error") !== -1) {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(
                        data.message +
                        '<br/><a href="' +
                        data.dwnld_url +
                        '">Click to Download File!</a>'
                    );
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html(
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message
                );
                document.getElementById("msgAreaExprt").innerHTML =
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message;
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function importAccntEditTrns(sbmtdJrnlBatchID) {
    var partMsg = "Increase/Decrease Transactions";
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: "Import " + partMsg + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT ' +
            partMsg.toUpperCase() +
            "</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val("");
                    $("#allOtherFileInput6").off("change");
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById("allOtherFileInput6");
                        var file = input.files[0];
                        /* read the file metadata*/
                        var output = "";
                        output +=
                            '<span style="font-weight:bold;">' +
                            escape(file.name) +
                            "</span><br />\n";
                        output += " - FileType: " + (file.type || "n/a") + "<br />\n";
                        output += " - FileSize: " + file.size + " bytes<br />\n";
                        output +=
                            " - LastModified: " +
                            (file.lastModifiedDate
                                ? file.lastModifiedDate.toLocaleDateString()
                                : "n/a") +
                            "<br />\n";

                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: "Validating Selected File",
                            message:
                                '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' +
                                output +
                                "</div>",
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton("btn-srvr-prcs");
                                    $footerButton.disable();
                                    /* read the file content*/
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert("File Not Found!");
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert("File is not readable");
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break; 
                                            default:
                                                alert("An error occurred reading this file.");
                                        }
                                    };
                                    reader.onprogress = function (evt) {
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round(
                                                (evt.loaded / evt.total) * 100
                                            );
                                            /* Increase the progress bar length.*/
                                            var elem = document.getElementById("myBar");
                                            elem.style.width = percentLoaded + "%";
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File...Please Wait...</span>"
                                                );
                                            } else {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-check"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File Completed!</span>"
                                                );

                                                var $footerButton = dialogItself.getButton(
                                                    "btn-srvr-prcs"
                                                );
                                                if (isFileValid == true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert("File read cancelled");
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById("myBar");
                                        elem.style.width = "1%";
                                        $("#myInformation").html(
                                            '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing ' +
                                            partMsg +
                                            "...Please Wait...</span>"
                                        );
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var rownumber = "";
                                            var transDesc = "";
                                            var transRefDocNo = "";
                                            var transIncrsDcrs = "";
                                            var accntnumber = "";
                                            var accntName = "";
                                            var transAmount = "";
                                            var transCurr = "";
                                            var transDate = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            rownumber = data[row][item];
                                                            break;
                                                        case 2:
                                                            transDesc = data[row][item];
                                                            break;
                                                        case 3:
                                                            transRefDocNo = data[row][item];
                                                            break;
                                                        case 4:
                                                            transIncrsDcrs = data[row][item];
                                                            break;
                                                        case 5:
                                                            accntnumber = data[row][item];
                                                            break;
                                                        case 6:
                                                            accntName = data[row][item];
                                                            break;
                                                        case 7:
                                                            transAmount = data[row][item];
                                                            break;
                                                        case 8:
                                                            transCurr = data[row][item];
                                                            break;
                                                        case 9:
                                                            transDate = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: "Error-Validating Selected File",
                                                                size: "small",
                                                                message:
                                                                    '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                },
                                                            });
                                                    }
                                                }
                                                if (rwCntr === 0) {
                                                    if (
                                                        accntnumber.toUpperCase() ===
                                                        "Account Number**".toUpperCase() &&
                                                        accntName.toUpperCase() ===
                                                        "Account Name".toUpperCase() &&
                                                        transDesc.toUpperCase() ===
                                                        "Transaction Description**".toUpperCase() &&
                                                        transDate.toUpperCase() ===
                                                        "Transaction Date**".toUpperCase()
                                                    ) {
                                                        /*alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());*/
                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: "Error-Import " + partMsg + "",
                                                            size: "small",
                                                            message:
                                                                '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            },
                                                        });
                                                    }
                                                }
                                                if (
                                                    accntnumber.trim() !== "" &&
                                                    accntName.trim() !== "" &&
                                                    transDesc.trim() !== "" &&
                                                    transDate.trim() !== ""
                                                ) {
                                                    if (rwCntr === 0) {
                                                        dataToSend =
                                                            dataToSend +
                                                            transDesc
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transRefDocNo
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transIncrsDcrs
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntnumber
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transAmount
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transCurr
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDate
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "|";
                                                    } else {
                                                        dataToSend =
                                                            dataToSend +
                                                            transDesc
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transRefDocNo
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transIncrsDcrs
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntnumber
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transAmount
                                                                .replace(/[^-?0-9\.]/g,"")
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transCurr
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDate
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "|";
                                                    }
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output +=
                                                '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' +
                                                vldRwCntr;
                                            output += "<br/>Total No. of Rows:" + rwCntr + "</span>";
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import " + partMsg + "",
                                                size: "small",
                                                message: "Error:" + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                },
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                },500);
                            },
                            buttons: [
                                {
                                    label: "Cancel",
                                    icon: "glyphicon glyphicon-menu-left",
                                    cssClass: "btn-default",
                                    action: function (dialogItself) {
                                        isFileValid = false;
                                        reader.abort();
                                        dialogItself.close();
                                    },
                                },
                                {
                                    id: "btn-srvr-prcs",
                                    label: "Start Server Processing",
                                    icon: "glyphicon glyphicon-menu-right",
                                    cssClass: "btn-primary",
                                    action: function (dialogItself) {
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveAccntEditTrnsExcl(sbmtdJrnlBatchID,dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import " + partMsg + "",
                                                size: "small",
                                                message:
                                                    '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () { },
                                            });
                                        }
                                    },
                                },
                            ],
                        });
                    });
                    performFileClick("allOtherFileInput6");
                }
            }
        },
    });
}

function saveAccntEditTrnsExcl(sbmtdJrnlBatchID,dataToSend) {
    var partMsg = "Increase/Decrease Transactions";
    if (dataToSend.trim() === "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message:
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">No Data to Send!</span></p>',
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Importing " + partMsg + "",
        size: "small",
        message:
            '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing ' +
            partMsg +
            "...Please Wait...</div>",
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            /*getAccbAcntChrt('clear', '#allmodules', 'grp=6&typ=1&pg=1&vtyp=0');*/
            var sbmtdJrnlBatchID =
                typeof $("#sbmtdJrnlBatchID").val() === "undefined"
                    ? "-1"
                    : $("#sbmtdJrnlBatchID").val();
            getOneJrnlBatchForm(sbmtdJrnlBatchID,1,"ReloadDialog",-1,"");
            ClearAllIntervals();
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 2,
                    q: "UPDATE",
                    actyp: 105,
                    sbmtdJrnlBatchID: sbmtdJrnlBatchID,
                    dataToSend: dataToSend,
                },
            });
            prgstimerid2 = window.setInterval(rfrshSaveEditTrns,1000);
        });
    });
}

function rfrshSaveEditTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 2,
            q: "UPDATE",
            actyp: 106,
        },
        success: function (data) {
            var elem = document.getElementById("myBar1");
            elem.style.width = data.percent + "%";
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function exprtAccntSmmryTrns() {
    var sbmtdJrnlBatchID =
        typeof $("#sbmtdJrnlBatchID").val() === "undefined"
            ? "-1"
            : $("#sbmtdJrnlBatchID").val();
    var partMsg = "Simplified Transactions";
    var exprtMsg =
        '<form role="form">' +
        '<p style="color:#000;">' +
        "How many " +
        partMsg +
        " will you like to Export?" +
        "<br/>1=No " +
        partMsg +
        "(Empty Template)" +
        "<br/>2=All " +
        partMsg +
        "" +
        "<br/>3-Infinity=Specify the exact number of " +
        partMsg +
        " to Export<br/>" +
        "</p>" +
        '<div class="form-group" style="margin-bottom:10px !important;">' +
        '<div class="input-group">' +
        '<span class="input-group-addon" id="basic-addon1">' +
        '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
        '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
        "</div>" +
        "</div>" +
        '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
        "</p>" +
        "</form>";

    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: "Export " + partMsg + "!",
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) { },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton("btn_exprt_rpt");
            $("#recsToExprt").focus();
        },
        buttons: [
            {
                label: "Cancel",
                icon: "glyphicon glyphicon-menu-left",
                cssClass: "btn-default",
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    window.clearInterval(prgstimerid2);
                    ClearAllIntervals();
                },
            },
            {
                id: "btn_exprt_rpt",
                label: "Export",
                icon: "glyphicon glyphicon-menu-right",
                cssClass: "btn-primary",
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $("#recsToExprt").val();
                    if (!isNumber(inptNum)) {
                        var dialog = bootbox.alert({
                            title: "Exporting " + partMsg + "",
                            size: "small",
                            message: "Please provide a valid Number!",
                            callback: function () { },
                        });
                        return false;
                    } else {
                        var $button = this;
                        $button.disable();
                        $button.spin();
                        dialogItself.setClosable(false);
                        document.getElementById("msgAreaExprt").innerHTML =
                            '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src=\'cmn_images/ajax-loader2.gif\'/><br/><span style="color:blue;font-size:11px;text-align: left;margin-top:0px;">Working on Export...Please Wait...</span>';
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 2,
                                    q: "UPDATE",
                                    actyp: 7,
                                    inptNum: inptNum,
                                    sbmtdJrnlBatchID: sbmtdJrnlBatchID,
                                },
                            });
                            prgstimerid2 = window.setInterval(rfrshAccntSmmryTrns,1000);
                        });
                    }
                },
            },
        ],
    });
}

function rfrshAccntSmmryTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 2,
            q: "UPDATE",
            actyp: 8,
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf("Error") !== -1) {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(
                        data.message +
                        '<br/><a href="' +
                        data.dwnld_url +
                        '">Click to Download File!</a>'
                    );
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html(
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message
                );
                document.getElementById("msgAreaExprt").innerHTML =
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message;
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function importAccntSmmryTrns(sbmtdJrnlBatchID) {
    var partMsg = "Simplified Transactions";
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: "Import " + partMsg + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT ' +
            partMsg.toUpperCase() +
            "</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val("");
                    $("#allOtherFileInput6").off("change");
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById("allOtherFileInput6");
                        var file = input.files[0];
                        /* read the file metadata*/
                        var output = "";
                        output +=
                            '<span style="font-weight:bold;">' +
                            escape(file.name) +
                            "</span><br />\n";
                        output += " - FileType: " + (file.type || "n/a") + "<br />\n";
                        output += " - FileSize: " + file.size + " bytes<br />\n";
                        output +=
                            " - LastModified: " +
                            (file.lastModifiedDate
                                ? file.lastModifiedDate.toLocaleDateString()
                                : "n/a") +
                            "<br />\n";

                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: "Validating Selected File",
                            message:
                                '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' +
                                output +
                                "</div>",
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton("btn-srvr-prcs");
                                    $footerButton.disable();
                                    /* read the file content*/
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert("File Not Found!");
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert("File is not readable");
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break; 
                                            default:
                                                alert("An error occurred reading this file.");
                                        }
                                    };
                                    reader.onprogress = function (evt) {
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round(
                                                (evt.loaded / evt.total) * 100
                                            );
                                            /* Increase the progress bar length.*/
                                            var elem = document.getElementById("myBar");
                                            elem.style.width = percentLoaded + "%";
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File...Please Wait...</span>"
                                                );
                                            } else {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-check"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File Completed!</span>"
                                                );

                                                var $footerButton = dialogItself.getButton(
                                                    "btn-srvr-prcs"
                                                );
                                                if (isFileValid == true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert("File read cancelled");
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById("myBar");
                                        elem.style.width = "1%";
                                        $("#myInformation").html(
                                            '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing ' +
                                            partMsg +
                                            "...Please Wait...</span>"
                                        );
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var rownumber = "";
                                            var transDesc = "";
                                            var transRefDocNo = "";
                                            var transIncrsDcrs1 = "";
                                            var accntnumber1 = "";
                                            var accntName1 = "";
                                            var transIncrsDcrs2 = "";
                                            var accntnumber2 = "";
                                            var accntName2 = "";
                                            var transAmount = "";
                                            var transCurr = "";
                                            var transDate = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            rownumber = data[row][item];
                                                            break;
                                                        case 2:
                                                            transDesc = data[row][item];
                                                            break;
                                                        case 3:
                                                            transRefDocNo = data[row][item];
                                                            break;
                                                        case 4:
                                                            transIncrsDcrs1 = data[row][item];
                                                            break;
                                                        case 5:
                                                            accntnumber1 = data[row][item];
                                                            break;
                                                        case 6:
                                                            accntName1 = data[row][item];
                                                            break;
                                                        case 7:
                                                            transIncrsDcrs2 = data[row][item];
                                                            break;
                                                        case 8:
                                                            accntnumber2 = data[row][item];
                                                            break;
                                                        case 9:
                                                            accntName2 = data[row][item];
                                                            break;
                                                        case 10:
                                                            transAmount = data[row][item];
                                                            break;
                                                        case 11:
                                                            transCurr = data[row][item];
                                                            break;
                                                        case 12:
                                                            transDate = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: "Error-Validating Selected File",
                                                                size: "small",
                                                                message:
                                                                    '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                },
                                                            });
                                                    }
                                                }
                                                if (rwCntr === 0) {
                                                    if (
                                                        transDesc.toUpperCase() ===
                                                        "Transaction Description**".toUpperCase() &&
                                                        transRefDocNo.toUpperCase() ===
                                                        "Cheque/Voucher/Receipt No. (Ref. Doc. No.)".toUpperCase() &&
                                                        transIncrsDcrs1.toUpperCase() ===
                                                        "Increase/Decrease 1**".toUpperCase() &&
                                                        transDate.toUpperCase() ===
                                                        "Transaction Date**".toUpperCase()
                                                    ) {
                                                        /*alert(transDesc.toUpperCase() + "|" + transRefDocNo.toUpperCase() +
                                                                                                                                             "|" + transIncrsDcrs1.toUpperCase() + "|" + transDate.toUpperCase());*/
                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: "Error-Import " + partMsg + "",
                                                            size: "small",
                                                            message:
                                                                '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            },
                                                        });
                                                    }
                                                }
                                                if (
                                                    transDesc.trim() !== "" &&
                                                    transIncrsDcrs1.trim() !== "" &&
                                                    transDate.trim() !== "" &&
                                                    transIncrsDcrs2.trim() !== ""
                                                ) {
                                                    if (rwCntr === 0) {
                                                        dataToSend =
                                                            dataToSend +
                                                            transDesc
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transRefDocNo
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transIncrsDcrs1
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntnumber1
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName1
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transIncrsDcrs2
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntnumber2
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName2
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transAmount
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transCurr
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDate
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "|";
                                                    } else {
                                                        dataToSend =
                                                            dataToSend +
                                                            transDesc
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transRefDocNo
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transIncrsDcrs1
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntnumber1
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName1
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transIncrsDcrs2
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntnumber2
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            accntName2
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transAmount
                                                                .replace(/[^-?0-9\.]/g,"")
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transCurr
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "~" +
                                                            transDate
                                                                .replace(/(~)/g,"{-;-;}")
                                                                .replace(/(\|)/g,"{:;:;}") +
                                                            "|";
                                                    }
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output +=
                                                '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' +
                                                vldRwCntr;
                                            output += "<br/>Total No. of Rows:" + rwCntr + "</span>";
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import " + partMsg + "",
                                                size: "small",
                                                message: "Error:" + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                },
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                },500);
                            },
                            buttons: [
                                {
                                    label: "Cancel",
                                    icon: "glyphicon glyphicon-menu-left",
                                    cssClass: "btn-default",
                                    action: function (dialogItself) {
                                        isFileValid = false;
                                        reader.abort();
                                        dialogItself.close();
                                    },
                                },
                                {
                                    id: "btn-srvr-prcs",
                                    label: "Start Server Processing",
                                    icon: "glyphicon glyphicon-menu-right",
                                    cssClass: "btn-primary",
                                    action: function (dialogItself) {
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveAccntSmmryTrnsExcl(sbmtdJrnlBatchID,dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import " + partMsg + "",
                                                size: "small",
                                                message:
                                                    '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () { },
                                            });
                                        }
                                    },
                                },
                            ],
                        });
                    });
                    performFileClick("allOtherFileInput6");
                }
            }
        },
    });
}

function saveAccntSmmryTrnsExcl(sbmtdJrnlBatchID,dataToSend) {
    var partMsg = "Simplified Transactions";
    if (dataToSend.trim() === "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message:
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">No Data to Send!</span></p>',
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Importing " + partMsg + "",
        size: "small",
        message:
            '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing ' +
            partMsg +
            "...Please Wait...</div>",
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            /*getAccbAcntChrt('clear', '#allmodules', 'grp=6&typ=1&pg=1&vtyp=0');*/
            var sbmtdJrnlBatchID =
                typeof $("#sbmtdJrnlBatchID").val() === "undefined"
                    ? "-1"
                    : $("#sbmtdJrnlBatchID").val();
            getOneJrnlBatchForm(sbmtdJrnlBatchID,1,"ReloadDialog",-1,"");
            ClearAllIntervals();
        },
    });
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 2,
                    q: "UPDATE",
                    actyp: 107,
                    sbmtdJrnlBatchID: sbmtdJrnlBatchID,
                    dataToSend: dataToSend,
                },
            });
            prgstimerid2 = window.setInterval(rfrshSaveSmmryTrns,1000);
        });
    });
}

function rfrshSaveSmmryTrns() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 2,
            q: "UPDATE",
            actyp: 108,
        },
        success: function (data) {
            var elem = document.getElementById("myBar1");
            elem.style.width = data.percent + "%";
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}
/*Export/Import Budgets*/
function exprtAccntBdgts() {
    var accbSbmtdBudgetID =
        typeof $("#accbSbmtdBudgetID").val() === "undefined"
            ? "-1"
            : $("#accbSbmtdBudgetID").val();
    var exprtMsg =
        '<form role="form">' +
        '<p style="color:#000;">' +
        "How many Budget Lines will you like to Export?" +
        "<br/>1=No Budget Lines(Empty Template)" +
        "<br/>2=All Budget Lines" +
        "<br/>3-Infinity=Specify the exact number of Budget Lines to Export<br/>" +
        "</p>" +
        '<div class="form-group" style="margin-bottom:10px !important;">' +
        '<div class="input-group">' +
        '<span class="input-group-addon" id="basic-addon1">' +
        '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
        '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus>' +
        "</div>" +
        "</div>" +
        '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
        "</p>" +
        "</form>";

    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: "Export Budget Lines!",
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) { },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton("btn_exprt_rpt");
            $("#recsToExprt").focus();
        },
        buttons: [
            {
                label: "Cancel",
                icon: "glyphicon glyphicon-menu-left",
                cssClass: "btn-default",
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    window.clearInterval(prgstimerid2);
                    ClearAllIntervals();
                },
            },
            {
                id: "btn_exprt_rpt",
                label: "Export",
                icon: "glyphicon glyphicon-menu-right",
                cssClass: "btn-primary",
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $("#recsToExprt").val();
                    if (!isNumber(inptNum)) {
                        var dialog = bootbox.alert({
                            title: "Exporting Budget Lines",
                            size: "small",
                            message: "Please provide a valid Number!",
                            callback: function () { },
                        });
                        return false;
                    } else {
                        var $button = this;
                        $button.disable();
                        $button.spin();
                        dialogItself.setClosable(false);
                        document.getElementById("msgAreaExprt").innerHTML =
                            '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src=\'cmn_images/ajax-loader2.gif\'/><br/><span style="color:blue;font-size:11px;text-align: left;margin-top:0px;">Working on Export...Please Wait...</span>';
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 6,
                                    q: "UPDATE",
                                    actyp: 903,
                                    inptNum: inptNum,
                                    accbSbmtdBudgetID: accbSbmtdBudgetID,
                                },
                            });
                            prgstimerid2 = window.setInterval(rfrshAccntBdgtsPrcs,1000);
                        });
                    }
                },
            },
        ],
    });
}

function rfrshAccntBdgtsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 6,
            q: "UPDATE",
            actyp: 904,
        },
        success: function (data) {
            if (data.percent >= 100) {
                if (data.message.indexOf("Error") !== -1) {
                    $("#msgAreaExprt").html(data.message);
                } else {
                    $("#msgAreaExprt").html(
                        data.message +
                        '<br/><a href="' +
                        data.dwnld_url +
                        '">Click to Download File!</a>'
                    );
                }
                exprtBtn.enable();
                exprtBtn.stopSpin();
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            } else {
                $("#msgAreaExprt").html(
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message
                );
                document.getElementById("msgAreaExprt").innerHTML =
                    '<img style="width:165px;height:20px;display:inline;float:left;margin-left:3px;margin-right:3px;margin-top:-2px;clear: left;" src="cmn_images/ajax-loader2.gif"/>' +
                    data.message;
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

function importAccntBdgts() {
    var dataToSend = "";
    var isFileValid = true;
    var dialog1 = bootbox.confirm({
        title: "Import Budget Lines?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT BUDGET LINES</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                if (isReaderAPIAvlbl()) {
                    $("#allOtherFileInput6").val("");
                    $("#allOtherFileInput6").off("change");
                    $("#allOtherFileInput6").change(function () {
                        var fileName = $(this).val();
                        var input = document.getElementById("allOtherFileInput6");
                        var file = input.files[0];
                        /* read the file metadata*/
                        var output = "";
                        output +=
                            '<span style="font-weight:bold;">' +
                            escape(file.name) +
                            "</span><br />\n";
                        output += " - FileType: " + (file.type || "n/a") + "<br />\n";
                        output += " - FileSize: " + file.size + " bytes<br />\n";
                        output +=
                            " - LastModified: " +
                            (file.lastModifiedDate
                                ? file.lastModifiedDate.toLocaleDateString()
                                : "n/a") +
                            "<br />\n";

                        var reader = new FileReader();
                        BootstrapDialog.show({
                            size: BootstrapDialog.SIZE_LARGE,
                            type: BootstrapDialog.TYPE_DEFAULT,
                            title: "Validating Selected File",
                            message:
                                '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Validating Selected File...Please Wait...</div><br/><div id="fileInformation">' +
                                output +
                                "</div>",
                            animate: true,
                            closable: true,
                            closeByBackdrop: false,
                            closeByKeyboard: false,
                            onshow: function (dialogItself) {
                                setTimeout(function () {
                                    var $footerButton = dialogItself.getButton("btn-srvr-prcs");
                                    $footerButton.disable();
                                    /* read the file content*/
                                    reader.onerror = function (evt) {
                                        switch (evt.target.error.code) {
                                            case evt.target.error.NOT_FOUND_ERR:
                                                alert("File Not Found!");
                                                break;
                                            case evt.target.error.NOT_READABLE_ERR:
                                                alert("File is not readable");
                                                break;
                                            case evt.target.error.ABORT_ERR:
                                                break; 
                                            default:
                                                alert("An error occurred reading this file.");
                                        }
                                    };
                                    reader.onprogress = function (evt) {
                                        if (evt.lengthComputable) {
                                            var percentLoaded = Math.round(
                                                (evt.loaded / evt.total) * 100
                                            );
                                            /* Increase the progress bar length.*/
                                            var elem = document.getElementById("myBar");
                                            elem.style.width = percentLoaded + "%";
                                            if (percentLoaded < 100) {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File...Please Wait...</span>"
                                                );
                                            } else {
                                                $("#myInformation").html(
                                                    '<span style="color:green;"><i class="fa fa-check"></i>' +
                                                    percentLoaded +
                                                    "% Validating Selected File Completed!</span>"
                                                );

                                                var $footerButton = dialogItself.getButton(
                                                    "btn-srvr-prcs"
                                                );
                                                if (isFileValid == true) {
                                                    $footerButton.enable();
                                                } else {
                                                    $footerButton.disable();
                                                }
                                            }
                                        }
                                    };
                                    reader.onabort = function (e) {
                                        alert("File read cancelled");
                                    };
                                    reader.onloadstart = function (e) {
                                        var elem = document.getElementById("myBar");
                                        elem.style.width = "1%";
                                        $("#myInformation").html(
                                            '<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing Budget Lines...Please Wait...</span>'
                                        );
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var csv = event.target.result;
                                            var data = $.csv.toArrays(csv);
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            var accntnumber = "";
                                            var accntName = "";
                                            var bdgtCrncyCode = "";
                                            var actnIfExceeded = "";
                                            var amnt1Value = "";
                                            var amnt2Value = "";
                                            var amnt3Value = "";
                                            var amnt4Value = "";
                                            var amnt5Value = "";
                                            var amnt6Value = "";
                                            var amnt7Value = "";
                                            var amnt8Value = "";
                                            var amnt9Value = "";
                                            var amnt10Value = "";
                                            var amnt11Value = "";
                                            var amnt12Value = "";
                                            for (var row in data) {
                                                for (var item in data[row]) {
                                                    colCntr++;
                                                    switch (colCntr) {
                                                        case 1:
                                                            accntnumber = data[row][item];
                                                            break;
                                                        case 2:
                                                            accntName = data[row][item];
                                                            break;
                                                        case 3:
                                                            bdgtCrncyCode = data[row][item];
                                                            break;
                                                        case 4:
                                                            actnIfExceeded = data[row][item];
                                                            break;
                                                        case 5:
                                                            amnt1Value = data[row][item];
                                                            break;
                                                        case 6:
                                                            amnt2Value = data[row][item];
                                                            break;
                                                        case 7:
                                                            amnt3Value = data[row][item];
                                                            break;
                                                        case 8:
                                                            amnt4Value = data[row][item];
                                                            break;
                                                        case 9:
                                                            amnt5Value = data[row][item];
                                                            break;
                                                        case 10:
                                                            amnt6Value = data[row][item];
                                                            break;
                                                        case 11:
                                                            amnt7Value = data[row][item];
                                                            break;
                                                        case 12:
                                                            amnt8Value = data[row][item];
                                                            break;
                                                        case 13:
                                                            amnt9Value = data[row][item];
                                                            break;
                                                        case 14:
                                                            amnt10Value = data[row][item];
                                                            break;
                                                        case 15:
                                                            amnt11Value = data[row][item];
                                                            break;
                                                        case 16:
                                                            amnt12Value = data[row][item];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                title: "Error-Validating Selected File",
                                                                size: "small",
                                                                message:
                                                                    '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                },
                                                            });
                                                    }
                                                }
                                                if (rwCntr === 0) {
                                                    if (
                                                        accntnumber.toUpperCase() ===
                                                        "Account Number**".toUpperCase() &&
                                                        accntName.toUpperCase() ===
                                                        "Account Name**".toUpperCase() &&
                                                        bdgtCrncyCode.toUpperCase() ===
                                                        "Budget Currency Code**".toUpperCase() &&
                                                        actnIfExceeded.toUpperCase() ===
                                                        "Action if Exceeded**".toUpperCase()
                                                    ) {
                                                        /*alert(number.toUpperCase() + "|" + processName.toUpperCase() + "|" + isEnbld.toUpperCase());*/
                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            title: "Error-Import Budget Lines",
                                                            size: "small",
                                                            message:
                                                                '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            },
                                                        });
                                                    }
                                                }
                                                if (
                                                    accntnumber.trim() !== "" &&
                                                    accntName.trim() !== "" &&
                                                    bdgtCrncyCode.trim() !== "" &&
                                                    amnt4Value.trim() !== ""
                                                ) {
                                                    dataToSend =
                                                        dataToSend +
                                                        accntnumber
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        accntName
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        bdgtCrncyCode
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        actnIfExceeded
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt1Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt2Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt3Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt4Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt5Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt6Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt7Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt8Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt9Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt10Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt11Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "~" +
                                                        amnt12Value
                                                            .replace(/(~)/g,"{-;-;}")
                                                            .replace(/(\|)/g,"{:;:;}") +
                                                        "|";
                                                    vldRwCntr++;
                                                }
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            output +=
                                                '<br/><span style="color:blue;font-weight:bold;">No. of Valid Rows:' +
                                                vldRwCntr;
                                            output += "<br/>Total No. of Rows:" + rwCntr + "</span>";
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import Budget Lines",
                                                size: "small",
                                                message: "Error+NEW:" + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                },
                                            });
                                        }
                                    };
                                    reader.readAsText(file);
                                },500);
                            },
                            buttons: [
                                {
                                    label: "Cancel",
                                    icon: "glyphicon glyphicon-menu-left",
                                    cssClass: "btn-default",
                                    action: function (dialogItself) {
                                        isFileValid = false;
                                        reader.abort();
                                        dialogItself.close();
                                    },
                                },
                                {
                                    id: "btn-srvr-prcs",
                                    label: "Start Server Processing",
                                    icon: "glyphicon glyphicon-menu-right",
                                    cssClass: "btn-primary",
                                    action: function (dialogItself) {
                                        if (isFileValid == true) {
                                            dialogItself.close();
                                            saveAccntBdgtsExcl(dataToSend);
                                        } else {
                                            var dialog = bootbox.alert({
                                                title: "Error-Import Accounts",
                                                size: "small",
                                                message:
                                                    '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                callback: function () { },
                                            });
                                        }
                                    },
                                },
                            ],
                        });
                    });
                    performFileClick("allOtherFileInput6");
                }
            }
        },
    });
}

function saveAccntBdgtsExcl(dataToSend) {
    var accbSbmtdBudgetID =
        typeof $("#accbSbmtdBudgetID").val() === "undefined"
            ? "-1"
            : $("#accbSbmtdBudgetID").val();

    if (dataToSend.trim() === "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message:
                '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">No Data to Send!</span></p>',
        });
        return false;
    }

    var dialog = bootbox.alert({
        title: "Importing Accounts",
        size: "small",
        message:
            '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1"><i class="fa fa-spin fa-spinner"></i> Importing Budget Lines...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid2);
            window.clearInterval(prgstimerid2);
            getAccbBdgtDets("","#accbBdgtsDetList","grp=6&typ=1&pg=6&vtyp=1");
            ClearAllIntervals();
        },
    });

    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                method: "POST",
                url: "index.php",
                data: {
                    grp: 6,
                    typ: 1,
                    pg: 6,
                    q: "UPDATE",
                    actyp: 901,
                    accbSbmtdBudgetID: accbSbmtdBudgetID,
                    dataToSend: dataToSend,
                },
            });
            prgstimerid2 = window.setInterval(rfrshSaveAccntBdgts,1000);
        });
    });
}

function rfrshSaveAccntBdgts() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 6,
            typ: 1,
            pg: 6,
            q: "UPDATE",
            actyp: 902,
        },
        success: function (data) {
            var elem = document.getElementById("myBar1");
            elem.style.width = data.percent + "%";
            $("#myInformation1").html(data.message);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
                window.clearInterval(prgstimerid2);
                ClearAllIntervals();
            }
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        },
    });
}

/*Investment Transactions - Fund Management*/
function getPayInvstTrans(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#payInvstTransSrchFor").val() === "undefined"
            ? "%"
            : $("#payInvstTransSrchFor").val();
    var srchIn =
        typeof $("#payInvstTransSrchIn").val() === "undefined"
            ? "Both"
            : $("#payInvstTransSrchIn").val();
    var pageNo =
        typeof $("#payInvstTransPageNo").val() === "undefined"
            ? 1
            : $("#payInvstTransPageNo").val();
    var limitSze =
        typeof $("#payInvstTransDsplySze").val() === "undefined"
            ? 10
            : $("#payInvstTransDsplySze").val();
    var sortBy =
        typeof $("#payInvstTransSortBy").val() === "undefined"
            ? ""
            : $("#payInvstTransSortBy").val();
    var qShwUsrOnly = $("#payInvstTransShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#payInvstTransShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayInvstTrans(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayInvstTrans(actionText,slctr,linkArgs);
    }
}

function getOnePayInvstTransForm(pKeyID,vwtype,actionTxt) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=70&vtyp=" + vwtype + "&sbmtdPayInvstTransID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalNrml",
        actionTxt,
        "Fund Management Voucher Details (ID:" + pKeyID + ")",
        "myFormsModalNrmlTitle",
        "myFormsModalNrmlBody",
        function () {
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalNrml").css("overflow","auto");
                });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLg").css("overflow","auto");
                });
            $("#allOtherInputData99").val("0");
            $("#onePayInvstTransEDTForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#myFormsModalNrml").off("hidden.bs.modal");
            $("#myFormsModalNrml").one("hidden.bs.modal",function (e) {
                getPayInvstTrans("","#allmodules","grp=6&typ=1&pg=70&vtyp=0");
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
        }
    );
}

function getOnePInvstRedeemForm(pKeyID,vwtype,actionTxt) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=70&vtyp=" + vwtype + "&sbmtdPayInvstTransID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        actionTxt,
        "Investment Redemption Details (ID:" + pKeyID + ")",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModaly").css("overflow","auto");
                });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModaly").css("overflow","auto");
                });
            $("#allOtherInputData99").val("0");
            $("#onePayInvstTransEDTForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#myFormsModaly").off("hidden.bs.modal");
            $("#myFormsModaly").one("hidden.bs.modal",function (e) {
                getOnePayInvstTransForm(pKeyID,1,"ReloadDialog");
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
        }
    );
}
/*INVESTMENT CALCULATOR*/
function getOnePayInvstCalcForm(pKeyID,vwtype) {
    var lnkArgs =
        "grp=6&typ=1&pg=70&vtyp=" + vwtype + "&sbmtdPayInvstTransID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        "ShowDialog",
        "Investment Calculator",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
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
                        if (e.keyCode == 8) startTypingTimer($(e.target));
                    });
                    $(field).on("input paste",function (e) {
                        startTypingTimer($(e.target));
                    });
                };
                $("#billrate").on("change",function (e) {
                    var rate = Number($("#billrate").val()) * 100;
                    $("#erate").val(rate);
                    calculate_bill();
                });
                $("#rate").on("change",function (e) {
                    $("#erate").val("");
                    calculate();
                });

                function startTypingTimer(field_input) {
                    if (typingTimeout != undefined) clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(function () {
                        eval(field_input.attr("onfinishinput"));
                    },200);
                }

                function replaceAll(find,replace,str) {
                    return str.replace(new RegExp(find,"g"),replace);
                }

                function numberWithCommas(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",");
                }

                function calculate_bill() {
                    var principal = $("input#principal").val();
                    principal = replaceAll(",","",principal);
                    principal = parseFloat(principal);
                    if (!principal) return false;

                    var rate = parseFloat($("input#erate").val()) || "";
                    if (rate == "") {
                        rate = $("select#billrate").val();
                    } else {
                        rate = parseFloat(rate) / 100.0;
                    }

                    $(".total-91").text("GHS " + fv_tbill(rate,1,principal,1));
                    $(".total-180").text("GHS " + fv_tbill(rate,2,principal,1));
                    $(".total-360").text("GHS " + fv_tbill(rate,4,principal,1));
                    $(".total-720").text("GHS " + fv_tbill(rate,8,principal,1));
                    $(".total-1080").text("GHS " + fv_tbill(rate,12,principal,1));
                    $(".total-1800").text("GHS " + fv_tbill(rate,20,principal,1));
                    $(".total-7200").text("GHS " + fv_tbill(rate,80,principal,1));
                }

                function calculate() {
                    var principal = $("input#principal").val();
                    principal = replaceAll(",","",principal);
                    principal = parseFloat(principal);
                    if (!principal) return false;

                    var rate = parseFloat($("input#erate").val()) || "";
                    if (rate == "") {
                        rate = $("select#rate").val();
                    } else {
                        rate = parseFloat(rate) / 100.0;
                    }
                    $(".total-91").text("GHS " + fv_a(rate,1,0,principal,1));
                    $(".total-180").text("GHS " + fv_a(rate,2,0,principal,1));
                    $(".total-360").text("GHS " + fv_a(rate,4,0,principal,1));
                    $(".total-720").text("GHS " + fv_a(rate,8,0,principal,1));
                    $(".total-1080").text("GHS " + fv_a(rate,12,0,principal,1));
                    $(".total-1800").text("GHS " + fv_a(rate,20,0,principal,1));
                    $(".total-7200").text("GHS " + fv_a(rate,80,principal,1));
                }

                function fv_a(rate,nper,pmt,pv,type) {
                    var fv = pv * Math.pow(1 + rate / 4,nper);
                    return numberWithCommas(fv.toFixed(2));
                }

                function fv_tbill(rate,nper,pv,type) {
                    var pmt = 0;
                    var rate = Math.pow(1 + rate,1 / 4) - 1;
                    var pv = "-" + pv;
                    var pow = Math.pow(1 + rate,nper),
                        fv;
                    if (rate) {
                        fv = (pmt * (1 + rate * type) * (1 - pow)) / rate - pv * pow;
                    } else {
                        fv = -1 * (pv + pmt * nper);
                    }
                    return numberWithCommas(fv.toFixed(2));
                }

                function fv(pv,r,n,nper) {
                    r = r / 100;
                    return (((r / nper) * pv) / n + parseFloat(pv)).toFixed(3);
                }
            })(jQuery);
            $("input#principal").detect("calculate");
            $("input#erate").detect("calculate");
            $("#attchdInvstTransDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function getOnePayInvstTransDocsForm(pKeyID,vwtype,sbmtdPayDocTyp) {
    if (typeof sbmtdPayDocTyp === "undefined" || sbmtdPayDocTyp === null) {
        sbmtdPayDocTyp = "INVESTMENT";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=70&vtyp=" +
        vwtype +
        "&sbmtdPayInvstTransID=" +
        pKeyID +
        "&sbmtdPayDocTyp=" +
        sbmtdPayDocTyp;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModaly",
        "ShowDialog",
        "Fund Management Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            var table1 = $("#attchdInvstTransDocsTable").DataTable({
                paging: false,
                ordering: false,
                info: false,
                bFilter: false,
                scrollX: false,
            });
            $("#attchdInvstTransDocsTable").wrap('<div class="dataTables_scroll"/>');
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdInvstTransDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function uploadFileToInvstTransDocs(
    inptElmntID,
    attchIDElmntID,
    docNmElmntID,
    sbmtdHdrID,
    rowIDAttrb,
    sbmtdPayDocTyp
) {
    var docCtrgrName = $("#" + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Doc. Name/Description cannot be empty!</span></p>';
    }
    if (sbmtdHdrID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Attachments must be done on a saved Document/Transaction!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    $("#" + inptElmntID).val("");
    $("#" + inptElmntID).off("change");
    $("#" + inptElmntID).change(function () {
        var fileName = $(this).val();
        var input = document.getElementById(inptElmntID);
        sendFileToInvstTransDocs(
            input.files[0],
            docNmElmntID,
            attchIDElmntID,
            sbmtdHdrID,
            sbmtdPayDocTyp,
            function (data) {
                $("#" + attchIDElmntID).val(data.attchID);
                var dialog = bootbox.alert({
                    title: "Server Response!",
                    size: "small",
                    message: '<div id="myInformation">' + data.message + "</div>",
                    callback: function () {
                        if (data.message.indexOf("Success") !== -1) {
                        }
                    },
                });
            }
        );
    });
    performFileClick(inptElmntID);
}

function sendFileToInvstTransDocs(
    file,
    docNmElmntID,
    attchIDElmntID,
    sbmtdHdrID,
    sbmtdPayDocTyp,
    callBackFunc
) {
    var data1 = new FormData();
    data1.append("daInvstTransAttchmnt",file);
    data1.append("grp",6);
    data1.append("typ",1);
    data1.append("pg",70);
    data1.append("q","UPDATE");
    data1.append("actyp",2);
    data1.append("docCtrgrName",$("#" + docNmElmntID).val());
    data1.append("attchmentID",$("#" + attchIDElmntID).val());
    data1.append("sbmtdPayInvstTransID",sbmtdHdrID);
    data1.append("sbmtdPayDocTyp",sbmtdPayDocTyp);
    var dialog1 = bootbox.alert({
        title: "Uploading File...",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Uploading File...Please Wait...</p>',
    });
    dialog1.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                type: "POST",
                data: data1,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    dialog1.modal("hide");
                    callBackFunc(data);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function getAttchdInvstTransDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var srchFor =
        typeof $("#attchdInvstTransDocsSrchFor").val() === "undefined"
            ? "%"
            : $("#attchdInvstTransDocsSrchFor").val();
    var srchIn =
        typeof $("#attchdInvstTransDocsSrchIn").val() === "undefined"
            ? "Both"
            : $("#attchdInvstTransDocsSrchIn").val();
    var pageNo =
        typeof $("#attchdInvstTransDocsPageNo").val() === "undefined"
            ? 1
            : $("#attchdInvstTransDocsPageNo").val();
    var limitSze =
        typeof $("#attchdInvstTransDocsDsplySze").val() === "undefined"
            ? 10
            : $("#attchdInvstTransDocsDsplySze").val();
    var sortBy =
        typeof $("#attchdInvstTransDocsSortBy").val() === "undefined"
            ? ""
            : $("#attchdInvstTransDocsSortBy").val();
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy;
    doAjaxWthCallBck(
        linkArgs,
        "myFormsModaly",
        actionDialog,
        "Fund Management Attached Documents",
        "myFormsModalyTitle",
        "myFormsModalyBody",
        function () {
            if (!$.fn.DataTable.isDataTable("#attchdInvstTransDocsTable")) {
                var table1 = $("#attchdInvstTransDocsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#attchdInvstTransDocsTable").wrap(
                    '<div class="dataTables_scroll"/>'
                );
            }
            $('[data-toggle="tooltip"]').tooltip();
            $("#attchdInvstTransDocsTblForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
        }
    );
}

function enterKeyFuncAttchdInvstTransDocs(
    e,
    actionText,
    slctr,
    linkArgs,
    actionDialog
) {
    if (typeof actionDialog === "undefined" || actionDialog === null) {
        actionDialog = "ShowDialog";
    }
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdInvstTransDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdInvstTransDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID =
        typeof $("#sbmtdPayInvstTransID").val() === "undefined"
            ? -1
            : $("#sbmtdPayInvstTransID").val();
    var docNum =
        typeof $("#payInvstTransDocNum").val() === "undefined"
            ? ""
            : $("#payInvstTransDocNum").val();
    var pKeyID = -1;
    if (
        typeof $("#attchdInvstTransDocsRow" + rndmNum + "_AttchdDocsID").val() ===
        "undefined"
    ) {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $("#attchdInvstTransDocsRow" + rndmNum + "_AttchdDocsID").val();
    }
    var dialog = bootbox.confirm({
        title: "Delete Document?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Document?<br/>Action cannot be Undone!</p>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete Document?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting Document...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 70,
                                    q: "DELETE",
                                    actyp: 5,
                                    attchmentID: pKeyID,
                                    sbmtdHdrID: sbmtdHdrID,
                                    docTrnsNum: docNum,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delPayInvstTrans(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Fund Management";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 70,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function savePayInvstTransForm(funcur,shdSbmt) {
    var sbmtdPayInvstTransID =
        typeof $("#sbmtdPayInvstTransID").val() === "undefined"
            ? -1
            : $("#sbmtdPayInvstTransID").val();
    var payInvstTrnsType =
        typeof $("#payInvstTrnsType").val() === "undefined"
            ? ""
            : $("#payInvstTrnsType").val();
    var payInvstItemType =
        typeof $("#payInvstItemType").val() === "undefined"
            ? ""
            : $("#payInvstItemType").text();
    var payInvstItemTypID =
        typeof $("#payInvstItemType").val() === "undefined"
            ? "-1"
            : $("#payInvstItemType").val();
    var payInvstRollOvrType =
        typeof $("#payInvstRollOvrType").val() === "undefined"
            ? ""
            : $("#payInvstRollOvrType").val();
    var payInvstPrchsDte =
        typeof $("#payInvstPrchsDte").val() === "undefined"
            ? ""
            : $("#payInvstPrchsDte").val();
    var payInvstMatureDte =
        typeof $("#payInvstMatureDte").val() === "undefined"
            ? ""
            : $("#payInvstMatureDte").val();
    var payInvstTransDesc =
        typeof $("#payInvstTransDesc").val() === "undefined"
            ? ""
            : $("#payInvstTransDesc").val();
    var payInvstRefNum =
        typeof $("#payInvstRefNum").val() === "undefined"
            ? ""
            : $("#payInvstRefNum").val();
    var payInvstTransInvcCur =
        typeof $("#payInvstTransInvcCur").val() === "undefined"
            ? ""
            : $("#payInvstTransInvcCur").val();
    var payInvstPrchsAmnt =
        typeof $("#payInvstPrchsAmnt").val() === "undefined"
            ? "0.00"
            : $("#payInvstPrchsAmnt").val();
    var payInvstMatureAmnt =
        typeof $("#payInvstMatureAmnt").val() === "undefined"
            ? "0.00"
            : $("#payInvstMatureAmnt").val();
    var payInvstIntrstRate =
        typeof $("#payInvstIntrstRate").val() === "undefined"
            ? "0.00"
            : $("#payInvstIntrstRate").val();
    var payInvstExchngRate =
        typeof $("#payInvstExchngRate").val() === "undefined"
            ? "0.00"
            : $("#payInvstExchngRate").val();
    var payInvstClientID =
        typeof $("#payInvstClientID").val() === "undefined"
            ? ""
            : $("#payInvstClientID").val();
    var errMsg = "";
    if (payInvstTrnsType.trim() === "" || payInvstItemType.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction and Item Type cannot be empty!</span></p>';
    }
    if (payInvstTransDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Narration cannot be empty!</span></p>';
    }
    if (payInvstRefNum.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reference Number cannot be empty!</span></p>';
    }
    if (payInvstPrchsDte.trim() === "" || payInvstTransInvcCur.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Purchase Date/Currency cannot be empty!</span></p>';
    }
    payInvstPrchsAmnt = fmtAsNumber("payInvstPrchsAmnt").toFixed(2);
    payInvstMatureAmnt = fmtAsNumber("payInvstMatureAmnt").toFixed(2);
    payInvstIntrstRate = fmtAsNumber("payInvstIntrstRate").toFixed(2);
    payInvstExchngRate = fmtAsNumber2("payInvstExchngRate").toFixed(4);
    if (payInvstPrchsAmnt <= 0 && payInvstMatureAmnt <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Purchase and Maturity Amounts cannot be both zero or less at the same time!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Fund Management",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Fund Management...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",70);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("sbmtdPayInvstTransID",sbmtdPayInvstTransID);
    formData.append("payInvstTrnsType",payInvstTrnsType);
    formData.append("payInvstItemType",payInvstItemType);
    formData.append("payInvstItemTypID",payInvstItemTypID);
    formData.append("payInvstTransDesc",payInvstTransDesc);
    formData.append("payInvstRollOvrType",payInvstRollOvrType);
    formData.append("payInvstPrchsDte",payInvstPrchsDte);
    formData.append("payInvstMatureDte",payInvstMatureDte);
    formData.append("payInvstRefNum",payInvstRefNum);
    formData.append("payInvstTransInvcCur",payInvstTransInvcCur);
    formData.append("payInvstPrchsAmnt",payInvstPrchsAmnt);
    formData.append("payInvstMatureAmnt",payInvstMatureAmnt);
    formData.append("payInvstIntrstRate",payInvstIntrstRate);
    formData.append("payInvstExchngRate",payInvstExchngRate);
    formData.append("payInvstClientID",payInvstClientID);
    formData.append("shdSbmt",shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPayInvstTransID = data.sbmtdPayInvstTransID;
                            getOnePayInvstTransForm(sbmtdPayInvstTransID,1,"ReloadDialog");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function savePInvstRedeemForm(funcur,shdSbmt) {
    var sbmtdPayInvstTransID =
        typeof $("#sbmtdPayInvstTransID2").val() === "undefined"
            ? -1
            : $("#sbmtdPayInvstTransID2").val();
    var payInvstRollOvrType =
        typeof $("#payInvstRollOvrType2").val() === "undefined"
            ? ""
            : $("#payInvstRollOvrType2").val();
    var payInvstMatureDte =
        typeof $("#payInvstMatureDte2").val() === "undefined"
            ? ""
            : $("#payInvstMatureDte2").val();
    var payInvstTransDesc =
        typeof $("#payInvstTransDesc2").val() === "undefined"
            ? ""
            : $("#payInvstTransDesc2").val();
    var payOLDMatureAmnt =
        typeof $("#payOLDMatureAmnt2").val() === "undefined"
            ? "0.00"
            : $("#payOLDMatureAmnt2").val();
    var payInvstMatureAmnt =
        typeof $("#payInvstMatureAmnt2").val() === "undefined"
            ? "0.00"
            : $("#payInvstMatureAmnt2").val();
    var errMsg = "";
    if (payInvstMatureDte.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Marurity Date cannot be empty!</span></p>';
    }
    payInvstMatureAmnt = fmtAsNumber("payInvstMatureAmnt2").toFixed(2);
    if (payInvstMatureAmnt <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Maturity Amount cannot be zero or less!</span></p>';
    }
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Investment Redemption",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Investment Redemption...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",70);
    formData.append("q","UPDATE");
    formData.append("actyp",5);
    formData.append("sbmtdPayInvstTransID",sbmtdPayInvstTransID);
    formData.append("payInvstTransDesc",payInvstTransDesc);
    formData.append("payInvstRollOvrType",payInvstRollOvrType);
    formData.append("payInvstMatureDte",payInvstMatureDte);
    formData.append("payInvstMatureAmnt",payInvstMatureAmnt);
    formData.append("payOLDMatureAmnt",payOLDMatureAmnt);
    formData.append("shdSbmt",shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPayInvstTransID = data.sbmtdPayInvstTransID;
                            getOnePayInvstTransForm(sbmtdPayInvstTransID,1,"ReloadDialog");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
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
    var sbmtdPayInvstTransID =
        typeof $("#sbmtdPayInvstTransID").val() === "undefined"
            ? -1
            : $("#sbmtdPayInvstTransID").val();
    var payInvstTransDesc =
        typeof $("#payInvstTransDesc").val() === "undefined"
            ? ""
            : $("#payInvstTransDesc").val();
    var payInvstTransDesc1 =
        typeof $("#payInvstTransDesc1").val() === "undefined"
            ? ""
            : $("#payInvstTransDesc1").val();
    var errMsg = "";
    if (sbmtdPayInvstTransID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (payInvstTransDesc === "" || payInvstTransDesc === payInvstTransDesc1) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#payInvstTransDesc").addClass("rho-error");
        $("#payInvstTransDesc").attr("readonly",false);
        $("#fnlzeRvrslPayInvstTransBtn").attr("disabled",false);
    } else {
        $("#payInvstTransDesc").removeClass("rho-error");
    }
    if (rhotrim(errMsg,"; ") !== "") {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var msgsTitle = "Fund Management Voucher";
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' +
            msgsTitle +
            "?<br/>This action cannot be UNDONE!</p>";
    } else {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">INITIATE REVERSAL</span> of this ' +
            msgsTitle +
            '?<br/>After submission you must come back here to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE the REVERSAL</span>!</p>';
    }
    var dialog = bootbox.confirm({
        title: "Void " + msgsTitle + "?",
        size: "small",
        message: msgBody,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var msg = "Fund Management Voucher";
                var dialog = bootbox.alert({
                    title: "Save " + msg,
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
                        msg +
                        "...Please Wait...</p>",
                    callback: function () {
                        sbmtdPayInvstTransID =
                            typeof $("#sbmtdPayInvstTransID").val() === "undefined"
                                ? -1
                                : $("#sbmtdPayInvstTransID").val();
                        if (sbmtdPayInvstTransID > 0) {
                            getOnePayInvstTransForm(sbmtdPayInvstTransID,1,"ReloadDialog");
                        }
                    },
                });
                dialog.init(function () {
                    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 6,
                                typ: 1,
                                pg: 70,
                                actyp: 1,
                                q: "VOID",
                                payInvstTransDesc: payInvstTransDesc,
                                sbmtdPayInvstTransID: sbmtdPayInvstTransID,
                                shdSbmt: shdSbmt,
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (
                                    /^[\],:{}\s]*$/.test(
                                        data
                                            .replace(/\\["\\\/bfnrtu]/g,"@")
                                            .replace(
                                                /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                                                "]"
                                            )
                                            .replace(/(?:^|:|,)(?:\s*\[)+/g,"")
                                    )
                                ) {
                                    obj = $.parseJSON(data);
                                    sbmtdPayInvstTransID = obj.sbmtdPayInvstTransID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdPayInvstTransID > 0) {
                                        $("#sbmtdPayInvstTransID").val(sbmtdPayInvstTransID);
                                    }
                                    if (msg.trim() === "") {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find(".bootbox-body").html(msg);
                                },500);
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            },
                        });
                    });
                });
            }
        },
    });
}
/*SIMPLIFIED VOUCHERS*/
function getSmplVchr(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#smplVchrSrchFor").val() === "undefined"
            ? "%"
            : $("#smplVchrSrchFor").val();
    var srchIn =
        typeof $("#smplVchrSrchIn").val() === "undefined"
            ? "Both"
            : $("#smplVchrSrchIn").val();
    var pageNo =
        typeof $("#smplVchrPageNo").val() === "undefined"
            ? 1
            : $("#smplVchrPageNo").val();
    var limitSze =
        typeof $("#smplVchrDsplySze").val() === "undefined"
            ? 10
            : $("#smplVchrDsplySze").val();
    var sortBy =
        typeof $("#smplVchrSortBy").val() === "undefined"
            ? ""
            : $("#smplVchrSortBy").val();
    var qShwUsrOnly = $("#smplVchrShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#smplVchrShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncSmplVchr(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getSmplVchr(actionText,slctr,linkArgs);
    }
}

function getOneSmplVchrForm(pKeyID,vwtype,actionTxt,inTmpltTyp) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=72&vtyp=" +
        vwtype +
        "&sbmtdSmplVchrID=" +
        pKeyID +
        "&inTmpltTyp=" +
        inTmpltTyp;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalNrml",
        actionTxt,
        "Simplified Voucher Details (ID:" + pKeyID + ")",
        "myFormsModalNrmlTitle",
        "myFormsModalNrmlBody",
        function () {
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalNrml").css("overflow","auto");
                });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalLg").css("overflow","auto");
                });
            $("#allOtherInputData99").val("0");
            $("#oneSmplVchrEDTForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#myFormsModalNrml").off("hidden.bs.modal");
            $("#myFormsModalNrml").one("hidden.bs.modal",function (e) {
                getSmplVchr(
                    "",
                    "#allmodules",
                    "grp=6&typ=1&pg=72&vtyp=0" + "&inTmpltTyp=" + inTmpltTyp
                );
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
        }
    );
}

function delSmplVchr(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Fund Management";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 72,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function saveSmplVchrForm(funcur,shdSbmt,inTmpltTyp) {
    var sbmtdSmplVchrID =
        typeof $("#sbmtdSmplVchrID").val() === "undefined"
            ? -1
            : $("#sbmtdSmplVchrID").val();
    var smplVchrTrnsType =
        typeof $("#smplVchrTrnsType").val() === "undefined"
            ? ""
            : $("#smplVchrTrnsType").val();
    var smplVchrTrnsNum =
        typeof $("#smplVchrTrnsNum").val() === "undefined"
            ? ""
            : $("#smplVchrTrnsNum").val();
    var smplVchrSpplrID =
        typeof $("#smplVchrSpplrID").val() === "undefined"
            ? "-1"
            : $("#smplVchrSpplrID").val();
    var smplVchrSpplrSiteID =
        typeof $("#smplVchrSpplrSiteID").val() === "undefined"
            ? "-1"
            : $("#smplVchrSpplrSiteID").val();
    var smplVchrMainTmpltID =
        typeof $("#smplVchrMainTmpltID").val() === "undefined"
            ? "-1"
            : $("#smplVchrMainTmpltID").val();
    var smplVchrMltplLines =
        typeof $("input[name='smplVchrMltplLines']:checked").val() === "undefined"
            ? "NO"
            : "YES";
    var smplVchrSpplrClsfctn =
        typeof $("#smplVchrSpplrClsfctn").val() === "undefined"
            ? ""
            : $("#smplVchrSpplrClsfctn").val();
    var smplVchrTransDte =
        typeof $("#smplVchrTransDte").val() === "undefined"
            ? ""
            : $("#smplVchrTransDte").val();
    var smplVchrDesc =
        typeof $("#smplVchrDesc").val() === "undefined"
            ? ""
            : $("#smplVchrDesc").val();
    var smplVchrRefNum =
        typeof $("#smplVchrRefNum").val() === "undefined"
            ? ""
            : $("#smplVchrRefNum").val();
    var smplVchrInvcCur =
        typeof $("#smplVchrInvcCur").val() === "undefined"
            ? ""
            : $("#smplVchrInvcCur").val();
    var smplVchrTransAmnt =
        typeof $("#smplVchrTransAmnt").val() === "undefined"
            ? "0.00"
            : $("#smplVchrTransAmnt").val();
    var smplVchrExchngRate =
        typeof $("#smplVchrExchngRate").val() === "undefined"
            ? "0.00"
            : $("#smplVchrExchngRate").val();
    var errMsg = "";
    if (smplVchrTrnsType.trim() === "" || smplVchrTrnsNum.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Type and Number cannot be empty!</span></p>';
    }
    if (smplVchrDesc.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Narration cannot be empty!</span></p>';
    }
    if (smplVchrTransDte.trim() === "" || smplVchrInvcCur.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Date/Currency cannot be empty!</span></p>';
    }
    if (smplVchrMltplLines == "NO" && Number(smplVchrMainTmpltID) <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Main Template cannot be empty when Voucher does not support Lines!</span></p>';
    }
    smplVchrTransAmnt = fmtAsNumber("smplVchrTransAmnt").toFixed(2);
    smplVchrExchngRate = fmtAsNumber2("smplVchrExchngRate").toFixed(4);
    if (smplVchrTransAmnt <= 0 && shdSbmt == 2) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cannot Finalize when Transaction Amount is zero or less!</span></p>';
    }
    var slctdTransLines = "";
    var isVld = true;
    $("#payTrnTypClsfctnsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var ln_LineID = $(
                        "#payTrnTypClsfctnsRow" + rndmNum + "_LineID"
                    ).val();
                    var ln_TmpltID = $(
                        "#payTrnTypClsfctnsRow" + rndmNum + "_TmpltID"
                    ).val();
                    var ln_Desc = $("#payTrnTypClsfctnsRow" + rndmNum + "_Desc").val();
                    var ln_Amount = $(
                        "#payTrnTypClsfctnsRow" + rndmNum + "_Amount"
                    ).val();
                    var ln_Date = $("#payTrnTypClsfctnsRow" + rndmNum + "_Date").val();
                    if (
                        Number(ln_TmpltID.replace(/[^-?0-9\.]/g,"")) > 0 &&
                        Number(ln_Amount.replace(/[^-?0-9\.]/g,"")) <= 0
                    ) {
                        $("#payTrnTypClsfctnsRow" + rndmNum + "_Amount").addClass(
                            "rho-error"
                        );
                    } else {
                        $("#payTrnTypClsfctnsRow" + rndmNum + "_Amount").removeClass(
                            "rho-error"
                        );
                        slctdTransLines =
                            slctdTransLines +
                            ln_LineID
                                .replace(/[^-?0-9\.]/g,"")
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_TmpltID
                                .replace(/[^-?0-9\.]/g,"")
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_Desc.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_Amount
                                .replace(/[^-?0-9\.]/g,"")
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            ln_Date.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "|";
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Transaction Voucher",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction Voucher...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",72);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("sbmtdSmplVchrID",sbmtdSmplVchrID);
    formData.append("smplVchrTrnsType",smplVchrTrnsType);
    formData.append("smplVchrTrnsNum",smplVchrTrnsNum);
    formData.append("smplVchrSpplrID",smplVchrSpplrID);
    formData.append("smplVchrSpplrSiteID",smplVchrSpplrSiteID);
    formData.append("smplVchrMainTmpltID",smplVchrMainTmpltID);
    formData.append("smplVchrMltplLines",smplVchrMltplLines);
    formData.append("smplVchrTransDte",smplVchrTransDte);
    formData.append("smplVchrSpplrClsfctn",smplVchrSpplrClsfctn);
    formData.append("smplVchrDesc",smplVchrDesc);
    formData.append("smplVchrRefNum",smplVchrRefNum);
    formData.append("smplVchrInvcCur",smplVchrInvcCur);
    formData.append("smplVchrTransAmnt",smplVchrTransAmnt);
    formData.append("smplVchrExchngRate",smplVchrExchngRate);
    formData.append("slctdTransLines",slctdTransLines);
    formData.append("inTmpltTyp",inTmpltTyp);
    formData.append("shdSbmt",shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdSmplVchrID = data.sbmtdSmplVchrID;
                            getOneSmplVchrForm(
                                sbmtdSmplVchrID,
                                1,
                                "ReloadDialog",
                                inTmpltTyp
                            );
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function saveSmplVchrRvrslForm(funcCur,shdSbmt,inTmpltTyp) {
    if (shdSbmt === 1) {
        disableBtnFunc("fnlzeRvrslSmplVchrBtn");
    }

    var obj;
    /** NEW **/
    $body = $("body");
    var sbmtdSmplVchrID =
        typeof $("#sbmtdSmplVchrID").val() === "undefined"
            ? -1
            : $("#sbmtdSmplVchrID").val();
    var smplVchrDesc =
        typeof $("#smplVchrDesc").val() === "undefined"
            ? ""
            : $("#smplVchrDesc").val();
    var smplVchrDesc1 =
        typeof $("#smplVchrDesc1").val() === "undefined"
            ? ""
            : $("#smplVchrDesc1").val();
    var errMsg = "";
    if (sbmtdSmplVchrID <= 0) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Invalid Document! Cannot Reverse</span></p>';
    }
    if (smplVchrDesc === "" || smplVchrDesc === smplVchrDesc1) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Reversal Reason is required!</span></p>';
        $("#smplVchrDesc").addClass("rho-error");
        $("#smplVchrDesc").attr("readonly",false);
        $("#fnlzeRvrslSmplVchrBtn").attr("disabled",false);
    } else {
        $("#smplVchrDesc").removeClass("rho-error");
    }
    if (rhotrim(errMsg,"; ") !== "") {
        $body.removeClass("mdlloadingDiag");
        bootbox.alert({
            title: "System Alert!",
            size: "small",
            message: errMsg,
        });
        return false;
    }
    var msgsTitle = "Transaction Voucher";
    var msgBody = "";
    if (shdSbmt > 0) {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE REVERSAL</span> of this ' +
            msgsTitle +
            "?<br/>This action cannot be UNDONE!</p>";
    } else {
        msgBody =
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">INITIATE REVERSAL</span> of this ' +
            msgsTitle +
            '?<br/>After submission you must come back here to <span style="color:red;font-weight:bold;font-style:italic;">FINALIZE the REVERSAL</span>!</p>';
    }
    var dialog = bootbox.confirm({
        title: "Void " + msgsTitle + "?",
        size: "small",
        message: msgBody,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var msg = "Transaction Voucher";
                var dialog = bootbox.alert({
                    title: "Save " + msg,
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Saving ' +
                        msg +
                        "...Please Wait...</p>",
                    callback: function () {
                        sbmtdSmplVchrID =
                            typeof $("#sbmtdSmplVchrID").val() === "undefined"
                                ? -1
                                : $("#sbmtdSmplVchrID").val();
                        if (sbmtdSmplVchrID > 0) {
                            getOneSmplVchrForm(
                                sbmtdSmplVchrID,
                                1,
                                "ReloadDialog",
                                inTmpltTyp
                            );
                        }
                    },
                });
                dialog.init(function () {
                    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                        $body = $("body");
                        $body.removeClass("mdlloading");
                        $.ajax({
                            method: "POST",
                            url: "index.php",
                            data: {
                                grp: 6,
                                typ: 1,
                                pg: 72,
                                actyp: 1,
                                q: "VOID",
                                smplVchrDesc: smplVchrDesc,
                                sbmtdSmplVchrID: sbmtdSmplVchrID,
                                shdSbmt: shdSbmt,
                                inTmpltTyp: inTmpltTyp,
                            },
                            success: function (result) {
                                var msg = "";
                                var data = result;
                                var p_sbmtdJrnlBatchID = -1;
                                if (
                                    /^[\],:{}\s]*$/.test(
                                        data
                                            .replace(/\\["\\\/bfnrtu]/g,"@")
                                            .replace(
                                                /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                                                "]"
                                            )
                                            .replace(/(?:^|:|,)(?:\s*\[)+/g,"")
                                    )
                                ) {
                                    obj = $.parseJSON(data);
                                    sbmtdSmplVchrID = obj.sbmtdSmplVchrID;
                                    msg = obj.sbmtMsg;
                                    if (sbmtdSmplVchrID > 0) {
                                        $("#sbmtdSmplVchrID").val(sbmtdSmplVchrID);
                                    }
                                    if (msg.trim() === "") {
                                        msg = "Transaction Reversal Created Successfully!";
                                    }
                                } else {
                                    msg = data;
                                }
                                setTimeout(function () {
                                    dialog.find(".bootbox-body").html(msg);
                                },500);
                            },
                            error: function (jqXHR,textStatus,errorThrown) {
                                /*dialog.find('.bootbox-body').html(errorThrown);*/
                                console.warn(jqXHR.responseText);
                            },
                        });
                    });
                });
            }
        },
    });
}

function delSmplVchrHdrLn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_LineID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_LineID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();
                     var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Transaction Line";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 72,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delSmplVchrHdr(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var msgPrt = "Transaction Voucher";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 72,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function afterVchrPayeeSlct() {
    var smplVchrSpplrID =
        typeof $("#smplVchrSpplrID").val() === "undefined"
            ? "-1"
            : $("#smplVchrSpplrID").val();
    var smplVchrSpplrSiteID =
        typeof $("#smplVchrSpplrSiteID").val() === "undefined"
            ? "-1"
            : $("#smplVchrSpplrSiteID").val();
    var smplVchrRefNum =
        typeof $("#smplVchrRefNum").val() === "undefined"
            ? ""
            : $("#smplVchrRefNum").val();
    getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append("grp",6);
        formData.append("typ",1);
        formData.append("pg",13);
        formData.append("q","VIEW");
        formData.append("vtyp",6);
        formData.append("smplVchrSpplrID",smplVchrSpplrID);
        $.ajax({
            url: "index.php",
            method: "POST",
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.message.indexOf("Success") !== -1) {
                    $("#smplVchrSpplrSiteID").val(data.smplVchrSpplrSiteID);
                    $("#smplVchrRefNum").val(data.smplVchrRefNum);
                }
            },
            error: function (jqXHR,textStatus,errorThrown) {
                console.warn(jqXHR.responseText);
            },
        });
    });
}

/*TRANSACTION TYPES*/
function getPayTransTyps(actionText,slctr,linkArgs) {
    var srchFor =
        typeof $("#payTransTypsSrchFor").val() === "undefined"
            ? "%"
            : $("#payTransTypsSrchFor").val();
    var srchIn =
        typeof $("#payTransTypsSrchIn").val() === "undefined"
            ? "Both"
            : $("#payTransTypsSrchIn").val();
    var pageNo =
        typeof $("#payTransTypsPageNo").val() === "undefined"
            ? 1
            : $("#payTransTypsPageNo").val();
    var limitSze =
        typeof $("#payTransTypsDsplySze").val() === "undefined"
            ? 10
            : $("#payTransTypsDsplySze").val();
    var sortBy =
        typeof $("#payTransTypsSortBy").val() === "undefined"
            ? ""
            : $("#payTransTypsSortBy").val();
    var qShwUsrOnly = $("#payTransTypsShwUsrOnly:checked").length > 0;
    var qShwUnpstdOnly = $("#payTransTypsShwUnpstdOnly:checked").length > 0;
    if (actionText == "clear") {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == "next") {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == "previous") {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs =
        linkArgs +
        "&searchfor=" +
        srchFor +
        "&searchin=" +
        srchIn +
        "&pageNo=" +
        pageNo +
        "&limitSze=" +
        limitSze +
        "&sortBy=" +
        sortBy +
        "&qShwUsrOnly=" +
        qShwUsrOnly +
        "&qShwUnpstdOnly=" +
        qShwUnpstdOnly;
    openATab(slctr,linkArgs);
}

function enterKeyFuncPayTransTyps(e,actionText,slctr,linkArgs) {
    var charCode = typeof e.which === "number" ? e.which : e.keyCode;
    if (charCode == 13) {
        getPayTransTyps(actionText,slctr,linkArgs);
    }
}

function getOnePayTransTypsForm(pKeyID,vwtype,actionTxt) {
    if (typeof actionTxt === "undefined" || actionTxt === null) {
        actionTxt = "ShowDialog";
    }
    var lnkArgs =
        "grp=6&typ=1&pg=71&vtyp=" + vwtype + "&sbmtdPayTransTypsID=" + pKeyID;
    doAjaxWthCallBck(
        lnkArgs,
        "myFormsModalxLG",
        actionTxt,
        "Transaction Types (ID:" + pKeyID + ")",
        "myFormsModalxLGTitle",
        "myFormsModalxLGBody",
        function () {
            $(".form_date_tme")
                .datetimepicker({
                    format: "dd-M-yyyy hh:ii:ss",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 0,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalxLG").css("overflow","auto");
                });
            $(".form_date")
                .datetimepicker({
                    format: "dd-M-yyyy",
                    language: "en",
                    weekStart: 0,
                    todayBtn: true,
                    autoclose: true,
                    todayHighlight: true,
                    keyboardNavigation: true,
                    startView: 2,
                    minView: 2,
                    maxView: 4,
                    forceParse: true,
                })
                .on("hide",function (ev) {
                    $("#myFormsModalxLG").css("overflow","auto");
                });
            if (!$.fn.DataTable.isDataTable("#payTrnTypClsfctnsTable")) {
                var table1 = $("#payTrnTypClsfctnsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#payTrnTypClsfctnsTable").wrap('<div class="dataTables_scroll"/>');
            }
            $("#allOtherInputData99").val("0");
            $("#onePayTransTypsEDTForm").submit(function (e) {
                e.preventDefault();
                return false;
            });
            $("#myFormsModalxLG").off("hidden.bs.modal");
            $("#myFormsModalxLG").one("hidden.bs.modal",function (e) {
                getPayTransTyps("","#allmodules","grp=6&typ=1&pg=71&vtyp=0");
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

            if (!$.fn.DataTable.isDataTable("#payTrnTypClsfctnsTable")) {
                var table1 = $("#payTrnTypClsfctnsTable").DataTable({
                    paging: false,
                    ordering: false,
                    info: false,
                    bFilter: false,
                    scrollX: false,
                });
                $("#payTrnTypClsfctnsTable").wrap('<div class="dataTables_scroll"/>');
            }
        }
    );
}

function shwHideTrnsTypsDivs() {
    var whtToDo =
        typeof $("#payTransTyp").val() === "undefined"
            ? "LOAN"
            : $("#payTransTyp").val();
    if (whtToDo === "LOAN" || whtToDo === "PAYMENT" || whtToDo === "SETTLEMENT") {
        $("#payTransIvstmntDiv").addClass("hideNotice");
        $("#payTransItemsDiv").removeClass("hideNotice");
        if (whtToDo === "SETTLEMENT") {
            $("#payTransItemLoansDiv").addClass("hideNotice");
            $("#payTransItemSettlDiv").removeClass("hideNotice");
        }
        if (whtToDo === "LOAN") {
            $("#payTransItemLoansDiv").removeClass("hideNotice");
            $("#payTransItemSettlDiv").addClass("hideNotice");
        }
    } else {
        $("#payTransItemsDiv").addClass("hideNotice");
        $("#payTransIvstmntDiv").removeClass("hideNotice");
    }
}

function shwHideIncExpVhcrDivs() {
    var whtToDo =
        typeof $("input[name='smplVchrMltplLines']:checked").val() === "undefined"
            ? "hide"
            : "show";
    if (whtToDo === "hide") {
        $("#payTransItemsDiv").addClass("hideNotice");
    } else {
        $("#payTransItemsDiv").removeClass("hideNotice");
    }
}

function savePayTransTypsForm(funcur,shdSbmt) {
    var sbmtdPayTransTypsID =
        typeof $("#sbmtdPayTransTypsID").val() === "undefined"
            ? "-1"
            : $("#sbmtdPayTransTypsID").val();
    var payTransTypsName =
        typeof $("#payTransTypsName").val() === "undefined"
            ? ""
            : $("#payTransTypsName").val();
    var payTransTypsDesc =
        typeof $("#payTransTypsDesc").val() === "undefined"
            ? ""
            : $("#payTransTypsDesc").val();
    var payTransTyp =
        typeof $("#payTransTyp").val() === "undefined"
            ? ""
            : $("#payTransTyp").val();
    var payTransPeriodTyp =
        typeof $("#payTransPeriodTyp").val() === "undefined"
            ? ""
            : $("#payTransPeriodTyp").val();
    var payTransTypsPrd =
        typeof $("#payTransTypsPrd").val() === "undefined"
            ? "0"
            : $("#payTransTypsPrd").val();
    var payTransTypsItmStID =
        typeof $("#payTransTypsItmStID").val() === "undefined"
            ? "-1"
            : $("#payTransTypsItmStID").val();
    var payTransTypsMnItmID =
        typeof $("#payTransTypsMnItmID").val() === "undefined"
            ? "-1"
            : $("#payTransTypsMnItmID").val();
    var payTransCshAcntID =
        typeof $("#payTransCshAcntID").val() === "undefined"
            ? "-1"
            : $("#payTransCshAcntID").val();
    var payTransAssetAcntID =
        typeof $("#payTransAssetAcntID").val() === "undefined"
            ? "-1"
            : $("#payTransAssetAcntID").val();
    var payTransRcvblAcntID =
        typeof $("#payTransRcvblAcntID").val() === "undefined"
            ? "-1"
            : $("#payTransRcvblAcntID").val();
    var payTransLbltyAcntID =
        typeof $("#payTransLbltyAcntID").val() === "undefined"
            ? "-1"
            : $("#payTransLbltyAcntID").val();
    var payTransRvnuAcntID =
        typeof $("#payTransRvnuAcntID").val() === "undefined"
            ? "-1"
            : $("#payTransRvnuAcntID").val();
    var payTransTypsIsEnbld =
        typeof $("input[name='payTransTypsIsEnbld']:checked").val() === "undefined"
            ? "NO"
            : "YES";

    var payTransTypsIntrst =
        typeof $("#payTransTypsIntrst").val() === "undefined"
            ? "0"
            : $("#payTransTypsIntrst").val();
    var payTransTypsIntrstTyp =
        typeof $("#payTransTypsIntrstTyp").val() === "undefined"
            ? ""
            : $("#payTransTypsIntrstTyp").val();
    var payTransTypsRepay =
        typeof $("#payTransTypsRepay").val() === "undefined"
            ? "0"
            : $("#payTransTypsRepay").val();
    var payTransTypsRepayTyp =
        typeof $("#payTransTypsRepayTyp").val() === "undefined"
            ? ""
            : $("#payTransTypsRepayTyp").val();
    var payTransTypsPFrmlr =
        typeof $("#payTransTypsPFrmlr").val() === "undefined"
            ? "0"
            : $("#payTransTypsPFrmlr").val();
    var payTransTypsNetAmntFmlr =
        typeof $("#payTransTypsNetAmntFmlr").val() === "undefined"
            ? "0"
            : $("#payTransTypsNetAmntFmlr").val();
    var payTransTypsMxAmntFrmlr =
        typeof $("#payTransTypsMxAmntFrmlr").val() === "undefined"
            ? "0"
            : $("#payTransTypsMxAmntFrmlr").val();
    var payTransTypsEnfrcMx =
        typeof $("input[name='payTransTypsEnfrcMx']:checked").val() === "undefined"
            ? "NO"
            : "YES";
    var lnkdPayTransTypsID =
        typeof $("#lnkdPayTransTypsID").val() === "undefined"
            ? "-1"
            : $("#lnkdPayTransTypsID").val();
    var payTransTypsMinAmntFrmlr =
        typeof $("#payTransTypsMinAmntFrmlr").val() === "undefined"
            ? ""
            : $("#payTransTypsMinAmntFrmlr").val();
    var payTransTypsMnBalsItmID =
        typeof $("#payTransTypsMnBalsItmID").val() === "undefined"
            ? "-1"
            : $("#payTransTypsMnBalsItmID").val();

    var errMsg = "";
    if (payTransTyp.trim() !== "SETTLEMENT") {
        lnkdPayTransTypsID = "-1";
        payTransTypsMnBalsItmID = "-1";
    }
    if (
        payTransTyp.trim() === "SETTLEMENT" &&
        (Number(lnkdPayTransTypsID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
            Number(payTransTypsMnBalsItmID.replace(/[^-?0-9\.]/g,"")) <= 0)
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Loan Type and Main Balance Item cannot be empty for SETTLEMENT!</span></p>';
    }
    if (payTransTyp.trim() === "" || payTransTypsName.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Transaction Type or Name cannot be empty!</span></p>';
    }
    if (
        (payTransTyp.trim() === "LOAN" || payTransTyp.trim() === "PAYMENT") &&
        Number(payTransTypsItmStID.replace(/[^-?0-9\.]/g,"")) <= 0
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Item Set cannot be empty!</span></p>';
    }
    if (
        (payTransTyp.trim() === "LOAN" || payTransTyp.trim() === "PAYMENT") &&
        Number(payTransTypsMnItmID.replace(/[^-?0-9\.]/g,"")) <= 0
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Main Pay Item cannot be empty!</span></p>';
    }
    if (
        payTransTyp.trim() === "INVESTMENT" &&
        (Number(payTransCshAcntID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
            Number(payTransAssetAcntID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
            Number(payTransRcvblAcntID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
            Number(payTransLbltyAcntID.replace(/[^-?0-9\.]/g,"")) <= 0 ||
            Number(payTransRvnuAcntID.replace(/[^-?0-9\.]/g,"")) <= 0)
    ) {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">All Accounts fields are required!</span></p>';
    }
    if (payTransTyp.trim() === "INVESTMENT" && payTransPeriodTyp.trim() === "") {
        errMsg +=
            '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Period Type cannot be empty!</span></p>';
    }
    var slctdTypClsfctns = "";
    var isVld = true;
    var errMsg = "";
    $("#payTrnTypClsfctnsTable")
        .find("tr")
        .each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr("id") === "undefined") {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr("id").split("_")[1];
                    var majClsfctn = $(
                        "#payTrnTypClsfctnsRow" + rndmNum + "_MajClsfctn"
                    ).val();
                    var minClsfctn = $(
                        "#payTrnTypClsfctnsRow" + rndmNum + "_MinClsfctn"
                    ).val();
                    var ln_OrdrNum = $(
                        "#payTrnTypClsfctnsRow" + rndmNum + "_OrdrNum"
                    ).val();
                    var isEnabled =
                        typeof $(
                            "input[name='payTrnTypClsfctnsRow" +
                            rndmNum +
                            "_IsEnbld']:checked"
                        ).val() === "undefined"
                            ? "NO"
                            : "YES";
                    if (majClsfctn.trim() === "") {
                        $("#payTrnTypClsfctnsRow" + rndmNum + "_MajClsfctn").addClass(
                            "rho-error"
                        );
                    } else {
                        $("#payTrnTypClsfctnsRow" + rndmNum + "_MajClsfctn").removeClass(
                            "rho-error"
                        );
                        slctdTypClsfctns =
                            slctdTypClsfctns +
                            $("#payTrnTypClsfctnsRow" + rndmNum + "_ClsfctnID")
                                .val()
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            $("#payTrnTypClsfctnsRow" + rndmNum + "_OrdrNum")
                                .val()
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            $("#payTrnTypClsfctnsRow" + rndmNum + "_MajClsfctn")
                                .val()
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            $("#payTrnTypClsfctnsRow" + rndmNum + "_MinClsfctn")
                                .val()
                                .replace(/(~)/g,"{-;-;}")
                                .replace(/(\|)/g,"{:;:;}") +
                            "~" +
                            isEnabled.replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") +
                            "|";
                    }
                }
            }
        });
    if (rhotrim(errMsg,"; ") !== "") {
        bootbox.alert({
            title: "System Alert!",
            /*size: 'small',*/
            message: errMsg,
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: "Save Transaction Types",
        size: "small",
        message:
            '<p><i class="fa fa-spin fa-spinner"></i> Saving Transaction Types...Please Wait...</p>',
        callback: function () { },
    });
    var formData = new FormData();
    formData.append("grp",6);
    formData.append("typ",1);
    formData.append("pg",71);
    formData.append("q","UPDATE");
    formData.append("actyp",1);
    formData.append("sbmtdPayTransTypsID",sbmtdPayTransTypsID);
    formData.append("payTransTypsName",payTransTypsName);
    formData.append("payTransTypsDesc",payTransTypsDesc);
    formData.append("payTransTyp",payTransTyp);
    formData.append("payTransTypsPrd",payTransTypsPrd);
    formData.append("payTransPeriodTyp",payTransPeriodTyp);
    formData.append("payTransTypsItmStID",payTransTypsItmStID);
    formData.append("payTransTypsMnItmID",payTransTypsMnItmID);
    formData.append("payTransCshAcntID",payTransCshAcntID);
    formData.append("payTransAssetAcntID",payTransAssetAcntID);
    formData.append("payTransRcvblAcntID",payTransRcvblAcntID);
    formData.append("payTransLbltyAcntID",payTransLbltyAcntID);
    formData.append("payTransRvnuAcntID",payTransRvnuAcntID);
    formData.append("payTransTypsIsEnbld",payTransTypsIsEnbld);

    formData.append("payTransTypsIntrst",payTransTypsIntrst);
    formData.append("payTransTypsIntrstTyp",payTransTypsIntrstTyp);
    formData.append("payTransTypsRepay",payTransTypsRepay);
    formData.append("payTransTypsRepayTyp",payTransTypsRepayTyp);
    formData.append("payTransTypsPFrmlr",payTransTypsPFrmlr);
    formData.append("payTransTypsNetAmntFmlr",payTransTypsNetAmntFmlr);
    formData.append("payTransTypsMxAmntFrmlr",payTransTypsMxAmntFrmlr);
    formData.append("payTransTypsEnfrcMx",payTransTypsEnfrcMx);
    formData.append("slctdTypClsfctns",slctdTypClsfctns);
    formData.append("lnkdPayTransTypsID",lnkdPayTransTypsID);
    formData.append("payTransTypsMnBalsItmID",payTransTypsMnBalsItmID);
    formData.append("payTransTypsMinAmntFrmlr",payTransTypsMinAmntFrmlr);

    formData.append("shdSbmt",shdSbmt);
    dialog.init(function () {
        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
            $body = $("body");
            $body.removeClass("mdlloading");
            $.ajax({
                url: "index.php",
                method: "POST",
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    setTimeout(function () {
                        dialog.find(".bootbox-body").html(data.message);
                        if (data.message.indexOf("Success") !== -1) {
                            sbmtdPayTransTypsID = data.sbmtdPayTransTypsID;
                            getOnePayTransTypsForm(sbmtdPayTransTypsID,1,"ReloadDialog");
                        }
                    },500);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                },
            });
        });
    });
}

function delPayTrnTypClsfctn(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (
        typeof $("#" + rowPrfxNm + rndmNum + "_ClsfctnID").val() === "undefined"
    ) {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_ClsfctnID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();
                     var $tds = $('#' + rowIDAttrb).find('td');
                     pKeyNm = $.trim($tds.eq(1).text());*/
    }
    var msgPrt = "Classifications";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 71,
                                    q: "DELETE",
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}

function delPayTransTyps(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $("#" + rowPrfxNm + rndmNum + "_HdrID").val() === "undefined") {
        /*Do Nothing*/
    } else {
        pKeyID = $("#" + rowPrfxNm + rndmNum + "_HdrID").val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $("#" + rowIDAttrb).find("td");
        pKeyNm = $.trim($tds.eq(3).text());
    }
    var msgPrt = "Transaction Setup";
    var dialog = bootbox.confirm({
        title: "Delete " + msgPrt + "?",
        size: "small",
        message:
            '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' +
            msgPrt +
            "?<br/>Action cannot be Undone!</p>",
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: "btn-success",
            },
            cancel: {
                label: '<i class="fa fa-times"></i> No',
                className: "btn-danger",
            },
        },
        callback: function (result) {
            if (result === true) {
                var dialog1 = bootbox.alert({
                    title: "Delete " + msgPrt + "?",
                    size: "small",
                    message:
                        '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' +
                        msgPrt +
                        "...Please Wait...</p>",
                    callback: function () {
                        $("body").css("padding-right","0px");
                    },
                });
                dialog1.init(function () {
                    if (pKeyID > 0) {
                        getMsgAsyncSilent("grp=1&typ=11&q=Check Session",function () {
                            $body = $("body");
                            $body.removeClass("mdlloading");
                            $.ajax({
                                method: "POST",
                                url: "index.php",
                                data: {
                                    grp: 6,
                                    typ: 1,
                                    pg: 71,
                                    q: "DELETE",
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    pKeyNm: pKeyNm,
                                },
                                success: function (result1) {
                                    setTimeout(function () {
                                        dialog1.find(".bootbox-body").html(result1);
                                        if (result1.indexOf("Success") !== -1) {
                                            $("#" + rowIDAttrb).remove();
                                        }
                                    },500);
                                },
                                error: function (jqXHR1,textStatus1,errorThrown1) {
                                    dialog1.find(".bootbox-body").html(errorThrown1);
                                },
                            });
                        });
                    } else {
                        setTimeout(function () {
                            $("#" + rowIDAttrb).remove();
                            dialog1.find(".bootbox-body").html("Row Removed Successfully!");
                        },500);
                    }
                });
            }
        },
    });
}
