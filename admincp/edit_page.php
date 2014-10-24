<?php if(count(get_included_files()) ==1) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    exit('400: Bad Request');
} ?>
<?php
$text = "";
$url = "";
$action = "";
$num_newlines = 5;
if (isset($_GET['url'])) $url = htmlentities($_GET['url']);;
if (isset($_GET['text'])) $text = $_GET['text'];
if (isset($_GET['action'])) $action = $_GET['action'];

if ($action == "edit") {
    if (!empty($url)) {
        if (empty($text)) {;
            $file = '../pages/' . $url . '.php';
            $text = file_get_contents($file);
            $rows = substr_count( $text, "\n" ) ;
        } else {
            $text = "page saved...";
        }
    }
}else if ($action == "delete") {
    if($pages->delete_page($url) & $permissions->delete_all_page_permissions($url)) {
        echo 'Page has been successfully deleted.<br />';
    }
}
?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js"></script>
<body>
<div id="content">
    <div class="box">
        <div class="box-header">Admin Panel</div>
        <div class="box-body">
            <h1>Edit Page</h1>

            <?php
            if (isset($_GET['success']) && empty($_GET['success'])) {
                echo 'Page created.';
            }
            $allpages = $pages->get_pages();
            if (!empty($allpages)) {
                foreach ($allpages as $showPage){
                    //displaying posts
                    echo ($showPage['title'].' - '.
                        $showPage['url'].' - '.
                        $showPage['content'].' - '.
                        $showPage['ip'].' - '.
                        $showPage['time'].'
                    			- <a href="index.php?page=edit_page&action=edit&url='.$showPage['url'].'">Edit</a>
                    			- <a href="index.php?page=edit_page&action=delete&url='.$showPage['url'].'">Delete</a>
                    			<br /><br />');
                }
            } else { ?>
                Home - <a href='index.php?page=edit_page&action=edit&url=home'>Edit</a>
                - <a href="index.php?page=edit_page&action=delete&url=home">Delete</a>
            <?php
            }
            ?>
            <!-- form -->
            <form action="" method="get" name="Edit Page">
                <input type="hidden" name="page" value="edit_page" />
                <p>Name:<br />
                    <input id="get-url" name="url" type="text" size="45" value="enter url"/>
                </p>
                <textarea name="text" data-editor="php" cols="100" rows="<?php echo $rows ?>" id="editpage"><?php echo htmlspecialchars($text) ?></textarea>
                <br />
                <input type="hidden" name="action" value="edit" />
                <input type="submit" value="submit"/>
            </form>
            <?php
            if(empty($errors) === false){
                echo '<p>' . implode('</p><p>', $errors) . '</p>';
            }
            ?>
        </div>
    </div>
</div>
<script>
    // Hook up ACE editor to all textareas with data-editor attribute
    $(function () {
        $('textarea[data-editor]').each(function () {
            var textarea = $(this);

            var mode = textarea.data('editor');

            var editDiv = $('<div>', {
                position: 'absolute',
                width: textarea.width(),
                height: textarea.height(),
                'class': textarea.attr('class')
            }).insertBefore(textarea);

            textarea.css('display', 'none');

            var editor = ace.edit(editDiv[0]);
            editor.renderer.setShowGutter(false);
            editor.getSession().setValue(textarea.val());
            editor.getSession().setMode("ace/mode/" + mode);
            // copy back to textarea on form submit...
            textarea.closest('form').submit(function () {
                textarea.val(editor.getSession().getValue());
            })

        });
    });
</script>
</body>
</html>