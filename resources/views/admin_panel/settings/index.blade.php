@extends('admin_panel.layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ERP Settings</h3>
                    </div>
                    <div class="card-body">
                        <!-- Advanced Settings & Navigation -->
                        <div class="mb-4 pb-3 border-bottom">
                            <h6 class="text-muted mb-3 font-weight-bold text-uppercase"
                                style="font-size: 0.8rem; letter-spacing: 1px;">Advanced Actions</h6>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('settings.return-policy') }}"
                                    class="btn btn-outline-primary mr-2 mb-2 shadow-sm">
                                    <i class="fas fa-undo-alt mr-2"></i> Return Policy
                                </a>
                                <a href="{{ route('settings.return-approvers') }}"
                                    class="btn btn-outline-info mr-2 mb-2 shadow-sm">
                                    <i class="fas fa-user-shield mr-2"></i> Return Approvers
                                </a>
                                <a href="#" class="btn btn-outline-dark mr-2 mb-2 shadow-sm">
                                    <i class="fas fa-exchange-alt mr-2"></i> Switch Account
                                </a>
                            </div>
                        </div>

                        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="company-tab" data-toggle="tab" href="#company"
                                    role="tab">
                                    <i class="fas fa-building"></i> Company
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab">
                                    <i class="fas fa-shopping-cart"></i> Sales
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="inventory-tab" data-toggle="tab" href="#inventory" role="tab">
                                    <i class="fas fa-boxes"></i> Inventory
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="accounting-tab" data-toggle="tab" href="#accounting" role="tab">
                                    <i class="fas fa-calculator"></i> Accounting
                                </a>
                            </li>
                        </ul>

                        @php
                            $canEditSettings = !auth()->check() || auth()->user()->hasRole('Super Admin') || auth()->user()->can('settings.edit') || auth()->user()->can('settings.create');
                        @endphp
                        <form id="settingsForm" class="mt-4" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="settingsTabContent">
                                <!-- Company Tab -->
                                <div class="tab-pane fade show active" id="company" role="tabpanel">
                                    <!-- Company Logo Section -->
                                    @php
                                        $companyLogo = \App\Models\Setting::get('company_logo') ?: \App\Models\Setting::get('web_site_logo');
                                        $hasLogo = !empty($companyLogo);
                                    @endphp
                                    <div class="card mb-4 border shadow-sm" style="background-color: #f8fafc;">
                                        <div class="card-header bg-white font-weight-bold d-flex align-items-center">
                                            <i class="fas fa-image text-primary mr-2"></i> Company Logo (Sale Receipt & Invoice)
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted small mb-3">
                                                Upload your official company logo. When uploaded, this logo will appear at the top of thermal sale receipts and invoices.
                                            </p>
                                            <div class="row align-items-center">
                                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                                    <div id="logoPreviewContainer" class="p-3 bg-white border rounded shadow-sm d-inline-flex align-items-center justify-content-center" style="min-width: 170px; min-height: 110px; width: 100%; max-width: 220px; height: 110px;">
                                                        <img id="logoPreview" 
                                                             src="{{ $hasLogo ? asset(ltrim($companyLogo, '/')) : '' }}" 
                                                             alt="Company Logo Preview" 
                                                             style="max-width: 100%; max-height: 90px; object-fit: contain; {{ !$hasLogo ? 'display: none;' : '' }}" />
                                                        <div id="noLogoText" class="text-muted text-center" style="{{ $hasLogo ? 'display: none;' : '' }}">
                                                            <i class="fas fa-image fa-2x d-block mb-1 text-secondary"></i>
                                                            <small>No Logo Uploaded</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="custom-file mb-2">
                                                        <input type="file" name="company_logo" id="companyLogoInput" class="custom-file-input" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        <label class="custom-file-label text-truncate" for="companyLogoInput" id="companyLogoLabel">Choose logo image file...</label>
                                                    </div>
                                                    <input type="hidden" name="remove_company_logo" id="removeCompanyLogoInput" value="0">
                                                    
                                                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-danger" id="btnRemoveLogo" style="{{ $hasLogo ? '' : 'display: none;' }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                            <i class="fas fa-trash-alt mr-1"></i> Remove Logo
                                                        </button>
                                                        <small class="text-muted ml-md-2">Formats: PNG, JPG, WebP, SVG (Max: 4MB). Transparent PNG is recommended.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $compSettings = collect($settings['company'] ?? [])->keyBy('key');
                                    @endphp

                                    <!-- Basic Company Info Card -->
                                    <div class="card mb-4 border shadow-sm">
                                        <div class="card-header bg-white font-weight-bold d-flex align-items-center">
                                            <i class="fas fa-building text-primary mr-2"></i> Company Basic Details
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @if(isset($compSettings['company_name']))
                                                    <div class="form-group">
                                                        <label>{{ $compSettings['company_name']['label'] }}</label>
                                                        <input type="text" name="settings[company_name]" class="form-control" value="{{ $compSettings['company_name']['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        <small class="form-text text-muted">{{ $compSettings['company_name']['description'] }}</small>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    @if(isset($compSettings['currency_symbol']))
                                                    <div class="form-group">
                                                        <label>{{ $compSettings['currency_symbol']['label'] }}</label>
                                                        <input type="text" name="settings[currency_symbol]" class="form-control" value="{{ $compSettings['currency_symbol']['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        <small class="form-text text-muted">{{ $compSettings['currency_symbol']['description'] }}</small>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-12">
                                                    @if(isset($compSettings['company_phone']))
                                                    <div class="form-group">
                                                        <label>{{ $compSettings['company_phone']['label'] }}</label>
                                                        <input type="text" name="settings[company_phone]" class="form-control" value="{{ $compSettings['company_phone']['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        <small class="form-text text-muted">{{ $compSettings['company_phone']['description'] }}</small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Invoice Header & Footer Customization Card -->
                                    <div class="card mb-4 border shadow-sm border-primary">
                                        <div class="card-header bg-primary text-white font-weight-bold d-flex align-items-center">
                                            <i class="fas fa-file-invoice mr-2"></i> Simple Invoice Header & Footer Customization
                                        </div>
                                        <div class="card-body" style="background-color: #fafbfc;">
                                            <p class="text-muted small mb-3">
                                                Customize the 3-column contact header and bottom address banner of the sales invoice (http://127.0.0.1:8000/sales/{id}/invoice).
                                            </p>

                                            <div class="row">
                                                <!-- Invoice Company Title / Name (e.g. Long Life Accessories) -->
                                                <div class="col-md-7">
                                                    @php 
                                                        $sCompName = $compSettings['invoice_company_name'] ?? null; 
                                                        $valCompName = $sCompName['value'] ?? ($compSettings['company_name']['value'] ?? 'Long Life Accessories');
                                                    @endphp
                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark">
                                                            <i class="fas fa-heading text-primary mr-1"></i> Invoice Company Title / Line (Center Header)
                                                        </label>
                                                        <input type="text" name="settings[invoice_company_name]" class="form-control font-weight-bold text-danger" value="{{ $valCompName }}" placeholder="Long Life Accessories" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        <small class="form-text text-muted">Title line displayed directly below the GLC logo in the center (e.g. <strong>Long Life Accessories</strong>).</small>
                                                    </div>
                                                </div>

                                                <!-- Invoice Online Link / Website URL -->
                                                <div class="col-md-5">
                                                    @php 
                                                        $sOnlineLink = $compSettings['invoice_online_link'] ?? null; 
                                                        $valOnlineLink = $sOnlineLink['value'] ?? ($compSettings['website_link']['value'] ?? '');
                                                    @endphp
                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark">
                                                            <i class="fas fa-link text-info mr-1"></i> Invoice Online / Website Link
                                                        </label>
                                                        <input type="text" name="settings[invoice_online_link]" class="form-control" value="{{ $valOnlineLink }}" placeholder="e.g. http://127.0.0.1:8000/sales/1/invoice" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        <small class="form-text text-muted">Optional web link or invoice URL.</small>
                                                    </div>
                                                </div>

                                                <!-- Show / Hide Company Name Text Toggle -->
                                                <div class="col-md-12 mb-3">
                                                    @php
                                                        $showCompNameSetting = \App\Models\Setting::get('show_company_name_on_print', '0');
                                                    @endphp
                                                    <div class="custom-control custom-switch bg-white p-2 pl-5 border rounded shadow-sm">
                                                        <input type="hidden" name="settings[show_company_name_on_print]" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="showCompNameToggle" name="settings[show_company_name_on_print]" value="1" {{ $showCompNameSetting == '1' ? 'checked' : '' }} {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        <label class="custom-control-label font-weight-bold text-dark" for="showCompNameToggle">
                                                            Show Company Name text on Invoice & Receipt <span class="text-muted font-weight-normal">(Default: OFF &mdash; Logo image mein pehle se GLC likha hua hai)</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Header Left Contacts -->
                                                <div class="col-md-6">
                                                    @php $sLeft = $compSettings['invoice_header_contacts_left'] ?? null; @endphp
                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark">
                                                            <i class="fas fa-user-tag text-info mr-1"></i> Header Contacts (Left Column)
                                                        </label>
                                                        <textarea name="settings[invoice_header_contacts_left]" class="form-control font-weight-bold" rows="4" placeholder="Faisal Mehmood&#10;0325 4682466&#10;Furqaan Shakeel&#10;03044576200" {{ !$canEditSettings ? 'disabled' : '' }}>{{ $sLeft['value'] ?? '' }}</textarea>
                                                        <small class="form-text text-muted">Names and phone numbers shown on the left of the invoice logo.</small>
                                                    </div>
                                                </div>

                                                <!-- Header Right Contacts -->
                                                <div class="col-md-6">
                                                    @php $sRight = $compSettings['invoice_header_contacts_right'] ?? null; @endphp
                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark">
                                                            <i class="fas fa-phone-alt text-success mr-1"></i> Header Contacts (Right Column)
                                                        </label>
                                                        <textarea name="settings[invoice_header_contacts_right]" class="form-control font-weight-bold" rows="4" placeholder="Anjum Shakeel&#10;03008033184&#10;Tayyab Shakeel&#10;03054453139&#10;PTCL #: 042 37322272&#10;042 37232370" {{ !$canEditSettings ? 'disabled' : '' }}>{{ $sRight['value'] ?? '' }}</textarea>
                                                        <small class="form-text text-muted">Names, mobile & PTCL numbers shown on the right of the invoice logo.</small>
                                                    </div>
                                                </div>

                                                <!-- Header Tagline / Deals In -->
                                                <div class="col-md-12">
                                                    @php $sTag = $compSettings['invoice_header_tagline'] ?? null; @endphp
                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark">
                                                            <i class="fas fa-tags text-warning mr-1"></i> Header Deals In / Product Categories (Under Company Name)
                                                        </label>
                                                        <textarea name="settings[invoice_header_tagline]" class="form-control font-weight-bold text-danger" rows="2" placeholder="Power Bank, Battery, Data Cable, Charger, Head Phone&#10;Handsfree, Bluetooth & Multimedia" {{ !$canEditSettings ? 'disabled' : '' }}>{{ $sTag['value'] ?? '' }}</textarea>
                                                        <small class="form-text text-muted">Categories/tagline displayed directly below the company name in the invoice header.</small>
                                                    </div>
                                                </div>

                                                <!-- Invoice Note / Terms (NOTE Section) -->
                                                <div class="col-md-12">
                                                    @php $sTerms = \App\Models\Setting::get('invoice_terms'); @endphp
                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark">
                                                            <i class="fas fa-clipboard-list text-primary mr-1"></i> Invoice Note / Terms (Invoice NOTE: Section)
                                                        </label>
                                                        <textarea name="settings[invoice_terms]" class="form-control font-weight-bold" rows="3" placeholder="1. No Return no Exchange without bill&#10;2. Change of Mind is not accepted&#10;3. Damaged or Burned not Accepted" {{ !$canEditSettings ? 'disabled' : '' }}>{{ $sTerms ?? '' }}</textarea>
                                                        <small class="form-text text-muted">Notes or return terms displayed on the bottom-left of the invoice under NOTE:.</small>
                                                    </div>
                                                </div>

                                                <!-- Company / Branch Addresses (Footer Banner) -->
                                                <div class="col-md-12">
                                                    @if(isset($compSettings['company_address']))
                                                    <div class="form-group">
                                                        <label class="font-weight-bold text-dark">
                                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i> Company Address / Branches (Invoice Bottom Footer Banner)
                                                        </label>
                                                        <textarea name="settings[company_address]" class="form-control" rows="3" placeholder="Branch#1: Shop #22,45, Basement Madina Center The Mall Hall Road, Lhr.&#10;Branch#2: Shop #40,43, 3rd Floor Hassan Center Hall Road, Lhr." {{ !$canEditSettings ? 'disabled' : '' }}>{{ $compSettings['company_address']['value'] }}</textarea>
                                                        <small class="form-text text-muted">Branch addresses displayed inside the bottom footer banner on invoices.</small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Links Card -->
                                    <div class="card mb-4 border shadow-sm">
                                        <div class="card-header bg-white font-weight-bold d-flex align-items-center">
                                            <i class="fas fa-share-alt text-primary mr-2"></i> Social Media Links (Receipts)
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach(['facebook_link', 'tiktok_link', 'instagram_link', 'website_link'] as $socKey)
                                                    @if(isset($compSettings[$socKey]))
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>{{ $compSettings[$socKey]['label'] }}</label>
                                                            <input type="text" name="settings[{{ $socKey }}]" class="form-control" value="{{ $compSettings[$socKey]['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                        </div>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Any other company settings -->
                                    @php
                                        $handledKeys = [
                                            'company_logo', 'web_site_logo', 'company_name', 'currency_symbol', 'company_phone',
                                            'invoice_company_name', 'invoice_online_link', 'show_company_name_on_print',
                                            'invoice_header_contacts_left', 'invoice_header_contacts_right', 'invoice_header_tagline',
                                            'company_address', 'invoice_terms', 'facebook_link', 'tiktok_link', 'instagram_link', 'website_link'
                                        ];
                                    @endphp
                                    @foreach($compSettings as $setting)
                                        @if(in_array($setting['key'], $handledKeys))
                                            @continue
                                        @endif
                                        <div class="form-group">
                                            <label>{{ $setting['label'] }}</label>
                                            @if ($setting['type'] === 'text')
                                                <textarea name="settings[{{ $setting['key'] }}]" class="form-control" rows="3" {{ !$canEditSettings ? 'disabled' : '' }}>{{ $setting['value'] }}</textarea>
                                            @else
                                                <input type="text" name="settings[{{ $setting['key'] }}]" class="form-control" value="{{ $setting['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                            @endif
                                            @if ($setting['description'])
                                                <small class="form-text text-muted">{{ $setting['description'] }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Sales Tab -->
                                <div class="tab-pane fade" id="sales" role="tabpanel">
                                    @if (isset($settings['sales']))
                                        @foreach ($settings['sales'] as $setting)
                                            <div class="form-group">
                                                <label>{{ $setting['label'] }}</label>
                                                @if ($setting['type'] === 'text')
                                                    <textarea name="settings[{{ $setting['key'] }}]" class="form-control" rows="3" {{ !$canEditSettings ? 'disabled' : '' }}>{{ $setting['value'] }}</textarea>
                                                @elseif($setting['type'] === 'integer')
                                                    <input type="number" name="settings[{{ $setting['key'] }}]"
                                                        class="form-control" value="{{ $setting['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                @else
                                                    <input type="text" name="settings[{{ $setting['key'] }}]"
                                                        class="form-control" value="{{ $setting['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                @endif
                                                @if ($setting['description'])
                                                    <small
                                                        class="form-text text-muted">{{ $setting['description'] }}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Inventory Tab -->
                                <div class="tab-pane fade" id="inventory" role="tabpanel">
                                    @if (isset($settings['inventory']))
                                        @foreach ($settings['inventory'] as $setting)
                                            <div class="form-group">
                                                <label>{{ $setting['label'] }}</label>
                                                <input type="number" name="settings[{{ $setting['key'] }}]"
                                                    class="form-control" value="{{ $setting['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                @if ($setting['description'])
                                                    <small
                                                        class="form-text text-muted">{{ $setting['description'] }}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Accounting Tab -->
                                <div class="tab-pane fade" id="accounting" role="tabpanel">
                                    @if (isset($settings['accounting']))
                                        @foreach ($settings['accounting'] as $setting)
                                            <div class="form-group">
                                                <label>{{ $setting['label'] }}</label>
                                                <input type="text" name="settings[{{ $setting['key'] }}]"
                                                    class="form-control" value="{{ $setting['value'] }}" {{ !$canEditSettings ? 'disabled' : '' }}>
                                                @if ($setting['description'])
                                                    <small
                                                        class="form-text text-muted">{{ $setting['description'] }}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                @if($canEditSettings)
                                    <button type="submit" class="btn btn-primary" id="btnSaveSettings">
                                        <i class="fas fa-save mr-1"></i> Save Settings
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
                                        <i class="fas fa-lock mr-1"></i> Save Settings (Read Only)
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            $(document).ready(function() {
                // Live preview logo on file select
                $('#companyLogoInput').on('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        $('#companyLogoLabel').text(file.name);
                        $('#removeCompanyLogoInput').val('0');
                        var reader = new FileReader();
                        reader.onload = function(evt) {
                            $('#logoPreview').attr('src', evt.target.result).show();
                            $('#noLogoText').hide();
                            $('#btnRemoveLogo').show();
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Remove logo action
                $('#btnRemoveLogo').on('click', function() {
                    $('#companyLogoInput').val('');
                    $('#companyLogoLabel').text('Choose logo image file...');
                    $('#removeCompanyLogoInput').val('1');
                    $('#logoPreview').attr('src', '').hide();
                    $('#noLogoText').show();
                    $(this).hide();
                });

                $('#settingsForm').on('submit', function(e) {
                    e.preventDefault();

                    var submitBtn = $('#btnSaveSettings');
                    var origHtml = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

                    var formEl = document.getElementById('settingsForm');
                    var formData = new FormData(formEl);

                    $.ajax({
                        url: '{{ route('settings.update') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            submitBtn.prop('disabled', false).html(origHtml);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            if (response.logo_url) {
                                $('#logoPreview').attr('src', response.logo_url + '?t=' + new Date().getTime()).show();
                                $('#noLogoText').hide();
                                $('#btnRemoveLogo').show();
                                $('#removeCompanyLogoInput').val('0');
                                $('#companyLogoInput').val('');
                                $('#companyLogoLabel').text('Choose logo image file...');
                            } else if ($('#removeCompanyLogoInput').val() === '1') {
                                $('#logoPreview').attr('src', '').hide();
                                $('#noLogoText').show();
                                $('#btnRemoveLogo').hide();
                            }
                        },
                        error: function(xhr) {
                            submitBtn.prop('disabled', false).html(origHtml);
                            var errorMsg = 'Failed to update settings';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                var errs = Object.values(xhr.responseJSON.errors).flat();
                                if (errs.length > 0) errorMsg = errs.join('\n');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMsg,
                            });
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection
