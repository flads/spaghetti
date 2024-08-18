<?php
$filename = $parsedQuery['file'];

require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$uri = $_SERVER['REQUEST_URI'];
$filePath = __DIR__ . '/../posts/' . str_replace('/post/', '', $filename) . '.md';
$file = file($filePath);

if (!$file || $filename[0] === '_') {
    header('Location: /404');
}

$post = [
    'title' => mb_substr($file[1], 8, -2),
    'date' => date_format(date_create(mb_substr($file[2], 6, -1)), 'd-m-Y'),
    'summary' => mb_substr($file[3], 10, -2),
    'draft' => mb_substr($file[4], 6, -1),
    'pinned' => mb_substr($file[5], 8, -1),
];

for ($i = 0; $i < 7; $i++) {
    unset($file[$i]);
}

$post['content'] = implode($file);
?>
<main class="edit-post">
    <div class="container">
        <div class="title">
            <h2 class="m-0">Edit Post</h2>
            <p class="m-0"><?php echo $post['date'] ?></p>
        </div>
        <span class="error general-error"></span>
        <form class="w-100" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input
                    id="title"
                    type="text"
                    name="title"
                    class="w-50"
                    placeholder="Enter title here"
                    data-old-value="<?php echo $post['title'] ?>"
                    value="<?php echo $post['title'] ?>">
                <span class="error title-error"></span>
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea id="summary" class="summary" name="summary" placeholder="Summarize what your post is about" maxlength="255"><?php echo $post['summary'] ?></textarea>
                <span class="error summary-error"></span>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" class="content" name="content" placeholder="Use your markdown skills here"><?php echo $post['content'] ?></textarea>
                <span class="error content-error"></span>
            </div>
            <div class="form-group">
                <button id="submit" class="submit" type="submit">Save</button>
            </div>
        </form>
    </div>
</main>

<script>
    const oldTitle = document.getElementsByName('title')[0].getAttribute('data-old-value')
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

    submitButton.onclick = async (event) => {
        try {
            errorFields.forEach(field => {
                document.querySelector(`span.${field}-error`).innerHTML = '';
            });

            event.preventDefault()

            const form = new FormData(document.querySelector('form'));

            form.set('oldTitle', oldTitle);

            const response = await fetch('/functions/editPost.php', {
                method: 'POST',
                body: form
            });

            const data = await response.json();

            if (response.status === 422) {
                data.errors.forEach(error => {
                    const span = document.querySelector(`span.${error.field}-error`);

                    span.innerHTML = error.message;
                });

                return;
            }

            window.location.href = `/post/${data.filename}`
        } catch (error) {
            document.querySelector(`span.general-error`).innerHTML = 'Oops! Something went wrong!';
        }
    };
</script>