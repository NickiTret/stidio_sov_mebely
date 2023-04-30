import JustValidate from "just-validate";
import Inputmask from "inputmask";

const validate = new JustValidate("#form");
const inputMask = new Inputmask('+7 (999) 999-99-99');
const telSelector = document.querySelector('input[type="tel"]')
const secret = document.querySelector('#secret');
const btnSend = document.querySelector('.btn-send');

inputMask.mask(telSelector);

if(secret) {
  btnSend.addEventListener('click', () => {
    secret.value = 'secretkey';
  });
  
}


validate
  .addField("#name", [
    {
      rule: "minLength",
      value: 3,
      errorMessage: "Имя должно быть не меньше 3 символов...",
    },
    {
      rule: "maxLength",
      value: 30,
      errorMessage: "Имя должно быть не больше 30 символов...",
    },
    {
      rule: "required",
      errorMessage: "Имя обезательное поле...",
    },
  ])
  .addField("#tel", [
    {
      rule: "required",
      value: true,
      errorMessage: "Телефон обязателен",
    },
    {
      rule: "function",
      validator: function () {
        const phone = telSelector.inputmask.unmaskedvalue();
        return phone.length === 10;
      },
      errorMessage: "Введите корректный телефон",
    },
  ])
  .addField("#secret", [
    {
      rule: "required",
      value: true,
    },
  ])
  .addField("#email", [
    {
      rule: "required",
      errorMessage: "Email обезательное поле...",
    },
    {
      rule: "email",
      errorMessage: "Email введен не парвильно...",
    },
  ])
  .onSuccess((event) => {
    console.log("Validation passes and form submitted", event);

    let formData = new FormData(event.target);

    console.log(...formData);

    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          console.log("Отправлено");
        }
      }
    };

    xhr.open("POST", "./tel.php", true);
    xhr.send(formData);
    alert('Сообщение отправлено! Спасибо, мы с вами свяжемся.')
    event.target.reset();
    const close = document.querySelector('.btn-close').click();
  });
