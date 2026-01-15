<div class="heading">
    <h3>Checkout</h3>
    <p><a href="<?php echo url('home'); ?>">Home</a> / Checkout</p>
</div>

<section class="display-orders">
    <h1 class="title">Your Cart Items</h1>
    <?php foreach ($cartItems as $item): ?>
        <p><?php echo e($item['name']); ?> <span>(<?php echo format_price($item['price']); ?> x <?php echo $item['quantity']; ?>)</span></p>
    <?php endforeach; ?>
    <div class="grand-total">Grand Total: <span><?php echo format_price($grandTotal); ?></span></div>
</section>

<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>" style="margin: 0 auto 2rem; max-width: 1200px; padding: 1.5rem; border-radius: 0.5rem; background-color: <?php echo $message['type'] == 'error' ? '#fee2e2' : '#dcfce7'; ?>; color: <?php echo $message['type'] == 'error' ? '#991b1b' : '#166534'; ?>; border: 1px solid <?php echo $message['type'] == 'error' ? '#fecaca' : '#bbf7d0'; ?>; display: flex; align-items: center; justify-content: space-between;">
        <span style="font-size: 1.4rem; font-weight: 500;"><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.style.display='none';" style="cursor: pointer; font-size: 1.4rem;"></i>
    </div>
<?php endif; ?>

<?php if ($grandTotal > 0 && !$success): ?>
    <section class="checkout">
        <form action="<?php echo url('checkout'); ?>" method="post">
            <?php echo csrf_field(); ?>
            <h3>Place Your Order</h3>
            <div class="flex">
                <div class="inputBox">
                    <span>Your Name:</span>
                    <input type="text" name="name" required placeholder="Enter your name" value="<?php echo e($_POST['name'] ?? $_SESSION['user_name'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Your Number:</span>
                    <input type="tel" name="number" required placeholder="Enter your number" value="<?php echo e($_POST['number'] ?? $_SESSION['user_number'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Your Email:</span>
                    <input type="email" name="email" required placeholder="Enter your email" value="<?php echo e($_POST['email'] ?? $_SESSION['user_email'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Payment Method:</span>
                    <select name="method" required>
                        <option value="cash on delivery" <?php echo (isset($_POST['method']) && $_POST['method'] == 'cash on delivery') ? 'selected' : ''; ?>>Cash on Delivery</option>
                        <option value="credit card" <?php echo (isset($_POST['method']) && $_POST['method'] == 'credit card') ? 'selected' : ''; ?>>Credit Card</option>
                        <option value="bank transfer" <?php echo (isset($_POST['method']) && $_POST['method'] == 'bank transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Flat/House No:</span>
                    <input type="text" name="flat" required placeholder="e.g. Flat 123" value="<?php echo e($_POST['flat'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Street:</span>
                    <input type="text" name="street" required placeholder="e.g. Main Street" value="<?php echo e($_POST['street'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>City:</span>
                    <input type="text" name="city" required placeholder="e.g. Lagos" value="<?php echo e($_POST['city'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>State:</span>
                    <input type="text" name="state" required placeholder="e.g. Lagos State" value="<?php echo e($_POST['state'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Country:</span>
                    <input type="text" name="country" required placeholder="e.g. Nigeria" value="<?php echo e($_POST['country'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Pin Code:</span>
                    <input type="text" name="pin_code" required placeholder="e.g. 100001" value="<?php echo e($_POST['pin_code'] ?? ''); ?>">
                </div>
            </div>
            <button type="submit" name="place_order" class="btn submit-btn">
                <span class="btn-text">Place Order</span>
                <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
            </button>
        </form>
    </section>

    <script>
        document.querySelector('.checkout form').addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            btn.disabled = true;
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-loading').style.display = 'inline';
        });
    </script>
<?php elseif ($success): ?>
    <section class="checkout">
        <div style="text-align:center; padding:2rem;">
            <i class="fas fa-check-circle" style="font-size:6rem; color:green;"></i>
            <h3 style="margin:2rem 0;">Order Placed Successfully!</h3>
            <a href="<?php echo url('orders'); ?>" class="btn">View Orders</a>
            <a href="<?php echo url('shop'); ?>" class="option-btn">Continue Shopping</a>
        </div>
    </section>
<?php else: ?>
    <section class="checkout">
        <p class="empty">Your cart is empty! <a href="<?php echo url('shop'); ?>">Go shopping</a></p>
    </section>
<?php endif; ?>