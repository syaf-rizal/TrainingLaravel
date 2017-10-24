<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use rndediiv2\SmartDevTable\Facade\SmartDevTable;
use App\Libraries\SmartDevMain;

class RegenciesController extends Controller
{
    public function listRegencies(Request $request)
    {
        $sqlRegencies = "SELECT a.id, b.name as provinces, a.name as regencies
        FROM regencies a LEFT JOIN provinces b
        ON a.province_id = b.id";
        SmartDevTable::keys(['id']);
        SmartDevTable::hiddens(['id']);
        SmartDevTable::columns([
            "a.id","b.name","a.name"
        ]);
        SmartDevTable::orderBy(1);
        SmartDevTable::sortBy("ASC");
        SmartDevTable::width([
            'provinces' => 150,
            'regencies' => 250
        ]);
        SmartDevTable::showSearch(TRUE);
        SmartDevTable::showChk(TRUE);
        SmartDevTable::single(TRUE);
        SmartDevTable::search([
            ['b.name', 'Provinces'],
            ['a.name', 'Regencies']
        ]);
        SmartDevTable::menu([
            'Preview' => [
                'GET', url('regencies'),'1', 'mdi-action-open-in-new', 'blue darken-1'
            ]
        ]);
        SmartDevTable::action(url('regencies'));
        SmartDevTable::postMethod(TRUE);
        SmartDevTable::tbTarget("listRegencies");
        SmartDevTable::expandRow(TRUE);
        SmartDevTable::title("Data Kabupaten Indonesia");

        SmartDevTable::bootstrapComponent([
            'classLarge12' => 'l',
            'classMiddle12' => 'm',
            'classSmall12'  => 's'
        ]);

        $smartTable = SmartDevTable::generate($sqlRegencies, $request);

        if($request->input('data-post'))
        {   
            return $smartTable;
        }
        else
        {
            $data = [
                'smartTable' => $smartTable
            ];
            return view('regencies', $data);
        }
    }

    public function generatorUuid()
    {
        $smartDevMain = new SmartDevMain();
        $arrData['objProvince'] = $smartDevMain->setSelectCombo("SELECT id, name FROM provinces", "id", "name", TRUE);
        $arrData['objSelectedProvince'] = 11;
        return view('regencies-form', $arrData);
    }
}
