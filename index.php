<?php
    //Set Up Class AutoLoading
    spl_autoload_register(function ($class) {
        require_once dirname(__FILE__) . '/includes/' . $class . '.Class.php';
    });

    /* Check for FORM [POST] Actions */
    //AddCategory [POST]
    if (isset($_POST["AddCategory"])) {
        $add_category = new Category($_POST["name"]);
    }
    //DeleteCategory [POST]
    if (isset($_POST["DeleteCategory"])) {
        $delete_category = new Category();
        $delete_category->DeleteCategoryFromDatabase($_POST["category_id"]);
    }
    //EditCategory [POST]
    if (isset($_POST["EditCategory"])) {
        $edit_category = new Category();
        $edit_category->GetCategoryFromDatabase($_POST["category_id"]);
    }
    //UpdateCategory [POST]
    if (isset($_POST["UpdateCategory"])) {
        $update_category = new Category();
        $update_category->UpdateCategoryInDatabase($_POST["category_id"], $_POST["name"]);
    }

    //Prepare to Render Category Options from Category Class
    $render_category = new Category();

    //Include HTML HEAD
    require_once dirname(__FILE__) . '/templates/head.php';    
?>
    <main>
        <h1>To-Do App</h1>
        <section>
            <h2>Add To-Do</h2>
            <form method="POST" action="">
                <label for="task">Task</label>
                <input type="text" name="task" id="task" placeholder="Task" required />
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" placeholder="Due Date" required />
                <label for="category_id_add">Category</label>
                <select name="category_id" id="category_id_add" placeholder="Category" required />
                    <option value="">Select Category</option>
                    <?php (!$render_category->message) ? $render_category->RenderCategoryToSelectOptions() : $render_category->message; //Render Category Options ?>
                </select>
                <br /><br />
                <input type="submit" name="AddToDo" value="Add To-Do">
            </form>
            <br /><br />
            <h2>Add Category</h2>
            <form method="POST" action="">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Name" required />
                <br /><br />
                <input type="submit" name="AddCategory" value="Add Category">
            </form>
            <?php if (isset($_POST["AddCategory"])): //If FORM is Submitted ?>
                <h3 class="message"><?php echo $add_category->message; ?></h3>
            <?php endif; ?>
            <br /><br />
            <h2>Delete Category</h2>
            <form method="POST" action="">
                <label for="category_id_delete">Category</label>
                <select name="category_id" id="category_id_delete" placeholder="Category" required />
                    <option value="">Select Category</option>
                    <?php (!$render_category->message) ? $render_category->RenderCategoryToSelectOptions() : $render_category->message; //Render Category Options ?>
                </select>
                <br /><br />
                <input type="submit" name="DeleteCategory" value="Delete Category">
            </form>
            <?php if (isset($_POST["DeleteCategory"])): //If FORM is Submitted ?>
                <h3 class="message"><?php echo $delete_category->message; ?></h3>
            <?php endif; ?>
            <br /><br />
            <h2>Edit Category</h2>
            <form method="POST" action="">
                <label for="category_id_edit">Category</label>
                <select name="category_id" id="category_id_edit" placeholder="Category" required />
                    <option value="">Select Category</option>
                    <?php (!$render_category->message) ? $render_category->RenderCategoryToSelectOptions() : $render_category->message; //Render Category Options ?>
                </select>
                <br /><br />
                <input type="submit" name="EditCategory" value="Edit Category">
            </form>
            <?php if (isset($_POST["EditCategory"]) && $edit_category->message): //If FORM is Submitted and Error Message is Available ?>
                <h3 class="message"><?php echo $edit_category->message; ?></h3>
            <?php endif; ?>
            <?php if (isset($_POST["EditCategory"]) && !empty($edit_category->category)): //If FORM is Submitted and Category is not Empty ?>
                <br /><br />
                <h2>Update Category</h2>
                <form method="POST" action="">
                    <input type="hidden" name="category_id" value="<?php echo $edit_category->category["CategoryID"]; ?>" required />
                    <label for="name_update">Category</label>
                    <input type="text" name="name" id="name_update" placeholder="Name" value="<?php echo $edit_category->category["Name"]; ?>" required />
                    <br /><br />
                    <input type="submit" name="UpdateCategory" value="Update Category">
                </form>
            <?php endif; ?>
            <?php if (isset($_POST["UpdateCategory"])): //If FORM is Submitted ?>
                <h3 class="message"><?php echo $update_category->message; ?></h3>
            <?php endif; ?>
        </section>
        <section>
            <h2>Active To-Do(s)</h2>
        </section>
        <section>
            <h2>Overdue To-Do(s)</h2>
        </section>
        <section>
            <h2>Completed To-Do(s)</h2>
        </section>
    </main>
<?php
    //Include END
    include_once dirname(__FILE__) . '/templates/footer.php';
?>