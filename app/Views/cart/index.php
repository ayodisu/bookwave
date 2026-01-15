<div class="heading">
    <h3>Your Cart</h3>
    <p><a href="<?php echo url('home'); ?>">Home</a> / Cart</p>
</div>

<section class="shopping-cart">
    <h1 class="title">Products In Cart</h1>
    <div class="box-container">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <div class="box">
                    <img src="<?php echo url('uploaded_img/' . e($item['image'])); ?>" alt="">
                    <div class="name"><?php echo e($item['name']); ?></div>
                    <div class="price"><?php echo format_price($item['price']); ?></div>
                    <form action="<?php echo url('cart'); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                        <input type="number" name="qty" min="1" value="<?php echo $item['quantity']; ?>" class="qty">
                        <button type="submit" name="update_qty" class="option-btn">Update</button>
                    </form>
                    <div class="sub-total">Sub Total: <span><?php echo format_price($item['price'] * $item['quantity']); ?></span></div>
                    <form action="<?php echo url('cart'); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="delete_item" class="delete-btn" onclick="return confirm('Delete this item?');">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">Your cart is empty!</p>
        <?php endif; ?>
    </div>

    <div class="cart-total">
        <p>Grand Total: <span><?php echo format_price($total); ?></span></p>
        <a href="<?php echo url('shop'); ?>" class="option-btn">Continue Shopping</a>
        <a href="<?php echo url('checkout'); ?>" class="btn <?php echo ($total <= 0) ? 'disabled' : ''; ?>">Proceed to Checkout</a>
        <?php if (!empty($items)): ?>
            <form action="<?php echo url('cart'); ?>" method="post" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" name="clear_cart" class="delete-btn" onclick="return confirm('Clear entire cart?');">Clear Cart</button>
            </form>
        <?php endif; ?>
    </div>
</section>