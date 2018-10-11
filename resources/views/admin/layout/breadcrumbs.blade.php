@if ($breadcrumbs)
    <ul class="breadcrumb p-0 m-0">
        @foreach ($breadcrumbs as $breadcrumb)
            @if(isset($breadcrumb->icon))
                {!! $breadcrumb->icon !!}
            @endif
            @if (!$loop->last)
                <li class="breadCrumbs_arrow"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li class="active">{{ $breadcrumb->title }}</li>
            @endif
        @endforeach
    </ul>
@endif


