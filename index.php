<?php
    //Set Up Class AutoLoading
    spl_autoload_register(function ($class) {
        require_once dirname(__FILE__) . '/includes/' . $class . '.Class.php';
    });

    //Include HTML HEAD
    require_once dirname(__FILE__) . '/templates/head.php';

    //Check for FORM [POST] Actions
    //AddCategory [POST]
    if (isset($_POST["AddCategory"])) {
        $add_category = new Category($_POST["name"]);
    }
?>
    <main>
        <h1>To-Do App</h1>
        <section>
            <h2>Add To-Do</h2>
            <form method="POST" action="">
                <label for="task">Task</label>
                <input type="text" name="task" id="task" placeholder="Task" required>
                <label for="due_date">Due Date</label>
                <input type="date" name="due_date" id="due_date" placeholder="Due Date" required>
                <label for="category">Category</label>
                <select name="category" id="category" placeholder="Category" required>
                    <option value="">Select Category</option>
                </select>
                <br /><br />
                <input type="submit" name="AddToDo" value="Add To-Do">
            </form>
            <br /><br />
            <h2>Add Category</h2>
            <form method="POST" action="">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Name" required>
                <br /><br />
                <input type="submit" name="AddCategory" value="Add Category">
            </form>
            <?php if (isset($_POST["AddCategory"])): ?>
                <h3 class="message"><?php echo $add_category->message; ?></h3>
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