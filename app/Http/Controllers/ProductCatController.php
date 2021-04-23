<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\ProductCategory;
use App\AuditTrail;
use App\User;
use Session;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Http\Requests\StoreProdcatRequest;
use App\Http\Requests\UpdateProdcatRequest;


class ProductCatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
      $this->middleware('checkrole');
    }

    public function index()
    {
        $title = "Product Category";
        $menu = DB::table('menu')
                ->orderBy('menu_order', 'asc')
                ->get();
        $data =array();
        foreach ($menu as $order) {
            $data[$order->parent_id][]=$order;
        }
        $menu = $this->menu($data);
        return view('prodcat.index',compact('title', 'menu'));
    }
    public function menu($data, $parent = 0){

      static $i = 1;
      // dd($role);
      if (isset($data[$parent])) {
          if($parent == 0) $html = '<ul class="list-unstyled components">';
          else $html = '<ul class="collapse list-unstyled" id="homeSubmenu'.$parent.'">';
          $i++; $checked = "";
          foreach ($data[$parent] as $v) {
              $menu = json_decode($_COOKIE['menu']);
              if (in_array($v->id, $menu)){
                  $child = $this->menu($data, $v->id);
                  $path = explode("/", request()->path());
                  if(empty($path[1])) $path[1] = 'home';
                  if($path[1] == $v->url) $active = 'class="active"'; else $active = '';
                  $html .= "<li ".$active.">";
                  if($v->url!== ''){
                      if($v->url== 'home') $url = url('/');
                      else $url = route($v->url.'.index');
                      $html .= '<a href="'.$url.'">'.$v->title.'</a>';
                  }else{
                      $html .= '<a href="#homeSubmenu'.$v->id.'" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">'.$v->title.'</a>';
                  }
                  if ($child) {
                      $i--;
                      $html .= $child;
                  }
                  $html .= '</li>';
              }
          }
          $html .= "</ul>";
          return $html;
      } else {
          return false;
      }
    }

    public function getProdcat(Request $request)
    {
        if (!empty($request->status) && isset($request->status)) {
            $query = ProductCategory::where('category_active', $request->status)->get();
        }
        if (!empty($request->id) && isset($request->id)){
            $query = ProductCategory::where('id', $request->id)->get();
        }
        if (!empty($request->id) && isset($request->id) && !empty($request->status) && isset($request->status)) {
            $query = ProductCategory::where('category_active', $request->status)->where('id', $request->id)->get();
        }
        if (empty($request->status) && empty($request->id)){
            $query = ProductCategory::all();
        }

        return Datatables::of($query)
        ->setRowId('{{$id}}')
        ->editColumn('author', function($prodcat) {
                  $author = explode(" - ", $prodcat->author);
                    return $author[0] ;
        })
        ->addColumn('action', function($prodcat){
          return view('datatable._action', [
            'edit_url' => route('prodcat.edit', $prodcat->id),
            'confirm_message' => 'Sure to delete ' . $prodcat->name . '?'
          ]);
        })
        ->addColumn('chkid', function($prodcat){
          return view('datatable._checked', [
            'id' => $prodcat->id
          ]);
        })
        ->rawColumns(['link', 'action'])
        ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = " Product Category";
        $gen ="Add";
        $menu = DB::table('menu')
                ->orderBy('menu_order', 'asc')
                ->get();
        $data =array();
        foreach ($menu as $order) {
            $data[$order->parent_id][]=$order;
        }
        $menu = $this->menu($data);
        return view('prodcat.create',compact("title","gen", "menu"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProdcatRequest $request)
    {
      $author = User::find(Auth::id());
      $author = $author->name." - ".$author->email;

      //Insert
      $prodcat = new ProductCategory;
      $prodcat->category_name   = $request->category_name;
      $prodcat->category_active = $request->category_active;
      $prodcat->created_by = $author;
      $prodcat->save();

      $audittail = [
        'audit_menu'        => 'Products',
        'audit_submenu'     => 'Product Category',
        'audit_action'      => 'Add',
        'audit_desc_after'  => json_encode($prodcat->getAttributes()),
        'audit_username' => $author
      ];
      AuditTrail::create($audittail);

      Session::flash("flash_notification", [
        "level"=>"success",
        "message"=>"<strong>$prodcat->name</strong> saved successfully"
      ]);
      return redirect()->route('prodcat.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
         return ProductCategory::query()->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $title = " Product Category";
      $gen ="Edit";
      $prodcat = ProductCategory::find($id);
      $menu = DB::table('menu')
              ->orderBy('menu_order', 'asc')
              ->get();
      $data =array();
      foreach ($menu as $order) {
          $data[$order->parent_id][]=$order;
      }
      $menu = $this->menu($data);
      return view('prodcat.edit')->with(compact('prodcat',"title","gen", "menu"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProdcatRequest $request, $id)
    {
      $author = User::find(Auth::id());
      $author = $author->name." - ".$author->email;

      $prodcat = ProductCategory::find($id);
      $auditbefore = $prodcat->getAttributes();
      //Update
      $prodcat->category_name   = $request->category_name;
      $prodcat->category_active = $request->category_active;
      $prodcat->created_by = $author;
      $prodcat->save();

      $audittail = [
        'audit_menu'        => 'Products',
        'audit_submenu'     => 'Product Category',
        'audit_action'      => 'Edit',
        'audit_desc_before' => json_encode($auditbefore),
        'audit_desc_after'  => json_encode($prodcat->getAttributes()),
        'audit_username'  => $author
      ];
      AuditTrail::create($audittail);

      Session::flash("flash_notification", [
        "level"=>"success",
        "message"=>"$prodcat->name changed successfully"
      ]);
      return redirect()->route('prodcat.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      ProductCategory::destroy($id);
      Session::flash("flash_notification", [
      "level"=>"success",
      "message"=>"Data deleted successfully"
      ]);
      return redirect()->route('prodcat.index');
    }
    public function deleteprodcat($id)
    {

      $author = User::find(Auth::id());
      $author = $author->name." - ".$author->email;
      $product = ProductCategory::find($id);

      $audittail = [
        'audit_menu'        => 'Products',
        'audit_submenu'     => 'Product Category',
        'audit_action'      => 'Delete',
        'audit_desc_after'  => json_encode($product->getAttributes()),
        'audit_username'    => $author
      ];
      $audit = AuditTrail::create($audittail);
      ProductCategory::destroy($id);
      return "success";

    }
}
