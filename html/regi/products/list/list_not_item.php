<?php
// 商品が空っぽの場合
?>
<tr>
    <td colspan='5'>
        <input type='hidden' id='products_count' name='products_count' value=0>
        <h2 class='text-center'>商品はありません。</h2>
        <p class='text-center'><a href='../create/'>新たに商品を登録しましょう！</a></p>
        <input type="hidden" id="update_msg" name="update_msg" value="<?= $update_msg ?>">
    </td>
</tr>