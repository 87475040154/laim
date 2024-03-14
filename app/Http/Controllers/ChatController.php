<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    //Метод отправки сообщения
    public function sendMessage(Request $request)
    {

        //Валидируем данные
        $request->validate([
            'sender_id' => 'required|integer', //Отправитель смс 1 - 2
            'recipient_id' => 'required|integer', //Получатель смс   2 - 1
            'message' => 'nullable|string|min:1|max:200', //смс
        ]);
        $message = Chat::add($request->all());
        return $message->id;
    }

    // Метод получить все мои чаты, с последним сообщением
    public function getAllChats(Request $request)
    {

        //Получаем все мои чаты - где я отпровитель или получатель
        $allChats = Chat::whereIn('id', function ($query) use ($request) {
            $query->select(DB::raw('MAX(id)'))
                ->from('chats')
                ->where('chat', true)
                ->where(function ($q) use ($request) {
                    $q->where('sender_id', $request->user_id)
                        ->where('delete_sender', false);
                })
                ->orWhere(function ($q) use ($request) {
                    $q->where('recipient_id', $request->user_id)
                        ->where('delete_recipient', false);
                })
                ->groupBy(DB::raw('IF(sender_id = ' . $request->user_id . ', recipient_id, sender_id)'));
        })
            ->orderBy('id', 'desc')
            ->get();

        //Перебераем чаты и добавляем доп. данные
        foreach ($allChats as $chat) {

            //Если у данного чата опровитель я, то получить данные получателя иначе наоборот
            if ($chat->sender_id == $request->user_id) {

                $chat->user_data = User::find($chat->recipient_id);

            } else {
                $chat->user_data = User::find($chat->sender_id);

                //Получим количество не прочитанных мной смс с данным пользователем
                $chat->count_new_message = Chat::where('sender_id', $chat->sender_id)->where('recipient_id', $chat->recipient_id)->where('open_recipient', false)->count();

            }
        }

        //Вернем чаты с последним смс
        return $allChats;
    }

    // Метод получить все смс одного чата
    public function getAllMessages(Request $request)
    {

        $chat = Chat::find($request->chat_id);

        // Определяем, кто получатель
        $recipient_id = ($chat->sender_id == $request->sender_id) ? $chat->recipient_id : $chat->sender_id;

        // Получаем все сообщения из чата
        $messages = Chat::whereIn('sender_id', [$chat->sender_id, $chat->recipient_id])
            ->whereIn('recipient_id', [$chat->sender_id, $chat->recipient_id])
            ->orderBy('id', 'asc')->get();

        // Обновляем флаги на сообщениях отправителя, которые получатель прочитал
        Chat::where('sender_id', $recipient_id)
            ->where('recipient_id', $request->sender_id)
            ->update([
                'open_recipient' => true,
                'open_date' => now(),
            ]);

        // Получаем данные друга (получателя)
        $recipient_data = User::find($recipient_id);

        return response()->json(['messages' => $messages, 'recipient_data' => $recipient_data], 200);

    }

    public function deleteChat(Request $request)
    {
        // Находим все сообщения одного чата
        $messages = Chat::whereIn('sender_id', [$request->sender_id, $request->recipient_id])
            ->whereIn('recipient_id', [$request->sender_id, $request->recipient_id])
            ->get();

        // Перебираем сообщения и устанавливаем флаги удаления
        foreach ($messages as $message) {
            if ($message->sender_id == $request->sender_id) {
                if ($message->delete_recipient == true) {

                    // Удалить сообщение и его изображения
                    if (count($message->images) > 0) {
                        foreach ($message->images as $name) {
                            Storage::delete('/img/messageImg/' . $name);
                        }
                    }
                    // Удаляем само сообщение
                    $message->delete();

                } else {
                    $message->delete_sender = true;
                    $message->save();
                }
            } else {
                if ($message->delete_sender == true) {

                    // Удалить сообщение и его изображения
                    if (count($message->images) > 0) {
                        foreach ($message->images as $name) {
                            Storage::delete('/img/messageImg/' . $name);
                        }
                    }
                    // Удаляем само сообщение
                    $message->delete();

                } else {
                    $message->delete_recipient = true;
                    $message->save();
                }
            }
        }
    }


}
