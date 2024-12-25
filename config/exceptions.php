<?php

return [

    /** Client default response */
    'bad_request' => [
        'status' => 400,
        'title' => 'The server cannot or will not process the request due to something that is perceived to be a client error.',
        'message' => 'Your request had an error. Please try again.',
        'error_code' => 10400
    ],
    'not_authenticated' => [
        'status' => 401,
        'title' => 'Not authenticated',
        'message' => 'You must be logged in to perform this action!',
        'error_code' => 10401
    ],
    'account_inactive' => [
        'status' => 401,
        'title' => 'Account inactive',
        'message' => 'Account inactive. Please contact support.',
        'error_code' => 11401
    ],
    'role_not_allowed' => [
        'status' => 401,
        'title' => 'Role not allowed',
        'message' => 'Role not allowed.',
        'error_code' => 12401
    ],
    'wrong_verification_code' => [
        'status' => 402,
        'title' => 'Wrong verification code',
        'message' => 'Wrong verification code',
        'error_code' => 10402

    ],
    'wrong_credentials' => [
        'status' => 402,
        'title' => 'The provided password is wrong.',
        'message' => 'The provided password is wrong',
        'error_code' => 10402
    ],
    'forbidden' => [
        'status' => 403,
        'title' => 'The request was a valid request, but the server is refusing to respond to it.',
        'message' => 'Your request was valid, but you are not authorised to perform that action.',
        'error_code' => 10403
    ],
    'access_denied' => [
        'status' => 403,
        'title' => 'You do not have permission to that object.',
        'message' => 'You do not have permission to that object.',
        'error_code' => 11403
    ],
    'user_blocked' => [
        'status' => 403,
        'title' => 'Your account is blocked.',
        'message' => 'Kindly note that your account is temporary suspended, please contact us on this email to resolve the issue info@telgani.com',
        'translationKey' => 'customer.blocked',
        'error_code' => 12403
    ],
    'not_found' => [
        'status' => 404,
        'title' => 'The requested resource could not be found but may be available again in the future. Subsequent requests by the client are permissible.',
        'message' => 'The resource you were looking for was not found.',
        'error_code' => 10404
    ],
    'email_not_found' => [
        'status' => 404,
        'title' => 'The requested resource could not be found but may be available again in the future. Subsequent requests by the client are permissible.',
        'message' => 'No user with this email.',
        'error_code' => 10404
    ],
    'phone_not_found' => [
        'status' => 404,
        'title' => 'The requested resource could not be found but may be available again in the future. Subsequent requests by the client are permissible.',
        'message' => 'No user with this phone.',
        'error_code' => 10404
    ],
    'precondition_failed' => [
        'status' => 412,
        'title' => 'The server does not meet one of the preconditions that the requester put on the request.',
        'message' => 'Your request did not satisfy the required preconditions.',
        'error_code' => 10412
    ],
    'upload_error' => [
        'status' => 413,
        'title' => 'Upload failed',
        'message' => 'File upload failed',
        'error_code' => 11004
    ],

    'phone_number_in_use' => [
        'status' => 436,
        'title' => 'Phone number in use',
        'message' => 'The telephone number is already registered',
        'error_code' => 11005
    ],

    'item_not_rate' => [
        'status' => 405,
        'title' => 'Item can`t rate',
        'message' => 'Item can`t rate',
        'error_code' => 11006
    ],

    'can_not_booking' => [
        'status' => 403,
        'title' => 'Can`t Booking',
        'message' => 'You have booked car for this period',
        'error_code' => 11007
    ],

    'cant_gather_data_from_provider' => [
        'status' => 438,
        'title' => 'cant_gather_data_from_provider',
        'message' => 'cant_gather_data_from_provider',
        'error_code' => 11008
    ],
    'cant_connect_to_remote_server' => [
        'status' => 439,
        'title' => 'cant_connect_to_remote_server',
        'message' => 'cant_connect_to_remote_server',
        'error_code' => 11009
    ],
    'token_inspection_failed' => [
        'status' => 440,
        'title' => 'token_inspection_failed',
        'message' => 'token_inspection_failed',
        'error_code' => 11010
    ],
];
