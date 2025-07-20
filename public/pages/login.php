<main class="login">
    <div class="container">
        <form class="login w-100" action="" method="POST">
            <div class="form-group">
                <div>
                    <input id="password" name="password" class="w-50" type="password" placeholder="Password...">
                    <span class="error password-error"></span>
                </div>
                <button id="submit" class="submit" type="submit">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    const submitButton = document.querySelector("div.form-group button.submit");

    document.addEventListener('keydown', (event) => {
        if (event.keyCode === 13 && event.ctrlKey) {
            submitButton.click();;
        }
    });

    document.getElementById('password').focus();

    submitButton.onclick = async (event) => {
        const passwordSpan = document.querySelector(`span.password-error`);

        try {
            passwordSpan.innerHTML = '';

            event.preventDefault()

            const form = new FormData(document.querySelector('form'));

            const response = await fetch('/services/LoginService.php', {
                method: 'POST',
                body: form
            });

            if (response.status === 422) {
                const data = await response.json();

                passwordSpan.innerHTML = data.errors[0].message;

                return;
            }

            window.location.href = '/'
        } catch (error) {
            passwordSpan.innerHTML = 'Oops! Something went wrong!';
        }
    };
</script>