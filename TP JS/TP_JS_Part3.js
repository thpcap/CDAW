"use strict";

function modify(e)
{
    let nodes = Array.from(e.currentTarget.parentNode.childNodes);
    let id = e.currentTarget.parentNode.id;
    myForm.elements["id"].value=id;
    nodes.forEach(element => {
        if(element.nodeName == "P") {
            myForm.elements["text"].value = element.textContent;
        }
    });
    Array.from(myForm.elements).forEach(element=>element.setAttribute("disabled,true"));
    myForm.style.display='block';
}

function deleter(e)
{
    e.currentTarget.parentNode.parentNode.removeChild(e.currentTarget.parentNode);
}

document.getElementById("addNew").addEventListener("click", function(e) {
    let usersDiv = document.getElementById("users");
    let lastUser = usersDiv.lastElementChild;
    let id = parseInt(lastUser.id.replace("user", ""), 10) + 1; // Extract the numeric part and increment by 1
    

    let newUserDiv = document.createElement("div");
    newUserDiv.id = "user" + id;
    newUserDiv.innerHTML = `
        <h4>New User ${id}</h4>
        <p>New comment for user ${id}.</p>
        <button class="modify">Modify Comment</button>
        <button class="remove">Remove Comment</button>
    `;

    usersDiv.appendChild(newUserDiv);

    // Add event listeners to the new buttons
    newUserDiv.querySelector(".modify").addEventListener("click", modify);
    newUserDiv.querySelector(".remove").addEventListener("click", deleter);
});

let modifiers = document.getElementsByClassName("modify");
Array.from(modifiers).forEach(m => m.addEventListener("click",modify));

let remover = document.getElementsByClassName("remove");
Array.from(remover).forEach(m => m.addEventListener("click",deleter));

function myFormFunction(e){
    if(myForm.elements["text"].value=="" && myForm.elements["id"].value==""){
        e.preventDefault();
        alert("Commentaire non valide");
    }else{
        let id = myForm.elements["id"].value;
        alert("modification du commentaire de l'user "+id);
        document.getElementById(id).querySelector("p").innerHTML= myForm.elements["text"].value;
        e.preventDefault();
        myForm.reset();
        myForm.style.display='none';
        Array.from(myForm.elements).forEach(element=>element.setAttribute("disabled,true"));
    }
}

let myForm=document.forms["myForm"];
myForm.addEventListener("submit",myFormFunction);