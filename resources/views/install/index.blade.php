<?php

// Define the required system configurations
$reqList = array(
    'php' => '8.1.0', // Example: Minimum PHP version required
    'openssl' => true,
    'fileinfo' => true,
    'pdo' => true,
    'curl' => true,
    'mbstring' => true,
    'tokenizer' => true,
    'xml' => true,
    'ctype' => true,
    'json' => true,
    'bcmath' => true,
    'gd' => true,
);

// Prepare the status strings
$strOk = '<span class="badge badge-success float-right">OK</span>';
$strFail = '<span class="badge badge-danger float-right">Fail</span>';

// Initialize an array to hold the requirement checks
$requirements = array();

// Perform the checks
$requirements['php_version'] = version_compare(PHP_VERSION, $reqList['php'], ">=") ? $strOk : $strFail;
$requirements['openssl_enabled'] = extension_loaded("openssl") ? $strOk : $strFail;
$requirements['pdo_enabled'] = extension_loaded("pdo") ? $strOk : $strFail;
$requirements['mbstring_enabled'] = extension_loaded("mbstring") ? $strOk : $strFail;
$requirements['curl_enabled'] = extension_loaded("curl") ? $strOk : $strFail;
$requirements['tokenizer_enabled'] = extension_loaded("tokenizer") ? $strOk : $strFail;
$requirements['xml_enabled'] = extension_loaded("xml") ? $strOk : $strFail;
$requirements['ctype_enabled'] = extension_loaded("ctype") ? $strOk : $strFail;
$requirements['fileinfo_enabled'] = extension_loaded("fileinfo") ? $strOk : $strFail;
$requirements['gd_enabled'] = extension_loaded("gd") ? $strOk : $strFail;
$requirements['json_enabled'] = extension_loaded("json") ? $strOk : $strFail;
$requirements['bcmath_enabled'] = extension_loaded("bcmath") ? $strOk : $strFail;


$allRequirementsMet = !in_array($strFail, $requirements);


$requirements['storage_writable'] = is_writable(storage_path()) ? $strOk : $strFail; // Adjust the path as necessary
$requirements['bootstrap_cache_writable'] = is_writable(base_path('bootstrap/cache')) ? $strOk : $strFail; // Adjust the path as necessary



?>


@extends('install.layout')

@section('title',"ElitePOS")


@section('content')

<div class="col-md-8">
    <h2 class="mb-4 text-light text-center">ElitePOS - Installation Wizard</h2>
    <div class="progress mb-4">
        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> </div>
    </div>
    <div class="card">
        <div class="card-header text-center">
            System Requirements Check
        </div>
        <ul class="list-group list-group-flush">
            <?php foreach ($requirements as $requirement => $status): ?>

                <li class="list-group-item"><?= ucfirst(str_replace('_', ' ', $requirement)) ?> 
                    @if ($requirement == 'php_version')
                    ( {{ PHP_VERSION}} ) 
                    @endif
                    <?= $status ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="mt-4 text-center">
        <button class="btn btn-primary" onclick="window.location.reload();">Check Again</button>
        <button class="btn btn-success" id="nextStepButton" disabled data-all-requirements-met="<?php echo $allRequirementsMet ? 'true' : 'false'; ?>">Next Step</button>

    </div>
</div>
    
@endsection


@push('scripts')

<script>
    var NextStep ='{{ url('install/step-1')}}' ;
    document.addEventListener("DOMContentLoaded", function() {
        var nextStepButton = document.getElementById("nextStepButton");
        var allRequirementsMet = nextStepButton.getAttribute("data-all-requirements-met") === "true";
    
        if (allRequirementsMet) {
            nextStepButton.disabled = false; // Enable the button if all requirements are met
            nextStepButton.addEventListener("click", function() {
                window.location.href = NextStep; // Modify this URL to the actual next step of your installation process
            });
        } else {
            // Optionally, provide feedback or alert to the user that not all requirements are met
            console.log("Some requirements are not met. Please resolve these issues before proceeding.");
        }
    });
    </script>
    
    
@endpush