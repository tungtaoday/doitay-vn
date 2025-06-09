<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Page;
use App\Models\Review;
use App\Models\Company;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Models\Rating;
use App\Models\RatingDetail;
use App\Models\Feature;
use App\Models\RatingReaction;
// use App\Models\ReactionType;

class RatingController extends Controller
{
    /*
    public function rateReaction(Request $request)
    {
        $request->validate( [
            'review_id' => 'required|exists:ratings,id',
            'reaction_type' => 'required|exists:reaction_types,type',
        ]);

        $reaction = RatingReaction::updateOrCreate(
            [
                'review_id' => $request->review_id,
                'user_id' => auth()->id(),
            ],
            [
                'reaction_type' => $request->reaction_type,
            ]
        );

        return response()->json(['success' => true, 'reaction' => $reaction]);
    }
    */

}