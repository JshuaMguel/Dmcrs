<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
    public function index()
    {
        $this->checkAdminAccess();
        
        $tables = $this->getTables();
        $tableData = [];
        
        foreach ($tables as $table) {
            $tableData[$table] = [
                'count' => DB::table($table)->count(),
                'columns' => Schema::getColumnListing($table)
            ];
        }
        
        return view('admin.database.index', compact('tables', 'tableData'));
    }
    
    public function table($tableName)
    {
        $this->checkAdminAccess();
        
        $tables = $this->getTables();
        if (!in_array($tableName, $tables)) {
            abort(404, 'Table not found');
        }
        
        $columns = Schema::getColumnListing($tableName);
        $data = DB::table($tableName)->paginate(50);
        
        return view('admin.database.table', compact('tableName', 'columns', 'data'));
    }
    
    public function deleteRecord(Request $request, $tableName, $id)
    {
        $this->checkAdminAccess();
        
        $tables = $this->getTables();
        if (!in_array($tableName, $tables)) {
            abort(404, 'Table not found');
        }
        
        DB::table($tableName)->where('id', $id)->delete();
        
        return redirect()->route('admin.database.table', $tableName)
            ->with('success', "Record {$id} deleted from {$tableName}");
    }
    
    public function truncateTable(Request $request, $tableName)
    {
        $this->checkAdminAccess();
        
        $tables = $this->getTables();
        if (!in_array($tableName, $tables)) {
            abort(404, 'Table not found');
        }
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($tableName)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        return redirect()->route('admin.database.table', $tableName)
            ->with('success', "All records deleted from {$tableName}");
    }
    
    private function getTables()
    {
        return [
            'users',
            'departments', 
            'subjects',
            'sections',
            'make_up_class_requests',
            'make_up_class_confirmations',
            'approvals',
            'schedules',
            'rooms'
        ];
    }
    
    private function checkAdminAccess()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Admin access required');
        }
    }
}