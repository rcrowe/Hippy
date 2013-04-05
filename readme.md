
$transport = new rcrowe\Hippy\Transport\Guzzle($token, $room, $from);
$hippy = new rcrowe\Hippy\Client($transport);

$message = new rcrowe\Hippy\Message(true, 'yellow');
$message = new rcrowe\Hippy\Message;
$message->addNotification();
$message->removeNotification();
$message->setBackgroundColor('yellow');
$message->setHtml('<a href="#">test</a>');
$message->setText('test');

$queue = new rcrowe\Hippy\Queue;
$queue->add($message);

$hippy->send($message);
$hippy->send($queue);
