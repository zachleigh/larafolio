<div class="section__item">
    <div class="">
        Name: <b>{{ $link->name() }}</b>
    </div>
    <div class="section__indented">
        Text: {{ $link->text() }}
    </div>
    <div class="section__indented">
        URL: 
        <a href="{{ $link->url() }}">
            {{ $link->url() }}
        </a>
    </div>
    <link-status
        url="{{ $link->url() }}"
        :check="{{ json_encode(config('larafolio.url_validation')) }}"
    >
    </link-status>
</div>
                            