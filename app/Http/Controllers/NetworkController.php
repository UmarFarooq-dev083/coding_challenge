<?php

namespace App\Http\Controllers;

use App\Http\Requests\NetworkRequest;
use App\Models\Connection;
use App\Models\ConnectionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NetworkController extends Controller
{
    public function index()
    {
        //These Funtions are written in side Model 
        $user = auth()->user();
        $data['data'] = [
            'suggestions' => $user->getSuggestions()->take(10),
            'suggestionsCount' => $user->getSuggestions()->count(),
            
            'sentRequests' => $user->getSentRequests()->take(10),
            'sentRequestsCount' => $user->getSentRequests()->count(),
            
            'receivedRequests' => $user->getReceivedRequests()->take(10),
            'receivedRequestsCount' => $user->getReceivedRequests()->count(),
            
            'connections' => $user->getConnections()->take(10),
            'connectionsCount' => $user->getConnections()->count(),
            
            'commonConnections' => $user->getCommonConnectionAndCount()->take(2),
            'commonConnectionsCount' => $user->getCommonConnections()->count(),
        ];
        return view('home', $data);
    }

    public function Store(NetworkRequest $request)
    {
        DB::beginTransaction();
    
        try {
            ConnectionRequest::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $request->id,
            ]);    
            DB::commit();
    
            return response()->json(['message' => 'Connection request and connection created successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store connection request and connection: ' . $e->getMessage());
    
            return response()->json(['error' => 'Failed to create connection request and connection.'], 500);
        }

    }
    
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $connectionAccepted = ConnectionRequest::where('id',$request->id)->first();
            $connectionAccepted->status = 'accepted';
            $connectionAccepted->save();
            Connection::create([
                'user_id'=> Auth::user()->id,
                'connected_user_id'=> $connectionAccepted->sender_id,
                'created_at' =>Carbon::now(),
                'updated_at' =>Carbon::now()
            ]);    
            DB::commit();
            return response()->json(['message' => 'Connection request accepted successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to accept connection request: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to accept request.'], 500);
        }
    }


    public function withraw(Request $request)
    {
        return ConnectionRequest::where('id',$request->id)->delete();
    }

    public function destroy(Request $request)
    {
        ConnectionRequest::where('sender_id',$request->id)->where('receiver_id',Auth::user()->id)->delete();
        return Connection::where('connected_user_id',$request->id)->where('user_id',Auth::user()->id)->delete();
        
    }

    public function loadMore(Request $request)
    {
        $user = auth()->user();
        $section = $request->section;
        $page = $request->page;
        $perPage = 10;

        $data = [];

        switch ($section) {
            case 'suggestions':
                $data = $user->getSuggestions()->forPage($page, $perPage);
                break;
            case 'sentRequests':
                $data = $user->getSentRequests()->forPage($page, $perPage);
                break;
            case 'receivedRequests':
                $data = $user->getReceivedRequests()->forPage($page, $perPage);
                break;
            case 'connectionRequest':
                $a['first'] = $user->getConnections()->forPage($page, $perPage);
                $a['second'] = $user->getCommonConnectionAndCount()->forPage($page, $perPage);
                $data = $a;
                break;
            case 'commonConnections':
                $data = $user->getCommonConnections()->forPage($page, $perPage);
                break;
        }

        return response()->json($data);
    }
}
