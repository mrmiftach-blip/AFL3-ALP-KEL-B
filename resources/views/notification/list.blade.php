@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2>Notifikasi</h2>
        <ul class="list-group mt-3">
            @forelse(auth()->user()->notifications as $notification)
                <a href="{{ route('notification.read', ['id' => $notification->id]) }}"
                    class="list-group-item list-group-item-action {{ $notification->read_at ? 'text-muted opacity-50 bg-light' : 'fw-bold' }}">
                    {{ $notification->data['text'] ?? 'Ada pemberitahuan baru' }}
                    <br>
                    <small class="text-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                </a>
            @empty
                <div class="alert alert-info">
                    Belum ada notifikasi saat ini.
                </div>
            @endforelse
        </ul>
    </div>
@endsection