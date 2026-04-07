function toggleMenu(el){
    let parent = el.parentElement;

    document.querySelectorAll(".menu-group").forEach(g=>{
        if(g !== parent){
            g.classList.remove("active");
        }
    });

    parent.classList.toggle("active");
}

function updateDateTime(){
    let now = new Date();

    let date = now.toDateString();
    let time = now.toLocaleTimeString();
    let h = new Date().getHours();
let greet = (h<12)?"☀️ Good Morning":(h<18)?"🌤️ Good Afternoon":"🌙 Good Evening";


     document.getElementById("greet").innerHTML = greet;
    document.getElementById("date").innerHTML = "📅 " + date;
    document.getElementById("time").innerHTML = "⏰ " + time;
}

// run immediately
updateDateTime();

// update every second
setInterval(updateDateTime,1000);


function toggleProfile(){
    let p = document.getElementById("profileDropdown");
    p.style.display = (p.style.display==="block")?"none":"block";
}

function toggleDark(){
    document.body.classList.toggle("dark");

    // SAVE MODE
    if(document.body.classList.contains("dark")){
        localStorage.setItem("mode","dark");
    }else{
        localStorage.setItem("mode","light");
    }
}

// LOAD MODE
window.onload = function(){
    if(localStorage.getItem("mode")==="dark"){
        document.body.classList.add("dark");
    }
}



// Close dropdown when click outside
document.addEventListener("click", function(e){
    let profile = document.querySelector(".profile-box");
    let dropdown = document.getElementById("profileDropdown");

    if(!profile.contains(e.target)){
        dropdown.style.display = "none";
    }
});
