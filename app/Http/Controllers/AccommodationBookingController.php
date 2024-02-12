<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\AccommodationBooking;
use App\Models\Accommodation;
//trigger an email
use App\Jobs\SendEmailJob;
use App\Mail\ReservationCreated;

use Log;

class AccommodationBookingController extends Controller
{
    public function index() {
        $reservations = AccommodationBooking::with(['accommodation', 'user'])->get();
        return Inertia::render('Dashboard/Staff/Reservations', [
            'reservations' => $reservations
        ]);
    }

    public function create($id) {

    }
    
    public function show() {

    }
    
    public function store(Request $request) {
        $request->validate([
            'accommodation_id' => 'required|numeric',
            'status' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'amount_of_days' => 'required|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        if($request->user_id) {
            $booking = new AccommodationBooking([
                'accommodation_id' => $request->accommodation_id,
                'user_id' => $request->user_id,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'amount_of_days' => $request->amount_of_days,
                'total' => $request->total,
                'note' => $request->note,
            ]);

            
            // Save the booking to the database
            $booking->save();
            $data = [];
            $data['user'] = User::find($request->user_id)->get();
            $data['accommodation'] = Accommodation::find($request->accommodation_id)->get();
            $data['booking'] = $booking;
            $template = new ReservationCreated($data);

            Log::info($data['user'][0]->id);

            SendEmailJob::dispatch($data, $template);

            return response()->json(['message' => 'Reservation Created']);
        } else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'id_number' => $request->id_number,
                'activated' => true,
                'password' => bcrypt($request->id_number),
            ]);

            $booking = new AccommodationBooking([
                'accommodation_id' => $request->accommodation_id,
                'user_id' => $user->id,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'amount_of_days' => $request->amount_of_days,
                'total' => $request->total,
                'note' => $request->note,
            ]);

            $data = [];
            $data['user'] = User::find($user->id)->get();
            $data['accommodation'] = Accommodation::find($request->accommodation_id)->get();
            $data['booking'] = $booking;
            $template = new ReservationCreated($data);

            // Log::info($data['user'][0]->name);

            SendEmailJob::dispatch($data, $template);

            $booking->save();

            return response()->json(['message' => 'User and Reservation Created']);

        }
    } 
    
    public function checkIn(Request $request) {
        try {
            $reservation = AccommodationBooking::find($request->id);

            if (!$reservation) {
                return response()->json(['error' => 'Reservation not found'], 404);
            }

            $reservation->check_in = $reservation->check_in == 0 ? 1 : 0;
            $reservation->time_check_in = date('Y-m-d H:i:s', strtotime('+2 hours'));
            $reservation->save();
    
            // Retrieve the updated list of reservations
            $reservations = AccommodationBooking::with(['accommodation', 'user'])->get();

            return response()->json([
                'message' => 'Reservation updated successfully',
                'reservations' => $reservations,
            ]);
    
        } catch (\Exception $e) {
            // Handle the exception and return an error response
            return response()->json([
                'error' => 'Failed to update reservation',
            ], 500);
        }
    }

    public function update() {

    }
    
    public function destroy(Request $request) {
        $reservation = AccommodationBooking::findOrFail($request->id);

        $reservation->delete();

        $reservations = AccommodationBooking::with(['accommodation', 'user'])->get();
        return response()->json([
            'reservations' => $reservations
        ]);
    }

    public function searchReservation() {

    }

    public function idNumber(Request $request) {
        Log::info($request);
        $request->validate([
            'idNumber' => 'required'
        ]);

        // Search for the user with the given idNumber
        $user = User::where('id_number', $request->input('idNumber'))->first();

        if ($user) {
            // If user is found, return the user data
            return response()->json([
                'user' => $user,
                'search_response' => 1,
                'connection' => 1
            ]);
        } else {
            // If user is not found, return an appropriate response
            return response()->json([
                'user' => 'No guest was found',
                'search_response' => 0,
                'connection' => 1
            ]);
        }
    }
}
