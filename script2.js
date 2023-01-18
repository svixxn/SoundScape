const prices = document.getElementsByClassName("sum");
const priceDiv = document.getElementById("generalPrice");
const generalPrice = document.getElementById("generalPriceAll");

getGeneralCost();

//Загальна сума товарів
function getGeneralCost(){
    let sum = 0;
    for (let i = 0; i < prices.length; i++) {
        sum += parseInt(prices[i].textContent);
    }
    priceDiv.innerHTML = sum + " грн";
    generalPrice.innerHTML = sum + 150 + " грн"
}

//Збільшення та зменшення товару в корзині
let cartQuan = document.getElementById("cartQuantity").innerHTML;
let newPrices = [];
for (let i = 0; i < cartQuan; i++) {
    const quanInput = document.getElementsByClassName(`quanInput${i}`)[0];
    let quanInputValue = parseInt(quanInput.value);
    const startCost = parseInt(prices[i].textContent);
    const inc = document.getElementsByClassName(`increm${i}`)[0];
    inc.addEventListener("click", () => {
        quanInputValue += 1;
        newPrices[i] = quanInputValue * startCost;
        quanInput.value = quanInputValue;
        prices[i].textContent = newPrices[i];
        getGeneralCost();
    })

    const dec = document.getElementsByClassName(`decrem${i}`)[0];
    dec.addEventListener("click", () => {
        if (quanInputValue >1) {
            quanInputValue -= 1;
            newPrices[i] = quanInputValue * startCost;
            quanInput.value = quanInputValue;
            prices[i].textContent = newPrices[i];
            getGeneralCost()
        }
    })
}