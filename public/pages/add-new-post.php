<main class="add-new-post">
    <div class="container">
        <h2 class="m-0">Add New Post</h2>
        <span class="error general-error"></span>
        <form class="w-100" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input id="title" type="text" name="title" class="w-50" placeholder="Enter title here">
                <span class="error title-error"></span>
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea id="summary" class="summary" name="summary" placeholder="Summarize what your post is about" maxlength="255"></textarea>
                <span class="error summary-error"></span>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" class="content" name="content" placeholder="Use your markdown skills here"></textarea>
                <span class="error content-error"></span>
            </div>
            <div class="form-group">
                <button id="submit" class="submit" type="submit">Publish</button>
            </div>
        </form>
    </div>
</main>

<script>
    const submitButton = document.querySelector("div.form-group button.submit");

    const errorFields = [
        'general',
        'title',
        'summary',
        'content'
    ];

    document.addEventListener('keydown', (event) => {
        if (event.keyCode === 13 && event.ctrlKey) {
            submitButton.click();
        }
    });

    document.getElementById('title').focus();

    submitButton.onclick = async (event) => {
        try {
            errorFields.forEach(field => {
                document.querySelector(`span.${field}-error`).innerHTML = '';
            });

            event.preventDefault();

            const form = new FormData(document.querySelector('form'));

            const response = await fetch('/services/AddNewPostService.php', {
                method: 'POST',
                body: form
            });

            const data = await response.json();

            if (response.status === 422) {
                data.errors.forEach(error => {
                    document.querySelector(`span.${error.field}-error`).innerHTML = error.message;
                });
                return;
            }

            if (response.status === 500) {
                throw new Error(data.errors[0].message);
            }

            window.location.href = '/';
        } catch (error) {
            document.querySelector(`span.general-error`).innerHTML = 'Oops! Something went wrong!';
        }
    };
</script>