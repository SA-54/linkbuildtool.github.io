@if(count(config('app.locales')) > 1)
    <div class="d-block d-md-inline-flex {{ (__('lang_dir') == 'rtl' ? ' mr-lg-3' : ' ml-lg-3') }}" data-toggle="tooltip" title="{{ __('Change language') }}">
        <a href="#" class="text-secondary text-decoration-none d-flex align-items-center py-1" data-toggle="modal" data-target="#changeLanguage">
            <span class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">@include('icons/language', ['class' => 'icon-text fill-current'])</span>
            <span class="flex-grow-1"><span class="text-muted">{{ config('app.locales')[config('app.locale')] }}</span></span>
        </a>
    </div>

    <div class="modal fade" id="changeLanguage" tabindex="-1" role="dialog" aria-labelledby="{{ __('Change language') }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Change language') }}</h6>
                    <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                    </button>
                </div>
                <form action="{{ route('locale') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @foreach(config('app.locales') as $code => $name)
                                <div class="col-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="i_language_{{ $code }}" name="locale" class="custom-control-input" value="{{ $code }}" @if(config('app.locale') == $code) checked @endif>
                                        <label class="custom-control-label" for="i_language_{{ $code }}" lang="{{ $code }}">{{ $name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif