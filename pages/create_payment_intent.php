<?php
require __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51RfnohFojX3lXg5TvGWqvrSniY0ExKjHkdoeIr6358YJiZJiE9qg8lwtZU8HfFZK8nckxIlKGJoLalC1oIbuhrEJ00saxXhyno'); 

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

try {
    $amount = $input['amount']; 

    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'usd',
    ]);

    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    
}
?>