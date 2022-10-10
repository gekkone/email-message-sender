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

        clearFailedField(form);

        if (!(new FormData(form)).get('content').trim().length) {
            selectFailedField(form, 'content', 'Текст сообщения не может быть пустым');
            e.preventDefault();
            return;
        }

        sendForm(e)
            .then(data => {
                let message = data.json.message || 'Получен некорректный ответ от сервера';
                let response = data.response;

                if (response != null && response.headers.has('retry-after')) {
                    let available = new Date();
                    available.setSeconds(available.getSeconds()
                        + Number.parseInt(response.headers.get('retry-after'))
                    );

                    message += "<br>Повторите попытку после<br>" + available.toLocaleString();
                }

                notify(message, form);
                let errors = data.json.errors || null;

                if (errors instanceof Object) {
                    for (let fieldName in errors) {
                        selectFailedField(form, fieldName, errors[fieldName]);
                    }
                }
            })
            .catch(errorMessage => {
                notify(errorMessage, form);
            });
    }

    async function validateRecaptcha() {
        return new Promise((resolve, reject) => {
            let recaptchaSiteKey = document.head.querySelector('meta[name="recaptcha-key"]').content;

            grecaptcha.ready(() => {
                grecaptcha.execute(recaptchaSiteKey, {action: 'sendFeedbackMessage'})
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
            } catch (error) {
                console.error(error);
                return Promise.reject('Не удалось пройти анти-спам проверку');
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
                        resolve({json: jsonData, response: response})
                    })
                    .catch(error => {
                        console.error(response);
                        console.error(error);
                        reject('Получен некорректный ответ от сервера');
                    });
            }).catch(error => {
                console.error(error);
                reject( 'Не удалось пройти анти-спам проверку');
            });
        });
    }

    function notify(message, form) {
        let notifyElm = form.querySelector('.form__notify');
        notifyElm.innerHTML = message;
    }

    function selectFailedField(form, fieldName, errMsg) {
        let field = form.querySelector(`*[name="${fieldName}"`);

        if (field) {
            field.classList.add('form__input_invalid');

            let error = document.createElement('span');
            error.classList.add('form__error-message');
            error.innerHTML = errMsg;
            form.insertBefore(error, field.nextSibling);
        }
    }

    function clearFailedField(form) {
        form.querySelectorAll('.form__input_invalid').forEach(field => {
            field.classList.remove('form__input_invalid');
        });

        form.querySelectorAll('.form__error-message').forEach(msg => {
            form.removeChild(msg);
        });
    }
});
