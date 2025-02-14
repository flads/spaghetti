<?php
    $env = parse_ini_file(__DIR__ . '/../../.env');

    $websiteName = $env["WEBSITE_NAME"];
    $loginUrl = $env["LOGIN_URL"];
    $loginPassword = $env["PASSWORD"];
    $githubUser = $env["GITHUB_USER"];
    $linkedinUser = $env["LINKEDIN_USER"];
?>
<main class="settings">
    <div class="container">
        <div class="title">
            <h2 class="m-0">Settings</h2>
        </div>
        <span class="error general-error"></span>
        <form class="w-100" method="POST">
            <div class="form-group">
                <label for="websiteTitle">Website Title</label>
                <input
                    id="websiteTitle"
                    type="text"
                    name="website_title"
                    placeholder="Enter title here"
                    value="<?php echo $websiteName ?>"
                    maxlength="20"
                >
                <span class="error website-title-error"></span>
            </div>
            <div class="form-group">
                <label for="loginUrl">Login URL</label>
                <input
                    id="loginUrl"
                    type="text"
                    name="login_url"
                    placeholder="Enter login url here"
                    value="<?php echo $loginUrl ?>"
                    maxlength="20"
                >
                <span class="error login-url-error"></span>
            </div>
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input
                    id="loginPassword"
                    type="text"
                    name="login_password"
                    placeholder="Enter login password here"
                    value="<?php echo $loginPassword ?>"
                >
                <span class="note">Make a great password, it's your only security wall.</span>
                <span class="error login-password-error"></span>
            </div>
            <div class="title settings-socials">
                <h3>Socials</h3>
            </div>
            <div class="form-group">
                <label for="githubUser">Github User</label>
                <input
                    id="githubUser"
                    type="text"
                    name="github_user"
                    placeholder="Enter github user here"
                    value="<?php echo $githubUser ?>"
                >
                <span class="error github-user-error"></span>
            </div>
            <div class="form-group">
                <label for="linkedinUser">Linkedin User</label>
                <input
                    id="linkedinUser"
                    type="text"
                    name="linkedin_user"
                    placeholder="Enter linkedin user here"
                    value="<?php echo $linkedinUser ?>"
                >
                <span class="error linkedin-user-error"></span>
            </div>
            <div class="form-group">
                <button id="submit" class="submit" type="submit">Save</button>
            </div>
        </form>
    </div>
</main>

<script>
    const submitButton = document.querySelector("div.form-group button.submit");

    const errorFields = [
        'website-title',
        'login-url',
        'login-password',
        'github-user',
        'linkedin-user',
    ];

    document.addEventListener('keydown', (event) => {
        if (event.keyCode === 13 && event.ctrlKey) {
            submitButton.click();
        }
    });

    submitButton.onclick = async (event) => {
        try {
            errorFields.forEach(field => {
                document.querySelector(`span.${field}-error`).innerHTML = '';
            });

            event.preventDefault()

            const form = new FormData(document.querySelector('form'));

            const response = await fetch('/functions/updateSettings.php', {
                method: 'POST',
                body: form
            });

            if (response.status === 422) {
                const data = await response.json();

                data.errors.forEach(error => {
                    const span = document.querySelector(`span.${error.field}-error`);

                    span.innerHTML = error.message;
                });

                return;
            }

            window.location.href = `/${form.get('login_url')}/settings`;
        } catch (error) {
            document.querySelector(`span.general-error`).innerHTML = 'Oops! Something went wrong!';
        }
    };
</script>