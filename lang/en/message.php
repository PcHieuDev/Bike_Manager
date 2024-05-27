<?php
if (! function_exists('get_messages')) {
    function get_messages()
    {
        return [
            'errors' => [
                'product_not_found' => 'product not found',
                'error_updating_product' => 'Error updating product',
                'error_finding_product' => 'Error finding product',
                'error_deleting_product' => 'Error deleting product',
                'error_saving_product' => 'Error saving product',
            ],
            'success' => [
                'product_found' => 'product found',
                'product_deleted' => 'product deleted',
                'product_updated' => 'product updated',
                'product_saved' => 'product saved',
            ],
        ];
    }
}