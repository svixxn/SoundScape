//Створення CKEditor
let editors = document.querySelectorAll('.editor');
for (let i in editors) {
    ClassicEditor
        .create(editors[i])
        .catch(error => {
            console.error(error);
        });
}


//Отриматння поточного року
const d = new Date();
let year = d.getFullYear();
var spanYear = document.getElementById("year");
spanYear.innerHTML = year;


//Додавання класу active до посилань навбара
let links = document.getElementsByClassName("nav-main");
let url = window.location.href.split('?')[0];
for (let i = 0; i < links.length; i++) {
   let link = links[i].href.split('?')[0];
    if(url === link){
        links[i].classList.add("active");
    }
}

//Додавання класу active до посилань pagination
let pagLinks = document.getElementsByClassName("page-link");
for (let i = 0; i < pagLinks.length; i++) {
    if(window.location.href === pagLinks[i].href){
        pagLinks[i].classList.add("active");
    }
}


//Створення слайдера
var priceSlider = document.getElementById('priceSlider');
noUiSlider.create(priceSlider, {
    start: [50000, 200000],
    connect: true,
    range: {
        'min': 1000,
        'max': 400000
    }
});

//Відображення у інпутах значення слайдера
const minPriceInput = document.getElementById("minPrice");
const maxPriceInput = document.getElementById("maxPrice");
priceSlider.noUiSlider.on("update", () => {
    const values = priceSlider.noUiSlider.get();
    minPriceInput.value = parseInt(values[0]);
    maxPriceInput.value = parseInt(values[1]);
});

[minPriceInput, maxPriceInput].forEach(() => {
    addEventListener("change", () => {
        priceSlider.noUiSlider.set([minPriceInput.value, maxPriceInput.value])
    })
});


///Функції///


//Задання посилання для сортування за ціною
function setLink() {
    let priceLink = document.getElementById("priceSortLink");
    const values = priceSlider.noUiSlider.get();
    const value1 = parseInt(values[0]);
    const value2 = parseInt(values[1]);
    document.cookie = `value1=${value1}`;
    document.cookie = `value2=${value2}`;
    priceLink.href += `startPrice=${value1}&endPrice=${value2}`
}
function setLinks(i){
    let pagPriceLink = document.getElementsByClassName("page-link-cost");
    let cookies = document.cookie.split("; ");
    const value1 = cookies[0].split("=")[1];
    const value2 = cookies[1].split("=")[1];
    pagPriceLink[i-1].href=`/goods/sort?page=${i}&startPrice=${value1}&endPrice=${value2}`
}
function setLinkForSides(i){

}










