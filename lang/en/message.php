<?php
if (! function_exists('get_messages')) {
    function get_messages()
    {
        return [
            'errors' => [
                'product_not_found' => 'Product not found',
                'error_updating_product' => 'Error updating Product',
                'error_finding_product' => 'Error finding Product',
                'error_deleting_product' => 'Error deleting Product',
                'error_saving_product' => 'Error saving Product',
                'error_registering_user' => 'Error registering user',   
                'error_login' => 'Error logging in',
                'error_logout' => 'Error logging out',
            ],
            'success' => [
                'product_found' => 'Product found',
                'product_deleted' => 'Product deleted',
                'product_updated' => 'Product updated',
                'product_saved' => 'Product saved',
                'user_registered' => 'User registered',
                'user_loggin_success' => 'User logged in successfully',
            ],
        ];
    }
}