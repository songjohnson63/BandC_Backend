<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResource; // Import the ExperienceResource
use App\Helpers\ApiResponseHelper;

class CustomerApiController extends Controller
{
    /**
     * Display a listing of the students.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $customers = Customer::all(); 
        return ApiResponseHelper::success($customers);

    }

    /**
     * Store a newly created student in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,rather_not_to_say',
            'address' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create($validated);

        return ApiResponseHelper::success($customer, "Customer created successfully", 201);

    }

    /**
     * Display the specified student.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id); 

        return new CustomerResource($customer);
    }

    /**
     * Update the specified student in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,rather_not_to_say',
            'address' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    /**
     * Remove the specified student from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(null, 204);
    }
}
