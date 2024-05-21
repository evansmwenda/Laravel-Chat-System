<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;

class Users extends Component
{
    public function message($userId)
    {
        //send message to user
        $currentUserId = auth()->id();

        # Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($currentUserId, $userId) {
                    $query->where('sender_id', $currentUserId)
                        ->where('receiver_id', $userId);
                    })
                ->orWhere(function ($query) use ($currentUserId, $userId) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', $currentUserId);
                })->first();
        
      if ($existingConversation) {
          # Conversation already exists, redirect to existing conversation
          return redirect()->route('chat', ['query' => $existingConversation->id]);
      }
  
      # Create new conversation
      $createdConversation = Conversation::create([
          'sender_id' => $currentUserId ,
          'receiver_id' => $userId,
      ]);
 
        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }


    public function render()
    {
        return view('livewire.users',['users' => User::where('id','!=' ,auth()->id())->get()]);
    }
}
