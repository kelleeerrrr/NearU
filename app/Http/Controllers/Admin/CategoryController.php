<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Predefined categories - these are user types, not database categories
        $categories = [
            [
                'name' => 'Student',
                'description' => 'Regular students looking for housing',
                'icon' => '🎓',
                'color' => '#3b82f6',
                'permissions' => ['Browse listings', 'Save favorites', 'Contact owners', 'Schedule visits']
            ],
            [
                'name' => 'Owner',
                'description' => 'Property owners listing dorms',
                'icon' => '🏠',
                'color' => '#f59e0b',
                'permissions' => ['Create listings', 'Manage inquiries', 'Schedule visits', 'View statistics']
            ],
            [
                'name' => 'Admin',
                'description' => 'System administrators',
                'icon' => '🛡️',
                'color' => '#dc2626',
                'permissions' => ['Full system access', 'User management', 'System settings', 'Reports and analytics']
            ]
        ];

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Categories are predefined, cannot create new ones
        return redirect()->route('admin.categories.index')
            ->with('info', 'Categories are predefined and cannot be modified.');
    }

    public function edit($id)
    {
        return view('admin.categories.edit');
    }

    public function update(Request $request, $id)
    {
        // Categories are predefined, cannot update
        return redirect()->route('admin.categories.index')
            ->with('info', 'Categories are predefined and cannot be modified.');
    }

    public function destroy($id)
    {
        // Categories are predefined, cannot delete
        return redirect()->route('admin.categories.index')
            ->with('info', 'Categories are predefined and cannot be modified.');
    }
}
