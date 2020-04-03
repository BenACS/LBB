let deleteButtons = document.querySelectorAll(".js-btn-delete");
let quantitySelectors = document.querySelectorAll('.js-quantity-selector');

let itemsBox = document.getElementsByClassName('item-box');

function getCartValue() {
    let totalValue = 0;
    for (let i=0 ; i<itemsBox.length ; i++) {
        let price = parseFloat(itemsBox[i].getElementsByClassName('article-price')[0].innerText);
        let quantity = parseInt(itemsBox[i].getElementsByClassName('js-quantity-selector')[0].value);
        totalValue += price*quantity;
    }
    js_totalPriceDF.innerText = totalValue.toFixed(2);
    let totalValueFinal = totalValue - parseFloat(js_discount.innerText);
    js_totalPriceVAT.innerText = totalValueFinal.toFixed(2);
}
getCartValue();

for (let i=0 ; i<deleteButtons.length ; i++) {
    deleteButtons[i].onclick = function(e) {
        e.preventDefault();

        let params = {
            "articleId":parseInt(deleteButtons[i].dataset.articleId)
        };

        const url = this.href;
        axios.post(url, params)
            .then(function(response) { 
                js_nbr_items.innerText = response.data.itemsInCart;
                document.getElementById('item_' + response.data.articleId).remove();
                if (document.querySelectorAll("[id^='item_']").length == 0) {
                    js_items_list.innerHTML = `<div>No registered items in the cart</div>`;
                    proceed_btn.setAttribute("disabled","disabled")
                }
                getCartValue();
            });
    }
}

for (let i=0 ; i<quantitySelectors.length ; i++) {
    quantitySelectors[i].onchange = function() {
        let params = {
            "articleId":parseInt(this.dataset.articleId),
            "quantity": parseInt(this.value)
        };

        const url = quantity_form.action;
        axios.post(url, params)
            .then(function(response) { 
                getCartValue();
            })
    } 
}