<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AVICONTROLController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('avicontrol::index');
    }

   
    public function welcome()
    {
        return view('avicontrol::welcome');
    }

    public function admin()
    {
        // Obtener alertas dinámicas para el dashboard
        $alertController = new \Modules\AVICONTROL\Http\Controllers\AlertController();
        $dashboardAlerts = $alertController->getDashboardAlerts();
        
        return view('avicontrol::admin.welcome', compact('dashboardAlerts'));
    }

    public function create()
    {
        return view('avicontrol::create');
    }


    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('avicontrol::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('avicontrol::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
