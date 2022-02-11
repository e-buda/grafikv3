function overStyle(object,color){
    object.style.backgroundColor = color;
}
function outStyle(object){
    if(object.classList.contains("active")){
        object.style.backgroundColor = '#63ff63';
        return
    }
    object.style.backgroundColor = 'white';
}
mainDay = 1;
takt = true;
function choseDay(day){
    mainDay = day;
    document.getElementById("selectedDay").innerText = day;

}
setInterval(function (){
    if(takt){
        takt = false;
        document.getElementById('day'+mainDay).style.backgroundColor = "rgb(255,170,170)"
    }
    else{
        takt = true;
        document.getElementById('day'+mainDay).style.backgroundColor = "rgba(255,255,255,0)"
    }

},500)