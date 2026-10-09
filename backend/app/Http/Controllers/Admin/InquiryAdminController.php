<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Helpers\Common_function;
use Yajra\DataTables\Facades\DataTables;

class InquiryAdminController extends Controller
{
    protected $main_module;

    public function __construct()
    {
        $this->middleware('admin');
        $this->main_module = 'Inquiries';
    }

    public function index(Request $request)
    {
        $data = array('title' => "Inquiries", 'main_module' => $this->main_module);
        $data['tbl'] = Common_function::encrypt('inquiries');

        return view('admin.inquiries', $data);
    }

    private function getPageName(?string $type): string
    {
        $map = [
            'general' => 'Contact Us',
            'calculator' => 'Light Calculator',
            'catalogue' => 'Catalogues',
            'product' => 'Product',
        ];
        return $map[strtolower($type ?? '')] ?? ucfirst(str_replace(['_', '-'], ' ', $type ?? 'General'));
    }

    private function getPageBadge(?string $type): string
    {
        $badgeMap = [
            'general' => 'badge-info',
            'calculator' => 'badge-warning',
            'catalogue' => 'badge-primary',
            'product' => 'badge-success',
        ];
        $badgeClass = $badgeMap[strtolower($type ?? '')] ?? 'badge-secondary';
        $pageName = $this->getPageName($type);
        return '<span class="badge ' . $badgeClass . '" style="font-size: 11px; padding: 4px 8px;">' . e($pageName) . '</span>';
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = Inquiry::query()->latest();
            return Datatables::eloquent($query)
                ->addIndexColumn()
                ->setRowId(function ($row) {
                    return 'data-' . $row->id;
                })
                ->setRowClass(function ($row) {
                    return 'data';
                })
                ->addColumn('page', function ($row) {
                    return $this->getPageBadge($row->type);
                })
                ->addColumn('type', function ($row) {
                    return $this->getPageBadge($row->type);
                })
                ->addColumn('reference', function ($row) {
                    return !empty($row->reference) ? '<span class="font-weight-bold text-dark">' . e($row->reference) . '</span>' : '<span class="text-muted">-</span>';
                })
                ->addColumn('name', function ($row) {
                    return '<span class="text-dark font-weight-bold">' . e($row->name) . '</span>';
                })
                ->addColumn('contact', function ($row) {
                    $parts = [];
                    if (!empty($row->email)) {
                        $parts[] = '<a href="mailto:' . e($row->email) . '" style="color:#0284c7; text-decoration:none; font-weight:500;">' . e($row->email) . '</a>';
                    }
                    if (!empty($row->phone)) {
                        $parts[] = '<span class="text-muted d-block small mt-1">' . e($row->phone) . '</span>';
                    }
                    return !empty($parts) ? implode('', $parts) : '<span class="text-muted">-</span>';
                })
                ->addColumn('role', function ($row) {
                    return !empty($row->role) ? e($row->role) : '<span class="text-muted">-</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('M d, Y h:i A') : '-';
                })
                ->addColumn('action', function ($row) {
                    $pageName = $this->getPageName($row->type);

                    $jsonPayload = htmlspecialchars(json_encode([
                        'id' => $row->id,
                        'page' => $pageName,
                        'type' => $pageName,
                        'reference' => $row->reference ?: '-',
                        'name' => $row->name,
                        'email' => $row->email,
                        'mobile' => $row->phone ?: 'N/A',
                        'city' => $row->city ?: 'N/A',
                        'company' => $row->company ?: 'N/A',
                        'role' => $row->role ?: 'N/A',
                        'message' => $row->message ?: 'No message provided.',
                        'created_at' => $row->created_at ? $row->created_at->format('F d, Y - h:i A') : 'N/A',
                    ]), ENT_QUOTES, 'UTF-8');

                    return '<div class="text-center list-action actBtn-td" style="white-space: nowrap;">
                                <a href="javascript:;" class="view-lead-btn mx-1 text-primary" data-lead="' . $jsonPayload . '" data-toggle="tooltip" title="View Details"><i class="ft-eye font-medium-3"></i></a>
                                <a href="javascript:;" class="delete-lead-btn mx-1 text-danger" data-id="' . $row->id . '" data-toggle="tooltip" title="Delete"><i class="icon ft-trash-2 font-medium-3"></i></a>
                            </div>';
                })
                ->rawColumns(['page', 'type', 'reference', 'name', 'contact', 'role', 'action'])
                ->filterColumn('contact', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('email', 'like', "%{$keyword}%")
                          ->orWhere('phone', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->whereRaw("DATE_FORMAT(created_at, '%b %d, %Y') like ?", ["%{$keyword}%"])
                          ->orWhere('created_at', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('page', function ($query, $keyword) {
                    $reverseMap = [
                        'contact' => 'general',
                        'contact us' => 'general',
                        'light' => 'calculator',
                        'light calculator' => 'calculator',
                        'calculator' => 'calculator',
                        'catalogue' => 'catalogue',
                        'catalogues' => 'catalogue',
                        'catalog' => 'catalogue',
                        'product' => 'product',
                    ];
                    $lower = strtolower(trim($keyword));
                    if (isset($reverseMap[$lower])) {
                        $query->where('type', 'like', "%{$reverseMap[$lower]}%");
                    } else {
                        $query->where('type', 'like', "%{$keyword}%");
                    }
                })
                ->make(true);
        }
    }

    public function destroy($id)
    {
        try {
            $item = Inquiry::findOrFail($id);
            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Inquiry deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete record: ' . $e->getMessage()
            ], 500);
        }
    }
}
