window.onload = shuffle;

function shuffle() {
    var ol = document.querySelector('ol');
    for (var i = ol.children.length; i >= 0; i--) {
        ol.appendChild(ol.children[Math.random() * i | 0]);
    }
}
