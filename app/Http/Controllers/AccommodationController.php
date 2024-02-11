<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use App\Models\Accommodation;
use App\Models\AccommodationImagePath;
use App\Models\AccomodationAmenities;
use Illuminate\Support\Facades\Storage;

class AccommodationController extends Controller
{
    public function index() {
        $accommodations = Accommodation::with(['images' => function ($query) {
            // Filter images to get only the featured image
            $query->where('featured_image', true);
        }])->get();

        return inertia::render('Dashboard/Staff/Accommodations', [
            'accommodations' => $accommodations
        ]);
    }

    public function show($id) {
        $accommodation = Accommodation::with(['images'])->find($id);

        if (!$accommodation) {
            // handle the case where the accommodation is not found, e.g., redirect or show an error page.
            abort(404, 'Accommodation not found');
        }

        return Inertia::render('Detail/AccommodationDetail', [
            'accommodation' => $accommodation,
        ]);
    }

    public function create() {
        // $amenities = AccommodationAmenity::all(); // Adjust the model and query based on your actual setup

        // Pass the amenities data to the Inertia view
        return Inertia::render('Dashboard/Staff/CreateOrUpdate/AccommodationCreate', [
            // 'amenities' => $amenities,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('accommodations')],
            'description' => 'required|string',
            'price_per_adult' => 'required',
            'price_per_child' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user(); // Get the authenticated user

        $accommodation = Accommodation::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price_per_adult' => $request->input('price_per_adult'),
            'price_per_child' => $request->input('price_per_child'),
            'amenities' => json_encode($request->input('amenities')),
            'user_id' => $user->id, // Save the current user's ID
        ]);
    
        
        if ($request->hasFile('images')) {
            $uploadedImages = [];    
            $featuredImageIndex = $request->input('featuredImage');

            foreach ($request->file('images') as $index => $image) {
                $path = $image->storeAs(
                    "accommodations/{$accommodation->id}",
                    $image->hashName(),
                    'public'
                );

                $uploadedImages[] = $path;

                AccommodationImagePath::create([
                    'accommodation_id' => $accommodation->id,
                    'image_path' => $path,
                    'featured_image' => $index === (int)$featuredImageIndex,
                ]);
            }
        }
    
        return Inertia::render('Dashboard/Staff/CreateOrUpdate/AccommodationCreate', [
            'success' => 'Accommodation created successfully.',
        ]);
    
    }

    public function edit($id)  {
        $accommodation = Accommodation::with(['images'])->find($id);

        if (!$accommodation) {
            // handle the case where the accommodation is not found, e.g., redirect or show an error page.
            abort(404, 'Accommodation not found');
        }

        return Inertia::render('Dashboard/Staff/CreateOrUpdate/AccommodationEdit', [
            'accommodation' => $accommodation,
        ]);
    }

    public function update() {

    }

    public function destroy($id)
    {
        // Step 1: Retrieve the accommodation by its ID
        $accommodation = Accommodation::findOrFail($id);

        // Step 2: Delete the associated images from storage
        $images = $accommodation->images;
        foreach ($images as $image) {
            Storage::delete($image->image_path);
            $image->delete();
        }

        // Step 3: Delete the accommodation record
        $accommodation->delete();

        // Step 4: Get Accommodation and pass it to the front
        $accommodations = Accommodation::with(['images' => function ($query) {
            // Filter images to get only the featured image
            $query->where('featured_image', true);
        }])->get();

        return inertia::render('Dashboard/Staff/Accommodations', [
            'accommodations' => $accommodations,
        ])->with('success', 'Accommodations updated successfully!');
    }

    public function searchAccommodations() {

    }

}
