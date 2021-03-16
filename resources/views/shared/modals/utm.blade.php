<div class="modal fade" id="utmModal" tabindex="-1" role="dialog" aria-labelledby="utmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="utmModalLabel">{{ __('UTM Builder') }}</h6>
                <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                </button>
            </div>
            <div class="modal-body">
                @can('utm', ['App\Link', $userFeatures['option_utm']])
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="utm_source">{{ __('Source') }}</label>
                        <input type="text" name="utm_source" id="utm_source" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="utm_medium">{{ __('Medium') }}</label>
                        <input type="text" name="utm_medium" id="utm_medium" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="utm_campaign">{{ __('Campaign') }}</label>
                        <input type="text" name="utm_campaign" id="utm_campaign" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="utm_term">{{ __('Term') }}</label>
                        <input type="text" name="utm_term" id="utm_term" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-12 col-md-6">
                        <label for="utm_content">{{ __('Content') }}</label>
                        <input type="text" name="utm_content" id="utm_content" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-6">
                    </div>
                </div>
                @else
                    @include('shared.feature_unlock')
                @endcan
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>