<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    const VALIDATION_RULES = [
        'name' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        'address_line_1' => 'nullable|string|max:255',
        'address_line_2' => 'nullable|string|max:255',
        'zip_code' => 'nullable|string|max:7',
        'city' => 'nullable|string|max:255',
        'province' => 'nullable|string|max:2',
        'country' => 'nullable|string|max:2',
        'tjm' => 'nullable|decimal:0,2',
        'email' => 'nullable|string|max:255',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->orderBy('name', 'ASC')
            ->paginate(10);

        return Inertia::render('Customers', [
            'customers' => $customers,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);

        $customer = new Customer();
        $customer->name = $validatedData['name'];
        $customer->company = $validatedData['company'];
        $customer->address_line_1 = $validatedData['address_line_1'];
        $customer->address_line_2 = $validatedData['address_line_2'];
        $customer->zip_code = $validatedData['zip_code'];
        $customer->city = $validatedData['city'];
        $customer->province = $validatedData['province'];
        $customer->country = $validatedData['country'];
        $customer->tjm = $validatedData['tjm'];
        $customer->email = $validatedData['email'];

        try {
            $customer->save();
            return to_route('customers')->with('success', 'Client créé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(Customer $customer)
    {
        if (!$customer->exists) {
            abort(404, "Client non trouvée");
        }
        return Inertia::render('CustomersForm', [
            'customer' => $customer->only(['id', 'name', 'company', 'address_line_1', 'address_line_2', 'zip_code', 'city', 'province', 'country', 'tjm', 'email'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate(self::VALIDATION_RULES);
        try {
            $customer->update($validatedData);
            return to_route('customers')->with('success', 'Client modifié avec succès');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return to_route('customers')->with('success', 'Client supprimé  avec succès');
        } catch (\Exception $e) {
            return back()->with('error',  $e->getMessage());
        }
    }
}
