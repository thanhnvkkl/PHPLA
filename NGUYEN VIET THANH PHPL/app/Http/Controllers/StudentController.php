<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    //CRUD


    //them - Create - C
    public function addstudent()
    {
        return view('student.create');
    }

    public function store(Request $request)
    {
        $student = new Student;
        $student->ten = $request->input('ten');
        $student->email = $request->input('email');
        $student->lop = $request->input('lop');
        if ($request->hasFile('anhdaidien')) {
            $file = $request->file('anhdaidien');
            $extension = $file->getClientOriginalExtension(); //lay ten mo rong .jpg, .png,....
            $filename = time() . '.' . $extension;
            $file->move('uploads/students/', $filename);  //upload len thu muc uploads/students
            $student->anhdaidien = $filename;
        }
        $student->save();
        return redirect()->back()->with('status', 'Them sinh vien thanh cong');
    }
    //liet ke - React - R
    public function index()
    {
        $students = Student::all();  //lay tat ca du lieu trong bang student
        return view('student.index', compact('students')); //tim den file index.blade.php trong thu muc views/student
    }

    //cap nhat - Update - U
    public function edit($id)
    {
        //tim student theo id
        $student = Student::find($id);
        return view('student.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        //tim student theo id
        $student = Student::find($id);
        $student->ten = $request->input('ten');
        $student->email = $request->input('email');
        $student->lop = $request->input('lop');
        if ($request->hasFile('anhdaidien')) {
            //co file dinh kem trong form update thi tim file cu va xoa di
            //neu truoc do ko co anh dai dien cu thi ko xoa
            $anhcu = 'uploads/students/' . $student->anhdaidien;
            if (File::exists($anhcu)) {
                File::delete($anhcu);
            }
            $file = $request->file('anhdaidien');
            $extension = $file->getClientOriginalExtension(); //lay ten mo rong .jpg, .png,....
            $filename = time() . '.' . $extension;
            $file->move('uploads/students/', $filename);  //upload len thu muc uploads/students
            $student->anhdaidien = $filename;
        }
        $student->update();
        return redirect()->back()->with('status', 'Cap nhat sinh vien voi anh dai dien thanh cong');
    }
    //xoa - Delete - D
    public function delete($id)
    {
        $student = Student::find($id);
        $anhdaidien = 'uploads/students/' . $student->anhdaidien;
        if (File::exists($anhdaidien)) {
            File::delete($anhdaidien);
        }
        $student->delete();
        return redirect()->back()->with('status', 'Xóa sinh viên và ảnh đại diện thành công');
    }
}
