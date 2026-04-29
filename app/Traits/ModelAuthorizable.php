<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


trait ModelAuthorizable
{
    public function __construct()
    {
        $className = class_basename($this); // Get the class name without the namespace
        $convertedName = Str::kebab($className);
        $permissionBaseName = str_replace('-controller', '', $convertedName);

        $this->middleware(['permission:' . $permissionBaseName . ': list', 'permission:' . $permissionBaseName . ': view']); //these are mandatory to access this class.
        $this->middleware(['permission:' . $permissionBaseName . ': create'], ['only' => ['create', 'store']]); //these rules are required for these methods only.
        $this->middleware(['permission:' . $permissionBaseName . ': edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:' . $permissionBaseName . ': delete'], ['only' => ['destroy']]);
        $this->middleware(['permission:' . $permissionBaseName . ': restore'], ['only' => ['restore']]);
    }
}
