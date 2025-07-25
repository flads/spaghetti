<?php
$filename = $parsedQuery['file'];
$uri = $_SERVER['REQUEST_URI'];

if ($filename) {
    $filePath = __DIR__ . '/../posts/' . str_replace('/post/', '', $filename) . '.md';
    $fileExists = file_exists($filePath);
}

$isAboutPage = $filename === '_about';

$redirect = !$filename || !$fileExists || ($filename[0] === '_' && !$isAboutPage);
?>

<div id="redirect" data-redirect="<?php echo $redirect ?>"></div>
<div id="filename" data-filename="<?php echo $filename ?>"></div>

<script>
    const redirect = document.getElementById('redirect').getAttribute('data-redirect');

    if (redirect) window.location.href = '/404';
</script>


<?php
require(__DIR__ . '/../../libs/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();
$file = file($filePath);

$post = [
    'title' => mb_substr($file[1], 8, -2)
];

if ($file) {
    if (!$isAboutPage) {
        $post['date'] = date_format(date_create(mb_substr($file[2], 6, -1)), 'd-m-Y');
        $post['summary'] = mb_substr($file[3], 10, -2);
        $post['draft'] = mb_substr($file[4], 6, -1);
        $post['pinned'] = mb_substr($file[5], 8, -1);
    }
    
    for ($i = 0; $i < (count($post) + 2); $i++) {
        unset($file[$i]);
    }
    
    $post['content'] = implode($file);
}
?>
<main class="edit-post">
    <div class="container">
        <div class="title">
            <h2 class="m-0"><?php echo $isAboutPage ? 'Edit Page' : 'Edit Post' ?></h2>
            <?php if (!$isAboutPage) { ?>
                <div class="date">
                    <p class="m-0" id="displayedDate"><?php echo $post['date'] ?></p>
                    <?php if ($isLogged) { ?>
                        <i class="fa-solid fa-pencil pointer" id="editDate" title="Edit date"></i>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <span class="error general-error"></span>
        <form class="w-100" method="POST">
            <div class="hidden" id="dateFormGroup">
                <label for="date">Date</label>
                <input
                    id="date"
                    type="text"
                    name="date"
                    class="w-25"
                    placeholder="Enter date here"
                    data-old-value="<?php echo $post['date'] ?>"
                    value="<?php echo $post['date'] ?>"
                    maxlength="10">
                <span class="error date-error"></span>
            </div>
            <hr class="hidden" id="dateLine">
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
            <?php if (!$isAboutPage) { ?>
                <div class="form-group">
                    <label for="summary">Summary</label>
                    <textarea id="summary" class="summary" name="summary" placeholder="Summarize what your post is about" maxlength="255"><?php echo $post['summary'] ?></textarea>
                    <span class="error summary-error"></span>
                </div>
            <?php } ?>
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
    const displayedDate = document.getElementById('displayedDate');
    const editDate = document.getElementById('editDate');
    const dateFormGroup = document.getElementById('dateFormGroup');
    const dateLine = document.getElementById('dateLine');
    const filename = document.getElementById('filename').getAttribute('data-filename');
    const oldTitle = document.getElementsByName('title')[0].getAttribute('data-old-value');
    const submitButton = document.querySelector("div.form-group button.submit");

    const isAboutPage = filename === '_about';

    const errorFields = [
        'general',
        'title',
        'content'
    ];

    if (!isAboutPage) {
        errorFields.push('summary');
    }

    if (editDate) {
        editDate.onclick = (event) => {
            dateFormGroup.classList.remove('hidden');
            dateLine.classList.remove('hidden');
            dateFormGroup.classList.add('form-group');
            displayedDate.classList.add('hidden');
            editDate.classList.add('hidden');
        }
    }

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

            event.preventDefault();

            const form = new FormData(document.querySelector('form'));

            form.set('oldTitle', oldTitle);
            form.set('filename', filename);

            const response = await fetch('/services/EditPostService.php', {
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

            window.location.href = isAboutPage ?
                '/about' :
                `/post/${data.filename}`;
        } catch (error) {
            document.querySelector(`span.general-error`).innerHTML = 'Oops! Something went wrong!';
        }
    };
</script>