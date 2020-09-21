document.addEventListener('readystatechange', () => {
    if (document.readyState !== 'complete') {
        return;
    }

    let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

    let feedbackForm = document.querySelector('form');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', feedbackFormHandler);
    }

    function feedbackFormHandler(e) {
        let form = e.target;

        form.querySelectorAll('.input_invalid').forEach(field => {
            field.classList.remove('input_invalid');
        });

        form.querySelectorAll('.form_error-message').forEach(msg => {
            form.removeChild(msg);
        })

        sendForm(e)
            .then(jsonResponse => {
                notify(jsonResponse.message, form);

            })
            .catch(jsonResponse => {
                notify(jsonResponse.message || 'Получен некооректный ответ от сервера',  form);

                let errors = jsonResponse.errors || null;

                if (errors instanceof Object) {
                    for (let fieldName in errors) {
                        let field = form.querySelector(`*[name="${fieldName}"`);
                        let errorMsg = errors[fieldName];

                        if (field) {
                            field.classList.add('input_invalid');

                            error = document.createElement('span');
                            error.classList.add('form_error-message');
                            error.innerHTML = errorMsg;
                            form.insertBefore(error, field.nextSibling);
                        }
                    }
                }
            });
    }

    async function validateRecaptcha() {
        return new Promise((resolve, reject) => {
            let recaptchaSiteKey = document.head.querySelector('meta[name="recaptcha-key"]').content;

            grecaptcha.ready(() => {
                grecaptcha.execute(recaptchaSiteKey, { action: 'sendFeedbackMessage' })
                    .then(token => resolve(token))
                    .catch(error => reject(error));
            });
        });
    }

    async function sendForm(e) {
        e.preventDefault();

        let form = e.target;
        let data = new FormData(form);

        if (typeof grecaptcha !== 'undefined') {
            validateRecaptcha()
                .then(token => data.append('recaptcha-token', token))
                .catch(error => {
                    console.error(error);
                    notify('Не удалось пройти анти-спам проверку', form);
                });
        }

        return new Promise((resolve, reject) => {
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: data
            }).then(response => {
                response.json()
                    .then(jsonData => {
                        response.ok ? resolve(jsonData) : reject(jsonData);
                    })
                    .catch(error => {
                        console.error(response);
                        console.error(error);
                        notify('Получен некорректный ответ от сервера', form);
                    });
            }).catch(error => {
                console.log(error);
                notify('Не удалось отправить запрос на сервер', form);
            });
        });
    }

    function notify(message, form) {
        let notifyElm = form.querySelector('.notify');
        notifyElm.innerHTML = message;
    }
});
