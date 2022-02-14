function overStyle(object, color) {
    if (!editable()) {
        return;
    }
    object.style.backgroundColor = color;
}

function outStyle(object) {
    if (!editable()) {
        return;
    }
    if (object.classList.contains("active")) {
        object.style.backgroundColor = '#63ff63';
        return
    }
    object.style.backgroundColor = 'white';
}

mainDay = -1;
takt = true;
background = "rgba(0,0,0,0)"
actualActive = -1

function choseDay(day) {
    if (editable) {
        if (mainDay > 0) {
            document.getElementById("day" + mainDay).style.backgroundColor = background;
        }
        if (actualActive !== -1) {
            document.getElementById(actualActive).classList.remove("active");
            outStyle(document.getElementById(actualActive))
        }
        background = "rgba(0,0,0,0)"
        mainDay = day;
        document.getElementById("selectedDay").innerText = day;
        for (var x = 0; x < data.length; x++) {
            if (data[x].day === mainDay) {
                for (var y = 0; y < types.length; y++) {
                    if (types[y].id === data[x].type) {
                        background = types[y].color;
                        actualActive = "selectOption" + types[y].id;
                        document.getElementById(actualActive).classList.add("active");
                        outStyle(document.getElementById(actualActive))
                    }
                }
            }
        }
    }

}

function init() {
    initCal();
    if (editable()) {
        var x = setInterval(function () {
            if (takt) {
                takt = false;
                document.getElementById('day' + mainDay).style.backgroundColor = "rgb(255,170,170)"
            } else {
                takt = true;

                document.getElementById('day' + mainDay).style.backgroundColor = background
            }

        }, 500)
    } else {
        document.getElementById("remover").style.display = "none";
        document.getElementById("fullDaySelectorInf").innerText = "Edycja Jest niemożliwa";
        for (var y = 0; y < types.length; y++) {
            document.getElementById('selectOption' + types[y].id).style.backgroundColor = types[y].color;
        }
    }
    for (var x = 0; x < data.length; x++) {
        for (var y = 0; y < types.length; y++) {
            if (types[y].id === data[x].type) {
                document.getElementById('day' + data[x].day).style.backgroundColor = types[y].color;
            }
        }
    }
    document.getElementById("loadingBox").style.display = "none";
}

function setForActualDay(id) {

    if (actualActive !== -1) {
        document.getElementById(actualActive).classList.remove("active");
        outStyle(document.getElementById(actualActive))
    }
    var remover = id === -1;
    if (remover) {
        for (var x = 0; x < data.length; x++) {
            if (data[x].day === mainDay) {
                arrayRemove(data, x)
                background = "rgb(0,0,0,0)";
            }
        }
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                if (this.responseText === "OK") {
                    var isExist = false;
                    for (var x = 0; x < data.length; x++) {
                        if (data[x].day === mainDay) {
                            data[x].type = id;
                            isExist = true
                            for (var y = 0; y < types.length; y++) {
                                if (types[y].id === id) {
                                    background = types[y].color
                                    actualActive = "selectOption" + id;
                                    document.getElementById(actualActive).classList.add("active");

                                }
                            }
                        }
                    }
                    if (!isExist) {
                        data.push({"day": mainDay, "type": id})
                        for (var x = 0; x < types.length; x++) {
                            if (types[x].id === id) {
                                background = types[x].color
                                actualActive = "selectOption" + id;
                                document.getElementById(actualActive).classList.add("active");

                            }
                        }

                    }
                } else {
                    for(var i = 0; i < types.length; i++){
                        if(types[i].id===id){
                            notification("Zmiana <b>"+types[i].name+"</b> na Dzień <b>" + mainDay + "." + month + "." + year + "</b> zajęta przez: <b>" + this.responseText+"</b>", "rgba(255,128,128)", 10000,false)
                        }
                    }

                }

            }
        }
        xmlhttp.open("GET", "isAvalible.php?day=" + mainDay + "&month=" + month + "&year=" + year + "&type=" + id, true);
        xmlhttp.send();


    }
}

function arrayRemove(arr, index) {
    for (let i = index; i < arr.length - 1; i++) {
        arr[i] = arr[i + 1];
    }
    arr.length -= 1;
    return arr;
}