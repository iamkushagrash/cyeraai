@extends('layouts.user-mecha')

@section('title', 'Ticket Discussion - Cyera AI')
@section('page-title', 'Ticket Discussion')
@section('page-icon', 'fas fa-comments')

@section('page-actions')
<a href="{{ url('/User/ViewTicket') }}" class="mecha-btn-outline" style="padding: 4px 10px; font-size: 9px; height: auto;">
    <i class="fas fa-arrow-left"></i> ALL TICKETS
</a>
@endsection

@section('content')
<div class="mecha-hud-card" style="max-width: 680px; margin: 0 auto;">
    <div class="mecha-card-header" style="border-bottom: 1px solid rgba(229, 168, 35, 0.3); padding-bottom: 12px; margin-bottom: 14px;">
        <div class="mecha-card-title-wrap">
            <i class="fas fa-ticket" style="color: #FFD700;"></i>
            <div>
                <h2 class="mecha-card-title">{{ $viewticket[0]->title ?? 'Ticket Discussion' }}</h2>
                <div class="mecha-card-subtitle">Ticket #{{ $viewticket[0]->subid ?? '' }} &bull; {{ $viewticket[0]->sub ?? '' }}</div>
            </div>
        </div>
        <span class="mecha-badge-yellow">THREAD</span>
    </div>

    <!-- Messages Chat Stream -->
    <div style="display: flex; flex-direction: column; gap: 12px; max-height: 440px; overflow-y: auto; padding: 6px 4px 16px 4px;">
        @foreach($viewticket as $view)
            @if($view->ustatus == 0)
                <!-- User Bubble (Right) -->
                <div style="align-self: flex-end; max-width: 82%; background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), rgba(245, 166, 35, 0.25)); border: 1px solid rgba(255, 215, 0, 0.45); border-radius: 12px 12px 2px 12px; padding: 10px 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                    <div style="font-size: 11.5px; color: #FFF; line-height: 1.5;">
                        {!! $view->htext !!}
                    </div>
                    <div style="font-size: 8px; color: #FFE082; text-align: right; margin-top: 4px;">
                        <i class="fas fa-user"></i> You &bull; {{ $view->created_at }}
                    </div>
                </div>
            @else
                <!-- Support Bubble (Left) -->
                <div style="align-self: flex-start; max-width: 82%; background: linear-gradient(135deg, rgba(0, 229, 255, 0.1), rgba(0, 255, 136, 0.15)); border: 1px solid rgba(0, 255, 136, 0.4); border-radius: 12px 12px 12px 2px; padding: 10px 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.6);">
                    <div style="font-size: 9px; font-weight: 800; color: #00FF88; margin-bottom: 3px;">
                        <i class="fas fa-headset"></i> CYERA SUPPORT
                    </div>
                    <div style="font-size: 11.5px; color: #E2E8F0; line-height: 1.5;">
                        {!! $view->htext !!}
                    </div>
                    <div style="font-size: 8px; color: #94A3B8; margin-top: 4px;">
                        {{ $view->created_at }}
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Reply Box Form -->
    <form action="{{ url('/User/ReplyTicket') }}" method="POST" style="border-top: 1px solid rgba(229, 168, 35, 0.25); padding-top: 16px; margin-top: 8px;">
        @csrf
        <input type="hidden" name="ticket" value="{{ $viewticket[0]->subid }}">

        <div class="mecha-form-group">
            <label class="mecha-form-label" for="textmsg">
                <span>Post a Reply</span>
            </label>
            <div class="mecha-input-wrap">
                <textarea name="textmsg" id="textmsg" class="mecha-input-control @error('textmsg') is-invalid @enderror" rows="3" style="height: auto; padding: 10px 14px;" placeholder="Type your response to support..." required></textarea>
            </div>
            @error('textmsg')
                <small style="color: #FF4D7D; font-size: 10px;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 14px;">
            <a href="{{ url('/User/ViewTicket') }}" class="mecha-btn-outline" style="width: auto; height: 40px; padding: 0 16px;">
                BACK
            </a>
            <button type="submit" class="mecha-btn-gold" style="width: auto; height: 40px; padding: 0 20px;">
                <i class="fas fa-paper-plane"></i> SEND REPLY
            </button>
        </div>
    </form>
</div>
@endsection