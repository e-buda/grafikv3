mainDay = -1;
takt = true;
background = "rgba(0,0,0,0)"
actualActive = -1

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

        }, 400)
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
                document.getElementById('day' + data[x].day+"TypeName").innerHTML = types[y].name;
                document.getElementById('day' + data[x].day).style.backgroundColor = types[y].color;
            }
        }
    }
    document.getElementById("loadingBox").style.display = "none";
}

function setForActualDay(id) {
    var remover = id === -1;
    if (remover) {
        document.getElementById('day' + mainDay+"TypeName").innerHTML = "";
        if (actualActive !== -1) {
            document.getElementById(actualActive).classList.remove("active");
            outStyle(document.getElementById(actualActive))
        }
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
                var json = JSON.parse(this.responseText);
                if (json.ok === true) {
                    if (actualActive !== -1) {
                        document.getElementById(actualActive).classList.remove("active");
                        outStyle(document.getElementById(actualActive))
                    }
                    var isExist = false;
                    for (var x = 0; x < data.length; x++) {
                        if (data[x].day === mainDay) {
                            data[x].type = id;
                            isExist = true
                            for (var y = 0; y < types.length; y++) {
                                if (types[y].id === id) {
                                    document.getElementById('day' + mainDay+"TypeName").innerHTML = types[y].name;
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
                                document.getElementById('day' + mainDay+"TypeName").innerHTML = types[x].name;
                                background = types[x].color
                                actualActive = "selectOption" + id;
                                document.getElementById(actualActive).classList.add("active");

                            }
                        }

                    }
                    notification("Zapisano","rgba(128,255,129,0.5)",2000,true);
                } else {
                    for(var j = 0; j < json.errors.length; j++){
                        if(json.errors[j].inf!=="blocked"){
                            notification(json.errors[j].inf, "rgba(255,128,128)", 5000, true)
                        }
                        else {
                            var lockedBy = ""
                            for(var z = 0; z < json.errors[j].lockedBy.length; z++){
                                lockedBy += json.errors[j].lockedBy[z]
                                if(z !== json.errors[j].lockedBy.length-1){
                                    lockedBy += ", "
                                }
                            }
                            if(json.errors[j].isGroup){
                                notification("Grupa <b>" + json.errors[j].name + "</b> na Dzień <b>" + mainDay + "." + month + "." + year + "</b> zajęta przez: <b>" + lockedBy + "</b>", "rgba(255,128,128)", 4000, true)
                            }
                            else {
                                notification("Zmiana <b>" + json.errors[j].name + "</b> na Dzień <b>" + mainDay + "." + month + "." + year + "</b> zajęta przez: <b>" + lockedBy + "</b>", "rgba(255,128,128)", 4000, true)
                            }
                        }
                    }


                }

            }
        }
        xmlhttp.open("GET", "saveDay.php?day=" + mainDay + "&month=" + month + "&year=" + year + "&type=" + id, true);
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