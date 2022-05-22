<div id="_identity_" class="w3-card-4 w3-panel ##messageColor## w3-leftbar ##sidebarColor## w3-round-large" style="display: none;">
    <h3><i class="fa ##messageIcon##"></i> ##messageType##</h3>
    <p>##messageText##</p>
</div>
<!--suppress JSUnresolvedVariable -->
<script>
let _identity_ = document.getElementById('_identity_');
setTimeout(function(){ _identity_.style.display = 'block'; _identity_.classList.add('w3-animate-opacity');}, _timeout_);

document.getElementById('_identity_').addEventListener('click',function(){
    _identity_.classList.add('w3-animate-fadeout');})

_identity_.addEventListener('animationend', function() {
    if (this.classList.contains('w3-animate-fadeout')) {
        this.style.display = 'none';
        this.classList.remove('w3-animate-fadeout')}});

setTimeout(function(){ _identity_.classList.add('w3-animate-fadeout'); }, _timeoutfadeout_); // 10000ms = 10s

</script>
