<?php
if (! function_exists('get_messages')) {
    function get_messages()
    {
        return [
            'errors' => [
                'product_not_found' => 'ProductActions not found',
                'error_updating_product' => 'Error updating ProductActions',
                'error_finding_product' => 'Error finding ProductActions',
                'error_deleting_product' => 'Error deleting ProductActions',
                'error_saving_product' => 'Error saving ProductActions',
            ],
            'success' => [
                'product_found' => 'ProductActions found',
                'product_deleted' => 'ProductActions deleted',
                'product_updated' => 'ProductActions updated',
                'product_saved' => 'ProductActions saved',
            ],
        ];
    }
}