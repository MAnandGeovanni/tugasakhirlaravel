<?php

namespace App\Http\Controllers;
 
use App\Imports\EmployeeImport;
use PDF;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function index(Request $request) {
        if($request->has('search')){
            $data = Employee::where('nama','LIKE','%' .$request->search)->paginate(5);

        }else{
            $data = Employee::paginate(5);
        } 
        return view('datapegawai',compact('data'));
    }

    public function tambahpegawai(){
        return view('tambahdata');
    }

    public function insertdata(Request $request) {
       // dd($request->all());
        $data = Employee::create($request->all());
        if($request->hasFile('foto')){
            $request->file('foto')->move('fotopegawai/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }
        return redirect()->route('pegawai')->with('success', 'Data Berhasil di Tambahkan');
    }


    public function tampilkandata($id){
        $data = Employee::find($id);
        //dd($data);
        return view('tampildata', compact('data'));

    }


    public function updatedata(Request $request, $id){
        $data = Employee::find($id);
        $data->update($request->all());
        return redirect()->route('pegawai')->with('success', 'Data Berhasil di Update');
    
    }


    public function delete($id){
        $data = Employee::find($id);
        $data->delete();
        return redirect()->route('pegawai')->with('success', 'Data Berhasil di Hapus');

    }

    public function exportpdf(){
        $data = Employee::all();

        view()->share('data', $data);
        $pdf = PDF::loadview('datapegawai-pdf');
        return $pdf->download('data.pdf');
    }

    public function exportexcel(){
        return Excel::download(new EmployeeExport, "datapegawai.xlsx");
    }

    public function importexcel(Request $request){
       $data=$request->file('file');

        $namafile = $data->getClientOriginalName();
        $data->move('employeeData', $namafile);

        Excel::import(new EmployeeImport, \public_path('/EmployeeData/'.$namafile));
        return \redirect()->back();
    }
}
