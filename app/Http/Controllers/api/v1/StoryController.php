<?php

namespace App\Http\Controllers\api\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoryviewResource;
use App\Http\Resources\UserResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Story;
use App\Models\Storyview;
use App\Models\Storylike;
use App\Models\Storydislike;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
class StoryController extends Controller
{
    //

    public function index()
    {
   $currentUser = Auth::user()->id;
   $stalite    = User::find($currentUser);
   $stalite->assignRole('Stalite');

   $facultyId = User::find($currentUser)->department->faculty_id;
   //    return "$facultyId";
    $stories = Story::where('faculty_id', $facultyId)->orWhere('level', Story::LEVEL_GENERAL)->withCount('storylikes as likes')->withCount('storydislikes as dislikes')->withCount('storyviews as views')->get();
      return $stories;
//    return User::find(Auth::user()->id)->getRoleNames();
   if(Auth::user()->hasRole('Stalite')){
    } else if(Auth::user()->hasRole('Remedial')){
         $stories = Story::where('level', Story::LEVEL_REMEDIAL)->orWhere('level', Story::LEVEL_GENERAL)->withCount('storylikes as likes')->withCount('storydislikes as dislikes')->withCount('storyviews as views')->get();
         return $stories;

     } else if(Auth::user()->hasRole('Aspirant')){
         $stories = Story::where('level', Story::LEVEL_ASPIRANT)->orWhere('level', Story::LEVEL_GENERAL)->withCount('storylikes as likes')->withCount('storydislikes as dislikes')->withCount('storyviews as views')->get();
         return $stories;

     }else if(Auth::user()->hasRole('JUPEB')){
        $stories = Story::where('level', Story::LEVEL_JUPEB)->orWhere('level', Story::LEVEL_GENERAL)->withCount('storylikes as likes')->withCount('storydislikes as dislikes')->withCount('storyviews as views')->get();
        return $stories;

    }
    }
    public function createStory()
    {
         Notification::pushNotification('uniapp','Student Union', 'SU president posted an update');
    }

    public function storyViewUser($id)
    {
        // $result = collect();

        $users  = DB::table('storyviews') ->join('users', 'storyviews.user_id', '=', 'users.id')
                      ->select( 'users.name', 'users.imageUrl', 'storyviews.created_at')->get();
        // $storyview =  Storyview::where('story_id', $id)->users;
        // foreach ($storyview as $story) {
        //      $user =
        //      User::where('id',$story->user_id)->get();
        //      $result->push($user);
        //  }

                 return  StoryviewResource::collection( $users);
    }

    public function deleteStory($id)
    {
       Story::destroy($id);
       Storylike::where('story_id',$id)->destroy();
       Storydislike::where('story_id',$id)->destroy();
    }

    public function updateStoryCount(Request $request)
    {
   $currentUser = Auth::user()->id;

          Storyview::where('story_id', $request->id)->where('user_id', $currentUser )->insert();
    }

    public function likeStory(Request $request)
    {
        $currentUser = Auth::user()->id;

        Storylike::where('story_id', $request->id)->where('user_id', $currentUser )->insert();
    }

    public function dislikeStory(Request $request)
    {
        $currentUser = Auth::user()->id;

        Storydislike::where('story_id', $request->id)->where('user_id', $currentUser )->insert();
    }
}
