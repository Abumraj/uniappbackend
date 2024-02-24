<?php
namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\AspirantSubscription;
use Illuminate\Http\Request;
use App\Http\Resources\RemedialResource;
use App\Models\AspirantSubscription as ModelsAspirantSubscription;

class AspirantSubscriptionController extends Controller
{
    public function index()
    {
        $courses = ModelsAspirantSubscription::all();

            return RemedialResource::collection($courses);
    }
}
