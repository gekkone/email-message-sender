document.addEventListener('readystatechange', () => {
    if (document.readyState !== 'complete') {
        return;
    }

    let forms = document.querySelectorAll('form');
    forms.forEach((form) => {
        form.addEventListener('submit', sendForm);
    })

    function sendForm(e) {
        let token = document.head.querySelector('meta[name="csrf-token"]').content;
        let form = e.currentTarget;
        let data = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: data
        })
        .then(response => {
            response.json()
                .then(data => {
                    if (response.ok) {
                        notify(data.message);

                        form.querySelectorAll('.input__invalid').forEach(field => {
                            field.classList.remove('input__invalid');
                        });

                        form.querySelectorAll('.form_error-message').forEach(msg => {
                            form.removeChild(msg);
                        })
                    }
                    else {
                        notify(data.message || 'Получен некооректный ответ от сервера');

                        let errors = data.errors || null;

                        if (errors instanceof Object) {
                            for (let fieldName in errors) {
                                let field = form.querySelector(`*[name="${fieldName}"`);
                                let errorMsg = errors[fieldName];

                                if (field) {
                                    field.classList.add('input__invalid');

                                    error = document.createElement('span');
                                    error.classList.add('form_error-message');
                                    error.innerHTML = errorMsg;
                                    form.insertBefore(error, field);
                                }
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                    console.error(response);

                    notify('Получен некорректный ответ от сервера');
                });
        })
        .catch(error => {
            console.log(error);
            notify('При отправке сообщения произошла ошибка');
        });

        e.preventDefault();
    }

    function notify(message) {
        let notifyElm = document.querySelector('div.notify');
        notifyElm.innerHTML = message;
    }
});
