<?php

declare(strict_types=1);

$update = json_decode(file_get_contents('php://input'));

$currency = new Currency();
$bot = new botHandler();

if (isset($update->message)) {
    $message = $update->message;
    $chat_id = $message->chat->id;
    $type = $message->chat->type ?? 'Not specified';
    $miid = $message->message_id ?? 'Not specified';
    $name = $message->from->first_name ?? 'Not specified';
    $user = $message->from->username ?? '';
    $fromid = $message->from->id ?? 'Not specified';
    $text = $message->text ?? 'Not specified';
    $title = $message->chat->title ?? 'Not specified';
    $chatuser = $message->chat->username ?? 'Not specified';
    $chatuser = $chatuser ? $chatuser : "Shaxsiy Guruh!";
    $caption = $message->caption ?? 'Not specified';
    $entities = $message->entities ?? 'Not specified';
    $entities = $entities[0] ?? 'Not specified';
    $left_chat_member = $message->left_chat_member ?? 'Not specified';
    $new_chat_member = $message->new_chat_member ?? 'Not specified';
    $photo = $message->photo ?? 'Not specified';
    $video = $message->video ?? 'Not specified';
    $audio = $message->audio ?? 'Not specified';
    $voice = $message->voice ?? 'Not specified';
    $reply = $message->reply_markup ?? 'Not specified';
    $fchat_id = $message->forward_from_chat->id ?? 'Not specified';
    $fid = $message->forward_from_message_id ?? 'Not specified';

    if ($text === "/start"){
        try {
            $bot->handleStartCommand($chat_id);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return $e->getMessage();
        }
    }
}
    if ($update->callback_query) {
        $callback_query = $update->callback_query;
        $callback_query->data = $update->callback_query_data;
        $chat_id = $callback_query->message->chat->id;
        $message_id = $callback_query->message->message_id;

        try {
            $bot->http->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => print_r($callback_query, true),
                ]
            ]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        return $e->getMessage();
        }
        return;
    }