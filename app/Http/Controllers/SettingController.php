<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Models\Company;
use App\Models\CompanySettigRel;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use Inertia\Response AS InertiaResponse;

class SettingController extends Controller
{
    public function __construct()
    {
        //
    }

    /**
     * Jelenítse meg az oldalt az alapértelmezett beállításokhoz.
     *
     * @return \Inertia\Response
     */
    public function defaultIndex(): InertiaResponse
    {
        return Inertia::render('Setting/Default/Index');
    }

    public function companyIndex()
    {
        $companies = Company::all();

        return Inertia::render('Setting/Company/Index', [
            'companies' => $companies
        ]);
        
    }

    /**
     * Az alapértelmezett beállítások lekérése a kérelemben megadott keresési feltételek alapján.
     *
     * @param Request $request A keresési paramétereket tartalmazó kéréspéldány.
     * @return \Illuminate\Http\Resources\Json\JsonResource Az alapértelmezett beállítások forrásainak gyűjteménye.
     */
    public function getDefaultSettings(Request $request): JsonResource
    {
        $settingQuery = Setting::search($request);
        $settings = SettingResource::collection($settingQuery->get());

        return $settings;
    }

    /**
     * A cégspecifikus beállítások lekérése a kérésben megadott keresési feltételek alapján.
     *
     * Ez a metódus lekérdezi a vállalati beállítások kapcsolatát, és egy gyűjteményt ad vissza
     * a keresési paramétereknek megfelelő beállítási erőforrások közül.
     *
     * @param Request $request A keresési paramétereket tartalmazó kéréspéldány.
     * @return \Illuminate\Http\Resources\Json\JsonResource Vállalati beállítási erőforrások gyűjteménye.
     */
    public function getComapnySettings(Request $request): JsonResource
    {
        $settingQuery = CompanySettigRel::search($request);
        $settings = SettingResource::collection($settingQuery->get());

        return $settings;
    }

    

    public function getDefaultSettingById(int $id){
        $setting = Setting::where('id', $id)
            ->findOrFail($id);

        return response()->json(
            [
                'id' => $setting->id,
                'name' => $setting->name,
                'default_value' => $setting->default_value,
                'active' => $setting->active,
            ], 
            Response::HTTP_OK);
    }

    public function getCompanySettingById(int $id){}

    public function createDefaultSetting(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_value' => 'nullable|string',
            'companies' => 'array',
            'companies.*' => 'exists:companies,id',
        ]);
    
        $setting = Setting::create([
            'name' => $validated['name'],
            'default_value' => $validated['default_value'],
            'active' => true,
        ]);
    
        if (!empty($validated['companies'])) {
            foreach ($validated['companies'] as $companyId) {
                $setting->companies()->attach($companyId, ['value' => $validated['default_value']]);
            }
        }
    
        activity()->log("New setting created: {$setting->name}");
        return redirect()->back()->with('success', 'Setting created successfully.');
    }

    public function createCompanySetting(Request $request)
    {
        //
    }

    public function updateDefaultSetting(Request $request, $settingId)
    {
        $validated = $request->validate([
            'default_value' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $setting = Setting::findOrFail($settingId);
        $setting->update([
            'default_value' => $validated['default_value'],
            'active' => $validated['active'],
        ]);

        activity()->log("Setting updated: {$setting->name}");
        return redirect()->back()->with('success', 'Setting updated successfully.');
    }

    public function updateCompanySetting(Request $request)
    {
        //
    }

    public function deactivateDefaultSetting($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->update(['active' => false]);

        activity()->log("Setting globally deactivated: {$setting->name}");
        return redirect()->route('settings.index')->with('success', 'Setting deactivated successfully.');
    }

    public function deactivateCompanySetting($companyId, $settingId)
    {
        $companySetting = CompanySettigRel::where('companies_id', $companyId)
            ->where('settings_id', $settingId)->firstOrFail();
        $companySetting->update(['active' => false]);

        activity()->log("Company-specific setting deactivated: Company ID {$companyId}, Setting ID {$settingId}");
        return redirect()->route('company.settings.index', $companyId)
            ->with('success', 'Setting deactivated successfully.');
    }

    public function deleteDefaultSetting($settingId){}

    public function deleteCompanySetting($companyId, $settingId){}
}
