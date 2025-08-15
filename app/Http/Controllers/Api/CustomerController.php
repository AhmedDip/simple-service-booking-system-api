<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Support\CommonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomerController extends Controller
{
    public function listServices()
    {
        $commonResponse = new CommonResponse();

        try {
            $services = Service::where('status', 'active')->get();
            $commonResponse->success('Services Fetched Successfully', $services);
        } catch (Throwable $e) {
            Log::channel('booking')->info('LIST_SERVICES_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }

    public function storeBooking(StoreBookingRequest $request)
    {
        $commonResponse = new CommonResponse();

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'user_id'      => Auth::id(),
                'service_id'   => $request->service_id,
                'booking_date' => $request->booking_date,
                'status'       => 'pending',
            ]);

            DB::commit();

            $commonResponse->success('Booking created successfully', $booking);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('booking')->info('BOOKING_CREATE_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }

    public function myBookings()
    {
        $commonResponse = new CommonResponse();

        try {
            $bookings = Booking::with('service')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

            $commonResponse->success('Bookings Fetched Successfully!', $bookings);

        } catch (Throwable $e) {
            Log::channel('booking')->info('LIST_MY_BOOKINGS_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }
}
