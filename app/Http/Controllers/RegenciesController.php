<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\SmartDevTable;

class RegenciesController extends Controller
{
    public function listRegencies(Request $request)
    {
        $smartDevTable = new SmartDevTable();
        $sqlRegencies = "SELECT a.id, b.name as provinces, a.name as regencies
                         FROM regencies a LEFT JOIN provinces b
                         ON a.province_id = b.id";
        $smartDevTable->columns([
            "a.id","b.name","a.name"
        ]);
        $smartDevTable->width([
            'provinces' => 150,
            'regencies' => 250
        ]);
        $smartDevTable->search([
            ['b.name', 'Provinces'],
            ['a.name', 'Regencies']
        ]);
        $smartDevTable->keys(['id']);
        $smartDevTable->hiddens(['id']);
        $smartDevTable->menu([
            'Preview' => [
                'GET', 'localhost:8000','1', 'mdi-action-open-in-new', 'blue darken-1'
            ]
        ]);
        $smartDevTable->action('localhost:8000');
        $smartDevTable->postMethod(TRUE);
        $smartDevTable->orderBy(1);
        $smartDevTable->sortBy("ASC");
        $smartDevTable->showSearch(TRUE);
        $smartDevTable->showChk(TRUE);
        $smartDevTable->single(TRUE);
        $smartDevTable->expandRow(TRUE);
        $smartDevTable->tbTarget("listRegencies");
        $smartDevTable->title("Data Kabupaten Indonesia");
        return $smartDevTable->generate($sqlRegencies, $request);

        // $data = [
        //     'judul' => 'Training Laravel'
        // ];
        // return view('regencies', $data);
    }
}
