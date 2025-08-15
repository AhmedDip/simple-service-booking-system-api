<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Booking;
use App\Models\Service;
use App\Support\CommonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminController extends Controller
{
    public function storeService(StoreServiceRequest $request)
    {
        $commonResponse = new CommonResponse();

        try {
            DB::beginTransaction();

            $service = Service::create($request->validated());

            DB::commit();

            $commonResponse->success('Service created successfully!', $service);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('booking')->info('SERVICE_CREATE_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }

    public function updateService(UpdateServiceRequest $request, $id)
    {
        $commonResponse = new CommonResponse();

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);
            $service->update($request->validated());

            DB::commit();

            $commonResponse->success('Service updated successfully!', $service);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('booking')->info('SERVICE_UPDATE_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }

    public function deleteService($id)
    {
        $commonResponse = new CommonResponse();

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);
            $service->delete();

            DB::commit();

            $commonResponse->success('Service deleted successfully!');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::channel('booking')->info('SERVICE_DELETE_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }

    public function listAllBookings()
    {
        $commonResponse = new CommonResponse();

        try {
            $bookings = Booking::with(['user','service'])->latest()->get();
            $commonResponse->success('All bookings fetched successfully!', $bookings);
        } catch (Throwable $e) {
            Log::channel('booking')->info('LIST_ALL_BOOKINGS_FAILED', ['error' => $e->getMessage()]);
            $commonResponse->fail('Failed! '.$e->getMessage());
        }

        return $commonResponse->commonApiResponse();
    }
}
