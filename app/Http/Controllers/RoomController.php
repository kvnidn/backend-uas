<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    //
    public function index() {
        $room = Room::orderBy('id')->get();
        $title = 'Room';
        return view('room/index', compact('room', 'title'));
    }

    public function create() {
        $title = 'room';
        return view('room/create', [
            'title'=>$title,
        ]);
    }

    public function store(Request $request) {
        $paddedRoomNumber = str_pad($request->room_number, 4, '0', STR_PAD_LEFT);
        
        $request->merge(['room_number' => $paddedRoomNumber]);
        
        $request->validate([
            'room_number'=>'required|unique:room,room_number|string|max:4'
        ]);


        Room::create([
            'room_number' => $paddedRoomNumber
        ]);

        return redirect('room/create')->with('status', 'Room created');
    }

    public function edit(int $id) {
        $room = Room::findOrFail($id);
        $title = "Room";

        return view("room/edit", compact('room', 'title'));
    }

    public function update(Request $request, int $id) {
        $paddedRoomNumber = str_pad($request->room_number, 4, '0', STR_PAD_LEFT);

        $request->merge(['room_number' => $paddedRoomNumber]);

        $request->validate([
            'room_number' => 'required|unique:room,room_number|string|max:4'
        ]);


        Room::findOrFail($id)->update([
            'room_number' => $paddedRoomNumber
        ]);

        return redirect('room/')->with('status', 'Room updated');
    }

    public function destroy(int $id) {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect('room/')->with('status', 'Room deleted');
    }
}
