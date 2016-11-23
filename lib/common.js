/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* this function used to show or hide the div element */
function displayHideDiv(shw, hde) {
    shw = shw || null;
    hde = hde || null;

    if (shw != null) {
        $("#" + shw).toggle(100);
    }

    if (hde != null) {
        $("#" + hde).toggle(100);
    }

}

function showModal(divName, divid) {
        $('#' + divName).modal('show');
        $('#' + divid).prop('disabled', false);
}

function popUp(url, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var sw = (screen.width * .60);
        var sh = (screen.height * .60);
        window.open(url, 'pop-up', 'width=' + sw + ', height=' + sh + ', top=' + top + ', left=' + left);
}