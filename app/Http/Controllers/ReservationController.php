<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $team = Group::where('user_id', Auth::id())->pluck('team')->first();

        // Salvo solo i risultati che appartengono all'utente loggato
        $reservations = Reservation::where('team_id', $team);

        // Se presente un valore all'interno l'input di ricerca filtro i risultati
        if ($request->keywords) {
            $reservations->where("note", "like", "%" . $request->keywords . "%");
        }

        // Salvo i risultati come array
        $results = $reservations->paginate(10);

        // Se l'array è vuoto
        if ($results->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No reservations match your search',
                'results' => $results
            ]);
        }

        // Formatto i risultati
        $formattedResults = $results->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'team_id' => $reservation->team_id,
                'user' => [
                    'name' => $reservation->name,
                    'surname' => $reservation->surname
                ],
                'date' => $reservation->date,
                'timeFrom' => $reservation->timeFrom,
                'timeTo' => $reservation->timeTo,
                'note' => $reservation->note
            ];
        });

        $data = [
            'current_page' => $results->currentPage(),
            'data' => $formattedResults,
            'last_page' => $results->lastPage(),
            'per_page' => $results->perPage(),
            'total' => $results->total(),
            'next_page_url' => $results->nextPageUrl(),
            'prev_page_url' => $results->previousPageUrl(),
            'from' => $results->firstItem(),
            'to' => $results->lastItem()
        ];

        return response()->json([
            'success' => true,
            'total_results' => $results->count(),
            'results' => $data
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
    public function store(StoreReservationRequest $request)
    {
        $data = $request->all();

        $team = Group::where('user_id', Auth::id())->pluck('team')->first();

        $reservation = new Reservation();
        $reservation->name = $data['user']['name'];
        $reservation->team_id = $team;
        $reservation->surname = $data['user']['surname'];
        $reservation->date = $data['date'];
        $reservation->timeFrom = $data['timeFrom'];
        $reservation->timeTo = $data['timeTo'];
        $reservation->note = $data['note'];
        $reservation->save();

        return response()->json([
            "success" => true,
            'results' => [
                'id' => $reservation->id,
                'team_id' => $reservation->team_id,
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
        $team = Group::where('user_id', Auth::id())->pluck('team')->first();

        if ($team !== $reservation->team_id) {
            return response()->json([
                'success' => false,
                'results' => 'Forbidden'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'results' => [
                'id' => $reservation->id,
                'team_id' => $reservation->team_id,
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
    public function update(UpdateReservationRequest $request, $id)
    {
        $request->request->add(['id' => $id]);

        $data = $request->all();

        // Cerco la prenotazione da aggiornare
        $reservation = Reservation::find($request->id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not founded',
            ], 404);
        }

        $team = Group::where('user_id', Auth::id())->pluck('team')->first();

        if ($team !== $reservation->team_id) {
            return response()->json([
                'success' => false,
                'message' => 'Non sei autorizzato ad aggiornare questa prenotazione',
            ], 403);
        }

        $reservation->name = $data['user']['name'];
        $reservation->surname = $data['user']['surname'];
        $reservation->date = $data['date'];
        $reservation->timeFrom = $data['timeFrom'];
        $reservation->timeTo = $data['timeTo'];
        $reservation->note = $data['note'];
        $result = $reservation->save();

        if ($result) {
            return response()->json([
                'success' => true,
                'results' => 'Reservation has been changed',
                'id_changed' => $data['id']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'results' => 'Error Server'
            ], 500);
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
        $team = Group::where('user_id', Auth::id())->pluck('team')->first();

        $reservation = Reservation::where('team_id', $team)->find($id);

        if (!$reservation) {
            return response()->json([
                'success' => false,
                'message' => "Reservation id: " . $id . " not found",
            ], 404);
        }

        $success = $reservation->delete();

        return response()->json([
            'success' => $success ? true : false,
            'message' => "Reservation " . $id . " has been " . ($success ? "deleted" : "not deleted"),
        ]);
    }
}
