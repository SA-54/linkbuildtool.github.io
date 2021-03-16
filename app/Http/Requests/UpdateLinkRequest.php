<?php

namespace App\Http\Requests;

use App\Link;
use App\Rules\LinkDisabledGateRule;
use App\Rules\LinkExpirationGateRule;
use App\Rules\LinkGeoGateRule;
use App\Rules\LinkPasswordGateRule;
use App\Rules\LinkPlatformGateRule;
use App\Rules\LinkPublicGateRule;
use App\Rules\LinkRotationGateRule;
use App\Rules\LinkSpaceGateRule;
use App\Rules\ValidateAliasRule;
use App\Rules\ValidateBadWordsRule;
use App\Rules\ValidateDeepLinkRule;
use App\Rules\ValidateGeoKeyRule;
use App\Rules\ValidatePlatformKeyRule;
use App\Rules\ValidateSpaceOwnershipRule;
use App\Rules\ValidateUrlRule;
use App\Traits\UserFeaturesTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateLinkRequest extends FormRequest
{
    use UserFeaturesTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // If the request is to edit a link as a specific user
        // And the user is not an admin
        if (request()->has('user_id') && Auth::user()->role == 0) {
            return false;
        }

        // Check if the link to be edited exists under that user
        if (request()->has('user_id')) {
            Link::where([['id', '=', request()->route('id')], ['user_id', '=', request()->input('user_id')]])->firstOrFail();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userFeatures = $this->getFeatures(Auth::user());

        return [
            'url' => ['sometimes', 'required', new ValidateUrlRule(), 'max:2048', new ValidateBadWordsRule()],
            'alias' => ['sometimes', 'alpha_dash', 'max:255', new ValidateAliasRule()],
            'password' => ['nullable', 'string', 'max:128', new LinkPasswordGateRule($userFeatures)],
            'space' => ['nullable', 'integer', new ValidateSpaceOwnershipRule(), new LinkSpaceGateRule($userFeatures)],
            'disabled' => ['nullable', 'boolean', new LinkDisabledGateRule($userFeatures)],
            'public' => ['nullable', 'boolean', new LinkPublicGateRule($userFeatures)],
            'expiration_url' => ['nullable', new ValidateUrlRule(), 'max:2048', new ValidateBadWordsRule(), new LinkExpirationGateRule($userFeatures)],
            'expiration_date' => ['nullable', 'required_with:expiration_time', 'date_format:Y-m-d', new LinkExpirationGateRule($userFeatures)],
            'expiration_time' => ['nullable', 'required_with:expiration_date', 'date_format:H:i', new LinkExpirationGateRule($userFeatures)],
            'expiration_clicks' => ['nullable', 'integer', 'min:0', 'digits_between:0,9', new LinkExpirationGateRule($userFeatures)],
            'target_type' => ['nullable', 'integer', 'min:0', 'max:3'],
            'geo.*.key' => ['nullable', 'required_with:geo.*.value', new ValidateGeoKeyRule(), new LinkGeoGateRule($userFeatures)],
            'geo.*.value' => ['nullable', 'required_with:geo.*.key', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new ValidateDeepLinkRule($userFeatures)],
            'platform.*.key' => ['nullable', 'required_with:platform.*.value', new ValidatePlatformKeyRule(), new LinkPlatformGateRule($userFeatures)],
            'platform.*.value' => ['nullable', 'required_with:platform.*.key', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new ValidateDeepLinkRule($userFeatures)],
            'rotation.*.value' => ['nullable', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new ValidateDeepLinkRule($userFeatures), new LinkRotationGateRule($userFeatures)]
        ];
    }

    public function attributes()
    {
        return [
            'url' => __('Link'),
            'alias' => __('Alias'),
            'password' => __('Password'),
            'space' => __('Space'),
            'disabled' => __('Disabled'),
            'public' => __('Stats'),
            'expiration_url' => __('Expiration link'),
            'expiration_date' => __('Expiration date'),
            'expiration_time' => __('Expiration time'),
            'expiration_clicks' => __('Expiration clicks'),
            'geo.*.key' => __('Country'),
            'geo.*.value' => __('Link'),
            'platform.*.key' => __('Platform'),
            'platform.*.value' => __('Link'),
            'rotation.*.value' => __('Link')
        ];
    }
}
