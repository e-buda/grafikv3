function notification(name,color,time,willHide) {
    var id = uuidv4();
    const body = '<p class="title">'+name+'<span class="close" onclick="document.getElementById(`'+id+'`).remove()"><i class="bi bi-x-circle-fill"></i></span></p>'
    var notifi = document.createElement('div');
    notifi.id = id;
    notifi.style.backgroundColor = color;
    notifi.innerHTML = body;
    notifi.className = 'notification';
    document.getElementById('notification').appendChild(notifi)
    if(willHide) {
        setTimeout(
            function () {
                document.getElementById(id).remove()
            }, time
        )
    }
}
function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}
