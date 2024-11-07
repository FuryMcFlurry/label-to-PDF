<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\View\View;

class LabelController extends Controller
{
    protected string $base_url;
    protected string $auth;
    protected string $api_user;

    protected string $companyID;

    public function __construct()
    {
        $this->base_url = getenv('QLS_API_BASE_URL'); //https://api.pakketdienstqls.nl/
        $this->auth = getenv('QLS_API_PASS');
        $this->api_user = getenv('QLS_API_USER');

        $this->companyID = '9e606e6b-44a4-4a4e-a309-cc70ddd3a103'; // replace this with smart code.
    }


    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() : View
    {
        return view('label.listView');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function overviewShipment(){
        return view('track.shipmentOverview');
    }

    public function viewShipment(string $id)
    {
        return view('label.individualView', ['shipmentId' => $id]);
    }


    public function showBarcode()
    {
        $generator = new BarcodeGeneratorHTML();
        $barcode = '1234567890'; // test value

        return view('barcode', compact('generator', 'barcode'));
    }

    public function list(){
        return view('label.listView');
    }

    /**
     * This method gives me multiple shipments from API.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Psr\Http\Message\StreamInterface
     */
    public function getList(GuzzleClient $client)
    {
        try {
            $guzzleResponse = $client->get("{$this->base_url}companies/{$this->companyID}/shipments", [
                'auth' => [
                    $this->api_user,
                    $this->auth
                ]
            ]);

            if ($guzzleResponse->getStatusCode() === 200) {
                return $guzzleResponse->getBody();
            }

            return back()->with('danger', __("No shipments found for the specified company."));
        } catch (Throwable $exception) {
            return back()->with('danger', __("Something went wrong while fetching shipments."));
        }
    }

    /**
     * This method gives me one shipment from API.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Psr\Http\Message\StreamInterface
     */
    public function getShipment(Request $request,  GuzzleClient $client)
    {
        $shipmentID = $request->id;
        try {
            $guzzleResponse = $client->get($this->base_url . 'companies/' . $this->companyID . '/shipments/' . $shipmentID,
                [
                    'auth' => [
                        $this->api_user,
                        $this->auth
                    ]
                ]
            );

            if ($guzzleResponse->getStatusCode() === 200) {
                return $guzzleResponse->getBody();
            }

            return back()->with('danger', __("No results found"));
        } catch (Throwable $exception) {
            return back()->with('danger', __("Something went wrong"));
        }
    }
}
