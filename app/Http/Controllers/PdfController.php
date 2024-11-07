<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function generatePdf(Request $request)
    {
        $shipmentData = $request->input('shipment');  // Retrieve 'shipment' data from the request
        $title = $shipmentData['delivery_contact']['name'] . "'s" . ' ' . $shipmentData['product']['name'];
        $pdf = PDF::loadView('pdf.LabelPdf', ['shipment' => $shipmentData , 'title' => $title]);
        return $pdf->download($title . '.pdf');
    }
}