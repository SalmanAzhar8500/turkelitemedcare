@extends('layouts.site')

@section('title', $page->seo_title ?: ($page->title.' | '.website_setting('brand.name', 'Turkelite Medcare')))
@section('description', $page->seo_description ?: ($page->description ?: __('site.hero_lead')))
@section('robots', $page->seo_robots ?: 'index,follow,max-image-preview:large,max-snippet:-1')

@section('content')
    @php($concept = current_design_concept())
    @includeIf('pages.home-concepts.'.$concept)
@endsection
