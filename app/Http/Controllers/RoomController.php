<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    //
    public function index() {
        $room = Room::orderBy('room_number')->get();
        $title = 'Room';
        return view('room/index', compact('room', 'title'));
    }

    public function create() {
        $title = 'Room';
        return view('room/create', [
            'title'=>$title,
        ]);
    }

    public function store(Request $request) {
        $paddedRoomNumber = str_pad($request->room_number, 4, '0', STR_PAD_LEFT);

        $request->merge(['room_number' => $paddedRoomNumber]);

        $validator = Validator::make($request->all(),[
            'room_number'=>'required|unique:room,room_number|string|max:4'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'createRoom')
                        ->withInput();
        }

        Room::create([
            'room_number' => $paddedRoomNumber
        ]);

        return redirect('room/')->with('status', 'Room created');
    }

    public function edit(int $id) {
        $room = Room::findOrFail($id);
        $title = "Room";

        return view("room/edit", compact('room', 'title'));
    }

    public function update(Request $request, int $id) {
        $paddedRoomNumber = str_pad($request->room_number, 4, '0', STR_PAD_LEFT);

        $request->merge(['room_number' => $paddedRoomNumber]);

        $validator = Validator::make($request->all(),[
            'room_number' => 'required|string|max:4|unique:room,room_number,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator, 'editRoom')
                        ->withInput();
        }

        Room::findOrFail($id)->update([
            'room_number' => $paddedRoomNumber
        ]);

        return redirect('room/')->with('status', 'Room updated');
    }

    public function destroy(int $id) {
        if (Auth::check()) {
            $room = Room::findOrFail($id);
            $room->delete();
            return redirect('room/')->with('status', 'Room deleted');
        } else {
            return redirect('/login')->with('loginError', 'Please login to delete room');
        }
    }
}
