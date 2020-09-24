document.addEventListener('readystatechange', () => {
    if (document.readyState !== 'complete') {
        return;
    }

    let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

    let feedbackForm = document.querySelector('.form-feedback');
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', feedbackFormHandler);
    }

    function feedbackFormHandler(e) {
        let form = e.target;

        form.querySelectorAll('.form__input_invalid').forEach(field => {
            field.classList.remove('form__input_invalid');
        });

        form.querySelectorAll('.form__error-message').forEach(msg => {
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
                            field.classList.add('form__input_invalid');

                            error = document.createElement('span');
                            error.classList.add('form__error-message');
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
            try {
                let token = await validateRecaptcha();
                data.append('g-recaptcha-response', token);
            }
            catch (error) {
                console.error(error);
                return Promise.reject({ 'message': 'Не удалось пройти анти-спам проверку' });
            }
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
                        reject({ 'message': 'Получен некорректный ответ от сервера' });
                    });
            }).catch(error => {
                console.error(error);
                reject({ 'message': 'Не удалось пройти анти-спам проверку' });
            });
        });
    }

    function notify(message, form) {
        let notifyElm = form.querySelector('.form__notify');
        notifyElm.innerHTML = message;
    }
});
