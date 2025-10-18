<div class="notification-panel">
    @foreach(auth()->user()->notifications as $notification)
        <div class="p-4 mb-4 bg-white rounded-lg shadow {{ $notification->read_at ? 'opacity-75' : '' }}">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $notification->data['title'] }}
                </h3>
                <span class="text-sm text-gray-500">
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
            <p class="mt-2 text-gray-600">{{ $notification->data['message'] }}</p>
            @if(isset($notification->data['remarks']) && $notification->data['remarks'])
                <p class="mt-2 text-sm text-gray-500">
                    <strong>Remarks:</strong> {{ $notification->data['remarks'] }}
                </p>
            @endif
            <div class="mt-3 text-sm text-gray-500">
                <p>Subject: {{ $notification->data['subject'] }}</p>
                <p>Date: {{ $notification->data['date'] }}</p>
                <p>Time: {{ $notification->data['time'] }}</p>
            </div>
            @unless($notification->read_at)
                <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                        Mark as read
                    </button>
                </form>
            @endunless
        </div>
    @endforeach
</div>