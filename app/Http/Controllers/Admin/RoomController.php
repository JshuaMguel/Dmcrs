<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Check if user has admin access
     */
    private function checkAdminAccess()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized access. Please login first.');
        }

        $role = strtolower(str_replace(' ', '_', Auth::user()->role ?? ''));

        if (!in_array($role, ['admin', 'superadmin', 'super_admin', 'super admin'])) {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }
    }

    /**
     * Display a listing of the rooms.
     */
    public function index()
    {
        $this->checkAdminAccess();

        $rooms = Room::orderBy('name')->paginate(15);

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        $this->checkAdminAccess();

        return view('admin.rooms.create');
    }

    /**
     * Store a newly created room in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully!');
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        $this->checkAdminAccess();

        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified room in storage.
     */
    public function update(Request $request, Room $room)
    {
        $this->checkAdminAccess();

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name,' . $room->id,
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified room from storage.
     */
    public function destroy(Room $room)
    {
        $this->checkAdminAccess();

        // Check if room is being used in any schedules
        if ($room->schedules()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Cannot delete room. It is currently assigned to ' . $room->schedules()->count() . ' schedule(s).');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully!');
    }
}
