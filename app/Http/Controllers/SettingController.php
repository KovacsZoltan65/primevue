<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySettigRel;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;
use Inertia\Response AS InertiaResponse;

class SettingController extends Controller
{

    /**
     * Jelenítse meg az oldalt az alapértelmezett beállításokhoz.
     *
     * @return \Inertia\Response
     */
    public function defaultIndex(): InertiaResponse
    {
        // Az alapértelmezett beállításokat tartalmazó oldalhoz szükséges adatokat szolgáltatja.
        $settings = Setting::all();

        return Inertia::render(
            'Setting/Default/Index', 
            ['settings' => $settings]
        );
    }

    public function companyIndex()
    {
        $companies = Company::all();
        /*
        $company = Company::with(['settings' => function ($query) {
            $query->wherePivot('is_active', true);
        }])->findOrFail($companyId);
        
        #$company = Company::findOrFail($companyId);
        $company_settings = $company->settings->map(function ($setting) {
            return [
                'id' => $setting->id,
                'name' => $setting->name,
                'value' => $setting->pivot->value ?? $setting->default_value,
                'is_active' => $setting->pivot->is_active,
            ];
        });
*/
        return Inertia::render(
            'Setting/Company/Index', 
            [
                'companies' => $companies
            ]
        );
        
    }

    public function getDefaultSettingById(int $id){
        $setting = Setting::where('id', $id)
            ->findOrFail($id);

        return response()->json(
            [
                'id' => $setting->id,
                'name' => $setting->name,
                'default_value' => $setting->default_value,
                'is_active' => $setting->is_active,
            ], 
            Response::HTTP_OK);
    }

    public function getCompanySettingById(int $id){}

    public function create(Request $request)
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
            'is_active' => true,
        ]);
    
        if (!empty($validated['companies'])) {
            foreach ($validated['companies'] as $companyId) {
                $setting->companies()->attach($companyId, ['value' => $validated['default_value']]);
            }
        }
    
        activity()->log("New setting created: {$setting->name}");
        return redirect()->back()->with('success', 'Setting created successfully.');
    }

    public function update(Request $request, $settingId)
    {
        $validated = $request->validate([
            'default_value' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $setting = Setting::findOrFail($settingId);
        $setting->update([
            'default_value' => $validated['default_value'],
            'is_active' => $validated['is_active'],
        ]);

        activity()->log("Setting updated: {$setting->name}");
        return redirect()->back()->with('success', 'Setting updated successfully.');
    }

    public function deactivateSetting($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->update(['is_active' => false]);

        activity()->log("Setting globally deactivated: {$setting->name}");
        return redirect()->route('settings.index')->with('success', 'Setting deactivated successfully.');
    }

    public function deactivateCompanySetting($companyId, $settingId)
    {
        $companySetting = CompanySettigRel::where('companies_id', $companyId)
            ->where('settings_id', $settingId)->firstOrFail();
        $companySetting->update(['is_active' => false]);

        activity()->log("Company-specific setting deactivated: Company ID {$companyId}, Setting ID {$settingId}");
        return redirect()->route('company.settings.index', $companyId)
            ->with('success', 'Setting deactivated successfully.');
    }

}
