<?php
require(__DIR__ . '/../resources/php/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$filename = str_replace('/post/', '', $_SERVER['REQUEST_URI']);

$filePath = __DIR__ . '/../posts/' . $filename . '.md';
$file = file($filePath);

$post = [
    'filename' => $filename,
    'title' => mb_substr($file[1], 8, -2),
    'date' => date_format(date_create(mb_substr($file[2], 6, -1)), 'd-m-Y'),
    'summary' => $parsedown->text(mb_substr($file[3], 10, -2)),
    'draft' => mb_substr($file[4], 6, -1),
    'pinned' => mb_substr($file[5], 8, -1),
];

for ($i = 0; $i < 7; $i++) {
    unset($file[$i]);
}

$post['content'] = $parsedown->text(implode($file));
?>

<main class="post">
    <div class="container">
        <div class="post-header">
            <div class="title">
                <h2 class="m-0">
                    <?php echo $post['title'] ?>
                </h2>
                <p class="m-0"><?php echo $post['date'] ?></p>
            </div>
            <div class="post-actions">
                <?php if ($isLogged) { ?>
                    <i class="fa-solid fa-pencil" data-filename="<?php echo $post['filename'] ?>" title="Edit post"></i>
                    <i class="fa-solid fa-trash" data-filename="<?php echo $post['filename'] ?>" title="Delete post"></i>
                <?php } ?>
            </div>
        </div>
        <div class="content">
            <?php echo $post['content'] ?>
        </div>
    </div>
    <div class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <p>Do you really want to delete this post?</p>
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div class="modal-body">
                <p>Deleted files are moved to a "_trash" folder in the project.</p>
            </div>
            <div class="modal-footer">
                <button class="cancel">
                    Cancel
                </button>
                <button class="confirm">
                    Yes
                </button>
            </div>
        </div>
    </div>
</main>

<script>
    const editButton = document.querySelector("div.post-actions i.fa-pencil");
    const deleteButton = document.querySelector("div.post-actions i.fa-trash");
    const modal = document.querySelector("div.modal");
    const modalCloseButton = document.querySelector("div.modal i.fa-xmark");
    const modalCancelButton = document.querySelector("div.modal button.cancel");
    const modalConfirmButton = document.querySelector("div.modal button.confirm");

    let target;

    if (editButton) {
        editButton.onclick = (event) => {
            const filename = event.target.getAttribute('data-filename');

            window.location.href = `/admin/edit-post?file=${filename}`;
        }
    }

    if (deleteButton) {
        deleteButton.onclick = (event) => {
            target = event.target;

            modal.style.display = 'block';
        }
    }

    if (modal) {
        modalCloseButton.onclick = () => {
            modal.style.display = "none";
        }

        modalCancelButton.onclick = () => {
            modal.style.display = "none";
        }

        modalConfirmButton.onclick = () => {
            const filename = target.getAttribute('data-filename');

            fetch('/functions/deletePost.php?file_to_delete=' + filename, {
                method: 'GET'
            }).then(() => {
                window.location.href = '/'
            });
        }
    }

    if (modal) {
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                modal.style.display = 'none';
            }
        });
    }
</script>