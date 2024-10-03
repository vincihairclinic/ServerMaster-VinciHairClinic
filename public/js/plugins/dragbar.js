var left = document.getElementById('drag-left');
var right = document.getElementById('drag-right');
var bar = document.getElementById('dragbar');

var drag = function (e) {
    document.selection ? document.selection.empty() : window.getSelection().removeAllRanges();
    left.style.width = (e.pageX - bar.offsetWidth / 2) + 'px';
};

bar.addEventListener('mousedown', function (){
    document.addEventListener('mousemove', drag);
});

bar.addEventListener('mouseup', function (){
    document.removeEventListener('mousemove', drag);
});