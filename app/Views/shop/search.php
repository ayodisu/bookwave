<div class="heading">
    <h3>Search Results</h3>
    <p><a href="<?php echo url('home'); ?>">Home</a> / Search</p>
</div>

<section class="search-form">
    <form action="<?php echo url('search'); ?>" method="get">
        <input type="text" name="q" placeholder="Search for books..." value="<?php echo e($query ?? ''); ?>" class="box">
        <button type="submit" class="btn">Search</button>
    </form>
</section>

<section class="products">
    <?php if (!empty($query)): ?>
        <h1 class="title">Results for "<?php echo e($query); ?>"</h1>
    <?php endif; ?>
    <div class="box-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <form action="<?php echo url('cart/add'); ?>" method="post" class="box">
                    <?php echo csrf_field(); ?>
                    <img class="image" src="<?php echo url('uploaded_img/' . e($product['image'])); ?>" alt="<?php echo e($product['name']); ?>">
                    <div class="name"><?php echo e($product['name']); ?></div>
                    <div class="price"><?php echo format_price($product['price']); ?></div>
                    <input type="number" min="1" name="product_quantity" value="1" class="qty">
                    <input type="hidden" name="product_name" value="<?php echo e($product['name']); ?>">
                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo e($product['image']); ?>">
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No products found<?php echo $query ? ' for "' . e($query) . '"' : ''; ?></p>
        <?php endif; ?>
    </div>
</section>