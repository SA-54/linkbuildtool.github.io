<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">{{ __('Share') }}</h6>
                <button type="button" class="close d-flex align-items-center justify-content-center" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="d-flex align-items-center">@include('icons.close')</span>
                </button>
            </div>
            <div class="modal-body d-flex flex-wrap pt-0">
                <a href="#" id="share-twitter" class="d-flex align-items-center icon-twitter p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded">
                    @include('icons.share.twitter',  ['class' => 'icon-social text-white-important fill-current'])
                </a>

                <a href="#" id="share-facebook" class="d-flex align-items-center icon-facebook p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded">
                    @include('icons.share.facebook',  ['class' => 'icon-social text-white-important fill-current'])
                </a>

                <a href="#" id="share-reddit" class="d-flex align-items-center icon-reddit p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded">
                    @include('icons.share.reddit',  ['class' => 'icon-social text-white-important fill-current'])
                </a>

                <a href="#" id="share-pinterest" class="d-flex align-items-center icon-pinterest p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded">
                    @include('icons.share.pinterest',  ['class' => 'icon-social text-white-important fill-current'])
                </a>

                <a href="#" id="share-linkedin" class="d-flex align-items-center icon-linkedin p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded">
                    @include('icons.share.linkedin',  ['class' => 'icon-social text-white-important fill-current'])
                </a>

                <a href="#" id="share-email" class="d-flex align-items-center icon-email p-2 mt-3 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }} rounded">
                    @include('icons.share.email',  ['class' => 'icon-social text-white-important fill-current'])
                </a>

                <a href="#" id="share-qr" class="d-flex align-items-center icon-qr p-2 mt-3 rounded">
                    @include('icons.share.qr',  ['class' => 'icon-social text-white-important fill-current'])
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>