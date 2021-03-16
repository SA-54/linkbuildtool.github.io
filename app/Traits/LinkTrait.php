<?php


namespace App\Traits;

use App\Link;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

trait LinkTrait
{
    /**
     * Store a new link
     *
     * @param Request $request
     * @return Model
     */
    protected function linkCreate(Request $request)
    {
        $user = Auth::user();

        $link = new Link;

        $link->user_id = $user->id ?? 0;

        return $this->model($request, $link, 0);
    }

    /**
     * Store multiple new links
     *
     * @param Request $request
     * @return array
     */
    protected function linksCreate(Request $request)
    {
        $user = Auth::user();

        $urls = preg_split('/\n|\r/', $request->input('urls'), -1, PREG_SPLIT_NO_EMPTY);

        $now = Carbon::now();

        $data = [];
        foreach ($urls as $url) {
            $metadata = $this->parseUrl($request);

            $data[] = ['user_id' => $user->id, 'url' => $url, 'alias' => $this->generateAlias(), 'title' => !empty($metadata) && isset($metadata['title']) ? trim($metadata['title']) : null, 'space_id' => $request->input('space') ?? null, 'domain_id' => $request->input('domain') ?? null, 'created_at' => $now, 'updated_at' => $now];
        }

        Link::insert($data);

        return $data;
    }

    /**
     * Update the link
     *
     * @param Request $request
     * @param Model $link
     * @return Link|Model
     */
    protected function linkUpdate(Request $request, Model $link)
    {
        return $this->model($request, $link, 1);
    }

    /**
     * Create or update the model
     *
     * @param Request $request
     * @param Model $link the query type, 0 for create, 1 for update
     * @param int $type
     * @return Model
     */
    private function model(Request $request, Model $link, int $type)
    {
        $metadata = $this->parseUrl($request);

        if ($request->has('url')) {
            $link->url = $request->input('url');
            $link->title = !empty($metadata) && isset($metadata['title']) ? trim($metadata['title']) : null;
        }

        if ($type == 0) {
            $link->alias = $request->input('alias') ?? $this->generateAlias();
        } else {
            if ($request->has('alias')) {
                $link->alias = $request->input('alias');
            }
        }

        if ($request->has('disabled')) {
            $link->disabled = $request->input('disabled');
        }

        if ($request->has('public')) {
            $link->public = $request->input('public');
        }

        if ($request->has('space')) {
            $link->space_id = $request->input('space');
        }

        if ($type == 0) {
            if ($request->has('domain')) {
                $link->domain_id = $request->input('domain');
            }
        }

        if ($request->has('expiration_url')) {
            $link->expiration_url = $request->input('expiration_url');
        }

        if ($request->has('expiration_date') && $request->has('expiration_time')) {
            $link->ends_at = $request->input('expiration_date') && $request->input('expiration_time') ? Carbon::createFromFormat('Y-m-d H:i', $request->input('expiration_date').' '.$request->input('expiration_time'), Auth::user()->timezone ?? config('app.timezone'))->tz(config('app.timezone'))->toDateTimeString() : null;
        }

        if ($request->has('expiration_clicks')) {
            $link->expiration_clicks = $request->input('expiration_clicks');
        }

        if ($request->has('password')) {
            $link->password = $request->input('password') !== $link->password ? (!empty($request->input('password')) ? Hash::make($request->input('password')) : null) : $link->password;
        }

        if ($request->has('target_type')) {
            $link->target_type = $request->input('target_type');
        }

        if ($request->has('geo')) {
            $link->geo_target = array_filter(array_map('array_filter', array_values($request->input('geo')))) ?? null;
        }

        if ($request->has('platform')) {
            $link->platform_target = array_filter(array_map('array_filter', array_values($request->input('platform')))) ?? null;
        }

        if ($request->has('rotation')) {
            $link->rotation_target = array_filter(array_map('array_filter', array_values($request->input('rotation')))) ?? null;
        }

        $link->save();

        return $link;
    }

    /**
     * Generate a random unique alias
     *
     * @return string|null
     */
    private function generateAlias()
    {
        $alias = null;
        $unique = false;
        $fails = 0;

        while (!$unique) {
            if ($fails > 10) {
                $alias = $this->generateString(6);
            } elseif ($fails > 20) {
                $alias = $this->generateString(7);
            } else {
                $alias = $this->generateString(5);
            }

            // Check if the alias exists
            if(!Link::where('alias', '=', $alias)->exists()) {
                $unique = true;
            }

            $fails++;
        }

        return $alias;
    }

    /**
     * Generate a random string
     *
     * @param int $length
     * @return string
     */
    private function generateString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Parse the contents of a given URL
     *
     * @param Request $request
     * @return array
     */
    private function parseUrl(Request $request)
    {
        $metadata = [];

        $httpClient = new HttpClient();

        try {
            $content = $httpClient->request('GET', $request->input('url'), [
                'timeout' => 5,
                'http_errors' => false,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36'
                ],
                'on_headers' => function (ResponseInterface $response) {
                    if ($response->getHeaderLine('Content-Length') > 2097152) {
                        throw new \Exception('The file size exceeded the limits.');
                    }
                }
            ]);

            $headerType = $content->getHeader('content-type');
            $parsed = \GuzzleHttp\Psr7\parse_header($headerType);
            $metadata = $this->formatMetaTags(mb_convert_encoding($content->getBody(), 'UTF-8', $parsed[0]['charset'] ?? 'UTF-8'));
        } catch (\Exception $e) {
        }

        return $metadata;
    }

    /**
     * Parse and format the meta tags.
     *
     * @param $value
     * @return array|false
     */
    public function formatMetaTags($value)
    {
        $array = [];

        // Match the meta tags
        $pattern = '
            ~<\s*meta\s
        
            # using lookahead to capture type to $1
            (?=[^>]*?
            \b(?:name|property|http-equiv)\s*=\s*
            (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
            ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
            )
        
            # capture content to $2
            [^>]*?\bcontent\s*=\s*
            (?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
            ([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
            [^>]*>
        
            ~ix';
        if(preg_match_all($pattern, $value, $out)) {
            $array = array_combine(array_map('strtolower', $out[1]), $out[2]);
        }

        // Match the title tags
        preg_match("/<title[^>]*>(.*?)<\/title>/is", $value, $title);
        $array['title'] = $title[1];

        // Return the result
        return $array;
    }
}