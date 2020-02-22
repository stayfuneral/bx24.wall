window.onload = function() {
   const formValues = {}
   const form = document.querySelectorAll('.formData');
   form.forEach(item => {
      if(item.type === 'hidden') {
         formValues.dealId = item.value;
      }
       const elem = document.getElementById(item.id);
       elem.addEventListener('change', function() {
           switch(item.type) {

               case 'number':
               case 'select-one':
                   formValues[elem.name] = elem.value
                   break;
               case 'checkbox':
                   formValues[elem.name] = elem.checked
                   break;
           }
       });
   });
   const btn = document.querySelector('button');
   btn.addEventListener('click', () => {
       const CalcPrice = new Promise((resolve, reject) => {
           fetch('/app/api/app.php', {
               method: 'post',
               body: JSON.stringify(formValues)
           })
           .then(response => {
               resolve(response.json())
           })
       })
       CalcPrice.then(data => {
           console.log(data)
           const result = document.getElementById('result');
           if(data.finalPrice !== 'Цена договорная') {
                resultContent = '<h3 class="font-weight-bold">Итого: <span class="text-primary">' + data.finalPrice + ' ₽</span></h3>';
           } else {
               resultContent = data.finalPrice;
           }
           
           result.innerHTML = resultContent;
       })
   })
}