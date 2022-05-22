
/*!
* some javascript for the settings page.
* nightlength() calculates the night length by substracting day length ( in hours) from 24
* validlightnum() checks for range 0 - 255 in the rgbw led settings block. returns false if value is outside of the range
* validdaynum() checks for range 0 - 24 and returns false if vaulue is out of scope
*
* eventlisteners:
* DOMContentLoaded checks the url for a hash link and displays the corresponding settings block.
* calculateDayNightLength() intercepts sending the daylength form, calculates day / night length from hours to seconds, store the numbers in the appropriate input value and sends the form.
* calculateDuskDawn() intercepts sending the duskdawn form, calculates minutes to seconds, updates the input values accordingly and sends the form
* generateBrightness() intercepts sending the rgbwbrightness form, assembles a string (r, g, b, w) for brow and bloom, updates the value of 2
* hidden input fields and disables the other input fields, so that only needed data reaches the script.
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

    function validlightnum(a) {
    return !(a < 0 || a > 255);
}

    function validdaynum(a) {
    return !(a < 0 || a > 24);
}

    document.getElementById("daylength").addEventListener('submit', calculateDayNightLength);
    function calculateDayNightLength() {
        document.forms["daylength"]["light[night]"].disabled = false;
        document.forms["daylength"]["light[night]"].value = 24 - document.forms["daylength"]["light[day]"].value;
        document.forms["daylength"]["light[night]"].value = document.forms["daylength"]["light[night]"].value * 60 * 60;
        document.forms["daylength"]["light[day]"].value = document.forms["daylength"]["light[day]"].value * 60 * 60;
}

    document.getElementById("duskdawn").addEventListener('submit', calculateDuskDawn);
    function calculateDuskDawn() {
    document.getElementById("light[dim]").value = document.getElementById("light[dim]").value * 60;
}

    document.getElementById("rgbwbrightness").addEventListener('submit', generateBrightness);
    function generateBrightness() {
    document.getElementById("light[rgbw_grow]").value = document.getElementById("intensity_grow_r").value + "," + document.getElementById("intensity_grow_g").value + "," + document.getElementById("intensity_grow_b").value + "," + document.getElementById("intensity_grow_w").value;
    document.getElementById("light[rgbw_bloom]").value = document.getElementById("intensity_bloom_r").value + "," + document.getElementById("intensity_bloom_g").value + "," + document.getElementById("intensity_bloom_b").value + "," + document.getElementById("intensity_bloom_w").value;

    document.getElementById("intensity_grow_r").disabled = true;
    document.getElementById("intensity_grow_g").disabled = true;
    document.getElementById("intensity_grow_b").disabled = true;
    document.getElementById("intensity_grow_w").disabled = true;
    document.getElementById("intensity_bloom_r").disabled = true;
    document.getElementById("intensity_bloom_g").disabled = true;
    document.getElementById("intensity_bloom_b").disabled = true;
    document.getElementById("intensity_bloom_w").disabled = true;
}

