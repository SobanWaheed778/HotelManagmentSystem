<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51QevSDC72xlZ1IDdlPt8ulBh6Bd4FNA3rDIPr0Q0S43uF3Qn4GOAaD9jRYjDDizNermva7U5SFzJr4WlByZiDIQG00nVJBfLnU');

// Get raw input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (empty($input['visitor_logs_id']) || empty($input['price'])) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'Invalid input: visitor_logs_id and price are required.']);
    exit();
}

$visitor_logs_id = $input['visitor_logs_id'];
$price = (float) $input['price'] * 100; // Convert to cents

try {
    // Create a PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $price,
        'currency' => 'usd',
        'metadata' => [
            'visitor_logs_id' => $visitor_logs_id, // Add visitor_logs_id to metadata
        ],
    ]);

    // Return the client secret
    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500); // Internal server error
    echo json_encode(['error' => $e->getMessage()]);
}
?>