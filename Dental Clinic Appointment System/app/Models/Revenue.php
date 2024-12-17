<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    public function index()
{
    $revenues = Revenue::all()->sum('amount'); // Assuming 'amount' is a column in the 'revenues' table
    
    // Pass the total revenue to the view
    return view('dashboard', ['totalRevenue' => $revenues]);
}

}
