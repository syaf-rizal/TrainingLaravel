<?php
/**
 * SmartDevTable For Laravel
 * Versioning Alpha 1.0 - 13 Oct 2017 (First Commit)
 * Modified : syafrizal (syafrizal@edi-indonesia.co.id)
 */

namespace App\Libraries;
use Illuminate\Http\Request;
use DB;
error_reporting(E_ERROR);

class SmartDevTable
{
    protected $rows             = [];
    protected $columns          = [];
    protected $hiderows         = [];
    protected $keys             = [];
    protected $proses           = [];
    protected $keycari          = [];
    protected $heading          = [];
    protected $width            = [];
    protected $menuWidth        = "";
    protected $autoHeading      = TRUE;
    protected $showChk          = TRUE;
    protected $useWhere         = FALSE;
    protected $caption          = NULL;
    protected $template         = NULL;
    protected $newLine          = "";
    protected $language         = "ID";
    protected $emptyCell        = "&nbsp;";
    protected $action           = "";
    protected $details          = "";
    protected $baris            = "10";
    protected $db               = "";
    protected $hal              = "AUTO";
    protected $uri              = "";
    protected $showSearch       = TRUE;
    protected $single           = TRUE;
    protected $dropdown         = TRUE;
    protected $orderBy          = 1;
    protected $groupBy          = [];
    protected $sortBy           = "ASC";
    protected $postMethod       = FALSE;
    protected $tbTarget         = "";
    protected $title            = "";
    protected $expandRow        = FALSE;
    protected $addFilter        = FALSE;
    protected $fieldFilters     = "";
    protected $dbComboBox       = [];
    protected $setTrId          = FALSE;
    protected $attrId           = "";
    protected $callBack         = "";
    protected $filedCallBack    = "";
    protected $divAppend        = FALSE;

    
    public function __constructor() 
    {
        $this->hiderows[] = 'HAL';    
    }

    public function width($row) 
    {
        $this->width = $row;
        return;
    }

    public function menuWidth($row)
    {
        $this->menuWidth = $row;
        return;
    }

    public function showSearch($showSearch)
    {
        $this->showSearch = $showSearch;
        return;
    }

    public function showChk($showChk)
    {
        $this->showChk = $showChk;
        return;
    }

    public function useWhere($useWhere)
    {
        $this->useWhere = $userWhere;
        return;
    }

    public function columns($col)
    {
        $this->columns = $col;
        return;
    }

    public function groupBy($fields)
    {
        if(!is_array($fields))
        {
            return FALSE;
        }
        $this->groupBy = $fields;
        return;
    }

    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return;
    }

    public function sortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        return;
    }

    public function rowCount($row)
    {
        $this->baris = $row;
        return;
    }

    public function action($action)
    {
        $this->action = $action;
        return;
    }

    public function detail($uri)
    {
        $this->details = $uri;
        return;
    }

    public function tbTarget($target)
    {
        $this->tbTarget = $target;
        return;
    }

    public function expandRow($expand)
    {
        $this->expandRow = $expand;
        return;
    }

    public function hiddens($row)
    {
        if(!is_array($row))
        {
            $row = array($row);
        }
        foreach($row as $a)
        {
            if(!in_array($a, $this->hiderows)) $this->hiderows[] = $a;
        }
        return;
    }

    public function keys($row)
    {
        if(!is_array($row))
        {
            $row = array($row);
        }
        foreach($row as $a)
        {
            if(in_array($a, $this->keys)) $this->keys[] = $a;
        }
        return;
    }

    public function menu($row)
    {
        if(!is_array($row))
        {
            return FALSE;
        }
        $this->proses = $row;
        return;
    }

    public function search($row)
    {
        if(!is_array($row))
        {
            return FALSE;
        }
        $this->keycari = $row;
        return;
    }

    public function single($more)
    {
        $this->single = $more;
        return;
    }

    public function dropdown($btn)
    {
        $this->dropdown = $btn;
        return;
    }

    public function postMethod($postMethod)
    {
        $this->postMethod = $postMethod;
        return;
    }

    public function addFilter($clause)
    {
        $this->addFilter = $clause;
        return;
    }

    public function fieldFilter($fields)
    {
        $this->fieldFilters = $fields;
        return;
    }

    public function setTrId($attrId)
    {
        $this->setTrId = $attrId;
        return;
    }

    public function attrId($attr)
    {
        $this->attrId = $attr;
        return;
    }

    public function callBack($callback)
    {
        $this->callBack = $callback;
        return;
    }

    public function fieldCallBack($fields)
    {
        $this->filedCallBack = $fields;
        return;
    }

    public function divAppend($div)
    {
        $this->divAppend = $div;
        return;
    }

    public function title($title)
    {
        $this->title = $title;
        return;
    }

    public function setTemplate($template)
    {
        if(!is_array($template)) return FALSE;
        $this->template = $template;
    }

    public function setHeadings()
    {
        $args = func_get_args();
        $this->heading = (is_array($args[0])) ? $args[0] : $args;
    }

    public function makeColumns($array = array(), $colLimit = 0)
    {
        if(!is_array($array) OR count($array) == 0 ) return FALSE;
        $this->autoHeading = FALSE;
        if($colLimit == 0) return $array;
        $new = $array();
        while(count($array) > 0)
        {
            $temp = array_splice($array, 0, $colLimit);
            if(count($temp) < $colLimit)
            {
                for($i = count($temp); $i < $colLimit; $i++)
                {
                    $temp[] = '$nbsp;';
                }
            }
            $new = $temp;
        }
        return $new;
    }

    public function setEmpty($val)
    {
        $this->emptyCell = $val;
    }

    public function addRow()
    {
        $args = func_get_args();
        $this->rows[] = (is_array($args[0])) ? $args[0] : $args;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;
        return;
    }

    public function generate($tableData = NULL, Request $request)
    {
        if(!is_null($tableData))
        {
            if(is_object($tableData))
            {
                $this->setFromObject($tableData);
            }
            else if(is_array($tableData))
            {
                $setHeading = (count($this->heading) == 0 AND $this->autoHeading == FALSE) ? FALSE : TRUE;
                $this->setFromArray($tableData, $setHeading);
                //exit("Set From Array");
            }
            else if($tableData != "")
            {
                if(env('DB_CONNECTION') == 'sqlsrv' && !is_array($this->columns)) return 'Missing require params (columns)';
                if($request->input('data-post')) $this->clear();

                $kunci = "";
                $terkunci = "";
                $tercari = "";

                if(!$request->input('post-data')) {
                    if($this->addFilter)
                    {
                        $default = strpos(strtolower($tableDate), "where");
                        if($default === FALSE)
                            $tableData .= " WHERE " . $this->fieldFilters;
                        else
                            $tableData .= " AND " . $this->fieldFilters;
                    }
                }
                else
                {
                    $this->addFilter = FALSE;
                    $this->fieldFilters = "";
                }
                if(!$request->input('inline'))
                {
                    #For single search with select box as key search
                    $arrkunci = explode("|", $request->input('opt_search'));
                    if($request->input('range') && $request->input('block'))
                    {
                        #Range Date picker with single searching
                        if(is_array($request->input('range')))
                        {
                            $arrbetween = $request->input('range');
                            $range = "BETWEEN '" . $arrbetween[0] . "' AND '" . $arrbetween[1] . "'";
                            $arrcari = explode("|", $range);
                        }
                    }
                    else
                    {
                        $arrcari = explode("|", $request->input('key_search'));
                    }
                }
                else
                {
                    $arrkunci = array_keys($request->input('opt_search'));
                    $arrcari = $request->input('opt_search');
                }

                $and = "";

                foreach($arrkunci as $z => $kunci)
                {
                    if(array_key_exists($kunci, $this->keycari))
                    {
                        $terkunci = $this->keycari[$kunci];
                        $terkunci = $terkunci[0];
                        $cari = $arrcari[$z];
                        if(is_array($cari))
                        {
                            if($cari[0] != "" && $cari != "")
                            {
                                if(count(array_unique($cari)) > 1)
                                {
                                    $tercari .= " $and $terkunci BETWEEN '" . $cari[0] . "' AND '" . $cari[1] . "'";
                                    $and = " AND ";
                                }
                            }
                        }
                        else
                        {
                            if($cari != "")
                            {
                                $cari = str_replace("'","''", $cari);
                                if(substr($terkunci, 0, 4) == "{IN}")
                                {
                                    $terkunci = substr($terkunci, 4);
                                    $tercari .= " $and " . str_replace("{LIKE}", "LIKE '%$cari%'", $terkunci);
                                }
                                else
                                {
                                    $between = strpos(strtolower($cari), "between");
                                    if($between === FALSE)
                                        $tercari .= " $and $terkunci LIKE '%$cari%'";
                                    else
                                        $tercari .= " $terkunci " . str_replace("''", "'", $cari);
                                }
                                $and = " AND ";
                            }
                        }
                    }
                }

                if($this->baris != "ALL")
                {
                    if($request->input('tb_view') < 1) 
                        $this->baris = 10;
                    else 
                        $this->baris = $request->input('tb_view');
                }

                if($tercari != "")
                {
                    if($this->useWhere)
                    {
                        $tableData .= " WHERE $tercari";
                    }
                    else
                    {
                        $exists = strpos(strtolower($tableData), "where");
                        if($exists === FALSE)
                            $tableData .= " WHERE $tercari";
                        else 
                            $tableData .= " AND $tercari";
                    }
                }
                #Group By
                if(count($this->groupBy) > 0)
                {
                    $sKomaGroup = "";
                    $sColumnsGroup = "";
                    foreach($this->groupBy as $z)
                    {
                        $sColumnsGroup .= $sKomaGroup;
                        $sKomaGroup = ",";
                    }
                    $tableData = $tableData . " GROUP BY " . $sColumnsGroup;
                }
                #Num rows of records
                $iTotalRecord = 0;
                $iTotalCount = 0;
                $objTableCount = DB::select("SELECT COUNT(*) AS JML FROM ($tableData) AS TBL");
                if(sizeof($objTableCount) > 0)
                {
                    foreach($objTableCount as $a)
                    {
                        $iTotalCount = $a->JML;
                    }
                    $iTotalRecord = $iTotalCount;
                }
                else
                {
                    $iTotalRecord = 0;
                }
                #Sort By
                if($request->input('orderby'))
                {
                    $this->orderBy = (is_numeric($request->input('orderby')) ? (int)$request->input('orderby') : $request->input('orderby'));
                    $this->sortBy = $request->input('sortby');
                    if(env('DB_CONNECTION') == 'sqlsrv')
                    {
                        if(is_numeric($this->orderBy))
                        {
                            $orderby = $this->columns[$this->orderBy-1];
                            if(is_array($orderby)) $orderby = $orderby[0];
                        }
                        else
                        {
                            $orderby = $request->input('orderby');
                        }
                    }
                    else
                    {
                        $orderby = $this->orderBy;
                    }
                }
                else
                {
                    if(is_numeric($this->orderBy))
                    {
                        if(env('DB_CONNECTION') == 'sqlsrv')
                        {
                            $orderby = $this->columns[$this->orderBy-1];
                            if(is_array($orderby)) $orderby = $orderby[0];
                        }
                        else
                        {
                            $orderby = $this->orderBy;
                        }
                    }
                    else
                    {
                        $orderby = $this->orderBy;
                    }
                }
                #Rows ALL || AUTO
                if($this->baris != "ALL")
                {
                    $iTotalCount = ceil($iTotalCount / $this->baris);
                    if($this->hal == "AUTO") $this->hal = $request->input('tb_hal');
                    if($this->hal < 1) $this->hal = 1;
                    if($this->hal > $iTotalCount) $this->hal = $iTotalCount;
                    if($this->hal == 1)
                    {
                        if(env('DB_CONNECTION') == 'sqlsrv')
                        {
                            $iFrom = $this->hal;
                            $iTo = $this->baris;
                        }
                        else
                        {
                            $iFrom = 0;
                            $iTo = $this->baris;
                        }
                    }
                    else
                    {
                        if(env('DB_CONNECTION') == 'sqlsrv')
                        {
                            $iFrom = ($this->hal * $this->baris) - $this->baris + 1;
                            $iTo = $this->hal * $this->baris;
                        }
                        else
                        {
                            $iFrom = $this->hal > 0 ? ($this->hal-1) * $this->baris : 0;
                            $iTo = $this->baris;
                        }
                    }
                    if(env('DB_CONNECTION') == 'sqlsrv')
                        $tableData = "SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY $orderby $this->sortBy) AS HAL, ".substr($tableData, 6)." ) AS TBLTMP WHERE HAL >= $iFrom AND HAL <= $iTo";
                    else
                        $tableData = "$tableData ORDER BY $orderby $this->sortBy LIMIT $iFrom, $iTo";
                }
                else
                {
                    $tableData = $tableData . " ORDER BY $orderby $this->sortBy";
                }
                $tableData = DB::select(trim(preg_replace('/\s+/',' ', $tableData)));
                $this->setFromObject($tableData);
            }
        }
        #End Table Data is not null
        if (count($this->heading) == 0 AND count($this->rows) == 0)
		{
			return '<i>Undefined Table Data</i>';
        }
        
        #Compile template
        $this->compileTemplate();
        
        #Start print table component
        $out  = '<div class="section">';
        $out .= '   <form id="'. $this->tbTarget.'" action="'. $this->action.'" autocomplete="off">';

        #Condition for type searching, single keyword or multiple keywords searching
        if($this->single)
        $out .= '   <input type="hidden" id="searchtipe" value="1" '. ($this->postMethod ? 'name="block"' : '') . '>';
        else
        $out .= '   <input type="hidden" id="searchtipe" value="N" '. ($this->postMethod ? 'name="inline"' : '') . '>';

        $arrSubHome = array();
        if(count($this->proses) > 0)
        {
            foreach($this->proses as $proA => $proB)
            {
                if(count($proB) > 3) {
                    if($proB[3] == 'home' && $proB[0] == 'GET' && $proB[2] == '0') $arrSubHome[$proA] = $proB;
                }
            }
        }

        $out .= '   <div class="row bottom_action" id="bottom_action_'. $this->tbTarget .'">
                        <div class="col s12 m12 l12">
                            <div class="action-left">
                                <a href="javascript:void(0)" onclick="_hideBottom($(this));" id="'.rand().'" class="btn-floating waves-effect waves-light green darken-4" title="Back or Close" data-body = ".'.$this->tbTarget.'" data-bar="#bottom_action_'.$this->tbTarget.'"><i class="mdi-navigation-arrow-back"></i></a> &nbsp;';
                                if(count($this->proses) > 0 && $this->showChk)
                                {
                                    foreach($this->proses as $a => $b)
                                    {
                                        $out .= '<a href="javascript:void(0);" title="'.$a.'" class="tbs_menu btn-floating waves-effect waves-light '.$b[4].'" met="'.$b[0].'" jml="'.$b[2].'" url="'.$b[1].'"'.(strlen($b[5]) > 0 ? 'isngajax = "true" data-form = "#'.$this->tbTarget.'"' : ""). (strlen(trim($b[6])) > 0 ? 'data-body = "'.$b[5].'"' : ''). ' ><i class="'.$b[3].'"></i></a> &nbsp;';
                                    }
                                }
        $out .= '           <div class="selected_items"><span></span></div>
                            </div>
                        </div>
                    </div>';
        
        $out .= '   <div>';

        if(!$request->input('data-post'))
        {
        $out .=	'   <div class="col s12 m12 l12 navsearch_'.$this->tbTarget.'">
                        <nav class="blue lighten-1">
                            <div class="nav-wrapper">
                                <div class="left col s12 m5 l5">
                                    <ul>
                                        <li><i class="mdi-action-view-list"></i></li>
                                        <li>&nbsp;'.$this->title.'</li>
                                    </ul>
                                </div>
                                <div class="col s12 m7 l7 hide-on-med-and-down">
                                    <ul class="right">';
                            if($this->showSearch){
        $out .=	'                       <li><a href="javascript:void(0);" onClick="$(\'#sSearch_'.$this->tbTarget.'\').toggle(\'slow\')"><i class="mdi-action-find-in-page small icon-demo"></i></a></li>';
                            }
        $out .=	'                   </ul>
                                </div>
                            </div>
                        </nav>
                    </div>';
        }

        /**
         * Start search, if we define showSearch(TRUE)
         */
        if(!$request->input('data-post')){
			if ($this->showSearch){
				$lblcategory = "Filter Berdasarkan";
				$seltitle = "Pilih Kategori";
				$contains = "Dengan kata kunci";
				$titlecontains = "Ketik Kata Kunci &amp; Tekan Enter Untuk Mencari";
				$lbldropdownbtn = "Pilih Proses";
				$arrkey = $this->keycari;
				$out .= '<div class="col s12" id="sSearch_'.$this->tbTarget.'">';
				/**
				 * Single Searching
				 */
				if($this->single){
					$out .=					'<div class="row" style="margin-left:10px; margin-top:20px; margin-right:10px; margin-bottom:10px;">';
					$out .= 					'<div class="col s12">';
					$out .=						'<div class="row">';
					$out .= 					'<label class="col s3">'.$lblcategory.'</label>';
                    $out .= 					'<div class="col s4" id="'.rand().'">';
                    $out .= 						'<select class="select-dropdown" id="tb_keycari" title="'.$seltitle.'"'.($this->postMethod ? 'name="opt_search" data-postmethod = "'.$this->postMethod.'" data-form = "#'.$this->tbTarget.'" data-action = "'.$this->action.'" data-target =".'.$this->tbTarget.'" data-post = "TRUE"' : '').' >';
														foreach ($this->keycari as $a => $b){
															$out .= '<option value="';
															$out .= $a;
															$out .= '"';
															if (count($b)>2){
																if ($b[2][0]=="STRING"){
																	$out .= ' cb="'.implode(";", $b[2][1]).'"';
																}else if ($b[2][0]=="ARRAY"){
																	$out .= ' cb="'.implode(";", array_keys($b[2][1])).'" urcb="'.implode(";", array_values($b[2][1])).'"';
																}else if ($b[2][0] == "DATEPICKER"){
																	$out .= ' picker = "'. $b[2][1][0] . '" data-picker ="'.$b[2][1][1].'" data-format ="'.$b[2][1][2].'"';
																}else if($b[2][0] == "DATERANGE"){
																	$out .= ' range = "'. $b[2][1][0] . '" data-picker = "'.$b[2][1][1].'" data-format = "'.$b[2][1][2].'"';
																}
															}
															$out .= '>';
															$out .= $b[1];
															$out .= '</option>';
														}
					$out .= 							'</select>';
					$out .=						'</div>';
					
					$out .=						'<div class="col s5" id="'.rand().'">';
					$out .=							'<div class="input-field col s12" style="margin-top:0px;"><input class="form-control input-sm key_search" type="text" id="key_search" placeholder="'.$contains.'" name="key_search"><label for="key_search" class=""></label><i class="mdi-action-pageview prefix"vdata-form = "#'.$this->tbTarget.'" data-action = "'.$this->action.'" data-target =".'.$this->tbTarget.'" data-post = "TRUE" style="cursor:pointer;" id="btn-search-'.rand().'" onclick="postobj($(this));"></i></div>';
					$out .=						'</div>';

					$out .= 					'</div>';
					$out .=						'</div>';
					$out .=					'</div>';
				}
				/** 
				 * End Single Searching 
				*/
				
				/**
				 * Multiple Searching
				 */
				else
				{
					$out .= 			    '<div class="row" style="margin-left:10px; margin-top:20px; margin-right:10px; margin-bottom:10px;">';
						foreach($this->keycari as $a => $b){
					$out .=						'<div class="col s6">
														<label id="lbl_'.str_replace(".", "_", $b[0]).'" style="margin-bottom:0px;">'.$b[1].'</label>';
														if (count($b)>2){
															if ($b[2][0]=="STRING"){
																$out .= ' combobox string';
															}else if ($b[2][0]=="ARRAY"){
																$out .= '<div class="select fg-line">';
																$out .= '<select class="form-control keywords" name="opt_search['.$a.']" id="'.rand(pow(10, $counter-1), pow(10, $counter)-1).'" '.((is_array($b[3]) && $this->postmethod) ? 'data-url = "'.$b[3][0].'"' : "").'>';
																$out .= '<option value=""></option>';
																foreach ($b[2][1] as $valopt => $selopt) {
																	$out .= '<option value= "'.$valopt.'">'.$selopt.'</option>';
																}
																$out .= '</select>';
																$out .= '</div>';
															}else if ($b[2][0] == "DATEPICKER"){
																$out .= '<input type="text" class="form-control date-pickers keywords" data-date-format="'.$b[2][1][2].'" placeholder="'.$b[2][1][2].'" name="opt_search['.$a.']"" id="'.rand(pow(10, $counter-1), pow(10, $counter)-1).'">';
															}else if($b[2][0] == "DATERANGE"){
																$out .= '<div class="input-daterange input-group"><input class="form-control datepickers-range keywords col s6 date-start" type="text" placeholder="Dari" name="opt_search['.$a.'][]" data-date-format="'.$b[2][1][2].'" id="'.rand(pow(10, $counter-1), pow(10, $counter)-1).'"><span class="input-group-addon"><i class="fa fa-chevron-right"></i></span><input class="form-control datepickers-range keywords col s6 date-end" type="text" placeholder="Sampai" name="opt_search['.$a.'][]" data-date-format="'.$b[2][1][2].'" id="'.rand(pow(10, $counter-1), pow(10, $counter)-1).'">
						  </div>';
															}
														}else{
															$out .= '<input type="text" class="form-control keywords" id="'.$b[0].'" name="opt_search['.$a.']" id="'.rand(pow(10, $counter-1), pow(10, $counter)-1).'">';
														}
					$out .=								'</div>';			
						}
					$out .= 				'</div>';
					
					$out .= '<div class="row" style="margin-left:10px; margin-top:20px; margin-right:10px; margin-bottom:10px;">
								<div class="btn-demo">
									<button class="btn waves-effect waves-light blue lighten-1" data-form = "#'.$this->tbTarget.'" data-action = "'.$this->action.'" data-target =".'.$this->tbTarget.'" data-post = "TRUE" style="cursor:pointer;" id="btn-search-'.rand().'" onclick="postobj($(this)); return false;">Cari <i class="mdi-content-send right"></i></button>
								</div>
							 </div>';
				}
				/**
				 * End Multiple Searching
				 */
				$out .= '</div>';
			}				 
		}
        /**
        * End search
        */
        /**
        * Start parsing record into table html
        */
        $out .= '   <div class="col s12 '.$this->tbTarget.'">';
        $out .= '   <div style="height:10px;">&nbsp;</div>';
        $out .=     $this->template['table_open'];
        $out .= '   <thead>';
        if(count($this->heading) > 0)
        {
            if ($this->baris=='ALL') $out .= '<tr class="head">';
            else $out .= '<tr>';
            foreach($this->heading as $z => $heading)
			{
                if(env('DB_CONNECTION') =='sqlsrv')
				{
					if(!$this->showChk) $z--;
				}
				else
				{
					if(!$this->showChk) $z++;
					
                }

                if(!in_array($heading, $this->hiderows))
				{
                    if($this->expandRow){
                        $z--;
                    }
                    if($this->expandRow && $z == 1)
                    {
                        $out .= '<th width="8">x</th>';
                    }
                    
                    if(array_key_exists($heading, $this->width) ) $out .= '<th width="'.$this->width[$heading].'">';
                    else $out .= "<th>";
                    if ( $this->baris != "ALL")
                    {
                        if($z == $this->orderBy)
                        {
                            if ($this->sortBy == "ASC")
                            {
                                $out .= "<span title=\"Sort By ".$heading." (Z-A)\" orderby=\"$z\" sortby=\"DESC\"".($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"orderby\" onclick=\"order($(this));\"" : "").">$heading</span>";
                            }
                            else
                            {
                                $out .= "<span title=\"Sort By ".$heading." (Z-A)\" orderby=\"$z\" sortby=\"ASC\"".($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"orderby\" onclick=\"order($(this));\"" : "")."\>$heading</span>";
                            }
                        }
                        else
                        {
                            if($z > 0)
                            $out .= "<span title=\"Sort By ".$heading." (Z-A)\" orderby=\"$z\" sortby=\"ASC\"".($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"orderby\" onclick=\"order($(this));\"" : "").">$heading</span>";
                            else
                            $out .= $heading;
                        }
                    }
                    else
                    {
                        $out .= "<span>$heading</span>";
                    }
                    $out .= '</th>';
                }
            }
        }
        $out .= '   </thead>';
        
        if (count($this->rows) > 0)
		{
			$out .= '<tbody '.($this->postmethod ? 'id="body-'.$this->tbtarget.'"': '').'>';
            $i = 1;
			foreach($this->rows as $row)
			{
                if (! is_array($row))
                {
                    break;
                }
                
                $keyz = "";
                $koma = "";
    
                foreach ($this->keys as $a)
                {
                    $keyz .= $koma.$row[$a];
                    $koma = ".";
                }
    
                $name = (fmod($i++, 2)) ? '' : 'alt_';
                if($i%2==0) $cls = 'alt-row';
                else $cls = "main-row";
                if ($this->details=="")
                {
                    $out .= '<tr class="'.$cls.'" urldetil="" id="'.$this->tbTarget.'-'.rand().'">';
                }
                else
                {
                    if ($this->showChk)
                        $out .= '<tr class="'.$cls.'" urldetil="/'.$keyz.'" id="'.$this->tbTarget.'-'.rand().'">';
                    else
                        $out .= '<tr class="'.$cls.'" urldetil="/'.$keyz.'" id="'.$this->tbTarget.'-'.rand().'">';
                }
                $out .= $this->newLine;
    
                if( $this->showChk )
                {
                    $sId_Td = 'td_'.$this->tbTarget.'_'.rand();
                    $out .= '<td><div class="center-align"><input type="checkbox" name="tb_chk[]" data-bar = "#bottom_action_'.$this->tbTarget.'" class="tb_chk" id ="'.$sId_Td.'" value="'.$keyz.'" onchange="_chk($(this));"><label for="'.$sId_Td.'"></label></div></td>';
                }
    
                if ($this->expandRow) $out .= '<td width="8"><a href="javascript:void(0);" id="expand'.$keyz.'" onclick="expand($(this)); return false;" title="Expand baris"><i class="zmdi zmdi-format-valign-center zmdi-hc-fw"></i></a></td>';
                $seq = -1;
                foreach($row as $rowz => $cell)
                {
                    if (!in_array($rowz, $this->hiderows))
                    {
                        if ($this->baris=='ALL' || !$this->showChk) $out .= '<td class="pad12">';
                        else $out .= "<td>";
                        if ($cell === "")
                        {
                            $out .= $this->emptyCell;
                        }
                        else
                        {
                            $cell = str_replace(chr(10), '<br>', $cell);
                            $url_col = $this->columns[$seq];
                            if ( is_array($url_col) )
                            {
                                $new_url_col = $url_col[1];
                                $url_col = explode("{", $new_url_col); 
                                foreach($url_col as $x)
                                {
                                    $temp_url_col = explode("}", $x); 
                                    $temp_url_col = $temp_url_col[0]; 
                                    $new_url_col = str_replace("{".$temp_url_col."}", $row[$temp_url_col], $new_url_col); 
                                }
                                if(in_array('modal', $this->columns[$seq]))
                                {
                                    $out .= '<a href="javascript:void(0);" data-url = "'.$new_url_col.'" class="modal-link" onclick="modalrow($(this));">'.$cell.'</a>'; #Extend
                                }
                                else if(in_array('replace', $this->columns[$seq]))
                                {
                                    $out .= '<a href="javascript:void(0);" data-url="'.$new_url_col.'" class="replaced" id = "'.rand().'" onclick="fullscdiv($(this));" data-target="#'.$this->columns[$seq][3].'"><small><i class="fa fa-unsorted"></i></small> '.$cell.'</a>';
                                }
                                else if(in_array('append', $this->columns[$seq]))
                                {
                                    $out .= '<a href="javascript:void(0);" data-url = "'.$new_url_col.'" class="append-link" onclick="appendrow($(this));">'.$cell.'</a>'; #End Extend
                                }
                                else{
                                    $out .= '<a href="'.$new_url_col.'">'.$cell.'</a>'; #Default
                                }
                            }
                            else
                            {
                                $out .= $cell;
                            }
                        }
                        $out .= $this->template['cell_'.$name.'end'];
                    }
                    $seq++;
                }
    
                if($this->setTrId)
                {
                    $attributeRow = $this->attrId;
                    $arrAttribute = explode("{", $attributeRow);
                    foreach($arrAtribute as $attrx)
                    {
                        $tempAttr = explode("}", $attrx);
                        $tempAttr = $tempAttr[0];
                        $attributeRow = str_replace("{".$tempAttr."}", $row[$tempAttr], $attributeRow);
                    }
                    if($this->callBack != "")
                    {
                        $urlCallback = $this->callBack;
                        $tmpCallback = explode("{", $urlCallback);
                        foreach($tmpCallback as $urlCall){
                            $tmpCallbackx = explode("}", $urlCall);
                            $tmpCallbackx = $tmpCallbackx[0];
                            $urlCallback = str_replace("{".$tmpCallbackx."}", $row[$tmpCallbackx], $urlCallback);
                        }
                    }
                    $out .= '<td><a href="javascript:void(0);" id="'.$this->tbTarget.'_'.$i.'" class="tdselect" data-target="'.str_replace('{','',str_replace('}','',$this->attrId)).'" data-retrive = "'.$attributeRow.'" '.($this->callBack!= "" ? "data-url-callback=\"".$urlCallback."\"" : "").' '.($this->callBack!= "" ? "data-field-callback=\"".$this->fieldCallBack."\"" : "").' onclick="selectedrow($(this));"><i class="fa fa-check-square"></i></a></td>';
                }
            }
            $out .= '</tbody>';
        }
        else
        {
            $out .= '<tr><td colspan="'.count($this->heading).'"><center><span class="label label-danger">Record Not Found</span></center></td></tr>';
        }

        $out .=     $this->template['table_close'];
        $out .= '   <input type="hidden" name="tb_hal" value="'.$this->hal.'" /><input type="hidden" name="tb_view" value="'.$this->baris.'" /><input type="hidden" name="orderby" value="'.$this->orderBy.'"><input type="hidden" name="sortby" value="'.$this->sortBy.'">';
		$out .= '<input type="hidden" name="tblang" value="'.$this->lang.'">';
        if ($this->details!="") $out .= '<input type="hidden" id="urldtl" value="'.$this->details.'">';

        $out .= '   </div>';
        /**
        * End parsing record
        */

        /**
         * Block Pagination
         */
        if (count($this->rows) > 0)
        {
            $out .= '<div class="row">';
            $out .= '   <div class="col s12">';
            $out .= '       <div class="row">';
            if($this->baris != "ALL")
            {
                $datast = ($this->hal - 1);
                if ( $datast<1 ) $datast = 1;
                else $datast = $datast * $this->baris + 1;
                $dataen = $datast + $this->baris - 1;
                if($iTotalRecord < $dataen) $dataen = $iTotalRecord;
                if($iTotalRecord==0) $datast = 0;
                /**
                 * Navigasi Pagination left
                 */
                $out .= '       <div class="col s6 m6">';
                $out .= '           <ul class="nav-kiri pagination" style="margin-left:10px;">';
                                        if($iTotalRecord>=10)
                                        {
                                            if($this->baris==10)
                                                $out .= '<li class="active"><a href="javascript:void(0);" class="per" title="Tampilkan 10 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per10\" onclick=\"view($(this));\"" : "").'>10</a></li>';
                                            else
                                                $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="per" title="Tampilkan 10 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per10\" onclick=\"view($(this));\"" : "").'>10</a></li>';
                                        }
                                        if($iTotalRecord>=20)
                                        {
                                            if($this->baris==20)
                                                $out .= '<li class="active"><a href="javascript:void(0);" class="per" title="Tampilkan 20 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per20\" onclick=\"view($(this));\"" : "").'>20</a></li>';
                                            else
                                                $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="per" title="Tampilkan 20 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per20\" onclick=\"view($(this));\"" : "").'>20</a></li>';
                                        }
                                        if($iTotalRecord>=50)
                                        {
                                            if($this->baris==50)
                                                $out .= '<li class="active"><a href="javascript:void(0);" class="per" title="Tampilkan 50 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per50\" onclick=\"view($(this));\"" : "").'>50</a></li>';
                                            else
                                                $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="per" title="Tampilkan 50 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per50\" onclick=\"view($(this));\"" : "").'>50</a></li>';
                                        }
                                        if($iTotalRecord>=100)
                                        {
                                            if($this->baris==100)
                                                $out .= '<li class="active"><a href="javascript:void(0);" class="per" title="Tampilkan 100 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per100\" onclick=\"view($(this));\"" : "").'>100</a></li>';
                                            else
                                                $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="per" title="Tampilkan 100 Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per100\" onclick=\"view($(this));\"" : "").'>100</a></li>';
                                        }
                                    if($iTotalRecord!=10 || $iTotalRecord!=20 || $iTotalRecord!=50 || $iTotalRecord!=100)
                                    {
                                        if($iTotalRecord<=100)
                                        {
                                            if($this->baris==$iTotalRecord)
                                            {
                                                $out .= '<li class="active"><a href="javascript:void(0);" class="current per" title="Tampilkan '.$iTotalRecord.' Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per".$iTotalRecord."\" onclick=\"view($(this));\"" : "").'>'.$iTotalRecord.'</a></li>';
                                            }
                                            else
                                            {
                                                $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="per" title="Tampilkan '.$iTotalRecord.' Data Per Halaman" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->actions."\" data-target = \".".$this->tbTarget."\" id =\"per".$iTotalRecord."\" onclick=\"view($(this));\"" : "").'>'.$iTotalRecord.'</a></li>';
                                            }
                                        }
                                        else
                                        {
                                            $out .= '<li class="disabled"><a href="javascript:void(0);" class="disabled" title="Total Data">'.$iTotalRecord.'</a></li>';
                                        }
                                    }
                $out .= '           </ul>';
                $out .= '       </div>';
                              /**
                 * End Navigasi Pagination left
                 */
                /**
                 * Navigasi Pagination Right
                 */
                $out .= '       <div class="col s6 m6">';
                $out .= '           <ul class="nav-kanan pagination" style="margin-right:10px;">';
                                    if($this->hal==1)
                                    $out .= '<li class="active"><a href="javascript:void(0);" class="active page" title="Ke Halaman 1" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"page-".$this->hal."\" onclick=\"nextprevpage($(this));\"" : "").'>1</a></li>';
                                    else
                                    $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="page" title="Ke Halaman 1" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"page-".$this->hal."\" onclick=\"nextprevpage($(this));\"" : "").'>1</a></li>';
                                        
                                    if($this->hal>=6)
                                    {
                                        $out .= '<li class="waves-effect"><a href="#">&hellip; </a></li>';
                                        $minnav = $this->hal-2;
                                        $maxnav = $this->hal+2;
                                    }
                                    else
                                    {
                                        $minnav = 0;
                                        $maxnav = 0;
                                    }
                                    $countnav = 1;
                                    for($halnav=2;$halnav<$iTotalCount;$halnav++){
                                        if(($minnav==0 && $maxnav==0) || ($halnav>=$minnav && $halnav<=$maxnav))
                                        {
                                            if($this->hal==$halnav)
                                                $out .= '<li class="active"><a href="javascript:void(0);" class="active page" title="Ke Halaman '.$halnav.'" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"page-".$halnav."\" onclick=\"nextprevpage($(this));\"" : "").'>'.$halnav.'</a></li>';
                                            else
                                                $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="page" title="Ke Halaman '.$halnav.'" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"page-".$halnav."\" onclick=\"nextprevpage($(this));\"" : "").'>'.$halnav.'</a></li>';
                                            $countnav++;
                                        }
                                        if($countnav==6) break;
                                    }
                                    if($iTotalCount>7) $out .= '<li class="waves-effect"><a href="#">&hellip; </a></li>';
                                    if($iTotalCount>1)
                                    {
                                        if($this->hal==$iTotalCount)
                                            $out .= '<li class="active"><a href="javascript:void(0);" class="active page" title="Ke Halaman'.$iTotalCount.'" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"page-".$iTotalCount."\" onclick=\"nextprevpage($(this));\"" : "").'>'.$iTotalCount.'</a></li>';
                                        else
                                            $out .= '<li class="waves-effect"><a href="javascript:void(0);" class="page" title="Ke Halaman '.$iTotalCount.'" '.($this->postMethod || $request->input('data-post') ? " data-post = \"TRUE\" data-form = \"#".$this->tbTarget."\" data-action = \"".$this->action."\" data-target = \".".$this->tbTarget."\" id =\"page-".$iTotalCount."\" onclick=\"nextprevpage($(this));\"" : "").'>'.$iTotalCount.'</a></li>';
                                    }
                $out .= '           </ul>';
                $out .= '       </div>';
                /**
                 * End navigation pagination right
                 */
            }
            $out .= '       </div>';
            $out .= '   </div>';
            $out .= '</div>';
        }

        /**
         * End Pagination
         */

        $out .= '   </div>';
        #End Form
        $out .= '   </form>';
        $out .= '</div>';
        #End print table component

        if($this->divAppend)
        {
			$out .= '<div class="row" id="newdiv"></div>';
        }
        
        if (count($this->proses) > 0 && $this->showChk){
			$out .= '<div class="fixed-action-btn">
						<a class="btn-floating btn-large red accent-4"><i class="mdi-action-assignment"></i></a>';
				$out .= '<ul>';
				if(count($arrsubhome)>0){
					foreach ($arrsubhome as $a => $b){
						$out .= '<li><a href="javascript:void(0);" id = "'.rand().'new" act="'.$b[1].'" value="'.$a.'" '.($b[4]=="append" ? "data-append=\"true\" data-div=\"#newdiv\" data-class = \".formsearch"   : "").' '.($b[4]=="modal" ? "data-modal=\"true" : "").' class="btn-floating green btnsubmenu" style="transform: scaleY(0.4) scaleX(0.4) translateY(40px) translateX(0px); opacity: 0;"><i class="mdi-content-create"></i></a></li>';
					}
				}			
				$out .= '</ul>';	
			$out .= '</div>';
        }
        
        return $out;
    }

    public function clear()   
    {
        $this->heading      = array();
        $this->rows         = array();
        $this->autoHeading  = TRUE;
    }

    public function setFromObject($query)
    {
        if(count($this->heading) == 0)
        {
            empty($this->heading);
            if($this->showChk) $this->heading[] = '<input type="checkbox" class="filled-in"'.($this->postMethod ? 'class="chkall'.$this->tbTarget.'" onchange="_chkall($(this));" id ="chkall'.$this->tbTarget.'" data-bar = "#bottom_action_'.$this->tbTarget.'" data-body = "#body-'.$this->tbTarget.'" ': 'id="tb_chkall').'><label for="'.($this->postMethod ? 'chkall'.$this->tbTarget : 'tb_chkall').'"></label>';
            if($this->expandRow) $this->heading[] = '&nbsp';
            if($this->list_fields($query) != FALSE)
            {
                foreach($this->list_fields($query) as $a)
                {
                    $this->heading[] = $a;
                }
            }
            if($this->setTrId) $this->heading[] = '&nbsp;';
        }
        if(sizeof($query) > 0)
        {
            foreach($query as $row)
            {
                $this->rows[] = (array)$row;
            }
        }
    }

    public function list_fields($dbObjects)
    {
        if(sizeof($dbObjects) > 0)
            return array_keys((array)$dbObjects[0]);
        else
            return FALSE;
    }

    public function setFromArray($data, $setHeading = TRUE)
    {
        if(!is_array($data) OR count($data) == 0)
        {
            return FALSE;
        }
        $i = 0;
        foreach($data as $row) 
        {
            if(!is_array($row))
            {
                $this->rows[] = $row;
                break;
            }
            if($i == 0 AND count($data) > 1 AND count($this->heading) == 0 AND $setHeading = TRUE)
            {
                $this->heading[] = $row;
            }
            else
            {
                $this->rows[] = $row;
            }
            $i++;
        }
    }

    public function compileTemplate()
    {
        if($this->template == NULL)
        {
            $this->template = $this->defaultTemplate();
            return;
        }
        $this->temp = $this->defaultTemplate();
        foreach (array('table_open','heading_row_start', 'heading_row_end', 'heading_cell_start', 'heading_cell_end', 'row_start', 'row_end', 'cell_start', 'cell_end', 'row_alt_start', 'row_alt_end', 'cell_alt_start', 'cell_alt_end', 'table_close') as $val)
		{
			if ( ! isset($this->template[$val]))
			{
				$this->template[$val] = $this->temp[$val];
			}
		}
    }

    public function defaultTemplate()
    {
        return [
            'table_open'                => '<table class="tableajax table striped responsive-table">',
            'heading_row_start'         => '<tr>',
            'heading_row_end'           => '</tr>',
            'heading_cell_start'        => '<th>',
            'heading_cell_end'          => '</th>',
            'row_start'                 => '<tr>',
            'row_end'                   => '</tr>',
            'cell_start'                => '<td>',
            'cell_end'                  => '</td>',
            'row_alt_start'             => '<tr>',
            'row_alt_end'               => '</tr>',
            'cell_alt_start'            => '<td>',
            'cell_alt_end'              => '</td>',
            'table_close'               =>  '</table>'
        ];
    }

}