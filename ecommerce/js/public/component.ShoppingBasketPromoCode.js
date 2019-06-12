window.ShoppingBasketPromoCodeComponent = function(componentElement) {
    var formElement;
    var codeInputElement;
    var submitButton;
    var statusElement;
    var titleElement;
    var resetButton;
    var init = function() {
        if (formElement = componentElement.querySelector('.shoppingbasket_promocode_form')) {
            if (codeInputElement = formElement.querySelector('.shoppingbasket_promocode_input')) {
                if (submitButton = formElement.querySelector('.shoppingbasket_promocode_button')) {
                    submitButton.addEventListener('click', submitClickHandler);
                }
            }
        }
        if (statusElement = componentElement.querySelector('.shoppingbasket_promocode_status')) {
            titleElement = statusElement.querySelector('.shoppingbasket_promocode_status_title');

            if (resetButton = statusElement.querySelector('.shoppingbasket_promocode_status_reset')) {
                resetButton.addEventListener('click', resetClickHandler);

            }
        }
        controller.addListener('shoppingBasketUpdated', updateData);
        updateData();
    };
    var submitClickHandler = function() {
        var promoCode = codeInputElement.value;
        if (promoCode) {
            shoppingBasketLogics.setPromoCode(promoCode);
        }
    };
    var resetClickHandler = function() {
        shoppingBasketLogics.setPromoCode('');
    };
    var updateData = function() {
        var discount = shoppingBasketLogics.getPromoCodeDiscount();
        if (discount) {
            formElement.style.display = 'none';
            statusElement.style.display = 'block';
            titleElement.innerHTML = discount.title;
        } else {
            formElement.style.display = '';
            statusElement.style.display = 'none';
            titleElement.innerHTML = '';
        }
    };
    init();
};
