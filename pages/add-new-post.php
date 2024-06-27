<main class="add-new-post">
    <div class="container">
        <h2 class="m-0">Add New Post</h2>
        <form class="w-100">
            <div class="form-group">
                <label for="title">Title</label>
                <input id="title" type="text" name="title" class="w-50" placeholder="Enter title here">
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <textarea id="summary" class="summary" name="summary" placeholder="Summarize what your post is about" maxlength="255"></textarea>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" class="content" name="content" placeholder="Use your markdown skills here"></textarea>
            </div>
            <div class="form-group">
                <button>Publish</button>
            </div>
        </form>
    </div>
</main>