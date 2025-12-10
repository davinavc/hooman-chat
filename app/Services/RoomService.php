<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Room;

class RoomService{

    private function createResponse($user, $room){
        $otherUser = $room->members->first();

        $lastMessage = $room->lastMessage;

        $returnLastmessage = [];

        $statusData = [];

        $totalUnread = 0;

        if($lastMessage){

            if($lastMessage->sender_id == $user->id){
                //User login as Sender
                $statusData = $lastMessage->statusForOthers->map(function($status){
                    return [
                        'receiver' => $status->recipient_id,
                        'status' => $status->status,
                        'readAt' => $status->read_at ? $status->read_at->toDateTimeString() : null,
                    ];
                });
            } else{
                //User login as Receiver
                $totalUnread = $room->messages()
                                    ->whereHas('statuses', function ($query) use ($user){
                                        $query->where('recipient_id', $user->id)
                                                ->where('status', 'delivered');
                                    })->count();
            }

            $returnLastmessage = [
                'id' => $lastMessage->id,
                'type' => $lastMessage->type,
                'message' => $lastMessage->message,
                'createdAt' => $lastMessage->created_at->toDateTimeString(),
                'status' => $statusData
            ];
        }

        if($room->type == 'private'){
            return [
                'id' => $room->id,
                'user_id' => $otherUser->id,
                'type' => $room->type,
                'name' => $otherUser->name,
                'avatar' => $otherUser->avatar,
                'lastMessage' => $returnLastmessage,
                'totalUnread' => $totalUnread
            ];
        } else if($room->type == 'group'){
            return [
                'id'=>$room->id,
                'type'=>$room->type,
                'name'=>$room->name,
                'avatar'=> '',
                'lastMessage' => $returnLastmessage,
                'totalUnread' => $totalUnread
            ];
        }
    }

    public function getRooms(string $search = null){
        $user = Auth::user();

        $query = $user->rooms()->with(['members' => function ($query) use ($user) {
            $query->where('user_id', '!=', $user->id);
        }]);
        
        if($search){
            $query->where(function ($q) use ($user, $search){
                $q->where(function ($sub) use ($user, $search){
                    $sub->where('type', 'private')
                        ->whereHas('members', function ($memberQuery) use ($user, $search){
                            $memberQuery->where('user_id', '!=', $user->id)
                            ->where('name', 'like', '%'.$search.'%');
                    });
                })->orWhere(function ($sub) use ($user, $search){
                    $sub->where('type', 'group')
                        ->where('name', 'like', '%'.$search.'%');
                });
                
            });
        }

        return $query->get()->map(function($room) use ($user){
            return $this->createResponse($user, $room);
        });
    }

    public function getContacts(string $search = null) {
        return User::where('name', 'like', '%'.$search.'%')
                    ->where('id', '!=', Auth::id())
                    ->get()
                    ->map(function($user){
                        return[
                            'id' => $user->id,
                            'type' => 'contact',
                            'user_id' =>$user->id,
                            'name' => $user->name,
                            'avatar' => $user->avatar
                        ];
                    });
    }
    
}