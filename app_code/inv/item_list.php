<?php
$canAdd = test_prmssns($dfltPrvldgs[8], $mdlNm);
$canEdt = test_prmssns($dfltPrvldgs[9], $mdlNm);
$canVwCstPrc = test_prmssns("Can View Cost Price", $mdlNm); //Can View Cost Price
$canDel = $canEdt;
$canVwRcHstry = test_prmssns("View Record History", $mdlNm);

$pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
$lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 15;
$sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Last Created";
if (array_key_exists('lgn_num', get_defined_vars())) {
    if ($lgn_num > 0 && $canview === true) {
        if ($qstr == "DELETE") {
            if ($actyp == 1) {
                /* Delete Item */
                $canDelItm = test_prmssns($dfltPrvldgs[33], $mdlNm);
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? cleanInputData($_POST['sbmtdItmID']) : -1;
                $itemNm = isset($_POST['itemNm']) ? cleanInputData($_POST['itemNm']) : "";
                if ($canDelItm) {
                    echo deleteINVItm($sbmtdItmID, $itemNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 2) {
                /* Delete Item Stores */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdStckID = isset($_POST['sbmtdStckID']) ? cleanInputData($_POST['sbmtdStckID']) : -1;
                $stockNm = isset($_POST['stockNm']) ? cleanInputData($_POST['stockNm']) : "";
                if ($canEdtItm) {
                    echo deleteINVItmStore($sbmtdStckID, $stockNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 3) {
                /* Delete Item UoM */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdLineID = isset($_POST['sbmtdLineID']) ? cleanInputData($_POST['sbmtdLineID']) : -1;
                $uomNm = isset($_POST['uomNm']) ? cleanInputData($_POST['uomNm']) : "";
                if ($canEdtItm) {
                    echo deleteINVItmUom($sbmtdLineID, $uomNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 4) {
                /* Delete Item Interactions */
                $canEdtItm = test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdLineID = isset($_POST['sbmtdLineID']) ? cleanInputData($_POST['sbmtdLineID']) : -1;
                $drugNm = isset($_POST['drugNm']) ? cleanInputData($_POST['drugNm']) : "";
                if ($canEdtItm) {
                    echo deleteINVItmIntrctn($sbmtdLineID, $drugNm);
                } else {
                    restricted();
                }
            } else if ($actyp == 500) { //Plan Setup
                $rowCnt = deleteItemPymntPlans($PKeyID);
                if ($rowCnt > 0) {
                    echo "Line Deleted Successfully";
                } else {
                    echo "Failed to Delete Line";
                }
                exit();
            }
        } else if ($qstr == "UPDATE") {
            if ($actyp == 1) {
                //Items
                header("content-type:application/json");
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? (float) cleanInputData($_POST['sbmtdItmID']) : -1;
                $invItemNm = isset($_POST['invItemNm']) ? cleanInputData($_POST['invItemNm']) : '';
                $invItemDesc = isset($_POST['invItemDesc']) ? cleanInputData($_POST['invItemDesc']) : '';
                $invTmpltID = isset($_POST['invTmpltID']) ? (float) cleanInputData($_POST['invTmpltID']) : -1;
                $invItemType = isset($_POST['invItemType']) ? cleanInputData($_POST['invItemType']) : '';
                $invItemCtgryID = isset($_POST['invItemCtgryID']) ? (float) cleanInputData($_POST['invItemCtgryID']) : -1;
                $invTxCodeID = isset($_POST['invTxCodeID']) ? (float) cleanInputData($_POST['invTxCodeID']) : -1;
                $invBaseUomID = isset($_POST['invBaseUomID']) ? (float) cleanInputData($_POST['invBaseUomID']) : -1;
                $invDscntCodeID = isset($_POST['invDscntCodeID']) ? (float) cleanInputData($_POST['invDscntCodeID']) : -1;
                $invChrgCodeID = isset($_POST['invChrgCodeID']) ? (float) cleanInputData($_POST['invChrgCodeID']) : -1;
                $invMinItmQty = isset($_POST['invMinItmQty']) ? (float) cleanInputData($_POST['invMinItmQty']) : 0;
                $invMaxItmQty = isset($_POST['invMaxItmQty']) ? (float) cleanInputData($_POST['invMaxItmQty']) : 0;
                $invValCrncyID = isset($_POST['invValCrncyID']) ? (float) cleanInputData($_POST['invValCrncyID']) : -1;
                $invPriceLessTax = isset($_POST['invPriceLessTax']) ? (float) cleanInputData($_POST['invPriceLessTax']) : 0;
                $invSllngPrice = isset($_POST['invSllngPrice']) ? (float) cleanInputData($_POST['invSllngPrice']) : 0;
                $invNwPrftAmnt = isset($_POST['invNwPrftAmnt']) ? cleanInputData($_POST['invNwPrftAmnt']) : 0;
                $invNewSllngPrice = isset($_POST['invNewSllngPrice']) ? cleanInputData($_POST['invNewSllngPrice']) : 0;
                $invPrftMrgnPrcnt = isset($_POST['invPrftMrgnPrcnt']) ? cleanInputData($_POST['invPrftMrgnPrcnt']) : 0;
                $invPrftMrgnAmnt = isset($_POST['invPrftMrgnAmnt']) ? cleanInputData($_POST['invPrftMrgnAmnt']) : 0;
                $invAssetAcntID = isset($_POST['invAssetAcntID']) ? (float) cleanInputData($_POST['invAssetAcntID']) : -1;
                $invCogsAcntID = isset($_POST['invCogsAcntID']) ? (float) cleanInputData($_POST['invCogsAcntID']) : -1;
                $invSRvnuAcntID = isset($_POST['invSRvnuAcntID']) ? (float) cleanInputData($_POST['invSRvnuAcntID']) : -1;
                $invSRetrnAcntID = isset($_POST['invSRetrnAcntID']) ? (float) cleanInputData($_POST['invSRetrnAcntID']) : -1;
                $invPRetrnAcntID = isset($_POST['invPRetrnAcntID']) ? (float) cleanInputData($_POST['invPRetrnAcntID']) : -1;
                $invExpnsAcntID = isset($_POST['invExpnsAcntID']) ? (float) cleanInputData($_POST['invExpnsAcntID']) : -1;
                $invItmOthrDesc = isset($_POST['invItmOthrDesc']) ? cleanInputData($_POST['invItmOthrDesc']) : '';
                $invItmExtrInfo = isset($_POST['invItmExtrInfo']) ? cleanInputData($_POST['invItmExtrInfo']) : '';
                $invItmGnrcNm = isset($_POST['invItmGnrcNm']) ? cleanInputData($_POST['invItmGnrcNm']) : '';
                $invItmTradeNm = isset($_POST['invItmTradeNm']) ? cleanInputData($_POST['invItmTradeNm']) : '';
                $invItmUslDsge = isset($_POST['invItmUslDsge']) ? cleanInputData($_POST['invItmUslDsge']) : '';
                $invItmMaxDsge = isset($_POST['invItmMaxDsge']) ? cleanInputData($_POST['invItmMaxDsge']) : '';
                $invItmCntrIndctns = isset($_POST['invItmCntrIndctns']) ? cleanInputData($_POST['invItmCntrIndctns']) : '';
                $invItmFoodIntrctns = isset($_POST['invItmFoodIntrctns']) ? cleanInputData($_POST['invItmFoodIntrctns']) : '';

                $isItmEnbld = isset($_POST['isItmEnbld']) ? cleanInputData($_POST['isItmEnbld']) : 'NO';
                $isPlnngEnbld = isset($_POST['isPlnngEnbld']) ? cleanInputData($_POST['isPlnngEnbld']) : 'NO';
                $autoLoadInVMS = isset($_POST['autoLoadInVMS']) ? cleanInputData($_POST['autoLoadInVMS']) : 'NO';
                $oldItemID = getINVItmID($invItemNm);
                $isItmEnbldVal = ($isItmEnbld == "NO") ? "0" : "1";
                $isPlnngEnbldVal = ($isPlnngEnbld == "NO") ? "0" : "1";
                $autoLoadInVMSVal = ($autoLoadInVMS == "NO") ? "0" : "1";
                $errMsg = "";
                if ($invItemNm != "" && $invItemDesc != "" && ($oldItemID <= 0 || $oldItemID == $sbmtdItmID)) {
                    if ($sbmtdItmID <= 0) {
                        createINVItm(
                            $invItemNm,
                            $invItemDesc,
                            $invItemCtgryID,
                            $orgID,
                            $isItmEnbldVal,
                            $invSllngPrice,
                            $invCogsAcntID,
                            $invAssetAcntID,
                            $invSRvnuAcntID,
                            $invSRetrnAcntID,
                            $invPRetrnAcntID,
                            $invExpnsAcntID,
                            $invTxCodeID,
                            $invDscntCodeID,
                            $invChrgCodeID,
                            $invMinItmQty,
                            $invMaxItmQty,
                            $isPlnngEnbldVal,
                            $invItemType,
                            "",
                            $invItmExtrInfo,
                            $invItmOthrDesc,
                            $invBaseUomID,
                            $invTmpltID,
                            $invItmGnrcNm,
                            $invItmTradeNm,
                            $invItmUslDsge,
                            $invItmMaxDsge,
                            $invItmCntrIndctns,
                            $invItmFoodIntrctns,
                            $invPriceLessTax,
                            $invValCrncyID,
                            $autoLoadInVMSVal
                        );
                        $sbmtdItmID = getINVItmID($invItemNm);
                    } else {
                        updateINVItem(
                            $sbmtdItmID,
                            $invItemNm,
                            $invItemDesc,
                            $invItemCtgryID,
                            $orgID,
                            $isItmEnbldVal,
                            $invSllngPrice,
                            $invCogsAcntID,
                            $invAssetAcntID,
                            $invSRvnuAcntID,
                            $invSRetrnAcntID,
                            $invPRetrnAcntID,
                            $invExpnsAcntID,
                            $invTxCodeID,
                            $invDscntCodeID,
                            $invChrgCodeID,
                            $invMinItmQty,
                            $invMaxItmQty,
                            $isPlnngEnbldVal,
                            $invItemType,
                            "",
                            $invItmExtrInfo,
                            $invItmOthrDesc,
                            $invBaseUomID,
                            $invTmpltID,
                            $invItmGnrcNm,
                            $invItmTradeNm,
                            $invItmUslDsge,
                            $invItmMaxDsge,
                            $invItmCntrIndctns,
                            $invItmFoodIntrctns,
                            $invPriceLessTax,
                            $invValCrncyID,
                            $autoLoadInVMSVal
                        );
                    }
                    //Save Item Stores
                    $afftctd = 0;
                    $slctdItmStores = isset($_POST['slctdItmStores']) ? cleanInputData($_POST['slctdItmStores']) : '';
                    if (trim($slctdItmStores, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmStores, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 6) {
                                $lnStockID = (float) (cleanInputData1($crntRow[0]));
                                $lnStoreID = (float) cleanInputData1($crntRow[1]);
                                $lnShelves = cleanInputData1($crntRow[2]);
                                $lnShelveIDs = cleanInputData1($crntRow[3]);
                                $lnStrtDte = cleanInputData1($crntRow[4]);
                                $lnEndDte = cleanInputData1($crntRow[5]);
                                if ($lnStrtDte != "") {
                                    $lnStrtDte = cnvrtDMYTmToYMDTm($lnStrtDte);
                                } else {
                                    $lnStrtDte = getDB_Date_time();
                                }
                                if ($lnEndDte != "") {
                                    $lnEndDte = cnvrtDMYTmToYMDTm($lnEndDte);
                                }
                                $oldStockID = getINVItemStockID($sbmtdItmID, $lnStoreID);
                                if ($oldStockID <= 0 && $lnStoreID > 0) {
                                    //Insert
                                    $afftctd += createINVItemStore(
                                        $sbmtdItmID,
                                        $lnStoreID,
                                        $lnShelves,
                                        $orgID,
                                        $lnStrtDte,
                                        $lnEndDte,
                                        $lnShelveIDs
                                    );
                                } else if ($lnStockID > 0) {
                                    $afftctd += updateINVItemStore(
                                        $oldStockID,
                                        $sbmtdItmID,
                                        $lnStoreID,
                                        $lnShelves,
                                        $orgID,
                                        $lnStrtDte,
                                        $lnEndDte,
                                        $lnShelveIDs
                                    );
                                }
                            }
                        }
                    }
                    //Save Item UOMs
                    $afftctd1 = 0;
                    $slctdItmUOMs = isset($_POST['slctdItmUOMs']) ? cleanInputData($_POST['slctdItmUOMs']) : '';
                    if (trim($slctdItmUOMs, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmUOMs, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 6) {
                                $lnLineID = (float) (cleanInputData1($crntRow[0]));
                                $lnUOMID = (float) cleanInputData1($crntRow[1]);
                                $lnCnvrsnFctr = (float) cleanInputData1($crntRow[2]);
                                $lnSortOrder = (float) cleanInputData1($crntRow[3]);
                                $lnSllngPrice = (float) cleanInputData1($crntRow[4]);
                                $lnPrcLsTx = getPriceLessTx($invTxCodeID, $lnSllngPrice);
                                //(float) cleanInputData1($crntRow[5]);
                                //var_dump($crntRow);
                                $oldLnLineID = getINVItemUomID($sbmtdItmID, $lnUOMID);
                                if ($oldLnLineID <= 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                    //Insert
                                    $afftctd1 += createINVItemUom(
                                        $sbmtdItmID,
                                        $lnUOMID,
                                        $lnCnvrsnFctr,
                                        $lnSortOrder,
                                        $lnPrcLsTx,
                                        $lnSllngPrice
                                    );
                                } else if ($oldLnLineID > 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                    $afftctd1 += updateINVItemUom(
                                        $oldLnLineID,
                                        $sbmtdItmID,
                                        $lnUOMID,
                                        $lnCnvrsnFctr,
                                        $lnSortOrder,
                                        $lnPrcLsTx,
                                        $lnSllngPrice
                                    );
                                }
                            }
                        }
                    }
                    //Save Item Interactions
                    $afftctd2 = 0;
                    $slctdItmIntrctns = isset($_POST['slctdItmIntrctns']) ? cleanInputData($_POST['slctdItmIntrctns']) : '';
                    //echo $slctdItmIntrctns;
                    if (trim($slctdItmIntrctns, "|~") != "") {
                        $variousRows = explode("|", trim($slctdItmIntrctns, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            //var_dump($variousRows[$z]);
                            //echo "COUNT:".count($crntRow);
                            if (count($crntRow) == 4) {
                                $lnLineID = (float) (cleanInputData1($crntRow[0]));
                                $lnDrugID = (float) cleanInputData1($crntRow[1]);
                                $lnIntrctn = cleanInputData1($crntRow[2]);
                                $lnAction = cleanInputData1($crntRow[3]);
                                $oldLineID = getINVItemIntrctnID($sbmtdItmID, $lnDrugID);
                                if ($oldLineID <= 0 && $lnDrugID > 0) {
                                    //Insert
                                    $afftctd2 += createINVItemIntrctn($sbmtdItmID, $lnDrugID, $lnIntrctn, $lnAction);
                                } else if ($oldLineID > 0) {
                                    $afftctd2 += updateINVItemIntrctn($oldLineID, $sbmtdItmID, $lnDrugID, $lnIntrctn, $lnAction);
                                }
                            }
                        }
                    }
                    $nwImgLoc = "";
                    uploadDaImageItem($sbmtdItmID, $nwImgLoc);
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Item Successfully Saved!<br/>" . $afftctd . " Item Store(s) Saved!<br/>" . $afftctd1 . " Item UoM(s) Saved!<br/>" . $afftctd2 . " Interaction(s) Saved!<br/>" . $nwImgLoc;
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 101) {
                //Item Prices
                header("content-type:application/json");
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? (float) cleanInputData($_POST['sbmtdItmID']) : -1;
                $invItemNm = isset($_POST['invItemNm']) ? cleanInputData($_POST['invItemNm']) : '';
                $invItemDesc = isset($_POST['invItemDesc']) ? cleanInputData($_POST['invItemDesc']) : '';
                $invTxCodeID = isset($_POST['invTxCodeID']) ? (float) cleanInputData($_POST['invTxCodeID']) : -1;
                $invDscntCodeID = isset($_POST['invDscntCodeID']) ? (float) cleanInputData($_POST['invDscntCodeID']) : -1;
                $invChrgCodeID = isset($_POST['invChrgCodeID']) ? (float) cleanInputData($_POST['invChrgCodeID']) : -1;
                $invValCrncyID = isset($_POST['invValCrncyID']) ? (float) cleanInputData($_POST['invValCrncyID']) : -1;
                $invPriceLessTax = isset($_POST['invPriceLessTax']) ? (float) cleanInputData($_POST['invPriceLessTax']) : 0;
                $invSllngPrice = isset($_POST['invSllngPrice']) ? (float) cleanInputData($_POST['invSllngPrice']) : 0;
                $invNwPrftAmnt = isset($_POST['invNwPrftAmnt']) ? cleanInputData($_POST['invNwPrftAmnt']) : 0;
                $invNewSllngPrice = isset($_POST['invNewSllngPrice']) ? cleanInputData($_POST['invNewSllngPrice']) : 0;
                $invPrftMrgnPrcnt = isset($_POST['invPrftMrgnPrcnt']) ? cleanInputData($_POST['invPrftMrgnPrcnt']) : 0;
                $invPrftMrgnAmnt = isset($_POST['invPrftMrgnAmnt']) ? cleanInputData($_POST['invPrftMrgnAmnt']) : 0;
                $oldItemID = getINVItmID($invItemNm);
                $errMsg = "";
                if ($invItemNm != "" && $invItemDesc != "" && ($oldItemID <= 0 || $oldItemID == $sbmtdItmID)) {
                    if ($sbmtdItmID > 0) {
                        $affctd = updateSellingPrice($sbmtdItmID, $invSllngPrice, $invTxCodeID);
                    }
                    $nwImgLoc = "";
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmID;
                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>Item Price Successfully Saved!";
                    echo json_encode($arr_content);
                    exit();
                } else {
                    $arr_content['percent'] = 100;
                    $arr_content['itemid'] = (float) $sbmtdItmID;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Data Supplied is Incomplete or Invalid at some fields!</span>";
                    echo json_encode($arr_content);
                    exit();
                }
            } else if ($actyp == 102) {
                //Import Items
                $dataToSend = trim(cleanInputData($_POST['dataToSend']), "|~");
                session_write_close();

                //New Receipt Form
                $sbmtdScmCnsgnRcptID = -1;
                $scmCnsgnRcptType = "Miscellaneous Receipt";
                $scmCnsgnRcptSRC = "NORMAL";
                $sbmtdScmCnsgnRcptPOID = -1;
                $sbmtdScmCnsgnRcptITEMID = -1;
                $orgnlScmCnsgnRcptID = $sbmtdScmCnsgnRcptID;
                $scmCnsgnRcptDfltTrnsDte = $gnrlTrnsDteDMY;//HMS
                $scmCnsgnRcptCreator = $uName;
                $scmCnsgnRcptCreatorID = $usrID;
                $gnrtdTrnsNo = "";
                $scmCnsgnRcptDesc = $scmCnsgnRcptType . " - Bulk/Excel Upload of Items Quantities";

                $srcCnsgnRcptDocID = $sbmtdScmCnsgnRcptPOID;
                $srcCnsgnRcptDocTyp = "";
                $scmCnsgnRcptDocTmpltID = -1;
                $srcCnsgnRcptDocNum = "";

                $scmCnsgnRcptSpplr = "";
                $scmCnsgnRcptSpplrID = -1;
                $scmCnsgnRcptSpplrSite = "";
                $scmCnsgnRcptSpplrSiteID = -1;
                $scmCnsgnRcptSpplrClsfctn = "Supplier";
                $rqStatus = "Incomplete";
                $rqStatusNext = "Receive";
                $rqstatusColor = "red";

                $scmCnsgnRcptTtlAmnt = 0;
                $scmCnsgnRcptAppldAmnt = 0;
                $scmCnsgnRcptPayTerms = "";
                $scmCnsgnRcptPayMthd = "";
                $scmCnsgnRcptPayMthdID = -1;
                $scmCnsgnRcptPaidAmnt = 0;
                $scmCnsgnRcptGLBatch = "";
                $scmCnsgnRcptGLBatchID = -1;
                $scmCnsgnRcptSpplrInvcNum = "";
                $scmCnsgnRcptDocTmplt = "";
                $scmCnsgnRcptEvntRgstr = "";
                $scmCnsgnRcptEvntRgstrID = -1;
                $scmCnsgnRcptEvntCtgry = "";
                $scmCnsgnRcptEvntDocTyp = "";
                $scmCnsgnRcptDfltBalsAcnt = "";
                $scmCnsgnRcptInvcCurID = $fnccurid;
                $scmCnsgnRcptInvcCur = $fnccurnm;
                $scmCnsgnRcptIsPstd = "0";
                $scmCnsgnRcptAllwDues = "0";
                $scmCnsgnRcptAutoBals = "1";
                $scmCnsgnRcptExRate = 1;
                $otherModuleDocId = -1;
                $otherModuleDocTyp = "";
                $otherModuleDocNum = "";
                $sbmtdScmRcvblsInvcID = -1;
                $scmCnsgnRcptRcvblDocID = -1;
                $scmCnsgnRcptRcvblDoc = "";
                $scmCnsgnRcptRcvblDocType = "";
                $mkReadOnly = "";
                $mkRmrkReadOnly = "";
                $scmCnsgnRcptDfltBalsAcntID = get_DfltSplrPyblsCashAcnt($scmCnsgnRcptSpplrID, $orgID);
                if ($scmCnsgnRcptDfltBalsAcntID > 0) {
                    $usrTrnsCode = getGnrlRecNm("sec.sec_users", "user_id", "code_for_trns_nums", $usrID);
                    if ($usrTrnsCode == "") {
                        $usrTrnsCode = "XX";
                    }
                    $dte = date('ymd');
                    $docTypes = array("Purchase Order Receipt", "Miscellaneous Receipt");
                    $docTypPrfxs = array("PO-RCPT", "MISC-RCPT");
                    $docTypPrfx = $docTypPrfxs[findArryIdx($docTypes, $scmCnsgnRcptType)];
                    $gnrtdTrnsNo1 = $docTypPrfx . "-" . $usrTrnsCode . "-" . $dte . "-";
                    $gnrtdTrnsNo = $gnrtdTrnsNo1 . str_pad(((getRecCount_LstNum("inv.inv_consgmt_rcpt_hdr", "rcpt_number", "rcpt_id", $gnrtdTrnsNo1 . "%") + 1) . ""),
                        3,
                        '0',
                        STR_PAD_LEFT
                    );
                    $scmCnsgnRcptDfltBalsAcnt = getAccntNum($scmCnsgnRcptDfltBalsAcntID) . "." . getAccntName($scmCnsgnRcptDfltBalsAcntID);
                    $scmCnsgnRcptInvcCurID = (int) getGnrlRecNm("accb.accb_chart_of_accnts", "accnt_id", "crncy_id", $scmCnsgnRcptDfltBalsAcntID);
                    if ($scmCnsgnRcptInvcCurID > 0) {
                        $scmCnsgnRcptInvcCur = getPssblValNm($scmCnsgnRcptInvcCurID);
                    }
                    $funcExchRate = round(get_LtstExchRate($scmCnsgnRcptInvcCurID, $fnccurid, $scmCnsgnRcptDfltTrnsDte), 4);
                    $sbmtdScmCnsgnRcptID = createCnsgnRcpHdr($orgID, $gnrtdTrnsNo, $scmCnsgnRcptDesc, $scmCnsgnRcptDfltTrnsDte, $scmCnsgnRcptSpplrID, $scmCnsgnRcptSpplrSiteID, $rqStatus, $rqStatusNext, $srcCnsgnRcptDocID, $scmCnsgnRcptDfltBalsAcntID, $scmCnsgnRcptInvcCurID, $funcExchRate);
                }
                $affctd = 0;
                if ($dataToSend != "") {
                    $variousRowsBIG = explode("|", $dataToSend);
                    $totalBIG = count($variousRowsBIG);
                    for ($y = 0; $y < $totalBIG; $y++) {
                        //logSessionErrs($variousRowsBIG[$y] . ":Y=:" . $y . ":COUNT::" . count($variousRowsBIG));
                        /* DELETE FROM inv.inv_itm_list
                          WHERE item_id IN (SELECT tbl1.max_item_id from (select trim(item_code), count(item_id), max(item_id) max_item_id, min(item_id) FROM inv.inv_itm_list
                          group by trim(item_code)
                          having count(item_id) >1) tbl1) */
                        $crntRow = explode("~", $variousRowsBIG[$y]);
                        if (count($crntRow) == 25) {
                            $invItemNm = trim(cleanInputData1($crntRow[0]));
                            if (strpos($invItemNm, "'") === 0) {
                                $invItemNm = substr($invItemNm, 1);
                            }
                            $invItemDesc = trim((cleanInputData1($crntRow[1])));
                            $isItmEnbld = strtoupper(trim(cleanInputData1($crntRow[2])));
                            $invTmpltNm = trim(cleanInputData1($crntRow[3]));
                            $invItemType = trim(cleanInputData1($crntRow[4]));

                            $invItemCtgryNm = trim(cleanInputData1($crntRow[5]));
                            $invBaseUomNm = trim(cleanInputData1($crntRow[6]));
                            $invTxCodeNm = trim(cleanInputData1($crntRow[7]));
                            $invDscntCodeNm = trim(cleanInputData1($crntRow[8]));
                            $invChrgCodeNm = trim(cleanInputData1($crntRow[9]));
                            $invValCrncyNm = trim(cleanInputData1($crntRow[10]));
                            $invSllngPrice = trim(cleanInputData1($crntRow[11]));
                            $invItmQty = trim(cleanInputData1($crntRow[12]));
                            $invItmInitCost = trim(cleanInputData1($crntRow[13]));
                            $slctdItmStores = trim(cleanInputData1($crntRow[14]));
                            $slctdItmUOMs = trim(cleanInputData1($crntRow[15]));

                            $invItmExtrInfo = trim(cleanInputData1($crntRow[16]));
                            $invItmOthrDesc = trim(cleanInputData1($crntRow[17]));
                            $invItmGnrcNm = trim(cleanInputData1($crntRow[18]));
                            $invItmTradeNm = trim(cleanInputData1($crntRow[19]));
                            $invItmUslDsge = trim(cleanInputData1($crntRow[20]));
                            $invItmMaxDsge = trim(cleanInputData1($crntRow[21]));
                            $invItmCntrIndctns = trim(cleanInputData1($crntRow[22]));
                            $invItmFoodIntrctns = trim(cleanInputData1($crntRow[23]));
                            $slctdItmIntrctns = trim(cleanInputData1($crntRow[24]));

                            $invItemCtgryID = -1;
                            $invBaseUomID = -1;
                            $invDscntCodeID = -1;
                            $invChrgCodeID = -1;
                            $invTxCodeID = -1;
                            $invValCrncyID = -1;
                            $sbmtdItmID = -1;
                            $isItmEnbldVal = "0";
                            $invTmpltID = -1;
                            if ($y === 0) {
                                if (strtoupper($invItemNm) == strtoupper("Item Name**") && strtoupper($invItemDesc) == strtoupper("Item Description**") && strtoupper($invTmpltNm) == strtoupper("Item Template (For Accounts)**") && strtoupper($slctdItmIntrctns) == strtoupper("Drug Interactions(Drug Code~Interaction Effect~Action|)")) {
                                    continue;
                                } else {
                                    $arr_content['percent'] = 100;
                                    $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> Selected File is Invalid!";
                                    //.strtoupper($number) ."|". strtoupper($processName) ."|". strtoupper($isEnbld1 == "IS ENABLED?");
                                    $arr_content['msgcount'] = $total;
                                    file_put_contents(
                                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_iteminvimprt_progress.rho",
                                        json_encode($arr_content)
                                    );
                                    break;
                                }
                            } else {
                                $sbmtdItmID = getINVItmID($invItemNm);
                                $isItmEnbldVal = ($isItmEnbld == "NO") ? "0" : "1";
                                $invTmpltID = getInvTemplateID($invTmpltNm);
                                //logSessionErrs($invItemNm . ":invItemCtgryID::" . $invItemType);
                                $invItemCtgryID = getGnrlRecID("inv.inv_product_categories", "cat_name", "cat_id", $invItemCtgryNm, $orgID);
                                $invBaseUomID = getGnrlRecID("inv.unit_of_measure", "uom_name", "uom_id", $invBaseUomNm, $orgID);
                                //logSessionErrs($invItemCtgryID . ":invItemCtgryID::" . $invBaseUomID);
                                //logSessionErrs($invTxCodeNm);
                                $invTxCodeID = getTaxCodeID($invTxCodeNm, $orgID);
                                //logSessionErrs("DISCOUNT:".$invDscntCodeNm);
                                $invDscntCodeID = getTaxCodeID($invDscntCodeNm, $orgID);
                                $invChrgCodeID = getTaxCodeID($invChrgCodeNm, $orgID);
                                //logSessionErrs("CHARGE:".$invTxCodeID . ":" . $invChrgCodeID . "::" . $invDscntCodeID);
                                $invValCrncyID = getPssblValID($invValCrncyNm, getLovID("Currencies"));
                                $invSllngPrice = (float) $invSllngPrice;
                                $invItmQty = (float) $invItmQty;
                                $invItmInitCost = (float) $invItmInitCost;
                            }
                            $invAssetAcntID = -1;
                            $invCogsAcntID = -1;
                            $invSRvnuAcntID = -1;
                            $invSRetrnAcntID = -1;
                            $invPRetrnAcntID = -1;
                            $invExpnsAcntID = -1;
                            $invMinItmQty = 0;
                            $invMaxItmQty = 0;
                            $autoLoadInVMSVal = "0";
                            $isPlnngEnbldVal = "0";
                            $invPriceLessTax = 0;
                            $exitErrMsg = "";
                            //logSessionErrs($invItemNm . ":invItemType11::" . $invItemType);
                            $tmplRslts = getInvTemplateInf($invTmpltID);
                            if (
                                $invItemType != "Merchandise Inventory" &&
                                $invItemType != "Non-Merchandise Inventory" &&
                                $invItemType != "Fixed Assets" &&
                                $invItemType != "Expense Item" &&
                                $invItemType != "Services" &&
                                $invItemType != "VaultItem-Cash" &&
                                $invItemType != "VaultItem-NonCash"
                            ) {
                                $invItemType = "";
                            }
                            //logSessionErrs($invItemNm . ":invItemType22::" . $invItemType);
                            while ($tpltRw = loc_db_fetch_array($tmplRslts)) {
                                $invAssetAcntID = (int) $tpltRw[0];
                                $invCogsAcntID = (int) $tpltRw[1];
                                $invSRvnuAcntID = (int) $tpltRw[2];
                                $invSRetrnAcntID = (int) $tpltRw[3];
                                $invPRetrnAcntID = (int) $tpltRw[4];
                                $invExpnsAcntID = (int) $tpltRw[5];
                                $invTxCodeID = ($invTxCodeID <= 0) ? (int) $tpltRw[6] : $invTxCodeID;
                                $invDscntCodeID = ($invDscntCodeID <= 0) ? (int) $tpltRw[7] : $invDscntCodeID;
                                $invChrgCodeID = ($invChrgCodeID <= 0) ? (int) $tpltRw[8] : $invChrgCodeID;
                                $invItemType = (trim($invItemType) == "") ? $tpltRw[9] : $invItemType;
                                //$invBaseUomID = ($invBaseUomID <= 0) ? (int) $tpltRw[10] : $invBaseUomID;
                                $invValCrncyID = ($invValCrncyID <= 0) ? (int) $tpltRw[11] : $invValCrncyID;
                                $autoLoadInVMSVal = $tpltRw[12];
                                $isPlnngEnbldVal = $tpltRw[13];
                                $invMinItmQty = (float) $tpltRw[14];
                                $invMaxItmQty = (float) $tpltRw[15];
                                $invItemCtgryID = ($invItemCtgryID <= 0) ? (int) $tpltRw[16] : $invItemCtgryID;
                            }
                            //logSessionErrs($invItemNm . ":invItemType::" . $invItemType);
                            if ($invItemNm != "" && $invItemDesc != "" && $invItemType != "" && $invItemCtgryID > 0 && $invBaseUomID > 0 && $invAssetAcntID > 0 && $invExpnsAcntID > 0) {
                                if ($sbmtdItmID <= 0) {
                                    createINVItm(
                                        $invItemNm,
                                        $invItemDesc,
                                        $invItemCtgryID,
                                        $orgID,
                                        $isItmEnbldVal,
                                        $invSllngPrice,
                                        $invCogsAcntID,
                                        $invAssetAcntID,
                                        $invSRvnuAcntID,
                                        $invSRetrnAcntID,
                                        $invPRetrnAcntID,
                                        $invExpnsAcntID,
                                        $invTxCodeID,
                                        $invDscntCodeID,
                                        $invChrgCodeID,
                                        $invMinItmQty,
                                        $invMaxItmQty,
                                        $isPlnngEnbldVal,
                                        $invItemType,
                                        "",
                                        $invItmExtrInfo,
                                        $invItmOthrDesc,
                                        $invBaseUomID,
                                        $invTmpltID,
                                        $invItmGnrcNm,
                                        $invItmTradeNm,
                                        $invItmUslDsge,
                                        $invItmMaxDsge,
                                        $invItmCntrIndctns,
                                        $invItmFoodIntrctns,
                                        $invPriceLessTax,
                                        $invValCrncyID,
                                        $autoLoadInVMSVal
                                    );
                                    $sbmtdItmID = getINVItmID($invItemNm);
                                } else {
                                    updateINVItem(
                                        $sbmtdItmID,
                                        $invItemNm,
                                        $invItemDesc,
                                        $invItemCtgryID,
                                        $orgID,
                                        $isItmEnbldVal,
                                        $invSllngPrice,
                                        $invCogsAcntID,
                                        $invAssetAcntID,
                                        $invSRvnuAcntID,
                                        $invSRetrnAcntID,
                                        $invPRetrnAcntID,
                                        $invExpnsAcntID,
                                        $invTxCodeID,
                                        $invDscntCodeID,
                                        $invChrgCodeID,
                                        $invMinItmQty,
                                        $invMaxItmQty,
                                        $isPlnngEnbldVal,
                                        $invItemType,
                                        "",
                                        $invItmExtrInfo,
                                        $invItmOthrDesc,
                                        $invBaseUomID,
                                        $invTmpltID,
                                        $invItmGnrcNm,
                                        $invItmTradeNm,
                                        $invItmUslDsge,
                                        $invItmMaxDsge,
                                        $invItmCntrIndctns,
                                        $invItmFoodIntrctns,
                                        $invPriceLessTax,
                                        $invValCrncyID,
                                        $autoLoadInVMSVal
                                    );
                                }
                                //Save Item Stores
                                $afftctd = 0;
                                if (trim($slctdItmStores, "|~") != "") {
                                    $variousRows = explode("|", trim($slctdItmStores, "|"));
                                    for ($z = 0; $z < count($variousRows); $z++) {
                                        $crntRow = explode("~", $variousRows[$z]);
                                        if (count($crntRow) == 1) {
                                            $lnStockID = -1;
                                            $lnStoreID = getStoreID(trim(cleanInputData1($crntRow[0])));
                                            $lnShelves = "";
                                            $lnShelveIDs = "";
                                            $lnStrtDte = "";
                                            $lnEndDte = "";
                                            if ($lnStrtDte != "") {
                                                $lnStrtDte = cnvrtDMYTmToYMDTm($lnStrtDte);
                                            } else {
                                                $lnStrtDte = getDB_Date_time();
                                            }
                                            if ($lnEndDte != "") {
                                                $lnEndDte = cnvrtDMYTmToYMDTm($lnEndDte);
                                            } else {
                                                $lnEndDte = "4000-12-31 23:59:59";
                                            }
                                            $oldStockID = getINVItemStockID($sbmtdItmID, $lnStoreID);
                                            if ($oldStockID <= 0 && $lnStoreID > 0) {
                                                //Insert
                                                $afftctd += createINVItemStore(
                                                    $sbmtdItmID,
                                                    $lnStoreID,
                                                    $lnShelves,
                                                    $orgID,
                                                    $lnStrtDte,
                                                    $lnEndDte,
                                                    $lnShelveIDs
                                                );
                                                $oldStockID = getINVItemStockID($sbmtdItmID, $lnStoreID);
                                            }
                                            if ($z === 0 && $sbmtdScmCnsgnRcptID > 0 && $oldStockID > 0 && $invItmQty > 0) {
                                                $ln_TrnsLnID = -1;
                                                $ln_ItmID = $sbmtdItmID;
                                                $ln_StoreID = $lnStoreID;
                                                $ln_LineDesc = str_replace($invItemNm . ".", "", $invItemNm . "." . $invItemDesc);
                                                $ln_QTY = $invItmQty;
                                                $ln_UnitPrice = $invItmInitCost;
                                                $ln_PODocLnID = -1;
                                                $ln_ManDte = '';
                                                $ln_ExpryDte = '';
                                                $ln_TagNo = '';
                                                $ln_SerialNo = '';
                                                $ln_CnsgnCdtn = "Good";
                                                $ln_ExtraDesc = "Initial Receipt";
                                                /* preg_match_all("/\[[^\]]*\]/", $ln_ExtraDesc, $matches);
                                                  //var_dump($matches);
                                                  if (trim($ln_ExtraDesc) != "" && strpos($matches[0][0], "[CS No.:") !== FALSE) {
                                                  $ln_ExtraDesc = str_replace($matches[0][0], "", $ln_ExtraDesc);
                                                  } */
                                                $errMsg = "";
                                                if ($errMsg === "") {
                                                    if ($ln_TrnsLnID <= 0) {
                                                        $afftctd += createCnsgnRcptLine(
                                                            $ln_QTY,
                                                            $ln_UnitPrice,
                                                            $ln_ExpryDte,
                                                            $ln_ManDte,
                                                            $ln_TagNo,
                                                            $ln_SerialNo,
                                                            $ln_PODocLnID,
                                                            $ln_CnsgnCdtn,
                                                            $ln_ExtraDesc,
                                                            $ln_ItmID,
                                                            $ln_StoreID,
                                                            $sbmtdScmCnsgnRcptID
                                                        );
                                                    }
                                                } else {
                                                    $exitErrMsg .= $errMsg;
                                                }
                                            }
                                        }
                                    }
                                }
                                //Save Item UOMs
                                $afftctd1 = 0;
                                if (trim($slctdItmUOMs, "|~") != "") {
                                    $variousRows = explode("|", trim($slctdItmUOMs, "|"));
                                    for ($z = 0; $z < count($variousRows); $z++) {
                                        $crntRow = explode("~", $variousRows[$z]);
                                        if (count($crntRow) == 4) {
                                            $lnLineID = -1;
                                            $lnUOMID = getGnrlRecID("inv.unit_of_measure", "uom_name", "uom_id", trim(cleanInputData1($crntRow[0])), $orgID);

                                            $lnCnvrsnFctr = (float) trim(cleanInputData1($crntRow[1]));
                                            $lnSortOrder = (float) trim(cleanInputData1($crntRow[2]));
                                            $lnSllngPrice = (float) trim(cleanInputData1($crntRow[3]));
                                            $lnPrcLsTx = getPriceLessTx($invTxCodeID, $lnSllngPrice);
                                            //(float) cleanInputData1($crntRow[5]);
                                            //var_dump($crntRow);
                                            $oldLnLineID = getINVItemUomID($sbmtdItmID, $lnUOMID);
                                            if ($oldLnLineID <= 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                                //Insert
                                                $afftctd1 += createINVItemUom(
                                                    $sbmtdItmID,
                                                    $lnUOMID,
                                                    $lnCnvrsnFctr,
                                                    $lnSortOrder,
                                                    $lnPrcLsTx,
                                                    $lnSllngPrice
                                                );
                                            } else if ($oldLnLineID > 0 && $lnUOMID > 0 && $lnUOMID != $invBaseUomID) {
                                                $afftctd1 += updateINVItemUom(
                                                    $oldLnLineID,
                                                    $sbmtdItmID,
                                                    $lnUOMID,
                                                    $lnCnvrsnFctr,
                                                    $lnSortOrder,
                                                    $lnPrcLsTx,
                                                    $lnSllngPrice
                                                );
                                            }
                                        }
                                    }
                                }
                                //Save Item Interactions
                                $afftctd2 = 0;
                                //echo $slctdItmIntrctns;
                                if (trim($slctdItmIntrctns, "|~") != "") {
                                    $variousRows = explode("|", trim($slctdItmIntrctns, "|"));
                                    for ($z = 0; $z < count($variousRows); $z++) {
                                        $crntRow = explode("~", $variousRows[$z]);
                                        //var_dump($variousRows[$z]);
                                        //echo "COUNT:".count($crntRow);
                                        if (count($crntRow) == 3) {
                                            $lnLineID = -1;
                                            $lnDrugID = (float) getItmID(cleanInputData1($crntRow[0]), $orgID);
                                            $lnIntrctn = cleanInputData1($crntRow[1]);
                                            $lnAction = cleanInputData1($crntRow[2]);
                                            $oldLineID = getINVItemIntrctnID($sbmtdItmID, $lnDrugID);
                                            if ($oldLineID <= 0 && $lnDrugID > 0) {
                                                //Insert
                                                $afftctd2 += createINVItemIntrctn($sbmtdItmID, $lnDrugID, $lnIntrctn, $lnAction);
                                            } else if ($oldLineID > 0) {
                                                $afftctd2 += updateINVItemIntrctn($oldLineID, $sbmtdItmID, $lnDrugID, $lnIntrctn, $lnAction);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $percent = round((($y + 1) / $totalBIG) * 100, 2);
                        $arr_content['percent'] = $percent;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span> 100% Completed!..." . $affctd . " out of " . $totalBIG . " Inventory Item(s) imported.";
                            $arr_content['msgcount'] = $totalBIG;
                        } else {
                            $arr_content['message'] = "<i class=\"fa fa-spin fa-spinner\"></i> Importing Accounts...Please Wait..." . ($y + 1) . " out of " . $totalBIG . " Inventory Item(s) imported.";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_iteminvimprt_progress.rho",
                            json_encode($arr_content)
                        );
                    }
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    file_put_contents($ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_iteminvimprt_progress.rho", json_encode($arr_content));
                }
            } else if ($actyp == 103) {
                //Checked Importing Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_iteminvimprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => null, "message" => null));
                }
            } else if ($actyp == 3) {
                //Export Inventory Items
                $inptNum = isset($_POST['inptNum']) ? (int) cleanInputData($_POST['inptNum']) : 0;
                session_write_close();
                $affctd = 0;
                $errMsg = "Invalid Option!";
                if ($inptNum >= 0) {
                    $hdngs = array(
                        "Item Name**", "Item Description**", "Enabled?", "Item Template (For Accounts)**", "Item Type",
                        "Category**", "Base UOM**", "Tax Code", "Discount Code", "Charge Code", "Currency", "Selling Price", "Total Qty",
                        "Unit Cost Price", "Stores/Warehouses (Store Name|)", "UOMs(UOM~Conversion Factor~Sort Order~Selling Price|)",
                        "Extra Info", "Other Description", "Generic Name", "Trade Name", "Usual Dosage", "Maximum Dosage",
                        "Contraindications", "Food Interactions", "Drug Interactions(Drug Code~Interaction Effect~Action|)"
                    );
                    $limit_size = 0;
                    if ($inptNum > 2) {
                        $limit_size = $inptNum;
                    } else if ($inptNum == 2) {
                        $limit_size = 1000000;
                    }
                    $rndm = getRandomNum(10001, 9999999);
                    $dteNm = date('dMY_His');
                    $nwFileNm = $fldrPrfx . "dwnlds/tmp/InvItemsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $dwnldUrl = $app_url . "dwnlds/tmp/InvItemsExprt_" . $dteNm . "_" . $rndm . ".csv";
                    $opndfile = fopen($nwFileNm, "w");
                    fputcsv($opndfile, $hdngs);
                    if ($limit_size <= 0) {
                        $arr_content['percent'] = 100;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!... Inventory Items Template Exported.</span>";
                        $arr_content['msgcount'] = 0;
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_InvItems_exprt_progress.rho",
                            json_encode($arr_content)
                        );
                        fclose($opndfile);
                        exit();
                    }
                    $z = 0;
                    $crntRw = "";
                    $result = get_INVItemsToExport($orgID, $limit_size);
                    $total = loc_db_num_rows($result);
                    $fieldCntr = loc_db_num_fields($result);
                    while ($row = loc_db_fetch_array($result)) {
                        //"" . ($z + 1), 
                        $crntRw = array(
                            "'" . $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6],
                            $row[7], $row[8], $row[9], $row[10], $row[11],
                            $row[12], $row[13], $row[14], $row[15], $row[16], $row[17], $row[18], $row[19], $row[20], $row[21],
                            $row[22], $row[23], $row[24]
                        );
                        fputcsv($opndfile, $crntRw);
                        //file_put_contents($nwFileNm, $crntRw, FILE_APPEND | LOCK_EX);
                        $percent = round((($z + 1) / $total) * 100, 2);
                        $arr_content['percent'] = $percent;
                        $arr_content['dwnld_url'] = $dwnldUrl;
                        if ($percent >= 100) {
                            $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span><span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"> 100% Completed!..." . ($z +
                                1) . " out of " . $total . " Inventory Item(s) exported.</span>";
                            $arr_content['msgcount'] = $total;
                        } else {
                            $arr_content['message'] = "<span style=\"color:blue;font-size:12px;text-align: center;margin-top:0px;\"><br/>Exporting Inventory Items...Please Wait..." . ($z +
                                1) . " out of " . $total . " Inventory Item(s) exported.</span>";
                        }
                        file_put_contents(
                            $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_InvItems_exprt_progress.rho",
                            json_encode($arr_content)
                        );
                        $z++;
                    }
                    fclose($opndfile);
                } else {
                    $percent = 100;
                    $arr_content['percent'] = $percent;
                    $arr_content['message'] = "<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i> 100% Completed...An Error Occured!<br/>$errMsg</span>";
                    $arr_content['msgcount'] = "";
                    $arr_content['dwnld_url'] = "";
                    file_put_contents(
                        $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_InvItems_exprt_progress.rho",
                        json_encode($arr_content)
                    );
                }
            } else if ($actyp == 4) {
                //Checked Exporting Process Status                
                header('Content-Type: application/json');
                $file = $ftp_base_db_fldr . "/bin/log_files/$lgn_num" . "_InvItems_exprt_progress.rho";
                if (file_exists($file)) {
                    $text = file_get_contents($file);
                    echo $text;

                    $obj = json_decode($text);
                    if ($obj->percent >= 100) {
                        //$rs = file_exists($file) ? unlink($file) : TRUE;
                    }
                } else {
                    echo json_encode(array("percent" => 0, "message" => '<span style=\"color:red;\"><i class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></i>Not Started</span>'));
                }
            } else if ($actyp == 500) {
                //var_dump($_POST);
                $sbmtdItmPymntPlansITEMID = isset($_POST['sbmtdItmPymntPlansITEMID']) ? cleanInputData($_POST['sbmtdItmPymntPlansITEMID']) : -1;
                $slctdItmPymntPlans = isset($_POST['slctdItmPymntPlans']) ? cleanInputData($_POST['slctdItmPymntPlans']) : "";

                $vldtyUpdtCnt = 0;
                $rsltCrtCnt = 0;
                $rsltUpdtCnt = 0;

                //$dateStr = getDB_Date_time();

                if ($sbmtdItmPymntPlansITEMID > 0) {

                    //CREATE PROCESSING FEES
                    $dateStr = getDB_Date_time();
                    $recCntInst = 0;
                    $recCntUpdt = 0;

                    if (trim($slctdItmPymntPlans, "|~") != "") {

                        $variousRows = explode("|", trim($slctdItmPymntPlans, "|"));
                        for ($z = 0; $z < count($variousRows); $z++) {
                            $crntRow = explode("~", $variousRows[$z]);
                            if (count($crntRow) == 7) {
                                $itmPymntPlansID = (int) (cleanInputData1($crntRow[0]));
                                $itmID = (int) cleanInputData1($crntRow[1]);
                                $planName = cleanInputData1($crntRow[2]);
                                $planPrice = (float) cleanInputData1($crntRow[3]);
                                $noOfPymnts = (float) cleanInputData1($crntRow[4]);
                                $initDpst = (float) cleanInputData1($crntRow[5]);
                                $isEnbld = cleanInputData1($crntRow[6]);



                                if ($itmPymntPlansID > 0) {
                                    $recCntUpdt = $recCntUpdt + updateItemPymntPlans($itmPymntPlansID, $sbmtdItmPymntPlansITEMID, $planName, $noOfPymnts, $planPrice, $initDpst, $isEnbld, $usrID, $dateStr);
                                } else {
                                    $itmPymntPlansID = getItemPymntPlansID();
                                    $recCntInst = $recCntInst + insertItemPymntPlans($itmPymntPlansID, $sbmtdItmPymntPlansITEMID, $planName, $noOfPymnts, $planPrice, $initDpst, $isEnbld, $usrID, $dateStr);
                                }
                            }
                        }
                    }

                    //echo "<span style='color:green;font-weight:bold !important;'>Credit Product Saved</br><i>$recCntInst Fee Record(s) inserted</br>$recCntUpdt Fee record(s) updated</i></span>";
                    echo json_encode(array("sbmtdItmPymntPlansITEMID" => $sbmtdItmPymntPlansITEMID, "recCntInst" => $recCntInst, "recCntUpdt" => $recCntUpdt));
                    exit();
                } else {
                    echo '<div><img src="cmn_images/error.gif" style="float:left;margin-right:5px;width:30px;height:30px;"/>'
                        . '<br/>Please complete all required fields before proceeding!<br/></div>';
                    exit();
                }
            }
        } else {
            if ($vwtyp == 0) {
                echo $cntent . "<li onclick=\"openATab('#allmodules', 'grp=$group&typ=$type&pg=$pgNo&vtyp=0');\">
                                    <span class=\"divider\"><i class=\"fa fa-angle-right\" aria-hidden=\"true\"></i></span>
                                    <span style=\"text-decoration:none;\">Item List</span>
				</li>
                               </ul>
                              </div>";
                //Stockable Item List
                $total = get_INVItemsTtl($srchFor, $srchIn);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }

                $curIdx = $pageNo - 1;
                $result = get_INVItems($srchFor, $srchIn, $curIdx, $lmtSze, $sortBy);
                $cntr = 0;
                $colClassType1 = "col-lg-2";
                $colClassType2 = "col-lg-4";
?>
                <form id='allINVItmsForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row rhoRowMargin">
                        <div class="<?php echo $colClassType2; ?>" style="padding:0px 15px 0px 15px !important;">
                            <div class="input-group">
                                <input class="form-control" id="allINVItmsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                ?>" onkeyup="enterKeyFuncAllINVItms(event, '', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                <input id="allINVItmsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllINVItms('clear', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </label>
                                <label class="btn btn-primary btn-file input-group-addon" onclick="getAllINVItms('', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <span class="glyphicon glyphicon-search"></span>
                                </label>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType2; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allINVItmsSrchIn">
                                    <?php
                                    $valslctdArry = array("", "", "");
                                    $srchInsArrys = array("Name/Description", "Category", "Type");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allINVItmsDsplySze" style="min-width:70px !important;">
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                    ?>
                                        <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-sort-by-attributes"></span></span>
                                <select data-placeholder="Select..." class="form-control chosen-select" id="allINVItmsSortBy">
                                    <?php
                                    $valslctdArry = array("", "");
                                    $srchInsArrys = array("Last Created", "Value");
                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($sortBy == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                    ?>
                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="<?php echo $colClassType1; ?>">
                            <nav aria-label="Page navigation">
                                <ul class="pagination" style="margin: 0px !important;">
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllINVItms('previous', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="rhopagination" href="javascript:getAllINVItms('next', '#allmodules', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row " style="margin-bottom:2px;padding:2px 15px 2px 15px !important;">
                        <div class="col-md-12" style="padding:2px 1px 2px 1px !important;border-top:1px solid #ddd;border-bottom:1px solid #ddd;">
                            <?php if ($canAdd === true) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="getOneINVItmsForm(-1, 'ShowDialog');">
                                    <img src="cmn_images/add1-64.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    New Store Item
                                </button>
                            <?php } ?>
                            <button type="button" class="btn btn-default btn-sm" onclick="exprtInvntryItems();">
                                <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                Export Store Items
                            </button>
                            <?php if ($canAdd) { ?>
                                <button type="button" class="btn btn-default btn-sm" onclick="importInvntryItems();">
                                    <img src="cmn_images/image007.png" style="left: 0.5%; padding-right: 5px; height:20px; width:auto; position: relative; vertical-align: middle;">
                                    Import Store Items
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-responsive" id="allINVItmsTable" cellspacing="0" width="100%" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>&nbsp;</th>
                                        <th>Item Code/Name</th>
                                        <th>Type / Category / Description</th>
                                        <th>Qty</th>
                                        <th>UOM</th>
                                        <th>CUR.</th>
                                        <th>Unit Value</th>
                                        <th>Total Value</th>
                                        <th style="text-align:center;">Enabled?</th>
                                        <th>CS</th>
                                        <th>SP</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <?php if ($canVwRcHstry === true) { ?>
                                            <th>...</th>
                                        <?php } ?>
                                        <th>PP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = loc_db_fetch_array($result)) {
                                        $cntr += 1;
                                    ?>
                                        <tr id="allINVItmsRow_<?php echo $cntr; ?>">
                                            <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Details" onclick="getOneINVItmsForm(<?php echo $row[0]; ?>, 'ShowDialog');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/edit32.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <?php
                                                echo str_replace(" (" . $row[1] . ")", "", $row[1] . " (" . $row[2] . ")");
                                                ?>
                                                <input type="hidden" class="form-control" aria-label="..." id="allINVItmsRow<?php echo $cntr; ?>_ItemID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allINVItmsRow<?php echo $cntr; ?>_ItmID" value="<?php echo $row[0]; ?>">
                                                <input type="hidden" class="form-control" aria-label="..." id="allINVItmsRow<?php echo $cntr; ?>_StoreID" value="<?php echo -1; ?>">
                                            </td>
                                            <td class="lovtd"><?php echo "<span style=\"font-weight:bold;\">[" . $row[18] . "]</span> - " . $row[31] . " (" . $row[2] . ")"; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo number_format((float) $row[19], 2); ?></td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;color:black;font-weight:bold;" onclick="getGnrlScmUOMBrkdwnForm(-1, 3, <?php echo $row[0]; ?>, <?php echo $row[19]; ?>, '<?php echo $row[33]; ?>');" data-toggle="tooltip" data-placement="bottom" title="View QTY Breakdown"><?php echo $row[23]; ?></button>
                                            </td>
                                            <td class="lovtd"><?php echo $row[33]; ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;"><?php echo number_format((float) $row[30], 2); ?></td>
                                            <td class="lovtd" style="text-align:right;font-weight:bold;color:blue;"><?php
                                                                                                                    echo number_format((float) $row[19] * (float) $row[30], 2);
                                                                                                                    ?></td>
                                            <td class="lovtd" style="text-align:center;">
                                                <?php
                                                $isChkd = "";
                                                if ($row[13] == "1") {
                                                    $isChkd = "checked=\"true\"";
                                                }
                                                ?>
                                                <div class="form-group form-group-sm">
                                                    <div class="form-check" style="font-size: 12px !important;">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" id="allINVItmsRow<?php echo $cntr; ?>_IsEnabled" name="allINVItmsRow<?php echo $cntr; ?>_IsEnabled" <?php echo $isChkd ?> disabled="true">
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="lovtd" style="text-align: center;">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Item Consignments" onclick="getScmSalesInvcItems('allINVItmsRow_<?php echo $cntr; ?>', 'ShowDialog', '', 'true', function () {
                                                                                        var a = 1;
                                                                                    });" style="padding:2px !important;">
                                                    <img src="cmn_images/chcklst3.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd" style="text-align: center;">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View/Edit Item's New Selling Price" onclick="getOneINVItmPricesForm('allINVItmsRow_<?php echo $cntr; ?>', 'ShowDialog');" style="padding:2px !important;">
                                                    <img src="cmn_images/payment_256.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Quick Receipt" onclick="getOneScmCnsgnRcptForm(-1, 1, 'ShowDialog', 'Miscellaneous Receipt', 'QUICK_RCPT',<?php echo $row[0]; ?>);" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/assets1.jpg" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                            <?php if ($canDel === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delINVItms('allINVItmsRow_<?php echo $cntr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item">
                                                        <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <?php if ($canVwRcHstry === true) { ?>
                                                <td class="lovtd">
                                                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                            echo urlencode(encrypt1(($row[0] . "|inv.inv_itm_list|item_id"),
                                                                                                                                                                                                                $smplTokenWord1
                                                                                                                                                                                                            ));
                                                                                                                                                                                                            ?>');" style="padding:2px !important;">
                                                        <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                    </button>
                                                </td>
                                            <?php } ?>
                                            <td class="lovtd">
                                                <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Payment Plans" onclick="getOneItmPymntPlansForm(-1, 500, 'ShowDialog', <?php echo $row[0]; ?>, '<?php echo addslashes($row[1]); ?>');" style="padding:2px !important;" style="padding:2px !important;">
                                                    <!--<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>-->
                                                    <img src="cmn_images/dscnt_456356.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 1) {
                //New Store Item Form
                //$canEdt = test_prmssns($dfltPrvldgs[31], $mdlNm) || test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? cleanInputData($_POST['sbmtdItmID']) : -1;

                $invItemNm = "";
                $invItemDesc = "";

                $invTmpltNm = "";
                $invTmpltID = -1;
                $invItemType = "";
                $invItemCtgry = "";
                $invItemCtgryID = -1;
                $invBaseUom = "";
                $invBaseUomID = -1;

                $invTxCodeID = -1;
                $invTxCodeName = "";
                $invDscntCodeID = -1;
                $invDscntCodeName = "";
                $invChrgCodeID = -1;
                $invChrgCodeName = "";
                $isPlnngEnbld = "No";
                $autoLoadInVMS = "Yes";
                $isEnbld = "No";
                $invMinItmQty = 0;
                $invMaxItmQty = 0;

                $invValCrncyID = -1;
                $invValCrncyNm = "";
                $invPriceLessTax = 0;
                $invSllngPrice = 0;

                $invNwPrftAmnt = 0;
                $invLtstCostPrice = 0;
                $invNewSllngPrice = 0;
                $invPrftMrgnPrcnt = 0;
                $invPrftMrgnAmnt = 0;

                $invAssetAcntID = -1;
                $invAssetAcntNm = "";
                $invCogsAcntID = -1;
                $invCogsAcntNm = "";
                $invSRvnuAcntID = -1;

                $invSRvnuAcntNm = "";
                $invSRetrnAcntID = -1;
                $invSRetrnAcntNm = "";
                $invPRetrnAcntID = -1;
                $invPRetrnAcntNm = "";
                $invExpnsAcntID = -1;
                $invExpnsAcntNm = "";

                $rsltAc = get_One_DfltAcnt($orgID);
                if ($rwAc = loc_db_fetch_array($rsltAc)) {
                    $invAssetAcntID = (int) $rwAc[1];
                    $invAssetAcntNm = getAccntNum($invAssetAcntID) . "." . getAccntName($invAssetAcntID);
                    $invCogsAcntID = (int) $rwAc[2];
                    $invCogsAcntNm = getAccntNum($invCogsAcntID) . "." . getAccntName($invCogsAcntID);
                    $invSRvnuAcntID = (int) $rwAc[5];

                    $invSRvnuAcntNm = getAccntNum($invSRvnuAcntID) . "." . getAccntName($invSRvnuAcntID);
                    $invSRetrnAcntID = (int) $rwAc[6];
                    $invSRetrnAcntNm = getAccntNum($invSRetrnAcntID) . "." . getAccntName($invSRetrnAcntID);
                    $invPRetrnAcntID = (int) $rwAc[4];
                    $invPRetrnAcntNm = getAccntNum($invPRetrnAcntID) . "." . getAccntName($invPRetrnAcntID);
                    $invExpnsAcntID = (int) $rwAc[3];
                    $invExpnsAcntNm = getAccntNum($invExpnsAcntID) . "." . getAccntName($invExpnsAcntID);
                }

                $invItmExtrInfo = "";
                $invItmOthrDesc = "";

                $invItmGnrcNm = "";
                $invItmTradeNm = "";
                $invItmUslDsge = "";
                $invItmMaxDsge = "";
                $invItmCntrIndctns = "";
                $invItmFoodIntrctns = "";
                $nwFileName = "";
                $oldFileNm = "";
                $extension = "png";
                if ($sbmtdItmID > 0) {
                    $result = get_OneINVItems($sbmtdItmID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdItmID = (float) $row[0];
                        $invItemNm = $row[1];
                        $invItemDesc = $row[2];

                        $invTmpltNm = $row[4];
                        $invTmpltID = (float) $row[3];
                        $invItemType = $row[5];
                        $invItemCtgry = $row[7];
                        $invItemCtgryID = (float) $row[6];
                        $invBaseUom = $row[9];
                        $invBaseUomID = (float) $row[8];

                        $invTxCodeID = (float) $row[10];
                        $invTxCodeName = $row[11];
                        $invDscntCodeID = (float) $row[12];
                        $invDscntCodeName = $row[13];
                        $invChrgCodeID = (float) $row[14];
                        $invChrgCodeName = $row[15];
                        $isPlnngEnbld = ($row[16] == "1") ? "Yes" : "No";
                        $autoLoadInVMS = ($row[17] == "1") ? "Yes" : "No";
                        $isEnbld = ($row[18] == "1") ? "Yes" : "No";
                        $invMinItmQty = (float) $row[19];
                        $invMaxItmQty = (float) $row[20];

                        $invValCrncyID = (float) $row[21];
                        $invValCrncyNm = $row[22];
                        $invSllngPrice = (float) $row[24];
                        $invPriceLessTax = (float) $row[23];
                        if ($invPriceLessTax == 0) {
                            $invPriceLessTax = $invSllngPrice;
                        }
                        $invLtstCostPrice = (float) $row[47]; // getHgstUnitCostPrice($sbmtdItmID);

                        $invNwPrftAmnt = round(round($invPriceLessTax, 2) - $invLtstCostPrice, 2);
                        $invNewSllngPrice = 0;
                        $invPrftMrgnPrcnt = 0;
                        if (round($invPriceLessTax, 2) != 0) {
                            $invPrftMrgnPrcnt = round(($invNwPrftAmnt / round($invPriceLessTax, 2)) * 100, 2);
                        }
                        $invPrftMrgnAmnt = 0;

                        $invAssetAcntID = (float) $row[25];
                        $invAssetAcntNm = $row[26];
                        $invCogsAcntID = (float) $row[27];
                        $invCogsAcntNm = $row[28];
                        $invSRvnuAcntID = (float) $row[29];

                        $invSRvnuAcntNm = $row[30];
                        $invSRetrnAcntID = (float) $row[31];
                        $invSRetrnAcntNm = $row[32];
                        $invPRetrnAcntID = (float) $row[33];
                        $invPRetrnAcntNm = $row[34];
                        $invExpnsAcntID = (float) $row[35];
                        $invExpnsAcntNm = $row[36];
                        $invItmExtrInfo = $row[37];
                        $invItmOthrDesc = $row[38];

                        $invItmGnrcNm = $row[39];
                        $invItmTradeNm = $row[40];
                        $invItmUslDsge = $row[41];
                        $invItmMaxDsge = $row[42];
                        $invItmCntrIndctns = $row[43];
                        $invItmFoodIntrctns = $row[44];

                        $temp = explode(".", $row[46]);
                        $oldFileNm = $temp[0];
                        $extension = end($temp);
                        if ($extension == "") {
                            $extension = "png";
                        }
                        $nwFileName = encrypt1($row[46], $smplTokenWord1) . "." . $extension;
                    }
                }
                $ftp_src = $ftp_base_db_fldr . "/Inv/" . $sbmtdItmID . "." . $extension;
                $fullPemDest = $fldrPrfx . $tmpDest . $nwFileName;
                if (file_exists($ftp_src)) {
                    copy("$ftp_src", "$fullPemDest");
                } else if (!file_exists($fullPemDest)) {
                    $ftp_src = $fldrPrfx . 'cmn_images/actions_document_preview.png';
                    copy("$ftp_src", "$fullPemDest");
                }
                $nwFileName = $tmpDest . $nwFileName;
            ?>
                <form class="form-horizontal" id='storeItmStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row">
                        <div class="row" style="padding: 0px 15px 0px 15px !important;">
                            <div class="col-md-6" style="padding: 0px 5px 0px 5px !important;">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="invItemNm" class="control-label">Item Name:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <input type="text" name="invItemNm" id="invItemNm" class="form-control rqrdFld" value="<?php echo $invItemNm; ?>" style="width:100% !important;">
                                                <input type="hidden" name="sbmtdItmID" id="sbmtdItmID" class="form-control" value="<?php echo $sbmtdItmID; ?>">
                                            <?php } else { ?>
                                                <span><?php echo $invItemNm; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <label for="invItemDesc" class="control-label">Item Description:</label>
                                        </div>
                                        <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                            <?php if ($canEdt === true) { ?>
                                                <textarea rows="5" name="invItemDesc" id="invItemDesc" class="form-control rqrdFld"><?php echo $invItemDesc; ?></textarea>
                                            <?php } else { ?>
                                                <span><?php echo $invItemDesc; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                <label for="isItmEnbld" class="control-label">
                                                    <?php
                                                    $isChkd = "";
                                                    $isRdOnly = "disabled=\"true\"";
                                                    if ($canEdt === true) {
                                                        $isRdOnly = "";
                                                    }
                                                    if ($isEnbld == "Yes") {
                                                        $isChkd = "checked=\"true\"";
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="isItmEnbld" id="isItmEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Enabled?</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                            &nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding: 1px !important;">
                                <div class="col-md-8">
                                    <fieldset class="basic_person_fs1" style="min-height: 50px !important;">
                                        <!--<legend class="basic_person_lg">Item's Picture</legend>-->
                                        <div style="margin-bottom: 5px;margin-top:5px !important;">
                                            <img src="<?php echo $nwFileName; ?>" alt="..." id="img1ItmTest" class="img-rounded center-block img-responsive" style="height: 150px !important; width: auto !important;">
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-4">
                                    <?php if ($canEdt === true) { ?>
                                        <div class="form-group form-group-sm" style="margin-bottom: 10px;margin-top:10px !important;">
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <label class="btn btn-primary btn-file" style="width:100% !important;min-width: 100% !important;">
                                                    Browse... <input type="file" id="daItemPicture" name="daItemPicture" onchange="changeImgSrc(this, '#img1ItmTest', '#img1ItmSrcLoc');" class="btn btn-default" style="display: none;">
                                                </label>
                                            </div>
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <input type="text" class="form-control" aria-label="..." id="img1ItmSrcLoc" value="">
                                            </div>
                                            <div class="col-md-12" style="padding:1px !important;">
                                                <button type="button" class="btn btn-default" data-dismiss="modal" style="width:100% !important;">Close</button>
                                                <?php if ($canEdt === true) { ?>
                                                    <button type="button" class="btn btn-success" onclick="saveINVItmsForm();" style="width:100% !important;">Save Changes</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="padding:1px 15px 1px 15px !important;">
                            <hr style="margin:3px 0px 3px 0px;">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" style="margin-top:1px !important;">
                                    <li class="active"><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsGnrl" id="invItmsGnrltab" style="padding: 3px 10px !important;">General</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsGl" id="invItmsGltab" style="padding: 3px 10px !important;">GL Accounts</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsStores" id="invItmsStorestab" style="padding: 3px 10px !important;">Stores/WH</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsExtInfo" id="invItmsExtInfotab" style="padding: 3px 10px !important;">Extra Info.</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsUOM" id="invItmsUOMtab" style="padding: 3px 10px !important;">UOMs</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsDrugLbl" id="invItmsDrugLbltab" style="padding: 3px 10px !important;">Drug Labels</a></li>
                                    <li><a data-toggle="tabajxinvitms" data-rhodata="" href="#invItmsDrugIntrctns" id="invItmsDrugIntrctnstab" style="padding: 3px 10px !important;">Drug Interactions</a></li>
                                </ul>
                                <div class="custDiv" style="padding:0px !important;min-height: 40px !important;" id="oneInvItmDetTblSctn">
                                    <div class="tab-content" style="padding:5px !important;padding-top:7px !important;">
                                        <div id="invItmsGnrl" class="tab-pane fadein active" style="border:none !important;padding:0px !important;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invTmpltNm" class="control-label">Apply Template:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group">
                                                                        <input type="text" name="invTmpltNm" id="invTmpltNm" class="form-control" value="<?php echo $invTmpltNm; ?>" readonly="true">
                                                                        <input type="hidden" name="invTmpltID" id="invTmpltID" class="form-control" value="<?php echo $invTmpltID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Item Templates', '', '', '', 'radio', true, '', 'invTmpltID', 'invTmpltNm', 'clear', 0, '', function () {
                                                                                                        var aa112 = 1;
                                                                                                    });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invTmpltNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invItemType" class="control-label">Item Type:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <select class="form-control rqrdFld" id="invItemType" onchange="">
                                                                        <?php
                                                                        $valslctdArry = array("", "", "", "", "", "", "");
                                                                        $valuesArrys = array(
                                                                            "Merchandise Inventory", "Non-Merchandise Inventory",
                                                                            "Fixed Assets", "Expense Item", "Services", "VaultItem-Cash",
                                                                            "VaultItem-NonCash"
                                                                        );
                                                                        for ($z = 0; $z < count($valuesArrys); $z++) {
                                                                            if ($invItemType == $valuesArrys[$z]) {
                                                                                $valslctdArry[$z] = "selected";
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $valuesArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $valuesArrys[$z]; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invItemType; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invItemCtgry" class="control-label">Category:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control rqrdFld" aria-label="..." id="invItemCtgry" value="<?php echo $invItemCtgry; ?>" readonly="">
                                                                        <input type="hidden" id="invItemCtgryID" value="<?php echo $invItemCtgryID; ?>">
                                                                        <label id="invItemCtgryLbl" class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Categories', '', '', '', 'radio', true, '', 'invItemCtgryID', 'invItemCtgry', 'clear', 1, '');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invItemCtgry; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invBaseUom" class="control-label">Base UOM:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group">
                                                                        <input type="text" name="invBaseUom" id="invBaseUom" class="form-control rqrdFld" value="<?php echo $invBaseUom; ?>" readonly="true">
                                                                        <input type="hidden" name="invBaseUomID" id="invBaseUomID" class="form-control" value="<?php echo $invBaseUomID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', 'allOtherInputOrgID', '', '', 'radio', true, '', 'invBaseUomID', 'invBaseUom', 'clear', 0, '', function () {
                                                                                                        var aa112 = 1;
                                                                                                    });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invBaseUom; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invTxCodeName" class="control-label">Tax Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invTxCodeName" id="invTxCodeName" class="form-control" value="<?php echo $invTxCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invTxCodeID" id="invTxCodeID" class="form-control" value="<?php echo $invTxCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', '', '', '', 'radio', true, '', 'invTxCodeID', 'invTxCodeName', 'clear', 0, '', function () {
                                                                                                        var aa112 = 1;
                                                                                                    });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invTxCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invDscntCodeName" class="control-label">Discount Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invDscntCodeName" id="invDscntCodeName" class="form-control" value="<?php echo $invDscntCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invDscntCodeID" id="invDscntCodeID" class="form-control" value="<?php echo $invDscntCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Discount Codes', '', '', '', 'radio', true, '', 'invDscntCodeID', 'invDscntCodeName', 'clear', 0, '', function () {
                                                                                                        var aa112 = 1;
                                                                                                    });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invDscntCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invChrgCodeName" class="control-label">Charge Code:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdt === true) { ?>
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invChrgCodeName" id="invChrgCodeName" class="form-control" value="<?php echo $invChrgCodeName; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invChrgCodeID" id="invChrgCodeID" class="form-control" value="<?php echo $invChrgCodeID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Extra Charges', '', '', '', 'radio', true, '', 'invChrgCodeID', 'invChrgCodeName', 'clear', 0, '', function () {
                                                                                                        var aa112 = 1;
                                                                                                    });">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invChrgCodeName; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                &nbsp;
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                                    <label for="isPlnngEnbld" class="control-label">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        $isRdOnly = "disabled=\"true\"";
                                                                        if ($canEdt === true) {
                                                                            $isRdOnly = "";
                                                                        }
                                                                        if ($isPlnngEnbld == "Yes") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" name="isPlnngEnbld" id="isPlnngEnbld" <?php echo $isChkd . " " . $isRdOnly; ?>>Is Planning Enabled?</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <div class="checkbox" style="padding: 0px 0px 5px 0px !important;">
                                                                    <label for="autoLoadInVMS" class="control-label">
                                                                        <?php
                                                                        $isChkd = "";
                                                                        $isRdOnly = "disabled=\"true\"";
                                                                        if ($canEdt === true) {
                                                                            $isRdOnly = "";
                                                                        }
                                                                        if ($autoLoadInVMS == "Yes") {
                                                                            $isChkd = "checked=\"true\"";
                                                                        }
                                                                        ?>
                                                                        <input type="checkbox" name="autoLoadInVMS" id="autoLoadInVMS" <?php echo $isChkd . " " . $isRdOnly; ?>>Auto-Load in VMS?</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invMinItmQty" class="control-label">Planning Qtys:</label>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invMinItmQty" id="invMinItmQty" class="form-control" value="<?php echo $invMinItmQty; ?>" placeholder="Min Qty.">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invMinItmQty; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invMaxItmQty" id="invMaxItmQty" class="form-control" value="<?php echo $invMaxItmQty; ?>" placeholder="Max Qty.">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invMaxItmQty; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invValCrncyNm" class="control-label">Value Currency:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php if ($canEdt === true) { ?>
                                                                    <div class="input-group" style="width:100% !important;">
                                                                        <input type="text" name="invValCrncyNm" id="invValCrncyNm" class="form-control rqrdFld" value="<?php echo $invValCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                                        <input type="hidden" name="invValCrncyID" id="invValCrncyID" class="form-control" value="<?php echo $invValCrncyID; ?>">
                                                                        <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Currencies', '', '', '', 'radio', true, '', 'invValCrncyNm', '', 'clear', 0, '', function () {
                                                                                                        var aa112 = 1;
                                                                                                    }, 'invValCrncyID');">
                                                                            <span class="glyphicon glyphicon-th-list"></span>
                                                                        </label>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <span><?php echo $invValCrncyNm; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invPriceLessTax" class="control-label">Price Less Tax & Charges:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invPriceLessTax" id="invPriceLessTax" class="form-control" value="<?php echo $invPriceLessTax; ?>" placeholder="0.00" readonly="true">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invPriceLessTax; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invSllngPrice" class="control-label">Selling Price:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invSllngPrice" id="invSllngPrice" class="form-control rqrdFld" value="<?php echo $invSllngPrice; ?>" placeholder="0.00">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invSllngPrice; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invLtstCostPrice" class="control-label">Average Cost Price:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invLtstCostPrice" id="invLtstCostPrice" class="form-control" value="<?php echo $invLtstCostPrice; ?>" placeholder="0.00" readonly="true">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invLtstCostPrice; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invNwPrftAmnt" class="control-label">New Profit Amount:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invNwPrftAmnt" id="invNwPrftAmnt" class="form-control" value="<?php echo $invNwPrftAmnt; ?>" placeholder="0.00" readonly="true">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invNwPrftAmnt; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invNewSllngPrice" class="control-label">New Selling Price:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invNewSllngPrice" id="invNewSllngPrice" class="form-control" value="<?php echo $invNewSllngPrice; ?>" placeholder="0.00" readonly="true">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invNewSllngPrice; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invPrftMrgnPrcnt" class="control-label">Profit Margin(%):</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="text" name="invPrftMrgnPrcnt" id="invPrftMrgnPrcnt" class="form-control" value="<?php echo $invPrftMrgnPrcnt . "%"; ?>" placeholder="0.00" readonly="true">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invPrftMrgnPrcnt . "%"; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="invPrftMrgnAmnt" class="control-label">Profit Margin:</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <input type="number" name="invPrftMrgnAmnt" id="invPrftMrgnAmnt" class="form-control" value="<?php echo $invPrftMrgnAmnt; ?>" placeholder="0.00" readonly="true">
                                                                <?php } else { ?>
                                                                    <span><?php echo $invPrftMrgnAmnt; ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                                <label for="" class="control-label">&nbsp</label>
                                                            </div>
                                                            <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                                <?php
                                                                if ($canEdt === true) {
                                                                ?>
                                                                    <button type="button" class="btn btn-primary" onclick="saveINVItmsForm();">Overwrite Selling Price</button>
                                                                <?php } else { ?>
                                                                    <span>&nbsp</span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsGl" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invAssetAcntNm" class="control-label">Inventory/Asset Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invAssetAcntNm" id="invAssetAcntNm" class="form-control rqrdFld" value="<?php echo $invAssetAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invAssetAcntID" id="invAssetAcntID" class="form-control" value="<?php echo $invAssetAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Asset Accounts', '', '', '', 'radio', true, '', 'invAssetAcntID', 'invAssetAcntNm', 'clear', 0, '', function () {
                                                                                                var aa112 = 1;
                                                                                            });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invAssetAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invCogsAcntNm" class="control-label">Cost of Goods Sold:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invCogsAcntNm" id="invCogsAcntNm" class="form-control rqrdFld" value="<?php echo $invCogsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invCogsAcntID" id="invCogsAcntID" class="form-control" value="<?php echo $invCogsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Revenue Accounts', '', '', '', 'radio', true, '', 'invCogsAcntID', 'invCogsAcntNm', 'clear', 0, '', function () {
                                                                                                var aa112 = 1;
                                                                                            });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invCogsAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invSRvnuAcntNm" class="control-label">Sales Revenue:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invSRvnuAcntNm" id="invSRvnuAcntNm" class="form-control rqrdFld" value="<?php echo $invSRvnuAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invSRvnuAcntID" id="invSRvnuAcntID" class="form-control" value="<?php echo $invSRvnuAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Revenue Accounts', '', '', '', 'radio', true, '', 'invSRvnuAcntID', 'invSRvnuAcntNm', 'clear', 0, '', function () {
                                                                                                var aa112 = 1;
                                                                                            });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invSRvnuAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invSRetrnAcntNm" class="control-label">Sales Return:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invSRetrnAcntNm" id="invSRetrnAcntNm" class="form-control rqrdFld" value="<?php echo $invSRetrnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invSRetrnAcntID" id="invSRetrnAcntID" class="form-control" value="<?php echo $invSRetrnAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Revenue Accounts', '', '', '', 'radio', true, '', 'invSRetrnAcntID', 'invSRetrnAcntNm', 'clear', 0, '', function () {
                                                                                                var aa112 = 1;
                                                                                            });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invSRetrnAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invPRetrnAcntNm" class="control-label">Purchase Returns Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invPRetrnAcntNm" id="invPRetrnAcntNm" class="form-control rqrdFld" value="<?php echo $invPRetrnAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invPRetrnAcntID" id="invPRetrnAcntID" class="form-control" value="<?php echo $invPRetrnAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Contra Expense Accounts', '', '', '', 'radio', true, '', 'invPRetrnAcntID', 'invPRetrnAcntNm', 'clear', 0, '', function () {
                                                                                                var aa112 = 1;
                                                                                            });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invPRetrnAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invExpnsAcntNm" class="control-label">Expense Account:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <div class="input-group" style="width:100% !important;">
                                                                <input type="text" name="invExpnsAcntNm" id="invExpnsAcntNm" class="form-control rqrdFld" value="<?php echo $invExpnsAcntNm; ?>" readonly="true" style="width:100% !important;">
                                                                <input type="hidden" name="invExpnsAcntID" id="invExpnsAcntID" class="form-control" value="<?php echo $invExpnsAcntID; ?>">
                                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Expense Accounts', '', '', '', 'radio', true, '', 'invExpnsAcntID', 'invExpnsAcntNm', 'clear', 0, '', function () {
                                                                                                var aa112 = 1;
                                                                                            });">
                                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                                </label>
                                                            </div>
                                                        <?php } else { ?>
                                                            <span><?php echo $invExpnsAcntNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsStores" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <?php
                                            $nwRowHtmlAB = "<tr id=\"oneItmStoresRow__WWW123WWW\">"
                                                . "<td class=\"lovtd\"><span>New</span></td>"
                                                . "<td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StoreNm\" value=\"\" style=\"width:100%;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StoreID\" value=\"-1\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_StockID\" value=\"-1\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Stores', '', '', '', 'radio', true, '', 'oneItmStoresRow_WWW123WWW_StoreID', 'oneItmStoresRow_WWW123WWW_StoreNm', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group\"  style=\"width:100%;\">
                                                                <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_ShlvNm\" value=\"\" style=\"width:100%;\">
                                                                <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmStoresRow_WWW123WWW_ShlvIDs\" value=\"\">
                                                                <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Store Shelves', 'oneItmStoresRow_WWW123WWW_StoreID', '', '', 'check', true, '', 'oneItmStoresRow_WWW123WWW_ShlvIDs', 'oneItmStoresRow_WWW123WWW_ShlvNm', 'clear', 1, '');\">
                                                                    <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\"> 
                                                        <div class=\"\" style=\"width:100% !important;\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneItmStoresRow_WWW123WWW_StrtDte\" value=\"\" readonly=\"true\" style=\"width:100% !important;\">
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <div class=\"\">
                                                            <div class=\"input-group date form_date_tme\" data-date=\"\" data-date-format=\"dd-M-yyyy hh:ii:ss\" data-link-field=\"dtp_input2\" data-link-format=\"yyyy-mm-dd hh:ii:ss\" style=\"width:100%;\">
                                                                <input class=\"form-control\" size=\"16\" type=\"text\" id=\"oneItmStoresRow_WWW123WWW_EndDte\" value=\"\" readonly=\"true\" style=\"width:100%;\">
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-remove\"></span></span>
                                                                <span class=\"input-group-addon\"><span class=\"glyphicon glyphicon-calendar\"></span></span>
                                                            </div>                                                                
                                                        </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delINVItmStores('oneItmStoresRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item Store\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                            if ($canVwRcHstry === true) {
                                                $nwRowHtmlAB .= "<td class=\"lovtd\">&nbsp;</td></tr>";
                                            }
                                            $nwRowHtml = urlencode($nwRowHtmlAB);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdt === true) { ?>
                                                        <button id="addNwStoreBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowAfta('oneItmStoresTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Item Store">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Store
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshItmBtn1" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneINVItmsForm(<?php echo $sbmtdItmID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title="Reload Item Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmStoresTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th>Store Name</th>
                                                                <th>Shelves</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneINVItemStores($sbmtdItmID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $storeName = $rwCage[1];
                                                                $shelves = $rwCage[2];
                                                                $storeStrtDte = $rwCage[3];
                                                                $storeEndDte = $rwCage[4];
                                                            ?>
                                                                <tr id="oneItmStoresRow_<?php echo $cntrUsr; ?>">
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdt === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group" style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StoreNm" value="<?php echo $storeName; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID" value="<?php echo $rwCage[5]; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_StockID" value="<?php echo $rwCage[7]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Stores', '', '', '', 'radio', true, '<?php echo $storeName; ?>', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeName; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdt === true) { ?>
                                                                            <div class="">
                                                                                <div class="input-group" style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvNm" value="<?php echo $shelves; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvIDs" value="<?php echo $rwCage[6]; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Store Shelves', 'oneItmStoresRow<?php echo $cntrUsr; ?>_StoreID', '', '', 'check', true, '<?php echo $shelves; ?>', 'oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvIDs', 'oneItmStoresRow<?php echo $cntrUsr; ?>_ShlvNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $shelves; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneItmStoresRow<?php echo $cntrUsr; ?>_StrtDte" value="<?php echo $storeStrtDte; ?>" readonly="true">
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeStrtDte; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <div class="input-group date form_date_tme" data-date="" data-date-format="dd-M-yyyy hh:ii:ss" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd hh:ii:ss" style="width:100%;">
                                                                                    <input class="form-control" size="16" type="text" id="oneItmStoresRow<?php echo $cntrUsr; ?>_EndDte" value="<?php echo $storeEndDte; ?>" readonly="true">
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $storeEndDte; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php if ($canEdt === true) { ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delINVItmStores('oneItmStoresRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item Store">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($rwCage[7] . "|inv.inv_stock|stock_id"),
                                                                                                                                                                                                                                        $smplTokenWord1
                                                                                                                                                                                                                                    ));
                                                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsExtInfo" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmExtrInfo" class="control-label">Extra Information:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                        ?>
                                                            <textarea rows="4" name="invItmExtrInfo" id="invItmExtrInfo" class="form-control"><?php echo $invItmExtrInfo; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmExtrInfo; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmOthrDesc" class="control-label">Other Description:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                        ?>
                                                            <textarea rows="6" name="invItmOthrDesc" id="invItmOthrDesc" class="form-control"><?php echo $invItmOthrDesc; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmOthrDesc; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsUOM" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <?php
                                            $nwRowHtmlAB = "<tr id=\"oneItmUOMsRow__WWW123WWW\">"
                                                . "<td class=\"lovtd\"><span>New</span></td>"
                                                . "<td class=\"lovtd\">
                                                            <div class=\"\">
                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_UOMNm\" value=\"\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_UOMID\" value=\"-1\">
                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_LineID\" value=\"-1\">
                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', '', '', '', 'radio', true, '', 'oneItmUOMsRow_WWW123WWW_UOMID', 'oneItmUOMsRow_WWW123WWW_UOMNm', 'clear', 1, '');\">
                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_CnvrsnFctr\" value=\"0\">
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_SortOrdr\" value=\"\">
                                                            </div>
                                                    </td>
                                                    <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_SllgnPrce\" value=\"0.00\">
                                                            </div>  
                                                    </td>
                                                     <td class=\"lovtd\">
                                                            <div class=\"\">                                                                                
                                                                <input type=\"number\" class=\"form-control\" aria-label=\"...\" id=\"oneItmUOMsRow_WWW123WWW_PriceLsTx\" value=\"0.00\" readonly=\"true\">
                                                            </div>   
                                                    </td>
                                                    <td class=\"lovtd\">
                                                        <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delINVItmUoMs('oneItmUOMsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Item UOM\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                        </button>
                                                    </td>";
                                            if ($canVwRcHstry === true) {
                                                $nwRowHtmlAB .= "<td class=\"lovtd\">&nbsp;</td></tr>";
                                            }
                                            $nwRowHtml = urlencode($nwRowHtmlAB);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php
                                                    if ($canEdt === true) {
                                                    ?>
                                                        <button id="addNwUOMBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowAfta('oneItmUOMsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Item UOM">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add UOM
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshItmBtn2" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneINVItmsForm(<?php echo $sbmtdItmID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title="Reload Item Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmUOMsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th>UOM</th>
                                                                <th>Conversion Factor</th>
                                                                <th>Sort Order</th>
                                                                <th>Selling Price</th>
                                                                <th>Price Less Tax</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneINVItemUOMs($sbmtdItmID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $itmUOM1 = $rwCage[1];
                                                                $itmUOMLineID = $rwCage[4];
                                                                $itmUOMID = $rwCage[5];
                                                                $uomCnvrsnFctr = $rwCage[2];
                                                                $uomSortOrdr = (float) $rwCage[3];
                                                                $uomSllngPrice = (float) $rwCage[6];
                                                                $uomPriceLsTx = (float) $rwCage[7];
                                                            ?>
                                                                <tr id="oneItmUOMsRow_<?php echo $cntrUsr; ?>">
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <div class="input-group" style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMNm" value="<?php echo $itmUOM1; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMID" value="<?php echo $itmUOMID; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $itmUOMLineID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Unit Of Measures', '', '', '', 'radio', true, '<?php echo $itmUOMID; ?>', 'oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMID', 'oneItmUOMsRow<?php echo $cntrUsr; ?>_UOMNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $itmUOM1; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <input type="number" class="form-control rqrdFld" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_CnvrsnFctr" value="<?php echo $uomCnvrsnFctr; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomCnvrsnFctr; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <input type="number" class="form-control rqrdFld" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_SortOrdr" value="<?php echo $uomSortOrdr; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomSortOrdr; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <input type="number" class="form-control rqrdFld" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_SllgnPrce" value="<?php echo $uomSllngPrice; ?>">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomSllngPrice; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <input type="number" class="form-control" aria-label="..." id="oneItmUOMsRow<?php echo $cntrUsr; ?>_PriceLsTx" value="<?php echo $uomPriceLsTx; ?>" readonly="true">
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $uomPriceLsTx; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php if ($canEdt === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delINVItmUoMs('oneItmUOMsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Item UOM">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($rwCage[4] . "|inv.itm_uoms|itm_uom_id"),
                                                                                                                                                                                                                                        $smplTokenWord1
                                                                                                                                                                                                                                    ));
                                                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsDrugLbl" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmGnrcNm" class="control-label">Generic Name:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                        ?>
                                                            <input type="text" name="invItmGnrcNm" id="invItmGnrcNm" class="form-control" value="<?php echo $invItmGnrcNm; ?>">
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmGnrcNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmTradeNm" class="control-label">Trade Name:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                        ?>
                                                            <input type="text" name="invItmTradeNm" id="invItmTradeNm" class="form-control" value="<?php echo $invItmTradeNm; ?>">
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmTradeNm; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmUslDsge" class="control-label">Usual Dosage:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php
                                                        if ($canEdt === true) {
                                                        ?>
                                                            <textarea rows="3" name="invItmUslDsge" id="invItmUslDsge" class="form-control"><?php echo $invItmUslDsge; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmUslDsge; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmMaxDsge" class="control-label">Maximum Dosage:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) {
                                                        ?>
                                                            <textarea rows="3" name="invItmMaxDsge" id="invItmMaxDsge" class="form-control"><?php echo $invItmMaxDsge; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmMaxDsge; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmCntrIndctns" class="control-label">Contraindications:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <textarea rows="3" name="invItmCntrIndctns" id="invItmCntrIndctns" class="form-control"><?php echo $invItmCntrIndctns; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmCntrIndctns; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                                        <label for="invItmFoodIntrctns" class="control-label">Food Interactions:</label>
                                                    </div>
                                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                                        <?php if ($canEdt === true) { ?>
                                                            <textarea rows="3" name="invItmFoodIntrctns" id="invItmFoodIntrctns" class="form-control"><?php echo $invItmFoodIntrctns; ?></textarea>
                                                        <?php } else { ?>
                                                            <span><?php echo $invItmFoodIntrctns; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="invItmsDrugIntrctns" class="tab-pane fade hideNotice" style="border:none !important;padding:0px !important;">
                                            <?php
                                            $nwRowHtml = "<tr id=\"oneItmDrugIntrctnsRow__WWW123WWW\">"
                                                . "<td class=\"lovtd\"><span>New</span></td>"
                                                . "<td class=\"lovtd\">
                                                                            <div class=\"\">
                                                                                <div class=\"input-group\"  style=\"width:100%;\">
                                                                                    <input type=\"text\" class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_DrugNm\" value=\"\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_DrugID\" value=\"-1\">
                                                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_LineID\" value=\"-1\">
                                                                                    <label class=\"btn btn-primary btn-file input-group-addon\" onclick=\"getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', '', '', '', 'radio', true, '', 'oneItmDrugIntrctnsRow_WWW123WWW_DrugID', 'oneItmDrugIntrctnsRow_WWW123WWW_DrugNm', 'clear', 0, '');\">
                                                                                        <span class=\"glyphicon glyphicon-th-list\"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"\">
                                                                                <textarea class=\"form-control\" aria-label=\"...\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_Intrctn\" rows=\"3\"></textarea>                                                                                    
                                                                            </div>
                                                                    </td>
                                                                    <td class=\"lovtd\">
                                                                            <div class=\"\">
                                                                                <select data-placeholder=\"Select...\" class=\"form-control chosen-select\" id=\"oneItmDrugIntrctnsRow_WWW123WWW_Action\">";

                                            $valslctdArry = array("", "", "", "");
                                            $srchInsArrys = array(
                                                "Disallow if combination found", "Warn if combination found", "Disallow if combination not found",
                                                "Warn if combination not found"
                                            );
                                            for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                $nwRowHtml .= "<option value=\"" . $srchInsArrys[$z] . "\" " . $valslctdArry[$z] . ">" . $srchInsArrys[$z] . "</option>";
                                            }
                                            $nwRowHtml .= "</select>                                                                
                                                                            </div>
                                                                    </td>
                                                                        <td class=\"lovtd\">
                                                                            <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"delINVItmDrgIntrctns('oneItmDrugIntrctnsRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Drug Interaction\">
                                                                                <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                                            </button>
                                                                        </td>";
                                            if ($canVwRcHstry === true) {
                                                $nwRowHtml .= "<td class=\"lovtd\">&nbsp;</td></tr>";
                                            }
                                            $nwRowHtml = urlencode($nwRowHtml);
                                            ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($canEdt === true) {
                                                    ?>
                                                        <button id="addNwDrugIntrctnBtn" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowAfta('oneItmDrugIntrctnsTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="New Drug Interaction">
                                                            <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                            Add Drug Interaction
                                                        </button>
                                                    <?php } ?>
                                                    <button id="refreshItmBtn3" type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="getOneINVItmsForm(<?php echo $sbmtdItmID; ?>, 'ReloadDialog');" data-toggle="tooltip" data-placement="bottom" title="Reload Item Details">
                                                        <img src="cmn_images/refresh.bmp" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                        Refresh
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-striped table-bordered table-responsive" id="oneItmDrugIntrctnsTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                                        <thead>
                                                            <tr>
                                                                <th style="max-width:50px;width:50px;">No.</th>
                                                                <th>Drug</th>
                                                                <th>Interaction Effect</th>
                                                                <th>Action</th>
                                                                <th style="max-width:30px;width:30px;">&nbsp;</th>
                                                                <?php
                                                                if ($canVwRcHstry === true) {
                                                                ?>
                                                                    <th>...</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $rslt = get_OneINVItemDrgIntrctns($sbmtdItmID);
                                                            $cntrUsr = 0;
                                                            while ($rwCage = loc_db_fetch_array($rslt)) {
                                                                $cntrUsr++;
                                                                $drugName = $rwCage[1];
                                                                $intrctnEffct = $rwCage[2];
                                                                $actionDrug = $rwCage[3];
                                                                $scndDrugID = $rwCage[4];
                                                                $drugIntrctnID = $rwCage[5];
                                                            ?>
                                                                <tr id="oneItmDrugIntrctnsRow_<?php echo $cntrUsr; ?>">
                                                                    <td class="lovtd"><span><?php echo ($rwCage[0]); ?></span></td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <div class="input-group" style="width:100%;">
                                                                                    <input type="text" class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugNm" value="<?php echo $drugName; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugID" value="<?php echo $scndDrugID; ?>">
                                                                                    <input type="hidden" class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_LineID" value="<?php echo $drugIntrctnID; ?>">
                                                                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Inventory Items', '', '', '', 'radio', true, '<?php echo $scndDrugID; ?>', 'oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugID', 'oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_DrugNm', 'clear', 1, '');">
                                                                                        <span class="glyphicon glyphicon-th-list"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $drugName; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <textarea class="form-control" aria-label="..." id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_Intrctn" rows="3"><?php echo $intrctnEffct; ?></textarea>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $intrctnEffct; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="lovtd">
                                                                        <?php
                                                                        if ($canEdt === true) {
                                                                        ?>
                                                                            <div class="">
                                                                                <select data-placeholder="Select..." class="form-control chosen-select" id="oneItmDrugIntrctnsRow<?php echo $cntrUsr; ?>_Action">
                                                                                    <?php
                                                                                    $valslctdArry = array("", "", "", "");
                                                                                    $srchInsArrys = array(
                                                                                        "Disallow if combination found",
                                                                                        "Warn if combination found",
                                                                                        "Disallow if combination not found",
                                                                                        "Warn if combination not found"
                                                                                    );
                                                                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                                                                        if ($actionDrug == $srchInsArrys[$z]) {
                                                                                            $valslctdArry[$z] = "selected";
                                                                                        }
                                                                                    ?>
                                                                                        <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        <?php } else { ?>
                                                                            <span><?php echo $actionDrug; ?></span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php if ($canEdt === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="delINVItmDrgIntrctns('oneItmDrugIntrctnsRow_<?php echo $cntrUsr; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Drug Interaction">
                                                                                <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php
                                                                    if ($canVwRcHstry === true) {
                                                                    ?>
                                                                        <td class="lovtd">
                                                                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="View Record History" onclick="getRecHstry('<?php
                                                                                                                                                                                                                                    echo urlencode(encrypt1(($rwCage[5] . "|inv.inv_drug_interactions|drug_intrctn_id"),
                                                                                                                                                                                                                                        $smplTokenWord1
                                                                                                                                                                                                                                    ));
                                                                                                                                                                                                                                    ?>');" style="padding:2px !important;">
                                                                                <img src="cmn_images/Information.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">
                                                                            </button>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <?php if ($canEdt === true) { ?>
                                <button type="button" class="btn btn-primary" onclick="saveINVItmsForm();">Save Changes</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 101) {
                //New Store Item Form 
                $canEdt = test_prmssns($dfltPrvldgs[31], $mdlNm) || test_prmssns($dfltPrvldgs[32], $mdlNm);
                $sbmtdItmID = isset($_POST['sbmtdItmID']) ? cleanInputData($_POST['sbmtdItmID']) : -1;
                $rowIDAttrb = isset($_POST['rowIDAttrb']) ? cleanInputData($_POST['rowIDAttrb']) : 'XX_RHO_UNDEFINED';

                $invItemNm = "";
                $invItemDesc = "";

                $invTmpltNm = "";
                $invTmpltID = -1;
                $invItemType = "";
                $invItemCtgry = "";
                $invItemCtgryID = -1;
                $invBaseUom = "";
                $invBaseUomID = -1;

                $invTxCodeID = -1;
                $invTxCodeName = "";
                $invDscntCodeID = -1;
                $invDscntCodeName = "";
                $invChrgCodeID = -1;
                $invChrgCodeName = "";
                $isPlnngEnbld = "No";
                $autoLoadInVMS = "Yes";
                $isEnbld = "No";
                $invMinItmQty = 0;
                $invMaxItmQty = 0;

                $invValCrncyID = -1;
                $invValCrncyNm = "";
                $invPriceLessTax = 0;
                $invSllngPrice = 0;

                $invNwPrftAmnt = 0;
                $invLtstCostPrice = 0;
                $invNewSllngPrice = 0;
                $invPrftMrgnPrcnt = 0;
                $invPrftMrgnAmnt = 0;

                $invAssetAcntID = -1;
                $invAssetAcntNm = "";
                $invCogsAcntID = -1;
                $invCogsAcntNm = "";
                $invSRvnuAcntID = -1;

                $invSRvnuAcntNm = "";
                $invSRetrnAcntID = -1;
                $invSRetrnAcntNm = "";
                $invPRetrnAcntID = -1;
                $invPRetrnAcntNm = "";
                $invExpnsAcntID = -1;
                $invExpnsAcntNm = "";
                $invItmExtrInfo = "";
                $invItmOthrDesc = "";

                $invItmGnrcNm = "";
                $invItmTradeNm = "";
                $invItmUslDsge = "";
                $invItmMaxDsge = "";
                $invItmCntrIndctns = "";
                $invItmFoodIntrctns = "";
                $nwFileName = "";
                $oldFileNm = "";
                $extension = "png";
                if ($sbmtdItmID > 0) {
                    $result = get_OneINVItems($sbmtdItmID);
                    while ($row = loc_db_fetch_array($result)) {
                        $sbmtdItmID = (float) $row[0];
                        $invItemNm = $row[1];
                        $invItemDesc = $row[2];

                        $invTmpltNm = $row[4];
                        $invTmpltID = (float) $row[3];
                        $invItemType = $row[5];
                        $invItemCtgry = $row[7];
                        $invItemCtgryID = (float) $row[6];
                        $invBaseUom = $row[9];
                        $invBaseUomID = (float) $row[8];

                        $invTxCodeID = (float) $row[10];
                        $invTxCodeName = $row[11];
                        $invDscntCodeID = (float) $row[12];
                        $invDscntCodeName = $row[13];
                        $invChrgCodeID = (float) $row[14];
                        $invChrgCodeName = $row[15];
                        $isPlnngEnbld = ($row[16] == "1") ? "Yes" : "No";
                        $autoLoadInVMS = ($row[17] == "1") ? "Yes" : "No";
                        $isEnbld = ($row[18] == "1") ? "Yes" : "No";
                        $invMinItmQty = (float) $row[19];
                        $invMaxItmQty = (float) $row[20];

                        $invValCrncyID = (float) $row[21];
                        $invValCrncyNm = $row[22];
                        $invSllngPrice = (float) $row[24];
                        $invPriceLessTax = (float) $row[23];
                        if ($invPriceLessTax == 0) {
                            $invPriceLessTax = $invSllngPrice;
                        }
                        $invLtstCostPrice = getHgstUnitCostPrice($sbmtdItmID);

                        $invNwPrftAmnt = round(round($invPriceLessTax, 2) - $invLtstCostPrice, 2);
                        $invNewSllngPrice = 0;
                        $invPrftMrgnPrcnt = 0;
                        if (round($invPriceLessTax, 2) != 0) {
                            $invPrftMrgnPrcnt = round(($invNwPrftAmnt / round($invPriceLessTax, 2)) * 100, 2);
                        }
                        $invPrftMrgnAmnt = 0;

                        $invAssetAcntID = (float) $row[25];
                        $invAssetAcntNm = $row[26];
                        $invCogsAcntID = (float) $row[27];
                        $invCogsAcntNm = $row[28];
                        $invSRvnuAcntID = (float) $row[29];

                        $invSRvnuAcntNm = $row[30];
                        $invSRetrnAcntID = (float) $row[31];
                        $invSRetrnAcntNm = $row[32];
                        $invPRetrnAcntID = (float) $row[33];
                        $invPRetrnAcntNm = $row[34];
                        $invExpnsAcntID = (float) $row[35];
                        $invExpnsAcntNm = $row[36];
                        $invItmExtrInfo = $row[37];
                        $invItmOthrDesc = $row[38];

                        $invItmGnrcNm = $row[39];
                        $invItmTradeNm = $row[40];
                        $invItmUslDsge = $row[41];
                        $invItmMaxDsge = $row[42];
                        $invItmCntrIndctns = $row[43];
                        $invItmFoodIntrctns = $row[44];

                        $temp = explode(".", $row[46]);
                        $oldFileNm = $temp[0];
                        $extension = end($temp);
                        if ($extension == "") {
                            $extension = "png";
                        }
                        $nwFileName = encrypt1($row[46], $smplTokenWord1) . "." . $extension;
                    }
                }
            ?>
                <form class="form-horizontal" id='storeItmStpForm' action='' method='post' accept-charset='UTF-8'>
                    <div class="row" style="padding: 0px 15px 0px 15px !important;">
                        <div class="col-md-12" style="padding: 0px 5px 0px 5px !important;">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invItemNm" class="control-label">Item Name:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="text" name="invItemNm" id="invItemNm" class="form-control" value="<?php echo $invItemNm; ?>" style="width:100% !important;" readonly="true">
                                            <input type="hidden" name="sbmtdItmID" id="sbmtdItmID" class="form-control" value="<?php echo $sbmtdItmID; ?>">
                                        <?php } else { ?>
                                            <span><?php echo $invItemNm; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invItemDesc" class="control-label">Item Description:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <textarea rows="1" name="invItemDesc" id="invItemDesc" class="form-control" readonly="true"><?php echo $invItemDesc; ?></textarea>
                                        <?php } else { ?>
                                            <span><?php echo $invItemDesc; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invTxCodeName" class="control-label">Tax Code:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <div class="input-group" style="width:100% !important;">
                                                <input type="text" name="invTxCodeName" id="invTxCodeName" class="form-control" value="<?php echo $invTxCodeName; ?>" readonly="true" style="width:100% !important;">
                                                <input type="hidden" name="invTxCodeID" id="invTxCodeID" class="form-control" value="<?php echo $invTxCodeID; ?>">
                                                <label class="btn btn-primary btn-file input-group-addon" onclick="getLovsPage('myLovModal', 'myLovModalTitle', 'myLovModalBody', 'Tax Codes', '', '', '', 'radio', true, '', 'invTxCodeID', 'invTxCodeName', 'clear', 0, '', function () {
                                                                                var aa112 = 1;
                                                                            });">
                                                    <span class="glyphicon glyphicon-th-list"></span>
                                                </label>
                                            </div>
                                        <?php } else { ?>
                                            <span><?php echo $invTxCodeName; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invValCrncyNm" class="control-label">Value Currency:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <div class="input-group" style="width:100% !important;">
                                                <input type="text" name="invValCrncyNm" id="invValCrncyNm" class="form-control" value="<?php echo $invValCrncyNm; ?>" readonly="true" style="width:100% !important;">
                                                <input type="hidden" name="invValCrncyID" id="invValCrncyID" class="form-control" value="<?php echo $invValCrncyID; ?>">
                                            </div>
                                        <?php } else { ?>
                                            <span><?php echo $invValCrncyNm; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invPriceLessTax" class="control-label">Price Less Tax:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="number" name="invPriceLessTax" id="invPriceLessTax" class="form-control" value="<?php echo $invPriceLessTax; ?>" placeholder="0.00" readonly="true">
                                        <?php } else { ?>
                                            <span><?php echo $invPriceLessTax; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invSllngPrice" class="control-label">Selling Price:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="number" name="invSllngPrice" id="invSllngPrice" class="form-control rqrdFld" value="<?php echo $invSllngPrice; ?>" placeholder="0.00">
                                        <?php } else { ?>
                                            <span><?php echo $invSllngPrice; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invLtstCostPrice" class="control-label">Average Cost Price:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="number" name="invLtstCostPrice" id="invLtstCostPrice" class="form-control" value="<?php echo $invLtstCostPrice; ?>" placeholder="0.00" readonly="true">
                                        <?php } else { ?>
                                            <span><?php echo $invLtstCostPrice; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invNwPrftAmnt" class="control-label">Profit Amount:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="number" name="invNwPrftAmnt" id="invNwPrftAmnt" class="form-control" value="<?php echo $invNwPrftAmnt; ?>" placeholder="0.00" readonly="true">
                                        <?php } else { ?>
                                            <span><?php echo $invNwPrftAmnt; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invNewSllngPrice" class="control-label">New Selling Price:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="number" name="invNewSllngPrice" id="invNewSllngPrice" class="form-control" value="<?php echo $invNewSllngPrice; ?>" placeholder="0.00" readonly="true">
                                        <?php } else { ?>
                                            <span><?php echo $invNewSllngPrice; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invPrftMrgnPrcnt" class="control-label">Profit Margin(%):</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="text" name="invPrftMrgnPrcnt" id="invPrftMrgnPrcnt" class="form-control" value="<?php echo $invPrftMrgnPrcnt . "%"; ?>" placeholder="0.00" readonly="true">
                                        <?php } else { ?>
                                            <span><?php echo $invPrftMrgnPrcnt . "%"; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <div class="col-sm-12">
                                    <div class="col-md-4" style="padding: 0px 0px 0px 0px !important;">
                                        <label for="invPrftMrgnAmnt" class="control-label">Profit Margin:</label>
                                    </div>
                                    <div class="col-md-8" style="padding: 0px 0px 0px 0px !important;">
                                        <?php if ($canEdt === true) { ?>
                                            <input type="number" name="invPrftMrgnAmnt" id="invPrftMrgnAmnt" class="form-control" value="<?php echo $invPrftMrgnAmnt; ?>" placeholder="0.00" readonly="true">
                                        <?php } else { ?>
                                            <span><?php echo $invPrftMrgnAmnt; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:1px 15px 1px 15px !important;">
                        <hr style="margin:3px 0px 3px 0px;">
                    </div>
                    <div class="row" style="float:right;padding-right: 30px;margin-top: 5px;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?php if ($canEdt === true) { ?>
                            <button type="button" class="btn btn-primary" onclick="saveINVItmPricesForm('<?php echo $rowIDAttrb; ?>');">Overwrite Selling Price</button>
                        <?php } ?>
                    </div>
                </form>
            <?php
            } else if ($vwtyp == 2) {
                if (!$canAdd && !$canEdt) {
                    restricted();
                    exit();
                }
                $itemID = isset($_POST['sbmtdItemID']) ? cleanInputData($_POST['sbmtdItemID']) : 0;
                $varTtlQty = isset($_POST['varTtlQty']) ? cleanInputData($_POST['varTtlQty']) : 0;
                $sbmtdRwNum = isset($_POST['sbmtdRwNum']) ? cleanInputData($_POST['sbmtdRwNum']) : -1;
                $sbmtdTblRowID = isset($_POST['sbmtdTblRowID']) ? cleanInputData($_POST['sbmtdTblRowID']) : "";
                $rowIDAttrb = isset($_POST['rowIDAttrb']) ? cleanInputData($_POST['rowIDAttrb']) : "";
                $sbmtdCrncyNm = isset($_POST['sbmtdCrncyNm']) ? cleanInputData($_POST['sbmtdCrncyNm']) : -1;
                $sbmtdCrncyID = getPssblValID($sbmtdCrncyNm, getLovID("Currencies"));
                $ttlQty = $varTtlQty;
                $nwQty = 0;
                $rmndPrtVal = $ttlQty;
                $ttlPrce = 0;
                $unitPrce = 0;
                $fnccurid = $sbmtdCrncyID;
                $fnccurnm = $sbmtdCrncyNm;
                if ($sbmtdCrncyID <= 0) {
                    $fnccurid = getOrgFuncCurID($orgID);
                    $fnccurnm = getPssblValNm($fnccurid);
                }
            ?>
                <div class="row">
                    <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRowIDAttrb" value="<?php echo $rowIDAttrb; ?>">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneINVQtyBrkDwnTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UOM</th>
                                    <th style="text-align: right;">UOM QTY</th>
                                    <th style="text-align: right;">EQUIV BASE QTY</th>
                                    <th style="text-align: right;">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $crncyID = $fnccurid;
                                $crncyIDNm = $fnccurnm;
                                $whlPrtVal = 0;
                                $rngSum = 0;
                                $cnvrtdQty = 0;
                                $result = getUomBrkDwn($itemID);
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr++;
                                    $cnvsnFctr = $row[4];
                                    if ($rngSum == $ttlQty) {
                                        $cnvrtdQty = 0;
                                        $whlPrtVal = 0;
                                    } else {
                                        if ($rmndPrtVal >= $cnvsnFctr) {
                                            $whlPrt = (int) ($rmndPrtVal / $cnvsnFctr);
                                            $rmndPrt = $rmndPrtVal % $cnvsnFctr;
                                            if ($whlPrt > 0) {
                                                $whlPrtVal = $whlPrt;
                                                $cnvrtdQty = $whlPrtVal * $cnvsnFctr;
                                            }
                                            if ($rmndPrt > 0) {
                                                $rmndPrtVal = $rmndPrt;
                                            }
                                        } else {
                                            $cnvrtdQty = 0;
                                            $whlPrtVal = 0;
                                        }
                                        $rngSum = $rngSum + $cnvrtdQty;
                                    }
                                    if (strpos($rowIDAttrb, "CnsgnRcpt") !== FALSE || strpos($rowIDAttrb, "StockTrnsfr") !== FALSE) {
                                        $unitPrce = $cnvsnFctr * (float) ($row[7]);
                                    } else {
                                        $unitPrce = (float) ($row[5]);
                                    }
                                    $ttlPrce += $whlPrtVal * $unitPrce;
                                    $nwQty += $cnvrtdQty;
                                ?>
                                    <tr id="oneINVQtyBrkRow_<?php echo $cntr; ?>">
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <span><?php echo $row[1] ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_ItmUomID" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitPrce; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_CnvFctr" value="<?php echo $row[4]; ?>" style="width:100% !important;">
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbQty" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_BaseQty" name="oneINVQtyBrkRow<?php echo $cntr; ?>_BaseQty" value="<?php
                                                                                                                                                                                                                            echo number_format($whlPrtVal, 0);
                                                                                                                                                                                                                            ?>" onchange="calcScmUomBrkdwnRowVal('oneINVQtyBrkRow_<?php echo $cntr; ?>');" onkeypress="invTrnsUomFormKeyPress(event, 'oneINVQtyBrkRow_<?php echo $cntr; ?>');" style="width:100% !important;text-align: right;">
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbEqQty" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_EquivQty" name="oneINVQtyBrkRow<?php echo $cntr; ?>_EquivQty" value="<?php
                                                                                                                                                                                                                                echo number_format($cnvrtdQty, 0);
                                                                                                                                                                                                                                ?>" style="width:100% !important;text-align: right;" readonly="true">
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <input type="text" class="form-control invUmbTtl" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_TtlVal" name="oneINVQtyBrkRow<?php echo $cntr; ?>_TtlVal" value="<?php
                                                                                                                                                                                                                        echo number_format($whlPrtVal * $unitPrce, 2);
                                                                                                                                                                                                                        ?>" style="width:100% !important;text-align: right;" readonly="true">
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdQtyTtlBtn\">" . number_format(
                                            $nwQty,
                                            0,
                                            '.',
                                            ','
                                        ) . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                    </th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdUmValsTtlBtn\">" . number_format(
                                            $ttlPrce,
                                            2,
                                            '.',
                                            ','
                                        ) . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="applyNewINVQtyVal(<?php echo $sbmtdRwNum; ?>, 'myFormsModalx', '<?php echo $rowIDAttrb; ?>');">Apply Changes</button>
                        </div>
                    </div>
                </div>
            <?php
            } else if ($vwtyp == 3) {
                $itemID = isset($_POST['sbmtdItemID']) ? cleanInputData($_POST['sbmtdItemID']) : 0;
                $varTtlQty = isset($_POST['varTtlQty']) ? cleanInputData($_POST['varTtlQty']) : 0;
                $sbmtdCrncyNm = isset($_POST['sbmtdCrncyNm']) ? cleanInputData($_POST['sbmtdCrncyNm']) : -1;
                $rowIDAttrb = isset($_POST['rowIDAttrb']) ? cleanInputData($_POST['rowIDAttrb']) : "";
                $sbmtdCrncyID = getPssblValID($sbmtdCrncyNm, getLovID("Currencies"));
                //var_dump($_POST);
                $ttlQty = $varTtlQty;
                $nwQty = 0;
                $rmndPrtVal = $ttlQty;
                $ttlPrce = 0;
                $unitPrce = 0;
                $fnccurid = $sbmtdCrncyID;
                $fnccurnm = $sbmtdCrncyNm;
                if ($sbmtdCrncyID <= 0) {
                    $fnccurid = getOrgFuncCurID($orgID);
                    $fnccurnm = getPssblValNm($fnccurid);
                }
            ?>
                <div class="row">
                    <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRowIDAttrb" value="<?php echo $rowIDAttrb; ?>">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-responsive" id="oneINVQtyBrkDwnTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>UOM</th>
                                    <th style="text-align: right;">UOM QTY</th>
                                    <th style="text-align: right;">EQUIV BASE QTY</th>
                                    <th style="text-align: right;">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cntr = 0;
                                $crncyID = $fnccurid;
                                $crncyIDNm = $fnccurnm;
                                $whlPrtVal = 0;
                                $rngSum = 0;
                                $cnvrtdQty = 0;
                                $result = getUomBrkDwn($itemID);
                                while ($row = loc_db_fetch_array($result)) {
                                    $cntr++;
                                    $cnvsnFctr = $row[4];
                                    if ($rngSum == $ttlQty) {
                                        $cnvrtdQty = 0;
                                        $whlPrtVal = 0;
                                    } else {
                                        if ($rmndPrtVal >= $cnvsnFctr) {
                                            $whlPrt = (int) ($rmndPrtVal / $cnvsnFctr);
                                            $rmndPrt = $rmndPrtVal % $cnvsnFctr;
                                            if ($whlPrt > 0) {
                                                $whlPrtVal = $whlPrt;
                                                $cnvrtdQty = $whlPrtVal * $cnvsnFctr;
                                            }
                                            if ($rmndPrt > 0) {
                                                $rmndPrtVal = $rmndPrt;
                                            }
                                        } else {
                                            $cnvrtdQty = 0;
                                            $whlPrtVal = 0;
                                        }
                                        $rngSum = $rngSum + $cnvrtdQty;
                                    }
                                    if (strpos($rowIDAttrb, "CnsgnRcpt") !== FALSE || strpos($rowIDAttrb, "StockTrnsfr") !== FALSE) {
                                        $unitPrce = $cnvsnFctr * (float) ($row[7]);
                                    } else {
                                        $unitPrce = (float) ($row[5]);
                                    }
                                    $ttlPrce += $whlPrtVal * $unitPrce;
                                    $nwQty += $cnvrtdQty;
                                ?>
                                    <tr id="oneINVQtyBrkRow_<?php echo $cntr; ?>">
                                        <td class="lovtd"><span><?php echo ($cntr); ?></span></td>
                                        <td class="lovtd">
                                            <span><?php echo $row[1] ?></span>
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_ItmUomID" value="<?php echo $row[2]; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_UntVal" value="<?php echo $unitPrce; ?>" style="width:100% !important;">
                                            <input type="hidden" class="form-control" aria-label="..." id="oneINVQtyBrkRow<?php echo $cntr; ?>_CnvFctr" value="<?php echo $row[4]; ?>" style="width:100% !important;">
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($whlPrtVal, 0); ?></span>
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($cnvrtdQty, 0); ?></span>
                                        </td>
                                        <td class="lovtd" style="text-align: right;">
                                            <span><?php echo number_format($whlPrtVal * $unitPrce, 2); ?></span>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>TOTALS:</th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdQtyTtlBtn\">" . number_format(
                                            $nwQty,
                                            0,
                                            '.',
                                            ','
                                        ) . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdQtyTtlVal" value="<?php echo $nwQty; ?>">
                                    </th>
                                    <th style="text-align: right;">
                                        <?php
                                        echo "<span style=\"color:blue;\" id=\"myCptrdUmValsTtlBtn\">" . number_format(
                                            $ttlPrce,
                                            2,
                                            '.',
                                            ','
                                        ) . "</span>";
                                        ?>
                                        <input type="hidden" id="myCptrdUmValsTtlVal" value="<?php echo $ttlPrce; ?>">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="float:right;">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            <?php
            } else if ($vwtyp == 4) {
                //Display Items List During Sales
                $error = "";
                $searchAll = true;
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Both';
                $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 10;
                $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Trns. ID DESC";
                $qCnsgnOnly = isset($_POST['qCnsgnOnly']) ? cleanInputData($_POST['qCnsgnOnly']) : "false";
                $sbmtdDocType = isset($_POST['sbmtdDocType']) ? cleanInputData($_POST['sbmtdDocType']) : "";
                $sbmtdItemID = isset($_POST['sbmtdItemID']) ? (int) cleanInputData($_POST['sbmtdItemID']) : -1;
                $sbmtdStoreID = isset($_POST['sbmtdStoreID']) ? (int) cleanInputData($_POST['sbmtdStoreID']) : -1;
                $sbmtdCstmrSiteID = isset($_POST['scmSalesInvcCstmrSiteID']) ? (float) cleanInputData($_POST['scmSalesInvcCstmrSiteID']) : -1;
                $sbmtdCallBackFunc = isset($_POST['sbmtdCallBackFunc']) ? cleanInputData($_POST['sbmtdCallBackFunc']) : 'function(){var a=1;}';
                $sbmtdRowIDAttrb = isset($_POST['sbmtdRowIDAttrb']) ? cleanInputData($_POST['sbmtdRowIDAttrb']) : '';
                $qCnsgnOnlyB = ($qCnsgnOnly == "true") ? true : false;
                if ($sbmtdStoreID <= 0 && $qCnsgnOnlyB == false) {
                    $sbmtdStoreID = $selectedStoreID;
                }
                if ($qCnsgnOnlyB === true && $sbmtdItemID > 0) {
                    $lmtSze = 1000000;
                }
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $total = get_Total_StoreItms($srchFor, $srchIn, $orgID, $sbmtdStoreID, $sbmtdDocType, $qCnsgnOnlyB, $sbmtdItemID);
                if ($pageNo > ceil($total / $lmtSze)) {
                    $pageNo = 1;
                } else if ($pageNo < 1) {
                    $pageNo = ceil($total / $lmtSze);
                }
                $curIdx = $pageNo - 1;
                $result = get_StoreItems(
                    $srchFor,
                    $srchIn,
                    $curIdx,
                    $lmtSze,
                    $orgID,
                    $sbmtdStoreID,
                    $sbmtdDocType,
                    $qCnsgnOnlyB,
                    $sbmtdItemID,
                    $sbmtdCstmrSiteID
                );
                $cntr = 0;
                $ttlAvlblQTY = 0;
                $ttlRsvdQTY = 0;
                $ttlQTY = 0;
                $ttlSP = 0;
                $ttlCP = 0;
            ?>
                <form id='scmSalesInvItmsForm' action='' method='post' accept-charset='UTF-8'>
                    <!--ROW ID-->
                    <input class="form-control" id="tblRowID" type="hidden" placeholder="ROW ID" />
                    <fieldset class="">
                        <legend class="basic_person_lg1" style="color: #003245">STORE ITEMS</legend>
                        <div class="row" style="margin-bottom:0px;">
                            <?php
                            $colClassType1 = "col-md-2";
                            $colClassType2 = "col-md-5";
                            $colClassType3 = "col-md-6";
                            ?>
                            <div class="<?php echo $colClassType3; ?>" style="padding:0px 15px 0px 15px !important;">
                                <div class="input-group">
                                    <input class="form-control" id="scmSalesInvItmsSrchFor" type="text" placeholder="Search For" value="<?php
                                                                                                                                        echo trim(str_replace("%", " ", $srchFor));
                                                                                                                                        ?>" onkeyup="enterKeyFuncScmSalesInvItms(event, '', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>')">
                                    <input id="scmSalesInvItmsPageNo" type="hidden" value="<?php echo $pageNo; ?>">
                                    <input id="sbmtdItemID" type="hidden" value="<?php echo $sbmtdItemID; ?>">
                                    <input id="sbmtdStoreID" type="hidden" value="<?php echo $sbmtdStoreID; ?>">
                                    <input id="sbmtdDocType" type="hidden" value="<?php echo $sbmtdDocType; ?>">
                                    <input id="sbmtdCallBackFunc" type="hidden" value="<?php echo $sbmtdCallBackFunc; ?>">
                                    <input id="sbmtdRowIDAttrb" type="hidden" value="<?php echo $sbmtdRowIDAttrb; ?>">
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvItms('clear', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </label>
                                    <label class="btn btn-primary btn-file input-group-addon" onclick="getScmSalesInvItms('', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </label>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvItmsSrchIn">
                                        <?php
                                        $valslctdArry = array("", "");
                                        $srchInsArrys = array("Item Code/Name", "Item Description");
                                        for ($z = 0; $z < count($srchInsArrys); $z++) {
                                            if ($srchIn == $srchInsArrys[$z]) {
                                                $valslctdArry[$z] = "selected";
                                            }
                                        ?>
                                            <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                                    <select data-placeholder="Select..." class="form-control chosen-select" id="scmSalesInvItmsDsplySze" style="min-width:70px !important;">
                                        <?php
                                        $valslctdArry = array("", "", "", "", "", "", "", "", "", "", "");
                                        $dsplySzeArry = array(1, 5, 10, 15, 20, 30, 50, 100, 500, 1000, 1000000);
                                        for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                            if ($lmtSze == $dsplySzeArry[$y]) {
                                                $valslctdArry[$y] = "selected";
                                            } else {
                                                $valslctdArry[$y] = "";
                                            }
                                        ?>
                                            <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="<?php echo $colClassType1; ?>">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination" style="margin: 0px !important;">
                                        <li>
                                            <a href="javascript:getScmSalesInvItms('previous', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:getScmSalesInvItms('next', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="<?php echo $colClassType1; ?>" style="padding:5px 1px 0px 1px !important;">
                                <div class="form-check" style="font-size: 12px !important;">
                                    <label class="form-check-label">
                                        <?php
                                        $shwCnsgnOnlyChkd = "";
                                        if ($qCnsgnOnlyB == true) {
                                            $shwCnsgnOnlyChkd = "checked=\"true\"";
                                        }
                                        ?>
                                        <input type="checkbox" class="form-check-input" onclick="getScmSalesInvItms('', '#myFormsModalLxBody', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>');" id="scmSalesInvItmsShwCnsgnOnly" name="scmSalesInvItmsShwCnsgnOnly" <?php echo $shwCnsgnOnlyChkd; ?>>
                                        Show Consignments
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-responsive" id="scmSalesInvItmsHdrsTable" cellspacing="0" width="100%" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;max-width:30px;width:30px;">...</th>
                                            <th style="text-align:center;max-width:35px;width:35px;">No.</th>
                                            <th style="max-width:275px;width:275px;">Item Code/Description</th>
                                            <th style="text-align:right;min-width:220px;width:220px;">Category</th>
                                            <th style="text-align:center;max-width:55px;width:55px;">UOM.</th>
                                            <th style="text-align:right;">Consignment No.</th>
                                            <th style="text-align:right;">Available QTY</th>
                                            <th style="text-align:right;display:none;">Reserved QTY</th>
                                            <th style="text-align:right;display:none;">Total QTY</th>
                                            <th style="text-align:right;">Selling Price</th>
                                            <?php if ($canVwCstPrc === true) { ?>
                                                <th style="text-align:right;">Cost Price</th>
                                            <?php } ?>
                                            <th>Store</th>
                                            <!--<th>Shelves</th>-->
                                            <th>Tax/Discount Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = loc_db_fetch_array($result)) {
                                            $cntr += 1;
                                            $trnsLnItmID = (float) $row[0];
                                            $trnsLnItmNm = $row[2];
                                            $trnsLnUomID = (float) $row[18];
                                            $trnsLnUomNm = $row[19];
                                            $trnsLnCnsgnNo = (float) $row[11];
                                            $trnsLnAvlblQty = (float) $row[20];
                                            $trnsLnRsvdQty = (float) $row[21];
                                            $trnsLnTtlQty = (float) $row[22];
                                            $trnsLnSellPrice = (float) $row[3];
                                            $trnsLnCostPrice = (float) $row[12];
                                            $trnsLnExpryDate = (float) $row[13];
                                            $trnsLnStckID = (float) $row[5];
                                            $trnsLnCtgryID = (float) $row[4];
                                            $trnsLnCtgryNm = $row[23];
                                            $trnsLnStoreID = (float) $row[6];
                                            $trnsLnStoreNm = $row[14];
                                            $trnsLnShelves = $row[7];
                                            $trnsLnTaxID = (float) $row[8];
                                            $trnsLnTaxNm = $row[15];
                                            $trnsLnDscntID = (float) $row[9];
                                            $trnsLnDscntNm = $row[16];
                                            $trnsLnChrgID = (float) $row[10];
                                            $trnsLnChrgNm = $row[17];
                                            $trnsLnInvAcntID = (int) $row[24];
                                            $trnsLnCogsAcntID = (int) $row[25];
                                            $trnsLnSalesRevAcntID = (int) $row[26];
                                            $trnsLnSalesRetAcntID = (int) $row[27];
                                            $trnsLnPrchsRetAcntID = (int) $row[28];
                                            $trnsLnExpnsAcntID = (int) $row[29];
                                            $trnsLnItmType = $row[30];
                                            $ttlAvlblQTY += $trnsLnAvlblQty;
                                            $ttlRsvdQTY += $trnsLnRsvdQty;
                                            $ttlQTY += $trnsLnTtlQty;
                                            $ttlSP += ($trnsLnAvlblQty * $trnsLnSellPrice);
                                            $ttlCP += ($trnsLnAvlblQty * $trnsLnCostPrice);
                                        ?>
                                            <tr id="scmSalesInvItmsHdrsRow_<?php echo $cntr; ?>" class="hand_cursor">
                                                <td class="lovtd" style="text-align:center;">
                                                    <input type="checkbox" name="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CheckBox" value="scmSalesInvItmsHdrsRow_<?php echo $cntr; ?>">
                                                </td>
                                                <td class="lovtd"><?php echo ($curIdx * $lmtSze) + ($cntr); ?></td>
                                                <td class="lovtd"><?php echo $trnsLnItmNm; ?></td>
                                                <td class="lovtd">
                                                    <?php echo $trnsLnCtgryNm . str_replace(" (" . $trnsLnCtgryNm . ")", "", " (" . $trnsLnItmType . ")");
                                                                    ?>
                                                </td>
                                                <td class="lovtd" style="max-width:55px;width:55px;text-align: center;">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_UomID" value="<?php echo $trnsLnUomID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_QTY" value="<?php echo $trnsLnAvlblQty; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ItmID" value="<?php echo $trnsLnItmID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ItmNm" value="<?php echo $trnsLnItmNm; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_UomNm" value="<?php echo $trnsLnUomNm; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_SellPrice" value="<?php echo $trnsLnSellPrice; ?>">
                                                    <?php if (strpos($sbmtdDocType, "Sales") !== FALSE || strpos($sbmtdDocType, "Invoice") !== FALSE) { ?>
                                                        <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CostPrice" value="<?php echo $trnsLnCostPrice; ?>">
                                                    <?php } ?>
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CnsgnNo" value="<?php echo $trnsLnCnsgnNo; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_TaxID" value="<?php echo $trnsLnTaxID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_DscntID" value="<?php echo $trnsLnDscntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ChrgID" value="<?php echo $trnsLnChrgID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_StoreID" value="<?php echo $trnsLnStoreID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_StoreNm" value="<?php echo $trnsLnStoreNm; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_InvAcntID" value="<?php echo $trnsLnInvAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_CogsAcntID" value="<?php echo $trnsLnCogsAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_SalesRevAcntID" value="<?php echo $trnsLnSalesRevAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_SalesRetAcntID" value="<?php echo $trnsLnSalesRetAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_PrchsRetAcntID" value="<?php echo $trnsLnPrchsRetAcntID; ?>">
                                                    <input type="hidden" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_ExpnsAcntID" value="<?php echo $trnsLnExpnsAcntID; ?>">
                                                    <div class="" style="width:100% !important;">
                                                        <label class="btn btn-default btn-file" onclick="getOneScmUOMBrkdwnForm(-1, 3, 'scmSalesInvItmsHdrsRow_<?php echo $cntr; ?>');">
                                                            <span class="" id="scmSalesInvItmsHdrsRow<?php echo $cntr; ?>_UomNm1"><?php echo $trnsLnUomNm; ?></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td class="lovtd" style="text-align:right;"><?php echo $trnsLnCnsgnNo; ?></td>
                                                <?php
                                                $style1 = "color:red;";
                                                if ($trnsLnAvlblQty > 0) {
                                                    $style1 = "color:green;";
                                                }
                                                ?>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;<?php echo $style1; ?>"><?php
                                                                                                                                    echo $trnsLnAvlblQty;
                                                                                                                                    ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;display:none;"><?php echo $trnsLnRsvdQty; ?></td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;color:blue;display:none;"><?php
                                                                                                                                        echo $trnsLnTtlQty;
                                                                                                                                        ?>
                                                </td>
                                                <td class="lovtd" style="text-align:right;font-weight: bold;"><?php
                                                                                                                echo number_format($trnsLnSellPrice, 2);
                                                                                                                ?></td>
                                                <?php if ($canVwCstPrc === true) { ?>
                                                    <td class="lovtd" style="text-align:right;font-weight: bold;"><?php echo number_format($trnsLnCostPrice, 2); ?></td>
                                                <?php } ?>
                                                <td class="lovtd"><?php echo $trnsLnStoreNm; ?></td>
                                                <td class="lovtd"><?php echo trim($trnsLnTaxNm . ", " . $trnsLnDscntNm . "," . $trnsLnChrgNm, ", "); ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>TOTALS:</th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . $ttlAvlblQTY . "</span>";
                                                ?>
                                            </th>
                                            <th style="text-align: right;display:none;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . $ttlRsvdQTY . "</span>";
                                                ?>
                                            </th>
                                            <th style="text-align: right;display:none;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . $ttlQTY . "</span>";
                                                ?>
                                            </th>
                                            <th style="text-align: right;">
                                                <?php
                                                echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . number_format($ttlSP, 2, '.', ',') . "</span>";
                                                ?>
                                            </th>
                                            <?php if ($canVwCstPrc === true) { ?>
                                                <th style="text-align: right;">
                                                    <?php
                                                    echo "<span style=\"color:red;font-weight:bold;font-size:14px;\">" . number_format($ttlCP, 2, '.', ',') . "</span>";
                                                    ?>
                                                </th>
                                            <?php } ?>
                                            <th style="">&nbsp;</th>
                                            <!--<th style="">&nbsp;</th>                                           -->
                                            <th style="">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="float:right;padding-right: 15px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#myFormsModalLx').modal('hide');">Close</button>
                            <?php if (strpos($sbmtdDocType, "Receipt") !== FALSE) { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdSalesInvItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmCnsgnRcptSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                            <?php } else if (strpos($sbmtdDocType, "Purchase") !== FALSE) { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdSalesInvItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmPrchsDocSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                            <?php } else if (strpos($sbmtdDocType, "Stock") !== FALSE) { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdSalesInvItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmStockTrnsfrSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-primary" onclick="applySlctdSalesInvItms('<?php echo $sbmtdRowIDAttrb; ?>', '<?php echo $qCnsgnOnly; ?>', 'oneScmSalesInvcSmryLinesTable', <?php echo $sbmtdCallBackFunc; ?>);">Apply Selection</button>
                            <?php } ?>
                        </div>
                    </fieldset>
                </form>
            <?php
            } else if ($vwtyp == 5) {
                //get Item Details
                header("content-type:application/json");
                $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                if (strpos($srchFor, "%") === FALSE) {
                    $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                    $srchFor = str_replace("%%", "%", $srchFor);
                }
                $sbmtdItemID = isset($_POST['sbmtdItemID']) ? cleanInputData($_POST['sbmtdItemID']) : -1;
                $sbmtdStoreID = isset($_POST['sbmtdStoreID']) ? (int) cleanInputData($_POST['sbmtdStoreID']) : -1;
                $qCnsgnOnly = isset($_POST['qCnsgnOnly']) ? cleanInputData($_POST['qCnsgnOnly']) : "false";
                $sbmtdDocType = isset($_POST['sbmtdDocType']) ? cleanInputData($_POST['sbmtdDocType']) : "Sales Invoice";
                $sbmtdCstmrSiteID = isset($_POST['sbmtdCstmrSiteID']) ? (float) cleanInputData($_POST['sbmtdCstmrSiteID']) : -1;
                $qCnsgnOnlyB = ($qCnsgnOnly == "true") ? true : false;
                $sbmtdCallBackFunc = isset($_POST['sbmtdCallBackFunc']) ? cleanInputData($_POST['sbmtdCallBackFunc']) : 'function(){var a=1;}';
                $sbmtdRowIDAttrb = isset($_POST['sbmtdRowIDAttrb']) ? cleanInputData($_POST['sbmtdRowIDAttrb']) : '';

                if ($sbmtdStoreID <= 0 && $qCnsgnOnlyB == false) {
                    $sbmtdStoreID = $selectedStoreID;
                }
                $result = get_OneStoreItemDets(
                    $srchFor,
                    $orgID,
                    $sbmtdStoreID,
                    $sbmtdDocType,
                    $qCnsgnOnlyB,
                    $sbmtdItemID,
                    $sbmtdCstmrSiteID
                );

                if ($row = loc_db_fetch_array($result)) {
                    $arr_content['ln_ItmID'] = (float) $row[0];
                    $arr_content['ln_ItmNm'] = $row[2];
                    $arr_content['ln_UomID'] = (float) $row[18];
                    $arr_content['ln_UomNm'] = $row[19];
                    $arr_content['ln_CnsgnNo'] = (float) $row[11];
                    $arr_content['ln_AvlblQty'] = (float) $row[20];
                    $trnsLnRsvdQty = (float) $row[21];
                    $trnsLnTtlQty = (float) $row[22];
                    $arr_content['ln_SellPrice'] = (float) $row[3];
                    $arr_content['ln_CostPrice'] = (float) $row[12];
                    $trnsLnExpryDate = (float) $row[13];
                    $trnsLnStckID = (float) $row[5];
                    $trnsLnCtgryID = (float) $row[4];
                    $trnsLnCtgryNm = $row[23];
                    $arr_content['ln_StoreID'] = (float) $row[6];
                    $trnsLnStoreNm = $row[14];
                    $arr_content['ln_StoreNm'] = $trnsLnStoreNm;
                    $trnsLnShelves = $row[7];
                    $arr_content['ln_TaxID'] = (float) $row[8];
                    $trnsLnTaxNm = $row[15];
                    $arr_content['ln_DscntID'] = (float) $row[9];
                    $trnsLnDscntNm = $row[16];
                    $arr_content['ln_ChrgID'] = (float) $row[10];
                    $trnsLnChrgNm = $row[17];
                    $arr_content['ln_InvAcntID'] = (int) $row[24];
                    $arr_content['ln_CogsAcntID'] = (int) $row[25];
                    $arr_content['ln_SalesRevAcntID'] = (int) $row[26];
                    $arr_content['ln_SalesRetAcntID'] = (int) $row[27];
                    $arr_content['ln_PrchsRetAcntID'] = (int) $row[28];
                    $arr_content['ln_ExpnsAcntID'] = (int) $row[29];
                    $trnsLnItmType = $row[30];
                }
                $errMsg = "Success";
                $arr_content['message'] = "<span style=\"color:green;\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i></span>" . $errMsg;
                echo json_encode($arr_content);
                exit();
            } else if ($vwtyp == 500) {
            ?>
                <div class="row">
                    <!-- ROW 1 -->
                    <div class="col-lg-12">
                        <div class="row" id="allItmPymntPlansDetailInfo" style="padding:0px 15px 0px 15px !important">
                            <?php
                            $trnsStatus = "Incomplete";
                            $canAddItmPymntPlans = test_prmssns($dfltPrvldgs[8], $mdlNm);
                            $canEdtItmPymntPlans = test_prmssns($dfltPrvldgs[8], $mdlNm);
                            $canDelItmPymntPlans = test_prmssns($dfltPrvldgs[8], $mdlNm);

                            $searchAll = true;
                            $isEnabledOnly = false;
                            if (isset($_POST['isEnabled'])) {
                                $isEnabledOnly = cleanInputData($_POST['isEnabled']);
                            }

                            $srchFor = isset($_POST['searchfor']) ? cleanInputData($_POST['searchfor']) : '';
                            $srchIn = isset($_POST['searchin']) ? cleanInputData($_POST['searchin']) : 'Plan Name';
                            $pageNo = isset($_POST['pageNo']) ? cleanInputData($_POST['pageNo']) : 1;
                            $lmtSze = isset($_POST['limitSze']) ? cleanInputData($_POST['limitSze']) : 50;
                            $sortBy = isset($_POST['sortBy']) ? cleanInputData($_POST['sortBy']) : "Date Added DESC";
                            if (strpos($srchFor, "%") === FALSE) {
                                $srchFor = "%" . str_replace(" ", "%", $srchFor) . "%";
                                $srchFor = str_replace("%%", "%", $srchFor);
                            }

                            $itmID = isset($_POST['sbmtdItmPymntPlansITEMID']) ? cleanInputData($_POST['sbmtdItmPymntPlansITEMID']) : -1;
                            $sbmtdItmDesc = isset($_POST['sbmtdItmDesc']) ? cleanInputData($_POST['sbmtdItmDesc']) : '';


                            if ($itmID > 0) {
                                $total = getItmPymntPlansTblTtl($isEnabledOnly, $srchFor, $srchIn, $orgID, $searchAll, $itmID);

                                if ($pageNo > ceil($total / $lmtSze)) {
                                    $pageNo = 1;
                                } else if ($pageNo < 1) {
                                    $pageNo = ceil($total / $lmtSze);
                                }
                                $curIdx = $pageNo - 1;
                                $result2 = getItmPymntPlansTbl($isEnabledOnly, $srchFor, $srchIn, $curIdx, $lmtSze, $orgID, $searchAll, $sortBy, $itmID);
                            ?>
                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                    <legend class="basic_person_lg1" style="color: #003245">PAYMENT PLANS</legend>
                                    <?php
                                    if ($canEdtItmPymntPlans === true) {
                                        //$colClassType1 = "col-lg-2";
                                        $colClassType1 = "col-lg-6";
                                        $colClassType2 = "col-lg-3";
                                        $colClassType3 = "col-lg-4";
                                        $nwRowHtml = urlencode("<tr id=\"allItmPymntPlansRow__WWW123WWW\">"
                                            . "<td class=\"lovtd\"><span class=\"normaltd\">New</span></td>
                                                <td class=\"lovtd\">
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allItmPymntPlansRow_WWW123WWW_ItmPymntPlansID\" value=\"-1\" style=\"width:100% !important;\"> 
                                                    <input type=\"hidden\" class=\"form-control\" aria-label=\"...\" id=\"allItmPymntPlansRow_WWW123WWW_ItmID\" value=\"<?php echo $itmID; ?>\" style=\"width:100% !important;\">                                                                         
                                                    <input type=\"text\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansRow_WWW123WWW_PlanName\" name=\"allItmPymntPlansRow_WWW123WWW_PlanName\" value=\"\">                                                                        
                                                </td>
                                                <td class=\"lovtd\">
                                                    <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansRow_WWW123WWW_PlanPrice\" name=\"allItmPymntPlansRow_WWW123WWW_PlanPrice\" value=\"\">
                                                </td>                                             
                                                <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansRow_WWW123WWW_NoOfPymnts\" name=\"allItmPymntPlansRow_WWW123WWW_NoOfPymnts\" value=\"\">                                                               
                                                </td>
                                                <td class=\"lovtd\">
                                                        <input type=\"number\" min=\"0\" class=\"form-control rqrdFld\" aria-label=\"...\" id=\"allItmPymntPlansRow_WWW123WWW_InitDpst\" name=\"allItmPymntPlansRow_WWW123WWW_InitDpst\" value=\"\">                                                               
                                                </td>
                                                <td class=\"lovtd\">       
                                                    <select class=\"form-control\" aria-label=\"...\" id=\"allItmPymntPlansRow_WWW123WWW_IsEnbld\" name=\"allItmPymntPlansRow_WWW123WWW_IsEnbld\">
                                                            <option value=\"Yes\" selected>Yes</option>
                                                            <option value=\"No\" >No</option>														
                                                    </select>
                                                </td>
                                                <td class=\"lovtd\">
                                                    <button type=\"button\" class=\"btn btn-default\" style=\"margin: 0px !important;padding:0px 3px 2px 4px !important;\" onclick=\"deleteItmPymntPlans('allItmPymntPlansRow__WWW123WWW');\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Delete Plan\">
                                                            <img src=\"cmn_images/no.png\" style=\"height:15px; width:auto; position: relative; vertical-align: middle;\">
                                                    </button>
                                                </td>
                                            </tr>");
                                    ?>
                                        <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">
                                            <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="insertNewRowBe4('allItmPymntPlansTable', 0, '<?php echo $nwRowHtml; ?>');" data-toggle="tooltip" data-placement="bottom" title="Add Payment Plan">
                                                    <img src="cmn_images/add1-64.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;New Plan
                                                </button>
                                            <?php } ?>
                                        </div>
                                    <?php
                                    } else {
                                        $colClassType1 = "col-lg-3";
                                        $colClassType2 = "col-lg-6";
                                        $colClassType3 = "col-lg-6";
                                        /* $colClassType1 = "col-lg-3";
                                          $colClassType2 = "col-lg-3";
                                          $colClassType3 = "col-lg-3"; */
                                    }
                                    ?>
                                    <!--<div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                    <div class="input-group">
                    <input class="form-control" id="allItmPymntPlansSrchFor" type = "text" placeholder="Search For" value="<?php echo trim(str_replace("%", " ", $srchFor)); ?>" 
                    onkeyup="enterKeyFuncAllItmPymntPlans(event, '', '#allItmPymntPlansDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansITEMID=<?php echo $itmID; ?>');">
                    <input id="allItmPymntPlansPageNo" type = "hidden" value="<?php echo $pageNo; ?>">
                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItmPymntPlans('clear', '#allItmPymntPlansDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansITEMID=<?php echo $itmID; ?>');">
                    <span class="glyphicon glyphicon-remove"></span>
                    </label>
                    <label class="btn btn-primary btn-file input-group-addon" onclick="getAllItmPymntPlans('', '#allItmPymntPlansDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansITEMID=<?php echo $itmID; ?>');">
                    <span class="glyphicon glyphicon-search"></span>
                    </label> 
                    </div>
                    </div>
                    <div class="<?php echo $colClassType2; ?>" style="padding:0px 3px 0px 3px !important;">
                    <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-filter"></span></span>
                    <select data-placeholder="Select..." class="form-control chosen-select" id="allItmPymntPlansSrchIn">
                                    <?php
                                    $valslctdArry = array("");
                                    $srchInsArrys = array("Plan Name");

                                    for ($z = 0; $z < count($srchInsArrys); $z++) {
                                        if ($srchIn == $srchInsArrys[$z]) {
                                            $valslctdArry[$z] = "selected";
                                        }
                                    ?>
                                                <option value="<?php echo $srchInsArrys[$z]; ?>" <?php echo $valslctdArry[$z]; ?>><?php echo $srchInsArrys[$z]; ?></option>
                                    <?php } ?>
                    </select>
                    <span class="input-group-addon" style="max-width: 1px !important;padding:0px !important;width:1px !important;border:none !important;"></span>
                    <select data-placeholder="Select..." class="form-control chosen-select" id="allItmPymntPlansDsplySze" style="min-width:70px !important;">                            
                                    <?php
                                    $valslctdArry = array("", "", "", "", "", "", "", "");
                                    $dsplySzeArry = array(1, 5, 10, 15, 30, 50, 100, 500, 1000);
                                    for ($y = 0; $y < count($dsplySzeArry); $y++) {
                                        if ($lmtSze == $dsplySzeArry[$y]) {
                                            $valslctdArry[$y] = "selected";
                                        } else {
                                            $valslctdArry[$y] = "";
                                        }
                                    ?>
                                                    <option value="<?php echo $dsplySzeArry[$y]; ?>" <?php echo $valslctdArry[$y]; ?>><?php echo $dsplySzeArry[$y]; ?></option>                            
                                        <?php
                                    }
                                        ?>
                    </select>
                    </div>
                    </div>
                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 3px 0px 3px !important;">
                    <nav aria-label="Page navigation">
                    <ul class="pagination" style="margin: 0px !important;">
                    <li>
                    <a class="rhopagination" href="javascript:getAllItmPymntPlans('previous', '#allItmPymntPlansDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansITEMID=<?php echo $itmID; ?>');" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    </a>
                    </li>
                    <li>
                    <a class="rhopagination" href="javascript:getAllItmPymntPlans('next', '#allItmPymntPlansDetailInfo', 'grp=<?php echo $group; ?>&typ=<?php echo $type; ?>&pg=<?php echo $pgNo; ?>&vtyp=<?php echo $vwtyp; ?>&sbmtdItmPymntPlansITEMID=<?php echo $itmID; ?>');" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    </a>
                    </li>
                    </ul>
                    </nav>
                    </div>-->


                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdItmDesc" name="recCnt" value="<?php echo $sbmtdItmDesc; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="recCnt" name="recCnt" value="">
                                    <input type="hidden" class="form-control" aria-label="..." id="gnrlOrgID" name="gnrlOrgID" value="<?php echo $orgID; ?>">
                                    <input type="hidden" class="form-control" aria-label="..." id="sbmtdItmPymntPlansITEMID" name="sbmtdItmPymntPlansITEMID" value="<?php echo $itmID; ?>">
                                    <div class="<?php echo $colClassType1; ?>" style="padding:0px 1px 0px 3px !important;">
                                        <div style="float:right !important;">
                                            <button type="button" class="btn btn-default" style="margin-bottom: 5px;" onclick="saveItmPymntPlans();" data-toggle="tooltip" data-placement="bottom" title="Save Payment Plan(s)">
                                                <img src="cmn_images/FloppyDisk.png" style="height:20px; width:auto; position: relative; vertical-align: middle;">&nbsp;Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="padding:0px 15px 0px 15px !important">
                                    <div class="col-md-12" style="padding:0px 3px 0px 3px !important">
                                        <table class="table table-striped table-bordered table-responsive" id="allItmPymntPlansTable" cellspacing="0" width="100%" style="width:100%;min-width: 300px !important;">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Plan Name</th>
                                                    <th>Plan Price</th>
                                                    <th>No. Of Payments</th>
                                                    <th>Initial Deposit</th>
                                                    <th>Is Enabled</th>
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $cntr = 0;
                                                while ($row2 = loc_db_fetch_array($result2)) {
                                                    $cntr += 1;
                                                ?>
                                                    <tr id="allItmPymntPlansRow_<?php echo $cntr; ?>">
                                                        <td class="lovtd"><span><?php echo ($curIdx * $lmtSze) + ($cntr); ?></span></td>
                                                        <td class="lovtd">
                                                            <input type="hidden" class="form-control" aria-label="..." id="allItmPymntPlansRow<?php echo $cntr; ?>_ItmPymntPlansID" value="<?php echo $row2[0]; ?>" style="width:100% !important;">
                                                            <input type="hidden" class="form-control" aria-label="..." id="allItmPymntPlansRow<?php echo $cntr; ?>_ItmID" value="<?php echo $itmID; ?>" style="width:100% !important;">
                                                            <input type="text" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansRow<?php echo $cntr; ?>_PlanName" name="allItmPymntPlansRow<?php echo $cntr; ?>_PlanName" value="<?php echo $row2[1]; ?>" readonly="readonly">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="number" min="1" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansRow<?php echo $cntr; ?>_PlanPrice" name="allItmPymntPlansRow<?php echo $cntr; ?>_PlanPrice" value="<?php echo $row2[3]; ?>">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansRow<?php echo $cntr; ?>_NoOfPymnts" name="allItmPymntPlansRow<?php echo $cntr; ?>_NoOfPymnts" value="<?php echo $row2[2]; ?>">
                                                        </td>
                                                        <td class="lovtd">
                                                            <input type="number" min="0" class="form-control rqrdFld" aria-label="..." id="allItmPymntPlansRow<?php echo $cntr; ?>_InitDpst" name="allItmPymntPlansRow<?php echo $cntr; ?>_InitDpst" value="<?php echo $row2[5]; ?>">
                                                        </td>
                                                        <td class="lovtd">
                                                            <select class="form-control" aria-label="..." id="allItmPymntPlansRow<?php echo $cntr; ?>_IsEnbld" name="allItmPymntPlansRow<?php echo $cntr; ?>_IsEnbld">
                                                                <?php
                                                                $sltdYes = "";
                                                                $sltdNo = "";
                                                                if ($row2[4] == "Yes") {
                                                                    $sltdYes = "selected";
                                                                } else if ($row2[4] == "No") {
                                                                    $sltdNo = "selected";
                                                                }
                                                                ?>
                                                                <option value="Yes" <?php echo $sltdYes; ?>>Yes</option>
                                                                <option value="No" <?php echo $sltdNo; ?>>No</option>
                                                            </select>
                                                        </td>
                                                        <?php if ($trnsStatus == "Incomplete" || $trnsStatus == "Withdrawn" || $trnsStatus == "Rejected") { ?>
                                                            <td class="lovtd">
                                                                <button type="button" class="btn btn-default" style="margin: 0px !important;padding:0px 3px 2px 4px !important;" onclick="deleteItmPymntPlans('allItmPymntPlansRow_<?php echo $cntr; ?>', '<?php echo $row2[0]; ?>');" data-toggle="tooltip" data-placement="bottom" title="Delete Payment Plan">
                                                                    <img src="cmn_images/no.png" style="height:15px; width:auto; position: relative; vertical-align: middle;">
                                                                </button>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php
                            } else {
                            ?>
                                <span>No Results Found</span>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
<?php
            }
        }
    }
}
?>