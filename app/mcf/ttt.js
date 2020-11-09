/*****************************************
 (C) http://www.calculator.net all right reserved.
 *****************************************/
var bkm1 = new Array("http://digg.com/submit?phase=3&url=", "http://www.myspace.com/Modules/PostTo/Pages/?u=", "http://www.facebook.com/sharer.php?u=", "http://delicious.com/post?url=", "http://www.google.com/bookmarks/mark?op=add&bkmk=", "http://twitter.com/home?status=Viewing%20", "http://www.stumbleupon.com/submit?url=");
var bkm2 = new Array("&title=", "&t=", "", "&title=", "&title=", "&title=", "&title=");
function bkm() {
    var w = window, u = w.location.href, t = document.title, e;
    if ((e = w.sidebar)) {
        e.addPanel(t, u, "");
        return false;
    }
    if ((e = w.external)) {
        e.AddFavorite(u, t);
        return false;
    }
}
function shr(iN) {
    var w = window, u = w.location.href, t = document.title;
    window.location = bkm1[iN] + escape(u) + bkm2[iN] + escape(t);
    return false;
}
function gObj(obj) {
    var theObj;
    if (document.all) {
        if (typeof obj == "string") {
            return document.all(obj);
        } else {
            return obj.style;
        }
    }
    if (document.getElementById) {
        if (typeof obj == "string") {
            return document.getElementById(obj);
        } else {
            return obj.style;
        }
    }
    return null;
}
function trimAll(sString) {
    while (sString.substring(0, 1) == ' ') {
        sString = sString.substring(1, sString.length);
    }
    while (sString.substring(sString.length - 1, sString.length) == ' ') {
        sString = sString.substring(0, sString.length - 1);
    }
    return sString;
}
function isNumber(val) {
    val = val + "";
    if (val.length < 1)
        return false;
    if (isNaN(val)) {
        return false;
    } else {
        return true;
    }
}
function formatAsMoney(num) {
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + '$' + num + '.' + cents);
}
function formatNum(inNum) {
    outStr = "" + inNum;
    inNum = parseFloat(outStr);
    if ((outStr.length) > 10) {
        outStr = "" + inNum.toPrecision(10);
    }
    if (outStr.indexOf(".") > -1) {
        while (outStr.charAt(outStr.length - 1) == "0") {
            outStr = outStr.substr(0, (outStr.length - 1));
        }
        if (outStr.charAt(outStr.length - 1) == ".")
            outStr = outStr.substr(0, (outStr.length - 1));
        return outStr;
    } else {
        return outStr;
    }
}
function formatNum2(inNum, nlen, ncutoff) {
    inNum = parseFloat(inNum);
    inFloor = Math.floor(inNum);
    if (Math.abs(inNum - inFloor) < ncutoff) {
        return inFloor;
    } else {
        return inNum.toFixed(nlen);
    }
}
function showquickmsg(inStr, isError) {
    if (isError) {
        inStr = "<font color=red>" + inStr + "</font>";
    }
    gObj("coutput").innerHTML = inStr;
}
var MONTH_NAMES = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
var DAY_NAMES = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
function LZ(A) {
    return(A < 0 || A > 9 ? "" : "0") + A
}
function isDate(C, B) {
    var A = getDateFromFormat(C, B);
    if (A == 0) {
        return false
    }
    return true
}
function compareDates(E, F, C, D) {
    var B = getDateFromFormat(E, F);
    var A = getDateFromFormat(C, D);
    if (B == 0 || A == 0) {
        return -1
    } else {
        if (B > A) {
            return 1
        }
    }
    return 0
}
function formatDate(f, a) {
    a = a + "";
    var J = "";
    var T = 0;
    var e = "";
    var D = "";
    var I = f.getYear() + "";
    var F = f.getMonth() + 1;
    var b = f.getDate();
    var N = f.getDay();
    var L = f.getHours();
    var V = f.getMinutes();
    var P = f.getSeconds();
    var R, S, B, Q, g, C, Z, Y, W, O, j, L, i, G, A, X;
    var U = new Object();
    if (I.length < 4) {
        I = "" + (I - 0 + 1900)
    }
    U.y = "" + I;
    U.yyyy = I;
    U.yy = I.substring(2, 4);
    U.M = F;
    U.MM = LZ(F);
    U.MMM = MONTH_NAMES[F - 1];
    U.NNN = MONTH_NAMES[F + 11];
    U.d = b;
    U.dd = LZ(b);
    U.E = DAY_NAMES[N + 7];
    U.EE = DAY_NAMES[N];
    U.H = L;
    U.HH = LZ(L);
    if (L == 0) {
        U.h = 12
    } else {
        if (L > 12) {
            U.h = L - 12
        } else {
            U.h = L
        }
    }
    U.hh = LZ(U.h);
    if (L > 11) {
        U.K = L - 12
    } else {
        U.K = L
    }
    U.k = L + 1;
    U.KK = LZ(U.K);
    U.kk = LZ(U.k);
    if (L > 11) {
        U.a = "PM"
    } else {
        U.a = "AM"
    }
    U.m = V;
    U.mm = LZ(V);
    U.s = P;
    U.ss = LZ(P);
    while (T < a.length) {
        e = a.charAt(T);
        D = "";
        while ((a.charAt(T) == e) && (T < a.length)) {
            D += a.charAt(T++)
        }
        if (U[D] != null) {
            J = J + U[D]
        } else {
            J = J + D
        }
    }
    return J
}
function _isInteger(C) {
    var B = "1234567890";
    for (var A = 0; A < C.length; A++) {
        if (B.indexOf(C.charAt(A)) == -1) {
            return false
        }
    }
    return true
}
function _getInt(F, D, E, C) {
    for (var A = C; A >= E; A--) {
        var B = F.substring(D, D + A);
        if (B.length < E) {
            return null
        }
        if (_isInteger(B)) {
            return B
        }
    }
    return null
}
function getDateFromFormat(U, N) {
    U = U + "";
    N = N + "";
    var T = 0;
    var J = 0;
    var P = "";
    var E = "";
    var S = "";
    var G, F;
    var B = new Date();
    var H = B.getYear();
    var R = B.getMonth() + 1;
    var Q = 1;
    var C = B.getHours();
    var O = B.getMinutes();
    var L = B.getSeconds();
    var I = "";
    while (J < N.length) {
        P = N.charAt(J);
        E = "";
        while ((N.charAt(J) == P) && (J < N.length)) {
            E += N.charAt(J++)
        }
        if (E == "yyyy" || E == "yy" || E == "y") {
            if (E == "yyyy") {
                G = 4;
                F = 4
            }
            if (E == "yy") {
                G = 2;
                F = 2
            }
            if (E == "y") {
                G = 2;
                F = 4
            }
            H = _getInt(U, T, G, F);
            if (H == null) {
                return 0
            }
            T += H.length;
            if (H.length == 2) {
                if (H > 70) {
                    H = 1900 + (H - 0)
                } else {
                    H = 2000 + (H - 0)
                }
            }
        } else {
            if (E == "MMM" || E == "NNN") {
                R = 0;
                for (var M = 0; M < MONTH_NAMES.length; M++) {
                    var D = MONTH_NAMES[M];
                    if (U.substring(T, T + D.length).toLowerCase() == D.toLowerCase()) {
                        if (E == "MMM" || (E == "NNN" && M > 11)) {
                            R = M + 1;
                            if (R > 12) {
                                R -= 12
                            }
                            T += D.length;
                            break
                        }
                    }
                }
                if ((R < 1) || (R > 12)) {
                    return 0
                }
            } else {
                if (E == "EE" || E == "E") {
                    for (var M = 0; M < DAY_NAMES.length; M++) {
                        var K = DAY_NAMES[M];
                        if (U.substring(T, T + K.length).toLowerCase() == K.toLowerCase()) {
                            T += K.length;
                            break
                        }
                    }
                } else {
                    if (E == "MM" || E == "M") {
                        R = _getInt(U, T, E.length, 2);
                        if (R == null || (R < 1) || (R > 12)) {
                            return 0
                        }
                        T += R.length
                    } else {
                        if (E == "dd" || E == "d") {
                            Q = _getInt(U, T, E.length, 2);
                            if (Q == null || (Q < 1) || (Q > 31)) {
                                return 0
                            }
                            T += Q.length
                        } else {
                            if (E == "hh" || E == "h") {
                                C = _getInt(U, T, E.length, 2);
                                if (C == null || (C < 1) || (C > 12)) {
                                    return 0
                                }
                                T += C.length
                            } else {
                                if (E == "HH" || E == "H") {
                                    C = _getInt(U, T, E.length, 2);
                                    if (C == null || (C < 0) || (C > 23)) {
                                        return 0
                                    }
                                    T += C.length
                                } else {
                                    if (E == "KK" || E == "K") {
                                        C = _getInt(U, T, E.length, 2);
                                        if (C == null || (C < 0) || (C > 11)) {
                                            return 0
                                        }
                                        T += C.length
                                    } else {
                                        if (E == "kk" || E == "k") {
                                            C = _getInt(U, T, E.length, 2);
                                            if (C == null || (C < 1) || (C > 24)) {
                                                return 0
                                            }
                                            T += C.length;
                                            C--
                                        } else {
                                            if (E == "mm" || E == "m") {
                                                O = _getInt(U, T, E.length, 2);
                                                if (O == null || (O < 0) || (O > 59)) {
                                                    return 0
                                                }
                                                T += O.length
                                            } else {
                                                if (E == "ss" || E == "s") {
                                                    L = _getInt(U, T, E.length, 2);
                                                    if (L == null || (L < 0) || (L > 59)) {
                                                        return 0
                                                    }
                                                    T += L.length
                                                } else {
                                                    if (E == "a") {
                                                        if (U.substring(T, T + 2).toLowerCase() == "am") {
                                                            I = "AM"
                                                        } else {
                                                            if (U.substring(T, T + 2).toLowerCase() == "pm") {
                                                                I = "PM"
                                                            } else {
                                                                return 0
                                                            }
                                                        }
                                                        T += 2
                                                    } else {
                                                        if (U.substring(T, T + E.length) != E) {
                                                            return 0
                                                        } else {
                                                            T += E.length
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    if (T != U.length) {
        return 0
    }
    if (R == 2) {
        if (((H % 4 == 0) && (H % 100 != 0)) || (H % 400 == 0)) {
            if (Q > 29) {
                return 0
            }
        } else {
            if (Q > 28) {
                return 0
            }
        }
    }
    if ((R == 4) || (R == 6) || (R == 9) || (R == 11)) {
        if (Q > 30) {
            return 0
        }
    }
    if (C < 12 && I == "PM") {
        C = C - 0 + 12
    } else {
        if (C > 11 && I == "AM") {
            C -= 12
        }
    }
    var A = new Date(H, R - 1, Q, C, O, L);
    return A.getTime()
}
function parseDate(G) {
    var E = (arguments.length == 2) ? arguments[1] : false;
    generalFormats = new Array("y-M-d", "MMM d, y", "MMM d,y", "y-MMM-d", "d-MMM-y", "MMM d");
    monthFirst = new Array("M/d/y", "M-d-y", "M.d.y", "MMM-d", "M/d", "M-d");
    dateFirst = new Array("d/M/y", "d-M-y", "d.M.y", "d-MMM", "d/M", "d-M");
    var B = new Array("generalFormats", E ? "dateFirst" : "monthFirst", E ? "monthFirst" : "dateFirst");
    var F = null;
    for (var D = 0; D < B.length; D++) {
        var A = window[B[D]];
        for (var C = 0; C < A.length; C++) {
            F = getDateFromFormat(G, A[C]);
            if (F != 0) {
                return new Date(F)
            }
        }
    }
    return null
}
;
function showTopNav(navSection) {
    var outPut = '<div class="topNavAbs"><div class="topNavIn"><div class="topNavLeft" onClick="window.location=\'/\';">&nbsp;</div><div class="topNavRight">';
    outPut += '<span class="dropdown"><span class="dropbtn"';
    if (navSection == 1)
        outPut += ' style="background-color: #3e8e41;"';
    outPut += ' onClick="window.location=\'/financial-calculator.html\';">Financial</span><div class="dropdown-content" style="left:0;"><a href="/mortgage-calculator.html">Mortgage Calculator</a><a href="/loan-calculator.html">Loan Calculator</a><a href="/auto-loan-calculator.html">Auto Loan Calculator</a><a href="/interest-calculator.html">Interest Calculator</a><a href="/real-estate-calculator.html">Real Estate Calculator</a><a href="/take-home-pay-calculator.html">Take-Home-Pay Calculator</a><a href="/payment-calculator.html">Payment Calculator</a><a href="/retirement-calculator.html">Retirement Calculator</a><a href="/amortization-calculator.html">Amortization Calculator</a><a href="/investment-calculator.html">Investment Calculator</a><a href="/personal-loan-calculator.html">Personal Loan Calculator</a><a href="/inflation-calculator.html">Inflation Calculator</a><a href="/lease-calculator.html">Lease Calculator</a><a href="/finance-calculator.html">Finance Calculator</a><a href="/mortgage-payoff-calculator.html">Mortgage Payoff Calculator</a><a href="/refinance-calculator.html">Refinance Calculator</a><a href="/credit-card-calculator.html">Credit Card Calculator</a><a href="/budget-calculator.html">Budget Calculator</a><a href="/tax-calculator.html">Income Tax Calculator</a><a href="/financial-calculator.html">More &gt;&gt;</a></div></span>';
    outPut += '<span class="dropdown"><span class="dropbtn"';
    if (navSection == 2)
        outPut += ' style="background-color: #3e8e41;"';
    outPut += ' onClick="window.location=\'/weight-loss-calculator.html\';">Weight Loss</span><div class="dropdown-content" style="left:0;"><a href="/bmi-calculator.html">BMI Calculator</a><a href="/calorie-calculator.html">Calorie Calculator</a><a href="/body-fat-calculator.html">Body Fat Calculator</a><a href="/bmr-calculator.html">BMR Calculator</a><a href="/carbohydrate-calculator.html">Carbohydrate Calculator</a><a href="/ideal-weight-calculator.html">Ideal Weight Calculator</a><a href="/body-type-calculator.html">Body Type Calculator</a><a href="/army-body-fat-calculator.html">Army Body Fat Calculator</a><a href="/weight-watchers-points-calculator.html">Weight Watchers Points Calculator</a><a href="/weight-loss-calculator.html">More &gt;&gt;</a></div></span>';
    outPut += '<span class="dropdown"><span class="dropbtn"';
    if (navSection == 3)
        outPut += ' style="background-color: #3e8e41;"';
    outPut += ' onClick="window.location=\'/math-calculator.html\';">Math</span><div class="dropdown-content" style="left:0;"><a href="/scientific-calculator.html">Scientific Calculator</a><a href="/fraction-calculator.html">Fraction Calculator</a><a href="/percent-calculator.html">Percentage Calculator</a><a href="/time-calculator.html">Time Calculator</a><a href="/triangle-calculator.html">Triangle Calculator</a><a href="/volume-calculator.html">Volume Calculator</a><a href="/number-sequence-calculator.html">Number Sequence Calculator</a><a href="/math-calculator.html">More &gt;&gt;</a></div></span>';
    outPut += '<span class="dropdown"><span class="dropbtn"';
    if (navSection == 4)
        outPut += ' style="background-color: #3e8e41;"';
    outPut += ' onClick="window.location=\'/pregnancy-calculator.html\';">Pregnancy</span><div class="dropdown-content"><a href="/ovulation-calculator.html">Ovulation Calculator</a><a href="/due-date-calculator.html">Due Date Calculator</a><a href="/pregnancy-conception-calculator.html">Pregnancy Conception Calculator</a><a href="/pregnancy-weight-gain-calculator.html">Pregnancy Weight Gain Calculator</a><a href="/conception-calculator.html">Conception Calculator</a></div></span>';
    outPut += '<span class="dropdown"><span class="dropbtn"';
    if (navSection == 5)
        outPut += ' style="background-color: #3e8e41;"';
    outPut += ' onClick="window.location=\'/other-calculator.html\';">Other</span><div class="dropdown-content"><a href="/love-calculator.html">Love Calculator</a><a href="/ip-subnet-calculator.html">IP Subnet Calculator</a><a href="/gas-mileage-calculator.html">Gas Mileage Calculator</a><a href="/conversion-calculator.html">Conversion Calculator</a><a href="/gpa-calculator.html">GPA Calculator</a><a href="/grade-calculator.html">Grade Calculator</a><a href="/time-card-calculator.html">Time Card Calculator</a><a href="/time-zone-calculator.html">Time Zone Calculator</a><a href="/height-calculator.html">Height Calculator</a><a href="/gdp-calculator.html">GDP Calculator</a><a href="/concrete-calculator.html">Concrete Calculator</a><a href="/marriage-calculator.html">Marriage Calculator</a><a href="/age-calculator.html">Age Calculator</a><a href="/bra-size-calculator.html">Bra Size Calculator</a><a href="/fuel-cost-calculator.html">Fuel Cost Calculator</a><a href="/btu-calculator.html">BTU Calculator</a><a href="/roofing-calculator.html">Roofing Calculator</a><a href="/other-calculator.html">More &gt;&gt;</a></div></span>';
    outPut += '<span class="dropdown"><span class="dropbtn"';
    if (navSection == 6)
        outPut += ' style="background-color: #3e8e41;"';
    outPut += ' onClick="window.location=\'/calculators-for-your-site.html\';">For Your Site</span></span>';
    document.write(outPut);
}