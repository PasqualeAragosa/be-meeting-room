<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::orderByDesc('id')->where('user_id', Auth::id())->paginate(10);

        foreach ($reservations as $reservation) {
            $results[] = [
                'id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'user' => [
                    'name' => $reservation->name,
                    'surname' => $reservation->surname
                ],
                'date' => $reservation->date,
                'timeFrom' => $reservation->timeFrom,
                'timeTo' => $reservation->timeTo,
                'note' => $reservation->note
            ];
        }

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreReservationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'name' => 'required',
                'surname' => 'required',
                'date' => 'required|date|date_format:d-m-Y',
                'timeFrom' => 'required',
                'timeTo' => 'required|after:timeFrom',
                'note' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $reservation = new Reservation();
        $reservation->name = $data['name'];
        $reservation->user_id = Auth::id();
        $reservation->surname = $data['surname'];
        $reservation->date = $data['date'];
        $reservation->timeFrom = $data['timeFrom'];
        $reservation->timeTo = $data['timeTo'];
        $reservation->note = $data['note'];
        $reservation->save();

        return response()->json([
            "success" => true,
            'results' => [
                'id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'user' => [
                    'name' => $reservation->name,
                    'surname' => $reservation->surname,
                ],
                'date' => $reservation->date,
                'timeFrom' => $reservation->timeFrom,
                'timeTo' => $reservation->timeTo,
                'note' => $reservation->note,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {

        if (Auth::id() === $reservation->user_id && $reservation) {
            return response()->json([
                'success' => true,
                'results' => [
                    'id' => $reservation->id,
                    'user_id' => $reservation->user_id,
                    'user' => [
                        'name' => $reservation->name,
                        'surname' => $reservation->surname,
                    ],
                    'date' => $reservation->date,
                    'timeFrom' => $reservation->timeFrom,
                    'timeTo' => $reservation->timeTo,
                    'note' => $reservation->note,
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'results' => 'Reservation not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateReservationRequest  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make(
            $data,
            [
                'name' => 'required',
                'surname' => 'required',
                'date' => 'required|date|date_format:d-m-Y',
                'timeFrom' => 'required',
                'timeTo' => 'required|after:timeFrom',
                'note' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $reservation = Reservation::find($request->id);
        $reservation->name = $request->name;
        $reservation->surname = $request->surname;
        $reservation->date = $request->date;
        $reservation->timeFrom = $request->timeFrom;
        $reservation->timeTo = $request->timeTo;
        $reservation->note = $request->note;
        $result = $reservation->save();

        if ($result && Auth::id() === $reservation->user_id) {
            return response()->json([
                'success' => true,
                'results' => 'Reservation has been changed',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'results' => 'Reservation not founded'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if ($id && $reservation) {
            return response()->json([
                'success' => $reservation->delete(),
                'message' => "Reservation " . $id . " has been deleted",
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Reservation id: " . $id . " not found",
            ]);
        }
    }

    public function search($string)
    {

        $reservations = Reservation::where("note", "like", "%" . $string . "%")->where('user_id', Auth::id())->get();

        foreach ($reservations as $reservation) {
            $results[] = [
                'id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'user' => [
                    'name' => $reservation->name,
                    'surname' => $reservation->surname
                ],
                'date' => $reservation->date,
                'timeFrom' => $reservation->timeFrom,
                'timeTo' => $reservation->timeTo,
                'note' => $reservation->note
            ];
        }

        if ($results) {
            return response()->json([
                'success' => true,
                'results' => $results
            ]);
        }
    }
}
