function prepareAcademcs(lnkArgs, htBody, targ, rspns) {
    $(targ).html(rspns);
    $(document).ready(function () {
        if (lnkArgs.indexOf("&pg=1&vtyp=0") !== -1 ||
            lnkArgs.indexOf("&pg=1&vtyp=1") !== -1) {
            loadScript("app/prs/prsn_admin.js?v=" + jsFilesVrsn, function () {
                if (!$.fn.DataTable.isDataTable('#reportCardsHdrsTable')) {
                    var table1 = $('#reportCardsHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#reportCardsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#reportCardsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                $('#reportCrdHdrRmngLife1').val($('#reportCrdHdrRmngLife').val());
                var table1;
                if (!$.fn.DataTable.isDataTable('#reportCardsPMStpsTable')) {
                    table1 = $('#reportCardsPMStpsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#reportCardsPMStpsTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneReportCardsExtrInfTable')) {
                    var table2 = $('#oneReportCardsExtrInfTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneReportCardsExtrInfTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#reportCrdHdrForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                if (!$.fn.DataTable.isDataTable('#oneReportCardTransLinesTable')) {
                    var table2 = $('#oneReportCardTransLinesTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneReportCardTransLinesTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneReportCardPMRecsTable')) {
                    var table2 = $('#oneReportCardPMRecsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneReportCardPMRecsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('[data-toggle="tabajxasShtdetls"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=15&typ=1' + dttrgt;
                    $(targ + 'tab').tab('show');
                    if (targ.indexOf('asShtDetlsTrans') >= 0) {
                        $("#reportCardHdnTabNm").val('asShtDetlsTrans');
                        if ($('#asShtDetlsTrans').text().trim().length <= 0) {
                            var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                            var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
                            return getReportCrdHdr('clear', '#asShtDetlsTrans', 'grp=15&typ=1&pg=1&vtyp=2' +
                                "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                        }
                    } else if (targ.indexOf('asShtDetlsPMRecs') >= 0) {
                        $("#reportCardHdnTabNm").val('asShtDetlsPMRecs');
                        if ($('#asShtDetlsPMRecs').text().trim().length <= 0) {
                            var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                            var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
                            return getReportCrdHdr('clear', '#asShtDetlsPMRecs', 'grp=15&typ=1&pg=1&vtyp=3' +
                                "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                        }
                    } else {
                        $("#reportCardHdnTabNm").val('');
                    }
                });
                $('[data-toggle="tabajxreportcard"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=15&typ=1' + dttrgt;
                    $(targ + 'tab').tab('show');
                    $("#reportCardHdnTabNm").val('');
                    if (targ.indexOf('reportCardDetList') >= 0) {
                        if ($('#reportCardDetList').text().trim().length <= 0) {
                            var assessSbmtdSheetID = typeof $("#reportCardID").val() === 'undefined' ? '-1' : $("#reportCardID").val();
                            var assessSbmtdSheetNm = typeof $("#reportCardNm").val() === 'undefined' ? '' : $("#reportCardNm").val();
                            return getReportCardDets('clear', '#reportCardDetList', 'grp=15&typ=1&pg=1&vtyp=1' +
                                "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                        }
                    }
                });
            });
        } else if (lnkArgs.indexOf("&pg=1&vtyp=2") !== -1) {
            if (!$.fn.DataTable.isDataTable('#oneReportCardTransLinesTable')) {
                var table2 = $('#oneReportCardTransLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneReportCardTransLinesTable').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1 ||
            lnkArgs.indexOf("&pg=2&vtyp=1") !== -1) {
            loadScript("app/prs/prsn_admin.js?v=" + jsFilesVrsn, function () {
                if (!$.fn.DataTable.isDataTable('#assessSheetsHdrsTable')) {
                    var table1 = $('#assessSheetsHdrsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#assessSheetsHdrsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#assessSheetsForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                $('#assessShtHdrRmngLife1').val($('#assessShtHdrRmngLife').val());
                var table1;
                if (!$.fn.DataTable.isDataTable('#assessSheetsPMStpsTable')) {
                    table1 = $('#assessSheetsPMStpsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#assessSheetsPMStpsTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneAssessSheetsExtrInfTable')) {
                    var table2 = $('#oneAssessSheetsExtrInfTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneAssessSheetsExtrInfTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#assessShtHdrForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                if (!$.fn.DataTable.isDataTable('#oneAssessSheetTransLinesTable')) {
                    var table2 = $('#oneAssessSheetTransLinesTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneAssessSheetTransLinesTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneAssessSheetPMRecsTable')) {
                    var table2 = $('#oneAssessSheetPMRecsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneAssessSheetPMRecsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('[data-toggle="tabajxasShtdetls"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=15&typ=1' + dttrgt;
                    $(targ + 'tab').tab('show');
                    if (targ.indexOf('asShtDetlsTrans') >= 0) {
                        $("#assessSheetHdnTabNm").val('asShtDetlsTrans');
                        if ($('#asShtDetlsTrans').text().trim().length <= 0) {
                            var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                            var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
                            return getAssessShtHdr('clear', '#asShtDetlsTrans', 'grp=15&typ=1&pg=2&vtyp=2' +
                                "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                        }
                    } else if (targ.indexOf('asShtDetlsPMRecs') >= 0) {
                        $("#assessSheetHdnTabNm").val('asShtDetlsPMRecs');
                        if ($('#asShtDetlsPMRecs').text().trim().length <= 0) {
                            var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                            var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
                            return getAssessShtHdr('clear', '#asShtDetlsPMRecs', 'grp=15&typ=1&pg=2&vtyp=3' +
                                "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                        }
                    } else {
                        $("#assessSheetHdnTabNm").val('');
                    }
                });
                $('[data-toggle="tabajxassesssheet"]').click(function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    var linkArgs = 'grp=15&typ=1' + dttrgt;
                    $(targ + 'tab').tab('show');
                    $("#assessSheetHdnTabNm").val('');
                    if (targ.indexOf('assessSheetDetList') >= 0) {
                        if ($('#assessSheetDetList').text().trim().length <= 0) {
                            var assessSbmtdSheetID = typeof $("#assessSheetID").val() === 'undefined' ? '-1' : $("#assessSheetID").val();
                            var assessSbmtdSheetNm = typeof $("#assessSheetNm").val() === 'undefined' ? '' : $("#assessSheetNm").val();
                            return getAssessSheetDets('clear', '#assessSheetDetList', 'grp=15&typ=1&pg=2&vtyp=1' +
                                "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm, assessSbmtdSheetID);
                        }
                    }
                });
            });
        } else if (lnkArgs.indexOf("&pg=2&vtyp=2") !== -1) {
            if (!$.fn.DataTable.isDataTable('#oneAssessSheetTransLinesTable')) {
                var table2 = $('#oneAssessSheetTransLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneAssessSheetTransLinesTable').wrap('<div class="dataTables_scroll"/>');
            }
        } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1) {
            loadScript("app/prs/prsn_admin.js?v=" + jsFilesVrsn, function () {
                var table1;
                var table2;
                if (!$.fn.DataTable.isDataTable('#acaRgstratnTable')) {
                    table1 = $('#acaRgstratnTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#acaRgstratnTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnCrsesTable')) {
                    table2 = $('#oneAcaRgstratnCrsesTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneAcaRgstratnCrsesTable').wrap('<div class="dataTables_scroll"/>');
                }
                if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnSbjctsTable')) {
                    var table3 = $('#oneAcaRgstratnSbjctsTable').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#oneAcaRgstratnSbjctsTable').wrap('<div class="dataTables_scroll"/>');
                }
                $('#acaRgstratnForm').submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                $('#acaRgstratnTable tbody').off('click');
                $('#acaRgstratnTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table1.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pkeyID = typeof $('#acaRgstratnRow' + rndmNum + '_PrsnID').val() === 'undefined' ? '-1' : $('#acaRgstratnRow' + rndmNum + '_PrsnID').val();
                    getOneAcaRgstratnForm(pkeyID, 1);
                });
                $('#acaRgstratnTable tbody')
                    .off('mouseenter', 'tr');
                $('#acaRgstratnTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table1.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });

                $('#oneAcaRgstratnCrsesTable tbody').off('click');
                $('#oneAcaRgstratnCrsesTable tbody').on('click', 'tr', function () {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        table2.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                    }
                    var rndmNum = $(this).attr('id').split("_")[1];
                    var pkeyID = typeof $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val() === 'undefined' ? '-1' : $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val();
                    getOneAcaRgstratnSbjctsForm(pkeyID, 3);
                });

                $('#oneAcaRgstratnCrsesTable tbody')
                    .off('mouseenter', 'tr');
                $('#oneAcaRgstratnCrsesTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });
            });
        } else if (lnkArgs.indexOf("&pg=4&vtyp=0") !== -1 || lnkArgs.indexOf("&pg=4&vtyp=2") !== -1) {
            var table1;
            var table2;
            if (!$.fn.DataTable.isDataTable('#acaClassesTable')) {
                table1 = $('#acaClassesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaClassesTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneAcaClassesCrsesTable')) {
                table2 = $('#oneAcaClassesCrsesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneAcaClassesCrsesTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneAcaClassesSbjctsTable')) {
                var table3 = $('#oneAcaClassesSbjctsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneAcaClassesSbjctsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#acaClassesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#acaClassesTable tbody').off('click');
            $('#acaClassesTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#acaClassesRow' + rndmNum + '_ClassesID').val() === 'undefined' ? '-1' : $('#acaClassesRow' + rndmNum + '_ClassesID').val();
                getOneAcaClassesForm(pkeyID, 1);
            });
            $('#acaClassesTable tbody')
                .off('mouseenter', 'tr');
            $('#acaClassesTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });


            $('#oneAcaClassesCrsesTable tbody').off('click');
            $('#oneAcaClassesCrsesTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#oneAcaClassesCrsesRow' + rndmNum + '_CrseLnID').val() === 'undefined' ? '-1' : $('#oneAcaClassesCrsesRow' + rndmNum + '_CrseLnID').val();
                getOneAcaCrsesSbjctsForm(pkeyID, 3);
            });
            $('#oneAcaClassesCrsesTable tbody')
                .off('mouseenter', 'tr');
            $('#oneAcaClassesCrsesTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table2.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });

        } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable('#acaPosHldrsHdrsTable')) {
                var table2 = $('#acaPosHldrsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaPosHldrsHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#acaPosHldrsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=6&vtyp=0") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable('#acaPeriodsHdrsTable')) {
                var table2 = $('#acaPeriodsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaPeriodsHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#acaPeriodsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=7&vtyp=0") !== -1 || lnkArgs.indexOf("&pg=7&vtyp=2") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable('#acaAssessTypesHdrsTable')) {
                var table1 = $('#acaAssessTypesHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaAssessTypesHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneAcaAssessTypesSmryLinesTable')) {
                var table2 = $('#oneAcaAssessTypesSmryLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneAcaAssessTypesSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#acaAssessTypesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#acaAssessTypesHdrsTable tbody').off('click');
            $('#acaAssessTypesHdrsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#acaAssessTypesRow' + rndmNum + '_AssessTypesID').val() === 'undefined' ? '-1' : $('#acaAssessTypesRow' + rndmNum + '_AssessTypesID').val();
                getOneAcaAssessTypesForm(pkeyID, 1);
            });
            $('#acaAssessTypesHdrsTable tbody')
                .off('mouseenter', 'tr');
            $('#acaAssessTypesHdrsTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
            $(".jbDetAccRate").ForceNumericOnly();
            $(".jbDetDbt").ForceNumericOnly();
            $(".jbDetCrdt").ForceNumericOnly();
        } else if (lnkArgs.indexOf("&pg=8&vtyp=0") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable('#acaCoursesHdrsTable')) {
                var table2 = $('#acaCoursesHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaCoursesHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#acaCoursesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=9&vtyp=0") !== -1) {

            var table1;
            if (!$.fn.DataTable.isDataTable('#acaSbjctsHdrsTable')) {
                var table2 = $('#acaSbjctsHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaSbjctsHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#acaSbjctsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
        } else if (lnkArgs.indexOf("&pg=10&vtyp=0") !== -1 || lnkArgs.indexOf("&pg=10&vtyp=2") !== -1) {
            var table1;
            if (!$.fn.DataTable.isDataTable('#acaGradeScalesHdrsTable')) {
                var table1 = $('#acaGradeScalesHdrsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#acaGradeScalesHdrsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneAcaGradeScalesSmryLinesTable')) {
                var table2 = $('#oneAcaGradeScalesSmryLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneAcaGradeScalesSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#acaGradeScalesForm').submit(function (e) {
                e.preventDefault();
                return false;
            });

            $('#acaGradeScalesHdrsTable tbody').off('click');
            $('#acaGradeScalesHdrsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pkeyID = typeof $('#acaGradeScalesRow' + rndmNum + '_GradeScalesID').val() === 'undefined' ? '-1' : $('#acaGradeScalesRow' + rndmNum + '_GradeScalesID').val();
                getOneAcaGradeScalesForm(pkeyID, 1);
            });
            $('#acaGradeScalesHdrsTable tbody')
                .off('mouseenter', 'tr');
            $('#acaGradeScalesHdrsTable tbody')
                .on('mouseenter', 'tr', function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        table1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });
        }
        htBody.removeClass("mdlloading");
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
        $(".jbDetColNo").focus(function () {
            $(this).select();
        });
        $(".assesScoreM").focus(function () {
            $(this).select();
        });
        $(".jbDetGPA").focus(function () {
            $(this).select();
        });
        $(".jbDetMin").focus(function () {
            $(this).select();
        });
        $(".jbDetMax").focus(function () {
            $(this).select();
        });
        $(".jbDetColNo").ForceNumericOnly();
        $(".assesScoreNum").ForceNumericOnly();
        $(".jbDetGPA").ForceNumericOnly();
        $(".jbDetMin").ForceNumericOnly();
        $(".jbDetMax").ForceNumericOnly();
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

function getAcaSbjcts(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaSbjctsSrchFor").val() === 'undefined' ? '%' : $("#acaSbjctsSrchFor").val();
    var srchIn = typeof $("#acaSbjctsSrchIn").val() === 'undefined' ? 'Both' : $("#acaSbjctsSrchIn").val();
    var pageNo = typeof $("#acaSbjctsPageNo").val() === 'undefined' ? 1 : $("#acaSbjctsPageNo").val();
    var limitSze = typeof $("#acaSbjctsDsplySze").val() === 'undefined' ? 10 : $("#acaSbjctsDsplySze").val();
    var sortBy = typeof $("#acaSbjctsSortBy").val() === 'undefined' ? '' : $("#acaSbjctsSortBy").val();
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

function enterKeyFuncAcaSbjcts(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaSbjcts(actionText, slctr, linkArgs);
    }
}

function saveAcaSbjctsForm() {
    var errMsg = "";
    var isVld = true;
    var slctdSubjectIDs = "";
    $('#acaSbjctsHdrsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#acaSbjctsHdrsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#acaSbjctsHdrsRow' + rndmNum + '_TrnsLnID').val();
                var ln_Type = typeof $('#acaSbjctsHdrsRow' + rndmNum + '_Type').val() === 'undefined' ? '' : $('#acaSbjctsHdrsRow' + rndmNum + '_Type').val();
                var ln_SbjctCode = typeof $('#acaSbjctsHdrsRow' + rndmNum + '_SbjctCode').val() === 'undefined' ? '' : $('#acaSbjctsHdrsRow' + rndmNum + '_SbjctCode').val();
                var ln_SbjctNm = typeof $('#acaSbjctsHdrsRow' + rndmNum + '_SbjctNm').val() === 'undefined' ? '' : $('#acaSbjctsHdrsRow' + rndmNum + '_SbjctNm').val();
                var ln_SjctDesc = typeof $('#acaSbjctsHdrsRow' + rndmNum + '_SjctDesc').val() === 'undefined' ? '' : $('#acaSbjctsHdrsRow' + rndmNum + '_SjctDesc').val();
                var ln_IsEnbld = typeof $("input[name='acaSbjctsHdrsRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (ln_SbjctCode.trim() !== '') {
                    if (ln_SbjctNm.trim() === '' || ln_Type.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Name and Type for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#acaSbjctsHdrsRow' + rndmNum + '_SbjctNm').addClass('rho-error');
                        $('#acaSbjctsHdrsRow' + rndmNum + '_Type').addClass('rho-error');
                    } else {
                        $('#acaSbjctsHdrsRow' + rndmNum + '_SbjctNm').removeClass('rho-error');
                        $('#acaSbjctsHdrsRow' + rndmNum + '_Type').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdSubjectIDs = slctdSubjectIDs +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_Type.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SbjctCode.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SbjctNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SjctDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
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
        title: 'Save Subjects/Tasks',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Subjects/Tasks...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 9);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('slctdSubjectIDs', slctdSubjectIDs);

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
                            getAcaSbjcts('', '#allmodules', 'grp=15&typ=1&pg=9&vtyp=0');
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

function insertNewAcaSbjctsRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
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

function delAcaSbjcts(rowIDAttrb) {
    var msgPart = "Subject/Task";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SbjctNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
                                    typ: 1,
                                    pg: 9,
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

function getAcaCourses(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaCoursesSrchFor").val() === 'undefined' ? '%' : $("#acaCoursesSrchFor").val();
    var srchIn = typeof $("#acaCoursesSrchIn").val() === 'undefined' ? 'Both' : $("#acaCoursesSrchIn").val();
    var pageNo = typeof $("#acaCoursesPageNo").val() === 'undefined' ? 1 : $("#acaCoursesPageNo").val();
    var limitSze = typeof $("#acaCoursesDsplySze").val() === 'undefined' ? 10 : $("#acaCoursesDsplySze").val();
    var sortBy = typeof $("#acaCoursesSortBy").val() === 'undefined' ? '' : $("#acaCoursesSortBy").val();
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

function enterKeyFuncAcaCourses(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaCourses(actionText, slctr, linkArgs);
    }
}

function saveAcaCoursesForm() {
    var errMsg = "";
    var isVld = true;
    var slctdCourseIDs = "";
    $('#acaCoursesHdrsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#acaCoursesHdrsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#acaCoursesHdrsRow' + rndmNum + '_TrnsLnID').val();
                var ln_Type = typeof $('#acaCoursesHdrsRow' + rndmNum + '_Type').val() === 'undefined' ? '' : $('#acaCoursesHdrsRow' + rndmNum + '_Type').val();
                var ln_CourseCode = typeof $('#acaCoursesHdrsRow' + rndmNum + '_CourseCode').val() === 'undefined' ? '' : $('#acaCoursesHdrsRow' + rndmNum + '_CourseCode').val();
                var ln_CourseNm = typeof $('#acaCoursesHdrsRow' + rndmNum + '_CourseNm').val() === 'undefined' ? '' : $('#acaCoursesHdrsRow' + rndmNum + '_CourseNm').val();
                var ln_CourseDesc = typeof $('#acaCoursesHdrsRow' + rndmNum + '_CourseDesc').val() === 'undefined' ? '' : $('#acaCoursesHdrsRow' + rndmNum + '_CourseDesc').val();
                var ln_IsEnbld = typeof $("input[name='acaCoursesHdrsRow" + rndmNum + "_IsEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
                if (ln_CourseCode.trim() !== '') {
                    if (ln_CourseNm.trim() === '' || ln_Type.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Name and Type for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#acaCoursesHdrsRow' + rndmNum + '_CourseNm').addClass('rho-error');
                        $('#acaCoursesHdrsRow' + rndmNum + '_Type').addClass('rho-error');
                    } else {
                        $('#acaCoursesHdrsRow' + rndmNum + '_CourseNm').removeClass('rho-error');
                        $('#acaCoursesHdrsRow' + rndmNum + '_Type').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdCourseIDs = slctdCourseIDs +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_Type.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CourseCode.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CourseNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CourseDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
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
        title: 'Save Courses/Objectives',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Courses/Objectives...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 8);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('slctdCourseIDs', slctdCourseIDs);

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
                            getAcaCourses('', '#allmodules', 'grp=15&typ=1&pg=8&vtyp=0');
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

function insertNewAcaCoursesRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
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

function delAcaCourses(rowIDAttrb) {
    var msgPart = "Course/Objective";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CourseNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
                                    typ: 1,
                                    pg: 8,
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

function getAcaAssessTypes(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaAssessTypesSrchFor").val() === 'undefined' ? '%' : $("#acaAssessTypesSrchFor").val();
    var srchIn = typeof $("#acaAssessTypesSrchIn").val() === 'undefined' ? 'Both' : $("#acaAssessTypesSrchIn").val();
    var pageNo = typeof $("#acaAssessTypesPageNo").val() === 'undefined' ? 1 : $("#acaAssessTypesPageNo").val();
    var limitSze = typeof $("#acaAssessTypesDsplySze").val() === 'undefined' ? 10 : $("#acaAssessTypesDsplySze").val();
    var sortBy = typeof $("#acaAssessTypesSortBy").val() === 'undefined' ? '' : $("#acaAssessTypesSortBy").val();
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

function enterKeyFuncAcaAssessTypes(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaAssessTypes(actionText, slctr, linkArgs);
    }
}

function getOneAcaAssessTypesForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaAssessTypesDetailInfo';
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
    var lnkArgs = 'grp=15&typ=1&pg=7&vtyp=' + vwtype + '&sbmtdAssessTypesID=' + tmpltID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID;
    //alert(lnkArgs);
    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        if (!$.fn.DataTable.isDataTable('#oneAcaAssessTypesSmryLinesTable')) {
            var table2 = $('#oneAcaAssessTypesSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaAssessTypesSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#acaAssessTypesForm').submit(function (e) {
            e.preventDefault();
            return false;
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

function saveAcaAssessTypesForm() {
    var acaAssessTypesID = typeof $("#acaAssessTypesID").val() === 'undefined' ? '-1' : $("#acaAssessTypesID").val();
    var acaAssessTypesName = typeof $("#acaAssessTypesName").val() === 'undefined' ? '' : $("#acaAssessTypesName").val();
    var acaAssessTypesDesc = typeof $("#acaAssessTypesDesc").val() === 'undefined' ? '' : $("#acaAssessTypesDesc").val();
    var acaAssessTypesType = typeof $("#acaAssessTypesType").val() === 'undefined' ? '' : $("#acaAssessTypesType").val();
    var acaAssessTypesLevel = typeof $("#acaAssessTypesLevel").val() === 'undefined' ? '' : $("#acaAssessTypesLevel").val();
    var acaAssessTypesLnkdAssessID = typeof $("#acaAssessTypesLnkdAssessID").val() === 'undefined' ? '-1' : $("#acaAssessTypesLnkdAssessID").val();
    var acaAssessTypesLnkdAssessNm = typeof $("#acaAssessTypesLnkdAssessNm").val() === 'undefined' ? '' : $("#acaAssessTypesLnkdAssessNm").val();
    var acaAssessTypesGrdScaleID = typeof $("#acaAssessTypesGrdScaleID").val() === 'undefined' ? '-1' : $("#acaAssessTypesGrdScaleID").val();
    var acaAssessTypesGrdScaleNm = typeof $("#acaAssessTypesGrdScaleNm").val() === 'undefined' ? '' : $("#acaAssessTypesGrdScaleNm").val();
    var acaAssessTypesIsEnbld = typeof $("input[name='acaAssessTypesIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='acaAssessTypesIsEnbld']:checked").val();
    var errMsg = "";
    if (acaAssessTypesName.trim() === '' || acaAssessTypesType.trim() === '' || acaAssessTypesLevel.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Assessment Type, Name and Level cannot be empty!</span></p>';
    }
    if (acaAssessTypesGrdScaleNm.trim() === '' || Number(acaAssessTypesGrdScaleID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Grade Scale cannot be empty!</span></p>';
    }
    var msgPart = 'Assessment Type';
    var errMsg = "";
    var isVld = true;
    var slctdColumnIDs = "";
    $('#oneAcaAssessTypesSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_RecLnID = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_RecLnID').val() === 'undefined' ? '-1' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_RecLnID').val();
                var ln_LineName = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_LineName').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_LineName').val();
                var ln_LineDesc = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_LineDesc').val();
                var ln_HdrText = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_HdrText').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_HdrText').val();
                var ln_SectionLoc = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_SectionLoc').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_SectionLoc').val();
                var ln_DataType = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_DataType').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_DataType').val();
                var ln_DataLength = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_DataLength').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_DataLength').val();
                var ln_ColNum = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_ColNum').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_ColNum').val();
                var ln_IsEnabled = typeof $("input[name='oneAcaAssessTypesSmryRow" + rndmNum + "_IsEnabled']:checked").val() === 'undefined' ? 'NO' : 'YES';
                var ln_SQLFormular = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_SQLFormular').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_SQLFormular').val();
                var ln_CSStyle = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_CSStyle').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_CSStyle').val();
                var ln_MinValue = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_MinValue').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_MinValue').val();
                var ln_MaxValue = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_MaxValue').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_MaxValue').val();
                var ln_LovName = typeof $('#oneAcaAssessTypesSmryRow' + rndmNum + '_LovName').val() === 'undefined' ? '' : $('#oneAcaAssessTypesSmryRow' + rndmNum + '_LovName').val();
                var ln_IsDsplyd = typeof $("input[name='oneAcaAssessTypesSmryRow" + rndmNum + "_IsDsplyd']:checked").val() === 'undefined' ? 'NO' : 'YES';
                var ln_IsFormular = ln_SQLFormular.trim() === '' ? 'NO' : 'YES';
                if (ln_LineName.trim() !== '') {
                    if (ln_HdrText.trim() === '' || ln_DataType.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Header Text and Data Type for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneAcaAssessTypesSmryRow' + rndmNum + '_HdrText').addClass('rho-error');
                        $('#oneAcaAssessTypesSmryRow' + rndmNum + '_DataType').addClass('rho-error');
                    } else {
                        $('#oneAcaAssessTypesSmryRow' + rndmNum + '_HdrText').removeClass('rho-error');
                        $('#oneAcaAssessTypesSmryRow' + rndmNum + '_DataType').removeClass('rho-error');
                    }
                    var minValue = Number($('#oneAcaAssessTypesSmryRow' + rndmNum + '_ColNum').attr('min-rhodata').replace(/[^-?0-9\.]/g, ''));
                    var maxValue = Number($('#oneAcaAssessTypesSmryRow' + rndmNum + '_ColNum').attr('max-rhodata').replace(/[^-?0-9\.]/g, ''));

                    if (Number(ln_ColNum.replace(/[^-?0-9\.]/g, '')) < minValue ||
                        Number(ln_ColNum.replace(/[^-?0-9\.]/g, '')) > maxValue) {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Data Column Number for Row No. ' + i + ' must be between ' + minValue + ' and ' + maxValue + '!</span></p>';
                        $('#oneAcaAssessTypesSmryRow' + rndmNum + '_ColNum').addClass('rho-error');
                    } else {
                        $('#oneAcaAssessTypesSmryRow' + rndmNum + '_ColNum').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdColumnIDs = slctdColumnIDs +
                            ln_RecLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_HdrText.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SectionLoc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_DataType.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_DataLength.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_IsEnabled.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SQLFormular.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_IsFormular.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_ColNum.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_MinValue.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_MaxValue.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_IsDsplyd.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CSStyle.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LovName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save ' + msgPart + '',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msgPart + '...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 7);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('acaAssessTypesID', acaAssessTypesID);
    formData.append('acaAssessTypesName', acaAssessTypesName);
    formData.append('acaAssessTypesDesc', acaAssessTypesDesc);
    formData.append('acaAssessTypesType', acaAssessTypesType);
    formData.append('acaAssessTypesLevel', acaAssessTypesLevel);
    formData.append('acaAssessTypesLnkdAssessID', acaAssessTypesLnkdAssessID);
    formData.append('acaAssessTypesLnkdAssessNm', acaAssessTypesLnkdAssessNm);
    formData.append('acaAssessTypesGrdScaleID', acaAssessTypesGrdScaleID);
    formData.append('acaAssessTypesGrdScaleNm', acaAssessTypesGrdScaleNm);
    formData.append('acaAssessTypesIsEnbld', acaAssessTypesIsEnbld);
    formData.append('slctdColumnIDs', slctdColumnIDs);

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
                            getAcaAssessTypes('', '#allmodules', 'grp=15&typ=1&pg=7&vtyp=0');
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

function insertNewAcaAssessTypesRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
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
    $(".jbDetAccRate").ForceNumericOnly();
    $(".jbDetDbt").ForceNumericOnly();
    $(".jbDetCrdt").ForceNumericOnly();
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

function delAcaAssessTypes(rowIDAttrb) {
    var msgPart = "Assessment Type";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_AssessTypesID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_AssessTypesID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_AssessTypesNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

function delAcaAssessTypesLne(rowIDAttrb) {
    var msgPart = "Assessment Type Column";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_RecLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_RecLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_LineName').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

function getAcaAssessTypesDet(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaAssessTypesDetSrchFor").val() === 'undefined' ? '%' : $("#acaAssessTypesDetSrchFor").val();
    var srchIn = typeof $("#acaAssessTypesDetSrchIn").val() === 'undefined' ? 'Both' : $("#acaAssessTypesDetSrchIn").val();
    var pageNo = typeof $("#acaAssessTypesDetPageNo").val() === 'undefined' ? 1 : $("#acaAssessTypesDetPageNo").val();
    var limitSze = typeof $("#acaAssessTypesDetDsplySze").val() === 'undefined' ? 10 : $("#acaAssessTypesDetDsplySze").val();
    var sortBy = typeof $("#acaAssessTypesDetSortBy").val() === 'undefined' ? '' : $("#acaAssessTypesDetSortBy").val();
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

function enterKeyFuncAcaAssessTypesDet(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaAssessTypesDet(actionText, slctr, linkArgs);
    }
}
/*Grade SCales*/

function getAcaGradeScales(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaGradeScalesSrchFor").val() === 'undefined' ? '%' : $("#acaGradeScalesSrchFor").val();
    var srchIn = typeof $("#acaGradeScalesSrchIn").val() === 'undefined' ? 'Both' : $("#acaGradeScalesSrchIn").val();
    var pageNo = typeof $("#acaGradeScalesPageNo").val() === 'undefined' ? 1 : $("#acaGradeScalesPageNo").val();
    var limitSze = typeof $("#acaGradeScalesDsplySze").val() === 'undefined' ? 10 : $("#acaGradeScalesDsplySze").val();
    var sortBy = typeof $("#acaGradeScalesSortBy").val() === 'undefined' ? '' : $("#acaGradeScalesSortBy").val();
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

function enterKeyFuncAcaGradeScales(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaGradeScales(actionText, slctr, linkArgs);
    }
}

function getOneAcaGradeScalesForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaGradeScalesDetailInfo';
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
    var lnkArgs = 'grp=15&typ=1&pg=10&vtyp=' + vwtype + '&sbmtdGradeScalesID=' + tmpltID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID;
    //alert(lnkArgs);
    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        if (!$.fn.DataTable.isDataTable('#oneAcaGradeScalesSmryLinesTable')) {
            var table2 = $('#oneAcaGradeScalesSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaGradeScalesSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#acaGradeScalesForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(".jbDetGPA").focus(function () {
            $(this).select();
        });
        $(".jbDetMin").focus(function () {
            $(this).select();
        });
        $(".jbDetMax").focus(function () {
            $(this).select();
        });
        $(".jbDetCrdt").focus(function () {
            $(this).select();
        });
        $(".jbDetGPA").ForceNumericOnly();
        $(".jbDetMin").ForceNumericOnly();
        $(".jbDetMax").ForceNumericOnly();
    });
}

function saveAcaGradeScalesForm() {
    var acaGradeScalesID = typeof $("#acaGradeScalesID").val() === 'undefined' ? '-1' : $("#acaGradeScalesID").val();
    var acaGradeScalesName = typeof $("#acaGradeScalesName").val() === 'undefined' ? '' : $("#acaGradeScalesName").val();
    var acaGradeScalesDesc = typeof $("#acaGradeScalesDesc").val() === 'undefined' ? '' : $("#acaGradeScalesDesc").val();
    var acaGradeScalesIsEnbld = typeof $("input[name='acaGradeScalesIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='acaGradeScalesIsEnbld']:checked").val();
    var errMsg = "";
    if (acaGradeScalesName.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Grade Scale Name cannot be empty!</span></p>';
    }
    var msgPart = 'Grade Scale';
    var errMsg = "";
    var isVld = true;
    var slctdColumnIDs = "";
    $('#oneAcaGradeScalesSmryLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_RecLnID = typeof $('#oneAcaGradeScalesSmryRow' + rndmNum + '_RecLnID').val() === 'undefined' ? '-1' : $('#oneAcaGradeScalesSmryRow' + rndmNum + '_RecLnID').val();
                var ln_LineName = typeof $('#oneAcaGradeScalesSmryRow' + rndmNum + '_LineName').val() === 'undefined' ? '' : $('#oneAcaGradeScalesSmryRow' + rndmNum + '_LineName').val();
                var ln_LineDesc = typeof $('#oneAcaGradeScalesSmryRow' + rndmNum + '_LineDesc').val() === 'undefined' ? '' : $('#oneAcaGradeScalesSmryRow' + rndmNum + '_LineDesc').val();
                var ln_GradeGPA = typeof $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeGPA').val() === 'undefined' ? '0' : $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeGPA').val();
                var ln_GradeMin = typeof $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMin').val() === 'undefined' ? '0' : $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMin').val();
                var ln_GradeMax = typeof $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMax').val() === 'undefined' ? '0' : $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMax').val();
                if (ln_LineName.trim() !== '') {
                    if (ln_GradeMin.trim() === '' || ln_GradeMax.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Band Mininum and Maximun Values for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMin').addClass('rho-error');
                        $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMax').addClass('rho-error');
                    } else {
                        $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMin').removeClass('rho-error');
                        $('#oneAcaGradeScalesSmryRow' + rndmNum + '_GradeMax').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdColumnIDs = slctdColumnIDs +
                            ln_RecLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_LineDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_GradeGPA.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_GradeMin.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_GradeMax.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save ' + msgPart + '',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msgPart + '...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 10);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('acaGradeScalesID', acaGradeScalesID);
    formData.append('acaGradeScalesName', acaGradeScalesName);
    formData.append('acaGradeScalesDesc', acaGradeScalesDesc);
    formData.append('acaGradeScalesIsEnbld', acaGradeScalesIsEnbld);
    formData.append('slctdColumnIDs', slctdColumnIDs);

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
                            getAcaGradeScales('', '#allmodules', 'grp=15&typ=1&pg=10&vtyp=0');
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

function insertNewAcaGradeScalesRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
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

    $(".jbDetGPA").focus(function () {
        $(this).select();
    });
    $(".jbDetMin").focus(function () {
        $(this).select();
    });
    $(".jbDetMax").focus(function () {
        $(this).select();
    });
    $(".jbDetGPA").ForceNumericOnly();
    $(".jbDetMin").ForceNumericOnly();
    $(".jbDetMax").ForceNumericOnly();
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

function delAcaGradeScales(rowIDAttrb) {
    var msgPart = "Grade Scale";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_GradeScalesID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_GradeScalesID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_GradeScalesNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

function getAcaGradeScalesDet(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaGradeScalesSrchFor").val() === 'undefined' ? '%' : $("#acaGradeScalesSrchFor").val();
    var srchIn = typeof $("#acaGradeScalesSrchIn").val() === 'undefined' ? 'Both' : $("#acaGradeScalesSrchIn").val();
    var pageNo = typeof $("#acaGradeScalesPageNo").val() === 'undefined' ? 1 : $("#acaGradeScalesPageNo").val();
    var limitSze = typeof $("#acaGradeScalesDsplySze").val() === 'undefined' ? 10 : $("#acaGradeScalesDsplySze").val();
    var sortBy = typeof $("#acaGradeScalesSortBy").val() === 'undefined' ? '' : $("#acaGradeScalesSortBy").val();
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

function enterKeyFuncAcaGradeScalesDet(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaGradeScalesDet(actionText, slctr, linkArgs);
    }
}
/*End Grade Scales*/

function getAcaPeriods(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaPeriodsSrchFor").val() === 'undefined' ? '%' : $("#acaPeriodsSrchFor").val();
    var srchIn = typeof $("#acaPeriodsSrchIn").val() === 'undefined' ? 'Both' : $("#acaPeriodsSrchIn").val();
    var pageNo = typeof $("#acaPeriodsPageNo").val() === 'undefined' ? 1 : $("#acaPeriodsPageNo").val();
    var limitSze = typeof $("#acaPeriodsDsplySze").val() === 'undefined' ? 30 : $("#acaPeriodsDsplySze").val();
    var sortBy = typeof $("#acaPeriodsSortBy").val() === 'undefined' ? '' : $("#acaPeriodsSortBy").val();
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

function enterKeyFuncAcaPeriods(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaPeriods(actionText, slctr, linkArgs);
    }
}

function saveAcaPeriodsForm() {
    var msgPart = "Assessment Periods";
    var errMsg = "";
    var isVld = true;
    var slctdPeriodIDs = "";
    $('#acaPeriodsHdrsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#acaPeriodsHdrsRow' + rndmNum + '_TrnsLnID').val();
                var ln_Type = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_Type').val() === 'undefined' ? '' : $('#acaPeriodsHdrsRow' + rndmNum + '_Type').val();
                var ln_PeriodNm = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodNm').val() === 'undefined' ? '' : $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodNm').val();
                var ln_PeriodDesc = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodDesc').val() === 'undefined' ? '' : $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodDesc').val();
                var ln_StrtDte = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#acaPeriodsHdrsRow' + rndmNum + '_StrtDte').val();
                var ln_EndDte = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_EndDte').val() === 'undefined' ? '' : $('#acaPeriodsHdrsRow' + rndmNum + '_EndDte').val();
                var ln_Status = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_Status').val() === 'undefined' ? '' : $('#acaPeriodsHdrsRow' + rndmNum + '_Status').val();
                var ln_PeriodNumber = typeof $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodNumber').val() === 'undefined' ? '1' : $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodNumber').val();

                if (ln_PeriodNm.trim() !== '') {
                    if (ln_PeriodNm.trim() === '' || ln_Type.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Name and Type for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodNm').addClass('rho-error');
                        $('#acaPeriodsHdrsRow' + rndmNum + '_Type').addClass('rho-error');
                    } else {
                        $('#acaPeriodsHdrsRow' + rndmNum + '_PeriodNm').removeClass('rho-error');
                        $('#acaPeriodsHdrsRow' + rndmNum + '_Type').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdPeriodIDs = slctdPeriodIDs +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_Type.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PeriodNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PeriodDesc.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StrtDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_EndDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_Status.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PeriodNumber.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save ' + msgPart + '',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msgPart + '...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 6);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('slctdPeriodIDs', slctdPeriodIDs);

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
                            getAcaPeriods('', '#allmodules', 'grp=15&typ=1&pg=6&vtyp=0');
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

function insertNewAcaPeriodsRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
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

function delAcaPeriods(rowIDAttrb) {
    var msgPart = "Assessment Period";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_PeriodNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

function getAcaPosHldrs(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaPosHldrsSrchFor").val() === 'undefined' ? '%' : $("#acaPosHldrsSrchFor").val();
    var srchIn = typeof $("#acaPosHldrsSrchIn").val() === 'undefined' ? 'Both' : $("#acaPosHldrsSrchIn").val();
    var pageNo = typeof $("#acaPosHldrsPageNo").val() === 'undefined' ? 1 : $("#acaPosHldrsPageNo").val();
    var limitSze = typeof $("#acaPosHldrsDsplySze").val() === 'undefined' ? 10 : $("#acaPosHldrsDsplySze").val();
    var sortBy = typeof $("#acaPosHldrsSortBy").val() === 'undefined' ? '' : $("#acaPosHldrsSortBy").val();
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

function enterKeyFuncAcaPosHldrs(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode === 13) {
        getAcaPosHldrs(actionText, slctr, linkArgs);
    }
}

function saveAcaPosHldrsForm() {
    var msgPart = "Position Holders";
    var errMsg = "";
    var isVld = true;
    var slctdPosHldrsIDs = "";
    $('#acaPosHldrsHdrsTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_TrnsLnID = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_TrnsLnID').val() === 'undefined' ? '-1' : $('#acaPosHldrsHdrsRow' + rndmNum + '_TrnsLnID').val();
                var ln_Type = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_Type').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_Type').val();
                var ln_GroupID = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_GroupID').val() === 'undefined' ? '-1' : $('#acaPosHldrsHdrsRow' + rndmNum + '_GroupID').val();
                var ln_GroupNm = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_GroupNm').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_GroupNm').val();
                var ln_CourseID = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_CourseID').val() === 'undefined' ? '-1' : $('#acaPosHldrsHdrsRow' + rndmNum + '_CourseID').val();
                var ln_CourseNm = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_CourseNm').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_CourseNm').val();
                var ln_SbjctID = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_SbjctID').val() === 'undefined' ? '-1' : $('#acaPosHldrsHdrsRow' + rndmNum + '_SbjctID').val();
                var ln_SbjctNm = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_SbjctNm').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_SbjctNm').val();
                var ln_PosID = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_PosID').val() === 'undefined' ? '-1' : $('#acaPosHldrsHdrsRow' + rndmNum + '_PosID').val();
                var ln_PosNm = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_PosNm').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_PosNm').val();
                var ln_PosHldrID = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_PosHldrID').val() === 'undefined' ? '-1' : $('#acaPosHldrsHdrsRow' + rndmNum + '_PosHldrID').val();
                var ln_PosHldrNm = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_PosHldrNm').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_PosHldrNm').val();
                var ln_StrtDte = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_StrtDte').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_StrtDte').val();
                var ln_EndDte = typeof $('#acaPosHldrsHdrsRow' + rndmNum + '_EndDte').val() === 'undefined' ? '' : $('#acaPosHldrsHdrsRow' + rndmNum + '_EndDte').val();
                if (ln_PosNm.trim() !== '') {
                    if (ln_PosNm.trim() === '' || ln_PosHldrNm.trim() === '') {
                        isVld = false;
                        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                            'font-weight:bold;color:red;">Position and Position Holder for Row No. ' + i + ' cannot be empty!</span></p>';
                        $('#acaPosHldrsHdrsRow' + rndmNum + '_PosNm').addClass('rho-error');
                        $('#acaPosHldrsHdrsRow' + rndmNum + '_PosHldrNm').addClass('rho-error');
                    } else {
                        $('#acaPosHldrsHdrsRow' + rndmNum + '_PosNm').removeClass('rho-error');
                        $('#acaPosHldrsHdrsRow' + rndmNum + '_PosHldrNm').removeClass('rho-error');
                    }
                    if (isVld === true) {
                        slctdPosHldrsIDs = slctdPosHldrsIDs +
                            ln_TrnsLnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_Type.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_GroupID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_CourseID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_SbjctID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PosID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_PosHldrID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_StrtDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            ln_EndDte.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save ' + msgPart + '',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving ' + msgPart + '...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 5);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('slctdPosHldrsIDs', slctdPosHldrsIDs);

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
                            getAcaPosHldrs('', '#allmodules', 'grp=15&typ=1&pg=5&vtyp=0');
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

function insertNewAcaPosHldrsRows(tableElmntID, position, inptHtml) {
    for (var i = 0; i < 1; i++) {
        var nwRndm = Math.floor((Math.random() * 9999999) + 1000000);
        var nwInptHtml = urldecode(inptHtml.replace(/(_WWW123WWW_)+/g, nwRndm + "_").replace(/(_WWW123WWW)+/g, nwRndm));
        if ($('#' + tableElmntID + ' > tbody > tr').length >= 1) {
            if ($('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).text() == 'No data available in table') {
                $('#' + tableElmntID + ' > tbody > tr > td').eq($('#' + tableElmntID + ' > tbody > tr').length - 1).remove();
                $('#' + tableElmntID).append(nwInptHtml);
            } else {
                $('#' + tableElmntID + ' > tbody > tr').eq(0).before(nwInptHtml);
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

function delAcaPosHldrs(rowIDAttrb) {
    var msgPart = "Position Holder";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_TrnsLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SbjctNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

function getAcaClasses(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaClassesSrchFor").val() === 'undefined' ? '%' : $("#acaClassesSrchFor").val();
    var srchIn = typeof $("#acaClassesSrchIn").val() === 'undefined' ? 'Both' : $("#acaClassesSrchIn").val();
    var pageNo = typeof $("#acaClassesPageNo").val() === 'undefined' ? 1 : $("#acaClassesPageNo").val();
    var limitSze = typeof $("#acaClassesDsplySze").val() === 'undefined' ? 10 : $("#acaClassesDsplySze").val();
    var sortBy = typeof $("#acaClassesSortBy").val() === 'undefined' ? '' : $("#acaClassesSortBy").val();
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

function enterKeyFuncAcaClasses(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaClasses(actionText, slctr, linkArgs);
    }
}

function getAcaClassesDet(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaClassesDetSrchFor").val() === 'undefined' ? '%' : $("#acaClassesDetSrchFor").val();
    var srchIn = typeof $("#acaClassesDetSrchIn").val() === 'undefined' ? 'Both' : $("#acaClassesDetSrchIn").val();
    var pageNo = typeof $("#acaClassesDetPageNo").val() === 'undefined' ? 1 : $("#acaClassesDetPageNo").val();
    var limitSze = typeof $("#acaClassesDetDsplySze").val() === 'undefined' ? 10 : $("#acaClassesDetDsplySze").val();
    var sortBy = typeof $("#acaClassesDetSortBy").val() === 'undefined' ? '' : $("#acaClassesDetSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    $("#acaClassesDetPageNo").val(pageNo);
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAcaClassesDet(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAcaClassesDet(actionText, slctr, linkArgs);
    }
}

function getOneAcaClassesForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaClassesDetailInfo';
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
    var lnkArgs = 'grp=15&typ=1&pg=4&vtyp=' + vwtype + '&sbmtdClassesID=' + tmpltID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID;

    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        var table2 = null;
        if (!$.fn.DataTable.isDataTable('#oneAcaClassesCrsesTable')) {
            table2 = $('#oneAcaClassesCrsesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaClassesCrsesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneAcaClassesSbjctsTable')) {
            var table3 = $('#oneAcaClassesSbjctsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaClassesSbjctsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#acaClassesForm').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $('#oneAcaClassesCrsesTable tbody').off('click');
        $('#oneAcaClassesCrsesTable tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table2.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
            var rndmNum = $(this).attr('id').split("_")[1];
            var pkeyID = typeof $('#oneAcaClassesCrsesRow' + rndmNum + '_CrseLnID').val() === 'undefined' ? '-1' : $('#oneAcaClassesCrsesRow' + rndmNum + '_CrseLnID').val();
            getOneAcaCrsesSbjctsForm(pkeyID, 3);
        });
        $('#oneAcaClassesCrsesTable tbody')
            .off('mouseenter', 'tr');
        $('#oneAcaClassesCrsesTable tbody')
            .on('mouseenter', 'tr', function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    table2.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
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


function getOneAcaCrsesSbjctsForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaCrseSbjctsDetailInfo';
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
    var lnkArgs = 'grp=15&typ=1&pg=4&vtyp=' + vwtype + '&sbmtdCrseLineID=' + tmpltID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID;

    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        if (!$.fn.DataTable.isDataTable('#oneAcaClassesSbjctsTable')) {
            var table3 = $('#oneAcaClassesSbjctsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaClassesSbjctsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#acaClassesForm').submit(function (e) {
            e.preventDefault();
            return false;
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

function saveAcaClassesForm(actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaClassesDetailInfo';
    }
    var acaClassesID = typeof $("#acaClassesID").val() === 'undefined' ? '-1' : $("#acaClassesID").val();
    var acaClassesLnkdLnkdDivID = typeof $("#acaClassesLnkdLnkdDivID").val() === 'undefined' ? '-1' : $("#acaClassesLnkdLnkdDivID").val();
    var acaClassesNxtClassID = typeof $("#acaClassesNxtClassID").val() === 'undefined' ? '-1' : $("#acaClassesNxtClassID").val();
    var acaClassesName = typeof $("#acaClassesName").val() === 'undefined' ? '' : $("#acaClassesName").val();
    var acaClassesDesc = typeof $("#acaClassesDesc").val() === 'undefined' ? '' : $("#acaClassesDesc").val();
    var acaClassesType = typeof $("#acaClassesType").val() === 'undefined' ? '' : $("#acaClassesType").val();
    var acaClassesGrpFcltrPosNm = typeof $("#acaClassesGrpFcltrPosNm").val() === 'undefined' ? '' : $("#acaClassesGrpFcltrPosNm").val();
    var acaClassesGrpRepPosNm = typeof $("#acaClassesGrpRepPosNm").val() === 'undefined' ? '' : $("#acaClassesGrpRepPosNm").val();
    var acaClassesSbjctFcltrPosNm = typeof $("#acaClassesSbjctFcltrPosNm").val() === 'undefined' ? '' : $("#acaClassesSbjctFcltrPosNm").val();
    var acaClassesIsEnbld = typeof $("input[name='acaClassesIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='acaClassesIsEnbld']:checked").val();

    var errMsg = "";
    if (acaClassesName.trim() === '' || acaClassesType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Group Type Name and Type cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var msgPart = 'Assessment Group';
    var dialog = bootbox.alert({
        title: 'Save ' + msgPart,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving  ' + msgPart + '...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 4);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('acaClassesID', acaClassesID);
    formData.append('acaClassesLnkdLnkdDivID', acaClassesLnkdLnkdDivID);
    formData.append('acaClassesName', acaClassesName);
    formData.append('acaClassesDesc', acaClassesDesc);
    formData.append('acaClassesType', acaClassesType);
    formData.append('acaClassesGrpFcltrPosNm', acaClassesGrpFcltrPosNm);
    formData.append('acaClassesGrpRepPosNm', acaClassesGrpRepPosNm);
    formData.append('acaClassesSbjctFcltrPosNm', acaClassesSbjctFcltrPosNm);
    formData.append('acaClassesNxtClassID', acaClassesNxtClassID);
    formData.append('acaClassesIsEnbld', acaClassesIsEnbld);

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
                            acaClassesID = data.acaClassesID;
                            if (destElmntID === 'acaClassesDetailInfo') {
                                getAcaClasses('', '#allmodules', 'grp=15&typ=1&pg=4&vtyp=0');
                            } else {
                                getOneAcaClassesForm(acaClassesID, 1, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID);
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

function insertNewAcaClassesRows(tableElmntID, position, inptHtml) {
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

function delAcaClasses(rowIDAttrb) {
    var msgPart = "Assessment Group";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_ClassesID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_ClassesID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_ClassesNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

function delAcaClassesLne(rowIDAttrb) {
    var msgPart = "Programme/Objective";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CrseLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CrseLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_CrseCode').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
                                    typ: 1,
                                    pg: 4,
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

function delAcaClassesSbjcts(rowIDAttrb) {
    var msgPart = "Subject/Task";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SbjctLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SbjctLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SbjctCode').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
                                    typ: 1,
                                    pg: 4,
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

function afterCrseSelect() {
    var classCrseCode = typeof $("#classCrseCode").val() === 'undefined' ? '' : $("#classCrseCode").val();
    var crseCode = classCrseCode.split(".")[0];
    var crseName = classCrseCode.split(".")[1];
    $('#classCrseCode').val(crseCode);
    $('#classCrseName').val(crseName);
}

function afterSbjctSelect() {
    var crseSbjctCode = typeof $("#crseSbjctCode").val() === 'undefined' ? '' : $("#crseSbjctCode").val();
    var sbjctCode = crseSbjctCode.split(".")[0];
    var sbjctName = crseSbjctCode.split(".")[1];
    $('#crseSbjctCode').val(sbjctCode);
    $('#crseSbjctName').val(sbjctName);
}

function getClassCrseForm(elementID, modalBodyID, titleElementID, formElementID,
    tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, sbmtdClassID) {
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
                    var rndmNum = tRowElementID.split("_")[1];
                    var rowPrfxNm = tRowElementID.split("_")[0];
                    /*var $tds = $('#' + tRowElementID).find('td');*/
                    $('#classCoursePkeyID').val(pKeyID);
                    $('#sbmtdClassID').val(sbmtdClassID);
                    $('#classCrseID').val($('#' + rowPrfxNm + rndmNum + '_CrseID').val());
                    $('#classCrseCode').val($('#' + rowPrfxNm + rndmNum + '_CrseCode').val());
                    $('#classCrseName').val($('#' + rowPrfxNm + rndmNum + '_CrseName').val());
                    var isEnbld = $('#' + rowPrfxNm + rndmNum + '_IsEnbld').val();

                    if (isEnbld === '1' || isEnbld === 'YES') {
                        document.getElementById("classCrseIsEnbldNO").checked = false;
                        document.getElementById("classCrseIsEnbldYES").checked = true;
                    } else {
                        document.getElementById("classCrseIsEnbldYES").checked = false;
                        document.getElementById("classCrseIsEnbldNO").checked = true;
                    }
                    //alert(isEnbld);
                    $('#classCrseMinWeight').val($('#' + rowPrfxNm + rndmNum + '_MinWeight').val());
                    $('#classCrseMaxWeight').val($('#' + rowPrfxNm + rndmNum + '_MaxWeight').val());
                }
                $('#' + elementID).off('show.bs.modal');
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
        xmlhttp.send("grp=15&typ=1&pg=4&vtyp=" + vtyp + "&classCoursePkeyID=" + pKeyID + "&sbmtdClassID=" + sbmtdClassID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveClassCrseForm(elementID, pKeyID, sbmtdClassID, tRowElementID) {
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var classCrseID = typeof $("#classCrseID").val() === 'undefined' ? '-1' : $("#classCrseID").val();
        var classCrseCode = typeof $("#classCrseCode").val() === 'undefined' ? '' : $("#classCrseCode").val();
        var classCrseName = typeof $("#classCrseName").val() === 'undefined' ? '' : $("#classCrseName").val();
        var classCrseIsEnbld = typeof $("input[name='classCrseIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='classCrseIsEnbld']:checked").val();
        var classCrseMinWeight = typeof $("#classCrseMinWeight").val() === 'undefined' ? '0' : $("#classCrseMinWeight").val();
        var classCrseMaxWeight = typeof $("#classCrseMaxWeight").val() === 'undefined' ? '0' : $("#classCrseMaxWeight").val();
        var errMsg = "";
        if (classCrseCode.trim().length <= 0 || classCrseName.trim().length <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Code/Name cannot be empty!</span></p>';
        }
        if (rhotrim(errMsg, '; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg
            });
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("RHO-ERROR") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText
                    });
                } else {
                    if (pKeyID <= 0) {
                        $('#oneAcaClassesCrsesTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        var rowPrfxNm = tRowElementID.split("_")[0];
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(classCrseCode + '.' + classCrseName);

                        $('#' + rowPrfxNm + rndmNum + '_CrseID').val(classCrseID);
                        $('#' + rowPrfxNm + rndmNum + '_CrseCode').val(classCrseCode);
                        $('#' + rowPrfxNm + rndmNum + '_CrseName').val(classCrseName);
                        $('#' + rowPrfxNm + rndmNum + '_LineName').val(classCrseCode + '.' + classCrseName);
                        $('#' + rowPrfxNm + rndmNum + '_IsEnbld').val(classCrseIsEnbld);
                        $('#' + rowPrfxNm + rndmNum + '_MinWeight').val(classCrseMinWeight);
                        $('#' + rowPrfxNm + rndmNum + '_MaxWeight').val(classCrseMaxWeight);
                    }
                    $('#' + elementID).modal('hide');
                    getOneAcaClassesForm(sbmtdClassID, 1, 'PasteDirect', 'acaClassesDetailInfo', '', '', '');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=15&typ=1&pg=4&q=UPDATE&actyp=11" +
            "&classCrseID=" + classCrseID +
            "&classCrseCode=" + classCrseCode +
            "&classCrseName=" + classCrseName +
            "&classCrseIsEnbld=" + classCrseIsEnbld +
            "&classCrseMinWeight=" + classCrseMinWeight +
            "&classCrseMaxWeight=" + classCrseMaxWeight +
            "&classCoursePkeyID=" + pKeyID +
            "&sbmtdClassID=" + sbmtdClassID);
    });
}


function getClassCrseSbjctForm(elementID, modalBodyID, titleElementID, formElementID,
    tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, sbmtdCrseID, sbmtdClassID) {
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
                    var rndmNum = tRowElementID.split("_")[1];
                    var rowPrfxNm = tRowElementID.split("_")[0];
                    /*var $tds = $('#' + tRowElementID).find('td');*/
                    $('#courseSbjctPkeyID').val(pKeyID);
                    $('#sbmtdCrseID').val(sbmtdCrseID);
                    $('#sbmtdClassID').val(sbmtdClassID);
                    $('#crseSbjctID').val($('#' + rowPrfxNm + rndmNum + '_SbjctID').val());
                    $('#crseSbjctCode').val($('#' + rowPrfxNm + rndmNum + '_SbjctCode').val());
                    $('#crseSbjctName').val($('#' + rowPrfxNm + rndmNum + '_SbjctNm').val());
                    var isEnbld = $('#' + rowPrfxNm + rndmNum + '_IsEnbld').val();

                    if (isEnbld === '1' || isEnbld === 'YES') {
                        document.getElementById("crseSbjctIsEnbldNO").checked = false;
                        document.getElementById("crseSbjctIsEnbldYES").checked = true;
                    } else {
                        document.getElementById("crseSbjctIsEnbldYES").checked = false;
                        document.getElementById("crseSbjctIsEnbldNO").checked = true;
                    }
                    //alert(isEnbld);
                    $('#crseSbjct_Type').val($('#' + rowPrfxNm + rndmNum + '_SbjctType').val());
                    $('#crseSbjct_Weight').val($('#' + rowPrfxNm + rndmNum + '_Weight').val());
                }
                $('#' + elementID).off('show.bs.modal');
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
        xmlhttp.send("grp=15&typ=1&pg=4&vtyp=" + vtyp + "&crseSbjctPkeyID=" + pKeyID + "&sbmtdCrseID=" + sbmtdCrseID +
            "&sbmtdClassID=" + sbmtdClassID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveClassCrseSbjctForm(elementID, pKeyID, sbmtdCrseID, sbmtdClassID, tRowElementID) {
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var crseSbjctID = typeof $("#crseSbjctID").val() === 'undefined' ? '-1' : $("#crseSbjctID").val();
        var crseSbjctCode = typeof $("#crseSbjctCode").val() === 'undefined' ? '' : $("#crseSbjctCode").val();
        var crseSbjctName = typeof $("#crseSbjctName").val() === 'undefined' ? '' : $("#crseSbjctName").val();
        var crseSbjctIsEnbld = typeof $("input[name='crseSbjctIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='crseSbjctIsEnbld']:checked").val();
        var crseSbjct_Type = typeof $("#crseSbjct_Type").val() === 'undefined' ? '' : $("#crseSbjct_Type").val();
        var crseSbjct_Weight = typeof $("#crseSbjct_Weight").val() === 'undefined' ? '0' : $("#crseSbjct_Weight").val();
        var crseSbjct_PeriodType = typeof $("#crseSbjct_PeriodType").val() === 'undefined' ? '' : $("#crseSbjct_PeriodType").val();
        var crseSbjct_PeriodNum = typeof $("#crseSbjct_PeriodNum").val() === 'undefined' ? '0' : $("#crseSbjct_PeriodNum").val();
        var errMsg = "";
        if (crseSbjctCode.trim().length <= 0 || crseSbjctName.trim().length <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Code/Name cannot be empty!</span></p>';
        }
        if (rhotrim(errMsg, '; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg
            });
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("RHO-ERROR") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText
                    });
                } else {
                    if (pKeyID <= 0) {
                        $('#oneAcaClassesSbjctsTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        var rowPrfxNm = tRowElementID.split("_")[0];
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(crseSbjctCode + '.' + crseSbjctName);

                        $('#' + rowPrfxNm + rndmNum + '_SbjctID').val(crseSbjctID);
                        $('#' + rowPrfxNm + rndmNum + '_SbjctCode').val(crseSbjctCode);
                        $('#' + rowPrfxNm + rndmNum + '_SbjctNm').val(crseSbjctName);
                        //$('#' + rowPrfxNm + rndmNum + '_LineName').val(crseSbjctCode + '.' + crseSbjctName);
                        $('#' + rowPrfxNm + rndmNum + '_IsEnbld').val(crseSbjctIsEnbld);
                        $('#' + rowPrfxNm + rndmNum + '_SbjctType').val(crseSbjct_Type);
                        $('#' + rowPrfxNm + rndmNum + '_Weight').val(crseSbjct_Weight);
                        $('#' + rowPrfxNm + rndmNum + '_PrdTyp').val(crseSbjct_PeriodType);
                        $('#' + rowPrfxNm + rndmNum + '_PrdNum').val(crseSbjct_PeriodNum);
                    }
                    $('#' + elementID).modal('hide');
                    getOneAcaClassesForm(sbmtdClassID, 1, 'PasteDirect', 'acaClassesDetailInfo', '', '', '');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=15&typ=1&pg=4&q=UPDATE&actyp=12" +
            "&crseSbjctID=" + crseSbjctID +
            "&crseSbjctCode=" + crseSbjctCode +
            "&crseSbjctName=" + crseSbjctName +
            "&crseSbjctIsEnbld=" + crseSbjctIsEnbld +
            "&crseSbjct_Weight=" + crseSbjct_Weight +
            "&crseSbjct_Type=" + crseSbjct_Type +
            "&crseSbjct_PeriodType=" + crseSbjct_PeriodType +
            "&crseSbjct_PeriodNum=" + crseSbjct_PeriodNum +
            "&crseSbjctPkeyID=" + pKeyID +
            "&sbmtdCrseID=" + sbmtdCrseID +
            "&sbmtdClassID=" + sbmtdClassID);
    });
}

function onAcaPrsnFilterByChange() {
    var acaRgstratnFilterBy = typeof $("#acaRgstratnFilterBy").val() === 'undefined' ? '' : $("#acaRgstratnFilterBy").val();
    var acaRgstratnFilterByVal = typeof $("#acaRgstratnFilterByVal").val() === 'undefined' ? '' : $("#acaRgstratnFilterByVal").val();
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
        formData.append('dataAdminFilterBy', acaRgstratnFilterBy);
        formData.append('dataAdminFilterByVal', acaRgstratnFilterByVal);
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
                    $("#acaRgstratnFilterByVal").empty();
                    for (var i = 0; i < options.length; i++) {
                        var defaultSlctd = false;
                        var isSlctd = false;
                        if (acaRgstratnFilterByVal === options[i]) {
                            defaultSlctd = true;
                            isSlctd = true;
                        }
                        var o = new Option(options[i], options[i], defaultSlctd, isSlctd);
                        /// jquerify the DOM object 'o' so we can use the html method
                        $(o).html(options[i]);
                        $("#acaRgstratnFilterByVal").append(o);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function onAcaPrsnFltrValChange() {
    getAcaRgstratn('', '#allmodules', 'grp=15&typ=1&pg=3&vtyp=0');
}

function getAcaRgstratn(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#acaRgstratnSrchFor").val() === 'undefined' ? '%' : $("#acaRgstratnSrchFor").val();
    var srchIn = typeof $("#acaRgstratnSrchIn").val() === 'undefined' ? 'Both' : $("#acaRgstratnSrchIn").val();
    var pageNo = typeof $("#acaRgstratnPageNo").val() === 'undefined' ? 1 : $("#acaRgstratnPageNo").val();
    var limitSze = typeof $("#acaRgstratnDsplySze").val() === 'undefined' ? 30 : $("#acaRgstratnDsplySze").val();
    var sortBy = typeof $("#acaRgstratnSortBy").val() === 'undefined' ? '' : $("#acaRgstratnSortBy").val();
    var acaRgstratnFilterBy = typeof $("#acaRgstratnFilterBy").val() === 'undefined' ? '' : $("#acaRgstratnFilterBy").val();
    var acaRgstratnFilterByVal = typeof $("#acaRgstratnFilterByVal").val() === 'undefined' ? '' : $("#acaRgstratnFilterByVal").val();
    var qShwCrntOnly = $('#acaRgstratnShwCrntOnly:checked').length > 0;
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
        "&acaRgstratnFilterBy=" + acaRgstratnFilterBy +
        "&acaRgstratnFilterByVal=" + acaRgstratnFilterByVal + "&qShwCrntOnly=" + qShwCrntOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAcaRgstratn(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode === 13) {
        getAcaRgstratn(actionText, slctr, linkArgs);
    }
}

function getOneAcaRgstratnForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaRgstratnDetailInfo';
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
    var qShwCrntOnly = $('#acaRgstratnShwCrntOnly:checked').length > 0;
    var acaRgstratnPrsID = typeof $("#acaRgstratnPrsID").val() === 'undefined' ? '-1' : $("#acaRgstratnPrsID").val();
    if (tmpltID > 0) {
        acaRgstratnPrsID = tmpltID;
    }
    var lnkArgs = 'grp=15&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdAcaSttngsPrsnID=' + acaRgstratnPrsID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID + '&qShwCrntOnly=' + qShwCrntOnly;

    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        var table2 = null;
        if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnCrsesTable')) {
            table2 = $('#oneAcaRgstratnCrsesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaRgstratnCrsesTable').wrap('<div class="dataTables_scroll"/>');
        }
        if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnSbjctsTable')) {
            var table3 = $('#oneAcaRgstratnSbjctsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaRgstratnSbjctsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#acaRgstratnForm').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $('#oneAcaRgstratnCrsesTable tbody').off('click');
        $('#oneAcaRgstratnCrsesTable tbody').on('click', 'tr', function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                table2.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
            var rndmNum = $(this).attr('id').split("_")[1];
            var pkeyID = typeof $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val() === 'undefined' ? '-1' : $('#oneAcaRgstratnCrsesRow' + rndmNum + '_LnID').val();
            getOneAcaRgstratnSbjctsForm(pkeyID, 3);
        });

        $('#oneAcaRgstratnCrsesTable tbody')
            .off('mouseenter', 'tr');
        $('#oneAcaRgstratnCrsesTable tbody')
            .on('mouseenter', 'tr', function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    table2.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
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

function getOneAcaRgstratnSbjctsForm(tmpltID, vwtype, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'PasteDirect';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaRgstratnSbjctsDetailInfo';
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
    var lnkArgs = 'grp=15&typ=1&pg=3&vtyp=' + vwtype + '&sbmtdAcaSttngsID=' + tmpltID +
        '&actionTxt=' + actionTxt + '&destElmntID=' + destElmntID +
        '&titleMsg=' + titleMsg + '&titleElementID=' + titleElementID + '&modalBodyID=' + modalBodyID;

    doAjaxWthCallBck(lnkArgs, destElmntID, actionTxt, titleMsg, titleElementID, modalBodyID, function () {
        if (!$.fn.DataTable.isDataTable('#oneAcaRgstratnSbjctsTable')) {
            var table3 = $('#oneAcaRgstratnSbjctsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAcaRgstratnSbjctsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#acaRgstratnForm').submit(function (e) {
            e.preventDefault();
            return false;
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

function saveAcaRgstratnForm(actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID) {
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = 'acaClassesDetailInfo';
    }
    var acaClassesID = typeof $("#acaClassesID").val() === 'undefined' ? '-1' : $("#acaClassesID").val();
    var acaClassesLnkdLnkdDivID = typeof $("#acaClassesLnkdLnkdDivID").val() === 'undefined' ? '-1' : $("#acaClassesLnkdLnkdDivID").val();
    var acaClassesName = typeof $("#acaClassesName").val() === 'undefined' ? '' : $("#acaClassesName").val();
    var acaClassesDesc = typeof $("#acaClassesDesc").val() === 'undefined' ? '' : $("#acaClassesDesc").val();
    var acaClassesType = typeof $("#acaClassesType").val() === 'undefined' ? '' : $("#acaClassesType").val();
    var acaClassesIsEnbld = typeof $("input[name='acaClassesIsEnbld']:checked").val() === 'undefined' ? 'NO' : $("input[name='acaClassesIsEnbld']:checked").val();

    var errMsg = "";
    if (acaClassesName.trim() === '' || acaClassesType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Group Type Name and Type cannot be empty!</span></p>';
    }
    if (rhotrim(errMsg, '; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            /*size: 'small',*/
            message: errMsg
        });
        return false;
    }
    var msgPart = 'Assessment Group';
    var dialog = bootbox.alert({
        title: 'Save ' + msgPart,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving  ' + msgPart + '...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 4);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('acaClassesID', acaClassesID);
    formData.append('acaClassesLnkdLnkdDivID', acaClassesLnkdLnkdDivID);
    formData.append('acaClassesName', acaClassesName);
    formData.append('acaClassesDesc', acaClassesDesc);
    formData.append('acaClassesType', acaClassesType);
    formData.append('acaClassesIsEnbld', acaClassesIsEnbld);

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
                            acaClassesID = data.acaClassesID;
                            if (destElmntID === 'acaClassesDetailInfo') {
                                getAcaClasses('', '#allmodules', 'grp=15&typ=1&pg=4&vtyp=0');
                            } else {
                                getOneAcaClassesForm(acaClassesID, 1, actionTxt, destElmntID, titleMsg, titleElementID, modalBodyID);
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

function getAcaRgstratnClassForm(elementID, modalBodyID, titleElementID, formElementID,
    tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, sbmtdRgstrPersonID) {
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
                    var rndmNum = tRowElementID.split("_")[1];
                    var rowPrfxNm = tRowElementID.split("_")[0];
                    /*var $tds = $('#' + tRowElementID).find('td');*/
                    $('#acaRgstrClassPkeyID').val(pKeyID);
                    $('#sbmtdRgstrPersonID').val(sbmtdRgstrPersonID);
                    $('#acaRgstrClassID').val($('#' + rowPrfxNm + rndmNum + '_ClassID').val());
                    $('#acaRgstrCrseID').val($('#' + rowPrfxNm + rndmNum + '_CrseID').val());
                    $('#acaRgstrPrdID').val($('#' + rowPrfxNm + rndmNum + '_PrdID').val());
                    $('#acaRgstrClassNm').val($('#' + rowPrfxNm + rndmNum + '_ClassNm').val());
                    $('#acaRgstrCrseName').val($('#' + rowPrfxNm + rndmNum + '_CrseName').val());
                    $('#acaRgstrPrdName').val($('#' + rowPrfxNm + rndmNum + '_PrdNm').val());
                }
                $('#' + elementID).off('show.bs.modal');
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
        xmlhttp.send("grp=15&typ=1&pg=3&vtyp=" + vtyp + "&acaRgstrClassPkeyID=" + pKeyID +
            "&sbmtdRgstrPersonID=" + sbmtdRgstrPersonID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveAcaRgstratnClassForm(elementID, pKeyID, sbmtdRgstrPersonID, tRowElementID) {
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var acaRgstrClassID = typeof $("#acaRgstrClassID").val() === 'undefined' ? '-1' : $("#acaRgstrClassID").val();
        var acaRgstrCrseID = typeof $("#acaRgstrCrseID").val() === 'undefined' ? '-1' : $("#acaRgstrCrseID").val();
        var acaRgstrPrdID = typeof $("#acaRgstrPrdID").val() === 'undefined' ? '-1' : $("#acaRgstrPrdID").val();
        var acaRgstrClassNm = typeof $("#acaRgstrClassNm").val() === 'undefined' ? '' : $("#acaRgstrClassNm").val();
        var acaRgstrCrseName = typeof $("#acaRgstrCrseName").val() === 'undefined' ? '' : $("#acaRgstrCrseName").val();
        var acaRgstrPrdName = typeof $("#acaRgstrPrdName").val() === 'undefined' ? '' : $("#acaRgstrPrdName").val();
        var errMsg = "";
        if (Number(acaRgstrClassID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            Number(acaRgstrCrseID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            Number(acaRgstrPrdID.replace(/[^-?0-9\.]/g, '')) <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Assessment Group, Programme and Period cannot be empty!</span></p>';
        }
        if (rhotrim(errMsg, '; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg
            });
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("RHO-ERROR") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText
                    });
                } else {
                    if (pKeyID <= 0) {
                        $('#oneAcaRgstratnCrsesTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        var rowPrfxNm = tRowElementID.split("_")[0];
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(acaRgstrClassNm);
                        $tds.eq(2).text(acaRgstrCrseName);
                        $tds.eq(3).text(acaRgstrPrdName);
                        $('#' + rowPrfxNm + rndmNum + '_ClassID').val(acaRgstrClassID);
                        $('#' + rowPrfxNm + rndmNum + '_CrseID').val(acaRgstrCrseID);
                        $('#' + rowPrfxNm + rndmNum + '_PrdID').val(acaRgstrPrdID);
                        $('#' + rowPrfxNm + rndmNum + '_ClassNm').val(acaRgstrClassNm);
                        $('#' + rowPrfxNm + rndmNum + '_CrseName').val(acaRgstrCrseName);
                        $('#' + rowPrfxNm + rndmNum + '_PrdNm').val(acaRgstrPrdName);
                    }
                    $('#' + elementID).modal('hide');
                    getOneAcaRgstratnForm(sbmtdRgstrPersonID, 1, 'PasteDirect', 'acaRgstratnDetailInfo', '', '', '');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("grp=15&typ=1&pg=3&q=UPDATE&actyp=11" +
            "&acaRgstrClassID=" + acaRgstrClassID +
            "&acaRgstrCrseID=" + acaRgstrCrseID +
            "&acaRgstrPrdID=" + acaRgstrPrdID +
            "&acaRgstrClassNm=" + acaRgstrClassNm +
            "&acaRgstrCrseName=" + acaRgstrCrseName +
            "&acaRgstrPrdName=" + acaRgstrPrdName +
            "&acaRgstrClassPkeyID=" + pKeyID +
            "&sbmtdRgstrPersonID=" + sbmtdRgstrPersonID);
    });
}

function getAcaRgstratnSbjctForm(elementID, modalBodyID, titleElementID, formElementID,
    tRowElementID, formTitle, vtyp, addOrEdit, pKeyID, acaRgstratnSttngsID) {
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
                    var rndmNum = tRowElementID.split("_")[1];
                    var rowPrfxNm = tRowElementID.split("_")[0];
                    /*var $tds = $('#' + tRowElementID).find('td');*/
                    $('#sttngsSbjctPkeyID').val(pKeyID);
                    $('#acaRgstratnSttngsID').val(acaRgstratnSttngsID);
                    $('#sttngsSbjctID').val($('#' + rowPrfxNm + rndmNum + '_SbjctID').val());
                    $('#sttngsSbjctName').val($('#' + rowPrfxNm + rndmNum + '_SbjctNm').val());
                }
                $('#' + elementID).off('show.bs.modal');
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
        xmlhttp.send("grp=15&typ=1&pg=3&vtyp=" + vtyp + "&crseSbjctPkeyID=" + pKeyID +
            "&acaRgstratnSttngsID=" + acaRgstratnSttngsID + "&tRowElmntNm=" + tRowElementID);
    });
}

function saveAcaRgstratnSbjctForm(elementID, pKeyID, acaRgstratnSttngsID, tRowElementID) {
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {
        var sttngsSbjctID = typeof $("#sttngsSbjctID").val() === 'undefined' ? '-1' : $("#sttngsSbjctID").val();
        var sttngsSbjctName = typeof $("#sttngsSbjctName").val() === 'undefined' ? '' : $("#sttngsSbjctName").val();
        var errMsg = "";
        if (sttngsSbjctName.trim().length <= 0) {
            errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;color:red;">Task/Subject cannot be empty!</span></p>';
        }
        if (rhotrim(errMsg, '; ') !== '') {
            bootbox.alert({
                title: 'System Alert!',
                size: 'small',
                message: errMsg
            });
            return false;
        }
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
                $body.removeClass("mdlloadingDiag");
                $body.removeClass("mdlloading");
                if (xmlhttp.responseText.indexOf("RHO-ERROR") !== -1) {
                    /*Do Nothing*/
                    bootbox.alert({
                        title: 'System Alert!',
                        size: 'small',
                        message: xmlhttp.responseText
                    });
                } else {
                    if (pKeyID <= 0) {
                        $('#oneAcaRgstratnSbjctsTable').append(xmlhttp.responseText);
                    } else {
                        var rndmNum = tRowElementID.split("_")[1];
                        var rowPrfxNm = tRowElementID.split("_")[0];
                        var $tds = $('#' + tRowElementID).find('td');
                        $tds.eq(1).text(sttngsSbjctName);
                        $('#' + rowPrfxNm + rndmNum + '_SbjctID').val(sttngsSbjctID);
                        $('#' + rowPrfxNm + rndmNum + '_SbjctNm').val(sttngsSbjctName);
                    }
                    $('#' + elementID).modal('hide');
                    //getOneAcaRgstratnForm(sbmtdRgstrPersonID, 1, 'PasteDirect', 'acaRgstratnDetailInfo', '', '', '');
                }
            }
        };
        xmlhttp.open("POST", "index.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send("grp=15&typ=1&pg=3&q=UPDATE&actyp=12" +
            "&sttngsSbjctID=" + sttngsSbjctID +
            "&sttngsSbjctName=" + sttngsSbjctName +
            "&sttngsSbjctPkeyID=" + pKeyID +
            "&acaRgstratnSttngsID=" + acaRgstratnSttngsID);
    });
}

function delAcaRgstratnLne(rowIDAttrb) {
    var msgPart = "Course/Objective";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_LnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_LnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_ClassNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

function delAcaRgstratnSbjcts(rowIDAttrb) {
    var msgPart = "Subject/Task";
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SbjctLnID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SbjctLnID').val();
        pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SbjctNm').val();
    }
    var dialog = bootbox.confirm({
        title: 'Delete ' + msgPart + '?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this ' + msgPart + '?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete ' + msgPart + '?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting ' + msgPart + '...Please Wait...</p>',
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
                                    grp: 15,
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

//ASSESSMENT SHEETS
function getAssessSheets(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#assessSheetsSrchFor").val() === 'undefined' ? '%' : $("#assessSheetsSrchFor").val();
    var srchIn = typeof $("#assessSheetsSrchIn").val() === 'undefined' ? 'Both' : $("#assessSheetsSrchIn").val();
    var pageNo = typeof $("#assessSheetsPageNo").val() === 'undefined' ? 1 : $("#assessSheetsPageNo").val();
    var limitSze = typeof $("#assessSheetsDsplySze").val() === 'undefined' ? 10 : $("#assessSheetsDsplySze").val();
    var sortBy = typeof $("#assessSheetsSortBy").val() === 'undefined' ? '' : $("#assessSheetsSortBy").val();
    var qShwUsrOnly = $('#assessSheetsShwUsrOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwSelfOnly=" + qShwUsrOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncAssessSheets(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAssessSheets(actionText, slctr, linkArgs);
    }
}

function getAssessSheetDets(actionText, slctr, linkArgs, assessSheetID) {
    if (typeof assessSheetID === 'undefined' || assessSheetID === null) {
        assessSheetID = -1;
    }
    var tstassessSheetID = "a" + assessSheetID;
    if (Number(tstassessSheetID.replace(/[^-?0-9\.]/g, '')) > 0) {
        $("#assessSbmtdSheetID").val(assessSheetID);
        assessSbmtdSheetID = assessSheetID;
    } else {
        var assessSheetID = typeof $("#assessSheetID").val() === 'undefined' ? '-1' : $("#assessSheetID").val();
        var assessSheetNm = typeof $("#assessSheetNm").val() === 'undefined' ? '' : $("#assessSheetNm").val();
        var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
        var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
        if (Number(assessSheetID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            (Number(assessSheetID.replace(/[^-?0-9\.]/g, '')) !== Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) &&
                Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) > 0)) {
            $("#assessSheetID").val(assessSbmtdSheetID);
            $("#assessSheetNm").val(assessSbmtdSheetNm);
            assessSheetID = assessSbmtdSheetID;
            assessSheetNm = assessSbmtdSheetNm;
        }
        if (Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            (Number(assessSheetID.replace(/[^-?0-9\.]/g, '')) !== Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) &&
                Number(assessSheetID.replace(/[^-?0-9\.]/g, '')) > 0)) {
            $("#assessSbmtdSheetID").val(assessSheetID);
            $("#assessSbmtdSheetNm").val(assessSheetNm);
            assessSbmtdSheetID = assessSheetID;
            assessSbmtdSheetNm = assessSheetNm;
        }
        if (linkArgs.indexOf("&assessSbmtdSheetNm=") === -1) {
            linkArgs = linkArgs + "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm;
        }
    }
    var assessSheetHdnTabNm = typeof $("#assessSheetHdnTabNm").val() === 'undefined' ? '' : $("#assessSheetHdnTabNm").val();
    if (assessSheetHdnTabNm === 'asShtDetlsTrans') {
        getAssessShtHdr('', '#asShtDetlsTrans', 'grp=15&typ=1&pg=2&vtyp=2');
    } else if (assessSheetHdnTabNm === 'asShtDetlsPMRecs') {
        getAssessShtHdr('', '#asShtDetlsPMRecs', 'grp=15&typ=1&pg=2&vtyp=3');
    } else {
        openATab(slctr, linkArgs);
    }
}

function enterKeyFuncAssessSheetDets(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAssessSheetDets(actionText, slctr, linkArgs);
    }
}

function getAssessShtHdr(actionText, slctr, linkArgs, assessSheetID) {
    if (typeof assessSheetID === 'undefined' || assessSheetID === null) {
        assessSheetID = -1;
    }
    var srchFor = typeof $("#assessShtHdrSrchFor").val() === 'undefined' ? '%' : $("#assessShtHdrSrchFor").val();
    var srchIn = typeof $("#assessShtHdrSrchIn").val() === 'undefined' ? 'Both' : $("#assessShtHdrSrchIn").val();
    var pageNo = typeof $("#assessShtHdrPageNo").val() === 'undefined' ? 1 : $("#assessShtHdrPageNo").val();
    var limitSze = typeof $("#assessShtHdrDsplySze").val() === 'undefined' ? 15 : $("#assessShtHdrDsplySze").val();
    var sortBy = typeof $("#assessShtHdrSortBy").val() === 'undefined' ? '' : $("#assessShtHdrSortBy").val();
    var qShwUsrOnly = $('#assessShtHdrShwUsrOnly:checked').length > 0;
    var qShwNonZeroOnly = $('#assessShtHdrNonZeroOnly:checked').length > 0;
    var assessSheetID1 = typeof $("#assessSheetID").val() === 'undefined' ? '-1' : $("#assessSheetID").val();
    var assessSheetNm1 = typeof $("#assessSheetNm").val() === 'undefined' ? '' : $("#assessSheetNm").val();
    var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
    var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
    if (Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        assessSbmtdSheetID = assessSheetID1;
        assessSbmtdSheetNm = assessSheetNm1;
    }
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwNonZeroOnly=" + qShwNonZeroOnly;
    if (assessSheetID <= 0) {
        linkArgs = linkArgs + "&assessSbmtdSheetID=" + assessSbmtdSheetID +
            "&assessSbmtdSheetNm=" + assessSbmtdSheetNm;
    }
    $('#myFormsModalLg').modal('hide');
    openATab(slctr, linkArgs);
}

function enterKeyFuncAssessShtHdr(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAssessShtHdr(actionText, slctr, linkArgs);
    }
}

function vldtAssessColNumFld(rowIDAttrb) {
    var classes = $("#" + rowIDAttrb).attr('class');
    var ln_ColNum = Number(rowIDAttrb.split("-")[1].replace(/[^-?0-9\.]/g, ''));
    var ln_AsessScore = typeof $("#" + rowIDAttrb).val() === 'undefined' ? '' : $("#" + rowIDAttrb).val();
    if (classes.indexOf("assesScoreNum") !== -1) {
        ln_AsessScore = ln_AsessScore.replace(/[^-?0-9\.]/g, '');
        var minValue = Number($("#" + rowIDAttrb).attr('min-rhodata').replace(/[^-?0-9\.]/g, ''));
        var maxValue = Number($("#" + rowIDAttrb).attr('max-rhodata').replace(/[^-?0-9\.]/g, ''));

        if (Number(ln_AsessScore) < minValue ||
            Number(ln_AsessScore) > maxValue) {
            $("#" + rowIDAttrb).addClass('rho-error');
        } else {
            $("#" + rowIDAttrb).removeClass('rho-error');
        }
    }
}

function saveAssessShtHdrForm(shdSbmt) {
    if (typeof shdSbmt === 'undefined' || shdSbmt === null) {
        shdSbmt = 0;
    }
    var assessShtHdrID = typeof $("#assessShtHdrID").val() === 'undefined' ? '-1' : $("#assessShtHdrID").val();
    var assessShtHdrName = typeof $("#assessShtHdrName").val() === 'undefined' ? '' : $("#assessShtHdrName").val();
    var assessShtHdrDesc = typeof $("#assessShtHdrDesc").val() === 'undefined' ? '' : $("#assessShtHdrDesc").val();

    var assessShtHdrTypeID = typeof $("#assessShtHdrTypeID").val() === 'undefined' ? '-1' : $("#assessShtHdrTypeID").val();
    var assessShtHdrTypeNm = typeof $("#assessShtHdrTypeNm").val() === 'undefined' ? '' : $("#assessShtHdrTypeNm").val();
    var assessShtHdrPeriodID = typeof $("#assessShtHdrPeriodID").val() === 'undefined' ? '-1' : $("#assessShtHdrPeriodID").val();
    var assessShtHdrPeriodNm = typeof $("#assessShtHdrPeriodNm").val() === 'undefined' ? '' : $("#assessShtHdrPeriodNm").val();
    var assessShtHdrPrsnID = typeof $("#assessShtHdrPrsnID").val() === 'undefined' ? '-1' : $("#assessShtHdrPrsnID").val();
    var assessShtHdrPrsnNm = typeof $("#assessShtHdrPrsnNm").val() === 'undefined' ? '' : $("#assessShtHdrPrsnNm").val();

    var assessShtHdrAsdPrsID = typeof $("#assessShtHdrAsdPrsID").val() === 'undefined' ? '-1' : $("#assessShtHdrAsdPrsID").val();
    var assessShtHdrAsdPrsNm = typeof $("#assessShtHdrAsdPrsNm").val() === 'undefined' ? '' : $("#assessShtHdrAsdPrsNm").val();

    var assessShtHdrClassID = typeof $("#assessShtHdrClassID").val() === 'undefined' ? '-1' : $("#assessShtHdrClassID").val();
    var assessShtHdrClassNm = typeof $("#assessShtHdrClassNm").val() === 'undefined' ? '' : $("#assessShtHdrClassNm").val();
    var assessShtHdrCrseID = typeof $("#assessShtHdrCrseID").val() === 'undefined' ? '-1' : $("#assessShtHdrCrseID").val();
    var assessShtHdrCrseNm = typeof $("#assessShtHdrCrseNm").val() === 'undefined' ? '' : $("#assessShtHdrCrseNm").val();
    var assessShtHdrSbjctID = typeof $("#assessShtHdrSbjctID").val() === 'undefined' ? '-1' : $("#assessShtHdrSbjctID").val();
    var assessShtHdrSbjctNm = typeof $("#assessShtHdrSbjctNm").val() === 'undefined' ? '' : $("#assessShtHdrSbjctNm").val();
    var assessShtHdrStatus = typeof $("#assessShtHdrStatus").val() === 'undefined' ? 'Open for Editing' : $("#assessShtHdrStatus").val();

    var errMsg = "";
    if (assessShtHdrName.trim() === '' || assessShtHdrDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Assessment Sheet Name and Description cannot be empty!</span></p>';
    }
    if (assessShtHdrTypeNm.trim() === '' || assessShtHdrPeriodNm.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Assessment Sheet Type and Period cannot be empty!</span></p>';
    }
    if (Number(assessShtHdrClassID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
        Number(assessShtHdrCrseID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
        Number(assessShtHdrSbjctID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Assessment Sheet Group, Programme and Subject cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdHdrFtrValues = "";
    var slctdDetLineValues = "";

    $(".assessShtHdrFtrVal").each(function () {
        var classes = $(this).attr('class');
        var key = $(this).attr('id').split("_")[1];
        var val = $(this).val();
        if (classes.indexOf("assesScoreNum") !== -1) {
            val = $(this).val().replace(/[^-?0-9\.]/g, '');
            $(this).val(val);
        }
        slctdHdrFtrValues = slctdHdrFtrValues +
            key.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
            val.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
    });

    var tempColVals = "";
    var tempCntr = 0;
    $('#oneAssessSheetTransLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_LineID = $('#assessShtColRow' + rndmNum + '_LineID').val();
                var ln_PrsnID = $('#assessShtColRow' + rndmNum + '_PrsnID').val();
                var ln_PrsnNm = $('#assessShtColRow' + rndmNum + '_PrsnNm').val();
                var ln_AsessScores = new Array(50);
                var ln_ColNum = 1;
                if (isVld === true) {
                    slctdDetLineValues = slctdDetLineValues +
                        ln_LineID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        ln_PrsnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~";
                    tempColVals = "";
                    tempCntr = 0;
                    $(".assessShtColRow" + rndmNum).each(function () {
                        var classes = $(this).attr('class');
                        ln_ColNum = Number($(this).attr('id').split("-")[1].replace(/[^-?0-9\.]/g, ''));
                        ln_AsessScores[ln_ColNum - 1] = typeof $('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val() === 'undefined' ? '' : $('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val();
                        if (classes.indexOf("assesScoreNum") !== -1) {
                            ln_AsessScores[ln_ColNum - 1] = ln_AsessScores[ln_ColNum - 1].replace(/[^-?0-9\.]/g, '');
                            $('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val(ln_AsessScores[ln_ColNum - 1]);
                            var minValue = Number($('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').attr('min-rhodata').replace(/[^-?0-9\.]/g, ''));
                            var maxValue = Number($('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').attr('max-rhodata').replace(/[^-?0-9\.]/g, ''));

                            if (Number(ln_AsessScores[ln_ColNum - 1]) < minValue ||
                                Number(ln_AsessScores[ln_ColNum - 1]) > maxValue) {
                                isVld = false;
                                errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Value for Row No. ' + i + ' must be between ' + minValue + ' and ' + maxValue + '!</span></p>';
                                $('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').addClass('rho-error');
                            } else {
                                $('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').removeClass('rho-error');
                            }
                        }
                        tempColVals = tempColVals +
                            ln_ColNum + "#" +
                            ln_AsessScores[ln_ColNum - 1].replace(/(#)/g, "{!;!;}").replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~";
                        tempCntr++;
                    });

                    /*for (var p = 0; p < ln_AsessScores.length; p++) {
                     ln_ColNum = (p + 1);
                     ln_AsessScores[p] = typeof $('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val() === 'undefined' ? '' : $('#assessShtColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val();
                     slctdDetLineValues = slctdDetLineValues
                     + ln_AsessScores[p].replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~";
                     }.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}")*/
                    slctdDetLineValues = slctdDetLineValues +
                        tempCntr + "~" +
                        tempColVals +
                        ln_PrsnNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save Assessment Sheet',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Assessment Sheet...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 2);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('assessShtHdrID', assessShtHdrID);
    formData.append('assessShtHdrName', assessShtHdrName);
    formData.append('assessShtHdrDesc', assessShtHdrDesc);
    formData.append('assessShtHdrTypeID', assessShtHdrTypeID);
    formData.append('assessShtHdrTypeNm', assessShtHdrTypeNm);
    formData.append('assessShtHdrPeriodID', assessShtHdrPeriodID);
    formData.append('assessShtHdrPeriodNm', assessShtHdrPeriodNm);

    formData.append('assessShtHdrPrsnID', assessShtHdrPrsnID);
    formData.append('assessShtHdrPrsnNm', assessShtHdrPrsnNm);
    formData.append('assessShtHdrClassID', assessShtHdrClassID);
    formData.append('assessShtHdrClassNm', assessShtHdrClassNm);
    formData.append('assessShtHdrCrseID', assessShtHdrCrseID);
    formData.append('assessShtHdrCrseNm', assessShtHdrCrseNm);

    formData.append('assessShtHdrSbjctID', assessShtHdrSbjctID);
    formData.append('assessShtHdrSbjctNm', assessShtHdrSbjctNm);

    formData.append('slctdHdrFtrValues', slctdHdrFtrValues);
    formData.append('slctdDetLineValues', slctdDetLineValues);
    formData.append('shdSbmt', shdSbmt);
    formData.append('assessShtHdrStatus', assessShtHdrStatus);
    formData.append('assessShtHdrAsdPrsID', assessShtHdrAsdPrsID);
    formData.append('assessShtHdrAsdPrsNm', assessShtHdrAsdPrsNm);

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
                            assessShtHdrID = data.assessShtHdrID;
                            getAssessSheetDets('', '#assessSheetDetList', 'grp=15&typ=1&pg=2&vtyp=1&assessSbmtdSheetID=' + assessShtHdrID + '&assessSbmtdSheetNm=' + assessShtHdrName, assessShtHdrID);
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

function delAssessShtHdr(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_HdrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_HdrID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Assessment Sheet?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Assessment Sheet?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Assessment Sheet?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Assessment Sheet...Please Wait...</p>',
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
                                    grp: 15,
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
//END ASSESSMENT SHEETS

//REPORT CARDS
function getReportCards(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#reportCardsSrchFor").val() === 'undefined' ? '%' : $("#reportCardsSrchFor").val();
    var srchIn = typeof $("#reportCardsSrchIn").val() === 'undefined' ? 'Both' : $("#reportCardsSrchIn").val();
    var pageNo = typeof $("#reportCardsPageNo").val() === 'undefined' ? 1 : $("#reportCardsPageNo").val();
    var limitSze = typeof $("#reportCardsDsplySze").val() === 'undefined' ? 10 : $("#reportCardsDsplySze").val();
    var sortBy = typeof $("#reportCardsSortBy").val() === 'undefined' ? '' : $("#reportCardsSortBy").val();
    var qShwUsrOnly = $('#reportCardsShwUsrOnly:checked').length > 0;
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwSelfOnly=" + qShwUsrOnly;
    openATab(slctr, linkArgs);
}

function enterKeyFuncReportCards(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getReportCards(actionText, slctr, linkArgs);
    }
}

function getReportCardDets(actionText, slctr, linkArgs, reportCardID) {
    if (typeof reportCardID === 'undefined' || reportCardID === null) {
        reportCardID = -1;
    }
    var tstreportCardID = "a" + reportCardID;
    if (Number(tstreportCardID.replace(/[^-?0-9\.]/g, '')) > 0) {
        $("#assessSbmtdSheetID").val(reportCardID);
        assessSbmtdSheetID = reportCardID;
    } else {
        var reportCardID = typeof $("#reportCardID").val() === 'undefined' ? '-1' : $("#reportCardID").val();
        var reportCardNm = typeof $("#reportCardNm").val() === 'undefined' ? '' : $("#reportCardNm").val();
        var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
        var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
        if (Number(reportCardID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            (Number(reportCardID.replace(/[^-?0-9\.]/g, '')) !== Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) &&
                Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) > 0)) {
            $("#reportCardID").val(assessSbmtdSheetID);
            $("#reportCardNm").val(assessSbmtdSheetNm);
            reportCardID = assessSbmtdSheetID;
            reportCardNm = assessSbmtdSheetNm;
        }
        if (Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
            (Number(reportCardID.replace(/[^-?0-9\.]/g, '')) !== Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) &&
                Number(reportCardID.replace(/[^-?0-9\.]/g, '')) > 0)) {
            $("#assessSbmtdSheetID").val(reportCardID);
            $("#assessSbmtdSheetNm").val(reportCardNm);
            assessSbmtdSheetID = reportCardID;
            assessSbmtdSheetNm = reportCardNm;
        }
        if (linkArgs.indexOf("&assessSbmtdSheetNm=") === -1) {
            linkArgs = linkArgs + "&assessSbmtdSheetID=" + assessSbmtdSheetID +
                "&assessSbmtdSheetNm=" + assessSbmtdSheetNm;
        }
    }
    var reportCardHdnTabNm = typeof $("#reportCardHdnTabNm").val() === 'undefined' ? '' : $("#reportCardHdnTabNm").val();
    if (reportCardHdnTabNm === 'asShtDetlsTrans') {
        getReportCrdHdr('', '#asShtDetlsTrans', 'grp=15&typ=1&pg=1&vtyp=2');
    } else if (reportCardHdnTabNm === 'asShtDetlsPMRecs') {
        getReportCrdHdr('', '#asShtDetlsPMRecs', 'grp=15&typ=1&pg=1&vtyp=3');
    } else {
        openATab(slctr, linkArgs);
    }
}

function enterKeyFuncReportCardDets(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getReportCardDets(actionText, slctr, linkArgs);
    }
}

function getReportCrdHdr(actionText, slctr, linkArgs, reportCardID) {
    if (typeof reportCardID === 'undefined' || reportCardID === null) {
        reportCardID = -1;
    }
    var srchFor = typeof $("#reportCrdHdrSrchFor").val() === 'undefined' ? '%' : $("#reportCrdHdrSrchFor").val();
    var srchIn = typeof $("#reportCrdHdrSrchIn").val() === 'undefined' ? 'Both' : $("#reportCrdHdrSrchIn").val();
    var pageNo = typeof $("#reportCrdHdrPageNo").val() === 'undefined' ? 1 : $("#reportCrdHdrPageNo").val();
    var limitSze = typeof $("#reportCrdHdrDsplySze").val() === 'undefined' ? 15 : $("#reportCrdHdrDsplySze").val();
    var sortBy = typeof $("#reportCrdHdrSortBy").val() === 'undefined' ? '' : $("#reportCrdHdrSortBy").val();
    var qShwUsrOnly = $('#reportCrdHdrShwUsrOnly:checked').length > 0;
    var qShwNonZeroOnly = $('#reportCrdHdrNonZeroOnly:checked').length > 0;
    var reportCardID1 = typeof $("#reportCardID").val() === 'undefined' ? '-1' : $("#reportCardID").val();
    var reportCardNm1 = typeof $("#reportCardNm").val() === 'undefined' ? '' : $("#reportCardNm").val();
    var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
    var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();
    if (Number(assessSbmtdSheetID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        assessSbmtdSheetID = reportCardID1;
        assessSbmtdSheetNm = reportCardNm1;
    }
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn +
        "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy + "&qShwNonZeroOnly=" + qShwNonZeroOnly;
    if (reportCardID <= 0) {
        linkArgs = linkArgs + "&assessSbmtdSheetID=" + assessSbmtdSheetID +
            "&assessSbmtdSheetNm=" + assessSbmtdSheetNm;
    }
    $('#myFormsModalLg').modal('hide');
    openATab(slctr, linkArgs);
}

function enterKeyFuncReportCrdHdr(e, actionText, slctr, linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getReportCrdHdr(actionText, slctr, linkArgs);
    }
}

function vldtAssessColNumFld(rowIDAttrb) {
    var classes = $("#" + rowIDAttrb).attr('class');
    var ln_ColNum = Number(rowIDAttrb.split("-")[1].replace(/[^-?0-9\.]/g, ''));
    var ln_AsessScore = typeof $("#" + rowIDAttrb).val() === 'undefined' ? '' : $("#" + rowIDAttrb).val();
    if (classes.indexOf("assesScoreNum") !== -1) {
        ln_AsessScore = ln_AsessScore.replace(/[^-?0-9\.]/g, '');
        var minValue = Number($("#" + rowIDAttrb).attr('min-rhodata').replace(/[^-?0-9\.]/g, ''));
        var maxValue = Number($("#" + rowIDAttrb).attr('max-rhodata').replace(/[^-?0-9\.]/g, ''));

        if (Number(ln_AsessScore) < minValue ||
            Number(ln_AsessScore) > maxValue) {
            $("#" + rowIDAttrb).addClass('rho-error');
        } else {
            $("#" + rowIDAttrb).removeClass('rho-error');
        }
    }
}

function saveReportCrdHdrForm(shdSbmt) {
    if (typeof shdSbmt === 'undefined' || shdSbmt === null) {
        shdSbmt = 0;
    }
    var reportCrdHdrID = typeof $("#reportCrdHdrID").val() === 'undefined' ? '-1' : $("#reportCrdHdrID").val();
    var reportCrdHdrName = typeof $("#reportCrdHdrName").val() === 'undefined' ? '' : $("#reportCrdHdrName").val();
    var reportCrdHdrDesc = typeof $("#reportCrdHdrDesc").val() === 'undefined' ? '' : $("#reportCrdHdrDesc").val();

    var reportCrdHdrTypeID = typeof $("#reportCrdHdrTypeID").val() === 'undefined' ? '-1' : $("#reportCrdHdrTypeID").val();
    var reportCrdHdrTypeNm = typeof $("#reportCrdHdrTypeNm").val() === 'undefined' ? '' : $("#reportCrdHdrTypeNm").val();
    var reportCrdHdrPeriodID = typeof $("#reportCrdHdrPeriodID").val() === 'undefined' ? '-1' : $("#reportCrdHdrPeriodID").val();
    var reportCrdHdrPeriodNm = typeof $("#reportCrdHdrPeriodNm").val() === 'undefined' ? '' : $("#reportCrdHdrPeriodNm").val();
    var reportCrdHdrPrsnID = typeof $("#reportCrdHdrPrsnID").val() === 'undefined' ? '-1' : $("#reportCrdHdrPrsnID").val();
    var reportCrdHdrPrsnNm = typeof $("#reportCrdHdrPrsnNm").val() === 'undefined' ? '' : $("#reportCrdHdrPrsnNm").val();

    var reportCrdHdrAsdPrsID = typeof $("#reportCrdHdrAsdPrsID").val() === 'undefined' ? '-1' : $("#reportCrdHdrAsdPrsID").val();
    var reportCrdHdrAsdPrsNm = typeof $("#reportCrdHdrAsdPrsNm").val() === 'undefined' ? '' : $("#reportCrdHdrAsdPrsNm").val();

    var reportCrdHdrClassID = typeof $("#reportCrdHdrClassID").val() === 'undefined' ? '-1' : $("#reportCrdHdrClassID").val();
    var reportCrdHdrClassNm = typeof $("#reportCrdHdrClassNm").val() === 'undefined' ? '' : $("#reportCrdHdrClassNm").val();
    var reportCrdHdrCrseID = typeof $("#reportCrdHdrCrseID").val() === 'undefined' ? '-1' : $("#reportCrdHdrCrseID").val();
    var reportCrdHdrCrseNm = typeof $("#reportCrdHdrCrseNm").val() === 'undefined' ? '' : $("#reportCrdHdrCrseNm").val();
    var reportCrdHdrSbjctID = typeof $("#reportCrdHdrSbjctID").val() === 'undefined' ? '-1' : $("#reportCrdHdrSbjctID").val();
    var reportCrdHdrSbjctNm = typeof $("#reportCrdHdrSbjctNm").val() === 'undefined' ? '' : $("#reportCrdHdrSbjctNm").val();
    var reportCrdHdrStatus = typeof $("#reportCrdHdrStatus").val() === 'undefined' ? 'Open for Editing' : $("#reportCrdHdrStatus").val();

    var errMsg = "";
    if (reportCrdHdrName.trim() === '' || reportCrdHdrDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Report Card Name and Description cannot be empty!</span></p>';
    }
    if (reportCrdHdrTypeNm.trim() === '' || reportCrdHdrPeriodNm.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Report Card Type and Period cannot be empty!</span></p>';
    }
    /* || Number(reportCrdHdrCrseID.replace(/[^-?0-9\.]/g, '')) <= 0*/
    if (Number(reportCrdHdrClassID.replace(/[^-?0-9\.]/g, '')) <= 0 ||
        Number(reportCrdHdrAsdPrsID.replace(/[^-?0-9\.]/g, '')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Report Card Group and Person Assessed cannot be empty!</span></p>';
    }
    var isVld = true;
    var slctdHdrFtrValues = "";
    var slctdDetLineValues = "";

    $(".reportCrdHdrFtrVal").each(function () {
        var classes = $(this).attr('class');
        var key = $(this).attr('id').split("_")[1];
        var val = $(this).val();
        if (classes.indexOf("assesScoreNum") !== -1) {
            val = $(this).val().replace(/[^-?0-9\.]/g, '');
            $(this).val(val);
        }
        slctdHdrFtrValues = slctdHdrFtrValues +
            key.replace(/[^-?0-9\.]/g, '').replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
            val.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
    });

    var tempColVals = "";
    var tempCntr = 0;
    $('#oneReportCardTransLinesTable').find('tr').each(function (i, el) {
        isVld = true;
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var ln_LineID = $('#reportCrdColRow' + rndmNum + '_LineID').val();
                var ln_PrsnID = $('#reportCrdColRow' + rndmNum + '_PrsnID').val();
                var ln_PrsnNm = $('#reportCrdColRow' + rndmNum + '_PrsnNm').val();
                var ln_AsessScores = new Array(50);
                var ln_ColNum = 1;
                if (isVld === true) {
                    slctdDetLineValues = slctdDetLineValues +
                        ln_LineID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                        ln_PrsnID.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~";
                    tempColVals = "";
                    tempCntr = 0;
                    $(".reportCrdColRow" + rndmNum).each(function () {
                        var classes = $(this).attr('class');
                        ln_ColNum = Number($(this).attr('id').split("-")[1].replace(/[^-?0-9\.]/g, ''));
                        ln_AsessScores[ln_ColNum - 1] = typeof $('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val() === 'undefined' ? '' : $('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val();
                        if (classes.indexOf("assesScoreNum") !== -1) {
                            ln_AsessScores[ln_ColNum - 1] = ln_AsessScores[ln_ColNum - 1].replace(/[^-?0-9\.]/g, '');
                            $('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val(ln_AsessScores[ln_ColNum - 1]);
                            var minValue = Number($('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').attr('min-rhodata').replace(/[^-?0-9\.]/g, ''));
                            var maxValue = Number($('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').attr('max-rhodata').replace(/[^-?0-9\.]/g, ''));

                            if (Number(ln_AsessScores[ln_ColNum - 1]) < minValue ||
                                Number(ln_AsessScores[ln_ColNum - 1]) > maxValue) {
                                isVld = false;
                                errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                                    'font-weight:bold;color:red;">Value for Row No. ' + i + ' must be between ' + minValue + ' and ' + maxValue + '!</span></p>';
                                $('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').addClass('rho-error');
                            } else {
                                $('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').removeClass('rho-error');
                            }
                        }
                        tempColVals = tempColVals +
                            ln_ColNum + "#" +
                            ln_AsessScores[ln_ColNum - 1].replace(/(#)/g, "{!;!;}").replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~";
                        tempCntr++;
                    });

                    /*for (var p = 0; p < ln_AsessScores.length; p++) {
                     ln_ColNum = (p + 1);
                     ln_AsessScores[p] = typeof $('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val() === 'undefined' ? '' : $('#reportCrdColRow' + rndmNum + '_AsessScore-' + ln_ColNum + '').val();
                     slctdDetLineValues = slctdDetLineValues
                     + ln_AsessScores[p].replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~";
                     }.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}")*/
                    slctdDetLineValues = slctdDetLineValues +
                        tempCntr + "~" +
                        tempColVals +
                        ln_PrsnNm.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
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
        title: 'Save Report Card',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Report Card...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 1);
    formData.append('q', 'UPDATE');
    formData.append('actyp', 1);
    formData.append('reportCrdHdrID', reportCrdHdrID);
    formData.append('reportCrdHdrName', reportCrdHdrName);
    formData.append('reportCrdHdrDesc', reportCrdHdrDesc);
    formData.append('reportCrdHdrTypeID', reportCrdHdrTypeID);
    formData.append('reportCrdHdrTypeNm', reportCrdHdrTypeNm);
    formData.append('reportCrdHdrPeriodID', reportCrdHdrPeriodID);
    formData.append('reportCrdHdrPeriodNm', reportCrdHdrPeriodNm);

    formData.append('reportCrdHdrPrsnID', reportCrdHdrPrsnID);
    formData.append('reportCrdHdrPrsnNm', reportCrdHdrPrsnNm);
    formData.append('reportCrdHdrClassID', reportCrdHdrClassID);
    formData.append('reportCrdHdrClassNm', reportCrdHdrClassNm);
    formData.append('reportCrdHdrCrseID', reportCrdHdrCrseID);
    formData.append('reportCrdHdrCrseNm', reportCrdHdrCrseNm);

    formData.append('reportCrdHdrSbjctID', reportCrdHdrSbjctID);
    formData.append('reportCrdHdrSbjctNm', reportCrdHdrSbjctNm);

    formData.append('slctdHdrFtrValues', slctdHdrFtrValues);
    formData.append('slctdDetLineValues', slctdDetLineValues);
    formData.append('shdSbmt', shdSbmt);
    formData.append('reportCrdHdrStatus', reportCrdHdrStatus);
    formData.append('reportCrdHdrAsdPrsID', reportCrdHdrAsdPrsID);
    formData.append('reportCrdHdrAsdPrsNm', reportCrdHdrAsdPrsNm);

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
                            reportCrdHdrID = data.reportCrdHdrID;
                            getReportCardDets('', '#reportCardDetList', 'grp=15&typ=1&pg=1&vtyp=1&assessSbmtdSheetID=' + reportCrdHdrID + '&assessSbmtdSheetNm=' + reportCrdHdrName, reportCrdHdrID);
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

function delReportCrdHdr(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_HdrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_HdrID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Report Card?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Report Card?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Report Card?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Report Card...Please Wait...</p>',
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
                                    grp: 15,
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
//END REPORT CARDS

//EXPORT/IMPORT GRADE SCALES

var prgstimerid2;
var exprtBtn;
var exprtBtn2;

function exprtGrdScale() {
    var msgPart = "Grade Scales";
    var exprtMsg = '<form role="form">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 10,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshGrdScalePrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshGrdScalePrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 10,
            q: 'UPDATE',
            actyp: 1002
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
                                                                callback: function () { }
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
                                                            callback: function () { }
                                                        });
                                                    }
                                                }
                                                if (locIDNo.trim() !== "" && prsnFirstNm.trim() !== "" &&
                                                    prsnType.trim() !== "" &&
                                                    prsnTitle.trim() !== "" &&
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
                                                callback: function () { }
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
                                            callback: function () { }
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
//END EXPORT/IMPORT GRADE SCALES

//EXPORT/IMPORT SUBJECTS/TASKS
function exprtSbjctTask() {
    var msgPart = "Subjects/Tasks";
    var exprtMsg = '<form role="form">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 9,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshSbjctTaskPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshSbjctTaskPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 9,
            q: 'UPDATE',
            actyp: 1002
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
//END EXPORT/IMPORT SUBJECTS/TASKS

//EXPORT/IMPORT PROGRAMMES/OBJECTIVES
function exprtCourseObjctv() {
    var msgPart = "Programmes/Objectives";
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 8,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshCourseObjctvPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshCourseObjctvPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 8,
            q: 'UPDATE',
            actyp: 1002
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
//END EXPORT/IMPORT PROGRAMMES/OBJECTIVES


//EXPORT/IMPORT ASSESSMENT TYPES
function exprtAssessTypes() {
    var msgPart = "Assessment Types";
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 7,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshAssessTypesPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshAssessTypesPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 7,
            q: 'UPDATE',
            actyp: 1002
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
//END EXPORT/IMPORT ASSESSMENT TYPES

//EXPORT/IMPORT ASSESSMENT GROUPS
function exprtAssessGrps() {
    var msgPart = "Assessment Groups";
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 4,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshAssessGrpsPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshAssessGrpsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 4,
            q: 'UPDATE',
            actyp: 1002
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
//END EXPORT/IMPORT CLASSES_COURSES


//EXPORT/IMPORT ASSESSMENT GROUPS
function exprtRgstrtns() {
    var msgPart = "Task Assignments/Registrations";
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 3,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum
                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshRgstrtnsPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshRgstrtnsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 3,
            q: 'UPDATE',
            actyp: 1002
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
//END EXPORT/IMPORT CLASSES_COURSES

//EXPORT/IMPORT ASSESSMENT Sheets
function exprtAsessShts() {
    var msgPart = "Assessment Sheets";
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();

                if (!isNumber(inptNum)) {
                    var dialog = bootbox.alert({
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 2,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum,
                                assessSbmtdSheetID: assessSbmtdSheetID,
                                assessSbmtdSheetNm: assessSbmtdSheetNm

                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshAsessShtsPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshAsessShtsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 2,
            q: 'UPDATE',
            actyp: 1002
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
//END EXPORT/IMPORT ASSESSMENT Sheets


//EXPORT/IMPORT SCORE CARDS
function exprtScoreCards() {
    var msgPart = "Report/Score Cards";
    var exprtMsg = '<form role="form" id="recsToExprtForm">' +
        '<p style="color:#000;">' +
        'How many ' + msgPart + ' will you like to Export?' +
        '<br/>1=No ' + msgPart + '(Empty Template)' +
        '<br/>2=All ' + msgPart + '' +
        '<br/>3-Infinity=Specify the exact number of ' + msgPart + ' to Export<br/>' +
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
        onshow: function (dialogItself) { },
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
                var assessSbmtdSheetID = typeof $("#assessSbmtdSheetID").val() === 'undefined' ? '-1' : $("#assessSbmtdSheetID").val();
                var assessSbmtdSheetNm = typeof $("#assessSbmtdSheetNm").val() === 'undefined' ? '' : $("#assessSbmtdSheetNm").val();

                if (!isNumber(inptNum)) {
                    var dialog = bootbox.alert({
                        title: 'Exporting ' + msgPart + '',
                        size: 'small',
                        message: 'Please provide a valid Number!',
                        callback: function () { }
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
                                grp: 15,
                                typ: 1,
                                pg: 1,
                                q: 'UPDATE',
                                actyp: 1001,
                                inptNum: inptNum,
                                assessSbmtdSheetID: assessSbmtdSheetID,
                                assessSbmtdSheetNm: assessSbmtdSheetNm

                            }
                        });
                        prgstimerid2 = window.setInterval(rfrshScoreCardsPrcs, 1000);
                    });
                }
            }
        }]
    });
}

function rfrshScoreCardsPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 15,
            typ: 1,
            pg: 1,
            q: 'UPDATE',
            actyp: 1002
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
//END EXPORT/IMPORT SCORE CARDS


function printEmailFullTermRpt(pKeyID) {
    var dialog = bootbox.alert({
        title: 'GET PDF',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Getting PDF...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
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

function printEmailFullRgstrSlp(pKeyID, sbmtdAcaSttngsID) {
    if (typeof sbmtdAcaSttngsID === 'undefined' || sbmtdAcaSttngsID === null) {
        sbmtdAcaSttngsID = -1;
    }
    var dialog = bootbox.alert({
        title: 'GET PDF',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Getting PDF...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp', 15);
    formData.append('typ', 1);
    formData.append('pg', 3);
    formData.append('q', 'VIEW');
    formData.append('vtyp', 701);
    formData.append('pKeyID', pKeyID);
    formData.append('sbmtdAcaSttngsID', sbmtdAcaSttngsID);
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