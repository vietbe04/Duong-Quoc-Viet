<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Danh sách danh mục
     */
    public function index(Request $request)
    {
        $query = Category::withCount('courses');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Form tạo danh mục
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Lưu danh mục mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'status.required' => 'Vui lòng chọn trạng thái',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Đảm bảo slug unique
        $count = Category::where('slug', $validated['slug'])->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Tạo danh mục thành công!');
    }

    /**
     * Form sửa danh mục
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục',
            'name.unique' => 'Tên danh mục đã tồn tại',
            'status.required' => 'Vui lòng chọn trạng thái',
        ]);

        // Cập nhật slug nếu tên thay đổi
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            $count = Category::where('slug', $validated['slug'])
                ->where('id', '!=', $category->id)
                ->count();

            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục
     */
    public function destroy(Category $category)
    {
        // Kiểm tra có khóa học không
        if ($category->courses()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có khóa học!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Xóa danh mục thành công!');
    }
}
