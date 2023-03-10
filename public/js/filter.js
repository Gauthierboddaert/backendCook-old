//open filter
const openFilter = document.getElementsByClassName("filterOpen");

openFilter[0].addEventListener("click", function(){
    document.getElementById("overlay").style.display = "flex";
});

//close filter
const closeIcon = document.getElementsByClassName("closefitlreRecipe");

closeIcon[0].addEventListener("click", function(){
    document.getElementById("overlay").style.display = "none";
});

