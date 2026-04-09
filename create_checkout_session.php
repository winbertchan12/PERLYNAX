<?php
session_start();
include 'config.php';

if(empty($_SESSION['cart'])){
    http_response_code(400);
    echo json_encode(['error'=>'Cart is empty']);
    exit();
}

// Stripe secret key
$secret_key = 'sk_test_YOUR_SECRET_KEY';

// Prepare line items
$line_items = [];
foreach($_SESSION['cart'] as $item){
    $line_items[] = [
        'price_data'=>[
            'currency'=>'usd',
            'product_data'=>['name'=>$item['name']],
            'unit_amount'=>intval($item['price']*100)
        ],
        'quantity'=>$item['quantity']
    ];
};

$data = [
    'success_url' => 'http://localhost/PERLYNAX/checkout_success.php',
    'cancel_url' => 'http://localhost/PERLYNAX/cart.php',
    'mode' => 'payment',
    'line_items' => $line_items
];

// cURL to Stripe API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/checkout/sessions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

// Convert line_items array to Stripe-compatible format
$post_fields = [
    'success_url'=>$data['success_url'],
    'cancel_url'=>$data['cancel_url'],
    'mode'=>$data['mode']
];

foreach($data['line_items'] as $i=>$item){
    $post_fields["line_items[$i][price_data][currency]"] = $item['price_data']['currency'];
    $post_fields["line_items[$i][price_data][product_data][name]"] = $item['price_data']['product_data']['name'];
    $post_fields["line_items[$i][price_data][unit_amount]"] = $item['price_data']['unit_amount'];
    $post_fields["line_items[$i][quantity]"] = $item['quantity'];
}

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");
$response = curl_exec($ch);
curl_close($ch);

header('Content-Type: application/json');
echo $response;
