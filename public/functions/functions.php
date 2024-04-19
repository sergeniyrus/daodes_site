<?php
use Illuminate\Support\Facades\DB;

function updateViews($newId)
{
    DB::table('news')
        ->where('id', $newId)
        ->increment('views');
}

function offer($state)
{
$OfferState = DB::table('offers')->where('state', $state);
return view('dao')->with('OfferState', $OfferState);
}