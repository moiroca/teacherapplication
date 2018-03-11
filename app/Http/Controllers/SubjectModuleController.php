<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\SubjectModule;
use App\Http\Requests\AnnouncementPostRequest;

class SubjectModuleController extends Controller
{
	public function index(Request $request, $subject_id)
	{
		$subject = Subject::with('modules')->find($subject_id);

		return view('modules.index', compact('subject'));
	}

	public function create(Request $request, $subject_id)
	{
		$subject = Subject::find($subject_id);

		return view('modules.create', compact('subject'));
	}

	public function save(AnnouncementPostRequest $request, $subject_id)
	{
		$document = $request->file('document');

		$path = $document->store('documents', 'local');
		$name = $request->get('announcement');

		$module = Module::create([
			'name'	=> $name,
			'path'	=> $path
		]);

		SubjectModule::create([
			'subject_id' => $subject_id,
			'module_id'	 => $module->id
		]);

		return redirect()->route('modules.subject.index', ['subject_id' => $subject_id]);
	}

	public function download(Request $request)
	{
		
		return response()->download($pathToFile);
	}
}
