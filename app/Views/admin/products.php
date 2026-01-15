<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
<?php endif; ?>

<section class="add-products">
    <h1 class="title"><?php echo isset($editProduct) ? 'Update Product' : 'Add Product'; ?></h1>
    <form action="<?php echo url('admin/products'); ?>" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if (isset($editProduct)): ?>
            <input type="hidden" name="product_id" value="<?php echo $editProduct['id']; ?>">
        <?php endif; ?>
        <input type="text" name="name" placeholder="Enter product name" class="box" required value="<?php echo e($editProduct['name'] ?? ''); ?>">
        <input type="number" name="price" placeholder="Enter product price" class="box" required min="1" value="<?php echo $editProduct['price'] ?? ''; ?>">
        <input type="file" name="image" class="box" accept="image/*" <?php echo isset($editProduct) ? '' : 'required'; ?>>
        <?php if (isset($editProduct)): ?>
            <img src="<?php echo url('uploaded_img/' . e($editProduct['image'])); ?>" alt="" style="max-width:100px; margin:1rem 0;">
        <?php endif; ?>
        <button type="submit" name="<?php echo isset($editProduct) ? 'update_product' : 'add_product'; ?>" class="btn">
            <?php echo isset($editProduct) ? 'Update Product' : 'Add Product'; ?>
        </button>
        <?php if (isset($editProduct)): ?>
            <a href="<?php echo url('admin/products'); ?>" class="option-btn">Cancel</a>
        <?php endif; ?>
    </form>
</section>

<section class="show-products">
    <h1 class="title">Products List</h1>
    <div class="box-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="box">
                    <img src="<?php echo url('uploaded_img/' . e($product['image'])); ?>" alt="">
                    <div class="name"><?php echo e($product['name']); ?></div>
                    <div class="price"><?php echo format_price($product['price']); ?></div>
                    <a href="<?php echo url('admin/products?edit=' . $product['id']); ?>" class="option-btn">Update</a>
                    <form action="<?php echo url('admin/products'); ?>" method="post" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="delete_product" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No products added yet!</p>
        <?php endif; ?>
    </div>
</section>