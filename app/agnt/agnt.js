function prepareAgnt(lnkArgs, htBody, targ, rspns)
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
        } else if (lnkArgs.indexOf("&pg=3&vtyp=0") !== -1)
        {
        } else if (lnkArgs.indexOf("&pg=5&vtyp=0") !== -1)
        {
        }
        htBody.removeClass("mdlloading");
    });
}