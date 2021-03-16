<?php

namespace App\Http\Requests;

use App\Rules\LinkDisabledGateRule;
use App\Rules\LinkDomainGateRule;
use App\Rules\LinkExpirationGateRule;
use App\Rules\FieldNotPresentRule;
use App\Rules\LinkGeoGateRule;
use App\Rules\LinkPasswordGateRule;
use App\Rules\LinkPlatformGateRule;
use App\Rules\LinkPublicGateRule;
use App\Rules\LinkRotationGateRule;
use App\Rules\LinkSpaceGateRule;
use App\Rules\ValidateAliasRule;
use App\Rules\ValidateBadWordsRule;
use App\Rules\ValidateDeepLinkRule;
use App\Rules\ValidateDomainOwnershipRule;
use App\Rules\LinkLimitGateRule;
use App\Rules\ValidateGeoKeyRule;
use App\Rules\ValidatePlatformKeyRule;
use App\Rules\ValidateSpaceOwnershipRule;
use App\Rules\ValidateUrlRule;
use App\Rules\ValidateUrlsCountRule;
use App\Rules\ValidateUrlsRule;
use App\Traits\UserFeaturesTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateLinkRequest extends FormRequest
{
    use UserFeaturesTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        // Prevent guest users from creating multi-links
        if ($request->input('multi_link') && Auth::check() == false) {
            abort(403);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        // If the user is logged-in
        if (Auth::check()) {
            $userFeatures = $this->getFeatures(Auth::user());

            if ($request->input('multi_link')) {
                $rules = [
                    'urls' => ['required', new ValidateBadWordsRule(), new LinkLimitGateRule($userFeatures), new ValidateUrlsRule(), new ValidateUrlsCountRule()],
                    'space' => ['nullable', 'integer', new ValidateSpaceOwnershipRule(), new LinkSpaceGateRule($userFeatures)],
                    'domain' => ['nullable', 'integer', new ValidateDomainOwnershipRule(), new LinkDomainGateRule($userFeatures)]
                ];
            } else {
                $rules = [
                    'url' => ['required', 'max:2048', new ValidateUrlRule(), new ValidateBadWordsRule(), new LinkLimitGateRule($userFeatures), new ValidateDeepLinkRule($userFeatures)],
                    'alias' => ['nullable', 'alpha_dash', 'max:255', new ValidateAliasRule()],
                    'password' => ['nullable', 'string', 'max:128', new LinkPasswordGateRule($userFeatures)],
                    'space' => ['nullable', 'integer', new ValidateSpaceOwnershipRule(), new LinkSpaceGateRule($userFeatures)],
                    'domain' => ['nullable', 'integer', new ValidateDomainOwnershipRule(), new LinkDomainGateRule($userFeatures)],
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
        }
        // If the user is not logged in
        else {
            $rules = [
                'url' => ['required', 'max:2048', new ValidateUrlRule(), new ValidateDeepLinkRule(null), new ValidateBadWordsRule()],
                'alias' => [new FieldNotPresentRule()],
                'password' => [new FieldNotPresentRule()],
                'space' => [new FieldNotPresentRule()],
                'domain' => [new FieldNotPresentRule()],
                'disabled' => [new FieldNotPresentRule()],
                'public' => [new FieldNotPresentRule()],
                'expiration_url' => [new FieldNotPresentRule()],
                'expiration_date' => [new FieldNotPresentRule()],
                'expiration_time' => [new FieldNotPresentRule()],
                'expiration_clicks' => [new FieldNotPresentRule()],
                'target_type' => [new FieldNotPresentRule()],
                'geo.*.key' => [new FieldNotPresentRule()],
                'geo.*.value' => [new FieldNotPresentRule()],
                'platform.*.key' => [new FieldNotPresentRule()],
                'platform.*.value' => [new FieldNotPresentRule()],
                'rotation.*.value' => [new FieldNotPresentRule()],
                'g-recaptcha-response' => [(config('settings.captcha_shorten') ? 'required' : 'sometimes'), 'captcha']
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'url' => __('Link'),
            'alias' => __('Alias'),
            'password' => __('Password'),
            'space' => __('Space'),
            'domain' => __('Domain'),
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
