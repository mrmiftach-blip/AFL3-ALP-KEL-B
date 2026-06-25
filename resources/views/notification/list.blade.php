@extends('layouts.app')

@section('content')
    <div class="container py-4" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Notifikasi</h2>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ auth()->user()->unreadNotifications->count() }} Baru
            </span>
        </div>

        <div class="card shadow-sm border-0">
            <div class="list-group list-group-flush">
                @forelse(auth()->user()->notifications as $notification)
                    @php
                        $isRead = $notification->read_at !== null;
                    @endphp
                    <a href="{{ route('notification.read', ['id' => $notification->id]) }}"
                        class="list-group-item list-group-item-action p-4 border-bottom {{ $isRead ? 'bg-white' : 'bg-primary bg-opacity-10' }}"
                        style="{{ !$isRead ? 'border-left: 4px solid var(--bs-primary) !important;' : 'border-left: 4px solid transparent !important;' }} transition: all 0.2s ease;">

                        <div class="d-flex align-items-start gap-3">
                            <div class="mt-1">
                                @if($isRead)
                                    <i class="bi bi-envelope-open text-secondary fs-4"></i>
                                @else
                                    <i class="bi bi-envelope-fill text-primary fs-4"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 {{ $isRead ? 'text-secondary fw-normal' : 'text-dark fw-bold' }}"
                                    style="line-height: 1.5;">
                                    {{ $notification->data['text'] ?? 'Ada pemberitahuan baru' }}
                                </h6>
                                <small class="{{ $isRead ? 'text-muted' : 'text-primary fw-medium' }}">
                                    <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @if(!$isRead)
                                <span class="mt-2 badge bg-primary rounded-circle p-1" style="width: 10px; height: 10px;">
                                    <span class="visually-hidden">Unread</span>
                                </span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="card-body text-center py-5">
                        <i class="bi bi-bell-slash text-muted opacity-50" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-dark fw-bold">Belum Ada Notifikasi</h5>
                        <p class="text-secondary mb-0">Pemberitahuan mengenai status lamaran dan aktivitas lainnya akan muncul
                            di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection