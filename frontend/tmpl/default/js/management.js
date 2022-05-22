
/*!
* some javascript for the settings page.
* nightlength() calculates the night length by substracting day length ( in hours) from 24
* validdaynum() checks for range 0 - 24 and returns false if vaulue is out of scope
*
* eventlisteners:
* calculateDayNightLengthNew() does the same, for the newproject form
*/

function nightLength(daylength, origin) {
    let nightlength;
    if (origin === "daylength") {
        nightlength = document.forms["daylength"]["light[night]"];
        nightlength.value = 24 - daylength.value;
    } else if (origin === "newproject") {
        nightlength = document.forms["newproject"]["light[night]"];
        nightlength.value = 24 - daylength.value;
    }
}

function validdaynum(a) {
    return !(a < 0 || a > 24);
}

document.getElementById("newproject").addEventListener('submit', calculateDayNightLengthNew);
function calculateDayNightLengthNew() {
    document.forms["newproject"]["light[night]"].disabled = false;
    document.forms["newproject"]["light[night]"].value = 24 - document.forms["newproject"]["light[day]"].value;
    document.forms["newproject"]["light[night]"].value = document.forms["newproject"]["light[night]"].value * 60 * 60;
    document.forms["newproject"]["light[day]"].value = document.forms["newproject"]["light[day]"].value * 60 * 60;
}
