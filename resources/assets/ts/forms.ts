// Fetch all the forms we want to apply custom Bootstrap validation styles to
const forms = document.querySelectorAll<HTMLFormElement>(
    "form.needs-validation"
);

// Loop over them and prevent submission
forms.forEach((form) =>
    form.addEventListener(
        "submit",
        (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add("was-validated");
        },
        false
    )
);
