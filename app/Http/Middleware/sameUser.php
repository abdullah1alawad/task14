<?php

namespace App\Http\Middleware;

use App\Models\Review;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Traits\GeneralTrait;

class sameUser
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $review=Review::find($request->id);
        $user_id=$review->user_id;
        if(auth()->user()->id==$user_id)
            return $next($request);
        else
            return $this->errorResponse('Authenticated but Unauthorized',403);
    }
}
