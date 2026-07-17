@foreach (session('flash_notification', []) as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        @php
            $styles = [
                'success' => 'bg-green-50 border-green-500 text-green-700',
                'error'   => 'bg-red-50 border-red-500 text-red-700',
                'danger'  => 'bg-red-50 border-red-500 text-red-700',
                'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-700',
                'info'    => 'bg-blue-50 border-blue-500 text-blue-700',
            ];
            $level = $message['level'] ?? 'info';
        @endphp

        <div class="border-l-4 rounded-b px-4 py-3 mt-8 mb-4 flex items-center justify-between {{ $styles[$level] ?? $styles['info'] }}"
             role="alert"
        >
            <span>{!! $message['message'] !!}</span>

            @if ($message['important'])
                <button type="button"
                        class="ml-4 -mr-1 font-bold text-xl leading-none focus:outline-none"
                        onclick="this.parentElement.remove()"
                        aria-hidden="true"
                >&times;</button>
            @endif
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
