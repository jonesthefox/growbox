// noinspection JSUnusedGlobalSymbols

function disabler(from, to, option)
{
    for (let i = 0; i <= document.querySelectorAll('*[id^="position"]').length -1; i++) {
        let pos = "position[" + i + "]"
        if (i !== from) {
            if (document.getElementById('position[' + from + ']').getAttribute('previous') !== "") {
                let prev = document.getElementById('position[' + from + ']').getAttribute('previous')
                document.querySelectorAll('#' + CSS.escape(pos) + ' option').forEach(opt => { if (opt.value === prev)  { opt.disabled = false; } });
            }
            document.querySelectorAll('#' + CSS.escape(pos) + ' option').forEach(opt => { if (opt.value === option)  { opt.disabled = true; } });
        }
    }
}

function previous(id, value)
{
    if (value !== "")
    { document.getElementById(id).setAttribute('previous', value) }
}

function resetter()
{
    document.querySelectorAll('*[id^="position"] option').forEach(opt => { if (opt.value !== '') { opt.disabled = false; } });
}