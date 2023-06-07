<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Room;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // TODO Не учитывал моменты что места (любые виды ) могут быть заняты.

    // TODO A2 - Manage events
    public function events(Request $request)
    {
        // TODO A2a – List all events
        $organizer = Auth::guard('organizer')->user();
        $events = Event::with(['tickets'])->where(['organizer_id' => $organizer->id])->get();
        return view('events/index', ['events' => $events, 'organizer' => $organizer]);
    }
    public function event(Request $request, $event_slug)
    {
        // TODO A2h – Show event detail
        $event = Event::with(['tickets', 'channels', 'rooms'])->where(['slug' => $event_slug])->first();
        $tickets = $event->tickets;
        $channels = $event->channels;
        $rooms = $event->rooms;
        $organizer = Auth::guard('organizer')->user();
        $sessions = collect($rooms)->map(function ($room) {
            return Session::where(['room_id' => $room->id])->get();
        });
        $sessions = $sessions->flatten()->all();
        return view('events/detail', ['organizer' => $organizer, 'event' => $event, 'tickets' => $tickets, 'channels' => $channels, 'rooms' => $rooms, 'sessions' => []]);
    }
    public function create_event(Request $request, Auth $auth)
    {
        $organizer = $auth::guard('organizer')->user();

        if ($request->method() == "POST") {
            // TODO A2c - Enter information for a new event

            // TODO A2d – Enter already used slug for a new event
            // TODO A2e – Enter invalid slug for an event
            $valid_data = Validator::make($request->all(), [
                'name' => ['required'],
                'slug' => ['required', 'exists:events,slug', 'regex:/^[a-z0-9-]+$/'],
                'date' => ['required']
            ], [
                'slug.exists' => 'Slug is already used',
                'slug.regex' => 'Slug must not be empty and only contain a-z, 0-9 and "-"',
            ]);

            $event = Event::create([
                'organizer_id' => $organizer->id,
                'name' => $request->name,
                'slug' => $request->slug,
                'date' => $request->date,
            ]);

            // TODO Install flash-message
            session()->flash('message', 'Event successfully created');

            // Redirect to event page
            return redirect(route("event", ['event_slug' => $event->slug]));
        }

        // TODO A2b - Show form for new event
        return view('events/create', ['organizer' => $organizer,]);
    }
    public function edit_event(Request $request, Auth $auth, $slug)
    {
        $organizer = $auth::guard('organizer')->user();
        $event = Event::where(['slug' => $slug])->first();

        if ($request->method() == "POST") {
            // TODO A2g – Update event information

            // TODO A2d – Enter already used slug for a new event
            // TODO A2e – Enter invalid slug for an event
            $valid_data = Validator::make($request->all(), [
                'name' => ['required'],
                'slug' => ['required', 'exists:events,slug', 'regex:/^[a-z0-9-]+$/'],
                'data' => ['required']
            ], [
                'slug.exists' => 'Slug is already used',
                'slug.regex' => 'Slug must not be empty and only contain a-z, 0-9 and "-"',
            ]);

            $event->update([
                'organizer_id' => $organizer->id,
                'name' => $valid_data['name'],
                'slug' => $valid_data['slug'],
                'date' => $valid_data['date'],
            ]);

            // TODO Install flash-message
            session()->flash('message', 'Event successfully updated');

            return redirect(route("event", ['slug' => $slug]));
        }

        // TODO A2f - Show form for updating event
        return view('create_event', ['event' => $event]);
    }

    // A3 – Manage tickets
    public function create_ticket(Request $request, Auth $auth, $slug)
    {
        $organizer = $auth::guard('organizer')->user();
        $event = Event::where(['slug' => $slug])->first();

        if ($request->method() == "POST") {
            // TODO A3b – Enter information for a new ticket 

            $valid_data = $request->validate([
                'event_id' => ['required'],
                'name' => ['required'],
                'cost' => ['required'],
                'special_validity' => ['required'],
            ]);

            if ($valid_data['special_validity'] == 'date') {
                $special_validity = ['type' => 'date', 'date' => $valid_data['date']];
            } else {
                $special_validity = ['type' => 'amount', 'amount' => $valid_data['amount']];
            }

            EventTicket::create([
                'organizer_id' => $organizer->id,
                'name' => $valid_data['name'],
                'slug' => $valid_data['slug'],
                'cost' => $valid_data['cost'],
                'special_validity' => json_encode($special_validity, JSON_UNESCAPED_UNICODE)
            ]);


            // TODO Install flash-message
            session()->flash('message', 'Ticket successfully created');

            return redirect(route("event", ['slug' => $slug]));
        }

        // TODO A3a – Show form for new ticket
        return view('create_event', ['event' => $event]);
    }

    // TODO A4 – Manage sessions
    public function session(Request $request)
    {
        // TODO A4b – Enter information for a new session
        if ($request->method() == "POST") {
        }

        // TODO A4e – Update session information
        if ($request->method() == "PATCH") {
        }

        // TODO A4a – Show form for new session

    }

    // A5 - Manage channels (realized)
    public function create_channel(Request $request, $slug)
    {
        $event = Event::where(['slug' => $slug])->first();

        if ($request->method() == "POST") {
            $valid_data = $request->validate([
                'name' => ['required']
            ]);

            Channel::create([
                'event_id' => $event->id,
                'name' => $valid_data['name']
            ]);

            // TODO Install flash-message
            session()->flash('message', 'Channel successfully created');

            // TODO A5b – Enter information for a new channel
            return redirect(route("event", ['slug' => $slug]));
        }

        // TODO A6a – Show form for new room
        return view('create_channel', ['event' => $event]);
    }

    // A6 – Manage rooms
    public function create_room(Request $request, $slug)
    {
        $event = Event::where(['slug' => $slug])->first();

        if ($request->method() == "POST") {
            // TODO A6b – Enter information for a new room
            $valid_data = $request->validate([
                'name' => ['required'],
                'channel_id' => ['required', 'exists:rooms, id'],
                'capacity' => ['required'],
            ]);

            $channel = Channel::where(['id' => $valid_data['channel_id']]);
            Room::create([
                'channel_id' => $channel->id,
                'name' => $valid_data['name'],
                'capacity' => $valid_data['capacity']
            ]);

            // TODO Install flash-message
            session()->flash('message', 'Room successfully created');

            return redirect(route("event", ['slug' => $slug]));
        }

        $channels = $event->channels();

        // TODO A6a – Show form for new room
        return view('create_room', ['event' => $event, 'channels' => $channels]);
    }

    // TODO Room capacity
}
