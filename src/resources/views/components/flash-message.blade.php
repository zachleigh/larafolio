@if (session()->has('flash_message'))
    <flash-message
        title="{{ session('flash_message')['title'] }}"
        message="{{ session('flash_message')['message'] }}"
        type="{{ session('flash_message')['type'] }}"
    ></flash-message>
@endif

<flash-message></flash-message>