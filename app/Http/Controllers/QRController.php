<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Link;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    public function index(Request $request, $id)
    {
        $link = Link::findOrFail($id);

        return view('qr/content', ['link' => $link]);
    }
}
