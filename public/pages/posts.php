<?php $redirect = $path === "/$loginUrl" ?>

<div id="redirect" data-redirect="<?php echo $redirect ?>"></div>

<script>
    const redirect = document.getElementById('redirect').getAttribute('data-redirect');

    if (redirect) window.location.href = '/';
</script>

<?php
require(__DIR__ . '/../../libs/parsedown-1.7.4/Parsedown.php');

$parsedown = new Parsedown();

$filenames = array_diff(scandir(__DIR__ . '/../posts/'), array('..', '.'));
$postsFilenames = array_filter($filenames, fn($filename) => $filename[0] !== '_');

$posts = array_map(function ($filename) use ($parsedown) {
    $filePath = __DIR__ . '/../posts/' . $filename;
    $file = file($filePath);

    return [
        'filename' => str_replace('.md', '', $filename),
        'title' => mb_substr($file[1], 8, -2),
        'date' => mb_substr($file[2], 6, -1),
        'formattedDate' => date_format(date_create(mb_substr($file[2], 6, -1)), 'Y-m-d'),
        'summary' => $parsedown->text(mb_substr($file[3], 10, -2)),
        'draft' => mb_substr($file[4], 6, -1),
        'pinned' => mb_substr($file[5], 8, -1),
    ];
}, $postsFilenames);

usort($posts, function ($a, $b) {
    if ($a['pinned'] == $b['pinned']) {
        return $b['date'] <=> $a['date'];
    }

    return $b['pinned'] <=> $a['pinned'];
});
?>

<main class="posts">
    <div class="container">
        <div class="page-header">
            <?php if ($isLogged) { ?>
                <button class="new-post">
                    New Post
                </button>
            <?php } ?>
        </div>
        <ul>
            <?php foreach ($posts as $post) { ?>
                <li>
                    <div class="post-header">
                        <div class="post-title">
                            <div>
                                <h2 class="m-0">
                                    <a href="/post/<?php echo $post['filename'] ?>">
                                        <?php echo $post['title'] ?>
                                    </a>
                                </h2>
                                <p class="m-0"><?php echo $post['formattedDate'] ?></p>
                            </div>
                            <?php if ($isLogged) { ?>
                                <i
                                    class="fa-solid fa-thumbtack pointer <?php if ($post['pinned'] === 'false') echo "disabled" ?>"
                                    data-filename="<?php echo $post['filename'] ?>"
                                >
                                </i>
                            <?php } ?>
                            <?php if (!$isLogged && $post['pinned'] === 'true') { ?>
                                <i class="fa-solid fa-thumbtack"></i>
                            <?php } ?>
                        </div>
                        <div class="post-actions">
                            <?php if ($isLogged) { ?>
                                <i class="fa-solid fa-pencil" data-filename="<?php echo $post['filename'] ?>" title="Edit post"></i>
                                <i class="fa-solid fa-trash" data-filename="<?php echo $post['filename'] ?>" title="Delete post"></i>
                            <?php } ?>
                        </div>
                    </div>
                    <?php echo $post['summary'] ?>
                </li>
            <?php } ?>
            <?php if (empty($posts)) { ?>
                <p>No posts yet ...</p>
            <?php } ?>
        </ul>
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
    const loginUrl = document.querySelector('html').getAttribute('data-login-url');
    const pinButtons = document.querySelectorAll('div.post-title i.fa-thumbtack[data-filename]');
    const addNewPostButton = document.querySelector("button.new-post");
    const editButtons = document.querySelectorAll("div.post-actions i.fa-pencil");
    const deleteButtons = document.querySelectorAll("div.post-actions i.fa-trash");
    const modal = document.querySelector("div.modal");
    const modalCloseButton = document.querySelector("div.modal i.fa-xmark");
    const modalCancelButton = document.querySelector("div.modal button.cancel");
    const modalConfirmButton = document.querySelector("div.modal button.confirm");

    if (pinButtons) {
        pinButtons.forEach(pinButton => {
            pinButton.onclick = (event) => {
                const filename = event.target.getAttribute('data-filename');

                fetch('/services/PinPostService.php?filename=' + filename, {
                    method: 'GET'
                }).then(() => location.reload());
            }
        });
    }

    if (addNewPostButton) {
        addNewPostButton.onclick = (event) => {
            window.location.href = `/${loginUrl}/add-new-post`;
        }
    }

    let target;

    if (editButtons) {
        editButtons.forEach(editButton => {
            editButton.onclick = (event) => {
                const filename = event.target.getAttribute('data-filename');

                window.location.href = `/${loginUrl}/edit-post?file=${filename}`;
            }
        });
    }

    if (deleteButtons) {
        deleteButtons.forEach(deleteButton => {
            deleteButton.onclick = (event) => {
                target = event.target;

                modal.style.display = 'block';
            }
        });
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

            fetch('/services/DeletePostService.php?file_to_delete=' + filename, {
                method: 'GET'
            }).then(() => location.reload());
        }

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