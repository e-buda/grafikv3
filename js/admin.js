function createUser(name,surname,login,tempPass,workGroup,mail){

}
function deleteUser(id){

}
var id=-1;
function modifyUser(iid){
    id = iid;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'userEdit.php?action=GET&id='+iid, true);
    xhr.onload = function () {
        var json = JSON.parse(this.responseText);
        if(json.ok){
            document.getElementById("editionName").value = json.data[0].name;
            document.getElementById("editionSurname").value = json.data[0].surname;
            document.getElementById("editionLogin").value = json.data[0].user;
            document.getElementById("editionWorkGroup").value = json.data[0].workGroup;
            document.getElementById("editionMail").value = json.data[0].mail;
            document.getElementById("editionIsAdmin").checked = json.data[0].isAdmin;
            modifyUserModal()
        }
        else{
            var lstError = ""
            for(var i = 0; i < json.errors.length; i -= -1){
                lstError += json.errors[i].inf;
                if(json.errors.length-1!==i){
                    lstError +=", ";
                }
            }
            notification("Api connection Error:<b> "+lstError+"</b>","rgba(255,0,0,0.3)",4000,true)
        }
    };

    xhr.send(null)
}
function modifyUserModal(){
    document.getElementById("modifyUserModal").style.display = "flex"
}