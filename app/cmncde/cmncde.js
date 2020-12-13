var tableSg1;

function getCstmrSpplrForm(pKeyID,pKeyTitle,actionTxt,callBackFunc,pKeyElmntID) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (!(typeof pKeyElmntID === 'undefined' || pKeyElmntID === null)) {
        pKeyID = typeof $("#" + pKeyElmntID).val() === 'undefined' ? '-1' : $("#" + pKeyElmntID).val();
    }
    var lnkArgs = 'grp=6&typ=1&pg=13&vtyp=1&sbmtdCstmrSpplrID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalLx',actionTxt,pKeyTitle,'myFormsModalLxTitle','myFormsModalLxBody',function () {
        $('#vaultCstmrStpForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(function () {
            $('[data-toggle="tabajxvmscstmrs"]').off('click');
            $('[data-toggle="tabajxvmscstmrs"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                $(targ + 'tab').tab('show');
                if (targ.indexOf('vmsCstmrsGnrl') >= 0) {
                    $('#vmsCstmrsGnrl').removeClass('hideNotice');
                    $('#vmsCstmrsSites').addClass('hideNotice');
                    $('#vmsCstmrsSrvcs').addClass('hideNotice');
                } else if (targ.indexOf('vmsCstmrsSites') >= 0) {
                    $('#vmsCstmrsGnrl').addClass('hideNotice');
                    $('#vmsCstmrsSites').removeClass('hideNotice');
                    $('#vmsCstmrsSrvcs').addClass('hideNotice');
                } else if (targ.indexOf('vmsCstmrsSrvcs') >= 0) {
                    $('#vmsCstmrsGnrl').addClass('hideNotice');
                    $('#vmsCstmrsSites').addClass('hideNotice');
                    $('#vmsCstmrsSrvcs').removeClass('hideNotice');
                }
            });
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
        $('#allOtherInputData99').val(0);
        $('#myFormsModalLx').off('hidden.bs.modal');
        $('#myFormsModalLx').one('hidden.bs.modal',function (e) {
            $('#myFormsModalLxTitle').html('');
            $('#myFormsModalLxBody').html('');
            callBackFunc();
            $(e.currentTarget).unbind();
        });
    });
}

function afterCstmrLnkdPrsnSlct() {
    var cstmrSpplrLnkdPrsnID = typeof $("#cstmrSpplrLnkdPrsnID").val() === 'undefined' ? '-1' : $("#cstmrSpplrLnkdPrsnID").val();
    var cstmrSpplrLnkdPrsn = typeof $("#cstmrSpplrLnkdPrsn").val() === 'undefined' ? '' : $("#cstmrSpplrLnkdPrsn").val();
    getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
        $body = $("body");
        $body.removeClass("mdlloadingDiag");
        $body.removeClass("mdlloading");
        var obj;
        var formData = new FormData();
        formData.append('grp',6);
        formData.append('typ',1);
        formData.append('pg',13);
        formData.append('q','VIEW');
        formData.append('vtyp',5);
        formData.append('cstmrSpplrLnkdPrsnID',cstmrSpplrLnkdPrsnID);
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
                    $("#cstmrSpplrDOB").val(data.cstmrSpplrDOB);
                    $("#cstmrSpplrGender").val(data.cstmrSpplrGender);
                    $("#cstmrSpplrClsfctn").val("Individual");
                    $("#cstmrSpplrNm").val(cstmrSpplrLnkdPrsn);
                    $("#cstmrSpplrDesc").val(cstmrSpplrLnkdPrsn);
                }
            },
            error: function (jqXHR,textStatus,errorThrown) {
                console.warn(jqXHR.responseText);
            }
        });
    });
}

function saveCstmrsForm() {
    var sbmtdCstmrSpplrID = typeof $("#sbmtdCstmrSpplrID").val() === 'undefined' ? '-1' : $("#sbmtdCstmrSpplrID").val();
    var cstmrSpplrNm = typeof $("#cstmrSpplrNm").val() === 'undefined' ? '' : $("#cstmrSpplrNm").val();
    var cstmrSpplrDesc = typeof $("#cstmrSpplrDesc").val() === 'undefined' ? '' : $("#cstmrSpplrDesc").val();
    var cstmrSpplrType = typeof $("#cstmrSpplrType").val() === 'undefined' ? '' : $("#cstmrSpplrType").val();
    var cstmrSpplrClsfctn = typeof $("#cstmrSpplrClsfctn").val() === 'undefined' ? '' : $("#cstmrSpplrClsfctn").val();
    var cstmrSpplrLnkdPrsnID = typeof $("#cstmrSpplrLnkdPrsnID").val() === 'undefined' ? '-1' : $("#cstmrSpplrLnkdPrsnID").val();
    var cstmrSpplrGender = typeof $("#cstmrSpplrGender").val() === 'undefined' ? '' : $("#cstmrSpplrGender").val();
    var cstmrSpplrDOB = typeof $("#cstmrSpplrDOB").val() === 'undefined' ? '' : $("#cstmrSpplrDOB").val();
    var cstmrLbltyAcntID = typeof $("#cstmrLbltyAcntID").val() === 'undefined' ? '-1' : $("#cstmrLbltyAcntID").val();
    var cstmrRcvblsAcntID = typeof $("#cstmrRcvblsAcntID").val() === 'undefined' ? '-1' : $("#cstmrRcvblsAcntID").val();
    var cstmrCmpnyBrandNm = typeof $("#cstmrCmpnyBrandNm").val() === 'undefined' ? '' : $("#cstmrCmpnyBrandNm").val();
    var isCstmrEnbld = typeof $("input[name='isCstmrEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var cstmrOrgType = typeof $("#cstmrOrgType").val() === 'undefined' ? '' : $("#cstmrOrgType").val();
    var cstmrRegNum = typeof $("#cstmrRegNum").val() === 'undefined' ? '' : $("#cstmrRegNum").val();
    var cstmrDateIncprtd = typeof $("#cstmrDateIncprtd").val() === 'undefined' ? '' : $("#cstmrDateIncprtd").val();
    var cstmrIncprtnType = typeof $("#cstmrIncprtnType").val() === 'undefined' ? '' : $("#cstmrIncprtnType").val();
    var cstmrVatNumber = typeof $("#cstmrVatNumber").val() === 'undefined' ? '' : $("#cstmrVatNumber").val();
    var cstmrTinNumber = typeof $("#cstmrTinNumber").val() === 'undefined' ? '' : $("#cstmrTinNumber").val();
    var cstmrSsnitRegNum = typeof $("#cstmrSsnitRegNum").val() === 'undefined' ? '' : $("#cstmrSsnitRegNum").val();
    var cstmrNumEmployees = typeof $("#cstmrNumEmployees").val() === 'undefined' ? '' : $("#cstmrNumEmployees").val();
    var cstmrDescSrvcs = typeof $("#cstmrDescSrvcs").val() === 'undefined' ? '' : $("#cstmrDescSrvcs").val();
    var errMsg = "";
    if (cstmrSpplrNm.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier name cannot be empty!</span></p>';
    }
    if (cstmrSpplrDesc.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier Description cannot be empty!</span></p>';
    }
    if (cstmrSpplrGender.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier Gender cannot be empty!</span></p>';
    }
    if (cstmrSpplrDOB.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier Date Established cannot be empty!</span></p>';
    }
    if (Number(cstmrLbltyAcntID.replace(/[^-?0-9\.]/g,'')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier Liability Account cannot be empty!</span></p>';
    }
    if (Number(cstmrRcvblsAcntID.replace(/[^-?0-9\.]/g,'')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier Receivables Account cannot be empty!</span></p>';
    }
    if (cstmrSpplrType.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier Type cannot be empty!</span></p>';
    }
    if (cstmrSpplrClsfctn.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Customer/Supplier Classification cannot be empty!</span></p>';
    }
    var cstmrListOfSrvcs = "";
    var isVld = true;
    $('#oneCstmrSrvcsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                if (typeof $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val() === 'undefined') {
                    /*Do Nothing*/
                } else {
                    if (isVld === true) {
                        cstmrListOfSrvcs = cstmrListOfSrvcs + $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val() + "|";
                    }
                }
            }
        }
    });
    if (isVld === false) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Please fix all Line Errors!</span></p>';
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
        title: 'Save Customer/Supplier',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Customer/Supplier...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getCstmrSpplrForm(sbmtdCstmrSpplrID,'Create/Edit Supplier','ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('daCstmrPicture',$('#daCstmrPicture')[0].files[0]);
    formData.append('grp',6);
    formData.append('typ',1);
    formData.append('pg',13);
    formData.append('q','UPDATE');
    formData.append('actyp',1);
    formData.append('sbmtdCstmrSpplrID',sbmtdCstmrSpplrID);
    formData.append('cstmrSpplrNm',cstmrSpplrNm);
    formData.append('cstmrSpplrDesc',cstmrSpplrDesc);
    formData.append('cstmrSpplrType',cstmrSpplrType);
    formData.append('cstmrSpplrClsfctn',cstmrSpplrClsfctn);
    formData.append('cstmrSpplrLnkdPrsnID',cstmrSpplrLnkdPrsnID);
    formData.append('cstmrSpplrGender',cstmrSpplrGender);
    formData.append('cstmrSpplrDOB',cstmrSpplrDOB);
    formData.append('isCstmrEnbld',isCstmrEnbld);
    formData.append('cstmrLbltyAcntID',cstmrLbltyAcntID);
    formData.append('cstmrRcvblsAcntID',cstmrRcvblsAcntID);
    formData.append('cstmrCmpnyBrandNm',cstmrCmpnyBrandNm);
    formData.append('cstmrOrgType',cstmrOrgType);
    formData.append('cstmrRegNum',cstmrRegNum);
    formData.append('cstmrDateIncprtd',cstmrDateIncprtd);
    formData.append('cstmrIncprtnType',cstmrIncprtnType);
    formData.append('cstmrVatNumber',cstmrVatNumber);
    formData.append('cstmrTinNumber',cstmrTinNumber);
    formData.append('cstmrSsnitRegNum',cstmrSsnitRegNum);
    formData.append('cstmrNumEmployees',cstmrNumEmployees);
    formData.append('cstmrDescSrvcs',cstmrDescSrvcs);
    formData.append('cstmrListOfSrvcs',cstmrListOfSrvcs);
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
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdCstmrSpplrID = result.cstmrid;
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function getOneCstmrSitesForm(pKeyID,actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=6&typ=1&pg=13&vtyp=2&srcMenu=VMS&sbmtdSiteID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModaln',actionTxt,'Site (ID:' + pKeyID + ')','myFormsModalnTitle','myFormsModalnBody',function () {
        $('#cstmrSiteForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#allOtherInputData99').val(0);
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
        $('#myFormsModalNrml').off('hidden.bs.modal');
        $('#myFormsModalNrml').one('hidden.bs.modal',function (e) {
            $('#myFormsModalNrmlTitle').html('');
            $('#myFormsModalNrmlBody').html('');
            $(e.currentTarget).unbind();
        });
    });
}

function saveCstmrSitesForm() {
    var sbmtdCstmrSpplrID = typeof $("#sbmtdCstmrSpplrID").val() === 'undefined' ? '-1' : $("#sbmtdCstmrSpplrID").val();
    var sbmtdSiteID = typeof $("#sbmtdSiteID").val() === 'undefined' ? '-1' : $("#sbmtdSiteID").val();
    var csSiteName = typeof $("#csSiteName").val() === 'undefined' ? '' : $("#csSiteName").val();
    var csSiteDesc = typeof $("#csSiteDesc").val() === 'undefined' ? '' : $("#csSiteDesc").val();
    var csSiteBllngAddress = typeof $("#csSiteBllngAddress").val() === 'undefined' ? '' : $("#csSiteBllngAddress").val();
    var csSiteShpngAddress = typeof $("#csSiteShpngAddress").val() === 'undefined' ? '' : $("#csSiteShpngAddress").val();
    var csSiteCntctPrsn = typeof $("#csSiteCntctPrsn").val() === 'undefined' ? '' : $("#csSiteCntctPrsn").val();
    var csSiteCntctNos = typeof $("#csSiteCntctNos").val() === 'undefined' ? '' : $("#csSiteCntctNos").val();
    var csSiteEmailAdrs = typeof $("#csSiteEmailAdrs").val() === 'undefined' ? '' : $("#csSiteEmailAdrs").val();
    var csSiteWthTxCodeID = typeof $("#csSiteWthTxCodeID").val() === 'undefined' ? '-1' : $("#csSiteWthTxCodeID").val();
    var csSiteDscntCodeID = typeof $("#csSiteDscntCodeID").val() === 'undefined' ? '-1' : $("#csSiteDscntCodeID").val();
    var isCsSiteEnbld = typeof $("input[name='isCsSiteEnbld']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var csSiteCountry = typeof $("#csSiteCountry").val() === 'undefined' ? '' : $("#csSiteCountry").val();
    var csSiteIDType = typeof $("#csSiteIDType").val() === 'undefined' ? '' : $("#csSiteIDType").val();
    var csSiteIDNum = typeof $("#csSiteIDNum").val() === 'undefined' ? '' : $("#csSiteIDNum").val();
    var csSiteDateIsd = typeof $("#csSiteDateIsd").val() === 'undefined' ? '' : $("#csSiteDateIsd").val();
    var csSiteExpryDate = typeof $("#csSiteExpryDate").val() === 'undefined' ? '' : $("#csSiteExpryDate").val();
    var csSiteOtherInfo = typeof $("#csSiteOtherInfo").val() === 'undefined' ? '' : $("#csSiteOtherInfo").val();
    var csSiteBankNm = typeof $("#csSiteBankNm").val() === 'undefined' ? '' : $("#csSiteBankNm").val();
    var csSiteBrnchNm = typeof $("#csSiteBrnchNm").val() === 'undefined' ? '' : $("#csSiteBrnchNm").val();
    var csSiteAcntNum = typeof $("#csSiteAcntNum").val() === 'undefined' ? '' : $("#csSiteAcntNum").val();
    var csSiteCrncy = typeof $("#csSiteCrncy").val() === 'undefined' ? '' : $("#csSiteCrncy").val();
    var csSiteSwftCode = typeof $("#csSiteSwftCode").val() === 'undefined' ? '' : $("#csSiteSwftCode").val();
    var csSiteIbanCode = typeof $("#csSiteIbanCode").val() === 'undefined' ? '' : $("#csSiteIbanCode").val();
    var errMsg = "";
    if (csSiteName.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Site name cannot be empty!</span></p>';
    }
    if (csSiteCntctPrsn.trim() === "") {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Contact Person cannot be empty!</span></p>';
    }
    if (Number(sbmtdCstmrSpplrID.replace(/[^-?0-9\.]/g,'')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Customer/Supplier cannot be empty!</span></p>';
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
        title: 'Save Item',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Site...Please Wait...</p>',
        callback: function () {
            if (shdClose > 0) {
                /*$('#myFormsModalNrml').modal('hide');*/
                getOneCstmrSitesForm(sbmtdSiteID,'ReloadDialog');
            }
        }
    });
    var formData = new FormData();
    formData.append('grp',6);
    formData.append('typ',1);
    formData.append('pg',13);
    formData.append('q','UPDATE');
    formData.append('actyp',2);
    formData.append('sbmtdCstmrSpplrID',sbmtdCstmrSpplrID);
    formData.append('sbmtdSiteID',sbmtdSiteID);
    formData.append('csSiteName',csSiteName);
    formData.append('csSiteDesc',csSiteDesc);
    formData.append('isCsSiteEnbld',isCsSiteEnbld);
    formData.append('csSiteBllngAddress',csSiteBllngAddress);
    formData.append('csSiteShpngAddress',csSiteShpngAddress);
    formData.append('csSiteCntctPrsn',csSiteCntctPrsn);
    formData.append('csSiteCntctNos',csSiteCntctNos);
    formData.append('csSiteEmailAdrs',csSiteEmailAdrs);
    formData.append('csSiteWthTxCodeID',csSiteWthTxCodeID);
    formData.append('csSiteDscntCodeID',csSiteDscntCodeID);
    formData.append('csSiteCountry',csSiteCountry);
    formData.append('csSiteIDType',csSiteIDType);
    formData.append('csSiteIDNum',csSiteIDNum);
    formData.append('csSiteDateIsd',csSiteDateIsd);
    formData.append('csSiteExpryDate',csSiteExpryDate);
    formData.append('csSiteOtherInfo',csSiteOtherInfo);
    formData.append('csSiteBankNm',csSiteBankNm);
    formData.append('csSiteBrnchNm',csSiteBrnchNm);
    formData.append('csSiteAcntNum',csSiteAcntNum);
    formData.append('csSiteCrncy',csSiteCrncy);
    formData.append('csSiteSwftCode',csSiteSwftCode);
    formData.append('csSiteIbanCode',csSiteIbanCode);
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
                success: function (result) {
                    dialog.find('.bootbox-body').html(result.message);
                    if (result.message.indexOf("Success") !== -1) {
                        shdClose = 1;
                        sbmtdSiteID = result.cstmrsiteid;
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}

function delCstmrs(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var cstmrNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_CstmrID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_CstmrID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        cstmrNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Trade Partner?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Trade Partner?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Trade Partner?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Trade Partner...Please Wait...</p>',
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
                                    grp: 6,
                                    typ: 1,
                                    pg: 13,
                                    q: 'DELETE',
                                    actyp: 1,
                                    pKeyID: pKeyID,
                                    cstmrNm: cstmrNm
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

function delCstmrsSites(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var siteNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SiteID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SiteID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        siteNm = $.trim($tds.eq(1).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Site?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Site?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Site?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Site...Please Wait...</p>',
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
                                    grp: 6,
                                    typ: 1,
                                    pg: 13,
                                    q: 'DELETE',
                                    actyp: 2,
                                    pKeyID: pKeyID,
                                    siteNm: siteNm
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

function delCstmrsSrvcOffrd(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var stockNm = '';
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SrvcNm').val();
        var $tds = $('#' + rowIDAttrb).find('td');
    }
    var dialog = bootbox.confirm({
        title: 'Delete Service?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Service?<br/>Action cannot be Undone!</p>',
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
                    title: 'Delete Service?',
                    size: 'small',
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Service...Please Wait...</p>',
                    callback: function () {
                        $("body").css("padding-right","0px");
                    }
                });
                dialog1.init(function () {
                    setTimeout(function () {
                        $("#" + rowIDAttrb).remove();
                        dialog1.find('.bootbox-body').html('Row Removed Successfully!<br/><span style="font-weight:bold;font-style:italic;color:red;">NB: You must click on SAVE for CHANGES to take EFFECT!</span>');
                    },500);
                    $('#allOtherInputData99').val(0);
                });
            }
        }
    });
}

function getOneCstmrsDocsForm(pKeyID,vwtype) {
    var lnkArgs = 'grp=6&typ=1&pg=13&vtyp=' + vwtype + '&sbmtdAccbCstmrsID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModaly','ShowDialog','Trade Partner\'s Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        var table1 = $('#attchdCstmrsDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdCstmrsDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdCstmrsDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function uploadFileToCstmrsDocs(inptElmntID,attchIDElmntID,docNmElmntID,sbmtdHdrID,rowIDAttrb) {
    var docCtrgrName = $('#' + docNmElmntID).val();
    var errMsg = "";
    if (docCtrgrName.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Name/Description cannot be empty!</span></p>';
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
        sendFileToCstmrsDocs(input.files[0],docNmElmntID,attchIDElmntID,sbmtdHdrID,function (data) {
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

function sendFileToCstmrsDocs(file,docNmElmntID,attchIDElmntID,sbmtdHdrID,callBackFunc) {
    var data1 = new FormData();
    data1.append('daCstmrAttchmnt',file);
    data1.append('grp',6);
    data1.append('typ',1);
    data1.append('pg',13);
    data1.append('q','UPDATE');
    data1.append('actyp',20);
    data1.append('docCtrgrName',$('#' + docNmElmntID).val());
    data1.append('attchmentID',$('#' + attchIDElmntID).val());
    data1.append('sbmtdAccbCstmrsID',sbmtdHdrID);
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

function getAttchdCstmrsDocs(actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var srchFor = typeof $("#attchdCstmrsDocsSrchFor").val() === 'undefined' ? '%' : $("#attchdCstmrsDocsSrchFor").val();
    var srchIn = typeof $("#attchdCstmrsDocsSrchIn").val() === 'undefined' ? 'Both' : $("#attchdCstmrsDocsSrchIn").val();
    var pageNo = typeof $("#attchdCstmrsDocsPageNo").val() === 'undefined' ? 1 : $("#attchdCstmrsDocsPageNo").val();
    var limitSze = typeof $("#attchdCstmrsDocsDsplySze").val() === 'undefined' ? 10 : $("#attchdCstmrsDocsDsplySze").val();
    var sortBy = typeof $("#attchdCstmrsDocsSortBy").val() === 'undefined' ? '' : $("#attchdCstmrsDocsSortBy").val();
    if (actionText == 'clear') {
        srchFor = "%";
        pageNo = 1;
    } else if (actionText == 'next') {
        pageNo = parseInt(pageNo) + 1;
    } else if (actionText == 'previous') {
        pageNo = parseInt(pageNo) - 1;
    }
    linkArgs = linkArgs + "&searchfor=" + srchFor + "&searchin=" + srchIn + "&pageNo=" + pageNo + "&limitSze=" + limitSze + "&sortBy=" + sortBy;
    doAjaxWthCallBck(linkArgs,'myFormsModaly',actionDialog,'Petty Cash Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        if (!$.fn.DataTable.isDataTable('#attchdCstmrsDocsTable')) {
            var table1 = $('#attchdCstmrsDocsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#attchdCstmrsDocsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdCstmrsDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function enterKeyFuncAttchdCstmrsDocs(e,actionText,slctr,linkArgs,actionDialog) {
    if (typeof actionDialog === 'undefined' || actionDialog === null) {
        actionDialog = 'ShowDialog';
    }
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAttchdCstmrsDocs(actionText,slctr,linkArgs,actionDialog);
    }
}

function delAttchdCstmrsDoc(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var sbmtdHdrID = typeof $("#sbmtdAccbCstmrsID").val() === 'undefined' ? -1 : $("#sbmtdAccbCstmrsID").val();
    var docNum = typeof $("#cstmrSpplrNm").val() === 'undefined' ? '' : $("#cstmrSpplrNm").val();
    var pKeyID = -1;
    if (typeof $('#attchdCstmrsDocsRow' + rndmNum + '_AttchdDocsID').val() === 'undefined') {
        /*Do Nothing allnoticesRow<?php echo $cntr; ?> */
    } else {
        pKeyID = $('#attchdCstmrsDocsRow' + rndmNum + '_AttchdDocsID').val();
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
                                    grp: 6,
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

function getRecHstry(encdMsg) {
    var dialog1 = bootbox.alert({
        title: 'Record History',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Getting Record History...Please Wait...</p>',
        callback: function () {
            $("body").css("padding-right","0px");
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
                    grp: 1,
                    typ: 11,
                    q: 'Record History',
                    encdMsg: encdMsg
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
    });
}

function getSegmentValuesForm(pKeyID,pKeyTitle,actionTxt,callBackFunc) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=5&typ=1&pg=1&vtyp=9&sbmtdSegmentID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalLgY',actionTxt,pKeyTitle,'myFormsModalLgYTitle','myFormsModalLgYBody',function () {
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
        $('#allOtherInputData99').val(0);
        if (!$.fn.DataTable.isDataTable('#allSgmntValsTable')) {
            tableSg1 = $('#allSgmntValsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allSgmntValsTable').wrap('<div class="dataTables_scroll"/>');
        }

        if (!$.fn.DataTable.isDataTable('#allSgmntClsfctnsTable')) {
            var table1 = $('#allSgmntClsfctnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allSgmntClsfctnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#allSgmntValsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $('#allSgmntValsTable tbody').off('click','tr');
        $('#allSgmntValsTable tbody').off('mouseenter','tr');
        $('#allSgmntValsTable tbody').on('click','tr',function () {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');

            } else {
                tableSg1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');

            }
            var rndmNum = $(this).attr('id').split("_")[1];
            var segValID = typeof $('#allSgmntValsRow' + rndmNum + '_SgmntValID').val() === 'undefined' ? -1 : $('#allSgmntValsRow' + rndmNum + '_SgmntValID').val();
            getOneSgmntValForm(segValID,11);
        });

        $('#allSgmntValsTable tbody')
            .on('mouseenter','tr',function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    tableSg1.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
                }
            });

        $('#myFormsModalLgY').off('hidden.bs.modal');
        $('#myFormsModalLgY').one('hidden.bs.modal',function (e) {
            $('#myFormsModalLgYTitle').html('');
            $('#myFormsModalLgYBody').html('');
            callBackFunc();
            $(e.currentTarget).unbind();
        });
    });
}

function getOneSgmntValForm(sbmtdSegValID,vwtype,sbmtdSegmentID,callBackFunc) {
    if (typeof sbmtdSegmentID === 'undefined' || callBackFunc === null) {
        sbmtdSegmentID = -1;
    }
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    var lnkArgs = 'grp=5&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdSegValID=' + sbmtdSegValID + '&sbmtdSegmentID=' + sbmtdSegmentID;
    doAjaxWthCallBck(lnkArgs,'sgmntValsDetailInfo','PasteDirect','','','',function () {
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();

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
            $('#allOtherInputData99').val(0);
            if (!$.fn.DataTable.isDataTable('#allSgmntValsTable')) {
                tableSg1 = $('#allSgmntValsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSgmntValsTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#allSgmntClsfctnsTable')) {
                var table1 = $('#allSgmntClsfctnsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allSgmntClsfctnsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allSgmntValsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allSgmntValsTable tbody').off('click','tr');
            $('#allSgmntValsTable tbody').off('mouseenter','tr');

            $('#allSgmntValsTable tbody').on('click','tr',function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');

                } else {
                    tableSg1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');

                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var segValID = typeof $('#allSgmntValsRow' + rndmNum + '_SgmntValID').val() === 'undefined' ? -1 : $('#allSgmntValsRow' + rndmNum + '_SgmntValID').val();
                getOneSgmntValForm(segValID,11);
            });
            $('#allSgmntValsTable tbody')
                .on('mouseenter','tr',function () {
                    if ($(this).hasClass('highlight')) {
                        $(this).removeClass('highlight');
                    } else {
                        tableSg1.$('tr.highlight').removeClass('highlight');
                        $(this).addClass('highlight');
                    }
                });

            $('#myFormsModalLgY').off('hidden.bs.modal');
            $('#myFormsModalLgY').one('hidden.bs.modal',function (e) {
                $('#myFormsModalLgYTitle').html('');
                $('#myFormsModalLgYBody').html('');
                callBackFunc();
                $(e.currentTarget).unbind();
            });

        });
    });
}

function saveSgmntValForm() {
    var sgValOrgDetOrgID = typeof $("#sgValOrgDetOrgID").val() === 'undefined' ? -1 : $("#sgValOrgDetOrgID").val();
    var segmentValueID = typeof $("#segmentValueID").val() === 'undefined' ? -1 : $("#segmentValueID").val();
    var sbmtdSegmentID = typeof $("#sbmtdSegmentID").val() === 'undefined' ? -1 : $("#sbmtdSegmentID").val();
    var dpndntSegmentID = typeof $("#dpndntSegmentID").val() === 'undefined' ? -1 : $("#dpndntSegmentID").val();
    var segmentValue = typeof $("#segmentValue").val() === 'undefined' ? '' : $("#segmentValue").val();
    var segmentValueDesc = typeof $("#segmentValueDesc").val() === 'undefined' ? '' : $("#segmentValueDesc").val();
    var sbmtdSegmentClsfctn = typeof $("#sbmtdSegmentClsfctn").val() === 'undefined' ? '' : $("#sbmtdSegmentClsfctn").val();
    var prntSegmentValueID = typeof $("#prntSegmentValueID").val() === 'undefined' ? '-1' : $("#prntSegmentValueID").val();
    var prntSegmentValue = typeof $("#prntSegmentValue").val() === 'undefined' ? '' : $("#prntSegmentValue").val();
    var dpndntSegmentValueID = typeof $("#dpndntSegmentValueID").val() === 'undefined' ? '-1' : $("#dpndntSegmentValueID").val();
    var dpndntSegmentValue = typeof $("#dpndntSegmentValue").val() === 'undefined' ? '' : $("#dpndntSegmentValue").val();
    var sgValLnkdSiteLocID = typeof $("#sgValLnkdSiteLocID").val() === 'undefined' ? -1 : $("#sgValLnkdSiteLocID").val();
    var sgValLnkdSiteLoc = typeof $("#sgValLnkdSiteLoc").val() === 'undefined' ? '' : $("#sgValLnkdSiteLoc").val();
    var sgValAllwdGrpType = typeof $("#sgValAllwdGrpType").val() === 'undefined' ? '' : $("#sgValAllwdGrpType").val();

    var sgValAllwdGrpID = typeof $("#sgValAllwdGrpID").val() === 'undefined' ? -1 : $("#sgValAllwdGrpID").val();
    var sgValAllwdGrpValue = typeof $("#sgValAllwdGrpValue").val() === 'undefined' ? '' : $("#sgValAllwdGrpValue").val();
    var sgValAcntType = typeof $("#sgValAcntType").val() === 'undefined' ? '' : $("#sgValAcntType").val();

    var sgValAcntClsfctn = typeof $("#sgValAcntClsfctn").val() === 'undefined' ? 1 : $("#sgValAcntClsfctn").val();
    var sgValCtrlAcntID = typeof $("#sgValCtrlAcntID").val() === 'undefined' ? -1 : $("#sgValCtrlAcntID").val();
    var sgValCtrlAcnt = typeof $("#sgValCtrlAcnt").val() === 'undefined' ? '' : $("#sgValCtrlAcnt").val();
    var sgValMppdAcntID = typeof $("#sgValMppdAcntID").val() === 'undefined' ? -1 : $("#sgValMppdAcntID").val();
    var sgValMppdAcnt = typeof $("#sgValMppdAcnt").val() === 'undefined' ? '' : $("#sgValMppdAcnt").val();

    var sgValIsEnabled = typeof $("input[name='sgValIsEnabled']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValIsEnabled']:checked").val();
    var sgValCmbntnsAllwd = typeof $("input[name='sgValCmbntnsAllwd']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValCmbntnsAllwd']:checked").val();
    var sgValIsPrntAcnt = typeof $("input[name='sgValIsPrntAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValIsPrntAcnt']:checked").val();
    var sgValIsContraAcnt = typeof $("input[name='sgValIsContraAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValIsContraAcnt']:checked").val();
    var sgValIsRetErngsAcnt = typeof $("input[name='sgValIsRetErngsAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValIsRetErngsAcnt']:checked").val();
    var sgValIsNetIncmAcnt = typeof $("input[name='sgValIsNetIncmAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValIsNetIncmAcnt']:checked").val();
    var sgValIsSuspnsAcnt = typeof $("input[name='sgValIsSuspnsAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValIsSuspnsAcnt']:checked").val();
    var sgValHsSubldgrAcnt = typeof $("input[name='sgValHsSubldgrAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='sgValHsSubldgrAcnt']:checked").val();

    var errMsg = "";
    if (sgValOrgDetOrgID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Organization cannot be empty!</span></p>';
    }
    if (sbmtdSegmentID <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Linked Account Segment cannot be empty!</span></p>';
    }
    if (segmentValue.trim() === '' || segmentValueDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Segment Value/Description cannot be empty!</span></p>';
    }
    if (dpndntSegmentID > 0 && dpndntSegmentValue.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Dependent Segment Value cannot be empty!</span></p>';
    }
    if (sbmtdSegmentClsfctn.trim() === 'NaturalAccount' && sgValAcntType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Account Type cannot be empty!</span></p>';
    }
    var slctdSegmentClsfctns = "";
    var isVld = true;
    var errMsg = "";
    $('#allSgmntClsfctnsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var majClsfctn = $('#allSgmntClsfctnsRow' + rndmNum + '_MajClsfctn').val();
                var minClsfctn = $('#allSgmntClsfctnsRow' + rndmNum + '_MinClsfctn').val();
                if (majClsfctn.trim() === '' && minClsfctn.trim() === '') {
                    $('#allSgmntClsfctnsRow' + rndmNum + '_MajClsfctn').addClass('rho-error');
                    $('#allSgmntClsfctnsRow' + rndmNum + '_MinClsfctn').addClass('rho-error');
                } else {
                    $('#allSgmntClsfctnsRow' + rndmNum + '_MajClsfctn').removeClass('rho-error');
                    $('#allSgmntClsfctnsRow' + rndmNum + '_MinClsfctn').removeClass('rho-error');
                    slctdSegmentClsfctns = slctdSegmentClsfctns + $('#allSgmntClsfctnsRow' + rndmNum + '_ClsfctnID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#allSgmntClsfctnsRow' + rndmNum + '_MajClsfctn').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#allSgmntClsfctnsRow' + rndmNum + '_MinClsfctn').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
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
        title: 'Save Segment Values',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Segment Values...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',5);
    formData.append('typ',1);
    formData.append('pg',1);
    formData.append('q','UPDATE');
    formData.append('actyp',8);
    formData.append('sgValOrgDetOrgID',sgValOrgDetOrgID);
    formData.append('segmentValueID',segmentValueID);
    formData.append('sbmtdSegmentID',sbmtdSegmentID);
    formData.append('segmentValue',segmentValue);
    formData.append('segmentValueDesc',segmentValueDesc);

    formData.append('dpndntSegmentID',dpndntSegmentID);
    formData.append('sbmtdSegmentClsfctn',sbmtdSegmentClsfctn);
    formData.append('prntSegmentValueID',prntSegmentValueID);
    formData.append('prntSegmentValue',prntSegmentValue);
    formData.append('prntSegmentValueID',prntSegmentValueID);
    formData.append('dpndntSegmentValueID',dpndntSegmentValueID);
    formData.append('dpndntSegmentValue',dpndntSegmentValue);
    formData.append('sgValLnkdSiteLocID',sgValLnkdSiteLocID);
    formData.append('sgValLnkdSiteLoc',sgValLnkdSiteLoc);
    formData.append('sgValAllwdGrpType',sgValAllwdGrpType);
    formData.append('sgValAllwdGrpID',sgValAllwdGrpID);
    formData.append('sgValAllwdGrpValue',sgValAllwdGrpValue);
    formData.append('sgValIsEnabled',sgValIsEnabled);
    formData.append('sgValCmbntnsAllwd',sgValCmbntnsAllwd);
    formData.append('sgValIsPrntAcnt',sgValIsPrntAcnt);
    formData.append('sgValIsContraAcnt',sgValIsContraAcnt);

    formData.append('sgValIsRetErngsAcnt',sgValIsRetErngsAcnt);
    formData.append('sgValIsNetIncmAcnt',sgValIsNetIncmAcnt);
    formData.append('sgValIsSuspnsAcnt',sgValIsSuspnsAcnt);
    formData.append('sgValHsSubldgrAcnt',sgValHsSubldgrAcnt);
    formData.append('sgValAcntType',sgValAcntType);
    formData.append('sgValAcntClsfctn',sgValAcntClsfctn);
    formData.append('sgValCtrlAcntID',sgValCtrlAcntID);
    formData.append('sgValCtrlAcnt',sgValCtrlAcnt);
    formData.append('sgValMppdAcntID',sgValMppdAcntID);
    formData.append('sgValMppdAcnt',sgValMppdAcnt);
    formData.append('slctdSegmentClsfctns',slctdSegmentClsfctns);
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
                            getAllSgmntVals('','#myFormsModalLgYBody','grp=5&typ=1&pg=1&vtyp=9&sbmtdSegmentID=' + sbmtdSegmentID + '&segmentValue=' + segmentValue);
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

function getAllSgmntVals(actionText,slctr,linkArgs) {
    var srchFor = typeof $("#allSgmntValsSrchFor").val() === 'undefined' ? '%' : $("#allSgmntValsSrchFor").val();
    var srchIn = typeof $("#allSgmntValsSrchIn").val() === 'undefined' ? 'Both' : $("#allSgmntValsSrchIn").val();
    var pageNo = typeof $("#allSgmntValsPageNo").val() === 'undefined' ? 1 : $("#allSgmntValsPageNo").val();
    var limitSze = typeof $("#allSgmntValsDsplySze").val() === 'undefined' ? 10 : $("#allSgmntValsDsplySze").val();
    var sortBy = typeof $("#allSgmntValsSortBy").val() === 'undefined' ? '' : $("#allSgmntValsSortBy").val();
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

function enterKeyFuncSgmntVals(e,actionText,slctr,linkArgs) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode == 13) {
        getAllSgmntVals(actionText,slctr,linkArgs);
    }
}

function delSgmntVals(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var pKeyNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_SgmntValID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_SgmntValID').val();
        /*pKeyNm = $('#' + rowPrfxNm + rndmNum + '_SgmntName').val();*/
        var $tds = $('#' + rowIDAttrb).find('td');
        pKeyNm = $.trim($tds.eq(1).text());
    }
    var msgPrt = "Account Segment Value";
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 8,
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

function delSgValRptClsfctn(rowIDAttrb) {
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
    var msgPrt = "Report Classifications";
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
                                    grp: 5,
                                    typ: 1,
                                    pg: 1,
                                    q: 'DELETE',
                                    actyp: 9,
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

function getAccountsDetForm(pKeyID,pKeyTitle,actionTxt,callBackFunc) {
    if (typeof callBackFunc === 'undefined' || callBackFunc === null) {
        callBackFunc = function () {
            var tstabcd = 1;
        };
    }
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var lnkArgs = 'grp=6&typ=1&pg=1&vtyp=1&sbmtdAccountID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalLx',actionTxt,pKeyTitle,'myFormsModalLxTitle','myFormsModalLxBody',function () {
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
        $('#allOtherInputData99').val(0);
        if (!$.fn.DataTable.isDataTable('#acbRptClsfctnsTable')) {
            var table1 = $('#acbRptClsfctnsTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#acbRptClsfctnsTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('#accbAccntsDetForm').submit(function (e) {
            e.preventDefault();
            return false;
        });

        $('#myFormsModalLx').off('hidden.bs.modal');
        $('#myFormsModalLx').one('hidden.bs.modal',function (e) {
            $('#myFormsModalLxTitle').html('');
            $('#myFormsModalLxBody').html('');
            callBackFunc();
            $(e.currentTarget).unbind();
        });
    });
}

function delAcbRptClsfctn(rowIDAttrb) {
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
    var msgPrt = "Report Classifications";
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
                                    grp: 6,
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

function getAcntSgmtBrkdwnForm(pKeyID,vwtype,sgValElmntIDPrfx,accntNumElmntID,accntNameElmntID,actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    var accntSgmnt1ValID = typeof $("#" + sgValElmntIDPrfx + "1ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "1ValID").val();
    var accntSgmnt2ValID = typeof $("#" + sgValElmntIDPrfx + "2ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "2ValID").val();
    var accntSgmnt3ValID = typeof $("#" + sgValElmntIDPrfx + "3ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "3ValID").val();
    var accntSgmnt4ValID = typeof $("#" + sgValElmntIDPrfx + "4ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "4ValID").val();
    var accntSgmnt5ValID = typeof $("#" + sgValElmntIDPrfx + "5ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "5ValID").val();
    var accntSgmnt6ValID = typeof $("#" + sgValElmntIDPrfx + "6ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "6ValID").val();
    var accntSgmnt7ValID = typeof $("#" + sgValElmntIDPrfx + "7ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "7ValID").val();
    var accntSgmnt8ValID = typeof $("#" + sgValElmntIDPrfx + "8ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "8ValID").val();
    var accntSgmnt9ValID = typeof $("#" + sgValElmntIDPrfx + "9ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "9ValID").val();
    var accntSgmnt10ValID = typeof $("#" + sgValElmntIDPrfx + "10ValID").val() === 'undefined' ? -1 : $("#" + sgValElmntIDPrfx + "10ValID").val();
    var slctdSgmntValIDs = accntSgmnt1ValID + "|" + accntSgmnt2ValID + "|" + accntSgmnt3ValID + "|" +
        accntSgmnt4ValID + "|" + accntSgmnt5ValID + "|" + accntSgmnt6ValID + "|" +
        accntSgmnt7ValID + "|" + accntSgmnt8ValID + "|" + accntSgmnt9ValID + "|" +
        accntSgmnt10ValID;
    var lnkArgs = 'grp=6&typ=1&pg=1&vtyp=' + vwtype + '&sbmtdAccountID=' + pKeyID +
        '&slctdSgmntValIDs=' + slctdSgmntValIDs + '&sgValElmntIDPrfx=' + sgValElmntIDPrfx +
        '&accntNumElmntID=' + accntNumElmntID + '&accntNameElmntID=' + accntNameElmntID;
    doAjaxWthCallBck(lnkArgs,'myFormsModalx',actionTxt,'Account Segments Breakdown','myFormsModalxTitle','myFormsModalxBody',function () {
        if (!$.fn.DataTable.isDataTable('#accntSgmntsBrkDwnTable')) {
            var table1 = $('#accntSgmntsBrkDwnTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#accntSgmntsBrkDwnTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function applyNewAcntSgmtBrkdwn(modalID,sgValElmntIDPrfx,accntNumElmntID,accntNameElmntID) {
    var slctdSgmntsAccntNum = "";
    var slctdSgmntsAccntName = "";
    $('#accntSgmntsBrkDwnTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var sgmntValue = $('#accntSgmntsBrkDwn' + rndmNum + '_SegValue').val();
                var sgmntValueDesc = $('#accntSgmntsBrkDwn' + rndmNum + '_SegValueDesc').val();
                var sgmntNumber = $('#accntSgmntsBrkDwn' + rndmNum + '_SegmentNum').val();
                var sgmntValueID = typeof $('#accntSgmntsBrkDwn' + rndmNum + '_SegValID').val() === 'undefined' ? -1 : $('#accntSgmntsBrkDwn' + rndmNum + '_SegValID').val();

                $("#" + sgValElmntIDPrfx + '' + sgmntNumber + "ValID").val(sgmntValueID);
                if (sgmntValue.trim() === '' && sgmntValueDesc.trim() === '') { } else {
                    slctdSgmntsAccntNum = slctdSgmntsAccntNum + '' + sgmntValue;
                    slctdSgmntsAccntName = slctdSgmntsAccntName + ' ' + sgmntValueDesc;
                }

            }
        }
    });
    /*alert(slctdSgmntsAccntName.trim());
     alert(slctdSgmntsAccntNum.trim());*/
    $('#' + accntNameElmntID).val(slctdSgmntsAccntName.trim());
    $('#' + accntNumElmntID).val(slctdSgmntsAccntNum.trim());
    $('#' + modalID).modal('hide');
}

function clearAcntSgmtBrkdwn(rowIDAttribute) {
    var rndmNum = rowIDAttribute.split("_")[1];
    $('#accntSgmntsBrkDwn' + rndmNum + '_SegVal').val('');
    $('#accntSgmntsBrkDwn' + rndmNum + '_SegValue').val('');
    $('#accntSgmntsBrkDwn' + rndmNum + '_SegValueDesc').val('');
    $('#accntSgmntsBrkDwn' + rndmNum + '_SegValID').val(-1);
}

function saveAccountsDetForm() {
    var sbmtdAccbGrpOrgID = typeof $("#sbmtdAccbGrpOrgID").val() === 'undefined' ? -1 : $("#sbmtdAccbGrpOrgID").val();
    var sbmtdAccountID = typeof $("#sbmtdAccountID").val() === 'undefined' ? -1 : $("#sbmtdAccountID").val();
    var acbAccountNum = typeof $("#acbAccountNum").val() === 'undefined' ? '' : $("#acbAccountNum").val();
    var acbAccountNumDesc = typeof $("#acbAccountNumDesc").val() === 'undefined' ? '' : $("#acbAccountNumDesc").val();
    var acbPrntAccountNumID = typeof $("#acbPrntAccountNumID").val() === 'undefined' ? -1 : $("#acbPrntAccountNumID").val();
    var acbPrntAccountNum = typeof $("#acbPrntAccountNum").val() === 'undefined' ? '' : $("#acbPrntAccountNum").val();
    var acbAcntCurncyID = typeof $("#acbAcntCurncyID").val() === 'undefined' ? -1 : $("#acbAcntCurncyID").val();
    var acbAcntCurncy = typeof $("#acbAcntCurncy").val() === 'undefined' ? '' : $("#acbAcntCurncy").val();
    var acbAcntAcntType = typeof $("#acbAcntAcntType").val() === 'undefined' ? '' : $("#acbAcntAcntType").val();

    var acbAcntAcntClsfctn = typeof $("#acbAcntAcntClsfctn").val() === 'undefined' ? 1 : $("#acbAcntAcntClsfctn").val();
    var acbAcntCtrlAcntID = typeof $("#acbAcntCtrlAcntID").val() === 'undefined' ? -1 : $("#acbAcntCtrlAcntID").val();
    var acbAcntCtrlAcnt = typeof $("#acbAcntCtrlAcnt").val() === 'undefined' ? '' : $("#acbAcntCtrlAcnt").val();
    var acbAcntMppdAcntID = typeof $("#acbAcntMppdAcntID").val() === 'undefined' ? -1 : $("#acbAcntMppdAcntID").val();
    var acbAcntMppdAcnt = typeof $("#acbAcntMppdAcnt").val() === 'undefined' ? '' : $("#acbAcntMppdAcnt").val();

    var acbAcntIsEnabled = typeof $("input[name='acbAcntIsEnabled']:checked").val() === 'undefined' ? 'NO' : $("input[name='acbAcntIsEnabled']:checked").val();
    var acbAcntIsPrntAcnt = typeof $("input[name='acbAcntIsPrntAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='acbAcntIsPrntAcnt']:checked").val();
    var acbAcntIsContraAcnt = typeof $("input[name='acbAcntIsContraAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='acbAcntIsContraAcnt']:checked").val();
    var acbAcntIsRetErngsAcnt = typeof $("input[name='acbAcntIsRetErngsAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='acbAcntIsRetErngsAcnt']:checked").val();
    var acbAcntIsNetIncmAcnt = typeof $("input[name='acbAcntIsNetIncmAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='acbAcntIsNetIncmAcnt']:checked").val();
    var acbAcntIsSuspnsAcnt = typeof $("input[name='acbAcntIsSuspnsAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='acbAcntIsSuspnsAcnt']:checked").val();
    var acbAcntHsSubldgrAcnt = typeof $("input[name='acbAcntHsSubldgrAcnt']:checked").val() === 'undefined' ? 'NO' : $("input[name='acbAcntHsSubldgrAcnt']:checked").val();

    var accntSgmnt1ValID = typeof $("#accntSgmnt1ValID").val() === 'undefined' ? -1 : $("#accntSgmnt1ValID").val();
    var accntSgmnt2ValID = typeof $("#accntSgmnt2ValID").val() === 'undefined' ? -1 : $("#accntSgmnt2ValID").val();
    var accntSgmnt3ValID = typeof $("#accntSgmnt3ValID").val() === 'undefined' ? -1 : $("#accntSgmnt3ValID").val();
    var accntSgmnt4ValID = typeof $("#accntSgmnt4ValID").val() === 'undefined' ? -1 : $("#accntSgmnt4ValID").val();
    var accntSgmnt5ValID = typeof $("#accntSgmnt5ValID").val() === 'undefined' ? -1 : $("#accntSgmnt5ValID").val();
    var accntSgmnt6ValID = typeof $("#accntSgmnt6ValID").val() === 'undefined' ? -1 : $("#accntSgmnt6ValID").val();
    var accntSgmnt7ValID = typeof $("#accntSgmnt7ValID").val() === 'undefined' ? -1 : $("#accntSgmnt7ValID").val();
    var accntSgmnt8ValID = typeof $("#accntSgmnt8ValID").val() === 'undefined' ? -1 : $("#accntSgmnt8ValID").val();
    var accntSgmnt9ValID = typeof $("#accntSgmnt9ValID").val() === 'undefined' ? -1 : $("#accntSgmnt9ValID").val();
    var accntSgmnt10ValID = typeof $("#accntSgmnt10ValID").val() === 'undefined' ? -1 : $("#accntSgmnt10ValID").val();

    var errMsg = "";
    if (acbAccountNum.trim() === '' || acbAccountNumDesc.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Account Number/Description cannot be empty!</span></p>';
    }
    if (acbAcntAcntType.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Account Type cannot be empty!</span></p>';
    }
    var slctdAccntClsfctns = "";
    var isVld = true;
    var errMsg = "";
    $('#acbRptClsfctnsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var majClsfctn = $('#acbRptClsfctnsRow' + rndmNum + '_MajClsfctn').val();
                var minClsfctn = $('#acbRptClsfctnsRow' + rndmNum + '_MinClsfctn').val();
                if (majClsfctn.trim() === '' && minClsfctn.trim() === '') {
                    $('#acbRptClsfctnsRow' + rndmNum + '_MajClsfctn').addClass('rho-error');
                    $('#acbRptClsfctnsRow' + rndmNum + '_MinClsfctn').addClass('rho-error');
                } else {
                    $('#acbRptClsfctnsRow' + rndmNum + '_MajClsfctn').removeClass('rho-error');
                    $('#acbRptClsfctnsRow' + rndmNum + '_MinClsfctn').removeClass('rho-error');
                    slctdAccntClsfctns = slctdAccntClsfctns + $('#acbRptClsfctnsRow' + rndmNum + '_ClsfctnID').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#acbRptClsfctnsRow' + rndmNum + '_MajClsfctn').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "~" +
                        $('#acbRptClsfctnsRow' + rndmNum + '_MinClsfctn').val().replace(/(~)/g,"{-;-;}").replace(/(\|)/g,"{:;:;}") + "|";
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
        title: 'Save Account',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Saving Account...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',6);
    formData.append('typ',1);
    formData.append('pg',1);
    formData.append('q','UPDATE');
    formData.append('actyp',1);
    formData.append('sbmtdAccbGrpOrgID',sbmtdAccbGrpOrgID);
    formData.append('sbmtdAccountID',sbmtdAccountID);
    formData.append('acbAccountNum',acbAccountNum);
    formData.append('acbAccountNumDesc',acbAccountNumDesc);
    formData.append('acbPrntAccountNumID',acbPrntAccountNumID);

    formData.append('acbPrntAccountNum',acbPrntAccountNum);
    formData.append('acbAcntIsEnabled',acbAcntIsEnabled);
    formData.append('acbAcntIsPrntAcnt',acbAcntIsPrntAcnt);
    formData.append('acbAcntIsContraAcnt',acbAcntIsContraAcnt);
    formData.append('acbAcntIsRetErngsAcnt',acbAcntIsRetErngsAcnt);
    formData.append('acbAcntIsNetIncmAcnt',acbAcntIsNetIncmAcnt);
    formData.append('acbAcntIsSuspnsAcnt',acbAcntIsSuspnsAcnt);
    formData.append('acbAcntHsSubldgrAcnt',acbAcntHsSubldgrAcnt);
    formData.append('acbAcntAcntType',acbAcntAcntType);
    formData.append('acbAcntAcntClsfctn',acbAcntAcntClsfctn);
    formData.append('acbAcntCtrlAcntID',acbAcntCtrlAcntID);
    formData.append('acbAcntCtrlAcnt',acbAcntCtrlAcnt);
    formData.append('acbAcntMppdAcntID',acbAcntMppdAcntID);
    formData.append('acbAcntMppdAcnt',acbAcntMppdAcnt);

    formData.append('acbAcntCurncyID',acbAcntCurncyID);
    formData.append('acbAcntCurncy',acbAcntCurncy);

    formData.append('accntSgmnt1ValID',accntSgmnt1ValID);
    formData.append('accntSgmnt2ValID',accntSgmnt2ValID);
    formData.append('accntSgmnt3ValID',accntSgmnt3ValID);
    formData.append('accntSgmnt4ValID',accntSgmnt4ValID);
    formData.append('accntSgmnt5ValID',accntSgmnt5ValID);
    formData.append('accntSgmnt6ValID',accntSgmnt6ValID);
    formData.append('accntSgmnt7ValID',accntSgmnt7ValID);
    formData.append('accntSgmnt8ValID',accntSgmnt8ValID);
    formData.append('accntSgmnt9ValID',accntSgmnt9ValID);
    formData.append('accntSgmnt10ValID',accntSgmnt10ValID);
    formData.append('slctdAccntClsfctns',slctdAccntClsfctns);

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
                            sbmtdAccountID = data.sbmtdAccountID;
                            getAccountsDetForm(sbmtdAccountID,'Accounts Detail Information','ReloadDialog',
                                function () {
                                    getAccbAcntChrt('','#allmodules','grp=6&typ=1&pg=1&vtyp=0');
                                });
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


function accbPymntsPayMthdChng() {
    var accbPymntsPayMthd = typeof $("#accbPymntsPayMthdID option:selected").text() === 'undefined' ? '' : $("#accbPymntsPayMthdID option:selected").text();
    if (accbPymntsPayMthd.indexOf("Prepayment") !== -1) {
        $('#accbPymntsPrepayFrmGroup').removeClass('hideNotice');
    } else {
        $('#accbPymntsPrepayFrmGroup').addClass('hideNotice');
    }
}

function getOneAccbPayInvcForm(pKeyID,docType,actionTxt,pOrgnPayID,extraPKeyID,extraPKeyType,
    dfltAmountTndrdFld,invcSrc,pCstmrSpplrElmtID,invCurElmntID,musAllwDues,srcCaller,extraInptParams) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof pOrgnPayID === 'undefined' || pOrgnPayID === null) {
        pOrgnPayID = -1;
    }

    if (typeof extraPKeyID === 'undefined' || extraPKeyID === null) {
        extraPKeyID = -1;
    }
    if (typeof extraPKeyType === 'undefined' || extraPKeyType === null) {
        extraPKeyType = '';
    }
    if (typeof extraInptParams === 'undefined' || extraInptParams === null) {
        extraInptParams = '';
    }
    if (typeof dfltAmountTndrdFld === 'undefined' || dfltAmountTndrdFld === null) {
        dfltAmountTndrd = 'XX1_RHO_UNDEFINED_1';
    }
    var dfltAmountTndrd = typeof $("#" + dfltAmountTndrdFld).val() === 'undefined' ? '0.00' : $("#" + dfltAmountTndrdFld).val();

    if (typeof pCstmrSpplrElmtID === 'undefined' || pCstmrSpplrElmtID === null) {
        pCstmrSpplrElmtID = 'XX1_RHO_UNDEFINED_1';
    }
    var pCstmrSpplrID = typeof $("#" + pCstmrSpplrElmtID).val() === 'undefined' ? '-1' : $("#" + pCstmrSpplrElmtID).val();
    if (typeof invCurElmntID === 'undefined' || invCurElmntID === null) {
        invCurElmntID = 'XX1_RHO_UNDEFINED_1';
    }
    var invCurNm = typeof $("#" + invCurElmntID).val() === 'undefined' ? 'GHS' : $("#" + invCurElmntID).val();
    if (typeof invcSrc === 'undefined' || invcSrc === null) {
        invcSrc = 'NORMAL';
    }
    if (typeof musAllwDues === 'undefined' || musAllwDues === null) {
        musAllwDues = 'NO';
    }
    if (typeof srcCaller === 'undefined' || srcCaller === null) {
        srcCaller = 'SALES';
    }
    var lnkArgs = 'grp=6&typ=1&pg=12&vtyp=101&sbmtdDocumentID=' + pKeyID + '&sbmtdDocumentType=' + docType +
        '&sbmtdAccbPymntsID=' + pOrgnPayID + '&dfltAmountTndrd=' + dfltAmountTndrd.replace(/[^-?0-9\.]/g,'') +
        '&accbPymntsInvcSpplrID=' + pCstmrSpplrID + '&accbPymntsInvcCur=' + invCurNm +
        '&sbmtdExtraPKeyID=' + extraPKeyID + '&sbmtdExtraPKeyType=' + extraPKeyType;

    doAjaxWthCallBck(lnkArgs,'myFormsModal',actionTxt,'Pay Invoice (' + docType + ')','myFormsModalTitle','myFormsModalBody',function () {
        $('#accbPymntsPayInvcForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('[data-toggle="tooltip"]').tooltip();
        var sbmtdAccbPymntsID = typeof $("#sbmtdAccbPymntsID").val() === 'undefined' ? -1 : $("#sbmtdAccbPymntsID").val();
        if (sbmtdAccbPymntsID <= 0) {
            $("#accbPymntsGvnAmnt").focus(function () {
                $(this).select();
            });
            if (invcSrc === 'QUICK_SALE') {
                $('#saveQuickInvPayBtn').focus();
                $("#saveQuickInvPayBtn").select();
            } else {
                $("#accbPymntsGvnAmnt").focus();
                $("#accbPymntsGvnAmnt").select();
            }
        } else {
            $("#accbPymntsDesc").focus();
            $("#accbPymntsDesc").select();
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
        $('#allOtherInputData99').val(0);
        accbPymntsPayMthdChng();
        $('#myFormsModal').off('hidden.bs.modal');
        $('#myFormsModal').one('hidden.bs.modal',function (e) {
            $('#myFormsModalTitle').html('');
            $('#myFormsModalBody').html('');
            if (sbmtdAccbPymntsID > 0) {
                if (docType === "Supplier Payments") {
                    getOneAccbPymntsHstryForm(pKeyID,103,'ReloadDialog',pKeyID,'Payable Invoice','Supplier Payments');
                } else {
                    if (extraPKeyID > 0 && extraPKeyType === 'Sales Invoice') {
                        getOneAccbPymntsHstryForm(pKeyID,103,'ReloadDialog',extraPKeyID,extraPKeyType,'Customer Payments');
                    } else {
                        getOneAccbPymntsHstryForm(pKeyID,103,'ReloadDialog',pKeyID,'Receivable Invoice','Customer Payments');
                    }
                }
            } else if (docType === "Supplier Payments") {
                getOneAccbPyblsInvcForm(pKeyID,1,'ReloadDialog');
            } else if (docType === "Customer Payments") {
                if (srcCaller === 'QUICK_PAY') {
                    if (extraPKeyID > 0) {
                        getOneScmSalesInvcForm(extraPKeyID,3,'ReloadDialog',extraPKeyType,musAllwDues,srcCaller);
                    }
                } else {
                    if (invcSrc === 'QUICK_SALE') {
                        getScmSalesInvc('','#allmodules','grp=12&typ=1&pg=1&vtyp=1');
                    } else {
                        if (extraPKeyID > 0 && extraPKeyType === 'Sales Invoice') {
                            getOneScmSalesInvcForm(extraPKeyID,3,'ReloadDialog',extraPKeyType);
                        } else if (extraPKeyID > 0 && extraPKeyType === 'Sales Invoice-Hospitality') {
                            var extraInptPrmArray = extraInptParams.split("|");
                            getOneHotlChckinDocForm(extraPKeyID,3,'ReloadDialog',extraPKeyType,extraInptPrmArray[0],extraInptPrmArray[1],extraInptPrmArray[2],extraInptPrmArray[3],extraInptPrmArray[4]);
                        } else {
                            getOneAccbRcvblsInvcForm(pKeyID,1,'ReloadDialog','',extraPKeyID,extraPKeyType);
                        }
                    }
                }
            }
            $(e.currentTarget).unbind();
        });
    });
}

function quickInvcPayAmntChng() {
    fmtAsNumber('accbPymntsGvnAmnt');
    var accbPymntsGvnAmnt = typeof $("#accbPymntsGvnAmnt").val() === 'undefined' ? '0' : $("#accbPymntsGvnAmnt").val().replace(/[^-?0-9\.]/g,'');
    var accbPymntsPaidAmnt = typeof $("#accbPymntsPaidAmnt").val() === 'undefined' ? '0' : $("#accbPymntsPaidAmnt").val().replace(/[^-?0-9\.]/g,'');
    var accbPymntsChngBals = Number(accbPymntsPaidAmnt.replace(/[^-?0-9\.]/g,'')) - Number(accbPymntsGvnAmnt.replace(/[^-?0-9\.]/g,''));
    $("#accbPymntsChngBals").val(accbPymntsChngBals);
    if (accbPymntsChngBals <= 0) {
        $('#accbPymntsChngBals').css('color','green');
        $('#accbPymntsInvcCur3').css('color','green');
    } else {
        $('#accbPymntsChngBals').css('color','red');
        $('#accbPymntsInvcCur3').css('color','red');
    }
    fmtAsNumber('accbPymntsChngBals');
    $('#saveQuickInvPayBtn').focus();
    $("#saveQuickInvPayBtn").select();
}

function quickInvcPayAmntKeyFunc(e) {
    var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
    if (charCode === 13) {
        $('#saveQuickInvPayBtn').focus();
        $("#saveQuickInvPayBtn").select();
    }
    //return false;
}

function afterPrepayDocSlctnQckInvPay() {
    var accbPymntsPrepayDocIDs = typeof $("#accbPymntsPrepayDocID").val() === 'undefined' ? '-1' : $("#accbPymntsPrepayDocID").val();
    var accbPymntsPrepayDocLovNm = typeof $("#accbPymntsPrepayDocLovNm").val() === 'undefined' ? '' : $("#accbPymntsPrepayDocLovNm").val();
    if (accbPymntsPrepayDocIDs.trim() !== "-1") {
        getMsgAsyncSilent('grp=1&typ=11&q=Check Session',function () {
            $body = $("body");
            $body.removeClass("mdlloadingDiag");
            $body.removeClass("mdlloading");
            var obj;
            var formData = new FormData();
            formData.append('grp',6);
            formData.append('typ',1);
            formData.append('pg',12);
            formData.append('q','VIEW');
            formData.append('vtyp',102);
            formData.append('accbPymntsPrepayDocIDs',accbPymntsPrepayDocIDs);
            formData.append('accbPymntsPrepayDocLovNm',accbPymntsPrepayDocLovNm);
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    console.warn(data);
                    if (data.message.indexOf("Success") !== -1) {
                        $('#accbPymntsGvnAmnt').val(data.accbPymntsGvnAmnt);
                        quickInvcPayAmntChng();
                    }
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.warn(jqXHR.responseText);
                }
            });
        });
    } else {
        $('#accbPymntsGvnAmnt').val('0.00');
        quickInvcPayAmntChng();
    }
}

function saveQuickInvPayForm() {
    var sbmtdAccbPymntsID = typeof $("#sbmtdAccbPymntsID").val() === 'undefined' ? -1 : $("#sbmtdAccbPymntsID").val();
    var sbmtdDocumentID = typeof $("#sbmtdDocumentID").val() === 'undefined' ? -1 : $("#sbmtdDocumentID").val();
    var sbmtdDocumentType = typeof $("#sbmtdDocumentType").val() === 'undefined' ? '' : $("#sbmtdDocumentType").val();
    var sbmtdExtraPKeyID = typeof $("#sbmtdExtraPKeyID").val() === 'undefined' ? -1 : $("#sbmtdExtraPKeyID").val();
    var sbmtdExtraPKeyType = typeof $("#sbmtdExtraPKeyType").val() === 'undefined' ? '' : $("#sbmtdExtraPKeyType").val();
    var accbPymntsPayMthdID = typeof $("#accbPymntsPayMthdID").val() === 'undefined' ? -1 : $("#accbPymntsPayMthdID").val();
    var accbPymntsPayMthdNm = typeof $("#accbPymntsPayMthdID option:selected").text() === 'undefined' ? '' : $("#accbPymntsPayMthdID option:selected").text();
    var accbPymntsPrepayDocIDs = typeof $("#accbPymntsPrepayDocID").val() === 'undefined' ? '-1' : $("#accbPymntsPrepayDocID").val();
    var accbPymntsInvcSpplrID = typeof $("#accbPymntsInvcSpplrID").val() === 'undefined' ? -1 : $("#accbPymntsInvcSpplrID").val();
    var accbPymntsDesc = typeof $("#accbPymntsDesc").val() === 'undefined' ? '' : $("#accbPymntsDesc").val();
    var accbPymntsDfltTrnsDte = typeof $("#accbPymntsDfltTrnsDte").val() === 'undefined' ? '' : $("#accbPymntsDfltTrnsDte").val();
    var accbPymntsGvnAmnt = typeof $("#accbPymntsGvnAmnt").val() === 'undefined' ? '0' : $("#accbPymntsGvnAmnt").val().replace(/[^-?0-9\.]/g,'');
    var accbPymntsPaidAmnt = typeof $("#accbPymntsPaidAmnt").val() === 'undefined' ? '0' : $("#accbPymntsPaidAmnt").val().replace(/[^-?0-9\.]/g,'');
    var accbPymntsChngBals = typeof $("#accbPymntsChngBals").val() === 'undefined' ? '0' : $("#accbPymntsChngBals").val().replace(/[^-?0-9\.]/g,'');
    fmtAsNumber('accbPymntsGvnAmnt').toFixed(2);
    fmtAsNumber('accbPymntsPaidAmnt').toFixed(2);
    fmtAsNumber('accbPymntsChngBals').toFixed(2);
    var accbPymntsChqName = typeof $("#accbPymntsChqName").val() === 'undefined' ? '' : $("#accbPymntsChqName").val();
    var accbPymntsChqNumber = typeof $("#accbPymntsChqNumber").val() === 'undefined' ? '' : $("#accbPymntsChqNumber").val();
    var accbPymntsExpiryDate = typeof $("#accbPymntsExpiryDate").val() === 'undefined' ? '' : $("#accbPymntsExpiryDate").val();
    var accbPymntsSignCode = typeof $("#accbPymntsSignCode").val() === 'undefined' ? '' : $("#accbPymntsSignCode").val();
    var errMsg = "";
    if (Number(accbPymntsPayMthdID.replace(/[^-?0-9\.]/g,'')) <= 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Payment Method cannot be empty!</span></p>';
    }
    if (Number(accbPymntsGvnAmnt.replace(/[^-?0-9\.]/g,'')) === 0) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Amount Given cannot be empty!</span></p>';
    }
    if (accbPymntsDesc.trim() === '' || accbPymntsDfltTrnsDte.trim() === '') {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Description and Date cannot be empty!</span></p>';
    }
    /*alert(accbPymntsPayMthdNm.indexOf("Cheque"));
     alert(accbPymntsPayMthdNm.indexOf("Check"));
     alert(accbPymntsPayMthdNm);*/
    if ((accbPymntsPayMthdNm.indexOf("Cheque") !== -1 || accbPymntsPayMthdNm.indexOf("Check") !== -1) &&
        (accbPymntsChqName.trim() === '' || accbPymntsChqNumber.trim() === '')) {
        errMsg += '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
            'font-weight:bold;color:red;">Cheque Name and Number cannot be empty for this Payment Method!</span></p>';
        $("#accbPymntsChqNumber").focus();
    }
    if (rhotrim(errMsg,'; ') !== '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: errMsg
        });
        return false;
    }
    var dialog = bootbox.alert({
        title: 'Process Payment',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Processing Payment...Please Wait...</p>',
        callback: function () {
            $('#myFormsModal').modal('hide');
        }
    });
    var formData = new FormData();
    formData.append('grp',6);
    formData.append('typ',1);
    formData.append('pg',12);
    formData.append('q','UPDATE');
    formData.append('actyp',5);
    formData.append('accbPymntsPayMthdID',accbPymntsPayMthdID);
    formData.append('accbPymntsPrepayDocIDs',accbPymntsPrepayDocIDs);
    formData.append('accbPymntsInvcSpplrID',accbPymntsInvcSpplrID);
    formData.append('accbPymntsDesc',accbPymntsDesc);
    formData.append('accbPymntsDfltTrnsDte',accbPymntsDfltTrnsDte);
    formData.append('accbPymntsGvnAmnt',accbPymntsGvnAmnt.replace(/[^-?0-9\.]/g,''));
    formData.append('accbPymntsPaidAmnt',accbPymntsPaidAmnt.replace(/[^-?0-9\.]/g,''));
    formData.append('accbPymntsChngBals',accbPymntsChngBals.replace(/[^-?0-9\.]/g,''));
    formData.append('accbPymntsChqName',accbPymntsChqName);
    formData.append('accbPymntsChqNumber',accbPymntsChqNumber);
    formData.append('accbPymntsExpiryDate',accbPymntsExpiryDate);
    formData.append('accbPymntsSignCode',accbPymntsSignCode);
    formData.append('sbmtdDocumentID',sbmtdDocumentID);
    formData.append('sbmtdAccbPymntsID',sbmtdAccbPymntsID);
    formData.append('sbmtdDocumentType',sbmtdDocumentType);
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
                            dialog.modal('hide');
                            $('#myFormsModal').modal('hide');
                            if (sbmtdExtraPKeyID > 0 && sbmtdExtraPKeyType === 'Sales Invoice') {
                                printPOSRcpt(sbmtdExtraPKeyID);
                            }
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


function printPOSRcpt(pKeyID) {
    var dialog1 = bootbox.alert({
        title: 'Printing POS Receipt',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Printing POS Receipt...Please Wait...</p>',
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
                        grp: 12,
                        typ: 1,
                        pg: 1,
                        q: 'VIEW',
                        vtyp: 702,
                        pKeyID: pKeyID
                    },
                    success: function (result1) {
                        setTimeout(function () {
                            var myWindow = window.open('','Receipt','height=600,width=900');
                            myWindow.document.write(urldecode(result1));
                            myWindow.document.close(); // necessary for IE >= 10

                            myWindow.onload = function () { // necessary if the div contain images
                                myWindow.focus(); // necessary for IE >= 10
                                myWindow.print();
                                myWindow.close();
                            };
                            dialog1.modal('hide');
                        },5);
                    },
                    error: function (jqXHR1,textStatus1,errorThrown1) {
                        dialog1.find('.bootbox-body').html(errorThrown1);
                    }
                });
            });
        } else {
            setTimeout(function () {
                dialog1.find('.bootbox-body').html('No invoice Selected for Printing!');
            },50);
        }
    });
}

function printPayPOSRcpt(pKeyID) {
    var dialog1 = bootbox.alert({
        title: 'Printing POS Receipt',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Printing POS Receipt...Please Wait...</p>',
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
                        q: 'VIEW',
                        vtyp: 702,
                        pKeyID: pKeyID
                    },
                    success: function (result1) {
                        setTimeout(function () {
                            var myWindow = window.open('','Receipt','height=600,width=900');
                            myWindow.document.write(urldecode(result1));
                            myWindow.document.close(); // necessary for IE >= 10

                            myWindow.onload = function () { // necessary if the div contain images
                                myWindow.focus(); // necessary for IE >= 10
                                myWindow.print();
                                myWindow.close();
                            };
                            dialog1.modal('hide');
                        },5);
                    },
                    error: function (jqXHR1,textStatus1,errorThrown1) {
                        dialog1.find('.bootbox-body').html(errorThrown1);
                    }
                });
            });
        } else {
            setTimeout(function () {
                dialog1.find('.bootbox-body').html('No Transaction Selected for Printing!');
            },50);
        }
    });
}

function getOneJrnlBatchForm(pKeyID,vwtype,actionTxt,extraPKeyID,extraPKeyType,destElmntID) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof extraPKeyID === 'undefined' || extraPKeyID === null) {
        extraPKeyID = -1;
    }
    if (typeof extraPKeyType === 'undefined' || extraPKeyType === null) {
        extraPKeyType = '';
    }
    if (typeof destElmntID === 'undefined' || destElmntID === null) {
        destElmntID = '';
    }
    if (typeof pKeyID === 'undefined' || pKeyID === null) {
        pKeyID = -1;
    }
    if (pKeyID <= 0 && extraPKeyType !== '') {
        return false;
    }
    var accbJrnlBatchDsplySze = typeof $("#accbJrnlBatchDsplySze").val() === 'undefined' ? '50' : $("#accbJrnlBatchDsplySze").val();
    var lnkArgs = 'grp=6&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdJrnlBatchID=' + pKeyID +
        '&extraPKeyID=' + extraPKeyID + '&extraPKeyType=' + extraPKeyType + '&accbJrnlBatchDsplySze=' + accbJrnlBatchDsplySze;
    if (destElmntID.trim() === "") {
        doAjaxWthCallBck(lnkArgs,'myFormsModalLg',actionTxt,'Journal Entry Batch Details (ID:' + pKeyID + ')','myFormsModalTitleLg','myFormsModalBodyLg',function () {
            //Check and Call Insert Empty Lines
            var addNwJrnlBatchDetHtml = typeof $("#addNwJrnlBatchDetHtml").val() === 'undefined' ? '' : $("#addNwJrnlBatchDetHtml").val();
            var addNwJrnlBatchEditHtml = typeof $("#addNwJrnlBatchEditHtml").val() === 'undefined' ? '' : $("#addNwJrnlBatchEditHtml").val();
            var addNwJrnlBatchSmryHtml = typeof $("#addNwJrnlBatchSmryHtml").val() === 'undefined' ? '' : $("#addNwJrnlBatchSmryHtml").val();

            var addNwJrnlBatchDetCount = typeof $("#addNwJrnlBatchDetCount").val() === 'undefined' ? '0' : $("#addNwJrnlBatchDetCount").val();
            var addNwJrnlBatchEditCount = typeof $("#addNwJrnlBatchEditCount").val() === 'undefined' ? '0' : $("#addNwJrnlBatchEditCount").val();
            var addNwJrnlBatchSmryCount = typeof $("#addNwJrnlBatchSmryCount").val() === 'undefined' ? '0' : $("#addNwJrnlBatchSmryCount").val();
            /*if (Number(addNwJrnlBatchDetCount.replace(/[^-?0-9\.]/g, '')) <= 0) {
             insertNewJrnlBatcRows('oneJrnlBatchDetLinesTable', 0, addNwJrnlBatchDetHtml);
             }
             if (Number(addNwJrnlBatchEditCount.replace(/[^-?0-9\.]/g, '')) <= 0) {
             insertNewJrnlBatcRows('oneJrnlBatchEditLinesTable', 0, addNwJrnlBatchEditHtml);
             }
             if (Number(addNwJrnlBatchSmryCount.replace(/[^-?0-9\.]/g, '')) <= 0) {
             insertNewJrnlBatcRows('oneJrnlBatchSmryLinesTable', 0, addNwJrnlBatchSmryHtml);
             }*/
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
            $('#oneJrnlBatchEDTForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (extraPKeyID <= 0) {
                $('#myFormsModalLg').off('hidden.bs.modal');
                $('#myFormsModalLg').one('hidden.bs.modal',function (e) {
                    if (extraPKeyID <= 0) {
                        getAccbJrnlEntrs('','#allmodules','grp=6&typ=1&pg=2&vtyp=0');
                    }
                    $(e.currentTarget).unbind();
                });
            }
            if (!$.fn.DataTable.isDataTable('#oneJrnlBatchDetLinesTable')) {
                var table1 = $('#oneJrnlBatchDetLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneJrnlBatchDetLinesTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneJrnlBatchSmryLinesTable')) {
                var table1 = $('#oneJrnlBatchSmryLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneJrnlBatchSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
            }
            if (!$.fn.DataTable.isDataTable('#oneJrnlBatchEditLinesTable')) {
                var table1 = $('#oneJrnlBatchEditLinesTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#oneJrnlBatchEditLinesTable').wrap('<div class="dataTables_scroll"/>');
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
            $('[data-toggle="tabajxjrnlbatch"]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var targ = $this.attr('href');
                var dttrgt = $this.attr('data-rhodata');
                var linkArgs = 'grp=6&typ=1' + dttrgt;
                $(targ + 'tab').tab('show');
                if (targ.indexOf('jrnlBatchSmryLines') >= 0) {
                    $('#addNwJrnlBatchDetBtn').addClass('hideNotice');
                    $('#jrnlBatchDetLines').addClass('hideNotice');
                    $('#addNwJrnlBatchEditBtn').addClass('hideNotice');
                    $('#jrnlBatchEditLines').addClass('hideNotice');
                    $('#addNwJrnlBatchDetTmpltBtn').addClass('hideNotice');
                    $('#addNwJrnlBatchEditTmpltBtn').addClass('hideNotice');
                    $('#addNwJrnlBatchSmryTmpltBtn').removeClass('hideNotice');
                    $('#addNwJrnlBatchSmryBtn').removeClass('hideNotice');
                    $('#jrnlBatchSmryLines').removeClass('hideNotice');
                    $('#exprtNwJrnlBatchDetBtn').addClass('hideNotice');
                    $('#imprtNwJrnlBatchDetBtn').addClass('hideNotice');
                    $('#exprtNwJrnlBatchSmryBtn').removeClass('hideNotice');
                    $('#imprtNwJrnlBatchSmryBtn').removeClass('hideNotice');
                    $('#exprtNwJrnlBatchEditBtn').addClass('hideNotice');
                    $('#imprtNwJrnlBatchEditBtn').addClass('hideNotice');
                    /*alert($('#jrnlBatchSmryLines').html());
                     alert($('#jrnlBatchSmryLines').text().trim().length);*/
                    if ($('#jrnlBatchSmryLines').text().trim().length <= 0) {
                        return openATab(targ,linkArgs);
                    }
                } else if (targ.indexOf('jrnlBatchEditLines') >= 0) {
                    $('#addNwJrnlBatchDetBtn').addClass('hideNotice');
                    $('#jrnlBatchDetLines').addClass('hideNotice');
                    $('#addNwJrnlBatchSmryBtn').addClass('hideNotice');
                    $('#jrnlBatchSmryLines').addClass('hideNotice');
                    $('#addNwJrnlBatchDetTmpltBtn').addClass('hideNotice');
                    $('#addNwJrnlBatchEditTmpltBtn').removeClass('hideNotice');
                    $('#addNwJrnlBatchSmryTmpltBtn').addClass('hideNotice');
                    $('#addNwJrnlBatchEditBtn').removeClass('hideNotice');
                    $('#jrnlBatchEditLines').removeClass('hideNotice');
                    $('#exprtNwJrnlBatchDetBtn').addClass('hideNotice');
                    $('#imprtNwJrnlBatchDetBtn').addClass('hideNotice');
                    $('#exprtNwJrnlBatchSmryBtn').addClass('hideNotice');
                    $('#imprtNwJrnlBatchSmryBtn').addClass('hideNotice');
                    $('#exprtNwJrnlBatchEditBtn').removeClass('hideNotice');
                    $('#imprtNwJrnlBatchEditBtn').removeClass('hideNotice');
                    if ($('#jrnlBatchEditLines').text().trim().length <= 0) {
                        return openATab(targ,linkArgs);
                    }
                } else {
                    $('#addNwJrnlBatchDetBtn').removeClass('hideNotice');
                    $('#jrnlBatchDetLines').removeClass('hideNotice');
                    $('#addNwJrnlBatchSmryBtn').addClass('hideNotice');
                    $('#jrnlBatchSmryLines').addClass('hideNotice');
                    $('#addNwJrnlBatchEditBtn').addClass('hideNotice');
                    $('#jrnlBatchEditLines').addClass('hideNotice');
                    $('#addNwJrnlBatchDetTmpltBtn').removeClass('hideNotice');
                    $('#addNwJrnlBatchEditTmpltBtn').addClass('hideNotice');
                    $('#addNwJrnlBatchSmryTmpltBtn').addClass('hideNotice');
                    $('#exprtNwJrnlBatchDetBtn').removeClass('hideNotice');
                    $('#imprtNwJrnlBatchDetBtn').removeClass('hideNotice');
                    $('#exprtNwJrnlBatchSmryBtn').addClass('hideNotice');
                    $('#imprtNwJrnlBatchSmryBtn').addClass('hideNotice');
                    $('#exprtNwJrnlBatchEditBtn').addClass('hideNotice');
                    $('#imprtNwJrnlBatchEditBtn').addClass('hideNotice');
                }
            });
            $('#oneJrnlBatchDetLinesTable tr').off('click');
            $('#oneJrnlBatchDetLinesTable tr').click(function () {
                var rowIndex = $('#oneJrnlBatchDetLinesTable tr').index(this);
                $('#allOtherInputData99').val(rowIndex);
            });
            $('#oneJrnlBatchSmryLinesTable tr').off('click');
            $('#oneJrnlBatchSmryLinesTable tr').click(function () {
                var rowIndex = $('#oneJrnlBatchSmryLinesTable tr').index(this);
                $('#allOtherInputData99').val(rowIndex);
            });
            $('#oneJrnlBatchEditLinesTable tr').off('click');
            $('#oneJrnlBatchEditLinesTable tr').click(function () {
                var rowIndex = $('#oneJrnlBatchEditLinesTable tr').index(this);
                $('#allOtherInputData99').val(rowIndex);
            });
            calcAllJrnlBatchDetTtl();
        });
    } else {
        lnkArgs = 'grp=6&typ=1&pg=5&vtyp=' + vwtype + '&sbmtdJrnlBatchID=' + pKeyID + '&accbJrnlBatchDsplySze=' + accbJrnlBatchDsplySze;
        openATab(destElmntID,lnkArgs);
    }
}

function calcAllJrnlBatchDetTtl() {
    var ttlAmount = 0;
    var ttlAmount1 = 0;
    var ttlRwAmount = 0;
    var ttlRwAmount1 = 0;
    var rate1;
    $('#oneJrnlBatchDetLinesTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var prfxName = $(el).attr('id').split("_")[0];
                ttlRwAmount = ($("#" + prfxName + rndmNum + "_DebitAmnt").val() + ',').replace(/,/g,"");
                ttlAmount = ttlAmount + Number(ttlRwAmount);
                ttlRwAmount1 = ($("#" + prfxName + rndmNum + "_CreditAmnt").val() + ',').replace(/,/g,"");
                ttlAmount1 = ttlAmount1 + Number(ttlRwAmount1);
            }
        }
    });
    $('#myCptrdJbDbtsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdJbDbtsTtlVal').val(ttlAmount.toFixed(2));
    $('#myCptrdJbCrdtsTtlBtn').text(addCommas(ttlAmount1.toFixed(2)));
    $('#myCptrdJbCrdtsTtlVal').val(ttlAmount1.toFixed(2));
}

function getOneAccbPymntsHstryForm(pKeyID,vwtype,actionTxt,extraPKeyID,extraPKeyType,accbInvcVchType) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof extraPKeyID === 'undefined' || extraPKeyID === null) {
        extraPKeyID = -1;
    }
    if (typeof extraPKeyType === 'undefined' || extraPKeyType === null) {
        extraPKeyType = '';
    }
    if (typeof accbInvcVchType === 'undefined' || accbInvcVchType === null) {
        accbInvcVchType = '';
    }
    var lnkArgs = 'grp=6&typ=1&pg=12&vtyp=' + vwtype + '&sbmtdAccbInvcID=' + pKeyID +
        '&extraPKeyID=' + extraPKeyID + '&extraPKeyType=' + extraPKeyType + "&accbInvcVchType=" + accbInvcVchType;
    doAjaxWthCallBck(lnkArgs,'myFormsModalLg',actionTxt,'Payment Details (ID:' + pKeyID + ')','myFormsModalTitleLg','myFormsModalBodyLg',function () {
        $('#allOtherInputData99').val('0');
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal',function (e) {
            if (extraPKeyID <= 0) {
                getAccbPymnts('','#allmodules','grp=6&typ=1&pg=12&vtyp=0');
            }
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneAccbPymntsHstryLinesTable')) {
            var table1 = $('#oneAccbPymntsHstryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAccbPymntsHstryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
    });
}


function getOneAccbRcvblsInvcForm(pKeyID,vwtype,actionTxt,accbRcvblsInvcVchType,extraPKeyID,extraPKeyType) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof accbRcvblsInvcVchType === 'undefined' || accbRcvblsInvcVchType === null) {
        accbRcvblsInvcVchType = 'Supplier Standard Payment';
    }
    if (typeof extraPKeyID === 'undefined' || extraPKeyID === null) {
        extraPKeyID = -1;
    }
    if (typeof extraPKeyType === 'undefined' || extraPKeyType === null) {
        extraPKeyType = '';
    }
    var lnkArgs = 'grp=6&typ=1&pg=11&vtyp=' + vwtype + '&sbmtdAccbRcvblsInvcID=' + pKeyID + '&accbRcvblsInvcVchType=' + accbRcvblsInvcVchType + '&extraPKeyID=' + extraPKeyID + '&extraPKeyType=' + extraPKeyType;
    doAjaxWthCallBck(lnkArgs,'myFormsModalLg',actionTxt,'Receivable Invoice Details (ID:' + pKeyID + ')','myFormsModalTitleLg','myFormsModalBodyLg',function () {
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
        $('#oneAccbRcvblsInvcEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal',function (e) {
            if (extraPKeyID <= 0) {
                getAccbRcvblsInvc('','#allmodules','grp=6&typ=1&pg=11&vtyp=0');
            }
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneAccbRcvblsInvcSmryLinesTable')) {
            var table1 = $('#oneAccbRcvblsInvcSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAccbRcvblsInvcSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();

        $('[data-toggle="tabajxrcvblsinvc"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=6&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('rcvblsInvcExtraInfo') >= 0) {
                $('#addNwAccbRcvblsInvcSmryBtn').addClass('hideNotice');
                $('#addNwAccbRcvblsInvcTaxBtn').addClass('hideNotice');
                $('#addNwAccbRcvblsInvcDscntBtn').addClass('hideNotice');
                $('#addNwAccbRcvblsInvcChrgBtn').addClass('hideNotice');
                $('#addNwAccbRcvblsInvcPrepayBtn').addClass('hideNotice');
            } else {
                $('#addNwAccbRcvblsInvcSmryBtn').removeClass('hideNotice');
                $('#addNwAccbRcvblsInvcTaxBtn').removeClass('hideNotice');
                $('#addNwAccbRcvblsInvcDscntBtn').removeClass('hideNotice');
                $('#addNwAccbRcvblsInvcChrgBtn').removeClass('hideNotice');
                $('#addNwAccbRcvblsInvcPrepayBtn').removeClass('hideNotice');
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
        $('#oneAccbRcvblsInvcSmryLinesTable tr').off('click');
        $('#oneAccbRcvblsInvcSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneAccbRcvblsInvcSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllAccbRcvblsInvcSmryTtl();
    });
}


function calcAllAccbRcvblsInvcSmryTtl() {
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    $('#oneAccbRcvblsInvcSmryLinesTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var prfxName = $(el).attr('id').split("_")[0];
                var lineType = $("#" + prfxName + rndmNum + '_ItemType').val();
                var lnsWHTax = $("#" + prfxName + rndmNum + '_IsWHTax').val();
                ttlRwAmount = ($("#" + prfxName + rndmNum + "_EntrdAmt").val() + ',').replace(/,/g,"");
                ttlAmount = ttlAmount + Number(ttlRwAmount);
                /*if (lineType == '3Discount' || (lineType == '2Tax' && lnsWHTax == '1')) {
                 ttlAmount = ttlAmount - Number(ttlRwAmount);
                 } else {
                 }*/
            }
        }
    });
    $('#myCptrdRcvblsInvcValsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdRcvblsInvcValsTtlVal').val(ttlAmount.toFixed(2));
    $('#myCptrdRIJbSmryAmtTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdRIJbSmryAmtTtlVal').val(ttlAmount.toFixed(2));
    $('#accbRcvblsInvcTtlAmnt').val(addCommas(ttlAmount.toFixed(2)));
    var accbRcvblsInvcPaidAmnt = $('#accbRcvblsInvcPaidAmnt').val().replace(/,/g,"");
    $('#accbRcvblsInvcOustndngAmnt').val(addCommas((ttlAmount - Number(accbRcvblsInvcPaidAmnt)).toFixed(2)));
}


function getOneAccbPyblsInvcForm(pKeyID,vwtype,actionTxt,accbPyblsInvcVchType,extraPKeyID,extraPKeyType) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    if (typeof accbPyblsInvcVchType === 'undefined' || accbPyblsInvcVchType === null) {
        accbPyblsInvcVchType = 'Supplier Standard Payment';
    }
    if (typeof extraPKeyID === 'undefined' || extraPKeyID === null) {
        extraPKeyID = -1;
    }
    if (typeof extraPKeyType === 'undefined' || extraPKeyType === null) {
        extraPKeyType = '';
    }
    var lnkArgs = 'grp=6&typ=1&pg=10&vtyp=' + vwtype + '&sbmtdAccbPyblsInvcID=' + pKeyID + '&accbPyblsInvcVchType=' + accbPyblsInvcVchType + '&extraPKeyID=' + extraPKeyID + '&extraPKeyType=' + extraPKeyType;
    doAjaxWthCallBck(lnkArgs,'myFormsModalLg',actionTxt,'Payables Invoice Details (ID:' + pKeyID + ')','myFormsModalTitleLg','myFormsModalBodyLg',function () {
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
        $('#oneAccbPyblsInvcEDTForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal',function (e) {
            if (extraPKeyID <= 0) {
                getAccbPyblsInvc('','#allmodules','grp=6&typ=1&pg=10&vtyp=0');
            }
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneAccbPyblsInvcSmryLinesTable')) {
            var table1 = $('#oneAccbPyblsInvcSmryLinesTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneAccbPyblsInvcSmryLinesTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();

        $('[data-toggle="tabajxpyblsinvc"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=6&typ=1' + dttrgt;
            $(targ + 'tab').tab('show');
            if (targ.indexOf('pyblsInvcExtraInfo') >= 0) {
                $('#addNwAccbPyblsInvcSmryBtn').addClass('hideNotice');
                $('#addNwAccbPyblsInvcTaxBtn').addClass('hideNotice');
                $('#addNwAccbPyblsInvcDscntBtn').addClass('hideNotice');
                $('#addNwAccbPyblsInvcChrgBtn').addClass('hideNotice');
                $('#addNwAccbPyblsInvcPrepayBtn').addClass('hideNotice');
            } else {
                $('#addNwAccbPyblsInvcSmryBtn').removeClass('hideNotice');
                $('#addNwAccbPyblsInvcTaxBtn').removeClass('hideNotice');
                $('#addNwAccbPyblsInvcDscntBtn').removeClass('hideNotice');
                $('#addNwAccbPyblsInvcChrgBtn').removeClass('hideNotice');
                $('#addNwAccbPyblsInvcPrepayBtn').removeClass('hideNotice');
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
        $('#oneAccbPyblsInvcSmryLinesTable tr').off('click');
        $('#oneAccbPyblsInvcSmryLinesTable tr').click(function () {
            var rowIndex = $('#oneAccbPyblsInvcSmryLinesTable tr').index(this);
            $('#allOtherInputData99').val(rowIndex);
        });
        calcAllAccbPyblsInvcSmryTtl();
    });
}


function calcAllAccbPyblsInvcSmryTtl() {
    var ttlAmount = 0;
    var ttlRwAmount = 0;
    $('#oneAccbPyblsInvcSmryLinesTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var prfxName = $(el).attr('id').split("_")[0];
                var lineType = $("#" + prfxName + rndmNum + '_ItemType').val();
                var lnsWHTax = $("#" + prfxName + rndmNum + '_IsWHTax').val();
                ttlRwAmount = ($("#" + prfxName + rndmNum + "_EntrdAmt").val() + ',').replace(/,/g,"");
                ttlAmount = ttlAmount + Number(ttlRwAmount);
                /*if (lineType == '3Discount' || (lineType == '2Tax' && lnsWHTax == '1')) {
                 ttlAmount = ttlAmount - Number(ttlRwAmount);
                 } else {
                 }*/
            }
        }
    });
    $('#myCptrdPyblsInvcValsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdPyblsInvcValsTtlVal').val(ttlAmount.toFixed(2));
    $('#myCptrdPIJbSmryAmtTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdPIJbSmryAmtTtlVal').val(ttlAmount.toFixed(2));
    $('#accbPyblsInvcTtlAmnt').val(addCommas(ttlAmount.toFixed(2)));
    var accbPyblsInvcPaidAmnt = $('#accbPyblsInvcPaidAmnt').val().replace(/,/g,"");
    $('#accbPyblsInvcOustndngAmnt').val(addCommas((ttlAmount - Number(accbPyblsInvcPaidAmnt)).toFixed(2)));
}


function resetAccbFSRptRpts(slctr,linkArgs) {
    shdHideFSRpt = 0;
    openATab(slctr,linkArgs);
}

function getAccbFSRptRpts(startRunng,slctr,linkArgs,startAcntID) {
    var accbFSRptMaxAcntLvl = typeof $("#accbFSRptMaxAcntLvl").val() === 'undefined' ? 1 : $("#accbFSRptMaxAcntLvl").val();
    var accbFSRptSbmtdAccountID = typeof $("#accbFSRptSbmtdAccountID").val() === 'undefined' ? -1 : $("#accbFSRptSbmtdAccountID").val();
    var accbFSRptAcntNum = typeof $("#accbFSRptAcntNum").val() === 'undefined' ? '' : $("#accbFSRptAcntNum").val();
    var accbStrtFSRptDte = typeof $("#accbStrtFSRptDte").val() === 'undefined' ? '' : $("#accbStrtFSRptDte").val();
    var accbFSRptDte = typeof $("#accbFSRptDte").val() === 'undefined' ? '' : $("#accbFSRptDte").val();
    var accbFSRptAcntTypes = typeof $("#accbFSRptAcntTypes").val() === 'undefined' ? '' : $("#accbFSRptAcntTypes").val();
    var accbFSRptPrdType = typeof $("#accbFSRptPrdType").val() === 'undefined' ? '' : $("#accbFSRptPrdType").val();
    var accbFSRptShwVariance = $('#accbFSRptShwVariance:checked').length > 0 ? "YES" : "NO";
    var accbFSRptShwHideZero = $('#accbFSRptShwHideZero:checked').length > 0 ? "YES" : "NO";
    var accbFSRptShwSmmry = $('#accbFSRptShwSmmry:checked').length > 0 ? "YES" : "NO";
    var accbFSRptShwNetPos = $('#accbFSRptShwNetPos:checked').length > 0 ? "YES" : "NO";
    var accbFSRptUseCreationDte = $('#accbFSRptUseCreationDte:checked').length > 0 ? "YES" : "NO";
    var accbFSRptSgmnt1ValID = typeof $("#accbFSRptSgmnt1ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt1ValID").val();
    var accbFSRptSgmnt2ValID = typeof $("#accbFSRptSgmnt2ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt2ValID").val();
    var accbFSRptSgmnt3ValID = typeof $("#accbFSRptSgmnt3ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt3ValID").val();
    var accbFSRptSgmnt4ValID = typeof $("#accbFSRptSgmnt4ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt4ValID").val();
    var accbFSRptSgmnt5ValID = typeof $("#accbFSRptSgmnt5ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt5ValID").val();
    var accbFSRptSgmnt6ValID = typeof $("#accbFSRptSgmnt6ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt6ValID").val();
    var accbFSRptSgmnt7ValID = typeof $("#accbFSRptSgmnt7ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt7ValID").val();
    var accbFSRptSgmnt8ValID = typeof $("#accbFSRptSgmnt8ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt8ValID").val();
    var accbFSRptSgmnt9ValID = typeof $("#accbFSRptSgmnt9ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt9ValID").val();
    var accbFSRptSgmnt10ValID = typeof $("#accbFSRptSgmnt10ValID").val() === 'undefined' ? -1 : $("#accbFSRptSgmnt10ValID").val();

    var accbFSRptCreatedByID = typeof $("#accbFSRptCreatedByID").val() === 'undefined' ? -1 : $("#accbFSRptCreatedByID").val();
    var accbFSRptCreatedBy = typeof $("#accbFSRptCreatedBy").val() === 'undefined' ? '' : $("#accbFSRptCreatedBy").val();
    var accbFSRptDocType = typeof $("#accbFSRptDocType").val() === 'undefined' ? '' : $("#accbFSRptDocType").val();
    var accbFSRptSortBy = typeof $("#accbFSRptSortBy").val() === 'undefined' ? '' : $("#accbFSRptSortBy").val();


    var accbFSRptItemCodeID = typeof $("#accbFSRptItemCodeID").val() === 'undefined' ? -1 : $("#accbFSRptItemCodeID").val();
    var accbFSRptItemCode = typeof $("#accbFSRptItemCode").val() === 'undefined' ? '' : $("#accbFSRptItemCode").val();
    var accbFSRptStoreID = typeof $("#accbFSRptStoreID").val() === 'undefined' ? -1 : $("#accbFSRptStoreID").val();
    var accbFSRptStore = typeof $("#accbFSRptStore").val() === 'undefined' ? '' : $("#accbFSRptStore").val();
    var accbFSRptCtgryID = typeof $("#accbFSRptCtgryID").val() === 'undefined' ? -1 : $("#accbFSRptCtgryID").val();
    var accbFSRptCtgry = typeof $("#accbFSRptCtgry").val() === 'undefined' ? '' : $("#accbFSRptCtgry").val();
    var accbFSRptItemType = typeof $("#accbFSRptItemType").val() === 'undefined' ? '' : $("#accbFSRptItemType").val();
    var accbFSRptQTYType = typeof $("#accbFSRptQTYType").val() === 'undefined' ? '' : $("#accbFSRptQTYType").val();
    var accbFSRptMinQTY = typeof $("#accbFSRptMinQTY").val() === 'undefined' ? 0 : $("#accbFSRptMinQTY").val();
    var accbFSRptMaxQTY = typeof $("#accbFSRptMaxQTY").val() === 'undefined' ? 9999999999 : $("#accbFSRptMaxQTY").val();
    if (startAcntID >= 1) {
        accbFSRptSbmtdAccountID = startAcntID;
        accbFSRptMaxAcntLvl = 100;
        accbFSRptShwSmmry = "NO";
    }
    if (startAcntID >= 1) {
        shdHideFSRpt = 1;
    } else {
        shdHideFSRpt = 0;
    }
    linkArgs = linkArgs + "&accbFSRptMaxAcntLvl=" + accbFSRptMaxAcntLvl +
        "&accbFSRptSbmtdAccountID=" + accbFSRptSbmtdAccountID +
        "&accbFSRptAcntNum=" + accbFSRptAcntNum +
        "&accbFSRptDte=" + accbFSRptDte +
        "&accbStrtFSRptDte=" + accbStrtFSRptDte +
        "&accbFSRptAcntTypes=" + accbFSRptAcntTypes +
        "&accbFSRptPrdType=" + accbFSRptPrdType +
        "&startRunng=" + startRunng +
        "&accbFSRptShwVariance=" + accbFSRptShwVariance +
        "&accbFSRptShwHideZero=" + accbFSRptShwHideZero +
        "&accbFSRptShwSmmry=" + accbFSRptShwSmmry +
        "&accbFSRptShwNetPos=" + accbFSRptShwNetPos +
        "&accbFSRptSgmnt1ValID=" + accbFSRptSgmnt1ValID +
        "&accbFSRptSgmnt2ValID=" + accbFSRptSgmnt2ValID +
        "&accbFSRptSgmnt3ValID=" + accbFSRptSgmnt3ValID +
        "&accbFSRptSgmnt4ValID=" + accbFSRptSgmnt4ValID +
        "&accbFSRptSgmnt5ValID=" + accbFSRptSgmnt5ValID +
        "&accbFSRptSgmnt6ValID=" + accbFSRptSgmnt6ValID +
        "&accbFSRptSgmnt7ValID=" + accbFSRptSgmnt7ValID +
        "&accbFSRptSgmnt8ValID=" + accbFSRptSgmnt8ValID +
        "&accbFSRptSgmnt9ValID=" + accbFSRptSgmnt9ValID +
        "&accbFSRptSgmnt10ValID=" + accbFSRptSgmnt10ValID +
        "&accbFSRptItemCodeID=" + accbFSRptItemCodeID +
        "&accbFSRptStoreID=" + accbFSRptStoreID +
        "&accbFSRptCtgryID=" + accbFSRptCtgryID +
        "&accbFSRptItemType=" + accbFSRptItemType +
        "&accbFSRptQTYType=" + accbFSRptQTYType +
        "&accbFSRptMinQTY=" + accbFSRptMinQTY +
        "&accbFSRptMaxQTY=" + accbFSRptMaxQTY +
        "&accbFSRptItemCode=" + accbFSRptItemCode +
        "&accbFSRptStore=" + accbFSRptStore +
        "&accbFSRptCtgry=" + accbFSRptCtgry +
        "&accbFSRptDocType=" + accbFSRptDocType +
        "&accbFSRptSortBy=" + accbFSRptSortBy +
        "&accbFSRptCreatedByID=" + accbFSRptCreatedByID +
        "&accbFSRptCreatedBy=" + accbFSRptCreatedBy +
        "&accbFSRptUseCreationDte=" + accbFSRptUseCreationDte;
    openATab(slctr,linkArgs);
    if (startAcntID >= 1) {
        shdHideFSRpt = 1;
    } else {
        shdHideFSRpt = 0;
    }
}

function shwHideFSRptDivs(whtToDo) {
    if (whtToDo === 'hide') {
        $('#leftDivFSRpt').addClass('hideNotice');
        $('#rightDivFSRptBtn').removeClass('hideNotice');
        $('#rightDivFSRpt').removeClass('col-md-9');
        $('#rightDivFSRpt').addClass('col-md-12');
    } else {
        $('#leftDivFSRpt').removeClass('hideNotice');
        $('#rightDivFSRpt').removeClass('col-md-12');
        $('#rightDivFSRpt').addClass('col-md-9');
        $('#rightDivFSRptBtn').addClass('hideNotice');
    }
}


var prgstimerid;
var prgstimerid1;

function autoLoadAddresses() {
    var msgType = typeof $("#msgType").val() === 'undefined' ? '' : $("#msgType").val();
    var sndMsgOneByOne = typeof $("#sndMsgOneByOne").val() === 'undefined' ? '' : $("#sndMsgOneByOne").val();
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var groupID = typeof $("#groupID").val() === 'undefined' ? '' : $("#groupID").val();
    var grpName = typeof $("#groupName").val() === 'undefined' ? '' : $("#groupName").val();
    var workPlaceID = typeof $("#workPlaceID").val() === 'undefined' ? -1 : $("#workPlaceID").val();
    var workPlaceSiteID = typeof $("#workPlaceSiteID").val() === 'undefined' ? -1 : $("#workPlaceSiteID").val();

    var mailTo = typeof $("#mailTo").val() === 'undefined' ? '' : $("#mailTo").val();
    var mailCc = typeof $("#mailCc").val() === 'undefined' ? '' : $("#mailCc").val();
    var mailBcc = typeof $("#mailBcc").val() === 'undefined' ? '' : $("#mailBcc").val();
    var mailAttchmnts = typeof $("#mailAttchmnts").val() === 'undefined' ? '' : $("#mailAttchmnts").val();
    var mailSubject = typeof $("#mailSubject").val() === 'undefined' ? '' : $("#mailSubject").val();

    var bulkMessageBody = typeof $("#bulkMessageBody").val() === 'undefined' ? '' : ($('#bulkMessageBody').summernote('code'));

    var dialog = bootbox.alert({
        title: 'Get Addresses',
        size: 'small',
        message: '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Getting Addresses...Please Wait...</div>',
        callback: function () {
            clearInterval(prgstimerid);
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
                    grp: 8,
                    typ: 1,
                    pg: 7,
                    q: 'UPDATE',
                    actyp: 1,
                    msgType: msgType,
                    sndMsgOneByOne: sndMsgOneByOne,
                    grpType: grpType,
                    grpName: grpName,
                    groupID: groupID,
                    workPlaceID: workPlaceID,
                    workPlaceSiteID: workPlaceSiteID,
                    mailTo: mailTo,
                    mailCc: mailCc,
                    mailBcc: mailBcc,
                    mailAttchmnts: mailAttchmnts,
                    mailSubject: mailSubject,
                    bulkMessageBody: bulkMessageBody
                }
            });
            prgstimerid = window.setInterval(rfrshGetAdrsPrgrs,1000);
        });
    });
}

function rfrshGetAdrsPrgrs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 8,
            typ: 1,
            pg: 7,
            q: 'UPDATE',
            actyp: 2
        },
        success: function (data) {
            var elem = document.getElementById('myBar');
            elem.style.width = data.percent + '%';
            $("#myInformation").html(data.message);
            if (data.percent == 100) {
                window.clearInterval(prgstimerid);
                $('#mailTo').val(data.addresses);
            }
        }
    });
}

function autoQueueMsgs() {
    var rho_msg_source = typeof $("#rho_msg_source").val() === 'undefined' ? '' : $("#rho_msg_source").val();
    var msgType = typeof $("#msgType").val() === 'undefined' ? '' : $("#msgType").val();
    var sndMsgOneByOne = typeof $("input[name='sndMsgOneByOne']:checked").val() === 'undefined' ? 'NO' : 'YES';
    var grpType = typeof $("#grpType").val() === 'undefined' ? '' : $("#grpType").val();
    var groupID = typeof $("#groupID").val() === 'undefined' ? '-1' : $("#groupID").val();
    var grpName = typeof $("#groupName").val() === 'undefined' ? '' : $("#groupName").val();
    var workPlaceID = typeof $("#workPlaceID").val() === 'undefined' ? -1 : $("#workPlaceID").val();
    var workPlaceSiteID = typeof $("#workPlaceSiteID").val() === 'undefined' ? -1 : $("#workPlaceSiteID").val();
    if (rho_msg_source == "SELF-SERVICE-MSG" && Number(groupID.replace(/[^-?0-9\.]/g,"")) > 0) {
        $("#mailTo").val(groupID + ";");
    }

    var mailTo = typeof $("#mailTo").val() === 'undefined' ? '' : $("#mailTo").val();
    var mailCc = typeof $("#mailCc").val() === 'undefined' ? '' : $("#mailCc").val();
    var mailBcc = typeof $("#mailBcc").val() === 'undefined' ? '' : $("#mailBcc").val();
    var mailAttchmnts = typeof $("#mailAttchmnts").val() === 'undefined' ? '' : $("#mailAttchmnts").val();
    var mailSubject = typeof $("#mailSubject").val() === 'undefined' ? '' : $("#mailSubject").val();
    var bulkMessageBody = typeof $("#bulkMessageBody").val() === 'undefined' ? '' : ($('#bulkMessageBody').summernote('code'));
    if (msgType === "SMS") {
        bulkMessageBody = bulkMessageBody.replace(/<\/p>/gi," ")
            .replace(/<br\/?>/gi," ")
            .replace(/<\/?[^>]+(>|$)/g,"");
        /*
          bulkMessageBody = bulkMessageBody.replace(/<\/p>/gi, "\n")
         .replace(/<br\/?>/gi, "\n")
         .replace(/<\/?[^>]+(>|$)/g, "");
         */
    }
    if (mailSubject.trim() === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Subject cannot be empty!</span></p>'
        });
        return false;
    }
    if (bulkMessageBody.trim() === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Message Body cannot be empty!</span></p>'
        });
        return false;
    }
    if (rhotrim(mailTo,'; ') === '') {
        bootbox.alert({
            title: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                'font-weight:bold;">Mail To cannot be empty!</span></p>'
        });
        return false;
    }
    var dialog1 = bootbox.confirm({
        title: 'Queue Messages?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">SEND THIS MESSAGE</span> to the Indicated Receipients?<br/>Action cannot be Undone!</p>',
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
                var dialog = bootbox.alert({
                    title: 'Queue Messages',
                    size: 'small',
                    message: '<div id="myProgress"><div id="myBar"></div></div><div id="myInformation"><i class="fa fa-spin fa-spinner"></i> Queuing Messages...Please Wait...</div>',
                    callback: function () {
                        clearInterval(prgstimerid1);
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
                                grp: 8,
                                typ: 1,
                                pg: 7,
                                q: 'UPDATE',
                                actyp: 3,
                                msgType: msgType,
                                sndMsgOneByOne: sndMsgOneByOne,
                                grpType: grpType,
                                grpName: grpName,
                                groupID: groupID,
                                workPlaceID: workPlaceID,
                                workPlaceSiteID: workPlaceSiteID,
                                mailTo: mailTo,
                                mailCc: mailCc,
                                mailBcc: mailBcc,
                                mailAttchmnts: mailAttchmnts,
                                mailSubject: mailSubject,
                                bulkMessageBody: bulkMessageBody
                            }
                        });
                        prgstimerid1 = window.setInterval(rfrshQueueMsgsPrgrs,1000);
                    });
                });
            }
        }
    });
}

function rfrshQueueMsgsPrgrs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 8,
            typ: 1,
            pg: 7,
            q: 'UPDATE',
            actyp: 4
        },
        success: function (data) {
            var elem = document.getElementById('myBar');
            elem.style.width = data.percent + '%';
            $("#myInformation").html(data.message);
            if (data.percent == 100) {
                window.clearInterval(prgstimerid1);
            }
        }
    });
}

function clearMsgForm() {
    var dialog = bootbox.confirm({
        title: 'Clear Form?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">CLEAR</span> this Form?<br/>Action cannot be Undone!</p>',
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
                $("#mailTo").val('');
                $("#mailCc").val('');
                $("#mailBcc").val('');
                $("#mailAttchmnts").val('');
                $("#mailSubject").val('');
                $('#bulkMessageBody').summernote('code','<p></p>');
            }
        }
    });
}

function attchFileToMsg() {
    var crntAttchMnts = $("#mailAttchmnts").val();
    $("#allOtherFileInput3").change(function () {
        var fileName = $(this).val();
        var input = document.getElementById('allOtherFileInput3');
        sendMsgsFile(input.files[0],function () {
            var inptUrl = $("#allOtherInputData3").val();
            crntAttchMnts = crntAttchMnts + ";" + inptUrl;
            $("#mailAttchmnts").val(crntAttchMnts);
        });
    });
    performFileClick('allOtherFileInput3');
}

function sendMsgsFile(file,callBackFunc) {
    var data1 = new FormData();
    data1.append("file",file);
    $.ajax({
        url: "dwnlds/uploader1.php",
        data: data1,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            $("#allOtherInputData3").val(data);
            callBackFunc();
        },
        error: function (jqXHR,textStatus,errorThrown) {
            console.log(textStatus + " " + errorThrown);
        }
    });
}

function sendGeneralMessage(msgType,mailToElmtID,mailCcElmtID,mailSubjectElmtID,bulkMessageBodyElmtID,mailAttchmntsElmtID,elementID,actionAfter,titleMsg,titleElementID,modalBodyID) {
    if (typeof mailAttchmntsElmtID === 'undefined' || mailAttchmntsElmtID === null) {
        mailAttchmntsElmtID = 'XXX_RHO_UNDEFINED';
    }
    var mailTo = typeof $("#" + mailToElmtID).val() === 'undefined' ? '' : $("#" + mailToElmtID).val();
    var mailCc = typeof $("#" + mailCcElmtID).val() === 'undefined' ? '' : $("#" + mailCcElmtID).val();
    var mailSubject = typeof $("#" + mailSubjectElmtID).val() === 'undefined' ? '' : $("#" + mailSubjectElmtID).val();
    var bulkMessageBody = typeof $("#" + bulkMessageBodyElmtID).val() === 'undefined' ? '' : $("#" + bulkMessageBodyElmtID).val();
    var mailAttchmnts = typeof $("#" + mailAttchmntsElmtID).val() === 'undefined' ? '' : $("#" + mailAttchmntsElmtID).val();
    var linkArgs = 'grp=8&typ=1&pg=7&vtyp=0&msgType=' + msgType + '&mailTo=' + mailTo + '&mailCc=' + mailCc + '&mailSubject=' + mailSubject + '&bulkMessageBody=' + bulkMessageBody + "&mailAttchmnts=" + mailAttchmnts;
    if (typeof msgType === 'undefined' || msgType === null) {
        msgType = 'Email';
    }
    if (typeof elementID === 'undefined' || elementID === null) {
        elementID = 'sndBlkMsgForm';
    }
    if (typeof actionAfter === 'undefined' || actionAfter === null) {
        actionAfter = 'ShowDialog';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = 'Send Bulk Email/SMS';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = 'sndBlkMsgFormTitle';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = 'sndBlkMsgFormBody';
    }
    doAjaxWthCallBck(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID,function () {
        var fileLink = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-file"/> Upload',
                tooltip: 'Upload Inline Within File',
                click: function () {
                    $(function () {
                        $("#allOtherFileInput1").change(function () {
                            var fileName = $(this).val();
                            var input = document.getElementById('allOtherFileInput1');
                            sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                var inptText = $("#allOtherInputData2").val();
                                var inptNwWndw = $("#allOtherInputData2").val();
                                if (inptText === "") {
                                    inptText = "Read More...";
                                }
                                if (inptNwWndw === "") {
                                    inptNwWndw = true;
                                }
                                $('#bulkMessageBody').summernote('createLink',{
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
        $('#bulkMessageBody').summernote({
            minHeight: 375,
            focus: true,
            disableDragAndDrop: false,
            dialogsInBody: true,
            toolbar: [
                ['style',['style']],
                ['style',['bold','italic','underline','clear']],
                ['font',['strikethrough','superscript','subscript']],
                ['fontsize',['fontsize']],
                ['fontname',['fontname']],
                ['color',['color']],
                ['para',['ul','ol','paragraph','height']],
                ['height',['height']],
                ['table',['table']],
                ['insert',['link','picture','video','hr']],
                ['view',['fullscreen','codeview']],
                ['help',['help']],
                ['misc',['print']],
                ['mybutton',['upload']]
            ],
            buttons: {
                upload: fileLink
            },
            callbacks: {
                onImageUpload: function (file,editor,welEditable) {
                    sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                        var inptUrl = $("#allOtherInputData1").val();
                        $('#bulkMessageBody').summernote("insertImage",inptUrl,'filename');
                    });
                }
            }
        });
        $('.note-editable').trigger('focus');
        $('#bulkMessageBody').summernote('code',urldecode(bulkMessageBody));
        $('#mailTo').val(urldecode(mailTo));
        $('#mailCc').val(urldecode(mailCc));
        $('#mailSubject').val(urldecode(mailSubject));
        $('#mailAttchmnts').val(mailAttchmnts);
        $('button.note-btn').mouseup(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });
        $('button.note-btn').hover(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        },function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });

        $('#' + elementID).off('hidden.bs.modal');
        $('#' + elementID).one("hidden.bs.modal",function (e) {
            $(e.currentTarget).unbind();
        });
    });
}

function sendGeneralMessage1(msgType,mailTo,mailCc,mailSubject,bulkMessageBody,mailAttchmnts,elementID,actionAfter,titleMsg,titleElementID,modalBodyID) {
    var linkArgs = 'grp=8&typ=1&pg=7&vtyp=0&msgType=' + msgType + '&mailTo=' + mailTo + '&mailCc=' + mailCc + '&mailSubject=' + mailSubject + '&bulkMessageBody=' + bulkMessageBody + "&mailAttchmnts=" + mailAttchmnts;
    if (typeof msgType === 'undefined' || msgType === null) {
        msgType = 'Email';
    }
    if (typeof elementID === 'undefined' || elementID === null) {
        elementID = 'sndBlkMsgForm';
    }
    if (typeof actionAfter === 'undefined' || actionAfter === null) {
        actionAfter = 'ShowDialog';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = 'Send Bulk Email/SMS';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = 'sndBlkMsgFormTitle';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = 'sndBlkMsgFormBody';
    }
    doAjaxWthCallBck(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID,function () {
        var fileLink = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-file"/> Upload',
                tooltip: 'Upload File Inline Within Message Body',
                click: function () {
                    $(function () {
                        $("#allOtherFileInput1").change(function () {
                            var fileName = $(this).val();
                            var input = document.getElementById('allOtherFileInput1');
                            sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                var inptText = $("#allOtherInputData2").val();
                                var inptNwWndw = $("#allOtherInputData2").val();
                                if (inptText === "") {
                                    inptText = "Read More...";
                                }
                                if (inptNwWndw === "") {
                                    inptNwWndw = true;
                                }
                                $('#bulkMessageBody').summernote('createLink',{
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
        $('#bulkMessageBody').summernote({
            minHeight: 375,
            focus: true,
            disableDragAndDrop: false,
            dialogsInBody: true,
            toolbar: [
                ['style',['style']],
                ['style',['bold','italic','underline','clear']],
                ['font',['strikethrough','superscript','subscript']],
                ['fontsize',['fontsize']],
                ['fontname',['fontname']],
                ['color',['color']],
                ['para',['ul','ol','paragraph','height']],
                ['height',['height']],
                ['table',['table']],
                ['insert',['link','picture','video','hr']],
                ['view',['fullscreen','codeview']],
                ['help',['help']],
                ['misc',['print']],
                ['mybutton',['upload']]
            ],
            buttons: {
                upload: fileLink
            },
            callbacks: {
                onImageUpload: function (file,editor,welEditable) {
                    sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                        var inptUrl = $("#allOtherInputData1").val();
                        $('#bulkMessageBody').summernote("insertImage",inptUrl,'filename');
                    });
                }
            }
        });
        $('.note-editable').trigger('focus');
        $('#bulkMessageBody').summernote('code',urldecode(bulkMessageBody));
        $('#mailTo').val(urldecode(mailTo));
        $('#mailCc').val(urldecode(mailCc));
        $('#mailSubject').val(urldecode(mailSubject));
        $('#mailAttchmnts').val(mailAttchmnts);
        $('button.note-btn').mouseup(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });
        $('button.note-btn').hover(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        },function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });

        $('#' + elementID).off('hidden.bs.modal');
        $('#' + elementID).one("hidden.bs.modal",function (e) {
            $(e.currentTarget).unbind();
        });
    });
}
/**Self-Service Msg */
function sendGeneralMessage2(msgType,mailToElmtID,mailCcElmtID,mailSubjectElmtID,bulkMessageBodyElmtID,mailAttchmntsElmtID,elementID,actionAfter,titleMsg,titleElementID,modalBodyID) {
    if (typeof mailAttchmntsElmtID === 'undefined' || mailAttchmntsElmtID === null) {
        mailAttchmntsElmtID = 'XXX_RHO_UNDEFINED';
    }
    var mailTo = typeof $("#" + mailToElmtID).val() === 'undefined' ? '' : $("#" + mailToElmtID).val();
    var mailCc = typeof $("#" + mailCcElmtID).val() === 'undefined' ? '' : $("#" + mailCcElmtID).val();
    var mailSubject = typeof $("#" + mailSubjectElmtID).val() === 'undefined' ? '' : $("#" + mailSubjectElmtID).val();
    var bulkMessageBody = typeof $("#" + bulkMessageBodyElmtID).val() === 'undefined' ? '' : $("#" + bulkMessageBodyElmtID).val();
    var mailAttchmnts = typeof $("#" + mailAttchmntsElmtID).val() === 'undefined' ? '' : $("#" + mailAttchmntsElmtID).val();
    var linkArgs = 'grp=8&typ=1&pg=7&vtyp=0&q=SELF-SERVICE-MSG&msgType=' + msgType + '&mailTo=' + mailTo + '&mailCc=' + mailCc + '&mailSubject=' + mailSubject + '&bulkMessageBody=' + bulkMessageBody + "&mailAttchmnts=" + mailAttchmnts;
    if (typeof msgType === 'undefined' || msgType === null) {
        msgType = 'Email';
    }
    if (typeof elementID === 'undefined' || elementID === null) {
        elementID = 'sndBlkMsgForm';
    }
    if (typeof actionAfter === 'undefined' || actionAfter === null) {
        actionAfter = 'ShowDialog';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = 'Send Bulk Email/SMS';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = 'sndBlkMsgFormTitle';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = 'sndBlkMsgFormBody';
    }
    doAjaxWthCallBck(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID,function () {
        var fileLink = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-file"/> Upload',
                tooltip: 'Upload File Inline Within Message Body',
                click: function () {
                    $(function () {
                        $("#allOtherFileInput1").change(function () {
                            var fileName = $(this).val();
                            var input = document.getElementById('allOtherFileInput1');
                            sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                var inptText = $("#allOtherInputData2").val();
                                var inptNwWndw = $("#allOtherInputData2").val();
                                if (inptText === "") {
                                    inptText = "Read More...";
                                }
                                if (inptNwWndw === "") {
                                    inptNwWndw = true;
                                }
                                $('#bulkMessageBody').summernote('createLink',{
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
        $('#bulkMessageBody').summernote({
            minHeight: 375,
            focus: true,
            disableDragAndDrop: false,
            dialogsInBody: true,
            toolbar: [
                ['style',['style']],
                ['style',['bold','italic','underline','clear']],
                ['font',['strikethrough','superscript','subscript']],
                ['fontsize',['fontsize']],
                ['fontname',['fontname']],
                ['color',['color']],
                ['para',['ul','ol','paragraph','height']],
                ['height',['height']],
                ['table',['table']],
                ['insert',['link','picture','video','hr']],
                ['view',['fullscreen','codeview']],
                ['help',['help']],
                ['misc',['print']],
                ['mybutton',['upload']]
            ],
            buttons: {
                upload: fileLink
            },
            callbacks: {
                onImageUpload: function (file,editor,welEditable) {
                    sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                        var inptUrl = $("#allOtherInputData1").val();
                        $('#bulkMessageBody').summernote("insertImage",inptUrl,'filename');
                    });
                }
            }
        });
        $('.note-editable').trigger('focus');
        $('#bulkMessageBody').summernote('code',urldecode(bulkMessageBody));
        $('#mailTo').val(urldecode(mailTo));
        $('#mailCc').val(urldecode(mailCc));
        $('#mailSubject').val(urldecode(mailSubject));
        $('#mailAttchmnts').val(mailAttchmnts);
        $('button.note-btn').mouseup(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });
        $('button.note-btn').hover(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        },function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });

        $('#' + elementID).off('hidden.bs.modal');
        $('#' + elementID).one("hidden.bs.modal",function (e) {
            $(e.currentTarget).unbind();
        });
    });
}

function sendGeneralMessage3(rowIDAttrb,msgType,mailToElmtID,mailCcElmtID,mailSubjectElmtID,bulkMessageBodyElmtID,mailAttchmntsElmtID,elementID,actionAfter,titleMsg,titleElementID,modalBodyID) {
    if (typeof mailAttchmntsElmtID === 'undefined' || mailAttchmntsElmtID === null) {
        mailAttchmntsElmtID = 'XXX_RHO_UNDEFINED';
    }
    if (typeof mailSubjectElmtID === 'undefined' || mailSubjectElmtID === null) {
        mailSubjectElmtID = 'prevMailSubject';
    }
    if (typeof bulkMessageBodyElmtID === 'undefined' || bulkMessageBodyElmtID === null) {
        bulkMessageBodyElmtID = 'prevBulkMessageBody';
    }
    var prfxNm = rowIDAttrb.split("_")[0];
    var rndmNum = rowIDAttrb.split("_")[1];
    var RoutingID = $('#' + prfxNm + '' + rndmNum + '_RoutingID').val();

    var rowCnt = 0;
    var mailTo = typeof $("#" + mailToElmtID).val() === 'undefined' ? '' : $("#" + mailToElmtID).val();
    var mailCc = typeof $("#" + mailCcElmtID).val() === 'undefined' ? '' : $("#" + mailCcElmtID).val();
    var mailSubject = typeof $("#" + mailSubjectElmtID).val() === 'undefined' ? '' : $("#" + mailSubjectElmtID).val();
    var bulkMessageBody = typeof $("#" + bulkMessageBodyElmtID).val() === 'undefined' ? '' : $("#" + bulkMessageBodyElmtID).val();
    var mailAttchmnts = typeof $("#" + mailAttchmntsElmtID).val() === 'undefined' ? '' : $("#" + mailAttchmntsElmtID).val();
    var linkArgs = 'grp=8&typ=1&pg=7&vtyp=0&q=SELF-SERVICE-MSG&msgType=' + msgType + '&mailTo=' + mailTo + '&mailCc=' + mailCc + '&mailSubject=' + mailSubject + '&bulkMessageBody=' + bulkMessageBody + "&mailAttchmnts=" + mailAttchmnts + "&RoutingID=" + RoutingID;
    if (typeof msgType === 'undefined' || msgType === null) {
        msgType = 'Email';
    }
    if (typeof elementID === 'undefined' || elementID === null) {
        elementID = 'sndBlkMsgForm';
    }
    if (typeof actionAfter === 'undefined' || actionAfter === null) {
        actionAfter = 'ShowDialog';
    }
    if (typeof titleMsg === 'undefined' || titleMsg === null) {
        titleMsg = 'Send Bulk Email/SMS';
    }
    if (typeof titleElementID === 'undefined' || titleElementID === null) {
        titleElementID = 'sndBlkMsgFormTitle';
    }
    if (typeof modalBodyID === 'undefined' || modalBodyID === null) {
        modalBodyID = 'sndBlkMsgFormBody';
    }
    doAjaxWthCallBck(linkArgs,elementID,actionAfter,titleMsg,titleElementID,modalBodyID,function () {
        var fileLink = function (context) {
            var ui = $.summernote.ui;
            var button = ui.button({
                contents: '<i class="fa fa-file"/> Upload',
                tooltip: 'Upload File Inline Within Message Body',
                click: function () {
                    $(function () {
                        $("#allOtherFileInput1").change(function () {
                            var fileName = $(this).val();
                            var input = document.getElementById('allOtherFileInput1');
                            sendNoticesFile(input.files[0],"","","OTHERS",function () {
                                var inptUrl = $("#allOtherInputData1").val();
                                var inptText = $("#allOtherInputData2").val();
                                var inptNwWndw = $("#allOtherInputData2").val();
                                if (inptText === "") {
                                    inptText = "Read More...";
                                }
                                if (inptNwWndw === "") {
                                    inptNwWndw = true;
                                }
                                $('#bulkMessageBody').summernote('createLink',{
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
        $('#bulkMessageBody').summernote({
            minHeight: 375,
            focus: true,
            disableDragAndDrop: false,
            dialogsInBody: true,
            toolbar: [
                ['style',['style']],
                ['style',['bold','italic','underline','clear']],
                ['font',['strikethrough','superscript','subscript']],
                ['fontsize',['fontsize']],
                ['fontname',['fontname']],
                ['color',['color']],
                ['para',['ul','ol','paragraph','height']],
                ['height',['height']],
                ['table',['table']],
                ['insert',['link','picture','video','hr']],
                ['view',['fullscreen','codeview']],
                ['help',['help']],
                ['misc',['print']],
                ['mybutton',['upload']]
            ],
            buttons: {
                upload: fileLink
            },
            callbacks: {
                onImageUpload: function (file,editor,welEditable) {
                    sendNoticesFile(file[0],editor,welEditable,"IMAGES",function () {
                        var inptUrl = $("#allOtherInputData1").val();
                        $('#bulkMessageBody').summernote("insertImage",inptUrl,'filename');
                    });
                }
            }
        });
        var mailSubject = typeof $("#" + mailSubjectElmtID).val() === 'undefined' ? '' : $("#" + mailSubjectElmtID).val();
        var bulkMessageBody = typeof $("#" + bulkMessageBodyElmtID).val() === 'undefined' ? '' : $("#" + bulkMessageBodyElmtID).val();
        $('.note-editable').trigger('focus');
        $('#bulkMessageBody').summernote('code',urldecode(bulkMessageBody));
        $('#mailTo').val(urldecode(mailTo));
        $('#mailCc').val(urldecode(mailCc));
        $('#mailSubject').val(urldecode(mailSubject));
        $('#mailAttchmnts').val(mailAttchmnts);
        $('button.note-btn').mouseup(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });
        $('button.note-btn').hover(function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        },function () {
            $('.modal.in[style="display: block; padding-right: 6px;"][aria-hidden="false"]').css("cssText","z-index: 9998 !important;display: block; padding-right: 6px;");
        });

        $('#' + elementID).off('hidden.bs.modal');
        $('#' + elementID).one("hidden.bs.modal",function (e) {
            $(e.currentTarget).unbind();
        });
    });
}

function getOneVmsDocsForm(pKeyID,trnsType,vwtype) {
    var lnkArgs = 'grp=25&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdVmsTrnsHdrID=' + pKeyID + '&trnsType=' + trnsType;
    doAjaxWthCallBck(lnkArgs,'myFormsModaly','ShowDialog','VMS Trns. Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        var table1 = $('#attchdVMSDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdVMSDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdVMSDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function getOneMcfDocsForm_Gnrl(pKeyID,trnsType,vwtype,formTitle) {
    $('#allOtherInputData99').val('0');
    var lnkArgs = 'grp=17&typ=1&pg=8&vtyp=' + vwtype + '&sbmtdHdrID=' + pKeyID + '&docType=' + trnsType + '&subPgNo=' + vwtype + '.1';
    doAjaxWthCallBck(lnkArgs,'myFormsModaly','ShowDialog',formTitle,'myFormsModalyTitle','myFormsModalyBody',function () {
        var table1 = $('#attchdMCFDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#attchdMCFDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdMCFDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function getOneMcfDocsForm(trnsType,vwtype) {
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
            callback: function () {
                /* your callback code */
            }
        });
        return;
    }
    var lnkArgs = 'grp=17&typ=1&pg=8&vtyp=' + vwtype + '&sbmtdHdrID=' + pKeyID + '&docType=' + trnsType + '&subPgNo=' + vwtype + '.1&pAcctID=' + acctID;
    doAjaxWthCallBck(lnkArgs,'myFormsModaly','ShowDialog','Banking Attached Documents','myFormsModalyTitle','myFormsModalyBody',function () {
        var table1 = $('#attchdMCFDocsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#acctTrnsId').val($('#attchdMCFDocsNwTrnsId').val());
        $('#attchdMCFDocsTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#attchdMCFDocsTblForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
    });
}

function getCashBreakdown(elementID,modalBodyID,titleElementID,formElementID,tRowElementID,formTitle,pgNo,subPgNo,vtyp,vtypActn) {
    var pKeyID = $('#acctTrnsId').val();
    var mcfPymtCrncyNm = $('#mcfPymtCrncyNm').val();
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
            callback: function () {
                /* your callback code */
            }
        });
        return;
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
            $('#' + titleElementID).html(formTitle);
            $('#' + modalBodyID).html(xmlhttp.responseText);
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

            if (vtyp === 5 && vtypActn === 'EDIT') {
                /*Get various field element IDs and populate values*/
                var $tds = $('#' + tRowElementID).find('td');
                $('#lowRange').val($.trim($tds.eq(2).text()));
                $('#highRange').val($.trim($tds.eq(3).text()));
                $('#amountFlat').val($.trim($tds.eq(4).text()));
                $('#amountPrcnt').val($.trim($tds.eq(5).text()));
                $('#remarks').val($.trim($tds.eq(6).text()));
                $('#tblRowElementID').val(tRowElementID);
            }
            $('#' + elementID).off('show.bs.modal');
            $('#' + elementID).on('show.bs.modal',function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            $body.removeClass("mdlloadingDiag");
            $('#' + elementID).modal('show',{
                backdrop: 'static'
            });

            $(document).ready(function () {
                $('#acctTrnsId').val($('#initAcctTrnsId').val());
                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });

                if (!$.fn.DataTable.isDataTable('#cashBreakdownTblEDT')) {
                    var table1 = $('#cashBreakdownTblEDT').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#cashBreakdownTblEDT').wrap('<div class="dataTables_scroll"/>');
                }
                calcAllMcfCbTtl();
                $(".cbQty").focus(function () {
                    $(this).select();
                });
                $(".cbTTlAmnt").focus(function () {
                    $(this).select();
                });
                $(".cbExchngRate").focus(function () {
                    $(this).select();
                });
                $(".cbRnngBal").focus(function () {
                    $(this).select();
                });
                $('.cbQty').bind('keypress',
                    function (event) {
                        //alert(event.which);
                        if (event.which === 13) {
                            var nextItem;
                            var nextItemVal = 0;
                            var curItemVal = $('#cashDenominationsID').val();

                            if (curItemVal == $('#cashDenominationsTtl').val()) {
                                nextItem = $('.cbQty').eq(0);
                            } else {
                                nextItemVal = Number(curItemVal);
                                nextItem = $('.cbQty').eq(nextItemVal);
                            }
                            nextItem.focus();
                        }
                    });

                $('.cbTTlAmnt').bind('keypress',
                    function (event) {
                        if (event.which === 13) {
                            var nextItem;
                            var nextItemVal = 0;
                            var curItemVal = $('#cashDenominationsID').val();

                            if (curItemVal == $('#cashDenominationsTtl').val()) {
                                nextItem = $('.cbTTlAmnt').eq(0);
                            } else {
                                nextItemVal = Number(curItemVal);
                                nextItem = $('.cbTTlAmnt').eq(nextItemVal);
                            }
                            nextItem.focus();
                        }
                    });

                $('.cbExchngRate').bind('keypress',
                    function (event) {
                        if (event.which === 13) {
                            var nextItem;
                            var nextItemVal = 0;
                            var curItemVal = $('#cashDenominationsID').val();

                            if (curItemVal == $('#cashDenominationsTtl').val()) {
                                nextItem = $('.cbExchngRate').eq(0);
                            } else {
                                nextItemVal = Number(curItemVal);
                                nextItem = $('.cbExchngRate').eq(nextItemVal);
                            }
                            nextItem.focus();
                        }
                    });

                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).one('hidden.bs.modal',function (e) {
                    $('#' + titleElementID).html('');
                    $('#' + modalBodyID).html('');
                    $(e.currentTarget).unbind();
                });
            });
        }
    };
    xmlhttp.open("POST","index.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=" + pgNo + "&subPgNo=" + subPgNo + "&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID + "&pAcctID=" + acctID + "&mcfPymtCrncyNm=" + mcfPymtCrncyNm);
}

function getCashBreakdown_LoanRepay(elementID,modalBodyID,titleElementID,formElementID,tRowElementID,formTitle,pgNo,subPgNo,vtyp,vtypActn) {
    var pKeyID = $('#acctTrnsId').val();
    var mcfPymtCrncyNm = $('#mcfPymtCrncyNm').val();
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
            callback: function () {
                /* your callback code */
            }
        });
        return;
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
            $('#' + titleElementID).html(formTitle);
            $('#' + modalBodyID).html(xmlhttp.responseText);
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


            if (vtyp === 5 && vtypActn === 'EDIT') {
                /*Get various field element IDs and populate values*/
                var $tds = $('#' + tRowElementID).find('td');
                $('#lowRange').val($.trim($tds.eq(2).text()));
                $('#highRange').val($.trim($tds.eq(3).text()));
                $('#amountFlat').val($.trim($tds.eq(4).text()));
                $('#amountPrcnt').val($.trim($tds.eq(5).text()));
                $('#remarks').val($.trim($tds.eq(6).text()));
                $('#tblRowElementID').val(tRowElementID);
            }
            $('#' + elementID).off('show.bs.modal');
            $('#' + elementID).on('show.bs.modal',function (e) {
                $(this).find('.modal-body').css({
                    'max-height': '100%'
                });
            });
            $body.removeClass("mdlloadingDiag");
            $('#' + elementID).modal('show',{
                backdrop: 'static'
            });
            $(document).ready(function () {

                $('#acctTrnsId').val($('#initAcctTrnsId').val());
                $('#' + formElementID).submit(function (e) {
                    e.preventDefault();
                    return false;
                });
                if (!$.fn.DataTable.isDataTable('#cashBreakdownTblEDT')) {
                    var table1 = $('#cashBreakdownTblEDT').DataTable({
                        "paging": false,
                        "ordering": false,
                        "info": false,
                        "bFilter": false,
                        "scrollX": false
                    });
                    $('#cashBreakdownTblEDT').wrap('<div class="dataTables_scroll"/>');
                }
                calcAllMcfCbTtl_LoanRepay();
                $(".cbQty").focus(function () {
                    $(this).select();
                });
                $(".cbTTlAmnt").focus(function () {
                    $(this).select();
                });
                $(".cbExchngRate").focus(function () {
                    $(this).select();
                });
                $(".cbRnngBal").focus(function () {
                    $(this).select();
                });
                $('.cbQty').bind('keypress',
                    function (event) {
                        //alert(event.which);
                        if (event.which === 13) {
                            var nextItem;
                            var nextItemVal = 0;
                            var curItemVal = $('#cashDenominationsID').val();

                            if (curItemVal == $('#cashDenominationsTtl').val()) {
                                nextItem = $('.cbQty').eq(0);
                            } else {
                                nextItemVal = Number(curItemVal);
                                nextItem = $('.cbQty').eq(nextItemVal);
                            }
                            nextItem.focus();
                        }
                    });

                $('.cbTTlAmnt').bind('keypress',
                    function (event) {
                        if (event.which === 13) {
                            var nextItem;
                            var nextItemVal = 0;
                            var curItemVal = $('#cashDenominationsID').val();

                            if (curItemVal == $('#cashDenominationsTtl').val()) {
                                nextItem = $('.cbTTlAmnt').eq(0);
                            } else {
                                nextItemVal = Number(curItemVal);
                                nextItem = $('.cbTTlAmnt').eq(nextItemVal);
                            }
                            nextItem.focus();
                        }
                    });

                $('.cbExchngRate').bind('keypress',
                    function (event) {
                        if (event.which === 13) {
                            var nextItem;
                            var nextItemVal = 0;
                            var curItemVal = $('#cashDenominationsID').val();

                            if (curItemVal == $('#cashDenominationsTtl').val()) {
                                nextItem = $('.cbExchngRate').eq(0);
                            } else {
                                nextItemVal = Number(curItemVal);
                                nextItem = $('.cbExchngRate').eq(nextItemVal);
                            }
                            nextItem.focus();
                        }
                    });

                $('#' + elementID).off('hidden.bs.modal');
                $('#' + elementID).one('hidden.bs.modal',function (e) {
                    $('#' + titleElementID).html('');
                    $('#' + modalBodyID).html('');
                    $(e.currentTarget).unbind();
                });
            });
        }
    };
    xmlhttp.open("POST","index.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("grp=17&typ=1&pg=" + pgNo + "&subPgNo=" + subPgNo + "&vtyp=" + vtyp + "&vtypActn=" + vtypActn + "&PKeyID=" + pKeyID + "&pAcctID=" + acctID + "&mcfPymtCrncyNm=" + mcfPymtCrncyNm);
}


function calcAllMcfCbTtl() {
    var ttlAmount = 0;
    var ttlAmount1 = 0;
    var $tds;
    var val;
    var qty1;
    var rate1;
    var crncyNm = $('#mcfPymtCrncyNm').val();
    var mcfTlrTrnsType = typeof $('#mcfTlrTrnsType').val() === 'undefined' ? "DEPOSIT" : $('#mcfTlrTrnsType').val();
    $('#cashBreakdownTblEDT').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];

                qty1 = $("#cashBreakdownRow_" + rndmNum + " .cbQty").val();
                rate1 = $("#cashBreakdownRow_" + rndmNum + " .cbExchngRate").val().replace(/,/g,"");

                $tds = $("#cashBreakdownRow_" + rndmNum).find('td');
                //val = $.trim($tds.eq(4).text()); OLD
                val = $.trim($tds.eq(5).text());

                ttlAmount = ttlAmount + (Number(qty1) * Number(val));
                ttlAmount1 = ttlAmount1 + (Number(qty1) * Number(val) * Number(rate1));

                var itemID = $.trim($tds.eq(6).text());
                var rnngBals = typeof $('#cashBreakdownDenom_' + itemID + '').val() === 'undefined' ? '0' : $('#cashBreakdownDenom_' + itemID + '').val();
                if (mcfTlrTrnsType.trim() == "DEPOSIT") {
                    var newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g,'')) + (Number(qty1) * Number(val));
                    $('#cashBreakdownRow' + i + '_RnngBal').val(addCommas(newRnngBals.toFixed(2)));
                } else {
                    var newRnngBals = Number(rnngBals.replace(/[^-?0-9\.]/g,'')) - (Number(qty1) * Number(val));
                    $('#cashBreakdownRow' + i + '_RnngBal').val(addCommas(newRnngBals.toFixed(2)));
                }
            }
        }
    });

    $('#cashBreakdownLgnd').text(crncyNm + ' ' + addCommas(ttlAmount.toFixed(2)));
    $('#mcfCptrdCbValsTtlBtn').text(addCommas(ttlAmount.toFixed(2)));
    $('#cashDenominationsTtlRaw').val(ttlAmount.toFixed(2));
    $('#cashDenominationsTtlRaw1').val(ttlAmount1.toFixed(2));
    $('#cashDenominationsTtlFmtd').val(addCommas(ttlAmount.toFixed(2)));
}

function calcBlkCshRowsTtlVals() {
    var ttlAmount = 0;
    var ttlRowAmount = 0;
    $('#oneVmsTrnsLnsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var lineTrnsTyp1 = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum + '_TrnsType').val();
                var ttlCptrd = typeof $('#' + rowPrfxNm + rndmNum + '_chqVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_chqVal').val();
                var exchngRate = typeof $('#' + rowPrfxNm + rndmNum + '_exchngRate').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_exchngRate').val();
                ttlRowAmount = (Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')) * Number(exchngRate));
                $('#' + rowPrfxNm + rndmNum + '_chqVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')).toFixed(2)));
                if (lineTrnsTyp1 === "WITHDRAWAL") {
                    ttlAmount = ttlAmount - ttlRowAmount;
                } else {
                    ttlAmount = ttlAmount + ttlRowAmount;
                }
            }
        }
    });
    $('#oneVmsBCashTrnsLnsTable').find('tr').each(function (i,el) {
        if (i > 0) {
            if (typeof $(el).attr('id') === 'undefined') {
                /*Do Nothing*/
            } else {
                var rndmNum = $(el).attr('id').split("_")[1];
                var rowPrfxNm = $(el).attr('id').split("_")[0];
                var lineTrnsTyp1 = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsType').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum + '_TrnsType').val();
                var ttlCptrd = typeof $('#' + rowPrfxNm + rndmNum + '_chqVal').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_chqVal').val();
                var exchngRate = typeof $('#' + rowPrfxNm + rndmNum + '_exchngRate').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_exchngRate').val();
                ttlRowAmount = (Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')) * Number(exchngRate.replace(/[^-?0-9\.]/g,'')));
                $('#' + rowPrfxNm + rndmNum + '_chqVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')).toFixed(2)));
                var balsAfta = typeof $('#' + rowPrfxNm + rndmNum + '_BalsAfta').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_BalsAfta').val();
                var newBals = 0;
                ttlRowAmount = (Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')) * Number(exchngRate));
                $('#' + rowPrfxNm + rndmNum + '_chqVal').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')).toFixed(2)));
                if (lineTrnsTyp1 === "WITHDRAWAL") {
                    ttlAmount = ttlAmount - ttlRowAmount;
                    newBals = Number(balsAfta.replace(/[^-?0-9\.]/g,'')) - ttlRowAmount;
                } else {
                    ttlAmount = ttlAmount + ttlRowAmount;
                    newBals = Number(balsAfta.replace(/[^-?0-9\.]/g,'')) + ttlRowAmount;
                }
                $('#' + rowPrfxNm + rndmNum + '_BalsAftaD').val(addCommas(newBals.toFixed(2)));
            }
        }
    });
    var shdDoCashless = typeof $("#shdDoCashless").val() === 'undefined' ? '0' : $("#shdDoCashless").val();
    if (shdDoCashless !== '0') {
        $('#oneTrnsfrMiscTrnsTable').find('tr').each(function (i,el) {
            if (i > 0) {
                if (typeof $(el).attr('id') === 'undefined') {
                    /*Do Nothing*/
                } else {
                    var rndmNum = $(el).attr('id').split("_")[1];
                    var rowPrfxNm = $(el).attr('id').split("_")[0];
                    var lineTrnsTyp1 = typeof $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val() === 'undefined' ? 'NotPresent' : $('#' + rowPrfxNm + rndmNum + '_TrnsTyp').val();
                    var ttlCptrd = typeof $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val() === 'undefined' ? '0' : $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val();
                    var exchngRate = typeof $('#' + rowPrfxNm + rndmNum + '_Rate').val() === 'undefined' ? '1' : $('#' + rowPrfxNm + rndmNum + '_Rate').val();
                    ttlRowAmount = (Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')) * Number(exchngRate.replace(/[^-?0-9\.]/g,'')));
                    $('#' + rowPrfxNm + rndmNum + '_DstAmnt').val(addCommas(Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')).toFixed(2)));
                    ttlRowAmount = (Number(ttlCptrd.replace(/[^-?0-9\.]/g,'')) * Number(exchngRate));
                    if (lineTrnsTyp1 === "WITHDRAWAL") {
                        ttlAmount = ttlAmount - ttlRowAmount;
                    } else {
                        ttlAmount = ttlAmount + ttlRowAmount;
                    }
                }
            }
        });
    }
    var crncyNm = $('#vmsTrnsCrncyNm').val();
    $('#myCptrdValsTtlBtn').text(crncyNm + ' ' + addCommas(ttlAmount.toFixed(2)));
    $('#myCptrdValsTtlVal').val(ttlAmount.toFixed(2));
}

function getOneUOMBrkdwnForm(pKeyID,vwtype,rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var sbmtdTblRowID = 'oneVmsTrnsLnsTable';
    if (rowPrfxNm.indexOf('Coins') >= 0) {
        sbmtdTblRowID = 'oneVmsTrnsCoinsLnsTable';
    }
    var sbmtdItemID = $('#' + rowPrfxNm + rndmNum + '_ItmID').val();
    var varTtlQtyStr = $('#' + rowPrfxNm + rndmNum + '_Qty').val();
    var varTtlQty = Number(varTtlQtyStr.replace(/[^-?0-9\.]/g,''));
    var sbmtdCrncyNm = typeof $("#vmsTrnsCrncyNm").val() === 'undefined' ? '' : $("#vmsTrnsCrncyNm").val();

    var lnkArgs = 'grp=25&typ=1&pg=2&vtyp=' + vwtype + '&sbmtdVmsTrnsHdrID=' + pKeyID +
        "&sbmtdItemID=" + sbmtdItemID + "&varTtlQty=" + varTtlQty +
        "&sbmtdRwNum=" + rndmNum + "&sbmtdCrncyNm=" + sbmtdCrncyNm +
        "&sbmtdTblRowID=" + sbmtdTblRowID + "&rowIDAttrb=" + rowIDAttrb;

    doAjaxWthCallBck(lnkArgs,'myFormsModalx','ShowDialog','VMS Trns. QTY UOM Breakdown','myFormsModalxTitle','myFormsModalxBody',function () {
        var table1 = $('#oneVmsQtyBrkDwnTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneVmsQtyBrkDwnTable').wrap('<div class="dataTables_scroll"/>');
        $('[data-toggle="tooltip"]').tooltip();
        $('#oneVmsTrnsForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $(".vmsUmbTtl").focus(function () {
            $(this).select();
        });
        $(".vmsUmbQty").focus(function () {
            $(this).select();
        });
    });
}

function afterShowVMS() {
    $('#allOtherInputData99').val('0');
    if (!$.fn.DataTable.isDataTable('#oneVmsExpnsTrnsLnsTable')) {
        var table1 = $('#oneVmsExpnsTrnsLnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneVmsExpnsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#oneVmsTrnsLnsTable')) {
        var table1 = $('#oneVmsTrnsLnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneVmsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#oneVmsTrnsCoinsLnsTable')) {
        var table1 = $('#oneVmsTrnsCoinsLnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneVmsTrnsCoinsLnsTable').wrap('<div class="dataTables_scroll"/>');
    }
    $('[data-toggle="tooltip"]').tooltip();
    $(".vmsCbTtl").focus(function () {
        $(this).select();
    });
    $(".vmsCbQty").focus(function () {
        $(this).select();
    });
    $(".vmsFncCrncy").focus(function () {
        $(this).select();
    });
    $(".vmsTtlAmt").focus(function () {
        $(this).select();
    });
    $(".vmsCbBndl").focus(function () {
        $(this).select();
    });
    $(".vmsCbTray").focus(function () {
        $(this).select();
    });
    $('[data-toggle="tabajxdenoms"]').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var targ = $this.attr('href');
        $(targ + 'tab').tab('show');
        if (targ.indexOf('Coin') >= 0) {
            $('#addNwNoteBtn').addClass('hideNotice');
            $('#btnGrpForNotes').addClass('hideNotice');
            $('#cashItmsNotes').addClass('hideNotice');
            $('#cashItmsExpns').addClass('hideNotice');
            $('#addNwExpnsBtn').addClass('hideNotice');
            $('#addNwCoinBtn').removeClass('hideNotice');
            $('#btnGrpForCoins').removeClass('hideNotice');
            $('#cashItmsCoins').removeClass('hideNotice');
        } else if (targ.indexOf('Note') >= 0) {
            $('#addNwNoteBtn').removeClass('hideNotice');
            $('#btnGrpForNotes').removeClass('hideNotice');
            $('#cashItmsNotes').removeClass('hideNotice');
            $('#cashItmsExpns').addClass('hideNotice');
            $('#addNwCoinBtn').addClass('hideNotice');
            $('#btnGrpForCoins').addClass('hideNotice');
            $('#cashItmsCoins').addClass('hideNotice');
            $('#addNwExpnsBtn').addClass('hideNotice');
        } else {
            $('#cashItmsExpns').removeClass('hideNotice');
            $('#addNwExpnsBtn').removeClass('hideNotice');
            $('#addNwCoinBtn').addClass('hideNotice');
            $('#btnGrpForCoins').addClass('hideNotice');
            $('#cashItmsCoins').addClass('hideNotice');
            $('#addNwNoteBtn').addClass('hideNotice');
            $('#btnGrpForNotes').addClass('hideNotice');
            $('#cashItmsNotes').addClass('hideNotice');
        }
    });
    $('#oneVmsTrnsLnsTable tr').off('click');
    $('#oneVmsTrnsLnsTable tr').click(function () {
        var rowIndex = $('#oneVmsTrnsLnsTable tr').index(this);
        $('#allOtherInputData99').val(rowIndex);
    });
    $('#oneVmsTrnsCoinsLnsTable tr').off('click');
    $('#oneVmsTrnsCoinsLnsTable tr').click(function () {
        var rowIndex = $('#oneVmsTrnsCoinsLnsTable tr').index(this);
        $('#allOtherInputData99').val(rowIndex);
    });
    $('#myFormsModalLg').off('hidden.bs.modal');
}

function afterShowBNK() {
    if (!$.fn.DataTable.isDataTable('#oneVmsTrnsLnsTable')) {
        var table1 = $('#oneVmsTrnsLnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneVmsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#acctHistoryTblAdd')) {
        var table1 = $('#acctHistoryTblAdd').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#acctHistoryTblAdd').wrap('<div class="dataTables_scroll"/>');
    }
    $('[data-toggle="tooltip"]').tooltip();
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
}

function afterShowBulk() {
    $('#allOtherInputData99').val('0');
    if (!$.fn.DataTable.isDataTable('#oneVmsTrnsLnsTable')) {
        var table1 = $('#oneVmsTrnsLnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneVmsTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#oneVmsBCashTrnsLnsTable')) {
        var table1 = $('#oneVmsBCashTrnsLnsTable').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#oneVmsBCashTrnsLnsTable').wrap('<div class="dataTables_scroll"/>');
    }
    if (!$.fn.DataTable.isDataTable('#cashBreakdownTblEDT')) {
        var table1 = $('#cashBreakdownTblEDT').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#cashBreakdownTblEDT').wrap('<div class="dataTables_scroll"/>');
    }
    /*var addCashTrnsBtn = document.getElementById("addCashTrnsBtn");
     var rows = $('#oneVmsBCashTrnsLnsTable tr').length;
     if (rows <= 0) {
     for (var i = 0; i < 30; i++) {
     addCashTrnsBtn.click();
     }
     }*/
    $('[data-toggle="tooltip"]').tooltip();
    $(".blkCshAcnt").focus(function () {
        $(this).select();
    });
    $(".blkCshDocNm").focus(function () {
        $(this).select();
    });
    $(".blkCshAmnt").focus(function () {
        $(this).select();
    });
    $(".blkCshRate").focus(function () {
        $(this).select();
    });
    $('#myFormsModalLg').off('hidden.bs.modal');
    $('#myFormsModalLg').one('hidden.bs.modal',function (e) {
        $(e.currentTarget).unbind();
    });
    $('[data-toggle="tabajxblktrns"]').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var targ = $this.attr('href');
        $(targ + 'tab').tab('show');
        if (targ.indexOf('blkTrnsCash') >= 0) {
            $('#addChqTrnsBtn').addClass('hideNotice');
            $('#addCashTrnsBtn').removeClass('hideNotice');
            $('#addMiscTrnsBtn').addClass('hideNotice');
            $('#blkTrnsCash').removeClass('hideNotice');
            $('#blkTrnsCheques').addClass('hideNotice');
            $('#blkTrnsTllrTill').addClass('hideNotice');
            $('#blkTrnsMisc').addClass('hideNotice');
        } else if (targ.indexOf('blkTrnsCheques') >= 0) {
            $('#addChqTrnsBtn').removeClass('hideNotice');
            $('#addCashTrnsBtn').addClass('hideNotice');
            $('#addMiscTrnsBtn').addClass('hideNotice');
            $('#blkTrnsCheques').removeClass('hideNotice');
            $('#blkTrnsCash').addClass('hideNotice');
            $('#blkTrnsTllrTill').addClass('hideNotice');
            $('#blkTrnsMisc').addClass('hideNotice');
        } else if (targ.indexOf('blkTrnsTllrTill') >= 0) {
            $('#addChqTrnsBtn').addClass('hideNotice');
            $('#addCashTrnsBtn').addClass('hideNotice');
            $('#addMiscTrnsBtn').addClass('hideNotice');
            $('#blkTrnsCash').addClass('hideNotice');
            $('#blkTrnsCheques').addClass('hideNotice');
            $('#blkTrnsTllrTill').removeClass('hideNotice');
            $('#blkTrnsMisc').addClass('hideNotice');
        } else if (targ.indexOf('blkTrnsMisc') >= 0) {
            $('#addChqTrnsBtn').addClass('hideNotice');
            $('#addCashTrnsBtn').addClass('hideNotice');
            $('#addMiscTrnsBtn').removeClass('hideNotice');
            $('#blkTrnsCash').addClass('hideNotice');
            $('#blkTrnsCheques').addClass('hideNotice');
            $('#blkTrnsTllrTill').addClass('hideNotice');
            $('#blkTrnsMisc').removeClass('hideNotice');
        }
    });
    $('#oneVmsTrnsLnsTable tr').off('click');
    $('#oneVmsBCashTrnsLnsTable tr').off('click');
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
    calcAllMcfCbTtl();
    calcBlkCshRowsTtlVals();
    $(".cbQty").focus(function () {
        $(this).select();
    });
    $(".cbTTlAmnt").focus(function () {
        $(this).select();
    });
    $(".cbExchngRate").focus(function () {
        $(this).select();
    });
    $(".cbRnngBal").focus(function () {
        $(this).select();
    });
}

function afterShowTrsfr() {
    $('#allOtherInputData99').val('0');
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
    $(function () {
        $('[data-toggle="tabajxtrnsfrs"]').off('click');
        $('[data-toggle="tabajxtrnsfrs"]').click(function (e) {
            e.preventDefault();
            var $this = $(this);
            var targ = $this.attr('href');
            $(targ + 'tab').tab('show');
            var dttrgt = $this.attr('data-rhodata');
            var linkArgs = 'grp=17&typ=1' + dttrgt;
            if (targ.indexOf('trnsfrOrdrExctns') >= 0) {
                $('#trnsfrOrdrExctns').removeClass('hideNotice');
                $('#trnsfrExtrDstntns').addClass('hideNotice');
                $('#trnsfrMiscTrns').addClass('hideNotice');
                $('#trnsfrExtrSources').addClass('hideNotice');
            } else if (targ.indexOf('trnsfrExtrDstntns') >= 0) {
                $('#trnsfrOrdrExctns').addClass('hideNotice');
                $('#trnsfrMiscTrns').addClass('hideNotice');
                $('#trnsfrExtrDstntns').removeClass('hideNotice');
                $('#trnsfrExtrSources').addClass('hideNotice');
            } else if (targ.indexOf('trnsfrExtrSources') >= 0) {
                $('#trnsfrOrdrExctns').addClass('hideNotice');
                $('#trnsfrExtrDstntns').addClass('hideNotice');
                $('#trnsfrExtrSources').removeClass('hideNotice');
                $('#trnsfrMiscTrns').addClass('hideNotice');
            } else if (targ.indexOf('trnsfrMiscTrns') >= 0) {
                $('#trnsfrOrdrExctns').addClass('hideNotice');
                $('#trnsfrExtrDstntns').addClass('hideNotice');
                $('#trnsfrMiscTrns').removeClass('hideNotice');
                $('#trnsfrExtrSources').addClass('hideNotice');
            }
        });
    });
    $('#myFormsModalLg').off('hidden.bs.modal');
    $('#myFormsModalLg').one('hidden.bs.modal',function (e) {
        $('#myFormsModalBodyLg').html('');
        $('#myFormsModalTitleLg').html('');
        $(e.currentTarget).unbind();
    });
    if (!$.fn.DataTable.isDataTable('#trnsfrOrderExctnsTbl')) {
        var table1 = $('#trnsfrOrderExctnsTbl').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "bFilter": false,
            "scrollX": false
        });
        $('#trnsfrOrderExctnsTbl').wrap('<div class="dataTables_scroll"/>');
    }
    $('[data-toggle="tooltip"]').tooltip();
    $('#allOtherInputData99').val(0);
}

function getPrsnAdminCreate(daGender,daNationality,daReligion,daRelType,daRelCause,iDPrfxComboBox) {
    loadScript("app/prs/prsn.js?v=" + jsFilesVrsn,function () {
        loadScript("app/prs/prsn_admin.js?v=" + jsFilesVrsn,function () {
            getBscProfileForm('myFormsModalLg','myFormsModalBodyLg','myFormsModalTitleLg','dtAdmnBscPrsnPrflForm','Add Person Basic Profile (Direct)',-1,23,2,'ADD','ShowDialog',daGender,daNationality,daReligion,daRelType,daRelCause,iDPrfxComboBox);
        });
    });
}


function getPrsnProfilePDF(pKeyID) {
    var dialog = bootbox.alert({
        title: 'GET PDF',
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> Getting PDF...Please Wait...</p>',
        callback: function () { }
    });
    var formData = new FormData();
    formData.append('grp',8);
    formData.append('typ',1);
    formData.append('pg',5);
    formData.append('q','VIEW');
    formData.append('vtyp',101);
    formData.append('pKeyID',pKeyID);
    var dwnldURL = "";
    var mailTo = "";
    var mailCc = "";
    var mailSubject = "";
    var bulkMessageBody = "";
    var mailAttchmnts = "";
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
                                    PDFObject.embed(dwnldURL,"#allRhoPDFDocDisplays",options);
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
                                },{
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
                                        PDFObject.embed(dwnldURL,"#allRhoPDFDocDisplays",options);
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
                                    ,{
                                    id: 'popOKBtnEmail',
                                    label: 'SEND MAIL',
                                    icon: 'glyphicon glyphicon-envelope',
                                    cssClass: 'btn-primary',
                                    action: function (dialogItself) {
                                        popDialogItself = dialogItself;
                                        sendGeneralMessage1('Email',mailTo,mailCc,mailSubject,bulkMessageBody,mailAttchmnts);
                                        /*window.open(dwnldURL.replace(/(.pdf)/gi, ".html"), '_blank');*/
                                        dialogItself.setClosable(true);
                                        dialogItself.close();
                                    }
                                }
                                ]
                            });
                        }
                    },50);
                },
                error: function (jqXHR,textStatus,errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                    console.warn(jqXHR.responseText);
                }
            });
        });
    });
}