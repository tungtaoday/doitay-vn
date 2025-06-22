<div class="row">
    <div class="col-md-12">
        <div class="card overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive table-responsive--sm">
                    <table class="table align-items-center table--light">
                        <thead>
                        <tr>
                            <th>@lang('Short Code')</th>
                            <th>@lang('Description')</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                            @php
                                $templateShortcodes = $template->shortcodes;
                                // Convert object to array if needed
                                if (is_object($templateShortcodes)) {
                                    $templateShortcodes = (array) $templateShortcodes;
                                } elseif (is_string($templateShortcodes)) {
                                    $templateShortcodes = json_decode($templateShortcodes, true);
                                }
                                $templateShortcodes = $templateShortcodes ?: [];
                                
                                $globalShortcodes = gs('global_shortcodes');
                                if (is_object($globalShortcodes)) {
                                    $globalShortcodes = (array) $globalShortcodes;
                                } elseif (is_string($globalShortcodes)) {
                                    $globalShortcodes = json_decode($globalShortcodes, true);
                                }
                                $globalShortcodes = $globalShortcodes ?: [];
                            @endphp
                            
                            @if($templateShortcodes && is_array($templateShortcodes))
                                @foreach($templateShortcodes as $shortcode => $key)
                                <tr>
                                    {{-- blade-formatter-disable --}}
                                    <td><span class="short-codes">@php echo "{{". $shortcode ."}}"  @endphp</span></td>
                                    {{-- blade-formatter-enable --}}
                                    <td>{{ __($key) }}</td>
                                </tr>
                                @endforeach
                            @endif
                            
                            @if($globalShortcodes && is_array($globalShortcodes))
                                @foreach($globalShortcodes as $shortCode => $codeDetails)
                                <tr>
                                    {{-- blade-formatter-disable --}}
                                    <td><span class="short-codes">@{{@php echo $shortCode @endphp}}</span></td>
                                    {{-- blade-formatter-enable --}}
                                    <td>{{ __($codeDetails) }}</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- card end -->

    </div>
</div>
