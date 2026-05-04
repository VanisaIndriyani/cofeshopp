@include('layouts.admin', [
    'title' => $title ?? null,
    'header' => $header ?? null,
    'subtitle' => $subtitle ?? null,
    'slot' => $slot,
])
