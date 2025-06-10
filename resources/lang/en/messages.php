<?php

return [
    // Authentication messages
    'auth' => [
        'success' => 'Authentication successful',
        'failed' => 'These credentials do not match our records',
        'password' => 'The provided password is incorrect',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds',
        'invalid_credentials' => 'Invalid credentials',
        'login_success' => 'User logged in successfully',
        'logout_success' => 'Logged out successfully',
        'refresh_success' => 'Token refreshed successfully',
    ],

    // API responses
    'api' => [
        'success' => 'Operation completed successfully',
        'error' => 'An error occurred',
        'not_found' => 'Resource not found',
        'unauthorized' => 'Unauthorized access',
        'forbidden' => 'Forbidden',
        'validation_error' => 'Validation errors occurred',
    ],

    // Common terms
    'common' => [
        'save' => 'Save',
        'update' => 'Update',
        'delete' => 'Delete',
        'cancel' => 'Cancel',
        'confirm' => 'Are you sure?',
        'yes' => 'Yes',
        'no' => 'No',
    ],

    // Validation messages
    'validation' => [
        'required' => 'The :attribute field is required',
        'email' => 'The :attribute must be a valid email address',
        'unique' => 'The :attribute has already been taken',

    ],

    'address' => [
        'index_success' => 'Addresses retrieved successfully',
        'index_error' => 'Error occurred while fetching addresses',
        'store_success' => 'Address added successfully',
        'store_error' => 'Error occurred while adding address',
        'update_success' => 'Address updated successfully',
        'update_error' => 'Error occurred while updating address',
        'delete_success' => 'Address deleted successfully',
        'delete_error' => 'Error occurred while deleting address',
        'validation' => [
            'network_name' => [
                'required' => 'Network name is required',
                'unique' => 'This network name already exists',
            ],
            'target' => [
                'required' => 'Target URL is required',
            ],
            'qr_image' => [
                'required' => 'QR image is required',
                'image' => 'File must be an image',
            ],
        ],
    ],

    'administration' => [
        'supervisor' => [
            'index_success' => 'All supervisors retrieved successfully',
            'show_success' => 'Supervisor retrieved successfully',
            'store_success' => 'Account registered successfully',
            'update_success' => 'Data modified successfully',
            'delete_success' => 'Supervisor account deleted successfully',
            'permissions_success' => 'All permissions retrieved successfully',
            'errors' => [
                'show_error' => 'Error occurred while retrieving supervisor',
                'store_error' => 'Error occurred while registering account',
                'update_error' => 'Error occurred while modifying supervisor data',
                'delete_error' => 'Error occurred while deleting supervisor',
            ],
        ],
    ],

    'chat' => [
        'index_success' => 'Chats retrieved successfully',
        'show_success' => 'Chat retrieved successfully',
        'create_success' => 'Chat created successfully',
        'message_sent' => 'Message sent successfully',
        'errors' => [
            'index_error' => 'Error occurred while retrieving chats',
            'show_error' => 'Error occurred while retrieving chat',
            'create_error' => 'Error occurred while creating chat',
            'message_error' => 'Error occurred while sending message',
        ],
    ],


    'merchants' => [

        'fetched' => 'All merchants retrieved successfully',
        'details_fetched' => 'Merchant details retrieved successfully',
        'created' => 'Merchant created successfully',
        'updated' => 'Merchant updated successfully',
        'deleted' => 'Merchant deleted successfully',
        'blocked' => 'User blocked successfully',
        'unblocked' => 'User unblocked successfully',
        'errors' => [
            'fetch_error' => 'An error occurred while fetching merchants',
            'details_error' => 'An error occurred while showing merchant details',
            'create_error' => 'An error occurred while creating a merchant',
            'update_error' => 'An error occurred while updating merchant data',
            'delete_error' => 'An error occurred while deleting the merchant',
            'block_error' => 'An error occurred while blocking the user',
            'unblock_error' => 'An error occurred while unblocking the user',
        ],
    ],


    'notification' => [
        'index_success' => 'Notifications retrieved successfully',
        'show_success' => 'Notification retrieved successfully',
        'create_success' => 'Notification sent successfully',
        'delete_success' => 'Notification deleted successfully',
        'count_success' => 'Notification count retrieved successfully',
        'errors' => [
            'index_error' => 'Error occurred while retrieving notifications',
            'show_error' => 'Error occurred while retrieving notification',
            'create_error' => 'Error occurred while sending notification',
            'delete_error' => 'Error occurred while deleting notification',
        ],
    ],

    'order' => [
        'index_success' => 'Orders retrieved successfully',
        'show_success' => 'Order details retrieved successfully',
        'update_success' => 'Order status updated successfully',
        'delete_success' => 'Order deleted successfully',
        'errors' => [
            'index_error' => 'Error occurred while retrieving orders',
            'show_error' => 'Error occurred while showing order details',
            'update_error' => 'Error occurred while updating order status',
            'delete_error' => 'Error occurred while deleting the order',
        ],
    ],


    'product' => [
        'index_success' => 'Products retrieved successfully',
        'show_success' => 'Product retrieved successfully',
        'create_success' => 'Product created successfully',
        'update_success' => 'Product updated successfully',
        'delete_success' => 'Product deleted successfully',
        'errors' => [
            'index_error' => 'Error occurred while retrieving products',
            'show_error' => 'Error occurred while retrieving product details',
            'create_error' => 'Error occurred while saving the product',
            'update_error' => 'Error occurred while updating the product',
            'delete_error' => 'Error occurred while deleting the product',
        ],
    ],

    'product_type' => [
        'index_success' => 'Categories retrieved successfully',
        'show_success' => 'Category retrieved successfully',
        'create_success' => 'New category added successfully',
        'update_success' => 'Category updated successfully',
        'delete_success' => 'Category deleted successfully',
        'errors' => [
            'index_error' => 'Error occurred while retrieving categories',
            'show_error' => 'Error occurred while retrieving category information',
            'create_error' => 'Error occurred while adding a new category',
            'update_error' => 'Error occurred while updating the category',
            'delete_error' => 'Error occurred while deleting the category',
        ],
    ],

    'region' => [
        'index_success' => 'Regions retrieved successfully',
        'show_success' => 'Region retrieved successfully',
        'create_success' => 'Region created successfully',
        'update_success' => 'Region updated successfully',
        'delete_success' => 'Region deleted successfully',
        'errors' => [
            'index_error' => 'Error occurred while retrieving regions',
            'show_error' => 'Error occurred while retrieving region information',
            'create_error' => 'Error occurred while creating region',
            'update_error' => 'Error occurred while updating region',
            'delete_error' => 'Error occurred while deleting region',
        ],
    ],
];