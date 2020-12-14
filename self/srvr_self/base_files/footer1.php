<script src="cs/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 
<script src="cs/plugins/jquery-ui/jquery-ui.min.js"></script>-->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip 
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>-->
<!-- Bootstrap 4 -->
<!--<script src="cs/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<script src="cs/plugins/bootstrap4/js/bootstrap.bundle.min.js"></script>
<script src="cs/plugins/bootstrap4-dialog/js/bootstrap-dialog.min.js"></script>
<!-- ChartJS -->
<!--<script src="cs/plugins/chart.js/Chart.min.js"></script>-->
<!-- Summernote -->
<script src="cs/plugins/summernote/summernote-bs4.min.js"></script>
<!-- AdminLTE App -->
<script src="cs/dist/js/template-app.min.js"></script>
<!-- DataTables -->
<script src="cs/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="cs/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<!-- Moment -->
<script src="cs/plugins/moment/moment.min.js"></script>
<script src="cs/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<div id="allOtherContent"></div>
<iframe id="allOtherIframe1" style="display:none;" src=""></iframe>
<a id="allOtherATag1" style="display:none" href="" download="exported_table.xls"></a>
<input type="hidden" id="allOtherInputData1" value="" />
<input type="hidden" id="allOtherInputData2" value="" />
<input type="hidden" id="allOtherInputData3" value="" />
<input type="hidden" id="allOtherInputData4" value="" />
<input type="hidden" id="allOtherInputData5" value="" />
<input type="hidden" id="allOtherInputData99" value="0" />
<input type="hidden" id="allOtherInputData101" value="0" />
<input id="allOtherFileInput1" type="file" style="visibility:hidden" />
<input id="allOtherFileInput2" type="file" style="visibility:hidden" />
<input id="allOtherFileInput3" type="file" style="visibility:hidden" />
<input id="allOtherFileInput4" type="file" style="visibility:hidden" />
<input id="allOtherFileInput5" type="file" style="visibility:hidden" />
<input type="hidden" id="allOtherInputOrgID" value="<?php echo $orgID; ?>">
<input type="hidden" id="allOtherInputUsrID" value="<?php echo $usrID; ?>">
<input id="allOtherFileInput6" type="file" style="visibility:hidden" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
<div class="modalLdng"></div>
<div class="modal fade" id="myFormsModalLg" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl" style="min-width:300px;max-width:93%;width:93%;margin:5px calc(1.4% + 1px) !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myFormsModalTitleLg"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="myFormsModalBodyLg" style="min-height: 100px;border-bottom: none !important;">
            </div>
            <div class="modal-footer justify-content-between" style="border-top: none !important;">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="myFormsModalLx" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl" style="min-width:300px;max-width:87%;width:87%;margin:10px calc(4.5% + 1px) !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myFormsModalLxTitle"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="myFormsModalLxBody" style="min-height: 100px;border-bottom: none !important;"></div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myFormsModalx" aria-hidden="true" style="display: none;z-index: 9997 !important;">
    <div class="modal-dialog modal-lg" id="myFormsModalxDiag" style="min-width:300px;max-width:90%;width:40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myFormsModalxTitle"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="myFormsModalxBody" style="min-height: 100px;border-bottom: none !important;"></div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myLovModal" aria-hidden="true" style="display: none;z-index: 10099 !important;">
    <div class="modal-dialog modal-lg" role="document" id="myLovModalDiag" style="min-width:300px;max-width:90%;width:40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLovModalTitle"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="myLovModalBody" style="min-height: 100px;border-bottom: none !important;"></div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myFormsModalNrmlZI" aria-hidden="true" style="display: none;z-index: 9999 !important;">
    <div class="modal-dialog modal-lg" role="document" id="myFormsModalNrmlDiagZI" style="min-width:340px;max-width:90%;width:60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myFormsModalNrmlTitleZI"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="myFormsModalNrmlBodyZI" style="min-height: 100px;border-bottom: none !important;"></div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myFormsModalLgZI" aria-hidden="true" style="display: none;z-index: 9999 !important;">
    <div class="modal-dialog" role="document" style="min-width:300px;max-width:93%;width:93%;margin:5px calc(1.4% + 1px) !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myFormsModalTitleLgZI"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="myFormsModalBodyLgZI" style="min-height: 100px;border-bottom: none !important;"></div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="sndBlkMsgForm" aria-hidden="true" style="display: none;z-index: 9997 !important;">
    <div class="modal-dialog" role="document" style="min-width:80%;width:80% !important;margin:5px calc(2.4% + 1px) !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="sndBlkMsgFormTitle">Send Bulk Email/SMS</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="sndBlkMsgFormBody" style="border-bottom: none !important;">
            </div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>
<!--<div class="modal fade" id="myFormsModaly" aria-hidden="true" style="display: none;z-index: 9997 !important;">
    <div class="modal-dialog" role="document" id="myFormsModalyDiag" style="min-width:300px;max-width:90%;width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myFormsModalyTitle"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="myFormsModalyBody" style="min-height: 100px;border-bottom: none !important;"></div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>-->
<div class="modal fade" id="myFormsModalxLG" aria-hidden="true" style="display: none;z-index: 9996 !important;">
    <div class="modal-dialog" role="document" id="myFormsModalxLGDiag" style="min-width:340px;max-width:90%;width:50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myFormsModalxLGTitle"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="myFormsModalxLGBody" style="min-height: 100px;border-bottom: none !important;"></div>
            <div class="modal-footer" style="border-top: none !important;">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var jsFilesVrsn = '<?php echo $jsCssFileVrsn; ?>';
    var noticesElmntNm = '<?php echo $noticesElmntNm; ?>';
    var shdHideFSRpt;
    var autoCreateSalesLns = -1;
    var trnsNtAllwdDts = new Array();
    var trnsNtAllwdDys = new Array();
</script>
<script src="js_self/self_global.js?v=<?php echo $jsCssFileVrsn; ?>"></script>
<script src="js_self/self_rpt.js?v=<?php echo $jsCssFileVrsn; ?>"></script>
</body>

</html>