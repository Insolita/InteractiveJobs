<?php

namespace App\Http\Controllers;

use App\Lib\InteractiveJobs\Models\JobLog;
use function redirect;
use function view;

class JobLogController extends Controller
{
    
    public function index()
    {
        $logs = JobLog::groups()->paginate();
        return view('logs.index', ['logs' => $logs]);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Lib\InteractiveJobs\Models\JobLog $log
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(JobLog $log)
    {
        $logs = JobLog::job($log)->paginate();
        return view('logs.show', ['logs' => $logs, 'group' => $log]);
    }
    
    public function showGroup($type, $id)
    {
        $log =  JobLog::where(['loggable_type' => $type, 'loggable_id' => $id])->firstOrFail();
        $logs = JobLog::job($log)->paginate();
        return view('logs.show', ['logs' => $logs, 'group' => $log]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lib\InteractiveJobs\Models\JobLog $log
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(JobLog $log)
    {
        JobLog::job($log)->delete();
        return redirect()->route('logs.index');
    }
}
