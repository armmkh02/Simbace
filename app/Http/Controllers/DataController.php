<?php

namespace App\Http\Controllers;

use App\Components\simBaseAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Lumen\Application;

class DataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @return View|Application
     */
    public function index()
    {
        return view('app');
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return (new simBaseAuth())->callFunction('f_api_return_accommodations_data');
    }

    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function saveData(Request $request): array
    {
        $this->validate($request, [
            "acc_id" => 'required|int',
            "number" => 'required|int',
            "room type" => 'required|int',
            "check in" => 'required|date',
            "nights" => 'required|int',
        ]);

        $data = $this->generateArray($request->all());

        return (new simBaseAuth())->callFunction('f_api_save_accommodations_data', $data);
    }

    /**
     * @param string $date
     * @return int
     */
    public function getDate(string $date): int
    {
        $firstDate = Carbon::createFromDate($date);
        $secondDate = Carbon::createFromDate("1970-01-01");

       return $firstDate->diffInDays($secondDate);
    }

    /**
     * @param array $request
     * @return array
     */
    public function generateArray(array $request): array
    {
        return [
            "acc_id" => $request['acc_id'],
            "number" => $request['number'],
            "room type" => $request['room type'],
            "check in" => $this->getDate($request['check in']),
            "nights" => $request['nights']
        ];
    }
}
