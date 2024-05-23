<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use App\Notifications\MessageRead;
use App\Notifications\MessageSent;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatBox extends Component
{
    public  $selectedConversation;
    public $message;

    public $loadedMessages;

    public $paginate_var = 10;

    protected $listeners = [
        'loadMore'
    ];

    public function mount()
    {
        $this->loadMessages();
        $this->markMessagesAsRead();

    }

    public function render()
    {
        return view('livewire.chat.chat-box');
    }

    public function getListeners()
    {
        $auth_id = auth()->user()->id;
        return [
            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications'
        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                $this->dispatch('scroll-bottom');

                $newMessage = Message::find($event['message_id']);

                //push message
                $this->loadedMessages->push($newMessage);

                //mark as read
                $newMessage->read_at = now();
                $newMessage->save();

                //broadcast 
                $this->selectedConversation->getReceiver()
                    ->notify(new MessageRead($this->selectedConversation->id));  
            }
        }
    }

    public function loadMore()
    {
        //increment 
        // $this->paginate_var += 10;

        $this->loadMessages();


        //update the chat height 
        $this->dispatch('update-chat-height');
    }

    public function loadMessages()
    {

        $userId = auth()->id();
        #get count
        $count = Message::where('conversation_id', $this->selectedConversation->id)    
            ->where(function ($query) use($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->count();

        #skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            ->where(function ($query) use($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            // ->skip($count - $this->paginate_var)
            // ->take($this->paginate_var)
            ->get();


        return $this->loadedMessages;
    }

    public function markMessagesAsRead()
    {
        // Mark all messages in the selected conversation as read
        Message::where('conversation_id', $this->selectedConversation->id)
            ->where('receiver_id', Auth::id())
            ->update(['read_at' => now()]);
    }

    public function sendMessage()
    {
        $this->validate(['message' => 'required|string']);


        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'message' => $this->message
        ]);
        

        $this->reset('message');

        //scroll to bottom
        $this->dispatch('scroll-bottom');


        //push the message
        $this->loadedMessages->push($createdMessage);


        //update conversation model
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();


        //refresh chatlist
        $this->dispatch('refresh')->to('chat.chat-list');

        // broadcast notification now
        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id
            ));
    }
}
