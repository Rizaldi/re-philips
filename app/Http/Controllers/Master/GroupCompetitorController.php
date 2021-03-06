<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;
use App\Filters\GroupCompetitorFilters;
use App\Traits\StringTrait;
use DB;
use App\GroupCompetitor;

class GroupCompetitorController extends Controller
{
    use StringTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.groupcompetitor');
    }

    /**
     * Data for DataTables
     *
     * @return \Illuminate\Http\Response
     */
    public function masterDataTable(){

        $data = GroupCompetitor::where('group_competitors.deleted_at', null)
        			->join('group_products', 'group_competitors.groupproduct_id', '=', 'group_products.id')
                    ->select('group_competitors.*', 'group_products.name as groupproduct_name')->get();

        return $this->makeTable($data);
    }

    // Data for select2 with Filters
    public function getDataWithFilters(GroupCompetitorFilters $filters){        
        $data = GroupCompetitor::filter($filters)->get();

        return $data;
    }

    // Datatable template
    public function makeTable($data){

           return Datatables::of($data)
           		->addColumn('action', function ($item) {

                   return 
                    "<a href='#groupcompetitor' data-id='".$item->id."' data-toggle='modal' class='btn btn-sm btn-warning edit-groupcompetitor'><i class='fa fa-pencil'></i></a>
                    <button class='btn btn-danger btn-sm btn-delete deleteButton' data-toggle='confirmation' data-singleton='true' value='".$item->id."'><i class='fa fa-remove'></i></button>";
                    
                })
                ->rawColumns(['action'])
                ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	// return $request->all();

        $this->validate($request, [
            'name' => 'required|string|max:255',
            'kategori' => 'string|max:255',
            'groupproduct_id' => 'required'
            ]);

       	$groupcompetitor = GroupCompetitor::create($request->all());
        
        return response()->json(['url' => url('/groupcompetitor')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = GroupCompetitor::with('groupProduct')->where('id', $id)->first();

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'kategori' => 'string|max:255',
            'groupproduct_id' => 'required'
            ]);

        $groupcompetitor = GroupCompetitor::find($id)->update($request->all());

        return response()->json(
            ['url' => url('/groupcompetitor'), 'method' => $request->_method]);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $groupcompetitor = GroupCompetitor::destroy($id);

        return response()->json($id);
    }
}
