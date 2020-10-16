<?php
    //Start Session to Enable $_SESSION Usage
    session_start();

    //Set Up Class AutoLoading
    spl_autoload_register(function ($class) {
        require_once dirname(__FILE__) . '/includes/' . $class . '.Class.php';
    });

    /* Check for FORM [POST] Actions */
    //SignInUp [POST]
    if (isset($_POST["SignInUp"])) {
        $sign_in_up = new User($_POST["username"], $_POST["password"]);
    }
    //SignOut [POST]
    if (isset($_POST["SignOut"])) {
        $sign_out = new User();
        $sign_out->SignOut();
    }
    //DeleteAccount [POST]
    if (isset($_POST["DeleteAccount"])) {
        $delete_account = new User();
        $delete_account->DeleteUserFromDatabase();
    }
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
        <?php if (isset($_SESSION["username"]) && isset($_SESSION["userid"])): ?>
            <h1>To-Do App for <?php echo $_SESSION["username"]; ?></h1>
            <ul>
                <li>
                    <form method="POST" action="">
                        <input type="submit" name="SignOut" value="Sign Out">
                    </form>
                </li>
                <li>
                    <form method="POST" action="">
                        <input type="submit" name="DeleteAccount" value="Delete Account">
                    </form>
                </li>
            </ul>
            <section>
                <h2>Add To-Do</h2>
                <form method="POST" action="">
                    <label for="task">Task</label>
                    <input type="text" name="task" id="task" placeholder="Task" required />
                    <label for="due_date">Due Date</label>
                    <input type="date" name="due_date" id="due_date" placeholder="Due Date" required />
                    <label for="category_id_add">Category</label>
                    <select name="category_id" id="category_id_add" placeholder="Category" required>
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
                    <select name="category_id" id="category_id_delete" placeholder="Category" required>
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
                    <select name="category_id" id="category_id_edit" placeholder="Category" required>
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
        <?php else: ?>
            <h1>Sign In / Sign Up to Use the To-Do App</h1>
            <section>
                <h2>Sign In / Sign Up</h2>
                <form method="POST" action="">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Username" required />
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    <br /><br />
                    <input type="submit" name="SignInUp" value="Sign In / Sign Up">
                </form>
                <?php if (isset($_POST["SignInUp"])): //If FORM is Submitted ?>
                    <h3 class="message"><?php echo $sign_in_up->message; ?></h3>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>
<?php
    //Include END
    include_once dirname(__FILE__) . '/templates/footer.php';
?>