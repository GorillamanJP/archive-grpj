<?php
function redirect_with_error_with_form(string $redirect_url, string $message, string $message_details, string $message_type, array $form_data): never
{
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    $_SESSION["message"] = $message;
    $_SESSION["message_details"] = $message_details;
    $_SESSION["message_type"] = $message_type;
    session_write_close();
    ?>
    <form action="<?= $redirect_url ?>" method="post" id="form">
        <?php foreach($form_data as $name => $value): ?>
            <?php if(is_array($value)): ?>
                <?php foreach($value as $sub_name => $sub_value): ?>
                    <input type="hidden" name="<?= htmlspecialchars($name) ?>[<?= htmlspecialchars($sub_name) ?>]"
                    value="<?= htmlspecialchars($sub_value) ?>">
                <?php endforeach ?>
            <?php else: ?>
                <input type="hidden" name="<?= htmlspecialchars($name) ?>"
                value="<?= htmlspecialchars($value) ?>">
            <?php endif ?>
        <?php endforeach ?>
    </form>
    <script>
        document.getElementById("form").submit();
    </script>
    <?php
    exit();
}