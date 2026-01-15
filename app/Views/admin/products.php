<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();" style="cursor: pointer;"></i>
    </div>
<?php endif; ?>

<div class="products-hybrid">
    <!-- Add Product Form -->
    <div class="admin-card" style="height: fit-content;">
        <div class="card-header">
            <h3 class="card-title"><?php echo isset($editProduct) ? 'Edit Product' : 'Add New Product'; ?></h3>
        </div>
        <form action="<?php echo url('admin/products'); ?>" method="post" enctype="multipart/form-data" style="padding: 1.5rem;">
            <?php echo csrf_field(); ?>
            <?php if (isset($editProduct)): ?>
                <input type="hidden" name="product_id" value="<?php echo $editProduct['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="E.g. The Great Gatsby" required value="<?php echo e($editProduct['name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Price (₦)</label>
                <input type="number" name="price" class="form-control" placeholder="0.00" required min="1" value="<?php echo $editProduct['price'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" <?php echo isset($editProduct) ? '' : 'required'; ?>>
                <?php if (isset($editProduct)): ?>
                    <div style="margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <img src="<?php echo url('uploaded_img/' . e($editProduct['image'])); ?>" alt="Current" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;">
                        <span style="font-size: 0.75rem; color: #64748b;">Current Image</span>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" name="<?php echo isset($editProduct) ? 'update_product' : 'add_product'; ?>" class="btn" style="flex: 1;">
                    <?php echo isset($editProduct) ? 'Update' : 'Add Product'; ?>
                </button>
                <?php if (isset($editProduct)): ?>
                    <a href="<?php echo url('admin/products'); ?>" class="btn" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Product List -->
    <div class="admin-card">
        <div class="card-header">
            <h3 class="card-title">Product Inventory</h3>
            <div style="font-size: 0.875rem; color: #64748b;">
                Count: <strong><?php echo count($products); ?></strong>
            </div>
        </div>
        <div class="table-container">
            <?php if (!empty($products)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo url('uploaded_img/' . e($product['image'])); ?>" alt="" class="table-image">
                                </td>
                                <td>
                                    <div style="font-weight: 500; font-size: 0.875rem;"><?php echo e($product['name']); ?></div>
                                </td>
                                <td style="font-weight: 600; color: var(--dark);"><?php echo format_price($product['price']); ?></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="<?php echo url('admin/products?edit=' . $product['id']); ?>" class="action-btn edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo url('admin/products'); ?>" method="post" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" name="delete_product" class="action-btn delete" onclick="return confirm('Delete this product?');" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                    <p>No products in inventory.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>