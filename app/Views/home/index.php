<section class="home">
    <div class="content">
        <h3>Hand Picked Books to Your Door</h3>
        <p>Discover your next favorite book from our curated collection. Quality reads delivered straight to you.</p>
        <a href="<?php echo url('about'); ?>" class="white-btn">Discover More</a>
    </div>
</section>

<section class="products">
    <h1 class="title">Latest Products</h1>
    <div class="box-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <form action="<?php echo url('home'); ?>" method="post" class="box">
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
            <p class="empty">No products added yet!</p>
        <?php endif; ?>
    </div>
    <div class="load-more" style="margin-top: 2rem; text-align:center">
        <a href="<?php echo url('shop'); ?>" class="option-btn">Load More</a>
    </div>
</section>

<section class="about">
    <div class="flex">
        <div class="image">
            <img src="<?php echo url('images/about-img.jpg'); ?>" alt="">
        </div>
        <div class="content">
            <h3>About Us</h3>
            <p>We are passionate about connecting readers with their next great book. Our curated collection features the best titles across all genres.</p>
            <a href="<?php echo url('about'); ?>" class="btn">Read More</a>
        </div>
    </div>
</section>

<section class="home-contact">
    <div class="content">
        <h3>Want to Build Something Amazing?</h3>
        <p>This is a portfolio project. If you need a website, web app, or custom software — let's talk!</p>
        <a href="<?php echo url('contact'); ?>" class="white-btn">Hire Me</a>
    </div>
</section>

<script>
    document.querySelectorAll('.box form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            if (btn) {
                btn.disabled = true;
                btn.querySelector('.btn-text').style.display = 'none';
                btn.querySelector('.btn-loading').style.display = 'inline';
            }
        });
    });
</script>