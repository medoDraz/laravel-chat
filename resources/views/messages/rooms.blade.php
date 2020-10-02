@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Rooms Chat
                        <a href="{{ route('room.chat.add.form') }}" class="float-right"
                           style="display: inline-block; "><i
                                class="fa fa-plus"></i></a>
                    </div>

                    <div class="card-body">
                        @if($rooms->count() > 0)
                            <div class="list-group" style="border: none">
                                @foreach($rooms as $room)

                                    <a href="{{ route('messages.index',$room->id) }}"
                                       class="list-group-item list-group-item-action">
                                        {{ $room->name }}
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <h2>Sorry There is not Chat Rooms yet...</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
