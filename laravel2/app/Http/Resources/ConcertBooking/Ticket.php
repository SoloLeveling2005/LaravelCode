<?php

namespace App\Http\Resources\ConcertBooking;

use App\Models\Booking;
use App\Models\Location;
use App\Models\Location_seat;
use App\Models\Location_seat_row;
use App\Models\Show;
use Illuminate\Http\Resources\Json\JsonResource;

class Ticket extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $seat = Location_seat::where(['ticket_id'=>$this->id])->first();
        $row = Location_seat_row::where(['id'=>$this->$seat->location_seat_row_id])->first();
        $show = Show::with(['concert'])->where(['id'=>$row->show_id])->first();
        $concert = $show->concert;
        $location = Location::where(['id'=>$concert->location_id])->first();
        $booking = Booking::where(['id'=>$this->booking_id])->first();
        return [
            'id'=>$this->id,
            'code'=>$this->code,
            "name"=> $booking->name,
            "created_at"=> $this->created_at,
            "row"=> [
                "id"=> $row->id,
                "name"=> $row->name
            ],
            "seat"=> $seat->number,
            "show"=> [
                "id"=> $show->id,
                "start"=> $show->start,
                "end"=> $show->end,
                "concert"=> [
                    "id"=> $concert->id,
                    "artist"=> $concert->artist,
                    "location"=> [
                        "id"=> $location->id,
                        "name"=> $location->name
                    ]
                ]
            ]
        ];
    }
}
