window.onload = function() {
   const formValues = {}
   const form = document.querySelectorAll('.formData');
   form.forEach(item => {
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
           fetch('tst.fetch.php', {
               method: 'post',
               body: JSON.stringify(formValues)
           })
           .then(response => {
               resolve(response.json())
           })
       })
       CalcPrice.then(data => {
           console.log(data)
       })
   })
}