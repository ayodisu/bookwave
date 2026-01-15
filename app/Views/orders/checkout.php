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

<?php if ($grandTotal > 0 && !$success): ?>
    <section class="checkout">
        <form action="<?php echo url('checkout'); ?>" method="post">
            <?php echo csrf_field(); ?>
            <h3>Place Your Order</h3>
            <div class="flex">
                <div class="inputBox">
                    <span>Your Name:</span>
                    <input type="text" name="name" required placeholder="Enter your name" value="<?php echo e($_SESSION['user_name'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Your Number:</span>
                    <input type="tel" name="number" required placeholder="Enter your number">
                </div>
                <div class="inputBox">
                    <span>Your Email:</span>
                    <input type="email" name="email" required placeholder="Enter your email" value="<?php echo e($_SESSION['user_email'] ?? ''); ?>">
                </div>
                <div class="inputBox">
                    <span>Payment Method:</span>
                    <select name="method" required>
                        <option value="cash on delivery">Cash on Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="bank transfer">Bank Transfer</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Flat/House No:</span>
                    <input type="text" name="flat" required placeholder="e.g. Flat 123">
                </div>
                <div class="inputBox">
                    <span>Street:</span>
                    <input type="text" name="street" required placeholder="e.g. Main Street">
                </div>
                <div class="inputBox">
                    <span>City:</span>
                    <input type="text" name="city" required placeholder="e.g. Lagos">
                </div>
                <div class="inputBox">
                    <span>State:</span>
                    <input type="text" name="state" required placeholder="e.g. Lagos State">
                </div>
                <div class="inputBox">
                    <span>Country:</span>
                    <input type="text" name="country" required placeholder="e.g. Nigeria">
                </div>
                <div class="inputBox">
                    <span>Pin Code:</span>
                    <input type="text" name="pin_code" required placeholder="e.g. 100001">
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