@section('css')
    <style>
        .x-sidebar {
            border-radius: 2rem;
            color: var(--bs-light);
        }

        .x-sidebar .list-group .list-group-item {
            color: var(--bs-light);
            /* border-color: var(--bs-light); */
            background-color: transparent;
            border-color: transparent;
            font-weight: bold;
        }

        .x-sidebar .list-group .list-group-item:hover,
        .x-sidebar .list-group .list-group-item.active {
            color: var(--bs-secondary);
        }
    </style>
@endsection

<div class="x-sidebar-wrapper col-md-4 col-lg-3 col-xl-2 min-vh-100 p-3 d-flex align-items-stretch">
    <div class="w-100 h-auto x-sidebar bg-primary p-4 d-flex flex-column gap-3">
        <div class="title-wrapper text-center">
            <h1>Project W</h1>
        </div>
        <div class="list-group list-group-flush">
            @foreach ($links as $linkItem)
                <a href="{{$linkItem['url']}}" class="list-group-item {{$linkItem['active']? 'active': ''}}">
                    <div class="row">
                        @isset($linkItem["icon"])
                            <div class="col-2">
                                <i class="{{$linkItem['icon']}}"></i>
                            </div>
                        @endisset
                        <div class="col">{{$linkItem["label"]}}</div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="list-group mt-auto">
            <a href="#" class="list-group-item">
                <div class="row align-items-center">
                    <div class="col-4">
                        <img src="https://boredhumans.b-cdn.net/faces2/{{rand(1,500)}}.jpg" alt="John Doe" class="w-100 ratio ratio-1x1 rounded-circle">
                    </div>
                    <div class="col">John Doe</div>
                </div>
            </a>
        </div>
    </div>
</div>

