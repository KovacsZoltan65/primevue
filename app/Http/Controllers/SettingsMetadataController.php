<?php

namespace App\Http\Controllers;

use App\Models\SettingsMetadata;
use Illuminate\Http\Request;

class SettingsMetadataController extends Controller
{
    public function __construct() {
        //
    }
    
    public function index(Request $request)
    {
        return response()->json(SettingsMetadata::all());
    }
    
    public function show(string $key)
    {
        $metadata = SettingMetadata::where('key', $key)->firstOrFail();
        return response()->json($metadata);
    }
    
    public function getAllMetadata(Request $request)
    {
        //
    }
    
    public function getMetadata(Request $request)
    {
        //
    }
    
    public function getMetadataByKey(string $key)
    {
        //
    }
    
    public function createMetadata(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings_metadata,key',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json',
            'level' => 'required|in:application,company',
            'is_required' => 'boolean',
            'default_value' => 'nullable',
            'validation_rules' => 'nullable|json',
        ]);

        $metadata = SettingMetadata::create($validated);
        return response()->json($metadata, 201);
    }
    
    public function updateMetadata(Request $request, int $id)
    {
        $metadata = SettingMetadata::where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|in:string,integer,boolean,json',
            'level' => 'nullable|in:application,company',
            'is_required' => 'boolean',
            'default_value' => 'nullable',
            'validation_rules' => 'nullable|json',
        ]);

        $metadata->update($validated);
        return response()->json($metadata);
    }
    
    public function deleteMetadata(int $id)
    {
        $metadata = SettingMetadata::where('id', $id)->firstOrFail();
        $metadata->delete();

        return response()->json(['message' => 'Metadata deactivated successfully']);
    }
}
