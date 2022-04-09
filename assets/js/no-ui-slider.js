import nouislider from 'nouislider';
import 'nouislider/dist/nouislider.css';

let price_slider = document.getElementById('price-slider');
let price_monthly_slider = document.getElementById('price-monthly-slider');
let min_price = document.getElementById('min-price');
let max_price = document.getElementById('max-price');
let min_price_monthly = document.getElementById('min-price-monthly');
let max_price_monthly = document.getElementById('max-price-monthly');
let price_selected = document.getElementById('price-selected');
let total_price = document.querySelector('.filter-budget-choice-total');
let mounth_price = document.querySelector('.filter-budget-choice-mensuel');

nouislider.create(price_slider, {
    start: [price_slider.getAttribute('data-selected-min-price'), price_slider.getAttribute('data-selected-max-price')],
    tooltips: [true, true],
    format: {
        to: (value) => `${value.toFixed(0)} €`,
        from: (value) => Number(value.replace(' €', '')),
    },
    step: 1,
    connect: true,
    range: {
        'min': Number(price_slider.getAttribute('data-min-price')),
        'max': Number(price_slider.getAttribute('data-max-price'))
    }
});

price_slider.noUiSlider.on('update', function () {
    min_price.value = price_slider.noUiSlider.get()[0].replace(' €', '');
    max_price.value = price_slider.noUiSlider.get()[1].replace(' €', '');
});

nouislider.create(price_monthly_slider, {
    start: [price_monthly_slider.getAttribute('data-selected-min-price'), price_monthly_slider.getAttribute('data-selected-max-price')],
    tooltips: [true, true],
    format: {
        to: (value) => `${value.toFixed(0)} €`,
        from: (value) => Number(value.replace(' €', '')),
    },
    step: 1,
    connect: true,
    range: {
        'min': Number(price_monthly_slider.getAttribute('data-min-price')),
        'max': Number(price_monthly_slider.getAttribute('data-max-price'))
    }
});

price_monthly_slider.noUiSlider.on('update', function () {
    min_price_monthly.value = price_monthly_slider.noUiSlider.get()[0].replace(' €', '');
    max_price_monthly.value = price_monthly_slider.noUiSlider.get()[1].replace(' €', '');
});

total_price.addEventListener('click', () => {
    price_slider.classList.remove('hidden');
    min_price.setAttribute('name', 'min-price');
    max_price.setAttribute('name', 'max-price');
    price_monthly_slider.classList.add('hidden');
    min_price_monthly.removeAttribute('name');
    max_price_monthly.removeAttribute('name');
    total_price.style.backgroundColor = "#18495C";
    total_price.style.color = "#FFFFFF";
    mounth_price.style.backgroundColor = "transparent";
    mounth_price.style.color = "#18495C";
    price_selected.value = 1;
})

mounth_price.addEventListener('click', () => {
    price_monthly_slider.classList.remove('hidden');
    min_price_monthly.setAttribute('name', 'min-price-monthly');
    max_price_monthly.setAttribute('name', 'max-price-monthly');
    price_slider.classList.add('hidden');
    min_price.removeAttribute('name');
    max_price.removeAttribute('name');
    mounth_price.style.backgroundColor = "#18495C";
    mounth_price.style.color = "#FFFFFF";
    total_price.style.backgroundColor = "transparent";
    total_price.style.color = "#18495C";
    price_selected.value = 2;
})

if (Number(price_selected.value) === 2) {
    mounth_price.click();
}
