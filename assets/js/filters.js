import noUiSlider from 'nouislider'
import 'nouislider/distribute/nouislider.css'

const slider = document.getElementById('price_slider');

if (slider) {
    const min = document.getElementById('min');
    const max = document.getElementById('max');

    if (slider.dataset.min != slider.dataset.max) {
        const minValue = Math.floor(parseFloat(slider.dataset.min));
        const maxValue = Math.ceil(parseFloat(slider.dataset.max));

        const range = noUiSlider.create(slider, {
            start: [min.value || minValue, max.value || maxValue],
            connect: true,
            step: 1,
            range: {
                'min': minValue,
                'max': maxValue
            }
        })

        range.on('slide', function (values, handle) {
            if (handle === 0) {
                min.value = Math.round(values[0]);
            }
            if (handle === 1) {
                max.value = Math.round(values[1]);
            }
        })
    }
    
}

;
