<?php
require '../vendor/autoload.php';

use Aws\Sns\SnsClient;

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../common/sign_in.php');
    exit();
}

$AAK = getenv('AWS_ACCESS_KEY');
$ASK = getenv('AWS_SECRET_KEY');
$SNS_TOPIC_ARN = getenv('SNS_TOPIC_ARN');

$client = new SnsClient([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'credentials' => [
        'key'    => "$AAK",
        'secret' => "$ASK",
    ]
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    $result = $client->subscribe([
        'Endpoint' => $email,
        'Protocol' => 'email',
        'TopicArn' => "$SNS_TOPIC_ARN" 
    ]);
    $message = "Subscription request sent. Please check your email ($email) to confirm!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css" />
    <title>Subscribe for Notifications</title>
</head>

<body>
    <main>
    <?php
    echo "AWS ACCESS KEY: " . $AAK . "<br>";
    echo "AWS SECRET KEY: " . $ASK . "<br>";
    echo "SNS TOPIC ARN: " . $SNS_TOPIC_ARN . "<br>";
    ?>
    <div class="subscribe-form">
        <h2>Subscribe for Notifications</h2>
        <form action="subscribe.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
    </div>
    </main>
</body>

</html>
