<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "url": "https://{{ request()->getHttpHost() }}",
        "name": "{{ App\Repositories\Base\StringClearRepository::clearFroJsonLd(\App\W::$metaTitle) }}",
        "description": "{{ App\Repositories\Base\StringClearRepository::clearFroJsonLd(\App\W::$metaDescription) }}"
    }
</script>

@if(!empty(\App\W::$breadcrumbs))
    <?php
    $jsonBreadcrumb = [
        '@context'        => "https://schema.org",
        '@type'           => "BreadcrumbList",
        'itemListElement' => []
    ];
    $position = 0;
    foreach (\App\W::$breadcrumbs as $breadcrumb){
        $position++;
        $jsonBreadcrumb['itemListElement'][] = [
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => App\Repositories\Base\StringClearRepository::clearFroJsonLd($breadcrumb->name),
            'item'     => $breadcrumb->url == '#' ? request()->url : $breadcrumb->url,
        ];
    }

    ?>
	<script type="application/ld+json">
		{!! json_encode($jsonBreadcrumb, JSON_UNESCAPED_UNICODE) !!}
	</script>
@endif
