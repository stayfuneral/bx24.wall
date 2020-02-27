window.onload = function() {
    const formValues = {
        diameter: [],
        width: [],
        materialType: [],
        holeCount: []
    };

    const formDataSelector = '.formData';
    const mainParams = document.getElementById('mainParams');    
    const holeArea = document.getElementById('holeArea');
    const holeRow = document.getElementById('holeRow'); 
    
    const addHole = document.getElementById('addHole');

    const reset = document.getElementById('reset');
    reset.addEventListener('click', () => {
        window.location.reload(true);
    })

    getValuesfromForm(['mainParams', 'holeRow'], formDataSelector);
    
    let Counter = 1;

    addHole.addEventListener('click', function() {
        let counter = Counter++;      
        const newRow = holeRow.cloneNode(true);
        let newId = newRow.id + counter
        newRow.setAttribute('id', newId)

        newRow.querySelectorAll('input').forEach(item => item.value = '');
        holeArea.append(newRow)
        getValuesfromForm(newId, formDataSelector);
    })

    const calc = document.getElementById('calc');
    calc.addEventListener('click', () => {
        // console.log(formValues);
        const CalcPrice = new Promise((resolve,reject) => {
            fetch('/app/api/app.php', {
                method: 'post',
                body: JSON.stringify(formValues)
            })
            .then(response => resolve(response.json()));
        });
        CalcPrice.then(data => {
            console.log(data);
            const result = document.getElementById('result');
            if(data.finalPrice !== 'Цена договорная') {
                    resultContent = '<h3 class="font-weight-bold">Общая стоимость заказа: <span class="text-primary">' + data.finalPrice + ' ₽</span></h3>';
            } else {
                resultContent = data.finalPrice;
            }
            
            result.innerHTML = resultContent;
            setTimeout(window.location.reload(true), 5000);
        })
    })
    
    function getValuesfromForm(ids, data) {
        switch(typeof(ids)) {
            case 'string':
                insertDataToFormValues(ids, data);
                break;
            case 'object':
                for(let i = 0; i < ids.length; i++) {
                    insertDataToFormValues(ids[i],data);
                }
                break;
        }
    }

    function insertDataToFormValues(elementId, data) {

        const Element = document.getElementById(elementId);
        const Data = Element.querySelectorAll(data);
        Data.forEach(item => {
            if(item.type === 'hidden' && item.name === 'dealId') {
                formValues[item.name] = Number(item.value);
            }
            item.addEventListener('change', () => {
                let name = item.name
                let search = name.indexOf('[]');
                if(search > 0) {
                    name = name.replace('[]', '')
                }
                switch(name) {
                    case 'diameter':
                    case 'width':
                    case 'materialType':
                    case 'holeCount':
                        formValues[name].push(Number(item.value));
                        break;                    
                    case 'transportCost':
                        formValues[name] = Number(item.value);
                        break;
                    case 'paymentType':
                        formValues[name] = item.value;
                        break;
                }
            });        
        })
    }
}