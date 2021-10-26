<?php

namespace Spatie\TagsField\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Tags\Tag;

class TagsFieldController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $query = resolve(config('tags.tag_model', Tag::class))->query();

        if ($request->has('filter.containing')) {
            $query->containing($request['filter']['containing']);
        }

        if ($request->has('filter.type')) {
            $query->where('type', $request['filter']['type']);
        }

        if ($request->has('filter.subdomainId')) {
            $query->where('subdomain_id', $request['filter']['subdomainId']);
        }

        if ($request->has('limit')) {
            $query->limit($request['limit']);
        }

        $sorted = $query->get()->sortBy(function (Tag $tag) {
            return strtolower($tag->name);
        })->values();

        $suggested = $sorted->map(function (Tag $tag) {
            return $tag->name;
        });

        if(config('tags.enable_permanent_suggestions')){
            $permanentSuggestions = collect(config('tags.permanent_suggestions_list', []));

            $suggested = $suggested->reject(function($tagName) use($permanentSuggestions) {
                return $permanentSuggestions->contains($tagName);
            });

            foreach($permanentSuggestions->sort()->reverse() as $s){
                $suggested->prepend($s);
            }
        }

        return $suggested;
    }
}
