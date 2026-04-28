import JustValidate from "just-validate";
import Inputmask from "inputmask";

const contactForms = document.querySelectorAll(".js-contact-form");

contactForms.forEach((form, index) => {
  const nameInput = form.querySelector('[name="name"]');
  const telInput = form.querySelector('[name="tel"]');
  const emailInput = form.querySelector('[name="mail"]');
  const contentInput = form.querySelector('[name="content"]');
  const secretInput = form.querySelector('[name="secret"]');
  const submitButton = form.querySelector('button[type="submit"]');

  if (!nameInput || !telInput || !emailInput || !contentInput) {
    return;
  }

  const uid = `contact-form-${index + 1}`;
  const formId = form.id || uid;
  form.id = formId;
  form.setAttribute("novalidate", "novalidate");

  nameInput.id = nameInput.id || `${uid}-name`;
  telInput.id = telInput.id || `${uid}-tel`;
  emailInput.id = emailInput.id || `${uid}-email`;
  contentInput.id = contentInput.id || `${uid}-content`;

  if (secretInput) {
    secretInput.id = secretInput.id || `${uid}-secret`;
  }

  const inputMask = new Inputmask("+7 (999) 999-99-99");
  inputMask.mask(telInput);

  const validate = new JustValidate(`#${formId}`, {
    errorFieldCssClass: "is-invalid",
    errorLabelCssClass: "form-error-label",
    focusInvalidField: true,
    lockForm: true,
  });

  if (secretInput && submitButton) {
    submitButton.addEventListener("click", () => {
      secretInput.value = "secretkey";
    });
  }

  validate
    .addField(`#${nameInput.id}`, [
      {
        rule: "required",
        errorMessage: "Введите имя",
      },
      {
        rule: "minLength",
        value: 2,
        errorMessage: "Минимум 2 символа",
      },
      {
        rule: "maxLength",
        value: 60,
        errorMessage: "Слишком длинное имя",
      },
    ])
    .addField(`#${telInput.id}`, [
      {
        rule: "required",
        errorMessage: "Введите телефон",
      },
      {
        rule: "function",
        validator: function () {
          const phone = telInput.inputmask?.unmaskedvalue() ?? "";
          return phone.length === 10;
        },
        errorMessage: "Укажите телефон полностью",
      },
    ])
    .addField(`#${emailInput.id}`, [
      {
        rule: "required",
        errorMessage: "Введите email",
      },
      {
        rule: "email",
        errorMessage: "Проверьте формат email",
      },
    ]);

  validate.addField(`#${contentInput.id}`, [
    {
      rule: "function",
      validator: function () {
        const value = contentInput.value.trim();
        return value.length === 0 || value.length >= 5;
      },
      errorMessage: "Если пишете сообщение, добавьте хотя бы 5 символов",
    },
  ]);

  if (secretInput) {
    validate.addField(`#${secretInput.id}`, [
      {
        rule: "required",
        value: true,
      },
    ]);
  }

  validate.onSuccess((event) => {
    event.target.submit();
  });
});
