let choice = document.querySelectorAll('.filter-title');
let checkbox = document.querySelectorAll('.checkbox');
let select_sort = document.getElementById('select-sort');
let form_sort = document.getElementById('sort');
let form_direction = document.getElementById('direction');

choice.forEach((el) => {
    el.addEventListener('click', () => {
        el.classList.toggle('opened');
    });
    if (el.getAttribute('data-opened') === '1') {
        el.classList.toggle('opened');
    }
})

checkbox.forEach((el) => {
   el.onchange = function () {
        el.closest('.checkbox-div').classList.toggle('unactive');
    }
})

select_sort.addEventListener('change', () => {
    let select_sort_value = select_sort.value.split('-');
    form_sort.value = select_sort_value[0];
    form_direction.value = select_sort_value[1];
});
