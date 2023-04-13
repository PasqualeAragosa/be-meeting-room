<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = Reservation::paginate(10);

        return response()->json([
            'success' => true,
            'results' => $reservations,
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
                'notes' => 'required',
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
        $reservation->surname = $data['surname'];
        $reservation->date = $data['date'];
        $reservation->timeFrom = $data['timeFrom'];
        $reservation->timeTo = $data['timeTo'];
        $reservation->notes = $data['notes'];
        $reservation->save();

        return response()->json([
            "success" => true,
            "data" => $reservation
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::where('id', $id)->first();

        // Change date format using Carbon
        $reservation->date = Carbon::createFromFormat('Y-m-d', $reservation->date)->format('d-m-Y');

        if ($id) {
            return response()->json([
                'success' => true,
                'results' => $reservation,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'results' => 'Reservation not found'
            ]);
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
                'notes' => 'required',
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
        $reservation->notes = $request->notes;
        $result = $reservation->save();

        if ($result) {
            return response()->json([
                'success' => true,
                'results' => 'Reservation has been changed'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'results' => 'PUT operation is failed'
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

        $result = $reservation->delete();

        if ($result) {
            return response()->json([
                'success' => true,
                'results' => "Reservation " . $id . " has been deleted"
            ]);
        } else {
            return response()->json(null);
        }
    }

    public function search($string)
    {

        $result = Reservation::where("notes", "like", "%" . $string . "%")->get();

        if ($result) {
            return response()->json([
                'success' => true,
                'results' => $result
            ]);
        }
    }
}
