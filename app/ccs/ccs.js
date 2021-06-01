/*CONSUMER CREDIT ASSESSMENT*/
function prepareCcs(lnkArgs, htBody, targ, rspns)
{
    $(targ).html(rspns);
    $(document).ready(function () {
        if (lnkArgs.indexOf("&pg=2&vtyp=0") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=2&vtyp=1") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=2&vtyp=2") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=2&vtyp=4") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=3") !== -1)
        {
            /*$(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="tabajxrptdet"]').click(function (e) {
                    alert("Hello");
                    e.preventDefault();
                    var $this = $(this);
                    var targ = $this.attr('href');
                    var dttrgt = $this.attr('data-rhodata');
                    //var linkArgs = 'grp=14&typ=1' + dttrgt;
                    var linkArgs = dttrgt;
                    $(targ + 'tab').tab('show');
                    if (targ.indexOf('prfBCOPAddPrsnDataEDT') >= 0) {
                        openATab(targ, linkArgs);
                    }
                });
            });*/
                        
            if (!$.fn.DataTable.isDataTable('#allRcmddSrvsMainsTable')) {
                table2 = $('#allRcmddSrvsMainsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allRcmddSrvsMainsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allRcmddSrvsMainsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            $('#allRcmddSrvsMainsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainID').val() === 'undefined' ? -1 : $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainID').val();
                var appntmntID = typeof $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainApptmntID').val() === 'undefined' ? -1 : $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainApptmntID').val();
                var srvsTypeSysCode = typeof $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainSysCode').val() === 'undefined' ? '' : $('#allRcmddSrvsMainsRow' + rndmNum + '_RcmddSrvsMainSysCode').val();
                openATab('#allRcmddSrvsMainsHdrInfo','grp=14&typ=1&pg=102&mdl=Clinic/Hospital&q=ADTNL-DATA-FORM&vtyp=1&appntmntID='+appntmntID+'&formType='+srvsTypeSysCode+'&vtypActn=EDIT&srcRcmddSrvsID='+pKeyID);
            });
            $('#allRcmddSrvsMainsTable tbody') .on('mouseenter', 'tr', function () {
                if ($(this).hasClass('highlight')) {
                    $(this).removeClass('highlight');
                } else {
                    table2.$('tr.highlight').removeClass('highlight');
                    $(this).addClass('highlight');
                }
            });
        } else if (lnkArgs.indexOf("&pg=5") !== -1)
        {
            var table2 = null;
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
            if (!$.fn.DataTable.isDataTable('#allPrvdrGroupsTable')) {
                table2 = $('#allPrvdrGroupsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allPrvdrGroupsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allPrvdrGroupsForm').submit(function (e) {
                e.preventDefault();
                return false;
            });
            if (!$.fn.DataTable.isDataTable('#allPrvdrGroupPersonsTable')) {
                var table3 = $('#allPrvdrGroupPersonsTable').DataTable({
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "bFilter": false,
                    "scrollX": false
                });
                $('#allPrvdrGroupPersonsTable').wrap('<div class="dataTables_scroll"/>');
            }
            $('#allPrvdrGroupsTable tbody').on('click', 'tr', function () {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table2.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var rndmNum = $(this).attr('id').split("_")[1];
                var pKeyID = typeof $('#allPrvdrGroupsRow' + rndmNum + '_PrvdrGroupID').val() === 'undefined' ? '%' : $('#allPrvdrGroupsRow' + rndmNum + '_PrvdrGroupID').val();
                getOnePrvdrGroupDetail(pKeyID, 1);
            });
            $('#allPrvdrGroupsTable tbody')
                    .on('mouseenter', 'tr', function () {
                        if ($(this).hasClass('highlight')) {
                            $(this).removeClass('highlight');
                        } else {
                            table2.$('tr.highlight').removeClass('highlight');
                            $(this).addClass('highlight');
                        }
                    });

            
            $('#allOtherInputData99').val(0);
        } 
        htBody.removeClass("mdlloading");
    });
}

function getOneCathSacramentForm(pKeyID, vwtype, actionTxt, callBackFunc) {

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

    var lnkArgs = 'grp=50&typ=1&vtyp=' + vwtype + '&PKeyID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModalLg', actionTxt, 'Sacrament (ID:' + pKeyID + ')', 'myFormsModalTitleLg', 'myFormsModalBodyLg', function () {
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
        $('#oneCathSacramentForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        $('#myFormsModalLg').off('hidden.bs.modal');
        $('#myFormsModalLg').one('hidden.bs.modal', function (e) {
            getAllCathSacrament('', '#allmodules', 'grp=50&typ=1&vtyp=0');
            $(e.currentTarget).unbind();
        });
        if (!$.fn.DataTable.isDataTable('#oneCathSacramentTable')) {
            var table1 = $('#oneCathSacramentTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#oneCathSacramentTable').wrap('<div class="dataTables_scroll"/>');
        }
        $('[data-toggle="tooltip"]').tooltip();
        $(document).ready(function () {
            callBackFunc();
        });

    });
}

function getAllCathSacrament(actionText, slctr, linkArgs) {
    var srchFor = typeof $("#allCathSacramentSrchFor").val() === 'undefined' ? '%' : $("#allCathSacramentSrchFor").val();
    var srchIn = typeof $("#allCathSacramentSrchIn").val() === 'undefined' ? 'Both' : $("#allCathSacramentSrchIn").val();
    var pageNo = typeof $("#allCathSacramentPageNo").val() === 'undefined' ? 1 : $("#allCathSacramentPageNo").val();
    var limitSze = typeof $("#allCathSacramentDsplySze").val() === 'undefined' ? 10 : $("#allCathSacramentDsplySze").val();
    var sortBy = typeof $("#allCathSacramentSortBy").val() === 'undefined' ? '' : $("#allCathSacramentSortBy").val();
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

function getWitnessForm(pKeyID, actionTxt) {
    if (typeof actionTxt === 'undefined' || actionTxt === null) {
        actionTxt = 'ShowDialog';
    }
    
    if(pKeyID == "-1" || pKeyID == ""){
        bootbox.alert({
            size: "small",
            title: "Rhomicom Message",
            message: "<span style='color:red; font-weight:bold !important;'>Save Baptism First!</span>",
            callback: function () {
                /* your callback code */
            }
        });
        return;
    }
    
    //var lnkArgs = 'grp=12&typ=1&pg=15&vtyp=' + vwtype + '&sbmtdItmPymntPlansSetupID=' + pKeyID;
    var lnkArgs = 'grp=50&typ=1&vtyp=500&sbmtdMtrmntID=' + pKeyID;
    doAjaxWthCallBck(lnkArgs, 'myFormsModaly', actionTxt, 'Witnesses', 'myFormsModalyTitle', 'myFormsModalyBody', function () {
        $('#allOtherInputData99').val('0');
        $('#allItmPymntPlansSetupForm').submit(function (e) {
            e.preventDefault();
            return false;
        });
        
        $('#myFormsModaly').on('show.bs.modal', function (e) {
            $(this).find('.modal-body').css({
                'max-height': '100%'
            });
        
            /*$(this).find('.modal-dialog').css({
                'witdh': '600px'
            });*/
        });
        //$body.removeClass("mdlloadingDiag");
        /*$('#myFormsModaly').modal({
            backdrop: 'static',
            keyboard: false
        });*/
        
        $('#myFormsModaly').off('hidden.bs.modal');
        $('#myFormsModaly').one('hidden.bs.modal', function (e) {
            $(e.currentTarget).unbind();
        });
        /*if (!$.fn.DataTable.isDataTable('#allItmPymntPlansSetupTable')) {
            var table1 = $('#allItmPymntPlansSetupTable').DataTable({
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "scrollX": false
            });
            $('#allItmPymntPlansSetupTable').wrap('<div class="dataTables_scroll"/>');
        }*/
        $('[data-toggle="tooltip"]').tooltip();
    });
}

function saveCathSacrament(optn) {

    var box;
    var box2;

    //$("#svSacramentBtn").attr('disabled', 'disabled');

    getMsgAsyncSilent('grp=1&typ=11&q=Check Session', function () {

        var obj;
        var bptsmID = typeof $('#bptsmID').val() === 'undefined' ? -1 : $('#bptsmID').val();
        var transactionNo = typeof $('#transactionNo').val() === 'undefined' ? '' : $('#transactionNo').val();
        var baptismDate = typeof $('#baptismDate').val() === 'undefined' ? '' : $('#baptismDate').val();
        var baptismPlace = typeof $('#baptismPlace').val() === 'undefined' ? '' : $('#baptismPlace').val();
        var baptismMode = typeof $('#baptismMode').val() === 'undefined' ? '' : $('#baptismMode').val();     
        var minister = typeof $('#minister').val() === 'undefined' ? '' : $('#minister').val();
        var godParent = typeof $('#godParent').val() === 'undefined' ? '' : $('#godParent').val();
                 
        var lastName = typeof $('#lastName').val() === 'undefined' ? '' : $('#lastName').val();
        var otherNames = typeof $('#otherNames').val() === 'undefined' ? '' : $('#otherNames').val();
        var title = typeof $('#title').val() === 'undefined' ? '' : $('#title').val();
        var gender = typeof $('#gender').val() === 'undefined' ? '' : $('#gender').val();
        var dob = typeof $('#dob').val() === 'undefined' ? '' : $('#dob').val();
        var pob = typeof $('#pob').val() === 'undefined' ? '' : $('#pob').val();
        
        
        var nameOfFather = typeof $('#nameOfFather').val() === 'undefined' ? '' : $('#nameOfFather').val();
        var religionOfFather = typeof $('#religionOfFather').val() === 'undefined' ? '' : $('#religionOfFather').val();
        var nameOfMother = typeof $('#nameOfMother').val() === 'undefined' ? '' : $('#nameOfMother').val();
        var religionOfMother = typeof $('#religionOfMother').val() === 'undefined' ? '' : $('#religionOfMother').val();

        var frstCommunionId = typeof $('#frstCommunionId').val() === 'undefined' ? -1 : $('#frstCommunionId').val();
        var firstCommMinister = typeof $('#firstCommMinister').val() === 'undefined' ? '' : $('#firstCommMinister').val();
        var firstCommDate = typeof $('#firstCommDate').val() === 'undefined' ? '' : $('#firstCommDate').val();
        var firstCommPlace = typeof $('#firstCommPlace').val() === 'undefined' ? '' : $('#firstCommPlace').val();

        var cnfrmtnId = typeof $('#cnfrmtnId').val() === 'undefined' ? -1 : $('#cnfrmtnId').val();
        var cnfrmtnName = typeof $('#cnfrmtnName').val() === 'undefined' ? '' : $('#cnfrmtnName').val();
        var cnfrmtnGodParent = typeof $('#cnfrmtnGodParent').val() === 'undefined' ? '' : $('#cnfrmtnGodParent').val();
        var cnfrmtnMinister = typeof $('#cnfrmtnMinister').val() === 'undefined' ? '' : $('#cnfrmtnMinister').val();
        var cnfrmtnPlace = typeof $('#cnfrmtnPlace').val() === 'undefined' ? '' : $('#cnfrmtnPlace').val();
        var cnfrmtnDate = typeof $('#cnfrmtnDate').val() === 'undefined' ? '' : $('#cnfrmtnDate').val();

        var mtrmnyID = typeof $('#mtrmnyID').val() === 'undefined' ? -1 : $('#mtrmnyID').val();
        var mtrmnyPlace = typeof $('#mtrmnyPlace').val() === 'undefined' ? '' : $('#mtrmnyPlace').val();
        var mtrmnyDate = typeof $('#mtrmnyDate').val() === 'undefined' ? '' : $('#mtrmnyDate').val();
        var mtrmnyChurch = typeof $('#mtrmnyChurch').val() === 'undefined' ? '' : $('#mtrmnyChurch').val();
        var mtrmnyMinister = typeof $('#mtrmnyMinister').val() === 'undefined' ? '' : $('#mtrmnyMinister').val();
        var mtrmnyDispensation = typeof $('#mtrmnyDispensation').val() === 'undefined' ? '' : $('#mtrmnyDispensation').val();
        var mtrmnyLocalSpouseBaptismId = typeof $('#mtrmnyLocalSpouseBaptismId').val() === 'undefined' ? -1 : $('#mtrmnyLocalSpouseBaptismId').val();

        var extSpouseLastName = typeof $('#extSpouseLastName').val() === 'undefined' ? '' : $('#extSpouseLastName').val();
        var extSpouseOtherNames = typeof $('#extSpouseOtherNames').val() === 'undefined' ? '' : $('#extSpouseOtherNames').val();
        //var extSpouseTitle = typeof $('#extSpouseTitle').val() === 'undefined' ? '' : $('#extSpouseTitle').val();
        var extSpouseGender = typeof $('#extSpouseGender').val() === 'undefined' ? '' : $('#extSpouseGender').val();
        var extSpouseDob = typeof $('#extSpouseDob').val() === 'undefined' ? '' : $('#extSpouseDob').val();
        var extSpousePob = typeof $('#extSpousePob').val() === 'undefined' ? '' : $('#extSpousePob').val();
        var extSpouseNameOfFather = typeof $('#extSpouseNameOfFather').val() === 'undefined' ? '' : $('#extSpouseNameOfFather').val();
        var extSpouseNameOfMother = typeof $('#extSpouseNameOfMother').val() === 'undefined' ? '' : $('#extSpouseNameOfMother').val();
        var extSpouseBaptismDate = typeof $('#extSpouseBaptismDate').val() === 'undefined' ? '' : $('#extSpouseBaptismDate').val();
        var extSpouseBaptismPlace = typeof $('#extSpouseBaptismPlace').val() === 'undefined' ? '' : $('#extSpouseBaptismPlace').val();

        if (transactionNo.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Baptism No.</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (baptismDate.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Baptism Date</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (baptismPlace.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Baptism Place</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (minister.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Baptism Officiation Minister</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (lastName.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Person Last Name</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (otherNames.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Other Names</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (dob.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Date of Birth of Person</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (pob.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Place of Birth of Person</span>",
                callback: function () {
                    /* your callback code */
                }
            });
            return false;
        } else if (nameOfFather.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Father's Name</span>",
                callback: function () {
                }
            });
            return false;
        } else if (nameOfMother.trim() == "") {
            bootbox.alert({
                size: "small",
                title: "Rhomicom Message",
                message: "<span style='color:red;font-weight:bold !important;'>Enter Mother's Name</span>",
                callback: function () {
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
        formData.append('grp', 50);
        formData.append('typ', 1);
        formData.append('q', 'UPDATE');
        formData.append('actyp', 1);
        formData.append('bptsmID', bptsmID);
        formData.append('transactionNo', transactionNo);
        formData.append('baptismDate', baptismDate);
        formData.append('baptismPlace', baptismPlace);
        formData.append('baptismMode', baptismMode);
        formData.append('minister', minister);
        formData.append('godParent', godParent);
        formData.append('lastName', lastName);
        formData.append('otherNames', otherNames);
        formData.append('title', title);
        formData.append('gender', gender);
        formData.append('dob', dob);
        formData.append('pob', pob);
        formData.append('nameOfFather', nameOfFather);
        formData.append('religionOfFather', religionOfFather);
        formData.append('nameOfMother', nameOfMother);
        formData.append('religionOfMother', religionOfMother);
        
        formData.append('frstCommunionId', frstCommunionId);  
        formData.append('firstCommMinister', firstCommMinister);
        formData.append('firstCommDate', firstCommDate);
        formData.append('firstCommPlace', firstCommPlace);  
        
        formData.append('cnfrmtnId', cnfrmtnId);
        formData.append('cnfrmtnName', cnfrmtnName);
        formData.append('cnfrmtnGodParent', cnfrmtnGodParent);  
        formData.append('cnfrmtnMinister', cnfrmtnMinister);
        formData.append('cnfrmtnPlace', cnfrmtnPlace);
        formData.append('cnfrmtnDate', cnfrmtnDate);  
        
        formData.append('mtrmnyID', mtrmnyID);
        formData.append('mtrmnyPlace', mtrmnyPlace);
        formData.append('mtrmnyDate', mtrmnyDate);  
        formData.append('mtrmnyChurch', mtrmnyChurch);
        formData.append('mtrmnyMinister', mtrmnyMinister);
        formData.append('mtrmnyDispensation', mtrmnyDispensation); 
        formData.append('mtrmnyLocalSpouseBaptismId', mtrmnyLocalSpouseBaptismId);   
        
        formData.append('extSpouseLastName', extSpouseLastName);
        formData.append('extSpouseOtherNames', extSpouseOtherNames);
        formData.append('extSpouseGender', extSpouseGender);  
        formData.append('extSpouseDob', extSpouseDob);
        formData.append('extSpousePob', extSpousePob);
        formData.append('extSpouseNameOfFather', extSpouseNameOfFather);  
        formData.append('extSpouseNameOfMother', extSpouseNameOfMother);
        formData.append('extSpouseBaptismDate', extSpouseBaptismDate);
        formData.append('extSpouseBaptismPlace', extSpouseBaptismPlace);      

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
                    getOneCathSacramentForm(obj.bptsmID, 1, 'ReloadDialog', function () {
                        msg = "Form Saved Successfully!";
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
                $("#svSacramentBtn").removeAttr('disabled');

            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
}

function unfreezeDialog(){
    var box = bootbox.alert({
        size: "small",
        title: "Rhomicom Message",
        message: "Form Refresh Successfully!"
    });
}

function saveWitnessForm(mtrmnyID) {

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
                if (typeof $('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').val() === 'undefined') {
                    /*Do Nothing*/
                } else {

                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').val() == "" || $('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').val() == "") {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').css('border-width', '1px');
                    }
                    if ($('#allItmPymntPlansSetupRow' + rndmNum + '_WitnessFor').val() == "" || $('#allItmPymntPlansSetupRow' + rndmNum + '_WitnessFor').val() == "") {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_WitnessFor').css('border-color', 'red');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_WitnessFor').css('border-width', '2px');
                        errCount = errCount + 1;
                    } else {
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_WitnessFor').css('border-color', '#ccc');
                        $('#allItmPymntPlansSetupRow' + rndmNum + '_WitnessFor').css('border-width', '1px');
                    }

                    if (errCount <= 0) {
                        slctdItmPymntPlansSetup = slctdItmPymntPlansSetup +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_WtnssID').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_Witness').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            $('#allItmPymntPlansSetupRow' + rndmNum + '_WitnessFor').val().replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~" +
                            mtrmnyID + "|";
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
            message: "<span style='color:red;'><b><i>Please enter data in all highlighted record(s)</i></b></span>",
            callback: function () {
                /* your callback code */
            }
        });
        return false;
    }

    var dsplyMsg = "Saving...Please Wait...";
    var dsplyMsgTtle = "Save Witness Form?";
    var dsplyMsgRtrn = "Witness Form Saved";

    var dialog = bootbox.alert({
        title: dsplyMsgTtle,
        size: 'small',
        message: '<p><i class="fa fa-spin fa-spinner"></i> ' + dsplyMsg + '</p>',
        callback: function () {
            var recCnt = typeof $("#recCnt").val() === 'undefined' ? 0 : $("#recCnt").val();

            if (parseInt(recCnt) > 0) {
                getWitnessForm(mtrmnyID, 'ReloadDialog');
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
                    grp: 50,
                    typ: 1,
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
                            var msg = "<span style='color:green;font-weight:bold !important;'>" + dsplyMsgRtrn + "</br><i>" + obj.recCntInst + " record(s) inserted</br>" +
                                obj.recCntUpdt + " record(s) updated</i></span>"
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

function deleteOneWitness(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var PlanName = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_WtnssID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_WtnssID').val();
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
                                    grp: 50,
                                    typ: 1,
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

function delCathSacrament(rowIDAttrb) {
    var rndmNum = rowIDAttrb.split("_")[1];
    var rowPrfxNm = rowIDAttrb.split("_")[0];
    var pKeyID = -1;
    var CustNm = "";
    if (typeof $('#' + rowPrfxNm + rndmNum + '_BptsmID').val() === 'undefined') {
        /*Do Nothing*/
    } else {
        pKeyID = $('#' + rowPrfxNm + rndmNum + '_BptsmID').val();
        var $tds = $('#' + rowIDAttrb).find('td');
        CustNm = $.trim($tds.eq(2).text());
    }
    var dialog = bootbox.confirm({
        title: 'Delete Sacrament?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:red;font-weight:bold;font-style:italic;">DELETE</span> this Sacrament?<br/>Action cannot be Undone!</p>',
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
                    message: '<p><i class="fa fa-spin fa-spinner"></i> Deleting Sacrament...Please Wait...</p>',
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
                                    grp: 50,
                                    typ: 1,
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

//IMPORT-EXPORT
var prgstimerid2;
function imprtSacrament(){
    loadScript("cmn_scrpts/xlsx.core.min.js", function () {
        imprtSacramentData();
    });
}

function imprtSacramentData()
{
    var invldRows = "";
    var dataToSendBZ = "";//BAPTISM
    var dataToSendFC = "";//1ST COMMUNION
    var dataToSendCF = "";//CONFIRMATION
    var dataToSendHM = "";//HOLY MATRIMONY
    var dataToSendHMW = "";//HOLY MATRIMONY WITNESSES
    
    var isFileValid = true;
    var psbHdrID = typeof $("#psbHdrID").val() === 'undefined' ? -1 : $("#psbHdrID").val();
    var dialog1 = bootbox.confirm({
        title: 'Import Indicator Values?',
        size: 'small',
        message: '<p style="text-align:center;">Are you sure you want to <span style="color:green;font-weight:bold;font-style:italic;">IMPORT SACRAMENT</span> to overwrite existing ones?<br/>Action cannot be Undone!</p>',
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
                                        $("#myInformation").html('<span style="color:green;"><i class="fa fa-spin fa-spinner"></i>1% Started Importing Data...Please Wait...</span>');
                                    };
                                    reader.onload = function (event) {
                                        try {
                                            var xlsfile = "";
                                            
                                            //For Browsers other than IE.
                                            if (reader.readAsBinaryString) {
                                                  xlsfile = event.target.result;
                                            } else {
                                                //For IE Browser.
                                                var bytes = new Uint8Array(event.target.result);
                                                for (var i = 0; i < bytes.byteLength; i++) {
                                                    xlsfile += String.fromCharCode(bytes[i]);
                                                } 
                                            }
                                            
                                            var workbook = XLSX.read(xlsfile, {
                                                type: 'binary'
                                            });
                                            
                                            //Fetch the name of First Sheet.
                                            var firstSheet = workbook.SheetNames[0];
                                            var secondSheet = workbook.SheetNames[1];
                                            var thirdSheet = workbook.SheetNames[2];
                                            var fourthSheet = workbook.SheetNames[3];
                                            var fifthSheet = workbook.SheetNames[4];
                                            
                                            //Read all rows from First Sheet into an JSON array.
                                            var dataBZ = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet], {header:1, defval: ""}); //BAPTISM
                                            var dataFC = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[secondSheet], {header:1, defval: ""}); //1ST COMMUNION 
                                            var dataCF = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[thirdSheet], {header:1, defval: ""}); //CONFIRMATION
                                            var dataHM = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[fourthSheet], {header:1, defval: ""}); //HOLY MATRIMONY
                                            var dataHMW = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[fifthSheet], {header:1, defval: ""}); //HOLY MATRIMONY WITNESSES

                                            //FIRST WORK SHEET - BAPTISM
                                            var rwCntr = 0;
                                            var colCntr = 0;
                                            var vldRwCntr = 0;
                                            
                                            var transactionNo = "";
                                            var otherNames = "";
                                            var lastName = "";
                                            var title = "";
                                            var gender = "";
                                            var dob = "";
                                            var pob = "";
                                            var baptismDate = "";
                                            var baptismPlace = "";
                                            var baptismMode = "";
                                            var minister = "";
                                            var godParent = "";
                                            var nameOfFather = "";
                                            var nameOfMother = "";
                                            var religionOfFather = "";
                                            var religionOfMother = "";

                                            var dsp = "";
                                            for (var row in dataBZ) {
                                                
                                                transactionNo = "";
                                                otherNames = "";
                                                lastName = "";
                                                title = "";
                                                gender = "";
                                                dob = "";
                                                pob = "";
                                                baptismDate = "";
                                                baptismPlace = "";
                                                baptismMode = "";
                                                minister = "";
                                                godParent = "";
                                                nameOfFather = "";
                                                nameOfMother = "";
                                                religionOfFather = "";
                                                religionOfMother = "";
                                                                                                
                                                for (var item in dataBZ[row]) {
                                                    colCntr++;
                                                    
                                                    switch (colCntr) {
                                                        case 1:
                                                            transactionNo = dataBZ[row][item];
                                                            break;
                                                        case 2:
                                                            otherNames = dataBZ[row][item];
                                                            break;
                                                        case 3:
                                                            lastName = dataBZ[row][item];
                                                            break;
                                                        case 4:
                                                            title = dataBZ[row][item];
                                                            break;
                                                        case 5:
                                                            gender = dataBZ[row][item];
                                                            break;
                                                        case 6:
                                                            dob = dataBZ[row][item];
                                                            break;
                                                        case 7:
                                                            pob = dataBZ[row][item];
                                                            break;
                                                        case 8:
                                                            baptismDate = dataBZ[row][item];
                                                            break;
                                                        case 9:
                                                            baptismPlace = dataBZ[row][item];
                                                            break;
                                                        case 10:
                                                            baptismMode = dataBZ[row][item];
                                                            break;
                                                         case 11:
                                                            minister = dataBZ[row][item];
                                                            break;
                                                        case 12:
                                                            godParent = dataBZ[row][item];
                                                            break;
                                                        case 13:
                                                            nameOfFather = dataBZ[row][item];
                                                            break;
                                                        case 14:
                                                            nameOfMother = dataBZ[row][item];
                                                            break;
                                                        case 15:
                                                            religionOfFather = dataBZ[row][item];
                                                            break;
                                                        case 16:
                                                            religionOfMother = dataBZ[row][item];
                                                            break;                                                            
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                catgry: 'Error-Validating Selected File',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this file.Invalid Column in File!</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                }
                                                                                               
                                                if (rwCntr === 0) {		
                                                    if (transactionNo.toUpperCase() === "BAPTISM NO" && otherNames.toUpperCase() === "FIRST NAME" && lastName.toUpperCase() === "SURNAME" 
                                                            && title.toUpperCase() === "TITLE" && gender.toUpperCase() === "GENDER" && dob.toUpperCase() === "DATE OF BIRTH" 
                                                            && pob.toUpperCase() === "PLACE OF BIRTH" && baptismDate.toUpperCase() === "DATE OF BAPTISM" && baptismPlace.toUpperCase() === "PLACE OF BAPTISM"
                                                            && baptismMode.toUpperCase() === "BAPTISM MODE" && minister.toUpperCase() === "MINISTER" && godParent.toUpperCase() === "GODPARENT"
                                                            && nameOfFather.toUpperCase() === "FATHER" && nameOfMother.toUpperCase() === "MOTHER" && religionOfFather.toUpperCase() === "RELIGION OF FATHER"
                                                            && religionOfMother.toUpperCase() === "RELIGION OF MOTHER")
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            catgry: 'Error-Import Data',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid File Selected!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                if (1 == 1)
                                                {
                                                    dataToSendBZ = dataToSendBZ + transactionNo.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + otherNames.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + lastName.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + title.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + gender.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + dob.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + pob.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + baptismDate.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + baptismPlace.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + baptismMode.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + minister.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + godParent.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + nameOfFather.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + nameOfMother.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + religionOfFather.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + religionOfMother.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    
                                                    if(transactionNo.trim() !== "" /*&& otherNames.trim() !== ""
                                                        && lastName.trim() !== "" && dob.trim() !== "" && pob.trim() !== ""*/){
                                                          vldRwCntr++;                      
                                                    }
                                                } 
                                                colCntr = 0;
                                                rwCntr++;
                                            }
                                            
                                            output += '<br/><span style="color:blue;font-weight:bold;">BAPTISM: No. of Valid Rows:' + vldRwCntr;
                                            output += '<br/>Total No. of Rows:' + rwCntr + '</span>';

                                            //SECOND WORK SHEET - 1ST COMMUNION
                                            var rwCntrFC = 0;
                                            var colCntrFC = 0;
                                            var vldRwCntrFC = 0;
                                            
                                            var transactionNoFC = "";
                                            var firstCommMinisterFC = "";
                                            var firstCommDateFC = "";
                                            var firstCommPlaceFC = "";
                                            
                                            var dsp = "";
                                            for (var rowFC in dataFC) {
                                                transactionNoFC = "";
                                                firstCommMinisterFC = "";
                                                firstCommDateFC = "";
                                                firstCommPlaceFC = "";
                                                   
                                                
                                                for (var itemFC in dataFC[rowFC]) {

                                                    colCntrFC++;
                                                    
                                                    switch (colCntrFC) {
                                                        case 1:
                                                            transactionNoFC = dataFC[rowFC][itemFC];
                                                            break;
                                                        case 2:
                                                            firstCommMinisterFC = dataFC[rowFC][itemFC];
                                                            break;
                                                        case 3:
                                                            firstCommDateFC = dataFC[rowFC][itemFC];
                                                            break;
                                                        case 4:
                                                            firstCommPlaceFC = dataFC[rowFC][itemFC];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                catgry: 'Error-Validating Worksheet',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this worksheet.Invalid Column on 1ST COMMUNION Worksheet !</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                }
                                                 
                                                if (rwCntrFC === 0) {
                                                    if (transactionNoFC.toUpperCase() === "BAPTISM NO" && firstCommMinisterFC.toUpperCase() === "MINISTER"
                                                            && firstCommDateFC.toUpperCase() === "DATE OF FIRST COMMUNION" && firstCommPlaceFC.toUpperCase() === "PLACE OF FIRST COMMUNION")
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            catgry: 'Error-Import Data',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid Worksheet!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                
                                                if (1 == 1)
                                                {                                                    
                                                    dataToSendFC = dataToSendFC + transactionNoFC.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + firstCommMinisterFC.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + firstCommDateFC.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + firstCommPlaceFC.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    
                                                    if(transactionNoFC.trim() !== "" && firstCommMinisterFC.trim() !== "" && firstCommDateFC.trim() !== ""){
                                                        vldRwCntrFC++;     
                                                    }
                                                } /*else {
                                                    invldRows += "Row "+rwCntr+" has no data in columns ";
                                                }*/
                                                //dsp = dsp + sysCode+'**'+catgryFC + '**' + indctrFC+ '**'+insttnNmFC + '**'+cmntsFC +"</br>"+dataToSend;
                                                colCntrFC = 0;
                                                rwCntrFC++;
                                            }
                                            
                                            output += '<br/><span style="color:blue;font-weight:bold;">1ST COMMUNION: No. of Valid Rows:' + vldRwCntrFC;
                                            output += '<br/>Total No. of Rows:' + rwCntrFC + '</span>';
                                            
                                            //THIRD WORK SHEET - CONFIRMATION
                                            var rwCntrCF = 0;
                                            var colCntrCF = 0;
                                            var vldRwCntrCF = 0;
                                            
                                            var transactionNoCF = "";
                                            var cnfrmtnNameCF = "";
                                            var cnfrmtnGodParentCF = "";
                                            var cnfrmtnMinisterCF = "";
                                            var cnfrmtnPlaceCF = "";
                                            var cnfrmtnDateCF = "";
                                            
                                            var dsp = "";
                                            for (var rowCF in dataCF) {
                                                transactionNoCF = "";
                                                cnfrmtnNameCF = "";
                                                cnfrmtnGodParentCF = "";
                                                cnfrmtnMinisterCF = "";
                                                cnfrmtnPlaceCF = "";
						cnfrmtnDateCF = "";
                                                
                                                for (var itemCF in dataCF[rowCF]) {

                                                    colCntrCF++;
                                                    
                                                    switch (colCntrCF) {
                                                        case 1:
                                                            transactionNoCF = dataCF[rowCF][itemCF];
                                                            break;
                                                        case 2:
                                                            cnfrmtnNameCF = dataCF[rowCF][itemCF];
                                                            break;
                                                        case 3:
                                                            cnfrmtnGodParentCF = dataCF[rowCF][itemCF];
                                                            break;
                                                        case 4:
                                                            cnfrmtnMinisterCF = dataCF[rowCF][itemCF];
                                                            break;
							case 5:
                                                            cnfrmtnPlaceCF = dataCF[rowCF][itemCF];
                                                            break;
                                                        case 6:
                                                            cnfrmtnDateCF = dataCF[rowCF][itemCF];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                catgry: 'Error-Validating Worksheet',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this worksheet.Invalid Column on CONFIRMATION Worksheet !</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                }
                                                
                                                 
						if (rwCntrCF === 0) {
                                                    if (transactionNoCF.toUpperCase() === "BAPTISM NO" && cnfrmtnNameCF.toUpperCase() === "CONFIRMATION NAME"
                                                            && cnfrmtnGodParentCF.toUpperCase() === "GODPARENT" && cnfrmtnMinisterCF.toUpperCase() === "MINISTER"
							    && cnfrmtnPlaceCF.toUpperCase() === "CONFIRMATION PLACE" && cnfrmtnDateCF.toUpperCase() === "DATE OF CONFIRMATION")
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            catgry: 'Error-Import Data',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid Worksheet!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                
                                                if (1 == 1)
                                                {                                                    
                                                    dataToSendCF = dataToSendCF + transactionNoCF.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + cnfrmtnNameCF.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + cnfrmtnGodParentCF.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + cnfrmtnMinisterCF.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + cnfrmtnPlaceCF.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + cnfrmtnDateCF.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    
                                                    if(transactionNoCF.trim() !== "" /*&& cnfrmtnGodParentCF.trim() !== "" && cnfrmtnMinisterCF.trim() !== ""
													&& cnfrmtnPlaceCF.trim() !== "" && cnfrmtnDateCF.trim() !== ""*/){
                                                        vldRwCntrCF++;     
                                                    }
                                                } /*else {
                                                    invldRows += "Row "+rwCntr+" has no data in columns ";
                                                }*/
                                                //dsp = dsp + sysCode+'**'+catgryCF + '**' + indctrCF+ '**'+insttnNmCF + '**'+cmntsCF +"</br>"+dataToSend;
                                                colCntrCF = 0;
                                                rwCntrCF++;
                                            }
                                            
                                            output += '<br/><span style="color:blue;font-weight:bold;">CONFIRMATION: No. of Valid Rows:' + vldRwCntrCF;
                                            output += '<br/>Total No. of Rows:' + rwCntrCF + '</span>';
                                            
                                            
                                            //FOURTH WORK SHEET - HOLY MATRIMONY
                                            var rwCntrHM = 0;
                                            var colCntrHM = 0;
                                            var vldRwCntrHM = 0;
                                            
                                            var transactionNoHM = "";
                                            var mtrmnyPlaceHM = "";
                                            var mtrmnyDateHM = "";
                                            var mtrmnyChurchHM = "";
                                            var mtrmnyMinisterHM = "";
                                            var mtrmnyDispensationHM = "";
                                            var mtrmnyLocalSpouseBaptismNoHM = "";
                                            var extSpouseOtherNamesHM = "";
                                            var extSpouseLastNameHM = "";
                                            var extSpouseGenderHM = "";
                                            var extSpouseDobHM = "";
                                            var extSpousePobHM = "";
                                            var extSpouseNameOfFatherHM = "";
                                            var extSpouseNameOfMotherHM = "";
                                            var extSpouseBaptismDateHM = "";
                                            var extSpouseBaptismPlaceHM = "";
											
                                            var dsp = "";
                                            for (var rowHM in dataHM) {
                                                transactionNoHM = "";
                                                mtrmnyPlaceHM = "";
                                                mtrmnyDateHM = "";
                                                mtrmnyChurchHM = "";
                                                mtrmnyMinisterHM = "";
                                                mtrmnyDispensationHM = "";
                                                mtrmnyLocalSpouseBaptismNoHM = "";
                                                extSpouseOtherNamesHM = "";
                                                extSpouseLastNameHM = "";
                                                extSpouseGenderHM = "";
                                                extSpouseDobHM = "";
                                                extSpousePobHM = "";
                                                extSpouseNameOfFatherHM = "";
                                                extSpouseNameOfMotherHM = "";
                                                extSpouseBaptismDateHM = "";
                                                extSpouseBaptismPlaceHM = "";
                                                
                                                for (var itemHM in dataHM[rowHM]) {

                                                    colCntrHM++;
                                                    
                                                    switch (colCntrHM) {
                                                        case 1:
                                                            transactionNoHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 2:
                                                            mtrmnyPlaceHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 3:
                                                            mtrmnyDateHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 4:
                                                            mtrmnyChurchHM = dataHM[rowHM][itemHM];
                                                            break;
							case 5:
                                                            mtrmnyMinisterHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 6:
                                                            mtrmnyDispensationHM = dataHM[rowHM][itemHM];
                                                            break;
							case 7:
                                                            mtrmnyLocalSpouseBaptismNoHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 8:
                                                            extSpouseOtherNamesHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 9:
                                                            extSpouseLastNameHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 10:
                                                            extSpouseGenderHM = dataHM[rowHM][itemHM];
                                                            break;
							case 11:
                                                            extSpouseDobHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 12:
                                                            extSpousePobHM = dataHM[rowHM][itemHM];
                                                            break;
							case 13:
                                                            extSpouseNameOfFatherHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 14:
                                                            extSpouseNameOfMotherHM = dataHM[rowHM][itemHM];
                                                            break;
							case 15:
                                                            extSpouseBaptismDateHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        case 16:
                                                            extSpouseBaptismPlaceHM = dataHM[rowHM][itemHM];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                catgry: 'Error-Validating Worksheet',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this worksheet.Invalid Column on HOLY MATRIMONY Worksheet !</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                }
		
						if (rwCntrHM === 0) {
                                                    if (transactionNoHM.toUpperCase() === "BAPTISM NO" && mtrmnyPlaceHM.toUpperCase() === "MATRIMONY PLACE"
                                                            && mtrmnyDateHM.toUpperCase() === "MATRIMONY DATE" && mtrmnyChurchHM.toUpperCase() === "CHURCH"
                                                            && mtrmnyMinisterHM.toUpperCase() === "MINISTER" && mtrmnyDispensationHM.toUpperCase() === "DISPENSATION"
                                                            && mtrmnyLocalSpouseBaptismNoHM.toUpperCase() === "LOCAL SPOUSE BAPTISM NUMBER" && extSpouseOtherNamesHM.toUpperCase() === "EXT-SPOUSE FIRST NAME"
                                                            && extSpouseLastNameHM.toUpperCase() === "EXT-SPOUSE SURNAME" && extSpouseGenderHM.toUpperCase() === "EXT-SPOUSE GENDER"
                                                            && extSpouseDobHM.toUpperCase() === "EXT-SPOUSE DATE OF BIRTH" && extSpousePobHM.toUpperCase() === "EXT-SPOUSE PLACE OF BIRTH"
                                                            && extSpouseNameOfFatherHM.toUpperCase() === "EXT-SPOUSE FATHER" && extSpouseNameOfMotherHM.toUpperCase() === "EXT-SPOUSE MOTHER"
                                                            && extSpouseBaptismDateHM.toUpperCase() === "EXT-SPOUSE BAPTISM DATE" && extSpouseBaptismPlaceHM.toUpperCase() === "EXT-SPOUSE BAPTISM PLACE")
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            catgry: 'Error-Import Data',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid Worksheet!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                
                                                if (1 == 1)
                                                {                                                    
                                                    dataToSendHM = dataToSendHM + transactionNoHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + mtrmnyPlaceHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + mtrmnyDateHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + mtrmnyChurchHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + mtrmnyMinisterHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + mtrmnyDispensationHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + mtrmnyLocalSpouseBaptismNoHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseOtherNamesHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseLastNameHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseGenderHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseDobHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpousePobHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseNameOfFatherHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseNameOfMotherHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseBaptismDateHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + extSpouseBaptismPlaceHM.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "|";
                                                    
                                                    if(transactionNoHM.trim() !== "" /*&& mtrmnyDateHM.trim() !== "" && mtrmnyChurchHM.trim() !== ""
													&& mtrmnyMinisterHM.trim() !== "" && mtrmnyDispensationHM.trim() !== ""*/){
                                                        vldRwCntrHM++;     
                                                    }
                                                } /*else {
                                                    invldRows += "Row "+rwCntr+" has no data in columns ";
                                                }*/
                                                //dsp = dsp + sysCode+'**'+catgryHM + '**' + indctrHM+ '**'+insttnNmHM + '**'+cmntsHM +"</br>"+dataToSend;
                                                colCntrHM = 0;
                                                rwCntrHM++;
                                            }
                                            
                                            output += '<br/><span style="color:blue;font-weight:bold;">HOLY MATRIMONY: No. of Valid Rows:' + vldRwCntrHM;
                                            output += '<br/>Total No. of Rows:' + rwCntrHM + '</span>';

                                            //FIFTH WORK SHEET - HOLY MATRIMONY WITNESSES
                                            var rwCntrHMW = 0;
                                            var colCntrHMW = 0;
                                            var vldRwCntrHMW = 0;
                                            
                                            var transactionNoHMW = "";
                                            var witnessHMW = "";
                                            var witnessForHMW = "";
                                            
                                            var dsp = "";
                                            for (var rowHMW in dataHMW) {
                                                transactionNoHMW = "";
                                                witnessHMW = "";
                                                witnessForHMW = "";
												
                                                for (var itemHMW in dataHMW[rowHMW]) {

                                                    colCntrHMW++;
                                                    
                                                    switch (colCntrHMW) {
                                                        case 1:
                                                            transactionNoHMW = dataHMW[rowHMW][itemHMW];
                                                            break;
                                                        case 2:
                                                            witnessHMW = dataHMW[rowHMW][itemHMW];
                                                            break;
                                                        case 3:
                                                            witnessForHMW = dataHMW[rowHMW][itemHMW];
                                                            break;
                                                        default:
                                                            var dialog = bootbox.alert({
                                                                catgry: 'Error-Validating Worksheet',
                                                                size: 'small',
                                                                message: '<span style="color:red;font-weight:bold:">An error occurred reading this worksheet.Invalid Column on HOLY MATRIMONY WITNESSES Worksheet !</span>',
                                                                callback: function () {
                                                                    isFileValid = false;
                                                                    reader.abort();
                                                                }
                                                            });
                                                    }
                                                }

                                                if (rwCntrHMW === 0) {
                                                    if (transactionNoHMW.toUpperCase() === "BAPTISM NO" && witnessHMW.toUpperCase() === "WITNESS NAME"
                                                            && witnessForHMW.toUpperCase() === "WITNESS FOR")
                                                    {

                                                    } else {
                                                        var dialog = bootbox.alert({
                                                            catgry: 'Error-Import Data',
                                                            size: 'small',
                                                            message: '<span style="color:red;font-weight:bold:">Invalid Worksheet!</span>',
                                                            callback: function () {
                                                                isFileValid = false;
                                                                reader.abort();
                                                            }
                                                        });
                                                    }
                                                }
                                                
                                                if (1 == 1)
                                                {                                                    
                                                    dataToSendHMW = dataToSendHMW + transactionNoHMW.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + witnessHMW.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") + "~"
                                                            + witnessForHMW.replace(/(~)/g, "{-;-;}").replace(/(\|)/g, "{:;:;}") +  "|";
                                                    
                                                    if(transactionNoHMW.trim() !== "" && witnessHMW.trim() !== "" && witnessForHMW.trim() !== ""){
                                                        vldRwCntrHMW++;     
                                                    }
                                                } /*else {
                                                    invldRows += "Row "+rwCntr+" has no data in columns ";
                                                }*/
                                                //dsp = dsp + sysCode+'**'+catgryHMW + '**' + indctrHMW+ '**'+insttnNmHMW + '**'+cmntsHMW +"</br>"+dataToSend;
                                                colCntrHMW = 0;
                                                rwCntrHMW++;
                                            }
											
					    output += '<br/><span style="color:blue;font-weight:bold;">HOLY MATRIMONY WITNESSES: No. of Valid Rows:' + vldRwCntrHMW;
                                            output += '<br/>Total No. of Rows:' + rwCntrHMW + '</span>';
                                            
                                            //output += '<br/>Recs:' + dsp + '</span>';
                                            $("#fileInformation").html(output);
                                        } catch (err) {
                                            var dialog = bootbox.alert({
                                                catgry: 'Error-Import Data',
                                                size: 'small',
                                                message: 'Error:' + err.message,
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                }
                                            });
                                        }
                                    };
                                                                        
                                    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                                    if (regex.test(input.value.toLowerCase())) {
                                        if (typeof (FileReader) != "undefined") {
                                            //var reader = new FileReader();

                                            //For Browsers other than IE.
                                            if (reader.readAsBinaryString) {
                                                reader.readAsBinaryString(file);
                                            } else {
                                                //For IE Browser.
                                                reader.readAsArrayBuffer(file);
                                            }
                                        } else {
                                            var dialog = bootbox.alert({
                                                catgry: 'Error-Import Data',
                                                size: 'small',
                                                message: '<span style="color:red;font-weight:bold:">This browser does not support HTML5!</span>',
                                                callback: function () {
                                                    isFileValid = false;
                                                    reader.abort();
                                                }
                                            });
                                        }
                                    } else {
                                        var dialog = bootbox.alert({
                                            catgry: 'Error-Import Data',
                                            size: 'small',
                                            message: '<span style="color:red;font-weight:bold:">Please upload a valid Excel file!</span>',
                                            callback: function () {
                                                isFileValid = false;
                                                reader.abort();
                                            }
                                        });
                                    }
                                    //reader.readAsBinaryString(file);
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
                                            saveSacramentData(dataToSendBZ, dataToSendFC, dataToSendCF, dataToSendHM, dataToSendHMW);
                                        } else {
                                            var dialog = bootbox.alert({
                                                catgry: 'Error-Import Data',
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

function saveSacramentData(dataToSendBZ, dataToSendFC, dataToSendCF, dataToSendHM, dataToSendHMW)
{
    if (dataToSendBZ.trim() === '')
    {
        bootbox.alert({
            catgry: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;">No Data on BAPTISM to Send!</span></p>'
        });
        return false;
    }
    
    /*if (dataToSendFC.trim() === '')
    {
        bootbox.alert({
            catgry: 'System Alert!',
            size: 'small',
            message: '<p><span style="font-family: georgia, times;font-size: 12px;font-style:italic;' +
                    'font-weight:bold;">No Data on VALUE DRIVERS to Send!</span></p>'
        });
        return false;
    }*/
    
    var dialog = bootbox.alert({
        title: 'Data Import',
        size: 'medium',
        message: '<div id="myProgress1"><div id="myBar1"></div></div><div id="myInformation1" style="color:blue;font-weight:bold;"><i class="fa fa-spin fa-spinner"></i> Importing Data...Please Wait...</div><div id="myErrorBar1"></div>',
        callback: function () {
            clearInterval(prgstimerid2);
            getAllCathSacrament('', '#allmodules', 'grp=50&typ=1&pg=0&vtyp=0');
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
                    grp: 50,
                    typ: 1,
                    q: 'IMPORT AND EXPORT',
                    srctyp: 5,
                    dataToSendBZ: dataToSendBZ,
                    dataToSendFC: dataToSendFC,
                    dataToSendCF: dataToSendCF,
                    dataToSendHM: dataToSendHM,
                    dataToSendHMW: dataToSendHMW
                }
            });
            prgstimerid2 = window.setInterval(rfrshSaveSacramentData, 1000);
        });
    });
}

function rfrshSaveSacramentData() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 50,
            typ: 1,
            q: 'IMPORT AND EXPORT',
            srctyp: 6
        },
        success: function (data) {
            var elem = document.getElementById('myBar1');
            elem.style.width = data.percent + '%';
            $("#myInformation1").html(data.message);
            $("#myErrorBar1").html(data.cstmerrors);
            if (data.percent >= 100) {
                window.clearInterval(prgstimerid2);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus + " " + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
}

function exprtSacrament()
{
    var fieldsPrfx = "CathSacrament";
    //var bptsmID = typeof $("#bptsmID").val() === 'undefined' ? -1 : $("#bptsmID").val();
    var srchFor = typeof $("#all" + fieldsPrfx + "SrchFor").val() === 'undefined' ? '%' : $("#all" + fieldsPrfx + "SrchFor").val();
    var srchIn = typeof $("#all" + fieldsPrfx + "SrchIn").val() === 'undefined' ? 'Both' : $("#all" + fieldsPrfx + "SrchIn").val();
    var sortBy = typeof $("#all" + fieldsPrfx + "SortBy").val() === 'undefined' ? 'Baptism No.' : $("#all" + fieldsPrfx + "SortBy").val();
    
    var msgTitle = "Records";
    var exprtMsg = '<form role="form">' +
            '<p style="color:#000;">' +
            'How many ' + msgTitle + ' will you like to Export?' +
            '<br/>1=No ' + msgTitle + '(Empty Template)' +
            '<br/>2=All ' + msgTitle + '' +
            '<br/>3-Infinity=Specify the exact number of ' + msgTitle + ' to Export<br/>' +
            '</p>' +
            '<div class="form-group" style="margin-bottom:10px !important;">' +
            '<div class="input-group">' +
            '<span class="input-group-addon" id="basic-addon1">' +
            '<i class="fa fa-sort-numeric-asc fa-fw fa-border"></i></span>' +
            '<input type="number" class="form-control" placeholder="" aria-describedby="basic-addon1" id="recsToExprt" name="recsToExprt" onkeyup="" tabindex="0" autofocus value="2">' +
            '</div>' +
            '</div>' +
            '<p style="font-size:12px;" id="msgAreaExprt">&nbsp;' +
            '</p>' +
            '</form>';
    BootstrapDialog.show({
        size: BootstrapDialog.SIZE_SMALL,
        type: BootstrapDialog.TYPE_DEFAULT,
        title: 'Export ' + msgTitle + '!',
        message: exprtMsg,
        animate: true,
        closable: true,
        closeByBackdrop: false,
        closeByKeyboard: false,
        onshow: function (dialogItself) {
        },
        onshown: function (dialogItself) {
            exprtBtn = dialogItself.getButton('btn_exprt_recs');
            $('#recsToExprt').focus();
        },
        buttons: [{
                label: 'Cancel',
                icon: 'glyphicon glyphicon-menu-left',
                cssClass: 'btn-default',
                action: function (dialogItself) {
                    window.clearInterval(prgstimerid2);
                    dialogItself.close();
                    ClearAllIntervals();
                }
            }, {
                id: 'btn_exprt_recs',
                label: 'Export',
                icon: 'glyphicon glyphicon-menu-right',
                cssClass: 'btn-primary',
                action: function (dialogItself) {
                    /*Validate Input and Do Ajax if OK*/
                    var inptNum = $('#recsToExprt').val();
                    if (!isNumber(inptNum))
                    {
                        var dialog = bootbox.alert({
                            title: 'Exporting ' + msgTitle + '',
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
                                    grp: 50,
                                    typ: 1,
                                    q: 'IMPORT AND EXPORT',
				    srctyp: 7,
                                    inptNum: inptNum,
                                    pSrchFor: srchFor,
                                    pSrchIn: srchIn,
                                    pSortBy: sortBy
                                }
                            });
                            prgstimerid2 = window.setInterval(rfrshExprtFSCIndicatorValuesPrcs, 1000);
                        });
                    }
                }
            }]
    });
}

function rfrshExprtFSCIndicatorValuesPrcs() {
    $.ajax({
        method: "POST",
        url: "index.php",
        data: {
            grp: 50,
            typ: 1,
	    q: 'IMPORT AND EXPORT',
	    srctyp: 8
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