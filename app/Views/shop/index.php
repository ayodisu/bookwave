<div class="heading">
    <h3>Our Shop</h3>
    <p><a href="<?php echo url('home'); ?>">Home</a> / Shop</p>
</div>

<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>" style="margin: 2rem auto; max-width: 1200px; padding: 1.5rem; border-radius: 0.5rem; background-color: <?php echo $message['type'] == 'error' ? '#fee2e2' : '#dcfce7'; ?>; color: <?php echo $message['type'] == 'error' ? '#991b1b' : '#166534'; ?>; border: 1px solid <?php echo $message['type'] == 'error' ? '#fecaca' : '#bbf7d0'; ?>; display: flex; align-items: center; justify-content: space-between;">
        <span style="font-size: 1.4rem; font-weight: 500;"><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.style.display='none';" style="cursor: pointer; font-size: 1.4rem;"></i>
    </div>
<?php endif; ?>

<section class="products">
    <h1 class="title">All Products</h1>
    <p style="text-align:center; font-size:1.6rem; color:var(--light-color); margin-bottom:2rem;">
        Showing <?php echo min(($currentPage - 1) * $perPage + 1, $totalProducts); ?>-<?php echo min($currentPage * $perPage, $totalProducts); ?> of <?php echo $totalProducts; ?> products
    </p>
    <div class="box-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <form action="<?php echo url('shop'); ?>" method="post" class="box product-form">
                    <?php echo csrf_field(); ?>
                    <img class="image" src="<?php echo url('uploaded_img/' . e($product['image'])); ?>" alt="<?php echo e($product['name']); ?>">
                    <div class="name"><?php echo e($product['name']); ?></div>
                    <div class="price"><?php echo format_price($product['price']); ?></div>
                    <input type="number" min="1" name="product_quantity" value="1" class="qty">
                    <input type="hidden" name="product_name" value="<?php echo e($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo e($product['image']); ?>">
                    <button type="submit" name="add_to_cart" class="btn submit-btn">
                        <span class="btn-text">Add to Cart</span>
                        <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Adding...</span>
                    </button>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No products available yet!</p>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="<?php echo url('shop?page=1'); ?>" class="page-link">&laquo; First</a>
                <a href="<?php echo url('shop?page=' . ($currentPage - 1)); ?>" class="page-link">&lsaquo; Prev</a>
            <?php endif; ?>

            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            for ($i = $startPage; $i <= $endPage; $i++):
            ?>
                <a href="<?php echo url('shop?page=' . $i); ?>" class="page-link <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="<?php echo url('shop?page=' . ($currentPage + 1)); ?>" class="page-link">Next &rsaquo;</a>
                <a href="<?php echo url('shop?page=' . $totalPages); ?>" class="page-link">Last &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<script>
    document.querySelectorAll('.product-form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            btn.disabled = true;
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-loading').style.display = 'inline';
        });
    });
</script>