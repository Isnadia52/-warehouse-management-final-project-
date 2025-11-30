<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\RestockOrder;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        $data['total_products'] = Product::count();
        $data['low_stock_count'] = Product::whereRaw('current_stock <= min_stock')->count();
        $data['pending_transactions'] = Transaction::where('status', 'Pending')->count();

        if ($user->role === 'admin') {
            $data['total_users'] = User::count();
            $data['pending_supplier'] = User::where('role', 'supplier')->where('is_approved', false)->count();
            $data['recent_transactions'] = Transaction::latest()->take(5)->get();
            return view('admin.dashboard', compact('data'));
        } 
        
        if ($user->role === 'manager') {
            $data['pending_approval'] = Transaction::where('status', 'Pending')->count();
            $data['total_restock_pending'] = RestockOrder::where('status', 'Pending')->count();
            $data['low_stock_products'] = Product::whereRaw('current_stock <= min_stock')->orderBy('current_stock')->take(5)->get();
            return view('manager.dashboard', compact('data'));
        } 
        
        if ($user->role === 'staff') {
            $data['transactions_today'] = Transaction::where('staff_id', $user->id)
                                                  ->whereDate('created_at', today())
                                                  ->count();
            $data['recent_transactions'] = Transaction::where('staff_id', $user->id)->latest()->take(5)->get();
            return view('staff.dashboard', compact('data'));
        }
        
        if ($user->role === 'supplier') {
            $data['po_pending'] = RestockOrder::where('supplier_id', $user->id)
                                            ->where('status', 'Pending')
                                            ->count();
            $data['po_in_transit'] = RestockOrder::where('supplier_id', $user->id)
                                             ->where('status', 'In Transit')
                                             ->count();
            $data['recent_po'] = RestockOrder::where('supplier_id', $user->id)->latest()->take(5)->get();
            return view('supplier.dashboard', compact('data'));
        }

        return view('dashboard');
    }
}
